<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('Projects',array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Project List
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
									<?php echo $this->Form->input('Projects.id', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'projects-id'));?>
								</div> 
								<label class="control-label col-md-1">Name</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Projects.name', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'projects-name'));?>
								</div>

								<label class="control-label col-md-1">Status</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Projects.status', $arrStatus, array('label' => false ,'div'=>false, 'class'=>'form-control form-control-inline input-medium','id'=>'projects-status'));?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">City</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Projects.city', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium ','id'=>'projects-city'));?>
								</div>
								<label class="control-label col-md-1">State</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Projects.state', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker'));?>
								</div> 
								<label class="control-label col-md-1">Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Projects.search_date',array(''=>'Select','Projects.created'=>'Created Date'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline input-medium date-picker'));?>
								</div>  
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">Period</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('Projects.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline input-medium','id'=>'SearchPeriod'));?>
								</div>
								<label class="control-label col-md-1">Customer Email</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Projects.email', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium ','id'=>'projects-email'));?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-1">From Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Projects.DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom')); ?>
								</div> 
								<label class="control-label col-md-1">To Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('Projects.DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo')); ?>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-5 col-md-6">										
										<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
										<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
										<button type="button" class="btn green" id="downloadcsvbtn"><i class="fa fa-download"></i> Download .xls</button>
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
					<?php echo $this->element('projectlist'); ?>
					<?php if($AjaxRequest=='0'){?>
					<!--DISPLAY LIST OF ADMIN USERS -->
				</div>
			</div>
		</div>
<?php echo $this->Form->end(); ?>
 <div id="site_servey" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_header_title">Inspection From JREDA</h4>
                </div>
                <div class="modal-body">
                	<div class="row" style="margin-bottom:10px;float:right;">
                	<div class="col-md-12">
                	<button type="button" class="btn green" onclick="javascript:download_xls();"><i class="fa fa-download"></i> Download .xls</button>
                	<button type="button" class="btn green" id="all_surveys_project" onclick="javascript:click_view_projectsurveys();" ><i class="fa fa-download"></i> Download Report PDF</button>
                	</div>
                	</div>
                	<div class="row">
                	<div class="col-md-12">
                	<?php echo $this->Form->create('Surveys',array("id"=>"formmain_surveys","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
                	<?php echo $this->Form->hidden('project_id',array("value"=>'',"id"=>"project_id")); ?>
                	<table class="table table-striped table-bordered table-hover" id="table-example-survey">
						<thead>
							<tr>
								<th class="sorting">ID</th>
								<th class="sorting">Building Name</th>
								<th class="sorting">Contact Name</th>
								<th class="sorting">Designation</th>
								<th class="sorting">Address1</th>
								<th class="sorting">Address2</th>
								<th class="sorting">Address3</th>
								<th class="sorting">Mobile</th>
								<th class="sorting">Surveyer Name</th>
								<th>Action</th>
							</tr>
						</thead>	
					</table>
  					<?php
                    echo $this->Form->end(); ?>
                	</div>
                	</div>
                    </div>
                    
                   
                </div>
            </div>
        </div>
    </div>
    <div id="jqtable_data"></div>
<script type="text/javascript">
var DataT_sur = '';
$(document).ready(function() {
	resetcustomdates(true);
	resetdates();
});

<?php 
echo $JqdTablescr;

echo $JqdTablescr_survey;
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
var formdata=[];
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
function downloadcsv_fn()
{
	$('#formmain').attr('action','<?php echo WEB_ADMIN_URL."projects/downloadcsv"; ?>');
	$('#formmain').submit();
}
function show_modal(project_id,total_survey)
{
	$("#project_id").val(project_id);
	$.ajax({
			url:WEB_ADMIN_URL+'projects/get_project_name',
			data:{'project_id':project_id},
			type:'POST',
			success: function(res)
            {
            	$('#all_surveys_project').hide();
            	if(total_survey>0)
            	{
            		$('#all_surveys_project').show();
            	}
            	$("#modal_header_title").html(res);
            	DataT_sur.draw();
				$("#site_servey").modal('show');
            }
	});
}
function download_xls()
{
	var project_id = $("#project_id").val();
	$('#formmain_surveys').attr('action','<?php echo WEB_ADMIN_URL."projects/create_xls"; ?>');
	$('#formmain_surveys').submit();
	
	//window.location.href = WEB_ADMIN_URL+'projects/create_xls/'+project_id;
}
function click_view_projectsurveys()
{
	var project_id = $("#project_id").val();
	window.location.href="<?php echo constant('WEB_URL').constant('ADMIN_PATH').'projects/viewprojectsurveyreport/';?>"+project_id;
}
</script>
<?php }?>