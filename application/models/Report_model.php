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

    public function getDataReport($id_guru, $id_mapel, $id_kelas, $topik)
    {
        $select = 'id_siswa, sw.nama';
        $from = "(select id_siswa, nama from siswa where kelas_id = {$id_kelas}) sw";
        foreach ($topik as $key => $value) {
            $select .= ", tugas{$key}, ujian{$key}";
            $from .= "
            left join
            (select id_siswa, nama, round(avg(nilai)) as tugas{$key} from hasil_tugas ht 
            JOIN tugas t ON ht.tugas_id = t.id_tugas
            JOIN topik tp ON tp.id_topik = t.topik_id
            JOIN siswa s ON s.id_siswa = ht.siswa_id
            where t.guru_id = {$id_guru} AND s.kelas_id = {$id_kelas} AND tp.mapel_id = {$id_mapel} AND t.topik_id = {$value->id_topik} 
            group by id_siswa) t{$key}
            using(id_siswa)
            left join
            (select id_siswa, nama, round(avg(nilai)) as ujian{$key} from hasil_ujian hu 
            JOIN ujian u ON hu.ujian_id = u.id_ujian
            JOIN topik t ON t.id_topik = u.topik_id
            JOIN siswa s ON s.id_siswa = hu.siswa_id
            where u.guru_id = {$id_guru} AND s.kelas_id = {$id_kelas} AND t.mapel_id = {$id_mapel} AND u.topik_id = {$value->id_topik} 
            group by id_siswa) u{$key}
            using(id_siswa)";
        };
        $this->datatables->select($select);
        $this->datatables->from($from);
        return $this->datatables->generate();
    }
}
