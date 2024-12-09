<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<?php
/** Allow JERAD TO BYPASS THE DISCOM */
$JREDA_APPROVAL_ARRAY = array(  $MStatus->APPLICATION_SUBMITTED,
								$MStatus->FIELD_REPORT_SUBMITTED,
								$MStatus->WORK_STARTS,
								$FEASIBILITY_APPROVAL,
								$FUNDS_ARE_NOT_AVAILABLE,
								$FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
$ALLOWED_IPS                = array("203.88.147.186");
$IP_ADDRESS                 = (isset($this->request)?$this->request->clientIp():"");
$ShowFailResponse           = true;//(in_array($IP_ADDRESS,$ALLOWED_IPS)?true:false);
$ALLOWED_APPROVE_GEDAIDS    = ALLOW_ALL_ACCESS;
?>
<?php  $this->Html->addCrumb($pageTitle);  ?>
<style>
.pad-5
{
	padding-left: 5px !important;
}
.pad-25
{
	padding-left: 29px !important;
}
.pad-20
{
	padding-left: 24px !important;
}
.action-row .dropdown .btn {
	background: #171717;
	color: white;
	border-radius: 4px !important;
	padding: 10px;
	box-shadow: 2px 2px 2px 1px #888888;
}
.action-row .dropdown .dropdown-menu {
	margin-top: 0px;
	padding: 10px;
}
.action-row .dropdown .dropdown-menu .dropdown-item {
	display: block;
	width: 100%;
	padding: .25rem 1.5rem;
	clear: both;
	font-weight: 400;
	color: #212529;
	text-align: inherit;
	white-space: nowrap;
	background-color: transparent;
	border: 0;
	text-decoration: none;
}
</style>
<br/>
<div class="container ApplyOnline-leads">
	<?php echo $this->Form->create("form-main",['id' => 'form-main','method' => 'post','type' => 'post',"url" => "/apply-online-list"]); ?>
		<?php echo $this->Flash->render('cutom_admin'); ?>
		
		<div class="alert alert-warning">
			<strong>Notice!</strong>
			<ul>
				
				<?php
				if (BLOCK_APPLICATION == 1)
				{
				?>
					<li style="font-size: 1.0em;"><?php echo BLOCK_APPLICATION_MESSAGE;?></li>
				<?php
				}
				?>
				<?php
				if(date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-3 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-2 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-1 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3)))
				{
					?>
					<li style="font-size: 1.0em;">You can submit application till <strong><?php echo date('d-M-Y H:i:s',strtotime(DATE_STOP_1_1_3));?> PM</strong>.</li>
					<?php
				}
				?>

			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('status', $application_dropdown_status,array('label' => false,'class'=>'form-control','empty'=>'-Select status-','placeholder'=>'From Date')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('from_date', array('label' => false,'class'=>'form-control date-picker ','placeholder'=>'From Date','autocomplete'=>'off')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('to_date', array('label' => false,'class'=>'form-control date-picker','placeholder'=>'To Date','autocomplete'=>'off')); ?>
				</div>
				<?php if($member_type != $MemberTypeDiscom){ ?>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('discom_name', $discom_arr,array('label' => false,'class'=>'form-control','empty'=>'-Select Discom-','placeholder'=>'From Date')); ?>
				</div>
				<?php }?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				
				<div class="col-md-3">
					<?php echo $this->Form->input('application_search_no', array('label' => false,'class'=>'form-control','placeholder'=>'Application Number','autocomplete'=>'off')); ?>
				</div>
				<?php
				if(!empty($member_id))
				{
					?>
					<div class="col-md-3">
						<?php //echo $this->Form->input('installer_name', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Name','autocomplete'=>'off'));
						echo $this->Form->select('installer_name',$Installers,array('label' => false,'class'=>'form-control chosen-select','id'=>'installer_name','data-placeholder'=>'-Select Installers-',"multiple"=>"multiple"));?>
					</div>
					<?php
				}
				?>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('order_by_form', array('ApplyOnlines.modified|DESC'=>'Modified Date Descending','ApplyOnlines.modified|ASC'=>'Modified Date Ascending','submitted_date|DESC'=>'Submitted Date Descending','submitted_date|ASC'=>'Submitted Date Ascending'),array('label' => false,'class'=>'form-control','placeholder'=>'')); ?>
				</div>

			</div>
		</div>
	
		<div class="row">
			<div class="col-md-12">
				
				
				<div class="col-md-1">
					<?php echo $this->Form->input('Search', array('label' => false,'type'=>'submit','name'=>'Search','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Search','div'=>false)); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->Form->input('Reset', array('label' => false,'type'=>'submit','name'=>'Reset','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Reset','div'=>false)); ?>
				</div>
			</div>
		</div>
	<?php echo $this->Form->end();?>
	<div class="row">
		<?php if (!empty($ApplyOnlineLeads)) {

				?>

		<div class="col-md-6">
			<h5 style="margin: 10px;"><?=$this->Paginator->counter(['format' => 'Total Application found: {{count}} (']) ?><?php echo _FormatGroupNumberV2($TotalPvCapacity).' <span style="text-transform:none !important;">kWp</span>)';?></h5>
		</div>
		<div class="col-md-6">
			<div class="text-right">
				<ul class="pagination text-right" style="margin: 0px;">
				<?php
				$this->Paginator->options(array('url'=> array(  'controller' => 'ApplyOnlines',
																'action' => 'applyonline_list')));
				echo $this->Paginator->numbers(['before' => $this->Paginator->prev('Prev'),
												'after' => $this->Paginator->next('Next')]); ?>

				</ul>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-12">
			<?php 

			foreach($ApplyOnlineLeads as $ApplyOnlineLead):?>
			<div class="row p-row">
				<div class="p-title">
					<div class="col-md-2">
						<a href="<?php echo URL_HTTP; ?>view-applyonline-kusum/<?php echo encode($ApplyOnlineLead->id); ?>" class="name_text_size">
							<?php echo trim((!empty(trim($ApplyOnlineLead->application_type_name)) ? $ApplyOnlineLead->application_type_name : $ApplyOnlineLead->application_no)); ?>
						</a>
						<?php
						$str_append     = '';
						$approval = $MStatus->Approvalstage($ApplyOnlineLead->id);
						if($ApplyOnlineLead->query_sent=='1' && !in_array($MStatus->APPLICATION_CANCELLED,$approval)){?>
							<span class="application-status">
							<small style="font-size:12px">
								<br />
								(<?php
									if($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA && $ApplyOnlineLead->payment_status==0)
									{
										$str_append = ' - Payment Pending';
									}
									echo"Query Sent".$str_append;?>)
							</small>
						</span>
						<?php } else { ?>
						<span class="application-status">
							<small style="font-size:12px">
							<br />(<?php if(isset($application_status[$ApplyOnlineLead->application_status])) {
								$status_app_disp    = $application_status[$ApplyOnlineLead->application_status];
									$status_app_disp= str_replace(array('JREDA'), array('GEDA'), $status_app_disp);

									if($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA && $ApplyOnlineLead->payment_status==0)
									{
										$str_append = ' - Payment Pending';
									}
								echo $status_app_disp.$str_append;  } else { echo '-'; }  ?>)
							</small>
						</span>
						<?php }?>
						<?php
							$additional_capacity = (isset($ApplyOnlineLead->apply_onlines_others['is_enhancement']) && $ApplyOnlineLead->apply_onlines_others['is_enhancement']) ? 'Additional ' : '';
						?>
					</div>
					<div class="col-md-7">
						<span class="action-row action-btn">
							<?php
								$active                 = 1;
								$EnableFA               = false;
								$ApproveFA              = false;
								$ForwardToOther         = false;
								$Subsidy_Availability   = false;
								$EditApplication        = false;
								$PayApplication         = false;
								$RegistrationLink       = false;
								$MeterInstallation      = false;
								$WorkCompletionReport   = false;
								$InspectionFromCEI      = false;
								$DrawingFormOPEN        = false;
								$InspectionFormOPEN     = false;
								$InspectionFinalCEI     = false;
								$InspectionFromDisCom   = false;
								$InspectionFromJREDA    = false;
								$ReleaseSubsidy         = false;
								$ApproveDV              = false;
								$VarifyOtp              = false;
								$ShowGedaApproval_Letter= false;
								$ClaimSubsidy           = false;
								$WorkOrderDisplay       = false;
								$ExecutionDisplay       = false;
								$PaymentApproval        = false;
								$Self_Certificate       = false;
								$Download_Receipt       = false;
								$Download_Agreement     = false;
								$DownloadMeterInsallation= false;
								$DownloadSummarySheet   = false;
								$updateRequest          = false;
								$updateCapacity         = false;
								$downloadInspection     = false;
								$RemoveCommonMeter      = false;
								$DisplyEdit             = $MStatus->can_workstart($ApplyOnlineLead->application_status);
								//$ShowMeterInsallation   = $MStatus->ApprovedCEIStatus($ApplyOnlineLead->id);
								$ShowMeterInsallation   = false;
								$DispDownloadFesibility = false;
								$InspectionPDF          = false;
								if(!empty($InspectionPDF) && (in_array($member_id,$ALLOWED_APPROVE_GEDAIDS)))
								{
									$downloadInspection = true;
								}
								
								if(($ApplyOnlines->torent_ahmedabad == $ApplyOnlineLead->discom || $ApplyOnlines->torent_surat == $ApplyOnlineLead->discom))
								{
									if(!in_array($MStatus->SUBSIDY_AVAILIBILITY,$approval))
									{
										$LastFailResponse       = $ApiLogResponse->GetLatestGUVNLResponse($ApplyOnlineLead->id);
									}

								}
								elseif(!in_array($MStatus->METER_INSTALLATION,$approval))
								{
									$LastFailResponse       = $ApiLogResponse->GetLatestGUVNLResponse($ApplyOnlineLead->id);
								}
							   
								$LastFailSpinResponse   = $SpinLogResponse->GetLatestSPINResponse($ApplyOnlineLead->id);
								if(in_array($MStatus->WORK_EXECUTED,$approval))
								{
									$DownloadMeterInsallation = true;
								}
								
								
								$GetLastMessage          = $ApplyonlineMessage->GetLastMessageByApplication($ApplyOnlineLead->id,'0',$member_id);
								$applyOnlinesDataDoc= $applyOnlinesDataDocList->find("all",['conditions'=>['application_id'=>$ApplyOnlineLead->id,'doc_type'=>'Self_Certificate']])->first();
								$disp_query              = '';
								if(!empty($GetLastMessage))
								{
									$disp_query          = $GetLastMessage['message'];
								}
								$FesibilityData          = $FesibilityReport->getReportData($ApplyOnlineLead->id);
								$ChangeDiscom = true;
								if ($ApplyOnlineLead->section > 0) {
									$ChangeDiscom = false;
								}
								if(in_array($MStatus->APPROVED_FROM_GEDA,$approval) && $is_member==false)
								{
									$updateRequest  = true;
								}
								if(in_array($MStatus->APPROVED_FROM_GEDA,$approval) && $is_member==false)
								{
									$updateCapacity  = true;
								}

								if(!in_array($MStatus->APPLICATION_CANCELLED,$approval))
								{
									$ChangeDiscom = false;
									//&& $member_type == $MemberTypeDiscom
									if(isset($member_type)  && (in_array($ApplyOnlineLead->application_status,$MStatus->SHOWFESIBILITYLINK))) {
										$EnableFA           = true;
										if ($ApplyOnlineLead->subdivision <= 0) {
											$ForwardToOther  = true;
										}
									}
									if(in_array($MStatus->APPROVED_FROM_GEDA,$approval) && $str_append=='' && !$PayApplication) {
										$ShowGedaApproval_Letter           = true;
									}
									
									if(isset($is_member) && ($is_member == false)) {
										if($is_member == false && ($ApplyOnlineLead->application_status == 4 && ($ApplyOnlineLead->application_status != 5 || $ApplyOnlineLead->application_status != 6))) {
											$RegistrationLink = false;
										}
										//(!in_array($MStatus->APPROVED_FROM_GEDA,$approval))
										$AllowedEditStatus = array($APPLICATION_GENERATE_OTP,$MStatus->APPLICATION_PENDING);
										if($is_member == false && in_array($ApplyOnlineLead->application_status,$AllowedEditStatus)) {
											$EditApplication = true;

										} else if($is_member == false && empty($ApplyOnlineLead->application_status)) {
											$EditApplication = true;
										}
										if($ApplyOnlineLead->payment_status != '1'  && $ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA)
										{
											$PayApplication = true;
										}
									}
									
									
									if(isset($member_type) && $member_type == $JREDA && !in_array($MStatus->FEASIBILITY_APPROVAL,$approval))
									{
										$EditApplication = true;
									}
									else if($is_member == false && $ApplyOnlineLead->application_status == $APPLICATION_GENERATE_OTP)
									{
										$EditApplication = true;
									}
									if($is_member == false && $ApplyOnlineLead->query_sent == "1" && !in_array($MStatus->FEASIBILITY_APPROVAL,$approval) )
									{
										$EditApplication = true;
									}
									if($is_member == false && $ApplyOnlineLead->application_status == $APPLICATION_GENERATE_OTP) {
										$VarifyOtp = true;
									}
									
								}
								$arr_application_status = $MStatus->all_status_application($ApplyOnlineLead->id);
								switch ($ApplyOnlineLead->application_status) {
									case 1: {
										$active     = 1;
										break;
									}
									case 2: {
										$active = 3;
										break;
									}
									case 3:
									case 5:
									case 6:
									case 24:
									{
										$active = 4;
										break;
									}
									case 4:
									case 25:
									{
										$active = 6;
										break;
									}
									case 9:
									{
										$active = 5;
										break;
									}
									case 7:
									{
										$active = 6;
										break;
									}
									case 8:
									case 17:
									case 12:
									case 26:
									{
										$active = 7;
										break;
									}
									case 27:
									case 15:
									{
										$active = 8;
										break;
									}
									case 28:
									{
										$active = 9;
										break;
									}
									case 20:
									case 23:
									case 21:
									{
										$active = 3;
										break;
									}
								}
							?>
							<div class="col-md-4">
								<div class="dropdown">
									<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Application Actions <i class="fa fa-chevron-down"></i>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<?php if($VarifyOtp) { ?>
											<a href="javascript:;" data-toggle="modal" data-target="#Varify_Otp" class="Varify_Otp dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-check-square-o"></i> Verify OTP
											</a>
											<a href="/ApplyOnlinesKusum/resend_otp/<?php echo encode($ApplyOnlineLead->id); ?>" class="Resend_Otp dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-check-square-o"></i> Resend OTP
											</a>
										<?php } ?>
										<?php if ($ApproveDV) { ?>
											<?php
												$quota_msg = $ApplyOnlines->checked_total_capacity($ApplyOnlineLead->installer_id);
												/*if($quota_msg!==true)
												{
													?>
													<a class="dropdown-item" href="javascript:;" onclick="javascript:alert('<?php echo $quota_msg;?>');">
														<i class="fa fa-check-square-o"></i> Verify Document
													</a>
													<?php
												}
												else
												{*/
													echo $this->Form->postLink(
												'<i class="fa fa-check-square-o"></i> Verify Document',
												['action' => 'document_verify', encode($ApplyOnlineLead->id)],
												['confirm' => 'Are you sure you want to verify document for this application?','escape' => false,'class' => "dropdown-item"]);
												//}
											?>
										<?php }  ?>

										<?php if ($EditApplication) { 
												if(empty($additional_capacity))
												{
													?>
													<a class="dropdown-item" href="/apply-onlines-kusum/manage/<?php echo encode($ApplyOnlineLead->id); ?>">
														<i class="fa fa-pencil-square-o"></i> Edit
													</a>
												<?php
												}
												else
												{	?>
													<a class="dropdown-item" href="/add-additional-capacity/manage/<?php echo encode($ApplyOnlineLead->id); ?>">
														<i class="fa fa-pencil-square-o"></i> Edit
													</a>
													<?php 
												}
											}
										//|| (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == "203.88.138.46")
										?>
										<?php if ($PayApplication && ($payment_on)) { ?>
											<a class="dropdown-item" href="/payutransfer/make-payment-kusum/<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-rupee"></i> Pay Application Fee
											</a>
										<?php } ?>
										<?php if(isset($member_type) && !in_array($MStatus->APPLICATION_CANCELLED,$approval)) { ?>
											<a href="javascript:;" data-toggle="modal" data-target="#SendMessage" class="SendMessage dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-envelope" aria-hidden="true"></i> Send Message
											</a>
										<?php } ?>
										<?php if($is_member == false && $ApplyOnlineLead->query_sent=='1' && !in_array($MStatus->APPLICATION_CANCELLED,$approval)) { ?>
											<a href="javascript:;" data-toggle="modal" data-target="#ReplayMessage" class="ReplayMessage dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-envelope" aria-hidden="true"></i> Reply Message
											</a>
										<?php } ?>
										
										<?php if($ApplyOnlineLead->application_status==$MStatus->APPLICATION_PENDING) { ?>
											<a href="javascript:;" data-toggle="modal" data-target="#uploaddocument" class="uploaddocument dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upload Signed Application
											</a>
										<?php } ?>
										
										
										<?php
										if(isset($member_type) && $member_type == $JREDA && ($ApplyOnlineLead->category != $ApplyOnlines->category_residental || $ApplyOnlineLead->social_consumer==1)  && ($ApplyOnlineLead->application_status == $MStatus->APPLICATION_SUBMITTED ||
											$ApplyOnlineLead->application_status == $MStatus->REJECTED_FROM_GEDA) && in_array($member_id,$ALLOWED_APPROVE_GEDAIDS))
										{
										?>
											<a href="javascript:;" data-toggle="modal" data-target="#approvegeda" class="approvegeda dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-check-square-o" aria-hidden="true"></i> Approved GEDA
											</a>
										<?php } ?>
										<?php
										if(isset($member_type) && $member_type == $JREDA && in_array($MStatus->APPLICATION_CANCELLED,$approval))
										{
										?>
											<a href="javascript:;" data-toggle="modal" data-target="#reopenApplication" class="reopenApplication dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-check-square-o" aria-hidden="true"></i> Reopen Application
											</a>
										<?php } ?>
										<?php
										if(isset($member_type) && $member_type == $JREDA && $ApplyOnlineLead->query_sent!='1' && !in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && (empty($FesibilityData) || (isset($FesibilityData->payment_approve) && $FesibilityData->payment_approve == 0)) && !in_array($MStatus->APPLICATION_CANCELLED,$approval))
										{
										?>
											<a href="javascript:;" data-toggle="modal" data-target="#resetApplication" class="resetApplication dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-check-square-o" aria-hidden="true"></i> Reset Application
											</a>
										<?php } ?>
										<?php if($Self_Certificate) { ?>
											<a href="javascript:;" data-toggle="modal" data-target="#selfcertificate" class="selfcertificate dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-check-square-o" aria-hidden="true"></i> Self-Certification
											</a>
										<?php } ?>

										<?php if(isset($is_installer) && $is_installer == true && in_array($MStatus->APPLICATION_SUBMITTED,$approval)){?>
											<a href="javascript:;" data-toggle="modal" data-target="#otherdocument" class="otherdocument dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
												<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upload Document
											</a>
										<?php } ?>
										<?php if(isset($is_installer) && $is_installer == true && (in_array($MStatus->WAITING_LIST,$approval) || $ApplyOnlineLead->application_status==$MStatus->WAITING_LIST))
											{ ?>
											<a href="javascript:;" data-toggle="modal" onclick="removeapp('<?php echo encode($ApplyOnlineLead->id); ?>')" class="removeapp dropdown-item">
												<i class="fa fa-refresh" aria-hidden="true"></i> Re-Apply Application
											 </a>
										<?php } ?>
										<?php if(isset($is_installer) && $is_installer == true && ($ApplyOnlineLead->application_status== '' || $ApplyOnlineLead->application_status==$MStatus->APPLICATION_GENERATE_OTP || $ApplyOnlineLead->application_status==$MStatus->APPLICATION_PENDING))
											{ ?>
											<a href="javascript:;" data-toggle="modal" onclick="deleteapp('<?php echo encode($ApplyOnlineLead->id); ?>')" class="removeapp dropdown-item" >
												<i class="fa fa-trash" aria-hidden="true"></i> Delete Application
											 </a>
										<?php }
										else if(isset($member_type) && ($authority_account == 1 || in_array($member_id,$ALLOWED_APPROVE_GEDAIDS)) && CAN_DELETE_APPLICATION_MEMBER == 1)
										{
											$approvedApplication 	= $ApplicationRequestDelete->findLatestApprovedRequest($ApplyOnlineLead->id);
											if($approvedApplication == 1 && !in_array($MStatus->METER_INSTALLATION,$approval)) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#delete_application" class="delete_application dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-trash" aria-hidden="true"></i> Delete Application
												</a>
											<?php 
											}	
										}
										if(in_array($MStatus->APPLICATION_SUBMITTED,$approval) && !in_array($MStatus->METER_INSTALLATION,$approval) && !in_array($MStatus->APPLICATION_CANCELLED,$approval))
										{ 
											$approvedApplication 	= $ApplicationRequestDelete->findLatestApprovedRequest($ApplyOnlineLead->id);
											if($approvedApplication == 2 || $approvedApplication == 0) { ?>

												<a href="javascript:;" data-toggle="modal" data-target="#pre_delete_application_request" class="pre_delete_application_request dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-trash" aria-hidden="true"></i> Delete Application Request
												</a>
											
												<a href="javascript:;" data-toggle="modal" data-target="#delete_application_request" class="delete_application_request dropdown-item hide"  data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-trash" aria-hidden="true"></i> Delete Application Request
												</a>
											<?php 
											}
										} ?>
									</div>
								</div>
							</div>
							<?php if(CAN_DOWNLOAD_PDF == 1) { 
								$meterInstalledStage 	= $MStatus->getmeterInstalledStageData($ApplyOnlineLead->id);
								$downloadCorrigendum 	= false;
								if((!isset($meterInstalledStage->created) || (isset($meterInstalledStage->created) && (strtotime($meterInstalledStage->created) >= strtotime(CORRIGENDUM_LETTER_DATE)))) && in_array($MStatus->APPLICATION_SUBMITTED,$approval)) {
									$downloadCorrigendum 	= true;
								}
								?>
								<div class="col-md-5">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="downloaddropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Application Document <i class="fa fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu" aria-labelledby="downloaddropdownMenu">
											<a class="dropdown-item" href="/apply-onlines-kusum/view-application/<?php echo encode($ApplyOnlineLead->id); ?>" onclick="download_app()">
												<i class="fa fa-download"></i> Download Application
											</a>
										
											<?php if($ShowGedaApproval_Letter) { ?>
												<a href="/ApplyOnlinesKusum/geda_letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> GEDA Registration Letter
												</a>
											<?php } ?>
											<?php if($Download_Receipt) { ?>
												<a href="/ApplyOnlinesKusum/payment_receipt/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Receipt
												</a>
											<?php } ?>
											<?php if($Download_Agreement) { ?>
												<a href="/ApplyOnlinesKusum/getAgreementLetter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Agreement Letter
												</a>
											<?php } ?>
											<?php if($DownloadMeterInsallation) {?>
												<a href="/ApplyOnlinesKusum/inspection_letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Joint Inspection Letter
												</a>
											<?php } ?>
											<?php if($DispDownloadFesibility) { ?>
												<a href="/ApplyOnlinesKusum/feasibility_report/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Fesibility Report
												</a>
											<?php } ?>
											<?php if ($InspectionFromJREDA) { ?>
												<a href="/apply-onlines/geda-inspection-letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Inspection and Approval From GEDA
												</a>
											<?php } ?>
											<?php if ($DownloadSummarySheet) { ?>
												<a href="/Subsidy/getSubsidySummarySheet/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Summary Sheet
												</a>
											<?php } ?>
											<?php if ($downloadInspection) { ?>
												<a href="<?php echo $InspectionPDF;?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Inspection Report
												</a>
											<?php } ?>

										</div>
									</div>
								</div>
							<?php } ?>
							<div class="col-md-3 center">
								<span style="font-size:18px;color:#ffcc29;"><strong>
									<?php
										echo ($ApplyOnlineLead->disclaimer_subsidy==1) ? '&nbsp;Non Subsidy' : '';

									echo !empty($ApplyOnlineLead->pcr_code) ? 'PCR: '.$ApplyOnlineLead->pcr_code : '';
									?></strong>
								</span>
							</div>
						</span>
					</div>
					<div class="col-md-3">
						<span class="p-date pull-right">
							<?php
							$application_date           = $ApplyOnlineLead->created;
							if(!empty($ApplyOnlineLead->modified))
							{
								$date_data=$MStatus->find('all',array('conditions'=>array('application_id'=>$ApplyOnlineLead->id),'order'=>array('id'=>'desc')))->first();
								?>
									<!-- <?php echo 'Modified'.$ApplyOnlineLead->modified;?> -->
									<?php
								$application_date=$ApplyOnlineLead->modified;
								if(empty($ApplyOnlineLead->application_status) && empty($ApplyOnlineLead->customer_name_prefixed) && empty($ApplyOnlineLead->api_response))
								{
									$application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
								}
								if(!empty($date_data))
								{
									//echo date('Y-m-d H:i',strtotime($date_data->created))."--".date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)-23400);
									?>
									<!-- <?php echo date('Y-m-d H:i',strtotime($date_data->created)) ."--".date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800);?>-->
									<?php
									if(date('Y-m-d H:i',strtotime($date_data->created))==date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800))
									{
										?>
									<!-- <?php echo "iffff";?>-->
									<?php
										$application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
									}
									$LastGUVNLResponse       = $ApiLogResponse->GetLatestGUVNLData($ApplyOnlineLead->id);
									if(!empty($LastGUVNLResponse))
									{
										?>

										<?php
										if(date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800)==date('Y-m-d H:i',strtotime($LastGUVNLResponse->created)))
											{
												$application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
												?>
												<!-- <?php echo date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800)."--".date('Y-m-d H:i',strtotime($LastGUVNLResponse->created));?>-->
												<?php
											}
									}


								}

							 
							}
						
							?>
							<?php  echo 'Modified '.(!empty($application_date) ? date(LIST_DATE_FORMAT,strtotime($application_date)) : ''); ?>
						</span>
						<br/>
						<span class="p-date pull-right">
						<?php echo (!empty($ApplyOnlineLead->submitted_date) ? 'Submitted '.date(LIST_DATE_FORMAT,strtotime($ApplyOnlineLead->submitted_date)) : '');?>
						</span>
						<?php
						if($memberApproved == 1 && ($ApplyOnlineLead->category != $ApplyOnlines->category_residental || $ApplyOnlineLead->social_consumer==1))
						{
							$arr_members = $MStatus->getApprovedBy($ApplyOnlineLead->id);

							?>
							<span class="p-date pull-right">Verified By <?php echo isset($arr_members->member['name']) ? $arr_members->member['name'] : '-';?></span>
							<?php
						}
						?>
					</div>
				</div>
				<?php
					$Approved 		= "";
					$pv_capacity 	= (!empty($ApplyOnlineLead->pv_capacity) ? $ApplyOnlineLead->pv_capacity : '-');
					
				?>
				<div class="clear"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="col-xs-4"><?php 
						$pvCapacityText = 'DC';
						$submitedStage 	= $MStatus->getsubmittedStageData($ApplyOnlineLead->id);
						if(strtotime($ApplyOnlineLead->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)))) {
								$pvCapacityText = 'AC';
						}

						echo $additional_capacity;?>PV capacity (<?php echo $pvCapacityText;?>) to be installed (in MW)</div>
						<div class="col-xs-2 pad-5">
							<?php echo $pv_capacity; ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="col-xs-8">Application No.</div>
						<div class="col-xs-4">
							<?php echo $ApplyOnlineLead->application_no;?>
						</div>
					</div>
					<div class="col-lg-6">
						
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-6">
						<div class="col-xs-12 col-sm-8 col-lg-8">Installer</div>
						<div class="col-xs-12 col-sm-4 col-lg-4">
							<?php
								echo !empty($ApplyOnlineLead->installer_data['installer_name'])?$ApplyOnlineLead->installer_data['installer_name']:'-';
							?>
						</div>
					</div>
					<div class="col-lg-6">
						
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="col-xs-12 col-sm-8 col-lg-8">Developer</div>
						<div class="col-xs-12 col-sm-4 col-lg-4">
							<?php
								echo !empty($ApplyOnlineLead->installer_created_data['installer_name'])?$ApplyOnlineLead->installer_created_data['installer_name']:'-';
							?>
						</div>
					</div>
					<div class="col-lg-6">
						
					</div>
				</div>
				
				<?php
				if($memberApproved == 1)
				{
					?>
					
					<?php
				}
				if(!empty($additional_capacity))
				{
					?>
					<div class="row">
					<?php
						$existing_capacity = (isset($ApplyOnlineLead->apply_onlines_others['existing_capacity'])) ? $ApplyOnlineLead->apply_onlines_others['existing_capacity'] : '';
						?>
						<div class="col-lg-12 col-xs-12 col-sm-12">
							<div class="col-xs-4">Existing Capacity</div>
							<div class="col-xs-8 pad-5" >
								<?php echo $existing_capacity; ?>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div class="row">
					<?php
					$disDetails = $ApplyOnlines->getDiscomDetails($ApplyOnlineLead->circle,$ApplyOnlineLead->division,$ApplyOnlineLead->subdivision,$ApplyOnlineLead->area);
					?>
					<div class="col-lg-12 col-xs-12 col-sm-12">
						<div class="col-xs-4">Discom</div>
						<div class="col-xs-8 pad-5" >
							<?php echo $disDetails; ?>
						</div>
					</div>
				</div>
				<?php /*if (isset($LastFailResponse) && !empty($LastFailResponse) && $ShowFailResponse) { ?>
				<div class="row">
					<div class="col-lg-12 col-xs-12 col-sm-12">
						<div class="col-xs-4">Discom API Response</div>
						<div class="col-xs-8 pad-5" >
							<?php echo $LastFailResponse; ?>
						</div>
					</div>
				</div>
				<?php }*/ ?>
				
				<?php
					if (!empty($GetLastMessage)) {
						$LastMessageHtml    = "<div><span><b><u>Comment</u></b></span><br /><span>".str_replace("'","",$GetLastMessage['message'])."</span><br /><br /><span><b><u>Comment By</u></b></span><br /><span>".$GetLastMessage['comment_by']."</span><br /><br /><span><b><u>IP Address</u></b></span><br /><span>".$GetLastMessage['ip_address']."</span><br /><br /><span><b><u>Comment On</u></b></span><br /><span>".$GetLastMessage['created']."</span></div>";
						$LastMessageRender  = "<span data-toggle=\"popover\" title=\"Latest Comment\" data-html=\"true\" data-content=\"".htmlspecialchars($LastMessageHtml,ENT_QUOTES)."\"><b style=\"color:black;\">View Last Comment</b></span>";
						echo "<div class=\"row\"><div class=\"col-lg-12 col-xs-12 col-sm-12\"><div class=\"col-xs-8 col-sm-6 col-lg-6\"><a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#ViewMessage\" class=\"ViewMessage\" data-id=\"".encode($ApplyOnlineLead->id)."\"><b>View All</b></a> | ".$LastMessageRender."</div></div></div>";
					}
				?>
				<div class="row progressbar-container">
					<ul class="progressbar_guj">
					<?php
						foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) {
							$IsActive           = array_key_exists($key, $arr_application_status)?"active":"";
							if(empty($arr_application_status))
							{
								$IsActive       = ($key==$ApplyOnlineLead->application_status)?"active":"";
							}

							if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlineLead->disclaimer_subsidy==1)
							{

							}
							else
							{
								$text_apply     = '';
								$style          = '';
								if ($pv_capacity<=10 && ($key==5 || $key==7))
								{
									$text_apply = ' Self Certification';
									//$style      = "font-size:9px;";

									$value      = ($key==5) ? "Approval" : "Inspection";
								}
								if($str_append!='' && $key==2)
								{
									$IsActive   = '';
								}
								echo "<li class=\"".$IsActive."\" ><span style='".$style."'>".$value.$text_apply."</span></li>";
							}
						}
					?>
					</ul>
				</div>
			</div>
			<?php endforeach; ?>
			<?php if (!empty($ApplyOnlineLeads)) { ?>
			<!-- Paging Starts Here -->
			<div class="text-right">
				<ul class="pagination text-right">
				<?php
				echo $this->Paginator->numbers([
							'before' => $this->Paginator->prev('Prev'),
							'after' => $this->Paginator->next('Next')]); ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
	<div id="forword_popup_discom" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Forward Application </h4>
				</div>
				<div class="modal-body">
					<div id="messageBox"></div>
					<?php
					echo $this->Form->create('forward_application',['name'=>'forward_application','id'=>'forward_application']); ?>
					<div class="form-group text">
					<label>Select Division</label>
					<?php echo $this->Form->input('id',['id'=>'application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('member_assign_id',(isset($discomList)?$discomList:array()),['id'=>'discom_id',"class" =>"form-control",'label' => true,'empty'=>'-Select Division-']); ?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Forward',['type'=>'button','id'=>'login_btn_1','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="forword_popup_subdivision" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<?php
				$IsSubdivision      = false;
				if(isset($discom_details['field']))
				{
					$IsSubdivision  = ($discom_details['field'] == "subdivision")?true:false;
				}
					$class_1        = ($IsSubdivision)?"hide":"";
					$class_2        = ($IsSubdivision)?"":"hide";
					$Modal_Title    = (!$IsSubdivision)?"Assign Subdivision / Section":"Assign Section";
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo $Modal_Title;?></h4>
				</div>
				<div class="modal-body">
					<div id="assign_division_message"></div>
					<?php
					echo $this->Form->create('assign_division', ['id'=>'assign_division',
																'method'=>'post','type' => 'post'
															,'url' => '/apply-onlines/assign-division']);
					?>
					<div class="form-group text">
					<?php echo $this->Form->input('id',['id'=>'app_id','label' => false,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('division',['id'=>'division','label' => false,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('subdivision_id',['id'=>'subdivision_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('section_id',['id'=>'section_id','label' => false,'type'=>'hidden']); ?>
						<div class="col-md-12">
							<?php echo $this->Form->select('subdivision',(isset($divisionList)?$divisionList:array()),['id'=>'subdivision',"class" =>"form-control ".$class_1,'label' => false,'empty'=>'-Select Subdivision-']); ?>
						</div>
						<div class="col-md-12 <?php echo $class_1?>" style="padding-left: 40px;">
							<?php echo $this->Form->input('section_chk',['id'=>'section_chk','label' => false,'type'=>'checkbox']); ?><label for="section_chk">If you know the Section "Click Here" to assign or skip this to assign directly to Sub-division.</label>
						</div>
						<div class="col-md-12">
							<?php echo $this->Form->select('section',array(),['id'=>'section',"class" =>"form-control select-section ".$class_2,'label' => false,'empty'=>'-Select Section-']); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Assign',['type'=>'button','id'=>'assigndiscom','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="discom_status" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Subsidy Availability</h4>
				</div>
				<div class="modal-body">
					<div id="messageBox"></div>
					<?php
					echo $this->Form->create('sabsidy_availability',['name'=>'sabsidy_availability','id'=>'sabsidy_availability']); ?>
					<div class="form-group text">
					<?php echo $this->Form->input('id',['id'=>'sabsidy_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('member_assign_id',['id'=>'member_assign_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('application_status',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[$SUBSIDY_AVAILIBILITY=>'Yes, Subsidy available',$FUNDS_ARE_NOT_AVAILABLE=>'No, Funds are not available',$FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE=>'No, Funds are available but scheme is not active'],'label' => false]); ?>
					<span class="hide application_status_message"></span>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_3','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	
	<div id="SendMessage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Send Message</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('SendMessageForm',['name'=>'SendMessageForm','id'=>'SendMessageForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('appid',['id'=>'SendMessage_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->textarea('messagebox',[ "class" =>"form-control messagebox",
																	'id'=>'messagebox',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Message or Comment']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary sendmessage_btn','data-form-name'=>'SendMessageForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="delete_application" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Application</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('DeleteApplicationForm',['name'=>'DeleteApplicationForm','id'=>'DeleteApplicationForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'Delete_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control messagebox",
																	'id'=>'messagebox',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Reason']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Ok',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary deleteapplication_btn','data-form-name'=>'DeleteApplicationForm']); 
							?>
						</div>
						<div class="col-md-2">
							<?php 
						 	echo $this->Form->input('Cancel',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary ','data-dismiss'=>"modal"]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="pre_delete_application_request" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Application Request</h4>
				</div>
				<div class="modal-body">
					<?php echo $this->Form->input('application_id',['id'=>'pre_Delete_application_req_id','label' => true,'type'=>'hidden']); ?>
					Kindly use the format provided here to request for deletion. In case of any other format of letter, approval may not be provided. <a href="/Format-for-Consent-from-Consumer.docx">Consent from Consumer</a>, <a href="/Format-for-Consent-from-Installer.docx">Consent from Installer</a><br/><br/>
					<?php  echo $this->Form->input('Continue to Proceed',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary delete_application_popup','data-dismiss'=>"modal"]); ?>

				</div>
			</div>
		</div>
	</div>
	<div id="delete_application_request" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Application Request</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('DeleteApplicationRequestForm',['name'=>'DeleteApplicationRequestForm','id'=>'DeleteApplicationRequestForm','type'=>'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
						<?php echo $this->Form->textarea('reason',[ "class" =>"form-control messagebox",
																		'id'=>'messagebox',
																		'cols'=>'50','rows'=>'5',
																		'label' => false,
																		'placeholder' => 'Reason']);
						?>
					</div>
					<?php if($member_type == $MemberTypeDiscom || $is_installer == true){ ?>
					<?php echo $this->Form->input('consent_not_available',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[0=>"I have Both"],'label' => false]); ?>
					<?php } else { ?>
					<?php echo $this->Form->input('consent_not_available',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[1=>"I don't have Consumer Consent Letter",2=>"I don't have Installer Consent Letter",0=>"I have Both"],'label' => false]); ?>
					<?php }	?>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<lable class="col-md-4">Consumer Consent Letter</lable>
								<div class="col-md-8">
									<?php echo $this->Form->input('consumer_consent_letter', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'consumer_consent_letter')); ?>
									<div id="consumer_consent_letter_view"></div>
								</div>

							</div>
						</div>
					</div>
					<div class="row" style="margin-right: 2px;margin-left: -4px;">
						<div class="col-md-12"  id="consumer_consent_letter-file-errors"></div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<lable class="col-md-4">Installer Consent Letter</lable>
								<div class="col-md-8">
									<?php echo $this->Form->input('vendor_consent_letter', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'vendor_consent_letter')); ?>
									<div id="vendor_consent_letter_view"></div>
								</div>
								

							</div>
						</div>
					</div>
					<div class="row" style="margin-right: 2px;margin-left: -4px;">
						<div class="col-md-12"  id="vendor_consent_letter-file-errors"></div>
					</div>
					
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('application_id',['id'=>'Delete_application_req_id','label' => true,'type'=>'hidden']);
							 echo $this->Form->input('delete_request_id',['id'=>'delete_request_id','label' => true,'type'=>'hidden']); ?>
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary deleteapp_request_btn','data-form-name'=>'DeleteApplicationRequestForm']); 
							?>
						</div>
						<div class="col-md-2">
							<?php 
						 	echo $this->Form->input('Cancel',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary ','data-dismiss'=>"modal"]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="approvedpayment" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approve Payment</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ApprovePaymentForm',['name'=>'ApprovePaymentForm','id'=>'ApprovePaymentForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'payment_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">

										<div class="col-md-12"><label>Approve</label></div>
										<div class="col-md-12">

										   <?php echo $this->Form->select('payment_approve',array("1"=>"Yes","0"=>"No"),["class" =>"form-control payment_approve",'id'=>'payment_approve','label' => true]); ?> <br>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'approve_pay_file')); ?>
										</div>
										<div class="col-md-12" >
											<div class="" style="margin-right: 2px;margin-left: 0px;" id="approve_pay_file-file-errors"></div>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('message',[ "class" =>"form-control message",
																	'id'=>'message',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Message or Comment']);
											?>
										</div>
									</div>
								</div>
							</div> <br>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary approvepayment_btn','data-form-name'=>'ApprovePaymentForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="approvegeda" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approve GEDA Letter</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ApproveGedaForm',['name'=>'ApproveGedaForm','id'=>'ApproveGedaForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('geda_id',['id'=>'geda_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12"><label>Approve</label></div>
							<div class="col-md-12">
								<?php echo $this->Form->select('geda_approve',array("1"=>"Yes","0"=>"No"),["class" =>"form-control payment_approve",'id'=>'payment_approve','label' => true]); ?> <br>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary approvegeda_btn','data-form-name'=>'ApproveGedaForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="ViewMessage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">View Messages</h4>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
	<div id="Varify_Otp" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Verify OTP</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('VarifyOtpForm',['name'=>'VarifyOtpForm','id'=>'VarifyOtpForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('appid',['id'=>'VarifyOtp_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('otp',["class" =>"form-control",
																	'id'=>'otp',
																	'label' => false,
																	"placeholder" => "Enter OTP"
														]);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyOtpForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="ReplayMessage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reply Message</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ReplayMessageForm',['name'=>'ReplayMessageForm','id'=>'ReplayMessageForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<div id="reply_message" class="inquiry-right"></div>
					<?php echo $this->Form->input('app_id',['id'=>'ReplayMessage_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->textarea('message',[ "class" =>"form-control message",
																	'id'=>'message',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Message or Comment']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary replaymessage_btn','data-form-name'=>'ReplayMessageForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="uploaddocument" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload Signed Document</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('UploadDocumentForm',['name'=>'UploadDocumentForm','id'=>'UploadDocumentForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'upload_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'upload_signed_file')); ?>
										</div>

									</div>
								</div>
							</div>
							<div class="row" style="margin-right: 2px;margin-left: -4px;">
								<div class="col-md-12"  id="upload_signed_file-file-errors"></div>
							</div>
							 <br>
					</div>
					<div class="row">
						<?php
						/*if($quota_msg_disp!==true)
						{
						?>
							<div class="message alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><?php echo $quota_msg_disp;?>
							</div>
						<?php
						}
						else
						{*/
							?>
							<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary uploaddocument_btn','data-form-name'=>'UploadDocumentForm']); ?>
							</div>
							<?php
						//}
						?>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="removeCommonMeter" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Remove Common Meter</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('RemoveCommonForm',['name'=>'RemoveCommonForm','id'=>'RemoveCommonForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('meter_application_id',['id'=>'meter_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">	
						<div class="col-md-2">
						<?php echo $this->Form->input('Remove',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary removeCommonMeter_btn','data-form-name'=>'UploadDocumentForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="selfcertificate" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Self Certification</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('SelfCertificationForm',['name'=>'SelfCertificationForm','id'=>'SelfCertificationForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'selfcertificate_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12">
								<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'self_cert_file')); ?>
							</div>
						</div>
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12"  id="self_cert_file-file-errors"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary selfcertificate_btn','data-form-name'=>'SelfCertificationForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="reopenApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reopen Application</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ReopenForm',['name'=>'ReopenForm','id'=>'ReopenForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('reopen_application_id',['id'=>'reopen_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12"><label>Reopen</label></div>
							<div class="col-md-12">
								<?php echo $this->Form->textarea('message',
									[
										"class" =>"form-control reason",
										'id'=>'ReopenApplicationMessage',
										'cols'=>'50','rows'=>'5',
										'label' => false,
										'placeholder' => 'Comments, if any'
									]);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_reopen','label'=>false,'class'=>'btn btn-primary reopenApplication_btn','data-form-name'=>'ReopenForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="resetApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reset Application</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ResetForm',['name'=>'ResetForm','id'=>'ResetForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('reset_application_id',['id'=>'reset_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12"><label>Reset</label></div>
							<div class="col-md-12">
								<?php echo $this->Form->textarea('message',
									[
										"class" =>"form-control reason",
										'id'=>'ResetApplicationMessage',
										'cols'=>'50','rows'=>'5',
										'label' => false,
										'placeholder' => 'Comments, if any'
									]);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_reopen','label'=>false,'class'=>'btn btn-primary resetApplication_btn','data-form-name'=>'ResetForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="otherdocument" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload Other Document</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('OtherDocumentForm',['name'=>'OtherDocumentForm','id'=>'OtherDocumentForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('other_application_id',['id'=>'other_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<label>Document Type</label>
											<?php echo $this->Form->input('message',['id'=>'message','label' =>false,'type'=>'text']); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'other_docfile')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="other_docfile-file-errors"></div>
									</div>
								</div>
							</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary otherdocument_btn','data-form-name'=>'OtherDocumentForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>


</div>
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
$("#other_docfile").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#other_docfile-file-errors',
	maxFileSize: '1024',
});
$("#upload_signed_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#upload_signed_file-file-errors',
	maxFileSize: '1024',
});
$("#self_cert_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#self_cert_file-file-errors',
	maxFileSize: '1024',
});
$("#approve_pay_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#approve_pay_file-file-errors',
	maxFileSize: '1024',
});
$("#consumer_consent_letter").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#consumer_consent_letter-file-errors',
	maxFileSize: '1024',
});
$("#vendor_consent_letter").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#vendor_consent_letter-file-errors',
	maxFileSize: '1024',
});

var status_messages = <?php echo json_encode($application_status); ?>;
$('a[rel="viewView"]').click(function(){
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
$('.date-picker').datepicker({format: 'dd/M/yyyy'});
$(".forward_application").click(function(){
	var application_id = $(this).attr("data-id");
	$("#application_id").val(application_id);
	$("#forward_application select").val('');
});
$("#section_chk").change(function() {
	if ($(".select-section").hasClass("hide")) {
		$(".select-section").removeClass("hide");
	} else {
		$(".select-section").addClass("hide");
	}
});

$(".show-hide-action").click(function(){
	if ($(".action-row").hasClass("hide")) {
		$(".action-row").removeClass("hide");
	} else {
		$(".action-row").addClass("hide");
	}
});

$("#subdivision").change(function(){
	$.ajax({
			  type: "POST",
			  url: "/apply-onlines/getSubdivision",
			  data: {"division":0,"subdivision":$(this).val()},
			  success: function(response) {
				var result = $.parseJSON(response);
				$("#section").html("");
				$("#section").append($("<option />").val(0).text('-Select Section-'));
				if (result.data.section != undefined) {
					$.each(result.data.section, function(index, title) {
						$("#section").append($("<option />").val(index).text(title));
					});
				}
			  }
		});
});
$(".discom_status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#sabsidy_application_id").val(application_id);
	$('input[name="application_status"]').prop('checked', false);
});
$("#assigndiscom").click(function(){
	if ($("#subdivision").val() <= 0) {
		alert("Subdivision is required field.");
		return false;
	} else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/assign-division",
			  data: $("#assign_division").serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-danger");
					$("#assign_division_message").html(result.msg);
				} else {
					$("#assign_division_message").addClass("alert alert-success");
					$("#assign_division_message").html(result.msg);
				}
			  }
		});
	}
	return false;
});

function CallInspectionData(application_id,formname,approval_type)
{
	$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: {"appid":application_id,"approval_type":approval_type,"show-prev-report":1},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok" && result.inspection_data != "") {
					$.each(result.inspection_data, function(rowid, rowval) {
						if (rowid == 'application_status') {
							$("#"+formname).find(".application_status").val(rowval);
						} else if (rowid == 'reason') {
							$("#"+formname).find(".reason").val(rowval);
						} else {
							$("#"+formname).find(".que_"+rowid).val(rowval);
						}
					});
				}
			  }
		});
}



$(".Varify_Otp").click(function(){
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var application_id = $(this).attr("data-id");
	$("#VarifyOtp_application_id").val(application_id);
});
$(".approval_btn").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find(".application_status").val() == 2 && reason.length < 1) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("");
		$("#"+fromobj).find("#messageBox").html("Reason is required field.");
		return false;
	} else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			  }
		});
	}
	return false;
});


