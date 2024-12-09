<style>
	.greenhead {
		background-color: #4cc972 !important;
		color: #fff;
		border-top: 1px solid;
	}

	.table thead tr th {
		font-size: 12px;
	}
</style>
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
						<div class="row">
							<div class="col-md-6">
								<label>Geo Cordinate Details</label>
							</div>
						</div>
						<?php if ($ApplicationGeoLoc) { ?>
							<div class="row">
								<div class="col-md-12 table-responsive">
									<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead">
										<thead class="thead-dark">											
											<th scope="col" width="10%">X-Cordinate</th>
											<th scope="col" width="10%">Y-Cordinate</th>
											<th scope="col" width="20%">WTG Make</th>
											<th scope="col" width="20%">WTG Model</th>
											<th scope="col" width="10%">Capacity</th>
											<th scope="col" width="10%">Rotor Dia. in meters</th>
											<th scope="col" width="10%">Hub Height in meters</th>
										</thead>

										<tbody>
											<?php foreach ($ApplicationGeoLoc as $k => $v) { ?>
												<tr>
													
													<td class="valignTop"> <?php echo $v['x_cordinate'] ?></td>
													<td class="valignTop"> <?php echo $v['y_cordinate'] ?></td>
													<td class="valignTop"> <?php echo $v['manufacturer_master']['name'] ?></td>
													<td class="valignTop"> <?php echo $v['wtg_model'] ?></td>
													<td class="valignTop capacity"> <?php echo $v['wtg_capacity'] ?></td>
													<td class="valignTop"> <?php echo $v['wtg_rotor_dimension'] ?></td>
													<td class="valignTop"> <?php echo $v['wtg_hub_height'] ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>

						<?php } ?>

						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Grid Connectivity</label>
									<?php echo isset($ApplicationData['grid_connectivity']) ? $gridLevel[$ApplicationData['grid_connectivity']] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Proposed GETCO/PGCIL Substation</label>
									<?php echo isset($ApplicationData['getco_substation']) ? $ApplicationData['getco_substation'] : ""; ?>
								</div>

							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Power Injection Level</label>
									<?php echo isset($ApplicationData['injection_level']) ? $injectionLevel[$ApplicationData['injection_level']] : ""; ?>
								</div>
								<div class="col-md-6">
									<label>Annual output of energy(kWh)</label>
									<?php echo isset($ApplicationData['project_energy']) ? $ApplicationData['project_energy'] : ""; ?>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<label>End use of electricity</label>
									<?php if (isset($ApplicationData['grid_connectivity'])) {
										if($ApplicationData['grid_connectivity'] == 1){
											echo $ApplicationData['end_stu']  ? $EndSTU[$ApplicationData['end_stu']] : '';
										}else{
											echo $ApplicationData['end_ctu']  ? $EndCTU[$ApplicationData['end_ctu']] : '';
										}
									} ?>
								</div>
								<?php if (isset($ApplicationData['end_stu']) && ($ApplicationData['end_stu'] == 2 || $ApplicationData['end_stu']==3)) { ?>
								<div class="col-md-6">
									<label>RE Attributes</label>
									<?php
										if($ApplicationData['end_stu'] == 2){
											echo $captive[$ApplicationData['captive']];
										}
										if($ApplicationData['end_stu'] == 3){
											echo $third_party[$ApplicationData['third_party']];
										}
									?>
								</div>
								<?php } ?>
							</div>
						

							<?php if (isset($ApplicationData['end_stu']) && $ApplicationData['end_stu']== 2) { ?>
								<div class="row">
									<div class="col-md-6">
										<label>Equity Share</label>
										<?php echo isset($ApplicationData['equity_share']) ? $equityShare[$ApplicationData['equity_share']] : ""; ?>
									</div>
									<?php if($ApplicationData['equity_share']==2){ ?>
									<div class="col-md-6">
										<label>Type of CGP</label>
										<?php echo isset($ApplicationData['cgp']) ? $cgp[$ApplicationData['cgp']] : ""; ?>
									</div>
									<?php } ?>								
								</div>
								<?php if($ApplicationData['equity_share']==2){ ?>
								<div class="row">

									<!-- share 1 -->
									<?php if($ApplicationData['cgp']==1){ ?>								
									<div class="col-md-6">
										<label>A certified copy of Share Register and Share Certificate</label>
										<?php if (!empty($cgpFiles['captive_share_register'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['captive_share_register'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_captive_share_register/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Certificate issued by the Chartered Accountant/CS</label>
										<?php if (!empty($cgpFiles['captive_ca_cs_certi'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['captive_ca_cs_certi'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_captive_ca_cs_certi/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Balance sheet of the Company/Individual if any</label>
										<?php if (!empty($cgpFiles['captive_balance_sheet'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['captive_balance_sheet'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_captive_balance_sheet/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Annual Audited Account </label>
										<?php if (!empty($cgpFiles['captive_annual_audit'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['captive_annual_audit'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_captive_annual_audit/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>


									<!-- share 2 -->
									<?php if($ApplicationData['cgp']==2){ ?>								
									<div class="col-md-6">
										<label>Partnership Deed</label>
										<?php if (!empty($cgpFiles['partnership_deed'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['partnership_deed'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_partnership_deed/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Share Holding</label>
										<?php if (!empty($cgpFiles['partnership_share_holding'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['partnership_share_holding'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_partnership_share_holding/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Return filed before the Registrar of Firms by Partnership firm to whomsoever applicable on annual basis as per provisions of the relevant Act</label>
										<?php if (!empty($cgpFiles['partnership_return_filed'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['partnership_return_filed'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_partnership_return_filed/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

									<!-- share 3 -->
									<?php if($ApplicationData['cgp']==3){ ?>								
									<div class="col-md-6">
										<label>A certified copy of Share Register</label>
										<?php if (!empty($cgpFiles['limited_share_register'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['limited_share_register'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_limited_share_register/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Share   Certificate</label>
										<?php if (!empty($cgpFiles['limited_share_certi'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['limited_share_certi'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_limited_share_certi/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Certificate from the Company Secretary and return filed before the Registrar of Companies,including Form MGT-7 or 7-A whichever is applicable and Form- AoC-4 to be furnished on annual basis as per provisions of the relevant Act.</label>
										<?php if (!empty($cgpFiles['limited_company_secretary_certi'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['limited_company_secretary_certi'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_limited_company_secretary_certi/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

									<!-- share 4 -->
									<?php if($ApplicationData['cgp']==4){ ?>								
									<div class="col-md-6">
										<label>A certified copy of returns filed before the Registrar of Companies, including Form MGT-7 or 7-A whichever is applicable and Form- AoC-4, 
										filed before Registrar of Firm or Registrar of Society on annual basis as per provisions of the relevant Act</label>
										<?php if (!empty($cgpFiles['association_certified_return_filed'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['association_certified_return_filed'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_association_certified_return_filed/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Share Register	showing equity share holding with voting rights of the members/shareholders of the Association of 					-  association_share_register
										Persons in the Captive Generating Plant</label>
										<?php if (!empty($cgpFiles['association_share_register'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['association_share_register'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_association_share_register/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>A certificate from a registered Chartered Accountant, along with Audited Annual Account and Balance Sheet</label>
										<?php if (!empty($cgpFiles['association_certi_of_ca'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['association_certi_of_ca'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_association_certi_of_ca/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Certificate	from Company Secretary</label>
										<?php if (!empty($cgpFiles['certi_from_company_secretary'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['certi_from_company_secretary'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_certi_from_company_secretary/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

									<!-- share 5 -->
									<?php if($ApplicationData['cgp']==5){ ?>								
									<div class="col-md-6">
										<label>A certificate from the District Registrar of Cooperative Society</label>
										<?php if (!empty($cgpFiles['cooperative_certi_from_district_registrar'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['cooperative_certi_from_district_registrar'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_cooperative_certi_from_district_registrar/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>copy of Share Register of Co-Operative Society showing shareholding of respective shareholders(members) with voting rights for respective financial year</label>
										<?php if (!empty($cgpFiles['cooperative_share_register'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['cooperative_share_register'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_cooperative_share_register/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

									<!-- share 6 -->
									<?php if($ApplicationData['cgp']==6){ ?>								
									<div class="col-md-6">
										<label>A certified copy of return filed before the Registrar of Companies, including Form MGT-7 or 7-A whichever is applicable and Form-AoC-4 to be 
										furnished on annual basis as per provisions of the relevant Act</label>
										<?php if (!empty($cgpFiles['spv_company_return_file'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['spv_company_return_file'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_spv_company_return_file/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Certified copy of Share Register showing equity holding with voting right of the members of the SPV in the Captive 
										Generating Plant	as	share holder(s)</label>
										<?php if (!empty($cgpFiles['spv_company_certi_of_share_register'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['spv_company_certi_of_share_register'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_spv_company_certi_of_share_register/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Memorandum of Associations</label>
										<?php if (!empty($cgpFiles['spv_company_memorandum'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['spv_company_memorandum'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_spv_company_memorandum/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Articles of Association and a certificate from a registered Chartered Accountant</label>
										<?php if (!empty($cgpFiles['spv_company_articles_of_associate'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['spv_company_articles_of_associate'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_spv_company_articles_of_associate/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Company Secretary</label>
										<?php if (!empty($cgpFiles['spv_company_company_secretary'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['spv_company_company_secretary'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_spv_company_company_secretary/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

									<!-- share 7 -->
									<?php if($ApplicationData['cgp']==7){ ?>								
									<div class="col-md-6">
										<label>Annual Balance Sheet (ii) Form MGT7</label>
										<?php if (!empty($cgpFiles['cgp_holding_annual_balance_sheet'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['cgp_holding_annual_balance_sheet'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_cgp_holding_annual_balance_sheet/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Account of Company(Form AOC- 4)filed under Companies Act and Rules by Holding Company and Subsidiary Company.</label>
										<?php if (!empty($cgpFiles['cgp_holding_acc_of_company'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['cgp_holding_acc_of_company'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_cgp_holding_acc_of_company/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

									<!-- share 8 -->
									<?php if($ApplicationData['cgp']==8){ ?>								
									<div class="col-md-6">
										<label>Annual Balance Sheet (ii) Form MGT7</label>
										<?php if (!empty($cgpFiles['cgp_annual_balance_sheet'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['cgp_annual_balance_sheet'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_cgp_annual_balance_sheet/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Account of Company (Form AOC- 4)filed under Companies Act and Rules by Subsidiary Company and Holding Company.</label>
										<?php if (!empty($cgpFiles['cgp_acc_of_company'])) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData['id'], $ApplicationData['cgp_acc_of_company'])) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_cgp_acc_of_company/' . encode($ApplicationData['id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<?php } ?>

								</div>							
								<?php } ?>	
							<?php } ?>

							

							<!-- Sale To Discom -->
							<div class="row">
								
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
							<?php if (isset($ApplicationData['end_use_of_electricity']) &&  $ApplicationData['end_use_of_electricity'] == 1) { ?>
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
							<?php } ?>

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

							<br>
							
							<?php if (isset($ApplicationData['app_trans_to_stu']) && $ApplicationData['app_trans_to_stu'] == 0) { ?>
								<div class="row">
									<div class="col-md-12">
										<label>Whether application is for RE Project Developer as a Transferor in STU?</label>
										<?php echo isset($ApplicationData['app_trans_to_stu']) ? ($ApplicationData['app_trans_to_stu'] == 1 ? "Yes" : "No") : '' ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Consumer No.</label>
										<?php echo isset($ApplicationData['consumer_no']) ? $ApplicationData['consumer_no'] : '' ?>
									</div>
									<div class="col-md-6">
										<label>Sanctioned Load / Contract Demand</label>
										<?php echo isset($ApplicationData['sanctioned_load']) ? $ApplicationData['sanctioned_load'] : '' ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Electricity Bill</label>
										<?php if (!empty($ApplicationData->copy_of_electricity_bill)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->copy_of_electricity_bill)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_copy_of_electricity_bill/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Name of concerned DISCOM</label>
										<?php echo isset($ApplicationData['discom']) ? $discom_arr[$ApplicationData['discom']] : '' ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Voltage level</label>
										<?php echo isset($ApplicationData['voltage_level']) ? $ApplicationData['voltage_level'] : '' ?>
									</div>
									<div class="col-md-6">
										<label>Approximate Project Cost (Rs. in lacs)</label>
										<?php echo isset($ApplicationData['project_estimated_cost']) ? $ApplicationData['project_estimated_cost'] : '' ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Do you intend to wheel the energy at multiple location?</label>
										<?php echo isset($ApplicationData['wheel_energy_multi_location']) ? ($ApplicationData['wheel_energy_multi_location'] == 1 ? "Yes" : "No") : '' ?>
									</div>
									<?php if (isset($ApplicationData['wheel_energy_multi_location']) && $ApplicationData['wheel_energy_multi_location'] == 1) { ?>
									<div class="col-md-6">
										<h6><b><center>Wheel The Energy At Multiple Locations</center></b></h6>
										<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead">
											<thead>
												<tr class="thead-dark">
													<th scope="col" width="60%">Discom</th>
													<th scope="col" width="40%">% of Energy to be wheeled</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($Energy_Data)) {
													foreach ($Energy_Data as $key => $value) { ?>
														<tr>
															<td><?php echo $discom_arr[$value['energy_discom']] ?></td>
															<td><?php echo $value['energy_per'] ?></td>

														</tr>
												<?php }
												} ?>
											</tbody>
										</table>
									</div>
									<?php } ?>
								</div>

							<?php } ?>

							<?php if (isset($lanDetails) && !empty($lanDetails)) { ?>
							<div class="greenbox">
								<h4>Land Details</h4>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">									
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
									
								</div>
							</div>
							<?php } ?>
							<div class="greenbox">
								<h4>Project Details</h4>
							</div><br>
							<div class="form-group">
								<h6><b>
										<center>Power Evacuation Pooling Substation Details</center>
									</b></h6>
								<div class="row">
									<div class="col-md-12">
										<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead">
											<thead>
												<tr class="thead-dark">
													<th scope="col" width="12%" rowspan="2">Name</th>
													<th scope="col" width="10%" rowspan="2">Distict</th>
													<th scope="col" width="10%" rowspan="2">Taluka</th>
													<th scope="col" width="10%" rowspan="2">Village</th>
													<th scope="col" width="8%" rowspan="2">Capacity</th>
													<th scope="col" width="7%" rowspan="2">Voltage level of the pooling Substation(kV)</th>
													<th scope="col" width="16%" colspan="2">Substation Capacity</th>
													<th scope="col" width="16%" colspan="2">Connected Load</th>

												</tr>
												<tr class="thead-dark greenhead">
													<th scope="col" width="8%" style="text-align: center;">MW</th>
													<th scope="col" width="8%" style="text-align: center;">MVA</th>
													<th scope="col" width="8%" style="text-align: center;">MW</th>
													<th scope="col" width="8%" style="text-align: center;">MVA</th>
												</tr>
											</thead>

											<tbody>
												<?php if (!empty($Wind_Pooling_Data)) {
													foreach ($Wind_Pooling_Data as $key => $value) { ?>
														<tr>
															<td><?php echo $value['name_of_pooling_sub'] ?></td>
															<td><?php echo $arrDistictData[$value['distict_of_pooling_sub']] ?></td>
															<td><?php echo $value['taluka_of_pooling_sub'] ?></td>
															<td><?php echo $value['village_of_pooling_sub'] ?></td>
															<td><?php echo $value['cap_of_pooling_sub'] ?></td>
															<td><?php echo $value['vol_of_pooling_sub'] ?></td>
															<td><?php echo $value['sub_mw_of_pooling_sub'] ?></td>
															<td><?php echo $value['sub_mva_of_pooling_sub'] ?></td>
															<td><?php echo $value['conn_mw_of_pooling_sub'] ?></td>
															<td><?php echo $value['conn_mva_of_pooling_sub'] ?></td>
														</tr>
												<?php }
												} ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>


							<div class="form-group">
								<h6><b>
										<center>Power Evacuation Getco Substation Details</center>
									</b></h6>
								<div class="row">
									<div class="col-md-12">
										<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead">
											<thead>
												<tr class="thead-dark">
													<th scope="col" width="12%" rowspan="2">Name</th>
													<th scope="col" width="12%" rowspan="2">Distict</th>
													<th scope="col" width="12%" rowspan="2">Taluka</th>
													<th scope="col" width="12%" rowspan="2">Village</th>
													<th scope="col" width="12%" rowspan="2">Capacity</th>
													<th scope="col" width="12%" rowspan="2">Voltage level of the GETCO/PGCIL Substation(kV)</th>
													<th scope="col" width="12%" colspan="2">GETCO/PGCIL SS Capacity</th>
													<th scope="col" width="12%">Approved injection capacity</th>

												</tr>
												<tr class="thead-dark greenhead">
													<th scope="col" width="8%" style="text-align: center;">MW</th>
													<th scope="col" width="8%" style="text-align: center;">MVA</th>
													<th scope="col" width="8%" style="text-align: center;">MW</th>
												</tr>
											</thead>

											<tbody>
												<?php if (!empty($Wind_Getco_Data)) {
													foreach ($Wind_Getco_Data as $key => $value) { ?>
														<tr>
															<td><?php echo $value['name_of_getco'] ?></td>
															<td><?php echo $arrDistictData[$value['distict_of_getco']] ?></td>
															<td><?php echo $value['taluka_of_getco'] ?></td>
															<td><?php echo $value['village_of_getco'] ?></td>
															<td><?php echo $value['cap_of_getco'] ?></td>
															<td><?php echo $value['vol_of_getco'] ?></td>
															<td><?php echo $value['sub_mw_of_getco'] ?></td>
															<td><?php echo $value['sub_mva_of_getco'] ?></td>
															<td><?php echo $value['conn_mw_of_getco'] ?></td>

														</tr>
												<?php }
												} ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label>Upload permission letter of the GETCO/PGCIL/DISCOM's with all annexures</label>
										<?php if (!empty($ApplicationData->permission_letter_of_getco)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->permission_letter_of_getco)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_permission_letter_of_getco/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<label>Reference No.</label>
										<?php echo isset($ApplicationData['permission_lett_ref_no']) ? $ApplicationData['permission_lett_ref_no'] : '' ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label>Date of Permission and Validity</label>
										<?php echo isset($ApplicationData['dt_of_per_validity']) ? date_format($ApplicationData['dt_of_per_validity'], 'd-m-Y') : '' ?>
									</div>
								</div>
							</div>

							<div class="greenbox">
								<h4>Other Documents</h4>
							</div><br>
							<div class="form-group">
								<div class="row">
									<div class="col-md-11">
										<label>Undertaking / Declaration on Rs. 300/- non judicial stamp paper regarding newness of the Wind Turbine Generators</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->undertaking_dec)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->undertaking_dec)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_undertaking_dec/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>Micro Siting Drawing</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->micro_sitting_drawing)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->micro_sitting_drawing)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_micro_sitting_drawing/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>Proof regarding ownership of the project</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->proof_of_ownership)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->proof_of_ownership)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_proof_of_ownership/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>The notarized copy of purchase contract/work order for sale of Wind Power Project</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->notarized_contract)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->notarized_contract)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_notarized_contract/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>CA certificate regarding the ownership of the proposed Wind Power Project</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->ca_certificate)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->ca_certificate)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_ca_certificate/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>Copy of invoice with GST or any other ownership proof for purchase of the proposed Wind Power Project</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->invoice_with_gst)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->invoice_with_gst)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_invoice_with_gst/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>Share Subscription & Share Hlding Agreement between RE Generator and Consumer Holder along with the CA certificate certifying the equity holding of the proposed Wind Power Project for captive use as per the definition no. 6 of clause no. 5 of Gujarat Renewable Energy Policy - 2023</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->share_subscription)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->share_subscription)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_share_subscription/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>If the proposed land is Pvt. Land Permission to use the above land for Bonafide Industrial Use/NA Permission/Deemed NA Order. If NA permission is unable to produce at this stage under taking to produce the same prior to the commissioning of the project</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->pvt_proposed_land)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->pvt_proposed_land)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_pvt_proposed_land/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>If end use of power generated from proposed Wind power project is for sale to DISCOM than, 'No Due Certificate' for the project site and developer from the concern DISCOM, within whose jurisdiction of the RE project is to be installed.</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->proj_sale_to_discom_no_due)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->proj_sale_to_discom_no_due)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_proj_sale_to_discom_no_due/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-11">
										<label>If end use of power generated from proposed Wind power project is for Captive Use & Third Part Sale than, 'No Due Certificate' for the project site and developer from the concern DISCOM, within whose jurisdiction of the RE project is to be installed.</label>
									</div>
									<div class="col-md-1">
										<?php if (!empty($ApplicationData->proj_captive_use_no_due)) : ?>
											<?php if ($Couchdb->documentExist($ApplicationData->id, $ApplicationData->proj_captive_use_no_due)) { ?>
											<?php
												echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_proj_captive_use_no_due/' . encode($ApplicationData->id) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></strong>";
											}
											?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>