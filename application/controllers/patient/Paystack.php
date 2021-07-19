<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paystack extends Patient_Controller
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
            $balance_amount              = $this->payment_model->getBalanceTotal($id);
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $data['amount']              = $amount['deposit_amount'];
            $data["payment_type"]        = 'ipd';
            $result                      = $this->patient_model->getIpdDetails($id, $ipdid);
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
            $this->load->view("layout/patient/header");
            $this->load->view("patient/paystack", $data);
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
            $data["payment_type"]        = 'opd';
            $charges                     = $this->charge_model->getOPDCharges($id, $ipdid);
            $data["charges"]             = $charges;
            $paymentDetails              = $this->payment_model->opdpaymentDetails($id);
            $paid_amount                 = $this->payment_model->getOPDPaidTotal($id, $ipdid);
            $data["paid_amount"]         = $paid_amount["paid_amount"];
            $balance_amount              = $this->payment_model->getOPDBalanceTotal($id);
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $amount                      = $this->session->userdata('payment_amount');
            $ipdid                       = $amount['record_id'];
            $data['amount']              = $amount['deposit_amount'];
            $result                      = $this->patient_model->getDetails($id, $ipdid);
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
            $this->load->view("layout/patient/header");
            $this->load->view("patient/paystack", $data);
            $this->load->view("layout/patient/footer");
        }
    }

    public function pay_bypaystack()
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
                $type                        = $this->input->post("payment_type");
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

            if (isset($data)) {
                $result       = array();
                $amount       = $data['amount'] * 100;
                $ref          = time();
                $callback_url = base_url() . 'patient/paystack/verify_payment/' . $ref . "/" . $type;
                $postdata     = array('email' => $_POST['email'], 'amount' => $amount, "reference" => $ref, "callback_url" => $callback_url);
                $url          = "https://api.paystack.co/transaction/initialize";
                $ch           = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $headers = [
                    'Authorization: Bearer ' . $api_secret_key,
                    'Content-Type: application/json',
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $request = curl_exec($ch);
                curl_close($ch);

                if ($request) {
                    $result = json_decode($request, true);
                }
                $redir = $result['data']['authorization_url'];
            }
            $array = array('status' => 'success', 'error' => '', 'location' => $redir);
        }
        echo json_encode($array);
    }

    public function verify_payment($ref, $type = 'ipd')
    {
        $result = array();
        $url    = 'https://api.paystack.co/transaction/verify/' . $ref;
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->pay_method->api_secret_key]
        );
        $request = curl_exec($ch);
        curl_close($ch);
        if ($request) {
            $result = json_decode($request, true);
            if ($result) {
                if ($result['data']) {
                    //something came in
                    if ($result['data']['status'] == 'success') {
                        if ($this->session->has_userdata('payment_amount')) {
                            $amount         = $this->session->userdata('payment_amount');
                            $ipdid          = $amount['record_id'];
                            $data['amount'] = $amount['deposit_amount'];
                        }
                        $transactionid = $ref;
                        if ($type == 'opd') {
                            $save_record = array(
                                'patient_id'   => $this->patient_data['patient_id'],
                                'paid_amount'  => ($data['amount']),
                                'opd_id'       => $ipdid,
                                'date'         => date('Y-m-d'),
                                'total_amount' => '',
                                'note'         => "Online fees deposit through Paystack TXN ID: " . $transactionid,
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
                                'note'         => "Online fees deposit through Paystack TXN ID: " . $transactionid,
                                'payment_mode' => 'Online',
                            );
                            $insert_id = $this->payment_model->addPayment($save_record);

                        }
                        redirect(base_url("patient/pay/successinvoice/" . $insert_id . "/" . $type));
                        redirect(site_url('patient/dashboard'));
                    } else {
                        // the transaction was not successful, do not deliver value'
                        //uncomment this line to inspect the result, to check why it failed.
                        redirect(base_url("patient/pay/paymentfailed"));
                    }
                } else {
                    redirect(base_url("patient/pay/paymentfailed"));
                }

            } else {
                //die("Something went wrong while trying to convert the request variable to json. Uncomment the print_r command to see what is in the result variable.");
                redirect(base_url("patient/pay/paymentfailed"));
            }
        } else {
            //die("Something went wrong while executing curl. Uncomment the var_dump line above this line to see what the issue is. Please check your CURL command to make sure everything is ok");
            redirect(base_url("patient/pay/paymentfailed"));
        }
    }

}