$(".approval_btn_cei").click(function(){
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find("#drawing_app_no").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	}
	else if($("#"+fromobj).find("#drawing_app_status").val() == '')
	{
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
		return false;
	}
	else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			  }
		});
	}
	return false;
});
$(".SendMessage").click(function(){
	var application_id = $(this).attr("data-id");
	$("#SendMessage_application_id").val(application_id);
});
$(".delete_application").click(function(){
	var application_id = $(this).attr("data-id");
	$("#Delete_application_id").val(application_id);
});
$(".delete_application_request").click(function(){
	var application_id = $(this).attr("data-id");
	//var reason = $("#"+fromobj).find(".reason").val();
	
	
	$("#Delete_application_req_id").val(application_id);
	$("#delete_request_id").val('');
	$("#consent-not-available-0").prop("checked", true);
	$("#consent-not-available-1").prop("checked", false);
	$("#consent-not-available-2").prop("checked", false);
	$("#consumer_consent_letter_view").html('');
	$("#vendor_consent_letter_view").html('');
	$("#DeleteApplicationRequestForm").find("#messagebox").val('');
	$("#delete_request_id").val('');
	$.ajax
	({
		type: "POST",
		url: "/ApplyOnlinesKusum/fetchDeleteAppRequest",
		data: {'application_id':application_id},
		success: function(response) {
			var result = $.parseJSON(response);
			if (result.type == "ok")
			{
				console.log(result.response['reject_reason']);
				if(result.response['id'] != '' && result.response['status']!=2) {
					$("#consumer_consent_letter_view").html(result.response['consumer_consent_letter']);
					$("#vendor_consent_letter_view").html(result.response['vendor_consent_letter']);
					$("#DeleteApplicationRequestForm").find("#messagebox").val(result.response['reason']);
					if(result.response['consent_not_available'] == 1) {
						$("#consent-not-available-1").prop("checked", true);
					} else if(result.response['consent_not_available'] == 2) {
						$("#consent-not-available-2").prop("checked", true);
					}
					else {
						$("#consent-not-available-0").prop("checked", true);
					}
					$("#delete_request_id").val(result.response['id']);
				}
			}
		}
	});
});
$(".pre_delete_application_request").click(function(){
	var application_id = $(this).attr("data-id");
	//var reason = $("#"+fromobj).find(".reason").val();
	
	
	$("#pre_Delete_application_req_id").val(application_id);
});
$(".ReplayMessage").click(function(){
	var applicationid = $(this).attr("data-id");
	$("#ReplayMessage_application_id").val(applicationid);
	$("#reply_message").html($("#send_application_msg_"+applicationid).html());
});
$(".uploaddocument").click(function(){
	var application_id = $(this).attr("data-id");
	$("#upload_application_id").val(application_id);
});

