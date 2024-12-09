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
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> Shifting of WTG Cordinates</h4>
		<div class="col-md-4" style="margin-top:30px;text-align:right">
			<span style="font-size:18px;color:<?php echo $applicationCategory->color_code;?>">
				<strong style="text-align:left;"><?php echo isset($applicationCategory->category_name) ? $applicationCategory->category_name : '';?></strong></span><br>&nbsp;&nbsp;Application No.: <?php echo $Applications->application_no;?>
		</div>
		<div class="row" style="border-radius:5px; padding:20px;">
			<table class="table custom_table lable_left">
				<tbody>
					<tr>
						<td>
							
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
																	
																	<a href="/GeoShiftingApplication/downloadGeoApplicationShiftingVerifiedPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
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
		<?php if($is_member == true){?>
			<div class="col-md-3">
					<?php echo $this->Form->input('Generate WTG Shifting Verification PDF', array('label' => false, 'class' => ' btn btn-green green  GeoVerify','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoVerify")); ?>
					</div>
		<?php } ?>
		
		<div class="row">
		 	<div class="col-md-12 ">

				<div class="table table-responsive table-bordered noborder" >
					 
					 <table id= "tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead" >

					  	<tr class="thead-dark">
					  		<thead class="thead-dark">
					  		<th colspan="10" style="text-align:center;" >Details of WTG Coordinate Shifting </th>
					  		</thead>
					  	</tr>
					  	<tr >
					  		<td rowspan = "2"  style="text-align:center;" >Sr No </td>
					  		<td rowspan = "2"  style="text-align:center;" >WTG Location </td>
					  		<td rowspan = "2"  style="text-align:center;" >Land Survey No </td>
					  		<td colspan = "3"  style="text-align:center;" >Applied WTG Coordinates </td>
					  		<td colspan = "3"  style="text-align:center;" >Modified WTG Coordinates</td>
					  		<td rowspan = "2"  >Action <?php if($is_member == false){?><input type="checkbox" class="select_all check" id="0"/><?php }else{?><input type="checkbox" id="select-all" class = "verify" name="verify" ><?php }?></td>
					  	</tr>
					  	<tr>
					  		<td style="text-align:center;" >UTM Zone </td>
					  		<td style="text-align:center;" >UTM Easting </td>
					  		<td style="text-align:center;" >UTM Northing</td>
					  		<td style="text-align:center;" >UTM Zone </td>
					  		<td style="text-align:center;" >UTM Easting </td>
					  		<td style="text-align:center;" >UTM Northing</td>
					  	</tr>
					  	<tbody>
					  		<?php $counter = 1;
					  		foreach ($geo_application_data as $key => $value) {

					  			$internal_clashed_docs 	= $ApplicationGeoLocation->internal_clashed_docs($value['id']);
					  			$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
								// Key to check
								$keyToCheck = $value->zone;

								if (array_key_exists($keyToCheck, $zonearray)) {
								    // Display the value corresponding to the key
								     $zone = $zonearray[$keyToCheck]; 
								    // echo"<pre>"; print_r($zone); die();
								}
								if(!empty($value->geo_shifting_application['modified_zone'])){
									$keyToCheckM = $value->geo_shifting_application['modified_zone'];
									
									if (array_key_exists($keyToCheckM, $zonearray) && !empty($keyToCheckM) ) {
									    // Display the value corresponding to the key
									     $zoneM = $zonearray[$keyToCheckM]; 
									    // echo"<pre>"; print_r($zone); die();
									}

								}else{
									$zoneM = ''; 
								}
								
					  			?>
					  			
					  		<tr>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_application_id',array("type" => "text",'label' => false,'type'=>'hidden','class'=>'form-control','placeholder'=>'','id'=>'geo_application_id_'.$counter,'value'=>$geo_application_data[$key]['id'])); ?><?php echo $counter ?> </td>
						  		<td style="text-align:center;" ><?php echo $geo_application_data[$key]['wtg_location']?></td>
						  		<td style="text-align:center;" ><?php echo $geo_application_data[$key]['land_survey_no']?> </td>
						  		<td style="text-align:center;" ><?php echo $zone ?></td>
						  		<td style="text-align:center;" ><?php echo isset($geo_application_data[$key]['geo_shifting_application']['old_x_cordinate'])?$geo_application_data[$key]['geo_shifting_application']['old_x_cordinate']:$geo_application_data[$key]['x_cordinate']?></td>
						  		<td style="text-align:center;" ><?php echo isset($geo_application_data[$key]['geo_shifting_application']['old_y_cordinate'])?$geo_application_data[$key]['geo_shifting_application']['old_y_cordinate']:$geo_application_data[$key]['y_cordinate']?></td>
						  		<td style="text-align:center;" ><?php echo isset($zoneM) && !empty($zoneM)?$zoneM:'-'?> </td>

						  		<td style="text-align:center;" ><?php echo isset($geo_application_data[$key]['geo_shifting_application']['modified_x_cordinate'])?$geo_application_data[$key]['geo_shifting_application']['modified_x_cordinate']:'-'?> </td>

						  		<td style="text-align:center;" ><?php echo isset($geo_application_data[$key]['geo_shifting_application']['modified_y_cordinate'])?$geo_application_data[$key]['geo_shifting_application']['modified_y_cordinate']:'-'?></td>
						  		<td style="text-align:center;width:440px;" >
						  			<?php if($is_member == True){ ?>
										<?php if($geo_application_data[$key]['geo_shifting_application']['payment_status'] = 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 2  && $geo_application_data[$key]['geo_shifting_application']['approved'] != 3 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 4){?>
											<div class="col-md-12 row" > 
												<div class="col-md-3" >
											 	<?php echo $this->Form->input('Approve', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingApprove','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingApprove", 'data-id'=>$geo_application_data[$key]['id'])); ?>
											 	</div>
											 	<div class="col-md-3" >
									  			<?php echo $this->Form->input('Reject', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingReject','style'=>'color:white;background-color: #EA4335;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingReject", 'data-id'=>$geo_application_data[$key]['id'],'data-prod-id'=>$geo_application_data[$key]['geo_shifting_application']['id'])); ?>
									  			</div>
									  			<div class="col-md-3" style="margin-left: -15px;" >
									  			<?php echo $this->Form->input('Clash', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingClash','style'=>'color:white;background-color: #4285F4;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingClash", 'data-id'=>$geo_application_data[$key]['id'],'data-prod-id'=>$geo_application_data[$key]['geo_shifting_application']['id'])); ?>
									  			</div>
									  			<div class="col-md-3" style="margin-left: -15px;"  >
									  			<?php echo $this->Form->input('Internal Clashed', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingClashInternal','style'=>'color:white;background-color: #FBBC05;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingClashInternal", 'data-id'=>$geo_application_data[$key]['id'],'data-prod-id'=>$geo_application_data[$key]['geo_shifting_application']['id'])); ?>
									  			</div>
								  			</div> 
										<?php }else if($geo_application_data[$key]['geo_shifting_application']['approved'] == 4 && isset($internal_clashed_docs->clashed_geo_id) && !empty($internal_clashed_docs->uploadfile)){
												$path = Internal_Clashed_PATH.$internal_clashed_docs->clashed_geo_id.'/'.$internal_clashed_docs->uploadfile;?>
													&nbsp;&nbsp;
													<a href="<?php echo URL_HTTP.'app-docs/Internal_clashed_uploadfile/'.encode($internal_clashed_docs->clashed_geo_id); ?>" target="_blank"><i class="fa fa-eye"> View Internal Clashed Upload File</i></a>
													<div class="col-md-12 row" > 
														<div class="col-md-3" >
													 	<?php echo $this->Form->input('Approve', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingApprove','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingApprove", 'data-id'=>$geo_application_data[$key]['id'])); ?>
													 	</div>
													 	<div class="col-md-3" >
											  			<?php echo $this->Form->input('Reject', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingReject','style'=>'color:white;background-color: #EA4335;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingReject", 'data-id'=>$geo_application_data[$key]['id'],'data-prod-id'=>$geo_application_data[$key]['geo_shifting_application']['id'])); ?>
											  			</div>
											  			<div class="col-md-3" style="margin-left: -15px;" >
											  			<?php echo $this->Form->input('Clash', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingClash','style'=>'color:white;background-color: #4285F4;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingClash", 'data-id'=>$geo_application_data[$key]['id'],'data-prod-id'=>$geo_application_data[$key]['geo_shifting_application']['id'])); ?>
											  			</div>
											  			<div class="col-md-3" style="margin-left: -15px;"  >
											  			<?php echo $this->Form->input('Internal Clashed', array('label' => false,'class' => ' btn  btn-sm  GeoShiftingClashInternal','style'=>'color:white;background-color: #FBBC05;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoShiftingClashInternal", 'data-id'=>$geo_application_data[$key]['id'],'data-prod-id'=>$geo_application_data[$key]['geo_shifting_application']['id'])); ?>
											  			</div><br><br>
								  					</div> 
															
										<?php	}else if($geo_application_data[$key]['geo_shifting_application']['payment_status'] = 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 4 && empty($internal_clashed_docs->uploadfile)){?>
												<span onclick="javascript:show_shifting_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-success bold"><p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p></span>
												
												<?php if(isset($geo_application_data[$key]['geo_shifting_application']['wtg_verified']) && $geo_application_data[$key]['geo_shifting_application']['wtg_verified'] == 1){ ?>
						  								<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Verified </span>
						  						<?php } else{ ?>
						  							<input type="checkbox" id="<?php echo $geo_application_data[$key]['id']?>" class = 'verify' name="verify">
						  						<?php } ?>
												
										<?php	}else if($geo_application_data[$key]['geo_shifting_application']['payment_status'] = 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 3){?>
												<span onclick="javascript:show_shifting_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-success bold"><p style="text-decoration: underline;color: #307FE2;">Clashing</p> </span>

												<?php if(isset($geo_application_data[$key]['geo_shifting_application']['wtg_verified']) && $geo_application_data[$key]['geo_shifting_application']['wtg_verified'] == 1){ ?>
						  								<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Verified </span>
						  						<?php } else{ ?>
						  							<input type="checkbox" id="<?php echo $geo_application_data[$key]['id']?>" class = 'verify' name="verify">
						  						<?php } ?>
										<?php	}else if($geo_application_data[$key]['geo_shifting_application']['payment_status'] = 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 1){?>
												<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Approved </span>

												<?php if(isset($geo_application_data[$key]['geo_shifting_application']['wtg_verified']) && $geo_application_data[$key]['geo_shifting_application']['wtg_verified'] == 1){ ?>
						  								<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Verified </span>
						  						<?php } else{ ?>
						  							<input type="checkbox" id="<?php echo $geo_application_data[$key]['id']?>" class = 'verify' name="verify">
						  						<?php } ?>
										<?php	}else if($geo_application_data[$key]['geo_shifting_application']['payment_status'] = 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 2){?>
												
												<i class="fa fa-times" aria-hidden="true"></i> <span  onclick="javascript:show_reason('<?php echo $geo_application_data[$key]['geo_shifting_application']['id']?>');" class="text-danger bold" title="">Rejected </span>

												<?php if(isset($geo_application_data[$key]['geo_shifting_application']['wtg_verified']) && $geo_application_data[$key]['geo_shifting_application']['wtg_verified'] == 1){ ?>
						  								<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Verified </span>
						  						<?php } else{ ?>
						  							<input type="checkbox" id="<?php echo $geo_application_data[$key]['id']?>" class = 'verify' name="verify">
						  						<?php } ?>
										<?php	}
									}else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] != 1){
										 echo $this->Form->input('Modify WTG Coordinates', array('label' => false, 'class' => ' btn btn-secondary btn-sm  ModifyWTG','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#ModifyWTG", 'data-id'=>$geo_application_data[$key]['id'] , 'data-prod-id'=>$geo_application_data[$key]['application_id'])); 
										if(	!empty($geo_application_data[$key]['geo_shifting_application']['modified_x_cordinate']) ){
										 echo $this->form->input('Pay', array('type'=>'checkbox','class'=>'checkbox check','value'=>$geo_location_charges,'id'=>$geo_application_data[$key]['id']));	
										}				  		
									}else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] == 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 2 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 3 && $geo_application_data[$key]['geo_shifting_application']['approved'] != 4){
										echo 'Submitted';
									}else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] == 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 4 && !empty($internal_clashed_docs->uploadfile)){?>
										<span onclick="javascript:show_shifting_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-success bold"><p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p> </span>
									<?php }else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] == 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 4 && empty($internal_clashed_docs->uploadfile)){?>
										<span onclick="javascript:show_shifting_internal_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-success bold"><p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p> </span>
									<?php }else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] == 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 3){?>
										<span onclick="javascript:show_shifting_clash_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-success bold"><p style="text-decoration: underline;color: #307FE2;">Clashing</p></span>
									<?php }else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] == 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 1){?>
										<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">Approved </span>
									<?php }else if($is_member == false && $geo_application_data[$key]['geo_shifting_application']['payment_status'] == 1 && $geo_application_data[$key]['geo_shifting_application']['approved'] == 2){?>
										<i class="fa fa-times" aria-hidden="true"></i> <span  onclick="javascript:show_reason('<?php echo $geo_application_data[$key]['geo_shifting_application']['id']?>');" class="text-danger bold" title="">Rejected </span>
								<?php	}?>
							  	</td>
						  	</tr>
						  <?php $counter++; }?>
					  	</tbody>	
					</table>
				</div>
			</div>

		</div>
	</div>

	<div id="ModifyWTG" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modify WTG Coordinates</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('ModifyWTGForm',['name'=>'ModifyWTGForm','id'=>'ModifyWTGForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
						<?php echo $this->Form->input('ModifyWTG_geo_application_id',['id'=>'ModifyWTG_geo_application_id','label' => true,'type'=>'hidden']); ?>
						<?php echo $this->Form->input('ModifyWTG_application_id',['id'=>'ModifyWTG_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
								<div class="col-md-3">
									<lable>UTM Zone </lable>
									<?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control', 'id' => 'zone')); ?>
									
								</div>
								<div class="col-md-3">
									<lable>UTM Easting </lable>
									<?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateEastingDecimalInput(this)','id' => 'x_cordinate', 'placeholder'=>'UTM Easting')); ?>
									
								</div>
								<div class="col-md-3">
									<lable>UTM Northing</lable>
									<?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateNorthingDecimalInput(this)','id' => 'y_cordinate', 'placeholder'=>'UTM Northing')); ?>
									
								</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<?php echo $this->Form->input('Submit',['type'=>'button','label'=>false,'class'=>'btn btn-primary ModifyWTG_btn','data-form-name'=>'ModifyWTGForm']); ?>
							</div>
						</div>
						<?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div id="GeoShiftingApprove" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to Approve?</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoShiftingApproveForm',['name'=>'GeoShiftingApproveForm','id'=>'GeoShiftingApproveForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoShiftingApprove_geo_application_id',['id'=>'GeoShiftingApprove_geo_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<?php echo $this->Form->input('Submit',['id'=>'GeoShiftingApprove_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoShiftingApprove_btn button-right','data-form-name'=>'GeoShiftingApproveForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="GeoShiftingReject" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reject Geo Shifting Modifaction</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoShiftingRejectForm',['name'=>'GeoShiftingRejectForm','id'=>'GeoShiftingRejectForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoShiftingReject_geo_application_id',['id'=>'GeoShiftingReject_geo_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('GeoShiftingReject_shifting_id',['id'=>'GeoShiftingReject_shifting_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
								<lable>Reason to Reject </lable>
								
								<?php echo $this->Form->textarea('reject_reason', array('label' => false,'class'=>'form-control','placeholder'=>'Reason to Reject','id'=>'reject_reason')); ?>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-12">
								<?php echo $this->Form->input('Submit',['id'=>'GeoShiftingReject_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoShiftingReject_btn button-right','data-form-name'=>'GeoShiftingRejectForm']); ?>
							</div>
						</div>
					</div>
					

					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoShiftingReasonReject" class="modal" role="dialog" >
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
	<div id="GeoShiftingClash" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Clashed Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoShiftingClashForm',['name'=>'GeoShiftingClashForm','id'=>'GeoShiftingClashForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoShiftingClash_geo_id',['id'=>'GeoShiftingClash_geo_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('GeoShiftingClash_shifting_id',['id'=>'GeoShiftingClash_shifting_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12">
								<lable>Geo Clashed  </lable>
								<?php echo $this->Form->select('approved_geo_id', $LocationList, array('label' => false, 'class' => 'form-control chosen-select','multiple' => 'multiple','empty' =>'-Select Location-', 'id' => 'approved_geo_id'.$counter)); ?>
							</div>
							<div class="col-md-12" style="margin-top: 20px;">
								<?php echo $this->Form->textarea('clashed_remark', array('label' => false,'class'=>'form-control','placeholder'=>'Clashed Remark','id'=>'clashed_remark')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'GeoShiftingClash_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoShiftingClash_btn button-right','data-form-name'=>'GeoShiftingClashForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>	
	<div id="GeoShiftingClashInternal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Internal Clashed Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoShiftingClashInternalForm',['name'=>'GeoShiftingClashInternalForm','id'=>'GeoShiftingClashInternalForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoShiftingClashInternal_geo_id',['id'=>'GeoShiftingClashInternal_geo_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('GeoShiftingClashInternal_shifting_id',['id'=>'GeoShiftingClashInternal_shifting_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12">
								<lable>Geo Clashed  </lable>
								<?php echo $this->Form->select('approved_geo_id', $LocationList_internal, array('label' => false, 'class' => 'form-control chosen-select','multiple' => 'multiple','empty' =>'-Select Location-', 'id' => 'approved_geo_id')); ?>
							</div>
							<div class="col-md-12" style="margin-top: 20px;">
								<?php echo $this->Form->textarea('internal_clashed_remark', array('label' => false,'class'=>'form-control','placeholder'=>'Internal Clashed Remark ','id'=>'internal_shifting_clashed_remark')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'GeoShiftingClashInternal_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoShiftingClashInternal_btn button-right','data-form-name'=>'GeoShiftingClashInternalForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>		
	<?php if($is_member == false){?>
		<div class="row" style="border-radius:5px; padding:20px;">
			<table class="table custom_table lable_left">
				<tbody>
					<tr>
						<td>
							
							<div class="col-md-6 m-2">
								<?php echo $this->Form->create('PaymentForm',['type'=>'file','name'=>'PaymentForm','id'=>'PaymentForm','class'=>'PaymentForm','url' => '/GeoShiftingPayment/make-payment/'.$id,'autocomplete'=>'off']);
								echo $this->Form->input('Paymentapplication_id',['label' => false,'type'=>'hidden','value'=>$id]);
									echo $this->Form->input('Paymentapplication_type',['label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
								?>
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">Payment For Shifting WTG Cordinates</legend>
									
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
																	
																	<a href="/GeoShiftingApplication/downloadGeoShiftingApplicationPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
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
	<div id="agree_popup" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Income Tax TDS Terms</h4>
				</div>
				<div class="modal-body">
					Amount Deducted as TDS should be deposited with government and e-tds return should be filed in prescribed time limit.
					<div id="message_error"></div>
					<br>
					If failed to do so, penalty of equal amount of TDS will be charged.
					<br><br>
					<div class="row">
						<div class="col-md-12">
						<?php echo $this->Form->input('I Agree',['type'=>'button','label'=>false,'class'=>'btn btn-primary button-right','onclick'=>'agreeClick();']); ?>
						</div>
					</div>
					
				</div>
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
																	
																	<a href="/GeoShiftingApplication/downloadGeoShiftingApplicationPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
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
	<div class="row col-md-12">
				<div class="col-md-3">
					<?php echo $this->Html->link('Back',['controller'=>'','action' => 'applications-list'],['class'=>'next btn btn-primary btn-md  cbtnsendmsg btn-default']); ?>
				</div>
			</div>
	<div id="GeoReasonClashedData" class="modal" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
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
	$("#uploadfile").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-s",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#uploadfile-file-errors',
		maxFileSize: '1024',
	});
function show_reason(shifting_id)
{
	console.log(shifting_id);
	
	$.ajax({
				type: "POST",
				url: "/GeoShiftingApplication/rejectedData",
				data: {"shifting_id":shifting_id},
				
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
						$("#GeoShiftingReasonReject").find(".modal-body").html(result.message);
						$("#GeoShiftingReasonReject").modal("show");
					} 
					else {
						$("#GeoShiftingReasonReject").modal("show");
					}
				}
			});

}
$(".ModifyWTG").click(function(){
	var geo_application_id = $(this).attr("data-id");
	var application_id = $(this).attr("data-prod-id");
	$("#ModifyWTG_geo_application_id").val(geo_application_id);
	$("#ModifyWTG_application_id").val(application_id);
});
$(".ModifyWTG_btn").click(function() {
	var form = $('#ModifyWTGForm');
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
				url: "/GeoShiftingApplication/Add_ModifyWTG",
				data: formdata ? formdata : form .serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ModifyWTGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#ModifyWTGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".ModifyWTG_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoShiftingApprove").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoShiftingApprove_geo_application_id").val(application_id);
});
$(".GeoShiftingApprove_btn").click(function() {
	var form = $('#GeoShiftingApproveForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoShiftingApplication/geo_shifting_approvedata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoShiftingApproveForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoShiftingApproveForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoShiftingApprove_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoShiftingReject").click(function(){

	var geo_application_id = $(this).attr("data-id");
	var shifting_id = $(this).attr("data-prod-id");
	$("#GeoShiftingReject_geo_application_id").val(geo_application_id);
	$("#GeoShiftingReject_shifting_id").val(shifting_id);
});
$(".GeoShiftingReject_btn").click(function() {
	var form = $('#GeoShiftingRejectForm');
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
				url: "/GeoShiftingApplication/geo_shifting_rejectdata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoShiftingRejectForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoShiftingRejectForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoShiftingReject_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoShiftingClash").click(function(){
	var application_id = $(this).attr("data-id");
	var shifting_id = $(this).attr("data-prod-id");
	
	console.log(application_id);
	$("#GeoShiftingClash_geo_id").val(application_id);
	$("#GeoShiftingClash_shifting_id").val(shifting_id);
});
$(".GeoShiftingClash_btn").click(function() {
	var form = $('#GeoShiftingClashForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");
	var clashed_remark = $("#"+fromobj).find("#clashed_remark").val();
	
	if($("#"+fromobj).find("#clashed_remark").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Clashed Remark is required field.");
		return false;
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoShiftingApplication/geo_location_shifting_clashdata",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoShiftingClashForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoShiftingClashForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoShiftingClash_btn").removeAttr('disabled');
					}
				}
			});

});
$(".GeoShiftingClashInternal").click(function(){
	var application_id = $(this).attr("data-id");
	var shifting_id = $(this).attr("data-prod-id");
	
	$("#GeoShiftingClashInternal_geo_id").val(application_id);
	$("#GeoShiftingClashInternal_shifting_id").val(shifting_id);
});
$(".GeoShiftingClashInternal_btn").click(function() {
	var form = $('#GeoShiftingClashInternalForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");
	var internal_clashed_remark = $("#"+fromobj).find("#internal_clashed_remark").val();
	
	if($("#"+fromobj).find("#internal_clashed_remark").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Internal Clashed Remark is required field.");
		return false;
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoShiftingApplication/geo_location_clashdata_internal",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoShiftingClashInternalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoShiftingClashInternalForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoShiftingClashInternal_btn").removeAttr('disabled');
					}
				}
			});

});

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

$(document).ready(function(){
    $checks = $(".check");
    $checks.on('change', function() {
        var string = $checks.filter(":checked").map(function(i,v){
            return this.id;
        }).get().join(",");
        console.log(string);
        $('#geo_id').val(string);
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

    $verify = $(".verify");
    $verify.on('change', function() {
        var string = $verify.filter(":checked").map(function(i,v){
            return this.id;
        }).get().join(",");
        console.log(string);
        $('#GeoVerify_application_id').val(string);
    });

    $('#select-all').change(function(){
	
	   // If "Select All" checkbox is checked, select all checkboxes; otherwise, deselect all checkboxes
	    $('.verify').prop('checked', $(this).prop('checked'));
		$verify = $(".verify");
	    var selectedIds = $verify.filter(":checked").map(function(i,v){
	            return this.id;
	        }).get().join(",");
	    var textToRemove = "select-all,";
	    var newString = selectedIds.replace(textToRemove, "");
	        console.log(newString);
	    $('#GeoVerify_application_id').val(newString);
	   // return selectedIds;
	});

	$('.chosen-select').chosen({
        //disable_search_threshold: 10,
        search_contains:true,
        width: '100%'
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
function show_shifting_clash_reason(geo_id)
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
function show_shifting_internal_clash_reason(geo_id)
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
				url: "/GeoShiftingApplication/geo_location_shifting_verifydata",
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
</script>