<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
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
.nk_tabs .tab-content a {
    color: #444 !important;
}
.chosen-container .chosen-results {
    max-height:200px;
}
.chosen-container.chosen-container-single {
	width: 225px !important; /* or any value that fits your needs */
}
.row {
	margin-right: 0px !important;
}
.radio {
	margin-bottom: 0px !important;
	margin-top: 0px !important;
}
.applay-online-from input[type="checkbox"] {
    width: 18px;
    float: left;
    margin-top: 5px !important;
    margin-left: 0px !important;
    margin-right: 5px !important;
}
.textCheckeboxLeft {
	margin-left: 25px !important;
}
</style>
<?php
	$allocatedCategory 	= 3;
	$this->Html->addCrumb($pageTitle);
	$Report 			= "";
	
	/*if (isset($applyonlineapproval) && !empty($applyonlineapproval) ) {
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

	
	$IsInstallerAllowedToSubmit = true;
	$ALERT_MESSAGE              = "";*/

	/*if($this->Session->read('Customers.customer_type')=='installer' && ($tab=='tab_1' || $tab=='') && $create_project=='1')
	{

		$CustomerID                 = $this->Session->read('Customers.id');
		$IsInstallerAllowedToSubmit = $ApplyOnlineObj->IsInstallerAllowedToSubmit($CustomerID);
		if (!$IsInstallerAllowedToSubmit) {
			$ALERT_MESSAGE = "You are not allowed to submit application for more than 140 kW. For further details contact GEDA office at Gandhinagar, GJ.";
		}
	}*/

	/** STOP B CATEGORY INSTALLERS TO SUBMIT THE APPLICATION */
	/*$newSchemeApp 		= 0;
	$pvCapacityText 	= 'DC';
	if(isset($ApplyOnlines->created) && strtotime($ApplyOnlines->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
		$newSchemeApp 	= 1;
		$pvCapacityText = 'AC';
	}
	echo $this->Form->create($ApplyOnlines,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'contactForm', 'url' => '/apply-onlines/'.$str_url,'autocomplete'=>'off','onSubmit'=>'return CheckFormSubmit();'));*/
?>

<!-- File: src/Template/Users/login.ctp -->
<div class="container applay-online-from">
	<div class="row">
		<h2 class="col-md-9 mb-sm mt-sm"><strong>Application</strong></h2>
		<div class="col-md-3 pull-right">
			<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg pull-right">
			<?php echo $this->Html->link('RE Application',['controller'=>'Applications','action' => 'list']); ?>
			</span>
		</div>
	</div>
	<?php echo $this->Flash->render('cutom_admin');?>
	<div class="tabs tabs-bottom tabs-simple nk_tabs">
		<ul class="nav nav-tabs">
			<li class="<?php echo ($tab_id == 1) ? 'active' : '' ;?>">
				<a href="#general" data-toggle="tab">General Profile</a>
			</li>
			<li class="<?php echo empty($applicationID) ? 'desible' : '';?> <?php echo ($tab_id == 2) ? 'active' : '' ;?>" >
				<a href="<?php echo empty($applicationID) ? 'javascript;' : '#technical';?>" data-toggle="tab" <?php echo empty($applicationID) ? 'decibel="true"' : '';?>>Technical Details</a>
			</li>
			<?php //if(!in_array($applicationCategory->id,array(5,6))) { ?>
				<li class="<?php echo empty($applicationID) ? 'desible' : '';?> <?php echo ($tab_id == 3) ? 'active' : '' ;?>">
					<a href="<?php echo empty($applicationID) ? 'javascript;' : '#fees';?>" data-toggle="tab">Fees Structure</a>
				</li>
			<?php  //} ?>
		</ul>
		<div class="subsidy-claim tab-content">
			<div class="tab-pane <?php echo ($tab_id == 1) ? 'active' : '' ;?>" id="general">
			    <?php echo $this->element('applications/general_profile'); ?>
			</div>
			<div class="tab-pane <?php echo ($tab_id == 2) ? 'active' : '' ;?>" id="technical">
			    <?php echo $this->element('applications/technical_details'); ?>
			</div>
			<div class="tab-pane <?php echo ($tab_id == 3) ? 'active' : '' ;?>" id="fees">
			    <?php echo $this->element('applications/fees_structure'); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	$("#f_pan_card").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_pan_card-file-errors',
		maxFileSize: '1024',
	});
	$("#a_upload_undertaking").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#a_upload_undertaking-file-errors',
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
	$("#f_sale_discom").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_sale_discom-file-errors',
		maxFileSize: '1024',
	});
	$("#f_file_board").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#f_file_board-file-errors',
		maxFileSize: '1024',
	});
	$("#app_msme").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#app_msme-file-errors',
		maxFileSize: '1024',
	});
	$('.fa').popover({trigger: "hover"});
	$(".datepicker").datepicker({format:'dd-mm-yyyy',autoclose: true});
	$('.applicationform3').submit(function(){
		$('.applicationform3 input ,.applicationform3 select').removeAttr('disabled');
	});
