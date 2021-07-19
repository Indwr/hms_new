<?php

class Radio_model extends CI_Model
{

    public $column_order  = array('test_name', 'short_name', 'test_type', 'lab_name', 'sub_category', 'report_days', 'charges.standard_charge'); //set column field database for datatable orderable
    public $column_search = array('test_name', 'short_name', 'test_type', 'lab_name', 'sub_category', 'report_days', 'charges.standard_charge');
    public $columnreport_order = array('radiology_report.bill_no','radiology_report.reporting_date','patients.patient_name','radio.test_name','radio.short_name','staff.name','description','radiology_report.apply_charge'); //set column field database for datatable orderable
    public $columnreport_search = array('radiology_report.bill_no','radiology_report.reporting_date','patients.patient_name','radio.test_name','radio.short_name','staff.name');

    public function add($radiology)
    {
        $this->db->insert('radio', $radiology);
        return $this->db->insert_id();
    }

    public function searchFullText()
    {
        $this->db->select('radio.*,lab.id as category_id,lab.lab_name,charges.standard_charge');
        $this->db->join('lab', 'radio.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'radio.charge_id = charges.id', 'left');
        $this->db->where('`radio`.`radiology_category_id`=`lab`.`id`');
        $this->db->order_by('lab.id', 'desc');
        $query = $this->db->get('radio');
        return $query->result_array();
    }

    public function search_datatable()
    {
        $this->db->select('radio.*,lab.id as category_id,lab.lab_name,charges.standard_charge');
        $this->db->join('lab', 'radio.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'radio.charge_id = charges.id', 'left');
        $this->db->where('`radio`.`radiology_category_id`=`lab`.`id`');
        if (!isset($_POST['order'])) {
            $this->db->order_by('lab.id', 'desc');
        }
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
        $query = $this->db->get('radio');
        return $query->result();
    }

    public function search_datatable_count()
    {
        $this->db->select('radio.*,lab.id as category_id,lab.lab_name,charges.standard_charge');
        $this->db->join('lab', 'radio.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'radio.charge_id = charges.id', 'left');
        $this->db->where('`radio`.`radiology_category_id`=`lab`.`id`');
        $this->db->order_by('lab.id', 'desc');
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
        $query        = $this->db->from('radio');
        $total_result = $query->count_all_results();
        return $total_result;
    }

     public function searchreport_datatable()
    {    
        $this->db->select('radiology_report.*, radio.id as rid,radio.test_name, radio.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('radio', 'radiology_report.radiology_id = radio.id', 'inner');
        $this->db->join('staff', 'staff.id = radiology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = radio.charge_id');
        $this->db->join('patients', 'patients.id = radiology_report.patient_id');
        $this->db->where("patients.is_active", "yes");
         if(!isset($_POST['order'])){
         $this->db->order_by('radiology_report.id', 'desc');
         }
        if(!empty($_POST['search']['value']) ) {   // if there is a search parameter
            $counter=true;
            $this->db->group_start();
  
         foreach ($this->columnreport_search as $colomn_key => $colomn_value) {
         if($counter){
              $this->db->like($colomn_value, $_POST['search']['value']);      
              $counter=false;
         }
              $this->db->or_like($colomn_value, $_POST['search']['value']);
        }
        $this->db->group_end();           
        }
        $this->db->limit($_POST['length'],$_POST['start']);
        if(isset($_POST['order'])){
        $this->db->order_by($this->columnreport_order[$_POST['order'][0]['column']],$_POST['order'][0]['dir']);
        }
        $query = $this->db->get('radiology_report');
        return $query->result();
    }

   public function searchreport_datatable_count() {        
       $this->db->select('radiology_report.*, radio.id as rid,radio.test_name, radio.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('radio', 'radiology_report.radiology_id = radio.id', 'inner');
        $this->db->join('staff', 'staff.id = radiology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = radio.charge_id');
        $this->db->join('patients', 'patients.id = radiology_report.patient_id');
        $this->db->where("patients.is_active", "yes");
        if(!empty($_POST['search']['value']) ) {   // if there is a search parameter
            $counter=true;
            $this->db->group_start();  
         foreach ($this->columnreport_search as $colomn_key => $colomn_value) {
         if($counter){
              $this->db->like($colomn_value, $_POST['search']['value']);      
              $counter=false;
         }
              $this->db->or_like($colomn_value, $_POST['search']['value']);
        }
        $this->db->group_end();           
        }
        $query = $this->db->from('radiology_report');
        $total_result= $query->count_all_results();
        return $total_result;
    }

    public function addparameter($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('radiology_parameterdetails', $data);
        } else {
            $this->db->insert_batch('radiology_parameterdetails', $data);
            return $this->db->insert_id();
        }
    }

    public function delete_parameter($delete_arr)
    {
        foreach ($delete_arr as $key => $value) {
            $id = $value["id"];
            $this->db->where("id", $value["id"])->delete("radiology_parameterdetails");
        }
    }

    public function getpathoparameter($id = null)
    {
        if (!empty($id)) {
            $this->db->select('radiolog_parameter.*,unit.unit_name');
            $this->db->from('radiolog_parameter');
            $this->db->join('unit', 'radiolog_parameter.unit = unit.id', 'left');
            $this->db->where("radiolog_parameter.id", $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('radiology_parameter.*,unit.unit_name');
            $this->db->from('radiology_parameter');
            $this->db->join('unit', 'radiology_parameter.unit = unit.id', 'left');
            $this->db->join('radio', 'radiology_parameter.id = radio.radiology_parameter_id', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getparameterDetails($id)
    {
        $query = $this->db->select('radiology_parameterdetails.*,radiology_parameter.parameter_name,radiology_parameter.reference_range,radiology_parameter.unit,unit.unit_name')
            ->join('radiology_parameter', 'radiology_parameter.id = radiology_parameterdetails.parameter_id')
            ->join('unit', 'unit.id = radiology_parameter.unit')
            ->where('radiology_parameterdetails.radiology_id', $id)
            ->get('radiology_parameterdetails');
        return $query->result_array();
    }

    public function getparameterDetailsforpatient($report_id)
    {
        $query = $this->db->select('radiology_report_parameterdetails.*,radiology_parameter.parameter_name,radiology_parameter.reference_range,radiology_parameter.unit,unit.unit_name')
            ->join('radiology_parameter', 'radiology_parameter.id = radiology_report_parameterdetails.parameter_id')
            ->join('unit', 'unit.id = radiology_parameter.unit')
            ->where("radiology_report_parameterdetails.radiology_report_id", $report_id)
            ->get('radiology_report_parameterdetails');
        return $query->result_array();
        echo $this->db->last_query();
    }

    public function getDetails($id)
    {
        $this->db->select('radio.*,lab.id as category_id,lab.lab_name, charges.id as charge_id, charges.code, charges.charge_category, charges.standard_charge, charges.description');
        $this->db->join('lab', 'radio.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'radio.charge_id = charges.id', 'left');
        $this->db->where('radio.id', $id);
        $query = $this->db->get('radio');
        return $query->row_array();
    }

    public function update($radiology)
    {
        $query = $this->db->where('id', $radiology['id'])
            ->update('radio', $radiology);
    }

    public function delete($id)
    {
        $this->db->where("id", $id)->delete('radio');
    }

    public function getRadiology($id = null)
    {
        if (!empty($id)) {
            $this->db->where("radio.id", $id);
        }
        $query = $this->db->select('radio.*,charges.charge_category,charges.code,charges.standard_charge')->join('charges', 'radio.charge_id = charges.id')->order_by('radio.id', 'desc')->get('radio');
        if (!empty($id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getBillDetails($id)
    {
        $this->db->select('radiology_report.*,radio.test_name,radio.short_name,radio.report_days,patients.patient_name,staff.name as doctorname,staff.surname as doctorsurname');
        $this->db->where('radiology_report.id', $id);
        $this->db->join('radio', 'radio.id = radiology_report.radiology_id');
        $this->db->join('patients', 'patients.id = radiology_report.patient_id');
        $this->db->join('staff', 'staff.id = radiology_report.consultant_doctor', 'left');
        $query        = $this->db->get('radiology_report');
        $result       = $query->row_array();
        $generated_by = $result["generated_by"];
        $staff_query  = $this->db->select("staff.name,staff.surname")
            ->where("staff.id", $generated_by)
            ->get("staff");
        $staff_result               = $staff_query->row_array();
        $result["generated_byname"] = $staff_result["name"] . $staff_result["surname"];
        return $result;
    }

    public function updateparameter($condition)
    {
        $SQL = "INSERT INTO radiology_parameterdetails
                    (parameter_id, id)
                    VALUES
                    " . $condition . "
                    ON DUPLICATE KEY UPDATE
                    parameter_id=VALUES(parameter_id)";
        $query = $this->db->query($SQL);
    }

    public function getMaxId()
    {
        $query  = $this->db->select('max(id) as bill_no')->get("radiology_report");
        $result = $query->row_array();
        return $result["bill_no"];
    }

    public function getAllBillDetails($id)
    {
        $query = $this->db->select('radiology_report.*,radio.test_name,radio.short_name,radio.report_days,radio.charge_id')
            ->join('radio', 'radio.id = radiology_report.radiology_id')
            ->where('radiology_report.id', $id)
            ->get('radiology_report');
        return $query->result_array();
    }

    public function testReportBatch($report_batch)
    {
        if (isset($report_batch["id"])) {
            $this->db->where("id", $report_batch["id"])->update('radiology_report', $report_batch);
        } else {
            $this->db->insert('radiology_report', $report_batch);
            return $this->db->insert_id();
        }
    }

    public function getRadiologyReport($id)
    {
        $query = $this->db->select('radiology_report.*,radio.id as pid,radio.charge_id as cid,staff.name,staff.surname,charges.charge_category,charges.code,charges.standard_charge')
            ->join('radio', 'radiology_report.radiology_id = radio.id')
            ->join('charges', 'radio.charge_id = charges.id')
            ->join('staff', 'staff.id = radiology_report.consultant_doctor', "left")
            ->where("radiology_report.id", $id)
            ->get('radiology_report');
        return $query->row_array();
    }

    public function updateTestReport($report_batch)
    {
        $this->db->where('id', $report_batch['id'])->update('radiology_report', $report_batch);
    }

    public function addparametervalue($parametervalue)
    {
        if (isset($parametervalue["id"])) {
            $this->db->where("id", $parametervalue["id"])->update('radiology_report_parameterdetails', $parametervalue);
        } else {
            $this->db->insert('radiology_parameterdetails', $parametervalue);
        }
    }

    public function getTestReportBatch($radiology_id)
    {
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option     = false;
        $userdata           = $this->customlib->getUserData();
        $role_id            = $userdata['role_id'];
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $doctorid = $userdata['id'];
                $this->db->where("radiology_report.consultant_doctor", $doctorid);
            }}

        $this->db->select('radiology_report.*, radio.id as rid,radio.test_name, radio.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('radio', 'radiology_report.radiology_id = radio.id', 'inner');
        $this->db->join('staff', 'staff.id = radiology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = radio.charge_id');
        $this->db->join('patients', 'patients.id = radiology_report.patient_id');
        $this->db->where("patients.is_active", "yes");
        $this->db->order_by('radiology_report.id', 'desc');
        $query  = $this->db->get('radiology_report');
        $result = $query->result();
        foreach ($result as $key => $value) {
            $generated_by = $value->generated_by;
            $staff_query  = $this->db->select("staff.name,staff.surname")
                ->where("staff.id", $generated_by)
                ->get("staff");
            $staff_result                   = $staff_query->row_array();
            $result[$key]->generated_byname = $staff_result["name"] . $staff_result["surname"];
        }
        return $result;
    }

    public function getTestReportBatchRadio($patient_id)
    {
        $this->db->select('radiology_report.*, radio.id as rid,radio.test_name, radio.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('radio', 'radiology_report.radiology_id = radio.id', 'inner');
        $this->db->join('staff', 'staff.id = radiology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = radio.charge_id');
        $this->db->join('patients', 'patients.id = radiology_report.patient_id');
        $this->db->where('patient_id', $patient_id);
        $this->db->order_by('radio.id', 'desc');
        $query = $this->db->get('radiology_report');
        return $query->result();
    }

    public function deleteTestReport($id)
    {
        $query = $this->db->where('id', $id)
            ->delete('radiology_report');
    }

    public function getChargeCategory()
    {
        $query = $this->db->select('charge_categories.*')
            ->where('charge_type', 'investigations')
            ->get('charge_categories');
        return $query->result_array();
    }

    public function getparameterBypathology($id)
    {
        $query = $this->db->select('radiology_parameterdetails.parameter_id')
            ->where('radiology_id', $id)
            ->get('radiology_parameterdetails');
        return $query->result_array();
    }

    public function addParameterforPatient($data)
    {
        $this->db->insert("radiology_report_parameterdetails", $data);
    }
}
