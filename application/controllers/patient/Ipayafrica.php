<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ipayafrica extends Patient_Controller
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

            if($amount['payment_type']=='ipd'){

                $charges                     = $this->charge_model->getCharges($id, $ipdid);
                $paymentDetails              = $this->payment_model->paymentDetails($id);
                $paid_amount                 = $this->payment_model->getPaidTotal($id, $ipdid);
                $balance_amount              = $this->payment_model->getBalanceTotal($id, $ipdid);
                $result                      = $this->patient_model->getIpdDetails($id, $ipdid);

            }else{

                $charges                     = $this->charge_model->getOPDCharges($id, $ipdid);
                $paymentDetails              = $this->payment_model->opdpaymentDetails($id);
                $paid_amount                 = $this->payment_model->getOPDPaidTotal($id, $ipdid);
                $balance_amount              = $this->payment_model->getOPDBalanceTotal($id);
                $result                      = $this->patient_model->getDetails($id, $ipdid);

            } 
            
            $data["charges"]             = $charges;
            $data["paid_amount"]         = $paid_amount["paid_amount"];
            $data["payment_type"]        = $amount['payment_type'];
            $data["balance_amount"]      = $balance_amount["balance_amount"];
            $data["payment_details"]     = $paymentDetails;
            $api_publishable_key         = ($this->pay_method->api_publishable_key);
            $api_secret_key              = ($this->pay_method->api_secret_key);
            $data['api_publishable_key'] = $api_publishable_key;
            $data['api_secret_key']      = $api_secret_key;
            $data['amount']              = $amount['deposit_amount'];
            $data['patient']             = $result;
            $data['currency']            = $setting['currency'];
            $data['hospital_name']       = $setting['name'];
            $data['image']               = $setting['image'];
        }
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'required');
        if ($this->form_validation->run() == false) {
           $this->load->view("layout/patient/header");
            $this->load->view("patient/ipayafrica", $data);
            $this->load->view("layout/patient/footer");
        } else {

      
             $fields = array("live"=> "1",
                    "oid"=> $ipdid.time(),
                    "inv"=> time(),
                    "ttl"=> $data['amount'],
                    "tel"=> $_POST['phone'],
                    "eml"=> $_POST['email'],
                    "vid"=> ($this->pay_method->api_publishable_key),
                    "curr"=> $data['currency'],
                    "p1"=> "airtel",
                    "p2"=> "",
                    "p3"=> "",
                    "p4"=> $data['amount'],
                    "cbk"=> base_url().'patient/ipayafrica/success',
                    "cst"=> "1",
                    "crl"=> "2"
                    );
            
            $datastring =  $fields['live'].$fields['oid'].$fields['inv'].$fields['ttl'].$fields['tel'].$fields['eml'].$fields['vid'].$fields['curr'].$fields['p1'].$fields['p2'].$fields['p3'].$fields['p4'].$fields['cbk'].$fields['cst'].$fields['crl'];

            $hashkey =($this->pay_method->api_secret_key);
            $generated_hash = hash_hmac('sha1',$datastring , $hashkey);
            $data['fields']=$fields;
            $data['generated_hash']=$generated_hash;
            $this->load->view("patient/ipayafrica_pay", $data);

            } 
    }
 

  

    public function success()
    {
        if(!empty($_GET['status'])){
             if ($this->session->has_userdata('payment_amount')) {
                $amount         = $this->session->userdata('payment_amount');
                $ipdid          = $amount['record_id'];
                $data['amount'] = $amount['deposit_amount'];
                $payment_type   =$amount['payment_type'];
            }
            $transactionid = $_GET['txncd'];
            if ($payment_type == 'opd') {
                $save_record = array(
                    'patient_id'   => $this->patient_data['patient_id'],
                    'paid_amount'  => ($data['amount']),
                    'opd_id'       => $ipdid,
                    'date'         => date('Y-m-d'),
                    'total_amount' => '',
                    'note'         => "Online fees deposit through IpayAfrica TXN ID: " . $transactionid,
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
                    'note'         => "Online fees deposit through IpayAfrica TXN ID: " . $transactionid,
                    'payment_mode' => 'Online',
                );
 
                $insert_id = $this->payment_model->addPayment($save_record);
            }
            redirect(base_url("patient/pay/successinvoice/" . $insert_id . '/' . $payment_type));
        }else{
             redirect(base_url("patient/pay/paymentfailed"));
        }
     
    }
}