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
			.text_justify li{
				text-align:justify;
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
				font-weight:bold;
			}
			.active_status {
				background-color: orange;
				color:white;
			}
			.border_class {
				border-style: solid; 
				border-width: 1px;
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
				<table width="100%" align="center">
					<tr>
						<td>
							<table cellspacing="5" cellpadding="3">
								<tr>
									<td id="header">
										 <center><b>Technical DATA FORM FOR FEASIBILITY CLEARANCE OF ROOFTOP SOLAR PV PLANT</b></center>
									 </td>   
								</tr>
								
							</table>
						</td>
					</tr>
				</table>
				<table width="100%" align="center" >
					<tr>
						<td>
							<table style="border:1px solid #000000;border-collapse: collapse;" cellspacing="1" cellpadding="3" align="center">
								<tr>
									<td align="left" style="width: 230px;" class="border_class">Date and Time of Submission of Report</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->created;?> 
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of Consumer</td>
									<td align="left" class="border_class">
										<?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Email ID</td>
									<td align="left" class="border_class">
										<?php echo $ApplyOnlines->email; ?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Consumer No.</td>
									<td align="left" class="border_class">
										 <?php echo $ApplyOnlines->consumer_no; ?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Application No.</td>
									<td align="left" class="border_class">
										<?php echo $ApplyOnlines->aid; ?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of Sub-Division</td>
									<td align="left" class="border_class">
										<?php
												if (!empty($subdivision) && isset($subdivision[0]['title'])) {
													echo $subdivision[0]['title'];
												} else {
													echo "-";
												}
											?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of Division</td>
									<td align="left" class="border_class">
										<?php
												if (!empty($division) && isset($division[0]['title'])) {
													echo $division[0]['title'];
												} else {
													echo "-";
												}
										?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of Circle</td>
									<td align="left" class="border_class">
										<?php
												if (!empty($circle) && isset($circle[0]['title'])) {
													echo $circle[0]['title'];
												} else {
													echo "-";
												}
											?>    
									</td>
								 </tr>
								 <tr>
									<td align="left" style="width: 230px" class="border_class">Contact No.</td>
									<td align="left" class="border_class">
										<?php echo $ApplyOnlines->mobile;?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Category</td>
									<td align="left" class="border_class">
										<?php echo $category; ?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Address/ Location</td>
									<td align="left" class="border_class">
										 <?php echo $ApplyOnlines->comunication_address; ?>            
									</td>           
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Sanctioned Load / Contract Load of Customer (kW)</td>
									<td align="left" class="border_class">     
										<?php echo $FeasibilityData->sanction_load;?>   
									</td>
									
								</tr>
								<?php /*
								<tr>
									<td align="left" style="width: 230px" class="border_class">Supply Voltage</td>
									<td align="left" class="border_class">   
										<?php echo $FeasibilityData->supply_voltage;?>    
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Capacity of Proposed SPVPP (in KW)</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->proposed_capacity;?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name/ Location of feeding Transformer</td>
									<td align="left" class="border_class">  
										 <?php echo $FeasibilityData->transformer_location;?>     
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Capacity of above Transformer (kVA)</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->transformer_capacity;?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Connected Load (kVA) on the Transformer</td>
									<td align="left" class="border_class">   
										<?php echo $FeasibilityData->connected_load;?>    
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Maximum Demand in Amps</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->max_demand;?> 
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">No. of LT Circuits</td>
									<td align="left" class="border_class">     
										<?php echo $FeasibilityData->lt_circuits;?>   
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Length of LT Circuit</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->length_lt_circuits;?> 
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Size of Conductor (sq. mm)</td>
									<td align="left" class="border_class">   
										<?php echo $FeasibilityData->conductor_size;?>    
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Maximum Demand in Amps</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->max_demand;?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of Feeder</td>
									<td align="left" class="border_class">  
										<?php echo $FeasibilityData->name_of_feeder;?>     
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Size of Conductor / Capacity</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->size_conductor_capacity;?> 
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of feeding Sub-Station</td>
									<td align="left" class="border_class">  
										 <?php echo $FeasibilityData->substation_name;?>
									</td>
									
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">SPVPPs already connected on the Distribution Transformer (in kW)</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->spvpp_distribution_transformer;?>
									</td>
								</tr>
								  <tr>
									<td align="left" style="width: 230px" class="border_class">Senior pending SPVPPs to be connected on the Transformer (in kW)</td>
									<td align="left" class="border_class">
										<?php echo $FeasibilityData->pending_transformer;?>
									</td>
								</tr>
								*/?>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Recommended Capacity by Field Office (kW)</td>
									<td align="left" class="border_class">  
										 <?php echo $FeasibilityData->recommended_capacity_by_discom;?>
									</td>
									
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Approved?</td>
									<td align="left" class="border_class">  
										<?php if($FeasibilityData->approved==1) 
										{
											echo 'Yes';
										}
										else
										{
											echo 'No';
										}
										?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Quotation Number</td>
									<td align="left" class="border_class">  
										<?php echo $FeasibilityData->quotation_number;?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Estimated Amount</td>
									<td align="left" class="border_class">  
										<?php echo $FeasibilityData->estimated_amount;?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Estimated Due Date</td>
									<td align="left" class="border_class">  
										 <?php echo date('d-m-Y',strtotime($FeasibilityData->estimated_due_date));?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Payment Status</td>
									<td align="left" class="border_class">  
										<?php
										if($FeasibilityData->payment_approve==1) 
										{ 
											echo 'Paid'; 
										}
										else
										{
											echo 'Not Paid';
										}
										?>
									</td>
								</tr>
								<tr>
									<td align="left" style="width: 230px" class="border_class">Name of the Field Officer</td>
									<td align="left" class="border_class">  
										 <?php echo $division[0]->title;?>
									</td>
									
								</tr>
							</table>
						</td>
					</tr>                    
				</table> <?php /*
				<table width="100%"  align="center">
					<tr>
						<td>
							<table border="2" cellpadding="3" align="center">
							   
								<tr>
									<td align="left" style="width: 230px">Capacity of proposed SPVPP on this Transformer (in kW)</td>
									<td align="left">
										 <?php echo $FeasibilityData->capacity_spvpp;?>
									</td>
								</tr>
								 <tr>
									<td align="left" style="width: 230px">Total load on this Transformer (in kW)
									</td>
									<td align="left">
										<?php echo $FeasibilityData->total_load; ?>
									</td>
								</tr> 
								
							</table>
							</td>
					</tr>            
				</table>*/?>
			</div>
		</div>
	</body>
</html>