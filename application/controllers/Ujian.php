<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ujian extends CI_Controller
{

	public $mhs, $user;

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->helper('my');
		// $this->load->model('Master_model', 'master');
		$this->load->model('Topik_model', 'topik');
		$this->load->model('Ujian_model', 'ujian');
		$this->load->model('Kelas_model', 'kelas');
		$this->form_validation->set_error_delimiters('', '');

		$this->user = $this->ion_auth->user()->row();
		$this->mhs 	= $this->ujian->getIdSiswa($this->user->username);
	}

	public function akses_guru()
	{
		if (!$this->ion_auth->in_group('guru')) {
			show_error('Halaman ini khusus untuk guru untuk membuat Test Online, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
	}

	public function akses_siswa()
	{
		if (!$this->ion_auth->in_group('siswa')) {
			show_error('Halaman ini khusus untuk siswa mengikuti ujian, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
	}

	// untuk mengencode data menjadi json
	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}


	public function data()
	{
		$this->akses_guru();
		$user = $this->ion_auth->user()->row();
		$data['guru'] = $this->ujian->getIdGuru($user->username);
		$this->output_json($data['kelas'] = $this->ujian->getKelas($data['guru']->kelas_id), false);
	}

	public function nilai($id_kelas)
	{
		$user = $this->ion_auth->user()->row();
		$data = [
			'user' => $this->user,
			'judul'	=> 'Ujian',
			'subjudul' => 'Detail Hasil Ujian',
			'guru' => $this->ujian->getIdGuru($user->username),
			'id_kelas' => $id_kelas
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function nilai_ujian($id_kelas)
	{
		$user = $this->ion_auth->user()->row();
		$data['guru'] = $this->ujian->getIdGuru($user->username);
		$this->output_json($this->ujian->getDataUjian($data['guru']->id_guru, $data['guru']->mapel_id, $id_kelas), false);
	}

	// fungsi untuk menampilkan seluruh daftar ujian yang dibuat untuk guru
	public function master()
	{
		$this->akses_guru();
		$user = $this->ion_auth->user()->row();
		$data = [
			'user' => $user,
			'judul'	=> 'Ujian',
			'subjudul' => 'Data Ujian',
			'guru' => $this->ujian->getIdGuru($user->username),
		];
		$kelas_id = explode(',', $data['guru']->kelas_id);
		$data['kelas'] = $this->kelas->getKelas($kelas_id)->result();
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function save()
	{
		$id_kelas 		= $this->input->post('id_kelas', true);
		$id_guru 		= $this->input->post('id_guru', true);
		$id_mapel 		= $this->input->post('id_mapel', true);

		$rows 			= count($this->input->post('id_ujian', true));
		for ($i = 0; $i < $rows; $i++) {
			$insert = array(
				'guru_id' 	=> $id_guru,
				'kelas_id' 	=> $id_kelas,
				'mapel_id' 	=> $id_mapel,
				'id_siswa' 	=> $this->input->post('id_siswa[' . $i . ']', true),
				'nilai_uts' 		=> $this->input->post('uts[' . $i . ']', true),
				'nilai_uas'	 	=> $this->input->post('uas[' . $i . ']', true)
			);
			if ($this->input->post('id_ujian[' . $i . ']', true) == 0) {
				$action = $this->ujian->create('ujian', $insert);
			} else {
				$action = $this->ujian->update('ujian', $insert, 'id_ujian', $this->input->post('id_ujian[' . $i . ']', true));
			}
		}
		$data['status'] = $action ? TRUE : FALSE;
		$this->output_json($data);
	}
}
