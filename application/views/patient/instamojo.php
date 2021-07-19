<!-- Content Wrapper. Contains page content -->
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Payment Details</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                         <form method="post" id="formadd">
                        <table class="table table-striped mb0 font13">
                            <tbody>
                                <tr>
                                    <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                    <td class="bozero"><?php echo $patient['patient_name'] ?></td>
                                    <th class="bozero"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                    <td class="bozero"><?php echo $patient['patient_unique_id']; ?> </td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('gender'); ?></th>
                                    <td><?php echo $patient['gender']; ?></td>
                                    <th><?php echo $this->lang->line('marital_status'); ?></th>
                                    <td><?php echo $patient['marital_status']; ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('phone'); ?></th>
                                    <td><input calss="form-control" name="phone" type="text" value="<?php echo set_value('phone', $patient['mobileno']) ?>" ></td>
                                    <th><?php echo $this->lang->line('email'); ?></th>
                                    <td><input class="form-control" name="email" type="text" value="<?php echo set_value('email', $patient['email']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('age'); ?></th>
                                    <td>
                                        <?php echo $patient['age'] . " years " . $patient['month'] . " months"; ?>
                                    </td>
                                    <th><?php echo $this->lang->line('guardian_name'); ?></th>
                                    <td><?php echo $patient['guardian_name']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('credit_limit') . " (" . $currency_symbol . ")"; ?></th>
                                    <td><?php echo $patient['credit_limit']; ?>
                                    </td>
                                    <th><?php echo $this->lang->line('opd_ipd_no'); ?></th>
                                    <td><?php if (isset($patient['ipd_no'])) {echo $patient['ipd_no'];} else {
    echo $patient['opd_no'];

}?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <?php
$j     = 0;
$total = 0;
foreach ($charges as $key => $charge) {
    ?>


                            <?php
$total += $charge["apply_charge"];
    ?>

                            <?php
$j++;
}
?>
                        <div class="row">
                            <div class=" col-md-offset-6 col-xs-6">
                                <p class="lead"><?php echo $this->lang->line('amount'); ?></p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody><tr>
                                                <th style="width:50%"><?php echo $this->lang->line('balance') . " " . $this->lang->line('bill') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>
                                                <td><?php if ($total > ($paid_amount + $amount)) {
    $cal = $total - $paid_amount - $amount;
    echo $total - $paid_amount - $amount;
} else {
    echo '0';}
?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('add') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")" ?></th>
                                                <td><?php echo $amount ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo validation_errors(); ?>
                            <input type="hidden" name="payment_type" value="<?php echo $payment_type; ?>">
                       <input id="formaddbtn"  class="btn btn-primary col-md-offset-3 pull-right" type="submit"  value="Pay by Instamojo" >

                        </form>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                </div>
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
      $(document).ready(function (e) {
        $("#formadd").on('submit', (function (e) {
            $("#formaddbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>patient/instamojo/pay_byinstamojo',
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
                        window.location.href = data.location;
                    }
                    $("#formaddbtn").button('reset');
                },
                error: function () {
                }
            });
        }));
    });
</script>