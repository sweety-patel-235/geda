<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style>
.rowcat .col-md-6 {
border: 1px solid #c1c1c1;
}
.rowcat .control-label {
text-align: right;
}
.rowcat1 .row {
border: 1px solid #c1c1c1;
padding: 7px;
}
.form-horizontal .radio {
	
	padding-top: 0px !important;
}
.check-box-address{
	margin-top: 20px !important;
}
/*.input-group {
    width: 285px !important;
}*/
.applay-online-from input[type="checkbox"] {
	width: 18px;
	float: left;
	margin-top: 15px;
}
.button-right {
	float: right;
}
.mendatory_field
{
  color : red;
}
#serialData .table td {
	text-align: left !important;
}
.table-bordered {
    border: 1px solid #dee2e6 !important; 
}
.fieldset
{
    border: 1px solid #ddd !important;
    margin: 0;
    min-width: 0;
    padding: 10px;
    position: relative;
    border-radius:4px;
    background-color:#f5f5f5;
    padding-left:10px!important;
}
.fieldset-legends
{
    font-size:14px;
    font-weight:bold;
    margin-bottom: 0px;
    width: 35%;
    border: 1px solid #dddddd;
    border-radius: 4px;
    padding: 5px 5px 5px 10px;
    background-color: #dddddd;
}
.input-group {
   width: 250px;
}
#tbl_wind_info th, td {
	white-space: nowrap !important;
	flex-flow: nowrap !important;
	flex-wrap: nowrap !important;
}
#tbl_wind_info th.sorting {
	white-space: nowrap !important;
	flex-flow: nowrap !important;
	flex-wrap: nowrap !important;
}
</style>
<?php
	$this->Html->addCrumb('RE Application','applications-list'); 
	$this->Html->addCrumb($pageTitle); 
	$DOCUMENT_PATH 		= "";
	$titleClass         = "col-md-8";
	
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container-fluid applications-from">
	<div class="row col-md-12">
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> WTG Coordinates </h4>
		<div class="col-md-4" style="margin-top:30px;text-align:right">
			<span style="font-size:18px;color:<?php echo $applicationCategory->color_code;?>">
				<strong style="text-align:left;"><?php echo isset($applicationCategory->category_name) ? $applicationCategory->category_name : '';?></strong></span><br>&nbsp;&nbsp;Application No.: <?php echo $Applications->application_no;?>
		</div>
	</div>
	<?php if($is_member == false){?>
		<div class="row" style="border-radius:5px; padding:20px;">
			<table class="table custom_table lable_left">
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 m-2">
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">Add the WTG Coordinates</legend>
									<div class="col-md-12">
										
										<div class="row mt-xlg">
											<div class="col-md-12">
												<lable class="col-md-4" style="margin-top: 10px;">Total Coordinates can Add</lable>
											<div class="col-md-4">
												<?php echo $this->Form->input('geo_cordinate_file', array('label' => false,'class'=>'form-control','placeholder'=>'','value'=>$total_wtg_application ,'readonly'=>'readonly')); ?>
											</div>
											<?php if($total_wtg_application > 0){ ?>
												<div class="col-md-4 text-align-center">
												<?php echo $this->Form->input('Add', array('label' => false, 'class' => ' btn btn-secondary btn-sm  AddApplication ','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#AddApplication", 'data-id'=>$id)); ?>
											</div>
											<?php } else {

											}?>
											
											
										</div>
								</fieldset>
							</div>
							<div class="col-md-4 m-2">
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">Applied WTG Coordinates</legend>
									<div class="col-md-12">
										
										<div class="row mt-xlg">
											<div class="col-md-12" style="margin-bottom: 18px;">
												<?php $applied_coordinated = count($geo_application_data);
													echo $applied_coordinated;
												?>
											</div>
											
										</div>
									</div>
								</fieldset>
							</div>
							
							<!-- Verified PDF <?php //echo $key+1 ?> -->
							<?php if((isset($Geo_application_verification_log) && !empty($Geo_application_verification_log))) { ?>
							<div class="col-md-4 m-2">
								
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width"> Geo Location Verification</legend>
											<div class="row mt-xlg">
												<div class="col-md-12">
														<?php
														$counter = 1;
															foreach ($Geo_application_verification_log as $key => $value) 
															{?>
																<div Class="col-md-3">
																	
																	<a href="/GeoApplications/downloadGeoApplicationVerifiedPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
																	<div style="text-align: justify;text-justify: inter-word;">
																	<i class="fa fa-download"></i> <span ><?php echo $Applications->registration_no;?> <?php echo date('d-M-Y',strtotime($value['created'])); ?></span></div></a>
																</div><br>
														<?php $counter++; }  ?> 
													</div>
											</div>
								</fieldset>
							</div>
							<?php }?>
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php }else{?>
		<div class="row" style="border-radius:5px; padding:20px;">
			<table class="table custom_table lable_left">
				<tbody>
					<tr>
						<td>
							
							<?php if((isset($Geo_application_verification_log) && !empty($Geo_application_verification_log))) { ?>
							<div class="col-md-6 m-2">
								
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width"> Geo Location Verification</legend>
											<div class="row mt-xlg">
												<div class="col-md-12">
														<?php
														$counter = 1;
															foreach ($Geo_application_verification_log as $key => $value) 
															{?>
																<div Class="col-md-3">
																	
																	<a href="/GeoApplications/downloadGeoApplicationVerifiedPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
																	<div style="text-align: justify;text-justify: inter-word;">
																	<i class="fa fa-download"></i> <span > Verified PDF <?php echo $key+1 ?></span></div> </a>
																</div>
														<?php $counter++; }  ?> 
													</div>
											</div>
								</fieldset>
							</div>
							<?php }?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php }?>
	<div class="col-md-12" style="margin-bottom: 18px;">
		<?php echo $this->Form->input('Add Offline Data', array('label' => false, 'class' => ' btn btn-secondary btn-sm  AddOfflineApplication ','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#AddOfflineApplication", 'data-id'=>$id ,'prod-id'=>$Applications->registration_no)); ?>
	</div>
							
 	<div class="row">
	 	<div class="col-md-12 ">

			<div class="table table-responsive table-bordered noborder" >
				 
				 <table id= "tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead" >

				  	<tr class="thead-dark">
				  		<thead class="thead-dark">
				  		<th colspan="20" style="text-align:center;" >Details of WTG Coordinate Verification </th>
				  		</thead>
				  	</tr>
				  	<tr >
				  		<td rowspan = "2"  style="text-align:center;" >Sr No </td>
				  		<td rowspan = "2"  style="text-align:center;" >WTG Location </td>
				  		<td rowspan = "2"  style="text-align:center;" >Type of Land </td>
				  		<td rowspan = "2"  style="text-align:center;" >Land Survey No </td>
				  		<td rowspan = "2"  style="text-align:center;" >Land Area in sq. mtr </td>
				  		<td rowspan = "2"  style="text-align:center;" >RLMM Validity </td>
				  		<!--<td rowspan = "2"  style="text-align:center;" >Sub Lease Deed </td> -->
				  		
				  		<td rowspan = "2"  style="text-align:center;" >District </td>
				  		<td rowspan = "2"  style="text-align:center;" >Taluka </td>
				  		<td rowspan = "2"  style="text-align:center;" >Village </td>
				  		<td rowspan = "2"  style="text-align:center;" >Consent Letter (Landowner)</td>
				  		<td colspan = "3"  style="text-align:center;" >Applied Coordinates </td>
				  		<td colspan = "6"  style="text-align:center;" >Details of WTG</td>
				  		<td rowspan = "2"  style="text-align:center;" >Action <?php if($is_member == false){?><input type="checkbox" class="select_all check" id="0"/><?php }?> </td>
				  	</tr>
				  	<tr>
				  		<td style="text-align:center;" >UTM Zone </td>
				  		<td style="text-align:center;" >UTM Easting </td>
				  		<td style="text-align:center;" >UTM Northing</td>
				  		<td style="text-align:center;" >RLMM </td>
				  		<td style="text-align:center; width: 20px;" >Make </td>
				  		<td style="text-align:center;width: 20px;" >Model No </td>
				  		<td style="text-align:center;" >Capacity in KW  </td>
				  		<td style="text-align:center;" >Rotor Dia in meters </td>
				  		<td style="text-align:center;"  >Hub Height in meters </td>
				  	</tr>
				  	<tbody>
				  		
					 	<?php $counter = 1;
					  	foreach ($geo_application_data as $key => $value) {?>
				  			<?php
				  				//$remainingday 			= $ApplicationGeoLocation->CheckValidityData($value['id'],$value['application_id']);
				  				//$clash_data 			= $ApplicationGeoLocation->CheckClashData($value->x_cordinate,$value->y_cordinate,$value->id);
				  				$member_clash_data 		= $ApplicationGeoLocation->Member_CheckClashData($value['id']);

				  				$internal_clashed_docs 	= $ApplicationGeoLocation->internal_clashed_docs($value['id']);
				  				//echo"<pre>"; print_r($internal_clashed_docs->clashed_geo_id); 
				  				// echo"<pre>"; print_r($member_clash_data); 
				  				// echo"<pre>"; print_r($clash_data); 
				  				//echo"<pre>"; print_r($member_clash_data); 
							  				//echo"<pre>"; print_r($value); 
							  		// 		if(!empty($clash_data)){
								  	// 			if($value->approved == 3){
								  	// 				if($is_member == true){
								  	// 					$clash_text = 'Clashing';
								  	// 				}else{
											// 			$clash_text = 'You Are In Queue!';
								  	// 				}
								  	// 			}else{
											// 		$clash_text = '';
											// 	}
											// }
				  						//Clashing:307FE2,Internal Clashing:cdcd09,You Are In Queue:bf34cf
				  				$clash_data ='';
								if(!empty($clash_data)){
				  					if($is_member == true && $clash_data == 'Clashing'){
				  						$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
				  					} elseif($clash_data == 'Internal Clashing'){
										$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
				  					}else{
										$clash_text = '<p style="text-decoration: underline;color: #bf34cf;">You Are In Queue!</p>';
				  					}
								}else if(!empty($member_clash_data)){
				  					if($is_member == true && $member_clash_data == 'Clashing'){
				  						$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
				  					}elseif($member_clash_data == 'Internal Clashing'){
										$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
				  					}else{
										$clash_text = '<p style="text-decoration: underline;color: #bf34cf;">You Are In Queue!</p>';
				  					}
								}else{
									$clash_text = '';
								}
								echo $this->Form->create($Applications, ['name'=>'geo_cordinate'.$counter,'id'=>'geo_cordinate'.$counter,'enctype'=>"multipart/form-data"]);
			                    echo $this->Form->input('application_id',['label' => false,'type'=>'hidden','value'=>$id]);
			                  
			                    $remainingday = '';

			                    if($value['payment_status'] == 1 || $value['approved'] == 1 || $value['approved'] == 2){
			                    	$disabled 	=  "disabled";
			                    }else{
			                    	$disabled   = '';
			                    }
			                    $this->Form->templates(['inputContainer' => '{{content}}']);

			                    // Key to check
								$keyToCheckrlmm = $value->rlmm;
								if (array_key_exists($keyToCheckrlmm, $rlmm)) {
								    // Display the value corresponding to the key
								     $rlmm_name = $rlmm[$keyToCheckrlmm]; 
								}

								// Key to check
								$keyToCheckzone = $value->zone;
								if (array_key_exists($keyToCheckzone, $zone_drop_down)) {
								    // Display the value corresponding to the key
								     $zone = $zone_drop_down[$keyToCheckzone]; 
								}

								$keyToCheckTypeLand = $value->type_of_land;
								if (array_key_exists($keyToCheckTypeLand, $type_of_land)) {
								    // Display the value corresponding to the key
								     $land_type = $type_of_land[$keyToCheckTypeLand]; 
								}

								// Key to find
	                            $keyToCheckdistrict =  $value->geo_district;

	                            // Check if the key is present in the array
	                            if (array_key_exists($keyToCheckdistrict, $district)) {
	                                // If the key exists, show its value
	                                $district_name = $district[$keyToCheckdistrict];
	                                
	                            }

	                            // Key to find
	                            $keyToChecktaluka =  $value->geo_taluka;

	                            // Check if the key is present in the array
	                            if (array_key_exists($keyToChecktaluka, $taluka)) {
	                                // If the key exists, show its value
	                                $taluka_name = $taluka[$keyToChecktaluka];
	                                
	                            }

	                            // Key to find
	                            $keyToCheckmake = $value->wtg_make;
	                          
	                                // Check if the key is present in the array
	                                if (array_key_exists($keyToCheckmake, $wtg_make)) {
	                                    // If the key exists, show its value
	                                    $wtg_make_name = $wtg_make[$keyToCheckmake];
	                                    
	                                }
			                ?>

				  			<tr>
				  				<div id="message_error_approval"></div>
				  				<?php echo $this->Form->input('geo_id',array("type" => "text",'label' => false,'type'=>'hidden','class'=>'form-control','placeholder'=>'','id'=>'geo_id_'.$counter,'value'=>$geo_application_data[$key]['id'])); ?>
						  		<td style="text-align:center;" > <?php echo $counter ?></td>

						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['wtg_location'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $land_type ?></label></td>

						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['land_survey_no'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['land_area'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['wtg_validity_date'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $district_name ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $taluka_name ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['geo_village'] ?></label></td>
						  		<td style="text-align:center;" >
						  			<?php if(!empty($geo_application_data[$key]['land_per_form'])){ 
						  				// Land_
										$path = WTG_PATH.$geo_application_data[$key]['id'].'/'.$geo_application_data[$key]['land_per_form'];?>
									&nbsp;&nbsp;
									<a class="" href="<?php echo URL_HTTP.'app-docs/land_per_form/'.encode($geo_application_data[$key]['id']); ?>" target="_blank"><i class="fa fa-eye"> View Uploaded Form</i></a>
						  			<?php } ?>
						  		</td>
						  		<td style="text-align:center;" ><label><?php echo $zone ?></label></td>
						  		
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['x_cordinate'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['y_cordinate'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $rlmm_name ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $wtg_make_name ?></label>
						  		<?php if(!empty($geo_application_data[$key]['wtg_file'])){ 
						  				$path = WTG_PATH.$geo_application_data[$key]['id'].'/'.$geo_application_data[$key]['wtg_file'];?>
									&nbsp;&nbsp;
									<a href="<?php echo URL_HTTP.'app-docs/wtg_file/'.encode($geo_application_data[$key]['id']); ?>" target="_blank"><i class="fa fa-eye"> View Wtg File</i></a>
						  			<?php } ?>
						  		</td>
						  		<td style="text-align:center;width: 20px;"><label><?php echo $geo_application_data[$key]['wtg_model'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['wtg_capacity'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['wtg_rotor_dimension'] ?></label></td>
						  		<td style="text-align:center;" ><label><?php echo $geo_application_data[$key]['wtg_hub_height'] ?></label></td>
						  		
						  		<td style="text-align:center;">
						  			<?php if($geo_application_data[$key]['payment_status'] == 1){?>
						  				<?php if($is_member == true){ ?>
						  					<?php if(($geo_application_data[$key]['payment_status'] == 1) && ($geo_application_data[$key]['approved'] == 1)){?>
						  							<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Approved </span>
						  							<?php if(isset($geo_application_data[$key]['wtg_verified']) && $geo_application_data[$key]['wtg_verified'] == 1){ ?>
						  								<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Verified </span>
						  						<?php } else{ ?>
						  							<input type="checkbox" id="<?php echo $geo_application_data[$key]['id']?>" class = 'verify' name="verify">
						  						<?php } ?>
						  						<?php } elseif(!empty($clash_text)){ ?>
						  						<span  onclick="javascript:show_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-danger bold" style="text-decoration: underline;"><?php echo $clash_text;?> </span>
							  						<?php if(isset($geo_application_data[$key]['wtg_verified']) && $geo_application_data[$key]['wtg_verified'] == 1){ ?>
							  								<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Verified </span>
							  						<?php } else{ ?>
							  							<input type="checkbox" id="<?php echo $geo_application_data[$key]['id']?>" class = 'verify' name="verify">
							  						<?php } ?>
						  						<?php } else{ ?>
						  							<?php /*echo $this->Form->input('gg', array('label' => false, 'class' => ' btn btn-secondary btn-sm  approvedata','style'=>'color:white;background-color: #307FE2;','name' => 'approve_'.$counter, 'type' => 'button',  'data-form-name'=>'geo_cordinate'.$counter)); */?>
						  							 <!-- Clash:307FE2 ,Internal Clash:f7f700,No clashing:4cc972,Reject:F3565D -->
						  							<?php echo $this->Form->input('Clash', array('label' => false, 'class' => ' btn  btn-sm  GeoClash','style'=>'color:white;background-color: #4285F4;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoClash", 'data-id'=>$geo_application_data[$key]['id'])); ?>
								  					<?php echo $this->Form->input('Internal Clash', array('label' => false, 'class' => ' btn  btn-sm  GeoClashInternal','style'=>'color:white;background-color: #FBBC05;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoClashInternal", 'data-id'=>$geo_application_data[$key]['id'])); ?>
								  					<?php echo $this->Form->input('No clashing', array('label' => false, 'class' => ' btn  btn-sm  GeoApprove','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoApprove", 'data-id'=>$geo_application_data[$key]['id'])); ?>
								  					<?php echo $this->Form->input('Reject', array('label' => false, 'class' => ' btn  btn-sm  GeoReject','style'=>'color:white;background-color: #EA4335;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoReject", 'data-id'=>$geo_application_data[$key]['id'])); ?>
								  					<?php if(isset($internal_clashed_docs->clashed_geo_id) && !empty($internal_clashed_docs->uploadfile)){ 
										  				$path = Internal_Clashed_PATH.$internal_clashed_docs->clashed_geo_id.'/'.$internal_clashed_docs->uploadfile;?>
													&nbsp;&nbsp;<br><br>
													<a href="<?php echo URL_HTTP.'app-docs/Internal_clashed_uploadfile/'.encode($internal_clashed_docs->clashed_geo_id); ?>" target="_blank"><i class="fa fa-eye"> View Internal Clashed Upload File</i></a>
										  			<?php } ?>
						  					<?php	} ?>
						  					
						  				<?php }elseif($is_member == false && $geo_application_data[$key]['approved'] == 1){ ?>
						  				 	
						  				 	<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Approved </span>
						  				 	<?php if(!empty($remainingday)){?>
												<br><span class="text-success">Remaining Days to Complete application <?php echo $remainingday?> </span>
						  				 	<?php	} ?>
						  				<?php }elseif($geo_application_data[$key]['developer_action_status'] == 3){ ?>
						  				 		<?php echo $this->Form->input('Accept', array('label' => false, 'class' => ' btn btn-secondary btn-sm  DeveloperAccept','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#DeveloperAccept", 'data-id'=>$geo_application_data[$key]['id'])); ?> 
								  				<?php echo $this->Form->input('Reject', array('label' => false, 'class' => ' btn btn-secondary btn-sm  DeveloperReject','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#DeveloperReject", 'data-id'=>$geo_application_data[$key]['id'])); ?>
						  				<?php }else{ 
							  				 	if(!empty($clash_text)){?>
							  				 		<?php if($clash_text == '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>'){
							  				 			  // echo $this->Form->input('Upload Docs', array('label' => false, 'class' => ' btn btn-secondary btn-sm  InternalClashed','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#InternalClashed", 'data-id'=>$geo_application_data[$key]['id']));
							  				 			  ?>
							  				 			  <span  onclick="javascript:show_internal_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-danger bold" style="text-decoration: underline;"><?php echo $clash_text;?> </span>
							  				 		<?php }else{?>
							  				 			<span  onclick="javascript:show_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-danger bold" style="text-decoration: underline;"><?php echo $clash_text;?> </span>
							  				 		<?php } ?>
							  				 		
							  				 	
							  				 	<?php }else{
							  				 		if($geo_application_data[$key]['query_raised'] == 1){?>
							  				 			<!-- <button type="button" class="btn btn-sm GeoRaisedQuery" style="color:black;background-color: #ccc;" data-toggle="modal" data-target="#GeoRaisedQuery" data-id='<?php echo $geo_application_data[$key]['id'] ?>'> Raised Query </button> -->
							  				 			<?php $ag_id = $geo_application_data[$key]['id'];?>
							  				 			<?php echo $this->Form->input('Raised Query', array('label' => false, 'class' => ' btn btn-secondary btn-sm  GeoRaisedQuery ','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'onclick'=>"javascript:show_query_modal($ag_id)")); ?>
							  				 	<?php	}
							  				 		echo 'Submitted';
							  				 	}
							  				} ?>
						  			<?php }elseif($geo_application_data[$key]['approved'] == 2){ ?>
						  				 	<i class="fa fa-times" aria-hidden="true"></i> <span  onclick="javascript:show_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-danger bold" title="<?php echo $value->reject_reason;?>">Rejected </span>
											
						  				<?php }else{ ?>
						  					<?php $ag_id = $geo_application_data[$key]['id'];?>
						  					<?php if($is_member == false){   ?> <?php echo $this->Form->input('Update', array('label' => false, 'class' => ' btn btn-secondary btn-sm ','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'onclick'=>"javascript:show_update_modal($ag_id)")); ?> <?php echo $this->form->input('Pay', array('type'=>'checkbox','value'=>$geo_location_charges,'class'=>'checkbox check','id'=>$geo_application_data[$key]['id']));	
						  					 }  
						  					} ?>


						  		</td>
						  	</tr><?php echo $this->Form->end(); ?>
					  	<?php $counter++; 
					  	}
					  	?>
					  
				  </tbody>	
				</table>
				
			</div>
		</div>
	</div>
	<?php if($is_member == true){?>
		<div class="row" style="border-radius:5px; padding:20px;">
			<table class="table custom_table lable_left">
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 m-2">
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">KMZ Upload Files</legend>
									<div class="col-md-12">
										<?php if((isset($ApplicationsDocs) && !empty($ApplicationsDocs))) { ?>
											<div class="row mt-xlg">
												<lable class="col-md-2">KMZ Files</lable>
													<div class="col-md-10">
														<?php
														$counter = 1;
															foreach ($ApplicationsDocs as $key => $value) 
															{
																if (empty($value['file_name']) || !$Couchdb->documentExist($id,$value['file_name'])) continue;
																?>
																<div Class="col-md-6">
																	
																	<!-- <a href="<?php //echo URL_HTTP.'app-docs/geo_cordinate_file/'.$id; ?>" target="_blank"><div style="text-align: justify;text-justify: inter-word;">
																	<i class="fa fa-download"></i> <span ><?php// echo $counter;?>. KMZ File : <?php //echo date('d-M-Y',strtotime($value['created'])); ?>  </span></div></a> -->

																	<a class="dropdown-item" href="/GeoApplications/download/<?php echo $value['file_name']; ?>"><i class="fa fa-download"></i> <span ><?php echo $counter;?>. KMZ File : <?php echo date('d-M-Y',strtotime($value['created'])); ?>  </span></a>
																</div>
														<?php $counter++; }  ?> 
													</div>
											</div>
										<?php } ?>
								</fieldset>
							</div>
							<div class="col-md-6 m-2">
								
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">Payment For Geo Location Verification</legend>
									
									
									<?php if((isset($Geo_application_paymet_log) && !empty($Geo_application_paymet_log))) { ?>
											<div class="row mt-xlg">
												<lable class="col-md-2">Payment Receipts</lable>
													<div class="col-md-10">
														<?php
														$counter = 1;
															foreach ($Geo_application_paymet_log as $key => $value) 
															{?>
																<div Class="col-md-3">
																	
																	<a href="/GeoApplications/downloadGeoApplicationPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
																	<div style="text-align: justify;text-justify: inter-word;">
																	<i class="fa fa-download"></i> <span >Download Receipt <?php echo $key+1 ?></span></div> </a>
																</div>
														<?php $counter++; }  ?> 
													</div>
											</div>
									<?php } ?>
											
								</fieldset>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php }?>
	<?php if($is_member == false){?>
		<div class="row" style="border-radius:5px; padding:20px;">
			<table class="table custom_table lable_left">
				<tbody>
					<tr>
						<td>
							<!-- <div class="col-md-6 m-2">
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">KMZ Upload Files</legend>
									<div class="col-md-12">
										<?php
											echo $this->Form->create('GeoFileForm',['name'=>'GeoFileForm','id'=>'GeoFileForm','type' => 'file']); 
											echo $this->Form->input('GeoFileForm_application_id',['label' => false,'type'=>'hidden','value'=>$id]);
											echo $this->Form->input('application_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
										?>
										<div class="row mt-xlg">
											<div class="col-md-12">
												<lable class="col-md-3">Upload KMZ file <br><span class="small" >[Upload KMZ of size upto 1024 KB]</span></lable>
											<div class="col-md-6">
												<?php echo $this->Form->input('geo_cordinate_file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload','id'=>'GeoFile')); ?>
											</div>
											<div class="col-md-3 text-align-center">
												<?php echo $this->Form->input('Submit',['id'=>'GeoFile_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary btn-default GeoFile_btn','data-form-name'=>'GeoFileForm']); ?>
												
											</div>
											
											<div class="row" style="margin-right: 2px;margin-left: -4px;">
												<div class="col-md-12"  id="GeoFile-file-errors"></div>
											</div>
											</div>
											<div id="child-select-error" class="has-error"></div>
										</div>
										
										<?php
										echo $this->Form->end(); ?>
										<?php if((isset($ApplicationsDocs) && !empty($ApplicationsDocs))) { ?>
											<div class="row mt-xlg">
												<lable class="col-md-2">KMZ Files</lable>
													<div class="col-md-10">
														<?php
														$counter = 1;
														
															foreach ($ApplicationsDocs as $key => $value) 
															{
																// if (empty($value['file_name']) || !$Couchdb->documentExist($id,$value['file_name'])) continue;
																?>
																<div Class="col-md-6">
																	
																	<a class="dropdown-item" href="/GeoApplications/download/<?php echo $value['file_name']; ?>"><i class="fa fa-download"></i> <span ><?php echo $counter;?>. KMZ File : <?php echo date('d-M-Y',strtotime($value['created'])); ?>  </span></a>
																	
																</div>
														<?php $counter++; }  ?> 
													</div>
											</div>
										<?php } ?>
								</fieldset>
							</div> -->
							<div class="col-md-6 m-2">
								<?php echo $this->Form->create('PaymentForm',['type'=>'file','name'=>'PaymentForm','id'=>'PaymentForm','class'=>'PaymentForm','url' => '/GeoPayment/make-payment/'.$id,'autocomplete'=>'off']);
								echo $this->Form->input('Paymentapplication_id',['label' => false,'type'=>'hidden','value'=>$id]);
									echo $this->Form->input('Paymentapplication_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
								?>
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">Payment For Geo Location Verification</legend>
									
									<div class="row mt-xlg">
										<?php $geo_location_tax = isset($applicationCategory->geo_location_tax) ? $applicationCategory->geo_location_tax : 0;
											$geo_location_tds = isset($applicationCategory->application_tds_percentage) ? $applicationCategory->application_tds_percentage : 0;
										?>
										<div class="col-md-12">
											
											<div class="row mt-xlg">
												<div class="col-md-4">
													<label>Cordinate Payment Fees</label>
													<?php echo $this->Form->input('geo_payment', array('label' => false,'type'=>'text','class'=>'form-control','readonly'=>'readonly','id'=>'geo_payment','placeholder'=>'Cordinate Payment')); ?>
													<?php echo $this->Form->input('geo_id', array('label' => false,'type'=>'hidden','class'=>'form-control','readonly'=>'readonly','id'=>'geo_id')); ?>
												</div>
												<div class="col-md-4">
													<label>GST at <?php echo $geo_location_tax;?>% (in Rs.)</label>
													<?php echo $this->Form->input('gst_fees', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'GST at 18%','id'=>'gst_fees')); ?>
												</div>
												<div class="col-md-4">
													<label>Total (in Rs.)</label>
													<?php echo $this->Form->input('geo_total_fee', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'Total','id'=>'geo_total_fee')); ?>
												</div>
											</div>
											
										</div>
										<div class="col-md-12">
											<div class="row showtds" style="display:none;" >
												<div class="form-group">
													<div class="col-md-4">
														<label>TDS at <?php echo $geo_location_tds;?>% (in Rs.)</label>
														<?php echo $this->Form->input('geo_location_tds', array('label' => false,'type'=>'text','class'=>'form-control','readonly'=>'readonly','id'=>'geo_location_tds','placeholder'=>'TDS')); ?>
													</div>
													<div class="col-md-4">
														<label>Net Payable</label>
														<?php echo $this->Form->input('net_payable', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'Net Payable','id'=>'net_payable')); ?>
													</div>
													
												</div>
											</div>
											<div class="row col-md-12">
												<div class="col-md-10">
														<?php echo $this->Form->input('liable_tds', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'','placeholder'=>'','style'=>'margin-left: -48px !important;')); ?>
															<span class="textCheckeboxLeft" style="margin-left:283px !important;">Are you liable to deduct TDS as per Income Tax Act?</span>
												</div>
												
											</div>
											<div class="row">
												<div class="form-group">
													<div class="col-md-12">
														<?php echo $this->Form->input('terms_agree', array('label' => false,'type'=>'checkbox','class'=>'terms_agree','placeholder'=>'','style'=>'margin-left: -131px !important;','value'=>'1','id'=>'terms_agree','disabled'=>'disabled')); ?>
															<span class="textCheckeboxLeft" style="margin-left:51px !important;">Are you Agree to <a href="javascript:;" data-toggle="modal" data-target="#agree_popup" class="agree_popup" ><strong>Terms and Conditions</strong></a></span>?
													</div>
												</div>
											</div>
											<div class="row mt-xlg">
												<div class="col-md-12 text-align-center">
													
													<?php echo $this->Form->input('Payment',['type'=>'submit','label'=>false,'class'=>'btn btn-primary btn-default ','data-form-name'=>'PaymentForm']); ?>
												</div>
											</div>
										</div>
										
									</div>
									
									<?php if((isset($Geo_application_paymet_log) && !empty($Geo_application_paymet_log))) { ?>
											<div class="row mt-xlg">
												<lable class="col-md-2">Payment Receipts</lable>
													<div class="col-md-10">
														<?php
														$counter = 1;
															foreach ($Geo_application_paymet_log as $key => $value) 
															{?>
																<div Class="col-md-3">
																	
																	<a href="/GeoApplications/downloadGeoApplicationPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
																	<div style="text-align: justify;text-justify: inter-word;">
																	<i class="fa fa-download"></i> <span >Download Receipt <?php echo $key+1 ?></span></div> </a>
																	
																</div>
														<?php $counter++; }  ?> 
													</div>
											</div>
									<?php } ?>
											
								</fieldset>
								<?php echo $this->Form->end(); ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php }?>
  	<div class="row col-md-12">
		<div class="col-md-3">
			<?php echo $this->Html->link('Back',['controller'=>'','action' => 'applications-list'],['class'=>'next btn btn-primary btn-md  cbtnsendmsg btn-default']); ?>
		</div>
		<?php if($is_member == true){?>
		<div class="col-md-3">

			<?php echo $this->Form->input('Generate WTG Verificatin PDF', array('label' => false, 'class' => ' btn  btn-sm  GeoVerify','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoVerify")); ?>
		</div>
	<?php }?>
	</div>
	<div id="GeoRaisedQuery" class="modal fade" role="dialog">
		<div class="modal-dialog">					<?php echo $this->Form->input('GeoRaisedQuery_application_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]); ?>

			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Geo Location Data</h4>
				</div>
				
				<div class="modal-body">
					<?php
					$counter = 0;
					echo $this->Form->create('GeoRaisedQueryForm',['name'=>'GeoRaisedQueryForm'.$counter,'id'=>'GeoRaisedQueryForm'.$counter,'type' => 'file']); 

					?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoRaisedQuery_application_id',['id'=>'GeoRaisedQuery_application_id','label' => true,'type'=>'hidden','value'=>$Applications->id]); ?>
					<?php echo $this->Form->input('geo_id',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_geo_id_'.$counter,'placeholder'=>'Geo id','type'=>'hidden')); ?>
						<div class="row">
							<div class="col-md-12">
								<lable>Raised Query </lable>
								<?php echo $this->Form->textarea('query_raised_remark',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'query_raised_remark','disabled'=>'disabled')); ?>
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<lable>Type of Land </lable>
								<?php echo $this->Form->select('type_of_land', $type_of_land, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'q_type_of_land_'.$counter, 'placeholder'=>'Type of Land')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Land Survey No. </lable>
								<?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_land_survey_no_'.$counter, 'placeholder'=>'Land Survey No.')); ?>
								
							</div>
						</div>
						
						
						<div class="row">
							
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>RLMM	 </lable>
								<?php echo $this->Form->select('rlmm', $rlmm, array('label' => false, 'class' => 'form-control','id' => 'q_rlmm'.$counter, 'onChange' => 'javascript:rlmmchange_q('.$counter.')')); ?>
							</div>
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>RLMM Validity </lable>
								<?php echo $this->Form->input('wtg_validity_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_validity_date','id'=>'q_wtg_validity_date_'.$counter,'placeholder'=>'Validity Date','autocomplete'=>'off')); ?>
								
							</div>
							<!-- Land -->
							<div class="col-md-6 " style="margin-top:10px;margin-bottom:10px;">
								<lable>Consent Letter from Landowner* </lable>&nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
								<?php echo $this->Form->input('land_per_form', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Consent Letter from Landowner','id'=>'q_land_per_form_'.$counter)); ?>
								 <a href="/undertaking_geo_Bahedhari_cum_Sahamati.docx"  class="private" style="text-decoration: underline;display: none;"><strong>Please upload consent letter with 7/12 document from the landowner[Download Concent Letter Format]</strong></a> 

								  <strong class="forest" style="text-decoration: underline;display: none;">[Upload NOC]</strong>
								  <strong class="govt" style="text-decoration: underline;display: none;">Please upload acknowledgement letter of the application submitted to collector or recommendation letter by GEDA for availing Government Land [Acknowledgement from collector office]</strong>
								  <strong class="geda" style="text-decoration: underline;display: none;">[Recommended from GEDA]</strong>
								<!-- <span id= 'q_land_per_form' class="q_land_per_form"></span> -->
								<div class="col-md-6 q_land_per_form">
									 
								</div>
							</div>
						</div>
						<div class="row Y_data">
							<div class="col-md-3">
								<lable>WTG Make </lable>
								<?php echo $this->Form->select('wtg_make', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wtg_make_cls', 'empty' => '- Select WTG Make-', 'id' => 'q_wtg_make_'.$counter, 'onChange' => 'javascript:changeMake_q('.$counter.')')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>WTG Model </lable>
								<?php echo $this->Form->select('wtg_model', [], array('label' => false, 'class' => 'rfibox wtg_model_cls', 'empty' => '- Select WTG Model-', 'id' => 'q_wtg_model_'.$counter, 'onChange' => 'javascript:changemodel_q('.$counter.')')); ?> 
								
							</div>
							<div class="col-md-3">
								<lable>WTG Capacity </lable>
								<?php echo $this->Form->select('wtg_capacity', [], array('label' => false, 'class' => 'rfibox wtg_capacity_cls', 'empty' => '- Select WTG Capacity-', 'id' => 'q_wtg_capacity_'.$counter,'onChange' => 'javascript:changeWindRowCapacity(this)')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>WTG Rotor Dimension </lable>
								<?php echo $this->Form->select('wtg_rotor_dimension', [], array('label' => false, 'class' => 'rfibox wtg_rotor_dimension_cls', 'empty' => '- Select WTG Rotor Dimension-', 'id' => 'q_wtg_rotor_dimension_'.$counter)); ?>
								
							</div>
						</div>
						<div class="row Y_data">
							
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>WTG Hub Height </lable>
								<?php echo $this->Form->select('wtg_hub_height', [], array('label' => false, 'class' => 'rfibox wtg_hub_height_cls', 'empty' => '- Select WTG Hub Height-', 'id' => 'q_wtg_hub_height_'.$counter)); ?>
								
							</div>

						</div>
						<div class="row N_data" style="display: none;">
							<div class="col-md-3">
								<lable>WTG Make </lable>
								<?php echo $this->Form->input('wtg_make_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_wtg_make_n_'.$counter, 'placeholder'=>'WTG Make')); ?> 
							</div>
							
							<div class="col-md-3">
								<lable>WTG Model </lable>
								<?php echo $this->Form->input('wtg_model_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_wtg_model_n_'.$counter, 'placeholder'=>'WTG Model')); ?>
							</div>
							<div class="col-md-3">
								<lable>WTG Capacity </lable>
								<?php echo $this->Form->input('wtg_capacity_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_wtg_capacity_n_'.$counter, 'placeholder'=>'WTG Capacity')); ?> 
							</div>
							<div class="col-md-3">
								<lable>WTG Rotor Dimension </lable>
								<?php echo $this->Form->input('wtg_rotor_dimension_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_wtg_rotor_dimension_n_'.$counter, 'placeholder'=>'WTG Rotor Dimension')); ?>  	
							</div>

						</div>
						<div class="row N_data" style="display: none;">
							<div class="col-md-3">
								<lable>WTG Hub Height </lable>
								<?php echo $this->Form->input('wtg_hub_height_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'q_wtg_hub_height_n_'.$counter, 'placeholder'=>'WTG Hub Height')); ?>  
							</div>
							
							<div class="col-md-9">
								<lable>WTG Technical Specification</lable> &nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
								<?php echo $this->Form->input('wtg_file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload File','id'=>'q_wtg_file_'.$counter)); ?>
								<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="q_wtg_file_0-file-errors"></div>
									</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'GeoRaisedQuery_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoRaisedQuery_btn button-right','data-form-name'=>'GeoRaisedQueryForm'.$counter]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="AddOfflineApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Offline Geo Location Data</h4>
				</div>
				
				<div class="modal-body">
					<?php
					$counter = 0;
					echo $this->Form->create('AddOfflineApplicationForm',['name'=>'AddOfflineApplicationForm'.$counter,'id'=>'AddOfflineApplicationForm'.$counter,'type' => 'file']); 

					?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('AddOfflineApplication_application_id',['id'=>'AddOfflineApplication_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('AddOfflineApplication_application_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]); ?>
						<div class="row">
							<div class="col-md-4">
								<lable>Registration No </lable>
								<?php echo $this->Form->input('app_reg_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'app_reg_no_off','placeholder'=>'Registration No','readonly')); ?>
								
							</div>
							<div class="col-md-4">
								<lable>Developer Name</lable>
								<?php echo $this->Form->select('installer_name', $Installers, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'installer_name_off_'.$counter, 'placeholder'=>'Developer Name')); ?>
								
							</div>
							<div class="col-md-4">
								<lable>WTG Location</lable>
								<?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_location_off_'.$counter, 'placeholder'=>'WTG Location')); ?>
								
							</div>
						</div>
						<div class="row">

							<div class="col-md-4">
								<lable>District </lable>
								<?php echo $this->Form->select('district', $district, array('label' => false, 'class' => 'form-control','id' => 'district_off_'.$counter, 'empty' => '-Select District-', 'placeholder'=>'District' ,'onChange'=>'javascript:getTalukaFromDistrict_offline('.$counter.');')); ?>
							</div>
							<div class="col-md-4">
								<lable>Taluka </lable>
								<?php echo $this->Form->select('taluka',array(), array('label' => false, 'class' => 'form-control','id' => 'taluka_off_'.$counter, 'empty' => '-Select Taluka-', 'placeholder'=>'Taluka' )); ?>
							</div>
							
							<div class="col-md-4">
								<lable>Village </lable>
								<?php echo $this->Form->input('village',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'village_off_'.$counter, 'placeholder'=>'Village')); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<lable>UTM Zone </lable>
								<?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control', 'id' => 'zone_off_'.$counter)); ?>
							</div>
							<div class="col-md-4">
								<lable>UTM Easting </lable>
								<?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateEastingDecimalInput(this)','id' => 'x_cordinate_off_'.$counter, 'placeholder'=>'UTM Easting')); ?>
							</div>
							<div class="col-md-4">
								<lable>UTM Northing</lable>
								<?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateNorthingDecimalInput(this)','id' => 'y_cordinate_off_'.$counter, 'placeholder'=>'UTM Northing')); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4" style="margin-top:10px;margin-bottom:10px;">
								<lable>Offline Approved Date </lable>
								<?php echo $this->Form->input('offline_approved_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline offline_approved_date','id'=>'offline_approved_date'.$counter,'placeholder'=>'Approved Date','autocomplete'=>'off')); ?>
								
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'AddOfflineApplication_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary AddOfflineApplication_btn button-right','data-form-name'=>'AddOfflineApplicationForm'.$counter]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="AddApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Geo Location Data</h4>
				</div>
				
				<div class="modal-body">
					<?php
					$counter = 0;
					echo $this->Form->create('AddApplicationForm',['name'=>'AddApplicationForm'.$counter,'id'=>'AddApplicationForm'.$counter,'type' => 'file']); 

					?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('AddApplication_application_id',['id'=>'AddApplication_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('AddApplication_application_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]); ?>
						<div class="row">
							<div class="col-md-3">
								<lable>WTG Location </lable>
								<?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_location_'.$counter,'placeholder'=>'WTG Location')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Type of Land </lable>
								<?php echo $this->Form->select('type_of_land', $type_of_land, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_land_'.$counter, 'placeholder'=>'Type of Land')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Land Survey No. </lable>
								<?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'land_survey_no_'.$counter, 'placeholder'=>'Land Survey No.')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Land Area in sq. mtr</lable>
								<?php echo $this->Form->input('land_area',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'land_area_'.$counter, 'placeholder'=>'Land Area',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
								
							</div>
						</div>
						<div class="row">

							<div class="col-md-3">
								<lable>District </lable>
								<?php echo $this->Form->select('geo_district', $district, array('label' => false, 'class' => 'form-control','id' => 'geo_district_'.$counter, 'empty' => '-Select District-', 'placeholder'=>'District' ,'onChange'=>'javascript:getTalukaFromDistrict('.$counter.');')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Taluka </lable>
								<?php echo $this->Form->select('geo_taluka',array(), array('label' => false, 'class' => 'form-control','id' => 'geo_taluka_'.$counter, 'empty' => '-Select Taluka-', 'placeholder'=>'Taluka' )); ?>

								
							</div>
							
							<div class="col-md-3">
								<lable>Village </lable>

								<?php echo $this->Form->input('geo_village',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'geo_village_'.$counter, 'placeholder'=>'Village')); ?>
								
							</div>
							
							
						</div>
						<div class="row">
							<div class="col-md-3">
								<lable>UTM Zone </lable>
								<?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control', 'id' => 'zone'.$counter)); ?>
								
							</div>
							<div class="col-md-3">
								<lable>UTM Easting </lable>
								<?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateEastingDecimalInput(this)','id' => 'x_cordinate_'.$counter, 'placeholder'=>'UTM Easting')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>UTM Northing</lable>
								<?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateNorthingDecimalInput(this)','id' => 'y_cordinate_'.$counter, 'placeholder'=>'UTM Northing')); ?>
								
							</div>
							
						</div>
						<div class="row">
							
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>RLMM	 </lable>
								<?php echo $this->Form->select('rlmm', $rlmm, array('label' => false, 'class' => 'form-control','id' => 'rlmm'.$counter, 'onChange' => 'javascript:rlmmchange('.$counter.')')); ?>
							</div>
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>RLMM Validity </lable>
								<?php echo $this->Form->input('wtg_validity_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_validity_date','id'=>'wtg_validity_date_'.$counter,'placeholder'=>'Validity Date','autocomplete'=>'off')); ?>
								
							</div>
							<!-- Land -->
							<div class="col-md-6 " style="margin-top:10px;margin-bottom:10px;">
								<lable>Consent Letter from Landowner* </lable>&nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
								<?php echo $this->Form->input('land_per_form', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Consent Letter from Landowner','id'=>'land_per_form_'.$counter)); ?>
								 <a href="/undertaking_geo_Bahedhari_cum_Sahamati.docx"  class="private" style="text-decoration: underline;display: none;"><strong>Please upload consent letter with 7/12 document from the landowner[Download Concent Letter Format]</strong></a> 

								  <strong class="forest" style="text-decoration: underline;display: none;">[Upload NOC]</strong>
								  <strong class="govt" style="text-decoration: underline;display: none;">Please upload acknowledgement letter of the application submitted to collector or recommendation letter by GEDA for availing Government Land [Acknowledgement from collector office]</strong>
								  <strong class="geda" style="text-decoration: underline;display: none;">[Recommended from GEDA]</strong>
								
							</div>
						</div>
						<div class="row Y_data">
							<div class="col-md-3">
								<lable>WTG Make </lable>
								<?php echo $this->Form->select('wtg_make', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wtg_make_cls', 'empty' => '- Select WTG Make-', 'id' => 'wtg_make_'.$counter, 'onChange' => 'javascript:changeMake('.$counter.')')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>WTG Model </lable>
								<?php echo $this->Form->select('wtg_model', [], array('label' => false, 'class' => 'rfibox wtg_model_cls', 'empty' => '- Select WTG Model-', 'id' => 'wtg_model_'.$counter, 'onChange' => 'javascript:changemodel('.$counter.')')); ?> 
								
							</div>
							<div class="col-md-3">
								<lable>WTG Capacity </lable>
								<?php echo $this->Form->select('wtg_capacity', [], array('label' => false, 'class' => 'rfibox wtg_capacity_cls', 'empty' => '- Select WTG Capacity-', 'id' => 'wtg_capacity_'.$counter,'onChange' => 'javascript:changeWindRowCapacity(this)')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>WTG Rotor Dimension </lable>
								<?php echo $this->Form->select('wtg_rotor_dimension', [], array('label' => false, 'class' => 'rfibox wtg_rotor_dimension_cls', 'empty' => '- Select WTG Rotor Dimension-', 'id' => 'wtg_rotor_dimension_'.$counter)); ?>
								
							</div>
						</div>
						<div class="row Y_data">
							
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>WTG Hub Height </lable>
								<?php echo $this->Form->select('wtg_hub_height', [], array('label' => false, 'class' => 'rfibox wtg_hub_height_cls', 'empty' => '- Select WTG Hub Height-', 'id' => 'wtg_hub_height_'.$counter)); ?>
								
							</div>

						</div>
						<div class="row N_data" style="display: none;">
							<div class="col-md-3">
								<lable>WTG Make </lable>
								<?php echo $this->Form->input('wtg_make_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_make_n_'.$counter, 'placeholder'=>'WTG Make')); ?> 
							</div>
							
							<div class="col-md-3">
								<lable>WTG Model </lable>
								<?php echo $this->Form->input('wtg_model_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_model_n_'.$counter, 'placeholder'=>'WTG Model')); ?>
							</div>
							<div class="col-md-3">
								<lable>WTG Capacity </lable>
								<?php echo $this->Form->input('wtg_capacity_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_capacity_n_'.$counter, 'placeholder'=>'WTG Capacity')); ?> 
							</div>
							<div class="col-md-3">
								<lable>WTG Rotor Dimension </lable>
								<?php echo $this->Form->input('wtg_rotor_dimension_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_rotor_dimension_n_'.$counter, 'placeholder'=>'WTG Rotor Dimension')); ?>  	
							</div>

						</div>
						<div class="row N_data" style="display: none;">
							<div class="col-md-3">
								<lable>WTG Hub Height </lable>
								<?php echo $this->Form->input('wtg_hub_height_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_hub_height_n_'.$counter, 'placeholder'=>'WTG Hub Height')); ?>  
							</div>
							
							<div class="col-md-9">
								<lable>WTG Technical Specification</lable> &nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
								<?php echo $this->Form->input('wtg_file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload File','id'=>'wtg_file_'.$counter)); ?>
								<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="wtg_file_0-file-errors"></div>
									</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'AddApplication_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary AddApplication_btn button-right','data-form-name'=>'AddApplicationForm'.$counter]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="UpdateApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Geo Location Data</h4>
				</div>
				
				<div class="modal-body">
					<?php
					$counter = 0;
					echo $this->Form->create('UpdateApplicationForm',['name'=>'UpdateApplicationForm'.$counter,'id'=>'UpdateApplicationForm'.$counter,'type' => 'file']); 

					?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('UpdateApplication_application_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]); ?>
					<?php echo $this->Form->input('UpdateApplication_application_id',['id'=>'UpdateApplication_application_id','label' => true,'type'=>'hidden','value'=>$Applications->id]); ?>
					<?php echo $this->Form->input('geo_id',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_geo_id_'.$counter,'placeholder'=>'Geo id','type'=>'hidden')); ?>
						<div class="row">
							<div class="col-md-3">
								<lable>WTG Location </lable>
								<?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_location_'.$counter,'placeholder'=>'WTG Location')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Type of Land </lable>
								<?php echo $this->Form->select('type_of_land', $type_of_land, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'up_type_of_land_'.$counter, 'placeholder'=>'Type of Land')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Land Survey No. </lable>
								<?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_land_survey_no_'.$counter, 'placeholder'=>'Land Survey No.')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Land Area in sq. mtr</lable>
								<?php echo $this->Form->input('land_area',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_land_area_'.$counter, 'placeholder'=>'Land Area',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
								
							</div>
						</div>
						<div class="row">

							<div class="col-md-3">
								<lable>District </lable>
								<?php echo $this->Form->select('geo_district', $district, array('label' => false, 'class' => 'form-control','id' => 'up_geo_district_'.$counter, 'empty' => '-Select District-', 'placeholder'=>'District' ,'onChange'=>'javascript:getTalukaFromDistrict_up('.$counter.');')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>Taluka </lable>
								<?php echo $this->Form->select('geo_taluka',array(), array('label' => false, 'class' => 'form-control','id' => 'up_geo_taluka_'.$counter, 'empty' => '-Select Taluka-', 'placeholder'=>'Taluka' )); ?>

								
							</div>
							
							<div class="col-md-3">
								<lable>Village </lable>
								<?php echo $this->Form->input('geo_village',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'up_geo_village_'.$counter, 'placeholder'=>'Village')); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<lable>UTM Zone </lable>
								<?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control', 'id' => 'up_zone_'.$counter)); ?>
								
							</div>
							<div class="col-md-3">
								<lable>UTM Easting </lable>
								<?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateEastingDecimalInput(this)','id' => 'up_x_cordinate_'.$counter, 'placeholder'=>'UTM Easting')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>UTM Northing</lable>
								<?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateNorthingDecimalInput(this)','id' => 'up_y_cordinate_'.$counter, 'placeholder'=>'UTM Northing')); ?>
							</div>
						</div>
						<div class="row">
							
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>RLMM	 </lable>
								<?php echo $this->Form->select('rlmm', $rlmm, array('label' => false, 'class' => 'form-control','id' => 'up_rlmm'.$counter, 'onChange' => 'javascript:rlmmchange_up('.$counter.')')); ?>
							</div>
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>RLMM Validity </lable>
								<?php echo $this->Form->input('wtg_validity_date', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_validity_date','id'=>'up_wtg_validity_date_'.$counter,'placeholder'=>'Validity Date','autocomplete'=>'off')); ?>
								
							</div>
							<!-- Land -->
							<div class="col-md-6 " style="margin-top:10px;margin-bottom:10px;">
								<lable>Consent Letter from Landowner* </lable>&nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
								<?php echo $this->Form->input('land_per_form', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Consent Letter from Landowner','id'=>'up_land_per_form_'.$counter)); ?>
								 <a href="/undertaking_geo_Bahedhari_cum_Sahamati.docx"  class="private" style="text-decoration: underline;display: none;"><strong>Please upload consent letter with 7/12 document from the landowner[Download Concent Letter Format]</strong></a> 

								  <strong class="forest" style="text-decoration: underline;display: none;">[Upload NOC]</strong>
								  <strong class="govt" style="text-decoration: underline;display: none;">Please upload acknowledgement letter of the application submitted to collector or recommendation letter by GEDA for availing Government Land [Acknowledgement from collector office]</strong>
								  <strong class="geda" style="text-decoration: underline;display: none;">[Recommended from GEDA]</strong>
								
							</div>
						</div>
						<div class="row Y_data">
							<div class="col-md-3">
								<lable>WTG Make </lable>
								<?php echo $this->Form->select('wtg_make', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wtg_make_cls', 'empty' => '- Select WTG Make-', 'id' => 'up_wtg_make_'.$counter, 'onChange' => 'javascript:changeMake_up('.$counter.')')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>WTG Model </lable>
								<?php echo $this->Form->select('wtg_model', [], array('label' => false, 'class' => 'rfibox wtg_model_cls', 'empty' => '- Select WTG Model-', 'id' => 'up_wtg_model_'.$counter, 'onChange' => 'javascript:changemodel_up('.$counter.')')); ?> 
								
							</div>
							<div class="col-md-3">
								<lable>WTG Capacity </lable>
								<?php echo $this->Form->select('wtg_capacity', [], array('label' => false, 'class' => 'rfibox wtg_capacity_cls', 'empty' => '- Select WTG Capacity-', 'id' => 'up_wtg_capacity_'.$counter,'onChange' => 'javascript:changeWindRowCapacity(this)')); ?>
								
							</div>
							<div class="col-md-3">
								<lable>WTG Rotor Dimension </lable>
								<?php echo $this->Form->select('wtg_rotor_dimension', [], array('label' => false, 'class' => 'rfibox wtg_rotor_dimension_cls', 'empty' => '- Select WTG Rotor Dimension-', 'id' => 'up_wtg_rotor_dimension_'.$counter)); ?>
								
							</div>
						</div>
						<div class="row Y_data">
							
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable>WTG Hub Height </lable>
								<?php echo $this->Form->select('wtg_hub_height', [], array('label' => false, 'class' => 'rfibox wtg_hub_height_cls', 'empty' => '- Select WTG Hub Height-', 'id' => 'up_wtg_hub_height_'.$counter)); ?>
								
							</div>

						</div>
						<div class="row N_data" style="display: none;">
							<div class="col-md-3">
								<lable>WTG Make </lable>
								<?php echo $this->Form->input('wtg_make_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_make_n_'.$counter, 'placeholder'=>'WTG Make')); ?> 
							</div>
							
							<div class="col-md-3">
								<lable>WTG Model </lable>
								<?php echo $this->Form->input('wtg_model_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_model_n_'.$counter, 'placeholder'=>'WTG Model')); ?>
							</div>
							<div class="col-md-3">
								<lable>WTG Capacity </lable>
								<?php echo $this->Form->input('wtg_capacity_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_capacity_n_'.$counter, 'placeholder'=>'WTG Capacity')); ?> 
							</div>
							<div class="col-md-3">
								<lable>WTG Rotor Dimension </lable>
								<?php echo $this->Form->input('wtg_rotor_dimension_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_rotor_dimension_n_'.$counter, 'placeholder'=>'WTG Rotor Dimension')); ?>  	
							</div>

						</div>
						<div class="row N_data" style="display: none;">
							<div class="col-md-3">
								<lable>WTG Hub Height </lable>
								<?php echo $this->Form->input('wtg_hub_height_n',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_hub_height_n_'.$counter, 'placeholder'=>'WTG Hub Height')); ?>  
							</div>
							
							<div class="col-md-9">
								<lable>WTG Technical Specification</lable> &nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
								<?php echo $this->Form->input('wtg_file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload File','id'=>'up_wtg_file_'.$counter)); ?>
								<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="up_wtg_file_0-file-errors"></div>
									</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'UpdateApplication_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary UpdateApplication_btn button-right','data-form-name'=>'UpdateApplicationForm'.$counter]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="GeoClash" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Clashed Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoClashForm',['name'=>'GeoClashForm','id'=>'GeoClashForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoClash_geo_id',['id'=>'GeoClash_geo_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
								<lable>Geo Clashed  </lable>
								<?php echo $this->Form->select('approved_geo_id', $LocationList, array('label' => false, 'class' => 'form-control chosen-select','multiple' => 'multiple','empty' =>'-Select Location-', 'id' => 'approved_clashed_geo_id')); ?>
							</div>
							<div class="col-md-12" style="margin-top: 20px;">
								<?php echo $this->Form->textarea('clashed_remark', array('label' => false,'class'=>'form-control','placeholder'=>'Clashed Remark','id'=>'clashed_remark')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'GeoClash_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoClash_btn button-right','data-form-name'=>'GeoClashForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoClashInternal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Clashed Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoClashInternalForm',['name'=>'GeoClashInternalForm','id'=>'GeoClashInternalForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoClashInternal_geo_id',['id'=>'GeoClashInternal_geo_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
								<lable>Geo Clashed  </lable>
								<?php echo $this->Form->select('approved_geo_id', $LocationList_internal, array('label' => false, 'class' => 'form-control chosen-select','multiple' => 'multiple','empty' =>'-Select Location-', 'id' => 'approved_geo_id')); ?>
							</div>
							<div class="col-md-12" style="margin-top: 20px;">
								<?php echo $this->Form->textarea('internal_clashed_remark', array('label' => false,'class'=>'form-control','placeholder'=>'Internal Clashed Remark ','id'=>'internal_clashed_remark')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'GeoClashInternal_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoClashInternal_btn button-right','data-form-name'=>'GeoClashInternalForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoApprove" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to Approve?</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoApproveForm',['name'=>'GeoApproveForm','id'=>'GeoApproveForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoApprove_application_id',['id'=>'GeoApprove_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<?php echo $this->Form->input('Submit',['id'=>'GeoApprove_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoApprove_btn button-right','data-form-name'=>'GeoApproveForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoReject" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reject Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoRejectForm',['name'=>'GeoRejectForm','id'=>'GeoRejectForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoReject_application_id',['id'=>'GeoReject_application_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
								<lable>Reason to Reject </lable>
								
								<?php echo $this->Form->textarea('reject_reason', array('label' => false,'class'=>'form-control','placeholder'=>'Reason to Reject','id'=>'reject_reason')); ?>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-12">
								<?php echo $this->Form->input('Submit',['id'=>'GeoReject_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoReject_btn button-right','data-form-name'=>'GeoRejectForm']); ?>
							</div>
						</div>
					</div>
					

					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoReasonReject" class="modal" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reason of Rejected Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<div class="row">
							<div class="col-md-12">
								<lable>Reason to Reject </lable>
								
							
								
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="GeoReasonClashedData" class="modal" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reason of Clashed Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<div class="row">
							<div class="col-md-12">
								<lable>Reason to Clashed </lable>
								
							
							 
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="agree_popup" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<!-- Income Tax TDS  -->
					<h4 class="modal-title">Terms and Conditions</h4>
				</div>
				<div class="modal-body">
					Amount Deducted as TDS should be deposited with government and e-tds return should be filed in prescribed time limit.
					<div id="message_error"></div>
					<br>
					If failed to do so, penalty of equal amount of TDS will be charged.
					<br><br>  Complete application in all respects will be considered as date of application. In case of query raised the date of application will be renewed as last date of modification<br><br> 
					<div class="row">
						<div class="col-md-12">
						<?php echo $this->Form->input('I Agree',['type'=>'button','label'=>false,'class'=>'btn btn-primary button-right','onclick'=>'agreeClick();']); ?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<div id="DeveloperAccept" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to Accept ?</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('DeveloperAcceptForm',['name'=>'DeveloperAcceptForm','id'=>'DeveloperAcceptForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('DeveloperAccept_application_id',['id'=>'DeveloperAccept_application_id','label' => true,'type'=>'hidden']); ?>

						
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<?php echo $this->Form->input('Submit',['id'=>'DeveloperAccept_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary DeveloperAccept_btn button-right','data-form-name'=>'DeveloperAcceptForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="DeveloperReject" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reject Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('DeveloperRejectForm',['name'=>'DeveloperRejectForm','id'=>'DeveloperRejectForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('DeveloperReject_application_id',['id'=>'DeveloperReject_application_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
								<lable>Reason to Reject </lable>
								
								<?php echo $this->Form->textarea('reject_reason', array('label' => false,'class'=>'form-control','placeholder'=>'Reason to Reject','id'=>'Developer_reject_reason')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'DeveloperReject_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary DeveloperReject_btn button-right','data-form-name'=>'DeveloperRejectForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>

	<div id="InternalClashed" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload Document</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('InternalClashedForm',['name'=>'InternalClashedForm','id'=>'InternalClashedForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
						<div class="row">
								<div class="col-md-12 internalclashedreason">
									 
								</div>
							</div>
							<?php echo $this->Form->input('InternalClashed_geo_id',['id'=>'InternalClashed_geo_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<lable class="col-md-6">Internal Clashed file&nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span></lable>
										<div class="col-md-6">
											<?php echo $this->Form->input('uploadfile', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload File','id'=>'uploadfile')); ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->textarea('internal_clashed_remark',['id'=>'internal_clashed_remark','label' =>false,'type'=>'text','placeholder'=>'Remark']); ?>
										</div>
									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="uploadfile-file-errors"></div>
									</div>
								</div>
							</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'InternalClashed_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary InternalClashed_btn button-right','data-form-name'=>'InternalClashedForm']); ?>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoVerify" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to Verify the Selected Coordinates?</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoVerifyForm',['name'=>'GeoVerifyForm','id'=>'GeoVerifyForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoVerify_application_id',['id'=>'GeoVerify_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<?php echo $this->Form->input('Submit',['id'=>'GeoVerify_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoVerify_btn button-right','data-form-name'=>'GeoVerifyForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
</div>



<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	$('.cross').click(function(){
   	 // Reload the page when clicked
    	location.reload();
 	});
 	$("#land_per_form_0").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-m",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#land_per_form_0-file-errors',
		maxFileSize: '5120',
	});
	
	$("#wtg_file_0").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-m",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#wtg_file_0-file-errors',
		maxFileSize: '1024',
	});
	$("#uploadfile").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-s",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#uploadfile-file-errors',
		maxFileSize: '1024',
	});

	$("#up_wtg_file_0").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-m",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#up_wtg_file_0-file-errors',
		maxFileSize: '1024',
	});
	$("#up_land_per_form_0").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-m",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#up_land_per_form_0-file-errors',
		maxFileSize: '5120',
	});
	$("#q_land_per_form_0").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-m",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#q_land_per_form_0-file-errors',
		maxFileSize: '5120',
	});
	
$(document).ready(function(){
	<?php $counter = 1;
	foreach ($geo_application_data as $key => $value) {?>
		$("#wtg_file_<?php echo $counter; ?>").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#wtg_file_<?php echo $counter; ?>-file-errors',
			maxFileSize: '5120',
		});
		$("#land_per_form_<?php echo $counter; ?>").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#land_per_form_<?php echo $counter; ?>-file-errors',
			maxFileSize: '5120',
		});
		$('#type_of_land_<?php echo $counter; ?>').change(function(){
            var selectedOption = $(this).val();
            console.log(selectedOption);
            if(selectedOption === 'P' || selectedOption === 'F') {
                $('.Land_<?php echo $counter; ?>').show(); // show the text box if option3 is selected
            } else {
                $('.Land_<?php echo $counter; ?>').hide(); // hide the text box if any other option is selected
            }
        });
		var type_of_land = '<?php echo $value->type_of_land ?>';
        if(type_of_land == 'G' || type_of_land == 'GL' ){
        	$('.Land_<?php echo $counter; ?>').hide();
        }else{
        	$('.Land_<?php echo $counter; ?>').show();
        }
	<?php $counter++;
	}
	?>
	//$('.chosen-select').chosen();
	$('.chosen-select').chosen({
        //disable_search_threshold: 10,
        search_contains:true,
        width: '100%'
    });
	
});
$('.offline_approved_date').datepicker({
       maxDate: new Date() // Set minimum date to today
      });
$('.wtg_validity_date').datepicker({
        minDate: 0 // Set minimum date to today
      });
// $(document).ready(function(){
//     $('.checkbox').change(function(){
//         var total = 0;
//         $('.checkbox:checked').each(function(){
//             total += parseInt($(this).val());
//         });
//         $('#geo_payment').val(total);
//     });
// });
// Close the modal when the user clicks on the close button (×)
//$('.PaymentForm input').removeAttr('disabled');
$('.PaymentForm').submit(function(){
		$('.PaymentForm input ,.PaymentForm select').removeAttr('disabled');
	});

function validateEastingDecimalInput(input) {
    // Remove any characters from the input value that are not digits or a decimal point
	input.value = input.value.replace(/[^\d.]/g, '');

	// Split the input value into integer and decimal parts
	let parts = input.value.split('.');
	let integerPart = parts[0];
	let decimalPart = parts[1];

	// If there are more than 6 digits before the decimal point, truncate the integer part
	if (integerPart.length > 6) {
	    input.value = integerPart.slice(0, 6);
	    integerPart = 6;
	}

	// If there's a decimal part and it's longer than 3 digits, truncate it
	if (decimalPart && decimalPart.length > 3) {
	    input.value = integerPart + '.' + decimalPart.slice(0, 3);
	}
}

function validateNorthingDecimalInput(input) {
    // Remove non-numeric and non-decimal characters
    input.value = input.value.replace(/[^0-9.]/g, '');

    // Split the input value into integer and decimal parts
    let parts = input.value.split('.');
    let integerPart = parts[0];
    let decimalPart = parts[1];

    // Limit integer part to 7 digits
    if (integerPart.length > 7) {
        input.value = input.value.slice(0, 7);
        integerPart = input.value.slice(0, 7);
    }

    // Limit decimal part to 3 digits
    if (decimalPart && decimalPart.length > 3) {
        input.value = integerPart + '.' + decimalPart.slice(0, 3);
    }
} 
function agreeClick()
{
	$(".terms_agree").prop('checked',true);
	$('#agree_popup').modal('hide');
	$('.showtds').show();
    $('.showtds').prop('disabled', false);
}
function show_reason(geo_id)
{
	console.log(geo_id);
	
	$.ajax({
				type: "POST",
				url: "/GeoApplications/rejectedData",
				data: {"geo_id":geo_id},
				
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result);
					if (result.success == 1) {
						$("#GeoReasonReject").find(".modal-body").html(result.message);
						$("#GeoReasonReject").modal("show");
					} 
					else {
						$("#GeoReasonReject").modal("show");
					}
				}
			});


}
function show_clash_reason(geo_id)
{
	$.ajax({
				type: "POST",
				url: "/GeoApplications/clashedData",
				data: {"geo_id":geo_id},
				
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result);
					if (result.success == 1) {
						$("#GeoReasonClashedData").find(".modal-body").html(result.message);
						$("#GeoReasonClashedData").modal("show");
					} 
					else {
						$("#GeoReasonClashedData").modal("show");
					}
				}
			});


}
function show_internal_clash_reason(geo_id)
{
	$.ajax({
				type: "POST",
				url: "/GeoApplications/InternalclashedData",
				data: {"geo_id":geo_id},
				
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result);
					if (result.success == 1) {
						$("#InternalClashed").find(".internalclashedreason").html(result.message);
						$("#InternalClashed_geo_id").val(result.geo_id);
						$("#InternalClashed").find(".internalclashedreason").html(result.message);
						$("#InternalClashed").modal("show");
					} 
					else {
						$("#InternalClashed").modal("show");
					}
				}
			});


}
$(".AddApplication").click(function(){
	var application_id = $(this).attr("data-id");
	$("#AddApplication_application_id").val(application_id);
});
$(".AddApplication_btn").click(function() {
	var form = $('#AddApplicationForm0');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
		var formcounter = fromobj.split('_').pop().toLowerCase();
		console.log(formcounter);
		var indexvalue  =  formcounter[formcounter.length - 1];
	console.log(indexvalue);
		ValidateRow(indexvalue);
	
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_savedata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#AddApplicationForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#AddApplicationForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".AddApplication_btn").removeAttr('disabled');
					}
				}
			});

});
$(".AddOfflineApplication").click(function(){
	var application_id = $(this).attr("data-id");
	var registration_no = $(this).attr("prod-id");
	
	$("#AddOfflineApplication_application_id").val(application_id);
	$("#app_reg_no_off").val(registration_no);
});
$(".AddOfflineApplication_btn").click(function() {
	var form = $('#AddOfflineApplicationForm0');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	var fromobj = $(this).attr("data-form-name");
	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];
	ValidateRow_offline(indexvalue);
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_save_offline_data",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#AddOfflineApplicationForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#AddOfflineApplicationForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".AddOfflineApplication_btn").removeAttr('disabled');
					}
				}
			});

});
function show_update_modal(id)
{
	console.log(id);
	$("#UpdateApplication").modal("show");
	$.ajax({
				type: "POST",
				url: "/GeoApplications/getSavedData",
				data: {"id":id},
				
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					var responseData = $.parseJSON(result.message); 
					console.log(responseData);
					if (result.success == 1) {
						console.log(responseData);
						$('#up_geo_id_0').val(responseData.id);
						$('#up_wtg_location_0').val(responseData.wtg_location);
            			$('#up_type_of_land_0').val(responseData.type_of_land);
            			$('#up_land_survey_no_0').val(responseData.land_survey_no);
            			$('#up_land_area_0').val(responseData.land_area);
            			$('#up_geo_district_0').val(responseData.geo_district);
            			console.log(responseData.geo_taluka);
            			//$('#up_geo_taluka_0').val(responseData.taluka_name);
            			// $("#up_geo_taluka_0" " option[value="+responseData.taluka_name+"]").attr("selected","selected");
            			 $("#up_geo_taluka_0").append('<option selected value=' + responseData.geo_taluka + '>' + responseData.taluka_name + '</option>');
            			$('#up_geo_village_0').val(responseData.geo_village);
            			$('#up_zone_0').val(responseData.zone);
            			$('#up_x_cordinate_0').val(responseData.x_cordinate);
            			$('#up_y_cordinate_0').val(responseData.y_cordinate);
            			$('#up_rlmm0').val(responseData.rlmm);
            			
            			var formattedDate = new Date(responseData.wtg_validity_date).toLocaleDateString();
            			$('#up_wtg_validity_date_0').val(formattedDate);

            			
            			if(responseData.rlmm == 'Y'){
            				$('#up_wtg_make_0').val(responseData.wtg_make);
	            			$("#up_wtg_model_0").append('<option selected value=' + responseData.wtg_model + '>' + responseData.wtg_model + '</option>');
	            			$('#up_wtg_capacity_0').append('<option selected value=' + responseData.wtg_capacity + '>' + responseData.wtg_capacity + '</option>');
	            			$('#up_wtg_rotor_dimension_0').append('<option selected value=' + responseData.wtg_rotor_dimension + '>' + responseData.wtg_rotor_dimension + '</option>');
	            			$('#up_wtg_hub_height_0').append('<option selected value=' + responseData.wtg_hub_height + '>' + responseData.wtg_hub_height + '</option>');
            			}
            			if(responseData.rlmm == 'N'){
	            			$('#up_wtg_make_n_0').val(responseData.wtg_make);
	            			$("#up_wtg_model_n_0").val(responseData.wtg_model);
	            			$('#up_wtg_capacity_n_0').val(responseData.wtg_capacity);
	            			$('#up_wtg_rotor_dimension_n_0').val(responseData.wtg_rotor_dimension);
	            			$('#up_wtg_hub_height_n_0').val(responseData.wtg_hub_height);
	            			$('.N_data').show();
					        $('.N_data').prop('disabled', false);
					        $('.Y_data').hide();
					        $('.Y_data').prop('disabled', true);
            			}
            			
				 	  	var path = responseData.path;
				 	  	$("#up_land_per_form_0").html('<a href="'+path+'" target="_blank"><i class="fa fa-eye"> View Land Permission</i></a>');

					} 
					else {
						$("#GeoReasonReject").modal("show");
					}
				}
			});


}
// $(".UpdateApplication").click(function(){
// 	var application_id = $(this).attr("data-id");
// 	$("#UpdateApplication_application_id").val(application_id);
// });
$(".UpdateApplication_btn").click(function() {
	var form = $('#UpdateApplicationForm0');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
		var formcounter = fromobj.split('_').pop().toLowerCase();
		console.log(formcounter);
		var indexvalue  =  formcounter[formcounter.length - 1];
	console.log(indexvalue);
		ValidateRow_up(indexvalue);
	
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_editdata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#UpdateApplicationForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#UpdateApplicationForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".AddApplication_btn").removeAttr('disabled');
					}
				}
			});

});
$(document).ready(function(){
    $checks = $(".check");
    $checks.on('change', function() {
        var string = $checks.filter(":checked").map(function(i,v){
            return this.id;
        }).get().join(",");
        console.log(string);
        $('#geo_id').val(string);
    });

    $verify = $(".verify");
    $verify.on('change', function() {
        var string = $verify.filter(":checked").map(function(i,v){
            return this.id;
        }).get().join(",");
        console.log(string);
        $('#GeoVerify_application_id').val(string);
    });

    $('.checkbox').change(function(){
	    var total 			= 0;
	    var total_gst 		= 0;
	    var geo_total_fee 	= 0;
	    var geo_location_tds= 0;
	    var net_payable		= 0;
	    var gst = <?php echo $applicationCategory->geo_location_tax ?>;
	    var geo_location_tds = <?php echo $applicationCategory->application_tds_percentage ?>;
	    $('.checkbox:checked').each(function(){
	        total 						+= parseInt($(this).val());
	        total_gst 					=(total*gst)/100;
	        geo_total_fee 				=(total+total_gst);
	        geo_location_tds_amount 	=(total*geo_location_tds)/100;
	        net_payable					=((geo_total_fee) - geo_location_tds_amount);
	    });
	    $('#geo_payment').val(total);
	    $('#gst_fees').val(total_gst);
	    $('#geo_total_fee').val(geo_total_fee);
	    $('#geo_location_tds').val(geo_location_tds_amount);
	    $('#net_payable').val(net_payable);
	});
	$('.onlycharacter').keypress(function(event){
        var inputValue = event.which;
        // Allow letters: A-Z and a-z
        if((inputValue >= 65 && inputValue <= 90) || (inputValue >= 97 && inputValue <= 122) || inputValue == 8 || inputValue == 32) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }

    });

	$('#type_of_land_0').change(function(){
            var selectedOption = $(this).val();
            console.log(selectedOption);
            if(selectedOption === 'P') {
                $('.Land').show(); // show the text box if option3 is selected
                $('.private').show();
                $('.forest').hide();
                $('.govt').hide();
                $('.geda').hide();
            } else if(selectedOption === 'F'){
            	$('.Land').show(); // show the text box if option3 is selected
                $('.private').hide();
                $('.forest').show();
                $('.govt').hide();
                $('.geda').hide();
            }else if(selectedOption === 'G'){
            	 $('.private').hide();
                $('.forest').hide();
                $('.govt').show();
                $('.geda').hide();
            }else if(selectedOption === 'GL'){
            	$('.private').hide();
                $('.forest').hide();
                $('.govt').hide();
                $('.geda').show();
            }
        });

	$('#up_type_of_land_0').change(function(){
            var selectedOption = $(this).val();
            console.log(selectedOption);
            if(selectedOption === 'P') {
                $('.Land').show(); // show the text box if option3 is selected
                $('.private').show();
                $('.forest').hide();
                $('.govt').hide();
                $('.geda').hide();
            } else if(selectedOption === 'F'){
            	$('.Land').show(); // show the text box if option3 is selected
                $('.private').hide();
                $('.forest').show();
                $('.govt').hide();
                $('.geda').hide();
            }else if(selectedOption === 'G'){
            	 $('.private').hide();
                $('.forest').hide();
                $('.govt').show();
                $('.geda').hide();
            }else if(selectedOption === 'GL'){
            	$('.private').hide();
                $('.forest').hide();
                $('.govt').hide();
                $('.geda').show();
            }
        });
});

