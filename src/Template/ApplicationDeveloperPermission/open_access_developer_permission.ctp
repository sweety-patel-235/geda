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
	.check-box-address {
		margin-top: 20px !important;
	}
	.mendatory_field {
		color: red;
	}
	.nk_tabs .tab-content a {
		color: #444 !important;
	}
	.chosen-container .chosen-results {
		max-height: 200px;
	}
	.chosen-container.chosen-container-single {
		width: 343px !important;
		/* or any value that fits your needs */
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
	.sale-to-discom {
		display: none;
	}
	.captive-with-rpo {
		display: none;
	}
	.project-with-RE-mechanism {
		display: none;
	}
	.captive,
	.third-party {
		display: none;
	}
	.textCheckeboxLeft {
		margin-left: 25px !important;
	}

	input[type="checkbox"][readonly] {
		pointer-events: none;
	}
</style>
<?php
$allocatedCategory 	= 3;
$this->Html->addCrumb($pageTitle);
?>
<!-- File: src/Template/Users/login.ctp -->
<div class="container applay-online-from">

	<?php echo $this->Flash->render('cutom_admin'); ?>
	
	<div class="tabs tabs-bottom tabs-simple nk_tabs">
		<ul class="nav nav-tabs">
			<li class="<?php echo ($tab_id == 1) ? 'active' : ''; ?>">
				<a href="#general" data-toggle="tab">General Profile</a>
			</li>
			<li class="<?php echo empty($applicationID) ? 'desible' : ''; ?> <?php echo ($tab_id == 2 ) ? 'active' : ''; ?>">
				<a href="<?php echo empty($applicationID) ? 'javascript;' : '#technical'; ?>" data-toggle="tab" <?php echo empty($applicationID) ? 'decibel="true"' : ''; ?>>Technical Details</a>
			</li>
			<li class="<?php echo empty($applicationID) ? 'desible' : ''; ?> <?php echo ($tab_id == 3 ) ? 'active' : ''; ?>">
				<a href="<?php echo empty($applicationID)? 'javascript;' : '#land'; ?>" data-toggle="tab" >Land Details</a>
			</li>
			<li class="<?php echo empty($applicationID) ||$tab!=3  ? 'desible' : ''; ?> <?php echo ($tab_id == 4 && $tab==3) ? 'active' : ''; ?>">
				<a href="<?php echo empty($applicationID)||$tab!=3  ? 'javascript;' : '#fees'; ?>" data-toggle="tab">Fees Structure</a>
			</li>
		</ul>
		<div class="subsidy-claim tab-content">
			<div class="tab-pane <?php echo ($tab_id == 1) ? 'active' : ''; ?>" id="general">
				<?php echo $this->element('application-developer-permission/general_profile'); ?>
			</div>
			<div class="tab-pane <?php echo ($tab_id == 2 ) ? 'active' : ''; ?>" id="technical">
				<?php echo $this->element('application-developer-permission/open_access_technical_details'); ?>
			</div>
			<div class="tab-pane <?php echo ($tab_id == 3 ) ? 'active' : ''; ?>" id="land">
				<?php echo $this->element('application-developer-permission/open_access_land_details'); ?>
			</div>
			<div class="tab-pane <?php echo ($tab_id == 4 && $tab==3) ? 'active' : ''; ?>" id="fees">
				<?php echo $this->element('application-developer-permission/fees_structure'); ?>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function() {
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
		$("#upload_sale_to_discom").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#upload_sale_to_discom-file-errors',
			maxFileSize: '1024',
		});
		$("#no_due_1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#no_due_1-file-errors',
			maxFileSize: '1024',
		});
		$("#no_due_2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#no_due_2-file-errors',
			maxFileSize: '1024',
		});

		$("#upload_proof_of_ownership_1").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#upload_proof_of_ownership_1-file-errors',
			maxFileSize: '1024',
		});
		$("#upload_proof_of_ownership_2").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#upload_proof_of_ownership_2-file-errors',
			maxFileSize: '1024',
		});
		$("#doc_of_beneficiary").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#doc_of_beneficiary-file-errors',
			maxFileSize: '1024',
		});
		$("#stamp_of_re_gen_plant").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#stamp_of_re_gen_plant-file-errors',
			maxFileSize: '1024',
		});
		$("#copy_of_conventional_electricity").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#copy_of_conventional_electricity-file-errors',
			maxFileSize: '1024',
		});
		$("#electricit_bill_of_third_party").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#electricit_bill_of_third_party-file-errors',
			maxFileSize: '1024',
		});
				
		$("#multi_third_party").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#multi_third_party-file-errors',
			maxFileSize: '1024',
		});

		$("#rec_accrediation_cer").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#rec_accrediation_cer-file-errors',
			maxFileSize: '1024',
		});

		$("#doc_of_gerc_license").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#doc_of_gerc_license-file-errors',
			maxFileSize: '1024',
		});

		$("#upload_undertaking_newness").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#doc_of_gerc_license-file-errors',
			maxFileSize: '1024',
		});

		

		$('.fa').popover({
			trigger: "hover"
		});
		$(".datepicker").datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
		$('#proposed_date_of_commm').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});

		$('#end_use_of_electricity').on('change', function() {
			
			if (this.value == '1') {

				$(".captive").hide();
				$(".third-party").hide();
				$(".sale-to-discom").show();
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
				$(".third-party").find("option:selected").removeAttr("selected");
				$(".captive").find("option:selected").removeAttr("selected");
			} else if (this.value == '2') {
				$(".captive").show();
				$(".third-party").hide();
				$(".sale-to-discom").hide();
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
				$(".third-party").find("option:selected").removeAttr("selected");
			} else if (this.value == '3') {
				$(".captive").hide();
				$(".third-party").show();
				$(".sale-to-discom").hide();
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
				$(".captive").find("option:selected").removeAttr("selected");
			}
		});
		$('#end_use_of_electricity').trigger('change');


		$('#captive').on('change', function() {
			
			if (this.value == '1') {				
				$(".captive-with-rpo").show();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
			} else if (this.value == '2') {
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").show();
			} else if (this.value == '0') {
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
			}
		});
		$('#captive').trigger('change');

		$('#third_party').on('change', function() {
			
			if (this.value == '1') {				
				$(".captive-with-rpo").show();
				$(".project-for-third-party").show();
				$(".project-with-RE-mechanism").hide();
			} else if (this.value == '2') {
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").show();
				$(".project-with-RE-mechanism").show();
			} else if (this.value == '0') {
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
			}
		});
		$('#third_party').trigger('change');

		$('#project_for_rpo').on('change', function() {
			if (this.value == '1') {
				$(".captive").show();
			} else {
				$(".captive").hide();
			}
		});
		$('#project_for_rpo').trigger("change");

		$('.applicationform3').submit(function() {
			$('.applicationform3 input ,.applicationform3 select').removeAttr('disabled');
		});
		
		$(".AddModuleRow").click(function() {
			AddModuleRow();
		});
		$(".AddInverterRow").click(function() {
			AddInverterRow();
		});
		 AddLandRow();
		$(".AddLandRow").click(function() {
			AddLandRow();
		});
	});

	function toggel_third_party_detail() {
		$(".third-party-details").addClass('hide');
		$("input[type=radio][name=details_of_third_party]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".third-party-details").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".third-party-details").addClass('hide');
			}
		});
	}
	toggel_third_party_detail();

	function deed_copy_func() {
		$(".deed-doc").addClass('hide');
		$("input[type=radio][name=deed_copy]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".deed-doc").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".deed-doc").addClass('hide');
			}
		});
	}
	deed_copy_func();

	function capacity_of_stoa() {
		$(".capacity-of-stoa").addClass('hide');
		$("input[type=radio][name=certi_of_stoa]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".capacity-of-stoa").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".capacity-of-stoa").addClass('hide');
			}
		});
	}
	capacity_of_stoa();

	function stamp_re_gen_plant() {
		$(".stamp-of-re-gen-plant-div").addClass('hide');
		$("input[type=radio][name=RE_generating_plant]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".stamp-of-re-gen-plant-div").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".stamp-of-re-gen-plant-div").addClass('hide');
			}
		});
	}
	stamp_re_gen_plant();

	function captive_power_plant() {
		$(".captive-power-plant").addClass('hide');
		$("input[type=radio][name=captive_conv_power_plant]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".captive-power-plant").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".captive-power-plant").addClass('hide');
			}
		});
	}
	captive_power_plant();

	function phy_copy_of_rec_reg_web_toggle() {
		$(".rec-mechanism-upload").addClass('hide');
		$(".rec-mechanism").addClass('hide');
		$("input[type=radio][name=phy_copy_of_rec_reg_web]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".rec-mechanism-upload").removeClass('hide');
				$(".rec-mechanism").addClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".rec-mechanism-upload").addClass('hide');
				$(".rec-mechanism").removeClass('hide');
			
			}
		});
	}
	phy_copy_of_rec_reg_web_toggle();


	function copy_of_gerc_togg() {
		$(".copy_of_gerc_div").addClass('hide');		
		$("input[type=radio][name=copy_of_gerc]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".copy_of_gerc_div").removeClass('hide');				
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".copy_of_gerc_div").addClass('hide');				
			}
		});
	}
	copy_of_gerc_togg();
	
	function agreeClick() {
		$(".terms_agree").prop('checked', true);
		$('#agree_popup').modal('hide');
	}

	function ShowHideOthers() {
		if ($("#type_of_applicant").val() == 'Other') {
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

	function validateCommaSepratedNumber(key) {
		var keycode = (key.which) ? key.which : key.keyCode;
		if (!(keycode == 8 || keycode == 44 || (keycode >= 48 && keycode <= 57))) {
			return false;
		}
	}

	function validateCommaSeparatedDecimals(event) {
		var keycode = (event.which) ? event.which : event.keyCode;
		var char = String.fromCharCode(keycode);
		if (keycode !== 8 && keycode !== 44 && keycode !== 46 && (keycode < 48 || keycode > 57)) {
			return false;
		}
		
		var input = event.srcElement.value;
		var lastChar = input.slice(-1);

		if (keycode == 44 && (lastChar == ',' || input === '')) {
			return false;
		}

		var segments = input.split(',');
		for (var i = 0; i < segments.length; i++) {
			var segment = segments[i];
			var dotCount = (segment.match(/\./g) || []).length;
			if (dotCount > 0 && char === '.') {
				return false;
			}
			if (segment === '' && char === '.') {
				return false;
			}
		}
		return true;
	}


	function change_injection() {
		var injectionLevel = $("#grid_connectivity").val();

		if (injectionLevel == 1) {
			$(".cls-ctu").addClass('hide');
			$(".cls-stu").removeClass('hide');
		} else {
			$(".cls-ctu").removeClass('hide');
			$(".cls-stu").addClass('hide');
		}
		clickstd();
	}

	function clickstd() {
		var injectionLevel = $("#grid_connectivity").val();
		$(".sale-discom").addClass('hide');
		if (injectionLevel == 1) {
			if ($("#end_stu").val() == 3) {
				$(".sale-discom").removeClass('hide');
			}
			
		}
	}
	change_injection();

	function ShowHideDesignationOthers(designation) {
		if ($("#" + designation).val() == 'Others') {
			$("#div_" + designation).show();
		} else {
			$("#div_" + designation).hide();
		}
	}

	function toggel_msme() {
		$(".msme_file").addClass('hide');
		$(".msme").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".msme_file").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".msme_file").addClass('hide');
			}
		});
	}

	function changeTotalCapacity() {
		var wtg_no = $("#wtg_no").val();
		var capacity_wtg = $("#capacity_wtg").val();
		var total_capacity = (wtg_no * capacity_wtg); ///1000
		$("#total_capacity").val(total_capacity.toFixed(3));

	}

	function changeModTotalCapacity() {
		var mod_no = $("#mod_no").val();
		var capacity_mod = $("#capacity_mod").val();
		var mod_total_capacity = (mod_no * capacity_mod) / 1000000;
		$("#mod_total_capacity").val(mod_total_capacity.toFixed(3));
	}

	function changeInvTotalCapacity() {
		var inv_no = $("#inv_no").val();
		var capacity_inv = $("#capacity_inv").val();
		var inv_total_capacity = (inv_no * capacity_inv) / 1000;
		$("#inv_total_capacity").val(inv_total_capacity.toFixed(3));
	}

	function AddTotalCapacity() {
		var mod_no = $("#mod_no").val();
		var capacity_mod = $("#capacity_mod").val();
		var mod_total_capacity = (mod_no * capacity_mod) / 1000000;
		$("#mod_total_capacity").val(mod_total_capacity.toFixed(3));
	}
	ShowHideDesignationOthers('type_director');
	ShowHideDesignationOthers('type_authority');
	toggel_msme();


	function termsandcondition() {

		var html_text = '<div class="col-md-7 col-sm-7 text-left" >District</div><div class="col-md-5 col-sm-5 text-left">Dataaaaa</div>';
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
				html: true
			},
			function(isConfirm) {
				//swal("Cancelled", "", "error");
			});
	}
	$("#state").change(function() {
		$("#district").html("");
		$("#district").append($("<option />").val('').text('-Select District-'));
		detailsFromState(1);
	});
	$("#land_state").change(function() {
		$("#land_district").html("");
		$("#land_district").append($("<option />").val('').text('-Select District-'));
		detailsFromState1(1);
	});

	function detailsFromState(reset = 0) {
		var org_val = '';
		if (reset == 0) {
			org_val = '<?php echo isset($Applications->district) ? $Applications->district : ""; ?>';
		}
		$.ajax({
			type: "POST",
			url: "/InstallerRegistrations/getDistrict",
			data: {
				"state": $('#state').val()
			},
			success: function(response) {
				var result = $.parseJSON(response);
				$("#district").html("");
				$("#district").append($("<option />").val('').text('-Select District-'));
				if (result.data.district != undefined) {
					$.each(result.data.district, function(index, title) {
						$("#district").append($("<option />").val(index).text(title));
					});
					$('#district').val(org_val);
					if (org_val != '') {

					}
				}
			}
		});
	}
	detailsFromState();

	function detailsFromState1(reset = 0) {
		var org_val = '';
		if (reset == 0) {
			org_val = '<?php echo isset($Applications->district) ? $Applications->district : ""; ?>';
		}
		$.ajax({
			type: "POST",
			url: "/InstallerRegistrations/getDistrict",
			data: {
				"state": $('#state').val()
			},
			success: function(response) {
				var result = $.parseJSON(response);
				$("#land_district").html("");
				$("#land_district").append($("<option />").val('').text('-Select District-'));
				if (result.data.district != undefined) {
					$.each(result.data.district, function(index, title) {
						$("#land_district").append($("<option />").val(index).text(title));
					});
					$('#land_district').val(org_val);
					if (org_val != '') {

					}
				}
			}
		});
	}
	detailsFromState1();
	var index = [];
	// Array starts with 0 but the id start with 0 so push a dummy value
	index.push(0);
	// Push 1 at index 1 since one child element is already created
	index.push(1)

	$('.addWind').click(function() {
		var id = getID();
		var text1 = "wtg_no";
		var text2 = "capacity_wtg";
		var text3 = "total_capacity";
		var mod_no = text1.concat(id);
		var capacity_mod = text2.concat(id);
		var mod_total_capacity = text3.concat(id);

		$('.optionBoxWind:first').after('<div class="optionBoxWind' + id + '"><div class="col-md-3"><br><input type="text" class="form-control" id="wtg_no' + id + '" name="wtg_no[]" placeholder="Nos. of WTG" onkeypress="return validateNumber(event)" onChange="changeWindTotalCapacityid(' + id + ')" ></div><div class="col-md-3"><br><input type="text" class="form-control" id="capacity_wtg' + id + '" name="capacity_wtg[]" placeholder="Capacity of each WTG" onkeypress="return validateNumber(event)" onChange="changeWindTotalCapacityid(' + id + ')" ></div><div class="col-md-3"><br><input type="text" class="form-control" id="total_capacity' + id + '" name="total_capacity[]" placeholder="Total Capacity in MW" onkeypress="return validateNumber(event)" readonly></div><div class="col-md-2"><br><input type="text" class="form-control"  name="make[]" placeholder="Make" ></div><div class="col-md-1"><br><input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterowWind(' + id + ')" value="-" /></div></div>');
	});

	function deleterowWind(id) {
		$(".optionBoxWind" + id).remove();
	}

	$('.addModhybrid').click(function() {
		var id = getID();
		var text1 = "mod_no";
		var text2 = "capacity_mod";
		var text3 = "mod_total_capacity";
		var mod_no = text1.concat(id);
		var capacity_mod = text2.concat(id);
		var mod_total_capacity = text3.concat(id);


	});

	function deleterowModule(id) {
		$(".optionBox" + id).remove();
	}
	$('.addInvhybrid').click(function() {
		var id = getID();
		var text1 = "inv_no";
		var text2 = "capacity_inv";
		var text3 = "mod_total_capacity";
		var inv_no = text1.concat(id);
		var capacity_inv = text2.concat(id);
		var inv_total_capacity = text3.concat(id);

		$('.optionBoxInv:first').after('<div class="optionBoxInv' + id + '" style="margin-left:-2px"><div class="col-md-3 blockInv"><br><input type="text" class="form-control" id="inv_no' + id + '" name="nos_inv[]" placeholder="Nos. of Inverter" onkeypress="return validateNumber(event)" onChange="changeInvTotalCapacityid(' + id + ')" ></div><div class="col-md-3 blockInv"><br><input type="text" class="form-control" id="capacity_inv' + id + '" name="inv_capacity[]" placeholder="Capacity of each Inverter" onkeypress="return validateNumber(event)" onChange="changeInvTotalCapacityid(' + id + ')" ></div><div class="col-md-3 blockInv"><br><input type="text" class="form-control" id="inv_total_capacity' + id + '" name="inv_total_capacity[]" placeholder="Total Capacity in MW" onkeypress="return validateNumber(event)" onChange="changeInvTotalCapacityid(' + id + ')" readonly></div><div class="col-md-2 blockInv"><br><input type="text" class="form-control"  name="inv_make[]" placeholder="Make" ></div><div class="col-md-1 blockInv removeInvhybrid"><br><input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button"  onclick="deleterowInverter(' + id + ')" value="-" /></div></div>');
	});

	function deleterowInverter(id) {
		$(".optionBoxInv" + id).remove();
	}

	function getID() {
		var emptyIndex = index.indexOf(-1);
		if (emptyIndex != -1) {
			index[emptyIndex] = emptyIndex

			return emptyIndex
		} else {
			emptyIndex = index.length
			index.push(emptyIndex)
			return emptyIndex
		}
	}

	function changeInvTotalCapacityid(id) {

		var inv_no = $("#inv_no" + (id)).val();
		var capacity_inv = $("#capacity_inv" + (id)).val();
		var inv_total_capacity = (inv_no * capacity_inv) / 1000;

		$("#inv_total_capacity" + (id)).val(inv_total_capacity.toFixed(3));
	}

	function changeModTotalCapacityid(id) {
		var mod_no = $("#mod_no" + (id)).val();
		var capacity_mod = $("#capacity_mod" + (id)).val();
		var mod_total_capacity = (mod_no * capacity_mod) / 1000000;

		$("#mod_total_capacity" + (id)).val(mod_total_capacity.toFixed(3));
	}

	function changeWindTotalCapacityid(id) {
		var wtg_no = $("#wtg_no" + (id)).val();
		var capacity_wtg = $("#capacity_wtg" + (id)).val();
		var total_capacity = (wtg_no * capacity_wtg);
		var add_capacity = $("#add_capacity").val();
		var add_wtg = $("#add_wtg").val();
		console.log(add_wtg);
		$("#total_capacity" + (id)).val(total_capacity.toFixed(3));
		var added_total_wtg = float(add_wtg + wtg_no);
		console.log(added_total_wtg);
		var add_total_capacity = (add_capacity + total_capacity);
		console.log(add_total_capacity);
		$("#add_capacity").val(added_total_wtg);
		$("#add_wtg").val(add_total_capacity.toFixed(3));
	}

	function removeModules(id, capacity_type, encode_application_id) {

		var id = id;
		var encode_application_id = encode_application_id;
		var capacity_type = capacity_type

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
						url: "/ApplicationDeveloperPermission/remove_modules",
						data: {
							'id': id,
							'capacity_type': capacity_type
						},
						beforeSend: function(xhr) {
							xhr.setRequestHeader(
								'X-CSRF-Token',
								<?= json_encode($this->request->param('_csrfToken')); ?>
							);
						},
						success: function(response) {
							var result = $.parseJSON(response);
							if (result.success == 1) {
								swal("Deleted!", "Your Condition has been deleted.", "success");

								window.location.href = '/open-access-permission/' + encode_application_id + '/2';
							}
						}
					});

				} else {
					swal("Cancelled", "Your Application file is safe :)", "error");
				}
			});
	}


	function setTotalAll() {
		var total_wind_no = 0;
		var total_wind_capacity = 0;
		$(".wtg_no_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total_wind_no = total_wind_no + parseFloat($(this).val());
			}
		});
		$(".wtg_capacity_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total_wind_capacity = total_wind_capacity + parseFloat($(this).val());
			}
		});
		$("#add_wtg").val(total_wind_no);
		$("#add_capacity").val(total_wind_capacity.toFixed(3));

		var total_module_no = 0;
		var total_module_capacity = 0;
		$(".module_no_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total_module_no = total_module_no + parseFloat($(this).val());
			}
		});
		$(".module_capacity_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total_module_capacity = total_module_capacity + parseFloat($(this).val());
			}
		});
		$("#add_mod_wtg").val(total_module_no);
		$("#add_mod_capacity").val(total_module_capacity.toFixed(3));

		var total_inverter_no = 0;
		var total_inverter_capacity = 0;
		$(".inverter_no_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total_inverter_no = total_inverter_no + parseFloat($(this).val());
			}
		});
		$(".inverter_capacity_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total_inverter_capacity = total_inverter_capacity + parseFloat($(this).val());
			}
		});
		$("#add_inv_wtg").val(total_inverter_no);
		$("#add_inv_capacity").val(total_inverter_capacity.toFixed(3));

		$("#total_wind_hybrid_capacity_disp").val((total_wind_capacity + total_inverter_capacity).toFixed(3));
		$("#module_hybrid_capacity_disp").val(total_module_capacity.toFixed(3));

		
	}

	/**** Land code start*/
	

	function removeLand(id, encode_application_id) {
		var id = id;
		var encode_application_id = encode_application_id;
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
						url: "/ApplicationDeveloperPermission/remove_land",
						data: {
							'id': id							
						},
						beforeSend: function(xhr) {
							xhr.setRequestHeader(
								'X-CSRF-Token',
								<?= json_encode($this->request->param('_csrfToken')); ?>
							);
						},
						success: function(response) {
							var result = $.parseJSON(response);
							if (result.success == 1) {
								swal("Deleted!", "Your Condition has been deleted.", "success");
								
								window.location.href = '/open-access-permission/' + encode_application_id + '/3';
							}
						}
					});

				} else {
					swal("Cancelled", "Your Application file is safe :)", "error");
				}
			});
	}

	function AddLandRow() {

		var addRow = 1;
		$("#tbl_land_info > tbody  > tr").each(function(index, tr) {

			
			if ($("#land_category_error_msg_" + index).html() != undefined) {
				$("#land_category_error_msg_" + index).parent().removeClass('has-error');
				$("#land_category_error_msg_" + index).remove();
			}
			if ($("#land_plot_servey_no_error_msg_" + index).html() != undefined) {
				$("#land_plot_servey_no_error_msg_" + index).parent().removeClass('has-error');
				$("#land_plot_servey_no_error_msg_" + index).remove();
			}
			if ($("#land_taluka_error_msg_" + index).html() != undefined) {
				$("#land_taluka_error_msg_" + index).parent().removeClass('has-error');
				$("#land_taluka_error_msg_" + index).remove();
			}
			if ($("#land_district_error_msg_" + index).html() != undefined) {
				$("#land_district_error_msg_" + index).parent().removeClass('has-error');
				$("#land_district_error_msg_" + index).remove();
			}
			if ($("#land_latitude_error_msg_" + index).html() != undefined) {
				$("#land_latitude_error_msg_" + index).parent().removeClass('has-error');
				$("#land_latitude_error_msg_" + index).remove();
			}
			if ($("#land_longitude_error_msg_" + index).html() != undefined) {
				$("#land_longitude_error_msg_" + index).parent().removeClass('has-error');
				$("#land_longitude_error_msg_" + index).remove();
			}
			if ($("#area_of_land_error_msg_" + index).html() != undefined) {
				$("#area_of_land_error_msg_" + index).parent().removeClass('has-error');
				$("#area_of_land_error_msg_" + index).remove();
			}
			if ($("#deed_of_land_error_msg_" + index).html() != undefined) {
				$("#deed_of_land_error_msg_" + index).parent().removeClass('has-error');
				$("#deed_of_land_error_msg_" + index).remove();
			}
			if ($("#a_deed_doc_error_msg_" + index).html() != undefined) {
				$("#a_deed_doc_error_msg_" + index).closest('div.land-file').after().removeClass('has-error');
				$("#a_deed_doc_error_msg_" + index).remove();
			}

			

		});
		$("#tbl_land_info > tbody  > tr").each(function(index, tr) {

			var land_category = $("#land_category_" + index).val() ? $("#land_category_" + index).val() : 0;
			var land_plot_servey_no = $("#land_plot_servey_no_" + index).val() ? $("#land_plot_servey_no_" + index).val() : 0;
			var land_taluka = $("#land_taluka_" + index).val() ? $("#land_taluka_" + index).val() : 0;
			var land_district = $("#land_district_" + index).val() ? $("#land_district_" + index).val() : 0;
			var land_latitude = $("#land_latitude_" + index).val() ? $("#land_latitude_" + index).val() : 0;
			var land_longitude = $("#land_longitude_" + index).val() ? $("#land_longitude_" + index).val() : 0;
			var area_of_land = $("#area_of_land_" + index).val() ? $("#area_of_land_" + index).val() : 0;
			var deed_of_land = $("#deed_of_land_" + index).val() ? $("#deed_of_land_" + index).val() : 0;
			
			var deed_doc = $("#deed_doc_" + index).val() ? $("#deed_doc_" + index).val() : 0;
			var deed_file = $("#deed_file_" + index).val() ? $("#deed_file_" + index).val() : 0;
			
			if (land_category <= 0 || land_plot_servey_no <= 0 || land_taluka <= 0 || land_district <= 0 || land_latitude <= 0 || land_longitude <= 0 || area_of_land <= 0 || deed_of_land <= 0 || (deed_doc <= 0 && deed_file <= 0)) {
				
				addRow = 0;
			}

			if (land_category <= 0) {
				$("#land_category_" + index).parent().addClass('has-error');
				$("#land_category_" + index).parent().append('<div class="help-block" id="land_category_error_msg_' + index + '">Required</div>');
			}
			if (land_plot_servey_no <= 0) {
				$("#land_plot_servey_no_" + index).parent().addClass('has-error');
				$("#land_plot_servey_no_" + index).parent().append('<div class="help-block" id="land_plot_servey_no_error_msg_' + index + '">Required</div>');
			}
			if (land_taluka <= 0) {
				$("#land_taluka_" + index).parent().addClass('has-error');
				$("#land_taluka_" + index).parent().append('<div class="help-block" id="land_taluka_error_msg_' + index + '">Required</div>');
			}
			if (land_district <= 0) {
				$("#land_district_" + index).parent().addClass('has-error');
				$("#land_district_" + index).parent().append('<div class="help-block" id="land_district_error_msg_' + index + '">Required</div>');
			}
			if (land_latitude <= 0) {
				$("#land_latitude_" + index).parent().addClass('has-error');
				$("#land_latitude_" + index).parent().append('<div class="help-block" id="land_latitude_error_msg_' + index + '">Required</div>');
			}
			if (land_longitude <= 0) {
				$("#land_longitude_" + index).parent().addClass('has-error');
				$("#land_longitude_" + index).parent().append('<div class="help-block" id="land_longitude_error_msg_' + index + '">Required</div>');
			}
			if (area_of_land <= 0) {
				$("#area_of_land_" + index).parent().addClass('has-error');
				$("#area_of_land_" + index).parent().append('<div class="help-block" id="area_of_land_error_msg_' + index + '">Required</div>');
			}
			if (deed_of_land <= 0) {
				$("#deed_of_land_" + index).parent().addClass('has-error');
				$("#deed_of_land_" + index).parent().append('<div class="help-block" id="deed_of_land_error_msg_' + index + '">Required</div>');
			}
			if (deed_doc <= 0 && deed_file <= 0)  {
				
				$("#deed_doc_" + index).closest('div.land-file').after().addClass('has-error');
				$("#deed_doc_" + index).closest('div.land-file').after().append('<div class="help-block" id="a_deed_doc_error_msg_' + index + '">Required</div>');
			}
			
		});
		if (addRow == 1) {
			var newRow = $("#tbl_land_info tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find(".lastrow").html('<button class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowLand(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>');
			newRow.find('.id_land').val('');
			newRow.find(".deed_file_cls").val('');
			newRow.find('strong').remove();
			$("#tbl_land_info").append(newRow);
			srno = $("#tbl_land_info").find("tr").length;
			$("#tbl_land_info tr:last").children('td:first').text(srno);
		}
		ResetLandRowID();

	}

	function ResetLandRowID() {
		$("#tbl_land_info > tbody  > tr").each(function(index, tr) {			

			$(tr).find(".land_category_cls").attr("id", "land_category_" + index);
			$(tr).find(".land_category_cls").attr("name", "land_category[" + index + "]");
			
			$(tr).find(".land_plot_servey_no_cls").attr("id", "land_plot_servey_no_" + index);
			$(tr).find(".land_plot_servey_no_cls").attr("name", "land_plot_servey_no[" + index + "]");

			$(tr).find(".land_taluka_cls").attr("id", "land_taluka_" + index);
			$(tr).find(".land_taluka_cls").attr("name", "land_taluka[" + index + "]");

			$(tr).find(".land_district_cls").attr("id", "land_district_" + index);
			$(tr).find(".land_district_cls").attr("name", "land_district[" + index + "]");

			$(tr).find(".land_latitude_cls").attr("id", "land_latitude_" + index);
			$(tr).find(".land_latitude_cls").attr("name", "land_latitude[" + index + "]");

			$(tr).find(".land_longitude_cls").attr("id", "land_longitude_" + index);
			$(tr).find(".land_longitude_cls").attr("name", "land_longitude[" + index + "]");

			$(tr).find(".area_of_land_cls").attr("id", "area_of_land_" + index);
			$(tr).find(".area_of_land_cls").attr("name", "area_of_land[" + index + "]");

			$(tr).find(".deed_of_land_cls").attr("id", "deed_of_land_" + index);
			$(tr).find(".deed_of_land_cls").attr("name", "deed_of_land[" + index + "]");

			$(tr).find(".a_deed_doc_cls").attr("id", "deed_doc_" + index);
			$(tr).find(".a_deed_doc_cls").attr("name", "a_deed_doc_" + index );

			$(tr).find(".deed_file_cls").attr("id", "deed_file_" + index);
			$(tr).find(".deed_file_cls").attr("name", "deed_file[" + index + "]");

			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
			
			$(tr).find(".deed_doc_error_cls").attr("id", "deed_doc_"+ index +"-file-errors");

			$(tr).find(".id_land").attr("id", "id_land_" + index + "");
			$(tr).find(".id_land").attr("name", "id_land[" + index + "]");
			
			$("#deed_doc_" + index).fileinput({
				showUpload: false,
				showPreview: false,
				dropZoneEnabled: false,
				mainClass: "input-group-md",
				allowedFileExtensions: ["pdf"],
				elErrorContainer: '#deed_doc_' + index + '-file-errors',
				maxFileSize: '1024',
			});

		});


	}

	function deleteRowLand(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();
		srno = $("#tbl_land_info").find("tr").length;
		$("#tbl_land_info tr:last").children('td:first').text(srno);
		ResetLandRowID();

	}

	/*** Land end */

	/*******Module add component related code start ************/
	function AddModuleRow() {

		var addRow = 1;
		$("#tbl_module_info > tbody  > tr").each(function(index, tr) {

			if ($("#nos_mod_error_msg_" + index).html() != undefined) {
				$("#nos_mod_error_msg_" + index).parent().removeClass('has-error');
				$("#nos_mod_error_msg_" + index).remove();
			}
			if ($("#mod_capacity_error_msg_" + index).html() != undefined) {
				$("#mod_capacity_error_msg_" + index).parent().removeClass('has-error');
				$("#mod_capacity_error_msg_" + index).remove();
			}
			if ($("#mod_make_error_msg_" + index).html() != undefined) {
				$("#mod_make_error_msg_" + index).parent().removeClass('has-error');
				$("#mod_make_error_msg_" + index).remove();
			}
			if ($("#type_of_spv_error_msg_" + index).html() != undefined) {
				$("#type_of_spv_error_msg_" + index).parent().removeClass('has-error');
				$("#type_of_spv_error_msg_" + index).remove();
			}
			if ($("#type_of_solar_error_msg_" + index).html() != undefined) {
				$("#type_of_solar_error_msg_" + index).parent().removeClass('has-error');
				$("#type_of_solar_error_msg_" + index).remove();
			}
		});
		$("#tbl_module_info > tbody  > tr").each(function(index, tr) {

			var nos_mod = $("#nos_mod_" + index).val() !== '' ? parseFloat($("#nos_mod_" + index).val()) : 0;
			var mod_capacity = $("#mod_capacity_" + index).val() ? parseFloat($("#mod_capacity_" + index).val()) : 0;
			var mod_make = $("#mod_make_" + index).val() ? $("#mod_make_" + index).val() : 0;
			var spv_technology = $("#type_of_spv_" + index).val() ? $("#type_of_spv_" + index).val() : 0;
			var panel_type = $("#type_of_solar_" + index).val() ? $("#type_of_solar_" + index).val() : 0;

			if (nos_mod <= 0 || mod_capacity <= 0 || mod_make <= 0 || spv_technology <= 0 || panel_type <= 0) {
				addRow = 0;
			}

			if (nos_mod <= 0) {
				$("#nos_mod_" + index).parent().addClass('has-error');
				$("#nos_mod_" + index).parent().append('<div class="help-block" id="nos_mod_error_msg_' + index + '">Required</div>');
			}
			if (mod_capacity <= 0) {
				$("#mod_capacity_" + index).parent().addClass('has-error');
				$("#mod_capacity_" + index).parent().append('<div class="help-block" id="mod_capacity_error_msg_' + index + '">Required</div>');
			}
			if (mod_make <= 0) {
				$("#mod_make_" + index).parent().addClass('has-error');
				$("#mod_make_" + index).parent().append('<div class="help-block" id="mod_make_error_msg_' + index + '">Required</div>');
			}

			if (spv_technology <= 0) {
				$("#type_of_spv_" + index).parent().addClass('has-error');
				$("#type_of_spv_" + index).parent().append('<div class="help-block" id="type_of_spv_error_msg_' + index + '">Required</div>');
			}
			if (panel_type <= 0) {
				$("#type_of_solar_" + index).parent().addClass('has-error');
				$("#type_of_solar_" + index).parent().append('<div class="help-block" id="type_of_solar_error_msg_' + index + '">Required</div>');
			}

		});

		if (addRow == 1) {
			var newRow = $("#tbl_module_info tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find('.id_module').val('');
			newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowModule(this)" value="-" />');
			$("#tbl_module_info").append(newRow);
		}
		ResetModuleRowID();
	}

	function ResetModuleRowID() {
		$("#tbl_module_info > tbody  > tr").each(function(index, tr) {
			$(tr).find(".module_no_cls").attr("id", "nos_mod_" + index);
			$(tr).find(".module_no_cls").attr("name", "nos_mod[" + index + "]");
			
			$(tr).find(".module_cap_cls").attr("id", "mod_capacity_" + index);
			$(tr).find(".module_cap_cls").attr("name", "mod_capacity[" + index + "]");
			$(tr).find(".module_capacity_cls").attr("id", "mod_total_capacity_" + index);
			$(tr).find(".module_capacity_cls").attr("name", "mod_total_capacity[" + index + "]");
			$(tr).find(".module_make_cls").attr("id", "mod_make_" + index);
			$(tr).find(".module_make_cls").attr("name", "mod_make[" + index + "]");

			$(tr).find(".type_of_spv_cls").attr("id", "type_of_spv_" + index);
			$(tr).find(".type_of_spv_cls").attr("name", "type_of_spv[" + index + "]");

			$(tr).find("type_of_solar_cls").attr("id", "type_of_solar_" + index);
			$(tr).find("type_of_solar_cls").attr("name", "type_of_solar[" + index + "]");

			$(tr).find(".id_module").attr("id", "id_module_" + index + "");
			$(tr).find(".id_module").attr("name", "id_module[" + index + "]");

			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
		});
	}

	function deleterRowModule(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();
		ResetModuleRowID();
		setTotalAll();
	}

	function changeModuleRowCapacity(_obj) {
		var currentID = $(_obj).attr("id");
		var arrIds = currentID.split("_");

		var total_capacity = ($("#nos_mod_" + arrIds[2]).val() * $("#mod_capacity_" + arrIds[2]).val()) / 1000000;
		$("#mod_total_capacity_" + arrIds[2]).val(total_capacity.toFixed(3));
		setTotalAll();
	}
	/*******Module add component related code end ************/


	/*******Inverter add component related code start ************/
	function AddInverterRow() {
		var addRow = 1;
		$("#tbl_inverter_info > tbody  > tr").each(function(index, tr) {
			//console.log($("#error_msg_"+index).html());

			if ($("#nos_inv_error_msg_" + index).html() != undefined) {
				$("#nos_inv_error_msg_" + index).parent().removeClass('has-error');
				$("#nos_inv_error_msg_" + index).remove();
			}
			if ($("#inv_capacity_error_msg_" + index).html() != undefined) {
				$("#inv_capacity_error_msg_" + index).parent().removeClass('has-error');
				$("#inv_capacity_error_msg_" + index).remove();
			}
			if ($("#inv_make_error_msg_" + index).html() != undefined) {
				$("#inv_make_error_msg_" + index).parent().removeClass('has-error');
				$("#inv_make_error_msg_" + index).remove();
			}
			if ($("#type_of_inverter_used_error_msg_" + index).html() != undefined) {
				$("#type_of_inverter_used_error_msg_" + index).parent().removeClass('has-error');
				$("#type_of_inverter_used_error_msg_" + index).remove();
			}


		});
		$("#tbl_inverter_info > tbody  > tr").each(function(index, tr) {
			var nos_inv = $("#nos_inv_" + index).val() !== '' ? parseFloat($("#nos_inv_" + index).val()) : 0;
			var inv_capacity = $("#inv_capacity_" + index).val() ? parseFloat($("#inv_capacity_" + index).val()) : 0;
			var inv_make = $("#inv_make_" + index).val() ? $("#inv_make_" + index).val() : 0;
			var type_of_inv = $("#type_of_inverter_used_" + index).val() ? $("#type_of_inverter_used_" + index).val() : 0;

			if (nos_inv <= 0 || inv_capacity <= 0 || inv_make <= 0 || type_of_inv <= 0) {
				addRow = 0;
			}


			if (nos_inv <= 0) {
				$("#nos_inv_" + index).parent().addClass('has-error');
				$("#nos_inv_" + index).parent().append('<div class="help-block" id="nos_inv_error_msg_' + index + '">Required</div>');
			}
			if (inv_capacity <= 0) {
				$("#inv_capacity_" + index).parent().addClass('has-error');
				$("#inv_capacity_" + index).parent().append('<div class="help-block" id="inv_capacity_error_msg_' + index + '">Required</div>');
			}
			if (inv_make <= 0) {
				$("#inv_make_" + index).parent().addClass('has-error');
				$("#inv_make_" + index).parent().append('<div class="help-block" id="inv_make_error_msg_' + index + '">Required</div>');
			}
			if (type_of_inv <= 0) {
				$("#type_of_inverter_used_" + index).parent().addClass('has-error');
				$("#type_of_inverter_used_" + index).parent().append('<div class="help-block" id="type_of_inverter_used_error_msg_' + index + '">Required</div>');
			}

		});

		if (addRow == 1) {
			var newRow = $("#tbl_inverter_info tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find('.id_module').val('');
			newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowInverter(this)" value="-" />');
			$("#tbl_inverter_info").append(newRow);
		}
		ResetInverterRowID();
	}

	function ResetInverterRowID() {
		$("#tbl_inverter_info > tbody  > tr").each(function(index, tr) {

			$(tr).find(".inverter_no_cls").attr("id", "nos_inv_" + index);
			$(tr).find(".inverter_no_cls").attr("name", "nos_inv[" + index + "]");
			$(tr).find(".inverter_cap_cls").attr("id", "inv_capacity_" + index);
			$(tr).find(".inverter_cap_cls").attr("name", "inv_capacity[" + index + "]");
			$(tr).find(".inverter_capacity_cls").attr("id", "inv_total_capacity_" + index);
			$(tr).find(".inverter_capacity_cls").attr("name", "inv_total_capacity[" + index + "]");
			$(tr).find(".inverter_make_cls").attr("id", "inv_make_" + index);
			$(tr).find(".inverter_make_cls").attr("name", "inv_make[" + index + "]");
			$(tr).find(".type_of_inverter_used_cls").attr("id", "type_of_inverter_used_" + index);
			$(tr).find(".type_of_inverter_used_cls").attr("name", "type_of_inverter_used[" + index + "]");
			$(tr).find(".id_inverter").attr("id", "id_inverter_" + index + "");
			$(tr).find(".id_inverter").attr("name", "id_inverter[" + index + "]");
			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
		});
	}

	function deleterRowInverter(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();
		ResetInverterRowID();
		setTotalAll();
	}

	function changeInverterRowCapacity(_obj) {
		var currentID = $(_obj).attr("id");
		var arrIds = currentID.split("_");

		var total_capacity = ($("#nos_inv_" + arrIds[2]).val() * $("#inv_capacity_" + arrIds[2]).val()) / 1000;
		$("#inv_total_capacity_" + arrIds[2]).val(total_capacity.toFixed(3));
		setTotalAll();
	}
	/*******Inverter add component related code end ************/


	<?php if ($errorModule == 1) { ?>
		AddModuleRow();
	<?php } ?>
	<?php if ($errorInverter == 1) { ?>
		AddInverterRow();
	<?php } ?>
	
	

	setTotalAll();

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

  
</script>