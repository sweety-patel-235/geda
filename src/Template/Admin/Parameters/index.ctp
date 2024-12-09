<?php if($AjaxRequest=='0'){?>
	<?php echo $this->Form->create('Parameter',array("id"=>"index-formmain","url"=>ADMIN_PATH."/PartMasters/index","name"=>"searchParameterlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off"));?>
	<div class="row">
		<div class="col-md-12">
			<?php  echo $this->Flash->render('cutom_admin'); ?>
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Parameters List
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse">
						</a>
					</div>
					<div class="actions">
						<?php 
						$blnAddParameterType = $Userright->checkadminrights($Userright->ADD_PARAMETER_TYPE);
						$blnAddParameter 	 = $Userright->checkadminrights($Userright->ADD_PARAMETER);
						if($blnAddParameterType){	
							echo $Userright->linkAddParameterType(constant('WEB_ADMIN_URL').'Parameters/manageparatype/','<i class="fa fa-plus"></i>Add Parameter Type','','alt="addRecord" class="btn green btn-border" rel="addparatype"').'&nbsp;';
						}
						if($blnAddParameter){
							echo $Userright->linkAddParameter(constant('WEB_ADMIN_URL').'Parameters/manageparameter/','<i class="fa fa-plus"></i>Add Parameter','','alt="addRecord" class="btn green btn-border"');
						}
						?>	
                    </div>
				</div>
				<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage"));?>
				<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
				<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction"));?>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-1">ID(s)</label>
							<div class="col-md-3">
								<?php echo $this->Form->input('Parameter.para_id', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline  date-picker')); ?>
							</div>
							<label class="control-label col-md-1">Parameter</label>
							<div class="col-md-3">
								<?php echo $this->Form->input('Parameter.para_value', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline  date-picker')); ?>
							</div>
							<label class="control-label col-md-1">Parent Parameter</label>
							<div class="col-md-3">
								<?php echo $this->Form->select('Parameter.para_parent_id',$arrParentParaList, array('label' => false,'empty'=>'-Only Parameter Types-','div'=>false,'class'=>'form-control form-control-inline  date-picker','id'=>'parent-parameter-id')); ?>
							</div>
						</div>
						<div class="form-group">	
							<label class="control-label col-md-1">Search Date</label>
							<div class="col-md-3">
								<?php echo $this->Form->select('Parameter.search_date',array(''=>'-Select Search Date-','Parameters.created'=>'Created Date'), array('label' => false ,'div'=>false,'default'=>'','onchange'=>'resetdates();',"id"=>"SearchDate",'class'=>'form-control form-control-inline  date-picker'));?>
							</div>
							<label class="control-label col-md-1">Select Period</label>
							<div class="col-md-3">
								<?php echo $this->Form->select('Parameter.search_period',$period,array('label' => false ,'div'=>false,'default'=>'','onChange'=>'resetcustomdates(false);',"id"=>"SearchPeriod",'class'=>'form-control form-control-inline '));?>
							</div>
							<label class="control-label col-md-1">From Date</label>
							<div class="col-md-3">
								<?php echo $this->Form->input('Parameter.DateFrom', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text',"id"=>"DateFrom",'class'=>'form-control form-control-inline  date-picker'));?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-1">To Date</label>
							<div class="col-md-3">
								<?php echo $this->Form->input('Parameter.DateTo', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text',"id"=>"DateTo",'class'=>'form-control form-control-inline  date-picker'));?>
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
					
				</div>
				<div class="tools">
					
				</div>
			</div>
		<?php }?>
		<?php echo $this->element('parameterlist'); ?>
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

	<?php echo $JqdTablescr; ?>
	function resetsearch()
	{
	    $('#parameter-para-id').val("");
		$('#parameter-para-value').val("");
		$('#parent-parameter-id').val("");
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
