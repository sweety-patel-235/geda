<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
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
</style>
<div class="container-fluid">
        <div class="col-md-12">
            <?php  echo $this->Flash->render('cutom_admin'); ?>
            <div class="tabs">
                <ul class="nav nav-tabs nav-justified">
                    <li class="<?php if($tab_active == 'step1') { echo 'active'; }?>">
                        <a href="#step1" data-toggle="tab" class="text-center"><b> Site Survey 1/4: Customer</b></a>
                    </li>
                    <li class="<?php if($tab_active == 'step2') { echo 'active'; }?>">
                        <a href="<?php if($mode == 'Add') { echo 'javascript:;'; } else { echo '#step2'; } ?>" data-toggle="tab" class="text-center"><b> Site Survey 2/4: Roof</b></a>
                    </li>
                    <li class="<?php if($tab_active == 'step3') { echo 'active'; }?>">
                        <a href="<?php if($mode == 'Add') { echo 'javascript:;'; } else { echo '#step3'; } ?>" data-toggle="tab" class="text-center"><b> Site Survey 3/4: Electrical Interfacing</b></a>
                    </li>
                    <li class="<?php if($tab_active == 'step4') { echo 'active'; }?>">
                        <a href="<?php if($mode == 'Add') { echo 'javascript:;'; } else { echo '#step4'; } ?>" data-toggle="tab" class="text-center"><b> Site Survey 1/4: Bill &amp; Tariff</b></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="step1" class="tab-pane <?php if($tab_active == 'step1') { echo 'active'; }?>">
                        <!-- Modal content-->
                        <div class="modal-body">
                            <?php
                            $app_str = "/".$proj_id;
                            if($mode == 'Edit')
                            {
                                $app_str = "/".$proj_id."/".$sur_id;
                            }
                            ?>
                            <?= $this->Form->create($siteSurvey,['name'=>'siteSurvey','id'=>'sitesurvey', 'url' => '/projects/sitesurvey'.$app_str]);
                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]);
                            echo $this->Form->input('building_id',["type" => "hidden","value"=>(!empty($building_id)?$building_id:'1')]);
                            ?>
                            <div class="form-group">
                                <label for="pv_wp" class="control-label col-sm-2">Building Name</label>
                                <?php echo $this->Form->input('action',["type" => "hidden","label"=>false,"class" => "form-control","value"=>"ADD_PROJECT_SURVEY"]);?>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['building_name']) && isset($SiteSurveysErrors['building_name']['_empty']) && !empty($SiteSurveysErrors['building_name']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-4 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('building_name',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-2">Surveyor Name</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('surveyer_name',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pv_wp" class="control-label col-sm-2">Contact Person</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['contact_name']) && isset($SiteSurveysErrors['contact_name']['_empty']) && !empty($SiteSurveysErrors['contact_name']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-4 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('contact_name',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-2">Designation</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('designation',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pv_wp" class="control-label col-sm-2">Mobile No</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['mobile']) && isset($SiteSurveysErrors['mobile']['_empty']) && !empty($SiteSurveysErrors['mobile']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-4 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('mobile',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-2">Landline No</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('landline',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pv_wp" class="control-label col-sm-2">Email</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['email_id']) && isset($SiteSurveysErrors['email_id']['_empty']) && !empty($SiteSurveysErrors['email_id']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-4 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('email_id',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-2">Address 1</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('address1',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pv_wp" class="control-label col-sm-2">Address 2</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('address2',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-2">Address 3</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('address3',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note" class="control-label col-sm-2">Notes</label>
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('notes1',["type" => "textarea","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>
                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left', "name" => "step_1"]); ?><br/>
                            <?= $this->Form->end(); ?>

                        </div>
                    </div>
                    <div id="step2" class="tab-pane <?php if($tab_active == 'step2') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?php
                            $app_str = "/".$proj_id;
                            if($mode == 'Edit')
                            {
                                $app_str = "/".$proj_id."/".$sur_id;
                            }
                            ?>
                            <?= $this->Form->create($siteSurvey,['name'=>'sitesurvey','id'=>'sitesurvey','type' => 'file','enctype'=>"multipart/form-data", 'url' => '/projects/sitesurvey'.$app_str]);
                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]);
                            echo $this->Form->input('building_id',["type" => "hidden","value"=>(!empty($building_id)?$building_id:'1')]);
                            ?>
                            <div class="form-group">
                                <?php echo $this->Form->input('action',["type" => "hidden","label"=>false,"class" => "form-control","value"=>"ADD_PROJECT_SURVEY"]);?>
                                <label for="pv_wp" class="control-label col-sm-2">Type Of Roof</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['roof_type']) && isset($SiteSurveysErrors['roof_type']['_empty']) && !empty($SiteSurveysErrors['roof_type']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-4 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->select('roof_type',$arr_roof_type,["label"=>false,"class" => "form-control"]);?>
                                    <?php
                                    if(isset($SiteSurveysErrors['roof_type']) && isset($SiteSurveysErrors['roof_type']['_empty']) && !empty($SiteSurveysErrors['roof_type']['_empty'])){  ?>
                                        <div class="help-block"><?php echo $SiteSurveysErrors['roof_type']['_empty']; ?></div>
                                    <?php } ?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-2">Roof Strength</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->select('roof_strenght',$arr_roof_st,["label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Overall Area</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['overall']) && isset($SiteSurveysErrors['overall']['_empty']) && !empty($SiteSurveysErrors['overall']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-2 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('overall',["type" => "number","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-2">
                                    <?php
                                    $is_overall = '2001';
                                    if($mode == 'Edit')
                                    {
                                        $is_overall = $siteSurvey['is_overall'];
                                    }
                                    echo $this->Form->radio('is_overall', $arr_area, array("class" => "form-control",'label' => array('escape' => false), "value" => $is_overall));?>
                                </div>
                                <label for="" class="control-label col-sm-2">Shadow Free Area</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['shadow_free']) && isset($SiteSurveysErrors['shadow_free']['_empty']) && !empty($SiteSurveysErrors['shadow_free']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-2 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('shadow_free',["type" => "number","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-2">
                                    <?php
                                    $is_shadow_free = '2001';
                                    if($mode == 'Edit')
                                    {
                                        $is_shadow_free = $siteSurvey['is_shadow_free'];
                                    }
                                    echo $this->Form->radio('is_shadow_free', $arr_area, array("class" => "form-control",'label' => array('escape' => false), "value" => $is_shadow_free));
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Road To Site</label>
                                <div class="col-sm-4">
                                    <?php
                                    $road_to_site = '0';
                                    if($mode == 'Edit')
                                    {
                                        $road_to_site = $siteSurvey['road_to_site'];
                                    }
                                    echo $this->Form->radio('road_to_site', array('0' => ' No&nbsp;&nbsp;', '1' => ' Yes'), array("class" => "form-control", 'label' => array('escape' => false), "value" => $road_to_site));?>
                                </div>
                                <label for="" class="control-label col-sm-2">Ladder To Roof</label>
                                <div class="col-sm-4">
                                    <?php
                                    $ladder_to_roof = '0';
                                    if($mode == 'Edit')
                                    {
                                        $ladder_to_roof = $siteSurvey['ladder_to_roof'];
                                    }
                                    echo $this->Form->radio('ladder_to_roof', array('0' => ' No&nbsp;&nbsp;', '1' => ' Yes'), array("class" => "form-control", 'label' => array('escape' => false), "value" => $ladder_to_roof));?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Age Of Building</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('age_of_building',["type" => "number","label"=>false,"class" => "form-control", 'placeholder' => 'in Years']);?>
                                </div>
                                <label for="" class="control-label col-sm-2">Azimuth</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('azimuth',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Inclination Of Roof</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('inclination_of_roof',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="" class="control-label col-sm-2">Is there any major shadow casting object on the roof?</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('object_on_roof',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Height Of Parapet</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('height_of_parapet',["type" => "number","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-2">
                                    <?php
                                    $is_height_of_parapet = '2001';
                                    if($mode == 'Edit')
                                    {
                                        $is_height_of_parapet = $siteSurvey['is_height_of_parapet'];
                                    }
                                    echo $this->Form->radio('is_height_of_parapet', $arr_area_smp, array("class" => "form-control",'label' => array('escape' => false), "value" => $is_height_of_parapet));
                                    ?>
                                </div>
                                <label for="" class="control-label col-sm-2">Floor Bellow Terrace</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('age_of_building',["type" => "number","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Distance Of Dc Cable</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('dc_cabel_distance',["type" => "number","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-2">
                                    <?php
                                    $is_dc_cable_distance = '2001';
                                    if($mode == 'Edit')
                                    {
                                        $is_dc_cable_distance = $siteSurvey['is_dc_cable_distance'];
                                    }
                                    echo $this->Form->radio('is_dc_cable_distance', $arr_area_smp, array("class" => "form-control",'label' => array('escape' => false), "value" => $is_dc_cable_distance));
                                    ?>
                                </div>
                                <label for="" class="control-label col-sm-2">Place for inverter</label>
                                <div class="col-sm-4" >
                                    <div class="file-loading" >
                                        <input id="place_inverter" name="document[place_inverter][]" type="file" multiple>
                                    </div>
                                    <div id="placeinv-file-errors"></div>
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php
                                                $counter=1;
                                                foreach($all_photo_data as $ph_data)
                                                {
                                                    if($ph_data['type'] == 'place_inverter')
                                                    {
                                                        $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                        if (file_exists($path))
                                                        {
                                                            $image_url=URL_HTTP.SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                            ?>
                                                            <td width="25%" id="photo_<?php echo encode($ph_data['id']);?>" style="padding-top:10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="<?php echo $image_url;?>" width="75px">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><i class="fa fa-remove"  onclick="javascript:delete_photo('<?php echo encode($ph_data['id']);?>');"> </i></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <?php

                                                            if($counter%4 == 0)
                                                            {
                                                                echo '</tr><tr>';
                                                            }
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Place for Battery</label>
                                <div class="col-sm-4">
                                    <div class="file-loading" >
                                        <input id="place_battery" name="document[place_battery][]" type="file" multiple>
                                    </div>
                                    <div id="placebat-file-errors"></div>
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php
                                                $counter=1;
                                                foreach($all_photo_data as $ph_data)
                                                {
                                                    if($ph_data['type'] == 'place_battery')
                                                    {
                                                        $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                        if (file_exists($path))
                                                        {
                                                            $image_url=URL_HTTP.SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                            ?>
                                                            <td width="25%" id="photo_<?php echo encode($ph_data['id']);?>" style="padding-top:10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="<?php echo $image_url;?>" width="75px">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><i class="fa fa-remove" onclick="javascript:delete_photo('<?php echo encode($ph_data['id']);?>');"> </i></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <?php

                                                            if($counter%4 == 0)
                                                            {
                                                                echo '</tr><tr>';
                                                            }
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <label for="" class="control-label col-sm-2">Place for AC Destribution box</label>
                                <div class="col-sm-4">
                                    <div class="file-loading" >
                                        <input id="place_for_ac_distribution_box" name="document[place_for_ac_distribution_box][]" type="file" multiple>
                                    </div>
                                    <div id="placeac-file-errors"></div>
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php
                                                $counter=1;
                                                foreach($all_photo_data as $ph_data)
                                                {
                                                    if($ph_data['type'] == 'place_for_ac_distribution_box')
                                                    {
                                                        $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                        if (file_exists($path))
                                                        {
                                                            $image_url=URL_HTTP.SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                            ?>
                                                            <td width="25%" id="photo_<?php echo encode($ph_data['id']);?>" style="padding-top:10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="<?php echo $image_url;?>" width="75px">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><i class="fa fa-remove" onclick="javascript:delete_photo('<?php echo encode($ph_data['id']);?>');"> </i></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <?php

                                                            if($counter%4 == 0)
                                                            {
                                                                echo '</tr><tr>';
                                                            }
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2">Place for Metering Point</label>
                                <div class="col-sm-4">
                                    <div class="file-loading" >
                                        <input id="metering_box" name="document[metering_box][]" type="file" multiple>
                                    </div>
                                    <div id="placemb-file-errors"></div>
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php
                                                $counter=1;
                                                foreach($all_photo_data as $ph_data)
                                                {
                                                    if($ph_data['type'] == 'metering_box')
                                                    {
                                                        $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                        if (file_exists($path))
                                                        {
                                                            $image_url=URL_HTTP.SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                            ?>
                                                            <td width="25%" id="photo_<?php echo encode($ph_data['id']);?>" style="padding-top:10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="<?php echo $image_url;?>" width="75px">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><i class="fa fa-remove" onclick="javascript:delete_photo('<?php echo encode($ph_data['id']);?>');"> </i></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <?php

                                                            if($counter%4 == 0)
                                                            {
                                                                echo '</tr><tr>';
                                                            }
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <label for="" class="control-label col-sm-2">Take Photographs</label>
                                <div class="col-sm-4">
                                    <div class="file-loading" >
                                        <input id="take_photographs" name="document[take_photographs][]" type="file" multiple>
                                    </div>
                                    <div id="takephoto-file-errors"></div>
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php
                                                $counter=1;
                                                foreach($all_photo_data as $ph_data)
                                                {
                                                    if($ph_data['type'] == 'take_photographs')
                                                    {
                                                        $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                        if (file_exists($path))
                                                        {
                                                            $image_url=URL_HTTP.SITE_SURVEY_PATH.$ph_data['type'].'/'.decode($proj_id).'/'.$ph_data['photo'];
                                                            ?>
                                                            <td width="25%" id="photo_<?php echo encode($ph_data['id']);?>" style="padding-top:10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="<?php echo $image_url;?>" width="75px">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><i class="fa fa-remove"  onclick="javascript:delete_photo('<?php echo encode($ph_data['id']);?>');"> </i></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <?php

                                                            if($counter%4 == 0)
                                                            {
                                                                echo '</tr><tr>';
                                                            }
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="note" class="control-label col-sm-2">Notes</label>
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('notes2',["type" => "textarea","label"=>false,"class" => "form-control"]);?>
                                </div>
                            </div>
                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull=left', 'name' => "step_2"]); ?><br/>
                            <?= $this->Form->end(); ?>

                        </div>
                    </div>
                    <div id="step3" class="tab-pane <?php if($tab_active == 'step3') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?php
                            $app_str = "/".$proj_id;
                            if($mode == 'Edit')
                            {
                                $app_str = "/".$proj_id."/".$sur_id;
                            }

                            ?>
                            <?= $this->Form->create($siteSurvey,['name'=>'sitesurvey','id'=>'sitesurvey','type' => 'file','enctype'=>"multipart/form-data", 'url' => '/projects/sitesurvey'.$app_str]);
                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]);
                            echo $this->Form->input('building_id',["type" => "hidden","value"=>(!empty($building_id)?$building_id:'1')]);
                            ?>
                            <div class="form-group">
                                <?php echo $this->Form->input('action',["type" => "hidden","label"=>false,"class" => "form-control","value"=>"ADD_PROJECT_SURVEY"]);?>
                                <label for="" class="control-label col-sm-2">Voltage Level Phase</label>
                                <div class="col-sm-4">
                                    <?php
                                    $voltage_pahse_level = '3ph.';
                                    if($mode == 'Edit')
                                    {
                                        $voltage_pahse_level = $siteSurvey['voltage_pahse_level'];
                                    }
                                    echo $this->Form->radio('voltage_pahse_level', array('3ph.'=>' 3ph.&nbsp;&nbsp;','1ph.'=>' 1ph.'), array("class" => "form-control",'label' => array('escape' => false), "value" => $voltage_pahse_level));

                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php
                                $reading_details_arr   = array();
                                $reading_det_style     = "none";
                                $reading_det           = '0';
                                if(isset($siteSurvey) && !empty($siteSurvey['reading_details']))
                                {
                                    $reading_details     = unserialize($siteSurvey['reading_details']);
                                    $reading_details_arr = $reading_details['ReadingDetails'];
                                }
                                if(!empty($reading_details_arr))
                                {
                                    $reading_det       = '1';
                                    $reading_det_style = "block";
                                }
                                ?>
                                <table class="table custom_table" id="customFields">
                                    <tbody>
                                    <?php
                                    if(empty($reading_details_arr))
                                    {
                                        ?>
                                        <tr id="rowId">
                                            <td width="95%" class="wwe-lang-matches" scope="col">
                                                <div class="form-group formctl">
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('r_phase[]',["type" => "number","class" => "form-control",'label' => ['text' => 'R-Phase/A ']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('r_phase_ry[]',["type" => "number","class" => "form-control",'label' => ['text' => 'RY/V']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('r_phase_rn[]',["type" => "number","class" => "form-control",'label' => ['text' => 'RN/V ']]);?>
                                                    </div>
                                                </div>
                                                <div class="form-group formctl">
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('y_phase[]',["type" => "number","class" => "form-control",'label' => ['text' => 'Y-Phase/A ']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('y_phase_yb[]',["type" => "number","class" => "form-control",'label' => ['text' => 'YB/V']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('y_phase_yn[]',["type" => "number","class" => "form-control",'label' => ['text' => 'YN/V ']]);?>
                                                    </div>
                                                </div>
                                                <div class="form-group formctl">
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('b_phase[]',["type" => "number","class" => "form-control",'label' => ['text' => 'B-Phase/A ']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('b_phase_rb[]',["type" => "number","class" => "form-control",'label' => ['text' => 'RB/V']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('b_phase_bn[]',["type" => "number","class" => "form-control",'label' => ['text' => 'BN/V ']]);?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="5%" class="remove_button" ><i class="fa fa-remove" style="color:#ff0000;display:none;"> </i></td>
                                        </tr>
                                        <?php
                                    }

                                    foreach($reading_details_arr as $key=>$reading_det)
                                    {
                                        $tr_id = '';
                                        if($key == 0)
                                        {
                                            $tr_id = "rowId";
                                        }
                                        ?>
                                        <tr id="<?php echo $tr_id;?>">
                                            <td width="95%" class="wwe-lang-matches" scope="col">
                                                <div class="form-group formctl">
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('r_phase[]',["type" => "number","class" => "form-control",'label' => ['text' => 'R-Phase/A '], "value" => $reading_det['r_phase']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('r_phase_ry[]',["type" => "number","class" => "form-control",'label' => ['text' => 'RY/V'], "value" => $reading_det['r_phase_ry']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('r_phase_rn[]',["type" => "number","class" => "form-control",'label' => ['text' => 'RN/V '], "value" => $reading_det['r_phase_rn']]);?>
                                                    </div>
                                                </div>
                                                <div class="form-group formctl">
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('y_phase[]',["type" => "number","class" => "form-control",'label' => ['text' => 'Y-Phase/A '], "value" => $reading_det['y_phase']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('y_phase_yb[]',["type" => "number","class" => "form-control",'label' => ['text' => 'YB/V'], "value" => $reading_det['y_phase_yb']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('y_phase_yn[]',["type" => "number","class" => "form-control",'label' => ['text' => 'YN/V '], "value" => $reading_det['y_phase_yn']]);?>
                                                    </div>
                                                </div>
                                                <div class="form-group formctl">
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('b_phase[]',["type" => "number","class" => "form-control",'label' => ['text' => 'B-Phase/A '], "value" => $reading_det['b_phase']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('b_phase_rb[]',["type" => "number","class" => "form-control",'label' => ['text' => 'RB/V'], "value" => $reading_det['b_phase_rb']]);?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <?php echo $this->Form->input('b_phase_bn[]',["type" => "number","class" => "form-control",'label' => ['text' => 'BN/V '], "value" => $reading_det['b_phase_bn']]);?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="5%" class="remove_button" ><i class="fa fa-remove" style="color:#ff0000;display:none;"> </i></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="_100 h_63">
                                    <p>
                                        <label class="spanright">
                                            <span ><a href="javascript:;" class="addmore" style="font-weight: bold;">+Add More</a></span>
                                        </label>
                                    </p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="measured_frequency" class="control-label col-sm-2">Measured Frequency</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('measured_frequency',["type" => "number", "label" => false, "class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-1">
                                    Hz
                                </div>
                                <label for="critical_load" class="control-label col-sm-2">Critical Load</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('critical_load',["type" => "number",  "label" => false, "class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-1">
                                    kW
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="genset_details" class="control-label col-sm-2">Diesel Genset</label>
                                <div class="col-sm-4">
                                    <?php
                                    $genset_details_arr     = array();
                                    $avg_diesel_consumption = '';
                                    $gen_det                = '0';
                                    $gen_det_style          = "none";
                                    if(isset($siteSurvey) && !empty($siteSurvey['genset_details']))
                                    {
                                        $genset_details     = unserialize($siteSurvey['genset_details']);
                                        $genset_details_arr = $genset_details['GensetDetails'];
                                    }
                                    if(!empty($genset_details_arr) || $siteSurvey['avg_diesel_consumption']!='')
                                    {
                                        $gen_det       = '1';
                                        $gen_det_style = "block";
                                    }
                                    echo $this->Form->radio('gen_det',array(
                                            array('value' => '0', 'text' => ' No&nbsp;&nbsp;', 'class' => 'diesel_gen'),
                                            array('value' => '1', 'text' => ' Yes', 'class' => 'diesel_gen'))
                                        ,array("value" => $gen_det, "label" => array("escape" => false)));
                                    ?>
                                </div>
                                <label for="avg_diesel_consumption" class="control-label col-sm-2" id="avg_diesel_label" style="display: <?php echo $gen_det_style;?>">Average Diesel Consumption</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('avg_diesel_consumption',["type" => "number", "label" => false, "placeholder" => "Litre", "class" => "form-control", "style" => "display: $gen_det_style"]);?>
                                </div>
                            </div>
                            <div class="form-group" style="margin:0px;padding:0px;display: <?php echo $gen_det_style;?>;" id="add_diesel_data">
                                <div class="col-sm-6" style="margin:0px;padding:0px;">
                                    <table class="table custom_table" id="customFields2" cellspacing="0" cellpadding="0">
                                        <tbody>
                                        <?php
                                        if(empty($genset_details_arr))
                                        {
                                            ?>
                                            <tr id="rowId2">
                                                <td class="wwe-lang-matches" scope="col">
                                                    <div class="form-group" style="margin:0px;padding:0px;">
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('capacity[]',["type" => "number","class" => "form-control",'label' => false]);?>
                                                        </div>
                                                        <div class="col-sm-1" style="padding-left:0px;">
                                                            kVA
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('usage[]',["type" => "number","class" => "form-control",'label' => false]);?>
                                                        </div>
                                                        <div class="col-sm-2" style="padding-left:0px;">
                                                            Hours/Day
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <i class="fa fa-remove" style="color:#ff0000;display:none;"> </i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        foreach($genset_details_arr as $key=>$gen_det)
                                        {
                                            $tr_id = '';
                                            if($key == 0)
                                            {
                                                $tr_id = "rowId2";
                                            }
                                            ?>
                                            <tr id="<?php echo $tr_id;?>">
                                                <td class="wwe-lang-matches" scope="col">
                                                    <div class="form-group" style="margin:0px;padding:0px;">
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('capacity[]',["type" => "number","class" => "form-control",'label' => false, "value" => $gen_det['kva']]);?>
                                                        </div>
                                                        <div class="col-sm-1" style="padding-left:0px;">
                                                            kVA
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('usage[]',["type" => "number","class" => "form-control",'label' => false, "value" => $gen_det['hours']]);?>
                                                        </div>
                                                        <div class="col-sm-2" style="padding-left:0px;">
                                                            Hours/Day
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <i class="fa fa-remove" style="color:#ff0000;display:none;"> </i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <label class="spanright">
                                        <span ><a href="javascript:;" class="addmore2" style="font-weight: bold;">+Add More</a></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inverter" class="control-label col-sm-2">Inverter</label>
                                <div class="col-sm-4">
                                    <?php
                                    $inverter_details_arr   = array();
                                    $inverter_det_style     = "none";
                                    $inverter_det           = '0';
                                    if(isset($siteSurvey) && !empty($siteSurvey['inverter_details']))
                                    {
                                        $inverter_details     = unserialize($siteSurvey['inverter_details']);
                                        $inverter_details_arr = $inverter_details['InverterDetails'];
                                    }
                                    if(!empty($inverter_details_arr))
                                    {
                                        $inverter_det       = '1';
                                        $inverter_det_style = "block";
                                    }

                                    echo $this->Form->radio('inverter',array(
                                            array('value' => '0', 'text' => ' No&nbsp;&nbsp;', 'class' => 'inv_chk'),
                                            array('value' => '1', 'text' => ' Yes', 'class' => 'inv_chk'))
                                        ,array("value" => $inverter_det, "label" => array("escape" => false)));
                                    ?>
                                </div>
                            </div>
                            <div class="form-group" style="margin:0px;padding:0px;display: <?php echo $inverter_det_style;?>;" id="inv_add_data">
                                <div class="col-sm-6" style="margin:0px;padding:0px;">
                                    <table class="table custom_table" id="customFields3" cellspacing="0" cellpadding="0">
                                        <tbody>
                                        <?php
                                        if(empty($inverter_details_arr))
                                        {
                                            ?>
                                            <tr id="rowId3">
                                                <td class="wwe-lang-matches" scope="col">
                                                    <div class="form-group" style="margin:0px;padding:0px;">
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('capacity_i[]',["type" => "number","class" => "form-control",'label' => false]);?>
                                                        </div>
                                                        <div class="col-sm-1" style="padding-left:0px;">
                                                            kVA
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('usage_i[]',["type" => "number","class" => "form-control",'label' => false]);?>
                                                        </div>
                                                        <div class="col-sm-2" style="padding-left:0px;">
                                                            Hours/Day
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <i class="fa fa-remove" style="color:#ff0000;display:none;"> </i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        foreach($inverter_details_arr as $key=>$inv_details)
                                        {
                                            $tr_id = '';
                                            if($key == 0)
                                            {
                                                $tr_id = "rowId3";
                                            }
                                            ?>
                                            <tr id="<?php echo $tr_id;?>">
                                                <td class="wwe-lang-matches" scope="col">
                                                    <div class="form-group" style="margin:0px;padding:0px;">
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('capacity_i[]',["type" => "number","class" => "form-control",'label' => false, "value" => $inv_details['kva']]);?>
                                                        </div>
                                                        <div class="col-sm-1" style="padding-left:0px;">
                                                            kVA
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <?php echo $this->Form->input('usage_i[]',["type" => "number","class" => "form-control",'label' => false, "value" => $inv_details['hours']]);?>
                                                        </div>
                                                        <div class="col-sm-2" style="padding-left:0px;">
                                                            Hours/Day
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <i class="fa fa-remove" style="color:#ff0000;display:none;"> </i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <label class="spanright">
                                        <span ><a href="javascript:;" class="addmore3" style="font-weight: bold;">+Add More</a></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group formctl">
                                <label for="pv_wp" class="control-label col-sm-3">Approximate percentage of power consumed between 10AM to 5PM</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('approx_power_consumed',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="pv_wp" class="control-label col-sm-3">Working Days Per Week</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('working_day_week',["type" => "number", "class" => "form-control",'label' =>false]);?>
                                </div>
                            </div>

                            <div class="form-group formctl">
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('notes3',["type" => "textarea","class" => "form-control",'label' => ['text' => 'Note']]);?>
                                </div>
                                <label for="take_ph" class="control-label col-sm-2">Take Photograph</label>
                                <div class="col-sm-4">
                                    <div class="file-loading" >
                                        <input id="electricity_bill" name="document[electricity_bill][]" type="file" multiple>
                                    </div>
                                    <div id="elecbill-file-errors"></div>
                                    <div>
                                        <table width="100%">
                                            <tr>
                                                <?php
                                                $counter=1;
                                                foreach($all_photo_data as $ph_data)
                                                {
                                                    if($ph_data['type'] == 'electricity_bill')
                                                    {
                                                        $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$proj_id.'/'.$ph_data['photo'];
                                                        if (file_exists($path))
                                                        {
                                                            $image_url=URL_HTTP.SITE_SURVEY_PATH.$ph_data['type'].'/'.$proj_id.'/'.$ph_data['photo'];
                                                            ?>
                                                            <td width="25%" id="photo_<?php echo encode($ph_data['id']);?>" style="padding-top:10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="<?php echo $image_url;?>" width="75px">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><i class="fa fa-remove" onclick="javascript:delete_photo('<?php echo encode($ph_data['id']);?>');"> </i></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <?php

                                                            if($counter%4 == 0)
                                                            {
                                                                echo '</tr><tr>';
                                                            }
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left', "name" => "step_3"]); ?><br/>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                    <div id="step4" class="tab-pane <?php if($tab_active == 'step4') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?php
                            $app_str = "/".$proj_id;
                            if($mode == 'Edit')
                            {
                                $app_str = "/".$proj_id."/".$sur_id;
                            }
                            ?>
                            <?= $this->Form->create($siteSurvey,['name'=>'sitesurvey','id'=>'sitesurvey', 'url' => '/projects/sitesurvey'.$app_str]);
                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]);
                            echo $this->Form->input('building_id',["type" => "hidden","value"=>(!empty($building_id)?$building_id:'1')]);
                            ?>

                            <div class="form-group">
                                <?php echo $this->Form->input('action',["type" => "hidden","label"=>false,"class" => "form-control","value"=>"ADD_PROJECT_SURVEY"]);?>
                                <label for="meter_type" class="control-label col-sm-2">Distribution Company</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('distribution_company',["type" => "text", "class" => "form-control", "label" => false]);?>
                                </div>
                                <label for="meter_type" class="control-label col-sm-2">Service/Customer No</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('customer_no',["type" => "text","class" => "form-control",'label' => false]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="meter_type" class="control-label col-sm-2">Meter Type</label>
                                <div class="col-sm-4">
                                    <?php
                                    echo $this->Form->select('meter_type', $arr_meter_type, array("label"=>false,"class" => "form-control"));?>
                                </div>
                                <label for="meter_acc" class="control-label col-sm-2">Meter Accuracy Class</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->select('meter_accuracy', $arr_meter_acc, array("label"=>false,"class" => "form-control"));?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cust_type" class="control-label col-sm-2">Type of Customer</label>
                                <?php $error_class_for_name_prefix = '';
                                if(isset($SiteSurveysErrors['customer_type']) && isset($SiteSurveysErrors['customer_type']['_empty']) && !empty($SiteSurveysErrors['customer_type']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                                <div class="col-sm-4 <?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->select('customer_type', $all_cust_type, array("class" => "form-control"));?>
                                    <?php
                                    if(isset($SiteSurveysErrors['customer_type']) && isset($SiteSurveysErrors['customer_type']['_empty']) && !empty($SiteSurveysErrors['customer_type']['_empty'])){  ?>
                                        <div class="help-block"><?php echo $SiteSurveysErrors['customer_type']['_empty']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <label for="cust_type" class="control-label col-sm-2">Sanction/Contract</label>
                                <div class="col-sm-2">
                                    <?php
                                    $is_snaction = '0';
                                    if($mode == 'Edit')
                                    {
                                        $is_snaction = $siteSurvey['is_snaction'];
                                    }
                                    ?>
                                    <?php echo $this->Form->radio('is_snaction', $arr_load_param, array("class" => "form-control",'label' => array('escape' => false), "value" => $is_snaction));
                                    ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('sanctioned_load',["type" => "number","class" => "form-control", "label" => false]);?>
                                </div>
                            </div>

                            <div class="form-group formctl">
                                <label for="cust_type" class="control-label col-sm-2">Billing Cycle</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->select('billing_cycle', $arr_billing_cycle, array("class" => "form-control"));?>
                                </div>
                            </div>

                            <div class="form-group" style="margin-left: 17px;margin-right: 17px;">
                                <table class="table table-bordered custom_table" >
                                    <tr>
                                        <td class="wwe-lang-matches"><strong>Sr No.</strong></td>
                                        <td class="wwe-lang-matches"><strong>Month</strong></td>
                                        <td class="wwe-lang-matches"><strong>Year</strong></td>
                                        <td class="wwe-lang-matches"><strong>Power Consumed (kWh)</strong></td>
                                        <td class="wwe-lang-matches"><strong>Billing (&#8377;)</strong></td>
                                    </tr>
                                    <?php
                                    if(isset($siteSurvey) && !empty($siteSurvey['month_details']))
                                    {
                                        $arr_month=unserialize($siteSurvey['month_details']);
                                        $arr_all_month=$arr_month['ElectricityBillDetails'];
                                    }
                                    $arr_year = array(date('Y') => date('Y'), date('Y')-1 => date('Y')-1, date('Y')-2 => date('Y')-2);
                                    for($i = 1; $i <= 12; $i++)
                                    {
                                        $bill_amt  = '';
                                        $power_con = '';
                                        $year      = date('Y');
                                        if(!empty($arr_all_month))
                                        {
                                            $bill_amt   = $arr_all_month[$i-1]['bill_amount'];
                                            $power_con  = $arr_all_month[$i-1]['power_consume'];
                                            $year       = $arr_all_month[$i-1]['year'];
                                            if(!in_array($year, $arr_year))
                                            {
                                                $arr_year[$year] = $year;
                                            }
                                        }
                                        $j = $i+1;
                                        if($i <= 9)
                                        {
                                            $j = '0'.$i+1;
                                        }
                                        ?>
                                        <tr>
                                            <td class="wwe-lang-matches" scope="col">
                                                <?php echo $i;?>
                                            </td>
                                            <td class="wwe-lang-matches" scope="col">
                                                <?php echo date('F',mktime('0', '0', '0', $j, '0', '0'));?>
                                            </td>
                                            <td class="wwe-lang-matches" scope="col">
                                                <div class="col-sm-12">
                                                    <?php
                                                    echo $this->Form->select('year[]', $arr_year, array("class" => "form-control",'label' => false,'value'=>$year));?>
                                                </div>
                                            </td>
                                            <td class="wwe-lang-matches" scope="col">
                                                <div class="col-sm-12">
                                                    <?php echo $this->Form->input('power_consume[]',["type" => "number","class" => "form-control",'label' => false, "value" => $power_con]);?>
                                                </div>
                                            </td>
                                            <td class="wwe-lang-matches" scope="col">
                                                <div class="col-sm-12">
                                                    <?php echo $this->Form->input('bill_amount[]',["type" => "number","class" => "form-control",'label' => false, "value" => $bill_amt]);?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label for="fixed_ch" class="control-label col-sm-2">Fixed Charges</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fixcharges_upto',["type" => "number","class" => "form-control", 'placeholder' => 'Up to', 'label' => false]);?>
                                </div>
                                <div class="col-sm-2">
                                    kW
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fixcharges_upto_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kW
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bet_frm" class="control-label col-sm-2">Between From</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('fxbetween1_from',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="bet_to" class="control-label col-sm-1">To</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('fxbetween1_to',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    kW
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fxbetween1_to_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bet_frm1" class="control-label col-sm-2">Between From</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('fxbetween2_from',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="bet_to1" class="control-label col-sm-1">To</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('fxbetween2_to',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    kW
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fxbetween2_to_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="mr_th" class="control-label col-sm-2">More Than</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fxmorethen',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <div class="col-sm-2">
                                    kW
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fxmorethen_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kW
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ene_charge" class="control-label col-sm-2">Energy Charges</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('eccharges_upto',["type" => "number","class" => "form-control", "placeholder" => "Up to", 'label' => false]);?>
                                </div>
                                <div class="col-sm-2">
                                    kWh
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('eccharges_upto_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bet_frm_e" class="control-label col-sm-2">Between From</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('ecbetween1_from',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="bet_to_e" class="control-label col-sm-1">To</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('ecbetween1_to',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    kWh
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('ecbetween1_to_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bet_frm1_e" class="control-label col-sm-2">Between From</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('ecbetween2_from',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="bet_to1_e" class="control-label col-sm-1">To</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('ecbetween2_to',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    kWh
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('ecbetween2_to_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="mr_th_e" class="control-label col-sm-2">More Than</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('ecmorethen',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <div class="col-sm-2">
                                    kWh
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('ecmorethen_rs',["type" => "number","class" => "form-control", 'placeholder' => 'Rs', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    Per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fuel_sur" class="control-label col-sm-2">Fule Surcharge</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fuel_charges', ["type" => "number","class" => "form-control", 'placeholder' => '%', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    per kWh
                                </div>
                                <label for="ele_due" class="control-label col-sm-2">Electricity Duty</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('electricity_duty',["type" => "number","class" => "form-control", 'placeholder' => '%', 'label' => false]);?>
                                </div>
                                <div class="col-sm-1">
                                    per kWh
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="other_rebete" class="control-label col-sm-2">Other Surcharge</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('other_surcharges1',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="other_rebete" class="control-label col-sm-2">Other Surcharge</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('other_surcharges2',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="other_rebete" class="control-label col-sm-2">Other Rebete</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('other_rebate',["type" => "number","class" => "form-control",'label' => false]);?>
                                </div>
                                <label for="note_4" class="control-label col-sm-2">Notes</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('notes4',["type" => "textarea","class" => "form-control",'label' => false]);?>
                                </div>
                            </div>
                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left', "name" => "step_4"]); ?><br/>
                            <?= $this->Form->end(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var selectid1 = 1;
        var selectid2 = 1;
        var selectid3 = 1;
        var clonerow = '';
        if($("#rowId").attr('id') == 'rowId'){
            clonerow = $("#rowId").clone();

        }
        $('.diesel_gen').click(function(){
            if($(this).val() == 1)
            {
                $("#add_diesel_data").show();
                $("#avg_diesel_label").show();
                $("#avg-diesel-consumption").show();
            }
            else
            {
                $("#add_diesel_data").hide();
                $("#avg_diesel_label").hide();
                $("#avg-diesel-consumption").hide();
            }
        });
        $('.inv_chk').click(function(){
            if($(this).val() == 1)
            {
                $("#inv_add_data").show();
            }
            else
            {
                $("#inv_add_data").hide();
            }
        });
        $('.addmore').click(function(){

            clonerow = $(clonerow).removeAttr("id");

            $(clonerow).find("a").removeClass('hide');
            $(clonerow).find("input").val('');
            $(clonerow).find("input").removeAttr("id");
            $(clonerow).find("select").attr("id",$(clonerow).find("select").attr("id")+selectid1);
            $(clonerow).find("#"+$(clonerow).find("select").attr("id")+"_chzn").remove();
            $(clonerow).find("select").removeClass('chzn-done');
            $(clonerow).find("#"+$(clonerow).find("select").attr("id")+selectid1).removeAttr('style');
            $(clonerow).find(".fa-remove").removeAttr("style");
            $(clonerow).find(".fa-remove").attr("style",'color:#ff0000;cursor:pointer;');
            $(clonerow).find(".fa-remove").attr("onclick",'javascript:$(this).parent().parent().remove();');

            $('#customFields tr').last().after($(clonerow).clone());

//alert($(clonerow).find("#"+$(clonerow).find("fa-remove").attr("id")+selectid1).attr('style'));
            $('#customFields tbody tr').last().find('span.checkbox').remove();
            $('#customFields tbody tr').last().find("input[type='checkbox']").attr('checked',false);
            $('#customFields tbody tr').last().find('input[type=checkbox]').each(function(){$(this).checkbox({
                cls : 'checkbox',
                empty : WEB_ADMIN_URL+'img/sprites/forms/checkboxes/empty.png'
            });
                //$('#customFields').find("#"+$(clonerow).find("checkbox").attr("id")+selectid).chosen();
                /* $(clonerow).find('span.checkbox').remove(); $(clonerow).find('input[type=checkbox]').each(function(){$(this).checkbox({
                 cls : 'checkbox',
                 empty : WEB_ADMIN_URL+'img/sprites/forms/checkboxes/empty.png'
                 }); */

            });

            console.log();
            selectid1++;
        });
        if($("#rowId2").attr('id') == 'rowId2'){
            clonerow2 = $("#rowId2").clone();
        }
        $('.addmore2').click(function(){
            clonerow2 = $(clonerow2).removeAttr("id");
            $(clonerow2).find("a").removeClass('hide');
            $(clonerow2).find("input").val('');
            $(clonerow2).find("input").removeAttr("id");
            $(clonerow2).find("select").attr("id",$(clonerow2).find("select").attr("id")+selectid2);
            $(clonerow2).find("#"+$(clonerow2).find("select").attr("id")+"_chzn").remove();
            $(clonerow2).find("select").removeClass('chzn-done');
            $(clonerow2).find("#"+$(clonerow2).find("select").attr("id")+selectid2).removeAttr('style');
            $(clonerow2).find(".fa-remove").removeAttr("style");
            $(clonerow2).find(".fa-remove").attr("style",'color:#ff0000;cursor:pointer;');
            $(clonerow2).find(".fa-remove").attr("onclick",'javascript:$(this).parent().parent().parent().remove();');
            $('#customFields2 tr').last().after($(clonerow2).clone());
            $('#customFields2 tbody tr').last().find('span.checkbox').remove();
            $('#customFields2 tbody tr').last().find("input[type='checkbox']").attr('checked',false);
            $('#customFields2 tbody tr').last().find('input[type=checkbox]').each(function(){$(this).checkbox({
                cls : 'checkbox',
                empty : WEB_ADMIN_URL+'img/sprites/forms/checkboxes/empty.png'
            });
            });
            selectid2++;
        });
        if($("#rowId3").attr('id') == 'rowId3'){
            clonerow3 = $("#rowId3").clone();
        }
        $('.addmore3').click(function(){
            clonerow3 = $(clonerow3).removeAttr("id");
            $(clonerow3).find("a").removeClass('hide');
            $(clonerow3).find("input").val('');
            $(clonerow3).find("input").removeAttr("id");
            $(clonerow3).find("select").attr("id",$(clonerow3).find("select").attr("id")+selectid2);
            $(clonerow3).find("#"+$(clonerow3).find("select").attr("id")+"_chzn").remove();
            $(clonerow3).find("select").removeClass('chzn-done');
            $(clonerow3).find("#"+$(clonerow3).find("select").attr("id")+selectid2).removeAttr('style');
            $(clonerow3).find(".fa-remove").removeAttr("style");
            $(clonerow3).find(".fa-remove").attr("style",'color:#ff0000;cursor:pointer;');
            $(clonerow3).find(".fa-remove").attr("onclick",'javascript:$(this).parent().parent().parent().remove();');
            $('#customFields3 tr').last().after($(clonerow3).clone());
            $('#customFields3 tbody tr').last().find('span.checkbox').remove();
            $('#customFields3 tbody tr').last().find("input[type='checkbox']").attr('checked',false);
            $('#customFields3 tbody tr').last().find('input[type=checkbox]').each(function(){$(this).checkbox({
                cls : 'checkbox',
                empty : WEB_ADMIN_URL+'img/sprites/forms/checkboxes/empty.png'
            });
            });
            selectid3++;
        });
    });
    $("#place_inverter").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 8,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#placeinv-file-errors',
        maxFileSize: 100,
    });
    $("#place_battery").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 8,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#placebat-file-errors',
        maxFileSize: 100,
    });
    $("#place_for_ac_distribution_box").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 8,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#placeac-file-errors',
        maxFileSize: 100,
    });
    $("#metering_box").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 8,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#placemb-file-errors',
        maxFileSize: 100,
    });
    $("#take_photographs").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 8,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#takephoto-file-errors',
        maxFileSize: 100,
    });
    $("#electricity_bill").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 8,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#elecbill-file-errors',
        maxFileSize: 100,
    });


    /*  $("#save_note").submit(function(e) {
     alert("hii");
     var form_data = new FormData(this);
     jQuery.ajax({
     url: '<?php //echo URL_HTTP."projects/saveProjectNote"; ?>',
     type: 'POST',
     data:  form_data,
     dataType:  'json',
     mimeType:"multipart/form-data",
     processData: false,
     contentType: false,
     success: function(results)
     {
     if(results.status=='1') {
     // location.reload();
     } else {

     }
     }
     });
     e.preventDefault();
     });*/
    function delete_photo(photo_id)
    {
        if(window.confirm("Are you sure want to delete photo?"))
        {
            jQuery.ajax({
                url: '<?php echo URL_HTTP."projects/delete_survey_image"; ?>',
                type: 'POST',
                data:  {photo_id:photo_id},
                success: function(results)
                {
                    if(results=='1') {
                        $("#photo_"+photo_id).remove();
                        // location.reload();
                    } else {

                    }
                }
            });
        }
    }
</script>
<script type="text/javascript">

    function my_fun()
    {
        var urlName= '<?php echo URL_HTTP . "projects/saveProjectNote"; ?>';
        $("#sitesurvey").submit();

//        var form_data = new FormData(this);
        jQuery.ajax({
            url: '<?php echo URL_HTTP."projects/saveProjectNote"; ?>',
            type: 'POST',
            data:  form_data,
            dataType:  'json',
            mimeType:"multipart/form-data",
            processData: false,
            contentType: false,
            success: function(results)
            {
               // alert("hello");
                if(results.status=='1') {
                    // location.reload();
                } else {

                }
            }
        });
//        e.preventDefault();
    }
</script>
