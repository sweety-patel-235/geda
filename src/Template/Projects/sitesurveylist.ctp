<?php
$this->Html->addCrumb('Projects', ['controller' => 'projects']);
$this->Html->addCrumb($pagetitle);

?>
<div class="container project-leads">
    <div class="row"  style="margin-bottom:10px;float:right;">
        <div class="col-md-12">
          <button type="button" class="btn green" onclick="javascript:download_xls();"><i class="fa fa-download"></i> Download .xls</button>
            <?php if($total_survey>0)
            {  ?>
            <button type="button" class="btn green" id="all_surveys_project" onclick="javascript:click_view_projectsurveys();" ><i class="fa fa-download"></i> Download Report PDF</button>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-12">
        <?php  echo $this->Flash->render('cutom_admin'); ?>
        <!-- BEGIN EXAMPLE TABLE PORTLET-->

        <div class="portlet box blue-madison noborder">

            <?php echo $this->Form->create('Surveys',array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
            <?php echo $this->Form->hidden('project_id',array("value"=>$proj_id,"id"=>"project_id")); ?>

            <table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
                <thead>
                <tr>
                    <th class="sorting">ID</th>
                    <th class="sorting">Building Name</th>
                    <th class="sorting">Contact Name</th>
                    <th class="sorting">Designation</th>
                    <th class="sorting">Address1</th>
                    <th class="sorting">Address2</th>
                    <th class="sorting">Address3</th>
                    <th class="sorting">Mobile</th>
                    <th class="sorting">Surveyer Name</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
            <?php
            echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<div id="jqtable_data"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog2">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
    <?php
        echo $JqdTablescr;
    ?>
    function download_xls()
    {
        var project_id = $("#project_id").val();
        $('#formmain').attr('action','<?php echo "/projects/create_xls"; ?>');
        $('#formmain').submit();

        //window.location.href = WEB_ADMIN_URL+'projects/create_xls/'+project_id;
    }

    function click_view_projectsurveys()
    {
        var project_id = $("#project_id").val();
        window.location.href="<?php echo constant('WEB_URL').'projects/viewprojectsurveyreport/';?>"+project_id;

    }
    /**
     *
     * showModel
     *
     * @param : id  : Id is use to identify for which Surveyor data should be edit.
     *
     * @defination : Event is use to edit data of sitesurvey.
     *
     */
    $('body').on('click', '.showModel', function() {
        var id = $(this).data("id")
        var modelheader = $(this).data("title");
        var modelUrl = $(this).data("url");


        document_window = $(window).width() - $(window).width() * 0.05;
        document_height = $(window).height() - $(window).height() * 0.20;

        modal_body = '<div class="modal-header" style="min-height: 45px;">' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + modelheader + '</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<iframe id="TaskIFrame" width="100%;" src="' + modelUrl + '" height="100%;" frameborder="0" allowtransparency="true"></iframe>' +
            '</div>';

        $('#myModal').find(".modal-content").html(modal_body);
        $('#myModal').modal('show');
        $('#myModal').find(".modal-dialog").attr('style', "min-width:" + document_window + "px !important;");
        $('#myModal').find(".modal-body").attr('style', "height:" + document_height + "px !important;");
        return false;

    });
    window.closeModal = function(){ $('#myModal').modal('hide'); };

</script>

