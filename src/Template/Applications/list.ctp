<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">


<?php
$this->Html->addCrumb($pageTitle);
?><style>
	.pad-5 {
		padding-left: 5px !important;
	}

	.chosen-container-single {
	    width:300px !important;
	}
	.pad-25 {
		padding-left: 29px !important;
	}

	.pad-20 {
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

	.modal-full-dialog {
		width: 90%;
		height: 95%;
		padding: 0;
	}

	.ApplyOnline-leads .p-title {
		font-size: 13px !important;
	}

	.progressbar_guj li {
		list-style: none;
		display: inline-block;
		width: calc(7% - 1px);
		position: relative;
		text-align: center;
		cursor: pointer;
	}

	.progressbar_guj li:after {
	    
	    left: -36% !important;
	    
	}
	.tableborder {
		border: 1px solid black !important;
		border-color: #00a651 !important;
	}

	.fontsize {
		font-size: 11px;
	}

	.meetingRadio {
		margin-left: 50px;
	}

	.card_main {
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		/* justify-content: space-between; */
	}

	.card_inner {
		width: 23%;
		background-color: #ddd;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		border-radius: 8px !important;
		overflow: hidden;
		text-align: center;
		margin: 4px;
	}

	/* .card img {
		width: 100%;
		height: auto;
		border-top-left-radius: 8px;
		border-top-right-radius: 8px;
	} */

	.body_card {
		padding: 4px 10px 4px 10px;
	}

	.title_card {
		font-size: 1.2rem;
		font-weight: bold;
	}

	.text_card {
		color: #333;
		line-height: 1.6;
	}

	#loader-overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		/* Semi-transparent background */
		z-index: 1050;
		/* Ensure it is above other elements */
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#loader {
		text-align: center;
	}

	#loader i {
		font-size: 50px;
		color: #ff4500;
		/* Change this to your desired color */
	}

	/* .card-footer {
		background-color: #f0f0f0;
		padding: 10px;
		text-align: center;
	} */
	.card-content {
		padding: 20px;
	}

	.status-list {
		list-style-type: none;
		padding: 0;
	}

	.status-item {
		position: relative;
		padding-left: 30px;
		margin-bottom: 10px;
	}

	.status-item::before {
		content: '';
		position: absolute;
		left: 0;
		top: 3px;
		width: 15px;
		height: 15px;
		background-color: grey;
		border-radius: 50%;
	}

	.status-item.active::before {
		background-color: #ff4500;
		/* active color */
	}
</style>
<br />

