<style>
.help-block {
  color: #a94442;
}
</style>
<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('InstallerCompanies',array("id"=>"index-formmain","url"=>ADMIN_PATH."/InstallerCompanies/index","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off", 'type' => 'file')); ?>
	<div class="row">
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Installer Company List
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
					</div>
					
				</div>
				<div class="portlet-body form">
					<!-- BEGIN FORM-->					
					<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
					<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
					<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
						<div class="form-body">
							<div class="form-group">
								<label class="control-label col-md-1">ID(s)</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('InstallerCompanies.id', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline date-picker'));?>
								</div> 
								<label class="control-label col-md-1">Company Title</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('InstallerCompanies.installer_name', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline'));?>
								</div>
								<label class="control-label col-md-1">State</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('InstallerCompanies.state',  array('label' => false ,'div'=>false,'type'=>'text', 'class'=>'form-control form-control-inline','id'=>'installercompanies-state'));?>
								</div> 
							</div>
							<div class="form-group">
							<label class="control-label col-md-1">Status</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('InstallerCompanies.status', array(''=>'Select','1'=>'Active','I'=>'Inactive'), array('label' => false ,'div'=>false,'type'=>'text', 'class'=>'form-control form-control-inline'));?>
								</div> 
								<label class="control-label col-md-1">Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('InstallerCompanies.search_date',array(''=>'Select','InstallerCompanies.lastlogin'=>'Last Login Date'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline date-picker'));?>
								</div> 
								<label class="control-label col-md-1">Period</label>
								<div class="col-md-3">
									<?php echo $this->Form->select('InstallerCompanies.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline ','id'=>'SearchPeriod'));?>
								</div>
							</div>
							<div class="form-group">
							<label class="control-label col-md-1">From Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('InstallerCompanies.DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline ','id'=>'DateFrom')); ?>
								</div> 
								<label class="control-label col-md-1">To Date</label>
								<div class="col-md-3">
									<?php echo $this->Form->input('InstallerCompanies.DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline ','id'=>'DateTo')); ?>
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
		<div class="row">
			<div class="col-md-12">			
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box blue-madison">
					<div class="portlet-title">
						<div class="caption">
							Managed Table
						</div>
						<div class="tools">
							
						</div>
					</div>
					<?php } ?>
					<div class="portlet-body">
	<table class="table table-striped table-bordered table-hover" id="table-example">
		<thead>
			<tr>
				<th class="sorting">ID</th>
				<th class="sorting">Company Title</th>
				<th class="sorting">Address</th>
				<th class="sorting">State</th>
				<th class="sorting">Contact</th>
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
<div id="installer_comp" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_header_title"></h4>
            </div>
            <div class="modal-body">
            	<?php echo $this->Form->create('InstallerSubscription',array("id"=>"inssub-formmain","url"=>ADMIN_PATH."/InstallerCompanies/add_subscription","name"=>"AddInstaller")); ?>
            	<?php echo $this->Form->hidden('installer_id',array("value"=>'',"id"=>"ins_id")); ?>
				<div class="row" style="margin-bottom:10px;display: none;" id="add_subscription_form">
            		<div class="col-md-12">
						<div class="form-body">
							<div class="form-group">
								<label class="control-label col-md-1">Plans</label>
								<div class="col-md-2">
									<?php echo $this->Form->select('plan_id',$installers_plan, array('label' => false ,'div'=>false,'type'=>'text', 'class'=>'form-control form-control-inline','id'=>'plan_id'));?>
									<div class="help-block" id="plan_id_error"></div>
								</div> 
								<label class="control-label col-md-2">Start Date</label>
								<div class="col-md-2">
									<?php echo $this->Form->input('start_date', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline ','id'=>'start_date')); ?>
									<div class="help-block" id="start_date_error"></div>
								</div> 
								<label class="control-label col-md-2">Expire Date</label>
								<div class="col-md-2">
									<?php echo $this->Form->input('expire_date', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline ','id'=>'expire_date')); ?>
									<div class="help-block" id="expire_date_error"></div>
								</div> 
							</div>
							<div class="row" >
								<div class="col-md-offset-5 col-md-6" style="margin-top: 10px;">			<button type="button" class="btn green" id="submitbtn" onClick="submitaddsub()"><i class="fa fa-check"></i> Submit</button>
									<button type="button" onClick="resetaddsub()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
                echo $this->Form->end(); ?>
            	<div class="row" style="margin-bottom:10px;float:right;" id="add_sub_button_div">
            	<div class="col-md-12">
            	<button type="button" class="btn green" onclick="javascript:add_subscription();"><i class="fa fa-plus"></i> Add Subscription</button>
            	</div>
            	</div>
            	<div class="row">
            	<div class="col-md-12">
            	<?php echo $this->Form->create('Subscription',array("id"=>"formmain_subscription","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
            	<?php echo $this->Form->hidden('installer_id',array("value"=>'',"id"=>"installer_id")); ?>
            	<table class="table table-striped table-bordered table-hover" id="table-example-survey">
					<thead>
						<tr>
							<th class="sorting">ID</th>
							<th class="sorting">Plan Name</th>
							<th class="sorting">Plan Price</th>
							<th class="sorting">Coupen Code</th>
							<th class="sorting">Coupen Amount</th>
							<th class="sorting">Is Flat</th>
							<th class="sorting">Payment Status</th>
							<th class="sorting">Start Date</th>
							<th class="sorting">Expire Date</th>
							<th class="sorting">Comment</th>
							<th class="sorting">Status</th>
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
<div id="jqtable_data"></div>
<script type="text/javascript">
var DataT_sur = '';
$(document).ready(function() {
	resetcustomdates(true);
	resetdates();
});

<?php 
echo $JqdTablescr;
echo $JqdTablescr_sub;
?>
	
function resetsearch()
{
	$("#index-formmain")[0].reset();
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
function show_modal(installer_id)
{
	$("#installer_id").val(installer_id);
	$("#ins_id").val(installer_id);
	$.ajax({
			url:WEB_ADMIN_URL+'InstallerSubscription/get_installer_name',
			data:{'installer_id':installer_id},
			type:'POST',
			success: function(res)
            {
            	var arr_res = res.split('|||');
            	$("#modal_header_title").html(arr_res[0]);
            	$("#add_sub_button_div").hide();
            	if(arr_res[1] == 1)
            	{
            		$("#add_sub_button_div").show();
            	}
            	DataT_sur.draw();
            	setDatestareexp();
            	$("#add_subscription_form").hide();
				$("#installer_comp").modal('show');
            }
	});
}
function setDatestareexp()
{
	$("#start_date").val("");
	$("#expire_date").val("");
	$("#plan_id").val("0");
	$("#start_date").datepicker({format:'dd-mm-yyyy',autoclose: true});
    $("#expire_date").datepicker({format:'dd-mm-yyyy',autoclose: true});
}
function resetaddsub()
{
	$("#inssub-formmain")[0].reset();
	reseterror();
}
function add_subscription()
{
	$("#add_subscription_form").show();
}
function reseterror()
{
	$("#plan_id").parent().removeAttr('class');
	$("#plan_id").parent().attr('class','col-md-2');
	$("#plan_id_error").html('');
	$("#start_date").parent().removeAttr('class');
	$("#start_date").parent().attr('class','input text');
	$("#start_date_error").html('');
	$("#expire_date").parent().removeAttr('class');
	$("#expire_date").parent().attr('class','input text');
	$("#expire_date_error").html('');
}
function submitaddsub()
{
	reseterror();
	$.ajax({
			url:WEB_ADMIN_URL+'InstallerSubscription/add_subscription',
			data:{'plan_id':$("#plan_id").val(),'start_date':$("#start_date").val(),'expire_date':$("#expire_date").val(),'installer_id':$("#ins_id").val()},
			type:'POST',
			datatype:'json',
			success: function(res)
            {
            	var arr=jQuery.parseJSON(res);
            	if(arr.result=='error')
            	{
            		$.each( arr.msg, function( key, value ) {  
					$("#"+key).parent().addClass('has-error');
					$("#"+key+"_error").html(value);
					});
            	}
            	else
            	{
            		if(arr.disp_add_sub==1)
            		{
            			$("#add_sub_button_div").show();
            		}
            		else
            		{
            			$("#add_sub_button_div").hide();
            		}
            		setDatestareexp();
            		DataT_sur.draw();
            	}
            	
            }
	});
	//$("#inssub-formmain").submit();
}
</script>
<?php }?>
