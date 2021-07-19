<?php

class Pathology_model extends CI_Model
{

    public $column_order  = array('test_name', 'short_name', 'test_type', 'category_name', 'sub_category', 'method', 'report_days', 'charges.standard_charge'); //set column field database for datatable orderable
    public $column_search = array('test_name', 'short_name', 'test_type', 'category_name', 'sub_category', 'method', 'report_days', 'charges.standard_charge');
    public $columnreport_order = array('pathology_report.bill_no','pathology_report.reporting_date','patients.patient_name','pathology.test_name','pathology.short_name','staff.name','description','pathology_report.apply_charge'); //set column field database for datatable orderable
    public $columnreport_search = array('pathology_report.bill_no','pathology_report.reporting_date','patients.patient_name','pathology.test_name','pathology.short_name','staff.name','pathology_report.description','pathology_report.apply_charge');
    public function add($pathology)
    {
        $this->db->insert('pathology', $pathology);
        return $this->db->insert_id();
    }

    public function addparameter($data)
    {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('pathology_parameterdetails', $data);
        } else {
            $this->db->insert_batch('pathology_parameterdetails', $data);
            return $this->db->insert_id();
        }

    }

    public function delete_parameter($delete_arr)
    {
        foreach ($delete_arr as $key => $value) {
            $id = $value["id"];
            $this->db->where("id", $value["id"])->delete("pathology_parameterdetails");
        }
    }

    public function updateparameter($condition)
    {
        $SQL = "INSERT INTO pathology_parameterdetails
                    (parameter_id, id)
                    VALUES
                    " . $condition . "
                    ON DUPLICATE KEY UPDATE
                    parameter_id=VALUES(parameter_id)";
        $query = $this->db->query($SQL);
    }

    public function getparameter($id)
    {
        $this->db->select('pathology_parameterdetails.*');
        $this->db->where('pathology_parameterdetails.id', $id);
        $query = $this->db->get('pathology_parameterdetails');
        return $query->row_array();
    }
    public function searchFullText($where = '', $rowperpage = '', $row = '')
    {
        $this->db->select('pathology.*,pathology_category.id as category_id,pathology_category.category_name,charges.standard_charge');
        $this->db->join('pathology_category', 'pathology.pathology_category_id = pathology_category.id', 'left');
        $this->db->join('charges', 'pathology.charge_id = charges.id', 'left');
        $this->db->where('`pathology`.`pathology_category_id`= `pathology_category`.`id`');
        $this->db->order_by('pathology_category.id', 'desc');
        $query = $this->db->get('pathology');
        return $query->result_array();
    }

    public function search_datatable()
    {
        $this->db->select('pathology.*,pathology_category.id as category_id,pathology_category.category_name,charges.standard_charge');
        $this->db->join('pathology_category', 'pathology.pathology_category_id = pathology_category.id', 'left');
        $this->db->join('charges', 'pathology.charge_id = charges.id', 'left');
        $this->db->where('`pathology`.`pathology_category_id`= `pathology_category`.`id`');
        if (!isset($_POST['order'])) {
            $this->db->order_by('pathology_category.id', 'desc');
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
        $query = $this->db->get('pathology');
        return $query->result();
    }

    public function search_datatable_count()
    {
        $this->db->select('pathology.*,pathology_category.id as category_id,pathology_category.category_name,charges.standard_charge');
        $this->db->join('pathology_category', 'pathology.pathology_category_id = pathology_category.id', 'left');
        $this->db->join('charges', 'pathology.charge_id = charges.id', 'left');
        $this->db->where('`pathology`.`pathology_category_id`= `pathology_category`.`id`');
        $this->db->order_by('pathology_category.id', 'desc');
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
        $query        = $this->db->from('pathology');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function getDetails($id)
    {
        $this->db->select('pathology.*,pathology_category.id as category_id,pathology_category.category_name,pathology_parameter.parameter_name, charges.id as charge_id, charges.code, charges.charge_category, charges.standard_charge, charges.description');
        $this->db->join('pathology_category', 'pathology.pathology_category_id = pathology_category.id', 'left');
        $this->db->join('charges', 'pathology.charge_id = charges.id', 'left');
        $this->db->join('pathology_parameter', 'pathology_parameter.id = pathology.pathology_parameter_id', 'left');
        $this->db->where('pathology.id', $id);
        $this->db->order_by('pathology.id', 'desc');
        $query = $this->db->get('pathology');
        return $query->row_array();
    }

    public function update($pathology)
    {
        $query = $this->db->where('id', $pathology['id'])
            ->update('pathology', $pathology);
    }

    public function getBillDetails($id)
    {
        $this->db->select('pathology_report.*,pathology.test_name,pathology.short_name,pathology.report_days,patients.patient_name,staff.name as doctorname,staff.surname as doctorsurname');
        $this->db->where('pathology_report.id', $id);
        $this->db->join('pathology', 'pathology.id = pathology_report.pathology_id');
        $this->db->join('patients', 'patients.id = pathology_report.patient_id');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', 'left');
        $query        = $this->db->get('pathology_report');
        $result       = $query->row_array();
        $generated_by = $result["generated_by"];
        $staff_query  = $this->db->select("staff.name,staff.surname,,staff.employee_id")
            ->where("staff.id", $generated_by)
            ->get("staff");
        $staff_result = $staff_query->row_array();
        if ($staff_result["employee_id"]) {
            $result["generated_byname"] = $staff_result["name"] . " " . $staff_result["surname"] . "(" . $staff_result["employee_id"] . ")";
        } else {
            $result["generated_byname"] = $staff_result["name"] . " " . $staff_result["surname"];
        }
        return $result;
    }

    public function getMaxId()
    {
        $query  = $this->db->select('max(id) as bill_no')->get("pathology_report");
        $result = $query->row_array();
        return $result["bill_no"];
    }

    public function getAllBillDetails($id)
    {
        $query = $this->db->select('pathology_report.*,pathology.test_name,pathology.short_name,pathology.report_days,pathology.charge_id')
            ->join('pathology', 'pathology.id = pathology_report.pathology_id')
            ->where('pathology_report.id', $id)
            ->get('pathology_report');
        return $query->result_array();
    }

    public function delete($id)
    {
        $this->db->where("id", $id)->delete('pathology');
        $this->db->where("pathology_id", $id)->delete('pathology_parameterdetails');
    }

    public function getPathology($id = null)
    {
        if (!empty($id)) {
            $this->db->where("pathology.id", $id);
        }
        $query = $this->db->select('pathology.*,charges.charge_category,charges.code,charges.standard_charge')->join('charges', 'pathology.charge_id = charges.id')->get('pathology');
        if (!empty($id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getPathologyReport($id)
    {
        $query = $this->db->select('pathology_report.*,pathology.id as pid,pathology.charge_id as cid,staff.name,staff.surname,charges.charge_category,charges.code,charges.standard_charge,patients.patient_name')
            ->join('patients', 'pathology_report.patient_id = patients.id')
            ->join('pathology', 'pathology_report.pathology_id = pathology.id')
            ->join('charges', 'pathology.charge_id = charges.id')
            ->join('staff', 'staff.id = pathology_report.consultant_doctor', "left")
            ->where("pathology_report.id", $id)
            ->get('pathology_report');
        return $query->row_array();
    }

    public function getPathologyparameterReport($id)
    {
        $query = $this->db->select('pathology_report.*,pathology.id as pid,pathology.charge_id as cid,staff.name,staff.surname,charges.charge_category,charges.code,charges.standard_charge,patients.patient_name')
            ->join('patients', 'pathology_report.patient_id = patients.id')
            ->join('pathology', 'pathology_report.pathology_id = pathology.id')
            ->join('charges', 'pathology.charge_id = charges.id')
            ->join('staff', 'staff.id = pathology_report.consultant_doctor', "left")
            ->where("pathology_report.id", $id)
            ->get('pathology_report');
        return $query->row_array();
    }

    public function testReportBatch($report_batch)
    {
        if (isset($report_batch["id"])) {
            $this->db->where("id", $report_batch["id"])->update('pathology_report', $report_batch);
        } else {
            $this->db->insert('pathology_report', $report_batch);
            return $this->db->insert_id();
        }
    }

    public function addparametervalue($parametervalue)
    {
        if (isset($parametervalue["id"])) {
            $this->db->where("id", $parametervalue["id"])->update('pathology_report_parameterdetails', $parametervalue);
        } else {
            $this->db->insert('pathology_parameterdetails', $parametervalue);

        }
    }

    public function updateTestReport($report_batch)
    {
        $this->db->where('id', $report_batch['id'])->update('pathology_report', $report_batch);
    }

    public function getTestReportBatch($pathology_id)
    {
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option     = false;
        $userdata           = $this->customlib->getUserData();
        $role_id            = $userdata['role_id'];
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $doctorid = $userdata['id'];
                $this->db->where("pathology_report.consultant_doctor", $doctorid);
            }}

        $this->db->select('pathology_report.*, pathology.id as pid,pathology.test_name,pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->join('patients', 'patients.id = pathology_report.patient_id');
        $this->db->where("patients.is_active", "yes");
        $this->db->order_by('pathology_report.id', 'desc');
        $query  = $this->db->get('pathology_report');
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
     public function searchreport_datatable() {
        $this->db->select('pathology_report.*, pathology.id as pid,pathology.test_name,pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->join('patients', 'patients.id = pathology_report.patient_id');
        $this->db->where("patients.is_active", "yes");      
         if(!isset($_POST['order'])){
          $this->db->order_by('pathology_report.id', 'desc');
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
        $query = $this->db->get('pathology_report');
        return $query->result();
    }

    public function searchreport_datatable_count() {        
         $this->db->select('pathology_report.*, pathology.id as pid,pathology.test_name,pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->join('patients', 'patients.id = pathology_report.patient_id');
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
        $query = $this->db->from('pathology_report');
        $total_result= $query->count_all_results();
        return $total_result;
    }

    public function getTestReportBatchPatho($patient_id)
    {
        $this->db->select('pathology_report.*, pathology.id as pid,pathology.test_name,pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge,patients.patient_name');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->join('patients', 'patients.id = pathology_report.patient_id');
        $this->db->where('patient_id', $patient_id);
        $this->db->order_by('pathology_report.id', 'desc');
        $query = $this->db->get('pathology_report');
        return $query->result();
    }

    public function deleteTestReport($id)
    {
        $query = $this->db->where('id', $id)
            ->delete('pathology_report');
    }

    public function getChargeCategory()
    {
        $query = $this->db->select('charge_categories.*')
            ->where('charge_type', 'investigations')
            ->get('charge_categories');
        return $query->result_array();
    }

    public function pathologyReport()
    {
        $this->db->select('pathology_report.*, pathology.id, pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "inner");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $query = $this->db->get('pathology_report');
        return $query->result_array();
    }

    public function searchPathologyReport($date_from, $date_to)
    {
        $this->db->select('pathology_report.*, pathology.id, pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "inner");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->where('pathology_report.reporting_date >=', $date_from);
        $this->db->where('pathology_report.reporting_date <=', $date_to);
        $query = $this->db->get("pathology_report");
        return $query->result_array();
    }

    public function getparameterBypathology($pathology_id)
    {
        $query = $this->db->select('pathology_parameterdetails.parameter_id')
            ->where('pathology_id', $pathology_id)
            ->get('pathology_parameterdetails');

        return $query->result_array();
    }

    public function addParameterforPatient($data)
    {
        $this->db->insert("pathology_report_parameterdetails", $data);
    }
}
