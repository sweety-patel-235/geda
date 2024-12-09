<?php
    $this->Html->addCrumb('My Apply-online List','apply-online-list'); 
    $this->Html->addCrumb($pageTitle);
    $ApproveFA          = false;
    if ($ApplyOnlines->application_status == $MStatus->FIELD_REPORT_SUBMITTED && isset($member_type) && $member_type == $DISCOM)
    {
        $ApproveFA      = true;
    }
?>
<style>
.serial_class
{
    width:4%;
}
.applyonline-viewmain .portlet-body {
    padding: 7px;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container">
    <div class="box">
        <div class="content">
            <div class="portlet box blue-madison applyonline-viewmain fesibility-report">
                <div class="row">
                    <h2 class="col-md-9 mb-sm mt-sm">
                        <small>Technical DATA FORM FOR FEASIBILITY CLEARANCE OF ROOFTOP SOLAR PV PLANT</small>
                    </h2>
                    <div class="col-md-3">
                    <?php 
                    if ($ApproveFA) { ?>
                        <a href="javascript:;" data-toggle="modal" data-target="#Approve_FA" class="Approve_FA" data-id="<?php echo encode($ApplyOnlines->id); ?>">
                            <span class="next btn btn-primary cbtnsendmsg">
                                <i class="fa fa-check-square-o"></i>Approve Fesibility Report
                            </span>
                        </a>
                    <?php 
                    }
                        ?>
                    </div>
                </div>
                <?php
                    $SubmitReport       = true;
                    if (!empty($ApplyOnlines->section) && $ApplyOnlines->section == $section) {
                        $SubmitReport   = true;
                    } else if (!empty($ApplyOnlines->division) && $ApplyOnlines->division == $division) {
                        $SubmitReport   = true;
                    }
                    
                    echo $this->Form->create($fesibility, ['id'=>'form-main','method'=>'post','type' => 'post','url' => 'apply-onlines/fesibility/'.$id]);
                    echo $this->Form->input('rid',['id'=>'id','label' => false,'type'=>'hidden','value'=>$rid]);
                    echo $this->Form->input('approved_by_subdivision',['id'=>'approved_by_subdivision','label' => false,'type'=>'hidden','value'=>0]);
                    $this->Form->templates(['inputContainer' => '{{content}}']);
                ?>
                <table width="100%" class="form">
                    <tr>
                        <td class="portlet-body" colspan="4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><label>Date and Time of Submission of Report</label></div>
                                        <div class="col-md-5"><?php echo $fesibility->created;?></div>
                                        <div class="col-md-1 text-right">
                                            <?php if ($SubmitReport) { ?>
                                                <span class="show-hide-action text-right close">
                                                    <i class="fa fa-pencil-square-o" style="font-size:24px"></i>
                                                </span>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">1.</td>
                        <td class="portlet-body" colspan="2" style="width:48%;"><label>Name of the SPG Developer</label></td>
                        <td class="portlet-body" style="width:48%;"><?php echo $this->Form->input('field_officer',array('label' => false,'class'=>'form-control','placeholder'=>'Name of the Field Officer')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class" rowspan="7">2.</td>
                        <td class="portlet-body" colspan="2"><label>Details of the building where rooftop solar project is to be installed</label></td>
                        <td class="portlet-body"><?php echo $ApplyOnlines->email;?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">i</td>
                        <td class="portlet-body"><label>Name of Sub-Division</label></td>
                        <td class="portlet-body"><?php
                                                if (!empty($subdivision) && isset($subdivision[0]['title'])) {
                                                    echo $subdivision[0]['title'];
                                                } else {
                                                    echo "-";
                                                }
                                            ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">ii</td>
                        <td class="portlet-body"><label>Consumer Name</label></td>
                        <td class="portlet-body"><?php echo $ApplyOnlines->customer_name_prefixed;?> <?php echo $ApplyOnlines->name_of_consumer_applicant;?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">iii</td>
                        <td class="portlet-body"><label>Consumer No.</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('consumer_no', array('div' => false,'label' => false,'class'=>'form-control','placeholder'=>'Consumer No.')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">iv</td>
                        <td class="portlet-body"><label>Address</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('comunication_address',array('div' => false,'label' => false,'class'=>'form-control','placeholder'=>'Address')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">v</td>
                        <td class="portlet-body"><label>Tariff of the Consumer</label></td>
                        <td class="portlet-body"><?php echo $this->Form->select('category',$BillCategoryList,array('label' => false,'class'=>'form-control','empty'=>'-SELECT TARIFF-','placeholder'=>'SELECT CATEGORY')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">vi</td>
                        <td class="portlet-body"><label>Contract demand / Load (kW)</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('sanction_load',array('label' => false,'class'=>'form-control','placeholder'=>'Sanctioned Load / Contract Load of Customer (kW)')); ?></td>
                    </tr>
                    <?php /*
                    <tr>
                        <td class="portlet-body serial_class" rowspan="5">3.</td>
                        <td class="portlet-body" colspan="2"><label>Details of 11 kV feeder</label></td>
                        <td class="portlet-body"></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">i</td>
                        <td class="portlet-body"><label>Name of Feeder</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('name_of_feeder',array('label' => false,'class'=>'form-control','placeholder'=>'Name of Feeder')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">ii</td>
                        <td class="portlet-body"><label>Transformer Capacity (in case of LT Connection) (kVA)</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('transformer_capacity',array('label' => false,'class'=>'form-control','placeholder'=>'Transformer Capacity (in case of LT Connection) (kVA)')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">iii</td>
                        <td class="portlet-body" valign="top"><label>Solar Rooftop capacity already connected/ Approved on thus transformer (In case of LT Connection)</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('capacity_already_connect',array('label' => false,'class'=>'form-control','placeholder'=>'Connected Load (kVA) on the Transformer','type'=>'textarea')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">iv</td>
                        <td class="portlet-body"><label>Maximum load (Amp) recorded during last one year on the feeder</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('max_demand_conductor',array('label' => false,'class'=>'form-control','placeholder'=>'Maximum load (Amp)')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">4.</td>
                        <td class="portlet-body" colspan="2"><label>No due./ Legal Dispute Certificate</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('length_lt_circuits',array('label' => false,'class'=>'form-control','placeholder'=>'No due./ Legal Dispute Certificate')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">5.</td>
                        <td class="portlet-body" colspan="2" ><label>Details estimate to be recovered from Applicant for strenghtening of SiCom's system for the work to be carried oit for providing connectivity &amp; evacuation facility of surplus power to be injected by the Applicant</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('conductor_size',array('label' => false,'class'=>'form-control','placeholder'=>'Details estimate')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">6.</td>
                        <td class="portlet-body" colspan="2" valign="top"><label>Details of capacity of any other source of electrical power exist in consume/s premises including solar if any.</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('details_capacity',array('label' => false,'class'=>'form-control','placeholder'=>'Details of capacity of any other source','type'=>'textarea')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body serial_class">7.</td>
                        <td class="portlet-body" colspan="2"><label>Connectivity Charged paid by consumer vide M.R. No. and Date</label></td>
                        <td class="portlet-body"><?php
                        if($ApplyOnlines->payment_status==1) { echo 'Paid'; } else { echo 'Not Paid'; }
                        ?></td>
                    </tr> */?>
                    <tr>
                        <td class="portlet-body serial_class">3.</td>
                        <td class="portlet-body" colspan="2"><label>Recommended Capacity by Field Office (kW)</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('recommended_capacity_by_discom',array('label' => false,'class'=>'form-control','placeholder'=>'Recommended Capacity by Field Office')); ?></td>
                    </tr>
                    
                    <tr>
                        <td class="portlet-body serial_class" rowspan="4">4.</td>
                        <td class="portlet-body">i</td>
                        <td class="portlet-body" ><label>Approved?</label></td>
                        <td class="portlet-body"><?php echo $this->Form->select('approved',array("1"=>"Yes","0"=>"No"),array('label' => false,'class'=>'approved-dd form-control','empty'=>'-APPROVED STATUS-','placeholder'=>'APPROVED STATUS')); ?>
                            <span class="reject-reason hide" >
                            <?php echo $this->Form->select('reason',$RejectReason,array('label' => false,'class'=>'form-control','empty'=>'-REJECT REASON-','placeholder'=>'REJECT REASON','style'=>'margin-top:5px;')); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="portlet-body">ii</td>
                        <td class="portlet-body"><label>Quotation Number</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('quotation_number',array('label' => false,'class'=>'form-control','placeholder'=>'Quotation Number')); ?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">ii</td>
                        <td class="portlet-body"><label>Estimated Amount</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('estimated_amount',["type" => "number",'label' => false,"class" => "form-control",'min'=>"0"]);?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body">ii</td>
                        <td class="portlet-body"><label>Estimated Due Date</label></td>
                        <td class="portlet-body"><?php echo $this->Form->input('estimated_due_date',["type" => "text",'label'=>false,"class" => "form-control datepicker","id"=>"estimated_due_date"]);?></td>
                    </tr>
                    <tr>
                        <td class="portlet-body"  colspan="4">
                        <?php
                           // if ($SubmitReport && $member_type == $DISCOM) {
                                if (isset($issubdivision) && $issubdivision == 1) {
                                    echo $this->Form->button('Approved By Subdivision', ['type' => 'button', 'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg btns subdivision_approval',
                                    'disabled'=>'disabled']);
                                } //,'disabled'=>'disabled'
                                echo $this->Form->button('Submit', ['type' => 'submit', 'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg m-right-10 btns','disabled'=>'disabled','onclick'=>'javascript:click_submit();','id'=>'submit_fesibility']);
                                echo '<span class="next btn btn-primary btn-lg mb-xlg m-right-10 cbtnsendmsg">'.$this->Html->link('Back To List',['controller'=>'ApplyOnlines','action' => 'applyonline_list']).'</span>';
                           /* } else {
                                echo '<span class="next btn btn-primary btn-lg mb-xlg cbtnsendmsg">'.$this->Html->link('Back To List',['controller'=>'ApplyOnlines','action' => 'applyonline_list']).'</span>';
                            }*/
                        ?>
                        </td>
                    </tr>
                </table>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    
</div>
<div id="Approve_FA" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Approval from Division</h4>
                </div>
                <div class="modal-body">
                    <?php
                    echo $this->Form->create('FApprove_FA',['name'=>'Approve_FA','id'=>'FApprove_FA']); ?>
                    <div id="messageBox"></div>
                    <div class="form-group text">
                    <?php echo $this->Form->input('approval_type',['id'=>'Approve_FA_approval_type','label' => true,'type'=>'hidden','value'=>'4']); ?>
                    <?php echo $this->Form->input('appid',['id'=>'Approve_FA_application_id','label' => true,'type'=>'hidden','value'=>encode($ApplyOnlines->id)]); ?>
                    <?php echo $this->Form->select('application_status',array("1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'JREDA_FA_application_status','label' => false]); ?><br />
                    <?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",      
                                                                'id'=>'Approve_FA_reason',
                                                                'cols'=>'50','rows'=>'5',
                                                                'label' => false,
                                                                'placeholder' => 'Comments, if any']);
                    ?>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                        <?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_7','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FApprove_FA']); ?>
                        </div>
                    </div>
                    <?php
                    echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
var date = new Date();
date.setMonth(date.getMonth() + 1, 1);
$('#estimated_due_date').datepicker({defaultDate: date,dateFormat: 'dd-mm-yy'});
</script>
<script type="text/javascript">
$(document).ready(function(){
    <?php if (!$SubmitReport) { ?>
        $(".form-control").attr("disabled","disabled");
        $("#JREDA_FA_application_status").removeAttr("disabled");
        $("#Approve_FA_reason").removeAttr("disabled");
        $("#FApprove_FA").removeAttr("disabled");
    <?php } else { ?>
    $(".form-control").attr("disabled","disabled");
     $("#JREDA_FA_application_status").removeAttr("disabled");
     $("#Approve_FA_reason").removeAttr("disabled");
     $("#FApprove_FA").removeAttr("disabled");
    $(".show-hide-action").click(function(){
        if ($(this).hasClass("close")) {
            $(".form-control").removeAttr("disabled");
            $(".btns").removeAttr("disabled");
            $(this).removeClass("close");
            $(this).addClass("open");
        } else {
            $(".form-control").attr("disabled","disabled");
            $(".btns").attr("disabled","disabled");
            $(this).addClass("close");
            $(this).removeClass("open");
        }
    });
    <?php } ?>
    $(".subdivision_approval").click(function() {
        $(".form-control").removeAttr("disabled");
        $("#approved_by_subdivision").val(1);
        $("#form-main").submit();
    });
    $(".approved-dd").change(function(){
        if ($(this).val() == 1) {
            $(".reject-reason").addClass("hide");
            $(".subdivision_approval").html("Approved By Subdivision");
        } else if ($(this).val() != 1) {
            $(".reject-reason").removeClass("hide");
            $(".subdivision_approval").html("Submit");
        }
    });
});
function click_submit()
{
    $("#submit_fesibility").attr('disabled','disabled');
    $("#form-main").submit();
}
$(".approval_btn").click(function(){
    var fromobj = $(this).attr("data-form-name");
    var reason = $("#"+fromobj).find(".reason").val();
    $("#"+fromobj).find("#messageBox").html("");
    $("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
    if ($("#"+fromobj).find(".application_status").val() == 2 && reason.length < 1) {
        $("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
        $("#"+fromobj).find("#messageBox").html("");
        $("#"+fromobj).find("#messageBox").html("Reason is required field.");
        return false;
    } else {
        $.ajax({
              type: "POST",
              url: "/apply-onlines/inspectionstage",
              data: $("#"+fromobj).serialize(),
              success: function(response) {
                var result = $.parseJSON(response);
                if (result.type == "error") {
                    $("#assign_division_message").addClass("alert alert-error");
                    $("#assign_division_message").html(result.msg);
                }
                window.location.href='/apply-online-list';
              }
        });
    }
    return false;
});
</script>