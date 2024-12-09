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
	$this->Html->addCrumb($pagetitle);
?>
<?php echo $this->Form->create('Reports',array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
<div class="container project-leads">
	<div class="col-md-12">
		<?php echo $this->Form->hidden('download',array("value"=>"","id"=>"download","value"=>1)); ?>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3">
						<?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
					</div>
					<div class="col-md-3">
						<button type="button" class="btn green" onclick="javascript:download_xls();"><i class="fa fa-download"></i> Download .xls</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
$(document).ready(function() {
	resetcustomdates(true);
	resetdates();
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
function resetdates()
{

}
function resetsearch() {
	window.location.reload();
}
function validatesearchform() {
	return true;
}
function download_xls() {
	$('#formmain').submit();
}
</script>