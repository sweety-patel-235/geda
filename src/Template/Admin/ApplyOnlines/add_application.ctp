<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.applyonline-viewmain .form-group {
    padding-right: 10px;
    padding-left: 10px;
}
.m-radio-inline label:after {
    content: "";
}
.m-checkbox-inline label:after {
    content: "";
}
.form-horizontal .radio, .form-horizontal .checkbox, .form-horizontal .radio-inline, .form-horizontal .checkbox-inline
{
    padding-top:0px;
}
</style>
<div class="grid_12">
    <div class="box">
        <div class="content">
            <div class="portlet box blue-madison applyonline-viewmain">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i> Add Application
                    </div>
                    <div class="tools">
                        <a href="" class="collapse" data-original-title="" title=""></a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <?php  echo $this->Form->create($ApplyOnlines,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'contactForm', 'url' => '/admin/ApplyOnlines/add_application','autocomplete'=>'off')); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Project Details</legend>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>Project Name <span class="mendatory_field">*</span></label>
                                            <?php 
                                            echo $this->Form->input('proj_name',array('label' => false,'class'=>'form-control','id'=>'proj_name')); ?>
                                            <?php 
                                            echo $this->Form->input('landmark',array('label' => false,'class'=>'form-control','id'=>'landmark','placeholder'=>'Landmark','type'=>'hidden')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Latitude <span class="mendatory_field">*</span></label>
                                            <?php 
                                            echo $this->Form->input('latitude',array('label' => false,'class'=>'form-control','type'=>'text','id'=>'latitude','placeholder'=>'Latitude')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Longitude <span class="mendatory_field">*</span></label>
                                            <?php 
                                            echo $this->Form->input('longitude',array('label' => false,'class'=>'form-control','type'=>'text','id'=>'longitude','placeholder'=>'Longitude')); ?>
                                        </div>
                                    </div>
                                    <?php
                                    $error_class_project_type = '';
                                    if(isset($ApplyOnlineErrors['project_type']) && isset($ApplyOnlineErrors['project_type']['_empty']) && !empty($ApplyOnlineErrors['project_type']['_empty'])){ $error_class_project_type = 'has-error'; }
                                    ?>
                                    <div class="row form-group">
                                        <div class="col-md-4 <?php echo $error_class_project_type;?>">
                                            <label>Category <span class="mendatory_field">*</span></label>
                                            <?php 
                                            echo $this->Form->select('project_type',$projectTypeArr,array('label' => false,'class'=>'form-control','id'=>'project_type')); ?>
                                            <?php
                                            if(!empty($error_class_project_type))
                                            {
                                                ?>
                                                <div class="help-block"><?php echo $ApplyOnlineErrors['project_type']['_empty']; ?></div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Area Type <span class="mendatory_field">*</span></label>
                                            <?php
                                            echo $this->Form->select('area_type',$areaTypeArr,array('label' => false,'class'=>'form-control','id'=>'area_type')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Rooftop Area <span class="mendatory_field">*</span></label>
                                             <?php 
                                            echo $this->Form->input('area',array('label' => false,'class'=>'form-control','id'=>'area','type'=>'text','onkeyup'=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>Average Monthly Bill <span class="mendatory_field">*</span></label>
                                            <?php 
                                            echo $this->Form->input('avg_monthly_bill',array('label' => false,'class'=>'form-control','id'=>'avg_monthly_bill','type'=>'text','onkeyup'=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Average Monthly Unit Consumed <span class="mendatory_field">*</span></label>
                                            <?php 
                                                echo $this->Form->input('energy_con',array('label' => false,'class'=>'form-control','id'=>'energy_con','type'=>'text','onkeyup'=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}")); ?>
                                        </div>
                                        <div class="col-md-4">
                                        <label>Backup Type</label>
                                            <?php echo $this->Form->select('backup_type',$backupTypeArr,array('label' => false,'class'=>'form-control','empty'=>'None','onChange'=>'displayUsageHours(this.value)')); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <div class="m-checkbox-inline">
                                                <label class="m-checkbox lbl_comunication_address">
                                                <input type="checkbox" value="1" class="check-box-address" name="project_common_meter"> Is the Applicant a Common Meter Connection?
                                                <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                        $hide_class     = '';
                                        if(SOCIAL_SECTOR=='0')
                                        {
                                            $hide_class = 'hide';
                                        }
                                        ?>
                                        <div class="col-md-4 <?php echo $hide_class;?>">
                                            &nbsp;
                                        </div> 
                                        <div class="row form-group" id="usage_hours_div">
                                            <div class="col-md-4" >
                                                <label>Hours of Usage</label>
                                                <input type="text" maxlength="10" class="form-control" name="usage_hours" id="usage_hours"> 
                                            </div>
                                            <div class="col-md-8">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                </fieldset>
                            </div>
                            <div class="row"><div class="col-md-12">&nbsp;</div></div>
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Discom and Installer Detail</legend>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>Installer <span class="mendatory_field">*</span></label>
                                            <?php
                                            asort($installersList);?>
                                            <?php echo $this->Form->select('ApplyOnlines.installer_id',$installersList, array('label' => false,'empty'=>'-select installer-','class'=>'form-control','placeholder'=>'Discom Name','id'=>'installer_id')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Discom <span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->select('ApplyOnlines.discom',$discom_arr, array('label' => false,'empty'=>'-Select DisCom-','class'=>'form-control','placeholder'=>'DisCom','id'=>'discom','onchange' => 'ShowHideDiv()'));  ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Consumer No.<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.consumer_no', array('label' => false,'class'=>'form-control','id'=>'consumer_no','placeholder'=>'Consumer NO')); ?>
                                        </div>    
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>Division/Zone<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->select('ApplyOnlines.discom_name',$discom_list, array('label' => false,'empty'=>'-Select Division-','class'=>'form-control','id'=>'division','placeholder'=>'Division','onchange'=>'javascript:click_division();')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Sub Division<span class="mendatory_field">*</span></label>
                                            <div id="subdivision"></div>
                                        </div>
                                        <div class="col-md-4" style="display: none" id="tno">
                                            <label>T-NO<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.tno', array('label' => false,'class'=>'form-control','id'=>'t_no','placeholder'=>'T-NO')); ?>
                                        </div>    
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>Sanctioned /Contract Load (in kW)<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.sanction_load_contract_demand', array('label' => false,'class'=>'form-control','placeholder'=>'Sanction Load/Contract Demand','id'=>'sanction_load')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="m-form__group form-group">
                                                <label>Phase of proposed Solar Inverter<span class="mendatory_field">*</span></label>
                                                <div class="m-radio-inline">
                                                    <label class="m-radio">
                                                    <input type="radio" name="ApplyOnlines[transmission_line]" value="1" checked="checked"> Single Phase
                                                    <span></span>
                                                    </label>
                                                    <label class="m-radio">
                                                    <input type="radio" name="ApplyOnlines[transmission_line]" value="3"> 3 Phase
                                                    <span></span>
                                                    </label>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="m-form__group form-group">
                                                <label>Who will provide the Net-Meter?<span class="mendatory_field">*</span></label>
                                                <div class="m-radio-inline">
                                                    <label class="m-radio">
                                                    <input type="radio" name="ApplyOnlines[net_meter]" value="1" checked="checked"> DisCom
                                                    <span></span>
                                                    </label>
                                                    <label class="m-radio">
                                                    <input type="radio" name="ApplyOnlines[net_meter]" value="2"> Installer/ EA
                                                    <span></span>
                                                    </label>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>PV Capacity (DC) to be installed (in kW)<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.pv_capacity', array('label' => false,'class'=>'form-control','placeholder'=>'PV Capacity')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Application No.<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.application_no', array('label' => false,'class'=>'form-control','placeholder'=>'Application No')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>GEDA Registration No.<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.geda_application_no', array('label' => false,'class'=>'form-control','placeholder'=>'GEDA Registration No')); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label>Application Id<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.id', array('label' => false,'class'=>'form-control','placeholder'=>'Id','type'=>"text")); ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row"><div class="col-md-12">&nbsp;</div></div>
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Consumer Detail</legend>
                                    <div class="row col-md-12">
                                        <div class="col-md-3">
                                            <label>Consumer Email</label>
                                            <?php echo $this->Form->input('ApplyOnlines.consumer_email', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Email')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Consumer Mobile<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.consumer_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Installer Email<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.installer_email', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Email')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Installer Mobile<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.installer_mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Mobile',"onkeyup"=>"if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}","maxlength"=>"10")); ?>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                    <div class="row col-md-12">
                                        <div class="col-md-3">
                                            <label>Name prefix<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->select('ApplyOnlines.customer_name_prefixed',$customer_name_prifix, array('label' => false,'class'=>'form-control','empty'=>'-Select Prifix-')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>First Name<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.name_of_consumer_applicant', array('label' => false,'class'=>'form-control','placeholder'=>'First Name','id'=>'name_of_consumer_applicant')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Middle Name</label>
                                            <?php echo $this->Form->input('ApplyOnlines.last_name', array('label' => false,'class'=>'form-control','placeholder'=>'Middle Name','id'=>'middle_name')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Last Name</label>
                                            <?php echo $this->Form->input('ApplyOnlines.third_name', array('label' => false,'class'=>'form-control','placeholder'=>'Last Name','id'=>'third_name')); ?>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                    <div class="row col-md-12">
                                        <div class="col-md-3">
                                            <label>Landline No</label>
                                            <?php echo $this->Form->input('ApplyOnlines.landline_no', array('label' => false,'class'=>'form-control','placeholder'=>'Landline No')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Street/House No.<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.address1', array('label' => false,'class'=>'form-control','id'=>'address1','placeholder'=>'Address 1','id'=>'add1')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Taluka<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.address2', array('label' => false,'class'=>'form-control','placeholder'=>'Taluka','id'=>'add2')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>District</label>
                                            <?php echo $this->Form->input('ApplyOnlines.district', array('label' => false,'class'=>'form-control','placeholder'=>'District','id'=>'district')); ?>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                    <div class="row col-md-12">
                                        <div class="col-md-3">
                                            <label>City/Village<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.city', array('label' => false,'class'=>'form-control','placeholder'=>'City','id'=>'city')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>State<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.state', array('label' => false,'class'=>'form-control','placeholder'=>'State','id'=>'state')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Pincode<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.pincode', array('type'=>'text','label' => false,'class'=>'form-control','placeholder'=>'Pincode','id'=>'pincode')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            &nbsp;
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                    <div class="row col-md-12">
                                        <div class="col-md-6">
                                            <div class="m-checkbox-inline lbl_comunication_address">
                                                <label class="m-checkbox lbl_comunication_address">
                                                <input type="checkbox" value="1" class="check-box-address chk_comunication_address" name="ApplyOnlines[comunication_address_as_above]"> Communication address as per above
                                                <span></span>
                                                </label>
                                            </div>
                                            <br/>
                                            <span class="comunication-address">
                                            <label>Communication Address<span class="mendatory_field">*</span></label>
                                             <?php echo $this->Form->textarea('ApplyOnlines.comunication_address', array('label' => false,'class'=>'form-control','placeholder'=>'Communication Address')); ?>
                                             </span>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 35px;">
                                            <label >
                                            Passport Size Photo of Consumer<span class="mendatory_field">*</span></label>
                                            <br/>
                                            <div class="file-loading" >
                                                <?php echo $this->Form->input('ApplyOnlines.profile_image', array('label' => false,'div' => false,'type'=>'file','id'=>'profile_image','templates' => ['inputContainer' => '{{content}}'])); ?>
                                            </div>
                                            <div id="profile_image-file-errors"></div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row"><div class="col-md-12">&nbsp;</div></div>
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Additional Details</legend>
                                    <div class="row col-md-12">
                                        <div class="col-md-4">
                                            <div class="m-form__group form-group">
                                                <label>Whether the Premises is owned or Rented<span class="mendatory_field">*</span></label>
                                                <div class="m-radio-inline">
                                                    <label class="m-radio">
                                                    <input type="radio" name="ApplyOnlines[owned_rented]" value="0" checked="checked"> Owned
                                                    <span></span>
                                                    </label>
                                                    <label class="m-radio">
                                                    <input type="radio" name="ApplyOnlines[owned_rented]" value="1"> Rented
                                                    <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row"><div class="col-md-12">&nbsp;</div></div>
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Document Details</legend>
                                    <div class="row col-md-12">
                                        <div class="col-md-4">
                                            <label>Attach Electricity Bill<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.file_attach_recent_bill', array('label' => false,'type'=>'file','id'=>'electricity_bill','class'=>'form-control','placeholder'=>'Recent Bill.')); ?>
                                            <div id="ele_bill-file-errors"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Aadhaar no.<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.aadhar_no_or_pan_card_no', array('label' => false,'class'=>'form-control','placeholder'=>'Aadhaar no.','type'=>'text')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Aadhaar Card/ Other ID Card<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.file_attach_photo_scan_of_aadhar', array('label' => false,'type'=>'file','id'=>'aadhar_card','class'=>'form-control'));  ?>
                                            <div id="aadhar_card-file-errors"></div>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                    <div class="row col-md-12">
                                        <div class="col-md-4">
                                            <label>Premises Ownership Details No<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.house_tax_holding_no', array('label' => false,'class'=>'form-control','placeholder'=>'','type'=>'text'));  ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Premises Ownership Document<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.file_attach_latest_receipt', array('label' => false,'type'=>'file','id'=>'file_attached_receipt','class'=>'form-control'));   ?>
                                            <div id="file_receipt-file-errors"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Upload Signed Document<span class="mendatory_field">*</span></label>
                                            <?php echo $this->Form->input('ApplyOnlines.signed_doc', array('label' => false,'type'=>'file','id'=>'signed_doc','class'=>'form-control'));   ?>
                                            <div id="signed_doc-file-errors"></div>
                                        </div>
                                        
                                    </div>
                                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                    <div class="row col-md-12">
                                        <div class="col-md-4">
                                            <label>Bank Account No.</label>
                                            <?php echo $this->Form->input('ApplyOnlines.bank_ac_no', array('label' => false,'class'=>'form-control','placeholder'=>'Bank AC no.')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>GST No.</label>
                                            <?php echo $this->Form->input('ApplyOnlines.gstno', array('type' => 'text','label' => false,'class'=>'form-control','placeholder'=>'GST Number.')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="m-checkbox-inline">
                                                <label class="m-checkbox lbl_comunication_address">
                                                <input type="checkbox" value="1" class="check-box-address" name="ApplyOnlines[capexmode]"> The Solar PV system is owned by the Consumer.
                                                <span></span>
                                                </label>
                                            </div>
                                            <div class="m-checkbox-inline">
                                                <label class="m-checkbox">
                                                <input type="checkbox" value="1" class="check-box-address" name="ApplyOnlines[disclaimer_subsidy]"> I don't want subsidy on the Solar PV System.
                                                <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                            <div class="row"><div class="col-md-12">&nbsp;</div></div>
                            <div class="col-md-12">
                                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                                <div class="row col-md-12">
                                    <div class="col-md-1">
                                        <?php 
                                        echo $this->Form->input('ApplyOnlines.disCom_application_fee', array('label' => false,'class'=>'form-control','value'=>'0','type'=>'hidden'));
                                        echo $this->Form->input('ApplyOnlines.jreda_processing_fee', array('label' => false,'class'=>'form-control','value'=>0,'type'=>'hidden'));
                                     ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('Submit', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_5','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_5')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
displayUsageHours();
function displayUsageHours(value) { 
    if(value > 0) {
        $('#usage_hours_div').css('display','');
    } 
    else {
        $('#usage_hours_div').css('display','none');
    }
}
function ShowHideDiv() {
    var discom_data = document.getElementById("discom");
    var tno = document.getElementById("tno");
    tno.style.display = (discom_data.value == '<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || discom_data.value == '<?php echo $ApplyOnlineObj->torent_surat;?>')  ? "block" : "none";
   
}
ShowHideDiv();
function click_division()
{
    if($("#discom").val()=='<?php echo $ApplyOnlineObj->torent_ahmedabad;?>' || $("#discom").val()=='<?php echo $ApplyOnlineObj->torent_surat;?>')
    {
        $.ajax({
            type: "POST",
            url: "getSubDivisionTorrent",
            data: {"division":$("#division").val()},
            success: function(response) {
                var result = $.parseJSON(response);
                $("#subdivision").html('');
                $("#subdivision").html('Sub-division: '+result.data.subdivision.title+'<input type="hidden" value="'+result.data.subdivision.id+'" name="ApplyOnlines[subdivision]"/>');
            }
        });
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "getSubDivisionOther",
            data: {"division":$("#division").val()},
            success: function(response) {
                var data_sub = '';
                var result = $.parseJSON(response);
                $("#subdivision").html('');
                if (result.data.subdivision != undefined) {
                $.each(result.data.subdivision, function(index, title) {
                    $("#subdivision").append($("<option />").val(index).text(title));
                });
                }
                var subdiv_data = $("#subdivision").html();
                $("#subdivision").html('<select class="form-control" name="ApplyOnlines[subdivision]">'+subdiv_data+'</select>');
                
            }
        });
    }
}
$("#discom").change(function(){
    $("#division").html("");
    $('#subdivision').html("");
    $("#division").append($("<option />").val('').text('-Select Division-'));
    detailsFromDiscom();
});
function detailsFromDiscom()
{
    var org_val=$('#division').val();
    $.ajax({
        type: "POST",
        url: "getDivision",
        data: {"discom":$('#discom').val()},
        success: function(response) {
            var result = $.parseJSON(response);
            $("#division").html("");
            $("#division").append($("<option />").val('').text('-Select Division-'));
            if (result.data.division != undefined) {
                $.each(result.data.division, function(index, title) {
                    $("#division").append($("<option />").val(index).text(title));
                });
                $('#division').val(org_val);
                $('#subdivision').html("");
                //click_division();
            }
        }
    });
}
$(document).ready(function() {
    $(".chk_comunication_address").change(function(){
        if($(this).is(":checked")){
            $(".comunication-address").addClass("hide");
        } else {
            $(".comunication-address").removeClass("hide");
        }
    });
    if($(".chk_comunication_address").is(":checked")){
        $(".comunication-address").addClass("hide");
    } else {
        $(".comunication-address").removeClass("hide");
    }
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
        maxFileSize: '1024',
    });
    $("#file_attached_receipt").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#file_receipt-file-errors',
        maxFileSize: '1024',
    });
    $("#signed_doc").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#signed_doc-file-errors',
        maxFileSize: '1024',
    });
    /*$("#applied_doc_1").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#applied_doc_1-file-errors',
        maxFileSize: '1024',
    });
    $("#applied_doc_2").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#applied_doc_2-file-errors',
        maxFileSize: '1024',
    });
    $("#applied_doc_3").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#applied_doc_3-file-errors',
        maxFileSize: '1024',
    });*/
});
</script>