<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Soal_model extends CI_Model
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

    public function getDataSoal($id)
    {
        $this->datatables->select('a.id_soal, a.soal, FROM_UNIXTIME(a.created_on) as created_on, FROM_UNIXTIME(a.updated_on) as updated_on, b.nama_mapel, c.nama_guru');
        $this->datatables->from('soal a');
        $this->datatables->join('mapel b', 'b.id_mapel=a.mapel_id');
        $this->datatables->join('guru c', 'c.id_guru=a.guru_id');
        if ($id !== null) {
            $this->datatables->where('a.mapel_id', $id);
        }
        return $this->datatables->generate();
    }

    public function getSoalById($id)
    {
        return $this->db->get_where('soal', ['id_soal' => $id])->row();
    }

    public function getMapelGuru($nip)
    {
        $this->db->select('mapel_id, nama_mapel, id_guru, nama_guru');
        $this->db->join('mapel', 'mapel_id=id_mapel');
        $this->db->from('guru')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getAllGuru()
    {
        $this->db->select('*');
        $this->db->from('guru a');
        $this->db->join('mapel b', 'a.mapel_id=b.id_mapel');
        return $this->db->get()->result();
    }
}
