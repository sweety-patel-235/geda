<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
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
.applay-online-from input[type="checkbox"] {
	width: 18px;
	float: left;
	margin-top: 15px;
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
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> Geo Coordinates </h4>
		<div class="col-md-4" style="margin-top:30px;text-align:right">
			<span style="font-size:18px;color:<?php echo $applicationCategory->color_code;?>">
				<strong style="text-align:left;"><?php echo isset($applicationCategory->category_name) ? $applicationCategory->category_name : '';?></strong></span><br>&nbsp;&nbsp;Application No.: <?php echo $Applications->application_no;?>
		</div>
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
				  		<td rowspan = "2"  style="text-align:center;" >Type of Deed </td>
				  		<td rowspan = "2"  style="text-align:center;" >Sub Lease Deed </td>
				  		<td rowspan = "2"  style="text-align:center;" >Village </td>
				  		<td rowspan = "2"  style="text-align:center;" >Taluka </td>
				  		<td rowspan = "2"  style="text-align:center;" >District </td>
				  		<td colspan = "3"  style="text-align:center;" >Applied Coordinates </td>
				  		<td colspan = "6"  style="text-align:center;" >Details of WTG</td>
				  		<td rowspan = "2"  style="text-align:center;" >Action <?php if($is_member == false){?><input type="checkbox" class="select_all check" id="0"/><?php }?> </td>
				  	</tr>
				  	<tr>
				  		<td style="text-align:center;" >Zone </td>
				  		<td style="text-align:center;" >X-Coordinate </td>
				  		<td style="text-align:center;" >Y-Coordinate </td>
				  		<td style="text-align:center;" >RLMM </td>
				  		<td style="text-align:center; width:100px;" >Make </td>
				  		<td style="text-align:center;" >Model No </td>
				  		<td style="text-align:center;" >Capacity in KW  </td>
				  		<td style="text-align:center;" >Rotor Dia in meters </td>
				  		<td style="text-align:center;"  >Hub Height in meters </td>
				  	</tr>
				  	<tbody>
				  		<div id="message_error_approval"></div>
				  		<?php $counter = 1; $index=0;
				  		if($is_member == true){
				  			if(empty($geo_application_payment_done)){?>
				  				<tr>
						  				<td style="text-align:center;" colspan="20">
						  					No Application is Pending to Approve
						  				</td>
						  			</tr>
				  			<?php }
					  		foreach ($geo_application_payment_done as $key => $value) {?>
					  			<?php
	               				echo $this->Form->create($Applications, ['name'=>'geo_cordinate_approve'.$counter,'id'=>'geo_cordinate_approve'.$counter,'enctype'=>"multipart/form-data"]);
			                    echo $this->Form->input('application_id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$id]);
			                    echo $this->Form->input('application_type',['application_type'=>'application_type','label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
			                    $this->Form->templates(['inputContainer' => '{{content}}']);
			                	?>
									<tr>
						  				<?php echo $this->Form->input('geo_id',array("type" => "text",'label' => false,'type'=>'hidden','class'=>'form-control','placeholder'=>'','id'=>'geo_id_'.$index,'value'=>$geo_application_payment_done[$key]['id'])); ?>
								  		<td style="text-align:center;" > <?php echo $counter ?></td>

								  		<td style="text-align:center;" ><?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_location_'.$counter,'id'=>'wtg_location_'.$index,'value'=>$geo_application_payment_done[$key]['wtg_location'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('type_of_land', $type_of_land, array('label' => false,'readonly'=>'readonly', 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_land_'.$counter,'value'=>$geo_application_payment_done[$key]['type_of_land'])); ?>
								  		</td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','id' => 'land_survey_no_'.$counter,'value'=>$geo_application_payment_done[$key]['land_survey_no'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('land_area',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','id' => 'land_area_'.$counter,'value'=>$geo_application_payment_done[$key]['land_area'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('type_of_deed', $type_of_deed, array('label' => false,'readonly'=>'readonly', 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_deed_'.$counter,'value'=>$geo_application_payment_done[$key]['type_of_deed'])); ?>
								  		</td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('sub_lease_deed',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','id' => 'sub_lease_deed_'.$counter,'value'=>$geo_application_payment_done[$key]['sub_lease_deed'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_village',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control onlycharacter','id' => 'geo_village_'.$counter,'style'=>'width: 80px;','value'=>$geo_application_payment_done[$key]['geo_village'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_taluka',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control onlycharacter','id' => 'geo_taluka_'.$counter,'style'=>'width: 80px;','value'=>$geo_application_payment_done[$key]['geo_taluka'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('geo_district', $district, array('label' => false, 'disabled'=>'disabled','class' => 'form-control','style'=>'width: 80px;','id' => 'geo_district_'.$counter,'value'=>$geo_application_payment_done[$key]['geo_district'] )); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control','readonly'=>'readonly',  'id' => 'zone'.$counter,'style'=>'width: 70px;','value'=>$geo_application_payment_done[$key]['zone'])); ?> </td>
								  		<td style="text-align:center;" ><span id="Elatitude" style="color: Red;display:none;"></span><?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control x_cordinate','id' => 'x_cordinate_'.$counter ,'min'=>'73.00', 'max'=>'74.999','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');",'value'=>$geo_application_payment_done[$key]['x_cordinate'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','id' => 'y_cordinate_'.$counter ,'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');",'value'=>$geo_application_payment_done[$key]['y_cordinate'])); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('rlmm', $rlmm, array('label' => false,'readonly'=>'readonly','class' => 'form-control', 'id' => 'rlmm'.$counter,'style'=>'width: 70px;','value'=>$geo_application_payment_done[$key]['rlmm'], 'onChange' => 'javascript:rlmmchange('.$counter.')')); ?></td>
								  		<?php if($geo_application_payment_done[$key]['rlmm'] == 'Y') {?>
									  		<td style="text-align:" class="Y_data<?php echo $counter?>" > <?php echo $this->Form->select('wtg_make', $type_manufacturer_wind, array('label' => false, 'disabled'=>'disabled','class' => 'rfibox wtg_make_cls', 'value'=>$geo_application_payment_done[$key]['wtg_make'], 'id' => 'wtg_make_'.$counter, 'style'=>'width: 100px;','onChange' => 'javascript:changeMake('.$counter.',"'.$geo_application_payment_done[$key]["wtg_model"].'")')); ?> </td>
									  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_model', [], array('label' => false, 'disabled'=>'disabled','class' => 'rfibox wtg_model_cls','value'=>$geo_application_payment_done[$key]['wtg_model'], 'id' => 'wtg_model_'.$counter,'style'=>'width: 100px;', 'onChange' => 'javascript:changemodel('.$counter.',"'.$geo_application_payment_done[$key]["wtg_hub_height"].'","'.$geo_application_payment_done[$key]["wtg_rotor_dimension"].'","'.$geo_application_payment_done[$key]["wtg_capacity"].'")')); ?> </td>
									  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_capacity', [], array('label' => false, 'disabled'=>'disabled','class' => 'rfibox wtg_capacity_cls', 'value'=>$geo_application_payment_done[$key]['wtg_capacity'], 'id' => 'wtg_capacity_'.$counter)); ?> </td> 
									  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_rotor_dimension', [], array('label' => false,'disabled'=>'disabled', 'class' => 'rfibox wtg_rotor_dimension_cls', 'value'=>$geo_application_payment_done[$key]['wtg_rotor_dimension'], 'id' => 'wtg_rotor_dimension_'.$counter)); ?> </td> 
									  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_hub_height', [], array('label' => false, 'disabled'=>'disabled','class' => 'rfibox wtg_hub_height_cls', 'value'=>$geo_application_payment_done[$key]['wtg_hub_height'], 'id' => 'wtg_hub_height_'.$counter)); ?> </td>
								  		<?php }else { ?>
									  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_make_n',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','value'=>$geo_application_payment_done[$key]['wtg_make'])); ?>  
									  		</td>
									  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_model_n',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','value'=>$geo_application_payment_done[$key]['wtg_model'])); ?>  </td>
									  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_capacity_n',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','value'=>$geo_application_payment_done[$key]['wtg_capacity'])); ?>  </td>
									  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_rotor_dimension_n',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','value'=>$geo_application_payment_done[$key]['wtg_rotor_dimension'])); ?>  </td>
									  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_hub_height_n',array("type" => "text",'label' => false,'readonly'=>'readonly','class'=>'form-control','value'=>$geo_application_payment_done[$key]['wtg_hub_height'])); ?>  </td>
									  	<?php } ?>
								  		<td style="text-align:center;">
								  			<?php echo $this->Form->input('Approve', array('label' => false, 'class' => ' btn btn-secondary btn-sm  approvedata','style'=>'color:white;background-color: #307FE2;','name' => 'approve_'.$counter, 'type' => 'button',  'data-form-name'=>'geo_cordinate_approve'.$counter)); ?> 
								  			<?php echo $this->Form->input('Reject', array('label' => false, 'class' => ' btn btn-secondary btn-sm  GeoReject','style'=>'color:white;background-color: #307FE2;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoReject", 'data-id'=>$geo_application_payment_done[$key]['id'])); ?>
								  		</td>
								  	</tr>
					  			<?php echo $this->Form->end(); ?>
					  			<?php $counter++; $index++;	
				  			 }?>	
				  		<?php } else{ ?>
				  		
					 	<?php $counter = 1; $index=0;
					  	foreach ($geo_application_data as $key => $value) {?>
				  			<?php
	               				echo $this->Form->create($Applications, ['name'=>'geo_cordinate'.$counter,'id'=>'geo_cordinate'.$counter,'enctype'=>"multipart/form-data"]);
			                    echo $this->Form->input('application_id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$id]);
			                    echo $this->Form->input('application_type',['application_type'=>'application_type','label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
			                    
			                    $this->Form->templates(['inputContainer' => '{{content}}']);
			                ?>

				  			<tr>
				  				
				  				<?php echo $this->Form->input('geo_id',array("type" => "text",'label' => false,'type'=>'hidden','class'=>'form-control','placeholder'=>'','id'=>'geo_id_'.$index,'value'=>$geo_application_data[$key]['id'])); ?>
						  		<td style="text-align:center;" > <?php echo $counter ?></td>

						  		<td style="text-align:center;" ><?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_location_'.$counter,'id'=>'wtg_location_'.$index,'value'=>$geo_application_data[$key]['wtg_location'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->select('type_of_land', $type_of_land, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_land_'.$counter,'value'=>$geo_application_data[$key]['type_of_land'])); ?>
						  		</td>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'land_survey_no_'.$counter,'value'=>$geo_application_data[$key]['land_survey_no'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('land_area',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'land_area_'.$counter,'value'=>$geo_application_data[$key]['land_area'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->select('type_of_deed', $type_of_deed, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_deed_'.$counter,'value'=>$geo_application_data[$key]['type_of_deed'])); ?>
						  		</td>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('sub_lease_deed',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'sub_lease_deed_'.$counter,'value'=>$geo_application_data[$key]['sub_lease_deed'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_village',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'geo_village_'.$counter,'style'=>'width: 80px;','value'=>$geo_application_data[$key]['geo_village'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_taluka',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'geo_taluka_'.$counter,'style'=>'width: 80px;','value'=>$geo_application_data[$key]['geo_taluka'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->select('geo_district', $district, array('label' => false, 'class' => 'form-control','style'=>'width: 80px;','id' => 'geo_district_'.$counter,'value'=>$geo_application_data[$key]['geo_district'] )); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control', 'id' => 'zone'.$counter,'style'=>'width: 70px;','value'=>$geo_application_data[$key]['zone'])); ?>
						  		 </td>
						  		<td style="text-align:center;" ><span id="Elatitude" style="color: Red;display:none;"></span><?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'class'=>'form-control x_cordinate','id' => 'x_cordinate_'.$counter ,'min'=>'73.00', 'max'=>'74.999','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');",'value'=>$geo_application_data[$key]['x_cordinate'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'y_cordinate_'.$counter ,'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');",'value'=>$geo_application_data[$key]['y_cordinate'])); ?></td>
						  		<td style="text-align:center;" ><?php echo $this->Form->select('rlmm', $rlmm, array('label' => false, 'class' => 'form-control', 'id' => 'rlmm'.$counter,'style'=>'width: 70px;','value'=>$geo_application_data[$key]['rlmm'], 'onChange' => 'javascript:rlmmchange('.$counter.')')); ?></td>
						  		<?php if($geo_application_data[$key]['rlmm'] == 'Y') {?>
						  		<td style="text-align:" class="Y_data<?php echo $counter?>" > <?php echo $this->Form->select('wtg_make', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wtg_make_cls', 'value'=>$geo_application_data[$key]['wtg_make'], 'id' => 'wtg_make_'.$counter, 'style'=>'width: 100px;','onChange' => 'javascript:changeMake('.$counter.',"'.$geo_application_data[$key]["wtg_model"].'")')); ?> </td>
						  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_model', [], array('label' => false, 'class' => 'rfibox wtg_model_cls','value'=>$geo_application_data[$key]['wtg_model'], 'id' => 'wtg_model_'.$counter,'style'=>'width: 100px;', 'onChange' => 'javascript:changemodel('.$counter.',"'.$geo_application_data[$key]["wtg_hub_height"].'","'.$geo_application_data[$key]["wtg_rotor_dimension"].'","'.$geo_application_data[$key]["wtg_capacity"].'")')); ?> </td>
						  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_capacity', [], array('label' => false, 'class' => 'rfibox wtg_capacity_cls', 'value'=>$geo_application_data[$key]['wtg_capacity'], 'id' => 'wtg_capacity_'.$counter)); ?> </td> 
						  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_rotor_dimension', [], array('label' => false, 'class' => 'rfibox wtg_rotor_dimension_cls', 'value'=>$geo_application_data[$key]['wtg_rotor_dimension'], 'id' => 'wtg_rotor_dimension_'.$counter)); ?> </td> 
						  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_hub_height', [], array('label' => false, 'class' => 'rfibox wtg_hub_height_cls', 'value'=>$geo_application_data[$key]['wtg_hub_height'], 'id' => 'wtg_hub_height_'.$counter)); ?> </td>
						  	<?php }else { ?>
						  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_make_n',array("type" => "text",'label' => false,'class'=>'form-control','value'=>$geo_application_data[$key]['wtg_make'])); ?>  
						  		</td>
						  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_model_n',array("type" => "text",'label' => false,'class'=>'form-control','value'=>$geo_application_data[$key]['wtg_model'])); ?>  </td>
						  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_capacity_n',array("type" => "text",'label' => false,'class'=>'form-control','value'=>$geo_application_data[$key]['wtg_capacity'])); ?>  </td>
						  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_rotor_dimension_n',array("type" => "text",'label' => false,'class'=>'form-control','value'=>$geo_application_data[$key]['wtg_rotor_dimension'])); ?>  </td>
						  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_hub_height_n',array("type" => "text",'label' => false,'class'=>'form-control','value'=>$geo_application_data[$key]['wtg_hub_height'])); ?>  </td>
						  	<?php } ?>
						  		<td style="text-align:center;">
						  			<?php if($geo_application_data[$key]['payment_status'] == 1){?>
						  				<?php if($is_member == true){ ?>
						  					<?php echo $this->Form->input('Approve', array('label' => false, 'class' => ' btn btn-secondary btn-sm  editdata','style'=>'color:white;background-color: #307FE2;','name' => 'edit_'.$counter, 'type' => 'button',  'data-form-name'=>'geo_cordinate'.$counter)); ?> 
						  				<?php }elseif($geo_application_data[$key]['approved'] == 1){ ?>
						  				 	
						  				 	<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success">Approved </span>
						  				<?php }else{ ?>
						  				 
						  					Submitted
						  				<?php } ?>
						  			<?php }elseif($geo_application_data[$key]['approved'] == 2 && $geo_application_data[$key]['payment_status'] == NULL){ ?>
						  				 	<i class="fa fa-times" aria-hidden="true"></i> <span  onclick="javascript:show_reason('<?php echo $geo_application_data[$key]['id']?>');" class="text-danger bold" title="<?php echo $value->reject_reason;?>">Rejected </span>
											
						  				 	<?php echo $this->Form->input('Update', array('label' => false, 'class' => ' btn btn-secondary btn-sm  editdata','style'=>'color:white;background-color: #307FE2;','name' => 'edit_'.$counter, 'type' => 'button', 'data-form-name'=>'geo_cordinate'.$counter)); ?> <?php echo $this->form->input('Pay', array('type'=>'checkbox','value'=>$geo_location_charges,'class'=>'checkbox check','id'=>$geo_application_data[$key]['id']));	?>
						  				<?php }else{ ?>
						  			<?php echo $this->Form->input('Update', array('label' => false, 'class' => ' btn btn-secondary btn-sm  editdata','style'=>'color:white;background-color: #307FE2;','name' => 'edit_'.$counter, 'type' => 'button', 'data-form-name'=>'geo_cordinate'.$counter)); ?> <?php echo $this->form->input('Pay', array('type'=>'checkbox','value'=>$geo_location_charges,'class'=>'checkbox check','id'=>$geo_application_data[$key]['id']));	?>
						  			<?php } ?>
						  		</td>
						  	</tr><?php echo $this->Form->end(); ?>
					  	<?php $counter++; $index++;	
					  	}
					  		if(!empty($count_of_application)){
						  		$total_wtg = $total_wtg - $count_of_application;
						  	}
					  		for($i=1;$i<=$total_wtg;$i++){ ?>
					  			<?php
		                   				echo $this->Form->create($Applications, ['name'=>'geo_cordinate'.$counter,'id'=>'geo_cordinate'.$counter,'enctype'=>"multipart/form-data"]);
					                    echo $this->Form->input('application_id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$id]);
					                    echo $this->Form->input('application_type',['application_type'=>'application_type','label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
					                    $this->Form->templates(['inputContainer' => '{{content}}']);
					                ?>
						  			<tr>
						  				
						  				<?php echo $this->Form->input('wtg_id',['wtg_id'=>'wtg_id','label' => false,'type'=>'hidden','value'=>$counter]); ?>
						  			 
								  		<td style="text-align:center;" > <?php echo $counter ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('wtg_location',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'wtg_location_'.$counter,'id'=>'wtg_location_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('type_of_land', $type_of_land, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_land_'.$counter)); ?>
								  		</td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('land_survey_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'land_survey_no_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('land_area',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'land_area_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('type_of_deed', $type_of_deed, array('label' => false, 'class' => 'form-control','empty' =>'-Select Type-', 'id' => 'type_of_deed_'.$counter)); ?>
								  		</td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('sub_lease_deed',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'sub_lease_deed_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_village',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','style'=>'width: 80px;','id' => 'geo_village_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_taluka',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','style'=>'width: 80px;','id' => 'geo_taluka_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('geo_district', $district, array('label' => false, 'class' => 'form-control','style'=>'width: 80px;', 'id' => 'geo_district_'.$counter, 'empty' => '-Select District-')); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('zone', $zone_drop_down, array('label' => false, 'class' => 'form-control', 'id' => 'zone'.$counter,'style'=>'width: 70px;')); ?>
								  		 </td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('x_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','style'=>'width: 80px;','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');",'id' => 'x_cordinate_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->input('y_cordinate',array("type" => "text",'label' => false,'class'=>'form-control','style'=>'width: 80px;','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{3}).+/g, '$1');",'id' => 'y_cordinate_'.$counter)); ?></td>
								  		<td style="text-align:center;" ><?php echo $this->Form->select('rlmm', $rlmm, array('label' => false, 'class' => 'form-control','style'=>'width: 80px;', 'id' => 'rlmm'.$counter, 'onChange' => 'javascript:rlmmchange('.$counter.')')); ?></td>

								  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_make', $type_manufacturer_wind, array('label' => false, 'class' => 'rfibox wtg_make_cls', 'empty' => '- Select -','style'=>'width: 100px;', 'id' => 'wtg_make_'.$counter, 'onChange' => 'javascript:changeMake('.$counter.')')); ?> </td>

								  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_model', [], array('label' => false, 'class' => 'rfibox wtg_model_cls', 'empty' => '- Select -', 'id' => 'wtg_model_'.$counter,'style'=>'width: 100px;', 'onChange' => 'javascript:changemodel('.$counter.')')); ?> </td>
										<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_capacity', [], array('label' => false, 'class' => 'rfibox wtg_capacity_cls', 'empty' => '- Select -','style'=>'width: 100px;', 'id' => 'wtg_capacity_'.$counter,'onChange' => 'javascript:changeWindRowCapacity(this)')); ?> </td> 
								  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_rotor_dimension', [], array('label' => false, 'class' => 'rfibox wtg_rotor_dimension_cls', 'empty' => '- Select -', 'id' => 'wtg_rotor_dimension_'.$counter)); ?> </td> 
								  		<td style="text-align:center;" class="Y_data<?php echo $counter?>"> <?php echo $this->Form->select('wtg_hub_height', [], array('label' => false, 'class' => 'rfibox wtg_hub_height_cls', 'empty' => '- Select -', 'id' => 'wtg_hub_height_'.$counter)); ?> </td>

								  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_make_n',array("type" => "text",'label' => false,'class'=>'form-control')); ?>  </td>
								  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_model_n',array("type" => "text",'label' => false,'class'=>'form-control')); ?>  </td>
								  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_capacity_n',array("type" => "text",'label' => false,'class'=>'form-control')); ?>  </td>
								  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_rotor_dimension_n',array("type" => "text",'label' => false,'class'=>'form-control')); ?>  </td>
								  		<td style="text-align:center;display: none;" class="N_data<?php echo $counter?>"><?php echo $this->Form->input('wtg_hub_height_n',array("type" => "text",'label' => false,'class'=>'form-control')); ?>  </td>
								  		
								  		<td style="text-align:center;"><?php echo $this->Form->input('Submit',['type'=>'button','label'=>false,'class'=>'btn btn-primary btn-sm savedata btn-default','data-form-name'=>'geo_cordinate'.$counter,'id' => 'geo_cordinate'.$counter ]); ?></td>
								
						  			</tr>
					  			<?php echo $this->Form->end(); ?>
					  	<?php $counter++;	} 
					  	?>
					  <?php }?>
				  </tbody>	
				</table>
				
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
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">KMZ Upload Files</legend>
									<div class="col-md-12">
										<?php
											echo $this->Form->create('GeoFileForm',['name'=>'GeoFileForm','id'=>'GeoFileForm','type' => 'file']); 
											echo $this->Form->input('application_id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$id]);
											echo $this->Form->input('application_type',['application_type'=>'application_type','label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
										?>
										<div class="row mt-xlg">
											<div class="col-md-12">
												<lable class="col-md-2">Upload KMZ file</lable>
											<div class="col-md-6">
												<?php echo $this->Form->input('geo_cordinate_file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload','id'=>'GeoFile')); ?>
											</div>
											<div class="col-md-4 text-align-center">
												<?php echo $this->Form->input('Submit',['type'=>'button','label'=>false,'class'=>'btn btn-primary btn-default GeoFile_btn','data-form-name'=>'GeoFileForm']); ?>
												
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
												<lable class="col-md-2">BMZ Files</lable>
													<div class="col-md-10">
														<?php
														$counter = 1;
															foreach ($ApplicationsDocs as $key => $value) 
															{
																if (empty($value['file_name']) || !$Couchdb->documentExist($id,$value['file_name'])) continue;
																?>
																<div Class="col-md-3">
																	
																	<a href="<?php echo URL_HTTP.'app-docs/geo_cordinate_file/'.$id; ?>" target="_blank">
																	<i class="fa fa-download"></i> <?php echo $value['title'];?> <?php echo $counter;?> </a>
																</div>
														<?php $counter++; }  ?> 
													</div>
											</div>
										<?php } ?>
								</fieldset>
							</div>
							<div class="col-md-6 m-2">
								<?php echo $this->Form->create('PaymentForm',['type'=>'file','name'=>'PaymentForm','id'=>'PaymentForm','class'=>'PaymentForm','url' => '/GeoPayment/make-payment/'.$id,'autocomplete'=>'off']);
								echo $this->Form->input('application_id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$id]);
									echo $this->Form->input('application_type',['application_type'=>'application_type','label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
								?>
								<fieldset class="fieldset">
									<legend class="fieldset-legends legend-width">Payment For Geo Location Verification</legend>
									
									<div class="row mt-xlg">
										<?php $geo_location_tax = isset($applicationCategory->geo_location_tax) ? $applicationCategory->geo_location_tax : 0;
											$geo_location_tds = isset($applicationCategory->application_tds_percentage) ? $applicationCategory->application_tds_percentage : 0;
										?>
										<div class="col-md-12">
											<?php
												echo $this->Form->create('GeoFileForm',['name'=>'GeoFileForm','id'=>'GeoFileForm','type' => 'file']); 
												echo $this->Form->input('application_id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$id]);
												echo $this->Form->input('application_type',['application_type'=>'application_type','label' => false,'type'=>'hidden','value'=>$Applications->application_type]);
											?>
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
									
									<?php if((isset($ApplicationsDocs) && !empty($ApplicationsDocs))) { ?>
											<div class="row mt-xlg">
												<lable class="col-md-2">Payment Receipts</lable>
													<div class="col-md-10">
														<?php
														$counter = 1;
															foreach ($Geo_application_paymet_log as $key => $value) 
															{?>
																<div Class="col-md-3">
																	
																	<a href="/Applications/downloadGeoApplicationPdf/<?php echo encode($value['id']); ?>" target="_blank" class="dropdown-item">
																	<i class="fa fa-download"></i> Download Receipt <?php echo $key+1 ?> </a>
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
	</div>
	<div id="GeoReject" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
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
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','label'=>false,'class'=>'btn btn-primary GeoReject_btn','data-form-name'=>'GeoRejectForm']); ?>
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
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reason of Rejected Geo Location</h4>
				</div>
				
				<div class="modal-body">
					<div class="row">
							<div class="col-md-12">
								<lable>Reason to Reject </lable>
								
							
								<?php echo $this->Form->textarea('reject_reason', array('label' => false,'class'=>'form-control','placeholder'=>'Reason to Reject','id'=>'reject_reason')); ?> 
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
						<div class="col-md-2">
						<?php echo $this->Form->input('I Agree',['type'=>'button','label'=>false,'class'=>'btn btn-primary ','onclick'=>'agreeClick();']); ?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>



<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

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
				url: "/Applications/rejectedData",
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
				url: "/Applications/geo_location_rejectdata",
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
	$('.onlycharacter').keypress(function(event){
        var inputValue = event.which;
        // Allow letters: A-Z and a-z
        if((inputValue >= 65 && inputValue <= 90) || (inputValue >= 97 && inputValue <= 122) || inputValue == 8) {
            return true;
        } else {
            event.preventDefault();
            return false;
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
				url: "/Applications/GeoFileDocument",
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
    } else if (rlmoption === 'Y') {
        $('.N_data'+id).hide();
        $('.N_data'+id).prop('disabled', true); // Disable the text box
        $('.Y_data'+id).show();
        $('.Y_data'+id).prop('disabled', false); 
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

foreach ($geo_application_data as $key => $value) {
	if($value->rlmm == 'Y'){?>
		changeMake('<?php echo $key+1 ?>','<?php echo $value->wtg_model ?>','<?php echo $value->wtg_rotor_dimension ?>','<?php echo $value->wtg_hub_height ?>','<?php echo $value->wtg_capacity ?>');
		$('.Y_data'+<?php echo $key+1 ?>).show();
        $('.Y_data'+<?php echo $key+1 ?>).prop('disabled', false);
        $('.N_data'+<?php echo $key+1?>).hide();
        $('.N_data'+<?php echo $key+1 ?>).prop('disabled', true);
	<?php }else{?>
		$('.N_data'+<?php echo $key+1 ?>).show();
        $('.N_data'+<?php echo $key+1 ?>).prop('disabled', false);
        $('.Y_data'+<?php echo $key+1?>).hide();
        $('.Y_data'+<?php echo $key+1 ?>).prop('disabled', true);
	<?php }
	$counter++;
}?>
<?php $counter = 1;

foreach ($geo_application_payment_done as $key => $value) {
	if($value->rlmm == 'Y'){?>
		changeMake('<?php echo $key+1 ?>','<?php echo $value->wtg_model ?>','<?php echo $value->wtg_rotor_dimension ?>','<?php echo $value->wtg_hub_height ?>','<?php echo $value->wtg_capacity ?>');
		$('.Y_data'+<?php echo $key+1 ?>).show();
        $('.Y_data'+<?php echo $key+1 ?>).prop('disabled', false);
        $('.N_data'+<?php echo $key+1?>).hide();
        $('.N_data'+<?php echo $key+1 ?>).prop('disabled', true);
	<?php }else{?>
		$('.N_data'+<?php echo $key+1 ?>).show();
        $('.N_data'+<?php echo $key+1 ?>).prop('disabled', false);
        $('.Y_data'+<?php echo $key+1?>).hide();
        $('.Y_data'+<?php echo $key+1 ?>).prop('disabled', true);
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
			url: "/Applications/geo_location_savedata",
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


function changeMake(id,mid='',rid='',hid='',cid='') {
	$.ajax({
		type: "POST",
		url: "/Applications/getModel",
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
			$("#wtg_model_" + id).append($("<option />").val('').text('-Select Model-'));
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
		url: "/Applications/getModelDetails",
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
			$("#wtg_validity_" + id).val("");
			
			$("#wtg_rotor_dimension_" + id).append($("<option />").val('').text('-Select Model-'));
			$("#wtg_hub_height_" + id).append($("<option />").val('').text('-Select Hub-'));
			$("#wtg_capacity_" + id).append($("<option />").val('').text('-Select Capacity-'));
			
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
			$("#wtg_validity_" + id).val(result.data.validity);
		}
	});
}

$(".editdata").click(function() {
	var fromobj = $(this).attr("data-form-name");

	var formcounter = fromobj.split('_').pop().toLowerCase();
	var indexvalue  =  formcounter[formcounter.length - 1];

	ValidateRow(indexvalue);

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$.ajax({
			type: "POST",
			url: "/Applications/geo_location_editdata",
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
		if($("#type_of_deed_error_msg_"+index).html() != undefined) {
			$("#type_of_deed_error_msg_"+index).parent().removeClass('has-error');
			$("#type_of_deed_error_msg_"+index).remove();
		}
		if($("#sub_lease_deed_error_msg_"+index).html() != undefined) {
			$("#sub_lease_deed_error_msg_"+index).parent().removeClass('has-error');
			$("#sub_lease_deed_error_msg_"+index).remove();
		}

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
		var wtg_location   	= $("#wtg_location_" + index).val() ? $("#wtg_location_" + index).val() : 0;
		var type_of_land   	= $("#type_of_land_" + index).val() ? $("#type_of_land_" + index).val() : 0;
		var land_survey_no  = $("#land_survey_no_" + index).val() ? $("#land_survey_no_" + index).val() : 0;
		var land_area   	= $("#land_area_" + index).val() ? $("#land_area_" + index).val() : 0;
		var type_of_deed   	= $("#type_of_deed_" + index).val() ? $("#ltype_of_deed_" + index).val() : 0;
		var sub_lease_deed  = $("#sub_lease_deed_" + index).val() ? $("#sub_lease_deed_" + index).val() : 0;
		var geo_village  	= $("#geo_village_" + index).val() ? $("#geo_village_" + index).val() : 0;
		var geo_taluka   	= $("#geo_taluka_" + index).val() ? $("#geo_taluka_" + index).val() : 0;
		var geo_district 	= $("#geo_district_" + index).val() ? $("#geo_district_" + index).val() : 0;
		var zone 			= $("#zone_" + index).val() ? $("#zone_" + index).val() : 0;
		var x_cordinate 	= $("#x_cordinate_" + index).val() ? $("#x_cordinate_" + index).val() : 0;
		var y_cordinate 	= $("#y_cordinate_" + index).val() ? $("#y_cordinate_" + index).val() : 0;
		
		var wtg_make 			= $("#wtg_make_" + index).val() ? $("#wtg_make_" + index).val() : 0;
		var wtg_model 			= $("#wtg_model_" + index).val() ? parseFloat($("#wtg_model_" + index).val()) : 0;
		var wtg_capacity 		= $("#wtg_capacity_" + index).val() ? parseFloat($("#wtg_capacity_" + index).val()) : 0;
		var wtg_rotor_dimension = $("#wtg_rotor_dimension_" + index).val() ? parseFloat($("#wtg_rotor_dimension_" + index).val()) : 0;
		var wtg_hub_height 		= $("#wtg_hub_height_" + index).val() ? parseFloat($("#wtg_hub_height_" + index).val()) : 0;
		
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
		if (type_of_deed <= 0) {
			$("#type_of_deed_" + index).parent().addClass('has-error');
			$("#type_of_deed_" + index).parent().append('<div class="help-block type_of_deed_error_msg_cls" id="type_of_deed_error_msg_' + index + '">Required</div>');
		}
		if (sub_lease_deed <= 0) {
			$("#sub_lease_deed_" + index).parent().addClass('has-error');
			$("#sub_lease_deed_" + index).parent().append('<div class="help-block sub_lease_deed_error_msg_cls" id="sub_lease_deed_error_msg_' + index + '">Required</div>');
		}
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
		if (x_cordinate <= 0) {
			$("#x_cordinate_" + index).parent().addClass('has-error');
			$("#x_cordinate_" + index).parent().append('<div class="help-block x_cordinate_error_msg_cls" id="x_cordinate_error_msg_' + index + '">Required</div>');
		}
		if (x_cordinate < 19.00 || x_cordinate >  24.82) {
			$("#x_cordinate_" + index).parent().addClass('has-error');
			$("#x_cordinate_" + index).parent().append('<div class="help-block x_cordinate_error_msg_cls" id="x_cordinate_error_msg_' + index + '">X-Coordinate Between 19.00 to 15.999</div>');
		}
		if (y_cordinate <= 0) {
			$("#y_cordinate_" + index).parent().addClass('has-error');
			$("#y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="y_cordinate_error_msg_' + index + '">Required</div>');
		}
		if (y_cordinate < 68.00 || y_cordinate > 74.62) {
			$("#y_cordinate_" + index).parent().addClass('has-error');
			$("#y_cordinate_" + index).parent().append('<div class="help-block y_cordinate_error_msg_cls" id="y_cordinate_error_msg_' + index + '">Y-Coordinate Between 68.00 to 74.62</div>');
		}
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

	ValidateRow(indexvalue);

	$("#message_error_approval").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$.ajax({
			type: "POST",
			url: "/Applications/geo_location_approvedata",
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
					$("#"+fromobj).find("#message_error_approval").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
				location.reload();
				} else {
					$("#"+fromobj).find("#message_error_approval").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
				}
			}
		});
});
</script>