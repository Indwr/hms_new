<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Radio extends Admin_Controller
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
        if (!$this->rbac->hasPrivilege('radiology test', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('parameter_name[]', $this->lang->line('parameter') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_name', $this->lang->line('test') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('short_name', $this->lang->line('short') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_type', $this->lang->line('test') . " " . $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('radiology_category_id', $this->lang->line('category') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('standard_charge', $this->lang->line('standard') . " " . $this->lang->line('charge'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'test_name'             => form_error('test_name'),
                'short_name'            => form_error('short_name'),
                'parameter_name[]'      => form_error('parameter_name[]'),
                'test_type'             => form_error('test_type'),
                'radiology_category_id' => form_error('radiology_category_id'),
                'charge_category_id'    => form_error('charge_category_id'),
                'code'                  => form_error('code'),
                'standard_charge'       => form_error('standard_charge'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $parameter_id = $this->input->post('parameter_name');
            // print_r($parameter_id);
            // exit();
            $radiology = array(
                'test_name'             => $this->input->post('test_name'),
                'short_name'            => $this->input->post('short_name'),
                'test_type'             => $this->input->post('test_type'),
                'radiology_category_id' => $this->input->post('radiology_category_id'),
                'sub_category'          => $this->input->post('sub_category'),
                'report_days'           => $this->input->post('report_days'),
                'charge_id'             => $this->input->post('code'),
            );
            $insert_id = $this->radio_model->add($radiology);

            $i = 0;
            foreach ($parameter_id as $key => $value) {
                $detail = array(
                    'radiology_id' => $insert_id,
                    'parameter_id' => $parameter_id[$i],
                );
                $data[] = $detail;

                $i++;
            }
            $this->radio_model->addparameter($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function parameterview($id)
    {
        $parametername         = $this->radio_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $detail         = $this->radio_model->getparameterDetails($id);
        $data['detail'] = $detail;
        $this->load->view("admin/radio/parameterview", $data);
    }

    public function parameterdetails($id, $valueid = '')
    {
        $parametername         = $this->radio_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $detail         = $this->radio_model->getparameterDetailsforpatient($valueid);
        $data['detail'] = $detail;
        $this->load->view("admin/radio/parameterdetails", $data);
    }

    public function editparameter($id)
    {
        $parametername         = $this->radio_model->getpathoparameter();
        $data["parametername"] = $parametername;
        $detail         = $this->radio_model->getparameterDetails($id);
        $data['detail'] = $detail;
        $this->load->view("admin/radio/editparameter", $data);
    }

    public function patientDetails()
    {
        if (!$this->rbac->hasPrivilege('patient', 'can_view')) {
            access_denied();
        }
        $id   = $this->input->post("id");
        $data = $this->patient_model->patientDetails($id);
        echo json_encode($data);
    }

    public function addpatient()
    {
        if (!$this->rbac->hasPrivilege('patient', 'can_add')) {
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
        if (!$this->rbac->hasPrivilege('radiology test', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'radiology');
        $categoryName            = $this->lab_model->getlabName();
        $data["marital_status"]  = $this->marital_status;
        $data["categoryName"]    = $categoryName;
        $data['charge_category'] = $this->radio_model->getChargeCategory();
        $doctors                 = $this->staff_model->getStaffbyrole(3);
        $data["doctors"]         = $doctors;
        $patients                = $this->patient_model->getPatientListall();
        $data["patients"]        = $patients;
        $parametername           = $this->lab_model->getradioparameter();
        $data["parametername"]   = $parametername;
        $data['resultlist']      = $this->radio_model->searchFullText();
      
        $this->load->view('layout/header');
        $this->load->view('admin/radio/search.php', $data);
        $this->load->view('layout/footer');
    }

    public function getparameterdetails()
    {
        $id     = $this->input->get_post('id');
        $result = $this->lab_model->getradioparameter($id);
        echo json_encode($result);
    }

    public function getDetails()
    {
        if (!$this->rbac->hasPrivilege('radiology test', 'can_view')) {
            access_denied();
        }
        $id     = $this->input->post("radiology_id");
        $result = $this->radio_model->getDetails($id);
        echo json_encode($result);
    }

    public function update()
    {
        if (!$this->rbac->hasPrivilege('radiology test', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('test_name', $this->lang->line('test') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('short_name', $this->lang->line('short') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_type', $this->lang->line('test') . " " . $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('radiology_category_id', $this->lang->line('category') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'test_name'             => form_error('test_name'),
                'short_name'            => form_error('short_name'),
                'test_type'             => form_error('test_type'),
                'radiology_category_id' => form_error('radiology_category_id'),
                'charge_category_id'    => form_error('charge_category_id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id                         = $this->input->post('id');
            $charge_category_id         = $this->input->post('charge_category_id');
            $id                         = $this->input->post('id');
            $charge_category_id         = $this->input->post('charge_category_id');
            $pre_radiology_parameter_id = $this->input->post("previous_radiology_parameter_id[]");
            $pre_radiology_id           = $this->input->post("previous_radiology_id");
            $pre_parameter_id           = $this->input->post("pre_parameter_id[]");
            $previous_parameter_id = $this->input->post("previous_parameter_id[]");
            $new_parameter_id = $this->input->post("new_parameter_id[]");
            $parameter_id = $this->input->post("parameter_name[]");
            $insert_data  = array();
            $radiology    = array(
                'id'                    => $id,
                'test_name'             => $this->input->post('test_name'),
                'short_name'            => $this->input->post('short_name'),
                'test_type'             => $this->input->post('test_type'),
                'radiology_category_id' => $this->input->post('radiology_category_id'),
                'sub_category'          => $this->input->post('sub_category'),
                'report_days'           => $this->input->post('report_days'),
                'charge_id'             => $charge_category_id,
            );

            $delete_arr = array();
            foreach ($previous_parameter_id as $pkey => $pvalue) {
                if (in_array($pvalue, $new_parameter_id)) {

                } else {
                    $delete_arr[] = array('id' => $pvalue);
                }
            }

            $i = 0;
            $j = 0;
            foreach ($parameter_id as $key => $value) {
                if (!empty($pre_radiology_parameter_id)) {
                    if (array_key_exists($i, $pre_radiology_parameter_id)) {
                        $detail = array(
                            'parameter_id' => $parameter_id[$i],
                            'id'           => $pre_radiology_parameter_id[$i],
                        );
                        $data[] = $detail;
                    } else {
                        $j++;
                        $insert_detail = array(
                            'radiology_id' => $pre_radiology_id,
                            'parameter_id' => $parameter_id[$i],
                        );
                        $insert_data[] = $insert_detail;
                    }} else {

                    $insert_detail = array(
                        'radiology_id' => $id,
                        'parameter_id' => $parameter_id[$i],
                    );
                    $insert_data[] = $insert_detail;

                }
                $i++;
            }
            
            $k         = $i - $j;
            $s         = 1;
            $condition = "";
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    if ($s == $k) {
                        $coma = '';
                    } else {
                        $coma = ',';
                    }
                    $condition .= "(" . $value['parameter_id'] . "," . $value['id'] . ")" . $coma;
                    $s++;
                }
            }

            if (!empty($data)) {
                $this->radio_model->updateparameter($condition);
            }
            if (!empty($insert_data)) {
                $this->radio_model->addparameter($insert_data);
            }

            if (!empty($delete_arr)) {
                $this->radio_model->delete_parameter($delete_arr);
            }

            $this->radio_model->update($radiology);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('radiology test', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->radio_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getRadiology()
    {
        if (!$this->rbac->hasPrivilege('radiology test', 'can_view')) {
            access_denied();
        }
        $id     = $this->input->post('radiology_id');
        $result = $this->radio_model->getRadiology($id);
        echo json_encode($result);
    }

    public function testReportBatch()
    {
        if (!$this->rbac->hasPrivilege('add_radio_patient_test_report', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('radiology_id', $this->lang->line('radiology') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('patient_id', $this->lang->line('patient'), 'required');
        $this->form_validation->set_rules('reporting_date', $this->lang->line('reporting').' '.$this->lang->line('date'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'radiology_id'   => form_error('radiology_id'),
                'patient_id'     => form_error('patient_id'),
                'reporting_date' => form_error('reporting_date'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $bill_no = $this->radio_model->getMaxId();
            if (empty($bill_no)) {
                $bill_no = 0;
            }
            $bill           = $bill_no + 1;
            $id             = $this->input->post('radiology_id');
            $patient_id     = $this->input->post('patient_id');
            $reporting_date = $this->input->post("reporting_date");

            $report_batch = array(
                'bill_no'           => $bill,
                'radiology_id'      => $id,
                'patient_id'        => $patient_id,
                'customer_type'     => $this->input->post('customer_type'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date'    => date('Y-m-d', $this->customlib->datetostrtotime($reporting_date)),
                'description'       => $this->input->post('description'),
                'generated_by'      => $this->session->userdata('hospitaladmin')['id'],
                'apply_charge'      => $this->input->post('apply_charge'),
            );
            $insert_id = $this->radio_model->testReportBatch($report_batch);
            $paramet_details = $this->radio_model->getparameterBypathology($id);
            foreach ($paramet_details as $pkey => $pvalue) {
                # code...

                $paramet_insert_array = array('radiology_report_id' => $insert_id,
                    'parameter_id'       => $pvalue["parameter_id"],

                );

                $insert_into_parameter = $this->radio_model->addParameterforPatient($paramet_insert_array);

            }

            if (isset($_FILES["radiology_report"]) && !empty($_FILES['radiology_report']['name'])) {
                $fileInfo = pathinfo($_FILES["radiology_report"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["radiology_report"]["tmp_name"], "./uploads/radiology_report/" . $img_name);
                $data_img = array('id' => $insert_id, 'radiology_report' => $img_name);
                $this->radio_model->testReportBatch($data_img);
            }
            $array = array('status' => 'success', 'id' => $insert_id, 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function getReportDetails($id, $parameter_id = '')
    {

        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $print_details            = $this->printing_model->get('', 'radiology');
        $data['print_details']    = $print_details;
        $result                   = $this->radio_model->getBillDetails($id);
        $data['result']           = $result;
        $detail                   = $this->radio_model->getAllBillDetails($id);
        $data['detail']           = $detail;
        $parameterdetails         = $this->radio_model->getparameterDetailsforpatient($id);
        $data['parameterdetails'] = $parameterdetails;
        $this->load->view('admin/radio/printReport', $data);
    }

    public function getBillDetails($id, $parameter_id = '')
    {

        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $print_details            = $this->printing_model->get('', 'radiology');
        $data['print_details']    = $print_details;
        $result                   = $this->radio_model->getBillDetails($id);
        $data['result']           = $result;
        $detail                   = $this->radio_model->getAllBillDetails($id);
        $data['detail']           = $detail;
        $parameterdetails         = $this->radio_model->getparameterDetailsforpatient($id);
        $data['parameterdetails'] = $parameterdetails;
        $this->load->view('admin/radio/printBill', $data);
    }

    public function getTestReportBatch()
    {

        if (!$this->rbac->hasPrivilege('add_radio_patient_test_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'radiology');
        $id               = $this->input->post("radiology_id");
        $doctors          = $this->staff_model->getStaffbyrole(3);
        $data["doctors"]  = $doctors;
        $patients         = $this->patient_model->getPatientListall();
        $data["patients"] = $patients;
      
        $this->load->view('layout/header');
        $this->load->view('admin/radio/reportDetail', $data);
        $this->load->view('layout/footer');
    }

    public function getRadiologyReport()
    {
        if (!$this->rbac->hasPrivilege('add_radio_patient_test_report', 'can_view')) {
            access_denied();
        }
        $id                       = $this->input->post('id');
        $result                   = $this->radio_model->getRadiologyReport($id);
        $result['reporting_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['reporting_date']));

        echo json_encode($result);
    }

    public function updateTestReport()
    {
        if (!$this->rbac->hasPrivilege('add_radio_patient_test_report', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('id', 'Id', 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id             = $this->input->post('id');
            $reporting_date = $this->input->post("reporting_date");

            $report_batch = array(
                'id'                => $id,
                'patient_name'      => $this->input->post('patient_name'),
                'patient_id'        => $this->input->post('patient_id_radio'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date'    => date('Y-m-d', $this->customlib->datetostrtotime($reporting_date)),
                'description'       => $this->input->post('description'),
                'apply_charge'      => $this->input->post('apply_charge'),
            );
            $this->radio_model->updateTestReport($report_batch);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));

            if (!empty($_FILES['radiology_report']['name'])) {
                $config['upload_path']   = 'uploads/radiology_report/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name']     = $_FILES['radiology_report']['name'];
                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('radiology_report')) {
                    $uploadData = $this->upload->data();
                    $picture    = $uploadData['file_name'];
                    $data_img   = array('id' => $id, 'radiology_report' => $picture);
                    $this->radio_model->updateTestReport($data_img);
                }
            }
        }
        echo json_encode($array);
    }

    public function parameteraddvalue()
    {
        if (!$this->rbac->hasPrivilege('radiology test', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $id             = $this->input->post('id');
            $reporting_date = $this->input->post("reporting_date");
            $report_batch   = array(
                'id'                => $id,
               
                'patient_id'        => $this->input->post('patient_id_radio'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date'    => date('Y-m-d', $this->customlib->datetostrtotime($reporting_date)),
                'description'       => $this->input->post('description'),                
                'apply_charge'      => $this->input->post('apply_charge'),
            );

            $parameter_id    = $this->input->post('parameter_id[]');
            $parameter_value = $this->input->post('parameter_value[]');
            $i               = 0;
            $parameter_array = array();
            if (!empty($parameter_id)) {
                foreach ($parameter_id as $pkey => $pvalue) {
                    $parameter_value_arr = array(
                        'id'                     => $pvalue,
                        'radiology_report_id'    => $id,
                        'radiology_report_value' => $parameter_value[$i],
                    );
                    

                    $this->radio_model->addparametervalue($parameter_value_arr);
                    $i++;
                }
            }

            if (!empty($_FILES['radiology_report']['name'])) {
                $config['upload_path']   = 'uploads/radiology_report/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name']     = $_FILES['radiology_report']['name'];

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $fileInfo = pathinfo($_FILES["radiology_report"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                $fileInfo = pathinfo($_FILES["radiology_report"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];

                $data_img = array('id' => $id, 'radiology_report' => $img_name);
                $this->radio_model->updateTestReport($data_img);

                move_uploaded_file($_FILES["radiology_report"]["tmp_name"], "./uploads/radiology_report/" . $img_name);

                // }
            }

            $this->radio_model->updateTestReport($report_batch);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function download($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/radiology_report/" . $doc;
        $data     = file_get_contents($filepath);
        force_download($doc, $data);
    }

    public function deleteTestReport($id)
    {
        if (!$this->rbac->hasPrivilege('add_radio_patient_test_report', 'can_delete')) {
            access_denied();
        }
        $this->radio_model->deleteTestReport($id);
    }

    public function radiologyReport()
    {
        if (!$this->rbac->hasPrivilege('radiology test', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/radio/radiologyreport');
        $select = 'radiology_report.*, radio.id, radio.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name';
        $join   = array(
            'JOIN radio ON radiology_report.radiology_id = radio.id',
            'LEFT JOIN staff ON radiology_report.consultant_doctor = staff.id',
            'JOIN charges ON charges.id = radio.charge_id',
            'JOIN patients ON patients.id = radiology_report.patient_id',
        );
        $table_name  = "radiology_report";
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

            $search_table  = "radiology_report";
            $search_column = "reporting_date";
            $resultlist    = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column);
        }
        $data["searchlist"]  = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"]  = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/radio/radiologyReport.php', $data);
        $this->load->view('layout/footer');
    }
}