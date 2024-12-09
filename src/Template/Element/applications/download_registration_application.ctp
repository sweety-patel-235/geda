<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>RE Application</title>
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
			.page_break { page-break-before: always; }

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
						<td align="right" style="margin-right:10px;">
							<?php
							$image_path = ROOT . DS ."webroot/pdf/images/geda.jpg";
							$type = pathinfo($image_path, PATHINFO_EXTENSION);
							$data = file_get_contents($image_path);
							$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
							?>
						<img src="<?php echo $base64;?>"  width="225px" height="80px">
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
									   <p><span><?php echo ($applicationDetails->application_type == 5) ? 'Registration No.' : 'PR No.';?>: <?php echo $applicationDetails->registration_no; ?></span></p> 
									</td>
									<td align="right" class="td_simple"  >
										<p><span style="margin-right:15px"> Date: <?php 
										if(!empty($applyOnlineGedaDate) && !empty($applyOnlineGedaDate->created))
										{
											echo date('d-M-Y',strtotime($applyOnlineGedaDate->created));
										}
										else
										{
											echo date('d-M-Y',strtotime($applicationDetails->created));
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
							<?php 
							//print_R($applicationDetails);
							echo $applicationDetails->name_of_applicant;?> <?php //echo $ApplyOnlines->name_of_consumer_applicant;?><br>
							<?php echo $applicationDetails->address1.', '.$applicationDetails->taluka.', '.$applicationDetails->district_master['name'].', '.$applicationDetails->state.' - '.$applicationDetails->pincode;?> 
							<br>
							<?php //echo $ApplyOnlines->address2;?>
							<br>
							<?php //echo $ApplyOnlines->city;?>
						</td>         
					</tr>
					<tr>
						<td class="td_simple"> 
							<p class="td_bold">Subject: <?php  echo ($applicationDetails->application_type == 5) ? '' : 'Provisional';?> Registration of <?php  echo ($applicationDetails->application_type == 5) ? 'Solar Power' : 'Renewable Energy';?>  Project under <?php  echo ($applicationDetails->kusum_type == 1) ? 'PM-KUSUM-A' : (($applicationDetails->kusum_type == 2) ? 'PM-KUSUM-C' : 'Gujarat Renewable Energy Policy- 2023') ;?></p>
						</td>
					</tr>
					 <tr>
						<td class="td_simple">
							<div style="margin-top:5px">
								 Dear Sir,
							</div>
							<div style="margin-top:5px;text-align: justify;">
								<p>1.	We hereby confirm the <?php  echo ($applicationDetails->application_type == 5) ? 'Registration' : 'Provisional Registration';?> of <?php  echo ($applicationDetails->application_type == 5) ? 'Solar Power' : 'Renewable Energy';?> Project with the Gujarat Energy Development Agency (GEDA) based on the <?php  echo ($applicationDetails->application_type == 5) ? "SPG's" : 'project Developer’s';?> proposed details are as follows
									<ul>
										<li>Name <?php  echo ($applicationDetails->application_type == 5) ? "of Solar Power Generator" : "RE Project Developer";?>: <?php echo $applicationDetails->name_of_applicant;?></li>
										<li>Renewable Energy Sector: <?php echo ($applicationDetails->kusum_type == 2) ? 'Solar under PM KUSUM-C' : (($applicationDetails->kusum_type == 1) ? 'Solar under PM KUSUM-C' : ($applicationDetails->application_category['category_name']));?></li>
										<li><?php  echo ($applicationDetails->application_type == 5) ? "Registered" : "Proposed";?> Capacity: 
											<?php if($applicationDetails->application_type == 4){?>
												<?php echo $applicationDetails->total_wind_hybrid_capacity ?>(  WTG <?php echo $applicationDetails->total_capacity?> MW +  Inverter <?php echo  $totalInverternos['mod_inv_total_capacity']?> MW)</li>
											<?php  } else if($applicationDetails->application_type == 5){ ?>
												(Module <?php echo $applicationDetails->module_hybrid_capacity ?> MW +  Inverter <?php echo  $applicationDetails->inverter_hybrid_capacity;?> MW)</li>
											<?php  } else if($applicationDetails->application_type == 3) {?>
													<?php echo $applicationDetails->total_capacity ?> MW</li>
											<?php }else {?>
													<?php echo $applicationDetails->pv_capacity_ac ?> MW</li>
											<?php }?>
										<li><?php  echo ($applicationDetails->application_type == 5) ? "Site Address" : "Proposed Location";?>:    Village-<?php echo $applicationDetails->project_village;?>, Ta-<?php echo $applicationDetails->project_taluka;?>, Dist. <?php echo $applicationDetails->dm_project['name'];?></li>
										<li>Grid Connectivity: <?php echo isset($gridLevel[$applicationDetails->grid_connectivity]) ? $gridLevel[$applicationDetails->grid_connectivity] : $EmptyDataCharector;?></li>
										<li>Power Injection Level: <?php echo isset($injectionLevel[$applicationDetails->injection_level]) ? $injectionLevel[$applicationDetails->injection_level] : $EmptyDataCharector;?>.</li>
										<li><?php  echo ($applicationDetails->application_type == 5) ? "" : "Proposed";?> <?php echo ($applicationDetails->grid_connectivity == 1)  ? 'GETCO' : 'PGCIL';?> Substation: <?php echo (isset($ss_name) && !empty($ss_name)) ? $ss_name : $applicationDetails->getco_substation;?></li>
										<li>End use of electricity: <?php 
											if($applicationDetails->grid_connectivity == 1) {
												echo isset($EndSTU[$EndUseDetails->application_end_use_electricity]) ? $EndSTU[$EndUseDetails->application_end_use_electricity] : $EmptyDataCharector;
											} elseif($applicationDetails->grid_connectivity == 2) {
												echo isset($EndCTU[$EndUseDetails->application_end_use_electricity]) ? $EndCTU[$EndUseDetails->application_end_use_electricity] : $EmptyDataCharector;
											}
											?>
										</li>
										<?php  if($applicationDetails->application_type == 5) { ?>
											<li>Name of DISCOM: <?php echo isset($discom_short_name->title) ? $discom_short_name->title : 'Discom';?></li>
										<?php } else { ?>
											<li>Name of concern DISCOM in whose jurisdiction project is proposed to be installed : <?php echo isset($discom_short_name->title) ? $discom_short_name->title : 'Discom';?></li>
										<?php } ?>
										
										<li>LoA number: <?php echo $applicationDetails->application_no; ?></li>
									</ul>
								</p>
								<p>2.	The <?php echo ($applicationDetails->application_type != 5) ? 'RE Project' : 'Solar Project';?> Developer / Generator shall assume all risks associated with the project, including technical, financial, regulatory, and environmental risks.</p>
								<p>3.	We acknowledge this <?php  echo ($applicationDetails->application_type == 5) ? '' : 'Provisional';?> Registration as per your application. You may apply for the grid connectivity from the appropriate authorities to establish grid connectivity for evacuation of power generated from proposed <?php echo ($applicationDetails->application_type != 5) ? 'RE Project' : 'Solar Project';?>.</p>
								<?php  if($applicationDetails->application_type != 5) { ?>
									<p>4.	You may apply for the Final Registration / Development Permission upon submission of Stage-II connectivity / Final Connectivity Approval / confirmation of Technical Feasibility Report (TFR) along with the necessary details & documents of the proposed RE Project.</p>
								<?php } else { ?>
									<p>4.	This Solar Project shall be governed in accordance with power purchase agreement, GERC regulations issued from time to time and Electricity Act- 2003.</p>
								<?php } ?>
							</div>         
						</td>
					</tr>
					
				</table>
				<div class="page_break"></div>
				
				<table width="100%">
					<tr>
						<td class="td_simple">
							<?php  if($applicationDetails->application_type != 5) { ?>
								<div style="text-align: justify;">
									<p>5.	The RE Project development permission, construction, and commissioning timelines shall be specified in the Final Registration / Development Permission/ Transfer Permission.</p>
									<p>6.	This RE Project shall be governed by the Gujarat Renewable Energy Policy-2023 and the GERC regulations issued from time to time. The Renewable Energy Generator / Developer shall be bound by all the provisions as decided by the GERC from time to time.</p>
									<p>7.	The validity of the Provisional Registration is for the period of 6 months from the date of issuance of this letter. The RE Project Developer shall obtain the Development Permission (for Wind and Hybrid) / Final Registration (for Solar) of proposed RE Project during the period mentioned above, failing which the Provisional Registration will stand cancelled and Provisional Registration Fees be forfeited as the case may be.</p>
									<?php if(isset($NewText) && !empty($NewText)){?>
	                                    <p><?php echo $NewText ?></p>
	                                <?php } ?>
								</div>
							<?php  } else { ?>
								<div style="text-align: justify;">
									<p>5.	The validity of this Registration shall be as per terms & conditions specified in the LoA /PPA.</p>
									<p>6.	The provisions of “Approved List of Models and Manufacturers” (ALMM) & its amendment issued by MNRE from time to time shall be applicable.</p>
									<p>7.	GEDA does not owe any responsibility regarding the performance of this Solar Plant.</p>
									<p>8.	It shall be responsibility of Solar Power Generator (Owner of the Project) to ensure taking all other statutory permission as required from various Government Departments and GEDA will have no responsibility.</p>
								</div>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="td_italic" >
							<table style="border:1px solid #000000;" cellpadding="5">
								<tr>
									<td rowspan="6" style="width:12%; vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										CC To
									</td>
									 <td rowspan="6" style="width:3%; vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										:
									</td>
									<td style="vertical-align: top;width:33%;border-right: 1px solid #000000;" class="td_italic">
										1. The Chief Engineer<br>
										<?php echo isset($discom_short_name->title) ? $discom_short_name->title : 'Discom';?>
									</td>
									<td style="vertical-align: top;width:52%;line-height: 15px;" class="td_italic"> 
										For necessary action please.
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										2. The Chief Engineer, GETCO
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										For necessary action please. 
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										3. <?php echo ($applicationDetails->grid_connectivity == 1)  ? 'The Chief Electrical Inspector' : ' The Central Electricity Authority (CEA)';?> 
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										For necessary action please.
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										4. The General Manager -RE, GUVNL
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										For your kind information.
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
										5. <?php echo $applicationDetails->developers['installer_name'];?>
									</td>
									<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
									  	For necessary action please.
									</td>
								</tr>
								<?php  if($applicationDetails->application_type == 5) { ?>
									<tr>
										<td style="vertical-align: top;border-right: 1px solid #000000;" class="td_italic">
											6. Name of DISCOM (which has issued LOA)
										</td>
										<td style="vertical-align: top;line-height: 15px;" class="td_italic"> 
										  	<?php echo isset($discom_short_name->title) ? $discom_short_name->title : 'Discom';?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
					<tr>
						<td align="left" class="td_simple">&nbsp;</td>
					</tr>
					<tr>
						<td align="center" class="td_bold">
							This is a computer generated letter and doesn't require signature.
						</td>
					</tr>
					<?php /*
					<tr>
						<td align="left" class="td_simple">
							Thanking you,
							       
						</td>
					</tr>
					<tr>
						<td align="right" class="td_bold">
							Gujarat Energy Development Agency
							<div style="margin-top:5px">
								Digital Signature
							</div>            
						</td>
					</tr>   */?>                     
				</table>
			</div>
		</div>
	</body>
</html>