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
.table-fr-td {
    padding-top:15px !important;
}
.tab-pane.active {
    display: flex !Important;
}
#step5 .col-sm-6 {
    z-index: 999;
}
.year_chart{
    margin-left: 0px;
    width:100%;
}
<?php

            $yearChart = '';
            if(isset($payBackGraphData) && !empty($payBackGraphData)) {
            foreach($payBackGraphData as $year => $data) {
            $yearChart .= "['".$year."',".$data."],";
            }
            $yearChart = rtrim($yearChart,",");
            }
      ?>
</style>
<div class="container-fluid">
     <div class="row border-style">
        <div class="col-md-12">
            <div class="tabs">
                <ul class="nav nav-tabs nav-justified">
                    <li class="<?php if($tab_active == 'step1') { echo 'active'; }?>">
                        <a href="#step1" data-toggle="tab" class="text-center"><b>Page 1 - Cost Input</b></a>
                    </li>
                    <li class="<?php if($tab_active == 'step2') { echo 'active'; }?>">
                        <a href="#step2" data-toggle="tab" class="text-center"><b>Page 2 - Assumption</b></a>
                    </li>
                    <li class="<?php if($tab_active == 'step3') { echo 'active'; }?>">
                        <a href="#step3" data-toggle="tab" class="text-center"><b>Page 3 - Electricity Bill Details</b></a>
                    </li>
                     <li class="<?php if($tab_active == 'step4') { echo 'active'; }?>">
                        <a href="#step4" data-toggle="tab" class="text-center"><b>Customize Financial Summery</b></a>
                    </li>
                    <li class="<?php if($tab_active == 'step5') { echo 'active'; }?>">
                        <a href="#step5" data-toggle="tab" class="text-center"><b>Techno Commercial Summery</b></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="step1" class="tab-pane <?php if($tab_active == 'step1') { echo 'active'; }?>">
                        <!-- Modal content-->
                        <div class="modal-body">
                            <?= $this->Form->create($Commercial,['name'=>'commercial1','id'=>'commercial1'],array('action' => 'projects/commercial'));
                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>
                            
                            <table class="table custom_table lable_left" id="customFields1" width="100%">
                                <tbody>
                                    <tr id="rowId">
                                        <td class="wwe-lang-matches table-fr-td" scope="col">

                                            <div class="form-group table-fr-td">
                                                <label for="pv_wp" class="control-label col-sm-2">Solar PV (Wp) Rating</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('pv_rating',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"rate","onchange"=>"totalcost()"]);?>
                                                </div>
                                                <label for="pv_qty" class="control-label col-sm-2">Quantity</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('pv_qty',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"qty","onchange"=>"totalcost()"]);?>
                                                </div>
                                                <label for="pv_rate" class="control-label col-sm-2">Amount (in &#x20b9)</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('pv_cost',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"amt","onchange"=>"totalcost()"]);?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inver_rating" class="control-label col-sm-2">Inverter Rating (KVA)</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('inverter_rating',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"inv_rate","onchange"=>"totalcost()"]);?>
                                                </div>
                                                <label for="inver_qty" class="control-label col-sm-2">Quantity</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('inverter_qty',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"inv_qty","onchange"=>"totalcost()"]);?>
                                                </div>
                                                <label for="inver_amount" class="control-label col-sm-2">Amount (in &#x20b9)</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('inverter_cost',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"inv_amt","onchange"=>"totalcost()"]);?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="bos" class="control-label col-sm-2">Balance of System</label>
                                                <div class="col-sm-2">
                                                </div>
                                                <label for="bos_qty" class="control-label col-sm-2">Quantity</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('bos_qty',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"bqty","onchange"=>"totalcost()"]);?>
                                                </div>
                                                <label for="bos_amount" class="control-label col-sm-2">Amount (in &#x20b9)</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('bos_cost',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"bosrs","onchange"=>"totalcost()"]);?>
                                                </div>

                                                <div class="row">
                                                    <div class="_100 h_63">
                                                        <p>
                                                        <div class="col-sm-11">
                                                        </div>
                                                        <label class="spanright" class="col-sm-1">
                                                            <span><a href="javascript:;" class="addmore">+Add More</a></span>
                                                        </label>
                                                        </p>
                                                    </div>
                                                </div>
                                            <div class="form-group" id="com_others" style="display: none;">
                                                <label for="inver_rating" class="control-label col-sm-2">Others</label>
                                                <div class="col-sm-2">
                                                </div>
                                                <label for="bos_qty" class="control-label col-sm-2">Quantity</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('other_qty',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"oqty","onchange"=>"totalcost()"]);?>
                                                </div>
                                                <label for="bos_amount" class="control-label col-sm-2">Amount (in &#x20b9)</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('other_cost',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"ors","onchange"=>"totalcost()"]);?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                            <div class="form-group" id="tax_total">
                                <label for="taxes" class="control-label col-sm-2">Taxes (in &#x20b9)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('taxes',["type" => "number","min"=>"0","class" => "form-control",'label' => false,"id"=>"tax","onchange"=>"totalcost()"]);?>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <label for="total" class="control-label col-sm-2">Total* (in &#x20b9)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('total_cost',["type" => "number","min"=>"0","class" => "form-control",'label' => false,"id"=>"total","onchange"=>"totalcost()"]);?>
                                </div>
                            </div>
                            <h4 align="center">OR</h4>
                            <div class="form-group" id="lumpsumcost">
                                <label for="amount" class="control-label col-sm-4">Lumpsum Cost (in &#x20b9)</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('lumpsum_cost',["type" => "number","min"=>"0","class" => "form-control",'label' => false,"id"=>"lumpsum_cost"]);?>
                            </div>
                            </div>

                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left',"name" => "step_1"]); ?><br/>

                            <?= $this->Form->end(); ?>
                        </div>
                    </div>

                    <div id="step2" class="tab-pane <?php if($tab_active == 'step2') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?= $this->Form->create($Commercial,['name'=>'commercial1','id'=>'commercial1'],array('action' => 'projects/commercial'));

                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>

                            <div class="form-group">
                                <label for="debt" class="control-label col-sm-2">Debt (in %)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('debt',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"debt"]);?>
                                </div>
                                <label for="interest" class="control-label col-sm-2">Interest (%)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('interest',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"interest"]);?>
                                </div>
                                <label for="depreciation" class="control-label col-sm-2">Depreciation (%)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('deprecation',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"deprecation"]);?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="om_cost" class="control-label col-sm-2">O&M Cost (in &#x20b9)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('om_cost',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"om_cost"]);?>
                                </div>
                                <label for="cuf" class="control-label col-sm-2">CUF (%)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('cuf',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"cuf"]);?>
                                </div>
                                <label for="loan_tensure" class="control-label col-sm-2">Loan Tenure (%)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('loanternure',["type" => "number","min"=>"0","label"=>false,"class" => "form-control","id"=>"loanternure"]);?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="button" value="restore default value" class="btn-primary btn pull-left" style="margin-top: 23px;" data-loading-text="Loading..." onclick="restore_value()">
                            </div>
                            
                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left','name' => "step_2"]); ?><br/>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                    <div id="step3" class="tab-pane <?php if($tab_active == 'step3') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?= $this->Form->create($Commercial,['name'=>'commercial1','id'=>'commercial1'],array('action' => 'projects/commercial'));
                           
                            echo $this->Form->input('note_project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>

                            <div class="form-group">
                                <label for="voltage_level_phase" class="control-label col-sm-2">Fixed Charges</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fc_charge_rs',["type" => "number","min"=>"0","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-1">
                                   Rs
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fc_charge_kw',["type" => "number","min"=>"0","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <div class="col-sm-1">
                                    kW
                                </div>
                                <div class="col-sm-1">
                                </div>
                            </div>
                            <?php
                            $energy_charge_arr   = array();
                            $ec_det           = '0';
                            if(isset($Commercial) && !empty($Commercial['energy_charge_details']))
                            {
                                $ec_details     = unserialize($Commercial['energy_charge_details']);
                                $energy_charge_arr = $ec_details['energy_charge'];
                            }
                            ?>
                            <table id="customFields2" width="100%">
                                <tbody>
                                <?php
                                if(empty($energy_charge_arr))
                                {
                                    ?>
                                    <tr id="rowId2">
                                        <td class="wwe-lang-matches" scope="col">

                                            <div class="form-group table-fr-td">
                                                <label for="energy_chage_upto" class="control-label col-sm-2">Energy
                                                    Charges</label>
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('eccharges_upto[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    kWh
                                                </div>
                                                <div class="col-sm-1">
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('eccharges_upto_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    Per kWh
                                                </div>
                                                <div class="col-sm-1">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="ec_between2_from" class="control-label col-sm-2">Between
                                                    From</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('ecbetween1_from[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    Between to
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('ecbetween1_to[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    kWh
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('ecbetween1_to_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    Per kWh
                                                </div>
                                                <div class="col-sm-1">
                                                    <p width="5%" class="remove_button"><i class="fa fa-remove"
                                                                                           style="color:#ff0000;display:none;"></i>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="ec_between2_from" class="control-label col-sm-2">Between
                                                    From</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('ecbetween2_from[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    Between to
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('ecbetween2_to[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    kWh
                                                </div>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('ecbetween2_to_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    Per kWh
                                                </div>
                                                <div class="col-sm-1">
                                                </div>

                                            </div>
                                            <div class="form-group table-fr-td">
                                                <label for="energy_chage_upto" class="control-label col-sm-2">More
                                                    Then</label>
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('ecmorethen[]', ["type" => "number", "min"=>"0","label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    kWh
                                                </div>
                                                <div class="col-sm-1">
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('ecmorethen_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control"]); ?>
                                                </div>
                                                <div class="col-sm-1">
                                                    Per kWh
                                                </div>

                                                <div class="col-sm-1">
                                                </div>

                                            </div>

                                        </td>
                                    </tr>
                                    <?php
                                }
                                foreach($energy_charge_arr as $key=>$ec_det)
                                {
                                $tr_id = '';
                                if($key == 0)
                                {
                                    $tr_id = "rowId2";
                                }
                                ?>
                                <tr id="<?php echo $tr_id;?>">
                                    <td class="wwe-lang-matches" scope="col">

                                        <div class="form-group table-fr-td">
                                            <label for="energy_chage_upto" class="control-label col-sm-2">Energy
                                                Charges</label>
                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('eccharges_upto[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control" , "value" => $ec_det['eccharges_upto']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                kWh
                                            </div>
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('eccharges_upto_rs[]', ["type" => "number","min"=>"0","label" => false, "class" => "form-control" ,"value" => $ec_det['eccharges_upto_rs']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                Per kWh
                                            </div>
                                            <div class="col-sm-1">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ec_between2_from" class="control-label col-sm-2">Between
                                                From</label>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('ecbetween1_from[]', ["type" => "number","min"=>"0","label" => false, "class" => "form-control","value" => $ec_det['ecbetween1_from']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                Between to
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('ecbetween1_to[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecbetween1_to']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                kWh
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('ecbetween1_to_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecbetween1_to_rs']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                Per kWh
                                            </div>
                                            <div class="col-sm-1">
                                                <p width="5%" class="remove_button"><i class="fa fa-remove" style="color:#ff0000;display:none;"></i>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ec_between2_from" class="control-label col-sm-2">Between
                                                From</label>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('ecbetween2_from[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecbetween2_from']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                Between to
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('ecbetween2_to[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecbetween2_to']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                kWh
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('ecbetween2_to_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecbetween2_to_rs']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                Per kWh
                                            </div>
                                            <div class="col-sm-1">
                                            </div>

                                        </div>
                                        <div class="form-group table-fr-td">
                                            <label for="energy_chage_upto" class="control-label col-sm-2">More
                                                Then</label>
                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('ecmorethen[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecmorethen']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                kWh
                                            </div>
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-3">
                                                <?php echo $this->Form->input('ecmorethen_rs[]', ["type" => "number","min"=>"0", "label" => false, "class" => "form-control","value" => $ec_det['ecmorethen_rs']]); ?>
                                            </div>
                                            <div class="col-sm-1">
                                                Per kWh
                                            </div>

                                            <div class="col-sm-1">
                                            </div>

                                        </div>

                                    </td>
                                </tr>
                                    <?php
                                }
                                ?>

                                </tbody>
                            </table>
                            <div class="row">
                                <div class="_100 h_63">
                                    <p>
                                        <label class="spanright">
                                            <span ><a href="javascript:;" class="addmore2">+Add More</a></span>
                                        </label>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group table-fr-td">
                                <label for="energy_chage_upto" class="control-label col-sm-2">+Variable charges</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('vc_charge',["type" => "number","min"=>"0","label"=>false,"class" => "form-control"]);?>
                                </div>
                                <label for="energy_chage_upto" class="control-label col-sm-1">Notes</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('note3',["type" => "text","label"=>false,"class" => "form-control"]);?>
                                </div>

                            </div>
                            <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left',"name" => "step_3"]); ?><br/>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                    <div id="step4" class="tab-pane <?php if($tab_active == 'step4') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?= $this->Form->create($Commercial,['name'=>'commercial1','id'=>'commercial1'],array('action' => 'projects/commercial'));

                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>

                            <?php
                            if(isset($Commercial) && !empty($Commercial['lumpsum_cost']))
                            {
                                ?>
                                <div class="form-group">
                                     <label for="lumpsum_cost" class="control-label col-sm-4">Lumpsum Cost</label>
                                     <div class="col-sm-4">
                                        <?php echo $this->Form->input('lumpsum_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                     </div>
                                 </div>
                            <?php }
                            else{
                               ?> 
                            <div class="form-group">
                                <label for="PV Cost" class="control-label col-sm-2">PV Cost</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('pv_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                <label for="Qty" class="control-label col-sm-2">Quantity</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('pv_qty',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="PV Cost" class="control-label col-sm-2">Inverter Cost</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('inverter_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                <label for="Qty" class="control-label col-sm-2">Quantity</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('inverter_qty',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="PV Cost" class="control-label col-sm-2">BOS Cost</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('bos_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                <label for="Qty" class="control-label col-sm-2">Quantity</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('bos_qty',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="PV Cost" class="control-label col-sm-2">Others Costs</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('other_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                                <label for="Qty" class="control-label col-sm-2">Quantity</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('other_qty',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="PV Cost" class="control-label col-sm-2">Total Cost Of PV System (in &#x20b9)</label>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('total_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                            <?= $this->Form->button(__('Save & View Report'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left','name' => "step_4"]); ?><br/>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                     <div id="step5" class="tab-pane <?php if($tab_active == 'step5') { echo 'active'; }?>">
                        <div class="modal-body">
                            <?= $this->Form->create($Commercial,['name'=>'commercial1','id'=>'commercial1'],array('action' => 'projects/commercial'));
                                        
                            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="PV Cost" class="control-label col-sm-2">Recommended Capacity*</label>
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('recommended_capacity',["type" => "number","label"=>false,"class" => "form-control","value"=>$recommendedSolarPvInstall,"disabled"=>"disabled"]);?>
                                    </div>
                                    <label for="Qty" class="control-label col-sm-2">Estimated Cost</label>
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('total_cost',["type" => "number","label"=>false,"class" => "form-control","disabled"=>"disabled"]);?>
                                    </div>

                                    <label for="PV Cost" class="control-label col-sm-2">Average Monthly Generation</label>
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('mnt_gen',["type" => "number","label"=>false,"class" => "form-control","value"=>$averageEnrgyGenInMonthdata,"disabled"=>"disabled"]);?>
                                    </div>
                                    <label for="Qty" class="control-label col-sm-2">Savings</label>
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('Savings',["type" => "number","label"=>false,"class" => "form-control","value"=>$savings,"disabled"=>"disabled"]);?>
                                    </div>
                                    <label for="PV Cost" class="control-label col-sm-2">Payback in Years</label>
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('payback',["type" => "number","label"=>false,"class" => "form-control","value"=>$payBack,"disabled"=>"disabled"]);?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="height: 450px">
                                <div id="year_data_chart" class="year_chart col-md-12" style="text-align: center"></div>
                            </div>
                            <?= $this->Form->button(__('Save & Back To Project'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left','name' => "step_5"]); ?><br/>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var selectid1 = 1;
    var selectid2 = 1;
    var clonerow = '';

    $("#rate ,#qty,#amt,#inv_rate,#inv_qty,#inv_amt,#bqty,#bosrs,#oqty,#ors,#tax").keyup(function(event) { 
       var len= $(this).val().length;  
        if(len>=0)
        {
             $('#lumpsumcost').hide();
             $('#lumpsum_cost').val('');
        }
        
    });
    $("#lumpsum_cost").keyup(function(event) { 
       var len= $(this).val().length;  
        if(len>=0)
        {
             $('#customFields1').hide();
             $('#tax_total').hide();
             $('#rate ,#qty,#amt,#inv_rate,#inv_qty,#inv_amt,#bqty,#bosrs,#oqty,#ors,#tax').val('');
             $('#total').val('');
        }
        
    });
    
    if($("#rowId").attr('id') == 'rowId') {
        clonerow = $("#rowId").clone();
    }
    $('.addmore').click(function(){
        $("#com_others").show();
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
        $(clonerow2).find(".fa-remove").attr("onclick",'javascript:$(this).parent().parent().parent().parent().remove();');
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
function totalcost()
{
    var pvq=0;
    var par=0;
    var pamt=0;var pvtotal=0;
    var pvr=document.getElementById('rate').value;
    var pvq=document.getElementById('qty').value;
    var pvamt=document.getElementById('amt').value;
    var pvtotal =pvr * pvq * pvamt;

    var invterrate=0;
    var invterqty=0;
    var invteramt=0;
    var invetertotal=0;
    var invterrate=document.getElementById('inv_rate').value;
    var invterqty=document.getElementById('inv_qty').value;
    var invteramt=document.getElementById('inv_amt').value;
    var invetertotal= invterrate * invterqty * invteramt;

    var bosq=0;
    var bosa=0;
    var bostotal=0;

    var bosq=document.getElementById('bqty').value;
    var bosa=document.getElementById('bosrs').value;
    var bostotal=bosq * bosa;
    var otherq=0;var othera=0;var othertotal=0;

    var otherq=document.getElementById('oqty').value;
    var othera=document.getElementById('ors').value;
    var othertotal=otherq * othera;

    var taxes=0;
    var taxes=document.getElementById('tax').value * 1;

    $('#total').val(pvtotal + invetertotal + bostotal + othertotal + taxes);
}
           
var debt_val='<?php echo $Commercial->debt ;?>';
var int_val='<?php echo $Commercial->interest;?>';
var dept_val='<?php echo $Commercial->deprecation ;?>';
var om_val='<?php echo $Commercial->om_cost;?>';
var cuf_val='<?php echo $Commercial->cuf ;?>';
var loan_val='<?php echo $Commercial->loanternure;?>';
function restore_value(){
    $('#debt').val(debt_val);
    $('#interest').val(int_val);
    $('#deprecation').val(dept_val);
    $('#om_cost').val(om_val);
    $('#cuf').val(cuf_val);
    $('#loanternure').val(loan_val);
 }
google.charts.load("current", {packages:['corechart']});
google.charts.setOnLoadCallback(yearDrawChart);

function yearDrawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Energy', 'Year'],
        <?php echo $yearChart; ?>
    ]);
    var view = new google.visualization.DataView(data);
    var options = {
        title: "Payback Chart",
        width: '100%',
        height: '100%',
        bar: {groupWidth: "60%"},
        colors: ['#FFCB29'],
        legend: { position: "none" },
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("year_data_chart"));
    chart.draw(view, options);
}

</script>
