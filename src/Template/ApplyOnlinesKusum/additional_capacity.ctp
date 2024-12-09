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

</style>
<?php
	$allocatedCategory 	= 3;
	$this->Html->addCrumb($pageTitle);
	$Report 			= "";
	if (isset($applyonlineapproval) && !empty($applyonlineapproval) ) {
		$Report 		= 1;
	}
	if($create_project=='1')
	{
		$str_url 		= '';
	}
	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_PATH.$ApplyOnlines->id.'/';
	}
	$IMAGE_EXT                  = array("png","jpg","gif","jpeg","bmp");

	/** STOP B CATEGORY INSTALLERS TO SUBMIT THE APPLICATION */
	$IsInstallerAllowedToSubmit = true;
	$ALERT_MESSAGE              = "";

	/*if($this->Session->read('Customers.customer_type')=='installer' && ($tab=='tab_1' || $tab=='') && $create_project=='1')
	{

		$CustomerID                 = $this->Session->read('Customers.id');
		$IsInstallerAllowedToSubmit = $ApplyOnlineObj->IsInstallerAllowedToSubmit($CustomerID);
		if (!$IsInstallerAllowedToSubmit) {
			$ALERT_MESSAGE = "You are not allowed to submit application for more than 140 kW. For further details contact GEDA office at Gandhinagar, GJ.";
		}
	}*/

	/** STOP B CATEGORY INSTALLERS TO SUBMIT THE APPLICATION */
	$newSchemeApp 		= 0;
	$pvCapacityText 	= 'DC';
	if(isset($ApplyOnlines->created) && strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
		$newSchemeApp 	= 1;
		$pvCapacityText = 'AC';
	}
	echo $this->Form->create($ApplyOnlines,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'contactForm', 'url' => '/add-additional-capacity/'.$str_url,'autocomplete'=>'off','onSubmit'=>'return CheckFormSubmit();'));
?>

