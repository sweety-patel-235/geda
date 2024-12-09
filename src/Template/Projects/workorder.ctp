<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
.rowcat .col-md-6 {
    border: 1px solid #c1c1c1;
}
.rowcat .control-label {
    text-align: right;
}
.rowcat1 .row {
    border: 1px solid #c1c1c1;
    padding: 7px;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container-fluid">
    <div class="col-md-12">
        <div class="modal-body">
            
            <?= $this->Form->create($commdata,['name'=>'workorder','id'=>'workorder','enctype'=>"multipart/form-data"]);
            echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>
            <?php
                if(!$fesibility_flag)
                {
                  ?>
                  <div class="form-group col-sm-12 text-center"> 
                    <label class="btn-style" style="color:#FFCC29;">Fesibility still pending.</label>
                  </div>
                  <?php
                }
                elseif(!$can_start_work)
                {
                  ?>
                  <div class="form-group col-sm-12 text-center"> 
                    <label class="btn-style" style="color:#FFCC29;">Fesibility payment not approve.</label>
                  </div>
                  <?php
                }
            ?>
            <div class="form-group hide">
                <label class="control-label col-sm-2">Project Id</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('project_id',["type" => "text",'label' => false,"class" => "form-control",'value'=>$project_id,"readonly"]);?>
                </div>
                <label class="control-label col-sm-2">Project Name</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('project_name',["type" => "text",'label' => false,"class" => "form-control",'value'=>(isset($project_name)?$project_name:''),"readonly"]);?>
                </div>
                
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Capacity(kW)</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('Capacity',["type" => "text",'label' => false,"class" => "form-control"]);?>
                </div>
                <label class="control-label col-sm-2">Work Order No</label>
                 <?php $error_class_for_name_prefix = '';
                            if(isset($WorkorderErrors['workorder_number']) && isset($WorkorderErrors['workorder_number']['_empty']) && !empty($WorkorderErrors['workorder_number']['_empty'])){ $error_class_for_name_prefix ='has-error'; } ?>
                <div class="col-sm-3 <?php echo $error_class_for_name_prefix;?>">

                    <?php echo $this->Form->input('workorder_number',["type" => "text",'label' => false,"class" => "form-control"]);?>
                </div>
               
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Work Order Date</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('work_date',["type" => "text",'label' => false,"class" => "form-control datepicker","value"=>$wo_date]);?>
                </div>
                <label class="control-label col-sm-2">MoU with between Consumer and Installer</label>
                <div class="col-sm-3">
                    <div class="file-loading">
                        <input id="workorder_image" name="workorder_image" type="file" multiple >
                    </div>
                    <div id="img-errors"></div>
                    <?php 
                    if(!empty($commdata->attached_doc))
                    {
                        $path = WORKORDER_PATH.$commdata->project_id.'/'.$commdata->attached_doc;;
                        if($Couchdb->documentExist($commdata->project_id,$commdata->attached_doc))
                        {
                            echo "<strong><a target=\"_blank\" href=\"".URL_HTTP.'app-docs/workorder_data/'.encode($commdata->project_id)."\">View Attached Document</a></strong>";
                        }
                       
                    }
                   
                    ?>
                </div>
            </div>
            <?php
            if($can_start_work  && $fesibility_flag && ($is_member==false || in_array($member_area,array(470,471))))
            {
                ?>
                <?php echo $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left']); ?>
                <?php
            }
            ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
//$('.datepicker').datepicker();
//$( ".datepicker" ).datepicker( "option", "dateFormat", 'dd-mm-yy' );
$(".datepicker").datepicker({
    dateFormat: 'dd-mm-yy'
});
$("#workorder_image").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 1,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#img-errors',
        maxFileSize: 1024,
    });
//$( ".datepicker" ).datepicker( "setDate", new Date(set_date));
/*$('.datepicker').datepicker();
$(".datepicker" ).datepicker( "option", "dateFormat", 'dd/mm/yy' );
$(".datepicker" ).datepicker( "option", "altFormat", "dd/mm/yy" );
//$(".datepicker" ).formatDate( "dd-mm-yy", new Date( set_date ) );
//$(".datepicker").datepicker({format:'dd-mm-yyyy',autoclose: true});
$(".datepicker" ).datepicker( "setDate", new Date(set_date));
$("#work_date").val(set_date);*/
$(document).on('click', '#addNote', function() {

$('#add_projects_note_model').modal('show');
});

$("#add_project_note_form").submit(function(e) {
    var form_data = new FormData(this);
    jQuery.ajax({
    url: '<?php echo URL_HTTP."projects/saveProjectNote"; ?>',
    type: 'POST',
    data:  form_data,
    dataType:  'json',
    mimeType:"multipart/form-data",
    processData: false,
    contentType: false,
    success: function(results)
    {
        if(results.status=='1') {
            location.reload();
        } else {

        }
    }
});
e.preventDefault();
});
</script>

