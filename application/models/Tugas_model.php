<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tugas_model extends CI_Model
{

    public function getDataTugas($id)
    {
        $this->datatables->select('a.id_tugas, a.token, a.nama_tugas, b.nama_mapel, c.nama_topik, a.jumlah_soal, a.jenis_soal, a.jenis');
        $this->datatables->from('tugas a');
        $this->datatables->join('mapel b', 'a.mapel_id = b.id_mapel');
        $this->datatables->join('topik c', 'c.id_topik = a.topik_id ');
        if ($id !== null) {
            $this->datatables->where('guru_id', $id);
        }
        return $this->datatables->generate();
    }

    public function getListTugas($id, $kelas)
    {
        $this->datatables->select("a.id_tugas, c.nama_guru, (select nama_kelas from kelas where id_kelas = {$kelas}) as nama_kelas, a.nama_tugas, b.nama_mapel, d.nama_topik, a.jumlah_soal, (SELECT COUNT(id) FROM hasil_tugas h WHERE h.siswa_id = {$id} AND h.tugas_id = a.id_tugas) AS ada");
        $this->datatables->from('tugas a');
        $this->datatables->join('mapel b', 'a.mapel_id = b.id_mapel');
        $this->datatables->join('guru c', 'a.guru_id = c.id_guru');
        $this->datatables->join('topik d', 'd.id_topik = a.topik_id');
        $this->datatables->where("a.guru_id IN (select id_guru from guru where FIND_IN_SET({$kelas}, kelas_id))", null);
        return $this->datatables->generate();
    }

    public function getTugasById($id)
    {
        $this->db->select('*');
        $this->db->from('tugas a');
        $this->db->join('guru b', 'a.guru_id=b.id_guru');
        $this->db->join('mapel c', 'a.mapel_id=c.id_mapel');
        $this->db->join('topik d', 'd.id_topik = a.topik_id');
        $this->db->where('id_tugas', $id);
        return $this->db->get()->row();
    }

    public function getIdGuru($nip)
    {
        $this->db->select('id_guru, nama_guru')->from('guru')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getJumlahSoal($m, $t)
    {
        $this->db->select('COUNT(id_soal) as jml_soal');
        $this->db->from('soal');
        $this->db->where('mapel_id', $m);
        $this->db->where("FIND_IN_SET({$t}, topik)", null);
        return $this->db->get()->row();
    }

    public function getIdSiswa($nis)
    {
        $this->db->select('*');
        $this->db->from('siswa a');
        $this->db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->db->where('nis', $nis);
        return $this->db->get()->row();
    }

    public function HslTugas($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(tgl_selesai) as waktu_habis');
        $this->db->from('hasil_tugas');
        $this->db->where('tugas_id', $id);
        $this->db->where('siswa_id', $mhs);
        return $this->db->get();
    }

    public function getSoal($id)
    {
        $tugas = $this->getTugasById($id);
        if ($tugas->jenis_soal === "pilgan") {
            $order = $tugas->jenis === "acak" ? 'rand()' : 'id_soal';

            $this->db->select('id_soal, soal, file, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
            $this->db->from('soal');
            $this->db->where('mapel_id', $tugas->mapel_id);
            $this->db->where("FIND_IN_SET({$tugas->topik_id}, topik)", null);
            $this->db->order_by($order);
            $this->db->limit($tugas->jumlah_soal);
        } else {
            $this->db->select('id_soal, soal, file, tipe_file');
            $this->db->from('soal');
            $this->db->where('mapel_id', $tugas->mapel_id);
            $this->db->where("FIND_IN_SET({$tugas->topik_id}, topik)", null);
            $this->db->where('id_soal', $tugas->id_soal_essay);
        }

        return $this->db->get()->result();
    }

    public function getSoalEssay($topik)
    {
        $this->db->select('*');
        $this->db->from('soal');
        $this->db->where("FIND_IN_SET({$topik}, topik)");
        return $this->db->get()->result();
    }

    public function ambilSoal($pc_urut_soal1, $pc_urut_soal_arr)
    {
        $this->db->select("*, {$pc_urut_soal1} AS jawaban");
        $this->db->from('soal');
        $this->db->where('id_soal', $pc_urut_soal_arr);
        return $this->db->get()->row();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('list_jawaban');
        $this->db->from('hasil_tugas');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->list_jawaban;
    }

    public function getHasilTugas($nip = null)
    {
        $this->datatables->select('b.id_tugas, b.nama_tugas, e.nama_topik, b.jumlah_soal, b.tgl_mulai');
        $this->datatables->select('c.nama_mapel, d.nama_guru');
        $this->datatables->from('hasil_tugas a');
        $this->datatables->join('tugas b', 'a.tugas_id = b.id_tugas');
        $this->datatables->join('mapel c', 'b.mapel_id = c.id_mapel');
        $this->datatables->join('guru d', 'b.guru_id = d.id_guru');
        $this->datatables->join('topik e', 'b.topik_id = e.id_topik');
        if ($nip !== null) {
            $this->datatables->where('d.nip', $nip);
        }
        $this->datatables->group_by('b.id_tugas');
        return $this->datatables->generate();
    }

    public function HslTugasById($id, $dt = false)
    {
        if ($dt === false) {
            $db = "db";
            $get = "get";
        } else {
            $db = "datatables";
            $get = "generate";
        }

        $this->$db->select('d.id, a.nama, b.nama_kelas, c.nama_jurusan, d.jml_benar, d.nilai');
        $this->$db->from('siswa a');
        $this->$db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->$db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->$db->join('hasil_tugas d', 'a.id_siswa=d.siswa_id');
        $this->$db->where(['d.tugas_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingNilai($id)
    {
        $this->db->select_min('nilai', 'min_nilai');
        $this->db->select_max('nilai', 'max_nilai');
        $this->db->select_avg('FORMAT(FLOOR(nilai),0)', 'avg_nilai');
        $this->db->where('tugas_id', $id);
        return $this->db->get('hasil_tugas')->row();
    }
}
