<div class="grid_12">
    <div class="box">
        <div class="content">
            <?php echo $this->Form->create($Parameter,array('name'=>'formmain','id'=>'formmain','class'=>'form-horizontal')); ?>
            <div class="portlet box blue-madison ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i> 
                        <?php $PATE_HEADING = (empty($para_id))?'Add Parameter':'Edit Parameter'; ?>
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
                                    <label class="col-md-3 control-label">Parent Parameter</label>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->select('Parameters.para_parent_id',$arrParentParaList, array('label' => false,'empty' => '--SELECT PARENT--','div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Parent Parameter'));
                                        ?>
                                        <?php echo $this->Form->error('para_parent_id'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Parameter</label>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Parameters.para_value', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Parameter'));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->textarea('Parameters.para_desc', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Description'));
                                        ?>
                                        <?php echo $this->Form->error('para_desc'); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Status</label>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->select('Parameters.status',$para_status, array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Status'));
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
            <?php echo $this->Form->end(); ?>
        </div>
    </div> <!-- End of .box -->
</div>

<script type="text/javascript">
function goback()
{
    window.location.href=WEB_ADMIN_URL+'Parameters/index';
}
</script>