<div class="grid_12">
<div class="box">
	<div class="content">
    <?php
    	$blnAddRemoveAdminEmailRights = false;
    	$blnAddRemoveAdminEmailRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS);
        echo $this->Form->create($Projects,array('class'=>'form-horizontal'));
    ?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Add Project
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <legend>Basic Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->input('Projects.name', array('label' => false,'class'=>'form-control','placeholder'=>'First Name')); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address Line</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Projects.address', array('label' => false,'class'=>'form-control','placeholder'=>'Address Line 2'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Projects.city', array('label' => false,'class'=>'form-control','placeholder'=>'City'));?>
                                </div>
                            </div>
						</div>
						<div class="col-md-6 pull-left">
                            <div class="form-group">
                                <label class="col-md-3 control-label">State</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Projects.state', array('label' => false,'class'=>'form-control','placeholder'=>'State'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">ZIP code</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Projects.zip', array('label' => false,'class'=>'form-control','placeholder'=>'ZIP code'));?>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
						    <legend>Contact Information</legend>
							<div class="col-md-6 pull-left">
								<div class="form-group">
									<label class="col-md-3 control-label">Area</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.area', array('label' => false,'class'=>'form-control','placeholder'=>'Email'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Area Type</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.area_type', array('label' => false,'class'=>'form-control','placeholder'=> 'Area Type'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Customer Type</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.customer_type', array('label' => false,'class'=>'form-control','placeholder'=> 'Customer Type'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Capacity KW</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.capacity_kw', array('label' => false,'class'=>'form-control','placeholder'=> 'Capacity KW'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Estimated Cost</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.estimated_cost', array('label' => false,'class'=>'form-control','placeholder'=> 'Estimated Cost'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Estimated KWH/Year</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.estimated_kwh_year', array('label' => false,'class'=>'form-control','placeholder'=> 'Estimated KWH/Year'));?>
									</div>
								</div>
							</div>
							<div class="col-md-6 pull-left">
								<div class="form-group">
									<label class="col-md-3 control-label">Discom</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.discom_id', array('label' => false,'class'=>'form-control','placeholder'=> 'Discom'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Avg Monthly Bill</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.avg_monthly_bill', array('label' => false,'class'=>'form-control','placeholder'=> 'Avg Monthly Bill'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Contract Load</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.contract_load', array('label' => false,'class'=>'form-control','placeholder'=> 'Contract Load'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Diesel Genset KVA</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.diesel_genset_kva', array('label' => false,'class'=>'form-control','placeholder'=> 'Diesel Genset KVA'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Usage Hours</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.usage_hours', array('label' => false,'class'=>'form-control','placeholder'=> 'Usage Hours'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Estimated Saving Year</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.estimated_saving_year', array('label' => false,'class'=>'form-control','placeholder'=> 'Estimated Saving Year'));?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">OP Maintence cost/month</label>
									<div class="col-md-9">
									<?php echo $this->Form->input('Projects.op_maintence_cost_month	', array('label' => false,'class'=>'form-control','placeholder'=> 'OP Maintence cost/month'));?>
									</div>
								</div>
							</div>
                        </div>
                        <div class="col-md-12">
                            <legend>Additional Information</legend>
                            <div class="col-md-6 pull-left">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Remark</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->input('Projects.remark', array('type' => 'textarea','rows'=>4,'label' => false,'class'=>'form-control','placeholder'=>'Remark'));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Submit</button>
                        <button type="button" class="btn default" onclick="goback()">Cancel</button>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
	
<script type="text/javascript">
function goback()
{
    window.location.href=WEB_ADMIN_URL+'users/index';
}

</script>
