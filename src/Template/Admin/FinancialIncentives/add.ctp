<div class="grid_12">
<div class="box">
    <div class="content">
        <?php echo $this->Form->create($FinancialIncentives,array('class'=>'form-horizontal')); ?>
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Add Financial Incentives
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">State</label>
                                <div class="col-md-10">
                                    <?php echo $this->Form->input('FinancialIncentives.state', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'State Name'));
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-10">
                                    <?php echo $this->Form->select('FinancialIncentives.status',$para_status, array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Status'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Net Metering</label>
                                <div class="col-md-10">
                                    <?php echo $this->Form->input('FinancialIncentives.netmetering', array('label' => false,'div'=>false,'type'=>'textarea','class'=>'edittextarea form-control','placeholder'=>'Net Metering'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Financial Incentive</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->input('FinancialIncentives.incentive', array('label' => false,'div'=>false,'type'=>'textarea','class'=>'edittextarea form-control','placeholder'=>'Incentive'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Other Title</label>
                                <div class="col-md-10">
                                    <?php echo $this->Form->input('FinancialIncentives.other_title', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Title'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Additional Info</label>
                                <div class="col-md-10">
                                    <?php echo $this->Form->input('FinancialIncentives.other_text', array('label' => false,'div'=>false,'type'=>'textarea','class'=>'edittextarea form-control','placeholder'=>'Additional Information'));
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
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript">
function goback() {
    window.location.href=WEB_ADMIN_URL+'financialIncentives/index';
}
$(document).ready(function(){

   tinymce.init({
                    selector: '.edittextarea',
                    height: 250,
                    theme: 'modern',
                    plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
                    toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
                    content_css: [
                        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    ]
                });
});
</script>