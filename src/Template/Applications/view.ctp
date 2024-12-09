<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.progressbar li {
    list-style: none;
    display: inline-block;
    width: calc(10% - 1px);
    position: relative;
    text-align: center;
    cursor: pointer;
}
.progressbar li.active:before {
    background-color: #FF6A39 !important;
    border-color: green;
}
.progressbar li.active + li:after {
    background-color: #FF6A39 !important;
}
.hide_class
{
	display:none;
}

.check-box-address{
	margin-left: 0px !important;
	margin-top: -15px !important;
}

</style>
<?php if($AjaxRequest=='0'){ ?>
<?php
	$this->Html->addCrumb('RE Application List','applications-list'); 
	$this->Html->addCrumb($pageTitle); 
	$DOCUMENT_PATH 		= "";
	if ($ApplyOnlines->id > 0) {
		$DOCUMENT_PATH = APPLYONLINE_PATH.$ApplyOnlines->id.'/';
	}
	if($ApplyOnlines->apply_state!=4 && strtolower($ApplyOnlines->apply_state)!='gujarat')
	{
		$ApplyOnlines->jreda_processing_fee = 0;
	}
	$approval           = $MStatus->Approvalstage($ApplyOnlines->id);
	$updateRequest      = false;
	$titleClass         = "col-md-8";
	// if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && $is_member==false)
	// {
	// 	$updateRequest  = true;
	// 	//$titleClass     = "col-md-6";
	// }
	$newSchemeApp 		= 0;
	$pvCapacityText 	= 'DC';
	$pvCapacityACText 	= 'AC';
	$PolicyYear     	= '2015';
	$GRNumber 			= 'SLR-11-2015-2442-B';
	$GRNumberDate 		= '13-08-2015';
	$submitedStage 		= $MStatus->getsubmittedStageData($ApplyOnlines->id);
	if(strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)))) {
		$newSchemeApp 		= 1;
		$pvCapacityText 	= 'AC';
		$pvCapacityACText 	= 'DC';
		$PolicyYear 		= '2021';
		$GRNumber 			= 'SLR/11/20121/77/B1';
		$GRNumberDate 		= '29th December 2020';
	}
