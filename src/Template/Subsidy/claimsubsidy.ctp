<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
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
						<?php echo $this->Form->input('request_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'request_no','placeholder'=>'Request No.')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->input('geda_application_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'geda_application_no','placeholder'=>'GEDA Registration No.')); ?>
					</div>
				</div>
				<div class="row col-md-12">
					<div class="col-md-3">
						<?php echo $this->Form->select('status',$REQUEST_STATUS,array('label' => false,'class'=>'form-control','empty'=>'-Select Request Status-','style'=>'margin-left:-15px;')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->select('recevied_status',$RECEVIED_STATUS,array('label' => false,'class'=>'form-control','empty'=>'-Select Verified Status-','style'=>'margin-left:-15px;')); ?>
					</div>
					<div class="col-md-6">
						<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Search</button>
						<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row col-md-12">&nbsp;</div>
		<div class="form-body">
			<div class="row col-md-12">
				<div class="col-md-6">
					<button type="button" class="btn green SubmitRequest">
						<i class="fa fa-handshake-o" aria-hidden="true"></i> Submit Request
					</button>
				</div>
			</div>
		</div>
		<div class="row col-md-12">&nbsp;</div>
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box blue-madison noborder">
				<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
					<thead>
					<tr>
						<th class="">Sr No.</th>
						<th class="">GEDA Registration No.</th>
						<th class="">Request No.</th>
						<th class="">Request Date</th>
						<th class="">Verified At GEDA</th>
						<th class="">Action</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
<div id="send_message" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Send Message</h4>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('SendMessageForm',['name'=>'SendMessageForm','id'=>'SendMessageForm']); ?>
				<div id="message_error"></div>
				<div class="form-group text">
					<div class="bold col-md-3">Query</div>
					<div class="col-md-9" id="last_message"></div>
				</div>
				<div class="form-group text">
				<?php echo $this->Form->input('last_message_id',['id'=>'last_message_id','label' => true,'type'=>'hidden']); ?>
				<?php echo $this->Form->input('appid',['id'=>'SendMessage_application_id','label' => true,'type'=>'hidden']); ?>
				<?php echo $this->Form->textarea('messagebox',[ "class" =>"form-control messagebox",
																'id'=>'messagebox',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Message or Comment']);
				?>
				</div>
				<div class="row">
					<div class="col-md-2">
					<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary sendmessage_btn','data-form-name'=>'SendMessageForm']); ?>
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
		resetcustomdates(true);
		resetdates();
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
						url: "/apply-onlines/ReplyToMessage",
						data: $("#"+fromobj).serialize(),
						success: function(response) {
							var result = $.parseJSON(response);
							if (result.success == 1) {
								$("#SendMessageForm").find(".messagebox").val('');
								$("#SendMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
								$("#send_message").modal('hide');
								ReloadDataTable();
							} else {
								$("#SendMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
							}
						}
					});
			}
		});
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
	function show_modal(application_id)
	{
		$("#SendMessage_application_id").val(application_id);
		$.ajax({
				type: "POST",
				url: "/apply-onlines/getlastsubsidymessage",
				data: "appid="+application_id,
				success: function(response) {
					$(".MessageBlock").html("");
					var result = $.parseJSON(response);
					$("#last_message_id").val(result.last_message.last_message_id);
					$("#last_message").html(result.last_message.last_message);
					$("#send_message").modal('show');
				}
			});
	}
</script>

