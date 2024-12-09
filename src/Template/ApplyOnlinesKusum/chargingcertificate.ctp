<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
    <div class="alert alert-warning">
            <strong>Notice!</strong>
            <ul>
                <li style="font-size: 1.0em;">If the Phase of the Solar Inverter is changed in this form then the change will be reflected in the Application Form as well.</li>
            </ul>
        </div>
    <div class="box">
        <div class="content">
            <div class="portlet box blue-madison applyonline-viewmain fesibility-report">
                <div class="row">
                    <h2 class="col-md-9 mb-sm mt-sm">
                        <small>Meter Installation Report</small>
                    </h2>
                </div>
                <?php 
                    echo $this->Form->create($ChargingCertificate, ['id'=>'chargingcertificate','name'=>'chargingcertificate','method'=>'post','type' => 'post','url' => 'apply-onlines/chargingcertificate/'.$id]);
                    echo $this->Form->input('id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$rid]);
                    $this->Form->templates(['inputContainer' => '{{content}}']);
                ?>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="fesibility-report form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Date and Time of Submission of Report</label>
                                    <?php echo $ChargingCertificate->created;?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Name of Consumer</label></div>
                                        <div class="col-md-6">
                                            <?php echo $ApplyOnlines->name_of_consumer_applicant;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Consumer No</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->consumer_no;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Sanctioned /Contract Load</label></div>
                                        <div class="col-md-6">
                                            <?php echo $ApplyOnlines->sanction_load_contract_demand;?>kW
                                        </div>
                                    </div>
                                    <div class="row hide">
                                        <div class="col-md-6"><label>Sanctioned Load</label></div>
                                        <div class="col-md-6">
                                            <?php echo $fesibility->sanction_load;?>kW
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Connectivity Phase</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->select('sanctioned_load_phase',$phasearray,array('div' => false,'label' => false,'class'=>'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Rooftop Solar PV Capacity</label></div>
                                        <div class="col-md-6">
                                            <?php echo $ApplyOnlines->pv_capacity;?>kWp
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Solar Inverter Phase</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->select('pv_capacity_phase',$phasearray,array('div' => false,'label' => false,'class'=>'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Registration No.</label></div>
                                        <div class="col-md-6">
                                            <?php echo $ApplyOnlines->geda_application_no;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Date</label></div>
                                        <div class="col-md-6">
                                            <?php echo $applicationSubmission->created; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if(!empty($RegistrationScheme->aid)) { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>CEI Drawing Approval No.</label></div>
                                        <div class="col-md-6">
                                            <?php echo $RegistrationScheme->aid;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Date</label></div>
                                        <div class="col-md-6">
                                            <?php echo $RegistrationScheme->created; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Meter Installed Date</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('meter_installed_date',["type" => "text",'label'=>false,'id'=>'meter_installed_date',"class" => "form-control datepicker",'value'=>(isset($ChargingCertificate->meter_installed_date)?date('d-m-Y',strtotime($ChargingCertificate->meter_installed_date)):'')]);?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Agreement Date</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('agreement_date',["type" => "text",'label'=>false,'id'=>'agreement_date',"class" => "form-control datepicker",'value'=>(isset($ChargingCertificate->agreement_date)?date('d-m-Y',strtotime($ChargingCertificate->agreement_date)):'')]);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Solar Meter No.</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('solar_meter',["type" => "text",'label'=>false,'id'=>'solar_meter',"class" => "form-control"]);?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><label>Bi-directional Meter No.</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('bi_directional_meter',["type" => "text",'label'=>false,'id'=>'bi_directional_meter',"class" => "form-control"]);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg']);
                                ?>
                            </div>
                        </div>
                    </div> 
                </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
});
</script>