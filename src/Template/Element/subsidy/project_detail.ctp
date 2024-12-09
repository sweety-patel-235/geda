<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_1', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="1" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Application Details</u></h4></div>
        <div class="col-md-12">
            <fieldset>
                <legend>Sanction Detail</legend>
                <?php
                if($SpinSubmit==false)
                {
                    ?>
                    <div class="row col-md-12">
                        <div class="col-md-12"><?php echo '<span style="font-size:16px;color:#ffcc29;"><strong>Spin submission quota over.</strong></span><br/>';?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Sanction Id</div>
                    <div class="col-md-9"><?php echo (!empty($ApplyOnlines->approval_id) && array_key_exists($ApplyOnlines->approval_id, $SubsidyTable->SPIN_APPROVAL)) ? $SubsidyTable->SPIN_APPROVAL[$ApplyOnlines->approval_id]['no'] : SPIN_APPROVAL_NO ;?></div>
                </div>
            </fieldset>
            <br />
            <fieldset>
                <legend>Project Detail</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Mode of Project</div>
                    <div class="col-md-9"><?php echo $MODE_OF_PROJECT;?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Name of the Installer</div>
                    <div class="col-md-9"><?php echo $Installer->installer_name;?></div>
                </div>
            </fieldset>
            <br />
            <fieldset>
                <legend>Registeration Details</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Photo of Consumer</div>
                    <div class="col-md-9"><?php
                        $IMAGE_EXT                  = array("png","jpg","gif","jpeg","bmp");
                        $DOCUMENT_PATH              = APPLYONLINE_PATH.decode($application_id).'/';
                        if(!empty($Applyonlinprofile) && !$displayUpload) {
                            if ($Couchdb->documentExist(decode($application_id),$Applyonlinprofile['file_name'])) {
                                $profile_image_ext  = pathinfo($DOCUMENT_PATH.$Applyonlinprofile['file_name'],PATHINFO_EXTENSION);
                                if (in_array($profile_image_ext,$IMAGE_EXT)) {
                        ?> 
                            <img style="width:125px;" src="<?php echo URL_HTTP.'app-docs/profile/'.$application_id; ?>"/>
                        <?php
                                } else {
                                    echo "<strong><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/profile/'.$application_id."\">View Profile Image</a></strong>";
                                }
                            }
                        }
                        else
                        {
                            echo $this->Form->input('Subsidy.profile_image', array('label' => false,'div' => false,'type'=>'file','id'=>'profile_image','templates' => ['inputContainer' => '{{content}}'])); 
                        }
                        ?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Name of Consumer</div>
                    <div class="col-md-9"><?php echo $ApplyOnlines->name_of_consumer_applicant;?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Registration No.</div>
                    <div class="col-md-9"><?php echo $ApplyOnlines->geda_application_no;?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Registration Letter Date</div>
                    <div class="col-md-9"><?php echo $GEDA_REGISTRATION_DATE;?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Registered Capacity</div>
                    <div class="col-md-9"><?php echo $ApplyOnlines->pv_capacity;?> kW</div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Category of the organization / beneficiary</div>
                    <div class="col-md-9"><?php echo $APPLICATION_CATEGORY_TITLE;?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Consumer Mobile</div>
                    <div class="col-md-5" style="margin-left: 14px;width:39%;"><?php echo $this->Form->input('Subsidy.consumer_mobile', array('label' => false,'div' => false,'type'=>'text','id'=>'consumer_mobile','class'=>'form-control','placeholder'=>'Consumer Mobile')); ?></div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Pincode</div>
                    <div class="col-md-5" style="margin-left: 14px;width:39%;"><?php echo $this->Form->input('Subsidy.pincode', array('label' => false,'div' => false,'type'=>'number','id'=>'pincode','class'=>'form-control','placeholder'=>'Pincode')); ?></div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Category</div>
                    <?php
                        $error_class_category = '';
                        if(isset($SubsidyErrors['category']) && isset($SubsidyErrors['category']['_empty']) && !empty($SubsidyErrors['category']['_empty'])){ $error_class_category = 'has-error'; }
                    ?>
                    <div class="col-md-5 <?php echo $error_class_category;?>">
                        <?php 
                            echo $this->Form->select('Subsidy.category',$arrCategory,array('label' => false,'class'=>'form-control','id'=>'category','empty'=>'Select Category','onchange'=>"javascript:changeCategory();"));
                            if(!empty($error_class_category))
                            {
                                ?>
                                <div class="help-block"><?php echo $SubsidyErrors['category']['_empty']; ?></div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <?php
                    $error_class_subcategory = '';
                    if(isset($SubsidyErrors['subcategory']) && isset($SubsidyErrors['subcategory']['_empty']) && !empty($SubsidyErrors['subcategory']['_empty'])){ $error_class_subcategory = 'has-error'; }
                ?>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Sub Category of the organization / beneficiary</div>
                    <div class="col-md-5 <?php echo $error_class_subcategory;?>">
                        <?php 
                            echo $this->Form->select('Subsidy.subcategory',array(''=>'Select Subcategory'),array('label' => false,'class'=>'form-control','id'=>'subcategory','placeholder'=>'Select Subcategory','onchange'=>"javascript:changeSubcategory();"));
                            if(!empty($error_class_subcategory))
                            {
                                ?>
                                <div class="help-block"><?php echo $SubsidyErrors['subcategory']['_empty']; ?></div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row col-md-12" id="ngo_id_field" style="display: none;">
                    <div class="label-heading col-md-3">NGO Id</div>
                    <div class="col-md-5" style="margin-left: 17px;margin-bottom: -25px;"><?php echo $this->Form->input('Subsidy.ngo_id', array('label' => false,'type'=>'text','id'=>'ngo_id','class'=>'form-control','placeholder'=>'NGO Id')); ?></div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row col-md-12" id="ngo_pan_field" style="display: none;">
                    <div class="label-heading col-md-3">NGO Pan</div>
                    <div class="col-md-5" style="margin-left: 17px;margin-bottom: -25px;"><?php echo $this->Form->input('Subsidy.ngo_pan', array('label' => false,'type'=>'text','id'=>'ngo_pan','class'=>'form-control','placeholder'=>'NGO Pan')); ?></div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Address</div>
                    <div class="col-md-9"><?php echo $CUSTOMER_ADDRESS;?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Is the Applicant a Common Meter Connection?</div>
                    <div class="col-md-9"><?php 
                    if($ApplyOnlines->common_meter!=1 && $is_member == false && $CanEdit == true && $SpinSubmit==true)
                    {
                        echo $this->Form->input('Subsidy.common_meter', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'form-control check-box-address','placeholder'=>'','id'=>'common_meter','style'=>"margin-left: 10px;"));
                    }
                    else
                    {
                        echo ($ApplyOnlines->common_meter==1) ? 'Yes':'No';
                    }
                    ?></div>
                </div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">District</div>
                    <?php
                        $error_class_district = '';
                        if(isset($SubsidyErrors['district']) && isset($SubsidyErrors['district']['_empty']) && !empty($SubsidyErrors['district']['_empty'])){ $error_class_district = 'has-error'; }
                    ?>
                    <div class="col-md-5 <?php echo $error_class_district;?>">
                        <?php 
                            echo $this->Form->select('Subsidy.district',$arrDistrict,array('label' => false,'class'=>'form-control','id'=>'district','empty'=>'Select District','placeholder'=>'Select District'));
                            if(!empty($error_class_district))
                            {
                                ?>
                                <div class="help-block"><?php echo $SubsidyErrors['district']['_empty']; ?></div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
            </fieldset>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <?php if(($is_member == false || $authority_account==1) && (($CanEdit == true && $SpinSubmit==true) || $displayUpload)) { ?>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_1','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_1')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Save & Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_1','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_1')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>      
<?php echo $this->Form->end(); ?>
<script>
    <?php
    if($displayUpload)
    {
        ?>
        $('#consumer_mobile').attr('disabled','disabled');
        $('#pincode').attr('disabled','disabled');
        $('#category').attr('disabled','disabled');
        $('#subcategory').attr('disabled','disabled');
        $('#common_meter').attr('disabled','disabled');
        $('#district').attr('disabled','disabled');
        <?php
    }
    ?>
    $('.form_submit').submit(function(){
        $('#consumer_mobile').removeAttr('disabled');
        $('#pincode').removeAttr('disabled');
        $('#category').removeAttr('disabled');
        $('#subcategory').removeAttr('disabled');
        $('#common_meter').removeAttr('disabled');
        $('#district').removeAttr('disabled');
    });
</script>