//	$(".cls-ctu").addClass('hide');
	$(".AddWindRow").click(function() { AddWindRow(); });
	$(".AddModuleRow").click(function() { AddModuleRow(); });
	$(".AddInverterRow").click(function() { AddInverterRow(); });
});
	function ShowHideOthers() {
		if($("#type_of_applicant").val() == 'Other') {
			$(".applicant_others").show();
		} else {
			$(".applicant_others").hide();
		}
	}
	ShowHideOthers();
	
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
function validateNumber(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8) && (keycode < 48 || keycode > 57)) {
		return false;
	} 
}
function change_injection()
{
	var injectionLevel 	= $("#grid_connectivity").val();

	if(injectionLevel == 1) {
		$(".cls-ctu").addClass('hide');
		$(".cls-stu").removeClass('hide');
		$('#ctu').prop('disabled', true);
		$('#stu').prop('disabled', false);
	} else {
		$(".cls-ctu").removeClass('hide');
		$(".cls-stu").addClass('hide');
		//$('#stu').prop('disabled', true);
		$('#ctu').prop('disabled', false);
	}
/*	$( ".end_stu_cls" ).each(function() {
		$(this).prop('checked',false);
	});
	$( ".end_ctu_cls" ).each(function() {
		$(this).prop('checked',false);
	});*/
	clickstd();
}
function clickstd() {
	//alert(valdata.val());
	/*if($(".end_stu_cls:checked").val() == 'Sale to DISCOM') {
		$(".sale-discom").removeClass('hide');
	} else {
		$(".sale-discom").addClass('hide');
	}*/
	var injectionLevel 	= $("#grid_connectivity").val();

	$(".sale-discom").addClass('hide');
	if(injectionLevel == 1) {
		if($("#end_stu").val() == 3) {
			$(".sale-discom").removeClass('hide');
		}
		/*$( ".end_stu_cls" ).each(function() {
			if($(this).is(':checked') && $(this).val() == 'Sale to DISCOM') {
				$(".sale-discom").removeClass('hide');
			}
		});*/
	}
}
change_injection();
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
function changeTotalCapacity() {
	var wtg_no 			= $("#wtg_no").val();
	var capacity_wtg 	= $("#capacity_wtg").val();
	var total_capacity 	= (wtg_no * capacity_wtg); ///1000
	var add_capacity	= $("#add_capacity").val();
	var add_wtg			= $("#add_wtg").val();

	$("#total_capacity").val(total_capacity.toFixed(3));
	if(isNaN(add_wtg)){
			var added_total_wtg		= (parseFloat(add_wtg) + parseFloat(wtg_no));	
	}else{
		var added_total_wtg		= (wtg_no);
	}
	if(isNaN(add_capacity)){
			var add_total_capacity	= (parseFloat(add_capacity) + parseFloat(total_capacity));	
	}else{
		var add_total_capacity		= (total_capacity);
	}
	
	$("#add_wtg").val(added_total_wtg);
	$("#add_capacity").val(add_total_capacity.toFixed(3));

}
function changeModTotalCapacity() {
	var mod_no 			= $("#mod_no").val();
	var capacity_mod 	= $("#capacity_mod").val();
	var mod_total_capacity 	= (mod_no * capacity_mod)/1000000;
	var add_mod_capacity	= $("#add_mod_capacity").val();
	var add_mod_wtg			= $("#add_mod_wtg").val();
	$("#mod_total_capacity").val(mod_total_capacity.toFixed(3));
//	var added_total_mod_wtg		= (parseFloat(mod_no));
//	var add_total_mod_capacity	= ( parseFloat(mod_total_capacity));
	setTotalAll();
	//$("#add_mod_wtg").val(added_total_mod_wtg);
	//$("#add_mod_capacity").val(add_total_mod_capacity.toFixed(3));
}
function changeInvTotalCapacity() {
	var inv_no 			= $("#inv_no").val();
	var capacity_inv 	= $("#capacity_inv").val();
	var inv_total_capacity 	= (inv_no * capacity_inv)/1000;
	var add_inv_capacity	= $("#add_inv_capacity").val();
	var add_inv_wtg			= $("#add_inv_wtg").val();
	$("#inv_total_capacity").val(inv_total_capacity.toFixed(3));
	var added_total_inv_wtg		= (parseFloat(add_inv_wtg) + parseFloat(inv_no));
	
	var add_total_inv_capacity	= (parseFloat(add_inv_capacity) + parseFloat(inv_total_capacity));
	
	console.log(add_total_inv_capacity);
	setTotalAll();
//	$("#add_inv_wtg").val(added_total_inv_wtg);
	//$("#add_inv_capacity").val(add_total_inv_capacity);
}

