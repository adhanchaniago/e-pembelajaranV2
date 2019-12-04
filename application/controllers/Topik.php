<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Topik extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('guru')) {
			show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		// $this->load->model('Master_model', 'master');
		$this->load->model('Mapel_model', 'mapel');
		$this->load->model('Topik_model', 'topik');

		$this->form_validation->set_error_delimiters('', '');
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function index()
	{
		$user = $this->ion_auth->user()->row();
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Topik',
			'subjudul' => 'Data Topik',
			'mapel' => $this->mapel->getMapelGuru($user->username)
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/topik/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	public function data($id = null)
	{
		$this->output_json($this->topik->getDataTopik($id), false);
	}
	public function add()
	{
		$user = $this->ion_auth->user()->row();
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Tambah Topik',
			'subjudul'	=> 'Tambah Data Topik',
			'banyak'	=> $this->input->post('banyak', true)
		];
		if ($this->ion_auth->is_admin()) {
			$data['mapel']	= $this->mapel->getAllMapel();
		} else {
			$data['mapel']	= $this->mapel->getMapelGuru($user->username);
		}
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/topik/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	public function edit()
	{
		$user = $this->ion_auth->user()->row();
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			redirect('topik');
		} else {
			$topik = $this->topik->getTopikById($chk);
			$data = [
				'user' 		=> $this->ion_auth->user()->row(),
				'judul'		=> 'Edit Topik',
				'subjudul'	=> 'Edit Data Topik',
				'topik'		=> $topik
			];
			if ($this->ion_auth->is_admin()) {
				$data['mapel']	= $this->mapel->getAllMapel();
			} else {
				$data['mapel']	= $this->mapel->getMapelGuru($user->username);
			}
			$this->load->view('_templates/dashboard/_header.php', $data);
			$this->load->view('master/topik/edit');
			$this->load->view('_templates/dashboard/_footer.php');
		}
	}
	public function save()
	{
		$rows = count($this->input->post('nama_topik', true));
		$mode = $this->input->post('mode', true);
		for ($i = 1; $i <= $rows; $i++) {
			$kelas		 	= 'kelas[' . $i . ']';
			$nama_topik 	= 'nama_topik[' . $i . ']';
			$mapel_id 		= 'mapel_id[' . $i . ']';
			$this->form_validation->set_rules($kelas, 'Kelas', 'required');
			$this->form_validation->set_rules($nama_topik, 'Topik', 'required');
			$this->form_validation->set_rules($mapel_id, 'Mapel', 'required');
			$this->form_validation->set_message('required', '{field} Wajib diisi');
			if ($this->form_validation->run() === FALSE) {
				$error[] = [
					$kelas 	=> form_error($kelas),
					$nama_topik 	=> form_error($nama_topik),
					$mapel_id 		=> form_error($mapel_id),
				];
				$status = FALSE;
			} else {
				if ($mode == 'add') {
					$insert[] = [
						'kelas' 	=> $this->input->post($kelas, true),
						'nama_topik' 	=> $this->input->post($nama_topik, true),
						'mapel_id' 	=> $this->input->post($mapel_id, true)
					];
				} else if ($mode == 'edit') {
					$update[] = array(
						'id_topik'		=> $this->input->post('id_topik[' . $i . ']', true),
						'kelas' 	=> $this->input->post($kelas, true),
						'nama_topik' 	=> $this->input->post($nama_topik, true),
						'mapel_id' 	=> $this->input->post($mapel_id, true)
					);
				}
				$status = TRUE;
			}
		}
		if ($status) {
			if ($mode == 'add') {
				$this->topik->create('topik', $insert, true);
				$data['insert']	= $insert;
			} else if ($mode == 'edit') {
				$this->topik->update('topik', $update, 'id_topik', null, true);
				$data['update'] = $update;
			}
		} else {
			if (isset($error)) {
				$data['errors'] = $error;
			}
		}
		$data['status'] = $status;
		$this->output_json($data);
	}
	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->topik->delete('topik', $chk, 'id_topik')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}
	public function topik_by_mapel($id)
	{
		$data = $this->topik->getTopikByMapel($id);
		$this->output_json($data);
	}
	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Topik',
			'subjudul' => 'Import Topik',
			'mapel' => $this->mapel->getAllMapel()
		];
		if ($import_data != null) $data['import'] = $import_data;
		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/topik/import');
		$this->load->view('_templates/dashboard/_footer');
	}
	public function preview()
	{
		$config['upload_path']		= './uploads/import/';
		$config['allowed_types']	= 'xls|xlsx|csv';
		$config['max_size']			= 2048;
		$config['encrypt_name']		= true;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('upload_file')) {
			$error = $this->upload->display_errors();
			echo $error;
			die;
		} else {
			$file = $this->upload->data('full_path');
			$ext = $this->upload->data('file_ext');
			switch ($ext) {
				case '.xlsx':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					break;
				case '.xls':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					break;
				case '.csv':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					break;
				default:
					echo "unknown file ext";
					die;
			}
			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'topik' => $sheetData[$i][0],
					'mapel' => $sheetData[$i][1]
				];
			}
			unlink($file);
			$this->import($data);
		}
	}
	public function do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		foreach ($input as $d) {
			$data[] = ['nama_topik' => $d->topik, 'mapel_id' => $d->mapel];
		}
		$save = $this->topik->create('topik', $data, true);
		if ($save) {
			redirect('topik');
		} else {
			redirect('topik/import');
		}
	}
}
