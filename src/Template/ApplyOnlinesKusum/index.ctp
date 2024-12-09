<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.form-horizontal .radio {

	padding-top: 0px !important;
}
.check-box-address{
	margin-top: 20px !important;
}
.mendatory_field{
	color:red;
}
.class_legend {
	border: none;width: auto;margin-left: 10px;
}
.class_fieldset {
	border: 1px solid #000;
	margin-bottom: 20px;
}

</style>
<?php
	$allocatedCategory 	= 3;
	$this->Html->addCrumb($pageTitle);
	$Report 			= "";
	

	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_KUSUM_PATH.'/';
	}
	$IMAGE_EXT                  = array("png","jpg","gif","jpeg","bmp");

	/** STOP B CATEGORY INSTALLERS TO SUBMIT THE APPLICATION */
	$IsInstallerAllowedToSubmit = true;
	$ALERT_MESSAGE              = "";

	echo $this->Form->create($ApplyOnlines,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'contactForm', 'url' => '/apply-onlines-kusum/'.$str_url,'autocomplete'=>'off','onSubmit'=>'return CheckFormSubmit();'));
?>

<!-- File: src/Template/Users/login.ctp -->
<div class="container applay-online-from">
	<div class="row">
		<h2 class="col-md-9 mb-sm mt-sm"><strong>Apply</strong> Online - Kusum</h2>
		<div class="col-md-3 pull-right">
			<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg pull-right">
			<?php echo $this->Html->link('My Application',['controller'=>'ApplyOnlines','action' => 'applyonline_list']); ?>
			</span>
		</div>
	</div>
	<?php echo $this->Flash->render('cutom_admin');?>
		
	
	<div class="tabs tabs-bottom tabs-simple nk_tabs">
		<a href="#desc_applicant" data-toggle="collapse" onclick="javascript:changeClick('id_desc_applicant','hide');" id="id_desc_applicant" style="display: none;"><h4>Description of Applicant</h4></a>
		<fieldset class="class_fieldset" id="desc_applicant">
			<legend class="class_legend" >
				<a href="#desc_applicant" data-toggle="collapse" onclick="javascript:changeClick('id_desc_applicant','show');" >Description of Applicant</a>
			</legend>
			<div >
				<?php 
				$error_class_type 	= '';
				if(isset($ApplyOnlineErrors['applicant_type_kusum']) && isset($ApplyOnlineErrors['applicant_type_kusum']['_empty']) && !empty($ApplyOnlineErrors['applicant_type_kusum']['_empty'])){ 
					$error_class_type = 'has-error';
				} ?>
				<div class="form-group">
					<div class="col-md-4 <?php echo $error_class_type; ?>">
						<label>Applicant Type</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.applicant_type_kusum',$applicant_type_kusum, array('label' => false,'class'=>'','empty'=>'-Select Type-','id'=>'applicant_type_kusum','onchange'=>'javascript:change_applicant_type();'));
						if(!empty($error_class_type)){  ?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['applicant_type_kusum']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label id="applicant_label_name">Name</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.application_type_name', array('label' => false,'class'=>'','id'=>'application_type_name'));?>
					</div>
					<div class="col-md-4 class_registration">
						<label>Enclosed Copy of Registration</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.file_copy_registration', array('label' => false,'type'=>'file','id'=>'file_copy_registration','class'=>'form-control','placeholder'=>'Copy Registration.')); ?>
						<div id="file_copy_registration-file-errors"></div>
						<br/>
						<?php if(!empty($ApplyOnlines->copy_registration)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->copy_registration)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->copy_registration,PATHINFO_EXTENSION);
									if (in_array($file_ext,$IMAGE_EXT)) {
								?>

									<img src="<?php echo URL_HTTP.'app-docs/copy_registration/'.encode($ApplyOnlines->id); ?>"/>
								<?php } else { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/copy_registration/'.encode($ApplyOnlines->id)."\">View Copy of Registration Document</a></strong>";
									?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4 class">
						<label>Aadhaar of Authorised Person</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.aadhaar_no', array('label' => false,'class'=>'form-control','placeholder'=>'Aadhaar Card Number','type'=>'text','maxlength'=>12,'onkeypress'=>"return validateInteger(event)")); ?>
					</div>
					<div class="col-md-4 class">
						<label>Copy of Aadhaar Card</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.file_aadhaar_card', array('label' => false,'type'=>'file','id'=>'file_aadhaar_card','class'=>'form-control','placeholder'=>'Copy of Aadhaar Card.')); ?>
						<div id="file_aadhaar_card-file-errors"></div>
						<br/>
						<?php if(!empty($ApplyOnlines->aadhaar_file)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->aadhaar_file)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->aadhaar_file,PATHINFO_EXTENSION);
									if (in_array($file_ext,$IMAGE_EXT)) {
								?>

									<img src="<?php echo URL_HTTP.'app-docs/aadhaar_file/'.encode($ApplyOnlines->id); ?>"/>
								<?php } else { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/aadhaar_file/'.encode($ApplyOnlines->id)."\">View Copy of Aadhaar Card</a></strong>";
									?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<br/>
					</div>
					<?php 
						$error_class_type 	= '';
						$memberVal 			= '';
						if(isset($RequestData['members'][0]) && !empty($RequestData['members'][0]))
						{
							$memberVal 		= $RequestData['members'][0];
						}
						if(isset($ApplyOnlineErrors['members']) && isset($ApplyOnlineErrors['members']['_empty']) && !empty($ApplyOnlineErrors['members']['_empty'])){ 
							$error_class_type = 'has-error';

						} ?>
					<div class="col-md-4 class_8002 <?php echo $error_class_type;?>">
						<label id="applicant_label_name">List of Members</label>
						<div id="newRow">
							<div class="form-group text col-md-10" style="margin-left:-15px !important;">
								<input type="text" name="ApplyOnlinesKusum[members][]" class="form-control m-input" placeholder="Member Name" autocomplete="off" value="<?php echo $memberVal;?>">
								<?php if(!empty($error_class_type)){  ?>
									<div class="help-block"><?php echo $ApplyOnlineErrors['members']['_empty']; ?></div>
								<?php } ?>
							</div>
							<div class="form-group text col-md-1">
								<a style="" href="javascript:;" class="btn btn-primary" id="addRow" title="Add More"><i class="fa fa-plus-circle"  ></i></a>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		</fieldset>

		<?php 
			$styleHeader 	= '';
			if(!empty($ApplyOnlineErrors)) { 
			$styleHeader 	= 'display:none;';
		} ?>
		<a href="#contact_details" data-toggle="collapse" onclick="javascript:changeClick('id_contact_details','hide');" id="id_contact_details" style="<?php echo $styleHeader;?>"><h4>Contact Details</h4></a>
		<fieldset class="class_fieldset" id="contact_details" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#contact_details" data-toggle="collapse" onclick="javascript:changeClick('id_contact_details','show');" >Contact Details</a>
			</legend>
			<div <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php }*/ ?>>
				<div class="form-group">
					<div class="col-md-4">
						<label >Correspondence address</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.correspondence_address', array('label' => false,'class'=>'','id'=>'correspondence_address'));?>
					</div>
					<div class="col-md-4">
						<label >Name of the authorized person</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.authorized_person', array('label' => false,'class'=>'','id'=>'authorized_person'));?>
					</div>
					<div class="col-md-4">
						<label>Enclose Letter of Authorization</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.file_authorize_letter', array('label' => false,'type'=>'file','id'=>'file_authorize_letter','class'=>'form-control','placeholder'=>'Authorization Letter.')); ?>
						<div id="file_authorize_letter-file-errors"></div>
						<br/>
						<?php if(!empty($ApplyOnlines->authorize_letter)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->authorize_letter)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->authorize_letter,PATHINFO_EXTENSION);
									if (in_array($file_ext,$IMAGE_EXT)) {
								?>

									<img src="<?php echo URL_HTTP.'app-docs/authorize_letter/'.encode($ApplyOnlines->id); ?>"/>
								<?php } else { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/authorize_letter/'.encode($ApplyOnlines->id)."\">View Letter of Authorization Document</a></strong>";
									?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label >Mobile Number</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.mobile', array('label' => false,'class'=>'','id'=>'mobile','placeholder'=>'Mobile','maxlength'=>10,'onkeypress'=>"return validateInteger(event)"));?>
					</div>
					<div class="col-md-4">
						<label >E-mail Id</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.email', array('label' => false,'class'=>'','id'=>'email'));?>
					</div>
				</div>
			</div>
		</fieldset>

		<a href="#discom_details" data-toggle="collapse" onclick="javascript:changeClick('id_discom_details','hide');" id="id_discom_details" style="<?php echo $styleHeader;?>"><h4>Details of Sub-station notified by the Discom (where the proposed land is available within 5 km radius)</h4></a>
		<fieldset class="class_fieldset" id="discom_details" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#discom_details" data-toggle="collapse" onclick="javascript:changeClick('id_discom_details','show');" >Details of Sub-station notified by the Discom (where the proposed land is available within 5 km radius)</a>
			</legend>
			<div  <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php }*/ ?>>
				<div class="form-group">
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($ApplyOnlineErrors['discom']) && isset($ApplyOnlineErrors['discom']['_empty']) && !empty($ApplyOnlineErrors['discom']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $ApplyOnlineErrors['discom']['_empty'];
					} ?>
					<div class="col-md-4 <?php echo $error_class_type;?>">
						<label >Name of Discom</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.discom',$discom_list, array('label' => false,'empty'=>'-Select Discom-','class'=>'form-control','id'=>'discom','placeholder'=>'Discom')); 
						?>
						<?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?>
					</div>
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($ApplyOnlineErrors['division']) && isset($ApplyOnlineErrors['division']['_empty']) && !empty($ApplyOnlineErrors['division']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $ApplyOnlineErrors['division']['_empty'];
					} ?>
					<div class="col-md-4 <?php echo $error_class_type;?>">
						<label >Name of Division</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.division',array(), array('label' => false,'empty'=>'-Select Division-','class'=>'form-control','id'=>'division','placeholder'=>'Division','onchange'=>'javascript:click_division();')); 
						?>
						<?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?>
					</div>
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($ApplyOnlineErrors['subdivision']) && isset($ApplyOnlineErrors['subdivision']['_empty']) && !empty($ApplyOnlineErrors['subdivision']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $ApplyOnlineErrors['subdivision']['_empty'];
					} ?>
					<div class="col-md-4 <?php echo $error_class_type;?>">
						<label >Name of Sub Division</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.subdivision',array(), array('label' => false,'empty'=>'-Select Sub Division-','class'=>'form-control','id'=>'subdivision','placeholder'=>'Sub Division')); 
						?>
						<?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4 hide">
						<label >Name of power utility</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.name_power_utility', array('label' => false,'class'=>'','id'=>'name_power_utility'));?>
					</div>
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($ApplyOnlineErrors['district']) && isset($ApplyOnlineErrors['district']['_empty']) && !empty($ApplyOnlineErrors['district']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $ApplyOnlineErrors['district']['_empty'];
					} ?>
					<div class="col-md-4 <?php echo $error_class_type;?>">
						<label >District</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.district',$arrDistrict,array('label' => false,'class'=>'form-control','id'=>'district','empty'=>'Select District','placeholder'=>'Select District'));?>
						<?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label >Taluka</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.panchayat_committee', array('label' => false,'class'=>'','id'=>'panchayat_committee'));?>
					</div>
					<div class="col-md-4">
						<label >Name of Sub-station</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.name_substation', array('label' => false,'class'=>'','id'=>'name_substation'));?>
					</div>
					<div class="col-md-4">
						<label >Declared capacity for Solar Power Project (MW)</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.declare_capacity', array('label' => false,'class'=>'','id'=>'declare_capacity','onkeypress'=>'return validateDecimal(event)'));?>
					</div>


				</div>
			</div>
		</fieldset>
		
		<a href="#land_details" data-toggle="collapse" onclick="javascript:changeClick('id_land_details','hide');" id="id_land_details" style="<?php echo $styleHeader;?>"><h4>Land Details</h4></a>
		<fieldset class="class_fieldset" id="land_details" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#land_details" data-toggle="collapse" onclick="javascript:changeClick('id_land_details','show');" >Land Details</a>
			</legend>
			<div id="" <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php } */ ?>>
				<div class="form-group">
					<div class="col-md-4">
						<label >Name of Village</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.village_name', array('label' => false,'class'=>'','id'=>'village_name','placeholder'=>'Name of Village'));?>
					</div>
					<div class="col-md-4">
						<label >Taluka</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.taluka', array('label' => false,'class'=>'','id'=>'taluka','placeholder'=>'Taluka'));?>
					</div>
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($ApplyOnlineErrors['land_district']) && isset($ApplyOnlineErrors['land_district']['_empty']) && !empty($ApplyOnlineErrors['land_district']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $ApplyOnlineErrors['land_district']['_empty'];
					} ?>
					<div class="col-md-4 <?php echo $error_class_type;?>">
						<label >District</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.land_district',$arrDistrict,array('label' => false,'class'=>'form-control','id'=>'land_district','empty'=>'Select District','placeholder'=>'Select District'));?>
						<?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?>
					</div>
					<div style="clear: both;"></div>
					<div class="col-md-2">
						<label >Survey No</label>
						<?php 
						$survey_no_1 	= (isset($RequestData['land_survey_no'][0]) &&  !empty($RequestData['land_survey_no'][0])) ? $RequestData['land_survey_no'][0] : ''; 
						$survey_no_2 	= (isset($RequestData['land_survey_no'][1]) &&  !empty($RequestData['land_survey_no'][1])) ? $RequestData['land_survey_no'][1] : ''; 
						$survey_no_3 	= (isset($RequestData['land_survey_no'][2]) &&  !empty($RequestData['land_survey_no'][2])) ? $RequestData['land_survey_no'][2] : ''; 
						?>
						<?php echo $this->Form->input('ApplyOnlinesKusum.land_survey_no][', array('label' => false,'class'=>'','id'=>'land_survey_no_1','placeholder'=>'Survey No','value'=>$survey_no_1));?>
						<?php echo $this->Form->input('ApplyOnlinesKusum.land_survey_no][', array('label' => false,'class'=>'','id'=>'land_survey_no_2','placeholder'=>'Survey No','value'=>$survey_no_2));?>
						<?php echo $this->Form->input('ApplyOnlinesKusum.land_survey_no][', array('label' => false,'class'=>'','id'=>'land_survey_no_3','placeholder'=>'Survey No','value'=>$survey_no_3));?>
					</div>
					<div class="col-md-2">
						<label >Survey Area</label>
						<?php 
						$survey_area_1 	= (isset($RequestData['land_survey_area'][0]) &&  !empty($RequestData['land_survey_area'][0])) ? $RequestData['land_survey_area'][0] : ''; 
						$survey_area_2 	= (isset($RequestData['land_survey_area'][1]) &&  !empty($RequestData['land_survey_area'][1])) ? $RequestData['land_survey_area'][1] : ''; 
						$survey_area_3 	= (isset($RequestData['land_survey_area'][2]) &&  !empty($RequestData['land_survey_area'][2])) ? $RequestData['land_survey_area'][2] : ''; 
						?>
						<?php echo $this->Form->input('ApplyOnlinesKusum.land_survey_area][', array('label' => false,'class'=>'land_area','id'=>'land_survey_area_1','placeholder'=>'Survey Area','onkeypress'=>'return validateDecimal(event)','onchange'=>"javascript:calculate_acre_sqm()",'value'=>$survey_area_1));?>
						<?php echo $this->Form->input('ApplyOnlinesKusum.land_survey_area][', array('label' => false,'class'=>'land_area','id'=>'land_survey_area_2','placeholder'=>'Survey Area','onkeypress'=>'return validateDecimal(event)','onchange'=>"javascript:calculate_acre_sqm()",'value'=>$survey_area_2));?>
						<?php echo $this->Form->input('ApplyOnlinesKusum.land_survey_area][', array('label' => false,'class'=>'land_area','id'=>'land_survey_area_3','placeholder'=>'Survey Area','onkeypress'=>'return validateDecimal(event)','onchange'=>"javascript:calculate_acre_sqm()",'value'=>$survey_area_3));?>
					</div>
					<div class="col-md-2">
						<label >&nbsp;</label>
						<?php
						$arrOptions 		= array();
						$counter 			= 0;

						foreach($arr_area_kusum as $key=>$val) {
							$checked 			= '';
							if($key == 9001) {
								$checked = "checked";
							}
							$arrOptions[]	= ['value' => $key,'id'=>'survey_type_0_'.$counter, 'text' =>$val,"class"=>"payment_mode_choice",'onclick'=>"javascript:calculate_acre_sqm()",$checked];
							$counter++;
						}
						echo $this->Form->input('ApplyOnlinesKusum.land_survey_type_0', [
												'type' 		=> 'radio',
												'label' 	=> false,
												'div' 		=> false,
												'options' 	=> $arrOptions,
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" >{{label}}</div>'
												],
												'before' 	=> '',
												'separator' => '',
												'after' 	=> '',
											]);
						$arrOptions 		= array();
						$counter 			= 0;
						foreach($arr_area_kusum as $key=>$val) {
							$checked 			= '';
							if($key == 9001) {
								$checked = "checked";
							}

							$arrOptions[]	= ['value' => $key,'id'=>'survey_type_1_'.$counter, 'text' =>$val,"class"=>"payment_mode_choice",'onclick'=>"javascript:calculate_acre_sqm()",$checked];
							$counter++;
						}
						echo $this->Form->input('ApplyOnlinesKusum.land_survey_type_1', [
												'type' 		=> 'radio',
												'label' 	=> false,
												'div' 		=> false,
												'options' 	=> $arrOptions,
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" >{{label}}</div>'
												],
												'before' 	=> '',
												'separator' => '',
												'after' 	=> '',
											]);
						$arrOptions 		= array();
						$counter 			= 0;
						foreach($arr_area_kusum as $key=>$val) {
							$checked 			= '';
							if($key == 9001) {
								$checked = "checked";
							}
							$arrOptions[]	= ['value' => $key,'id'=>'survey_type_2_'.$counter, 'text' =>$val,"class"=>"payment_mode_choice",'onclick'=>"javascript:calculate_acre_sqm()",$checked];
							$counter++;
						}
						echo $this->Form->input('ApplyOnlinesKusum.land_survey_type_2', [
												'type' 		=> 'radio',
												'label' 	=> false,
												'div' 		=> false,
												'options' 	=> $arrOptions,
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" >{{label}}</div>'
												],
												'before' 	=> '',
												'separator' => '',
												'after' 	=> '',
											]);
						?>
					</div>
					<div class="col-md-2">&nbsp;</div>
					<div class="col-md-4">
						<label>Please attach a copy of Uttara 7/ Jamabandi</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.file_jamabandi', array('label' => false,'type'=>'file','id'=>'file_jamabandi','class'=>'form-control','placeholder'=>'Authorization Letter.')); ?>
						<div id="file_jamabandi-file-errors"></div>
						<br/>
						<?php if(!empty($ApplyOnlines->jamabandi)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->jamabandi)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->jamabandi,PATHINFO_EXTENSION);
									if (in_array($file_ext,$IMAGE_EXT)) {
								?>

									<img src="<?php echo URL_HTTP.'app-docs/jamabandi/'.encode($ApplyOnlines->id); ?>"/>
								<?php } else { ?>
									<?php
										echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/jamabandi/'.encode($ApplyOnlines->id)."\">View Copy of Uttara 7/ Jamabandi Document</a></strong>";
									?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					<div style="clear: both;"></div>
					<div class="col-md-4">
						<label><strong>Total Area (in Acre) : </strong><span id="total_acre"></span></label><br/>
						<label class="hide"><strong>Total Area (in sqm) : </strong><span id="total_sqm"></span></label>
					</div>
				</div>
			</div>
		</fieldset>

		
		<a href="#offered_capacity" data-toggle="collapse"  onclick="javascript:changeClick('id_offered_capacity','hide');" id="id_offered_capacity" style="<?php echo $styleHeader;?>"><h4>Proposed Solar PV Power Plant capacity (in MW)</h4></a>
		<fieldset class="class_fieldset" id="offered_capacity" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#offered_capacity" data-toggle="collapse" onclick="javascript:changeClick('id_offered_capacity','show');" >Proposed Solar PV Power Plant capacity (in MW)</a>
			</legend>
			<div  <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php } */?>>
				<div class="form-group">
					<div class="col-md-4">
						<label >Proposed Solar PV Power Plant capacity (in MW)</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.pv_capacity', array('label' => false,'class'=>'','id'=>'pv_capacity','onkeypress'=>'return validateDecimal(event)','placeholder'=>'Proposed Capacity','onblur'=>'javascript:changeCost();'));?>
					</div>
				</div>
			</div>
		</fieldset>

		<a href="#distnace_substation" data-toggle="collapse" onclick="javascript:changeClick('id_distnace_substation','hide');" id="id_distnace_substation" style="<?php echo $styleHeader;?>"><h4>Distance between the Proposed land and Sub-station notified by the Discom</h4></a>
		<fieldset class="class_fieldset" id="distnace_substation" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#distnace_substation" data-toggle="collapse" onclick="javascript:changeClick('id_distnace_substation','show');" >Distance between the Proposed land and Sub-station notified by the Discom</a>
			</legend>
			<div id="" <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php }*/ ?>>
				<div class="form-group">
					<div class="col-md-4">
						<label >Distance between the Proposed land and Sub-station notified by the Discom</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.distance_plant', array('label' => false,'class'=>'','id'=>'distance_plant','placeholder'=>'Distance','onkeypress'=>'return validateDecimal(event)'));?>
					</div>
					<div class="col-md-4">
						<label ><br/></label>
						<?php 
							$arrOptions 	= array();
							foreach($arr_distance as $key=>$val) {
								$checked 			= '';
								if($key == 10001) {
									$checked = "checked";
								}
								$arrOptions[]	= ['value' => $key, 'text' =>$val,"class"=>"payment_mode_choice",$checked];
								$counter++;
							}
							echo $this->Form->input('ApplyOnlinesKusum.distance_type', [
											'type' 		=> 'radio',
											'label' 	=> false,
											'div' 		=> false,
											'options' 	=> $arrOptions,
											'templates' => [
												'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
												'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" >{{label}}</div>'
											],
											'before' 	=> '',
											'separator' => '',
											'after' 	=> '',
										]);?>
					</div>
				</div>
			</div>
		</fieldset>


		<a href="#option_available" data-toggle="collapse" onclick="javascript:changeClick('id_option_available','hide');" id="id_option_available" style="<?php echo $styleHeader;?>"><h4>Options available to the applicants for installation of Solar Power Plants</h4></a>
		<fieldset class="class_fieldset" id="option_available" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#option_available" data-toggle="collapse" onclick="javascript:changeClick('id_option_available','show');" >Options available to the applicants for installation of Solar Power Plants</a>
			</legend>
			<div id="" <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php }*/ ?>>
				<div class="form-group col-md-12">
					<?php echo $this->Form->input('ApplyOnlinesKusum.option_solar', [
											'type' 		=> 'radio',
											'label' 	=> false,
											'div' 		=> false,
											'options' 	=> 	[['value' => 0, 'text' =>'Setting up complete SPP himself',"class"=>"payment_mode_choice"],
															['value' => 1,'text' =>'Leasing land for setting up of SPP',"class"=>"payment_mode_choice"]],
											'templates' => [
												'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
												'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" >{{label}}</div>'
											],
											'before' 	=> '',
											'separator' => '',
											'after' 	=> '',
										]);?>
					
				</div>
			</div>
		</fieldset>

		<a href="#installer_details" data-toggle="collapse" onclick="javascript:changeClick('id_installer_details','hide');" id="id_installer_details" style="<?php echo $styleHeader;?>"><h4>Details of Solar PV Installer (GEDA Installers)</h4></a>
		<fieldset class="class_fieldset" id="installer_details" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#installer_details" data-toggle="collapse" onclick="javascript:changeClick('id_installer_details','show');" >Details of Solar PV Installer (GEDA Installers)</a>
			</legend>
			<div id="" <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php }*/ ?>>
				<div class="form-group">
					<?php 
					$error_class_type 	= '';
					$error_message 		= '';
					if(isset($ApplyOnlineErrors['installer_id']) && isset($ApplyOnlineErrors['installer_id']['_empty']) && !empty($ApplyOnlineErrors['installer_id']['_empty'])){ 
						$error_class_type 	= 'has-error';
						$error_message 		= $ApplyOnlineErrors['installer_id']['_empty'];
					} ?>
					<div class="col-md-4 <?php echo $error_class_type;?>">
						<label>Installer Name</label>
						<?php echo $this->Form->select('ApplyOnlinesKusum.installer_id',$installers_list, array('label' => false,'empty'=>'-Select Installer-','class'=>'form-control','id'=>'installer_id','placeholder'=>'Installer','onchange'=>'javascript:change_installer()')); 
						?>
						<?php if(!empty($error_message)) {?>
							<div class="help-block"><?php echo $error_message;?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label>Installer Email</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.installer_email', array('label' => false,'class'=>'','id'=>'installer_email','placeholder'=>'Installer Email'));?>
						
					</div>
					<div class="col-md-4">
						<label>Installer Mobile</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.installer_mobile', array('label' => false,'class'=>'','id'=>'installer_mobile','maxlength'=>10,'onkeypress'=>"return validateInteger(event)",'placeholder'=>'Installer Mobile'));?>
					</div>
				</div>
			</div>
		</fieldset>

		<a href="#fees" data-toggle="collapse" onclick="javascript:changeClick('id_fees','hide');" id="id_fees" style="<?php echo $styleHeader;?>"><h4>Application Fees</h4></a>
		<fieldset class="class_fieldset" id="fees" <?php if(empty($ApplyOnlineErrors)) { ?> style="display:none;"<?php } ?>>
			<legend class="class_legend" >
				<a href="#fees" data-toggle="collapse" onclick="javascript:changeClick('id_fees','show');" >Application Fees</a>
			</legend>
			<div id="" <?php /*if(empty($ApplyOnlineErrors)) { ?> aria-expanded="false" class="collapse" <?php }*/ ?>>
				<div class="form-group">
					<div class="col-md-4">
						<label>GEDA Processing Fee</label>
						<?php 
						$applicationfees	= $ApplyOnlines->pv_capacity*KUSUM_FEES*1000;
						$tax_amount 		= ($applicationfees*KUSUM_GST_PER)/100;
						echo $this->Form->input('ApplyOnlinesKusum.disCom_application_fee', array('label' => false,'class'=>'form-control','readonly'=>'true','value'=>$applicationfees,'placeholder'=>'DisCom Application Fee for Net Metering','id'=>'disCom_application_fee'));
						?>
					</div>
					<div class="col-md-4">
						<label>GST at <?php echo KUSUM_GST_PER;?>%</label>
						<?php echo $this->Form->input('ApplyOnlinesKusum.jreda_processing_fee', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>$tax_amount,'placeholder'=>'GEDA Processing Fee','id'=>'jreda_processing_fee')); ?>
					</div>
					<div class="col-md-4">
						<label>Total Fee</label>
						<?php
							echo $this->Form->input('ApplyOnlinesKusum.total_fee', array('label' => false,'class'=>'form-control','value'=>$applicationfees+$tax_amount,'readonly'=>'true','placeholder'=>'Total Fee','id'=>'total_fee'));
						?>
					</div>
				</div>
			</div>
		</fieldset>
		<div class="row">
			<div class="col-md-2">
			<?php echo $this->Form->submit('Save And Submit',array('label' => false,'class'=>'btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_submit' ,'value'=>'save_submit','id'=>'save_submit')); ?>
			</div>
		</div>
	</div>
	
