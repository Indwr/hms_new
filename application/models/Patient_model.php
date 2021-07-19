<?php

class Patient_model extends CI_Model
{
    public $column_order            = array('patients.patient_name', 'patients.patient_unique_id', 'patients.guardian_name', 'patients.gender', 'patients.mobileno', 'staff.name', 'opd_details.appointment_date', 'opd_details.patient_id'); //set column field database for datatable orderable
    public $column_search           = array('patient_name', 'patient_unique_id', 'guardian_name', 'mobileno');
    public $columncredential_order  = array('patient_name', 'patient_unique_id', 'username', 'password'); //set column field database for datatable orderable
    public $columncredential_search = array('patient_name', 'patient_unique_id', 'username', 'password');
    public $columnipd_order         = array('patients.patient_name', 'ipd_details.ipd_no', 'patients.patient_unique_id', 'patients.gender', 'patients.mobileno', 'staff.name', 'bed.name', 'charges', 'payment', 'amountdue', 'ipd_details.credit_limit'); //set column field database for datatable orderable
    public $columnipd_search        = array('patients.patient_name', 'ipd_details.ipd_no', 'patients.patient_unique_id', 'patients.gender', 'patients.mobileno', 'staff.name', 'bed.name', 'ipd_details.credit_limit');

    public $columndis_order         = array('patients.patient_name', 'patients.patient_unique_id', 'patients.gender', 'patients.mobileno', 'staff.name', 'ipd_details.date','ipd_details.date','charges','other_charge','tax','discount','net_amount'); //set column field database for datatable orderable
    
    public $columndis_search        = array('patients.patient_name', 'patients.patient_unique_id','patients.mobileno', 'staff.name', 'ipd_details.date','ipd_details.date');

