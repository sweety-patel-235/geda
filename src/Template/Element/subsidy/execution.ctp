<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_5', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="5" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Execution and Completion Details</u></h4></div>
        <div class="col-md-12">
            <fieldset>
                <legend>Project Geo-Location Details</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-2">Latitude</div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Subsidy.latitude', array('label' => false,'div' => false,'type'=>'text','id'=>'latitude','class'=>'form-control','placeholder'=>'Latitude')); ?>
                    </div>
                    <div class="label-heading col-md-2">Longitude</div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Subsidy.longitude', array('label' => false,'div' => false,'type'=>'text','id'=>'longitude','class'=>'form-control','placeholder'=>'Longitude')); ?>
                    </div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">
                <fieldset>
                    <legend>Bi-directional Meter Details</legend>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Date</div>
                        <div class="col-md-7">
                            <?php echo $this->Form->input('Subsidy.bidirectional_meter_date', array('label' => false,'div' => false,'type'=>'text','id'=>'bidirectional_meter_date','class'=>'form-control datepicker','placeholder'=>'Date of Installation')); ?>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Manufacture</div>
                        <div class="col-md-7">
                            <?php echo $this->Form->input('Subsidy.bidirectional_manufacture_name', array('label' => false,'div' => false,'type'=>'text','id'=>'bidirectional_manufacture_name','class'=>'form-control','placeholder'=>'Meter Manufacture Name')); ?>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Serial No.</div>
                        <div class="col-md-7">
                            <?php echo $this->Form->input('Subsidy.bidirectional_serial_no', array('label' => false,'div' => false,'type'=>'text','id'=>'bidirectional_serial_no','class'=>'form-control','placeholder'=>'Meter Serial No')); ?>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset>
                    <legend>Solar Meter Details</legend>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Manufacture</div>
                        <div class="col-md-7">
                            <?php echo $this->Form->input('Subsidy.solar_manufacture_name', array('label' => false,'div' => false,'type'=>'text','id'=>'solar_manufacture_name','class'=>'form-control','placeholder'=>'Meter Manufacture Name')); ?>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Serial No.</div>
                        <div class="col-md-7">
                            <?php echo $this->Form->input('Subsidy.solar_serial_no', array('label' => false,'div' => false,'type'=>'text','id'=>'solar_serial_no','class'=>'form-control','placeholder'=>'Meter Serial No')); ?>
                        </div>
                        <div class="col-md-7">&nbsp;</div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="col-md-12">
            <fieldset>
                <legend>Meter Installation Documents</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">DisCom Bidirection Meter Installation Sheet / Meter Sealing Report</div>
                    <div class="col-md-5">
                        <div>
                            <?php echo $this->Form->input('Subsidy.bidirectional_installation_sheet', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'bidirectional_installation_sheet','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->bidirectional_installation_sheet) && !empty($Subsidy->bidirectional_installation_sheet))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->bidirectional_installation_sheet;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->bidirectional_installation_sheet))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/bidirectional_installation_sheet/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Certificate of Bi-directional Meter Installation and Consent for Subsidy</div>
                    <div class="col-md-5">
                        <div>
                            <?php echo $this->Form->input('Subsidy.bidirectional_meter_certification', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'bidirectional_meter_certification','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->bidirectional_meter_certification) && !empty($Subsidy->bidirectional_meter_certification))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->bidirectional_meter_certification;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->bidirectional_meter_certification))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/bidirectional_meter_certification/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12 hide">
                    <div class="label-heading col-md-3">Meter Sealing Report</div>
                    <div class="col-md-5">
                       <div>
                            <?php echo $this->Form->input('Subsidy.meter_sealing_report', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'meter_sealing_report','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->meter_sealing_report) && !empty($Subsidy->meter_sealing_report))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->meter_sealing_report;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->meter_sealing_report))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/meter_sealing_report/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
            </fieldset>
        </div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="col-md-12">
            <fieldset>
                <legend>GRID CONNECTIVITY LEVEL DETAILS</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-2">Arrangement</div>
                    <div class="col-md-2">
                        <?php echo $this->Form->select('Subsidy.arrangement',array('1'=>'Net Metering','2'=>'Gross Metering','3'=>'Captive Consumption'),array('label' => false,'class'=>'form-control','id'=>'arrangement'));?></div>
                    <div class="label-heading col-md-2">Phase</div>
                    <div class="col-md-2">
                        <?php 
                            echo $this->Form->select('Subsidy.grid_level_phase',array('1'=>'Single Phase','3'=>'3 Phase'),array('label' => false,'class'=>'form-control','id'=>'grid_level_phase','placeholder'=>'Select Phase'));
                        ?>
                    </div>
                    <div class="label-heading col-md-2">Voltage</div>
                    <div class="col-md-2">
                        <?php 
                            echo $this->Form->select('Subsidy.grid_level_voltage',array('230'=>'230','415'=>'415','11000'=>'11000','33000'=>'33000','66000'=>'66000'),array('label' => false,'class'=>'form-control','type'=>'text','id'=>'grid_level_voltage','placeholder'=>'Enter Voltage'));
                        ?>
                    </div>
                </div>
            </fieldset>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <?php if(($is_member == false  || $authority_account==1) && $CanEdit == true && $SpinSubmit==true) { ?>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_5','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_5')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Save & Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_5','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_5')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>