</div>
<?php echo $this->Form->end(); ?>



<script type="text/javascript">
var counter=0;
$(function () {
  $('[data-toggle="popover"]').popover();
  $('[project_tips="popover"]').popover({placement:'top'});
})



$(document).ready(function() {
	$("#file_aadhaar_card").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#file_aadhaar_card-file-errors',
		maxFileSize: '200',
	});
	$("#file_copy_registration").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#file_copy_registration-file-errors',
		maxFileSize: '1024',
	});
	$("#file_jamabandi").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#file_jamabandi-file-errors',
		maxFileSize: '1024',
	});
	$("#file_authorize_letter").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#file_authorize_letter-file-errors',
		maxFileSize: '1024',
	});
	<?php if(isset($enabled_fields) && !empty($enabled_fields)) :?>
			$('.applay-online-from input,.applay-online-from select').attr('disabled','disabled');
			<?php foreach ($enabled_fields as $key => $value) :?>
				$('.applay-online-from #<?php echo $value ?>').removeAttr('disabled');
			<?php endforeach;
	endif; ?>
});
function change_applicant_type() {
	var sel_text 	= $("#applicant_type_kusum option:selected").text();
	var sel_val 	= $("#applicant_type_kusum").val();
	if(sel_val==<?php echo $ApplyOnlinesKusum->TypeGroup;?>) {
		$("#applicant_label_name").html('Name of Group');
		$(".class_8002").show();
	} else {
		$("#applicant_label_name").html('Name of '+sel_text);
		$(".class_8002").hide();
	}
	if(sel_val == <?php echo $ApplyOnlinesKusum->TypeIndividual;?>) {
		//$(".class_8001").show();
	} else {
		//$(".class_8001").hide();
	}
	if(sel_val != <?php echo $ApplyOnlinesKusum->TypePanchayat;?>) {
		$(".class_registration").show();
	}
	else {
		$(".class_registration").hide();
	}
	
} 
change_applicant_type();
function makeRow(counter,value_text) {
	var html = '<div  id="member_'+counter+'"><div class="form-group text col-md-10" style="margin-left:-15px !important;" >';
	html += '<input type="text" name="ApplyOnlinesKusum[members][]" class="form-control m-input" placeholder="Member Name" autocomplete="off" value="'+value_text+'" ></div><div class="form-group text col-md-1">';
	html += '<button  type="button" class="btn btn-danger" onClick="remove_name('+counter+')"  title="Remove"><i class="fa fa-trash"></i></button>';
	html += '</div></div>';
	
	return html;
}
$("#addRow").click(function () {
	var html = '';
	html=makeRow(counter,'');
	counter++;
	$('#newRow').append(html);
});

