<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style type="text/css">
.chosen-container .chosen-results {
    max-height:200px;
}
</style>
<?php
	$this->Html->addCrumb("ReMis Report");
	$IP_ADDRESS	= (isset($this->request)?$this->request->clientIp():"");
	$AllowedIp	= in_array($IP_ADDRESS,array("203.88.138.46"))?true:false;

?>
<?php echo $this->Form->create('ReReports',array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
<div class="container project-leads">
	<div class="col-md-12">
		<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
		<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
		<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3 ">
						<?php echo $this->Form->select('application_status', $application_dropdown_status,array('label' => false,'class'=>'form-control','empty'=>'-Select Status-')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->select('application_type', $applicationCategory,array('label' => false,'class'=>'form-control','empty'=>'-Select Category-','placeholder'=>'From Date')); ?>
						<?php echo $this->Form->hidden('download',array("value"=>0,"id"=>"download")); ?>
					</div>
				</div>	

				<div class="col-md-12">
					<div class="col-md-3 ">
						<?php echo $this->Form->select('order_by_form', array('Applications.modified|DESC'=>'Modified Date Descending','Applications.modified|ASC'=>'Modified Date Ascending','submitted_date|DESC'=>'Submitted Date Descending','submitted_date|ASC'=>'Submitted Date Ascending'),array('label' => false,'class'=>'form-control','placeholder'=>'')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->input('application_search_no', array('label' => false,'class'=>'form-control input-medium','placeholder'=>'Application Number','autocomplete'=>'off')); ?>
					</div>
					 <div class="col-md-3 ">
						<?php echo $this->Form->input('name_of_applicant', array('label' => false,'class'=>'form-control input-medium','placeholder'=>'Name of  Applicant Company','autocomplete'=>'off')); ?>
					</div> 
					<div class="col-md-3 ">
						<?php echo $this->Form->select('payment_status', array('0'=>'Not Paid','1'=>'Paid'),array('label' => false,'class'=>'form-control','empty'=>'-Select Payment Status-','placeholder'=>'')); ?>
					</div>
				</div>
				
		</div>
			<div class="row" style="margin-top: 10px;">
				<div class="col-md-offset-4 col-md-8">
					<div class="col-md-12 form-group text">
						<button type="button" class="btn btn-green green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
						<button type="button" onClick="resetsearch()" class="btn btn-green default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="button" class="btn btn-green green" onclick="javascript:download_processing_fees_xls();"><i class="fa fa-download"></i> Download .xls</button>
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<?php  echo $this->Flash->render('cutom_admin'); ?>
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue-madison noborder table-responsive">
			<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
				<thead>
				<tr>
					<!-- <th class=""></th>
					<th class="">Application No</th>
					<th class="">Application Type</th>
					<th class="">Applicatio Status</th>
					<th class="">MSME</th>
					<th class="">Name Director</th>
					<th class="">Director Type </th>
					<th class="">Director Email</th>
					<th class="">Authority Name </th>
					<th class="">Authority Type</th>
					<th class="">Authority Email</th>
					<th class="">created</th> -->

					<th class=""></th>
					<th class="">Application No.</th>
					<th class="">Name of Applicant</th>
					<th class="">Application Category</th>
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
	//window.location.reload();
	location.replace(location.href);
}
function validatesearchform() {
	return true;
}
function download_processing_fees_xls() {

	$('#formmain').attr('action','<?php echo "/ReReports/getrereportfromexel"; ?>');
	$('#formmain').submit();
}
</script>