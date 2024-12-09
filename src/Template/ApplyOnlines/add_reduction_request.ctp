<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.radio input[type="radio"], .radio-inline input[type="radio"], .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {
    margin : 0 !important;
}

.radio, .checkbox {
    margin-top : 5px !important;
    margin-bottom : 0px !important;
}
</style>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="modal-body">
            <?php echo  $this->Form->create($UpdateCapacity,['name'=>'UpdateRequestForm','id'=>'UpdateRequestForm','enctype'=>"multipart/form-data"]);
             ?>
            <div class="form-group text">
                <div class="row">
                    <lable class="col-md-4">Reason for change</lable>
                    <div class="col-md-8">
                            <?php echo $this->Form->input('UpdateCapacity.reason', array('label' => false,'type'=>'text','div'=>false,'class'=>'form-control','id'=>'reason')); ?>
                    </div>
                </div>
                <div class="row">
                    <lable class="col-md-4">Original AC Capacity (kW)</lable>
                    <div class="col-md-8">
                        <?php echo $registred_capacity; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <lable class="col-md-4">Current AC Capacity (kW)</lable>
                    <div class="col-md-8">
                        <?php echo $applicationDetails->pv_capacity; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <lable class="col-md-4">AC Capacity Required after Reduction (kW)</lable>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('UpdateCapacity.pv_capacity', array('label' => false,'type'=>'text','class'=>'form-control','onkeypress'=>"return validateDec(event)",'placeholder'=>'','id'=>'pv_capacity')); ?>
                    </div>
                </div>
                 <div class="row">
                    <lable class="col-md-4">Current DC Capacity (kW)</lable>
                    <div class="col-md-8">
                        <?php echo $applicationDetails->pv_dc_capacity; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <lable class="col-md-4">DC Capacity Required after Reduction (kW)</lable>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('UpdateCapacity.pv_capacity_dc', array('label' => false,'type'=>'text','class'=>'form-control','onkeypress'=>"return validateDec(event)",'placeholder'=>'','id'=>'pv_capacity')); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <lable class="col-md-4">Consent Letter from Consumer</lable>
                    <div class="col-md-8">
                        <?php 
                        echo $this->Form->input('UpdateCapacity.consent_letter', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'consent_letter','templates' => ['inputContainer' => '{{content}}']));
                        if(isset($UpdateCapacity->consent_letter) && !empty($UpdateCapacity->consent_letter))
                        {
                            $path = UPDATEDETAILS_PATH.$UpdateCapacity->application_id."/".$UpdateCapacity->consent_letter;
                            if ($Couchdb->documentExist($UpdateCapacity->application_id,$UpdateCapacity->consent_letter))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/consent_letter/'.encode($UpdateCapacity->id)."\">View Attached Document</a></strong>";
                            }
                        }
                        ?>
                    </div>
                </div>  
                <div class="row" style="margin-right: 2px;margin-left: -4px;">
                    <div class="col-md-12"  id="consent_letter-file-errors"></div>
                </div>  
            </div>
            <?php

            if($is_member == false && $UpdateCapacity->received_at!=1)
            {
                ?>
                <div class="row">
                    <div class="col-md-2">
                    <?php echo $this->Form->input('Submit',['type'=>'submit','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary updaterequest_btn','name'=>'save_submit']); ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
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
</script>
<script type="text/javascript">

$("#consent_letter").fileinput({
    showUpload: false,
    showPreview: false,
    dropZoneEnabled: false,
    mainClass: "input-group-lg",
    allowedFileExtensions: ["pdf"],
    elErrorContainer: '#consent_letter-file-errors',
    maxFileSize: '1024',
});

</script>