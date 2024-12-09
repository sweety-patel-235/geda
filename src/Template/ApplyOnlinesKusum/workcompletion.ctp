<?php
    $this->Html->addCrumb('My Apply-online List','apply-online-list'); 
    $this->Html->addCrumb($pageTitle);
?>
<style type="text/css">
.custom-header {
    font-size: 12px;
    width: 100px;
    vertical-align: middle !important;
}
.custom-header-2 {
    font-size: 12px;
    width: 120px;
    vertical-align: middle !important;
}
</style>
<div class="container">
    <div class="box">
        <div class="content">
            <div class="portlet box blue-madison applyonline-viewmain fesibility-report">
                <div class="row">
                    <h2 class="col-md-9 mb-sm mt-sm">
                        <small>Work Completion Report</small>
                    </h2>
                </div>
                <?php 
                    echo $this->Form->create($workcompletion, ['id'=>'form-main','method'=>'post','type'=>'file','url' => 'apply-onlines/workcompletion/'.$id]);
                    echo $this->Form->input('id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$rid]);
                    $this->Form->templates(['inputContainer' => '{{content}}']);
                ?>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="fesibility-report form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Name of Consumer</label></div>
                                        <div class="col-md-6">
                                            <?php echo $ApplyOnlines->name_of_consumer_applicant;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Application Ref. No.</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->aid;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Consumer No</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->consumer_no;?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Technology Description & System Design/ Specification</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">PV Modules</th>
                                        <th scope="col" class="text-center">Capacity/ Power (Wp)</th>
                                        <th scope="col" class="text-center">No. of Modules</th>
                                        <th scope="col" class="text-center">Technology</th>
                                    </tr>
                                </thead>
                                <?php
                                    $rowcnt = 5;
                                    $technologyspecs = isset($workcompletion->techspec)?unserialize($workcompletion->techspec):array();
                                    $cumulativepvmodules = 0;
                                    for($row=0;$row<$rowcnt;$row++) {
                                        $pvmodule       = "";
                                        $modules        = "";
                                        $technology     = "";
                                        if (isset($technologyspecs[$row])) {
                                            if (isset($technologyspecs[$row][0])) {
                                                $pvmodule = $technologyspecs[$row][0];
                                            }
                                            if (isset($technologyspecs[$row][1])) {
                                                $modules = $technologyspecs[$row][1];
                                            }
                                            if (isset($technologyspecs[$row][2])) {
                                                $technology = $technologyspecs[$row][2];
                                            }
                                        }
                                        $cumulativepvmodules += ($pvmodule*$modules);
                                ?>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="custom-header text-center">&nbsp;</th>
                                        <td>
                                            <?php echo $this->Form->input('techspec['.$row.'][]',array('div' => false,'id' =>'techspec-'.$row.'-'.($row),'label' => false,'class'=>'c1 form-control','value'=>$pvmodule)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('techspec['.$row.'][]',array('div' => false,'id' =>'techspec-'.$row.'-'.($row+1),'label' => false,'class'=>'c2 form-control','value'=>$modules)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->select('techspec['.$row.'][]',$ModuleTypes,array('div' => false,'id' =>'techspec-'.$row.'-'.($row+2),'label' => false,'class'=>'form-control','value'=>$technology)); ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php
                                    }
                                    if ($cumulativepvmodules > 0) {
                                        $cumulativepvmodules = ($cumulativepvmodules/1000);
                                    }
                                    $cumulativepvmodules = ($cumulativepvmodules > 0)?$cumulativepvmodules:"";
                                ?>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><label>
                                        Cumulative Capacity of PV Modules (kWp)</label>
                                    </div>
                                    <div class="col-md-9 cumulative-pv-modules">
                                        <?php echo $cumulativepvmodules;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><label>Upload</label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('document_1', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document')); ?>
                                        <?php
                                            $separator = "";
                                            if (isset($workcompletion->documents) && isset($workcompletion->documents['tech_spec'])) {
                                                foreach ($workcompletion->documents['tech_spec'] as $key=>$tech_spec)
                                                {
                                                    echo $separator.$this->Html->link(
                                                                            'Document '.($key + 1),
                                                                            $tech_spec['url'],
                                                                            ['class' => 'document',
                                                                                'target' => '_blank']
                                                                        );
                                                    $separator = " | ";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Inverters</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Inverters</th>
                                        <th scope="col" class="text-center">Capacity/ Power (W)</th>
                                        <th scope="col" class="text-center">Nos.</th>
                                        <th scope="col" class="text-center">Technology</th>
                                        <th scope="col" class="text-center">Make</th>
                                    </tr>
                                </thead>
                                <?php
                                    $rowcnt = 5;
                                    $cumulativepvmodules = 0;
                                    $invertors = isset($workcompletion->invertors)?unserialize($workcompletion->invertors):array();
                                    for($row=0;$row<$rowcnt;$row++) {
                                        $pvmodule       = "";
                                        $invertorcnt    = "";
                                        $technology     = "";
                                        $make           = "";
                                        if (isset($invertors[$row])) {
                                            if (isset($invertors[$row][0])) {
                                                $pvmodule = $invertors[$row][0];
                                            }
                                            if (isset($invertors[$row][1])) {
                                                $invertorcnt = $invertors[$row][1];
                                            }
                                            if (isset($invertors[$row][2])) {
                                                $technology = $invertors[$row][2];
                                            }
                                            if (isset($invertors[$row][3])) {
                                                $make = $invertors[$row][3];
                                            }
                                        }
                                        $cumulativepvmodules += ($pvmodule);
                                ?>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="custom-header text-center">&nbsp;</th>
                                        <td>
                                            <?php echo $this->Form->input('invertors['.$row.'][]',array('div' => false,'id' =>'invertors-'.$row.'-'.$row,'label' => false,'class'=>'c3 form-control','value'=>$pvmodule)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('invertors['.$row.'][]',array('div' => false,'id' =>'invertors-'.$row.'-'.($row+1),'label' => false,'class'=>'c4 form-control','value'=>$invertorcnt)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->select('invertors['.$row.'][]',$InverterTypes,array('div' => false,'id' =>'invertors-'.$row.'-'.($row+2),'label' => false,'class'=>'form-control','value'=>$technology)); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Form->input('invertors['.$row.'][]',array('div' => false,'id' =>'invertors-'.$row.'-'.($row+3),'label' => false,'class'=>'form-control','value'=>$make)); ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php
                                    }
                                    if ($cumulativepvmodules > 0) {
                                        $cumulativepvmodules = ($cumulativepvmodules/1000);
                                    }
                                    $cumulativepvmodules = ($cumulativepvmodules > 0)?$cumulativepvmodules:"";
                                ?>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><label>
                                        Capacity/Power of PCU/Inverters (kW)</label>
                                    </div>
                                    <div class="col-md-9 cumulative-pv-modules-2">
                                        <?php echo $cumulativepvmodules;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Grid Connectivity Level</label>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $this->Form->input('connectivity_level', array('label' => false,'class'=>'form-control','placeholder'=>'Grid Connectivity Level')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Grid Connectivity Level Phase</label>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $this->Form->input('connectivity_level_phase', array('label' => false,'class'=>'form-control','placeholder'=>'Grid Connectivity Level Phase')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Grid Connectivity Level Voltage</label>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $this->Form->input('connectivity_level_voltage', array('label' => false,'class'=>'form-control','placeholder'=>'Grid Connectivity Level Voltage')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Latitude and Longitute Info</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Latitude</label>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo "Latitude"; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Longitute</label>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo "Longitute"; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3"><label>Upload</label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('document_2', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document')); ?>
                                        <?php
                                            $separator = "";
                                            if (isset($workcompletion->documents) && isset($workcompletion->documents['tech_spec_1'])) {
                                                foreach ($workcompletion->documents['tech_spec_1'] as $key=>$tech_spec)
                                                {
                                                    echo $separator.$this->Html->link(
                                                                            'Document '.($key + 1),
                                                                            $tech_spec['url'],
                                                                            ['class' => 'document',
                                                                                'target' => '_blank']
                                                                        );
                                                    $separator = " | ";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    if (isset($customer_type) && in_array(strtolower($customer_type),$customer_types)) {
                                        echo $this->Form->button('Submit', ['type' => 'submit', 'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg']);
                                    } else {
                                        echo $this->Html->link('Back To List',['controller'=>'ApplyOnlines','action' => 'applyonline_list']);
                                    }
                                ?>
                            </div>
                        </div>
                    </div> 
                </div>
                <?php $this->Form->end(['data-type' => 'hidden']); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(".c2").blur(function() {
        var total = 0
        $( ".c1" ).each(function( index ) {
            var pv  = $(this).val();
            var mod = $("#techspec-"+index+"-"+(index+1)).val();
            var v3  = parseFloat(parseFloat(pv) * parseFloat(mod));
            if (v3 > 0) {
                total += v3;
            }
        });
        total = ((total > 0)?parseFloat(total/1000).toFixed(1):"");
        $(".cumulative-pv-modules").html(total);
    });
    $(".c3").blur(function() {
        var total = 0
        $( ".c3" ).each(function( index ) {
            var pv  = $(this).val();
            var mod = $("#invertor-"+index+"-"+(index+1)).val();
            var v3  = parseFloat(parseFloat(pv));
            if (v3 > 0) {
                total += v3;
            }
        });
        total = ((total > 0)?parseFloat(total/1000).toFixed(1):"");
        $(".cumulative-pv-modules-2").html(total);
    });
});
</script>