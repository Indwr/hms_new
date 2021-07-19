<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Symptoms_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        if (!empty($id)) {
            $this->db->select('symptoms.*,symptoms_classification.symptoms_type');
            $this->db->from('symptoms');
            $this->db->join('symptoms_classification', 'symptoms_classification.id = symptoms.type', 'left');
            $this->db->where("symptoms.id", $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {

            $this->db->select('symptoms.*,symptoms_classification.symptoms_type');
            $this->db->from('symptoms');
            $this->db->join('symptoms_classification', 'symptoms_classification.id = symptoms.type', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getsymtype($id = null)
    {
        $this->db->select()->from('symptoms_classification');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getbysys($sys_id)
    {
        $this->db->select()->from('symptoms');
        $this->db->where('type', $sys_id);
        $query = $this->db->get();
        return $query->result();

    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('symptoms');
    }

    public function removesymtype($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('symptoms_classification');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('symptoms', $data);
        } else {
            $this->db->insert('symptoms', $data);
        }
    }

    public function addsymtype($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('symptoms_classification', $data);
        } else {
            $this->db->insert('symptoms_classification', $data);
        }
    }

}
