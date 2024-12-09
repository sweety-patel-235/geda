<?php
$js_close 	= '<script>$(document).ready(function(){ setTimeout(function(){ $(".message.alert").slideUp("slow"); }, 7000); });</script>';
?>

<div class="message alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
	<?= h($message) ?>
	<?php echo $js_close ?>
</div>