<!-- File: src/Template/Users/login.ctp -->
<div class="container applay-online-from">
	<div class="row">
		<h2 class="col-md-9 mb-sm mt-sm"><strong>Apply</strong> Online</h2>
		<div class="col-md-3 pull-right">
			<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg pull-right">
			<?php echo $this->Html->link('My Application',['controller'=>'ApplyOnlines','action' => 'applyonline_list']); ?>
			</span>
		</div>
	</div>
	<?php echo $this->Flash->render('cutom_admin');

		if($quota_msg_disp!==true)
		{
			$IsInstallerAllowedToSubmit = false;
			/*
		?>

			<div class="message alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><?php echo $quota_msg_disp;?>
			</div>
		<?php
			*/
		}
		?>
		<div class="alert alert-warning">
			<strong>Notice!</strong>
			<ul>
			   <?php /* <li style="font-size: 1.0em;">Downtime schedule for maintenance activity at GUVNL  Data Center from 6th Sep 2019, 6:00PM  to  7th Sep 2019, 7:00AM.  Due to this API related service will not be available for the time.</li> 
				<li style="font-size: 1.0em;">There technical issue being faced in fetching the consumer data of UGVCL consumers has be resolved. The Installers can try it now.</li>*/?>
				 <?php /*<li style="font-size: 1.0em;">The Portal is live for online applications of Commercial/Industrial/Social Sector from 2nd May 2019 under non-subsidy scheme.</li>
			   <li style="font-size: 1.0em;">The applications under Residential, Commercial and Industrial categories are for the "Rooftop solar PV Power plant" or "Rooftop Solar PV System" or "Solar Generating Plant"  that means the Solar Photo Voltic Power including small solar system installed on the rooftops/ ground mounted or open land of consumer premises that uses sunlight for direct conversion into electricity through photovoltaic technology.</li>
				<li style="font-size: 1.0em;">Download Guidelines Document for .</li>
				<li style="font-size: 1.0em;">The receipt of the Application Fee made to GEDA shall be generated in the name of the Consumer who is the ultimate owner of the solar PV power system.</li>*/?>
				<?php /*
				Social Sector Applications are being reviewed and verified by GEDA and till then it will not be displayed in the My Application.
				<li style="font-size: 1.0em;">The Unified portal is live for social / institutional sector applications.</li>
				<li style="font-size: 1.0em;">The Rooftop Solar Policy of Gujarat has expired and all actions on GEDA portal are stopped till further notification on Policy is declared by the Government of Gujarat. For further details, kindly contact GEDA office at Gandhinagar.</li>*/?>
				<li style="font-size: 1.0em;">Application for PV Enhancement: 
				If earlier application is submitted online : kindly ensure meter installation stage ( stage no. 8) is cleared on online platform to do a fresh application under enhancement. If with out completion of stage 8, applicant wants to do the application then the existing application has to be deleted then apply as a fresh application.<br/>
				If applicable is submitted off-line: if existing capacity is not coming then enter your details in the link provided below. Link :  <a href="https://docs.google.com/forms/d/e/1FAIpQLSeMBAziq2jxuVAzritCeKXWyodIMzsAtQaBYffeaV5WgzGkBA/viewform?usp=sf_link" target="_blank">https://docs.google.com/forms/d/e/1FAIpQLSeMBAziq2jxuVAzritCeKXWyodIMzsAtQaBYffeaV5WgzGkBA/viewform?usp=sf_link</a></li>
				<li style="font-size: 1.0em;">In MSME Applications, it is mandatory to upload the Board Resolution as per the format provided in the Notice section below.</li>
				<li style="font-size: 1.0em;">For Delete Application Request, it is mandatory to use the format of consent letters as per the below mentioned formats.</li>
				<li style="font-size: 1.0em;"><span style="color:#ff0000;">Formats - </span><a href="/Format-for-Board-Authorization-Letter.docx" target="_blank">Board Authorization Letter</a>, <a href="/Format-for-Consent-from-Consumer.docx" target="_blank">Consent from Consumer</a>, <a href="/Format-for-Consent-from-Installer.docx" target="_blank">Consent from Installer</a>.</li>
				<li style="font-size: 1.0em;"><span style="color:#ff0000;">Download Guidelines Document for </span><a href="/meter_installation_procedure.pdf" target="_blank">Meter Installation Procedure</a>, <a href="/inverter_phase_change.pdf" target="_blank">Correction in Inverter Phase</a>, <a href="/Update_DisCom_Data.pdf" target="_blank">Update DisCom Data</a>, <a href="/Reduction_Capacity.pdf" target="_blank">Reduction in Capacity</a>, <a href="/installer_manual.pdf" target="_blank">Installer Manual</a>, <a href="/non-residential.pdf" target="_blank">Non- Residential Solar PV system</a> and <a href="/Delete-application.pdf" target="_blank">Delete Application</a>.</li>
				<li style="font-size: 1.0em;">All Installers who have not yet submitted there applications for MSME are informed to kindly download the new Application from "Download Application" and submit the same as signed document. The new update in the Application is effective from 14 October 2020.</li>
				<li style="font-size: 1.0em;">New Feature is activated for submission of applications for PV Enhancement.</li>
				<li style="font-size: 1.0em;">You can upload only pdf documents and the maximum size of upload shall be <strong>1MB</strong>.</li>
			   <?php /* <li style="font-size: 1.0em;">The applications of rooftop solar PV systems are allowed only under Non-Subsidy Scheme.</li>
				<li style="font-size: 1.0em;">All the applications submitted on the Unified Single Window Rooftop PV Portal between 1400 hours 26 December 2018 to 1400 hours on 28 December 2018 are reset to Application Form stage and it needs to be submitted only under the Non-Subsidy Scheme (except capacities between 1 kW and 1.3 kW).  Inconvenience caused is deeply regretted.</li>*/?>
				<?php /*<li style="font-size: 1.0em;">The Subsidy Claim module will be made live at 4:30 PM on 23 January 2019. Kindly refer the <a href="/installer_manual.pdf" target="_blank">Installer Manual</a> for detailed procedure.</li>*/?>
				<?php
				if(date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-2 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-1 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3)))
				{
					?>
					<li style="font-size: 1.0em;">You can submit application till <strong><?php echo date('d-M-Y H:i:s',strtotime(DATE_STOP_1_1_3));?> PM</strong>.</li>
					<?php
				}
				?>

			</ul>
		</div>
	<?php if ($IsInstallerAllowedToSubmit) { ?>
	<div class="tabs tabs-bottom tabs-simple nk_tabs">
		<ul class="nav nav-tabs">
			<?php
				$DisplayInstallerTab = ($this->Session->read('Customers.customer_type')=='installer')?"hide ":"";
				$DisplayCreateProTab = ($this->Session->read('Customers.customer_type')=='installer')?"":"hide";
				$DisplayInstallershowTab = '';
				if($this->Session->read('Customers.customer_type')=='installer' && ($tab=='tab_1' || $tab=='') && $create_project=='0')
				{
					$DisplayInstallershowTab ="active ";
				}
			?>
			<li class="<?php echo $DisplayInstallerTab;?><?php if($tab == ''){ echo 'active'; } ?>">
				<a href="#tabsNavigationSimple1" data-toggle="tab">Installer</a>
			</li>
			<?php if($create_project=='1') { ?>
				<li class="<?php echo $DisplayCreateProTab;?><?php if($tab == ''){ echo 'active'; } else if($tab == 'tab_1'){ echo 'active'; } ?>">
				<a href="#tabsNavigationSimple4" data-toggle="tab">Create Project</a>
				</li>
			<?php } ?>
			<li class="<?php echo $DisplayInstallershowTab;?><?php if(($tab == '' || $tab == 'tab_1') && !isset($edit_id) && $create_project=='1'){ echo 'desible'; } else if($tab == 'tab_1' && $create_project==0){ echo 'active'; } ?>">
				<a href="<?php if(($tab != '' || isset($edit_id)) && $create_project=='0') { echo '#tabsNavigationSimple2'; } else { echo 'javascript:;';} ?>" decibel="true" data-toggle="tab">Application</a>
			</li>
			<?php
			/*
			(($tab == '' || $tab == 'tab_1') && !isset($edit_id)) || !empty($ApplyOnlineErrors)
			(($tab == '' || $tab == 'tab_1') && !isset($edit_id)) || !empty($ApplyOnlineErrors)
			*/?>
			<li class="<?php if($tab != 'tab_2'){ echo 'desible'; } else if($tab == 'tab_2'){ echo 'active'; } ?>">
				<a href="<?php if($tab != 'tab_2') { echo 'javascript:;'; } else { echo '#tabsNavigationSimple3'; } ?>"  decibel="true" data-toggle="tab">Payment</a>
			</li>
		</ul>
		<input type="hidden" class="submit_captcha" name="submit_captcha" value="1" <?php echo  ($tab !='tab_2') ? 'disabled="disabled"' : '';?> />

		<div class="tab-content" >
			<div class="tab-pane <?php echo $DisplayCreateProTab;?><?php if(($tab == '' || $tab == 'tab_1') && $create_project=='1') { echo 'active'; } ?>" id="tabsNavigationSimple4" >
				<div class="form-group">
					<div class="col-md-8">
						<h4><u>Solar Project Location</u></h4>
					</div>
					<div class="col-md-4">
						<h6><?php echo 'PV capacity available slots '.implode(", ",$assign_slots);?></h6>
					</div>
				</div>
				<?php /*<div class="form-group">
					<div class="col-md-12" style="padding-left: 0px;">
						<input id="pac-input" class="form-control" type="text" placeholder="Search Box">
						<div id="myMap"></div>
					</div>
				</div>       */?>
				<div class="form-group">
					<div class="col-md-6">
						<label>Project Name <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('proj_name',array('label' => false,'class'=>'form-control','id'=>'proj_name')); ?>
					</div>
					<div class="2">
						<label>&nbsp;</label>
						<?php
							$LastMessageHtml    = "<div><span><b>Any name as per the discretion of Empanelled Agency. This is required for identification of the Project Application</b></span><br /><span></span></div>";
							$LastMessageRender  = "<span project_tips =\"popover\" title=\"<b>Tips</b>\" data-html=\"true\" data-content=\"".$LastMessageHtml."\"><b style=\"color:red;   \"><i class=\"fa fa-info-circle\" style=\"font-size: 18px;\"></i></b></span>";
							echo "<div class=\"row\">".$LastMessageRender."</div>";
						?>
					</div>
					<div class="col-md-2 hide">
						<label>Landmark </label>
						<?php
						echo $this->Form->input('landmark',array('label' => false,'class'=>'form-control','id'=>'landmark','placeholder'=>'Landmark')); ?>
					</div>

				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label>Latitude <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('latitude',array('label' => false,'class'=>'form-control','type'=>'text','id'=>'latitude','placeholder'=>'Latitude')); ?>
					</div>
					<div class="col-md-6">
						<label>Longitude <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('longitude',array('label' => false,'class'=>'form-control','type'=>'text','id'=>'longitude','placeholder'=>'Longitude')); ?>
					</div>
				</div>
				<?php
				$error_class_project_type = '';
				if(isset($ApplyOnlineErrors['project_type']) && isset($ApplyOnlineErrors['project_type']['_empty']) && !empty($ApplyOnlineErrors['project_type']['_empty'])){ $error_class_project_type = 'has-error'; }
				?>
				<div class="form-group">
					<div class="col-md-6 <?php echo $error_class_project_type;?>">
						<label>Category <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->select('project_type',$projectTypeArr,array('label' => false,'class'=>'form-control','id'=>'project_type','onChange'=>'javascript:changeCategory("project");')); ?>
						<?php
						if(!empty($error_class_project_type))
						{
							?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['project_type']['_empty']; ?></div>
							<?php
						}
						?>
					</div>
					<div class="col-md-6">
						<label>Area Type <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->select('area_type',$areaTypeArr,array('label' => false,'class'=>'form-control','id'=>'area_type')); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label>Rooftop Area <span class="mendatory_field">*</span></label>
						 <?php
						echo $this->Form->input('area',array('label' => false,'class'=>'form-control','id'=>'area','type'=>'text','onkeyup'=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
					</div>
					<div class="col-md-6">
						<label>Average Monthly Bill <span class="mendatory_field">*</span></label>
						<?php
						echo $this->Form->input('avg_monthly_bill',array('label' => false,'class'=>'form-control','id'=>'avg_monthly_bill','type'=>'text','onkeyup'=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label>Average Monthly Unit Consumed <span class="mendatory_field">*</span></label>
						<?php
							echo $this->Form->input('energy_con',array('label' => false,'class'=>'form-control','id'=>'energy_con','type'=>'text','onkeyup'=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
					</div>
					<div class="col-md-6">
						<label style="margin-left:20px;" class="hide">
							<?php echo $this->Form->input('project_common_meter', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'project_common_meter','onclick'=>'javascript:clickCommon("project_common_meter");')); ?>Is the Applicant a Common Meter Connection?
						</label>
						<label>Backup Type</label>
						<?php echo $this->Form->select('backup_type',$backupTypeArr,array('label' => false,'class'=>'form-control','empty'=>'None','onChange'=>'displayUsageHours(this.value)')); ?>
						<div class="row" id="usage_hours_div">
							<div class="form-group">
								<div class="col-md-12" >
									<label>Hours of Usage</label>
									<input type="text" maxlength="10" class="form-control" name="usage_hours" id="usage_hours">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
					
					</div>
					<?php
					$hide_class     = 'hide';
					if(SOCIAL_SECTOR=='0')
					{
						$hide_class = 'hide';
					}
					?>
					<div class="col-md-6 <?php echo $hide_class;?>">
						<label style="margin-left:20px;">
						<?php echo $this->Form->input('project_social_consumer', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'project_social_consumer')); ?>Is the Applicant a Social Sector Organization?
						</label>
					</div>

				</div>
				<?php
				$class_hide     = '';
				$checked     	= false;
				if($allocatedCategory==3)
				{
					$class_hide = 'hide';
					$checked 	= 'checked';
				}
				?>
				<div class="form-group <?php echo $class_hide;?>">
					<div class="col-md-6">
					<label style="margin-left:20px;">
						<?php echo $this->Form->input('project_disclaimer_subsidy', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'project_disclaimer_subsidy',"checked"=>$checked)); ?>I am applying for Solar PV System under Gujarat Solar Policy 2021.
						</label>
					</div>
				</div>
				<?php
				if($allocatedCategory==3)
				{
					?>
					<div class="form-group">
						<div class="col-md-12 oneline ">
							<label>
								<i class="fa fa-check"></i> I am applying for Solar PV System under Gujarat Solar Policy 2021.
							</label>
						</div>
					</div>
					<?php
				}
				?>
				
				<?php
				$captiveStatusClass = 'hide';
				if(IS_CAPTIVE_OPEN == 0)
				{
					$captiveStatusClass = 'hide';
				}
				?>
				<div class="form-group row captive_project <?php echo $captiveStatusClass;?>">
					<div class="col-md-12" style="margin-left:20px;">
						<label >
							<?php
							$checked        = false;
							$classAdd       = "hide";

							if(isset($ApplyOnlineErrors['project_renewable_attr']['_empty']) || $ApplyOnlines->project_renewable_attr_chk ==1)
							{
								$checked    = 'checked';
								$classAdd   = '';
							}
							echo $this->Form->input('project_renewable_attr_chk', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address renewable_attr_chk','id'=>'project_renewable_attr_chk','onclick'=>'javascript:toggel_captive();',"checked"=>$checked));
							?>
						The Applicant doesn’t want to keep the Renewable Attributes of this Solar PV system</label>
						<div class="div_renewable_attr <?php echo $classAdd;?>">
							<?php
							echo $this->Form->input('project_renewable_attr', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Yes (Type 1)","class"=>"renewable_attr"],
														['value' => 0, 'text' =>"No, the Applicant wants to keep the Renewable Attributes (Type 2A)","class"=>"renewable_attr"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '{{label}}'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
							?>
						</div>
					</div>
				</div>
				<div class="form-group row hide captive_project_rec">
					<div class="col-md-12" style="margin-left:20px;">
						<label>
							<?php
							echo $this->Form->input('project_rec_certificate', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address','id'=>'project_rec_certificate',"disabled"=>true,"checked"=>"checked"));
							//['value' => 1, 'text' =>"Yes","class"=>""],
							?>
						Is this Application done under the Renewable Energy Certificate (REC) Scheme?</label>
						<?php
						echo $this->Form->input('project_renewable_rec', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 0, 'text' =>"No, the application is to be done to meet the Renewable Purchase Obligation of the Applicant","class"=>"","checked"=>"checked"]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '{{label}}'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<?php echo $this->Form->input('Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'tab_4','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next4')); ?>
					</div>
				</div>
			</div>
			<div class="tab-pane <?php echo $DisplayInstallerTab;?><?php if($tab == ''){ echo 'active'; } ?>" id="tabsNavigationSimple1" >
				<div class="form-group">
					<div class="col-md-6">
					<label>Application from state</label>
					<?php
						echo $this->Form->input('project_pass_id', array('label' => false,'type'=>'hidden','class'=>'form-control','value'=>$project_id,'id'=>'project_pass_id'));
						echo $this->Form->input('ApplyOnlines.tariff', array('label' => false,'type'=>'hidden','class'=>'form-control','id'=>'tariff'));
						echo $this->Form->select('ApplyOnlines.apply_state',$state_list, array('label' => false,'class'=>'form-control','id'=>'apply_state','placeholder'=>'Discom Name'));
						?>
					</div>
				</div>
				<div class="form-group">
					<?php
						$error_class_installer_id = '';
						if(isset($ApplyOnlineErrors['installer_id']) && isset($ApplyOnlineErrors['installer_id']['_empty']) && !empty($ApplyOnlineErrors['installer_id']['_empty'])){ $error_class_installer_id = 'has-error'; }
					?>
					<div class="col-md-6 <?php echo $error_class_installer_id; ?>">
						<label>Installer</label>
						<?php echo $this->Form->input('ApplyOnlines.id', array('label' => false,'empty'=>'-Select Installer-','value'=>$id,'class'=>'form-control','type'=>'hidden','placeholder'=>'Discom Name')); ?>
						<?php asort($installer_list);?>
						<?php echo $this->Form->select('ApplyOnlines.installer_id',$installer_list, array('label' => false,'empty'=>'-select installer-','class'=>'form-control','placeholder'=>'Discom Name','id'=>'installer_id')); ?>
						<?php
						 if(isset($ApplyOnlineErrors['installer_id']) && isset($ApplyOnlineErrors['installer_id']['_empty']) && !empty($ApplyOnlineErrors['installer_id']['_empty'])){
						?>
						<div class="help-block"><?php echo $ApplyOnlineErrors['installer_id']['_empty']; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="form-group disclimer">
					<div class="col-md-12">
						<label>
							<?php echo $this->Form->input('ApplyOnlines.disclaimer', array('label' => false,'class'=>'form-control','type'=>'checkbox','placeholder'=>'Disclaimer')); ?>
							I hereby confirm to all the Terms and Conditions of Gujarat Energy Development Agency (GEDA), DisComs and the Scheme of GEDA. I also ensure that all the information in the Application Form is true and correct to the best of my knowledge.
						</label>
					</div>
					<br/>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<?php echo $this->Form->input('Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg ','name'=>'tab_1','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next1')); ?>
					</div>
				</div>
			</div>
			<div class="tab-pane <?php echo $DisplayInstallershowTab;?><?php if($tab == 'tab_1' && $create_project=='0'){ echo 'active'; } ?>" id="tabsNavigationSimple2">
				<div id="discomdata">
					<br>
					<h4><u>Discom Detail</u></h4>
					<div class="form-group" >
						<div class="form-group">
							<?php $error_discom_name="";
							if(isset($ApplyOnlineErrors['discom_name']) && isset($ApplyOnlineErrors['discom_name']['_empty']) && !empty($ApplyOnlineErrors['discom_name']['_empty'])){ $error_discom_name="has-error"; }?>
							<?php $error_discom="";
							if(isset($ApplyOnlineErrors['discom']) && isset($ApplyOnlineErrors['discom']['_empty']) && !empty($ApplyOnlineErrors['discom']['_empty'])){ $error_discom="has-error"; }?>
							 <div class="col-md-4 <?php echo $error_discom; ?>">
								<label>DisCom<span class="mendatory_field">*</span></label>
								 <?php echo $this->Form->select('ApplyOnlines.discom',$discom_arr, array('label' => false,'empty'=>'-Select DisCom-','class'=>'form-control','placeholder'=>'DisCom','id'=>'discom','onchange' => 'ShowHideDiv()'));
								if(isset($ApplyOnlineErrors['discom']) && isset($ApplyOnlineErrors['discom']['_empty']) && !empty($ApplyOnlineErrors['discom']['_empty'])){  ?>
								<div class="help-block"><?php echo $ApplyOnlineErrors['discom']['_empty']; ?></div>
								<?php } ?>
							</div>
							<div class="col-md-4">
								<?php
								$isReadonly     = false;
								if(!empty($ApplyOnlines->id))
								{
									$approval   = $MStatus->Approvalstage($ApplyOnlines->id);
									if(in_array($MStatus->APPLICATION_SUBMITTED,$approval))
									{
										$isReadonly     = true;
									}
								}
								?>
								<label>Consumer No.<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('ApplyOnlines.consumer_no', array('label' => false,'class'=>'form-control','id'=>'consumer_no','placeholder'=>'Consumer NO','readonly'=>$isReadonly)); ?>
							</div>
							<div class="col-md-4 <?php echo $error_discom_name; ?>">
								<label>Division/Zone<span class="mendatory_field">*</span></label>
								 <?php echo $this->Form->select('ApplyOnlines.discom_name',$discom_list, array('label' => false,'empty'=>'-Select Division-','class'=>'form-control','id'=>'division','placeholder'=>'Division','onchange'=>'javascript:click_division();'));
								if(isset($ApplyOnlineErrors['discom_name']) && isset($ApplyOnlineErrors['discom_name']['_empty']) && !empty($ApplyOnlineErrors['discom_name']['_empty'])){  ?>
								<div class="help-block"><?php echo $ApplyOnlineErrors['discom_name']['_empty']; ?></div>
								<?php } ?>
								<div id="subdivision"></div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4" style="display: none" id="tno">
							 <label>T-NO<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('ApplyOnlines.tno', array('label' => false,'class'=>'form-control','id'=>'t_no','placeholder'=>'T-NO')); ?>
						</div>
						<?php echo $this->Form->input('ApplyOnlines.id', array('label' => false,'vlaue'=>$id,'class'=>'form-control','type'=>'hidden','placeholder'=>'Discom Name','id'=>'application_id')); ?>
						<div class="col-md-2" >
						<?php echo $this->Form->input('Search', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg search','value'=>'Show Div','id'=>'search_data_btn','onclick'=>'javascript:click_search();','type'=>'button','templates' => ['inputContainer' => '{{content}}'])); ?>
						</div>
						<div class="col-md-1" id="spinner" style="display: none;" >
							<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px; margin-top: 14px;color:#71bf57"></i>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label>Sanctioned /Contract Load (in kW)<span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('ApplyOnlines.sanction_load_contract_demand', array('label' => false,'class'=>'form-control','placeholder'=>'Sanction Load/Contract Demand','id'=>'sanction_load')); ?>
						</div>
						<div class="col-md-4">
							<label>Who will provide the Net-Meter?<span class="mendatory_field">*</span></label>
							<?php
							echo $this->Form->input('ApplyOnlines.net_meter', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"DisCom","class"=>"payment_mode_choice"],
														['value' => 2, 'text' =>"Installer/ EA","class"=>"payment_mode_choice"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '{{label}}'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
							?>
						</div>
						<div class="col-md-4">
							<label>&nbsp;</label>
							<?php
							$LastMessageHtml    = "<div><span><b>In case of UGVCL and PGVCL, Installer can opt to provide the Net-Meter but for all other DisComs, Discom will provide it.</b></span><br /><span></span></div>";
							$LastMessageRender  = "<span data-toggle=\"popover\" title=\"<b>Tips</b>\" data-html=\"true\" data-content=\"".$LastMessageHtml."\"><b style=\"color:red;   \"><i class=\"fa fa-info-circle\" style=\"font-size: 18px;\"></i></b></span>";
							echo "<div class=\"row\">".$LastMessageRender."</div>";
							?>
						</div>
					</div>
					<div class="form-group">
						<?php $error_class_category = "";
						if(isset($ApplyOnlineErrors['category']) && isset($ApplyOnlineErrors['category']['_empty']) && !empty($ApplyOnlineErrors['category']['_empty'])){ $error_class_category = 'has-error'; }?>
						<div class="col-md-4 <?php echo $error_class_category; ?>">
							<label>Category</label>
							<?php echo $this->Form->select('ApplyOnlines.category',$customer_type_list, array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Select Category-','placeholder'=>'Comunication Address','id'=>'category','onchange'=>'javascript:changeCategory("applyonline");'));
							if(isset($ApplyOnlineErrors['category']) && isset($ApplyOnlineErrors['category']['_empty']) && !empty($ApplyOnlineErrors['category']['_empty'])){  ?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['category']['_empty']; ?></div>
							<?php } ?>
						</div>
						<?php $class = 'col-md-8';
							if($newSchemeApp == 1) { ?>
							<div class="col-md-4">
								<label>Plant (DC) to be installed (in kW)<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('ApplyOnlines.pv_dc_capacity', array('label' => false,'class'=>'form-control','placeholder'=>'Plant DC Capacity','onkeypress'=>"return validateDecimal(event)")); ?>
							</div>
						<?php $class 	= 'col-md-4';} ?>
						<div class="<?php echo $class;?>">
							<label>Phase of proposed Solar Inverter<span class="mendatory_field">*</span></label>
							<?php
							echo $this->Form->input('ApplyOnlines.transmission_line', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Single Phase","class"=>"payment_mode_choice"],
														['value' => 3, 'text' =>"3 Phase","class"=>"payment_mode_choice"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '{{label}}'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
							?>
						</div>
						
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label>Additional PV Capacity (<?php echo $pvCapacityText;?>) to be installed (in kW)<span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('ApplyOnlines.pv_capacity', array('label' => false,'class'=>'form-control','id'=>'pv_capacity','placeholder'=>'PV Capacity','onkeypress'=>"check_pv_cap();return validateDecimal(event)",'onblur'=>'javascript:check_pv_cap();')); ?>
						</div>
						<div class="col-md-4">
							<label>&nbsp;</label>
							<?php
							$existCapacity 			= '';
							$existCapacityval 		= '';
							if(isset($ApplyOnlinesOthers->existing_capacity) && !empty($ApplyOnlinesOthers->existing_capacity))
							{
								$existCapacity 		= ' Existing Capacity - '.$ApplyOnlinesOthers->existing_capacity.' kW';
								$existCapacityval 	= $ApplyOnlinesOthers->existing_capacity;
							}
							
							echo "<div class=\"row\"><label id=\"installed_capacity_text\" style=\"color:#FF0000;margin-left:5px;\">".$existCapacity."</label></div>";
							?>
							<?php echo $this->Form->input('ApplyOnlines.existingCapacity', array('type'=>'hidden','label' => false,'class'=>'form-control','id'=>'existingCapacity','value'=>$existCapacityval)); ?>
						</div>
						<?php if($newSchemeApp == 1) { ?>
							<div class="col-md-4">
								<label>Existing (AC) to be installed (in kW)<span class="mendatory_field">*</span></label>
								<?php echo $this->Form->input('ApplyOnlines.existing_ac_capacity', array('label' => false,'class'=>'form-control','placeholder'=>'Existing AC Capacity','onkeypress'=>"return validateDecimal(event)")); ?>
							</div>
						<?php } ?>
						<?php /*<div class="col-md-4">
							<label>&nbsp;</label>
							<?php
							$LastMessageHtml    = "<div><span><b>PV capacity available slots ".implode(", ",$assign_slots)."</b></span><br /><span></span></div>";
							$LastMessageRender  = "<span data-toggle=\"popover\" title=\"<b>Tips</b>\" data-html=\"true\" data-content=\"".$LastMessageHtml."\"><b style=\"color:red;   \"><i class=\"fa fa-info-circle\" style=\"font-size: 18px;margin-top:10px;\"></i></b></span>";
							echo "<div class=\"row\">".$LastMessageRender."</div>";
							?>
						</div>*/?>
						
					</div>
				</div>
				<h4><u> Consumer Detail</u></h4>
				<div class="form-group">
					<div class="col-md-3">
						<label>Consumer Email</label>
						<?php echo $this->Form->input('ApplyOnlines.consumer_email', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Email')); ?>
					</div>
					<div class="col-md-3">
						<label>Consumer Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.consumer_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
					</div>
					<?php
						$error_class_for_ins_prefix = '';
						if(isset($ApplyOnlineErrors['installer_email']) && isset($ApplyOnlineErrors['installer_email']['_empty_installer_email']) && !empty($ApplyOnlineErrors['installer_email']['_empty_installer_email'])){ $error_class_for_ins_prefix ='has-error'; } ?>
					<div class="col-md-3 <?php echo $error_class_for_ins_prefix; ?>">
						<label>Installer Email<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.installer_email', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Email'));
						if(isset($ApplyOnlineErrors['installer_email']) && isset($ApplyOnlineErrors['installer_email']['_empty_installer_email']) && !empty($ApplyOnlineErrors['installer_email']['_empty_installer_email']))
						{
						?>
						<div class="help-block"><?php echo $ApplyOnlineErrors['installer_email']['_empty']; ?></div>
						<?php
						} ?>
					</div>
					<div class="col-md-3">
						<label>Installer Mobile<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.installer_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
					</div>
				</div>
				<div class="form-group">
					<?php
						$error_class_for_name_prefix = '';
						if(isset($ApplyOnlineErrors['customer_name_prefixed']) && isset($ApplyOnlineErrors['customer_name_prefixed']['_empty']) && !empty($ApplyOnlineErrors['customer_name_prefixed']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
					<div class="col-md-2 <?php echo $error_class_for_name_prefix; ?>">
						<label>Name prefix<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('ApplyOnlines.customer_name_prefixed',$customer_name_prifix, array('label' => false,'class'=>'form-control','empty'=>'-Select Prifix-'));
						if(isset($ApplyOnlineErrors['customer_name_prefixed']) && isset($ApplyOnlineErrors['customer_name_prefixed']['_empty']) && !empty($ApplyOnlineErrors['customer_name_prefixed']['_empty']))
						{
						?>
						<div class="help-block"><?php echo $ApplyOnlineErrors['customer_name_prefixed']['_empty']; ?></div>
						<?php
						}
						?>
					</div>
					<?php
						$cols_data  = 3;
						$place_text = 'Middle Name';
					?>
					<div class="col-md-<?php echo $cols_data;?>">
						<label>First Name<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.name_of_consumer_applicant', array('label' => false,'class'=>'form-control','placeholder'=>'First Name','id'=>'name_of_consumer_applicant')); ?>
					</div>
					<div class="col-md-<?php echo $cols_data;?>">
						<label>&nbsp;</label>
						<?php
						 echo $this->Form->input('ApplyOnlines.last_name', array('label' => false,'class'=>'form-control','placeholder'=>$place_text,'id'=>'middle_name')); ?>
					</div>
					<div class="col-md-4">
						<label>Last Name</label>
						<?php
						echo $this->Form->input('ApplyOnlines.third_name', array('label' => false,'class'=>'form-control','placeholder'=>'Last Name','id'=>'third_name'));
						?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label>Landline No</label>
						 <?php echo $this->Form->input('ApplyOnlines.landline_no', array('label' => false,'class'=>'form-control','placeholder'=>'Landline No')); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label>Street/House No.<span class="mendatory_field">*</span></label>
						 <?php echo $this->Form->input('ApplyOnlines.address1', array('label' => false,'class'=>'form-control','id'=>'address1','placeholder'=>'Address 1','id'=>'add1')); ?>
					</div>
					<div class="col-md-4">
						<label>Taluka<span class="mendatory_field">*</span></label>
						 <?php echo $this->Form->input('ApplyOnlines.address2', array('label' => false,'class'=>'form-control','placeholder'=>'Taluka','id'=>'add2')); ?>
					</div>
					<div class="col-md-4">
						<label>District</label>
						 <?php echo $this->Form->input('ApplyOnlines.district', array('label' => false,'class'=>'form-control','placeholder'=>'District','id'=>'district')); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label>City/Village<span class="mendatory_field">*</span></label>
						 <?php echo $this->Form->input('ApplyOnlines.city', array('label' => false,'class'=>'form-control','placeholder'=>'City','id'=>'city')); ?>
					</div>
					<div class="col-md-4">
						<label>State<span class="mendatory_field">*</span></label>
						 <?php echo $this->Form->input('ApplyOnlines.state', array('label' => false,'class'=>'form-control','placeholder'=>'State','id'=>'state')); ?>
					</div>
					<div class="col-md-4">
						<label>Pincode<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.pincode', array('type'=>'text','label' => false,'class'=>'form-control','placeholder'=>'Pincode','id'=>'pincode')); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="lbl_comunication_address">
						<?php echo $this->Form->input('ApplyOnlines.comunication_address_as_above', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'chk_comunication_address form-control check-box-address ','placeholder'=>'Communication Address')); ?>Communication address as per above</label>
						<br/>
						<span class="comunication-address">
						<label>Communication Address<span class="mendatory_field">*</span></label>
						 <?php echo $this->Form->textarea('ApplyOnlines.comunication_address', array('label' => false,'class'=>'form-control','placeholder'=>'Communication Address')); ?>
						 </span>
						 <?php
						if(isset($ApplyOnlineErrors['comunication_address']) && isset($ApplyOnlineErrors['comunication_address']['_empty']) && !empty($ApplyOnlineErrors['comunication_address']['_empty'])) {  ?>
							<div class="help-block" style="color:#a94442;"><?php echo $ApplyOnlineErrors['comunication_address']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-6" style="margin-top: 35px;">
						<label class="lbl_comunication_address">
						Passport Size Photo of Consumer<span class="mendatory_field">*</span></label>
						<br/>
						<div class="file-loading" >
							<?php echo $this->Form->input('ApplyOnlines.profile_image', array('label' => false,'div' => false,'type'=>'file','id'=>'profile_image','templates' => ['inputContainer' => '{{content}}'])); ?>
						</div>
						<div id="profile_image-file-errors"></div>
						<?php
						$profile_image_id = '';
						if(!empty($Applyonlinprofile)) {
							if ($Couchdb->documentExist($ApplyOnlines->id,$Applyonlinprofile['file_name'])) {
								$profile_image_ext = pathinfo($DOCUMENT_PATH.$Applyonlinprofile['file_name'],PATHINFO_EXTENSION);
								if (in_array($profile_image_ext,$IMAGE_EXT)) {
						?>

							<img style="width:125px;" src="<?php echo URL_HTTP.'app-docs/profile/'.encode($ApplyOnlines->id); ?>"/>
						<?php
								} else {
									echo "<strong><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/profile/'.encode($ApplyOnlines->id)."\">View Profile Image</a></strong>";
								}
							}
							$profile_image_id = $Applyonlinprofile['id'];
						}
						echo $this->Form->input('ApplyOnlines.profile_image_id', array('label' => false,'type'=>'hidden','value'=>$profile_image_id,'class'=>'form-control'));
						?>
					</div>
				</div>
				<h4><u>Additional Details</u></h4>
				<div class="form-group">
					<div class="col-md-4 <?php echo $hide_class;?>">
					<label>
						<?php
						$flg_disp       = '1';
						if(!empty($ApplyOnlines->id))
						{
							$approval   = $MStatus->Approvalstage($ApplyOnlines->id);
							if((in_array($MStatus->APPLICATION_SUBMITTED,$approval) && $ApplyOnlines->social_consumer==1) || $ProjectsDetails->project_social_consumer==1)
							{
								$disabled   = 'true';
								echo $this->Form->input('ApplyOnlines.social_consumer', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address ','id'=>'social_consumer_guj',"disabled"=>$disabled));
								$flg_disp   = '0';
							}
						}
						if($flg_disp == '1')
						{
							?>
							<?php echo $this->Form->input('ApplyOnlines.social_consumer', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'social_consumer_guj form-control check-box-address','placeholder'=>'','id'=>'social_consumer_guj')); ?>
							<?php
						}
						?>
						Is the Applicant a Social Sector Organization?
					</label>
					</div>
					<?php
					$hide_class_gov     = '';
					if(GOVERMENT_AGENCY=='0')
					{
						$hide_class_gov = 'hide';
					}
					?>
					<div class="col-md-4 <?php echo $hide_class_gov;?>">
						<label>
							<?php
							$disabled   = 'false';
							if(isset($approval) && in_array($MStatus->APPLICATION_SUBMITTED,$approval))
							{
								$disabled   = 'true';
							}
							echo $this->Form->input('ApplyOnlines.govt_agency', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'govt_agency form-control check-box-address checkdata','placeholder'=>'','id'=>'govt_agency',"disabled"=>$disabled)); ?> Is the Applicant a Government Agency?
						</label>
					</div>
					<div class="col-md-4 <?php echo $hide_class_gov;?> <?php echo $hide_class;?>">
						<label>&nbsp;</label>
						<?php
							$LastMessageHtml    = "<div><span><b>Tick the box if the applicant is either a Social Sector or Government Agency else leave it unchecked.</b></span><br /><span></span></div>";
							$LastMessageRender  = "<span data-toggle=\"popover\" title=\"<b>Tips</b>\" data-html=\"true\" data-content=\"".$LastMessageHtml."\"><b style=\"color:red;   \"><i class=\"fa fa-info-circle\" style=\"font-size: 18px;\"></i></b></span>";
							echo "<div class=\"row\">".$LastMessageRender."</div>";
						?>
					</div>
				</div>
				<div class="form-group hide">
					<div class="col-md-6" >
						<label>Location of Proposed Rooftop Solar PV System</label>
						 <?php echo $this->Form->input('ApplyOnlines.roof_of_proposed', array('label' => false,'class'=>'form-control','placeholder'=>'Location of Proposed Rooftop Solar PV System')); ?>
					</div>
				</div>
				<div class="form-group hide" >
					<div class="col-md-6">
						<label>Average Monthly Units Consumed (kWh/month)</label>
						 <?php echo $this->Form->input('ApplyOnlines.energy_con', array('label' => false,'type'=>'number','class'=>'form-control','placeholder'=>'Average Monthly Units Consumed')); ?>
					</div>
					<div class="col-md-6">
						<label>Average Monthly Bill (in &#8377) </label>
						 <?php echo $this->Form->input('ApplyOnlines.bill', array('label' => false,'type'=>'number','class'=>'form-control','placeholder'=>'Average Monthly Bill')); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 <?php echo ($newSchemeApp == 1) ? 'hide' : '';?> ">
						<label >
							<?php echo $this->Form->input('ApplyOnlines.common_meter', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'common_meter','onclick'=>'javascript:clickCommon("common_meter");')); ?>Is the Applicant a Common Meter Connection?
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label>Whether the Premises is owned or Rented</label>
						<?php
						echo $this->Form->input('ApplyOnlines.owned_rented', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 0, 'text' =>"Owned ","class"=>"payment_mode_choice"],
													['value' => 1, 'text' =>"Rented","class"=>"payment_mode_choice"]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '{{label}}'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
					<div class="col-md-4 hide">
						<label>Location of Proposed Rooftop Solar PV System</label>
						<?php echo $this->Form->input('ApplyOnlines.location_proposed', array('label' => false,'type'=>'text','class'=>'form-control','placeholder'=>'')); ?>
					</div>
				</div>
				<h4><u>Document Details</u></h4>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-4">Attach Electricity Bill<span class="mendatory_field">*</span></label>
						<div class="col-md-4">
							<?php echo $this->Form->input('ApplyOnlines.file_attach_recent_bill', array('label' => false,'type'=>'file','id'=>'electricity_bill','class'=>'form-control','placeholder'=>'Recent Bill.')); ?>
							<div id="ele_bill-file-errors"></div>
						</div>
						<br/>
						<?php if(!empty($ApplyOnlines->attach_recent_bill)) : ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_recent_bill)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->attach_recent_bill,PATHINFO_EXTENSION);
									if (in_array($file_ext,$IMAGE_EXT)) {
								?>
										<img src="<?php echo URL_HTTP.'app-docs/attach_recent_bill/'.encode($ApplyOnlines->id); ?>"/>
								<?php } else { ?>
									<?php
										echo "<strong><a target=\"_RECENTBILL\" href=\"".URL_HTTP.'app-docs/attach_recent_bill/'.encode($ApplyOnlines->id)."\">View Recent Bill</a></strong>";
									?>
								<?php } ?>
							<?php } ?>
						<?php endif; ?>
					</div>
				</div>
				<?php
				$adhar_disp = '';
				if(!empty($this->Session->read('Members.member_type')))
				{
					$adhar_disp = 'hide';
				}
				?>
				<div class="form-group">
					<div class="row <?php echo $adhar_disp;?>">
						<div class="col-md-4" id="show_ad_label">
							<label>Aadhaar no.<span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('ApplyOnlines.aadhar_no_or_pan_card_no', array('label' => false,'class'=>'form-control','placeholder'=>'Aadhaar no.','type'=>'text')); ?>
						</div>
						<div class="col-md-4" id="show_ad_text">
							<label>Aadhaar Card/ Other ID Card<span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('ApplyOnlines.file_attach_photo_scan_of_aadhar', array('label' => false,'type'=>'file','id'=>'aadhar_card','class'=>'form-control','placeholder'=>'Comunication Address'));  ?>
							<div id="aadhar_card-file-errors"></div>
							<br/>
							<?php if(!empty($ApplyOnlines->attach_photo_scan_of_aadhar)) { ?>
								<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_photo_scan_of_aadhar)) { ?>
									<?php
										$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->attach_photo_scan_of_aadhar,PATHINFO_EXTENSION);
										if (in_array($file_ext,$IMAGE_EXT)) {
									?>
											<img src="<?php echo URL_HTTP.'app-docs/attach_photo_scan_of_aadhar/'.encode($ApplyOnlines->id); ?>"/>
									<?php } else { ?>
										<?php
											echo "<strong><a target=\"_AADHAR\" href=\"".URL_HTTP.'app-docs/attach_photo_scan_of_aadhar/'.encode($ApplyOnlines->id)."\">View Aadhar Card/ Other ID Card</a></strong>";
										?>
									<?php } ?>
								<?php } ?>
							 <?php } ?>
						</div>
						<div class="col-md-4" id="show_pan_label">
							<label>PAN card no.<span class="mendatory_field">*</span> </label>
							<?php echo $this->Form->input('ApplyOnlines.pan_card_no', array('label' => false,'class'=>'form-control','placeholder'=>'Pan card no.','type'=>'text')); ?>
						</div>
						<div class="col-md-4" id="show_pan_text">
							<label>Pan card<span class="mendatory_field">*</span></label>
							<?php echo $this->Form->input('ApplyOnlines.file_attach_pan_card_scan', array('label' => false,'type'=>'file','id'=>'attached_pan','class'=>'form-control','placeholder'=>'Comunication Address')); ?>
							<div id="attached_pan-file-errors"></div>
							<br/>
							<?php if(!empty($ApplyOnlines->attach_pan_card_scan)) { ?>
								<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_pan_card_scan)) { ?>
									<?php
										$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->attach_pan_card_scan,PATHINFO_EXTENSION);
										if (in_array($file_ext,$IMAGE_EXT)) {
									?>
										<img src="<?php echo URL_HTTP.'app-docs/attach_pan_card_scan/'.encode($ApplyOnlines->id); ?>"/>

									<?php } else { ?>
										<?php
											echo "<strong><a target=\"_PANCARD\" href=\"".URL_HTTP.'app-docs/attach_pan_card_scan/'.encode($ApplyOnlines->id)."\">View PanCard</a></strong>";
										?>
									<?php } ?>
								<?php } ?>
							 <?php } ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
					<?php
						$text_house_no  	= 'Premises Ownership Details No';
						$validation_sign	= '<span class="mendatory_field">*</span>';
						$text_house_attach  = 'Premises Ownership Document';
						$uploaded_text		= 'Ownership';
						$submitedStage 		= $MStatus->getsubmittedStageData($ApplyOnlines->id);
						if($ApplyOnlines->govt_agency == 1 && (strtotime($submitedStage) >= strtotime(GOVERMNET_AGENCY_DOCUMENTNOT) || strtotime($ApplyOnlines->created) >= strtotime(GOVERMNET_AGENCY_DOCUMENTNOT))) {
							$text_house_no  	= 'Work Order No';
							$text_house_attach  = 'Work Order Document';
							$uploaded_text		= 'Work Order';
						}
					?>
					<label><span id="house_no_text"><?php echo $text_house_no;?></span><?php echo $validation_sign;?></label>
					<?php echo $this->Form->input('ApplyOnlines.house_tax_holding_no', array('label' => false,'class'=>'form-control','placeholder'=>'','type'=>'text'));
					?>
					</div>
					<div class="col-md-4">
						<label><span id="house_no_attach"><?php echo $text_house_attach;?></span><?php echo $validation_sign;?></label>
						<?php echo $this->Form->input('ApplyOnlines.file_attach_latest_receipt', array('label' => false,'type'=>'file','id'=>'file_attached_receipt','class'=>'form-control','placeholder'=>'Comunication Address'));
						?>
						<div id="file_receipt-file-errors"></div>
						<br/>
						<?php if(!empty($ApplyOnlines->attach_latest_receipt)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->attach_latest_receipt)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->attach_latest_receipt,PATHINFO_EXTENSION);
									if (in_array($file_ext,$IMAGE_EXT)) {
								?>

									<img src="<?php echo URL_HTTP.'app-docs/attach_latest_receipt/'.encode($ApplyOnlines->id); ?>"/>
								<?php } else { ?>
									<?php
										echo "<strong><a target=\"_OWNERSHIP\" href=\"".URL_HTTP.'app-docs/attach_latest_receipt/'.encode($ApplyOnlines->id)."\">View $uploaded_text Document</a></strong>";
									?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<label>&nbsp;</label>
						<?php
							$LastMessageHtml    = "<div><span><b>Any one of Municipal Tax Receipt/ Sales and Purchase Deed/ Land Ownership Document/ Society Letter</b></span><br /><span></span></div>";
							$LastMessageRender  = "<span data-toggle=\"popover\" title=\"<b>Tips</b>\" data-html=\"true\" data-content=\"".$LastMessageHtml."\"><b style=\"color:red;   \"><i class=\"fa fa-info-circle\" style=\"font-size: 18px;\"></i></b></span>";
							echo "<div class=\"row\">".$LastMessageRender."</div>";
						?>
					</div>
				</div>
				<?php
				$hideFiles              = '';
				if(!isset($ApplyOnlines->category) || (!empty($ApplyOnlines->category) && $ApplyOnlines->category == $ApplyOnlineObj->category_residental))
				{
					$hideFiles          = 'hide';
				}
				?>
				<div class="form-group hideFilesData <?php echo $hideFiles;?>">
					<?php
					$error_f_class          = '';
					$hiddenVal              = '';
					if(isset($ApplyOnlineErrors['hi_file_company_incorporation']) && isset($ApplyOnlineErrors['hi_file_company_incorporation']['_empty']) && !empty($ApplyOnlineErrors['hi_file_company_incorporation']['_empty']))
						{
							$error_f_class  = 'has-error';
						}
						?>
					<div class="col-md-12 <?php echo $error_f_class;?>">
						<label class="col-md-4">Company Incorporation / Registration Certificate or Partnership deed<span class="mendatory_field">*</span></label>
						<div class="col-md-4">
							<?php echo $this->Form->input('file_company_incorporation', array('label' => false,'type'=>'file','id'=>'file_company_incorporation','class'=>'form-control','placeholder'=>'Recent Bill.')); ?>
							<div id="cominc-file-errors"></div>

						<br/>
						<?php if(!empty($ApplyOnlinesOthers->file_company_incorporation)) : ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlinesOthers->file_company_incorporation)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlinesOthers->file_company_incorporation,PATHINFO_EXTENSION);

									echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/file_company_incorporation/'.encode($ApplyOnlines->id)."\">View Company Incorporation</a></strong>";
									$hiddenVal = $ApplyOnlinesOthers->file_company_incorporation;
								}
								?>
						<?php endif; ?>
						</div>
						<div class="col-md-4">

							<?php echo $this->Form->input('ApplyOnlines.hi_file_company_incorporation', array('label' => false,'type'=>'hidden','id'=>'hi_file_company_incorporation','class'=>'form-control','value'=>$hiddenVal)); ?>
						</div>
					</div>
					<div class="col-md-12 <?php echo $error_f_class;?>">
						<div class="col-md-4">&nbsp;</div>
						<?php
							if(!empty($error_f_class))
							{
								?>
								<div class="help-block col-md-4"><?php echo $ApplyOnlineErrors['hi_file_company_incorporation']['_empty']; ?></div>
								<?php
							}
						?>
					</div>
				</div>
				<?php
				$error_f_class          = '';
					if(isset($ApplyOnlineErrors['hi_file_board']) && isset($ApplyOnlineErrors['hi_file_board']['_empty']) && !empty($ApplyOnlineErrors['hi_file_board']['_empty']))
						{
							$error_f_class  = 'has-error';
						}
						$hiddenVal = '';
				?>
				<div class="form-group hideFilesData <?php echo $hideFiles;?>">
					<div class="col-md-12 <?php echo $error_f_class;?>">
						<label class="col-md-4">Copy of Board resolution authorizing person for signing all the documents related to proposed project<span class="mendatory_field">*</span></label>
						<div class="col-md-4">
							<?php echo $this->Form->input('file_board', array('label' => false,'type'=>'file','id'=>'file_board','class'=>'form-control','placeholder'=>'Recent Bill.')); ?>
							<div id="board-file-errors"></div>

						<br/>
						<?php if(!empty($ApplyOnlinesOthers->file_board)) : ?>
							<?php if($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlinesOthers->file_board)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlinesOthers->file_board,PATHINFO_EXTENSION);

									echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/file_board/'.encode($ApplyOnlines->id)."\">View Copy of Board</a></strong>";
									$hiddenVal = $ApplyOnlinesOthers->file_board;
								}
								?>
						<?php endif; ?>
						</div>
						<div class="col-md-4">

							<?php echo $this->Form->input('ApplyOnlines.hi_file_board', array('label' => false,'type'=>'hidden','id'=>'hi_file_board','class'=>'form-control','value'=>$hiddenVal)); ?>
						</div>
					</div>
					<div class="col-md-12 <?php echo $error_f_class;?>">
						<div class="col-md-4">&nbsp;</div>
						<?php
							if(!empty($error_f_class))
							{
								?>
								<div class="help-block col-md-4"><?php echo $ApplyOnlineErrors['hi_file_board']['_empty']; ?></div>
								<?php
							}
						?>
					</div>
				</div>
				<div class="form-group">
					<div style="clear: both;"></div>
					<div class="col-md-6">
						<h4><u>Other Document to be attached here</u></h4>
					</div>
					<div class="col-md-6">
						<a style="color:#fff !important;padding:4px!important;margin:0px;" href="javascript:;" class="btn btn-primary btn-lg mb-xlg cbtnsendmsg" onclick="javascript:show_remaining();"><i class="fa fa-plus-circle" style="font-size: 20px;"></i> Click here to attach more Document</a>
					</div>
					<?php if(isset($ApplyonlinDocsList) && !empty($ApplyonlinDocsList)) { ?>
						<?php foreach ($ApplyonlinDocsList as $key => $value) { ?>
								<span class="add_doc">
									<div style="clear: both;"></div>
									<div class="col-md-4">
										<?php if($key == 0) { ?>
											<label>Document name</label>
										<?php }
										echo $this->Form->input('ApplyOnlines.Aplication_doc_title][', array('label' => false,'type'=>'text','class'=>'form-control','value'=>$value['title']));
										echo $this->Form->input('ApplyOnlines.Aplication_doc_id][', array('label' => false,'type'=>'hidden','value'=>$value['id'],'class'=>'form-control'));
										?>
									</div>
									<div class="col-md-4">
										<?php if($key == 0) { ?>
										<label>Document file</label>
										<?php }
										$disp_id = "applied_doc_".($key+1);
										echo $this->Form->input('ApplyOnlines.Aplication_doc_file][', array('label' => false,'type'=>'file','class'=>'form-control','id'=>$disp_id)); ?>
										<div id="<?php echo $disp_id;?>-file-errors"></div>
									</div>
									<div class="col-md-4">
									<?php $path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$value['file_name'];
										if($Couchdb->documentExist($ApplyOnlines->id,$value['file_name'])) {
											$file_ext = pathinfo($path,PATHINFO_EXTENSION);

											if (in_array($file_ext,$IMAGE_EXT))
											{
											?>
												<img style="width:50%;" src="<?php echo URL_HTTP.'app-docs/'.$value['doc_type'].'/'.encode($value['id']); ?>"/>

									<?php   }
											else
											{
												if($key == 0)
												{
													echo "<br/>";
												}
												echo "<strong><a target=\"_OWNERSHIP\" href=\"".URL_HTTP.'app-docs/'.$value['doc_type'].'/'.encode($value['id'])."\">View Document</a></strong>";

											}
										}
									?>

									</div>
								</span>
						<?php
							}
							if(($key+1)==1)
							{
								?>
								<span class="add_doc hide" id="add_doc_2">
									<div style="clear: both;"></div>
									<div class="col-md-4">
										<?php
										echo $this->Form->input('ApplyOnlines.Aplication_doc_title][', array('label' => false,'type'=>'text','class'=>'form-control','id'=>'doc_title_2'));
										echo $this->Form->input('ApplyOnlines.Aplication_doc_id][', array('label' => false,'type'=>'hidden','class'=>'form-control','id'=>'doc_id_2'));
										?>
									</div>
									<div class="col-md-4">
										<?php echo $this->Form->input('ApplyOnlines.Aplication_doc_file][', array('label' => false,'type'=>'file','class'=>'form-control add_document','id'=>'applied_doc_2')); ?>
										<div id="applied_doc_2-file-errors"></div>
									</div>
								</span>
								<?php
							}
							if(($key+1)==2 || ($key+1)==1)
							{
								?>
								<span class="add_doc hide" id="add_doc_3">
									<div style="clear: both;"></div>
									<div class="col-md-4">
										<?php
										echo $this->Form->input('ApplyOnlines.Aplication_doc_title][', array('label' => false,'type'=>'text','class'=>'form-control','id'=>'doc_title_3'));
										echo $this->Form->input('ApplyOnlines.Aplication_doc_id][', array('label' => false,'type'=>'hidden','class'=>'form-control','id'=>'doc_id_3'));
										?>
									</div>
									<div class="col-md-4">
										<?php echo $this->Form->input('ApplyOnlines.Aplication_doc_file][', array('label' => false,'type'=>'file','class'=>'form-control add_document','id'=>'applied_doc_3')); ?>
										<div id="applied_doc_3-file-errors"></div>
									</div>
								</span>
								<?php
							}
						?>
					<?php } else { ?>
						<span class="add_doc">
							<div style="clear: both;"></div>
							<div class="col-md-4">
								<label>Document name</label>
								<?php
								echo $this->Form->input('ApplyOnlines.Aplication_doc_title][', array('label' => false,'type'=>'text','class'=>'form-control'));
								echo $this->Form->input('ApplyOnlines.Aplication_doc_id][', array('label' => false,'type'=>'hidden','class'=>'form-control'));
								?>
							</div>
							<div class="col-md-4">
								<label>Document file</label>
								<?php echo $this->Form->input('ApplyOnlines.Aplication_doc_file][', array('label' => false,'type'=>'file','class'=>'form-control add_document','id'=>'applied_doc_1')); ?>
								<div id="applied_doc_1-file-errors"></div>
							</div>
						</span>
						<span class="add_doc hide" id="add_doc_2">
							<div style="clear: both;"></div>
							<div class="col-md-4">
								<?php
								echo $this->Form->input('ApplyOnlines.Aplication_doc_title][', array('label' => false,'type'=>'text','class'=>'form-control','id'=>'doc_title_2'));
								echo $this->Form->input('ApplyOnlines.Aplication_doc_id][', array('label' => false,'type'=>'hidden','class'=>'form-control','id'=>'doc_id_2'));
								?>
							</div>
							<div class="col-md-4">
								<?php echo $this->Form->input('ApplyOnlines.Aplication_doc_file][', array('label' => false,'type'=>'file','class'=>'form-control add_document','id'=>'applied_doc_2')); ?>
								<div id="applied_doc_2-file-errors"></div>
							</div>
						</span>
						<span class="add_doc hide" id="add_doc_3">
							<div style="clear: both;"></div>
							<div class="col-md-4">
								<?php
								echo $this->Form->input('ApplyOnlines.Aplication_doc_title][', array('label' => false,'type'=>'text','class'=>'form-control','id'=>'doc_title_3'));
								echo $this->Form->input('ApplyOnlines.Aplication_doc_id][', array('label' => false,'type'=>'hidden','class'=>'form-control','id'=>'doc_id_3'));
								?>
							</div>
							<div class="col-md-4">
								<?php echo $this->Form->input('ApplyOnlines.Aplication_doc_file][', array('label' => false,'type'=>'file','class'=>'form-control add_document','id'=>'applied_doc_3')); ?>
								<div id="applied_doc_3-file-errors"></div>
							</div>
						</span>
					<?php } ?>
				</div>
				<div class="form-group">
					<div class="col-md-12 oneline ">
						<label><?php echo $this->Form->input('ApplyOnlines.capexmode', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address','id'=>'capexmode')); ?>
					   The Solar PV system is owned by the Consumer.<span class="mendatory_field">*</span></label>
						<?php
						if(isset($ApplyOnlineErrors['capexmode']) && isset($ApplyOnlineErrors['capexmode']['_empty']) && !empty($ApplyOnlineErrors['capexmode']['_empty'])) {  ?>
							<div class="error-message"><?php echo $ApplyOnlineErrors['capexmode']['_empty']; ?></div>
						<?php } ?>
					</div>
				</div>
				<?php
				$class_hide     = '';
				if($allocatedCategory==3)
				{
					$class_hide = 'hide';
				}
				?>
				<div class="form-group <?php echo $class_hide;?>">
					<div class="col-md-12 oneline ">
						<label><?php
						$flg_disp       = '1';
						if(!empty($ApplyOnlines->id))
						{
							$approval   = $MStatus->Approvalstage($ApplyOnlines->id);
							$disabled   = 'false';
							if((in_array($MStatus->APPLICATION_SUBMITTED,$approval) && $ApplyOnlines->disclaimer_subsidy==1) || $ProjectsDetails->project_disclaimer_subsidy==1)
							{
								$disabled   = 'true';
								echo $this->Form->input('ApplyOnlines.disclaimer_subsidy', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address','id'=>'disclaimer_subsidy',"disabled"=>$disabled));
								$flg_disp   = '0';
							}
						}
						if($flg_disp == '1')
						{
							echo $this->Form->input('ApplyOnlines.disclaimer_subsidy', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address','id'=>'disclaimer_subsidy'));
						}
						 ?>
							<?php echo ($newSchemeApp == 0) ? "I don't want subsidy on the Solar PV System." : "I am applying for Solar PV System under Gujarat Solar Policy 2021."; ?>
						</label>
					</div>
				</div>
				<?php
				if($allocatedCategory==3)
				{
					?>
					<div class="form-group">
						<div class="col-md-12 oneline ">
							<label>
								<i class="fa fa-check"></i> <?php echo ($newSchemeApp == 0) ? "I don't want subsidy on the Solar PV System." : "I am applying for Solar PV System under Gujarat Solar Policy 2021."; ?>
							</label>
						</div>
					</div>
					<?php
				}
				$captiveStatusClass 	= ($newSchemeApp == 1) ? 'hide' : '';
				if(IS_CAPTIVE_OPEN == 0) {
					$captiveStatusClass = 'hide';
				}
				?>
				<div class="form-group row captive_project <?php echo $captiveStatusClass;?>">
					<?php
						$error_class        = '';
						if(isset($ApplyOnlineErrors['hi_renewable_attr']) && isset($ApplyOnlineErrors['hi_renewable_attr']['_empty']) && !empty($ApplyOnlineErrors['hi_renewable_attr']['_empty']))
						{
							$error_class    = 'has-error';
						}
						$error_class2        = '';
						if(isset($ApplyOnlineErrors['hi_renewable_rec']) && isset($ApplyOnlineErrors['hi_renewable_rec']['_empty']) && !empty($ApplyOnlineErrors['hi_renewable_rec']['_empty']))
						{
							$error_class2    = 'has-error';
						}
						$checked = false;
						if((!empty($error_class2) || !empty($error_class)) || $ApplyOnlines->renewable_attr_chk == 1 || $ApplyOnlines->renewable_attr == 1 || $ApplyOnlines->renewable_rec == 1)
						{
							$checked = 'checked';
						}
					?>
					<div class="col-md-12 <?php echo $error_class;?>">
						<label>
						<?php

						echo $this->Form->input('ApplyOnlines.renewable_attr_chk', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address renewable_attr_chk','id'=>'renewable_attr_chk','onclick'=>'javascript:toggel_captive();','checked'=>$checked));

						?>
						The Applicant doesn’t want to keep the Renewable Attributes of this Solar PV system</label>
						<div class="div_renewable_attr hide">
							<?php
							echo $this->Form->input('renewable_attr', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Yes (Type 1)","class"=>"renewable_attr"],
														['value' => 0, 'text' =>"No, the Applicant wants to keep the Renewable Attributes (Type 2A)","class"=>"renewable_attr"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '{{label}}'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);

							echo $this->Form->input('ApplyOnlines.hi_renewable_attr', array('label' => false,'type'=>'hidden','class'=>'form-control','value'=>'','id'=>'hi_renewable_attr'));
							if(!empty($error_class))
							{
								?>
								<div class="help-block"><?php echo $ApplyOnlineErrors['hi_renewable_attr']['_empty']; ?></div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="form-group row hide captive_project_rec">
					<div class="col-md-12 <?php echo $error_class2;?>">
						<label>
							<?php
							echo $this->Form->input('rec_certificate', array('label' => false,'type'=>'checkbox','value'=>'1','class'=>'form-control check-box-address','id'=>'rec_certificate',"disabled"=>true,"checked"=>"checked"));
							?>
							Is this Application done under the Renewable Energy Certificate (REC) Scheme?</label>
						<?php
						//['value' => 1, 'text' =>"Yes","class"=>""],

						echo $this->Form->input('renewable_rec', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 0, 'text' =>"No, the application is to be done to meet the Renewable Purchase Obligation of the Applicant","class"=>"","checked"=>"checked"]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '{{label}}'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						echo $this->Form->input('ApplyOnlines.hi_renewable_rec', array('label' => false,'type'=>'hidden','class'=>'form-control','value'=>'','id'=>'hi_renewable_rec'));
						if(!empty($error_class2))
						{
							?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['hi_renewable_rec']['_empty']; ?></div>
							<?php
						}
						?>
					</div>
				</div>
				<br/>
				<div class="col-md-12 form-group row ">
					<label">
						Is the Applicant a MSME?
					</label>
					<div class="">
						<?php
						echo $this->Form->input('ApplyOnlines.msme', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"msme","onclick"=>"javascript:toggel_msme();"],
													['value' => 0, 'text' =>"No","class"=>"msme","onclick"=>"javascript:toggel_msme();"]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '{{label}}'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
					
				</div>
				<div class="">
					<div class="col-md-7 div_contract_50_load_more hide">
						<label >
						Does the Applicant want to Install Solar PV more than 50% of the Contract Load?<span class="mendatory_field">*</span></label>
					
						<?php
						echo $this->Form->input('ApplyOnlines.contract_load_more', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"contract_load_more"],
													['value' => 0, 'text' =>"No","class"=>"contract_load_more"]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '{{label}}'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);

						if(!empty($error_class))
						{
							?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['hi_renewable_attr']['_empty']; ?></div>
							<?php
						}
						?>
					</div>
					<div class="col-md-5 div_contract_load_more hide">
						<label>Upload Certificate<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.file_upload_certificate', array('label' => false,'type'=>'file','id'=>'file_upload_certificate','class'=>'form-control'));
						?>
						<div id="file_upload_certificate-file-errors"></div>
						<br/>
						<?php if(!empty($ApplyOnlinesOthers->upload_certificate)) : ?>
							<?php if (file_exists($DOCUMENT_PATH.$ApplyOnlinesOthers->upload_certificate)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlinesOthers->upload_certificate,PATHINFO_EXTENSION);

									echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/upload_certificate/'.encode($ApplyOnlines->id)."\">View Upload Certificate</a></strong>";
									$hiddenVal = $ApplyOnlinesOthers->upload_certificate;
								}
								?>
						<?php endif; ?>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div class="div_contract_load_more hide">
					<?php $error_class_category = "";
					if(isset($ApplyOnlineErrors['msme_category']) && isset($ApplyOnlineErrors['msme_category']['_empty']) && !empty($ApplyOnlineErrors['msme_category']['_empty'])) {
						$error_class_category 	= "has-error";
					}
					?>
					<div class="col-md-4 <?php echo $error_class_category; ?>">
						<label>MSME Category<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('ApplyOnlines.msme_category',array("A"=>"A","B"=>"B","C"=>"C"), array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Select Category-','id'=>'msme_category','style'=>"margin-top:5px;"));
						if(!empty($error_class_category)){  ?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['msme_category']['_empty']; ?></div>
						<?php } ?>
					</div>
					<?php $error_class_typeapplicant = "";
					if(isset($ApplyOnlineErrors['type_of_applicant']) && isset($ApplyOnlineErrors['type_of_applicant']['_empty']) && !empty($ApplyOnlineErrors['type_of_applicant']['_empty'])) {
						$error_class_typeapplicant 	= "has-error";
					}
					?>
					<div class="col-md-4 <?php echo $error_class_typeapplicant; ?>"><label>Type of Applicant<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('ApplyOnlines.type_of_applicant',array("Individual"=>"Individual","Proprietary Firm"=>"Proprietary Firm","Partnership"=>"Partnership","Public Ltd."=>"Public Ltd.","Private Limited"=>"Private Limited","Other"=>"Other"), array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Select Type of Applicant-','id'=>'type_of_applicant','style'=>"margin-top:5px;",'onChange'=>'javascript:ShowHideOthers();'));
						if(!empty($error_class_typeapplicant)){  ?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['type_of_applicant']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4 applicant_others"><label>Applicant Type Other<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.applicant_others',array('label' => false,'class'=>'','id'=>'applicant_others'));
						?>
					</div>
					
				</div>
				<div style="clear: both;"></div>
				<div class="div_contract_load_more hide">
					
					<div class="col-md-4 ">
						<label>MSME Udhyog Aadhaar No. <span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.msme_aadhaar_no', array('label' => false,'class'=>'form-control','placeholder'=>'MSME Udhyog Aadhaar No.','type'=>'text')); ?>
					</div>
					<?php $error_class_typeauthority = "";
					if(isset($ApplyOnlineErrors['type_authority']) && isset($ApplyOnlineErrors['type_authority']['_empty']) && !empty($ApplyOnlineErrors['type_authority']['_empty'])) {
						$error_class_typeauthority 	= "has-error";
					}
					?>
					<div class="col-md-4 <?php echo $error_class_typeauthority;?>"><label>Signing Authority Type<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->select('ApplyOnlines.type_authority',array("Chairman"=>"Chairman","Managing Director"=>"Managing Director","CEO"=>"CEO","Partner"=>"Partner"), array('label' => false,'class'=>'change_customer_type form-control','empty'=>'-Select Signing Authority Type-','id'=>'type_authority','style'=>"margin-top:5px;"));
						if(!empty($error_class_typeauthority)){  ?>
							<div class="help-block"><?php echo $ApplyOnlineErrors['type_authority']['_empty']; ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4"><label>Name of Signing Authority<span class="mendatory_field">*</span></label>
						<?php echo $this->Form->input('ApplyOnlines.name_authority',array('label' => false,'class'=>'','id'=>'name_authority','placeholder'=>'Name of Signing Authority'));
						?>
					</div>	
				</div>
				<div style="clear: both;"></div>
				<div class="col-md-12 form-group row ">
					<label">
						Application is to be registered under
					</label>
					<div class="">
						<?php
						echo $this->Form->input('ApplyOnlines.rpo_rec', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"RPO Compliance","class"=>"rpo_rec","onclick"=>"javascript:toggle_rporec();"],
													['value' => 2, 'text' =>"REC Mechanism","class"=>"rpo_rec","onclick"=>"javascript:toggle_rporec();"],
													['value' => 0, 'text' =>"None","class"=>"rpo_rec","onclick"=>"javascript:toggle_rporec();"]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '{{label}}'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div class="div_rpo form-group row hide">
					<span class="col-md-6">
						Captive: 
					</span>
					<div class="col-md-6">
						<?php
						echo $this->Form->input('ApplyOnlines.rpo_is_captive', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"",'checked'=>'checked']
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
				</div>
				<div class="div_rpo form-group row hide">
					<span class="col-md-6">
						Whether beneficiary is an Obligated Entity covered under RPO obligation:<span class="mendatory_field">*</span> 
					</span>
					<div class="col-md-2">
						<?php
						echo $this->Form->input('ApplyOnlines.rpo_is_obligation', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>""],
													['value' => 0, 'text' =>"No","class"=>""]
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
				</div>
				<div class="div_rpo col-md-12 form-group row hide">
					<label >
						Documents of beneficiary in support of applicant being obligated entity for RPO compliance:
					</label>
					<div class="col-md-12">
						<label class="col-md-6">Copy of GERC Distribution Licensee Certificate:<span class="mendatory_field">*</span></label>
						<div class="col-md-2">
							<?php
							echo $this->Form->input('ApplyOnlines.gerc_is_distribution', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Yes","class"=>"gerc_dis","onclick"=>"javascript:toggle_gerc();"],
														['value' => 0, 'text' =>"No","class"=>"gerc_dis","onclick"=>"javascript:toggle_gerc();"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
							?>
						</div>
						<div class="col-md-4 gerc_certificate hide">
							<?php echo $this->Form->input('ApplyOnlines.file_gerc_certificate', array('label' => false,'div' => false,'type'=>'file','id'=>'file_gerc_certificate','class'=>'input-inline')); ?>
							<div id="gerc_certificate-file-errors"></div>
							<?php if(!empty($ApplyOnlines->gerc_certificate)) { ?>
								<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->gerc_certificate)) { ?>
									<?php
										$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->gerc_certificate,PATHINFO_EXTENSION);
										 ?>
										<?php
											echo "<strong><a target=\"_GERC\" href=\"".URL_HTTP.'app-docs/gerc_certificate/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
										?>
								<?php } ?>
							<?php } ?>
						</div>
						
					</div>
				</div>
				<div class="div_rpo col-md-12 form-group row hide">
					<div class="col-md-12">
						<label class="col-md-6">Whether applicant has Captive Conventional Power Plant (CPP):<span class="mendatory_field">*</span></label>
						<div class="col-md-2">
							<?php
							echo $this->Form->input('ApplyOnlines.rpo_is_cpp', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Yes","class"=>"rpo_cpp","onclick"=>"javascript:toggle_ccp();"],
														['value' => 0, 'text' =>"No","class"=>"rpo_cpp","onclick"=>"javascript:toggle_ccp();"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
							?>
						</div>
						<div class="col-md-4 capacity_cpp hide">
							<?php echo $this->Form->input('ApplyOnlines.capacity_cpp', array('label' => false,'div' => false,'type'=>'text','id'=>'capacity_cpp','class'=>'input-inline')); ?> (in kW)
						</div>
					</div>
				</div>
				<div class="div_rpo col-md-12 form-group row hide">
					<div class="col-md-12">
						<label class="col-md-6">Any previous Solar Project put up for captive RPO :<span class="mendatory_field">*</span> </label>
						<div class="col-md-6">
							<?php
							echo $this->Form->input('ApplyOnlines.rpo_is_captive_rpo', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Yes","class"=>""],
														['value' => 0, 'text' =>"No","class"=>""]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
							?>
						</div>
					</div>
				</div>
				<div class="div_rpo form-group row hide">
					<label class="col-md-6">
						Certificate of STOA/ MTOA/ LTOA by SLDC/GETCO:<span class="mendatory_field">*</span>
					</label>
					<div class="col-md-6">
						<?php
						echo $this->Form->input('ApplyOnlines.rpo_is_cert_getco', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"cert_getco",'onclick'=>'javascript:toggle_rpo_getco();'],
													['value' => 0, 'text' =>"No","class"=>"cert_getco",'onclick'=>'javascript:toggle_rpo_getco();']
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
					<div class="row col-md-12 form-group capacity_rpo_cert hide">
						<div class="col-md-12">
							<label class="col-md-6">Capacity for which Certificate of STOA/ MTOA/ LTOA issued by SLDC/GETCO:<span class="mendatory_field">*</span></label>
							<div class="col-md-2">
								<?php echo $this->Form->input('ApplyOnlines.capacity_rpo_cert',array('label' => false,'class'=>'','id'=>'capacity_rpo_cert'));
								?> (in kW)
							</div>
						</div>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div class="div_rec form-group row hide">
					<span class="col-md-6">
						Physical copy of application done on online REC registration website:<span class="mendatory_field">*</span>
					</span>
					<div class="col-md-2">
						<?php
						echo $this->Form->input('ApplyOnlines.rec_is_registration', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"rec_registration",'onclick'=>'javascript:toggle_rec_reg();'],
													['value' => 0, 'text' =>"No","class"=>"rec_registration",'onclick'=>'javascript:toggle_rec_reg();']
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
					<div class="col-md-4 rec_registration_copy hide">
						<?php echo $this->Form->input('ApplyOnlines.file_rec_registration_copy', array('label' => false,'div' => false,'type'=>'file','id'=>'file_rec_registration_copy','class'=>'input-inline')); ?>
						<div id="rec_registration_copy-file-errors"></div>
						<?php if(!empty($ApplyOnlines->rec_registration_copy)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->rec_registration_copy)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->rec_registration_copy,PATHINFO_EXTENSION);
									 ?>
									<?php
										echo "<strong><a target=\"_RECCOPY\" href=\"".URL_HTTP.'app-docs/rec_registration_copy/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
									?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<div class="div_rec form-group row hide">
					<span class="col-md-6">
						Copy of receipt for application done on online REC registration website:<span class="mendatory_field">*</span>
					</span>
					<div class="col-md-2">
					<?php
						echo $this->Form->input('ApplyOnlines.rec_is_receipt', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"rec_receipt",'onclick'=>'javascript:toggle_rec_receipt();'],
													['value' => 0, 'text' =>"No","class"=>"rec_receipt",'onclick'=>'javascript:toggle_rec_receipt();']
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
					<div class="col-md-4 rec_receipt_copy hide">
						<?php echo $this->Form->input('ApplyOnlines.file_rec_receipt_copy', array('label' => false,'div' => false,'type'=>'file','id'=>'file_rec_receipt_copy','class'=>'input-inline')); ?>
						<div id="rec_receipt_copy-file-errors"></div>
						<?php if(!empty($ApplyOnlines->rec_receipt_copy)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->rec_receipt_copy)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->rec_receipt_copy,PATHINFO_EXTENSION);
									 ?>
									<?php
										echo "<strong><a target=\"_RECRECEIPT\" href=\"".URL_HTTP.'app-docs/rec_receipt_copy/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
									?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<div class="div_rec form-group row hide">
					<label class="col-md-6">
						Power Evacuation Arrangement permission letter from the host State Transmission Utility or the concerned Distribution Licensee, as the case may be:<span class="mendatory_field">*</span>
					</label>
					<div class="col-md-2">
					<?php
						echo $this->Form->input('ApplyOnlines.rec_is_power_evaluation', [
												'type' => 'radio',
												'label' => false,
												'div' => false,
												'options' => [
													['value' => 1, 'text' =>"Yes","class"=>"rec_power_eval",'onclick'=>'javascript:toggle_power_evaluation();'],
													['value' => 0, 'text' =>"N/A","class"=>"rec_power_eval",'onclick'=>'javascript:toggle_power_evaluation();']
												],
												'templates' => [
													'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
													'radioWrapper' => '<div class="radio-inline screen-center screen-radio margin_radio_oneline" style="padding-left:0px;margin-top:-10px;">{{label}}</div>'
												],
												'before' => '',
												'separator' => '',
												'after' => '',
											]);
						?>
					</div>
					<div class="col-md-4 rec_power_evaluation hide">
						<?php echo $this->Form->input('ApplyOnlines.file_rec_power_evaluation', array('label' => false,'div' => false,'type'=>'file','id'=>'file_rec_power_evaluation','class'=>'input-inline')); ?>
						<div id="rec_power_evaluation-file-errors"></div>
						<?php if(!empty($ApplyOnlines->rec_power_evaluation)) { ?>
							<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->rec_power_evaluation)) { ?>
								<?php
									$file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->rec_power_evaluation,PATHINFO_EXTENSION);
									 ?>
									<?php
										echo "<strong><a target=\"_RECPOWER\" href=\"".URL_HTTP.'app-docs/rec_power_evaluation/'.encode($ApplyOnlines->id)."\">View Document</a></strong>";
									?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<?php  
					$error_class 	= '';
					$error_text 	= '';
					if(isset($ApplyOnlineErrors['rec_is_allowed_sancation']) && isset($ApplyOnlineErrors['rec_is_allowed_sancation']['_empty']) && !empty($ApplyOnlineErrors['rec_is_allowed_sancation']['_empty']))
					{  
						$error_class= 'has-error'; 
						$error_text = $ApplyOnlineErrors['rec_is_allowed_sancation']['_empty'];
					}
				?>
				<div class="div_rec col-md-12 form-group row hide <?php echo $error_class;?>">
					<label >
						Installation of Solar Project shall be allowed up to Sanctioned load/ Contract demand :<span class="mendatory_field">*</span> <span id="rec_is_allowed_sancation_text"></span><?php echo $this->Form->input('ApplyOnlines.rec_is_allowed_sancation', array('label' => false,'div' => false,'type'=>'hidden','id'=>'rec_is_allowed_sancation','class'=>'input-inline','onChange'=>'javascript:check_pv_cap();')); 
						if(!empty($error_text))
						{
							echo '<div class="help-block">'.$error_text.'</div>'; 
						} ?>
					</label>
					<div class="input-inline ">
					
					</div>
				</div>
				<?php  
					$error_class 	= '';
					$error_text 	= '';
					if(isset($ApplyOnlineErrors['rec_is_valid_min_cap']) && isset($ApplyOnlineErrors['rec_is_valid_min_cap']['_empty']) && !empty($ApplyOnlineErrors['rec_is_valid_min_cap']['_empty']))
					{  
						$error_class= 'has-error'; 
						$error_text = $ApplyOnlineErrors['rec_is_valid_min_cap']['_empty'];
					}
				?>
				<div class="div_rec col-md-12 form-group row hide <?php echo $error_class;?>">
					<label>
						Minimum Capacity of Solar Project shall be 250 kW :<span class="mendatory_field">*</span> <span id="rec_is_valid_min_cap_text"></span><?php echo $this->Form->input('ApplyOnlines.rec_is_valid_min_cap', array('label' => false,'div' => false,'type'=>'hidden','id'=>'rec_is_valid_min_cap','class'=>'input-inline'));
						if(!empty($error_text))
						{
							echo '<div class="help-block">'.$error_text.'</div>'; 
						}
						?>
					</label>
				</div>
				<div style="clear: both;"></div>
				<div class="form-group">
					<div class="col-md-2" style="width: 120px;">
						<?php echo $this->Form->input('Previous', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','value'=>'previous_2','name'=>'previous_2','type'=>'submit','placeholder'=>'Disclaimer')); ?>
					</div>
					<div class="col-md-1">
						<?php echo $this->Form->input('Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'tab_2','type'=>'submit','placeholder'=>'Disclaimer')); ?>
					</div>
					<div class="col-md-2">
						<?php echo $this->Form->input('Back to My Application', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'cancel','type'=>'button','placeholder'=>'Disclaimer','onclick'=>'cancel_application()','style'=>'margin-top: 6px;')); ?>
					</div>
				</div>
			</div>
			<div class="tab-pane <?php if($tab == 'tab_2'){ echo 'active'; } ?>" id="tabsNavigationSimple3">
				<h4><u>Details of GEDA Empanelled Agency</u></h4>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-12">Installer Name</label>
						<?php if(isset($ApplyOnlines->installer_id) && !empty($ApplyOnlines->installer_id)) { ?>
						<h6 class="installer_name pull-left">
							<?php echo (isset($ApplyOnlines->installer_id) && !empty($ApplyOnlines->installer_id) && isset($installer_list[$ApplyOnlines->installer_id])) ? $installer_list[$ApplyOnlines->installer_id] : '-'; ?>
						</h6>
						<div class="col-md-4">
						<?php echo $this->Form->select('ApplyOnlines.installer_id',$installer_list, array('label' => false,'class'=>'drp_installer_name hide form-control','placeholder'=>'Discom Name')); ?>
						</div>
						<i class="<?php echo $DisplayInstallerTab;?> fa installer_name fa-pencil-square-o edit_installer col-md-6"></i>
						<?php } ?>
					</div>
				</div>
				<h4><u>Information for Subsidy Disbursement (GEDA Empanelled Agency)</u></h4>
				<div class="form-group hide">
					<div class="col-md-6">
						<label>Bank Name</label>
						<?php echo $this->Form->input('ApplyOnlines.bank_name', array('label' => false,'class'=>'form-control','placeholder'=>'Bank Name.')); ?>
					</div>
				</div>
				<div class="form-group hide">
					<div class="col-md-6">
						<label>Bank Account No.</label>
						<?php echo $this->Form->input('ApplyOnlines.bank_ac_no', array('label' => false,'class'=>'form-control','placeholder'=>'Bank AC no.')); ?>
					</div>
				</div>
				<div class="form-group hide">
					<div class="col-md-6">
						<label>IFSC Code</label>
						<?php echo $this->Form->input('ApplyOnlines.ifsc_code', array('label' => false,'class'=>'form-control','placeholder'=>'IFSC Code.')); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label>GST No. of Consumer</label>
						<?php echo $this->Form->input('ApplyOnlines.gstno', array('type' => 'text','label' => false,'class'=>'form-control','placeholder'=>'GST Number.')); ?>
					</div>
				</div>
				<?php $Displaypaymentmode_hide = "hide "; //
				$Displaypaymentmode = ($ApplyOnlines->category==$ApplyOnlineObj->category_residental && ($ApplyOnlines->social_consumer==0 || SOCIAL_SECTOR_PAYMENT==0))?"hide ":""; ?>
				<div class="<?php echo $Displaypaymentmode_hide;?>form-group">
					<div class="col-md-6">
						<label>Payment Mode</label>
						<?php
							echo $this->Form->input('ApplyOnlines.payment_mode', [
													'type' => 'radio',
													'label' => false,
													'div' => false,
													'options' => [
														['value' => 1, 'text' =>"Online","class"=>"payment_mode_choice"],
														['value' => 0, 'text' =>"Offline","class"=>"payment_mode_choice"]
													],
													'templates' => [
														'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
														'radioWrapper' => '{{label}}'
													],
													'before' => '',
													'separator' => '',
													'after' => '',
												]);
						?>
					</div>
				</div>
				<div class="<?php echo $Displaypaymentmode_hide;?>form-group">
					<div class="col-md-6">
						<label>Cheque no./DD no.<span class="mendatory_field">*</label>
						 <?php echo $this->Form->input('ApplyOnlines.payment_gateway', array('label' => false,'class'=>'form-control','placeholder'=>'Cheque no./DD no.')); ?>
					</div>
				</div>
				<div class="<?php echo $Displaypaymentmode;?>form-group">
					<div class="col-md-6">
						<label>GEDA Processing Fee</label>
						<?php
							if($ApplyOnlines->govt_agency == 1 && GOVERMENT_AGENCY==1)
							{
								$applicable_amt = $amt_government;
								$tax_applicable = $amt_gov_tax;
								echo $this->Form->input('ApplyOnlines.disCom_application_fee', array('label' => false,'class'=>'form-control','readonly'=>'true','value'=>$amt_government,'placeholder'=>'DisCom Application Fee for Net Metering'));
							}
							elseif($ApplyOnlines->category == $ApplyOnlineObj->category_residental && ($ApplyOnlines->social_consumer==0 || SOCIAL_SECTOR_PAYMENT==0))
							{
								$applicable_amt = $amt_residental;
								$tax_applicable = 0;
								echo $this->Form->input('ApplyOnlines.disCom_application_fee', array('label' => false,'class'=>'form-control','readonly'=>'true','value'=>$amt_residental,'placeholder'=>'DisCom Application Fee for Net Metering'));
							}
							else
							{
								if(isset($ApplyOnlines->pv_capacity) && $ApplyOnlines->pv_capacity > 1000) {
									$amt_non_government = floatval(PRICE_PER_KW_GT1MW) * floatval($ApplyOnlines->pv_capacity);
								}
								$applicable_amt = $amt_non_government;
								$tax_applicable = $amt_non_gov_tax;
								echo $this->Form->input('ApplyOnlines.disCom_application_fee', array('label' => false,'class'=>'form-control','readonly'=>'true','value'=>$amt_non_government,'placeholder'=>'DisCom Application Fee for Net Metering'));
							}
						?>
					</div>
					<?php
					$tax_amount = $amt_tax_percent;
					if($amt_tax_percent=='%')
					{
						$tax_amount = ($applicable_amt*$tax_applicable)/100;
					}
					?>
					<div class="col-md-6">
						<label>GST at 18%</label>
						<?php echo $this->Form->input('ApplyOnlines.jreda_processing_fee', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>$tax_amount,'placeholder'=>'GEDA Processing Fee')); ?>
					</div>
				</div>
				<div class="<?php echo $Displaypaymentmode;?>form-group">
					<div class="col-md-6">
						<label>Total Fee</label>
						<?php
							echo $this->Form->input('ApplyOnlines.total_fee', array('label' => false,'class'=>'form-control','value'=>$applicable_amt+$tax_amount,'readonly'=>'true','placeholder'=>'Total Fee'));
						?>
					</div>
				</div>
				<div class="form-group disclimer">
					<div class="col-md-12">
						<label><?php echo $this->Form->input('ApplyOnlines.disclaimer3', array('label' => false,'class'=>'form-control','type'=>'checkbox','placeholder'=>'Disclaimer','value'=>'1','id'=>'disclaimer3')); ?>I hereby confirm to all the <a href="javascript:;">Terms and Conditions</a> of GEDA Discom and of the scheme of GEDA. I also ensure that all the information in the Application Form is true and correct to the best of my knowledge.</label>
					</div>
					<br/>
				</div>
				<?php
				$captcha = '';
				if(CAPTCHA_DISPLAY == 1) {
					if(isset($ApplyOnlineErrors['g-recaptcha-response']) && isset($ApplyOnlineErrors['g-recaptcha-response']['_empty']) && !empty($ApplyOnlineErrors['g-recaptcha-response']['_empty'])){ $captcha = 'has-error'; }?>
					<div class="col-md-12 <?php echo $captcha; ?>" style="margin-top: 25px;" >
						<div class="recaptcha" data-sitekey="<?php echo $SITE_KEY ;?>"></div>
					</div>
					<?php if(isset($ApplyOnlineErrors['g-recaptcha-response']) && isset($ApplyOnlineErrors['g-recaptcha-response']['_empty']) && !empty($ApplyOnlineErrors['g-recaptcha-response']['_empty'])){  ?>
						<div class="help-block" style="color:#a94442;"><?php echo $ApplyOnlineErrors['g-recaptcha-response']['_empty']; ?></div>
					<?php } ?>
				<?php } ?>
				<div class="row">
					<div class="col-md-2" style="width: 120px;">
					<?php echo $this->Form->submit('Previous',array('label' => false,'class'=>'btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'previous_3','value'=>'previous_3','type'=>'submit')); ?>
					</div>
					<div class="col-md-1">
					<?php echo $this->Form->submit('Save',array('label' => false,'class'=>'btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'tab_3' ,'value'=>'Send')); ?>
					</div>
					<div class="col-md-2">
					<?php echo $this->Form->submit('Save And Submit',array('label' => false,'class'=>'btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_submit' ,'value'=>'save_submit','id'=>'save_submit')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } else { ?>
		<div class="row"><h4 class="text-danger"><?php echo $ALERT_MESSAGE;?></h4></div>
	<?php } ?>
</div>
<?php echo $this->Form->end(); ?>
<!-- Modal -->
<div id="NoticeModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Update on Jharkhand Rooftop Solar subsidy Scheme</h4>
			</div>
			<div class="modal-body">
				<p>30% subsidy scheme for Residential Customer is valid only for the installations done by 2 May 2018 and from now onwards 30% subsidy on Residential Customer shall not be applicable till further notifications from JREDA. However, all other subsidies provided by the state government is still active and it is advised to the User to kindly consult with the officials of JREDA before applying for the Rooftop Solar PV system.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="NoticeModal2" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Alert</h4>
			</div>
			<div class="modal-body">
				<p>To proceed further kindly click.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="NoticeModal3" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Alert</h4>
			</div>
			<div class="modal-body">
				<p>To Apply for Industrial/Commerical Category, please tick "The Applicant doesn’t want to keep the Renewable Attributes of this Solar PV system" to proceed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo GOOGLE_MAP_API_KEY;?>&libraries=places"></script>
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDH36YQFTkd-moztzXAticZNbq9bmF0u54&libraries=places"></script> -->
<?php /*<script type="text/javascript" src="<?php echo URL_HTTP;?>js/googleMap.js"></script> */?>

<script type="text/javascript">
/*var CaptchaCallback = function(){
	$('.recaptcha').each(function(){
		grecaptcha.render(this,{'sitekey' : '6Le_81cUAAAAAPV0ndpsBU1DoBUVeltmqR0tMeML'});
	})
};*/
$(function () {
  $('[data-toggle="popover"]').popover();
  $('[project_tips="popover"]').popover({placement:'top'});
})
function chk_customer_type()
{
	<?php
	if($adhar_disp=='')
	{
		?>
		if($(".change_customer_type").val()==<?php echo $ApplyOnlineObj->category_residental;?> || $(".change_customer_type").val()=='<?php echo $ApplyOnlineObj->category_residental;?>')
		{
		   $("#show_ad_label").removeClass("hide");
		   $("#show_ad_text").removeClass("hide");
		   $("#show_pan_label").addClass("hide");
		   $("#show_pan_text").addClass("hide");
		}
		else
		{
			$("#show_ad_label").addClass("hide");
			$("#show_ad_text").addClass("hide");
			$("#show_pan_label").removeClass("hide");
			$("#show_pan_text").removeClass("hide");
		}
		<?php
	}
	?>
}
function CheckPaymentMode() {
	$(".payment_mode_choice").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1) {
			$(".payment_gateway").addClass("hide");
		}
	});
}
function CheckCaptiveAttr() {
	$(".renewable_attr").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 0) {
			$(".captive_project_rec").removeClass("hide");
		}
	});
}
CheckCaptiveAttr();
$(document).ready(function() {
	$(".edit_installer").click(function(){
		$(".drp_installer_name").removeClass("hide");
		$(".installer_name").addClass("hide");
	});
	$(".chk_comunication_address").change(function(){
		if($(this).is(":checked")){
			$(".comunication-address").addClass("hide");
		} else {
			$(".comunication-address").removeClass("hide");
		}
	});
	if($(".chk_comunication_address").is(":checked")){
		$(".comunication-address").addClass("hide");
	} else {
		$(".comunication-address").removeClass("hide");
	}
	$(".change_customer_type").change(function(){
		chk_customer_type();
	});
	chk_customer_type();
	CheckPaymentMode();
	$("#profile_image").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["jpg", "jpeg"],
		elErrorContainer: '#profile_image-file-errors',
		maxFileSize: '200',
	});
	$("#electricity_bill").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#ele_bill-file-errors',
		maxFileSize: '1024',
	});
	$("#aadhar_card").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#aadhar_card-file-errors',
		maxFileSize: '200',
	});
	$("#attached_pan").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#attached_pan-file-errors',
		maxFileSize: '200',
	});
	$("#file_attached_receipt").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#file_receipt-file-errors',
		maxFileSize: '1024',
	});
	$("#applied_doc_1").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#applied_doc_1-file-errors',
		maxFileSize: '1024',
	});
	$("#applied_doc_2").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#applied_doc_2-file-errors',
		maxFileSize: '1024',
	});
	$("#applied_doc_3").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#applied_doc_3-file-errors',
		maxFileSize: '1024',
	});
	$("#file_company_incorporation").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#cominc-file-errors',
		maxFileSize: '1024',
	});
	$("#file_board").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#board-file-errors',
		maxFileSize: '1024',
	});
	$("#file_upload_certificate").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#file_upload_certificate-file-errors',
		maxFileSize: '1024',
	});
	$("#file_gerc_certificate").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#gerc_certificate-file-errors',
		maxFileSize: '1024',
	});
	$("#file_rec_registration_copy").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#rec_registration_copy-file-errors',
		maxFileSize: '1024',
	});
	$("#file_rec_receipt_copy").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#rec_receipt_copy-file-errors',
		maxFileSize: '1024',
	});
	$("#file_rec_power_evaluation").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#rec_power_evaluation-file-errors',
		maxFileSize: '1024',
	});
	/*$(".add_document").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '.add_document-file-errors',
		maxFileSize: '1024',
	});*/
	<?php if(isset($enabled_fields) && !empty($enabled_fields)) :?>
			$('.applay-online-from input,.applay-online-from select').attr('disabled','disabled');
			<?php foreach ($enabled_fields as $key => $value) :?>
				$('.applay-online-from #<?php echo $value ?>').removeAttr('disabled');
			<?php endforeach;
	endif; ?>
});
function click_search()
{

	if(($("#consumer_no").val()).length>=3 && $("#discom").val()>0)
	{
		if(($("#discom").val()=='<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_surat;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_dahej;?>') && $("#t_no").val()=='')
		{
			alert("Please enter T-NO.");
		}
		else
		{
			$("#spinner").show();
			$("#name_of_consumer_applicant").val('');
			$("#middle_name").val('');
			$("#third_name").val('');
			$("#sanction_load").val('');
			$("#installed_capacity_text").html('');
			$("#existingCapacity").val('');
			$.ajax({
				type: "POST",
				url: "/ApplyOnlines/getSubDivisionConsumerCapacity",
				data: {"consumer_no":$("#consumer_no").val(),"discom":$("#discom").val(),"tno":$("#t_no").val(),"id":$("#application_id").val(),'project_id':$("#project_pass_id").val(),'division_id':$("#division").val(),'category':$("#category").val()},
				success: function(response) {
					$("#spinner").hide();
					var result = $.parseJSON(response);
					$("#consumer_no").parent().find('.help-block').remove();
					$("#consumer_no").parent().removeClass('has-error');
					if(result.data.success==1 || result.data.success==-1)
					{
						commonDatasetup(result,'');
					}
					else
					{
						$("#consumer_no").parent().addClass('has-error');
						$("#consumer_no").parent().append('<div class="help-block">'+result.data.response_msg+'</div>');
					}
					$('#skip').hide();
					check_pv_cap();
				}
			});
		}
	}
	else
	{
		alert("Please select discom and enter valid consumer number.");
	}
}
function reload_search()
{
	if(($("#application_id").val()).length>=1)
	{
		$.ajax({
			type: "POST",
			url: "/ApplyOnlines/getSearchedData",
			data: {"id":$("#application_id").val(),"discom":$('#discom').val()},
			success: function(response) {
				var result = $.parseJSON(response);
				if(result.data.success==1 || result.data.success==-1)
				{
					commonDatasetup(result,'1');
				}
				else
				{
				}
				detailsFromDiscom();
			}
		});
	}
}
reload_search();
var arrCategory = [];
<?php
foreach($customer_type_list as $key=>$cat)
{
	?>
	arrCategory['<?php echo $key;?>'] = '<?php echo $cat;?>';
	<?php
}
?>
function changeCategory(actionPassed)
{
	if(actionPassed == 'applyonline')
	{
		var Categorytype    = $("#category").val();
		$('.hideFilesData').removeClass('hide');

		if(Categorytype == '<?php echo $ApplyOnlineObj->category_residental;?>')
		{
			$('.hideFilesData').addClass('hide');
		}
	}
	<?php
	/*
	$(".renewable_attr_chk").attr("checked","true");
	toggel_captive();
	if(IS_CAPTIVE_OPEN == 1)
	{
		?>
		if(actionPassed == "project")
		{
			var project_type    = $("#project_type").val();
		}
		else
		{
			var project_type    = $("#category").val();
		}
		if(project_type == '<?php echo $ApplyOnlineObj->category_industrial;?>' || project_type == '<?php echo $ApplyOnlineObj->category_ht_indus;?>' || project_type == '<?php echo $ApplyOnlineObj->category_commercial;?>')
		{

		   // $(".captive_project").removeClass("hide");
		   // CheckCaptiveAttr();
		}
		else
		{
		   // $(".captive_project").addClass("hide");
		   // $(".captive_project_rec").addClass("hide");
		}
		<?php
	}*/
	?>

}
<?php
if($create_project=='1')
{
	?>
	//changeCategory("project");
	<?php
}
else
{
	?>
	changeCategory("applyonline");
	<?php
}
?>
//changeCategory("applyonline");
function commonDatasetup(result,reload)
{
	//var check_discom = result.data.seldiscom;
	var check_discom = $("#discom").val();
	if(check_discom!='<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' && check_discom!='<?php echo $ApplyOnlineObj->torent_surat;?>' && check_discom!='<?php echo $ApplyOnlineObj->torent_dahej;?>')
	{
		$("#name_of_consumer_applicant").val(result.data.first_name).attr('readonly','readonly');
		$("#middle_name").val(result.data.middle_name).attr('readonly','readonly');
		$("#third_name").val(result.data.last_name).attr('readonly','readonly');
		$("#sanction_load").val(result.data.sanction_load).attr('readonly','readonly');
	}
	else
	{
		$("#name_of_consumer_applicant").val(result.data.first_name).attr('readonly','readonly');
		$("#middle_name").val(result.data.middle_name).attr('readonly','readonly');
		$("#third_name").val(result.data.last_name).attr('readonly','readonly');
		$("#sanction_load").val(result.data.sanction_load).attr('readonly','readonly');
	}
	if(reload=='' || $("#consumer_no").val()=='')
	{

		$("#add1").val(result.data.address1);
		$("#add2").val(result.data.taluka);
		$("#sanction_load").val(result.data.sanction_load).attr('readonly','readonly');
		$("#installed_capacity_text").html(' Existing Capacity - '+result.data.installed_capacity+' kW');
		$("#existingCapacity").val(result.data.installed_capacity);
		if(result.data.category==3001)
		{
			$("#category").html("");
			$("#category").append($("<option />").val('').text('-Select Category-'));
			$("#category").append($("<option />").val('3001').text('Residential'));
			$("#category").val(result.data.category);
		}
		else
		{
			//$("#category").html("");
		   // $("#category").append($("<option />").val('').text('-Select Category-'));
			//$("#category").val(result.data.category);
			//var cat_text = $("#category option:selected").text();
			var cat_text = arrCategory[result.data.category];
			$("#category").html("");
			$("#category").append($("<option />").val('').text('-Select Category-'));
			$("#category").append($("<option />").val(result.data.category).text(cat_text));
			$("#category").val(result.data.category);
		}
		$("#city").val(result.data.city);
		$("#district").val(result.data.district);
		//$("#pincode").val(result.data.pincode);
		if(result.data.transmission_line==1)
		{
			//$('#applyonlines-transmission-line-3').prop("checked", "false");
			//$('#applyonlines-transmission-line-1').prop("checked", "true");
		}
		else if(result.data.transmission_line==3)
		{
			//$('#applyonlines-transmission-line-1').prop("checked", "false");
			//$('#applyonlines-transmission-line-3').prop("checked", "true");
		}
		$("#subdivision").html('');
		var key_data = Object.keys(result.data.subdivision)[0];

		$("#subdivision").html('Sub-division: '+result.data.subdivision[key_data]);
		if (result.data.subdivision != undefined) {
			$("#division").html("");
			$("#division").append($("<option />").val('').text('-Select Division-'));
			var key_data = Object.keys(result.data.subdivision)[0];

			$("#subdivision").html('Sub-division: '+result.data.subdivision[key_data]);
			if (result.data.division != undefined) {
			$.each(result.data.division, function(index, title) {
				$("#division").append($("<option />").val(index).text(title));
			});
			}
			$("#division").val(result.data.seldivision);
			$("#discom").val(result.data.seldiscom);
			$('select[id*="discom"] option').each(function(index,value) {
				if($(this).val()!=result.data.seldiscom)
				{
					//$('#discom option[value="'+$(this).val()+'"]').attr("disabled", "true");
				}
			});
			$('select[id*="division"] option').each(function(index,value) {
				if($(this).val()!=result.data.seldivision)
				{
					$('#division option[value="'+$(this).val()+'"]').attr("disabled", "true");
				}
			});
			var key_datadiv = (result.data.division.length);
			if(key_datadiv==0)
			{
				$("#division").html("");
				$("#division").append($("<option />").val('').text('-Select Division-'));
			}
		}
	}
	if($("#consumer_no").val()=='')
	{
		$("#consumer_no").val(result.data.api_consumer_no);
	}
	if(result.data.category!='')
	{
		//$("#category").val(result.data.category);
	   // var cat_text = $("#category option:selected").text();
		var cat_text = arrCategory[result.data.category];
		$("#category").html("");
		$("#category").append($("<option />").val('').text('-Select Category-'));
		$("#category").append($("<option />").val(result.data.category).text(cat_text));
		$("#category").val(result.data.category);
	}
	/*if($("#category").val()=='' && result.data.category!=3001)
	{
		$("#category").html("");
		$("#category").append($("<option />").val('').text('-Select Category-'));
	}*/
	if (result.data.tariff != undefined) {
		$("#tariff").val(result.data.tariff);
	}
	changeCategory("applyonline");
}
<?php if(isset($enabled_fields) && !empty($enabled_fields)) :?>
$('.form_submit').submit(function(){
	$('.applay-online-from input ,.applay-online-from select').removeAttr('disabled');
	$('#capexmode').attr('checked',true);
	$('#disclaimer3').attr('checked',true);
});
<?php endif; ?>
$('.form_submit').submit(function(){
	$('.applay-online-from select').removeAttr('disabled');
	$('#disclaimer_subsidy').removeAttr('disabled');
	$('#social_consumer_guj').removeAttr('disabled');
});
$("select[name='ApplyOnlines[installer_id]']").change(function(){
	var select_box_value = $(this).val();
	$("select[name='ApplyOnlines[installer_id]']").val(select_box_value);
});
$(".payment_mode_choice").click(function(){
	var select_box_value = $(this).val();
	if (select_box_value == 1) {
		$(".payment_gateway").addClass("hide");
	} else {
		$(".payment_gateway").removeClass("hide");
	}
});
$("#discom").change(function(){
	$("#division").html("");
	$('#subdivision').html("");
	$("#division").append($("<option />").val('').text('-Select Division-'));
	detailsFromDiscom();
});
$(".renewable_attr").click(function(){
	var selected_option = $(this).val();
	if(selected_option == 0)
	{
		$(".captive_project_rec").removeClass("hide");
		$("#NoticeModal2").modal("show");
	}
	else
	{
		$(".captive_project_rec").addClass("hide");
	}
});
function CheckFormSubmit()
{
	var cur_tab     = '<?php echo $tab;?>';
	if(cur_tab!='tab_2')
	{
		if($("#application_id").val()=='')
		{
			var project_category = $("#project_type").val();
			if(project_category!='<?php echo $ApplyOnlineObj->category_residental;?>' && $("#project_renewable_attr_chk").is(":checked") == false)
			{
				//$("#NoticeModal3").modal("show");
				//return false;
			}
		}
		else
		{
			<?php if($newSchemeApp == 0) { ?>
			var category = $("#category").val();
			if(category!='<?php echo $ApplyOnlineObj->category_residental;?>' && $("#renewable_attr_chk").is(":checked") == false)
			{
				$("#NoticeModal3").modal("show");
				return false;
			}
			<?php } ?>
		}
	}
}
function toggel_captive()
{
	if($(".renewable_attr_chk").is(":checked"))
	{
		$(".div_renewable_attr").removeClass('hide');
		$(".renewable_attr").each(function(opt){
			if($(this).is(":checked") && $(this).val() == 0) {
				$(".captive_project_rec").removeClass("hide");
			}
		});

	}
	else
	{
		$(".div_renewable_attr").addClass('hide');
		$(".captive_project_rec").removeClass("hide");
		$(".captive_project_rec").addClass("hide");
	}
}
function toggel_msme()
{
	$(".msme").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1) {
			$(".div_contract_load_more").removeClass("hide");
			<?php if($newSchemeApp == 0) { ?>
				$(".div_contract_50_load_more").removeClass("hide");
			<?php } ?>
		}
		if($(this).is(":checked") && $(this).val() == 0) {
			$(".div_contract_load_more").addClass("hide");
			<?php if($newSchemeApp == 0) { ?>
				$(".div_contract_50_load_more").addClass("hide");
			<?php } ?>
		}
	});
}
toggel_captive();
toggel_msme();
function detailsFromDiscom()
{
	var org_val=$('#division').val();
	$.ajax({
		type: "POST",
		url: "/ApplyOnlines/getDivision",
		data: {"discom":$('#discom').val()},
		success: function(response) {
		var result = $.parseJSON(response);
		$("#division").html("");
		$("#division").append($("<option />").val('').text('-Select Division-'));
		if (result.data.division != undefined) {
			$.each(result.data.division, function(index, title) {
				$("#division").append($("<option />").val(index).text(title));
			});
			$('#division').val(org_val);
			if(org_val!='')
			{
				if($("#discom").val()=='<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_surat;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_dahej;?>')
				{
				click_division();
				}
			}
		}
		}
	});

}

