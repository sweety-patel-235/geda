<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Project Page</title>
        <!-- Style CSS -->
        <link type="text/css" rel="stylesheet" media="all" href="css/style.css"  />
        
    </head>
    <body id="pdf-header">
        <div id="footer">
            <table>
                <tr>
                    <td>
                    Project Summary Report for <?php echo $project->capacity_kw; ?> KW Rooftop Solar PV System at <?php echo $project->address.", ".$project->city.", ".$project->state; ?>
                    </td>
                    <td align="right"><p class="page">Page <p></td>
                </tr>
            </table>
        </div>
        <div class="container">
            
            <div class="mainbox">
                <table>
                    <tr>
                        <td align="right">
                        <p>Date:<span> <?php echo $project->created->format("m/d/Y"); ?></span></p>
                        <p>Report No.<span>AHR16JA00000<?php echo $project->id; ?></span></p>
                        </td>
                    </tr>
                        <tr>
                        <td align="center">
                             <h1 class="text-center" style="font-size:30px;color:#333">Project Summary Report for <span class="red"><?php echo $project->capacity_kw; ?></span> KW Rooftop Solar PV System at <span class="red"><?php echo $project->address.", ".$project->city.", ".$project->state; ?></span></h1>
                  
                       <img src="images/rooftop-solar.jpg" style="height:800px"> 
                  </td>
              </tr>
                  
                </table>
                <div class="page-break"></div>
                <h1 style="color: #FDC426;">Introduction of AHA! App</h1>
                <p>AHA Solar Rooftop Helper App(AHA!) and the Website offers solar power estimation with approximate cost, applicable government incentives, finances, and information about your nearby Solar PV Rooftop Installers (the “Installer”). The app is availableon various platforms like android, iOS and Windows across several cities in India and also provides project management tool for Installers to carry out feasibility studies, site surveys, design and preparation of techno-commercial proposals for their customers. We offer a common platform for end consumers and Solar PV Installers to become a part of the solar revolution.</p>
                
                <h1>About Company & Team</h1>
                <p>At AHA! wearea team of dedicated professionals passionate about bringing a revolution in the solar energy sector of India. With entrepreneurial talent from diverse technical and management backgrounds,we bring diverse expertise encompassing all aspects of Solar PV industry. Our salient skills are tech knowhow, marketanalysis and understanding along with suggestions for financing projects in the Indian scenario.</p>
                <p>AHA! tracks the Solar PV market on real-time basis with its extensive and active network thus connecting you to industry experts, government officials and policy makers. AHA! is knowledge-driven, analytical in approach and believes in output-oriented approach in all processes. This reflects in the tools we use for calculating solar capacity on rooftop, building bridges between installers and end customers.</p>
                <p>Lastly, along with providing project management and strategic planning services we also strive to provide a transparent and reliable service.</p>
                <p>Further, we intend to provide complete solar solutions platform to residential, commercial and industrial establishments. Weemphasize on customized solutions in the Indian solar rooftop space as the solution for reducing power costs alongwith the benefit of promoting clean energy.</p>
                
                
                <!--  <div class="pagebrack"></div> -->
                <!--   <hr/> -->
                
                <div class="page-break"></div>
                
                <h1>Input by the User</h1>
                
                <table border="0" cellpadding="5" >
                    <tr>
                        <td width="50%">
                            <img src="<?php echo $mapImage; ?>" >
                        </td>
                        <td width="50%">
                            <div class="border-top">
                                <p><b>Input by</b> <span class="red"><?php echo ucfirst($project->customer['name']); ?></span></p>
                                <p class="green">Type of Customer</p>
                                <p class="red"><?php echo ucfirst($project->custtype['para_value']); ?></p>
                                <p class="green">Average Energy Consumption</p>
                                <p><?php echo $project->estimated_kwh_year;?> kW</p>
                                <p class="green">Average Monthly Bill</p>
                                <p><span class="green">Rs.</span><span class="red"><?php echo $project->avg_monthly_bill;?></span> per month</p>
                                <p class="green">Type of Back-up power used</p>
                                <p class="red">
                                    <?php 
                                    switch($project->backup_type){
                                        case 0 : $bkType = "No"; break;
                                        case 1 : $bkType = "Diesel"; break;
                                        case 2 : $bkType = "Inverter"; break;
                                        default : $bkType = "No"; break;
                                    };
                                    echo $bkType;
                                    ?></p>
                                <p class="green">Back-up Usage (if applicable)</p>
                                <p><span class="red">
                                    <?php echo ($project->backup_type > 0 ) ? $project->usage_hours.'</span>&nbsp;<span>hours per day</span>' : "N/A";?>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
                
                
                <h1>Assumptions</h1>
                <table border="0" cellpadding="5">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="border-top">
                                <p><b>Performance Parameter</b></p>
                                <p class="green">Operation and Maintenance Cost</p>
                                <p><?php echo $project->op_maintence_cost_month;?>% of Asset Value</p>
                                <p class="green">Operation and Maintenance Cost</p>
                                <p><?php echo $project->assu_om_cost;?>% annually</p>
                                <p class="green">Annual Performance Deration</p>
                                <p><?php echo $project->assu_escalation_om;?>% per year</p>
                            </div>
                        </td>
                        <td  width="50%"  valign="top">
                            <div class="border-top">
                                <p><b>Financial Parameter</b></p>
                                <p class="green">Debt</p>
                                <p><?php echo $project->assu_debt;?>%</p>
                                <p class="green">Insurance Cost</p>
                                <p class="green"><?php echo $project->assu_insurance_cost;?>% of Project Cost</p>
                                <p class="green">Depreciation</p>
                                <p>First 10 years– <?php echo $project->assu_rate_depre_for_10;?>%</p>
                                <p>Next 15 years – <?php echo $project->assu_rate_depre_next_15;?>%</p>
                                <p class="green">Accelerated Depreciation* (if applicable)</p>
                                <p><?php echo $project->assu_accelerated_depre;?>%</p>
                                <p class="green">Corporate Tax (if applicable)</p>
                                <p>0%</p>
                                <br/>
                            </div>
                        </td>
                    </tr>
                </table>
                <p><i>* Note: Accelerated Depreciation is applicable for the solar PV systems of 500 kW and abovein Industrial and commercial segments.</i></p>
                
                <div class="page-break"></div>
                
                <h1>Average Yearly Radiation For<sup>1</sup></h1>
                <table border="0" cellpadding="5" >
                    <tr>
                        <td width="50%">
                            <img src="<?php echo $radiationGraphImg; ?>">
                        </td>
                        <td width="50%">
                            <div class="border-top">
                                <p><b>QUARTER 1</b></p>
                                <p><b>467.70 kWh/m<sup>2</sup></b></p>
                                <p><b>QUARTER 2</b></p>
                                <p class="green">574.50 kWh/m<sup>2</sup></p>
                                <p><b>QUARTER 3</b></p>
                                <p><b>444.00 kWh/m<sup>2</sup></b></p>
                                <p><b>QUARTER 4</b></p>
                                <p><b>423.90 kWh/m<sup>2</sup></b></p>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                
                <hr/>
                <br>
                <h1>Results</h1>
                <div>
                    <table class="table-responsive t1">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Particulars</th>
                                <th>Units</th>
                                <th>Recommended by AHA!</th>
                                <th>Maximum Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A.</td>
                                <td>Recommended Capacity</td>
                                <td>kW</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                            <tr>
                                <td>B.</td>
                                <td>Estimated Cost</td>
                                <td>Rs.</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                            <tr>
                                <td>C.</td>
                                <td>Subsidy</td>
                                <td>Rs.</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                            <tr>
                                <td>D.</td>
                                <td>Cost of Solar Energy</td>
                                <td>Rs.</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                            <tr>
                                <td>E.</td>
                                <td>Payback</td>
                                <td>Rs.</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                            <tr>
                                <td>F.</td>
                                <td>Savings</td>
                                <td>Rs./yr</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                            <tr>
                                <td>G.</td>
                                <td>Total Subsidy</td>
                                <td>Rs/kW</td>
                                <td><span class="red">"No. from the App"</span></td>
                                <td><span class="red">"No. from the App"</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <p class="red">Graph to show that what percentage is recommended of maximum value</p>
                    <p class="border-top padtop5"><sup>1</sup> Numbers written in Quarter are variable and will change in all the reports: Reference Formula <sum of 3 months ></p>
                    <div class="border-top-yellow">&nbsp;</div>
                </div>
                
                <div class="clearfix"></div>
                
                <div class="page-break"></div>
                
                <section class="yellowh1 notoppad">
                    <h1>System specification</h1>
                    <div>
                        
                        <ol type="a">
                            <li>Solar PV Module Capacity: &nbsp; 250 kW x (&lt;<span class="red">Recommended Capacity</span>&gt; x 1000/250) nos.</li>
                            <li>Inverter Capacity : <span class="red">Recommended Capacity</span></li>
                            <li>Default Angle: &nbsp;&lt;<span class="red">Latitude of the Location</span>&gt;</li>
                        </ol>
                    </div>
                </section>
                
                <div class="clearfix"></div>
                <hr/>
                <br>
                <h1>Typical Grid-Connected l Solar PV System</h1>
                <div>
                    <br>
                    <img src="images/flow.png" >
                    <br>
                    <div class="page-break"></div>
                
                    <table class="table-responsive t2 margintop20">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Month</th>
                                <th>Energy Generated</th>
                                <th>Saving</th>
                            </tr>
                            <tr class="thcustom">
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>kWh</th>
                                <th>In Rs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>January</td>
                                <td class="red">3,527</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>February</td>
                                <td class="red">3,624</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>March</td>
                                <td class="red">4,671</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>April</td>
                                <td class="red">4,862</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>May</td>
                                <td class="red">5,165</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>June</td>
                                <td class="red">4,498</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>July</td>
                                <td class="red">3,707</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td>August</td>
                                <td class="red">3,519</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>September</td>
                                <td class="red">3,898</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>10.</td>
                                <td>October</td>
                                <td class="red">4,013</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>11.</td>
                                <td>November</td>
                                <td class="red">3,451</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>12.</td>
                                <td>December</td>
                                <td class="red">3,252</td>
                                <td><span class="red">-</span></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Annual</td>
                                <td class="red">48,191</td>
                                <td><span class="red">&nbsp;</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <p>The recommended size of the rooftop solar PV system will cover 53%of your electricity usage.</p>
                </div>
                
                <div class="clearfix"></div>
                
                <h1>Energy Generation Graph for Recommended Value</h1>
                <br>
                <img src="<?php echo $energyGraphImg; ?>" >
                <br>
                <div class="page-break"></div>
                
                <h1>Environment Benefits</h1>
                <br>
                <img src="images/chart2.png" >
                <br>
                
                <div class="clearfix"></div>
                
                <hr/>
                <h1>Installers Contacted</h1>
                <div>
                    <table class="t3">
                        <?php if(!empty($projectInstallers)){
                            foreach ($projectInstallers as $key => $val) {       
                        ?>
                        <tr>
                            <td class="border-top-yellow" valign="top"><b>Installer <?php echo ($key + 1); ?></b></td>
                            <td valign="top">:</td>
                            <td class="border-top-yellow">
                                <p><?php echo $val->installers['installer_name']; ?></p>
                                <p><?php echo $val->installers['address']; ?></p>
                                <p><?php echo $val->installers['contact']; ?></p>
                                <p><?php echo $val->installers['contact1']; ?></p>
                            </td>
                        </tr>
                        <?php } 
                        }else{ ?>
                        <tr>
                            <td align="center">No Installer Found!</td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                </div>
                <hr/>
                <div class="clearfix"></div>
                
                
                <h1>Summary</h1>
                <p>The recommended rooftop solar PV system (RSPVS) as per your requirement is <span class="red">25 kW.</span> The capacity is determined considering your electricity usage, relevant policy and regulations of your State Government, Government of India and the inputs given by you.</p>
                <p>An approximate cost of the RSPVS will be around <span class="red">Rs. 16,50,000 /- to Rs. 20,00,000/-</span> with a payback of <span class="red">7.5</span> yearsconsidering subsidy and <span class="red">8.5</span> years without subsidy. You are eligible for a subsidy <span class="red">30% on capital cost of RSPVS</span> from Government of India and <span class="red">10% on the capital cost of the RSPVS</span> from the state government.</p>
                <p>If you are a profit making Company then you can also avail benefit of 80% Accelerated Depreciation for your RSPVs of above 500 kW. This is applicable only for Industrial or Commercial User.</p>
                
                <div class="page-break"></div>
                
                <div class="mainboxlast">
                    <header>
                        <div class="row">
                          <div class="logo">
                              <img src="images/logo.png" >
                          </div>
                        </div>    
                    </header>
                       <br>
                        <img style="width:100%" src="images/bg.png">    
                   <br>
                    <section>
                            <table class="t4">
                                <tr>
                                    <td>Powered By</td>
                                    <td><img src="images/poweredby.png" class="img-responsive center-block"></td>
                                </tr>
                                <tr>
                                    <td>Knowledge Partner</td>
                                    <td><img src="images/germi.png" class="img-responsive center-block"></td>
                                </tr>
                            </table>
                         
                    </section>
                    <div class="clearfix"></div>   
                    <div class="customfooter">
                        <p>AHA Rooftop Solar Helper<br/>
                        First Floor, 23 Bougainvillea Marg, DLF Phase 2, Gurgaon - 122 008, Haryana, INDIA<br/>
                        Phone: +91 997 446 2506 ; Email: info@ahasolar.in<br/>
                        Website: www.ahasolar.in</p>
                    </div> 
                    <div class="clearfix"></div> 
                </div>
                
                
            </div>
        </div>
        
        
    </body>
</html>