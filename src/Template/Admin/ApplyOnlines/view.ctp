<?php if($AjaxRequest=='0'){ ?>
<style>
.rowcat .col-md-6{    
    border-bottom: 1px solid #c1c1c1;
    border-right: 1px solid #c1c1c1;
}
.rowcat{
    border-top: 1px solid #c1c1c1;   
}
.rowcat .col-md-6:first-child{
    border-left: 1px solid #c1c1c1;
}
.rowcat .control-label{
    text-align: right;
}
.rowcat1 .row{
    border: 1px solid #c1c1c1;
    padding: 7px;
}
.form-group{
    padding-top: 5px;
}

.applyonline-viewmain table tr td {
    padding: 10px;
}
.applyonline-viewmain .portlet-body h4 {
    display: table;
    background: #578EBE;
    margin: 0; 
    padding: 5px;
    color: #fff;
    font-size: 1.1em !important;
}

.applyonline-viewmain .greenbox {
    border-bottom: 1px solid #4D5B69;
    margin-top: 20px;
}
.greenbox:first-child {
    margin-top: 0;
}
.applyonline-viewmain .cbtnsendmsg {
    float: right;
    margin-bottom: 0 !important;
}
.applyonline-viewmain .attach-lable{
    padding: 0;
}
.applyonline-viewmain img {
    border:1px solid #c1c1c1;
    width:250px;
}
.applyonline-viewmain .attch-doc{

}
.progressbar {
  counter-reset: step;
}
.progressbar li {
  list-style: none;
  display: inline-block;
  width: calc(14% - 4px);
  position: relative;
  text-align: center;
  cursor: pointer;
}
.progressbar li:before {
  content: counter(step);
  counter-increment: step;
  width: 30px;
  height: 30px;
  line-height : 30px;
  border: 1px solid #ddd;
  border-radius: 100%;
  display: block;
  text-align: center;
  margin: 0 auto 10px auto;
  background-color: #fff;
  position: relative;
  z-index: 1;
}
.progressbar li.active:before{
    background-color: #4D5B69;
    color: #fff;
    border: 0px;
}
.progressbar li:after {
    content: "";
    position: absolute;
    width: 100%;
    height: 3px;
    background-color: #ddd;
    top: 14px;
    left: -50%;
    z-index: 0;
}
.progressbar li:first-child:after {
  content: none;
}
.progressbar li.active {
  color: green;
}
.progressbar li.active:before {
  border-color: green;
} 
.progressbar li.active + li:after {
  background-color: #4D5B69;
}
</style> 
<?php 
$address = "";
$address.= (isset($applyOnlinesData['address1'])?$applyOnlinesData['address1']:'');
$address.= (isset($applyOnlinesData['address2'])?', '.$applyOnlinesData['address2']:'');
$address.= (isset($applyOnlinesData['city'])?', '.$applyOnlinesData['city']:'');
$address.= (isset($applyOnlinesData['state'])?', '.$applyOnlinesData['state']:'');
$address.= (isset($applyOnlinesData['pincode'])?', '.$applyOnlinesData['pincode']:'');
//echo  '<pre>';
//print_r($applyOnlinesData);
?>
<div class="grid_12">
<div class="box">
    <div class="content">
        <div class="portlet box blue-madison applyonline-viewmain">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> View Apply Online
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="progressbar-container" style="margin-top:10px;">
                    <ul class="progressbar">
                        <?php $active = '';
                       // pr($APPLY_ONLINE_MAIN_STATUS);exit;
                        foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) {  
                            if($key==1 && $ApplyOnlines->application_status == $key){
                                $active = $key;
                                break;
                            } else if ($key==2 && $ApplyOnlines->application_status == $key) {
                                $active = $key;
                                break;
                            } else if (($key == 3 || $key == 5 || $key == 6)  && $ApplyOnlines->application_status == $key) {
                                $active = $key;
                                break;
                            }
                        } 
                        foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) { 
                            if($key <= $active ){ ?> 
                            <li class="active"><?php echo $value; ?></li>
                        <?php } else { ?>
                            <li><?php echo $value; ?></li>
                        <?php }
                        } ?>
                    </ul>
                </div>
                <div class="form-body">
                    <div class="greenbox">
                        <h4> Installer Detail </h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Installer</label>
                                 <?php echo $ApplyOnlines->installer['installer_name']; ?>                             
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-12">
                                 <?php if($ApplyOnlines->disclaimer == 1) { ?>
                                <i class="fa fa-check" style="padding-top:3px;position: absolute;"></i>
                                <?php } ?>
                                <label class="nodots" style="padding-left:20px;">I hereby confirm to all the Terms and Conditions of the JREDA and AHA! Solar and of the scheme of JREDA. I also ensure that all the information in the Application will be provided to the best of my knowledge.</label>
                            </div>
                        </div>
                    </div>
                    <div class="greenbox">
                        <h4> Contact Detail </h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Customer Name </label>
                                 <?php echo $ApplyOnlines->customer_name_prefixed.' '.$ApplyOnlines->name_of_consumer_applicant; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Mobile</label>
                                 <?php echo $ApplyOnlines->mobile; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Landline No</label>
                                 <?php echo $ApplyOnlines->landline_no; ?>
                            </div>
                        </div>    
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>email</label>
                                <?php 
                                    if (!empty($ApplyOnlines->email)) {
                                        echo $ApplyOnlines->email;
                                    } else {
                                        echo $ApplyOnlines['customers']['email'];
                                    } 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <label style="padding-left: 0;" class="col-md-2">Address</label>
                                <div class="col-md-10"><?php echo $ApplyOnlines->address1; ?>
                                 <br/>
                                 <?php echo $ApplyOnlines->address2; ?>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>City</label>
                                 <?php echo $ApplyOnlines->city; ?>
                            </div>
                            <div class="col-md-6">
                                <label>State</label>
                                 <?php echo $ApplyOnlines->state; ?>                     
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Pincode</label>
                                 <?php echo $ApplyOnlines->pincode; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Communication Address</label>
                                <?php 
                                if($ApplyOnlines->comunication_address_as_above == '1' || $ApplyOnlines->comunication_address == '1') 
                                {
                                    echo $ApplyOnlines->address1; ?>
                                    <br/>
                                <?php echo $ApplyOnlines->address2;
                                } 
                                else if($ApplyOnlines->comunication_address == '0')
                                {
                                    echo '';
                                }
                                else
                                {
                                    echo $ApplyOnlines->comunication_address;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="greenbox">
                        <h4>Bill Details</h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>DisCom Name</label>
                                 <?php echo isset($discom_list[$ApplyOnlines->discom]) ? $discom_list[$ApplyOnlines->discom] : ''; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Consumer NO.</label>
                                 <?php echo $ApplyOnlines->consumer_no; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Sanctioned /Contract Load (in kWh)</label>
                                 <?php echo $ApplyOnlines->sanction_load_contract_demand; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Category</label>
                                <?php
                                if($ApplyOnlines->parameter_cats['para_value']!='' && $ApplyOnlines->ApplyOnlines['para_value']!='null')
                                {
                                    echo $ApplyOnlines->parameter_cats['para_value'];
                                }
                                else
                                {
                                    echo $ApplyOnlines->category;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12"> 
                                <label class="nodots"> 
                                <?php if($ApplyOnlines->social_consumer == 1){ ?>
                                <i class="fa fa-check"></i>
                                <?php } ?>
                                  Are you a social sector consumer?</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                            <?php
                            if($ApplyOnlines->category==3001 || $ApplyOnlines->category=='Residental')
                            {
                                ?>
                                 <label>Aadhaar no. </label>
                                 <?php echo passdecrypt($ApplyOnlines->aadhar_no_or_pan_card_no); ?>
                                 <?php
                            }
                            else
                            {
                                ?>
                                  <label>PAN card no. </label>
                                 <?php echo passdecrypt($ApplyOnlines->pan_card_no); ?>
                                <?php
                            }
                            ?>   
                            </div>
                           
                            <div class="col-md-6">
                                <label>House Tax Holding No</label>
                                 <?php echo passdecrypt($ApplyOnlines->house_tax_holding_no); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12"> 
                                <label class="nodots"> 
                                <?php if($ApplyOnlines->acknowledgement_tax_pay == 1){ ?>
                                <i class="fa fa-check"></i>
                                <?php } ?>
                                 Yes, I have paid the house tax which is valid for at least the next 30 days from the date of this application.</label>
                            </div>
                        </div>
                    </div>
                    <div class="greenbox">
                        <h4> Attached Document </h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <?php 
                                $path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_recent_bill;
                                if (!empty($ApplyOnlines->attach_recent_bill) && file_exists($path)) {
                            ?>
                            <div class="col-md-3 align-center">
                                <label class="attach-lable">Recent Bill</label>
                                 <a href="<?php echo APPLYONLINE_URL.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_recent_bill; ?>" target="_blank"><i class="fa fa-file"></i></a>
                                
                            </div>
                            <?php 
                                }
                            ?>
                            <?php 
                                $path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_latest_receipt;
                                if (!empty($ApplyOnlines->attach_latest_receipt) && file_exists($path)) {
                            ?>
                            <div class="col-md-3 align-center">
                                <label class="attach-lable">House Tax Receipt </label>
                                 <a href="<?php echo APPLYONLINE_URL.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_latest_receipt; ?>" target="_blank"><i class="fa fa-file"></i></a>
                               
                            </div>
                            <?php 
                                }
                            ?>
                            <?php
                            if($ApplyOnlines->category==3001)
                            {
                                
                                $path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_photo_scan_of_aadhar;
                                if (!empty($ApplyOnlines->attach_photo_scan_of_aadhar) && file_exists($path)) {
                            ?>
                            <div class="col-md-3 align-center">
                                <label class="attach-lable">Aadhaar card</label>
                                 <a href="<?php echo APPLYONLINE_URL.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_photo_scan_of_aadhar; ?>" target="_blank"><i class="fa fa-file"></i></a>
                               
                            </div>
                            <?php 
                                }
                            }
                            else
                            {
                            ?>
                            <?php 
                                $path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_pan_card_scan;
                                if (!empty($ApplyOnlines->attach_pan_card_scan) && file_exists($path)) {
                            ?>
                            <div class="col-md-3 align-center">
                                <label class="attach-lable">PAN card</label>
                                 <a href="<?php echo APPLYONLINE_URL.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_pan_card_scan; ?>" target="_blank"><i class="fa fa-file"></i></a>
                                
                            </div>
                            <?php }
                            }
                             if(!empty($ApplyOnlines->attach_detail_project_report)) 
                             { ?>
                            <div class="col-md-3 align-center">
                               
                                    <label class="attach-lable">Project detail report</label>
                               
                                <a href="<?php echo APPLYONLINE_URL.$ApplyOnlines->id.'/'.$ApplyOnlines->attach_detail_project_report; ?>" target="_blank">
                                  <i class="fa fa-file"></i> <?php /*  <img src="/img/frontend/elegant-pdf-icon-16.png" /> */?>
                                </a>
                            </div> 
                            <?php }
                            ?>
                        </div>
                    </div>
                    <?php if(isset($applyOnlinesDataDocList) && !empty($applyOnlinesDataDocList)) { ?>
                    <div class="greenbox">
                        <h4> Other Attachment </h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <?php
                                foreach ($applyOnlinesDataDocList as $key => $value) 
                                {
                                    $path = APPLYONLINE_PATH.$ApplyOnlines->id.'/'.$value['file_name'];
                                    if (empty($value['file_name']) || !file_exists($path)) continue;
                            ?>
                                <div Class="col-md-4 align-center">
                                    <label class="attach-lable"><?php echo $value['title']; ?></label>
                                    &nbsp;&nbsp;
                                     <a href="<?php echo APPLYONLINE_URL.$ApplyOnlines->id.'/'.$value['file_name']; ?>" target="_blank"><i class="fa fa-file"></i></a>
                                </div>
                              <?php 
                                } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="greenbox">
                        <h4> Other Information </h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>PV Capacity</label>
                                 <?php echo $ApplyOnlines->pv_capacity; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Location of Proposed Rooftop Solar PV System</label>
                                 <?php echo $ApplyOnlines->roof_of_proposed; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($GetProjectEstimation) && !empty($GetProjectEstimation)) { ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Estimated Cost</label>
                                 <?php echo $GetProjectEstimation['est_cost']; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Estimated Cost With Subsidy</label>
                                 <?php echo $GetProjectEstimation['est_cost_subsidy']; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Estimated Saving/Month</label>
                                 <?php echo $GetProjectEstimation['saving_month']; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Average Monthly Units Consumed (kWh/month)</label>
                                 <?php echo $ApplyOnlines->energy_con; ?>
                            </div>
                            <div class="col-md-6">
                                <label>Average Monthly Bill (in &#8377)</label>
                                 <?php echo $ApplyOnlines->bill; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                 <label class="nodots">
                                 <?php if($ApplyOnlines->tod_billing_system == 1) { ?>
                                 <i class="fa fa-check"></i>
                                <?php } ?>
                                I, Consumer Applicant, is under ToD billing system.</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                 <label class="nodots"><?php if($ApplyOnlines->avail_accelerated_depreciation_benefits == 1){ ?>
                                 <i class="fa fa-check"></i>
                                 <?php } ?>
                                I, Consumer Applicant, or the third party owner shall avail accelerated depreciation benefits on the Rooftop Solar PV system.</label>
                            </div>
                        </div>
                    </div>

                    <div class="greenbox">
                        <h4> Application Fee and Subsidy Detail </h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                        <?php
                            $col1       = '3';
                            $col2       = '3';
                            if($transaction_id!='')
                            {
                                $col1   = '2';
                                $col2   = '4';
                            }
                        ?>
                            <div class="col-md-3">
                                <label class="nodots">Installer Name :
                                <?php echo $ApplyOnlines->installer['installer_name']; ?></label>
                            </div>
                            <div class="col-md-<?php echo $col1;?>">
                                <label>Payment Mode</label>
                                <?php   
                                    if($ApplyOnlines->payment_mode==1) { echo 'Online'; } 
                                    else { echo 'Offline'; }
                                ?>
                            </div>
                            <div class="col-md-<?php echo $col2;?>">
                                <label>Payment Gateway</label>
                                <?php echo $ApplyOnlines->payment_gateway; ?>
                            </div>
                            <div class="col-md-3">
                                <label>Payment Status</label>
                                <?php 
                                if($ApplyOnlines->payment_status == '1') 
                                {
                                    echo '<span><h4 style="background:#71BF57;padding:3px;float:right;margin-right:90px;line-height:20px;">Paid</h4></span>';
                                }
                                else
                                {
                                    echo '<span><h4 style="background:#a94442;padding:3px;float:right;margin-right:70px;line-height:20px;">Not paid</h4></span>';
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label style="width:300px;">Bank A/C no. for disbursement of subsidy</label>
                                <?php echo $ApplyOnlines->bank_ac_no; ?>
                            </div>
                          <?php
                          if($transaction_id!='')
                          {
                              ?>
                               <div class="col-md-4">
                                <label>Transaction ID</label>
                                 <?php echo $transaction_id; ?>
                                </div>
                                <div class="col-md-3">
                                <label>Payment Date</label>
                                 <?php echo $payment_date; ?>
                                </div>
                            <?php
                          }
                          ?> 
                        </div>
                        <div class="clear-both"></div>
                        <br/>
                        <table class="table-responsive table-bordered"> 
                            <tbody>
                                <tr>
                                    <td>DisCom Application Fee for Net Metering</td>
                                    <td><?php echo $ApplyOnlines->disCom_application_fee; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Total Fee</b></td>
                                    <td><b><?php 
                                    //$ApplyOnlines->jreda_processing_fee
                                    echo $ApplyOnlines->disCom_application_fee ; ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div> 
            </div>
        </div>
        <?php } ?>
        </div>
    </div>
</div>