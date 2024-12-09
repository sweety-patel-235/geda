<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $this->Html->addCrumb($pageTitle); ?>
<style type="text/css">
    .subsidy-claim .tab-pane {
        margin-top: 10px;
    }
</style>
<div class="container applay-online-from">
    <div class="row">
        <h2 class="col-md-9 mb-sm mt-sm"><strong>Subsidy</strong> Claim Section
          <?php
          if(!empty($ApplyOnlines->pcr_code))
          {
            ?> &nbsp;&nbsp;-&nbsp;&nbsp;
            <span style="font-size:25px;color:#ffcc29;">
              <strong>
                <?php
                  echo 'PCR: '.$ApplyOnlines->pcr_code;
                ?>
              </strong>
            </span>
            <?php
          }
          ?>
        </h2>
        <div class="col-md-3 pull-right">
            <span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg pull-right">
            <?php echo $this->Html->link('My Application',['controller'=>'ApplyOnlines','action' => 'applyonline_list']); ?>
            </span>
        </div>
    </div>
    <?php echo $this->Flash->render('cutom_admin'); ?>
    <?php
    $tabExtraclass      = '';
    if(empty($SubsidyExist))
    {
        //$tabExtraclass  = 'desible';

    }
    ?>
    <div id="subsidy-documents-file-errors"></div>
    <div class="tabs tabs-bottom tabs-simple nk_tabs">
        <ul class="nav nav-tabs">
            <li class="<?php if($tab_id == 1) { echo 'active'; } ?>">
                <a href="#ProjectDetails" data-toggle="tab">Project Detail</a>
            </li>
            <li class="<?php if($tab_id == 2){ echo 'active'; } ?> <?php echo $tabExtraclass;?>">
                <a href="<?php if(empty($tabExtraclass)) { echo '#IdProof'; } else { echo 'javascript:;'; } ?>" data-toggle="tab">ID Proof</a>
            </li>
            <li class="<?php if($tab_id == 3){ echo 'active'; } ?> <?php echo $tabExtraclass;?>">
                <a href="<?php if(empty($tabExtraclass)) { echo '#WorkOrder'; } else { echo 'javascript:;'; } ?>" data-toggle="tab">Work Order</a>
            </li>
            <li class="<?php if($tab_id == 4){ echo 'active'; } ?> <?php echo $tabExtraclass;?>">
                <a href="<?php if(empty($tabExtraclass)) { echo '#CEIDocs'; } else { echo 'javascript:;'; } ?>" data-toggle="tab">CEI Docs</a>
            </li>
            <li class="<?php if($tab_id == 5){ echo 'active'; } ?> <?php echo $tabExtraclass;?>">
                <a href="<?php if(empty($tabExtraclass)) { echo '#Execution'; } else { echo 'javascript:;'; } ?>" data-toggle="tab">Execution Details</a>
            </li>
            <li class="<?php if($tab_id == 6){ echo 'active'; } ?> <?php echo $tabExtraclass;?>">
                <a href="<?php if(empty($tabExtraclass)) { echo '#Technical'; } else { echo 'javascript:;'; } ?>" data-toggle="tab">Technical Details</a>
            </li>
            <?php
            if($ApplyOnlines->social_consumer==1)
            {
              ?>
              <li class="<?php if($tab_id == 7){ echo 'active'; } ?> <?php echo $tabExtraclass;?>">
                <a href="<?php if(empty($tabExtraclass)) { echo '#SocialSector'; } else { echo 'javascript:;'; } ?>" data-toggle="tab">For Social Sector</a>
              </li>
              <?php
            }
            ?> 
        </ul>
        <div class="subsidy-claim tab-content">
            <div class="tab-pane <?php if($tab_id == 1) { echo 'active'; } ?>" id="ProjectDetails">
                <?php echo $this->element('subsidy/project_detail'); ?>
            </div>
            <div class="tab-pane <?php if($tab_id == 2) { echo 'active'; } ?>" id="IdProof">
                <?php echo $this->element('subsidy/id_proof'); ?>
            </div>
            <div class="tab-pane <?php if($tab_id == 3) { echo 'active'; } ?>" id="WorkOrder">
                <?php echo $this->element('subsidy/work_order'); ?>
            </div>
            <div class="tab-pane <?php if($tab_id == 4) { echo 'active'; } ?>" id="CEIDocs">
                <?php echo $this->element('subsidy/cei_docs'); ?>
            </div>
            <div class="tab-pane <?php if($tab_id == 5) { echo 'active'; } ?>" id="Execution">
                <?php echo $this->element('subsidy/execution'); ?>
            </div>
            <div class="tab-pane <?php if($tab_id == 6) { echo 'active'; } ?>" id="Technical">
                <?php echo $this->element('subsidy/technical_details'); ?>
            </div>
            <?php
            if($ApplyOnlines->social_consumer==1)
            {
              ?>
              <div class="tab-pane <?php if($tab_id == 7) { echo 'active'; } ?>" id="SocialSector">
                  <?php echo $this->element('subsidy/for_social_sector'); ?>
              </div>
              <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
$selected_sub     = '';
if(isset($Subsidy->subcategory) && !empty($Subsidy->subcategory) && empty($postedData))
{
  $selected_sub = $Subsidy->subcategory;
}
elseif(isset($postedData['tab_id']) && $postedData['tab_id']=='1')
{
  $selected_sub = $postedData['Subsidy']['subcategory'];
}
?>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
var result = $.parseJSON('<?php echo ($arrSubData);?>');
function changeCategory()
{
  $("#subcategory").html("");
  var sel_category      = $("#category").val();
  var sel_subcategory   = '<?php echo $selected_sub;?>';
  $("#subcategory").append($("<option />").val('').text('Select Subcategory'));
  if(sel_category>0)
  {
    $.each(result[sel_category], function(index, title) {
      $("#subcategory").append($("<option />").val(index).text(title));
      if(sel_subcategory>0 && index==sel_subcategory)
      {
        $('#subcategory option[value="'+sel_subcategory+'"]').attr("selected", "true");
      }
    });
  }
}
function changeSubcategory()
{
  var sel_subcategory = $("#subcategory").val();
  var outSub          = $('#subcategory option[value="'+sel_subcategory+'"]').text();
  if(outSub.toLowerCase()=='ngo')
  {
    $("#ngo_id_field").show();
    $("#ngo_pan_field").show();
  }
  else
  {
    $("#ngo_id").val('');
    $("#ngo_pan").val('');
    $("#ngo_id_field").hide();
    $("#ngo_pan_field").hide();
  }
}
$(document).ready(function(){
    $(".subsidy-documents").fileinput({
        showUpload: false,
        showPreview: true,
        dropZoneEnabled: false,
        showCaption: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#subsidy-documents-file-errors',
        maxFileSize: '1024',
    });
    $(".pdf-excel").fileinput({
        showUpload: false,
        showPreview: true,
        dropZoneEnabled: false,
        showCaption: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#subsidy-documents-file-errors',
        maxFileSize: '1024',
    });
    $("#social_excel").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        showCaption: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["xlsx","xls"],
        elErrorContainer: '#subsidy-documents-file-errors',
        maxFileSize: '1024',
    });
    $("#pv_plant_site_photo").fileinput({
        showUpload: false,
        showPreview: true,
        dropZoneEnabled: false,
        showCaption: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg","jpeg"],
        elErrorContainer: '#subsidy-documents-file-errors',
        maxFileSize: '2048',
    });
    $("#aadhar_card").fileinput({
        showUpload: false,
        showPreview: true,
        dropZoneEnabled: false,
        showCaption: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#subsidy-documents-file-errors',
        maxFileSize: '200',
    });
    $("#profile_image").fileinput({
        showUpload: false,
        showPreview: true,
        dropZoneEnabled: false,
        showCaption: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg"],
        elErrorContainer: '#subsidy-documents-file-errors',
        maxFileSize: '200',
    });
    $(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
    changeCategory();
    changeSubcategory();
});
$('.modules').on('change', function() {
  var id_sel = this.id;
  if(this.value==4){
    $('#'+id_sel+'_o').show();
  }
  else{
    $('#'+id_sel+'_o').hide();
    //$('#'+id_sel+'_o').children().val('');
  }
});

