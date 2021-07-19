<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Appointment extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->config->load("mailsms");
        $this->notification            = $this->config->item('notification');
        $this->notificationurl         = $this->config->item('notification_url');
        $this->patient_notificationurl = $this->config->item('patient_notification_url');
        $this->search_type             = $this->config->item('search_type');
        $this->load->library('mailsmsconf');
        $this->load->library('Enc_lib');
        $this->load->model('appoint_priority_model');
        $this->appointment_status = $this->config->item('appointment_status');
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
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('mobileno', $this->lang->line('phone'), 'required|numeric');
        $this->form_validation->set_rules('doctorid', $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('appointment_status', $this->lang->line('appointment') . " " . $this->lang->line('status'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'date'               => form_error('date'),
                'patient_name'       => form_error('patient_name'),
                'mobileno'           => form_error('mobileno'),
                'doctor'             => form_error('doctorid'),
                'message'            => form_error('message'),
                'appointment_status' => form_error('appointment_status'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $date         = $this->input->post('date');

            $date_appoint  =  date("Y-m-d H:i:s", $this->customlib->datetostrtotime($date));
            $patient_id   = $this->input->post('patient_id');
            $patient_name = $this->input->post('patient_name');
            $status       = $this->input->post('appointment_status');
             $consult     = $this->input->post('live_consult');
            if ($consult) {
                $live_consult = $this->input->post('live_consult');
            }else{
                $live_consult = $this->lang->line('no');
            }
            $check_id     = $this->appointment_model->getMaxId();
            if (empty($check_id)) {
                $check_id = 0;
            }

            $max_id = $check_id + 1;
            if ($status == 'approved') {
                $app_no = 'APPNO' . $max_id;
            } else {
                $app_no = '';
            }
            $appointment = array(
                'patient_id'         => $patient_id,
                'appointment_no'     => $app_no,
                'date'               => $date_appoint,
                'priority'           => $this->input->post('priority'),
                'patient_name'       => $this->input->post('patient_name'),
                'gender'             => $this->input->post('gender'),
                'email'              => $this->input->post('email'),
                'mobileno'           => $this->input->post('mobileno'),
                'doctor'             => $this->input->post('doctorid'),
                'message'            => $this->input->post('message'),
                'live_consult'       => $live_consult,
                'source'             => 'Offline',
                'appointment_status' => $status,
            );
            $insert_id = $this->appointment_model->add($appointment);

            $notificationurl = $this->notificationurl;
            $url_link        = $notificationurl["appointment"];
           // $url             = base_url() . $url_link . '/' . $insert_id;
             $url             = $url_link . '/' . $insert_id;
            if ($status != 'approved') {
                $this->appointmentCreateNotification($patient_id, $patient_name, $url,$date_appoint);
            } else {

                $this->appointmentApprovedNotification($patient_id, $this->input->post('doctor'), $this->input->post('patient_name'), $url,$date_appoint);
            } 
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function appointmentCreateNotification($patient_id = '', $patient_name = '', $url,$date_appoint)
    {
        $notification      = $this->notification;
        $notification_desc = $notification["appointment_created"];
        $patient_url       = $this->patient_notificationurl['appointment'];
        $patient_desc      = str_replace(array('<patient>', '<url>'), array($patient_name, base_url() . $patient_url), $notification_desc);
        $desc              = str_replace(array('<patient>', '<url>'), array($patient_name, $url), $notification_desc);

        if (!empty($patient_id)) {
            $notification_data = array('notification_title' => $this->lang->line('notification_appointment_created'),
                'notification_desc'                             => $patient_desc,
                'notification_for'                              => 'Patient',
                'notification_type'                             => 'appointment',
                'receiver_id'                                   => $patient_id,
                'date'                                          => $date_appoint,
                'is_active'                                     => 'yes',
            );
            $admin_notification_data = array('notification_title' => $this->lang->line('notification_appointment_created'),
                'notification_desc'                                   => $desc,
                'notification_for'                                    => 'Super Admin',
                'notification_type'                                   => 'appointment',
                'receiver_id'                                         => '',
                'date'                                                => $date_appoint,
                'is_active'                                           => 'yes',
            );
        } else if (!empty($patient_name)) {
            $notification_data = array('notification_title' => $this->lang->line('notification_appointment_created'),
                'notification_desc'                             => $patient_desc,
                'notification_for'                              => 'Patient',
                'notification_type'                             => 'appointment',
                'receiver_id'                                   => $patient_name,
                'date'                                          => $date_appoint,
                'is_active'                                     => 'yes',
            );
            $admin_notification_data = array('notification_title' => $this->lang->line('notification_appointment_created'),
                'notification_desc'                                   => $desc,
                'notification_for'                                    => 'Super Admin',
                'notification_type'                                   => 'appointment',
                'receiver_id'                                         => '',
                'date'                                                => $date_appoint,
                'is_active'                                           => 'yes',
            );
        }
        $this->notification_model->addSystemNotification($notification_data);
        $this->notification_model->addSystemNotification($admin_notification_data);
    }

    public function status($id)
    {
        $appointment_no = 'APPNO' . $id;
        $data           = array('appointment_no' => $appointment_no, 'appointment_status' => 'approved');
        $this->appointment_model->status($id, $data);
        $appointment_details = $this->appointment_model->getDetails($id);
        $date_appoint        =  $appointment_details['date'];
        $notificationurl     = $this->notificationurl;
        $url_link            = $notificationurl["appointment"];
        $url                 = base_url() . $url_link . '/' . $id;
        ///$url                 = $url_link . '/' . $id;
        $this->appointmentApprovedNotification($appointment_details["patient_id"], $appointment_details["doctor"], $appointment_details["patient_name"], $url,$date_appoint);
        $sender_details = array('patient_id' => $appointment_details["patient_id"], 'appointment_id' => $id, 'contact_no' => $appointment_details["mobileno"], 'email' => $appointment_details["email"]);
        $this->mailsmsconf->mailsms('appointment_approved', $sender_details);
        redirect('admin/appointment/search');
    }

    public function appointmentApprovedNotification($patient_id = '', $doctor_id, $patient_name = '', $url,$date_appoint)
    {

        $notification      = $this->notification;
        $notification_desc = $notification["appointment_approved"];
        $desc              = str_replace(array('<patient>', '<url>'), array($patient_name, $url), $notification_desc);
        $patient_url       = $this->patient_notificationurl['appointment'];
        $patient_desc      = str_replace(array('<patient>', '<url>'), array($patient_name, base_url() . $patient_url), $notification_desc);

        if (!empty($patient_name)) {

            $notification_data = array('notification_title' => $this->lang->line('notification_appointment_approved'),
                'notification_desc'                             => $patient_desc,
                'notification_for'                              => 'Patient',
                'notification_type'                             => 'appointment',
                'receiver_id'                                   => $patient_id,
                'date'                                          => $date_appoint,
                'is_active'                                     => 'yes',
            );
            $admin_notification_data = array('notification_title' => $this->lang->line('notification_appointment_approved'),
                'notification_desc'                                   => $desc,
                'notification_for'                                    => 'Super Admin',
                'notification_type'                                   => 'appointment',
                'receiver_id'                                         => '',
                'date'                                                => $date_appoint,
                'is_active'                                           => 'yes',
            );
            $this->notification_model->addSystemNotification($notification_data);
            $this->notification_model->addSystemNotification($admin_notification_data);
        }

        if (!empty($doctor_id)) {

            $notification_data = array('notification_title' => $this->lang->line('notification_appointment_approved'),
                'notification_desc'                             => $desc,
                'notification_for'                              => 'Doctor',
                'receiver_id'                                   => $doctor_id,
                'notification_type'                             => 'appointment',
                'date'                                          => $date_appoint,
                'is_active'                                     => 'yes',
            );
            $this->notification_model->addSystemNotification($notification_data);
        }
    }

    public function search()
    {
        $this->session->set_userdata('top_menu', 'front_office');
        $app_data                      = $this->session->flashdata('app_data');
        $data['app_data']              = $app_data;
        $doctors                       = $this->staff_model->getStaffbyrole(3);
        $data["doctors"]               = $doctors;
        $patients                      = $this->patient_model->getPatientListall();
        $data["patients"]              = $patients;
        $data["appointment_status"]    = $this->appointment_status;
        $userdata                      = $this->customlib->getUserData();
        $role_id                       = $userdata['role_id'];
        $doctorid                      = "";
        $data['appoint_priority_list'] = $this->appoint_priority_model->appoint_priority_list();
        $doctor_restriction            = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option                = false;
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $disable_option = true;
                $doctorid       = $userdata['id'];
            }
        }
        $data["doctor_select"]  = $doctorid;
        $data["disable_option"] = $disable_option;
        $this->load->view('layout/header');
        $this->load->view('admin/appointment/search.php', $data);
        $this->load->view('layout/footer'); 
    }

    public function appointment_search()
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
        $resultlist   = $this->appointment_model->search_datatable($where_condition);
        $total_result = $this->appointment_model->search_datatable_count($where_condition);
        $data         = array();

        foreach ($resultlist as $result_key => $result_value) {
            $label = "";
            if ($result_value->appointment_status == "approved") {
                $label  = "class='label label-success'";
                $status = $result_value->appointment_no;
            } else if ($result_value->appointment_status == "pending") {
                $label  = "class='label label-warning'";
                $status = $this->lang->line($result_value->appointment_status);
            } else if ($result_value->appointment_status == "cancel") {
                $label  = "class='label label-danger'";
                $status = $this->lang->line($result_value->appointment_status);
            }

            $action = "<div class='rowoptionview'>";
            $action .= "<a href='#' data-toggle='tooltip' title='" . $this->lang->line('show') . "' class='btn btn-default btn-xs'   data-target='#viewModal' onclick='viewDetail(" . $result_value->id . ")'>  <i class='fa fa-reorder'></i> </a>";

            if ($result_value->appointment_status == 'pending') {
                if ($this->rbac->hasPrivilege('appointment_approve', 'can_view')) {

                    $action .= "<a type='submit' href='" . base_url() . "admin/appointment/status/" . $result_value->id . "'  class='btn btn-default btn-xs'  data-toggle='tooltip' title='' onclick='return askconfirm()' data-original-title='" . $this->lang->line('approve') . " " . $this->lang->line('appointment') . "'><i class='fa fa-check' aria-hidden='true'></i></a>";

                } 
            }
        if ($this->module_lib->hasActive('OPD')) { 
            if (($result_value->appointment_status == 'approved') && ($result_value->is_opd != 'yes')) {
                if ($this->rbac->hasPrivilege('appointment_approve', 'can_view')) {
                    if ($this->rbac->hasPrivilege('move_patient_in_opd', 'can_view')) {

                        $action .= "<a href=" . base_url() . 'admin/appointment/moveopd/' . $result_value->id . " onclick='' class='btn btn-default btn-xs'  data-toggle='tooltip' title='' onclick='return confirm(" . $this->lang->line('move') . ' ' . $this->lang->line('to') . ' ' . $this->lang->line('patient') . ' ' . $this->lang->line('in') . ' ' . $this->lang->line('opd') . ")' data-original-title='" . $this->lang->line('move') . " " . $this->lang->line('to') . " " . $this->lang->line('patient') . " " . $this->lang->line('in') . " " . $this->lang->line('opd') . "'> <i class='fas fa-share-square' aria-hidden='true'></i></a>";

                    }
                }
            }
        }
        if ($this->module_lib->hasActive('IPD')) { 
            if (($result_value->appointment_status == 'approved') && ($result_value->is_ipd != 'yes')) {
                if ($this->rbac->hasPrivilege('appointment_approve', 'can_view')) {
                    if ($this->rbac->hasPrivilege('move_patient_in_ipd', 'can_view')) {

                        $action .= "<a href=" . base_url() . 'admin/appointment/moveipd/' . $result_value->id . " onclick='' class='btn btn-default btn-xs'  data-toggle='tooltip' title='' onclick='return confirm(" . $this->lang->line('move') . ' ' . $this->lang->line('to') . ' ' . $this->lang->line('patient') . $this->lang->line('in') . ' ' . $this->lang->line('ipd') . ")' data-original-title='" . $this->lang->line('move') . " " . $this->lang->line('to') . " " . $this->lang->line('patient') . " " . $this->lang->line('in') . " " . $this->lang->line('ipd') . "'> <i class='fas fa-share-square' aria-hidden='true'></i></a>";

                    }
                }
            }
        }

            $action .= "</div'>";
            $first_action = "<a href='#' data-toggle='tooltip'  data-target='#viewModal' title=''  onclick='viewDetail(" . $result_value->id . ")'>";
            
            $nestedData   = array();
            $nestedData[] = $first_action . $result_value->patient_name . "</a>" . $action;
            $nestedData[] = $status;
            $nestedData[] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result_value->date));
            $nestedData[] = $result_value->mobileno;
            $nestedData[] = $result_value->gender;
            $nestedData[] = $result_value->name . " " . $result_value->surname;
            $nestedData[] = $result_value->source;
            $nestedData[] = $result_value->priorityname;
			if ($this->module_lib->hasActive('zoom_live_meeting')) { 
            $nestedData[] = $result_value->live_consult;
			}
            $nestedData[] = "<small " . $label . ">" . $this->lang->line($result_value->appointment_status) . "</small>";
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

    public function getDetails()
    {
        $id             = $this->input->post("appointment_id");
        $result         = $this->appointment_model->getDetails($id);
        $result["date"] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date']));
        echo json_encode($result);
    }

    public function getDetailsAppointment()
    {
        $id             = $this->input->post("appointment_id");
        $result         = $this->appointment_model->getDetailsAppointment($id);
        $result["date"] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date']));
        echo json_encode($result);
    }

    public function getappDetails($id)
    {
        $result         = $this->appointment_model->getDetails($id);
        $result["date"] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date']));
        echo json_encode($result);
    }

    public function update()
    {
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('mobileno', $this->lang->line('phone'), 'required|numeric');
        $this->form_validation->set_rules('doctor', $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('appointment_status', $this->lang->line('appointment') . " " . $this->lang->line('status'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'date'               => form_error('date'),
                'patient_name'       => form_error('patient_name'),
                'mobileno'           => form_error('mobileno'),
                'doctor'             => form_error('doctor'),
                'message'            => form_error('message'),
                'appointment_status' => form_error('appointment_status'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id             = $this->input->post('id');
            $status         = $this->input->post('appointment_status');
            $date           = $this->input->post('date');
            $appoint_status = $this->input->post('appointment_status');
            $consult     = $this->input->post('live_consult');
            if ($consult) {
                $live_consult = $this->input->post('live_consult');
            }else{
                $live_consult = $this->lang->line('no');
            }
            if ($appoint_status == 'approved') {
                $app_no = 'APPNO' . $id;
            } else {
                $app_no = $this->input->post('appointment_no');
            }
            $appointment = array(
                'id'                 => $id,
                'patient_id'         => $this->input->post('patient_id'),
                'appointment_no'     => $app_no,
                'date'               => date("Y-m-d H:i:s", $this->customlib->datetostrtotime($date)),
                'patient_name'       => $this->input->post('patient_name'),
                'priority'           => $this->input->post('priority'),
                'gender'             => $this->input->post('gender'),
                'email'              => $this->input->post('email'),
                'mobileno'           => $this->input->post('mobileno'),
                'doctor'             => $this->input->post('doctor'),
                'message'            => $this->input->post('message'),
                'live_consult'       => $live_consult,
                'appointment_status' => $this->input->post('appointment_status'),
            );

            $this->appointment_model->update($appointment);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $this->appointment_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_success_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function move($id)
    {
        $appointment_details = $this->appointment_model->getDetails($id);
        $patient_name        = $appointment_details['patient_name'];
        $gender              = $appointment_details['gender'];
        $email               = $appointment_details['email'];
        $phone               = $appointment_details['mobileno'];
        $doctor              = $appointment_details['doctor'];
        $note                = $appointment_details['message'];
        $appointment_date    = $appointment_details['date'];
        $amount              = $appointment_details['amount'];
        $live_consult        = $appointment_details['live_consult'];

        $check_patient_id = $this->patient_model->getMaxId();
        if (empty($check_patient_id)) {
            $check_patient_id = 1000;
        }
        $patient_id   = $check_patient_id + 1;
        $patient_data = array(
            'patient_name'      => $patient_name,
            'mobileno'          => $phone,
            'email'             => $email,
            'gender'            => $gender,
            'patient_unique_id' => $patient_id,
            'note'              => $note,
            'is_active'         => 'yes',
        );

        $insert_id          = $this->patient_model->add_patient($patient_data);
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
        if (isset($insert_id)) {
            $check_opd_id = $this->patient_model->getMaxOPDId();
            $opdnoid      = $check_opd_id + 1;

            $opd_data = array(
                'appointment_date' => $appointment_date,
                'opd_no'           => 'OPDN' . $opdnoid,
                'cons_doctor'      => $doctor,
                'patient_id'       => $insert_id,
                'amount'           => $amount,
                'live_consult'     => $live_consult
            );
            $opd_id = $this->patient_model->add_opd($opd_data);

            if (isset($opd_id)) {
                $this->appointment_model->delete($id);
            }
        }

        redirect('admin/appointment/search');
    }

    public function moveopd($id)
    {
        $appointment_details = $this->appointment_model->getDetails($id);
        $appointment_id      = $appointment_details['id'];
        $patient_name        = $appointment_details['patient_name'];
        $patient_id          = $appointment_details['patient_id'];
        $gender              = $appointment_details['gender'];
        $email               = $appointment_details['email'];
        $phone               = $appointment_details['mobileno'];
        $doctor              = $appointment_details['doctor'];
        $note                = $appointment_details['message'];
        $appointment_date    = date($this->customlib->getSchoolDateFormat(true, true), strtotime($appointment_details['date']));
        $amount              = $appointment_details['amount'];
        $live_consult        = $appointment_details['live_consult'];

        $opd_data = array(
            'appointment_date' => $appointment_date,
            'cons_doctor'      => $doctor,
            'patient_id'       => $patient_id,
            'live_consult'     => $live_consult,
        );

        $patient_data = array(
            'patient_name'     => $patient_name,
            'gender'           => $gender,
            'email'            => $email,
            'phone'            => $phone,
            'appointment_date' => $appointment_date,
            'cons_doctor'      => $doctor,
            'live_consult'     => $live_consult,
        );

        if (!empty($patient_id)) {
            $data['opd_data'] = $opd_data;
        } else {
            $data['opd_data'] = $patient_data;
        }

        $this->session->set_userdata("appointment_id", $appointment_id);
        $this->session->set_flashdata('opd_data', $data);
        redirect("admin/patient/search/");
    }

    public function moveipd($id)
    {

        $appointment_details = $this->appointment_model->getDetails($id);
        $appointment_id      = $appointment_details['id'];
        $patient_name        = $appointment_details['patient_name'];
        $patient_id          = $appointment_details['patient_id'];
        $gender              = $appointment_details['gender'];
        $email               = $appointment_details['email'];
        $phone               = $appointment_details['mobileno'];
        $doctor              = $appointment_details['doctor'];
        $note                = $appointment_details['message'];
        $appointment_date    = date($this->customlib->getSchoolDateFormat(true, true), strtotime($appointment_details['date']));
        $amount              = $appointment_details['amount'];
        $live_consult        = $appointment_details['live_consult'];
        $ipd_data            = array(
            'appointment_date' => $appointment_date,
            'cons_doctor'      => $doctor,
            'patient_id'       => $patient_id,
            'live_consult'     => $live_consult,
        );

        $patient_data = array(
            'patient_name'     => $patient_name,
            'gender'           => $gender,
            'email'            => $email,
            'phone'            => $phone,
            'appointment_date' => $appointment_date,
            'cons_doctor'      => $doctor,
            'live_consult'     => $live_consult,
        );

        if (!empty($patient_id)) {
            $data['ipd_data'] = $ipd_data;
        } else {
            $data['ipd_data'] = $patient_data;
        }

        $this->session->set_userdata("appointment_id", $appointment_id);
        $this->session->set_flashdata('ipd_data', $data);
        redirect("admin/patient/ipdsearch/");
    }

    public function getpatientDetails()
    {
        $id     = $this->input->post("patient_id");
        $result = $this->appointment_model->getpatientDetails($id);
        echo json_encode($result);
    }

    public function appointmentreport()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/appointment/appointmentReport');
        $doctorlist         = $this->staff_model->getEmployeeByRoleID(3);
        $data['doctorlist'] = $doctorlist;
        $select             = 'appointment.*,staff.name,staff.surname';
        $join               = array(
            'JOIN staff ON staff.id = appointment.doctor',
        );
        $where              = array();
        $userdata           = $this->customlib->getUserData();
        $role_id            = $userdata['role_id'];
        $user_id            = $userdata["id"];
        $doctorid           = $this->input->post('doctor');
        $disable_option     = false;
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $user_id  = $userdata["id"];
                $doctorid = $user_id;
                $where    = array(
                    "appointment.doctor = " . $user_id,
                );
                $disable_option = true;
            }
        }
        $data['disable_option'] = $disable_option;
        $data['user_id']        = $user_id;
        $data['doctor_select']  = $doctorid;
        if (!empty($doctorid)) {
            $where = array('appointment.doctor =' . $doctorid);
        }
        $table_name = "appointment";

        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }
        if (empty($search_type)) {
            $search_type = "";
            $resultlist  = $this->report_model->getReport($select, $join, $table_name, $where);
        } else {
            $search_table  = "appointment";
            $search_column = "date";
            $resultlist    = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column, $where);
        }

        $data["searchlist"]  = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"]  = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/appointment/appointmentReport.php', $data);
        $this->load->view('layout/footer');
    }
}
