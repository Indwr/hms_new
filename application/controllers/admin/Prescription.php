<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Prescription extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode   = $this->config->item('payment_mode');
        $this->blood_group    = $this->config->item('bloodgroup');
    }

    public function getPrescription($id, $opdid, $visitid = '')
    {
        if ($visitid > 0) {
            $result = $this->prescription_model->getvisit($visitid);
        } else {
            $result = $this->prescription_model->get($id);
        }

        $result['opd_id'];
        $prescription_list     = $this->prescription_model->getPrescriptionByOPD($result['opd_id'], $visitid);
        $data["print_details"] = $this->printing_model->get('', 'opdpre');
        $data["result"]        = $result;
        $data["id"]            = $id;
        $data["opdid"]         = $opdid;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data["prescription_list"] = $prescription_list;
        $this->load->view("admin/patient/prescription", $data);
    }

    public function getPrescriptionmanual($id, $opdid)
    {
        $result = $this->prescription_model->getmanual($opdid);

        $data["print_details"] = $this->printing_model->get('', 'opdpre');
        $data["result"]        = $result;

        $data["id"]    = $id;
        $data["opdid"] = $opdid;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }

        $this->load->view("admin/patient/prescriptionmanual", $data);
    }

    public function getIPDPrescription($id, $ipdid, $visitid = '')
    {
        $result                = $this->prescription_model->getIPD($id);
        $prescription_list     = $this->prescription_model->getPrescriptionByIPD($id, $ipdid, $visitid);
        $data["print_details"] = $this->printing_model->get('', 'ipdpres');
		
		
        $data["result"]        = $result;
        $data["id"]            = $id;
        $data["ipdid"]         = $ipdid;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data["prescription_list"] = $prescription_list;
        $this->load->view("admin/patient/ipdprescription", $data);
    }

    public function editPrescription($id, $opdid, $visitid = '')
    {
        $data['medicineCategory'] = $this->medicine_category_model->getMedicineCategory();
        $data['medicineName']     = $this->pharmacy_model->getMedicineName();
        $data['dosage']           = $this->medicine_dosage_model->getMedicineDosage();
        if ($visitid > 0) {
            $result = $this->prescription_model->getvisit($visitid);
        } else {
            $result = $this->prescription_model->get($id);
        }
        $prescription_list         = $this->prescription_model->getPrescriptionByOPD($opdid, $visitid);
        $data['roles']             = $this->role_model->get();
        $data["result"]            = $result;
        $data["id"]                = $id;
        $data["opdid"]             = $opdid;
        $data["prescription_list"] = $prescription_list;
        $this->load->view("admin/patient/edit_prescription", $data);
    }

    public function editipdPrescription($id, $ipdid, $visitid = '')
    {
        $data['medicineCategory']  = $this->medicine_category_model->getMedicineCategory();
        $data['medicineName']      = $this->pharmacy_model->getMedicineName();
        $data['dosage']            = $this->medicine_dosage_model->getMedicineDosage();
        $result                    = $this->prescription_model->getIPD($id);
        $prescription_list         = $this->prescription_model->getPrescriptionByIPD($id, $ipdid, $visitid);
        $data['roles']             = $this->role_model->get();
        $data["result"]            = $result;
        $data["id"]                = $id;
        $data["ipdid"]             = $ipdid;
        $data["prescription_list"] = $prescription_list;
        $this->load->view("admin/patient/edit_ipdprescription", $data);
    }

    public function deletePrescription($id, $opdid, $visitid = '')
    {
        if (!empty($opdid)) {
            if ($visitid > 0) {
                $this->prescription_model->deletePrescription($opdid, $visitid);
            } else {
                $this->prescription_model->deletePrescription($opdid);
            }
            $json = array('status' => 'success', 'error' => '', 'msg' => $this->lang->line('delete_message'));
            echo json_encode($json);
        }
    }

    public function deleteipdPrescription($id, $ipdid)
    {
        if (!empty($id)) {
            $this->prescription_model->deleteipdPrescription($id);
            $json = array('status' => 'success', 'error' => '', 'msg' => $this->lang->line('delete_message'));
            echo json_encode($json);
        }
    }

}
