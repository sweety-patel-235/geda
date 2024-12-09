<style>
    .land-file {
        display: flex;
        flex-flow: wrap;
    }

    .land-file>.file-input {
        width: 90% !important;
    }
</style>
<div class="row" style="border-radius:5px; padding-top:10px;">
    <div class="col-md-12">
        <?php echo $this->Form->create($Applications, ['type' => 'file', 'name' => 'applicationform3', 'id' => 'applicationform3']); ?>
        <input type="hidden" name="tab_id" value="3" />
        <input type="hidden" name="app_dev_id" value="<?php echo $app_dev_id; ?>" />
        <fieldset>
            <legend>Project Details</legend>
            <?php if (!empty($ApplicationGeoLocLand)) { ?>
            <div class="col-md-12">
                <div class="col-md-6">
                    <h5>Land details of the propose project</h5>
                </div>
                
            </div>
            <?php } ?>
            <div class="form-group">
                <div class="col-md-12 table-responsive" style="border:1px">
                    <table id="tbl_land_info" class="table table-striped table-bordered table-hover custom-greenhead">
                        <tbody>
                            <?php if (!empty($ApplicationGeoLocLand)) {
                                $encode_application_id = encode($Applications->application_id);
                                foreach ($ApplicationGeoLocLand as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo $key + 1 ?></td>
                                        <td style="text-align: left;">
                                            <?php echo $this->Form->input('app_geo_loc_id[]', ['label' => true,'type' => 'hidden',  'value' => $value['app_geo_loc_id'], 'class' => 'app_geo_loc_id', 'id' => 'app_geo_loc_id_' . $key]); ?>
                                            <div class="row land-details">

                                                <div class="col-md-2">
                                                    <label>Land Category<span class="mendatory_field">*</span></label>
                                                    <?php  $land = $value['type_of_land']=='P'?"1":"2"; ?>
                                                    <?php echo $this->Form->select('land_category[]', $landCategory, array('label' => false, 'value' => $land, 'class' => 'rfibox land_category_cls', 'id' => "land_category_" . $key, 'empty' => 'Select Category', 'disabled' => 'disabled')); ?>

                                                </div>
                                                <div class="col-md-2">
                                                    <label>Plot/Servey No.<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('land_plot_servey_no[]', array('label' => false, 'value' => $value['land_survey_no'], 'class' => 'rfibox land_plot_servey_no_cls', 'placeholder' => 'Land Plot Servey No', 'id' => 'land_plot_servey_no_' . $key , 'disabled' => 'disabled')); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>District<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('land_district[]', $arrDistictData, array('label' => false, 'value' => $value['geo_district'], 'class' => 'rfibox land_district_cls', 'id' => 'land_district_' . $key, 'empty' => 'Select District', 'disabled' => 'disabled')); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Taluka/Village<span class="mendatory_field">*</span></label>
                                                    
                                                    <?php echo $this->Form->select('land_taluka[]', $arrTalukaData, array('label' => false, 'value' => $value['geo_district'], 'class' => 'rfibox land_district_cls', 'id' => 'land_taluka_' . $key, 'empty' => 'Select Taluka', 'disabled' => 'disabled')); ?>
                                                    <?php //echo $this->Form->input('land_taluka[]', array('label' => false, 'value' => $value['geo_taluka'], 'class' => 'rfibox land_taluka_cls', 'placeholder' => 'Taluka/Village', 'id' => 'land_taluka_' . $key)); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>State<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('land_state[]', $arrStateData, array('label' => false, 'value' => 'Gujarat', 'class' => 'rfibox land_state_cls', 'id' => 'land_state_' . $key, 'empty' => 'Select State', 'disabled' => 'disabled')); ?>
                                                    
                                                </div>                                                
                                            </div>
                                            <div class="row land-details">
                                                <div class="col-md-2">
                                                    <label>UTM Easting<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('land_latitude[]', array('label' => false, 'value' => $value['x_cordinate'], 'class' => 'rfibox land_latitude_cls', 'placeholder' => 'Latitude', 'id' => 'land_latitude_' . $key, 'oninput'=>'validateEastingDecimalInput(this)', 'disabled' => 'disabled')); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>UTM Northing<span class="mendatory_field">*</span> </label>
                                                    <?php echo $this->Form->input('land_longitude[]', array('label' => false, 'value' => $value['y_cordinate'], 'class' => 'rfibox land_longitude_cls', 'placeholder' => 'Longitude', 'id' => 'land_longitude_' . $key, 'oninput'=>'validateNorthingDecimalInput(this)', 'disabled' => 'disabled')); ?>
                                                </div>
                                                							
                                                <div class="col-md-2">
                                                    <label>Area of land<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('area_of_land[]', array('label' => false, 'value' => $value['land_area'], 'class' => 'rfibox area_of_land_cls', 'placeholder' => 'Area in acres', 'id' => 'area_of_land_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Deed of Land<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('deed_of_land[]', $deedOfLand, array('label' => false, 'value' => $value['deed_of_land'], 'class' => 'rfibox deed_of_land_cls', 'id' => 'deed_of_land_' . $key, 'empty' => 'Select Deed')); ?>

                                                </div>
                                                <div class="col-md-3 land-file">

                                                    <label>Upload Land Document<span class="mendatory_field">*</span></label>
                                                    <input type="hidden" name="deed_file[]" class='deed_file_cls' id="deed_file_<?php echo $key ?>" value="<?php echo $value['deed_doc'] ?> "/>
                                                    <?php echo $this->Form->input('a_deed_doc[]', array('label' => false, 'div' => false, 'class' => 'rfibox a_deed_doc_cls', 'type' => 'file', 'id' => 'deed_doc_' . $key, 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
                                                    <?php if (!empty($value['deed_doc'])) : ?>
                                                        <?php if ($Couchdb->documentExist($value['app_geo_loc_id'], $value['deed_doc'])) { ?>
                                                        <?php
                                                           
                                                            echo "<strong style='margin: auto;'><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/pl_deed_doc/' . encode($value['app_geo_loc_id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true' style='font-size: 20px;'></i></a></strong>";
                                                        }
                                                        ?>
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                        </td>

                                       
                                    </tr>
                            <?php }
                            } ?>                            
                    </table>
                </div>
            </div>
            <legend>Power Evacuation Details</legend>
            <div class="col-md-12">
                <div class="col-md-10 text-center">
                    <h5>Details of Pooling Substation</h5>
                </div>
                <div class="col-md-2 block" style=" text-align:right;">
                    <input style="margin-right:14px;margin-bottom:5px" class="btn green AddPoolingSubRow" type="button" id="" value="ADD" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_pooling_sub_info" class="table table-striped table-bordered table-hover custom-greenhead">
                        <thead>
                            <tr class="thead-dark">
                                <th scope="col" width="12%" rowspan="2">Name</th>
                                <th scope="col" width="10%" rowspan="2">Distict</th>
                                <th scope="col" width="10%" rowspan="2">Taluka</th>
                                <th scope="col" width="10%" rowspan="2">Village</th>
                                <!-- <th scope="col" width="8%" rowspan="2">Capacity</th> -->
                                <th scope="col" width="7%" rowspan="2">Voltage level of the pooling Substation(kV)</th>
                                <th scope="col" width="16%" colspan="2">Substation Capacity</th>
                                <th scope="col" width="16%" colspan="2">Connected Load</th>
                                <th scope="col" width="8%">Action</th>
                            </tr>
                            <tr class="thead-dark greenhead">
                                <th scope="col" width="8%" style="text-align: center;">MW</th>
                                <th scope="col" width="8%" style="text-align: center;">MVA</th>
                                <th scope="col" width="8%" style="text-align: center;">MW</th>
                                <th scope="col" width="8%" style="text-align: center;">MVA</th>
                                <th scope="col" width="8%" style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($Wind_Pooling_Data)) {
                                foreach ($Wind_Pooling_Data as $key => $value) {
                                    $encode_application_id = encode($Applications->application_id);
                            ?>
                                    <tr>
                                        <?php echo $this->Form->input('id_land', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_pooling', 'id' => 'id_pooling_' . $key]); ?>
                                        <td>
                                            <?php echo $this->Form->input('name_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox name_of_pooling_sub_cls', 'value' => $value['name_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'name_of_pooling_sub_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->select('distict_of_pooling_sub[]', $arrDistictData, array('label' => false, 'class' => 'rfibox distict_of_pooling_sub_cls', 'value' => $value['distict_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'distict_of_pooling_sub_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('taluka_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox taluka_of_pooling_sub_cls', 'value' => $value['taluka_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'taluka_of_pooling_sub_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('village_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox village_of_pooling_sub_cls', 'value' => $value['village_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'village_of_pooling_sub_' . $key)); ?>
                                        </td>
                                        <!-- <td>
                                            <?php //echo $this->Form->input('cap_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox cap_of_pooling_sub_cls', 'value' => $value['cap_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'cap_of_pooling_sub_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td> -->
                                        <td>
                                            <?php echo $this->Form->select('vol_of_pooling_sub[]', $voltageLevel, array('label' => false, 'class' => 'rfibox vol_of_pooling_sub_cls', 'value' => $value['vol_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'vol_of_pooling_sub_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('sub_mw_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox sub_mw_of_pooling_sub_cls', 'value' => $value['sub_mw_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mw_of_pooling_sub_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('sub_mva_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox sub_mva_of_pooling_sub_cls', 'value' => $value['sub_mva_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mva_of_pooling_sub_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('conn_mw_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox conn_mw_of_pooling_sub_cls', 'value' => $value['conn_mw_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'conn_mw_of_pooling_sub_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('conn_mva_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox conn_mva_of_pooling_sub_cls', 'value' => $value['conn_mva_of_pooling_sub'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'conn_mva_of_pooling_sub_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td class="valignTop lastrow">
                                            <?php if ($key != 0) { ?>
                                                <?php if (isset($value['id']) && !empty($value['id'])) { ?>
                                                    <button type="button" id="btn_<?php echo $key ?>" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeGetcoSub('<?php echo $value['id']; ?>','<?php echo $encode_application_id ?>')"><i class="fa fa-trash" aria-hidden="true"></i></button>

                                                <?php } else { ?>
                                                    <button class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowPoolingSub(this)"></button>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td>
                                        <?php echo $this->Form->input('name_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox name_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'name_of_pooling_sub_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->select('distict_of_pooling_sub[]', $arrDistictData, array('label' => false, 'class' => 'rfibox distict_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'distict_of_pooling_sub_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('taluka_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox taluka_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'taluka_of_pooling_sub_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('village_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox village_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'village_of_pooling_sub_0')); ?>
                                    </td>
                                    <!-- <td>
                                        <?php //echo $this->Form->input('cap_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox cap_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'cap_of_pooling_sub_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td> -->
                                    <td>
                                        <?php echo $this->Form->select('vol_of_pooling_sub[]', $voltageLevel, array('label' => false, 'class' => 'rfibox vol_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'vol_of_pooling_sub_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('sub_mw_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox sub_mw_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mw_of_pooling_sub_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('sub_mva_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox sub_mva_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mva_of_pooling_sub_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('conn_mw_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox conn_mw_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'conn_mw_of_pooling_sub_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('conn_mva_of_pooling_sub[]', array('label' => false, 'class' => 'rfibox conn_mva_of_pooling_sub_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'conn_mva_of_pooling_sub_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td class="valignTop lastrow">&nbsp;</td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-10 text-center">
                    <h5>Details of GETCO/PGCIL Substation</h5>
                </div>
                <div class="col-md-2 block" style=" text-align:right;">
                    <input style="margin-right:14px;margin-bottom:5px" class="btn green AddGetcoSubRow" type="button" id="" value="ADD" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_getco_sub_info" class="table table-striped table-bordered table-hover custom-greenhead">
                        <thead>
                            <tr class="thead-dark">
                                <th scope="col" width="15%" rowspan="2">Name</th>
                                <th scope="col" width="15%" rowspan="2">Distict</th>
                                <th scope="col" width="15%" rowspan="2">Taluka</th>
                                <th scope="col" width="15%" rowspan="2">Village</th>
                                <th scope="col" width="8%" rowspan="2">Capacity</th>
                                <th scope="col" width="8%" rowspan="2">Voltage level of the GETCO/PGCIL Substation(kV)</th>
                                <th scope="col" width="8%" colspan="2">GETCO/PGCIL SS Capacity</th>
                                <th scope="col" width="8%">Approved injection capacity</th>
                                <th scope="col" width="8%">Action</th>
                            </tr>
                            <tr class="thead-dark greenhead">
                                <th scope="col" width="8%" style="text-align: center;">MW</th>
                                <th scope="col" width="8%" style="text-align: center;">MVA</th>
                                <th scope="col" width="8%" style="text-align: center;">MW</th>
                                <th scope="col" width="8%" style="text-align: center;"></th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php if (!empty($Wind_Getco_Data)) {
                                foreach ($Wind_Getco_Data as $key => $value) {
                                    $encode_application_id = encode($Applications->application_id);
                            ?>
                                    <tr>
                                        <?php echo $this->Form->input('id_land', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_getco', 'id' => 'id_getco_' . $key]); ?>
                                        <td>
                                            <?php echo $this->Form->input('name_of_getco[]', array('label' => false, 'class' => 'rfibox name_of_getco_cls', 'value' => $value['name_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'name_of_getco_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->select('distict_of_getco[]', $arrDistictData, array('label' => false, 'class' => 'rfibox distict_of_getco_cls', 'value' => $value['distict_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'distict_of_getco_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('taluka_of_getco[]', array('label' => false, 'class' => 'rfibox taluka_of_getco_cls', 'value' => $value['taluka_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'taluka_of_getco_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('village_of_getco[]', array('label' => false, 'class' => 'rfibox village_of_getco_cls', 'value' => $value['village_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'village_of_getco_' . $key)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('cap_of_getco[]', array('label' => false, 'class' => 'rfibox cap_of_getco_cls', 'value' => $value['cap_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'cap_of_getco_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('vol_of_getco[]', array('label' => false, 'class' => 'rfibox vol_of_getco_cls', 'value' => $value['vol_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'vol_of_getco_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('sub_mw_of_getco[]', array('label' => false, 'class' => 'rfibox sub_mw_of_getco_cls', 'value' => $value['sub_mw_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mw_of_getco_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('sub_mva_of_getco[]', array('label' => false, 'class' => 'rfibox sub_mva_of_getco_cls', 'value' => $value['sub_mva_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mva_of_getco_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('conn_mw_of_getco[]', array('label' => false, 'class' => 'rfibox conn_mw_of_getco_cls', 'value' => $value['conn_mw_of_getco'], 'autocomplete' => "false", 'type' => 'text', 'id' => 'conn_mw_of_getco_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                        </td>
                                        <td class="valignTop lastrow">
                                            <?php if ($key != 0) { ?>
                                                <?php if (isset($value['id']) && !empty($value['id'])) { ?>
                                                    <button type="button" id="btn_<?php echo $key ?>" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeGetcoSub('<?php echo $value['id']; ?>','<?php echo $encode_application_id ?>')"><i class="fa fa-trash" aria-hidden="true"></i></button>

                                                <?php } else { ?>
                                                    <button class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowGetcoSub(this)"></button>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td>
                                        <?php echo $this->Form->input('name_of_getco[]', array('label' => false, 'class' => 'rfibox name_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'name_of_getco_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->select('distict_of_getco[]', $arrDistictData, array('label' => false, 'class' => 'rfibox distict_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'distict_of_getco_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('taluka_of_getco[]', array('label' => false, 'class' => 'rfibox taluka_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'taluka_of_getco_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('village_of_getco[]', array('label' => false, 'class' => 'rfibox village_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'village_of_getco_0')); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('cap_of_getco[]', array('label' => false, 'class' => 'rfibox cap_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'cap_of_getco_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('vol_of_getco[]', array('label' => false, 'class' => 'rfibox vol_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'vol_of_getco_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('sub_mw_of_getco[]', array('label' => false, 'class' => 'rfibox sub_mw_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mw_of_getco_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('sub_mva_of_getco[]', array('label' => false, 'class' => 'rfibox sub_mva_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'sub_mva_of_getco_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('conn_mw_of_getco[]', array('label' => false, 'class' => 'rfibox conn_mw_of_getco_cls', 'autocomplete' => "false", 'type' => 'text', 'id' => 'conn_mw_of_getco_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                    </td>
                                    <td class="valignTop lastrow">&nbsp;</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4">
                        <label>Upload permission letter GETCO/PGCIL/DISCOM<span class="mendatory_field">*</span><i data-content="Upload permission letter of the GETCO/PGCIL/DISCOM's with all annexures" class="fa fa-info-circle"></i></label>
                        <?php echo $this->Form->input('a_permission_letter_of_getco', array('label' => false, 'div' => false, 'type' => 'file', 'id' => 'permission_letter_of_getco', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
                        <?php if (!empty($Applications->permission_letter_of_getco)) : ?>
                            <?php if ($Couchdb->documentExist($Applications->id, $Applications->permission_letter_of_getco)) { ?>
                            <?php

                                echo "<strong><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_permission_letter_of_getco/' . encode($Applications->id) . "\">View Electricity Bill</a></strong>";
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label>Reference No.<span class="mendatory_field">*</span></label>
                        <?php echo $this->Form->input('permission_lett_ref_no', array('label' => false, 'class' => 'form-control', 'id' => 'permission_lett_ref_no', 'type' => 'text')); ?>
                    </div>
                    <div class="col-md-4">
                        <label>Date of Permission and Validity<span class="mendatory_field">*</span></label>
                        <?php echo $this->Form->input('dt_of_per_validity', array('type' => 'text', 'label' => false, 'class' => 'form-control datepicker', 'id' => 'dt_of_per_validity')); ?>
                    </div>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="col-md-1">
                    <?php echo $this->Form->input('Save', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'save_3', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'save_3')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $this->Form->input('Save & Next', array('label' => false, 'class' => 'next btn btn-primary btn-lg mb-xlg cbtnsendmsg', 'name' => 'next_3', 'type' => 'submit', 'placeholder' => 'Disclaimer', 'id' => 'next_3')); ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>