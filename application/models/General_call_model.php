<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class general_call_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->insert('general_calls', $data);
    }

    public function call_list($id = null)
    {
        $this->db->select()->from('general_calls');
        if ($id != null) {
            $this->db->where('general_calls.id', $id);
        } else {
            $this->db->order_by('general_calls.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('general_calls');
    }

    public function call_update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('general_calls', $data);
    }

}