$('.select_all').click(function() {
  //This will select all inputs with id starting with green
  <?php $counter = 1;
  foreach ($geo_application_data as $key => $value) {?>
	$("input[id^=<?php echo $value->id ?>]").prop('checked', $(this).prop("checked"));
	  var total = 0;
	  $('.checkbox:checked').each(function(){
	        total += parseInt($(this).val());
	    });
	    $('#geo_payment').val(total);

	<?php 	$counter++;
	}?>
  
});

$(".GeoFile_btn").click(function() {
	var form = $('#GeoFileForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	
	var fromobj = $(this).attr("data-form-name");

	var GeoFile = $("#"+fromobj).find("#GeoFile").val();
	
	if(GeoFile != '' && $("#"+fromobj).find("#GeoFile").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Title is required field.");
		return false;
	}
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/GeoFileDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#CTUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#CTUStep1Form").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".CTUstep1_btn").removeAttr('disabled');
					}
				}
			});

});
function rlmmchange(id) {
	console.log(id);
	rlmoption = $("#rlmm"+id).val();
	console.log(rlmoption);
	// Show or hide the text box based on the selected option
    if (rlmoption === 'N') {
        $('.N_data'+id).show();
        $('.N_data'+id).prop('disabled', false);
        $('.Y_data'+id).hide();
        $('.Y_data'+id).prop('disabled', true); // Enable the text box

        $('.N_data').show();
        $('.N_data').prop('disabled', false);
        $('.Y_data').hide();
        $('.Y_data').prop('disabled', true);

    } else if (rlmoption === 'Y') {
        $('.N_data'+id).hide();
        $('.N_data'+id).prop('disabled', true); // Disable the text box
        $('.Y_data'+id).show();
        $('.Y_data'+id).prop('disabled', false); 

        $('.N_data').hide();
        $('.N_data').prop('disabled', true); // Disable the text box
        $('.Y_data').show();
        $('.Y_data').prop('disabled', false); 
    }

}

