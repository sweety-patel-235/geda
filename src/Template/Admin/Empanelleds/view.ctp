<?php
    /*Energy Month Chart Data Create*/
    $monthChart = '';
    if(isset($resultArr['monthChart']) && !empty($resultArr['monthChart'])) {
        $monthChartData =  json_decode($resultArr['monthChart']);
        foreach($monthChartData as $data) { 
            $dateObj   = DateTime::createFromFormat('!m', $data->x);
            $monthName = $dateObj->format('M'); 
            $monthChart .= "['".$monthName."',".$data->y."],";
        }
        $monthChart = rtrim($monthChart,",");   
    } 

    /*Energy Year Chart Data Create*/
    $yearChart = '';
    if(isset($resultArr['yearChart']) && !empty($resultArr['yearChart'])) {
        $yearChartData =  json_decode($resultArr['yearChart']);
        foreach($yearChartData as $data) { 
            $yearChart .= "['".$data->x."',".$data->y."],";
        }
        $yearChart = rtrim($yearChart,",");   
    }
	$blnEditProjectRights = $Userright->checkadminrights($Userright->EDIT_PROJECT);
    
?>
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
<div class="grid_12">
<div class="box">
    <div class="content">
	<?php echo $this->Flash->render('cutom_admin');?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> View Project (<?php echo $customerData->id;?>)
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="rowcat">
					 <div class="row">
                       <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Project Name</strong><br>&nbsp;</label>
                                <div class="col-md-7">
								<?php echo $customerData->name;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Address<br>&nbsp;</strong></label>
                                <div class="col-md-7">
                                <?php echo (!empty($customerData->address)?$customerData->address:$customerData->landmark); ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Customer Name</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerArr[0]->name; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Created Date</strong></label>
                                <div class="col-md-7">
                                <?php echo date('d-m-Y',strtotime($customerData->created)); ?>
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
                                <label class="col-md-5 control-label"><strong>Verification Code</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->verification_code; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Solar Radiation</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->solar_radiation; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Area</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->area; ?>  <?php echo $areaTypeArr[$customerData->area_type]; ?>
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
                                <?php echo ucwords($customerData->status); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Customer Type</strong></label>
                                <div class="col-md-7">
                                <?php echo $custTypeArr[$customerData->customer_type]; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Capacity (Kw)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->capacity_kw; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Estimated Cost</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->estimated_cost; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Estimated Cost With Subsidy</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->estimated_cost_subsidy; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Estimated Energy(kwh/year)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->estimated_kwh_year; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Recommended Capacity</strong></label>
                                <div class="col-md-7">
                                <?php  echo $customerData->recommended_capacity; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Maximum Capacity</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->maximum_capacity; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Average Monthly Bill</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->avg_monthly_bill; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Contract Load</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->contract_load; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Back Up Type</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->backup_type; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Diesel Genset(Kvh)</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->diesel_genset_kva; ?>
                                </div>
                            </div>
                        </div>
					</div>	
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Cost of Solar</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->cost_solar; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Solar Ratio</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->solar_ratio; ?>
                                </div>
                            </div>
                        </div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Payback</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->payback; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"><strong>Estimated Saving/Month</strong></label>
                                <div class="col-md-7">
                                <?php echo $customerData->estimated_saving_month; ?>
                                </div>
                            </div>
                        </div>
					</div>	
					</div>
                </div>
                    <div class="row">
                        <hr/>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <?php
                               $latLng = $customerData->latitude.",".$customerData->longitude;;
                                $mapUrl = base64_encode(file_get_contents('https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=550x378&markers=color:blue%7C'.$latLng.'&sensor=false'));
                                ?>
                                <img alt="Opps! Map not show proper, please refress page Or check Lat Long" src="data:image/png;base64,<?php echo $mapUrl; ?>" />
                            </div>
                            <div class="col-md-6">
                            <div class="col-md-12">
                                <form id="formmain" method="POST" action="/admin/projects/mailprojectdetail/<?php echo encode($id);?>" onSubmit="return validateForm();">
                                <div class="row">
                                      <div class="col-md-10"> <label class="col-md-12 control-label"> <i class="fa fa-user"></i><strong>Installer List</strong></label> </div> 
                                      <div class="col-md-1" style="margin: 5px -10px !important;"> 
                                            
                                       </div>
                                        <div class="col-md-1" style="margin: 5px -10px !important;">      
                                          
                                      </div>
                                </div>
								<div style="max-height:310px;overflow:auto;margin-bottom:0px; border-top: 1px solid #ccc;">
								<table class="table table-bordered table-hover" style="margin-bottom:0px">
									<thead>
										<tr>
											<th> # </th>
											<th> Id </th>
											<th> Installer Name </th>
											<th> Email </th>
										</tr>
									</thead>
									<tbody>
						      <?php 
								$i = 0;
								 if(!empty($projectInstallers))	{
									foreach($projectInstallers as $key => $value) {
										$i++;
                                        ?>
										<tr>
											<td> <?php 
                                                     if(!empty($value->installers['email'])){?>
                                                     <input type="checkbox" id="<?php echo $value->installers['id'] ?>" value="<?php echo $value->installers['id'] ?>" class="installerMail" name="installer[<?php echo $value->installers['id'] ?>]">
                                                      <?php
                                                     }   
                                                  ?> </td>
											<td> <?php echo $i;?> </td>
											<td> <?php echo $value->installers['installer_name']; ?></strong> &nbsp;<?php echo $value->installers['city']; ?> </td>
											<td> <?php echo $value->installers['email']; ?> </td>
										</tr>
									
                                    <?php      
                                    }
									?>
									</tbody>
									</table>
									</div>
									<table class="table table-bordered table-hover" style="background-color:#FFF">
									<tr>
											<td width="10px">  
											<?php if($blnEditProjectRights){?>
											<input type="checkbox" id="selectAll" value="selectAll" name="installerAll"> <?php } ?></td>
											<td>
											<?php if($blnEditProjectRights){?>
											 <button class="btn btn btn-sm btn-success"  type="submit"><i class="fa fa-envelope"></i>&nbsp;Send Email</button>
											 <?php } ?>
											 </td>
									</tr>
									</table>		
									<?php
								 }
								 else{
									 ?>
									<tr>
										<td colspan="4"> User not selected any Installers! </td>
									</tr>
									</table>
									</div>
									<?php    
								 }
                              ?>
							 </div> 
					      </form>
                      </div>
                    </div>
					</div>
                    <div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<div id="month_data_chart" class="month_chart"></div> 
							</div>
							<div class="col-md-6">
								 <div id="year_data_chart" class="year_chart"></div>
							</div>
						</div>
                    </div> 
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<img width="550px" src="data:image/png;base64,<?php echo base64_encode(file_get_contents($paybackGraphImg)); ;?>"> 
							</div>
							<div class="col-md-6">
								 &nbsp;
							</div>
						</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            &nbsp;
                        </div>
                        <div class="col-md-3">
						<?php if($blnEditProjectRights){ ?>
                             <a href="/admin/projects/viewreport/<?php echo encode($id); ?>">Download Report</a>
						 <?php } ?>	 
                        </div>
                    </div>   
                    <div class="row">
                        <hr/>
                        <div class="col-md-offset-5 col-md-6">
                            <button type="button" onclick="goback()" class="btn"><i class="fa fa-arrow-circle-left "></i> Back</button>
                        </div>
                    </div>
          </div>  
        </div> 
    </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
