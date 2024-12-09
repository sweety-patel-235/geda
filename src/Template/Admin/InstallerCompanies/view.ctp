<?php if($AjaxRequest=='0'){ ?>
<style>
.rowcat .col-md-6{
    border: 1px solid #c1c1c1;
}
.rowcat .control-label{
       text-align: right;
}
.rowcat1 .row{
    border: 1px solid #c1c1c1;
    padding: 7px;
}
</style> 
<div class="grid_12">
<div class="box">
    <div class="content">
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> View Installer
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="rowcat">
                    <div class="row">
                         <div class="col-md-12" >   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Installer Name</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->installer_name; ?><br>&nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Address</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->address;?>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Contact Name</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->contact_person;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Designation</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->designation;?>
                                </div>
                            </div>
                        </div>
                    </div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>City</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->city; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>State</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->state; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Pincode</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->pincode; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Longitude</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->longitude; ?>
                                </div>
                            </div>
                        </div>
					</div>
											
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Latitude</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->latitude; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Contact</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->contact; ?>
                                </div>
                            </div>
                        </div>
					</div>					
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Alternate No</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->contact1; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Mobile</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->mobile; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Email</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->email; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Fax No.</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->fax_no; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Website</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->website; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>About Installer</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->about_installer; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Installer Plan</strong></label>
                                <div class="col-md-7">
                                <?php  echo $customerData->sub_users .' User/'.$customerData->use_month." Month"; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Cumulative Rating.</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->cumulative_rating; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Application Code</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->application_code; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Rating Agency</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->rating_agency; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Agency Rating</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->rating; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>MNRE Rating</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->rating_category; ?>
                                </div>
                            </div>
                        </div>
					</div>	
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Branch Address</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->branch_address1; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Branch Address2</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->branch_address2; ?>
                                </div>
                            </div>
                        </div>
					</div>	
                   </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Branch Address3</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->branch_address3; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>PAN</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->pan; ?>
                                </div>
                            </div>
                        </div>
                    </div>  
				 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                               <label class="col-md-5 control-label"><strong>Status</strong></label>
                                <div class="col-md-7">
                                <?php echo (empty($customerData->status)?'In-Active':'Active'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>TIN</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->tin; ?>
                                </div>
                            </div>
                        </div>
					</div>
					 </div>
                     <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>10 kW SPV(Non-DCR)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->kw_non_dcr_10; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>10 kW SPV(DCR Category)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->kw_dcr_10; ?>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
                 <div class="row">
                         <div class="col-md-12"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>100 kW SPV(Non-DCR)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->kw_non_dcr_100; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>100 kW SPV(DCR Category)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->kw_dcr_100; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                    <div class="row">
                        <hr/>
                        <div class="col-md-12">
                             <?php
                               $latLng = $customerData->latitude.",".$customerData->longitude;;
                                $mapUrl = base64_encode(file_get_contents('https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=550x378&markers=color:blue%7C'.$latLng.'&sensor=false'));
                                ?>
                                <img alt="Opps! Map not show proper, please refress page Or check Lat Long" src="data:image/png;base64,<?php echo $mapUrl; ?>" />
                        </div>  
                    </div>  
                    <div class="row">
                        <hr/>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Work Area</strong></label>
                                <div class="col-md-8">
                                <?php foreach ($stateArr as $key => $value) {
                                   //print_r($value);
                                        echo (isset( $value->c['statename'])?$value->c['statename'].', ':''); 
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <hr/>
                        <div class="col-md-12">
                            <div class="col-md-2">
                            <strong>Rating Type</strong>
                            </div>
                            <div class="col-md-2">
                           <strong> Valid Upto</strong>
                            </div>
                            <div class="col-md-2">
                            <strong> Application No.</strong>
                            </div>
                            <div class="col-md-2">
                           <strong>  Name of Rating Agency</strong>
                            </div>
                            <div class="col-md-2">
                           <strong>  Agency Rating</strong>
                            </div>
                            <div class="col-md-2">
                             <strong>MNRE Rating</strong>
                            </div>
                        </div>
                    </div>

                    <?php
                    if(!empty($attingArr)){
                       foreach ($attingArr as $key => $value) {
                        if(empty($value['type']))
                            continue;
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-2">
                           <?php echo $value['type']?>
                            </div>
                            <div class="col-md-2">
                           <?php echo $value['validupto']?>
                            </div>
                            <div class="col-md-2">
                              <?php echo $value['appno']?>
                            </div>
                            <div class="col-md-2">
                              <?php echo $value['rate_agency']?>
                            </div>
                            <div class="col-md-2">
                            <?php echo $value['agency_rate']?>
                            </div>
                            <div class="col-md-2">
                            <?php echo $value['mnre_rate']?>
                            </div>
                        </div>
                    </div>
                     <?php } } else { ?>
                     <div class="row">
                        <hr/>
                        <div class="col-md-12">
                            <div class="col-md-4">
                           &nbsp;
                            </div>
                            <div class="col-md-4">
                                  No Record Found! 
                            <div class="col-md-4">
                             &nbsp;
                            </div>
                          
                        </div>
                    </div>
                     <?php } ?>   

                    <div class="row">
                        <hr/>
                        <div class="col-md-offset-5 col-md-6">
                            <button type="button" onclick="goback()" class="btn"><i class="fa fa-arrow-circle-left "></i> Back</button>
							 <a href="<?php echo WEB_ADMIN_URL?>InstallerCompanies/manage/<?php echo encode($id);?>" target="_blank"><button type="button" class="btn"><i class="fa fa-edit "></i> Edit</button></a>
							 <?php if(empty($customerData->status)){
								 ?>
								 <a href="<?php echo WEB_ADMIN_URL?>InstallerCompanies/enable/<?php echo encode($id);?>" ><button type="button" class="btn"><i class="fa fa-check-circle-o"></i> Enable</button></a>
								 <?php
								 
							 } else {
								 ?>
								 <a href="<?php echo WEB_ADMIN_URL?>InstallerCompanies/disable/<?php echo encode($id);?>" ><button type="button" class="btn"><i class="fa fa-circle-o"></i> Disable</button></a>
								 <?php
							 }?>
                        </div>
                    </div>
            </div>
        </div> 
    </div>
    </div>
    <?php } ?>
        <div class="row">
            <div class="col-md-12">         
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box blue-madison">
                     <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i> Branches of this company
                    </div>
                    <div class="tools">
                        <a href="" class="collapse" data-original-title="" title=""></a>
                    </div>
                </div>
                    <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="table-example">
                                <thead>
                                    <tr>
                                        <th class="sorting">ID</th>
                                        <th class="sorting">Company Title</th>
                                        <th class="sorting">Address</th>
                                        <th class="sorting">State</th>
                                        <th class="sorting">Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>    
                            </table>
                        </div> <!-- End of .content -->
                          </div>
                    </div> 
                    </div> 
                    <?php if($AjaxRequest=='0'){?>
</div>
<?php echo $this->Form->end(); ?>
    
<script type="text/javascript">
function goback()
{
    window.location.href=WEB_ADMIN_URL+'InstallerCompanies/index';
}
<?php 
echo $JqdTablescr;
?>
</script>
  <?php } ?>