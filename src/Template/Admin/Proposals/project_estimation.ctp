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
                    <?php 
                    $project->address = str_replace("Unnamed Road,", "", $project->address);
                    $project->address = str_replace($project->city.",", "", $project->address);  
                    $project->address = str_replace($project->state.",", "", $project->address);  
                    $project->address = str_replace(trim($project->country), "", $project->address);  
                    $project->address = rtrim($project->address,",");
                     ?>
                    Project Summary Report for <?php echo $project->recommended_capacity; ?> KW Rooftop Solar PV System
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
                        <p>Date:<span> <?php echo  date("m/d/Y",strtotime($project->created));?></span></p>
                        <p>Report No.<span><?php echo $projectReportId ?></span></p>
                        </td>
                    </tr>
                        <tr>
                        <td align="center">
                             <h1 class="text-center" style="font-size:30px;color:#333">Project Summary Report for <span ><?php echo $project->recommended_capacity; ?></span> KW Rooftop Solar PV System at <span ><?php echo $project->address.", ".$project->city.", ".$project->state; ?></span></h1>
                  
                       <img src="images/rooftop-solar.jpg" style="height:800px"> 
                  </td>
              </tr>
                  
                </table>
                <div class="page-break"></div>
                <h1 style="color: #FDC426;">Introduction of AHA! App</h1>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AHA Solar Rooftop Helper App (AHA!) and the Website offers solar power estimation with approximate cost, applicable government incentives, finances, and information about your nearby Solar PV Rooftop Installers (the “Installer”). The app is available on various platforms like Android, iOS and Windows across several cities in India and also provides project management tool for Installers to carry out feasibility studies, site surveys, design and preparation of techno-commercial proposals for their customers. We offer a common platform for end consumers and Solar PV Installers to become a part of the solar revolution.</p>
                
                <h1>About Company & Team</h1>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;At AHA! we are a team of dedicated professionals passionate about bringing a revolution in the solar energy sector of India. With entrepreneurial talent from diverse technical and management backgrounds,we bring diverse expertise encompassing all aspects of Solar PV industry. Our salient skills are tech know how, market analysis and understanding along with suggestions for financing projects in the Indian scenario.</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AHA! tracks the Solar PV market on real-time basis with its extensive and active network thus connecting you to industry experts, government officials and policy makers. AHA! is knowledge-driven, analytical in approach and believes in output-oriented approach in all processes. This reflects in the tools we use for calculating solar capacity on rooftop, building bridges between installers and end customers.</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lastly, along with providing project management and strategic planning services we also strive to provide a transparent and reliable service.</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Further, we intend to provide complete solar solutions platform to residential, commercial and industrial establishments. We emphasize on customized solutions in the Indian solar rooftop space as the solution for reducing power costs alongwith the benefit of promoting clean energy.</p>
                
                
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
                                <p><b>Input by</b> <span class="green"><?php echo ucfirst($project->customer['name']); ?></span></p>
                                <p>Type of Customer</p>
                                <p class="green"><?php echo ucfirst($project->custtype['para_value']); ?></p>
                                <p>Average Energy Consumption</p>
                                <p  class="green"><?php echo $project->estimated_kwh_year;?> kWh</p>
                                <p>Average Monthly Bill</p>
                                <p class="green"><span >Rs.</span><span ><?php echo $project->avg_monthly_bill;?></span> per month</p>
                                <p>Type of Back-up power used</p>
                                <p class="green">
                                    <?php 
                                    switch($project->backup_type){
                                        case 0 : $bkType = "No"; break;
                                        case 1 : $bkType = "Diesel"; break;
                                        case 2 : $bkType = "Inverter"; break;
                                        default : $bkType = "No"; break;
                                    };
                                    echo $bkType;
                                    ?></p>
                                <p>Back-up Usage (if applicable)</p>
                                <p class="green"><span >
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
                                <p>Operation and Maintenance Cost</p>
                                <p><?php echo O_AND_M_COST;?>% of Asset Value</p>
                                <p>Operation and Maintenance Cost</p>
                                <p><?php echo O_AND_M_ESCLATION;?>% annually</p>
                                <p>Annual Performance Deration</p>
                                <p><?php echo ANNUAL_DEGREDATION;?>% per year</p>
                            </div>
                        </td>
                        <td  width="50%"  valign="top">
                            <div class="border-top">
                                <p><b>Financial Parameter</b></p>
                                <p>Debt</p>
                                <p><?php echo DEBT_FRATION;?>%</p>
                                <p>Insurance Cost</p>
                                <p><?php echo INSURANCE_COST;?>% of Project Cost</p>
                                <p>Depreciation</p>
                                <p>First 10 years– <?php echo RATE_DEPRECATION_FOR_10;?>%</p>
                                <p>Next 15 years – <?php echo RATE_DEPRECATION_NEXT_15;?>%</p>
                                <p>Accelerated Depreciation* (if applicable)</p>
                                <p><?php echo RATE_OF_ACCELERATED_DEPRE;?>%</p>
                                <p>Corporate Tax (if applicable)</p>
                                <p><?php echo CORPORATE_TAX_RATE; ?>%</p>
                                <br/>
                            </div>
                        </td>
                    </tr>
                </table>
                <p><i>* Note: </i>
                    <p>1. Accelerated Depreciation is applicable for the solar PV systems for Industrial and Commercial segments.</p>
                    <p>2. 30% Capital Subsidy is applicable for the Solar PV System for Residential, Social Sector and non-profit making institution.</p>
                
                <div class="page-break"></div>
                
                <h1>Average Yearly Radiation For <?php echo $project->city.", ".$project->state; ?> <sup>1</sup></h1>
                <table border="0" cellpadding="5" >
                    <tr>
                        <td width="50%">
                            <img src="<?php echo $radiationGraphImg; ?>">
                        </td>
                        <td width="50%">
                            <div class="border-top">

                            <?php
                            if(!empty($radiationGraphData['radiation_ghi_data'])) { 
                                $quarter1 = 0;
                                $quarter2 = 0;
                                $quarter3 = 0;
                                $quarter4 = 0;
                                foreach ($radiationGraphData['radiation_ghi_data'] as $key => $radiation) { 
                                    if($key <= 3) {
                                        $quarter1 += $radiation;
                                    } elseif($key > 3 && $key <= 6) {
                                        $quarter2 += $radiation;
                                    } elseif($key > 6 && $key <= 9) {
                                        $quarter3 += $radiation;
                                    } elseif($key > 9 && $key <= 12) {
                                        $quarter4 += $radiation;
                                    }
                                }
                                $currMonth = date('n');
                                $qua1Class = (($currMonth <= 3)?"class=green":"");
                                $qua2Class = (($currMonth > 3 && $currMonth <= 6)?"class=green":"");
                                $qua3Class = (($currMonth > 6 && $currMonth <= 9)?"class=green":"");
                                $qua4Class = (($currMonth > 9 && $currMonth <= 12)?"class=green":"");
                            }
                            ?>
                                <p><b>QUARTER 1</b></p>
                                <p class="green" ><b><?php echo (isset($quarter1)?_FormatNumberV2($quarter1):0); ?> kWh/m<sup>2</sup></b></p>
                                <p><b>QUARTER 2</b></p>
                                <p class="green"><?php echo (isset($quarter2)?_FormatNumberV2($quarter2):0); ?> kWh/m<sup>2</sup></p>
                                <p><b>QUARTER 3</b></p>
                                <p class="green"><b><?php echo (isset($quarter3)?_FormatNumberV2($quarter3):0); ?> kWh/m<sup>2</sup></b></p>
                                <p><b>QUARTER 4</b></p>
                                <p class="green"><b><?php echo (isset($quarter4)?_FormatNumberV2($quarter4):0); ?> kWh/m<sup>2</sup></b></p>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                
                <hr/>
                <br>
                <h1>Results</h1>
                <div>
                    <table class="table-responsive t2 custom-table-style">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Particulars</th>
                                <th>Units</th>
                                <th>Recommended by AHA!</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A.</td>
                                <td>Recommended Capacity</td>
                                <td>kW</td>
                                <td align="center"><span ><?php echo $recommendedCapacity = (isset($project->recommended_capacity)?$project->recommended_capacity:0); ?></span></td>
                            </tr>
                            <tr>
                                <td>B.</td>
                                <td>Estimated Cost</td>
                                <td>Rs. in Lacs</td>
                                <td align="center"><span ><?php echo (isset($project->estimated_cost)?_FormatNumberV2($project->estimated_cost):0); ?></span></td>
                            </tr>
                            <tr>
                                <td>C.</td>
                                <td>Subsidy</td>
                                <td>Rs. in Lacs</td>
                                <td align="center"><span ><?php echo $subsidy = (isset($project->estimated_cost_subsidy)?_FormatNumberV2($project->estimated_cost - $project->estimated_cost_subsidy):0); ?></span></td>
                            </tr>
                            <tr>
                                <td>E.</td>
                                <td>Payback</td>
                                <td>Years</td>
                                <td align="center"><span ><?php echo (isset($project->payback)?_FormatNumberV2($project->payback):0); ?></span></td>
                            </tr>
                            <tr>
                                <td>F.</td>
                                <td>Savings</td>
                                <td>Rs./yr</td>
                                <td align="center"><span ><?php echo (isset($project->estimated_saving_month)?_FormatNumberV2($project->estimated_saving_month):0); ?></span></td>
                            </tr>
                            <tr>
                                <td>G.</td>
                                <td>Total Subsidy</td>
                                <td>Rs. in Lacs/ kW</td>
                                <td align="center"><span ><?php echo (($recommendedCapacity > 0)? _FormatNumberV2($subsidy/$recommendedCapacity):0); ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                
                <div class="clearfix"></div>
                
                <div class="page-break"></div>
                
                <section class="yellowh1 notoppad">
                    <h1>System specification</h1>
                    <div>                        
                        <ol type="a">
                            <li>Solar PV Module Capacity: &nbsp; <?php echo 250 * $project->recommended_capacity * 1000/250; ?> watt</li>
                            <li>Inverter Capacity : <span ><?php echo $project->recommended_capacity; ?> kW</span></li>
                            <li>Default Angle: &nbsp;<span ><?php echo round($project->latitude); ?></span></li>
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
                
                    <table  class="table-responsive t2 margintop20 custom-table-style">
                        <thead>
                            <tr>
                                <th align="center">Sr.</th>
                                <th align="left">Month</th>
                                <th align="right">Energy Generated (kWh)</th>
                                <th align="right">Saving (Rs)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($energyAndSavingDataArr['energy_data'])) { 
                            $totalEnergy = 0;
                            $totalSaving = 0;
                            foreach ($energyAndSavingDataArr['energy_data'] as $key => $energyGenerated) { 
                                $totalEnergy += $energyGenerated;
                                $totalSaving += $energyAndSavingDataArr['saving_data'][$key]; ?>
                                <tr>
                                    <td align="center"><?php echo $key; ?>.</td>
                                    <td align="left"><?php 
                                        $dateObj = DateTime::createFromFormat('!m', $key);
                                        echo $monthName = $dateObj->format('F');
                                    ?></td>
                                    <td align="right"><?php echo _FormatNumberV2($energyGenerated); ?></td>
                                    <td align="right"><span ><?php echo (isset($energyAndSavingDataArr['saving_data'][$key])?_FormatNumberV2($energyAndSavingDataArr['saving_data'][$key]):0); ?></span></td>
                                </tr>
                                <?php 
                            } ?>
                                <tr>
                                    <td align="center">&nbsp;</td>
                                    <td align="left">Annual</td>
                                    <td align="right"><?php echo _FormatNumberV2($totalEnergy); ?></td>
                                    <td align="right"><span ><?php echo _FormatNumberV2($totalSaving); ?></span></td>
                                </tr>
                                <?php 
                        } else { ?>
                            <tr>
                                <td colspan="4">Not found data.</td>
                            </tr>
                            <?php 
                        } ?>
                        </tbody>
                    </table>
                    <br/>
                    <p>The recommended size of the rooftop solar PV system will cover 53% of your electricity usage.</p>
                </div>
                
                <div class="clearfix"></div>
                
                <h1>Solar Payback Graph for Recommended Value</h1>
                <br>
                <img src="<?php echo $paybackGraphImg; ?>" >
				<br>
                <div class="page-break"></div>
                
                <h1>Environment Benefits</h1>
                <br>
                <table class="nk_table">
                    <tbody>
                    <tr>
                        <td>Co<sub>2</sub> Avoided equals</td>
                        <td><?php echo (isset($environmentData['CO2AvoidedEquals'])?$environmentData['CO2AvoidedEquals']:0); ?></td>
                        <td>Tons of Carben Annually</td>
                    </tr>
                    <tr>
                        <td>Nos. of trees</td>
                        <td><?php echo (isset($environmentData['noOfTrees'])?$environmentData['noOfTrees']:0); ?></td>
                        <td>Trees Planted for Life of Tree</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <ul>
                                <?php 
                                if(!empty($environmentData['noOfTrees'])) {
                                    for($i=0;$i<$environmentData['noOfTrees'];$i++) { ?>
                                        <li><img src="images/tree.png" alt=""></li>
                                    <?php 
										if($i == 40 )
										{	
											break;
										}
									}
									
                                } ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="last">Cars off the Road</td>
                        <td class="last"><?php echo (isset($environmentData['carsOffTheRoad'])?$environmentData['carsOffTheRoad']:0); ?></td>
                        <td class="last">Cars Taken off the Road For One Year </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <ul>
                                <?php 
                                if(!empty($environmentData['carsOffTheRoad'])) {
                                    for($i=0;$i<$environmentData['carsOffTheRoad'];$i++) { ?>
                                        <li><img src="images/car.png" alt=""></li>
                                    <?php 
										if($i == 40 )
										{	
											break;
										}
									} 
                                } ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="last">Energy Oil Equivalent Saved</td>
                        <td class="last"><?php echo (isset($environmentData['oilEquivalent'])?$environmentData['oilEquivalent']:0); ?></td>
                        <td class="last">Litres of Oil per year </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <ul>
                                <?php 
                                if(!empty($environmentData['oilEquivalent'])) { 
                                    $noOfKen = round($environmentData['oilEquivalent']/1000); 
                                    for($i=0;$i<$noOfKen;$i++) { ?>
                                        <li><img src="images/ken.png" alt=""></li>
                                    <?php 
										if($i == 40 )
										{	
											break;
										}
									} 
                                } ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="last">Average Home Powered </td>
                        <td class="last"><?php echo (isset($environmentData['avgHomePowered'])?$environmentData['avgHomePowered']:0); ?></td>
                        <td class="last">Homes Poweredfor One Year </td>
                    </tr>
                    <tr>
                        <td class="last" colspan="3">
                            <ul>
                                <?php 
                                if(!empty($environmentData['avgHomePowered'])) {
                                    for($i=0;$i<$environmentData['avgHomePowered'];$i++) { ?>
                                        <li><img src="images/home.png" alt=""></li>
                                    <?php 
										if($i == 40 )
										{	
											break;
										}
									} 
                                } ?>
                            </ul>
                        </td>
                    </tr>
                    <tbody>
                </table>
                <br>
                <div class="clearfix"></div>
				<?php if(!isset($hideInstaller)) {?>
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
                                <p><?php 
                            $val->installers['address'] = str_replace($val->installers['city'], "", $val->installers['address']);
                            $val->installers['address'] = str_replace($val->installers['state'], "", $val->installers['address']);                                
                                echo $val->installers['address']; ?></p>
                                <p><?php echo $val->installers['city'].", ".$val->installers['state']; ?>.</p>
                                <p><?php echo $val->installers['contact']; ?></p>
                                <p><?php echo $val->installers['contact1']; ?></p>
                            </td>
                        </tr>
                        <?php } 
                        }else{ ?>
                        <tr>
                            <td align="center">User not selected any Installer!</td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
				<?php } ?>
                <hr/>
                <div class="clearfix"></div>
                <h1>Summary</h1>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The recommended rooftop solar PV system (RSPVS) as per your requirement is <span ><?php echo $project->recommended_capacity; ?> kW.</span> The capacity is determined considering your electricity usage, relevant policy and regulations of your State Government, Government of India and the inputs given by you.</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;An approximate cost of the RSPVS will be around <span >Rs. <?php echo _FormatNumberV2($project->estimated_cost * 100000); ?> /- </span> with a payback of <span ><?php echo $project->payback; ?></span> years considering subsidy. <?php if(strtolower($project->custtype['para_value'])=='residential') {?> You are eligible for a subsidy <span >30% on capital cost of RSPVS</span> from Government of India.<?php }?></p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you are a profit making Company then you can also avail tax benefit of 80% Accelerated Depreciation for your RSPVS. This is applicable only for Industrial or Commercial User.</p>
                <div class="page-break"></div>
                <div class="mainboxlast">
                    <header>
                        <div class="row">
                          <div class="logo">
                              <img src="images/logo.png" >
                          </div>
                        </div>    
                    </header>
                    <h1>Finally, Important</h1>
                    <p>As you are seriously considering owning a rooftop solar PV system, please keep these facts in mind:</p>
                    <ul>
                        <li class="mbottom20">A PV system should last you at least 25 years!  Hence, quality is very important.  Cheapest is not always the best!</li>
                        <li class="mbottom20">Warranties you should have on your rooftop PV system:
                            <ul>
                                <li>25 year performance warranty on PV modules, which states that the performance of the PV module will not be less than 90% of its rated value for the first 10 years, and not less than 80 % for the next 15 years.</li>
                                <li>5 year workmanship warranty on PV modules.</li>
                                <li>5 year warranty on inverters.</li>
                                <li>5 year warranty on overall rooftop PV system.</li>
                            </ul></li>
                        <li class="mbottom20">You should take an undertaking from your PV system Installer that all components as well as the entire PV system itself adheres to all relevant IEC and Indian Standards.</li>
                        <li class="mbottom20">Your PV system Installer should help you obtain the necessary clearances (from the Distribution Company, Electrical Inspector, etc.) as well as subsidies, if applicable.</li>
                        <li class="mbottom20">We highly recommend you to give an at least 5-year comprehensive maintenance contract (CMC) to your PV system installer.<br/></li>
                        <li>And finally remember…</li>
                    </ul>
                    <p class="alone-title">You are not alone!</p>
                    <h4 class="align-center">If you have any questions, please feel free to contact us.</h4>
                    <section class="footer-section">
                            <table class="t4">
                                <tr>
                                    <td class="align-center" width="50%">Powered By</td>
                                    <td class="align-center" width="50%">Knowledge Partner</td>
                                </tr>
                                <tr>
                                    <td class="align-center"><img src="images/poweredby.png" class="img-responsive center-block"></td>
                                    <td class="align-center"><img src="images/germi.png" class="img-responsive center-block"></td>
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