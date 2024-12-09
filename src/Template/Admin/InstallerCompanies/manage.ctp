<style>
.col-md-6{
	padding-bottom: 5px;
}
</style>  
<?php  echo $this->Form->create($InstallerCompanies,array('class'=>'form-horizontal'));?>
<div class="grid_12">
<div class="box">
    <div class="content">
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Edit Installer
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
                                <label class="col-md-4 control-label"><strong>Installer Name</strong></label>
                                <div class="col-md-8">
                               <?php echo $this->Form->input('InstallerCompanies.installer_name',array('id'=>"company_name",'label' => false,'class'=>'form-control')); ?>
                                <?php echo $this->Form->hidden('InstallerCompanies.company_id',array('id'=>"company_id")); ?>
                                </div>
							</div>
                        </div>
                        <div class="col-md-6">
						 <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Address</strong></label>
                                <div class="col-md-8">
                                <?php echo $this->Form->input('InstallerCompanies.address',array('label' => false,'class'=>'form-control')); ?>
                                </div>
							</div>
                        </div>
					</div>
                    <div class="row">
                        <div class="col-md-6">
							 <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Contact Name</strong></label>
                                <div class="col-md-8">
                                <?php echo $this->Form->input('InstallerCompanies.contact_person',array('label' => false,'class'=>'form-control')); ?>
                                </div>
							</div>
                        </div>
                        <div class="col-md-6">
						 <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Designation</strong></label>
                                <div class="col-md-8">
                                 <?php echo $this->Form->input('InstallerCompanies.designation',array('label' => false,'class'=>'form-control')); ?>
                                </div>
							</div>	
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>City</strong></label>
                                <div class="col-md-5">
                                <?php
                                echo $this->Form->input('InstallerCompanies.city',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>State</strong></label>
                                <div class="col-md-8">
                                <?php
                                echo $this->Form->input('InstallerCompanies.state',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Pincode</strong></label>
                                <div class="col-md-8">
                                 <?php
                                echo $this->Form->input('InstallerCompanies.pincode',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Contact</strong></label>
                                <div class="col-md-8">
                                  <?php
                                echo $this->Form->input('InstallerCompanies.contact',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>					
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Mobile</strong></label>
                                <div class="col-md-8">
                                <?php echo $this->Form->input('InstallerCompanies.mobile',array('maxlength'=>10,'label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Email</strong></label>
                                <div class="col-md-8">
                                <?php
                                echo $this->Form->input('InstallerCompanies.email',array('data-msg-email'=>"Please enter a valid email address.", 'data-msg-required'=>"Please enter your email address.",'type'=>"email",'label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Fax No.</strong></label>
                                <div class="col-md-8">
								 <?php echo $this->Form->input('InstallerCompanies.fax_no',array('maxlength'=>10,'label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Website</strong></label>
                                <div class="col-md-8">
                                 <?php
                                echo $this->Form->input('InstallerCompanies.website',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Cumulative Rating.</strong></label>
                                <div class="col-md-8">
                                <?php echo $this->Form->input('InstallerCompanies.cumulative_rating',array('maxlength'=>10,'label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Application Code</strong></label>
                                <div class="col-md-8">
								 <?php echo $this->Form->input('InstallerCompanies.application_code',array('label' => false,'class'=>'form-control')); ?>
                                
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Rating Agency</strong></label>
                                <div class="col-md-8">
                                <?php echo $this->Form->input('InstallerCompanies.application_code',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Rating</strong></label>
                                <div class="col-md-8">
								<?php echo $this->Form->input('InstallerCompanies.rating',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>	
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Rating Category</strong></label>
                                <div class="col-md-8">
								<?php echo $this->Form->input('InstallerCompanies.rating_category',array('label' => false,'class'=>'form-control')); ?>
                                
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Branch Address</strong></label>
                                <div class="col-md-8">
                                <?php
								echo $this->Form->input('InstallerCompanies.branch_address1',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Branch Address2</strong></label>
                                <div class="col-md-8">
                                <?php
                                echo $this->Form->input('InstallerCompanies.branch_address2',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Branch Address3</strong></label>
                                <div class="col-md-8">
                                <?php
                                echo $this->Form->input('InstallerCompanies.branch_address3',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAN</strong></label>
                                <div class="col-md-8">
								 <?php
                                echo $this->Form->input('InstallerCompanies.pan',array('label' => false,'class'=>'form-control')); ?>
                                
                                </div>
                            </div>
                        </div>
					    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TIN</strong></label>
                                <div class="col-md-8">
								<?php
                                echo $this->Form->input('InstallerCompanies.tin',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                    </div>  
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>10 kW SPV(Non-DCR)</strong></label>
                                <div class="col-md-8">
								<?php
                                echo $this->Form->input('InstallerCompanies.kw_non_dcr_10',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>10 kW SPV(DCR Category)</strong></label>
                                <div class="col-md-8">
								<?php
                                echo $this->Form->input('InstallerCompanies.kw_dcr_10',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>100 kW SPV(Non-DCR)</strong></label>
                                <div class="col-md-8">
								<?php
                                echo $this->Form->input('InstallerCompanies.kw_non_dcr_100',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>100 kW SPV(DCR Category)</strong></label>
                                <div class="col-md-8">
								<?php
                                echo $this->Form->input('InstallerCompanies.kw_dcr_100',array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
					</div>	
					<div class="row">
						<div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>Status</strong></label>
                                <div class="col-md-8">
								 <?php
                                echo $this->Form->select('InstallerCompanies.status',array(0=>'In-Active',1=>'Active'),array('label' => false,'class'=>'form-control')); ?>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>About Installer</strong></label>
                                <div class="col-md-8">
								 <?php
                                echo $this->Form->input('InstallerCompanies.about_installer',array('label' => false,'class'=>'form-control')); ?>
                               
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="row">
						<div class="col-md-12">
							<input id="pac-input" class="form-control" type="text" placeholder="Search Box">
							<div id="myMap" style="width: 100%; height: 300px;"></div>                                   
							<div class="form-group">
								<div class="col-md-6">
									<label>Landmark </label>
									<input type="hidden" readonly="" value="<?php echo $InstallerCompanies->longitude; ?>" class="form-control" name="longitude" id="longitude">
									<input type="hidden" readonly="" class="form-control" value="<?php echo $InstallerCompanies->latitude; ?>" name="latitude" id="latitude">
									<input type="text" class="form-control" readonly="" name="landmark" id="landmark">
								</div>
							 </div>
						 </div>
				    </div>  
                    <div class="row">
						<div class="col-md-12">
							<div class="form-group">
							 <hr/>
								<div class="col-md-2">
								<strong>Rating Type</strong>
								</div>
								<div class="col-md-2">
							   <strong>&nbsp;Valid Upto</strong>
								</div>
								<div class="col-md-2">
								<strong>&nbsp;Application No.</strong>
								</div>
								<div class="col-md-2">
							   <strong>&nbsp;Name of Rating Agency</strong>
								</div>
								<div class="col-md-2">
							   <strong>&nbsp;&nbsp;&nbsp;Agency Rating</strong>
								</div>
								<div class="col-md-2">
								 <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MNRE Rating</strong>
								</div>
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
						<div class="form-group">
                            <div class="col-md-2">
							<?php
                                echo $this->Form->input('InstallerCompanies.type][',array('value'=>$value['type'],'label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
							<?php
                                echo $this->Form->input('InstallerCompanies.validupto][',array('value'=>$value['validupto'],'label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
							<?php
                                echo $this->Form->input('InstallerCompanies.appno][',array('value'=>$value['appno'],'label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
							<?php
                                echo $this->Form->input('InstallerCompanies.rate_agency][',array('value'=>$value['rate_agency'],'label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
							<?php
                                echo $this->Form->input('InstallerCompanies.agency_rate][',array('value'=>$value['agency_rate'],'label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
							<?php
                                echo $this->Form->input('InstallerCompanies.mnre_rate][',array('value'=>$value['mnre_rate'],'label' => false,'class'=>'form-control')); ?>
                            </div>
						</div>	
                        </div>
					</div>
                     <?php } } else { ?>
                     <div class="row">
						<div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label>Grid-Connected</label>
                               <p>Rating</p>
                            </div>
							<div class="col-md-2">
                            	<?php
                                echo $this->Form->hidden('InstallerCompanies.type][',array('value' => "Grid-Connected")); ?>
                                <?php
                                echo $this->Form->input('InstallerCompanies.validupto][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.appno][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
							<div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.rate_agency][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
							<div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.agency_rate][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
							<div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.mnre_rate][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2">
                                <label>Off-Grid</label>
                               <p>Rating</p>
                            </div>
							<div class="col-md-2">
								<?php
								echo $this->Form->hidden('InstallerCompanies.type][',array('value' => "Off-Grid")); ?>
								<?php
								echo $this->Form->input('InstallerCompanies.validupto][',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-2">
								<?php
								echo $this->Form->input('InstallerCompanies.appno][',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-2">
								<?php
								echo $this->Form->input('InstallerCompanies.rate_agency][',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-2">
								<?php
								echo $this->Form->input('InstallerCompanies.agency_rate][',array('label' => false,'class'=>'form-control')); ?>
							</div>
							<div class="col-md-2">
								<?php
								echo $this->Form->input('InstallerCompanies.mnre_rate][',array('label' => false,'class'=>'form-control')); ?>
							</div>
						</div>
                        <div class="form-group">
                            <div class="col-md-2">
                                <label>RESCO</label>
								<p>Rating</p>
                            </div>
							<div class="col-md-2">
                            	<?php
                                echo $this->Form->hidden('InstallerCompanies.type][',array('value' => "RESCO")); ?>
                                <?php
                                echo $this->Form->input('InstallerCompanies.validupto][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.appno][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
							<div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.rate_agency][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
							<div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.agency_rate][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
							<div class="col-md-2">
                                <?php
                                echo $this->Form->input('InstallerCompanies.mnre_rate][',array('label' => false,'class'=>'form-control')); ?>
                            </div>
                        </div>
                    </div>
					</div>
                     <?php } ?>
					<br/>		
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-2">
									<label><strong>Central</strong></label>
								</div>
								<div class="col-md-2">
									<label><strong>East</strong></label>
								</div>
								<div class="col-md-2">
									<label><strong>North</strong></label>
								</div>
								<div class="col-md-2">
									<label><strong>North-East</strong></label>
								</div>
								<div class="col-md-2">
									<label><strong>South</strong></label>
								</div>
								<div class="col-md-2">
									<label><strong>West</strong></label>
								</div>
							</div>
						
					 
                        <div class="form-group">
							<div class="col-md-2">
                                <?php foreach ($central_states as $key => $value) {
									?>
									<label>
									<input type="checkbox" name="InstallerCompanies[installer_state][]" id="<?php echo $value->id?>" value="<?php echo $value->id?>">
                                <?php echo $value['statename']; ?></label></br>
                                    <?php
                                }?>
                            </div>
                            <div class="col-md-2">
                                <?php foreach ($east_states as $key => $value) {
                                    ?>
                                    <label>
									<input type="checkbox" name="InstallerCompanies[installer_state][]" id="<?php echo $value->id?>" value="<?php echo $value->id?>">
                                     
                                <?php echo $value['statename']; ?></label></br>
                                    
                                    <?php
                                }?>
                            </div>
                            <div class="col-md-2">
                               <?php foreach ($north_states as $key => $value) {
                                    ?>
									 <label>
                                       <input type="checkbox" name="InstallerCompanies[installer_state][]" id="<?php echo $value->id?>" value="<?php echo $value->id?>">
                                <?php echo $value['statename']; ?> </label></br>
                                    <?php
                                }?>
                            </div>
                            <div class="col-md-2">
                                <?php foreach ($north_east_states as $key => $value) {
                                    ?>
									 <label>
                                      <input type="checkbox" name="InstallerCompanies[installer_state][]" id="<?php echo $value->id?>" value="<?php echo $value->id?>">
                                <?php echo $value['statename']; ?> </label></br>
                                    <?php
                                }?>
                            </div>
                             <div class="col-md-2">
                                <?php foreach ($south_states as $key => $value) {
                                    ?> <label>
                                        <input type="checkbox" name="InstallerCompanies[installer_state][]" id="<?php echo $value->id?>" value="<?php echo $value->id?>">
                                <?php echo $value['statename']; ?> </label></br>
                                    <?php
                                }?>
                            </div>
                            <div class="col-md-2">
                               <?php foreach ($west_states as $key => $value) {
                                    ?> <label>
                                       <input type="checkbox" name="InstallerCompanies[installer_state][]" id="<?php echo $value->id?>" value="<?php echo $value->id?>">
                                <?php echo $value['statename']; ?> </label></br>
                                    <?php
                                }?>
                            </div>
                        </div>
					</div>	
                    </div>					 
					<div class="row">
                        <hr/>
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
<?php echo $this->Form->end(); ?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDqfWF0vAnh-vajop-4cCralyhh51uT2Mk&libraries=places"></script>
<script type="text/javascript" src="<?php echo URL_HTTP?>js/googleMap.js"></script>
<script type="text/javascript">
<?php
if(!empty($stateArr)){
 foreach($stateArr as $key=>$valArr){
	?>
	$('#<?php echo $valArr->c['id'];?>').attr('checked',true);
	$('#<?php echo $valArr->c['id'];?>').parent().addClass('chacked');
<?	
}	
}
 ?>

function goback()
{
    window.location.href=WEB_ADMIN_URL+'InstallerCompanies/index';
}

</script>
