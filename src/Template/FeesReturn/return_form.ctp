<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<?php
	$this->Html->addCrumb($pageTitle);
	$ApproveFA          = false;
	
?>
<style>
.serial_class
{
	width:4%;
}
.applyonline-viewmain .portlet-body {
	padding: 7px;
}
.italic_data{
	font-size: 11px;
	font-style: italic;
}
.form-horizontal .radio {

	padding-top: 0px !important;
}
.check-box-address{
	margin-top: 20px !important;
}
.applay-online-from input[type="checkbox"] {
    
    margin-top: -35px !important;

}
.input-group .form-control{
	z-index: 0 !important;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $selectFile 	= ($view_status == 1) ? 'hide' : ''; ?>
<div class="container">
	<?php echo $this->Flash->render('cutom_admin'); ?>
	<div class="box">
		<div class="content applay-online-from">
			<div class="portlet box blue-madison applyonline-viewmain fesibility-report">
				<div class="row">
					<h2 class="col-md-12 mb-sm mt-sm">
						<small>Application for Refund of registration fees received by GEDA against the registration of Small Scale Distributed Solar Projects.</small>
					</h2>
				</div>
				<div class="row" style="float: right;margin-right: 10px;">
					<a href="<?php echo URL_HTTP;?>SSD_Indemnity_Bond.docx"><i class="fa fa-download"></i>&nbsp;&nbsp;Download the Notarized format of Indemnity Bond</a>
				</div>
				<div class="row" style="float: right;">
					&nbsp;
				</div>
				<?php
					$SubmitReport       = true;
					if (!empty($ApplyOnlines->section) && $ApplyOnlines->section == $section) {
						$SubmitReport   = true;
					} else if (!empty($ApplyOnlines->division) && $ApplyOnlines->division == $division) {
						$SubmitReport   = true;
					}
					
					echo $this->Form->create($FeesReturn, ['id'=>'form-main','method'=>'post','type' => 'post','enctype'=>'multipart/form-data','autocomplete'=>'off']);
					
					$this->Form->templates(['inputContainer' => '{{content}}']);
				?>
				<table width="100%" class="form">
					<?php /*<tr>
						<td class="portlet-body" colspan="4">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6"><label>Date and Time of Submission of Report</label></div>
										<div class="col-md-5"></div>
										<div class="col-md-1 text-right">
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>*/ ?>
					<tr>
						<td class="portlet-body serial_class" >1.</td>
						<td class="portlet-body" colspan="2" style="width:48%;"><label>Name of SPG/ Applicant</label></td>
						<td class="portlet-body" style="width:48%;"><?php echo $this->Form->input('spg_applicant',array('label' => false,'class'=>'form-control','placeholder'=>'Name of SPG/ Applicant')); ?></td>
					</tr>
					<tr>
						<td class="portlet-body">1.1</td>
						<td class="portlet-body" colspan="2" style="width:48%;"><label>Mobile No. of Applicant</label></td>
						<td class="portlet-body" style="width:48%;">
							<?php echo $this->Form->input('mobile', array('label' => false,'type'=>'text','id'=>'mobile','class'=>'form-control','maxlength'=>'10','onkeypress'=>'return validateInteger(event);')); ?>
						</td>
					</tr>
					<tr>
						<td class="portlet-body">1.2</td>
						<td class="portlet-body" colspan="2" style="width:48%;"><label>Email of Applicant</label></td>
						<td class="portlet-body" style="width:48%;">
							<?php echo $this->Form->input('email', array('label' => false,'type'=>'text','id'=>'email','class'=>'form-control')); ?>
						</td>
					</tr>
					
					<tr>
						<td class="portlet-body serial_class" rowspan="3">2.</td>
						<td class="portlet-body" colspan="2" rowspan="3"><label>GEDA registration no. & Date <br/><span class="italic_data">(enclose the copy of registration letter)</span></label></td>
						<td class="portlet-body"><?php echo $this->Form->input('registration_no',["type" => "text",'label'=>false,"class" => "form-control","id"=>"registration_no" ,'placeholder'=>'Registration no.']);?></td>
					</tr>
					<tr>
						<td class="portlet-body"><?php echo $this->Form->input('registration_date',["type" => "text",'label'=>false,"class" => "form-control","id"=>"registration_date" ,'placeholder'=>'Registration Date']);?></td>
					</tr>
					<tr>
						
						
						<td class="portlet-body" >
							<?php if(empty($selectFile)) { ?>
								<?php echo $this->Form->input('file_copy_registration', array('label' => false,'type'=>'file','id'=>'file_copy_registration','class'=>'form-control')); ?>
								<div id="file_copy_registration-file-errors"></div>
								<br/>
							<?php } ?>
							<?php if(!empty($FeesReturn->copy_registration)) { ?>
								<?php if ($Couchdb->documentExist($FeesReturn->id,$FeesReturn->copy_registration)) { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/fr_copy_registration/'.encode($FeesReturn->id)."\">View Registration Letter</a></strong>";
										?>
								<?php } ?>
							<?php } ?>
						</td>
					</tr>
					<?php  $disp_row 	= ($is_member) ? 'rowspan="5"' : ''; ?>
					<tr>
						<td class="portlet-body serial_class" <?php echo $disp_row;?>>3.</td>
						<td class="portlet-body" colspan="2"><label>Project Capacity kW (AC)</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('capacity', array('label' => false,'class'=>'form-control','placeholder'=>'Project Capacity kW (AC)','type'=>'text','maxlength'=>12,'onkeypress'=>"return validateDecimal(event)",'onblur'=>'javascript:changeCost();','id'=>'capacity')); ?></td>
					</tr>
					<?php  $disp_ref 	= ($is_member) ? '' : 'display:none;'; ?>
					<tr style="<?php echo $disp_ref;?>">
						
						<td class="portlet-body" colspan="2"><label>Refundable Amount (Rs.)</label></td>
						<td class="portlet-body" id="ref_amount"><?php echo $FeesReturn->refundable_amount;?></td>
					</tr>
					<tr style="<?php echo $disp_ref;?>">
						
						<td class="portlet-body" colspan="2"><label>GST Amount (Rs.)</label></td>
						<td class="portlet-body" id="gst_amount"><?php echo $FeesReturn->gst_amount;?></td>
					</tr>
					<tr style="<?php echo $disp_ref;?>">
						
						<td class="portlet-body" colspan="2"><label>Total (Basic + GST) Amount (Rs.)</label></td>
						<td class="portlet-body" id="total_gst_amount"><?php echo ($FeesReturn->refundable_amount + $FeesReturn->gst_amount);?></td>
					</tr>
					<tr style="<?php echo $disp_ref;?>">
						
						<td class="portlet-body" colspan="2"><label>Amount Refundable by GEDA (Rs.)</label></td>
						<td class="portlet-body"><?php echo $FeesReturn->refunded_amount;?></td>
					</tr>
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($FeesReturnErrors['discom']) && isset($FeesReturnErrors['discom']['_empty']) && !empty($FeesReturnErrors['discom']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $FeesReturnErrors['discom']['_empty'];
					} ?>
					<tr>
						<td class="portlet-body">4.</td>
						<td class="portlet-body" colspan="2"><label>Name of DISCOM</label></td>
						<td class="portlet-body <?php echo $error_class_type;?>"><?php echo $this->Form->select('discom',$discom_list, array('label' => false,'empty'=>'-Select Discom-','class'=>'form-control','id'=>'discom','placeholder'=>'Discom')); 
						?><?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?></td>
					</tr>
					<tr>
						<td class="portlet-body serial_class">5.</td>
						<td class="portlet-body" colspan="3"><label>Details of Payment Receipt issued by GEDA </label></td>
						
					</tr>
					<tr>
						<td class="portlet-body">5.1</td>
						<td class="portlet-body" colspan="2" valign="top"><label class="col-md-4" style="margin-left: -15px;">Payment Receipt </label> <label class="col-md-8" style="margin-top: 19px;"><?php echo $this->Form->input('payment_receipt', array('label' => false,'class'=>'form-control','id'=>'payment_receipt','type'=>'checkbox','placeholder'=>'Disclaimer','onClick'=>'javascript:chk_payment_receipt()')); ?></label>
							<br><span class="italic_data">(Either provide GEDA Receipt No. or upload Indeminity Bond as per the format provided)</span>
						</td>
						<td class="portlet-body">
							<?php  
								$div_receipt_disp 		= (isset($FeesReturn->payment_receipt) && !empty($FeesReturn->payment_receipt)) ? '' : 'display: none;' ;
								$div_indemnity_disp 	= (isset($FeesReturn->payment_receipt) && !empty($FeesReturn->payment_receipt)) ? 'display: none;' : '' ;
							?>
							<div id="div_ind_bond" style="<?php echo  $div_indemnity_disp;?>">
								<label><a href="<?php echo URL_HTTP;?>SSD_Indemnity_Bond.docx"><i class="fa fa-download"></i>&nbsp;&nbsp;Now Download Indemnity Bond</a></label>
								<?php if(empty($selectFile)) { ?>
									<?php echo $this->Form->input('file_indemnity_bond', array('label' => false,'type'=>'file','id'=>'file_indemnity_bond','class'=>'form-control')); ?>
									<div id="file_indemnity_bond-file-errors"></div>
								<?php } ?>
								<?php if(!empty($FeesReturn->indemnity_bond)) { ?>
									<?php if ($Couchdb->documentExist($FeesReturn->id,$FeesReturn->indemnity_bond)) { ?>
										<?php
											echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/fr_indemnity_bond/'.encode($FeesReturn->id)."\">View Indemnity Bond</a></strong>";
											?>
									<?php } ?>
								<?php } ?>
							</div>
							<div id="div_receipt" class="div_receipt" style="<?php echo $div_receipt_disp;?>">
								<?php if(empty($selectFile)) { ?>
									<?php echo $this->Form->input('file_receipt', array('label' => false,'type'=>'file','id'=>'file_receipt','class'=>'form-control')); ?>
									<div id="file_receipt-file-errors"></div>
								<?php } ?>
								<span class="italic_data">(enclose the copy of original receipt)</span>
								<?php if(!empty($FeesReturn->receipt)) { ?>
									<?php if ($Couchdb->documentExist($FeesReturn->id,$FeesReturn->receipt)) { ?>
										<?php
											echo "<br><strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/fr_receipt/'.encode($FeesReturn->id)."\">View Receipt</a></strong>";
											?>
									<?php } ?>
								<?php } ?>
							</div>
						</td>
					</tr>
					<tr class="div_receipt" style="<?php echo $div_receipt_disp;?>">
						<td class="portlet-body">5.2</td>
						<td class="portlet-body" colspan="2"><label>Receipt No. </label></td>
						<td class="portlet-body"><?php echo $this->Form->input('receipt_no',["type" => "text",'label'=>false,"class" => "form-control","id"=>"receipt_no" ,'placeholder'=>'Receipt No. ']);?></td>
					</tr>
					<tr class="div_receipt" style="<?php echo $div_receipt_disp;?>">
						<td class="portlet-body" >5.3</td>
						<td class="portlet-body" colspan="2"><label>Receipt Date </label></td>
						<td class="portlet-body"><?php echo $this->Form->input('receipt_date',["type" => "text",'label'=>false,"class" => "form-control datepicker","id"=>"receipt_date" ,'placeholder'=>'Receipt Date']);?>
						</td>
					</tr>
					
					<tr class="div_receipt" style="<?php echo $div_receipt_disp;?>">
						<td class="portlet-body">5.4</td>
						<td class="portlet-body" colspan="2"><label>Receipt amount in Rs. </label></td>
						<td class="portlet-body"><?php echo $this->Form->input('demand_amount',["type" => "text",'label'=>false,"class" => "form-control","id"=>"demand_amount" ,'placeholder'=>'Receipt amount in Rs. ','onkeypress'=>'return validateDecimal(event);']);?></td>
					</tr>
					
					<?php /*
					<tr>
						<td class="portlet-body">5.</td>
						<td class="portlet-body" colspan="2"><label>Name of GETCO S/S</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('name_getco',["type" => "text",'label'=>false,"class" => "form-control","id"=>"name_getco" ,'placeholder'=>'Name of GETCO S/S']);?></td>
						
					</tr>
					<tr>
						<td class="portlet-body">6.</td>
						<td class="portlet-body" colspan="3"><label>Details of Demand Draft which was submitted by the applicant to GEDA at the time of registration</label></td>
						
					</tr>
					<tr>
						<td class="portlet-body">6.1</td>
						<td class="portlet-body" colspan="2"><label>Demand Draft No.</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('draft_no',["type" => "text",'label'=>false,"class" => "form-control","id"=>"draft_no" ,'placeholder'=>'Demand Draft No.','onkeypress'=>'return validateInteger(event);']);?></td>
					</tr>
					<tr>
						<td class="portlet-body">6.2</td>
						<td class="portlet-body" colspan="2"><label>Demand Draft Date</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('draft_date',["type" => "text",'label'=>false,"class" => "form-control datepicker","id"=>"draft_date" ,'placeholder'=>'Demand Draft Date']);?></td>
					</tr>
					<tr>
						<td class="portlet-body">6.3</td>
						<td class="portlet-body" colspan="2"><label>Bank Name</label></td>
						<td class="portlet-body" ><?php echo $this->Form->input('demand_bank_name',["type" => "text",'label'=>false,"class" => "form-control","id"=>"demand_bank_name" ,'placeholder'=>'Bank Name']);?></td>
					</tr>
					*/?>

					<tr>
						<td class="portlet-body">6.</td>
						<td class="portlet-body" colspan="3"><label>SPG/ Applicant's account details</label></td>
						
					</tr>
					<tr>
						<td class="portlet-body">6.1</td>
						<td class="portlet-body" colspan="2"><label>Account No.<br><span class="italic_data">(Account holder name must be same as mentioned in the GEDA registration )</span></label></td>
						<td class="portlet-body"><?php echo $this->Form->input('account_no',["type" => "text",'label'=>false,"class" => "form-control","id"=>"account_no" ,'placeholder'=>'Account No. ','onkeypress'=>'return validateInteger(event);']);?></td>
					</tr>
					<tr>
						<td class="portlet-body">6.2</td>
						<td class="portlet-body" colspan="2"><label>Bank Name</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('bank_name',["type" => "text",'label'=>false,"class" => "form-control","id"=>"bank_name" ,'placeholder'=>'Bank Name']);?></td>
					</tr>
					<tr>
						<td class="portlet-body">6.3</td>
						<td class="portlet-body" colspan="2"><label>IFSC code</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('ifsc_code',["type" => "text",'label'=>false,"class" => "form-control","id"=>"ifsc_code" ,'placeholder'=>'IFSC code']);?></td>
					</tr>
					<tr>
						<td class="portlet-body">6.4</td>
						<td class="portlet-body" colspan="2"><label>Upload applicant's cheque<br><span class="italic_data">(Account holder name must be same as mentioned in the GEDA registration )</span></label></td>
						<td class="portlet-body">
							<?php if(empty($selectFile)) { ?>
								<?php echo $this->Form->input('file_account_cheque', array('label' => false,'type'=>'file','id'=>'file_account_cheque','class'=>'form-control')); ?>
								<div id="file_account_cheque-file-errors"></div>
							<?php } ?>
							<?php if(!empty($FeesReturn->account_cheque)) { ?>
								<?php if ($Couchdb->documentExist($FeesReturn->id,$FeesReturn->account_cheque)) { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/fr_account_cheque/'.encode($FeesReturn->id)."\">View uploaded cheque</a></strong>";
										?>
								<?php } ?>
							<?php } ?>
						</td>
					</tr>
					<?php /*<tr>
						<td class="portlet-body">7.</td>
						<td class="portlet-body" colspan="3"><label>Details of Power Purchase Agreement</label></td>
					</tr>
					<tr>
						<td class="portlet-body">7.1</td>
						<td class="portlet-body" colspan="2"><label>Date of PPA signed with DISCOM</label></td>
						<td class="portlet-body"><?php echo $this->Form->input('date_ppa_signed',["type" => "text",'label'=>false,"class" => "form-control","id"=>"date_ppa_signed" ,'placeholder'=>'Date of PPA signed with DISCOM']);?></td>
					</tr>*/?>
					<tr>
						<td class="portlet-body" rowspan="2">7.</td>
						<td class="portlet-body" colspan="2" rowspan="2"><label>Date of PPA termination with DISCOM <br><span class="italic_data">(enclose the copy of PPA termination letter or Undertaking submitted to Discom)</span></label></td>
						<td class="portlet-body"><?php echo $this->Form->input('date_ppa_term',["type" => "text",'label'=>false,"class" => "form-control","id"=>"date_ppa_term" ,'placeholder'=>'Date of PPA termination with DISCOM']);?></td>
					</tr>
					<tr>
						<td class="portlet-body" style="width:48%;">
							<?php if(empty($selectFile)) { ?>
								<?php echo $this->Form->input('file_pan_card', array('label' => false,'type'=>'file','id'=>'file_pan_card','class'=>'form-control')); ?>
								<div id="file_pan_card-file-errors"></div>
							<?php } ?>
							<?php if(!empty($FeesReturn->pan_card)) { ?>
								<?php if ($Couchdb->documentExist($FeesReturn->id,$FeesReturn->pan_card)) { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/fr_pan_card/'.encode($FeesReturn->id)."\">View PPA termination</a></strong>";
										?>
								<?php } ?>
							<?php } ?>
						</td>
					</tr> 
					
					<tr>
						<td class="portlet-body">8.</td>
						<td class="portlet-body" colspan="3"><label class="col-md-12">I Agree to all the terms and conditions of GEDA
						<?php echo $this->Form->input('disclaimer', array('label' => false,'class'=>'form-control','type'=>'checkbox','placeholder'=>'Disclaimer')); ?></label></td>
					</tr>
					<tr>
						<td class="portlet-body"  colspan="4">
						<?php
						if(empty($FeesReturn->referenceno) && empty($view_status)) {
							
							echo $this->Form->button('Submit', ['type' => 'submit', 'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg m-right-10 btns','onclick'=>'javascript:click_submit();','id'=>'submit_fesibility']);
						}
						if($view_status == 1)
						{
							echo 'Go to <strong>Action</strong> button on the <a href="'.URL_HTTP.'fees-return-list">SSDSP Return List</a> to Approve or Edit.';
						}
						?>
						</td>
					</tr>
				</table>
			   
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
	
</div>
<div id="Approve_FA" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approval from Division</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('FApprove_FA',['name'=>'Approve_FA','id'=>'FApprove_FA']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'Approve_FA_approval_type','label' => true,'type'=>'hidden','value'=>'4']); ?>
					<?php echo $this->Form->input('appid',['id'=>'Approve_FA_application_id','label' => true,'type'=>'hidden','value'=>encode($ApplyOnlines->id)]); ?>
					<?php echo $this->Form->select('application_status',array("1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'JREDA_FA_application_status','label' => false]); ?><br />
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",      
																'id'=>'Approve_FA_reason',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Comments, if any']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_7','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FApprove_FA']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
/*var date = new Date();
date.setMonth(date.getMonth() + 1, 1);*/
$('.datepicker').datepicker({dateFormat: 'dd-mm-yy'});
$('#registration_date').datepicker({dateFormat: 'dd-mm-yy','maxDate': '31-03-2021'});
$('#date_ppa_term').datepicker({dateFormat: 'dd-mm-yy','maxDate': '31-05-2021'});
$('#date_ppa_signed').datepicker({dateFormat: 'dd-mm-yy'});


</script>
<script type="text/javascript">
$(document).ready(function(){
	<?php if(empty($selectFile)) { ?>
	$("#file_pan_card").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf","jpg","jpeg"],
		elErrorContainer: '#file_pan_card-file-errors',
		maxFileSize: '2048',
	});
	$("#file_copy_registration").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf","jpg","jpeg"],
		elErrorContainer: '#file_copy_registration-file-errors',
		maxFileSize: '2048',
	});
	$("#file_receipt").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf","jpg","jpeg"],
		elErrorContainer: '#file_receipt-file-errors',
		maxFileSize: '2048',
	});
	$("#file_indemnity_bond").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf","jpg","jpeg"],
		elErrorContainer: '#file_indemnity_bond-file-errors',
		maxFileSize: '2048',
	});$("#file_account_cheque").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf","jpg","jpeg"],
		elErrorContainer: '#file_account_cheque-file-errors',
		maxFileSize: '2048',
	});