$(".approvegeda").click(function(){
	var application_id = $(this).attr("data-id");
	$("#geda_application_id").val(application_id);
});


$(".otherdocument").click(function(){
	var application_id = $(this).attr("data-id");
	$("#other_application_id").val(application_id);
});
$(".ViewMessage").click(function(){
	var application_id = $(this).attr("data-id");
	$.ajax({
			type: "POST",
			url: "/apply-onlines/GetMessages/"+$(this).attr("data-id"),
			success: function(response) {
				var result = $.parseJSON(response);
				if (result.html != '') {
					$("#ViewMessage").find(".modal-body").html(result.html);
				}
			}
		});
});

$(".sendmessage_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Message is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/apply-onlines/SendMessage",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#SendMessageForm").find(".messagebox").val('');
						$("#SendMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#SendMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".deleteapplication_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/RemoveApplicationMember",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#DeleteApplicationForm").find(".messagebox").val('');
						$("#DeleteApplicationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#DeleteApplicationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".deleteapp_request_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	var form = $('#'+fromobj);
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/DeleteApplicationRequest",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
						$("#"+fromobj).find(".messagebox").val('');
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						if(result.message == 'login') {
							window.location.reload();
						} else {
							$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						}
						
					}
				}
			});
	}
});

$(".delete_application_popup").click(function(){
	$("#pre_delete_application_request").modal('hide');
	$('.delete_application_request[data-id="' + $("#pre_Delete_application_req_id").val() + '"]').trigger('click');
});
$(".varifyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var otp_data = $("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/VarifyOtp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#VarifyOtpForm").find("#otp_data").val('');
						$("#VarifyOtpForm").find("#message_error").removeClass('alert-danger');
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#Varify_Otp").modal('hide');
						window.location.reload();
					} else {
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$("input[name='application_status']").change(function(){
	if($(this).val()==3) {
		$("#member_assign_id").val("<?php echo $CEI; ?>");
		// status for for application
	} else {
		$("#member_assign_id").val("<?php echo $JREDA; ?>");
	}
	$(".application_status_message").html(status_messages[parseInt($(this).val())]);
});
$(function () {
  $('[data-toggle="popover"]').popover();
})
$("#fetch_status_drawing").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var drawing_number  = $("#"+fromobj).find("#drawing_app_no_val").val();
	var app_id          = $("#"+fromobj).find("#DRAW_application_id").val();
	if ($("#"+fromobj).find("#drawing_app_no_val").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	}
	else
	{
		$.ajax
		({
			type: "POST",
			url: "/ApplyOnlinesKusum/fetch_status_api",
			data: {'drawing_number':drawing_number,'app_id':app_id,'api_type':'drawing'},
			success: function(response) {
			var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				else
				{
					$("#"+fromobj).find("#drawing_app_status_html_frm").html(result.response);
					$("#"+fromobj).find("#drawing_app_status_frm").val(result.response);
				}
			}
		});
	}

});
$("#fetch_status_cei").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var cei_number  = $("#"+fromobj).find("#cei_app_no_val").val();
	var app_id          = $("#"+fromobj).find("#CEI_form_application_id").val();
	if ($("#"+fromobj).find("#cei_app_no").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("Application Ref No. is required.");
		return false;
	}
	else
	{
		$.ajax
		({
			type: "POST",
			url: "/ApplyOnlinesKusum/fetch_status_api",
			data: {'cei_number':cei_number,'app_id':app_id,'api_type':'cei'},
			success: function(response) {
			var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				else
				{
					$("#"+fromobj).find("#cei_app_status_html_frm").html(result.response);
					$("#"+fromobj).find("#cei_app_status_frm").val(result.response);
				}
			}
		});
	}
});
$(".approval_btn_drawing").click(function(){
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find("#drawing_app_no").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	}
	else if($("#"+fromobj).find("#drawing_app_status").val() == '')
	{
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
		return false;
	}
	else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			  }
		});
	}
	return false;
});

