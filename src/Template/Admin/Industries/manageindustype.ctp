<div class="grid_12">
    <div class="box">
        <div class="content">
            <?php echo $this->Form->create($Industries,array('name'=>'formmain','id'=>'formmain','class'=>'form-horizontal')); ?>
            <div class="portlet box blue-madison ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i> 
                        <?php $PATE_HEADING = (empty($para_id))?'Add industry Type':'Edit industry Type'; ?>
                        <?php echo $PATE_HEADING;?>
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
                                    <label class="col-md-3 control-label">Industries Name</label>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Industries.industry_name', array('label' => false,'div'=>false,'class'=>'form-control','placeholder'=>'Parameter Value'));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Industries.description', array('label' =>false,'div'=>false,'rows'=>9,'type'=>'textarea','class'=>'form-control'));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Status</label>
                                    <div class="col-md-9">
                                        <?php 
                                         echo $this->Form->select('Industries.status',$para_status, array('label' =>false,'div'=>false,'rows'=>2,'type'=>'textarea','class'=>'form-control'));
                                        ?>
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
            <?php echo $this->Form->end(); ?>
        </div>
    </div> <!-- End of .box -->
</div>
<script type="text/javascript">
function goback()
{
    window.location.href=WEB_ADMIN_URL+'Industries/index';
}

</script>
