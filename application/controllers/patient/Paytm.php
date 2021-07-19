<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paytm extends Patient_Controller
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
        $this->load->library('Paytm_lib');
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
        $patient_detail = $this->setting_model->get();
        $patient_detail = $patient_detail[0];
        if ($this->session->has_userdata('payment_amount')) {
            $id                      = $this->patient_data['patient_id'];
            $charges                 = $this->charge_model->getCharges($id);
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
        $data['currency']     = $patient_detail['currency'];
        $data['name']         = $patient_detail['name'];
        $data["payment_type"] = 'ipd';
        $data['theme_color']  = $patient_detail['app_primary_color_code'];
        $data['title']        = 'Bill Payment Smart Hospital';
        $posted               = $_POST;
        $paytmParams          = array();
        $ORDER_ID             = time();
        $CUST_ID              = time();
        $paytmParams          = array(
            "MID"              => $api_publishable_key,
            "WEBSITE"          => $this->pay_method->paytm_website,
            "INDUSTRY_TYPE_ID" => $this->pay_method->paytm_industrytype,
            "CHANNEL_ID"       => "WEB",
            "ORDER_ID"         => $ORDER_ID,
            "CUST_ID"          => $id,
            "TXN_AMOUNT"       => $data['amount'],
            "CALLBACK_URL"     => base_url() . "patient/Paytm/paytm_response",
        );
        $paytmChecksum               = $this->paytm_lib->getChecksumFromArray($paytmParams, $this->pay_method->api_secret_key);
        $paytmParams["CHECKSUMHASH"] = $paytmChecksum;
        $paytmParams["payment_type"] = 'ipd';
        $this->session->set_userdata('payment_type', 'ipd');
        //$transactionURL              = 'https://securegw-stage.paytm.in/order/process';
        $transactionURL              = 'https://securegw.paytm.in/order/process';
        $data                   = array();
        $data['paytmParams']    = $paytmParams;
        $data['transactionURL'] = $transactionURL;
        $this->load->view('patient/paytm', $data);
    }

    public function opdpay()
    {
        $patient_detail = $this->setting_model->get();
        $patient_detail = $patient_detail[0];
        if ($this->session->has_userdata('payment_amount')) {
            $id                      = $this->patient_data['patient_id'];
            $amount                  = $this->session->userdata('payment_amount');
            $ipdid                   = $amount['record_id'];
            $charges                 = $this->charge_model->getOPDCharges($id, $ipdid);
            $data["charges"]         = $charges;
            $paymentDetails          = $this->payment_model->opdpaymentDetails($id);
            $paid_amount             = $this->payment_model->getOPDPaidTotal($id, $ipdid);
            $data["paid_amount"]     = $paid_amount["paid_amount"];
            $balance_amount          = $this->payment_model->getOPDBalanceTotal($id);
            $data["balance_amount"]  = $balance_amount["balance_amount"];
            $data["payment_details"] = $paymentDetails;
            $api_publishable_key     = ($this->pay_method->api_publishable_key);
            $api_secret_key          = ($this->pay_method->api_secret_key);
            $data['key_id']          = $api_publishable_key;
            $data['amount']          = $amount['deposit_amount'];
            $data['total']           = $amount['deposit_amount'] * 100;
        }

        $data['currency']    = $patient_detail['currency'];
        $data['name']        = $patient_detail['name'];
        $data['theme_color'] = $patient_detail['app_primary_color_code'];
        $data['title']       = 'Bill Payment Smart Hospital';

        $posted      = $_POST;
        $paytmParams = array();
        $ORDER_ID    = time();
        $CUST_ID     = time();

        $paytmParams = array(
            "MID"              => $api_publishable_key,
            "WEBSITE"          => $this->pay_method->paytm_website,
            "INDUSTRY_TYPE_ID" => $this->pay_method->paytm_industrytype,
            "CHANNEL_ID"       => "WEB",
            "ORDER_ID"         => $ORDER_ID,
            "CUST_ID"          => $id,
            "TXN_AMOUNT"       => $data['amount'],
            "CALLBACK_URL"     => base_url() . "patient/Paytm/paytm_response",
        );
        $paytmChecksum               = $this->paytm_lib->getChecksumFromArray($paytmParams, $this->pay_method->api_secret_key);
        $paytmParams["CHECKSUMHASH"] = $paytmChecksum;
        $transactionURL              = 'https://securegw-stage.paytm.in/order/process';
        $data                        = array();
        $data['paytmParams']         = $paytmParams;
        $data['transactionURL']      = $transactionURL;
        $this->session->set_userdata('payment_type', 'opd');
        $data["payment_type"] = 'opd';
        $this->load->view('patient/paytm', $data);
    }

    public function paytm_response()
    {
        $paytmChecksum   = "";
        $paramList       = array();
        $isValidChecksum = "FALSE";
        $paramList       = $_POST;
        $paytmChecksum   = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
        $isValidChecksum = $this->paytm_lib->verifychecksum_e($paramList, "01LCqDhQ#JXLr@58", $paytmChecksum);
        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                if ($this->session->has_userdata('payment_amount')) {
                    $amount         = $this->session->userdata('payment_amount');
                    $ipdid          = $amount['record_id'];
                    $payment_type   = $this->session->userdata('payment_type');
                    $data['amount'] = $amount['deposit_amount'];
                }
                $transactionid = $_POST['TXNID'];
                if ($payment_type == 'opd') {
                    $save_record = array(
                        'patient_id'   => $this->patient_data['patient_id'],
                        'paid_amount'  => ($data['amount']),
                        'opd_id'       => $ipdid,
                        'date'         => date('Y-m-d'),
                        'total_amount' => '',
                        'note'         => "Online fees deposit through Paytm TXN ID: " . $transactionid,
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
                        'note'         => "Online fees deposit through Paytm TXN ID: " . $transactionid,
                        'payment_mode' => 'Online',
                    );
                    $insert_id = $this->payment_model->addPayment($save_record);
                }
                redirect(base_url("patient/pay/successinvoice/" . $insert_id . "/" . $payment_type));
            } else {
                redirect(base_url("patient/pay/paymentfailed"));
            }
        } else {
            redirect(base_url("patient/pay/paymentfailed"));
        }
    }
}
