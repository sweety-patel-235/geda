<?php
	$this->Html->addCrumb($pageTitle);
?>
<div class="container">
	<div class="users form">
	<?php echo $this->Form->create('forgotpassword',['class' => 'validate-form','autocomplete'=>'off','onSubmit'=>'javascript:return changePass_enc();']) ?>
		<?php echo $this->Form->input('hdnaction', array('type'=>'hidden','id'=>'hdnaction','value'=>'send_otp')); ?>
		<div class="form-group">
			<div class="col-md-6 LOGIN_ID">
				<label>LOGIN ID</label>
				<?php echo $this->Form->input('LOGIN_ID', array('label' => false,'class'=>'form-control','placeholder'=>'LOGIN ID')); ?>
			</div>
			<div class="col-md-6 OTP_FOR_PASSWORD hide">
				<label>OTP</label>
				<?php echo $this->Form->input('code', array('label' => false,'class'=>'form-control','placeholder'=>'OTP CODE')); ?>
				<label>New Password</label>
				<?php echo $this->Form->input('password1', array('type'=>'password','label' => false,'class'=>'form-control','placeholder'=>'NEW PASSWORD')); ?>
				<label>Retype Password</label>
				<?php echo $this->Form->input('password2', array('type'=>'password','label' => false,'class'=>'form-control','placeholder'=>'RETYPE PASSWORD')); ?>
			</div>
		</div>
		<div class="form-group">
			<?php
				$captcha = '';
				if(CAPTCHA_DISPLAY == 1) {
				?>
					<div class="col-md-12 <?php echo $captcha; ?> " style="margin-top: 25px;" >
						<div class="recaptcha" data-sitekey="<?php echo CAPTCHA_KEY ;?>"></div>
					</div>
			<?php } ?>
			<div class="col-md-8">

				 <?php echo $this->Form->button(__('Submit'),['type'=>'submit','class'=>'btn btn-primary']); ?>
				 <?php echo $this->Form->button(__('Already Have OTP?'),['type'=>'button','class'=>'btn btn-info']); ?>
				 <?php echo $this->Form->button(__('BACK'),['type'=>'button','class'=>'btn btn-danger hide']); ?>
			</div>
		</div>
	<?php echo $this->Form->end() ?>
	</div>
</div>
<script type="text/javascript">
	$(".btn-info").click(function() {
		$("#email").val("");
		$("#code").val("");
		$("#password1").val("");
		$("#password2").val("");
		$(".LOGIN_ID").addClass("hide");
		$(".btn-info").addClass("hide");
		$(".OTP_FOR_PASSWORD").removeClass("hide");
		$(".btn-danger").removeClass("hide");
		$("#hdnaction").val("reset_pass");
	});
	$(".btn-danger").click(function(){
		$("#email").val("");
		$("#code").val("");
		$("#password1").val("");
		$("#password2").val("");
		$(".OTP_FOR_PASSWORD").addClass("hide");
		$(".btn-danger").addClass("hide");
		$(".LOGIN_ID").removeClass("hide");
		$(".btn-info").removeClass("hide");
		$("#hdnaction").val("send_otp");
	});
	function changePass_enc()
	{
		if($("#password1").val()!='' && $("#password2").val()!='') {
			var pass1 = $.base64.encode($("#password1").val());
			$("#password1").val(makeid()+pass1+makeid());
			var pass2 = $.base64.encode($("#password2").val());
			$("#password2").val(makeid()+pass2+makeid());
		}
	}
	$(document).ready(function(){
		<?php if ($hdnaction == "reset_pass") { ?>
			$(".btn-info").trigger("click");
		<?php } ?>
	});
</script>