function AddTotalCapacity() {
	var mod_no 			= $("#mod_no").val();
	var capacity_mod 	= $("#capacity_mod").val();
	var mod_total_capacity 	= (mod_no * capacity_mod)/1000000;
	$("#mod_total_capacity").val(mod_total_capacity.toFixed(3));
}
ShowHideDesignationOthers('type_director');
ShowHideDesignationOthers('type_authority');
toggel_msme();
function agreeClick()
{
	$(".terms_agree").prop('checked',true);

}
function termsandcondition()
{
	//alert(application_id);
	var html_text ='<div class="col-md-7 col-sm-7 text-left" >District</div><div class="col-md-5 col-sm-5 text-left">Dataaaaa</div>';
	html_text += '<div class="col-md-7 col-sm-7 text-left" >Taluka</div><div class="col-md-5 col-sm-5 text-left">Display</div>';

	swal({
		title: 'Income Tax TDS Terms',
		text: html_text,
		type: "",
		showCancelButton: false,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Yes, continue!",
		cancelButtonText: "No",
		closeOnConfirm: false,
		closeOnCancel: false,
		html:true
	},
	function(isConfirm) {
		//swal("Cancelled", "", "error");
	});
}
$("#state").change(function(){
	$("#district").html("");
	$("#district").append($("<option />").val('').text('-Select District-'));
	detailsFromState(1);
});
function detailsFromState(reset=0)
{
	var org_val 	= '';
	if(reset==0) {
		org_val 	= '<?php echo isset($Applications->district) ? $Applications->district : "";?>';
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
detailsFromState();
var index = [];
// Array starts with 0 but the id start with 0 so push a dummy value
index.push(0);
// Push 1 at index 1 since one child element is already created
index.push(1)



function removeHybrid(id,application_id,capacity,capacity_type,encode_application_id){

    var id 				= id;
    var application_id 	= application_id;
    var encode_application_id 	= encode_application_id;
    var capacity 		= capacity;
    var capacity_type	= capacity_type
    //alert(encode_application_id);
    swal({
          title: "Are you sure?",
          text: "You want to delete the file?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Delete it!",
          cancelButtonText: "No, cancel please!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {
          if (isConfirm) {
            $.ajax({
                        type: "POST",
                        url: "/Applications/RemoveHybrid",
                        data: {'id':id,'application_id':application_id,'capacity':capacity,'capacity_type':capacity_type},
                        beforeSend: function(xhr){
							xhr.setRequestHeader(
								'X-CSRF-Token',
								<?= json_encode($this->request->param('_csrfToken')); ?>
							);
						},
                        success: function(response) {
                            var result = $.parseJSON(response);
                            console.log(result.success);
                            if (result.success == 1) {
								swal("Deleted!", "Record has been deleted.", "success");
								window.location.href = '/Hybrid/'+encode_application_id+'/2'; 
                            }
                        }
                    });

          } else {
            swal("Cancelled", "Your Application file is safe :)", "error");
          }
        });
}
function removeWind(id,application_id,total_capacity,capacity_type,nos,capacity,encode_application_id){

    var id 						= id;
    var application_id 			= application_id;
    var encode_application_id 	= encode_application_id;
    var total_capacity 			= total_capacity;
    var capacity_type			= capacity_type;
	var nos 					= nos;
    var capacity				= capacity;
    console.log(capacity);
    swal({
          title: "Are you sure?",
          text: "You want to delete the file?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Delete it!",
          cancelButtonText: "No, cancel please!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {
          if (isConfirm) {
            $.ajax({
                        type: "POST",
                        url: "/Applications/RemoveWind",
                        data: {'id':id,'application_id':application_id,'capacity':capacity,'capacity_type':capacity_type,'nos':nos,'total_capacity':total_capacity},
                        beforeSend: function(xhr){
							xhr.setRequestHeader(
								'X-CSRF-Token',
								<?= json_encode($this->request->param('_csrfToken')); ?>
							);
						},
                        success: function(response) {
                            var result = $.parseJSON(response);
                            console.log(result.success);
                            if (result.success == 1) {
								swal("Deleted!", "Record has been deleted.", "success");
								window.location.href = '/Hybrid/'+encode_application_id+'/2'; 
                            }
                        }
                    });

          } else {
            swal("Cancelled", "Your Application file is safe :)", "error");
          }
        });
}
function setTotalAll()
{
	var total_wind_no = 0;
	var total_wind_capacity = 0;
	$(".wtg_no_cls").each(function(index,val) {
		if($(this).val()>0) {
			total_wind_no 		= total_wind_no+parseFloat($(this).val());
		}
	});
	$(".wtg_capacity_cls").each(function(index,val) {
		if($(this).val()>0) {
			total_wind_capacity = total_wind_capacity+parseFloat($(this).val());
		}
	});
	$("#add_wtg").val(total_wind_no);
	$("#add_capacity").val(total_wind_capacity.toFixed(3));

	var total_module_no = 0;
	var total_module_capacity = 0;
	$(".module_no_cls").each(function(index,val) {
		if($(this).val()>0) {
			total_module_no 		= total_module_no+parseFloat($(this).val());
		}
	});
	$(".module_capacity_cls").each(function(index,val) {
		if($(this).val()>0) {
			total_module_capacity = total_module_capacity+parseFloat($(this).val());
		}
	});
	$("#add_mod_wtg").val(total_module_no);
	$("#add_mod_capacity").val(total_module_capacity.toFixed(3));

	var total_inverter_no = 0;
	var total_inverter_capacity = 0;
	$(".inverter_no_cls").each(function(index,val) {
		if($(this).val()>0) {
			total_inverter_no 		= total_inverter_no+parseFloat($(this).val());
		}
	});
	$(".inverter_capacity_cls").each(function(index,val) {
		if($(this).val()>0) {
			total_inverter_capacity = total_inverter_capacity+parseFloat($(this).val());
		}
	});
	$("#add_inv_wtg").val(total_inverter_no);
	$("#add_inv_capacity").val(total_inverter_capacity.toFixed(3));

	$("#total_wind_hybrid_capacity_disp").val((total_wind_capacity+total_inverter_capacity).toFixed(3));
	$("#module_hybrid_capacity_disp").val(total_module_capacity.toFixed(3));
	
	console.log($("#total_wind_hybrid_capacity_disp").val());
	console.log($("#module_hybrid_capacity_disp").val());
}
/*******Wind add component related code start ************/
function AddWindRow() {
	var addRow 				= 1;
	$("#tbl_wind_info > tbody  > tr").each(function(index,tr) {
		//console.log($("#error_msg_"+index).html());
		
		if($("#wtg_no_error_msg_"+index).html() != undefined) {
			$("#wtg_no_error_msg_"+index).parent().removeClass('has-error');
			$("#wtg_no_error_msg_"+index).remove();
		}
		if($("#capacity_wtg_error_msg_"+index).html() != undefined) {
			$("#capacity_wtg_error_msg_"+index).parent().removeClass('has-error');
			$("#capacity_wtg_error_msg_"+index).remove();
		}
		if($("#make_error_msg_"+index).html() != undefined) {
			$("#make_error_msg_"+index).parent().removeClass('has-error');
			$("#make_error_msg_"+index).remove();
		}
	});
	$("#tbl_wind_info > tbody  > tr").each(function(index,tr) {
		var wtg_no 			= $("#wtg_no_"+index).val() !== '' ? parseFloat($("#wtg_no_"+index).val()) : 0;
		var capacity_wtg 	= $("#capacity_wtg_"+index).val() ? parseFloat($("#capacity_wtg_"+index).val()) : 0;
		var make 			= $("#make_"+index).val() ? $("#make_"+index).val() : 0;
		if(wtg_no<=0 || capacity_wtg<=0 || make<=0) {
			addRow  		= 0;
		}
		
		if(wtg_no <= 0) {
			$("#wtg_no_"+index).parent().addClass('has-error');
			$("#wtg_no_"+index).parent().append('<div class="help-block" id="wtg_no_error_msg_'+index+'">Please enter the value</div>');
		}
		if(capacity_wtg <= 0) {
			$("#capacity_wtg_"+index).parent().addClass('has-error');
			$("#capacity_wtg_"+index).parent().append('<div class="help-block" id="capacity_wtg_error_msg_'+index+'">Please enter the value</div>');
		}
		if(make <= 0) {
			$("#make_"+index).parent().addClass('has-error');
			$("#make_"+index).parent().append('<div class="help-block" id="make_error_msg_'+index+'">Please Select Make</div>');
		}

	});
	if(addRow == 1) {
		var newRow = $("#tbl_wind_info tr:last").clone(true).find('.rfibox').val('').end();
		newRow.find('.id_wind').val('');
		newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowWind(this)" value="-" />');
		$("#tbl_wind_info").append(newRow);
	}
	ResetWindRowID();
}
function ResetWindRowID()
{
	$("#tbl_wind_info > tbody  > tr").each(function(index,tr) {
		$(tr).find(".wtg_no_cls").attr("id","wtg_no_"+index);
		$(tr).find(".wtg_no_cls").attr("name","Applications[wtg_no]["+index+"]");
		$(tr).find(".wtg_cap_cls").attr("id","capacity_wtg_"+index);
		$(tr).find(".wtg_cap_cls").attr("name","Applications[capacity_wtg]["+index+"]");
		$(tr).find(".wtg_capacity_cls").attr("id","total_capacity_"+index);
		$(tr).find(".wtg_capacity_cls").attr("name","Applications[total_capacity]["+index+"]");
		$(tr).find(".wind_make_cls").attr("id","make_"+index);
		$(tr).find(".wind_make_cls").attr("name","Applications[make]["+index+"]");
		$(tr).find(".id_wind").attr("id","id_wind_"+index+"");
		$(tr).find(".id_wind").attr("name","Applications[id_wind]["+index+"]");
		$(tr).find(".lastrow").attr("id","actionrange_"+index);
		
	});
}
function deleterRowWind(_obj) 
{ 
	var deleteCellId= $(_obj).parent().attr("id");
	var arrData 	= deleteCellId.split("_");
	var idval 		= parseInt(arrData[1]);
	
	$(_obj).parent().parent().remove(); 
	ResetWindRowID();
	setTotalAll();
}

function changeWindRowCapacity(_obj) {
	var currentID	= $(_obj).attr("id");
	var arrIds 		= currentID.split("_");
	var total_capacity 	= ($("#wtg_no_"+arrIds[2]).val() * $("#capacity_wtg_"+arrIds[2]).val());
	$("#total_capacity_"+arrIds[2]).val(total_capacity.toFixed(3));
	setTotalAll();
}
/*******Wind add component related code end ************/

/*******Module add component related code start ************/
function AddModuleRow() {
	var addRow 				= 1;
	$("#tbl_module_info > tbody  > tr").each(function(index,tr) {
		//console.log($("#error_msg_"+index).html());
		
		if($("#nos_mod_error_msg_"+index).html() != undefined) {
			$("#nos_mod_error_msg_"+index).parent().removeClass('has-error');
			$("#nos_mod_error_msg_"+index).remove();
		}
		if($("#mod_capacity_error_msg_"+index).html() != undefined) {
			$("#mod_capacity_error_msg_"+index).parent().removeClass('has-error');
			$("#mod_capacity_error_msg_"+index).remove();
		}
		if($("#mod_make_error_msg_"+index).html() != undefined) {
			$("#mod_make_error_msg_"+index).parent().removeClass('has-error');
			$("#mod_make_error_msg_"+index).remove();
		}
		if($("#mod_spv_error_msg_"+index).html() != undefined) {
			$("#mod_spv_error_msg_"+index).parent().removeClass('has-error');
			$("#mod_spv_error_msg_"+index).remove();
		}
		if($("#mod_solar_panel_error_msg_"+index).html() != undefined) {
			$("#mod_solar_panel_error_msg_"+index).parent().removeClass('has-error');
			$("#mod_solar_panel_error_msg_"+index).remove();
		}
	});
	$("#tbl_module_info > tbody  > tr").each(function(index,tr) {
		var nos_mod 		= $("#nos_mod_"+index).val() !== '' ? parseFloat($("#nos_mod_"+index).val()) : 0;
		var mod_capacity 	= $("#mod_capacity_"+index).val() ? parseFloat($("#mod_capacity_"+index).val()) : 0;
		var mod_make 		= $("#mod_make_"+index).val() ? $("#mod_make_"+index).val() : 0;
		var mod_spv 		= $("#mod_type_of_spv_"+index).val() ? $("#mod_type_of_spv_"+index).val() : 0;
		var mod_solar_panel = $("#mod_type_of_solar_panel_"+index).val() ? $("#mod_type_of_solar_panel_"+index).val() : 0;

		if(nos_mod<=0 || mod_capacity<=0 || mod_make<=0 || mod_spv<=0 || mod_solar_panel<=0) {
			addRow  		= 0;
		}
		
		if(nos_mod <= 0) {
			$("#nos_mod_"+index).parent().addClass('has-error');
			$("#nos_mod_"+index).parent().append('<div class="help-block" id="nos_mod_error_msg_'+index+'">Please enter the value</div>');
		}
		if(mod_capacity <= 0) {
			$("#mod_capacity_"+index).parent().addClass('has-error');
			$("#mod_capacity_"+index).parent().append('<div class="help-block" id="mod_capacity_error_msg_'+index+'">Please enter the value</div>');
		}
		if(mod_make <= 0) {
			$("#mod_make_"+index).parent().addClass('has-error');
			$("#mod_make_"+index).parent().append('<div class="help-block" id="mod_make_error_msg_'+index+'">Please Select Make</div>');
		}
		if(mod_spv <= 0) {
			$("#mod_type_of_spv_"+index).parent().addClass('has-error');
			$("#mod_type_of_spv_"+index).parent().append('<div class="help-block" id="mod_spv_error_msg_'+index+'">Please Select SPV Technologies</div>');
		}
		if(mod_solar_panel <= 0) {
			$("#mod_type_of_solar_panel_"+index).parent().addClass('has-error');
			$("#mod_type_of_solar_panel_"+index).parent().append('<div class="help-block" id="mod_solar_panel_error_msg_'+index+'">Please Select Solar Panel</div>');
		}

	});
	
	if(addRow == 1) {
		var newRow = $("#tbl_module_info tr:last").clone(true).find('.rfibox').val('').end();
		newRow.find('.id_module').val('');
		newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowModule(this)" value="-" />');
		$("#tbl_module_info").append(newRow);
	}
	ResetModuleRowID();
}
function ResetModuleRowID()
{
	$("#tbl_module_info > tbody  > tr").each(function(index,tr) {
		$(tr).find(".module_no_cls").attr("id","nos_mod_"+index);
		$(tr).find(".module_no_cls").attr("name","Applications[nos_mod]["+index+"]");
		$(tr).find(".module_cap_cls").attr("id","mod_capacity_"+index);
		$(tr).find(".module_cap_cls").attr("name","Applications[mod_capacity]["+index+"]");
		$(tr).find(".module_capacity_cls").attr("id","mod_total_capacity_"+index);
		$(tr).find(".module_capacity_cls").attr("name","Applications[mod_total_capacity]["+index+"]");
		$(tr).find(".module_make_cls").attr("id","mod_make_"+index);
		$(tr).find(".module_make_cls").attr("name","Applications[mod_make]["+index+"]");
		$(tr).find(".module_spv_cls").attr("id","mod_type_of_spv_"+index);
		$(tr).find(".module_spv_cls").attr("name","Applications[mod_type_of_spv]["+index+"]");
		$(tr).find(".module_solar_panel_cls").attr("id","mod_type_of_solar_panel_"+index);
		$(tr).find(".module_solar_panel_cls").attr("name","Applications[mod_type_of_solar_panel]["+index+"]");
		$(tr).find(".id_module").attr("id","id_module_"+index+"");
		$(tr).find(".id_module").attr("name","Applications[id_module]["+index+"]");
		$(tr).find(".lastrow").attr("id","actionrange_"+index);
		
	});
}
function deleterRowModule(_obj) 
{ 
	var deleteCellId= $(_obj).parent().attr("id");
	var arrData 	= deleteCellId.split("_");
	var idval 		= parseInt(arrData[1]);
	$(_obj).parent().parent().remove(); 
	ResetModuleRowID();
	setTotalAll();
}

function changeModuleRowCapacity(_obj) {
	var currentID	= $(_obj).attr("id");
	var arrIds 		= currentID.split("_");
	
	var total_capacity 	= ($("#nos_mod_"+arrIds[2]).val() * $("#mod_capacity_"+arrIds[2]).val())/1000000;
	$("#mod_total_capacity_"+arrIds[2]).val(total_capacity.toFixed(3));
	setTotalAll();
}
/*******Module add component related code end ************/

/*******Inverter add component related code start ************/
function AddInverterRow() {
	var addRow 				= 1;
	$("#tbl_inverter_info > tbody  > tr").each(function(index,tr) {
		//console.log($("#error_msg_"+index).html());
		
		if($("#nos_inv_error_msg_"+index).html() != undefined) {
			$("#nos_inv_error_msg_"+index).parent().removeClass('has-error');
			$("#nos_inv_error_msg_"+index).remove();
		}
		if($("#inv_capacity_error_msg_"+index).html() != undefined) {
			$("#inv_capacity_error_msg_"+index).parent().removeClass('has-error');
			$("#inv_capacity_error_msg_"+index).remove();
		}
		if($("#inv_make_error_msg_"+index).html() != undefined) {
			$("#inv_make_error_msg_"+index).parent().removeClass('has-error');
			$("#inv_make_error_msg_"+index).remove();
		}
		if($("#inv_used_error_msg_"+index).html() != undefined) {
			$("#inv_used_error_msg_"+index).parent().removeClass('has-error');
			$("#inv_used_error_msg_"+index).remove();
		}
	});
	$("#tbl_inverter_info > tbody  > tr").each(function(index,tr) {
		var nos_inv 		= $("#nos_inv_"+index).val() !== '' ? parseFloat($("#nos_inv_"+index).val()) : 0;
		var inv_capacity 	= $("#inv_capacity_"+index).val() ? parseFloat($("#inv_capacity_"+index).val()) : 0;
		var inv_make 		= $("#inv_make_"+index).val() ? $("#inv_make_"+index).val() : 0;
		var inv_used 		= $("#inv_used_"+index).val() ? $("#inv_used_"+index).val() : 0;
		
		if(nos_inv<=0 || inv_capacity<=0 || inv_make<=0 || inv_used<=0) {
			addRow  		= 0;
		}
		
		
		if(nos_inv <= 0) {
			$("#nos_inv_"+index).parent().addClass('has-error');
			$("#nos_inv_"+index).parent().append('<div class="help-block" id="nos_inv_error_msg_'+index+'">Please enter the value</div>');
		}
		if(inv_capacity <= 0) {
			$("#inv_capacity_"+index).parent().addClass('has-error');
			$("#inv_capacity_"+index).parent().append('<div class="help-block" id="inv_capacity_error_msg_'+index+'">Please enter the value</div>');
		}
		if(inv_make <= 0) {
			$("#inv_make_"+index).parent().addClass('has-error');
			$("#inv_make_"+index).parent().append('<div class="help-block" id="inv_make_error_msg_'+index+'">Please Select Make</div>');
		}
		if(inv_used <= 0) {
			$("#inv_used_"+index).parent().addClass('has-error');
			$("#inv_used_"+index).parent().append('<div class="help-block" id="inv_used_error_msg_'+index+'">Please Select Type of Inverter Used</div>');
		}

	});
	
	if(addRow == 1) {
		var newRow = $("#tbl_inverter_info tr:last").clone(true).find('.rfibox').val('').end();
		newRow.find('.id_module').val('');
		newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowInverter(this)" value="-" />');
		$("#tbl_inverter_info").append(newRow);
	}
	ResetInverterRowID();
}
function ResetInverterRowID()
{
	$("#tbl_inverter_info > tbody  > tr").each(function(index,tr) {
		$(tr).find(".inverter_no_cls").attr("id","nos_inv_"+index);
		$(tr).find(".inverter_no_cls").attr("name","Applications[nos_inv]["+index+"]");
		$(tr).find(".inverter_cap_cls").attr("id","inv_capacity_"+index);
		$(tr).find(".inverter_cap_cls").attr("name","Applications[inv_capacity]["+index+"]");
		$(tr).find(".inverter_capacity_cls").attr("id","inv_total_capacity_"+index);
		$(tr).find(".inverter_capacity_cls").attr("name","Applications[inv_total_capacity]["+index+"]");
		$(tr).find(".inverter_make_cls").attr("id","inv_make_"+index);
		$(tr).find(".inverter_make_cls").attr("name","Applications[inv_make]["+index+"]");
		$(tr).find(".inverter_used_cls").attr("id","inv_used_"+index);
		$(tr).find(".inverter_used_cls").attr("name","Applications[inv_used]["+index+"]");
		$(tr).find(".id_inverter").attr("id","id_inverter_"+index+"");
		$(tr).find(".id_inverter").attr("name","Applications[id_inverter]["+index+"]");
		$(tr).find(".lastrow").attr("id","actionrange_"+index);
	});
}
function deleterRowInverter(_obj) 
{ 
	var deleteCellId= $(_obj).parent().attr("id");
	var arrData 	= deleteCellId.split("_");
	var idval 		= parseInt(arrData[1]);
	$(_obj).parent().parent().remove(); 
	ResetInverterRowID();
	setTotalAll();
}

function changeInverterRowCapacity(_obj) {
	var currentID	= $(_obj).attr("id");
	var arrIds 		= currentID.split("_");
	
	var total_capacity 	= ($("#nos_inv_"+arrIds[2]).val() * $("#inv_capacity_"+arrIds[2]).val())/1000;
	$("#inv_total_capacity_"+arrIds[2]).val(total_capacity.toFixed(3));
	setTotalAll();
}
/*******Inverter add component related code end ************/

<?php if($errorWind == 1) { ?>
	AddWindRow();
<?php } ?>
<?php if($errorModule == 1) { ?>
	AddModuleRow();
<?php } ?>
<?php if($errorInverter == 1) { ?>
	AddInverterRow();
<?php } ?>

setTotalAll();

function toggle_developer()
{
	$(".selectDeveloper").each(function(opt){
		if($(this).is(":checked") && $(this).val() == 1) {
			$(".gen_profile").addClass('hide');
			$(".select_developer").removeClass('hide');
			
		}
		if($(this).is(":checked") && $(this).val() == 0) {
			$(".gen_profile").removeClass('hide');
			$(".select_developer").addClass('hide');
		}
	});
}
$(".varifythirdpartyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$("#"+fromobj).find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#"+fromobj).find("#message_error").html('');
	var otp_data = $("#"+fromobj).find("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/developer-verify-otp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#"+fromobj).find("#otp_data").val('');
						$("#"+fromobj).find("#message_error").removeClass('alert-danger');
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						if(result.redirect_payment == 1) {
							window.location.href='/developer-payment/'+$("#insid").val();
						}
						else if(result.redirect_payment == 0) {
							window.location.href='/developer-verify-otp/'+$("#insid").val();
						}
					} else {
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".sendthirdpartyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	
	$("#"+fromobj).find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#"+fromobj).find("#message_error").html('');
	var otp_data = $("#"+fromobj).find("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/Applications/generateSendOTPDeveloper",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#"+fromobj).find("#otp_data").val('');
						$("#"+fromobj).find("#message_error").removeClass('alert-danger');
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						if(result.redirect_payment == 1) {
							window.location.href='/developer-payment/'+$("#insid").val();
						}
						else if(result.redirect_payment == 0) {
							window.location.href='/developer-verify-otp/'+$("#insid").val();
						}
					} else {
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});

$('#applicationform1').submit(function() {
	$('.application-from input,.application-from select').removeAttr('disabled');
});
</script>


