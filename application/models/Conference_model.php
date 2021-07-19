<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Conference_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->insert('conferences', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function addmeeting($data, $staff)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('conferences', $data);
        $insert_id = $this->db->insert_id();
        if (!empty($staff)) {
            $staff_list = array();
            foreach ($staff as $staff_key => $staff_value) {
                $staff_list[] = array('conference_id' => $insert_id, 'staff_id' => $staff_value);
            }
            $this->db->insert_batch('conference_staff', $staff_list);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getdata($id = null)
    {
        $this->db->select('conferences.*,create_by.name as create_by_name,create_by.surname as create_by_surname,for_create.name as create_for_name,for_create.surname as create_for_surname,create_by_role.name as create_by_role_name')->from('conferences');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as create_by_role', 'create_by_role.id = staff_create_by_roles.role_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.created_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as for_create_role', 'for_create_role.id = staff_roles.role_id');
        if ($id != null) {
            $this->db->where('conferences.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getconference($id = null)
    {
        $this->db->select('conferences.*')->from('conferences');
        if ($id != null) {
            $this->db->where('conferences.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function get($id = null)
    {
        $this->db->select('conferences.*,for_create.name as create_for_name,for_create.surname as create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname')->from('conferences');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as for_create_role', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as create_by_role', 'create_by_role.id = staff_create_by_roles.role_id');
        if ($id != null) {
            $this->db->where('conferences.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getdelete($id = null)
    {
        $this->db->select('conferences.*,')->from('conferences');
        if ($id != null) {
            $this->db->where('conferences.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getByconsult($staff_id = null)
    {
        $this->db->select('conferences.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,for_create.name as create_for_name,for_create.surname as create_for_surname,for_create.employee_id as create_for_employee_id,for_create_role.name as create_for_role_name,create_by.name as create_by_name,create_by.surname as create_by_surname,create_by.employee_id as create_by_employee_id,create_by_role.name as create_by_role_name')->from('conferences');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as for_create_role', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as create_by_role', 'create_by_role.id = staff_create_by_roles.role_id');
        if ($staff_id != "") {
            $this->db->where('conferences.staff_id', $staff_id);
        }
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getconfrencebyipd($staff_id = null, $patient_id = null, $ipdid = null)
    {
        $this->db->select('conferences.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,ipd_details.id as ipdid,ipd_details.ipd_no,for_create.name as create_for_name,for_create.surname as create_for_surname,for_create.employee_id as create_for_employee_id,for_create_role.name as create_for_role_name,create_by.name as create_by_name,create_by.surname as create_by_surname,create_by.employee_id as create_by_employee_id,create_by_role.name as create_by_role_name')->from('conferences');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('ipd_details', 'conferences.ipd_id = ipd_details.id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as for_create_role', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as create_by_role', 'create_by_role.id = staff_create_by_roles.role_id');
        $this->db->where('conferences.patient_id', $patient_id);
        if ($ipdid != "") {
            $this->db->where('conferences.ipd_id', $ipdid);
        }
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getconfrencebyopd($staff_id = null, $patient_id = null, $opdid = null)
    {
        $this->db->select('conferences.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,opd_details.id as opdid,opd_details.opd_no,for_create.name as create_for_name,for_create.surname as create_for_surname,for_create.employee_id as create_for_employee_id,for_create_role.name as create_for_role_name,create_by.name as create_by_name,create_by.surname as create_by_surname,create_by.employee_id as create_by_employee_id,create_by_role.name as create_by_role_name')->from('conferences');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('opd_details', 'conferences.opd_id = opd_details.id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as for_create_role', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as create_by_role', 'create_by_role.id = staff_create_by_roles.role_id');
        $this->db->where('conferences.patient_id', $patient_id);
        if ($opdid != "") {
            $this->db->where('conferences.opd_id', $opdid);
        }
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getconfrencebypatient($patient_id = null)
    {
        $this->db->select('conferences.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,for_create.name as create_for_name,for_create.surname as create_for_surname,for_create.employee_id as create_for_employee_id,for_create_role.name as create_for_role_name,create_by.name as create_by_name,create_by.surname as create_by_surname,create_by.employee_id as create_by_employee_id,create_by_role.name as create_by_role_name')->from('conferences');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as for_create_role', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as create_by_role', 'create_by_role.id = staff_create_by_roles.role_id');
        $this->db->where('conferences.patient_id', $patient_id);
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        //$this->db->order_by('conferences.date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getconfrencebyvisitopd($staff_id = null, $patient_id = null, $opdid = null)
    {
        $this->db->select('conferences.*,patients.id as pid,patients.patient_name,patients.patient_unique_id,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,for_create.employee_id as `for_create_employee_id`,for_create_role.name as `for_create_role_name`,')->from('conferences');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles', 'staff_roles.id = for_create.id');
        $this->db->join('roles as `for_create_role`', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.id = create_by.id');
        $this->db->join('roles as `create_by_role`', 'create_by_role.id = staff_create_by_roles.role_id');
        $this->db->where('conferences.patient_id', $patient_id);
        if ($opdid != "") {
            $this->db->where('conferences.opd_id', $opdid);
        }
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        //$this->db->order_by('conferences.date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getByStaff($staff_id = null)
    {
        $this->db->select('conferences.*,patients.id,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,for_create.employee_id as `for_create_employee_id`,for_create_role.name as `for_create_role_name`,')->from('conferences');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles', 'staff_roles.id = for_create.id');
        $this->db->join('roles as `for_create_role`', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.id = create_by.id');
        $this->db->join('roles as `create_by_role`', 'create_by_role.id = staff_create_by_roles.role_id');
        if ($staff_id != "") {
            $this->db->where('conferences.staff_id', $staff_id);
        }
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        // $this->db->order_by('conferences.date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getStaffMeeting($staff_id = null, $type = 'meeting')
    {
        if ($staff_id != "") {
            $sql   = "SELECT `conferences`.*, `for_create`.`surname` as `create_for_surname`, `create_by`.`name` as `create_by_name`, `create_by`.`surname` as `create_by_surname` , `create_by_role`.`name` as `create_by_role_name`,`create_by`.`employee_id` as `create_by_employee_id` FROM `conferences` LEFT JOIN `staff` as `for_create` ON `for_create`.`id` = `conferences`.`staff_id` JOIN `staff` as `create_by` ON `create_by`.`id` = `conferences`.`created_id`  JOIN `staff_roles` ON `staff_roles`.`staff_id` = `create_by`.`id` JOIN `roles` as `create_by_role` ON `create_by_role`.`id` = `staff_roles`.`role_id` WHERE `conferences`.`id` in (SELECT `conferences`.`id` FROM `conferences` WHERE `conferences`.`purpose`='" . $type . "' and created_id= " . $staff_id . " UNION SELECT `conferences`.`id` FROM `conference_staff` INNER JOIN conferences on conferences.id=conference_staff.conference_id  WHERE conference_staff.staff_id=" . $staff_id . " order by id desc)";
            $query = $this->db->query($sql);
            return $query->result();
        } else {
            $this->db->select('conferences.*,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,create_by_role.name as `create_by_role_name`,create_by.surname as `create_for_surname,create_by.employee_id as `create_by_employee_id`')->from('conferences');
            $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id', 'left');
            $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
            $this->db->join('staff_roles', 'staff_roles.id = create_by.id');
            $this->db->join('roles as `create_by_role`', 'create_by_role.id = staff_roles.role_id');
            $this->db->where('conferences.purpose', $type);
            $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
            //$this->db->order_by('conferences.date', 'DESC');
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('conferences');
        //=======================Code End===========================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getByClassSection($class_id, $section_id)
    {
        $this->db->select('conferences.*,classes.class,sections.section,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,for_create.employee_id as `for_create_employee_id`,for_create_role.name as `for_create_role_name`')->from('conferences');
        $this->db->join('classes', 'classes.id = conferences.class_id');
        $this->db->join('sections', 'sections.id = conferences.section_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff_roles', 'staff_roles.id = for_create.id');
        $this->db->join('roles as `for_create_role`', 'for_create_role.id = staff_roles.role_id');
        $this->db->where('conferences.class_id', $class_id);
        $this->db->where('conferences.section_id', $section_id);
        $this->db->where('conferences.session_id', $this->current_session);
        $this->db->order_by('DATE(`conferences`.`date`)', 'DESC');
        $this->db->order_by('conferences.date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getmeeting()
    {
        $this->db->select('conferences_history.*,conference.title,conference.date,patients.id as pid,patients.patient_name,patients.patient_unique_id,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,for_create.employee_id as `for_create_employee_id`,for_create_role.name as `for_create_role_name`,')->from('conferences');
        $this->db->join('conferences', 'conferences.id = conferences_history.conference_id');
        $this->db->join('patients', 'patients.id = conferences.patient_id');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('staff_roles', 'staff_roles.id = for_create.id');
        $this->db->join('roles as `for_create_role`', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.id = create_by.id');
        $this->db->join('roles as `create_by_role`', 'create_by_role.id = staff_create_by_roles.role_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function update($id, $data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $query = $this->db->update("conferences", $data);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getAllStaffByArray($staff = array())
    {

        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");
        $this->db->where_in('staff.id', $staff);
        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result();
    }
}
