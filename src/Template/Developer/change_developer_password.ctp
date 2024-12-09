<?php
    $this->Html->addCrumb($pageTitle); 
?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
<div class="users form">
<?php echo $this->Form->create($user,['class' => 'validate-form','method'=>'post','type'=>'post','onSubmit'=>'javascript:return changePass_enc();']) ?>
    <fieldset>
        <?= $this->Form->input('old_password',['class'=>'required','type'=>'password','autocomplete'=>'off','id'=>'old_password']) ?>
        <?= $this->Form->input('password1',['class'=>'required','type'=>'password','autocomplete'=>'off','label'=>'New Password']) ?>
     	<?= $this->Form->input('password2',['class'=>'required','type'=>'password','autocomplete'=>'off','label'=>'Confirm Password']) ?>
     
   </fieldset>
<?php
	$captcha = '';
	if(CAPTCHA_DISPLAY == 1) {
?>
	<div class="col-md-12 <?php echo $captcha; ?> " style="margin-top: 25px;margin-left: -15px;margin-bottom: 10px;" >
		<div class="recaptcha" data-sitekey="<?php echo CAPTCHA_KEY ;?>"></div>
	</div>
<?php } ?>
<?php echo $this->Form->button(__('Submit')); ?>
<?php echo $this->Form->end() ?>
</div>
</div>
<script language="javascript">
	function changePass_enc()
	{
		if($("#password1").val()!='' && $("#password2").val()!='' && $("#old_password").val()!='')
		{
			var pass1 = $.base64.encode($("#password1").val());
			$("#password1").val(makeid()+pass1+makeid());
			var pass2 = $.base64.encode($("#password2").val());
			$("#password2").val(makeid()+pass2+makeid());
			var oldp  = $.base64.encode($("#old_password").val());
			$("#old_password").val(makeid()+oldp+makeid());
		}
		
	}
</script>