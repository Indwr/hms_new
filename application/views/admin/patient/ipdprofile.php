    <?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$genderList = $this->customlib->getGender();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <div class="row">
                <div class="box-body pb0">             
                    <div class="col-lg-2 col-md-2 col-sm-3 text-center">
                        <?php
                        $image = $result['image'];
                        if (!empty($image)) {
                            $file = $result['image'];
                        } else {
                            $file = "uploads/patient_images/no_image.png";
                        }
                        ?>

                        <img width="115" height="115" class="" src="<?php echo base_url() . $file ?>" alt="No Image">
                        <div class="editviewdelete-icon pt8">
                            <a class="" href="#" onclick="getRecord('<?php echo $result['id'] ?>', '<?php echo $ipdid ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('profile') ?>"><i class="fa fa-reorder"></i>
                            </a>
                            <?php
                               
                                if($result['is_active'] != 'no') { if ($result['ipd_discharge'] != 'yes') {if ($this->rbac->hasPrivilege('ipd_patient', 'can_edit')) {
                                    ?>
                                    <a class="" href="#" onclick="getEditRecord('<?php echo $result['id'] ?>', '<?php echo $ipdid ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('edit') . " " . $this->lang->line('profile') ?>"><i class="fa fa-pencil"></i>
                                    </a>

                                   <?php }} if ($result['ipd_discharge'] != 'no') { 
                                        if ($this->rbac->hasPrivilege('discharged_summary', 'can_edit')) { ?>
                                     <a class="" href="#" onclick="getEditRecordDischarged('<?php echo $result['id'] ?>', '<?php echo $ipdid ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('discharged') . " " . $this->lang->line('summary') ?>"><i class="fa fa-file-text"></i>
                                    </a>
                                    <?php } } if ($result['ipd_discharge'] != 'yes') {if ($this->rbac->hasPrivilege('ipd_patient', 'can_delete')) { ?> 
                                    <a class="" href="#" onclick="deletePatient('<?php echo $result['id'] ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('delete') . " " . $this->lang->line('patient') ?>"><i class="fa fa-trash"></i>
                                    </a> 
                                    <?php
                                }}
                            }
                          
                            ?>
                        </div> 
                    </div>
                    <div class="col-md-10 col-lg-10 col-sm-9">
                        <div class="table-responsive">
                            <table class="table table-striped mb0 font13">
                                <tbody>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('name'); ?></th>
                                        <td class="bozerotop"><?php echo $result['patient_name'] ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('guardian_name'); ?></th>
                                        <td class="bozerotop"><?php echo $result['guardian_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('gender'); ?></th>
                                        <td class="bozerotop"><?php echo $result['gender']; ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('age'); ?></th>
                                        <td class="bozerotop">
                                            <?php
                                            if (!empty($result['age'])) {
                                                echo $result['age'] . " " . $this->lang->line('year') . " ";
                                            } if (!empty($result['month'])) {
                                                echo $result['month'] . " " . $this->lang->line('month');
                                            }
                                            ?>   
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('phone'); ?></th>
                                        <td class="bozerotop"><?php echo $result['mobileno']; ?></td>
                                        <th class="bozerotop"><?php
                                            echo $this->lang->line('credit_limit') . " (" . $currency_symbol . ")";
                                            ;
                                            ?></th>
                                        <td class="bozerotop"><?php echo $result['ipdcredit_limit']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                        <td class="bozerotop"><?php echo $result['patient_unique_id']; ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('ipd_no'); ?></th>
                                        <td class="bozerotop"><?php
                                            echo $result['ipd_no'];
                                            if ($result['ipd_discharge'] == 'yes') {
                                                echo " <span class='label label-warning'>" . $this->lang->line("discharged") . "</span>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php
                                            echo $this->lang->line('admission_date');
                                            ;
                                            ?></th>
                                        <td class="bozerotop"><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date'])) ?>
                                        </td>
                                        <th class="bozerotop"><?php
                                            echo $this->lang->line('bed');
                                            ;
                                            ?></th>
                                        <td class="bozerotop"><?php echo $result['bed_name'] . " - " . $result['bedgroup_name'] . " - " . $result['floor_name'] ?>
                                        </td>

                                    </tr>    
                                    <?php if ($result['ipd_discharge'] != 'no') { ?>
                                        <tr>
                                            <th class="bozerotop"><?php echo $this->lang->line('discharged') . " " . $this->lang->line('date'); ?></th>
                                            <td class="bozerotop"><?php echo date($this->customlib->getSchoolDateFormat($result['discharge_date'])); ?>
                                            </td>     
                                        </tr>      
                                    <?php } ?>           
                                </tbody>
                            </table>
                        </div>
                    </div>           
                </div>
            </div>
            <div>

                <div class="box border0">
                    <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-top:5px;"></div>
                    <div class="nav-tabs-custom border0" id="tabs">
                        <ul class="nav nav-tabs">
                            <?php if ($this->rbac->hasPrivilege('consultant register', 'can_view')) { ?>
                                <li class="active">
                                    <a href="#consultant_register"  data-toggle="tab" aria-expanded="true"><i class="fas fa-file-prescription"></i> <?php echo $this->lang->line('consultant') . " " . $this->lang->line('register'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_view')) { ?>

                                <li>
                                    <a href="#diagnosis" data-toggle="tab" aria-expanded="true"><i class="fas fa-diagnoses"></i> <?php echo $this->lang->line('diagnosis'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('ipd timeline', 'can_view')) { ?>

                                <li>
                                    <a href="#timeline" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('timeline'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('ipd_prescription', 'can_view')) { ?>
                                <li>
                                    <a href="#prescription" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('prescription'); ?></a>
                                </li>
                            <?php } if ($this->rbac->hasPrivilege('charges', 'can_view')) { ?>

                                <li>
                                    <a href="#charges" data-toggle="tab" aria-expanded="true"><i class="fas fa-donate"></i> <?php echo $this->lang->line('charges'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('payment', 'can_view')) { ?>

                                <li>
                                    <a href="#payment" data-toggle="tab" aria-expanded="true"><i class="fas fa-hand-holding-usd"></i> <?php echo $this->lang->line('payment'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('bill', 'can_view')) { ?>

                                <li>
                                    <a href="#bill" class="bill" data-toggle="tab" aria-expanded="true"><i class="fas fa-file-invoice-dollar"></i> <?php echo $this->lang->line('bill'); ?></a>
                                </li>
                            <?php } if ($this->module_lib->hasActive('zoom_live_meeting')) { if ($this->rbac->hasPrivilege('live_consultation', 'can_view')) {  ?>
                                  <li>
                                    <a href="#live_consult" class="live_consult" data-toggle="tab" aria-expanded="true"><i class="fa fa-video-camera ftlayer"></i> <?php echo $this->lang->line('live_consult'); ?></a>
                                </li>
                            <?php } } ?>
                        </ul>   

                        <div class="tab-content">
                            <?php
                            $charge_total = 0;
                            $bill_amount = 0;
                            foreach ($charges as $charge) {
                                $charge_total += $charge["apply_charge"];
                                $bill_amount = $charge_total - $paid_amount;
                            }
                            ?>

                           <?php 
                           if($result['ipd_discharge'] != 'yes'){
                           if (($bill_amount != 0) && ($result["ipdcredit_limit"] != '') &&  ($bill_amount >= $result["ipdcredit_limit"])) { ?>            
                        
                                <div class="alert alert-info"><?php echo $this->lang->line('ipd_payment_alert_message'); ?></div>
                        
                            <?php } } ?>

                            <!-- Consultant Register -->
                            <?php if ($this->rbac->hasPrivilege('consultant register', 'can_view')) { ?>
                            <div class="tab-pane active" id="consultant_register">
                                <?php
                                if ($this->rbac->hasPrivilege('consultant register', 'can_add')) {

                                    if ($result['ipd_discharge'] != 'yes') {
                                        ?>
                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addconsultant" onclick="holdModal('add_instruction')" data-toggle="modal"><i class="fas fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('consultant') . " " . $this->lang->line('instruction'); ?></a>
                                        </div><!--./impbtnview-->
                                        <?php
                                    }
                                }
                                ?>    
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('doctor'); ?></th>
                                        <th><?php echo $this->lang->line('instruction'); ?></th>
                                        <th><?php echo $this->lang->line('instruction') . " " . $this->lang->line('date'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($consultant_register)) {
                                                foreach ($consultant_register as $consultant_key => $consultant_value) {
                                                    ?>  
                                                    <tr>
                                                        <td><?php echo $appointment_date = date($this->customlib->getSchoolDateFormat(true, true), strtotime($consultant_value['date'])); ?></td>
                                                        <td><?php echo $consultant_value["name"] . " " . $consultant_value["surname"] ?></td>
                                                        <td><?php echo $consultant_value["instruction"] ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($consultant_value['ins_date'])); ?></td>

                                                        <td class="text-right">
                                                            <?php  if ($result['ipd_discharge'] != 'yes')  {if ($this->rbac->hasPrivilege('consultant register', 'can_delete')) { ?><a 
                                                                    class="btn btn-default btn-xs"  data-toggle="tooltip" title="" onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteIpdPatientConsultant/<?php echo $consultant_value['patient_id']; ?>/<?php echo $consultant_value['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>   
                                                            <?php }} ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?> 
                                        </tbody>
                                    </table>
                                </div> 
                            </div>  
                            <?php } ?>
                            <!-- -->
                            <?php if ($this->rbac->hasPrivilege('ipd_prescription ', 'can_view')) { ?>
                                <div class="tab-pane" id="prescription">
                                    <?php if ($this->rbac->hasPrivilege('ipd_prescription ', 'can_add')) { ?>
                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addprescription" onclick="holdModal('add_prescription')" data-toggle="modal"><i class="fas fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('prescription'); ?></a>
                                        </div><!--./impbtnview-->
                                    <?php } ?>
                                    <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                            <th><?php echo $this->lang->line('ipd_no'); ?></th>
                                            <th><?php echo $this->lang->line('prescription')." ".$this->lang->line('no'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>

                                            <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($prescription_detail)) {
                                                    foreach ($prescription_detail as $prescription_key => $prescription_value) {
                                                        ?>  
                                                        <tr>
                                                            <td><?php echo $result["ipd_no"] ?></td>
                                                             <td><?php echo $prescription_value["id"] ?></td>
                                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($prescription_value['date'])) ?></td>

                                                            <td class="text-right">
                                                                <a href="#prescription" class="btn btn-default btn-xs" onclick="view_prescription('<?php echo $prescription_value["id"] ?>', '<?php echo $prescription_value["ipd_id"] ?>','<?php echo $result["ipd_discharge"]?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('view') . " " . $this->lang->line('prescription'); ?>">
                                                                    <i class="fas fa-file-prescription"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>  
                                <?php } ?>      
                                <!-- -->           
                                <!-- diagnosis -->

                                <?php if ($this->rbac->hasPrivilege('ipd diagnosis ', 'can_view')) { ?>
                                <div class="tab-pane" id="diagnosis">
                                    <?php
                                    if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_add')) {
                                        if ($result['status'] != 'paid') {
                                            $userdata = $this->customlib->getUserData();
                                            $diagnosis = "yes";
                                            $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
                                            if ($doctor_restriction == 'enabled') {
                                                if ($userdata["role_id"] == 3) {
                                                    if ($userdata["id"] == $result["staff_id"]) {
                                                        
                                                    } else {
                                                        $diagnosis = 'not_applicable';
                                                    }
                                                }
                                            }}
                                            if ($result['ipd_discharge'] != 'yes') {
                                                ?>

                                                <div class="impbtnview">
                                                    <a href="#" class="btn btn-sm btn-primary dropdown-toggle adddiagnosis" onclick="holdModal('add_diagnosis')" data-toggle="modal"><i class="fas fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('diagnosis'); ?></a>
                                                </div><!--./impbtnview-->
                                                <?php
                                            }
                                        
                                    }
                                    ?>
                                    <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                            <th><?php echo $this->lang->line('report') . " " . $this->lang->line('type'); ?></th>
                                            <th><?php echo $this->lang->line('report') . " " . $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($diagnosis_detail)) {
                                                    foreach ($diagnosis_detail as $diagnosis_key => $diagnosis_value) {
                                                        ?>  
                                                        <tr>
                                                            <td><?php echo $diagnosis_value["report_type"] ?></td>
                                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($diagnosis_value['report_date'])) ?></td>
                                                            <td><?php echo $diagnosis_value["description"] ?></td>
                                                            <td class="text-right">
                                                                <?php if (!empty($diagnosis_value["document"])) { ?>
                                                                    <a class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download') ?>" href="<?php echo base_url() . "admin/patient/report_download/" . $diagnosis_value["document"] ?>" ><i class="fa fa-download"></i></a>
                                                                <?php } ?>
                                                                <?php
                                                                if ($result['ipd_discharge'] != 'yes'){
                                                                if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_edit')) {
                                                                    if (isset($diagnosis_value["diagnosis"])) {
                                                                        ?>
                                                                        <a 
                                                                            onclick="editDiagnosis('<?php echo $diagnosis_value['id']; ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>   
                                                                    <?php } }
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($result['ipd_discharge'] != 'yes'){
                                                                if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_delete')) {
                                                                    //  if($diagnosis == 'yes'){
                                                                    ?>
                                                                    <a  onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteIpdPatientDiagnosis/<?php echo $diagnosis_value['patient_id']; ?>/<?php echo $diagnosis_value['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>   
            <?php } }  ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?> 
                                            </tbody>
                                        </table>
                                    </div> 
                                </div> 
                        <?php } ?>  
                            <!-- Timeline -->
                          <?php if ($this->rbac->hasPrivilege('ipd timeline', 'can_view')) { ?>  
                            <div class="tab-pane" id="timeline">
<?php if ($result['ipd_discharge'] != 'yes') { if ($this->rbac->hasPrivilege('ipd timeline', 'can_add')) { ?>
                                    <div class="impbtnview">

                                        <a href="#" class="btn btn-sm btn-primary dropdown-toggle addtimeline" onclick="holdModal('myTimelineModal')" data-toggle='modal'><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('timeline'); ?></a>

                                    </div><!--./impbtnview-->
<?php } } ?>
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                <div class="timeline-header no-border">
                                    <div id="timeline_list">
<?php if (empty($timeline_list)) { ?>
                                            <br/>
                                            <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                            <?php } else { ?>
                                            <ul class="timeline timeline-inverse">
                                                <?php
                                                foreach ($timeline_list as $key => $value) {
                                                    ?>      
                                                    <li class="time-label">
                                                        <span class="bg-blue">    
        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date'])); ?></span>
                                                    </li> 
                                                    <li>
                                                        <i class="fa fa-list-alt bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <?php if ($result['ipd_discharge'] != 'yes') {if ($this->rbac->hasPrivilege('ipd timeline', 'can_delete')) { ?>
                                                                <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a></span>
                                                                    <?php }} ?><?php if ($result['ipd_discharge'] != 'yes') {if ($this->rbac->hasPrivilege('edittimeline', 'can_delete')) {
                                                                ?><span class="time"><a onclick="editTimeline('<?php echo $value['id']; ?>')" class="defaults-c text-right" data-toggle="tooltip" title=""  data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a></span> 

                                                            <?php } }?>

                                                            <?php if (!empty($value["document"])) { ?>
                                                                <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" href="<?php echo base_url() . "admin/timeline/download_patient_timeline/" . $value["id"] . "/" . $value["document"] ?>" data-original-title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a></span>
        <?php } ?>
                                                            <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?> </h3>
                                                            <div class="timeline-body">
        <?php echo $value['description']; ?> 
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?> 
                                                <li><i class="fa fa-clock-o bg-gray"></i></li> 
<?php } ?>  
                                        </ul>
                                    </div>
                                </div>
                            </div>  
  <?php } ?>  

                        <!-- Live Consult  -->

                            <?php if ($this->rbac->hasPrivilege('live_consultation', 'can_view')) { ?>
                                <div class="tab-pane" id="live_consult">
                                   <!--  <?php
                                    if ($this->rbac->hasPrivilege('ipd live_consult', 'can_add')) {
                                        if ($result['status'] != 'paid') {
                                            $userdata = $this->customlib->getUserData();
                                            $live_consult = "yes";
                                            $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
                                            if ($doctor_restriction == 'enabled') {
                                                if ($userdata["role_id"] == 3) {
                                                    if ($userdata["id"] == $result["staff_id"]) {
                                                        
                                                    } else {
                                                        $live_consult = 'not_applicable';
                                                    }
                                                }
                                            }}
                                            if ($result['ipd_discharge'] != 'yes') {
                                                ?>

                                                <div class="impbtnview">
                                                    <a href="#" class="btn btn-sm btn-primary dropdown-toggle" onclick="holdModal('add_diagnosis')" data-toggle="modal"><i class="fas fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('live_consult'); ?></a>
                                                </div>
                                                <?php
                                            }
                                        
                                    }
                                    ?> -->
                                    <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                    <th><?php echo $this->lang->line('consult').' '.$this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                       <!--  <th><?php echo $this->lang->line('api_used'); ?></th> -->
                                        <th><?php echo $this->lang->line('created_by'); ?> </th>
                                        <th><?php echo $this->lang->line('created_for'); ?></th>
                                        <th><?php echo $this->lang->line('patient'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if (empty($ipdconferences)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($ipdconferences as $conference_key => $conference_value) {

                                            $return_response = json_decode($conference_value->return_response);
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $conference_value->title; ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($conference_value->description == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $conference_value->description; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>

                                                <td class="mailbox-name">
                                                <?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($conference_value->date))?>
                                              <!--   <td class="mailbox-name">

                                                   <?php echo $this->lang->line($conference_value->api_type);   ?>

                                                </td> -->
                                                <td class="mailbox-name">

                                                    <?php
                                                    if ($conference_value->created_id == $logged_staff_id) {
                                                        echo $this->lang->line('self');
                                                    } else {
                                                        $name= ($conference_value->create_by_surname == "") ? $conference_value->create_by_name : $conference_value->create_by_name . " " . $conference_value->create_by_surname;
        echo  $name. " (".$conference_value->create_by_role_name.": ".$conference_value->create_by_employee_id.")";
                                                    }
                                                    ?></td>
                                                <td class="mailbox-name">
                                                    <?php
                                                          $name= ($conference_value->create_for_surname == "") ? $conference_value->create_for_name : $conference_value->create_for_name . " " . $conference_value->create_for_surname;
        echo  $name. " (".$conference_value->create_for_role_name.": ".$conference_value->create_for_employee_id.")";
                                                    ?>
                                                </td>

                                                <td class="mailbox-name">
                                                     <?php

                                                          $name= ($conference_value->patient_name == "") ? $conference_value->patient_name : $conference_value->patient_name ;
        echo  $name. " (".$conference_value->patient_unique_id.")";
                                                    ?>

                                                </td>
                                            <td class="mailbox-name">
                                                <form class="chgstatus_form" method="POST" action="<?php echo site_url('admin/conference/chgstatus')?>">
                                                <input type="hidden" name="conference_id" value="<?php echo $conference_value->id;?>">
                                                <select class="form-control chgstatus_dropdown" name="chg_status">
                                                     <option value="0" <?php if($conference_value->status==0) echo "selected='selected'" ?>><?php echo $this->lang->line('awaited'); ?></option>
                                                     <option value="1" <?php if($conference_value->status==1) echo "selected='selected'" ?>><?php echo $this->lang->line('cancelled'); ?> </option>
                                                     <option value="2" <?php if($conference_value->status==2) echo "selected='selected'" ?>><?php echo $this->lang->line('finished'); ?> </option>
                                                 </select>
                                                </form>
                                            </td>
                                                <td class="mailbox-date relative text-right" width="90">
                                                    <?php 
if($conference_value->status == 0){
    ?>
<a data-placement="left" href="#" class="btn btn-sm label-success start-mr-20" data-toggle="modal" data-target="#modal-chkstatus" data-id="<?php echo $conference_value->id; ?>">
                                <span class="label" ><i class="fa fa-video-camera"></i> <?php echo $this->lang->line('start') ?></span></a>
    <?php
}
                                                     ?>
                                                    <?php

                                                    if ($conference_value->api_type != 'self') {
                                                        ?>
                                                        <?php 
                                                        if ($result['ipd_discharge'] != 'yes'){
                                                        if($this->rbac->hasPrivilege('live_classes','can_delete')){
                                                            ?>
                                                            <a data-placement="left" href="<?php echo base_url(); ?>admin/conference/delete/<?php echo $conference_value->id . "/" . $return_response->id; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                            <?php
                                                        } }
                                                        ?>
                                                        
                                                        <?php
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    </tbody>
                                </table>
                                    </div> 
                                </div> 
                        <?php } ?> 

                            <!--Charges-->
                       <?php if ($this->rbac->hasPrivilege('charges', 'can_view')) { ?>     
                            <div class="tab-pane" id="charges">
                                <?php
                                if ($this->rbac->hasPrivilege('charges', 'can_add')) {
                                    if ($result['ipd_discharge'] != 'yes') {
                                        ?>

                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addcharges" onclick="holdModal('myChargesModal')" data-toggle='modal'><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('charges'); ?></a>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>                        
                                <div class="download_label"><?php echo $this->lang->line('charges'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('charge') . " " . $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?> </th>
                                        <th class="text-right"><?php
                                            echo $this->lang->line('organisation') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')';
                                            ;
                                            ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            if (!empty($charges)) {

                                                foreach ($charges as $charge) {

                                                    $total += $charge["apply_charge"];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($charge['date'])); ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $charge["charge_type"] ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $charge["charge_category"] ?></td>
                                                        <td class="text-right"><?php echo $charge["standard_charge"] ?></td>
                                                        <td class="text-right"><?php echo $charge["org_charge"] ?></td>
                                                        <td class="text-right"><?php echo $charge["apply_charge"] ?></td>
                                                        <td class="text-right"> 
                                                        <?php if ($result['ipd_discharge'] != 'yes') { if ($this->rbac->hasPrivilege('charges', 'can_delete')) { ?>
                                                                <a onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteIpdPatientCharge/<?php echo $charge['patient_id']; ?>/<?php echo $charge['id']; ?>', '<?php echo $this->lang->line('delete_message') ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a> 
        <?php }} ?>   
                                                        </td>
                                                    </tr>
                                                <?php } ?>  

<?php } ?>
                                        </tbody>


                                        <tr class="box box-solid total-bg">
                                            <td colspan='6' class="text-right"><?php echo $this->lang->line('total') . " : " . $currency_symbol . "" . $total ?> <input type="hidden" id="charge_total" name="charge_total" value="<?php echo $total ?>">
                                            </td><td></td>
                                        </tr>
                                    </table>
                                </div> 
                            </div>
<?php } ?>

                            <div class="tab-pane" id="bill">
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <h4 class="box-title mt0"><?php echo $this->lang->line('charges'); ?></h4>
                                        <div class="table-responsive" style="border: 1px solid #dadada;border-radius: 2px; padding: 10px;">

                                            <table class="nobordertable table table-striped">
                                                <tr>
                                                    <th width="16%" ><?php echo $this->lang->line('charges'); ?> </th>
                                                    <th width="16%" ><?php echo $this->lang->line('category') ?></th>
                                                    <th width="19%"><?php echo $this->lang->line('date') ?></th> 
                                                    <th width="16%" class="pttright reborder"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?> </th>
                                                </tr>
                                                <?php
                                                $j = 0;
                                                $total = 0;
                                                foreach ($charges as $key => $charge) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $charge["charge_type"]; ?></td> 
                                                        <td><?php echo $charge["charge_category"]; ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($charge['date'])) ?></td>
                                                        <td class="pttright reborder"><?php echo $charge["apply_charge"]; ?></td>
                                                    </tr>
                                                    <?php
                                                    $total += $charge["apply_charge"];
                                                    ?>

                                                    <?php
                                                    $j++;
                                                }
                                                ?>
                                                <tr class="box box-solid total-bg">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right"><?php echo $this->lang->line('total') . " : "; ?>  <?php echo $currency_symbol . $total ?></td>

                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-1"></div> -->
                                    <div class="col-md-6">
                                        <h4 class="box-title mt0"><?php echo $this->lang->line('payment'); ?></h4>
                                        <div class="table-responsive" style="border: 1px solid #dadada;border-radius: 2px; padding: 10px;">

                                            <table class="nobordertable table table-striped">
                                                <tr>
                                                    <th width="20%" class=""><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></th>
                                                    <th width="16%" class=""><?php echo $this->lang->line('payment') . " " . $this->lang->line('date'); ?></th>
                                                    <th width="16%" class="text-right"><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?> </th>
                                                </tr>

                                                <?php
                                                $k = 0;
                                                $total_paid = 0;
                                                foreach ($payment_details as $key => $payment) {
                                                    ?>
                                                    <tr>
                                                        <td class="pttleft" style="text-transform: capitalize;"><?php echo $payment["payment_mode"]; ?></td>
                                                        <td class=""><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($payment['date'])); ?></td>
                                                        <td class="text-right"><?php echo $payment["paid_amount"]; ?></td>

                                                    </tr>
                                                    <?php
                                                    $total_paid += $payment["paid_amount"];
                                                }
                                                ?>
                                                <tr class="box box-solid total-bg">
                                                    <td></td>
                                                    <td></td>

                                                    <td class="text-right"><?php echo $this->lang->line('total') . "  : " ?>  <?php echo $currency_symbol . $total_paid ?></td>

                                                </tr>
                                            </table>

                                        </div><!--./table-responsive-->
                                        <h4 class="box-title ptt10"><?php echo $this->lang->line('bill') . " " . $this->lang->line('summary'); ?></h4>                    
                                        <div class="table-responsive" style="border: 1px solid #dadada;border-radius: 2px; padding: 10px;">
                                            <table class="nobordertable table table-striped table-responsive">
                                                <form class="" method="post" id="add_bill" action="#"  enctype="multipart/form-data">
                                                    <input type="hidden" name="status" id="status" value="<?php echo $result["is_active"] ?>">
                                                    <input type="hidden" name="ipdid" value="<?php echo $ipdid ?>">
<?php if ($result['ipd_discharge'] != 'yes') { ?> 
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo number_format($total, 2, '.', ''); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('any_other_charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable"><input type="text" id="other_charge" value="<?php
                                                                if (!empty($result["other_charge"])) {
                                                                    echo $result["other_charge"];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" name="other_charge" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . "(%)"; ?></th> 
                                                            <td class="text-right ipdbilltable">
                                                                <input type="text" id="discount_percent"  name="discount_percent" style="width: 30%; float: right" class="form-control">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable">
                                                                <input type="hidden" name="patient_id" value="<?php echo $result["id"]; ?>">

                                                                <input type="text" id="discount" value="<?php
                                                                if (!empty($result["discount"])) {
                                                                    echo $result["discount"];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" name="discount" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('tax') . "(%)"; ?></th> 
                                                            <td class="text-right ipdbilltable">
                                                                <input type="text" id="tax_percent" name="tax_percent" style="width: 30%; float: right" class="form-control">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable"><input type="text" name="tax" value="<?php
                                                                if (!empty($result["tax"])) {
                                                                    echo $result["tax"];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="tax" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        
                                                      
                                                        <tr>
                                                            <th><?php echo $this->lang->line('gross') . " " . $this->lang->line('total') . " (" . $this->lang->line('balance') . " " . $this->lang->line('amount') . ")" . " (" . $currency_symbol . ")"; ?></th>
                                                            <td class="text-right fontbold20">

                                                            <span id="grass_amount_span" class="">0</span></td> 
                                                           
                                                        </tr>
                                                        <tr>

                                                            <td colspan="2"><input type="hidden" id="gross_total" value="<?php echo $total - $paid_amount ?>" name="gross_total" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php
                                                                if (!empty($paid_amount)) {
                                                                   echo number_format($paid_amount, 2, '.', ''); 
                                                                  //  echo $paid_amount;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>
                                                                <input type="hidden" value="<?php
                                                                if (!empty($paid_amount)) {
                                                                    echo $paid_amount;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="paid_amountpa" name="" style="width: 30%" class="form-control">
                                                                <input type="hidden" value="<?php echo $total ?>" id="total_amount" name="total_amount" style="width: 30%" class="form-control">
                                                                <input type="hidden" value="<?php echo $result['bed'] ?>" id="bed_no" name="bed_no" style="width: 30%; float: right" class="form-control">

                                                            </td>
                                                        </tr>
                                                        

                                                        <tr>
                                                            <th><?php echo $this->lang->line('net_payable') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20">

                                                                <span id="net_amount_span" class="">0</span>
                                                                <input type="hidden" name="net_amount" value="<?php
                                                                if (!empty($result["net_amount"])) {
                                                                    echo $result["net_amount"];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="net_amount" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
<?php } else { ?> 
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('any_other_charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['other_charge'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['discount'] ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['tax'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('gross') . " " . $this->lang->line('total') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['gross_total'] ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $paid_amount; ?>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('net_payable') . " " . $this->lang->line('amount') . " (" . $this->lang->line('paid') . ") (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20">
                                                            <?php echo $result['net_amount'] ?>
                                                            </td>
                                                        </tr>
            <?php } ?>
                                            </table>

                        <?php if ($result['ipd_discharge'] != 'yes') { ?> 
                                                <?php if ($this->rbac->hasPrivilege('calculate_bill', 'can_view')) { ?>
                                                    <input type="button" onclick="calculate()" id="cal_btn"  name="" value="<?php echo $this->lang->line('calculate'); ?>" class="btn btn-sm btn-info">
                                                <?php } if ($this->rbac->hasPrivilege('generate_bill_discharge_patient', 'can_view')) { ?>
                                                    <input data-loading-text="<?php echo $this->lang->line('processing') ?>" type="submit" style="display:none" id="save_button" name="" value="<?php echo $this->lang->line('generate_bill_discharge_patient') ?>" class="btn btn-sm btn-info"/>
                                                <?php } ?>
                                                <a href="#" style="display:none" class="btn btn-sm btn-info" id="printBill" onclick="printBill('<?php echo $result["id"] ?>', '<?php echo $ipdid ?>')"><?php echo $this->lang->line('print') . " " . $this->lang->line('bill') ?></a>
<?php } else { ?>
                                                <span class="pull-right"><?php echo $this->lang->line('bill_generated_by') . " : " . $bill_info["name"] . " " . $bill_info["surname"] . " (" . $bill_info["employee_id"] . ")"; ?></span>

                                                <?php if ($this->rbac->hasPrivilege('revert_generated_bill', 'can_view')) { ?>
                                                    <input type="button" onclick="checkbed('<?php echo $result['id'] ?>', '<?php echo $result['bill_id'] ?>', '<?php echo $result['bed_id'] ?>','<?php echo $ipdid ?>')" id="revert_btn"  name="" value="<?php echo $this->lang->line('revert') . " " . $this->lang->line('generated') . " " . $this->lang->line('bill'); ?>" class="btn btn-sm btn-info">
    <?php } ?>

                                                <a href="#"  class="btn btn-sm btn-info" onclick="printBill('<?php echo $result["id"] ?>', '<?php echo $ipdid ?>')"><?php echo $this->lang->line('print') . " " . $this->lang->line('bill') ?></a>

<?php } ?> 

                                        </div>
                                    </div>
                                    </form>

                                </div>
                            </div> 


                            <div class="tab-pane" id="payment">
                                <?php
                                if ($this->rbac->hasPrivilege('payment', 'can_add')) {
                                    if ($result['ipd_discharge'] != 'yes') {
                                        ?>
                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addpayment" onclick="addpaymentModal()" data-toggle='modal'><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('payment'); ?></a>
                                        </div><!--./impbtnview-->
                                        <?php
                                    }
                                }
                                ?>
                                <div class="download_label"><?php echo $this->lang->line('payment'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>
                                        <?php if ($result['ipd_discharge'] != 'yes') { ?>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                    <?php } ?>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (!empty($payment_details)) {
                                                $total = 0;
                                                foreach ($payment_details as $payment) {
                                                    if (!empty($payment['paid_amount'])) {
                                                        $total += $payment['paid_amount'];
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($payment['date'])); ?></td>
                                                        <td><?php echo $payment["note"] ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $payment["payment_mode"] ?></td>
                                                        <td class="text-right"><?php echo $payment["paid_amount"] ?></td>
                                                       <!--  <td><?php echo $payment["balance_amount"] ?></td> -->
                                                        <td class="text-right">
        <?php if (!empty($payment["document"])) { ?>
                                                                <a href="<?php echo base_url(); ?>admin/payment/download/<?php echo $payment["document"]; ?>"  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            <?php } ?>
        <?php if ($result['ipd_discharge'] != 'yes') {if ($this->rbac->hasPrivilege('payment', 'can_delete')) { 

                
            ?>
                                                                <a href="<?php echo base_url(); ?>admin/patient/deleteIpdPatientPayment/<?php echo $payment['patient_id']; ?>/<?php echo $payment['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_conform') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>   
        <?php } }?>
                                                        </td>
                                                    </tr>

    <?php } ?> 
                                                <tr class="box box-solid total-bg">

                                                    <td></td>
                                                    <td></td>
                                                    <td></td> <td  class="text-right"><?php echo $this->lang->line('total') . " : " . $currency_symbol . "" . $total; ?>
                                                    </td><td></td> 
                                                </tr>

                                            </tbody>

<?php } ?>

                                    </table>
                                </div> 
                            </div>
                            <!-- Bill payment -->  
                        </div>
                    </div>
                </div> 
            </div> <!-- /.box-body -->
        </div><!--./box box-primary-->

    </section>
</div>

<div id="modal-chkstatus"  class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
    <form id="form-chkstatus" action="" method="POST">
        <div class="modal-content">
            <div class="">
                <button type="button" class="close modalclosezoom" data-dismiss="modal">&times;</button>
               
            </div>
            <div class="modal-body" id="zoom_details">

            </div>
        </div>
    </form>
    </div>
</div>

<!-- Add Diagnosis -->
<div class="modal fade" id="add_diagnosis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('diagnosis'); ?> </h4> 
            </div>

            <form id="form_diagnosis" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">    
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
<?php echo $this->lang->line('report') . " " . $this->lang->line('type'); ?> 
                                        <small class="req"> *</small>
                                    </label> 
                                    <input type="text" name="report_type" class="form-control" id="report_type" />
                                    <input type="hidden" value="<?php echo $id ?>" name="patient" class="form-control" id="patient" />    
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('report') . " " . $this->lang->line('date') ?> </label><input type="text" class="form-control date" name="report_date" />
                                </div> 
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('document') ?> </label><input type="file" class="form-control filestyle" name="report_document" id="report_document" />
                                </div> 
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description') ?> </label> <textarea name="description" class="form-control" id="description"></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="box-footer">    
                    <button type="submit" id="form_diagnosisbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save') ?></button>
                </div>  
            </form>


        </div></div> </div>
<!-- Timeline -->
<div class="modal fade" id="myTimelineModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('add') . " " . $this->lang->line('timeline'); ?></h4> 
            </div>
            <form id="add_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">    
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">


                        <div class="row">
                            <div class=" col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                    <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $id ?>">
                                    <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control"  />
                                    <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?>
                                        <small class="req"> *</small>
                                    </label>
                                    <input id="timeline_date" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" placeholder="" type="text" class="form-control date"  />
                                    <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control"></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <div class="" style="margin-top:-5px; border:0; outline:none;">
                                        <input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="<?php echo set_value('timeline_doc'); ?>" />
                                        <span class="text-danger">
<?php echo form_error('timeline_doc'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="align-top"><?php echo $this->lang->line('visible'); ?></label>
                                    <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox"   />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="box-footer">   
                    <button type="submit" id="add_timelinebtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>  
            </form>


        </div>
    </div> 
</div>

<!-- Edit Timeline -->
<div class="modal fade" id="myTimelineEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('edit') . " " . $this->lang->line('timeline'); ?></h4> 
            </div>
            <form id="edit_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">


                            <div class=" col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                    <input type="hidden" name="patient_id" id="epatientid" value="">
                                    <input type="hidden" name="timeline_id" id="etimelineid" value="">
                                    <input id="etimelinetitle" name="timeline_title" placeholder="" type="text" class="form-control"  />
                                    <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                   <!-- <input id="etimelinedate" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" placeholder="" type="text" class="form-control date"  />-->
                                    <input type="text" name="timeline_date" class="form-control date" id="etimelinedate"/>
                                    <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea id="timelineedesc" name="timeline_desc" placeholder=""  class="form-control"></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <div class="" style="margin-top:-5px; border:0; outline:none;"><input id="etimeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="<?php echo set_value('timeline_doc'); ?>" />
                                        <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span></div>
                                </div>
                                <div class="form-group">
                                    <label class="align-top"><?php echo $this->lang->line('visible'); ?></label>
                                    <input id="evisible_check"  name="visible_check" value="yes" placeholder="" type="checkbox"   />

                                </div>
                            </div>


                        </div>
                    </div>
                </div> 

                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>" id="edit_timelinebtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>
            </form>

        </div>
    </div> 
</div>

<!-- Edit Diagnosis -->
<div class="modal fade" id="edit_diagnosis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('edit') . " " . $this->lang->line('diagnosis'); ?></h4> 
            </div>
            <form id="form_editdiagnosis" accept-charset="utf-8"  enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">


                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
<?php echo $this->lang->line('report') . " " . $this->lang->line('type'); ?></label><small class="req"> *</small> 
                                    <input type="text" name="report_type" class="form-control" id="ereporttype" />
                                    <input type="hidden" value="" name="diagnosis_id" class="form-control" id="eid" /> 
                                    <input type="hidden" value="" name="diagnosispatient_id" class="form-control" id="epatient_id" />   


                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
<?php echo $this->lang->line('report') . " " . $this->lang->line('date'); ?></label> 
                                    <input type="text" name="report_date" class="form-control date" id="ereportdate"/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="align-top"><?php echo $this->lang->line('document'); ?></label> <input type="file" class="form-control filestyle" name="report_document" id="ereportdocument" />
                                </div> 
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label> 
                                    <textarea name="description" class="form-control" id="edescription"></textarea>

                                </div> 
                            </div>
                        </div>
                    </div>
                </div>       
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_editdiagnosisbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>

            </form>

        </div>
    </div> 
</div>

<div class="modal fade" id="edit_prescription"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('edit') . " " . $this->lang->line('prescription'); ?></h4> 
            </div>

            <div class="modal-body pt0 pb0" id="editdetails_prescription">
            </div>    

        </div>
    </div> 
</div>
<!-- Add Prescription -->
<div class="modal fade" id="add_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('add') . " " . $this->lang->line('prescription'); ?></h4> 
            </div>
             <form id="form_prescription" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                    <div class="col-sm-9">
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_note'); ?></label> 
                                    <textarea style="height:50px"  name="header_note" class="form-control" id="compose-textareanew" ></textarea>
                                </div> 
                                <hr/>
                            </div>

                            <table class="table table-striped table-bordered table-hover" id="tableID">
                                <tr id="row0">
                                    <td>           
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo $this->lang->line('medicine') . " " . $this->lang->line("category"); ?></label> <small class="req"> *</small>
                                                <select class="form-control select2" style="width: 100%" name='medicine_cat[]' onchange="getMedicineName(0)"  id="medicine_cat0">
                                                    <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                                    <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                                        ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?>
                                                        </option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>                     
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('medicine'); ?></label> 
                                                <select class="form-control select2" style="width: 100%"  name="medicine[]" id="search-query0">
                                                    <option value="l"><?php echo $this->lang->line('select') ?></option>
                                                </select>
                                                <div id="suggesstion-box0"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('dosage'); ?></label> 

                                                <select class="form-control select2" style="width: 100%"  name="dosage[]" id="search-dosage0">
                                                    <option value="l"><?php echo $this->lang->line('select') ?></option>
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('instruction'); ?></label> 
                                                <textarea name="instruction[]" style="height: 28px;" class="form-control" ></textarea>
                                            </div> 
                                        </div>
                                    </td>
                                    <td valign="bottom">                                        
                                        <button type="button" onclick="add_more()" style="color: #2196f3; padding-top: 27px" class="modaltableclosebtn"><i class="fa fa-plus"></i></button></td>
								</tr>
                            </table>
                          <div class="col-sm-12">  
                            <input type="hidden" id="prescription_id" value="<?php echo $result["ipdid"] ?>" name="ipd_no">
                            <input type="hidden" id="ipd_no_value" value="<?php echo $result["ipd_no"] ?>" name="ipd_no_value">
                            <hr/>
                        </div>  
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_note'); ?></label> 
                                    <textarea style="height:50px" rows="1" name="footer_note" class="form-control" id="compose-textareas"></textarea>
                                </div> 
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-sm-3">
                    <div class="ptt10">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('notification')." ".$this->lang->line('to'); ?></label>
                             <?php
                                foreach ($roles as $role_key => $role_value) {
                                            $userdata = $this->customlib->getUserData();
                                            $role_id = $userdata["role_id"];
                                            ?>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="visible[]" value="<?php echo $role_value['id']; ?>" <?php
                                                        if ($role_value["id"] == $role_id) {
                                                            echo "checked onclick='return false;'";
                                                        }
                                                        ?>  <?php echo set_checkbox('visible[]', $role_value['id'], false) ?> /> <b><?php echo $role_value['name']; ?></b> </label>
                                                </div>
                                                <?php
                                            }
                                            ?>

                     </div>
                </div>
                </div>  
                </div> <!--./modal-body--> 
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_prescriptionbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info"><?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>   
        </div>
    </div> 
</div>

<!-- -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('patient') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formrevisit" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table mb0 table-striped table-bordered examples">
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                            <td width="35%"><span id="patient_name"></span></td>
                                            <th width="15%"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                            <td width="35%"><span id='patients_id'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('guardian_name'); ?></th>
                                            <td width="35%"><span id='guardian_name'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('gender'); ?></th>
                                            <td width="35%"><span id='gen'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('marital_status'); ?></th>
                                            <td width="35%"><span id="marital_status"></span></td>
                                            <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                                            <td width="35%"><span id="contact"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('email'); ?></th>
                                            <td width="35%"><span id='email' style="text-transform: none"></span></td>
                                            <th width="15%"><?php echo $this->lang->line('address'); ?></th>
                                            <td width="35%"><span id='patient_address'></span></td>
                                        </tr>
                                        <tr>  
                                            <th width="15%"><?php echo $this->lang->line('age'); ?></th>
                                            <td width="35%"><span id="age"></span></td>
                                            <th width="15%"><?php echo $this->lang->line('blood_group'); ?></th>
                                            <td width="35%"><span id="blood_group"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('height'); ?></th>
                                            <td width="35%"><span id='height'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('weight'); ?></th>
                                            <td width="35%"><span id="weight"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('temperature'); ?></th>
                                            <td width="35%"><span id='temperature'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('respiration'); ?></th>
                                            <td width="35%"><span id="respiration"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('pulse'); ?></th>
                                            <td width="35%"><span id='pulse'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('bp'); ?></th>
                                            <td width="35%"><span id='patient_bp'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('symptoms'); ?></th>
                                            <td width="35%"><span id='symptoms'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('known_allergies'); ?></th>
                                            <td width="35%"><span id="known_allergies"></span></td>                               
                                        </tr>
                                        <tr>
                                             <th width="15%"><?php echo $this->lang->line('admission') . " " . $this->lang->line('date'); ?></th>
                                            <td width="35%"><span id="admission_date"></span></td> 
                                            <th width="15%"><?php echo $this->lang->line('case'); ?></th>
                                            <td width="35%"><span id='case'></span></td>                                        
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('old') . " " . $this->lang->line('patient'); ?></th>
                                            <td width="35%"><span id='old_patient'></span></td>                                   
                                            <th width="15%"><?php echo $this->lang->line('casualty'); ?></th>
                                            <td width="35%"><span id="casualty"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('refference'); ?></th>
                                            <td width="35%"><span id="refference"></span></td>
                                            <th width="15%"><?php echo $this->lang->line('organisation'); ?></th>
                                            <td width="35%"><span id="organisation"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo $this->lang->line('bed') . " " . $this->lang->line('group'); ?></th>
                                            <td width="35%"><span id="bed_group"></span></td>
                                             <th width="15%"><?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></th>
                                            <td width="35%"><span id='doc'></span></td>                                       
                                        </tr>
                                        <tr>                                        
                                            <th width="15%"><?php echo $this->lang->line('bed') . " " . $this->lang->line('number'); ?></th>
                                            <td width="35%"><span id='bed_name'></span></td>
                                        </tr>
                                    </table>
                                </div>    
                            </div>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div> 
</div>

<!-- -->
<div class="modal fade" id="prescriptionview" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close"  data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='edit_deleteprescription'>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('prescription'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescription"></div>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('payment'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="add_payment" accept-charset="utf-8" method="post" class="ptt10" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small class="req"> *</small> 
                                        <input type="text" name="amount" id="amount" class="form-control">    
                                        <input type="hidden" name="patient_id" id="payment_patient_id" class="form-control">
                                        <input type="hidden" name="ipdid" value="<?php echo $ipdid ?>">
                                        <input type="hidden" name="total" id="total" class="form-control">
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label> 
                                        <select class="form-control" name="payment_mode">
                                            <?php foreach ($payment_mode as $key => $value) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php
                                                if ($key == 'cash') {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $value ?></option>
<?php } ?>
                                        </select>    
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small> 
                                        <input type="text" name="payment_date" id="date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input type="file" class="filestyle form-control"   name="document">
                                        <span class="text-danger"><?php echo form_error('document'); ?></span> 
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('note'); ?></label> 
                                        <input type="text" name="note" id="note" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">   
                                <div class="box-footer">
                                    <button type="submit" id="add_paymentbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>  
                            </div>   
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div> 
</div>
<!-- -->

<!--Add Charges-->
<div class="modal fade" id="myChargesModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('charges') ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="add_charges" accept-charset="utf-8"  method="post" class="ptt10" >
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small> 
                                        <input id="charge_date" name="date" placeholder="" type="text" class="form-control date" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('type') ?></label><small class="req"> *</small> 

                                        <select name="charge_type" onchange="getcharge_category(this.value)" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($charge_type as $key => $value) {
                                                ?>
                                                <option value="<?php echo $key ?>">
                                                <?php echo $value ?>
                                                </option>
<?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('charge_type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('category') ?></label><small class="req"> *</small> 
                                        <select name="charge_category" id="charge_category" style="width: 100%" class="form-control select2" onchange="getchargecode(this.value)">
                                            <option value=""><?php echo $this->lang->line('select')?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('charge_category'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('code') ?></label><small class="req"> *</small> 
                                        <select name="code" id="code" style="width: 100%" class="form-control select2" onchange="get_Charges(this.value, '<?php echo $result['organisation'] ?>')">
                                            <option value=""><?php echo $this->lang->line('select')?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('code'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")" ?></label>
                                        <input type="text" readonly name="standard_charge" id="standard_charge" class="form-control" value="<?php echo set_value('standard_charge'); ?>"> 
                                        <input type="hidden" name="patient_id" value="<?php echo $result["id"] ?>">
                                        <input type="hidden" name="charge_id" id="charge_id">
                                        <input type="hidden" name="org_id" id="org_id">
                                        <input type="hidden" name="ipdid" value="<?php echo $ipdid ?>" >
                                        <span class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('organisation') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")" ?></label>
                                        <input type="text" readonly name="schedule_charge" id="schedule_charge" placeholder="" class="form-control" value="<?php echo set_value('schedule_charge'); ?>">    
                                        <span class="text-danger"><?php echo form_error('schedule_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")" ?></label><small class="req"> *</small><input type="text" name="apply_charge" id="apply_charge" class="form-control">    
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="box-footer">    
                                    <button type="submit" id="add_chargesbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save') ?></button>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div> 
</div>
<!-- -->
<div class="modal fade" id="myModaledit"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modalfullmobile" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!--<h4 class="box-title"><?php echo $this->lang->line('patient') . " " . $this->lang->line('information') ?></h4> -->
                <div class="row">
                    <div class="col-sm-6 col-xs-8">
                        <div class="form-group15">                                     
                            <div>
                                <select onchange="get_ePatientDetails(this.value)" disabled class="form-control select2" style="width:100%" id="evaddpatient_id" name='' >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($patients as $dkey => $dvalue) { ?>
                                        <option value="<?php echo $dvalue["id"]; ?>" <?php
                                        if ((isset($patient_select)) && ($patient_select == $dvalue["id"])) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $dvalue["patient_name"] . " (" . $dvalue["patient_unique_id"] . ")" ?>
                                        </option>   
                                    <?php } ?>
                                </select>
                            </div>
                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                        </div>
                    </div><!--./col-sm-6 col-xs-8 -->
                </div><!--./row--> 
            </div>
            <form id="formeditrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row row-eq">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div class="ptt10">
                                        <div id="evajax_load"></div>
                                        <div class="row" id="evpatientDetails" style="display:none">
                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                <ul class="singlelist">
                                                    <li class="singlelist24bold">
                                                        <span id="evlistname"></span></li>
                                                    <li>
                                                        <i class="fas fa-user-secret" data-toggle="tooltip" data-placement="top" title="Guardian"></i>
                                                        <span id="evguardian"></span>
                                                    </li>
                                                </ul>   
                                                <ul class="multilinelist">   
                                                    <li>
                                                        <i class="fas fa-venus-mars" data-toggle="tooltip" data-placement="top" title="Gender"></i>
                                                        <span id="evgenders" ></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-tint" data-toggle="tooltip" data-placement="top" title="Blood Group"></i>
                                                        <span id="evblood_group"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-ring" data-toggle="tooltip" data-placement="top" title="Marital Status"></i>
                                                        <span id="evmarital_status"></span>
                                                    </li> 
                                                </ul>  
                                                <ul class="singlelist">  
                                                    <li>
                                                        <i class="fas fa-hourglass-half" data-toggle="tooltip" data-placement="top" title="Age"></i>
                                                        <span id="evage"></span>
                                                    </li> 
                                                    <li>
                                                        <i class="fa fa-phone-square" data-toggle="tooltip" data-placement="top" title="Phone"></i> 
                                                        <span id="evlistnumber"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="Email"></i>
                                                        <span id="evemail"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-street-view" data-toggle="tooltip" data-placement="top" title="Address"></i>
                                                        <span id="evaddress" ></span>
                                                    </li>
													<li>
                                                        <b><?php echo $this->lang->line('any_known_allergies') ?> </b> 
                                                        <span id="evallergies" ></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('remarks') ?> </b> 
                                                        <span id="evnote"></span>
                                                    </li>    
                                                </ul>                               
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3"> 
                                                <div class="pull-right">
                                                    <?php
                                                    $file = "uploads/patient_images/no_image.png";
                                                    ?>        
                                                    <img class="profile-user-img img-responsive" src="<?php echo base_url() . $file ?>" id="evimage" alt="User profile picture">
                                                </div>           
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12"> 
                                                <div class="dividerhr"></div>
                                            </div><!--./col-md-12-->
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('height'); ?></label> 
                                                    <input name="height" id="evheight" type="text" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('weight'); ?></label> 
                                                    <input name="weight" id="evweight" type="text" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('bp'); ?></label> 
                                                    <input name="bp" id="evbp" type="text" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('pulse'); ?></label> 
                                                    <input name="pulse" id="evpulse" type="text" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('temperature'); ?></label> 
                                                    <input name="temperature" id="evtemperature" type="text" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('respiration'); ?></label> 
                                                    <input name="respiration" id="evrespiration" type="text" class="form-control" />
                                                </div>
                                            </div>
                                             <div class="col-sm-3 col-xs-4">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('symptoms')." ".$this->lang->line('type') ; ?></label>
                                                <div><select name='symptoms_type' id="act" class="form-control select2 act" style="width:100%">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                            ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["symptoms_type"] ;?></option>   
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                            </div>
                                        </div>
                                            <div class="col-sm-3">
                                                <label for="exampleInputFile"> 
                                                    <?php echo $this->lang->line('symptoms')." ".$this->lang->line('title') ; ?></label>
                                                <div id="dd" class="wrapper-dropdown-3">
                                                    <input class="form-control filterinput" type="text">
                                                    <ul class="dropdown scroll150 section_ul">
                                                        <li><label class="checkbox"><?php echo $this->lang->line('select') ?></label></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="email"><?php echo $this->lang->line('symtoms')." ".$this->lang->line('description'); ?></label> 
                                                    <textarea row="3" name="symptoms" id="symptoms_description" class="form-control" ></textarea>
                                                </div> 
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label> 
                                                    <textarea name="note" id='evnoteipd' rows="3" class="form-control"><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>     
                                        </div>
                                        <input name="patient_id" id="evpatients_id" type="hidden" class="form-control" value="<?php echo set_value('id'); ?>" />
                                        <input name="otid" id="otid" type="hidden" class="form-control"  value="<?php echo set_value('id'); ?>" />
                                        <input type="hidden" id="updateid" name="updateid">
                                        <input type="hidden" id="ipdid" name="ipdid">
                                        <input type="hidden" id="previous_bed_id" name="previous_bed_id">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('admission') . " " . $this->lang->line('date') ?><small class="req"> *</small> </label>
                                                <input id="edit_admission_date" name="appointment_date" placeholder="" type="text" class="form-control datetime" />
                                                <span class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('case'); ?></label>
                                                <div><input class="form-control" type='text' id="patient_case" name='case_type' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('casualty'); ?></label>
                                                <div>
                                                    <select name="casualty" id="patient_casualty" class="form-control" >
                                                        <option value="<?php echo $this->lang->line('yes') ?>"><?php echo $this->lang->line('yes') ?></option>
                                                        <option value="<?php echo $this->lang->line('no') ?>" selected><?php echo $this->lang->line('no') ?></option>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span></div>
                                        </div> 
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('old') . " " . $this->lang->line('patient'); ?></label>
                                                <div>
                                                    <select name="old_patient" id="old" class="form-control">
                                                        <option value="<?php echo $this->lang->line('yes') ?>"><?php echo $this->lang->line('yes') ?></option>
                                                        <option value="<?php echo $this->lang->line('no') ?>" selected><?php echo $this->lang->line('no') ?></option>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('credit_limit') . " (" . $currency_symbol . ")"; ?></label>
                                                <input type="text" id="credits_limits" value="<?php echo set_value('credit_limit'); ?>" name="credit_limit" class="form-control">
                                            </div>
                                        </div>                                  
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('organisation'); ?></label>
                                                <div><select class="form-control" name='organisation' id="organisations">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                            ?>
                                                            <option value="<?php echo $orgvalue["id"]; ?>"><?php echo $orgvalue["organisation_name"] ?></option>   
<?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('organisation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('refference'); ?></label>
                                                <div><input class="form-control" type='text' name='refference' id="patient_refference" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?> <small class="req"> *</small> </label>
                                                <div>
                                                    <select class="form-control select2" style="width: 100%;" <?php
                                                            if ($disable_option == true) {
                                                                echo "disabled";
                                                            }
                                                            ?> name='cons_doctor' id="patient_consultant" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($doctors as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                    if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>   
                                                    <?php } ?>
                                                    </select>
<?php if ($disable_option == true) { ?>
                                                        <input type="hidden" name="cons_doctor" value="<?php echo $doctor_select ?>">
<?php } ?>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('cons_doctor'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                        <?php echo $this->lang->line('bed') . " " . $this->lang->line('group'); ?></label>
                                                <div>
                                                    <select class="form-control" name='bed_group_id' id='bed_group_id' onchange="getBed(this.value, '', 'yes')">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($bedgroup_list as $key => $bedgroup) {
                                                            ?>
                                                            <option value="<?php echo $bedgroup["id"] ?>"><?php echo $bedgroup["name"] . " - " . $bedgroup["floor_name"] ?></option>
<?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>  

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('bed') . " " . $this->lang->line('no'); ?></label><small class="req"> *</small> 
                                                <div><select class="form-control select2" style="width:100%" name='bed_no' id='bed_nos'>
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>

                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('bed_no'); ?></span></div>
                                        </div>  
                                    </div><!--./row-->    
                                </div><!--./col-md-4-->
                            </div><!--./row-->   
                        </div><!--./col-md-12-->       
                    </div><!--./row--> 
                </div>             
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="formeditrecordbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>

            </form>  
        </div>
    </div>    
</div>

<!-- discharged summary   -->
<div class="modal fade" id="myModaldischarged"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <div class="modalicon"> 
                 	 <div id='summary_print'>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('discharged') . " " . $this->lang->line('summary') ?></h4> 
                <div class="row">
				</div><!--./row--> 
            </div>
            <form id="formdishrecord" accept-charset="utf-8"  enctype="multipart/form-data" method="post" >
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            <div class="row row-eq">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="ptt10">
                                        <div id="evajax_load"></div>
                                        <div class="row" id="" >
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <ul class="multilinelist">                                                 
                                                      <li>  <label for="pwd"><?php echo $this->lang->line('name'); ?></label>                                  
                                                        <span id="disevlistname"></span>
                                                    </li>
                                                     <li>
                                                        <label for="pwd"><?php echo $this->lang->line('age'); ?></label>
                                                        <span id="disevage"></span>
                                                    </li> 
                                                     <li>
                                                        <label for="pwd"><?php echo $this->lang->line('gender'); ?></label>
                                                        <span id="disevgenders" ></span>
                                                    </li>
                                                </ul>   
                                                <ul class="multilinelist">                                                    
                                                    <li>
                                                         <label><?php echo $this->lang->line('admission') . " " . $this->lang->line('date') ?></label>
                                                        <span id="disedit_admission_date"></span>
                                                    </li> 
                                                    <li>
                                                         <label><?php echo $this->lang->line('discharged') . " " . $this->lang->line('date') ?></label>
                                                        <span id="disedit_discharge_date"></span>
                                                    </li> 
                                                </ul>  
                                            <ul class="singlelist">  
                                                    <li>
                                                        <label><?php echo $this->lang->line('address')?></label>
                                                        <span id="disevaddress"></span>
                                                    </li>
                                            </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('diagnosis'); ?></label>
                                                    <input name="diagnosis" id='disdiagnosis' rows="3" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('operation'); ?></label>
                                                    <input name="operation" id='disoperation'  class="form-control" >
                                                </div>
                                            </div>                                                 
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label> 
                                                    <textarea name="note" id='disevnoteipd' rows="3" class="form-control" ><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>                                          
                                            <div class="col-md-12"> 
                                                <div class="dividerhr"></div>
                                            </div><!--./col-md-12-->                                         
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('investigations'); ?></label> 
                                                    <textarea name="investigations" id='disinvestigations' rows="3" class="form-control" ><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('treatment_at_home'); ?></label> 
                                                    <textarea name="treatment_at_home" id='distreatment_at_home' rows="3" class="form-control" ><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>     
                                        </div>
                                        <input name="patient_id" id="disevpatients_id" type="hidden">
                                        <input type="hidden" id="disupdateid" name="updateid">
                                        <input type="hidden" id="disipdid" name="ipdid">
										</div>
                                </div>                               
                            </div><!--./row-->   
                        </div><!--./col-md-12-->       
                    </div><!--./row--> 
                </div>             
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="formdishrecordbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>  
        </div>
    </div>    
</div>
<!-- discharged summary   -->
<div class="modal fade" id="add_instruction" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('consultant') . " " . $this->lang->line('instruction'); ?></h4> 
            </div>
            <form id="consultant_register_form"  accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="">   
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-sm-4">
                                <input name="patient_id" placeholder="" id="ins_patient_id" value="<?php echo $result["id"] ?>" type="hidden" class="form-control" />
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table tableover table-striped table-bordered table-hover" id="constableID">
                                        <tr>
                                            <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('date'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                            <th><?php echo $this->lang->line('consultant'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                            <th><?php echo $this->lang->line('instruction'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                            <th><?php echo $this->lang->line('instruction') . " " . $this->lang->line('date'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                        </tr>
                                        <tr id="row0">
                                            <td><input type="text" name="date[]" value="" class="form-control datetime"></td>
                                            <td>
                                                <input type="hidden" name="doctor[]" id="doctor_set">                                
                                                <select name="doctor_field[]" <?php
                                                    if ($disable_option == true) {
                                                        echo "disabled";
                                                    }
                                                    ?> style="width: 100%" id="doctor_field" class="form-control select2">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($doctors as $key => $value) {
                                                        ?>
                                                        <option  <?php
                                                        if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $value["id"] ?>"><?php echo $value["name"] . " " . $value["surname"] ?></option>
    													<?php } ?>
                                                </select></td>
                                            <td><textarea name="instruction[]" style="height:28px" class="form-control"></textarea></td>
                                            <td><input type="text"  name="insdate[]" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" class="form-control date">
                                                <input type="hidden" name="ipdid" value="<?php echo $ipdid ?>">
                                            </td>
                                            <td><button type="button" onclick="add_consultant_row()" style="color: #2196f3" class="closebtn"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="box-footer">   
                    <button type="submit" id="consultant_registerbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>"  class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div> 
            </form>
        </div>
    </div>
</div>    

<!-- change bed -->
<div class="modal fade" id="alot_bed" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('bed'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="alot_bed_form"  accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="alert alert-info">
<?php echo $this->lang->line('bed_alot_message') ?>  
                            </div>  
                            <div class="row">
                                <input name="patient_id" placeholder=""  value="<?php echo $result["id"] ?>" type="hidden" class="form-control"   />
                                <input name="ipd_no" placeholder=""  value="<?php echo $result["ipd_no"] ?>" type="hidden" class="form-control"   />
                                <div class="col-md-12">
                                    <label><?php echo $this->lang->line('bed') . " " . $this->lang->line('group'); ?><small class="req"> *</small></label>
                                    <select class="form-control" onchange="getBed(this.value, '', 'yes', 'alotbedoption')" name="bedgroup">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
<?php foreach ($bedgroup_list as $key => $bedgroup) {
    ?>
                                            <option value="<?php echo $bedgroup["id"] ?>"><?php echo $bedgroup["name"] . " - " . $bedgroup["floor_name"] ?></option>
<?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                    <label><?php echo $this->lang->line('bed') . " " . $this->lang->line('no'); ?><small class="req"> *</small></label>
                                    <select class="form-control select2" style="width: 100%" id="alotbedoption" name="bedno">
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" id="alotbedbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>"  class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div> 

<!-- <script type="text/html" id="act-template">
	<?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                            ?>
        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["symptoms_type"] ;?></option> 
        <?php
    }
    ?>
</script>   -->

<script>
    $(document).on('change', '.act', function () {
        $this = $(this);
        var sys_val = $(this).val();
        var section_ul = $(this).closest('div.row').find('ul.section_ul');
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/patient/getPartialsymptoms',
            data: {'sys_id': sys_val},
            dataType: 'JSON',
            beforeSend: function () {
                // setting a timeout
                $('ul.section_ul').find('li:not(:first-child)').remove();
            },
            success: function (data) {
                section_ul.append(data.record);
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
            },
            complete: function () {

            }
        });
    });
</script>
<script type="text/javascript"> 
    $(document).on('click', '.remove_row', function () {
        $this = $(this);
        $this.closest('.row').remove();
    });

    $(document).mouseup(function (e)
    {
        var container = $(".wrapper-dropdown-3"); // YOUR CONTAINER SELECTOR
        if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            $("div.wrapper-dropdown-3").removeClass('active');
        }
    });

    $(document).on('click', '.filterinput', function () {
        if (!$(this).closest('.wrapper-dropdown-3').hasClass("active")) {
            $(".wrapper-dropdown-3").not($(this)).removeClass('active');
            $(this).closest("div.wrapper-dropdown-3").addClass('active');
        }
    });

    $(document).on('click', 'input[name="section[]"]', function () {
        $(this).closest('label').toggleClass('active_section');
    });

    $(document).on('keyup', '.filterinput', function () {
        var valThis = $(this).val().toLowerCase();
        var closer_section = $(this).closest('div').find('.section_ul > li');
        var noresult = 0;
        if (valThis == "") {
            closer_section.show();
            noresult = 1;
            $('.no-results-found').remove();
        } else {
            closer_section.each(function () {
                var text = $(this).text().toLowerCase();
                var match = text.indexOf(valThis);
                if (match >= 0) {
                    $(this).show();
                    noresult = 1;
                    $('.no-results-found').remove();
                } else {
                    $(this).hide();
                }
            });
        }
        ;
        if (noresult == 0) {
            closer_section.append('<li class="no-results-found">No results found.</li>');
        }
    });
    
</script>
<script type="text/javascript">
    $(function () {
        $('.select2').select2();
    });

    function addpaymentModal() {
        var total = $("#charge_total").val();
        var patient_id = '<?php echo $result["id"] ?>';
        $("#total").val(total);
        $("#payment_patient_id").val(patient_id);
        holdModal('myPaymentModal');
    }

    $('#modal-chkstatus').on('shown.bs.modal', function (e) {
            var $modalDiv = $(e.delegateTarget);            
            var id=$(e.relatedTarget).data('id');            
            
            $.ajax({
                type: "POST",
                url: base_url + 'admin/conference/getlivestatus',
                data: {'id':id},
                dataType: "JSON",
                beforeSend: function () {
				$('#zoom_details').html("");
                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {                    
                   $('#zoom_details').html(data.page);
                    $modalDiv.removeClass('modal_loading');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function (data) {
                    $modalDiv.removeClass('modal_loading');
                }
            });
        })
    function getRecord(id, ipdid) {
        console.log(id);
        var active = '<?php echo $result['is_active'] ?>';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getIpdDetails',
            type: "POST",
            data: {recordid: id, ipdid: ipdid, active: active},
            dataType: 'json',
            success: function (data) {
                $("#patients_id").html(data.patient_unique_id);
                $("#patient_name").html(data.patient_name);
                $("#contact").html(data.mobileno);
                $("#email").html(data.email);
                var age = '';
                var month = '';
                if (data.age != '') {
                    age = data.age + ' Year ';
                }
                if (data.month != '') {
                    month = data.month + ' Month ';
                }
                $("#age").html(age + month);
                $("#gen").html(data.gender);
                $("#guardian_name").html(data.guardian_name);
                $("#admission_date").html(data.date);
                $("#case").html(data.case_type);
                $("#casualty").html(data.casualty);
                $("#symptoms").html(data.vsymptoms);
                $("#known_allergies").html(data.known_allergies);
                $("#refference").html(data.refference);
                $("#doc").html(data.name + ' ' + data.surname);
                $("#amount").html(data.amount);
                $("#tax").html(data.tax);
                $("#height").html(data.height);
                $("#weight").html(data.weight);
                $("#temperature").html(data.temperature);
                $("#respiration").html(data.respiration); 
                $("#pulse").html(data.pulse);       
                $("#patient_bp").html(data.bp);
                $("#blood_group").html(data.blood_group);
                $("#old_patient").html(data.old_patient);
                $("#payment_mode").html(data.payment_mode);
                $("#organisation").html(data.organisation_name);
                $("#opdid").val(data.opdid);
                $("#patient_address").html(data.address);
                $("#marital_status").html(data.marital_status);
                $("#note").val(data.note);
                $("#bed_group").html(data.bedgroup_name + '-' + data.floor_name);
                $("#bed_name").html(data.bed_name);
                $("#updateid").val(id);
                holdModal('viewModal');
            },
        });
    }
    function getEditRecord(id, ipdid) {
        var active = '<?php echo $result['is_active'] ?>';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getIpdDetails',
            type: "POST",
            data: {recordid: id, ipdid: ipdid, active: active},
            dataType: 'json',
            success: function (data) {
                $('#evlistname').html(data.patient_name);
                $('#evguardian').html(data.guardian_name);
                $('#evlistnumber').html(data.mobileno);
                $('#evemail').html(data.email);
                if (data.age == "") {
                    $("#evage").html("");
                } else {
                    if (data.age) {
                        var age = data.age + " " + "Years";
                    } else {
                        var age = '';
                    }
                    if (data.month) {
                        var month = data.month + " " + "Month";
                    } else {
                        var month = '';
                    }
                    if (data.dob) {
                        var dob = "(" + data.dob + ")";
                    } else {
                        var dob = '';
                    }

                    $("#evage").html(age + "," + month + " " + dob);
                }
                $("#evaddress").html(data.address);
                $("#enote").html(data.note);
                $("#evgenders").html(data.gender);
                $("#evmarital_status").html(data.marital_status);
                $("#evblood_group").html(data.blood_group);
                $("#evallergies").html(data.known_allergies);
                $("#patients_ids").val(data.patient_unique_id);
                $("#patient_names").val(data.patient_name);
                $("#edit_admission_date").val(data.date);
                $("#contacts").val(data.mobileno);
                $("#patient_image").val(data.image);
                $("#emails").val(data.email);
                $("#ages").val(data.age);
                $("#months").val(data.month);
                $("#evheight").val(data.height);
                $("#evweight").val(data.weight);
                $("#evbp").val(data.bp);
                $("#evpulse").val(data.pulse);
                $("#evtemperature").val(data.temperature);
                $("#evrespiration").val(data.respiration);
                $("#edit_patient_address").val(data.address);
                $("#patient_case").val(data.case_type);
                $("#symptoms_description").val(data.symptoms);
                $("#patient_allergies").val(data.known_allergies);
                $("#evnoteipd").val(data.ipdnote);
                $("#patient_refference").val(data.refference);
                $("#bloodgroups").val(data.blood_group);
                $("#guardian_names").val(data.guardian_name);
                $("#credits_limits").val(data.ipdcredit_limit);
                $("#ipdid").val(data.ipdid);
                $("#previous_bed_id").val(data.bed);
                $("#bed_group_id").val(data.bed_group_id);
                getBed(data.bed_group_id, data.bed, 'yes');
                $("#updateid").val(id);
                $('select[id="patient_consultant"] option[value="' + data.cons_doctor + '"]').attr("selected", "selected");
                $('select[id="patient_casualty"] option[value="' + data.casualty + '"]').attr("selected", "selected");
                $('select[id="old"] option[value="' + data.old_patient + '"]').attr("selected", "selected");
                $('select[id="genders"] option[value="' + data.gender + '"]').attr("selected", "selected");
                $('select[id="marital_statuss"] option[value="' + data.marital_status + '"]').attr("selected", "selected");
                $('select[id="organisations"] option[value="' + data.organisation + '"]').attr("selected", "selected");
                $("#patient_consultant").select2().select2('val', data.cons_doctor);
                $("#evaddpatient_id").select2().select2('val', data.patient_id);
                holdModal('myModaledit');
            },
        });
    }

    function getEditRecordDischarged(id, ipdid) {       
        var active = '<?php echo $result['is_active'] ?>';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getIpdDetails',
            type: "POST",
            data: {recordid: id, ipdid: ipdid, active: active},
            dataType: 'json',
            success: function (data) {
                $('#disevlistname').html(data.patient_name);
                $('#disevguardian').html(data.guardian_name);
                $('#disevlistnumber').html(data.mobileno);
                $('#disevemail').html(data.email);
                if (data.age == "") {
                    $("#disevage").html("");
                } else {
                    if (data.age) {
                        var age = data.age + " " + "Years";
                    } else {
                        var age = '';
                    }
                    if (data.month) {
                        var month = data.month + " " + "Month";
                    } else {
                        var month = '';
                    }
                    if (data.dob) {
                        var dob = "(" + data.dob + ")";
                    } else {
                        var dob = '';
                    }

                    $("#disevage").html(age + "," + month + " " + dob);
                }
                $("#disevaddress").html(data.address);
                $("#disenote").html(data.note);
                $("#disevgenders").html(data.gender);
                $("#disevmarital_status").html(data.marital_status);
                $("#disedit_admission_date").html(data.date);
                $("#disedit_discharge_date").html(data.discharge_date);
                $("#disipdid").val(data.ipdid);
                $("#disupdateid").val(data.summary_id);
                $("#disevpatients_id").val(data.patient_id);
                $("#disinvestigations").val(data.summary_investigations);
                $("#disevnoteipd").val(data.summary_note);
                $("#disdiagnosis").val(data.disdiagnosis);
                $("#disoperation").val(data.disoperation);
                $("#distreatment_at_home").val(data.summary_treatment_home);
                 $('#summary_print').html("<?php if ($this->rbac->hasPrivilege('discharged_summary', 'can_view')) { ?><a href='#' data-toggle='tooltip' onclick='printData(" + data.summary_id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a> <?php } ?>");               
                holdModal('myModaldischarged');
            },
        });
    }

     function printData(insert_id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/patient/getsummaryDetails/' + insert_id,
            type: 'POST',
            data: {id: insert_id, print: 'yes'},
            success: function (result) {
                popup(result);
            }
        });
    }

    function get_ePatientDetails(id) {
        var base_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        $("#ajax_load").html("<center><img src='" + base_url + "'/>");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/patientDetails',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (res) {
                if (res) {
                    $("#evajax_load").html("");
                    $("#evpatientDetails").show();
                    $('#evpatient_unique_id').html(res.patient_unique_id);
                    $('#evlistname').html(res.patient_name);
                    $('#evpatients_id').val(res.id);
                    $('#evguardian').html(res.guardian_name);
                    $('#evlistnumber').html(res.mobileno);
                    $('#evemail').html(res.email);
                    if (res.age == "") {
                        $("#evage").html("");
                    } else {
                        if (res.age) {
                            var age = res.age + " " + "Years";
                        } else {
                            var age = '';
                        }
                        if (res.month) {
                            var month = res.month + " " + "Month";
                        } else {
                            var month = '';
                        }
                        if (res.dob) {
                            var dob = "(" + res.dob + ")";
                        } else {
                            var dob = '';
                        }

                        $("#evage").html(age + "," + month + " " + dob);
                    }
                    $('#evdoctname').val(res.name + " " + res.surname);
                    $("#evbp").html(res.bp);
                    $("#esymptoms").html(res.symptoms);
                    $("#evknown_allergies").html(res.known_allergies);
                    $("#evaddress").html(res.address);
                    $("#evnote").html(res.note);
                    $("#evgenders").html(res.gender);
                    $("#evmarital_status").html(res.marital_status);
                    $("#evblood_group").html(res.blood_group);
                    $("#evallergies").html(res.known_allergies);
                    $("#evimage").attr("src", '<?php echo base_url() ?>' + res.image);
                } else {
                    $("#evajax_load").html("");
                    $("#evpatientDetails").hide();
                }
            }
        });
    }

    $(document).ready(function (e) {
        $("#formeditrecord").on('submit', (function (e) {
            $("#formeditrecordbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/ipd_update',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#formeditrecordbtn").button('reset');
                },
                error: function () {
                    //  alert("Fail")
                }
            });
        }));
    });

     $(document).ready(function (e) {
        $("#formdishrecord").on('submit', (function (e) {
            $("#formdishrecordbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_discharged_summary',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#formdishrecordbtn").button('reset');
                },
                error: function () {
                    //  alert("Fail")
                }
            });
        }));
    });

    $(document).ready(function (e) {
        $("#alot_bed_form").on('submit', (function (e) {
            $("#alotbedbtn").button('loading');

            e.preventDefault();
            var bedid = $("#alotbedoption").val();
            var billid = '<?php echo $result["bill_id"] ?>';
            var ipdid = '<?php echo $ipdid ?>';
            var patient_id = '<?php echo $result["id"] ?>';
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/updatebed',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        revert(patient_id, billid, bedid, ipdid)
                        //window.location.reload(true);
                    }
                    $("#alotbedbtn").button('reset');
                },
                error: function () {
                    //  alert("Fail")
                }
            });
        }));
    });

    function editRecord(id, opdid) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/opd_details',
            type: "POST",
            data: {recordid: id, opdid: opdid},
            dataType: 'json',
            success: function (data) {
                $("#patientid").val(data.patient_unique_id);
                $("#patientname").val(data.patient_name);
                $("#appointmentdate").val(data.appointment_date);
                $("#edit_case").val(data.case_type);
                $("#edit_symptoms").val(data.symptoms);
                $("#edit_casualty").val(data.casualty);
                $("#edit_knownallergies").val(data.known_allergies);
                $("#edit_refference").val(data.refference);
                $("#edit_consdoctor").val(data.cons_doctor);
                $("#edit_amount").val(data.amount);
                $("#edit_tax").val(data.tax);
                $("#edit_paymentmode").val(data.payment_mode);
                $("#edit_opdid").val(opdid);
            },
        });
    }

    $(document).ready(function (e) {
        $("#add_payment").on('submit', (function (e) {
            e.preventDefault();
            $("#add_paymentbtn").button("loading");
            $.ajax({
                url: '<?php echo base_url(); ?>admin/payment/create',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#add_paymentbtn").button("reset");
                }, error: function () {}
            });
        }));
    });


    $(document).ready(function (e) {
        $("#formedit").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/opd_detail_update',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                }, error: function () {}
            });
        }));
    });

    $(document).ready(function (e) {
        $("#add_charges").on('submit', (function (e) {
            e.preventDefault();
            $("#add_chargesbtn").button('loading');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/charges/add_ipdcharges',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#add_chargesbtn").button('reset');
                },
                error: function () {}
            });
        }));
    });
    function getBed(bed_group, bed = '', active, htmlid = 'bed_nos') {

        var div_data = "";
        $('#' + htmlid).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $("#" + htmlid).select2("val", 'l');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/setup/bed/getbedbybedgroup',
            type: "POST",
            data: {bed_group: bed_group, bed_id: bed, active: active},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    if (bed == obj.id) {
                        sel = "selected";
                    }
                    div_data += "<option " + sel + " value=" + obj.id + ">" + obj.name + "</option>";
                });
                $("#" + htmlid).html("<option value=''>Select</option>");
                $('#' + htmlid).append(div_data);
                $("#" + htmlid).select2().select2('val', bed);
            }
        });
    }

    $(document).ready(function (e) {
        $("#form_prescription").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_ipdprescription',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                },
                error: function () {}
            });
        }));
    });
    $(document).ready(function (e) {
        $("#form_diagnosis").on('submit', (function (e) {
            e.preventDefault();
            $("#form_diagnosisbtn").button('loading');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_diagnosis',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#form_diagnosisbtn").button('reset');
                },
                error: function () {}
            });
        }));
    });

     $(document).ready(function (e) {
        $("#form_editdiagnosis").on('submit', (function (e) {
            e.preventDefault();
            $("#form_editdiagnosisbtn").button('loading');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/update_diagnosis',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#form_diagnosisbtn").button('reset');
                },
                error: function () {}
            });
        }));
    });

    function getMedicineName(id) {

        var category_selected = $("#medicine_cat" + id).val();
        var arr = category_selected.split('-');
        var category_set = arr[0];

        div_data = '';

        $("#search-query" + id).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $('#search-query' + id).select2("val", +id);

        $.ajax({
            type: "POST",
            url: base_url + "admin/pharmacy/get_medicine_name",
            data: {'medicine_category_id': category_selected},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.medicine_name + "'>" + obj.medicine_name + "</option>";
                });
                $("#search-query" + id).html("<option value=''>Select</option>");
                $('#search-query' + id).append(div_data);
                $('#search-query' + id).select2("val", '');
                getMedicineDosage(id);
            }
        });

    }
    


    function getMedicineDosage(id) {
        //  alert(category_selected)
        // alert(id);
        var category_selected = $("#medicine_cat" + id).val();
        var arr = category_selected.split('-');
        var category_set = arr[0];
        // alert(category_selected);
        div_data = '';

        $("#search-dosage" + id).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $('#search-dosage' + id).select2("val", +id);

        $.ajax({
            type: "POST",
            url: base_url + "admin/pharmacy/get_medicine_dosage",
            data: {'medicine_category_id': category_selected},
            dataType: 'json',
            success: function (res) {

                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.dosage + "'>" + obj.dosage + "</option>";

                });
                $("#search-dosage" + id).html("<option value=''>Select</option>");
                $('#search-dosage' + id).append(div_data);
                //$("#search-dosage" + id).select2();
                $('#search-dosage' + id).select2("val", '');

            }
        });

    }
    function editDiagnosis(id) {
        //alert(patient_id);
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/editDiagnosis',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#eid").val(data.id);
                $("#epatient_id").val(data.patient_id);
                $("#ereporttype").val(data.report_type);
                $("#ereportdate").val(data.report_date);
                //$("#ereportdocument").val(data.document);
                $("#edescription").val(data.description);
                //$("#add_diagnosis").modal('hide');
                holdModal('edit_diagnosis');

            },
        });
    }

    function add_more() {

        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var div = "<div id=row1><div class='col-sm-3 col-xs-6'><select class='form-control select2' onchange='getMedicineName(" + id + ")' name='medicine_cat[]'  id='medicine_cat" + id + "'><option value='<?php echo set_value('medicine_category_id'); ?>'><?php echo $this->lang->line('select') ?></option><?php foreach ($medicineCategory as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["medicine_category"] ?></option><?php } ?></select></div><div class='col-sm-3 col-xs-6'><div class=form-group><select class='form-control select2'  name='medicine[]' id='search-query" + id + "'><option value='l'><?php echo $this->lang->line('select') ?></option></select></div></div><div class='col-sm-3 col-xs-6'><div class=form-group><select  class='form-control select2' name='dosage[]' id='search-dosage" + id + "'><option value='l'><?php echo $this->lang->line('select') ?></option></select></div></div><div class='col-sm-3 col-xs-6'><div class=form-group><textarea style='height:28px' name='instruction[]' class=form-control id=description></textarea></div></div></div>";

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'><td>" + div + "</td><td><button type='button' onclick='delete_row(" + id + ")' class='modaltableclosebtn'><i class='fa fa-remove'></i></button></td></tr>";
        $('.select2').select2();
    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
        //table.deleteRow(id);
    }

    $(document).ready(function (e) {
        $("#add_timeline").on('submit', (function (e) {
            var patient_id = $("#patient_id").val();
            e.preventDefault();
            $("#add_timelinebtn").button('loading');
            $.ajax({
                url: "<?php echo site_url("admin/timeline/add_patient_timeline") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        $.ajax({
                            url: '<?php echo base_url(); ?>admin/timeline/patient_timeline/' + patient_id,
                            success: function (res) {
                                $('#timeline_list').html(res);
                                $('#myTimelineModal').modal('toggle');
                            },
                            error: function () {
                                alert("Fail")
                            }
                        });
                        window.location.reload(true);
                    }
                    $("#add_timelinebtn").button('reset');
                },
                error: function (e) {
                    alert("Fail");
                    //console.log(e);
                }
            });
        }));
    });

    $(document).ready(function (e) {
        $("#add_bill").on('submit', (function (e) {
            if (confirm('<?php echo $this->lang->line('confirm')?>')) {
                $("#save_button").button('loading');
                e.preventDefault();
                $.ajax({
                    url: "<?php echo site_url("admin/payment/addbill") ?>",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function (index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.href = '<?php echo base_url(); ?>admin/patient/discharged_patients';
                        }
                        $("#save_button").button('reset');

                    },
                    error: function (e) {
                        alert("Fail");
                        // console.log(e);
                    }
                });
            } else {
                return false;
            }

        }));
    });


    function delete_timeline(id) {
        var patient_id = $("#patient_id").val();
        if (confirm('<?php echo $this->lang->line("delete_conform") ?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/timeline/delete_patient_timeline/' + id,
                success: function (res) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/timeline/patient_timeline/' + patient_id,
                        success: function (res) {
                            $('#timeline_list').html(res);
                            successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                        },
                        error: function () {
                            alert("Fail")
                        }
                    });
                }, error: function () {
                    alert("Fail")
                }
            });
        }
    }
    $(document).ready(function (e) {

        $(function () {
            var hash = window.location.hash;
            hash && $('ul.nav-tabs a[href="' + hash + '"]').tab('show');

            $('.nav-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollmem = $('body').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });


    });

    function editTimeline(id) {
        // alert(id);
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/editTimeline',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy',]) ?>';
                var dt = new Date(data.timeline_date).toString(date_format);
                $("#etimelineid").val(data.id);
                $("#epatientid").val(data.patient_id);
                $("#etimelinetitle").val(data.title);
                $("#etimelinedate").val(dt);
                //$("#ereportdocument").val(data.document);
                $("#timelineedesc").val(data.description);
                if (data.status == '') {
                    //$("#evisible_check").attr('checked', false);
                } else
                {
                    $("#evisible_check").attr('checked', true);
                }
                //$("#add_diagnosis").modal('hide');
                holdModal('myTimelineEditModal');

            },
        });
    }

    $(document).ready(function (e) {
        $("#edit_timeline").on('submit', (function (e) {
            $("#edit_timelinebtn").button('loading');
            var patient_id = $("#patient_id").val();
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/timeline/edit_patient_timeline") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#edit_timelinebtn").button('reset');
                },
                error: function (e) {
                    alert("Fail");
                    console.log(e);
                }
            });
        }));
    });

    function edit_prescription(id, ipdid) {
//console.log(id);
        $.ajax({
            url: '<?php echo base_url(); ?>admin/prescription/editipdPrescription/' + id + '/' + ipdid,
            success: function (res) {
                $('#prescriptionview').modal('hide');
                $("#editdetails_prescription").html(res);
            },
            error: function () {
                alert("Fail") }
        });
    }

    function view_prescription(id, ipdid, discharged='') {
        console.log(discharged);


        $.ajax({
            url: '<?php echo base_url(); ?>admin/prescription/getIPDPrescription/' + id + '/' + ipdid,
            success: function (res) {
                $("#getdetails_prescription").html(res);
            },
            error: function () {
                alert("Fail")
            }
        });

        if(discharged != "yes"){
         $('#edit_deleteprescription').html("<?php if ($this->rbac->hasPrivilege('ipd_prescription', 'can_view')) { ?><a href='#prescription'' onclick='printprescription(" + id + "," + ipdid + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?><?php if ($this->rbac->hasPrivilege('ipd_prescription', 'can_edit')) { ?><a href='#prescription'' onclick='edit_prescription(" + id + "," + ipdid + ")' data-target='#edit_prescription' data-toggle='modal'  data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php }  if ($this->rbac->hasPrivilege('ipd_prescription', 'can_delete')) { ?><a onclick='delete_prescription(" + id + "," + ipdid + ")'  href='#'  data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php } ?>");
        }else{
        $('#edit_deleteprescription').html("<?php if ($this->rbac->hasPrivilege('ipd_prescription', 'can_view')) { ?><a href='#prescription'' onclick='printprescription(" + id + "," + ipdid + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?>");
        }


        holdModal('prescriptionview');
    }

    function getcharge_category(id) {
        var div_data = "";
        //alert(id);
        //   $("#charge_category").select2().select2('val', '');
        // $("#charge_category").html("<option value=''>Select</option>");
        $('#charge_category').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $("#charge_category").select2("val", 'l');


        $.ajax({
            url: '<?php echo base_url(); ?>admin/charges/get_charge_category',
            type: "POST",
            data: {charge_type: id},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.name + "'>" + obj.name + "</option>";
                });
                $('#charge_category').html("<option value=''>Select</option>");
                $('#charge_category').append(div_data);
                $("#charge_category").select2("val", '');
            }
        });
    }


    function get_Charges(code, orgid) {
        $("#standard_charge").html("standard_charge");
        $("#schedule_charge").html("schedule_charge");

        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/ipdCharge',
            type: "POST",
            data: {code: code, organisation_id: orgid},
            dataType: 'json',
            success: function (res) {
               // console.log(res);
                if (res) {

                    $('#standard_charge').val(res.standard_charge);
                    $('#schedule_charge').val(res.org_charge);
                    $('#charge_id').val(res.id);
                    $('#org_id').val(res.org_charge_id);

                    if (res.org_charge == null) {
                        $('#apply_charge').val(res.standard_charge);
                    } else {
                        $('#apply_charge').val(res.org_charge);
                    }
                } else {
                    //  $('#standard_charge').val('0');
                    // $('#schedule_charge').val('0');
                    // $('#charge_id').val('0');
                    // $('#org_id').val('0');
                }
            }
        });
    }

    function getchargecode(charge_category) {
        var div_data = "";
        $('#code').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $("#code").select2("val", 'l');


        $.ajax({
            url: '<?php echo base_url(); ?>admin/charges/getchargeDetails',
            type: "POST",
            data: {charge_category: charge_category},
            dataType: 'json',
            success: function (res) {
                //alert(res)
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.code + " - " + obj.description + "</option>";

                });

                $('#code').html("<option value=''>Select</option>");

                $('#code').append(div_data);
                $("#code").select2("val", '');

                $('#standard_charge').val('');
                $('#apply_charge').val('');
            }
        });
    }
    // function calculate() {

    //     var discount_percent = $("#discount_percent").val();
    //     var tax_percent = $("#tax_percent").val();
    //     var total_amount = $("#total_amount").val();
    //     var discount = $("#discount").val();
    //      var tax = $("#tax").val();
    //      var other_charge = $("#other_charge").val();

    //      if(discount == ''){
    //          $("#discount").val(0);
    //     }
        
    //     if(tax == ''){
    //          $("#tax").val(0);
    //     }

    //     if(other_charge == ''){
    //          $("#other_charge").val(0);
    //     }

    //     if (discount_percent != '') {
    //         var discount = (total_amount * discount_percent) / 100;
    //         $("#discount").val(discount.toFixed(2));
    //     } else {
    //         var discount = $("#discount").val();

    //     }

    //     if (tax_percent != '') {
    //         var tax = ((total_amount - discount) * tax_percent) / 100;
    //         $("#tax").val(tax.toFixed(2));
    //     } else {
    //         var tax = $("#tax").val();

    //     }

    //     var other_charge = $("#other_charge").val();
    //     //var gross_total = $("#gross_total").val();
    //     // var net_amount = $("#net_amount").val();
    //     var gross_total = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax);
    //     var net_amount = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
    //     $("#gross_total").val(gross_total.toFixed(2));
    //     $("#grass_amount_span").html(net_amount.toFixed(2));
    //     $("#net_amount").val(net_amount.toFixed(2));
    //     $("#net_amount_span").html(net_amount.toFixed(2));
    //     $("#save_button").show();
    //     $("#printBill").show();
    // }
     function calculate() {

        var discount_percent = $("#discount_percent").val();
        var tax_percent = $("#tax_percent").val();
        var other_charge = $("#other_charge").val();
        var paid_amount = $("#paid_amountpa").val();

       var total_amount = $("#total_amount").val();

        var subtotal_amount = parseFloat(total_amount) + parseFloat(other_charge);

        //console.log(paid_amount);

        if (discount_percent != '') {
            var discount = (subtotal_amount * discount_percent) / 100;
            $("#discount").val(discount.toFixed(2));
        } else {
            var discount = $("#discount").val();

        }

        if (tax_percent != '') {
            var tax = ((subtotal_amount - discount) * tax_percent) / 100;
            $("#tax").val(tax.toFixed(2));
        } else {
            var tax = $("#tax").val();
        }

         var gross_total = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
         var net_amount = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
         var net_amount_payble = parseFloat(net_amount) - parseFloat(paid_amount);
         $("#gross_total").val(gross_total.toFixed(2));
        
         $("#grass_amount").val(net_amount.toFixed(2));
         $("#grass_amount_span").html(net_amount.toFixed(2));
         $("#net_amount").val(net_amount_payble.toFixed(2));
         $("#net_amount_span").html(net_amount_payble.toFixed(2));
         $("#net_amount_payble").val(net_amount_payble.toFixed(2));
         $("#save_button").show();
         $("#printBill").show();
    }
    function revert(patient_id, billid, bedid, ipdid) {


        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/revertBill',
            type: "POST",
            data: {patient_id: patient_id, bill_id: billid, bed_id: bedid, ipdid: ipdid},
            dataType: 'json',
            success: function (res) {
                if (res.status == "fail") {
                    var message = "";
                    errorMsg(res.message);
                } else {
                    successMsg(res.message);
                    window.location.href = '<?php echo base_url() ?>admin/patient/ipdsearch';
                }
            }
        });
        // window.location.reload(true);
        // $( "#tabs" ).tabs({ active: 'charges' });

    }

     

    function checkbed(patient_id, billid, bedid,ipdid) {
        var v = 'false';
        if (confirm('<?php echo $this->lang->line('are_you_sure')?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/setup/bed/checkbed',
                type: "POST",
                data: {bed_id: bedid},
                dataType: 'json',
                success: function (res) {

                    if (res.status == "fail") {
                        $("#alot_bed").modal('show');
                    } else {
                        revert(patient_id, billid, bedid,ipdid)
                    }

                }
            });

        }

    }


    $(document).ready(function (e) {
        $("#consultant_register_form").on('submit', (function (e) {
//var student_id = $("#student_id").val();
//alert("hii");
             var doctor_id = $("#doctor_field").val();
             $("#doctor_set").val(doctor_id);
   

            $("#consultant_registerbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_consultant_instruction',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {

                    if (data.status == "fail") {

                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);
                        window.location.reload(true);

                    }
                    $("#consultant_registerbtn").button('reset');

                },
                error: function () {
                    //  alert("Fail")
                }
            });


        }));
    });


    function add_consultant_row() {



        var table = document.getElementById("constableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);

        var div = "<td><input type='text' name='date[]' class='form-control datetime'></td><td><select name='doctor[]'  class='select2' style='width:100%'><option value=''><?php echo $this->lang->line('select') ?></option><?php foreach ($doctors as $key => $value) { ?><option value='<?php echo $value["id"] ?>'><?php echo $value["name"] . " " . $value["surname"] ?></option><?php } ?></select></td><td><textarea name='instruction[]' style='height:28px' class='form-control'></textarea></td><td><input type='text' name='insdate[]' class='form-control date'></td>";

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div + "<td><button type='button' onclick='delete_consultant_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";

        $('.select2').select2();


    }

    function delete_consultant_row(id) {

        var table = document.getElementById("constableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
//table.deleteRow(id);
    }

</script>
<script type="text/javascript">

    function deletePatient(id) {
        if (confirm('<?php echo $this->lang->line('delete_conform') ?>')) {
            $.ajax({
                url: base_url + 'admin/patient/deleteIpdPatient/' + id,
                type: 'POST',
                data: {patient_id: id},
                success: function (data) {
                    if (data.status == "fail") {

                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);
                        window.location.href = '<?php echo base_url() . "admin/patient/ipdsearch" ?>';

                    }
                }
            });
        }
    }
    function printBill(patientid, ipdid) {
        var total_amount = $("#total_amount").val();
        var discount = $("#discount").val();
        var other_charge = $("#other_charge").val();
        var gross_total = $("#gross_total").val();
        var tax = $("#tax").val();
        var net_amount = $("#net_amount").val();
        var status = $("#status").val();
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payment/getBill/',
            type: 'POST',
            data: {patient_id: patientid, ipdid: ipdid, total_amount: total_amount, discount: discount, other_charge: other_charge, gross_total: gross_total, tax: tax, net_amount: net_amount, status: status},
            success: function (result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }
    function popup(data)
    {
        var base_url = '<?php echo base_url() ?>';
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
        return true;
    }
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

    function delete_record(url, Msg) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_conform') . "'"; ?>)) {
            $.ajax({
                url: url,
                success: function (res) {
                    successMsg(Msg);
                    window.location.reload(true);
                }
            })
        }
    }



    function printprescription(id, opdid) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/prescription/getIPDPrescription/' + id + '/' + opdid,
            type: 'POST',
            data: {payslipid: id, print: 'yes'},
            //dataType: "json",
            success: function (result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }

    $(function () {
        $("#compose-textareas,#compose-textareanew").wysihtml5({
            toolbar: {
                "image": false,
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change','.chgstatus_dropdown',function(){
        $(this).parent('form.chgstatus_form').submit()

    });

    $("form.chgstatus_form").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           dataType:"JSON",
           success: function(data)
           {
               if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    successMsg(data.message);

                    window.location.reload(true);
                }              
           }
         });
	});	
	
