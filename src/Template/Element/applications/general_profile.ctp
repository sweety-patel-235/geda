<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php 	
				$strHide 		= (isset($Applications->id) && !empty($Applications->id) && !in_array($applicationCategory->id,array(5,6)) && isset($Applications->map_workorder_id) && !empty($Applications->map_workorder_id)) ? '' :'hide';
				$behalfHide 	= (isset($Applications->id) && !empty($Applications->id)) ? 'hide' :'';
				$disabled 		= (isset($Applications->map_workorder_id) && !empty($Applications->map_workorder_id)) ? "disabled" :'';
				$checked 		= (isset($Applications->map_workorder_id) && !empty($Applications->map_workorder_id)) ? "checked" :'';
				$category_kusum = ((isset($Applications->id) && !empty($Applications->id)) || in_array($applicationCategory->id,array(5,6))) ? "hide" :'';
				$type_kusum_sel = (!in_array($applicationCategory->id,array(5,6))) ? "hide" :'';
				$strGenProfile 	= ((isset($Applications->id) && !empty($Applications->id)) || in_array($applicationCategory->id,array(5,6))) ? '' :'hide';

				?>
		<fieldset class="<?php echo $category_kusum;?><?php //echo $behalfHide;?>">
			<legend>Application Behalf on</legend>
			<div class="row ">
				<div class="form-group">
					<div class="col-md-4">
						<?php
						echo $this->Form->input('Applications.myself', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 0, 'text' =>"My Project","class"=>"selectDeveloper","onclick"=>"javascript:toggle_developer();",$checked,$disabled],
													['value' => 1, 'text' =>"Assigned Project","class"=>"selectDeveloper","onclick"=>"javascript:toggle_developer();",$checked,$disabled]
												],
												'templates' => [
															'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
														],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="hide ">
			<legend>Select Developer</legend>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<?php echo $this->Form->select('ApplyOnlines.map_developer_id',$developer_list, array('label' => false,'class'=>'form-control chosen-select','placeholder'=>'-Select Developer-','empty'=>'-Select Developer-')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('Send OTP',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyEmailOtpForm']); ?>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="<?php echo $strHide;?> select_developer">
			<legend><?php echo empty($strHide) ? 'Selected' : 'Select'; ?> Work Order</legend>
			<?php echo $this->Form->create($Applications,['type'=>'file','name'=>'applicationmapform1','id'=>'applicationmapform1']);?>
				<div class="row">
					<div class="form-group">
						<div class="col-md-4">
							<input type="hidden" name="tab_id" value="1" />
							<?php echo $this->Form->select('Applications.map_workorder_id',$assigned_workorder_list, array('label' => false,'class'=>'form-control chosen-select','placeholder'=>'-Select Work Order-','empty'=>'-Select Work Order-',$disabled)); ?>
						</div>
						<div class="col-md-3 <?php echo $behalfHide;?>">
							<?php echo $this->Form->input('Submit',['type'=>'submit','id'=>'','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
				</div>
			<?php echo $this->Form->end(); ?>
		</fieldset>

		<?php //select_developer;?>
		<?php echo $this->Form->create('VarifyEmailOtpForm',['name'=>'VarifyEmailOtpForm','id'=>'VarifyEmailOtpForm']); ?>
			<fieldset class="hide">
				<legend>Email OTP Verification</legend>
				<div class="row">
					<div class="form-group">
						
						<div class="col-md-12">
							<div id="message_error"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						
						<div class="col-md-4 cinput">
							<?php echo $this->Form->input('otp',["class" =>"form-control",
																			'id'=>'otp',
																			'label' => false,
																			"placeholder" => "Enter Email OTP"
																]);
							?>
						</div>
						<div class="col-md-2">
						<?php echo $this->Form->input('Verify OTP',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary varifythirdpartyotp_btn','data-form-name'=>'VarifyEmailOtpForm']); ?>
						</div>
						<div class="col-md-2">
						<?php echo $this->Form->input('Resend OTP',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary resendemailotp_btn','data-form-name'=>'VarifyEmailOtpForm']); ?>
						</div>
					</div>
				</div>
			</fieldset>
		<?php echo $this->Form->end(); ?>
				
		<?php echo $this->Form->create($Applications,['type'=>'file','name'=>'applicationform1','id'=>'applicationform1','class'=>'application-from']);?>
		<input type="hidden" name="tab_id" value="1" />
		<fieldset class="<?php echo $type_kusum_sel;?><?php //echo $behalfHide;?>">
			<legend>KUSUM Type</legend>
			<div class="row ">
				<div class="form-group">
					<div class="col-md-4">
						<?php //echo $this->Form->select('ApplyOnlines.kusum_type',array("1"=>"KUSUM A","2"=>"KUSUM C"), array('label' => false,'class'=>'form-control chosen-select')); ?>
						<?php
						echo $this->Form->input('Applications.kusum_type', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													//['value' => 1, 'text' =>"KUSUM A"],
													['value' => 2, 'text' =>"KUSUM C"]
												],
												'templates' => [
															'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
														],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="<?php echo $strGenProfile;?> gen_profile" >
			<legend>General Profile</legend>	
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>Name of Applicant Company<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.name_of_applicant', array('label' => false,'class'=>'form-control','placeholder'=>'Name of Applicant Company','id'=>'name_of_consumer_applicant',$disabled)); ?>
					</div>
					<div class="col-md-4 hide">
						<label>Address of Registered Office<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.address', array('label' => false,'class'=>'form-control','id'=>'address1','placeholder'=>'Address of Registered Office','id'=>'comm_address',$disabled)); ?>
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>Street/House no<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.address1', array('label' => false,'class'=>'form-control','placeholder'=>'Street/House no',$disabled)); ?>
					</div>
					<div class="col-md-4">
						<label>Taluka/Village<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.taluka', array('label' => false,'class'=>'form-control','placeholder'=>'Taluka/Village',$disabled)); ?>
					</div>
					<div class="col-md-4">
						<label>City<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.city', array('label' => false,'class'=>'form-control','placeholder'=>'City','id'=>'comm_address',$disabled)); ?>
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<?php
						$error_class = isset($ApplicationError['state']['_empty']) && !empty($ApplicationError['state']['_empty']) ? 'has-error' : ''; 
					?>
					<div class="col-md-4 <?php echo $error_class;?>">
						<label>State *</label>
						<?php
						echo $this->Form->select('Applications.state',$arrStateData,array('label' => false,'class'=>'form-control','id'=>'state','empty'=>'-Select State-',$disabled)); ?>
						<?php if(!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['state']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php
						$error_class = isset($ApplicationError['district']['_empty']) && !empty($ApplicationError['district']['_empty']) ? 'has-error' : ''; 
					?>
					<div class="col-md-4 <?php echo $error_class;?>">
						<label>District <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->select('Applications.district',$arrDistictData,array('label' => false,'class'=>'form-control','id'=>'district','empty'=>'-Select District-',$disabled)); ?>
						<?php if(!empty($error_class)) { ?>
							<div class="help-block"><?php echo $ApplicationError['district']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label>Pincode<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.pincode', array('type'=>'text','label' => false,'class'=>'form-control','placeholder'=>'Pincode','id'=>'pincode',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}",$disabled)); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
						 <?php echo $this->Form->input('Applications.contact', array('label' => false,'class'=>'form-control','placeholder'=>'WhatsApp No.',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10",$disabled)); ?>
					</div>
					<div class="col-md-4">
						<label>Consumer Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10",$disabled)); ?>
					</div>
					<div class="col-md-4">
						<label>Consumer Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.email', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Email',$disabled)); ?>
					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="form-group">
			
						<div class="col-md-4" id="show_pan_label">
									<label>PAN card no.<span class="mendatory_field">*</span> </label>
									<?php echo $this->Form->input('Applications.pan', array('label' => false,'class'=>'form-control','placeholder'=>'PAN card no.','type'=>'text','maxlength'=>10,$disabled)); ?>
						</div>
						<div class="col-md-4" id="show_pan_text">
							<label>PAN card<span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('Applications.f_pan_card', array('label' => false,'type'=>'file','id'=>'f_pan_card','class'=>'form-control','placeholder'=>'Comunication Address','accept'=>'.pdf',$disabled)); ?>
							<div id="f_pan_card-file-errors"></div>
							<?php /*<br/>*/?>
							<?php if(!empty($Applications->pan_card)) { ?>
								<?php if ($Couchdb->documentExist($Applications->id,$Applications->pan_card)) { ?>
									
										<?php
											echo "<strong><a target=\"_PANCARD\" href=\"".URL_HTTP.'app-docs/a_pan_card/'.encode($Applications->id)."\">View PanCard</a></strong>";
										?>
									
								<?php } ?>
							 <?php } ?>
						</div>
						<div class="col-md-4">
							<label>GST No. of Consumer</label>
							<?php echo $this->Form->input('Applications.GST', array('type' => 'text','label' => false,'class'=>'form-control','placeholder'=>'GST Number.',$disabled)); ?>
						</div>
					
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<?php $error_class_typeapplicant = "";
					if(isset($ApplicationError['type_of_applicant']) && isset($ApplicationError['type_of_applicant']['_empty']) && !empty($ApplicationError['type_of_applicant']['_empty'])) {
						$error_class_typeapplicant 	= "has-error";

					}
					?>
					<div class="col-md-4 <?php echo $error_class_typeapplicant; ?>"><label>Type of Applicant<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('Applications.type_of_applicant',$type_of_applicant, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Select Type of Applicant-','id'=>'type_of_applicant','onChange'=>'javascript:ShowHideOthers();',$disabled));
						if(!empty($error_class_typeapplicant)){  ?>
							<div class="help-block"><?php echo $ApplicationError['type_of_applicant']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4 applicant_others"><label>Applicant Type Other<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.applicant_others',array('label' => false,'class'=>'','id'=>'applicant_others',$disabled));
						?>
					</div>	
					<div class="col-md-4 div_contract_load_more">
						<label>Enclose self-certified copy <span class="mendatory_field">*</span>&nbsp;<i data-content="Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP" class="fa fa-info-circle"></i></label> <?php /*of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP */?>
						<?php echo $this->Form->input('Applications.f_registration_document', array('label' => false,'type'=>'file','id'=>'f_registration_document','class'=>'form-control','accept'=>'.pdf',$disabled));
						?>
						<div id="f_registration_document-file-errors"></div>
						<?php /*<br/>*/?>
						<?php if(!empty($Applications->registration_document)) : ?>
							<?php if($Couchdb->documentExist($Applications->id,$Applications->registration_document)) { ?>
								<?php
									echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/a_registration_document/'.encode($Applications->id)."\">View Upload Certificate</a></strong>";
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
						echo $this->Form->input('Applications.msme', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"msme","onclick"=>"javascript:toggel_msme();"],
													['value' => 0, 'text' =>"No","class"=>"msme","onclick"=>"javascript:toggel_msme();"]
												],
												'templates' => [
															'radioWrapper' => '<div class="radio-inline screen-center screen-radio" style="margin-top:0px;">{{label}}</div>'
														],
												'before' => '',
												'separator' => '',
												'after' => '',$disabled
											]);
						?>
					</div>	
					<div class="col-md-3 msme_file">
						<label>MSME<span class="mendatory_field">*</span></label>
						<div class="file-loading" >
							<?php echo $this->Form->input('Applications.app_msme', array('label' => false,'div' => false,'type'=>'file','id'=>'app_msme','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf',$disabled)); ?>
						</div>
						<div id="app_msme-file-errors"></div>
						<?php
						if(!empty($Applications->a_msme)) :  ?>
							<?php if ($Couchdb->documentExist($Applications->id,$Applications->a_msme)) { 
								echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/a_msme/'.encode($Applications->id)."\">View MSME Document</a></strong>";
							 	} 
							?>
						<?php endif; ?>
					</div>
					<!-- <div class="col-md-3">
						<label class="lbl_comunication_address">
						Upload Undertaking form<span class="mendatory_field">*</span></label>
						<br/>
						<div class="file-loading" >
							<?php //echo $this->Form->input('Applications.a_upload_undertaking', array('label' => false,'div' => false,'type'=>'file','id'=>'a_upload_undertaking','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf'));  //,$disabled?>
						</div>
						<a href="/Undertaking_by_REG.docx" style="text-decoration: underline;"><strong>[Download Undertaking Format]</strong></a>
						<div id="a_upload_undertaking-file-errors"></div>
						<?php /*
						if(!empty($Applications->upload_undertaking)) :  ?>
							<?php if ($Couchdb->documentExist($Applications->id,$Applications->upload_undertaking)) { 
								echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/a_upload_undertaking/'.encode($Applications->id)."\">View Undertaking form</a></strong>";
							 	} 
							?>
						<?php endif; */ ?>
					</div> -->
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-6"><label>Name of the Managing Director / Chief Executive of the Company<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.name_director',array('label' => false,'class'=>'','id'=>'name_director','placeholder'=>'Name of the Managing Director'));
						?>
					</div>	
					<?php
						$error_class = isset($ApplicationError['type_director']['_empty']) && !empty($ApplicationError['type_director']['_empty']) ? 'has-error' : ''; 
					?>
					<div class="col-md-3 <?php echo $error_class;?>">
					
						<label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('Applications.type_director',$designation, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Designation-','id'=>'type_director','onChange'=>"javascript:ShowHideDesignationOthers('type_director');"));
						if(!empty($error_class)){  ?>
							<div class="help-block"><?php echo $ApplicationError['type_director']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3" id="div_type_director"><label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.type_director_others',array('label' => false,'class'=>'','id'=>'type_director_others'));
						?>
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
						<?php echo $this->Form->input('Applications.director_whatsapp', array('label' => false,'class'=>'form-control','placeholder'=>'WhatsApp No.',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}",'maxlength'=>'10')); ?>
					</div>
					<div class="col-md-4">
						<label>Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.director_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
					</div>
					<div class="col-md-4">
						<label>Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.director_email', array('label' => false,'class'=>'form-control','placeholder'=>'Email')); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<div class="col-md-6"><label>Name of the authorized Signatory<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.name_authority',array('label' => false,'class'=>'','id'=>'name_authority','placeholder'=>'Name of the authorized Signatory'));
						?>
					</div>	
					<?php
						$error_class = isset($ApplicationError['type_authority']['_empty']) && !empty($ApplicationError['type_authority']['_empty']) ? 'has-error' : ''; 
					?>
					<div class="col-md-3 <?php echo $error_class;?>"><label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('Applications.type_authority',$designation, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Designation-','id'=>'type_authority','onChange'=>"javascript:ShowHideDesignationOthers('type_authority');"));
						if(!empty($error_class)){  ?>
							<div class="help-block"><?php echo $ApplicationError['type_authority']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-3" id="div_type_authority"><label>Designation<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.type_authority_others',array('label' => false,'class'=>'','id'=>'type_authority_others'));
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
		
					<label class="col-md-8">Copy of Board resolution authorizing person for signing all the documents related to proposed project<span class="mendatory_field">*</span></label>
					<div class="col-md-4">
						<?php echo $this->Form->input('Applications.f_file_board', array('label' => false,'type'=>'file','id'=>'f_file_board','class'=>'form-control','placeholder'=>'Recent Bill.','accept'=>'.pdf')); ?>
						<div id="f_file_board-file-errors"></div>

					<?php if(!empty($Applications->d_file_board)) : ?>
						<?php if($Couchdb->documentExist($Applications->id,$Applications->d_file_board)) { ?>
							<?php /*<br/>*/?>
							<?php
								echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/a_file_board/'.encode($Applications->id)."\">View Copy of Board</a></strong>";
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
						<?php echo $this->Form->input('Applications.authority_whatsapp', array('label' => false,'class'=>'form-control','placeholder'=>'WhatsApp No.',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}",'maxlength'=>10)); ?>
					</div>
					<div class="col-md-4">
						<label>Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.authority_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10",'type'=>'text')); ?>
					</div>
					<div class="col-md-4">
						<label>Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('Applications.authority_email', array('label' => false,'class'=>'form-control','placeholder'=>'Email')); ?>
					</div>
				</div>
			</div>
			
				<div class="row col-md-12" style="float: right;">
					<div class="col-md-2" align="right" style="float: right;padding-right: 0px !important;" >
						<?php echo $this->Form->input('Save & Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_1','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_1')); ?>
					</div>
					<div class="col-md-1"  style="float: right;" >
						<?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_1','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_1')); ?>
					</div>
					
				</div>
			
			
		</fieldset>
		
		<?php echo $this->Form->end(); ?>
	</div>
</div>