?>
<div class="container">
	<div class="box">
		<div class="content">
			<div class="portlet box blue-madison applyonline-viewmain">
				<div class="row">
					<h2 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> View </h2>
					<?php
					if($updateRequest)
					{
						/*?>
						<div class="col-md-3 next btn btn-primary btn-lg mb-xlg cbtnsendmsg" style="float:left;margin-top:20px;">
							<a href="javascript:;" data-toggle="modal" data-title="Send Request for Update" data-target="#UpdateRequest" class="UpdateRequest dropdown-item showModel" data-url="<?php echo URL_HTTP; ?>ApplyOnlines/AddUpdateRequest/<?php echo encode($ApplyOnlines->id)?>" data-id="<?php echo encode($ApplyOnlines->id); ?>">Send Request for Update
							</a>
						</div>
						<?php
						*/
					}
					else
					{
					 /*   ?>
						<div class="col-md-1 next btn btn-primary btn-lg mb-xlg cbtnsendmsg" style="float:left;margin-top:20px;"><a href="javascript:;" onclick="javascript:updateApp('<?php echo $encode_id;?>')">Update</a></div>
						<?php */
					}
					?>
					
					<div class="col-md-4" style="margin-top:30px;text-align:right"><span style="font-size:18px;color:<?php echo $applicationCategory->color_code;?>"><strong style="text-align:left;">
							<?php 
								$category_text 			= '';
								if(isset($applicationCategory->category_name)) {
									if($ApplyOnlines->kusum_type == 1) {
										$category_text 	= 'KUSUM A';
									} elseif($ApplyOnlines->kusum_type == 2) {
										$category_text 	= 'KUSUM C';
									} else {
										$category_text 	= $applicationCategory->category_name;
									}
								}
								echo  $category_text;?></strong>
								</span><br>&nbsp;&nbsp;Application No.: <?php echo $ApplyOnlines->application_no;?></strong></div>
				</div>
				<div class="portlet-body form">
					 <div class="progressbar-container">
						<?php
						$ul_progress = 'progressbar';
						$str_append     = '';
						if($ApplyOnlines->application_status == $MStatus->APPROVED_FROM_GEDA && ($ApplyOnlines->category!=$ApplyOnlines->category_residental || ($ApplyOnlines->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1) || ($ApplyOnlines->govt_agency==1 && GOVERMENT_AGENCY==1)) && $ApplyOnlines->payment_status==0)
						{
							$str_append = ' - Payment Pending';
						}
						?>
						<ul class="<?php echo $ul_progress;?>">

							<?php $active = '';
							if($ApplyOnlines->application_type == 5){
								$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);
								foreach ($MStatus->apply_online_main_status_kusum as $key => $value) {
									
									$IsActive           = array_key_exists($key, $arr_application_status)?"active":"";
									if(empty($arr_application_status))
									{
										$IsActive       = ($key==$ApplyOnlines->application_status)?"active":"";
									}
									
									$text_apply     = '';
									$style          = 'font-size:8px;';

									if($str_append!='' && $key==2)
									{
										$IsActive   = '';
									}
									echo "<li class=\"".$IsActive."\" ><span style='".$style."'>".$value.$text_apply."</span></li>";
								
								}
							} else if($ApplyOnlines->injection_level == 1 || $ApplyOnlines->injection_level == 2){
								$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);
								if($ApplyOnlines->application_type == 4 || $ApplyOnlines->application_type == 3){
									$APPLY_ONLINE_MAIN_STATUS_TP =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','11'=> 'TFR','15'=>'WTG Co-Verification','7'=>'Developer Permission','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}else{
									$APPLY_ONLINE_MAIN_STATUS_TP =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','11'=> 'TFR','7'=>'Final Registration','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}
								//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS_TP as $key => $value) { 
									$IsActive = array_key_exists($key, $arr_application_status)?"active":"";
									if(empty($arr_application_status))
									{
										$IsActive = ($key==$ApplyOnlines->application_status)?"active":"";
									}
									if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlines->disclaimer_subsidy==1)
									{

									}
									else
									{
										$text_apply     = '';
										$style          = 'font-size:8px;';
										if($str_append!='' && $key==2)
										{
											$IsActive   = '';
										}
										
										if ($ApplyOnlines->pv_capacity<=10 && ( $key==7)) 
											{
												$text_apply = ' Self Certification';
												//$style      = "font-size:8px;";

												$value      = ($key==5) ? "Approval" : "Inspection";
											}
										?>
										<li class="<?php echo $IsActive;?>"><span style='<?php echo $style;?>'><?php echo $value.$text_apply; ?></span></li>
										<?php
									}
								}
							} elseif(isset($ApplyOnlines->application_connectivity_step['connectivity_type']) && $ApplyOnlines->application_connectivity_step['connectivity_type'] == 1 && $Application->grid_connectivity == 1){
								$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);
								if($ApplyOnlines->application_type == 4 || $ApplyOnlines->application_type == 3){
									$APPLY_ONLINE_MAIN_STATUS_STU =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','13'=> 'STU','15'=>'WTG Co-Verification','7'=>'Developer Permission','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}else{
									$APPLY_ONLINE_MAIN_STATUS_STU =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','13'=> 'STU','7'=>'Final Registration','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}
								//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS_STU as $key => $value) {
									
									$IsActive           = array_key_exists($key, $arr_application_status)?"active":"";
									if(empty($arr_application_status))
									{
										$IsActive = ($key==$ApplyOnlines->application_status)?"active":"";
									}
									if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlines->disclaimer_subsidy==1)
									{

									}
									else
									{
										$text_apply     = '';
										$style          = 'font-size:8px;';
										if($str_append!='' && $key==2)
										{
											$IsActive   = '';
										}
										
										if ($ApplyOnlines->pv_capacity<=10 && ( $key==7)) 
											{
												$text_apply = ' Self Certification';
												//$style      = "font-size:8px;";

												$value      = ($key==5) ? "Approval" : "Inspection";
											}
										?>
										<li class="<?php echo $IsActive;?>"><span style='<?php echo $style;?>'><?php echo $value.$text_apply; ?></span></li>
										<?php
									}
								
								}
							} elseif($ApplyOnlines->grid_connectivity == 2){
								$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);

								if($ApplyOnlines->application_type == 4 || $ApplyOnlines->application_type == 3){
									$APPLY_ONLINE_MAIN_STATUS_CTU =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','12'=> 'CTU - In Principal','15'=>'WTG Co-Verification','14'=> 'CTU - Final Principal','7'=>'Developer Permission','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}else{
									$APPLY_ONLINE_MAIN_STATUS_CTU =array(  '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','12'=> 'CTU - In Principal','14'=> 'CTU - Final Principal','7'=>'Final Registration','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS_CTU as $key => $value) {
									
									$IsActive           = array_key_exists($key, $arr_application_status)?"active":"";
									if(empty($arr_application_status))
									{
										$IsActive = ($key==$ApplyOnlines->application_status)?"active":"";
									}
									if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlines->disclaimer_subsidy==1)
									{

									}
									else
									{
										$text_apply     = '';
										$style          = 'font-size:8px;';
										if($str_append!='' && $key==2)
										{
											$IsActive   = '';
										}
										
										if ($ApplyOnlines->pv_capacity<=10 && ( $key==7)) 
											{
												$text_apply = ' Self Certification';
												//$style      = "font-size:8px;";

												$value      = ($key==5) ? "Approval" : "Inspection";
											}
										?>
										<li class="<?php echo $IsActive;?>"><span style='<?php echo $style;?>'><?php echo $value.$text_apply; ?></span></li>
										<?php
									}
								
								}
							}else {
								$arr_application_status = $MStatus->all_status_application($ApplyOnlines->id);
								if($ApplyOnlines->application_type == 4 || $ApplyOnlines->application_type == 3){
									$APPLY_ONLINE_MAIN_STATUS =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','4'=>'Stage 1: Connectivity','15'=>'WTG Co-Verification','5'=>'Stage 2: Connectivity','7'=>'Developer Permission','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}else{
									$APPLY_ONLINE_MAIN_STATUS =array( '1'=>'Application Submitted','2'=>'Document Verified','3'=>'Provisional Letter','4'=>'Stage 1: Connectivity','5'=>'Stage 2: Connectivity','7'=>'Final Registration','6'=>'CEI Drawing','8'=>'CEI Inspection','9'=>'Application Agreement','10'=>'Project Commissioning');
								}//'12'=>'Work Execution',
								foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) { 
									$IsActive = array_key_exists($key, $arr_application_status)?"active":"";
									if(empty($arr_application_status))
									{
										$IsActive = ($key==$ApplyOnlines->application_status)?"active":"";
									}
									if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlines->disclaimer_subsidy==1)
									{

									}
									else
									{
										$text_apply     = '';
										$style          = 'font-size:8px;';
										if($str_append!='' && $key==2)
										{
											$IsActive   = '';
										}
										
										if ($ApplyOnlines->pv_capacity<=10 && ( $key==7)) 
											{
												$text_apply = ' Self Certification';
												//$style      = "font-size:8px;";

												$value      = ($key==5) ? "Approval" : "Inspection";
											}
										?>
										<li class="<?php echo $IsActive;?>"><span style='<?php echo $style;?>'><?php echo $value.$text_apply; ?></span></li>
										<?php
									}
								}	
							}
							 ?>
						</ul>

					</div> 
					
					<div class="form-body">
					
						
						<div class="greenbox">
							<h4>General Profile Details</h4>
						</div>
						 <div class="row">
							<div class="col-md-10 col-sm-8 ">
								<div class="row">
									<div class="col-md-7">
										<label>Name of Applicant Company</label>
										<?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_applicant; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Category</label>
										<?php 
											$category_text 			= '';
											if(isset($applicationCategory->category_name)) {
												if($ApplyOnlines->kusum_type == 1) {
													$category_text 	= 'KUSUM A';
												} elseif($ApplyOnlines->kusum_type == 2) {
													$category_text 	= 'KUSUM C';
												} else {
													$category_text 	= $applicationCategory->category_name;
												}
											}
											echo  $category_text;?>
										<?php //echo isset($applicationCategory->category_name) ? $applicationCategory->category_name : '';?>
									</div>
								</div>
								<?php /*<div class="row">
									<div class="col-md-12">
										<label>Address of Registered Office</label>
										 <?php echo $ApplyOnlines->address; ?>
									</div>
								</div>*/?>
								<div class="row">
									<div class="col-md-7">
										<label>Street/House no</label>
										 <?php echo $ApplyOnlines->address1; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Taluka/Village</label>
										 <?php echo $ApplyOnlines->taluka; ?>
									</div>
								</div> 
								
								<div class="row">
									<div class="col-md-7">
										<label>City</label>
										 <?php echo $ApplyOnlines->city; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>State</label>
										 <?php echo $ApplyOnlines->state; ?>                     
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<label>District</label>
										 <?php echo isset($district->name) ? $district->name : ''; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Pincode</label>
										 <?php echo $ApplyOnlines->pincode; ?>
									</div>
								</div> 
								<div class="row">
									<div class="col-md-7 ">
										<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
										 <?php echo $ApplyOnlines->contact; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Consumer Mobile</label>
										 <?php echo $ApplyOnlines->mobile; ?>
									</div>
								</div> 
								<div class="row">
									<div class="col-md-7 ">
										<label>Consumer Email</label>
										 <?php echo $ApplyOnlines->email; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>PAN card no.</label>
										 <?php echo $ApplyOnlines->pan; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-7 ">
										<label>GST No. of Consumer</label>
										 <?php echo $ApplyOnlines->GST; ?>
									</div>
									<div class="col-md-5 left_space">
										<label>Type of Applicant</label>
										 <?php echo $ApplyOnlines->type_of_applicant; ?>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-7 ">
										<label>Is the Applicant a MSME?</label>
										
										<?php if($ApplyOnlines->msme ==1){
											 echo 'Yes';
										
										} else{
											echo 'No';
										}?>
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
								 <?php echo isset($ApplyOnlines->name_director) ? $ApplyOnlines->name_director : ''; ?>
							</div>
							
							<div class="col-md-6">
								<label>Designation</label>
								 <?php echo $ApplyOnlines->type_director; ?>
							</div>
							<div class="col-md-6">
								<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
								 <?php echo $ApplyOnlines->director_whatsapp; ?>
							</div>
							<div class="col-md-6">
									<label>Mobile</label>
									 <?php echo $ApplyOnlines->director_mobile; ?>
								</div>
							<div class="col-md-6">
									<label>Email</label>
									 <?php echo $ApplyOnlines->director_email; ?>                     
							</div>
						</div>
						<div class="greenbox">
							<h4>Authorized Details</h4>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Name of the authorized Signatory</label>
								 <?php echo isset($ApplyOnlines->name_authority) ? $ApplyOnlines->name_authority : ''; ?>
							</div>
							
							<div class="col-md-6">
								<label>Designation</label>
								 <?php echo $ApplyOnlines->type_authority; ?>
							</div>
							<div class="col-md-6">
								<label>WhatsApp No.<img src="/img/whatsapp.png" height="20" width="20" style="width: 20px;border:none;padding:0px;margin: 0px;" /></label>
								 <?php echo $ApplyOnlines->authority_whatsapp; ?>
							</div>
							<div class="col-md-6">
									<label>Mobile</label>
									 <?php echo $ApplyOnlines->authority_mobile; ?>
							</div>
							<div class="col-md-6">
									<label>Email</label>
									 <?php echo $ApplyOnlines->authority_email; ?>                     
							</div>
						</div>
						<div class="greenbox">
							<h4>Technical Details</h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>DisCom Name</label>
									 <?php echo isset($discom_list[$ApplyOnlines->discom]) ? $discom_list[$ApplyOnlines->discom] : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Project State</label>
									 <?php echo isset($ApplyOnlines->project_state) ? $ApplyOnlines->project_state : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Grid Connectivity</label>
									<?php echo isset($gridLevel[$ApplyOnlines->grid_connectivity]) ? $gridLevel[$ApplyOnlines->grid_connectivity] : '';?> 	
								</div>
								<div class="col-md-6">
									<label>Power Injection Level</label>
									<?php if($ApplyOnlines->grid_connectivity == 1){
										echo isset($injectionLevel[$ApplyOnlines->injection_level]) ? $injectionLevel[$ApplyOnlines->injection_level] : '';
									}?>
									<?php if($ApplyOnlines->grid_connectivity == 2){
										echo isset($ApplyOnlines->injection_level_ctu) ? $ApplyOnlines->injection_level_ctu : '';
									}?>
								</div>
								<?php /*<div class="col-md-6"> 
									<label class="nodots"> Total Capacity (in MW)</label>
									<?php echo $ApplyOnlines->total_capacity; ?>
								</div>*/?>
								
								<div class="col-md-6">
									<?php ?>
									<label>Name of Proposed <?php echo ($ApplyOnlines->grid_connectivity == 1)  ? 'GETCO' : 'PGCIL';?> Substation </label>
									 <?php echo isset($ApplyOnlines->getco_substation) ? $ApplyOnlines->getco_substation : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Village</label>
									 <?php echo isset($ApplyOnlines->project_village) ? $ApplyOnlines->project_village : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Taluka</label>
									 <?php echo isset($ApplyOnlines->project_taluka) ? $ApplyOnlines->project_taluka : ''; ?>
								</div>
								<div class="col-md-6">
									<label>District </label>
									 <?php echo isset($district->name) ? $district->name : ''; ?>
								</div>
								
								<?php if($ApplyOnlines->application_type == 2) { ?>
							
									<div class="col-md-6">
										<label>Project Capacity AC (in MW)</label>
										 <?php echo isset($ApplyOnlines->pv_capacity_ac) ? $ApplyOnlines->pv_capacity_ac : ''; ?>
									</div>
									<div class="col-md-6">
										<label>Project Capacity DC (in MW)</label>
										 <?php echo isset($ApplyOnlines->pv_capacity_dc) ? $ApplyOnlines->pv_capacity_dc : ''; ?>
									</div>
							
								<?php } elseif($ApplyOnlines->application_type == 3 || $ApplyOnlines->application_type == 4) { ?>
									<div class="col-md-6">
										<label>Total WTG (Nos.)</label>
										 <?php echo isset($ApplyOnlines->wtg_no) ? $ApplyOnlines->wtg_no : ''; ?>
									</div>
									<?php /*<div class="col-md-6">
										<label>Total Capacity of each WTG (in MW)</label>
										 <?php echo isset($ApplyOnlines->capacity_wtg) ? $ApplyOnlines->capacity_wtg : ''; ?>
									</div>*/?>
									<div class="col-md-6">
										<label>Total WTG capacity (in MW)</label>
										 <?php echo isset($ApplyOnlines->total_capacity) ? $ApplyOnlines->total_capacity : ''; ?>
									</div>
									<?php /*<div class="col-md-6">
										<label>Make</label>
										 <?php echo isset($ApplyOnlines->make) ? $ApplyOnlines->make : ''; ?>
									</div>*/?>
							
								<?php 	} if($ApplyOnlines->application_type == 4) { ?>
									<div class="col-md-6">
										<label>Total SPV Module (Nos.)</label>
										 <?php echo isset($totalModulenos['nos_mod_inv']) ? $totalModulenos['nos_mod_inv'] : ''; ?>
									</div>
									<div class="col-md-6">
										<label>Total SPV Module capacity (in MW)</label>
										 <?php echo isset($totalModulenos['mod_inv_total_capacity']) ? $totalModulenos['mod_inv_total_capacity']: ''; ?>
									</div>
									<div class="col-md-6">
										<label>Total Inverter (Nos.)</label>
										 <?php echo isset($totalInverternos['nos_mod_inv']) ? $totalInverternos['nos_mod_inv'] : ''; ?>
									</div>
									<div class="col-md-6">
										<label>Total Inverter capacity (in MW)</label>
										 <?php echo isset($totalInverternos['mod_inv_total_capacity']) ? $totalInverternos['mod_inv_total_capacity'] : ''; ?>
									</div>
									<div class="col-md-6">
										<label>Total Commulative capacity AC (in MW)</label>
										<?php echo isset($ApplyOnlines->total_wind_hybrid_capacity) ? $ApplyOnlines->total_wind_hybrid_capacity : ''; ?>
									</div>
									<div class="col-md-6">
										<label>Total Commulative capacity DC (in MW)</label>
										 <?php echo isset($totalModulenos['mod_inv_total_capacity']) ? $totalModulenos['mod_inv_total_capacity'] : ''; ?>
									</div>
								<?php  } ?>
								
								<div class="col-md-6">
									<label>Expected Annual output of energy from the proposed project in <?php echo ($ApplyOnlines->application_type == 2) ? 'kWh' : 'MWH'; ?></label>
									 <?php echo isset($ApplyOnlines->project_energy) ? $ApplyOnlines->project_energy : ''; ?>
								</div>
								
								<div class="col-md-6">
									<label>Approximate Project Cost (Rs. in lacs)</label>
									 <?php echo isset($ApplyOnlines->project_estimated_cost) ? $ApplyOnlines->project_estimated_cost : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Approximate employment generation from the proposed project (in Nos.)</label>
									 <?php echo isset($ApplyOnlines->approx_generation) ? $ApplyOnlines->approx_generation : ''; ?>
								</div>
								<div class="col-md-6">
									<label>Tentative date of commissioning</label>
									 <?php echo isset($ApplyOnlines->comm_date) ? $ApplyOnlines->comm_date : ''; ?>
								</div>
								<div class="col-md-6">
									<label>End use of electricity</label>
									<?php 
									if($ApplyOnlines->grid_connectivity == 1) {
										echo isset($EndSTU[$EndUseDetails->application_end_use_electricity]) ? $EndSTU[$EndUseDetails->application_end_use_electricity] : '';
									} elseif($ApplyOnlines->grid_connectivity == 2) {
										echo isset($EndCTU[$EndUseDetails->application_end_use_electricity]) ? $EndCTU[$EndUseDetails->application_end_use_electricity] : '';
									}
									?>
								</div>
							</div>
						</div>
						
						<div class="greenbox">
							<h4>Fee structure for Provisional Registration at GEDA</h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>GEDA Processing Fee (in Rs.)</label>
									<?php echo isset($ApplyOnlines->application_fee) ? $ApplyOnlines->application_fee : '';?> 	
								</div>
								<div class="col-md-6">
									<label>GST at 18% (in Rs.)</label>
									<?php echo isset($ApplyOnlines->gst_fees) ? $ApplyOnlines->gst_fees : '';?> 	
								</div>
								<div class="col-md-6">
									<label>Total (in Rs.)</label>
									<?php echo isset($ApplyOnlines->application_total_fee) ? ($ApplyOnlines->application_total_fee + $ApplyOnlines->tds_deduction) : '';?> 	
								</div>
								<div class="col-md-6">
									<label>liable to deduct TDS as per Income Tax Act</label>
									<?php 
									if(isset($ApplyOnlines->liable_tds) && ($ApplyOnlines->liable_tds == 1)) {
										echo 'Yes';
									} elseif(isset($ApplyOnlines->liable_tds) && $ApplyOnlines->liable_tds == 0) {
										echo 'No';
									}else{
										echo '-';
									}
									?>
								</div>
								<?php if(isset($ApplyOnlines->liable_tds) && ($ApplyOnlines->liable_tds == 1)) { ?>
									<div class="col-md-6">
										<label>TDS Deduction</label> <?php echo $ApplyOnlines->tds_deduction;?>
									</div>
									<div class="col-md-6">
										<label>Net Payable amount</label>  <?php echo ($ApplyOnlines->application_total_fee );?>
									</div>
								<?php }?>
								<div class="col-md-6">
									<label>Agree to Terms and Conditions</label>
									<?php 
									if(isset($ApplyOnlines->terms_agree) && ($ApplyOnlines->terms_agree == 1)) {
										echo 'Yes';
									} elseif(isset($ApplyOnlines->terms_agree) && $ApplyOnlines->terms_agree == 0) {
										echo 'No';
									}else{
										echo '-';
									}
									?>	
								</div>
							</div>
						</div>
						<?php if(isset($connectivitystage_data) && !empty($connectivitystage_data)) { ?>
							<div class="greenbox">
								<h4>Connectivity Stage Details</h4>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label>Date of Connectivity</label>
										<?php echo isset($connectivitystage_data->Date_of_connectivity) ? $connectivitystage_data->Date_of_connectivity : ''; ?>
									</div>
									<div class="col-md-6">
										<label>Date of Validity</label>
										<?php echo isset($connectivitystage_data->Date_of_validity) ? $connectivitystage_data->Date_of_validity : ''; ?>
									</div>
									<div class="col-md-6">
										<label> Grid Connectivity Capacity (in MW)</label>
										<?php echo isset($connectivitystage_data->grid_connectivity_capacity) ? $connectivitystage_data->grid_connectivity_capacity : ''; ?> 
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="greenbox">
							<h4> Attached Document </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
								  	<?php if(!empty($ApplyOnlines->pan_card)) { ?>
										<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->pan_card)) { ?>
											 <label>Pan Card</label>
											 <a href="<?php echo URL_HTTP.'app-docs/a_pan_card/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
												
										<?php } ?>
									<?php } ?>
								</div>
								<div class="col-md-3">
								  	<?php if(!empty($ApplyOnlines->registration_document)) { ?>
										<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->registration_document)) { ?>
											<label>Enclose self-certified copy</label>
											<a href="<?php echo URL_HTTP.'app-docs/a_registration_document/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
										<?php } ?>
									<?php } ?>
								</div>
								<div class="col-md-3">
								  	<?php if(!empty($ApplyOnlines->a_msme)) { ?>
										<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->a_msme)) { ?>
											<label>MSME Document</label>
											<a href="<?php echo URL_HTTP.'app-docs/a_msme/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
										<?php } ?>
									<?php } ?>
								</div>
								<div class="col-md-3">
									<?php if(!empty($ApplyOnlines->upload_undertaking)) { ?>
										<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->upload_undertaking)) { ?>
											<label>Upload Undertaking form</label>
											<a href="<?php echo URL_HTTP.'app-docs/a_upload_undertaking/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
											
										<?php } ?>
									<?php } ?>
								</div>
								<div class="col-md-3">
								  	<?php if(!empty($ApplyOnlines->f_sale_discom)) { ?>
										<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->f_sale_discom)) { ?>
											<label>Sale to DISCOM</label>
											<a href="<?php echo URL_HTTP.'app-docs/f_sale_discom/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
										<?php } ?>
									<?php } ?>
								</div>

								<div class="col-md-12">
									<?php if(!empty($ApplyOnlines->d_file_board)) { ?>
										<?php if ($Couchdb->documentExist($ApplyOnlines->id,$ApplyOnlines->d_file_board)) { ?>
											<label>Copy of Board resolution authorizing person for signing all the documents related to proposed project</label>
											<a href="<?php echo URL_HTTP.'app-docs/a_file_board/'.encode($ApplyOnlines->id); ?>" target="_blank">
												<i class="fa fa-file"></i>
											</a>
										<?php } ?>
									<?php } ?>   
								</div>
							</div>
						</div>
						
						<?php if((isset($applyOnlinesDataDocList) && !empty($applyOnlinesDataDocList))) { ?>
						<div class="greenbox">
							<h4> Other Attachment </h4>
						</div>
						<div class="form-group">
							<div class="row">
								
								<?php
									foreach ($applyOnlinesDataDocList as $key => $value) 
									{
										$path = APPLICATIONS_PATH.$ApplyOnlines->id.'/'.$value['file_name'];
										if (empty($value['file_name']) || !$Couchdb->documentExist($ApplyOnlines->id,$value['file_name'])) continue;
										?>
										<div Class="col-md-4">
											<label class="attach-lable">
											<?php if($value['doc_type']=='others') {
												echo $value['title'];
											} else{
												echo $value['title']; 
											}?>
											</label>
											&nbsp;&nbsp;
											<?php /* <a href="<?php echo URL_HTTP.APPLICATIONS_PATH.encode($value['id']).'/'.$value['file_name']; ?>" target="_blank"><i class="fa fa-file"></i></a> */?>
											<a href="<?php echo URL_HTTP . $path; ?>" target="_blank"><i class="fa fa-file"></i></a>
										</div>
										<?php 
									}  ?>
							</div>
						</div>
						<?php } ?>
						<?php if((isset($applyOnlinesDataDocListStage1) && !empty($applyOnlinesDataDocListStage1))) { ?>
						<div class="greenbox">
							<h4> Connectivity Stage  Attachment </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php
									foreach ($applyOnlinesDataDocListStage1 as $key => $value) 
									{
										if (empty($value['file_name']) || !$Couchdb->documentExist($ApplyOnlines->id,$value['file_name'])) continue;
										?>
										<div Class="col-md-4">
											<label class="attach-lable">
											<?php 
												echo $value['title']; 
											?>
											</label>
											<?php if($value['doc_type']=='CTUstep1') {?>
												&nbsp;&nbsp;
												<a href="<?php echo URL_HTTP.'app-docs/CTUstep1/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
											<?php } else{ ?>
												&nbsp;&nbsp;
											<a href="<?php echo URL_HTTP.'app-docs/STUstep1/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a> 
											<?php }?>
										</div>
										<?php 
									}  ?>

							</div>
						</div>
						<?php } ?>
						<?php if((isset($applyOnlinesDataDocListStage2) && !empty($applyOnlinesDataDocListStage2))) { ?>
						<div class="form-group">
							<div class="row">
								<?php
									foreach ($applyOnlinesDataDocListStage2 as $key => $value) 
									{
										if (empty($value['file_name']) || !$Couchdb->documentExist($ApplyOnlines->id,$value['file_name'])) continue;
										?>
										<div Class="col-md-4">
											<label class="attach-lable">
											<?php 
												echo $value['title']; 
											?>
											</label>
											<?php if($value['doc_type']=='CTUstep2') {?>
												&nbsp;&nbsp;
												<a href="<?php echo URL_HTTP.'app-docs/CTUstep2/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
											<?php } else{ ?>
												&nbsp;&nbsp;
											<a href="<?php echo URL_HTTP.'app-docs/STUstep2/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a> 
											<?php }?>
										</div>
										<?php 
									}  ?>

							</div>
						</div>
						<?php } ?>
						<?php if((isset($applyOnlinesDataDocListTP) && !empty($applyOnlinesDataDocListTP))) { ?>
						<div class="greenbox">
							<h4> TFR Attachment </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php
									$path = APPLICATIONS_PATH.$ApplyOnlines->id.'/'.$applyOnlinesDataDocListTP['file_name'];
								?>
								<div Class="col-md-4">
									<label class="attach-lable">
									<?php 
										echo $applyOnlinesDataDocListTP['title']; 
									?>
									</label>
									&nbsp;&nbsp;
									<a href="<?php echo URL_HTTP.'app-docs/TPfile/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if((isset($applyOnlinesDataDocListPC) && !empty($applyOnlinesDataDocListPC))) { ?>
						<div class="greenbox">
							<h4> Project Commissioning Attachment </h4>
						</div>
						<div class="form-group">
							<div class="row">
								<?php
									$path = APPLICATIONS_PATH.$ApplyOnlines->id.'/'.$applyOnlinesDataDocListPC['file_name'];
								?>
								<div Class="col-md-4">
									<label class="attach-lable">
									<?php 
										echo $applyOnlinesDataDocListPC['title']; 
									?>
									</label>
									&nbsp;&nbsp;
									<a href="<?php echo URL_HTTP.'app-docs/ProjectCommissioning/'.encode($ApplyOnlines->id); ?>" target="_blank"><i class="fa fa-file"></i></a>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="greenbox">
							<h4> Developer Permission Application </h4>
						</div>
						
						<?php if(isset($openAccessDeveloperApp)) { ?>
						<div class="form-group">						
							<a class="next btn btn-primary btn-sm " href="/ApplicationDeveloperPermission/open_access_view/<?php echo encode($openAccessDeveloperApp->id).'/0' ?>">View Developer Permission</a>
						</div>
						<?php } ?>
						<?php if(isset($windDeveloperApp)) { ?>
						<div class="form-group">
							<div class="row">
								<?php foreach($windDeveloperApp as $wk=>$wv){	 ?>
								<div class="col-md-3">						
									<a class="next btn btn-primary btn-sm " href="/ApplicationDeveloperPermission/wind_view/<?php echo encode($wv->id).'/0' ?>">View Developer Permission - <?php echo $wv->app_order?></a>
								</div>
								<?php } ?>
							</div>
						</div >
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog2">
		<div class="modal-content">
			
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
function updateApp(id){
	var application_id = id;
	swal({
  title: "Are you sure?",
  text: "You want to update the data?",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Update it!",
  cancelButtonText: "No, Cancel plx!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {  
	$(".confirm").attr('disabled','disabled');
	$.ajax({

				type: "POST",
				url: "/ApplyOnlines/updateApiData",
				data: {'update_application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Updated!", "Your Application has been updated successfully.", "success");
						   window.location.reload();
					}
					else
					{
						swal("Fail!", result.response.response_msg, "error");
						
					}
				}
			});
	
  } else {
	swal("Cancelled", "Your Application is safe :)", "error");
  }
});
}
$(".showModel").click(function(){
	var modelheader = $(this).data("title");
	var modelUrl = $(this).data("url");
	document_window = $(window).width() - $(window).width()*0.05;
	document_height = $(window).height() - $(window).height() * 0.20;  
	modal_body = '<div class="modal-header" style="min-height: 45px;">'+
	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
	'</div>'+
	'<div class="modal-body">'+
	'<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
	'</div>';
	
	$('#myModal').find(".modal-content").html(modal_body);
	$('#myModal').modal('show');
	$('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
	$('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	return false;
});
window.closeModal = function(){ $('#myModal').modal('hide'); };

$(".updaterequest_btn").click(function() {
	$('#UpdateRequestForm').submit();
	var form = $('#UpdateRequestForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$(".updaterequest_btn").attr('disabled','disabled');
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/UploadDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#UpdateRequestForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#UpdateRequestForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".updaterequest_btn").removeAttr('disabled');
					}

				}
			});
	
});

</script>