<div class="container ApplyOnline-leads" style="width: 1460px;">
	<?php echo $this->Form->create("form-main", array('type' => 'post', 'id' => 'form-main', 'url' => '/applications-list')); ?>
	<?php echo $this->Flash->render('cutom_admin'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-3 form-group text">
				<?php echo $this->Form->select('application_status', $application_dropdown_status, array('label' => false, 'class' => 'form-control', 'empty' => '-Select Status-')); ?>
			</div>
			<div class="col-md-3 form-group text">
				<?php echo $this->Form->select('application_type', $applicationCategory, array('label' => false, 'class' => 'form-control', 'empty' => '-Select Category-', 'placeholder' => 'From Date')); ?>
				<?php echo $this->Form->hidden('download', array("value" => 0, "id" => "download")); ?>
			</div>
			<div class="col-md-3 form-group text">
				<?php echo $this->Form->select('application_transfer',  array('0' => 'No', '1' => 'Yes'), array('label' => false, 'class' => 'form-control', 'empty' => '-Transfer-')); ?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('application_search_no', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Application Number', 'autocomplete' => 'off')); ?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('name_of_applicant', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Name of the Applicant Company', 'autocomplete' => 'off')); ?>
			</div>
			<div class="col-md-3 form-group text">
				<?php echo $this->Form->select('payment_status', array('0' => 'Not Paid', '1' => 'Paid'), array('label' => false, 'class' => 'form-control', 'empty' => '-Select Payment Status-', 'placeholder' => '')); ?>
			</div>
			<div class="col-md-3 form-group text">
				<?php echo $this->Form->select('order_by_form', array('Applications.modified|DESC' => 'Modified Date Descending', 'Applications.modified|ASC' => 'Modified Date Ascending', 'submitted_date|DESC' => 'Submitted Date Descending', 'submitted_date|ASC' => 'Submitted Date Ascending'), array('label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
			</div>
			<div class="col-md-3">
				<?php echo $this->Form->input('receipt_no', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Payment Receipt No.', 'autocomplete' => 'off')); ?>
			</div>
			<div class="col-md-1 pull-right">
				<?php echo $this->Form->input('Reset', array('label' => false, 'type' => 'submit', 'name' => 'Reset', 'class' => 'next btn btn-primary btn-lg mb-xlg', 'value' => 'Reset', 'div' => false)); ?>
			</div>
			<div class="col-md-1 pull-right">
				<?php echo $this->Form->input('Search', array('label' => false, 'type' => 'submit', 'name' => 'Search', 'class' => 'next btn btn-primary btn-lg mb-xlg', 'value' => 'Search')); ?>
			</div>

			<?php /*<div class="col-md-1">
					<button type="button" class="btn green btn-download"><i class="fa fa-file-excel-o"></i></button>
				</div>*/ ?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
	<?php echo $this->Form->create("form-main", array('type' => 'post', 'url' => '/Applications/list')); ?>
	<div class="row">
		<div class="col-md-12">
			<h2 class="col-md-9 mb-sm mt-sm"><strong>Applications</strong></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<h5 style="margin: 10px;">
				<?= $this->Paginator->counter(['format' => 'Total Applications found: {{count}}']) ?></h5>
		</div>
		<div class="col-md-6">
			<div class="text-right">
				<ul class="pagination text-right" style="margin: 0px;">
					<?php $this->Paginator->options(array(
						'url' => array(
							'controller' => 'Applications',
							'action' => 'list'
						)
					));

					echo $this->Paginator->numbers([
						'before' => $this->Paginator->prev('Prev'),
						'after' => $this->Paginator->next('Next')
					]); ?>

				</ul>
			</div>
		</div>

		<div class="col-md-12">
			<?php

			foreach ($ApplicationsDetails as $Application) : $approval = $MStatus->Approvalstage($Application->id); ?>
				<div class="row p-row" >
					<div class="p-title row" style="margin-left: 0px;">
						<?php
						//Vishal
						$transfer_text 			= '';
						if (in_array($Application->id, $transferApplicationList)) {
							echo '<span class="label label-warning pull-right" style="margin: -15px;margin-right: 0;border-top-right-radius: 8px !important;margin-bottom: 10px;">Transfer</span>';
						}
						//Vishal
						?>

						<div class="col-md-4">
							<a href="<?php echo URL_HTTP; ?>view-applications/<?php echo encode($Application->id); ?>" class="name_text_size">
								<?php echo trim((!empty($Application->customer_name_prefixed) ? $Application->customer_name_prefixed : '') . ' ' . (!empty(trim($Application->name_of_applicant)) ? $Application->name_of_applicant : $Application->application_no)); ?>
							</a>
							<!-- <a href="javascript:;"><?php echo $Application->name_of_applicant; ?></a> -->
							<?php if ($Application->query_sent == '1' && !in_array($MStatus->APPLICATION_CANCELLED, $approval)) { ?>
								<span class="application-status">
									<small style="font-size:12px">
										<br />
										(<?php
											$str_append 		= '';
											$status_app_disp    = isset($application_status[$Application->application_status]) ? $application_status[$Application->application_status] : '';
											if ($Application->payment_status == 0) // && !in_array($Application->application_type, array(5,6))
											{
												$str_append = ' - Payment Pending';
											}

											echo $status_app_disp . " - Query Sent" . $str_append; ?>)
									</small>
								</span>
							<?php } else { ?>
								<span class="application-status">
									<small style="font-size:12px">
										<br />
										(<?php
											$str_append 		= '';
											$status_app_disp    = isset($application_status[$Application->application_status]) ? $application_status[$Application->application_status] : '';
											if ($Application->payment_status == 0) // && !in_array($Application->application_type, array(5,6))
											{
												$str_append = ' - Payment Pending';
											}
											echo $status_app_disp . $str_append; ?>)
									</small>
								</span>
							<?php } ?>
						</div>
						<div class="col-md-5">
							<span class="action-row action-btn">
								<?php
								$VarifyOtp 		= false;
								$EditApplication = false;
								$ApproveDV 		= false;
								$PayApplication = false;
								$Download_Receipt = false;
								$GetLastMessage          = $ApplicationsMessage->GetLastMessageByApplication($Application->id, '0', $member_id);
								$disp_query              = '';
								if (!empty($GetLastMessage)) {
									$disp_query          = $GetLastMessage['message'];
								}

								?>
								<?php if ($is_member == true || ($is_member == false && $installer_id != $Application->workorder_installer_id)) { ?>
									<div class="col-md-5">
										<div class="dropdown">

											<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Application Actions <i class="fa fa-chevron-down"></i>
											</button>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

												<a href="<?php echo URL_HTTP; ?>view-applications/<?php echo encode($Application->id); ?>" class="dropdown-item">
													<i class="fa fa-eye"></i> View
												</a>
												<?php
												if (($is_member == false && !in_array($MStatus->APPLICATION_SUBMITTED, $approval) && $Application->payment_status != 1) || ($is_member == true  && !empty($memberApproved))) {
													$EditApplication = true;
												}
												if ($is_member == false  && $Application->query_sent == 1) {
													$EditApplication = true;
												}
												?>
												<?php if ($EditApplication) { ?>
													<a class="dropdown-item" href="/<?php echo isset($Application->application_category['route_name']) ? $Application->application_category['route_name'] : ''; ?>/<?php echo encode($Application->id); ?>"> <i class="fa fa-pencil-square-o"></i> Edit </a>
												<?php }
												if ($is_member == false && $Application->application_status == $MStatus->APPLICATION_GENERATE_OTP) {
													$VarifyOtp 	= true;
												}
												if ($VarifyOtp) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#Varify_Otp" class="Varify_Otp dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-check-square-o"></i> Verify OTP
													</a>
													<a href="/Applications/resend_otp/<?php echo encode($Application->id); ?>" class="Resend_Otp dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-check-square-o"></i> Resend OTP
													</a>
												<?php }  ?>

												<!-- Vishal -->
												<?php

												if ((in_array($MStatus->CONNECTIVITY_STEP2, $approval) || in_array($MStatus->TFR, $approval) || in_array($MStatus->CTU1, $approval)) && $is_member == false && $customer_id == $Application->customer_id) {

													if ($Application->application_type == 2) {

														$openAccessAppList = $OpenDevPermissionApp->getOpenAccessDevPermissionList(encode($Application->id));

														if (!empty($openAccessAppList)) {
															//empty($openAccessAppList->payable_total_fee) && $openAccessAppList->payment_status == 0 && 
															if ($openAccessAppList->editable == 1) {

																echo '<a href="/open-access-permission/' . encode($Application->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Final Registration</a>';
															}
															if (!empty($openAccessAppList->payable_total_fee) && $openAccessAppList->payment_status == 0) {

																echo '<a href="/developer-permission-payment/' . encode($Application->id) . '/0/' . $Application->application_type . '" class="dropdown-item"><i class="fa fa-rupee"></i> Final Registration Payment</a>';
															}
														} else {
															echo '<a href="/open-access-permission/' . encode($Application->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Final Registration</a>';
														}
													}

													if ($Application->application_type == 3) {
														$geo_loc_exist = $WindDevPermissionApp->geoLocationAvailable(encode($Application->id));
														if (isset($geo_loc_exist) && !empty($geo_loc_exist) && $geo_loc_exist == true) {
															echo '<a href="/wind-permission/' . encode($Application->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Wind Developer Permission</a>';
														}
														$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
														if (!empty($windAppList)) {
															foreach ($windAppList as $k => $v) {
																if (!isset($v->payable_total_fee) && $v->payment_status == 0) {
																	echo '<a href="/wind-permission/' . encode($Application->id) . '/' . encode($v->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Wind Developer Permission - ' . $v->app_order . '</a>';
																}
																if (isset($v->payable_total_fee) && $v->payment_status == 0) {
																	echo '<a href="/developer-permission-payment/' . encode($Application->id) . '/' . encode($v->id) . '/' . $Application->application_type . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Wind Developer Permission Payment - ' . $v->app_order . '</a>';
																}
															}
														}
													}

													if ($Application->application_type == 4) {
														$geo_loc_exist = $WindDevPermissionApp->geoLocationAvailable(encode($Application->id));
														if (isset($geo_loc_exist) && !empty($geo_loc_exist) && $geo_loc_exist == true) {
															echo '<a href="/hybrid-permission/' . encode($Application->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Hybrid Developer Permission</a>';
														}
														$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
														if (!empty($windAppList)) {
															foreach ($windAppList as $k => $v) {
																if (!isset($v->payable_total_fee) && $v->payment_status == 0) {
																	echo '<a href="/hybrid-permission/' . encode($Application->id) . '/' . encode($v->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Hybrid Developer Permission - ' . $v->app_order . '</a>';
																}
																if (isset($v->payable_total_fee) && $v->payment_status == 0) {
																	echo '<a href="/developer-permission-payment/' . encode($Application->id) . '/' . encode($v->id) . '/' . $Application->application_type . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Hybrid Developer Permission Payment - ' . $v->app_order . '</a>';
																}
															}
														}
													}
												}
												if ($Application->application_type == 2 && isset($member_id)) {
													
													$openAccessAppList = $OpenDevPermissionApp->getOpenAccessDevPermissionList(encode($Application->id));
													if (!empty($openAccessAppList) && isset($openAccessAppList)) {
														
														$checkMemberApproval = $developerApplicationQuery->checkMemberApproval(encode($Application->id), encode($openAccessAppList->id), 2, $member_id);
														if ($openAccessAppList->payment_status == 1 && $openAccessAppList->status == 0 && $checkMemberApproval == 1) {
															echo '<a data-toggle="modal"  class="open_access_permission dropdown-item" data-id="' . encode($Application->id) . '" dev-app-id="' . encode($openAccessAppList->id) . '" app-type=2><i class="fa fa-check-square-o"></i> Approve Final Registration</a>';
														}

														if ($openAccessAppList->status == 1) {
															echo '<a data-toggle="modal"  class="wind_dp_letter dropdown-item" data-id="' . encode($openAccessAppList->application_id) . '" dev-app-id="' . encode($openAccessAppList->id) . '" app-type=2><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Generate DP Letter</a>';
														}
														$generatedDPLetter = $WindDevPermissionApp->checkDpLetter(encode($openAccessAppList->application_id), encode($openAccessAppList->id));

														if ($openAccessAppList->status == 1 && !isset($openAccessAppList->final_registration_letter) && $generatedDPLetter == 1) {
															echo '<a data-toggle="modal"  class="upload_dp_letter dropdown-item" data-id="' . encode($openAccessAppList->application_id) . '" dev-app-id="' . encode($openAccessAppList->id) . '" app-type=2><i class="fa fa-upload" aria-hidden="true"></i> Upload Signed Final Registration Letter</a>';
														}
													}

												}

												if ($Application->application_type == 3 && isset($member_id)) {
													$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
													if (!empty($windAppList) && isset($windAppList)) {
														foreach ($windAppList as $k => $v) {
															$checkMemberApproval = $developerApplicationQuery->checkMemberApproval(encode($v->application_id), encode($v->id), 3, $member_id);
															if ($v->payment_status == 1 && $v->status == 0 && $checkMemberApproval == 1) {
																echo '<a data-toggle="modal"  class="open_access_permission dropdown-item" data-id="' . encode($v->application_id) . '" dev-app-id="' . encode($v->id) . '" app-type=3><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve Developer Permission - ' . $v->app_order . '</a>';
															}
															$generatedDPLetter = $WindDevPermissionApp->checkDpLetter(encode($Application->id), encode($v->id));
															if ($v->status == 1 && $generatedDPLetter == 0) {
																echo '<a data-toggle="modal"  class="wind_dp_letter dropdown-item" data-id="' . encode($v->application_id) . '" dev-app-id="' . encode($v->id) . '" app-type=3><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Generate DP Letter - ' . $v->app_order . '</a>';
															}
															$checkUploadedDPLetter = $WindDevPermissionApp->checkUploadedDPLetter(encode($Application->id), encode($v->id));
															if ($v->status == 1 && isset($member_id) && $generatedDPLetter == 1 && $checkUploadedDPLetter==0) {
																echo '<a data-toggle="modal"  class="upload_dp_letter dropdown-item" data-id="' . encode($v->application_id) . '" dev-app-id="' . encode($v->id) . '" app-type=3><i class="fa fa-upload" aria-hidden="true"></i> Upload Signed DP Letter - ' . $v->app_order . '</a>';
															}
														}
													}
												}

												if ($Application->application_type == 4 && isset($member_id)) {
													$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
													if (!empty($windAppList) && isset($windAppList)) {
														foreach ($windAppList as $k => $v) {
															$checkMemberApproval = $developerApplicationQuery->checkMemberApproval(encode($v->application_id), encode($v->id), 4, $member_id);
															if ($v->payment_status == 1 && $v->status == 0 && $checkMemberApproval == 1) {
																echo '<a data-toggle="modal"  class="open_access_permission dropdown-item" data-id="' . encode($v->application_id) . '" dev-app-id="' . encode($v->id) . '" app-type=4><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve Developer Permission - ' . $v->app_order . '</a>';
															}
															$generatedDPLetter = $WindDevPermissionApp->checkDpLetter(encode($Application->id), encode($v->id));
															if ($v->status == 1 && $generatedDPLetter == 0) {
																echo '<a data-toggle="modal"  class="wind_dp_letter dropdown-item" data-id="' . encode($v->application_id) . '" dev-app-id="' . encode($v->id) . '" app-type=4><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Generate DP Letter - ' . $v->app_order . '</a>';
															}
															$checkUploadedDPLetter = $WindDevPermissionApp->checkUploadedDPLetter(encode($Application->id), encode($v->id));															
															if ($v->status == 1 && isset($member_id) && $generatedDPLetter == 1 && $checkUploadedDPLetter==0) {
																echo '<a data-toggle="modal"  class="upload_dp_letter dropdown-item" data-id="' . encode($v->application_id) . '" dev-app-id="' . encode($v->id) . '" app-type=3><i class="fa fa-upload" aria-hidden="true"></i> Upload Signed DP Letter - ' . $v->app_order . '</a>';
															}
														}
													}
												}
												
												//transfer
												if ($is_member == true ||  $customer_id == $Application->customer_id) {
													// $checkDPTransfer = $WindDevPermissionApp->checkDPTransfer(encode($Application->id));
													// if ($checkDPTransfer == 1) {
													// 	echo '<a data-toggle="modal"  class="transfer_dp dropdown-item" data-id="' . encode($Application->id) . '"  app-type=3><i class="fa fa-exchange" aria-hidden="true"></i> Transfer WTG</a>';
													// }
												}
												if (in_array($Application->id, $transferApplicationList) && $customer_id != $Application->customer_id && $is_member == false) {
													
													// if ($Application->application_type == 3 || $Application->application_type == 4) {
													// 	$tran_geo_loc_exist = $TransferDevPermissionApp->geoLocationAvailable(encode($Application->id),$customer_id);
													// 		if (isset($tran_geo_loc_exist) && !empty($tran_geo_loc_exist) && $tran_geo_loc_exist == true) {
													// 			echo '<a href="/transfer-developer-permission/' . encode($Application->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Transfer Developer Permission</a>';
													// 		}
													// 	$windTransferAppList = $TransferDevPermissionApp->getTransferDevPermissionList(encode($Application->id),$customer_id);

													// 	if (!empty($windTransferAppList)) {
													// 		foreach ($windTransferAppList as $tk => $tv) {
													// 			if (!isset($tv->payable_total_fee) && $tv->payment_status == 0) {
													// 				echo '<a href="/transfer-developer-permission/' . encode($Application->id) . '/' . encode($tv->id) . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Transfer Developer Permission - ' . $tv->app_order . '</a>';
													// 			}
													// 			if (isset($tv->payable_total_fee) && $tv->payment_status == 0) {
													// 				echo '<a href="/transfer-developer-permission-payment/' . encode($Application->id) . '/' . encode($tv->id) . '/' . $Application->application_type . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Transfer Developer Permission Payment - ' . $tv->app_order . '</a>';
													// 			}																
													// 		}
													// 	}
													// }
												}
												if (($Application->application_type == 3 || $Application->application_type == 4)&& isset($member_id)) {
													// $windTransferAppList = $TransferDevPermissionApp->getTransferDevPermissionList(encode($Application->id));
													// //$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
													// if (!empty($windTransferAppList) && isset($windTransferAppList)) {
														
													// 	foreach ($windTransferAppList as $wtk => $wtv) {
													// 		$checkMemberApproval = $TransferDeveloperApplicationQuery->checkMemberApproval(encode($wtv->application_id), encode($wtv->id), 3, $member_id);
													// 		if ($wtv->payment_status == 1 && $wtv->status == 0 && $checkMemberApproval == 1) {
													// 			echo '<a data-toggle="modal"  class="transfer_permission dropdown-item" data-id="' . encode($wtv->application_id) . '" tran-dev-app-id="' . encode($wtv->id) . '" app-type='.$Application->application_type.'><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve Transfer Developer Permission - ' . $wtv->app_order . '</a>';
													// 		}
													// 		$generatedTPLetter = $TransferDevPermissionApp->checkTpLetter(encode($Application->id), encode($wtv->id));

													// 		if ($wtv->status == 1 && $generatedTPLetter == 0) {
													// 			echo '<a data-toggle="modal"  class="wind_tp_letter dropdown-item" data-id="' . encode($wtv->application_id) . '" tran-dev-app-id="' . encode($wtv->id) . '" app-type='.$Application->application_type.'><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Generate TP Letter - ' . $wtv->app_order . '</a>';
													// 		}
													// 		$checkUploadedTPLetter = $TransferDevPermissionApp->checkUploadedTPLetter(encode($Application->id), encode($wtv->id));
													// 		if ($wtv->status == 1 && isset($member_id) && $generatedDPLetter == 1 && $checkUploadedTPLetter==0) {
													// 			echo '<a data-toggle="modal"  class="upload_tp_letter dropdown-item" data-id="' . encode($wtv->application_id) . '" tran-dev-app-id="' . encode($wtv->id) . '" app-type='.$Application->application_type.'><i class="fa fa-upload" aria-hidden="true"></i> Upload Signed TP Letter - ' . $wtv->app_order . '</a>';
													// 		}
													// 	}
													// }
												}
												

												?>
												<!-- end -->
												<?php if (isset($is_installer) && $is_installer == true && in_array($MStatus->APPLICATION_SUBMITTED, $approval)) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#otherdocument" class="otherdocument dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upload Document
													</a>
												<?php } ?>
												<?php if ($Application->application_type != 5) { ?>
													<?php if ($Application->injection_level == 1 || $Application->injection_level == 2) { ?>
													<!-- in_array($APPROVED_FROM_GEDA, $approval)&& && $Application->application_status == $APPROVED_FROM_GEDA-->
														<?php if ( in_array($MStatus->APPLICATION_SUBMITTED, $approval)  && !in_array($MStatus->TFR, $approval)  && $Application->grid_connectivity == 1) { ?>
															<?php if ($Application->discom == 15 || $Application->discom == 16 || $Application->discom == 17 && is_numeric($Application->getco_substation)) { ?>
																<a href="javascript:;" data-toggle="modal" data-target="#TP" class="TP dropdown-item" data-id="<?php echo encode($Application->id); ?>" 
																><i class="fa fa-pencil-square-o" aria-hidden="true"></i> TFR
																</a>
															<?php } else if(is_numeric($Application->getco_substation) == false){?>
																<a href="javascript:;" data-toggle="modal" data-target="#Substation" class="Substation dropdown-item" data-id="<?php echo encode($Application->id); ?>" >
																	<i class="fa fa-pencil-square-o" aria-hidden="true"></i>Update Getco Substation
																</a>
															<?php } else { ?>
															<a href="javascript:;" data-toggle="modal" data-target="#STUstep1" class="STUstep1 dropdown-item" data-id="<?php echo encode($Application->id); ?>">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> TFR
															</a>
															<?php } ?>
														<?php } ?>
													<?php } else { ?>
														<!-- in_array($APPROVED_FROM_GEDA, $approval) && $Application->application_status == $APPROVED_FROM_GEDA-->
														<?php if (in_array($MStatus->APPLICATION_SUBMITTED, $approval) && $Application->grid_connectivity == 1 && !in_array($MStatus->STU, $approval) && !in_array($MStatus->CONNECTIVITY_STEP1, $approval ) && is_numeric($Application->getco_substation)) { ?>
															<a href="javascript:;" data-toggle="modal" data-target="#STUstep1" class="STUstep1 dropdown-item" data-id="<?php echo encode($Application->id); ?>" data-prod-id="<?php echo ($Application->discom); ?>">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> STU Connectivity
															</a>
														<?php } else if(is_numeric($Application->getco_substation) == false){?>
															<a href="javascript:;" data-toggle="modal" data-target="#Substation" class="Substation dropdown-item" data-id="<?php echo encode($Application->id); ?>" >
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i>Update Getco Substation
															</a>
														<?php }	?>
														<?php if (in_array($CONNECTIVITY_STEP1, $approval) && $Application->application_status == $CONNECTIVITY_STEP1 && $Application->grid_connectivity == 1) { ?>
															<a href="javascript:;" data-toggle="modal" data-target="#STUstep2" class="STUstep2 dropdown-item" data-id="<?php echo encode($Application->id); ?>">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Stage 2 : Connectivity
															</a>
														<?php } ?>
													<?php } ?>

													<?php if (in_array($APPROVED_FROM_GEDA, $approval) && $Application->application_status == $APPROVED_FROM_GEDA && $Application->grid_connectivity == 2) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#CTUstep1" class="CTUstep1 dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> CTU Connectivity Stage
														</a>
													<?php } ?>
													<?php if (isset($is_installer) && $is_installer == true && in_array($CTU1, $approval) && $Application->application_status == $CTU1 && $Application->grid_connectivity == 2) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#CTUstep2" class="CTUstep2 dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> CTU Connectivity Stage 2
														</a>
													<?php } ?>
												<?php } ?>
												<!-- <a href="javascript:;" data-toggle="modal" data-target="#Geo_Cordinate" class="Geo_Cordinate dropdown-item" data-id="<?php echo encode($Application->id); ?>">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add Geo Cordinate
												</a>  -->
												<!-- $member_type == $SEEGEOLOCATION &&   empty($member_type) && -->
												<?php if ((in_array($MStatus->TFR, $approval) || in_array($MStatus->STU, $approval) || in_array($MStatus->CONNECTIVITY_STEP1, $approval) || in_array($MStatus->CTU1, $approval)) && $is_member == false && ($Application->application_type != 2)) { ?>
													<a href="<?php echo URL_HTTP; ?>applications_geo_location/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> WTG Coordinate Verification
													</a>
													<!-- $member_type == $SEEGEOLOCATION && -->
												<?php } else if ((in_array($MStatus->TFR, $approval) || in_array($MStatus->STU, $approval) || in_array($MStatus->CONNECTIVITY_STEP1, $approval) || in_array($MStatus->CTU1, $approval)) && $is_member == true && ($Application->application_type != 2)) { ?>
													<a href="<?php echo URL_HTTP; ?>applications_geo_location/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> WTG Coordinate Verification
													</a>
													<a href="<?php echo URL_HTTP; ?>applications_wtg_delete/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> Delete WTG Locations
													</a>
												<?php } ?>

												<?php if ((in_array($MStatus->TFR, $approval) || in_array($MStatus->STU, $approval) || in_array($MStatus->CONNECTIVITY_STEP1, $approval) || in_array($MStatus->CTU1, $approval)) && ($Application->application_type != 2)) { ?>
													<a href="<?php echo URL_HTTP; ?>applications_wtg_shifting/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> Shifting of WTG Coordinate
													</a>
													<a href="<?php echo URL_HTTP; ?>applications_wtg_modify_make/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> Modify Make
													</a>
													<a href="<?php echo URL_HTTP; ?>applications_wtg_delete/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> Delete WTG Locations
													</a>
												<?php } else if ($member_type == $SEEGEOLOCATION && (in_array($MStatus->TFR, $approval) || in_array($MStatus->CONNECTIVITY_STEP2, $approval) || in_array($MStatus->CTU1, $approval)) && ($Application->application_type != 2)) { ?>
													<a href="<?php echo URL_HTTP; ?>applications_wtg_shifting/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> Shifting of WTG Coordinate
													</a>
												<?php } ?>
												<!-- (!in_array($MStatus->DRAWING_APPLIED,$approval)) && -->
												<?php if ((in_array($MStatus->TFR, $approval) || in_array($MStatus->STU, $approval) || in_array($MStatus->CONNECTIVITY_STEP2, $approval) || in_array($MStatus->CTU2, $approval))  && !in_array($MStatus->DRAWING_APPLIED, $approval)) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#DRAWING_Status" class="DRAWING_Status dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-check-square-o"></i>CEI Drawing Application Ref. No.
													</a>
												<?php } ?>
												<?php if ((in_array($MStatus->TFR, $approval) || in_array($MStatus->STU, $approval) || in_array($MStatus->CONNECTIVITY_STEP2, $approval) || in_array($MStatus->CTU2, $approval))  && !in_array($MStatus->DRAWING_APPLIED, $approval)&& ($Application->application_type != 2)) { ?>
												
													<a href="<?php echo URL_HTTP; ?>applications_cei_drawing/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> CEI Drawing all
													</a>
												<?php } ?>
												<?php //if (in_array($MStatus->DRAWING_APPLIED,$approval)) { 
												?>
												<!-- <a href="javascript:;" data-toggle="modal" data-target="#Work_Execution" class="Work_Execution dropdown-item" data-id="
												<?php // echo encode($Application->id); 
																																											?>">
													<i class="fa fa-check-square-o"></i> Work Execution
												</a> -->
												<?php // } 
												?>
												<?php if (in_array($MStatus->DRAWING_APPLIED, $approval) && !in_array($MStatus->CEI_INSPECTION_APPROVED, $approval) ) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#CEI_APP_Status_POPUP" class="CEI_APP_Status_POPUP dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-check-square-o"></i> CEI/CEA Permission
													</a>
													
												<?php } ?>
												<?php if (in_array($MStatus->DRAWING_APPLIED, $approval) && !in_array($MStatus->CEI_INSPECTION_APPROVED, $approval) && ($Application->application_type != 2)) { ?>
												<a href="<?php echo URL_HTTP; ?>applications_cei_inspection/<?php echo encode($Application->id); ?>" class="dropdown-item">
														<i class="fa fa-eye"></i> CEI/CEA Permission all
													</a>

												<?php } ?>

												<?php if (in_array($MStatus->CEI_INSPECTION_APPROVED, $approval) && !in_array($MStatus->BPTA, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#BPTA" class="BPTA dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> BPTA & LTOA/MTOA/STOA
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_bpta/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> BPTA & LTOA/MTOA/STOA All
															</a>
														<?php }?>
												<?php } ?>
												<?php if (isset($member_type) && !empty($member_type) &&  in_array($MStatus->BPTA, $approval) && !in_array($MStatus->BPTA_APPROVED, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#BPTAApproval" class="BPTAApproval dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i>BPTA & LTOA/MTOA/STOA Approval
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_bpta_approval/<?php echo encode($Application->id); ?>" class="dropdown-item">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i>BPTA & LTOA/MTOA/STOA Approval All
														</a>
														<?php }?>
												<?php } ?>

												<?php if (!isset($member_type) && empty($member_type) && in_array($MStatus->BPTA_APPROVED, $approval) && !in_array($MStatus->WHELLING, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#WHEELING" class="WHEELING dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> WHEELING Agreement
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_wheeling/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> WHEELING Agreement All
															</a>
														<?php }?>
												<?php } ?>
												<?php if (isset($member_type) && !empty($member_type) &&  in_array($MStatus->WHELLING, $approval) && !in_array($MStatus->WHELLING_APPROVED, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#WHEELINGApproval" class="WHEELINGApproval dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> WHEELING Agreement Approval
														</a>

														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_wheeling_approval/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> WHEELING Agreement Approval All
															</a>
														<?php }?>
												<?php } ?>

												<?php if (!isset($member_type) && empty($member_type) && in_array($MStatus->WHELLING_APPROVED, $approval) && !in_array($MStatus->METER_SEALING, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#METER_SEALING" class="METER_SEALING dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Meter Sealing Report
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_meter/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Meter Sealing Report All
															</a>
														<?php }?>
												<?php } ?>
												<?php if (isset($member_type) && !empty($member_type) &&  in_array($MStatus->METER_SEALING, $approval) && !in_array($MStatus->METER_SEALING_APPROVED, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#METER_SEALINGApproval" class="METER_SEALINGApproval dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Meter Sealing Report Approval
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_meter_approval/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Meter Sealing Report Approval All
															</a>
														<?php }?>
												<?php } ?>

												<?php if (!isset($member_type) && empty($member_type) && in_array($MStatus->METER_SEALING, $approval) && !in_array($MStatus->POWER_INJECTION, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#POWER_INJECTION" class="POWER_INJECTION dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Declaration of Power Injection
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_power_injection/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Declaration of Power Injection All
															</a>
														<?php }?>
												<?php } ?>
												<?php if (isset($member_type) && !empty($member_type) &&  in_array($MStatus->POWER_INJECTION, $approval) && !in_array($MStatus->POWER_INJECTION_APPROVED, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#POWER_INJECTIONApproval" class="POWER_INJECTIONApproval dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i>  Power Injection Approval
														</a>
														<?php if($Application->application_type != 2){?>
															<a href="<?php echo URL_HTTP; ?>applications_power_injection_approval/<?php echo encode($Application->id); ?>" class="dropdown-item">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Power Injection Approval All
															</a>
														<?php }?>
												<?php } ?>

												<?php if (in_array($MStatus->POWER_INJECTION_APPROVED, $approval) && !in_array($MStatus->INTIMATION_FOR_COMPLETION, $approval)) { ?>

													<a href="javascript:;" data-toggle="modal" data-target="#" class="completeintimation dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-check-square-o" aria-hidden="true"></i> Intimation for Completion
													</a>
													<?php if($Application->application_type != 2){?>
														<a href="<?php echo URL_HTTP; ?>applications_intimation/<?php echo encode($Application->id); ?>" class="dropdown-item">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Intimation for Completion All
														</a>
													<?php }?>
												<?php } ?>
												<?php if (isset($member_type) && !empty($member_type) &&  in_array($MStatus->INTIMATION_FOR_COMPLETION, $approval) && !in_array($MStatus->PROJECT_COMMISSIONING, $approval)) { ?>

														<a href="javascript:;" data-toggle="modal" data-target="#ProjectCommissioning" class="ProjectCommissioning dropdown-item" data-id="<?php echo encode($Application->id);  ?>">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Project Commmissioning   
														</a>
													<?php if($Application->application_type != 2){?>
														<a href="<?php echo URL_HTTP; ?>applications_project_commissioning/<?php echo encode($Application->id); ?>" class="dropdown-item">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Project Commmissioning All
														</a>
													<?php }?>
												<?php } ?>


												<?php /*if (in_array($MStatus->WHELLING_APPROVED, $approval) &&  !in_array($MStatus->SLDC, $approval) ) { ?>

		completeintimation										<a href="javascript:;" data-toggle="modal" data-target="#SLDC" class="SLDC dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> SLDC Application
														</a>
												<?php } ?>

												<?php if (isset($member_type) && !empty($member_type) &&  in_array($MStatus->SLDC, $approval) && !in_array($MStatus->SLDC_APPROVED, $approval)) { ?>

												<a href="javascript:;" data-toggle="modal" data-target="#SLDCApproval" class="SLDCApproval dropdown-item" data-id="<?php echo encode($Application->id); ?>">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> SLDC Approval
														</a>
												<?php } */?>
												
												<?php
												if ($Application->application_status == $MStatus->APPLICATION_PENDING && $Application->payment_status == '1') {  //  || (($Application->payment_status != '1') && in_array($Application->application_type, array(5,6))) 
												?>
													<a href="javascript:;" data-toggle="modal" data-target="#uploaddocument" class="uploaddocument dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upload Signed Application
													</a>
												<?php } ?>
												<?php
												if (isset($member_type) && !empty($member_type) && $Application->payment_status == 1 && !empty($memberApproved) && !in_array($MStatus->DOCUMENT_VERIFIED, $approval)) {
													$ApproveDV = true;
												}
												if ($ApproveDV) {
												?>
													<?php
													if ($Application->grid_connectivity == 1) {
														$connectivity =  isset($EndSTU[$Application->application_end_use_electricity['application_end_use_electricity']]) ? $EndSTU[$Application->application_end_use_electricity['application_end_use_electricity']] : '';
													} elseif ($Application->grid_connectivity == 2) {
														$connectivity =  isset($EndCTU[$Application->application_end_use_electricity['application_end_use_electricity']]) ? $EndCTU[$Application->application_end_use_electricity['application_end_use_electricity']] : '';
													}

													?>
													<a class="dropdown-item" href="javascript:;" onclick="javascript:document_verify_click('<?php echo encode($Application->id); ?>');" id="application_<?php echo encode($Application->id); ?>" data-district="<?php echo $Application->district_master['name']; ?>" data-taluka="<?php echo $Application->taluka; ?>" data-village="<?php echo $Application->city; ?>" data-category="<?php echo $Application->application_category['category_name']; ?>" data-route="<?php echo $Application->application_category['route_name']; ?>" data-substation="<?php echo $Application->getco_substation; ?>" data-connectivity="<?php echo $connectivity; ?>">
														<i class="fa fa-check-square-o"></i> Verify Document
													</a>
												<?php
												}
												if ($Application->payment_status != '1' && in_array($Application->application_status, array($MStatus->APPLICATION_PENDING, $MStatus->APPROVED_FROM_GEDA, $MStatus->APPLICATION_SUBMITTED)) && $is_member == false) //&& !in_array($Application->application_type,array(5,6))
												{
													$PayApplication = true;
												}
												?>
												<?php if ($PayApplication) { ?>
													<a class="dropdown-item" href="/RePayment/make-payment/<?php echo encode($Application->id); ?>">
														<i class="fa fa-rupee"></i> Pay Application Fee
													</a>
												<?php } ?>
												<?php if (isset($member_type) && !in_array($MStatus->APPLICATION_CANCELLED, $approval)) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#SendMessage" class="SendMessage dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-envelope" aria-hidden="true"></i> Send Message
													</a>
												<?php } ?>
												<?php if ($is_member == false && $Application->query_sent == '1' && !in_array($MStatus->APPLICATION_CANCELLED, $approval)) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#ReplayMessage" class="ReplayMessage dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-envelope" aria-hidden="true"></i> Reply Message
													</a>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php } ?>
								<div class="col-md-5">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="downloaddropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Document <i class="fa fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu" aria-labelledby="downloaddropdownMenu">
											<a class="dropdown-item" href="/Applications/downloadpdf/<?php echo encode($Application->id); ?>" onclick="download_app()">
												<i class="fa fa-download"></i> Download Application
											</a>
											<!-- <a class="dropdown-item" href="/GeoApplications/downloadGEOLetterPdf/<?php echo encode($Application->id); ?>" target="_blank">
															<i class="fa fa-download"></i> Geo Letter
											</a> -->
											<?php
											if (in_array($MStatus->DOCUMENT_VERIFIED, $approval) && $Application->payment_status == 1) {
												if ($is_member == false && empty($Application->discom)) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#mapDiscom" class="mapDiscom dropdown-item" data-id="<?php echo encode($Application->id); ?>">
														<i class="fa fa-download"></i> <?php echo ($Application->application_type == 5) ? 'GEDA Letter' : 'Provisional Letter'; ?>
													</a>
												<?php } else { ?>
													<a class="dropdown-item" href="/Applications/downloadRegistrationPdf/<?php echo encode($Application->id); ?>" target="_blank">
														<i class="fa fa-download"></i> <?php echo ($Application->application_type == 5) ? 'GEDA Letter' : 'Provisional Letter'; ?>
													</a>
												<?php } ?>

											<?php } ?>
											<?php if ($Application->payment_status == 1) {
												$Download_Receipt   = true;
											}
											?>
											<?php if ($Download_Receipt) { ?>
												<a href="/Applications/downloadReApplicationPdf/<?php echo encode($Application->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Receipt
												</a>
											<?php } ?>
											<?php if (isset($Application->e_invoice_url) && !empty($Application->e_invoice_url)) { ?>
												<a href="<?php echo $Application->e_invoice_url; ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download E-invoice
												</a>
											<?php } ?>
											<!-- Vishal -->

											<?php
											if (in_array($MStatus->CONNECTIVITY_STEP2, $approval) || in_array($MStatus->TFR, $approval) || in_array($MStatus->CTU1, $approval)) {
												$openAccessApp = $OpenDevPermissionApp->getOpenAccessDevPermissionList(encode($Application->id));
												if ($Application->application_type == 2) {
													if (!empty($openAccessApp) && $openAccessApp->payment_status == 1) {

														echo '<a href="/download-developer-permission-payment-receipt/' . encode($openAccessApp->id) . '/' . $openAccessApp->application_type . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Download Final Registration Receipt</a>';

														echo '<a href="/ApplicationDeveloperPermission/open_access_form_pdf/' . encode($openAccessApp->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Download Final Registration Form</a>';

														if ($openAccessApp->status == 1 && isset($member_id)) {
															echo '<a href="/ApplicationDeveloperPermission/open_access_letter/' . encode($openAccessApp->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Download Final Registration Letter</a>';
														}

														if ($openAccessAppList->status == 1 && isset($openAccessAppList->final_registration_letter) && !isset($member_id)) {
															echo "<a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_final_registration_letter/' . encode($openAccessAppList->id) . "\" class='dropdown-item'><i class='fa fa-download'></i> Download Final Registration Letter</a>";
														}
													}
												}
												if ($Application->application_type == 3 || $Application->application_type == 4) {
													$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
													if (!empty($windAppList)) {

														foreach ($windAppList as $k => $v) {
															if ($Application->application_type == 3 ){
																echo '<a href="/ApplicationDeveloperPermission/wind_form_pdf/' . encode($v->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Developer Permission Application - ' . $v->app_order . '</a>';
															}
															if ($Application->application_type == 4){
																echo '<a href="/ApplicationDeveloperPermission/hybrid_form_pdf/' . encode($v->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Developer Permission Application - ' . $v->app_order . '</a>';
															}
															if ($v->payment_status == 1) {
																echo '<a href="/download-developer-permission-payment-receipt/' . encode($v->id) . '/' . $v->application_type . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Download Developer Permission Receipt - ' . $v->app_order . '</a>';
															}
															$generatedDPLetter = $WindDevPermissionApp->checkDpLetter(encode($Application->id), encode($v->id));

															if ($v->status == 1 && isset($member_id) && $generatedDPLetter == 1) {
																echo '<a href="/ApplicationDeveloperPermission/wind_hybrid_letter/' . encode($v->id) . '/' . encode($Application->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Download Developer Permission Letter - ' . $v->app_order . '</a>';
															}
															$uploadedDPLetter = $WindDevPermissionApp->checkUploadedDPLetter(encode($Application->id), encode($v->id));
															if ($v->status == 1 && !isset($member_id) && $generatedDPLetter == 1 && $uploadedDPLetter == 1) {
																echo "<a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_signed_dp_letter/' . encode($v->id) . "\" class='dropdown-item'><i class='fa fa-download'></i> Download Developer Permission Letter - " . $v->app_order . "</a>";
															}
														}
													}
												}
												
											}
											if (in_array($Application->id, $transferApplicationList) && $customer_id != $Application->customer_id ) {
												if ($Application->application_type == 3 || $Application->application_type == 4) {
													
													// $windTransferAppList = $TransferDevPermissionApp->getTransferDevPermissionList(encode($Application->id),$customer_id);

													// if (!empty($windTransferAppList)) {
													// 	$Url = ($Application->application_type == 3)? '/TransferDeveloperPermission/transfer_wind_form_pdf/' : '/TransferDeveloperPermission/transfer_hybrid_form_pdf/';
													// 	foreach ($windTransferAppList as $tk => $tv) {
													// 		echo '<a href="'.$Url.encode($tv->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Transfer Developer Permission Application - ' . $tv->app_order . '</a>';
													// 		if ($v->payment_status == 1) {
													// 			echo '<a href="/download-transfer-developer-permission-payment-receipt/' . encode($tv->id) . '/' . $tv->application_type . '" class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Download Transfer Developer Permission Receipt - ' . $tv->app_order . '</a>';
													// 		}
													// 		$generatedTPLetter = $TransferDevPermissionApp->checkTpLetter(encode($Application->id), encode($tv->id));
															
													// 		if ($tv->status == 1 && isset($member_id) && $generatedTPLetter == 1) {
													// 			echo '<a href="/TransferDeveloperPermission/wind_hybrid_letter/' . encode($tv->id) . '/' . encode($Application->id) . '" target="_blank" class="dropdown-item"><i class="fa fa-download"></i> Download Transfer Developer Permission Letter - ' . $tv->app_order . '</a>';
													// 		}
													// 		$uploadedTPLetter = $TransferDevPermissionApp->checkUploadedTPLetter(encode($Application->id), encode($tv->id));
													// 		if ($tv->status == 1 && !isset($member_id) && $generatedTPLetter == 1 && $uploadedTPLetter == 1) {
													// 			echo "<a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/t_signed_tp_letter/' . encode($tv->id) . "\" class='dropdown-item'><i class='fa fa-download'></i> Download Transfer Developer Permission Letter - " . $tv->app_order . "</a>";
													// 		}															
													// 	}
													// }
												}																								
											}
											?>
											<!--  -->
										</div>
									</div>
								</div>
							</span>
						</div>
						<div class="col-md-3" style="text-align: right;">

							<span class="p-date " style="font-size:18px;color:<?php echo $Application->application_category['color_code']; ?>"><strong>
									<?php
									$category_text 			= '';
									if (isset($Application->application_category['category_name'])) {
										if ($Application->kusum_type == 1) {
											$category_text 	= 'KUSUM A';
										} elseif ($Application->kusum_type == 2) {
											$category_text 	= 'KUSUM C';
										} else {
											$category_text 	= $Application->application_category['category_name'];
										}
									}

									echo  $category_text ?></strong>
							</span><br>
							<?php echo 'Modified ' . (!empty($Application->modified) ? date(LIST_DATE_FORMAT, strtotime($Application->modified)) : ''); ?><br>
							<?php
							$submitedStage 	= $MStatus->getsubmittedStageData($Application->id);
							if (isset($submitedStage->created) && !empty($submitedStage->created)) {
								echo 'Submitted ' . (!empty($submitedStage->created) ? date(LIST_DATE_FORMAT, strtotime($submitedStage->created)) : '');
							} ?>
						</div>
					</div>
					<div class="clear"></div>
					<div class="row" style="margin-top: -10px;margin-bottom: 0px;">
						<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
							<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Application No.</div>
							<div class="col-xs-5 col-sm-5 col-lg-5 col-md-5">
								<?php echo (isset($Application->application_no) ? $Application->application_no : ''); ?>
							</div>
							<div class="p-title col-xs-4 col-sm-4 col-lg-4 col-md-4" style="text-align: right;padding-right: 0px;">
								<?php
								$provisonalDate 	= $MStatus->getgedaletterStageData($Application->id);
								if (isset($provisonalDate->created) && !empty($provisonalDate->created)) {
									echo 'Provisional Letter ' . (!empty($provisonalDate->created) ? date(LIST_DATE_FORMAT, strtotime($provisonalDate->created)) : '');
								} ?>
							</div>
						</div>
					</div>
					<?php if ($is_member == true) { ?>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Developer Name </div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->developers['installer_name']) ? $Application->developers['installer_name'] : '') ?>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ($Application->application_type == 2) { ?>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Project Capacity AC (in MW) </div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->pv_capacity_ac) ? $Application->pv_capacity_ac : '') ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Project Capacity DC (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->pv_capacity_dc) ? $Application->pv_capacity_dc : '') ?>
								</div>
							</div>
						</div>
					<?php } elseif ($Application->application_type == 3) { ?>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Nos. of WTG </div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->wtg_no) ? $Application->wtg_no : '') ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Capacity of each WTG (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->capacity_wtg) ? $Application->capacity_wtg : '') ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Total Capacity (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->total_capacity) ? $Application->total_capacity : '') ?>
								</div>
							</div>
						</div>
					<?php } elseif ($Application->application_type == 4) { ?>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Project Capacity AC (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo isset($Application->total_wind_hybrid_capacity) ? $Application->total_wind_hybrid_capacity : ''; ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Project Capacity DC (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->capacity_wtg) && !empty($Application->wtg_no)) ? (($Application->wtg_no * $Application->capacity_wtg)) : ''; ///1000 
									?>
								</div>
							</div>
						</div>
					<?php } elseif ($Application->application_type == 5) { ?>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Project Capacity AC (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo isset($Application->inverter_hybrid_capacity) ? $Application->inverter_hybrid_capacity : ''; ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Project Capacity DC (in MW)</div>
								<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
									<?php echo (!empty($Application->module_hybrid_capacity)) ? (($Application->module_hybrid_capacity)) : ''; ///1000 
									?>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
							<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3"> Grid Connectivity</div>
							<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">
								<?php echo isset($gridLevel[$Application->grid_connectivity]) ? $gridLevel[$Application->grid_connectivity] : ''; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
							<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Power Injection level</div>
							<div class="col-xs-9 col-sm-9 col-lg-9 col-md-9">

								<?php if ($Application->grid_connectivity == 1) {
									if ($Application->injection_level == 1) {
										echo "Below 11 KV";
									} else if ($Application->injection_level == 2) {
										echo "11 KV";
									} else if ($Application->injection_level == 3) {
										echo "66 KV";
									} else if ($Application->injection_level == 4) {
										echo "Above 66 KV";
									}
								} ?>
								<?php if ($Application->grid_connectivity == 2) {
									echo isset($Application->injection_level_ctu) ? $Application->injection_level_ctu : '';
								} ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
							<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">Discom</div>
							<div class="col-xs-4 col-sm-4 col-lg-4 col-md-4">
								<?php
								echo $Application->branch_masters['title'];
								?>
							</div>
							<div class="col-xs-2 col-sm-2 col-lg-2 col-md-2">Substation</div>
							<div class="col-xs-3 col-sm-3 col-lg-3 col-md-3">
								<?php
								echo $Application->getco_substation;
								?>
							</div>
						</div>
					</div>
					<?php
					if (!empty($GetLastMessage)) {
						$LastMessageHtml    = "<div><span><b><u>Comment</u></b></span><br /><span>" . str_replace("'", "", $GetLastMessage['message']) . "</span><br /><br /><span><b><u>Comment By</u></b></span><br /><span>" . $GetLastMessage['comment_by'] . "</span><br /><br /><span><b><u>IP Address</u></b></span><br /><span>" . $GetLastMessage['ip_address'] . "</span><br /><br /><span><b><u>Comment On</u></b></span><br /><span>" . $GetLastMessage['created'] . "</span></div>";
						$LastMessageRender  = "<span data-toggle=\"popover\" title=\"Latest Comment\" data-html=\"true\" data-content=\"" . htmlspecialchars($LastMessageHtml, ENT_QUOTES) . "\"><b style=\"color:black;\">View Last Comment</b></span>";
						echo "<div class=\"row\"><div class=\"col-lg-12 col-xs-12 col-sm-12\"><div class=\"col-xs-12 col-sm-12 col-lg-12\"><a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#ViewMessage\" class=\"ViewMessage\" data-id=\"" . encode($Application->id) . "\"><b>View All</b></a> | " . $LastMessageRender . '</div></div></div>';
					}
					?>
					<div class="row progressbar-container">
						<ul class="progressbar_guj">
							<?php
							if ($Application->application_type == 5) {
								$arr_application_status = $MStatus->all_status_application($Application->id);
								foreach ($MStatus->apply_online_main_status_kusum as $key => $value) {

									$IsActive           = array_key_exists($key, $arr_application_status) ? "active" : "";
									if (empty($arr_application_status)) {
										$IsActive       = ($key == $Application->application_status) ? "active" : "";
									}

									$text_apply     = '';
									$style          = '';

									if ($str_append != '' && $key == 2) {
										$IsActive   = '';
									}
									echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
								}
							} else if ($Application->injection_level == 1 || $Application->injection_level == 2) {
								$arr_application_status = $MStatus->all_status_application($Application->id);

								if ($Application->application_type == 4 || $Application->application_type == 3) {
									$APPLY_ONLINE_MAIN_STATUS_TP = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '11' => 'TFR', '15' => 'WTG Co-Verification', '7' => 'Developer Permission', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission',  '16' => 'BPTA','9' => 'Wheeling Agreement','17' => 'Meter Sealing Report','18' => 'Power Injection', '10' => 'Project Commissioning');
								} else {
									$APPLY_ONLINE_MAIN_STATUS_TP = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '11' => 'TFR', '7' => 'Final Registration', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission','16' => 'BPTA', '9' => 'Wheeling Agreement','17' => 'Meter Sealing Report','18' => 'Power Injection',  '10' => 'Project Commissioning');
								}
								//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS_TP as $key => $value) {

									$IsActive           = array_key_exists($key, $arr_application_status) ? "active" : "";
									if (empty($arr_application_status)) {
										$IsActive       = ($key == $Application->application_status) ? "active" : "";
									}

									$text_apply     = '';
									$style          = '';

									if ($str_append != '' && $key == 2) {
										$IsActive   = '';
									}
									if ($key != '7') {
										echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
									} else {
										if ($value == 'Developer Permission') {
											$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
											$strTitle = '';
											if (!empty($windAppList)) {
												foreach ($windAppList as $k => $v) {
													$activeTitle = $v->status == 1 ? "active" : "";

													$strTitle = $strTitle . '<div class="status-item ' . $activeTitle . '"> Developer Permission - ' . $v->app_order . '</div>';
												}
											}
											$LastMessageHtml = '
														<div class="card">
															<div class="card-content">
																<div class="status-list">
																	' . $strTitle . '
																</div>
															</div>											
														</div>';
											echo "<li data-placement=\"bottom\" class=\"" . htmlspecialchars($IsActive, ENT_QUOTES) . "\" data-toggle=\"popover\" data-html=\"true\" data-content=\"" . htmlspecialchars($LastMessageHtml, ENT_QUOTES) . "\">
														<span style='" . htmlspecialchars($style, ENT_QUOTES) . "'>" . htmlspecialchars($value, ENT_QUOTES) . htmlspecialchars($text_apply, ENT_QUOTES) . "</span>
													</li>";
										} else {
											echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
										}
									}
								}
							} elseif (isset($Application->application_connectivity_step['connectivity_type']) && $Application->application_connectivity_step['connectivity_type'] == 1 && $Application->grid_connectivity == 1) {

								$arr_application_status = $MStatus->all_status_application($Application->id);
								if ($Application->application_type == 4 || $Application->application_type == 3) {
									$APPLY_ONLINE_MAIN_STATUS_STU = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '13' => 'STU', '15' => 'WTG Co-Verification', '7' => 'Developer Permission', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission', '16' => 'BPTA', '9' => 'Wheeling Agreement','17' => 'Meter Sealing Report','18' => 'Power Injection', '10' => 'Project Commissioning');
								} else {
									$APPLY_ONLINE_MAIN_STATUS_STU = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '13' => 'STU', '7' => 'Final Registration', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission', '16' => 'BPTA', '9' => 'Wheeling Agreement','17' => 'Meter Sealing Report','18' => 'Power Injection', '10' => 'Project Commissioning');
								}
								//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS_STU as $key => $value) {

									$IsActive           = array_key_exists($key, $arr_application_status) ? "active" : "";
									if (empty($arr_application_status)) {
										$IsActive       = ($key == $Application->application_status) ? "active" : "";
									}

									$text_apply     = '';
									$style          = '';

									if ($str_append != '' && $key == 2) {
										$IsActive   = '';
									}
									if ($key != '7') {
										echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
									} else {
										if ($value == 'Developer Permission') {
											$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
											$strTitle = '';
											if (!empty($windAppList)) {
												foreach ($windAppList as $k => $v) {
													$activeTitle = $v->status == 1 ? "active" : "";

													$strTitle = $strTitle . '<div class="status-item ' . $activeTitle . '"> Developer Permission - ' . $v->app_order . '</div>';
												}
											}
											$LastMessageHtml = '
														<div class="card">
															<div class="card-content">
																<div class="status-list">
																	' . $strTitle . '
																</div>
															</div>											
														</div>';
											echo "<li data-placement=\"bottom\" class=\"" . htmlspecialchars($IsActive, ENT_QUOTES) . "\" data-toggle=\"popover\" data-html=\"true\" data-content=\"" . htmlspecialchars($LastMessageHtml, ENT_QUOTES) . "\">
														<span style='" . htmlspecialchars($style, ENT_QUOTES) . "'>" . htmlspecialchars($value, ENT_QUOTES) . htmlspecialchars($text_apply, ENT_QUOTES) . "</span>
													</li>";
										} else {
											echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
										}
									}
									//echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
								}
							} elseif (isset($Application->grid_connectivity) && $Application->grid_connectivity == 2) {
								$arr_application_status = $MStatus->all_status_application($Application->id);
								if ($Application->application_type == 4 || $Application->application_type == 3) {
									$APPLY_ONLINE_MAIN_STATUS_CTU = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '12' => 'CTU - In Principal', '15' => 'WTG Co-Verification', '14' => 'CTU - Final Principal', '7' => 'Developer Permission', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission','16' => 'BPTA', '9' => 'Wheeling Agreement','17' => 'Meter Sealing Report','18' => 'Power Injection',  '10' => 'Project Commissioning');
								} else {
									$APPLY_ONLINE_MAIN_STATUS_CTU = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '12' => 'CTU - In Principal', '14' => 'CTU - Final Principal', '7' => 'Final Registration', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission', '16' => 'BPTA','9' => 'Wheeling Agreement', '17' => 'Meter Sealing Report','18' => 'Power Injection', '10' => 'Project Commissioning');
								}
								//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS_CTU as $key => $value) {

									$IsActive           = array_key_exists($key, $arr_application_status) ? "active" : "";
									if (empty($arr_application_status)) {
										$IsActive       = ($key == $Application->application_status) ? "active" : "";
									}

									$text_apply     = '';
									$style          = '';

									if ($str_append != '' && $key == 2) {
										$IsActive   = '';
									}
									if ($key != '7') {
										echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
									} else {
										if ($value == 'Developer Permission') {
											$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
											$strTitle = '';
											if (!empty($windAppList)) {
												foreach ($windAppList as $k => $v) {
													$activeTitle = $v->status == 1 ? "active" : "";

													$strTitle = $strTitle . '<div class="status-item ' . $activeTitle . '"> Developer Permission - ' . $v->app_order . '</div>';
												}
											}
											$LastMessageHtml = '
														<div class="card">
															<div class="card-content">
																<div class="status-list">
																	' . $strTitle . '
																</div>
															</div>											
														</div>';
											echo "<li data-placement=\"bottom\" class=\"" . htmlspecialchars($IsActive, ENT_QUOTES) . "\" data-toggle=\"popover\" data-html=\"true\" data-content=\"" . htmlspecialchars($LastMessageHtml, ENT_QUOTES) . "\">
														<span style='" . htmlspecialchars($style, ENT_QUOTES) . "'>" . htmlspecialchars($value, ENT_QUOTES) . htmlspecialchars($text_apply, ENT_QUOTES) . "</span>
													</li>";
										} else {
											echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
										}
									}
									//echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
								}
							} else { //'4' => 'Stage 1: Connectivity'
								$arr_application_status = $MStatus->all_status_application($Application->id);
								if ($Application->application_type == 4 || $Application->application_type == 3) {
									$APPLY_ONLINE_MAIN_STATUS = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '13' => 'Stage 1: Connectivity', '15' => 'WTG Co-Verification', '5' => 'Stage 2: Connectivity', '7' => 'Developer Permission', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission','16' => 'BPTA', '9' => 'Wheeling Agreement', '17' => 'Meter Sealing Report','18' => 'Power Injection', '10' => 'Project Commissioning');
								} else {
									$APPLY_ONLINE_MAIN_STATUS = array('1' => 'Application Submitted', '2' => 'Document Verified', '3' => 'Provisional Letter', '13' => 'Stage 1: Connectivity', '5' => 'Stage 2: Connectivity', '7' => 'Final Registration', '6' => 'CEI Drawing', '8' => 'CEI/CEA Permission','16' => 'BPTA', '9' => 'Wheeling Agreement', '17' => 'Meter Sealing Report','18' => 'Power Injection', '10' => 'Project Commissioning');
								}
								//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) {

									$IsActive           = array_key_exists($key, $arr_application_status) ? "active" : "";
									if (empty($arr_application_status)) {
										$IsActive       = ($key == $Application->application_status) ? "active" : "";
									}

									$text_apply     = '';
									$style          = '';

									if ($str_append != '' && $key == 2) {
										$IsActive   = '';
									}
									if ($key != '7') {
										echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
									} else {
										if ($value == 'Developer Permission') {
											$windAppList = $WindDevPermissionApp->getWindDevPermissionList(encode($Application->id));
											$strTitle = '';
											if (!empty($windAppList)) {
												foreach ($windAppList as $k => $v) {
													$activeTitle = $v->status == 1 ? "active" : "";

													$strTitle = $strTitle . '<div class="status-item ' . $activeTitle . '"> Developer Permission - ' . $v->app_order . '</div>';
												}
											}
											$LastMessageHtml = '
														<div class="card">
															<div class="card-content">
																<div class="status-list">
																	' . $strTitle . '
																</div>
															</div>											
														</div>';
											echo "<li data-placement=\"bottom\" class=\"" . htmlspecialchars($IsActive, ENT_QUOTES) . "\" data-toggle=\"popover\" data-html=\"true\" data-content=\"" . htmlspecialchars($LastMessageHtml, ENT_QUOTES) . "\">
														<span style='" . htmlspecialchars($style, ENT_QUOTES) . "'>" . htmlspecialchars($value, ENT_QUOTES) . htmlspecialchars($text_apply, ENT_QUOTES) . "</span>
													</li>";
										} else {
											echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
										}
									}
									//echo "<li class=\"" . $IsActive . "\" ><span style='" . $style . "'>" . $value . $text_apply . "</span></li>";
								}
							}
							?>
						</ul>
					</div>
				</div>
			<?php endforeach; ?>
		</div>


		<!-- Paging Starts Here -->
		<div class="text-right">
			<ul class="pagination text-right">
				<?php
				echo $this->Paginator->numbers([
					'before' => $this->Paginator->prev('Prev'),
					'after' => $this->Paginator->next('Next')
				]); ?>
			</ul>
		</div>
		<?php echo $this->Form->end(); ?>
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
		<div id="ViewLastResponse" class="modal fade" role="dialog">
			<div class="modal-dialog modal-full-dialog">
				<div class="modal-content modal-full-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Last Response</h4>
					</div>
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<div id="ViewCustomerResponse" class="modal fade" role="dialog">
			<div class="modal-dialog modal-full-dialog">
				<div class="modal-content modal-full-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Customer API Response</h4>
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
						echo $this->Form->create('VarifyOtpForm', ['name' => 'VarifyOtpForm', 'id' => 'VarifyOtpForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('appid', ['id' => 'VarifyOtp_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<?php echo $this->Form->input('otp', [
								"class" => "form-control",
								'id' => 'otp',
								'label' => false,
								"placeholder" => "Enter OTP"
							]);
							?>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary varifyotp_btn', 'data-form-name' => 'VarifyOtpForm']); ?>
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
						echo $this->Form->create('UploadDocumentForm', ['name' => 'UploadDocumentForm', 'id' => 'UploadDocumentForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('application_id', ['id' => 'upload_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload document', 'id' => 'upload_signed_file', 'accept' => '.pdf')); ?>
										</div>

									</div>
								</div>
							</div>
							<div class="row" style="margin-right: 2px;margin-left: -4px;">
								<div class="col-md-12" id="upload_signed_file-file-errors"></div>
							</div>
							<br>
						</div>
						<div class="row">

							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary uploaddocument_btn', 'data-form-name' => 'UploadDocumentForm']); ?>
							</div>

						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="mapDiscom" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Provisional Letter (Select Discom of the Project Location)</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('mapDiscomForm', ['name' => 'mapDiscomForm', 'id' => 'mapDiscomForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('application_id', ['id' => 'mapdiscom_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->select('discom', $discom_arr, array('label' => false, 'class' => 'form-control', 'empty' => '-Select Discom-')); ?>
										</div>

									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary map_discom_btn', 'data-form-name' => 'mapDiscomForm']); ?>
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
						echo $this->Form->create('SendMessageForm', ['name' => 'SendMessageForm', 'id' => 'SendMessageForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('appid', ['id' => 'SendMessage_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<?php echo $this->Form->textarea('messagebox', [
								"class" => "form-control messagebox",
								'id' => 'messagebox',
								'cols' => '50',
								'rows' => '5',
								'label' => false,
								'placeholder' => 'Message or Comment'
							]);
							?>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'id' => '', 'label' => false, 'class' => 'btn btn-primary sendmessage_btn', 'data-form-name' => 'SendMessageForm']); ?>
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
						echo $this->Form->create('ReplayMessageForm', ['name' => 'ReplayMessageForm', 'id' => 'ReplayMessageForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<div id="reply_message" class="inquiry-right"></div>
							<?php echo $this->Form->input('app_id', ['id' => 'ReplayMessage_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<?php echo $this->Form->textarea('message', [
								"class" => "form-control message",
								'id' => 'message',
								'cols' => '50',
								'rows' => '5',
								'label' => false,
								'placeholder' => 'Message or Comment'
							]);
							?>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'id' => 'login_btn_8', 'label' => false, 'class' => 'btn btn-primary replaymessage_btn', 'data-form-name' => 'ReplayMessageForm']); ?>
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
						echo $this->Form->create('OtherDocumentForm', ['name' => 'OtherDocumentForm', 'id' => 'OtherDocumentForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('other_application_id', ['id' => 'other_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<label>Document Type</label>
											<?php echo $this->Form->input('message', ['id' => 'message', 'label' => false, 'type' => 'text']); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload document', 'id' => 'other_docfile')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="other_docfile-file-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary otherdocument_btn', 'data-form-name' => 'OtherDocumentForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="TP" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Upload TFR Document</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('TPForm', ['name' => 'TPForm', 'id' => 'TPForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('TP_application_id', ['id' => 'TP_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('TP', ['id' => 'titletp', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('TPfile', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'TPfile')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="TPfile-file-errors"></div>
									</div>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary TP_btn', 'data-form-name' => 'TPForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="Substation" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Update Substation</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('SubstationForm', ['name' => 'SubstationForm', 'id' => 'SubstationForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('Substation_application_id', ['id' => 'Substation_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									
									<label>Name of Proposed GETCO / PGCIL Substation</label><br>
									<?php echo $this->Form->select('getco_substation', $substation_details, array('label' => false, 'class' => 'change_customer_type form-control  chosen-select', 'empty' => '-Select Substation-')); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary Substation_btn', 'data-form-name' => 'SubstationForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="STUstep2" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Stage 2 : Connectivity</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('STUstep2Form', ['name' => 'STUstep2Form', 'id' => 'STUstep2Form', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('STUstep2_application_id', ['id' => 'STUstep2_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">

										<div class="col-md-12">
											<?php echo $this->Form->input('grid_connectivity_capacity', ['id' => 'grid_connectivity_capacity', 'label' => false, 'type' => 'text', 'oninput' => "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');", 'placeholder' => 'Grid Connectivity Capacity (in MW)']); ?>
										</div>

									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('title1', ['id' => 'title21', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('file1', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'step21')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="step21-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('title2', ['id' => 'title22', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('file2', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'step22')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="step22-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('title3', ['id' => 'title23', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('file3', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'step23')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="step23-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('title4', ['id' => 'title24', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('file4', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'step24')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="step24-file-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary STUstep2_btn', 'data-form-name' => 'STUstep2Form']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="STUstep1" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<div class="connectivity_opt">
							<h4 class="modal-title connectivity_opt"> Do you have connectivity ?</h4>
							<input type="radio" name="has_connectivity" value="1"> Yes &nbsp;&nbsp;
							<input type="radio" name="has_connectivity" value="2"> No
						</div>
						<div class="STU" style="display:none">
							<h4 class="modal-title STU"> STU : Connectivity</h4>
							<input type="radio" name="connectivity_type" value="1"> Old &nbsp;&nbsp;
							<input type="radio" name="connectivity_type" value="2"> New
						</div>
					</div>
					<div class="modal-body STUold" style="display:none">
						<?php
						echo $this->Form->create('STUStep1Form', ['name' => 'STUStep1Form', 'id' => 'STUStep1Form', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('STUStep1_application_id', ['id' => 'STUStep1_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">

									<div class="row">
										<div class="col-md-6">
											<lable>Date of Connectivity </lable>
											<?php echo $this->Form->input('stu_connectivity_date', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-control-inline input-medium', 'id' => 'stu_connectivity_date', 'placeholder' => 'Connectivity Date', 'autocomplete' => 'off')); ?>
										</div>
										<div class="col-md-6">
											<lable>Date of Validity </lable>
											<?php echo $this->Form->input('stu_validity_date', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-control-inline input-medium', 'id' => 'stu_validity_date', 'placeholder' => 'Validity Date', 'autocomplete' => 'off')); ?>
										</div>
									</div>
									<div class="row">
										<lable class="col-md-4">Connectivity Upload Approval</lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('connectivity_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'STUfile1')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="STUfile1-file-errors"></div>
									</div>

									<div class="row">
										<lable class="col-md-4">BG Upload</lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('bg_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'STUfile2')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="STUfile2-file-errors"></div>
									</div>

								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary STUstep1_btn', 'data-form-name' => 'STUStep1Form']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>

					<div class="modal-body STUnew" style="display:none">
						<?php
						echo $this->Form->create('STUStep1NewForm', ['name' => 'STUStep1NewForm', 'id' => 'STUStep1NewForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('STUStep1new_application_id', ['id' => 'STUStep1new_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<h5 class="modal-title">Stage 1 Connectivity</h5><br>
									<div class="col-md-4">
										<lable>Connectivity Document</lable>

									</div>
									<div class="col-md-8">
										<?php echo $this->Form->input('stunew_file1', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'stunew_file1')); ?>
									</div>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary STUstep1New_btn', 'data-form-name' => 'STUStep1NewForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
					<div class="modal-body STUGUVNL" style="display:none">

						<?php
						echo $this->Form->create('STUStep1Form', ['name' => 'STUStep1Form', 'id' => 'STUStep1Form', 'type' => 'file', 'enctype' => 'multipart/form-data']); ?>

						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Fetch Details', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary fetch STUstep1_btnGUVNL', 'data-form-name' => 'STUStep1Form']); ?>
							</div>
						</div>


						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('applicant_id', ['id' => 'STUStep1_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<!-- <button id="submitButton">Submit and Redirect</button> -->
							<?php echo $this->Form->input('Go To guvnl', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary submitButton', 'id' => 'submitButton', 'style' => 'display:none']); ?>

							<input type="hidden" name="company_name" id='company_name'>
							<input type="hidden" name="applicant_name" id='applicant_name'>
							<input type="hidden" name="pan_card" id='pan_card'>
							<input type="hidden" name="mobile" id='mobile'>
							<input type="hidden" name="email_id" id='email_id'>
							<input type="hidden" name="address" id='address'>
							<input type="hidden" name="city" id='city'>
							<input type="hidden" name="state" id='state'>
							<input type="hidden" name="gst_no" id='gst_no'>
							<input type="hidden" name="prov_proj_no" id='prov_proj_no'>
							<input type="hidden" name="applicant_type" id='applicant_type'>
							<input type="hidden" name="project_type" id='project_type'>
							<input type="hidden" name="project_purpose" id='project_purpose'>
							<input type="hidden" name="ss_name" id='ss_name'>
							<input type="hidden" name="ss_id" id='ss_id'>
							<input type="hidden" name="getcogetco_field_office" id='getcogetco_field_office'>
							<input type="hidden" name="applied_capacity_ac" id='applied_capacity_ac'>
							<input type="hidden" name="applied_capacity_dc" id='applied_capacity_dc'>
							<input type="hidden" name="voltage_class" id='voltage_class'>
							<input type="hidden" name="pwr_company" id='pwr_company'>
							<input type="hidden" name="developer_registration_no" id='developer_registration_no'>

							<input type="hidden" name="pancard_doc" id='pancard_doc'>
							<input type="hidden" name="undertaking" id='undertaking'>
							<input type="hidden" name="board_resolution" id='board_resolution'>


						</div>

						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="STUstep1_guvnl" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"> STU Connectivity </h4>
					</div>

					<div class="modal-body">

						<?php
						echo $this->Form->create('STUStep1Form', ['name' => 'STUStep1Form', 'id' => 'STUStep1Form', 'type' => 'file', 'enctype' => 'multipart/form-data']); ?>

						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Fetch Details', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary fetch STUstep1_btn', 'data-form-name' => 'STUStep1Form']); ?>
							</div>
						</div>


						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('applicant_id', ['id' => 'STUStep1_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<!-- <button id="submitButton">Submit and Redirect</button> -->
							<?php echo $this->Form->input('Go To guvnl', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary submitButton', 'id' => 'submitButton', 'style' => 'display:none']); ?>

							<input type="hidden" name="company_name" id='company_name'>
							<input type="hidden" name="applicant_name" id='applicant_name'>
							<input type="hidden" name="pan_card" id='pan_card'>
							<input type="hidden" name="mobile" id='mobile'>
							<input type="hidden" name="email_id" id='email_id'>
							<input type="hidden" name="address" id='address'>
							<input type="hidden" name="city" id='city'>
							<input type="hidden" name="state" id='state'>
							<input type="hidden" name="gst_no" id='gst_no'>
							<input type="hidden" name="prov_proj_no" id='prov_proj_no'>
							<input type="hidden" name="applicant_type" id='applicant_type'>
							<input type="hidden" name="project_type" id='project_type'>
							<input type="hidden" name="project_purpose" id='project_purpose'>
							<input type="hidden" name="ss_name" id='ss_name'>
							<input type="hidden" name="ss_id" id='ss_id'>
							<input type="hidden" name="getcogetco_field_office" id='getcogetco_field_office'>
							<input type="hidden" name="applied_capacity_ac" id='applied_capacity_ac'>
							<input type="hidden" name="applied_capacity_dc" id='applied_capacity_dc'>
							<input type="hidden" name="voltage_class" id='voltage_class'>
							<input type="hidden" name="pwr_company" id='pwr_company'>
							<input type="hidden" name="developer_registration_no" id='developer_registration_no'>

							<input type="hidden" name="pancard_doc" id='pancard_doc'>
							<input type="hidden" name="undertaking" id='undertaking'>
							<input type="hidden" name="board_resolution" id='board_resolution'>


						</div>

						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="CTUstep1" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"> CTU Connectivity </h4>
					</div>

					<div class="modal-body">
						<?php
						echo $this->Form->create('CTUStep1Form', ['name' => 'CTUStep1Form', 'id' => 'CTUStep1Form', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('CTUStep1_application_id', ['id' => 'CTUStep1_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">

									<div class="row">
										<lable class="col-md-4">In Principal Document </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('ip_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'CTUfile1_ip')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="CTUfile1_ip-file-errors"></div>
									</div>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary CTUstep1_btn', 'data-form-name' => 'CTUStep1Form']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		
		<div id="CTUstep2" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Stage 2 : Connectivity</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('CTUStep2Form', ['name' => 'CTUStep2Form', 'id' => 'CTUStep2Form', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('CTUStep2_application_id', ['id' => 'CTUStep2_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Final Principal Document </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('fp_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'CTUfile1_fp')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="CTUfile1_fp-file-errors"></div>
									</div>
								</div>
							</div>
							<?php /* } */ ?>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary CTUstep2_btn', 'data-form-name' => 'CTUStep2Form']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="DRAWING_Status" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">CEI Drawing Application Ref. No.</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('DCEI_Status', ['name' => 'DRAWING_Status', 'id' => 'DCEI_Status']); ?>
						<div id="messageBox"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('approval_type', ['id' => 'DRAW_approval_type', 'label' => true, 'type' => 'hidden', 'value' => '51']); ?>
							<?php echo $this->Form->input('appid', ['id' => 'DRAW_application_id', 'label' => true, 'type' => 'hidden']); ?>

						</div>
						<div class="row">
							<div class="col-md-6">
								<?php echo $this->Form->input('drawing_app_no', ['label' => false, 'placeholder' => 'CEI drawing application number', 'id' => 'drawing_app_no_val']); ?>
							</div>
							<div class="col-md-6">
								<?php echo $this->Form->input('Fetch Status', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary', 'id' => 'fetch_status_drawing', 'data-form-name' => 'DCEI_Status']); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>CEI Drawing Application Status:
									<?php echo $this->Form->input('drawing_app_status', ['label' => false, 'type' => 'hidden', 'id' => 'drawing_app_status_frm']); ?></label>
							</div>
							<div class="col-md-6" id="drawing_app_status_html_frm"></div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'id' => '', 'label' => false, 'class' => 'btn btn-primary approval_btn_drawing', 'data-form-name' => 'DCEI_Status']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="CEI_APP_Status_POPUP" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">CEI/CEA Permission Form</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('CEI_APP_Status_FORM', ['name' => 'CEI_APP_Status_FORM', 'id' => 'CEI_APP_Status_FORM']); ?>
						<div id="messageBox"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('approval_type', ['id' => 'CEI_app_approval_type', 'label' => true, 'type' => 'hidden', 'value' => '6']); ?>
							<?php echo $this->Form->input('appid', ['id' => 'CEI_form_application_id', 'label' => true, 'type' => 'hidden']); ?>

						</div>
						<div class="row" id="cei_app_number_data">
							<div class="col-md-6">
								<?php echo $this->Form->input('cei_app_no', ['label' => false, 'placeholder' => 'Application Ref No.', 'id' => 'cei_app_no_val']); ?>
							</div>
							<div class="col-md-6">
								<?php echo $this->Form->input('Fetch Status', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary', 'id' => 'fetch_status_cei', 'data-form-name' => 'CEI_APP_Status_FORM']); ?>
							</div>
						</div>
						<div class="row" id="cei_app_status_data">
							<div class="col-md-6">
								<label>CEI Application Status:
									<?php echo $this->Form->input('cei_app_status', ['label' => false, 'type' => 'hidden', 'id' => 'cei_app_status_frm']); ?></label>
							</div>
							<div class="col-md-6" id="cei_app_status_html_frm"></div>
						</div>

						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'id' => '', 'label' => false, 'class' => 'btn btn-primary approval_btn_cei', 'data-form-name' => 'CEI_APP_Status_FORM']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="BPTA" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">BPTA Application Agreement</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('BPTAForm', ['name' => 'BPTAForm', 'id' => 'BPTAForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('BPTA_application_id', ['id' => 'BPTA_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">BPTA  Document 1</lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('bpta_document1', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'bpta_file1')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="bpta_file1-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">BPTA  Document 2</lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('bpta_document2', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'bpta_file2')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="bpta_file2-file-errors"></div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary BPTA_btn', 'data-form-name' => 'BPTAForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="BPTAApproval" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">BPTA Documents Approval</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('BPTAApprovalForm', ['name' => 'BPTAApprovalForm', 'id' => 'BPTAApprovalForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('BPTAApproval_application_id', ['id' => 'BPTAApproval_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-6"  id="bpta_document_file1"></div>
										<div class="col-md-6"  id="bpta_document_file2"></div>
									</div><br>
									<div class="row">
										<div class="col-md-6">
											<?php echo $this->Form->select('bpta_approve',array("1"=>"Yes","2"=>"No"),["class" =>"form-control",'id'=>'


											','label' => true]); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('bpta_approval_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'bpta_approval_date','placeholder'=>'Approval Date','autocomplete'=>'off')); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('bpta_reason',[ "class" =>"form-control reason",
																				'id'=>'bpta_reason',
																				'cols'=>'50','rows'=>'5',
																				'label' => false,
																				'placeholder' => 'Comments, if any']);
											?>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary BPTAApproval_btn', 'data-form-name' => 'BPTAApprovalForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="WHEELING" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Wheeling Application Agreement</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('WHEELINGForm', ['name' => 'WHEELINGForm', 'id' => 'WHEELINGForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('WHEELING_application_id', ['id' => 'WHEELING_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Wheeling Agreement Document </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('Wheeling_Agreement_document', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'Wheeling_file')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="Wheeling_file-file-errors"></div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary WHEELING_btn', 'data-form-name' => 'WHEELINGForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="WHEELINGApproval" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Wheeling Agreement Approval</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('WHEELINGApprovalForm', ['name' => 'WHEELINGApprovalForm', 'id' => 'WHEELINGApprovalForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('WHEELINGApproval_application_id', ['id' => 'WHEELINGApproval_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="wheeling_agreement_document_file"></div>
									</div><br>
									<div class="row">
										<div class="col-md-6">
											<?php echo $this->Form->select('wheeling_approve',array("1"=>"Yes","2"=>"No"),["class" =>"form-control",'id'=>'


											','label' => true]); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('wheeling_approval_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'wheeling_approval_date','placeholder'=>'Approval Date','autocomplete'=>'off')); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('wheeling_reason',[ "class" =>"form-control reason",
																				'id'=>'wheeling_reason',
																				'cols'=>'50','rows'=>'5',
																				'label' => false,
																				'placeholder' => 'Comments, if any']);
											?>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary WHEELINGApproval_btn', 'data-form-name' => 'WHEELINGApprovalForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="METER_SEALING" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Meter Sealing Report</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('METER_SEALINGForm', ['name' => 'METER_SEALINGForm', 'id' => 'METER_SEALINGForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('METER_SEALING_application_id', ['id' => 'METER_SEALING_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Meter Sealing Report </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('meter_sealing_report', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'meter_file')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="meter_file-file-errors"></div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary METER_SEALING_btn', 'data-form-name' => 'METER_SEALINGForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="METER_SEALINGApproval" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Meter Sealing Report Approval</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('METER_SEALINGApprovalForm', ['name' => 'METER_SEALINGApprovalForm', 'id' => 'METER_SEALINGApprovalForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('METER_SEALINGApproval_application_id', ['id' => 'METER_SEALINGApproval_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="meter_sealing_report_file"></div>
									</div><br>
									<div class="row">
										<div class="col-md-6">
											<?php echo $this->Form->select('meter_approve',array("1"=>"Yes","2"=>"No"),["class" =>"form-control",'id'=>'


											','label' => true]); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('meter_approval_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'meter_approval_date','placeholder'=>'Approval Date','autocomplete'=>'off')); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('meter_reason',[ "class" =>"form-control reason",
																				'id'=>'meter_reason',
																				'cols'=>'50','rows'=>'5',
																				'label' => false,
																				'placeholder' => 'Comments, if any']);
											?>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary METER_SEALINGApproval_btn', 'data-form-name' => 'METER_SEALINGApprovalForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="POWER_INJECTION" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Declaration of Power Injection</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('POWER_INJECTIONForm', ['name' => 'POWER_INJECTIONForm', 'id' => 'POWER_INJECTIONForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('POWER_INJECTION_application_id', ['id' => 'POWER_INJECTION_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Power Injection Report </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('power_injection_report', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'power_injection_file')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="power_injection_file-file-errors"></div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary POWER_INJECTION_btn', 'data-form-name' => 'POWER_INJECTIONForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="POWER_INJECTIONApproval" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Meter Sealing Report Approval</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('POWER_INJECTIONApprovalForm', ['name' => 'POWER_INJECTIONApprovalForm', 'id' => 'POWER_INJECTIONApprovalForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('POWER_INJECTIONApproval_application_id', ['id' => 'POWER_INJECTIONApproval_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="power_injection_report_file"></div>
									</div><br>
									<div class="row">
										<div class="col-md-6">
											<?php echo $this->Form->select('power_injection_approve',array("1"=>"Yes","2"=>"No"),["class" =>"form-control",'id'=>'


											','label' => true]); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('power_injection_approval_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'power_injection_approval_date','placeholder'=>'Approval Date','autocomplete'=>'off')); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('power_injection_reason',[ "class" =>"form-control reason",
																				'id'=>'power_injection_reason',
																				'cols'=>'50','rows'=>'5',
																				'label' => false,
																				'placeholder' => 'Comments, if any']);
											?>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary POWER_INJECTIONApproval_btn', 'data-form-name' => 'POWER_INJECTIONApprovalForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="SLDC" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">SLDC Application</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('SLDCForm', ['name' => 'SLDCForm', 'id' => 'SLDCForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('SLDC_application_id', ['id' => 'SLDC_application_id', 'label' => true, 'type' => 'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('sldc_title1', ['id' => 'sldc_title1', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('sldc_file1', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'sldc_file1')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="sldc_file1-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('sldc_title2', ['id' => 'sldc_title2', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('sldc_file2', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'sldc_file2')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="sldc_file2-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('sldc_title3', ['id' => 'sldc_title3', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('sldc_file3', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'sldc_file3')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="sldc_file3-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('sldc_title4', ['id' => 'sldc_title4', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('sldc_file4', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'sldc_file4')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="sldc_file4-file-errors"></div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<?php echo $this->Form->input('sldc_title5', ['id' => 'sldc_title5', 'label' => false, 'type' => 'text', 'placeholder' => 'Title']); ?>
										</div>
										<div class="col-md-8">
											<?php echo $this->Form->input('sldc_file5', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload File', 'id' => 'sldc_file5')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="sldc_file5-file-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary SLDC_btn', 'data-form-name' => 'SLDCForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="SLDCApproval" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">SLDC Agreement Approval</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('SLDCApprovalForm', ['name' => 'SLDCApprovalForm', 'id' => 'SLDCApprovalForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('SLDCApproval_application_id', ['id' => 'SLDCApproval_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="Sldc_Agreement_document_file1"></div>
										<div class="col-md-12"  id="Sldc_Agreement_document_file2"></div>
										<div class="col-md-12"  id="Sldc_Agreement_document_file3"></div>
										<div class="col-md-12"  id="Sldc_Agreement_document_file4"></div>
										<div class="col-md-12"  id="Sldc_Agreement_document_file5"></div>
									</div><br>
									<div class="row">
										<div class="col-md-6">
											<?php echo $this->Form->select('sldc_approval',array("1"=>"Yes","2"=>"No"),["class" =>"form-control",'id'=>'','label' => true]); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('sldc_approved_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'sldc_approved_date','placeholder'=>'Approval Date','autocomplete'=>'off')); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('sldc_reason',[ "class" =>"form-control reason",
																				'id'=>'sldc_reason',
																				'cols'=>'50','rows'=>'5',
																				'label' => false,
																				'placeholder' => 'Comments, if any']);
											?>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary SLDCApproval_btn', 'data-form-name' => 'SLDCApprovalForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="completeintimation" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Intimation for Completion Form</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('IntimationCompForm',['name'=>'IntimationCompForm','id'=>'IntimationCompForm','type' => 'file']); ?>
					<div id="messageBox"></div>
					<?php echo $this->Form->input('comp_intimation_id',['id'=>'int_comp_application_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
							<?php echo $this->Form->input('Intimation for Completion',['type'=>'button','id'=>'login_btn_13','label'=>false,'class'=>' btn btn-primary completeintimation_btn','data-form-name'=>'IntimationCompForm']); ?>
							</div>
						</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
		<div id="Work_Execution" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Work Execution</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('Work_ExecutionForm', ['name' => 'Work_ExecutionForm', 'id' => 'Work_ExecutionForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('Work_Execution_application_id', ['id' => 'Work_Execution_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<!-- <div class="row">
							<div class="col-md-12">
								<div class="row">
									<lable class="col-md-4">Final Principal Document </lable>
									<div class="col-md-8">
									<?php echo $this->Form->input('fp_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'CTUfile1_fp')); ?>
									</div>
								</div>
								<div class="row" style="margin-right: 2px;margin-left: -4px;">
									<div class="col-md-12"  id="CTUfile1_fp-file-errors"></div>
								</div>
							</div>
						</div> -->
							<?php /* } */ ?>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary Work_Execution_btn', 'data-form-name' => 'Work_ExecutionForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="ProjectCommissioning" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close cross" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Project Commissioning</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('ProjectCommissioningForm', ['name' => 'ProjectCommissioningForm', 'id' => 'ProjectCommissioningForm', 'type' => 'file']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
							<?php echo $this->Form->input('ProjectCommissioning_application_id', ['id' => 'ProjectCommissioning_application_id', 'label' => true, 'type' => 'hidden']); ?>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Meter No.</lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('PC_meter_no', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'PC_meter_no', 'id' => 'PC_meter_no')); ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Date of Commissioning </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('PC_date', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control form-control-inline input-medium', 'id' => 'PC_date', 'autocomplete' => 'off')); ?>
										</div>
									</div>

								</div>
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-4">Upload Document </lable>
										<div class="col-md-8">
											<?php echo $this->Form->input('pc_upload_file', array('label' => false, 'type' => 'file', 'class' => 'form-control', 'placeholder' => 'Upload', 'id' => 'pc_upload_file')); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12" id="pc_upload_file-file-errors"></div>

									</div>
								</div>
							</div>
							<?php /* } */ ?>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit', ['type' => 'button', 'label' => false, 'class' => 'btn btn-primary ProjectCommissioning_btn', 'data-form-name' => 'ProjectCommissioningForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>




		<!-- Comment Modal -->
		<div id="comment_modal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<form id="comment-form">
						<div class="modal-header">
							<button type="button" class="close cross" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Add Comment</h4>
						</div>
						<div class="modal-body">
							<div id="comment-error-msg" class="alert alert-danger" style="display: none;"></div>
							<textarea id="comment-text" name="comment-text" rows="4" cols="50"></textarea>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary" id="submit-comment">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div id="loader-overlay" style="display:none;">
			<div id="loader">
				<i class="fa fa-spinner fa-spin" style="font-size:50px;"></i>
			</div>
		</div>
		<!-- Vishal	-->
		<!-- Modal -->
		<div id="open_access_permission" class="modal fade" role="dialog">

			<div class="modal-dialog">
				<!-- Loader -->

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title tran-title">Developer Final Application Confirmation</h4>
					</div>
					<div class="modal-body" style="width:100%;padding:10px 30px">
						<table style="width:100%;font-size:small">
							<tr>
								<th>District</th>
								<td id="district"></td>
							</tr>
							<tr>
								<th>Taluka</th>
								<td id="taluka"></td>
							</tr>
							<tr>
								<th>City</th>
								<td id="appcity"></td>
							</tr>
							<tr>
								<th>Consumer Application Type</th>
								<td id="appType"></td>
							</tr>
							<tr>
								<th>Name of Proposed GETCO/PGCIL Substation</th>
								<td id="substation"></td>
							</tr>
							<tr>
								<th>End use of electricity</th>
								<td id="endUse"></td>
							</tr>
						</table>
						<div style="padding: 0;text-align:right;margin-top:20px">
							<a type="button" class="btn btn-info" id="viewButton" target="_blank"><i class="fa fa-eye"></i> View</a>
							<a type="button" class="btn btn-warning" id="editButton" target="_blank"><i class="fa fa-pencil"></i> Edit</a>
							<a type="button" class="btn btn-primary" id="msgButton" data-toggle="modal" data-target="#commentModal" data-id=""><i class="fa fa-comments"></i> Comments</a>

						</div>
						<div class="card_main" style="margin-top:20px">
							<div class="card_inner">
								<div class="body_card">
									<p class="title_card">Role</p>
									<span class="badge badge-success">Status</span>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" style="padding-top: 0;">
						<form id="approvalForm" action="" name="approvalForm" method="post">
							<input type="hidden" id="application_id" name="application_id" value="">
							<input type="hidden" id="dev_app_id" name="dev_app_id" value="">
							<input type="hidden" id="app_type" name="app_type" value="">
							<div class="row" style="margin-top: 30px;">

								<div class="col-sm-6 inward" style="text-align: start;">
									<div class="form-group">
										<label>Physical Document Submitted Date</label>
										<input type="text" class="form-control datepicker" name="inward_date" id="inward_date">
									</div>
								</div>
								<div class="col-sm-6 " style="text-align: start;">
									<div class="form-group">
										<label>Actions</label>
										<select class="form-control" id="action" name="action" required>
											<option value="">Select Action</option>
											<option value="1">Approve</option>
											<option value="2">Forward</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row" style="margin-top:0px;display: flex;justify-content: end;">
								<div class="col-sm-4 form-group comment-group" style="display: none;text-align: start; ">
									<label>Forward To</label>
									<select class="form-control" id="forward" name="forward" required>
										<option value="">Select</option>

									</select>
								</div>
								<div class="col-sm-8 form-group comment-group" style="display: none;text-align: start; ">
									<label for="comment">Comment:</label>
									<textarea class="form-control" id="comment" name="comment"></textarea>
								</div>
								<div class="col-sm-12 form-group developer_comment-group" style="display: none;text-align: start; ">
									<label for="developer-comment">Message To Developer:</label>
									<textarea class="form-control" id="developer_comment" name="developer_comment"></textarea>
								</div>



							</div>
							<div class="row modal-footer">
								<div class="col-sm-12 " style="justify-content: end;">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
							<div class="row" style="margin-top:10px;">
								<div class="col-sm-12">
									<div id="success-alert" class="success-alert alert alert-success" style="display: none;text-align:center"></div>
									<div id="danger-alert" class="danger-alert alert alert-danger" style="display: none;text-align:center"></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div id="commentModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Comments</h4>
					</div>
					<div class="modal-body">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title"></h5>
								<p class="card-text"></p>
								<span class="badge badge-success"></span>
								<span class="badge badge-danger"></span>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>



		<div id="wind_dp_letter" class="modal fade" role="dialog">
			<div class="modal-dialog modal-full-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Create DP Letter</h4>
					</div>
					<div class="modal-body">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div class="row">
										<form id="dpForm" name="dpForm" method="post">
											<input type="hidden" id="dp_application_id" name="dp_application_id" value="">
											<input type="hidden" id="dp_dev_app_id" name="dp_dev_app_id" value="">
											<input type="hidden" id="dp_app_type" name="dp_app_type" value="">

											<div class="texteditor-container">

												<?php echo $this->Form->input('content', array('type' => 'textarea', 'rows' => 300, 'label' => false, 'class' => 'form-control', 'id' => 'editor', 'value' => '')); ?>
											</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<?php echo $this->Form->button(__('Save'), ['type' => 'submit', 'id' => 'save_btn', 'class' => 'btn-primary']); ?>
						<div class="col-sm-4 text-center">
							<div id="success-alert" class="success-alert alert alert-success" style="display: none;text-align:center"></div>
							<div id="danger-alert" class="danger-alert alert alert-danger" style="display: none;text-align:center"></div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="wind_tp_letter" class="modal fade" role="dialog">
			<div class="modal-dialog modal-full-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Create TP Letter</h4>
					</div>
					<div class="modal-body">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div class="row">
										<form id="tpForm" name="tpForm" method="post">
											<input type="hidden" id="tp_application_id" name="tp_application_id" value="">
											<input type="hidden" id="tp_dev_app_id" name="tp_dev_app_id" value="">
											<input type="hidden" id="tp_app_type" name="tp_app_type" value="">

											<div class="texteditor-container">

												<?php echo $this->Form->input('content', array('type' => 'textarea', 'rows' => 300, 'label' => false, 'class' => 'form-control', 'id' => 'tpeditor', 'value' => '')); ?>
											</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<?php echo $this->Form->button(__('Save'), ['type' => 'submit', 'id' => 'save_btn', 'class' => 'btn-primary']); ?>
						<div class="col-sm-4 text-center">
							<div id="success-alert" class="success-alert alert alert-success" style="display: none;text-align:center"></div>
							<div id="danger-alert" class="danger-alert alert alert-danger" style="display: none;text-align:center"></div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="upload_dp_letter" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Upload Signed DP Letter</h4>
					</div>
					<div class="modal-body">
						<div class="card">
							<div class="card-body">
								<form id="upload_dp_letter_form" name="upload_dp_letter_form" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<input type="hidden" id="upload_dp_application_id" name="upload_dp_application_id" value="">
										<input type="hidden" id="upload_dp_dev_app_id" name="upload_dp_dev_app_id" value="">
										<input type="hidden" id="upload_dp_app_type" name="upload_dp_app_type" value="">
										<label for="upload_signed_dp_letter">Upload Signed DP Letter:</label>
										<input type="file" id="upload_signed_dp_letter" name="upload_signed_dp_letter" class="form-control">
									</div>
									<div id='upload_signed_dp_letter-file-errors'></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="col-sm-8 text-center">
							<div id="success-alert" class="success-alert alert alert-success" style="display: none;"></div>
							<div id="danger-alert" class="danger-alert alert alert-danger" style="display: none;"></div>
						</div>
						<div class="ml-auto">
							<?php echo $this->Form->button(__('Upload'), ['type' => 'submit', 'id' => 'save_btn', 'class' => 'btn-primary']); ?>
						</div>

						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- TP Letter -->
		<div id="upload_tp_letter" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Upload Signed DP Letter</h4>
					</div>
					<div class="modal-body">
						<div class="card">
							<div class="card-body">
								<form id="upload_tp_letter_form" name="upload_tp_letter_form" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<input type="hidden" id="upload_tp_application_id" name="upload_tp_application_id" value="">
										<input type="hidden" id="upload_tp_dev_app_id" name="upload_tp_dev_app_id" value="">
										<input type="hidden" id="upload_tp_app_type" name="upload_tp_app_type" value="">
										<label for="upload_signed_tp_letter">Upload Signed DP Letter:</label>
										<input type="file" id="upload_signed_tp_letter" name="upload_signed_tp_letter" class="form-control">
									</div>
									<div id='upload_signed_dp_letter-file-errors'></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="col-sm-8 text-center">
							<div id="success-alert" class="success-alert alert alert-success" style="display: none;"></div>
							<div id="danger-alert" class="danger-alert alert alert-danger" style="display: none;"></div>
						</div>
						<div class="ml-auto">
							<?php echo $this->Form->button(__('Upload'), ['type' => 'submit', 'id' => 'save_btn', 'class' => 'btn-primary']); ?>
						</div>

						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="transfer_dp" class="modal fade" role="dialog">
			<div class="modal-dialog modal-full-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Transfer Developer Permission</h4>
					</div>
					<div class="modal-body">
						<div class="card">
							<div class="card-body">
								<form id="transfer_dp_form" name="transfer_dp_form" method="post">
									<div class="row">
										<div class="col-md-12">
											<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead" style="width:100% !important">
												<thead class="thead-dark">
													<th scope="col" width="1%"><input type="checkbox" name="checkAll" class="form-check-input checkAll"></th>
													<th scope="col" width="7%">X-Cordinate</th>
													<th scope="col" width="7%">Y-Cordinate</th>
													<th scope="col" width="15%">WTG Make</th>
													<th scope="col" width="20%">WTG Model</th>
													<th scope="col" width="5%">Capacity</th>
													<th scope="col" width="5%">Rotor Dia. in meters</th>
													<th scope="col" width="5%">Hub Height in meters</th>
													<th scope="col" width="5%">Land Survey No</th>
													<th scope="col" width="5%">Village</th>
													<th scope="col" width="5%">Taluka</th>
													<th scope="col" width="5%">District</th>
													<th scope="col" width="5%">Transfer To</th>
													<th scope="col" width="5%">Developer Permission</th>
													<th scope="col" width="5%">Status</th>
												</thead>
												<tbody></tbody>
											</table>
											<div id="pagination-controls" class="pagination-container"></div>
											<div id="checkbox-error-container" class="has-error"></div>

										</div>
									</div>
									<?php if ($is_member == false) { ?>
										<div class="modal-footer">
											<div class="col-sm-6 text-left">
												<div id="success-alert" class="success-alert alert alert-success" style="display: none;"></div>
												<div id="danger-alert" class="danger-alert alert alert-danger" style="display: none;"></div>
											</div>
											<div class="col-sm-1 text-center">
												<label for="select-developer">Transfer To:</label>
											</div>
											<div class="col-sm-3 text-center">
												<div class="form-group">
													<select class="form-control" id="developer" name="developer" required>
														<option value="">Select Developer</option>
													</select>
												</div>
											</div>
											<div class="ml-auto">
												<?php echo $this->Form->button(__('Submit'), ['type' => 'submit', 'id' => 'save_transfer', 'class' => 'btn-primary']);
												?>
											</div>

										</div>
									<?php } ?>
								</form>
							</div>
						</div>
					</div>
					<!-- Modal footer -->
				</div>
			</div>
		</div>



		<!-- Vishal-->
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
	<!-- Vishal -->
	<script type="text/javascript" src="/tinymce/tinymce.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<!-- vishal -->
	<script type="text/javascript">
		//try
		
		$('#wheeling_approval_date').datepicker({format: 'dd/mm/yyyy'});
		$('#meter_approval_date').datepicker({format: 'dd/mm/yyyy'});
		$('#power_injection_approval_date').datepicker({format: 'dd/mm/yyyy'});
		$('#bpta_approval_date').datepicker({format: 'dd/mm/yyyy'});
		$('#sldc_approved_date').datepicker({format: 'dd/mm/yyyy'});
		$(document).ready(function() {
			$('#inward_date').datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true
			});
			$('.open_access_permission').on('click', function() {
				var applicationId = $(this).data('id');
				var devAppId = $(this).attr('dev-app-id');
				var appType = $(this).attr('app-type');

				$('#loader-overlay').show();

				$.ajax({
					type: "POST",
					url: "/ApplicationDeveloperPermission/developer_form_approval",
					data: {
						id: applicationId,
						dev_app_id: devAppId,
						app_type: appType
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {

						if (response) {
							$('#loader-overlay').hide();
							var data = JSON.parse(response);

							if (data && data.district && data.taluka && data.city && data.appType && data.substation && data.endUse && data.roles) {
								$('#district').text(data.district);
								$('#taluka').text(data.taluka);
								$('#appcity').text(data.city);
								$('#appType').text(data.appType);
								$('#substation').text(data.substation);
								$('#endUse').text(data.endUse);
								$('#application_id').val(applicationId);
								$('#app_type').val(appType);
								$('#dev_app_id').val(devAppId);
								$('#msgButton').attr('data-id', applicationId);
								$('#msgButton').attr('dev-app-id', devAppId);
								$('#msgButton').attr('app-type', appType);
								$('#inward_date').val(data.inward_date);
								$('#approvalForm').attr('action', 'ApplicationDeveloperPermission/developer_application_query');
								$('#msgButton').attr('data-action', '/ApplicationDeveloperPermission/developer_form_approval_msg');
								if (appType == 2) {
									$('#viewButton').attr('href', '/ApplicationDeveloperPermission/open_access_view/' + devAppId + '/0');
									$('#editButton').attr('href', '/open-access-permission/' + applicationId);
								}
								if (appType == 3) {
									$('#viewButton').attr('href', '/ApplicationDeveloperPermission/wind_view/' + devAppId + '/0');
									$('#editButton').attr('href', '/wind-permission/' + applicationId + '/' + devAppId);
								}
								if (appType == 4) {
									$('#viewButton').attr('href', '/ApplicationDeveloperPermission/wind_view/' + devAppId + '/0');
									$('#editButton').attr('href', '/hybrid-permission/' + applicationId + '/' + devAppId);
								}
								if (data.jpoFlag == 1) {
									//$('.inward').hide();
									$('#action option[value="1"]').remove();
									if ($('#action option[value="3"]').length === 0) {

										$('#action').append('<option value="3">Query</option>');
									}
								}
								if (data.jpoFlag == 0) {
									$('.inward').hide();
								}
								// Clear the existing cards
								$('.card_main').empty();
								var cards = data.roles;
								if (cards.length > 0) {
									cards.forEach(function(card) {
										var statusText, badgeClass;
										switch (card.status) {
											case 1:
												statusText = 'Approved';
												badgeClass = 'success';
												break;
											case 2:
												statusText = 'Forward';
												badgeClass = 'primary';

												break;
											default:
												statusText = 'Pending';
												badgeClass = 'secondary';
												break;
										}

										var cardHtml = `
												<div class="card_inner">
													<div class="body_card">
														<p class="title_card">${card.role}</p>
														<span class="badge badge-${badgeClass}">${statusText}</span>														
														<p class="title_card">${card.created ? formatDate(card.created) : ''}</p>
													</div>
												</div>
											`;
										$('.card_main').append(cardHtml);

										var optionHtml = `<option value="${card.member_id}">${card.role}</option>`;
										$('#forward').append(optionHtml);
									});
								}
							} else {
								$('#open_access_permission .modal-body').html('<p>Data not found. Try again...</p>');
								$('#loader-overlay').hide();
							}
						} else {
							$('#open_access_permission .modal-body').html('<p>Data not found. Try again...</p>');
							$('#loader-overlay').hide();
						}
						$('#open_access_permission').modal('show');

					},

					error: function(xhr, status, error) {
						$('#open_access_permission').modal('show');
						$('#open_access_permission .modal-body').html('<p>An error occurred. Try again...</p>');
						$('#loader-overlay').hide();
					}
				});

			});

			$(function() { //must
				$("#approvalForm").validate({
					errorElement: 'span',
					errorClass: 'help-block error-help-block',
					errorPlacement: function(error, element) {
						if (element.hasClass('select2')) {
							error.insertAfter(element.parent().find('span.select2'));
						} else if (element.parent('.input-group').length ||
							element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
							error.insertAfter(element.parent());
							// else just place the validation message immediatly after the input
						} else {
							error.insertAfter(element);
						}
					},

					rules: {
						comment: {
							required: true
						},
						// inward_date: {
						// 	required: function(element) {
						// 		return $("#action").val() === "1";
						// 	}
						// },
						inward_date: {
							required: function(element) {
								var actionVal = $("#action").val();
								return actionVal === "1" || actionVal === "2";
							}
						},
						developer_comment: {
							required: function(element) {
								return $("#action").val() === "3";
							}
						}
					},
					messages: {
						comment: {
							required: 'Comment is required'
						},
						inward_date: {
							required: 'Select Inward Date'
						},
						developer_comment: {
							required: 'Required'
						}
					},

					highlight: function(element) {
						$(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // add the Bootstrap error class to the control group
					},

					success: function(element) {
						$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // remove the Boostrap error class from the control group
					},

					focusInvalid: true,
					submitHandler: function(form) {

						$('.alert').hide();
						$('#loader-overlay').show();
						$.ajax({
							type: "POST",
							url:  $('#approvalForm').attr('action'),
							data: new FormData(form),
							contentType: false,
							cache: false,
							processData: false,

							beforeSend: function(xhr) {
								xhr.setRequestHeader(
									'X-CSRF-Token',
									<?php echo json_encode($this->request->param('_csrfToken')); ?>
								);
							},
							success: function(response) {
								var res = JSON.parse(response);
								var message = res.message;
								$('#loader-overlay').hide();
								if (res.status == 1) {
									$('#approvalForm')[0].reset();
									$('.success-alert').show().html(message);
									setTimeout(function() {
										$('.success-alert').hide().html(message);
										$('#open_access_permission').modal('hide');
										location.reload();
									}, 2000);
								} else {
									$('.danger-alert').show().html(message);
									setTimeout(function() {
										$('.danger-alert').hide().html(message);
									}, 2000);
								}
							},
							error: function(err) {
								$('.danger-alert').show().html('Ooops...Something went wrong. Please try again.');

							}
						});
					}

				});
			});

			$('#msgButton').on('click', function() {
				var applicationId 	= $(this).data('id');
				var devAppId 		= $(this).attr('dev-app-id');
				var appType			= $(this).attr('app-type');
				var dataAction 		= $(this).attr('data-action');
				$.ajax({
					type: "POST",
					url: dataAction, //"/ApplicationDeveloperPermission/developer_form_approval_msg",
					data: {
						id: applicationId,
						dev_app_id: devAppId,
						app_type: appType
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {

						var data = JSON.parse(response); // Assuming response is JSON

						// Clear existing content
						$('#commentModal .modal-body').empty();

						// Loop through data and create cards
						data.forEach(function(item) {
							var statusText, badgeClass;
							switch (item.status) {
								case 1:
									statusText = 'Approved';
									badgeClass = 'success';
									break;
								case 2:
									statusText = `Forward To ${item.forward_role} on ${formatDate(item.created)}`;
									badgeClass = 'primary';
									break;
								default:
									statusText = 'Pending';
									badgeClass = 'secondary';
									break;
							}

							var cardHtml = `
								<div class="card">
									<div class="card-body">
										<h5 class="card-title">${item.member_role}</h5>
										<p class="card-text">${item.query_msg}</p>
										<span class="badge badge-${badgeClass} badge-custom">${statusText} </span>
									</div>
								</div><hr>
							`;
							$('#commentModal .modal-body').append(cardHtml);
						});

						// Show the modal
						$('#commentModal').modal('show');
					},
					error: function(xhr, status, error) {
						$('#modal-body').html('<p>An error occurred while fetching the data.</p>');
					}
				});

			});

			tinymce.init({
				selector: 'textarea#editor', // Change this value according to your HTML
				plugins: [
					'advlist autolink lists link image charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table paste code help wordcount',
					'pagebreak'
				],
				toolbar: 'undo redo | formatselect | ' +
					'bold italic backcolor | alignleft aligncenter ' +
					'alignright alignjustify | bullist numlist outdent indent | ' +
					'removeformat | table | pagebreak | pagenum',
				height: 500,
				//content_css: '//www.tiny.cloud/css/codepen.min.css'
			});
			$('.wind_dp_letter').on('click', function() {
				var applicationId = $(this).data('id');
				var devAppId = $(this).attr('dev-app-id');
				var appType = $(this).attr('app-type');

				$('#loader-overlay').show();
				$.ajax({
					type: "POST",
					url: "/ApplicationDeveloperPermission/create_wind_hybrid_dp_letter",
					data: {
						application_id: applicationId,
						dev_app_id: devAppId,
						app_type: appType
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						if (response) {
							$('#loader-overlay').hide();
							var data = JSON.parse(response);

							if (data && data.html) {
								tinymce.get('editor').setContent(data.html);
								$('#dp_application_id').val(applicationId);
								$('#dp_app_type').val(appType);
								$('#dp_dev_app_id').val(devAppId);
							} else {
								$('#wind_dp_letter .modal-body').html('<p>Data not found. Try again...</p>');
								$('#loader-overlay').hide();
							}
						} else {
							$('#wind_dp_letter .modal-body').html('<p>Data not found. Try again...</p>');
							$('#loader-overlay').hide();
						}
						$('#wind_dp_letter').modal('show');
					},
					error: function(xhr, status, error) {
						$('#wind_dp_letter').modal('show');
						$('#wind_dp_letter .modal-body').html('<p>An error occurred. Try again...</p>');
						$('#loader-overlay').hide();
					}
				});
			});
			$('#dpForm').on('submit', function(e) {
				e.preventDefault();
				tinymce.triggerSave();
				var formData = $(this).serialize();
				$.ajax({
					type: 'POST',
					url: '/ApplicationDeveloperPermission/save_wind_hybrid_dp_letter',
					data: formData,
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var res = JSON.parse(response);
						var message = res.message;
						$('#loader-overlay').hide();
						if (res.status == 1) {
							$('#dpForm')[0].reset();
							$('.success-alert').show().html(message);
							setTimeout(function() {
								$('.success-alert').hide().html(message);
								$('#wind_dp_letter').modal('hide');
								location.reload();
							}, 2000);
						} else {
							$('.danger-alert').show().html(message);
							setTimeout(function() {
								$('.danger-alert').hide().html(message);
							}, 2000);
						}
					},
					error: function(err) {
						$('.danger-alert').show().html('Ooops...Something went wrong. Please try again.');
					}
				});
			});

			//Transfer DP

			$('.transfer_dp').on('click', function() {
				var applicationId = $(this).data('id');
				$('#loader-overlay').show();

				$.ajax({
					type: "POST",
					url: "/ApplicationDeveloperPermission/transfer_developer_permission",
					data: {
						application_id: applicationId
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						$('#loader-overlay').hide();
						if (response) {
							var response = JSON.parse(response);
							const data = response.application_geo_loc;
							const developerDetails = response.developer_details;

							if ($.fn.DataTable.isDataTable('#tbl_wind_info')) {
								$('#tbl_wind_info').DataTable().clear().destroy();
							}
							$('#tbl_wind_info').DataTable({
								data: data,
								columns: [{
										data: null,
										render: function(data, type, row) {
											return row.transferee_name ? '' : `<input type="checkbox" name="geo_loc_ids[]" class="form-check-input checkboxes" value="${row.id}">`;
										}
									},
									{
										data: 'x_cordinate'
									},
									{
										data: 'y_cordinate'
									},
									{
										data: 'manufacturer_master.name',
										render: function(data) {
											return data || '';
										}
									},
									{
										data: 'wtg_model'
									},
									{
										data: 'wtg_capacity'
									},
									{
										data: 'wtg_rotor_dimension'
									},
									{
										data: 'wtg_hub_height'
									},
									{
										data: 'land_survey_no'
									},
									{
										data: 'geo_village'
									},
									{
										data: 'geo_taluka'
									},
									{
										data: 'geo_district'
									},
									{
										data: 'transferee_name',
										render: function(data) {
											return data || '-';
										}
									},
									{
										data: 'final_registration_no',
									},
									{
										data: 'transfer_status',
										render: function(data) {
											let statusText = '';
											let badgeClass = '';
											if (data == 0) {
												statusText = 'Pending';
												badgeClass = 'badge-warning';
											} else if (data == 1) {
												statusText = 'Accepted';
												badgeClass = 'badge-success';
											} else if (data == 2) {
												statusText = 'Rejected';
												badgeClass = 'badge-danger';
											} else {
												statusText = 'Not Transfer';
												badgeClass = 'badge-primary';
											}
											return `<span class="badge ${badgeClass}">${statusText}</span>`;
										}
									}
								],
								paging: true,
								searching: true,
								ordering: true,
								info: true,
							});
							// Populate the developer dropdown
							$('#developer').empty();
							$('#developer').append('<option value="">Select Developer</option>');
							$.each(developerDetails, (id, name) => {
								$('#developer').append(`<option value="${id}">${name}</option>`);
							});

						} else {
							$('#transfer_dp .modal-body').html('<p>Data not found. Try again...</p>');
						}
						$('#transfer_dp').modal('show');
					},
					error: function(xhr, status, error) {
						$('#transfer_dp').modal('show');
						$('#transfer_dp .modal-body').html('<p>An error occurred. Try again...</p>');
						$('#loader-overlay').hide();
					}
				});
			});


			$('.transfer_permission').on('click', function() {
				var applicationId 	= $(this).data('id');
				var tranDevAppId 	= $(this).attr('tran-dev-app-id');
				var appType 		= $(this).attr('app-type');
				
				$('#loader-overlay').show();

				$.ajax({
					type: "POST",
					url: "/TransferDeveloperPermission/developer_form_approval",
					data: {
						id: applicationId,
						tran_dev_app_id: tranDevAppId,
						app_type: appType
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {

						if (response) {
							$('#loader-overlay').hide();
							var data = JSON.parse(response);
							console.log(data);
							if (data && data.district && data.taluka && data.city && data.appType && data.substation && data.endUse && data.roles) {
								$('#district').text(data.district);
								$('#taluka').text(data.taluka);
								$('#appcity').text(data.city);
								$('#appType').text(data.appType);
								$('#substation').text(data.substation);
								$('#endUse').text(data.endUse);
								$('#application_id').val(applicationId);
								$('#app_type').val(appType);
								$('#dev_app_id').val(tranDevAppId);
								$('#msgButton').attr('data-id', applicationId);
								$('#msgButton').attr('dev-app-id', tranDevAppId);
								$('#msgButton').attr('app-type', appType);
								$('#msgButton').attr('data-action', '/TransferDeveloperPermission/developer_form_approval_msg');
								$('#inward_date').val(data.inward_date);
								$('#approvalForm').attr('action', 'TransferDeveloperPermission/developer_application_query');
								$('.tran-title').text('Transfer Developer Application Confirmation')

								if (appType == 3) {
									$('#viewButton').attr('href', '/TransferDeveloperPermission/wind_view/' + tranDevAppId + '/0');
									$('#editButton').attr('href', '/transfer-developer-permission/' + applicationId + '/' + tranDevAppId);
								}
								if (appType == 4) {
									$('#viewButton').attr('href', '/TransferDeveloperPermission/wind_view/' + tranDevAppId + '/0');
									$('#editButton').attr('href', '/transfer-developer-permission/' + applicationId + '/' + tranDevAppId);
								}
								if (data.jpoFlag == 1) {
									//$('.inward').hide();
									$('#action option[value="1"]').remove();
									if ($('#action option[value="3"]').length === 0) {

										$('#action').append('<option value="3">Query</option>');
									}
								}
								if (data.jpoFlag == 0) {
									$('.inward').hide();
								}
								// Clear the existing cards
								$('.card_main').empty();
								var cards = data.roles;
								if (cards.length > 0) {
									cards.forEach(function(card) {
										var statusText, badgeClass;
										switch (card.status) {
											case 1:
												statusText = 'Approved';
												badgeClass = 'success';
												break;
											case 2:
												statusText = 'Forward';
												badgeClass = 'primary';

												break;
											default:
												statusText = 'Pending';
												badgeClass = 'secondary';
												break;
										}

										var cardHtml = `
												<div class="card_inner">
													<div class="body_card">
														<p class="title_card">${card.role}</p>
														<span class="badge badge-${badgeClass}">${statusText}</span>														
														<p class="title_card">${card.created ? formatDate(card.created) : ''}</p>
													</div>
												</div>
											`;
										$('.card_main').append(cardHtml);

										var optionHtml = `<option value="${card.member_id}">${card.role}</option>`;
										$('#forward').append(optionHtml);
									});
								}
							} else {
								$('#open_access_permission .modal-body').html('<p>Data not found. Try again...</p>');
								$('#loader-overlay').hide();
							}
						} else {
							$('#open_access_permission .modal-body').html('<p>Data not found. Try again...</p>');
							$('#loader-overlay').hide();
						}
						$('#open_access_permission').modal('show');

					},

					error: function(xhr, status, error) {
						$('#open_access_permission').modal('show');
						$('#open_access_permission .modal-body').html('<p>An error occurred. Try again...</p>');
						$('#loader-overlay').hide();
					}
				});
			});
			tinymce.init({
				selector: 'textarea#tpeditor', // Change this value according to your HTML
				plugins: [
					'advlist autolink lists link image charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table paste code help wordcount',
					'pagebreak'
				],
				toolbar: 'undo redo | formatselect | ' +
					'bold italic backcolor | alignleft aligncenter ' +
					'alignright alignjustify | bullist numlist outdent indent | ' +
					'removeformat | table | pagebreak | pagenum',
				height: 500,
				//content_css: '//www.tiny.cloud/css/codepen.min.css'
			});
			$('.wind_tp_letter').on('click', function() {
				var applicationId = $(this).data('id');
				var tranDevAppId = $(this).attr('tran-dev-app-id');
				var appType = $(this).attr('app-type');

				$('#loader-overlay').show();
				$.ajax({
					type: "POST",
					url: "/TransferDeveloperPermission/create_wind_hybrid_tp_letter",
					data: {
						application_id: applicationId,
						tran_dev_app_id: tranDevAppId,
						app_type: appType
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						if (response) {
							$('#loader-overlay').hide();
							var data = JSON.parse(response);

							if (data && data.html) {
								tinymce.get('tpeditor').setContent(data.html);
								$('#tp_application_id').val(applicationId);
								$('#tp_app_type').val(appType);
								$('#tp_dev_app_id').val(tranDevAppId);
							} else {
								$('#wind_tp_letter .modal-body').html('<p>Data not found. Try again...</p>');
								$('#loader-overlay').hide();
							}
						} else {
							$('#wind_tp_letter .modal-body').html('<p>Data not found. Try again...</p>');
							$('#loader-overlay').hide();
						}
						console.log("here");
						$('#wind_tp_letter').modal('show');
					},
					error: function(xhr, status, error) {
						$('#wind_tp_letter').modal('show');
						$('#wind_tp_letter .modal-body').html('<p>An error occurred. Try again...</p>');
						$('#loader-overlay').hide();
					}
				});
			});
			$('#tpForm').on('submit', function(e) {
				e.preventDefault();
				tinymce.triggerSave();
				var formData = $(this).serialize();
				$.ajax({
					type: 'POST',
					url: '/TransferDeveloperPermission/save_wind_hybrid_tp_letter',
					data: formData,
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var res = JSON.parse(response);
						var message = res.message;
						$('#loader-overlay').hide();
						if (res.status == 1) {
							$('#tpForm')[0].reset();
							$('.success-alert').show().html(message);
							setTimeout(function() {
								$('.success-alert').hide().html(message);
								$('#wind_tp_letter').modal('hide');
								location.reload();
							}, 2000);
						} else {
							$('.danger-alert').show().html(message);
							setTimeout(function() {
								$('.danger-alert').hide().html(message);
							}, 2000);
						}
					},
					error: function(err) {
						$('.danger-alert').show().html('Ooops...Something went wrong. Please try again.');
					}
				});
			});
			$('.checkAll').click(function() {
				if (this.checked) {
					$(".checkboxes").prop("checked", true);
				} else {
					$(".checkboxes").prop("checked", false);
				}
			});
			// Filter table rows based on input
			$('#tableFilter').on('keyup', function() {
				var value = $(this).val().toLowerCase();
				$('#tbl_wind_info tbody tr').filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
				});
			});
			$(function() {
				$.validator.addMethod('checkboxRequired', function(value, element) {
					return $('#tbl_wind_info tbody').find('input[type="checkbox"]:checked').length > 0;
				}, 'Please select at least one item.');

				$("#transfer_dp_form").validate({
					errorElement: 'span',
					errorClass: 'help-block error-help-block',
					errorPlacement: function(error, element) {
						if (element.hasClass('select2')) {
							error.insertAfter(element.parent().find('span.select2'));
						} else if (element.prop('type') === 'checkbox') {
							error.appendTo($('#checkbox-error-container')); // Add a container for error messages
						} else {
							error.insertAfter(element);
						}
					},

					rules: {
						developer: {
							required: true
						},
						'geo_loc_ids[]': {
							checkboxRequired: true // Using custom validator for checkbox selection
						}
					},
					messages: {
						developer: {
							required: 'Select Developer'
						},
						'geo_loc_ids[]': {
							checkboxRequired: 'Please select at least one item.'
						}
					},

					highlight: function(element) {
						$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
					},

					success: function(element) {
						$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
					},

					focusInvalid: true,
					submitHandler: function(form) {
						$('.alert').hide();
						$.ajax({
							type: "POST",
							url: "/ApplicationDeveloperPermission/save_wtg_transfer",
							data: new FormData(form),
							contentType: false,
							cache: false,
							processData: false,
							beforeSend: function(xhr) {
								xhr.setRequestHeader(
									'X-CSRF-Token',
									<?php echo json_encode($this->request->param('_csrfToken')); ?>
								);
							},
							success: function(response) {
								var res = JSON.parse(response);
								var message = res.message;
								if (res.status == 1) {
									$('#transfer_dp_form')[0].reset();
									$('.success-alert').show().html(message);
									setTimeout(function() {
										$('.success-alert').hide().html(message);
										$('#transfer_dp').modal('hide');
										location.reload();
									}, 2000);
								} else {
									$('.danger-alert').show().html(message);
									setTimeout(function() {
										$('.danger-alert').hide().html(message);
									}, 2000);
								}
							},
							error: function(err) {
								$('.danger-alert').show().html('Ooops...Something went wrong. Please try again.');
							}
						});
					}
				});
			});




			function formatDate(timestamp) {
				var date = new Date(timestamp);
				var day = date.getDate();
				var month = date.getMonth() + 1;
				var year = date.getFullYear();
				var hours = date.getHours();
				var minutes = date.getMinutes();

				if (day < 10) day = '0' + day;
				if (month < 10) month = '0' + month;
				if (hours < 10) hours = '0' + hours;
				if (minutes < 10) minutes = '0' + minutes;

				return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
			}

			function formatDateTime(timestamp) {
				// Parse the timestamp
				var date = new Date(timestamp);

				// Get day, month, year, hours, and minutes
				var day = date.getDate();
				var month = date.getMonth() + 1; // Months are zero-based
				var year = date.getFullYear().toString().substr(-2); // Get last 2 digits of year
				var hours = date.getHours();
				var minutes = date.getMinutes();

				// Add leading zeros if needed
				if (day < 10) day = '0' + day;
				if (month < 10) month = '0' + month;
				if (hours < 10) hours = '0' + hours;
				if (minutes < 10) minutes = '0' + minutes;

				// Format the date and time as dd-mm-yy hh:mm
				return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
			}
			$('#action').change(function() {
				if ($(this).val() == '2') {
					$('.comment-group').show();
					$('.developer_comment-group').hide();
				} else if ($(this).val() == '3') {
					$('.comment-group').hide();
					$('.developer_comment-group').show();
				} else {
					$('#comment').val('');
					$('#forward').val('');
					$('.developer_comment').val('');
					$('.comment-group').hide();
				}
			});

			$('.upload_dp_letter').on('click', function() {
				var applicationId = $(this).data('id');
				var devAppId = $(this).attr('dev-app-id');
				var appType = $(this).attr('app-type');
				$('#upload_dp_application_id').val(applicationId);
				$('#upload_dp_app_type').val(appType);
				$('#upload_dp_dev_app_id').val(devAppId);
				$('#upload_dp_letter').modal('show');
				$('#upload_dp_letter_form')[0].reset();
			});
			$(function() { //must
				$("#upload_dp_letter_form").validate({
					errorElement: 'span',
					errorClass: 'help-block error-help-block',
					errorPlacement: function(error, element) {
						// Place the error message after the input-group div
						if (element.attr("name") == "upload_signed_dp_letter") {
							error.insertAfter(element.closest('.input-group'));
						} else {
							error.insertAfter(element);
						}
					},
					rules: {
						upload_signed_dp_letter: {
							required: true
						}
					},
					messages: {
						upload_signed_dp_letter: {
							required: 'Upload Signed DP Letter is required'
						}
					},
					highlight: function(element) {
						$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
					},
					success: function(label, element) {
						$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
						label.remove(); // Remove the error message label
					},

					focusInvalid: true,
					submitHandler: function(form) {

						$('.alert').hide();
						$('#loader-overlay').show();
						$.ajax({
							type: "POST",
							url: "/ApplicationDeveloperPermission/upload_dp_letter",
							data: new FormData(form),
							contentType: false,
							cache: false,
							processData: false,

							beforeSend: function(xhr) {
								xhr.setRequestHeader(
									'X-CSRF-Token',
									<?php echo json_encode($this->request->param('_csrfToken')); ?>
								);
							},
							success: function(response) {
								var res = JSON.parse(response);
								var message = res.message;
								$('#loader-overlay').hide();
								if (res.status == 1) {
									$('#upload_dp_letter_form')[0].reset();
									$('.success-alert').show().html(message);
									setTimeout(function() {
										$('.success-alert').hide().html(message);
										$('#upload_dp_letter').modal('hide');
										location.reload();
									}, 2000);
								} else {
									$('.danger-alert').show().html(message);
									setTimeout(function() {
										$('.danger-alert').hide().html(message);
									}, 2000);
								}
							},
							error: function(err) {
								$('.danger-alert').show().html('Ooops...Something went wrong. Please try again.');
								$('#loader-overlay').hide();

							}
						});
					}

				});
			});

			$("#upload_signed_dp_letter").fileinput({
				showUpload: false,
				showPreview: false,
				dropZoneEnabled: false,
				mainClass: "input-group-s",
				allowedFileExtensions: ["pdf"],
				elErrorContainer: '#upload_signed_dp_letter-file-errors',
				maxFileSize: '2048',
			});

			//Transfer Letter
			$('.upload_tp_letter').on('click', function() {
				var applicationId = $(this).data('id');
				var devAppId = $(this).attr('tran-dev-app-id');
				var appType = $(this).attr('app-type');
				$('#upload_tp_application_id').val(applicationId);
				$('#upload_tp_app_type').val(appType);
				$('#upload_tp_dev_app_id').val(devAppId);
				$('#upload_tp_letter').modal('show');
				$('#upload_tp_letter_form')[0].reset();
			});
			$(function() { //must
				$("#upload_tp_letter_form").validate({
					errorElement: 'span',
					errorClass: 'help-block error-help-block',
					errorPlacement: function(error, element) {
						// Place the error message after the input-group div
						if (element.attr("name") == "upload_signed_tp_letter") {
							error.insertAfter(element.closest('.input-group'));
						} else {
							error.insertAfter(element);
						}
					},
					rules: {
						upload_signed_tp_letter: {
							required: true
						}
					},
					messages: {
						upload_signed_dp_letter: {
							required: 'Upload Signed TP Letter is required'
						}
					},
					highlight: function(element) {
						$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
					},
					success: function(label, element) {
						$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
						label.remove(); // Remove the error message label
					},

					focusInvalid: true,
					submitHandler: function(form) {

						$('.alert').hide();
						$('#loader-overlay').show();
						$.ajax({
							type: "POST",
							url: "/TransferDeveloperPermission/upload_tp_letter",
							data: new FormData(form),
							contentType: false,
							cache: false,
							processData: false,

							beforeSend: function(xhr) {
								xhr.setRequestHeader(
									'X-CSRF-Token',
									<?php echo json_encode($this->request->param('_csrfToken')); ?>
								);
							},
							success: function(response) {
								var res = JSON.parse(response);
								var message = res.message;
								$('#loader-overlay').hide();
								if (res.status == 1) {
									$('#upload_tp_letter_form')[0].reset();
									$('.success-alert').show().html(message);
									setTimeout(function() {
										$('.success-alert').hide().html(message);
										$('#upload_tp_letter').modal('hide');
										location.reload();
									}, 2000);
								} else {
									$('.danger-alert').show().html(message);
									setTimeout(function() {
										$('.danger-alert').hide().html(message);
									}, 2000);
								}
							},
							error: function(err) {
								$('.danger-alert').show().html('Ooops...Something went wrong. Please try again.');
								$('#loader-overlay').hide();

							}
						});
					}

				});
			});

			$("#upload_signed_tp_letter").fileinput({
				showUpload: false,
				showPreview: false,
				dropZoneEnabled: false,
				mainClass: "input-group-s",
				allowedFileExtensions: ["pdf"],
				elErrorContainer: '#upload_signed_tp_letter-file-errors',
				maxFileSize: '2048',
			});

		});
		//try

		$(document).ready(function() {

			// Handle click on the Comment button
			$('.comment-button').click(function() {

				$('#comment_modal').modal('show'); // Show the comment modal
			});

			// Handle form submission
			$('#comment-form').submit(function(event) {
				event.preventDefault();
				var comment = $('#comment-text').val().trim();
				if (comment === '') {
					$('#comment-error-msg').text('Please enter a comment.').show();
					return;
				}
				// Assuming Ajax submission
				$.ajax({
					type: "POST",
					url: "/Applications/ReplayMessage",
					data: $("#" + fromobj).serialize(),
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
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
				//  // $.ajax({
				//  //  url: '/Applications/UploadDocument', // Replace with your actual server endpoint
				//  //  type: 'POST',
				//  //  data: {
				//  //      comment: comment
				//  //  },
				//  //  success: function(response) {
				//  //      console.log('Comment submitted successfully.');
				//  //      $('#comment-modal').modal('hide'); // Close the comment modal
				//  //      // Optionally, do something with the response from server
				//  //  },
				//  //  error: function(xhr, status, error) {
				//  //      console.error('Error submitting comment:', error);
				//  //      // Handle error case
				//  //  }
				//  // });
			});
		});

		$('.cross').click(function() {
			// Reload the page when clicked
			location.reload();
		});
		$('#stu_connectivity_date').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
		$('#stu_validity_date').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
		$('#PC_date').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
		$('#ctu_connectivity_date').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
		$('#ctu_validity_date').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
		$(document).ready(function() {
			$("input[name$='has_connectivity']").click(function() {
				var test = $(this).val();
				console.log(test);
				if (test == 1) {
					$(".STU").show();
					$(".STUGUVNL").hide();
				}
				if (test == 2) {
					$(".STU").hide();
					$(".STUnew").hide();
					$(".STUold").hide();
					$(".STUGUVNL").show();
				}

			});
			$("input[name$='connectivity_type']").click(function() {
				var test = $(this).val();
				console.log(test);
				if (test == 1) {
					$(".STUold").show();
					$(".STUnew").hide();
					$(".connectivity_opt").hide();
				}
				if (test == 2) {
					$(".STUold").hide();
					$(".STUnew").show();
					$(".connectivity_opt").hide();
				}

			});
		});
		$(".showModel").click(function() {

			var modelheader = $(this).data("title");
			var modelUrl = $(this).data("url");
			var defaultURL = "window.location.href=\'<?php echo URL_HTTP; ?>applications-list\'";
			document_window = $(window).width() - $(window).width() * 0.05;
			document_height = $(window).height() - $(window).height() * 0.20;
			modal_body = '<div class="modal-header" style="min-height: 45px;">' +
				'<h4 class="modal-title">' + modelheader + '</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
				'</div>' +
				'<div class="modal-body">' +
				'<iframe id="TaskIFrame" width="100%;" src="' + modelUrl + '" height="50%;" frameborder="0" allowtransparency="true"></iframe>' +
				'</div>';

			$('#myModal').find(".modal-content").html(modal_body);
			$('#myModal').modal('show');
			$('#myModal').find(".modal-dialog").attr('style', "min-width:" + document_window + "px !important;");
			$('#myModal').find(".modal-body").attr('style', "height:" + document_height + "px !important;");
			return false;
		});
		window.closeModal = function() {
			$('#myModal').modal('hide');
		};

		$("#stunew_file1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#stunew_file1-file-errors',
			maxFileSize: '2048',
		});
		$("#CTUStep2file1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#CTUStep2file1-file-errors',
			maxFileSize: '2048',
		});
		$("#CTUStep2file2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#CTUStep2file2-file-errors',
			maxFileSize: '2048',
		});
		$("#CTUStep2file3").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#CTUStep2file3-file-errors',
			maxFileSize: '2048',
		});
		$("#CTUfile1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#CTUfile1-file-errors',
			maxFileSize: '2048',
		});
		$("#CTUfile1_ip").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#CTUfile1_ip-file-errors',
			maxFileSize: '2048',
		});
		$("#CTUfile1_fp").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#CTUfile1_fp-file-errors',
			maxFileSize: '2048',
		});
		$("#Wheeling_file").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#Wheeling_file-file-errors',
			maxFileSize: '2048',
		});
		$("#meter_file").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#meter_file-file-errors',
			maxFileSize: '2048',
		});
		$("#power_injection_file").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#power_injection_file-file-errors',
			maxFileSize: '2048',
		});
		$("#bpta_file1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#bpta_file1-file-errors',
			maxFileSize: '2048',
		});
		$("#bpta_file2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#bpta_file2-file-errors',
			maxFileSize: '2048',
		});
		$("#pc_upload_file").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#pc_upload_file-file-errors',
			maxFileSize: '2048',
		});
		$("#GNA_file1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#GNA_file1-file-errors',
			maxFileSize: '2048',
		});
		$("#GNA_file2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#GNA_file2-file-errors',
			maxFileSize: '2048',
		});
		$("#STUfile1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#STUfile1-file-errors',
			maxFileSize: '2048',
		});
		$("#STUfile2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#STUfile2-file-errors',
			maxFileSize: '2048',
		});
		$("#TPfile").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#TPfile-file-errors',
			maxFileSize: '2048',
		});
		$("#step12").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step12-file-errors',
			maxFileSize: '2048',
		});
		$("#step13").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step13-file-errors',
			maxFileSize: '2048',
		});
		$("#step14").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step14-file-errors',
			maxFileSize: '2048',
		});
		$("#step21").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step21-file-errors',
			maxFileSize: '2048',
		});
		$("#step22").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step22-file-errors',
			maxFileSize: '2048',
		});
		$("#step23").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step23-file-errors',
			maxFileSize: '2048',
		});
		$("#step24").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#step24-file-errors',
			maxFileSize: '2048',
		});
		$("#sldc_file1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#sldc_file1-file-errors',
			maxFileSize: '2048',
		});
		$("#sldc_file2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#sldc_file2-file-errors',
			maxFileSize: '2048',
		});
		$("#sldc_file3").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#sldc_file3-file-errors',
			maxFileSize: '2048',
		});
		$("#sldc_file4").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#sldc_file4-file-errors',
			maxFileSize: '2048',
		});
		$("#sldc_file5").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#sldc_file5-file-errors',
			maxFileSize: '2048',
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
		$("#other_docfile").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-lg",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#other_docfile-file-errors',
			maxFileSize: '1024',
		});
		$(".Varify_Otp").click(function() {
			$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$("#VarifyOtpForm").find("#message_error").html('');
			var application_id = $(this).attr("data-id");
			$("#VarifyOtp_application_id").val(application_id);
		});

		$(".varifyotp_btn").click(function() {
			var fromobj = $(this).attr("data-form-name");
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$("#VarifyOtpForm").find("#message_error").html('');
			var otp_data = $("#otp").val();
			if (otp_data.length < 1) {
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("OTP is required field.");
				$("#" + fromobj).find("#message_error").removeClass("hide");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/VarifyOtp",
					data: $("#" + fromobj).serialize(),
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
		$(".uploaddocument").click(function() {
			var application_id = $(this).attr("data-id");
			$("#upload_application_id").val(application_id);
		});
		$(".uploaddocument_btn").click(function() {
			var form = $('#UploadDocumentForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$(".uploaddocument_btn").attr('disabled', 'disabled');
			$.ajax({
				type: "POST",
				url: "/Applications/UploadDocument",
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
		$(".mapDiscom").click(function() {
			var application_id = $(this).attr("data-id");
			$("#mapdiscom_application_id").val(application_id);
		});
		$(".map_discom_btn").click(function() {
			var form = $('#mapDiscomForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$(".map_discom_btn").attr('disabled', 'disabled');
			$.ajax({
				type: "POST",
				url: "/Applications/mapDiscom",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#mapDiscomForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#mapDiscomForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".map_discom_btn").removeAttr('disabled');
					}

				}
			});

		});
		$(document).ready(function() {
			$('.btn-download').click(function() {
				$("#download").val(1);
				$("#form-main").submit();
				return false;
			});
			$('.chosen-select').chosen();
			$('.chosen-select-deselect').chosen({
				allow_single_deselect: true
			});

		});
		$('a[rel="viewView"]').click(function() {
			$.fancybox({
				'autoDimensions': true,
				'href': this.href,
				'width': 700,
				'type': 'iframe',
				'arrows': false,
				'scrolling': false,
				'autoSize': true,
				'mouseWheel': false
			});
			return false;
		});
		$(function() {
			$('[data-toggle="popover"]').popover();
		});
		$(".ReplayMessage").click(function() {
			var applicationid = $(this).attr("data-id");
			$("#ReplayMessage_application_id").val(applicationid);
			$("#reply_message").html($("#send_application_msg_" + applicationid).html());
		});
		$(".replaymessage_btn").click(function() {
			var fromobj = $(this).attr("data-form-name");
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			var messagebox = $("#" + fromobj).find(".message").val();
			if (messagebox.length < 1) {
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("Message is required field.");
				$("#" + fromobj).find("#message_error").removeClass("hide");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/ReplayMessage",
					data: $("#" + fromobj).serialize(),
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
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

		$(".ViewMessage").click(function() {
			var application_id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: "/Applications/GetMessages/" + $(this).attr("data-id"),
				beforeSend: function(xhr) {
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?= json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					if (result.html != '') {
						$("#ViewMessage").find(".modal-body").html(result.html);
					}
				}
			});
		});
		$(".ViewLastResponse").click(function() {
			var application_id = $(this).attr("data-id");
			$("#ViewLastResponse").find(".modal-body").html('');
			$.ajax({
				type: "POST",
				url: "/Applications/GetLastResponse/" + $(this).attr("data-id"),
				success: function(response) {
					var result = $.parseJSON(response);
					if (result.html != '') {
						$("#ViewLastResponse").find(".modal-body").html(result.html);
					}
				}
			});
		});
		$(".ViewCustomerResponse").click(function() {
			var application_id = $(this).attr("data-id");
			$("#ViewCustomerResponse").find(".modal-body").html('');
			$.ajax({
				type: "POST",
				url: "/Applications/GetCustomerResponse/" + $(this).attr("data-id"),
				success: function(response) {
					var result = $.parseJSON(response);
					if (result.html != '') {
						$("#ViewCustomerResponse").find(".modal-body").html(result.html);
					}
				}
			});
		});
		$(".SendMessage").click(function() {
			var application_id = $(this).attr("data-id");
			$("#SendMessage_application_id").val(application_id);
		});
		$(".sendmessage_btn").click(function() {
			var fromobj = $(this).attr("data-form-name");
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			var messagebox = $("#" + fromobj).find(".messagebox").val();
			if (messagebox.length < 1) {
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("Message is required field.");
				$("#" + fromobj).find("#message_error").removeClass("hide");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/SendMessage",
					data: $("#" + fromobj).serialize(),
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
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
		$(".otherdocument").click(function() {
			var application_id = $(this).attr("data-id");
			$("#other_application_id").val(application_id);
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
				url: "/Applications/OtherDocument",
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
		$(".TP").click(function() {
			var application_id = $(this).attr("data-id");
			$("#TP_application_id").val(application_id);
		});
		$(".TP_btn").click(function() {
			var form = $('#TPForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			var fromobj = $(this).attr("data-form-name");

			var TPfile = $("#" + fromobj).find("#TPfile").val();

			if (TPfile != '' && $("#" + fromobj).find("#titletp").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}

			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/TPDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#TPForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#TPForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".TP_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".Substation").click(function() {
			var application_id = $(this).attr("data-id");
			$("#Substation_application_id").val(application_id);
		});
		$(".Substation_btn").click(function() {
			var form = $('#SubstationForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			var fromobj = $(this).attr("data-form-name");

			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/Substation_save",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#SubstationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#SubstationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".Substation_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".STUstep2").click(function() {
			var application_id = $(this).attr("data-id");
			$("#STUstep2_application_id").val(application_id);
		});
		$(".STUstep2_btn").click(function() {
			var form = $('#STUstep2Form');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			var fromobj = $(this).attr("data-form-name");

			var step21 = $("#" + fromobj).find("#step21").val();
			var step22 = $("#" + fromobj).find("#step22").val();
			var step23 = $("#" + fromobj).find("#step23").val();
			var step24 = $("#" + fromobj).find("#step24").val();

			if (step21 != '' && $("#" + fromobj).find("#title21").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			if (step22 != '' && $("#" + fromobj).find("#title22").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			if (step23 != '' && $("#" + fromobj).find("#title23").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			if (step24 != '' && $("#" + fromobj).find("#title24").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/STUstep2Document",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#STUstep2Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#STUstep2Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".STUstep2_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".STUstep1").click(function() {
			var application_id = $(this).attr("data-id");
			var discom = $(this).attr("data-prod-id");
			console.log(discom);
			$("#STUStep1_application_id").val(application_id);
			$("#STUStep1new_application_id").val(application_id);
			if(discom == 15 || discom == 16 || discom == 17){
				$(".connectivity_opt").hide();
 				$(".STU").show();
			}
		});

		$(".STUstep1_btn").click(function() {
			var form = $('#STUStep1Form');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}

			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/STUStep1Document",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#STUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#STUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".STUstep1_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".STUstep1New_btn").click(function() {
			var form = $('#STUStep1NewForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			var fromobj = $(this).attr("data-form-name");

			var stunew_file1 = $("#" + fromobj).find("#stunew_file1").val();

			// if(stunew_file1 != '' && $("#"+fromobj).find("#stunew_title1").val() == '') {
			// 	$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
			// 	$("#"+fromobj).find("#message_error").html("");
			// 	$("#"+fromobj).find("#message_error").html("Title is required field.");
			// 	return false;
			// }

			console.log("new");

			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/STUStep1newDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#STUStep1NewForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#STUStep1NewForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".STUstep1New_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".STUstep1_btnGUVNL").click(function() {
			var form = $('#STUStep1Form');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			console.log('hii');
			var fromobj = $(this).attr("data-form-name");
			$.ajax({
				type: "POST",
				url: "/Applications/get_details",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function(xhr) {
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					var responseData = $.parseJSON(result.message);
					console.log(responseData);
					if (result.success == 1) {
						console.log(responseData);
						$('#company_name').val(responseData.company_name);
						$('#applicant_name').val(responseData.applicant_name);
						$('#pan_card').val(responseData.pan_card);
						$('#mobile').val(responseData.mobile);
						$('#email_id').val(responseData.email_id);
						$('#address').val(responseData.address);
						$('#city').val(responseData.city);
						$('#state').val(responseData.state);
						$('#gst_no').val(responseData.gst_no);
						$('#prov_proj_no').val(responseData.prov_proj_no);
						$('#applicant_type').val(responseData.applicant_type);
						$('#project_type').val(responseData.project_type);
						$('#project_purpose').val(responseData.project_purpose);
						$('#ss_name').val(responseData.ss_name);
						$('#ss_id').val(responseData.ss_id);
						//$('#proposed_ss').val(responseData.proposed_ss);
						$('#getcogetco_field_office').val(responseData.getcogetco_field_office);
						$('#applied_capacity_ac').val(responseData.applied_capacity_ac);
						$('#applied_capacity_dc').val(responseData.applied_capacity_dc);
						$('#voltage_class').val(responseData.voltage_class);
						$('#pwr_company').val(responseData.pwr_company);
						$('#developer_registration_no').val(responseData.developer_registration_no);

						$('#pancard_doc').val(responseData.pancard_doc);
						$('#undertaking').val(responseData.undertaking);
						$('#board_resolution').val(responseData.board_resolution);
						// console.log(responseData.pancard_doc);
						// $('#pancard_doc').attr('href', responseData.pancard_doc);
						// $('#undertaking').attr('href', responseData.undertaking);
						// $('#board_resolution').attr('href', responseData.board_resolution);

						$('.submitButton').show();
						$('.submitButton').prop('disabled', false);
						$('.fetch').hide();
						$('.fetch').prop('disabled', true);

						$("#STUStep1Form").find("#message_error").html(result.remark).removeClass("hide").addClass('alert').addClass('alert-success');
						//location.reload();
					} else {
						$("#STUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".STUstep1_btn").removeAttr('disabled');
					}
				}
			});
		});
		$(".CTUstep1").click(function() {
			var application_id = $(this).attr("data-id");
			$("#CTUStep1_application_id").val(application_id);
		});
		$(".CTUstep1_btn").click(function() {
			var form = $('#CTUStep1Form');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			console.log('hii');
			var fromobj = $(this).attr("data-form-name");

			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/CTUStepDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#CTUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#CTUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".CTUstep1_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".CTUstep2").click(function() {
			var application_id = $(this).attr("data-id");
			$("#CTUStep2_application_id").val(application_id);

		});
		$(".CTUstep2_btn").click(function() {
			var form = $('#CTUStep2Form');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/CTUStep2Document",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#CTUStep2Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#CTUStep2Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".CTUstep2_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".WHEELING").click(function() {
			var application_id = $(this).attr("data-id");
			$("#WHEELING_application_id").val(application_id);
			console.log(application_id);

		});
		$(".WHEELING_btn").click(function() {
			var form = $('#WHEELINGForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/WHEELINGDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#WHEELINGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#WHEELINGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".WHEELING_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".WHEELINGApproval").click(function() {
			var application_id = $(this).attr("data-id");
			$("#WHEELINGApproval_application_id").val(application_id);
			console.log(application_id);
			$("#wheeling_agreement_document_file").html('');
			$.ajax({
					type: "POST",
					url: "/Applications/fetchWheelingdataDocument",
					data: {'app_id':application_id},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.type == "ok") {
							if(result.document_link!='')
							{
								$("#wheeling_agreement_document_file").html(result.document_link['Wheeling_Agreement_document']);
								
							}
						}
					  
					}
				});

		});
		$(".WHEELINGApproval_btn").click(function() {
			var form = $('#WHEELINGApprovalForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/WHEELINGApprovalDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#WHEELINGApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#WHEELINGApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".WHEELINGApproval_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".BPTA").click(function() {
			var application_id = $(this).attr("data-id");
			$("#BPTA_application_id").val(application_id);
			console.log(application_id);

		});
		$(".BPTA_btn").click(function() {
			var form = $('#BPTAForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/BPTADocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#BPTAForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#BPTAForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".BPTA_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".BPTAApproval").click(function() {
			var application_id = $(this).attr("data-id");
			$("#BPTAApproval_application_id").val(application_id);
			console.log(application_id);
			$("#bpta_document_file").html('');
			$.ajax({
					type: "POST",
					url: "/Applications/fetchBPTAdataDocument",
					data: {'app_id':application_id},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.type == "ok") {
							if(result.document_link!='')
							{
								$("#bpta_document_file1").html(result.document_link['bpta_document1']);
								$("#bpta_document_file2").html(result.document_link['bpta_document2']);
								
							}
						}
					  
					}
				});

		});
		$(".BPTAApproval_btn").click(function() {
			var form = $('#BPTAApprovalForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/BPTAApprovalDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#BPTAApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#BPTAApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".BPTAApproval_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".METER_SEALING").click(function() {
			var application_id = $(this).attr("data-id");
			$("#METER_SEALING_application_id").val(application_id);
			console.log(application_id);

		});
		$(".METER_SEALING_btn").click(function() {
			var form = $('#METER_SEALINGForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/METER_SEALINGDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#METER_SEALINGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#METER_SEALINGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".METER_SEALING_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".METER_SEALINGApproval").click(function() {
			var application_id = $(this).attr("data-id");
			$("#METER_SEALINGApproval_application_id").val(application_id);
			console.log(application_id);
			$("#meter_sealing_report_file").html('');
			$.ajax({
					type: "POST",
					url: "/Applications/fetchMETER_SEALINGdataDocument",
					data: {'app_id':application_id},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.type == "ok") {
							if(result.document_link!='')
							{
								$("#meter_sealing_report_file").html(result.document_link['meter_sealing_report']);
								
							}
						}
					  
					}
				});

		});
		$(".METER_SEALINGApproval_btn").click(function() {
			var form = $('#METER_SEALINGApprovalForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/METER_SEALINGApprovalDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#METER_SEALINGApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#METER_SEALINGApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".METER_SEALINGApproval_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".POWER_INJECTION").click(function() {
			var application_id = $(this).attr("data-id");
			$("#POWER_INJECTION_application_id").val(application_id);
			console.log(application_id);

		});
		$(".POWER_INJECTION_btn").click(function() {
			var form = $('#POWER_INJECTIONForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/POWER_INJECTIONDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#POWER_INJECTIONForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#POWER_INJECTIONForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".POWER_INJECTION_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".POWER_INJECTIONApproval").click(function() {
			var application_id = $(this).attr("data-id");
			$("#POWER_INJECTIONApproval_application_id").val(application_id);
			console.log(application_id);
			$("#power_injection_report_file").html('');
			$.ajax({
					type: "POST",
					url: "/Applications/fetchPOWER_INJECTIONdataDocument",
					data: {'app_id':application_id},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.type == "ok") {
							if(result.document_link!='')
							{
								$("#power_injection_report_file").html(result.document_link['power_injection_report']);
								
							}
						}
					  
					}
				});

		});
		$(".POWER_INJECTIONApproval_btn").click(function() {
			var form = $('#POWER_INJECTIONApprovalForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/POWER_INJECTIONApprovalDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#POWER_INJECTIONApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#POWER_INJECTIONApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".POWER_INJECTIONApproval_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".completeintimation").click(function(){
			var application_id = $(this).attr("data-id");
			$("#int_comp_application_id").val(application_id);
		});
		$(".completeintimation_btn").click(function() {
			var fromobj = $(this).attr("data-form-name");
			var reason = $("#"+fromobj).find(".reason").val();
			$("#"+fromobj).find("#messageBox").html("");
			$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");

			$.ajax({
				type: "POST",
				url: "/Applications/IntimationCompletion",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
				var result = $.parseJSON(response);
				if (result.success == 1)
				{
						$("#IntimationCompForm").find(".message").val('');
						$("#IntimationCompForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#IntimationCompForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
					window.location.reload();
				}
			});

			return false;
		});
		$(".SLDC").click(function() {
			var application_id = $(this).attr("data-id");
			$("#SLDC_application_id").val(application_id);
		});
		$(".SLDC_btn").click(function() {
			var form = $('#SLDCForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			var fromobj = $(this).attr("data-form-name");

			var sldc_file1 = $("#" + fromobj).find("#sldc_file1").val();
			var sldc_file2 = $("#" + fromobj).find("#sldc_file2").val();
			var sldc_file3 = $("#" + fromobj).find("#sldc_file3").val();
			var sldc_file4 = $("#" + fromobj).find("#sldc_file4").val();
			var sldc_file5 = $("#" + fromobj).find("#sldc_file5").val();

			if (sldc_file1 != '' && $("#" + fromobj).find("#sldc_title1").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			} 
			if (sldc_file2 != '' && $("#" + fromobj).find("#sldc_title2").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			if (sldc_file3 != '' && $("#" + fromobj).find("#sldc_title3").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			if (sldc_file4 != '' && $("#" + fromobj).find("#sldc_title4").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			if (sldc_file5 != '' && $("#" + fromobj).find("#sldc_title5").val() == '') {
				$("#" + fromobj).find("#message_error").addClass("alert alert-danger");
				$("#" + fromobj).find("#message_error").html("");
				$("#" + fromobj).find("#message_error").html("Title is required field.");
				return false;
			}
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/SLDCDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#SLDCForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#SLDCForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".SLDC_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".SLDCApproval").click(function() {
			var application_id = $(this).attr("data-id");
			$("#SLDCApproval_application_id").val(application_id);
			console.log(application_id);
			$("#Sldc_Agreement_document_file1").html('');
			$("#Sldc_Agreement_document_file2").html('');
			$("#Sldc_Agreement_document_file3").html('');
			$("#Sldc_Agreement_document_file4").html('');
			$("#Sldc_Agreement_document_file5").html('');
			$.ajax({
					type: "POST",
					url: "/Applications/fetchSLDCdataDocument",
					data: {'app_id':application_id},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						console.log(result);
						if (result.type == "ok") {
							if(result.document_link!='')
							{
								$("#Sldc_Agreement_document_file1").html(result.document_link['sldc_file1']);
								$("#Sldc_Agreement_document_file2").html(result.document_link['sldc_file2']);
								$("#Sldc_Agreement_document_file3").html(result.document_link['sldc_file3']);
								$("#Sldc_Agreement_document_file4").html(result.document_link['sldc_file4']);
								$("#Sldc_Agreement_document_file5").html(result.document_link['sldc_file5']);
								
							}
						}
					  
					}
				});

		});
		$(".SLDCApproval_btn").click(function() {
			var form = $('#SLDCApprovalForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/SLDCApprovalDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#SLDCApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#SLDCApprovalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".SLDCApproval_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".ProjectCommissioning").click(function() {
			var application_id = $(this).attr("data-id");
			$("#ProjectCommissioning_application_id").val(application_id);

		});
		$(".ProjectCommissioning_btn").click(function() {
			var form = $('#ProjectCommissioningForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/ProjectCommissioningStage",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#ProjectCommissioningForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ProjectCommissioningForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".ProjectCommissioning_btn").removeAttr('disabled');
					}
				}
			});

		});

		function download_app() {
			//window.location.redirect('/Applications/downloadpdf/'+applicationId);
			<?php
			/*if($is_member == false)
			{
				?>
				alert("ATTENTION : The SIGNATURE in the Application Uploaded at the time of registeration and that for all documents at the time of subsidy claim processing MUST be same.\n 1. It is strongly advised to review the details of AC and DC Capacity, amount to paid for the GEDA registration and other application details before submission of the application. The capacity can’t be changed once the application is submitted.\n 2. In order to change the Installer has to delete the application and re-apply. In such cases of cancellation or deletion of the application, there shall be no refund of charges paid at GEDA.\n 3. It is suggested to carefully look into the details before submission of the application and uploading the Signed Application Document.");
				<?php
			}*/
			?>
		}

		function document_verify_click(application_id) {

			var district = $("#application_" + application_id).attr('data-district');
			var taluka = $("#application_" + application_id).attr('data-taluka');
			var village = $("#application_" + application_id).attr('data-village');
			var category = $("#application_" + application_id).attr('data-category');
			var route = $("#application_" + application_id).attr('data-route');
			var substation = $("#application_" + application_id).attr('data-substation');
			var connectivity = $("#application_" + application_id).attr('data-connectivity');


			//alert(application_id);
			var html_text = '<div class="col-md-7 col-sm-7 text-left" >District</div><div class="col-md-5 col-sm-5 text-left">' + district + '</div>';
			html_text += '<div class="col-md-7 col-sm-7 text-left" >Taluka</div><div class="col-md-5 col-sm-5 text-left">' + taluka + '</div>';
			html_text += '<div class="col-md-7 col-sm-7 text-left">City</div><div class="col-md-5 col-sm-5 text-left">' + village + '</div>';



			html_text += '<div class="col-md-7 col-sm-7 text-left">Consumer Application Type</div><div class="col-md-5 col-sm-5 text-left">' + category + '</div>';
			html_text += '<div class="col-md-7 col-sm-7 text-left">Name of Proposed GETCO/ PGCIL Substation</div><div class="col-md-5 col-sm-5 text-left">' + substation + '</div>';
			html_text += '<div class="col-md-7 col-sm-7 text-left">End use of electricity</div><div class="col-md-5 col-sm-5 text-left">' + connectivity + '</div>';
			html_text += '<div class="col-md-3 col-sm-3 text-left"><a target="_blank"  href="/' + route + '/' + application_id + '" class="next btn btn-primary btn-sm mb-xlg" style="background-color:#C1C1C1 !important;">Edit</a></div>';
			html_text += '<div class="col-md-4 col-sm-4 text-left"><a target="_blank"  href="/view-applications/' + application_id + '" class="next btn btn-primary btn-sm mb-xlg" style="background-color:#C1C1C1 !important;">View</a></div>';
			html_text += '';
			html_text += '<div class="col-md-12 col-sm-12 text-left">&nbsp;</div>';
			swal({
					title: 'Are you sure following parameters are correct for this application? To Verify, click on "Approve".',
					text: html_text,
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Approve",
					cancelButtonText: "No",
					closeOnConfirm: false,
					closeOnCancel: false,
					html: true
				},
				function(isConfirm) {
					if (isConfirm) {
						window.location.href = "/Applications/document_verify/" + application_id;
					} else {
						swal("Cancelled", "", "error");
					}
				});
		}
		$(".Work_Execution").click(function() {
			var application_id = $(this).attr("data-id");
			$("#Work_Execution_application_id").val(application_id);

		});
		$(".Work_Execution_btn").click(function() {
			var form = $('#Work_ExecutionForm');
			var formdata = false;
			if (window.FormData) {
				formdata = new FormData(form[0]);
			}
			// / $("#lta_signed_doc_html").val('');
			$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
			$.ajax({
				type: "POST",
				url: "/Applications/Work_Execution",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						console.log(result.success);
						console.log('hello');
						console.log(result.document_link);
						console.log('hi');
						// /$("#lta_signed_doc_html").html(result.document_link['lta_signed_doc']);
						$("#Work_ExecutionForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#Work_ExecutionForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".Work_Execution_btn").removeAttr('disabled');
					}
				}
			});

		});
		$(".DRAWING_Status").click(function() {
			var application_id = $(this).attr("data-id");
			$("#DRAW_application_id").val(application_id);
			$.ajax({
				type: "POST",
				url: "/ApplyOnlines/fetchceidata",
				data: {
					'app_id': application_id
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result);
					if (result.type == "ok") {
						$("#drawing_app_no_val").val(result.response['drawing_app_no']);
						$("#drawing_app_status_html_frm").html(result.response['drawing_app_status']);
						$("#drawing_app_status_frm").val(result.response['drawing_app_status']);
					}
					// window.location.reload();
				}
			});
		});
		$("#fetch_status_drawing").click(function() {
			var fromobj = $(this).attr("data-form-name");
			var drawing_number = $("#" + fromobj).find("#drawing_app_no_val").val();
			var app_id = $("#" + fromobj).find("#DRAW_application_id").val();
			if ($("#" + fromobj).find("#drawing_app_no_val").val() == '') {
				$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#" + fromobj).find("#messageBox").html("CEI Drawing application number is required.");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/fetch_restatus_api",
					data: {
						'drawing_number': drawing_number,
						'app_id': app_id,
						'api_type': 'drawing'
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						console.log(result);
						if (result.type == "error") {
							$("#assign_division_message").addClass("alert alert-error");
							$("#assign_division_message").html(result.msg);
						} else {
							$("#" + fromobj).find("#drawing_app_status_html_frm").html(result.response);
							$("#" + fromobj).find("#drawing_app_status_frm").val(result.response);
							$("#DCEI_Status").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						}
					}
				});
			}

		});


		$(".approval_btn_drawing").click(function() {
			var fromobj = $(this).attr("data-form-name");
			//var reason = $("#"+fromobj).find(".reason").val();
			$("#" + fromobj).find("#messageBox").html("");
			$("#" + fromobj).find("#messageBox").removeClass("alert alert-danger");
			if ($("#" + fromobj).find("#drawing_app_no").val() == '') {
				$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#" + fromobj).find("#messageBox").html("CEI Drawing application number is required.");
				return false;
			} else if ($("#" + fromobj).find("#drawing_app_status").val() == '') {
				$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#" + fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/inspectionstage",
					data: $("#" + fromobj).serialize(),
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
		$(".CEI_APP_Status_POPUP").click(function() {
			var application_id = $(this).attr("data-id");
			$("#CEI_form_application_id").val(application_id);
			$.ajax({
				type: "POST",
				url: "/Applications/fetchceidata",
				data: {
					'app_id': application_id
				},
				beforeSend: function(xhr) {
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					if (result.type == "ok") {
						$("#cei_app_no_val").val(result.response['cei_app_no']);
						$("#cei_app_status_html_frm").html(result.response['cei_app_status']);
						$("#cei_app_status_frm").val(result.response['cei_app_status']);
					}
					// window.location.reload();
				}
			});
		});
		$("#fetch_status_cei").click(function() {
			var fromobj = $(this).attr("data-form-name");
			var cei_number = $("#" + fromobj).find("#cei_app_no_val").val();
			var app_id = $("#" + fromobj).find("#CEI_form_application_id").val();
			if ($("#" + fromobj).find("#cei_app_no").val() == '') {
				$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#" + fromobj).find("#messageBox").html("Application Ref No. is required.");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/fetch_restatus_api",
					data: {
						'cei_number': cei_number,
						'app_id': app_id,
						'api_type': 'cei'
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader(
							'X-CSRF-Token',
							<?php echo json_encode($this->request->param('_csrfToken')); ?>
						);
					},
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.type == "error") {
							$("#assign_division_message").addClass("alert alert-error");
							$("#assign_division_message").html(result.msg);
						} else {
							$("#" + fromobj).find("#cei_app_status_html_frm").html(result.response);
							$("#" + fromobj).find("#cei_app_status_frm").val(result.response);
						}
					}
				});
			}
		});
		$(".approval_btn_cei").click(function() {
			var fromobj = $(this).attr("data-form-name");
			//var reason = $("#"+fromobj).find(".reason").val();
			$("#" + fromobj).find("#messageBox").html("");
			$("#" + fromobj).find("#messageBox").removeClass("alert alert-danger");
			if ($("#" + fromobj).find("#drawing_app_no").val() == '') {
				$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#" + fromobj).find("#messageBox").html("CEI Drawing application number is required.");
				return false;
			} else if ($("#" + fromobj).find("#drawing_app_status").val() == '') {
				$("#" + fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#" + fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
				return false;
			} else {
				$.ajax({
					type: "POST",
					url: "/Applications/inspectionstage",
					data: $("#" + fromobj).serialize(),
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
		document.getElementById('submitButton').addEventListener('click', function() {
			// Create a new form element
			const form = document.createElement('form');
			form.method = 'POST';
			//form.action = 'https://saralsetu.guvnl.com/connectivity_registration.php'; // Replace with your URL
			form.action = 'https://devakshayurjasetu.guvnl.com/connectivity_registration.php'; // Replace with your URL
			form.target = '_blank'; // Open in a new tab
			form.enctype = 'multipart/form-data'; // Required for file uploads
			// Create input elements and append to form
			const input1 = document.createElement('input');
			input1.type = 'hidden';
			input1.name = 'company_name';
			input1.value = $('#company_name').val();
			form.appendChild(input1);

			const input2 = document.createElement('input');
			input2.type = 'hidden';
			input2.name = 'pan_card';
			input2.value = $('#pan_card').val();
			form.appendChild(input2);

			const input3 = document.createElement('input');
			input3.type = 'hidden';
			input3.name = 'mobile';
			input3.value = $('#mobile').val();
			form.appendChild(input3);

			const input4 = document.createElement('input');
			input4.type = 'hidden';
			input4.name = 'email_id';
			input4.value = $('#email_id').val();
			form.appendChild(input4);

			const input5 = document.createElement('input');
			input5.type = 'hidden';
			input5.name = 'address';
			input5.value = $('#address').val();
			form.appendChild(input5);

			const input6 = document.createElement('input');
			input6.type = 'hidden';
			input6.name = 'city';
			input6.value = $('#city').val();
			form.appendChild(input6);

			const input7 = document.createElement('input');
			input7.type = 'hidden';
			input7.name = 'state';
			input7.value = $('#state').val();
			form.appendChild(input7);

			const input8 = document.createElement('input');
			input8.type = 'hidden';
			input8.name = 'gst_no';
			input8.value = $('#gst_no').val();
			form.appendChild(input8);

			const input9 = document.createElement('input');
			input9.type = 'hidden';
			input9.name = 'prov_proj_no';
			input9.value = $('#prov_proj_no').val();
			form.appendChild(input9);

			const input10 = document.createElement('input');
			input10.type = 'hidden';
			input10.name = 'applicant_type';
			input10.value = $('#applicant_type').val();
			form.appendChild(input10);

			const input11 = document.createElement('input');
			input11.type = 'hidden';
			input11.name = 'project_type';
			input11.value = $('#project_type').val();
			form.appendChild(input11);

			const input12 = document.createElement('input');
			input12.type = 'hidden';
			input12.name = 'project_purpose';
			input12.value = $('#project_purpose').val();
			form.appendChild(input12);

			const input13 = document.createElement('input');
			input13.type = 'hidden';
			input13.name = 'ss_name';
			input13.value = $('#ss_name').val();
			form.appendChild(input13);

			const input14 = document.createElement('input');
			input14.type = 'hidden';
			input14.name = 'ss_id';
			input14.value = $('#ss_id').val();
			form.appendChild(input14);

			const input15 = document.createElement('input');
			input15.type = 'hidden';
			input15.name = 'getcogetco_field_office';
			input15.value = $('#getcogetco_field_office').val();
			form.appendChild(input15);

			const input16 = document.createElement('input');
			input16.type = 'hidden';
			input16.name = 'applied_capacity_ac';
			input16.value = $('#applied_capacity_ac').val();
			form.appendChild(input16);

			const input17 = document.createElement('input');
			input17.type = 'hidden';
			input17.name = 'applied_capacity_dc';
			input17.value = $('#applied_capacity_dc').val();
			form.appendChild(input17);

			const input18 = document.createElement('input');
			input18.type = 'hidden';
			input18.name = 'voltage_class';
			input18.value = $('#voltage_class').val();
			form.appendChild(input18);

			const input19 = document.createElement('input');
			input19.type = 'hidden';
			input19.name = 'pwr_company';
			input19.value = $('#pwr_company').val();
			form.appendChild(input19);

			const input20 = document.createElement('input');
			input20.type = 'hidden';
			input20.name = 'developer_registration_no';
			input20.value = $('#developer_registration_no').val();
			form.appendChild(input20);

			const input21 = document.createElement('input');
			input21.type = 'hidden';
			input21.name = 'pancard_doc';
			input21.value = $('#pancard_doc').val();
			form.appendChild(input21);

			const input22 = document.createElement('input');
			input22.type = 'hidden';
			input22.name = 'undertaking';
			input22.value = $('#undertaking').val();
			form.appendChild(input22);

			const input23 = document.createElement('input');
			input23.type = 'hidden';
			input23.name = 'board_resolution';
			input23.value = $('#board_resolution').val();
			form.appendChild(input23);

			const input24 = document.createElement('input');
			input24.type = 'hidden';
			input24.name = 'applicant_name';
			input24.value = $('#applicant_name').val();
			form.appendChild(input24);

			// Append the form to the body and submit
			document.body.appendChild(form);
			form.submit();

			// Remove the form from the document after submission
			document.body.removeChild(form);
		});
	</script>