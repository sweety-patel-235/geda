<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Fees Return</title>
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
			.td_bold{ font-family: 'arial_bold';font-size: 13px;}
			.td_italic{font-family: 'arial_italic';font-size: 13px;}
			.td_simple{font-family: 'arial_simple';font-size: 13px;}
			@page {
				margin: 10px;
			}
			body {
				margin: 20px;
			}
			.text_justify li{
				text-align:justify;
			} 
			.border_class {
				border-style: solid; 
				border-width: 1px;
			}
			.page-break{
				page-break-after: always;
			}

			</style>
	</head>

	<body id="pdf-header">
		<script type="text/php">
			if (isset($pdf)) {
				$curdate    = date('d-M-Y');
				$fees_return_no =  "SSDSP Refund Receipt No. : ##RECEIPT_NO##";
				$x          = 35;
				$y          = 810;
				$text       = "$fees_return_no, Date: $curdate##JIR_CODE##";
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
							<p class="td_simple">(Climate Change Department, Government of Gujarat)<br>4th floor, Block No. 2, Udhyogbhavan, Sector 11, Gandhinagar,<br>
							Ph: 079-23257251-54, GST. No. 24AAATG1858Q1ZA
							</p>
							<p></p>
							<p class="td_simple">Application for Refund of registration fees received by GEDA against the registration of Small Scale Distributed Solar Projects.</p>
						</div> 
						</td>   
					</tr>   
					<tr>
						<td class="td_simple" style="text-align: justify;">
							The refund of registration fees received by GEDA against the registration of Small Scale Distributed Solar Projects under the “Policy for Development of Small Scale Distributed Solar Projects-2019 of Government of Gujarat, resolution no. SLR/11/2019/51/B1 dated 6th March, 2019 and guidelines for implementation of Policy for Development of Small Scale Distributed Solar Projects-2019 of Government of Gujarat, resolution No. SLR/11/2019/51/B1 dated 15th November, 2019” shall be as per the GUVNL Notice “Public Notice – Policy for Development of Small Scale Distributed Solar Projects- 2019” dated 09/08/2021. 
						</td>         
					</tr>
					<tr>
						<td class="td_simple" style="text-align: right">
							SSDSP Refund Receipt No. :<?php echo $FeesReturn->fees_return_no;?>
						</td>
					</tr>
					<tr>
						<td>
						<table style="border:1px solid #000000;border-collapse: collapse;font-size:10px;" cellspacing="1" cellpadding="3" align="center" width="100%">
							<tr>
								<td class="border_class td_simple"  width="10%">Sr. No.</td>
								<td class="border_class td_simple" width="20%">Segments</td>
								<td class="border_class td_simple"  width="70%">Details</td>
							</tr>
							<tr>
								<td class="border_class td_simple serial_class">1.</td>
								<td class="border_class td_simple"  style="width:48%;"><label>Name of SPG/ Applicant</label></td>
								<td class="border_class td_simple" style="width:48%;"><?php echo $FeesReturn->spg_applicant;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">1.1</td>
								<td class="border_class td_simple" ><label>Mobile No. of Applicant</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->mobile;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">1.2</td>
								<td class="border_class td_simple" ><label>Email of Applicant</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->email;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple serial_class">2.</td>
								<td class="border_class td_simple" ><label>GEDA registration no. & Date</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->registration_no.'<br>'.date('d-m-Y',strtotime($FeesReturn->registration_date));?></td>
							</tr>
							<tr>
								<td class="border_class td_simple serial_class">3.</td>
								<td class="border_class td_simple" ><label>Project Capacity kW(AC)</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->capacity;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">4.</td>
								<td class="border_class td_simple" ><label>Name of DISCOM</label></td>
								<td class="border_class td_simple"><?php echo $discom_name;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple serial_class">5.</td>
								<td class="border_class td_simple" colspan="2" ><label>Details of Payment Receipt issued by GEDA </label></td>
								
							</tr>
							<tr>
								<td class="border_class td_simple">5.1</td>
								<td class="border_class td_simple" ><label>Payment Receipt</label></td>
								<td class="border_class td_simple"><?php echo (isset($FeesReturn->payment_receipt) && !empty($FeesReturn->payment_receipt)) ? 'Yes' : 'No';?></td>
							</tr>
							<?php 
							if($FeesReturn->payment_receipt == 1) { ?>
								<tr>
									<td class="border_class td_simple">5.2</td>
									<td class="border_class td_simple" ><label>Receipt No. </label></td>
									<td class="border_class td_simple"><?php echo $FeesReturn->receipt_no;?></td>
								</tr>
								<tr>
									<td class="border_class td_simple">5.3</td>
									<td class="border_class td_simple" ><label>Receipt Date</label></td>
									<td class="border_class td_simple"><?php echo date('d-m-Y',strtotime($FeesReturn->receipt_date));?></td>
								</tr>
								<tr>
									<td class="border_class td_simple">5.4</td>
									<td class="border_class td_simple" ><label>Receipt amount in Rs. </label></td>
									<td class="border_class td_simple"><?php echo $FeesReturn->demand_amount;?></td>
								</tr>
							<?php } ?>
							<?php /*<tr>
								<td class="border_class td_simple">5.</td>
								<td class="border_class td_simple" ><label>Name of GETCO S/S</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->name_getco;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">6.</td>
								<td class="border_class td_simple" colspan="2" ><label>Details of Demand Draft which was submitted by the applicant to GEDA at the time of registration</label></td>
								
							</tr>
							<tr>
								<td class="border_class td_simple">6.1</td>
								<td class="border_class td_simple" ><label>Demand Draft No.</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->draft_no;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">6.2</td>
								<td class="border_class td_simple" ><label>Demand Draft Date</label></td>
								<td class="border_class td_simple"><?php echo date('d-m-Y',strtotime($FeesReturn->draft_date));?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">6.3</td>
								<td class="border_class td_simple" ><label>Bank Name</label></td>
								<td class="border_class td_simple" ><?php echo $FeesReturn->demand_bank_name;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">6.4</td>
								<td class="border_class td_simple" ><label>D.D. Amount in Rs. </label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->demand_amount;?></td>
							</tr>
							*/ ?>
							<tr>
								<td class="border_class td_simple">6.</td>
								<td class="border_class td_simple" colspan="2" ><label>SPG/Applicant’s account details</label></td>
								
							</tr>
							<tr>
								<td class="border_class td_simple">6.1</td>
								<td class="border_class td_simple" ><label>Account no.</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->account_no;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">6.2</td>
								<td class="border_class td_simple" ><label>Bank Name</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->bank_name;?></td>
							</tr>
							<tr>
								<td class="border_class td_simple">6.3</td>
								<td class="border_class td_simple" ><label>IFSC code</label></td>
								<td class="border_class td_simple"><?php echo $FeesReturn->ifsc_code;?></td>
							</tr>
							<?php /*<tr>
								<td class="border_class td_simple">7.</td>
								<td class="border_class td_simple" colspan="2" ><label>Details of Power Purchase Agreement</label></td>
							</tr>
							 <tr>
								<td class="border_class td_simple">7.1</td>
								<td class="border_class td_simple" ><label>Date of PPA signed with DISCOM</label></td>
								<td class="border_class td_simple"><?php echo date('m-d-Y',strtotime($FeesReturn->date_ppa_signed));?></td>
							</tr> */?>
							<tr>
								<td class="border_class td_simple">7.</td>
								<td class="border_class td_simple" ><label>Date of PPA termination with DISCOM</label></td>
								<td class="border_class td_simple"><?php echo date('d-m-Y',strtotime($FeesReturn->date_ppa_term));?></td>
							</tr>
							
						</table>
					</td></tr>
					<tr>
						<td class="td_simple" style="text-align: justify;">&nbsp;
						</td>         
					</tr>                         
				</table>
				<div class="page-break" ></div>
				<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:30px;">
					<tr>
						<td align="" class="td_bold" >
							Declaration to be signed by the authorized signatory of the applicant:<p></p>
						</td>
					</tr>
					<tr>
						<td  class="td_simple">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="td_simple">
							<div style="text-align:justify;">
								I	(Name & designation)  _____________________________________ (authorized representative of M/s._____________________________________ ) declare that we have choosen “one-time option to exit” from PPA with DISCOM and GEDA registration mentioned at point 2 above for Receipt No.: <?php echo $FeesReturn->fees_return_no;?>. I have read and understood GUVNL Notice “Public Notice – Policy for Development of Small Scale Distributed Solar Projects- 2019” dated 09/08/2021.
							</div>
						</td>
					</tr>
					<tr>
						<td  class="td_simple">
							<div style="text-align:justify;">
								Signature of Applicant and seal of the company:
							</div>
						</td>
					</tr>
					<tr>
						<td  class="td_simple">
							<p>Place:<br>Date:</p>
						</td>
					</tr>
					<tr>
						<td  class="td_simple">
							Please submit Hard copy of Application submitted online alongwith following Documents.
						</td>
					</tr>
					<tr>
						<td  class="td_simple">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td  class="td_simple">
							<?php if(empty($FeesReturn->payment_receipt)) { ?>
								Indemnity Bond for GEDA (Original)<br>
							<?php } ?>
							GEDA Registration Letter (Original)<br>
							<?php if($FeesReturn->payment_receipt == 1) { ?>
								GEDA Registration Payment Receipt (Original)<br>
							<?php } ?>
							Undertaking submitted to Discom(Copy)<br>

						</td>
					</tr>
				</table> 
			</div>
		</div>
	</body>
</html>