$("#GeoFile").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-s",
		allowedFileExtensions: ["kmz"],
		elErrorContainer: '#GeoFile-file-errors',
		maxFileSize: '5120',
	});
$(document).on('click', '#addNote', function() {
  $('#add_projects_note_model').modal('show');
});

$(document).ready(function () {
<?php $counter = 1;

foreach ($geo_application_data as $key => $value) {?>
getTalukaFromDistrict('<?php echo $counter ?>','<?php echo $value->geo_taluka ?>');
<?php	if($value->rlmm == 'Y'){?>
		changeMake('<?php echo $counter ?>','<?php echo $value->wtg_model ?>','<?php echo $value->wtg_rotor_dimension ?>','<?php echo $value->wtg_hub_height ?>','<?php echo $value->wtg_capacity ?>');
		$('.Y_data'+<?php echo $counter ?>).show();
        $('.Y_data'+<?php echo $counter ?>).prop('disabled', false);
        $('.N_data'+<?php echo $counter?>).hide();
        $('.N_data'+<?php echo $counter ?>).prop('disabled', true);
	<?php }else{?>
		$('.N_data'+<?php echo $counter ?>).show();
        $('.N_data'+<?php echo $counter ?>).prop('disabled', false);
        $('.Y_data'+<?php echo $counter?>).hide();
        $('.Y_data'+<?php echo $counter ?>).prop('disabled', true);
	<?php }

	$counter++;
}?>
});



