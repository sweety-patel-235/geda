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
			<?php echo $this->Form->hidden('download',array("value"=>0,"id"=>"download")); ?>
			<div class="form-body">
				<div class="row col-md-12">
					<div class="col-md-3 ">
						<?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->input('fees_return_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'fees_return_no','placeholder'=>'Fees Return No.')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->input('registration_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'registration_no','placeholder'=>'GEDA Registration No.')); ?>
					</div>
				</div>
				<div class="row col-md-12">
					<div class="col-md-3">
						<?php echo $this->Form->select('status',$REQUEST_STATUS,array('label' => false,'class'=>'form-control','empty'=>'-Select Request Status-','style'=>'margin-left:-15px;')); ?>
					</div>
					
					<div class="col-md-6">
						<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Search</button>
						<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="button" class="btn green btn-download"><i class="fa fa-file-excel-o"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="row col-md-12">&nbsp;</div>
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box blue-madison noborder table-responsive">
				<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
					<thead>
					<tr>
						<th class="">Sr No.</th>
						<th class="">Fees Return No.</th>
						<th class="">Created</th>
						<th class="">Applicant Name</th>
						<th class="">Registration No.</th>
						<th class="">Registration Date</th>
						<th class="">Capacity</th>
						<th class="">Discom</th>
						<th class="">Receipt No.</th>
						<th class="">Status</th>
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
				<h4 class="modal-title">Approved Request</h4>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('approve_request',['name'=>'frm_approve_request','id'=>'approve_request']); ?>
				<div id="message_error"></div>
				<div class="form-group text">
					<?php echo $this->Form->input('requestid',['id'=>'requestid','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('return_status',array("0"=>"Pending","1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'return_status','label' => false]); ?>
					<div class="col-md-12" id="approval_text">
						
					</div><br />

					<?php echo $this->Form->textarea('received_msg',[ "class" =>"form-control reason",      
																'id'=>'received_msg',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Comments, if any']);
					?>
					
				</div>
				
				<div class="row">
					<div class="col-md-4"><label>Project Capacity kW (AC)</label>
						
					</div>
					<div class="col-md-8" id="capacity">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>D.D Amount (Rs.)</label>
						
					</div>
					<div class="col-md-8" id="demand_amount">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>Total Project Fees (Rs.)</label>
						
					</div>
					<div class="col-md-8" id="project_fees">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>Name of account holder</label>
						
					</div>
					<div class="col-md-8" id="name_account_holder">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>Account No.</label>
						
					</div>
					<div class="col-md-8" id="account_no">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>IFSC Code</label>
						
					</div>
					<div class="col-md-8" id="ifsc_code">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>Bank Name</label>
						
					</div>
					<div class="col-md-8" id="bank_name">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>Payment Reference No.</label>
						
					</div>
					<div class="col-md-8" id="reference_no">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"><label>Refundable amount (Rs.)</label>
						
					</div>
					<div class="col-md-8">
						<?php echo $this->Form->input('refunded_amount',["class" =>"form-control",'id'=>'refunded_amount','label' => false,'onkeypress'=>"return validateDecimal(event)"]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" id="payment_response_html">
						
					</div>
					<div class="col-md-2" id="approval_button">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_5','label'=>false,'class'=>'btn btn-primary request_approval_btn','data-form-name'=>'approve_request']); ?>
					</div>
					<div class="col-md-10" id="payment_button">
						
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<?php echo $JqdTablescr; ?>
	$(document).ready(function() {
		$('.btn-download').click(function(){
			$("#download").val(1);
			$("#formmain").submit();
			return false;
		});
		resetcustomdates(true);
		resetdates();
		$('.chosen-select').chosen();
		$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
		$(".SubmitRequest").click(function() {
		   $(this).attr("disabled","disabled");
			if($('input[type=checkbox]:checked').length == 0)
			{
				var ErrorMSG = '<div class="alert alert-danger"><strong>ERROR!</strong> Please select at least one record to proceed further.</div>';
				$(".MessageBlock").html(ErrorMSG);
				$(this).removeAttr("disabled");
				return false;
			}
			var request_ids = [];
			$("input:checked").each(function() {request_ids.push($(this).val());});
			$.ajax({
				url: "/savesubsidyclaims",
				type: "post",
				data: {request_id:request_ids},
				success: function(d) {
					var response = $.parseJSON(d);
					if (response.type == "ok") {
						var ErrorMSG = '<div class="alert alert-success"><strong>SUCCESS!</strong> '+response.msg+'</div>';
						$(".MessageBlock").html(ErrorMSG);
						ReloadDataTable();
					} else {
						var ErrorMSG = '<div class="alert alert-danger"><strong>ERROR!</strong> '+response.msg+'</div>';
						$(".MessageBlock").html(ErrorMSG);
					}
					$(".SubmitRequest").removeAttr("disabled");
				},
				error: function(d) {
					var ErrorMSG = '<div class="alert alert-danger"><strong>ERROR!</strong> Something went wrong during process request. Please try again.</div>';
					$(".MessageBlock").html(ErrorMSG);
					$(".SubmitRequest").removeAttr("disabled");
				}
			});
		});
	});
	function ReloadDataTable()
	{
		$('#download').val(0);
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
window.closeModal = function(){ $('#myModal').modal('hide'); };
$(document).on("click", ".approve_Status" , function() {

	var requestid 	= $(this).attr("data-id");
	var approved_by = $(this).attr("data-approved-by");
	var approved_on = $(this).attr("data-approved-on");
	
	$("#requestid").val(requestid);
	fetchStatusRequest(requestid,approved_by,approved_on);
	$('#approve_Status').modal('show');
});
function fetchStatusRequest(requestid,approved_by,approved_on)
{
	//$("#payment_response_html").html("");
	$.ajax
	({
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?= json_encode($this->request->param('_csrfToken')); ?>
				);
		},
		type: "POST",
		url: "/FeesReturn/fetchFeesRequest",
		data: {'requestid':requestid},
		success: function(response) {
			var result = $.parseJSON(response);
				if (result.type == "ok") {
				{
					//console.log(result.response['received_msg']);
				   $("#received_msg").val(result.response['received_msg']);
				   if(result.response['refunded_amount'] > 0) {
				   		$("#refunded_amount").val(result.response['refunded_amount']);
				   } else {
				   		$("#refunded_amount").val(result.response['refundable_amount']);
				   }
					$("#project_fees").html(NumFormat(result.response['refundable_amount']));
					$("#demand_amount").html(NumFormat(result.response['demand_amount']));
					$("#capacity").html(result.response['capacity']);
					$("#return_status").val(result.response['status']);
					$("#name_account_holder").html(result.response['spg_applicant']);
					$("#account_no").html(result.response['account_no']);
					$("#ifsc_code").html(result.response['ifsc_code']);
					$("#bank_name").html(result.response['bank_name']);
					$("#reference_no").html(result.response['referenceno']);
					if(result.response['referenceno'] !='' && result.response['referenceno'] !=null && result.response['payment_transfer_completed'] !=null) 
					{
						$("#payment_response_html").html("<span class='alert-success'>Payment Transferred Successfully.</span>");
					}
					
					if(result.response['status'] >=1) {
						$("#approval_text").html('Request Approved by '+approved_by+' on date '+approved_on);
						$("#approval_button").hide();
						$("#return_status").hide();
						$("#payment_button").html(result.response['payment_button']);
						$("#payment_button").show();
					}
				}
			}
		}
	});
}
$(".request_approval_btn").click(function(event) {
	event.preventDefault();
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
		$.ajax({
				type: "POST",
				url: "/FeesReturn/ApproveRequest",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						//location.reload();
						//window.location.reload();
						$('#approve_Status').modal('hide');
					} else {
						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	
	});
/*$(".request_payment_btn").click(function(event) {
	event.preventDefault();
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
		$.ajax({
				type: "POST",
				url: "/FeesReturn/PaymentRequest",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						//location.reload();
						//window.location.reload();
						$('#approve_Status').modal('hide');
					} else {
						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	
	});*/
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
function NumFormat(number){
	var outputformat = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2,minimumFractionDigits: 2 }).format(number);
	return outputformat;
}
function ClickPay(requestId)
{
	$("#payment_button").html("<span class='alert-success'>Payment is going to process.....</span>");
	$.ajax({
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?= json_encode($this->request->param('_csrfToken')); ?>
				);
		},
		type: "POST",
		url: "/FeesReturn/PaymentRequestProcess",
		data: {request_id:requestId},
		success: function(response) {
			var result = $.parseJSON(response);
			if(result.message == 'Login') {
				window.location.reload();
			} else {
				if (result.success == 1) {
					$("#payment_response_html").html("<span class='alert-success'>"+result.message+"</span>");
				} else {
					$("#payment_response_html").html("<span class='alert-danger'>"+result.message+"</span>");
				}
				fetchStatusRequest(requestId,result.approved_by,result.approved_on);
			}
		}
	});
}
function ClickGetStatus(requestId)
{
	$("#payment_response_html").html("");
	$("#payment_button").html("<span class='alert-success'>Fetch payment status is going to process.....</span>");
	$.ajax({
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?= json_encode($this->request->param('_csrfToken')); ?>
				);
		},
		type: "POST",
		url: "/FeesReturn/GetPaymentUpdateDetails",
		data: {request_id:requestId},
		success: function(response) {
			var result = $.parseJSON(response);
			if(result.message == 'Login') {
				window.location.reload();
			} else {
				if (result.success == 1) {
					$("#payment_response_html").html("<span class='alert-success'>"+result.message+"</span>");
				} else {
					$("#payment_response_html").html("<span class='alert-danger'>"+result.message+"</span>");
				}
				fetchStatusRequest(requestId,result.approved_by,result.approved_on);
			}
		}
	});
}

</script>

