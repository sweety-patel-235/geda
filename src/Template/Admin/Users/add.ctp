<div class="grid_12">
<div class="box">
	<div class="content">
    <?php
    	$blnAddRemoveAdminEmailRights = false;
    	$blnAddRemoveAdminEmailRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS);
        echo $this->Form->create($Users,array('class'=>'form-horizontal','type'=>'file'));
    ?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Add User
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <legend>Profile Picture</legend>
                            <div class="form-group">
                                <div class="col-md-9">
                                <?php echo $this->Form->input('User.profile_pic', array('label' => false,'class'=>'form-control','type'=>'file')); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php 
                                        echo '<img src="/img/default-img.png" />';
                                    ?>
                                </div>
                            </div>
                            <legend>Basic Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label">First Name</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->input('Users.firstname', array('label' => false,'class'=>'form-control','placeholder'=>'First Name')); ?>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Last Name</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.lastname', array('label' => false,'class'=>'form-control','placeholder'=>'Last Name')); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address Line 1</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.address1', array('label' => false,'class'=>'form-control','placeholder'=>'Address Line 1'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address Line 2</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.address2', array('label' => false,'class'=>'form-control','placeholder'=>'Address Line 2'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.city', array('label' => false,'class'=>'form-control','placeholder'=>'City'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">State</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.state', array('label' => false,'class'=>'form-control','placeholder'=>'State'));?>
                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Country</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.country', array('label' => false,'class'=>'form-control','placeholder'=>'Country'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">ZIP code</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.zip', array('label' => false,'class'=>'form-control','placeholder'=>'ZIP code'));?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pull-left">
                            <legend>Login Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Username</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.username', array('label' => false,'class'=>'form-control','placeholder'=>'Username'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.password',array('label' => false,'class'=>'form-control','type'=>'Password','placeholder'=>'Password'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Confirm Password</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.confirmpassword',array('label' => false,'class'=>'form-control','type'=>'password','placeholder'=>'Confirm Password')); ?>
                                </div>
                            </div>
                            <legend>Contact Information</legend>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.email', array('label' => false,'class'=>'form-control','placeholder'=>'Email'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Mobile</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.mobile', array('label' => false,'class'=>'form-control','placeholder'=> 'Mobile'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Home Phone</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.homephone', array('label' => false,'class'=>'form-control','placeholder'=>'Home Phone'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Designation</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('Users.designation', array('label' => false,'class'=>'form-control','placeholder'=>'Designation'));?>
                                </div>
                            </div>
                        </div>                        
                        <div class="col-md-12">
                            <legend>Additional Information</legend>
                            <div class="col-md-6 pull-left">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">User Type</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->select('Users.usertype',$usertypes,array('label' => false,'class'=>'form-control','empty'=>'-Select User Type-','placeholder'=>'User Type')); ?>
                                    <?php echo $this->Form->error('usertype'); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Department</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->select('Users.department_id',$department,array('type'=>'select','class'=>'form-control select2','div'=>false,'multiple' => true));?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pull-left">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Remark</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->input('Users.remark', array('type' => 'textarea','rows'=>4,'label' => false,'class'=>'form-control','placeholder'=>'Remark'));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-5 col-md-6">
                            <button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                            <button type="button" onclick="goback()" class="btn"><i class="fa fa-close"></i> Cancel</button>
                        </div>
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