$(".addconsultant").click(function(){		
	$('#consultant_register_form').trigger("reset");
	var table = document.getElementById("constableID");
    var table_len = (table.rows.length);	
	for (i = 1; i < table_len; i++) {			
		delete_consultant_row(i);
	}
});

$(".adddiagnosis").click(function(){		
	$('#form_diagnosis').trigger("reset");
	$(".dropify-clear").trigger("click");
});

$(".addtimeline").click(function(){		
	$('#add_timeline').trigger("reset");
	$(".dropify-clear").trigger("click");
});

$(".addpayment").click(function(){		
	$('#add_payment').trigger("reset");
	$(".dropify-clear").trigger("click");
});

$(".addcharges").click(function(){		
	$('#add_charges').trigger("reset");
	$('#select2-charge_category-container').html("");
	$('#select2-code-container').html("");
});

$(".addprescription").click(function(){		
	$('#form_prescription').trigger("reset");
	//$('#select2-medicine_cat0-container').html('');
	$('#select2-search-query0-container').html('');
	$('#select2-search-dosage0-container').html('');
	var table = document.getElementById("tableID");
    var table_len = (table.rows.length);	
	for (i = 1; i < table_len; i++) {			
		delete_consultant_row(i);
	}
});	
</script>
<!-- <script type="text/javascript">
    $(document).on('click', '.add-btn', function () {
        var s = "";
        s += "<div class='row'>";
        s += "<input name='rows[]' type='hidden' value='" + rows + "'>";
        s += "<div class='col-md-6'>";
        s += "<div class='form-group'>";
        s += "<label for='act'>Act</label>";
        s += "<select class='form-control act select2' id='act' name='act" + rows + "' data-row_id='" + rows + "'>";
        s += "<option value=''>--Select--</option>";
        s += $('#act-template').html();
        s += "</select>";
        s += "<small class='text text-danger help-inline'></small>";
        s += "</div>";
        s += "</div>";
        s += "<div class='col-md-5'>";
        s += "<label for='validationDefault02'>Section</label>";
        s += "<div id='dd' class='wrapper-dropdown-3'>";
        s += "<input class='form-control filterinput' type='text'>";
        s += "<ul class='dropdown scroll150 section_ul'>";
        s += "<li><label class='checkbox'>--Select--</label></li>";
        s += "</ul>";
        s += "</div>";
        s += "</div>";
        s += "<div class='col-md-1'>";
        s += "<div class='form-group'>";
        s += "<label for='removebtn'>&nbsp;</label>";
        s += "<button type='button' class='form-control btn btn-sm btn-danger remove_row'><i class='fa fa-remove'></i></button>";
        s += "</div>";
        s += "</div>";
        s += "</div>";
        $(".multirow").append(s);
        $('.select2').select2();
        link = 2;
        rows++;
    });
</script>

<script type="text/html" id="act-template">

    
   <?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                            ?>
        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["symptoms_type"] ;?></option> 
        <?php
    }
    ?>
</script>  -->