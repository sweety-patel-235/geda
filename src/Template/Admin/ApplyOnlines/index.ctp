<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('ApplyOnlines',array("id"=>"formmain","url"=>ADMIN_PATH."/customers/index","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Apply Online List
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body form">
					<!-- BEGIN FORM-->
					
					<?php //echo $this->Form->hidden('draw',array("value"=>$page_count,"id"=>"draw")); ?>
					<?php echo $this->Form->hidden('CurrentPage',array("value"=>$CurrentPage,"id"=>"CurrentPage")); ?>
					<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
					<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
						<div class="form-body">
							<div class="form-group">
								<label class="control-label col-md-1">ID(s)</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('ApplyOnlines.id', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-id'));?>
								</div> 
								<label class="control-label col-md-1">Customer</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('ApplyOnlines.customer_id',$customer_list_list, array('label' => false,'empty'=>'-select customer-','div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-name'));?>
								</div>
								<label class="control-label col-md-1">Customer Type</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('ApplyOnlines.customer_type',$customer_type_list, array('label' => false ,'div'=>false,'type'=>'text','empty'=>'-select customer type-','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-email'));?>
								</div> 
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">City</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('ApplyOnlines.city', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-city'));?>
								</div>
								<label class="control-label col-md-1">State</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('ApplyOnlines.state', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-city'));?>
								</div>
								<label class="control-label col-md-1">Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('ApplyOnlines.search_date',array(''=>'Select','ApplyOnlines.created'=>'Created Date'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline input-medium date-picker'));?>
								</div>
								

							</div>
							<div class="form-group">
								<label class="control-label col-md-1">Period</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('ApplyOnlines.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline input-medium','id'=>'SearchPeriod'));?>
								</div>
								<label class="control-label col-md-1">From Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('ApplyOnlines.DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom')); ?>
								</div> 	
								<label class="control-label col-md-1">To Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('ApplyOnlines.DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo')); ?>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-5 col-md-6">										
										<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
										<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
										<button type="button" class="btn green" id="downloadcsvbtn"><i class="fa fa-download"></i> Download .xls</button>
										<button type="button" class="btn green" id="" onclick="window.location.href='ApplyOnlines/add_application'">Add Application</button>
										<?php 
											$blnAddAdminuserRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD);
											if($blnAddAdminuserRights)
											{	
												echo $Userright->linkAddCustomer(constant('WEB_URL').constant('ADMIN_PATH').'customers/manage/','<i class="fa fa-plus"></i> Add Customer','','alt="addRecord" class="btn green pull-right"');
											}
										?>	
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box blue-madison">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-list-ol"></i>Managed Table
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<?php } ?>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="table-example">
							<thead>
								<tr>
									<th class="sorting">ID</th>
									<th class="sorting">Customer</th>
									<th class="sorting">Customer Type</th>
									<th class="sorting">Email</th>
									<th class="sorting">PV Capacity</th>
									<th class="sorting">City</th>
									<th class="sorting">State</th>
									<th class="sorting">Created</th>
									<th>Action</th>
								</tr>
							</thead>	
						</table>
					</div> <!-- End of .content -->
					<?php if($AjaxRequest=='0') { ?>
					<!--DISPLAY LIST OF ADMIN USERS -->
				</div>
			</div>
		</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">

$(document).ready(function() {
	resetcustomdates(true);
	resetdates();
});

<?php 
echo $JqdTablescr;
?>
	
function resetsearch()
{
	$('#customers-id').val("");
	$('#customers-name').val("");
    $('#customers-email').val("");
    $('#customers-mobile').val("");
    $('#customers-city').val("");
   	$('#customers-status').val("");
    $('#SearchDate').val("");
    $('#SearchPeriod').val("");
    $('#users-limit').val("25");
	resetdates();
	$('#searchbtn').click();
}
function resetcustomdates(onload)
{
	validClick = false;
    var period		= $('#SearchPeriod').val();
	var Today		= '<?php echo date("d-m-Y");?>';
	var Yesterday	= '<?php echo date("d-m-Y",strtotime("yesterday"));?>';
    if(period==3)
    {
		$("#DateFrom").removeAttr("disabled");
        $("#DateTo").removeAttr("disabled");
		if(!onload) {
			$("#DateFrom").val(Yesterday);
			$("#DateTo").val(Today);
		}
    }
    else
    {
        $("#DateFrom").val("");
		$("#DateTo").val("");
		$("#DateFrom").attr("disabled",true);
        $("#DateTo").attr("disabled",true);
    }
    if(period=="")
    {
        $("#DateFrom").val("");
		$("#DateTo").val("");
    }
    if(period==1)
    {
        $("#DateFrom").val(Today);
		$("#DateTo").val(Today);
    }
    if(period==2)
    {
        $("#DateFrom").val(Yesterday);
		$("#DateTo").val(Today);
    }

    $("#DateFrom").datepicker({format:'dd-mm-yyyy',autoclose: true});
    $("#DateTo").datepicker({format:'dd-mm-yyyy',autoclose: true});
}

function validatesearchdates()
{
    var err= '';
    if($('#SearchPeriod').val()=='' && $('#SearchDate').val() != '')
    {
        err +='Please select \"Period\" value.\r\n';
    }
    if($('#SearchPeriod').val()==3 && $('#SearchDate').val()!='')
    {
        if(err=='')
		{
			date_1 = $("#DateFrom").val();
			date_2 = $("#DateTo").val();
			if (date_1 == '') {
				err +='"From Date" should not empty.\r\n';
				$("#DateFrom").focus();
			} else if (date_2 == '') {
				err +='"To Date" should not empty.\r\n';
				$("#DateTo").focus();
			} /*else if(!dateDiff(date_1,date_2)) {
				err +='"To Date" should not be less than "From Date".\r\n';
				$("#DateTo").focus();
			}*/
		}
    }
    return err;
}

function validatesearchform()
{
	var err = '';
	var setFocus = '';
	err = validatesearchdates();
	if(err!='')
	{
		alert(err);
		if(setFocus!='')
		{
			var obj = eval(setFocus);
			obj.focus();
		}
		return false;
	}
	return true;
}

function resetdates()
{
	if($('#SearchDate').val()=='')
	{
		$('#SearchPeriod').val("");
		$('#SearchPeriod').attr("disabled",true).trigger("liszt:updated");
		resetcustomdates(false);
	}
	else
	{
		$('#SearchPeriod').removeAttr("disabled").trigger("liszt:updated");
	}
}
function downloadcsv_fn()
{
	$('#formmain').attr('action','<?php echo WEB_ADMIN_URL."ApplyOnlines/downloadcsv"; ?>');
	$('#formmain').submit();
}
</script>
<?php } ?>
