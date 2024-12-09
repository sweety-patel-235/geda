<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<?php
	$this->Html->addCrumb($pageTitle); 
?>
<style type="text/css">
 /* http://docs.jquery.com/UI/Autocomplete#theming*/
.ui-autocomplete { position: absolute; cursor: default; background:#CCC }   

/* workarounds */
html .ui-autocomplete { width:1px; } /* without this, the menu expands to 100% in IE6 */
.ui-menu {
	list-style:none;
	padding: 2px;
	margin: 0;
	display:block;
	float: left;
}
.ui-menu .ui-menu {
	margin-top: -3px;
}
.ui-menu .ui-menu-item {
	margin:0;
	padding: 0;
	zoom: 1;
	float: left;
	clear: left;
	width: 100%;
}
.ui-menu .ui-menu-item a {
	text-decoration:none;
	display:block;
	padding:.2em .4em;
	line-height:1.5;
	zoom:1;
}
.ui-menu .ui-menu-item a.ui-state-hover,
.ui-menu .ui-menu-item a.ui-state-active {
	font-weight: normal;
	margin: -1px;
}
.check-box-address{
	margin-top: 20px !important;
}
.checkbox input[type="checkbox"]{
    width: 18px;
    float: left;
    margin-top: -37px !important;
}
</style>
	<div class="container">
		<div class="row">
			<div class="col-md-12"><h4><strong><?php echo $pageTitle;?></strong></h4></div>
		</div>
		<div class="row" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
			<?php if($installerData->otp_verified_status == 0) { ?>
				<div class="col-md-12">
					<?php echo $this->Form->create('VarifyOtpForm',['name'=>'VarifyOtpForm','id'=>'VarifyOtpForm']); ?>
					<fieldset>
						<legend>Mobile OTP Verification</legend>
						<div class="row">
							<div class="form-group">
								
								<div class="col-md-12">
									<div id="message_error"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								
								<div class="col-md-4 cinput">

									<?php echo $this->Form->input('insid',['id'=>'insid','label' => true,'type'=>'hidden','value'=>$installer_id]); ?>
									<?php echo $this->Form->input('is_email',['id'=>'is_email','label' => true,'type'=>'hidden','value'=>0]); ?>
									<?php echo $this->Form->input('otp',["class" =>"form-control",
																					'id'=>'otp',
																					'label' => false,
																					"placeholder" => "Enter Mobile OTP"
																		]);
									?>
								</div>
								<div class="col-md-2">
								<?php echo $this->Form->input('Verify OTP',['type'=>'button','id'=>'login_btn_OTP1','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyOtpForm']); ?>
								</div>
								<div class="col-md-2">
								<?php echo $this->Form->input('Resend OTP',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary resendotp_btn','data-form-name'=>'VarifyOtpForm']); ?>
								</div>
							</div>
						</div>
						</fieldset>
						
					<?php echo $this->Form->end(); ?>
				</div>
			<?php } ?>
			<?php if($installerData->otp_email_verified_status == 0) { ?>
				<div class="col-md-12">
					<?php echo $this->Form->create('VarifyEmailOtpForm',['name'=>'VarifyEmailOtpForm','id'=>'VarifyEmailOtpForm']); ?>
					<fieldset>
						<legend>Email OTP Verification</legend>
						<div class="row">
							<div class="form-group">
								
								<div class="col-md-12">
									<div id="message_error"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								
								<div class="col-md-4 cinput">

									<?php echo $this->Form->input('insid',['id'=>'insid','label' => true,'type'=>'hidden','value'=>$installer_id]); ?>
									<?php echo $this->Form->input('is_email',['id'=>'is_email','label' => true,'type'=>'hidden','value'=>1]); ?>
									<?php echo $this->Form->input('otp',["class" =>"form-control",
																					'id'=>'otp',
																					'label' => false,
																					"placeholder" => "Enter Email OTP"
																		]);
									?>
								</div>
								<div class="col-md-2">
								<?php echo $this->Form->input('Verify OTP',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyEmailOtpForm']); ?>
								</div>
								<div class="col-md-2">
								<?php echo $this->Form->input('Resend OTP',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary resendemailotp_btn','data-form-name'=>'VarifyEmailOtpForm']); ?>
								</div>
							</div>
						</div>
						</fieldset>
					<?php echo $this->Form->end(); ?>
				</div>
			<?php } ?>
			<div class="col-md-12">
				<?php echo $this->Form->input('Back to Registration',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary','data-form-name'=>'','onclick'=>'click_registration();']); ?>
			</div>
		</div>
	</div>
<script language="javascript">
$('.panel-heading u').click(function() {
	$('.panel-heading').removeClass('actives');
	$(this).parents('.panel-heading').addClass('actives');
	$('.panel-title').removeClass('actives'); //just to make a visual sense
	$(this).parent().addClass('actives'); //just to make a visual sense
 });
$(document).ready(function() {
	$("#f_upload_undertaking").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_upload_undertaking-file-errors',
		maxFileSize: '1024',
	});
});

$(".varifyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$("#"+fromobj).find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#"+fromobj).find("#message_error").html('');
	var otp_data = $("#"+fromobj).find("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/verify-otp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#"+fromobj).find("#otp_data").val('');
						$("#"+fromobj).find("#message_error").removeClass('alert-danger');
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						if(result.redirect_payment == 1) {
							window.location.href='/installer-payment/'+$("#insid").val();
						}
						else if(result.redirect_payment == 0) {
							window.location.href='/verify-otp/'+$("#insid").val();
						}
					} else {
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});

$(".resendotp_btn").click(function() {
	window.location.href='/resend-otp/0/'+$("#insid").val();
});
$(".resendemailotp_btn").click(function() {
	window.location.href='/resend-otp/1/'+$("#insid").val();
});
function click_registration()
{

	window.location.href="<?php echo URL_HTTP.'installer-registration/'.$company_id;?>"
}
</script>
