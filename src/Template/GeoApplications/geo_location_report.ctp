<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style type="text/css">

.chosen-container .chosen-results {
    max-height:200px;
}
.button-right {
	float: right;
}
.container {
	padding: 10px;
}
.select_class{
	margin-left: -14px;
    margin-top: 20px;
}
.input_class{
	margin-top: 10px;
}

</style>
<?php
	$this->Html->addCrumb("WTG coordinate verification report");
	$IP_ADDRESS	= (isset($this->request)?$this->request->clientIp():"");
	$AllowedIp	= in_array($IP_ADDRESS,array("203.88.138.46"))?true:false;

?>
<?php echo $this->Form->create('GeoApplication',array("id"=>"formmain","name"=>"searchGeouserlist",'class'=>'form-horizontal form-bordered')); ?>
<div class="container">
	<div class="col-md-12">
		<?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
		<?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
		<?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3 ">
						<?php echo $this->Form->select('application_status', $geo_application_dropdown_status,array('label' => false,'class'=>'form-control input-medium','empty'=>'-Select Status-')); ?><br>
						<?php echo $this->Form->select('application_type', $applicationCategory,array('label' => false,'class'=>'form-control input-medium','empty'=>'-Select Category-','placeholder'=>'From Date')); ?><br>
						<?php echo $this->Form->select('geo_district', $district,array('label' => false,'class'=>'form-control input-medium','empty'=>'-Select District-','id' => 'geo_district','onChange'=>'javascript:getTalukaFromDistrict();')); ?><br>
						
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
						<?php echo $this->Form->input('installer_name', array('label' => false,'class'=>'form-control input-medium input_class','placeholder'=>'Name of Developer','autocomplete'=>'off')); ?>
						<?php echo $this->Form->select('geo_taluka', $taluka,array('label' => false,'id'=>'geo_taluka','class'=>'form-control input-medium select_class','empty'=>'-Select Taluka-')); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
						<?php echo $this->Form->input('provisional_search_no', array('label' => false,'class'=>'form-control input-medium input_class','placeholder'=>'Provisional Number','autocomplete'=>'off','id'=>'provisional_search_no')); ?>
						<?php echo $this->Form->select('action_by', $action_by,array('label' => false,'id'=>'action_by','class'=>'form-control input-medium select_class' ,'empty'=>'-Action By-')); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo $this->Form->input('payment_date', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'payment_date','placeholder'=>'WTG Submitted Date')); ?>
						<?php echo $this->Form->input('wtg_location', array('label' => false,'class'=>'form-control input-medium input_class','placeholder'=>'WTG Location','autocomplete'=>'off')); ?>
						<?php echo $this->Form->select('wtg_verified', $wtg_verified,array('label' => false,'id'=>'wtg_verified','class'=>'form-control input-medium select_class','empty'=>'-WTG Verified-')); ?>
						
					</div>
				</div>
			</div>
			
			<div class="row" style="margin-top: 10px;">
				<div class="col-md-offset-4 col-md-8">
					<div class="col-md-12 form-group text">
						<button type="button" class="btn btn-green green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
						<button type="button" onClick="resetsearch()" class="btn btn-green default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="button" class="btn btn-green green" onclick="javascript:download_processing_fees_xls();"><i class="fa fa-download"></i> Download .xls</button>
					</div>
					
				</div>
				<div class="col-md-offset-3 col-md-12">
					
					<div class="col-md-3">
					<?php echo $this->Form->input('Generate WTG Verificatin PDF', array('label' => false, 'class' => ' btn btn-green green  GeoVerify','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoVerify")); ?>
					</div>
					<div class="col-md-3">
					<?php echo $this->Form->input('Add Offline Wtg Data', array('label' => false, 'class' => ' btn btn-green green  AddOfflineApplication ','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#AddOfflineApplication")); ?>
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<?php  echo $this->Flash->render('cutom_admin'); ?>
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue-madison noborder table-responsive">
			<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
				<thead>
				<tr>
					<th class=""></th>
					<th class="">Provisional No.</th>
					<th class="">WTG Co-ordinate Submitted on</th>
					<th class="">WTG Location</th>
					<th class="">District</th>
					<th class="">Taluka</th>
					<!-- <th class="">Application Category</th> -->
					<th class="">Re Project Developer Name</th>
					<th class="">Action By</th>
					<th class="">Status</th>
					<th class="">Action <input type="checkbox" id="select-all" class = "verify" name="verify" ></th>
				</tr>
				</thead>
			</table>
		</div>
		
	</div>
	
</div>
<?php echo $this->Form->end(); ?>
<div id="jqtable_data"></div>
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
					<?php echo $this->Form->input('AddOfflineApplication_application_type',['label' => false,'type'=>'hidden','value'=>""]); ?>
						<div class="row">
							<div class="col-md-4">
								<lable>Registration No </lable>
								<?php echo $this->Form->input('app_reg_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'app_reg_no_off','placeholder'=>'Registration No')); ?>
								
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
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reason of Rejected Geo Location</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="GeoApprove" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
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
	<div id="GeoClash" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
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
								<?php echo $this->Form->select('approved_geo_id','',array('label' => false, 'id'=>'location_list','class' => 'form-control chosen-select')); ?>
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
					<button type="button" class="close" data-dismiss="modal">&times;</button>
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
								<?php echo $this->Form->select('approved_geo_id','',array('label' => false, 'id'=>'Internal_location_list','class' => 'form-control chosen-select')); ?>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div id="UpdateAgreementApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Agreement consumer location</h4>
				</div>
				
				<div class="modal-body">
					<?php
					$counter = 0;
					echo $this->Form->create('UpdateAgreementApplicationForm',['name'=>'UpdateAgreementApplicationForm'.$counter,'id'=>'UpdateAgreementApplicationForm'.$counter,'type' => 'file']); 

					?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('UpdateAgreementApplication_application_id',['id'=>'UpdateAgreementApplication_application_id','label' => true,'type'=>'hidden']); ?>
					<div class="row">
							<div class="col-md-4">
								<lable>Consumer No. </lable>
								<?php echo $this->Form->input('consumer_no',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_consumer_no','placeholder'=>'Consumer No',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
								
							</div>
							<div class="col-md-4">
								<lable>Name of the consumer </lable>
								<?php echo $this->Form->input('consumer_name',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'up_consumer_name', 'placeholder'=>'Name of the consumer')); ?>
								
							</div>
							<div class="col-md-4">
								<lable>Name of DISCOM </lable>
								<?php echo $this->Form->input('discom_name',array("type" => "text",'label' => false,'class'=>'form-control onlycharacter','id' => 'up_discom_name', 'placeholder'=>'Name of DISCOM')); ?>
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<lable>WTG No. </lable>
								<?php echo $this->Form->select('wtg_location', $WtgList, array('label' => false, 'class' => 'form-control','id' => 'up_wtg_location', 'empty' => '-Select WTG No-', 'placeholder'=>'WTG No','onChange'=>'javascript:getCapacityFromLocation('.');')); ?>
								
							</div>
							<div class="col-md-4">
								<lable>Capcity of each WTG </lable>
								<?php echo $this->Form->input('wtg_capacity',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_wtg_capacity', 'placeholder'=>'Capcity of each WTG','readonly' => true)); ?>
								
								
							</div>
							<div class="col-md-4">
								<lable>% Share in MW </lable>
								<?php echo $this->Form->input('percentage_share',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_percentage_share', 'placeholder'=>'% Share in MW')); ?>
																
							</div>
							
						</div>
						<div class="row">
							
							<div class="col-md-4">
								<lable>Capacity Allocated in MW </lable>
								<?php echo $this->Form->input('capacity_allocated',array("type" => "text",'label' => false,'class'=>'form-control','id' => 'up_capacity_allocated', 'placeholder'=>'Capacity Allocated in MW','readonly' => true)); ?>
							</div>
							<div class="col-md-4">
								<lable>Transmission Agreement </lable>
								<?php echo $this->Form->input('transmission_agree_doc', array('label' => false,'type'=>'file','class'=>'form-control','id'=>'transmission_agree_doc_1')); ?>
								<div class="col-md-12"  id="transmission_agree_doc_1-file-errors"></div>
								<div class ="up_transmission_agree_doc" id="up_transmission_agree_doc"></div>
								
							</div>
							<div class="col-md-4">
								<lable>Wheeling Agreement</lable>
								<?php echo $this->Form->input('whelling_agree_doc', array('label' => false,'type'=>'file','class'=>'form-control','id'=>'whelling_agree_doc_1')); ?>
								<div class="col-md-12"  id="whelling_agree_doc_1-file-errors"></div>
								<div class ="up_whelling_agree_doc" id="up_whelling_agree_doc"></div>
							</div>

							
						</div>
					
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->input('Submit',['id'=>'UpdateAgreementApplication_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary UpdateAgreementApplication_btn button-right','data-form-name'=>'UpdateAgreementApplicationForm'.$counter]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
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
<script type="text/javascript">
<?php echo $JqdTablescr; ?>
$(document).ready(function() {
	
	resetcustomdates(true);
	resetdates();
	
	$('.chosen-select').chosen({
        //disable_search_threshold: 10,
        search_contains:true,
        width: '100%'
    });
    


});

$('.close').click(function(){
 // Reload the page when clicked
	location.reload();
});

function getTalukaFromDistrict() {
	var district= $("#geo_district").val();
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
			$("#geo_taluka").html('');
			$("#geo_taluka").append($("<option />").val('').text('-Select Taluka-'));
			if (result.data != undefined) {
				$.each(result.data, function(index, title) {
					$("#geo_taluka").append($("<option />").val(index).text(title));
				});
				
			}
			//getVillageFromTaluka();
		}
	});
}
function show_check(){
	if($('#provisional_search_no').val() != ''){
		$verify = $(".verify");
	    $verify.on('change', function() {
	        var string = $verify.filter(":checked").map(function(i,v){
	            return this.id;
	        }).get().join(",");
	        console.log(string);
	        $('#GeoVerify_application_id').val(string);
	    });
	}else{
		alert("1st select Provisional number");
		$('.verify').prop('checked', false);
		//$('.verify').prop('unchecked', $(this).prop('unchecked'));
	}

}
$('#select-all').change(function(){
	if($('#provisional_search_no').val() != ''){
		var geo_id_arr = '<?php echo $geo_id_arr_selected; ?>';
		console.log(geo_id_arr);
		console.log('hi');
	   // If "Select All" checkbox is checked, select all checkboxes; otherwise, deselect all checkboxes
	    $('.verify').prop('checked', $(this).prop('checked'));
		$verify = $(".verify");
	    var selectedIds = $verify.filter(":checked").map(function(i,v){
	            return this.id;
	        }).get().join(",");
	    var textToRemove = "select-all,";
	    var newString = selectedIds.replace(textToRemove, "");
	        console.log(selectedIds);
	    $('#GeoVerify_application_id').val(newString);
	   // return selectedIds;
	}else{
		alert("1st select Provisional number");
		$('#select-all').prop('checked', false);
		//$('.verify').prop('unchecked', $(this).prop('unchecked'));
	}
	
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
$(".GeoApprove_btn").click(function() {
	var form = $('#GeoApproveForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
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
$(".GeoClash_btn").click(function() {
	var form = $('#GeoClashForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	var fromobj = $(this).attr("data-form-name");

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
$(".GeoClashInternal_btn").click(function() {
	var form = $('#GeoClashInternalForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	var fromobj = $(this).attr("data-form-name");

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
function show_reason(geo_id)
{
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
function show_reject_modal(GeoReject_application_id)
{
	$("#GeoReject").modal('show');
	$("#GeoReject_application_id").val(GeoReject_application_id);
}
function show_approve_modal(GeoApprove_application_id)
{
	$("#GeoApprove").modal('show');
	$("#GeoApprove_application_id").val(GeoApprove_application_id);
}
function show_clash_modal(GeoClash_geo_id,GeoClash_application_id)
{
	$("#GeoClash_geo_id").val(GeoClash_geo_id);
	$("#GeoClash_application_id").val(GeoClash_application_id);
	$.ajax({
				type: "POST",
				url: "/GeoApplications/locationlistData",
				data: {"geo_id":GeoClash_geo_id,"application_id":GeoClash_application_id},
				
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

						$("#GeoClash").modal('show');

						if (result.message != undefined) {
							$("#location_list").html("");
							$("#location_list").append($("<option />").val('').text('-Select Location-'));
							$.each(result.message, function(index, title) {
								$("#location_list").append('<option value=' + index + '>' + title + '</option>');
							});
							$('#location_list').trigger('chosen:updated');  
						}
					} 
					else {
						$("#GeoClash").modal("hide");
					}
				}
			});
}
function show_internal_clash_modal(GeoClash_geo_id,GeoClash_application_id)
{
	$("#GeoClashInternal_geo_id").val(GeoClash_geo_id);
	$("#GeoClashInternal_application_id").val(GeoClash_application_id);
	$.ajax({
				type: "POST",
				url: "/GeoApplications/InternallocationlistData",
				data: {"geo_id":GeoClash_geo_id,"application_id":GeoClash_application_id},
				
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
						$("#GeoClashInternal").modal('show');
						if (result.message != undefined) {
							$("#Internal_location_list").html("");
							$("#Internal_location_list").append($("<option />").val('').text('-Select Location-'));
							$.each(result.message, function(index, title) {
								$("#Internal_location_list").append('<option value=' + index + '>' + title + '</option>');
							});
							$('#Internal_location_list').trigger('chosen:updated');  
						}
					} 
					else {
						$("#GeoClashInternal").modal("hide");
					}
				}
			});
}
function resetcustomdates(onload)
{
	var period      = $('#SearchPeriod').val();
	var Today       = '<?php echo date("d-m-Y");?>';
	var Yesterday   = '<?php echo date("d-m-Y",strtotime("yesterday"));?>';
	$("#DateFrom").removeAttr("disabled");
	$("#DateTo").removeAttr("disabled");
	if(!onload) {
		$("#DateFrom").val(Yesterday);
		$("#DateTo").val(Today);
	}
	$("#DateFrom").datepicker({format:'dd-mm-yyyy',autoclose: true});
	$("#DateTo").datepicker({format:'dd-mm-yyyy',autoclose: true});

	$('#DateFrom').on('keypress', function() {
        validateDateRange();
    });

    $('#DateTo').on('keypress', function() {
        validateDateRange();
    });

	$("#payment_date").datepicker({format:'dd-mm-yyyy',autoclose: true});
    function validateDateRange() {
        var fromDate = $('#DateFrom').val();
        var toDate = $('#DateTo').val();

        if (fromDate && toDate) {
            var fromDateObj = new Date(fromDate);
            var toDateObj = new Date(toDate);

            if (fromDateObj > toDateObj) {
                alert('From date should not be greater than To date');
                $('#DateFrom').val('');
            }
        }
    }
}
function resetdates() {

}
function resetsearch() {
	//window.location.reload();
	location.replace(location.href);
}
function validatesearchform() {
	return true;
}
$('.offline_approved_date').datepicker({
	maxDate: new Date() // Set minimum date to today
});
function show_update_modal(id)
{
	console.log(id);
	$("#UpdateAgreementApplication").modal("show");
	$.ajax({
				type: "POST",
				url: "/ApplicationsAgreement/getSavedData",
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
						$('#up_consumer_no').val(responseData.consumer_no);
            			$('#up_consumer_name').val(responseData.consumer_name);
            			$('#up_discom_name').val(responseData.discom_name);
            			$('#up_wtg_capacity').val(responseData.wtg_capacity);
            			$('#up_percentage_share').val(responseData.percentage_share);
            			$('#up_capacity_allocated').val(responseData.capacity_allocated);
            	

				        var wtg_location = responseData.wtg_location;
				        var geo_location_id = responseData.geo_location_id;
				        var application_id = responseData.application_id;
				        var path1 = responseData.path1;
				        var path2 = responseData.path2;
					console.log(application_id);

						$("#up_wtg_location").html("");
						// $("#up_wtg_location").append($("<option />").val('').text('-Select Category-'));
						$("#up_wtg_location").append($("<option selected>").val(geo_location_id).text(wtg_location));

						$("#up_transmission_agree_doc").html('<a href="'+path1+'" target="_blank"><i class="fa fa-eye"> View Transmission Agreement</i></a>');

						$("#up_whelling_agree_doc").html('<a href="'+path2+'" target="_blank"><i class="fa fa-eye"> View Whelling Agreement</i></a>');

					} 
					else {
						$("#GeoReasonReject").modal("show");
					}
				}
			});


}

function showModel(title,url){
console.log('hello');
	// var modelheader = $(this).data("title");
	var modelheader = title;
	var modelUrl = url;
	// var modelUrl = $(this).data("url");
	var defaultURL 		= "window.location.href=\'<?php echo URL_HTTP; ?>apply-online-list\'";
	document_window = $(window).width() - $(window).width()*0.40;
	document_height = $(window).height() - $(window).height() * 0.15;
	modal_body = '<div class="modal-header" style="min-height: 45px;">'+
	'<h4 class="modal-title">'+modelheader+'</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
	'</div>'+
	'<div class="modal-body">'+
	'<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
	'</div>';

	$('#myModal').find(".modal-content").html(modal_body);
	$('#myModal').modal('show');
	$('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
	$('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	return false;
};
window.closeModal = function(){ $('#myModal').modal('hide'); };


function download_processing_fees_xls() {

	$('#formmain').attr('action','<?php echo "/GeoApplications/getgeoreportfromexel"; ?>');
	$('#formmain').submit();
}
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
				url: "/GeoApplications/save_offline_data",
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
function ValidateRow_offline(index) {
	//$("#tbl_wind_info > tbody  > tr").each(function(index, tr) {
		
		if($("#wtg_location_off_error_msg_"+index).html() != undefined) {
			$("#wtg_location_off_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_location_off_error_msg_"+index).remove();
		}
		if($("#offline_approved_date_error_msg_"+index).html() != undefined) {
			$("#offline_approved_date_error_msg_"+index).parent().removeClass('has-error');
			$("#offline_approved_date_error_msg_"+index).remove();
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

		

		
		var wtg_location_off   		= $("#wtg_location_off_" + index).val() ? $("#wtg_location_off_" + index).val() : 0;
		var offline_approved_date 	= $("#offline_approved_date_" + index).val() ? $("#loffline_approved_date_" + index).val() : 0;
		// var sub_lease_deed  = $("#sub_lease_deed_" + index).val() ? $("#sub_lease_deed_" + index).val() : 0;
		
		var zone_off 				= $("#zone_off_" + index).val() ? $("#zone_off_" + index).val() : 0;
		var x_cordinate_off 		= $("#x_cordinate_off_" + index).val() ? $("#x_cordinate_off_" + index).val() : 0;
		var y_cordinate_off 		= $("#y_cordinate_off_" + index).val() ? $("#y_cordinate_off_" + index).val() : 0;
		
		if ( wtg_location_off <= 0 || offline_approved_date <= 0 ||  zone_off <= 0 || x_cordinate_off <= 0 || y_cordinate_off <=0) {
			addRow = 0;
		}

		if (wtg_location_off <= 0) {
			$("#wtg_location_off_" + index).parent().addClass('has-error');
			$("#wtg_location_off_" + index).parent().append('<div class="help-block wtg_location_off_error_msg_cls" id="wtg_location_off_error_msg_' + index + '">Required</div>');
		}
		if (offline_approved_date <= 0) {
			$("#offline_approved_date_" + index).parent().addClass('has-error');
			$("#offline_approved_date_" + index).parent().append('<div class="help-block offline_approved_date_error_msg_cls" id="offline_approved_date_error_msg_' + index + '">Required</div>');
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