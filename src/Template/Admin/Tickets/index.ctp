<?php if($AjaxRequest=='0'){?>
<?php echo $this->Form->create('Ticket',array("id"=>"formmain",/*"url"=>"admin/tickets/index",*/'class'=>'form-horizontal form-bordered',"name"=>"searchTicketlist"));?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet box blue-madison">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-list-ul"></i>Ticket List
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
				</div>
			</div>
			<div class="portlet-body form">
				<?php echo $this->Form->hidden('draw',array("value"=>$page_count,"id"=>"draw")); ?>
				<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
				<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
				<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction"));?>
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-1">ID(s)</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Ticket.id', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>
						<label class="control-label col-md-1">Username</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Ticket.user_id', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div>
						<label class="control-label col-md-1">Date</label>
						<div class="col-md-3">
							<?php echo $this->Form->select('Ticket.search_date',array(''=>'-Select Search Date-','Ticket.created'=>'Ticket Date'), array('label' => false ,'id'=>'SearchDate','div'=>false,'type'=>'text','onchange'=>"resetdates();",'class'=>'form-control form-control-inline input-medium date-picker'));?>
						</div> 
					</div>

					<div class="form-group">
						<label class="control-label col-md-1">Period</label>
						<div class="col-md-3">
							<?php echo $this->Form->select('Ticket.search_period',$period, array('label' => false ,'div'=>false,'onChange'=>'resetcustomdates(false);', 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'SearchPeriod'));?>
						</div>
						<label class="control-label col-md-1">From Date</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Ticket.DateFrom', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker','id'=>'DateFrom'));?>
						</div> 
						<label class="control-label col-md-1">To Date</label>
						<div class="col-md-3">
							<?php echo $this->Form->input('Ticket.DateTo', array('label' => false ,'size'=>16,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium date-picker','id'=>'DateTo'));?>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-5 col-md-6">										
								<button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
								<button type="button" class="btn default" onClick="resetsearch()"><i class="fa fa-refresh"></i> Reset</button>

								<?php 
									$blnAddTicket	= $Userright->checkadminrights($Userright->ADD_TICKET);
									if($blnAddTicket)
									{	
										echo $Userright->linkAddTicket(constant('WEB_URL').'admin/tickets/manageticket/','<i class="fa fa-plus"></i> Add Ticket','','alt="addRecord" class="btn green pull-right" class="btn green pull-right" rel="createTicket"');
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
			<?php }?>
			<?php echo $this->element('ticketlist'); ?>
			<?php if($AjaxRequest=='0'){?>
		</div>
	</div>
</div>
<!--DISPLAY LIST OF ADMIN USERS -->



<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
<?php echo $this->Form->end(); ?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		resetcustomdates(true);
		resetdates();
	});

<?php echo $JqdTablescr; ?>

function resetsearch()
{
    $('#ticket-id').val("");
	$('#ticket-user-id').val("");
    $('#SearchDate').val("");
    $('#SearchPeriod').val("");
    $('#DateFrom').val("");
    $('#DateTo').val("");
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
			}
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
	$('#DateFrom').datepicker({format:'dd-mm-yyyy'});
	$('#DateTo').datepicker({format:'dd-mm-yyyy'});
}

$(document).ready(function(e) {
	div_start 	= '<div id="tooltip">';
	div_end 	= '</div>';
	
	div_tag_start 	= '<span class="tag">';
	fiv_tag_end 	= '</span>';
	
	div_body  	=	div_tag_start+'<span><i class="ico-contract mymargin"> </i> <span>View Ticket Details</span></span>'+fiv_tag_end;
	div_body  	+=	div_tag_start+'<span><i class="ico-disable mymargin"> </i> <span>Close Ticket</span></span>'+fiv_tag_end;
	div_body  	+=	div_tag_start+'<span><i class="ico-history mymargin"> </i> <span>View Ticket History</span></span>'+fiv_tag_end;
	
	var dav_tag = div_start + div_body + div_end;
    $('.dataTables_length').after(dav_tag);

});
</script>
<?php }?>
<?php
    /*echo $this->Html->script('autocomplete/jquery-ui-1.10.3.custom.min');
    echo $this->Html->script('autocomplete/customer');
    echo $this->fetch('script');*/
?>
