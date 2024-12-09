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
            <?php echo  $this->Form->create($UpdateDetails,['name'=>'UpdateRequestForm','id'=>'UpdateRequestForm','enctype'=>"multipart/form-data"]);
             ?>
            <div class="form-group text">
                <div class="row">
                    <lable class="col-md-4">Reason for change</lable>
                    <div class="col-md-8">
                            <?php echo $this->Form->input('UpdateDetails.reason', array('label' => false,'type'=>'text','div'=>false,'class'=>'form-control','id'=>'reason')); ?>
                    </div>
                </div>
                <?php
                if(!empty($originalData))
                {
                    ?>
                    <div class="row">
                        <lable class="col-md-12">Old Data </lable>
                    </div>
                    <?php
                    foreach($originalData as $valOrg)
                    {
                        
                        ?>
                        <div class="row" >
                            <div class="col-md-4">&nbsp;</div>
                            <div class="col-md-3" ><?php echo $valOrg['text'];?>:
                                
                            </div>
                            <div class="col-md-5"><?php echo $valOrg['val'];?>
                            </div>
                            
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <lable class="col-md-12">Current Data </lable>
                    </div>
                    <?php
                    foreach($originalNewData as $valOrg)
                    {
                        
                        ?>
                        <div class="row" >
                            <div class="col-md-4">&nbsp;</div>
                            <div class="col-md-3" ><?php echo $valOrg['text'];?>:
                                
                            </div>
                            <div class="col-md-5"><?php echo $valOrg['val'];?>
                            </div>
                            
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="row">
                    <lable class="col-md-12">Field to be updated </lable>
                </div>
                <div class="row" >
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-1" >
                        <?php echo $this->Form->input('UpdateDetails.is_name_update', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'','placeholder'=>'','id'=>'is_name_update')); ?>
                    </div>
                    <div class="col-md-7">Name of the Consumer 
                    </div>
                    
                </div>
                <div class="row" >
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-1" >
                    <?php echo $this->Form->input('UpdateDetails.is_division_details', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'','placeholder'=>'','id'=>'is_division_details')); ?>
                    </div> 
                    <div class="col-md-7">Circle, Division and Sub-Division</div>
                </div>
                <div class="row" >
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-1" >
                        <?php echo $this->Form->input('UpdateDetails.is_contract_load', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'','placeholder'=>'','id'=>'is_contract_load')); ?>
                    </div>
                    <div class="col-md-7">Contract Load 
                        <?php
                        $error_class_for_name_prefix    = '';
                        $error_text                     = '';

                        if(isset($UpdateDetailsErrors['select_updated']) && isset($UpdateDetailsErrors['select_updated']['_empty']) && !empty($UpdateDetailsErrors['select_updated']['_empty']))
                        { 
                            $error_class_for_name_prefix    = 'has-error'; 
                            $error_text                     = $UpdateDetailsErrors['select_updated']['_empty'];
                        }
                        ?>
                        <div class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('UpdateDetails.select_updated',["type" => "hidden",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>0]);
                        if(!empty($error_text))
                        {
                        echo '<div class="help-block">'.$error_text.'</div>'; 
                        } ?>
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <lable class="col-md-4">Aadhaar Number</lable>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('UpdateDetails.aadhar_no', array('label' => false,'type'=>'text','class'=>'form-control','placeholder'=>'','id'=>'aadhar_no')); ?>
                    </div>
                </div>   
                <div class="row">
                    <lable class="col-md-4">Aadhaar Card</lable>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('UpdateDetails.aadhar_card', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'','id'=>'aadhar_card')); ?>
                        <?php 
                        if(isset($UpdateDetails->aadhar_card) && !empty($UpdateDetails->aadhar_card))
                        {
                            $path = UPDATEDETAILS_PATH.$UpdateDetails->application_id."/".$UpdateDetails->aadhar_card;
                            if ($Couchdb->documentExist($UpdateDetails->application_id,$UpdateDetails->aadhar_card))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/aadhar_card_update/'.encode($UpdateDetails->id)."\">View Attached Document</a></strong>";
                            }
                        }
                        
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-right: 2px;margin-left: -4px;">
                    <div class="col-md-12"  id="aadhar_card-file-errors"></div>
                </div>  
                <div class="row">
                    <lable class="col-md-4">Consumer Photo</lable>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('UpdateDetails.profile_image', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'profile_image')); ?>
                        <?php
                        if(isset($UpdateDetails->profile_image) && !empty($UpdateDetails->profile_image)) {
                            $path = UPDATEDETAILS_PATH.$UpdateDetails->application_id."/".$UpdateDetails->profile_image;
                            if ($Couchdb->documentExist($UpdateDetails->application_id,$UpdateDetails->profile_image)) {
                                $profile_image_ext = pathinfo($path,PATHINFO_EXTENSION);
                                ?>
                                <img style="width:125px;" src="<?php echo URL_HTTP.'app-docs/profile_image/'.encode($UpdateDetails->id); ?>"/>
                                <?php
                            }
                            
                        }
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-right: 2px;margin-left: -4px;">
                    <div class="col-md-12"  id="profile_image-file-errors"></div>
                </div>
                <div class="row">
                    <lable class="col-md-4">New Electricity Bill </lable>
                    <div class="col-md-8">
                        <?php echo $this->Form->input('UpdateDetails.electricity_bill', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'electricity_bill')); ?>
                        <?php
                        if(isset($UpdateDetails->electricity_bill) && !empty($UpdateDetails->electricity_bill)) {
                            $path = UPDATEDETAILS_PATH.$UpdateDetails->application_id."/".$UpdateDetails->electricity_bill;
                           if ($Couchdb->documentExist($UpdateDetails->application_id,$UpdateDetails->electricity_bill)) {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/electricity_bill/'.encode($UpdateDetails->id)."\">View Attached Document</a></strong>";
                            }
                            
                        }
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-right: 2px;margin-left: -4px;">
                    <div class="col-md-12"  id="ele_bill-file-errors"></div>
                </div>     
                
            </div>
            <?php
            if($is_member == false)
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
$("#profile_image").fileinput({
    showUpload: false,
    showPreview: false,
    dropZoneEnabled: false,
    mainClass: "input-group-lg",
    allowedFileExtensions: ["jpg", "jpeg"],
    elErrorContainer: '#profile_image-file-errors',
    maxFileSize: '200',
});
$("#electricity_bill").fileinput({
    showUpload: false,
    showPreview: false,
    dropZoneEnabled: false,
    mainClass: "input-group-lg",
    allowedFileExtensions: ["pdf"],
    elErrorContainer: '#ele_bill-file-errors',
    maxFileSize: '1024',
});
$("#aadhar_card").fileinput({
    showUpload: false,
    showPreview: false,
    dropZoneEnabled: false,
    mainClass: "input-group-lg",
    allowedFileExtensions: ["pdf"],
    elErrorContainer: '#aadhar_card-file-errors',
    maxFileSize: '200',
});
</script>