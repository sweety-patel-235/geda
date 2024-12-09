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
			#header{
				color: black;
				font-size: 16px;
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
				font-weight:normal;
			}
			.active_status {
				background-color: orange;
				color:white;
			}
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
			.td_bold{ font-family: 'arial_bold'; font-size: 14px;}
			.td_italic{font-family: 'arial_italic'; font-size: 14px;}
			.td_simple{font-family: 'arial_simple';font-size: 14px;}
			</style>
	</head>
	<body id="pdf-header">
		<?php $jir_unique_code = $ApplyOnlines['apply_onlines_others']['jir_unique_code'];?>
		<div id="footer">
			<table>
				<tr>
					<td class="td_simple">
						JIR Unique Code : <?php echo $jir_unique_code;?>
					</td>
					
				</tr>
			</table>
		</div>
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
				$text       = ""; //Page {PAGE_NUM} of {PAGE_COUNT}
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
				<table width="100%" >
					<tr>
						<td colspan="2">
							<table cellspacing="5" cellpadding="5" align="center">
								<tr>
									<td id="header">
										<b><u>CERTIFICATE BI-DIRECTIONAL METER INSTALLATION FOR </u></b>
									 </td>
								</tr>
								<tr>
									<td id="header">
										 <center><b><u>ROOFTOP SOLAR PV SYSTEM</u></b></center>
									 </td>
								</tr>                                
							</table>
						</td>
					</tr>
					<br>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="2" cellpadding="2">
								<tr>
									<td>
										<?php 
										$bidirectional_date     = '-';
										if(isset($Charging_data->meter_installed_date) && !empty($Charging_data->meter_installed_date))
										{
											$bidirectional_date = $Charging_data->meter_installed_date;
										}
										elseif(isset($Ins_data->bi_date) && !empty($Ins_data->bi_date))
										{
											$bidirectional_date = $Ins_data->bi_date;
										}
										if($bidirectional_date != '-')
										{
											$arr_date           = explode(",",$bidirectional_date);
											$bidirectional_date = $arr_date[0];
										}
										?> 
										<p style="line-height:28px; text-align:justify; margin-right:20px;">This is certify that a <?php echo number_format($ApplyOnlines->pv_capacity,3,'.','');?> kW grid connected Rooftop Solar PV System and a Bi-directional meter for net metering has been installed on (Date) <u>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</u>&nbsp; upon certificate of Chief Electrical Inspector/Contractor for charging of the installation, at the premises of (Name) <?php echo $ApplyOnlines->customer_name_prefixed;?> <?php echo $ApplyOnlines->name_of_consumer_applicant;?> Consumer No. <?php echo$ApplyOnlines->consumer_no;?> at (Address) 
										<?php echo $ApplyOnlines->address1; ?>,<?php echo $ApplyOnlines->address2;?>,<?php echo isset($ApplyOnlines->district) && !empty($ApplyOnlines->district) ? $ApplyOnlines->district : '-';?>,<?php echo $ApplyOnlines->city; ?>,<?php echo $ApplyOnlines->state; ?>-<?php echo $ApplyOnlines->pincode; ?>. The date of installation of the bi-directional meter is reckoned as the date of commissioning of the above Rooftop Solar PV System.</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<br>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="3" cellpadding="3" rows="4" cols="4">
								<tr>
									<td style="width: 75px;">
									</td>
									<td id="header">
										DISCOM
									</td>
									<td id="header">
										EMPANELLED AGENCY
									</td>
									<td id="header">
										CONSUMER
									</td>
								</tr>
								<br>
								 <tr>
									<td style="width: 75px;">
										Name :
									</td>
									<td style="width: 75px;">
									   <?php echo isset($discom_list[$ApplyOnlines->discom]) ? $discom_list[$ApplyOnlines->discom] : ''; ?>
									</td>
								   <td style="width: 75px;">
									   <?php echo $ApplyOnlines->installer['installer_name']; ?>
									</td>
								   <td style="width:150px; "><?php echo $ApplyOnlines->name_of_consumer_applicant; //<div style="border-bottom:1px solid #000; margin-right:20px; height: 20px">?></div>
									   
									</td>
								</tr>
								 <tr>
									<td style="width: 75px;">
										Designation :
									</td>
								   <td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px"></div>
									   
									</td>
									<td style="width:150px;">
									   Site Engineer
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px"></div>
									   
									</td>
								</tr>
								<br>
								<tr>
									<td style="width: 75px;">
										Signature & Stamp :
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px"></div>
									   
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px"></div>
									   
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px"></div>     
									</td>
								</tr>     
							</table>
						</td>
					</tr>
				</table>
				<div style="page-break-after: always;"></div>
				<table>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td>
										<p style="line-height:28px; text-align:justify;margin-right:20px;">This is to certify that the GEDA empaneled agency/ Installer has supplied the Beneficiary/ Consumer a Rooftop Solar PV system of <?php echo number_format($project_data->recommended_capacity,3,'.','');?> kW upon deducting of the following mentioned GEDA and MNRE subsidy. </p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="width: 75px;">
										<ul>
											<li>GEDA Subsidy Amount:</li>
										</ul>
									</td>
									<td style="width: 75px;">
										 <?php echo $state_subsidy_amount;?>
									</td>
								</tr>
								<tr>  
									<td style="width: 75px;">
										<ul>
											<li>MNRE Subsidy Amount:</li>
										 </ul>
									</td>
									<td style="width: 75px;">
										<?php echo $MNRE_subsidy_amount;?>
									</td>
								</tr>
								<tr>
									<td style="width: 75px;">
										<ul>
											<li>Total Subsidy Amount:</li>
										</ul>
									</td>
									<td style="width: 75px;">
										<?php echo $total_subsidy_amount;?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="td_simple">
							<table cellspacing="4" cellpadding="4" rows="4" cols="4">
								<tr>
									<td style="width: 75px;">
									</td>
									<td style="width:150px;" id="header">
										EMPANELLED AGENCY      
									</td>
									<td style="width:150px;" id="header">
										CONSUMER/ BENIFICIARY 
									</td>
								  
								</tr>
								<br>
								 <tr>
									<td style="width: 75px;">
										Name 
									</td>
									<td style="width:150px;">
									   <?php echo $ApplyOnlines->installer['installer_name']; ?>
									</td>
									<td style="width:150px; "><?php echo $ApplyOnlines->name_of_consumer_applicant;//<div style="border-bottom:1px solid #000; margin-right:20px; height: 20px;margin-bottom:30px;">?></div>  
								</tr>
								 <tr>
									<td style="width: 75px;">
										Signature & Stamp
									</td>  
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px;margin-bottom:30px;"></div>
									   
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px;margin-bottom:30px;"></div>     
									</td>
								</tr>
								<tr>
									<td style="width: 75px;">
										
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px;margin-top: 50px;"></div>
									</td>
									<td style="width:150px; "><div style="border-bottom:1px solid #000; margin-right:20px; height: 20px;margin-top: 50px;"></div>     
									</td>
								  <!--  <td style="width:150px; " colspan="2"><div style="border-top:1px solid #000; margin-right:20px;margin-top: 70px; height: 20px"></div>
									   
									</td>   -->
								</tr>
								<br>
							   
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>