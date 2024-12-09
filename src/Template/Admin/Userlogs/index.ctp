<?php echo $this->Form->create('Userlog',array("id"=>"formmain",'class'=>'form-horizontal form-bordered',"url"=>"/userlogs/index","name"=>"searchAdminloglist"));?>
<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>User Log Report
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body form">
				<?php echo $this->Form->hidden('total_pages',array("value"=>$page_count,"id"=>"TotalPages"));?>
				<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage"));?>
				<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort"));?>
				<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction"));?>
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-1">User ID(s)</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Userlog.adminuserid', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'userlog-adminuserid'));?>
						</div>

						<label class="control-label col-md-1">User Name</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('User.username', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'userlog-username'));?>
						</div>
						<label class="control-label col-md-1">Remark</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Userlog.remark', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'userlog-remark'));?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-1">Action</label>
						<div class="col-md-3">
							<?php echo $this->Form->select('Userlog.actionid',$arrAdminaction, array('label' => false,'div'=>false,'type'=>'text','empty'=>'Select','class'=>'form-control form-control-inline input-medium date-picker','id'=>'userlog-action'));?>
						</div>
						<label class="control-label col-md-1">IP Address</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Userlog.ipaddress', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>
						<label class="control-label col-md-1">Date</label>
						<div class="col-md-3">
							<?php echo $this->Form->select('Userlog.search_date',array(''=>'Select','Userlogs.created'=>'User Log'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div> 
					</div>

					<div class="form-group">
						<label class="control-label col-md-1">Period</label>
						<div class="col-md-3">
							<?php echo $this->Form->select('Userlog.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'SearchPeriod'));?>
						</div>
						<label class="control-label col-md-1">From Date</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Userlog.DateFrom', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'DateFrom'));?>
						</div> 
						<label class="control-label col-md-1">To Date</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Userlog.DateTo', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text' ,'class'=>'form-control form-control-inline input-medium date-picker','id'=>'DateTo'));?>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-5 col-md-6">										
								<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
								<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
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
			<?php echo $this->element('userloglist'); ?>
		</div>
	</div>
</div><!--DISPLAY LIST OF ADMIN USERS -->

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
    $('#userlog-adminuserid').val("");
	$('#userlog-username').val("");
	$('#userlog-remark').val("");
    $('#userlog-action').val("");
    $('#userlog-ipaddress').val("");
    $('#SearchDate').val("");
    $('#SearchPeriod').val("");
	resetdates();
}
function resetcustomdates(onload)
{
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
	//ToggleDateFields(false);
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