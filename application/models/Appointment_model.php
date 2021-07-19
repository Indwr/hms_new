<?php
class Appointment_model extends CI_Model
{

    public $column_order  = array('patients.patient_name', 'appointment.appointment_no', 'appointment.date', 'patients.mobileno', 'patients.gender', 'staff.name', 'appointment.source', 'appoint_priority.appoint_priority', 'appointment.live_consult', 'appointment.appointment_status'); //set column field database for datatable orderable
    public $column_search = array('patients.patient_name', 'appointment.patient_name', 'appointment.appointment_no', 'appointment.date', 'appointment.mobileno', 'appointment.mobileno', 'patients.gender', 'staff.name', 'appointment.source', 'appointment.live_consult', 'appointment.appointment_status'); 

//========================================================================================
    public function add($appointment)
    {
        $this->db->insert('appointment', $appointment);
        return $this->db->insert_id();
    }

//=========================================================================================
    public function searchFullText()
    {
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $userdata           = $this->customlib->getUserData();
        $role_id            = $userdata['role_id'];
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $user_id  = $userdata["id"];
                $doctorid = $user_id;
                $this->db->where('appointment.doctor', $user_id);
            }
        }
        $this->db->select('appointment.*,staff.name, IFNULL(patients.patient_name, appointment.patient_name) as patient_name,IFNULL(patients.gender, appointment.gender) as gender, IFNULL(patients.email, appointment.email) as email, IFNULL(patients.mobileno, appointment.mobileno) as mobileno,staff.surname');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->join('patients', 'appointment.patient_id = patients.id', "left");
        $this->db->where('`appointment`.`doctor`=`staff`.`id`');
        $this->db->order_by('`appointment`.`date`', 'desc');
        $query = $this->db->get('appointment');
        return $query->result_array();
    }

    public function search_datatable()
    {
        $userdata           = $this->customlib->getUserData();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        if ($doctor_restriction == 'enabled') {
            if ($userdata["role_id"] == 3) {
                $this->db->where('appointment.doctor', $userdata['id']);
            }
        }
        $this->db->select('appointment.*,staff.name, IFNULL(patients.patient_name, appointment.patient_name) as patient_name,IFNULL(patients.gender, appointment.gender) as gender, IFNULL(patients.email, appointment.email) as email, IFNULL(patients.mobileno, appointment.mobileno) as mobileno,staff.surname,appoint_priority.appoint_priority as priorityname');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->join('patients', 'appointment.patient_id = patients.id', "left");
        $this->db->join('appoint_priority', 'appoint_priority.id = appointment.priority', "left");
        if (!isset($_POST['order'])) {
            $this->db->order_by('`appointment`.`date`', 'desc');
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
        $query = $this->db->get('appointment');
        return $query->result();
    }

    public function search_datatable_count()
    {
        $userdata           = $this->customlib->getUserData();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        if ($doctor_restriction == 'enabled') {
            if ($userdata["role_id"] == 3) {
                $this->db->where('appointment.doctor', $userdata['id']);
            }
        }
        $this->db->select('appointment.*,staff.name, IFNULL(patients.patient_name, appointment.patient_name) as patient_name,IFNULL(patients.gender, appointment.gender) as gender, IFNULL(patients.email, appointment.email) as email, IFNULL(patients.mobileno, appointment.mobileno) as mobileno,staff.surname');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->join('patients', 'appointment.patient_id = patients.id', "left");
        $this->db->where('patients.is_active', 'yes');
        $this->db->order_by('`appointment`.`date`', 'desc');

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

        $query        = $this->db->from('appointment');
        $total_result = $query->count_all_results();
        return $total_result;

    }
//==========================================================================================

    public function getMaxId()
    {
        $query  = $this->db->select('max(id) as maxid')->get("`appointment`");
        $result = $query->row_array();
        return $result["maxid"];
    }

//==========================================================================================
    public function getDetails($id)
    {
        $this->db->select('appointment.*,staff.name,staff.surname,IFNULL(patients.patient_name, appointment.patient_name) as patient_name,IFNULL(patients.gender, appointment.gender) as gender, IFNULL(patients.email, appointment.email) as email, IFNULL(patients.mobileno, appointment.mobileno) as mobileno,appoint_priority.appoint_priority');
        $this->db->join('staff', 'appointment.doctor = staff.id', "left");
        $this->db->join('patients', 'appointment.patient_id = patients.id', "left");
        $this->db->join('appoint_priority', 'appoint_priority.id = appointment.priority', "left");
        $this->db->where('appointment.id', $id);
        $query = $this->db->get('appointment');
        return $query->row_array();
    }

    public function getDetailsFornotification($id)
    {
        $this->db->select('appointment.*,staff.name as staff_name,staff.surname as staff_surname,IFNULL(patients.patient_name, appointment.patient_name) as patient_name,IFNULL(patients.gender, appointment.gender) as gender, IFNULL(patients.email, appointment.email) as email, IFNULL(patients.mobileno, appointment.mobileno) as mobileno,appoint_priority.appoint_priority');
        $this->db->join('staff', 'appointment.doctor = staff.id', "left");
        $this->db->join('patients', 'appointment.patient_id = patients.id', "left");
        $this->db->join('appoint_priority', 'appoint_priority.id = appointment.priority', "left");
        $this->db->where('appointment.id', $id);
        $query = $this->db->get('appointment');
        return $query->row_array();
    }

    public function getDetailsAppointment($id)
    {
        $this->db->select('appointment.*,appoint_priority.appoint_priority,staff.name,staff.surname,');
        $this->db->join('staff', 'appointment.doctor = staff.id', "left");
        $this->db->join('appoint_priority', 'appoint_priority.id = appointment.priority', "left");
        $this->db->where('appointment.id', $id);
        $query = $this->db->get('appointment');
        return $query->row_array();
    }

//=========================================================================================
    public function update($appointment)
    {
        $query = $this->db->where('id', $appointment['id'])
            ->update('appointment', $appointment);
    }

//=========================================================================================
    public function delete($id)
    {
        $this->db->where("id", $id)->delete('appointment');
    }

//=========================================================================================
    public function getAppointment($id = null)
    {
        $query = $this->db->order_by('id', 'desc')->get('appointment');
        return $query->result_array();
    }

//=========================================================================================
    public function status($id, $data)
    {
        $this->db->where("id", $id)->update("appointment", $data);
    }

    public function move($id, $data)
    {
        $this->db->where("id", $id)->update("appointment", $data);
    }

    public function getpatientDetails($id)
    {
        $query = $this->db->select('patients.*')
            ->where('patients.patient_unique_id', $id)
            ->get('patients');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

}
