<?php
    $this->Html->addCrumb($pageTitle); 
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>AHA Solar Rooftop Helper (“the AHA”) App offers solar estimation with approximate cost, applicable government incentives, finance, and information about your nearby Solar PV Rooftop Installer across several cities in India. We offer a common platform for end consumers and Solar PV Installers to become a part of the solar revolution.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"><h4><strong>Solar Calculator</strong></h4></div>
        </div>
        <div class="row" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
            <div class="col-md-12">
                <form id="solarcalculator" action="/solarCalculators/solar_calculator" onsubmit="return validateForm();"  method="POST">
                    <div class="row">
						<div class="form-group">
                            <div class="col-md-12">
                                <label>Solar Project Location *<label>                        
                            </div>                                
                        </div> 
                        <div class="form-group">
                            <div class="col-md-12">
                                <input id="pac-input" class="form-control" type="text" placeholder="Search Box">
                                <div id="myMap"></div>                                   
                            </div>                                
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Landmark </label>
                                <input type="hidden" readonly="" class="form-control" name="longitude" id="longitude">
                                <input type="hidden" readonly="" class="form-control" name="latitude" id="latitude">
                                <input type="text" class="form-control" readonly="" name="landmark" id="landmark">
                            </div>
                            <?php if(!empty($customerId)) { ?>
                            <div class="col-md-6">
                                <label>Project Name *</label>
                                <?php 
                                echo $this->Form->input('proj_name',array('label' => false,'class'=>'form-control')); ?>
                            </div>
                            <?php } else { ?>
                                &nbsp;    
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Customer Type *</label>
                                <?php 
                                echo $this->Form->select('project_type',$projectTypeArr,array('label' => false,'class'=>'form-control','id'=>'project_type')); ?>
                            </div>
                            <div class="col-md-6">
                                <label>Area Type *</label>
                                <?php
                                echo $this->Form->select('area_type',$areaTypeArr,array('label' => false,'class'=>'form-control','id'=>'area_type')); ?>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Rooftop Area *</label>
                                <input type="text" maxlength="10" class="form-control" name="area" id="area" onkeyup="if(this.value==''){alert('Please enter numeric value');}else if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}">
                            </div>
                            <div class="col-md-6">
                                <label>Average Monthly Bill *</label>
                                <input type="text" maxlength="10" class="form-control" name="avg_monthly_bill" id="avg_monthly_bill"  onkeyup="if(this.value==''){alert('Please enter numeric value');}else if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');}">
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Average Monthly Unit Consumed *</label>
                                <input type="text" maxlength="10" class="form-control" name="energy_con" id="energy_con" onkeyup="if(this.value==''){alert('Please enter numeric value');}else if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'');} ">
                            </div>
                            <div class="col-md-6">
                                <label>Backup Type</label>
                                <?php
                                echo $this->Form->select('backup_type',$backupTypeArr,array('label' => false,'class'=>'form-control','empty'=>'None','onChange'=>'displayUsageHours(this.value)')); ?>
                            </div>
                           
                        </div>
                    </div>
                     <div class="row" id="usage_hours_div">
                        <div class="form-group">
                             <div class="col-md-6" >
                                <label>Hours of Usage</label>
                                <input type="text" maxlength="10" class="form-control" name="usage_hours" id="usage_hours"> 
                            </div>
                            <div class="col-md-6">
                              &nbsp;
                           </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" value="Get Result" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDqfWF0vAnh-vajop-4cCralyhh51uT2Mk&libraries=places"></script>
<script type="text/javascript" src="js/googleMap.js"></script>
<script type="text/javascript">
function validateForm() {
     
    if ($('#project_type').val() == '') {
        alert("Please select value for customer type");
        return false;
        }
	 if ($('#area_type').val() == '') {
        alert("Please enter numeric value for area type");
        return false;
        }
	 if ($('#area').val() == '') {
        alert("Please enter numeric value for rooftop area");
        return false;
    }
	 if ($('#avg_monthly_bill').val() == '') {
        alert("Please enter numeric value for average monthly bill");
        return false;
    }
	 if ($('#energy_con').val() == '') {
        alert("Please enter numeric value for monthly unit consumed");
        return false;
    }
	return true;
}
displayUsageHours();
function displayUsageHours(value) { 
    if(value > 0) {
        $('#usage_hours_div').css('display','');
    } else {
        $('#usage_hours_div').css('display','none');
    }
}
</script>
