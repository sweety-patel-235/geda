<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<?php
	$this->Html->addCrumb($pageTitle);
	$ApproveFA          = false;
	
?>
<style>
.serial_class
{
	width:4%;
}
.applyonline-viewmain .portlet-body {
	padding: 7px;
}
.italic_data{
	font-size: 11px;
	font-style: italic;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container ApplyOnline-leads" style="min-height: 250px;">
	<?php echo $this->Flash->render('cutom_admin'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="row p-row alert-info">
				Fees Return form data submitted successfully. Your Fees Return No. is - <?php echo $fee_return->fees_return_no;?>. <a href="<?php echo URL_HTTP;?>download-fees-return/<?php echo encode($id);?>">Click here</a> to download.
			</div>
			
		</div>
	</div>
	
</div>


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
var date = new Date();
date.setMonth(date.getMonth() + 1, 1);
$('.datepicker').datepicker({defaultDate: date,dateFormat: 'dd-mm-yy'});
</script>
<script type="text/javascript">
$(document).ready(function(){
	
	
	$(".subdivision_approval").click(function() {
		
		
		$("#form-main").submit();
	});
	$(".approved-dd").change(function(){
		if ($(this).val() == 1) {
			$(".reject-reason").addClass("hide");
			$(".subdivision_approval").html("Approved By Subdivision");
		} else if ($(this).val() != 1) {
			$(".reject-reason").removeClass("hide");
			$(".subdivision_approval").html("Submit");
		}
	});
});
function click_submit()
{
	$("#submit_fesibility").attr('disabled','disabled');
	$("#form-main").submit();
}
$(".approval_btn").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find(".application_status").val() == 2 && reason.length < 1) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("");
		$("#"+fromobj).find("#messageBox").html("Reason is required field.");
		return false;
	} else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.href='/apply-online-list';
			  }
		});
	}
	return false;
});

function validateInteger(key) {
	var keycode = (key.which) ? key.which : key.keyCode;
	if (!(keycode == 8) && (keycode < 48 || keycode > 57)) {
		return false;
	} else {
		return true;
	}
}
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

</script>