$(".savedata").click(function() {
	
	var fromobj     = $(this).attr("data-form-name");

	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];

	ValidateRow(indexvalue);

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$.ajax({
			type: "POST",
			url: "/GeoApplications/geo_location_savedata",
			data: $("#"+fromobj).serialize(),
			beforeSend: function(xhr){
				xhr.setRequestHeader(
					'X-CSRF-Token',
					<?php echo json_encode($this->request->param('_csrfToken')); ?>
				);
			},
			success: function(response) {
				var result = $.parseJSON(response);
				console.log(result.success);
				if (result.success == 1) {
					$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
				location.reload();
				} else {
					$("#geo_cordinate4").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');

				}
			}
		});
});
function getTalukaFromDistrict(id,taluka) {
	var district= $("#geo_district_" + id).val();
	console.log(taluka);
	$.ajax({
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?= json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		type: "POST",
		url: "/GeoApplications/getTalukaFromDistrict",
		data: {"district":district},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#geo_taluka_"+ id).html('');
			$("#geo_taluka_"+ id).append($("<option />").val('').text('-Select Taluka-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					$("#geo_taluka_"+ id).append($("<option />").val(index).text(title));
				});
				//$('#geo_taluka_0').val('');
				if(taluka != '') {
					$("#geo_taluka_" + id+" option[value="+taluka+"]").attr("selected","selected");
				} else {
					$("#geo_taluka_"+ id).val('');
				}
				
			}
			//getVillageFromTaluka();
		}
	});
}

