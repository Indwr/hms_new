<?php
class Birthordeath_model extends CI_Model
{

    public $column_order  = array('birth_report.child_name', 'birth_report.gender', 'birth_report.ref_no', 'birth_report.birth_date', 'patients.patient_name', 'birth_report.father_name', 'birth_report.birth_report'); //set column field database for datatable orderable
    public $column_search = array('birth_report.child_name', 'birth_report.gender', 'birth_report.ref_no', 'birth_report.birth_date', 'patients.patient_name', 'birth_report.father_name', 'birth_report.birth_report'); //set column

    public $deathcolumn_order  = array('death_report.opdipd_no', 'patients.patient_name', 'patients.gender', 'death_report.death_date', 'death_report.death_report'); //set column field database for datatable orderable
    public $deathcolumn_search = array('death_report.opdipd_no', 'patients.patient_name', 'patients.gender', 'death_report.death_date', 'death_report.death_report'); //set column field

    public function getDetails($id)
    {

        $this->db->select('birth_report.*,patients.patient_name');
        $this->db->join('patients', 'patients.id = birth_report.mother_name');
        $this->db->where('birth_report.id', $id);
        $query = $this->db->get('birth_report');
        return $query->row_array();
    }

    public function getBirthDetails()
    {
        $this->db->order_by('id', 'desc');
        $this->db->select('birth_report.*,patients.patient_name');
        $this->db->join('patients', 'patients.id=birth_report.mother_name');
        $query = $this->db->get("birth_report");
        return $query->result_array();
    }

    public function search_datatable()
    {
        $this->db->select('birth_report.*,patients.patient_name');
        $this->db->join('patients', 'patients.id=birth_report.mother_name');
        if (!isset($_POST['order'])) {
            $this->db->order_by('birth_report.00id', 'desc');
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
        $query = $this->db->get('birth_report');
        return $query->result();
    }

    public function search_datatable_count()
    {
        $this->db->order_by('id', 'desc');
        $this->db->select('birth_report.*,patients.patient_name');
        $this->db->join('patients', 'patients.id=birth_report.mother_name');
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
        $query        = $this->db->from('birth_report');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function getDetailsCustom($id)
    {
        $query = $this->db->select('custom_fields.*')->where('id', $id)->get('custom_fields');
        return $query->row_array();
    }

    public function getDeDetails($id)
    {
        $this->db->select('death_report.*,patients.patient_name,patients.gender,patients.address');
        $this->db->join('patients', 'patients.id = death_report.patient');
        $this->db->where('death_report.id', $id);
        $query = $this->db->get('death_report');
        return $query->row_array();
    }

    public function getDeDetailsCustom($id)
    {
        $query = $this->db->select('custom_fields.*')->where('id', $id)->get('custom_fields');
        return $query->row_array();
    }

    public function delete($id)
    {
        $query = $this->db->where('id', $id)
            ->delete('birth_report');
    }

    public function deletecustom($id)
    {
        $query = $this->db->where('id', $id)
            ->delete('custom_fields');
    }

    public function deletedeath($id)
    {
        $query = $this->db->where('id', $id)
            ->delete('death_report');
    }

    public function getBirthDetailsCustom()
    {
        $this->db->order_by('id', 'desc');
        $this->db->select('custom_fields.*');
        $this->db->where('belong_to', 'birth_report');
        $query = $this->db->get("custom_fields");
        return $query->result_array();
    }

    public function getDeathDetailsCustom()
    {
        $this->db->order_by('id', 'desc');
        $this->db->select('custom_fields.*');
        $this->db->where('belong_to', 'death_report');
        $query = $this->db->get("custom_fields");
        return $query->result_array();
    }

    public function getBirthData($id)
    {
        $this->db->select('birth_report.*');
        $this->db->where('birth_report.id', $id);
        $query = $this->db->get("birth_report");
        return $query->row_array();
    }

    public function getDeathDetails()
    {
        $this->db->order_by('id', 'desc');
        $this->db->select('death_report.*,patients.patient_name,patients.gender');
        $this->db->join('patients', 'patients.id = death_report.patient');
        $query = $this->db->get("death_report");
        return $query->result_array();
    }

    public function search_deathdatatable()
    {
        $this->db->select('death_report.*,patients.patient_name,patients.gender');
        $this->db->join('patients', 'patients.id = death_report.patient');
        if (!isset($_POST['order'])) {
            $this->db->order_by('death_report.id', 'desc');
        }

        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->deathcolumn_search as $colomn_key => $colomn_value) {
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
            $this->db->order_by($this->deathcolumn_order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        }
        $query = $this->db->get('death_report');
        return $query->result();
    }

    public function search_deathdatatable_count()
    {
        $this->db->order_by('id', 'desc');
        $this->db->select('death_report.*,patients.patient_name,patients.gender');
        $this->db->join('patients', 'patients.id = death_report.patient');
        if (!empty($_POST['search']['value'])) {
            // if there is a search parameter
            $counter = true;
            $this->db->group_start();
            foreach ($this->deathcolumn_search as $colomn_key => $colomn_value) {
                if ($counter) {
                    $this->db->like($colomn_value, $_POST['search']['value']);
                    $counter = false;
                }
                $this->db->or_like($colomn_value, $_POST['search']['value']);
            }
            $this->db->group_end();
        }
        $query        = $this->db->from('death_report');
        $total_result = $query->count_all_results();
        return $total_result;
    }

    public function addDeathdata($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('death_report', $data);
        } else {
            $this->db->insert('death_report', $data);
            return $this->db->insert_id();
        }
    }

    public function addBirthdata($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('birth_report', $data);
        } else {
            $this->db->insert('birth_report', $data);
            return $this->db->insert_id();
        }
    }

}
