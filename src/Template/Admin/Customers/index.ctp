<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('Customers',array("id"=>"formmain","url"=>ADMIN_PATH."/customers/index","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Customers List
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body form">
					<!-- BEGIN FORM-->
					
					<?php //echo $this->Form->hidden('draw',array("value"=>$page_count,"id"=>"draw")); ?>
					<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
					<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
					<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
						<div class="form-body">
							<div class="form-group">
								<label class="control-label col-md-1">ID(s)</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.id', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-id'));?>
								</div> 
								<label class="control-label col-md-1">Name</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.name', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-name'));?>
								</div>
								<label class="control-label col-md-1">Email</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.email', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-email'));?>
								</div> 
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">Status</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Customers.status', array(''=>'Select','1'=>'Active','I'=>'Inactive'), array('label' => false ,'div'=>false,'type'=>'text', 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-status'));?>
								</div>
								<label class="control-label col-md-1">Mobile</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.mobile', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-mobile'));?>
								</div> 
								<label class="control-label col-md-1">City</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.city', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text' ,'options'=>$arrUserType, 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'customers-city'));?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Customers.search_date',array(''=>'Select','Customers.created'=>'Created Date','Customers.last_login_date'=>'Last Login Date'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline input-medium date-picker'));?>
								</div>
								<label class="control-label col-md-1">Period</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Customers.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline input-medium','id'=>'SearchPeriod'));?>
								</div>
								<label class="control-label col-md-1">From Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom')); ?>
								</div> 
							</div>
							<div class="form-group">	
								<label class="control-label col-md-1">To Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Customers.DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo')); ?>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-5 col-md-6">										
										<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
										<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
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
									<th class="sorting">Customer ID</th>
									<th class="sorting">Name</th>
									<th class="sorting">Email</th>
									<th class="sorting">Mobile</th>
									<th class="sorting">Last Login date</th>
									<th class="sorting">Created</th>
									<th>Action</th>
								</tr>
							</thead>	
						</table>
					</div> <!-- End of .content -->
					<?php if($AjaxRequest=='0'){?>
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
</script>
<?php } ?>
