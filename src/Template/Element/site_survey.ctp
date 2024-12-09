<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Survey Page</title>
        <!-- Style CSS -->
        <link type="text/css" rel="stylesheet" media="all" href="css/style.css"  />
        <style>

        @font-face {
          font-family: 'arial_bold';
          src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALBD.TTF');
        }
        @font-face {
          font-family: 'arial_simple';
          src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/arial.ttf');
        }

        .td_survey { font-family: 'arial_bold'; font-size:15px; }
        .td_survey_simple { font-family: 'arial_simple'; font-size:15px; }
        .td_survey_notes { font-family: 'arial_bold'; font-size:15px; }
        .td_survey_1 {  width:35%; font-size:15px; }
        .list_ul_ol { font-size:15px; }
        @page :first {
            margin-left:0px;
            margin-right:15px;
        }
        #watermark { font-family: 'arial_simple'; position: fixed; width: 400px; height: 470px; margin-left: 120px; margin-top: 190px; opacity: 0.2;font-size: 150px; -webkit-transform: rotate(-30deg); -moz-transform: rotate(-30deg);}



        </style>
    </head>
    <body id="pdf-header">
        <?php
        if(empty($arr_subscription))
        {
            ?>
            <div id='watermark'>Ahasolar</div>
            <?php
        }
        ?>
        <div class="container">
            <div class="mainbox">
                <table>
                    <tr>
                        <td colspan="2">
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td >
                                    <p><img src="images/logo_pdf.png" style="height:120px;" /></p>
                                    </td>
                                    <td align="right" style="margin-right:10px;">
                                    <p><span> Project No.: <?php echo $result_data_pass[0]['project_id'];?></span></p>
                                    <p><span> Date: <?php echo date('d-M-Y');?> </span></p>
                                    </td>
                                </tr>
                                <tr><td colspan="2">
                                    <table border="0" cellspacing="0" cellpadding="10" style="background-color: #FDC426;border: 1px solid #FDC426;">
                                        <tr>
                                            <td style="font-size: 20px;font-weight: bold;padding-left:30px;color:#FFffff;">Site Survey Summary:<br/><?php echo ucwords($result_project_data['name']);?></td>
                                        </tr>
                                    </table>
                                </td></tr>
                                <tr><td colspan="2">&nbsp;
                                </td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <img src="images/survey_image1.png" style="width:635px;"/>
                        </td>
                        <td >
                            <img src="images/survey_image2.jpg" style="width:140px;height:105px;"/>
                        <br>
                            <img src="images/survey_image3.jpg" style="width:140px;height:105px;"/>
                        <br>
                            <img src="images/survey_image4.jpg" style="width:140px;height:105px;"/>
                        <br>
                            <img src="images/survey_image5.jpg" style="width:140px;height:105px;"/>
                        </td>
                    </tr>
                </table>
                <table style="margin-top:70px;margin-left: 25px;" align="center">
                    <tr>
                        <td style="color: #FDC426;font-size:30px;" colspan="3">AHA Solar...
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #FDC426;font-size:25px;" colspan="3">Finding Harmony in Chaos
                        </td>
                    </tr>
                     <tr>
                        <td colspan="3">&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" valign="top">
                            <h1 style="color: #FDC426;">Prepared by</h1>
                        </td>
                        <td width="5%" valign="top">
                            <h1 style="color: #FDC426;text-align: center;" > : </h1>
                        </td>
                        <td width="75%">
                            <h1 style="color: #FDC426;">AHA! Rooftop Solar Helper <br/>(Professional Version)</h1>
                        </td>
                    </tr>
                    <?php
                    if(!empty($result_installer))
                    {
                    ?>
                    <tr>
                        <td>
                            <h1 style="color: #FDC426;">Powered by</h1>
                        </td>
                        <td>
                            <h1 style="color: #FDC426;text-align: center;" > : </h1>
                        </td>
                        <td>
                            <h1 style="color: #FDC426;"><?php
                                    if($result_installer[0]['installers']['company_id']>0)
                                    {
                                        echo $result_installer[0]['companies']['company_name'];
                                    }
                                    else
                                    {
                                        echo $result_installer[0]['installers']['installer_name'];
                                    }
                                ?></h1>
                        </td>
                    </tr>
                    <?php
                    }
                ?>
                </table>
                <div class="page-break"></div>
                <table>
                    <thead>
                        <tr>
                            <td colspan="2"> 
                                <table  cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" class="td_survey_simple" ><h1 style="margin:0px;">Table of Contents</h1>
                                        <hr/></td>
                                    </tr>
                                    <?php
                                    if(!empty($arr_subscription))
                                    {
                                        ?>
                                        <tr>
                                            <td><img src="<?php echo WWW_ROOT;?>/img/frontend/banner_pdf.jpg" /></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </thead>
                </table>
                <ul class="list_ul_ol" style="list-style-type:none;">
                    <li><span class="td_survey">Site Survey Screen 1: Customer</span>
                        <ul class="td_survey_simple" style="list-style-type:disc;margin-top:10px;margin-bottom: 10px;">
                            <li>Customer Info</li>
                            <li>Contact</li>
                            <li>Project Basic Info</li>
                            <li>Project Name</li>
                        </ul>
                    </li>
                    <li><span class="td_survey">Site Survey Screen 2: Roof</span>
                        <ul class="td_survey_simple" style="list-style-type:disc;margin-top:10px;margin-bottom: 10px;">
                            <li>Site Info</li>
                            <li>Roof Info</li>
                            <li>Roof Type</li>
                            <li>Area</li>
                            <li>Orientation</li>
                        </ul>
                    </li>
                    <li><span class="td_survey">Site Survey Screen 3: Interfacing</span>
                        <ul class="td_survey_simple" style="list-style-type:disc;margin-top:10px;margin-bottom: 10px;">
                            <li>Electrical Interconnection</li>
                            <li>Voltage Level</li>
                            <li>Metering Type</li>
                            <li>Location</li>
                        </ul>
                    </li>
                    <li><span class="td_survey">Site Survey Screen 4: Bill and Tariff</span>
                        <ul class="td_survey_simple" style="list-style-type:disc;margin-top:10px;margin-bottom: 10px;">
                            <li>Bill Details</li>
                        </ul>
                    </li>
                </ul>
                <?php
                if(count($result_data_pass)>1)
                {
                    ?>
                    <ul class="list_ul_ol" style="list-style-type:none;"><li><span class="td_survey" style="font-size:20px;">List of Buildings</span>
                        <ol class="list_ul_ol td_survey_simple" style="margin-top:10px;margin-bottom: 10px;">
                            <?php
                            foreach($result_data_pass as $result_data)
                            {
                                ?>
                                <li><?php echo $result_data['building_name'];?></li>
                                <?php
                           }
                           ?>
                        </ol>
                        </li>
                    </ul>
                    <?php
                }
                $disp_build_count   = 0;
                $build_count        = 0;
                if(count($result_data_pass)>1)
                {
                    $disp_build_count = 1;
                }
                foreach($result_data_pass as $result_data)
                {
                    $build_count++;
                    $all_photo_data     = $all_photo_data_projects[$result_data['id']];
                    $result_photo_data  = $result_photo_data_projects[$result_data['id']];
                    ?>
                <div class="page-break"></div>
                    <table  cellspacing="0" cellpadding="0" border="0">
                        <thead>
                        <tr>
                            <td colspan="2"> 
                                <table  cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" class="td_survey_simple"><h1 style="margin:0px;">Site Survey Screen 1: <br/>Customer Details</h1></td>
                                        <td align="right" valign="top" class="td_survey_simple"><h1 style="margin:0px;">Building <?php if($disp_build_count == 1) { echo $build_count; } ?>: <?php echo $result_data['building_name'];?></h1></td>
                                    </tr>
                                    <?php
                                    if(!empty($arr_subscription))
                                    {
                                        ?>
                                        <tr>
                                            <td colspan="2"><img src="<?php echo WWW_ROOT;?>/img/frontend/banner_pdf.jpg" /></td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <tr><td colspan="2">
                                        <hr/></td></tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </thead>
                    </table>
                <table  border="0">
                    <tr>
                        <td class="td_survey">Site Survey Date</td>
                        <td class="td_survey_simple"><?php echo date('d-M-Y',strtotime($result_data['created']));?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Project Name</td>
                        <td class="td_survey_simple"><?php echo ucwords($result_project_data['name']);?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Building Name</td>
                        <td class="td_survey_simple"><?php echo $result_data['building_name'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Contact Person</td>
                        <td class="td_survey_simple"><?php echo $result_data['contact_name'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Designation</td>
                        <td class="td_survey_simple"><?php echo $result_data['designation'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Mobile No.</td>
                        <td class="td_survey_simple"><?php echo $result_data['mobile'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Landline</td>
                        <td class="td_survey_simple"><?php echo $result_data['landline'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Email</td>
                        <td class="td_survey_simple"><?php echo $result_data['email_id'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Address Line 1</td>
                        <td class="td_survey_simple"><?php echo $result_data['address1'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Address Line 2</td>
                        <td class="td_survey_simple"><?php echo $result_data['address2'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Address Line 3</td>
                        <td class="td_survey_simple"><?php echo $result_data['address3'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Latitude</td>
                        <td class="td_survey_simple"><?php if($result_data['site_lat']>0) { echo $result_data['site_lat']; } else { echo '-'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Longitude</td>
                        <td class="td_survey_simple"><?php if($result_data['site_log']>0) { echo $result_data['site_log']; } else { echo '-'; } ?></td>
                    </tr>
                    <?php
                    if($mapImage[$result_data['id']]!='')
                    {
                        ?>
                        <tr>
                            <td colspan="2" align="center" >&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center" ><img src="<?php echo $mapImage[$result_data['id']];?>" /></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td  colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="td_survey_notes" colspan="2">Notes</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td_survey_simple"><?php echo $result_data['notes1'];?></td>
                    </tr>
                </table>
                <div class="page-break"></div>
                <?php
                $disp_inverter_ph     = 0;
                $disp_battery         = 0;
                $disp_ac_distribution = 0;
                $disp_meter_point     = 0;
                ?>
                <table  border="0">
                    <thead>
                        <tr>
                            <td colspan="2"> 
                                <table  cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" class="td_survey_simple"><h1 style="margin:0px;">Site Survey Screen 2: <br/>Roof Details</h1></td>
                                        <td align="right" valign="top" class="td_survey_simple"><h1 style="margin:0px;">Building <?php if($disp_build_count == 1) { echo $build_count; } ?>: <?php echo $result_data['building_name'];?></h1></td>
                                    </tr>
                                    <?php
                                    if(!empty($arr_subscription))
                                    {
                                        ?>
                                        <tr>
                                            <td colspan="2"><img src="<?php echo WWW_ROOT;?>/img/frontend/banner_pdf.jpg" /></td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <tr><td colspan="2">
                                        <hr/>
                                        </td></tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </thead>
                    <tr>
                        <td class="td_survey">Building Name</td>
                        <td class="td_survey_simple"><?php echo $result_data['building_name'];?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ul class="list_ul_ol"><span class="td_survey">Type of Roof</span><br/>
                               
                                    <?php 
                                    
                                    if($result_data['roof_type']==4) 
                                    { 
                                        echo ' - '.$result_data['other_roof_type']; 
                                    } 
                                    elseif($result_data['roof_type']==1)
                                    {
                                        echo '<img src="images/flat_rcc_roof.jpg" />';    
                                    }
                                    elseif($result_data['roof_type']==2)
                                    {
                                        echo '<img src="images/tilt_shed.jpg" />';    
                                    }
                                    elseif($result_data['roof_type']==3)
                                    {
                                        echo '<img src="images/sheet_metal.jpg" />';    
                                    }
                                    echo '<li class="td_survey_simple">'.$all_roof[$result_data['roof_type']].'</li>'; 
                            ?>        
                            </ul>
                        </td>
                    </tr>
                     <tr>
                        <td colspan="2"><span class="td_survey">Roof Strength</span>
                            <ul class="list_ul_ol">
                                <li class="td_survey_simple">
                                    <?php if($result_data['roof_strenght']!='' && $result_data['roof_strenght']!='0') { echo $all_roof_st[$result_data['roof_strenght']]; } else { echo 'N/A'; } ?></li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="2"><span class="td_survey">Roof Area</span>
                            <ul class="list_ul_ol" >
                                <li class="td_survey_simple">Overall <span  style="margin-left: 130px;">
                                <?php if($result_data['overall']!='') { echo $result_data['overall']." ".$all_area_types[$result_data['is_overall']]; }?></span></li>
                                <li class="td_survey_simple">Shadow-free <span style="margin-left: 90px;"><?php if($result_data['shadow_free']!='') { echo $result_data['shadow_free']." ".$all_area_types[$result_data['is_shadow_free']]; }?></span></li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_survey">Age of Building</td>
                        <td class="td_survey_simple"><?php echo $result_data['age_of_building'];?> Years</td>
                    </tr>
                    <tr>
                        <td class="td_survey">Azimuth</td>
                        <td class="td_survey_simple"><?php if($result_data['azimuth']!='') { echo $result_data['azimuth'].'degrees'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Inclination</td>
                        <td class="td_survey_simple"><?php if($result_data['inclination_of_roof']!='') { echo $result_data['inclination_of_roof'].'degrees'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Is there any major showdown-casting object on the roof?</td>
                        <td valign="top" class="td_survey_simple"><?php echo $result_data['object_on_roof'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Height of Parapet?</td>
                        <td class="td_survey_simple"><?php if($result_data['height_of_parapet']!='') { echo $result_data['height_of_parapet']." ".$all_area_type_smp[$result_data['is_height_of_parapet']]; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">How many floors below the Terrace?</td>
                        <td valign="top" class="td_survey_simple"><?php echo $result_data['floor_below_tarrace'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Distance of DC cable?</td>
                        <td class="td_survey_simple"><?php if($result_data['is_dc_cable_distance']!='') { echo $result_data['dc_cabel_distance']." ".$all_area_type_smp[$result_data['is_dc_cable_distance']]; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Place for Inverter?</td>
                        <td class="td_survey_simple"><?php if(!empty($result_photo_data) && array_key_exists('place_inverter',$result_photo_data)) 
                            { 
                                echo 'Yes'; 
                                $disp_inverter_ph=1; 
                            } 
                            else
                            { 
                                echo 'No'; 
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Place for Battery?</td>
                        <td class="td_survey_simple"><?php if(!empty($result_photo_data) && array_key_exists('place_battery',$result_photo_data)) 
                            { 
                                echo 'Yes';
                                $disp_battery = 1;
                            } 
                            else
                            { 
                                echo 'No'; 
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Place for AC Distribution Box?</td>
                        <td class="td_survey_simple"><?php if(!empty($result_photo_data) && array_key_exists('place_for_ac_distribution_box',$result_photo_data)) 
                            { 
                                echo 'Yes';
                                $disp_ac_distribution = 1;
                            } 
                            else
                            { 
                                echo 'No'; 
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Place for Metering Point?</td>
                        <td class="td_survey_simple"><?php if(!empty($result_photo_data) && array_key_exists('metering_box',$result_photo_data)) 
                            { 
                                echo 'Yes';
                                $disp_meter_point = 1;
                            } 
                            else
                            { 
                                echo 'No'; 
                            }
                            ?></td>
                    </tr>
                </table>
                <div class="page-break"></div>
                    <table  border="0" width="100%" cellspacing="0" cellpadding="0">
                        <thead>
                        <tr>
                            <td colspan="2"> 
                                <table  cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" class="td_survey_simple"><h1 style="margin:0px;">Site Survey Screen 2: <br/>Roof Details</h1></td>
                                        <td align="right" valign="top" class="td_survey_simple"><h1 style="margin:0px;">Building <?php if($disp_build_count == 1) { echo $build_count; } ?>: <?php echo $result_data['building_name'];?></h1></td>
                                    </tr>
                                    <?php
                                    if(!empty($arr_subscription))
                                    {
                                        ?>
                                        <tr>
                                            <td colspan="2"><img src="<?php echo WWW_ROOT;?>/img/frontend/banner_pdf.jpg" /></td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <tr><td colspan="2">
                                        <hr/>
                                        </td></tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr> 
                        </thead>
                        <?php
                        if($disp_inverter_ph == 1)
                        {
                        ?>
                        <tr>
                        <td class="td_survey_1 td_survey">Photos for Inverter</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="5" cellspacing="5">
                                    <tr>
                                    <?php
                                    $counter=1;
                                    foreach($all_photo_data as $ph_data)
                                    {
                                        if($ph_data['type'] == 'place_inverter')
                                        {
                                            $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                            if (file_exists($path))
                                            {
                                                $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                                ?>
                                                <td >
                                                    <img src="<?php echo $image_url;?>" width="155px" />
                                                </td>
                                                <?php
                                                if($counter % 4==0)
                                                {
                                                    ?>
                                                    </tr><tr>
                                                    <?php
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    }
                    if($disp_battery == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1 td_survey">Photos for Battery</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="5" cellspacing="5">
                                    <tr>
                                    <?php
                                    $counter=1;
                                    foreach($all_photo_data as $ph_data)
                                    {
                                        if($ph_data['type'] == 'place_battery')
                                        {
                                            $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                            if (file_exists($path))
                                            {
                                                $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                                ?>
                                                <td>
                                                    <img src="<?php echo $image_url;?>" width="155px" />
                                                </td>
                                                <?php
                                                if($counter % 4==0)
                                                {
                                                    ?>
                                                    </tr><tr>
                                                    <?php
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    }
                    if($disp_ac_distribution == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1 td_survey">Photos for AC Distribution Box</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="5" cellspacing="5">
                                    <tr>
                                    <?php
                                    $counter=1;
                                    foreach($all_photo_data as $ph_data)
                                    {
                                        if($ph_data['type'] == 'place_for_ac_distribution_box')
                                        {
                                            $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                            if (file_exists($path))
                                            {
                                                $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                                ?>
                                                <td>
                                                    <img src="<?php echo $image_url;?>" width="155px" />
                                                </td>
                                                <?php
                                                if($counter % 4==0)
                                                {
                                                    ?>
                                                    </tr><tr>
                                                    <?php
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    }
                    if($disp_meter_point == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1 td_survey">Photos for Metering Point</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="5" cellspacing="5">
                                    <tr>
                                    <?php
                                    $counter=1;
                                    foreach($all_photo_data as $ph_data)
                                    {
                                        if($ph_data['type'] == 'metering_box')
                                        {
                                            $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                            if (file_exists($path))
                                            {
                                                $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                                ?>
                                                <td>
                                                    <img src="<?php echo $image_url;?>" width="155px" />
                                                </td>
                                                <?php
                                                if($counter % 4==0)
                                                {
                                                    ?>
                                                    </tr><tr>
                                                    <?php
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    }
                    if(!empty($result_photo_data) && (array_key_exists('take_photographs', $result_photo_data) || array_key_exists('site_photos', $result_photo_data)))
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1 td_survey">Others Photos</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="5" cellspacing="5">
                                    <tr>
                                    <?php
                                    $counter=1;
                                    foreach($all_photo_data as $ph_data)
                                    {
                                        if($ph_data['type'] == 'site_photos' || $ph_data['type'] == 'take_photographs')
                                        {
                                            $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                            if (file_exists($path))
                                            {
                                                $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                                ?>
                                                <td>
                                                    <img src="<?php echo $image_url;?>" width="155px" />
                                                </td>
                                                <?php
                                                if($counter % 4==0)
                                                {
                                                    ?>
                                                    </tr><tr>
                                                    <?php
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                        <tr>
                            <td class="td_survey_notes">Notes</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td_survey_simple"><?php echo $result_data['notes2'];?></td>
                        </tr>
                        </table>
                    
                <div class="page-break"></div>
                <table  border="0" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td colspan="2"> 
                                <table  cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="left" class="td_survey_simple"><h1 style="margin:0px;">Site Survey Screen 3: <br/>Electrical Parameters</h1></td>
                                        <td align="right" valign="top" class="td_survey_simple"><h1 style="margin:0px;">Building <?php if($disp_build_count == 1) { echo $build_count; } ?>: <?php echo $result_data['building_name'];?></h1></td>
                                    </tr>
                                    <?php
                                    if(!empty($arr_subscription))
                                    {
                                        ?>
                                        <tr>
                                            <td colspan="2"><img src="<?php echo WWW_ROOT;?>/img/frontend/banner_pdf.jpg" /></td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <tr><td colspan="2">
                                        <hr/>
                                        </td></tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </thead>
                
                    <tr>
                        <td class="td_survey_1 td_survey">Voltage Level</td>
                        <td><?php echo str_replace('p','P',$result_data['voltage_pahse_level']);?></td>
                    </tr>
                    <?php
                    if(!empty($result_data['reading_details']))
                    {
                        ?>
                        <tr>
                            <td class="td_survey_1 td_survey">Electrical Parameters</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td_survey_simple">
                                <?php $arr_reading_data = unserialize($result_data['reading_details']); 
                                foreach($arr_reading_data['ReadingDetails'] as $val)
                                {
                                    ?>
                                    <table border="1" style="background-color: #F2F2F2;">
                                        <tr>
                                            <td width="20%">R-Phase</td>
                                            <td width="10%" align="center"><?php if(!empty($val['r_phase'])) { echo $val['r_phase']; } else { echo '-'; } ?></td>
                                            <td width="10%">A</td>
                                            <td width="10%">RY</td>
                                            <td width="10%" align="center"><?php if(!empty($val['r_phase_ry'])) {  echo $val['r_phase_ry']; } else { echo '-'; } ?></td>
                                            <td width="10%">V</td>
                                            <td width="10%">RN</td>
                                            <td width="10%" align="center"><?php if(!empty($val['r_phase_rn'])) { echo $val['r_phase_rn'];  } else { echo '-'; } ?></td>
                                            <td width="10%">V</td>
                                        </tr>
                                        <tr>
                                            <td>Y-Phase</td>
                                            <td align="center"><?php if(!empty($val['y_phase'])) { echo $val['y_phase']; } else { echo '-'; } ?></td>
                                            <td>A</td>
                                            <td>YB</td>
                                            <td align="center"><?php if(!empty($val['y_phase_yb'])) { echo $val['y_phase_yb']; } else { echo '-'; } ?></td>
                                            <td>V</td>
                                            <td>YN</td>
                                            <td align="center"><?php if(!empty($val['y_phase_yb'])) { echo $val['y_phase_yn']; } else { echo '-'; } ?></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>B-Phase</td>
                                            <td align="center"><?php if(!empty($val['b_phase'])) { echo $val['b_phase'];  } else { echo '-'; } ?></td>
                                            <td>A</td>
                                            <td>RB</td>
                                            <td align="center"><?php if(!empty($val['b_phase_rb'])) { echo $val['b_phase_rb']; } else { echo '-'; } ?></td>
                                            <td>V</td>
                                            <td>BN</td>
                                            <td align="center"><?php if(!empty($val['b_phase_bn'])) { echo $val['b_phase_bn']; } else { echo '-'; } ?></td>
                                            <td>V</td>
                                        </tr>
                                    </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td class="td_survey_1 td_survey">Measured Frequency:</td>
                        <td class="td_survey_simple"><?php if($result_data['measured_frequency']!='') { echo $result_data['measured_frequency'].' Hz'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1 td_survey">Critical Load:</td>
                        <td class="td_survey_simple"><?php if($result_data['critical_load']!='') { echo $result_data['critical_load'].' kW'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <?php
                    if(!empty($result_data['genset_details']) || !empty($result_data['inverter_details']))
                    {
                        ?>
                        <tr>
                            <td class="td_survey_1 td_survey">Diesel Genset</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td_survey_simple">
                                <table border="1" style="background-color: #F2F2F2;">
                                <?php $arr_genset_data = unserialize($result_data['genset_details']); 
                                foreach($arr_genset_data['GensetDetails'] as $val)
                                {
                                    ?>
                                        <tr>
                                            <td width="20%">Diesel Genset</td>
                                            <td width="15%" align="center"><?php if(!empty($val['kva'])) { echo $val['kva']; } else { echo '-'; }?></td>
                                            <td width="15%">kVA</td>
                                            <td width="15%">Usage</td>
                                            <td width="15%" align="center"><?php if(!empty($val['hours'])) { echo $val['hours']; } else { echo '-'; }?></td>
                                            <td width="20%">Hours/Day</td>
                                        </tr>
                                    <?php
                                }
                                $arr_genset_data = unserialize($result_data['inverter_details']); 
                                foreach($arr_genset_data['InverterDetails'] as $val)
                                {
                                    ?>
                                        <tr>
                                            <td width="20%">Inverter</td>
                                            <td width="15%" align="center"><?php if(!empty($val['kva'])) { echo $val['kva']; }  else { echo '-'; }?></td>
                                            <td width="15%">kVA</td>
                                            <td width="15%">Usage</td>
                                            <td width="15%" align="center"><?php if(!empty($val['hours'])) { echo $val['hours']; }  else { echo '-'; }?></td>
                                            <td width="20%">Hours/Day</td>
                                        </tr>
                                    <?php
                                }
                                ?>
                                </table>
                            </td>
                        </tr>
                    <?php
                    }
                    if(!empty($result_photo_data) && (array_key_exists('electricity_bill', $result_photo_data)))
                    {
                        ?>
                        <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                        <td class="td_survey_1 td_survey">Photographs</td>
                        <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table cellpadding="5" cellspacing="5">
                                    <tr>
                                    <?php
                                    $counter=1;
                                    foreach($all_photo_data as $ph_data)
                                    {
                                        if($ph_data['type'] == 'electricity_bill')
                                        {
                                            $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                            if (file_exists($path))
                                            {
                                                $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                                ?>
                                                <td>
                                                    <img src="<?php echo $image_url;?>" width="155px" />
                                                </td>
                                                <?php
                                                if($counter % 4==0)
                                                {
                                                    ?>
                                                    </tr><tr>
                                                    <?php
                                                }
                                                $counter++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2">&nbsp;</td>   
                    </tr>
                    <tr>
                        <td class="td_survey_notes" colspan="2">Notes</td>   
                    </tr>
                    <tr>
                        <td colspan="2" class="td_survey_simple"><?php echo $result_data['notes3'];?></td>
                        
                    </tr>
                </table>
                <div class="page-break"></div>
                <table>
                    <thead>
                            <tr>
                                <td colspan="2"> 
                                    <table  cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td align="left" class="td_survey_simple"><h1 style="margin:0px;">Site Survey Screen 4: <br/>Electricity Bill and Tariff Details</h1></td>
                                            <td align="right" valign="top" class="td_survey_simple"><h1 style="margin:0px;">Building <?php if($disp_build_count == 1) { echo $build_count; } ?>: <?php echo $result_data['building_name'];?></h1></td>
                                        </tr>
                                        <?php
                                        if(!empty($arr_subscription))
                                        {
                                            ?>
                                            <tr>
                                                <td colspan="2"><img src="<?php echo WWW_ROOT;?>/img/frontend/banner_pdf.jpg" /></td>
                                            </tr>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <tr><td colspan="2">
                                            <hr/>
                                            </td></tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </thead>
                    <tr>
                        <td class="td_survey_1 td_survey">Distribution Company:</td>
                        <td class="td_survey_simple"><?php echo $result_data['distribution_company'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1 td_survey" >Service/ Customer No.:</td>
                        <td class="td_survey_simple"><?php echo $result_data['customer_no'];?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><span class="td_survey_1 td_survey">Meter Type:</span>
                            <ul class="list_ul_ol">
                                <li class="td_survey_simple"><?php if($all_meter[$result_data['meter_type']] == '') { echo 'N/A'; } else { echo $all_meter[$result_data['meter_type']]; } ?></li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><span class="td_survey_1 td_survey">Meter Accuracy Class:</span>
                            <ul class="list_ul_ol">
                                <li class="td_survey_simple"><?php if($all_meter_accuracy[$result_data['meter_accuracy']] == '') { echo 'N/A'; } else { echo $all_meter_accuracy[$result_data['meter_accuracy']]; } ?></li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><span class="td_survey_1 td_survey">Type of Customer:</span>
                            <ul class="list_ul_ol">
                                <li class="td_survey_simple"><?php if($all_cust_type[$result_data['customer_type']] == '') {  echo 'N/A'; } else { echo $all_cust_type[$result_data['customer_type']]; } ?></li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_survey_1 td_survey">Sanctioned Load:</td>
                        <td class="td_survey_simple"><?php if($result_data['sanctioned_load']!='') { echo $result_data['sanctioned_load'];?> - <?php echo $all_load[$result_data['is_snaction']]; }?> </td>
                    </tr>
                    <tr>
                        <td class="td_survey_1 td_survey">Contract Demand:</td>
                        <td class="td_survey_simple"><?php if($result_data['contract_demand']!='') { echo $result_data['contract_demand'];?> - <?php echo $all_load[$result_data['is_contract']]; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1 td_survey">Billing Cycle:</td>
                        <td class="td_survey_simple"><?php echo $all_billing[$result_data['billing_cycle']];?></td>
                    </tr>
                <?php
                if(!empty($result_data['month_details']))
                {
                ?>
                <tr><td colspan="2" class="td_survey">Electricity Bill Details:</td></tr>
                <tr><td colspan="2" >
                    <table  border="1" style="border:1px solid #000000;" width="400px">
                        <tr>
                            <td style="background-color: #F2F2F2;" class="td_survey">Month</td>
                            <td style="background-color: #F2F2F2;" class="td_survey">Year</td>
                            <td style="background-color: #F2F2F2;" class="td_survey">Unit</td>
                            <td style="background-color: #F2F2F2;" class="td_survey">Amount</td>
                        </tr>
                        <?php
                        $arr_month       = unserialize($result_data['month_details']);
                        $arr_all_month   = $arr_month['ElectricityBillDetails'];
                        $sum_power_con   = 0;
                        $sum_bill_amount = 0;
                        $total_val       = 0;
                        for($i=0;$i<=11;$i++)
                        {
                            ?>
                            <tr>
                            <td style="background-color: #F2F2F2;" class="td_survey_simple"><?php echo $arr_all_month[$i]['month'];?></td>
                            <?php
                            $str_year          = '';
                            $str_power_consume = '';
                            $str_bill_amount   = '';
                            if(strtolower($arr_all_month[$i]['year'])!='year')
                            {
                                $str_year          = $arr_all_month[$i]['year'];
                                $str_power_consume = $arr_all_month[$i]['power_consume'];
                                $str_bill_amount   = $arr_all_month[$i]['bill_amount'];
                                $sum_power_con     = $sum_power_con+$arr_all_month[$i]['power_consume'];
                                $sum_bill_amount   = $sum_bill_amount+$arr_all_month[$i]['bill_amount'];
                                $total_val++;
                            }  
                            ?>
                            <td style="background-color: #F2F2F2;" class="td_survey_simple"><?php echo $str_year;?></td>
                            <td style="background-color: #F2F2F2;" class="td_survey_simple"><?php echo $str_power_consume;?></td>
                            <td style="background-color: #F2F2F2;" class="td_survey_simple"><?php echo $str_bill_amount;?></td>
                            </tr>
                            <?php             
                        }
                        $avg_pow_con  = 0;
                        $avg_bill_amt = 0;
                        if($total_val>0)
                        {
                            $avg_pow_con  = number_format($sum_power_con/$total_val,'2','.',',');
                            $avg_bill_amt = number_format($sum_bill_amount/$total_val,'2','.',',');
                        }
                        ?>
                        <tr>
                            <td style="background-color: #F2F2F2;" class="td_survey">Average</td>
                            <td style="background-color: #F2F2F2;" class="td_survey"></td>
                            <td style="background-color: #F2F2F2;" class="td_survey"><?php echo $avg_pow_con;?></td>
                            <td style="background-color: #F2F2F2;" class="td_survey"><?php echo $avg_bill_amt;?></td>
                        </tr>
                    </table>
                </td></tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="2">&nbsp;</td>   
                </tr>
                <tr>
                    <td class="td_survey_notes" colspan="2">Notes</td>
                </tr>
                <tr>
                    <td colspan="2" class="td_survey_simple"><?php echo $result_data['notes4'];?></td>
                </tr>
                </table>  
                <?php
            }
            ?>         
            </div>
        </div>
        <div id="footer" class='f_data'>
            <table>
                <tr>
                    <td class="td_survey_simple" style="color: #FDC426;">
                     <?php echo date('d M Y');?> <?php echo ucwords($result_project_data['name']);?> <?php echo $footer_st_content;?> 
                    </td>
                    <td class="td_survey_simple" style="color:#000000;" align="right"><p class="page">Page <p></td>
                </tr>
            </table>
        </div>
    </body>
</html>