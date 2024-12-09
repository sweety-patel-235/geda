<div class="grid_12">
<div class="box">
    <div class="content">
    <?php echo $this->Form->create($Users,array('action' => '/change_password','class'=>'form-horizontal','autocomplete'=>'off')); ?>        
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Change Password
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <?php echo $this->Form->input('Users.password', array('label' => false,'div'=>false,'type'=>'password','class'=>'form-control','placeholder'=>'Enter Password'));
                                    ?>
                                </div>
                            </div>                                
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <?php echo $this->Form->input('Users.confirm_password', array('label' => false,'div'=>false,'type'=>'password','class'=>'form-control','placeholder'=>'Enter Confirm Password'));
                                    ?>
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
$("#users-password").val('');
function goback() {
    window.location.href=WEB_ADMIN_URL+'users/index';
}
</script>
