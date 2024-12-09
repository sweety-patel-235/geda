<div class="grid_12">
<div class="box">
    <!--div class="header">
        <img width="16" height="16" alt="" src="<?php echo IMAGE_URL?>icons/packs/fugue/16x16/ui-text-field-format.png">
        <h3>Add User</h3>
    </div-->
    <div class="content">
    <?php
        echo $this->Form->create($Users,array('action' => '/edit','class'=>'form-horizontal'));
        //echo $this->Form->create('User',array('class'=>'form-horizontal'));
        /*$blnAddRemoveAdminEmailRights = false;
        $blnAddRemoveAdminEmailRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS);
        */
    ?>
        
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Edit User
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
                        </div>
                        <div class="col-md-12">
                            <legend>Additional Information</legend>
                            <div class="col-md-6 pull-left">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">User Type</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->select('Users.usertype',$usertypes,array('label' => false,'class'=>'form-control','empty'=>'-Select User Type-','placeholder'=>'User Type')); ?>
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label class="col-md-3 control-label">Department</label>
                                    <div class="col-md-9">
                                    <?php //echo $this->Form->select('Users.department_id][',$department,array('type'=>'select','empty'=>'-Choose Department-','class'=>'form-control select2','div'=>false,'multiple'));?>
                                    </div>
                                </div>-->

                                <?php 

                                //pr($user_department);exit;
                                //pr($this);exit;
                                /*$selected_department_id = array();
                                if(isset($data['Users']['department_id'])){
                                    for($i=0;$i<count($data['Users']['department_id']);$i++){
                                        $selected_department_id[$data['Users']['department_id'][$i]] = $department[$data['Users']['department_id'][$i]];
                                    }
                                } else {
                                    for($i=0;$i<count($user_department);$i++){
                                        
                                        $selected_department_id[$user_department[$i]->department_id] = $department[$user_department[$i]->department_id];
                                           
                                    }
                                    pr($selected_department_id);
                                }*/
                                /*if(!isset($data['Users']['department_id'])){
                                    $selected_department_id = '{';
                                    for($i=0;$i<count($user_department);$i++){
                                        $selected_department_id .= $user_department[$i]->department_id.',';
                                    }
                                    $selected_department_id = rtrim($selected_department_id,',');
                                    $selected_department_id .= '}';
                                    echo $selected_department_id; ?>
                                    <script type="text/javascript">
                                        select_value = JSON.parsh('<?php echo $selected_department_id ?>');
                                        alert(select_value.length);
                                    </script>
                                    <?php
                                }*/
                                ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Department</label>
                                    <div class="col-md-9">
                                    <?php echo $this->Form->select('Users.department_id',$department,array('class'=>'form-control select2','div'=>false, 'multiple' => true));?>
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
