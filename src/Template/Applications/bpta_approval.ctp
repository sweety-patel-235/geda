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
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> BPTA Approval</h4>
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
				  		<th colspan="4" style="text-align:center;" >BPTA Approval</th>
				  		</thead>
				  	</tr>
				  	<tr >
				  		<td  style="text-align:center;" >Sr No </td>
				  		<td  style="text-align:center;" >WTG Location </td>
				  		<td  style="text-align:center;" >BPTA Approval</td>
				  		<td  style="text-align:center;" >Status</td>
				  	</tr>
				  	
				  	<tbody>
				  		<?php $counter =1;
				  		 foreach ($wtg_applications as $key => $value) {
				  		 	$bpta_status 		= $BptaApplicationDetails->check_bpta_approval_status($value['id']);

				  		 	?>

				  			<tr >
						  		<td style="text-align:center;" ><?php echo $counter ?> </td>
						  		<td style="text-align:center;" ><?php echo $value['wtg_location'];?> </td>
						  		<td style="text-align:center;" ><a href="javascript:;" data-toggle="modal" data-target="#BPTAApproval" class="BPTAApproval dropdown-item" data-id="<?php echo encode($value->application_id); ?>" data-geoid ="<?php echo $value->id; ?>"><i class="fa fa-check-square-o"></i> BPTA
													</a></td>
						  		<td style="text-align:center;" > <?php echo !empty($bpta_status->bpta_approve)?' Completed' :'-' ?></td>
						  		
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
	<div id="BPTAApproval" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">BPTA Documents Approval</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('BPTAApprovalForm', ['name' => 'BPTAApprovalForm', 'id' => 'BPTAApprovalForm', 'type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
						<?php echo $this->Form->input('BPTAApproval_application_id', ['id' => 'BPTAApproval_application_id', 'label' => true, 'type' => 'hidden']); ?>
						<?php echo $this->Form->input('BPTAApproval_app_geo_loc_id', ['id' => 'BPTAApproval_app_geo_loc_id', 'label' => true, 'type' => 'hidden']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="row" style="margin-right: 2px;margin-left: -4px;">
									<div class="col-md-6"  id="bpta_document_file1"></div>
									<div class="col-md-6"  id="bpta_document_file2"></div>
								</div><br>
								<div class="row">
									<div class="col-md-6">
										<?php echo $this->Form->select('bpta_approve',array("1"=>"Yes","2"=>"No"),["class" =>"form-control",'id'=>'


										','label' => true]); ?>
									</div>
									<div class="col-md-6">
										<?php echo $this->Form->input('bpta_approval_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'bpta_approval_date','placeholder'=>'Approval Date','autocomplete'=>'off')); ?>
									</div>
									<div class="col-md-12">
										<?php echo $this->Form->textarea('bpta_reason',[ "class" =>"form-control reason",
																			'id'=>'bpta_reason',
																			'cols'=>'50','rows'=>'5',
																			'label' => false,
																			'placeholder' => 'Comments, if any']);
										?>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary BPTAApproval_btn', 'data-form-name' => 'BPTAApprovalForm']); ?>
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
$('#bpta_approval_date').datepicker({format: 'dd/mm/yyyy'});
$(".BPTAApproval").click(function() {
	var application_id = $(this).attr("data-id");
	var app_geo_loc_id = $(this).attr("data-geoid");
	$("#BPTAApproval_application_id").val(application_id);
	$("#BPTAApproval_app_geo_loc_id").val(app_geo_loc_id);
	console.log(application_id);
	$("#bpta_document_file").html('');
	$.ajax({
			type: "POST",
			url: "/Applications/fetchBPTAdataDocument",
			data: {'app_id':application_id},
			beforeSend: function(xhr) {
				xhr.setRequestHeader(
					'X-CSRF-Token',
					<?php echo json_encode($this->request->param('_csrfToken')); ?>
				);
			},
			success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok") {
					if(result.document_link!='')
					{
						$("#bpta_document_file1").html(result.document_link['bpta_document1']);
						$("#bpta_document_file2").html(result.document_link['bpta_document2']);
						
					}
				}
			  
			}
		});

});
$(".BPTAApproval_btn").click(function() {
	var form = $('#BPTAApprovalForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$.ajax({
		type: "POST",
		url: "/Applications/BPTAApprovalDocumentAll",
		data: formdata ? formdata : form.serialize(),
		cache: false,
		contentType: false,
		processData: false,
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result.success);
			if (result.success == 1) {
				
				$("#BPTAApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
				location.reload();
			} else {
				$("#BPTAApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
				$(".BPTAApproval_btn").removeAttr('disabled');
			}
		}
	});

});
</script>