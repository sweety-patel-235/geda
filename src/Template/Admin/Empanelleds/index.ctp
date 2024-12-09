<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('Empanelled',array("id"=>"formmain","url"=>ADMIN_PATH."/customers/index","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Empanelled List
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
									<?php echo $this->Form->input('Empanelled.id', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium'));?>
								</div> 
								<label class="control-label col-md-1">Name</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Empanelled.agency', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium'));?>
								</div>

								<label class="control-label col-md-1">Status</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Empanelled.status', $arrStatus, array('label' => false ,'div'=>false, 'class'=>'form-control form-control-inline input-medium'));?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">Level</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Empanelled.level',$arrEmpLevel, array('label' => false ,'div'=>false , 'class'=>'form-control form-control-inline input-medium'));?>
								</div>
								<label class="control-label col-md-1">Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Empanelled.search_date',array(''=>'Select','Empanelled.created'=>'Created Date'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline input-medium date-picker'));?>
								</div>  
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">Period</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Empanelled.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline input-medium','id'=>'SearchPeriod'));?>
								</div>
								<label class="control-label col-md-1">From Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Empanelled.DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom')); ?>
								</div> 
								<label class="control-label col-md-1">To Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Empanelled.DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo')); ?>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-5 col-md-6">										
										<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
										<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
										<?php 
											 $blnAddAdminuserRights = $Userright->checkadminrights($Userright->ADD_EMPANEL);
											if($blnAddAdminuserRights)
											{	
												echo $Userright->linkAddEmpanelled(constant('WEB_URL').constant('ADMIN_PATH').'empanelleds/manage/','<i class="fa fa-plus"></i> Add Empanelled','','alt="addRecord" class="btn green pull-right"');
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
					<?php echo $this->element('empanelledlist'); ?>
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
$('a[rel="viewRecord"]').click(function(){
	 $.fancybox({
		'autoDimensions' : true,
		'href'    : this.href,
		'width'   : 700, 
		'type'    : 'iframe',
		'arrows'  : false,
		'scrolling':false,
		'autoSize':true,
		'mouseWheel':false
	});
	 return false;
});	
function resetsearch()
{
	$('#projects-id').val("");
	$('#projects-username').val("");
    $('#projects-name').val("");
    $('#projects-email').val("");
    $('#projects-mobile').val("");
    $('#projects-city').val("");
   	$('#projects-status').val("");
    $('#projects-usertype').val("");
    $('#SearchDate').val("");
    $('#SearchPeriod').val("");
    $('#users-limit').val("25");
	resetdates();
	$('#searchbtn').click();
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
