<?php date_default_timezone_set("Asia/Kolkata"); ?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
</head>

<style>
body {
  background: #f2f4f7;
  color: #28333b;
  font-family: 'DM Sans', sans-serif;
  font-size: 1em;
  padding: 0px 25px;
}
body a {
  color: #28333b;
  text-decoration: none;
  border-bottom: 2px solid rgba(64,179,255,0.5);
  opacity: 0.75;
  transition: all 0.5s ease;
}
body a:hover {
  border-bottom: 2px solid #40b3ff;
  opacity: 1;
}
.field {
  margin-bottom: 25px;
}
.field.full {
  width: 100%;
}
.field.half {
  width: calc(50% - 12px);
}
.field label {
  display: block;
  text-transform: uppercase;
  font-size: 12px;
  margin-bottom: 8px;
}
.field input {
  padding: 12px;
  border-radius: 6px;
  border: 2px solid #e8ebed;
  display: block;
  font-size: 14px;
  width: 100%;
  box-sizing: border-box;
}
.field input:placeholder {
  color: #e8ebed !important;
}
.flex {
  display: flex;
  flex-direction: row wrap;
  align-items: center;
}
.flex.justify-space-between {
  justify-content: space-between;
}
.card {
  padding: 50px;
  margin: 50px auto;
  max-width: 850px;
  background: #fff;
  border-radius: 6px;
  box-sizing: border-box;
  box-shadow: 0px 24px 60px -1px rgba(37,44,54,0.14);
}
.card .container {
  max-width: 700px;
  margin: 0 auto;
}
.card .card-title {
  margin-bottom: 50px;
}
.card .card-title h2 {
  margin: 0;
}
.card .card-body .payment-type,
.card .card-body .payment-info {
  margin-bottom: 25px;
}
.card .card-body .payment-type h4 {
  margin: 0;
}
.card .card-body .payment-type .types {
  margin: 25px 0;
}
.card .card-body .payment-type .types .type {
  width: 30%;
  position: relative;
  background: #f2f4f7;
  border: 2px solid #e8ebed;
  padding: 25px;
  box-sizing: border-box;
  border-radius: 6px;
  cursor: pointer;
  text-align: center;
  transition: all 0.5s ease;
}
.card .card-body .payment-type .types .type:hover {
  border-color: #28333b;
}
.card .card-body .payment-type .types .type:hover .logo,
.card .card-body .payment-type .types .type:hover p {
  color: #28333b;
}
.card .card-body .payment-type .types .type.selected {
  border-color: #40b3ff;
  background: rgba(64,179,255,0.1);
}
.card .card-body .payment-type .types .type.selected .logo {
  color: #40b3ff;
}
.card .card-body .payment-type .types .type.selected p {
  color: #28333b;
}
.card .card-body .payment-type .types .type.selected::after {
  content: '\f00c';
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
  position: absolute;
  height: 40px;
  width: 40px;
  top: -21px;
  right: -21px;
  background: #fff;
  border: 2px solid #40b3ff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.card .card-body .payment-type .types .type .logo,
.card .card-body .payment-type .types .type p {
  transition: all 0.5s ease;
}
.card .card-body .payment-type .types .type .logo {
  font-size: 48px;
  color: #8a959c;
}
.card .card-body .payment-type .types .type p {
  margin-bottom: 0;
  font-size: 10px;
  text-transform: uppercase;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #8a959c;
}
.card .card-body .payment-info .column {
  width: calc(100% - 25px);
}
.card .card-body .payment-info .title {
  display: flex;
  flex-direction: row;
  align-items: center;
}
.card .card-body .payment-info .title .num {
  height: 24px;
  width: 24px;
  border-radius: 50%;
  border: 2px solid #40b3ff;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  margin-right: 12px;
  font-size: 12px;
}
footer {
  margin: 50px auto;
  max-width: 850px;
  text-align: center;
}
.button {
  text-transform: uppercase;
  font-weight: 600;
  font-size: 12px;
  letter-spacing: 0.5px;
  padding: 15px 25px;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.5s ease;
  background: transparent;
  border: 2px solid transparent;
}
.button.button-link {
  padding: 0 0 2px;
  margin: 0 25px;
  border-bottom: 2px solid rgba(64,179,255,0.5);
  border-radius: 0;
  opacity: 0.75;
}
.button.button-link:hover {
  border-bottom: 2px solid #40b3ff;
  opacity: 1;
}
.button.button-primary {
  background: #40b3ff;
  color: #fff;
}
.button.button-primary:hover {
  background: #218fd9;
}
.button.button-secondary {
  background: transparent;
  border-color: #e8ebed;
  color: #8a959c;
}
.button.button-secondary:hover {
  border-color: #28333b;
  color: #28333b;
}

input:disabled, input[readonly] {
    background-color: #e9ecef;
    opacity: 1;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert {
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}

</style>
<article class="card">
	<div class="container">
		<div class="card-title">
			<h2>Payment</h2>
		</div>
        <?php 
        if($this->session->flashdata('error')){ ?>
            <div class="alert alert-danger" role="alert">
               <?= $this->session->flashdata('error') ?>
            </div>
        <?php }
        
        ?>
		<div class="card-body">
            <form method="post" action="<?= base_url('admin/staff/profile/'.$upgradeId.'/upgrade/package') ?>">
			<div class="payment-type">
				<h4>Choose Payment Circle below</h4>
				<div class="types flex justify-space-between">
                    <?php
                     foreach ($getSubscriptionsPlans as $key => $value) {
                         $selectedClass = ($key == 1) ? 'selected' : '';
                         if($value->circle >= $getLastSubscriptions[0]->circle){?>
                          <div class="type <?= $selectedClass ?>" data-amount="<?= $value->amount ?>" data-month="<?= $value->circle ?>">
                              <div class="logo">
                              <i class="fas fa-ad"></i>
                              </div>
                              <div class="text">
                              <?= $value->circle ?> Month
                            </div>
                         </div>
                        <?php }
                       ?>
                    <?php }
                    ?>
				</div>
			</div>
			<div class="payment-info flex justify-space-between">
				<div class="column billing">
					<div class="title">
						<div class="num">1</div>
						<h4>Billing Info</h4>
					</div>
					<div class="flex justify-space-between">
						<div class="field half">
							<label for="start_date">Start Date</label>
                            <input type="hidden" id="startDate" name="startDate">
                            <input type="hidden" id="circle" name="circle">
							<input id="start_date" type="text" name="start_date" placeholder="Start Date" readonly>
						</div>
						<div class="field half">
							<label for="end_date">End Date</label>
                            <input type="hidden" id="endDate" name="endDate">
							<input id="end_date" type="text" name="end_date" placeholder="End Date" readonly>
						</div>
					</div>
					<div class="field half">
						<label for="amount">Amount</label>
                        <input type="hidden" value="<?= $getSubscriptionsPlans[1]->amount ?>" name="amount" id="amount">
						<input type="text" id="ams" value="<?= $getSubscriptionsPlans[1]->amount ?>" placeholder="Amount" readonly>
					</div>
				</div>
			</div>
		</div>
		<div class="card-actions flex justify-space-between">
			<div class="flex-start">
				<!-- <button class="button button-secondary">Return to Store</button> -->
			</div>
			<div class="flex-end">
				<!-- <button class="button button-link">Back to Shipping</button> -->
				<button type="submit" class="button button-primary">Proceed</button>
			</div>
		</div>

        </form>
	</div>
</article>
<?php 

$startDateFromLastExpireDate = date('Y-m-d H:i:s', strtotime("+".$getLastSubscriptions[0]->circle." months", strtotime($getLastSubscriptions[0]->subscriptionActiveTime)));
echo $startDateFromLastExpireDate;
?>
<script>
    $(document).ready( function(){
        let startDate = "<?= $startDateFromLastExpireDate ?>";
        let endDate = "<?=  date('Y-m-d H:i:s', strtotime("+3 months", strtotime($startDateFromLastExpireDate))) ?>"
        $('#startDate').val(startDate);
        $('#start_date').val(startDate);
        $('#endDate').val(endDate);
        $('#end_date').val(endDate);
        $('#circle').val(3);
    })
    $(".type").click(function(){
        let endDate;
        $('.type').removeClass('selected');
        $(this).addClass('selected');
        let month = $(this).attr('data-month');
        let amount = $(this).attr('data-amount');
        let startDate = "<?= $startDateFromLastExpireDate ?>";
        $('#ams').val(amount)
        $('#amount').val(amount)
        $('#startDate').val(startDate);
        $('#start_date').val(startDate);
        $('#circle').val(month);
        if(month == 1){
            endDate = "<?=  date('Y-m-d H:i:s', strtotime("+1 months", strtotime($startDateFromLastExpireDate))) ?>"
        }
        if(month == 3){
            endDate = "<?=  date('Y-m-d H:i:s', strtotime("+3 months", strtotime($startDateFromLastExpireDate))) ?>"
        }
        if(month == 6){
            endDate = "<?=  date('Y-m-d H:i:s', strtotime("+6 months", strtotime($startDateFromLastExpireDate))) ?>"
        }
        $('#endDate').val(endDate);
        $('#end_date').val(endDate);
    });
</script>