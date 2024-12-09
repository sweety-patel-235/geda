<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php echo $this->Form->create($Applications, ['type' => 'file', 'name' => 'applicationform2', 'id' => 'applicationform2']); ?>
		<input type="hidden" name="tab_id" value="2" />
		<fieldset>
			<legend>Technical Details</legend>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">

						<label>Type of Power Project<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('type_of_power_project1', array('label' => false, 'class' => 'form-control', 'value' => 'Solar Photovoltaic', 'id' => 'type_of_power_project', 'disabled' => 'disabled')); ?>
						<input type="hidden" name="type_of_power_project" value="Solar Photovoltaic">
					</div>
					<?php
					$error_class = isset($ApplicationError['type_of_spp']['_empty']) && !empty($ApplicationError['type_of_spp']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-4 <?php echo $error_class; ?>">
						<label>Type of SPP<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_of_spp', $typeOfSPP, array('label' => false, 'class' => 'form-control', 'id' => 'type_of_spp', 'empty' => 'Select SPP')); ?>
						<?php if (isset($ApplicationError['type_of_spp']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['type_of_spp']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['type_of_mounting_system']['_empty']) && !empty($ApplicationError['type_of_mounting_system']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-4 <?php echo $error_class; ?>">
						<label>Type of mounting system used<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_of_mounting_system', $typeOfMountingSystem, array('label' => false, 'class' => 'form-control', 'id' => 'type_of_mounting_system', 'empty' => 'Select Mounting System')); ?>
						<?php if (isset($ApplicationError['type_of_mounting_system']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['type_of_mounting_system']['_empty']; ?></div>
						<?php } ?>
					</div>
				</div>
			</div>			
			<br>
			<div class="row">
				
				<div class="col-md-12">
					<div class="col-md-6">
						<h4>Details of SPV Modules</h4>
					</div>
					<div class="col-md-6 block" style=" text-align:right;">
						<input style="margin-right:14px;" class="btn green AddModuleRow" type="button" id="" value="+" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12 table-responsive">
						<table id="tbl_module_info" class="table table-striped table-bordered table-hover custom-greenhead">
							<thead class="thead-dark">
								<th scope="col" style="width:5%">Nos. of SPV Module</th>
								<th scope="col" style="width:15%">Capacity of each SPV Module (in Wp)</th>
								<th scope="col" style="width:15%">Total SPV Modules Capacity (in MW)</th>
								<th scope="col" style="width:15%">Make</th>
								<th scope="col" style="width:15%">SPV Technologies</th>
								<th scope="col" style="width:15%">Solar Panel</th>
								<th scope="col" style="width:5%">Action</th>
							</thead>
							<tbody>
								<?php if (!empty($Open_Access_Module_Data)) {
									foreach ($Open_Access_Module_Data as $key => $value) {
										$encode_application_id = encode($Applications->application_id);
								?>
										<tr>
											<td class="valignTop">
												<?php echo $this->Form->input('id_module[]', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_module', 'id' => 'id_module_' . $key]); ?>
												<?php echo $this->Form->input('nos_mod[]', array('label' => false, 'class' => 'form-control module_no_cls rfibox', 'placeholder' => 'Nos. of Module', 'onkeypress' => "return validateDecimal(event)", 'id' => "nos_mod_" . $key, 'onChange' => 'javascript:changeModuleRowCapacity(this)', 'value' => $value['nos_mod_inv'])); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('mod_capacity[]', array('label' => false, 'class' => 'form-control rfibox module_cap_cls', 'placeholder' => 'Capacity of each Module (in Wp)', 'onkeypress' => "return validateDecimal(event)", 'id' => "mod_capacity_" . $key, 'onChange' => 'javascript:changeModuleRowCapacity(this)', 'value' => $value['mod_inv_capacity'])); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('mod_total_capacity[]', array('label' => false, 'class' => 'form-control rfibox module_capacity_cls', 'placeholder' => 'Total SPV Modules Capacity (in MW)', 'id' => 'mod_total_capacity_' . $key, 'value' => $value['mod_inv_total_capacity'], 'readonly' => 'readonly')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('mod_make[]', $type_manufacturer_mod, array('label' => false, 'class' => 'rfibox module_make_cls', 'value' => $value['mod_inv_make'], 'empty' => '-Select -', 'id' => 'mod_make_' . $key)); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('type_of_spv[]', $typeOfspv, array('label' => false, 'class' => 'rfibox type_of_spv_cls', 'value' => $value['type_of_spv_technologies'], 'empty' => '-Select-', 'id' => 'type_of_spv_' . $key)); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('type_of_solar[]', $typeOfSolarPanel, array('label' => false, 'class' => 'rfibox type_of_solar_cls', 'value' => $value['type_of_solar_panel'], 'empty' => '-Select-', 'id' => 'type_of_solar_' . $key)); ?>
											</td>
											<td class="valignTop lastrow">
												<?php if ($key != 0) { ?>
													<?php if (isset($value['id']) && !empty($value['id'])) { ?>
														<input type="button" id="" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeModules('<?php echo $value['id']; ?>',<?php echo $value['capacity_type']; ?>,'<?php echo $encode_application_id ?>')" value="-" />
													<?php } else { ?>
														<input class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowWind(this)" value="-" />
													<?php } ?>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								<?php } else { ?>

									<tr>
										<td class="valignTop">
											<?php echo $this->Form->input('nos_mod[]', array('label' => false, 'class' => 'form-control module_no_cls rfibox', 'placeholder' => 'Nos. of Module', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'nos_mod_0', 'onChange' => 'javascript:changeModuleRowCapacity(this)')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->input('mod_capacity[]', array('label' => false, 'class' => 'form-control rfibox module_cap_cls', 'placeholder' => 'Capacity of each Module (in Wp)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'mod_capacity_0', 'onChange' => 'javascript:changeModuleRowCapacity(this)')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->input('mod_total_capacity[]', array('label' => false, 'class' => 'form-control module_capacity_cls rfibox', 'placeholder' => 'Total SPV Modules Capacity (in MW)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'mod_total_capacity_0', 'readonly' => 'readonly')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->select('mod_make[]', $type_manufacturer_mod, array('label' => false, 'class' => 'rfibox module_make_cls', 'empty' => '-Select -', 'id' => 'mod_make_0')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->select('type_of_spv[]', $typeOfspv, array('label' => false, 'class' => 'rfibox type_of_spv_cls', 'empty' => '-Select-', 'id' => 'type_of_spv_0')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->select('type_of_solar[]', $typeOfSolarPanel, array('label' => false, 'class' => 'rfibox type_of_solar_cls', 'empty' => '-Select-', 'id' => 'type_of_solar_0')); ?>
										</td>
										<td class="valignTop lastrow">&nbsp;</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group" style="margin-top: 10px;">
					<div class="col-md-2"><label> Total SPV Module (Nos.)</label></div>
					<div class="col-md-2 ">
						<?php echo $this->Form->input('nos_mod_inv', array('label' => false, 'class' => 'form-control', 'value' => isset($totalModulenos['nos_mod_inv']) ? $totalModulenos['nos_mod_inv'] : 0, 'readonly' => 'readonly', 'id' => 'add_mod_wtg')); ?>
					</div>
					<div class="col-md-2"><label> Total SPV Module capacity (MW)</label></div>
					<div class="col-md-2 ">
						<?php echo $this->Form->input('module_hybrid_capacity', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'add_mod_capacity', 'readonly' => 'readonly', 'value' => isset($totalModuleCapacity) ? $totalModuleCapacity : 0)); ?>
					</div>

					<div class="col-md-4">
						<?php
						$error_text = 'Module capacity can not be blank at least add one Module.';

						if (!empty($additional_data_error)) {
							echo '<div style="color: #a94442;">' . $error_text . '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				
				<div class="col-md-12">
					<div class="col-md-6">
						<h4>Details of Invertors</h4>
					</div>
					<div class="col-md-6 blockInv" style=" text-align:right;">
						<input style="margin-right:14px;" class="btn green AddInverterRow" type="button" id="addInverter" value="+" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12 table-responsive">
						<table id="tbl_inverter_info" class="table table-striped table-bordered table-hover custom-greenhead">
							<thead class="thead-dark">
								<th scope="col" style="width:15%">Nos. of Inverter</th>
								<th scope="col">Capacity of each Inverter (in kW)</th>
								<th scope="col">Total Inverter Capacity (in MW)</th>
								<th scope="col" style="width:25%">Make</th>
								<th scope="col" style="width:25%">Type of Inverter used</th>
								<th scope="col">Action</th>
							</thead>
							<tbody>
								<?php if (!empty($Open_Access_Inverter_Data)) {
									foreach ($Open_Access_Inverter_Data as $key => $value) {
										$encode_application_id = encode($Applications->application_id);
								?>
										<tr>
											<td class="valignTop">
												<?php echo $this->Form->input('id_inverter', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_inverter', 'id' => 'id_inverter_' . $key]); ?>
												<?php echo $this->Form->input('nos_inv[]', array('label' => false, 'class' => 'form-control inverter_no_cls rfibox', 'placeholder' => 'Nos. of Inverter', 'onkeypress' => "return validateDecimal(event)", 'id' => "nos_inv_" . $key, 'onChange' => 'javascript:changeInverterRowCapacity(this)', 'value' => $value['nos_mod_inv'])); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('inv_capacity[]', array('label' => false, 'class' => 'form-control rfibox inverter_cap_cls', 'placeholder' => 'Capacity of each Inverter (in kW)', 'onkeypress' => "return validateDecimal(event)", 'id' => "inv_capacity_" . $key, 'onChange' => 'javascript:changeInverterRowCapacity(this)', 'value' => $value['mod_inv_capacity'])); ?>

											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('inv_total_capacity[]', array('label' => false, 'class' => 'form-control rfibox inverter_capacity_cls', 'placeholder' => 'Total Inverter Capacity (in MW)', 'id' => 'inv_total_capacity_' . $key, 'value' => $value['mod_inv_total_capacity'], 'readonly' => 'readonly')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('inv_make[]', $type_manufacturer_inv, array('label' => false, 'class' => 'rfibox inverter_make_cls', 'value' => $value['mod_inv_make'], 'empty' => '-Select Inverter Make-', 'id' => 'inv_make_' . $key)); ?>

											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('type_of_inverter_used[]', $typeOfInverterUsed, array('label' => false, 'value' => $value['type_of_inverter_used'],'class' => 'rfibox type_of_inverter_used_cls', 'id' => 'type_of_inverter_used_' . $key, 'empty' => 'Select Inverter Used')); ?>
											</td>
											<td class="valignTop lastrow">
												<?php if ($key != 0) { ?>
													<?php if (isset($value['id']) && !empty($value['id'])) { ?>
														<input type="button" id="" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeModules('<?php echo $value['id']; ?>',<?php echo $value['capacity_type']; ?>,'<?php echo $encode_application_id ?>')" value="-" />

													<?php } else { ?>
														<input class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowWind(this)" value="-" />
													<?php } ?>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								<?php } else { ?>
									<tr>
										<td class="valignTop">
											<?php echo $this->Form->input('nos_inv[]', array('label' => false, 'class' => 'form-control inverter_no_cls rfibox', 'placeholder' => 'Nos. of Inverter', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'nos_inv_0', 'onChange' => 'javascript:changeInverterRowCapacity(this)')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->input('inv_capacity[]', array('label' => false, 'class' => 'form-control rfibox inverter_cap_cls', 'placeholder' => 'Capacity of each Inverter (in kW)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'inv_capacity_0', 'onChange' => 'javascript:changeInverterRowCapacity(this)')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->input('inv_total_capacity[]', array('label' => false, 'class' => 'form-control inverter_capacity_cls rfibox', 'placeholder' => 'Total Inverter Capacity (in MW)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'inv_total_capacity_0', 'readonly' => 'readonly')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->select('inv_make[]', $type_manufacturer_inv, array('label' => false, 'class' => 'rfibox inverter_make_cls', 'empty' => '-Select Inverter Make-', 'id' => 'inv_make_0')); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->select('type_of_inverter_used[]', $typeOfInverterUsed, array('label' => false, 'class' => 'rfibox type_of_inverter_used_cls', 'id' => 'type_of_inverter_used_0', 'empty' => 'Select Inverter Used')); ?>
										</td>
										<td class="valignTop lastrow">&nbsp;</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group" style="margin-top: 10px;">
					<div class="col-md-2"><label> Total Inverter (Nos.)</label></div>
					<div class="col-md-2 ">
						<?php echo $this->Form->input('nos_mod_inv', array('label' => false, 'class' => 'form-control', 'value' => isset($totalInverternos['nos_mod_inv']) ? $totalInverternos['nos_mod_inv'] : 0, 'readonly' => 'readonly', 'id' => 'add_inv_wtg')); ?>
					</div>
					<div class="col-md-2"><label> Total Inverter capacity (MW)</label></div>
					<div class="col-md-2 ">
						<?php echo $this->Form->input('inverter_hybrid_capacity', array('label' => false, 'class' => 'form-control', 'id' => 'add_inv_capacity', 'readonly' => 'readonly', 'value' => isset($totalInverterCapacity) ? $totalInverterCapacity : 0)); ?>
					</div>

					<div class="col-md-4">
						<?php
						$error_text = 'Inverter capacity can not be blank at least add one Module.';

						if (!empty($additional_data_error)) {
							echo '<div style="color: #a94442;">' . $error_text . '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<br>

			<div class="row">
				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['type_of_consumer']['_empty']) && !empty($ApplicationError['type_of_consumer']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>Type of Consumer<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_of_consumer', $typeOfConsumer, array('label' => false, 'class' => 'form-control', 'id' => 'type_of_consumer', 'empty' => 'Select Consumer')); ?>
						<?php if (isset($ApplicationError['type_of_consumer']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['type_of_consumer']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['type_of_msme']['_empty']) && !empty($ApplicationError['type_of_msme']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>Type of MSME<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_of_msme', $typeOfMsme, array('label' => false, 'class' => 'form-control', 'id' => 'type_of_msme', 'empty' => 'Select MSME')); ?>
						<?php if (isset($ApplicationError['type_of_msme']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['type_of_msme']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['end_use_of_electricity']['_empty']) && !empty($ApplicationError['end_use_of_electricity']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>End use of electricity<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('end_use_of_electricity', $endUseOfElectricity, array('label' => false, 'class' => 'form-control', 'id' => 'end_use_of_electricity', 'empty' => 'Select Use of Electricity')); ?>
						<?php if (isset($ApplicationError['end_use_of_electricity']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['end_use_of_electricity']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['captive']['_empty']) && !empty($ApplicationError['captive']['_empty']) ? 'has-error' : '';
					?>
					<div class="captive col-md-3 <?php echo $error_class; ?>">
						<label>RE Attributes<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('captive', $captive, array('label' => false, 'class' => 'form-control', 'id' => 'captive', 'empty' => 'Select')); ?>
						<?php if (isset($ApplicationError['captive']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['captive']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['third_party']['_empty']) && !empty($ApplicationError['third_party']['_empty']) ? 'has-error' : '';
					?>
					<div class="third-party col-md-3 <?php echo $error_class; ?>">
						<label>RE Attributes<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('third_party', $third_party, array('label' => false, 'class' => 'form-control', 'id' => 'third_party', 'empty' => 'Select')); ?>
						<?php if (isset($ApplicationError['third_party']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['third_party']['_empty']; ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group " style="margin-bottom: 15px;display: flex;width: 100%;flex-wrap: wrap;">
					<div class="col-md-4 sale-to-discom" style="margin-bottom:15px">
						<label>LOI/PPA with GUVNL/DISCOM<span class="mendatory_field">*</span><i data-content="Upload LOI/PPA with GUVNL/DISCOM to purchase power from proposed project" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_upload_sale_to_discom', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_sale_to_discom', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->upload_sale_to_discom)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_sale_to_discom)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_sale_to_discom/' . encode($Applications->id) . "\">View Sale To Discom</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='upload_sale_to_discom-file-errors'></div>
					</div>
					<div class="col-md-4 " style="margin-bottom:15px">
						<label>No Due Certificate<span class="mendatory_field">*</span> <i data-content="Upload 'No Due Certificate' for the project site from concern DISCOM in whose jurisdiction the Solar plant is to be installed" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_no_due_1', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'no_due_1', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->no_due_1)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->no_due_1)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_no_due_1/' . encode($Applications->id) . "\">View No Due Certificate Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='no_due_1-file-errors'></div>
					</div>
					<div class="col-md-4 " style="margin-bottom:15px">
						<label>No Due Certificate<span class="mendatory_field">*</span> <i data-content="Upload 'No Due Certificate' for the project site and developer from concern DISCOM in whose jurisdiction the Solar plant is to be installed" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_no_due_2', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'no_due_2', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->no_due_2)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->no_due_2)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_no_due_2/' . encode($Applications->id) . "\">View No Due Certificate Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='no_due_2-file-errors'></div>
					</div>
					<div class="col-md-4 " style="margin-bottom:15px">
						<label>CA certi of ownership<span class="mendatory_field">*</span> <i data-content="CA certificate regarding the ownership of the proposed Solar Power Project" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_upload_proof_of_ownership_1', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_proof_of_ownership_1', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->upload_proof_of_ownership_1)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_proof_of_ownership_1)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_proof_of_ownership_1/' . encode($Applications->id) . "\">View Proof of Ownership Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='upload_proof_of_ownership_1-file-errors'></div>
					</div>
					<div class="col-md-4 " style="margin-bottom:15px">
						<label>Agreement and Shareholding of Plant<span class="mendatory_field">*</span> <i data-content="Share Subscription and Share Holding Agreement between RE Generator and Consumer Holder along with the CA certificate certifying the equity holding of the proposed Solar Power Project for captive use as per the definition no. 6 of clause no.5 of Gujarat Renewable Energy Policy - 2023" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>

						<?php echo $this->Form->input('a_upload_proof_of_ownership_2', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_proof_of_ownership_2', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->upload_proof_of_ownership_2)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_proof_of_ownership_2)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_proof_of_ownership_2/' . encode($Applications->id) . "\">View Proof of Ownership Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='upload_proof_of_ownership_2-file-errors'></div>
					</div>

					<div class="col-md-4" style="margin-bottom:15px">
						<label>Upload Undertaking For Newness Form<span class="mendatory_field">*</span></label>
						
						<?php echo $this->Form->input('a_upload_undertaking_newness', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_undertaking_newness', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						
						<a href="/Undertaking_for_Newness.pdf" style="text-decoration: underline;"><strong>[Download Undertaking Format]</strong></a>
						<div id="a_upload_undertaking_newness-file-errors"></div>
						<?php if (!empty($Applications->upload_undertaking_newness)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_undertaking_newness)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_upload_undertaking_newness/' . encode($Applications->id) . "\">View Upload Undertaking Newness</a></strong>";
							}
							?>
						<?php endif; ?>
					</div>
				</div>
			</div>

			

			<!-- C. Project for RPO -->
			<legend class="captive-with-rpo">Project for RPO Compliance: </legend>
			<div class="row captive-with-rpo">
				<div class="form-group">
					<div class="col-md-4 ">
						<label>Whether beneficiary is an Obligated Entity covered under RPO obligation<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('beneficiary_obligated_entity', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [['value' => 1, 'text' => "Yes", "checked" => "checked"]], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
					<div class="col-md-4">
						<label>Document<span class="mendatory_field">*</span> <i data-content="Documents of beneficiary in support of applicant being obligated entity for RPO compliance" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_doc_of_beneficiary', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'doc_of_beneficiary', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->doc_of_beneficiary)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->doc_of_beneficiary)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_doc_of_beneficiary/' . encode($Applications->id) . "\">View Beneficiary Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='doc_of_beneficiary-file-errors'></div>
					</div>
					<div class="col-md-4">
						<label>Copy of GERC Distibution License Certificate<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('copy_of_gerc', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:copy_of_gerc_togg();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:copy_of_gerc_togg();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
				</div>
			</div>
			<div class="row captive-with-rpo">
				<div class="form-group" style="margin-bottom: 15px;display: flex;width: 100%;flex-wrap: wrap;">
					<div class="col-md-4 copy_of_gerc_div">
						<label>Document of GERC <span class="mendatory_field">*</span> <small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_doc_of_gerc_license', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'doc_of_gerc_license', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->doc_of_gerc_license)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->doc_of_gerc_license)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_doc_of_gerc_license/' . encode($Applications->id) . "\">View Copy of Conventional Electricity Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='doc_of_gerc_license-file-errors'></div>
					</div>
					<div class="col-md-4">
						<label>Whether applicant has CPP<span class="mendatory_field">*</span><i data-content="Whether applicant has Captive Conventional Power Plant(CPP)" class="fa fa-info-circle"></i></label>
						<?php
						echo $this->Form->input('captive_conv_power_plant', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:captive_power_plant();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:captive_power_plant();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
					<div class="col-md-4 captive-power-plant">
						<label>Capacity of CPP (MW)<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('capacity_of_cpp', array('label' => false, 'class' => 'form-control', 'id' => 'capacity_of_cpp', 'type' => 'text', 'onkeypress' => "return validateDecimal(event)")); ?>

					</div>
					<div class="col-md-4 captive-power-plant">
						<label>Document<span class="mendatory_field">*</span> <i data-content="Enclose the copy of paid conventional electricity duty of last 3 month" class="fa fa-info-circle"></i> <small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_copy_of_conventional_electricity', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'copy_of_conventional_electricity', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->copy_of_conventional_electricity)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->copy_of_conventional_electricity)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_copy_of_conventional_electricity/' . encode($Applications->id) . "\">View Copy of Conventional Electricity Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='copy_of_conventional_electricity-file-errors'></div>
					</div>


					<div class="col-md-4 captive-power-plant">
						<label>Any previous Solar Project put up for captive RPO<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('prev_solar_project', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [['value' => 1, 'text' => "Yes"], ['value' => 0, 'text' => "No"]], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
					<div class="col-md-4">
						<label>Certificate of STOA/MTOA/LTOA by SLDC/GETCO<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('certi_of_stoa', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:capacity_of_stoa();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:capacity_of_stoa();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
					<div class="col-md-4 capacity-of-stoa">
						<label>Capacity of STOA/MTOA/LTOA (MW)<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('certi_of_stoa_capacity', array('label' => false, 'class' => 'form-control', 'id' => 'certi_of_stoa_capacity', 'type' => 'text', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>

					<div class="col-md-4">
						<label>STOA/MTOA/LTOA? <span class="mendatory_field">*</span><i data-content="If STOA/MTOA/LTOA then Notarized Undertaking on Rs. 300 stamp, confirming the compliance for maintaining the eligibility as obligated entity by retaining STOA/MTOA/LTOA throughout the life span of RE generating plant" class="fa fa-info-circle"></i></label>
						<?php
						echo $this->Form->input('RE_generating_plant', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:stamp_re_gen_plant();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:stamp_re_gen_plant();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
					<div class="col-md-4 stamp-of-re-gen-plant-div">
						<label>Notarized Stamp<span class="mendatory_field">*</span><i data-content="Upload Rs.300 Notarized Undertaking Stamp" class="fa fa-info-circle"></i> <small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_stamp_of_re_gen_plant', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'stamp_of_re_gen_plant', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->stamp_of_re_gen_plant)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->stamp_of_re_gen_plant)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_stamp_of_re_gen_plant/' . encode($Applications->id) . "\">View Stamp of Re Generate Plant Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='stamp_of_re_gen_plant-file-errors'></div>
					</div>
				</div>
			</div>
			<!-- C. Project for RPO -->

			<!-- E. Project with REC Mechanism-->
			<legend class="project-with-RE-mechanism">Project with REC Mechanism: </legend>
			<div class="row project-with-RE-mechanism">
				<div class="form-group">
					<div class="col-md-4">
						<label>Physical copy of application done on online REC registration website?<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('phy_copy_of_rec_reg_web', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:phy_copy_of_rec_reg_web_toggle();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:phy_copy_of_rec_reg_web_toggle();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
					<div class="col-md-4 rec-mechanism-upload">
						<label>REC accrediation Certificate</label>
						<?php echo $this->Form->input('a_rec_accrediation_cer', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'rec_accrediation_cer', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->rec_accrediation_cer)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->rec_accrediation_cer)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_rec_accrediation_cer/' . encode($Applications->id) . "\">View RE Accredeation Certificate Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='rec_accrediation_cer-file-errors'></div>
					</div>

					<div class="col-md-4 rec-mechanism-upload">
						<label>Copy of receipt for application done on online REC registration website?<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('receipt_copy_of_rec_reg_web', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [['value' => 1, 'text' => "Yes"], ['value' => 0, 'text' => "No"]], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>

					<div class="col-md-8 rec-mechanism">
						<span style="color:#307fe2">
							Note:<br>
							As you are not an obligant entity, kindly select other option than REC
						</span>
					</div>
				</div>
			</div>
			<div class="row project-with-RE-mechanism">
				<div class="form-group">
					<div class="col-md-4 rec-mechanism-upload">
						<label>Power Evacuation Arrangement permission letter from the host State Transmission Utility or the concerned Distibution Licence, as the case may be?<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('power_eva_arra_per', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [['value' => 1, 'text' => "Yes"], ['value' => 0, 'text' => "No"]], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
				</div>
			</div>

			<!-- D. Project for Third Party Sale OR Third Party Sale & RPO compliance to Third Party OR Third Party Sale with REC Mechanism-->
			<legend class="project-for-third-party">Project for Third Party Sale with REC Mechanism(Details of Third Party beneficiary company)</legend>
			<div class="row project-for-third-party">
				<div class="form-group">
					<div class="col-md-4">
						<label>Details of Third Party?<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('details_of_third_party', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:toggel_third_party_detail();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:toggel_third_party_detail();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
				</div>
			</div>
			<div class="row project-for-third-party ">
				<div class="form-group third-party-details">
					<div class="col-md-4">
						<label>Name<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('third_party_name', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_name')); ?>
					</div>
					<div class="col-md-8">
						<label>Address<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('third_party_address', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_address')); ?>
					</div>
				</div>
			</div>
			<div class="row project-for-third-party ">
				<div class="form-group third-party-details">
					<div class="col-md-4">
						<label>Consumer No.</label>
						<?php echo $this->Form->input('third_party_consumer_no', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_consumer_no', 'onkeypress' => "return validateNumber(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Contract Demand</label>
						<?php echo $this->Form->input('third_party_contract_demand', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_contract_demand', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Capacity of Existing Solar Plant(MW)</label>
						<?php echo $this->Form->input('third_party_capacity_existing_plant', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_capacity_existing_plant')); ?>
					</div>
				</div>
			</div>
			<div class="row project-for-third-party ">
				<div class="form-group third-party-details">
					<div class="col-md-4">
						<label>Electricity bill of Third Party Consumer<span class="mendatory_field">*</span> <small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_electricit_bill_of_third_party', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'electricit_bill_of_third_party', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->electricit_bill_of_third_party)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->electricit_bill_of_third_party)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_electricit_bill_of_third_party/' . encode($Applications->id) . "\">View Electricity Bill of Third Party Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='electricit_bill_of_third_party-file-errors'></div>
					</div>
					<div class="col-md-4">
						<label>In case of multiple third party consumer, upload the detail copy of same <small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_multi_third_party', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'multi_third_party', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->multi_third_party)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->multi_third_party)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_multi_third_party/' . encode($Applications->id) . "\">View Multi Third Party Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='multi_third_party-file-errors'></div>
					</div>
				</div>
			</div>

			<legend></legend>
			<br>
			<div class="row">
				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['name_of_discome_plant_installed']['_empty']) && !empty($ApplicationError['name_of_discome_plant_installed']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-4 <?php echo $error_class; ?>">
						<label>Name of DISCOME, Solar plant is to be installed<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('name_of_discome_plant_installed', $discom_arr, array('label' => false, 'class' => 'form-control', 'id' => 'name_of_discome_plant_installed', 'empty' => 'Select DisCom')); ?>
						<?php if (isset($ApplicationError['name_of_discome_plant_installed']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['name_of_discome_plant_installed']['_empty']; ?></div>
						<?php } ?>
					</div>
					
					<div class="col-md-4">
						<label>Consumer No. (e.g 123456,654321)</label>
						<?php echo $this->Form->input('consumer_no', array('label' => false, 'class' => 'form-control', 'id' => 'consumer_no', 'type' => 'text', 'onkeypress' => "return validateCommaSepratedNumber(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Sanctioned Load / Contract Demand (e.g 1.2,6.5)</label>
						<?php echo $this->Form->input('sanctioned_load', array('label' => false, 'class' => 'form-control', 'id' => 'sanctioned_load', 'onkeypress' => "return validateCommaSepratedDecimal(event)")); ?>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>Existing Solar Power Plant(MW) (e.g 2.53,2.36)</label>
						<?php echo $this->Form->input('existing_solar_plan', array('label' => false, 'class' => 'form-control', 'id' => 'existing_solar_plan','onkeypress' => "return validateCommaSepratedDecimal(event)")); ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['name_of_discome_power_wheeled']['_empty']) && !empty($ApplicationError['name_of_discome_power_wheeled']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-4 <?php echo $error_class; ?>">
						<label>Name of DISCOME, Where power to be wheeled<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('name_of_discome_power_wheeled', $discom_arr, array('label' => false, 'class' => 'form-control', 'id' => 'name_of_discome_power_wheeled', 'empty' => 'Select DisCom')); ?>
						<?php if (isset($ApplicationError['name_of_discome_power_wheeled']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['name_of_discome_power_wheeled']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label>Proposed GETCO Substation name<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('getco_substation_name', array('label' => false, 'class' => 'form-control', 'id' => 'getco_substation_name')); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['getco_voltage_level']['_empty']) && !empty($ApplicationError['getco_voltage_level']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-4 <?php echo $error_class; ?>">
						<label>Proposed GETCO Voltage level<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('getco_voltage_level', $getcoVoltageLevel, array('label' => false, 'class' => 'form-control', 'id' => 'getco_voltage_level', 'empty' => 'Select Voltage Level')); ?>
						<?php if (isset($ApplicationError['getco_voltage_level']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['getco_voltage_level']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label>Expected Annual output of energy (kWh)<span class="mendatory_field">*</span><i data-content="Expected Annual output of energy from the proposed project in kWh" class="fa fa-info-circle"></i></label>
						<?php echo $this->Form->input('expected_annual_output', array('label' => false, 'class' => 'form-control', 'id' => 'expected_annual_output', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Proposed date of Commissioning<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('proposed_date_of_commm', array('type' => 'text', 'label' => false, 'class' => 'form-control datepicker', 'id' => 'proposed_date_of_commm')); ?>
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>Approximate Project Cost<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('app_project_cost', array('label' => false, 'class' => 'form-control', 'id' => 'app_project_cost', 'onkeypress' => "return validateNumber(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Name of EPC Contractor/Developer<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('epc_constractor_nm', array('label' => false, 'class' => 'form-control', 'id' => 'epc_constractor_nm')); ?>
					</div>
					<div class="col-md-4">
						<label>EPC Contractor Address<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('epc_constractor_add', array('label' => false, 'class' => 'form-control', 'id' => 'epc_constractor_add')); ?>
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>Contact Person of EPC Contractor/Developer<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('epc_constractor_con_per', array('label' => false, 'class' => 'form-control', 'id' => 'epc_constractor_con_per')); ?>
					</div>
					<div class="col-md-4">
						<label>EPC Contractor E-mail<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('epc_constractor_email', array('label' => false, 'class' => 'form-control', 'id' => 'epc_constractor_email')); ?>
					</div>
					<div class="col-md-4">
						<label>EPC Contractor Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('epc_constractor_mobile', array('label' => false, 'class' => 'form-control', 'id' => 'epc_constractor_mobile')); ?>
					</div>
				</div>
			</div>
			<div class="row col-md-12">
				<div class="col-md-1">
					<?php echo $this->Form->input('Save', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'save_2', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'save_2')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('Save & Next', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'next_2', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'next_2')); ?>
				</div>
			</div>
		</fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
</div>