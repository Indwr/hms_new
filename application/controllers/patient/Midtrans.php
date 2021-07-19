<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Midtrans extends Patient_Controller
{
    public $api_config     = "";
    public $payment_method = array();
    public $pay_method     = array();

    public function __construct()
    {
        parent::__construct();
        $this->pay_method   = $this->paymentsetting_model->getActiveMethod();
        $this->patient_data = $this->session->userdata('patient');
        $this->setting      = $this->setting_model->get();
        $this->load->library('Midtrans_lib');
    }

    public function index()
    {
        $setting             = $this->setting[0];
        $data                = array();
        $id                  = $this->patient_data['patient_id'];
        $data["id"]          = $id;
        $data['productinfo'] = "bill payment smart hospital";
        if ($this->session->has_userdata('payment_amount')) {
            $amount                  = $this->session->userdata('payment_amount');
            $ipdid                   = $amount['record_id'];
            $charges                 = $this->charge_model->getCharges($id, $ipdid);
            $data["charges"]         = $charges;
            $paymentDetails          = $this->payment_model->paymentDetails($id);
            $paid_amount             = $this->payment_model->getPaidTotal($id, $ipdid);
            $data["paid_amount"]     = $paid_amount["paid_amount"];
            $balance_amount          = $this->payment_model->getBalanceTotal($id);
            $data["balance_amount"]  = $balance_amount["balance_amount"];
            $data["payment_details"] = $paymentDetails;
            $data["payment_type"]    = 'ipd';
            $api_secret_key          = ($this->pay_method->api_secret_key);
            $data['api_secret_key']  = $api_secret_key;
            $data['amount']          = $amount['deposit_amount'];
            $result                  = $this->patient_model->getIpdDetails($id, $ipdid);
            $data['patient']         = $result;
            $data['currency']        = $setting['currency'];
            $data['hospital_name']   = $setting['name'];
            $data['image']           = $setting['image'];
        }

        $data['setting']   = $this->setting;
        $data['api_error'] = array();
        $transaction       = array(
            'transaction_details' => array(
                'order_id'     => time(),
                'gross_amount' => round($data['amount']), // no decimal allowed
            ),
        );

        $snapToken          = $this->midtrans_lib->getSnapToken($transaction, $api_secret_key);
        $data['snap_Token'] = $snapToken;
        $this->load->view("layout/patient/header");
        $this->load->view('patient/midtrans', $data);
        $this->load->view("layout/patient/footer");
    }

    public function opdpay()
    {
        $setting             = $this->setting[0];
        $data                = array();
        $id                  = $this->patient_data['patient_id'];
        $data["id"]          = $id;
        $data['productinfo'] = "bill payment smart hospital";
        if ($this->session->has_userdata('payment_amount')) {
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
            $data["payment_type"]    = 'opd';
            $api_secret_key          = ($this->pay_method->api_secret_key);
            $data['api_secret_key']  = $api_secret_key;
            $data['amount']          = $amount['deposit_amount'];
            $result                  = $this->patient_model->getDetails($id, $ipdid);
            $data['patient']         = $result;
            $data['currency']        = $setting['currency'];
            $data['hospital_name']   = $setting['name'];
            $data['image']           = $setting['image'];
        }

        $data['setting']   = $this->setting;
        $data['api_error'] = array();
        $transaction       = array(
            'transaction_details' => array(
                'order_id'     => time(),
                'gross_amount' => round($data['amount']), // no decimal allowed

            ),
        );

        $snapToken          = $this->midtrans_lib->getSnapToken($transaction, $api_secret_key);
        $data['snap_Token'] = $snapToken;
        $this->load->view("layout/patient/header");
        $this->load->view('patient/midtrans', $data);
        $this->load->view("layout/patient/footer");
    }

    public function success()
    {
        $response   = json_decode($_POST['result_data']);
        $payment_id = $response->transaction_id;
        if ($this->session->has_userdata('payment_amount')) {
            $amount         = $this->session->userdata('payment_amount');
            $ipdid          = $amount['record_id'];
            $data['amount'] = $amount['deposit_amount'];
        }

        $transactionid = $payment_id;
        $type          = $this->input->post('payment_type');

        if ($type == 'opd') {

            $save_record = array(
                'patient_id'   => $this->patient_data['patient_id'],
                'paid_amount'  => ($data['amount']),
                'opd_id'       => $ipdid,
                'date'         => date('Y-m-d'),
                'total_amount' => '',
                'note'         => "Online fees deposit through Midtrans TXN ID: " . $transactionid,
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
                'note'         => "Online fees deposit through Midtrans TXN ID: " . $transactionid,
                'payment_mode' => 'Online',
            );

            $insert_id = $this->payment_model->addPayment($save_record);
        }
        $array = array('insert_id' => $insert_id);
        echo json_encode($array);
    }
}
