<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}
		$this->load->model('Dashboard_model', 'dashboard');
		$this->user = $this->ion_auth->user()->row();
	}

	public function admin_box()
	{
		$box = [
			[
				'box' 		=> 'light-blue',
				'total' 	=> $this->dashboard->total('jurusan'),
				'title'		=> 'Jurusan',
				'icon'		=> 'graduation-cap'
			],
			[
				'box' 		=> 'olive',
				'total' 	=> $this->dashboard->total('kelas'),
				'title'		=> 'Kelas',
				'icon'		=> 'building-o'
			],
			[
				'box' 		=> 'yellow-active',
				'total' 	=> $this->dashboard->total('guru'),
				'title'		=> 'Guru',
				'icon'		=> 'user-secret'
			],
			[
				'box' 		=> 'red',
				'total' 	=> $this->dashboard->total('siswa'),
				'title'		=> 'Siswa',
				'icon'		=> 'user'
			],
		];
		$info_box = json_decode(json_encode($box), FALSE);
		return $info_box;
	}

	public function index()
	{
		$user = $this->user;
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Dashboard',
			'subjudul'	=> 'Data Aplikasi',
		];

		if ($this->ion_auth->is_admin()) {
			$data['info_box'] = $this->admin_box();
		} elseif ($this->ion_auth->in_group('guru')) {
			$mapel = ['mapel' => 'guru.mapel_id=mapel.id_mapel']; //join
			$data['guru'] = $this->dashboard->get_where('guru', 'nip', $user->username, $mapel)->row();
			$kelas_id = explode(',', $data['guru']->kelas_id);
			$data['kelas'] = $this->dashboard->getKelas($kelas_id)->result();
		} else {
			$join = [
				'kelas b' 	=> 'a.kelas_id = b.id_kelas',
				'jurusan c'	=> 'b.jurusan_id = c.id_jurusan'
			];
			$data['siswa'] = $this->dashboard->get_where('siswa a', 'nis', $user->username, $join)->row();
		}

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('dashboard');
		$this->load->view('_templates/dashboard/_footer.php');
	}
}
