<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Corrigendum Letter</title>
		<!-- Style CSS -->
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css"/>
		<style>
			@font-face {
			font-family:'arial_italic';
			src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALI.TTF');
			}
			@font-face {
			font-family: 'arial_bold';
			src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALBD.TTF');
			}
			@font-face {
			font-family: 'arial_simple';
			src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/arial.ttf');
			}
			.td_bold{ font-family: 'arial_bold';font-size: 14px;}
			.td_italic{font-family: 'arial_italic';font-size: 14px;}
			.td_simple{font-family: 'arial_simple';font-size: 14px;}
			@page {
				margin: 10px;
			}
			body {
				margin: 20px;
			}
			.text_justify li{
				text-align:justify;
			} 
			</style>
	</head>
	<body id="pdf-header">
		<script type="text/php">
			if (isset($pdf)) {
				$curdate    = date('d-M-Y');
				$x          = 35;
				$y          = 810;
				$text       = "Date: $curdate";
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
			<?php
			$newSchemeApp       = 0;
			$pvCapacityText     = 'DC solar PV module';
			$pvCapacityACText 	= 'AC';
			$PolicyYear     	= '2015';
			$GRNumber 			= 'SLR-11-2015-2442-B';
			$GRNumberDate 		= '13-8-2015';
			$pvDCText			= '';
			if(isset($ApplyOnlines->created) && strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || isset($applyOnlineGedaDate->created) && strtotime($applyOnlineGedaDate->create) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
				$newSchemeApp   	= 1;
				$pvCapacityText 	= 'AC';
				$pvCapacityACText	= 'DC';
				$PolicyYear 		= '2021';
				$GRNumber 			= 'SLR/11/20121/77/B1';
				$GRNumberDate 		= '29th December 2020';
				$pvDCText 			= ', '.$applyOnlinesOthersData->pv_dc_capacity.' kW DC Capacity';
			}
            ?>
			<!-- HEADER MARGIN FIRST PAGE -->
			<!--div id="headerA"><h1></h1></div-->
			<!-- HEADER MARGIN -->
			<!-- HEADER MARGIN ALL PAGES-->
			<!--div id="headerB"><h1></h1></div-->
			<!-- HEADER MARGIN ALL PAGES -->
			<div id="content" class="mainbox">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" style="margin-right:10px;">
						<?php 
							$logo_image     = '';
							$path           = 'img/geda.png';
							if (!empty($path) && file_exists($path)) {
								$type = pathinfo($path, PATHINFO_EXTENSION);
								$data = file_get_contents($path);
								$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
								$logo_image = "<img src=\"".$base64."\" height=\"80px\" />";
							} 
						?>
						<?php echo $logo_image;?>
						<div align="center">
							<p style="color:black;margin-bottom:0;font-size: 22px;" class="td_bold">GUJARAT ENERGY DEVELOPMENT AGENCY</p>
							<p class="td_simple">4th Floor, Block No. 11-12, Udyog Bhawan, Gandhinagar,<br>
							Ph: 079-23257251-54, GST. No. 24AAATG1858Q1ZA
							</p>
						</div> 
						</td>   
					</tr>   
					<tr>
					   <td>
							<table width="100%">
								<tr>
									<td align="left" class="td_italic">
									   <p><span>Corrigendum Letter No.: <?php echo $ApplyOnlines->geda_application_no; ?></span></p> 
									</td>
									<td align="right" class="td_simple">
										<p><span style="margin-right:15px"> Date: <?php  echo date('d-M-Y');
										?>
									</span></p>
									</td>
								</tr>
							</table>
							
						</td>            
					</tr>
					<tr>
						<td align="left" class="td_simple">
							 To:
						</td>
								
					</tr>
					<tr>
						<td class="td_simple">
							<?php echo $ApplyOnlines->customer_name_prefixed;?> <?php echo $ApplyOnlines->name_of_consumer_applicant;?><br>
							<?php echo $ApplyOnlines->address1;?>
							<br>
							<?php echo $ApplyOnlines->address2;?>
							<br>
							<?php echo $ApplyOnlines->city;?>
						</td>         
					</tr>
					<tr>
						<td class="td_simple"> 
							<p class="td_bold">Sub : Corrigendum for Registration for Rooftop Solar PV (RTPV) system under Gujarat Solar Power Policy – 2021 (the “Policy”) </p>
							<p>
								<table>
									<tr>
										<td>Ref : </td>
										<td class="td_italic">1.</td>
										<td class="td_italic"> Application No. <?php echo $ApplyOnlines->application_no; ?> dated <?php echo $APPLICATION_DATE;?></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="td_italic">2.</td>
										<td> DisCom Consumer No. <?php echo $ApplyOnlines->consumer_no ;?> and Sanctioned Load/ Contract Demand <?php echo $ApplyOnlines->sanction_load_contract_demand; ?> kW</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="td_italic" valign="top">3.</td>
										<td>Solar PV Capacity (<?php echo $pvCapacityText;?>) to be Installed <?php echo $ApplyOnlines->pv_capacity; ?> (kW) 
											<?php if(!empty($applyOnlinesOthersData->pv_dc_capacity)) { ?>
											; Plant <?php echo $pvCapacityACText;?> Capacity to be Installed <?php echo $applyOnlinesOthersData->pv_dc_capacity; ?> (kW)<?php } ?></td>
									</tr>
									<?php if($applyOnlinesOthersData->is_enhancement == 1) { ?>
									<tr>
										<td>&nbsp;</td>
										<td class="td_italic" valign="top">4.</td>
										<td><?php if($newSchemeApp == 0) { ?>
											Existing Capacity <?php echo $applyOnlinesOthersData->existing_capacity;?> kW; Total Capacity: <?php echo number_format(($applyOnlinesOthersData->existing_capacity + $ApplyOnlines->pv_capacity),3);?> kW
											<?php  } else { 
												$actualDC 		= !empty($applyOnlinesOthersData->pv_dc_capacity) ? $applyOnlinesOthersData->pv_dc_capacity : 0;
												$existingAC 	= !empty($applyOnlinesOthersData->existing_ac_capacity) ? $applyOnlinesOthersData->existing_ac_capacity : 0;?>
											Existing DC Capacity <?php echo $applyOnlinesOthersData->existing_capacity;?> kW; Applied DC Capacity: <?php echo $actualDC;?> kW; Total Capacity: <?php echo number_format(($applyOnlinesOthersData->existing_capacity + $actualDC),3);?> kW<br/>
											Existing AC Capacity <?php echo $existingAC;?> kW; Applied AC Capacity: <?php echo $ApplyOnlines->pv_capacity;?> kW; Total Capacity: <?php echo number_format(($ApplyOnlines->pv_capacity + $existingAC),3);?> kW
											<?php } ?></td>
									</tr>
									<?php } ?>
								</table>
							</p>          
						</td>
					</tr>
					 <tr>
						<td class="td_simple">
							<div style="margin-top:5px">
								 Dear Sir,
							</div>
							<div style="margin-top:5px">
								The Registration Number with the name of the applicant and the name of the GEDA empaneled vendor that you have selected must always be quoted for any communication with GEDA in this regards. Your application is registered for installation of the rooftop solar system of <?php echo $ApplyOnlines->pv_capacity; ?> kW <?php echo $pvCapacityText;?><?php echo $pvDCText;?>  (the “Capacity”) under  <u><?php echo ucfirst($category_name);?></u>.      
							</div>
							<div style="margin-top:5px"> 
								This Registration is neither transferable to other applicant nor is transferable to any other GEDA Empaneled Vendor and the registration shall be valid for the commissioning of the rooftop system on till validity of the Policy.  Further, this letter shall be superseding the Registration Letter issued earlier.
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" class="td_simple">
							Yours faithfully
							<div style="margin-top:5px">
								For Gujarat Energy Development Agency
							</div>            
						</td>
					</tr>
					 <tr>
						<td class="td_italic" >
							<table style="border:1px solid #000000;" cellpadding="5">
								<tr>
									<td rowspan="3" style="width:12%; vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										CC To
									</td>
									 <td rowspan="3" style="width:3%; vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										:
									</td>
									<td style="vertical-align: top;width:33%;border-right: 1px solid #000000;" class="td_italic">
										1. The Chief Engineer<br>
										  <?php echo $discom_short_name->title;?>
									</td>
									<td style="vertical-align: top;width:52%;line-height: 15px;" class="td_italic"> 
										With a request for ascertaining the technical feasibility, signing of the connectivity agreement and installation of the bi-directional meter.
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										2. The Chief Electrical Inspector
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										With a request to approve the SLD and grant charging permission for the eligible system capacity.
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										3.<?php echo $ApplyOnlines->installer['installer_name'];?>
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										For necessary action, and installation of the solar system upon technical feasibility done by the DISCOM
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td  class="td_simple">
										<span class="td_bold">DISCLAIMER: </span><span class="td_simple">The PV Installer/ Vendor/ EPC Company is selected by the Applicant/Consumer as per his choice, GEDA owning no responsibility of the EPCs for his technical, financial strength quality integrity, etc. and the financial transactions with PV Installer. </span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td align="center" class="td_bold">
									(This is Computer generated letter and does not require any signature)
									</td>
								</tr>
							</table>
						</td>
					</tr>                               
				</table>
			</div>
		</div>
	</body>
</html>