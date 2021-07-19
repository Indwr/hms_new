<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class appoint_priority_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function add($appoint_priority)
    {
        $this->db->insert('appoint_priority', $appoint_priority);
    }

    public function appoint_priority_list($id = null)
    {
        $this->db->select()->from('appoint_priority');
        if ($id != null) {
            $this->db->where('appoint_priority.id', $id);
        } else {
            $this->db->order_by('appoint_priority.id');
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
        $this->db->delete('appoint_priority');
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('appoint_priority', $data);
    }
}
