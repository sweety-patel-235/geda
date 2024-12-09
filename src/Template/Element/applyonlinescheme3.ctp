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
			$pvCapacityACText   = 'AC';
			$PolicyYear         = '2015';
			$GRNumber           = 'SLR-11-2015-2442-B';
			$GRNumberDate       = '13-8-2015';
			$pvDCText           = '';
			if(isset($ApplyOnlines->created) && strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || isset($applyOnlineGedaDate->created) && strtotime($applyOnlineGedaDate->create) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
				$newSchemeApp       = 1;
				$pvCapacityText     = 'AC';
				$pvCapacityACText   = 'DC';
				$PolicyYear         = '2021';
				$GRNumber           = 'SLR/11/20121/77/B1';
				$GRNumberDate       = '29th December 2020';
				$pvDCText           = ', '.$applyOnlinesOthersData->pv_dc_capacity.' kW DC Capacity';
			}
			?>
			<!-- HEADER MARGIN FIRST PAGE -->
			<!--div id="headerA"><h1></h1></div-->
			<!-- HEADER MARGIN -->
			<!-- HEADER MARGIN ALL PAGES-->
			<!--div id="headerB"><h1></h1></div-->
			<!-- HEADER MARGIN ALL PAGES -->
			<div id="content" class="mainbox">
				<table width="100%">
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
							<p class="td_simple">4th Floor, Block No. 11-12, Udyog Bhawan, Gandhinagar, GJ, INDIA<br>
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
									   <p><span>Registration No.: <?php echo $ApplyOnlines->geda_application_no; ?></span></p> 
									</td>
									<td align="right" class="td_simple">
										<p><span style="margin-right:15px"> Date: <?php 
										if(!empty($applyOnlineGedaDate) && !empty($applyOnlineGedaDate->created))
										{
											echo date('d-M-Y',strtotime($applyOnlineGedaDate->created));
										}
										else
										{
											echo date('d-M-Y',strtotime($ApplyOnlines->created));
										}
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
							<p class="td_bold">Sub : Registration for Rooftop Solar PV (RTPV) system under Gujarat Renewable Energy Policy – 2023, G.R. No. REN/e-file/20/2023/0476/B1 dated 4th October 2023 and amendments thereof, if any (the “Policy”)</p>
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
										<td><?php 
												$actualDC       = !empty($applyOnlinesOthersData->pv_dc_capacity) ? $applyOnlinesOthersData->pv_dc_capacity : 0;
												$existingAC     = !empty($applyOnlinesOthersData->existing_ac_capacity) ? $applyOnlinesOthersData->existing_ac_capacity : 0;?>
											Existing DC Capacity <?php echo $applyOnlinesOthersData->existing_capacity;?> kW; Applied DC Capacity: <?php echo $actualDC;?> kW; Total Capacity: <?php echo number_format(($applyOnlinesOthersData->existing_capacity + $actualDC),3);?> kW<br/>
											Existing AC Capacity <?php echo $existingAC;?> kW; Applied AC Capacity: <?php echo $ApplyOnlines->pv_capacity;?> kW; Total Capacity: <?php echo number_format(($ApplyOnlines->pv_capacity + $existingAC),3);?> kW
											</td>
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
							<div style="margin-top:5px;text-align: justify;">
								Thank you for your online application for setting up of a rooftop solar PV system. Your application is registered with GEDA and the Registration Number is <?php echo $ApplyOnlines->geda_application_no; ?>. This Registration Number with the name of the applicant and the name of the Installer that you have selected must always be quoted for any communication with GEDA in this regards.        
							</div>         
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="margin-top:5px;text-align: justify;">
								Your application is registered for installation of the rooftop solar system of <?php echo $ApplyOnlines->pv_capacity; ?> kW <?php echo $pvCapacityText;?><?php echo $pvDCText;?> (the “Capacity”) under <u><?php echo ucfirst($category_name);?></u> sector.
								.      
							</div>
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="margin-top:5px; text-align: justify;">
								This Registration Letter is copied to the Installer selected by you, the Chief Electrical Inspector, CEA, (if applicable), and to the DISCOM in whose service area the rooftop system is to be located, for their further process. The registration of your project shall be governed by the following terms and conditions:
							</div>
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="margin-left:15px">
								<p style="text-align: justify;">
									1. This Registration is neither transferable to other applicant nor is transferable to any other Solar PV Installer, (the "Installers"). This Registration shall be governed by the provisions of Gujarat Renewable Energy Policy – 2023 & GERC Net Metering Regulations from time to time and its amendments thereof, if any.
								</p>
								<p style="text-align: justify;">2. In the event if you have to change your selected Installer after the Registration, you need to cancel this Registration and apply under a fresh application.</p>
								<p style="text-align: justify;">3. You have registered the solar PV plant under the Clause no. 9.2 of Gujarat Renewable Energy Policy-2023.</p>   
							</div>
						</td>
					</tr>
					<tr>
						<td class="td_simple"> 
							<div style="margin-left:15px"> 
								<p style="text-align: justify;">4. You shall ensure that the location proposed for installation of the system is shadow free during the entire day and also keep the system clean and dust free for optimum output.</p>
								<p style="text-align: justify;">5. You shall sign the connectivity agreement with the DISCOM upon its ascertaining the technical feasibility of the system installation.</p>
								<p style="text-align: justify;">6. You shall allow access to your premises by representatives of GEDA/ DISCOM/ CEI for installation of  respective meter as per provision of Gujarat Renewable Energy Policy – 2023 and its amendments thereof, if any / inspection/ verification of the system.</p>
								
								<p style="text-align: justify;">7. The respective meter shall be installed in accordance to the technical specifications defined by the DISCOM.</p>
							  
								<p style="text-align: justify;">8. The technical specifications of the Solar PV system and all its allied components, shall be mutually agreed between YOU and the INSTALLER selected by you.</p>
								<p style="text-align: justify;">9. It is YOUR sole responsibility to ensure and check all the components properly with the selected INSTALLER. In no case, GEDA shall responsible for the delay in installation, sub-standard material, low generation or any issue related to performance and quality. However, it is advisable to always install solar PV system in the shadow free area and with proper sizing and structures for maximum generation. Further, the Applicant shall check the requisite documents and reports from the suitable simulation tools on its own or through the Installer to ascertain the above mentioned points.</p> 
								
							   
								<p style="text-align: justify;">10. This Application has been submitted under <span class="td_bold"><?php echo ucfirst($category_name);?></span>  <span class="td_simple">Category.</span></p>
							  
								<p style="text-align: justify;" class="td_simple">11. The Provision of “Approved List of Models and Manufacturers” (ALMM) & its amendment issued by MNRE from time to time shall be applicable.</p>
								<p style="text-align: justify;" class="td_simple">12. The Provision & amendment issued by GERC/ EPD from time to time shall be applicable.</p>
								<p style="text-align: justify;" class="td_simple">13. This project shall be governed by the Gujarat Renewable Energy Policy-2023 and the GERC regulations issued from time to time. The Renewable Energy Generator shall be bound by all the provisions as decided by the GERC.</p>
								<p style="text-align: justify;">14. The Applicant, <?php echo $ApplyOnlines->name_of_consumer_applicant;?>, has given following signed undertaking in the application form for installation of the solar roof top system under this registration.</p>
								<?php if(isset($NewText) && !empty($NewText)){?>
                                    <p style="text-align: justify;">15. <?php echo $NewText ?></p>
                                <?php } ?>
							</div>          
						</td>
					</tr>
					<tr>
						<td class="td_simple"> <span class="td_bold">UNDERTAKING: </span><span class="td_simple"> I hereby agree that:</span>
							<div style="margin-left:15px"> 
								<p style="text-align: justify;">1. This Project is registered under the Capital Expenditure (CAPEX) mode, where the solar PV system is owned<br/>by me and electricity generated is used by ME.</p>
								<p style="text-align: justify;">2. I have on my OWN selected the Installer upon due diligence and have mutually agreed with the terms and conditions amongst ourselves.</p>
								<p style="text-align: justify;">3. I am aware that GEDA has no role whatsoever in the selection of the Installer/Vendor and thus GEDA is not responsible for INSTALLER’s technical and financial capabilities, quality and integrity, any kind of delay; financial or technical loss, quality and standards of the system and its components; theft, financial transaction done or any criminal assault occurred because of the selection of the Installer.</p>
								<p style="text-align: justify;">4. I am fully responsible for the Solar PV system that will be installed under this application.</p>
								<p style="text-align: justify;">5. I am aware that the Gujarat Renewable Energy Policy – 2023 and its amendments thereof under which this project is registered does not have any provision of subsidy or financial assistance.</p>
						
								<p style="text-align: justify;">6. I agree to abide by the provisions of the Gujarat Renewable Energy Policy – 2023 and GERC Net Metering Regulations from time to time of with all  amendments thereof.</p>
							  
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
									<td rowspan="4" style="width:12%; vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										CC To
									</td>
									 <td rowspan="4" style="width:3%; vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
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
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										4.CEA
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										With a request to approve the SLD and grant charging permission for the eligible system capacity. (if applicable)
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