function changeMake(id,mid='',rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		url: "/GeoApplications/getModel",
		data: {
			"makeId": $('#wtg_make_' + id).val()
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?php echo json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result);
			$("#wtg_model_" + id).html("");
			$("#wtg_model_" + id).append($("<option />").val('').text('-Select WTG Model-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					if(title.toLowerCase() == mid.toLowerCase() && rid !='' && hid !='' && cid !=''){
						$("#wtg_model_" + id).append($("<option selected>").val(index).text(title));
						changemodel(id,rid,hid,cid);
					}else{
						$("#wtg_model_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
		}
	});
}

function changemodel(id,rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		async: false,
		url: "/GeoApplications/getModelDetails",
		data: {
			"modelNm": $('#wtg_model_' + id).val()
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?php echo json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#wtg_rotor_dimension_" + id).html("");
			$("#wtg_hub_height_" + id).html("");
			$("#wtg_capacity_" + id).html("");
			$("#wtg_validity_date_" + id).val("");
			
			$("#wtg_rotor_dimension_" + id).append($("<option />").val('').text('-Select WTG Rotor Dimension-'));
			$("#wtg_hub_height_" + id).append($("<option />").val('').text('-Select WTG Hub Height-'));
			$("#wtg_capacity_" + id).append($("<option />").val('').text('-Select WTG Capacity-'));
			
			if (result.data.rotor != undefined) {
				$.each(result.data.rotor, function(index, title) {
					if(title == rid){
						$("#wtg_rotor_dimension_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#wtg_rotor_dimension_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			if (result.data.hub != undefined) {
				$.each(result.data.hub, function(index, title) {
					if(title == hid){
						$("#wtg_hub_height_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#wtg_hub_height_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			if (result.data.capacity != undefined) {
				$.each(result.data.capacity, function(index, title) {
					if(title == cid){
						$("#wtg_capacity_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#wtg_capacity_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			$("#wtg_validity_date_" + id).val(result.data.validity);
			$("#wtg_validity_date_" + id).prop("readonly", true);
		}
	});
}
$(".editdata").click(function() {
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];

	var rlmm = $("#rlmm"+indexvalue).val();
	var wtg_file = $("#wtg_file_"+indexvalue).val();
	var land_per_form = $("#land_per_form_"+indexvalue).val();
	
	if(!(wtg_file)){
		var formdata = false;
		var formData = new FormData($("#"+fromobj)[0]); // Create FormData object
    }else{
		var formdata = false;
		var fileInput = $('#wtg_file_'+indexvalue)[0].files[0]; // Get the file object
		var formData = new FormData($("#"+fromobj)[0]); // Create FormData object
    	formData.append('wtg_file', fileInput); // Append file to FormData
	}

	var fileInput1 = $('#land_per_form_'+indexvalue)[0].files[0]; // Get the file object
	formData.append('land_per_form', fileInput1); // Append file to FormData
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_editdata",
				//data: formData,
				data: formData,
				contentType: false,
            	processData: false,
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						// $("#TPForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						// $("#TPForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						//$(".TP_btn").removeAttr('disabled');
					}
				}
			});
});

function ValidateRow(index) {
	//$("#tbl_wind_info > tbody  > tr").each(function(index, tr) {
		if($("#wtg_location_error_msg_"+index).html() != undefined) {
			$("#wtg_location_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_location_error_msg_"+index).remove();
		}
		if($("#type_of_land_error_msg_"+index).html() != undefined) {
			$("#type_of_land_error_msg_"+index).parent().removeClass('has-error');
			$("#type_of_land_error_msg_"+index).remove();
		}
		if($("#land_survey_no_error_msg_"+index).html() != undefined) {
			$("#land_survey_no_error_msg_"+index).parent().removeClass('has-error');
			$("#land_survey_no_error_msg_"+index).remove();
		}
		if($("#land_area_error_msg_"+index).html() != undefined) {
			$("#land_area_error_msg_"+index).parent().removeClass('has-error');
			$("#land_area_error_msg_"+index).remove();
		}
		if($("#wtg_validity_date_error_msg_"+index).html() != undefined) {
			$("#wtg_validity_date_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_validity_date_error_msg_"+index).remove();
		}
		// if($("#sub_lease_deed_error_msg_"+index).html() != undefined) {
		// 	$("#sub_lease_deed_error_msg_"+index).parent().removeClass('has-error');
		// 	$("#sub_lease_deed_error_msg_"+index).remove();
		// }

		if($("#geo_village_error_msg_"+index).html() != undefined) {
			$("#geo_village_error_msg_"+index).parent().removeClass('has-error');
			$("#geo_village_error_msg_"+index).remove();
		}
		if($("#geo_taluka_error_msg_"+index).html() != undefined) {
			$("#geo_taluka_error_msg_"+index).parent().removeClass('has-error');
			$("#geo_taluka_error_msg_"+index).remove();
		}
		if($("#geo_district_error_msg_"+index).html() != undefined) {
			$("#geo_district_error_msg_"+index).parent().removeClass('has-error');
			$("#geo_district_error_msg_"+index).remove();
		}
		if($("#zone_error_msg_"+index).html() != undefined) {
			$("#zone_error_msg_"+index).parent().removeClass('has-error');
			$("#zone_error_msg_"+index).remove();
		}
		if($("#x_cordinate_error_msg_"+index).html() != undefined) {
			$("#x_cordinate_error_msg_"+index).parent().removeClass('has-error');
			$("#x_cordinate_error_msg_"+index).remove();
		}
		if($("#y_cordinate_error_msg_"+index).html() != undefined) {
			$("#y_cordinate_error_msg_"+index).parent().removeClass('has-error');
			$("#y_cordinate_error_msg_"+index).remove();
		}

		if($("#wtg_make_error_msg_"+index).html() != undefined) {
			$("#wtg_make_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_make_error_msg_"+index).remove();
		}
		if($("#wtg_model_error_msg_"+index).html() != undefined) {
			$("#wtg_model_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_model_error_msg_"+index).remove();
		}
		if($("#wtg_capacity_error_msg_"+index).html() != undefined) {
			$("#wtg_capacity_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_capacity_error_msg_"+index).remove();
		}
		if($("#wtg_rotor_dimension_error_msg_"+index).html() != undefined) {
			$("#wtg_rotor_dimension_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_rotor_dimension_error_msg_"+index).remove();
		}
		if($("#wtg_hub_height_error_msg_"+index).html() != undefined) {
			$("#wtg_hub_height_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_hub_height_error_msg_"+index).remove();
		}
		if($("#land_per_form_error_msg_"+index).html() != undefined) {
			$("#land_per_form_error_msg_"+index).parent().removeClass('has-error');
			$("#land_per_form_error_msg_"+index).remove();
		}

		var wtg_location   		= $("#wtg_location_" + index).val() ? $("#wtg_location_" + index).val() : 0;
		var type_of_land   		= $("#type_of_land_" + index).val() ? $("#type_of_land_" + index).val() : 0;
		var land_survey_no  	= $("#land_survey_no_" + index).val() ? $("#land_survey_no_" + index).val() : 0;
		var land_area   		= $("#land_area_" + index).val() ? $("#land_area_" + index).val() : 0;
		var wtg_validity_date 	= $("#wtg_validity_date_" + index).val() ? $("#lwtg_validity_date_" + index).val() : 0;
		// var sub_lease_deed  = $("#sub_lease_deed_" + index).val() ? $("#sub_lease_deed_" + index).val() : 0;
		var geo_village  		= $("#geo_village_" + index).val() ? $("#geo_village_" + index).val() : 0;
		var geo_taluka   		= $("#geo_taluka_" + index).val() ? $("#geo_taluka_" + index).val() : 0;
		var geo_district 		= $("#geo_district_" + index).val() ? $("#geo_district_" + index).val() : 0;
		var zone 				= $("#zone_" + index).val() ? $("#zone_" + index).val() : 0;
		var x_cordinate 		= $("#x_cordinate_" + index).val() ? $("#x_cordinate_" + index).val() : 0;
		var y_cordinate 		= $("#y_cordinate_" + index).val() ? $("#y_cordinate_" + index).val() : 0;
		
		var wtg_make 			= $("#wtg_make_" + index).val() ? $("#wtg_make_" + index).val() : 0;
		var wtg_model 			= $("#wtg_model_" + index).val() ? parseFloat($("#wtg_model_" + index).val()) : 0;
		var wtg_capacity 		= $("#wtg_capacity_" + index).val() ? parseFloat($("#wtg_capacity_" + index).val()) : 0;
		var wtg_rotor_dimension = $("#wtg_rotor_dimension_" + index).val() ? parseFloat($("#wtg_rotor_dimension_" + index).val()) : 0;
		var wtg_hub_height 		= $("#wtg_hub_height_" + index).val() ? parseFloat($("#wtg_hub_height_" + index).val()) : 0;

		var land_per_form 			= $("#land_per_form_" + index).val() ? $("#land_per_form_" + index).val() : 0;
		if (land_per_form <= 0) {
			$("#land_per_form_" + index).parent().addClass('has-error');
			$("#land_per_form_" + index).parent().append('<div class="help-block land_per_form_error_msg_cls" id="land_per_form_error_msg_' + index + '">Required</div>');
		}
		var rlmm 		= $("#rlmm" + index).val();
		console.log('rlmm');
		if(rlmm == 'N'){
			if($("#wtg_make_n_error_msg_"+index).html() != undefined) {
				$("#wtg_make_n_error_msg_"+index).parent().removeClass('has-error');
				$("#wtg_make_n_error_msg_"+index).remove();
			}
			if($("#wtg_model_n_error_msg_"+index).html() != undefined) {
				$("#wtg_model_n_error_msg_"+index).parent().removeClass('has-error');
				$("#wtg_model_n_error_msg_"+index).remove();
			}
			if($("#wtg_capacity_n_error_msg_"+index).html() != undefined) {
				$("#wtg_capacity_n_error_msg_"+index).parent().removeClass('has-error');
				$("#wtg_capacity_n_error_msg_"+index).remove();
			}
			if($("#wtg_rotor_dimension_n_error_msg_"+index).html() != undefined) {
				$("#wtg_rotor_dimension_n_error_msg_"+index).parent().removeClass('has-error');
				$("#wtg_rotor_dimension_n_error_msg_"+index).remove();
			}
			if($("#wtg_hub_height_n_error_msg_"+index).html() != undefined) {
				$("#wtg_hub_height_n_error_msg_"+index).parent().removeClass('has-error');
				$("#wtg_hub_height_n_error_msg_"+index).remove();
			}
			if($("#wtg_file_error_msg_"+index).html() != undefined) {
				$("#wtg_file_error_msg_"+index).parent().removeClass('has-error');
				$("#wtg_file_error_msg_"+index).remove();
			}
			var wtg_make_n 				= $("#wtg_make_n_" + index).val() ? $("#wtg_make_n_" + index).val() : 0;
			var wtg_model_n 			= $("#wtg_model_n_" + index).val() ? $("#wtg_model_n_" + index).val() : 0;
			var wtg_capacity_n 			= $("#wtg_capacity_n_" + index).val() ? $("#wtg_capacity_n_" + index).val() : 0;
			var wtg_rotor_dimension_n 	= $("#wtg_rotor_dimension_n_" + index).val() ? $("#wtg_rotor_dimension_n_" + index).val() : 0;
			var wtg_hub_height_n 		= $("#wtg_hub_height_n_" + index).val() ? $("#wtg_hub_height_n_" + index).val() : 0;
			var wtg_file 				= $("#wtg_file_" + index).val() ? $("#wtg_file_" + index).val() : 0;
			if (wtg_file <= 0) {
				$("#wtg_file_" + index).parent().addClass('has-error');
				$("#wtg_file_" + index).parent().append('<div class="help-block wtg_file_error_msg_cls" id="wtg_file_error_msg_' + index + '">Required</div>');
			}
			if (wtg_make_n <= 0) {
				$("#wtg_make_n_" + index).parent().addClass('has-error');
				$("#wtg_make_n_" + index).parent().append('<div class="help-block wtg_make_n_error_msg_cls" id="wtg_make_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_model_n <= 0) {
				$("#wtg_model_n_" + index).parent().addClass('has-error');
				$("#wtg_model_n_" + index).parent().append('<div class="help-block wtg_model_n_error_msg_cls" id="wtg_model_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_capacity_n <= 0) {
				$("#wtg_capacity_n_" + index).parent().addClass('has-error');
				$("#wtg_capacity_n_" + index).parent().append('<div class="help-block wtg_capacity_n_error_msg_cls" id="wtg_capacity_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_rotor_dimension_n <= 0) {
				$("#wtg_rotor_dimension_n_" + index).parent().addClass('has-error');
				$("#wtg_rotor_dimension_n_" + index).parent().append('<div class="help-block wtg_rotor_dimension_n_error_msg_cls" id="wtg_rotor_dimension_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_hub_height_n <= 0) {
				$("#wtg_hub_height_n_" + index).parent().addClass('has-error');
				$("#wtg_hub_height_n_" + index).parent().append('<div class="help-block wtg_hub_height_n_error_msg_cls" id="wtg_hub_height_n_error_msg_' + index + '">Required</div>');
			}
		}
		

		if ( wtg_make <= 0 || wtg_model <= 0 || wtg_capacity <= 0 || wtg_rotor_dimension <= 0 || wtg_hub_height <= 0) {
			addRow = 0;
		}

		if (wtg_location <= 0) {
			$("#wtg_location_" + index).parent().addClass('has-error');
			$("#wtg_location_" + index).parent().append('<div class="help-block wtg_location_error_msg_cls" id="wtg_location_error_msg_' + index + '">Required</div>');
		}
		if (type_of_land <= 0) {
			$("#type_of_land_" + index).parent().addClass('has-error');
			$("#type_of_land_" + index).parent().append('<div class="help-block type_of_land_error_msg_cls" id="type_of_land_error_msg_' + index + '">Required</div>');
		}
		if (land_survey_no <= 0) {
			$("#land_survey_no_" + index).parent().addClass('has-error');
			$("#land_survey_no_" + index).parent().append('<div class="help-block land_survey_no_error_msg_cls" id="land_survey_no_error_msg_' + index + '">Required</div>');
		}
		if (land_area <= 0) {
			$("#land_area_" + index).parent().addClass('has-error');
			$("#land_area_" + index).parent().append('<div class="help-block land_area_error_msg_cls" id="land_area_error_msg_' + index + '">Required</div>');
		}
		if (wtg_validity_date <= 0) {
			$("#wtg_validity_date_" + index).parent().addClass('has-error');
			$("#wtg_validity_date_" + index).parent().append('<div class="help-block wtg_validity_date_error_msg_cls" id="wtg_validity_date_error_msg_' + index + '">Required</div>');
		}
		// if (sub_lease_deed <= 0) {
		// 	$("#sub_lease_deed_" + index).parent().addClass('has-error');
		// 	$("#sub_lease_deed_" + index).parent().append('<div class="help-block sub_lease_deed_error_msg_cls" id="sub_lease_deed_error_msg_' + index + '">Required</div>');
		// }
		if (geo_village <= 0) {
			$("#geo_village_" + index).parent().addClass('has-error');
			$("#geo_village_" + index).parent().append('<div class="help-block geo_village_error_msg_cls" id="geo_village_error_msg_' + index + '">Required</div>');
		}
		if (geo_taluka <= 0) {
			$("#geo_taluka_" + index).parent().addClass('has-error');
			$("#geo_taluka_" + index).parent().append('<div class="help-block geo_taluka_error_msg_cls" id="geo_taluka_error_msg_' + index + '">Required</div>');
		}
		if (geo_district <= 0) {
			$("#geo_district_" + index).parent().addClass('has-error');
			$("#geo_district_" + index).parent().append('<div class="help-block geo_district_error_msg_cls" id="geo_district_error_msg_' + index + '">Required</div>');
		}
		if (zone <= 0) {
			$("#zone_" + index).parent().addClass('has-error');
			$("#zone_" + index).parent().append('<div class="help-block zone_error_msg_cls" id="zone_error_msg_' + index + '">Required</div>');
		}
		//var xpattern = /^\d{6}\.\d{0,3}$/;
		var xpattern = /^(\d{6}(\.\d{0,3})?)?$/;
		if(xpattern.test(x_cordinate)){

		}else{
			$("#x_cordinate_" + index).parent().addClass('has-error');
			$("#x_cordinate_" + index).parent().append('<div class="help-block x_cordinate_error_msg_cls" id="x_cordinate_error_msg_' + index + '">Value does not match the format "000000.000"</div>');
		}
		// if (x_cordinate < 19.00 || x_cordinate >  24.82) {
			
		// }
		//var ypattern = /^\d{7}\.\d{0,3}$/;
		var ypattern = /^(\d{7}(\.\d{0,3})?)?$/;
		if(ypattern.test(y_cordinate)){

		}else{
			$("#y_cordinate_" + index).parent().addClass('has-error');
			$("#y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="y_cordinate_error_msg_' + index + '">Value does not match the format "0000000.000"</div>');
		}
		// if (y_cordinate < 68.00 || y_cordinate > 74.62) {
		// 	$("#y_cordinate_" + index).parent().addClass('has-error');
		// 	$("#y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="y_cordinate_error_msg_' + index + '">Y-Coordinate Between 68.00 to 74.62</div>');
		// }
		if (wtg_make <= 0) {
			$("#wtg_make_" + index).parent().addClass('has-error');
			$("#wtg_make_" + index).parent().append('<div class="help-block wtg_make_error_msg_cls" id="wtg_make_error_msg_' + index + '">Required</div>');
		}
		if (wtg_model <= 0) {
			$("#wtg_model_" + index).parent().addClass('has-error');
			$("#wtg_model_" + index).parent().append('<div class="help-block wtg_model_error_msg_cls" id="wtg_model_error_msg_' + index + '">Required</div>');
		}
		if (wtg_capacity <= 0) {
			$("#wtg_capacity_" + index).parent().addClass('has-error');
			$("#wtg_capacity_" + index).parent().append('<div class="help-block wtg_capacity_error_msg_cls" id="wtg_capacity_error_msg_' + index + '">Required</div>');
		}
		if (wtg_rotor_dimension <= 0) {
			$("#wtg_rotor_dimension_" + index).parent().addClass('has-error');
			$("#wtg_rotor_dimension_" + index).parent().append('<div class="help-block wtg_rotor_dimension_error_msg_cls" id="wtg_rotor_dimension_error_msg_' + index + '">Required</div>');
		}
		if (wtg_hub_height <= 0) {
			$("#wtg_hub_height_" + index).parent().addClass('has-error');
			$("#wtg_hub_height_" + index).parent().append('<div class="help-block wtg_hub_height_error_msg_cls" id="wtg_hub_height_error_msg_' + index + '">Required</div>');
		}
		
	//});
}

$(".GeoApprove").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoApprove_application_id").val(application_id);
});
$(".GeoApprove_btn").click(function() {
	var form = $('#GeoApproveForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_approvedata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoApproveForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoApproveForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoApprove_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoReject").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoReject_application_id").val(application_id);
});
$(".GeoReject_btn").click(function() {
	var form = $('#GeoRejectForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");

	var reject_reason = $("#"+fromobj).find("#reject_reason").val();
	
	if($("#"+fromobj).find("#reject_reason").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		return false;
	}
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_rejectdata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoRejectForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoRejectForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoReject_btn").removeAttr('disabled');
					}
				}
			});

});
// $(".GeoVerify").click(function(){
// 	var application_id = $(this).attr("data-id");
// 	$("#GeoVerify_application_id").val(application_id);
// });
$(".GeoVerify_btn").click(function() {
	var form = $('#GeoVerifyForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_verifydata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoVerifyForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoVerifyForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoVerify_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoClash").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoClash_geo_id").val(application_id);
});
$(".GeoClash_btn").click(function() {
	var form = $('#GeoClashForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");
	var clashed_remark = $("#"+fromobj).find("#clashed_remark").val();
	if($("#"+fromobj).find("#approved_clashed_geo_id").val() == null) {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Clashed Data is required field.");
		return false;
	}
	if($("#"+fromobj).find("#clashed_remark").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Clashed Remark is required field.");
		return false;
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_clashdata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoClashForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoClashForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoClash_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoClashInternal").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoClashInternal_geo_id").val(application_id);
});
$(".GeoClashInternal_btn").click(function() {
	var form = $('#GeoClashInternalForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");
	var internal_clashed_remark = $("#"+fromobj).find("#internal_clashed_remark").val();
	if($("#"+fromobj).find("#approved_geo_id").val() == null) {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Internal Clashed Data is required field.");
		return false;
	}
	if($("#"+fromobj).find("#internal_clashed_remark").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Internal Clashed Remark is required field.");
		return false;
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_clashdata_internal",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoClashInternalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoClashInternalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoClashInternal_btn").removeAttr('disabled');
					}
				}
			});

});
$(".DeveloperAccept").click(function(){
	var application_id = $(this).attr("data-id");
	$("#DeveloperAccept_application_id").val(application_id);
});
$(".DeveloperAccept_btn").click(function() {
	var form = $('#DeveloperAcceptForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('DeveloperAccept_btn');
	var fromobj = $(this).attr("data-form-name");

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/developer_accept_data",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#DeveloperAcceptForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#DeveloperAcceptForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".DeveloperAccept_btn").removeAttr('disabled');
					}
				}
			});
});

$(".DeveloperReject").click(function(){
	var application_id = $(this).attr("data-id");
	$("#DeveloperReject_application_id").val(application_id);
});
$(".DeveloperReject_btn").click(function() {
	var form = $('#DeveloperRejectForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('DeveloperReject_btn');
	var fromobj = $(this).attr("data-form-name");
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/developer_reject_data",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#DeveloperRejectForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#DeveloperRejectForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".DeveloperReject_btn").removeAttr('disabled');
					}
				}
			});
});

