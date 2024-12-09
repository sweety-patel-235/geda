<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<?php
$this->Html->addCrumb($pagetitle);
?>
<?php echo $this->Form->create('Subsidy',array("id"=>"formmain","name"=>"searchClaimSubsidy",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
	<div class="container project-leads">
		<div class="col-md-12 MessageBlock"></div>
		<div class="col-md-12">
			<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
			<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
			<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
			<div class="form-body">
				<div class="row col-md-12">
					<div class="col-md-3 ">
						<?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->input('application_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'application_no','placeholder'=>'Application No.')); ?>
					</div>
				</div>
				<div class="row col-md-12">
					<div class="col-md-3">
						<?php echo $this->Form->select('approval_status',$REQUEST_STATUS,array('label' => false,'class'=>'form-control','style'=>'margin-left:-15px;')); ?>
					</div>
					
					<div class="col-md-6">
						<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Search</button>
						<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row col-md-12">&nbsp;</div>
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box blue-madison noborder newregistration">
				<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
					<thead>
					<tr>
						<th class="">Sr No.</th>
						<th class="">Application No.</th>
						<th class="">Created On</th>
						<th class="">Created By</th>
						<th class="">Approval Status</th>
						<th class="">Approved By</th>
						<th class="">Approved On</th>
						<th class="">Action</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog2">
		<div class="modal-content">
			
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div id="approve_Status" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Delete Application Approval</h4>
			</div>
			<div class="modal-body">
				<div class="">
					<label>Click on Submit to approve the request and then go to action button in order to delete application from the system.</label>
				</div>
				<?php
				echo $this->Form->create('approve_request',['name'=>'frm_approve_request','id'=>'approve_request']); ?>
				<div id="message_error"></div>
				<div class="form-group text">
				<?php echo $this->Form->input('insid',['id'=>'insid','label' => true,'type'=>'hidden']); ?>
				<?php echo $this->Form->select('geda_approval',array("1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'geda_approval','label' => false,'empty'=>'Pending','onChange'=>'javascript:changeType();']); ?><br />
				<?php echo $this->Form->textarea('reject_reason',[ "class" =>"form-control reason messagebox",      
															'id'=>'reject_reason',
															'cols'=>'50','rows'=>'5',
															'label' => false,
															'placeholder' => 'Comments, if any']);
				?>
				</div>
				<div class="row">
					<div class="col-md-2" id="submit_approval">
					<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_5','label'=>false,'class'=>'btn btn-primary request_approval_btn','data-form-name'=>'approve_request']); ?>
					
					</div>
					<div class="col-md-2" id="delete_approval">
						<?php echo $this->Form->input('Delete Application',['type'=>'button','id'=>'delete_btn','label'=>false,'class'=>'btn btn-primary delete_approval_btn','data-form-name'=>'approve_request']); ?>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<div id="delete_application" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Delete Application</h4>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('DeleteApplicationForm',['name'=>'DeleteApplicationForm','id'=>'DeleteApplicationForm']); ?>
				<div id="message_error"></div>
				<div class="form-group text">
				<?php echo $this->Form->input('application_id',['id'=>'Delete_application_id','label' => true,'type'=>'hidden']); ?>
				<?php echo $this->Form->textarea('reason',[ "class" =>"form-control messagebox",
																'id'=>'messagebox',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Reason']);
				?>
				</div>
				<div class="row">
					<div class="col-md-2">
						<?php echo $this->Form->input('Ok',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary deleteapplication_btn','data-form-name'=>'DeleteApplicationForm']); 
						?>
					</div>
					<div class="col-md-2">
						<?php 
					 	echo $this->Form->input('Cancel',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary ','data-dismiss'=>"modal"]); ?>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<div id="fetch_details" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Delete Application Request</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<lable class="col-md-12"><strong>Reason</strong></lable>
						</div>
						<div class="row">
							<div class="col-md-12" id="reason_fetch"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->input('consent_not_available',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[1=>"I don't have Consumer Consent Letter",2=>"I don't have Installer Consent Letter",0=>"I have Both"],'label' => false]); ?>
						<div class="row">
							<lable class="col-md-5"><strong>Consumer Consent Letter</strong></lable>
							<div class="col-md-7" id="consumer_consent_letter">
								
							</div>

						</div>
					</div>
				</div>
				<div class="row" style="margin-right: 2px;margin-left: -4px;">
					<div class="col-md-12"  id="consumer_consent_letter-file-errors"></div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<lable class="col-md-5"><strong>Installer Consent Letter</strong></lable>
							<div class="col-md-7" id="vendor_consent_letter">
								
							</div>

						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<?php echo $JqdTablescr; ?>

		
	$(document).ready(function() {
		resetcustomdates(true);
		resetdates();
		$('.chosen-select').chosen();
		$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	});
	function ReloadDataTable()
	{
		$('#searchbtn').trigger("click");
	}
	function resetcustomdates(onload)
	{
		var period      = $('#SearchPeriod').val();
		var Today       = '<?php echo date("d-m-Y");?>';
		var Yesterday   = '<?php echo date("d-m-Y",strtotime("yesterday"));?>';
		$("#DateFrom").removeAttr("disabled");
		$("#DateTo").removeAttr("disabled");
		if(!onload) {
			$("#DateFrom").val(Yesterday);
			$("#DateTo").val(Today);
		}
		$("#DateFrom").datepicker({format:'dd-mm-yyyy',autoclose: true});
		$("#DateTo").datepicker({format:'dd-mm-yyyy',autoclose: true});
	}
	function resetdates()
	{
		
	}
	function resetsearch()
	{
		window.location.reload();
	}
	function validatesearchform()
	{
		return true;
	}
	$("body").on('click','.showModel',function(){
	var modelheader = $(this).data("title");
	var modelUrl = $(this).data("url");
	document_window = $(window).width() - $(window).width()*0.05;
	document_height = $(window).height() - $(window).height() * 0.20;  
	modal_body = '<div class="modal-header" style="min-height: 45px;">'+
	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
	'</div>'+
	'<div class="modal-body">'+
	'<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
	'</div>';
	
	$('#myModal').find(".modal-content").html(modal_body);
	$('#myModal').modal('show');
	$('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
	$('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	return false;
});
function setAppID(application_id) {
	$("#Delete_application_id").val(application_id);
}
function changeType()
{
	
	if($("#geda_approval").val() == 1)
	{
		$("#delete_approval").show();
	}
	else
	{
		$("#delete_approval").hide();
	}
}
$(".delete_approval_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	}
	
	$("#DeleteApplicationForm").find(".messagebox").val($("#reject_reason").val());
	$(".deleteapplication_btn").trigger("click");
	$(".request_approval_btn").trigger("click");
});
$(".deleteapplication_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/RemoveApplicationMember",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#DeleteApplicationForm").find(".messagebox").val('');
						$("#DeleteApplicationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						//location.reload();
						ReloadDataTable();
					} else {
						$("#DeleteApplicationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
window.closeModal = function(){ $('#myModal').modal('hide'); };
$(document).on("click", ".approve_Status" , function() {
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var insid = $(this).attr("data-id");
	$("#insid").val(insid);
	$.ajax
	({
		type: "POST",
		url: "/ApplyOnlines/fetchDeleteAppRequest",
		data: {'insid':insid},
		success: function(response) {
			var result = $.parseJSON(response);
				if (result.type == "ok") {
				{
					console.log(result.response['reject_reason']);
					$("#Delete_application_id").val(result.response['encode_application_id']);
					$("#reject_reason").val(result.response['reject_reason']);
					$("#geda_approval").val(result.response['status']);
					$("#submit_approval").show();

					if(result.response['status'] == 2) {
						$("#submit_approval").hide();
						$("#delete_approval").hide();
					} 
					changeType();
				}
			}
		}
	});
	$('#approve_Status').modal('show');
});
$(document).on("click", ".fetch_details" , function() {
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var insid = $(this).attr("data-id");
	$("#insid").val(insid);
	$.ajax
	({
		type: "POST",
		url: "/ApplyOnlines/fetchDeleteAppRequest",
		data: {'insid':insid},
		success: function(response) {
			var result = $.parseJSON(response);
			if (result.type == "ok") 
			{
				console.log(result.response['reject_reason']);
				$("#consumer_consent_letter").html(result.response['consumer_consent_letter']);
				$("#vendor_consent_letter").html(result.response['vendor_consent_letter']);
				$("#reason_fetch").html(result.response['reason']);
				$("#consent-not-available-1").prop("checked",false);
				$("#consent-not-available-2").prop("checked",false);
				$("#consent-not-available-0").prop("checked",false);
				if(result.response['consent_not_available'] == 1) {
					$("#consent-not-available-1").prop("checked", true);
				} else if(result.response['consent_not_available'] == 2) {
					$("#consent-not-available-2").prop("checked", true);
				} else {
					$("#consent-not-available-0").prop("checked", true);
				}
			}
		}
	});
	$('#fetch_details').modal('show');
});

$(".request_approval_btn").click(function(event) {
	event.preventDefault();
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1 && $("#"+fromobj).find("#geda_approval").val()==2) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ApproveDeleteRequest",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						ReloadDataTable();
						//location.reload();
						//window.location.reload();
						$('#approve_Status').modal('hide');
					} else {
						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
		}
	});
</script>