<?php if(isset($RequestData['members'][1]))
{
	$memberVal 		= $RequestData['members'][1];

	foreach($RequestData['members'] as $k=>$val) { 
		if($k>0) { ?>
			var html=makeRow(counter,'<?php echo $val;?>');
			counter++;
			$('#newRow').append(html);
		<?php  }
	}
}?>


function remove_name(cnt)
{
	$("#member_"+cnt).remove();
}

function validateInteger(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8) && (keycode < 48 || keycode > 57)) {
		return false;
	} else {
		return true;
	}
}
function validateDecimal(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
		return false;
	} else {
		var parts = key.srcElement.value.split('.');
		if (parts.length > 1 && keycode == 46) return false;
		return true;
	}
}
function calculate_acre_sqm(){
	var total_sqm 	= 0;
	var total_acre 	= 0;
	$(".land_area").each(function (key,val) {
		if($(this).val()!='') {
			var valData = parseFloat($(this).val());
			if($("#survey_type_"+key+"_0").is(':checked')) {
				console.log('acre');
				total_sqm = total_sqm+(valData*4046.86);
				total_acre = total_acre+(valData);
			} else if($("#survey_type_"+key+"_1").is(':checked')) {
				total_sqm = total_sqm+valData;
				total_acre = total_acre+(valData*0.000247105);
			}
		}
		
	})
	$("#total_acre").html(total_acre.toFixed(2));
	$("#total_sqm").html(total_sqm.toFixed(2));
}
calculate_acre_sqm();
$("#discom").change(function(){
	$("#division").html("");
	$("#division").append($("<option />").val(0).text('-Select Division-'));
	clearSubdivision();
	detailsFromDiscom();
});
function detailsFromDiscom()
{
	var org_val="<?php echo isset($ApplyOnlines->division) ? ($ApplyOnlines->division) : 0;?>";

	$.ajax({
		type: "POST",
		url: "/ApplyOnlines/getDivision",
		data: {"discom":$('#discom').val()},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#division").html("");
			$("#division").append($("<option />").val(0).text('-Select Division-'));
			if (result.data.division != undefined) {
				$.each(result.data.division, function(index, title) {
					$("#division").append($("<option />").val(index).text(title));
				});
				$('#division').val(org_val);
				if(org_val!='')
				{
					
					click_division();
				}
			}
		}
	});

}
function clearSubdivision() {
	$('#subdivision').html("");
	$("#subdivision").append($("<option />").val(0).text('-Select Sub Division-'));
}
function click_division()
{
	clearSubdivision();
	var org_val="<?php echo isset($ApplyOnlines->subdivision) ? ($ApplyOnlines->subdivision) : 0;?>";
	$.ajax({
		type: "POST",
		headers: {
			'X-CSRF-TOKEN': <?= json_encode($this->request->param('_csrfToken')); ?>
			},
		url: "/ApplyOnlines/getSubdivision",
		data: {"division":$("#division").val()},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#subdivision").html("");
			$("#subdivision").append($("<option />").val(0).text('-Select Sub Division-'));
			if (result.data.subdivision != undefined) {
				$.each(result.data.subdivision, function(index, title) {
					$("#subdivision").append($("<option />").val(index).text(title));
				});
				$('#subdivision').val(org_val);
				
			}
		}
	});
	
}
detailsFromDiscom();
function change_installer() {
	$.ajax({
		type: "POST",
		headers: {
			'X-CSRF-TOKEN': <?= json_encode($this->request->param('_csrfToken')); ?>
			},
		url: "/ApplyOnlinesKusum/getInstaller",
		data: {"installer_id":$("#installer_id").val()},
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result.data.email);
			$("#installer_email").val(result.data.email);
			$("#installer_mobile").val(result.data.mobile);
		}
	});
}
function changeCost() {

	var capacity = $('#pv_capacity').val();
	var applicationfees	= capacity*<?php echo KUSUM_FEES;?>*1000;
	var tax_amount 		= (applicationfees*<?php echo KUSUM_GST_PER;?>)/100;
	var total_amount 	= applicationfees + tax_amount;
	$("#disCom_application_fee").val(applicationfees);
	$("#jreda_processing_fee").val(tax_amount);
	$("#total_fee").val(total_amount);
}
function changeClick(x,show_hide){
	var arrData 	= x.split('id_');
	
	if(show_hide == 'hide') {
		$("#"+x).hide();
		$("#"+arrData[1]).show();
	}
	else {
		$("#"+x).show();
		//$("#"+arrData[1]).hide();
	}
}
function revertClick(x)
{
	
}
</script>