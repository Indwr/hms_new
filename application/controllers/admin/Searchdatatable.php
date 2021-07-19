<?php

class Searchdatatable extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('mailsmsconf');
        $this->marital_status       = $this->config->item('marital_status');
        $this->payment_mode         = $this->config->item('payment_mode');
        $this->search_type          = $this->config->item('search_type');
        $this->blood_group          = $this->config->item('bloodgroup');
        $this->charge_type          = $this->customlib->getChargeMaster();
        $data["charge_type"]        = $this->charge_type;
        $this->patient_login_prefix = "pat";
    }

    public function pathology_search()
    {

        $draw            = $_POST['draw'];
        $row             = $_POST['start'];
        $rowperpage      = $_POST['length']; // Rows display per page
        $columnIndex     = $_POST['order'][0]['column']; // Column index
        $columnName      = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition = array();
        if (!empty($_POST['search']['value'])) {
            $where_condition = array('search' => $_POST['search']['value']);
        }
        $resultlist   = $this->pathology_model->search_datatable($where_condition);
        $total_result = $this->pathology_model->search_datatable_count($where_condition);
        $data         = array();

        foreach ($resultlist as $result_key => $result_value) {

            $action = "<div class='rowoptionview'>";
            $action .= " <a href='#' data-toggle='tooltip' title='" . $this->lang->line('show') . "' onclick='viewDetail(" . $result_value->id . "," . $result_value->test_name . "'></a>";
            if ($this->rbac->hasPrivilege('add_patho_patient_test_report', 'can_add')) {

                $action .= "<a href='#' onclick='addTestReport(" . $result_value->id . "," . $result_value->pathology_parameter_id . "),addpatientreport()' class='btn btn-default btn-xs '  data-toggle='tooltip' title='" . $this->lang->line('add_patient_report') . "'><i class='fa fa-plus-square' aria-hidden='true'></i></a>";

            }

            if ($this->rbac->hasPrivilege('pathology test', 'can_view')) {
                $action .= "<a href='#' data-toggle='tooltip' onclick='viewDetail(" . $result_value->id . ")'  class='btn btn-default btn-xs' title='" . $this->lang->line('show') . "' ><i class='fa fa-reorder'></i></a>";

            }

            $action .= "</div'>";
            $first_action = "<a href='#' data-toggle='tooltip' title='" . $this->lang->line('show') . "'  onclick='viewDetail(" . $result_value->id . ")'>";

            $nestedData   = array();
            $nestedData[] = $result_value->test_name;
            $nestedData[] = $result_value->short_name;
            $nestedData[] = $result_value->test_type;
            $nestedData[] = $result_value->category_name;
            $nestedData[] = $result_value->sub_category;
            $nestedData[] = $result_value->method;
            $nestedData[] = $result_value->report_days;
            $nestedData[] = $first_action . $result_value->standard_charge . "</a>" . $action;
            $data[]       = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval($total_result), // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data, // total data array
        );

        echo json_encode($json_data); // send data as json format

    }

    public function radiology_search()
    {

        $draw            = $_POST['draw'];
        $row             = $_POST['start'];
        $rowperpage      = $_POST['length']; // Rows display per page
        $columnIndex     = $_POST['order'][0]['column']; // Column index
        $columnName      = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition = array();
        if (!empty($_POST['search']['value'])) {
            $where_condition = array('search' => $_POST['search']['value']);
        }
        $resultlist   = $this->radio_model->search_datatable($where_condition);
        $total_result = $this->radio_model->search_datatable_count($where_condition);
        $data         = array();

        foreach ($resultlist as $result_key => $result_value) {

            $action = "<div class='rowoptionview'>";
            $action .= " <a href='#' data-toggle='tooltip' title='" . $this->lang->line('show') . "' onclick='viewDetail(" . $result_value->id . ")'></a>";

            if ($this->rbac->hasPrivilege('add_radio_patient_test_report', 'can_add')) {
                $action .= "<a href='#' onclick='addTestReport(" . $result_value->id . "),addpatientreport()' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('add_patient_report') . "'><i class='fa fa-plus-square' aria-hidden='true'></i></a>";
            }

            if ($this->rbac->hasPrivilege('radiology test', 'can_view')) {
                $action .= "<a href='#' data-toggle='tooltip' onclick='viewDetail(" . $result_value->id . ")'  class='btn btn-default btn-xs' title='" . $this->lang->line('show') . "' ><i class='fa fa-reorder'></i></a>";
            }

            $action .= "</div'>";
            $first_action = "<a href='#' data-toggle='tooltip' title='" . $this->lang->line('show') . "'  onclick='viewDetail(" . $result_value->id . ")'>";

            $nestedData   = array();
            $nestedData[] = $first_action . $result_value->test_name . "</a>" . $action;
            $nestedData[] = $result_value->short_name;
            $nestedData[] = $result_value->test_type;
            $nestedData[] = $result_value->lab_name;
            $nestedData[] = $result_value->sub_category;
            $nestedData[] = $result_value->report_days;
            $nestedData[] = "<font style=text-align: right >" . $result_value->standard_charge . "</font>";
            $data[]       = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval($total_result), // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data, // total data array
        );

        echo json_encode($json_data); // send data as json format

    }


    public function radiologyreport_search(){

            $draw = $_POST['draw'];
            $row = $_POST['start'];
            $rowperpage = $_POST['length']; // Rows display per page
            $columnIndex = $_POST['order'][0]['column']; // Column index
            $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
            $where_condition=array();
            if(!empty($_POST['search']['value']) ) {
                $where_condition=array('search'=>$_POST['search']['value']);
            }
            $resultlist = $this->radio_model->searchreport_datatable($where_condition);
            $total_result = $this->radio_model->searchreport_datatable_count($where_condition);
            $data = array();
              
            foreach ($resultlist as $result_key => $result_value) { 
                if (!empty($result_value->apply_charge)) {
                    $charge = $result_value->apply_charge;
                } else {
                    $charge = $detail->standard_charge;
                }
             $action ="<div class='rowoptionview'>"; 
                if (!empty($result_value->radiology_id)) {
                   $action.="<a href=".base_url().'admin/radio/download/'.$result_value->radiology_id." class='btn btn-default btn-xs'  data-toggle='tooltip' title='".$this->lang->line('download')."'><i class='fa fa-download' aria-hidden='true'></i></a>";  
                }
              
                if ($this->rbac->hasPrivilege('add_patho_patient_test_report', 'can_edit')) {
                $action.="<a href='#' onclick='addParametervalue(".$result_value->id.",".$result_value->radiology_id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('add').'/'.$this->lang->line('edit').' '.$this->lang->line('parameter').' '.$this->lang->line('value')."'><i class='fa fa-pencil' aria-hidden='true'></i></a>"; 
                }

               if ($this->rbac->hasPrivilege('pathology_print_report', 'can_view')) {
                $action.="<a href='#' onclick='viewDetailReport(".$result_value->id.",".$result_value->radiology_id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('print').' '.$this->lang->line('report')."'><i class='fa fa-print' aria-hidden='true'></i></a>"; 

                }

                if ($this->rbac->hasPrivilege('pathology_print_bill', 'can_view')) {
                $action.="<a href='#' onclick='viewDetailbill(".$result_value->id.",".$result_value->radiology_id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('print').' '.$this->lang->line('bill')."'><i class='fa fa-print' aria-hidden='true'></i></a>"; 

                }

                if ($this->rbac->hasPrivilege('add_patho_patient_test_report', 'can_delete')) {
                $action.="<a href='#' onclick='deleterecord(".$result_value->id.")' class='btn btn-default btn-xs '  data-toggle='tooltip' title='".$this->lang->line('delete')."'><i class='fa fa-trash' aria-hidden='true'></i></a>"; 
                }
           
            $action.="</div'>";

            $nestedData=array();  
            $nestedData[]= $result_value->bill_no.$action;
            $nestedData[]= $result_value->reporting_date;
            $nestedData[]= $result_value->patient_name;
            $nestedData[]= $result_value->test_name;
            $nestedData[]= $result_value->short_name;
            $nestedData[]= $result_value->name." ".$result_value->surname;
            $nestedData[]= $result_value->description;
            $nestedData[]= $charge;         
            $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval($draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval($total_result),  // total number of records
                "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array
                );

    echo json_encode($json_data);  // send data as json format
    }


   public function ot_search(){

        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition=array();
        if(!empty($_POST['search']['value']) ) {
            $where_condition=array('search'=>$_POST['search']['value']);
        }
        $resultlist = $this->operationtheatre_model->search_datatable($where_condition);
        $total_result = $this->operationtheatre_model->search_datatable_count($where_condition);
        $data = array();
          
        foreach ($resultlist as $result_key => $result_value) { 
        $action ="<div class='rowoptionview'>"; 
        if ($this->rbac->hasPrivilege('ot_consultant_instruction', 'can_add')) {
          $action.="<a href='#' onclick='add_instruction(".$result_value->id.",".$result_value->pid."),refreshmodal()' class='btn btn-default btn-xs'  data-toggle='tooltip' title='". $this->lang->line('consultant').' '. $this->lang->line('instruction')."'><i class='fa fa-user-md'></i></a>";  
        }

        if ($this->rbac->hasPrivilege('ot_patient', 'can_view')) {
            $action.="<a href='#' onclick='viewDetail(".$result_value->pid.")'
       class='btn btn-default btn-xs'  data-toggle='tooltip'  title='".$this->lang->line('show')."'><i class='fa fa-reorder'></i></a>";
        }

        if ($this->rbac->hasPrivilege('ot_patient', 'can_view')) {    
         $action.="<a href='#'  onclick='viewDetailBill(".$result_value->id.")' class='btn btn-default btn-xs'  data-toggle='tooltip'  title='".$this->lang->line('print')."' ><i class='fa fa-print'></i></a>"; 
        }  

      $action.="</div'>";
      $first_action = "<a href='#'   onclick='viewDetail(".$result_value->pid.")' data-toggle='tooltip' title='".$this->lang->line('detail')."'  href='".base_url()."student/view/".$result_value->id."'>";

        $nestedData=array();  
        $nestedData[]= $result_value->bill_no.$action;
        $nestedData[]= $first_action.$result_value->patient_name."</a>";
        $nestedData[]=$result_value->patient_unique_id;
        $nestedData[]=$result_value->gender;
        $nestedData[]=$result_value->mobileno;
        $nestedData[]=$result_value->operation_name;
        $nestedData[]=$result_value->operation_type;
        $nestedData[]=$result_value->name." ".$result_value->surname;
        $nestedData[]= date($this->customlib->getSchoolDateFormat(), strtotime($result_value->date));    
        $nestedData[]=$result_value->apply_charge; 
        $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval($total_result),  // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
            );

echo json_encode($json_data);  // send data as json format


}

    public function birth_record()
    {

        $draw            = $_POST['draw'];
        $row             = $_POST['start'];
        $rowperpage      = $_POST['length']; // Rows display per page
        $columnIndex     = $_POST['order'][0]['column']; // Column index
        $columnName      = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition = array();
        if (!empty($_POST['search']['value'])) {
            $where_condition = array('search' => $_POST['search']['value']);
        }
        $resultlist   = $this->birthordeath_model->search_datatable($where_condition);
        $total_result = $this->birthordeath_model->search_datatable_count($where_condition);
        $data         = array();

        foreach ($resultlist as $result_key => $result_value) {

            $action = "<div class='rowoptionview'>";
            $action .= "<a href='#' onclick='viewDetail(" . $result_value->id . ")' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('show') . "'><i class='fa fa-reorder'></i></a>";

            if ($this->rbac->hasPrivilege('birth_record', 'can_edit')) {
                $action .= "<a href='#' onclick='getRecord(" . $result_value->id . ")' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
            }

            if ($this->rbac->hasPrivilege('birth_record', 'can_delete')) {
                $action .= "<a class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='delete_bill(" . $result_value->id . ")' data-original-title='" . $this->lang->line('delete') . "'><i class='fa fa-trash'></i></a>";
            }

            $action .= "</div'>";

            $nestedData   = array();
            $nestedData[] = $result_value->child_name . "</a>" . $action;
            $nestedData[] = $result_value->gender;
            $nestedData[] = $result_value->ref_no;
            $nestedData[] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result_value->birth_date));
            $nestedData[] = $result_value->patient_name;
            $nestedData[] = $result_value->father_name;
            $nestedData[] = $result_value->birth_report;
            $data[]       = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval($total_result), // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data, // total data array
        );

        echo json_encode($json_data); // send data as json format

    }

    public function death_record()
    {

        $draw            = $_POST['draw'];
        $row             = $_POST['start'];
        $rowperpage      = $_POST['length']; // Rows display per page
        $columnIndex     = $_POST['order'][0]['column']; // Column index
        $columnName      = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition = array();
        if (!empty($_POST['search']['value'])) {
            $where_condition = array('search' => $_POST['search']['value']);
        }
        $resultlist   = $this->birthordeath_model->search_deathdatatable($where_condition);
        $total_result = $this->birthordeath_model->search_deathdatatable_count($where_condition);
        $data         = array();

        foreach ($resultlist as $result_key => $result_value) {

            $action = "<div class='rowoptionview'>";
            $action .= "<a href='#'  onclick='viewDetail(" . $result_value->id . ")'
                    class='btn btn-default btn-xs'  data-toggle='tooltip'
                  title='" . $this->lang->line('show') . "'><i class='fa fa-reorder'></i></a>";

            if ($this->rbac->hasPrivilege('death_record', 'can_edit')) {
                $action .= "<a href='#' onclick='getRecord(" . $result_value->id . ")' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
            }

            if ($this->rbac->hasPrivilege('death_record', 'can_delete')) {
                $action .= "<a  class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='delete_bill(" . $result_value->id . ")' data-original-title='" . $this->lang->line('delete') . "'><i class='fa fa-trash'></i></a>";
            }

            $action .= "</div'>";

            $nestedData   = array();
            $nestedData[] = $result_value->opdipd_no. "</a>" . $action;
            $nestedData[] = $result_value->patient_name;
            $nestedData[] = $result_value->gender;
            $nestedData[] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result_value->death_date));
            $nestedData[] = $result_value->death_report ;
            $data[]       = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval($total_result), // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data, // total data array
        );

        echo json_encode($json_data); // send data as json format

    }

   public function blood_issue()
{
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $where_condition=array();
        if(!empty($_POST['search']['value']) ) {
            $where_condition=array('search'=>$_POST['search']['value']);
        }
        $resultlist = $this->bloodissue_model->search_datatable($where_condition);
        $total_result = $this->bloodissue_model->search_datatable_count($where_condition);
        $data = array();
          
        foreach ($resultlist as $result_key => $result_value) {            
               
            $action ="<div class='rowoptionview'>"; 
            $action.="<a href='#' onclick='viewDetail(".$result_value->id.")' class='btn btn-default btn-xs'  data-toggle='modal' title='".$this->lang->line('show')."' ><i class='fa fa-reorder'></i></a>";
              
            $action.="<a href='#' onclick='viewDetailBill(".$result_value->id.")'
                    class='btn btn-default btn-xs'  data-toggle='modal' title='".$this->lang->line('print')."' ><i class='fa fa-print'></i></a>";                 

                if ($this->rbac->hasPrivilege('blood_issue', 'can_delete')) {
                   $action.="<a  class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='deleterecord(".$result_value->id.")' data-original-title='".$this->lang->line('delete')."'><i class='fa fa-trash'></i></a>"; 
                }               
   
                $action.="</div'>";
               
        $nestedData=array();  
        $nestedData[]= $result_value->bill_no."</a>".$action;
        $nestedData[]=date($this->customlib->getSchoolDateFormat(true, true), strtotime($result_value->date_of_issue));
        $nestedData[]=$result_value->patient_name;
        $nestedData[]= $result_value->blood_group;
        $nestedData[]=$result_value->gender;
        $nestedData[]= $result_value->donor_name;
        $nestedData[]= $result_value->bag_no;
        $nestedData[]= $result_value->amount;
        $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval($total_result),  // total number of records
            "recordsFiltered" => intval($total_result), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
            );

echo json_encode($json_data);  // send data as json format

}

}
