<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Instamojo extends Patient_Controller
{
    public $payment_method = array();
    public $pay_method     = array();
    public $patient_data;
    public $setting;

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('Customlib');
        $this->patient_data   = $this->session->userdata('patient');
        $this->payment_method = $this->paymentsetting_model->get();
        $this->pay_method     = $this->paymentsetting_model->getActiveMethod();
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode   = $this->config->item('payment_mode');
        $this->blood_group    = $this->config->item('bloodgroup');
        $this->setting        = $this->setting_model->get();
    }

    public function index()
    {
        $setting             = $this->setting[0];
        $data                = array();
        $id                  = $this->patient_data['patient_id'];
        $data["id"]          = $id;
        $data['productinfo'] = "bill payment smart hospital";
        if ($this->session->has_userdata('payment_amount')) {
            $amount                      = $this->session->userdata('payment_amount');
            $ipdid                       = $amount['record_id'];
            $charges                     = $this->charge_model->getCharges($id, $ipdid);
            $data["charges"]             = $charges;
            $paymentDetails              = $this->payment_model->paymentDetails($id);
            $paid_amount                 = $this->payment_model->getPaidTotal($id, $ipdid);
            $data["paid_amount"]         = $paid_amount["paid_amount"];
            $data["payment_type"]        = 'ipd';
            $balance_amount              = $this->payment_model->getBalanceTotal($id, $ipdid);
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $data['amount']              = $amount['deposit_amount'];
            $result                      = $this->patient_model->getIpdDetails($id, $ipdid);
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
            $this->load->view("layout/patient/header");
            $this->load->view("patient/instamojo", $data);
            $this->load->view("layout/patient/footer");
        }
    }

    public function opdpay()
    {
        $setting             = $this->setting[0];
        $data                = array();
        $id                  = $this->patient_data['patient_id'];
        $data["id"]          = $id;
        $data['productinfo'] = "bill payment smart hospital";
        if ($this->session->has_userdata('payment_amount')) {
            $amount                      = $this->session->userdata('payment_amount');
            $ipdid                       = $amount['record_id'];
            $charges                     = $this->charge_model->getOPDCharges($id, $ipdid);
            $data["charges"]             = $charges;
            $paymentDetails              = $this->payment_model->opdpaymentDetails($id);
            $paid_amount                 = $this->payment_model->getOPDPaidTotal($id, $ipdid);
            $data["paid_amount"]         = $paid_amount["paid_amount"];
            $balance_amount              = $this->payment_model->getOPDBalanceTotal($id);
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $data["payment_type"]        = 'opd';
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $data['amount']              = $amount['deposit_amount'];
            $result                      = $this->patient_model->getDetails($id, $ipdid);
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
            $this->load->view("layout/patient/header");
            $this->load->view("patient/instamojo", $data);
            $this->load->view("layout/patient/footer");
        }
    }

    public function pay_byinstamojo()
    {
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('email'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $patient_detail = $this->session->userdata('patient');
            if ($this->session->has_userdata('payment_amount')) {
                $id                          = $this->patient_data['patient_id'];
                $charges                     = $this->charge_model->getCharges($id);
                $data["charges"]             = $charges;
                $paymentDetails              = $this->payment_model->paymentDetails($id);
                $paid_amount                 = $this->payment_model->getPaidTotal($id);
                $data["paid_amount"]         = $paid_amount["paid_amount"];
                $balance_amount              = $this->payment_model->getBalanceTotal($id);
                $data["balance_amount"]      = $balance_amount["balance_amount"];
                $data["payment_details"]     = $paymentDetails;
                $api_publishable_key         = ($this->pay_method->api_publishable_key);
                $api_secret_key              = ($this->pay_method->api_secret_key);
                $data['api_publishable_key'] = $api_publishable_key;
                $data['api_secret_key']      = $api_secret_key;
                $amount                      = $this->session->userdata('payment_amount');
                $ipdid                       = $amount['record_id'];
                $data['amount']              = $amount['deposit_amount'];
            }

            $payment_type = $this->input->post("payment_type");
            $ch           = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array("X-Api-Key:$api_secret_key",
                    "X-Auth-Token:$api_publishable_key"));
            $payload = array(
                'purpose'                 => 'Bill Payment',
                'amount'                  => $data['amount'],
                'phone'                   => $_POST['phone'],
                'buyer_name'              => $patient_detail['name'],
                'redirect_url'            => base_url() . 'patient/instamojo/success/' . $payment_type,
                'send_email'              => false,
                'webhook'                 => base_url() . 'webhooks/insta_webhook',
                'send_sms'                => false,
                'email'                   => $_POST['email'],
                'allow_repeated_payments' => false,
            );

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($response, true);
            if ($json['success']) {
                $url   = $json['payment_request']['longurl'];
                $array = array('status' => 'success', 'error' => '', 'location' => $url);
            } else {

                foreach ($json['message'] as $key => $value) {
                    $error[] = $value[0] . "<br>";
                }
                $array = array('status' => 'fail', 'error' => $error);
            }
        }
        echo json_encode($array);
    }

    public function success($payment_type = '')
    {
        if ($_GET['payment_status'] == 'Credit') {
            if ($this->session->has_userdata('payment_amount')) {
                $amount         = $this->session->userdata('payment_amount');
                $ipdid          = $amount['record_id'];
                $data['amount'] = $amount['deposit_amount'];
            }
            $transactionid = $_GET['payment_id'];
            if ($payment_type == 'opd') {
                $save_record = array(
                    'patient_id'   => $this->patient_data['patient_id'],
                    'paid_amount'  => ($data['amount']),
                    'opd_id'       => $ipdid,
                    'date'         => date('Y-m-d'),
                    'total_amount' => '',
                    'note'         => "Online fees deposit through Instamojo TXN ID: " . $transactionid,
                    'payment_mode' => 'Online',
                );

                $insert_id = $this->payment_model->addOPDPayment($save_record);
            } else {
                $save_record = array(
                    'patient_id'   => $this->patient_data['patient_id'],
                    'paid_amount'  => ($data['amount']),
                    'ipd_id'       => $ipdid,
                    'date'         => date('Y-m-d'),
                    'total_amount' => '',
                    'note'         => "Online fees deposit through Instamojo TXN ID: " . $transactionid,
                    'payment_mode' => 'Online',
                );

                $insert_id = $this->payment_model->addPayment($save_record);
            }
            
            redirect(base_url("patient/pay/successinvoice/" . $insert_id . '/' . $payment_type));

        } else {
            redirect(base_url("patient/pay/paymentfailed"));
        }
    }
}