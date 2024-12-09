<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_3', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="3" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Work Order</u></h4></div>
        <div class="col-md-12">
            <fieldset>
                <legend>Work Order Documents</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Copy of Invoice</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.invoice_copy', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents form-control','id'=>'invoice_copy','templates' => ['inputContainer' => '{{content}}'])); 
                        if(isset($Subsidy->invoice_copy) && !empty($Subsidy->invoice_copy))
                        {
                            $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->invoice_copy;
                            if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->invoice_copy))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/invoice_copy/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">MoU with between Consumer and Installer</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.mou_document', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'mou_document','templates' => ['inputContainer' => '{{content}}'])); ?>
                        
                        <?php 
                        if(isset($Subsidy->mou_document) && !empty($Subsidy->mou_document))
                        {
                            $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->mou_document;
                            if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->mou_document))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/mou_document/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                            }
                        }
                        elseif(!empty($Workorder->attached_doc))
                        {
                            $path = WORKORDER_PATH.$Workorder->project_id.'/'.$Workorder->attached_doc;
                            if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->attached_doc))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/workorder_data/'.encode($Workorder->project_id)."\">View Attached Document</a></strong>";
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
            </fieldset>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <?php if(($is_member == false  || $authority_account==1) && $CanEdit == true && $SpinSubmit==true) { ?>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_3','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_3')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Save & Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_3','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_3')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>