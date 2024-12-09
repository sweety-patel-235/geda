<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_2', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="2" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Identity Proof Documents</u></h4></div>
        <div class="col-md-12">
            <fieldset>
                <legend>Personal Details</legend>
                <?php if (!empty($ApplyOnlines->aadhar_no_or_pan_card_no)) {?>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Aadhaar No.</div>
                    <div class="col-md-2">
                        <?php echo $this->Form->input('Subsidy.aadhar_no', array('label' => false,'div' => false,'type'=>'text','id'=>'aadhar_no','class'=>'form-control','placeholder'=>'Aadhaar No')); ?>
                        <?php //echo passdecrypt($ApplyOnlines->aadhar_no_or_pan_card_no);?>
                    </div>
                    <div class="col-md-7">
                        <?php 
                        if(!empty($ApplyOnlines->attach_photo_scan_of_aadhar) && empty($Subsidy->aadhar_card)) {
                                if ($Couchdb->documentExist($Subsidy->application_id,$ApplyOnlines->attach_photo_scan_of_aadhar)) {
                                    $file_aadhar_size = filesize($DOCUMENT_PATH.$ApplyOnlines->attach_photo_scan_of_aadhar)/1000;
                                    if($file_aadhar_size>200)
                                    {
                                        echo '<span style="font-size:16px;color:#ffcc29;"><strong>Attached file size is of '.$file_aadhar_size.' KB. Kindly upload a file of max. size 200 KB in .pdf format.</strong></span><br/>';
                                    }
                                    $file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->attach_photo_scan_of_aadhar,PATHINFO_EXTENSION);
                                    if (in_array($file_ext,$IMAGE_EXT)) {
                                        /* Set the width fixed at 200px; */
                                        echo '<span style="font-size:16px;color:#ffcc29;"><strong>Attached file extension is '.$file_ext.'. Require file size is 200 KB of .pdf file.</strong></span><br/>';
                                        $width = 200;
                                         
                                        /* Get the image info */
                                        $info = getimagesize($DOCUMENT_PATH.$ApplyOnlines->attach_photo_scan_of_aadhar);
                                         
                                        /* Calculate aspect ratio by dividing height by width */
                                        $aspectRatio = $info[1] / $info[0];
                                         
                                        /* Keep the width fix at 100px, 
                                           but change the height according to the aspect ratio */
                                        $newHeight = (int)($aspectRatio * $width) . "px";
                                         
                                        /* Use the 'newHeight' in the CSS height property below. */
                                        $width .= "px";
                                        echo "<img style=\"width: $width; height: $newHeight;\" src=\"".URL_HTTP.'app-docs/attach_photo_scan_of_aadhar/'.encode($ApplyOnlines->id)."\"/>";
                                    } else {
                                        echo "<strong><a target=\"_AADHAR\" href=\"".URL_HTTP.'app-docs/attach_photo_scan_of_aadhar/'.encode($ApplyOnlines->id)."\">View Aadhar Card/ Other ID Card</a></strong>";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
                <?php } 
                else if (!empty($ApplyOnlines->pan_card_no)) {?>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">PAN</div>
                    <div class="col-md-2">
                        <?php echo $this->Form->input('Subsidy.aadhar_no', array('label' => false,'div' => false,'type'=>'text','id'=>'aadhar_no','class'=>'form-control','placeholder'=>'Pan No')); ?>
                        <?php //echo passdecrypt($ApplyOnlines->pan_card_no);?></div>
                    <div class="col-md-7">
                        <?php 
                        if(!empty($ApplyOnlines->attach_pan_card_scan)) {
                                if ($Couchdb->documentExist($Subsidy->application_id,$ApplyOnlines->attach_pan_card_scan)) {
                                    $file_pan_size = filesize($DOCUMENT_PATH.$ApplyOnlines->attach_pan_card_scan)/1000;
                                    if($file_pan_size>200)
                                    {
                                        echo '<span style="font-size:16px;color:#ffcc29;"><strong>Attached file size is of '.$file_pan_size.' KB. Kindly upload a file of max. size 200 KB in .pdf format.</strong></span><br/>';
                                    }
                                    $file_ext = pathinfo($DOCUMENT_PATH.$ApplyOnlines->attach_pan_card_scan,PATHINFO_EXTENSION);
                                    if (in_array($file_ext,$IMAGE_EXT)) {
                                        echo '<span style="font-size:16px;color:#ffcc29;"><strong>Attached file extension is '.$file_ext.'. Require file size is 200 KB of .pdf file.</strong></span><br/>';
                                        /* Set the width fixed at 200px; */
                                        $width = 200;
                                         
                                        /* Get the image info */
                                        $info = getimagesize($DOCUMENT_PATH.$ApplyOnlines->attach_pan_card_scan);
                                         
                                        /* Calculate aspect ratio by dividing height by width */
                                        $aspectRatio = $info[1] / $info[0];
                                         
                                        /* Keep the width fix at 100px, 
                                           but change the height according to the aspect ratio */
                                        $newHeight = (int)($aspectRatio * $width) . "px";
                                         
                                        /* Use the 'newHeight' in the CSS height property below. */
                                        $width .= "px";

                                        echo "<img style=\"width: $width; height: $newHeight;\" src=\"".URL_HTTP.'app-docs/attach_pan_card_scan/'.encode($ApplyOnlines->id)."\"/>";
                                    } else {
                                         echo "<strong><a target=\"_AADHAR\" href=\"".URL_HTTP.'app-docs/attach_pan_card_scan/'.encode($ApplyOnlines->id)."\">View Pan Card/ Other ID Card</a></strong>"
                                       ;
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
                <?php }
                $str_attached       = 'Aadhaar';
                if($ApplyOnlinesTable->category_residental != $ApplyOnlines->category)
                {
                    $str_attached   = 'Pan';
                }
                ?>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Upload <?php echo $str_attached;?> Card</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.aadhar_card', array('label' => false,'div' => false,'type'=>'file','id'=>'aadhar_card','templates' => ['inputContainer' => '{{content}}'])); 
                        if(isset($Subsidy->aadhar_card) && !empty($Subsidy->aadhar_card))
                        {
                            $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->aadhar_card;
                            if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->aadhar_card))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/aadhar_card/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row"><div class="col-md-12"><?php
                if(!empty($ApplyOnlines->attach_recent_bill) && empty($Subsidy->recent_bill)) {
                    if ($Couchdb->documentExist($Subsidy->application_id,$ApplyOnlines->attach_recent_bill)) {
                        $file_bill_size = filesize($DOCUMENT_PATH.$ApplyOnlines->attach_recent_bill)/1000;
                        $file_ext       = explode(".", $ApplyOnlines->attach_recent_bill);
                        if($file_bill_size>1024)
                        {
                            echo '<span style="font-size:16px;color:#ffcc29;"><strong>Attached file size is of '.$file_bill_size.' KB. Kindly upload a file of max. size 1024 KB in .pdf format.</strong></span><br/>';
                        }
                        if(strtolower($file_ext[1])!='pdf')
                        {
                            echo '<span style="font-size:16px;color:#ffcc29;"><strong>Attached file extension is of '.$file_ext[1].'. Kindly upload a file of max. size 1024 KB in .pdf format.</strong></span><br/>';
                        }
                    }
                }
                ?>
                </div></div>
                
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Attached Recent Bill</div>
                    <div class="col-md-5">
                        <?php echo $this->Form->input('Subsidy.recent_bill', array('label' => false,'div' => false,'type'=>'file','id'=>'recent_bill','class'=>'subsidy-documents','templates' => ['inputContainer' => '{{content}}'])); 
                        if(isset($Subsidy->recent_bill) && !empty($Subsidy->recent_bill))
                        {
                            $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->recent_bill;
                            if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->recent_bill))
                            {
                                echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/recent_bill/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
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
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_2','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_2')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Save & Next', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_2','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_2')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>