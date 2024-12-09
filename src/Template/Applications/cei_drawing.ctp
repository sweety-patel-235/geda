<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.rowcat .col-md-6 {
border: 1px solid #c1c1c1;
}
.rowcat .control-label {
text-align: right;
}
.rowcat1 .row {
border: 1px solid #c1c1c1;
padding: 7px;
}
.form-horizontal .radio {
	
	padding-top: 0px !important;
}
.check-box-address{
	margin-top: 20px !important;
}
.applay-online-from input[type="checkbox"] {
	width: 18px;
	float: left;
	margin-top: 15px;
}
.mendatory_field
{
  color : red;
}
#serialData .table td {
	text-align: left !important;
}
.table-bordered {
    border: 1px solid #dee2e6 !important; 
}
.fieldset
{
    border: 1px solid #ddd !important;
    margin: 0;
    min-width: 0;
    padding: 10px;
    position: relative;
    border-radius:4px;
    background-color:#f5f5f5;
    padding-left:10px!important;
}
.fieldset-legends
{
    font-size:14px;
    font-weight:bold;
    margin-bottom: 0px;
    width: 35%;
    border: 1px solid #dddddd;
    border-radius: 4px;
    padding: 5px 5px 5px 10px;
    background-color: #dddddd;
}
#tbl_wind_info th, td {
	white-space: nowrap !important;
	flex-flow: nowrap !important;
	flex-wrap: nowrap !important;
}
#tbl_wind_info th.sorting {
	white-space: nowrap !important;
	flex-flow: nowrap !important;
	flex-wrap: nowrap !important;
}
</style>
<?php
	$this->Html->addCrumb('RE Application','applications-list'); 
	$this->Html->addCrumb($pageTitle); 
	$DOCUMENT_PATH 		= "";
	$titleClass         = "col-md-8";
	
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container-fluid applications-from">
	<div class="row col-md-12">
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> CEI Drawing </h4>
		<div class="col-md-4" style="margin-top:30px;text-align:right">
			<span style="font-size:18px;color:<?php echo $applicationCategory->color_code;?>">
				<strong style="text-align:left;"><?php echo isset($applicationCategory->category_name) ? $applicationCategory->category_name : '';?></strong></span><br>&nbsp;&nbsp;Application No.: <?php echo $applyOnlinesData->application_no;?>
		</div>
	</div>
 	<div class="row">
	 	<div class="col-md-12 ">

			<div class="table table-responsive table-bordered noborder" >
				 
				 <table id= "tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead" >

				  	<tr class="thead-dark">
				  		<thead class="thead-dark">
				  		<th colspan="4" style="text-align:center;" >CEI Drawing </th>
				  		</thead>
				  	</tr>
				  	<tr >
				  		<td  style="text-align:center;" >Sr No </td>
				  		<td  style="text-align:center;" >WTG Location </td>
				  		<td  style="text-align:center;" >CEI Drawing </td>
				  		<td  style="text-align:center;" >Status</td>
				  	</tr>
				  	
				  	<tbody>
				  		<?php $counter =1;
				  		 foreach ($wtg_applications as $key => $value) {
				  		 	$drawing_status 		= $CeiReApplicationDetails->check_drawing_status($value['id']);

				  		 	?>

				  			<tr >
						  		<td style="text-align:center;" ><?php echo $counter ?> </td>
						  		<td style="text-align:center;" ><?php echo $value['wtg_location'];?> </td>
						  		<td style="text-align:center;" ><a href="javascript:;" data-toggle="modal" data-target="#DRAWING_Status" class="DRAWING_Status dropdown-item" data-id="<?php echo encode($value->application_id); ?>" data-geoid ="<?php echo $value->id; ?>"><i class="fa fa-check-square-o"></i> CEI Drawing Application Ref. No.
													</a></td>
						  		<td style="text-align:center;" > <?php echo $drawing_status->drawing_app_status ?></td>
						  		
						  	</tr>
				  		<?php $counter++ ; }  ?>
				  		
				  		
				  		
				  </tbody>	
				</table>
				
			</div>
		</div>
		<div class="row col-md-12">
			<div class="col-md-3">
				<?php echo $this->Html->link('Back',['controller'=>'','action' => 'applications-list'],['class'=>'next btn btn-primary btn-md  cbtnsendmsg btn-default']); ?>
			</div>
		</div>
	</div>
	<div id="DRAWING_Status" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">CEI Drawing Application Ref. No.</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('DCEI_Status', ['name' => 'DRAWING_Status', 'id' => 'DCEI_Status']); ?>
						<div id="messageBox"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('approval_type', ['id' => 'DRAW_approval_type', 'label' => true, 'type' => 'hidden', 'value' => '51']); ?>
							<?php echo $this->Form->input('appid', ['id' => 'DRAW_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<?php echo $this->Form->input('app_geo_loc_id', ['id' => 'DRAW_app_geo_loc_id', 'label' => true, 'type' => 'hidden']); ?>

						</div>
						<div class="row">
							<div class="col-md-6">
								<?php echo $this->Form->input('drawing_app_no', ['label' => false, 'placeholder' => 'CEI drawing application number', 'id' => 'drawing_app_no_val']); ?>
							</div>
							<div class="col-md-6">
								<?php echo $this->Form->input('Fetch Status', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary', 'id' => 'fetch_status_drawing', 'data-form-name' => 'DCEI_Status']); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>CEI Drawing Application Status:
									<?php echo $this->Form->input('drawing_app_status', ['label' => false, 'type' => 'hidden', 'id' => 'drawing_app_status_frm']); ?></label>
							</div>
							<div class="col-md-6" id="drawing_app_status_html_frm"></div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'id' => '', 'label' => false, 'class' => 'btn btn-primary approval_btn_drawing', 'data-form-name' => 'DCEI_Status']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
</div>



<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$(".DRAWING_Status").click(function() {
	var application_id = $(this).attr("data-id");
	var app_geo_loc_id = $(this).attr("data-geoid");
	$("#DRAW_application_id").val(application_id);
	$("#DRAW_app_geo_loc_id").val(app_geo_loc_id);
	console.log(application_id);
	$.ajax({
		type: "POST",
		url: "/ApplyOnlines/fetchceidata",
		data: {
			'app_id': application_id
		},
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result);
			if (result.type == "ok") {
				$("#drawing_app_no_val").val(result.response['drawing_app_no']);
				$("#drawing_app_status_html_frm").html(result.response['drawing_app_status']);
				$("#drawing_app_status_frm").val(result.response['drawing_app_status']);
			}
			// window.location.reload();
		}
	});
});
$("#fetch_status_drawing").click(function() {
	var fromobj = $(this).attr("data-form-name");
	var drawing_number = $("#" + fromobj).find("#drawing_app_no_val").val();
	var app_id = $("#" + fromobj).find("#DRAW_application_id").val();
	var geo_id = $("#" + fromobj).find("#DRAW_app_geo_loc_id").val();
	if ($("#" + fromobj).find("#drawing_app_no_val").val() == '') {
		$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#" + fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	} else {
		$.ajax({
			type: "POST",
			url: "/Applications/fetch_restatus_api_all",
			data: {
				'drawing_number': drawing_number,
				'app_id': app_id,
				'api_type': 'drawing'
			},
			beforeSend: function(xhr) {
				xhr.setRequestHeader(
					'X-CSRF-Token',
					<?php echo json_encode($this->request->param('_csrfToken')); ?>
				);
			},
			success: function(response) {
				var result = $.parseJSON(response);
				console.log(result);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				} else {
					$("#" + fromobj).find("#drawing_app_status_html_frm").html(result.response);
					$("#" + fromobj).find("#drawing_app_status_frm").val(result.response);
					$("#DCEI_Status").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
				}
			}
		});
	}

});
$(".approval_btn_drawing").click(function() {
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#" + fromobj).find("#messageBox").html("");
	$("#" + fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#" + fromobj).find("#drawing_app_no").val() == '') {
		$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#" + fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	} else if ($("#" + fromobj).find("#drawing_app_status").val() == '') {
		$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#" + fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
		return false;
	} else {
		$.ajax({
			type: "POST",
			url: "/Applications/cei_drawing_all",
			data: $("#" + fromobj).serialize(),
			success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			}
		});
	}
	return false;
});
</script>