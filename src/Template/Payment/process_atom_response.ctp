<?php
if(!empty($partner_request_id)) {
	echo $this->Form->create(null,['id' => 'fr', 'name' => 'fr', 'method' => 'POST', 'url' => $ru]);
	echo $this->Form->input('payment_status', ['type' => 'hidden', 'value'=>$payment_status]);
	echo $this->Form->input('txn_id', ['type' => 'hidden', 'value'=>$company_txn_id]);
	echo $this->Form->end();
?>
	<script type='text/javascript'>
	document.fr.submit();
	</script>
<?php
} else {
	if($payment_status == 'ok') {
		echo "Payment successfully received";
	} else {
		echo "Payment failed.";
	}
}
?>
