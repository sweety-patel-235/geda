<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		
		<?php echo $this->Form->create($Applications, ['type' => 'file', 'name' => 'applicationform1', 'id' => 'applicationform1']); ?>
		<input type="hidden" name="tab_id" value="1" />
		<?php if(isset($app_dev_id)){ ?>
		<input type="hidden" name="app_dev_id" value="<?php echo $app_dev_id; ?>"/>
		<?php } ?>
		<fieldset>
			<legend>General Profile</legend>

			<div class="row">
				<div class="form-group">
					<div class="col-md-3">
						<label>Name of Applicant Company<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('name_of_applicant', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Name of Applicant Company', 'id' => 'name_of_consumer_applicant', 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3">
						<label>Street/House no<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('address1', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Street/House no', 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3">
						<label>Taluka/Village<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('taluka', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Taluka/Village', 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3">
						<label>City<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('city', array('label' => false, 'class' => 'form-control', 'placeholder' => 'City', 'id' => 'city', 'disabled' => 'disabled')); ?>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<?php
					$error_class = isset($ApplicationError['state']['_empty']) && !empty($ApplicationError['state']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>State *</label>
						<?php
						echo $this->Form->select('state', $arrStateData, array('label' => false, 'class' => 'form-control', 'id' => 'state', 'empty' => '-Select State-', 'disabled' => 'disabled')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['state']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
					$error_class = isset($ApplicationError['district']['_empty']) && !empty($ApplicationError['district']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">
						<label>District <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->select('district', $arrDistictData, array('label' => false, 'class' => 'form-control', 'id' => 'district', 'empty' => '-Select District-', 'disabled' => 'disabled')); ?>
						<?php if (!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['district']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3">
						<label>Pincode<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('pincode', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Pincode', 'id' => 'pincode', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3">
						<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
						<?php echo $this->Form->input('contact', array('label' => false, 'class' => 'form-control', 'placeholder' => 'WhatsApp No.', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", "maxlength" => "10", 'disabled' => 'disabled')); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">

					<div class="col-md-3">
						<label>Consumer Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('mobile', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Consumer Mobile', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", "maxlength" => "10", 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3">
						<label>Consumer Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('email', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Consumer Email', 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3" id="show_pan_label">
						<label>PAN card no.<span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('pan', array('label' => false, 'class' => 'form-control', 'placeholder' => 'PAN card no.', 'type' => 'text', 'maxlength' => 10, 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-3" id="show_pan_text">
						<label>PAN card<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('f_pan_card', array('label' => false, 'type' => 'file', 'id' => 'f_pan_card', 'class' => 'form-control', 'placeholder' => 'Comunication Address', 'accept' => '.pdf', 'disabled' => 'disabled')); ?>
						<div id="f_pan_card-file-errors"></div>

						<?php if (!empty($Applications->pan_card)) { ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->pan_card)) { ?>

								<?php
								if(isset($Applications->application_id))
								echo "<strong><a target=\"_PANCARD\" href=\"" . URL_HTTP . 'app-docs/p_pan_card/' . encode($Applications->application_id) . "\">View PanCard</a></strong>";
								else
								echo "<strong><a target=\"_PANCARD\" href=\"" . URL_HTTP . 'app-docs/p_pan_card/' . encode($Applications->id) . "\">View PanCard</a></strong>";
								?>

							<?php } ?>
						<?php } ?>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="form-group">					
					<div class="col-md-3">
						<label>GST No. of Consumer</label>
						<?php echo $this->Form->input('GST', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => 'GST Number.', 'disabled' => 'disabled')); ?>
					</div>
					<?php $error_class_typeapplicant = "";
					if (isset($ApplicationError['type_of_applicant']) && isset($ApplicationError['type_of_applicant']['_empty']) && !empty($ApplicationError['type_of_applicant']['_empty'])) {
						$error_class_typeapplicant 	= "has-error";
					}
					?>
					<div class="col-md-3 <?php echo $error_class_typeapplicant; ?>"><label>Type of Applicant<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_of_applicant', $type_of_applicant, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Select Type of Applicant-', 'id' => 'type_of_applicant', 'onChange' => 'javascript:ShowHideOthers();', 'disabled' => 'disabled'));
						if (!empty($error_class_typeapplicant)) {  ?>
							<div class="help-block"><?php echo $ApplicationError['type_of_applicant']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3 applicant_others"><label>Applicant Type Other<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('applicant_others', array('label' => false, 'class' => '', 'id' => 'applicant_others', 'disabled' => 'disabled'));
						?>
					</div>
					<div class="col-md-3 div_contract_load_more">
						<label>Enclose self-certified copy <span class="mendatory_field">*</span>&nbsp;<i data-content="Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP" class="fa fa-info-circle"></i></label> <?php /*of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP */ ?>
						<?php echo $this->Form->input('f_registration_document', array('label' => false, 'type' => 'file', 'id' => 'f_registration_document', 'class' => 'form-control', 'accept' => '.pdf', 'disabled' => 'disabled'));
						?>
						<div id="f_registration_document-file-errors"></div>

						<?php if (!empty($Applications->registration_document)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->registration_document)) { ?>
							<?php
								if(isset($Applications->application_id))
									echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_registration_document/' . encode($Applications->application_id) . "\">View Upload Certificate</a></strong>";
								else
									echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_registration_document/' . encode($Applications->id) . "\">View Upload Certificate</a></strong>";
							}
							?>
						<?php endif; ?>
					</div>

				</div>
			</div>
			
			<div style="clear: both;"></div>


			<div class="row">
				<div class="form-group">
					<div class="col-md-3"><label>Is the Applicant a MSME?</label>
						<?php
						echo $this->Form->input('msme', [
							'type' => 'radio',
							'label' => false,
							'div' => false,
							'options' => [
								['value' => 1, 'text' => "Yes", "class" => "msme", "onclick" => "javascript:toggel_msme();"],
								['value' => 0, 'text' => "No", "class" => "msme", "onclick" => "javascript:toggel_msme();"]
							],
							'templates' => [
								'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
							],
							'before' => '',
							'separator' => '',
							'after' => '', 'disabled' => 'disabled'
						]);
						?>
					</div>
					<div class="col-md-3 msme_file">
						<label>MSME<span class="mendatory_field">*</span></label>
						<div class="file-loading">
							<?php echo $this->Form->input('app_msme', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'app_msme', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf', 'disabled' => 'disabled')); ?>
						</div>
						<div id="app_msme-file-errors"></div>
						<?php
						if (!empty($Applications->a_msme)) :  ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->a_msme)) {
								if(isset($Applications->application_id))
									echo "<br/><strong><a target=\"_RECENTBILL\" href=\"" . URL_HTTP . 'app-docs/p_a_msme/' . encode($Applications->application_id) . "\">View MSME Document</a></strong>";
								else
								echo "<br/><strong><a target=\"_RECENTBILL\" href=\"" . URL_HTTP . 'app-docs/p_a_msme/' . encode($Applications->id) . "\">View MSME Document</a></strong>";
							}
							?>
						<?php endif; ?>
					</div>
					<div class="col-md-3">
						<label class="lbl_comunication_address">
							Upload Undertaking form<span class="mendatory_field">*</span></label>
						<div class="file-loading">
							<?php echo $this->Form->input('a_upload_undertaking', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'a_upload_undertaking', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf', 'disabled' => 'disabled')); ?>
						</div>
						<a href="/Undertaking_by_REG.docx" style="text-decoration: underline;"><strong>[Download Undertaking Format]</strong></a>
						<div id="a_upload_undertaking-file-errors"></div>
						<?php
						if (!empty($Applications->upload_undertaking)) :  ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->upload_undertaking)) {
								if(isset($Applications->application_id))
									echo "<br/><strong><a target=\"_RECENTBILL\" href=\"" . URL_HTTP . 'app-docs/p_upload_undertaking/' . encode($Applications->application_id) . "\">View Undertaking form</a></strong>";
								else
								echo "<br/><strong><a target=\"_RECENTBILL\" href=\"" . URL_HTTP . 'app-docs/p_upload_undertaking/' . encode($Applications->id) . "\">View Undertaking form</a></strong>";	
							}
							?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-6"><label>Name of the Managing Director / Chief Executive of the Company<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('name_director', array('label' => false, 'class' => '', 'id' => 'name_director', 'placeholder' => 'Name of the Managing Director', 'disabled' => 'disabled'));
						?>
					</div>
					<?php
					$error_class = isset($ApplicationError['type_director']['_empty']) && !empty($ApplicationError['type_director']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>">

						<label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_director', $designation, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Designation-', 'id' => 'type_director', 'onChange' => "javascript:ShowHideDesignationOthers('type_director');", 'disabled' => 'disabled'));
						if (!empty($error_class)) {  ?>
							<div class="help-block"><?php echo $ApplicationError['type_director']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3" id="div_type_director"><label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('type_director_others', array('label' => false, 'class' => '', 'id' => 'type_director_others', 'disabled' => 'disabled'));
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
						<?php echo $this->Form->input('director_whatsapp', array('label' => false, 'class' => 'form-control', 'placeholder' => 'WhatsApp No.', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", 'maxlength' => '10', 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-4">
						<label>Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('director_mobile', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Mobile', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", "maxlength" => "10", 'disabled' => 'disabled')); ?>
					</div>
					<div class="col-md-4">
						<label>Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('director_email', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Email', 'disabled' => 'disabled')); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<div class="col-md-4"><label>Name of the authorized Signatory<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('name_authority', array('label' => false, 'class' => 'onlycharacter', 'id' => 'name_authority', 'placeholder' => 'Name of the authorized Signatory'));
						?>
					</div>
					<?php
					$error_class = isset($ApplicationError['type_authority']['_empty']) && !empty($ApplicationError['type_authority']['_empty']) ? 'has-error' : '';
					?>
					<div class="col-md-3 <?php echo $error_class; ?>"><label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('type_authority', $designation, array('label' => false, 'class' => 'change_customer_type form-control', 'empty' => '-Designation-', 'id' => 'type_authority', 'onChange' => "javascript:ShowHideDesignationOthers('type_authority');"));
						if (!empty($error_class)) {  ?>
							<div class="help-block"><?php echo $ApplicationError['type_authority']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3" id="div_type_authority"><label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('type_authority_others', array('label' => false, 'class' => '', 'id' => 'type_authority_others', 'disabled' => 'disabled'));
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">

					<label class="col-md-8">Copy of Board resolution authorizing person for signing all the documents related to proposed project<span class="mendatory_field">*</span></label>
					<div class="col-md-4">
						<?php echo $this->Form->input('f_file_board', array('label' => false, 'type' => 'file', 'id' => 'f_file_board', 'class' => 'form-control', 'placeholder' => 'Recent Bill.', 'accept' => '.pdf', 'disabled' => 'disabled')); ?>
						<div id="f_file_board-file-errors"></div>

						<?php if (!empty($Applications->d_file_board)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->d_file_board)) { ?>
								
							<?php
								if(isset($Applications->application_id))
									echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_d_file_board/' . encode($Applications->application_id) . "\">View Copy of Board</a></strong>";
								else
									echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_d_file_board/' . encode($Applications->id) . "\">View Copy of Board</a></strong>";
								$hiddenVal = $Applications->d_file_board;
							}
							?>
						<?php endif; ?>
					</div>

				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
						<?php echo $this->Form->input('authority_whatsapp', array('label' => false, 'class' => 'form-control', 'id'=>'authority_whatsapp','placeholder' => 'WhatsApp No.', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", 'maxlength' => "10",'minlength' => "10")); ?>
					</div>
					<div class="col-md-4">
						<label>Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('authority_mobile', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Mobile','id'=>'authority_mobile', "onkeyup" => "if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}", "maxlength" => "10", 'minlength' => "10",'type' => 'text')); ?>
					</div>
					<div class="col-md-4">
						<label>Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('authority_email', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
					</div>
				</div>
			</div>
			<?php //if($type != 'hybridDSS') { 
			?>
			<div class="row col-md-12">
				<div class="col-md-1">
					<?php echo $this->Form->input('Save', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'save_1', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'save_1')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('Save & Next', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'next_1', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'next_1')); ?>
				</div>
			</div>
			<?php //} 
			?>

		</fieldset>

		<?php echo $this->Form->end(); ?>
	</div>
</div>