<?php } ?>
	$(".subdivision_approval").click(function() {
		
		
		$("#form-main").submit();
	});
	$(".approved-dd").change(function(){
		if ($(this).val() == 1) {
			$(".reject-reason").addClass("hide");
			$(".subdivision_approval").html("Approved By Subdivision");
		} else if ($(this).val() != 1) {
			$(".reject-reason").removeClass("hide");
			$(".subdivision_approval").html("Submit");
		}
	});
});
function click_submit()
{
	$("#submit_fesibility").attr('disabled','disabled');
	$("#form-main").submit();
}
$(".approval_btn").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find(".application_status").val() == 2 && reason.length < 1) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("");
		$("#"+fromobj).find("#messageBox").html("Reason is required field.");
		return false;
	} else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.href='/apply-online-list';
			  }
		});
	}
	return false;
});

function validateInteger(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8) && (keycode < 48 || keycode > 57)) {
		return false;
	} else {
		return true;
	}
}
function validateDecimal(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
		return false;
	} else {
		var parts = key.srcElement.value.split('.');
		if (parts.length > 1 && keycode == 46) return false;
		return true;
	}
}
function changeCost() {

	var capacity = $('#capacity').val();
	var applicationfees	= capacity*<?php echo REFUNDED_AMOUNT;?>;
	var tax_amount 		= (applicationfees*<?php echo REFUNDED_GST_PER;?>)/100;
	var total_amount 	= applicationfees + tax_amount;
	$("#ref_amount").html(applicationfees);
	$("#gst_amount").html(tax_amount);
	$("#total_gst_amount").html(total_amount);
	
}
function chk_payment_receipt()
{
	if($("#payment_receipt").is(':checked')) {
		$("#div_ind_bond").hide();
		$(".div_receipt").show();
	} else {
		$("#div_ind_bond").show();
		$(".div_receipt").hide();
	}
}
</script>