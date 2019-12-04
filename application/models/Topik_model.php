<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Topik_model extends CI_Model
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
    /**
     * Data Topik
     */
    public function getDataTopik($id)
    {
        $this->datatables->select('id_topik, kelas, nama_topik, mapel_id, nama_mapel');
        $this->datatables->from('topik');
        $this->datatables->join('mapel', 'mapel_id=id_mapel');
        if ($id !== null) {
            $this->datatables->where('mapel_id', $id);
        }
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_topik, nama_topik, id_mapel, nama_mapel');
        return $this->datatables->generate();
    }

    public function getTopikByMapel($where, $multi_where = false)
    {
        $this->db->from('topik');
        if ($multi_where == false) {
            $this->db->where('mapel_id', $where);
        } else {
            $this->db->where($where);
        }
        $this->db->order_by('id_topik', 'asc');
        return $this->db->get()->result();
    }

    public function getTopikById($id, $single = false)
    {
        if ($single === false) {
            $this->db->where_in('id_topik', $id);
            $this->db->order_by('nama_topik');
            $query = $this->db->get('topik')->result();
        } else {
            $query = $this->db->get_where('topik', array('id_topik' => $id))->row();
        }
        return $query;
    }

    public function getTopik($where, $group = false)
    {
        if ($group == true) {
            $this->db->select('mapel_id');
        }
        $this->db->where(['kelas' => $where]);
        if ($group == true) {
            $this->db->group_by('mapel_id');
        }
        $result = $this->db->get('topik');
        return $result->result();
    }

    public function getAllTopik()
    {
        return $this->db->get('topik')->result();
    }
}
