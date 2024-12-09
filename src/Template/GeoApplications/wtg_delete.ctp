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
		<h4 class="<?php echo $titleClass;?> mb-sm mt-sm"><strong>Application</strong> Delete WTG Cordinates</h4>
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
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="row">
		 	<div class="col-md-12 ">

				<div class="table table-responsive table-bordered noborder" >
					 
					 <table id= "tbl_wind_info" class="table table-striped table-bordered table-hover custom-greenhead" >

					  	<tr class="thead-dark">
					  		<thead class="thead-dark">
					  		<th colspan="7" style="text-align:center;" >Details of WTG Coordinate </th>
					  		</thead>
					  	</tr>
					  	<tr >
					  		<td rowspan = "2"  style="text-align:center;" >Sr No </td>
					  		<td rowspan = "2"  style="text-align:center;" >WTG Location </td>
					  		<td rowspan = "2"  style="text-align:center;" >Land Survey No </td>
					  		<td colspan = "3"  style="text-align:center;" >Applied WTG Coordinates </td>
					  		
					  		<td rowspan = "2"  >Action </td>
					  	</tr>
					  	<tr>
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
								
					  			?>
					  			
					  		<tr>
						  		<td style="text-align:center;" ><?php echo $this->Form->input('geo_application_id',array("type" => "text",'label' => false,'type'=>'hidden','class'=>'form-control','placeholder'=>'','id'=>'geo_application_id_'.$counter,'value'=>$geo_application_data[$key]['id'])); ?><?php echo $counter ?> </td>
						  		<td style="text-align:center;" ><?php echo $geo_application_data[$key]['wtg_location']?></td>
						  		<td style="text-align:center;" ><?php echo $geo_application_data[$key]['land_survey_no']?> </td>
						  		<td style="text-align:center;" ><?php echo $zone ?></td>
						  		<td style="text-align:center;" ><?php echo $geo_application_data[$key]['x_cordinate']?></td>
						  		<td style="text-align:center;" ><?php echo $geo_application_data[$key]['y_cordinate']?></td>
						  		
						  		<td style="text-align:center;width:440px;" >
						  			<div class="col-md-12 row" > 
												<?php echo $this->Form->input('Delete', array('label' => false,'class' => ' btn  btn-sm  GeoDeleteWTG','style'=>'color:white;background-color: #34A853;', 'type' => 'button', 'data-toggle'=>"modal" ,'data-target'=>"#GeoDeleteWTG", 'data-id'=>$geo_application_data[$key]['id'])); ?>
											 
											 	
								  			</div> 
							  	</td>
						  	</tr>
						  <?php $counter++; }?>
					  	</tbody>	
					</table>
				</div>
			</div>
			<div class="row col-md-12">
				<div class="col-md-3">
					<?php echo $this->Html->link('Back',['controller'=>'','action' => 'applications-list'],['class'=>'next btn btn-primary btn-md  cbtnsendmsg btn-default']); ?>
				</div>
			</div>
		</div>
	</div>

	
	<div id="GeoDeleteWTG" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close cross" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure you want to Delete?</h4>
				</div>
				
				<div class="modal-body">
					<?php
					echo $this->Form->create('GeoDeleteWTGForm',['name'=>'GeoDeleteWTGForm','id'=>'GeoDeleteWTGForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('GeoDeleteWTG_geo_application_id',['id'=>'GeoDeleteWTG_geo_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<?php echo $this->Form->input('Submit',['id'=>'GeoDeleteWTG_submit','type'=>'button','label'=>false,'class'=>'btn btn-primary GeoDeleteWTG_btn button-right','data-form-name'=>'GeoDeleteWTGForm']); ?>
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

$(".GeoDeleteWTG").click(function(){
	var application_id = $(this).attr("data-id");
	$("#GeoDeleteWTG_geo_application_id").val(application_id);
});
$(".GeoDeleteWTG_btn").click(function() {
	var form = $('#GeoDeleteWTGForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	 console.log('hii');
	var fromobj = $(this).attr("data-form-name");

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/GeoApplications/geo_wtg_delete",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#GeoDeleteWTGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} 
					else {
						$("#GeoDeleteWTGForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".GeoDeleteWTG_btn").removeAttr('disabled');
					}
				}
			});

});
</script>