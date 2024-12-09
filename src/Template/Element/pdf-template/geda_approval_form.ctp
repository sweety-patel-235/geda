<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $pageTitle; ?></title>
		<!-- Style CSS -->
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css"/>
		<style>
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
				background-color: white;
				font-size: 14px;
			}
			#headerA {
				position: fixed;
				left: 0px; right: 0px; top: 0px;
				text-align: center;
				background-color: white;
				height: 100px;
			}
			#headerB {
				position: absolute;
				left: -20px; right: -20px; top: -200px;
				text-align: center;
				background-color: white;
				height: 100px;
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
			.td_bold{ font-family: 'arial_bold';}
			.td_italic{font-family: 'arial_italic';}
			.td_simple{font-family: 'arial_simple';}
			</style>
	</head>
	<?php 
	$arrayOfQue         = array("1.a"=>"Solar PV Modules Made In India?",
								"1.b"=>"Solar PV module has minimum capacity of 200Wp?",
								"1.c"=>"Rated Output Power tolerance is between +/- 3%?",
								"1.d"=>"Is an I-V curve been provided to the consumer by the developer under STC?",
								"2.a"=>"Inverter Capacity is as per power plant capacity?",
								"2.b"=>"Is PCU/Inverter capable of complete automatic operation including wake-up, syncronization & shutdown?",
								"3.a"=>"Structures are made up of Hot dip galvanized MS mounting structures?",
								"3.b"=>"Mounting structure steel material thickness minimum of 2.5 mm?",
								"3.c"=>"Structure Material other than G.I.(Galvanized Iron)?",
								"3.d"=>"Is the fasteners made up of stainless steel?",
								"3.e"=>"Is the SPV structure well-grounded/fastened?",
								"3.f"=>"Is minimum clearance of the structure from the roof/ground level minimum 300 mm?",
								"4.a"=>"Cables connected through Cable glands?",
								"4.b"=>"Is the Junction Box followed IP65 Standard and IEC 62208?",
								"4.c"=>"MCBs/MCCB is installed?",
								"5.a"=>"Nos. of Lighting Arrestors (if System Capacity more than 10 kW)",
								"5.b"=>"Nos. of Earthing Protection",
								"6.a"=>"PVC/XLPE Cables used in system?",
								"6.b"=>"All wiring are concealed/ in rigid PVC pipe/ PVC Patti?",
								"7.a"=>"IS an electrical drawings and Installation and O&M manuals been provided to the consumer by the developer?",
								"8"=>"Additional structure/ items provided, if any, and details thereof.",
								"9"=>"Remarks, if any"
						);
	$non_selectbox          = array(16,17,18,22);
	$array_yes_no           = array("1"=>"Yes","2"=>"No");
	?>
	<body id="pdf-header">
		<script type="text/php">
			if (isset($pdf)) {
				$x          = 50;
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
			<div id="content" class="mainbox" style="width:675px;">
				<table cellspacing="0" cellpadding="0" width="100%">
					<thead>
						<tr>
							<td colspan="2" width="500">
								<table cellspacing="0" cellpadding="0" width="500" style="margin-top:70px;">
									<tr>
										<td align="center">
											<p class="td_simple">
												Gujarat Energy Development Agency,Gandhinagar<br />
												Checklist for Inspection/ Verfication of SPP at Site for Quality Assurance
											</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</thead>
					<tr>
						<td colspan="2" width="500">
							<table cellspacing="0" cellpadding="5" width="500" border="1">
							<tr>
								<td colspan="3" class="td_simple">Name of Supplier: <?php echo $INSTALLER_NAME;?></td>
							</tr>
							<tr>
								<td width="30" class="td_simple">Sr. No.</td>
								<td width="470" colspan="2" class="td_simple">
									<table cellspacing="0" cellpadding="0" width="470" border="0">
									<tr>
										<td valign="top" width="160" rowspan="2">Name & Address of the Applicant:</td>
										<td><?php echo $CUSTOMER_NAME;?></td>
									</tr>
									<tr>
										<td ><?php echo $CUSTOMER_ADDRESS;?></td>
									</tr>
									<tr>
										<td>GEDA Registration No:</td>
										<td><?php echo $GEDA_REGISTRATION_NO;?></td>
									</tr>
									</table>
								</td>
							</tr>
							<?php 
								$key = 0;
								foreach ($arrayOfQue as $sr_no=>$Question) {
								if ($key == 15) {
									echo '</table><div style="page-break-after: always;" ></div><table cellspacing="0" cellpadding="5" width="500" border="1">';
								}
							?>
							<tr>
								<td valign="middle" width="30" class="td_simple"><?php echo $sr_no;?></td>
								<?php if ($sr_no != 9) { ?>
									<td valign="top" class="td_simple"><?php echo $Question;?></td>
									<?php if (!in_array($key,$non_selectbox)) { ?>
										<td  valign="top" class="td_simple">
											<?php if (isset($INSPECTION_DATA[$key])) { ?>
												<?php echo (isset($array_yes_no[$INSPECTION_DATA[$key]])?$array_yes_no[$INSPECTION_DATA[$key]]:"-");?>
											<?php } else { ?>
												<?php echo implode("&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;", $array_yes_no); ?>
											<?php } ?>
										</td>
									<?php } else { ?>
										<td valign="top" height="30" class="td_simple">
											<?php echo (isset($INSPECTION_DATA[$key])?$INSPECTION_DATA[$key]:"");?>
										</td>
									<?php } ?>
								<?php } else { ?>
									<td width="470" valign="top" height="100" colspan="2" class="td_simple">
										<?php echo $Question;?>: <?php echo (isset($INSPECTION_DATA[$key])?$INSPECTION_DATA[$key]:"");?>
									</td>
								<?php } ?>
							</tr>
							<?php 
									$key++;
								}
							?>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" width="500">
							<table cellspacing="1" cellpadding="1" width="500">
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" width="500">
							<table cellspacing="1" cellpadding="1" width="500">
								<tr>
									<td colspan="3" class="td_bold">
										It is certified the system is installed / not installed satisfactory, and is found to be as per / not as per the specification of GEDA / MNRE.
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" width="500">
							<table cellspacing="1" cellpadding="1" width="500">
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" width="500">
							<table cellspacing="1" cellpadding="1" width="500">
								<tr>
									<td class="td_simple">Name & Signature of Beneficiary with data</td>
									<td>&nbsp;</td>
									<td class="td_simple">Name & Signature of Officer with data</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>