    public $columnpatient_order     = array('patient_unique_id', 'patient_name', 'age', 'gender', 'mobileno', 'guardian_name', 'address', 'action'); //set column field database for datatable orderable
    public $columnpatient_search    = array('patient_unique_id', 'patient_name', 'age', 'gender', 'mobileno', 'guardian_name', 'address');

    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('patients', $data);
        } else {
            $this->db->insert('patients', $data);
            return $this->db->insert_id();
        }
    }

    public function add_patient($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('patients', $data);
        } else {
            $this->db->insert('patients', $data);
            return $this->db->insert_id();
        }
    }

    public function valid_patient($id)
    {
        $this->db->select('ipd_details.patient_id,patients.discharged,patients.id as pid');
        $this->db->join('patients', 'patients.id=ipd_details.patient_id');
        $this->db->where('patient_id', $id);
        $this->db->where('patients.discharged', 'no');
        $query = $this->db->get('ipd_details');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function doctCharge($doctor)
    {
        $query = $this->db->where("doctor", $doctor)->get("consult_charges");
        return $query->row_array();
    }

    public function doctortpaCharge($doctor, $organisation = "")
    {
        $result      = array();
        $first_query = $this->db->where("consult_charges.doctor", $doctor)
            ->get("consult_charges");
        $first_result = $first_query->row_array();
        $charge_id    = $first_result["id"];
        $result       = $first_result;
        if (!empty($organisation)) {
            $second_query = $this->db->select("tpa_doctorcharges.org_charge")
                ->where("charge_id", $charge_id)
                ->where("org_id", $organisation)
                ->get("tpa_doctorcharges");
            $second_result = $second_query->row_array();
            if ($second_query->num_rows() > 0) {
                $result["org_charge"] = $second_result["org_charge"];
            } else {
                $result["org_charge"] = $first_result["standard_charge"];
            }
        } else {
            $result["org_charge"] = '';
        }
        return $result;
    }

    public function doctName($doctor)
    {
        $query = $this->db->where("id", $doctor)->get("staff");
        return $query->row_array();
    }

    public function patientDetails($id)
    {
        $query = $this->db->where("id", $id)->get("patients");
        return $query->row_array();
    }

    public function getpatient()
    {
        $query = $this->db->where("patients.is_active", 'yes')->get("patients");
        return $query->row_array();
    }

    public function doctorDetails($id)
    {
        $query = $this->db->where("id", $id)->get("staff");
        return $query->row_array();
    }

    public function supplierDetails($id)
    {
        $query = $this->db->where("id", $id)->get("supplier_category");
        return $query->row_array();
    }

    public function add_opd($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('opd_details', $data);
        } else {
            $this->db->insert('opd_details', $data);
            return $this->db->insert_id();
        }
    }

    public function add_opdvisit($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('visit_details', $data);
        } else {
            $this->db->insert('visit_details', $data);
            return $this->db->insert_id();
        }
    }

    public function add_ipd($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('ipd_details', $data);
        } else {
            $this->db->insert('ipd_details', $data);
            return $this->db->insert_id();
        }
    }

    public function add_disch_summary($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('discharged_summary', $data);
        } else {
            $this->db->insert('discharged_summary', $data);
            return $this->db->insert_id();
        }
    }

     public function add_dischopd_summary($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('discharged_summary_opd', $data);
        } else {
            $this->db->insert('discharged_summary_opd', $data);
            return $this->db->insert_id();
        }
    }

    public function addipd($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('ipd_prescription_basic', $data);
        } else {
            $this->db->insert('ipd_prescription_basic', $data);

            return $this->db->insert_id();
        }
    }

    public function searchAll($searchterm)
    {
        $this->db->select('patients.*')
            ->from('patients')
            ->like('patients.patient_name', $searchterm)
            ->or_like('patients.guardian_name', $searchterm)
            ->or_like('patients.patient_type', $searchterm)
            ->or_like('patients.address', $searchterm)
            ->or_like('patients.patient_unique_id', $searchterm)
            ->order_by('patients.id', 'desc');

        $query     = $this->db->get();
        $result    = $query->result_array();
        $info      = array();
        $data      = array();
        $url       = array();
        $info_data = array('OPD', 'IPD', 'Radiology', 'Pathology', 'Pharmacy', 'Operation Theatre');
        $info_url  = array();
        foreach ($result as $key => $value) {
            if ($value['is_active'] == 'yes') {
                $id          = $value["id"];
                $info_url[0] = base_url() . 'admin/patient/profile/' . $value['id'] . "/" . $value['is_active'];
                $info_url[1] = base_url() . 'admin/patient/ipdprofile/' . $value['id'];
                $info_url[2] = base_url() . 'admin/radio/getTestReportBatch';
                $info_url[3] = base_url() . 'admin/pathology/getTestReportBatch';
                $info_url[4] = base_url() . 'admin/pharmacy/bill';
                $info_url[5] = base_url() . 'admin/operationtheatre/otsearch';

                $info[0] = $this->db->where("patient_id", $id)->get("opd_details");
                $info[1] = $this->db->where("patient_id", $id)->get("ipd_details");
                $info[2] = $this->db->where("patient_id", $id)->get("radiology_report");
                $info[3] = $this->db->where("patient_id", $id)->get("pathology_report");
                $info[4] = $this->db->where("patient_id", $id)->get("pharmacy_bill_basic");
                $info[5] = $this->db->where("patient_id", $id)->get("operation_theatre");

                for ($i = 0; $i < sizeof($info); $i++) {
                    if ($info[$i]->num_rows() > 0) {
                        $data[$i] = $info_data[$i];
                        $url[$i]  = $info_url[$i];
                    } else {
                        unset($data[$i]);
                        unset($url[$i]);
                    }
                }
                $result[$key]['info'] = $data;
                $result[$key]['url']  = $url;
            } else {
                unset($result[$key]);
            }
        }

        return $result;
    }

    // ===================== search datatable for Patient ==================================

    public function searchpatient_datatable()
    {

        $this->db->select("patients.*,'0' action")->from('patients');
        if (!isset($_POST['order'])) {
            $this->db->order_by('patients.id', "desc");
        }
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->columnpatient_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }
        $this->db->limit($_POST['length'], $_POST['start']);
        if (isset($_POST['order'])) {
            $this->db->order_by($this->columnpatient_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        }
        $query     = $this->db->get();
        $result    = $query->result();
        $info      = array();
        $data      = array();
        $url       = array();
        $info_data = array('OPD', 'IPD', 'Radiology', 'Pathology', 'Pharmacy', 'Operation Theatre');
        $info_url  = array();
        foreach ($result as $key => $value) {
            if ($value->is_active == 'yes') {
                $id = $value->id;

                $info_url[0] = base_url() . 'admin/patient/profile/' . $value->id . "/" . $value->is_active;
                $info_url[1] = base_url() . 'admin/patient/ipdprofile/' . $value->id;
                $info_url[2] = base_url() . 'admin/radio/getTestReportBatch';
                $info_url[3] = base_url() . 'admin/pathology/getTestReportBatch';
                $info_url[4] = base_url() . 'admin/pharmacy/bill';
                $info_url[5] = base_url() . 'admin/operationtheatre/otsearch';

                $info[0] = $this->db->where("patient_id", $id)->get("opd_details");
                $info[1] = $this->db->where("patient_id", $id)->get("ipd_details");
                $info[2] = $this->db->where("patient_id", $id)->get("radiology_report");
                $info[3] = $this->db->where("patient_id", $id)->get("pathology_report");
                $info[4] = $this->db->where("patient_id", $id)->get("pharmacy_bill_basic");
                $info[5] = $this->db->where("patient_id", $id)->get("operation_theatre");

                for ($i = 0; $i < sizeof($info); $i++) {
                    if ($info[$i]->num_rows() > 0) {
                        $data[$i] = $info_data[$i];
                        $url[$i]  = $info_url[$i];
                    } else {
                        unset($data[$i]);
                        unset($url[$i]);
                    }
                }
                $result[$key]->info = $data;
                $result[$key]->url  = $url;
            } else {
                unset($result[$key]);
            }
        }
        return $result;

    }

    public function searchpatient_datatable_count()
    {
        $this->db->select('patients.*');
        $this->db->order_by('patients.id', "desc");
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->column_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }
        $query        = $this->db->from('patients');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function searchAlldisable($searchterm)
    {
        $this->db->select('patients.*')
            ->from('patients')
            ->like('patients.patient_name', $searchterm)
            ->or_like('patients.guardian_name', $searchterm)
            ->or_like('patients.patient_type', $searchterm)
            ->or_like('patients.address', $searchterm)
            ->or_like('patients.patient_unique_id', $searchterm)
            ->order_by('patients.id', 'desc');
        $query     = $this->db->get();
        $result    = $query->result_array();
        $info      = array();
        $data      = array();
        $url       = array();
        $info_data = array('OPD', 'IPD', 'Radiology', 'Pathology', 'Pharmacy', 'Operation Theatre');
        $info_url  = array();
        foreach ($result as $key => $value) {
            if ($value['is_active'] == 'no') {
                $id = $value["id"];

                $info_url[0] = base_url() . 'admin/patient/profile/' . $value['id'] . "/" . $value['is_active'];
                $info_url[1] = base_url() . 'admin/patient/ipdprofile/' . $value['id'] . "/" . $value['is_active'];
                $info_url[2] = base_url() . 'admin/radio/getTestReportBatch';
                $info_url[3] = base_url() . 'admin/pathology/getTestReportBatch';
                $info_url[4] = base_url() . 'admin/pharmacy/bill';
                $info_url[5] = base_url() . 'admin/operationtheatre/otsearch';

                $info[0] = $this->db->where("patient_id", $id)->get("opd_details");
                $info[1] = $this->db->where("patient_id", $id)->get("ipd_details");
                $info[2] = $this->db->where("patient_id", $id)->get("radiology_report");
                $info[3] = $this->db->where("patient_id", $id)->get("pathology_report");
                $info[4] = $this->db->where("patient_id", $id)->get("pharmacy_bill_basic");
                $info[5] = $this->db->where("patient_id", $id)->get("operation_theatre");

                for ($i = 0; $i < sizeof($info); $i++) {
                    if ($info[$i]->num_rows() > 0) {
                        $data[$i] = $info_data[$i];
                        $url[$i]  = $info_url[$i];
                    } else {
                        unset($data[$i]);
                        unset($url[$i]);
                    }
                }
                $result[$key]['info'] = $data;
                $result[$key]['url']  = $url;
            } else {
                unset($result[$key]);
            }
        }

        return $result;
    }

    public function checkpatientipd($patient_type)
    {
        $this->db->where('patient_id', $patient_type);
        $query = $this->db->get('ipd_details');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

     public function checkpatientipddis($pid)
    {
        $this->db->where('patient_id', $pid);
        $this->db->where('discharged', 'no');
        $query = $this->db->get('ipd_details');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function checkpatientopd($patient_type)
    {
        $this->db->where('patient_id', $patient_type);
        $query = $this->db->get('opd_details');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function checkpatientpharma($patient_type)
    {
        $this->db->where('patient_id', $patient_type);
        $query = $this->db->get('pharmacy_bill_basic');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function checkpatientot($patient_type)
    {
        $this->db->where('patient_id', $patient_type);
        $query = $this->db->get('operation_theatre');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function getPatientListall()
    {
        $this->db->select('patients.*')->from('patients');
        $this->db->where('patients.is_active', 'yes');
        $this->db->order_by('patients.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();

        $userdata           = $this->customlib->getUserData();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option     = false;
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $disable_option = true;
                $doctorid       = $userdata['id'];
            }
        }
    }

    public function getsymptoms($id)
    {
        $this->db->select('symptoms.*')->from('symptoms');
        $this->db->where('symptoms.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getBlooddonarListall()
    {
        $this->db->select('blood_donor.*')->from('blood_donor');
        $this->db->order_by('blood_donor.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPatientListallPat()
    {
        $this->db->select('patients.*')->from('patients');
        $this->db->order_by('patients.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPatientList()
    {
        $this->db->select('patients.*,users.username,users.id as user_tbl_id,users.is_active as user_tbl_active')
            ->join('users', 'users.user_id = patients.id')
            ->from('patients');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getpatientDetails($id)
    {
        $this->db->select('patients.*')->from('patients')->where('patients.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getpatientbyUniqueid($uid)
    {
        $this->db->select('patients.id')->from('patients')->where('patients.patient_unique_id', $uid);
        $query = $this->db->get();
        return $this->db->query();
    }

    public function searchFullText($opd_month, $searchterm, $carray = null, $limit = 100, $start = "")
    {
        $last_date          = date("Y-m-01 23:59:59.993", strtotime("-" . $opd_month . " month"));
        $userdata           = $this->customlib->getUserData();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $this->db->select('opd_details.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,patients.guardian_name,patients.gender,patients.mobileno,patients.is_ipd,staff.name,staff.surname')->from('opd_details');
        $this->db->join('patients', "patients.id=opd_details.patient_id", "LEFT");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "LEFT");
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->or_like('patients.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('max(opd_details.appointment_date)', 'desc');
        $this->db->group_by('opd_details.patient_id');
        $this->db->limit($limit, $start);
        if ($doctor_restriction == 'enabled') {
            if ($userdata["role_id"] == 3) {
                $this->db->where('opd_details.cons_doctor', $userdata['id']);
            }
        }
        $query  = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    // ===================== search datatable for OPD ==================================

    public function search_datatable()
    {
        $setting            = $this->setting_model->get();
        $opd_month          = $setting[0]['opd_record_month'];
        $userdata           = $this->customlib->getUserData();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        if ($doctor_restriction == 'enabled') {
            if ($userdata["role_id"] == 3) {
                $this->db->where('opd_details.cons_doctor', $userdata['id']);
            }
        }
        $this->db->select('opd_details.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,patients.guardian_name,patients.gender,patients.mobileno,patients.is_ipd,staff.name,staff.surname')->from('opd_details');
        $this->db->join('patients', "patients.id=opd_details.patient_id", "LEFT");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "LEFT");
        $this->db->where('patients.is_active', 'yes');
        if (!isset($_POST['order'])) {
            $this->db->order_by('max(opd_details.appointment_date)', 'desc');
        }
        $this->db->group_by('opd_details.patient_id');
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->column_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }
        $this->db->limit($_POST['length'], $_POST['start']);
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        }

        $query  = $this->db->get();
        $result = $query->result();
        $i      = 0;
        foreach ($result as $key => $value) {
            $generated_by = $value->generated_by;
            $staff_query  = $this->db->select("staff.name,staff.surname")
                ->where("staff.id", $generated_by)
                ->get("staff");
            $staff_result                   = $staff_query->row_array();
            $result[$key]->generated_byname = $staff_result["name"] . $staff_result["surname"];
            $patient_id                     = $value->pid;
            $total_visit                    = $this->totalVisit($patient_id);
            $last_visit                     = $this->lastVisit($patient_id);
            $opdno                          = $this->lastVisitopdno($patient_id);
            $consultant                     = $this->getConsultant($patient_id, $opd_month);
            $result[$i]->total_visit        = $total_visit["total_visit"];
            $result[$i]->last_visit         = $last_visit["last_visit"];
            $result[$i]->opdno              = $opdno['opdno'];
            $i++;
        }

        return $result;
    }

    public function search_datatable_count()
    {
        $userdata           = $this->customlib->getUserData();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        if ($doctor_restriction == 'enabled') {
            if ($userdata["role_id"] == 3) {
                $this->db->where('opd_details.cons_doctor', $userdata['id']);
            }
        }
        $this->db->select('opd_details.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,patients.guardian_name,patients.gender,patients.mobileno,patients.is_ipd,staff.name,staff.surname');
        $this->db->join('patients', "patients.id=opd_details.patient_id", "LEFT");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "LEFT");
        $this->db->where('patients.is_active', 'yes');
        $this->db->order_by('max(opd_details.appointment_date)', 'desc');
        $this->db->group_by('opd_details.patient_id');
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->column_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }
        $query        = $this->db->from('opd_details');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    // ===================== search datatable for patient credential ==================================
    public function search_datatablecredential()
    {
        $this->db->select('patients.*,users.id as uid,users.user_id,users.username,users.password')->from('patients');
        $this->db->join('users', 'patients.id = users.user_id');
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->columncredential_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->order_by($this->columncredential_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function search_datatablecredential_count()
    {

        $this->db->select('patients.*,users.id as uid,users.user_id,users.username,users.password');
        $this->db->join('users', 'patients.id = users.user_id');
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->columncredential_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();

        }

        $query        = $this->db->from('patients');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function searchByMonth($opd_month, $searchterm, $carray = null)
    {
        $data       = array();
        $first_date = date('Y-m' . '-01', strtotime("-" . $opd_month . " month"));
        $last_date  = date('Y-m' . '-' . date('t', strtotime($first_date)) . ' 23:59:59.993');
        $this->db->select('patients.*')->from('patients');
        $this->db->where('patients.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->or_like('patients.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('patients.id', 'desc');
        $query  = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $key => $value) {
            $consultant_data = $this->getConsultant($value["id"], $opd_month);
            if (!empty($consultant_data)) {
                $result[$key]['name']    = $consultant_data[0]["name"];
                $result[$key]['surname'] = $consultant_data[0]["surname"];
            }
        }

        return $result;
    }

    public function getConsultant($patient_id, $opd_month)
    {
        $first_date = date('Y-m' . '-01', strtotime("-" . $opd_month . " month"));
        $last_date  = date('Y-m' . '-' . date('t', strtotime($first_date)) . ' 23:59:59.993');
        $opd_query  = $this->db->select('opd_details.appointment_date,opd_details.case_type,staff.name,staff.surname')
            ->join('staff', 'staff.id = opd_details.cons_doctor', "inner")
            ->where('opd_details.appointment_date >', $first_date)
            ->where('opd_details.appointment_date <', $last_date)
            ->where('opd_details.patient_id', $patient_id)
            ->limit(1)
            ->get('opd_details');
        $result = $opd_query->result_array();
        return $result;
    }

    public function totalVisit($patient_id)
    {
        $query = $this->db->select('count(opd_details.patient_id) as total_visit')
            ->where('patient_id', $patient_id)
            ->get('opd_details');
        return $query->row_array();
    }

    public function lastVisit($patient_id)
    {
        $query = $this->db->select('max(opd_details.appointment_date) as last_visit')
            ->where('patient_id', $patient_id)
            ->get('opd_details');
        return $query->row_array();
    }

    public function lastVisitopdno($patient_id)
    {
        $query = $this->db->select('max(opd_details.appointment_date) as lastvisit_date')
            ->where('patient_id', $patient_id)
            ->get('opd_details');
        $data = $query->row_array();

        if (!empty($data)) {
            $visitdate = $data["lastvisit_date"];
            $opd_query = $this->db->select("opd_details.opd_no as opdno")
                ->where("opd_details.appointment_date", $visitdate)
                ->get("opd_details");
            $result = $opd_query->row_array();
        }
        return $result;
    }

    public function getMaxPatientId()
    {
        $query = $this->db->select('max(patients.id) as patient_id')
            ->where('patients.is_active', 'yes')
            ->get('patients');
        $result = $query->row_array();
        return $result["patient_id"];
    }

    public function patientProfile($id, $active = 'yes')
    {

        $query     = $this->db->where("id", $id)->get("patients");
        $result    = $query->row_array();
        $data      = array();
        $opd_query = $this->db->where('patient_id', $id)->get('opd_details');
        $ipd_query = $this->db->where('patient_id', $id)->get('ipd_details');
        if ($opd_query->num_rows() > 0) {
            $data                 = $this->getDetails($id);
            $data["patient_type"] = 'Outpatient';
        } else if ($ipd_query->num_rows() > 0) {
            $data                 = $this->getIpdDetails($id, $active);
            $data["patient_type"] = 'Inpatient';
        }
        return $data;
    }

    public function patientProfileDetails($id, $active = 'yes')
    {
        $query  = $this->db->where("id", $id)->get("patients");
        $result = $query->row_array();
        return $result;
    }

    public function patientProfileType($id, $ptypeno)
    {
        $query     = $this->db->where("id", $id)->get("patients");
        $result    = $query->row_array();
        $data      = array();
        $opd_query = $this->db->where('opd_details.patient_id', $id)->where('opd_details.opd_no', $ptypeno)->get('opd_details');
        $ipd_query = $this->db->where('patient_id', $id)->where('ipd_details.ipd_no', $ptypeno)->get('ipd_details');
        if ($opd_query->num_rows() > 0) {
            $data                 = $this->getDetails($id);
            $data["patient_type"] = 'Outpatient';
        } else if ($ipd_query->num_rows() > 0) {
            $data                 = $this->getIpdDetailsptype($id);
            $data["patient_type"] = 'Inpatient';
        }
        return $data;
    }

    public function getDetails($id, $opdid = null)
    {
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,opd_details.id as opdid,opd_details.casualty,opd_details.cons_doctor,opd_details.generated_by as generated_id,opd_details.refference,opd_details.opd_no,opd_details.known_allergies as opdknown_allergies,opd_details.amount as amount,opd_details.height,opd_details.weight,opd_details.bp,opd_details.symptoms,opd_details.tax,opd_details.payment_mode,opd_details.note_remark,opd_details.discharged,opd_details.pulse,opd_details.temperature,opd_details.respiration,opd_details.opd_no,opd_details.live_consult,opd_details.patient_id as pid,discharged_summary_opd.id as summary_id,discharged_summary_opd.note as summary_note,discharged_summary_opd.diagnosis as disdiagnosis,discharged_summary_opd.operation as disoperation,discharged_summary_opd.investigations as summary_investigations,discharged_summary_opd.treatment_home as summary_treatment_home,opd_billing.status,opd_billing.gross_total,opd_billing.discount,opd_billing.date as discharge_date,opd_billing.tax,opd_billing.net_amount,opd_billing.total_amount,opd_billing.other_charge,opd_billing.generated_by,opd_billing.id as bill_id,organisation.organisation_name,organisation.id as orgid,staff.id as staff_id,staff.name,staff.surname,consult_charges.standard_charge,opd_patient_charges.apply_charge,visit_details.amount as visitamount,visit_details.id as visitid')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "left");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "left");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->join('opd_billing', 'opd_details.id = opd_billing.opd_id', "left");
        $this->db->join('discharged_summary_opd', 'opd_details.id = discharged_summary_opd.opd_id', "left");
        $this->db->join('consult_charges', 'consult_charges.doctor=opd_details.cons_doctor', 'left');
        $this->db->join('opd_patient_charges', 'opd_details.id=opd_patient_charges.opd_id', 'left');
        $this->db->join('visit_details', 'visit_details.opd_id=opd_details.id', 'left');
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('patients.id', $id);
        if ($opdid != null) {
            $this->db->where('opd_details.id', $opdid);
        }

        $query  = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            $generated_by = $result["generated_id"];
            $staff_query  = $this->db->select("staff.name,staff.surname")
                ->where("staff.id", $generated_by)
                ->get("staff");
            $staff_result               = $staff_query->row_array();
            $result["generated_byname"] = $staff_result["name"] . " " . $staff_result["surname"];
        }
        return $result;
    }

    public function getDetailsopdnotification($id, $opdid = null)
    {
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,opd_details.id as opdid,opd_details.casualty,opd_details.cons_doctor,opd_details.generated_by as generated_id,opd_details.refference,opd_details.opd_no,opd_details.known_allergies as opdknown_allergies,opd_details.amount as amount,opd_details.height,opd_details.weight,opd_details.bp,opd_details.symptoms,opd_details.tax,opd_details.payment_mode,opd_details.note_remark,opd_details.discharged,opd_details.pulse,opd_details.temperature,opd_details.respiration,opd_details.opd_no,opd_details.live_consult,opd_billing.status,opd_billing.gross_total,opd_billing.discount,opd_billing.date as discharge_date,opd_billing.tax,opd_billing.net_amount,opd_billing.total_amount,opd_billing.other_charge,opd_billing.generated_by,opd_billing.id as bill_id,organisation.organisation_name,organisation.id as orgid,staff.id as staff_id,staff.name,staff.surname,consult_charges.standard_charge,opd_patient_charges.apply_charge,visit_details.amount as visitamount,visit_details.id as visitid')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "left");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "left");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->join('opd_billing', 'patients.id = opd_billing.patient_id', "left");
        $this->db->join('consult_charges', 'consult_charges.doctor=opd_details.cons_doctor', 'left');
        $this->db->join('opd_patient_charges', 'opd_details.id=opd_patient_charges.opd_id', 'left');
        $this->db->join('visit_details', 'visit_details.opd_id=opd_details.id', 'left');
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('patients.id', $id);
        if ($opdid != null) {
            $this->db->where('opd_details.id', $opdid);
        }

        $query  = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
             $hospital_setting = $this->setting_model->get();
             $result['currency_symbol'] = $hospital_setting[0]['currency_symbol'];
            $generated_by = $result["generated_id"];
            $staff_query  = $this->db->select("staff.name,staff.surname")
                ->where("staff.id", $generated_by)
                ->get("staff");
            $staff_result               = $staff_query->row_array();
            $result["generated_byname"] = $staff_result["name"] . " " . $staff_result["surname"];
            $payment = $this->payment_model->getopdbilling($id, $opdid);
            $result['billing_amount'] = $payment['billing_amount'];
        }
        return $result;
    }

    public function addImport($patient_data)
    {
        $this->db->insert('patients', $patient_data);
        return $this->db->insert_id();
    }

    public function getIpdDetails($id, $ipdid = '', $active = 'yes')
    {
        $this->db->select('patients.*,ipd_details.patient_id,ipd_details.date,ipd_details.discharged_date as ipd_dischargedate,ipd_details.case_type,ipd_details.ipd_no,ipd_details.id as ipdid,ipd_details.casualty,ipd_details.height,ipd_details.weight,ipd_details.bp,ipd_details.cons_doctor,ipd_details.refference,ipd_details.known_allergies as ipdknown_allergies,ipd_details.amount,ipd_details.credit_limit as ipdcredit_limit,ipd_details.symptoms,ipd_details.discharged as ipd_discharge,ipd_details.tax,ipd_details.bed,ipd_details.bed_group_id,ipd_details.note as ipdnote,ipd_details.bed,ipd_details.bed_group_id,ipd_details.payment_mode,ipd_details.credit_limit,ipd_details.pulse,ipd_details.temperature,ipd_details.respiration,discharged_summary.id as summary_id,discharged_summary.note as summary_note,discharged_summary.diagnosis as disdiagnosis,discharged_summary.operation as disoperation,discharged_summary.investigations as summary_investigations,discharged_summary.treatment_home as summary_treatment_home,ipd_billing.status,ipd_billing.gross_total,ipd_billing.discount,ipd_billing.date as discharge_date,ipd_billing.tax,ipd_billing.net_amount,ipd_billing.total_amount,ipd_billing.other_charge,ipd_billing.generated_by,ipd_billing.id as bill_id,staff.id as staff_id,staff.name,staff.surname,organisation.organisation_name,bed.name as bed_name,bed.id as bed_id,bed_group.name as bedgroup_name,floor.name as floor_name')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "left");
        $this->db->join('ipd_billing', 'ipd_details.id = ipd_billing.ipd_id', "left");
        $this->db->join('discharged_summary', 'ipd_details.id = discharged_summary.ipd_id', "left");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', $active);
        $this->db->where('patients.id', $id);
        $this->db->where('ipd_details.id', $ipdid);
        $query = $this->db->get();
        return $query->row_array();
    }

     public function getIpdfornotification($id, $ipdid = '', $active = 'yes')
    {
        $this->db->select('patients.*,ipd_details.patient_id,ipd_details.date,ipd_details.discharged_date as ipd_dischargedate,ipd_details.case_type,ipd_details.ipd_no,ipd_details.id as ipdid,ipd_details.casualty,ipd_details.height,ipd_details.weight,ipd_details.bp,ipd_details.cons_doctor,ipd_details.refference,ipd_details.known_allergies as ipdknown_allergies,ipd_details.amount,ipd_details.credit_limit as ipdcredit_limit,ipd_details.symptoms,ipd_details.discharged as ipd_discharge,ipd_details.tax,ipd_details.bed,ipd_details.bed_group_id,ipd_details.note as ipdnote,ipd_details.bed,ipd_details.bed_group_id,ipd_details.payment_mode,ipd_details.credit_limit,ipd_details.pulse,ipd_details.temperature,ipd_details.respiration,discharged_summary.id as summary_id,discharged_summary.note as summary_note,discharged_summary.diagnosis as disdiagnosis,discharged_summary.operation as disoperation,discharged_summary.investigations as summary_investigations,discharged_summary.treatment_home as summary_treatment_home,ipd_billing.status,ipd_billing.gross_total,ipd_billing.discount,ipd_billing.date as discharge_date,ipd_billing.tax,ipd_billing.net_amount,ipd_billing.total_amount,ipd_billing.other_charge,ipd_billing.generated_by,ipd_billing.id as bill_id,staff.id as staff_id,staff.name,staff.surname,organisation.organisation_name,bed.name as bed_name,bed.id as bed_id,bed_group.name as bedgroup_name,floor.name as floor_name')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "left");
        $this->db->join('ipd_billing', 'ipd_details.id = ipd_billing.ipd_id', "left");
        $this->db->join('discharged_summary', 'ipd_details.id = discharged_summary.ipd_id', "left");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', $active);
        $this->db->where('patients.id', $id);
        $this->db->where('ipd_details.id', $ipdid);
        $query = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
             $hospital_setting = $this->setting_model->get();
             $result['currency_symbol'] = $hospital_setting[0]['currency_symbol'];
             $charge = $this->payment_model->getChargeTotal($id, $ipdid);
             $result['charge_amount'] = $charge['apply_charge'];
             $payment = $this->payment_model->getPaidTotal($id, $ipdid);
             $result['paid_amount'] = $payment['paid_amount'];
         }
         return $result;
    }
    

    public function getIpdDetailsptype($id)
    {
        $this->db->select('patients.*,ipd_details.patient_id,ipd_details.date,ipd_details.case_type,ipd_details.ipd_no,ipd_details.id as ipdid,ipd_details.casualty,ipd_details.height,ipd_details.weight,ipd_details.bp,ipd_details.cons_doctor,ipd_details.refference,ipd_details.known_allergies,ipd_details.amount,ipd_details.credit_limit as ipdcredit_limit,ipd_details.symptoms,ipd_details.discharged as ipd_discharge,ipd_details.tax,ipd_details.bed,ipd_details.bed_group_id,ipd_details.note as ipdnote,ipd_details.bed,ipd_details.bed_group_id,')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "left");
        $this->db->where('patients.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getIpdnotiDetails($id)
    {
        $this->db->select('ipd_details.*,')->from('ipd_details');
        $this->db->where('ipd_details.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getsummaryDetails($id)
    {
        $this->db->select('discharged_summary.*,patients.patient_name,patients.id as patientid,patients.patient_unique_id,patients.age,patients.month,patients.gender,patients.address,ipd_details.date,ipd_details.discharged_date');
        $this->db->join('patients', 'discharged_summary.patient_id = patients.id');
        $this->db->join('ipd_details', 'discharged_summary.ipd_id = ipd_details.id');
        $this->db->where('discharged_summary.id', $id);
        $query = $this->db->get('discharged_summary');
        return $query->row_array();
    }

     public function getsummaryopdDetails($id)
    {
        $this->db->select('discharged_summary_opd.*,patients.patient_name,patients.id as patientid,patients.patient_unique_id,patients.age,patients.month,patients.gender,patients.address,opd_billing.date as discharged_date,opd_details.id as opdid,opd_details.appointment_date');
        $this->db->join('patients', 'discharged_summary_opd.patient_id = patients.id');
        $this->db->join('opd_details', 'discharged_summary_opd.opd_id = opd_details.id');
        $this->db->join('opd_billing', 'discharged_summary_opd.opd_id = opd_billing.opd_id','inner');
        $this->db->where('discharged_summary_opd.id', $id);
        $query = $this->db->get('discharged_summary_opd');
        return $query->row_array();
    }

    public function getOpdnotiDetails($id)
    {
        $this->db->select('opd_details.*,')->from('opd_details');
        $this->db->where('opd_details.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getOpdpresnotiDetails($id)
    {
        $this->db->select('opd_details.*,')->from('opd_details');
        $this->db->where('opd_details.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getPatientId()
    {
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,opd_details.id as opdid,opd_details.casualty,opd_details.cons_doctor,opd_details.refference,opd_details.known_allergies,opd_details.amount,opd_details.symptoms,opd_details.tax,opd_details.payment_mode')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->where('patients.is_active', 'yes');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getpatientidbyipd($ipdid)
    {
        $this->db->select('ipd_details')->from('ipd_details');
        $this->db->where('ipd_details.id', $ipdid);
        $query = $this->db->get();
        return $query->row_array();
    }
     public function getopdidbyopdno($opdno)
    {
        $this->db->select('opd_details.id as opdid')->from('opd_details');
        $this->db->where('opd_details.opd_no', $opdno);
        $query = $this->db->get();
        return $query->row_array();
    }

     public function getipdidbyipdno($ipdno)
    {
        $this->db->select('ipd_details.id as ipdid')->from('ipd_details');
        $this->db->where('ipd_details.ipd_no', $ipdno);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getOpd($id)
    {
        $this->db->select('opd_details.id,opd_details.opd_no as opdipd_no')->from('opd_details');
        $this->db->where('opd_details.patient_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getIpd($id)
    {
        $this->db->select('ipd_details.id,ipd_details.ipd_no as opdipd_no')->from('ipd_details');
        $this->db->where('ipd_details.patient_id', $id);
        $this->db->where('ipd_details.discharged', 'no');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getOPDetails($id, $opdid = null)
    {
        if (!empty($opdid)) {
            $this->db->where("opd_details.id", $opdid);
        }
        $this->db->select('opd_details.*,patients.organisation,patients.old_patient,patients.patient_name,staff.id as staff_id,staff.name,staff.surname,consult_charges.standard_charge,opd_patient_charges.apply_charge')->from('opd_details');
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->join('patients', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('consult_charges', 'consult_charges.doctor=opd_details.cons_doctor', 'left');
        $this->db->join('opd_patient_charges', 'opd_details.id=opd_patient_charges.opd_id', 'left');
        $this->db->where('opd_details.patient_id', $id);
        $this->db->group_by('opd_details.id', '');
        $this->db->order_by('opd_details.id', 'desc');
        $query = $this->db->get();
        if (!empty($opdid)) {
            return $query->row_array();
        } else {
            $result = $query->result_array();
            $i      = 0;
            foreach ($result as $key => $value) {
                $opd_id = $value["id"];
                $check  = $this->db->where("opd_id", $opd_id)->where("visit_id", 0)->get('prescription');
                if ($check->num_rows() > 0) {
                    $result[$i]['prescription'] = 'yes';
                } else {
                    $result[$i]['prescription'] = 'no';
                    $userdata                   = $this->customlib->getUserData();
                    if ($this->session->has_userdata('hospitaladmin')) {
                        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
                        if ($doctor_restriction == 'enabled') {
                            if ($userdata["role_id"] == 3) {
                                if ($userdata["id"] == $value["staff_id"]) {

                                } else {
                                    $result[$i]['prescription'] = 'not_applicable';
                                }
                            }
                        }
                    }
                }
                $i++;
            }
            return $result;
        }
    }

    public function getOPDDetailsforbill($id, $opdid = null)
    {
        if (!empty($opdid)) {
            $this->db->where("opd_details.id", $opdid);
        }
        $this->db->select('opd_details.*,patients.organisation,patients.old_patient,patients.patient_name,staff.id as staff_id,staff.name,staff.surname,consult_charges.standard_charge,opd_patient_charges.apply_charge,opd_payment.paid_amount,opd_billing.status')->from('opd_details');
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->join('patients', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('consult_charges', 'consult_charges.doctor=opd_details.cons_doctor', 'left');
        $this->db->join('opd_patient_charges', 'opd_details.id=opd_patient_charges.opd_id', 'left');
        $this->db->join('opd_payment', 'opd_payment.opd_id=opd_details.id', 'left');
        $this->db->join('opd_billing', 'opd_billing.opd_id=opd_details.id', 'left');
        $this->db->where('opd_details.patient_id', $id);
        $this->db->group_by('opd_details.id', '');
        $this->db->order_by('opd_details.id', 'desc');
        $query = $this->db->get();
        if (!empty($opdid)) {
            return $query->row_array();
        } else {
            $result = $query->result_array();
            $i      = 0;
            foreach ($result as $key => $value) {
                $opd_id                = $value["id"];
                $check                 = $this->db->where("opd_id", $opd_id)->where("visit_id", 0)->get('prescription');
                $payment               = $this->getOPDPayment($value["patient_id"], $value["id"]);
                $charge                = $this->getOPDCharges($value["patient_id"], $value["id"]);
                $bill                  = $this->getOPDbill($value["patient_id"], $value["id"]);
                $result[$i]["payment"] = $payment['opdpayment'];
                $result[$i]["charges"] = $charge['charge'];
                $result[$i]["bill"]    = $bill['billamount'];

                $i++;
            }
            return $result;
        }
    }

    public function getIPDDetailsforbill($id, $opdid = null)
    {
        if (!empty($opdid)) {
            $this->db->where("ipd_details.id", $opdid);
        }
        $this->db->select('ipd_details.*,patients.organisation,patients.old_patient,patients.patient_name,staff.id as staff_id,staff.name,staff.surname,patient_charges.apply_charge,payment.paid_amount,ipd_billing.status')->from('ipd_details');
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('patients', 'patients.id = ipd_details.patient_id', "inner");
        //$this->db->join('consult_charges', 'consult_charges.doctor=opd_details.cons_doctor', 'left');
        $this->db->join('patient_charges', 'ipd_details.id=patient_charges.ipd_id', 'left');
        $this->db->join('payment', 'payment.ipd_id=ipd_details.id', 'left');
        $this->db->join('ipd_billing', 'ipd_billing.ipd_id=ipd_details.id', 'left');
        $this->db->where('ipd_details.patient_id', $id);
        $this->db->group_by('ipd_details.id', '');
        $this->db->order_by('ipd_details.id', 'desc');
        $query = $this->db->get();
        if (!empty($opdid)) {
            return $query->row_array();
        } else {
            $result = $query->result_array();
            $i      = 0;
            foreach ($result as $key => $value) {
                $opd_id                = $value["id"];
                $check                 = $this->db->where("opd_id", $opd_id)->where("visit_id", 0)->get('prescription');
                $payment               = $this->getPayment($value["patient_id"], $value["id"]);
                $charge                = $this->getCharges($value["patient_id"], $value["id"]);
                $bill                  = $this->getIpdBillDetails($value["patient_id"], $value["id"]);
                $result[$i]["payment"] = $payment['payment'];
                $result[$i]["charges"] = $charge['charge'];
                $result[$i]["bill"]    = $bill['billamount'];

                $i++;
            }
            return $result;
        }
    }

    public function geteditDiagnosis($id)
    {
        $this->db->select('diagnosis.*,patients.patient_name,patients.patient_name')->from('diagnosis');
        $this->db->join('patients', 'patients.id = diagnosis.patient_id');
        $this->db->where('diagnosis.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getopddetailspres($id)
    {
        $this->db->select('opd_details.*,patients.organisation,patients.old_patient,staff.id as staff_id,staff.name,staff.surname')->from('opd_details');
        $this->db->join('patients', 'patients.id = opd_details.patient_id');
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->where('opd_details.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getopddetailsbill($id)
    {
        $this->db->select('opd_details.*,patients.organisation,patients.patient_name,patients.old_patient,staff.id as staff_id,staff.name,staff.surname')->from('opd_details');
        $this->db->join('patients', 'patients.id = opd_details.patient_id');
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->where('opd_details.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getipddetailspres($id)
    {
        $this->db->select('ipd_details.*,patients.organisation,patients.old_patient,staff.id as staff_id,staff.name,staff.surname')->from('ipd_details');
        $this->db->join('patients', 'patients.id = ipd_details.patient_id');
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->where('ipd_details.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function add_diagnosis($data)
    {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("diagnosis", $data);
        } else {
            $this->db->insert("diagnosis", $data);
            return $this->db->insert_id();
        }
    }

    public function getDiagnosisDetails($id)
    {
        $query1 = $this->db->select('diagnosis.*,diagnosis.id as diagnosis')
            ->join('patients', 'patients.id = diagnosis.patient_id', "inner")
            ->where("patient_id", $id)
            ->get("diagnosis");
        $result1 = $query1->result_array();

        $query2 = $this->db->select('pathology_report.reporting_date as report_date,pathology_report.id,pathology_report.patient_id as patient_id,pathology_report.pathology_report as document,pathology.test_name as report_type,pathology_report.description')
            ->join('pathology', 'pathology.id = pathology_report.pathology_id', "inner")
            ->join('patients', 'patients.id = pathology_report.patient_id', "inner")
            ->where("pathology_report.patient_id", $id)
            ->get("pathology_report");
        $result2 = $query2->result_array();
        $query3  = $this->db->select('radiology_report.reporting_date as report_date,radiology_report.id,radiology_report.patient_id as patient_id,radiology_report.radiology_report as document,radio.test_name as report_type,radiology_report.description')
            ->join('radio', 'radio.id = radiology_report.radiology_id', "inner")
            ->join('patients', 'patients.id = radiology_report.patient_id', "inner")
            ->where("radiology_report.patient_id", $id)
            ->get("radiology_report");
        $result3 = $query3->result_array();
        return array_merge($result1, $result2, $result3);
    }

    public function deleteIpdPatientDiagnosis($id)
    {
        $this->db->where('id', $id)
            ->delete('diagnosis');
        $this->db->where('id', $id)
            ->delete('pathology_report');
        $this->db->where('id', $id)
            ->delete('radiology_report');
    }

    public function add_prescription($data_array)
    {
        $this->db->insert_batch("prescription", $data_array);
    }

    public function add_ipdprescription($data_array)
    {
        $this->db->insert_batch("ipd_prescription_details", $data_array);
    }

    public function getMaxId()
    {
        $query  = $this->db->select('max(patient_unique_id) as patient_id')->get("patients");
        $result = $query->row_array();
        return $result["patient_id"];
    }

    public function getMaxOPDId()
    {
        $query  = $this->db->select('max(id) as patient_id')->get("opd_details");
        $result = $query->row_array();
        return $result["patient_id"];
    }

    public function getMaxIPDId()
    {
        $query  = $this->db->select('max(id) as ipdid')->get("ipd_details");
        $result = $query->row_array();
        return $result["ipdid"];
    }

    public function search_ipd_patients($searchterm, $active = 'yes', $discharged = 'no', $patient_id = '', $limit = "", $start = "")
    {
        $userdata = $this->customlib->getUserData();
        if ($this->session->has_userdata('hospitaladmin')) {
            $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
            if ($doctor_restriction == 'enabled') {
                if ($userdata["role_id"] == 3) {
                    $this->db->where('ipd_details.cons_doctor', $userdata['id']);
                }
            }
        }

        if (!empty($patient_id)) {
            $this->db->where("patients.id", $patient_id);
        }
        $this->db->select('patients.*,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.id as ipdid,ipd_details.credit_limit as ipdcredit_limit,ipd_details.case_type,ipd_details.ipd_no,staff.name,staff.surname
              ')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', $active);
        $this->db->where('ipd_details.discharged', $discharged);
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->or_like('patients.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('ipd_details.id', "desc");
        $query = $this->db->get();
        if (!empty($patient_id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function search_ipdpatients($patient_id)
    {
        $userdata = $this->customlib->getUserData();
        if ($this->session->has_userdata('hospitaladmin')) {
            $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
            if ($doctor_restriction == 'enabled') {
                if ($userdata["role_id"] == 3) {
                    $this->db->where('ipd_details.cons_doctor', $userdata['id']);
                }
            }
        }

        if (!empty($patient_id)) {
            $this->db->where("ipd_details.patient_id", $patient_id);
        }
        $this->db->select('ipd_details.*')->from('ipd_details');
        
        $this->db->order_by('ipd_details.id', "desc");
        $query = $this->db->get();
        if (!empty($patient_id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    // ===================== search datatable for IPD ==================================

    public function searchipd_datatable()
    {
        $userdata = $this->customlib->getUserData();
        if ($this->session->has_userdata('hospitaladmin')) {
            $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
            if ($doctor_restriction == 'enabled') {
                if ($userdata["role_id"] == 3) {
                    $this->db->where('ipd_details.cons_doctor', $userdata['id']);
                }
            }
        }

        $this->db->select("patients.*,'0' charges,'0' payment,'0' amountdue,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.id as ipdid,ipd_details.credit_limit as ipdcredit_limit,ipd_details.case_type,ipd_details.ipd_no,staff.name,staff.surname
              ")->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('ipd_details.discharged', 'no');
        if (!isset($_POST['order'])) {
            $this->db->order_by('ipd_details.id', "desc");
        }
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->columnipd_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        if (isset($_POST['order'])) {
            $this->db->order_by($this->columnipd_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        }
        $query  = $this->db->get();
        $result = $query->result();
        $i      = 0;
        foreach ($result as $key => $value) {
            $id                    = $value->id;
            $ipdid                 = $value->ipdid;
            $charges               = $this->patient_model->getCharges($id, $ipdid);
            $result[$i]->charges   = $charges['charge'];
            $payment               = $this->patient_model->getPayment($id, $ipdid);
            $result[$i]->payment   = $payment['payment'];
            $result[$i]->amountdue = $charges['charge'] - $payment['payment'];
            $i++;
        }
        return $result;

    }

    public function searchipd_datatable_count()
    {

        $this->db->select('patients.*,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.id as ipdid,ipd_details.credit_limit as ipdcredit_limit,ipd_details.case_type,ipd_details.ipd_no,staff.name,staff.surname
              ');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('ipd_details.discharged', 'no');
        $this->db->order_by('ipd_details.id', "desc");
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->column_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();
        }
        $query        = $this->db->from('patients');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function searchdischarged_datatable()
    {
        $userdata = $this->customlib->getUserData();
        if ($this->session->has_userdata('hospitaladmin')) {
            $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
            if ($doctor_restriction == 'enabled') {
                if ($userdata["role_id"] == 3) {
                    $this->db->where('ipd_details.cons_doctor', $userdata['id']);
                }
            }
        }

        $this->db->select("patients.*,'0' charges,'0' payment,'0' amountdue,'0' tax,'0' discount,'0' other_charge,'0' net_amount,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.id as ipdid,ipd_details.credit_limit as ipdcredit_limit,ipd_details.case_type,ipd_details.ipd_no,staff.name,staff.surname
              ")->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('ipd_details.discharged', 'yes');
        if (!isset($_POST['order'])) {
            $this->db->order_by('ipd_details.id', "desc");
        }
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->columndis_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        if (isset($_POST['order'])) {
            $this->db->order_by($this->columndis_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        }
        $query  = $this->db->get();
        $result = $query->result();
        $i      = 0;
        foreach ($result as $key => $value) {
            $id                    = $value->id;
            $ipdid                 = $value->ipdid;
            $discharge_details = $this->getIpdBillDetails($id, $ipdid);
            $charges               = $this->getCharges($id, $ipdid);
            $result[$i]->charges   = $charges['charge'];
            $payment               = $this->getPayment($id, $ipdid);
            $result[$i]->payment   = $payment['payment'];
            $result[$i]->discharge_date = $discharge_details['date'];
            $result[$i]->other_charge = $discharge_details['other_charge'];
            $result[$i]->tax = $discharge_details['tax'];
            $result[$i]->discount = $discharge_details['discount'];
            $result[$i]->net_amount = $discharge_details['net_amount'];
            $result[$i]->amountdue = $charges['charge'] - $payment['payment'];
            $i++;
        }
        return $result;

    }

    public function searchdischarged_datatable_count()
    {

        $this->db->select('patients.*,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.id as ipdid,ipd_details.credit_limit as ipdcredit_limit,ipd_details.case_type,ipd_details.ipd_no,staff.name,staff.surname
              ');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('ipd_details.discharged', 'yes');
        $this->db->order_by('ipd_details.id', "desc");
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->columndis_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();
        }
        $query        = $this->db->from('patients');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function patientipddetails($patient_id)
    {
        $this->db->select('patients.*,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.id as ipdid,ipd_details.case_type,ipd_details.ipd_no,staff.name,staff.surname
              ')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.id', $patient_id);
        $this->db->where('ipd_details.discharged', "yes");
        $this->db->order_by('ipd_details.id', "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_consultantInstruction($data)
    {
        $this->db->insert_batch("consultant_register", $data);
    }

    public function deleteIpdPatientConsultant($id)
    {
        $query = $this->db->where('id', $id)
            ->delete('consultant_register');
    }

    public function getPatientConsultant($id, $ipdid)
    {
        $query = $this->db->select('consultant_register.*,staff.name,staff.surname')->join('staff', 'staff.id = consultant_register.cons_doctor', "inner")->where("patient_id", $id)->where("ipd_id", $ipdid)->get("consultant_register");
        return $query->result_array();
    }

    public function ipdCharge($code, $orgid)
    {
        if (!empty($orgid)) {
            $this->db->select('charges.*,organisations_charges.id as org_charge_id, organisations_charges.org_id, organisations_charges.org_charge ');
            $this->db->join('organisations_charges', 'charges.id = organisations_charges.charge_id');
            $this->db->where('organisations_charges.org_id', $orgid);
            $this->db->where('charges.id', $code);
            $query = $this->db->get('charges');
            if ($query->num_rows() == 0) {
                $this->db->where('charges.id', $code);
                $query = $this->db->get('charges');
            }
        } else {
            $this->db->where('charges.id', $code);
            $query = $this->db->get('charges');
        }
        return $query->row_array();
    }

    public function getDataAppoint($id)
    {
        $query = $this->db->where('patients.id', $id)->get('patients');
        return $query->row_array();
    }

    public function search($id)
    {
        $this->db->select('appointment.*,specialist.specialist_name,staff.id as sid,staff.name,staff.surname,patients.id as pid,patients.patient_unique_id');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->join('patients', 'appointment.patient_id = patients.id', 'inner');
        $this->db->join('specialist', 'specialist.id = staff.specialist', 'left');
        $this->db->where('`appointment`.`doctor`=`staff`.`id`');
        $this->db->where('appointment.patient_id = patients.id');
        $this->db->where('appointment.patient_id=' . $id);
        $query = $this->db->get('appointment');
        return $query->result_array();
    }

    public function getOpdPatient($opd_ipd_no)
    {
        $query = $this->db->select('opd_details.patient_id,opd_details.opd_no,patients.id as pid,patients.patient_name,patients.age,patients.guardian_name,patients.guardian_address,patients.admission_date,patients.gender,staff.name as doctorname,staff.surname')
            ->join('patients', 'opd_details.patient_id = patients.id')
            ->join('staff', 'staff.id = opd_details.cons_doctor', "inner")
            ->where('opd_no', $opd_ipd_no)
            ->get('opd_details');
        return $query->row_array();
    }

    public function getIpdPatient($opd_ipd_no)
    {
        $query = $this->db->select('ipd_details.patient_id,ipd_details.ipd_no,patients.id as pid,patients.patient_name,patients.age,patients.guardian_name,patients.guardian_address,patients.admission_date,patients.gender,staff.name as doctorname,staff.surname')
            ->join('patients', 'ipd_details.patient_id = patients.id')
            ->join('staff', 'staff.id = ipd_details.cons_doctor', "inner")
            ->where('ipd_no', $opd_ipd_no)
            ->get('ipd_details');
        return $query->row_array();
    }

    public function getAppointmentDate()
    {
        $query = $this->db->select('opd_details.appointment_date')->get('opd_details');
    }

    public function deleteOPD($opdid)
    {
        $this->db->where("id", $opdid)->delete("opd_details");
    }

    public function deleteOPDPatient($id)
    {
        $this->db->where("patient_id", $id)->delete("opd_details");
    }

    public function deletePatient($id)
    {
        $query = $this->db->select('bed.id')
            ->join('ipd_details', 'ipd_details.bed = bed.id')
            ->where("ipd_details.patient_id", $id)->where("ipd_details.discharged", 'no')->get('bed');

        $result = $query->row_array();
        $bed_id = $result["id"];
        if ($bed_id) {
            $this->db->where("id", $bed_id)->update('bed', array('is_active' => 'yes'));
            $this->db->where("patient_id", $id)->delete("ipd_details");
        }
        $this->db->where("id", $id)->delete("patients");
    }

    public function getCharges($patient_id, $ipdid = '')
    {
        $query = $this->db->select("sum(apply_charge) as charge")->where("patient_id", $patient_id)->where("ipd_id", $ipdid)->get("patient_charges");
        return $query->row_array();
    }

    public function getOPDCharges($patient_id, $opdid = '')
    {
        $query = $this->db->select("sum(apply_charge) as charge")->where("patient_id", $patient_id)->where("opd_id", $opdid)->get("opd_patient_charges");
        return $query->row_array();
    }

    public function getOPDvisitCharges($patient_id, $opdid = '')
    {
        $query = $this->db->select("sum(amount) as vamount")->where("patient_id", $patient_id)->where("opd_id", $opdid)->get("visit_details");
        return $query->row_array();
    }

    public function getOPDbill($patient_id, $opdid = '')
    {
        $query = $this->db->select("sum(net_amount) as billamount")->where("patient_id", $patient_id)->where("opd_id", $opdid)->get("opd_billing");
        return $query->row_array();
    }

    public function getPayment($patient_id, $ipdid = '')
    {
        $query = $this->db->select("sum(paid_amount) as payment")->where("patient_id", $patient_id)->where("ipd_id", $ipdid)->get("payment");
        return $query->row_array();
    }

    public function getOthercharge($patient_id, $ipdid = '')
    {
        $query = $this->db->select("sum(other_charge) as othercharge")->where("patient_id", $patient_id)->where("ipd_id", $ipdid)->get("ipd_billing");
        return $query->row_array();
    }

    public function getopdPayment($patient_id, $opdid = '')
    {
        $query = $this->db->select("sum(paid_amount) as opdpayment")->where("patient_id", $patient_id)->where("opd_id", $opdid)->get("opd_payment");
        return $query->row_array();
    }

    public function patientCredentialReport()
    {
        $query = $this->db->select('patients.*,users.id as uid,users.user_id,users.username,users.password')
            ->join('users', 'patients.id = users.user_id')->where("users.is_active", 'yes')
            ->get('patients');
        return $query->result_array();
    }

    public function getPaymentDetail($patient_id)
    {
        $SQL   = 'select patient_charges.amount_due,payment.amount_deposit from (SELECT sum(paid_amount) as `amount_deposit` FROM `payment` WHERE patient_id=' . $this->db->escape($patient_id) . ') as payment ,(SELECT sum(apply_charge) as `amount_due` FROM `patient_charges` WHERE patient_id=' . $this->db->escape($patient_id) . ') as patient_charges';
        $query = $this->db->query($SQL);
        return $query->row();
    }

    public function getPaymentDetailpatient($ipd_id)
    {
        $SQL   = 'select patient_charges.amount_due,payment.amount_deposit from (SELECT sum(paid_amount) as `amount_deposit` FROM `payment` WHERE ipd_id=' . $this->db->escape($ipd_id) . ') as payment ,(SELECT sum(apply_charge) as `amount_due` FROM `patient_charges` WHERE ipd_id=' . $this->db->escape($ipd_id) . ') as patient_charges';
        $query = $this->db->query($SQL);
        return $query->row();
    }

    public function getOpdPaymentDetailpatient($opd_id)
    {
        $SQL   = 'select opd_patient_charges.amount_due,opd_payment.amount_deposit from (SELECT sum(paid_amount) as `amount_deposit` FROM `opd_payment` WHERE opd_id=' . $this->db->escape($opd_id) . ') as opd_payment ,(SELECT sum(apply_charge) as `amount_due` FROM `opd_patient_charges` WHERE opd_id=' . $this->db->escape($opd_id) . ') as opd_patient_charges';
        $query = $this->db->query($SQL);
        return $query->row();
    }

    public function getIpdBillDetails($id, $ipdid)
    {
        $query = $this->db->where("patient_id", $id)->where("ipd_id", $ipdid)->get("ipd_billing");
        return $query->row_array();
    }

    public function getDepositAmountBetweenDate($start_date, $end_date)
    {
        $opd_query        = $this->db->select('*')->get('opd_details');
        $bloodbank_query  = $this->db->select('*')->get('blood_issue');
        $pharmacy_query   = $this->db->select('*')->get('pharmacy_bill_basic');
        $opd_result       = $opd_query->result();
        $bloodbank_result = $bloodbank_query->result();
        $result_value     = $opd_result;
        $return_array     = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                $return = $this->findObjectById($result_value, $st_date, $ed_date);
                if (!empty($return)) {
                    foreach ($return as $r_key => $r_value) {
                        $a                    = array();
                        $a['amount']          = $r_value->amount;
                        $a['date']            = $r_value->appointment_date;
                        $a['amount_discount'] = 0;
                        $a['amount_fine']     = 0;
                        $a['description']     = '';
                        $a['payment_mode']    = $r_value->payment_mode;
                        $a['inv_no']          = $r_value->patient_id;
                        $return_array[]       = $a;
                    }
                }
            }
        }

        return $return_array;
    }

    public function findObjectById($array, $st_date, $ed_date)
    {
        $sarray = array();
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($array as $row_key => $row_value) {
                $appointment_date = date("Y-m-d", strtotime($row_value->appointment_date));
                if ($appointment_date == $find) {
                    $sarray[] = $row_value;
                }
            }
        }
        return $sarray;
    }

    public function getEarning($field, $module, $search_field = '', $search_value = '', $search = '')
    {
        $search_arr = array();
        foreach ($search as $key => $value) {
            $key              = $module . "." . $key;
            $search_arr[$key] = $value;
        }
        if ((!empty($search_field)) && (!empty($search_value))) {
            $this->db->where($search_field, $search_value);
        }
        if (!empty($search_arr)) {
            $this->db->where($search_arr);
        }
        if ($module == 'ipd_billing') {
            $this->db->join("ipd_details", "ipd_billing.ipd_id = ipd_details.id");
        }
        if ($module == 'payment') {
            $this->db->join("ipd_details", "payment.ipd_id = ipd_details.id");
        }
        if ($module == 'opd_details') {
            $this->db->join("patients", "patients.id = opd_details.patient_id");
        }
        if ($module == 'pharmacy_bill_basic') {
            $this->db->join("patients", "patients.id = pharmacy_bill_basic.patient_id");
        }
        if ($module == 'ambulance_call') {
            $this->db->join("patients", "patients.id = ambulance_call.patient_name");
        }
        $query  = $this->db->select('sum(' . $field . ') as amount')->get($module);
        $result = $query->row_array();
        return $result["amount"];
    }

    public function getPathologyEarning($search = '')
    {
        if (!empty($search)) {
            $this->db->where($search);
        }
        $query = $this->db->select('sum(pathology_report.apply_charge) as amount')
            ->join('pathology', 'pathology.charge_id = charges.id')
            ->join('pathology_report', 'pathology_report.pathology_id = pathology.id')
            ->get('charges');
        $result = $query->row_array();
        return $result["amount"];
    }

    public function getRadiologyEarning($search = '')
    {
        if (!empty($search)) {
            $this->db->where($search);
        }

        $query = $this->db->select('sum(radiology_report.apply_charge) as amount')
            ->join('radio', 'radio.charge_id = charges.id')
            ->join('radiology_report', 'radiology_report.radiology_id = radio.id')
            ->get('charges');
        $result = $query->row_array();
        return $result["amount"];
    }

    public function getOTEarning($search = '')
    {	
		$search_arr = array();
        foreach ($search as $key => $value) {
            $key              = $key;
            $search_arr[$key] = $value;
        }
        if (!empty($search_arr)) {
            $this->db->where($search_arr);
        }

        $query = $this->db->select('sum(operation_theatre.apply_charge) as amount')
            ->join('operation_theatre', 'operation_theatre.charge_id = charges.id')
            ->join('patients', 'operation_theatre.patient_id = patients.id', 'inner')
            ->where('patients.is_active', 'yes')
            ->where('year(operation_theatre.date)', '2020')
            ->get('charges');
        $result = $query->row_array();
        return $result["amount"];
    }

    public function deleteIpdPatient($id)
    {
        $query = $this->db->select('bed.id')
            ->join('ipd_details', 'ipd_details.bed = bed.id')
            ->where("ipd_details.patient_id", $id)->get('bed');

        $result = $query->row_array();
        $bed_id = $result["id"];
        $this->db->where("id", $bed_id)->update('bed', array('is_active' => 'yes'));
        $this->db->where("patient_id", $id)->delete('ipd_details');
        $this->db->where("patient_id", $id)->delete('patient_charges');
        $this->db->where("patient_id", $id)->delete('payment');
        $this->db->where("patient_id", $id)->delete('ipd_billing');
    }

    public function getIncome($date_from, $date_to)
    {
        $object  = new stdClass();
        $query1  = $this->getEarning($field = 'amount', $module = 'opd_details', $search_field = '', $search_value = '', $search = array('appointment_date >=' => $date_from, 'appointment_date <=' => $date_to));
        $amount1 = $query1;

        $query2  = $this->getEarning($field = 'paid_amount', $module = 'payment', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount2 = $query2;

        $query3  = $this->getEarning($field = 'net_amount', $module = 'pharmacy_bill_basic', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount3 = $query3;

        $query4  = $this->getEarning($field = 'amount', $module = 'blood_issue', $search_field = '', $search_value = '', $search = array('date_of_issue >=' => $date_from, 'date_of_issue <=' => $date_to . " 23:59:59.993"));
        $amount4 = $query4;

        $query5  = $this->getEarning($field = 'amount', $module = 'ambulance_call', $search_field = '', $search_value = '', $search = array('created_at >=' => $date_from, 'created_at <=' => $date_to));
        $amount5 = $query5;

        $query6  = $this->getPathologyEarning(array('pathology_report.reporting_date >=' => $date_from, 'pathology_report.reporting_date <=' => $date_to));
        $amount6 = $query6;

        $query7  = $this->getRadiologyEarning(array('radiology_report.reporting_date >=' => $date_from, 'radiology_report.reporting_date <=' => $date_to));
        $amount7 = $query7;

        $query8  = $this->getOTEarning(array('operation_theatre.date >=' => $date_from, 'operation_theatre.date <=' => $date_to));
        $amount8 = $query8;

        $query9  = $this->getEarning($field = 'amount', $module = 'income', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount9 = $query9;

        $query10  = $this->getEarning($field = 'net_amount', $module = 'ipd_billing', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount10 = $query10;

        $query11  = $this->getEarning($field = 'net_amount', $module = 'opd_billing', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount11 = $query11;

        $query12  = $this->getEarning($field = 'paid_amount', $module = 'opd_payment', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount12 = $query12;

        $amount         = $amount1 + $amount2 + $amount3 + $amount4 + $amount5 + $amount6 + $amount7 + $amount8 + $amount9 + $amount10 + $amount11 + $amount12;
        $object->amount = $amount;
        return $object;
    }

    public function getBillInfo($id)
    {
        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,ipd_billing.date as discharge_date')
            ->join('ipd_billing', 'staff.id = ipd_billing.generated_by')
            ->where('ipd_billing.patient_id', $id)
            ->get('staff');
        $result = $query->row_array();
        return $result;
    }

    public function getopdBillInfo($id, $visitid)
    {
        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,opd_billing.date as discharge_date')
            ->join('opd_billing', 'staff.id = opd_billing.generated_by')
            ->where('opd_billing.patient_id', $id)
            ->where('opd_billing.opd_id', $visitid)
            ->get('staff');
        $result = $query->row_array();
        return $result;
    }

    public function getBillstatus($id, $visitid)
    {
        $query = $this->db->select('opd_billing.*,visit_details.amount as visitamount,opd_details.amount as amount,patients.id')
            ->join('patients', 'patients.id=opd_billing.patient_id')
            ->join('opd_details', 'opd_details.patient_id = opd_billing.id', "left")
            ->join('visit_details', 'visit_details.patient_id = opd_billing.patient_id', "left")
            ->where('opd_billing.patient_id', $id)
            ->where('opd_billing.opd_id', $visitid)
            ->get('opd_billing');
        $result = $query->row_array();
        return $result;
    }

    public function getStatus($id)
    {
        $query  = $this->db->where("id", $id)->get("patients");
        $result = $query->row_array();
        return $result;
    }

    public function searchPatientNameLike($searchterm)
    {
      
        $this->db->select('patients.*')->from('patients');
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->group_end();
        $this->db->where('patients.is_active', 'yes');
        $this->db->order_by('patients.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPatientEmail()
    {
        $query = $this->db->select("patients.email,patients.id,patients.mobileno,patients.app_key")
            ->join("users", "patients.id = users.user_id")
            ->where("users.role", "patient")
            ->where("patients.is_active", "yes")
            ->get("patients");
        return $query->result_array();
    }

    public function updatebed($data)
    {
        $this->db->where('ipd_no', $data["ipd_no"])
            ->update('ipd_details', $data);
    }

    public function getVisitDetails($id, $visitid)
    {
        $query = $this->db->select('opd_details.*,staff.name,staff.surname')
            ->join('patients', 'opd_details.patient_id = patients.id')
            ->join('staff', 'opd_details.cons_doctor = staff.id')
            ->where(array('opd_details.patient_id' => $id, 'opd_details.id' => $visitid))
            ->get('opd_details');
        return $query->row_array();
    }

    public function getpatientDetailsByVisitId($id, $visitid)
    {
        $query = $this->db->select('visit_details.*,visit_details.amount as apply_charge, opd_details.id as opdid, staff.name,staff.surname,patients.age,patients.month,patients.patient_name,patients.gender,patients.email,patients.mobileno,patients.address,patients.marital_status,patients.blood_group,patients.dob,patients. patient_unique_id')
            ->join('patients', 'visit_details.patient_id = patients.id')
            ->join('opd_details', 'visit_details.opd_no = opd_details.opd_no')
            ->join('staff', 'opd_details.cons_doctor = staff.id')
            ->where(array('opd_details.patient_id' => $id, 'visit_details.id' => $visitid))
            ->get('visit_details');
        $result = $query->row_array();
        if (!empty($result)) {
            $generated_by = $result["generated_by"];
            $staff_query  = $this->db->select("staff.name,staff.surname")
                ->where("staff.id", $generated_by)
                ->get("staff");
            $staff_result               = $staff_query->row_array();
            $result["generated_byname"] = $staff_result["name"] . " " . $staff_result["surname"];
        }
        return $result;
    }

    public function addvisitDetails($opd_data)
    {
        if (isset($opd_data["id"])) {
            $this->db->where("id", $opd_data["id"])->update("visit_details", $opd_data);
        } else {
            $this->db->insert("visit_details", $opd_data);
        }
    }

    public function getVisitDetailsByOPD($id, $visitid)
    {
        $query = $this->db->select('visit_details.*,opd_details.id as opdid, staff.name,staff.surname')
            ->join('patients', 'visit_details.patient_id = patients.id')
            ->join('opd_details', 'visit_details.opd_no = opd_details.opd_no')
            ->join('staff', 'opd_details.cons_doctor = staff.id')
            ->where(array('opd_details.patient_id' => $id, 'visit_details.opd_id' => $visitid))
            ->get('visit_details');
        $result = $query->result_array();

        //print_r($result);

        $i = 0;
        foreach ($result as $key => $value) {
            $opd_id = $value["id"];
            $check  = $this->db->where("visit_id", $opd_id)->get('prescription');
            //   echo $this->db->last_query();
            // echo $check->num_rows()."iam";
            if ($check->num_rows() > 0) {
                $result[$i]['prescription'] = 'yes';
            } else {
                $result[$i]['prescription'] = 'no';
                $userdata                   = $this->customlib->getUserData();
                if ($this->session->has_userdata('hospitaladmin')) {
                    $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
                    if ($doctor_restriction == 'enabled') {
                        if ($userdata["role_id"] == 3) {
                            if ($userdata["id"] == $value["staff_id"]) {

                            } else {
                                $result[$i]['prescription'] = 'not_applicable';
                            }
                        }
                    }
                }
            }
            $i++;
        }
        return $result;
    }

    public function deleteVisit($id)
    {
        $this->db->where("id", $id)->delete("visit_details");
    }

    public function printVisitDetails($patient_id, $visitid)
    {
        $query = $this->db->select("patients.*,organisation.organisation_name,opd_details.opd_no,opd_details.amount as apply_charge, opd_details.id as opdid,opd_details.appointment_date,opd_details.symptoms,opd_details.case_type,opd_details.casualty,opd_details.note_remark,staff.name,staff.surname")
            ->join('opd_details', 'patients.id = opd_details.patient_id')
            ->join('staff', 'staff.id = opd_details.cons_doctor')
            ->join('organisation', 'organisation.id = patients.organisation', 'left')
            ->where("patients.id", $patient_id)
            ->where("opd_details.id", $visitid)
            ->get("patients");

        return $query->row_array();
    }

}
