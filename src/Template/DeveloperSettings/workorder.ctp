<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style>
.form-horizontal .radio {

	padding-top: 0px !important;
}
.check-box-address{
	margin-top: 20px !important;
}
.mendatory_field{
	color:red;
}
.nk_tabs .tab-content a {
    color: #444 !important;
}
.chosen-container .chosen-results {
    max-height:200px;
}
.chosen-container.chosen-container-single {
	width: 343px !important; /* or any value that fits your needs */
}
.row {
	margin-right: 0px !important;
}
.radio {
	margin-bottom: 0px !important;
	margin-top: 0px !important;
}
.applay-online-from input[type="checkbox"] {
    width: 18px;
    float: left;
    margin-top: 5px !important;
    margin-left: 0px !important;
    margin-right: 5px !important;
}
.textCheckeboxLeft {
	margin-left: 25px !important;
}
.text-success {
	color:#4CC972 !important;
}
</style>
<?php
	$allocatedCategory 	= 3;
	$this->Html->addCrumb($pageTitle);
	$Report 			= "";
	/*if (isset($applyonlineapproval) && !empty($applyonlineapproval) ) {
		$Report 		= 1;
	}
	if($create_project=='1')
	{
		$str_url 		= '';
	}
	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_PATH.$ApplyOnlines->id.'/';
	}
	$IMAGE_EXT                  = array("png","jpg","gif","jpeg","bmp");

	
	$IsInstallerAllowedToSubmit = true;
	$ALERT_MESSAGE              = "";*/

	/*if($this->Session->read('Customers.customer_type')=='installer' && ($tab=='tab_1' || $tab=='') && $create_project=='1')
	{

		$CustomerID                 = $this->Session->read('Customers.id');
		$IsInstallerAllowedToSubmit = $ApplyOnlineObj->IsInstallerAllowedToSubmit($CustomerID);
		if (!$IsInstallerAllowedToSubmit) {
			$ALERT_MESSAGE = "You are not allowed to submit application for more than 140 kW. For further details contact GEDA office at Gandhinagar, GJ.";
		}
	}*/

	/** STOP B CATEGORY INSTALLERS TO SUBMIT THE APPLICATION */
	/*$newSchemeApp 		= 0;
	$pvCapacityText 	= 'DC';
	if(isset($ApplyOnlines->created) && strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
		$newSchemeApp 	= 1;
		$pvCapacityText = 'AC';
	}
	echo $this->Form->create($ApplyOnlines,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'contactForm', 'url' => '/apply-onlines/'.$str_url,'autocomplete'=>'off','onSubmit'=>'return CheckFormSubmit();'));*/
?>

