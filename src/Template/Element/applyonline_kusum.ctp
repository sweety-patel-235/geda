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
									
									$str_disp_text  = 'GEDA';
									
									$newSchemeApp 		= 0;
									$pvCapacityText 	= 'DC';
									$PolicyYear     	= '2015';
									$GRNumber 			= 'SLR-11-2015-2442-B';
									$GRNumberDate 		= '13-08-2015';
									if(strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)))) {
										$newSchemeApp 	= 1;
										$pvCapacityText = 'AC';
										$PolicyYear 	= '2021';
										$GRNumber 		= 'SLR/11/20121/77/B1';
										$GRNumberDate 	= '29th December 2020';
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
								<tr><td align="left" colspan="4" class="td_simple th_class">Description of Applicant</td></tr>
								<tr>
									<td align="left" class="td_simple">Applicant Type</td>
									<td align="left" class="td_simple"><?php echo isset($ParametersDetails->para_value) ? $ParametersDetails->para_value : ''; ?></td>
									<td align="left" class="td_simple">Name</td>
									<td align="left" class="td_simple"><?php echo (!empty($ApplyOnlines->application_type_name)) ?$ApplyOnlines->application_type_name :"-" ; ?></td>
								</tr>
								
								
								<?php if($ApplyOnlinesTable->TypeGroup == $ApplyOnlines->applicant_type_kusum) { ?>							
									<tr>
										<td align="left" class="td_simple" colspan="2">List of Members</td>
										<td align="left" class="td_simple" colspan="2"><?php $arrM = array(); 
										if(!empty($arrMembers))  { 
											foreach($arrMembers as $memberData) {
												$arrM[]	= 	$memberData->name;
											} 
										}
										if(count($arrM) > 0) {
											echo implode(", ",$arrM);
										}
										?></td>
									</tr>
								<?php } ?>
								<tr>
									<td align="left" class="td_simple" colspan="2">Correspondence address</td>
									<td align="left" class="td_simple" colspan="2"><?php echo $ApplyOnlines->correspondence_address; ?></td>
								</tr>
								<tr>
									<td align="left" class="td_simple" colspan="2">Name of the authorized person</td>
									<td align="left" class="td_simple" colspan="2"><?php echo (!empty($ApplyOnlines->authorized_person)) ?$ApplyOnlines->authorized_person :"-" ; ?></td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Mobile Number</td>
									<td align="left" class="td_simple"><?php echo $ApplyOnlines->mobile; ?></td>
								
									<td align="left" class="td_simple">E-mail Id</td>
									<td align="left" class="td_simple"><?php echo (!empty($ApplyOnlines->email)) ?$ApplyOnlines->email :"-" ; ?></td>
								</tr>
								 
							</table>
						</td>
					</tr>
					<?php
						$disDetails 	= $ApplyOnlinesTable->getDiscomDetails($ApplyOnlines->circle,$ApplyOnlines->division,$ApplyOnlines->subdivision,$ApplyOnlines->area,1);
						$district_name 	= $ApplyOnlinesTable->getDistrictName($ApplyOnlines->district);
					?>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="0" cellpadding="0" width="100%">
								<tr><td align="left" colspan="5" class="td_simple" style="background: #333; color: white; ">Details of Sub-station</td></tr>
								<tr>
									<td align="left" class="td_simple">Name of Discom</td>
									<td align="left" class="td_simple">
										<?php echo isset($disDetails[0]) ? $disDetails[0] : '-'; ?>
									</td>
									<td align="left" class="td_simple">Name of Division</td>
									<td align="left" class="td_simple" >
										<?php echo isset($disDetails[2]) ? $disDetails[2] : '-'; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Name of Sub Division</td>
									<td align="left" class="td_simple">
										<?php echo isset($disDetails[3]) ? $disDetails[3] : '-'; ?>
									</td>
									<td align="left" class="td_simple">Name of power utility</td>
									<td align="left" class="td_simple" >
										<?php echo $ApplyOnlines->name_power_utility; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple">District</td>
									<td align="left" class="td_simple">
										<?php echo !empty($district_name) ? $district_name : ''; ?>
									</td>
									<td align="left" class="td_simple">Taluka</td>
									<td align="left" class="td_simple" >
										<?php echo $ApplyOnlines->panchayat_committee; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple" colspan="3">Name of Sub-station</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->name_substation; ?> 
									</td>
								</tr>
								<tr>	
									<td align="left" class="td_simple" colspan="3">Declared capacity for Solar Power Project (MW)</td>
									<td align="left" class="td_simple" >
										<?php echo $ApplyOnlines->declare_capacity; ?>
									</td>
								</tr>
								<tr><td align="left" colspan="5" class="td_simple th_class">Land and Others Detail</td></tr>
								
								<tr>
									<td align="left" class="td_simple">Name of Village</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->village_name; ?>
									</td>
									<td align="left" class="td_simple">Taluka</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->taluka; ?>
									</td>
								</tr>
								<tr>
									
									<td align="left" class="td_simple">District</td>
									<td align="left" class="td_simple" colspan="3">
										<?php   $land_district_name = $ApplyOnlinesTable->getDistrictName($ApplyOnlines->land_district);
											echo $land_district_name; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple" colspan="3">Proposed Solar PV Power Plant capacity (in MW)</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->pv_capacity; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple" colspan="3">Distance between the Proposed land and Sub-station notified by the Discom</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->distance_plant; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple" colspan="3">Options available to the applicants for installation of Solar Power Plants</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->option_solar; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple">Installer Name</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->installers['installer_name']; ?>
									</td>
									<td align="left" class="td_simple">Installer Email</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->installer_email; ?>
									</td>
								</tr>
								<tr>
									<td align="left" class="td_simple" colspan="3">Installer Mobile</td>
									<td align="left" class="td_simple">
										<?php echo $ApplyOnlines->installer_mobile; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0" width="90%;">
								<tbody>
									<tr><td align="left" colspan="4" class="td_bold">Declaration:</br></td>
								</tr>
								<tr>
									<td colspan="4" class="td_simple">
										<div style="text-align:justify;">
										<p>1. I (name and designation) _____________________________________  authorized representative of <?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?> declare that information filled in the application form is true and correct to my knowledge and GEDA is not responsible for providing land, the power evacuation and water supply, for the operation/maintenance of the power evacuation facilities and its uninterrupted functioning.  Further that GEDA is absolved from any loss that may occur on account of failure of the substation/transmission line and/or non-performance of any system of the project etc. I have read and understood and shall abide by the provision of the Gujarat Solar Policy – <?php echo $PolicyYear;?> vide Government of Gujarat GR no. <?php echo $GRNumber;?> dated <?php echo $GRNumberDate;?>. The solar Rooftop Project shall be installed under CAPEX model only with the ownership of premises and Solar Rooftop of the undersigned. I am aware that any other model for installation of Solar Rooftop System other than CAPEX model is not applicable under the provision of Gujarat Solar Power Policy – <?php echo $PolicyYear;?>.</p>
										<p> 2. The entire electrical safety, structural stability and performance of the system shall be the entire responsibility of the Applicant and GEDA shall be absolved of any responsibility whatsoever that may arise during the lifespan of the solar PV system.</p>
										</div>
									</td>
								</tr>
								</tbody> 
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellspacing="2" cellpadding="2" style="margin-top:30px;">
								<tr><td align="right" colspan="4" class="td_simple">
									<div style="width:350px;float:right;text-align:center;">
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