function ShowHideDiv() {
	var discom_data = document.getElementById("discom");
	var tno = document.getElementById("tno");
	tno.style.display = (discom_data.value == '<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || discom_data.value == '<?php echo $ApplyOnlineObj->torent_surat;?>' || discom_data.value == '<?php echo $ApplyOnlineObj->torent_dahej;?>')  ? "block" : "none";
	/*if (discom_data.value == '<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || discom_data.value == '<?php echo $ApplyOnlineObj->torent_surat;?>') {
		$("#name_of_consumer_applicant").removeAttr("readonly");
		$("#middle_name").removeAttr("readonly");
		$("#third_name").removeAttr("readonly");

			$("#consumer_no").removeAttr('readonly');
			$("#discom").removeAttr('disabled');
			$("#division").removeAttr('disabled');

	} else {*/
		$("#name_of_consumer_applicant").attr("readonly","readonly");
		$("#middle_name").attr("readonly","readonly");
		$("#third_name").attr("readonly","readonly");
		$("#sanction_load").attr("readonly","readonly");
		<?php if(!empty($applyonlineapproval))
		{
			?>
			//$("#consumer_no").attr('readonly',"readonly");
			$("#discom").attr('disabled','disabled');
			$("#division").attr('disabled','disabled');
		<?php
		}
		?>
	//}
}
ShowHideDiv();
function ShowHideOthers() {
	if($("#type_of_applicant").val() == 'Other') {
		$(".applicant_others").show();
	} else {
		$(".applicant_others").hide();
	}
}
ShowHideOthers();
$(".checkdata").click(function() {
	if($(this).is(":checked")) {
	   // $("#category").val('<?php echo $ApplyOnlineObj->category_government;?>').change();
	   // $('#social_consumer_guj').removeAttr('checked');
	}
});
$(".social_consumer_guj").click(function() {
	if($(".social_consumer_guj").is(":checked")) {
		$('#govt_agency').removeAttr('checked');
	}
});
$(".govt_agency").click(function() {
	$("#house_no_text").html('Premises Ownership Details No');
	$("#house_no_attach").html('Premises Ownership Document');
	if($(".govt_agency").is(":checked")){
		$('#social_consumer_guj').removeAttr('checked');
		<?php  if((strtotime($submitedStage) >= strtotime(GOVERMNET_AGENCY_DOCUMENTNOT) || strtotime($ApplyOnlines->created) >= strtotime(GOVERMNET_AGENCY_DOCUMENTNOT))) { ?>
			$("#house_no_text").html('Work Order No');
			$("#house_no_attach").html('Work Order Document');
		<?php } ?>
	}
});
function click_skip(){
	$('#search_data_btn').hide();
	$('#skip').hide();
}
displayUsageHours();
function displayUsageHours(value) {
	if(value > 0) {
		$('#usage_hours_div').css('display','');
	}
	else {
		$('#usage_hours_div').css('display','none');
	}
}
function click_division()
{
	if($("#discom").val()=='<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_surat;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_dahej;?>')
	{
		$.ajax({
			type: "POST",
			url: "/ApplyOnlines/getSubDivisionTorrent",
			data: {"division":$("#division").val()},
			success: function(response) {
				var result = $.parseJSON(response);
				$("#subdivision").html('');
				$("#subdivision").html('Sub-division: '+result.data.subdivision);
			}
		});
	}
	else
	{
		$("#subdivision").html('');
	}
}
function cancel_application(){
	window.location.href='/ApplyOnlines/applyonline_list';
}
function show_remaining()
{
	if($("#add_doc_2").hasClass('hide'))
	{
		$("#add_doc_2").removeClass('hide');
		$("#add_doc_2").show();
	}
	else if($("#add_doc_3").hasClass('hide'))
	{
		$("#add_doc_3").removeClass('hide');
		$("#add_doc_3").show();
	}
}
function clickCommon(actionPass)
{
	if($("#"+actionPass).is(':checked'))
	{
		swal({
			title: "Are you sure this application is for Common Meter?",
			text: "Note: As per the relevant policies of the Government of Gujarat, the State subsidy is not applicable on Common Meter connection. It is also to be noted that once this checkbox is clicked then it can't be changed. So, in case of any doubt kindly check with the competent authorities at DisCom and GEDA before proceeding ahead.",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, continue!",
			cancelButtonText: "No",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				swal("Ok", "", "success");
			} else {
				$("#"+actionPass).removeAttr('checked');
				swal("Cancelled", "", "error");
			}
		});
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
function toggle_rporec()
{
	$(".rpo_rec").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 0) {
			$(".div_rpo").addClass("hide");
			$(".div_rec").addClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 1) {
			$(".div_rpo").removeClass("hide");
			$(".div_rec").addClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 2) {
			$(".div_rpo").addClass("hide");
			$(".div_rec").removeClass("hide");
		}
		check_pv_cap();
	});
}
function toggle_gerc() {
	$(".gerc_dis").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1 )
		{
			$(".gerc_certificate").removeClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 0 )
		{
			$(".gerc_certificate").addClass("hide");
		}
	});
}
function toggle_ccp() {
	$(".rpo_cpp").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1 )
		{
			$(".capacity_cpp").removeClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 0 )
		{
			$(".capacity_cpp").addClass("hide");
		}
	});
}
function toggle_rpo_getco() {
	$(".cert_getco").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1 )
		{
			$(".capacity_rpo_cert").removeClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 0 )
		{
			$(".capacity_rpo_cert").addClass("hide");
		}
	});
}
function toggle_rec_reg() {
	$(".rec_registration").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1 )
		{
			$(".rec_registration_copy").removeClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 0 )
		{
			$(".rec_registration_copy").addClass("hide");
		}
	});
}
function toggle_rec_receipt() {
	$(".rec_receipt").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1 )
		{
			$(".rec_receipt_copy").removeClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 0 )
		{
			$(".rec_receipt_copy").addClass("hide");
		}
	});
}
function toggle_power_evaluation() {
	$(".rec_power_eval").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1 )
		{
			$(".rec_power_evaluation").removeClass("hide");
		}
		else if($(this).is(":checked") && $(this).val() == 0 )
		{
			$(".rec_power_evaluation").addClass("hide");
		}
	});
}

