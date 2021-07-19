<?php

class Chargetype extends Admin_Controller
{
    public function index()
    {
        if (!$this->rbac->hasPrivilege('hospital_charges', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'charges/index');
        $this->config->load("payroll");
        $charge_type         = $this->customlib->getChargeMaster();
        $result              = $this->setting_model->getChargeMaster();
        $arr                 = array();
        $data["charge_type"] = $charge_type;
        $data['resultlist']  = $result;
        $data['schedule']    = $this->organisation_model->get();
        $this->load->view("layout/header");
        $this->load->view("admin/charges/chargeType", $data);
        $this->load->view("layout/footer");
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('charge_type', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('charge_type', $this->lang->line('charge') . " " . $this->lang->line('type'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'charge_type' => form_error('charge_type'),
            );
            $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array(
                'charge_type' => $this->input->post('charge_type'),
                'is_default'  => 'no',
                'is_active'   => 'yes',
            );

            $insert_id  = $this->chargetype_model->add($data);
            $json_array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($json_array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('charge_type', 'can_delete')) {
            access_denied();
        }
        $result = $this->chargetype_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/chargetype');
    }

}
