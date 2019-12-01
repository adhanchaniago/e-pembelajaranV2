<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
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
		$this->load->model('Report_model', 'report');
		$this->load->model('Dashboard_model', 'dashboard');
		$this->load->model('Soal_model', 'soal');
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');

		$this->user = $this->ion_auth->user()->row();
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
			show_error('Halaman ini khusus untuk siswa mengikuti tugas, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
	}

	// untuk mengencode data menjadi json
	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}


	public function json($id = null)
	{
		$this->akses_guru();

		$this->output_json($this->tugas->getDataTugas($id), false);
	}

	public function data($id_kelas, $kelas)
	{
		$user = $this->ion_auth->user()->row();
		$guru = $this->report->getGuru($user->username)->row();
		$topik = $this->master->getTopikByMapel(['mapel_id' => $guru->mapel_id, 'kelas' => $kelas], true);
		echo $this->output_json($this->report->getDataReport($guru->id_guru, $guru->mapel_id, $id_kelas, $topik), false);
	}

	// fungsi untuk menampilkan seluruh daftar tugas yang dibuat untuk guru
	public function index()
	{
		$this->akses_guru();

		$user = $this->ion_auth->user()->row();
		$data = [
			'user' => $user,
			'judul'	=> 'Laporan',
			'subjudul' => 'Laporan Kelas',
			'guru' => $this->report->getGuru($user->username)->row()
		];

		$kelas_id = explode(',', $data['guru']->kelas_id);
		$data['kelas'] = $this->dashboard->getKelas($kelas_id)->result();
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('report/report_mapel');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function convert_tgl($tgl)
	{
		$this->akses_guru();
		return date('Y-m-d H:i:s', strtotime($tgl));
	}


	//  fungsi untuk jquery mengambil data
	public function list_json()
	{
		$this->akses_siswa();

		$list = $this->tugas->getListTugas($this->mhs->id_siswa, $this->mhs->kelas_id);
		$this->output_json($list, false);
	}

	// ini fungsi untuk menampilkan daftar tugas di siswa
	public function list()
	{
		$this->akses_siswa();

		$user = $this->ion_auth->user()->row();

		$data = [
			'user' 		=> $user,
			'judul'		=> 'Tugas',
			'subjudul'	=> 'List Tugas',
			'mhs' 		=> $this->tugas->getIdSiswa($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('tugas/list');
		$this->load->view('_templates/dashboard/_footer.php');
	}
}
