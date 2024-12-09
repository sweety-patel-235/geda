<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style type="text/css">
.chosen-container .chosen-results {
    max-height:200px;
}
.check-box-address{
	margin-top: 20px !important;
}
</style>
<?php
	$this->Html->addCrumb("MIS REPORT");
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
			<div class="row" style="margin-left: 0px;">
				<div class="col-md-11">
					<?php echo $this->Form->select('installer_name_multi',$Installers,array('label' => false,'class'=>'form-control chosen-select','empty'=>'-Select Installers-','id'=>'installer_name','data-placeholder'=>'-Select Installers-',"multiple"=>"multiple"));?>
				</div>
			</div>
			<div class="row" style="margin-top: 10px;margin-left: 0px;">
				<div class="col-md-11">
					<?php echo $this->Form->select('mis_export_fields',$arrReportFields,array('label' => false,'class'=>'form-control chosen-select','empty'=>'-Select Export Fields-','id'=>'mis_export_fields','data-placeholder'=>'-Select Export Fields-',"multiple"=>"multiple",'value'=>$default_fields));?>
				</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div class="col-md-offset-4 col-md-8">
					<div class="col-md-12 form-group text">
						<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
						<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="button" class="btn green" onclick="javascript:download_xls();"><i class="fa fa-download"></i> Download .xls</button>
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
					<th class="">GEDA Registration No.</th>
					<th class="">Application Status</th>
					<th class="">Installer Name</th>
					<th class="">Submitted On</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>
<div id="jqtable_data"></div>
<script type="text/javascript">
<?php echo $JqdTablescr; ?>
$(document).ready(function() {
	resetcustomdates(true);
	resetdates();
	$('.chosen-select').chosen();
	$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
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
</script>