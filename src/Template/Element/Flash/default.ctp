<?php
$class 		= 'message';
$type  		= '';
$js_close 	= '';

$types =  array('success' => array('sym' => '<strong>Success!</strong>','class'=>'alert alert-success'),
				'error'=>array('sym' =>'<strong>Error!</strong>','class'=>'alert alert-danger'),
				'warning'=>array('sym' =>'<strong>Warning!</strong>','class'=>'alert alert-warning'));

if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if ( isset($params['type']) && !empty($params['type'])) {
    $type 		.= ' ' . $types[$params['type']]['sym'];
    $class 		.= ' ' . $types[$params['type']]['class'];
    $js_close 	.= '<script>$(document).ready(function(){ setTimeout(function(){ $(".message.alert").slideUp("slow"); }, 7000); });</script>';
}
?>
<div class="<?= h($class) ?>">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
	<?= $type ?> <?= h($message) ?>
	<?php echo $js_close ?>
</div>
