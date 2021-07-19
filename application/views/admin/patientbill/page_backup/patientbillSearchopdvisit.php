<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">   
   
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bill Payment --r</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/patientbill/search') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <input name="patient_id" id="patient_id" type="hidden" class="form-control" />

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('patient'); ?></label><small class="req">  *</small>
                                                <select onchange="get_PatientDetails(this.value)"  class="form-control select2" <?php
                                                    //if ($disable_option == true) {
                                                        //echo "disabled";
                                                   // }
                                                    ?> style="width:100%" name='' id="addpatient_id" >
                                                        <option value=""><?php echo $this->lang->line('select') . " " . $this->lang->line('patient') ?></option>
                                                        <?php foreach ($patients as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                            if ((isset($patient_select)) && ($patient_select == $dvalue["id"])) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo $dvalue["patient_name"] . " (" . $dvalue["patient_unique_id"] . ')' ?></option>   
                                                    <?php } ?>
                                                    </select>
                                                <span class="text-danger"><?php echo form_error('patient_id'); ?></span>
                                            </div> 
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Select modules --r</label>
                                                <select name='select_group' id="" onchange="getopdipd(this.value)" class="form-control"  style="width:100%" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>                           
                                                        <option value="<?php echo $this->lang->line('opd')?>" ><?php echo $this->lang->line('opd') ?></option>
                                                        <option value="<?php echo $this->lang->line('ipd')?>" ><?php echo $this->lang->line('ipd') ?></option>                                                    
                                                </select>    
                                                <span class="text-danger"><?php echo form_error('select_group'); ?></span>
                                            </div> 
                                        </div>
                                         <!--  <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                        <?php echo $this->lang->line('opd') . " / " .$this->lang->line('ipd')." ". $this->lang->line('number'); ?></label><small class="req"> *</small> 
                                                <div>
                                                <select class="form-control" style="width: 100%" name='opdipd_id' id='opdipd_no'>
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>  </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('opdipd_no'); ?></span>
                                            </div>
                                        </div>    -->   
                                        <div class="col-sm-12">  
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>  
                            </div>
                          
                        </div>
                    </div>
                
               <?php
                //if (isset($resultlist)) {
                    ?> 
                    <div class="">
                       <div class="box-header ptbnull"></div> 
                        <div class="box-header ptbnull">
                            <!-- <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('list'); ?>
                                <?php echo form_error('student'); ?></h3> -->
                            <div class="box-tools pull-right"></div>
                        </div>
                        <div class="box-body table-responsive">
                          
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="round5" src="<?php
                                        if (!empty($result['image'])) {
                                            echo base_url() . $result['image'];
                                        } else {
                                            echo base_url() . "uploads/student_images/no_image.png";
                                        }
                                        ?>" alt="No Image">
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td> <?php echo $result['patient_name']; ?> </td>

                                                        <th class="bozero"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                                       <td> <?php echo $result['patient_unique_id']; ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo  $this->lang->line('phone'); ?></th>
                                                        <td> <?php echo $result['mobileno']; ?> </td>
                                                        <th><?php echo $this->lang->line('email'); ?></th>
                                                      <td> <?php echo $result['email']; ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('blood_group'); ?></th>
                                                        <td> <?php echo $result['blood_group']; ?> </td>
                                                        
                                                        <th><?php echo $this->lang->line('address'); ?></th>
                                                         <td> <?php echo $result['address']; ?>
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th><?php echo $this->lang->line('bill')." ".$this->lang->line('status'); ?></th>
                                                        <td> <?php if ($result['discharged'] =='yes') {
                                                           echo "<span class='label label-success'>".$this->lang->line('paid')."</span>";
                                                        }else{
                                                             echo "<span class='label label-danger'>".$this->lang->line('unpaid')."</span>";
                                                        } ?> </td>
                                                        
                                                       
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <div class="">
    
        <div class="row">
            <div class="col-md-12 itemcol">
                <div class="nav-tabs-custom relative">
                    <!-- <a href="#" class="dshow arrow-angle"><i class="fa fa-angle-right"></i></a>
                    <a href="#" class="dhide arrow-angle" style="display: none;"><i class="fa fa-angle-left"></i></a> -->
                    <ul class="nav nav-tabs">
                        <?php if ($this->rbac->hasPrivilege('revisit', 'can_view')) { ?>
                            <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><i class="far fa-caret-square-down"></i> <?php echo $this->lang->line('visits'); ?></a></li>

                            <?php
                        }
                        if ($this->rbac->hasPrivilege('opd_charges', 'can_view')) {
                            ?>
                            <li><a href="#charges" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('charges'); ?></a></li>
                            <?php
                        }
                        ?>
                        <?php if ($this->rbac->hasPrivilege('opd_payment', 'can_view')) {
                            ?>
                            <li><a href="#payment" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('payment'); ?></a></li>
                            <?php
                        }
                        if ($this->rbac->hasPrivilege('opd_bill', 'can_view')) {
                            ?>
                            <li><a href="#bill" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('bill'); ?></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <div class="tab-content">
                        <?php if ($this->rbac->hasPrivilege('revisit', 'can_view')) { ?>
                            <div class="tab-pane active" id="activity">
                                <!-- <div class="impbtnview">
                                    <?php if ($this->rbac->hasPrivilege('revisit', 'can_add')) { if($result['discharged'] !='yes'){?>

                                        <a href="#"  onclick="getRevisitRecord('<?php echo $result['id'] ?>')" class="btn btn-primary btn-sm revisitrecheckup"  data-toggle="modal" title=""><i class="fas fa-exchange-alt"></i> <?php echo $this->lang->line('re_checkup'); ?>
                                        </a>
                                    <?php }} ?>
                                </div> -->
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('opd') . " " . $this->lang->line('details'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                        <th><?php echo $this->lang->line('opd_no'); ?></th>
                                        <th><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('consultant'); ?></th>
                                       
                                              
                                        </thead>
                                        <tbody>
                                            <?php
                                            $visit_charge = 0;
                                            if (!empty($opd_details)) {
                                                foreach ($opd_details as $key => $value) {
                                                    if ($value["id"] == $visit_id) {
                                                        $visit_charge += $value['amount'];
                                                        ?>  
                                                        <tr>
                                                            <td><?php echo $value['opd_no']; ?></td>
                                                            <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($value['appointment_date'])) ?></td>
                                                            <td><?php echo $value["name"] . " " . $value["surname"]; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?> 

                                            <?php
                                            $revisit_charge = 0;
                                            if (!empty($revisit_details)) {

                                                foreach ($revisit_details as $key => $revisit) {
                                                    $revisit_charge += $revisit['amount'];
                                                    ?>

                                                    <tr>
                                                        <td><?php echo $revisit['opd_no']; ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($revisit['appointment_date'])) ?></td>
                                                        <td><?php echo $revisit["name"] . " " . $revisit["surname"]; ?></td>
                                                       
                                                    </tr>
                                                <?php }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
<?php } ?>

                        <!-- -->
                             

                        <!-- Charges -->
                                <?php if ($this->rbac->hasPrivilege('opd_charges', 'can_view')) { ?>
                            <div class="tab-pane" id="charges">
                                <div class="impbtnview">
    <?php if ($this->rbac->hasPrivilege('opd_charges', 'can_add')) { if ($result['discharged'] !='yes'){?>
                                        <a data-toggle="modal" onclick="holdModal('add_chargeModal')" class="btn btn-primary btn-sm addcharges"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') ?> <?php echo $this->lang->line('charges'); ?></a>
    <?php }} ?>
                                </div>
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('opd') . " " . $this->lang->line('details'); ?></div>
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
                                            if (!empty($charges_detail)) {
                                                foreach ($charges_detail as $charges_key => $charges_value) {
                                                    $total += $charges_value["apply_charge"];
                                                   
                                                    ?>  
                                                    <tr>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($charges_value['date'])); ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $charges_value["charge_type"] ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $charges_value["charge_category"] ?></td>
                                                        <td class="text-right"><?php echo $charges_value["standard_charge"] ?></td>
                                                        <td class="text-right"><?php echo $charges_value["org_charge"] ?></td>
                                                        <td class="text-right"><?php echo $charges_value["apply_charge"] ?></td>
                                                        <td class="text-right"> 
                                                            <?php if ($this->rbac->hasPrivilege('opd_charges', 'can_delete')) { ?>
                                                                <a onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteOpdPatientCharge/<?php echo $charges_value['patient_id']; ?>/<?php echo $charges_value['opd_id']; ?>/<?php echo $charges_value['id']; ?>', '<?php echo $this->lang->line('delete_message') ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a> 
                                                    <?php } ?>   
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?> 

                                        </tbody>

                                        <tr class="box box-solid total-bg">
                                            <td colspan='6' class="text-right"><?php echo $this->lang->line('total') . " : " . $currency_symbol . "" . $total ?> <input type="hidden" id="charge_total" name="charge_total" value="<?php echo $total ?>">
                                            </td><td></td>
                                        </tr>
                                    </table>
                                </div> 
                            </div>    
                            <!-- -->  
                            <!--payment -->
                            <?php } if ($this->rbac->hasPrivilege('opd_payment', 'can_view')) {
                                ?>
                            <div class="tab-pane" id="payment">
                                <?php
                                if ($this->rbac->hasPrivilege('opd_payment', 'can_add')) {
                                     if ($result['discharged'] != 'yes') {
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

                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
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
                                                            <?php if ($this->rbac->hasPrivilege('opd_payment', 'can_delete')) { ?>
                                                                <a href="<?php echo base_url(); ?>admin/patient/deleteOpdPatientPayment/<?php echo $payment['patient_id']; ?>/<?php echo $payment['id']; ?>/<?php echo $payment['opd_id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_conform') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>   
                                                    <?php } ?>
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
                            <!-- -->
                                <?php } ?>
                         
                        <!-- -->

                           

                       

                    <?php if ($this->rbac->hasPrivilege('opd_bill', 'can_view')) { ?>         
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
                                                if (!empty($charges_detail)) {
                                                    foreach ($charges_detail as $key => $charge) {
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
                                                if (!empty($payment_details)) {
                                                    foreach ($payment_details as $key => $payment) {
                                                        ?>
                                                        <tr>
                                                            <td class="pttleft" style="text-transform: capitalize;"><?php echo $payment["payment_mode"]; ?></td>
                                                            <td class=""><?php echo date($this->customlib->getSchoolDateFormat(true, false), strtotime($payment['date'])) ?></td>
                                                            <td class="text-right"><?php echo $payment["paid_amount"]; ?></td>

                                                        </tr>
                                                        <?php
                                                        $total_paid += $payment["paid_amount"];
                                                    }
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
                                                    <?php
                                                    
                                                    if ($billstatus["status"] != "paid")  {
                                                        ?> 
                                                        <tr>
                                                        <th><?php echo $this->lang->line('consultant') . " " . $this->lang->line('charges') . " (" . $this->lang->line('paid') . ")" . " (" . $currency_symbol . ")" ; ?></th> 
                                                        <td class="text-right fontbold20"><?php echo $visit_charge+ $revisit_charge; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total ; ?></td>
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

                                                         <!--<tr>
                                                            <th><?php echo $this->lang->line('any_other_charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable"><input type="text" id="other_charge" value="<?php
                                                                if (!empty($result["other_charge"])) {
                                                                    echo $result["other_charge"];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" name="other_charge" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>-->

                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . "(%)"; ?></th> 
                                                            <td class="text-right ipdbilltable">
                                                                <input type="text" id="discount_percent"  name="discount_percent" style="width: 30%; float: right" class="form-control">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable">
                                                                <input type="hidden" name="patient_id" value="<?php echo $id ?>">
                                                                <input type="hidden" name="opd_id" value="<?php echo $visit_id ?>">
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
                                                            <th><?php echo $this->lang->line('gross') . " " . $this->lang->line('total') ." (". $currency_symbol . ")"; ?></th>
                                                            <td class="text-right fontbold20">

                                                            <span id="grass_amount_span" class="">0</span></td> 
                                                            <!--<td class="text-right fontbold20"><?php echo $total + $result["tax"]   ?></td>-->

                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><input type="hidden" id="gross_total" value="<?php echo $total - $paid_amount ?>" name="gross_total" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20">
                                                                <?php
                                                                if (!empty($paid_amount)) {
                                                                    echo $paid_amount;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?> 
                                                            <input type="hidden" value="<?php echo $total ?>" id="total_amount" name="total_amount" style="width: 30%" class="form-control">

                                                            <input type="hidden" value="<?php
                                                                if (!empty($paid_amount)) {
                                                                    echo $paid_amount;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="paid_amountpa" name="" style="width: 30%" class="form-control">
                                                            </td>
                                                        </tr>
                                                       
                                                        
                                                        <tr>
                                                            <th><?php echo $this->lang->line('net_payable') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20">

                                                                <span id="net_amount_span" class="">0</span><input type="hidden" name="net_amount" value="<?php
                                                                if (!empty($result["net_amount"])) {
                                                                    echo $result["net_amount"];
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="net_amount_payble" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
    <?php } else { ?>               
                                                        <tr>
                                                            <th><?php echo $this->lang->line('consultant') . " " . $this->lang->line('charges') . " (" . $this->lang->line('paid') . ")". " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $visit_charge + $revisit_charge ; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total ; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('any_other_charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $billstatus['other_charge'] ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php
                                                            echo $billstatus['discount'];
                                                         ?>
                                                            </td>
                                                        </tr>

                                                          <tr>
                                                            <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $billstatus['tax'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('gross') . " " . $this->lang->line('total') ." (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $billstatus['gross_total'] ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $paid_amount; ?>
                                                            </td>
                                                        </tr>
                                                         
                                                        <tr>
                                                            <th><?php echo $this->lang->line('net_payable') . " " . $this->lang->line('amount') . " (" . $this->lang->line('paid') . ") (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $billstatus['net_amount'] ?>
                                                        
                                                            </td>
                                                        </tr>

                                            <?php } ?>

                                            </table>

                                            <?php
//if($paid_amount <= ($total+$visit_charge+$revisit_charge) ){ 
                                            if ($billstatus["status"] != "paid") {
                                                ?> 
                                                <?php if ($this->rbac->hasPrivilege('opd_bill', 'can_add')) { ?>
                                                    <input type="button" onclick="calculate()" id="cal_btn"  name="" value="<?php echo $this->lang->line('calculate'); ?>" class="btn btn-sm btn-info">
                                                <?php } ?>
                                                <input data-loading-text="<?php echo $this->lang->line('processing') ?>" type="submit" style="display:none" id="save_button" name="" value="<?php echo $this->lang->line('generate') ?>" class="btn btn-sm btn-info"/>

                                                <a href="#" style="display:none" class="btn btn-sm btn-info" id="printBill" onclick="printBill('<?php echo $result["id"] ?>', '<?php echo $this->uri->segment(5); ?>')"><?php echo $this->lang->line('print') . " " . $this->lang->line('bill') ?></a>
                                                <?php
                                            } else {

                                                if ($billstatus["status"] == "paid") {
                                                    ?>
                                                    <span class="pull-right"><?php echo $this->lang->line('bill_generated_by') . " : " . $bill_info["name"] . " " . $bill_info["surname"] ?> <?php if(!empty($bill_info["employee_id"] )) { echo " (" . $bill_info["employee_id"] . ")"; } ?></span>

                                                    <a href="#"  class="btn btn-sm btn-info" onclick="printBill('<?php echo $result["id"] ?>', '<?php echo $this->uri->segment(5); ?>')"><?php echo $this->lang->line('print') . " " . $this->lang->line('bill') ?></a>
        <?php }
    }
    ?> 

                                        </div>
                                    </div>               

                                </div>
                            </div>
<?php } ?>
                        <!-- -->
                    </div>

                </div>
                </form>

            </div>
     
                    
                           

                        </div><!--./box-body-->
                    </div>
                  </div>  
                    <?php
               // }
                ?>
            </div>
        </div> 

        <!-- -->
    </section>
</div>

<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('payment'); ?></h4> 
            </div>
            <form id="add_payment" accept-charset="utf-8" method="post" class="" >    
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small class="req"> *</small> 
                                    <input type="text" name="amount" id="amount" class="form-control">    
                                    <input type="hidden" name="patient_id" id="payment_patient_id" class="form-control">
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
                    </div>
                </div> 
                <div class="box-footer">    
                    <button type="submit" id="add_paymentbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>   
            </form>
        </div>
    </div> 
</div>
<!-- -->
<script src="" type="text/javascript" charset="utf-8" async defer>
        
    $(function () {
        $('#easySelectable').easySelectable();
        $('.select2').select2()
    //stopPropagation();
    })
     $("#addpatient_id").click(function(){  
    $('#select2-addpatient_id-container').html("");
    //$('#formadd').trigger("reset");
   // $("#patientDetails").hide();
});

</script>
<script type="text/javascript">
    /*
     Author: mee4dy@gmail.com
     */
    (function ($) {
        //selectable html elements
        $.fn.easySelectable = function (options) {
            var el = $(this);
            var options = $.extend({
                'item': 'li',
                'state': true,
                onSelecting: function (el) {

                },
                onSelected: function (el) {

                },
                onUnSelected: function (el) {

                }
            }, options);
            el.on('dragstart', function (event) {
                event.preventDefault();
            });
            el.off('mouseover');
            el.addClass('easySelectable');
            if (options.state) {
                el.find(options.item).addClass('es-selectable');
                el.on('mousedown', options.item, function (e) {
                    $(this).trigger('start_select');
                    var offset = $(this).offset();
                    var hasClass = $(this).hasClass('es-selected');
                    var prev_el = false;
                    el.on('mouseover', options.item, function (e) {
                        if (prev_el == $(this).index())
                            return true;
                        prev_el = $(this).index();
                        var hasClass2 = $(this).hasClass('es-selected');
                        if (!hasClass2) {
                            $(this).addClass('es-selected').trigger('selected');
                            el.trigger('selected');
                            options.onSelecting($(this));
                            options.onSelected($(this));
                        } else {
                            $(this).removeClass('es-selected').trigger('unselected');
                            el.trigger('unselected');
                            options.onSelecting($(this))
                            options.onUnSelected($(this));
                        }
                    });
                    if (!hasClass) {
                        $(this).addClass('es-selected').trigger('selected');
                        el.trigger('selected');
                        options.onSelecting($(this));
                        options.onSelected($(this));
                    } else {
                        $(this).removeClass('es-selected').trigger('unselected');
                        el.trigger('unselected');
                        options.onSelecting($(this));
                        options.onUnSelected($(this));
                    }
                    var relativeX = (e.pageX - offset.left);
                    var relativeY = (e.pageY - offset.top);
                });
                $(document).on('mouseup', function () {
                    el.off('mouseover');
                });
            } else {
                el.off('mousedown');
            }
        };
    })(jQuery);

</script>
<script type="text/javascript">

      function get_PatientDetails(id) {
        //$("#schedule_charge").html("schedule_charge");
        //var base_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        //$("#ajax_load").html("<center><img src='" + base_url + "'/>");
       // var password = makeid(5)
        //$('#guardian_name').html("Null");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/patientDetails',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (res) {
                //console.log(res);

                if (res) {

                   // $("#ajax_load").html("");
                  //  $("#patientDetails").show();
                    $('#patient_unique_id').html(res.patient_unique_id);
                    $('#patient_id').val(res.id);
                    $('#listname').html(res.patient_name);
                    $('#guardian').html(res.guardian_name);
                    $('#listnumber').html(res.mobileno);
                    $('#email').html(res.email);
                    $('#mobnumber').val(res.mobileno);
                    $('#pemail').val(res.email);
                    $('#patientname').val(res.patient_name);
                    if (res.age == "") {
                        $("#age").html("");
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

                        $("#age").html(age + "," + month + " " + dob);
                    }
                    $('#doctname').val(res.name + " " + res.surname);
                    $("#bp").html(res.bp);
                    $("#symptoms").html(res.symptoms);
                    $("#known_allergies").html(res.known_allergies);
                    $("#address").html(res.address);
                    $("#note").html(res.note);
                    $("#height").html(res.height);
                    $("#weight").html(res.weight);
                    $("#genders").html(res.gender);
                    $("#marital_status").html(res.marital_status);
                    $("#blood_group").html(res.blood_group);
                    $("#allergies").html(res.known_allergies);
                    $("#image").attr("src", '<?php echo base_url() ?>' + res.image);
                   
                } 
            }
        });
    }

     function getopdipd(opdipd_group) {        
        var pid = $('#patient_id').val();        
        var div_data = "";
        $('#opdipd_no').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
       // $("#opdipd_no").select2("val", '1');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/conference/getopdipd',
            type: "POST",
            data: {opdipd_group: opdipd_group,patient_id: pid},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value=" + obj.id + ">" + obj.opdipd_no + "</option>";                   
                });
                $("#opdipd_no").html("<option value=''>Select</option>");
                $('#opdipd_no').append(div_data);
               // $("#opdipd_no").select2().select2('val', '');
            }
        });
    }

     function addpaymentModal() {
       
        holdModal('myPaymentModal');
    }

    $(".addpayment").click(function(){      
    $('#add_payment').trigger("reset");
    $(".dropify-clear").trigger("click");
    });

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

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
        $("#net_amount").val(net_amount.toFixed(2));
        $("#grass_amount").val(net_amount.toFixed(2));
        $("#grass_amount_span").html(net_amount.toFixed(2));
        //$("#net_amount_span").html(net_amount.toFixed(2));
        $("#net_amount_span").html(net_amount_payble.toFixed(2));
        $("#net_amount_payble").val(net_amount_payble.toFixed(2));
        $("#save_button").show();
        $("#printBill").show();
    }

     function printBill(patientid, opdid) {

        var total_amount = $("#total_amount").val();
        var discount = $("#discount").val();
        var other_charge = $("#other_charge").val();
        var gross_total = $("#gross_total").val();
        var tax = $("#tax").val();
        var net_amount = $("#net_amount").val();
        var status = $("#status").val();
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payment/getOPDBill/',
            type: 'POST',
            data: {patient_id: patientid, opdid: opdid, total_amount: total_amount, discount: discount, other_charge: other_charge, gross_total: gross_total, tax: tax, net_amount: net_amount, status: status},
            success: function (result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }

    function generateBill(id, amount) {
        $("#opdidhide").val(id);
        $("#totalopdcharges").val(amount);
        $("#addBillModal").modal('show');
    }

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>