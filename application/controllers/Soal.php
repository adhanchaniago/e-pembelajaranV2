<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Soal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        } else if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('guru')) {
            show_error('Hanya Administrator dan guru yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
        }
        $this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
        $this->load->helper('my'); // Load Library Ignited-Datatables
        // $this->load->model('Master_model', 'master');
        $this->load->model('Mapel_model', 'mapel');
        $this->load->model('Topik_model', 'topik');
        $this->load->model('Soal_model', 'soal');
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
            'user' => $user,
            'judul'    => 'Soal',
            'subjudul' => 'Bank Soal'
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua mapel
            $data['mapel'] = $this->mapel->getAllMapel();
        } else {
            //Jika bukan maka mapel dipilih otomatis sesuai mapel guru
            $data['mapel'] = $this->soal->getMapelGuru($user->username);
        }

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/data');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function detail($id)
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'Soal',
            'subjudul'  => 'Edit Soal',
            'soal'      => $this->soal->getSoalById($id),
        ];

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/detail');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function add()
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'Soal',
            'subjudul'  => 'Buat Soal'
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua mapel
            $data['guru'] = $this->soal->getAllGuru();
        } else {
            //Jika bukan maka mapel dipilih otomatis sesuai mapel guru
            $data['guru'] = $this->soal->getMapelGuru($user->username);
            $mapel = $this->soal->getMapelGuru($user->username);
            $data['topik'] = $this->topik->getTopikByMapel($mapel->mapel_id);
        }

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/add');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function edit($id)
    {
        $s = $this->soal->getSoalById($id);
        $topik = explode(',', $s->topik);
        foreach ($topik as $topik) {
            $top[] = (object) array("id_topik" => $topik);
        }
        $user = $this->ion_auth->user()->row();
        $mapel = $this->soal->getMapelGuru($user->username);
        $data = [
            'user'      => $user,
            'judul'        => 'Soal',
            'subjudul'  => 'Edit Soal',
            'soal'      => $this->soal->getSoalById($id),
            'all_topik'        => $this->topik->getTopikByMapel($mapel->mapel_id),
            'topik'            => $top
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua mapel
            $data['guru'] = $this->soal->getAllGuru();
        } else {
            //Jika bukan maka mapel dipilih otomatis sesuai mapel guru
            $data['guru'] = $this->soal->getMapelGuru($user->username);
        }

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/edit');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function data($id = null)
    {
        $this->output_json($this->soal->getDataSoal($id), false);
    }

    public function validasi()
    {
        if ($this->ion_auth->is_admin()) {
            $this->form_validation->set_rules('guru_id', 'Guru', 'required');
        }
        $this->form_validation->set_rules('soal', 'Soal', 'required');
        $this->form_validation->set_rules('topik_id[]', 'Topik', 'required');
        $this->form_validation->set_rules('jawaban_a', 'Jawaban A', 'required');
        $this->form_validation->set_rules('jawaban_b', 'Jawaban B', 'required');
        $this->form_validation->set_rules('jawaban_c', 'Jawaban C', 'required');
        $this->form_validation->set_rules('jawaban_d', 'Jawaban D', 'required');
        $this->form_validation->set_rules('jawaban_e', 'Jawaban E', 'required');
        $this->form_validation->set_rules('jawaban', 'Kunci Jawaban', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot Soal', 'required|max_length[2]');
    }

    public function validasi_essay()
    {
        if ($this->ion_auth->is_admin()) {
            $this->form_validation->set_rules('guru_id', 'Guru', 'required');
        }
        $this->form_validation->set_rules('soal', 'Soal', 'required');
        $this->form_validation->set_rules('topik_id[]', 'Topik', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot Soal', 'required|max_length[2]');
    }

    public function file_config()
    {
        $allowed_type     = [
            "image/jpeg", "image/jpg", "image/png", "image/gif",
            "audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
            "video/mp4", "application/octet-stream"
        ];
        $config['upload_path']      = FCPATH . 'uploads/bank_soal/';
        $config['allowed_types']    = 'jpeg|jpg|png|gif|mpeg|mpg|mpeg3|mp3|wav|wave|mp4';
        $config['encrypt_name']     = TRUE;

        return $this->load->library('upload', $config);
    }

    public function save()
    {
        // variabel method untuk mengetahui apakah menyimpan form add atau form edit
        $method = $this->input->post('method', true);

        // mengetahui essay atau pilgan
        if ($this->input->post('jenis_soal') == 'pilgan') {
            $this->validasi();
        } else {
            $this->validasi_essay();
        }
        $this->file_config();
        if ($this->form_validation->run() === FALSE) {
            $method === 'add' ? $this->add() : $this->edit($this->input->post('id_soal'));
        } else {
            $t = $this->input->post('topik_id', true);
            $topik = implode(",", $t);
            // essay atau pilgan
            if ($this->input->post('jenis_soal') == 'pilgan') {
                $data = [
                    'topik'      => $topik,
                    'soal'      => $this->input->post('soal', true),
                    'jenis_soal' => $this->input->post('jenis_soal', true),
                    'jawaban'   => $this->input->post('jawaban', true),
                    'bobot'     => $this->input->post('bobot', true),
                ];

                $abjad = ['a', 'b', 'c', 'd', 'e'];

                // Inputan Opsi
                foreach ($abjad as $abj) {
                    $data['opsi_' . $abj]    = $this->input->post('jawaban_' . $abj, true);
                }
            } else {
                $data = [
                    'topik'      => $topik,
                    'soal'      => $this->input->post('soal', true),
                    'jenis_soal' => $this->input->post('jenis_soal', true),
                    'bobot'     => $this->input->post('bobot', true),
                ];
            }
            $i = 0;
            foreach ($_FILES as $key => $val) {
                $img_src = FCPATH . 'uploads/bank_soal/';
                $getsoal = $this->soal->getSoalById($this->input->post('id_soal', true));

                $error = '';
                if ($key === 'file_soal') {
                    if (!empty($_FILES['file_soal']['name'])) {
                        if (!$this->upload->do_upload('file_soal')) {
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'File Soal Error');
                            exit();
                        } else {
                            if ($method === 'edit') {
                                if (!unlink($img_src . $getsoal->file)) {
                                    show_error('Error saat delete gambar <br/>' . var_dump($getsoal), 500, 'Error Edit Gambar');
                                    exit();
                                }
                            }
                            $data['file'] = $this->upload->data('file_name');
                            $data['tipe_file'] = $this->upload->data('file_type');
                        }
                    }
                } else {
                    $file_abj = 'file_' . $abjad[$i];
                    if (!empty($_FILES[$file_abj]['name'])) {
                        if (!$this->upload->do_upload($key)) {
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'File Opsi ' . strtoupper($abjad[$i]) . ' Error');
                            exit();
                        } else {
                            if ($method === 'edit') {
                                if (!unlink($img_src . $getsoal->$file_abj)) {
                                    show_error('Error saat delete gambar', 500, 'Error Edit Gambar');
                                    exit();
                                }
                            }
                            $data[$file_abj] = $this->upload->data('file_name');
                        }
                    }
                    $i++;
                }
            }

            if ($this->ion_auth->is_admin()) {
                $pecah = $this->input->post('guru_id', true);
                $pecah = explode(':', $pecah);
                $data['guru_id'] = $pecah[0];
                $data['mapel_id'] = end($pecah);
            } else {
                $data['guru_id'] = $this->input->post('guru_id', true);
                $data['mapel_id'] = $this->input->post('mapel_id', true);
            }

            if ($method === 'add') {
                //push array
                $data['created_on'] = time();
                $data['updated_on'] = time();
                //insert data
                if ($this->input->post('jenis_soal') == 'pilgan') {
                    $this->soal->create('soal', $data);
                } else {
                    $this->soal->create('soal', $data);
                }
            } else if ($method === 'edit') {
                //push array
                $data['updated_on'] = time();
                //update data
                if ($this->input->post('jenis_soal') == 'pilgan') {
                    $id_soal = $this->input->post('id_soal', true);
                    $this->soal->update('soal', $data, 'id_soal', $id_soal);
                } else {
                    $id_soal = $this->input->post('id_soal', true);
                    $this->soal->update('soal', $data, 'id_soal', $id_soal);
                }
            } else {
                show_error('Method tidak diketahui', 404);
            }
            redirect('soal');
        }
    }

    public function delete()
    {
        $chk = $this->input->post('checked', true);

        // Delete File
        foreach ($chk as $id) {
            $abjad = ['a', 'b', 'c', 'd', 'e'];
            $path = FCPATH . 'uploads/bank_soal/';
            $soal = $this->soal->getSoalById($id);
            // Hapus File Soal
            if (!empty($soal->file)) {
                if (file_exists($path . $soal->file)) {
                    unlink($path . $soal->file);
                }
            }
            //Hapus File Opsi
            $i = 0; //index
            foreach ($abjad as $abj) {
                $file_opsi = 'file_' . $abj;
                if (!empty($soal->$file_opsi)) {
                    if (file_exists($path . $soal->$file_opsi)) {
                        unlink($path . $soal->$file_opsi);
                    }
                }
            }
        }

        if (!$chk) {
            $this->output_json(['status' => false]);
        } else {
            if ($this->soal->delete('soal', $chk, 'id_soal')) {
                $this->output_json(['status' => true, 'total' => count($chk)]);
            }
        }
    }
}
