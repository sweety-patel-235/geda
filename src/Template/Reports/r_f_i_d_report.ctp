<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">

<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style type="text/css">
.chosen-container .chosen-results {
    max-height:200px;
}
.check-box-address{
	margin-top: 20px !important;
}
</style>
<?php
	$this->Html->addCrumb("RFID REPORT");
?>
<?php echo $this->Form->create('Reports',array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
<div class="container project-leads">
	<div class="col-md-12">
		<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
		<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
		<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
		<div class="form-body">
			 <div class="row">
				<div class="col-md-12">
					<div class="col-md-3">
						<?php echo $this->Form->select('DateField', array('apply_online_approvals.created'=>'Application Stage Date','charging_certificate.meter_installed_date'=>'Meter Installation Date'),array('label' => false,'class'=>'form-control','empty'=>'-Select date-')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->select('status', $application_dropdown_status,array('label' => false,'class'=>'form-control','empty'=>'-Select status-')); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3">
						<?php echo $this->Form->select('payment_status', array('0'=>'Not Paid','1'=>'Paid'),array('label' => false,'class'=>'form-control','empty'=>'-Select payment status-','placeholder'=>'')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('application_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'application_no','placeholder'=>'Application No.')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('geda_application_no', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'geda_application_no','placeholder'=>'GEDA Registration No.')); ?>
					</div>
					<div class="col-md-3">
						<?php //echo $this->Form->input('govt_agency', array('label' => false ,'div'=>false,'type'=>'checkbox' , 'class'=>'form-control form-control-inline input-medium','id'=>'govt_agency','placeholder'=>'','value'=>1)); ?>
						<?php //echo $this->Form->input('govt_agency', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'govt_agency')); ?> 
						<?php echo $this->Form->select('govt_agency', array('1'=>'Yes'),array('label' => false,'class'=>'form-control','empty'=>'-Select Government Agency-','placeholder'=>'')); ?>
						<?php //echo $this->Form->input('page_no', array('label' => false ,'div'=>false,'type'=>'number' , 'class'=>'form-control form-control-inline input-medium','id'=>'page_no','placeholder'=>'Go To','style'=>'width:100px !important;','min'=>'1')); ?>
						<input type="hidden" id="total_records_data" value="" name="total_records_data" />
					</div>
				</div>
			</div>
			<!--<div class="row" style="margin-left: 0px;">
				<div class="col-md-11">
					<?php echo $this->Form->select('installer_name_multi',$Installers,array('label' => false,'class'=>'form-control chosen-select','empty'=>'-Select Installers-','id'=>'installer_name','data-placeholder'=>'-Select Installers-',"multiple"=>"multiple"));?>
				</div>
			</div>
			 <div class="row" style="margin-top: 10px;margin-left: 0px;">
				<div class="col-md-11">
					<?php echo $this->Form->select('mis_export_fields',$arrReportFields,array('label' => false,'class'=>'form-control chosen-select','empty'=>'-Select Export Fields-','id'=>'mis_export_fields','data-placeholder'=>'-Select Export Fields-',"multiple"=>"multiple",'value'=>$default_fields));?>
				</div>
			</div> -->
			<div class="row" style="margin-top: 10px;">
				<div class="col-md-offset-4 col-md-8">
					<div class="col-md-12 form-group text">
						<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
						<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
						<!-- <button type="button" class="btn green" onclick="javascript:download_xls();"><i class="fa fa-download"></i> Download .xls</button> -->
					</div>
					<div class="col-md-12 form-group text">
						
						<?php echo $this->Form->input('Add RFID Data', array('label' => false, 'class' => ' btn btn-green green  AddOfflineRFID ','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#AddOfflineRFID")); ?>

						<!-- <?php //echo $this->Form->input('Show all the rfid link', array('label' => false, 'class' => ' btn btn-green green  AddOfflineRFID ','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#AddOfflineRFID")); ?> -->
						<a href="<?php echo URL_HTTP; ?>apply_onlines_rfid_data/" class="dropdown-item">
														<i class="fa fa-eye"></i> Show all the rfid link
													</a>
					</div>
				</div>
			</div> 
		</div>
	</div>
	<div class="col-md-12">
		<?php  echo $this->Flash->render('cutom_admin'); ?>
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue-madison noborder">
			<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
				<thead>
				<tr>
					<th class=""></th>
					<th class="">Application No.</th>
					<!-- <th class="">GEDA Registration No.</th> -->
					<th class="">Application Status</th>
					<th class="">Project Capacity</th>
					<th class="">RFID Upload</th>
					<th class="">Under Taking Upload</th>
					<th class="">Under Taking Upload Sample</th>
					<!-- <th class="">Submitted On</th> -->
					<th class="">Action</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
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

</div>
<?php echo $this->Form->end(); ?>
<div id="upload_docs" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Documents</h4>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('add_docs_request',['name'=>'frm_add_docs_request','id'=>'add_docs_request', 'type' => 'file']); ?>
				<div id="message_error"></div>
				<div class="form-group text">
				<?php echo $this->Form->input('insid',['id'=>'insid','label' => true,'type'=>'hidden']); ?>
				</div>
				<div class="row">
					<div class="col-md-12">
						<lable class="col-md-4">RFID Upload </lable>
							<div class="col-md-6">
								<?php echo $this->Form->input('rfid_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'rfid_file')); ?>
							</div>
						
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12" id="rfid_file-file-errors"></div>
						</div>
					</div>
					<div class="col-md-12">
						<lable class="col-md-4">Undertaking Upload </lable>
							<div class="col-md-6">
								<?php echo $this->Form->input('under_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'under_file')); ?>
							</div>
						
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12" id="under_file-file-errors"></div>
						</div>
					</div>
				</div>
				<div class="row" align="right">
					<div class="col-md-2">
					<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_5','label'=>false,'class'=>'btn btn-primary upload_docs_btn','data-form-name'=>'add_docs_request']); ?>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<div id="jqtable_data"></div>
 <div id="AddOfflineRFID" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<button type="button" class="close cross" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Offline RFID Data</h4>
			</div>
			
			<div class="modal-body">
				<?php
				$counter = 0;
				echo $this->Form->create('AddOfflineRFIDForm',['name'=>'AddOfflineRFIDForm'.$counter,'id'=>'AddOfflineRFIDForm'.$counter,'type' => 'file']); 

				?>
				<div id="message_error"></div>
				<div class="form-group text">
				<?php echo $this->Form->input('AddOfflineRFID_application_id',['id'=>'AddOfflineRFID_application_id','label' => true,'type'=>'hidden']); ?>
				<?php echo $this->Form->input('AddOfflineRFID_application_type',['label' => false,'type'=>'hidden','value'=>""]); ?>
					<div class="row">
						<div class="col-md-4">
							<lable>Application No </lable>
							<?php echo $this->Form->input('application_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'application_no_off','placeholder'=>'Application No')); ?>
							
						</div>
						<div class="col-md-4">
							<lable>Application Type </lable>
							<?php echo $this->Form->select('application_type', $applicationCategory,array('label' => false,'class'=>'form-control','empty'=>'-Select Category-')); ?>
							
						</div>
						<div class="col-md-12">
							<lable>Project Details</lable>
							<?php echo $this->Form->textarea('project_details',array("type" => "text",'label' => false,'class'=>'form-control','placeholder'=>'Project Details')); ?>
							
						</div>
						<div class="col-md-12"><br>
							<lable class="col-md-4">RFID Upload </lable>
								<div class="col-md-6">
									<?php echo $this->Form->input('rfid_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'rfid_file_static')); ?>
								</div>
							
							<div class="row" style="margin-right: 2px;margin-left: -4px;">
								<div class="col-md-12" id="rfid_file_static-file-errors"></div>
							</div>
						</div>
						<div class="col-md-12">
							<lable class="col-md-4">Undertaking Upload </lable>
								<div class="col-md-6">
									<?php echo $this->Form->input('undertaking_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'under_file_static')); ?>
								</div>
							
							<div class="row" style="margin-right: 2px;margin-left: -4px;">
								<div class="col-md-12" id="under_file_static-file-errors"></div>
							</div>
						</div>
					</div>
					
					
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->input('Submit',['id'=>'AddOfflineRFID_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary AddOfflineRFID_btn button-right','data-form-name'=>'AddOfflineRFIDForm'.$counter]); ?>
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
	$('.chosen-select').chosen();
	$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
});
$("#rfid_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-s",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#rfid_file-file-errors',
	maxFileSize: '10240',
});
$("#under_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-s",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#under_file-file-errors',
	maxFileSize: '10240',
});
$("#rfid_file_static").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-s",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#rfid_file_static-file-errors',
	maxFileSize: '10240',
});
$("#under_file_static").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-s",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#under_file_static-file-errors',
	maxFileSize: '10240',
});
$(".AddOfflineRFID_btn").click(function() {

	var form = $('#AddOfflineRFIDForm0');
	console.log('hii');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	var fromobj = $(this).attr("data-form-name");
	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];
	//ValidateRow_offline(indexvalue);
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/Reports/Add_rfid_data",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#AddOfflineRFIDForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#AddOfflineRFIDForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".AddOfflineRFID_btn").removeAttr('disabled');
					}
				}
			});

});
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
function resetdates() {

}
function resetsearch() {
	window.location.reload();
}
function validatesearchform() {
	return true;
}
function download_xls() {
	$('#formmain').attr('action','<?php echo "/Reports/getreportfromexel"; ?>');
	$('#formmain').submit();
}

