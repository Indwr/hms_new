<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Smsgateway
{

    private $_CI;
    private $hospital_setting;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->model('setting_model');
        $this->_CI->load->model('staff_model');
        $this->_CI->load->model('appointment_model');
        $this->_CI->load->model('smsconfig_model');
        $this->_CI->load->model('payment_model');
        $this->hospital_setting = $this->_CI->setting_model->get();
    }

    public function sendSMS($send_to, $msg)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {
                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentLiveconsultSMS($id, $send_to, $conference_id, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getPatientLiveconsultContent($id, $conference_id, $template);
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {
                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {
                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentRegisterSMSOPD($id, $send_to, $ptypeno, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getPatientRegistrationContentOPD($id, $ptypeno, $template);
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentRegisterSMSIPD($id, $send_to, $ptypeno, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getPatientRegistrationContentIPD($id, $ptypeno, $template);
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentDischargedSMS($id, $send_to, $ipdid, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getPatientDischargedContent($id, $ipdid, $template);

        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {
                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentopdDischargedSMS($id, $send_to, $opdid, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getopdPatientDischargedContent($id, $opdid, $template);
        //$msg = $data["msg"];
        // $send_to = $data["contact"];
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {
                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentAppointmentConfirmation($id, $send_to, $appointment_id, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getAppointmentConfirmationContent($id, $appointment_id, $template);

        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }

            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentOnlineMeetingStaffSMS($detail, $template)
    {

        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {

            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($detail[$staff_key], $template);

                    $subject = "Live Meeting";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('clickatell', $params);

                        try {
                            $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {

                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode'        => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token'  => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number'      => $sms_detail->contact,
                        );

                        $this->_CI->load->library('twilio', $params);

                        $from     = $sms_detail->contact;
                        $to       = $send_to;
                        $message  = $msg;
                        $response = $this->_CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                        );
                        $this->_CI->load->library('msgnineone', $params);
                        $this->_CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'senderid' => $sms_detail->senderid,
                            'password' => $sms_detail->password,
                        );
                        $this->_CI->load->library('smscountry', $params);
                        $this->_CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash'     => $sms_detail->password,
                        );
                        $this->_CI->load->library('textlocalsms', $params);
                        $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'custom') {
                        $this->_CI->load->library('customsms');
                        $from    = $sms_detail->contact;
                        $to      = $send_to;
                        $message = $msg;
                        $this->_CI->customsms->sendSMS($to, $message);
                    } else {

                    }
                }
            }
        }
    }
    public function sendLoginCredential($chk_mail_sms, $sender_details, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);

        $send_to = $sender_details['contact_no'];
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {

                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username' => $sms_detail->username,
                    'senderid' => $sms_detail->senderid,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'custom') {
                $this->_CI->load->library('customsms');
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function getOnlineMeetingStaffContent($staff_detail, $template)
    {

        foreach ($staff_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getPatientRegistrationContentOPD($id, $ptypeno, $template)
    {
        $opdid   = $this->_CI->patient_model->getopdidbyopdno($ptypeno);
        $patient = $this->_CI->patient_model->getDetails($id, $opdid['opdid']);
        foreach ($patient as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getPatientRegistrationContentIPD($id, $ptypeno, $template)
    {
        $ipdid   = $this->_CI->patient_model->getipdidbyipdno($ptypeno);
        $patient = $this->_CI->patient_model->getIpdDetails($id, $ipdid['ipdid']);
        foreach ($patient as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getPatientLiveconsultContent($id, $conference_id,$template)
    {
        $conference = $this->_CI->conference_model->getconference($conference_id);
        foreach ($conference as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getPatientDischargedContent($id, $ipd_id, $template)
    {

        $result = $this->_CI->patient_model->getIpdfornotification($id, $ipd_id);

        foreach ($result as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getopdPatientDischargedContent($id, $opd_id, $template)
    {

        $result = $this->_CI->patient_model->getDetailsopdnotification($id, $opd_id);

        foreach ($result as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getAppointmentConfirmationContent($id, $appointment_id, $template)
    {

        $result = $this->_CI->appointment_model->getDetailsFornotification($appointment_id);

        foreach ($result as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getLoginCredentialContent($credential_for, $sender_details, $template)
    {
        if ($credential_for == "patient") {
            $patient                        = $this->_CI->patient_model->patientProfileDetails($sender_details['id']);
            $sender_details['url']          = site_url('site/userlogin');
            $sender_details['display_name'] = $patient['patient_name'];
        } elseif ($credential_for == "staff") {
            $staff                          = $this->_CI->staff_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/login');
            $sender_details['display_name'] = $staff['name'] . " " . $staff['surname'];
        }

        foreach ($sender_details as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

}
