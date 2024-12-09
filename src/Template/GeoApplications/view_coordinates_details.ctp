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
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<div class="container-fluid applications-from">
	<div class="row col-md-12" style="margin-bottom: 20px;">
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> WTG Coordinates </h4>
		<div class="col-md-4" style="margin-top:30px;text-align:right">
			<span style="font-size:18px;color:<?php echo $applicationCategory->color_code;?>">
				<strong style="text-align:left;"><?php echo isset($applicationCategory->category_name) ? $applicationCategory->category_name : '';?></strong></span><br>&nbsp;&nbsp;Application No.: <?php echo $applyOnlinesData->application_no;?>
				<br>&nbsp;&nbsp;Geo Application Submitted No.: <?php echo date('d-M-Y',strtotime($geo_location_data->payment_date)); ?> 
				<br>&nbsp;&nbsp;Provisional No.: <?php echo $applyOnlinesData->registration_no;?>
		</div>
	</div>
	<div class="row" style="border-radius:5px; padding:20px;">
		<table class="table custom_table lable_left">
			<tbody>
				<tr>
					<td>
						<div class="col-md-6 m-2">
							<fieldset class="fieldset">
								<legend class="fieldset-legends legend-width" style="width: 75%;">Add the WTG Coordinates</legend>
								<lable class="col-md-6" style="margin-top: 10px;">Coordinates can Add</lable>
									<div class="col-md-6">
										<?php echo $this->Form->input('geo_cordinate_file', array('label' => false,'class'=>'form-control','placeholder'=>'','value'=>$total_wtg_application ,'readonly'=>'readonly')); ?>
									</div>
							</fieldset>
						</div>
						<div class="col-md-6 m-2">
							<fieldset class="fieldset">
								<legend class="fieldset-legends legend-width"  style="width: 75%;">Applied WTG Coordinates</legend>
								<lable class="col-md-6" style="margin-top: 10px;">Applied Coordinates</lable>
									<div class="col-md-6">
										<?php echo $this->Form->input('', array('label' => false,'class'=>'form-control','placeholder'=>'','value'=> count($geo_application_data) ,'readonly'=>'readonly')); ?>
									</div>
							</fieldset>
						</div>
				
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="row">
	 	<div class="col-md-12 ">
			<div >
				<div class="form-group text">
					
					
						<div class="row">
							
							<div class="col-md-3">
								<lable style="font-weight: bold;">WTG Location</lable>
								<?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control ','placeholder'=>'wtg_location', 'value'=> (isset($geo_location_data->wtg_location) ? $geo_location_data->wtg_location : '') ,'readonly'=>'readonly')); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->input('type_of_land',array("type" => "text",'label' => true,'class'=>'form-control ','placeholder'=>'type_of_land', 'value'=>(isset($geo_location_data->type_of_land) ? $geo_location_data->type_of_land : '') ,'readonly'=>'readonly')); ?>
								</div>
							<div class="col-md-3">
								<?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => true,'class'=>'form-control ','placeholder'=>'land_survey_no', 'value'=>(isset($geo_location_data->land_survey_no) ? $geo_location_data->land_survey_no : '') ,'readonly'=>'readonly')); ?>
								</div>
							<div class="col-md-3">
								<lable style="font-weight: bold;">Land Area in sq. mtr</lable>
								<?php echo $this->Form->input('land_area',array("type" => "text",'label' => false,'class'=>'form-control ','placeholder'=>'land_area', 'value'=>(isset($geo_location_data->land_area) ? $geo_location_data->land_area : '') ,'readonly'=>'readonly')); ?>
								</div>
						</div>
						<div class="row">

							<div class="col-md-3">
								<lable style="font-weight: bold;">District</lable>
								<?php echo $this->Form->input('geo_district',array("type" => "text",'label' => false,'class'=>'form-control ','placeholder'=>'District', 'value'=>(isset($district) ? $district : '') ,'readonly'=>'readonly')); ?>
								
							</div>
							<div class="col-md-3">
								<lable style="font-weight: bold;">Taluka</lable>
								<?php echo $this->Form->input('geo_taluka',array("type" => "text",'label' => false,'class'=>'form-control ','placeholder'=>'Taluka', 'value'=>(isset($taluka) ? $taluka : '') ,'readonly'=>'readonly')); ?>

								
							</div>
							
							<div class="col-md-3">
								<lable style="font-weight: bold;">Village</lable>
								<?php echo $this->Form->input('geo_village',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','placeholder'=>'Village', 'value'=>(isset($geo_location_data->geo_village) ? $geo_location_data->geo_village : '') ,'readonly'=>'readonly')); ?>
							</div>
							<div class="col-md-3">
								<?php echo $this->Form->input('zone', array('label' => true ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline zone', 'value'=>(isset($zone) ? $zone : '') ,'readonly'=>'readonly')); ?>
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<lable style="font-weight: bold;">UTM Easting</lable>
								<?php echo $this->Form->input('UTM Easting',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateEastingDecimalInput(this)', 'value'=>(isset($geo_location_data->x_cordinate) ? $geo_location_data->x_cordinate : '') ,'placeholder'=>'UTM Easting','readonly'=>'readonly')); ?>
								
							</div>
							<div class="col-md-3">
								<lable style="font-weight: bold;">UTM Northing</lable>
								<?php echo $this->Form->input('UTM Northing',array("type" => "text",'label' => false,'class'=>'form-control','oninput'=>'validateNorthingDecimalInput(this)', 'value'=>(isset($geo_location_data->y_cordinate) ? $geo_location_data->y_cordinate : '') , 'placeholder'=>'UTM Northing','readonly'=>'readonly')); ?>
								
							</div>
							<div class="col-md-3" >
								<lable style="font-weight: bold;">RLMM</lable>
								
								<?php echo $this->Form->input('RLMM', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline rlmm', 'value'=>(isset($geo_location_data->rlmm) ? $geo_location_data->rlmm : '') ,'readonly'=>'readonly')); ?>
							</div>
							<div class="col-md-3" >
								<lable style="font-weight: bold;">RLMM Validity</lable>
								<?php echo $this->Form->input('Rlmm Validity', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_validity_date', 'value'=>(isset($geo_location_data->wtg_validity_date) ? $geo_location_data->wtg_validity_date : '') ,'readonly'=>'readonly')); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<lable style="font-weight: bold;">WTG Make</lable>
								<?php echo $this->Form->input('wtg_make', array('label' => false ,'div'=>true,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_make', 'value'=>(isset($wtg_make) ? $wtg_make : '') ,'readonly'=>'readonly')); ?>
								
							</div>
							<div class="col-md-3">
								<lable style="font-weight: bold;">WTG Model</lable>
								<?php echo $this->Form->input('wtg_model', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_model', 'value'=>(isset($geo_location_data->wtg_model) ? $geo_location_data->wtg_model : '') ,'readonly'=>'readonly')); ?>
							</div>
							<div class="col-md-3">
								<lable style="font-weight: bold;">WTG Capacity (Kw)</lable>
								<?php echo $this->Form->input('wtg_capacity', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_capacity', 'value'=>(isset($geo_location_data->wtg_capacity) ? $geo_location_data->wtg_capacity : '') ,'readonly'=>'readonly')); ?>
							</div>
							<div class="col-md-3">
								<lable style="font-weight: bold;">WTG Rotor Dimension (M)</lable>
								<?php echo $this->Form->input('wtg_rotor_dimension', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_rotor_dimension', 'value'=>(isset($geo_location_data->wtg_rotor_dimension) ? $geo_location_data->wtg_rotor_dimension : '') ,'readonly'=>'readonly')); ?>
							</div>
						</div>
						<div class="row ">
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								<lable style="font-weight: bold;">WTG Hub Height (M)</lable>
								<?php echo $this->Form->input('wtg_hub_height', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline wtg_hub_height', 'value'=>(isset($geo_location_data->wtg_hub_height) ? $geo_location_data->wtg_hub_height : '') ,'readonly'=>'readonly')); ?>
							</div>
							<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
								
								<?php if(!empty($geo_location_data['land_per_form'])){ 
										$path = WTG_PATH.$geo_location_data['id'].'/'.$geo_location_data['land_per_form'];?>
										<lable>Consent Letter from Landowner </lable>
									&nbsp;&nbsp;
									<!-- class="Land" -->
									<a  href="<?php echo URL_HTTP.'app-docs/land_per_form/'.encode($geo_location_data['id']); ?>" target="_blank"><i class="fa fa-eye"> View Uploaded Form</i></a>
						  			<?php } ?>
							</div>
							<?php if(!empty($geo_location_data['wtg_file'])){ 
						  				$path = WTG_PATH.$geo_location_data['id'].'/'.$geo_location_data['wtg_file'];?>
								<div class="col-md-3" style="margin-top:10px;margin-bottom:10px;">
									<lable>WTG Technical Specification</lable>
									
									 &nbsp;<span class="small" >[Upload PDF of size upto 1024 KB]</span>
										&nbsp;&nbsp;
										<a href="<?php echo URL_HTTP.'app-docs/wtg_file/'.encode($geo_location_data['id']); ?>" target="_blank"><i class="fa fa-eye"> View Wtg File</i></a>
							  	</div>
						  	<?php } ?>
							
						</div>
				</div>
				<div class="row">
					<div class="col-md-12" style="text-align: center;">
						<?php if($geo_location_data->payment_status == 1 && $geo_location_data->approved != 1){
			               		if(!empty($clash_text)){ ?>
			               			<span  onclick="show_clash_reason('<?php echo $geo_location_data->id?>');" class="text-danger bold" style="text-decoration: underline;"><?php echo $clash_text;?> </span>
				                <?php  	}else{ ?>
									 	<button type="button" class="btn btn-sm GeoClash" style="color:white;background-color: #307FE2;"  data-toggle="modal"data-target="#GeoClash" data-id='<?php echo $geo_location_data->id ?>' > Clash</button>
									 	<button type="button" class="btn btn-sm GeoClashInternal" style="color:black;background-color: #f7f700;" data-toggle="modal"data-target="#GeoClashInternal" data-id='<?php echo $geo_location_data->id ?>'> Internal Clash</button>
									 	<button type="button" class="btn btn-sm GeoApprove" style="color:white;background-color: #4cc972;"  data-toggle="modal"data-target="#GeoApprove" data-id='<?php echo $geo_location_data->id ?>'> No clashing</button>
									 	<button type="button" class="btn btn-sm GeoReject" style="color:white;background-color: #F3565D;"   data-toggle="modal"data-target="#GeoReject" data-id='<?php echo $geo_location_data->id ?>'> Reject</button>
									 	<button type="button" class="btn btn-sm GeoRaiseQuery" style="color:black;background-color: #ccc;" data-toggle="modal" data-target="#GeoRaiseQuery" data-id='<?php echo $geo_location_data->id ?>'> Raise Query </button>
										<?php 	if(isset($internal_clashed_docs->clashed_geo_id) && !empty($internal_clashed_docs->uploadfile)){ 
													$path = URL_HTTP.'app-docs/Internal_clashed_uploadfile/'.encode($internal_clashed_docs->clashed_geo_id);?>
													<a href="<?php echo URL_HTTP.'app-docs/Internal_clashed_uploadfile/'.encode($internal_clashed_docs->clashed_geo_id); ?>" target="_blank"><i class="fa fa-eye"> View Internal Clashed Upload File</i></a>
									 	<?php   }
									} ?>
		                <?php }elseif($geo_location_data->payment_status == 1 &&  $geo_location_data->approved == 1){ ?>
								<?php	if(!empty($remainingday)){?>
											 <i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">No clashing </span><br><span class="text-success">Remaining Days to Complete application <?php echo $remainingday ?> </span>;
								<?php	}else{?>
									 		 <i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">No clashing </span>;	
								<?php	}?>
						<?php	}elseif( $geo_location_data->approved == 2){ ?>
							 	
							  	<i class="fa fa-times" aria-hidden="true"></i> <span  onclick="javascript:show_reason('<?php echo $geo_location_data->id?>');" class="text-danger bold" >Rejected </span>
						<?php	}?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="border-radius:5px; padding:20px;">
		<table class="table custom_table lable_left">
			<tbody>
				<tr>
					<td>
						<div class="col-md-4 m-2">
							<fieldset class="fieldset">
								<legend class="fieldset-legends legend-width" style="width: 75%;">Provisional Letter</legend>
								
									<div class="col-md-6">
										<a class="dropdown-item" href="/Applications/downloadRegistrationPdf/<?php echo encode($application_id); ?>" target="_blank">
											<i class="fa fa-download"></i> <?php echo ($applyOnlinesData->application_type ==5 ) ? 'GEDA Letter' : 'Provisional Letter';?>
										</a>
									</div>
							</fieldset>
						</div>
						<div class="col-md-4 m-2">
							<fieldset class="fieldset">
								<legend class="fieldset-legends legend-width"  style="width: 85%;">Connectivity Stage 1 Document</legend>
									<div class="col-md-6">
										<?php if((isset($applicationDocsSTU) && !empty($applicationDocsSTU))) { 
											foreach ($applicationDocsSTU as $key => $value) 
												{
													if (empty($value['file_name']) || !$Couchdb->documentExist($application_id,$value['file_name'])) continue;
													?>
													<a href="<?php echo URL_HTTP.'app-docs/STUstep1/'.$application_id; ?>" target="_blank"><div style="text-align: justify;text-justify: inter-word;">
														<i class="fa fa-download"></i> <span > STU Stage1 File : <?php echo date('d-M-Y',strtotime($value['created'])); ?> </span></div></a>
										<?php }   } ?>
										<?php if((isset($applicationDocsCTU) && !empty($applicationDocsCTU))) {
											foreach ($applicationDocsCTU as $key => $value) 
												{
													if (empty($value['file_name']) || !$Couchdb->documentExist($application_id,$value['file_name'])) continue;
													?>
													
														<a href="<?php echo URL_HTTP.'app-docs/CTUstep1/'.$application_id; ?>" target="_blank"><div style="text-align: justify;text-justify: inter-word;">
														<i class="fa fa-download"></i> <span > CTU Stage1 File : <?php echo date('d-M-Y',strtotime($value['created'])); ?> </span></div></a>
													
										<?php }   } ?>

									</div>
							</fieldset>
						</div>
						<div class="col-md-4 m-2">
							<fieldset class="fieldset">
								<legend class="fieldset-legends legend-width" style="width: 75%;">KMZ Upload Files</legend>
								<div class="col-md-12">
									<?php if((isset($applicationDocs) && !empty($applicationDocs))) { ?>
										<div class="col-md-10">
											<?php
											$counter = 1;

												foreach ($applicationDocs as $key => $value) 
												{
													// echo"<pre>"; print_r($application_id); die();
													// if (empty($value['file_name']) || !$Couchdb->documentExist($application_id,$value['file_name'])) continue;
													?>
													<div Class="col-md-12">
														
														<a class="dropdown-item" href="/GeoApplications/download/<?php echo $value['file_name']; ?>"><i class="fa fa-download"></i> <span ><?php echo $counter;?>. KMZ File : <?php echo date('d-M-Y',strtotime($value['created'])); ?>  </span></a>
													</div>
											<?php $counter++; }  ?> 
										</div>
									<?php } ?>
								</div>		
							</fieldset>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
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
								<?php echo $this->Form->select('approved_geo_id', $LocationList, array('label' => false, 'class' => 'form-control chosen-select','empty' =>'-Select Location-','multiple' => 'multiple', 'id' => 'approved_clashed_geo_id')); ?>
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
								<?php echo $this->Form->select('approved_geo_id', $LocationList_internal, array('label' => false, 'class' => 'form-control chosen-select','empty' =>'-Select Location-','multiple' => 'multiple', 'id' => 'approved_geo_id')); ?>
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
	<div id="GeoRaiseQuery" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Raised Query</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoRaiseQueryForm',['name'=>'GeoRaiseQueryForm','id'=>'GeoRaiseQueryForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoRaiseQuery_application_id',['id'=>'GeoRaiseQuery_application_id','label' => true,'type'=>'hidden']); ?>

						<div class="row">
							<div class="col-md-12">
								<!-- <lable>Reason to Reject </lable> -->
								
								<?php echo $this->Form->textarea('query_raised_remark', array('label' => false,'class'=>'form-control','placeholder'=>'Raised Query','id'=>'reject_reason')); ?>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-12">
								<?php echo $this->Form->input('Submit',['id'=>'GeoRaiseQuery_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoRaiseQuery_btn button-right','data-form-name'=>'GeoRaiseQueryForm']); ?>
							</div>
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
		maxFileSize: '1024',
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
			maxFileSize: '1024',
		});
		$("#land_per_form_<?php echo $counter; ?>").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-s",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#land_per_form_<?php echo $counter; ?>-file-errors',
			maxFileSize: '1024',
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
            } else if(selectedOption === 'F'){
            	$('.Land').show(); // show the text box if option3 is selected
                $('.private').hide();
                $('.forest').show();
            }else {
                $('.Land').hide(); // hide the text box if any other option is selected
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
		maxFileSize: '1024',
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

$(".approvedata").click(function() {
	var fromobj = $(this).attr("data-form-name");
	console.log(fromobj);
	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];
	console.log(indexvalue);
	var rlmm = $("#rlmm"+indexvalue).val();
	var geo_id = $("#geo_id_"+indexvalue).val();
	

	$("#message_error_approval").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$.ajax({
			type: "POST",
			url: "/GeoApplications/geo_location_approvedata",
			data: {"id":geo_id},
			//data: formData,
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
					$("#"+fromobj).find("#message_error_approval").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
				location.reload();
				} else {
					$("#"+fromobj).find("#message_error_approval").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
				}
			}
		});
});
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
	console.log(application_id);
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
	var approved_clashed_geo_id = $("#"+fromobj).find("#approved_clashed_geo_id").val();
	console.log(approved_clashed_geo_id);
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
	var approved_geo_id = $("#"+fromobj).find("#approved_geo_id").val();
	console.log(approved_geo_id);
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
$(".GeoRaiseQuery").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoRaiseQuery_application_id").val(application_id);
});
$(".GeoRaiseQuery_btn").click(function() {
	var form = $('#GeoRaiseQueryForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");

	var query_raised_remark = $("#"+fromobj).find("#query_raised_remark").val();
	
	if($("#"+fromobj).find("#query_raised_remark").val() == '') {
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		return false;
	}
	
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_location_raisedquery",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoRaiseQueryForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoRaiseQueryForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoRaiseQuery_btn").removeAttr('disabled');
					}
				}
			});

});
</script>