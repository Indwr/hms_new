<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Conference extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mailsmsconf');
        $this->load->model(array('conference_model', 'conferencehistory_model'));
        $this->conference_setting = $this->setting_model->getzoomsetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('setting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'conference/zoom_api_setting');
        $data          = array();
        $data['title'] = 'Zoom Setting';
        $setting       = $this->setting_model->getzoomsetting();
        if (empty($setting)) {
            $setting                  = new stdClass();
            $setting->zoom_api_key    = "";
            $setting->zoom_api_secret = "";
        }

        $data['setting'] = $setting;
        $this->form_validation->set_rules('zoom_api_key', $this->lang->line('zoom_api_key'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('zoom_api_secret', $this->lang->line('zoom_api_secret'), 'required|trim|xss_clean');

        if ($this->form_validation->run() === false) {
            $data['title'] = 'Email Config List';
            $this->load->view('layout/header', $data);
            $this->load->view('admin/conference/index', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $data_insert = array(
                'id'              => $this->input->post('id'),
                'zoom_api_key'    => $this->input->post('zoom_api_key'),
                'zoom_api_secret' => $this->input->post('zoom_api_secret'),
                'use_doctor_api'  => $this->input->post('use_doctor_api'),
                'use_zoom_app'    => $this->input->post('use_zoom_app'),
                'opd_duration'    => $this->input->post('opd_duration'),
                'ipd_duration'    => $this->input->post('ipd_duration'),
            );
            $this->setting_model->addzoomdetails($data_insert);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/conference');
        }
    }

    public function getopdipd()
    {
        $opd_ipd    = $this->input->post('opdipd_group');
        $patient_id = $this->input->post('patient_id');
        if ($opd_ipd == $this->lang->line('opd')) {
            $result = $this->patient_model->getOpd($patient_id);
        } elseif ($opd_ipd == $this->lang->line('ipd')) {
            $result = $this->patient_model->getIpd($patient_id);
        }
        echo json_encode($result);
    }

    

    public function consult()
    {
        if (!$this->rbac->hasPrivilege('live_consultation', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'conference');
        $this->session->set_userdata('sub_menu', 'conference/live_consult');
        $data                       = array();
        $role                       = json_decode($this->customlib->getStaffRole());
        $patient                    = $this->patient_model->getpatient();
        $data['patientlist']        = $patient;
        $patients                   = $this->patient_model->getPatientListall();
        $data["patients"]           = $patients;
        $data['role']               = $role;
        $staff_id                   = $this->customlib->getStaffID();
        $data['logged_staff_id']    = $staff_id;
        $doctors                    = $this->staff_model->getStaffbyrole(3);
        $userdata                   = $this->customlib->getUserData();
        $role_id                    = $userdata["role_id"];
        $data["doctors"]            = $doctors;
        $conference_setting         = $this->setting_model->getzoomsetting();
        $data['conference_setting'] = $conference_setting;
        $doctorid                   = "";
        $doctor_restriction         = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option             = false;
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $disable_option = true;
                $doctorid       = $userdata['id'];
            }
        }
        $data["doctor_select"]  = $doctorid;
        $data["disable_option"] = $disable_option;
        if ($role->id == 3) {
            $stafflist           = $this->staff_model->getEmployee(3);
            $data['stafflist']   = $stafflist;
            $data['consult']     = array();
            $days                = $this->customlib->getDaysname();
            $data['conferences'] = $this->conference_model->getByconsult($this->customlib->getStaffID());
            $userdata            = $this->customlib->getUserData();
            $role_id             = $userdata["role_id"];
            $condition           = "";

        } else {
            $data['conferences'] = $this->conference_model->getByconsult();
        }

        $this->load->view('layout/header');
        if ($role->id == 3) {
            $this->load->view('admin/conference/consult', $data);
        } else {
            $roles         = $this->role_model->get();
            $data['roles'] = $roles;
            $this->load->view('admin/conference/staffconsult', $data);
        }
        $this->load->view('layout/footer');
    }

    public function join($type, $id)
    {
        $zoom_api_key    = "";
        $zoom_api_secret = "";
        if ($type == "consult") {
            $leaveUrl = "admin/conference/consult";
        } elseif ($type == "meeting") {
            $leaveUrl = "admin/conference/meeting";
        }
        $live = $this->conference_model->getdata($id);
        if ($live->api_type == "global") {
            $zoomsetting = $this->setting_model->getzoomsetting();
            if (!empty($zoomsetting)) {
                $zoom_api_key    = $zoomsetting->zoom_api_key;
                $zoom_api_secret = $zoomsetting->zoom_api_secret;
            }
        } else {
            $staff           = $this->staff_model->get($live->created_id);
            $zoom_api_key    = $staff['zoom_api_key'];
            $zoom_api_secret = $staff['zoom_api_secret'];
        }

        $meetingID                = json_decode($live->return_response)->id;
        $data['zoom_api_key']     = $zoom_api_key;
        $data['zoom_api_secret']  = $zoom_api_secret;
        $data['meetingID']        = $meetingID;
        $data['meeting_password'] = $live->password;
        $data['leaveUrl']         = $leaveUrl;
        $data['title']            = $live->title;
        if ($type == "meeting") {
            $data['host'] = ($live->create_by_surname == "") ? $live->create_by_name : $live->create_by_name . " " . $live->create_by_surname;
            $staff_id     = $this->customlib->getStaffID();
            if ($live->created_id != $staff_id) {
                $data_insert = array(
                    'conference_id' => $id,
                    'staff_id'      => $staff_id,
                );
                $this->conferencehistory_model->updatehistory($data_insert, 'staff');
            }
        } elseif ($type == "consult") {
            $data['host'] = ($live->create_for_surname == "") ? $live->create_for_name : $live->create_for_name . " " . $live->create_for_surname;
        }
        $data['name'] = $this->customlib->getAdminSessionUserName();
        $this->load->view('admin/conference/join', $data);
    }

    public function getcredential()
    {
        $response                    = array();
        $staff                       = $this->staff_model->get($this->customlib->getStaffID());
        $response['zoom_api_key']    = $staff['zoom_api_key'];
        $response['zoom_api_secret'] = $staff['zoom_api_secret'];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function getlivestatus()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $conference_id              = $this->input->post('id');
            $live                       = $this->conference_model->getdata($conference_id);
            $data['conference_setting'] = $this->conference_setting;
            if ($live->api_type == "global") {
                $zoomsetting = $this->setting_model->getzoomsetting();
                if (!empty($zoomsetting)) {
                    $zoom_api_key    = $zoomsetting->zoom_api_key;
                    $zoom_api_secret = $zoomsetting->zoom_api_secret;
                }
            } else {
                $staff           = $this->staff_model->get($live->created_id);
                $zoom_api_key    = $staff['zoom_api_key'];
                $zoom_api_secret = $staff['zoom_api_secret'];
            }
            $params = array(
                'zoom_api_key'    => $zoom_api_key,
                'zoom_api_secret' => $zoom_api_secret,
            );
            $this->load->library('zoom_api', $params);
            $meetingID               = json_decode($live->return_response)->id;
            $api_Response            = $this->zoom_api->getMeeting($meetingID);
            $data['api_Response']    = $api_Response;
            $staff_id                = $this->customlib->getStaffID();
            $data['logged_staff_id'] = $staff_id;
            $data['live']            = $live;
            $data['live_url']        = json_decode($live->return_response);
            $data['page']            = $this->load->view('admin/conference/_livestatus', $data, true);
            $array                   = array('status' => '1', 'page' => $data['page']);
            echo json_encode($data);
            //=====

        }
    }

  

    public function delete($id, $zoom_id)
    {
        $result = $this->conference_model->getdelete($id);
        if (empty($result)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-error text-left">Something went wrong.</div>');
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }

        if ($result->api_type == 'global') {
            $params = array(
                'zoom_api_key'    => "",
                'zoom_api_secret' => "",
            );
        } else {
            $staff = $this->staff_model->get($this->customlib->getStaffID());
            if ($staff['zoom_api_key'] == "" && $staff['zoom_api_secret'] == "") {
                $this->session->set_flashdata('msg', '<div class="alert alert-error text-left">You have created by your own account, API Credential not exists.</div>');
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }
            $params = array(
                'zoom_api_key'    => $staff['zoom_api_key'],
                'zoom_api_secret' => $staff['zoom_api_secret'],
            );
        }
        $this->load->library('zoom_api', $params);
        $response = $this->zoom_api->deleteMeeting($zoom_id);
        if (!empty($response)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $response->message . '</div>');
        } else {
            $data['title'] = 'Delete Conference';
            $this->session->set_flashdata('msg', '<div class="alert alert-error text-left">' . $this->lang->line('delete_message') . '</div>');
            $this->conference_model->remove($id);
        }
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function addcredential()
    {
        $response = array();
        if ($this->input->post('button') == "save") {
            $this->form_validation->set_rules('zoom_api_key', $this->lang->line('zoom_api_key'), 'required|trim|xss_clean');
            $this->form_validation->set_rules('zoom_api_secret', $this->lang->line('zoom_api_secret'), 'required|trim|xss_clean');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'zoom_api_key'    => form_error('zoom_api_key'),
                    'zoom_api_secret' => form_error('zoom_api_secret'),
                );
                $response = array('status' => 0, 'error' => $data);
            } else {
                $insert_array = array(
                    'id'              => $this->customlib->getStaffID(),
                    'zoom_api_key'    => $this->input->post('zoom_api_key'),
                    'zoom_api_secret' => $this->input->post('zoom_api_secret'),
                );
                $insert_id = $this->staff_model->update($insert_array);
                $response  = array('status' => 1, 'message' => $this->lang->line('success_message'));
            }

        } else {
            $insert_array = array(
                'id'              => $this->customlib->getStaffID(),
                'zoom_api_key'    => null,
                'zoom_api_secret' => null,
            );
            $insert_id = $this->staff_model->update($insert_array);
            $response  = array('status' => 1, 'message' => $this->lang->line('update_message'));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function addByOther()
    {
        $response = array();
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('staff_id', $this->lang->line('doctor'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('host_video', $this->lang->line('host_video'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('client_video', $this->lang->line('client_video'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('consult_duration_minutes'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('opdipd_id', $this->lang->line('opd_ipd_no'), 'required|trim|xss_clean');     
        
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'staff_id'     => form_error('staff_id'),
                'host_video'   => form_error('host_video'),
                'client_video' => form_error('client_video'),
                'duration'     => form_error('duration'),
                'opdipd_id'     => form_error('opdipd_id'),
            );
            $response = array('status' => 0, 'error' => $data);

        } else {
            //=======
            $api_type = 'global';
            $params   = array(
                'zoom_api_key'    => "",
                'zoom_api_secret' => "",
            );
            $this->load->library('zoom_api', $params);
            $select_group = $this->input->post('select_group');
            if ($select_group == $this->lang->line('opd')) {

                $opdid = $this->input->post('opdipd_id');
            } else {
                $opdid = '';
            }

            if ($select_group == $this->lang->line('ipd')) {
                $ipdid = $this->input->post('opdipd_id');
            } else {
                $ipdid = '';
            }

            $insert_array = array(
                'staff_id'     => $this->input->post('staff_id'),
                'patient_id'   => $this->input->post('patient_id'),
                'title'        => $this->input->post('title'),
                'opd_id'       => $opdid,
                'ipd_id'       => $ipdid,
                'date'         => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($this->input->post('date'))),
                'duration'     => $this->input->post('duration'),
                'password'     => $this->input->post('password'),
                'created_id'   => $this->customlib->getStaffID(),
                'api_type'     => $api_type,
                'purpose'      => 'consult',
                'host_video'   => $this->input->post('host_video'),
                'client_video' => $this->input->post('client_video'),
                'description'  => $this->input->post('description'),
                'timezone'     => $this->customlib->getTimeZone(),
            );

            $response = $this->zoom_api->createAMeeting($insert_array);

            if ($response) {
                if (isset($response->id)) {
                    $insert_array['return_response'] = json_encode($response);
                    $conferenceid                    = $this->conference_model->add($insert_array);
                    $sender_details                  = array('patient_id' => $this->input->post('patient_id'), 'conference_id' => $conferenceid, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
                    $this->mailsmsconf->mailsms('live_consult', $sender_details);
                    $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
                } else {
                    $response = array('status' => 0, 'error' => array($response->message));
                }

            } else {
                $response = array('status' => 0, 'error' => array('Something went wrong.'));
            }

        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    

    public function meeting()
    {
        if (!$this->rbac->hasPrivilege('live_meeting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'conference');
        $this->session->set_userdata('sub_menu', 'conference/live_meeting');
        $data                    = array();
        $role                    = json_decode($this->customlib->getStaffRole());
        $data['role']            = $role;
        $data['logged_staff_id'] = $this->customlib->getStaffID();
        if ($role->id == 7) {
            $data['conferences'] = $this->conference_model->getStaffMeeting();
        } else {
            $data['conferences'] = $this->conference_model->getStaffMeeting($data['logged_staff_id']);

        }
        $data['staffList'] = $this->staff_model->get();
        $this->load->view('layout/header');
        $this->load->view('admin/conference/meeting', $data);
        $this->load->view('layout/footer');

    }

    public function addMeeting()
    {

        $response = array();
        $this->form_validation->set_rules('title', $this->lang->line('meeting') . ' ' . $this->lang->line('title'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('meeting') . ' ' . $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('meeting_duration_minutes'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('host_video', $this->lang->line('host_video'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('staff[]', $this->lang->line('staff'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('client_video', $this->lang->line('client_video'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'title'        => form_error('title'),
                'date'         => form_error('date'),
                'staff[]'      => form_error('staff[]'),
                'host_video'   => form_error('host_video'),
                'client_video' => form_error('client_video'),
                'password'     => form_error('password'),
                'duration'     => form_error('duration'),
            );

            $response = array('status' => 0, 'error' => $data);

        } else {
            //=======
            $api_type = 'global';
            $params   = array(
                'zoom_api_key'    => "",
                'zoom_api_secret' => "",
            );

            $this->load->library('zoom_api', $params);
            //============
            $insert_array = array(
                'title'        => $this->input->post('title'),
                'date'         => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($this->input->post('date'))),
                'duration'     => $this->input->post('duration'),
                'password'     => $this->input->post('password'),
                'created_id'   => $this->customlib->getStaffID(),
                'api_type'     => $api_type,
                'host_video'   => $this->input->post('host_video'),
                'client_video' => $this->input->post('client_video'),
                'description'  => $this->input->post('description'),
                'purpose'      => 'meeting',
                'timezone'     => $this->customlib->getTimeZone(),
            );

            $response = $this->zoom_api->createAMeeting($insert_array);
            $staff    = $this->input->post('staff[]');

            if ($response) {
                if (isset($response->id)) {
                    $insert_array['return_response'] = json_encode($response);
                    $this->conference_model->addmeeting($insert_array, $staff);

                    $staff_mail_sms_list = $this->conference_model->getAllStaffByArray($staff);

                    if (!empty($staff_mail_sms_list)) {
                        $sender_details = array();
                        foreach ($staff_mail_sms_list as $staff_mail_sms_list_key => $staff_mail_sms_list_value) {
                            $sender_details[] = array(
                                'title'       => $this->input->post('title'),
                                'date'        => $this->input->post('date'),
                                'duration'    => $this->input->post('duration'),
                                'employee_id' => $staff_mail_sms_list_value->employee_id,
                                'department'  => $staff_mail_sms_list_value->department,
                                'designation' => $staff_mail_sms_list_value->designation,
                                'name'        => ($staff_mail_sms_list_value->surname == "") ? $staff_mail_sms_list_value->name : $staff_mail_sms_list_value->name . " " . $staff_mail_sms_list_value->surname,
                                'contact_no'  => $staff_mail_sms_list_value->contact_no,
                                'email'       => $staff_mail_sms_list_value->email,
                            );
                        }

                        $this->mailsmsconf->mailsms('live_meeting', $sender_details);
                    }
                    $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
                } else {
                    $response = array('status' => 0, 'error' => array($response->message));
                }

            } else {
                $response = array('status' => 0, 'error' => array('Something went wrong.'));
            }

        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function chgstatus()
    {
        $response = array();
        $this->form_validation->set_rules('conference_id', $this->lang->line('zoom_api_key'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('chg_status', $this->lang->line('zoom_api_secret'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'conference_id' => form_error('conference_id'),
                'chg_status'    => form_error('chg_status'),
            );
            $response = array('status' => 0, 'error' => $data);

        } else {
            $insert_array = array(
                'status' => $this->input->post('chg_status'),
            );
            $insert_id = $this->conference_model->update($this->input->post('conference_id'), $insert_array);
            $response  = array('status' => 1, 'message' => $this->lang->line('update_message'));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function meeting_report()
    {
        if (!$this->rbac->hasPrivilege('live_meeting_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'conference/meeting_report');
        $data                    = array();
        $staff_id                = $this->customlib->getStaffID();
        $data['logged_staff_id'] = $staff_id;
        $data['meetingList']     = $this->conferencehistory_model->getmeeting();
        $this->load->view('layout/header');
        $this->load->view('admin/conference/meeting_report', $data);
        $this->load->view('layout/footer');
    }

    public function consult_report()
    {
        if (!$this->rbac->hasPrivilege('live_consultation_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'conference/consult_report');
        $data                    = array();
        $staff_id                = $this->customlib->getStaffID();
        $data['logged_staff_id'] = $staff_id;
        $data['consultList']     = $this->conferencehistory_model->getconsult();
        $this->load->view('layout/header');
        $this->load->view('admin/conference/consult_report', $data);
        $this->load->view('layout/footer');
    }

    

    public function add_history()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $staff_id    = $this->customlib->getStaffID();
            $data_insert = array(
                'conference_id' => $this->input->post('id'),
                'staff_id'      => $staff_id,
            );

            $this->conferencehistory_model->updatehistory($data_insert, 'staff');
            $array = array('status' => 1, 'error' => '');
            echo json_encode($array);
        }
    }

    public function getViewerList()
    {
        $recordid     = $this->input->post('recordid');
        $type         = $this->input->post('type');
        $data['type'] = 'staff';

        if (isset($type)) {
            $data['type']         = $type;
            $data['viewerDetail'] = $this->conferencehistory_model->getLivePatient($recordid);
        } else {
            $data['viewerDetail'] = $this->conferencehistory_model->getMeetingStaff($recordid);
        }

        $data['page'] = $this->load->view('admin/conference/_partialviewerlist', $data, true);
        echo json_encode($data);
    }
}
