<?php

class Floor_model extends CI_Model
{

    public function valid_floor($str)
    {
        $name = $this->input->post('name');
        if ($this->check_floor_exists($name)) {
            $this->form_validation->set_message('check_exists', $this->lang->line('floor') . " " . $this->lang->line('record_already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function check_floor_exists($name)
    {
        if ($name != 0) {
            $data  = array('name' => $name);
            $query = $this->db->where($data)->get('floor');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('name', $name);
            $query = $this->db->get('floor');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function saveFloor($data)
    {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("floor", $data);
        } else {
            $this->db->insert("floor", $data);
        }
    }

    public function floor_list($id = null)
    {
        $this->db->select()->from('floor');
        if ($id != null) {
            $this->db->where('floor.id', $id);
        } else {
            $this->db->order_by('floor.id', 'desc');
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
        $this->db->where("id", $id)->delete("floor");
    }
}
