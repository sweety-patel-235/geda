<!DOCTYPE html><html lang=""><head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>GEDA Application</title>
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
			.td_bold{ font-family: 'arial_bold';}
			.td_italic{font-family: 'arial_italic';}
			.td_simple{font-family: 'arial_simple';}
			@page {
				margin: 10px;
			}
			body {
				margin: 20px;
				font-size: 13px;
			}
			.text_justify li{
				text-align:justify;
			} 
			.checkbox{
				height: 15px;width:15px;border:1px solid black; cursor: pointer;
			}
			.checkboxtick{
				height: 15px;width:15px;border:1px solid black; background-color: black;
			}
			#chkBike{
			  cursor: pointer;
			}
			.page-break{
				page-break-after: always;
			}
			</style>
	</head><body id="pdf-header">
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
				<table width="100%">
					<tr>
						<td align="left" valign="top" width="70%" >
										
											<span style="color:black;margin-bottom:0;font-size: 20px !important;" class="td_bold">GUJARAT ENERGY DEVELOPMENT AGENCY</span>
											<p class="td_simple">4th Floor, Block No. 11-12, Udyog Bhawan, Gandhinagar,<br>
											Ph: 079-23257251-54, GST. No. 24AAATG1858Q1ZA
											</p>
						</td>   
						<td align="right" valign="top" style="float: right;" width="30%" >
										<?php
										$image_path = ROOT . DS ."webroot/pdf/images/geda.jpg";
			                            $type = pathinfo($image_path, PATHINFO_EXTENSION);
			                            $data = file_get_contents($image_path);
			                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
										?>
						 <img src="<?php echo $base64;?>"   width="225px" height="80px">
						</td>
					</tr>
					<tr>
                        <td class="td_simple" colspan="2" >
                            <div style="text-align: center;">
                               <h2 style="color:black;">
                               		<?php echo ($ApplicationData->application_type == 2) ? 'Solar Power Project <h3 style="color:black;">Application form for provisional registration of Solar Power Project </h3>' : (($ApplicationData->application_type == 3) ? 'Wind Power Project <h3 style="color:black;">Application form for provisional registration of Wind Power Project  </h3>' : '');?></h2>
                                 	
                            </div>
                            
                            
                        </td>
                    </tr>
				</table>
				<table  cellpadding="5" width="100%">
					<tbody>
						<tr>
							<td align="left" class="td_italic">
							<p><span>Application No.: <?php echo $ApplicationData->application_no; ?></span></p> 
						</td>
						<td align="right" class="td_simple">
							<p>
								<span style="margin-right:15px"> Date: <?php  echo date('d-M-Y',strtotime($ApplicationData->created));
									
									?>
								</span>
							</p>
						</td>
						</tr>
					</tbody>
				</table>
				<table style="border:1px solid #000000; border-collapse: collapse;" cellpadding="5" width="100%">
					<tbody>
						<tr>
							<td class="td_simple" align="center" colspan="8"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">A. General Profile of Applicant:</td>
						</tr>
						<tr>
							<td class="td_simple" align="center" rowspan="6" width="5%" style="border-bottom: 1px solid;border-right: 1px solid;">1.</td>
							<td class="td_simple" align="center" colspan="2" width="30%" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Name of the Applicant Company</td>
							<td class="td_simple" align="center" colspan="5" width="65%"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"> <?php echo isset($ApplicationData->name_of_applicant) ?  $ApplicationData->name_of_applicant : $EmptyDataCharector;?></td>
						</tr><?php /*
						<tr>
							<td class="td_simple" align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Address of Registered Office</td>
							<td class="td_simple" align="center"  colspan="5"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->address) ?  $ApplicationData->address : $EmptyDataCharector;?></td>
						</tr> */?>
						<tr>
							<td class="td_simple" align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Street/ House No.</td>
							<td class="td_simple" align="center"  colspan="5" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->address1) ?  $ApplicationData->address1 : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td class="td_simple" align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Village/ Taluka </td>
							<td class="td_simple" align="center"  colspan="5"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->taluka) ?  $ApplicationData->taluka : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td class="td_simple" align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">City </td>
							<td class="td_simple" align="center" width="15%"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->city) ?  $ApplicationData->city : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center" width="10%"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">State </td>
							<td class="td_simple" align="center" width="10%" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->state) ?  $ApplicationData->state : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center" width="15%" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">PIN </td>
							<td class="td_simple" align="center" width="15%" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->pincode) ?  $ApplicationData->pincode : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Phone</td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->mobile) ?  $ApplicationData->mobile : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"    style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">
								<?php 
								$image_path = ROOT . DS ."webroot/img/whatsapp.png";
								$type 		= pathinfo($image_path, PATHINFO_EXTENSION);
								$data 		= file_get_contents($image_path);
								$whatsapp 	= 'data:image/' . $type . ';base64,' . base64_encode($data);
								?>
								WhatsApp no. <img src="<?php echo $whatsapp;?>" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></td>
							<td class="td_simple" align="center" colspan="2"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->contact) ?  $ApplicationData->contact : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Mobile </td>
							<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->mobile) ?  $ApplicationData->mobile : $EmptyDataCharector;?></td>

						</tr>
						<tr>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">E-mail</td>
							<td class="td_simple" align="center" colspan="6" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->email) ?  $ApplicationData->email : $EmptyDataCharector;?></td>
							<?php /*<td class="td_simple" align="center" width="30%"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Website</td>
							<td class="td_simple" align="center"  colspan="2" width="30%" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"> </td>*/?>
						</tr>
						<tr>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; ">2.</td>
							<td class="td_simple" align="center"colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">PAN Number </td>
							<td class="td_simple" align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->pan) ?  $ApplicationData->pan : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">GST No. </td>
							<td class="td_simple" align="center"  colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->GST) ?  $ApplicationData->GST : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid;">3.</td>
							<td class="td_simple" align="center"  colspan="2"    style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Type of Applicant </td>
							<td class="td_simple" align="center" colspan="5"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->type_of_applicant) ?  ((strtolower($ApplicationData->type_of_applicant) == 'other') ? $ApplicationData->applicant_others : $ApplicationData->type_of_applicant) : $EmptyDataCharector;?> </td>
						</tr>

						<tr>
							<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid;">4.</td>
							<td class="td_simple" align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">MSME</td>
							<td class="td_simple" align="center" colspan="5" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo (isset($ApplicationData->msme) && $ApplicationData->msme == 1) ?  'Yes' : 'No';?> </td>
						</tr>
						<tr>
							<td class="td_simple" align="center" rowspan="2" style="border-bottom: 1px solid;border-right: 1px solid; ">5.</td>
							<td class="td_simple" align="center"  colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Name of the Managing Director/ Chief Executive of the Company </td>

							<td class="td_simple" align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"> <?php echo isset($ApplicationData->name_director) ?  $ApplicationData->name_director : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Designation </td>
							<td class="td_simple" align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->type_director) ?  ((strtolower($ApplicationData->type_director) == 'others') ? $ApplicationData->type_director_others : $ApplicationData->type_director) : $EmptyDataCharector ;?></td>

						</tr>
						<tr>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">WhatsApp no. <img src="<?php echo $whatsapp;?>" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /> </td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->director_whatsapp) ?  $ApplicationData->director_whatsapp : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Mobile </td>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->director_mobile) ?  $ApplicationData->director_mobile : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Email </td>
							<td class="td_simple" align="center"  colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->director_email) ?  $ApplicationData->director_email : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td class="td_simple" align="center" rowspan="3"  style="border-bottom: 1px solid;border-right: 1px solid;">6.</td>
							<td class="td_simple" align="center"  colspan="2"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Name of the Authorized Signatory. </td>

							<td class="td_simple" align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->name_authority) ?  $ApplicationData->name_authority : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Designation </td>
							<td class="td_simple" align="center"  colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->type_authority) ?  ((strtolower($ApplicationData->type_authority) == 'others') ? $ApplicationData->type_authority_others : $ApplicationData->type_authority) : $EmptyDataCharector ;?></td>

						</tr>
						<tr>
							<td class="td_simple" align="center" colspan="7"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Kindly enclose certified copy the Board Resolution authorizing the Signatory. </td>

						</tr>
						<tr>
							<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">WhatsApp no. <img src="<?php echo $whatsapp;?>" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /> </td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->authority_whatsapp) ?  $ApplicationData->authority_whatsapp : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Mobile </td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->authority_mobile) ?  $ApplicationData->authority_mobile : $EmptyDataCharector;?></td>
							<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Email </td>
							<td class="td_simple" align="center"  colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->authority_email) ?  $ApplicationData->authority_email : $EmptyDataCharector;?></td>
						</tr>

						
					</tbody>
				</table>
				
				<div class="page-break" ></div>
				<table style="border:1px solid #000000; border-collapse: collapse;" cellpadding="5" width="100%">
					<tbody>
						
						<tr>
							<td class="td_simple" align="center" colspan="4" class="td_bold" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo (isset($ApplicationData->application_category['route_name']) && $ApplicationData->application_category['route_name'] == 'GroundMounted') ? 'B. Technical Details of the Proposed Project for Provisional Registration: ' : 'B. Technical and Financial details of the Proposed Project :';?></td>
						</tr>
						<?php 
						if(isset($ApplicationData->application_category['route_name']) && $ApplicationData->application_category['route_name'] == 'GroundMounted') { ?>
							<tr>
								<td width="5%" class="td_simple"  align="center" style="border-bottom: 1px solid;border-right: 1px solid;width:5% !important; ">1.</td>
								<td width="30%"  class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;">Project Capacity</td>
								<td width="65%" class="td_simple"  align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;"><?php echo isset($ApplicationData->pv_capacity_ac) ?  $ApplicationData->pv_capacity_ac : $EmptyDataCharector;?> MW(AC) / <?php echo isset($ApplicationData->pv_capacity_dc) ?  $ApplicationData->pv_capacity_dc : $EmptyDataCharector;?>MW(DC)</td> 
							</tr>
						<?php } 
						else if(isset($ApplicationData->application_category['route_name']) && ($ApplicationData->application_category['route_name'] == 'Wind' || $ApplicationData->application_category['route_name'] == 'Hybrid')) { 

							?>
							<tr>
								<td width="5%" class="td_simple" align="center" rowspan="<?php echo ($ApplicationData->application_category['route_name'] == 'Hybrid') ? '8' : '2';?>"  style="border-bottom: 1px solid;border-right: 1px solid;width:5% !important;" >1.</td>
								<td width="30%" class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;" >Particulars of Project</td>
								<td width="65%"  class="td_simple" align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;">Details of Wind Power Project</td>
							</tr>
							<tr>
								<td colspan="3">
									<table style="border:1px solid #000000; border-collapse: collapse;" cellpadding="5" width="100%">
										<?php 
										if(!empty($allWindData)) {  ?>
											<tr>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Sr. No.</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Make</td>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Nos. of WTG</td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Capacity of each WTG (in MW)</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Total Capacity (in MW)</td>
												
											</tr>
											<?php 
											$totalNo 			= 0;
											$totalCapacity 		= 0;
											$totalCumCapacity 	= 0;
											foreach($allWindData as $keyw=>$windData) {
												$totalNo 			= $totalNo + $windData->nos_mod_inv;
												$totalCapacity 		= $totalCapacity + $windData->mod_inv_capacity;
												$totalCumCapacity 	= $totalCumCapacity + $windData->mod_inv_total_capacity;
												?>
												<tr>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $keyw+1;?></td>
													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($windData->manufacturer_master['name']) ?  $windData->manufacturer_master['name'] : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($windData->nos_mod_inv) ?  $windData->nos_mod_inv : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($windData->mod_inv_capacity) ?  $windData->mod_inv_capacity : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($windData->mod_inv_total_capacity) ?  $windData->mod_inv_total_capacity : $EmptyDataCharector;?></td>
												</tr>
											<?php 
											} ?>
											<tr>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;" colspan="2">Total</td>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalNo;?></td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalCapacity;?></td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalCumCapacity;?></td>
											</tr>
											<?php 
										}  ?>
									</table>
								</td>
							</tr>
						<?php } ?>
						<?php if(in_array($ApplicationData->application_type,array(5,6))) { ?>
							<tr>
								<td width="5%" class="td_simple" align="center" rowspan="6"  style="border-bottom: 1px solid;border-right: 1px solid;width:5% !important;" >1.</td>
								<td width="30%" class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;" >Particulars of Project</td>
								<td width="65%"  class="td_simple" align="center" colspan="2"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;">Details of SPV Modules</td>
							</tr>
						<?php } ?>
						<?php if( $ApplicationData->application_category['route_name'] == 'Hybrid' || in_array($ApplicationData->application_type,array(5,6))) { 
							if( $ApplicationData->application_category['route_name'] == 'Hybrid') { 
							?>
							<tr>
								<td width="30%" class="td_simple" align="center"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;" >Particulars of Project</td>
								<td width="65%"  class="td_simple" align="center" colspan="2"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;">Details of SPV Modules</td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan="3">
									<table style="border:1px solid #000000; border-collapse: collapse;" cellpadding="5" width="100%">
										<?php 
										if(!empty($allModuleData)) {  ?>
											<tr>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Sr. No.</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Make</td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">SPV Technologies</td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Solar Panel</td>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Nos. of SPV Modules</td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Capacity of each SPV Module (in Wp)</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Total Capacity of SPV Modules (in MW)</td>
												
											</tr>
											<?php 
											$totalNo 			= 0;
											$totalCapacity 		= 0;
											$totalCumCapacity 	= 0;
											$moduleCumulative 	= 0;
											foreach($allModuleData as $keyw=>$moduleData) {

												
												$totalNo 			= $totalNo + $moduleData->nos_mod_inv;
												$totalCapacity 		= $totalCapacity + $moduleData->mod_inv_capacity;
												$totalCumCapacity 	= $totalCumCapacity + $moduleData->mod_inv_total_capacity;
												if(isset($moduleData->mod_type_of_spv) && !empty($moduleData->mod_type_of_spv)){
													// Key to check
													$mod_type_of_spvCheck = $moduleData->mod_type_of_spv;
													if (array_key_exists($mod_type_of_spvCheck, $type_of_spv)) {
													    // Display the value corresponding to the key
													     $mod_type_of_spv = $type_of_spv[$mod_type_of_spvCheck]; 
													}
												}
												if(isset($moduleData->mod_type_of_solar_panel) && !empty($moduleData->mod_type_of_solar_panel)){
													// Key to check
													$mod_type_of_solar_panelCheck = $moduleData->mod_type_of_solar_panel;
													if (array_key_exists($mod_type_of_solar_panelCheck, $type_of_solar_panel)) {
													    // Display the value corresponding to the key
													     $mod_type_of_solar_panel = $type_of_solar_panel[$mod_type_of_solar_panelCheck]; 
													}
												}
												?>
												<tr>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $keyw+1;?></td>
													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($moduleData->manufacturer_master['name']) ?  $moduleData->manufacturer_master['name'] : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($mod_type_of_spv) ?  $mod_type_of_spv : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($mod_type_of_solar_panel) ?  $mod_type_of_solar_panel : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($moduleData->nos_mod_inv) ?  $moduleData->nos_mod_inv : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($moduleData->mod_inv_capacity) ?  $moduleData->mod_inv_capacity : $EmptyDataCharector;?></td>

													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($moduleData->mod_inv_total_capacity) ?  $moduleData->mod_inv_total_capacity : $EmptyDataCharector;?></td>
												</tr>
											<?php 
											} ?>
											<tr>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;" colspan="4">Total</td>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalNo;?></td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalCapacity;?></td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php $moduleCumulative = $totalCumCapacity; echo $totalCumCapacity;?></td>
											</tr>
											<?php 
										}  ?>
									</table>
								</td>
							</tr>
							<tr>
								<td width="30%" class="td_simple" align="center"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;" >Particulars of Project</td>
								<td width="65%"  class="td_simple" align="center" colspan="2"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;">Details of Inverter</td>
							</tr>
							<tr>
								<td colspan="3">
									<table style="border:1px solid #000000; border-collapse: collapse;" cellpadding="5" width="100%">
										<?php 
										if(!empty($allInverterData)) {  ?>
											<tr>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Sr. No.</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Make</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Type of Inverter Used</td>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Nos. of Inverter</td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Capacity of each Inverter (in kW)</td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Total Capacity of Inverter (in MW)</td>
												
											</tr>
											<?php 
											$totalNo 			= 0;
											$totalCapacity 		= 0;
											$totalCumCapacity 	= 0;
											foreach($allInverterData as $keyw=>$inverterData) {
												$totalNo 			= $totalNo + $inverterData->nos_mod_inv;
												$totalCapacity 		= $totalCapacity + $inverterData->mod_inv_capacity;
												$totalCumCapacity 	= $totalCumCapacity + $inverterData->mod_inv_total_capacity;
												if(isset( $inverterData->inv_used) && !empty($inverterData->inv_used)){
													// Key to check
													$inv_usedCheck = $inverterData->inv_used;
													if (array_key_exists($inv_usedCheck, $type_of_inverter_used)) {
													    // Display the value corresponding to the key
													     $inv_used = $type_of_inverter_used[$inv_usedCheck]; 
													}
												}
												?>
												<tr>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $keyw+1;?></td>
													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($inverterData->manufacturer_master['name']) ? $inverterData->manufacturer_master['name'] : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($inv_used) ? $inv_used : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($inverterData->nos_mod_inv) ?  $inverterData->nos_mod_inv : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($inverterData->mod_inv_capacity) ?  $inverterData->mod_inv_capacity : $EmptyDataCharector;?></td>
													<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($inverterData->mod_inv_total_capacity) ?  $inverterData->mod_inv_total_capacity : $EmptyDataCharector;?></td>
												</tr>
											<?php 
											} ?>
											<tr>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;" colspan="3">Total</td>
												<td class="td_simple" align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalNo;?></td>
												<td class="td_simple" align="center"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalCapacity;?></td>
												<td class="td_simple" align="center"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo $totalCumCapacity;?></td>
											</tr>
											<?php 
										}  ?>
									</table>
								</td>
							</tr>
							<tr>
								<td class="td_simple" align="center"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;" >Total Cumulative capacity AC (MW)</td>
								<td class="td_simple" align="center" colspan="2"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;"><?php echo isset($ApplicationData->total_wind_hybrid_capacity) ? $ApplicationData->total_wind_hybrid_capacity : ''; ?></td>
							</tr>
							<tr>
								<td class="td_simple" align="center"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:30% !important;" >Total Cumulative capacity DC (MW)</td>
								<td  class="td_simple" align="center" colspan="2"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;width:65% !important;"> <?php echo isset($moduleCumulative) ? $moduleCumulative: ''; ?></td>
							</tr>
						<?php } ?>
						
						
						<tr>
							<td align="center" style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; ">2.</td>
							<td align="center"  style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Grid Connectivity</td>
							<td align="center" colspan="2"   style="border-top: 1px solid;border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($gridLevel[$ApplicationData->grid_connectivity]) ? $gridLevel[$ApplicationData->grid_connectivity] : $EmptyDataCharector;?> </td> 
						</tr>
						<tr>
							<td align="center"  style="border-bottom: 1px solid;border-right: 1px solid;">3.</td>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Name of Proposed <?php echo ($ApplicationData->grid_connectivity == 1) ? 'GETCO' : 'PGCIL';?>  Substation</td>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->getco_substation) ?  $ApplicationData->getco_substation : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid;">4.</td>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Power Injection Level</td>
							<td align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($injectionLevel[$ApplicationData->injection_level]) ? $injectionLevel[$ApplicationData->injection_level] : $EmptyDataCharector;?> </td>
						</tr>
						<tr>
							<td align="center" rowspan="3"   style="border-bottom: 1px solid;border-right: 1px solid;">5.</td>
							<td align="center"  rowspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">End use of electricity</td>
							<td align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($gridLevel[$ApplicationData->grid_connectivity]) ? $gridLevel[$ApplicationData->grid_connectivity] : $EmptyDataCharector;?> </td>
						</tr>
						<tr>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php 
							if($ApplicationData->grid_connectivity == 1) {
								echo isset($EndSTU[$EndUseDetails->application_end_use_electricity]) ? $EndSTU[$EndUseDetails->application_end_use_electricity] : $EmptyDataCharector;
							} elseif($ApplicationData->grid_connectivity == 2) {
								echo isset($EndCTU[$EndUseDetails->application_end_use_electricity]) ? $EndCTU[$EndUseDetails->application_end_use_electricity] : $EmptyDataCharector;
							}
							?></td>
						</tr>
						<tr>
							<td align="center" colspan="3"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: center;">Note:	If end use of power is for sale to DISCOM, Kindly attached the consent letter/ LOI/ PPA of 	GUVNL/ DISCOM to purchase power from proposed project.</td>
						</tr>
						<tr>
							<td align="center" rowspan="4" style="border-bottom: 1px solid;border-right: 1px solid;">6.</td>
							<td align="center" rowspan="4" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Details of Power Project site</td>
							<td align="center" colspan="2" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">State: <?php echo isset($ApplicationData->project_state) ?  $ApplicationData->project_state : $EmptyDataCharector;?>
						</tr>
						<tr>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Village: <?php echo isset($ApplicationData->project_village) ?  $ApplicationData->project_village : $EmptyDataCharector;?>
						</tr>
						<tr>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Taluka: <?php echo isset($ApplicationData->project_taluka) ?  $ApplicationData->project_taluka : $EmptyDataCharector;?>
						</tr>
						<tr>
							
							<td align="center" colspan="2"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">District: <?php echo isset($ApplicationData->dm_project['name']) ?  $ApplicationData->dm_project['name'] : $EmptyDataCharector;?>
						</tr>
					    <tr>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid;">7.</td>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Expected Annual output of Energy from the Proposed Project <?php echo ($ApplicationData->application_type == 2) ? '(in kWh)' : '(in MWH)'; ?></td>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->project_energy) ?  $ApplicationData->project_energy : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid;">8.</td>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Tentative Date of Commissioning</td>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->comm_date) ?  date('d-m-Y',strtotime($ApplicationData->comm_date)) : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid;">9.</td>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Approximate Project Cost (Rs. in lacs)</td>
							<td align="center" colspan="2"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->project_estimated_cost) ?  _FormatNumberV2($ApplicationData->project_estimated_cost) : $EmptyDataCharector;?></td>
						</tr>
						<tr>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid;">10.</td>
							<td align="center" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Approximate Employment Generation from the Proposed Project (in Nos.)</td>
							<td align="center" colspan="2"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;"><?php echo isset($ApplicationData->approx_generation) ?  $ApplicationData->approx_generation : $EmptyDataCharector;?></td>
						</tr>
					</tbody>
				</table>
				
				<?php //if(!in_array($ApplicationData->application_type,array(5,6))) { ?>
					<table style="border:1px solid #000000; border-collapse: collapse;margin-top: 20px;" cellpadding="5" width="100%">
						<tbody>
							
						    <tr>
						    	<td align="center" colspan="8"   style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">C. Fee structure for Provisional Registration at GEDA: </td>
						    </tr>
							<tr>
								<td align="center"  width="5%" style="border-bottom: 1px solid;border-right: 1px solid;">1.</td>
								<td align="center" colspan="2" width="50%" style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Provisional Registration Fee <?php echo isset($ApplicationData->application_total_fee) ?  _FormatNumberV2($ApplicationData->application_total_fee) : $EmptyDataCharector;?> Including GST</td>
								<td align="center" colspan="5" width="50%"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: center;">Online payment</td>
							</tr>
							<tr>
								<td align="center" colspan="8" width="30%"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">(At present GST of 18% is applicable, GEDA GST No. 24AAATG1858Q1ZA)</td>
							</tr>
							<tr>
								<td align="center" colspan="8" width="30%"  style="border-bottom: 1px solid;border-right: 1px solid; text-align: left;">Note: The Provisional Registration fee submitted will be adjusted against Development Application processing fee. If applicant fails to get the Development Permission with in the stipulated time line, the Provisional Registration fee submitted by the applicant would be non-refundable.</td>
							</tr>
						   
						</tbody>
					</table>
				<?php //} ?>
			</div>
		</div>
	</body></html>