function validateForm()
{
	var i =0;
	$(".installerMail").each(function(){
		if($(this).is(':checked')){
			i++;
		}
	});
	if(i==0)
	{
		alert("Please select installers from list");
		return false;	
	}else
	{
		return true;
	}
	
	
	
}
function goback()
{
    window.location.href=WEB_ADMIN_URL+'projects/index';
}
$(document).ready(function(){
    $('#selectAll').change(function() {
    if(this.checked){
         $(".installerMail").attr('checked', "checked");
         $(".installerMail").parent().addClass('checked');
     }
     else
     {
         $(".installerMail").removeAttr('checked');
         $(".installerMail").parent().removeClass('checked');
     }
    });
});

</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(monthDrawChart);
    function monthDrawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Energy', 'Month'],
          <?php echo $monthChart; ?>
        ]);

        var view = new google.visualization.DataView(data);
        var options = {
            title: "Month Energy Chart",
            width: '100%',
            height: 400,
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("month_data_chart"));
        chart.draw(view, options);
    }

    google.charts.setOnLoadCallback(yearDrawChart);
    function yearDrawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Energy', 'Year'],
          <?php echo $yearChart; ?>
        ]);

        var view = new google.visualization.DataView(data);
        var options = {
            title: "Year Energy Chart",
            width: '100%',
            height: 400,
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("year_data_chart"));
        chart.draw(view, options);
    }
    
</script>
