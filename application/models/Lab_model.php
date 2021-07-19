<?php

class Lab_model extends CI_model
{

    public function valid_lab_name($str)
    {
        $lab_name = $this->input->post('lab_name');
        $id       = $this->input->post('lab_id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_lab_exists($lab_name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function addparameter($data)
    {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('radiology_parameter', $data);
        } else {
            $this->db->insert('radiology_parameter', $data);
            return $this->db->insert_id();
        }
    }

    public function valid_parameter_name($str)
    {
        $parameter_name = $this->input->post('parameter_name');
        $id             = $this->input->post('parameter_id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_parameter_exists($parameter_name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function valid_unit_name($str)
    {
        $unit_name = $this->input->post('unit_name');
        $id        = $this->input->post('unit_id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_unit_exists($unit_name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function addunit($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('unit', $data);
        } else {
            $this->db->insert('unit', $data);
            return $this->db->insert_id();
        }
    }

    public function deleteunit($id)
    {
        $this->db->where("id", $id)->delete("unit");
    }

    public function check_unit_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'unit_name' => $name);
            $query = $this->db->where($data)->get('unit');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('unit_name', $name);
            $query = $this->db->get('unit');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function getradioparameter($id = null)
    {
        if (!empty($id)) {
            $this->db->select('radiology_parameter.*,unit.unit_name');
            $this->db->from('radiology_parameter');
            $this->db->join('unit', 'radiology_parameter.unit = unit.id', 'left');
            $this->db->where("radiology_parameter.id", $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('radiology_parameter.*,unit.unit_name');
            $this->db->from('radiology_parameter');
            $this->db->join('unit', 'radiology_parameter.unit = unit.id', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getlabName($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('lab');
            return $query->row_array();
        } else {
            $query = $this->db->get("lab");
            return $query->result_array();
        }
    }

    public function check_lab_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'lab_name' => $name);
            $query = $this->db->where($data)->get('lab');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('lab_name', $name);
            $query = $this->db->get('lab');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function check_parameter_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'parameter_name' => $name);
            $query = $this->db->where($data)->get('radiology_parameter');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('parameter_name', $name);
            $query = $this->db->get('radiology_parameter');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function addLabName($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('lab', $data);
        } else {
            $this->db->insert('lab', $data);
            return $this->db->insert_id();
        }
    }

    public function getall()
    {
        $this->datatables->select('id,lab_name');
        $this->datatables->from('lab');
        $this->datatables->add_column('view', '<a href="' . site_url('admin/lab/edit/$1') . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"> <i class="fa fa-pencil"></i></a><a href="' . site_url('admin/lab/delete/$1') . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>', 'id');
        return $this->datatables->generate();
    }

    public function delete($id)
    {
        $this->db->where("id", $id)->delete("lab");
    }

    public function delete_parameter($id)
    {
        $this->db->where("id", $id)->delete("radiology_parameter");
    }

    public function getunit($id = null)
    {
        if (!empty($id)) {
            $this->db->select("unit.*");
            $this->db->where('id', $id);
            $this->db->where('unit_type', 'radio');
            $query = $this->db->get('unit');
            return $query->row_array();
        } else {
            $this->db->select("unit.*");
            $this->db->where('unit_type', 'radio');
            $query = $this->db->get('unit');
            return $query->result_array();
        }
    }

}
