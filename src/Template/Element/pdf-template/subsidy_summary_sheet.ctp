<!DOCTYPE html>
<html lang="">
	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ApplyOnline Subsidy Summary Sheet</title>
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
				margin: 5px;
			}
			body {
				margin: 5px;
			}
			</style>
	</head>
	<body class="td_simple">
		<div class="container td_simple">
			<!-- HEADER MARGIN FIRST PAGE -->
			<!--div id="headerA"><h1></h1></div-->
			<!-- HEADER MARGIN -->
			<!-- HEADER MARGIN ALL PAGES-->
			<!--div id="headerB"><h1></h1></div-->
			<!-- HEADER MARGIN ALL PAGES -->
			<div id="content" class="mainbox td_simple" style="width: 95%;">
				<table width="100%" align="center" class="td_simple">
					<tr>
						<td>
							<table width="99%" cellspacing="5" cellpadding="3" class="td_simple">
							<tr>
								<td id="" align="center" class="td_bold">Summary Report for Subsidy Claim</td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="td_simple">
					<tr>
						<td>
							<table width="99%" border="1" cellpadding="2" align="center" cellspacing="0" class="td_simple">
							<tr>
								<td width="3%" class="td_bold">Sr.</td>
								<td width="18%" class="td_bold">Consumer Details</td>
								<td width="15%" class="td_bold">Registration No.</td>
								<td width="14.5%" class="td_bold">Installation Data</td>
								<td width="14.5%" class="td_bold">Subsidy Detail</td>
								<td width="10%" class="td_bold">Commissioning</td>
								<td width="15%" class="td_bold">Details of Electrical Inspector’s License Certificate</td>
								<td width="10%" class="td_bold">Consumer Photo</td>
							</tr>
							<?php foreach ($arrApplications as $row=>$arrApplication) { ?>
							<tr>
								<td valign="top" class="td_simple"><?php echo ($row+1);?></td>
								<td valign="top" class="td_simple">
									<?php echo $arrApplication['name_of_consumer_applicant'];?><br />
									<?php if (!empty($arrApplication['address1'])) {?>
										<?php echo $arrApplication['address1'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['address2'])) {?>
										<?php echo $arrApplication['address2'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['city'])) {?>
										<?php echo $arrApplication['city'];?>
									<?php } ?>
									<?php if (!empty($arrApplication['state'])) {?>
										&nbsp;,<?php echo $arrApplication['state'];?>
									<?php } ?>
									<?php if (!empty($arrApplication['pincode'])) {?>
										&nbsp;-&nbsp;<?php echo $arrApplication['pincode'];?>
									<?php } ?>
									<?php if (!empty($arrApplication['consumer_mobile'])) {?>
										<br /><?php echo $arrApplication['consumer_mobile'];?>
									<?php } ?>
									<?php if (!empty($arrApplication['aadhar_no_or_pan_card_no'])) {?>
										<br /><?php echo passdecrypt($arrApplication['aadhar_no_or_pan_card_no']);?>
									<?php } ?>
									<?php if (!empty($arrApplication['branch_masters']['title'])) {?>
										<br /><?php echo ($arrApplication['branch_masters']['title']);?>
									<?php } ?>
									<?php if (!empty($arrApplication['consumer_no'])) {?>
										<br /><?php echo $arrApplication['consumer_no'];?>
									<?php } ?>
								</td>
								<td valign="top" class="td_simple">
									<?php if (!empty($arrApplication['installer']['installer_name'])) {?>
										<?php echo ($arrApplication['installer']['installer_name']);?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['geda_application_no'])) {?>
										<?php echo $arrApplication['geda_application_no'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['geda_registration_date'])) {?>
										<?php echo date("Y-m-d",strtotime($arrApplication['geda_registration_date']));?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['pv_capacity'])) {?>
										<?php echo $arrApplication['pv_capacity']." (kW)";?><br />
									<?php } ?>
								</td>
								<td valign="top" class="td_simple">
									<?php 
										if (!empty($arrApplication['apply_onlines_subsidy']['modules_data'])) {
											$modules_data = unserialize($arrApplication['apply_onlines_subsidy']['modules_data']);
											if (!empty($modules_data)) 
											{
												$Total_Capacity = 0;

												foreach ($modules_data as $module_data) {
													$module_data['m_make'] = empty($module_data['m_make']) ? '-' : $module_data['m_make'];
													if (empty($module_data['m_capacity']) || empty($module_data['m_make']) || empty($module_data['m_modules'])) continue;

													$Module_Make = $module_data['m_make'];
													if (empty($module_data['m_type_other'])) {
														echo $Module_Make." ".$type_modules[$module_data['m_type_modules']]."<br />";
													} else {
														echo $Module_Make." ".$module_data['m_type_other']."<br />";
													}
													if (!empty($module_data['m_capacity'])) {
														echo $module_data['m_capacity']." Wp<br />";
													}
													if (!empty($module_data['m_modules'])) {
														echo $module_data['m_modules']." no.<br />";
													}
													$Total_Capacity += ($module_data['m_capacity']*$module_data['m_modules']);
												}
												echo "Total Installed Capacity (kW) - ".number_format(($Total_Capacity/1000),3,'.','')."<br />";
												echo "<hr />";
											}
										}
										if (!empty($arrApplication['apply_onlines_subsidy']['inverter_data'])) {
											$inverters_data = unserialize($arrApplication['apply_onlines_subsidy']['inverter_data']);
											if (!empty($inverters_data)) 
											{
												//(($inverter_data['i_make'] == "0") && empty($inverter_data['i_make_other']))||
												$Total_Capacity = 0;
												foreach ($inverters_data as $inverter_data) {
													if (empty($inverter_data['i_capacity']) || empty($inverter_data['i_type_modules']) ||  empty($inverter_data['i_modules'])) continue;
													if(strtolower($make_inverters[$inverter_data['i_make']]) == 'select')
													{
														$make_inverters[$inverter_data['i_make']]='-';
													}
													$Inverter_Make = empty($inverter_data['i_make_other'])?$make_inverters[$inverter_data['i_make']]:$inverter_data['i_make_other'];
													if (!empty($Inverter_Make)) {
														echo $Inverter_Make."<br />";
													}
													if (!empty($inverter_data['i_capacity'])) {
														echo $inverter_data['i_capacity']." kW<br />";
													}
													if (!empty($inverter_data['i_modules'])) {
														echo $inverter_data['i_modules']." no.<br />";
													}
													$Total_Capacity += ($inverter_data['i_capacity']*$inverter_data['i_modules']);
												}
												echo "Total Inverter Capacity (kW) - ".number_format($Total_Capacity,3,'.','')."<br />";
												echo "<hr />";
											}
										}
									?>
								</td>
								<td valign="top" class="td_simple">
									<?php 
									if(!empty($arrApplication['pcr_code']))
									{

										echo (!empty($arrApplication['approval_id']) && array_key_exists($arrApplication['approval_id'], $SubsidyTable->SPIN_APPROVAL)) ? $SubsidyTable->SPIN_APPROVAL[$arrApplication['approval_id']]['no'].'<br>' : '';
										echo 'PCR No.: '.$arrApplication['pcr_code'].'<br />Dated: '.(!empty($arrApplication['pcr_submited']) ? date('d-M-Y',strtotime($arrApplication['pcr_submited'])) : '-').'<br />';
									}
									if(!empty($arrApplication['subsidy_claim_requests']['request_no']))
									{
										echo 'Claim Request No.: '.$arrApplication['subsidy_claim_requests']['request_no'].'<br />';
									}
									
									?>
									<?php if (!empty($MNRE_SCHEME_TITLE)) {?>
										<?php echo ($MNRE_SCHEME_TITLE);?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['projects']['subsidy_details']['total_cost'])) {?>
										<?php echo "T-Rs "._FormatNumberV2($arrApplication['projects']['subsidy_details']['total_cost']);?><br />
									<?php } else { ?>
										<?php echo "-";?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['projects']['subsidy_details']['state_subsidy_amount'])) {?>
										<?php echo "S-Rs "._FormatNumberV2($arrApplication['projects']['subsidy_details']['state_subsidy_amount']);?><br />
									<?php } else { ?>
										<?php echo "-";?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['projects']['subsidy_details']['central_subsidy'])) {?>
										<?php echo "M-Rs "._FormatNumberV2($arrApplication['projects']['subsidy_details']['central_subsidy_amount']);?><br />
									<?php } else { ?>
										<?php echo "-";?><br />
									<?php } ?>
								</td>
								<td valign="top" class="td_simple">
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['comm_date'])) {?>
										<?php echo $arrApplication['apply_onlines_subsidy']['comm_date'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['project_installation']['bi_date'])) {?>
										<?php echo $arrApplication['project_installation']['bi_date'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['project_installation']['meter_serial_no'])) {?>
										<?php echo $arrApplication['project_installation']['meter_serial_no'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['project_installation']['meter_manufacture'])) {?>
										<?php echo $arrApplication['project_installation']['meter_manufacture'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['project_installation']['solar_meter_serial_no'])) {?>
										<?php echo $arrApplication['project_installation']['solar_meter_serial_no'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['project_installation']['solar_meter_manufacture'])) {?>
										<?php echo $arrApplication['project_installation']['solar_meter_manufacture'];?><br />
									<?php } ?>
								</td>
								<td valign="top" class="td_simple">
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['cei_licence_no'])) {?>
										<?php echo $arrApplication['apply_onlines_subsidy']['cei_licence_no'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['cei_authorised_by'])) {?>
										<?php echo $arrApplication['apply_onlines_subsidy']['cei_authorised_by'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['cei_licence_expiry_date'])) {?>
										<?php if ($arrApplication['apply_onlines_subsidy']['cei_licence_expiry_date'] != '0000-00-00') { ?>
											<?php echo $arrApplication['apply_onlines_subsidy']['cei_licence_expiry_date'];?><br />
										<?php } else { ?>
											<?php echo "-";?><br />
										<?php }?>
									<?php } ?>
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['cei_self_certification_date'])) {?>
										<?php if ($arrApplication['apply_onlines_subsidy']['cei_self_certification_date'] != '0000-00-00') { ?>
											<?php echo $arrApplication['apply_onlines_subsidy']['cei_self_certification_date'];?><br />
										<?php } else { ?>
											<?php echo "-";?><br />
										<?php } ?>
									<?php } ?>
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['cei_contractor'])) { ?>
										<?php echo $arrApplication['apply_onlines_subsidy']['cei_contractor'];?><br />
									<?php } ?>
									<?php if (!empty($arrApplication['apply_onlines_subsidy']['cei_superviser'])) {?>
										<?php echo $arrApplication['apply_onlines_subsidy']['cei_superviser'];?><br />
									<?php } ?>
								</td>
								<td valign="top" class="td_simple">
									<?php if (!empty($arrApplication['Profile_Photo_Url'])) { ?>
										<?php
										$type = pathinfo($arrApplication['ProfileFileName'], PATHINFO_EXTENSION);
										$data = $arrApplication['Profile_Photo_Url'];
										$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
										echo "<div style=\"text-align:center;\"><img src=\"".$base64."\" width=\"75px\"></div>";
										?>
									<?php } else { ?>
										<?php echo "-";?>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							</table>
						</td>
					</tr>                    
				</table>
			</div>
		</div>
	</body>
</html>