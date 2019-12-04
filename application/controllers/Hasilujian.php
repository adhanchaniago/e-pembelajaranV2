<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once('application/libraries/vendor/autoloader.php');


use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Similarity\CosineSimilarity;

class HasilUjian extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}

		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		// $this->load->model('Master_model', 'master');
		$this->load->model('Ujian_model', 'ujian');

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

		$this->output_json($this->ujian->getHasilUjian($nip_guru), false);
	}

	public function NilaiMhs($id)
	{
		$this->output_json($this->ujian->HslUjianById($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Ujian',
			'subjudul' => 'Hasil Ujian',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function detail($id)
	{
		$ujian = $this->ujian->getUjianById($id);
		$nilai = $this->ujian->bandingNilai($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Ujian',
			'subjudul' => 'Detail Hasil Ujian',
			'ujian'	=> $ujian,
			'nilai'	=> $nilai
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function essay($id)
	{
		$essay = $this->ujian->getHasilEssay($id)->row();
		$data = [
			'user' => $this->user,
			'judul'	=> 'Hasil',
			'subjudul' => 'Hasil Essay',
			'essay'	=> $essay,
			'plagiasi' => $this->cekplagiat($essay->id_soal_essay, $essay->id, $essay->list_jawaban)
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/hasil_essay');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	function cekplagiat($id_soal_essay, $id, $jawaban)
	{
		$jwb_A = strip_tags($jawaban);
		//memanggil jawaban
		$jwb_B = $this->ujian->getAllJawabanByIdSoal($id_soal_essay, $id)->result();
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
			$action = $this->ujian->update('hasil_ujian', $input, 'id', $id);
			$data['status'] = $action ? TRUE : FALSE;
		}
		$this->output_json($data);
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->ujian->getIdSiswa($this->user->username);
		$hasil 	= $this->ujian->HslUjian($id, $mhs->id_siswa)->row();
		$ujian 	= $this->ujian->getUjianById($id);

		$data = [
			'ujian' => $ujian,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];

		$this->load->view('ujian/cetak', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$ujian = $this->ujian->getUjianById($id);
		$nilai = $this->ujian->bandingNilai($id);
		$hasil = $this->ujian->HslUjianById($id)->result();

		$data = [
			'ujian'	=> $ujian,
			'nilai'	=> $nilai,
			'hasil'	=> $hasil
		];

		$this->load->view('ujian/cetak_detail', $data);
	}
}
