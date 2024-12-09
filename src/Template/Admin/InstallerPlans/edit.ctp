<div class="grid_12">
<div class="box">
    <div class="content">
    <?php echo $this->Form->create($InstallerPlans,array('class'=>'form-horizontal','type'=>'file'));?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Edit Installer Plan
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                                                
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Plan Name</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('InstallerPlans.plan_name', array('type' => 'textbox','rows'=>4,'label' => false,'class'=>'form-control','placeholder'=>'Plan Name'));?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">Plan Price</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('InstallerPlans.plan_price', array('type' => 'textbox','rows'=>4,'label' => false,'class'=>'form-control','placeholder'=>'Plan Price'));?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">User Limit</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->input('InstallerPlans.user_limit', array('type' => 'textbox','rows'=>4,'label' => false,'class'=>'form-control','placeholder'=>'User Limit'));?>
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
    window.location.href=WEB_ADMIN_URL+'InstallerPlans/index';
}

</script>
