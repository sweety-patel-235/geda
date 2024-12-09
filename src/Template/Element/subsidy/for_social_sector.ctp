<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_7', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="7" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Additional Document For Social Sector</u></h4></div>
        <div class="col-md-12">
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Photo ID Proof of Signing Authority</div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.signing_authority', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'signing_authority','templates' => ['inputContainer' => '{{content}}']));
                            if(isset($Subsidy->signing_authority) && !empty($Subsidy->signing_authority))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->signing_authority;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->signing_authority))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/signing_authority/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Charity Commissioner Certificate </div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.charity_certificate', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'charity_certificate','templates' => ['inputContainer' => '{{content}}']));
                            if(isset($Subsidy->charity_certificate) && !empty($Subsidy->charity_certificate))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->charity_certificate;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->charity_certificate))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/charity_certificate/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Signing Authority Letter </div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.authority_letter', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'authority_letter','templates' => ['inputContainer' => '{{content}}']));
                            if(isset($Subsidy->authority_letter) && !empty($Subsidy->authority_letter))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->authority_letter;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->authority_letter))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/authority_letter/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Form B </div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.formb', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'formb','templates' => ['inputContainer' => '{{content}}']));
                            if(isset($Subsidy->formb) && !empty($Subsidy->formb))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->formb;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->formb))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/formb/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Form C </div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.formc', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'formc','templates' => ['inputContainer' => '{{content}}'])); 
                            if(isset($Subsidy->formc) && !empty($Subsidy->formc))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->formc;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->formc))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/formc/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Affidavit / Declaration Certificate from Developer /EPC on Rs. 100  Stamp</div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.affidavit', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'affidavit','templates' => ['inputContainer' => '{{content}}']));
                            if(isset($Subsidy->affidavit) && !empty($Subsidy->affidavit))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->affidavit;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->affidavit))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/affidavit/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row col-md-12">
            	<div class="label-heading col-md-4">Agreement / Consent / Certificate from User / beneficiary on  Rs. 100 Stamp</div>
                <div class="col-md-3"><div class="">
                        <?php echo $this->Form->input('Subsidy.agreement_stamp', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'agreement_stamp','templates' => ['inputContainer' => '{{content}}'])); 
                            if(isset($Subsidy->agreement_stamp) && !empty($Subsidy->agreement_stamp))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->agreement_stamp;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->agreement_stamp))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/agreement_stamp/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
        </div>
        <div class="col-md-12">
            <fieldset>
                <legend>Bill of Material</legend>
                <div class="row col-md-12">
                	<div class="label-heading col-md-4">Excel</div>
                    <div class="col-md-3"><div class="">
                            <?php echo $this->Form->input('Subsidy.social_excel', array('label' => false,'div' => false,'type'=>'file','class'=>'','id'=>'social_excel','templates' => ['inputContainer' => '{{content}}']));
                                if(isset($Subsidy->social_excel) && !empty($Subsidy->social_excel))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->social_excel;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->social_excel))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/social_excel/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                	<div class="label-heading col-md-4">PDF</div>
                    <div class="col-md-3"><div class="">
                            <?php echo $this->Form->input('Subsidy.social_pdf', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'social_pdf','templates' => ['inputContainer' => '{{content}}']));
                                if(isset($Subsidy->social_pdf) && !empty($Subsidy->social_pdf))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->social_pdf;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->social_pdf))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/social_pdf/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
            </fieldset>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <?php if(($is_member == false  || $authority_account==1) && $CanEdit == true && $SpinSubmit==true) { ?>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_7','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_7')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Save & Submit', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_7','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_7')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>