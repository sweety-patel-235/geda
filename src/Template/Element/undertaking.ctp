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
						<?php /*	<?php
							$image_path = ROOT . DS ."webroot/pdf/images/geda.jpg";
							$type = pathinfo($image_path, PATHINFO_EXTENSION);
							$data = file_get_contents($image_path);
							$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
							?>
						<img src="<?php echo $base64;?>"  width="225px" height="80px"> */?>
						<div align="center">
							<p style="color:black;margin-bottom:0;font-size: 22px;" class="td_bold">UNDERTAKING</p>
						</div> 
						</td>   
					</tr>  
					<tr>
						<td style="height: 380px">&nbsp;
						
						</td>   
					</tr>  
					
					<tr>
						<td align="left" style="margin-right:10px;" class="td_bold">
						To, <br/>
						The Director, <br/>
						Gujarat Energy Development Agency <br/>
						4th Floor, Udyog Bhavan, Block No 11-12, Sector 11, <br/>
						Gandhinagar, Gujarat - 382010. 

						</td>   
					</tr>  
					
			
					<tr>
						<td class="td_simple"> 
							<p class="td_bold">Sub : Undertaking for opting into the Gujarat Renewable Energy Policy-2023 and to be bound by all provisions decided by the Gujarat Electricity Regulatory Commission (GERC).</p>
							<p>
								<table>
									<tr>
										<td style="text-align:justify">I/We, [Name of Renewable Energy Generator], hereby undertake to comply with all the provisions of the Gujarat Renewable Energy Policy-2023 and the GERC regulations issued from time to time. We understand that we are bound by all the provisions that are decided by the GERC.</td>
									</tr>
									<tr>
										<td style="text-align:justify">We understand that the GERC has not yet issued regulations regarding the applicability of Open Access charges and losses, Energy Accounting, Banked Energy, Banking Charges, Peak Charges, and other charges under the Gujarat Renewable Energy Policy-2023. However, we agree to be bound by whatever regulations the GERC issues in the future.</td>
									</tr>
									<tr>
										<td style="text-align:justify">We further undertake that we are bound by all the provisions, rules, and regulations that may be decided by the Gujarat Electricity Regulatory Commission (GERC), even if those provisions, rules, and regulations impact the payback or return on investment of the project.</td>
									</tr>
								</table>
							</p>          
						</td>
					</tr>                      
				</table>
				<table>
					<tr>
						<td class="td_bold">Consumer No ____________________________</td>
					</tr>
					<tr>
						<td class="td_bold">Sanctioned Load / Contract Demand (in kW) __________</td>
					</tr>
				
					<tr>
						<td class="td_bold">Name: ___________</td>
					</tr>
					<tr>
						<td class="td_bold">Designation:</td>
					</tr>
					<tr>
						<td class="td_bold">Dated this __ day of _____ 2023</td>
					</tr>
					<tr>
						<td class="td_bold">Solemnly affirmed at Ahmedabad (this __ day of ____ 2023)</td>
					</tr>
					<tr>
						<td class="td_bold">BEFORE ME (NOTARY)</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>