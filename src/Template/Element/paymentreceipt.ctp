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
						<td align="right" style="margin-right:10px;">
							<table width="100%">
								<tr>
									<td width="50">
										<?php 
											$logo_image     = '';
											$path           = 'img/2_4.png';
											if (!empty($path) && file_exists($path)) {
												$type = pathinfo($path, PATHINFO_EXTENSION);
												$data = file_get_contents($path);
												$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
												$logo_image = "<img src=\"".$base64."\" height=\"100px\" />";
											} 
										?>
										<?php echo $logo_image;?>
									</td>
									<td>
										<div style="margin-right:100px;text-align: center;">
											<p style="color:black;margin-bottom:0;font-size: 22px;" class="td_bold">GUJARAT ENERGY DEVELOPMENT AGENCY</p>
											<p class="td_simple">4th Floor, Block No. 11-12, Udyog Bhavan, Gandhinagar-382 017.<br>
											Ph: 079-23257251-54, GST. No. 24AAATG1858Q1ZA
											</p>
											<p class="td_simple">Receipt - Cum - Tax Invoice
											</p>
										</div>
									</td>
								</tr>
							</table>
						</td>   
					</tr>  
					<tr>
						<td class="td_simple" >
							<table style="border:1px solid #000000;border-collapse: collapse;" cellpadding="2" width="100%">
								<tr>
									<td rowspan="3" style="width:52%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;" >
										<span >To <br/>Name and Address </span><br/>
										<?php echo $ApplyOnlines->customer_name_prefixed;?> <?php echo $ApplyOnlines->name_of_consumer_applicant;?><br>
										<?php echo $ApplyOnlines->address1;?>
										<br>
										<?php echo $ApplyOnlines->address2;?> - <?php echo $ApplyOnlines->pincode;?>
									</td>
									<td style="vertical-align: top;width:33%;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;padding-right: 3px;" >
										Date
									</td>
									<td style="vertical-align: top;width:33%;line-height: 15px;border-bottom: 1px solid #000000;padding-left: 3px;" > 
									   <?php echo date_format($payment_data->payment_dt,"d-m-Y H:i A");?> 
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;padding-right: 3px;" >
										Receipt No.
									</td>
									<td style="vertical-align: top;line-height: 15px;border-bottom: 1px solid #000000;padding-left: 3px;" > 
										<?php echo $payment_details->receipt_no;?>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;padding-right: 3px;" >
										GST No.
									</td>
									<td style="vertical-align: top;line-height: 15px;border-bottom: 1px solid #000000;padding-left: 3px;" > 
										<?php echo !empty($ApplyOnlines->gstno) ? $ApplyOnlines->gstno : '-';?>
									</td>
								</tr>
								<tr><td colspan="3">Received with thanks by Online Transaction No. <?php echo $payment_details->transaction_id;?></td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td >&nbsp;</td>
					</tr>
					<tr>
						<td >
							<table style="border:1px solid #000000;border-collapse: collapse;" cellpadding="5" width="100%">
								<tr>
									<td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Sr.
									</td>
									<td style="vertical-align: top;width:65%;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;"  class="td_bold">
										Particulars
									</td>
									<td style="width:25%; vertical-align: top;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">
										Amount (Rs.)
									</td>
								</tr>
								<tr>
									<td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">1.
									</td>
									<td style="vertical-align: top;width:65%;border-right: 1px solid #000000;border-bottom: 1px solid #000000;" class="td_simple">
										Registration Fee- Solar Project
									</td>
									<td style="width:25%; vertical-align: top;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
										<?php echo $ApplyOnlines->disCom_application_fee;?>
									</td>
								</tr>
								<?php
								$cgst           = ($ApplyOnlines->jreda_processing_fee/2);
								$total_amount   = $ApplyOnlines->disCom_application_fee+$ApplyOnlines->jreda_processing_fee;
								?>
								<tr>
									<td  style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">2.
									</td>
									<td style="vertical-align: top;width:65%;border-right: 1px solid #000000;border-bottom: 1px solid #000000;" class="td_simple">
										Output CGST @ 9%
									</td>
									<td style="width:25%; vertical-align: top;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
										<?php echo $cgst;?>
									</td>
								</tr>
								<tr>
									<td  style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">3.
									</td>
									<td style="vertical-align: top;width:65%;border-right: 1px solid #000000;border-bottom: 1px solid #000000;"  class="td_simple">
										Output SGST @ 9%
									</td>
									<td style="width:25%; vertical-align: top;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
										<?php echo $cgst;?>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-bottom: 1px solid #000000;" class="td_simple">
									Application No:  <?php echo $ApplyOnlines->application_no;?><br>
									Received from <?php echo $ApplyOnlines->customer_name_prefixed;?> <?php echo $ApplyOnlines->name_of_consumer_applicant;?> for registration fees for <?php echo $ApplyOnlines->pv_capacity;?> kW solar PV project.</td>
									<td style="width:25%; vertical-align: top;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $total_amount;?></td>
								</tr>
								<tr>
									<td colspan="3" style="border-bottom: 1px solid #000000;" class="td_simple">Rupees: <?php echo ucwords(getIndianCurrency($total_amount));?></td>
								</tr>
								<tr>
									<td colspan="3" style="" class="td_simple">This online transaction is subject to realization.</td>
								</tr>
								<tr>
									<td colspan="3" style="">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" align="center" class="td_bold">This is computer generated receipt and doesn’t require signature.</td>
								</tr>
							</table>
						</td>
					</tr>          
				</table>
			</div>
		</div>
	</body>
</html>