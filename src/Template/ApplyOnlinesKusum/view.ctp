<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.progressbar_guj li {
	width: calc(10% - 0px);
	font-size: 8px !important;
}
.progressbar_guj span
{
	font-size: 8px !important;
}
.hide_class
{
	display:none;
}

.check-box-address{
	margin-left: 0px !important;
	margin-top: -15px !important;
}

</style>
<?php if($AjaxRequest=='0'){ ?>
<?php
	$this->Html->addCrumb('My Apply-online-kusum List','apply-online-kusum-list'); 
	$this->Html->addCrumb($pageTitle); 
	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_PATH.$ApplyOnlines->id.'/';
	}
	if($ApplyOnlines->apply_state!=4 && strtolower($ApplyOnlines->apply_state)!='gujarat')
	{
		//$ApplyOnlines->jreda_processing_fee = 0;
	}
	$approval           = $MStatus->Approvalstage($ApplyOnlines->id);
	$updateRequest      = false;
	$titleClass         = "col-md-8";
	if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && $is_member==false)
	{
		$updateRequest  = true;
		//$titleClass     = "col-md-6";
	}
	$newSchemeApp 		= 0;
	$pvCapacityText 	= 'DC';
	$pvCapacityACText 	= 'AC';
	$PolicyYear     	= '2015';
	$GRNumber 			= 'SLR-11-2015-2442-B';
	$GRNumberDate 		= '13-08-2015';
	$submitedStage 		= $MStatus->getsubmittedStageData($ApplyOnlines->id);
	if(strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)))) {
		$newSchemeApp 		= 1;
		$pvCapacityText 	= 'AC';
		$pvCapacityACText 	= 'DC';
		$PolicyYear 		= '2021';
		$GRNumber 			= 'SLR/11/20121/77/B1';
		$GRNumberDate 		= '29th December 2020';
	}
