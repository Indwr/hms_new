<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class complaint_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->insert('complaint', $data);
        return $this->db->insert_id();
    }

    public function image_add($complaint_id, $image)
    {
        $array = array('id' => $complaint_id);
        $this->db->set('image', $image);
        $this->db->where($array);
        $this->db->update('complaint');
    }

    public function complaint_list($id = null)
    {
        $this->db->select()->from('complaint');
        if ($id != null) {
            $this->db->where('complaint.id', $id);
        } else {
            $this->db->order_by('complaint.id', "desc");
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function image_delete($id, $img_name)
    {
        $file = "./uploads/front_office/complaints/" . $img_name;
        unlink($file);
        $this->db->where('id', $id);
        $this->db->delete('complaint');
        $controller_name = $this->uri->segment(2);
    }

    public function compalaint_update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('complaint', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('complaint');
    }

    public function getComplaintType()
    {
        $this->db->select('*');
        $this->db->from('complaint_type');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getComplaintSource()
    {
        $this->db->select('*');
        $this->db->from('source');
        $query = $this->db->get();
        return $query->result_array();
    }
}
