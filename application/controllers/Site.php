<?php

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
// use Razorpay\Api\Api;
// use Razorpay\Api\Errors\SignatureVerificationError;
class Site extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        require realpath(APPPATH . '../vendor/autoload.php');
        date_default_timezone_set("Asia/Kolkata");
        $this->check_installation();
        if ($this->config->item('installed') == true) {
            $this->db->reconnect();
        }
        $this->load->library(array('form_validation'));


        $this->load->library('Auth');
        $this->load->library('Enc_lib');
        $this->load->library('mailer');
        $this->load->config('ci-blog');
        $this->mailer;
    }

    private function check_installation()
    {
        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

    public function login()
    {
        if ($this->auth->logged_in()) {
            $this->auth->is_logged_in(true);
        }
        $data           = array();
        $data['title']  = 'Login';
        $school         = $this->setting_model->get();
        $notice_content = $this->config->item('ci_front_notice_content');
        $notices        = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice'] = $notices;
        $data['school'] = $school[0];
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('admin/login', $data);
        } else {
            $login_post = array(
                'email'    => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            $setting_result = $this->setting_model->get();
            $result         = $this->staff_model->checkLogin($login_post);
            if (!empty($result->language_id)) {
                $lang_array = array('lang_id' => $result->language_id, 'language' => $result->language);
            } else {
                $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
            }

            if ($result) {
                if ($result->is_active) {
                    $setting_result = $this->setting_model->get();

                    $session_data = array(
                        'id'                     => $result->id,
                        'username'               => $result->name . ' ' . $result->surname,
                        'email'                  => $result->email,
                        'roles'                  => $result->roles,
                        'date_format'            => $setting_result[0]['date_format'],
                        'currency_symbol'        => $setting_result[0]['currency_symbol'],
                        'start_month'            => $setting_result[0]['start_month'],
                        'school_name'            => $setting_result[0]['name'],
                        'timezone'               => $setting_result[0]['timezone'],
                        'sch_name'               => $setting_result[0]['name'],
                        'language'               => $lang_array,
                        'is_rtl'                 => $setting_result[0]['is_rtl'],
                        'doctor_restriction'     => $setting_result[0]['doctor_restriction'],
                        'superadmin_restriction' => $setting_result[0]['superadmin_restriction'],
                        'theme'                  => $setting_result[0]['theme'],
                    );
                    $this->session->set_userdata('hospitaladmin', $session_data);
                    $role      = $this->customlib->getStaffRole();
                    $role_name = json_decode($role)->name;
                    $this->customlib->setUserLog($this->input->post('username'), $role_name);

                    if (isset($_SESSION['redirect_to'])) {
                        redirect($_SESSION['redirect_to']);
                    } else {
                        redirect('admin/admin/dashboard');
                    }
                } else {
                    $data['error_message'] = $this->lang->line('administrator_message');
                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['error_message'] = $this->lang->line('invalid_message');
                $this->load->view('admin/login', $data);
            }
        }
    }

    public function logout()
    {
        $admin_session   = $this->session->userdata('hospitaladmin');
        $student_session = $this->session->userdata('student');
        $this->auth->logout();
        if ($admin_session) {
            redirect('site/login');
        } else if ($student_session) {
            redirect('site/userlogin');
        } else {
            redirect('site/userlogin');
        }
    }

    public function forgotpassword()
    {
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('admin/forgotpassword');
        } else {
            $email  = $this->input->post('email');
            $result = $this->staff_model->getByEmail($email);
            if ($result && $result->email != "") {
                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->id, 'verification_code' => $verification_code);
                $this->staff_model->add($update_record);
                $name          = $result->name;
                $resetPassLink = base_url('admin/resetpassword') . "/" . $verification_code;
                $send_for  = 'forgot_password' ; 
                $usertype = 'staff';
                $chk_mail_sms = $this->customlib->sendMailSMS($send_for);
                $sender_details = array('id' => $result->id, 'email' => $email);
                $body          = $this->forgotPasswordBody($usertype,$sender_details,$resetPassLink,$chk_mail_sms['template']);

                if ($chk_mail_sms['mail']) {
                     $result_new = $this->mailer->send_mail($result->email, $chk_mail_sms['subject'], $body);
                }
                $this->session->set_flashdata('message', $this->lang->line('recover_message'));
                redirect('site/login', 'refresh');
            } else {
                $data = array(
                    'error_message' => $this->lang->line('invalid_email'),
                );
            }
            $this->load->view('admin/forgotpassword', $data);
        }
    } 

    //reset password - final step for forgotten password
    public function admin_resetpassword($verification_code = null)
    {
        if (!$verification_code) {
            show_404();
        }
        $user = $this->staff_model->getByVerificationCode($verification_code);
        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('admin/admin_resetpassword', $data);
            } else {
                // finally change the password
                $password      = $this->input->post('password');
                $update_record = array(
                    'id'                => $user->id,
                    'password'          => $this->enc_lib->passHashEnc($password),
                    'verification_code' => "",
                );

                $change = $this->staff_model->update($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('reset_message'));
                    redirect('site/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line('worning_message'));
                    redirect('admin_resetpassword/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/forgotpassword", 'refresh');
        }
    }

    //reset password - final step for forgotten password
    public function resetpassword($role = null, $verification_code = null)
    {
        if (!$role || !$verification_code) {
            show_404();
        }
        $user = $this->user_model->getUserByCodeUsertype($role, $verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {
                $data['role']              = $role;
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('resetpassword', $data);
            } else {

                // finally change the password

                $update_record = array(
                    'id'                => $user->user_tbl_id,
                    'password'          => $this->input->post('password'),
                    'verification_code' => "",
                );

                $change = $this->user_model->saveNewPass($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('reset_message'));
                    redirect('site/userlogin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line('worning_message'));
                    redirect('user/resetpassword/' . $role . '/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/ufpassword", 'refresh');
        }
    }

    public function ufpassword()
    {
        $this->form_validation->set_rules('username', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user', $this->lang->line('user_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('ufpassword');
        } else {
            $email    = $this->input->post('username');
            $usertype = $this->input->post('user');
            $result   = $this->user_model->forgotPassword($usertype, $email);

            if ($result && $result->email != "") {
                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                $this->user_model->updateVerCode($update_record);
                if ($usertype == "patient") {
                    $name = $result->patient_name;
                } else {
                    $name = $result->patient_name;
                }
                $resetPassLink = site_url('user/resetpassword') . '/' . $usertype . "/" . $verification_code;
                $send_for  = 'forgot_password' ; 
                $chk_mail_sms = $this->customlib->sendMailSMS($send_for);
                $sender_details = array('id' => $result->id, 'email' => $email);
                $body          = $this->forgotPasswordBody($usertype,$sender_details,$resetPassLink,$chk_mail_sms['template']);
               
                if ($chk_mail_sms['mail']) {
                    $result = $this->mailer->send_mail($result->email, $chk_mail_sms['subject'], $body);
                }
                $this->session->set_flashdata('message', $this->lang->line('recover_message'));
                redirect('site/userlogin', 'refresh');
            } else {
                $data = array(
                    'error_message' => $this->lang->line('invalid_user_email'),
                );
            }
            $this->load->view('ufpassword', $data);
        }
    }

    public function forgotPasswordBody($usertype,$sender_details,$resetPassLink,$template) {
        if ($usertype == "patient") {
            $patient = $this->patient_model->patientProfileDetails($sender_details['id']);
            $sender_details['site_url'] = site_url('site/ufpassword');
            $sender_details['resetpasslink'] = $resetPassLink;
            $sender_details['display_name'] = $patient['patient_name'];
        }
        if ($usertype == "staff") {
            $staff = $this->staff_model->get($sender_details['id']);
            $sender_details['site_url'] = site_url('site/forgotpassword');
            $sender_details['resetpasslink'] = $resetPassLink;
            $sender_details['display_name'] = $staff['name']." ".$staff['surname'];
        } 

        foreach ($sender_details as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        return $template;
    }

    

    public function getpatientDetails()
    {
        $id     = $this->input->post("patient_id");
        $result = $this->appointment_model->getpatientDetails($id);
        $array  = array('status' => 0, 'result' => array());

        if ($result) {
            $array = array('status' => 1, 'result' => $result);
        }
        echo json_encode($array);
    }

     public function getdoctor() {
        $spec_id = $this->input->post('id');
        $active = $this->input->post('active');
        $result = $this->staff_model->getdoctorbyspecilist($spec_id);
        echo json_encode($result);
    }

    public function userlogin()
    {
        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        $data           = array();
        $data['title']  = 'Login';
        $school         = $this->setting_model->get();
        $notice_content = $this->config->item('ci_front_notice_content');
        $notices        = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice'] = $notices;
        $data['school'] = $school[0];
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('userlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            $login_details  = $this->user_model->checkLogin($login_post);
            $setting_result = $this->setting_model->get();
            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];
                if ($user->is_active == "yes") {
                    if ($user->role == "patient") {
                        $result = $this->user_model->read_user_information($user->id);
                    }
                    if ($result[0]->lang_id != 0) {
                        $lang_array = array('lang_id' => $result['0']->lang_id, 'language' => $result['0']->language);
                    } else {
                        $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                    }

                    if ($result != false) {

                        if ($result[0]->role == "patient") {
                            $session_data = array(
                                'id'              => $result[0]->id,
                                'patient_id'      => $result[0]->user_id,
                                'patient_type'    => $result[0]->patient_type,
                                'role'            => $result[0]->role,
                                'username'        => $result[0]->username,
                                'name'            => $result[0]->patient_name,
                                'date_format'     => $setting_result[0]['date_format'],
                                'currency_symbol' => $setting_result[0]['currency_symbol'],
                                'timezone'        => $setting_result[0]['timezone'],
                                'sch_name'        => $setting_result[0]['name'],
                                'language'        => array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']),
                                'is_rtl'          => $setting_result[0]['is_rtl'],
                                'theme'           => $setting_result[0]['theme'],
                                'image'           => $result[0]->image,
                            );

                            $this->session->set_userdata('patient', $session_data);
                            $this->customlib->setUserLog($result[0]->username, $result[0]->role);
                            redirect('patient/dashboard/appointment');
                        }
                    } else {
                        $data['error_message'] = $this->lang->line('account_suspended');
                        $this->load->view('userlogin', $data);
                    }
                } else {
                    $data['error_message'] = $this->lang->line('administrator_message');
                    $this->load->view('userlogin', $data);
                }
            } else {
                $data['error_message'] = $this->lang->line('invalid_message');
                $this->load->view('userlogin', $data);
            }
        }
    }



    public function upgrade(){
       
        $checkUpgrade = (isset($_SESSION['hospitaladmin']['id'])) ? $_SESSION['hospitaladmin']['id'] : '';
        if(!empty($checkUpgrade)){
            $upgradeData = $this->user_model->checkSubscription2($checkUpgrade);
            $subscriptionMonth = $this->checkMonthFromDate($upgradeData->subscriptionActiveTime);
            $currentMonth = $this->checkMonthFromDate(date('Y-m-d H:i:s'));
            if($subscriptionMonth['month'] == $currentMonth['currentMonth']){
                redirect(base_url('admin/admin/dashboard'));
            }
        }else{
            redirect(base_url('admin/admin/dashboard'));
        }
        $months = $this->checkMonthFromDate($upgradeData->subscriptionActiveTime);
        // if($months['month'] == $months['currentMonth']){
            $response['id'] = $checkUpgrade;
            $where = [];
            $order = 'asc';
            $response['getSubscriptionsPlans'] = $this->user_model->getData('subscription_plans',$where,$order);
            $this->load->view('upgrade',$response);
        // }else{
        //     redirect($_SERVER['HTTP_REFERER']);
        // }
    }

    public function upgradeCircle(){
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = $this->security->xss_clean($this->input->post());
            $where = ['id' => $_SESSION['hospitaladmin']['id']];
            $userData = $this->user_model->getData('staff',$where,'asc');
            $this->form_validation->set_rules('startDate', 'Start Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('endDate', 'End Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('circle', 'Circle', 'trim|required|xss_clean|numeric');
            if ($this->form_validation->run() != FALSE) {
                $api =   new Api('rzp_test_65XcijwZsuxIGr', 'VxawI2eWSo9H0krjCj1jxYQT');
                
                // $api = new Api('rzp_test_65XcijwZsuxIGr', 'VxawI2eWSo9H0krjCj1jxYQT');
                $keyId = 'rzp_test_65XcijwZsuxIGr';
                //
                // We create an razorpay order using orders api
                // Docs: https://docs.razorpay.com/docs/orders
                //
               
                    $orderData = [
                        'receipt'         => uniqid(),
                        'amount'          => $data['amount'] * 100, // 2000 rupees in paise
                        'currency'        => 'INR',
                        'payment_capture' => 1 // auto capture
                    ];

                    $razorpayOrder = $api->order->create($orderData);

                    $razorpayOrderId = $razorpayOrder['id'];

                    $_SESSION['razorpay_order_id'] = $razorpayOrderId;

                    $displayAmount = $amount = $orderData['amount'];
                    $data = [
                        "key"               => $keyId,
                        "amount"            => $amount,
                        "name"              => $userData[0]->name,
                        "description"       => "Subscription Payment",
                        "image"             => base_url('uploads/hospital_content/logo/0.png'),
                        "prefill"           => [
                        "name"              => $userData[0]->name,
                        "email"             => $_SESSION['hospitaladmin']['email'],
                        "contact"           => $userData[0]->contact_no,
                        ],
                        "notes"             => [
                        "user_id"           => $userData[0]->id,
                        "amount"            => $data['amount'],
                        "startDate"            => $data['startDate'],
                        "circle"            => $data['circle'],
                        "address"           => $userData[0]->permanent_address,
                        ],
                        "theme"             => [
                        "color"             => "#F37254"
                        ],
                        "order_id"          => $razorpayOrderId,
                    ];
                    $response['json'] = json_encode($data);
                    $response['data'] = $data;
                    $this->load->view("checkout/automatic", $response);
            }else{
                $this->session->set_flashdata('error', strip_tags(validation_errors()));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function verify(){
            $success = true;
            $error = "Payment Failed";
            if (empty($_POST['razorpay_payment_id']) === false)
            {
                $api = new Api('rzp_test_65XcijwZsuxIGr', 'VxawI2eWSo9H0krjCj1jxYQT');

                try
                {
                    // Please note that the razorpay order ID must
                    // come from a trusted source (session here, but
                    // could be database or something else)
                    $attributes = array(
                        'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                        'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                        'razorpay_signature' => $_POST['razorpay_signature']
                    );

                   $api->utility->verifyPaymentSignature($attributes);
                }
                catch(SignatureVerificationError $e)
                {
                    $success = false;
                    $error = 'Razorpay Error : ' . $e->getMessage();
                }
            }
            $payments = $api->order->fetch($_SESSION['razorpay_order_id'])->payments();
         
            $paymentGetwayResponseData['id'] = $payments->items[0]->id;
            $paymentGetwayResponseData['entity'] = $payments->items[0]->entity;
            $paymentGetwayResponseData['amount'] = $payments->items[0]->amount;
            $paymentGetwayResponseData['currency'] = $payments->items[0]->currency;
            $paymentGetwayResponseData['status'] = $payments->items[0]->status;
            $paymentGetwayResponseData['order_id'] = $payments->items[0]->order_id;
            $paymentGetwayResponseData['invoice_id'] = $payments->items[0]->invoice_id;
            $paymentGetwayResponseData['international'] = $payments->items[0]->international;
            $paymentGetwayResponseData['method'] = $payments->items[0]->method;
            $paymentGetwayResponseData['amount_refunded'] = $payments->items[0]->amount_refunded;
            $paymentGetwayResponseData['refund_status'] = $payments->items[0]->refund_status;
            $paymentGetwayResponseData['captured'] = $payments->items[0]->captured;
            $paymentGetwayResponseData['description'] = $payments->items[0]->description;
            $paymentGetwayResponseData['card_id'] = $payments->items[0]->card_id;
            $paymentGetwayResponseData['bank'] = $payments->items[0]->bank;
            $paymentGetwayResponseData['wallet'] = $payments->items[0]->wallet;
            $paymentGetwayResponseData['vpa'] = $payments->items[0]->vpa;
            $paymentGetwayResponseData['email'] = $payments->items[0]->email;
            $paymentGetwayResponseData['contact'] = $payments->items[0]->contact;
            $paymentGetwayResponseData['notes']['user_id'] = $payments->items[0]->notes['user_id'];
            $paymentGetwayResponseData['notes']['amount'] = $payments->items[0]->notes['amount'];
            $paymentGetwayResponseData['notes']['startDate'] = $payments->items[0]->notes['startDate'];
            $paymentGetwayResponseData['notes']['circle'] = $payments->items[0]->notes['circle'];
            $paymentGetwayResponseData['notes']['address'] = $payments->items[0]->notes['address'];
        
            $paymentGetwayResponseData['fee'] = $payments->items[0]->fee;
            $paymentGetwayResponseData['tax'] = $payments->items[0]->tax;
            $paymentGetwayResponseData['error_code'] = $payments->items[0]->error_code;
            $paymentGetwayResponseData['error_description'] = $payments->items[0]->error_description;
            $paymentGetwayResponseData['error_source'] = $payments->items[0]->error_source;
            $paymentGetwayResponseData['error_step'] = $payments->items[0]->error_step;
            $paymentGetwayResponseData['error_reason'] = $payments->items[0]->error_reason;
            $paymentGetwayResponseData['acquirer_data']['bank_transaction_id'] = $payments->items[0]->acquirer_data['bank_transaction_id'];
            $paymentGetwayResponseData['created_at'] = $payments->items[0]->created_at;
            $insertData = [
                'user_id' => $payments->items[0]->notes['user_id'],
                'amount' => $payments->items[0]->notes['amount'], 
                'circle' => $payments->items[0]->notes['circle'],
                'isActive' => 2,
                'subscriptionActiveTime' => $payments->items[0]->notes['startDate'],
                'paymentId' => $payments->items[0]->id,
                'paymentResponse' => json_encode($paymentGetwayResponseData), 
            ];
            $this->user_model->addData('subscription',$insertData);
            if ($success === true)
            {
                redirect(base_url('admin/admin/dashboard'));
            }
            else
            {
                $html = "<p>Your payment failed</p>
                        <p>{$error}</p>
                        <a href='".base_url('upgrade')."'>Please Complete Your Payment Process</a>
                        ";
                        
            }

            echo $html;
            die();
    //   $this->load->view('checkout/verify');
    }

    public function checkMonthFromDate($date){
        $time=strtotime($date);
        $currentDate = date('Y-m-d H:i:s');
        $currentTime=strtotime($currentDate);
        $data['month'] = date("F",$time);
        $data['currentMonth'] = date("F",$currentTime);
        return $data;
        // $year=date("Y",$time);
    }
}
