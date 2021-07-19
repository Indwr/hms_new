<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailsms extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->config->load("payroll");
        $this->search_type = $this->config->item('search_type');
        $this->mailer;
    }

    public function index()
    {

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'mailsms/index');
        $data['title'] = 'Add Mailsms';

        $select     = 'messages.*';
        $join       = array();
        $table_name = "messages";

        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }

        if (empty($search_type)) {

            $search_type = "";
            $listMessage = $this->report_model->getReport($select, $join, $table_name);
        } else {

            $search_table     = "messages";
            $search_column    = "created_at";
            $additional       = array();
            $additional_where = array();
            $listMessage      = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column);
        }
        $data['listMessage'] = $listMessage;
        $data["searchlist"]  = $this->search_type;
        $data["search_type"] = $search_type;

        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/index', $data);
        $this->load->view('layout/footer');
    }

    public function search()
    {
        $keyword       = $this->input->get_post('keyword');
        $category      = $this->input->get_post('category');
        $exp           = explode('-', $category);
        $category_name = $exp[0];
        $result        = array();
        if ($keyword != "" and $category != "") {
            if ($category_name == "staff") {
                $category_role = $exp[1];
                $result        = $this->staff_model->searchNameLike($keyword, $category_role);
            } elseif ($category_name == "patient") {
                $result = $this->patient_model->searchPatientNameLike($keyword);
            } else {

            }
        }
        echo json_encode($result);
    }

    public function compose()
    {
        if (!$this->rbac->hasPrivilege('email_sms', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Messaging');
        $this->session->set_userdata('sub_menu', 'notification/index');
        $data['title'] = 'Add sms';

        $userdata = $this->customlib->getUserData();
        $carray   = array();

        if (!empty($data["resultlist"])) {
            foreach ($data["resultlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $data['roles'] = $this->role_model->get();
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/compose', $data);
        $this->load->view('layout/footer');
    }

    public function composemail()
    {
        if (!$this->rbac->hasPrivilege('email_sms', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Messaging');
        $this->session->set_userdata('sub_menu', 'notification/index');
        $data['title'] = 'Add Mail';
        $userdata      = $this->customlib->getUserData();
        $carray        = array();

        if (!empty($data["resultlist"])) {
            foreach ($data["resultlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $data['roles'] = $this->role_model->get();
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/composeemail', $data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        $data['title']       = 'Add Vehicle';
        $data['id']          = $id;
        $editvehicle         = $this->vehicle_model->get($id);
        $data['editvehicle'] = $editvehicle;
        $listVehicle         = $this->vehicle_model->get();
        $data['listVehicle'] = $listVehicle;
        $this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header');
            $this->load->view('admin/mailsms/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $manufacture_year = $this->input->post('manufacture_year');
            $data             = array(
                'id'             => $this->input->post('id'),
                'vehicle_no'     => $this->input->post('vehicle_no'),
                'vehicle_model'  => $this->input->post('vehicle_model'),
                'driver_name'    => $this->input->post('driver_name'),
                'driver_licence' => $this->input->post('driver_licence'),
                'driver_contact' => $this->input->post('driver_contact'),
                'note'           => $this->input->post('note'),
            );
            ($manufacture_year != "") ? $data['manufacture_year'] = $manufacture_year : '';
            $this->vehicle_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Transport updated successfully</div>');
            redirect('admin/mailsms/index');
        }
    }

    public function delete($id)
    {
        $data['title'] = 'Fees Master List';
        $this->vehicle_model->remove($id);
        redirect('admin/mailsms/index');
    }

    public function send_individual_mail()
    {

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_rules('individual_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('individual_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user_list', $this->lang->line('recipent'), 'required');
        $this->form_validation->set_rules('individual_send_by', $this->lang->line('send_through'), 'required');
        if ($this->form_validation->run()) {
            $userlisting = json_decode($this->input->post('user_list'));
            $user_array  = array();
            foreach ($userlisting as $userlisting_key => $userlisting_value) {
                $array = array(
                    'category' => $userlisting_value[0]->category,
                    'user_id'  => $userlisting_value[0]->record_id,
                    'email'    => $userlisting_value[0]->email,
                    'mobileno' => $userlisting_value[0]->mobileno,
                    'app_key'  => $userlisting_value[0]->app_key,

                );
                $user_array[] = $array;
            }

            $sms_mail = $this->input->post('individual_send_by');
            if ($sms_mail == "sms") {
                $send_sms  = 1;
                $send_mail = 0;
            } else {
                $send_sms  = 0;
                $send_mail = 1;
            }

            $message       = $this->input->post('individual_message');
            $message_title = $this->input->post('individual_title');
            $data          = array(
                'is_individual' => 1,
                'title'         => $message_title,
                'message'       => $message,
                'send_mail'     => $send_mail,
                'send_sms'      => $send_sms,
                'user_list'     => json_encode($user_array),
            );

            $this->messages_model->add($data);

            if (!empty($user_array)) {

                if ($send_mail) {
                    if (!empty($this->mail_config)) {
                        foreach ($user_array as $user_mail_key => $user_mail_value) {
                            if ($user_mail_value['email'] != "") {
                                $this->mailer->send_mail($user_mail_value['email'], $message_title, $message, $_FILES);
                            }
                        }
                    }
                }

                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], strip_tags($message));
                        }
                    }
                }

            }
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
        } else {

            $data = array(
                'individual_title'     => form_error('individual_title'),
                'individual_message'   => form_error('individual_message'),
                'individual_send_by[]' => form_error('individual_send_by[]'),
                'user_list'            => form_error('user_list'),
            );

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_individual_sms()
    {

        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_rules('individual_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('individual_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user_list', $this->lang->line('recipent'), 'required');
        $this->form_validation->set_rules('individual_send_by[]', $this->lang->line('send_through'), 'required');
        if ($this->form_validation->run()) {
            $userlisting = json_decode($this->input->post('user_list'));

            $user_array = array();
            foreach ($userlisting as $userlisting_key => $userlisting_value) {
                $array = array(
                    'category' => $userlisting_value[0]->category,
                    'user_id'  => $userlisting_value[0]->record_id,
                    'email'    => $userlisting_value[0]->email,
                    'mobileno' => $userlisting_value[0]->mobileno,
                    'app_key'  => $userlisting_value[0]->app_key,
                );
                $user_array[] = $array;
            }

            $sms_mail       = $this->input->post('individual_send_by[]');
            $send_mail      = in_array('mail', $sms_mail) ? 1 : 0;
            $send_sms       = in_array('sms', $sms_mail) ? 1 : 0;
            $send_mobileapp = in_array('push', $sms_mail) ? 1 : 0;
            $message        = $this->input->post('individual_message');
            $message_title  = $this->input->post('individual_title');
            $data           = array(
                'is_individual' => 1,
                'title'         => $message_title,
                'message'       => $message,
                'send_mail'     => $send_mail,
                'send_sms'      => $send_sms,
                'user_list'     => json_encode($user_array),
            );

            $this->messages_model->add($data);
            if (!empty($user_array)) {

                if ($send_mail) {
                    if (!empty($this->mail_config)) {
                        foreach ($user_array as $user_mail_key => $user_mail_value) {
                            if ($user_mail_value['email'] != "") {
                                $this->mailer->send_mail($user_mail_value['email'], $message_title, $message);
                            }
                        }
                    }
                }

                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], strip_tags($message));
                        }
                    }
                }

                if ($send_mobileapp) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        $push_array = array(
                            'title' => $message_title,
                            'body'  => $message);
                        if ($user_mail_value['app_key'] != "") {
                            $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                        }
                    }
                }
            }
            echo json_encode(array('status' => 0, 'msg' => "Message sent successfully"));
        } else {

            $data = array(
                'individual_title'     => form_error('individual_title'),
                'individual_message'   => form_error('individual_message'),
                'individual_send_by[]' => form_error('individual_send_by[]'),
                'user_list'            => form_error('user_list'),
            );

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_group_sms()
    {
        $this->form_validation->set_rules('group_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('group_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('recipent'), 'required');
        $this->form_validation->set_rules('group_send_by[]', $this->lang->line('send_through'), 'required');
        if ($this->form_validation->run()) {
            $user_array     = array();
            $sms_mail       = $this->input->post('group_send_by[]');
            $send_mail      = in_array('mail', $sms_mail) ? 1 : 0;
            $send_sms       = in_array('sms', $sms_mail) ? 1 : 0;
            $send_mobileapp = in_array('push', $sms_mail) ? 1 : 0;
            $message        = $this->input->post('group_message');
            $message_title  = $this->input->post('group_title');
            $data           = array(
                'is_group'   => 1,
                'title'      => $message_title,
                'message'    => $message,
                'send_sms'   => $send_sms,
                'group_list' => json_encode(array()),
            );

            $this->messages_model->add($data);
            $userlisting = $this->input->post('user[]');

            foreach ($userlisting as $users_key => $users_value) {
                if ($users_value == "patient") {
                    $parent_array = $this->patient_model->getPatientEmail();
                    if (!empty($parent_array)) {
                        foreach ($parent_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'  => $parent_value['id'],
                                'email'    => $parent_value['email'],
                                'mobileno' => $parent_value['mobileno'],
                                'app_key'  => $parent_value['app_key'],

                            );
                            $user_array[] = $array;

                        }
                    }

                } else if (is_numeric($users_value)) {

                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }

            if (!empty($user_array)) {

                if ($send_mail) {
                    if (!empty($this->mail_config)) {
                        foreach ($user_array as $user_mail_key => $user_mail_value) {
                            if ($user_mail_value['email'] != "") {
                                $this->mailer->send_mail($user_mail_value['email'], $message_title, $message);
                            }
                        }
                    }
                }

                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], strip_tags($message));
                        }
                    }
                }

                if ($send_mobileapp) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        $push_array = array(
                            'title' => $message_title,
                            'body'  => $this->customlib->getLimitChar($message));
                        if ($user_mail_value['app_key'] != "") {

                            $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                        }
                    }
                }
            }

            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'group_title'     => form_error('group_title'),
                'group_message'   => form_error('group_message'),
                'group_send_by[]' => form_error('group_send_by[]'),
                'user[]'          => form_error('user[]'),
            );

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_group_mail()
    {
        $this->form_validation->set_rules('group_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('group_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('recipent'), 'required');
        if ($this->form_validation->run()) {
            $user_array = array();
            $sms_mail   = $this->input->post('group_send_by');
            if ($sms_mail == "sms") {
                $send_sms  = 1;
                $send_mail = 0;
            } else {
                $send_sms  = 0;
                $send_mail = 1;
            }
            $message       = $this->input->post('group_message');
            $message_title = $this->input->post('group_title');
            $data          = array(
                'is_group'   => 1,
                'title'      => $message_title,
                'message'    => $message,
                'send_mail'  => $send_mail,
                'send_sms'   => $send_sms,
                'group_list' => json_encode(array()),
            );

            $this->messages_model->add($data);
            $userlisting = $this->input->post('user[]');
            foreach ($userlisting as $users_key => $users_value) {
                if ($users_value == "patient") {
                    $parent_array = $this->patient_model->getPatientEmail();
                    if (!empty($parent_array)) {
                        foreach ($parent_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'  => $parent_value['id'],
                                'email'    => $parent_value['email'],
                                'mobileno' => $parent_value['mobileno'],
                                'app_key'  => $parent_value['app_key'],

                            );
                            $user_array[] = $array;

                        }
                    }

                } else if (is_numeric($users_value)) {

                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }

            if (!empty($user_array)) {

                if ($send_mail) {
                    if (!empty($this->mail_config)) {
                        foreach ($user_array as $user_mail_key => $user_mail_value) {
                            if ($user_mail_value['email'] != "") {
                                $this->mailer->send_mail($user_mail_value['email'], $message_title, $message, $_FILES);
                            }
                        }
                    }
                }

                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], strip_tags($message));
                        }
                    }
                }
            }

            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'group_title'     => form_error('group_title'),
                'group_message'   => form_error('group_message'),
                'group_send_by[]' => form_error('group_send_by[]'),
                'user[]'          => form_error('user[]'),
            );

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

}
