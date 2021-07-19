<?php

class Chargetype_model extends CI_Model
{
    public function add($data)
    {
        $this->db->insert('charge_type_master', $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where("id", $id)->where("is_default", 'no')->delete('charge_type_master');
    }

}
