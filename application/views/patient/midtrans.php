<!-- Content Wrapper. Contains page content -->
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="SB-Mid-client-2uDtZD3V5ZA_pNYW"></script>
             <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <!-- Horizontal Form --> <form id="payment-form" method="post">
                                  <input type="hidden" name="result_type" id="result-type" value=""></div>
                                  <input type="hidden" name="result_data" id="result-data" value="">
                                     <input type="hidden" name="payment_type" value="<?php echo $payment_type; ?>">
                              </div>
                                </form>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Payment Details</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body">
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
                                    <td><?php echo $patient['mobileno']; ?></td>
                                    <th><?php echo $this->lang->line('email'); ?></th>
                                    <td><?php echo $patient['email']; ?>
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
                                    <td><?php if (isset($patient['ipd_no'])) {echo $patient['ipd_no'];} else {echo $patient['opd_no'];}?>
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
                                <p class="lead">Amount</p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody><tr>
                                                <th style="width:50%"><?php echo $this->lang->line('balance') . " " . $this->lang->line('bill') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>
                                                <td><?php if ($total > ($paid_amount + $amount)) {
    $cal = $total - $paid_amount - $amount;
    // echo $paid_amount ;
    echo $total - $paid_amount - $amount;
} else {
    echo 0;
}
?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('add') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")" ?></th>
                                                <td><?php echo $amount ?></td>
                                            </tr>


                                        </tbody></table>
                                </div>
                            </div>
                        </div>

                        <?php echo validation_errors(); ?>
                        <button type="button"  name="search" id="pay-button"  value="" class="btn btn-info pull-right"><i class="fa fa fa-chevron-right"></i> <?php echo $this->lang->line('pay_with_midtrans'); ?></button>

                    </div><!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 <script type="text/javascript">
      var resultType = document.getElementById('result-type');
        var resultData = document.getElementById('result-data');
        var type = '<?php echo $payment_type ?>';
        function changeResult(type,data){
          $("#result-type").val(type);
          $("#result-data").val(JSON.stringify(data));
          //resultType.innerHTML = type;
          //resultData.innerHTML = JSON.stringify(data);
        }
      var payButton = document.getElementById('pay-button');
      payButton.addEventListener('click', function () {

        snap.pay('<?php echo $snap_Token; ?>',{ // store your snap token here
          onSuccess: function(result){ changeResult('success', result);
            $.ajax({
            url:  '<?php echo base_url(); ?>patient/midtrans/success',
            type: 'POST',
            data: $('#payment-form').serialize(),
            dataType: "json",
            success: function (msg) {
              window.location.href = "<?php echo base_url(); ?>patient/pay/successinvoice/"+msg.insert_id+"/"+type;
            }
        });
         },
  onPending: function(result){console.log('pending');console.log(result);},
  onError: function(result){console.log('error');console.log(result);},
  onClose: function(){console.log('customer closed the popup without finishing the payment');}
})

      });
    </script>