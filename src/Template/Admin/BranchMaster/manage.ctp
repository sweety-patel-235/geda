<style>
.input.checkbox{
    position: absolute;
}
.discom_master > span{
    padding: 30px;
}
</style>
<div class="grid_12">
<div class="box">
	<div class="content">
    <?php
    	$blnAddRemoveAdminEmailRights = false;
    	$blnAddRemoveAdminEmailRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS);
        echo $this->Form->create($BranchMasters,array('class'=>'form-horizontal'));
    ?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i><?php echo $mode; ?> Branch Master
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <legend>Basic Information</legend>
                        </div>
                        <?php if($is_main == 0) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Main Branch</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->select('BranchMasters.parent_id',$main_branch, array('label' => false,'empty'=>'-select main branch-','class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Title</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('BranchMasters.title', array('label' => false,'class'=>'form-control','placeholder'=>'Title'));?>
                                </div>
                            </div>
                        </div>
                        <?php if($is_main == 0){ ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('BranchMasters.city', array('label' => false,'class'=>'form-control','placeholder'=>'City'));?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Area</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('BranchMasters.area', array('label' => false,'class'=>'form-control','placeholder'=>'Area'));?>
                                </div>
                            </div>
                        </div>
                        <?php } else if ($is_main == 1){ ?>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">State</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->select('BranchMasters.state',$state_list ,array('label' => false,'class'=>'form-control','empty'=>'-select state-','placeholder'=>'Area'));
                                //echo $BranchMasters->errors('state');exit;
                                    //echo $this->BranchMasters->error('state');
                                if(!empty($BranchMasters->errors('state')['_empty'])) {
                                ?>
                                <div class="error-message"><?php echo $BranchMasters->errors('state')['_empty']; ?></div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
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
    window.location.href=WEB_ADMIN_URL+'BranchMaster/index';
}

</script>
