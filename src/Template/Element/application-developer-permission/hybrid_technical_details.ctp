
<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php echo $this->Form->create($Applications, ['type' => 'file', 'name' => 'applicationform2', 'id' => 'applicationform2']); ?>
		<input type="hidden" name="tab_id" value="2" />
		<input type="hidden" name="app_dev_id" value="<?php echo $app_dev_id; ?>" />
		<fieldset>
			<legend>Technical Details</legend>
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
			<div class="col-md-12">
				<div class="col-md-6">
					<h5>Details of Wind Power Project</h5>
				</div>

			</div>
			<?php if ($ApplicationGeoLoc) { ?>
				<div class="row">
					<div class="col-md-12 table-responsive">
						<div class="table-container">
							<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead">
								<thead class="thead-dark">
									
									<th scopre="col" width="2%"></th>
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
											<td> <input type="checkbox" name="geo_loc_ids[]" class="form-check-input" data-id=<?php echo $v['id'] ?> <?php echo (isset($geoVariable) && in_array($v['id'], $geoVariable)) ? "checked" : '' ?>></td>
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
				</div>
				<div class="row" style="margin-top:20px">
					<?php
					$error_class = isset($ApplicationError['geo_loc']['_empty']) && !empty($ApplicationError['geo_loc']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-4 <?php echo $error_class; ?>">
						<input type="hidden" name="geo_loc">
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['geo_loc']['_empty']; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="form-group" style="margin-top: 10px;">
						<div class="col-md-2"><label> Total WTG (Nos.)</label></div>
						<div class="col-md-2 ">
							<?php echo $this->Form->input('wtg_no', array('label' => false, 'class' => 'form-control', 'disabled' => 'disabled', 'id' => 'add_wtg')); ?>
						</div>
						<div class="col-md-2"><label> Total WTG capacity (in MW)</label></div>
						<div class="col-md-2 ">
							<?php echo $this->Form->input('total_capacity', array('label' => false, 'class' => 'form-control', 'disabled' => 'disabled', 'id' => 'add_capacity')); ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row">

				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['grid_connectivity']['_empty']) && !empty($ApplicationError['grid_connectivity']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>Grid Connectivity<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('grid_connectivity', $gridLevel, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Select Grid Connectivity-', 'onChange' => 'javascript:change_injection();', 'id' => 'grid_connectivity')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['grid_connectivity']['_empty']; ?></div>
						<?php } ?>
					</div>

					<div class="col-md-3">
						<label>Proposed GETCO/PGCIL Substation<span class="mendatory_field">*</span><i data-content="Name of Proposed GETCO / PGCIL Substation" class="fa fa-info-circle"></i></label>
						<?php echo $this->Form->input('getco_substation', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Name of Proposed GETCO / PGCIL Substation', 'type' => 'text', 'autocomplete' => "false", 'onkeypress' => "return validateCharacter(event)")); ?>

					</div>

					<?php
					$error_class = isset($ApplicationError['injection_level']['_empty']) && !empty($ApplicationError['injection_level']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>Power Injection Level<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('injection_level', $injectionLevel, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Power Injection Level-', 'id' => 'injection_level')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['injection_level']['_empty']; ?></div>
						<?php } ?>
					</div>

					<div class="col-md-3">
						<label>Annual output of energy(kWh) <span class="mendatory_field">*</span><i data-content="Expected Annual output of energy from the proposed project in (kWh)" class="fa fa-info-circle"></i> </label>
						<?php echo $this->Form->input('project_energy', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Expected Annual output', 'type' => 'text', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">

					<?php
					$error_class = isset($ApplicationError['end_stu']['_empty']) && !empty($ApplicationError['end_stu']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 cls-stu <?php echo $error_class; ?>">
						<label>End use of electricity <span class="mendatory_field">*</span></label>
						<?php
						if (empty($error_class) &&  !empty($arrEndUseElec)) {
							$Applications->end_stu = $arrEndUseElec;
						}
						echo $this->Form->select('end_stu', $EndSTU, array('label' => false, 'class' => 'form-control', 'id' => 'end_stu', 'empty' => '-Select End use of electricity-')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['end_stu']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3 cls-ctu <?php echo $error_class; ?>">
						<label>End use of electricity <span class="mendatory_field">*</span></label>
						<?php
						if (empty($error_class) &&  !empty($arrEndUseElec)) {
							$Applications->end_ctu = $arrEndUseElec;
						}
						echo $this->Form->select('end_ctu', $EndCTU, array('label' => false, 'class' => 'form-control', 'id' => 'end_ctu', 'empty' => '-Select End use of electricity-')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['end_ctu']['_empty']; ?></div>
						<?php } ?>
					</div>


					<?php
					$error_class = isset($ApplicationError['captive']['_empty']) && !empty($ApplicationError['captive']['_empty']) ? 'has-error' : '';
					?>
					<div class="captive col-md-3 <?php echo $error_class; ?>">
						<label>RE Attributes<span class="mendatory_field">*</span></label>
						
						<?php $captive = [-1 => 'Select'] + $captive; echo $this->Form->select('captive', $captive, array('label' => false, 'class' => 'form-control', 'id' => 'captive', 'default' => -1)); ?>
						<?php if (isset($ApplicationError['captive']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['captive']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['third_party']['_empty']) && !empty($ApplicationError['third_party']['_empty']) ? 'has-error' : '';
					?>
					<div class="third-party col-md-3 <?php echo $error_class; ?>">
						<label>RE Attributes<span class="mendatory_field">*</span></label>
						<?php $third_party = [-1 => 'Select']+$third_party; echo $this->Form->select('third_party', $third_party, array('label' => false, 'class' => 'form-control', 'id' => 'third_party', 'default' => -1)); ?>
						<?php if (isset($ApplicationError['third_party']['_empty'])) { ?>
							<div class="help-block"><?php echo $ApplicationError['third_party']['_empty']; ?></div>
						<?php } ?>
					</div>

				</div>

			</div>
			<legend class="captive-only">Share Detail </legend>
			<div class="row captive-only">

				<div class="col-md-6">
					<div class="col-md-12">
						<div class="col-md-10 text-center">
							<h5>RE Generator Share</h5>
						</div>
						<div class="col-md-2 block" style=" text-align:right;">

						</div>
					</div>
					<table id="tbl_generator_share_info" class="table table-striped table-bordered table-hover custom-greenhead">
						<thead>
							<tr class="thead-dark">
								<th scope="col" width="5%" rowspan="2">SrNo.</th>
								<th scope="col" width="60%" rowspan="2">Name of share holder</th>
								<th scope="col" width="30%" rowspan="2">Percentage equity share holding with voting rights</th>

							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>
									<?php echo $this->Form->input('re_name_of_share_holder', array('label' => false, 'value' => $Applications['re_name_of_share_holder'], 'class' => 'rfibox re_name_of_share_holder_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 're_name_of_share_holder_0' ,'onkeypress' => "return validateCharacter(event)")); ?>
								</td>
								<td>
									<?php echo $this->Form->input('re_equity_re_persontage', array('label' => false, 'value' => $Applications['re_equity_re_persontage'],  'class' => 'rfibox re_equity_persontage_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 're_equity_persontage', 'onkeypress' => "return reSharePerValidateNumber(event)", "onChange" => "checkRange(this.value)")); ?>
								</td>
							</tr>
						</tbody>
					</table>
					<div id="error_re"></div>
				</div>

				<div class="col-md-6">
					<div class="col-md-12">
						<div class="col-md-10 text-center">
							<h5>Consumer Share</h5>
						</div>
						<div class="col-md-2 block" style=" text-align:right;">
							<input style="margin-right:14px;margin-bottom:5px" class="btn green AddConsumerShareRow" type="button" id="" value="ADD" />
						</div>
					</div>
					<table id="tbl_consumer_share_info" class="table table-striped table-bordered table-hover custom-greenhead">
						<thead>
							<tr class="thead-dark">
								<th scope="col" width="5%" rowspan="2">SrNo.</th>
								<th scope="col" width="60%" rowspan="2">Name of share holder</th>
								<th scope="col" width="30%" rowspan="2">Percentage equity share holding with voting rights</th>
								<th scope="col" width="5%" rowspan="2">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($Consumer_Share_Data)) {
								foreach ($Consumer_Share_Data as $key => $value) {
									$encode_application_id = encode($Applications->application_id); ?>
									<tr>
										<?php echo $this->Form->input('id_consumer', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_consumer', 'id' => 'id_consumer_' . $key]); ?>
										<td><?php echo $key + 1 ?></td>
										<td>
											<?php echo $this->Form->input('name_of_share_holder[]', array('label' => false, 'class' => 'rfibox name_of_share_holder_cls', 'value' => $value['name_of_share_holder'], 'autocomplete' => "false", 'type' => 'text','onkeypress' => "return validateCharacter(event)", 'id' => 'name_of_share_holder_' . $key)); ?>
										</td>
										<td>
											<?php echo $this->Form->input('equity_persontage[]', array('label' => false, 'class' => 'rfibox equity_persontage_cls', 'value' => $value['equity_persontage'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'equity_persontage_' . $key, 'onkeypress' => "return validateNumber(event)", 'onChange' => 'javascript:changeConsumerSharePer(this)')); ?>
										</td>

										<td class="valignTop lastrow">
											<?php if ($key != 0) { ?>
												<?php if (isset($value['id']) && !empty($value['id'])) { ?>
													<button type="button" id="btn_<?php echo $key ?>" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeGetcoSub('<?php echo $value['id']; ?>','<?php echo $encode_application_id ?>')"><i class="fa fa-trash" aria-hidden="true"></i></button>

												<?php } else { ?>
													<button class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowPoolingSub(this)"></button>
												<?php } ?>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
									<td>1</td>
									<td>
										<?php echo $this->Form->input('name_of_share_holder[]', array('label' => false, 'class' => 'rfibox name_of_share_holder_cls', 'autocomplete' => "false", 'type' => 'text','onkeypress' => "return validateCharacter(event)", 'id' => 'name_of_share_holder_0')); ?>
									</td>
									<td>
										<?php echo $this->Form->input('equity_persontage[]', array('label' => false, 'class' => 'rfibox equity_persontage_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'equity_persontage_0', 'onkeypress' => "return validateNumber(event)", 'onChange' => 'javascript:changeConsumerSharePer(this)')); ?>
									</td>
									<td class="valignTop lastrow">&nbsp;</td>
								</tr>
							<?php } ?>

						</tbody>
					</table>
					<div>
						<div class="form-group" style="margin-top: 10px;">
							<div class="col-md-4">Share Total</div>
							<div class="col-md-2 ">
								<?php echo $this->Form->input('share_total', array('label' => false, 'class' => 'form-control', 'value' => isset($totalModulenos['nos_mod_inv']) ? $totalModulenos['nos_mod_inv'] : 0, 'readonly' => 'readonly', 'id' => 'share_total')); ?>
							</div>

						</div>
					</div>
					<div id="error_consumer"></div>
				</div>
			</div>
			<!--B. Sale To Discom -->
			<legend class="sale-to-discom">Sale To Discom </legend>
			<div class="row">
				<div class="form-group">
					<div class="col-md-6 sale-to-discom">
						<label>LOI/PPA with GUVNL/DISCOM<span class="mendatory_field">*</span> <i data-content="Upload LOI/PPA with GUVNL/DISCOM to purchase power from proposed project" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_upload_sale_to_discom', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_sale_to_discom', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->upload_sale_to_discom)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_sale_to_discom)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/w_upload_sale_to_discom/' . encode($Applications->id) . "\">View Sale To Discom</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='upload_sale_to_discom-file-errors'></div>
					</div>
					<div class="col-md-6 sale-to-discom">
						<label>No Due Certificate<span class="mendatory_field">*</span> <i data-content="Upload 'No Due Certificate' for the project site from concern DISCOM in whose jurisdiction the Solar plant is to be installed" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_no_due_1', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'no_due_1', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->no_due_1)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->no_due_1)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/w_no_due_1/' . encode($Applications->id) . "\">View No Due Certificate Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='no_due_1-file-errors'></div>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<div class="col-md-6 sale-to-discom">
						<label>No Due Certificate<span class="mendatory_field">*</span> <i data-content="Upload 'No Due Certificate' for the project site and developer from concern DISCOM in whose jurisdiction the Solar plant is to be installed" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_no_due_2', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'no_due_2', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->no_due_2)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->no_due_2)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/w_no_due_2/' . encode($Applications->id) . "\">View No Due Certificate Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='no_due_2-file-errors'></div>
					</div>

					<div class="col-md-6 sale-to-discom">
						<label>Proof Regarding Ownership of the Project<span class="mendatory_field">*</span> <i data-content="CA certificate regarding the ownership of the proposed Solar Power Project" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_upload_proof_of_ownership_1', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_proof_of_ownership_1', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->upload_proof_of_ownership_1)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_proof_of_ownership_1)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/w_upload_proof_of_ownership_1/' . encode($Applications->id) . "\">View Proof of Ownership Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='upload_proof_of_ownership_1-file-errors'></div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">

					<div class="col-md-6 sale-to-discom">
						<label>Proof Regarding Ownership of the Project<span class="mendatory_field">*</span> <i data-content="Share Subscription and Share Holding Agreement between RE Generator and Consumer Holder along with the CA certificate certifying the equity holding of the proposed Solar Power Project for captive use as per the definition no. 6 of clause no.5 of Gujarat Renewable Energy Policy - 2023" class="fa fa-info-circle"></i><small> [Upload PDF of size upto 1MB]</small></label>
						<?php echo $this->Form->input('a_upload_proof_of_ownership_2', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'upload_proof_of_ownership_2', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>

						<?php if (!empty($Applications->upload_proof_of_ownership_2)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_proof_of_ownership_2)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/w_upload_proof_of_ownership_2/' . encode($Applications->id) . "\">View Proof of Ownership Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='upload_proof_of_ownership_2-file-errors'></div>
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
							'div' => false, 'options' => [['value' => 1, 'text' => "Yes"], ['value' => 0, 'text' => "No"]], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
				</div>
			</div>
			<div class="row captive-with-rpo">
				<div class="form-group" style="margin-bottom: 15px;display: flex;width: 100%;flex-wrap: wrap;">
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
						<?php echo $this->Form->input('certi_of_stoa_capacity', array('label' => false, 'class' => 'form-control', 'id' => 'certi_of_stoa_capacity', 'onkeypress' => "return validateDecimal(event)")); ?>
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
			<legend class="project-for-third-party">Project for Third Party Sale OR Third Party Sale & RPO compliance to Third Party OR Third Party Sale with REC Mechanism(Details of Third Party beneficiary company)</legend>
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
						<label>Consumer No.<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('third_party_consumer_no', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_consumer_no', 'onkeypress' => "return validateNumber(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Contract Demand<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('third_party_contract_demand', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_contract_demand', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>
					<div class="col-md-4">
						<label>Capacity of Existing Solar Plant(MW)<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('third_party_capacity_existing_plant', array('label' => false, 'class' => 'form-control', 'id' => 'third_party_capacity_existing_plant', 'onkeypress' => "return validateDecimal(event)")); ?>
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
			<br>
			<legend></legend>
			<div class="row">
				<div class="form-group">

					<div class="col-md-3">
						<label>Whether application is for RE Project Developer as a Transferor in STU?<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('app_trans_to_stu', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:toggle_transferor_in_stu();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:toggle_transferor_in_stu();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>

					<div class="col-md-3 transferor_in_stu">
						<label>Consumer No.<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('consumer_no', array('label' => false, 'class' => 'form-control', 'id' => 'consumer_no', 'type' => 'text', 'onkeypress' => "return validateNumber(event)")); ?>
					</div>

					<div class="col-md-3 transferor_in_stu">
						<label>Sanctioned Load / Contract Demand<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('sanctioned_load', array('label' => false, 'class' => 'form-control', 'id' => 'sanctioned_load' , 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>

					<div class="col-md-3 transferor_in_stu">
						<label>Electricity Bill<span class="mendatory_field">*</span><i data-content="Enclose latest electricity bill" class="fa fa-info-circle"></i></label>
						<?php echo $this->Form->input('a_copy_of_electricity_bill', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'copy_of_electricity_bill', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->copy_of_electricity_bill)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->copy_of_electricity_bill)) { ?>
							<?php

								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_copy_of_electricity_bill/' . encode($Applications->id) . "\">View Electricity Bill</a></strong>";
							}
							?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<?php $error_discom = "";
					if (isset($ApplicationError['discom']) && isset($ApplicationError['discom']['_empty']) && !empty($ApplicationError['discom']['_empty'])) {
						$error_discom = "has-error";
					} ?>
					<div class="col-md-3 <?php echo $error_discom; ?> transferor_in_stu">
						<label>Name of concerned DISCOM<span class="mendatory_field">*</span> <i data-content="Name of concerned DISCOM, within whose jurisdiction the Wind Power Project is to be installed" class="fa fa-info-circle"></i></label>
						<?php echo $this->Form->select('discom', $discom_arr, array('label' => false, 'empty' => '-Select DisCom-', 'class' => 'form-control', 'placeholder' => 'DisCom', 'id' => 'discom'));
						if (!empty($error_discom)) {  ?>
							<div class="help-block"><?php echo $ApplicationError['discom']['_empty']; ?></div>
						<?php } ?>
					</div>

					<?php $error_voltage_level = "";
					if (isset($ApplicationError['voltage_level']) && isset($ApplicationError['voltage_level']['_empty']) && !empty($ApplicationError['voltage_level']['_empty'])) {
						$error_voltage_level = "has-error";
					} ?>
					<div class="col-md-3 <?php echo $error_voltage_level; ?> transferor_in_stu">
						<label>Voltage level<span class="mendatory_field">*</span><i data-content="Voltage level at which energy to be drawn" class="fa fa-info-circle"></i> </label>
						<?php echo $this->Form->select('voltage_level', $injectionLevel, array('label' => false, 'empty' => '-Select Voltage Level-', 'class' => 'form-control', 'id' => 'voltage_level'));
						if (!empty($error_voltage_level)) {  ?>
							<div class="help-block"><?php echo $ApplicationError['voltage_level']['_empty']; ?></div>
						<?php } ?>
					</div>

					<div class="col-md-3 transferor_in_stu">
						<label>Approximate Project Cost (Rs. in lacs)<span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('project_estimated_cost', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Approximate Project Cost', 'type' => 'text', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>

					<div class="col-md-3 transferor_in_stu">
						<label>Do you intend to wheel the energy at multiple location?<span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('wheel_energy_multi_location', [
							'type' => 'radio', 'label' => false,
							'div' => false, 'options' => [
								['value' => 1, 'text' => "Yes", "onclick" => "javascript:toggle_wheeled();"],
								['value' => 0, 'text' => "No", "onclick" => "javascript:toggle_wheeled();"]
							], 'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
						]);
						?>
					</div>
				</div>
			</div>

			<div class="col-md-6 wheeled">
				<div class="col-md-10">
					<h5>Wheel the energy at multiple locations</h5>

				</div>
				<div class="col-md-2 block" style=" text-align:right;">
					<input style="margin-right:14px;" class="btn green AddDiscomRow" type="button" id="" value="+" />

				</div>
			</div>
			<div class="row wheeled">
				<div class="col-md-6 table-responsive">
					<table id="tbl_energy_wheeled" class="table table-striped table-bordered table-hover custom-greenhead">
						<thead class="thead-dark">
							<th scope="col" width="60%">DISCOM</th>
							<th scope="col" width="30%">% of Energy to be wheeled</th>
							<th scope="col" width="10%">Action</th>
						</thead>
						<tbody>
							<?php if (isset($energyData) && !empty($energyData)) {
								foreach ($energyData as $key => $value) {
							?>
									<tr>
										<td class="valignTop">
											<?php echo $this->Form->select('energy_discom[]', $discom_arr, array('label' => false, 'class' => 'rfibox energy_discom_cls', 'empty' => '- Select Discom -', 'id' => 'energy_discom_' . $key, 'value' => $value['energy_discom'])); ?>
										</td>
										<td class="valignTop">
											<?php echo $this->Form->input('energy_per[]', array('label' => false, 'class' => 'form-control energy_per_cls rfibox', 'placeholder' => 'Energy %.', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'energy_per_' . $key, 'value' => $value['energy_per'])); ?>
										</td>
										<td class="valignTop lastrow">
											<?php
											if ($key != 0) {

												if (isset($value['id']) && !empty($value['id'])) {
													echo '<input type="button" id="" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeEnergy(' . $value['id'] . ',\'' . encode($Applications->application_id) . '\',\'' . encode($value['app_dev_per_id']) . '\')" value="-" />';
												} else {
													echo '<input class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowEnergy(this)" value="-" />';
												}
											}
											?>
										</td>
									</tr>
								<?php }
							} else { ?>
								<tr>
									<td class="valignTop">
										<?php echo $this->Form->select('energy_discom[]', $discom_arr, array('label' => false, 'class' => 'rfibox energy_discom_cls', 'empty' => '- Select Discom -', 'id' => 'energy_discom_0')); ?>
									</td>
									<td class="valignTop">
										<?php echo $this->Form->input('energy_per[]', array('label' => false, 'class' => 'form-control energy_per_cls rfibox', 'placeholder' => 'Energy %.', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'energy_per_0')); ?>
									</td>
									<td class="valignTop lastrow">&nbsp;</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
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