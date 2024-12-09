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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container-fluid">
  <div class="">
    <div class="col-md-12">
      <div class="modal-body">
        <?= $this->Form->create($commEntity,['name'=>'execution','id'=>'execution','enctype'=>"multipart/form-data"]);
          echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>
          <?php
          if(!$fesibility_flag)
          {
            ?>
            <div class="form-group col-sm-12 text-center"> 
              <label class="btn-style" style="color:#FFCC29;">Fesibility still pending.</label>
            </div>
            <?php
          }
          elseif(!$can_start_work)
          {
            ?>
            <div class="form-group col-sm-12 text-center"> 
              <label class="btn-style" style="color:#FFCC29;">Fesibility payment not approve.</label>
            </div>
            <?php
          }
          ?>
          <div class="form-group">
            <label class="control-label col-sm-2">Name of Applicant</label>
            <div class="col-sm-3">
              <?php echo isset($applyOnlinesData->customer_name_prefixed)?$applyOnlinesData->customer_name_prefixed.' '.$applyOnlinesData->name_of_consumer_applicant:''; ?>
            </div>
            <label class="control-label col-sm-2">GEDA Registration No.</label>
            <div class="col-sm-3">
              <?php 
                echo isset($applyOnlinesData->geda_application_no)?$applyOnlinesData->geda_application_no:'';
              ?>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2">Address</label>
            <div class="col-sm-3">
              <?php echo $applyOnlinesData->address1; ?>
              <br/>
              <?php echo $applyOnlinesData->address2." ".$applyOnlinesData->district; ?> 
            </div>
            <label class="control-label col-sm-2">Capacity(kW)</label>
            <div class="col-sm-3">
              <?php 
                echo $capacity;
              ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2">Contact No</label>
              <div class="col-sm-3">
                <?php echo $mobile_num;?>
              </div>
            <label class="control-label col-sm-2">Consumer No</label>
              <div class="col-sm-3">
                <?php
                if(!empty($applyOnlinesData))
                { 
                  echo $applyOnlinesData->consumer_no; 
                }
                ?>
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2">Latitude</label>
              <div class="col-sm-3">
                <?php echo $projectData['project']['latitude'];?>
              </div>
            <label class="control-label col-sm-2">Longitude</label>
              <div class="col-sm-3">
                <?php echo $projectData['project']['longitude'];?>
              </div>
          </div>
          <?php
          //&& isset($applyOnlinesData->disclaimer_subsidy) && $applyOnlinesData->disclaimer_subsidy==0
          if(SHOW_SUBSIDY_EXECUTION==1)
          {
            ?>
            <div class="form-group">
              <label class="control-label col-sm-2">Total Cost of installation (In Rs.)</label>
              <div class="col-sm-3">
                <?php echo (isset($projectData['project']->estimated_cost)?$projectData['project']->estimated_cost*100000:0)." Rs"; ?>
              </div>
              <?php //pr($subsidyData); ?>
              <label class="control-label col-sm-2">Amount of State subsidy</label>
              <div class="col-sm-3">
                <?php echo $state_subsidy_amount;?>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Amount of MNRE subsidy</label>
              <div class="col-sm-3">
                <?php echo $MNRE_subsidy_amount;?>
              </div>
            </div>
            <?php
          }
          ?>
          <div class="form-group">
            <label class="control-label col-sm-2">Start Date*</label>
              <?php $error_class_for_name_prefix = '';
                          if(isset($WorkorderErrors['start_date']) && isset($WorkorderErrors['start_date']['_empty']) && !empty($WorkorderErrors['start_date']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
            <div class="col-sm-3 <?php echo $error_class_for_name_prefix;?>">
              <?php echo $this->Form->input('start_date',["type" => "text",'label'=>false,'id'=>'start_date',"class" => "form-control datepicker",'value'=>(isset($data['start_date'])?$data['start_date']:'')]);?>
            </div>
            <label class="control-label col-sm-2">End Date*</label>
              <?php $error_class_for_name_prefix = '';
                          if(isset($WorkorderErrors['end_date']) && isset($WorkorderErrors['end_date']['_empty']) && !empty($WorkorderErrors['end_date']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
              <div class="col-sm-3 <?php echo $error_class_for_name_prefix;?>">
                <?php echo $this->Form->input('end_date',["type" => "text","label"=>false,'id'=>'end_date',"class" => "form-control datepicker",'value'=>(isset($data['end_date'])?$data['end_date']:'')]);?>
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2">Date of Bi-directional meter installation</label>
            <div class="col-sm-3">
              <?php $readBi = ($readOnlyBiDate == 1) ? "readonly" : ""; 
                    $dateBi = ($readOnlyBiDate == 0) ? "datepicker" : ""; 
              echo $this->Form->input('bi_date',["type" => "text",'label'=>false,'id'=>'bi_date',"class" => "form-control ".$dateBi,'value'=>(isset($data['bi_date'])?$data['bi_date']:''),"readonly"=>$readBi]);?>
            </div> 
          </div>
          <h4>Net Meter Details</h4>
          <div class="form-group">
            <label class="control-label col-sm-2">Meter Manufacture Name</label>
            <div class="col-sm-3">
              <?php echo $this->Form->input('meter_manufacture', array('label' => false,'class'=>'form-control','placeholder'=>'Net Meter Manufacture Name')); ?>
            </div>
            <label  class="control-label col-sm-2">Meter Serial No.</label>
            <div class="col-sm-4 ">
              <?php 
                $readMeter = ($readOnlyMeter == 1) ? "readonly" : ""; 
                echo $this->Form->input('meter_serial_no', array('label' => false,'class'=>'form-control','placeholder'=>'Net Meter Serial No.',"readonly"=>$readMeter)); ?>
            </div>
          </div>
          <h4>Solar Meter Details</h4>
          <div class="form-group">
            <label class="control-label col-sm-2">Meter Manufacture Name</label>
            <div class="col-sm-3">
              <?php echo $this->Form->input('solar_meter_manufacture', array('label' => false,'class'=>'form-control','placeholder'=>'Solar Meter Manufacture Name')); ?>
            </div>
            <label  class="control-label col-sm-2">Meter Serial No.</label>
            <div class="col-sm-4 ">
              <?php 
                $readSolar = ($readOnlySolar == 1) ? "readonly" : ""; 
                echo $this->Form->input('solar_meter_serial_no', array('label' => false,'class'=>'form-control','placeholder'=>'Solar Meter Serial No.','readonly'=>$readSolar)); ?>
            </div>
          </div>
          <h4>Modules</h4>
          <div class="row">
            <div class="col-md-12 table-responsive">
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
                $modules_data     = $data['modules_data'];
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
                        if(isset($WorkorderErrors['m_capacity']) && isset($WorkorderErrors['m_capacity']['_empty']) && !empty($WorkorderErrors['m_capacity']['_empty']) && $i==1){ $error_class_for_name_prefix ='has-error'; 
                        $error_text = $WorkorderErrors['m_capacity']['_empty'];} 
                  ?>
                  <tr>
                    <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('m_capacity[]',["type" => "text",'onkeypress'=>"return validateDec(event)",'label' => false,"id"=>"m_capacity_".$i,"class" => "c1 form-control ".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_capacity]); 
                      if($error_text!='')
                      {
                        echo '<div class="help-block">'.$error_text.'</div>';
                      }
                    ?></td>
                    <td style="vertical-align: top !important;"><?php echo $this->Form->input('m_make[]',["type" => "text",'label' => false,"class" => "form-control",'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_make]);?></td>
                    <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('m_modules[]',["type" => "number","step"=>'any','label' => false,"id"=>"m_mod_".$i,"class" => "c2 form-control".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_modules]);
                      if($error_text!='')
                      {
                        echo '<div class="help-block">'.$error_text.'</div>';
                      }
                    ?></td>
                    <td><?php echo $this->Form->select('m_type_modules[]',$type_modules,["label"=>false,"class" => "form-control modules ","id"=>'m_m_'.$i,"value"=>$m_type_modules]);
                      $style_disp = 'none';
                      if($m_type_modules==4)
                      {
                        $style_disp = 'block';
                      }
                      ?>
                      <div class="" id="m_m_<?php echo $i;?>_o" style="display: <?php echo $style_disp;?>">
                        <?php echo $this->Form->input('m_type_other[]',["type" => "text",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>$m_type_other]);?>
                      </div>
                    </td>
                  </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php
          if ($total_commulative > 0) 
          {
            $total_commulative  = ($total_commulative/1000);
          }
          $total_commulative    = ($total_commulative > 0)?number_format($total_commulative,3,'.',''):"";
          ?>
          <div class="form-group">
            <div class="col-md-2"><label>
              Cumulative Capacity of PV Modules (kW)</label>
            </div>
            <div class="col-md-3 cumulative-pv-modules">
              <?php echo $total_commulative;?>
            </div>
            <?php  
              $error_class_for_name_prefix    = '';
              $error_text                     = '';
              if(isset($WorkorderErrors['cumulative_module']) && isset($WorkorderErrors['cumulative_module']['_empty']) && !empty($WorkorderErrors['cumulative_module']['_empty']))
              { 
                $error_class_for_name_prefix  = 'has-error'; 
                $error_text                   = $WorkorderErrors['cumulative_module']['_empty'];
                
              }
            ?>
            <div class="col-md-6 <?php echo $error_class_for_name_prefix;?>">
              <?php
              echo $this->Form->input('cumulative_module',["type" => "hidden",'label' => false,"id"=>"cumulative_module","class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>!empty($total_commulative) ? $total_commulative : 0]);
              if(!empty($error_text))
              {
                echo '<div class="help-block">'.$error_text.'</div>'; 
              }
              ?>
            </div>
          </div>
          <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Upload PDF</label>
            <div class="col-sm-4 ">
              <div class="file-loading">
                <input id="image" name="document[modules_img][]" type="file" multiple >
              </div>
              <div id="modules-img-errors"></div>
              <div>
                <table width="100%">
                  <tr>
                  <?php
                    $counter=1;
                    foreach($all_photo_data as $key => $ph_data)
                    {
                     if($ph_data->c['type'] == 'modules')
                     {
                        $path = EXECUTION_PATH.$project_id.'/'.'modules'.'/';
                        $ext='';
                        if ($Couchdb->documentExist($project_id,$ph_data->c['photo']))
                        {
                        $ext='';
                        $ext = pathinfo($ph_data->c['photo'], PATHINFO_EXTENSION);
                        ?>
                        <td width="25%" id="photo_<?php echo encode($ph_data->c['id']);?>" style="padding-top:10px;">
                          <table>
                            <tr>
                              <td>
                                <?php
                                $logo_url = '';
                                if($ext=='pdf')
                                {
                                  $logo_url=URL_HTTP.'img/pdflogo.jpg';
                                }
                                elseif($ext=='xls' || $ext=='xlsx')
                                {
                                  $logo_url=URL_HTTP.'img/excellogo.jpg';
                                }
                                if($logo_url!='')
                                {
                                  ?>
                                  <a href="<?php echo URL_HTTP.'app-docs/modules/'.encode($ph_data->c['id']);?>" ><img src="<?php  echo $logo_url;?>" width="75px"></a>

                                  <?php
                                }
                                else
                                {
                                  ?>
                                  <a href="<?php echo URL_HTTP.'app-docs/modules/'.encode($ph_data->c['id']);?>" >View Attached Document</a>
                                  <?php  
                                }
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td align="center"><i class="fa fa-remove"  onclick="javascript:delete_photo('<?php echo encode($ph_data->c['id']);?>');"> </i></td>
                            </tr>
                          </table>
                        </td>
                        <?php
                        if($counter%3 == 0)
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
          <h4>Inverters</h4>
          <div class="row">
            <div class="col-md-12 table-responsive">
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
                $inverter_data        = $data['inverter_data'];
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
                        if(isset($WorkorderErrors['i_capacity']) && isset($WorkorderErrors['i_capacity']['_empty']) && !empty($WorkorderErrors['i_capacity']['_empty']) && $i==1){ $error_class_for_name_prefix ='has-error'; 
                        $error_text = $WorkorderErrors['i_capacity']['_empty'];}
                  ?>
                  <tr>
                    <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('i_capacity[]',["type" => "text",'onkeypress'=>"return validateDec(event)",'label' => false,"id"=>"i_capacity_".$i,"class" => "form-control c3 ".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_capacity]);
                      if($error_text!='')
                      {
                        echo '<div class="help-block">'.$error_text.'</div>';
                      }
                      ?></td>
                    <td style="vertical-align: top !important;" ><?php echo $this->Form->select('i_make[]',$make_inverters,["label"=>false,"class" => "form-control make_inverters_new ","id"=>'i_m_'.$i,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_make]);
                      $style_disp   = 'none';
                      if($i_make==27)
                      {
                        $style_disp = 'block';
                      }
                      
                    ?>
                      <div class="" id="i_m_<?php echo $i;?>_o" style="display: <?php echo $style_disp;?>">
                        <?php echo $this->Form->input('i_make_other[]',["type" => "text",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_make_other]);?>
                      </div>
                    </td>
                    <td style="vertical-align: top !important;" class="<?php echo $error_class_for_name_prefix;?>"><?php echo $this->Form->input('i_modules[]',["type" => "number","step"=>'any','label' => false,"id"=>"i_mod_".$i,"class" => "c4 form-control ".$error_class_for_name_prefix,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_modules]);
                      if($error_text!='')
                      {
                        echo '<div class="help-block">'.$error_text.'</div>';
                      }
                    ?>
                    </td>
                    <td style="vertical-align: top !important;"><?php echo $this->Form->select('i_type_modules[]',$type_inverters,["label"=>false,"class" => "form-control inverters","id"=>'i_t_'.$i,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_type_modules]);
                      $style_disp   = 'none';
                      if($i_type_modules==6)
                      {
                        $style_disp = 'block';
                      }
                    ?>
                      <div class="" id="i_t_<?php echo $i;?>_o" style="display: <?php echo $style_disp;?>">
                        <?php echo $this->Form->input('i_type_other[]',["type" => "text",'label' => false,"class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_type_other]);?>
                      </div>
                    </td>
                    <td style="vertical-align: top !important;"><?php 
                    echo $this->Form->select('i_phase[]',$inv_phase,["label"=>false,"class" => "form-control inverters","id"=>'i_phase_'.$i,'templates' => ['inputContainer' => '{{content}}'],"value"=>$i_phase]);
                    ?></td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
              </table>
            </div>
          </div>
          <?php
          if ($total_commulative_i > 0) 
          {
            $total_commulative_i  = ($total_commulative_i);
          }
          $total_commulative_i    = ($total_commulative_i > 0)?number_format($total_commulative_i,3,'.',''):"";
          ?>
          <div class="form-group">
            <div class="col-md-2"><label>
              Capacity/Power of PCU/Inverters (kW)</label>
            </div>
            <div class="col-md-3 cumulative-pv-modules-2">
              <?php echo $total_commulative_i;?>
            </div>
            <?php  
              $error_class_for_name_prefix    = '';
              $error_text                     = '';
              if(isset($WorkorderErrors['cumulative_inverter']) && isset($WorkorderErrors['cumulative_inverter']['_empty']) && !empty($WorkorderErrors['cumulative_inverter']['_empty']))
              { 
                $error_class_for_name_prefix  = 'has-error'; 
                $error_text                   = $WorkorderErrors['cumulative_inverter']['_empty'];
                
              }
            ?>
            <div class="col-md-6 <?php echo $error_class_for_name_prefix;?>">
              <?php
              echo $this->Form->input('cumulative_inverter',["type" => "hidden",'label' => false,"id"=>"cumulative_inverter","class" => "form-control","placeholder"=>"others",'templates' => ['inputContainer' => '{{content}}'],"value"=>!empty($total_commulative_i) ? $total_commulative_i : 0]);
              if(!empty($error_text))
              {
                echo '<div class="help-block">'.$error_text.'</div>'; 
              }
              ?>
            </div>
          </div>
          <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Upload PDF</label>
            <div class="col-sm-4 ">
              <div class="file-loading">
                <input id="image_inverter" name="document[inverter_img][]" type="file"     multiple >
              </div>
              <div id="inverter-img-errors"></div>
              <div>
                <table width="100%">
                  <tr>
                    <?php
                      $counter=1;
                      if(!empty($all_photo_data)) {
                        foreach($all_photo_data as $key => $ph_data)
                        {
                          if($ph_data->c['type'] == 'inverters')
                          {
                            $path = EXECUTION_PATH.$project_id.'/'.'inverters'.'/';
                            $ext='';
                            if ($Couchdb->documentExist($project_id,$ph_data->c['photo']))
                            {
                              $ext = pathinfo($ph_data->c['photo'], PATHINFO_EXTENSION);
                              $doc="";    
                              ?>
                              <td width="25%" id="photo_<?php echo encode($ph_data->c['id']);?>" style="padding-top:10px;">
                                <table>
                                  <tr>
                                    <td>
                                      <?php
                                      $logo_url = '';
                                      if($ext=='pdf')
                                      {
                                        $logo_url=URL_HTTP.'img/pdflogo.jpg';
                                      }
                                      elseif($ext=='xls' || $ext=='xlsx')
                                      {
                                        $logo_url=URL_HTTP.'img/excellogo.jpg';
                                      }
                                      if($logo_url!='')
                                      {
                                        ?>
                                        <a href="<?php echo URL_HTTP.'app-docs/inverters/'.encode($ph_data->c['id']);?>" ><img src="<?php  echo $logo_url;?>" width="75px"></a>
                                        <?php
                                      }
                                      else
                                      {
                                        ?>
                                        <a href="<?php echo URL_HTTP.'app-docs/inverters/'.encode($ph_data->c['id']);?>" >View Attached Document</a>
                                        <?php  
                                      }
                                      ?>
                                        
                                    </td>
                                  </tr>
                                  <tr>
                                    <td align="center"><i class="fa fa-remove"  onclick="javascript:delete_photo('<?php echo encode($ph_data->c['id']);?>');"> </i></td>
                                  </tr>
                                </table>
                              </td>
                              <?php
                                if($counter%3 == 0)
                                {
                                  echo '</tr><tr>';
                                }
                                $counter++;
                            }
                          }
                        }
                      }
                      
                    ?>
                   </tr>
                </table>
              </div>
            </div>
          </div>
          <h4>Grid Parameters</h4>
          <div class="form-group">
            <label class="control-label col-sm-2">Grid Connectivity Level Voltage</label>
            <div class="col-sm-3">
              <?php echo $this->Form->input('connectivity_level_voltage', array('label' => false,'class'=>'form-control','placeholder'=>'Grid Connectivity Level Voltage')); ?>
            </div>
            <label  class="control-label col-sm-2">Grid Connectivity Level Phase</label>
            <div class="col-sm-4 ">
              <?php echo $this->Form->select('connectivity_level_phase',array('1'=>'Single Phase','3'=>'3 Phase'), array('label' => false,'class'=>'form-control','empty'=>'-Select Phase-','placeholder'=>'Grid Connectivity Level Phase')); ?>
            </div>
          </div>
          <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Upload Other PDF</label>
            <div class="col-sm-4 ">
              <div class="file-loading">
                <input id="otherimage" name="document[other_img][]" type="file"     multiple >
              </div>
              <div id="errors"></div>
              <div>
                <table width="100%">
                  <tr>
                    <?php
                      $counter=1;
                      if(!empty($all_photo_data)) {
                        foreach($all_photo_data as $key => $ph_data)
                        {
                          if($ph_data->c['type'] == 'others')
                          {
                            $path = EXECUTION_PATH.$project_id.'/'.'others'.'/';
                            $ext='';
                            if ($Couchdb->documentExist($project_id,$ph_data->c['photo']))
                            {
                              $ext      = pathinfo($ph_data->c['photo'], PATHINFO_EXTENSION);
                              $doc      = "";
                            ?>
                              <td width="25%" id="photo_<?php echo encode($ph_data->c['id']);?>" style="padding-top:10px;">
                                <table>
                                  <tr>
                                    <td>
                                       <a href="<?php echo URL_HTTP.'app-docs/others_exe/'.encode($ph_data->c['id']);?>" ><img src="<?php  echo ($ext=='pdf')? URL_HTTP.'img/pdflogo.jpg':URL_HTTP.'app-docs/others_exe/'.encode($ph_data->c['id']);?>" width="75px"></a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td align="center"><i class="fa fa-remove"  onclick="javascript:delete_photo('<?php echo encode($ph_data->c['id']);?>');"> </i>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                              <?php
                              if($counter%3 == 0)
                              {
                                echo '</tr><tr>';
                              }
                              $counter++;
                            }
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
            <div class="col-sm-12">
              <?php
              if($can_start_work && $fesibility_flag && ($is_member==false || in_array($member_area,array(470,471))))
              {
                ?>
                <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left submit']); ?>
                <?php
              }
              ?>
            </div>
          </div>
        <?= $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
/*$( ".datepicker").datepicker();
$( ".datepicker").datepicker( "option", "dateFormat", 'dd-mm-yy' );
$( "#start_date" ).datepicker( "setDate", new Date(<?php echo (isset($data['start_date'])?$data['start_date']:'')?>));
$( "#end_date").datepicker( "setDate", new Date(<?php echo (isset($data['end_date'])?$data['end_date']:'')?>));
*/
$(".datepicker").datepicker({
  dateFormat: 'dd-mm-yy'
});


$(document).on('click', '#addNote', function() {
  $('#add_projects_note_model').modal('show');
});

$("#add_project_note_form").submit(function(e) {
  var form_data = new FormData(this);
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
          if(results.status=='1') {
              location.reload();
          } else {
          }
      }
  });
  e.preventDefault();
});

$("#upload_image").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 1,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf", "xls", "xlsx"],
        elErrorContainer: '#img-errors',
        maxFileSize: 1024,
    });

$("#image").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 6,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#modules-img-errors',
        maxFileSize: 1024,
    });
$("#image_inverter").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 6,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#inverter-img-errors',
        maxFileSize: 1024,
    });
$("#otherimage").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 6,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#errors',
        maxFileSize: 1024,
    });

function delete_photo(photo_id)
{
  if(window.confirm("Are you sure want to delete photo?"))
  {
    jQuery.ajax({
      beforeSend: function(xhr){
          xhr.setRequestHeader(
              'X-CSRF-Token',
              <?php echo json_encode($this->request->param('_csrfToken')); ?>
          );
      },
        url: '<?php echo URL_HTTP."projects/delete_execution_image"; ?>',
        type: 'POST',
        data:  {photo_id:photo_id},
        success: function(results)
        {
            if(results=='1') {
                $("#photo_"+photo_id).remove();
                document.getElementById('TaskIFrame').src += 'project/execution/'+ '<?php echo encode($project_id); ?>';
            } else {

            }
        }
    });
  }
}
$('.modules').on('change', function() {
  var id_sel = this.id;
  if(this.value==4){
    $('#'+id_sel+'_o').show();
  }
  else{
    $('#'+id_sel+'_o').hide();
    //$('#'+id_sel+'_o').children().val('');
  }
});

$('.make_inverters_new').on('change', function() {
  var id_sel = this.id;
  if(this.value==27){
    $('#'+id_sel+'_o').show();
  }
  else{
    $('#'+id_sel+'_o').hide();
    $('#'+id_sel+'_o').val('');
  }
});

$('.inverters').on('change', function() {
  var id_sel = this.id;
  if(this.value==6){
    $('#'+id_sel+'_o').show();
  }
  else{
    $('#'+id_sel+'_o').hide();
    $('#'+id_sel+'_o').val('');
  }
});
</script>
<script type="text/javascript">
$(document).ready(function(){
  $(".c2").blur(function() {
      var total = 0
      $( ".c1" ).each(function( index ) {
          var pv  = $("#m_capacity_"+(index+1)).val();
          var mod = $("#m_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total/1000).toFixed(3):"");
      $(".cumulative-pv-modules").html(total);
      $("#cumulative_module").val(total);
  });
  $(".c1").blur(function() {
      var total = 0
      $( ".c2" ).each(function( index ) {
          var pv  = $("#m_capacity_"+(index+1)).val();
          var mod = $("#m_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total/1000).toFixed(3):"");
      $(".cumulative-pv-modules").html(total);
      $("#cumulative_module").val(total);
      
  });
  $(".c3").blur(function() {
      var total = 0
      $( ".c4" ).each(function( index ) {
          var pv  = $("#i_capacity_"+(index+1)).val();
          var mod = $("#i_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total).toFixed(3):"");
      $(".cumulative-pv-modules-2").html(total);
      $("#cumulative_inverter").val(total);
  });
  $(".c4").blur(function() {
      var total = 0
      $( ".c3" ).each(function( index ) {
          var pv  = $("#i_capacity_"+(index+1)).val();
          var mod = $("#i_mod_"+(index+1)).val();
          var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
          if (v3 > 0) {
              total += v3;
          }
      });
      total = ((total > 0)?parseFloat(total).toFixed(3):"");
      $(".cumulative-pv-modules-2").html(total);
      $("#cumulative_inverter").val(total);
  });
});
function validateDec(key) {
  var keycode = (key.which) ? key.which : key.keyCode;
  if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
    return false;
  }
  else {
    var parts = key.srcElement.value.split('.');
    if (parts.length > 1 && keycode == 46)
        return false;
    return true;
  }
}
</script>