<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
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

    public function getAllTopik()
    {
        return $this->db->get('topik')->result();
    }
    /**
     * Data Kelas
     */
    public function getDataKelas()
    {
        $this->datatables->select('id_kelas, nama_kelas, id_jurusan, nama_jurusan');
        $this->datatables->from('kelas');
        $this->datatables->join('jurusan', 'jurusan_id=id_jurusan');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_kelas, nama_kelas, id_jurusan, nama_jurusan');
        return $this->datatables->generate();
    }
    public function getKelasById($id)
    {
        $this->db->where_in('id_kelas', $id);
        $this->db->order_by('nama_kelas');
        $query = $this->db->get('kelas')->result();
        return $query;
    }

    public function getAllKelas()
    {
        $this->db->select('id_kelas, nama_kelas, nama_jurusan');
        $this->db->from('kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->order_by('nama_kelas');
        return $this->db->get()->result();
    }

    /**
     * Data Jurusan
     */
    public function getDataJurusan()
    {
        $this->datatables->select('id_jurusan, nama_jurusan');
        $this->datatables->from('jurusan');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_jurusan, nama_jurusan');
        return $this->datatables->generate();
    }
    public function getJurusanById($id)
    {
        $this->db->where_in('id_jurusan', $id);
        $this->db->order_by('nama_jurusan');
        $query = $this->db->get('jurusan')->result();
        return $query;
    }
    public function getJurusan()
    {
        $this->db->select('id_jurusan, nama_jurusan');
        $this->db->from('kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->order_by('nama_jurusan', 'ASC');
        $this->db->group_by('id_jurusan');
        $query = $this->db->get();
        return $query->result();
    }
    public function getAllJurusan($id = null)
    {
        if ($id === null) {
            $this->db->order_by('nama_jurusan', 'ASC');
            return $this->db->get('jurusan')->result();
        } else {
            $this->db->select('jurusan_id');
            $this->db->from('jurusan_mapel');
            $this->db->where('mapel_id', $id);
            $jurusan = $this->db->get()->result();
            $id_jurusan = [];
            foreach ($jurusan as $j) {
                $id_jurusan[] = $j->jurusan_id;
            }
            if ($id_jurusan === []) {
                $id_jurusan = null;
            }

            $this->db->select('*');
            $this->db->from('jurusan');
            $this->db->where_not_in('id_jurusan', $id_jurusan);
            $mapel = $this->db->get()->result();
            return $mapel;
        }
    }


    /**
     * Data siswa
     */

    public function getDatasiswa()
    {
        $this->datatables->select('a.id_siswa, a.nama, a.nis, a.email, b.nama_kelas, c.nama_jurusan');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.nis) AS ada');
        $this->datatables->from('siswa a');
        $this->datatables->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->datatables->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        return $this->datatables->generate();
    }

    public function getsiswaById($id)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas_id=id_kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->where(['id_siswa' => $id]);
        return $this->db->get()->row();
    }




    public function getKelasByJurusan($id)
    {
        $query = $this->db->get_where('kelas', array('jurusan_id' => $id));
        return $query->result();
    }

    /**
     * Data guru
     */

    public function getDataguru()
    {
        $this->datatables->select('a.id_guru,a.nip, a.nama_guru, a.email, a.mapel_id, b.nama_mapel, (SELECT COUNT(id) FROM users WHERE username = a.nip OR email = a.email) AS ada');
        $this->datatables->from('guru a');
        $this->datatables->join('mapel b', 'a.mapel_id=b.id_mapel');
        return $this->datatables->generate();
    }

    public function getguruById($id)
    {
        $query = $this->db->get_where('guru', array('id_guru' => $id));
        return $query->row();
    }

    /**
     * Data mapel
     */

    public function getDatamapel()
    {
        $this->datatables->select('id_mapel, nama_mapel');
        $this->datatables->from('mapel');
        return $this->datatables->generate();
    }

    public function getAllMapel()
    {
        return $this->db->get('mapel')->result();
    }

    public function getmapelById($id, $single = false)
    {
        if ($single === false) {
            $this->db->where_in('id_mapel', $id);
            $this->db->order_by('nama_mapel');
            $query = $this->db->get('mapel')->result();
        } else {
            $query = $this->db->get_where('mapel', array('id_mapel' => $id))->row();
        }
        return $query;
    }

    public function getmapel($id = null)
    {
        $this->db->select('mapel_id');
        $this->db->from('jurusan_mapel');
        if ($id !== null) {
            $this->db->where_not_in('mapel_id', [$id]);
        }
        $mapel = $this->db->get()->result();
        $id_mapel = [];
        foreach ($mapel as $d) {
            $id_mapel[] = $d->mapel_id;
        }
        if ($id_mapel === []) {
            $id_mapel = null;
        }

        $this->db->select('id_mapel, nama_mapel');
        $this->db->from('mapel');
        $this->db->where_not_in('id_mapel', $id_mapel);
        return $this->db->get()->result();
    }

    public function getMapelGuru($nip)
    {
        $this->db->select('mapel_id, nama_mapel, id_guru, nama_guru');
        $this->db->join('mapel', 'mapel_id=id_mapel');
        $this->db->from('guru')->where('nip', $nip);
        return $this->db->get()->row();
    }
    /**
     * Data Kelas guru
     */

    public function getKelasguru()
    {
        $this->datatables->select('kelas_guru.id, guru.id_guru, guru.nip, guru.nama_guru, GROUP_CONCAT(kelas.nama_kelas) as kelas');
        $this->datatables->from('kelas_guru');
        $this->datatables->join('kelas', 'kelas_id=id_kelas');
        $this->datatables->join('guru', 'guru_id=id_guru');
        $this->datatables->group_by('guru.nama_guru');
        return $this->datatables->generate();
    }

    public function getAllguru($id = null)
    {
        $this->db->select('guru_id');
        $this->db->from('kelas_guru');
        if ($id !== null) {
            $this->db->where_not_in('guru_id', [$id]);
        }
        $guru = $this->db->get()->result();
        $id_guru = [];
        foreach ($guru as $d) {
            $id_guru[] = $d->guru_id;
        }
        if ($id_guru === []) {
            $id_guru = null;
        }

        $this->db->select('id_guru, nip, nama_guru');
        $this->db->from('guru');
        $this->db->where_not_in('id_guru', $id_guru);
        return $this->db->get()->result();
    }



    public function getKelasByGuru($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas_guru');
        $this->db->join('kelas', 'kelas_guru.kelas_id=kelas.id_kelas');
        $this->db->where('guru_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}
