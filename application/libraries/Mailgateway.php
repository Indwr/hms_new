<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailgateway
{

    private $_CI;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->model('setting_model');
        $this->_CI->load->model('appointment_model');
        $this->_CI->load->library('mailer');
        $this->_CI->load->model('payment_model');
        $this->_CI->mailer;
        $this->hospital_setting = $this->_CI->setting_model->get();
    }

    public function sentRegisterMailOPD($id, $send_to, $ptypeno, $template, $subject)
    {

        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $msg = $this->getPatientRegistrationContentOPD($id, $ptypeno, $template);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentRegistrationNotificationOPD($id,$ptypeno,$template) {
        $patient_result = $this->_CI->patient_model->getpatientDetails($id);
        $msg = $this->getPatientRegistrationContentOPD($id, $ptypeno, $template);
        $push_array = array(
            'title' => 'OPD Patient Registration Notification',
            'body' => $msg
        );
         if ($patient_result['app_key'] != "") {
             $this->_CI->pushnotification->send($patient_result['app_key'], $push_array, "mail_sms");
         }
    }

    public function sentRegisterMailIPD($id, $send_to, $ptypeno, $template, $subject)
    {

        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $msg = $this->getPatientRegistrationContentIPD($id, $ptypeno, $template);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentRegistrationNotificationIPD($id,$ptypeno,$template) {
        $patient_result = $this->_CI->patient_model->getpatientDetails($id);
        $msg = $this->getPatientRegistrationContentIPD($id, $ptypeno, $template);
        $push_array = array(
            'title' => 'IPD Patient Registration Notification',
            'body' => $msg
        );
         if ($patient_result['app_key'] != "") {
             $this->_CI->pushnotification->send($patient_result['app_key'], $push_array, "mail_sms");
         }
    }

    public function sentLiveconsultMail($id, $send_to, $conference_id, $template, $subject)
    {

        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $msg = $this->getPatientLiveConsultContent($id, $conference_id, $template);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }
   
    public function sentLiveconsultNotification($id,$conference_id,$template) {
        $patient_result = $this->_CI->patient_model->getpatientDetails($id);
        $msg = $this->getPatientLiveConsultContent($id, $conference_id, $template);
        $push_array = array(
            'title' => 'Live Consultation',
            'body' => $msg
        );
         if ($patient_result['app_key'] != "") {
             $this->_CI->pushnotification->send($patient_result['app_key'], $push_array, "mail_sms");
         }
    }

    public function sentOnlineMeetingStaffMail($detail, $subject, $template)
    {
        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($staff_value, $template);
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentDischargedMail($id, $send_to, $ipd_id, $template, $subject)
    {
        if (!empty($this->_CI->mail_config)) {
            $msg = $this->getPatientDischargedContent($id, $ipd_id, $template);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);

        }
    }

    public function sentDischargedNotificationIPD($id,$ipd_id,$template) {
        $patient_result = $this->_CI->patient_model->getpatientDetails($id);
        $msg = $this->getPatientDischargedContent($id, $ipd_id, $template);
        $push_array = array(
            'title' => 'IPD Patient Discharged Notification',
            'body' => $msg
        );
         if ($patient_result['app_key'] != "") {
             $this->_CI->pushnotification->send($patient_result['app_key'], $push_array, "mail_sms");
         }
    }

    public function sentopdDischargedMail($id, $send_to, $opd_id, $template, $subject)
    {
        if (!empty($this->_CI->mail_config)) {
            $msg = $this->getopdPatientDischargedContent($id, $opd_id, $template);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);

        }
    }

    public function sentDischargedNotificationOPD($id,$opd_id,$template) {
        $patient_result = $this->_CI->patient_model->getpatientDetails($id);
        $msg = $this->getopdPatientDischargedContent($id, $opd_id, $template);
        $push_array = array(
            'title' => 'OPD Patient Discharged Notification',
            'body' => $msg
        );
         if ($patient_result['app_key'] != "") {
             $this->_CI->pushnotification->send($patient_result['app_key'], $push_array, "mail_sms");
         }
    }

    public function sentAppointmentConfirmation($id, $send_to, $appointment_id, $template, $subject)
    {
        if (!empty($this->_CI->mail_config)) {
            $msg = $this->getAppointmentConfirmationContent($id, $appointment_id, $template);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentAppointmentConfirmationNotification($id,$appointment_id,$template) {
        $patient_result = $this->_CI->patient_model->getpatientDetails($id);
        $msg = $this->getAppointmentConfirmationContent($id, $appointment_id, $template);
        $push_array = array(
            'title' => 'Appointment Approved',
            'body' => $msg
        );
         if ($patient_result['app_key'] != "") {
             $this->_CI->pushnotification->send($patient_result['app_key'], $push_array, "mail_sms");
         }
    }

    public function sendLoginCredential($chk_mail_sms, $sender_details, $template, $subject)
    {
        $msg     = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);
        $send_to = $sender_details['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
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

    public function getPatientLiveConsultContent($id, $conference_id, $template)
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
        $patient = $this->_CI->appointment_model->getDetailsFornotification($appointment_id);
        foreach ($patient as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getOnlineMeetingStaffContent($staff_detail, $template)
    {
        foreach ($staff_detail as $key => $value) {
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