?>
<div class="container">
	<div class="box">
		<div class="content">
			<div class="portlet box blue-madison applyonline-viewmain">
				<div class="row">
					<h2 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Apply Online</strong> Kusum View </h2>
					<?php
					if($updateRequest)
					{
						
					}
					else
					{
					
					}
					?>
					
					<div class="col-md-4" style="margin-top:30px;text-align:right"><strong>Application No.: <?php echo $ApplyOnlines->application_no;?></strong></div>
				</div>
				<div class="portlet-body form">
					<div class="progressbar-container">
						<?php
						$ul_progress = 'progressbar';
						$str_append     = '';
						if($ApplyOnlines->application_status == $MStatus->APPROVED_FROM_GEDA && ($ApplyOnlines->category!=$ApplyOnlines->category_residental || ($ApplyOnlines->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1) || ($ApplyOnlines->govt_agency==1 && GOVERMENT_AGENCY==1)) && $ApplyOnlines->payment_status==0)
						{
							$str_append = ' - Payment Pending';
						}
						if($ApplyOnlines->apply_state=='4' || strtolower($ApplyOnlines->apply_state)=='gujarat')
						{
							$ul_progress = 'progressbar_guj';
						}
						?>
						<ul class="<?php echo $ul_progress;?>">
							<?php $active  	= '';
							$str_append 	= '';
							$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);
							if($ApplyOnlines->application_status == $MStatus->APPROVED_FROM_GEDA && $ApplyOnlines->payment_status==0)
							{
								$str_append = ' - Payment Pending';
							}
							foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) {  
								if($key==1 && $ApplyOnlines->application_status == $key){
									$active = $key;
									break;
								} else if ($key==2 && $ApplyOnlines->application_status == $key && empty($str_append)) {
									$active = $key;
									break;
								} else if (($key == 5 || $key == 6)  && $ApplyOnlines->application_status == $key) {
									$active = $key;
									break;
								} 
								else if ($key == 3  && $ApplyOnlines->application_status == 23) {
									$active = $key;
									break;
								} 
								else if ($key == 4 && ($ApplyOnlines->application_status == 24 || $ApplyOnlines->application_status == 3)) {
									$active = $key;
									break;
								}
								else if ($key == 5 && ($ApplyOnlines->application_status == 9)) {
									$active = $key;
									break;
								} 
								else if ($key == 6 && ($ApplyOnlines->application_status == 4 || $ApplyOnlines->application_status == 25 )) {
									$active = $key;
									break;
								}
								else if ($key == 7 && ($ApplyOnlines->application_status == 12 || $ApplyOnlines->application_status == 26)) {
									$active = $key;
									break;
								}
								else if ($key == 8 && ($ApplyOnlines->application_status == 27 || $ApplyOnlines->application_status == 15)) {
									$active = $key;
									break;
								}
								else if ($key == 9 && ($ApplyOnlines->application_status == 28)) {
									$active = $key;
									break;
								}
							} 
							foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) { 
								$IsActive = array_key_exists($key, $arr_application_status)?"active":"";
								if(empty($arr_application_status))
								{
									$IsActive = ($key==$ApplyOnlines->application_status)?"active":"";
								}
								if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlines->disclaimer_subsidy==1)
								{

								}
								else
								{
									$text_apply     = '';
									$style          = 'font-size:8px;';
									if($str_append!='' && $key==2)
									{
										$IsActive   = '';
									}
									
									if ($ApplyOnlines->pv_capacity<=10 && ($key==5 || $key==7)) 
										{
											$text_apply = ' Self Certification';
											//$style      = "font-size:8px;";

											$value      = ($key==5) ? "Approval" : "Inspection";
										}
									?>
									<li class="<?php echo $IsActive;?>"><span style='<?php echo $style;?>'><?php echo $value.$text_apply; ?></span></li>
									<?php
								}
							} ?>
						</ul>
					</div>
					<div class="form-body">
						<div class="greenbox">
							<h4>Description of Applicant</h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Applicant Type</label>
									<?php echo isset($ParametersDetails->para_value) ? $ParametersDetails->para_value : ''; ?>                  
								</div>
								<div class="col-md-6">
									<label>Name</label>
									 <?php echo (!empty($ApplyOnlines->application_type_name)) ?$ApplyOnlines->application_type_name :"-" ; ?>
								</div> 
							</div> 

							<?php if($ApplyOnlinesTable->TypeGroup == $ApplyOnlines->applicant_type_kusum) { ?>
								<div class="row">
									<div class="col-md-6">
										<label>List of Members</label>
										<?php $arrM = array(); ?>
										<?php if(!empty($arrMembers))  { foreach($arrMembers as $memberData) {
											$arrM[]	= 	$memberData->name;
										} }
										if(count($arrM) > 0) {
											echo implode(", ",$arrM);
										}
										?>                
									</div>
								</div> 
							<?php } ?>
							
							<div class="row">
								<div class="col-md-6">
									<label>Correspondence address</label>
									<?php echo $ApplyOnlines->correspondence_address; ?>                  
								</div>
								<div class="col-md-6">
									<label>Name of the authorized person</label>
									 <?php echo (!empty($ApplyOnlines->authorized_person)) ?$ApplyOnlines->authorized_person :"-" ; ?>
								</div> 
							</div> 
							<div class="row">
								<div class="col-md-6">
									<label>Mobile Number</label>
									<?php echo $ApplyOnlines->mobile; ?>                  
								</div>
								<div class="col-md-6">
									<label>E-mail Id</label>
									<?php echo (!empty($ApplyOnlines->email)) ?$ApplyOnlines->email :"-" ; ?>
								</div> 
							</div> 
						</div> 
						<div class="greenbox">
							<h4> Details of Sub-station  </h4>
						</div>
						<?php
						$disDetails 	= $ApplyOnlinesTable->getDiscomDetails($ApplyOnlines->circle,$ApplyOnlines->division,$ApplyOnlines->subdivision,$ApplyOnlines->area,1);
						$district_name 	= $ApplyOnlinesTable->getDistrictName($ApplyOnlines->district);
						?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Name of Discom</label>
									<?php echo isset($disDetails[0]) ? $disDetails[0] : '-'; ?>                             
								</div>
								<div class="col-md-6">
									<label>Name of Division</label>
									<?php echo isset($disDetails[2]) ? $disDetails[2] : '-'; ?>                             
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Name of Sub Division</label>
									<?php echo isset($disDetails[3]) ? $disDetails[3] : '-'; ?>                             
								</div>
								<div class="col-md-6">
									<label>Name of power utility</label>
									<?php echo $ApplyOnlines->name_power_utility; ?>                             
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>District</label>
									<?php echo !empty($district_name) ? $district_name : ''; ?>                             
								</div>
								<div class="col-md-6">
									<label>Taluka</label>
									<?php echo $ApplyOnlines->panchayat_committee; ?>                             
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Name of Sub-station</label>
									<?php echo $ApplyOnlines->name_substation; ?>                             
								</div>
								<div class="col-md-6">
									<label>Declared capacity for Solar Power Project (MW)</label>
									<?php echo $ApplyOnlines->declare_capacity; ?>                             
								</div>
							</div>
							
						</div>
						<div class="greenbox">
							<h4> Land and Others Detail </h4>
						</div>
						 <div class="row">
							<div class="col-md-10 col-sm-8 ">
								<div class="row">
									<div class="col-md-12">
										<label>Name of Village </label>
										<?php echo $ApplyOnlines->village_name; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>Taluka</label>
										 <?php echo $ApplyOnlines->taluka; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>District</label>
										<?php   $land_district_name = $ApplyOnlinesTable->getDistrictName($ApplyOnlines->land_district);
											echo $land_district_name; ?>
									</div>
								</div> 
								<div class="row">
									<div class="col-md-12">
										<label>Proposed Solar PV Power Plant capacity (in MW)</label>
										 <?php echo $ApplyOnlines->pv_capacity; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label ">Distance between the Proposed land and Sub-station notified by the Discom</label>
										<?php echo $ApplyOnlines->distance_plant; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Options available to the applicants for installation of Solar Power Plants</label>
										 <?php echo $ApplyOnlines->option_solar; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>Installer Name</label>
										<?php echo $ApplyOnlines->installers['installer_name']; ?>
									</div>
									<div class="col-md-5">
										<label>Installer Email</label>
										 <?php echo $ApplyOnlines->installer_email; ?>
									</div>
								</div>
								<div class="row">
									
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>Installer Mobile</label>
										 <?php echo $ApplyOnlines->installer_mobile; ?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="greenbox">
							<h4> Attached Document </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php 
									$path = APPLYONLINE_KUSUM_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->aadhaar_file;
									if (!empty($ApplyOnlines->aadhaar_file) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->aadhaar_file))  {  ?>
										<div class="col-md-3">
											<label class="attach-lable">Aadhaar Card</label>
											 <a href="<?php echo URL_HTTP.'app-docs/aadhaar_file/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
											
										</div>
									<?php  }
									$path = APPLYONLINE_KUSUM_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->copy_registration;
									if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->copy_registration)) { ?>
										<div class="col-md-3">
											<label class="attach-lable">Copy of Registration</label>
											<a href="<?php echo URL_HTTP.'app-docs/copy_registration/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
										</div>
									<?php }
									$path = APPLYONLINE_KUSUM_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->authorize_letter;
									if (!empty($ApplyOnlines->authorize_letter) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->authorize_letter)) {
									?>
										<div class="col-md-3">
											<label class="attach-lable">Enclose Letter of Authorization</label>
											 <a href="<?php echo URL_HTTP.'app-docs/authorize_letter/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
										</div>
									<?php  }
									$path = APPLYONLINE_KUSUM_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->jamabandi;
									if (!empty($ApplyOnlines->jamabandi) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->jamabandi)) {
									?>
										<div class="col-md-3">
											<label class="attach-lable">Uttara 7/ Jamabandi</label>
											 <a href="<?php echo URL_HTTP.'app-docs/jamabandi/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
											
										</div>
									<?php  } ?>   
							</div>
							
						</div>
						
						<?php if( (isset($applyOnlinesDataDocList) && !empty($applyOnlinesDataDocList))){ ?>
						<div class="greenbox">
							<h4> Other Attachment </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php
									foreach ($applyOnlinesDataDocList as $key => $value) 
									{
										$path = APPLYONLINE_KUSUM_PATH.$ApplyOnlines->id.'/'.$value['file_name'];

										if (empty($value['file_name']) || !$Couchdb->documentExist($ApplyOnlines->id,$value['file_name'])) continue;
										?>
										<div Class="col-md-4">
											<label class="attach-lable">
											<?php if($value['doc_type']=='others'){
												echo $value['title'];
											} else{
												echo $value['doc_type']; 
											}?></label>
											&nbsp;&nbsp;
											 <a href="<?php echo URL_HTTP.'app-docs/'.$value['doc_type'].'-kusum/'.encode($value['id']); ?>" target="_blank"><i class="fa fa-file"></i></a>
										  
										</div>
								  <?php }  ?>
							</div>
							
							 
						</div>
						<?php } ?>
						
						<?php
						$total_amount = $ApplyOnlines->disCom_application_fee+$ApplyOnlines->jreda_processing_fee;
						if($total_amount > 0) {
						?>
						<div class="greenbox">
							<h4> Application Fee</h4>
						</div>
						<div class="form-group">
							<div class="row">
							<?php
								$col1       = '3';
								$col2       = '3';
								if($transaction_id!='')
								{
									$col1   = '2';
									$col2   = '4';
								}
							?>
								<div class="col-md-<?php echo $col1;?> hide">
									<label>Payment Mode</label>
									<?php   
										if($ApplyOnlines->payment_mode==1) { echo 'Online'; } 
										else { echo 'Offline'; } 
									?>
								</div>
								<div class="col-md-<?php echo $col2;?>">
									<label>Payment Gateway</label>
									<?php echo $ApplyOnlines->payment_gateway; ?>
								</div>
								<div class="col-md-3">
									<label>Payment Status</label>
									<?php 
									if($ApplyOnlines->payment_status == '1') 
									{
										echo '<span><h4 style="background:#71BF57;padding:3px;float:right;margin-right:90px;line-height:20px;">Paid</h4></span>';
									}
									else
									{
										echo '<span><h4 style="background:#a94442;padding:3px;float:right;margin-right:70px;line-height:20px;">Not paid</h4></span>';
									} 
									?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<?php if($transaction_id!='') { ?>
									<div class="col-md-4">
										<label>Transaction ID</label>
										<?php echo $transaction_id; ?>
									</div>
									<div class="col-md-3">
										<label>Payment Date</label>
										<?php echo $payment_date; ?>
									</div>
								<?php } ?>
							</div>
							<div class="clear-both"></div>
							<br/>
							<table class="table-responsive table-bordered"> 
								<tbody>
									<tr>
										<td>Geda Processing Fee</td>
										<td style="text-align: right;"><?php echo $ApplyOnlines->disCom_application_fee; ?></td>
									</tr>
									<tr>
										<td>GST at 18%</td>
										<td style="text-align: right;"><?php echo empty($ApplyOnlines->jreda_processing_fee) ? '0':$ApplyOnlines->jreda_processing_fee; ?></td>
									</tr>
									<tr>
										<td><b>Total Fee</b></td>
										<td style="text-align: right;"><b><?php echo $total_amount; ?></b></td>
									</tr>
								</tbody>
							</table>
						</div> 
						<?php
						}
						?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg" style="margin-left:10px">
										<?php echo $this->Html->link('Back',['controller'=>'ApplyOnlines','action' => 'applyonline_list',$page_cur]); ?>
									</span>
									<?php if (isset($is_member) && ($is_member == false) && ($ApplyOnlines->category != 3001 || ($ApplyOnlines->social_consumer == 1 && SOCIAL_SECTOR_PAYMENT==1)  || ($ApplyOnlines->govt_agency == 1 && GOVERMENT_AGENCY==1))  && $ApplyOnlines->payment_status==0  && $ApplyOnlines->application_status == $MStatus->APPROVED_FROM_GEDA && $payment_on) { ?>
										<a href="/payutransfer/make-payment/<?php echo encode($ApplyOnlines->id); ?>">
											<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg ">
												<i class="fa fa-rupee"></i> Pay Application Fee
											</span>
										</a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<?php /*
<div id="UpdateRequest" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Send Request for Update</h4>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('UpdateRequestForm',['name'=>'UpdateRequestForm','id'=>'UpdateRequestForm', 'url' => '/ApplyOnlines/AddUpdateRequest','type' => 'file']); ?>
				<div id="message_error"></div>
				<?php echo $this->Form->input('application_id',['id'=>'upload_application_id','label' => true,'type'=>'hidden','value'=>encode($ApplyOnlines->id)]); ?>
				<div class="form-group text">
				
						<div class="row">
							<lable class="col-md-4">Reason for change</lable>
							<div class="col-md-8">
									<?php echo $this->Form->input('reason', array('label' => false,'type'=>'text','div'=>false,'class'=>'form-control','placeholder'=>'Upload document','id'=>'upload_signed_file')); ?>
							</div>
						</div>
						 <div class="row">
							<lable class="col-md-12">Field to be updated </lable>
						</div>
						<div class="row">
							
								<div class="col-md-1">
									<?php echo $this->Form->input('is_name_update', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'is_name_update')); ?>
								</div>
								<label class="col-md-11">Name of the Consumer 
								</label>
							
						</div>
						<div class="row">
						   
								<div class="col-md-1">
								<?php echo $this->Form->input('is_division_details', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'project_social_consumer')); ?>
								</div> 
								<label class="col-md-11">Circle, Division and Sub-Division</label>
								
							
						</div>
						<div class="row">
							<div class="col-md-1">
								
								<?php echo $this->Form->input('is_contract_load', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'project_social_consumer')); ?>
								
							</div>
							<label class="col-md-11">Contract Load </label>
						</div>
						<div class="row">
							<lable class="col-md-4">Aadhaar Card</lable>
							<div class="col-md-8">
								<?php echo $this->Form->input('aadhar_card', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'aadhar_card')); ?>
							</div>
						</div>
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12"  id="aadhar_card-file-errors"></div>
						</div>  
						<div class="row">
							<lable class="col-md-4">Consumer Photo</lable>
							<div class="col-md-8">
								<?php echo $this->Form->input('profile_image', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'profile_image')); ?>
							</div>
						</div>
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12"  id="profile_image-file-errors"></div>
						</div>
						<div class="row">
							<lable class="col-md-4">New Electricity Bill </lable>
							<div class="col-md-8">
								<?php echo $this->Form->input('electricity_bill', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'electricity_bill')); ?>
							</div>
						</div>
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12"  id="ele_bill-file-errors"></div>
						</div>
						
				</div>
				<div class="row">
					<div class="col-md-2">
					<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary updaterequest_btn','data-form-name'=>'UpdateRequestForm']); ?>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div> */?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog2">
		<div class="modal-content">
			
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
function updateApp(id){
	var application_id = id;
	swal({
  title: "Are you sure?",
  text: "You want to update the data?",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Update it!",
  cancelButtonText: "No, Cancel plx!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {  
	$(".confirm").attr('disabled','disabled');
	$.ajax({

				type: "POST",
				url: "/ApplyOnlines/updateApiData",
				data: {'update_application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Updated!", "Your Application has been updated successfully.", "success");
						   window.location.reload();
					}
					else
					{
						swal("Fail!", result.response.response_msg, "error");
						
					}
				}
			});
	
  } else {
	swal("Cancelled", "Your Application is safe :)", "error");
  }
});
}
$(".showModel").click(function(){
	var modelheader = $(this).data("title");
	var modelUrl = $(this).data("url");
	document_window = $(window).width() - $(window).width()*0.05;
	document_height = $(window).height() - $(window).height() * 0.20;  
	modal_body = '<div class="modal-header" style="min-height: 45px;">'+
	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
	'</div>'+
	'<div class="modal-body">'+
	'<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
	'</div>';
	
	$('#myModal').find(".modal-content").html(modal_body);
	$('#myModal').modal('show');
	$('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
	$('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	return false;
});
window.closeModal = function(){ $('#myModal').modal('hide'); };

$(".updaterequest_btn").click(function() {
	$('#UpdateRequestForm').submit();
	var form = $('#UpdateRequestForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$(".updaterequest_btn").attr('disabled','disabled');
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/UploadDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#UpdateRequestForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#UpdateRequestForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".updaterequest_btn").removeAttr('disabled');
					}

				}
			});
	
});
</script>