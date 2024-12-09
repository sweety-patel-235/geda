<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style>
.form-horizontal .radio {

	padding-top: 0px !important;
}
.check-box-address{
	margin-top: 20px !important;
}
.mendatory_field{
	color:red;
}
.nk_tabs .tab-content a {
    color: #444 !important;
}
.chosen-container .chosen-results {
    max-height:200px;
}
.chosen-container.chosen-container-single {
	width: 343px !important; /* or any value that fits your needs */
}
.row {
	margin-right: 0px !important;
}
.radio {
	margin-bottom: 0px !important;
	margin-top: 0px !important;
}
.applay-online-from input[type="checkbox"] {
    width: 18px;
    float: left;
    margin-top: 5px !important;
    margin-left: 0px !important;
    margin-right: 5px !important;
}
.textCheckeboxLeft {
	margin-left: 25px !important;
}
.text-success {
	color:#4CC972 !important;
}
</style>
<?php
	$allocatedCategory 	= 3;
	$this->Html->addCrumb($pageTitle);
	$Report 			= "";
	/*if (isset($applyonlineapproval) && !empty($applyonlineapproval) ) {
		$Report 		= 1;
	}
	if($create_project=='1')
	{
		$str_url 		= '';
	}
	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_PATH.$ApplyOnlines->id.'/';
	}
	$IMAGE_EXT                  = array("png","jpg","gif","jpeg","bmp");

	
	$IsInstallerAllowedToSubmit = true;
	$ALERT_MESSAGE              = "";*/

	/*if($this->Session->read('Customers.customer_type')=='installer' && ($tab=='tab_1' || $tab=='') && $create_project=='1')
	{

		$CustomerID                 = $this->Session->read('Customers.id');
		$IsInstallerAllowedToSubmit = $ApplyOnlineObj->IsInstallerAllowedToSubmit($CustomerID);
		if (!$IsInstallerAllowedToSubmit) {
			$ALERT_MESSAGE = "You are not allowed to submit application for more than 140 kW. For further details contact GEDA office at Gandhinagar, GJ.";
		}
	}*/

	/** STOP B CATEGORY INSTALLERS TO SUBMIT THE APPLICATION */
	/*$newSchemeApp 		= 0;
	$pvCapacityText 	= 'DC';
	if(isset($ApplyOnlines->created) && strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
		$newSchemeApp 	= 1;
		$pvCapacityText = 'AC';
	}
	echo $this->Form->create($ApplyOnlines,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'contactForm', 'url' => '/apply-onlines/'.$str_url,'autocomplete'=>'off','onSubmit'=>'return CheckFormSubmit();'));*/
?>

