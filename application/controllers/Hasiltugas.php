<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once('application/libraries/vendor/autoloader.php');


use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Similarity\CosineSimilarity;

class HasilTugas extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}

		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		// $this->load->model('Master_model', 'master');
		$this->load->model('Tugas_model', 'tugas');
		$this->load->model('Kuis_model', 'kuis');

		$this->user = $this->ion_auth->user()->row();
	}

	public function akses_guru()
	{
		if (!$this->ion_auth->in_group('guru')) {
			show_error('Halaman ini khusus untuk guru untuk membuat Test Online, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
	}
	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function data()
	{
		$nip_guru = null;
		if ($this->ion_auth->in_group('guru')) {
			$nip_guru = $this->user->username;
		}
		$this->output_json($this->tugas->getHasilTugas($nip_guru), false);
	}

	public function data_kuis()
	{
		$nip_guru = null;
		if ($this->ion_auth->in_group('guru')) {
			$nip_guru = $this->user->username;
		}
		$this->output_json($this->kuis->getHasilTugas($nip_guru), false);
	}

	public function NilaiMhs($id)
	{

		$this->output_json($this->tugas->HslTugasById($id, true), false);
	}

	public function NilaiMhs_kuis($id)
	{

		$this->output_json($this->kuis->HslTugasById($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Tugas',
			'subjudul' => 'Hasil Tugas',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('tugas/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function index_kuis()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Kuis',
			'subjudul' => 'Hasil Kuis',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('kuis/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function detail($id)
	{
		$tugas = $this->tugas->getTugasById($id);
		$nilai = $this->tugas->bandingNilai($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Tugas',
			'subjudul' => 'Detail Hasil Tugas',
			'tugas'	=> $tugas,
			'nilai'	=> $nilai
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('tugas/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function detail_kuis($id)
	{
		$tugas = $this->tugas->getTugasById($id);
		$nilai = $this->tugas->bandingNilai($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Kuis',
			'subjudul' => 'Detail Hasil Kuis',
			'tugas'	=> $tugas,
			'nilai'	=> $nilai
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('kuis/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function essay($id)
	{
		$essay = $this->tugas->getHasilEssay($id)->row();

		$data = [
			'user' => $this->user,
			'judul'	=> 'Hasil',
			'subjudul' => 'Hasil Essay',
			'essay'	=> $essay,
			'plagiasi' => $this->cekplagiat($essay->id_soal_essay, $essay->id, $essay->list_jawaban)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('tugas/hasil_essay');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function dummy_essay($id)
	{
		$essay = $this->tugas->getHasilEssay($id)->row();
		$hasil = $this->cekplagiat($essay->id_soal_essay, $essay->id, $essay->list_jawaban);
		$this->output_json($hasil);
	}

	public function essay_kuis($id)
	{
		$essay = $this->kuis->getHasilEssay($id)->row();

		$data = [
			'user' => $this->user,
			'judul'	=> 'Hasil',
			'subjudul' => 'Hasil Essay',
			'essay'	=> $essay,
			'plagiasi' => $this->cekplagiat($essay->id_soal_essay, $essay->id, $essay->list_jawaban)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('kuis/hasil_essay');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	function cekplagiat($id_soal_essay, $id, $jawaban)
	{
		$jwb_A = strip_tags($jawaban);
		//memanggil jawaban
		$jwb_B = $this->kuis->getAllJawabanByIdSoal($id_soal_essay, $id)->result();
		$tokenizer = new WhitespaceTokenizer();
		$cosine = new CosineSimilarity();
		$tok_A = $tokenizer->tokenize($jwb_A);
		$data = null;
		foreach ($jwb_B as $hasil) {
			$tok_B = $tokenizer->tokenize(strip_tags($hasil->list_jawaban));
			$hasil_plagiasi = $cosine->similarity($tok_A, $tok_B);
			$data[] = array(
				'nama' => $hasil->nama,
				'jawaban' => $hasil->list_jawaban,
				'hasil' => $hasil_plagiasi
			);
		}
		return $data;
	}


	public function savenilai()
	{
		$this->form_validation->set_rules('nilai', 'Nilai', 'numeric');
		$id = $this->input->post('id');
		$nilai = $this->input->post('nilai');
		if ($this->form_validation->run() === FALSE) {
			$data['status'] = FALSE;
			$data['errors'] = [
				'nilai' 	=> form_error('nilai')
			];
		} else {
			$input = [
				'id' 		=> $id,
				'nilai' 	=> $nilai
			];
			$action = $this->tugas->update('hasil_tugas', $input, 'id', $id);
			$data['status'] = $action ? TRUE : FALSE;
		}
		$this->output_json($data);
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->tugas->getIdSiswa($this->user->username);
		$hasil 	= $this->tugas->HslTugas($id, $mhs->id_siswa)->row();
		$tugas 	= $this->tugas->getTugasById($id);

		$data = [
			'tugas' => $tugas,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];

		$this->load->view('tugas/cetak', $data);
	}

	public function cetak_kuis($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->kuis->getIdSiswa($this->user->username);
		$hasil 	= $this->kuis->HslTugas($id, $mhs->id_siswa)->row();
		$tugas 	= $this->kuis->getTugasById($id);

		$data = [
			'tugas' => $tugas,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];

		$this->load->view('kuis/cetak', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$tugas = $this->tugas->getTugasById($id);
		$nilai = $this->tugas->bandingNilai($id);
		$hasil = $this->tugas->HslTugasById($id)->result();

		$data = [
			'tugas'	=> $tugas,
			'nilai'	=> $nilai,
			'hasil'	=> $hasil
		];

		$this->load->view('tugas/cetak_detail', $data);
	}

	public function cetak_detail_kuis($id)
	{
		$this->load->library('Pdf');

		$tugas = $this->kuis->getTugasById($id);
		$nilai = $this->kuis->bandingNilai($id);
		$hasil = $this->kuis->HslTugasById($id)->result();

		$data = [
			'tugas'	=> $tugas,
			'nilai'	=> $nilai,
			'hasil'	=> $hasil
		];

		$this->load->view('kuis/cetak_detail', $data);
	}
}