toggle_rporec();
toggle_gerc();
toggle_ccp();
toggle_rpo_getco();
toggle_rec_reg();
toggle_rec_receipt();
toggle_power_evaluation();
function check_pv_cap(){
	if($("#applyonlines-rpo-rec-2").is(":checked") && $("#applyonlines-rpo-rec-2").val() == 2) {

		var pv_capacity 	= $("#pv_capacity").val();
		var sanction_load 	= $("#sanction_load").val();

		if(pv_capacity >= 250) {
			$("#rec_is_valid_min_cap").val(1);
			$("#rec_is_valid_min_cap_text").html('Yes');
		}
		else {
			$("#rec_is_valid_min_cap").val(0);
			$("#rec_is_valid_min_cap_text").html('No');
		}
		if((sanction_load-pv_capacity) >=0) {
			console.log(sanction_load);
			$("#rec_is_allowed_sancation").val(1);
			$("#rec_is_allowed_sancation_text").html('Yes');
		}
		else {
			console.log(pv_capacity +'<='+ sanction_load);
			$("#rec_is_allowed_sancation").val(0);
			$("#rec_is_allowed_sancation_text").html('No');
		}
	}
	else {
		$("#rec_is_valid_min_cap").val(0);
		$("#rec_is_allowed_sancation").val(0);
		$("#rec_is_valid_min_cap_text").html('No');
		$("#rec_is_allowed_sancation_text").html('No');
	}
}
</script>