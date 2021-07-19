<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Razorpay extends Patient_Controller
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
            $paymentDetails              = $this->payment_model->paymentDetails($id);
            $balance_amount              = $this->payment_model->getBalanceTotal($id);
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $data["payment_type"]        = 'ipd';
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $amount                      = $this->session->userdata('payment_amount');
            $ipdid                       = $amount['record_id'];
            $data['amount']              = $amount['deposit_amount'];
            $charges                     = $this->charge_model->getCharges($id, $ipdid);
            $data["charges"]             = $charges;
            $paid_amount                 = $this->payment_model->getPaidTotal($id, $ipdid);
            $data["paid_amount"]         = $paid_amount;
            $result                      = $this->patient_model->getIpdDetails($id, $ipdid);
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
            $this->load->view("layout/patient/header");
            $this->load->view("patient/razorpay", $data);
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
            $paymentDetails              = $this->payment_model->opdpaymentDetails($id);
            $balance_amount              = $this->payment_model->getOPDBalanceTotal($id);
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $data["payment_type"]        = 'opd';
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $amount                      = $this->session->userdata('payment_amount');
            $ipdid                       = $amount['record_id'];
            $data['amount']              = $amount['deposit_amount'];
            $charges                     = $this->charge_model->getOPDCharges($id, $ipdid);
            $data["charges"]             = $charges;
            $paid_amount                 = $this->payment_model->getOPDPaidTotal($id, $ipdid);
            $data["paid_amount"]         = $paid_amount;
            $result                      = $this->patient_model->getDetails($id, $ipdid);
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
            $this->load->view("layout/patient/header");
            $this->load->view("patient/razorpay", $data);
            $this->load->view("layout/patient/footer");
        }
    }

    public function pay_byrazorpay()
    {
        $patient_detail = $this->setting_model->get();
        $patient_detail = $patient_detail[0];
        if ($this->session->has_userdata('payment_amount')) {
            $id                      = $this->patient_data['patient_id'];
            $charges                 = $this->charge_model->getCharges($id);
            $type                    = $this->input->post("payment_type");
            $data["payment_type"]    = $type;
            $data["charges"]         = $charges;
            $paymentDetails          = $this->payment_model->paymentDetails($id);
            $paid_amount             = $this->payment_model->getPaidTotal($id);
            $data["paid_amount"]     = $paid_amount["paid_amount"];
            $balance_amount          = $this->payment_model->getBalanceTotal($id);
            $data["balance_amount"]  = $balance_amount["balance_amount"];
            $data["payment_details"] = $paymentDetails;
            $api_publishable_key     = ($this->pay_method->api_publishable_key);
            $api_secret_key          = ($this->pay_method->api_secret_key);
            $data['key_id']          = $api_publishable_key;
            $amount                  = $this->session->userdata('payment_amount');
            $ipdid                   = $amount['record_id'];
            $data['amount']          = $amount['deposit_amount'];
            $data['total']           = $amount['deposit_amount'] * 100;
        }

        $data['currency']    = $patient_detail['currency'];
        $data['name']        = $patient_detail['name'];
        $data['theme_color'] = $patient_detail['app_primary_color_code'];
        $data['title']       = 'Bill Payment Smart Hospital';
        $data['return_url']  = site_url() . 'patient/razorpay/callback';
        $logoresult          = $this->customlib->getLogoImage();
        if (!empty($logoresult["mini_logo"])) {
            $mini_logo = base_url() . "uploads/hospital_content/logo/" . $logoresult["mini_logo"];
        } else {
            $mini_logo = base_url() . "uploads/hospital_content/logo/smalllogo.png";
        }

        $data['image'] = $mini_logo;
        $this->load->view("patient/pay_byrazorpay", $data);
    }

    public function callback()
    {
        if ($this->session->has_userdata('payment_amount')) {
            $amount         = $this->session->userdata('payment_amount');
            $ipdid          = $amount['record_id'];
            $data['amount'] = $amount['deposit_amount'];

        }

        $transactionid = $_POST['razorpay_payment_id'];
        $type          = $_POST['type'];
        if ($type == 'opd') {
            $save_record = array(
                'patient_id'   => $this->patient_data['patient_id'],
                'paid_amount'  => ($data['amount']),
                'opd_id'       => $ipdid,
                'date'         => date('Y-m-d'),
                'total_amount' => '',
                'note'         => "Online fees deposit through Razorpay TXN ID: " . $transactionid,
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
                'note'         => "Online fees deposit through Razorpay TXN ID: " . $transactionid,
                'payment_mode' => 'Online',
            );

            $insert_id = $this->payment_model->addPayment($save_record);

        }
        $array = array('insert_id' => $insert_id);
        echo json_encode($array);

    }
}
