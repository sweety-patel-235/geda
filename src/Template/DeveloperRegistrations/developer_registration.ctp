<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<?php
	$this->Html->addCrumb($pageTitle); 
	$this->Form->create($developer);
?>
<style type="text/css">
 /* http://docs.jquery.com/UI/Autocomplete#theming*/
.ui-autocomplete { position: absolute; cursor: default; background:#CCC }   

/* workarounds */
html .ui-autocomplete { width:1px; } /* without this, the menu expands to 100% in IE6 */
.ui-menu {
	list-style:none;
	padding: 2px;
	margin: 0;
	display:block;
	float: left;
}
.ui-menu .ui-menu {
	margin-top: -3px;
}
.ui-menu .ui-menu-item {
	margin:0;
	padding: 0;
	zoom: 1;
	float: left;
	clear: left;
	width: 100%;
}
.ui-menu .ui-menu-item a {
	text-decoration:none;
	display:block;
	padding:.2em .4em;
	line-height:1.5;
	zoom:1;
}
.ui-menu .ui-menu-item a.ui-state-hover,
.ui-menu .ui-menu-item a.ui-state-active {
	font-weight: normal;
	margin: -1px;
}
.check-box-address{
	/*margin-top: 20px !important;*/
}
.checkbox input[type="checkbox"]{
    width: 18px;
    float: left;
    margin-top: -37px !important;
}
.btn-primary:focus {
    border-color: #71BF57;
    background-color: #71BF57;
    color: white;
}
.btn_radius {
	border-radius: 10px !important;
}
.radio {
	margin-bottom: 0px !important;
	margin-top: 0px !important;
}
</style>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<p>
				It is suggested to kindly check the GST number twice before entering. The same will not be changed once the application is processed. GST credit will not be passed on to those who have not entered the correct GST number.
				</p>
				<p>It is in the interest of the RE Developer to provide as much details as possible. The email ID and mobile no. mentioned here in the form shall be used for all further communication related to this Portal. The documents to be uploaded shall be of max. 1 MB.</p>
				<p>The login shall be generated and emailed to you on your registered email ID upon the approval from GEDA Office.</p>
				<p><strong>1) To edit your Login Form, kindly enter the Mandatory Fields -  E-mail, Mobile, and PAN </strong><br>
				<strong>2) To Pay the fees kindly enter the Mandatory Fields -  E-mail, Mobile, and PAN to open your saved Login Form </strong></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><h4><strong><?php echo $pageTitle;?></strong></h4></div>
		</div>
		<div class="row" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
			<div class="col-md-12">
				<?php echo $this->Form->create($installer_detail,['type'=>'file','name'=>'installerReg','id'=>'installerReg', 'url' => '/'.$RouteURL.'/'.$company_id]);?>
				<?php echo $this->Form->hidden('Developers.selected_category',array("value"=>"","id"=>"selected_category")); ?>
				<fieldset>
					<legend><div class="col-md-4 col-sm-4 col-lg-4">Developer Registration Form </div>
						<div class="col-md-8 col-sm-8 col-lg-8">
							<?php if(!empty($applicationCategoryData)) {
								$arrSelected 	= isset($installer_detail->selected_category) ? explode(",",$installer_detail->selected_category) : array();
								foreach($applicationCategoryData as $applicationCategory) {
									
									$classApplied 	= (isset($installer_detail->selected_category) && in_array($applicationCategory->id,$arrSelected)) ? 'btn-primary' : (!isset($installer_detail->selected_category) ? (($applicationCategory->id ==  1) ? 'btn-primary' : 'btn-default') : 'btn-default');
									?>
									<button type="button" class="btn <?php echo $classApplied;?> btn-sm btn_radius" id="cat_<?php echo $applicationCategory->id;?>" onClick="chkCategory('<?php echo $applicationCategory->id;?>');" data-amount='<?php echo $applicationCategory->developer_charges;?>' data-tax='<?php echo $applicationCategory->developer_tax_percentage;?>' ><?php echo  ($applicationCategory->id ==1) ? 'RESCO' : $applicationCategory->category_name;?></button> 
									<?php 
								} if(isset($InstallerErrors['selected_category']['_empty']) && !empty($InstallerErrors['selected_category']['_empty'])) { ?>
									<div class="has-error" style="font-size:14px;">
										<div class="help-block"><?php echo $InstallerErrors['selected_category']['_empty']; ?></div>
									</div>

									<?php }

							} ?>
						</div>
						
					</legend>
					<div class="row hide">
						<div class="form-group">
							<div class="col-md-3 col-sm-3 col-lg-3">
							<?php
								echo $this->Form->input('Developers.form_type', [
														'type' => 'radio',
														'label' => false,
														'div' => false,
														'options' => [
															['value' => 1, 'text' =>"New","class"=>"","onclick"=>"javascript:basicForm(1)","checked"=>"checked"],
															['value' => 0, 'text' =>"Existing","class"=>"","onclick"=>"javascript:basicForm(0)"]
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
					<div class="row">
						<div class="form-group">
							<div class="col-md-4 cinput">
								<label>Name of the Developer Company  *</label>
								<?php echo $this->Form->input('Developers.installer_name',array('id'=>"company_name",'label' => false,'class'=>'form-control','placeholder'=>'Name of the Developer Company')); ?>
								<?php echo $this->Form->hidden('Developers.company_id',array('id'=>"company_id","value"=>decode($company_id))); ?>
							</div>
							<div class="col-md-4">
								<label>Name of the Authorized Person of Developer *</label>
								<?php echo $this->Form->input('Developers.contact_person',array('label' => false,'class'=>'form-control','placeholder'=>'Name of the Authorized Person of Developer')); ?>
							</div>
							<div class="col-md-4">
								<label>Address of Registered Office *</label>
								<?php
								echo $this->Form->input('Developers.address',array('label' => false,'class'=>'form-control','placeholder'=>'Address of Registered Office')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>Street/House no<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.address1', array('label' => false,'class'=>'form-control','id'=>'address1','placeholder'=>'Street/House no')); ?>
							</div>
							<div class="col-md-4">
								<label>Taluka/Village<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.taluka', array('label' => false,'class'=>'form-control','placeholder'=>'Taluka/Village')); ?>
							</div>
							<div class="col-md-4">
								<label>City *</label>
								<?php
								echo $this->Form->input('Developers.city',array('label' => false,'class'=>'form-control','placeholder'=>'City')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							
							<?php
								$error_class = isset($InstallerErrors['state']['_empty']) && !empty($InstallerErrors['state']['_empty']) ? 'has-error' : ''; 
							?>
							<div class="col-md-4 <?php echo $error_class;?>">
								<label>State *</label>
								<?php
								echo $this->Form->select('Developers.state',$arrStateData,array('label' => false,'class'=>'form-control','id'=>'state','empty'=>'-Select State-')); ?>
								<?php if(!empty($error_class)) { ?>
									<div class="help-block"><?php echo $InstallerErrors['state']['_empty']; ?></div>
								<?php } ?>
							</div>
							<?php
								$error_class = isset($InstallerErrors['district']['_empty']) && !empty($InstallerErrors['district']['_empty']) ? 'has-error' : ''; 
							?>
							<div class="col-md-4 <?php echo $error_class;?>">
								<label>District *</label>
								<?php
								echo $this->Form->select('Developers.district',$arrDistictData,array('label' => false,'class'=>'form-control','id'=>'district','empty'=>'-Select District-')); ?>
								<?php if(!empty($error_class)) { ?>
									<div class="help-block"><?php echo $InstallerErrors['district']['_empty']; ?></div>
								<?php } ?>
							</div>
							<div class="col-md-4">
								<label>PIN Code *</label>
								<?php echo $this->Form->input('Developers.pincode',array('label' => false,'class'=>'form-control','placeholder'=>'PIN Code',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}",'maxlength'=>'6')); ?>
							</div>
						</div>
					</div>
					
					<?php $fields_readonly = array(); 
					if(isset($installer_detail->payment_status) && $installer_detail->payment_status==1) { $fields_readonly['readonly'] = 'readonly'; } ?>
					<div class="row">
						<div class="form-group">
							<div class="col-md-3">
								<label>Email *</label>
								<?php

								echo $this->Form->input('Developers.email',array('data-msg-email'=>"Please enter a valid email address.", 'data-msg-required'=>"Please enter your email address.",'type'=>"email",'label' => false,'class'=>'form-control',$fields_readonly,'placeholder'=>'Email')); ?>
							</div>
							
							<div class="col-md-3">
								<label>Mobile No.*</label>
							  <?php echo $this->Form->input('Developers.mobile',array('maxlength'=>10,'label' => false,'class'=>'form-control',$fields_readonly,'placeholder'=>'Mobile No.',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
							</div>
							<div class="col-md-3">
								<label>WhatsApp No. <img src="/img/whatsapp.png" height="20" width="20" /></label>
								<?php
								echo $this->Form->input('Developers.contact',array('type'=>'hidden','label' => false,'class'=>'form-control')); ?>
								<?php
								echo $this->Form->input('Developers.contact1',array('label' => false,'class'=>'form-control','placeholder'=>'WhatsApp No.')); ?>
							</div>
							<div class="col-md-3">
								<label>Website </label>
								<?php
								echo $this->Form->input('Developers.website',array('label' => false,'class'=>'form-control','placeholder'=>'Website')); ?>
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-3">
								<label>PAN Number *</label>
								<?php
								echo $this->Form->input('Developers.pan',array('label' => false,'class'=>'form-control',$fields_readonly,'placeholder'=>'PAN Number','maxlength'=>'10'));?>
							</div>
							<div class="col-md-3">
								<label class="lbl_comunication_address">
								PAN Card<span class="mendatory_field">*</span></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Developers.f_pan_card', array('label' => false,'div' => false,'type'=>'file','id'=>'f_pan_card','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
								</div>
								<div id="f_pan_card-file-errors"></div>
								<?php
								if(!empty($installer_detail->pan_card)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->pan_card)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/d_pan_card/'.encode($installer_detail->id)."\">View PAN Card</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
							<div class="col-md-3 gst_select">
								<label>GST No.</label>
								<?php echo $this->Form->input('Developers.GST',array('label' => false,'class'=>'form-control','placeholder'=>'GST No.')); ?>
							</div>
							<div class="col-md-3 gst_select">
								<label class="lbl_comunication_address">
								GST Certificate</label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Developers.f_gst_certificate', array('label' => false,'div' => false,'type'=>'file','id'=>'f_gst_certificate','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
								</div>
								<div id="f_gst_certificate-file-errors"></div>
								<?php
								if(!empty($installer_detail->gst_certificate)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->gst_certificate)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/d_gst_certificate/'.encode($installer_detail->id)."\">View GST Certificate</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="form-group">
							<?php $error_class_typeapplicant = "";
							if(isset($InstallerErrors['type_of_applicant']) && isset($InstallerErrors['type_of_applicant']['_empty']) && !empty($InstallerErrors['type_of_applicant']['_empty'])) {
								$error_class_typeapplicant 	= "has-error";

							}
							?>
							<div class="col-md-3 <?php echo $error_class_typeapplicant; ?>"><label>Type of Applicant<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->select('Developers.type_of_applicant',$type_of_applicant, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Select Type of Applicant-','id'=>'type_of_applicant','onChange'=>'javascript:ShowHideOthers();'));
								if(!empty($error_class_typeapplicant)){  ?>
									<div class="help-block"><?php echo $InstallerErrors['type_of_applicant']['_empty']; ?></div>
								<?php } ?>
							</div>
							<div class="col-md-3 applicant_others"><label>Applicant Type Other<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.applicant_others',array('label' => false,'class'=>'','id'=>'applicant_others'));
								?>
							</div>	
							<div class="col-md-3">
								<label>Enclose self-certified copy <span class="mendatory_field">*</span>&nbsp;<i data-content="Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP" class="fa fa-info-circle"></i></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Developers.f_registration_document', array('label' => false,'div' => false,'type'=>'file','id'=>'f_registration_document','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
								</div>
								<div id="f_registration_document-file-errors"></div>
								<?php
								if(!empty($installer_detail->registration_document)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->registration_document)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/d_registration_document/'.encode($installer_detail->id)."\">View Registration Document</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
							<div class="col-md-3">
								<label class="lbl_comunication_address">
								Upload Undertaking form<span class="mendatory_field">*</span></label>
								<br/>
								<div class="file-loading" >
									<?php echo $this->Form->input('Developers.f_upload_undertaking', array('label' => false,'div' => false,'type'=>'file','id'=>'f_upload_undertaking','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
								</div>
								<a href="/Format-for-Undertaking.docx" style="text-decoration: underline;"><strong>[Download Undertaking Format]</strong></a>
								<div id="f_upload_undertaking-file-errors"></div>
								<?php
								if(!empty($installer_detail->upload_undertaking)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->upload_undertaking)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/d_upload_undertaking/'.encode($installer_detail->id)."\">View Undertaking form</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="form-group">
							<div class="col-md-3"><label >Is the Applicant a MSME?<span class="mendatory_field">*</span></label>
								<?php

							echo $this->Form->input('Developers.msme', [
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
													'after' => '',
												]);

							?>
							</div>	
							<div class="col-md-3 msme_file">
								<label>MSME<span class="mendatory_field">*</span></label>
								<div class="file-loading" >
									<?php echo $this->Form->input('Developers.d_msme', array('label' => false,'div' => false,'type'=>'file','id'=>'d_msme','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
								</div>
								<div id="d_msme-file-errors"></div>
								<?php
								if(!empty($installer_detail->d_msme)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->d_msme)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/d_msme/'.encode($installer_detail->id)."\">View MSME Document</a></strong>";
									 	} 
									?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-6"><label>Name of the Managing Director / Chief Executive of the Company<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.name_director',array('label' => false,'class'=>'','id'=>'name_director','placeholder'=>'Name of the Managing Director'));
								?>
							</div>	
							<?php
							$error_class_typeauthority = isset($InstallerErrors['type_director']['_empty']) && !empty($InstallerErrors['type_director']['_empty']) ? 'has-error' : ''; 
							?>
							<div class="col-md-3 <?php echo $error_class_typeauthority;?>"><label>Designation<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->select('Developers.type_director',$designation, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Designation-','id'=>'type_director','onChange'=>"javascript:ShowHideDesignationOthers('type_director');"));
								if(!empty($error_class_typeauthority)){  ?>
									<div class="help-block"><?php echo $InstallerErrors['type_director']['_empty']; ?></div>
								<?php } ?>
							</div>
							<div class="col-md-3" id="div_type_director"><label>Designation<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.type_director_others',array('label' => false,'class'=>'','id'=>'type_director_others'));
								?>
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
								<?php echo $this->Form->input('Developers.director_whatsapp', array('label' => false,'class'=>'form-control','placeholder'=>'WhatsApp No.',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
							</div>
							<div class="col-md-4">
								<label>Mobile<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.director_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10",'type'=>'text')); ?>
							</div>
							<div class="col-md-4">
								<label>Email<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.director_email', array('label' => false,'class'=>'form-control','placeholder'=>'Email')); ?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-md-6"><label>Name of the authorized Signatory<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.name_authority',array('label' => false,'class'=>'','id'=>'name_authority','placeholder'=>'Name of the authorized Signatory'));
								?>
							</div>	
							<?php
							$error_class_typeauthority = isset($InstallerErrors['type_authority']['_empty']) && !empty($InstallerErrors['type_authority']['_empty']) ? 'has-error' : ''; 
							?>
							<div class="col-md-3 <?php echo $error_class_typeauthority;?>"><label>Designation<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->select('Developers.type_authority',$designation, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Designation-','id'=>'type_authority','onChange'=>"javascript:ShowHideDesignationOthers('type_authority');"));
								if(!empty($error_class_typeauthority)){  ?>
									<div class="help-block"><?php echo $InstallerErrors['type_authority']['_empty']; ?></div>
								<?php } ?>
							</div>
							<div class="col-md-3" id="div_type_authority"><label>Designation<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.type_authority_others',array('label' => false,'class'=>'','id'=>'type_authority_others'));
								?>
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="form-group">
				
							<label class="col-md-8">Copy of Board resolution authorizing person for signing all the documents related to proposed project<span class="mendatory_field">*</span></label>

							<div class="col-md-4">
								
								<div class="file-loading" >
									<?php echo $this->Form->input('Developers.dfile_board', array('label' => false,'div' => false,'type'=>'file','id'=>'dfile_board','templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
								</div>
							
								<div id="dfile_board-file-errors"></div>
								<?php
								if(!empty($installer_detail->d_file_board)) :  ?>
									<?php if ($Couchdb->documentExist($installer_detail->id,$installer_detail->d_file_board)) { 
										echo "<br/><strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/d_file_board/'.encode($installer_detail->id)."\">View Copy of Board</a></strong>";
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
								<?php echo $this->Form->input('Developers.authority_whatsapp', array('label' => false,'class'=>'form-control','placeholder'=>'WhatsApp No.',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
							</div>
							<div class="col-md-4">
								<label>Mobile<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.authority_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10",'type'=>'text')); ?>
							</div>
							<div class="col-md-4">
								<label>Email<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('Developers.authority_email', array('label' => false,'class'=>'form-control','placeholder'=>'Email')); ?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="lbl_comunication_address">
								Total Processing Fees including GST (in &#8377;) <span id="total_price"><?php echo number_format((INSTALLER_PAYMENT_FEES + INSTALLER_GST_AMOUNT),2);?></span></label>
								<?php /*
								<div class="form-group text"><?php echo 'Amount '.number_format(INSTALLER_PAYMENT_FEES,2);?> + <?php echo 'GST '.number_format(INSTALLER_GST_AMOUNT,2);?></div> */?>
							</div>
						</div>
					</div>
					<?php if(isset($installer_detail->geda_approval) && $installer_detail->geda_approval==2) {  ?>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>
										 <strong>Query Raised -</strong> <?php echo $installer_detail->reject_reason;?>
									</label>
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<?php $error_class = isset($InstallerErrors['reply']['_empty']) && !empty($InstallerErrors['reply']['_empty']) ? 'has-error' : '';  ?>
								<div class="col-md-4">
									<label>
										Reply
									</label>
								</div>
								<div class="col-md-6 <?php echo $error_class;?>">
									<?php echo $this->Form->textarea('Developers.reply',[ "class" =>"form-control reason messagebox",      
															'id'=>'reply',
															'cols'=>'50','rows'=>'5',
															'label' => false,
															'placeholder' => 'Comments, if any']);?>
									<?php if(!empty($error_class)) { ?>
										<div class="help-block"><?php echo $InstallerErrors['reply']['_empty']; ?></div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</fieldset>
				<?php if(empty($member_id)) { ?>
					<div class="row">
						<div class="col-md-12" align="right">
							<input type="submit" value="Submit" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
							<?php
							/*if(!empty($installer_detail->otp) && empty($installer_detail->otp_verified_status)) {
								?>
								<input type="button" value="Verify OTP" data-toggle="modal" data-target="#Varify_Otp" class="Varify_Otp btn btn-primary btn-lg mb-xlg" data-id="<?php echo encode($installer_detail->id); ?>" >
								<input type="button" value="Resend OTP" data-toggle="modal" data-target="#Resend_Otp" class="Resend_Otp btn btn-primary btn-lg mb-xlg" data-id="<?php echo encode($installer_detail->id); ?>" >
								<?php
							}*/
							?>
						</div>
					</div>
				<?php } ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div id="Varify_Otp" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Verify OTP</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('VarifyOtpForm',['name'=>'VarifyOtpForm','id'=>'VarifyOtpForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
						<?php echo $this->Form->input('insid',['id'=>'insid','label' => true,'type'=>'hidden']); ?>
						<?php echo $this->Form->input('otp',["class" =>"form-control",
																		'id'=>'otp',
																		'label' => false,
																		"placeholder" => "Enter OTP"
															]);
						?>
						</div>
						<div class="row">
							<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyOtpForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="Resend_Otp" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Resend OTP</h4>
					</div>
					<div class="modal-body">
						<?php
						echo $this->Form->create('ResendOtpForm',['name'=>'ResendOtpForm','id'=>'ResendOtpForm']); ?>
						<div id="message_error"></div>
						<div class="form-group text">
						<?php echo $this->Form->input('resend_id',['id'=>'resend_id','label' => true,'type'=>'hidden','value'=>$installer_detail->id]); ?>
						<?php echo $this->Form->input('otp',["class" =>"form-control",
																		'id'=>'otp',
																		'label' => false,
																		"placeholder" => "Enter OTP"
															]);
						?>
						</div>
						<div class="row">
							<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary resendotp_btn','data-form-name'=>'ResendOtpForm']); ?>
							</div>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<script language="javascript">
$('.panel-heading u').click(function() {
	$('.panel-heading').removeClass('actives');
	$(this).parents('.panel-heading').addClass('actives');
	$('.panel-title').removeClass('actives'); //just to make a visual sense
	$(this).parent().addClass('actives'); //just to make a visual sense
 });
$(document).ready(function() {
	$("#f_upload_undertaking").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_upload_undertaking-file-errors',
		maxFileSize: '1024',
	});
	$("#f_gst_certificate").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_gst_certificate-file-errors',
		maxFileSize: '1024',
	});
	$("#f_registration_document").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_registration_document-file-errors',
		maxFileSize: '1024',
	});
	$("#f_pan_card").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_pan_card-file-errors',
		maxFileSize: '1024',
	});
	$("#dfile_board").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#dfile_board-file-errors',
		maxFileSize: '1024',
	});
	$("#d_msme").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#d_msme-file-errors',
		maxFileSize: '1024',
	});
	$('.fa').popover({trigger: "hover"});
});
$(".Varify_Otp").click(function(){
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var ins_id = $(this).attr("data-id");
	$("#insid").val(ins_id);
});
$(".varifyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var otp_data = $("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/verify-otp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#VarifyOtpForm").find("#otp_data").val('');
						$("#VarifyOtpForm").find("#message_error").removeClass('alert-danger');
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#Varify_Otp").modal('hide');
						window.location.reload();
					} else {
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".Resend_Otp").click(function(){
	$("#ResendOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#ResendOtpForm").find("#message_error").html('');
	var ins_id = $(this).attr("data-id");
	$("#resend_id").val(ins_id);
});
$(".resendotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#ResendOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#ResendOtpForm").find("#message_error").html('');
	var otp_data = $("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/InstallerRegistrations/ResendOtp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ResendOtpForm").find("#otp_data").val('');
						$("#ResendOtpForm").find("#message_error").removeClass('alert-danger');
						$("#ResendOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#Varify_Otp").modal('hide');
						window.location.reload();
					} else {
						$("#ResendOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$("#state").change(function(){
	$("#district").html("");
	$("#district").append($("<option />").val('').text('-Select District-'));
	detailsFromState(1);
});
function detailsFromState(reset=0)
{
	var org_val 	= '';
	if(reset==0) {
		org_val 	= '<?php echo isset($installer_detail->district) ? $installer_detail->district : "";?>';
	}
	$.ajax({
		type: "POST",
		url: "/InstallerRegistrations/getDistrict",
		data: {"state":$('#state').val()},
		success: function(response) {
		var result = $.parseJSON(response);
		$("#district").html("");
		$("#district").append($("<option />").val('').text('-Select District-'));
		if (result.data.district != undefined) {
			$.each(result.data.district, function(index, title) {
				$("#district").append($("<option />").val(index).text(title));
			});
			$('#district').val(org_val);
			if(org_val!='')
			{
		
			}
		}
		}
	});
}
function gst_click()
{
	if($("#gst_check").is(":checked")){
		$(".gst_select").hide();
	} else {
		$(".gst_select").show();
	}
	
}
detailsFromState();
gst_click();
function chkCategory(cat_id) {
	if($("#cat_"+cat_id).hasClass("btn-primary")) {
		$("#cat_"+cat_id).removeClass('btn-primary');
		$("#cat_"+cat_id).addClass('btn-default');
	} else {
		console.log($("#cat_"+cat_id).hasClass("btn-primary"));
		$("#cat_"+cat_id).addClass('btn-primary');
		$("#cat_"+cat_id).removeClass('btn-default');
	}
	getTotal();
}
function getTotal() {
	var counter 		= 0;
	var totalCategory 	= 5;
	var totalPrice 		= 0;
	var selectedCategory= [];
	for(var i=1;i<=totalCategory;i++)
	{
		if($("#cat_"+i).hasClass("btn-primary")) {
			var amount 		= parseFloat($("#cat_"+i).attr('data-amount'));
			var tax 		= parseFloat($("#cat_"+i).attr('data-tax'));
			var basicPrice 	= amount + ((amount*tax)/100);
			totalPrice  	= totalPrice + (basicPrice);
			selectedCategory.push(i);
			counter++;
		}
	}
	var selectedCat 	= selectedCategory.join(",");
	$("#selected_category").val(selectedCat);
	$("#total_price").html(NumFormat(totalPrice));
}
function NumFormat(number){
	var outputformat = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2,minimumFractionDigits: 2 }).format(number);
	return outputformat;
}
getTotal();
function ShowHideOthers() {
	if($("#type_of_applicant").val() == 'Other') {
		$(".applicant_others").show();
	} else {
		$(".applicant_others").hide();
	}
}
ShowHideOthers();
function ShowHideDesignationOthers(designation) {
	if($("#"+designation).val() == 'Others') {
		$("#div_"+designation).show();
	} else {
		$("#div_"+designation).hide();
	}
}
function toggel_msme()
{
	$(".msme_file").addClass('hide');
	$(".msme").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1) {
			$(".msme_file").removeClass('hide');
		}
		if($(this).is(":checked") && $(this).val() == 0) {
			$(".msme_file").addClass('hide');
		}
	});
}
ShowHideDesignationOthers('type_director');
ShowHideDesignationOthers('type_authority');
toggel_msme();
function basicForm(checkdata)
{	
	if(checkdata == 1) {
		
	} else {
		
	}
}
</script>
