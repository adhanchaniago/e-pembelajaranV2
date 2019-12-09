<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{

    public function getGuru($nip)
    {
        $this->db->select('*');
        $this->db->from('guru');
        $this->db->where('nip', $nip);
        return $this->db->get();
    }
    public function getSiswa($nis)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->where('nis', $nis);
        return $this->db->get();
    }

    public function getDataReport($id_guru, $id_mapel, $id_kelas, $topik)
    {
        $select = 'id_siswa, sw.nama';
        $from = "(SELECT id_siswa, nama FROM siswa where kelas_id = {$id_kelas}) sw";
        foreach ($topik as $key => $value) {

            $select .= ", tugas{$key}, kuis{$key}";
            $from .= "
                LEFT JOIN
                (SELECT id_siswa, nama, round(avg(nilai)) as tugas{$key} FROM hasil_tugas ht 
                JOIN tugas t ON ht.tugas_id = t.id_tugas
                JOIN topik tp ON tp.id_topik = t.topik_id
                JOIN siswa s ON s.id_siswa = ht.siswa_id
                where t.jenis_tugas='tugas' AND t.guru_id = {$id_guru} AND s.kelas_id = {$id_kelas} AND tp.mapel_id = {$id_mapel} AND t.topik_id = {$value->id_topik}
                group by id_siswa) t{$key}
                using(id_siswa)
                LEFT JOIN
                (SELECT id_siswa, nama, round(avg(nilai)) as kuis{$key} FROM hasil_tugas ht 
                JOIN tugas t ON ht.tugas_id = t.id_tugas
                JOIN topik tp ON tp.id_topik = t.topik_id
                JOIN siswa s ON s.id_siswa = ht.siswa_id
                where t.jenis_tugas='kuis' AND t.guru_id = {$id_guru} AND s.kelas_id = {$id_kelas} AND t.mapel_id = {$id_mapel} AND t.topik_id = {$value->id_topik}
                group by id_siswa) k{$key}
                using(id_siswa)";
        };
        $select .= ", nilai_uts, nilai_uas";
        $from .= "LEFT JOIN
        (SELECT s.id_siswa, u.mapel_id, nilai_uts, nilai_uas FROM ujian u
        JOIN siswa s ON s.id_siswa = u.id_siswa
        where u.mapel_id = {$id_mapel}) u{$key}
        using(id_siswa)";
        $this->datatables->select($select);
        $this->datatables->from($from);
        return $this->datatables->generate();
    }

    public function getDataReportSiswa($id_siswa, $kelas, $topik, $mapel)
    {
        $query = "";
        $lengthArr = count($mapel);
        foreach ($mapel as $key => $value) {
            $query .= " select * from (SELECT nama_mapel, mapel_id FROM hasil_tugas h JOIN tugas t ON h.tugas_id = t.id_tugas JOIN mapel m ON m.id_mapel=t.mapel_id where siswa_id = {$id_siswa} AND mapel_id = {$value->mapel_id} GROUP BY mapel_id ) mapel{$key}";
            foreach ($topik as $key2 => $value2) {
                if ($value2->mapel_id == $value->mapel_id) {

                    $query .= " LEFT JOIN
                        (SELECT round(avg(nilai)) as tugas{$key2}, t.mapel_id FROM hasil_tugas ht 
                        JOIN tugas t ON ht.tugas_id = t.id_tugas
                        JOIN topik tp ON tp.id_topik = t.topik_id
                        JOIN siswa s ON s.id_siswa = ht.siswa_id
                        where t.jenis_tugas='tugas' AND tp.mapel_id = {$value2->mapel_id} AND id_siswa = {$id_siswa} AND topik_id = {$value2->id_topik}) t{$key2}
                        using(mapel_id)
                        LEFT JOIN
                        (SELECT round(avg(nilai)) as kuis{$key2}, t.mapel_id FROM hasil_tugas ht 
                        JOIN tugas t ON ht.tugas_id = t.id_tugas
                        JOIN topik tp ON tp.id_topik = t.topik_id
                        JOIN siswa s ON s.id_siswa = ht.siswa_id
                        where t.jenis_tugas='kuis' AND tp.mapel_id = {$value2->mapel_id} AND id_siswa = {$id_siswa} AND topik_id = {$value2->id_topik}) k{$key2}
                        using(mapel_id)";
                }
            }
            $query .= "LEFT JOIN
            (SELECT u.mapel_id, nilai_uts, nilai_uas FROM ujian u
            JOIN siswa s ON s.id_siswa = u.id_siswa
            where u.mapel_id = {$value2->mapel_id} AND u.id_siswa = {$id_siswa}) u{$key}
            using(mapel_id)";
            if ($key != $lengthArr - 1) {
                $query .= " UNION";
            }
        };
        $data = $this->db->query($query);
        return $data;
    }
}
