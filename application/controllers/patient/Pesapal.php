<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pesapal extends Patient_Controller
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
        $this->load->library('pesapal_lib');
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
            $this->load->view("patient/pesapal", $data);
            $this->load->view("layout/patient/footer");
        } else {

        $token = $params = NULL;
        $consumer_key = $api_publishable_key;                  
        $consumer_secret =  $api_secret_key;
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';     
        $amount = number_format($data['amount'], 2);
        $desc = "Student Fee Payment";
        $type = 'MERCHANT'; 
        $reference = time();
        $first_name = $data['patient']['patient_name']; 
        $last_name = ''; 
        $email = $_POST['email'];
        $phonenumber = $_POST['phone']; 
        $callback_url = base_url('patient/pesapal/success'); 
        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
        $post_xml = htmlentities($post_xml);
        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
        $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
        $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        $data['iframe_src']=$iframe_src;
        $this->load->view('patient/pesapal_pay', $data);
            
            } 
    }





    public function success()
    {

            $reference = null;
            $pesapal_tracking_id = null;

            if(isset($_GET['pesapal_merchant_reference'])){
            $reference = $_GET['pesapal_merchant_reference'];
            }

            if(isset($_GET['pesapal_transaction_tracking_id'])){
            $pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];
            }

            $consumer_key = ($this->pay_method->api_publishable_key);
            $consumer_secret = ($this->pay_method->api_secret_key);
            $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
            $pesapalTrackingId=$_GET['pesapal_transaction_tracking_id'];
            $pesapal_merchant_reference=$_GET['pesapal_merchant_reference'];



            if($pesapalTrackingId!='')

            {
               $token = $params = NULL;
               $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
               $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
               $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
               $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
               $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
               $request_status->sign_request($signature_method, $consumer, $token);

               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $request_status);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
               curl_setopt($ch, CURLOPT_HEADER, 1);
               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
               if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True')

               {

                  $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
                  curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                  curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                  curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);

               }
               
               $response = curl_exec($ch);
               $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
               $raw_header  = substr($response, 0, $header_size - 4);
               $headerArray = explode("\r\n\r\n", $raw_header);
               $header      = $headerArray[count($headerArray) - 1];
               $elements = preg_split("/=/",substr($response, $header_size));
               $status = $elements[1];
     
        if ($status=='COMPLETED') {
            if ($this->session->has_userdata('payment_amount')) {
                $amount         = $this->session->userdata('payment_amount');
                $ipdid          = $amount['record_id'];
                $data['amount'] = $amount['deposit_amount'];
                $payment_type   =$amount['payment_type'];
            }
            $transactionid = $pesapal_tracking_id;
            if ($payment_type == 'opd') {
                $save_record = array(
                    'patient_id'   => $this->patient_data['patient_id'],
                    'paid_amount'  => ($data['amount']),
                    'opd_id'       => $ipdid,
                    'date'         => date('Y-m-d'),
                    'total_amount' => '',
                    'note'         => "Online fees deposit through Pesapal TXN ID: " . $transactionid,
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
                    'note'         => "Online fees deposit through Pesapal TXN ID: " . $transactionid,
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
}