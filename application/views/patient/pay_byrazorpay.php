
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
  var SITEURL = "<?php echo base_url() ?>";

  pay();
  function pay(e){
    var totalAmount = <?php echo $total; ?>;
    var type = '<?php echo $payment_type; ?>';

    var options = {
    "key": "<?php echo $key_id; ?>",
    "amount": "<?php echo $total; ?>", // 2000 paise = INR 20
    "name": "<?php echo $name; ?>",
    "description": "<?php echo $title; ?>",
    "currency": "<?php echo $currency; ?>",
    "image": "<?php echo $image; ?>",
    "handler": function (response){

          $.ajax({
            url: SITEURL + 'patient/razorpay/callback',
            type: 'post',
            dataType: 'json',
            data: {
                razorpay_payment_id: response.razorpay_payment_id , totalAmount : totalAmount ,type : type,
            },
            success: function (msg) {
              window.location.assign(SITEURL+'patient/pay/successinvoice/'+msg.insert_id+'/'+type);
            }
        });
    },

    "theme": {
        "color": "<?php echo $theme_color; ?>"
    }
  };
  var rzp1 = new Razorpay(options);
  rzp1.open();

  };
</script>