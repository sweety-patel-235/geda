<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Project Page</title>
        <!-- Style CSS -->
        <link type="text/css" rel="stylesheet" media="all" href="css/style.css"  />
        <style>
            .nk-header { display: block; width: 100%; }
            .count-numbers { background: black; font-size: 28px; text-align: center; width: 60px; height: 60px; line-height: 60px; float: left; margin-right: 10px; color: white; }
            .header-title { font-size: 28px; font-weight: bold; line-height: 60px; }
        </style>
    </head>

    <body id="pdf-header">
        <div id="footer">
            <table>
                <tr>
                    <td>
                        <?php echo date('Y/m/d');?>  Feasibility Report  <?php echo $project->recommended_capacity; ?> KW Rooftop Solar PV System
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
                            <h1 class="text-center" style="font-size:30px;color:#333">Project Summary Report for
                            <span><?php echo $project->recommended_capacity; ?></span> KW Rooftop Solar PV System at <span ><?php echo $project->address.", ".$project->city.", ".$project->state; ?></span></h1>
                        <img src="images/rooftop-solar.jpg" style="height:800px"> 
                        </td>
                    </tr>
                </table>
                <div class="page-break"></div>
                <p allign="center"> Feasibility Assessment Report for Grid Connected Rooftop Solar PV System of XX kWp Capacity<p>
                <p allign="center">AT <p>
                <p allign="center">
                    <Name of the Organization>
                <p>
                <p allign="center">
                    <Site Picture>
                <p>
                <p allign="center">Submitted to <p>
                <p allign="center">SOLAR ENERGY CORPORATION OF INDIA LIMITED<p>
                <p allign="center">(A Government of India Enterprises)<p>
                <p allign="center">Ist Floor, Wing A, Religare Building, D- 3, District Centre, Saket, New Delhi – 17<p>
                <p allign="center">Tel: 011- 71989256<p>
                
                <!--  <div class="pagebrack"></div> -->
                <!--   <hr/> -->
                <div class="page-break"></div>
                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">1</div>
                    <div class="header-title"> <h1>Brief Summary of Assessment</h1></div>
                </div>
                
                <table border="1" style="border:1px solid;" cellpadding="5" >
                    <tr>
                        <td width="10%">
                            1.
                        </td>
                        <td width="45%">
                            Name of the Office/ Institution
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            2.
                        </td>
                        <td width="45%">
                            Office/ Institution Address
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            3.
                        </td>
                        <td width="45%">
                            Name of the Authorized Officer
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            3.1.
                        </td>
                        <td width="45%">
                            Designation
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            3.2.
                        </td>
                        <td width="45%">
                            Mobile No.
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            3.4.
                        </td>
                        <td width="45%">
                            Email:
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            4.
                        </td>
                        <td width="45%">
                            Latitude
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            5.
                        </td>
                        <td width="45%">
                            Longitude
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.
                        </td>
                        <td width="45%">
                            Site Accessibility
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.1.
                        </td>
                        <td width="45%">
                            Road
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.2.
                        </td>
                        <td width="45%">
                            Nearest Railway Station
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.3.
                        </td>
                        <td width="45%">
                            Nearest Airport
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.4.
                        </td>
                        <td width="45%">
                            Nearest Town/ City
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.5.
                        </td>
                        <td width="45%">
                            Road to site
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            6.6.
                        </td>
                        <td width="45%">
                            Ladder to roof
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                    <tr>
                        <td width="10%">
                            7.
                        </td>
                        <td width="45%">
                            Current Contracted Load (kVA or kW)
                        </td>
                        <td width="45%">

                        </td>
                    </tr>

                    <tr>
                        <td width="10%">
                            8.
                        </td>
                        <td width="45%">
                            Average Yearly Electricity Consumption
                        </td>
                        <td width="45%">

                        </td>

                    </tr>
                    <tr>
                        <td width="10%">
                            9.
                        </td>
                        <td width="45%">
                            Current Average Electricity Rate
                        </td>
                        <td width="45%">

                        </td>

                    </tr>
                    <tr>
                        <td width="10%">
                            10.
                        </td>
                        <td width="45%">
                            Is DG utilized for power Generation
                        </td>
                        <td width="45%">

                        </td>

                    </tr>
                    <tr>
                        <td width="10%">

                        </td>
                        <td width="45%">
                            If  yes,  what  is  yearly  average  diesel consumption
                        </td>
                        <td width="45%">

                        </td>

                    </tr>
                    <tr>
                        <td width="10%">
                            11.
                        </td>
                        <td width="45%">
                            Status of Net Metering Policy in the State
                        </td>
                        <td width="45%">

                        </td>
                    </tr>

                    <tr>
                        <td width="10%">
                            12.
                        </td>
                        <td width="45%">
                            Prevailing  DisCom  rates  for  power  generated
                            from  Grid-Connected  Solar  PV  system
                            (INR/kWh) (if any)
                        </td>
                        <td width="45%">

                        </td>
                    </tr>

                    <tr>
                        <td width="10%">
                            13.
                        </td>
                        <td width="45%">
                            Total Roof Area Available (sq.m)
                        </td>
                        <td width="45%">

                        </td>
                    </tr>

                    <tr>
                        <td width="10%">
                            14.
                        </td>
                        <td width="45%">
                            Shadow-free  Roof  Area  Available  for  Grid-
                            Connected Solar PV System (sq.m)
                        </td>
                        <td width="45%">

                        </td>
                    </tr>

                    <tr>
                        <td width="10%">
                            15.
                        </td>
                        <td width="45%">
                            Recommended  Solar  Rooftop  PV  Capacity (kWp)
                        </td>
                        <td width="45%">

                        </td>
                    </tr>

                    <tr>
                        <td width="10%">
                            16.
                        </td>
                        <td width="45%">
                            Estimated  Average  Electricity  Generation (kWh)
                        </td>
                        <td width="45%">

                        </td>
                    </tr>
                </table>

                <div class="page-break"></div>

                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">2</div>
                    <div class="header-title"> <h1>Introduction</h1></div>
                </div>

                <p style="color:yellow">
                    2.1 Brief About Office/ Institution
                </p>

                <p style="color:yellow">
                    2.2 Nature of activity carried out at Office
                </p>

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
                    <div class="nk-header">
                        <div class="count-numbers" style="color:yellow">3</div>
                        <div class="header-title"> <h1>Current Electricity Scenario</h1></div>
                    </div>
                    Grid Connection Details

                    <table border="1" style="border:1px solid;" cellpadding="5" >
                        <tr>
                            <td width="40%">
                                Name of DisCom
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">

                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                Name of DisCom
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">

                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                               Sanctioned Contract Demand
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">
                                 in kVA
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                               Voltage
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">
                                 in V
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                               Frequency
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">
                                 in Hz
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                               Average Power Cost
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">
                                 INR/kWh
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                               Monthly Power Consumption for past 12 months
                            </td>
                            <td width="5%">
                               :
                            </td>
                            <td width="40%">

                            </td>
                            <td width="15%">
                                in kWh
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table border="1" style="border:1px solid;" cellpadding="5" >
                        <tr>
                            <th width="5%">
                               Sr.
                            </th>
                            <th width="15%">
                               Month
                            </th>
                             <th width="15%">
                                Year
                            </th>
                            <th width="40%">
                                Power Consumption(kWh)
                            </th>
                            <th width="25%">
                                Bill (INR)
                            </th>
                        </tr>
                        <tr>
                            <td width="5%">
                              1
                            </td>
                            <td width="15%">
                              January
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                             2
                            </td>
                            <td width="15%">
                              Februaby
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              3
                            </td>
                            <td width="15%">
                              March
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              4
                            </td>
                            <td width="15%">
                              April
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              5
                            </td>
                            <td width="15%">
                              May
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              6
                            </td>
                            <td width="15%">
                              June
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              7
                            </td>
                            <td width="15%">
                              July
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              8
                            </td>
                            <td width="15%">
                              Auguest
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              9
                            </td>
                            <td width="15%">
                              Suptember
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              10
                            </td>
                            <td width="15%">
                              October
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              11
                            </td>
                            <td width="15%">
                              November
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                              12
                            </td>
                            <td width="15%">
                              December
                            </td>
                             <td width="15%">

                            </td>
                            <td width="40%">

                            </td>
                            <td width="25%">

                            </td>
                        </tr>
                    </table>

                    <p>
                        Approximate percentage of power consumed between 10 AM to 5 PM:
                        </p>
                    <p>
                         Working days per week:
                         </p>
                    <p>
                        Is there any consumption during non-working days? If so, what is the load (kW or kVA, as a proportion of full load) and power consumption (kWh)

                    </p>
                    <p style="color">
                        3.2 Power back-up
                    </p>
                    <br>

                        <table border="0">
                            <tr>
                                <td width="10%">
                                 a.
                                </td>
                                <td width="40%">
                                  Source of Power Generation
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="40%">

                                </td>
                            </tr>
                            <tr>
                                <td width="10%">
                                  b.
                                </td>
                                <td width="40%">
                                  Annual Power Generation from the Source
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="40%">
                                    kWh
                                </td>
                            </tr>
                            <tr>
                                <td width="10%">
                                  c.
                                </td>
                                <td width="40%">
                                 Annual Fuel Cost
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="40%">
                                    INR/year
                                </td>
                            </tr>
                        </table>

                        <div class="page-break"></div>
                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">4</div>
                            <div class="header-title"> <h1>Site Description</h1></div>
                        </div>
                        <p style="color:yellow">
                            4.1 Overview
                        </p>
                        <p>
                            Site selection of a project for installation of solar PV power plant is one of the most important initial steps. Electricity generation costs and techno-economic feasibility of the project both are highly dependent on the project site itself. A good site has to have a high annual solar radiation to obtain maximum solar electricity output. It must be reasonably shadow free to accommodate the solar PV modules. It should also be close to the electricity interconnection point of the building to avoid the need to build expensive electricity lines for evacuating the power. Accessibility to site and rooftop shall be easy.
                        </p>
                        <br>
                        <table border="0">
                            <tr>
                                <th width="20%">
                                Sr.
                                </th>
                                <th width="80%">
                                  Particulars for Good Site
                                </th>
                            </tr>
                            <tr>
                                <td width="20%">
                                1.
                                </td>
                                <td width="80%">
                               High annual solar radiation
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                2.
                                </td>
                                <td width="80%">
                               Reasonably flat shadow-free terrace
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                3.
                                </td>
                                <td width="80%">
                                Close to the evacuation point
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                4.
                                </td>
                                <td width="80%">
                                Accessibility to the site should be easy
                                </td>
                            </tr>
                        </table>
                        <br>
                        <p>Rooftop solar PV power plant will be located on the terrace of Medical College at Jamshedpur, Jharkhand. This site has the entire prerequisite to be termed as high potential site for solar installation. The location of the sites is shown in the map below in Figure xx-xx
                        </p>
                          <p style="color:yellow">4.2    Geographical Location</p>
                          <p style="color:yellow">4.2.1 About the Site</p>

                        <table border="0">
                            <tr>
                                <th width="45%">
                                Parameter
                                </th>
                                 <th width="10%">
                                &nbsp;
                                </th>
                                <th width="45%">
                                  Location
                                </th>
                            </tr>
                            <tr>
                                <td width="45%">
                                Site Location
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                            <tr>
                                <td width="45%">
                                City/Town
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                            <tr>
                                <td width="45%">
                                    Latitude
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                            <tr>
                                <td width="45%">
                                    Longitude
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                            <tr>
                                <td width="45%">
                                    Elevation (m)
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                            <tr>
                                <td width="45%">
                                    Area (sq. m)
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                            <tr>
                                <td width="45%">
                                    Shadow free roof area for PV system (sq. m)
                                </td>
                                <td width="10%">
                                    :
                                </td>
                                <td width="45%">

                                </td>
                            </tr>
                        </table>

                        <div class="page-break"></div>
                        <p style="color:yellow">4.2.2   Connectivity</p>
                        <p>A.   Road Connectivity</p>
                        <p>B.   Airport Connectivity</p>
                        <p>C.   Port Connectivity</p>
                        <p>D.   Rail Connectivity</p>

                        <div class="page-break"></div>
                        <p style="color:yellow">  4.3 Solar Radiation</p>
                        <p>Ideally, actual measurement of solar radiation at the site is desirable for estimating the projected power output since solar energy is the raw material for power generation. It may be noted that the annual average solar radiation measurement even for 1-2 years is not sufficient. World over, an average radiation value for at least 8-10 years is used for solar power project designing since climatic variations are quite wide year-to-year. Under such a situation, the prevailing practice world over is to develop software which uses satellite measured solar radiation and matches it with the actual ground measured data for the particular site where actual data has been obtained for many years. Thus, a co-relation between satellite data and actually measured ground data is developed through the software, which helps in deriving the radiation values for unknown sites where actual ground measured data are not available. In the present study, radiation data published by Ministry of New and Renewable Energy, Government of India is used.</p>
                        <p>For the PV solar applications the cell absorbs both direct and diffused sunlight hence, GHI is an important parameter, which is the measurement of total solar radiation including direct and diffused radiation on horizontal surfaces usually measured using Pyranometer. However, the solar PV modules in power projects are typically placed at the latitude angle from the ground surface for getting optimum power generation. Therefore, Global Irradiance on Tilted surface (GTI) becomes more relevant. The derived values are tabulated below in Table 2-4.
                        </p>

                        <div class="page-break"></div>
                        <p style="color:yellow">Table 2 4: Solar Radiation Data</p>
                        <div class="page-break"></div>

                        <p style="color:yellow">4.4    Proposed Capacity</p>
                        <p>The <Building Name> has a potential for <Capacity> kW solar PV System. This capacity on its rooftop is determined considering the rooftop area, electrical load demand at the building and relaavent state Regulation. The power cut at this location is <in Hours>. </p>
                        <p>The total roof area space available on the <Building Name>, <City/Town> is <…..> <sq. m or sq. ft.>but due to existing nearby structures on the roof and trees, the entire space can’t be utilized for installation of Rooftop Solar PV System (RSPVS). Hence, a calculation is made to determine the total shadow free available area. As shown in Annexure XX, parts of the roof shall be used to install RSPVS. The total available shadow free roof area is 3,600 sq. m. Hence, <Shadow Free Area/ Total Area>% of available rooftop space is completely shadow free.</p>

                        <div class="page-break"></div>
                        <p>Table 2 5: Proposed Capacity of Rooftop Solar PV system</p>
                        <table border="0">
                            <tr>
                                <th width="5%">Sr.
                                </th>
                                <th width="40%">
                                    Building Name
                                </th>
                                <th width="20%">
                                    Shadow Free Area
                                    (in sq.m)
                                </th>
                                <th width="20%">
                                    Proposed Capacity
                                    (in kW)
                                </th>
                                <th width="20%">
                                    Type of System
                                </th>
                            </tr>
                            <tr>
                                <td width="5%">
                                    1.
                                </td>
                                <td width="40%">
                                    Building Name
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>

                        <br>
                        <p>
                        It is estimated that maximum possible RSPVS capacity based on available rooftop area is 250 KWp. Considering the peak electrical load of the building, it is estimated that cumulative solar PV system of 250kWp is sufficient to meet the energy needs of the building. Hence, to augment the existing electrical power system of 250kWp RSPVS is proposed at <Project Name>.
                        </p>

                        <div class="page-break"></div>
                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">5</div>
                            <div class="header-title"> <h1>Assessment of the Building</h1></div>
                        </div>
                        <p style="color:yellow">
                            5.1 Type of Building
                       </p>
                        <div class="page-break"></div>

                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">6</div>
                            <div class="header-title"> <h1>Solar Rooftop PV System</h1></div>
                        </div>
                        <p style="color:yellow">
                           6.1  Sizing of the Grid Connected Rooftop PV System
                        </p>
                       <p>
                            The RSPVS is designed with an optimum number of solar PV modules and grid connected power conditioning units of suitable capacity are used in designing the system. The whole installation has a minimum of 25 years design life.
                        </p>
                        <p>
                        There is a hybrid system with battery bank type of solar PV systems designed for this location:
                        </p>
                        <p style="color:yellow"> 6.1.1    Rooftop Solar PV System</p>
                        <p>
                            <b>A.  PV Modules</b>
                        </p>
                        <p>
                        A number of solar PV panels are connected in series and in parallel to obtain the desired wattage of DC output. Orientation and tilt of these module; and shading obstructions from surrounding are a few important design parameters which shall be considered at the time of solar PV design.
                        </p>
                        <p>
                        For the proposed solar PV power plant at <Project Name>, poly-crystalline silicon modules of 250 Wp have been considered for designing purpose. This enables optimum sizing in terms of power conditioners and power evacuation system. The selected PV module should have RF identification tag (RFID) as well as label for tracking purpose. The schematic of typical grid-connected solar PV system is given in Figure and specifications of a PV module are given below in Figure 6-5.
                        </p>
                        <div class="page-break"></div>
                        <p>
                            <img src="http://www.ahasolar.in/img/battrybank.png">
                        </p>
                        <p>
                           • Total capacity of power project = <Capacity> kWp  <br>
                           • PV Module wattage = 250 Wp <br>
                           • Total no. of SPV modules required for 250 KW SPV power plant = <Capacity/250> Nos.  <br>
                           • Total capacity of power project = <Capacity> kWp  <br>
                        </p>
                        <div class="page-break"></div>

                        <p>
                            <img src="http://www.ahasolar.in/img/battrybank.png"><br/>
                            <img src="http://www.ahasolar.in/img/battrybank.png"><br/>
                        </p>
                        <div class="page-break"></div>

                        <b>B.   Module Mounting Structure</b>
                        <p>The inclination of the module mounting structure shall be fixed at a tilt of <Latitude> degrees with solar PV modules mounted per structure and further connected in series.</p>
                        <p>The arrays frames are fabricated out of MS galvanized and are protected against the environment impacts for any corrosion and are designed for considering the simplicity, low cost and ease of installation at site. The lower edges of the modules shall be least 50 cm above the ground level, so as to remove any “splash back” from the ground caused by rain. While making foundation design due consideration shall be given on the weight of module assembly and seismic factors of the site.</p>

                        <table bordar="1">
                            <tr>
                                <td>Sr.</td>
                                <td>Particulars</td>
                                <td>Specifications</td>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>Plant Capacity (kW)</td>
                                <td><Capacity></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Module Peak Power (Wp)</td>
                                <td>250</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Modules Per Mounting Structure</td>
                                <td>Input from Website</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>Coating</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>Tilt</td>
                                <td>Latitude</td>
                            </tr>
                        </table>

                        <p>
                           <b>C. Power Conditioning Unit (PCU)</b>
                        </p>
                        <p>
                            The PCU converts the DC power to AC power to facilitate feeding into the grid, it consist of inverter and controlling unit. The inverter is the most complicated part of the Rooftop Solar PV system. It has to act as the interface between the PV array and the grid. As the PV array output varies with the solar radiation the inverter has to cope with the same. The power conditioning units used in grid-connected rooftop solar PV systems consist of an inverter and other electronics for MPPT, synchronization and remote monitoring. For this project, <INPUT FROM Website> kW capacity PCUs is recommended. Other configurations are also possible based on the final design. The system exports power to the grid when the DC output from solar array is available. The 3 phase output voltages and current are sinusoidal with low total harmonic distortion. The inverter shall be designed for continuous, reliable power supply as per specifications.
                        </p>
                        <p> <b>D.Junction Box or Combiner</b></p>
                        <p> Dust, water and vermin proof junction boxes of adequate rating and adequate terminal facility made of fire resistant plastic (FRP) shall be provided for wiring also will comply with IP67 standard. Each solar panel shall be provided with fuses of adequate rating to protect the solar arrays from accidental short circuit.</p>
                         <div class="page-break"></div>

                        <p> <b>E.   Array Junction Box</b></p>
                        <p>The array junction boxes will be dust, vermin and waterproof and made of thermo plastic with IP67 protection standards. Surge protection devices must be used at the terminals of array junction boxes for external over voltage protection. The junction boxes will be fitted with cable glands of appropriate sizes for both incoming and outgoing cables. Suitable markings should be provided on the bus bar for easy identification and cable ferrules shall be fitted at the cable termination points for identification.</p>

                        <p> <b>F.   Main Junction Box</b></p>
                        <p>Main junction boxes used to connect the output of array junction boxes to the grid-tie inverter. The output of 4/5 AJB’s is fed in to one main junction box. The single output of main junction box works as an input for inverter.</p>

                        <p> <b>G.  Safety Earthing System</b></p>
                        <p>A safety earthing system consisting of a buried GI flat conductor earthing grid will be provided for the switchyard. The earthing system will be formed to limit the grid resistance to below 1 ohm. In the switchyard area, the touch potential and step potential will be limited to the safe values.</p>

                        <p> <b>H.   Lightning Protection System</b></p>
                        <p>Rooftop Solar PV equipment will be shielded against direct lightning strikes by providing lightning arresters/ spikes/shield wires near the installations.</p>

                        <p> <b>I.  Plant Layout</b></p>
                        <p>All solar PV modules shall be ideally mounted on south facing fixed frames, tilted at <TILT> degree angle at which maximum energy can be generated. Rows between the solar PV modules structures are separated by an optimum distance so that shadow of one row does not fall on the next/adjacent row. To monitor the performance of the solar PV plant, a small control station with computers to continuously monitor the plant performance using online monitoring system and advance communication facilities for remote monitoring shall be installed.</p>

                        <div class="page-break"></div>
                        <p> <b>J.   Water Requirement</b></p>
                        <p>Water is required only for cleaning purpose of PV module. The cleaning frequency is depending on location of project site. As per site environmental condition, water cleaning is sufficient once a week. It is highly recommended that the PV modules shall be cleaned with a clean cloth or wiper and water only.
                        </p>
                        <p>
                        An incremental degradation of the glass surface may be caused by the alkali components of the clay. To mitigate this problem, the water sprayed shall have a mild acidic nature (pH~6.9). If the hardness is not within permissible limit then water softening plant may be required.
                        </p>

                        <table bordar="1">
                            <tr>
                                <td>A.</td>
                                <td>Total Capacity of power project</td>
                                <td>=</td>
                                <td><Capacity> kWp</td>
                            </tr>
                            <tr>
                                <td>B.</td>
                                <td>Water required for one time cleaning for one module</td>
                                 <td>=</td>
                                <td>3 litres</td>
                            </tr>
                            <tr>
                                <td>C.</td>
                                <td>Water required for one time cleaning for total power plant</td>
                                 <td>=</td>
                                <td>250</td>
                            </tr>
                            <tr>
                                <td>D.</td>
                                <td>&nbsp;</td>
                                 <td>=</td>
                                <td>3 x “C”</td>
                            </tr>
                            <tr>
                                <td>E.</td>
                                <td>&nbsp;</td>
                                <td>=</td>
                                <td>“D” liters per wash</td>
                            </tr>
                        </table>

                        <div class="page-break"></div>
                        <p style="color:yellow">
                            6.2 Yield Assessment
                        </p>
                        <p style="color:yellow">
                           6.2.1    Energy Estimation
                        </p>
                        <p>
                            Annual Energy Yield for the proposed PV power plant is defined as the amount of energy fed into the grid after due consideration of all kinds of generation and distribution losses. The solar PV based power plant comprises optical energy input (which is essentially dependent on the geographical/seasonal/ climatic and operating parameters with time) and electrical output (which depends on the technical specifications of electrical appliances in use).
                        </p>
                        <p>
                            The financial viability of a RSPVS depends greatly upon the most important parameter, namely reliable annual average solar radiation data for a given site averaged over several years. Solar radiation, which is the energy input, is very sensitive to local weather conditions and prone to supply fluctuations, directly affecting the output, project feasibility, techno-economic viability and performance of the project. It is the primary task of the solar system developer, investor, designer or an analyst to determine the amount, quality and duration of solar energy available at a specific site before selecting it for a solar PV power project. Although solar irradiance is relatively constant outside the earth's atmosphere, local climate influences can cause wide variations in available irradiance on the earth’s surface from site to site. In addition, the relative motion of the sun with respect to the earth will allow surfaces with different orientations to intercept different amounts of solar energy at different locations.
                        </p>
                         <p>
                            The financial viability of a RSPVS depends greatly upon the most important parameter, namely reliable annual average solar radiation data for a given site averaged over several years. Solar radiation, which is the energy input, is very sensitive to local weather conditions and prone to supply fluctuations, directly affecting the output, project feasibility, techno-economic viability and performance of the project. It is the primary task of the solar system developer, investor, designer or an analyst to determine the amount, quality and duration of solar energy available at a specific site before selecting it for a solar PV power project. Although solar irradiance is relatively constant outside the earth's atmosphere, local climate influences can cause wide variations in available irradiance on the earth’s surface from site to site. In addition, the relative motion of the sun with respect to the earth will allow surfaces with different orientations to intercept different amounts of solar energy at different locations.
                        </p>
                        <p>
                            Power generations in term of kWh for the proposed site at <Project Name> at <Location> are calculated by using AHA! Pro and PVsyst software. A standard radiation data from MNRE is considered for energy generation estimation. Detailed analysis of design parameters and resultant output along with stage-wise losses are carried out using PVsyst software which is attached in the Annexure.
                        </p>
                        <div class="page-break"></div>
                        <p style="color:yellow">
                            6.2.2   Losses
                        </p>
                        <p style="color:yellow">
                            Module Mismatch Losses
                        </p>
                        <p>
                            The maximum power output of the total PV array is always less than the sum of the maximum output of the individual modules. This difference is a result of slight inconsistencies in performance from one module to the next and is called module mismatch. Maximum 3-4% losses will be there for module mismatch, but the modules shall be connected in series and parallel of similar properties to reduce such losses and it will reduce the mismatch losses to 1%.
                        </p>
                        <p style="color:yellow">Cable Losses</p>
                        <p>Power is also lost to resistance in the system wiring. These losses should be kept to a minimum below 1% for the system. It is recommended to keep total energy loss in the cables be around 1.0%.</p>

                        <p style="color:yellow">DC to AC Conversion Losses</p>
                        <p>The DC power generated by the solar module must be converted AC power using an inverter. Some power is lost in the conversion process. In this case as discussed before that selected inverter has maximum DC power into AC power conversion efficiency of <Efficiency of Inerter> </p>


                        <p style="color:yellow">Transformer Losses</p>
                        <p>To account for different losses in the transformers like core and ohmic losses a typical coefficient of 0.989 has been considered i.e. 1.1%.</p>

                        <p style="color:yellow">Temperature Losses</p>
                        <p>Whenever there is increase in ambient temperature, the temperature of the PV module also increases, resulting in reduction of power output from the PV module. This depends on the temperature coefficient of the PV module as specified by the manufacturer. A loss of 10.1% will be there with increment in ambient temperature at the project site from standard conditions.</p>

                        <p style="color:yellow">Shading Losses</p>
                        <p>Shading on the solar PV panel can bring gigantic malicious effect on the energy yield. Generally, the potential solar PV shading objects are tree, nearby building buildings. Rooftop solar PV installations are more prone to the shading losses. As far as the large scale solar PV installation is concerned, shading of inverter room, control room and inter-row are the potential reason of shading losses. Moreover, these objects provide shading in the early morning hours or late evening hours. However, while designing the solar PV plant, all these factors are always taken under serious consideration and shading losses are optimized as 1.5%.</p>

                        <div class="page-break"></div>
                        <p style="color:yellow">Annual Degradation</p>
                        <p>The estimated life of solar PV modules is considered as 25 years. Performance of solar PV modules degrades over its specified lifetime. Normally, PV module manufacturers provide a performance guarantee and indicate the rate of degradation over the module lifetime. Essentially the solar PV modules used in grid solar power plants are warranted for output wattage which should not be less than 90% at the end of 10 years and 80% at the end of 25 years. In the present analysis, the annual degradation in production has been taken conservatively taken as 0.83 %, linear for entire project life of 25 years.</p>

                        <p style="color:yellow">6.3 Power Evacuation </p>
                        <p>Power generated from rooftop solar PV plant shall be evacuated at metering point of the building. The evacuation voltage shall be at <Input from APP> kV AC (<Input from APP> phase) wherein the evacuation point shall be before the net-meter.</p>

                        <p><Name of Discom> (the “Discom”) shall grant the necessary permissions to carry out all necessary arrangement like capacity to be connected to the grid based on the capacity of the transformer. Once the go ahead is given by the Discom, rooftop PV system shall be connected to near the grid. In case of net-metering based system, the existing energy meter shall be replaced with the Net-Meter which can account import and export of power from the building premises and in case of gross-metering based system, a new energy meter shall be installed after the existing energy meter i.e. towards the grid side.</p>

                        <p>The detailed single line diagram of the electrical interconnection is given in Annexure. The single line diagram shows the electrical system of the building and location of interconnection point for rooftop solar PV system.</p>
                        <div class="page-break"></div>

                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">7</div>
                            <div class="header-title"> <h1>Approvals</h1></div>
                        </div>
                        <div class="page-break"></div>

                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">8</div>
                            <div class="header-title"> <h1>Annexure</h1></div>
                        </div>
                        <p style="color:yellow">8.1  Monthly Electricity, diesel or any other bills</p>
                        <p><Input from website></p>

                         <p style="color:yellow">8.2    Single Line Diagram</p>
                        <p><Input from website></p>

                         <p style="color:yellow">8.3    Cable Distribution Diagram</p>
                        <p><Input from website></p>

                        <p style="color:yellow">8.4 Drawings for existing arrangements for Earthing and Lighting Protections</p>
                        <p><Input from website></p>

                        <div class="page-break"></div>
                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">9</div>
                            <div class="header-title"> <h1>Environment </h1></div>
                        </div>

                        <div class="page-break"></div>
                        <div class="nk-header">
                            <div class="count-numbers" style="color:yellow">10</div>
                            <div class="header-title"> <h1>Financial Analysis </h1></div>
                        </div>

                        <p>Important Note: The recommendations, your selection and the suggested results are based on your inputs, general assumptions and preliminary calculations.  Please discuss and finalize all technical and financial details with your Installer, as these numbers may slightly vary in your case.  The Installer will provide you the final specifications, costs and guarantees/ warranties.</p>
                        <p>AHA! Pro Software recommends a rooftop PV system capacity of 3 kW.</p>
                        <p>However, considering the site conditions and local parameters <Company Name> proposes a rooftop PV system capacity of 2 kW.</p>
                        <p>10.1   Financial Analysis</p>

                        <table border="0">
                            <tr>
                                <th width="5%">
                                Sr.
                                </th>
                                 <th width="40%">
                               Head
                                </th>
                                <th width="20%">
                                 &nbsp;
                                </th>
                                <th width="20%">
                                 (AHA! Assumption)
                                </th>
                                <th width="20%">
                                Result
                                </th>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                1.
                                </td>
                                 <td width="40%">
                                  CAPACITY
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                   &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                &nbsp;
                                </td>
                                <td width="40%">
                                  PV System Capacity
                                </td>
                                <td width="20%">
                                   :
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                  2 kW
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                2.
                                </td>
                                 <td width="40%">
                                PURCHASE COST
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                2.
                                </td>
                                 <td width="40%">
                               PV System Cost
                                </td>
                                <td width="20%">
                                   :
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                   Rs. 1,60,000/-
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                3.
                                </td>
                                 <td width="40%">
                                SUBSIDY
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                2.
                                </td>
                                 <td width="40%">
                                Central Subsidy
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                   (30% of capital cost up to 500 kW)
                                </td>
                                <td width="20%">
                                   Rs. 45,000/-
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">

                                </td>
                                 <td width="40%">
                                 State Subsidy
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                  (Rs. 10,000/- per kW up to 2 kW)
                                </td>
                                <td width="20%">
                                   Rs. 20,000/-
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                2.
                                </td>
                                 <td width="40%">
                                 TOTAL SUBSIDY
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                   Rs. 65,000/-
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                2.
                                </td>
                                 <td width="40%">
                                Cost to User after Subsidy
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                  Rs. 95,000/-
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                4.
                                </td>
                                <td width="40%">
                                    MAINTENANCE COST
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                   Maintenance Cost
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  (1% of Capital Cost, annually)
                                </td>
                                <td width="20%">
                                 Rs. 1,600/- p.a.
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Escalation in Mainten.
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                 (5.72%, annually)
                                </td>
                                <td width="20%">
                                 &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                    &nbsp;
                                </td>
                                <td width="40%">
                                    Insurance Cost
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                    (0.35% of Capital Cost, annually)
                                </td>
                                <td width="20%">
                                    Rs. 560/- p.a
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                    &nbsp;
                                </td>
                                <td width="40%">
                                    Escalation in Insurance
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                    (Nil, annually)
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                    5.
                                </td>
                                <td width="40%">
                                   FINANCING PARAMETERS
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                                <td width="20%">
                                   &nbsp;
                                </td>
                                <td width="20%">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                    &nbsp;
                                </td>
                                <td width="40%">
                                    Loan Amount
                                </td>
                                <td width="20%">
                                    :
                                </td>
                                <td width="20%">
                                (70% of Capital Cost)
                                </td>
                                <td width="20%">
                                    Rs. 1,12,000/-
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                 Interest Rate
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                 (SBI Base Rate + 2%)
                                </td>
                                <td width="20%">
                                 12.50% p.a.
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                    Loan Term
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                 10 Years
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Monthly Pmt. (EMI)
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                 Rs. 2,500/- p.m.
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                 6
                                </td>
                                <td width="40%">
                                 TAX & DEPRECIATION
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                 &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Corporate Tax Rate
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                 (Applicable to Non-residential systems)
                                </td>
                                <td width="20%">
                                     33.99%
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                   Accelerated Depreciation
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td  colspan="2">
                                  First year: 80%, Second year: 20% (Applicable to Industrial & Residential Consumers)

                                </td>
                           </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Straight-Line Depreciation
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td  colspan="2">
                                  First 10 years: 6% p.a., Next 15 years: 2 % p.a. (Applicable to Non-residential, Non-industrial & Non-commercial Consumers)
                                </td>
                            </tr>
                            <tr style="background-color:yellow">
                                <td width="5%">
                                7
                                </td>
                                 <td width="40%">
                                 RESULTS
                                </td>
                                <td width="20%">
                                   &nbsp;
                                </td>
                                <td width="20%">
                                 &nbsp;
                                </td>
                                <td width="20%">
                                      &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Cost of Solar Energy
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                     Rs. 6.3 per Unit
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Internal Rate of Return
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                    12%
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                    Return on Equity
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                    16%
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Breakeven Period
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                  &nbsp;
                                </td>
                                <td width="20%">
                                    5.3 Years
                                </td>
                            </tr>
                        </table>
                        <div class="page-break"></div>
                        <p style="color:yellow">Month-wise Daily Energy Generation Chart</p>
                        <p style="color:yellow">Payback Analysis Chart</p>
                        <div class="page-break"></div>

                        <p style="color:yellow">Environmental Benefit</p>
                        <table border="0">
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  CO2 Avoided
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                   3.95 Tonnes per Year
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                  Equivalent Oil Saved
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                   1,552 Litres per Year
                                </td>
                            </tr>
                            <tr>
                                <td width="5%">
                                 &nbsp;
                                </td>
                                 <td width="40%">
                                    Equivalent Trees Planted
                                </td>
                                <td width="20%">
                                  :
                                </td>
                                <td width="20%">
                                   1,552 Litres per Year
                                </td>
                            </tr>
                        </table>
                <div class="page-break"></div>

                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">11</div>
                    <div class="header-title"><h1>Technical Data </h1></div>
                </div>
                <div class="page-break"></div>

                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">12</div>
                    <div class="header-title"><h1>Warranty and Deadline</h1></div>
                </div>
                <div class="page-break"></div>

                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">13</div>
                    <div class="header-title"> <h1>Commercial Proposal</h1></div>
                </div>
                <div class="page-break"></div>

                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">14</div>
                    <div class="header-title"> <h1>Summary</h1></div>
                </div>
                <div class="page-break"></div>

                <div class="nk-header">
                    <div class="count-numbers" style="color:yellow">15</div>
                    <div class="header-title"> <h1>Reference</h1></div>
                </div>
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