$(".InternalClashed").click(function(){
	var geo_id = $(this).attr("data-id");
	$("#InternalClashed_geo_id").val(geo_id);
});
$(".InternalClashed_btn").click(function() {
	var form = $('#InternalClashedForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	 
	var fromobj = $(this).attr("data-form-name");

	var internal_clashed_remark = $("#"+fromobj).find("#internal_clashed_remark").val();
	var uploadfile = $("#"+fromobj).find("#uploadfile").val();

	if(internal_clashed_remark == '' ) {
		console.log("hello");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Remark is required field.");
		return false;
	}
	if(uploadfile == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Upload File is required field.");
		return false;
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/developer_InternalClashed_data",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#InternalClashedForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#InternalClashedForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".InternalClashed_btn").removeAttr('disabled');
					}
				}
			});
});

function changeMake_up(id,mid='',rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		url: "/GeoApplications/getModel",
		data: {
			"makeId": $('#up_wtg_make_' + id).val()
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?php echo json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result);
			$("#up_wtg_model_" + id).html("");
			$("#up_wtg_model_" + id).append($("<option />").val('').text('-Select WTG Model-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					if(title.toLowerCase() == mid.toLowerCase() && rid !='' && hid !='' && cid !=''){
						$("#up_wtg_model_" + id).append($("<option selected>").val(index).text(title));
						changemodel_up(id,rid,hid,cid);
					}else{
						$("#up_wtg_model_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
		}
	});
}