function showModel(title,url,id){
console.log('hello');
console.log(title);
console.log(url);
	// var modelheader = $(this).data("title");
	var modelheader = title;
	var modelUrl = url;
	// var modelUrl = $(this).data("url");
	var defaultURL 		= "window.location.href=\'<?php echo URL_HTTP; ?>apply-online-list\'";
	document_window = $(window).width() - $(window).width()*0.80;
	document_height = $(window).height() - $(window).height() * 0.50;
	modal_body = '<div class="modal-header" style="min-height: 45px;">'+
	'<h4 class="modal-title">'+modelheader+'</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
	'</div>'+
	'<div class="modal-body">'+
	'<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
	'</div>';

	$('#myModal').find(".modal-content").html(modal_body);
	$('#myModal').modal('show');
	$('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
	$('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	$("#CTUStep2_application_id").val(id);
	return false;
};
window.closeModal = function(){ $('#myModal').modal('hide'); };

$(document).on("click", ".upload_docs" , function() {

	var insid = $(this).attr("data-id");
	console.log('firdst');

	console.log(insid);
	console.log('insid');

	$("#insid").val(insid);
	
	$('#upload_docs').modal('show');
});

// $(".upload_docs_btn").click(function() {
	
// 	var form = $('#add_docs_request');
// 			var formdata = false;
// 			if (window.FormData) {
// 				formdata = new FormData(form[0]);
// 			}
// 	alert("hii");
// 		$.ajax({
// 				type: "POST",
// 				url: "/Reports/Add_rfid_docs",
// 				data: formdata ? formdata : form.serialize(),
// 				success: function(response) {
// 					var result = $.parseJSON(response);
// 					console.log(result.success);
// 					if (result.success == 1) {
// 						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
// 						DataT.ajax.reload(null, false);
// 						$('#approve_Status').modal('hide');
// 					} else {
// 						$("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
// 					}
// 				}
// 			});
		
// });
$(".upload_docs_btn").click(function() {
	//debugger;
			var form = $('#add_docs_request');

			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
console.log(formdata);
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Reports/Add_rfid_docs",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#add_docs_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#add_docs_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".upload_docs_btn").removeAttr('disabled');
					}
				}
			});

		});


</script>