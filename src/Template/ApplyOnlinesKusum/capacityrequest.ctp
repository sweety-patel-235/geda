<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<?php
$this->Html->addCrumb($pagetitle);
?>
<?php echo $this->Form->create('Subsidy',array("id"=>"formmain","name"=>"searchClaimSubsidy",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
    <div class="container project-leads">
        <div class="col-md-12 MessageBlock"></div>
        <div class="col-md-12">
            <?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
            <?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
            <?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>
            <div class="form-body">
                <div class="row col-md-12">
                    <div class="col-md-3 ">
                        <?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?php echo $this->Form->input('request_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'request_no','placeholder'=>'Request No.')); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?php echo $this->Form->input('geda_application_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'geda_application_no','placeholder'=>'GEDA Registration No.')); ?>
                    </div>
                </div>
                <div class="row col-md-12">
                    <div class="col-md-3">
                        <?php echo $this->Form->select('recevied_status',$REQUEST_STATUS,array('label' => false,'class'=>'form-control','empty'=>'-Select Request Status-','style'=>'margin-left:-15px;')); ?>
                    </div>
                    <?php
                    if($is_member)
                    {
                        ?>
                        <div class="col-md-3" style="padding-left: 0px;">
                            <?php echo $this->Form->select('installer_name_multi',$Installers,array('label' => false,'class'=>'form-control chosen-select','empty'=>'-Select Installers-','id'=>'installer_name','data-placeholder'=>'-Select Installers-',"multiple"=>"multiple")); ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-md-6">
                        <button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Search</button>
                        <button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <?php  echo $this->Flash->render('cutom_admin'); ?>
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue-madison noborder">
                <table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
                    <thead>
                    <tr>
                        <th class="">Sr No.</th>
                        <th class="">GEDA Registration No.</th>
                        <th class="">Installer Name</th>
                        <th class="">Requested Capacity</th>
                        <th class="">Request No.</th>
                        <th class="">Request Date</th>
                        <th class="">Status</th>
                        <th class="">Verified At GEDA</th>
                        <?php
                        if($is_member)
                        {
                            ?><th class="">Action</th>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

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
<div id="approve_Status" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Approved Request</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create('approve_request',['name'=>'frm_approve_request','id'=>'approve_request']); ?>
                <div id="message_error"></div>
                <div class="form-group text">
                    <?php echo $this->Form->input('requestid',['id'=>'requestid','label' => true,'type'=>'hidden']); ?>
                    <?php echo $this->Form->select('received_at',array("0"=>"Pending","1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'received_at','label' => false]); ?><br />
                    <?php echo $this->Form->textarea('received_msg',[ "class" =>"form-control reason",      
                                                                'id'=>'received_msg',
                                                                'cols'=>'50','rows'=>'5',
                                                                'label' => false,
                                                                'placeholder' => 'Comments, if any']);
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-2">
                    <?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_5','label'=>false,'class'=>'btn btn-primary request_approval_btn','data-form-name'=>'approve_request']); ?>
                    </div>
                </div>
                <?php
                echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php echo $JqdTablescr; ?>
    $(document).ready(function() {
        resetcustomdates(true);
        resetdates();
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
    });
    function ReloadDataTable()
    {
        $('#searchbtn').trigger("click");
    }
    function resetcustomdates(onload)
    {
        var period      = $('#SearchPeriod').val();
        var Today       = '<?php echo date("d-m-Y");?>';
        var Yesterday   = '<?php echo date("d-m-Y",strtotime("yesterday"));?>';
        $("#DateFrom").removeAttr("disabled");
        $("#DateTo").removeAttr("disabled");
        if(!onload) {
            $("#DateFrom").val(Yesterday);
            $("#DateTo").val(Today);
        }
        $("#DateFrom").datepicker({format:'dd-mm-yyyy',autoclose: true});
        $("#DateTo").datepicker({format:'dd-mm-yyyy',autoclose: true});
    }
    function resetdates()
    {
        
    }
    function resetsearch()
    {
        window.location.reload();
    }
    function validatesearchform()
    {
        return true;
    }
    $("body").on('click','.showModel',function(){
    var modelheader = $(this).data("title");
    var modelUrl = $(this).data("url");
    document_window = $(window).width() - $(window).width()*0.05;
    document_height = $(window).height() - $(window).height() * 0.20;  
    modal_body = '<div class="modal-header" style="min-height: 45px;">'+
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
    '</div>'+
    '<div class="modal-body">'+
    '<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
    '</div>';
    
    $('#myModal').find(".modal-content").html(modal_body);
    $('#myModal').modal('show');
    $('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
    $('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
    return false;
});
window.closeModal = function(){ $('#myModal').modal('hide'); };
$(document).on("click", ".approve_Status" , function() {

    var requestid = $(this).attr("data-id");
    
    $("#requestid").val(requestid);
    $.ajax
    ({
        type: "POST",
        url: "/ApplyOnlines/fetchCapacityRequest",
        data: {'requestid':requestid},
        success: function(response) {
            var result = $.parseJSON(response);
                if (result.type == "ok") {
                {
                    console.log(result.response['received_msg']);
                   $("#received_msg").val(result.response['received_msg']);
                   $("#received_at").val(result.response['received_at']);
                }
            }
        }
    });
    $('#approve_Status').modal('show');
});
$(".request_approval_btn").click(function(event) {
    event.preventDefault();
    var fromobj = $(this).attr("data-form-name");
    console.log(fromobj);
        $.ajax({
                type: "POST",
                url: "/ApplyOnlines/ApproveCapacityRequest",
                data: $("#"+fromobj).serialize(),
                success: function(response) {
                    var result = $.parseJSON(response);
                    console.log(result.success);
                    if (result.success == 1) {
                        $("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
                        //location.reload();
                        //window.location.reload();
                        $('#approve_Status').modal('hide');
                    } else {
                        $("#approve_request").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
                    }
                }
            });
    
    });
</script>

