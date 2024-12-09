<div class="container">
	<div class="box">
		<div class="content">
			<div class="portlet box blue-madison applyonline-viewmain">
				<div class="portlet-body form">
					<div class="form-body">


						<div class="greenbox">
							<h4>General Profile Details</h4>
						</div>
						<div class="row">
							<div class="col-md-10 col-sm-8 ">
								<div class="row">
									<div class="col-md-7">
										<label>Name of Applicant Company</label>
										<?php echo isset($ApplicationData['name_of_applicant']) ? $ApplicationData['name_of_applicant'] : ""; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Provisional Registration No.</label>
										<?php echo isset($ApplicationData['applications']['registration_no']) ? $ApplicationData['applications']['registration_no'] : ""; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-7">
										<label>Street/House no</label>
										<?php
										if (isset($ApplicationData['address']) && isset($ApplicationData['address1'])) {
											$street = $ApplicationData['address'] . ',' . $ApplicationData['address1'];
										} else {
											$street = "";
										}
										echo $street;
										?>
									</div>
									<div class="col-md-5 left_space">
										<label>Taluka/Village</label>
										<?php echo isset($ApplicationData['city']) ? $ApplicationData['city'] : ""; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-7">
										<label>City</label>
										<?php echo isset($ApplicationData['city']) ? $ApplicationData['city'] : ""; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>State</label>
										<?php echo isset($ApplicationData['state']) ? $ApplicationData['state'] : ""; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>District</label>
										<?php echo isset($ApplicationData['district']) ? $ApplicationData['district_master']['name'] : ""; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Pincode</label>
										<?php echo isset($ApplicationData['pincode']) ? $ApplicationData['pincode'] : ""; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7 ">
										<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
										<?php echo isset($ApplicationData['contact']) ? $ApplicationData['contact'] : ""; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Consumer Mobile</label>
										<?php echo isset($ApplicationData['mobile']) ? $ApplicationData['mobile'] : ""; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7 ">
										<label>Consumer Email</label>
										<?php echo isset($ApplicationData['email']) ? $ApplicationData['email'] : ""; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>PAN card no.</label>
										<?php echo isset($ApplicationData['pan']) ? $ApplicationData['pan'] : ""; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7 ">
										<label>GST No. of Consumer</label>
										<?php echo isset($ApplicationData['GST']) ? $ApplicationData['GST'] : ""; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Type of Applicant</label>
										<?php echo isset($ApplicationData['type_of_applicant']) ? $ApplicationData['type_of_applicant'] : ""; ?>
									</div>
								</div>


							</div>
						</div>


						<div class="greenbox">
							<h4>Executive Details</h4>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Name of the Managing Director / Chief Executive of the Company</label>
								Mr. <?php echo isset($ApplicationData['name_director']) ? $ApplicationData['name_director'] : ""; ?>
							</div>

							<div class="col-md-6">
								<label>Designation</label>
								<?php echo isset($ApplicationData['type_director']) ? $ApplicationData['type_director'] : ""; ?>
							</div>
							<div class="col-md-6">
								<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
								<?php echo isset($ApplicationData['director_whatsapp']) ? $ApplicationData['director_whatsapp'] : ""; ?>
							</div>
							<div class="col-md-6">
								<label>Mobile</label>
								<?php echo isset($ApplicationData['director_mobile']) ? $ApplicationData['director_mobile'] : ""; ?>
							</div>
							<div class="col-md-6">
								<label>Email</label>
								<?php echo isset($ApplicationData['director_email']) ? $ApplicationData['director_email'] : ""; ?>
							</div>
						</div>

						<div class="greenbox">
							<h4>Authorized Details</h4>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Name of the authorized Signatory</label>
								<?php echo isset($ApplicationData['name_authority']) ? $ApplicationData['name_authority'] : ""; ?>
							</div>

							<div class="col-md-6">
								<label>Designation</label>
								<?php echo isset($ApplicationData['type_authority']) ? $ApplicationData['type_authority'] : ""; ?>
							</div>
							<div class="col-md-6">
								<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
								<?php echo isset($ApplicationData['authority_whatsapp']) ? $ApplicationData['authority_whatsapp'] : ""; ?>
							</div>
							<div class="col-md-6">
								<label>Mobile</label>
								<?php echo isset($ApplicationData['authority_mobile']) ? $ApplicationData['authority_mobile'] : ""; ?>
							</div>
							<div class="col-md-6">
								<label>Email</label>
								<?php echo isset($ApplicationData['authority_email']) ? $ApplicationData['authority_email'] : ""; ?>
							</div>
						</div>

						<div class="greenbox">
							<h4>Attached Document</h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label>Pan Card</label>
									<?php if (!empty($ApplicationData['pan_card'])) { ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['pan_card'])) { ?>

											<?php
											if (isset($ApplicationData['application_id']))
												echo "<strong><a target=\"_PANCARD\" href=\"" . URL_HTTP . 'app-docs/p_pan_card/' . encode($ApplicationData['application_id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											else
												echo "<strong><a target=\"_PANCARD\" href=\"" . URL_HTTP . 'app-docs/p_pan_card/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											?>

										<?php } ?>
									<?php } ?>
								</div>
								<div class="col-md-4">
									<label>Enclose self-certified copy</label>
									<?php if (!empty($ApplicationData['registration_document'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['registration_document'])) { ?>
										<?php
											if (isset($ApplicationData['application_id']))
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_registration_document/' . encode($ApplicationData['application_id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											else
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_registration_document/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
								<div class="col-md-4">
									<label>Undertaking form</label>
									<?php if (!empty($ApplicationData['upload_undertaking'])) :  ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['upload_undertaking'])) {
											if (isset($ApplicationData['application_id']))
												echo "<strong><a target=\"_RECENTBILL\" href=\"" . URL_HTTP . 'app-docs/p_upload_undertaking/' . encode($ApplicationData['application_id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											else
												echo "<strong><a target=\"_RECENTBILL\" href=\"" . URL_HTTP . 'app-docs/p_upload_undertaking/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>Copy of Board resolution authorizing person for signing all the documents related to proposed project</label>
									<?php if (!empty($ApplicationData['d_file_board'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['d_file_board'])) { ?>

										<?php
											if (isset($ApplicationData['application_id']))
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_d_file_board/' . encode($ApplicationData['application_id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											else
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_d_file_board/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<div class="greenbox">
							<h4>Technical Details</h4>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Type of Power Project</label>
									Solar Photovoltaic
								</div>
								<div class="col-md-6">
									<label>Type of SPP</label>
									<?php echo isset($ApplicationData['type_of_spp']) ? $typeOfSPP[$ApplicationData['type_of_spp']] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Type of Mounting System Used</label>
									<?php echo isset($ApplicationData['type_of_mounting_system']) ? $typeOfMountingSystem[$ApplicationData['type_of_mounting_system']] : ""; ?>
								</div>

							</div>

							<div class="row">
								<div class="col-md-6">
									<label>Details of Solar System</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<table border=1 class="td_simple" width="100%" style="text-align: left; margin-top: 20px; font-size: 13.5px;">
										<tr>
											<td colspan="6" style="text-align: center;font-weight:bold">Modules Details</td>
										</tr>
										<?php
										if (isset($moduleAdditionalData)) {
											echo '<tr style="text-align: center;font-weight:bold">';
											echo '<td>Make of SPV Modules</td>';
											echo '<td>Nos. of Modules</td>';
											echo '<td>Capacity of each SPV Module(in Wp)</td>';
											echo '<td>Total SPV Modules Capacity (in MW)</td>';
											echo '<td>Type of SPV Technologies</td>';
											echo '<td>Type of Solar Panel</td>';
											echo '</tr>';
											foreach ($moduleAdditionalData as $k => $v) {
												echo '<tr style="text-align: center;">';
												echo '<td>' . $v['mod_inv_make'] . '</td>';
												echo '<td>' . $v['nos_mod_inv'] . '</td>';
												echo '<td>' . $v['mod_inv_capacity'] . '</td>';
												echo '<td>' . $v['mod_inv_total_capacity'] . '</td>';
												echo '<td>' . $typeOfspv[$v['type_of_spv_technologies']] . '</td>';
												echo '<td>' . $typeOfSolarPanel[$v['type_of_solar_panel']] . '</td>';
												echo '</tr>';
											}
											echo '<tr style="text-align: center;font-weight:bold">';
											$noOfModules = isset($totalModulenos) ? $totalModulenos["nos_mod_inv"] : "";
											$capOfModules = isset($totalModulenos) ? $totalModulenos['mod_inv_total_capacity'] : "";
											echo '<td colspan="3">Total SPV Module(Nos.) : ' . $noOfModules . '</td>';
											echo '<td colspan="3">Total SPV Module capacity(MW) : ' . $capOfModules . '</td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
								<div class="col-md-12">
									<table border=1 class="td_simple" width="100%" style="text-align: left; margin-top: 20px; font-size: 13.5px">
										<tr>
											<td colspan="5" style="text-align: center;font-weight:bold">Inverter Details</td>
										</tr>
										<?php
										if (isset($inverteAdditionalData)) {
											echo '<tr style="text-align: center;font-weight:bold">';
											echo '<td>Make of Inverter</td>';
											echo '<td>Nos. of Inverter</td>';
											echo '<td>Capacity of each Inverter (in kW)</td>';
											echo '<td>Total Inverter Capacity (in MW)</td>';
											echo '<td>Type of Inverter Used</td>';
											echo '</tr>';
											foreach ($inverteAdditionalData as $ik => $iv) {
												echo '<tr style="text-align: center;">';
												echo '<td>' . $iv['mod_inv_make'] . '</td>';
												echo '<td>' . $iv['nos_mod_inv'] . '</td>';
												echo '<td>' . $iv['mod_inv_capacity'] . '</td>';
												echo '<td>' . $iv['mod_inv_total_capacity'] . '</td>';
												echo '<td>' . $typeOfInverterUsed[$iv['type_of_inverter_used']] . '</td>';
												echo '</tr>';
											}
											echo '<tr style="text-align: center;font-weight:bold">';
											$noOfInv = isset($totalInverternos) ? $totalInverternos["nos_mod_inv"] : "";
											$capOfInv = isset($totalInverternos) ? $totalInverternos['mod_inv_total_capacity'] : "";
											echo '<td colspan="2">Total SPV Module(Nos.) : ' . $noOfInv . '</td>';
											echo '<td colspan="3">Total SPV Module capacity(MW) : ' . $capOfInv . '</td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
							<div class="row" style="margin-top: 20px;">
								<div class="col-md-6">
									<label>Type of Consumer</label>
									<?php echo isset($ApplicationData['type_of_consumer']) ? $typeOfConsumer[$ApplicationData['type_of_consumer']] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Type of MSME Manufacturing Enterprise</label>
									<?php echo isset($ApplicationData['type_of_msme']) ? $typeOfInverterUsed[$ApplicationData['type_of_msme']] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Sactioned Load / Contract Demand</label>
									<?php echo isset($ApplicationData['sanctioned_load']) ? $ApplicationData['sanctioned_load'] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Consumer No.</label>
									<?php echo isset($ApplicationData['consumer_no']) ? $ApplicationData['consumer_no'] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Solar plant is to be installed</label>
									<?php echo isset($ApplicationData['name_of_discome_plant_installed']) ? $discom_arr[$ApplicationData['name_of_discome_plant_installed']] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Where power to be wheeled</label>
									<?php echo isset($ApplicationData['name_of_discome_power_wheeled']) ? $discom_arr[$ApplicationData['name_of_discome_power_wheeled']] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Expected Annual output of energy (kWh)</label>
									<?php echo isset($ApplicationData['expected_annual_output']) ? $ApplicationData['expected_annual_output'] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Proposed date of Commissioning</label>
									<?php echo isset($ApplicationData['proposed_date_of_commm']) ? $ApplicationData['proposed_date_of_commm'] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Approximate Project Cost</label>
									<?php echo isset($ApplicationData['app_project_cost']) ? 'Rs. ' . $ApplicationData['app_project_cost'] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Name of EPC Contractor/Developer</label>
									<?php echo isset($ApplicationData['epc_constractor_nm']) ? 'Rs. ' . $ApplicationData['epc_constractor_nm'] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>EPC Contractor Address</label>
									<?php echo isset($ApplicationData['epc_constractor_add']) ? 'Rs. ' . $ApplicationData['epc_constractor_add'] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Contact Person of EPC Contractor/Developer</label>
									<?php echo isset($ApplicationData['epc_constractor_con_per']) ? 'Rs. ' . $ApplicationData['epc_constractor_con_per'] : ""; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>EPC Contractor E-mail</label>
									<?php echo isset($ApplicationData['epc_constractor_email']) ? 'Rs. ' . $ApplicationData['epc_constractor_email'] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>EPC Contractor Mobile</label>
									<?php echo isset($ApplicationData['epc_constractor_mobile']) ? 'Rs. ' . $ApplicationData['epc_constractor_mobile'] : ""; ?>
								</div>
							</div>
							<!-- Sale To Discom -->
							<div class="row">
								<div class="col-md-6">
									<label>End use of electricity</label>
									<?php echo isset($ApplicationData['end_use_of_electricity']) ? $endUseOfElectricity[$ApplicationData['end_use_of_electricity']] : ""; ?>
								</div>
								<?php if (isset($ApplicationData['end_use_of_electricity']) &&  $ApplicationData['end_use_of_electricity'] == 1) { ?>

									<div class="col-md-6">
										<label>LOI/PPA with GUVNL/DISCOM to purchase power from proposed project </label>
										<?php if (!empty($ApplicationData['upload_sale_to_discom'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['upload_sale_to_discom'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_sale_to_discom/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>

								<?php } ?>
							</div>

							<div class="row">
								<div class="col-md-6">
									<label>NOC for the project site from concern DISCOM</label>
									<?php if (!empty($ApplicationData['no_due_1'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['no_due_1'])) { ?>
										<?php
											echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_no_due_1/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
								<div class="col-md-6">
									<label>NOC for the project site and developer from concern DISCOM</label>
									<?php if (!empty($ApplicationData['no_due_2'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['no_due_2'])) { ?>
										<?php
											echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_no_due_2/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>CA certificate regarding the ownership of the proposed Solar Power Project</label>
									<?php if (!empty($ApplicationData['upload_proof_of_ownership_1'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['upload_proof_of_ownership_1'])) { ?>
										<?php
											echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_proof_of_ownership_1/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
								<div class="col-md-6">
									<label>CA certi certifying the equity holding of the proposed Solar Power Project</label>
									<?php if (!empty($ApplicationData['upload_proof_of_ownership_2'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['upload_proof_of_ownership_2'])) { ?>
										<?php
											echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_proof_of_ownership_2/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Upload Undertaking For Newness Form</label>
									<?php if (!empty($ApplicationData['upload_undertaking_newness'])) : ?>
										<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['upload_undertaking_newness'])) { ?>
										<?php
											echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_undertaking_newness/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
										}
										?>
									<?php endif; ?>
								</div>								
							</div>
							

							<!-- Project for RPO -->
							<?php if ((isset($ApplicationData['end_use_of_electricity']) &&  ($ApplicationData['end_use_of_electricity'] == 2 || $ApplicationData['end_use_of_electricity'] == 3)) && ($ApplicationData['captive'] == 1 || $ApplicationData['third_party'] == 1)) { ?>
								<div class="greenbox">
									<h4>Project for RPO Compliance: <?php echo $ApplicationData['captive'] == 1 ? "Captive" : "Third Party Sale" ?></h4>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Whether beneficiary is an Obligated Entity covered under RPO obligation</label>
										<?php echo isset($ApplicationData['beneficiary_obligated_entity']) && $ApplicationData['beneficiary_obligated_entity'] == 1 ? 'YES' : 'NO'; ?>
									</div>

								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Documents of beneficiary in support of applicant being obligated entity for RPO compliance</label>
										<?php if (!empty($ApplicationData['doc_of_beneficiary'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['doc_of_beneficiary'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_doc_of_beneficiary/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<label>Copy of GERC Distibution License Certificate</label>
										<?php echo isset($ApplicationData['copy_of_gerc']) && $ApplicationData['copy_of_gerc'] == 1 ? 'YES' : 'NO'; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Upload Document of GERC Distibution License Certificate</label>
										<?php if (!empty($ApplicationData['doc_of_gerc_license'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['doc_of_gerc_license'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_doc_of_gerc_license/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>	
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Whether applicant has Captive Conventional Power Plant (CPP)</label>
										<?php echo isset($ApplicationData['captive_conv_power_plant']) && $ApplicationData['captive_conv_power_plant'] == 1 ? 'YES' : 'NO'; ?>
									</div>
								</div>

								<?php if (isset($ApplicationData['captive_conv_power_plant']) && $ApplicationData['captive_conv_power_plant'] == 1) { ?>
									<div class="row">
										<div class="col-md-6">
											<label>Capacity of CPP (MW)</label>
											<?php echo isset($ApplicationData['capacity_of_cpp']) ? $ApplicationData['capacity_of_cpp'] : ''; ?> MW
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Electricity duty of last 3 month</label>
											<?php if (!empty($ApplicationData['copy_of_conventional_electricity'])) : ?>
												<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['copy_of_conventional_electricity'])) { ?>
												<?php
													echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_copy_of_conventional_electricity/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
												}
												?>
											<?php endif; ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Any previous Solar Project put up for captive RPO</label>
											<?php echo isset($ApplicationData['prev_solar_project']) ? 'YES' : 'NO'; ?></td>
										</div>
									</div>
								<?php } ?>
								<div class="row">
									<div class="col-md-6">
										<label>Certificate of STOA/MTOA/LTOA by SLDC/GETCO</label>
										<?php echo isset($ApplicationData['certi_of_stoa']) && $ApplicationData['certi_of_stoa'] == 1 ? 'YES' : 'NO'; ?>
									</div>
									<?php if (isset($ApplicationData['certi_of_stoa']) && $ApplicationData['certi_of_stoa'] == 1) { ?>
										<div class="col-md-6">
											<label>Which Certificate of STOA/ MTOA/ LTOA issued by SLDC/GETCO</label>
											<?php echo isset($ApplicationData['certi_of_stoa_capacity']) ? $ApplicationData['certi_of_stoa_capacity'] : ''; ?> MW
										</div>
									<?php } ?>
								</div>

								<div class="row">
									<div class="col-md-6">
										<label>STOA/MTOA/LTOA?</label>
										<?php echo isset($ApplicationData['RE_generating_plant']) && $ApplicationData['RE_generating_plant'] == 1 ? 'YES' : 'NO'; ?>
									</div>
									<?php if (isset($ApplicationData['RE_generating_plant']) && $ApplicationData['RE_generating_plant'] == 1) { ?>
										<div class="col-md-6">
											<label>Notarized Stamp of STOA/MTOA/LTOA?</label>
											<?php if (!empty($ApplicationData['stamp_of_re_gen_plant'])) : ?>
												<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['stamp_of_re_gen_plant'])) { ?>
												<?php
													echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_stamp_of_re_gen_plant/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
												}
												?>
											<?php endif; ?>
										</div>
									<?php } ?>
								</div>
							<?php } ?>

							<!-- Third Party -->
							<?php if (isset($ApplicationData['end_use_of_electricity']) &&  $ApplicationData['end_use_of_electricity'] == 3 && ($ApplicationData['third_party'] == 1 || $ApplicationData['third_party'] == 2)) { ?>
								<div class="greenbox">
									<h4>Third Party Details</h4>
								</div>

								<div class="row">
									<div class="col-md-6">
										<label>Details of Third Party?</label>
										<?php isset($ApplicationData['details_of_third_party']) && $ApplicationData['details_of_third_party'] == 1 ? 'YES' : 'NO' ?>
									</div>
								</div>
								<?php if (isset($ApplicationData['details_of_third_party']) && $ApplicationData['details_of_third_party'] == 1) { ?>
									<div class="row">
										<div class="col-md-6">
											<label>Name</label>
											<?php echo isset($ApplicationData['third_party_name']) ? $ApplicationData['third_party_name'] : '' ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Address</label>
											<?php echo isset($ApplicationData['third_party_address']) ? $ApplicationData['third_party_address'] : '' ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Consumer No.</label>
											<?php echo isset($ApplicationData['third_party_consumer_no']) ? $ApplicationData['third_party_consumer_no'] : '' ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Contract Demand</label>
											<?php echo isset($ApplicationData['third_party_contract_demand']) ? $ApplicationData['third_party_contract_demand'] : '' ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Capacity of Existing Solar Power Plant (MW) if installed before this application</label>
											<?php echo isset($ApplicationData['third_party_capacity_existing_plant']) ? $ApplicationData['third_party_capacity_existing_plant'] : '' ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Electricity bill of Third Party Consumer</label>
											<?php if (!empty($ApplicationData['electricit_bill_of_third_party'])) : ?>
												<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['electricit_bill_of_third_party'])) { ?>
												<?php
													echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_electricit_bill_of_third_party/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
												}
												?>
											<?php endif; ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>In case of multiple third party consumer, enclosed the detailed annexure copy of the same</label>
											<?php if (!empty($ApplicationData['multi_third_party'])) : ?>
												<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['multi_third_party'])) { ?>
												<?php
													echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_multi_third_party/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
												}
												?>
											<?php endif; ?>
										</div>
									</div>
								<?php } ?>
							<?php } ?>


							<?php if (isset($ApplicationData['end_use_of_electricity']) && ($ApplicationData['end_use_of_electricity'] == 2 || $ApplicationData['end_use_of_electricity'] == 3) && ($ApplicationData['captive'] == 2 || $ApplicationData['third_party'] == 2)) { ?>
								<div class="greenbox">
									<h4>Project with REC Mechanism: <?php echo $ApplicationData['captive'] == 1 ? "Captive" : "Third Party Sale" ?></h4>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Physical copy of application done on online REC registration website</label>
										<?php echo isset($ApplicationData['phy_copy_of_rec_reg_web']) && $ApplicationData['phy_copy_of_rec_reg_web'] == 1 ? 'YES' : 'NO' ?>
									</div>
								</div>
								<?php if (isset($ApplicationData['phy_copy_of_rec_reg_web']) && $ApplicationData['phy_copy_of_rec_reg_web'] == 1) { ?>
									<div class="row">
										<div class="col-md-6">
											<label>REC accrediation Certificate</label>
											<?php if (!empty($ApplicationData['rec_accrediation_cer'])) : ?>
												<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['rec_accrediation_cer'])) { ?>
												<?php
													echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_rec_accrediation_cer/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
												}
												?>
											<?php endif; ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Copy of receipt for application done on online REC registration website?</label>
											<?php echo isset($ApplicationData['receipt_copy_of_rec_reg_web']) && $ApplicationData['receipt_copy_of_rec_reg_web'] == 1 ? 'YES' : 'NO' ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Power Evacuation Arrangement permission letter from the host State Transmission Utility or the concerned Distibution Licence, as the case may be?</label>
											<?php echo isset($ApplicationData['power_eva_arra_per']) && $ApplicationData['power_eva_arra_per'] == 1 ? 'YES' : 'NO' ?>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
							<div class="greenbox">
								<h4>Land Details</h4>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">

									<?php if (isset($lanDetails)) { ?>
										<?php foreach ($lanDetails as $lk => $lv) { ?>
											<table width="100%" border=1 style="text-align: left; font-size: 13.5px;border-top: 2px solid;border-bottom: 2px solid;border-right: 2px solid;border-left: 2px solid;">

												<tr style="border:1px solid #777777">
													<td width="4%" style="border-right: none; text-align: center;"><?php echo $lk + 1 ?></td>
													<td width="18%" style="border-right: none; padding-left: 5px;">Land Category :</td>
													<td width="30%" style="border-left: none; border-right: none;"><?php echo isset($lv['land_category']) ? $landCategory[$lv['land_category']] : '' ?> </td>
													<td width="18%" style="border-right: none; padding-left: 5px;">Plot/Survey No. :</td>
													<td width="30%" style="border-left: none;"> <?php echo isset($lv['land_plot_servey_no']) ? $lv['land_plot_servey_no'] : '' ?> </td>

												</tr>
												<tr style="border-bottom:1px solid #777777">
													<td colspan=5 style="padding:0px !important">
														<table width="100%">
															<tr>
																<td width="15%" style="border-bottom: 1px solid #777777; padding-left: 5px;">Taluka/Village :</td>
																<td width="19%" style="border-bottom: 1px solid #777777; border-right: 1px solid ;"><?php echo isset($lv['land_taluka']) ? $lv['land_taluka'] : '' ?><?php echo isset($lv['land_city']) ? $lv['land_city'] : '' ?> </td>
																<td width="13%" style="border-bottom: 1px solid #777777; padding-left: 5px;">District :</td>
																<td width="20%" style="border-bottom: 1px solid #777777; border-right: 1px solid ;"><?php echo isset($lv['land_district']) ? '' : '' ?></td>
																<td width="13%" style="border-bottom: 1px solid #777777; padding-left: 5px;">State :</td>
																<td width="20%" style="border-bottom: 1px solid #777777;">Gujarat</td>
															</tr>
															<tr>
																<td style="padding-left: 5px;">Latitude :</td>
																<td style="border-right: 1px solid #777777"><?php echo isset($lv['land_latitude']) ? $lv['land_latitude'] : '' ?></td>
																<td style="padding-left: 5px;">Longitude :</td>
																<td style="border-right: 1px solid #777777"><?php echo isset($lv['land_longitude']) ? $lv['land_longitude'] : '' ?></td>
																<td style="padding-left: 5px;">Area of land :</td>
																<td><?php echo isset($lv['area_of_land']) ? $lv['area_of_land'] : '' ?></td>
															</tr>

														</table>
													</td>
												</tr>
												<tr>
													<td colspan=5 style="padding:0px !important">
														<table>
															<tr>
																<td width="20%" style="border:none;">Deed of land :</td>
																<td width="32%" style="border:none;"><?php echo isset($lv['deed_of_land']) ? $deedOfLand[$lv['deed_of_land']] : '' ?></td>
																<td width="30%" style="border:none; border-left: 1px solid black;">Upload Land Document :</td>
																<td width="18%" style="border:none;" colspan="3">Yes / No
																</td>
															</tr>
														</table>
													</td>
												</tr>

											</table><br>
										<?php }  ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>