<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_4', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="4" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Chief Electrical Inspector Office Documents</u></h4></div>
        <div class="col-md-12">
            <fieldset>
                <legend>CEI Documents & Details</legend>
                <?php
                if(isset($Fesibility->recommended_capacity_by_discom) && $Fesibility->recommended_capacity_by_discom > '10')
                {
                    ?>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">CEI Approval (For PV system above 10 kW)</div>
                        <div class="col-md-5">
                            <?php echo $this->Form->input('Subsidy.cei_approval_doc', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'cei_approval_doc','templates' => ['inputContainer' => '{{content}}']));
                            if(isset($Subsidy->cei_approval_doc) && !empty($Subsidy->cei_approval_doc))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->cei_approval_doc;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->cei_approval_doc))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/cei_approval_doc/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>

                        </div>
                        <div class="col-md-4">&nbsp;</div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">CEI Inspection (For PV system above 10 kW)</div>
                        <div class="col-md-5">
                            <?php echo $this->Form->input('Subsidy.cei_inspection_doc', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'cei_inspection_doc','templates' => ['inputContainer' => '{{content}}'])); 
                            if(isset($Subsidy->cei_inspection_doc) && !empty($Subsidy->cei_inspection_doc))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->cei_inspection_doc;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->cei_inspection_doc))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/cei_inspection_doc/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                            ?>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <?php
                }
                else
                {
                    ?>

                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Self Certification (For PV system below 10 kW)</div>
                        <div class="col-md-5">
                            <div class="">
                                <?php 
                                echo $this->Form->input('Subsidy.cei_self_certification', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'cei_self_certification','templates' => ['inputContainer' => '{{content}}'])); 

                                if(isset($Subsidy->cei_self_certification) && !empty($Subsidy->cei_self_certification))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->cei_self_certification;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->cei_self_certification))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/cei_self_certification/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                                elseif(!empty($Self_Certificate->file_name)) {
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Self_Certificate->file_name)) {
                                       /* $file_ext = pathinfo($DOCUMENT_PATH.$Self_Certificate->file_name,PATHINFO_EXTENSION);
                                        if (in_array($file_ext,$IMAGE_EXT)) {
                                           //  Set the width fixed at 200px; 
                                            $width = 200;
                                             
                                            // Get the image info 
                                            $info = getimagesize($DOCUMENT_PATH.$Self_Certificate->file_name);
                                             
                                            //Calculate aspect ratio by dividing height by width 
                                            $aspectRatio = $info[1] / $info[0];
                                             
                                            // Keep the width fix at 100px, 
                                             //  but change the height according to the aspect ratio 
                                            $newHeight = (int)($aspectRatio * $width) . "px";
                                             
                                            // Use the 'newHeight' in the CSS height property below. 
                                            $width .= "px";
    
                                            echo "<img style=\"width: $width; height: $newHeight;\" src=\"".URL_HTTP.'app-docs/Self_Certificate/'.encode($Self_Certificate->id)."\"/>";

                                        } else {*/
                                            echo "<strong><a target=\"_SELF_CERTIFICATION\" href=\"".URL_HTTP.'app-docs/Self_Certificate/'.encode($Self_Certificate->id)."\">View Self Certification</a></strong>";
                                        //}
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            &nbsp;
                        </div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <?php
                }
                ?>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Name of Electrical Licenced Contractor</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.cei_contractor', array('label' => false,'div' => false,'type'=>'text','id'=>'cei_contractor','class'=>'form-control','placeholder'=>'Name of Electrical Licenced Contractor')); ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Name of Authorised Superviser</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.cei_superviser', array('label' => false,'div' => false,'type'=>'text','id'=>'cei_superviser','class'=>'form-control','placeholder'=>'Name of authorised superviser')); ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Licence No</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.cei_licence_no', array('label' => false,'div' => false,'type'=>'text','id'=>'cei_licence_no','class'=>'form-control','placeholder'=>'Licence No')); ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Authorised Person for Signing</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.cei_authorised_by', array('label' => false,'div' => false,'type'=>'text','id'=>'cei_authorised_by','class'=>'form-control','placeholder'=>'Authorised Person Name')); ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Licence valid up to</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.cei_licence_expiry_date', array('label' => false,'div' => false,'type'=>'text','id'=>'cei_licence_expiry_date','class'=>'form-control datepicker','placeholder'=>'Licence Expiry Date')); ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <?php
                if(isset($Fesibility->recommended_capacity_by_discom) && $Fesibility->recommended_capacity_by_discom <= '10')
                {
                    ?>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-3">Date of self certification</div>
                        <div class="col-md-5">
                            <?php echo $this->Form->input('Subsidy.cei_self_certification_date', array('label' => false,'div' => false,'type'=>'text','id'=>'cei_self_certification_date','class'=>'form-control datepicker','placeholder'=>'Date of self certification')); ?>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                    </div>
                    <?php
                }
                ?>
            </fieldset>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <?php if(($is_member == false || $authority_account==1) && $CanEdit == true && $SpinSubmit==true) { ?>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_4','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_4')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Save & Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_4','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_4')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>