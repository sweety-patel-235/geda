<!-- E. List of Document Attached -->

<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php echo $this->Form->create($Applications, ['type' => 'file', 'name' => 'applicationform4', 'id' => 'applicationform4']); ?>
		<input type="hidden" name="tab_id" value="4" />
		<input type="hidden" name="app_dev_id" value="<?php echo $app_dev_id; ?>"/>
		<fieldset>
        <legend>Upload Documents</legend>
			
			<div class="row">
				<div class="form-group" style="margin-bottom: 15px;display: flex;width: 100%;flex-wrap: wrap;">
					
					<div class="col-md-4 wind-upload-file">
						<label>Undertaking / Declaration<span class="mendatory_field">*</span> <i data-content="Undertaking / Declaration on Rs. 300/- non judicial stamp paper regarding newness of the Wind Turbine Generators" class="fa fa-info-circle"></i></label>
						<?php echo $this->Form->input('a_undertaking_dec', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'undertaking_dec', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->undertaking_dec)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->undertaking_dec)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_undertaking_dec/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='undertaking_dec-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>Micro Siting Drawing<span class="mendatory_field">*</span> </label>
						<?php echo $this->Form->input('a_micro_sitting_drawing', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'micro_sitting_drawing', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->micro_sitting_drawing)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->micro_sitting_drawing)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_micro_sitting_drawing/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='micro_sitting_drawing-file-errors'></div>
					</div>
					<?php if($Applications->app_trans_to_stu == 0) { ?>
					<div class="col-md-4 wind-upload-file">
						<label>Proof regarding ownership of the project<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('a_proof_of_ownership', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'proof_of_ownership', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->proof_of_ownership)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->proof_of_ownership)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_proof_of_ownership/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='proof_of_ownership-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>Notarized of purchase contract/work order<span class="mendatory_field">*</span><i data-content="The notarized copy of purchase contract/work order for sale of Wind Power Project"></i></label>
						<?php echo $this->Form->input('a_notarized_contract', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'notarized_contract', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->notarized_contract)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->notarized_contract)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_notarized_contract/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='notarized_contract-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>CA certificate<span class="mendatory_field">*</span><i data-content="CA certificate regarding the ownership of the proposed Wind Power Project"></i></label>
						<?php echo $this->Form->input('a_ca_certificate', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'ca_certificate', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->ca_certificate)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->ca_certificate)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_ca_certificate/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='ca_certificate-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>Invoice with GST<span class="mendatory_field">*</span><i data-content="Copy of invoice with GST or any other ownership proof for purchase of the proposed Wind Power Project"></i></label>
						<?php echo $this->Form->input('a_invoice_with_gst', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'invoice_with_gst', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->invoice_with_gst)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->invoice_with_gst)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_invoice_with_gst/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='invoice_with_gst-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>Share Subscription & Share Holding<span class="mendatory_field">*</span><i data-content="Share Subscription & Share Hlding Agreement between RE Generator and Consumer Holder along with the CA certificate certifying the equity holding of the proposed Wind Power Project for captive use as per the definition no. 6 of clause no. 5 of Gujarat Renewable Energy Policy - 2023"></i></label>
						<?php echo $this->Form->input('a_share_subscription', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'share_subscription', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->share_subscription)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->share_subscription)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_share_subscription/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='share_subscription-file-errors'></div>
					</div>
					<?php }?>
					<div class="col-md-4 wind-upload-file">
						<label>Proposed land is Pvt?<span class="mendatory_field">*</span><i data-content="If the proposed land is Pvt. Land Permission to use the above land for Bonafide Industrial Use/NA Permission/Deemed NA Order. If NA permission is unable to produce at this stage under taking to produce the same prior to the commissioning of the project"></i></label>
						<?php echo $this->Form->input('a_pvt_proposed_land', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'pvt_proposed_land', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->pvt_proposed_land)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->pvt_proposed_land)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_pvt_proposed_land/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='pvt_proposed_land-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>Project Sale to DISCOM than No Due Certificate<span class="mendatory_field">*</span><i data-content="If end use of power generated from proposed Wind power project is for sale to DISCOM than, 'No Due Certificate' for the project site and developer from the concern DISCOM, within whose jurisdiction of the RE project is to be installed."></i></label>
						<?php echo $this->Form->input('a_proj_sale_to_discom_no_due', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'proj_sale_to_discom_no_due', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->proj_sale_to_discom_no_due)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->proj_sale_to_discom_no_due)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_proj_sale_to_discom_no_due/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='proj_sale_to_discom_no_due-file-errors'></div>
					</div>
					<div class="col-md-4 wind-upload-file">
						<label>Project is for Captive Use & Third Party Sale<span class="mendatory_field">*</span><i data-content="If end use of power generated from proposed Wind power project is for Captive Use & Third Part Sale than, 'No Due Certificate' for the project site and developer from the concern DISCOM, within whose jurisdiction of the RE project is to be installed."></i></label>
						<?php echo $this->Form->input('a_proj_captive_use_no_due', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'proj_captive_use_no_due', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
						<?php if (!empty($Applications->proj_captive_use_no_due)) : ?>
							<?php if ($Couchdb->documentExist($Applications->id, $Applications->proj_captive_use_no_due)) { ?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_proj_captive_use_no_due/' . encode($Applications->id) . "\">View Copy of Doc</a></strong>";
							}
							?>
						<?php endif; ?>
						<div id='proj_captive_use_no_due-file-errors'></div>
					</div>
					
				</div>
			</div>
			<br>
					
			<div class="row col-md-12">
				<div class="col-md-1">
					<?php echo $this->Form->input('Save', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'save_4', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'save_4')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('Save & Next', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'next_4', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'next_4')); ?>
				</div>
			</div>
            </fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
</div>