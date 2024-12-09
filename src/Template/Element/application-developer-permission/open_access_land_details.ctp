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
        <fieldset>
            <legend>Land Details</legend>

            <div class="col-md-12">
                <div class="col-md-6">
                    <h4>Land details of the propose project</h4>
                </div>
                <div class="col-md-6 block" style=" text-align:right;">
                    <input style="margin-right:14px;" class="btn green AddLandRow" type="button" id="" value="ADD" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 table-responsive" style="border:1px">
                    <table id="tbl_land_info" class="table table-striped table-bordered table-hover custom-greenhead">
                        <tbody>
                            <?php if (!empty($Open_Access_Land_Data)) {
                                foreach ($Open_Access_Land_Data as $key => $value) {
                                    $encode_application_id = encode($Applications->application_id);
                            ?>
                                    <tr>
                                        <td><?php echo $key + 1 ?></td>
                                        <td style="text-align: left;">
                                            <?php echo $this->Form->input('id_land', ['label' => true, 'type' => 'hidden', 'value' => $value['id'], 'class' => 'id_land', 'id' => 'id_land_' . $key]); ?>
                                            <div class="row land-details">

                                                <div class="col-md-2">
                                                    <label>Land Category<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('land_category[]', $landCategory, array('label' => false, 'value' => $value['land_category'], 'class' => 'rfibox land_category_cls', 'id' => "land_category_" . $key, 'empty' => 'Select Category')); ?>

                                                </div>
                                                <div class="col-md-2">
                                                    <label>Plot/Servey No.<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('land_plot_servey_no[]', array('label' => false, 'value' => $value['land_plot_servey_no'], 'class' => 'rfibox land_plot_servey_no_cls', 'placeholder' => 'Land Plot Servey No', 'id' => 'land_plot_servey_no_' . $key)); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Taluka/Village<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('land_taluka[]', array('label' => false, 'value' => $value['land_taluka'], 'class' => 'rfibox land_taluka_cls', 'placeholder' => 'Taluka/Village', 'id' => 'land_taluka_' . $key)); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>State<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('land_state[]', $arrStateData, array('label' => false, 'value' => $value['land_state'], 'class' => 'rfibox land_state_cls', 'id' => 'land_state_' . $key, 'empty' => 'Select State', 'disabled' => 'disabled')); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>District<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('land_district[]', $arrDistictData, array('label' => false, 'value' => $value['land_district'], 'class' => 'rfibox land_district_cls', 'id' => 'land_district_' . $key, 'empty' => 'Select District')); ?>
                                                </div>
                                            </div>
                                            <div class="row land-details">
                                                <div class="col-md-2">
                                                    <label>Latitude<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('land_latitude[]', array('label' => false, 'value' => $value['land_latitude'], 'class' => 'rfibox land_latitude_cls', 'placeholder' => 'Latitude', 'id' => 'land_latitude_' . $key, 'onkeypress' => "return validateDecimal(event)")); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Longitude<span class="mendatory_field">*</span> </label>
                                                    <?php echo $this->Form->input('land_longitude[]', array('label' => false, 'value' => $value['land_longitude'], 'class' => 'rfibox land_longitude_cls', 'placeholder' => 'Longitude', 'id' => 'land_longitude_' . $key, 'onkeypress' => "return validateDecimal(event)")); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Area of land<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->input('area_of_land[]', array('label' => false, 'value' => $value['area_of_land'], 'class' => 'rfibox area_of_land_cls', 'placeholder' => 'Area in acres', 'id' => 'area_of_land_' . $key, 'onkeypress' => "return validateNumber(event)")); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Deed of Land<span class="mendatory_field">*</span></label>
                                                    <?php echo $this->Form->select('deed_of_land[]', $deedOfLand, array('label' => false, 'value' => $value['deed_of_land'], 'class' => 'rfibox deed_of_land_cls', 'id' => 'deed_of_land_' . $key, 'empty' => 'Select Deed')); ?>

                                                </div>
                                                <div class="col-md-3 land-file">
                                                    
                                                    <label>Upload Land Document<span class="mendatory_field">*</span></label>
                                                    <input type="hidden" name="deed_file" class ='deed_file_cls' id="deed_file_<?php echo $key ?>" value=<?php echo $value['deed_doc']?>>
                                                    <?php echo $this->Form->input('a_deed_doc_' . $key, array('label' => false, 'div' => false, 'class' => 'rfibox a_deed_doc_cls', 'type' => 'file', 'id' => 'deed_doc_' . $key, 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
                                                    <?php if (!empty($value['deed_doc'])) : ?>
                                                        <?php if ($Couchdb->documentExist($value['app_dev_per_id'], $value['deed_doc'])) { ?>
                                                        <?php
                                                            echo "<strong style='margin: auto;'><a target=\"_blank\" href=\"" . URL_HTTP . 'app-docs/p_deed_doc/' . encode($value['app_dev_per_id']) . "\"><i class='fa fa-file-pdf-o' aria-hidden='true' style='font-size: 20px;'></i></a></strong>";
                                                        }
                                                        ?>
                                                    <?php endif; ?>                                                    
                                                </div>        

                                            </div>
                                            <div id='deed_doc_<?php echo $key?>-file-errors' class="deed_doc_error_cls"></div>
                                        </td>

                                        <td class="valignTop lastrow"> 
                                            <?php if ($key != 0) { ?>
                                                <?php if (isset($value['id']) && !empty($value['id'])) { ?>
                                                    <button type="button" id="btn_<?php echo $key ?>" style="background-color: #307fe2; color:#ffffff;" class="btn btn-secondary" onclick="javascript:removeLand('<?php echo $value['id']; ?>','<?php echo $encode_application_id ?>')"><i class="fa fa-trash" aria-hidden="true"></i></button>

                                                <?php } else { ?>
                                                    <button class="btn btn-secondary" style="background-color: #307fe2; color:#ffffff;" type="button" id="" onclick="deleteRowLand(this)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td>1</td>
                                    <td style="text-align: left;">
                                        <div class="row land-details">

                                            <div class="col-md-2">
                                                <label>Land Category<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->select('land_category[]', $landCategory, array('label' => false, 'class' => 'rfibox land_category_cls', 'id' => 'land_category_0', 'empty' => 'Select Category')); ?>

                                            </div>
                                            <div class="col-md-2">
                                                <label>Plot/Servey No.<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->input('land_plot_servey_no[]', array('label' => false, 'class' => 'rfibox land_plot_servey_no_cls', 'placeholder' => 'Land Plot Servey No', 'id' => 'land_plot_servey_no_0')); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Taluka/Village<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->input('land_taluka[]', array('label' => false, 'class' => 'rfibox land_taluka_cls', 'placeholder' => 'Taluka/Village', 'id' => 'land_taluka_0')); ?>
                                            </div>

                                            <div class="col-md-3">
                                                <label>State<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->select('land_state[]', $arrStateData, array('label' => false, 'class' => 'rfibox land_state_cls', 'id' => 'land_state_0', 'empty' => 'Select State', 'disabled' => 'disabled')); ?>
                                            </div>

                                            <div class="col-md-3">
                                                <label>District<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->select('land_district[]', $arrDistictData, array('label' => false, 'class' => 'rfibox land_district_cls', 'id' => 'land_district_0', 'empty' => 'Select District')); ?>
                                            </div>
                                        </div>
                                        <div class="row land-details">
                                            <div class="col-md-2">
                                                <label>Latitude<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->input('land_latitude[]', array('label' => false, 'class' => 'rfibox land_latitude_cls', 'placeholder' => 'Latitude', 'id' => 'land_latitude_0', 'onkeypress' => "return validateDecimal(event)")); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Longitude<span class="mendatory_field">*</span> </label>
                                                <?php echo $this->Form->input('land_longitude[]', array('label' => false, 'class' => 'rfibox land_longitude_cls', 'placeholder' => 'Longitude', 'id' => 'land_longitude_0', 'onkeypress' => "return validateDecimal(event)")); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Area of land<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->input('area_of_land[]', array('label' => false, 'class' => 'rfibox area_of_land_cls', 'placeholder' => 'Area in acres', 'id' => 'area_of_land_0', 'onkeypress' => "return validateNumber(event)")); ?>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Deed of Land<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->select('deed_of_land[]', $deedOfLand, array('label' => false, 'class' => 'rfibox deed_of_land_cls', 'id' => 'deed_of_land_0', 'empty' => 'Select Deed')); ?>

                                            </div>
                                            <div class="col-md-3 land-file">
                                                <label>Upload Land Document<span class="mendatory_field">*</span></label>
                                                <?php echo $this->Form->input('a_deed_doc_0', array('label' => false, 'div' => false, 'class' => 'rfibox a_deed_doc_cls', 'type' => 'file', 'id' => 'deed_doc_0', 'templates' => ['inputContainer' => '{{content}}'], 'accept' => '.pdf')); ?>
                                            </div>
                                            
                                        </div>
                                        <div id='deed_doc_0-file-errors' class="deed_doc_error_cls"></div>
                                    </td>
                                    <td class="valignTop lastrow">&nbsp;</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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