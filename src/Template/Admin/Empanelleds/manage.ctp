<div class="grid_12">
<div class="box">
	<div class="content">
    <?php
    	$blnAddRemoveAdminEmailRights = false;
    	$blnAddRemoveAdminEmailRights = $Userright->checkadminrights($Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS);
        echo $this->Form->create($Empanelled,array('class'=>'form-horizontal','type'=>'file'));
    ?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Add Empanelled
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
	                    <div class="col-md-12">
	                    	<legend>Basic Information</legend>
	                    </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Agency</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->input('Empanelled.agency', array('label' => false,'class'=>'form-control','placeholder'=>'First Name')); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Empanelled Level</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->select('Empanelled.level', $arrEmpLevel,array('label' => false,'class'=>'form-control','placeholder'=>'Address Line 2'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Empanelled Logo</label>
                                <div class="col-md-9">
                                 <?php echo $this->Form->input('Empanelled.image_path_logo',array('id'=>'myFile','label' => false,'div'=>false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload Image','style'=>'padding: 0px !important; margin-bottom: 0px !important;line-height: 0;')); ?>	

                                </div>
                            </div>
						</div>
						<div class="col-md-6 pull-left">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-9">
                                <?php echo $this->Form->select('Empanelled.status',$arrStatus, array('label' => false,'class'=>'form-control','placeholder'=>'State'));?>
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
$('#myFile').bind('change', function() {
  if(this.files[0].size > 2097152)
  {
	  alert('Please Select File Less than 2MB');
	  this.value='';
  }
});

</script>
