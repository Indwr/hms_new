<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class visitors_model extends CI_Model
{

    public $column_order  = array('purpose', 'name', 'contact', 'date', 'in_time', 'out_time'); //set column field database for datatable orderable
    public $column_search = array('purpose', 'name', 'contact', 'date', 'in_time', 'out_time');

    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->insert('visitors_book', $data);
        return $query = $this->db->insert_id();
    }

    public function getPurpose()
    {
        $this->db->select('*');
        $this->db->from('visitors_purpose');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function visitors_list($id = null)
    {
        $this->db->select()->from('visitors_book');
        if ($id != null) {
            $this->db->where('visitors_book.id', $id);
        } else {
            $this->db->order_by('visitors_book.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function search_datatable()
    {
        $this->db->select('visitors_book.*');
		if(!isset($_POST['order'])){
			$this->db->order_by('`visitors_book`.`id`', 'desc');
		}
		
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->column_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->order_by($this->column_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        $query = $this->db->get('visitors_book');
        return $query->result();
    }

    public function search_datatable_count()
    {
        $this->db->select('visitors_book.*');
        $this->db->order_by('`visitors_book`.`id`', 'desc');
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->column_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();
        }
        $query        = $this->db->from('visitors_book');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('visitors_book');
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Visitor deleted successfully</div>');
        redirect('admin/visitors');
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('visitors_book', $data);
    }

    public function image_add($visitor_id, $image)
    {
        $array = array('id' => $visitor_id);
        $this->db->set('image', $image);
        $this->db->where($array);
        $this->db->update('visitors_book');
    }

    public function image_update($visitor_id, $image)
    {
        $array = array('id' => $visitor_id);
        $this->db->set('image', $image);
        $this->db->where($array);
        $this->db->update('visitors_book');
    }

    public function image_delete($id, $img_name)
    {
        $file = "./uploads/front_office/visitors/" . $img_name;
        unlink($file);
        $this->db->where('id', $id);
        $this->db->delete('visitors_book');
        $controller_name = $this->uri->segment(2);
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> ' . ucfirst($controller_name) . ' deleted successfully</div>');
        redirect('admin/' . $controller_name);
    }

}
