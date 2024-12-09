<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php echo $this->Form->create($Applications, ['type' => 'file', 'name' => 'applicationform2', 'id' => 'applicationform2']); ?>
		<input type="hidden" name="tab_id" value="2" />
		<fieldset>
			<legend>Technical Details</legend>
			<?php if ($type == 'groundmounted') { ?>
				<div class="row">
					<div class="form-group">
						<div class="col-md-4">
							<label>Project Capacity AC (in MW) <span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('Applications.pv_capacity_ac', array('label' => false, 'class' => 'form-control', 'placeholder' => 'PV Capacity', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text')); ?>
						</div>
						<div class="col-md-4">
							<label>Project Capacity DC (in MW) <span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('Applications.pv_capacity_dc', array('label' => false, 'class' => 'form-control', 'placeholder' => 'PV Capacity', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text')); ?>
						</div>

					</div>
				</div>
			<?php } elseif ($type == 'wind' || $type == 'hybrid') { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6">
							<h4>Details of Wind Power Project</h4>
						</div>
						<div class="col-md-6 block" style=" text-align:right;">
							<input style="margin-right:14px;" class="btn green AddWindRow" type="button" id="" value="+" />
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12 table-responsive">
							<table id="tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead">
								<thead class="thead-dark">
									<th scope="col">Nos. of WTG</th>
									<th scope="col">Capacity of each WTG (in MW)</th>
									<th scope="col">Total WTG Capacity (in MW)</th>
									<th scope="col">Make</th>
									<th scope="col">Action</th>
								</thead>
								<tbody>
									<?php if (!empty($Wind_Data)) {
										foreach ($Wind_Data as $key => $value) {
											$encode_application_id = encode($value['application_id']);
									?>
											<tr>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.id_wind][', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_wind', 'id' => 'id_wind_' . $key]); ?>
													<?php //echo $this->Form->input('application_id[]',['label' => true,'type'=>'hidden','value'=>$value->application_id]); 
													?>
													<?php echo $this->Form->input('Applications.wtg_no][', array('label' => false, 'class' => 'form-control wtg_no_cls rfibox', 'placeholder' => 'Nos. of WTG', 'onkeypress' => "return validateDecimal(event)", 'id' => "wtg_no_" . $key, 'onChange' => 'javascript:changeWindRowCapacity(this)', 'value' => $value['nos_mod_inv'])); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.capacity_wtg][', array('label' => false, 'class' => 'form-control rfibox wtg_cap_cls', 'placeholder' => 'Capacity of each WTG', 'onkeypress' => "return validateDecimal(event)", 'id' => "capacity_wtg_" . $key, 'onChange' => 'javascript:changeWindRowCapacity(this)', 'value' => $value['mod_inv_capacity'])); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.total_capacity][', array('label' => false, 'class' => 'form-control rfibox wtg_capacity_cls', 'placeholder' => 'Total Capacity in MW', 'id' => 'total_capacity_' . $key, 'value' => $value['mod_inv_total_capacity'], 'readonly' => 'readonly')); ?>
												</td>
												<td class="valignTop">
													<?php echo $this->Form->select('Applications.make][', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wind_make_cls', 'value' => $value['mod_inv_make'], 'empty' => '-Select Wind Make-', 'id' => 'make_' . $key)); ?>

												</td>
												
												<td class="valignTop lastrow">
													<?php if ($key != 0) { ?>
														<?php if (isset($value['id']) && !empty($value['id'])) { ?>
															<input type="button" id="" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeWind('<?php echo $value['id']; ?>',<?php echo $value['application_id']; ?>,<?php echo $value['mod_inv_total_capacity']; ?>,<?php echo $value['capacity_type']; ?>,<?php echo $value['nos_mod_inv']; ?>,<?php echo $value['mod_inv_capacity']; ?>,'<?php echo $encode_application_id ?>')" value="-" />
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
												<?php echo $this->Form->input('Applications.wtg_no][', array('label' => false, 'class' => 'form-control wtg_no_cls rfibox', 'placeholder' => 'Nos. of WTG', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'wtg_no_0', 'onChange' => 'javascript:changeWindRowCapacity(this)')); ?>

											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('Applications.capacity_wtg][', array('label' => false, 'class' => 'form-control rfibox wtg_cap_cls', 'placeholder' => 'Capacity of each WTG', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'capacity_wtg_0', 'onChange' => 'javascript:changeWindRowCapacity(this)')); ?>

											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('Applications.total_capacity][', array('label' => false, 'class' => 'form-control wtg_capacity_cls rfibox', 'placeholder' => 'Total Capacity in MW', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'total_capacity_0', 'readonly' => 'readonly')); ?>

											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('Applications.make][', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wind_make_cls', 'empty' => '-Select Wind Make-', 'id' => 'make_0')); ?>

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
			<?php if ($type == 'hybrid' || in_array($applicationCategory->id,array(5,6))) { ?>

				<div class="row">
					<!-- <div class="col-md-12">
						<h4>Module Capacity</h4>
					</div> -->
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
									<th scope="col">Nos. of Module</th>
									<th scope="col">Capacity of each Module (in Wp)</th>
									<th scope="col">Total SPV Modules Capacity (in MW)</th>
									<th scope="col">Make</th>
									<th scope="col">SPV Technologies</th>
									<th scope="col">Solar Panel</th>
									<th scope="col">Action</th>
								</thead>
								<tbody>
									<?php if (!empty($Hybrid_Module_Data)) {
										foreach ($Hybrid_Module_Data as $key => $value) {
											$encode_application_id = encode($value['application_id']);
									?>
											<tr>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.id_module][', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_module', 'id' => 'id_module_' . $key]); ?>
													<?php //echo $this->Form->input('application_id[]',['label' => true,'type'=>'hidden','value'=>$value->application_id]); 
													?>
													<?php echo $this->Form->input('Applications.nos_mod][', array('label' => false, 'class' => 'form-control module_no_cls rfibox', 'placeholder' => 'Nos. of Module', 'onkeypress' => "return validateDecimal(event)", 'id' => "nos_mod_" . $key, 'onChange' => 'javascript:changeModuleRowCapacity(this)', 'value' => $value['nos_mod_inv'])); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.mod_capacity][', array('label' => false, 'class' => 'form-control rfibox module_cap_cls', 'placeholder' => 'Capacity of each Module (in Wp)', 'onkeypress' => "return validateDecimal(event)", 'id' => "mod_capacity_" . $key, 'onChange' => 'javascript:changeModuleRowCapacity(this)', 'value' => $value['mod_inv_capacity'])); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.mod_total_capacity][', array('label' => false, 'class' => 'form-control rfibox module_capacity_cls', 'placeholder' => 'Total SPV Modules Capacity (in MW)', 'id' => 'mod_total_capacity_' . $key, 'value' => $value['mod_inv_total_capacity'], 'readonly' => 'readonly')); ?>
												</td>
												<td class="valignTop">
													<?php echo $this->Form->select('Applications.mod_make][', $type_manufacturer_mod, array('label' => false, 'class' => 'rfibox module_make_cls', 'value' => $value['mod_inv_make'], 'empty' => '-Select SPV Module Make-', 'id' => 'mod_make_' . $key)); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->select('Applications.mod_type_of_spv][', $type_of_spv, array('label' => false, 'class' => 'rfibox module_spv_cls', 'value' => $value['mod_type_of_spv'], 'empty' => '-Select SPV Technologies-', 'id' => 'mod_type_of_spv_' . $key)); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->select('Applications.mod_type_of_solar_panel][', $type_of_solar_panel, array('label' => false, 'class' => 'rfibox module_solar_panel_cls', 'value' => $value['mod_type_of_solar_panel'], 'empty' => '-Select Solar Panel-', 'id' => 'mod_type_of_solar_panel_' . $key)); ?>

												</td>
												<td class="valignTop lastrow">
													<?php if ($key != 0) { ?>
														<?php if (isset($value['id']) && !empty($value['id'])) { ?>
															<input type="button" id="" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeHybrid('<?php echo $value['id']; ?>',<?php echo $value['application_id']; ?>,<?php echo $value['mod_inv_total_capacity']; ?>,<?php echo $value['capacity_type']; ?>,'<?php echo $encode_application_id ?>')" value="-" />
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
												<?php echo $this->Form->input('Applications.nos_mod][', array('label' => false, 'class' => 'form-control module_no_cls rfibox', 'placeholder' => 'Nos. of Module', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'nos_mod_0', 'onChange' => 'javascript:changeModuleRowCapacity(this)')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('Applications.mod_capacity][', array('label' => false, 'class' => 'form-control rfibox module_cap_cls', 'placeholder' => 'Capacity of each Module (in Wp)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'mod_capacity_0', 'onChange' => 'javascript:changeModuleRowCapacity(this)')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('Applications.mod_total_capacity][', array('label' => false, 'class' => 'form-control module_capacity_cls rfibox', 'placeholder' => 'Total SPV Modules Capacity (in MW)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'mod_total_capacity_0', 'readonly' => 'readonly')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('Applications.mod_make][', $type_manufacturer_mod, array('label' => false, 'class' => 'rfibox module_make_cls', 'empty' => '-Select SPV Module Make-', 'id' => 'mod_make_0')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('Applications.mod_type_of_spv][', $type_of_spv, array('label' => false, 'class' => 'rfibox module_spv_cls', 'empty' => '-Select SPV Technologies-', 'id' => 'mod_type_of_spv_0')); ?>

											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('Applications.mod_type_of_solar_panel][', $type_of_solar_panel, array('label' => false, 'class' => 'rfibox module_solar_panel_cls', 'empty' => '-Select Solar Panel-', 'id' => 'mod_type_of_solar_panel_0')); ?>

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
							<?php echo $this->Form->input('Applications.module_hybrid_capacity', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'add_mod_capacity', 'readonly' => 'readonly', 'value' => isset($Applications->module_hybrid_capacity) ? $Applications->module_hybrid_capacity : 0)); ?>
						</div>

						<div class="col-md-4">
							<?php
							$error_text = 'Module capacity can not be blank at least add one Module.';

							if (empty($hybrid_capacity->module_hybrid_capacity)) {
								echo '<div style="color: #a94442;">' . $error_text . '</div>';
							}
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- <div class="col-md-12">
						<h4>Inverter Capacity</h4>
					</div> -->
					<div class="col-md-12">
						<div class="col-md-6">
							<h4>Details of Invertors</h4>
						</div>
						<div class="col-md-6 blockInv" style=" text-align:right;">
							<input style="margin-right:14px;" class="btn green AddInverterRow" type="button" id="" value="+" />
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12 table-responsive">
							<table id="tbl_inverter_info" class="table table-striped table-bordered table-hover custom-greenhead">
								<thead class="thead-dark">
									<th scope="col">Nos. of Inverter</th>
									<th scope="col">Capacity of each Inverter (in kW)</th>
									<th scope="col">Total Inverter Capacity (in MW)</th>
									<th scope="col">Make</th>
									<th scope="col">Type of Inverter Used</th>
									<th scope="col">Action</th>
								</thead>
								<tbody>
									<?php if (!empty($Hybrid_Inverter_Data)) {
										foreach ($Hybrid_Inverter_Data as $key => $value) {
											$encode_application_id = encode($value['application_id']);
									?>
											<tr>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.id_inverter][', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_inverter', 'id' => 'id_inverter_' . $key]); ?>
													<?php //echo $this->Form->input('application_id[]',['label' => true,'type'=>'hidden','value'=>$value->application_id]); 
													?>
													<?php echo $this->Form->input('Applications.nos_inv][', array('label' => false, 'class' => 'form-control inverter_no_cls rfibox', 'placeholder' => 'Nos. of Inverter', 'onkeypress' => "return validateDecimal(event)", 'id' => "nos_inv_" . $key, 'onChange' => 'javascript:changeInverterRowCapacity(this)', 'value' => $value['nos_mod_inv'])); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.inv_capacity][', array('label' => false, 'class' => 'form-control rfibox inverter_cap_cls', 'placeholder' => 'Capacity of each Inverter (in kW)', 'onkeypress' => "return validateDecimal(event)", 'id' => "inv_capacity_" . $key, 'onChange' => 'javascript:changeInverterRowCapacity(this)', 'value' => $value['mod_inv_capacity'])); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->input('Applications.inv_total_capacity][', array('label' => false, 'class' => 'form-control rfibox inverter_capacity_cls', 'placeholder' => 'Total Inverter Capacity (in MW)', 'id' => 'inv_total_capacity_' . $key, 'value' => $value['mod_inv_total_capacity'], 'readonly' => 'readonly')); ?>
												</td>
												<td class="valignTop">
													<?php echo $this->Form->select('Applications.inv_make][', $type_manufacturer_inv, array('label' => false, 'class' => 'rfibox inverter_make_cls', 'value' => $value['mod_inv_make'], 'empty' => '-Select Inverter Make-', 'id' => 'inv_make_' . $key)); ?>

												</td>
												<td class="valignTop">
													<?php echo $this->Form->select('Applications.inv_used][', $type_of_inverter_used, array('label' => false, 'class' => 'rfibox inverter_used_cls', 'value' => $value['inv_used'], 'empty' => '-Select Type of Inverter Used-', 'id' => 'inv_used_' . $key)); ?>

												</td>
												<td class="valignTop lastrow">
													<?php if ($key != 0) { ?>
														<?php if (isset($value['id']) && !empty($value['id'])) { ?>
															<input type="button" id="" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeHybrid('<?php echo $value['id']; ?>',<?php echo $value['application_id']; ?>,<?php echo $value['mod_inv_total_capacity']; ?>,<?php echo $value['capacity_type']; ?>,'<?php echo $encode_application_id ?>')" value="-" />
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
												<?php echo $this->Form->input('Applications.nos_inv][', array('label' => false, 'class' => 'form-control inverter_no_cls rfibox', 'placeholder' => 'Nos. of Inverter', 'onkeypress' => "return validateNumber(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'nos_inv_0', 'onChange' => 'javascript:changeInverterRowCapacity(this)')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('Applications.inv_capacity][', array('label' => false, 'class' => 'form-control rfibox inverter_cap_cls', 'placeholder' => 'Capacity of each Inverter (in kW)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'inv_capacity_0', 'onChange' => 'javascript:changeInverterRowCapacity(this)')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->input('Applications.inv_total_capacity][', array('label' => false, 'class' => 'form-control inverter_capacity_cls rfibox', 'placeholder' => 'Total Inverter Capacity (in MW)', 'onkeypress' => "return validateDecimal(event)", 'autocomplete' => "false", 'type' => 'text', 'id' => 'inv_total_capacity_0', 'readonly' => 'readonly')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('Applications.inv_make][', $type_manufacturer_inv, array('label' => false, 'class' => 'rfibox inverter_make_cls', 'empty' => '-Select Inverter Make-', 'id' => 'inv_make_0')); ?>
											</td>
											<td class="valignTop">
												<?php echo $this->Form->select('Applications.inv_used][', $type_of_inverter_used, array('label' => false, 'class' => 'rfibox inverter_used_cls', 'empty' => '-Select Type of Inverter Used-', 'id' => 'inv_used_0')); ?>
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
							<?php echo $this->Form->input('Applications.inverter_hybrid_capacity', array('label' => false, 'class' => 'form-control', 'id' => 'add_inv_capacity', 'readonly' => 'readonly', 'value' => isset($Applications->inverter_hybrid_capacity) ? $Applications->inverter_hybrid_capacity : 0)); ?>
						</div>

						<div class="col-md-4">
							<?php
							$error_text = 'Inverter capacity can not be blank at least add one Module.';

							if (empty($hybrid_capacity->inverter_hybrid_capacity)) {
								echo '<div style="color: #a94442;">' . $error_text . '</div>';
							}
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-2"><label> Total Commulative capacity AC (MW)</label></div>
						<div class="col-md-2 ">
							<?php
							//,'value'=>!empty($Applications->total_wind_hybrid_capacity) ? $Applications->total_wind_hybrid_capacity : 0
							// echo $this->Form->input('Applications.total_wind_hybrid_capacity',array('type'=>'text','label' => false,'class'=>'form-control','id'=>"total_wind_hybrid_capacity",'readonly'=>'readonly')); 
							?>
							<?php echo $this->Form->input('total_wind_hybrid_capacity_disp', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'readonly' => 'readonly', 'id' => 'total_wind_hybrid_capacity_disp')); ?>
						</div>
						<div class="col-md-2"><label> Total Commulative capacity DC (MW)</label></div>
						<div class="col-md-2 ">
							<?php
							//,'value'=>!empty($Applications->module_hybrid_capacity) ? $Applications->module_hybrid_capacity : 0 
							//echo $this->Form->input('Applications.module_hybrid_capacity',array('label' => false,'class'=>'form-control','readonly'=>'readonly' ,'id'=>'module_hybrid_capacity')); 
							?>
							<?php //echo $this->Form->input('module_hybrid_capacity',array('label' => false,'class'=>'form-control','value'=>0,'readonly'=>'readonly','id'=>'module_hybrid_capacity')); 
							?>
							<?php echo $this->Form->input('module_hybrid_capacity_disp', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'readonly' => 'readonly', 'id' => 'module_hybrid_capacity_disp')); ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row">
				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['error_capacity']['custom']) && !empty($ApplicationError['error_capacity']['custom']) ? 'has-error' : '';
					?>
					<div class="col-md-12 <?php echo $error_class; ?>">
						<?php echo $this->Form->input('Applications.error_capacity', array('label' => false, 'class' => 'change_customer_type form-control', 'placeholder' => '', 'autocomplete' => "false","type"=>'hidden')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['error_capacity']['custom'];?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['grid_connectivity']['_empty']) && !empty($ApplicationError['grid_connectivity']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>Grid Connectivity<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('Applications.grid_connectivity', $gridLevel, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Select Grid Connectivity-', 'onChange' => 'javascript:change_injection();', 'id' => 'grid_connectivity')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['grid_connectivity']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['injection_level']['_empty']) && !empty($ApplicationError['injection_level']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 cls-stu <?php echo $error_class; ?>">
						<label>Power Injection Level<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('Applications.injection_level', $injectionLevel, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Power Injection Level-', 'id' => 'injection_level')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['injection_level']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3 cls-ctu">
						<label>Power Injection Level<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.injection_level_ctu', array('label' => false, 'class' => 'change_customer_type form-control', 'placeholder' => '-Power Injection Level-', 'autocomplete' => "false")); ?>
					</div>

					<div class="col-md-3 cls-stu">
						<label>Name of Proposed GETCO / PGCIL Substation <span class="mendatory_field">*</span></label>
						
						
						<?php echo $this->Form->select('Applications.getco_substation', $substation_details, array('label' => false, 'class' => 'change_customer_type form-control  chosen-select','id'=>'stu', 'empty' => '-Select Substation-')); ?>
					</div>
					<div class="col-md-3 cls-ctu">
						<label>Name of Proposed GETCO / PGCIL Substation <span class="mendatory_field">*</span></label>
						
						<?php echo $this->Form->input('Applications.getco_substation', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Name of Proposed GETCO / PGCIL Substation', 'id'=>'ctu','autocomplete' => "false")); ?> 
						
					</div>
					<?php
					$error_class = isset($ApplicationError['end_stu']['_empty']) && !empty($ApplicationError['end_stu']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 cls-stu <?php echo $error_class; ?>">
						<label>End use of electricity <span class="mendatory_field">*</span></label>
						<?php
						if (empty($error_class) &&  !empty($arrEndUseElec)) {
							$Applications->end_stu = $arrEndUseElec;
						}
						echo $this->Form->select('Applications.end_stu', $EndSTU, array('label' => false, 'class' => 'form-control', 'id' => 'end_stu', 'empty' => '-Select End use of electricity-', 'onChange' => 'javascript:clickstd();')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['end_stu']['_empty']; ?></div>
						<?php } ?>
						<?php /* <?php foreach($EndSTU as $chkData) { 
							$checkedSTU 		= false;
							if(in_array($chkData,$arrEndUseElec)) {
								$checkedSTU 	=	true;
							}
						?>
							<?php echo $this->Form->input('Applications.end_stu][', array('label' => false,'value'=>$chkData,'type'=>'checkbox','class'=>'end_stu_cls','placeholder'=>'','onClick'=>'javascript:clickstd();','checked'=>$checkedSTU)); ?>
							<span class="textCheckeboxLeft"><?php echo $chkData;?></span><br>
						<?php } 
						$checkedSTU 		= false;
						if(in_array('Proto Type',$arrEndUseElec)) {
							$checkedSTU 	=	true;
						}
						?>
						echo $this->Form->input('Applications.end_stu][', array('label' => false,'value'=>'Proto Type','type'=>'checkbox','class'=>'','placeholder'=>'','checked'=>$checkedSTU)); ?><span class="textCheckeboxLeft"><?php echo 'Proto Type';*/ ?>

					</div>
					<?php
					$error_class = isset($ApplicationError['end_ctu']['_empty']) && !empty($ApplicationError['end_ctu']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 cls-ctu <?php echo $error_class; ?>">
						<label>End use of electricity <span class="mendatory_field">*</span></label>
						<?php
						if (empty($error_class) &&  !empty($arrEndUseElec)) {
							$Applications->end_ctu = $arrEndUseElec;
						}
						echo $this->Form->select('Applications.end_ctu', $EndCTU, array('label' => false, 'class' => 'form-control', 'id' => 'end_ctu', 'empty' => '-Select End use of electricity-')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['end_ctu']['_empty']; ?></div>
						<?php } ?>
						<?php /*foreach($EndCTU as $chkData) { 
							$checkedCTU 		= false;
							if(in_array($chkData,$arrEndUseElec)) {
								$checkedCTU 	=	true;
							}
						?>
							<?php echo $this->Form->input('Applications.end_ctu][', array('label' => false,'value'=>$chkData,'type'=>'checkbox','class'=>'end_ctu_cls','placeholder'=>'','checked'=>$checkedCTU)); ?>
							<span class="textCheckeboxLeft"><?php echo $chkData;?></span><br>
						<?php } $checkedCTU 		= false;
							if(in_array('Proto Type',$arrEndUseElec)) {
								$checkedCTU 	=	true;
							}
							
						 ?>
						<?php echo $this->Form->input('Applications.end_ctu][', array('label' => false,'value'=>'Proto Type','type'=>'checkbox','class'=>'','placeholder'=>'','checked'=>$checkedCTU)); ?><span class="textCheckeboxLeft"><?php echo 'Proto Type';?></span>
						<?php echo $this->Form->input('Applications.end_ctu_select', array('label' => false,'type'=>'hidden')); */ ?>
					</div>
					<div class="col-md-3 cls-stu sale-discom hide">
						<label>Sale to DISCOM <span class="mendatory_field">*</span>&nbsp;<i data-content="Sale to DISCOM, please attached the consent letter / LOI / PPA of 	GUVNL / DISCOM to purchase power from proposed project" class="fa fa-info-circle"></i></label> <?php /*of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP */ ?>
						<?php echo $this->Form->input('Applications.f_sale_discom', array('label' => false, 'type' => 'file', 'id' => 'f_sale_discom', 'class' => 'form-control', 'accept' => '.pdf'));
						?>
						<div id="f_sale_discom-file-errors"></div>
						<?php if (!empty($Applications->f_sale_discom)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->f_sale_discom)) { ?>
							<?php

								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/f_sale_discom/' . encode($Applications->id) . "\">View Sale to DISCOM</a></strong>";
							}
							?>
						<?php endif; ?>
					</div>

				</div>
			</div>
			<?php if ($type == 'wind') { ?>
				<div class="row">
					<div class="form-group">


					</div>
				</div>
			<?php } ?>
			<div class="row">
				<div class="form-group">
					<?php $error_discom = "";
					if (isset($ApplicationError['discom']) && isset($ApplicationError['discom']['_empty']) && !empty($ApplicationError['discom']['_empty'])) {
						$error_discom = "has-error";
					} ?>
					<div class="col-md-3 <?php echo $error_discom; ?>">
						<label>DisCom<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('Applications.discom', $discom_arr, array('label' => false, 'empty' => '-Select DisCom-', 'class' => 'form-control', 'placeholder' => 'DisCom', 'id' => 'discom'));
						if (!empty($error_discom)) {  ?>
							<div class="help-block"><?php echo $ApplicationError['discom']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-2">
						<label>Project State<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.project_state', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Project State', 'readonly' => 'readonly', 'value' => 'Gujarat')); ?>
					</div>
					<div class="col-md-2">
						<label>Village<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.project_village', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Village')); ?>
					</div>
					<div class="col-md-2">
						<label>Taluka<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.project_taluka', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Taluka')); ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['project_district']['_empty']) && !empty($ApplicationError['project_district']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>District <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->select('Applications.project_district', $arrDistictData, array('label' => false, 'class' => 'form-control', 'id' => 'project_district', 'empty' => '-Select District-')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['project_district']['_empty']; ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">

					<div class="col-md-6" id="show_pan_label">
						<label>Expected Annual output of energy from the proposed project in <?php echo ($type == 'groundmounted') ? 'kWh' : 'MWH'; ?> <span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('Applications.project_energy', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Expected Annual output', 'type' => 'text', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>
					<div class="col-md-6">
						<label>Tentative date of commissioning<span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('Applications.comm_date', array('label' => false, 'class' => 'form-control datepicker', 'placeholder' => 'Tentative date of commissioning', 'type' => 'text')); ?>
					</div>

				</div>
			</div>


			<div class="row">
				<div class="form-group">

					<div class="col-md-6" id="show_pan_label">
						<label>Approximate Project Cost (Rs. in lacs)<span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('Applications.project_estimated_cost', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Approximate Project Cost', 'type' => 'text', 'onkeypress' => "return validateDecimal(event)")); ?>
					</div>
					<div class="col-md-6">
						<label>Approximate employment generation from the proposed project (in Nos.)<span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('Applications.approx_generation', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Approximate employment generation', 'type' => 'text', 'onkeypress' => "return validateNumber(event)")); ?>
					</div>

				</div>
			</div>

			<div class="row col-md-12" style="float: right;">
				<?php //if(!in_array($applicationCategory->id,array(5,6))) { ?>
					<div class="col-md-2" align="right" style="float: right;padding-right: 0px !important;">
						<?php echo $this->Form->input('Save & Next', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'next_2', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'next_2')); ?>
					</div>
					<div class="col-md-1" style="float: right;">
						<?php echo $this->Form->input('Save', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'save_2', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'save_2')); ?>
						
					</div>
				<?php /* } else { ?>
					<div class="col-md-2" align="right" style="float: right;padding-right: 0px !important;">
						<?php echo $this->Form->input('Submit', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_3','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_3')); ?>
					</div>
				<?php }*/ ?>
			</div>
		</fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
</div>