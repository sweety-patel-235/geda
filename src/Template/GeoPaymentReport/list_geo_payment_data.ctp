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
			
		</div>
		<div class="row col-md-12">&nbsp;</div>
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box blue-madison noborder table-responsive">
				<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
					<thead>
					<tr>
						<th class="">Year - Month</th>
						<th class="">Geo Application Payment Data</th>
						<!-- <th class="">Installers Payment Data</th> -->
					</tr>
					<?php for($i=date('Y');$i>=2024;$i--) {
							$endMonth 		= 12;
							if($i==date('Y')) {
								$endMonth 	= intval(date('m'));
							}
							for($m=$endMonth;$m>=1;$m--)
							{
								$dateObj   = DateTime::createFromFormat('!m', $m);
								$monthName = $dateObj->format('F');
								$monthPass = $dateObj->format('m');
								?>
								<tr>
									<td style="text-align: left;padding: 5px !important;"><?php echo $i." - ".$monthName;?></td>
									<td style="text-align: left;padding: 5px !important;"><a href="<?php echo URL_HTTP.'GeoPaymentReport/donwloadGeoApplicationPayment/'.$i.'/'.$monthPass;?>">Download Geo Application Payment</a></td>
									<!-- <td style="text-align: left;padding: 5px !important;"><a href="<?php echo URL_HTTP.'GeoPaymentReport/donwloadInstallerPayment/'.$i.'/'.$monthPass;?>">Download Installer Payment</a></td> -->
								</tr>
								<?php
							}
					} ?>
					
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
		// $(".SubmitRequest").click(function() {
		//    $(this).attr("disabled","disabled");
		// 	if($('input[type=checkbox]:checked').length == 0)
		// 	{
		// 		var ErrorMSG = '<div class="alert alert-danger"><strong>ERROR!</strong> Please select at least one record to proceed further.</div>';
		// 		$(".MessageBlock").html(ErrorMSG);
		// 		$(this).removeAttr("disabled");
		// 		return false;
		// 	}
		// 	var request_ids = [];
		// 	$("input:checked").each(function() {request_ids.push($(this).val());});
		// 	$.ajax({
		// 		url: "/savesubsidyclaims",
		// 		type: "post",
		// 		data: {request_id:request_ids},
		// 		success: function(d) {
		// 			var response = $.parseJSON(d);
		// 			if (response.type == "ok") {
		// 				var ErrorMSG = '<div class="alert alert-success"><strong>SUCCESS!</strong> '+response.msg+'</div>';
		// 				$(".MessageBlock").html(ErrorMSG);
		// 				ReloadDataTable();
		// 			} else {
		// 				var ErrorMSG = '<div class="alert alert-danger"><strong>ERROR!</strong> '+response.msg+'</div>';
		// 				$(".MessageBlock").html(ErrorMSG);
		// 			}
		// 			$(".SubmitRequest").removeAttr("disabled");
		// 		},
		// 		error: function(d) {
		// 			var ErrorMSG = '<div class="alert alert-danger"><strong>ERROR!</strong> Something went wrong during process request. Please try again.</div>';
		// 			$(".MessageBlock").html(ErrorMSG);
		// 			$(".SubmitRequest").removeAttr("disabled");
		// 		}
		// 	});
		// });
	});


window.closeModal = function(){ $('#myModal').modal('hide'); };
$(document).on("click", ".approve_Status" , function() {

	var requestid = $(this).attr("data-id");
	
	$("#requestid").val(requestid);
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
				   $("#received_msg").val(result.response['received_msg']);
				   $("#return_status").val(result.response['status']);
				}
			}
		}
	});
	$('#approve_Status').modal('show');
});
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
</script>