$('.make_inverters_new').on('change', function() {
  var id_sel = this.id;
  if(this.value==27){
    $('#'+id_sel+'_o').show();
  }
  else{
    $('#'+id_sel+'_o').hide();
    $('#'+id_sel+'_o').val('');
  }
});

$('.inverters').on('change', function() {
  var id_sel = this.id;
  if(this.value==6){
    $('#'+id_sel+'_o').show();
  }
  else{
    $('#'+id_sel+'_o').hide();
    $('#'+id_sel+'_o').val('');
  }
});
$(document).ready(function(){
  $(".c2").blur(function() {
      var total = 0
      $( ".c1" ).each(function( index ) {
          var pv  = $("#m_capacity_"+(index+1)).val();
          var mod = $("#m_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total/1000).toFixed(3):"");
      $(".cumulative-pv-modules").html(total);
      
  });
  $(".c1").blur(function() {
      var total = 0
      $( ".c2" ).each(function( index ) {
          var pv  = $("#m_capacity_"+(index+1)).val();
          var mod = $("#m_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total/1000).toFixed(3):"");
      $(".cumulative-pv-modules").html(total);
      
  });
  $(".c3").blur(function() {
      var total = 0
      $( ".c4" ).each(function( index ) {
          var pv  = $("#i_capacity_"+(index+1)).val();
          var mod = $("#i_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total).toFixed(3):"");
      $(".cumulative-pv-modules-2").html(total);
  });
  $(".c4").blur(function() {
      var total = 0
      $( ".c3" ).each(function( index ) {
          var pv  = $("#i_capacity_"+(index+1)).val();
          var mod = $("#i_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total).toFixed(3):"");
      $(".cumulative-pv-modules-2").html(total);
  });
});
function validateDec(key) {
  var keycode = (key.which) ? key.which : key.keyCode;
  if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
    return false;
  }
  else {
    var parts = key.srcElement.value.split('.');
    if (parts.length > 1 && keycode == 46)
        return false;
    return true;
  }
}
function del_geda_inspection(app_id){
    var application_id = app_id;
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
                url: "/Subsidy/RemoveGedaLetter",
                data: {'application_id':application_id},
                success: function(response) {
                    var result = $.parseJSON(response);
                    console.log(result.success);
                    if (result.success == 1) {
                           swal("Deleted!", "Your GEDA Inspection file has been deleted.", "success");
                       window.location.href = '/subsidy/'+application_id;    
                    } 
                }
            });
    
  } else {
    swal("Cancelled", "Your GEDA Inspection file is safe :)", "error");
  }
});
}
</script>