$(".approval_btn_claim").click(function(){
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if(window.confirm('Are you sure want to claim Subsidy?'))
	{
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {

				}
				window.location.reload();
			  }
		});
	}
	return false;
});
$(".replaymessage_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".message").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Message is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/ReplayMessage",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ReplayMessageForm").find(".message").val('');
						$("#ReplayMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						window.location.reload();
					} else {
						$("#ReplayMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".uploaddocument_btn").click(function() {
	var form = $('#UploadDocumentForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$(".uploaddocument_btn").attr('disabled','disabled');
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/UploadDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#UploadDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#UploadDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".uploaddocument_btn").removeAttr('disabled');
					}

				}
			});

});

$(".approvegeda_btn").click(function() {
	var form = $('#ApproveGedaForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/ApproveGeda",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ApproveGedaForm").find(".message").val('');
						$("#ApproveGedaForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ApproveGedaForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".reopenApplication_btn").click(function() {
	var form = $('#ReopenForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/ReopenApplication",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ReopenForm").find(".message").val('');
						$("#ReopenForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ReopenForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".resetApplication_btn").click(function() {
	var form = $('#ResetForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/ResetApplication",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ResetForm").find(".message").val('');
						$("#ResetForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ResetForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".otherdocument_btn").click(function() {
	var form = $('#OtherDocumentForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/OtherDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#OtherDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#OtherDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".otherdocument_btn").removeAttr('disabled');
					}
				}
			});

});

function removeapp(id){
	var application_id = id;
	swal({
  title: "Are you sure?",
  text: "You want to Re-open the file?",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Re-open it!",
  cancelButtonText: "No, cancel plx!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
	$.ajax({
				type: "POST",
				url: "/ApplyOnlinesKusum/ResetApplication",
				data: {'reset_application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Re-opened!", "Your Application file has been reopen.", "success");
						   window.location.reload();
					}
				}

				/*url: "/ApplyOnlines/RemoveApplication",
				data: {'application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Deleted!", "Your Application file has been deleted.", "success");
						   window.location.reload();
					}
				}*/
			});

  } else {
	swal("Cancelled", "Your Application file is safe :)", "error");
  }
});
}
function deleteapp(id){
	var application_id = id;
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
				url: "/ApplyOnlinesKusum/RemoveApplication",
				data: {'application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Deleted!", "Your Application file has been deleted.", "success");
						   window.location.reload();
					}
				}
			});

  } else {
	swal("Cancelled", "Your Application file is safe :)", "error");
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

function download_app(){
	<?php
	if($is_member == false)
	{
		?>
		alert("ATTENTION : The SIGNATURE in the Application Uploaded at the time of registeration and that for all documents at the time of subsidy claim processing MUST be same.");
		<?php
	}
	?>
}
$(document).ready(function() {
	$('.chosen-select').chosen();
	$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
});
</script>
