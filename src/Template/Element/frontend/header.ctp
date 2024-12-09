<?php
$Customers  = $this->Session->read('Customers');
$Members    = $this->Session->read('Members');

$USER       = '';
$MEMBER     = '';

if (isset($Customers['id'])) {
	$USER = $this->Session->read('Customers.id');
} else if (isset($Members['id'])) {
	$MEMBER 		= $this->Session->read('Members.id');
	$member_type 	= $this->Session->read("Members.member_type");
}
$ALLOWED_IPS                = array("122.169.91.255", "203.88.138.46");
$ALLOWED_MEMEBERS_MAP_VIEW  = array(1324, 1332);
$IP_ADDRESS                 = (isset($this->request) ? $this->request->clientIp() : "");
$ShowMoreReports            = true; //(in_array($IP_ADDRESS,$ALLOWED_IPS)?true:false);
$ShowMapView                = ($MEMBER > 0 && in_array($MEMBER, $ALLOWED_MEMEBERS_MAP_VIEW)) ? true : false;
$ALLOWED_APPROVE_GEDAIDS    = ALLOW_ALL_ACCESS;
$newInstallerRegistration   = ($MEMBER > 0 && in_array($MEMBER, $ALLOWED_APPROVE_GEDAIDS)) ? true : false;
$newDeveloperRegistration   = ($MEMBER > 0 && in_array($MEMBER, ALLOW_DEVELOPERS_ALL_ACCESS)) ? true : false;
$customerIsKusum 			= (isset($Customers['is_kusum']) && $Customers['is_kusum'] == 1) ? 1 : 0;
?>
<style>
	.logosmallsticky {
		max-width: 100% !important;
	}

	.HeaderLogo {
		margin-top: 0px !important;
	}

	.nk_tabs .tab-content a {
		color: #fff !important;
	}

	.applay-online-from .tabs.tabs-simple .nav-tabs {
		margin-bottom: -1px !important;
	}
