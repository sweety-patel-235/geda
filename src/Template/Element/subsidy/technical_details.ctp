<?php echo $this->Form->create($Subsidy,array('type'=>'file','class'=>'form-horizontal form_submit','id'=>'subsidyform_6', 'url' => '/subsidy/'.$application_id,'autocomplete'=>'off')); ?>
    <input type="hidden" name="tab_id" value="6" />
    <div class="form-group">
        <div class="col-md-12"><h4><u>Technical Details</u></h4></div>
        <div class="col-md-12">
            <fieldset>
                <legend>Modules</legend>
                <div class="row col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Capacity/Power (Wp)</th>
                                <th scope="col" class="text-center">Make</th>
                                <th scope="col" class="text-center">No. of Modules</th>
                                <th scope="col" class="text-center">Type of Modules</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                       // $modules_data     = isset($ProjectExecutionData->modules_data) ? unserialize($ProjectExecutionData->modules_data) : unserialize($Subsidy->modules_data);
                        $modules_data     = isset($Subsidy->modules_data) ? unserialize($Subsidy->modules_data) : '';
                        $total_commulative= 0;
                        for($i=1;$i<=3;$i++)
                        {
                            $row            = $i-1;
                            $m_capacity     = '';
                            $m_make         = '';
                            $m_modules      = '';
                            $m_type_modules = '';
                            $m_type_other   = '';
                            if (isset($modules_data[$row])) 
                            {
                                $m_capacity         = $modules_data[$row]['m_capacity'];
                                $m_make             = $modules_data[$row]['m_make'];
                                $m_modules          = $modules_data[$row]['m_modules'];
                                $m_type_modules     = $modules_data[$row]['m_type_modules'];
                                $m_type_other       = $modules_data[$row]['m_type_other'];
                                $total_commulative  = $total_commulative + (floatval($modules_data[$row]['m_capacity']) * floatval($modules_data[$row]['m_modules']));
                            }
                            $error_class_for_name_prefix  = '';
                            $error_text                   = '';
                            if(isset($SubsidyErrors['m_capacity']) && isset($SubsidyErrors['m_capacity']['_empty']) && !empty($SubsidyErrors['m_capacity']['_empty']) && $i==1)
                            { 
                                $error_class_for_name_prefix ='has-error'; 
                                $error_text = $SubsidyErrors['m_capacity']['_empty'];
                            } 
                        ?>
                            <tr>
                                <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('Subsidy.m_capacity][',["type" => "text",'onkeypress'=>"return validateDec(event)",'label' => false,"id"=>"m_capacity_".$i,"class" => "c1 form-control ".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_capacity]); 
                                if($error_text!='')
                                {
                                    echo '<div class="help-block">'.$error_text.'</div>';
                                }
                                ?>
                                </td>
                                <td style="vertical-align: top !important;"><?php echo $this->Form->input('Subsidy.m_make][',["type" => "text",'label' => false,"class" => "form-control",'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_make]);?>    
                                </td>
                                <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('Subsidy.m_modules][',["type" => "number","step"=>'any','label' => false,"id"=>"m_mod_".$i,"class" => "c2 form-control".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_modules]);
                                if($error_text!='')
                                {
                                    echo '<div class="help-block">'.$error_text.'</div>';
                                }
                                ?>
                                </td>
                                <td style="vertical-align: top !important;">
                                    <?php echo $this->Form->select('Subsidy.m_type_modules][',$type_modules,["label"=>false,"class" => "form-control modules ","id"=>'m_m_'.$i,"value"=>$m_type_modules]);
                                    $style_disp = 'none';
                                    if($m_type_modules==4)
                                    {
                                    $style_disp = 'block';
                                    }
                                    ?>
                                    <div class="" id="m_m_<?php echo $i;?>_o" style="display: <?php echo $style_disp;?>">
                                    <?php echo $this->Form->input('Subsidy.m_type_other][',["type" => "text",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_type_other]);?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                if ($total_commulative > 0) 
                {
                    $total_commulative  = ($total_commulative/1000);
                }
                $total_commulative    = ($total_commulative > 0)?number_format($total_commulative,3,'.',''):"";
                ?>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">Cumulative Capacity of PV Modules (kW)</div>
                    <div class="col-md-2 cumulative-pv-modules"><?php echo $total_commulative;?>
                    </div>
                    <?php  
                        $error_class_for_name_prefix    = '';
                        $error_text                     = '';
                        if(isset($SubsidyErrors['cumulative_module']) && isset($SubsidyErrors['cumulative_module']['_empty']) && !empty($SubsidyErrors['cumulative_module']['_empty']))
                        { 
                        $error_class_for_name_prefix  = 'has-error'; 
                        $error_text                   = $SubsidyErrors['cumulative_module']['_empty'];

                        }
                    ?>
                    <div class="col-md-6 <?php echo $error_class_for_name_prefix;?>">
                        <?php
                            echo $this->Form->input('Subsidy.cumulative_module',["type" => "hidden",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>0]);
                            if(!empty($error_text))
                            {
                            echo '<div class="help-block">'.$error_text.'</div>'; 
                            }
                        ?>
                    </div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">PV Module Serial No.</div>
                    <div class="col-md-5">
                        <div class="">
                            <?php echo $this->Form->input('Subsidy.pv_module_serial', array('label' => false,'div' => false,'type'=>'file','class'=>'pdf-excel','id'=>'pv_module_serial','templates' => ['inputContainer' => '{{content}}'])); 
                            if(isset($Subsidy->pv_module_serial) && !empty($Subsidy->pv_module_serial))
                            {
                                $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->pv_module_serial;
                                if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->pv_module_serial))
                                {
                                    echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/pv_module_serial/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">PV module IEC test certificate provided by NABL Laboratory (IEC certificate)</div>
                    <div class="col-md-5">
                        <div class="">
                            <?php echo $this->Form->input('Subsidy.pv_module_certificate', array('label' => false,'div' => false,'type'=>'file','class'=>'pdf-excel','id'=>'pv_module_certificate','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->pv_module_certificate) && !empty($Subsidy->pv_module_certificate))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->pv_module_certificate;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->pv_module_certificate))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/pv_module_certificate/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">Technical data sheet & catalogue of Solar PV Modules of proposed make, model and capacity</div>
                    <div class="col-md-5">
                        <div class="">
                            <?php echo $this->Form->input('Subsidy.pv_module_sheet', array('label' => false,'div' => false,'type'=>'file','class'=>'pdf-excel','id'=>'pv_module_sheet','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->pv_module_sheet) && !empty($Subsidy->pv_module_sheet))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->pv_module_sheet;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->pv_module_sheet))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/pv_module_sheet/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <fieldset>
                <legend>Inverters</legend>
                <div class="row col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Capacity/ Power (kW)</th>
                                <th scope="col" class="text-center">Make</th>
                                <th scope="col" class="text-center">No. of Inverters</th>
                                <th scope="col" class="text-center">Type of Inverters</th>
                                <th scope="col" class="text-center">Phase of Inverters</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $inverter_data     = isset($Subsidy->inverter_data) ? unserialize($Subsidy->inverter_data) : '';
                        $total_commulative_i  = 0;
                        for($i=1;$i<=3;$i++)
                        {
                            $row                  = $i-1;
                            $i_capacity           = '';
                            $i_make               = '';
                            $i_make_other         = '';
                            $i_modules            = '';
                            $i_type_modules       = '';
                            $i_type_other         = '';
                            $i_phase              = '';
                            if (isset($inverter_data[$row])) 
                            {
                                $i_capacity         = $inverter_data[$row]['i_capacity'];
                                $i_make             = $inverter_data[$row]['i_make'];
                                $i_make_other       = $inverter_data[$row]['i_make_other'];
                                $i_modules          = $inverter_data[$row]['i_modules'];
                                $i_type_modules     = $inverter_data[$row]['i_type_modules'];
                                $i_type_other       = $inverter_data[$row]['i_type_other'];
                                if(isset($inverter_data[$row]['i_phase']))
                                {
                                  $i_phase       = $inverter_data[$row]['i_phase'];
                                }
                                $total_commulative_i= $total_commulative_i + (floatval($inverter_data[$row]['i_capacity'])*floatval($inverter_data[$row]['i_modules']));
                            }
                            $error_class_for_name_prefix  = '';
                            $error_text                   = '';
                            if(isset($SubsidyErrors['i_capacity']) && isset($SubsidyErrors['i_capacity']['_empty']) && !empty($SubsidyErrors['i_capacity']['_empty']) && $i==1)
                            { 
                                $error_class_for_name_prefix ='has-error'; 
                                $error_text = $SubsidyErrors['i_capacity']['_empty'];
                            }
                            ?>
                            <tr>
                                <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('Subsidy.i_capacity][',["type" => "text",'onkeypress'=>"return validateDec(event)",'label' => false,"id"=>"i_capacity_".$i,"class" => "form-control c3 ".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_capacity]);
                                if($error_text!='')
                                {
                                echo '<div class="help-block">'.$error_text.'</div>';
                                }
                                ?>
                                </td>
                                <td style="vertical-align: top !important;" ><?php echo $this->Form->select('Subsidy.i_make][',$make_inverters,["label"=>false,"class" => "form-control make_inverters_new ","id"=>'i_m_'.$i,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_make]);
                                    $style_disp   = 'none';
                                    if($i_make==27)
                                    {
                                        $style_disp = 'block';
                                    }

                                ?>
                                    <div class="" id="i_m_<?php echo $i;?>_o" style="display: <?php echo $style_disp;?>">
                                    <?php echo $this->Form->input('Subsidy.i_make_other][',["type" => "text",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_make_other]);?>
                                    </div>
                                </td>
                                <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>">
                                    <?php echo $this->Form->input('Subsidy.i_modules][',["type" => "number",'label' => false,"step"=>'any',"id"=>"i_mod_".$i,"class" => "c4 form-control ".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_modules]);
                                    if($error_text!='')
                                    {
                                        echo '<div class="help-block">'.$error_text.'</div>';
                                    }
                                    ?>
                                </td>
                                <td style="vertical-align: top !important;"><?php echo $this->Form->select('Subsidy.i_type_modules][',$type_inverters,["label"=>false,"class" => "form-control inverters","id"=>'i_t_'.$i,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_type_modules]);
                                $style_disp   = 'none';
                                if($i_type_modules==6)
                                {
                                $style_disp = 'block';
                                }
                                ?>
                                    <div class="" id="i_t_<?php echo $i;?>_o" style="display: <?php echo $style_disp;?>">
                                    <?php echo $this->Form->input('Subsidy.i_type_other][',["type" => "text",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_type_other]);?>
                                    </div>
                                </td>
                                <td style="vertical-align: top !important;">
                                    <?php 
                                    echo $this->Form->select('Subsidy.i_phase][',$inv_phase,["label"=>false,"class" => "form-control inverters","id"=>'i_phase_'.$i,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_phase]);?>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <?php
                if ($total_commulative_i > 0) 
                {
                    $total_commulative_i  = ($total_commulative_i);
                }
                $total_commulative_i    = ($total_commulative_i > 0)?number_format($total_commulative_i,3,'.',''):"";
                ?>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">Capacity/Power of PCU/Inverters (kW)</div>
                    <div class="col-md-2 cumulative-pv-modules-2"><?php echo $total_commulative_i;?>
                    </div>
                    <?php
                        $error_class_for_name_prefix    = '';
                        $error_text                     = '';
                        if(isset($SubsidyErrors['cumulative_inverter']) && isset($SubsidyErrors['cumulative_inverter']['_empty']) && !empty($SubsidyErrors['cumulative_inverter']['_empty']))
                        { 
                        $error_class_for_name_prefix    = 'has-error'; 
                        $error_text                     = $SubsidyErrors['cumulative_inverter']['_empty'];
                        }
                    ?>
                    <div class="col-md-6 <?php echo $error_class_for_name_prefix;?>">
                    <?php  
                        echo $this->Form->input('Subsidy.cumulative_inverter',["type" => "hidden",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>0]);
                        if(!empty($error_text))
                        {
                        echo '<div class="help-block">'.$error_text.'</div>'; 
                        }
                    ?>
                    </div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">Inverter Serial No.</div>
                    <div class="col-md-5">
                        <div class="">
                            <?php echo $this->Form->input('Subsidy.inverter_serial', array('label' => false,'div' => false,'type'=>'file','class'=>'pdf-excel','id'=>'inverter_serial','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->inverter_serial) && !empty($Subsidy->inverter_serial))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->inverter_serial;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->inverter_serial))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/inverter_serial/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">Inverter IEC test certificate provided by NABL Laboratory( IEC certificate)</div>
                    <div class="col-md-5">
                        <div class="">
                            <?php echo $this->Form->input('Subsidy.inverter_certificate', array('label' => false,'div' => false,'type'=>'file','class'=>'pdf-excel','id'=>'inverter_certificate','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->inverter_certificate) && !empty($Subsidy->inverter_certificate))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->inverter_certificate;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->inverter_certificate))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/inverter_certificate/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-4">Technical data sheet & catalogue of Inverter of proposed make, model and capacity</div>
                    <div class="col-md-5">
                        <div class="">
                            <?php echo $this->Form->input('Subsidy.inverter_sheet', array('label' => false,'div' => false,'type'=>'file','class'=>'pdf-excel','id'=>'inverter_sheet','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->inverter_sheet) && !empty($Subsidy->inverter_sheet))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->inverter_sheet;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->inverter_sheet))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/inverter_sheet/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <fieldset>
                <legend>Commissioning Details</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-2">Date of Commissioning</div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Subsidy.comm_date', array('label' => false,'div' => false,'type'=>'text','id'=>'comm_date','class'=>'form-control datepicker','placeholder'=>'Date')); ?>
                    </div>
                    <div class="label-heading col-md-2">Inverter Login URL</div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('Subsidy.inv_login_url', array('label' => false,'div' => false,'type'=>'text','id'=>'inv_login_url','class'=>'form-control','placeholder'=>'Inverter Login URL')); ?>
                    </div>
                </div>
                <?php
                if($hideInverterDetail == 0)
                {
                    ?>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row col-md-12">
                        <div class="label-heading col-md-2">Inverter Login ID</div>
                        <div class="col-md-3">
                            <?php echo $this->Form->input('Subsidy.inv_login_id', array('label' => false,'div' => false,'type'=>'text','id'=>'inv_login_id','class'=>'form-control','placeholder'=>'Inverter Login ID')); ?>
                        </div>
                        <div class="label-heading col-md-2">Password</div>
                        <div class="col-md-3">
                            <?php echo $this->Form->input('Subsidy.inv_password', array('label' => false,'div' => false,'type'=>'text','id'=>'inv_password','class'=>'form-control','placeholder'=>'Password')); ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <fieldset>
                <legend>Other Attachments</legend>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Solar PV Plant Site Photo</div>
                    <div class="col-md-5">
                        <div>
                            <?php echo $this->Form->input('Subsidy.pv_plant_site_photo', array('label' => false,'div' => false,'type'=>'file','class'=>'','id'=>'pv_plant_site_photo','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->pv_plant_site_photo) && !empty($Subsidy->pv_plant_site_photo))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->pv_plant_site_photo;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->pv_plant_site_photo))
                                    {
                                        $detail_url = SUBSIDY_URL.$Subsidy->application_id."/".$Subsidy->pv_plant_site_photo;
                                        $photo_ext  = pathinfo($detail_url,PATHINFO_EXTENSION);
                                        if (in_array($photo_ext,$IMAGE_EXT)) 
                                        {
                                        ?> 
                                            <img style="width:125px;" src="<?php echo URL_HTTP.'app-docs/pv_plant_site_photo/'.encode($Subsidy->id); ?>"/>
                                        <?php
                                        } 
                                        else
                                        {
                                        
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/pv_plant_site_photo/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">If Required, Undertaking of Consumer </div>
                    <div class="col-md-5">
                        <div >
                            <?php echo $this->Form->input('Subsidy.undertaking_consumer', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'undertaking_consumer','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->undertaking_consumer) && !empty($Subsidy->undertaking_consumer))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->undertaking_consumer;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->undertaking_consumer))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/undertaking_consumer/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">GEDA Inspection Report </div>
                    <div class="col-md-5">
                        <div >
                            <?php echo $this->Form->input('Subsidy.geda_inspection_report', array('label' => false,'div' => false,'type'=>'file','class'=>'subsidy-documents','id'=>'geda_inspection_report','templates' => ['inputContainer' => '{{content}}'])); 
                                if(isset($Subsidy->geda_inspection_report) && !empty($Subsidy->geda_inspection_report))
                                {
                                    $path = SUBSIDY_PATH.$Subsidy->application_id."/".$Subsidy->geda_inspection_report;
                                    if ($Couchdb->documentExist($Subsidy->application_id,$Subsidy->geda_inspection_report))
                                    {
                                        echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/geda_inspection_report/'.encode($Subsidy->id)."\">View Attached Document</a></strong>";
                                        if($is_member == false && $CanEdit == true && $SpinSubmit==true) 
                                        {
                                            echo "&nbsp;&nbsp;<input type=\"button\" class=\"btn btn-md btn-danger\" onclick=\"javascript:del_geda_inspection('".encode($Subsidy->application_id)."')\" value=\"Delete\">";
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row col-md-12">
                    <div class="label-heading col-md-3">Remarks </div>
                    <div class="col-md-5">
                        <div >
                            <?php echo $this->Form->textarea('Subsidy.remarks',[ "class" =>"form-control reason",      
                                                                'id'=>'remarks',
                                                                'cols'=>'50','rows'=>'5',
                                                                'label' => false,
                                                                'placeholder' => 'Remarks, if any']);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
            </fieldset>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <?php if(($is_member == false  || $authority_account==1) && $CanEdit == true && $SpinSubmit==true) { ?>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <?php echo $this->Form->input('Save', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_6','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_6')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        $label      = 'Save & Submit';
                        if($ApplyOnlines->social_consumer==1)
                        {
                            $label  = 'Save & Next';
                        }
                        ?>
                        <?php echo $this->Form->input($label, array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'next_6','type'=>'submit','placeholder'=>'Disclaimer','id'=>'next_6')); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php echo $this->Form->end(); ?>