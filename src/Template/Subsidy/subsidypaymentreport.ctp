<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<?php $this->Html->addCrumb($pagetitle); ?>
<?php echo $this->Form->create('Subsidy',array("id"=>"formmain","name"=>"searchSubsidyClaims",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
    <div class="container project-leads">
        <div class="col-md-12 MessageBlock"></div>
        <div class="col-md-12">
            <div class="form-body">
                <div class="row col-md-12">
                    <div class="col-md-6">
                        <?php 
//,'multiple'=>'true','data-placeholder'=>'-Select Installers-'
                        echo $this->Form->select('installer_id',$Installers,array('label' => false,'class'=>'form-control chosen-select','id'=>'installer_id','multiple'=>'true','data-placeholder'=>'-Select Installers-')); ?>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn green" id="searchBtn"><i class="fa fa-check"></i> Search</button>
                        <button type="button" class="btn default" id="resetBtn"><i class="fa fa-refresh"></i> Reset</button>
                        <?php echo $this->Form->hidden('exporttype',array("value"=>"","id"=>"exporttype")); ?>
                        <button type="button" class="btn green" id="exportBtn"><i class="fa fa-file-excel-o"></i> Export</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <?php  echo $this->Flash->render('cutom_admin'); ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue-madison noborder">
                <table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
                    <thead>
                    <tr>
                        <th class="">Sr No.</th>
                        <th class="">Request No.</th>
                        <th class="">GEDA Reg. No.</th>
                        <th class="">PV Capacity (kW)</th>
                        <th class="">MNRE Subsidy (&#8377;)</th>
                        <th class="">State Subsidy (&#8377;)</th>
                        <th class="">Deduction (&#8377;)</th>
                        <th class="">NET Payable By GEDA (&#8377;)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $TotalStateSubsidy  = 0;
                        $TotalMnreSubsidy   = 0;
                        $TotalPvCapacity    = 0;
                        $TotalDeduction     = 0;
                        $TotalNetPayable    = 0;
                        foreach($arrSubsidyPayment as $key=>$val)
                        {
                            $subsidy_data   = $Projects->calculatecapitalcostwithsubsidy($val['projects']['recommended_capacity'],$val['projects']['estimated_cost'],$val['projects']['state'],$val['projects']['customer_type'],true,$val['apply_onlines']['social_consumer']);
                            if ($subsidy_data['state_subcidy_type'] == 0) {
                                $STATE_SUBSIDY          = $subsidy_data['state_subsidy']."%";
                                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$subsidy_data['state_subsidy_amount']:"0.00";
                            } else {
                                $STATE_SUBSIDY          = ($subsidy_data['state_subsidy'] > 0)?$subsidy_data['state_subsidy']:"-";
                                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$subsidy_data['state_subsidy_amount']:"0.00";
                            }
                            if ($subsidy_data['central_subcidy_type'] == 0) {
                                $CENTRAL_SUBSIDY            = $subsidy_data['central_subsidy']."%";
                                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$subsidy_data['central_subsidy_amount']:"0.00";
                            } else {
                                $CENTRAL_SUBSIDY            = ($subsidy_data['central_subsidy'] > 0)?$subsidy_data['central_subsidy']:"-";
                                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$subsidy_data['central_subsidy_amount']:"0.00";
                            } 
                            if($val['apply_onlines']['social_consumer']==1 || $val['apply_onlines']['common_meter']==1)
                            {
                                $STATE_SUBSIDY_AMOUNT       = 0;
                            }
                            $TotalSubsidy                   = $CENTRAL_SUBSIDY_AMOUNT + $STATE_SUBSIDY_AMOUNT;
                            $TotalPvCapacity                = $TotalPvCapacity + $val['projects']['recommended_capacity'];
                            $Deduction                      = $Subsidy->calculateDeduction($TotalPvCapacity,$val['installer_category_mapping']['category_id'],$val['projects']['estimated_cost']);
                            $NetPayable                     = $TotalSubsidy - $Deduction;
                            $TotalMnreSubsidy               = $TotalMnreSubsidy + $CENTRAL_SUBSIDY_AMOUNT;
                            $TotalStateSubsidy              = $TotalStateSubsidy + $STATE_SUBSIDY_AMOUNT;
                            $TotalDeduction                 = $TotalDeduction + $Deduction;
                            $TotalNetPayable                = $TotalNetPayable + $NetPayable;
                            ?>
                            <tr>
                                <td><?php echo $key+1;?></td>
                                <td><?php echo $val['subsidy_claim_requests']['request_no'];?></td>
                                <td><?php echo $val['apply_onlines']['geda_application_no'];?></td>
                                <td align="center" class="aha-text-center"><?php echo $val['projects']['recommended_capacity'];?></td>
                                <td align="right" class="aha-text-right"><?php echo _FormatNumberV2($CENTRAL_SUBSIDY_AMOUNT);?></td>
                                <td align="right" class="aha-text-right"><?php echo _FormatNumberV2($STATE_SUBSIDY_AMOUNT);?></td>
                                <td align="right" class="aha-text-right"><?php echo ($Deduction>0)?_FormatNumberV2($Deduction):"0.00";?></td>
                                <td align="right" class="aha-text-right"><?php echo _FormatNumberV2($NetPayable);?></td>
                            </tr>
                            <?php
                            
                        }
                        ?>
                        <tr>
                            <td align="right" class="aha-text-right" colspan="3"><strong>Total</strong></td>
                            <td align="center" class="aha-text-center"><strong><?php echo $TotalPvCapacity;?></strong></td>
                            <td align="right" class="aha-text-right"><strong><?php echo _FormatNumberV2($TotalMnreSubsidy);?></strong></td>
                            <td align="right" class="aha-text-right"><strong><?php echo _FormatNumberV2($TotalStateSubsidy);?></strong></td>
                            <td align="right" class="aha-text-right"><strong><?php echo ($TotalDeduction>0)?_FormatNumberV2($TotalDeduction):"0.00";?></strong></td>
                            <td align="right" class="aha-text-right"><strong><?php echo _FormatNumberV2($TotalNetPayable);?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
        $("#searchBtn").click(function() {
            $("#exporttype").val("");
            $("#formmain").submit();
        });
        $("#exportBtn").click(function() {
            $("#exporttype").val("csv");
            $("#formmain").submit();
        });
        $("#resetBtn").click(function() {
            window.location.reload();
        });
    });
</script>