</style>
<header id="header" data-plugin-options='{"alwaysStickyEnabled": true}' class="cheader">
	<div class="header-container container">
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 nopadleft">
			<div class="logo brand" style="display:table;">
				<div class="col-md-12 row">
					<a style="display:table-cell;vertical-align:middle;" href="<?php echo URL_HTTP; ?>">
						<img alt="Ahasolar_GEDA" class="HeaderLogo" data-sticky-height="auto" src="<?php echo URL_HTTP; ?>img/state/4/2_4.png" class="img-responsive tran">
					</a>
				</div>
			</div>
			<div class="logo brandsticky hideblock">
				<a href="<?php echo URL_HTTP; ?>">
					<img alt="Ahasolar_GEDA" data-sticky-width="92" data-sticky-height="auto" src="<?php echo URL_HTTP; ?>img/state/4/2_4_sticky.jpg" class="img-responsive tran" style="margin-top:8px;height:27px !important;">
				</a>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
			<div class="logo brand" style="display:table;width: 100%;">
				<div class="col-md-12 row text-right">
					<a style="vertical-align:middle;" href="<?php echo URL_HTTP; ?>">
						<img alt="Ahasolar_India_Symbole" style="max-width: 100px !important;height: 115px;" class="HeaderLogo" data-sticky-height="auto" src="<?php echo URL_HTTP; ?>img/state/4/1_4.png" class="img-responsive tran">
					</a>
				</div>
			</div>
			<div class="logo brandsticky hideblock" style="display:table;width: 100%;">
				<div class="col-md-12 row text-right">
					<a href="<?php echo URL_HTTP; ?>">
						<img alt="Ahasolar_India_Symbole" class="HeaderLogo" data-sticky-width="92" data-sticky-height="auto" src="<?php echo URL_HTTP; ?>img/state/4/1_4.png" class="img-responsive tran" style="height: 38px;">
					</a>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadleft nopadright">
			<nav class="nav-top nk_menu" style="width: 100%;">
				<ul class="nav nav-pills nav-top">
					<?php if (!empty($USER) || !empty($MEMBER)) { ?>
						<li class="email">
							<span>
								<i class="fa fa-envelope"></i>
								<?php echo $this->Html->link('Contact Us', ['controller' => 'Static', 'action' => 'contact_us']); ?>
							</span>
						</li>
					<?php } ?>
					<li class="email">
						<span>
							<i class="fa fa-envelope"></i>
							<a href="mailto:<?php echo str_replace(array('@', '.in'), array('[at]', '[dot]in'), COMPANY_INFO_EMAIL) ?>">
								<?php echo str_replace(array('@', '.in'), array('[at]', '[dot]in'), COMPANY_INFO_EMAIL); ?>
							</a>
						</span>
					</li>
					<li class="news">
						<span>
							<i class="fa fa-newspaper-o"></i>
							<?php echo $this->Html->link('News', ['controller' => 'Static', 'action' => 'news']); ?>
						</span>
					</li>
					<?php if (empty($USER) && empty($MEMBER)) { ?>
						<?php /*<li class="login">
						<span class="login_btn" data-logintype="member_login"><i class="fa fa-sign-in"></i><a href="#" data-toggle="modal" data-target="#login-model">Member Login</a></span>
					</li>*/ ?>
						<li class="login">
							<span class="login_btn" data-logintype="login"><i class="fa fa-sign-in"></i><a href="#" data-toggle="modal" data-target="#login-model">Login</a></span>
						</li>
					<?php } ?>
					<?php if (!empty($USER) || !empty($MEMBER)) { ?>
						<li>
							<span>
								<i class="fa fa-sign-out"></i>
								<a href="<?php echo WEB_URL; ?>users/logout">Logout</a>
							</span>
						</li>
					<?php } ?>
				</ul>
			</nav>
			<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
				<i class="fa fa-bars"></i>
			</button>
			<div class="mobnavtop">
				<div style="max-width: 100%; width: 100%;" aria-expanded="true" class="navbar-collapse nav-main-collapse collapse in">
					<div class="">
						<nav class="nav-main mega-menu">
							<ul class="nav nav-pills nav-main" id="mainMenu">
								<?php if (empty($USER) && empty($MEMBER)) { ?>
									<?php /*<li class="dropdown">
										<?php echo $this->Html->link('Home',WEB_URL); ?>
									</li>*/ ?>
									<li class="dropdown">
										<?php echo $this->Html->link('Track Application', '/apply-onlines/track-application'); ?>
									</li>

								<?php } ?>
								<?php if (!empty($USER) && $Customers['customer_type'] != "installer") { ?>
									<li><?php echo $this->Html->link('My Projects', "/project"); ?></li>
								<?php } ?>
								<?php if (!empty($MEMBER)) { ?>
									<li class="dropdown">
										<?php echo $this->Html->link('Dashboard', ['controller' => 'member', 'action' => 'index'], ['class' => 'dropdown-toggle']); ?>
										<ul class="dropdown-menu">
											<?php if (isset($member_type) && $member_type != 6005) { ?>
												<li><?php echo $this->Html->link('My Application', ['controller' => 'member', 'action' => 'index']); ?></li>
											<?php } ?>

											<li><?php echo $this->Html->link('RE Application', '/developer-dashboard'); ?></li>
										</ul>
									</li>
									<?php } else if (!empty($USER) && $Customers['customer_type'] == "installer") {
									if (isset($Customers['login_type']) && $Customers['login_type'] == "developer") { ?>
										<li><?php echo $this->Html->link('Dashboard', '/developer-dashboard'); ?></li>
									<?php } else { ?>
										<li><?php echo $this->Html->link('Dashboard', '/installer-dashboard'); ?></li>
								<?php }
								}
								?>
								<?php if (empty($USER) && empty($MEMBER)) { ?>
									<li class="dropdown">
										<?php echo $this->Html->link('Important Documents', ['controller' => 'Static', 'action' => 'whatit_cost'], ['class' => '']); ?>
									</li>
									<li class="dropdown">
										<?php echo $this->Html->link('Installer Form', '/installer-registration', ['class' => '']); ?>
										<ul class="dropdown-menu">
											<li><?php echo $this->Html->link('Rooftop', '/installer-registration'); ?></li>
											<li><?php echo $this->Html->link('Kusum', '/installer-registration-kusum'); ?></li>
										</ul>
									</li>
									<li class="dropdown">
										<?php echo $this->Html->link('Developer Form', '/developer-registration', ['class' => '']); ?>
									</li>
								<?php } ?>
								<?php if (!empty($USER)) { ?>
									<li class="dropdown">
										<?php if ($customerIsKusum == 1) { ?>
											<?php echo $this->Html->link('Apply', ['controller' => 'ApplyOnlinesKusum', 'action' => 'index'], ['class' => 'dropdown-toggle']); ?>
											<ul class="dropdown-menu">
												<li><?php echo $this->Html->link('New Application', ['controller' => 'ApplyOnlinesKusum', 'action' => 'index']); ?></li>
											</ul>
											<?php } else {
											$textNewApplication = (isset($Customers['login_type']) && ($Customers['login_type'] == "developer")) ? 'RTPV RESCO' : 'CAPEX';
											if (isset($Customers['login_type']) && ($Customers['login_type'] == "developer")) {
												if (!empty($activeCategoryIds)) {
													$categoryCounter 	= 0;
													foreach ($activeCategoryIds as $categoryId) {
														if (in_array($categoryId, $developerCategory) && $categoryCounter == 0) {
															if ($activeCategory[$categoryId]['route_name'] == '') {
																echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index'], ['class' => 'dropdown-toggle']);
															} else {
																echo $this->Html->link($activeCategory[$categoryId]['category_name'], $activeCategory[$categoryId]['route_name'], ['class' => 'dropdown-toggle']);
															}
															$categoryCounter++;
															break;
														}
													}
												}
											} else { ?>
												<?php echo $this->Html->link('Apply', ['controller' => 'ApplyOnlines', 'action' => 'index'], ['class' => 'dropdown-toggle']); ?>
											<?php

											}
											?>
											<ul class="dropdown-menu">
												<?php
												if (isset($Customers['login_type']) && $Customers['login_type'] == "developer") {
													if (!empty($activeCategoryIds)) {
														foreach ($activeCategoryIds as $categoryId) {
															if (in_array($categoryId, $developerCategory)) {
																if ($activeCategory[$categoryId]['route_name'] == '') {
												?>
																	<li><?php echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index']); ?></li>
																<?php
																} else {
																?>
																	<li><?php echo $this->Html->link($activeCategory[$categoryId]['category_name'], $activeCategory[$categoryId]['route_name']); ?></li>
													<?php
																}
															}
														}
													}
												} else { ?>
													<li><?php echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index']); ?></li>
												<?php } ?>
												<?php /*<li><?php echo $this->Html->link('Ground Mounted',['controller'=>'ApplyOnlines','action' => 'index']); ?></li>
											<li><?php echo $this->Html->link('Wind',['controller'=>'ApplyOnlines','action' => 'index']); ?></li>
											<li><?php echo $this->Html->link('Hybrid',['controller'=>'ApplyOnlines','action' => 'index']); ?></li> */ ?>
												<li><?php echo $this->Html->link('PV Capacity Enhancement', ['controller' => 'ApplyOnlines', 'action' => 'AdditionalCapacity']); ?></li>
											</ul>
										<?php } ?>
									</li>
								<?php } ?>
								<?php if (!empty($USER) || !empty($MEMBER)) { ?>
									<li class="dropdown">

										<?php echo $this->Html->link('My Application', ['controller' => 'ApplyOnlines', 'action' => 'applyonline_list'], ['class' => 'dropdown-toggle']); ?>
										<ul class="dropdown-menu">
											<?php if ($customerIsKusum == 1) { ?>
												<li><?php echo $this->Html->link('My Application', ['controller' => 'ApplyOnlinesKusum', 'action' => 'applyonline_list']); ?></li>
											<?php } else { ?>
												<?php if (isset($member_type) && $member_type != 6005) { ?>
													<li><?php echo $this->Html->link('My Application', ['controller' => 'ApplyOnlines', 'action' => 'applyonline_list']); ?></li>
												<?php } ?>

												<?php if ((isset($Customers['login_type']) && $Customers['login_type'] == "developer") || !empty($MEMBER)) { ?>
													<li><?php echo $this->Html->link('RE Application', ['controller' => 'Applications', 'action' => 'list']); ?></li>
												<?php } ?>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>
								<?php if (!empty($USER)) { ?>
									<li class="dropdown">
										<?php echo $this->Html->link('Profile', ['controller' => 'users', 'action' => 'updateprofile'], ['class' => 'dropdown-toggle']); ?>
										<ul class="dropdown-menu">
											<?php if (isset($Customers['login_type']) && $Customers['login_type'] == "developer") { ?>
												<li><?php echo $this->Html->link('Profile', ['controller' => 'developer', 'action' => 'update_developer_profile']); ?></li>
												<li><?php echo $this->Html->link('Change Password', ['controller' => 'developer', 'action' => 'change_developer_password']); ?></li>
												<li><?php echo $this->Html->link('Set Project', ['controller' => 'DeveloperSettings', 'action' => 'workorder']); ?></li>
												<li><?php echo $this->Html->link('Assigned Project', ['controller' => 'DeveloperSettings', 'action' => 'assigned_workorder']); ?></li>
												<li><?php echo $this->Html->link('Transfer WTG Permission Request', ['controller' => 'ApplicationDeveloperPermission', 'action' => 'transfer_permission_request']); ?></li>
												<li><?php echo $this->Html->link('Transfer Inverter Permission Request', ['controller' => 'ApplicationDeveloperPermission', 'action' => 'transfer_inverter_permission_request']); ?></li>
											<?php } else { ?>
												<li><?php echo $this->Html->link('Profile', ['controller' => 'users', 'action' => 'updateprofile']); ?></li>
												<li><?php echo $this->Html->link('Change Password', ['controller' => 'users', 'action' => 'changepassword']); ?></li>
											<?php } ?>
											<li><?php echo $this->Html->link('Contact Us', ['controller' => 'Static', 'action' => 'contact_us']); ?></li>
											<li><?php echo $this->Html->link('Logout', ['controller' => 'users', 'action' => 'logout']); ?></li>

										</ul>
									</li>
									<li class="dropdown">
										<?php echo $this->Html->link('Reports', ['controller' => 'Reports', 'action' => 'MISReport'], ['class' => 'dropdown-toggle']); ?>
										<ul class="dropdown-menu">
											<?php if ($ShowMapView) { ?>
												<li><?php echo $this->Html->link('Application Map View', "apply-onlines/map-view"); ?></li>
											<?php } ?>
											<li><?php echo $this->Html->link('MIS Report', ['controller' => 'Reports', 'action' => 'MISReport']); ?></li>
											<li><?php echo $this->Html->link('RFID Report', ['controller' => 'Reports', 'action' => 'RFIDReport']); ?></li>
											<?php if ((isset($Customers['login_type']) && $Customers['login_type'] == "developer") || !empty($MEMBER)) { ?>
												<li><?php echo $this->Html->link('RE MIS Report', ['controller' => 'ReReports', 'action' => 'ReMISReport']); ?></li>
											<?php } ?>


											<?php if (SUBSIDY_CLAIM == '1') { ?>
												<li><?php echo $this->Html->link('Request Subsidy Claims', ['controller' => 'Subsidy', 'action' => 'claimsubsidy']); ?></li>
											<?php } ?>
											<li><?php echo $this->Html->link('Change Request', ['controller' => 'ApplyOnlines', 'action' => 'updaterequest']); ?></li>
											<li><?php echo $this->Html->link('Capacity Reduction', ['controller' => 'ApplyOnlines', 'action' => 'capacityrequest']); ?></li>
										</ul>
									</li>
								<?php } ?>
								<?php
								if (!empty($MEMBER)) { ?>
									<li class="dropdown">
										<?php echo $this->Html->link('Member user', ['controller' => 'member', 'action' => 'updateprofile'], ['class' => 'dropdown-toggle']); ?>
										<ul class="dropdown-menu">
											<li><?php echo $this->Html->link('Profile', ['controller' => 'member', 'action' => 'updateprofile']); ?></li>
											<li><?php echo $this->Html->link('Change Password', ['controller' => 'member', 'action' => 'changepassword']); ?></li>
											<li><?php echo $this->Html->link('Contact Us', ['controller' => 'Static', 'action' => 'contact_us']); ?></li>
											<?php if (!empty($MEMBER)) { ?>
												<li><?php echo $this->Html->link('Installer List', ['controller' => 'Installers', 'action' => 'index']); ?></li>
												<?php if ($newInstallerRegistration) { ?>
													<li><?php echo $this->Html->link('New Installers - Rooftop', ['controller' => 'Installers', 'action' => 'new_registration']); ?></li>
													<li><?php echo $this->Html->link('New Developer - Kusum', ['controller' => 'Installers', 'action' => 'new_registration_kusum']); ?></li>
												<?php  } ?>
												<?php if ($newDeveloperRegistration) { ?>
													<li><?php echo $this->Html->link('New Developers', ['controller' => 'DeveloperRegistrations', 'action' => 'new_registration']); ?></li>
												<?php  } ?>
											<?php } ?>

											<li><?php echo $this->Html->link('Logout', ['controller' => 'users', 'action' => 'logout']); ?></li>
										</ul>
									</li>
									<li class="dropdown">
										<?php echo $this->Html->link('Reports', ['controller' => 'Reports', 'action' => 'MISReport'], ['class' => 'dropdown-toggle']); ?>
										<ul class="dropdown-menu">
											<?php if ($ShowMapView) { ?>
												<li><?php echo $this->Html->link('Application Map View', "apply-onlines/map-view"); ?></li>
											<?php } ?>
											<li><?php echo $this->Html->link('MIS Report', ['controller' => 'Reports', 'action' => 'MISReport']); ?></li>
											<li><?php echo $this->Html->link('RFID Report', ['controller' => 'Reports', 'action' => 'RFIDReport']); ?></li>
											<?php if ((isset($Customers['login_type']) && $Customers['login_type'] == "developer") || !empty($MEMBER)) { ?>
											<li><?php echo $this->Html->link('RE MIS Report', ['controller' => 'ReReports', 'action' => 'ReMISReport']); ?></li>
											<li><?php echo $this->Html->link('Application for WTG Coordinate verification', ['controller' => 'GeoApplications', 'action' => 'GeoLocationReport']); ?></li>
											<li><?php echo $this->Html->link('Application for Shifting WTG Coordinate verification', ['controller' => 'GeoShiftingApplication', 'action' => 'GeoLocationShiftingReport']); ?></li>
											<?php }?>
											<?php if ($ShowMoreReports) { ?>
												<li><?php echo $this->Html->link('Category Summary', ['controller' => 'Reports', 'action' => 'categorySummary']); ?></li>
												<li><?php echo $this->Html->link('Discom Summary', ['controller' => 'Reports', 'action' => 'discomSummary']); ?></li>
												<li><?php echo $this->Html->link('District Meter Installation', ['controller' => 'Reports', 'action' => 'districtMeter']); ?></li>
												<li><?php echo $this->Html->link('Meter Installation CP', ['controller' => 'Reports', 'action' => 'meterCp']); ?></li>
												<li><?php echo $this->Html->link('Month Wise RRSS', ['controller' => 'Reports', 'action' => 'monthRrss']); ?></li>
												<li><?php echo $this->Html->link('Subdivision Wise Summary', ['controller' => 'Reports', 'action' => 'subdivisionSummary']); ?></li>
												<li><?php echo $this->Html->link('Taluka Wise Summary', ['controller' => 'Reports', 'action' => 'talukaSummary']); ?></li>

											<?php } ?>
											<?php if ($MEMBER > 0 && in_array($MEMBER, ALLOW_DELETE_APPLICATION_ACCESS)) { ?>
												<li><?php echo $this->Html->link('Delete Application Request', ['controller' => 'ApplyOnlines', 'action' => 'DeleteApplicationRequestList']); ?></li>
											<?php } ?>
											<?php if ($Members['member_type'] == "6001" && SUBSIDY_CLAIM == '1') { ?>
												<li><?php echo $this->Html->link('Approve Subsidy Claims', ['controller' => 'Subsidy', 'action' => 'subsidyclaims']); ?></li>
												<?php if ($newInstallerRegistration) { ?>
													<li><?php echo $this->Html->link('Payment Report', ['controller' => 'PaymentReport', 'action' => 'listPaymentData']); ?></li>
													<li><?php echo $this->Html->link('SSDSP Return List', ['controller' => 'FeesReturn', 'action' => 'return_form_list']); ?></li>
													<!-- <li><?php //echo $this->Html->link('Geo Location Payment Report', ['controller' => 'GeoPaymentReport', 'action' => 'listGeoPaymentData']); ?></li> -->
												<?php } ?>
												<?php if ($newDeveloperRegistration) { ?>
													<li><?php echo $this->Html->link('New Developers', ['controller' => 'DeveloperRegistrations', 'action' => 'new_registration']); ?></li>
												<?php  } ?>
												<?php if ($ShowMoreReports) { ?>
													<li><?php echo $this->Html->link('Subsidy Claim Payment Report', '/subsidypaymentreport'); ?></li>

												<?php } ?>
											<?php } ?>
											<li><?php echo $this->Html->link('Change Request', ['controller' => 'ApplyOnlines', 'action' => 'updaterequest']); ?></li>
											<li><?php echo $this->Html->link('Capacity Reduction', ['controller' => 'ApplyOnlines', 'action' => 'capacityrequest']); ?></li>
										</ul>
									</li>
								<?php } ?>
								<?php if (empty($USER) && empty($MEMBER)) { ?>
									<li><?php echo $this->Html->link('Contact Us', ['controller' => 'Static', 'action' => 'contact_us']); ?></li>
									<li><?php echo $this->Html->link('Fees Return Form', ['controller' => 'FeesReturn', 'action' => 'return_form']); ?></li>
								<?php } ?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="hidemobnav">
		<div class="navbar-collapse nav-main-collapse collapse">
			<nav class="nav-main mega-menu">
				<ul class="nav nav-pills nav-main" id="mainMenu">
					<?php if (empty($USER) && empty($MEMBER)) { ?>
						<li class="dropdown"><?php echo $this->Html->link('Home', ['controller' => 'users', 'action' => 'index']); ?></li>
					<?php } ?>
					<?php if (!empty($USER) && $Customers['customer_type'] != "installer") { ?>
						<li><?php echo $this->Html->link('My Projects', "/project"); ?></li>
					<?php } ?>
					<?php if (!empty($MEMBER)) { ?>
						
						<li><?php echo $this->Html->link('Dashboard', '/member'); ?></li>
					<?php } ?>
					<?php if (empty($USER) && empty($MEMBER)) { ?>
						<li class="dropdown">
							<?php echo $this->Html->link('Important Documents', ['controller' => 'Static', 'action' => 'whatit_cost'], ['class' => '']); ?>
						</li>
						<li class="dropdown">
							<?php echo $this->Html->link('Installer Form', '/installer-registration', ['class' => 'dropdown-toggle']); ?>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link('Rooftop', '/installer-registration'); ?></li>
								<li><?php echo $this->Html->link('Kusum', '/installer-registration-kusum'); ?></li>
							</ul>
						</li>
						<li class="dropdown">
							<?php echo $this->Html->link('Developer Form', '/developer-registration', ['class' => '']); ?>
						</li>
					<?php } ?>
					<?php if (!empty($USER)) { ?>
						<li class="dropdown">
							<?php
							$textNewApplication = (isset($Customers['login_type']) && ($Customers['login_type'] == "developer")) ? 'RESCO' : 'CAPEX'; ?>
							<?php
							if (isset($Customers['login_type']) && ($Customers['login_type'] == "developer")) {
								if (!empty($activeCategoryIds)) {
									$categoryCounter 	= 0;
									foreach ($activeCategoryIds as $categoryId) {
										if (in_array($categoryId, $developerCategory) && $categoryCounter == 0) {
											if ($activeCategory[$categoryId]['route_name'] == '') {
												echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index'], ['class' => 'dropdown-toggle']);
											} else {
												echo $this->Html->link($activeCategory[$categoryId]['category_name'], $activeCategory[$categoryId]['route_name'], ['class' => 'dropdown-toggle']);
											}
											$categoryCounter++;
											break;
										}
									}
								}
							} else { ?>
								<?php echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index'], ['class' => 'dropdown-toggle']); ?>
							<?php

							}
							?>
							<ul class="dropdown-menu">
								<?php
								if (isset($Customers['login_type']) && $Customers['login_type'] == "developer") {
									if (!empty($activeCategoryIds)) {
										foreach ($activeCategoryIds as $categoryId) {

											if (in_array($categoryId, $developerCategory)) {
												if ($activeCategory[$categoryId]['route_name'] == '') {
								?>
													<li><?php echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index']); ?></li>
												<?php
												} else {
												?>
													<li><?php echo $this->Html->link($activeCategory[$categoryId]['category_name'], $activeCategory[$categoryId]['route_name']); ?></li>
									<?php
												}
											}
										}
									}
								} else { ?>
									<li><?php echo $this->Html->link($textNewApplication, ['controller' => 'ApplyOnlines', 'action' => 'index']); ?></li>
									<?php /*<li><?php echo $this->Html->link('Ground Mounted',['controller'=>'ApplyOnlines','action' => 'index']); ?></li>
								<li><?php echo $this->Html->link('Wind',['controller'=>'ApplyOnlines','action' => 'index']); ?></li>
								<li><?php echo $this->Html->link('Hybrid',['controller'=>'ApplyOnlines','action' => 'index']); ?></li>*/ ?>
									<li><?php echo $this->Html->link('PV Capacity Enhancement', ['controller' => 'ApplyOnlines', 'action' => 'AdditionalCapacity']); ?></li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (!empty($USER) || !empty($MEMBER)) { ?>
						<li class="dropdown">
							<?php echo $this->Html->link('My Application', ['controller' => 'ApplyOnlines', 'action' => 'applyonline_list'], ['class' => 'dropdown-toggle']); ?>
							<ul class="dropdown-menu">
								<?php if ($customerIsKusum == 1) { ?>
									<li><?php echo $this->Html->link('My Application', ['controller' => 'ApplyOnlinesKusum', 'action' => 'applyonline_list']); ?></li>
								<?php } else { ?>
									<?php if(isset($member_type) && $member_type != 6005){ ?>
										<li><?php echo $this->Html->link('My Application', ['controller' => 'ApplyOnlines', 'action' => 'applyonline_list']); ?></li>
									<?php }?>
									<?php if ((isset($Customers['login_type']) && $Customers['login_type'] == "developer") || !empty($MEMBER)) { ?>
										<li><?php echo $this->Html->link('RE Application', ['controller' => 'Applications', 'action' => 'list']); ?></li>
									<?php } ?>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php if (!empty($USER)) { ?>
						<li class="dropdown">
							<?php if (isset($Customers['login_type']) && $Customers['login_type'] == "developer") { ?>
								<?php echo $this->Html->link('Profile', ['controller' => 'developer', 'action' => 'update_developer_profile'], ['class' => 'dropdown-toggle']); ?>	
							<?php } else { ?>	
								<?php echo $this->Html->link('Profile', ['controller' => 'users', 'action' => 'updateprofile'], ['class' => 'dropdown-toggle']); ?>
							<?php } ?>
							<ul class="dropdown-menu">
								<?php if (isset($Customers['login_type']) && $Customers['login_type'] == "developer") { ?>
									<li><?php echo $this->Html->link('Profile', ['controller' => 'developer', 'action' => 'update_developer_profile']); ?></li>
									<li><?php echo $this->Html->link('Change Password', ['controller' => 'developer', 'action' => 'change_developer_password']); ?></li>
									<li><?php echo $this->Html->link('Set Project', ['controller' => 'DeveloperSettings', 'action' => 'workorder']); ?></li>
									<li><?php echo $this->Html->link('Assigned Project', ['controller' => 'DeveloperSettings', 'action' => 'assigned_workorder']); ?></li>
								<?php } else { ?>
									<li><?php echo $this->Html->link('Profile', ['controller' => 'users', 'action' => 'updateprofile']); ?></li>
									<li><?php echo $this->Html->link('Change Password', ['controller' => 'users', 'action' => 'changepassword']); ?></li>
								<?php } ?>
								<li><?php echo $this->Html->link('Contact Us', ['controller' => 'Static', 'action' => 'contact_us']); ?></li>
								<li><?php echo $this->Html->link('Logout', ['controller' => 'users', 'action' => 'logout']); ?></li>
							</ul>
						</li>
						<li class="dropdown">
							<?php echo $this->Html->link('Reports', ['controller' => 'Reports', 'action' => 'MISReport'], ['class' => 'dropdown-toggle']); ?>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link('MIS Report', ['controller' => 'Reports', 'action' => 'MISReport']); ?></li>
								<?php if ((isset($Customers['login_type']) && $Customers['login_type'] == "developer") || !empty($MEMBER)) { ?>
								<li><?php echo $this->Html->link('RE MIS Report', ['controller' => 'ReReports', 'action' => 'ReMISReport']); ?></li>
								<li><?php echo $this->Html->link('RFID Report', ['controller' => 'Reports', 'action' => 'RFIDReport']); ?></li>
								<?php }?>
							</ul>
						</li>
					<?php } ?>
					<?php
					if (!empty($MEMBER)) { ?>
						<li class="dropdown">
							<?php echo $this->Html->link('Member user', ['controller' => 'member', 'action' => 'updateprofile'], ['class' => 'dropdown-toggle']); ?>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link('Profile', ['controller' => 'member', 'action' => 'updateprofile']); ?></li>
								<li><?php echo $this->Html->link('Change Password', ['controller' => 'member', 'action' => 'changepassword']); ?></li>
								<li><?php echo $this->Html->link('Contact Us', ['controller' => 'Static', 'action' => 'contact_us']); ?></li>
								<?php if (!empty($MEMBER)) { ?>
									<li><?php echo $this->Html->link('Installer List', ['controller' => 'Installers', 'action' => 'index']); ?></li>
									<?php if ($newInstallerRegistration) { ?>
										<li><?php echo $this->Html->link('New Installers - Rooftop', ['controller' => 'Installers', 'action' => 'new_registration']); ?></li>
										<li><?php echo $this->Html->link('New Developer - Kusum', ['controller' => 'Installers', 'action' => 'new_registration_kusum']); ?></li>
									<?php  } ?>
								<?php } ?>
								<li><?php echo $this->Html->link('MIS Report', ['controller' => 'Reports', 'action' => 'MISReport']); ?></li>
								<?php if ((isset($Customers['login_type']) && $Customers['login_type'] == "developer") || !empty($MEMBER)) { ?>
								<li><?php echo $this->Html->link('RE MIS Report', ['controller' => 'ReReports', 'action' => 'ReMISReport']); ?></li>
								<?php }?>
								<?php if ($ShowMoreReports) { ?>
									<li><?php echo $this->Html->link('Category Summary', ['controller' => 'Reports', 'action' => 'categorySummary']); ?></li>
									<li><?php echo $this->Html->link('Discom Summary', ['controller' => 'Reports', 'action' => 'discomSummary']); ?></li>
									<li><?php echo $this->Html->link('District Meter Installation', ['controller' => 'Reports', 'action' => 'districtMeter']); ?></li>
									<li><?php echo $this->Html->link('Meter Installation CP', ['controller' => 'Reports', 'action' => 'meterCp']); ?></li>
									<li><?php echo $this->Html->link('Month Wise RRSS', ['controller' => 'Reports', 'action' => 'monthRrss']); ?></li>
									<li><?php echo $this->Html->link('Subdivision Wise Summary', ['controller' => 'Reports', 'action' => 'subdivisionSummary']); ?></li>
									<li><?php echo $this->Html->link('Taluka Wise Summary', ['controller' => 'Reports', 'action' => 'talukaSummary']); ?></li>

								<?php } ?>
								<?php if ($MEMBER > 0 && in_array($MEMBER, ALLOW_DELETE_APPLICATION_ACCESS)) { ?>
									<li><?php echo $this->Html->link('Delete Application Request', ['controller' => 'ApplyOnlines', 'action' => 'DeleteApplicationRequestList']); ?></li>
								<?php } ?>
								<?php if ($Members['member_type'] == "6001" && SUBSIDY_CLAIM == '1') { ?>
									<li><?php echo $this->Html->link('Approve Subsidy Claims', ['controller' => 'Subsidy', 'action' => 'subsidyclaims']); ?></li>
									<?php if ($newInstallerRegistration) { ?>
										<li><?php echo $this->Html->link('Payment Report', ['controller' => 'PaymentReport', 'action' => 'listPaymentData']); ?></li>
										<li><?php echo $this->Html->link('SSDSP Return List', ['controller' => 'FeesReturn', 'action' => 'return_form_list']); ?></li>
										<!-- <li><?php//echo $this->Html->link('Geo Location Payment Report', ['controller' => 'GeoPaymentReport', 'action' => 'listGeoPaymentData']); ?></li> -->
									<?php } ?>
									<?php if ($ShowMoreReports) { ?>
										<li><?php echo $this->Html->link('Subsidy Claim Payment Report', '/subsidypaymentreport'); ?></li>
									<?php } ?>
								<?php } ?>
								<li><?php echo $this->Html->link('Change Request', ['controller' => 'ApplyOnlines', 'action' => 'updaterequest']); ?></li>
								<li><?php echo $this->Html->link('Capacity Reduction', ['controller' => 'ApplyOnlines', 'action' => 'capacityrequest']); ?></li>
								<li><?php echo $this->Html->link('Logout', ['controller' => 'users', 'action' => 'logout']); ?></li>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
</header>
<!-- Modal Customer Login -->
<div id="login-model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Welcome! Login Here</h4>
			</div>
			<div class="modal-body applay-online-from">
				<div id="messageBox"></div>
				<?php
				echo $this->Form->create('Customers', ['name' => 'customer_login', 'id' => 'customer_login']);
				?>
				<div class="tabs tabs-bottom tabs-simple nk_tabs">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tabsNavigationSimple1" class="login_btn" data-logintype="login" data-toggle="tab">Installer Login</a>
						</li>
						<li class="">
							<a href="#tabsNavigationSimple1" class="login_btn" data-logintype="member_login" data-toggle="tab">Member Login</a>
						</li>
						<li class="">
							<a href="#tabsNavigationSimple1" class="login_btn" data-logintype="developer_login" data-toggle="tab">Developer Login</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tabsNavigationSimple1">
							<label 1="1" class="control-label" for="password">Login</label>
							<?php
							echo $this->Form->input('email', ['id' => 'email', "type" => "email", "class" => "form-control", 'label' => false]);
							echo $this->Form->input('password', ['id' => 'password', "type" => "password", "class" => "form-control", 'autocomplete' => 'off', 'label' => true]); ?>
							<?php
							$captcha = '';
							if (CAPTCHA_DISPLAY == 1) {
							?>
								<div class="col-md-12 <?php echo $captcha; ?> " style="margin-top: 15px;margin-bottom: 15px;margin-left: -13px;">
									<div class="recaptcha" data-sitekey="<?php echo CAPTCHA_KEY; ?>"></div>
								</div>
							<?php } ?>
							<?php ?>
							<div class="row">
								<div class="col-md-2">
									<?php echo $this->Form->button(__('LOGIN'), ['type' => 'button', 'id' => 'login_btn', 'class' => 'btn btn-primary']); ?>
								</div>
								<div class="col-md-2">
									<a href="/users/forgot_password" class="btn btn-primary forgot_pwd hide"></a>
									<a href="/users/forgot_password_developer" class="btn btn-primary forgot_pwd_dev hide"></a>
									<a href="/member/forgot-password" class="btn btn-primary forgot_member_pwd hide"></a>
								</div>
							</div>
							<div class="row">&nbsp;</div>
						</div>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<?php /*
<div class="message alert alert-danger" style="font-size: 20px;text-align: center;">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>GEDA server is under maintenance from 2:00pm to 4:00pm on 26th-Oct-2018. Inconvenience casused is deeply regretted.
</div>
*/ ?>
<script type="text/javascript">
	var login_type = '';
	$("#login_btn").click(function() {
		$("#password").val(makeid() + $.base64.encode($("#password").val()) + makeid());
		jQuery.ajax({
			type: "POST",
			url: '<?php echo WEB_URL; ?>users/' + login_type,
			data: new FormData($('form#customer_login')[0]),
			cache: false,
			processData: false,
			contentType: false,
			success: function(result) {
				var res = jQuery.parseJSON(result);
				if (res.type === 'ok') {
					if (login_type == 'login' || login_type == 'developer_login') {
						if (res.is_installer == 'customer') {
							window.location = '<?php echo WEB_URL; ?>projects';
						} else {
							window.location = '<?php echo WEB_URL; ?>apply-online-list';
						}

					} else {
						window.location = '<?php echo WEB_URL; ?>member';
					}
				} else {
					if (res.msg == 'changepass') {
						window.location = '<?php echo WEB_URL; ?>member/changepasswordforce';
					} else {
						var html = '<div class="alert alert-danger"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><p>' + res.msg + '</p></div>';
						$("#messageBox").html(html).delay(3000).fadeOut(700).css('display', '');
						var str = $("#password").val();
						var last = str.substring(str.length - 10, str.length);
						var first = str.substring(0, 10);
						str = str.replace(first, '');
						str = str.replace(last, '');
						$("#password").val($.base64.decode(str));
					}
				}
			}
		});
	});
	$(".login_btn").click(function() {
		login_type = '';
		login_type = $(this).attr("data-logintype");
		if (login_type == 'login') {
			$(".modal-title").html('Installer Login');
			$(".forgot_pwd").html('Forgot Password?');
			$(".forgot_pwd").removeClass('hide');
			$(".forgot_pwd_dev").addClass('hide');
			$(".app_note").removeClass('hide');
			$(".forgot_member_pwd").addClass('hide');
		} else if (login_type == 'member_login') {
			$(".modal-title").html('Member Login');
			$(".app_note").addClass('hide');
			$(".forgot_pwd").addClass('hide');
			$(".forgot_pwd_dev").addClass('hide');
			$(".forgot_member_pwd").html('Forgot Password?');
			$(".forgot_member_pwd").removeClass('hide');
		} else if (login_type == 'developer_login') {
			$(".modal-title").html('Developer Login');
			$(".forgot_pwd_dev").html('Forgot Password?');
			$(".forgot_pwd").addClass('hide');
			$(".forgot_pwd_dev").removeClass('hide');
			$(".app_note").removeClass('hide');
			$(".forgot_member_pwd").addClass('hide');
		}
	});
	var WEB_URL = "<?php echo URL_HTTP; ?>";
	$('.modal-dialog input').keypress(function(e) {
		var key = e.which;
		if (key == 13) {
			$('#login_btn').click();
		}
	});
	$(document).ready(function() {
		function blinker() {
			$('.blink_me').fadeOut(500);
			$('.blink_me').fadeIn(500);
		}

		setInterval(blinker, 1000);
	});
</script>
<style>
	.app_note h6 {
		width: 270px;
		float: left;
		padding-top: 5px;
	}

	.app_note a {
		width: 100px;
		display: table-cell;
	}

	.modal-app-links {
		font-size: 13px;
		vertical-align: top;
		line-height: 30px;
	}

	.blink_me {
		background-color: #FFCC29;
		border-radius: 4px;
	}

	.blink_me a {
		font-size: 12px;
		font-style: normal;
		line-height: 20px;
		margin-left: 3px;
		margin-right: 3px;
		text-transform: uppercase;
		font-weight: 700;
		padding: 10px 13px;
	}
</style>