<!-- File: src/Template/Users/login.ctp -->
<div class="container applay-online-from">
	<div class="row">
		<h2 class="col-md-12 col-lg-12 col-sm-12 mb-sm mt-sm"><strong>Assigned Project</strong></h2>
		
	</div>
	<?php echo $this->Flash->render('cutom_admin');?>
	<?php echo $this->Form->create($Workorder,['type'=>'file','name'=>'workorder_form','id'=>'workorder_form']);?>
	<div class="row">
		<div class="form-group">
			<div class="col-md-12 table-responsive">
				<table id="tbl_wororder_info" class="table table-striped table-bordered table-hover custom-greenhead">
					<thead class="thead-dark">
						<th scope="col" width="20%">Project</th>
						<th scope="col" width="15%">Assigned Capacity (in MW)</th>
						<th scope="col" width="15%">Approved On</th>
						<th scope="col" width="20%">Reject Reason</th>
						<th scope="col" width="17%">RE Applications</th>
						<th scope="col" width="12%">Action</th>
					</thead>
					<tbody>
						<?php if (!empty($developerAssignedDetails) && isset($developerAssignedDetails)) { 
							foreach($developerAssignedDetails as $key=>$value) {
								$encode_application_id = encode($value->id);
								?>
								<tr class="mainRow">
									<td valign="top" class="">
										<?php echo $value->workorder_no; ?>
									</td>
									<td valign="top" class="">
										<?php echo $value->capacity; ?>
									</td>
									<?php /*<td valign="top" class=""  >
										<?php if($value->status == 0) { echo 'Pending'; } 
											else if($value->status == 1) { echo 'Accepted'; }
											else  { echo 'Rejected'; } ?>
									</td>*/?>
									<td valign="top" class=""  >
										<?php echo !empty($value->approved_date) ? date('d-M-Y',strtotime($value->approved_date)) : ''; ?>
									</td>
									<td valign="top" class=""  >
										<?php echo $value->reject_reason; ?>
									</td>
									<td valign="top" class=""  >
										<?php $arrApplications = $DeveloperAssignWorkorder->getWorkOrderMappedApplication($value->id);
											if(!empty($arrApplications)) {
												foreach($arrApplications as $keyMapped=>$applicationMapped) {
													echo ($keyMapped > 0) ? '<br>'.$applicationMapped : $applicationMapped;
												}
											}
										?>
									</td>
									<td valign="top" class=""  >
										<?php if($value->status == 0) { ?>
											<button type="button" class="btn green SubmitRequest" onclick="javascript:approve_workorder('<?php echo encode($value->id);?>');" title="Accept">
												<i class="fa fa-check" aria-hidden="true"></i> Accept
											</button>
											<button type="button" class="btn btn-danger SubmitRequest" onclick="javascript:show_modal('<?php echo encode($value->id);?>');" title="Reject">
												<i class="fa fa-times" aria-hidden="true"></i> Reject
											</button> 
										<?php } else if($value->status == 1) { ?>
											<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Accepted </span>
										<?php } else { ?>
											
											<button type="button" class="btn green SubmitRequest" onclick="javascript:approve_workorder('<?php echo encode($value->id);?>');">
												<i class="fa fa-check" aria-hidden="true"></i> Accept
											</button>
											
											<i class="fa fa-times" aria-hidden="true"></i> <span class="text-danger bold" title="<?php echo $value->reject_reason;?>">Rejected </span>
										<?php } ?>
                        			</td>
                        			
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr class="mainRow">
								<td valign="top" class="" colspan="5">
									 No Record Found...
									
								</td>
								
							</tr>
						
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<div id="reject_reason" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Reject Reason</h4>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('RejectReasonForm',['name'=>'RejectReasonForm','id'=>'RejectReasonForm']); ?>
				<div id="message_error"></div>
				<div class="form-group text">
				<?php echo $this->Form->input('assigned_workorder_id',['id'=>'assigned_workorder_id','label' => true,'type'=>'hidden']); ?>
				<?php echo $this->Form->textarea('messagebox',[ "class" =>"form-control messagebox",
																'id'=>'messagebox',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Message or Comment']);
				?>
				</div>
				<div class="row">
					<div class="col-md-2">
					<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary sendmessage_btn','data-form-name'=>'RejectReasonForm']); ?>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script language="javascript">
function approve_workorder(wo_id)
{
	swal({
		title: 'Are you sure you want to accept this work order? click on "Accept".',
		text: '',
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Accept",
		cancelButtonText: "No",
		closeOnConfirm: false,
		closeOnCancel: false,
		html:true
	},
	function(isConfirm) {
		if (isConfirm) {
			window.location.href="/DeveloperSettings/approve_assigned_workorder/"+wo_id;
		} else {
			swal("Cancelled", "", "error");
		}
	});
}
function show_modal(wo_id)
{
	$("#assigned_workorder_id").val(wo_id);
	$(".MessageBlock").html("");
	$("#reject_reason").modal('show');

}
$(".sendmessage_btn").click(function() {
		var fromobj = $(this).attr("data-form-name");
		$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		var messagebox = $("#"+fromobj).find(".messagebox").val();
		if (messagebox.length < 1) {
			$("#"+fromobj).find("#message_error").html("");
			$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
			$("#"+fromobj).find("#message_error").html("Message is required field.");
			$("#"+fromobj).find("#message_error").removeClass("hide");
			return false;
		} else {
			$.ajax({
				type: "POST",
				url: "/DeveloperSettings/reject_assigned_workorder",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					if (result.success == 1) {
						$("#RejectReasonForm").find(".messagebox").val('');
						$("#RejectReasonForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#reject_reason").modal('hide');
						window.location.href='<?php echo URL_HTTP;?>DeveloperSettings/assigned_workorder';
					} else if (result.success == 2) {
						
						window.location.href='<?php echo URL_HTTP;?>home/';
					} else {
						$("#RejectReasonForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
		}
	});
</script>
