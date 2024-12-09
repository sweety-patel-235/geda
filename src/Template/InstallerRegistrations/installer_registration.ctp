<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<?php
	$this->Html->addCrumb($pageTitle); 
	$this->Form->create($installer);
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
.btn-primary:focus {
    border-color: #71BF57;
    background-color: #71BF57;
    color: white;
}
.btn_radius {
	border-radius: 10px !important;
}
</style>
<?php $installerText 	= ($registration_type!=1) ? 'Installer' : 'Developer';?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<p>
				It is suggested to kindly check the GST number twice before entering. The same will not be changed once the application is processed. GST credit will not be passed on to those who have not entered the correct GST number.
				</p>
				<p>It is in the interest of the Solar PV <?php echo $installerText;?> to provide as much details as possible. The email ID and mobile no. mentioned here in the form shall be used for all further communication related to this Portal. The documents to be uploaded shall be of max. 1 MB.</p>
				<p>The login shall be generated and emailed to you on your registered email ID upon the approval from GEDA Office.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><h4><strong><?php echo $pageTitle;?></strong></h4></div>
		</div>
		<div class="row" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
			<div class="col-md-12">
				<?php echo $this->Form->create($installer_detail,['type'=>'file','name'=>'installerReg','id'=>'installerReg', 'url' => '/'.$RouteURL.'/'.$company_id]);?>
				<?php echo $this->Form->hidden('Installers.selected_category',array("value"=>"","id"=>"selected_category")); ?>
				<fieldset>
					<legend><div class="col-md-4 col-sm-4 col-lg-4"><?php echo ($registration_type!=1) ? 'Installer' : '';?> Information </div>
						<div class="col-md-8 col-sm-8 col-lg-8">
							<?php if(!empty($applicationCategoryData)) {
								$arrSelected 	= isset($installer_detail->selected_category) ? explode(",",$installer_detail->selected_category) : array();
								foreach($applicationCategoryData as $applicationCategory) {
									$classApplied 	= (isset($installer_detail->selected_category) && in_array($applicationCategory->id,$arrSelected)) ? 'btn-primary' : (!isset($installer_detail->selected_category) ? (($applicationCategory->id ==  1) ? 'btn-primary' : 'btn-default') : 'btn-default');
									?>
									<button type="button" class="btn <?php echo $classApplied;?> btn-sm btn_radius" id="cat_<?php echo $applicationCategory->id;?>" onClick="chkCategory('<?php echo $applicationCategory->id;?>');" data-amount='<?php echo $applicationCategory->installer_charges;?>' data-tax='<?php echo $applicationCategory->installer_tax_percentage;?>' ><?php echo $applicationCategory->category_name;?></button>
									<?php
								}
								if(isset($InstallerErrors['selected_category']['_empty']) && !empty($InstallerErrors['selected_category']['_empty'])) { ?>
									<div class="has-error" style="font-size:14px;">
										<div class="help-block"><?php echo $InstallerErrors['selected_category']['_empty']; ?></div>
									</div>
								<?php }
							}?>
						</div>
					</legend>
					
					<div class="row">
						<div class="form-group">
							<div class="col-md-6 cinput">
								<label>Name of the Solar <?php echo $installerText;?> Company *</label>
								<?php echo $this->Form->input('Installers.installer_name',array('id'=>"company_name",'label' => false,'class'=>'form-control')); ?>
								<?php echo $this->Form->hidden('Installers.company_id',array('id'=>"company_id","value"=>decode($company_id))); ?>
							</div>
							<div class="col-md-6">
								<label>Name of the Head of the <?php echo $installerText;?> *</label>
								<?php echo $this->Form->input('Installers.contact_person',array('label' => false,'class'=>'form-control')); ?>
							</div>
						</div>
					</div>
					<?php $fields_readonly = array(); 
					if(isset($installer_detail->payment_status) && $installer_detail->payment_status==1) { $fields_readonly['readonly'] = 'readonly'; } ?>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>Designation *</label>
								<?php echo $this->Form->input('Installers.designation',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-4">
								<label>Mobile No.*</label>
							  <?php echo $this->Form->input('Installers.mobile',array('maxlength'=>10,'label' => false,'class'=>'form-control',$fields_readonly)); ?>
							</div>
							<div class="col-md-4">
								<label>Landline No.</label>
								<?php
								echo $this->Form->input('Installers.contact',array('label' => false,'class'=>'form-control')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>Email *</label>
								<?php
								echo $this->Form->input('Installers.email',array('data-msg-email'=>"Please enter a valid email address.", 'data-msg-required'=>"Please enter your email address.",'type'=>"email",'label' => false,'class'=>'form-control',$fields_readonly)); ?>
							</div>
							<div class="col-md-4">
								<label>WhatsApp No. <img src="/img/whatsapp.png" height="20" width="20" /></label>
								<?php
								echo $this->Form->input('Installers.fax_no',array('type'=>'hidden','label' => false,'class'=>'form-control')); ?>
								<?php
								echo $this->Form->input('Installers.contact1',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-4">
								<label>Website </label>
								<?php
								echo $this->Form->input('Installers.website',array('label' => false,'class'=>'form-control')); ?>
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>Office Address *</label>
								<?php
								echo $this->Form->input('Installers.address',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<?php
								$error_class = isset($InstallerErrors['state']['_empty']) && !empty($InstallerErrors['state']['_empty']) ? 'has-error' : ''; 
							?>
							<div class="col-md-4 <?php echo $error_class;?>">
								<label>State *</label>
								<?php
								echo $this->Form->select('Installers.state',$arrStateData,array('label' => false,'class'=>'form-control','id'=>'state','empty'=>'-Select State-')); ?>
								<?php if(!empty($error_class)) { ?>
									<div class="help-block"><?php echo $InstallerErrors['state']['_empty']; ?></div>
								<?php } ?>
							</div>
							<?php
								$error_class = isset($InstallerErrors['district']['_empty']) && !empty($InstallerErrors['district']['_empty']) ? 'has-error' : ''; 
							?>
							<div class="col-md-4 <?php echo $error_class;?>">
								<label>District *</label>
								<?php
								echo $this->Form->select('Installers.district',$arrDistictData,array('label' => false,'class'=>'form-control','id'=>'district','empty'=>'-Select District-')); ?>
								<?php if(!empty($error_class)) { ?>
									<div class="help-block"><?php echo $InstallerErrors['district']['_empty']; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>City *</label>
								<?php
								echo $this->Form->input('Installers.city',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-4">
								<label>PIN Code *</label>
								<?php echo $this->Form->input('Installers.pincode',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-4">
								<label class="lbl_comunication_address">
								Company/ Organization Registration Document<span class="mendatory_field">*</span></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Installers.f_registration_document', array('label' => false,'div' => false,'type'=>'file','id'=>'f_registration_document','templates' => ['inputContainer' => '{{content}}'])); ?>
								</div>
								<div id="f_registration_document-file-errors"></div>
								<?php
								if(!empty($installer_detail->registration_document)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->registration_document)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/registration_document/'.encode($installer_detail->id)."\">View Registration Document</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>PAN Number *</label>
								<?php
								echo $this->Form->input('Installers.pan',array('label' => false,'class'=>'form-control',$fields_readonly));?>
							</div>
							<div class="col-md-4">
								<label class="lbl_comunication_address">
								PAN Card<span class="mendatory_field">*</span></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Installers.f_pan_card', array('label' => false,'div' => false,'type'=>'file','id'=>'f_pan_card','templates' => ['inputContainer' => '{{content}}'])); ?>
								</div>
								<div id="f_pan_card-file-errors"></div>
								<?php
								if(!empty($installer_detail->pan_card)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->pan_card)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/pan_card/'.encode($installer_detail->id)."\">View PAN Card</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
							<div class="col-md-4">
								<label class="lbl_comunication_address">
								Upload Undertaking form<span class="mendatory_field">*</span></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Installers.f_upload_undertaking', array('label' => false,'div' => false,'type'=>'file','id'=>'f_upload_undertaking','templates' => ['inputContainer' => '{{content}}'])); ?>
								</div>
								<br/><a href="/Format-for-Undertaking.docx">[Download Undertaking Format]</a>
								<div id="f_upload_undertaking-file-errors"></div>
								<?php
								if(!empty($installer_detail->upload_undertaking)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->upload_undertaking)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/upload_undertaking/'.encode($installer_detail->id)."\">View Undertaking form</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label style="margin-left: 20px;margin-top:20px;">
									 &nbsp;&nbsp;I don’t have GST No. <?php echo $this->Form->input('Installers.gst_check',array('label' => false,'class'=>'form-control check-box-address','value'=>'1','type'=>'checkbox','onClick'=>'javascript:gst_click();','id'=>'gst_check')); ?>
								</label>
							</div>
							<div class="col-md-4 gst_select">
								<label>GST No. *</label>
								<?php echo $this->Form->input('Installers.GST',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-4 gst_select">
								<label class="lbl_comunication_address">
								GST Certificate<span class="mendatory_field">*</span></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Installers.f_gst_certificate', array('label' => false,'div' => false,'type'=>'file','id'=>'f_gst_certificate','templates' => ['inputContainer' => '{{content}}'])); ?>
								</div>
								<div id="f_gst_certificate-file-errors"></div>
								<?php
								if(!empty($installer_detail->gst_certificate)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->gst_certificate)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/gst_certificate/'.encode($installer_detail->id)."\">View GST Certificate</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="lbl_comunication_address">
								Total Processing Fees including GST (in &#8377;) <span id="total_price"><?php echo number_format((INSTALLER_PAYMENT_FEES + INSTALLER_GST_AMOUNT),2);?></span></label>
								<?php /*
								<div class="form-group text"><?php echo 'Amount '.number_format(INSTALLER_PAYMENT_FEES,2);?> + <?php echo 'GST '.number_format(INSTALLER_GST_AMOUNT,2);?></div> */?>
							</div>
						</div>
					</div>
					<?php if(isset($installer_detail->geda_approval) && $installer_detail->geda_approval==2) {  ?>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>
										 <strong>Query Raised -</strong> <?php echo $installer_detail->reject_reason;?>
									</label>
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<?php $error_class = isset($InstallerErrors['reply']['_empty']) && !empty($InstallerErrors['reply']['_empty']) ? 'has-error' : '';  ?>
								<div class="col-md-4">
									<label>
										Reply
									</label>
								</div>
								<div class="col-md-6 <?php echo $error_class;?>">
									<?php echo $this->Form->textarea('Installers.reply',[ "class" =>"form-control reason messagebox",      
															'id'=>'reply',
															'cols'=>'50','rows'=>'5',
															'label' => false,
															'placeholder' => 'Comments, if any']);?>
									<?php if(!empty($error_class)) { ?>
										<div class="help-block"><?php echo $InstallerErrors['reply']['_empty']; ?></div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</fieldset>
				<?php if(empty($member_id)) { ?>
					<div class="row">
						<div class="col-md-12">
							<input type="submit" value="Submit" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
							<?php
							/*if(!empty($installer_detail->otp) && empty($installer_detail->otp_verified_status)) {
								?>
								<input type="button" value="Verify OTP" data-toggle="modal" data-target="#Varify_Otp" class="Varify_Otp btn btn-primary btn-lg mb-xlg" data-id="<?php echo encode($installer_detail->id); ?>" >
								<input type="button" value="Resend OTP" data-toggle="modal" data-target="#Resend_Otp" class="Resend_Otp btn btn-primary btn-lg mb-xlg" data-id="<?php echo encode($installer_detail->id); ?>" >
								<?php
							}*/
							?>
						</div>
					</div>
				<?php } ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div id="Varify_Otp" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Verify OTP</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('VarifyOtpForm',['name'=>'VarifyOtpForm','id'=>'VarifyOtpForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
						<?php echo $this->Form->input('insid',['id'=>'insid','label' => true,'type'=>'hidden']); ?>
						<?php echo $this->Form->input('otp',["class" =>"form-control",
																		'id'=>'otp',
																		'label' => false,
																		"placeholder" => "Enter OTP"
															]);
						?>
						</div>
						<div class="row">
							<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyOtpForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="Resend_Otp" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Resend OTP</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('ResendOtpForm',['name'=>'ResendOtpForm','id'=>'ResendOtpForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
						<?php echo $this->Form->input('resend_id',['id'=>'resend_id','label' => true,'type'=>'hidden','value'=>$installer_detail->id]); ?>
						<?php echo $this->Form->input('otp',["class" =>"form-control",
																		'id'=>'otp',
																		'label' => false,
																		"placeholder" => "Enter OTP"
															]);
						?>
						</div>
						<div class="row">
							<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary resendotp_btn','data-form-name'=>'ResendOtpForm']); ?>
							</div>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
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
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_upload_undertaking-file-errors',
		maxFileSize: '1024',
	});
	$("#f_gst_certificate").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_gst_certificate-file-errors',
		maxFileSize: '1024',
	});
	$("#f_registration_document").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_registration_document-file-errors',
		maxFileSize: '1024',
	});
	$("#f_pan_card").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_pan_card-file-errors',
		maxFileSize: '1024',
	});
});
$(".Varify_Otp").click(function(){
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var ins_id = $(this).attr("data-id");
	$("#insid").val(ins_id);
});
$(".varifyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var otp_data = $("#otp").val();
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
						$("#VarifyOtpForm").find("#otp_data").val('');
						$("#VarifyOtpForm").find("#message_error").removeClass('alert-danger');
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#Varify_Otp").modal('hide');
						window.location.reload();
					} else {
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".Resend_Otp").click(function(){
	$("#ResendOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#ResendOtpForm").find("#message_error").html('');
	var ins_id = $(this).attr("data-id");
	$("#resend_id").val(ins_id);
});
$(".resendotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#ResendOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#ResendOtpForm").find("#message_error").html('');
	var otp_data = $("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/InstallerRegistrations/ResendOtp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ResendOtpForm").find("#otp_data").val('');
						$("#ResendOtpForm").find("#message_error").removeClass('alert-danger');
						$("#ResendOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#Varify_Otp").modal('hide');
						window.location.reload();
					} else {
						$("#ResendOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$("#state").change(function(){
	$("#district").html("");
	$("#district").append($("<option />").val('').text('-Select District-'));
	detailsFromState(1);
});
function detailsFromState(reset=0)
{
	var org_val 	= '';
	if(reset==0) {
		org_val 	= '<?php echo isset($installer_detail->district) ? $installer_detail->district : "";?>';
	}
	$.ajax({
		type: "POST",
		url: "/InstallerRegistrations/getDistrict",
		data: {"state":$('#state').val()},
		success: function(response) {
		var result = $.parseJSON(response);
		$("#district").html("");
		$("#district").append($("<option />").val('').text('-Select District-'));
		if (result.data.district != undefined) {
			$.each(result.data.district, function(index, title) {
				$("#district").append($("<option />").val(index).text(title));
			});
			$('#district').val(org_val);
			if(org_val!='')
			{
		
			}
		}
		}
	});
}
function gst_click()
{
	if($("#gst_check").is(":checked")){
		$(".gst_select").hide();
	} else {
		$(".gst_select").show();
	}
	
}
detailsFromState();
gst_click();
function chkCategory(cat_id) {
	if($("#cat_"+cat_id).hasClass("btn-primary")) {
		$("#cat_"+cat_id).removeClass('btn-primary');
		$("#cat_"+cat_id).addClass('btn-default');
	} else {
		console.log($("#cat_"+cat_id).hasClass("btn-primary"));
		$("#cat_"+cat_id).addClass('btn-primary');
		$("#cat_"+cat_id).removeClass('btn-default');
	}
	getTotal();
}
function getTotal() {
	var counter 		= 0;
	var totalCategory 	= '<?php echo count($applicationCategoryData);?>';
	var totalPrice 		= 0;
	var selectedCategory= [];
	for(var i=1;i<=totalCategory;i++)
	{
		if($("#cat_"+i).hasClass("btn-primary")) {
			var amount 		= parseFloat($("#cat_"+i).attr('data-amount'));
			var tax 		= parseFloat($("#cat_"+i).attr('data-tax'));
			var basicPrice 	= amount + ((amount*tax)/100);
			totalPrice  	= totalPrice + (basicPrice);
			selectedCategory.push(i);
			counter++;
		}
	}
	var selectedCat 	= selectedCategory.join(",");
	$("#selected_category").val(selectedCat);
	$("#total_price").html(NumFormat(totalPrice));
}
function NumFormat(number){
	var outputformat = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2,minimumFractionDigits: 2 }).format(number);
	return outputformat;
}
getTotal();
chkCategory(1);
</script>
