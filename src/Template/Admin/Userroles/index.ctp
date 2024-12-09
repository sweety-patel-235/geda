<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('Userrole',array("id"=>"formmain","url"=>ADMIN_PATH."/userroles/","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
	<div class="row">
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>User Role List
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
					<div class="actions">
						<?php 
							$blnAddAdminuserRights	= $Userright->checkadminrights($Userright->ADD_ADMIN_USER_ROLE);
							if($blnAddAdminuserRights) {		
								echo $Userright->linkAddAdminuser(constant('WEB_ADMIN_URL').'userroles/manage/','<i class="fa fa-plus"></i> Add Roles','','alt="addRecord" class="btn green btn-border"');
							}
						?>
                    </div>
				</div>
				<div class="portlet-body form">
				<?php //echo $this->Form->hidden('total_pages',array("value"=>$page_count,"id"=>"TotalPages")); ?>
				<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
				<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
				<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
					
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-2">ID(s)</label>
						<div class="col-md-4">
							<?php echo $this->Form->input('Userrole.id', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker')); ?>
						</div>
						<label class="control-label col-md-2">Role</label>
						<div class="col-md-4">
							<?php echo $this->Form->input('Userrole.rolename', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker')); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2">Search Date</label>
						<div class="col-md-4">
						<?php echo $this->Form->input('Userrole.search_date', array('label' => false ,'div'=>false,'options'=>array(''=>'Select','Userrole.created'=>'Created Date','Userrole.modified'=>'Modified Date'),'default'=>'','onchange'=>'resetdates();',"id"=>"SearchDate",'class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>

						<label class="control-label col-md-2">Select Period</label>
						<div class="col-md-4">
						<?php echo $this->Form->input('Userrole.search_period', array('label' => false ,'div'=>false,'options'=>$period,'default'=>'','onChange'=>'resetcustomdates(false);',"id"=>"SearchPeriod",'class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2">From Date</label>
						<div class="col-md-4">
						<?php echo $this->Form->input('Userrole.DateFrom', array('label' => false ,'div'=>false,'type'=>'text',"id"=>"DateFrom",'class'=>'required','data-date-relative'=>'now','class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>
						<label class="control-label col-md-2">To Date</label>
						<div class="col-md-4">
						<?php echo $this->Form->input('Userrole.DateTo', array('label' => false ,'div'=>false,'type'=>'text',"id"=>"DateTo",'class'=>'required','data-date-relative'=>'now','class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>
					</div>
						
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
<!--DISPLAY LIST OF ADMIN USER ROLES-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue-madison">
			<div class="portlet-title">
				<div class="caption">
					
				</div>
				<div class="tools">
					
				</div>
			</div>
			<?php }?>
			<?php echo $this->element('userrolelist'); ?>
			<?php if($AjaxRequest=='0'){?>
		</div>
	</div>
</div>
<!--DISPLAY LIST OF ADMIN USER ROLES-->
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
	$('#userrole-id').val("");
	$('#userrole-rolename').val("");
    $('#SearchDate').val("");
    $('#SearchPeriod').val("");
    $('#DateFrom').val("");
    $('#DateTo').val("");
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
<?php }?>