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
                        <h3 class="box-title"><?php echo $this->lang->line('appointment') . " " . $this->lang->line('report'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/appointment/appointmentreport') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                        <option value=""><?php echo $this->lang->line('all') ?></option>
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
                            <div class="col-sm-6 col-md-3">
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
                        <div class="box-header ptbnull"></div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('appointment') . " " . $this->lang->line('report'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('doctor'); ?></th>
                                        <th><?php echo $this->lang->line('source'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('status'); ?></th>
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
    foreach ($resultlist as $appointment) {
        if ($appointment["appointment_status"] == "approved") {
            $label = "class='label label-success'";
        } else if ($appointment["appointment_status"] == "pending") {
            $label = "class='label label-warning'";
        } else if ($appointment["appointment_status"] == "cancel") {
            $label = "class='label label-danger'";
        }
        ?>
                                            <tr>
                                                <td><?php echo $appointment['patient_name']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($appointment['date'])) ?></td>
                                                <td><?php echo $appointment['mobileno']; ?></td>
                                                <td><?php echo $appointment['gender']; ?></td>
                                                <td><?php echo $appointment['name'] . " " . $appointment['surname']; ?></td>
                                                <td><?php echo $appointment['source']; ?></td>
                                                <td class="text-right"><small <?php echo $label ?> ><?php echo $this->lang->line($appointment['appointment_status']); ?></small></td>
                                            </tr>
                                            <?php
$count++;
    }
    ?>
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
        $(".select2").select2();
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