<!-- File: src/Template/Users/login.ctp -->
<div class="container applay-online-from">
	<div class="row">
		<h2 class="col-md-12 col-lg-12 col-sm-12 mb-sm mt-sm"><strong>Set Project</strong></h2>
		
	</div>
	<?php echo $this->Flash->render('cutom_admin');?>
	<?php echo $this->Form->create($Workorder,['type'=>'file','name'=>'workorder_form','id'=>'workorder_form']);?>
	<div class="row">
		<div class="col-md-12" style=" text-align:right;" >
				<input style="margin-right:14px;"  class="btn green AddWorkOrderRow" type="button" id="" value="Add Project" /> 
		</div>
		<div class="col-md-12">
			<div class="col-md-12 col-sm-12 col-lg-12  block"  style=" text-align:right;"  >
				&nbsp;
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 table-responsive">
				<table id="tbl_wororder_info" class="table table-striped table-bordered table-hover custom-greenhead">
					<thead class="thead-dark">
						<th scope="col">Project</th>
						<th scope="col">Total Approved Capacity (in MW)</th>
						<th scope="col">Date</th>
						<th scope="col">Document</th>
						<th scope="col">Action</th>
					</thead>
					<tbody>
						<?php if (!empty($developerWorkOrderDetails) && isset($developerWorkOrderDetails)) { 
							foreach($developerWorkOrderDetails as $key=>$value) {
								$encode_application_id = encode($value->id);
								?>
								<tr class="mainRow">
									<td valign="top" class="">
										<?php echo $this->Form->input('Workorder.id_wororder][',['label' => true,'type'=>'hidden','value'=>$value->id,'class'=>'id_wororder','id'=>'id_wororder_'.$key]); ?>
										<?php echo $this->Form->input('Workorder.work_no][', array('label' => false,'class'=>'form-control work_no_cls rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'work_no_'.$key,'value'=>$value->workorder_no)); ?>
										
									</td>
									<td valign="top" class="">
										<?php echo $this->Form->input('Workorder.capacity][', array('label' => false,'class'=>'form-control rfibox capacity_cls','placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'capacity_'.$key,'value'=>$value->capacity,'disabled'=>'disabled')); ?>
										
									</td>
									<td valign="top" class="" >
										<?php echo $this->Form->input('Workorder.workorder_date][',["type" => "text",'label'=>false,'id'=>'workorder_date_'.$key,"class" => "form-control workorder_date_cls",'value'=>date('d-M-Y',strtotime($value->workorder_date))]);?>
										
									</td>
									<td valign="top" class="" >
										<div class="file-loading" >
											<?php echo $this->Form->input('Workorder.workorder_doc][', array('label' => false,'div' => false,'type'=>'file','id'=>'workorder_doc_'.$key,"class" => "form-control workorder_doc_cls",'templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
										</div>
										<?php if(!empty($value->workorder_doc)) : ?>
										<?php if($ReCouchdb->documentExist($value->id,$value->workorder_doc)) { ?>
												<?php
													echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/workorder/'.encode($value->id)."\">View Document</a></strong><input type='hidden' id='workorder_doc_view_".$key."' value='1' />
										";
												}
												?>
										<?php endif; ?>
										
										<?php /*<input type="file" accept=".pdf" id='workorder_doc_0' name="Workorder[workorder_doc][0]" class="workorder_doc_cls">*/?>
										<div class="workorder_doc_err_cls" id="workorder_doc_<?php echo $key;?>_cls"></div>
				
											
									</td>
									<td valign="top" class=" lastrow" id="actionrange_<?php echo $key;?>" ><input style="margin-right:14px;"  class="btn green" type="button" onclick="javascript:AddChildWorkOrderRow(this);" id="" value="Assign Project" /></td>
								</tr>
								<?php $subRowClass = (isset($value->assignWorkorder) && !empty($value->assignWorkorder)) ? '' : 'hide';?>
								<tr class="subMainRow_<?php echo $key;?> <?php echo $subRowClass;?>" >
									<td valign="top" class="" colspan="5">
										<table id="tbl_child_workorder_info_<?php echo $key;?>" class="table table-striped table-bordered table-hover custom-greenhead">
											<thead class="thead-dark">
												<th scope="col">Assign Project</th>
												<th scope="col">Set Capacity (in MW)</th>
												<th scope="col">Assign Developer</th>
												<th scope="col">RE Applications</th>
												<th scope="col">Action</th>
											</thead>
											<tbody>
												<?php if(isset($value->assignWorkorder) && !empty($value->assignWorkorder)) { 
														foreach($value->assignWorkorder as $keyAssign=>$assignedWO) { ?>
														<tr>
															<td valign="top" class="">
																<?php echo $this->Form->input('Workorder.child_id_wororder_'.$key.'][',['label' => true,'type'=>'hidden','value'=>$value->id,'class'=>'child_id_wororder_'.$key,'id'=>'child_id_wororder_'.$key.'_'.$keyAssign,'value'=>$assignedWO->id]); ?>
																<?php echo $this->Form->input('Workorder.child_work_no_'.$key.'][', array('label' => false,'class'=>'form-control child_work_no_cls_'.$key.' rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'child_work_no_'.$key.'_'.$keyAssign,'value'=>$assignedWO->workorder_no)); ?>
																
															</td>
															<td valign="top" class="">
																<?php echo $this->Form->input('Workorder.child_capacity_'.$key.'][', array('label' => false,'class'=>'form-control rfibox child_capacity_cls_'.$key,'placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'child_capacity_'.$key.'_'.$keyAssign,'value'=>$assignedWO->capacity,'onchange'=>"javascript:setTotalAll()")); ?>		
															</td>
															<td valign="top" class="">
																<?php //echo $this->Form->input('Workorder.child_developer_'.$key.'][', array('label' => false,'class'=>'form-control rfibox child_developer_cls_'.$key,'placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'child_capacity_'.$key.'_0')); ?>	
																<?php echo $this->Form->select('Workorder.child_developer_'.$key.'][', $arrDeveloper,array('label' => false,'class'=>'form-control rfibox child_developer_cls_'.$key,'empty'=>'-Select Developer-','placeholder'=>'','id'=>'child_developer_'.$key.'_'.$keyAssign,'value'=>$assignedWO->assign_installer_id)); ?>	
																
															</td>
															<td valign="top">
																<?php $arrApplications = $DeveloperAssignWorkorder->getWorkOrderMappedApplication($assignedWO->id);
																if(!empty($arrApplications)) {
																	foreach($arrApplications as $keyMapped=>$applicationMapped) {
																		echo ($keyMapped > 0) ? '<br>'.$applicationMapped : $applicationMapped;
																	}
																}
																?>
															</td>
															<td valign="top" class=" child_lastrow_<?php echo $key;?>" >
																<?php 	if($assignedWO->status == 0) { echo 'Pending'; } 
																		else if($assignedWO->status == 1) { echo '<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Accepted</span>'; }
																		else  { echo '<i class="fa fa-times" aria-hidden="true"></i> <span class="text-danger bold" title="'.$assignedWO->reject_reason.'">Rejected</span>'; } ?>

															</td>
														</tr>
												<?php }  } else { ?>
													<tr>
														<td valign="top" class="">
															<?php echo $this->Form->input('Workorder.child_work_no_'.$key.'][', array('label' => false,'class'=>'form-control child_work_no_cls_'.$key.' rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'child_work_no_'.$key.'_0')); ?>
															
														</td>
														<td valign="top" class="">
															<?php echo $this->Form->input('Workorder.child_capacity_'.$key.'][', array('label' => false,'class'=>'form-control rfibox child_capacity_cls_'.$key,'placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'child_capacity_'.$key.'_0','onchange'=>"javascript:setTotalAll()")); ?>		
														</td>
														<td valign="top" class="">
															<?php //echo $this->Form->input('Workorder.child_developer_'.$key.'][', array('label' => false,'class'=>'form-control rfibox child_developer_cls_'.$key,'placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'child_capacity_'.$key.'_0')); ?>	
															<?php echo $this->Form->select('Workorder.child_developer_'.$key.'][', $arrDeveloper,array('label' => false,'class'=>'form-control rfibox child_developer_cls_'.$key,'empty'=>'-Select Developer-','placeholder'=>'','id'=>'child_developer_'.$key.'_0')); ?>	
														</td>
														<td valign="top" class="" ></td>
														<td valign="top" class=" child_lastrow_<?php echo $key;?>" ></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</td>
								</tr>
								
							<?php } ?>
						<?php } else { ?>
							<tr class="mainRow">
								<td valign="top" class="">
									<?php echo $this->Form->input('Workorder.work_no][', array('label' => false,'class'=>'form-control work_no_cls rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'work_no_0')); ?>
									
								</td>
								<td valign="top" class="">
									<?php echo $this->Form->input('Workorder.capacity][', array('label' => false,'class'=>'form-control rfibox capacity_cls','placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'capacity_0')); ?>
									
								</td>
								<td valign="top" class="" >
									<?php echo $this->Form->input('Workorder.workorder_date][',["type" => "text",'label'=>false,'id'=>'workorder_date_0',"class" => "form-control workorder_date_cls"]);?>
									
								</td>
								<td valign="top" class="" >
									
										<div class="file-loading" >
											<?php echo $this->Form->input('Workorder.workorder_doc][', array('label' => false,'div' => false,'type'=>'file','id'=>'workorder_doc_0',"class" => "form-control workorder_doc_cls",'templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
										</div>
										<input type='hidden' id='workorder_doc_view_0' value='0' />
									
									<?php /*<input type="file" accept=".pdf" id='workorder_doc_0' name="Workorder[workorder_doc][0]" class="workorder_doc_cls"><a href="/Undertaking_by_REG.docx" style="text-decoration: underline;"><strong>[Download Undertaking Format]</strong></a>*/?>
									<div class="workorder_doc_err_cls" id="workorder_doc_0_cls"></div>
			
										
								</td>
								<td valign="top" class=" lastrow" id="actionrange_0"><input style="margin-right:14px;"  class="btn green" type="button" onclick="javascript:AddChildWorkOrderRow(this);" id="" value="Assign Project" /></td>
							</tr>
							<tr class="subMainRow_0 " ><?php //hide?>
								<td valign="top" class="" colspan="5">
									<table id="tbl_child_workorder_info_0" class="table table-striped table-bordered table-hover custom-greenhead">
										<thead class="thead-dark">
											<th scope="col">Assign Project</th>
											<th scope="col">Set Capacity (in MW)</th>
											<th scope="col">Assign Developer</th>
											<th scope="col">RE Applications</th>
											<th scope="col">Action</th>
										</thead>
										<tbody>
											<tr>
												<td valign="top" class="">
													<?php echo $this->Form->input('Workorder.child_work_no_0][', array('label' => false,'class'=>'form-control child_work_no_cls_0 rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'child_work_no_0_0')); ?>
													
												</td>
												<td valign="top" class="">
													<?php echo $this->Form->input('Workorder.child_capacity_0][', array('label' => false,'class'=>'form-control rfibox child_capacity_cls_0','placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'child_capacity_0_0','onchange'=>"javascript:setTotalAll()")); ?>
													
												</td>
												<td valign="top" class="">
													<?php echo $this->Form->select('Workorder.child_developer_0][', $arrDeveloper,array('label' => false,'class'=>'form-control rfibox child_developer_cls_0','empty'=>'-Select Developer-','placeholder'=>'','id'=>'child_developer_0_0')); ?>	
												</td>
												<td valign="top" class="" ></td>
												<td valign="top" class=" child_lastrow_0" ></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-12" >
			<?php echo $this->Form->input('Submit', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_1','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_1','style'=>"float: right;")); ?> 
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.fa').popover({trigger: "hover"});
	$(".workorder_date_cls").datepicker({format:'dd-M-yyyy',autoclose: true});
	$('.applicationform3').submit(function(){
		$('.applicationform3 input ,.applicationform3 select').removeAttr('disabled');
	});
	$(".AddWorkOrderRow").click(function() { AddWorkOrderRow(); });
	<?php if(count($developerWorkOrderDetails) == 0) { ?>
		$("#workorder_doc_0").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#workorder_doc_0_cls',
			maxFileSize: '1024',
		});
	<?php } else { foreach($developerWorkOrderDetails as $key=>$val) {  ?> 
		$("#workorder_doc_<?php echo $key;?>").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#workorder_doc_<?php echo $key;?>_cls',
			maxFileSize: '1024',
		});
	<?php } } ?>
	
	/*$("#workorder_doc_0").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#workorder_doc_0_cls',
		maxFileSize: '1024',
	});*/
	/*$("#workorder_doc_1").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '.workorder_doc_err_cls',
		maxFileSize: '1024',
	});*/
});
	
function validateDecimal(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
		return false;
	} else {
		var parts = key.srcElement.value.split('.');
		if (parts.length > 1 && keycode == 46) return false;
		return true;
	}
}
function validateNumber(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8) && (keycode < 48 || keycode > 57)) {
		return false;
	} 
}



var index = [];
// Array starts with 0 but the id start with 0 so push a dummy value
index.push(0);
// Push 1 at index 1 since one child element is already created
index.push(1)


function removeWorkorder(id,application_id,total_capacity,capacity_type,nos,capacity,encode_application_id) {

    var id 						= id;
    var application_id 			= application_id;
    var encode_application_id 	= encode_application_id;
    var total_capacity 			= total_capacity;
    var capacity_type			= capacity_type;
	var nos 					= nos;
    var capacity				= capacity;
    console.log(capacity);
    swal({
          title: "Are you sure?",
          text: "You want to delete the file?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Delete it!",
          cancelButtonText: "No, cancel please!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {
          if (isConfirm) {
            $.ajax({
                        type: "POST",
                        url: "/Applications/removeWorkorder",
                        data: {'id':id,'application_id':application_id,'capacity':capacity,'capacity_type':capacity_type,'nos':nos,'total_capacity':total_capacity},
                        beforeSend: function(xhr){
							xhr.setRequestHeader(
								'X-CSRF-Token',
								<?= json_encode($this->request->param('_csrfToken')); ?>
							);
						},
                        success: function(response) {
                            var result = $.parseJSON(response);
                            console.log(result.success);
                            if (result.success == 1) {
								swal("Deleted!", "Record has been deleted.", "success");
								window.location.href = '/Hybrid/'+encode_application_id+'/2'; 
                            }
                        }
                    });

          } else {
            swal("Cancelled", "Your Application file is safe :)", "error");
          }
        });
}

/*******add component related code start ************/
function AddWorkOrderRow() {
	var addRow 				= 1;
	var lastProcessIndex 	= 0;
	$(".mainRow").each(function(index,tr) {
		//console.log($("#error_msg_"+index).html());
		
		if($("#work_no_error_msg_"+index).html() != undefined) {
			$("#work_no_error_msg_"+index).parent().removeClass('has-error');
			$("#work_no_error_msg_"+index).remove();
		}
		if($("#capacity_error_msg_"+index).html() != undefined) {
			$("#capacity_error_msg_"+index).parent().removeClass('has-error');
			$("#capacity_error_msg_"+index).remove();
		}
		if($("#workorder_date_error_msg_"+index).html() != undefined) {
			$("#workorder_date_error_msg_"+index).parent().removeClass('has-error');
			$("#workorder_date_error_msg_"+index).remove();
		}
		if($("#workorder_doc_error_msg_"+index).html() != undefined) {
			$("#workorder_doc_error_msg_"+index).parent().removeClass('has-error');
			$("#workorder_doc_error_msg_"+index).remove();
		}

	});
	$(".mainRow").each(function(index,tr) {
		var work_no 			= $.trim($("#work_no_"+index).val()) !== '' ? ($("#work_no_"+index).val()) : '';
		var capacity 			= $("#capacity_"+index).val() ? parseFloat($("#capacity_"+index).val()) : 0;
		var workorder_date 		= $("#workorder_date_"+index).val() ? parseFloat($("#workorder_date_"+index).val()) : '';
		var workorder_doc 		= $("#workorder_doc_"+index).val() ? parseFloat($("#workorder_doc_"+index).val()) : '';
		var workorder_doc_view 	= $("#workorder_doc_view_"+index).val();
		if($.trim(work_no)=='' || capacity<=0 || $.trim(workorder_date) == '' || ($.trim(workorder_doc) == '' && workorder_doc_view != 1)) {
			addRow  		= 0;
		}
		
		if($.trim(work_no)=='') {
			$("#work_no_"+index).parent().addClass('has-error');
			$("#work_no_"+index).parent().append('<div class="help-block work_error_cls" id="work_no_error_msg_'+index+'">Please enter the value</div>');
		}
		if(capacity <= 0) {
			$("#capacity_"+index).parent().addClass('has-error');
			$("#capacity_"+index).parent().append('<div class="help-block capacity_error_cls" id="capacity_error_msg_'+index+'">Please enter the value</div>');
		}
		if($.trim(workorder_date)=='') {
			$("#workorder_date_"+index).parent().addClass('has-error');
			$("#workorder_date_"+index).parent().append('<div class="help-block workorder_date_error_cls" id="workorder_date_error_msg_'+index+'">Please select the date</div>');
		}
		if($.trim(workorder_doc)=='' && workorder_doc_view != 1) {
			$("#workorder_doc_"+index).parent().parent().parent().parent().addClass('has-error');
			$("#workorder_doc_"+index).parent().parent().parent().parent().append('<div class="help-block workorder_doc_error_cls" id="workorder_doc_error_msg_'+index+'">Please select the document</div>');
			//$("#workorder_doc_"+index+"_cls").html('Please select the document');
		}
		lastProcessIndex = index;
		
	});
	if(addRow == 1) {
		 $.ajax({
			type: "POST",
			url: "/DeveloperSettings/addComponent",
			data: {'newRowCounter':lastProcessIndex+1},
			beforeSend: function(xhr){
				xhr.setRequestHeader(
					'X-CSRF-Token',
					<?= json_encode($this->request->param('_csrfToken')); ?>
				);
			},
			success: function(response) {
				var result = (response);
				$("#tbl_wororder_info").append(result);
			}
		});

		/*var newRow 	= $(".mainRow:last").clone(true).find('.rfibox').val('').end();
		var curRow 	= lastProcessIndex+1;
		console.log(curRow);
		newRow.find('.id_wororder').val('');
		// $("#workorder_doc_'+curRow+'").fileinput({'+
		// 	'showUpload: false,'+
		// 	'showPreview: false,'+
		// 	'dropZoneEnabled: false,'+
		// 	'mainClass: "input-group-md",'+
		// 	'allowedFileExtensions: ["pdf"],'+
		// 	'elErrorContainer: "#workorder_doc_'+curRow+'_cls",'+
		// 	'maxFileSize: "1024",'+
		// '});<script language="javascript">$("#workorder_date_'+curRow+'").datepicker({format:"dd-mm-yyyy",autoclose: true});<\/script>
		newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowWorkorder(this)" value="-" />&nbsp;&nbsp;<input style="margin-right:14px;"  class="btn green" type="button" onclick="javascript:AddChildWorkOrderRow(this);" id="" value="+" />');
		$("#tbl_wororder_info").append(newRow);
		//#workorder_doc_'+curRow+'-file-errors
		//$("#tbl_wororder_info").append('');
		//console.log($("#tbl_wororder_info").html());
		//$("#workorder_date_"+curRow).datepicker({format:"dd-mm-yyyy",autoclose: true});
		$(".workorder_date_cls").datepicker({format:"dd-mm-yyyy",autoclose: true});*/
	}
	ResetWororderRowID(addRow);
}
var lastId = 0;
function ResetWororderRowID(addRow)
{
	
	$(".mainRow").each(function(index,tr) {
		$(tr).find(".work_no_cls").attr("id","work_no_"+index);
		$(tr).find(".work_no_cls").attr("name","Workorder[work_no]["+index+"]");
		$(tr).find(".work_error_cls").attr("id","work_no_error_msg_"+index);
		$(tr).find(".capacity_cls").attr("id","capacity_"+index);
		$(tr).find(".capacity_cls").attr("name","Workorder[capacity]["+index+"]");
		$(tr).find(".capacity_error_cls").attr("id","capacity_error_msg_"+index);
		$(tr).find(".id_wororder").attr("id","id_wororder_"+index+"");
		$(tr).find(".id_wororder").attr("name","Workorder[id_wororder]["+index+"]");
		$(tr).find(".workorder_date_cls").attr("id","workorder_date_"+index);
		$(tr).find(".workorder_date_cls").attr("name","Workorder[workorder_date]["+index+"]");
		$(tr).find(".workorder_date_error_cls").attr("id","workorder_date_error_msg_"+index);
		$(tr).find(".workorder_doc_cls").attr("id","workorder_doc_"+index);
		$(tr).find(".workorder_doc_cls").attr("name","Workorder[workorder_doc]["+index+"]");
		$(tr).find(".workorder_doc_err_cls").attr("id","workorder_doc_"+index+"_cls");
		$(tr).find(".workorder_doc_error_cls").attr("id","workorder_doc_error_msg_"+index);
		
		$(tr).find(".lastrow").attr("id","actionrange_"+index);
		
		lastId = index;
	});
	
	if(addRow == 1) {
		var newRow = $(".subMainRow_"+(lastId-1)+":last").clone(true).addClass('subMainRow_'+lastId).removeClass('subMainRow_'+(lastId-1));
		newRow.find('#tbl_child_workorder_info_'+(lastId-1)).attr("id","tbl_child_workorder_info_"+lastId);

		/*newRow.find("#tbl_child_workorder_info_"+lastId).html('<thead class="thead-dark"><th scope="col">Project</th><th scope="col">Set Capacity (in MW)</th><th scope="col">Action</th></thead><tbody><tr><td class="valignTop"><input type="text" name="Workorder[child_work_no_'+lastId+'][]" class="form-control rfibox child_work_no_cls_'+lastId+'" placeholder="Project" autocomplete="false" id="child_work_no_'+lastId+'_0" templatevars=""></td><td class="valignTop"><input type="text" name="Workorder[child_capacity_'+lastId+'][]" class="form-control rfibox child_capacity_cls_'+lastId+'" placeholder="Total Capacity" onkeypress="return validateDecimal(event)" autocomplete="false" id="child_capacity_'+lastId+'_0" templatevars=""></td><td class="valignTop child_lastrow_'+lastId+'" ></td></tr></tbody>');*/

		
		/*newRow.find(".child_work_no_cls_"+(lastId-1)).addClass("child_work_no_cls_"+lastId).removeClass("child_work_no_cls_"+(lastId-1));
		newRow.find(".child_capacity_cls_"+(lastId-1)).addClass("child_capacity_cls_"+lastId).removeClass("child_capacity_cls_"+(lastId-1));
		newRow.find(".child_id_wororder_"+(lastId-1)).addClass("child_id_wororder_"+lastId).removeClass("child_id_wororder_"+(lastId-1));
		newRow.find(".child_lastrow_"+(lastId-1)).addClass("child_lastrow_"+lastId).removeClass("child_lastrow_"+(lastId-1));*/
		
		//newRow.find('.subMainRow_'+(lastId-1)).addClass('subMainRow_'+lastId);
		//	newRow.find('.subMainRow_'+(lastId-1)).removeClass('subMainRow_'+(lastId-1));
		//$("#tbl_wororder_info").append(newRow);
		$(".workorder_date_cls").datepicker({format:"dd-M-yyyy",autoclose: true});
		ResetChildWorkorderRowID(lastId);
	}
}
function deleterRowWorkorder(_obj) 
{ 
	var deleteCellId= $(_obj).parent().attr("id");
	
	var arrData 	= deleteCellId.split("_");
	var idval 		= parseInt(arrData[1]);
	
	$(_obj).parent().parent().remove(); 
	ResetWororderRowID(0);
	//setTotalAll();
}


function AddChildWorkOrderRow(_obj) {
	var addRow 			= 1;
	var currentId 		= $(_obj).parent().attr("id");
	var arrData 		= currentId.split("_");
	var id 				= parseInt(arrData[1]);
	var TotalCapacity 	= 0;
	$(".subMainRow_"+id).removeClass('hide');
	
	$("#tbl_child_workorder_info_"+id+" > tbody  > tr").each(function(index,tr) {
		//console.log($("#error_msg_"+index).html());
		
		if($("#child_work_no_error_msg_"+id+"_"+index).html() != undefined) {
			$("#child_work_no_error_msg_"+id+"_"+index).parent().removeClass('has-error');
			$("#child_work_no_error_msg_"+id+"_"+index).remove();
		}
		if($("#child_capacity_error_msg_"+id+"_"+index).html() != undefined) {
			$("#child_capacity_error_msg_"+id+"_"+index).parent().removeClass('has-error');
			$("#child_capacity_error_msg_"+id+"_"+index).remove();
		}
		if($("#child_developer_error_msg_"+id+"_"+index).html() != undefined) {
			$("#child_developer_error_msg_"+id+"_"+index).parent().removeClass('has-error');
			$("#child_developer_error_msg_"+id+"_"+index).remove();
		}
	});
	$("#tbl_child_workorder_info_"+id+" > tbody  > tr").each(function(index,tr) {
		var work_no 	= $.trim($("#child_work_no_"+id+"_"+index).val()) !== '' ? ($("#child_work_no_"+id+"_"+index).val()) : '';
		var capacity 	= $("#child_capacity_"+id+"_"+index).val() ? parseFloat($("#child_capacity_"+id+"_"+index).val()) : 0;
		var developer 	= $("#child_developer_"+id+"_"+index).val() ? parseFloat($("#child_developer_"+id+"_"+index).val()) : 0;
		if($.trim(work_no)=='' || capacity<=0 || developer<=0) {
			addRow  		= 0;
		}
		work_order_capacity = parseFloat($('#capacity_'+id).val());
		
		TotalCapacity 		= TotalCapacity + parseFloat(capacity);
		if($.trim(work_no)=='') {
			$("#child_work_no_"+id+"_"+index).parent().addClass('has-error');
			$("#child_work_no_"+id+"_"+index).parent().append('<div class="help-block child_work_error_cls_'+id+'" id="child_work_no_error_msg_'+id+'_'+index+'">Please enter the value</div>');
		}
		
		if(capacity <= 0 || work_order_capacity < TotalCapacity) {
			if(work_order_capacity < TotalCapacity)
			{
				$("#child_capacity_"+id+"_"+index).parent().addClass('has-error');
				$("#child_capacity_"+id+"_"+index).parent().append('<div class="help-block child_capacity_error_cls_'+index+'" id="child_capacity_error_msg_'+id+'_'+index+'">Total capacity should be less than work order capacity.</div>');
				addRow  		= 0;
				//alert("Please set propoer value of capacity. "+index);
			} else {
				$("#child_capacity_"+id+"_"+index).parent().addClass('has-error');
				$("#child_capacity_"+id+"_"+index).parent().append('<div class="help-block child_capacity_error_cls_'+id+'" id="child_capacity_error_msg_'+id+'_'+index+'">Please enter the value</div>');
			}
		} if(developer <= 0) {
			$("#child_developer_"+id+"_"+index).parent().addClass('has-error');
			$("#child_developer_"+id+"_"+index).parent().append('<div class="help-block child_developer_error_cls_'+id+'" id="child_developer_error_msg_'+id+'_'+index+'">Please select the developer</div>');
		}
	});
	if(addRow == 1) {
		var newRow = $("#tbl_child_workorder_info_"+id+" tr:last").clone(true).find('.rfibox').val('').end();
		newRow.find('.child_id_wororder_'+id).val('');
		newRow.find(".child_lastrow_"+id).html('');//<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteChildRowWorkorder(this,'+id+')" value="-" />
		$("#tbl_child_workorder_info_"+id).append(newRow);
	}
	ResetChildWorkorderRowID(id);
}
function ResetChildWorkorderRowID(id)
{
	$("#tbl_child_workorder_info_"+id+" > tbody  > tr").each(function(index,tr) {
		$(tr).find(".child_work_no_cls_"+id).attr("id","child_work_no_"+id+"_"+index);
		$(tr).find(".child_work_no_cls_"+id).attr("name","Workorder[child_work_no_"+id+"]["+index+"]");
		$(tr).find(".child_work_error_cls_"+id).attr("id","child_work_no_error_msg_"+id+"_"+index);
		$(tr).find(".child_capacity_cls_"+id).attr("id","child_capacity_"+id+"_"+index);
		$(tr).find(".child_capacity_cls_"+id).attr("name","Workorder[child_capacity_"+id+"]["+index+"]");
		$(tr).find(".child_capacity_error_cls_"+id).attr("id","child_capacity_error_msg_"+id+"_"+index);
		$(tr).find(".child_id_wororder_"+id).attr("id","child_id_wororder_"+id+"_"+index+"");
		$(tr).find(".child_id_wororder_"+id).attr("name","Workorder[child_id_wororder_"+id+"]["+index+"]");
		$(tr).find(".child_developer_cls_"+id).attr("id","child_developer_"+id+"_"+index);
		$(tr).find(".child_developer_cls_"+id).attr("name","Workorder[child_developer_"+id+"]["+index+"]");
		$(tr).find(".child_developer_error_cls_"+id).attr("id","child_developer_error_msg_"+id+"_"+index);
		$(tr).find(".child_lastrow_"+id).attr("id","child_actionrange_"+id+"_"+index);
	});
}
function deleteChildRowWorkorder(_obj,id) 
{ 
	var deleteCellId= $(_obj).parent().attr("id");
	var arrData 	= deleteCellId.split("_");
	var idval 		= parseInt(arrData[1]);
	
	$(_obj).parent().parent().remove(); 
	ResetChildWorkorderRowID(id);
	//setTotalAll();
}
/*******add component related code end ************/

<?php if($errorWorkOrder == 1) { ?>
	AddWorkOrderRow();
<?php } ?>
function setTotalAll()
{
	var lastindex 	= 0;
	var flagErorr 	= 0;
	$(".capacity_cls").each(function(index,val) {
		if($(this).val()>0) {
			work_order_capacity = parseFloat($(this).val());
			var childCapacity 	= 0;

			$(".child_capacity_cls_"+index).each(function(index_ch,val_ch) {
				if($("#child_capacity_error_msg_"+index+"_"+index_ch).html() != undefined) {
					$("#child_capacity_error_msg_"+index+"_"+index_ch).parent().removeClass('has-error');
					$("#child_capacity_error_msg_"+index+"_"+index_ch).remove();
				}
				if(parseFloat($(this).val()) != NaN) {
					childCapacity 	= childCapacity+parseFloat($(this).val());
					if(work_order_capacity < childCapacity)
					{
						$("#child_capacity_"+index+"_"+index_ch).parent().addClass('has-error');
						$("#child_capacity_"+index+"_"+index_ch).parent().append('<div class="help-block child_capacity_error_cls_'+index+'" id="child_capacity_error_msg_'+index+'_'+index_ch+'">Total capacity should be less than work order capacity.</div>');
						flagErorr = 1;
						//alert("Please set propoer value of capacity. "+index);
					}
				}
				lastindex 		= index_ch;
			});
		}
	});
}

$('#workorder_form').submit(function() {
	$('.capacity_cls').removeAttr('disabled'); 
});
</script>


