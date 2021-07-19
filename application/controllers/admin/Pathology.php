<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pathology extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('mailsmsconf');
        $this->marital_status       = $this->config->item('marital_status');
        $this->payment_mode         = $this->config->item('payment_mode');
        $this->search_type          = $this->config->item('search_type');
        $this->blood_group          = $this->config->item('bloodgroup');
        $this->charge_type          = $this->customlib->getChargeMaster();
        $data["charge_type"]        = $this->charge_type;
        $this->patient_login_prefix = "pat";
    }

    public function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('parameter_name[]', $this->lang->line('parameter') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_name', $this->lang->line('test') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('short_name', $this->lang->line('short') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_type', $this->lang->line('test') . " " . $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('pathology_category_id', $this->lang->line('category') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('standard_charge', $this->lang->line('standard') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'test_name'          => form_error('test_name'),
                'short_name'         => form_error('short_name'),
                'test_type'          => form_error('test_type'),
                'pathology_category_id'    => form_error('pathology_category_id'),
                'parameter_name[]'   => form_error('parameter_name[]'),
                'charge_category_id' => form_error('charge_category_id'),
                'code'               => form_error('code'),
                'standard_charge'    => form_error('standard_charge'),               
                
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $parameter_id = $this->input->post('parameter_name');
            $pathology    = array(
                'test_name'             => $this->input->post('test_name'),
                'short_name'            => $this->input->post('short_name'),
                'test_type'             => $this->input->post('test_type'),
                'pathology_category_id' => $this->input->post('pathology_category_id'),
                'sub_category'          => $this->input->post('sub_category'),
                'report_days'           => $this->input->post('report_days'),
                'method'                => $this->input->post('method'),
                'charge_id'             => $this->input->post('code'),
            );

            $insert_id = $this->pathology_model->add($pathology);

            $i = 0;
            foreach ($parameter_id as $key => $value) {
                $detail = array(
                    'pathology_id' => $insert_id,
                    'parameter_id' => $parameter_id[$i],
                );
                $data[] = $detail;

                $i++;
            }

            $this->pathology_model->addparameter($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function addpatient()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('name'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $check_patient_id = $this->patient_model->getMaxId();
            if (empty($check_patient_id)) {
                $check_patient_id = 1000;
            }

            $patient_id = $check_patient_id + 1;

            $patient_data = array(
                'patient_name'      => $this->input->post('name'),
                'mobileno'          => $this->input->post('contact'),
                'marital_status'    => $this->input->post('marital_status'),
                'email'             => $this->input->post('email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'blood_group'       => $this->input->post('blood_group'),
                'address'           => $this->input->post('address'),
                'known_allergies'   => $this->input->post('known_allergies'),
                'patient_unique_id' => $patient_id,
                'note'              => $this->input->post('note'),
                'age'               => $this->input->post('age'),
                'month'             => $this->input->post('month'),
                'is_active'         => 'yes',
            );
            $insert_id = $this->patient_model->add_patient($patient_data);

            $user_password      = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_patient_login = array(
                'username' => $this->patient_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id'  => $insert_id,
                'role'     => 'patient',
            );
            $this->user_model->add($data_patient_login);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/patient_images/' . $img_name);
                $this->patient_model->add($data_img);
            }
        }
        echo json_encode($array);
    }

    public function search()
    {

        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'pathology');
        $categoryName         = $this->pathology_category_model->getcategoryName();
        $data["categoryName"] = $categoryName;

        $parametername         = $this->pathology_category_model->getpathoparameter();
        $data["parametername"] = $parametername;

        $data["title"]           = 'pathology';
        $data['charge_category'] = $this->pathology_model->getChargeCategory();
        $doctors                 = $this->staff_model->getStaffbyrole(3);
        $data["doctors"]         = $doctors;
        $patients                = $this->patient_model->getPatientListall();
        $data["patients"]        = $patients;
       
        $result         = $this->pathology_model->getPathology();
        $data['result'] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/pathology/search',$data);
        $this->load->view('layout/footer');
    }

     public function report_search(){
 
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition=array();
        if(!empty($_POST['search']['value'])) {
            $where_condition=array('search'=>$_POST['search']['value']);
        }
        $resultlist = $this->pathology_model->searchreport_datatable($where_condition);
        $total_result = $this->pathology_model->searchreport_datatable_count($where_condition);
        $data = array();
       
        foreach ($resultlist as $result_key => $result_value) { 
            
            if (!empty($result_value->apply_charge)) {
                $charge = $result_value->apply_charge;
            } else {
                $charge = $detail->standard_charge;
            }
         $action ="<div class='rowoptionview'>"; 
            if (!empty($result_value->pathology_report)) {
               $action.="<a href=".base_url().'admin/pathology/download/'.$result_value->pathology_report." class='btn btn-default btn-xs'  data-toggle='tooltip' title='".$this->lang->line('download')."'><i class='fa fa-download' aria-hidden='true'></i></a>";  
            }
          
            if ($this->rbac->hasPrivilege('add_patho_patient_test_report', 'can_edit')) {
            $action.="<a href='#' onclick='addParametervalue(".$result_value->id.",".$result_value->pathology_id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('add').'/'.$this->lang->line('edit').' '.$this->lang->line('parameter').' '.$this->lang->line('value')."'><i class='fa fa-pencil' aria-hidden='true'></i></a>"; 
            }

           if ($this->rbac->hasPrivilege('pathology_print_report', 'can_view')) {
            $action.="<a href='#' onclick='viewDetailReport(".$result_value->id.",".$result_value->pathology_id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('print').' '.$this->lang->line('report')."'><i class='fa fa-print' aria-hidden='true'></i></a>"; 

            }

            if ($this->rbac->hasPrivilege('pathology_print_bill', 'can_view')) {
            $action.="<a href='#' onclick='viewDetailbill(".$result_value->id.",".$result_value->pathology_id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('print').' '.$this->lang->line('bill')."'><i class='fa fa-print' aria-hidden='true'></i></a>"; 

            }

            if ($this->rbac->hasPrivilege('add_patho_patient_test_report', 'can_delete')) {
            $action.="<a href='#' onclick='deleterecord(".$result_value->id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('delete')."'><i class='fa fa-trash' aria-hidden='true'></i></a>"; 
            }
       
        $action.="</div'>";
        $nestedData=array();  
      
        $nestedData[]= $result_value->bill_no.$action;
        $nestedData[]= $result_value->reporting_date;
        $nestedData[]= $result_value->patient_name;
        $nestedData[]= $result_value->test_name;
        $nestedData[]= $result_value->short_name;
        $nestedData[]= $result_value->name." ".$result_value->surname;
        $nestedData[]= $result_value->description;
        $nestedData[]= $charge;         
        $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval($total_result),  // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
            );

    echo json_encode($json_data);  // send data as json format

    }

    public function editparameter($id)
    {       
        $parametername         = $this->pathology_category_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $detail         = $this->pathology_category_model->getparameterDetails($id);
        $data['detail'] = $detail;       
        $this->load->view("admin/pathology/editparameter", $data);
    }

    public function parameterview($id, $value_id = '')
    {        
        $parametername         = $this->pathology_category_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $detail         = $this->pathology_category_model->getparameterDetails($id, $value_id);
        $data['detail'] = $detail;
        $this->load->view("admin/pathology/parameterview", $data);
    }

    public function parameterdetails($id, $value_id = '')
    {

        $parametername         = $this->pathology_category_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $detail = $this->pathology_category_model->getparameterDetailsforpatient($value_id);       

        $data['detail'] = $detail;
        $this->load->view("admin/pathology/parameterdetails", $data);
    }

    public function getparameterdetails()
    {
        $id     = $this->input->get_post('id');
        $result = $this->pathology_category_model->getpathoparameter($id);
        echo json_encode($result);
    }

    public function getDetails()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $id     = $this->input->post("pathology_id");
        $result = $this->pathology_model->getDetails($id);
        echo json_encode($result);
    }

    public function update()
    {

        if (!$this->rbac->hasPrivilege('pathology test', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('test_name', $this->lang->line('test') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('short_name', $this->lang->line('short') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_type', $this->lang->line('test') . " " . $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('pathology_category_id', $this->lang->line('category') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'test_name'             => form_error('test_name'),
                'short_name'            => form_error('short_name'),
                'test_type'             => form_error('test_type'),
                'pathology_category_id' => form_error('pathology_category_id'),
                'code'                  => form_error('code'),
                'charge_category_id'    => form_error('charge_category_id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $id                         = $this->input->post('id');
            $charge_category_id         = $this->input->post('charge_category_id');
            $pre_pathology_parameter_id = $this->input->post("previous_pathology_parameter_id[]");
            $pre_pathology_id           = $this->input->post("previous_pathology_id");
            $pre_parameter_id = $this->input->post("previous_parameter_id[]");
            $new_parameter_id = $this->input->post("new_parameter_id[]");
            $parameter_id = $this->input->post("parameter_name[]");
            $insert_data = array();
            $pathology   = array(
                'id'                    => $id,
                'test_name'             => $this->input->post('test_name'),
                'short_name'            => $this->input->post('short_name'),
                'test_type'             => $this->input->post('test_type'),
                'pathology_category_id' => $this->input->post('pathology_category_id'),
                'sub_category'          => $this->input->post('sub_category'),
                'report_days'           => $this->input->post('report_days'),
                'method'                => $this->input->post('method'),
                'charge_id'             => $this->input->post('code'),
            );

        
            $i = 0;
            $j = 0;
            foreach ($parameter_id as $key => $value) {
                if (array_key_exists($i, $pre_pathology_parameter_id)) {
                    $detail = array(
                        'parameter_id' => $parameter_id[$i],
                        'id'           => $pre_pathology_parameter_id[$i],
                    );
                    $data[] = $detail;
                } else {
                    $j++;
                    $insert_detail = array(
                        'pathology_id' => $id,
                        'parameter_id' => $parameter_id[$i],
                    );
                    $insert_data[] = $insert_detail;
                }
                $i++;
            }

            $k         = $i - $j;
            $s         = 1;
            $condition = "";
            foreach ($data as $key => $value) {
                if ($s == $k) {
                    $coma = '';
                } else {
                    $coma = ',';
                }
                $condition .= "(" . $value['parameter_id'] . "," . $value['id'] . ")" . $coma;
                $s++;
            }

            $delete_arr = array();
            foreach ($pre_parameter_id as $pkey => $pvalue) {
                if (in_array($pvalue, $new_parameter_id)) {

                } else {
                    $delete_arr[] = array('id' => $pvalue);
                }
            }

            $this->pathology_model->updateparameter($condition);

            if (!empty($insert_data)) {
                $this->pathology_model->addparameter($insert_data);
            }

            if (!empty($delete_arr)) {
                $this->pathology_model->delete_parameter($delete_arr);
            }

            $this->pathology_model->update($pathology);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->pathology_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getPathology()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }

        $id     = $this->input->post('pathology_id');
        $result = $this->pathology_model->getPathology($id);
        echo json_encode($result);
    }

    public function getPathologyReport()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $id                       = $this->input->post('id');
        $result                   = $this->pathology_model->getPathologyReport($id);
        $result['reporting_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['reporting_date']));
        echo json_encode($result);
    }

    public function getPathologyparameterReport()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $id                       = $this->input->post('id');
        $result                   = $this->pathology_model->getPathologyparameterReport($id);
        $result['reporting_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['reporting_date']));
        echo json_encode($result);
    }

    public function updateTestReport()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('apply_charge', $this->lang->line('applied') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('pathology_report', $this->lang->line('file'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'id'           => form_error('id'),
                'patient_name' => form_error('patient_name'),
                'apply_charge' => form_error('apply_charge'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $reporting_date = $this->input->post("reporting_date");

            $id           = $this->input->post('id');
            $report_batch = array(
                'id'                => $id,
                'patient_name'      => $this->input->post('patient_name'),
                'patient_id'        => $this->input->post('patient_id_patho'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date'    => date('Y-m-d', $this->customlib->datetostrtotime($reporting_date)),
                'description'       => $this->input->post('description'),
                'apply_charge'      => $this->input->post('apply_charge'),
            );

            $this->pathology_model->updateTestReport($report_batch);

            if (!empty($_FILES['pathology_report']['name'])) {
                $config['upload_path']   = 'uploads/pathology_report/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
                $config['file_name']     = $_FILES['pathology_report']['name'];

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('pathology_report')) {
                    $uploadData = $this->upload->data();
                    $picture    = $uploadData['file_name'];
                } else {
                    $picture = "";
                }

                $data_img = array('id' => $id, 'pathology_report' => $picture);
                $this->pathology_model->updateTestReport($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function parameteraddvalue()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'id' => form_error('id'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $id = $this->input->post('id');
            $reporting_date = $this->input->post("reporting_date");
            $report_batch   = array(
                'id'                => $id,
                'patient_id'        => $this->input->post('patient_id_patho'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date'    => date('Y-m-d', $this->customlib->datetostrtotime($reporting_date)),
                'description'       => $this->input->post('description'),
                'apply_charge'      => $this->input->post('apply_charge'),
            );

            $parameter_id    = $this->input->post('parameter_id[]');
            $parameter_value = $this->input->post('parameter_value[]');
            $par_id          = $this->input->post('parid[]');
            $pathology_id    = $this->input->post('pathologyid');
            $update_id  = $this->input->post('update_id[]');
            $preport_id = $this->input->post('preport_id[]');

            $i               = 0;
            $parameter_array = array();
            foreach ($update_id as $pkey => $pvalue) {
                $parameter_value_arr = array(
                    'id'                     => $pvalue,
                    'pathology_report_id'    => $preport_id[$i],
                    'pathology_report_value' => $parameter_value[$i],
                );

                $this->pathology_model->addparametervalue($parameter_value_arr);
                $i++;
            }

            if (!empty($_FILES['pathology_report']['name'])) {
                $config['upload_path']   = 'uploads/pathology_report/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
                $config['file_name']     = $_FILES['pathology_report']['name'];
                $fileInfo                = pathinfo($_FILES["pathology_report"]["name"]);
                $img_name                = $id . '.' . $fileInfo['extension'];

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                move_uploaded_file($_FILES["pathology_report"]["tmp_name"], "./uploads/pathology_report/" . $img_name);

                $data_img = array('id' => $id, 'pathology_report' => $img_name);
                $this->pathology_model->updateTestReport($data_img);
            }

            $this->pathology_model->updateTestReport($report_batch);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function testReportBatch()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('patient_id', $this->lang->line('patient'), 'required');
        $this->form_validation->set_rules('pathology_id', $this->lang->line('pathology') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('apply_charge', $this->lang->line('applied') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('pathology_report', $this->lang->line('file'), 'callback_handle_upload');
        $this->form_validation->set_rules('reporting_date', 'Reporting Date', 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'patient_id'       => form_error('patient_id'),
                'pathology_id'     => form_error('pathology_id'),
                'apply_charge'     => form_error('apply_charge'),
                'reporting_date'   => form_error('reporting_date'),
                'pathology_report' => form_error('pathology_report'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $bill_no = $this->pathology_model->getMaxId();
            if (empty($bill_no)) {
                $bill_no = 0;
            }
            $bill           = $bill_no + 1;
            $id             = $this->input->post('pathology_id');
            $patient_id     = $this->input->post('patient_id');
            $reporting_date = $this->input->post("reporting_date");

            $report_batch = array(
                'bill_no'           => $bill,
                'pathology_id'      => $id,
                'patient_id'        => $patient_id,
                'customer_type'     => $this->input->post('customer_type'),
                'patient_name'      => $this->input->post('patient_name'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date'    => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($reporting_date)),
                'description'       => $this->input->post('description'),
                'apply_charge'      => $this->input->post('apply_charge'),
                'generated_by'      => $this->session->userdata('hospitaladmin')['id'],
                'pathology_report'  => '',
            );
           
            $insert_id = $this->pathology_model->testReportBatch($report_batch);
            $paramet_details = $this->pathology_model->getparameterBypathology($id);
            foreach ($paramet_details as $pkey => $pvalue) {
                # code...

                $paramet_insert_array = array('pathology_report_id' => $insert_id,
                    'parameter_id'                                      => $pvalue["parameter_id"],

                );

                $insert_into_parameter = $this->pathology_model->addParameterforPatient($paramet_insert_array);
            }

            if (isset($_FILES["pathology_report"]) && !empty($_FILES['pathology_report']['name'])) {
                $fileInfo = pathinfo($_FILES["pathology_report"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["pathology_report"]["tmp_name"], "./uploads/pathology_report/" . $img_name);
                $data_img = array('id' => $insert_id, 'pathology_report' => $img_name);
                $this->pathology_model->testReportBatch($data_img);
            }

            $array = array('status' => 'success', 'id' => $insert_id, 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type         = $_FILES["file"]['type'];
            $file_size         = $_FILES["file"]["size"];
            $file_name         = $_FILES["file"]["name"];
            $allowed_extension = $image_validate['allowed_extension'];
            $ext               = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_mime_type = $image_validate['allowed_mime_type'];
            if ($files = @getimagesize($_FILES['file']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array(strtolower($ext), $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $image_validate['upload_size']) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Not Allowed");
                return false;
            }

            return true;
        }
        return true;
    }

    public function getTestReportBatch()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'pathology');
        $id               = $this->input->post("id");
        $doctors          = $this->staff_model->getStaffbyrole(3);
        $data["doctors"]  = $doctors;
        $patients         = $this->patient_model->getPatientListall();
        $data["patients"] = $patients;     

        $this->load->view('layout/header');
        $this->load->view('admin/pathology/reportDetail', $data);
        $this->load->view('layout/footer');
    }

    public function getBillDetails($id, $parameter_id)
    {

        $print_details         = $this->printing_model->get('', 'pathology');
        $data['print_details'] = $print_details;
        $data['id']            = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }

        $result         = $this->pathology_model->getBillDetails($id);
        $data['result'] = $result;
        $detail         = $this->pathology_model->getAllBillDetails($id);
        $data['detail'] = $detail;
        $parametername         = $this->pathology_category_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $parameterdetails         = $this->pathology_category_model->getparameterDetailsforpatient($id);
        $data['parameterdetails'] = $parameterdetails;
        $this->load->view('admin/pathology/printBill', $data);
    }

    public function getReportDetails($id, $parameter_id)
    {

        $print_details         = $this->printing_model->get('', 'pathology');
        $data['print_details'] = $print_details;
        $data['id']            = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }

        $result                   = $this->pathology_model->getBillDetails($id);
        $data['result']           = $result;
        $detail                   = $this->pathology_model->getAllBillDetails($id);
        $data['detail']           = $detail;
        $parametername            = $this->pathology_category_model->getpathoparameter();
        $data["parametername"]    = $parametername;
        $parameterdetails         = $this->pathology_category_model->getparameterDetailsforpatient($id);
        $data['parameterdetails'] = $parameterdetails;

        $this->load->view('admin/pathology/printReport', $data);
    }

    public function download($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/pathology_report/" . $doc;
        $data     = file_get_contents($filepath);
        force_download($doc, $data);
    }

    public function deleteTestReport($id)
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_delete')) {
            access_denied();
        }
        $this->pathology_model->deleteTestReport($id);
    }

    public function pathologyReport()
    {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/pathology/pathologyreport');
        $select = 'pathology_report.*, pathology.id, pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name';
        $join   = array(
            'JOIN pathology ON pathology_report.pathology_id = pathology.id',
            'LEFT JOIN staff ON pathology_report.consultant_doctor = staff.id',
            'JOIN charges ON charges.id = pathology.charge_id', 'JOIN patients ON patients.id = pathology_report.patient_id',
        );
        $table_name = "pathology_report";       
        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }
        if (empty($search_type)) {
            $search_type = "";
            $resultlist  = $this->report_model->getReport($select, $join, $table_name);
        } else {

            $search_table  = "pathology_report";
            $search_column = "reporting_date";
            $resultlist    = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column);
        }

        $data["searchlist"]  = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"]  = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/pathology/pathologyReport.php', $data);
        $this->load->view('layout/footer');
    }

}
