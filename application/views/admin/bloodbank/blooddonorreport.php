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
                        <h3 class="box-title"><?php echo $this->lang->line('blood') . " " . $this->lang->line('donor') . " " . $this->lang->line('report'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/bloodbank/blooddonorreport') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-4" >
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
                            <div class="col-sm-6 col-md-4" id="fromdate" style="display: none">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_from'); ?></label><small class="req"> *</small>
                                    <input id="date_from" name="date_from" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_from', date($this->customlib->getSchoolDateFormat())); ?>"  />
                                    <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4" id="todate" style="display: none">
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
                        <div class="box-body">
                            <div class="download_label"><?php echo $this->lang->line('blood') . " " . $this->lang->line('donor') . " " . $this->lang->line('report'); ?></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('donor') . " " . $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('age'); ?></th>
                                            <th><?php echo $this->lang->line('blood_group'); ?></th>
                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                            <th><?php echo $this->lang->line('lot'); ?></th>
                                            <th><?php echo $this->lang->line('bag_no'); ?></th>
                                            <th><?php echo $this->lang->line('quantity') . " " . $this->lang->line('in_ml'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
if (empty($resultlist)) {
    ?>

                                            <?php
} else {
    $count = 1;
    foreach ($resultlist as $report) {
        ?>
                                                <tr>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(true, false), strtotime($report['createdat'])) ?>
                                                    </td>
                                                    <td><?php echo $report['donor_name']; ?></td>
                                                    <td><?php if (!empty($report['age'])) {echo $report['age'] . " " . $this->lang->line("years") . " ";}if (!empty($report['month'])) {echo $report['month'] . " " . $this->lang->line("month");}?>
                                                    </td>
                                                    <td><?php echo $report['blood_group']; ?>
                                                    </td>
                                                    <td><?php echo $report['gender']; ?></td>
                                                    <td><?php echo $report['lot']; ?></td>
                                                    <td><?php echo $report['bag_no']; ?></td>
                                                    <td><?php echo $report['quantity']; ?></td>
                                                </tr>
                                                <?php
$count++;
    }
}
?>
                                    </tbody>
                                </table>
                            </div>
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