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
	$this->Html->addCrumb('My Apply-online List','apply-online-list'); 
	$this->Html->addCrumb($pageTitle); 
	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_PATH.$ApplyOnlines->id.'/';
	}
	if($ApplyOnlines->apply_state!=4 && strtolower($ApplyOnlines->apply_state)!='gujarat')
	{
		$ApplyOnlines->jreda_processing_fee = 0;
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
					<h2 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Apply Online</strong> View </h2>
					<?php
					if($updateRequest)
					{
						/*?>
						<div class="col-md-3 next btn btn-primary btn-lg mb-xlg cbtnsendmsg" style="float:left;margin-top:20px;">
							<a href="javascript:;" data-toggle="modal" data-title="Send Request for Update" data-target="#UpdateRequest" class="UpdateRequest dropdown-item showModel" data-url="<?php echo URL_HTTP; ?>ApplyOnlines/AddUpdateRequest/<?php echo encode($ApplyOnlines->id)?>" data-id="<?php echo encode($ApplyOnlines->id); ?>">Send Request for Update
							</a>
						</div>
						<?php
						*/
					}
					else
					{
					 /*   ?>
						<div class="col-md-1 next btn btn-primary btn-lg mb-xlg cbtnsendmsg" style="float:left;margin-top:20px;"><a href="javascript:;" onclick="javascript:updateApp('<?php echo $encode_id;?>')">Update</a></div>
						<?php */
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
							<?php $active = '';
							
							$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);
							
							foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) {  
								if($key==1 && $ApplyOnlines->application_status == $key){
									$active = $key;
									break;
								} else if ($key==2 && $ApplyOnlines->application_status == $key) {
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
							<h4>Solar PV Plant Detail</h4>
						</div>
						<?php
						$additional_capacity = (isset($ApplyOnlines->apply_onlines_others['is_enhancement']) && $ApplyOnlines->apply_onlines_others['is_enhancement']) ? 'Additional ' : '';
						$existing_capacity = (isset($ApplyOnlines->apply_onlines_others['existing_capacity'])) ? $ApplyOnlines->apply_onlines_others['existing_capacity'] : '';
						?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Solar PV Capacity (<?php echo $pvCapacityText;?>) to be Installed (kW)</label>
									<?php echo $ApplyOnlines->pv_capacity; ?>                  
								</div>
								<?php if(!empty($additional_capacity))
									{
									?>
									<div class="col-md-6">
										<label>Existing DC Capacity to be Installed (kW)</label>
										 <?php echo $existing_capacity ; ?>
									</div>
									<?php
									}
								?>
								 <div class="col-md-6 hide">
									<label>Location of Proposed Rooftop Solar PV System</label>
									 <?php echo (!empty($ApplyOnlines->location_proposed)) ?$ApplyOnlines->location_proposed :"-" ; ?>
								</div> 
							</div> 
							<?php //if($newSchemeApp == 1) { ?>
								<div class="row">
									<div class="col-md-6">
										<label>Plant <?php echo $pvCapacityACText;?> Capacity to be Installed (kW)</label>
										<?php echo $applyOnlinesOthersData->pv_dc_capacity; ?>                
									</div>
									<?php if(!empty($additional_capacity))
										{
										?>
										<div class="col-md-6">
											<label>Existing AC Capacity to be Installed (kW)</label>
											<?php echo $applyOnlinesOthersData->existing_ac_capacity; ?>
										</div>
										<?php
										}
									?>
								</div> 
							<?php //} ?>
						</div> 
						<div class="greenbox">
							<h4> Installer/Channel Partner </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Installer</label>
									 <?php echo $ApplyOnlines->installer['installer_name']; ?>                             
								</div>
								<div class="col-md-6">
									<label>Contact No.</label>
									 <?php echo $ApplyOnlines->installer_mobile; ?>                             
								</div>
							</div>    
							<div class="row">
								<div class="col-md-12">
									 <?php if($ApplyOnlines->disclaimer == 1) { ?>
									<i class="fa fa-check" style="padding-top:3px;position: absolute;"></i>
									<?php } ?>
									<label class="nodots" style="padding-left:20px;">
									<?php if($ApplyOnlines->apply_state=='4' || strtolower($ApplyOnlines->apply_state)=='gujarat') { ?>
									I hereby confirm to all the Terms and Conditions of the GEDA and DisCom and of the scheme of GEDA. I also ensure that all the information in the Application will be provided to the best of my knowledge.
									<?php 
									}else{
									?>                            
									I hereby confirm to all the Terms and Conditions of the JREDA and AHA! Solar and of the scheme of JREDA. I also ensure that all the information in the Application will be provided to the best of my knowledge.
									<?php } ?>
									</label>
								</div>
							</div>
						</div>
						<div class="greenbox">
							<h4> Contact Detail </h4>
						</div>
						 <div class="row">
							<div class="col-md-10 col-sm-8 ">
								<div class="row">
									<div class="col-md-12">
										<label>Customer Name </label>
										 <?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>Mobile</label>
										 <?php echo $ApplyOnlines->mobile; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Landline No</label>
										 <?php echo $ApplyOnlines->landline_no; ?>
									</div>
								</div> 
								<div class="row">
									<div class="col-md-7">
										<label>email</label>
										 <?php echo $ApplyOnlines->email; ?>
									</div>
									<div class="col-md-5 left_space">
										<label style="padding-left: 0;" class="col-md-2">Address</label>
										<div class="col-md-10"><?php echo $ApplyOnlines->address1; ?>
										 <br/>
										 <?php echo $ApplyOnlines->address2." ".$ApplyOnlines->district; ?> 
										 </div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>City</label>
										 <?php echo $ApplyOnlines->city; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>State</label>
										 <?php echo $ApplyOnlines->state; ?>                     
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>Pincode</label>
										 <?php echo $ApplyOnlines->pincode; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Communication Address</label>
										<?php 
										if($ApplyOnlines->comunication_address_as_above == '1' || $ApplyOnlines->comunication_address == '1') 
										{
											echo "Same As Address";
										} 
										else if($ApplyOnlines->comunication_address == '0')
										{
											echo '';
										}
										else
										{
											echo $ApplyOnlines->comunication_address;
										}
										?>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-sm-4 " >
								<?php
								if(!empty($Applyonlinprofile))
								{
									$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$Applyonlinprofile['file_name'];
									if ($Couchdb->documentExist($ApplyOnlines->id,$Applyonlinprofile['file_name'])) 
									{
										?>
										<div Class="col-md-4">
											&nbsp;&nbsp;
											<img style="width:125px;" src="<?php echo URL_HTTP.'app-docs/profile/'.encode($ApplyOnlines->id); ?>"/>
										</div>
										<?php
									}
								}
								?>  
							</div>
						</div>
						<div class="greenbox">
							<h4>Bill Details</h4>
						</div>
								   <div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>DisCom Name</label>
									 <?php echo isset($discom_list[$ApplyOnlines->discom]) ? $discom_list[$ApplyOnlines->discom] : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Consumer No.</label>
									 <?php echo $ApplyOnlines->consumer_no; ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Sanctioned /Contract Load (in kW)</label>
									 <?php echo $ApplyOnlines->sanction_load_contract_demand; ?>
								</div>
								<div class="col-md-6">
									<label>Category</label>
									<?php
									if($ApplyOnlines->parameter_cats['para_value']!='' && $ApplyOnlines->parameter_cats['para_value']!='null')
									{
										echo $ApplyOnlines->parameter_cats['para_value'];
									}
									else
									{
										echo $ApplyOnlines->category;
									}
									?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<?php /*<div class="col-md-6"> 
									<label class="nodots">    
									  Are you a social sector consumer? :</label>
									  <?php if($ApplyOnlines->social_consumer == 1){ 
										 echo 'Yes';
									 } else{
										 echo 'No';
									 }?>
									 
								</div>*/?>
								<div class="col-md-6"> 
									<label class="nodots"> 
									Is the Applicant a Common Meter Connection? :</label>
									<?php 
									if($ApplyOnlines->common_meter == 1) { echo 'Yes'; } 
									else { echo 'No'; } ?>
								</div>
								<div class="col-md-6"> 
									<label class="nodots">    
									  Are you a Government Agency? :</label>
									  <?php if($ApplyOnlines->govt_agency == 1){ 
										 echo 'Yes';
									 } else{
										 echo 'No';
									 }?>
									 
								</div>
							   
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<?php
									$text_house_no      = 'House Tax Holding No';
									if($ApplyOnlines->apply_state==4 || strtolower($ApplyOnlines->apply_state)=='gujarat')
									{
										$text_house_no  = 'Premises Ownership Details No';
									}
									?>
									<label><?php echo $text_house_no;?></label>
									 <?php echo passdecrypt($ApplyOnlines->house_tax_holding_no); ?>
								</div>
								<div class="col-md-6"> 
									<label>Phase of Inverter</label>
									<?php if($ApplyOnlines->transmission_line == '1'){ 
										echo 'Single Phase';
									}
									else
									{
										echo '3 Phase';
									}
									?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6"> 
									<label class="nodots"> 
									Who will provide net meter? :</label>
									<?php 
									if($ApplyOnlines->net_meter == 1) { echo 'DisCom'; } 
									elseif($ApplyOnlines->net_meter == 2) { echo 'Installer/ EA'; }
									else { echo '-'; } ?>
								</div>
								<div class="col-md-6"> 
									<label class="nodots"> 
									Is the Applicant a MSME? :</label>
									<?php 
									if($applyOnlinesOthersData->msme == 1) { echo 'Yes'; } 
									elseif($applyOnlinesOthersData->msme === 0) { echo 'No'; }
									else { echo '-'; } ?>
								</div>
							</div>
						</div>
						<?php if ($applyOnlinesOthersData->msme == 1) {
							?>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6"> 
										<label class="nodots"> 
										Does the Applicant want to Install Solar PV more than 50% of the Contract Load? : <span style="font-weight: normal;"><?php 
										if($applyOnlinesOthersData->contract_load_more == 1) { echo 'Yes'; } 
										elseif($applyOnlinesOthersData->contract_load_more == 0) { echo 'No'; }
										else { echo '-'; } ?></span></label>
										
									</div>
									<div class="col-md-6"> 
										<label class="nodots"> 
										Category of MSME :</label>
										<?php 
										echo !empty($applyOnlinesOthersData->msme_category) ? $applyOnlinesOthersData->msme_category : '-';  ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6"> 
										<label class="nodots"> 
										Type of Applicant : <span style="font-weight: normal;"><?php 
										$othersText     = (strtolower($applyOnlinesOthersData->type_of_applicant) == 'other') ? ' - '.$applyOnlinesOthersData->applicant_others : '';
										echo (!empty($applyOnlinesOthersData->type_of_applicant)) ? $applyOnlinesOthersData->type_of_applicant.$othersText :'-';?></span></label>
										
									</div>
									<div class="col-md-6"> 
										<label class="nodots"> 
										MSME Udhyog Aadhaar No. :</label>
										<?php 
										echo !empty($applyOnlinesOthersData->msme_aadhaar_no) ? $applyOnlinesOthersData->msme_aadhaar_no : '-';  ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6"> 
										<label class="nodots"> 
										Signing Authority Type : <span style="font-weight: normal;"><?php 
										echo (!empty($applyOnlinesOthersData->type_authority)) ? $applyOnlinesOthersData->type_authority :'-';?></span></label>
										
									</div>
									<div class="col-md-6"> 
										<label class="nodots"> 
										Name of Signing Authority :</label>
										<?php 
										echo !empty($applyOnlinesOthersData->name_authority) ? $applyOnlinesOthersData->name_authority : '-';  ?>
									</div>
								</div>
							</div>
							<?php
						}
						?>
						<div class="greenbox">
							<h4> Attached Document </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php 
									$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_recent_bill;
									if (!empty($ApplyOnlines->attach_recent_bill) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_recent_bill)) 
										{         
									?>
									<div class="col-md-3">
										<label class="attach-lable">Recent Bill</label>
										 <a href="<?php echo URL_HTTP.'app-docs/attach_recent_bill/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
										
									</div>
									<?php 
										}
									?>
								<?php 
									$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_latest_receipt;
									if (!empty($ApplyOnlines->attach_latest_receipt) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_latest_receipt)) {
								?>
								<div class="col-md-3">
									<?php
									$text_house_attach      = 'House Tax Receipt';
									if($ApplyOnlines->apply_state==4 || strtolower($ApplyOnlines->apply_state)=='gujarat')
									{
										$text_house_attach  = 'Premises Ownership Document';
									}
									?>
									<label class="attach-lable"><?php echo $text_house_attach;?></label>
									 <a href="<?php echo URL_HTTP.'app-docs/attach_latest_receipt/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
								   
								</div>
								<?php 
									}
								//if(isset($is_member) && ($is_member == false || $memberViewPanAdhar==1)) {
									if($ApplyOnlines->category==3001  || strtolower($ApplyOnlines->category)=='residental' || strtolower($ApplyOnlines->category)=='residential')
									{
										$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_photo_scan_of_aadhar;
										if (!empty($ApplyOnlines->attach_photo_scan_of_aadhar) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_photo_scan_of_aadhar)) {
											//if($logged_in_id==$ApplyOnlines->customer_id)
											//{
											?>
											<div class="col-md-3">
												<label class="attach-lable">Aadhaar card</label>
												 <a href="<?php echo URL_HTTP.'app-docs/attach_photo_scan_of_aadhar/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
											   
											</div>
											<?php 
										   // }
										}
									}
									else
									{
										$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_pan_card_scan;
										if (!empty($ApplyOnlines->attach_pan_card_scan) && $Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_pan_card_scan)) {
										?>
										<div class="col-md-3">
											<label class="attach-lable">PAN card</label>
											 <a href="<?php echo URL_HTTP.'app-docs/attach_pan_card_scan/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
											
										</div>
									<?php 
										}
									}
								//}
								 if(!empty($ApplyOnlines->attach_detail_project_report)) 
								 { ?>
								<div class="col-md-3">
								   
										<label class="attach-lable">Project detail report</label>
								   
									<a href="<?php echo URL_HTTP.'app-docs/attach_detail_project_report/'.encode($ApplyOnlines->id); ?>" target="_blank">
									  <i class="fa fa-file"></i> <?php /*  <img src="/img/frontend/elegant-pdf-icon-16.png" /> */?>
									</a>
								</div> 
								<?php }
								?>
							</div>
							<div class="row">
								<div class="col-md-3">
								   <?php if(!empty($applyOnlinesOthersData->file_company_incorporation)) {
										$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->file_company_incorporation;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->file_company_incorporation)) { ?>
											<label class="attach-lable">Company Incorporation</label>
												<a href="<?php echo URL_HTTP.'app-docs/file_company_incorporation/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>   
								</div>
								<div class="col-md-3">
								   <?php if(!empty($applyOnlinesOthersData->file_board)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->file_board;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->file_board)) { ?>
											<label class="attach-lable">Copy of Board resolution</label>
												<a href="<?php echo URL_HTTP.'app-docs/file_board/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>   
								</div>
								<div class="col-md-3">
								   <?php if(!empty($applyOnlinesOthersData->upload_certificate)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->upload_certificate;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->upload_certificate)) { ?>
											<label class="attach-lable">Upload Certificate</label>
												<a href="<?php echo URL_HTTP.'app-docs/upload_certificate/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>   
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->gerc_certificate)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->gerc_certificate;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->gerc_certificate)) { ?>
											<label class="attach-lable">Copy of GERC Distribution Licensee</label>
												<a href="<?php echo URL_HTTP.'app-docs/gerc_certificate/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->rec_registration_copy)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->rec_registration_copy;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_registration_copy)) { ?>
											<label class="attach-lable">Physical copy of application</label>
												<a href="<?php echo URL_HTTP.'app-docs/rec_registration_copy/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->rec_registration_copy)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->rec_registration_copy;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_registration_copy)) { ?>
											<label class="attach-lable">Physical copy of application</label>
												<a href="<?php echo URL_HTTP.'app-docs/rec_registration_copy/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->rec_receipt_copy)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->rec_receipt_copy;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_receipt_copy)) { ?>
											<label class="attach-lable">Copy of receipt for application</label>
												<a href="<?php echo URL_HTTP.'app-docs/rec_receipt_copy/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->rec_power_evaluation)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->rec_power_evaluation;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_power_evaluation)) { ?>
											<label class="attach-lable">Power Evacuation Arrangement</label>
												<a href="<?php echo URL_HTTP.'app-docs/rec_power_evaluation/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->ppa_doc)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->ppa_doc;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->ppa_doc)) { ?>
											<label class="attach-lable">PPA Document</label>
												<a href="<?php echo URL_HTTP.'app-docs/ppa_doc/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->agreement_customer)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->agreement_customer;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->agreement_customer)) { ?>
											<label class="attach-lable">Agreement between Customer</label>
												<a href="<?php echo URL_HTTP.'app-docs/agreement_customer/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($applyOnlinesOthersData->upload_undertaking)) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$applyOnlinesOthersData->upload_undertaking;
											if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->upload_undertaking)) { ?>
											<label class="attach-lable">Undertaking</label>
												<a href="<?php echo URL_HTTP.'app-docs/app_upload_undertaking/'.encode($ApplyOnlines->id); ?>" target="_blank">
													<i class="fa fa-file"></i>
												</a>
											<?php 
											} 
										} 
									?>
								</div>
							</div>
						</div>
						 <?php if( (isset($applyOnlinesDataDocList) && !empty($applyOnlinesDataDocList)) || (isset($FeasibilityData) && !empty($FeasibilityData)) ){ ?>
						<div class="greenbox">
							<h4> Other Attachment </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php
									//if($is_member==false){
									foreach ($applyOnlinesDataDocList as $key => $value) 
									{
										$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$value['file_name'];
										if (empty($value['file_name']) || !$Couchdb->documentExist($ApplyOnlines->id,$value['file_name'])) continue;
									?>
									<div Class="col-md-4">
										<label class="attach-lable">
										<?php if($value['doc_type']=='others'){
											echo $value['title'];
										}
										else{
											echo $value['doc_type']; 
										}?></label>
										&nbsp;&nbsp;
										 <a href="<?php echo URL_HTTP.'app-docs/'.$value['doc_type'].'/'.encode($value['id']); ?>" target="_blank"><i class="fa fa-file"></i></a>
									  
									</div>
								  <?php } 
							  //}
								  ?>
							</div>
							<div class="row">
								<?php
									if(isset($FeasibilityData) && !empty($FeasibilityData) && $is_member==true )
									{
										$path = FEASIBILITY_URL.$ApplyOnlines->id.'/'.'paymentdata'.'/'.$FeasibilityData->file_name;
										if (!empty($FeasibilityData->file_name) && $Couchdb->documentExist($ApplyOnlines->id,$FeasibilityData->file_name))
										{
									?>
									<div Class="col-md-4">
										<label>Fesibility Attachment</label>
										<label class="attach-lable"><?php echo $FeasibilityData->file_name; ?></label>
										&nbsp;&nbsp;
										<a href="<?php echo URL_HTTP.'app-docs/paymentdata/'.encode($ApplyOnlines->id); ?>" target="_blank">
									  <i class="fa fa-file"></i>
										</a>
									</div>

								  <?php }
								   } ?>
							</div>
							 
						</div>
						<?php } ?>
						<div class="greenbox hide_class">
							<h4> Other Information </h4>
						</div>
						<div class="form-group hide_class">
							<?php if($member_type != $MemberTypeDiscom){ ?>
							<div class="row">
								<div class="col-md-6">
									<label>Average Monthly Units Consumed (kWh/month)</label>
									 <?php echo $ApplyOnlines->energy_con; ?>
								</div>
								<div class="col-md-6">
									<label>Average Monthly Bill (in &#8377)</label>
									 <?php echo $ApplyOnlines->bill; ?>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="form-group hide_class">
							<div class="row">
								<div class="col-md-12">
									 <label class="nodots">
									 <?php if($ApplyOnlines->tod_billing_system == 1) { ?>
									 <i class="fa fa-check"></i>
									<?php } ?>
									I, Consumer Applicant, is under ToD billing system.</label>
								</div>
							</div>
						</div>
						<div class="form-group hide_class">
							<div class="row">
								<div class="col-md-12">
									 <label class="nodots"><?php if($ApplyOnlines->avail_accelerated_depreciation_benefits == 1){ ?>
									 <i class="fa fa-check"></i>
									 <?php } ?>
									I, Consumer Applicant, or the third party owner shall avail accelerated depreciation benefits on the Rooftop Solar PV system.</label>
								</div>
							</div>
						</div>
						<?php if($applyOnlinesOthersData->rpo_rec == 1 || $applyOnlinesOthersData->rpo_rec == 2) { ?>
							<div class="greenbox">
								<h4> <?php echo ($applyOnlinesOthersData->rpo_rec == 1) ? 'RPO Compliance' : 'REC Mechanism';?></h4>
							</div>
						<?php } ?>
						<?php if($applyOnlinesOthersData->rpo_rec == 1) { ?>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label>Captive</label>
										<?php echo ($applyOnlinesOthersData->rpo_is_captive==1) ? 'Yes' : 'No';?>
									</div>
									
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Whether beneficiary is an Obligated Entity covered under RPO obligation</label>
										<?php echo ($applyOnlinesOthersData->rpo_is_obligation == 1) ? 'Yes' : 'No';?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Documents of beneficiary in support of applicant being obligated entity for RPO compliance</label>
										<div class="col-md-12">
											<label>Copy of GERC Distribution Licensee Certificate</label>
											<?php echo ($applyOnlinesOthersData->gerc_is_distribution == 1) ? 'Yes' : 'No';?>
											<?php if(!empty($applyOnlinesOthersData->gerc_certificate) && $applyOnlinesOthersData->gerc_is_distribution == 1) { ?>
											<?php if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->gerc_certificate)) { ?>
												<?php
													$file_ext = pathinfo($DOCUMENT_PATH.$applyOnlinesOthersData->gerc_certificate,PATHINFO_EXTENSION);
													 ?>
													<?php
														echo " - <strong><a target=\"_GERC\" href=\"".URL_HTTP.'app-docs/gerc_certificate/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
													?>
											<?php } ?>
										<?php } ?>
										</div>
										<div class="col-md-12">
											<label>Whether applicant has Captive Conventional Power Plant (CPP)</label>
											<?php echo ($applyOnlinesOthersData->rpo_is_cpp == 1) ? 'Yes - ' .$applyOnlinesOthersData->capacity_cpp .' kW': 'No';?>
										</div>
										<div class="col-md-12">
											<label>Any previous Solar Project put up for captive RPO</label>
											<?php echo ($applyOnlinesOthersData->rpo_is_captive_rpo == 1) ? 'Yes' : 'No';?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Certificate of STOA/ MTOA/ LTOA by SLDC/GETCO</label>
										<?php echo ($applyOnlinesOthersData->rpo_is_cert_getco == 1) ? 'Yes' : 'No';?>
										<?php if($applyOnlinesOthersData->rpo_is_cert_getco == 1) { ?>
											<div class="col-md-12">
												<label>Capacity for which Certificate of STOA/ MTOA/ LTOA issued by SLDC/GETCO </label><?php echo  $applyOnlinesOthersData->capacity_rpo_cert.' kW';?>
												
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } else if($applyOnlinesOthersData->rpo_rec == 2) { ?>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label>Physical copy of application done on online REC registration website</label>
										<?php echo ($applyOnlinesOthersData->rec_is_registration==1) ? 'Yes' : 'No';?>
										<?php if(!empty($applyOnlinesOthersData->rec_registration_copy) && $applyOnlinesOthersData->rec_is_registration == 1) { ?>
											<?php if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_registration_copy)) { ?>
												<?php
													$file_ext = pathinfo($DOCUMENT_PATH.$applyOnlinesOthersData->rec_registration_copy,PATHINFO_EXTENSION);
													 ?>
													<?php
														echo " - <strong><a target=\"_RECCOPY\" href=\"".URL_HTTP.'app-docs/rec_registration_copy/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
													?>
											<?php } ?>
										<?php } ?>
									</div>
									
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Copy of receipt for application done on online REC registration website</label>
										<?php echo ($applyOnlinesOthersData->rec_is_receipt == 1) ? 'Yes' : 'No';?>
										<?php if(!empty($applyOnlinesOthersData->rec_receipt_copy) && $applyOnlinesOthersData->rec_is_receipt == 1) { ?>
											<?php if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_receipt_copy)) { ?>
												<?php
													$file_ext = pathinfo($DOCUMENT_PATH.$applyOnlinesOthersData->rec_receipt_copy,PATHINFO_EXTENSION);
													 ?>
													<?php
														echo " - <strong><a target=\"_RECRECEIPT\" href=\"".URL_HTTP.'app-docs/rec_receipt_copy/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
													?>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Power Evacuation Arrangement permission letter from the host State Transmission Utility or the concerned Distribution Licensee, as the case may be</label>
										<?php echo ($applyOnlinesOthersData->rec_is_power_evaluation == 1) ? 'Yes' : 'No';?>
										<?php if(!empty($applyOnlinesOthersData->rec_power_evaluation) && $applyOnlinesOthersData->rec_is_power_evaluation == 1) { ?>
											<?php if ($Couchdb->documentExist($ApplyOnlines->id,$applyOnlinesOthersData->rec_power_evaluation)) { ?>
												<?php
													$file_ext = pathinfo($DOCUMENT_PATH.$applyOnlinesOthersData->rec_power_evaluation,PATHINFO_EXTENSION);
													 ?>
													<?php
														echo " - <strong><a target=\"_RECPOWER\" href=\"".URL_HTTP.'app-docs/rec_power_evaluation/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
													?>
											<?php } ?>
										<?php } ?>
										
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Installation of Solar Project shall be allowed up to Sanctioned load/ Contract demand</label>
										<?php echo ($applyOnlinesOthersData->rec_is_allowed_sancation == 1) ? 'Yes' : 'No';?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Minimum Capacity of Solar Project shall be 250 kW</label>
										<?php echo ($applyOnlinesOthersData->rec_is_valid_min_cap == 1) ? 'Yes' : 'No';?>
									</div>
								</div>
							</div>
						<?php } ?>
						
						
						<?php
						$total_amount = $ApplyOnlines->disCom_application_fee+$ApplyOnlines->jreda_processing_fee;
						if($total_amount > 0) {
						?>
						<div class="greenbox">
							<h4> Application Fee and Subsidy Detail </h4>
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
								<?php /*
								<div class="col-md-5">
									<label style="width:300px;">Bank A/C no. for disbursement of subsidy</label>
									<?php echo $ApplyOnlines->bank_ac_no; ?>
								</div> */?>
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
										<td><?php echo $ApplyOnlines->disCom_application_fee; ?></td>
									</tr>
									<tr>
										<td>GST at 18%</td>
										<td><?php echo empty($ApplyOnlines->jreda_processing_fee)?'0':$ApplyOnlines->jreda_processing_fee; ?></td>
									</tr>
									<tr>
										<td><b>Total Fee</b></td>
										<td><b><?php echo $total_amount; ?></b></td>
									</tr>
								</tbody>
							</table>
						</div> 
						<?php
						}
						?>
						<div class="greenbox">
								
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-12"> 
									<label class="nodots"> 
									 <i class="fa fa-check"></i>
									I am applying installation of solar PV in CAPEX.</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12"> 
									<label class="nodots"><?php if($ApplyOnlines->disclaimer_subsidy == 1){ ?>
										<i class="fa fa-check"></i> I don't want subsidy on the Solar PV System.
										<?php } ?>
									</label>      
								</div>
							</div>
						</div>
						<?php
						if($applyOnlinesOthersData->renewable_attr === 0 || $applyOnlinesOthersData->renewable_attr == 1)
						{
							?>
							
							<div class="form-group">
								<div class="row">
									<div class="col-md-12"> 
										<label class="nodots"> <?php /* class="fa fa-check"*/?>
											<i ></i>
											The Applicant doesn’t want to keep the Renewable Attributes of this Solar PV system.
											<?php
											if($applyOnlinesOthersData->renewable_attr == 1)
											{
												?>
												<div class="col-md-12"> 
													<label class="nodots"> 
														<?php
															echo ($applyOnlinesOthersData->renewable_attr == 1) ? '<i class="fa fa-check"></i>' : '<i class="">&nbsp;&nbsp;&nbsp;</i>';
														?>
														Yes (Type 1)
													</label>
												</div>
												<?php
											}
											if($applyOnlinesOthersData->renewable_attr === 0)
											{
												?>
												<div class="col-md-12"> 
													<label class="nodots"> 
														<?php
															echo ($applyOnlinesOthersData->renewable_attr === 0) ? '<i class="fa fa-check"></i>' : '<i class="">&nbsp;&nbsp;&nbsp;</i>';
														?>
														No, the Applicant wants to keep the Renewable Attributes (Type 2A)
													</label>
												</div>
												<?php
											}
											?>   
										</label>
									</div>
									<?php
									if($applyOnlinesOthersData->renewable_rec === 0 || $applyOnlinesOthersData->renewable_rec == 1)
									{
										//class="fa fa-check"
										?>
										<div class="col-md-12" style="margin-left: 30px;"> 

											<label class="nodots">
												<i ></i>
												Is this Application done under the Renewable Energy Certificate (REC) Scheme?
												<?php /*<div class="col-md-12"> 
													<label class="nodots"> 
														<?php
														echo ($applyOnlinesOthersData->renewable_rec == 1) ? '<i class="fa fa-check"></i>' : '<i class="">&nbsp;&nbsp;&nbsp;</i>';
														?>
														Yes
													</label>
												</div> */?>
												<div class="col-md-12"> 
													<label class="nodots"> 
														<?php
														echo ($applyOnlinesOthersData->renewable_rec ===0 ) ? '<i class="fa fa-check"></i>' : '<i class="">&nbsp;&nbsp;&nbsp;</i>';
														?>
														No, the application is to be done to meet the Renewable Purchase Obligation of the Applicant
													</label>
												</div>
											</label>      
										</div>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
						$ApproveDV      = false;
						if(isset($member_type) && $member_type == $MemberTypeDiscom && $ApplyOnlines->application_status == $MStatus->APPLICATION_SUBMITTED)
						{
							$ApproveDV  = true;
						}
						$undertakingDone 	= false;
						if(!empty($ApplyOnlines->apply_onlines_others['upload_undertaking'])) 
						{
							$undertakingDone 	= ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->apply_onlines_others['upload_undertaking'])) ? 1 : 0;
						}
						?>  
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg" style="margin-left:10px">
										<?php echo $this->Html->link('Back',['controller'=>'ApplyOnlines','action' => 'applyonline_list',$page_cur]); ?>
									</span>
									<?php if (isset($is_member) && ($is_member == false) && ($ApplyOnlines->category != 3001 || ($ApplyOnlines->social_consumer == 1 && SOCIAL_SECTOR_PAYMENT==1)  || ($ApplyOnlines->govt_agency == 1 && GOVERMENT_AGENCY==1))  && $ApplyOnlines->payment_status==0  && $ApplyOnlines->application_status == $MStatus->APPROVED_FROM_GEDA && $payment_on && $undertakingDone) { ?>
										<a href="/payutransfer/make-payment/<?php echo encode($ApplyOnlines->id); ?>">
											<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg ">
												<i class="fa fa-rupee"></i> Pay Application Fee
											</span>
										</a>
									<?php }
									if($ApproveDV)
									{
										?>
										<?php echo $this->Form->postLink(
											'Verify Document',
											['action' => 'document_verify', encode($ApplyOnlines->id)],
											['confirm' => 'Are you sure you want to verify document for this application?','escape' => false,'class' => "next btn btn-primary btn-lg mb-xlg cbtnsendmsg"]);
										?>
										<?php
									} ?>
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