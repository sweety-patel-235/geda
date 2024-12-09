<div class="grid_12">
<div class="box">
	<div class="content">
    <?php
    	$blnAddRemoveAdminEmailRights = false;
    	$blnAddRemoveAdminEmailRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS);
        echo $this->Form->create($Customers,array('class'=>'form-horizontal'));
    ?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Add User
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
                                    <?php echo $this->Form->input('Customers.name', array('label' => false,'class'=>'form-control','placeholder'=>'First Name')); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address Line 2</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.address2', array('label' => false,'class'=>'form-control','placeholder'=>'Address Line 2'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.city', array('label' => false,'class'=>'form-control','placeholder'=>'City'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">State</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.state', array('label' => false,'class'=>'form-control','placeholder'=>'State'));?>
                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">ZIP code</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.zip', array('label' => false,'class'=>'form-control','placeholder'=>'ZIP code'));?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pull-left">
                            <legend>Contact Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.email', array('label' => false,'class'=>'form-control','placeholder'=>'Email'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Mobile</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.mobile', array('label' => false,'class'=>'form-control','placeholder'=> 'Mobile'));?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pull-left">
                            <legend>Login Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.password',array('label' => false,'class'=>'form-control','type'=>'Password','placeholder'=>'Password'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Confirm Password</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Customers.confirmpassword',array('label' => false,'class'=>'form-control','type'=>'password','placeholder'=>'Confirm Password')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <legend>Additional Information</legend>
                            <div class="col-md-6 pull-left">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">User Type</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->select('Customers.usertype',$usertypes,array('label' => false,'class'=>'form-control','empty'=>'-Select User Type-','placeholder'=>'User Type')); ?>
                                    <?php echo $this->Form->error('usertype'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pull-left">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Remark</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->input('Customers.remark', array('type' => 'textarea','rows'=>4,'label' => false,'class'=>'form-control','placeholder'=>'Remark'));?>
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
