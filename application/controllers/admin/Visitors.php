<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Visitors extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'admin/visitors');
        $this->form_validation->set_rules('purpose', $this->lang->line('purpose'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required'); 
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        if ($this->form_validation->run() == false) {
            $data['visitor_list'] = $this->visitors_model->visitors_list();
            $data['Purpose']      = $this->visitors_model->getPurpose();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/visitorview', $data);
            $this->load->view('layout/footer');
        } else {
            $visitors = array(
                'purpose'      => $this->input->post('purpose'),
                'name'         => $this->input->post('name'),
                'contact'      => $this->input->post('contact'),
                'id_proof'     => $this->input->post('id_proof'),
                'no_of_pepple' => $this->input->post('pepples'),
                'date'         => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'in_time'      => $this->input->post('time'),
                'out_time'     => $this->input->post('out_time'),
                'note'         => $this->input->post('note'),
            );
            $visitor_id = $this->visitors_model->add($visitors);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = 'id' . $visitor_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/front_office/visitors/" . $img_name);
                $this->visitors_model->image_add($visitor_id, $img_name);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$this->lang->line('visitors_added_successfully').'</div>');
            redirect('admin/visitors');
        }
    }

    public function visitors_search()
    { 
        $draw            = $_POST['draw'];
        $row             = $_POST['start'];
        $rowperpage      = $_POST['length']; // Rows display per page
        $columnIndex     = $_POST['order'][0]['column']; // Column index
        $columnName      = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition = array();
        if (!empty($_POST['search']['value'])) {
            $where_condition = array('search' => $_POST['search']['value']);
        }
        $resultlist   = $this->visitors_model->search_datatable($where_condition);		
        $total_result = $this->visitors_model->search_datatable_count($where_condition);
        $data         = array();

        foreach ($resultlist as $result_key => $result_value) {

            $action = " <a href='#' data-toggle='tooltip' class='btn btn-default btn-xs pull-right'  title='" . $this->lang->line('show') . "'    data-target='#visitordetails' data-original-title='" . $this->lang->line('view') . "' onclick='getRecord(" . $result_value->id . ")'>  <i class='fa fa-reorder'></i> </a>";

            if ($result_value->image !== "") {
                $action .= "<a href=" . base_url() . 'admin/visitors/download/' . $result_value->image . " class='btn btn-default btn-xs pull-right'  data-toggle='tooltip' title=''  data-original-title=" . $this->lang->line('download') . "><i class='fa fa-download' aria-hidden='true'></i></a>";
            }

            if ($this->rbac->hasPrivilege('visitor_book', 'can_edit')) {
                $action .= "<a href='#'  class='btn btn-default btn-xs pull-right'  data-toggle='tooltip' title=" . $this->lang->line('edit') . " onclick=get(" . $result_value->id . ")><i class='fa fa-pencil' aria-hidden='true'></i></a>";
            }

            if ($this->rbac->hasPrivilege('visitor_book', 'can_delete')) {
                if ($result_value->image !== "") {
                    $action .= "<a href='#' onclick=delete_recordById('" . base_url() . 'admin/visitors/imagedelete/' . $result_value->id . '/' . $result_value->image . "') class='btn btn-default btn-xs pull-right'  data-toggle='tooltip' title=''  data-original-title=" . $this->lang->line('delete') . "><i class='fa fa-trash' aria-hidden='true'></i></a>";
                } else {

                    $action .= "<a href='#' onclick=delete_recordById('" . base_url() . 'admin/visitors/delete/' . $result_value->id . "','delete') class='btn btn-default btn-xs pull-right'  data-toggle='tooltip' title=''  data-original-title=" . $this->lang->line('delete') . "><i class='fa fa-trash' aria-hidden='true'></i></a>";
                }

            }

            $nestedData   = array();
            $nestedData[] = $result_value->purpose;
            $nestedData[] = $result_value->name;
            $nestedData[] = $result_value->contact;
            $nestedData[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result_value->date));
            $nestedData[] = $result_value->in_time;
            $nestedData[] = $result_value->out_time;
            $nestedData[] = $action;
            $data[]       = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval($total_result), // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data, // total data array
        );

        echo json_encode($json_data); // send data as json format

    }

    public function add()
    {
        $this->form_validation->set_rules('purpose', $this->lang->line('purpose'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_message('check_default', 'The Purpose field is required.');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'e1' => form_error('purpose'),
                'e2' => form_error('name'),
                'e3' => form_error('date'),
                'e4' => form_error('check_default'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $visitors = array(
                'purpose'      => $this->input->post('purpose'),
                'name'         => $this->input->post('name'),
                'contact'      => $this->input->post('contact'),
                'id_proof'     => $this->input->post('id_proof'),
                'no_of_pepple' => $this->input->post('pepples'),
                'date'         => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'in_time'      => $this->input->post('time'),
                'out_time'     => $this->input->post('out_time'),
                'note'         => $this->input->post('note'),
            );
            $visitor_id = $this->visitors_model->add($visitors);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = 'id' . $visitor_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/front_office/visitors/" . $img_name);
                $this->visitors_model->image_add($visitor_id, $img_name);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_delete')) {
            access_denied();
        }

        $this->visitors_model->delete($id);
    }

    public function edit()
    {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_edit')) {
            access_denied();
        }

        $id = $this->input->post('id');
        $this->form_validation->set_rules('purpose', $this->lang->line('purpose'), 'required|callback_check_default');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_message('check_default', 'The purpose field requred.');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'e1' => form_error('purpose'),
                'e2' => form_error('name'),
                'e3' => form_error('date'),
                'e4' => form_error('check_default'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $visitors = array(
                'purpose'      => $this->input->post('purpose'),
                'name'         => $this->input->post('name'),
                'contact'      => $this->input->post('contact'),
                'id_proof'     => $this->input->post('id_proof'),
                'no_of_pepple' => $this->input->post('pepples'),
                'date'         => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'in_time'      => $this->input->post('time'),
                'out_time'     => $this->input->post('out_time'),
                'note'         => $this->input->post('note'),
            );
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = 'id' . $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/front_office/visitors/" . $img_name);
                $this->visitors_model->image_update($id, $img_name);
            }
            $this->visitors_model->update($id, $visitors);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        echo json_encode($array);
    }

    public function details($id)
    {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_view')) {
            access_denied();
        }

        $data['data'] = $this->visitors_model->visitors_list($id);
        $this->load->view('admin/frontoffice/Visitormodelview', $data);
    }

    public function download($documents)
    {
        $this->load->helper('download');
        $filepath = "./uploads/front_office/visitors/" . $documents;
        $data     = file_get_contents($filepath);
        $name     = $documents;
        force_download($name, $data);
    }

    public function imagedelete($id, $image)
    {
        if (!$this->rbac->hasPrivilege('visitor_book', 'can_delete')) {
            access_denied();
        }
        $this->visitors_model->image_delete($id, $image);
    }

    public function check_default($post_string)
    {
        return $post_string == "" ? false : true;
    }

    public function get_visitor($id)
    {
        $data   = $this->visitors_model->visitors_list($id);
        $a      = array('datedd' => date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($data['date'])));
        $result = array_merge($a, $data);
        echo json_encode($result);
    }

}
