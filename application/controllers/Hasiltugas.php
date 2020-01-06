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
		$this->load->library('unit_test');

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

	//===============================================================
	//-----------------------PENGUJIAN UNIT 1------------------------
	//===============================================================

	public function savenilai_test($n = null)
	{
		$form = array(
			'id' => 13,
			'nilai' => $n
		);

		$this->form_validation->set_data($form);
		$this->form_validation->set_rules('nilai', 'Nilai', 'required|numeric');
		$id = $form['id'];
		$nilai = $form['nilai'];
		if ($this->form_validation->run() === FALSE) {
			$data['status'] = FALSE;
		} else {
			$input = [
				'id' 		=> $id,
				'nilai' 	=> $nilai
			];
			$action = $this->tugas->update('hasil_tugas', $input, 'id', $id);
			$data['status'] = $action ? TRUE : FALSE;
		}
		return $data;
	}

	public function unit_test_A_1() //TEST CASE 1 : NILAI TIDAK DIISI (GAGAL)
	{
		$test = $this->savenilai_test(); //parameter nilai kosong
		// print_r($test);
		$data['status'] = FALSE;
		$expected_result = $data;
		$test_name = 'SAVE NILAI GAGAL';
		echo $this->unit->run($test, $expected_result, $test_name);
	}

	public function unit_test_A_2() //TEST CASE 1 : NILAI DIISI 90 (BERHASIL)
	{
		$test = $this->savenilai_test(90); //parameter nilai diisi
		// print_r($test);
		$data['status'] = TRUE;
		$expected_result = $data;
		$test_name = 'SAVE NILAI BERHASIL';
		echo $this->unit->run($test, $expected_result, $test_name);
	}

	//===============================================================
	//-----------------------PENGUJIAN UNIT 3------------------------
	//===============================================================

	function cekplagiat_test($id_soal_essay, $id, $jawaban)
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

	public function unit_test_C_1() //TEST CASE 1 : (DATA PEMBANDING KOSONG) 
	{
		$test = $this->cekplagiat_test(4, 13, "<p>Saya harus lulus</p>"); //isi parameter 1 dengan id tugas, parameter 2 dengan id hasil_tugas, parameter 3 dengan jawaban yang akan dicek
		$expected_result = $test;
		// print_r($test);
		// $expected_result[] = array(
		// 	"nama" => "Muhammad Rayyan",
		// 	"jawaban" => "<p>Saya harus lulus</p>",
		// 	"hasil" => 1
		// );
		// print_r($expected_result);
		$test_name = 'CEK PLAGIASI TIDAK ADA DATA PEMBANDING';
		echo $this->unit->run($test, $expected_result, $test_name);
	}

	public function unit_test_C_2() //TEST CASE 2 : (DATA PEMBANDING ADA)
	{
		$test = $this->cekplagiat_test(1, 13, "<p>Saya harus lulus</p>"); //isi parameter 1 dengan id tugas, parameter 2 dengan id hasil_tugas, parameter 3 dengan jawaban yang akan dicek
		print_r($test);
		$expected_result = null;
		$test_name = 'CEK PLAGIASI ADA DATA PEMBANDING';
		echo $this->unit->run($test, $expected_result, $test_name);
	}

	//========================================================
	//-----------------------INTEGRASI------------------------
	//========================================================

	public function essay_test($id)
	{
		$essay = $this->tugas->getHasilEssay($id)->row();

		$data = [
			// 'user' => $this->user,
			'plagiasi' => $this->cekplagiat($essay->id_soal_essay, $essay->id, $essay->list_jawaban)
		];
		return $data;
	}

	public function integration_test_1() //TEST CASE 1 : (DATA PEMBANDING KOSONG) 
	{
		$test = $this->essay_test(14); //isi parameter dengan id hasil_tugas (essay) yg tidak ada pembandingnya
		print_r($test);
		$expected_result = array(
			"plagiasi" => null
		);
		$test_name = 'CEK PLAGIASI TIDAK ADA DATA PEMBANDING';
		echo $this->unit->run($test, $expected_result, $test_name);
	}

	public function integration_test_2() //TEST CASE 2 : (DATA PEMBANDING ADA) 
	{
		$test = $this->essay_test(13); //isi parameter dengan id hasil_tugas (essay) yg ada pembandingnya
		print_r($test);
		$expected_result = $test;
		$test_name = 'CEK PLAGIASI ADA DATA PEMBANDING';
		echo $this->unit->run($test, $expected_result, $test_name);
	}

	/** DISCLAIMER : DI CLASS INI ADA 2 PENGUJIAN UNIT DAN 1 PENGUJIAN INTEGRASI
	 * 
	 * CARA MELAKUKAN PENGUJIAN UNIT/INTEGRASI:
	 * 
	 * 1. ISI PARAMETER SESUAI PETUNJUK
	 * 2. JALANKAN DI BROWSER DENGAN ALAMAT http://localhost/epembelajaranV2/{nama controller}/{unit test yg mau diuji}
	 * 	 contoh : http://localhost/epembelajaranV2/hasiltugas/unit_test_A_1
	 * 3. variabel $test menampung hasil pengujian
	 *  	 variabel $expected_result menampung hasil yang diharapkan
	 *  	 variabel $test_name menampung nama pengujian (bisa diisi bebas)
	 * 	 $this->unit->run() berfungsi untuk menjalankan library unit_test pada CI
	 */
}
