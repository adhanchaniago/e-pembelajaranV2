<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_test extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Topik_model', 'topik');
		$this->load->model('Soal_model', 'soal');
		$this->load->model('Tugas_model', 'tugas');
		$this->load->model('Kuis_model', 'kuis');
		$this->form_validation->set_error_delimiters('', '');
		$this->load->library('unit_test');
	}

	public function akses_guru()
	{
		if (!$this->ion_auth->in_group('guru')) {
			show_error('Halaman ini khusus untuk guru untuk membuat Test Online, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
	}

	public function validasi($m = null, $t = null)
	{
		$this->akses_guru();

		$jml 	= $this->tugas->getJumlahSoal($m, $t)->jml_soal;
		$jml_a 	= $jml + 1; // Jika tidak mengerti, silahkan baca user_guide codeigniter tentang form_validation pada bagian less_than

		$this->form_validation->set_rules('nama_tugas', 'Nama Tugas/Kuis', 'required|alpha_numeric_spaces|max_length[50]');
		$this->form_validation->set_rules('topik', 'Topik', 'required');
		$this->form_validation->set_rules('jumlah_soal', 'Jumlah Soal', "required|integer|less_than[{$jml_a}]|greater_than[0]", ['less_than' => "Soal tidak cukup, hanya ada {$jml} soal untuk topik ini"]);
		$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'required');
		$this->form_validation->set_rules('tgl_selesai', 'Tanggal Selesai', 'required');
		$this->form_validation->set_rules('jenis', 'Acak Soal', 'required|in_list[acak,urut]');
		$this->form_validation->set_rules('jenis_soal', 'Tipe Soal', 'required|in_list[pilgan,essay]');
	}

	public function validasi_essay()
	{
		$this->akses_guru();

		// $jml 	= $this->tugas->getJumlahSoal($m, $t)->jml_soal;
		// $jml_a 	= $jml + 1; // Jika tidak mengerti, silahkan baca user_guide codeigniter tentang form_validation pada bagian less_than

		$this->form_validation->set_rules('nama_tugas', 'Nama Tugas/Kuis', 'required|alpha_numeric_spaces|max_length[50]');
		$this->form_validation->set_rules('topik', 'Topik', 'required');
		$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'required');
		$this->form_validation->set_rules('tgl_selesai', 'Tanggal Selesai', 'required');
		$this->form_validation->set_rules('jenis_soal', 'Tipe Soal', 'required|in_list[pilgan,essay]');
		$this->form_validation->set_rules('soal', 'Soal', 'required');
	}

	public function convert_tgl($tgl)
	{
		$this->akses_guru();
		return date('Y-m-d H:i:s', strtotime($tgl));
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}


	public function save()
	{
		$mapel_id 		= 8;
		$topik_id	 	= 18;
		$jenis_soal		= "pilgan";
		if ($jenis_soal == 'pilgan') { //2
			$this->validasi($mapel_id, $topik_id); //3
		} else {
			$this->validasi_essay(); //4
		} //5

		$this->load->helper('string');


		$guru_id 		= 10;
		$nama_tugas 	= "tugas 4";
		$jumlah_soal 	= "pilgan";
		$tgl_mulai 		= "2019-12-09 11:20:00";
		$tgl_selesai	= "2019-12-12 11:20:00"; //6
		$jenis_tugas	= "tugas";
		$jenis			= "acak";
		$id_soal		= null;
		$token 			= "89asihuahwdo0129niawd90uhawd";

		if ($this->form_validation->run() === FALSE) {
			$data['status'] = false;
			$data['errors'] = [
				'nama_tugas' 	=> form_error('nama_tugas'),
				'topik'		 	=> form_error('topik'),
				'jumlah_soal' 	=> form_error('jumlah_soal'),
				'tgl_mulai' 	=> form_error('tgl_mulai'),
				'tgl_selesai' 	=> form_error('tgl_selesai'),
				'jenis_soal'	=> form_error('jenis_soal'),
				'jenis' 		=> form_error('jenis'),
				'soal' 			=> form_error('soal')
			];
		} else {
			if ($jenis_soal == 'pilgan') {
				$input = [
					'nama_tugas' 	=> $nama_tugas,
					'topik_id'	 	=> $topik_id,
					'jumlah_soal' 	=> $jumlah_soal,
					'tgl_mulai' 	=> $tgl_mulai,
					'terlambat' 	=> $tgl_selesai,
					'jenis_tugas'	=> $jenis_tugas,
					'jenis_soal'	=> $jenis_soal,
					'jenis' 		=> $jenis,
				];
			} else {
				$input = [
					'nama_tugas' 	=> $nama_tugas,
					'topik_id'	 	=> $topik_id,
					'tgl_mulai' 	=> $tgl_mulai,
					'terlambat' 	=> $tgl_selesai,
					'jumlah_soal' 	=> 1,
					'jenis_tugas'	=> $jenis_tugas,
					'jenis_soal'	=> $jenis_soal,
					'id_soal_essay'	=> $id_soal,
				];
			}
			$input['guru_id']	= $guru_id;
			$input['mapel_id'] 	= $mapel_id;
			$input['token']		= $token;
			$action = $this->tugas->create('tugas', $input);

			$data['status'] = $action ? TRUE : FALSE;
		}
		return $this->output_json($data);
	}

	public function tambah_tugas()
	{
		$test = $this->save();
		$expected_result =  '{"status":true}';
		// $expected_result =  'pengeluaran berhasil ditambahkan';
		$test_name = 'Pengujian Unit Class Keuangan';
		echo $this->unit->run($test, $expected_result, $test_name);
	}
}
