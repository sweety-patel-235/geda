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
			font-family:'arial_italic';
			src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALI.TTF');
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
			<!-- HEADER MARGIN FIRST PAGE -->
			<!--div id="headerA"><h1></h1></div-->
			<!-- HEADER MARGIN -->
			<!-- HEADER MARGIN ALL PAGES-->
			<!--div id="headerB"><h1></h1></div-->
			<!-- HEADER MARGIN ALL PAGES -->
			<div id="content" class="mainbox">
				<table width="100%">
					<tr>
						<td align="right" style="margin-right:10px;" >
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
							<table>
								<tr>
									<td align="left" class="td_italic">
									   <p><span>Registration No.: <?php echo $ApplyOnlines->geda_application_no; ?></span></p> 
									</td>
									<td align="right" class="td_simple">
										<p> <span style="margin-right:15px"> Date: <?php 
											if(!empty($applyOnlineGedaDate) && !empty($applyOnlineGedaDate->created))
											{
												echo date('d-M-Y',strtotime($applyOnlineGedaDate->created));
											}
											else
											{
												echo date('d-M-Y',strtotime($ApplyOnlines->created));
											}
											?> 
											</span>
										</p>
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
							<p class="td_bold">Sub : Registration for Rooftop Solar PV (RTPV) system under Gujarat Solar Power Policy – 2015 (the “Policy”) for the year 2018-19</p>
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
								Thank you for your online application for setting up of a rooftop solar PV system. Your application is registered with GEDA and the Registration Number is <?php echo $ApplyOnlines->geda_application_no; ?>. This Registration Number with the name of the applicant and the name of the GEDA empaneled vendor that you have selected must always be quoted for any communication with GEDA in this regards.                
							</div>         
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="margin-top:5px">
							   Your application is registered for installation of the rooftop solar system of <?php echo $ApplyOnlines->pv_capacity; ?> kW DC solar PV module (the “Capacity”) under <u>industrial/commercial</u>.     
							</div>
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="margin-top:5px">
							   This Registration Letter is copied to the selected GEDA empaneled vendor, the Chief Electrical Inspector and to the DISCOM in whose service area the rooftop system is to be located, for their further process. The registration of your project shall be governed by the following terms and conditions: 
							</div>
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="margin-left:15px">
								<p>1. This Registration is neither transferable to other applicant nor is transferable to any other GEDA Empaneled Vendor. This registration shall be valid up to 31-3-2020, i.e. upto the validity of the Solar Power Policy- 2015.  </p>
								<p>2. The capacity of the system registered cannot be altered upon registration; however, the permissible limit for installation of the system shall be within +/- 5 % of the registered capacity. Irrespective of the kW rating of the system proposed to be installed, if the applicant is a three phase consumer,  the invertor shall be of Three Phase. The capacity of the system to be installed in kW permissible for installation cannot be more than 50 % of the contract demand with the Discom.</p>
								<?php
								$sr_no = 3;
								if($applyOnlinesOthersData->renewable_attr == 0 && $applyOnlinesOthersData->renewable_rec == 1)
								{
									?>
									<p><?php echo $sr_no; $sr_no++;?>. You have opted to keep the Renewable Attributes and to register the solar PV plant under the Clause no. 9.1.3 Type 2B. The project shall be installed under the Renewable Energy Certificate (REC) scheme and it also has to be registered at REC portal https://recregistryindia.nic.in/. </p>
									<?php
								}
								if($applyOnlinesOthersData->renewable_attr === 0 && $applyOnlinesOthersData->renewable_rec === 0)
								{
									?>
									<p><?php echo $sr_no; $sr_no++;?>. You have opted to keep the Renewable Attributes and to register the solar PV plant under the Clause no. 9.1.3 Type 2C. The project shall be installed to meet your Renewable Purchase Obligation (RPO).</p>
									<?php
								}
								?>
								
								
							   
							</div>
						</td>
					</tr>
					<tr>
						<td class="td_simple"> 
							<div style="margin-left:15px"> 
								<?php
								$strType    = '';
								if($applyOnlinesOthersData->renewable_attr == 1) { $strType = '(Type 1)'; }  if($applyOnlinesOthersData->renewable_attr == 0) { $strType = '(Type 2A)'; }
								?>
								<p><?php echo $sr_no; $sr_no++;?>. The energy settlement option shall be predefined by you as per the provisions of the Policy. <?php echo $strType;?></p>
								<p><?php echo $sr_no; $sr_no++;?>. You shall ensure that the location proposed for installation of the system is shadow free during the entire day and also keep the system clean and dust free for optimum output. </p>
								<p><?php echo $sr_no; $sr_no++;?>. You shall sign the connectivity agreement with the DISCOM upon its ascertaining the technical feasibility of the system installation.</p>
								<p><?php echo $sr_no; $sr_no++;?>.You shall pay the requisite GEDA Registration Fee/Charges to GEDA payable at Gandhinagar.</p>
								<p><?php echo $sr_no; $sr_no++;?>. You shall allow access to your premises by representatives of GEDA/DISCOM/CEI for installation of bidirectional meter/inspection/verification of the system. </p>
								<p><?php echo $sr_no; $sr_no++;?>. The system and the bi-directional meter shall be installed in accordance to the technical specifications specified in the Tender, and those specified by MNRE /DISCOM.</p>
								<p><?php echo $sr_no; $sr_no++;?>. You shall certify and sign the completion report in prescribed format upon the installation of the bi-directional meter and commissioning of the system. </p> 
								<p><?php echo $sr_no; $sr_no++;?>. The Application for RTPV scheme shall be cancelled in case the i) the Applicant does not respond to the queries raised by Discom/GEDA in 15 days; ii) the Applicant does not make the payment of estimate amount within 30 days.</p>
								<p><?php echo $sr_no; $sr_no++;?>. The DISCOM shall ascertain the Consumer’s ownership/legal possession of the premises where the  solar PV system is to be installed.</p>
								<?php if(isset($NewText) && !empty($NewText)){?>
                                    <p><?php echo $sr_no; $sr_no++;?>. <?php echo $NewText ?></p>
                                <?php } ?>
								
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
									   For necessary action, and installation of the solar system as per the technical specifications and in accordance with the terms and conditions of the tender, MNRE etc.
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
										<span class="td_bold">DISCLAIMER: </span><span class="td_simple">The PV Installer/ EPC is selected by the Applicant as per his choice, with GEDA owing no responsibility of the EPCs for his technical, financial strength, quality, integrity etc. and the financial transactions made with PV Installer. </span>
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