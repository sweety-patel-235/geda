<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ApplyOnline Application</title>
		<!-- Style CSS -->
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css"/>
		<style>
			@font-face {
			font-family: 'arial_bold';
			src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALBD.TTF');
			}
			@font-face {
			font-family: 'arial_simple';
			src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/arial.ttf');
			}
			.td_bold{ font-family: 'arial_bold';font-size: 14px;}
			.td_simple{font-family: 'arial_simple';font-size: 14px;}
			@page {
				margin: 10px;
			}
			/*
			@page :first {
				margin-top: 100px;
			}
			*/
			body {
				/* margin: 100px 20px 50px 20px; */
				margin: 20px;
			}
			#headerA {
				position: fixed;
				left: 0px; right: 0px; top: 0px;
				text-align: center;
				background-color: white;
				height: 90px;
			}
			#headerB {
				position: absolute;
				left: -20px; right: -20px; top: -200px;
				text-align: center;
				background-color: white;
				height: 190px;
			}
			#footer {
				position: fixed;
				left: 0px; right: 0px; bottom: 0px;
				text-align: center;
				background-color: white;
				height: 40px;
				color:black;
				font-weight:bold;
			}
			.active_status {
				background-color: orange;
				color:white;
			}
			.th_class {
				background: #333; color: white; 
			}
			th { background: #333; color: white; font-weight: bold; font-family: 'arial_bold';font-size: 14px;}
			</style>
	</head>
	<body id="pdf-header">
		<script type="text/php">
			if (isset($pdf)) {
				$x          = 35;
				$y          = 810;
				$text       = "";
				$font       = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold");
				$size       = 10;
				$color      = array(0,0,0);
				$word_space = 0.0;  //  default
				$char_space = 0.0;  //  default
				$angle      = 0.0;  //  default
				$pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

				$x          = 500;
				$y          = 810;
				$text       = "Page {PAGE_NUM} of {PAGE_COUNT}";
				$font       = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold");
				$size       = 10;
				$color      = array(0,0,0);
				$word_space = 0.0;  //  default
				$char_space = 0.0;  //  default
				$angle      = 0.0;   //  default
				$pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
			}
		</script>
		<div class="container">
			<!-- HEADER MARGIN FIRST PAGE -->
			<!--div id="headerA"><h1></h1></div-->
			<!-- HEADER MARGIN -->
			<!-- HEADER MARGIN ALL PAGES-->
			<!--div id="headerB"><h1></h1></div-->
			<!-- HEADER MARGIN ALL PAGES -->
			<div id="content" class="mainbox">
				<table>
					<tr>
						<td colspan="2">
							<table cellspacing="2" cellpadding="2" width="100%">
							<tr>
								<td align="left" class="td_simple">
									<?php
									$str_disp_text      = 'JREDA';
									if($ApplyOnlines->apply_state=='4' || strtolower($ApplyOnlines->apply_state)=='gujarat')
									{
										$str_disp_text  = 'GEDA';
									}
									$newSchemeApp 		= 0;
									$pvCapacityText 	= 'DC';
									$PolicyYear     	= '2015';
									$GRNumber 			= 'SLR-11-2015-2442-B';
									$GRNumberDate 		= '13-08-2015';
									$PolicyText 		= 'Solar Power';
									$PolicyText1 		= 'Solar';
									if(strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)))) {
										$newSchemeApp 	= 1;
										$pvCapacityText = 'AC';
										$PolicyYear 	= '2021';
										$GRNumber 		= 'SLR/11/20121/77/B1';
										$GRNumberDate 	= '29th December 2020';
									}
									if($applyOnlinesOthersData->scheme_id == 3) {
										$PolicyYear 	= '2023';
										$GRNumber 		= 'REN/e-file/20/2023/0476/B1';
										$GRNumberDate 	= '4th October 2023';
										$PolicyText 	= 'Renewable Energy';
										$PolicyText1 	= 'Renewable Energy';
									}
									?>
									<?php echo $str_disp_text;?> Application&nbsp;No
								</td>
								<td align="left" class="td_simple">
									<?php echo $ApplyOnlines->application_no; ?>
								</td>
								<td align="left" class="td_simple">Application Date</td>
								<td align="left" class="td_simple">
									<?php echo $APPLICATION_DATE; ?>
								</td>
								 <td align="left">
								 	<?php 
								 	$logo_image 	= '';
								 	$path 			= 'img/2_4.png';
							 		if (!empty($path) && file_exists($path)) {
										$type = pathinfo($path, PATHINFO_EXTENSION);
										$data = file_get_contents($path);
										$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
										$logo_image = "<img src=\"".$base64."\" width=\"70px\" />";
									} ?>
									<?php echo $logo_image;?>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellspacing="2" cellpadding="2" width="100%">
								<tr><td align="left" colspan="2" class="td_simple th_class">Solar PV Plant Detail</td></tr>
								<tr>
									<td align="left" class="td_simple">Solar PV Capacity (<?php echo $pvCapacityText;?>) to be Installed (kW)</td>
									<td align="left" class="td_simple"><?php echo $ApplyOnlines->pv_capacity; ?></td>
								</tr>
								<?php if($newSchemeApp == 1) { ?>
									<tr>
										<td align="left" class="td_simple">Plant DC Capacity to be Installed (kW)</td>
										<td align="left" class="td_simple"><?php echo $applyOnlinesOthersData->pv_dc_capacity; ?></td>
									</tr>
								<?php } 
								if($applyOnlinesOthersData->is_enhancement == 1) { ?>
									<tr>
										<td align="left" class="td_simple">Existing DC Capacity to be Installed (kW)</td>
										<td align="left" class="td_simple"><?php echo $applyOnlinesOthersData->existing_capacity; ?></td>
									</tr>
									<tr>
										<td align="left" class="td_simple">Existing AC Capacity to be Installed (kW)</td>
										<td align="left" class="td_simple"><?php echo $applyOnlinesOthersData->existing_ac_capacity; ?></td>
									</tr>
								<?php } ?>
								 
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="0" cellpadding="0" width="100%">
								<tr><td align="left" colspan="5" class="td_simple" style="background: #333; color: white; ">Installer/ Channel Partner Detail</td></tr>
								<tr>
									<td align="left" class="td_simple">Installer</td>
									<td align="left" class="td_simple" colspan="2">
									  <?php echo $ApplyOnlines->installer['installer_name']; ?>
									</td>
									<td align="left" class="td_simple">Contact No.</td>
									<td align="left" class="td_simple" >
									  <?php echo $ApplyOnlines->installer_mobile; ?>
									</td>
								</tr>
								<tr><td align="left" colspan="5" class="td_simple th_class">Contact Detail</td></tr>
								<tr>
									<td align="left" class="td_simple">CustomerName</td>
									<td align="left" class="td_simple"><?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?>
									</td>
									<td align="left" class="td_simple">Email</td>
									<td align="left" class="td_simple" >
										<?php echo $ApplyOnlines->email; ?>
									</td>
									<?php
										$profile_img = "-";
										if(isset($Applyonlinprofile['file_name']) && !empty($Applyonlinprofile['file_name'])) {
											$path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$Applyonlinprofile['file_name'];
											if (!empty($Applyonlinprofile['file_name']) && $Couchdb->documentExist($ApplyOnlines->id,$Applyonlinprofile['file_name'])) {
													$type = pathinfo($Applyonlinprofile['file_name'], PATHINFO_EXTENSION);
													//$type = pathinfo($path, PATHINFO_EXTENSION);
													//$data = file_get_contents($path);

													$data = $documentProfile;
													$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
													$profile_img = "<img src=\"".$base64."\" width=\"75px\" />";
												
											}
										}
									?>
									<td align="right" valign="top" rowspan="3" ><div style="text-align:right"><?php echo $profile_img; ?></div></td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Mobile</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->mobile; ?>
									</td>
									<td align="left" class="td_simple">Landline No</td>
									<td align="left" class="td_simple" colspan="2">
										<?php echo $ApplyOnlines->landline_no; ?>
									</td>
								</tr>
								<tr>
									
									<td align="left" class="td_simple">Address</td>
									<td align="left" class="td_simple" colspan="3">
										<?php echo $ApplyOnlines->address1." ".$ApplyOnlines->address2." ".$ApplyOnlines->district; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple">City</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->city; ?>
									</td>
									<td align="left" class="td_simple">State</td>
									<td align="left" class="td_simple" colspan="2">
										<?php echo $ApplyOnlines->state; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Pincode</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->pincode; ?>
									</td>
									<td align="left" class="td_simple">Communication Address</td>
									<td align="left" class="td_simple" colspan="2">
										<?php
										if ($ApplyOnlines->comunication_address_as_above == 1) {
											echo "Same As Address";
										} else if (!empty($ApplyOnlines->comunication_address)) {
											echo nl2br($ApplyOnlines->comunication_address);
										} else {
											echo "-";
										}
										?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellspacing="2" cellpadding="2" width="100%">
								<tr><td align="left" colspan="4" class="td_simple th_class">Bill Details</td></tr>
								<tr>
									<td align="left" class="td_simple">DisCom Name</td>
									<td align="left" colspan="3" class="td_simple"><?php echo isset($discom_list[$ApplyOnlines->discom]) ? $discom_list[$ApplyOnlines->discom]:'-'; ?></td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Consumer NO.</td>
									<td align="left" class="td_simple"><?php echo $ApplyOnlines->consumer_no; ?></td>
									<td align="left" class="td_simple">Division/Zone</td>
									<td align="left" class="td_simple"><?php echo isset($divison_list->title) ? $divison_list->title:'-'; ?></td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Sanctioned /Contract Load (in kW)</td>
									<td align="left" class="td_simple"><?php echo $ApplyOnlines->sanction_load_contract_demand; ?></td>
									
									<td align="left" class="td_simple">Category<?php if($newSchemeApp == 1) { ?><br/>Tariff<?php } ?></td>
									<td align="left" class="td_simple"><?php echo $ApplyOnlines->parameter_cats['para_value'];; ?><?php if($newSchemeApp == 1) { ?><br/><?php echo $applyOnlinesOthersData->tariff;?><?php } ?></td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Phase of Inverter</td>
									<td align="left" class="td_simple"><?php if($ApplyOnlines->transmission_line == '1'){ 
									echo 'Single Phase';
									 }
									 else
									{
										echo '3 Phase';
									}
									?> </td>
									<td align="left" class="td_simple"> Who will provide net meter?</td>
									<td align="left"  class="td_simple"><?php 
									if($ApplyOnlines->net_meter == 1) { echo 'DisCom'; } 
									elseif($ApplyOnlines->net_meter == 2) { echo 'Installer/ EA'; }
									else { echo '-'; } ?></td>
							
								</tr>
								<tr>
									<?php /*<td align="left" class="td_simple">Is the Applicant a Social Sector
									Organization?</td> 
									<td align="left" class="td_simple">
									<?php if($ApplyOnlines->social_consumer == 1){ 
									 echo 'Yes';
									  } else{
									 echo 'No';
										}?>
									</td>*/?>
									<td align="left" class="td_simple" colspan="2">Is the Applicant a Government Agency?</td>
									<td align="left" class="td_simple">
									<?php if($ApplyOnlines->govt_agency == 1){ 
									 echo 'Yes';
									  } else{
									 echo 'No';
										}?>
									</td>
									<td align="left" class="td_simple">&nbsp; </td>
								</tr>
								<?php if($newSchemeApp == 0) { ?>
									<tr>
										<td align="left" class="td_simple" colspan="2">Is the Applicant a Common Meter Connection?</td> 
										<td align="left" class="td_simple">
										<?php if($ApplyOnlines->common_meter == 1){ 
										 echo 'Yes';
										  } else{
										 echo 'No';
											}?>
										</td>
										<td align="left" class="td_simple">&nbsp; </td>
									</tr>
								<?php } ?>
								<tr>
									<td align="left" class="td_simple" colspan="2">Is the Applicant a MSME?</td> 
									<td align="left" class="td_simple">
									<?php 
									if($applyOnlinesOthersData->msme == 1) { echo 'Yes'; } 
									elseif($applyOnlinesOthersData->msme === 0) { echo 'No'; }
									else { echo '-'; } ?>
									</td>
									<td align="left" class="td_simple">&nbsp; </td>
								</tr>
								<?php if ($applyOnlinesOthersData->msme == 1) { ?>
								<?php if($newSchemeApp == 0) { ?>
									<tr>
										<td align="left" class="td_simple" colspan="2">Does the Applicant want to Install Solar PV more than 50% of the Contract Load?</td> 
										<td align="left" class="td_simple">
										<?php 
											if($applyOnlinesOthersData->contract_load_more == 1) { echo 'Yes'; } 
											elseif($applyOnlinesOthersData->contract_load_more == 0) { echo 'No'; }
											else { echo '-'; } ?>
										</td>
										<td align="left" class="td_simple">&nbsp; </td>
									</tr>
								<?php } ?>
								<tr>
									<td align="left" class="td_simple">Type of Applicant</td>
									<td align="left" class="td_simple"><?php $othersText     = (strtolower($applyOnlinesOthersData->type_of_applicant) == 'other') ? ' - '.$applyOnlinesOthersData->applicant_others : '';
										echo (!empty($applyOnlinesOthersData->type_of_applicant)) ? $applyOnlinesOthersData->type_of_applicant.$othersText :'-';?>
									</td>
									<td align="left" class="td_simple"> MSME Udhyog Aadhaar No.</td>
									<td align="left"  class="td_simple"><?php 
										echo !empty($applyOnlinesOthersData->msme_aadhaar_no) ? $applyOnlinesOthersData->msme_aadhaar_no : '-';  ?></td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Signing Authority Type </td>
									<td align="left" class="td_simple"><?php 
										echo (!empty($applyOnlinesOthersData->type_authority)) ? $applyOnlinesOthersData->type_authority :'-';?>
									</td>
									<td align="left" class="td_simple"> Name of Signing Authority</td>
									<td align="left"  class="td_simple"><?php 
										echo !empty($applyOnlinesOthersData->name_authority) ? $applyOnlinesOthersData->name_authority : '-';  ?></td>
								</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
					<?php if($applyOnlinesOthersData->rpo_rec == 1 || $applyOnlinesOthersData->rpo_rec == 2) { ?>
						<tr>
							<td colspan="2">
								<table cellspacing="2" cellpadding="2" width="100%">
									<tr><td align="left" colspan="4" class="td_simple th_class"><?php echo ($applyOnlinesOthersData->rpo_rec == 1) ? 'RPO Compliance' : 'REC Mechanism';?></td></tr>
									<?php if($applyOnlinesOthersData->rpo_rec == 1) { ?>
										<tr>
											<td align="left" class="td_simple">Captive</td>
											<td align="left" class="td_simple"><?php echo ($applyOnlinesOthersData->rpo_is_captive==1) ? 'Yes' : 'No';?></td>
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Whether beneficiary is an Obligated Entity covered under RPO obligation :<?php echo ($applyOnlinesOthersData->rpo_is_obligation == 1) ? 'Yes' : 'No';?></td>
											
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Documents of beneficiary in support of applicant being obligated entity for RPO compliance
												<ul>
													<li>Copy of GERC Distribution Licensee Certificate :<?php echo ($applyOnlinesOthersData->gerc_is_distribution == 1) ? 'Yes' : 'No';?></li>
													<li>Whether applicant has Captive Conventional Power Plant (CPP) :<?php echo ($applyOnlinesOthersData->rpo_is_cpp == 1) ? 'Yes - ' .$applyOnlinesOthersData->capacity_cpp .' kW': 'No';?></li>
													<li>Any previous Solar Project put up for captive RPO :<?php echo ($applyOnlinesOthersData->rpo_is_captive_rpo == 1) ? 'Yes' : 'No';?></li>
												</ul>
											</td>
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Certificate of STOA/ MTOA/ LTOA by SLDC/GETCO :<?php echo ($applyOnlinesOthersData->rpo_is_cert_getco == 1) ? 'Yes' : 'No';?>
												<?php if($applyOnlinesOthersData->rpo_is_cert_getco == 1) { ?>
													<ul>
														<li>Copy of GERC Distribution Licensee Certificate :<?php echo ($applyOnlinesOthersData->gerc_is_distribution == 1) ? 'Yes' : 'No';?></li>
													</ul>
												<?php } ?>
											</td>	
										</tr>
									
									<?php } else if($applyOnlinesOthersData->rpo_rec == 2) { ?>
										
										<tr>
											<td align="left" class="td_simple" colspan="2">Physical copy of application done on online REC registration website :<?php echo ($applyOnlinesOthersData->rec_is_registration == 1) ? 'Yes' : 'No';?></td>
											
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Copy of receipt for application done on online REC registration website :<?php echo ($applyOnlinesOthersData->rec_is_receipt == 1) ? 'Yes' : 'No';?>
											</td>
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Power Evacuation Arrangement permission letter from the host State Transmission Utility or the concerned Distribution Licensee, as the case may be :<?php echo ($applyOnlinesOthersData->rec_is_power_evaluation == 1) ? 'Yes' : 'No';?>
											</td>	
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Installation of Solar Project shall be allowed up to Sanctioned load/ Contract demand :<?php echo ($applyOnlinesOthersData->rec_is_allowed_sancation == 1) ? 'Yes' : 'No';?>
											</td>	
										</tr>
										<tr>
											<td align="left" class="td_simple" colspan="2">Minimum Capacity of Solar Project shall be 250 kW :<?php echo ($applyOnlinesOthersData->rec_is_valid_min_cap == 1) ? 'Yes' : 'No';?>
											</td>	
										</tr>
									
									<?php } ?>
								</table>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0" width="100%">
								<tr><td align="left" colspan="4" class="td_simple">
								<?php if($ApplyOnlines->disclaimer_subsidy == 1 && $newSchemeApp == 0){
								echo "I don’t want the subsidy on the Solar PV system."; 
								}?><br>
								 Yes, I am applying for installation of solar PV system under the CAPEX mode.<br></td></tr>
								
							</table>
						</td>
					</tr>
					<?php
						if($applyOnlinesOthersData->renewable_attr === 0 || $applyOnlinesOthersData->renewable_attr == 1)
						{
							?>
							<tr>
								<td colspan="2">
									<table cellspacing="0" cellpadding="0" width="100%">
										<tr><td align="left" colspan="4" class="td_simple">
										<?php
											echo ($applyOnlinesOthersData->renewable_attr == 1) ? 'Yes, The Applicant doesn’t want to keep the Renewable Attributes of this Solar PV system (Type 1).' : 'No, the Applicant wants to keep the Renewable Attributes (Type 2A)';
										?></td></tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<table cellspacing="0" cellpadding="0" width="100%">
										<tr><td align="left" colspan="4" class="td_simple">
										<?php
											echo ($applyOnlinesOthersData->renewable_rec == 1) ? 'Yes, This Application is done under the Renewable Energy Certificate (REC) Scheme' : (($applyOnlinesOthersData->renewable_rec === 0) ? 'No, the application is to be done to meet the Renewable Purchase Obligation of the Applicant' : '');
										?></td></tr>
									</table>
								</td>
							</tr>
						<?php 
						} 
					?>

					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0" width="90%;">
								<tbody>
									<tr><td align="left" colspan="4" class="td_bold">Declaration:</br></td>
								</tr>
								<tr>
									<td colspan="4" class="td_simple">
										<div style="text-align:justify;">
										<p>1. I (name and designation) _____________________________________  authorized representative of <?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?> declare that information filled in the application form is true and correct to my knowledge and GEDA is not responsible for providing land, the power evacuation and water supply, for the operation/maintenance of the power evacuation facilities and its uninterrupted functioning.  Further that GEDA is absolved from any loss that may occur on account of failure of the substation/transmission line and/or non-performance of any system of the project etc. I have read and understood and shall abide by the provision of the Gujarat <?php echo $PolicyText1;?> Policy – <?php echo $PolicyYear;?> vide Government of Gujarat GR no. <?php echo $GRNumber;?> dated <?php echo $GRNumberDate;?>. The solar Rooftop Project shall be installed under CAPEX model only with the ownership of premises and Solar Rooftop of the undersigned. I am aware that any other model for installation of Solar Rooftop System other than CAPEX model is not applicable under the provision of Gujarat <?php echo $PolicyText;?> Policy – <?php echo $PolicyYear;?>.</p>
										<p> 2. The entire electrical safety, structural stability and performance of the system shall be the entire responsibility of the Applicant and GEDA shall be absolved of any responsibility whatsoever that may arise during the lifespan of the solar PV system.</p>
										</div>
									</td>
								</tr>
								</tbody> 
							</table>
						</td>
					</tr>
					<?php if(strtotime($ApplyOnlines->created) >= strtotime(OPEN_NEW_QUATA) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(OPEN_NEW_QUATA)))) { ?>
					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0" width="90%;">
								<tbody>
									<tr><td align="left" colspan="4"><span class="td_bold">UNDERTAKING:</span>
										<span class="td_simple">I hereby agree that:</span></td>
								</tr>
								<tr>
									<td colspan="4" class="td_simple">
										<div style="text-align:justify;">
										<p>1. This Project is registered under the Capital Expenditure (CAPEX) mode, where the solar PV system is owned by me and electricity generated is used by ME.</p>
										<p>2. I have on my OWN selected the Installer upon due diligence and have mutually agreed with the terms and conditions amongst ourselves.</p>
										<p>3. I am aware that GEDA has no role whatsoever in the selection of the Installer/Vendor and thus GEDA is not responsible for INSTALLER’s technical and financial capabilities, quality and integrity, any kind of delay; financial or technical loss, quality and standards of the system and its components; theft, financial transaction done or any criminal assault occurred because of the selection of the Installer.</p>
										<p>4. I am fully responsible for the Solar PV system that will be installed under this application.</p>
										<?php if($applyOnlinesOthersData->scheme_id == 3) { ?>
											<p>5. I am aware that the Gujarat Renewable Energy Policy – <?php echo $PolicyYear;?> & GERC Net Metering Regulations from time to time and its amendments thereof, if any under which this project is registered does not have any provision of subsidy or financial assistance.</p>
										<?php } else { ?>
											<p>5. I am aware that the <?php echo $PolicyText1;?> Policy - <?php echo $PolicyYear;?> and its amendments thereof under which this project is registered does not have any provision of subsidy or financial assistance.</p>
										<?php } ?>
										<?php if($applyOnlinesOthersData->scheme_id == 3) { ?>
											<p>6. I agree to abide by the provisions of the Gujarat Renewable Energy Policy – <?php echo $PolicyText;?> of the Government of Gujarat & GERC Net Metering Regulations from time to time and its amendments thereof, if any.</p>
										<?php } else {?> 
											<?php if($applyOnlinesOthersData->rpo_rec == 1) { ?>
												<p>6. I agree to abide by the provision of <?php echo $PolicyText;?> Policy-2021 of Government of Gujarat and GERC Regulations with all amendment thereof.</p>
											<?php } else { ?>
												<p>6. I agree to abide by the provisions of the <?php echo $PolicyText;?> Policy - <?php echo $PolicyYear;?> of the Government of Gujarat with all amendments thereof.</p>
											<?php } ?>
										<?php } ?>
										
										</div>
									</td>
								</tr>
								</tbody> 
							</table>
						</td>
					</tr>    
					<?php } ?>
					<?php if($ApplyOnlines->apply_onlines_others['msme'] == 1 && $applyOnlinesOthersData['contract_load_more'] == 1 && $newSchemeApp == 0) { ?>
					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0" width="90%;">
								<tbody>
									<tr><td align="left" colspan="4"><span class="td_bold">A Declaration of the Applicant/ Owner of Project:</span>
										<span class="td_simple">I am fully aware and understood about the “amendments in Gujarat <?php echo $PolicyText;?> Policy- <?php echo $PolicyYear;?> for MSME Manufacturing Enterprises” which are as under:</span></td>
								</tr>
								<tr>
									<td colspan="4" class="td_simple">
										<div style="text-align:justify;">
											<p>1. In respect of MSME manufacturing consumers across the state, Micro Small and Medium Manufacturing Enterprises shall be allowed to set up Solar Projects of any capacity, irrespective of its Sanctioned Load/Contract Demand with the concerned DISCOM.</p>
											
										</div>
									</td>
								</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0" width="90%;">
								<tbody>
									<tr><td align="left" colspan="4">
										<p>2. Transmission & Wheeling Charges and Losses for wheeling of power for Captive use/ Third Party Shall be applicable as applicable to Open-Access consumers determined by GERC, as amended from time to time.</p>
										<p>3. Electricity Duty shall be applicable at applicable rate. Amount of electricity duty recovered shall be paid to DISCOMs towards cross subsidization.</p>
										<p>4. Cross Subsidy Surcharge and Additional Surcharge shall be applicable as applicable to Open-Access consumers in case of Third Party Sale.</p>
										<p>5. Energy Accounting shall be carried out on 07:00 hours to 18:00 hours basis of the same day (i.e. not on monthly basis).</p>
										<p>6. Any surplus solar energy not consumed by consumer as per Energy Accounting shall be billed/ Purchased by DISCOMs at Rs.2.25/unit.</p>
										<p>7. Requisite class of ABT meter shall be installed as may be specified by DISCOM.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="4" class="td_simple">
										<div style="text-align:justify;">
											I _____________________________________ (Name & Designation) authorised representative of M/s._____________________________________ (Name of MSME Manufacturing Enterprises) declare that the I have read and understood and shall abide by the provision of “amendments in Gujarat <?php echo $PolicyText;?> Policy- <?php echo $PolicyYear;?> for MSME Manufacturing Enterprises” vide Government of Gujarat GR no. (1) SLR-11/2015/2442/B1 dated 26-09- 2019, (2) SLR-11/2015/2442/B1 dated 01-09- 2020 & Gujarat Solar Power Policy – 2015 vide Government of Gujarat GR no. SLR-11/2015/2442/B dated 13-08- 2015 and also abide by the provisions declared from time to time by GERC, GOG, CEIG & GEDA.
										</div>
									</td>
								</tr>
								</tbody> 
							</table>
						</td>
					</tr>    
					<?php } ?>
					<tr>
						<td colspan="2">
							<table cellspacing="2" cellpadding="2" style="margin-top:30px;">
								<tr><td align="right" colspan="4" class="td_simple">
								<div style="width:300px;float:right;text-align:center;">
									<!-- (<?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?>)
								 -->
								<div style="border-bottom:1px solid #000;width:auto; margin-top:25px;">
									</div>
								<div style="padding-top: 25px" class="td_simple">(Signature of Applicant and seal of the Organization)</div>
							</div>
								</td></tr>
								
							</table>
						</td>
					</tr>
					
				   
				</table>
			</div>
		</div>
	</body>
</html>