function changemodel_up(id,rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		async: false,
		url: "/GeoApplications/getModelDetails",
		data: {
			"modelNm": $('#up_wtg_model_' + id).val()
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?php echo json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#up_wtg_rotor_dimension_" + id).html("");
			$("#up_wtg_hub_height_" + id).html("");
			$("#up_wtg_capacity_" + id).html("");
			$("#up_wtg_validity_date_" + id).val("");
			
			$("#up_wtg_rotor_dimension_" + id).append($("<option />").val('').text('-Select WTG Rotor Dimension-'));
			$("#up_wtg_hub_height_" + id).append($("<option />").val('').text('-Select WTG Hub Height-'));
			$("#up_wtg_capacity_" + id).append($("<option />").val('').text('-Select WTG Capacity-'));
			
			if (result.data.rotor != undefined) {
				$.each(result.data.rotor, function(index, title) {
					if(title == rid){
						$("#up_wtg_rotor_dimension_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#up_wtg_rotor_dimension_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			if (result.data.hub != undefined) {
				$.each(result.data.hub, function(index, title) {
					if(title == hid){
						$("#up_wtg_hub_height_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#up_wtg_hub_height_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			if (result.data.capacity != undefined) {
				$.each(result.data.capacity, function(index, title) {
					if(title == cid){
						$("#up_wtg_capacity_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#up_wtg_capacity_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			$("#up_wtg_validity_date_" + id).val(result.data.validity);
			$("#up_wtg_validity_date_" + id).prop("readonly", true);
		}
	});
}
function ValidateRow_up(index) {
	//$("#tbl_wind_info > tbody  > tr").each(function(index, tr) {
		if($("#up_wtg_location_error_msg_"+index).html() != undefined) {
			$("#up_wtg_location_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_location_error_msg_"+index).remove();
		}
		if($("#up_type_of_land_error_msg_"+index).html() != undefined) {
			$("#up_type_of_land_error_msg_"+index).parent().removeClass('has-error');
			$("#up_type_of_land_error_msg_"+index).remove();
		}
		if($("#up_land_survey_no_error_msg_"+index).html() != undefined) {
			$("#up_land_survey_no_error_msg_"+index).parent().removeClass('has-error');
			$("#up_land_survey_no_error_msg_"+index).remove();
		}
		if($("#up_land_area_error_msg_"+index).html() != undefined) {
			$("#up_land_area_error_msg_"+index).parent().removeClass('has-error');
			$("#up_land_area_error_msg_"+index).remove();
		}
		if($("#up_wtg_validity_date_error_msg_"+index).html() != undefined) {
			$("#up_wtg_validity_date_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_validity_date_error_msg_"+index).remove();
		}
		// if($("#up_sub_lease_deed_error_msg_"+index).html() != undefined) {
		// 	$("#up_sub_lease_deed_error_msg_"+index).parent().removeClass('has-error');
		// 	$("#up_sub_lease_deed_error_msg_"+index).remove();
		// }

		if($("#up_geo_village_error_msg_"+index).html() != undefined) {
			$("#up_geo_village_error_msg_"+index).parent().removeClass('has-error');
			$("#up_geo_village_error_msg_"+index).remove();
		}
		if($("#up_geo_taluka_error_msg_"+index).html() != undefined) {
			$("#up_geo_taluka_error_msg_"+index).parent().removeClass('has-error');
			$("#up_geo_taluka_error_msg_"+index).remove();
		}
		if($("#up_geo_district_error_msg_"+index).html() != undefined) {
			$("#up_geo_district_error_msg_"+index).parent().removeClass('has-error');
			$("#up_geo_district_error_msg_"+index).remove();
		}
		if($("#up_zone_error_msg_"+index).html() != undefined) {
			$("#up_zone_error_msg_"+index).parent().removeClass('has-error');
			$("#up_zone_error_msg_"+index).remove();
		}
		if($("#up_x_cordinate_error_msg_"+index).html() != undefined) {
			$("#up_x_cordinate_error_msg_"+index).parent().removeClass('has-error');
			$("#up_x_cordinate_error_msg_"+index).remove();
		}
		if($("#up_y_cordinate_error_msg_"+index).html() != undefined) {
			$("#up_y_cordinate_error_msg_"+index).parent().removeClass('has-error');
			$("#up_y_cordinate_error_msg_"+index).remove();
		}

		if($("#up_wtg_make_error_msg_"+index).html() != undefined) {
			$("#up_wtg_make_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_make_error_msg_"+index).remove();
		}
		if($("#up_wtg_model_error_msg_"+index).html() != undefined) {
			$("#up_wtg_model_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_model_error_msg_"+index).remove();
		}
		if($("#up_wtg_capacity_error_msg_"+index).html() != undefined) {
			$("#up_wtg_capacity_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_capacity_error_msg_"+index).remove();
		}
		if($("#up_wtg_rotor_dimension_error_msg_"+index).html() != undefined) {
			$("#up_wtg_rotor_dimension_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_rotor_dimension_error_msg_"+index).remove();
		}
		if($("#up_wtg_hub_height_error_msg_"+index).html() != undefined) {
			$("#up_wtg_hub_height_error_msg_"+index).parent().removeClass('has-error');
			$("#up_wtg_hub_height_error_msg_"+index).remove();
		}
		if($("#up_land_per_form_error_msg_"+index).html() != undefined) {
			$("#up_land_per_form_error_msg_"+index).parent().removeClass('has-error');
			$("#up_land_per_form_error_msg_"+index).remove();
		}

		var wtg_location   		= $("#up_wtg_location_" + index).val() ? $("#up_wtg_location_" + index).val() : 0;
		var type_of_land   		= $("#up_type_of_land_" + index).val() ? $("#up_type_of_land_" + index).val() : 0;
		var land_survey_no  	= $("#up_land_survey_no_" + index).val() ? $("#up_land_survey_no_" + index).val() : 0;
		var land_area   		= $("#up_land_area_" + index).val() ? $("#up_land_area_" + index).val() : 0;
		var wtg_validity_date 	= $("#up_wtg_validity_date_" + index).val() ? $("#up_lwtg_validity_date_" + index).val() : 0;
		// var sub_lease_deed  = $("#up_sub_lease_deed_" + index).val() ? $("#up_sub_lease_deed_" + index).val() : 0;
		var geo_village  		= $("#up_geo_village_" + index).val() ? $("#up_geo_village_" + index).val() : 0;
		var geo_taluka   		= $("#up_geo_taluka_" + index).val() ? $("#up_geo_taluka_" + index).val() : 0;
		var geo_district 		= $("#up_geo_district_" + index).val() ? $("#up_geo_district_" + index).val() : 0;
		var zone 				= $("#up_zone_" + index).val() ? $("#up_zone_" + index).val() : 0;
		var x_cordinate 		= $("#up_x_cordinate_" + index).val() ? $("#up_x_cordinate_" + index).val() : 0;
		var y_cordinate 		= $("#up_y_cordinate_" + index).val() ? $("#up_y_cordinate_" + index).val() : 0;
		
		var wtg_make 			= $("#up_wtg_make_" + index).val() ? $("#up_wtg_make_" + index).val() : 0;
		var wtg_model 			= $("#up_wtg_model_" + index).val() ? parseFloat($("#up_wtg_model_" + index).val()) : 0;
		var wtg_capacity 		= $("#up_wtg_capacity_" + index).val() ? parseFloat($("#up_wtg_capacity_" + index).val()) : 0;
		var wtg_rotor_dimension = $("#up_wtg_rotor_dimension_" + index).val() ? parseFloat($("#up_wtg_rotor_dimension_" + index).val()) : 0;
		var wtg_hub_height 		= $("#up_wtg_hub_height_" + index).val() ? parseFloat($("#up_wtg_hub_height_" + index).val()) : 0;

		var land_per_form 			= $("#up_land_per_form_" + index).val() ? $("#up_land_per_form_" + index).val() : 0;
		if (land_per_form <= 0) {
			$("#up_land_per_form_" + index).parent().addClass('has-error');
			$("#up_land_per_form_" + index).parent().append('<div class="help-block land_per_form_error_msg_cls" id="up_land_per_form_error_msg_' + index + '">Required</div>');
		}
		var rlmm 		= $("#up_rlmm" + index).val();
		console.log('rlmm');
		if(rlmm == 'N'){
			if($("#up_wtg_make_n_error_msg_"+index).html() != undefined) {
				$("#up_wtg_make_n_error_msg_"+index).parent().removeClass('has-error');
				$("#up_wtg_make_n_error_msg_"+index).remove();
			}
			if($("#up_wtg_model_n_error_msg_"+index).html() != undefined) {
				$("#up_wtg_model_n_error_msg_"+index).parent().removeClass('has-error');
				$("#up_wtg_model_n_error_msg_"+index).remove();
			}
			if($("#up_wtg_capacity_n_error_msg_"+index).html() != undefined) {
				$("#up_wtg_capacity_n_error_msg_"+index).parent().removeClass('has-error');
				$("#up_wtg_capacity_n_error_msg_"+index).remove();
			}
			if($("#up_wtg_rotor_dimension_n_error_msg_"+index).html() != undefined) {
				$("#up_wtg_rotor_dimension_n_error_msg_"+index).parent().removeClass('has-error');
				$("#up_wtg_rotor_dimension_n_error_msg_"+index).remove();
			}
			if($("#up_wtg_hub_height_n_error_msg_"+index).html() != undefined) {
				$("#up_wtg_hub_height_n_error_msg_"+index).parent().removeClass('has-error');
				$("#up_wtg_hub_height_n_error_msg_"+index).remove();
			}
			if($("#up_wtg_file_error_msg_"+index).html() != undefined) {
				$("#up_wtg_file_error_msg_"+index).parent().removeClass('has-error');
				$("#up_wtg_file_error_msg_"+index).remove();
			}
			var wtg_make_n 				= $("#up_wtg_make_n_" + index).val() ? $("#up_wtg_make_n_" + index).val() : 0;
			var wtg_model_n 			= $("#up_wtg_model_n_" + index).val() ? $("#up_wtg_model_n_" + index).val() : 0;
			var wtg_capacity_n 			= $("#up_wtg_capacity_n_" + index).val() ? $("#up_wtg_capacity_n_" + index).val() : 0;
			var wtg_rotor_dimension_n 	= $("#up_wtg_rotor_dimension_n_" + index).val() ? $("#up_wtg_rotor_dimension_n_" + index).val() : 0;
			var wtg_hub_height_n 		= $("#up_wtg_hub_height_n_" + index).val() ? $("#up_wtg_hub_height_n_" + index).val() : 0;
			var wtg_file 				= $("#up_wtg_file_" + index).val() ? $("#up_wtg_file_" + index).val() : 0;
			if (wtg_file <= 0) {
				$("#up_wtg_file_" + index).parent().addClass('has-error');
				$("#up_wtg_file_" + index).parent().append('<div class="help-block wtg_file_error_msg_cls" id="up_wtg_file_error_msg_' + index + '">Required</div>');
			}
			if (wtg_make_n <= 0) {
				$("#up_wtg_make_n_" + index).parent().addClass('has-error');
				$("#up_wtg_make_n_" + index).parent().append('<div class="help-block wtg_make_n_error_msg_cls" id="up_wtg_make_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_model_n <= 0) {
				$("#up_wtg_model_n_" + index).parent().addClass('has-error');
				$("#up_wtg_model_n_" + index).parent().append('<div class="help-block wtg_model_n_error_msg_cls" id="up_wtg_model_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_capacity_n <= 0) {
				$("#up_wtg_capacity_n_" + index).parent().addClass('has-error');
				$("#up_wtg_capacity_n_" + index).parent().append('<div class="help-block wtg_capacity_n_error_msg_cls" id="up_wtg_capacity_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_rotor_dimension_n <= 0) {
				$("#up_wtg_rotor_dimension_n_" + index).parent().addClass('has-error');
				$("#up_wtg_rotor_dimension_n_" + index).parent().append('<div class="help-block wtg_rotor_dimension_n_error_msg_cls" id="up_wtg_rotor_dimension_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_hub_height_n <= 0) {
				$("#up_wtg_hub_height_n_" + index).parent().addClass('has-error');
				$("#up_wtg_hub_height_n_" + index).parent().append('<div class="help-block wtg_hub_height_n_error_msg_cls" id="up_wtg_hub_height_n_error_msg_' + index + '">Required</div>');
			}
		}
		

		if ( wtg_make <= 0 || wtg_model <= 0 || wtg_capacity <= 0 || wtg_rotor_dimension <= 0 || wtg_hub_height <= 0) {
			addRow = 0;
		}

		if (wtg_location <= 0) {
			$("#up_wtg_location_" + index).parent().addClass('has-error');
			$("#up_wtg_location_" + index).parent().append('<div class="help-block wtg_location_error_msg_cls" id="up_wtg_location_error_msg_' + index + '">Required</div>');
		}
		if (type_of_land <= 0) {
			$("#up_type_of_land_" + index).parent().addClass('has-error');
			$("#up_type_of_land_" + index).parent().append('<div class="help-block type_of_land_error_msg_cls" id="up_type_of_land_error_msg_' + index + '">Required</div>');
		}
		if (land_survey_no <= 0) {
			$("#up_land_survey_no_" + index).parent().addClass('has-error');
			$("#up_land_survey_no_" + index).parent().append('<div class="help-block land_survey_no_error_msg_cls" id="up_land_survey_no_error_msg_' + index + '">Required</div>');
		}
		if (land_area <= 0) {
			$("#up_land_area_" + index).parent().addClass('has-error');
			$("#up_land_area_" + index).parent().append('<div class="help-block land_area_error_msg_cls" id="up_land_area_error_msg_' + index + '">Required</div>');
		}
		if (wtg_validity_date <= 0) {
			$("#up_wtg_validity_date_" + index).parent().addClass('has-error');
			$("#up_wtg_validity_date_" + index).parent().append('<div class="help-block wtg_validity_date_error_msg_cls" id="up_wtg_validity_date_error_msg_' + index + '">Required</div>');
		}
		
		if (geo_village <= 0) {
			$("#up_geo_village_" + index).parent().addClass('has-error');
			$("#up_geo_village_" + index).parent().append('<div class="help-block geo_village_error_msg_cls" id="up_geo_village_error_msg_' + index + '">Required</div>');
		}
		if (geo_taluka <= 0) {
			$("#up_geo_taluka_" + index).parent().addClass('has-error');
			$("#up_geo_taluka_" + index).parent().append('<div class="help-block geo_taluka_error_msg_cls" id="up_geo_taluka_error_msg_' + index + '">Required</div>');
		}
		if (geo_district <= 0) {
			$("#up_geo_district_" + index).parent().addClass('has-error');
			$("#up_geo_district_" + index).parent().append('<div class="help-block geo_district_error_msg_cls" id="up_geo_district_error_msg_' + index + '">Required</div>');
		}
		if (zone <= 0) {
			$("#up_zone_" + index).parent().addClass('has-error');
			$("#up_zone_" + index).parent().append('<div class="help-block zone_error_msg_cls" id="up_zone_error_msg_' + index + '">Required</div>');
		}
		//var xpattern = /^\d{6}\.\d{0,3}$/;
		var xpattern = /^(\d{6}(\.\d{0,3})?)?$/;
		if(xpattern.test(x_cordinate)){

		}else{
			$("#up_x_cordinate_" + index).parent().addClass('has-error');
			$("#up_x_cordinate_" + index).parent().append('<div class="help-block x_cordinate_error_msg_cls" id="up_x_cordinate_error_msg_' + index + '">Value does not match the format "000000.000"</div>');
		}
		// if (x_cordinate < 19.00 || x_cordinate >  24.82) {
			
		// }
		//var ypattern = /^\d{7}\.\d{0,3}$/;
		var ypattern = /^(\d{7}(\.\d{0,3})?)?$/;
		if(ypattern.test(y_cordinate)){

		}else{
			$("#up_y_cordinate_" + index).parent().addClass('has-error');
			$("#up_y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="up_y_cordinate_error_msg_' + index + '">Value does not match the format "0000000.000"</div>');
		}
		// if (y_cordinate < 68.00 || y_cordinate > 74.62) {
		// 	$("#up_y_cordinate_" + index).parent().addClass('has-error');
		// 	$("#up_y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="y_cordinate_error_msg_' + index + '">Y-Coordinate Between 68.00 to 74.62</div>');
		// }
		if (wtg_make <= 0) {
			$("#up_wtg_make_" + index).parent().addClass('has-error');
			$("#up_wtg_make_" + index).parent().append('<div class="help-block wtg_make_error_msg_cls" id="up_wtg_make_error_msg_' + index + '">Required</div>');
		}
		if (wtg_model <= 0) {
			$("#up_wtg_model_" + index).parent().addClass('has-error');
			$("#up_wtg_model_" + index).parent().append('<div class="help-block wtg_model_error_msg_cls" id="up_wtg_model_error_msg_' + index + '">Required</div>');
		}
		if (wtg_capacity <= 0) {
			$("#up_wtg_capacity_" + index).parent().addClass('has-error');
			$("#up_wtg_capacity_" + index).parent().append('<div class="help-block wtg_capacity_error_msg_cls" id="up_wtg_capacity_error_msg_' + index + '">Required</div>');
		}
		if (wtg_rotor_dimension <= 0) {
			$("#up_wtg_rotor_dimension_" + index).parent().addClass('has-error');
			$("#up_wtg_rotor_dimension_" + index).parent().append('<div class="help-block wtg_rotor_dimension_error_msg_cls" id="up_wtg_rotor_dimension_error_msg_' + index + '">Required</div>');
		}
		if (wtg_hub_height <= 0) {
			$("#up_wtg_hub_height_" + index).parent().addClass('has-error');
			$("#up_wtg_hub_height_" + index).parent().append('<div class="help-block wtg_hub_height_error_msg_cls" id="up_wtg_hub_height_error_msg_' + index + '">Required</div>');
		}
		
	//});
}
function getTalukaFromDistrict_up(id,taluka) {
	var district= $("#up_geo_district_" + id).val();
	console.log(taluka);
	$.ajax({
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?= json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		type: "POST",
		url: "/GeoApplications/getTalukaFromDistrict",
		data: {"district":district},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#up_geo_taluka_"+ id).html('');
			$("#up_geo_taluka_"+ id).append($("<option />").val('').text('-Select Taluka-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					$("#up_geo_taluka_"+ id).append($("<option />").val(index).text(title));
				});
				//$('#up_geo_taluka_0').val('');
				if(taluka != '') {
					$("#up_geo_taluka_" + id+" option[value="+taluka+"]").attr("selected","selected");
				} else {
					$("#up_geo_taluka_"+ id).val('');
				}
				
			}
			//getVillageFromTaluka();
		}
	});
}
function rlmmchange_up(id) {
	console.log(id);
	rlmoption = $("#up_rlmm"+id).val();
	console.log(rlmoption);
	// Show or hide the text box based on the selected option
    if (rlmoption === 'N') {
        $('.N_data'+id).show();
        $('.N_data'+id).prop('disabled', false);
        $('.Y_data'+id).hide();
        $('.Y_data'+id).prop('disabled', true); // Enable the text box

        $('.N_data').show();
        $('.N_data').prop('disabled', false);
        $('.Y_data').hide();
        $('.Y_data').prop('disabled', true);

    } else if (rlmoption === 'Y') {
        $('.N_data'+id).hide();
        $('.N_data'+id).prop('disabled', true); // Disable the text box
        $('.Y_data'+id).show();
        $('.Y_data'+id).prop('disabled', false); 

        $('.N_data').hide();
        $('.N_data').prop('disabled', true); // Disable the text box
        $('.Y_data').show();
        $('.Y_data').prop('disabled', false); 
    }

}

function show_query_modal(id)
{
	console.log(id);
	$("#GeoRaisedQuery").modal("show");
	$.ajax({
				type: "POST",
				url: "/GeoApplications/getSavedData",
				data: {"id":id},
				
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					var responseData = $.parseJSON(result.message); 
					console.log(responseData);
					if (result.success == 1) {
						console.log(responseData);
						$('#q_geo_id_0').val(responseData.id);
						$('#query_raised_remark').val(responseData.query_raised_remark);
						
            			$('#q_type_of_land_0').val(responseData.type_of_land);
            			$('#q_land_survey_no_0').val(responseData.land_survey_no);
            			
            			$('#q_rlmm0').val(responseData.rlmm);
            			
            			var formattedDate = new Date(responseData.wtg_validity_date).toLocaleDateString();
            			$('#q_wtg_validity_date_0').val(formattedDate);

            			
            			if(responseData.rlmm == 'Y'){
            				$('#q_wtg_make_0').val(responseData.wtg_make);
	            			$("#q_wtg_model_0").append('<option selected value=' + responseData.wtg_model + '>' + responseData.wtg_model + '</option>');
	            			$('#q_wtg_capacity_0').append('<option selected value=' + responseData.wtg_capacity + '>' + responseData.wtg_capacity + '</option>');
	            			$('#q_wtg_rotor_dimension_0').append('<option selected value=' + responseData.wtg_rotor_dimension + '>' + responseData.wtg_rotor_dimension + '</option>');
	            			$('#q_wtg_hub_height_0').append('<option selected value=' + responseData.wtg_hub_height + '>' + responseData.wtg_hub_height + '</option>');
            			}
            			if(responseData.rlmm == 'N'){
	            			$('#q_wtg_make_n_0').val(responseData.wtg_make);
	            			$("#q_wtg_model_n_0").val(responseData.wtg_model);
	            			$('#q_wtg_capacity_n_0').val(responseData.wtg_capacity);
	            			$('#q_wtg_rotor_dimension_n_0').val(responseData.wtg_rotor_dimension);
	            			$('#q_wtg_hub_height_n_0').val(responseData.wtg_hub_height);
	            			$('.N_data').show();
					        $('.N_data').prop('disabled', false);
					        $('.Y_data').hide();
					        $('.Y_data').prop('disabled', true);
            			}
            			
				 	  	var path = responseData.path;
				 	  	console.log(path);
				 	  	$("#GeoRaisedQuery").find(".q_land_per_form").html('<a href="'+path+'" target="_blank"><i class="fa fa-eye"> View Land Permission</i></a>');
				 	  	//$("#q_land_per_form").html('<a href="'+path+'" target="_blank"><i class="fa fa-eye"> View Land Permission</i></a>');

					} 
					
				}
			});


}
$(".GeoRaisedQuery_btn").click(function() {
	var form = $('#GeoRaisedQueryForm0');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	
	var fromobj = $(this).attr("data-form-name");
	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];
	
		ValidateRow_up(indexvalue);
	
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_editquerydata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function(xhr){
					xhr.setRequestHeader(
						'X-CSRF-Token',
						<?php echo json_encode($this->request->param('_csrfToken')); ?>
					);
				},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoRaisedQueryForm0").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					
				}
			});

});

function changeMake_q(id,mid='',rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		url: "/GeoApplications/getModel",
		data: {
			"makeId": $('#q_wtg_make_' + id).val()
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?php echo json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result);
			$("#q_wtg_model_" + id).html("");
			$("#q_wtg_model_" + id).append($("<option />").val('').text('-Select WTG Model-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					if(title.toLowerCase() == mid.toLowerCase() && rid !='' && hid !='' && cid !=''){
						$("#q_wtg_model_" + id).append($("<option selected>").val(index).text(title));
						changemodel_up(id,rid,hid,cid);
					}else{
						$("#q_wtg_model_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
		}
	});
}

function changemodel_q(id,rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		async: false,
		url: "/GeoApplications/getModelDetails",
		data: {
			"modelNm": $('#q_wtg_model_' + id).val()
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?php echo json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#q_wtg_rotor_dimension_" + id).html("");
			$("#q_wtg_hub_height_" + id).html("");
			$("#q_wtg_capacity_" + id).html("");
			$("#q_wtg_validity_date_" + id).val("");
			
			$("#q_wtg_rotor_dimension_" + id).append($("<option />").val('').text('-Select WTG Rotor Dimension-'));
			$("#q_wtg_hub_height_" + id).append($("<option />").val('').text('-Select WTG Hub Height-'));
			$("#q_wtg_capacity_" + id).append($("<option />").val('').text('-Select WTG Capacity-'));
			
			if (result.data.rotor != undefined) {
				$.each(result.data.rotor, function(index, title) {
					if(title == rid){
						$("#q_wtg_rotor_dimension_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#q_wtg_rotor_dimension_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			if (result.data.hub != undefined) {
				$.each(result.data.hub, function(index, title) {
					if(title == hid){
						$("#q_wtg_hub_height_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#q_wtg_hub_height_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			if (result.data.capacity != undefined) {
				$.each(result.data.capacity, function(index, title) {
					if(title == cid){
						$("#q_wtg_capacity_" + id).append($("<option selected>").val(index).text(title));
					}else{
						$("#q_wtg_capacity_" + id).append($("<option />").val(index).text(title));
					}
				});
			}
			$("#q_wtg_validity_date_" + id).val(result.data.validity);
			$("#q_wtg_validity_date_" + id).prop("readonly", true);
		}
	});
}
function ValidateRow_up(index) {
	//$("#tbl_wind_info > tbody  > tr").each(function(index, tr) {
		if($("#q_wtg_location_error_msg_"+index).html() != undefined) {
			$("#q_wtg_location_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_location_error_msg_"+index).remove();
		}
		if($("#q_type_of_land_error_msg_"+index).html() != undefined) {
			$("#q_type_of_land_error_msg_"+index).parent().removeClass('has-error');
			$("#q_type_of_land_error_msg_"+index).remove();
		}
		if($("#q_land_survey_no_error_msg_"+index).html() != undefined) {
			$("#q_land_survey_no_error_msg_"+index).parent().removeClass('has-error');
			$("#q_land_survey_no_error_msg_"+index).remove();
		}
		if($("#q_land_area_error_msg_"+index).html() != undefined) {
			$("#q_land_area_error_msg_"+index).parent().removeClass('has-error');
			$("#q_land_area_error_msg_"+index).remove();
		}
		if($("#q_wtg_validity_date_error_msg_"+index).html() != undefined) {
			$("#q_wtg_validity_date_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_validity_date_error_msg_"+index).remove();
		}
		// if($("#q_sub_lease_deed_error_msg_"+index).html() != undefined) {
		// 	$("#q_sub_lease_deed_error_msg_"+index).parent().removeClass('has-error');
		// 	$("#q_sub_lease_deed_error_msg_"+index).remove();
		// }

		if($("#q_geo_village_error_msg_"+index).html() != undefined) {
			$("#q_geo_village_error_msg_"+index).parent().removeClass('has-error');
			$("#q_geo_village_error_msg_"+index).remove();
		}
		if($("#q_geo_taluka_error_msg_"+index).html() != undefined) {
			$("#q_geo_taluka_error_msg_"+index).parent().removeClass('has-error');
			$("#q_geo_taluka_error_msg_"+index).remove();
		}
		if($("#q_geo_district_error_msg_"+index).html() != undefined) {
			$("#q_geo_district_error_msg_"+index).parent().removeClass('has-error');
			$("#q_geo_district_error_msg_"+index).remove();
		}
		if($("#q_zone_error_msg_"+index).html() != undefined) {
			$("#q_zone_error_msg_"+index).parent().removeClass('has-error');
			$("#q_zone_error_msg_"+index).remove();
		}
		if($("#q_x_cordinate_error_msg_"+index).html() != undefined) {
			$("#q_x_cordinate_error_msg_"+index).parent().removeClass('has-error');
			$("#q_x_cordinate_error_msg_"+index).remove();
		}
		if($("#q_y_cordinate_error_msg_"+index).html() != undefined) {
			$("#q_y_cordinate_error_msg_"+index).parent().removeClass('has-error');
			$("#q_y_cordinate_error_msg_"+index).remove();
		}

		if($("#q_wtg_make_error_msg_"+index).html() != undefined) {
			$("#q_wtg_make_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_make_error_msg_"+index).remove();
		}
		if($("#q_wtg_model_error_msg_"+index).html() != undefined) {
			$("#q_wtg_model_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_model_error_msg_"+index).remove();
		}
		if($("#q_wtg_capacity_error_msg_"+index).html() != undefined) {
			$("#q_wtg_capacity_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_capacity_error_msg_"+index).remove();
		}
		if($("#q_wtg_rotor_dimension_error_msg_"+index).html() != undefined) {
			$("#q_wtg_rotor_dimension_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_rotor_dimension_error_msg_"+index).remove();
		}
		if($("#q_wtg_hub_height_error_msg_"+index).html() != undefined) {
			$("#q_wtg_hub_height_error_msg_"+index).parent().removeClass('has-error');
			$("#q_wtg_hub_height_error_msg_"+index).remove();
		}
		if($("#q_land_per_form_error_msg_"+index).html() != undefined) {
			$("#q_land_per_form_error_msg_"+index).parent().removeClass('has-error');
			$("#q_land_per_form_error_msg_"+index).remove();
		}

		var wtg_location   		= $("#q_wtg_location_" + index).val() ? $("#q_wtg_location_" + index).val() : 0;
		var type_of_land   		= $("#q_type_of_land_" + index).val() ? $("#q_type_of_land_" + index).val() : 0;
		var land_survey_no  	= $("#q_land_survey_no_" + index).val() ? $("#q_land_survey_no_" + index).val() : 0;
		var land_area   		= $("#q_land_area_" + index).val() ? $("#q_land_area_" + index).val() : 0;
		var wtg_validity_date 	= $("#q_wtg_validity_date_" + index).val() ? $("#q_lwtg_validity_date_" + index).val() : 0;
		// var sub_lease_deed  = $("#q_sub_lease_deed_" + index).val() ? $("#q_sub_lease_deed_" + index).val() : 0;
		var geo_village  		= $("#q_geo_village_" + index).val() ? $("#q_geo_village_" + index).val() : 0;
		var geo_taluka   		= $("#q_geo_taluka_" + index).val() ? $("#q_geo_taluka_" + index).val() : 0;
		var geo_district 		= $("#q_geo_district_" + index).val() ? $("#q_geo_district_" + index).val() : 0;
		var zone 				= $("#q_zone_" + index).val() ? $("#q_zone_" + index).val() : 0;
		var x_cordinate 		= $("#q_x_cordinate_" + index).val() ? $("#q_x_cordinate_" + index).val() : 0;
		var y_cordinate 		= $("#q_y_cordinate_" + index).val() ? $("#q_y_cordinate_" + index).val() : 0;
		
		var wtg_make 			= $("#q_wtg_make_" + index).val() ? $("#q_wtg_make_" + index).val() : 0;
		var wtg_model 			= $("#q_wtg_model_" + index).val() ? parseFloat($("#q_wtg_model_" + index).val()) : 0;
		var wtg_capacity 		= $("#q_wtg_capacity_" + index).val() ? parseFloat($("#q_wtg_capacity_" + index).val()) : 0;
		var wtg_rotor_dimension = $("#q_wtg_rotor_dimension_" + index).val() ? parseFloat($("#q_wtg_rotor_dimension_" + index).val()) : 0;
		var wtg_hub_height 		= $("#q_wtg_hub_height_" + index).val() ? parseFloat($("#q_wtg_hub_height_" + index).val()) : 0;

		var land_per_form 			= $("#q_land_per_form_" + index).val() ? $("#q_land_per_form_" + index).val() : 0;
		if (land_per_form <= 0) {
			$("#q_land_per_form_" + index).parent().addClass('has-error');
			$("#q_land_per_form_" + index).parent().append('<div class="help-block land_per_form_error_msg_cls" id="q_land_per_form_error_msg_' + index + '">Required</div>');
		}
		var rlmm 		= $("#q_rlmm" + index).val();
		console.log('rlmm');
		if(rlmm == 'N'){
			if($("#q_wtg_make_n_error_msg_"+index).html() != undefined) {
				$("#q_wtg_make_n_error_msg_"+index).parent().removeClass('has-error');
				$("#q_wtg_make_n_error_msg_"+index).remove();
			}
			if($("#q_wtg_model_n_error_msg_"+index).html() != undefined) {
				$("#q_wtg_model_n_error_msg_"+index).parent().removeClass('has-error');
				$("#q_wtg_model_n_error_msg_"+index).remove();
			}
			if($("#q_wtg_capacity_n_error_msg_"+index).html() != undefined) {
				$("#q_wtg_capacity_n_error_msg_"+index).parent().removeClass('has-error');
				$("#q_wtg_capacity_n_error_msg_"+index).remove();
			}
			if($("#q_wtg_rotor_dimension_n_error_msg_"+index).html() != undefined) {
				$("#q_wtg_rotor_dimension_n_error_msg_"+index).parent().removeClass('has-error');
				$("#q_wtg_rotor_dimension_n_error_msg_"+index).remove();
			}
			if($("#q_wtg_hub_height_n_error_msg_"+index).html() != undefined) {
				$("#q_wtg_hub_height_n_error_msg_"+index).parent().removeClass('has-error');
				$("#q_wtg_hub_height_n_error_msg_"+index).remove();
			}
			if($("#q_wtg_file_error_msg_"+index).html() != undefined) {
				$("#q_wtg_file_error_msg_"+index).parent().removeClass('has-error');
				$("#q_wtg_file_error_msg_"+index).remove();
			}
			var wtg_make_n 				= $("#q_wtg_make_n_" + index).val() ? $("#q_wtg_make_n_" + index).val() : 0;
			var wtg_model_n 			= $("#q_wtg_model_n_" + index).val() ? $("#q_wtg_model_n_" + index).val() : 0;
			var wtg_capacity_n 			= $("#q_wtg_capacity_n_" + index).val() ? $("#q_wtg_capacity_n_" + index).val() : 0;
			var wtg_rotor_dimension_n 	= $("#q_wtg_rotor_dimension_n_" + index).val() ? $("#q_wtg_rotor_dimension_n_" + index).val() : 0;
			var wtg_hub_height_n 		= $("#q_wtg_hub_height_n_" + index).val() ? $("#q_wtg_hub_height_n_" + index).val() : 0;
			var wtg_file 				= $("#q_wtg_file_" + index).val() ? $("#q_wtg_file_" + index).val() : 0;
			if (wtg_file <= 0) {
				$("#q_wtg_file_" + index).parent().addClass('has-error');
				$("#q_wtg_file_" + index).parent().append('<div class="help-block wtg_file_error_msg_cls" id="q_wtg_file_error_msg_' + index + '">Required</div>');
			}
			if (wtg_make_n <= 0) {
				$("#q_wtg_make_n_" + index).parent().addClass('has-error');
				$("#q_wtg_make_n_" + index).parent().append('<div class="help-block wtg_make_n_error_msg_cls" id="q_wtg_make_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_model_n <= 0) {
				$("#q_wtg_model_n_" + index).parent().addClass('has-error');
				$("#q_wtg_model_n_" + index).parent().append('<div class="help-block wtg_model_n_error_msg_cls" id="q_wtg_model_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_capacity_n <= 0) {
				$("#q_wtg_capacity_n_" + index).parent().addClass('has-error');
				$("#q_wtg_capacity_n_" + index).parent().append('<div class="help-block wtg_capacity_n_error_msg_cls" id="q_wtg_capacity_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_rotor_dimension_n <= 0) {
				$("#q_wtg_rotor_dimension_n_" + index).parent().addClass('has-error');
				$("#q_wtg_rotor_dimension_n_" + index).parent().append('<div class="help-block wtg_rotor_dimension_n_error_msg_cls" id="q_wtg_rotor_dimension_n_error_msg_' + index + '">Required</div>');
			}
			if (wtg_hub_height_n <= 0) {
				$("#q_wtg_hub_height_n_" + index).parent().addClass('has-error');
				$("#q_wtg_hub_height_n_" + index).parent().append('<div class="help-block wtg_hub_height_n_error_msg_cls" id="q_wtg_hub_height_n_error_msg_' + index + '">Required</div>');
			}
		}
		

		if ( wtg_make <= 0 || wtg_model <= 0 || wtg_capacity <= 0 || wtg_rotor_dimension <= 0 || wtg_hub_height <= 0) {
			addRow = 0;
		}

		if (wtg_location <= 0) {
			$("#q_wtg_location_" + index).parent().addClass('has-error');
			$("#q_wtg_location_" + index).parent().append('<div class="help-block wtg_location_error_msg_cls" id="q_wtg_location_error_msg_' + index + '">Required</div>');
		}
		if (type_of_land <= 0) {
			$("#q_type_of_land_" + index).parent().addClass('has-error');
			$("#q_type_of_land_" + index).parent().append('<div class="help-block type_of_land_error_msg_cls" id="q_type_of_land_error_msg_' + index + '">Required</div>');
		}
		if (land_survey_no <= 0) {
			$("#q_land_survey_no_" + index).parent().addClass('has-error');
			$("#q_land_survey_no_" + index).parent().append('<div class="help-block land_survey_no_error_msg_cls" id="q_land_survey_no_error_msg_' + index + '">Required</div>');
		}
		if (land_area <= 0) {
			$("#q_land_area_" + index).parent().addClass('has-error');
			$("#q_land_area_" + index).parent().append('<div class="help-block land_area_error_msg_cls" id="q_land_area_error_msg_' + index + '">Required</div>');
		}
		if (wtg_validity_date <= 0) {
			$("#q_wtg_validity_date_" + index).parent().addClass('has-error');
			$("#q_wtg_validity_date_" + index).parent().append('<div class="help-block wtg_validity_date_error_msg_cls" id="q_wtg_validity_date_error_msg_' + index + '">Required</div>');
		}
		
		if (geo_village <= 0) {
			$("#q_geo_village_" + index).parent().addClass('has-error');
			$("#q_geo_village_" + index).parent().append('<div class="help-block geo_village_error_msg_cls" id="q_geo_village_error_msg_' + index + '">Required</div>');
		}
		if (geo_taluka <= 0) {
			$("#q_geo_taluka_" + index).parent().addClass('has-error');
			$("#q_geo_taluka_" + index).parent().append('<div class="help-block geo_taluka_error_msg_cls" id="q_geo_taluka_error_msg_' + index + '">Required</div>');
		}
		if (geo_district <= 0) {
			$("#q_geo_district_" + index).parent().addClass('has-error');
			$("#q_geo_district_" + index).parent().append('<div class="help-block geo_district_error_msg_cls" id="q_geo_district_error_msg_' + index + '">Required</div>');
		}
		if (zone <= 0) {
			$("#q_zone_" + index).parent().addClass('has-error');
			$("#q_zone_" + index).parent().append('<div class="help-block zone_error_msg_cls" id="q_zone_error_msg_' + index + '">Required</div>');
		}
		//var xpattern = /^\d{6}\.\d{0,3}$/;
		var xpattern = /^(\d{6}(\.\d{0,3})?)?$/;
		if(xpattern.test(x_cordinate)){

		}else{
			$("#q_x_cordinate_" + index).parent().addClass('has-error');
			$("#q_x_cordinate_" + index).parent().append('<div class="help-block x_cordinate_error_msg_cls" id="q_x_cordinate_error_msg_' + index + '">Value does not match the format "000000.000"</div>');
		}
		// if (x_cordinate < 19.00 || x_cordinate >  24.82) {
			
		// }
		//var ypattern = /^\d{7}\.\d{0,3}$/;
		var ypattern = /^(\d{7}(\.\d{0,3})?)?$/;
		if(ypattern.test(y_cordinate)){

		}else{
			$("#q_y_cordinate_" + index).parent().addClass('has-error');
			$("#q_y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="q_y_cordinate_error_msg_' + index + '">Value does not match the format "0000000.000"</div>');
		}
		// if (y_cordinate < 68.00 || y_cordinate > 74.62) {
		// 	$("#q_y_cordinate_" + index).parent().addClass('has-error');
		// 	$("#q_y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="y_cordinate_error_msg_' + index + '">Y-Coordinate Between 68.00 to 74.62</div>');
		// }
		if (wtg_make <= 0) {
			$("#q_wtg_make_" + index).parent().addClass('has-error');
			$("#q_wtg_make_" + index).parent().append('<div class="help-block wtg_make_error_msg_cls" id="q_wtg_make_error_msg_' + index + '">Required</div>');
		}
		if (wtg_model <= 0) {
			$("#q_wtg_model_" + index).parent().addClass('has-error');
			$("#q_wtg_model_" + index).parent().append('<div class="help-block wtg_model_error_msg_cls" id="q_wtg_model_error_msg_' + index + '">Required</div>');
		}
		if (wtg_capacity <= 0) {
			$("#q_wtg_capacity_" + index).parent().addClass('has-error');
			$("#q_wtg_capacity_" + index).parent().append('<div class="help-block wtg_capacity_error_msg_cls" id="q_wtg_capacity_error_msg_' + index + '">Required</div>');
		}
		if (wtg_rotor_dimension <= 0) {
			$("#q_wtg_rotor_dimension_" + index).parent().addClass('has-error');
			$("#q_wtg_rotor_dimension_" + index).parent().append('<div class="help-block wtg_rotor_dimension_error_msg_cls" id="q_wtg_rotor_dimension_error_msg_' + index + '">Required</div>');
		}
		if (wtg_hub_height <= 0) {
			$("#q_wtg_hub_height_" + index).parent().addClass('has-error');
			$("#q_wtg_hub_height_" + index).parent().append('<div class="help-block wtg_hub_height_error_msg_cls" id="q_wtg_hub_height_error_msg_' + index + '">Required</div>');
		}
		
	//});
}
function rlmmchange_q(id) {
	console.log(id);
	rlmoption = $("#q_rlmm"+id).val();
	console.log(rlmoption);
	// Show or hide the text box based on the selected option
    if (rlmoption === 'N') {
        $('.N_data'+id).show();
        $('.N_data'+id).prop('disabled', false);
        $('.Y_data'+id).hide();
        $('.Y_data'+id).prop('disabled', true); // Enable the text box

        $('.N_data').show();
        $('.N_data').prop('disabled', false);
        $('.Y_data').hide();
        $('.Y_data').prop('disabled', true);

    } else if (rlmoption === 'Y') {
        $('.N_data'+id).hide();
        $('.N_data'+id).prop('disabled', true); // Disable the text box
        $('.Y_data'+id).show();
        $('.Y_data'+id).prop('disabled', false); 

        $('.N_data').hide();
        $('.N_data').prop('disabled', true); // Disable the text box
        $('.Y_data').show();
        $('.Y_data').prop('disabled', false); 
    }

}

function ValidateRow_offline(index) {
	//$("#tbl_wind_info > tbody  > tr").each(function(index, tr) {
		if($("#app_reg_no_off_error_msg_"+index).html() != undefined) {
			$("#app_reg_no_off_error_msg_"+index).parent().removeClass('has-error');
			$("#app_reg_no_off_error_msg_"+index).remove();
		}
		
		if($("#installer_name_off_error_msg_"+index).html() != undefined) {
			$("#installer_name_off_error_msg_"+index).parent().removeClass('has-error');
			$("#installer_name_off_error_msg_"+index).remove();
		}
		if($("#wtg_location_off_error_msg_"+index).html() != undefined) {
			$("#wtg_location_off_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_location_off_error_msg_"+index).remove();
		}
		if($("#offline_approved_date_error_msg_"+index).html() != undefined) {
			$("#offline_approved_date_error_msg_"+index).parent().removeClass('has-error');
			$("#offline_approved_date_error_msg_"+index).remove();
		}


		if($("#village_off_error_msg_"+index).html() != undefined) {
			$("#village_off_error_msg_"+index).parent().removeClass('has-error');
			$("#village_off_error_msg_"+index).remove();
		}
		if($("#taluka_off_error_msg_"+index).html() != undefined) {
			$("#taluka_off_error_msg_"+index).parent().removeClass('has-error');
			$("#taluka_off_error_msg_"+index).remove();
		}
		if($("#district_off_error_msg_"+index).html() != undefined) {
			$("#district_off_error_msg_"+index).parent().removeClass('has-error');
			$("#district_off_error_msg_"+index).remove();
		}
		if($("#zone_off_error_msg_"+index).html() != undefined) {
			$("#zone_off_error_msg_"+index).parent().removeClass('has-error');
			$("#zone_off_error_msg_"+index).remove();
		}
		if($("#x_cordinate_off_error_msg_"+index).html() != undefined) {
			$("#x_cordinate_off_error_msg_"+index).parent().removeClass('has-error');
			$("#x_cordinate_off_error_msg_"+index).remove();
		}
		if($("#y_cordinate_off_error_msg_"+index).html() != undefined) {
			$("#y_cordinate_off_error_msg_"+index).parent().removeClass('has-error');
			$("#y_cordinate_off_error_msg_"+index).remove();
		}

		

		var app_reg_no   		= $("#app_reg_no_off_" + index).val() ? $("#app_reg_no_off_" + index).val() : 0;
		
		var installer_name_off  	= $("#installer_name_off_" + index).val() ? $("#installer_name_off_" + index).val() : 0;
		var wtg_location_off   		= $("#wtg_location_off_" + index).val() ? $("#wtg_location_off_" + index).val() : 0;
		var offline_approved_date 	= $("#offline_approved_date_" + index).val() ? $("#loffline_approved_date_" + index).val() : 0;
		// var sub_lease_deed  = $("#sub_lease_deed_" + index).val() ? $("#sub_lease_deed_" + index).val() : 0;
		var village_off  		= $("#village_off_" + index).val() ? $("#village_off_" + index).val() : 0;
		var taluka_off   		= $("#taluka_off_" + index).val() ? $("#taluka_off_" + index).val() : 0;
		var district_off 		= $("#district_off_" + index).val() ? $("#district_off_" + index).val() : 0;
		var zone_off 				= $("#zone_off_" + index).val() ? $("#zone_off_" + index).val() : 0;
		var x_cordinate_off 		= $("#x_cordinate_off_" + index).val() ? $("#x_cordinate_off_" + index).val() : 0;
		var y_cordinate_off 		= $("#y_cordinate_off_" + index).val() ? $("#y_cordinate_off_" + index).val() : 0;
		
		
		
		
		if ( app_reg_no <= 0 || installer_name_off <= 0 || wtg_location_off <= 0 || offline_approved_date <= 0 || village_off <= 0 || taluka_off <= 0 || district_off <= 0 || zone_off <= 0 || x_cordinate_off <= 0 || y_cordinate_off <=0) {
			addRow = 0;
		}

		if (app_reg_no <= 0) {
			$("#app_reg_no_off_" + index).parent().addClass('has-error');
			$("#app_reg_no_off_" + index).parent().append('<div class="help-block app_reg_no_off_error_msg_cls" id="app_reg_no_off_error_msg_' + index + '">Required</div>');
		}
		
		if (installer_name_off <= 0) {
			$("#installer_name_off_" + index).parent().addClass('has-error');
			$("#installer_name_off_" + index).parent().append('<div class="help-block installer_name_off_error_msg_cls" id="installer_name_off_error_msg_' + index + '">Required</div>');
		}
		if (wtg_location_off <= 0) {
			$("#wtg_location_off_" + index).parent().addClass('has-error');
			$("#wtg_location_off_" + index).parent().append('<div class="help-block wtg_location_off_error_msg_cls" id="wtg_location_off_error_msg_' + index + '">Required</div>');
		}
		if (offline_approved_date <= 0) {
			$("#offline_approved_date_" + index).parent().addClass('has-error');
			$("#offline_approved_date_" + index).parent().append('<div class="help-block offline_approved_date_error_msg_cls" id="offline_approved_date_error_msg_' + index + '">Required</div>');
		}
		
		if (village_off <= 0) {
			$("#village_off_" + index).parent().addClass('has-error');
			$("#village_off_" + index).parent().append('<div class="help-block village_off_error_msg_cls" id="village_off_error_msg_' + index + '">Required</div>');
		}
		if (taluka_off <= 0) {
			$("#taluka_off_" + index).parent().addClass('has-error');
			$("#taluka_off_" + index).parent().append('<div class="help-block taluka_off_error_msg_cls" id="taluka_off_error_msg_' + index + '">Required</div>');
		}
		if (district_off <= 0) {
			$("#district_off_" + index).parent().addClass('has-error');
			$("#district_off_" + index).parent().append('<div class="help-block district_off_error_msg_cls" id="district_off_error_msg_' + index + '">Required</div>');
		}
		if (zone_off <= 0) {
			$("#zone_off_" + index).parent().addClass('has-error');
			$("#zone_off_" + index).parent().append('<div class="help-block zone_off_error_msg_cls" id="zone_off_error_msg_' + index + '">Required</div>');
		}
		
		var xpattern = /^(\d{6}(\.\d{0,3})?)?$/;
		if(xpattern.test(x_cordinate_off)){

		}else{
			$("#x_cordinate_off_" + index).parent().addClass('has-error');
			$("#x_cordinate_off_" + index).parent().append('<div class="help-block x_cordinate_off_error_msg_cls" id="x_cordinate_off_error_msg_' + index + '">Value does not match the format "000000.000"</div>');
		}
		
		var ypattern = /^(\d{7}(\.\d{0,3})?)?$/;
		if(ypattern.test(y_cordinate_off)){

		}else{
			$("#y_cordinate_off_" + index).parent().addClass('has-error');
			$("#y_cordinate_off_" + index).parent().append('<div class="help-block y_cordinate_off_error_msg_cls" id="y_cordinate_off_error_msg_' + index + '">Value does not match the format "0000000.000"</div>');
		}
		
		
		
	//});
}

function getTalukaFromDistrict_offline(id,taluka) {
	var district= $("#district_off_" + id).val();
	console.log(district);
	$.ajax({
		beforeSend: function(xhr){
			xhr.setRequestHeader(
				'X-CSRF-Token',
				<?= json_encode($this->request->param('_csrfToken')); ?>
			);
		},
		type: "POST",
		url: "/GeoApplications/getTalukaFromDistrict",
		data: {"district":district},
		success: function(response) {
			var result = $.parseJSON(response);
			$("#taluka_off_"+ id).html('');
			$("#taluka_off_"+ id).append($("<option />").val('').text('-Select Taluka-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					$("#taluka_off_"+ id).append($("<option />").val(index).text(title));
				});
				//$('#taluka_0').val('');
				if(taluka != '') {
					$("#taluka_off_" + id+" option[value="+taluka+"]").attr("selected","selected");
				} else {
					$("#taluka_off_"+ id).val('');
				}
				
			}
			//getVillageFromTaluka();
		}
	});
}

</script>