<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('opd') . " " . $this->lang->line('balance') . " " . $this->lang->line('report'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/patient/opdreportbalance') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                        <option value="all"><?php echo $this->lang->line('all') ?></option>
                                        <?php foreach ($searchlist as $key => $search) {
    ?>
                                            <option value="<?php echo $key ?>" <?php
if ((isset($search_type)) && ($search_type == $key)) {
        echo "selected";
    }
    ?>><?php echo $search ?></option>
                                                <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3"  >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('doctor'); ?></label>
                                    <select class="form-control select2" <?php
if ($disable_option == true) {
    echo "disabled";
}
?> name="doctor" style="width: 100%">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($doctorlist as $dkey => $value) {
    ?>
                                            <option value="<?php echo $value["id"] ?>" <?php
if ((isset($doctor_select)) && ($doctor_select == $value["id"])) {
        echo "selected";
    }
    ?> ><?php echo $value["name"] . " " . $value["surname"] ?></option>
 <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3"  >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('patient') . " " . $this->lang->line('status'); ?></label>
                                    <select class="form-control "  name="patient_status" style="width: 100%">
                                        <option value="all" <?php if ((isset($patient_status)) && ($patient_status == 'all')) {
    echo "selected";
}
?> ><?php echo $this->lang->line('all') ?></option>
                                        <option value="on_opd" <?php if ((isset($patient_status)) && ($patient_status == 'on_opd')) {
    echo "selected";
}
?>><?php echo $this->lang->line('bill') . " " . $this->lang->line('not') .
" " . $this->lang->line('generated') ?></option>
                                        <option value="discharged" <?php if ((isset($patient_status)) && ($patient_status == 'discharged')) {
    echo "selected";
}
?>><?php echo $this->lang->line('bill') . " " . $this->lang->line('generated') ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('patient_status'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3" id="fromdate" style="display: none">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_from'); ?></label><small class="req"> *</small>
                                    <input id="date_from" name="date_from" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_from', date($this->customlib->getSchoolDateFormat())); ?>"  />
                                    <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3" id="todate" style="display: none">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_to'); ?></label><small class="req"> *</small>
                                    <input id="date_to" name="date_to" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_to', date($this->customlib->getSchoolDateFormat())); ?>"  />
                                    <span class="text-danger"><?php echo form_error('date_to'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>

                    </form>
                    <div class="box border0 clear">
                        <div class="box-header ptbnull">

                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('opd') . " " . $this->lang->line('balance') . " " . $this->lang->line('report'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?></th>
                                         <th><?php echo $this->lang->line('opd') . " " . $this->lang->line('no'); ?></th>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('age'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                        <th><?php echo $this->lang->line('guardian_name'); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th><?php echo $this->lang->line('casualty'); ?></th>
                                        <th><?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></th>
                                        <th><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></th>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th>
                                        <th><?php echo $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th>
                                        <th><?php echo $this->lang->line('paid') . " " . $this->lang->line('bill'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount') . '(' . $currency_symbol . ')'; ?></th>
                                        <th><?php echo $this->lang->line('balance') . " " . $this->lang->line('amount') . '(' . $currency_symbol . ')'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($resultlist)) {
    ?>

                                        <?php
} else {
    $count = 1;
    $total = 0;
    foreach ($resultlist as $report) {
        if ($report['discharged'] !== 'yes') {
            $pstatus = $this->lang->line('bill') . " " . $this->lang->line('not') . " " . $this->lang->line('generated');
        } else {
            $pstatus = $this->lang->line('bill') . " " . $this->lang->line('generated');
        }

        if (!empty($report['amount'])) {
            $amount   = $report['amount'] + $report['vamount'];
            $tpayment = $report['payment'] + $report['billpaid'];
            $balance  = $report["charges"] - $tpayment;
            $total += $balance;
        }

        ?>
                                            <tr>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($report['appointment_date'])) ?></td>
                                                <td><?php echo $report['opd_no']; ?></td>
                                                <td><?php echo $report['patient_unique_id']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>admin/patient/profile/<?php echo $report['pid']; ?>"><?php echo $report['patient_name'] ?>
                                                    </a>
                                                </td>
                                                <td><?php if (!empty($report['age'])) {echo $report['age'] . " " . $this->lang->line("years") . " ";}if (!empty($report['month'])) {echo $report['month'] . " " . $this->lang->line("month");}?></td>
                                                <td><?php echo $report['gender']; ?></td>
                                                <td><?php echo $report['mobileno']; ?></td>
                                                <td><?php echo $report['guardian_name']; ?></td>
                                                <td><?php echo $report['address']; ?></td>
                                                <td><?php echo $report['casualty']; ?></td>
                                                <td><?php echo $report['name'] . " " . $report['surname']; ?></td>
                                                <td><?php echo $report['payment_mode']; ?></td>
                                                <td><?php echo $pstatus; ?></td>
                                                <td><?php if (isset($report["charges"])) {echo $report["charges"];}?></td>
                                                <td><?php echo $report['payment']; ?></td>
                                                <td class="text-right"><?php echo $report['billpaid']; ?></td>
                                                <td class="text-right"><?php echo $amount; ?></td>
                                                <td class="text-right"><?php echo $balance; ?></td>
                                            </tr>
                                            <?php
$count++;
    }
    ?>              
                                        <tr class="box box-solid total-bg">
                                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            <td class="text-right" ><?php echo $this->lang->line('total') . " " . $this->lang->line('balance') . " :" . $currency_symbol . $total; ?>
                                            </td>

                                    </tr>
                                    </tbody>
                                    
<?php }?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
</div>
</section>
</div>


<script type="text/javascript">
    $(document).ready(function (e) {

        showdate('<?php echo $search_type; ?>');
    });

    function showdate(value) {

        if (value == 'period') {
            $('#fromdate').show();
            $('#todate').show();
        } else {
            $('#fromdate').hide();
            $('#todate').hide();
        }
    }
</script>