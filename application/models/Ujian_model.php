<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ujian_model extends CI_Model
{

    public function create($table, $data, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->insert($table, $data);
        } else {
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->update($table, $data, array($pk => $id));
        } else {
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    public function getKelas($kelas)
    {
        $this->datatables->select("id_kelas, nama_kelas");
        $this->datatables->from("kelas");
        $this->datatables->where("id_kelas IN ({$kelas})", null);
        return $this->datatables->generate();
    }

    public function getDataUjian($guru, $mapel, $kelas)
    {
        $this->datatables->select('IF(id_ujian IS NULL,0,id_ujian) as id_ujian, id_siswa, nama, nama_kelas, nilai_uts, nilai_uas');
        $this->datatables->from("(select id_siswa, nama, nama_kelas from siswa s JOIN kelas k ON s.kelas_id = k.id_kelas where kelas_id ={$kelas} ) a
        LEFT JOIN
        (select id_ujian, id_siswa, nilai_uts, nilai_uas from ujian where kelas_id ={$kelas} AND mapel_id = {$mapel} AND guru_id = {$guru}) b
        using(id_siswa)");

        return $this->datatables->generate();
    }

    public function getListUjian($id, $kelas)
    {
        $this->datatables->select("a.id_ujian, c.nama_guru, (select nama_kelas from kelas where id_kelas = {$kelas}) as nama_kelas, a.nama_ujian, b.nama_mapel, d.nama_topik, a.jumlah_soal, CONCAT(a.tgl_mulai, ' <br/> (', a.waktu, ' Menit)') as waktu, (SELECT COUNT(id) FROM hasil_ujian h WHERE h.siswa_id = {$id} AND h.ujian_id = a.id_ujian) AS ada");
        $this->datatables->from('ujian a');
        $this->datatables->join('mapel b', 'a.mapel_id = b.id_mapel');
        $this->datatables->join('guru c', 'a.guru_id = c.id_guru');
        $this->datatables->join('topik d', 'd.id_topik = a.topik_id');
        $this->datatables->where("a.guru_id IN (select id_guru from guru where FIND_IN_SET({$kelas}, kelas_id))", null);
        return $this->datatables->generate();
    }

    public function getUjianById($id)
    {
        $this->db->select('*');
        $this->db->from('ujian a');
        $this->db->join('guru b', 'a.guru_id=b.id_guru');
        $this->db->join('mapel c', 'a.mapel_id=c.id_mapel');
        $this->db->join('topik d', 'd.id_topik = a.topik_id');
        $this->db->where('id_ujian', $id);
        return $this->db->get()->row();
    }

    public function getIdGuru($nip)
    {
        $this->db->select('id_guru, nama_guru, mapel_id, kelas_id')->from('guru')->where('nip', $nip);
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

    public function HslUjian($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(tgl_selesai) as waktu_habis');
        $this->db->from('hasil_ujian');
        $this->db->where('ujian_id', $id);
        $this->db->where('siswa_id', $mhs);
        return $this->db->get();
    }

    public function getHasilEssay($id)
    {
        $this->db->select('*');
        $this->db->from('hasil_ujian a');
        $this->db->join('siswa b', 'a.siswa_id=b.id_siswa');
        $this->db->join('kelas c', 'b.kelas_id=c.id_kelas');
        $this->db->join('ujian d', 'a.ujian_id=d.id_ujian');
        $this->db->where('id', $id);
        return $this->db->get();
    }

    function getAllJawabanByIdSoal($id_soal_essay, $id)
    {
        $this->db->select('*');
        $this->db->from('hasil_ujian a');
        $this->db->join('siswa b', 'a.siswa_id=b.id_siswa');
        $this->db->join('kelas c', 'b.kelas_id=c.id_kelas');
        $this->db->join('ujian d', 'a.ujian_id=d.id_ujian');
        $this->db->where('id_soal_essay', $id_soal_essay);
        $this->db->where_not_in('id', $id);
        return $this->db->get();
    }

    public function getSoal($id)
    {
        $ujian = $this->getUjianById($id);
        if ($ujian->jenis_soal === "pilgan") {
            $order = $ujian->jenis === "acak" ? 'rand()' : 'id_soal';

            $this->db->select('id_soal, soal, file, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
            $this->db->from('soal');
            $this->db->where('mapel_id', $ujian->mapel_id);
            $this->db->where("FIND_IN_SET({$ujian->topik_id}, topik)", null);
            $this->db->where('jenis_soal', 'pilgan');
            $this->db->order_by($order);
            $this->db->limit($ujian->jumlah_soal);
            return $this->db->get()->result();
        } else {
            $this->db->select('id_soal, soal, file, tipe_file');
            $this->db->from('soal');
            $this->db->where('mapel_id', $ujian->mapel_id);
            $this->db->where("FIND_IN_SET({$ujian->topik_id}, topik)", null);
            $this->db->where('id_soal', $ujian->id_soal_essay);
            return $this->db->get()->row();
        }
    }

    public function getSoalEssay($topik)
    {
        $this->db->select('*');
        $this->db->from('soal');
        $this->db->where("FIND_IN_SET({$topik}, topik)");
        $this->db->where('jenis_soal', 'essay');
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
        $this->db->from('hasil_ujian');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->list_jawaban;
    }

    public function getHasilUjian($nip = null)
    {
        $this->datatables->select('b.id_ujian, b.nama_ujian, e.nama_topik, b.jenis_soal, b.jumlah_soal, CONCAT(b.waktu, " Menit") as waktu, b.tgl_mulai');
        $this->datatables->select('c.nama_mapel, d.nama_guru');
        $this->datatables->from('hasil_ujian a');
        $this->datatables->join('ujian b', 'a.ujian_id = b.id_ujian');
        $this->datatables->join('mapel c', 'b.mapel_id = c.id_mapel');
        $this->datatables->join('guru d', 'b.guru_id = d.id_guru');
        $this->datatables->join('topik e', 'b.topik_id = e.id_topik');
        if ($nip !== null) {
            $this->datatables->where('d.nip', $nip);
        }
        $this->datatables->group_by('b.id_ujian');
        return $this->datatables->generate();
    }

    public function HslUjianById($id, $dt = false)
    {
        if ($dt === false) {
            $db = "db";
            $get = "get";
        } else {
            $db = "datatables";
            $get = "generate";
        }

        $this->$db->select('d.id, a.nama, b.nama_kelas, d.jenis_soal, c.nama_jurusan, d.jml_benar, d.nilai');
        $this->$db->from('siswa a');
        $this->$db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->$db->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        $this->$db->join('hasil_ujian d', 'a.id_siswa=d.siswa_id');
        $this->$db->where(['d.ujian_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingNilai($id)
    {
        $this->db->select_min('nilai', 'min_nilai');
        $this->db->select_max('nilai', 'max_nilai');
        $this->db->select_avg('FORMAT(FLOOR(nilai),0)', 'avg_nilai');
        $this->db->where('ujian_id', $id);
        return $this->db->get('hasil_ujian')->row();
    }
}
