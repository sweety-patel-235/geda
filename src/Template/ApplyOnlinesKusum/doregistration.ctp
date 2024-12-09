<?php
    $this->Html->addCrumb('My Apply-online List','apply-online-list'); 
    $this->Html->addCrumb($pageTitle);
?>
<div class="container">
    <div class="box">
        <div class="content">
            <div class="portlet box blue-madison applyonline-viewmain fesibility-report">
                <div class="row">
                    <h2 class="col-md-9 mb-sm mt-sm">
                        <small>Application for Registration of the Sceme for Rooftop Solar PV System</small>
                    </h2>
                </div>
                <?php 
                    echo $this->Form->create($RegistrationScheme, ['type'=>'file','id'=>'form-main','url' => 'apply-onlines/do-registration/'.$id]);
                    echo $this->Form->input('id',['id'=>'id','label' => false,'type'=>'hidden','value'=>$rid]);
                    $this->Form->templates(['inputContainer' => '{{content}}']);
                ?>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="fesibility-report form-group">
                            <?php if (isset($RegistrationScheme->aid) && !empty($RegistrationScheme->aid)) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Registeration No.</label></div>
                                        <div class="col-md-6"><?php echo $RegistrationScheme->aid;?></div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Date</label></div>
                                        <div class="col-md-6"><?php echo $RegistrationScheme->created;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Name of Consumer</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->name_of_consumer_applicant;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Address Linked to Registeration</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->comunication_address;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Consumer No.</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->consumer_no;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Contact No.</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->mobile;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Email</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->email;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Application No.</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->aid;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Application Date</label></div>
                                        <div class="col-md-6"><?php echo $ApplyOnlines->created;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Letter of Approval (Reference No. & Date)</label>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if (isset($fesibility->id)) { ?>
                                                <?php echo $fesibility->aid;?> <?php echo $fesibility->created;?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Contract Demand of Consumer</label></div>
                                        <div class="col-md-6">
                                            <?php if (isset($fesibility->id)) { ?>
                                                <?php echo $fesibility->sanction_load;?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Capacity of Rooftop Solar PV system to be connected (Capacity not be exceed as approved by the JBVNL and as per RSPV Regulation 2015)</label></div>
                                        <div class="col-md-6">
                                            <?php if (isset($fesibility->id)) { ?>
                                                <?php echo (($fesibility->approved == 1)?"YES":"NO");?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Technical specifications and other particulars of solar PV module, Grid-tied Inververt and Interlocking System, etc. proposed to be installed - whether attached</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->select('tech_spec_1',array("1"=>"Yes","0"=>"No"),array('label' => false,'class'=>'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Upload</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('document_1', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document')); ?>
                                            <?php
                                                $separator = "";
                                                if (isset($RegistrationScheme->documents) && isset($RegistrationScheme->documents['tech_spec'])) {
                                                    foreach ($RegistrationScheme->documents['tech_spec'] as $key=>$tech_spec)
                                                    {
                                                        echo $separator.$this->Html->link(
                                                                                'Document '.($key + 1),
                                                                                $tech_spec['url'],
                                                                                ['class' => 'document', 'target' => '_blank']
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
                                    <div class="row">
                                        <div class="col-md-6"><label>Technical specifications and other particulars of renewable energy meter proposed to be installed - whether attached</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->select('tech_spec_2',array("1"=>"Yes","0"=>"No"),array('label' => false,'class'=>'form-control')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Upload</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('document_2', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document')); ?>
                                            <?php
                                                $separator = "";
                                                if (isset($RegistrationScheme->documents) && isset($RegistrationScheme->documents['tech_spec_1'])) {
                                                    foreach ($RegistrationScheme->documents['tech_spec_1'] as $key=>$tech_spec)
                                                    {
                                                        echo $separator.$this->Html->link(
                                                                                'Document '.($key + 1),
                                                                                $tech_spec['url'],
                                                                                ['class' => 'document', 'target' => '_blank']
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
                                    <div class="row">
                                        <div class="col-md-6"><label>Whether the Consumer opts to purchase meter himself or from Distribution Licensee</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->select('distribution_licensee',array("1"=>"Distribution Lisencee","2"=>"Himself"),array('label' => false,'class'=>'form-control')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Drawings for installing the Rooftop Solar PV System - whether attached</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->select('drawing',array("1"=>"Yes","0"=>"No"),array('label' => false,'class'=>'form-control')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Upload</label></div>
                                        <div class="col-md-6">
                                            <?php echo $this->Form->input('document_3', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document')); ?>
                                            <?php
                                                $separator = "";
                                                if (isset($RegistrationScheme->documents) && isset($RegistrationScheme->documents['drawing'])) {
                                                    foreach ($RegistrationScheme->documents['drawing'] as $key=>$tech_spec)
                                                    {
                                                        echo $separator.$this->Html->link(
                                                                                'Document '.($key + 1),
                                                                                $tech_spec['url'],
                                                                                ['class' => 'document', 'target' => '_blank']
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
                                    <div class="row">
                                        <div class="col-md-6"><label>Date of completion</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->input('date_of_completion', array('label' => false,'class'=>'form-control date-picker ','placeholder'=>'Date of completion')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Bank No.</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->input('bank_no', array('label' => false,'class'=>'form-control ','placeholder'=>'Bank Account No')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>IFSC CODE</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->input('ifsc_code', array('label' => false,'class'=>'form-control ','placeholder'=>'IFSC CODE')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Name of Bank</label></div>
                                        <div class="col-md-6"><?php echo $this->Form->input('bank_name', array('label' => false,'class'=>'form-control ','placeholder'=>'Name of Bank')); ?></div>
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
                                        echo '<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg">';
                                        echo $this->Html->link('Back To List',['controller'=>'ApplyOnlines','action' => 'applyonline_list']);
                                        echo '</span>';
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
<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$('.date-picker').datepicker({format: 'MM-dd-yyyy'});
</script>