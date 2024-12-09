<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style>
	.wind-upload-file {
		margin-bottom: 20px;
	}

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

	.greenhead {
		background-color: #4cc972 !important;
		color: #fff;
		border-top: 1px solid;
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

	.rlmmNSel {
		display: none;
	}

	.textCheckeboxLeft {
		margin-left: 25px !important;
	}

	input[type="checkbox"][readonly] {
		pointer-events: none;
	}

	/* .sale-to-discom {
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
	} */

	th {
		font-size: smaller !important;
	}

	.table-container {
		max-height: 400px;
		/* Adjust this value as needed */
		overflow-y: auto;
		position: relative;
	}

	.table-container table {
		width: 100%;
		border-collapse: collapse;
	}

	.table-container thead th {
		position: sticky;
		top: 0;
		background: #fff;
		/* Optional: background color */
		z-index: 1;
		/* Ensures the header stays above the body rows */
		box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
		/* Optional: shadow for better visibility */
		background-color: #4cc972 !important;
		color: #fff;
		border-top: 1px solid;
	}

	.table-container tbody tr:hover {
		background-color: #f5f5f5;
		/* Optional: hover effect */
	}
</style>
<?php
$allocatedCategory 	= 3;
$this->Html->addCrumb($pageTitle);
$Report 			= "";
?>
<div class="container applay-online-from">

	<?php echo $this->Flash->render('cutom_admin'); ?>

	<div class="tabs tabs-bottom tabs-simple nk_tabs">
		<ul class="nav nav-tabs">
			<li class="<?php echo ($tab_id == 1) ? 'active' : ''; ?>">
				<a href="#general" data-toggle="tab">General Profile</a>
			</li>
			<li class="<?php echo empty($applicationID) ? 'desible' : ''; ?> <?php echo ($tab_id == 2) ? 'active' : ''; ?>">
				<a href="<?php echo empty($applicationID) ? 'javascript;' : '#technical'; ?>" data-toggle="tab" <?php echo empty($applicationID) ? 'decibel="true"' : ''; ?>>Technical Details</a>
			</li>
			<li class="<?php echo empty($applicationID) ? 'desible' : ''; ?> <?php echo ($tab_id == 3) ? 'active' : ''; ?>">
				<a href="<?php echo empty($applicationID) ? 'javascript;' : '#project'; ?>" data-toggle="tab" onClick="javascript:LandDoc();">Project Details</a>
			</li>
			<li class="<?php echo empty($applicationID) ? 'desible' : ''; ?> <?php echo ($tab_id == 4) ? 'active' : ''; ?>">
				<a href="<?php echo empty($applicationID) ? 'javascript;' : '#upload'; ?>" data-toggle="tab">Upload</a>
			</li>
			<?php
			
				$payFees = 0;
				if(!empty($Applications)){
					if($Applications->tab_1 == 1 && $Applications->tab_2 == 1 && $Applications->tab_3 == 1 && $Applications->tab_4 == 1){
						$payFees = 1;
					}
				}
					
			?>
			<li class="<?php echo ($payFees == 0)  ? 'desible' : ''; ?> <?php echo ($tab_id == 5 && $payFees == 1) ? 'active' : ''; ?>">
				<a href="<?php echo ($payFees == 0)  ? 'javascript;' : '#fees'; ?>" data-toggle="tab" <?php echo ($payFees == 0)? 'decibel="true"' : ''; ?>>Fees Structure</a>				
			</li>

		</ul>
		<div class="subsidy-claim tab-content">
			<div class="tab-pane <?php echo ($tab_id == 1) ? 'active' : ''; ?>" id="general">

				<?php echo $this->element('application-developer-permission/general_profile'); ?>
			</div>
			<div class="tab-pane <?php echo ($tab_id == 2) ? 'active' : ''; ?>" id="technical">

				<?php echo $this->element('application-developer-permission/hybrid_technical_details'); ?>

			</div>
			<div class="tab-pane <?php echo ($tab_id == 3) ? 'active' : ''; ?>" id="project">

				<?php echo $this->element('application-developer-permission/hybrid_project_details'); ?>

			</div>
			<div class="tab-pane <?php echo ($tab_id == 4) ? 'active' : ''; ?>" id="upload">

				<?php echo $this->element('application-developer-permission/hybrid_upload_details'); ?>

			</div>
			<div class="tab-pane <?php echo ($tab_id == 5 && $payFees == 1) ? 'active' : ''; ?>" id="fees">
				<?php echo $this->element('application-developer-permission/fees_structure'); ?>
			</div>

		</div>
	</div>



</div>

<script type="text/javascript">
	$(document).ready(function() {
		LandDoc();
		$("#undertaking_dec").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#undertaking_dec-file-errors',
			maxFileSize: '1024',
		});

		$("#micro_sitting_drawing").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#micro_sitting_drawing-file-errors',
			maxFileSize: '1024',
		});

		$("#proof_of_ownership").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#proof_of_ownership-file-errors',
			maxFileSize: '1024',
		});
		$("#notarized_contract").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#notarized_contract-file-errors',
			maxFileSize: '1024',
		});
		$("#ca_certificate").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#ca_certificate-file-errors',
			maxFileSize: '1024',
		});
		$("#invoice_with_gst").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#invoice_with_gst-file-errors',
			maxFileSize: '1024',
		});
		$("#share_subscription").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#share_subscription-file-errors',
			maxFileSize: '1024',
		});
		$("#pvt_proposed_land").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#pvt_proposed_land-file-errors',
			maxFileSize: '1024',
		});

		$("#proj_sale_to_discom_no_due").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#proj_sale_to_discom_no_due-file-errors',
			maxFileSize: '1024',
		});
		$("#proj_captive_use_no_due").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#proj_captive_use_no_due-file-errors',
			maxFileSize: '1024',
		});

		$("#deed_doc_0").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#deed_doc_0-file-errors',
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

		$("#copy_of_conventional_electricity").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#copy_of_conventional_electricity-file-errors',
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
		$("#rec_accrediation_cer").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#rec_accrediation_cer-file-errors',
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
		$("#copy_of_electricity_bill").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#copy_of_electricity_bill-file-errors',
			maxFileSize: '1024',
		});

		$("#permission_letter_of_getco").fileinput({
			showUpload: false,
			showPreview: false,
			dropZoneEnabled: false,
			mainClass: "input-group-md",
			allowedFileExtensions: ["pdf"],
			elErrorContainer: '#permission_letter_of_getco-file-errors',
			maxFileSize: '1024',
		});

		$('.fa').popover({
			trigger: "hover"
		});

		$(".datepicker").datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});

		$('.applicationform3').submit(function() {
			$('.applicationform3 input ,.applicationform3 select').removeAttr('disabled');
		});


		$(".AddDiscomRow").click(function() {
			AddDiscomRow();
		});
		$(".AddLandRow").click(function() {
			AddLandRow();
		});

		$(".AddPoolingSubRow").click(function() {
			AddPoolingSubRow();
		});

		$(".AddGetcoSubRow").click(function() {
			AddGetcoSubRow();
		});

		$(".AddConsumerShareRow").click(function() {
			AddConsumerShareRow();
		});


		$('#end_stu').on('change', function() {
			if (this.value == '1') {
				$(".captive").hide();
				$(".third-party").hide();
				$(".sale-to-discom").show();
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
				$(".captive-only").hide();
				$(".third-party").find("option:selected").removeAttr("selected");
				$(".captive").find("option:selected").removeAttr("selected");
			} else if (this.value == '2') {
				$(".captive").show();
				$(".captive-only").show();
				$(".third-party").hide();
				$(".sale-to-discom").hide();
				$(".third-party").find("option:selected").removeAttr("selected");
			} else if (this.value == '3') {
				$(".captive").hide();
				$(".captive-only").hide();
				$(".third-party").show();
				$(".sale-to-discom").hide();
				$(".captive").find("option:selected").removeAttr("selected");
			} else if (this.value == '') {
				$(".captive").hide();
				$(".third-party").hide();
				$(".sale-to-discom").hide();
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
				$(".captive-only").hide();
				$(".third-party").find("option:selected").removeAttr("selected");
				$(".captive").find("option:selected").removeAttr("selected");
			}

		});
		$('#end_stu').trigger('change');


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
			} else if (this.value == -1) {
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
			} else if (this.value == -1) {
				$(".captive-with-rpo").hide();
				$(".project-for-third-party").hide();
				$(".project-with-RE-mechanism").hide();
			}
		});
		$('#third_party').trigger('change');

	});

	jQuery(document).ready(function($) {

		$(".form-check-input").change(function() {

			var totalCap = 0,
				values = [],
				count = 0;
			$('input[type=checkbox]').each(function() {

				if ($(this).is(':checked')) {
					count++;
					totalCap += parseInt($(this).closest('tr').find(".capacity").text());
					//values.push($(this).attr("data-id"));
					$(this).val($(this).attr("data-id"));
				}
			});

			//$('#geo-ids').val(values);
			var total_capacity = (totalCap) / 1000;
			$("#add_capacity").val(total_capacity.toFixed(3));
			$("#add_wtg").val(count);
		});
		$(".form-check-input").trigger("change")
	});

	function toggle_transferor_in_stu() {

		$(".transferor_in_stu").addClass('hide');
		$("input[type=radio][name=app_trans_to_stu]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".transferor_in_stu").addClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".transferor_in_stu").removeClass('hide');
			}
		});
	}
	toggle_transferor_in_stu();

	function toggle_wheeled() {

		$(".wheeled").addClass('hide');
		$("input[type=radio][name=wheel_energy_multi_location]").each(function(opt) {
			if ($(this).is(":checked") && $(this).val() == 1) {
				$(".wheeled").removeClass('hide');
			}
			if ($(this).is(":checked") && $(this).val() == 0) {
				$(".wheeled").addClass('hide');
			}
		});
	}
	toggle_wheeled();

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

	function reSharePerValidateNumber(key) {
		var keycode = (key.which) ? key.which : key.keyCode;
		if (!(keycode == 8) && (keycode < 48 || keycode > 57)) {
			return false;
		}
	}

	function validateCharacter(key) {
		var keycode = (key.which) ? key.which : key.keyCode;
		// Allow backspace (keycode 8), space (keycode 32), uppercase letters (65-90), and lowercase letters (97-122)
		if (!(keycode == 8 || keycode == 32) && (keycode < 65 || (keycode > 90 && keycode < 97) || keycode > 122)) {
			return false;
		}
		return true;
	}

	function validateEastingDecimalInput(input) {
		input.value = input.value.replace(/[^\d.]/g, '');
		let parts = input.value.split('.');
		let integerPart = parts[0];
		let decimalPart = parts[1];
		if (integerPart.length > 6) {
			input.value = integerPart.slice(0, 6);
			integerPart = 6;
		}
		if (decimalPart && decimalPart.length > 3) {
			input.value = integerPart + '.' + decimalPart.slice(0, 3);
		}
	}

	function validateNorthingDecimalInput(input) {
		input.value = input.value.replace(/[^0-9.]/g, '');
		let parts = input.value.split('.');
		let integerPart = parts[0];
		let decimalPart = parts[1];
		if (integerPart.length > 7) {
			input.value = input.value.slice(0, 7);
			integerPart = input.value.slice(0, 7);
		}
		if (decimalPart && decimalPart.length > 3) {
			input.value = integerPart + '.' + decimalPart.slice(0, 3);
		}
	}

	function checkRange(v) {

		$("#error_re").removeClass('has-error');
		$("#error_re").find('.help-block').remove();
		if (v < 51 || v > 74) {
			$("#error_re").addClass('has-error');
			$("#error_re").append('<div class="help-block">Enter Share Persontage Between 51% to 74%</div>');
		}
		if (v > 51 || v < 74) {
			$("#equity_persontage_0").val(100 - v);
			$("#share_total").val(100 - v);
		}
	}

	function changeConsumerSharePer(_obj) {
		var total = 0;

		$(".equity_persontage_cls").each(function(index, val) {
			$("#error_consumer").removeClass('has-error');
			$("#error_consumer").find('.help-block').remove();

			if ($(this).val() > 0) {
				total = total + parseInt($(this).val());
				$("#share_total").val(total);
			}
		});
		ety_per = $("#re_equity_persontage").val();
		per = parseInt(ety_per) + total;
		if (total < 26 || total > 49 || per > 100) {
			$("#error_consumer").addClass('has-error');
			$("#error_consumer").append('<div class="help-block">Enter Share Persontage Between 26% to 49%</div>');
		}
	}

	function getTotalShare() {
		var total = 0;
		$(".equity_persontage_cls").each(function(index, val) {
			if ($(this).val() > 0) {
				total = total + parseInt($(this).val());
			}
		});
		ety_per = $("#re_equity_persontage").val();
		share_total = parseInt(ety_per) + parseInt(total);
		if (share_total < 100) {
			return 1;
		} else {
			return 0;
		}
	}

	function change_injection() {

		var injectionLevel = $("#grid_connectivity").val();
		$(".sale-to-discom").addClass('hide');
		$(".captive").addClass('hide');
			$(".third-party").addClass('hide');
		if (injectionLevel == 1) {
			$(".cls-ctu").addClass('hide');
			$(".cls-stu").removeClass('hide');
			$(".sale-to-discom").removeClass('hide');
			$(".captive").removeClass('hide');
			$(".third-party").removeClass('hide');

		} else {
			$(".cls-ctu").removeClass('hide');
			$(".cls-stu").addClass('hide');

		}
		//clickstd();
	}

	// function clickstd() {		
	// 	var injectionLevel = $("#grid_connectivity").val();
	// 	$(".sale-discom").addClass('hide');
	// 	if (injectionLevel == 1) {
	// 		if ($("#end_stu").val() == 3) {
	// 			$(".sale-discom").removeClass('hide');
	// 		}			
	// 	}
	// }
	change_injection();

	function ShowHideDesignationOthers(designation) {
		if ($("#" + designation).val() == 'Others') {
			$("#div_" + designation).show();
		} else {
			$("#div_" + designation).addClass('hide');
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

	function agreeClick() {
		$(".terms_agree").prop('checked', true);
		$('#agree_popup').modal('hide');
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

	function removeEnergy(id, encode_application_id, encode_dev_app_id) {
		var id = id;
		var encode_application_id = encode_application_id;
		var encode_dev_app_id = encode_dev_app_id;

		swal({
				title: "Are you sure?",
				text: "You want to delete the data?",
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
						url: "/ApplicationDeveloperPermission/remove_energy",
						data: {
							'id': id,
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
								swal("Deleted!", "Your Data has been deleted.", "success");

								window.location.href = '/wind-permission/' + encode_application_id + '/' + encode_dev_app_id + '/2';
							}
						}
					});

				} else {
					swal("Cancelled", "Your Data is safe :)", "error");
				}
			});
	}



	/********** Multiple Location Wheeled **********/
	function AddDiscomRow() {
		var addRow = 1;
		$("#tbl_energy_wheeled > tbody  > tr").each(function(index, tr) {

			if ($("#energy_discom_error_msg_" + index).html() != undefined) {
				$("#energy_discom_error_msg_" + index).parent().removeClass('has-error');
				$("#energy_discom_error_msg_" + index).remove();
			}
			if ($("#energy_per_error_msg_" + index).html() != undefined) {
				$("#energy_per_error_msg_" + index).parent().removeClass('has-error');
				$("#energy_per_error_msg_" + index).remove();
			}

		});
		$("#tbl_energy_wheeled > tbody  > tr").each(function(index, tr) {
			var energy_discom = $("#energy_discom_" + index).val() !== '' ? parseFloat($("#energy_discom_" + index).val()) : 0;
			var energy_per = $("#energy_per_" + index).val() ? $("#energy_per_" + index).val() : 0;

			if (energy_discom <= 0 || energy_per <= 0) {
				addRow = 0;
			}
			if (energy_discom <= 0) {
				$("#energy_discom_" + index).parent().addClass('has-error');
				$("#energy_discom_" + index).parent().append('<div class="help-block energy_discom_error_msg_cls" id="energy_discom_error_msg_' + index + '">Please select discom</div>');
			}
			if (energy_per <= 0) {
				$("#energy_per_" + index).parent().addClass('has-error');
				$("#energy_per_" + index).parent().append('<div class="help-block energy_per_error_msg_cls" id="energy_per_error_msg_' + index + '">Please enter energy percentage</div>');
			}
		});
		if (addRow == 1) {
			var newRow = $("#tbl_energy_wheeled tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find(".lastrow").html('<input class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleterRowEnergy(this)" value="-" />');
			$("#tbl_energy_wheeled").append(newRow);
			ResetEnergyRowID();
		}
	}

	function ResetEnergyRowID() {
		$("#tbl_energy_wheeled > tbody  > tr").each(function(index, tr) {
			$(tr).find(".energy_discom_cls").attr("id", "energy_discom_" + index);
			$(tr).find(".energy_per_cls").attr("id", "energy_per_" + index);
			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
			$(tr).find(".energy_discom_error_msg_cls").attr("id", "energy_discom_error_msg_" + index);
			$(tr).find(".energy_per_error_msg_cls").attr("id", "energy_per_error_msg_" + index);
		});
	}

	function deleterRowEnergy(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();
		ResetEnergyRowID();
	}
	/********* End Multiple Location Wheeled ***********/

	/**** Land code start*/

	function LandDoc() {

		$("#tbl_land_info > tbody  > tr").each(function(index, tr) {

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

	function removePoolingSub(id, encode_application_id) {
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
						url: "/ApplicationDeveloperPermission/remove_pooling_sub",
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

	function removeGetcoSub(id, encode_application_id) {
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
						url: "/ApplicationDeveloperPermission/remove_getco_sub",
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

		//var addRow = 1;
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
			if (deed_doc <= 0 && deed_file <= 0) {

				$("#deed_doc_" + index).closest('div.land-file').after().addClass('has-error');
				$("#deed_doc_" + index).closest('div.land-file').after().append('<div class="help-block" id="a_deed_doc_error_msg_' + index + '">Required</div>');
			}

		});
		// if (addRow == 1) {
		// 	var newRow = $("#tbl_land_info tr:last").clone(true).find('.rfibox').val('').end();
		// 	newRow.find(".lastrow").html('<button class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowLand(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>');
		// 	newRow.find('.id_land').val('');
		// 	newRow.find(".deed_file_cls").val('');
		// 	newRow.find('strong').remove();
		// 	$("#tbl_land_info").append(newRow);
		// 	srno = $("#tbl_land_info").find("tr").length;
		// 	$("#tbl_land_info tr:last").children('td:first').text(srno);
		// }
		//ResetLandRowID();

	}

	// function ResetLandRowID() {
	// 	$("#tbl_land_info > tbody  > tr").each(function(index, tr) {

	// 		$(tr).find(".land_category_cls").attr("id", "land_category_" + index);
	// 		$(tr).find(".land_category_cls").attr("name", "land_category[" + index + "]");

	// 		$(tr).find(".land_plot_servey_no_cls").attr("id", "land_plot_servey_no_" + index);
	// 		$(tr).find(".land_plot_servey_no_cls").attr("name", "land_plot_servey_no[" + index + "]");

	// 		$(tr).find(".land_taluka_cls").attr("id", "land_taluka_" + index);
	// 		$(tr).find(".land_taluka_cls").attr("name", "land_taluka[" + index + "]");

	// 		$(tr).find(".land_district_cls").attr("id", "land_district_" + index);
	// 		$(tr).find(".land_district_cls").attr("name", "land_district[" + index + "]");

	// 		$(tr).find(".land_latitude_cls").attr("id", "land_latitude_" + index);
	// 		$(tr).find(".land_latitude_cls").attr("name", "land_latitude[" + index + "]");

	// 		$(tr).find(".land_longitude_cls").attr("id", "land_longitude_" + index);
	// 		$(tr).find(".land_longitude_cls").attr("name", "land_longitude[" + index + "]");

	// 		$(tr).find(".area_of_land_cls").attr("id", "area_of_land_" + index);
	// 		$(tr).find(".area_of_land_cls").attr("name", "area_of_land[" + index + "]");

	// 		$(tr).find(".deed_of_land_cls").attr("id", "deed_of_land_" + index);
	// 		$(tr).find(".deed_of_land_cls").attr("name", "deed_of_land[" + index + "]");

	// 		$(tr).find(".a_deed_doc_cls").attr("id", "deed_doc_" + index);
	// 		$(tr).find(".a_deed_doc_cls").attr("name", "a_deed_doc_" + index);

	// 		$(tr).find(".deed_file_cls").attr("id", "deed_file_" + index);
	// 		$(tr).find(".deed_file_cls").attr("name", "deed_file[" + index + "]");

	// 		$(tr).find(".lastrow").attr("id", "actionrange_" + index);

	// 		$(tr).find(".deed_doc_error_cls").attr("id", "deed_doc_" + index + "-file-errors");

	// 		$(tr).find(".id_land").attr("id", "id_land_" + index + "");
	// 		$(tr).find(".id_land").attr("name", "id_land[" + index + "]");

	// 		$("#deed_doc_" + index).fileinput({
	// 			showUpload: false,
	// 			showPreview: false,
	// 			dropZoneEnabled: false,
	// 			mainClass: "input-group-md",
	// 			allowedFileExtensions: ["pdf"],
	// 			elErrorContainer: '#deed_doc_' + index + '-file-errors',
	// 			maxFileSize: '1024',
	// 		});

	// 	});


	// }

	// function deleteRowLand(_obj) {
	// 	var deleteCellId = $(_obj).parent().attr("id");
	// 	var arrData = deleteCellId.split("_");
	// 	var idval = parseInt(arrData[1]);
	// 	$(_obj).parent().parent().remove();
	// 	srno = $("#tbl_land_info").find("tr").length;
	// 	$("#tbl_land_info tr:last").children('td:first').text(srno);
	// 	ResetLandRowID();

	// }

	// Evacuation Pooling Substation
	function AddPoolingSubRow() {

		var addRow = 1;
		$("#tbl_pooling_sub_info > tbody  > tr").each(function(index, tr) {


			if ($("#name_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#name_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#name_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#distict_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#distict_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#distict_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#taluka_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#taluka_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#taluka_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#village_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#village_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#village_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#cap_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#cap_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#cap_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#vol_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#vol_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#vol_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#sub_mw_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#sub_mw_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#sub_mw_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#sub_mva_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#sub_mva_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#sub_mva_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#conn_mw_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#conn_mw_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#conn_mw_of_pooling_sub_error_msg_" + index).remove();
			}
			if ($("#conn_mva_of_pooling_sub_error_msg_" + index).html() != undefined) {
				$("#conn_mva_of_pooling_sub_error_msg_" + index).parent().removeClass('has-error');
				$("#conn_mva_of_pooling_sub_error_msg_" + index).remove();
			}
		});
		$("#tbl_pooling_sub_info > tbody  > tr").each(function(index, tr) {

			var name_of_pooling_sub = $("#name_of_pooling_sub_" + index).val() ? $("#name_of_pooling_sub_" + index).val() : 0;
			var distict_of_pooling_sub = $("#distict_of_pooling_sub_" + index).val() ? $("#distict_of_pooling_sub_" + index).val() : 0;
			var taluka_of_pooling_sub = $("#taluka_of_pooling_sub_" + index).val() ? $("#taluka_of_pooling_sub_" + index).val() : 0;
			var village_of_pooling_sub = $("#village_of_pooling_sub_" + index).val() ? $("#village_of_pooling_sub_" + index).val() : 0;
			var cap_of_pooling_sub = $("#cap_of_pooling_sub_" + index).val() ? $("#cap_of_pooling_sub_" + index).val() : 0;
			var vol_of_pooling_sub = $("#vol_of_pooling_sub_" + index).val() ? $("#vol_of_pooling_sub_" + index).val() : 0;
			var sub_mw_of_pooling_sub = $("#sub_mw_of_pooling_sub_" + index).val() ? $("#sub_mw_of_pooling_sub_" + index).val() : 0;
			var sub_mva_of_pooling_sub = $("#sub_mva_of_pooling_sub_" + index).val() ? $("#sub_mva_of_pooling_sub_" + index).val() : 0;
			var conn_mw_of_pooling_sub = $("#conn_mw_of_pooling_sub_" + index).val() ? $("#conn_mw_of_pooling_sub_" + index).val() : 0;
			var conn_mva_of_pooling_sub = $("#conn_mva_of_pooling_sub_" + index).val() ? $("#conn_mva_of_pooling_sub_" + index).val() : 0;


			if (name_of_pooling_sub <= 0 || distict_of_pooling_sub <= 0 || taluka_of_pooling_sub <= 0 || village_of_pooling_sub <= 0 || cap_of_pooling_sub <= 0 || vol_of_pooling_sub <= 0 || sub_mw_of_pooling_sub <= 0 || sub_mva_of_pooling_sub <= 0 || conn_mw_of_pooling_sub <= 0 || conn_mva_of_pooling_sub <= 0) {
				addRow = 0;
			}

			if (name_of_pooling_sub <= 0) {
				$("#name_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#name_of_pooling_sub_" + index).parent().append('<div class="help-block" id="name_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (distict_of_pooling_sub <= 0) {
				$("#distict_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#distict_of_pooling_sub_" + index).parent().append('<div class="help-block" id="distict_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (taluka_of_pooling_sub <= 0) {
				$("#taluka_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#taluka_of_pooling_sub_" + index).parent().append('<div class="help-block" id="taluka_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (village_of_pooling_sub <= 0) {
				$("#village_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#village_of_pooling_sub_" + index).parent().append('<div class="help-block" id="village_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (cap_of_pooling_sub <= 0) {
				$("#cap_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#cap_of_pooling_sub_" + index).parent().append('<div class="help-block" id="cap_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (vol_of_pooling_sub <= 0) {
				$("#vol_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#vol_of_pooling_sub_" + index).parent().append('<div class="help-block" id="vol_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (sub_mw_of_pooling_sub <= 0) {
				$("#sub_mw_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#sub_mw_of_pooling_sub_" + index).parent().append('<div class="help-block" id="sub_mw_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (sub_mva_of_pooling_sub <= 0) {
				$("#sub_mva_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#sub_mva_of_pooling_sub_" + index).parent().append('<div class="help-block" id="sub_mva_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (conn_mw_of_pooling_sub <= 0) {
				$("#conn_mw_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#conn_mw_of_pooling_sub_" + index).parent().append('<div class="help-block" id="conn_mw_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}
			if (conn_mva_of_pooling_sub <= 0) {
				$("#conn_mva_of_pooling_sub_" + index).parent().addClass('has-error');
				$("#conn_mva_of_pooling_sub_" + index).parent().append('<div class="help-block" id="conn_mva_of_pooling_sub_error_msg_' + index + '">Required</div>');
			}

		});
		if (addRow == 1) {
			var newRow = $("#tbl_pooling_sub_info tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find(".lastrow").html('<button class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowPoolingSub(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>');
			$("#tbl_pooling_sub_info").append(newRow);
		}
		ResetPoolingSubRowID();

	}

	function ResetPoolingSubRowID() {
		$("#tbl_pooling_sub_info > tbody  > tr").each(function(index, tr) {

			$(tr).find(".name_of_pooling_sub_cls").attr("id", "name_of_pooling_sub_" + index);
			$(tr).find(".name_of_pooling_sub_cls").attr("name", "name_of_pooling_sub[" + index + "]");

			$(tr).find(".distict_of_pooling_sub_cls").attr("id", "distict_of_pooling_sub_" + index);
			$(tr).find(".distict_of_pooling_sub_cls").attr("name", "distict_of_pooling_sub[" + index + "]");

			$(tr).find(".taluka_of_pooling_sub_cls").attr("id", "taluka_of_pooling_sub_" + index);
			$(tr).find(".taluka_of_pooling_sub_cls").attr("name", "taluka_of_pooling_sub[" + index + "]");

			$(tr).find(".village_of_pooling_sub_cls").attr("id", "village_of_pooling_sub_" + index);
			$(tr).find(".village_of_pooling_sub_cls").attr("name", "village_of_pooling_sub[" + index + "]");

			$(tr).find(".cap_of_pooling_sub_cls").attr("id", "cap_of_pooling_sub_" + index);
			$(tr).find(".cap_of_pooling_sub_cls").attr("name", "cap_of_pooling_sub[" + index + "]");

			$(tr).find(".vol_of_pooling_sub_cls").attr("id", "vol_of_pooling_sub_" + index);
			$(tr).find(".vol_of_pooling_sub_cls").attr("name", "vol_of_pooling_sub[" + index + "]");

			$(tr).find(".sub_mw_of_pooling_sub_cls").attr("id", "sub_mw_of_pooling_sub_" + index);
			$(tr).find(".sub_mw_of_pooling_sub_cls").attr("name", "sub_mw_of_pooling_sub[" + index + "]");

			$(tr).find(".sub_mva_of_pooling_sub_cls").attr("id", "sub_mva_of_pooling_sub_" + index);
			$(tr).find(".sub_mva_of_pooling_sub_cls").attr("name", "sub_mva_of_pooling_sub[" + index + "]");

			$(tr).find(".conn_mw_of_pooling_sub_cls").attr("id", "conn_mw_of_pooling_sub_" + index);
			$(tr).find(".conn_mw_of_pooling_sub_cls").attr("name", "conn_mw_of_pooling_sub[" + index + "]");

			$(tr).find(".conn_mva_of_pooling_sub_cls").attr("id", "conn_mva_of_pooling_sub_" + index);
			$(tr).find(".conn_mva_of_pooling_sub_cls").attr("name", "conn_mva_of_pooling_sub[" + index + "]");

			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
		});

	}

	function deleteRowPoolingSub(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();

		ResetPoolingSubRowID();

	}

	// Evacuation GETCO/PECL Substation
	function AddGetcoSubRow() {

		var addRow = 1;
		$("#tbl_getco_sub_info > tbody  > tr").each(function(index, tr) {

			if ($("#name_of_getco_error_msg_" + index).html() != undefined) {
				$("#name_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#name_of_getco_error_msg_" + index).remove();
			}
			if ($("#distict_of_getco_error_msg_" + index).html() != undefined) {
				$("#distict_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#distict_of_getco_error_msg_" + index).remove();
			}
			if ($("#taluka_of_getco_error_msg_" + index).html() != undefined) {
				$("#taluka_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#taluka_of_getco_error_msg_" + index).remove();
			}
			if ($("#village_of_getco_error_msg_" + index).html() != undefined) {
				$("#village_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#village_of_getco_error_msg_" + index).remove();
			}
			if ($("#cap_of_getco_error_msg_" + index).html() != undefined) {
				$("#cap_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#cap_of_getco_error_msg_" + index).remove();
			}
			if ($("#vol_of_getco_error_msg_" + index).html() != undefined) {
				$("#vol_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#vol_of_getco_error_msg_" + index).remove();
			}
			if ($("#sub_mw_of_getco_error_msg_" + index).html() != undefined) {
				$("#sub_mw_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#sub_mw_of_getco_error_msg_" + index).remove();
			}
			if ($("#sub_mva_of_getco_error_msg_" + index).html() != undefined) {
				$("#sub_mva_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#sub_mva_of_getco_error_msg_" + index).remove();
			}
			if ($("#conn_mw_of_getco_error_msg_" + index).html() != undefined) {
				$("#conn_mw_of_getco_error_msg_" + index).parent().removeClass('has-error');
				$("#conn_mw_of_getco_error_msg_" + index).remove();
			}

		});
		$("#tbl_getco_sub_info > tbody  > tr").each(function(index, tr) {

			var name_of_getco = $("#name_of_getco_" + index).val() ? $("#name_of_getco_" + index).val() : 0;
			var distict_of_getco = $("#distict_of_getco_" + index).val() ? $("#distict_of_getco_" + index).val() : 0;
			var taluka_of_getco = $("#taluka_of_getco_" + index).val() ? $("#taluka_of_getco_" + index).val() : 0;
			var village_of_getco = $("#village_of_getco_" + index).val() ? $("#village_of_getco_" + index).val() : 0;
			var cap_of_getco = $("#cap_of_getco_" + index).val() ? $("#cap_of_getco_" + index).val() : 0;
			var vol_of_getco = $("#vol_of_getco_" + index).val() ? $("#vol_of_getco_" + index).val() : 0;
			var sub_mw_of_getco = $("#sub_mw_of_getco_" + index).val() ? $("#sub_mw_of_getco_" + index).val() : 0;
			var sub_mva_of_getco = $("#sub_mva_of_getco_" + index).val() ? $("#sub_mva_of_getco_" + index).val() : 0;
			var conn_mw_of_getco = $("#conn_mw_of_getco_" + index).val() ? $("#conn_mw_of_getco_" + index).val() : 0;



			if (name_of_getco <= 0 || distict_of_getco <= 0 || taluka_of_getco <= 0 || village_of_getco <= 0 || cap_of_getco <= 0 || vol_of_getco <= 0 || sub_mw_of_getco <= 0 || sub_mva_of_getco <= 0 || conn_mw_of_getco <= 0) {
				addRow = 0;
			}

			if (name_of_getco <= 0) {
				$("#name_of_getco_" + index).parent().addClass('has-error');
				$("#name_of_getco_" + index).parent().append('<div class="help-block" id="name_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (distict_of_getco <= 0) {
				$("#distict_of_getco_" + index).parent().addClass('has-error');
				$("#distict_of_getco_" + index).parent().append('<div class="help-block" id="distict_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (taluka_of_getco <= 0) {
				$("#taluka_of_getco_" + index).parent().addClass('has-error');
				$("#taluka_of_getco_" + index).parent().append('<div class="help-block" id="taluka_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (village_of_getco <= 0) {
				$("#village_of_getco_" + index).parent().addClass('has-error');
				$("#village_of_getco_" + index).parent().append('<div class="help-block" id="village_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (cap_of_getco <= 0) {
				$("#cap_of_getco_" + index).parent().addClass('has-error');
				$("#cap_of_getco_" + index).parent().append('<div class="help-block" id="cap_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (vol_of_getco <= 0) {
				$("#vol_of_getco_" + index).parent().addClass('has-error');
				$("#vol_of_getco_" + index).parent().append('<div class="help-block" id="vol_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (sub_mw_of_getco <= 0) {
				$("#sub_mw_of_getco_" + index).parent().addClass('has-error');
				$("#sub_mw_of_getco_" + index).parent().append('<div class="help-block" id="sub_mw_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (sub_mva_of_getco <= 0) {
				$("#sub_mva_of_getco_" + index).parent().addClass('has-error');
				$("#sub_mva_of_getco_" + index).parent().append('<div class="help-block" id="sub_mva_of_getco_error_msg_' + index + '">Required</div>');
			}
			if (conn_mw_of_getco <= 0) {
				$("#conn_mw_of_getco_" + index).parent().addClass('has-error');
				$("#conn_mw_of_getco_" + index).parent().append('<div class="help-block" id="conn_mw_of_getco_error_msg_' + index + '">Required</div>');
			}

		});
		if (addRow == 1) {
			var newRow = $("#tbl_getco_sub_info tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find(".lastrow").html('<button class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowGetcoSub(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>');
			$("#tbl_getco_sub_info").append(newRow);
		}
		ResetGetcoSubRowID();

	}

	function ResetGetcoSubRowID() {
		$("#tbl_getco_sub_info > tbody  > tr").each(function(index, tr) {

			$(tr).find(".name_of_getco_cls").attr("id", "name_of_getco_" + index);
			$(tr).find(".name_of_getco_cls").attr("name", "name_of_getco[" + index + "]");

			$(tr).find(".distict_of_getco_cls").attr("id", "distict_of_getco_" + index);
			$(tr).find(".distict_of_getco_cls").attr("name", "distict_of_getc[" + index + "]");

			$(tr).find(".taluka_of_getco_cls").attr("id", "taluka_of_getco_" + index);
			$(tr).find(".taluka_of_getco_cls").attr("name", "taluka_of_getco[" + index + "]");

			$(tr).find(".village_of_getco_cls").attr("id", "village_of_getco_" + index);
			$(tr).find(".village_of_getco_cls").attr("name", "village_of_getco[" + index + "]");

			$(tr).find(".cap_of_getco_cls").attr("id", "cap_of_getco_" + index);
			$(tr).find(".cap_of_getco_cls").attr("name", "cap_of_getco[" + index + "]");

			$(tr).find(".vol_of_getco_cls").attr("id", "vol_of_getco_" + index);
			$(tr).find(".vol_of_getco_cls").attr("name", "vol_of_getco[" + index + "]");

			$(tr).find(".sub_mw_of_getco_cls").attr("id", "sub_mw_of_getco_" + index);
			$(tr).find(".sub_mw_of_getco_cls").attr("name", "sub_mw_of_getco[" + index + "]");

			$(tr).find(".sub_mva_of_getco_cls").attr("id", "sub_mva_of_getco_" + index);
			$(tr).find(".sub_mva_of_getco_cls").attr("name", "sub_mva_of_getco[" + index + "]");

			$(tr).find(".conn_mw_of_getco_cls").attr("id", "conn_mw_of_getco_" + index);
			$(tr).find(".conn_mw_of_getco_cls").attr("name", "conn_mw_of_getco[" + index + "]");

			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
		});

	}

	function deleteRowGetcoSub(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();

		ResetGetcoSubRowID();
	}

	//Consumer Share
	function AddConsumerShareRow() {

		var addRow = 1;
		$("#tbl_consumer_share_info > tbody  > tr").each(function(index, tr) {

			if ($("#name_of_share_holder_error_msg_" + index).html() != undefined) {
				$("#name_of_share_holder_error_msg_" + index).parent().removeClass('has-error');
				$("#name_of_share_holder_error_msg_" + index).remove();
			}
			if ($("#equity_persontage_error_msg_" + index).html() != undefined) {
				$("#equity_persontage_error_msg_" + index).parent().removeClass('has-error');
				$("#equity_persontage_error_msg_" + index).remove();
			}
		});
		$("#tbl_consumer_share_info > tbody  > tr").each(function(index, tr) {

			var name_of_share_holder = $("#name_of_share_holder_" + index).val() ? $("#name_of_share_holder_" + index).val() : 0;
			var equity_persontage = $("#equity_persontage_" + index).val() ? $("#equity_persontage_" + index).val() : 0;

			if (name_of_share_holder <= 0 || equity_persontage <= 0) {
				addRow = 0;
			}
			if (name_of_share_holder <= 0) {
				$("#name_of_share_holder_" + index).parent().addClass('has-error');
				$("#name_of_share_holder_" + index).parent().append('<div class="help-block" id="name_of_share_holder_error_msg_' + index + '">Required</div>');
			}
			if (equity_persontage <= 0) {
				$("#equity_persontage_" + index).parent().addClass('has-error');
				$("#equity_persontage_" + index).parent().append('<div class="help-block" id="equity_persontage_error_msg_' + index + '">Required</div>');
			}

		});
		tot = getTotalShare();

		if (addRow == 1 && tot) {
			var newRow = $("#tbl_consumer_share_info tr:last").clone(true).find('.rfibox').val('').end();
			newRow.find(".lastrow").html('<button class="btn btn-secondary"  style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowConsumerShare(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>');

			$("#tbl_consumer_share_info").append(newRow);
			srno = $("#tbl_consumer_share_info").find("tbody tr").length;
			$("#tbl_consumer_share_info tr:last").children('td:first').text(srno);
		}
		ResetConsumerShareRowID();
		changeConsumerSharePer();

	}

	function ResetConsumerShareRowID() {
		$("#tbl_consumer_share_info > tbody  > tr").each(function(index, tr) {

			$(tr).find(".name_of_share_holder_cls").attr("id", "name_of_share_holder_" + index);
			$(tr).find(".name_of_share_holder_cls").attr("name", "name_of_share_holder_" + index);
			$(tr).find(".equity_persontage_cls").attr("id", "equity_persontage_" + index);
			$(tr).find(".equity_persontage_cls").attr("name", "equity_persontage_" + index);

			$(tr).find(".lastrow").attr("id", "actionrange_" + index);
		});

	}

	function deleteRowConsumerShare(_obj) {
		var deleteCellId = $(_obj).parent().attr("id");
		var arrData = deleteCellId.split("_");
		var idval = parseInt(arrData[1]);
		$(_obj).parent().parent().remove();
		srno = $("#tbl_consumer_share_info").find("tbody tr").length;
		$("#tbl_consumer_share_info tr:last").children('td:first').text(srno);
		ResetConsumerShareRowID();
		changeConsumerSharePer();
	}


	/*** Land end */

	<?php if ($errorEnergy == 1) {
	?>
		AddDiscomRow();
	<?php }
	?>

	<?php if ($errorLand == 1) { ?>
		AddLandRow();
	<?php } ?>

	<?php if ($errorPooling == 1) { ?>
		AddPoolingSubRow();
	<?php } ?>

	<?php if ($errorGetco == 1) { ?>
		AddGetcoSubRow();
	<?php } ?>

	<?php if ($errorShare == 1) { ?>
		AddConsumerShareRow();
	<?php } ?>
</script>