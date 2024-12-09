<?php  $this->Html->addCrumb($pageTitle);  ?>
<style>
.pad-5
{
    padding-left: 5px !important;
}
.pad-25
{
    padding-left: 29px !important;
}
.pad-20
{
    padding-left: 24px !important;
}
.action-row .dropdown .btn {
    background: #171717;
    color: white;
    border-radius: 4px !important;
    padding: 10px;
    box-shadow: 2px 2px 2px 1px #888888;
}
.action-row .dropdown .dropdown-menu {
    margin-top: 0px;
    padding: 10px;
}
.action-row .dropdown .dropdown-menu .dropdown-item {
    display: block;
    width: 100%;
    padding: .25rem 1.5rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    text-decoration: none;
}
</style>
<br/>
<div class="container ApplyOnline-leads">
    <?php echo $this->Form->create('form-main',['url' => '/apply-onlines/track-application','id'=>"form-main",'method'=>'post','type'=>'post']) ?>
        <?php echo $this->Flash->render('cutom_admin'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <?php echo $this->Form->input('geda_application_no', array('label' => false,'class'=>'form-control','placeholder'=>'GEDA Registration No','autocomplete'=>'off')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $this->Form->input('geda_consumer_no', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer No','autocomplete'=>'off')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $this->Form->input('geda_mobile_no', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Mobile No','autocomplete'=>'off')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $this->Form->input('Search', array('label' => false,'type'=>'submit','name'=>'Search','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Search')); ?>
                </div>
            </div>
        </div>
    <?php echo $this->Form->end();?>
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($ApplyOnlineLead)) { ?>
                <div class="row p-row">
                    <div class="p-title">
                        <div class="col-md-6">
                            <a href="javascript:;" class="name_text_size">
                                <?php echo trim((!empty($ApplyOnlineLead->customer_name_prefixed) ? $ApplyOnlineLead->customer_name_prefixed:'').' '.(!empty(trim($ApplyOnlineLead->name_of_consumer_applicant)) ? $ApplyOnlineLead->name_of_consumer_applicant : $ApplyOnlineLead->application_no)); ?>
                            </a>
                            <?php 
                            $approval = $MStatus->Approvalstage($ApplyOnlineLead->id);
                            if($ApplyOnlineLead->query_sent=='1' && !in_array($MStatus->APPLICATION_CANCELLED,$approval)){?>
                                <span class="application-status">
                                    <small style="font-size:12px"><br />(<?php echo"Query Sent";?>)</small>
                                </span>
                            <?php } else { ?>
                            <span class="application-status">
                                <small style="font-size:12px">
                                <br />(<?php if(isset($application_status[$ApplyOnlineLead->application_status])) { 
                                    $status_app_disp    = $application_status[$ApplyOnlineLead->application_status];
                                        $status_app_disp= str_replace(array('JREDA'), array('GEDA'), $status_app_disp);
                                        $str_append     = '';
                                        if($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA && $ApplyOnlineLead->category!=$ApplyOnlines->category_residental && $ApplyOnlineLead->payment_status==0)
                                        {
                                            $str_append = ' - Payment Pending';
                                        }
                                    echo $status_app_disp.$str_append;  } else { echo '-'; }  ?>)
                                </small>
                            </span>
                            <?php }?>
                        </div>
                        <div class="col-md-6">
                            <span class="p-date pull-right">
                            <?php
                                $application_date  = $ApplyOnlineLead->created;
                                if(!empty($ApplyOnlineLead->modified))
                                {
                                    $date_data=$MStatus->find('all',array('conditions'=>array('application_id'=>$ApplyOnlineLead->id),'order'=>array('id'=>'desc')))->first();
                                    $application_date=$ApplyOnlineLead->modified;
                                    if(empty($ApplyOnlineLead->application_status) && empty($ApplyOnlineLead->customer_name_prefixed) && empty($ApplyOnlineLead->api_response))
                                    {
                                        $application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
                                    }
                                    if(!empty($date_data))
                                    {
                                        if(date('Y-m-d H:i',strtotime($date_data->created))==date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800))
                                        {
                                            $application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
                                        }
                                        
                                    }
                                }
                                echo 'Modified '.(!empty($application_date) ? date(LIST_DATE_FORMAT,strtotime($application_date)) : '-');
                            ?>
                            </span>
                            <br/>
                            <span class="p-date pull-right">
                            <?php echo (!empty($ApplyOnlineLead->submitted_date) ? 'Submitted '.date(LIST_DATE_FORMAT,strtotime($ApplyOnlineLead->submitted_date)) : '');?>
                            </span>
                        </div>
                    </div>
                    <?php 
                        $Approved       = "";
                        $pv_capacity    = (!empty($ApplyOnlineLead->pv_capacity) ? $ApplyOnlineLead->pv_capacity : '-');
                        if (!empty($FesibilityData)) {
                            if ($FesibilityData->approved == 1) {
                                if ($FesibilityData->approved_by_subdivision) {
                                    $Approved = "<span class='text-info'>Approved by Sub-division</span>";
                                }
                            } else if ($ApplyOnlineLead->application_status == $MStatus->FIELD_REPORT_REJECTED) {
                                $Reason     = isset($FesibilityReport->RejectReason[$FesibilityData->reason])?" - ".$FesibilityReport->RejectReason[$FesibilityData->reason]:"";
                                if (!$FesibilityData->approved_by_subdivision) {
                                    $Approved = trim("<span class='text-danger'>Rejected by Sub-division</span> ".$Reason);
                                }
                            }
                            if ($ApplyOnlineLead->application_status != $MStatus->FIELD_REPORT_SUBMITTED || $ApplyOnlineLead->application_status != $MStatus->FIELD_REPORT_REJECTED ) {
                                $pv_capacity = $FesibilityData->recommended_capacity_by_discom;
                            }
                        }
                    ?>
                    <div class="clear"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-xs-4">PV capacity (DC) to be installed (in kW)</div>
                            <div class="col-xs-2 pad-5">
                                <?php echo $pv_capacity; ?>
                            </div>
                            <?php if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData)) { ?>
                                <div class="col-xs-2 pad-25">Quotation No.</div>
                                <div class="col-xs-3 pad-20">
                                    <?php echo $FesibilityData->quotation_number;?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-xs-8">Application No.</div>
                            <div class="col-xs-4">
                                <?php echo $ApplyOnlineLead->application_no;?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData)) { ?>
                                <div class="col-xs-6 col-sm-4 col-lg-4">Estimated Amount</div>
                                <div class="col-xs-6 col-sm-8 col-lg-8">
                                    <?php echo $FesibilityData->estimated_amount;?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-xs-12 col-sm-8 col-lg-8">Consumer No.</div>
                            <div class="col-xs-12 col-sm-4 col-lg-4">
                                <?php echo !empty($ApplyOnlineLead->consumer_no)?$ApplyOnlineLead->consumer_no:'-';?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData)) { ?>
                                <div class="col-xs-6 col-sm-4 col-lg-4">Estimated Due Date</div>
                                <div class="col-xs-6 col-sm-8 col-lg-8">
                                    <?php if(!empty($FesibilityData->estimated_due_date))
                                    { 
                                        $arr_data_date = explode(' ',$FesibilityData->estimated_due_date);
                                        $data_date = explode(' ',date(LIST_DATE_FORMAT,strtotime($arr_data_date[0])));
                                        echo $data_date[0];
                                    }?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-xs-12 col-sm-8 col-lg-8">Installer</div>
                            <div class="col-xs-12 col-sm-4 col-lg-4">
                                <?php 
                                    echo !empty($ApplyOnlineLead->installer['installer_name'])?$ApplyOnlineLead->installer['installer_name']:'-';
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData)) { ?>
                                <div class="col-xs-6 col-sm-4 col-lg-4">Payment Status</div>
                                <div class="col-xs-6 col-sm-8 col-lg-8">
                                    <?php echo ($FesibilityData->payment_approve==1)?'Paid':'Not Paid'; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-6 col-sm-6">
                            <div class="col-xs-8">Feasibility Comment</div>
                            <div class="col-xs-4">
                                <?php echo !empty($FesibilityData->message)?$FesibilityData->message:'-'; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $disDetails = $ApplyOnlines->getDiscomDetails($ApplyOnlineLead->circle,$ApplyOnlineLead->division,$ApplyOnlineLead->subdivision,$ApplyOnlineLead->area);
                        ?>
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="col-xs-4">Discom</div>
                            <div class="col-xs-8 pad-5" >
                                <?php echo $disDetails; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        $GetLastMessage = $ApplyonlineMessage->GetLastMessageByApplication($ApplyOnlineLead->id);
                        $disp_query     = '';
                        if(!empty($GetLastMessage))
                        {
                            $disp_query = $GetLastMessage['message'];
                        }
                        if (!empty($GetLastMessage)) {
                            $LastMessageHtml    = "<div><span><b><u>Comment</u></b></span><br /><span>".str_replace("'","",$GetLastMessage['message'])."</span><br /><br /><span><b><u>Comment By</u></b></span><br /><span>".$GetLastMessage['comment_by']."</span><br /><br /><span><b><u>IP Address</u></b></span><br /><span>".$GetLastMessage['ip_address']."</span><br /><br /><span><b><u>Comment On</u></b></span><br /><span>".$GetLastMessage['created']."</span></div>";
                            $LastMessageRender  = "<span data-toggle=\"popover\" title=\"Latest Comment\" data-html=\"true\" data-content=\"".htmlspecialchars($LastMessageHtml,ENT_QUOTES)."\"><a href=\"javascript:;\"><b>View Last Comment</b></a></span>";
                            echo "<div class=\"row\"><div class=\"col-lg-12 col-xs-12 col-sm-12\"><div class=\"col-xs-8 col-sm-6 col-lg-6\"><a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#ViewMessage\" class=\"ViewMessage\" data-id=\"".encode($ApplyOnlineLead->id)."\"><b>View All</b></a> | ".$LastMessageRender."</div></div></div>";
                        }
                    ?>
                    <div class="row progressbar-container">
                        <ul class="progressbar_guj">
                        <?php
                            $arr_application_status = $MStatus->all_status_application($ApplyOnlineLead->id);
                            foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) 
                            {
                                $IsActive = array_key_exists($key, $arr_application_status)?"active":"";
                                if(empty($arr_application_status))
                                {
                                    $IsActive = ($key==$ApplyOnlineLead->application_status)?"active":"";
                                }
                                if($key == 9 && SHOW_SUBSIDY_EXECUTION == 1 && $ApplyOnlineLead->disclaimer_subsidy == 1)
                                {

                                }
                                else
                                {
                                    echo "<li class=\"".$IsActive."\">".$value."</li>";
                                }
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            <?php } else if ($InvalidID) { ?>
                <div class="row p-row alert-danger">Please enter valid GEDA Registration No, Consumer No and Consumer Mobile No.</div>
            <?php } else { ?>
                <div class="row p-row alert-info">Track your application by entering your GEDA Registration No, Consumer No and Consumer Mobile No.</div>
            <?php } ?>
        </div>
    </div>
    <div id="ViewMessage" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">View Messages</h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php if (!empty($ApplyOnlineLead)) { ?>
$(".ViewMessage").click(function(e){
    e.preventDefault();
    var application_id = $(this).attr("data-id");
    $.ajax({
            type: "POST",
            url: "/apply-onlines/GetMessages/"+$(this).attr("data-id"),
            success: function(response) {
                var result = $.parseJSON(response);
                if (result.html != '') {
                    $("#ViewMessage").find(".modal-body").html(result.html);
                }
            }
        });
});
<?php } ?>
$(function () {
  $('[data-toggle="popover"]').popover();
})
</script>