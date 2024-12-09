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
        .td_survey { font-family:dejavu sans; font-style:bold; width:25%; font-size:16px; }
        .td_survey_1 { font-family:dejavu sans; font-style:bold; width:35%; font-size:16px; }
        </style>
    </head>
    <body id="pdf-header">
        <div id="footer">
            <table>
                <tr>
                    <td>
                     <?php echo date('d M Y',strtotime($result_data['created']));?> <?php echo $result_project_data['name'];?> Site Survey <?php echo $result_data['building_name'];?> Project Report
                    </td>
                    <td align="right"><p class="page">Page <p></td>
                </tr>
            </table>
        </div>
        <div class="container">
            
            <div class="mainbox">
                <table>
                    <tr>
                        <td align="left">
                        <p><img src="images/logo_pdf.png" style="height:120px;" > </p>
                        </td>
                        <td align="right">
                        <p><span> <?php echo date('d-M-Y',strtotime($result_data['created']));?> </span></p>
                        </td>
                    </tr>
                    <tr>
                        <td >
                        <img src="images/survey_image1.png" style="width:540px;">
                        </td>
                        <td >
                        <img src="images/survey_image2.jpg" style="width:140px;">
                        <br>
                        <img src="images/survey_image3.jpg" style="width:140px;">
                        <br>
                        <img src="images/survey_image4.jpg" style="width:140px;">
                        <br>
                        <img src="images/survey_image5.jpg" style="width:140px;">
                        </td>
                    </tr>
                </table>
                <table style="margin-top:160px;width:520px;" align="center">
                    <tr>
                        <td>
                            <h1 style="color: #FDC426;width:190px;">Report prepared by</h1>
                        </td>
                        <td>
                            <h1 style="color: #FDC426;width:60px;text-align: center;" > : </h1>
                        </td>
                        <td>
                            <h1 style="color: #FDC426;width:270px">AHA! Rooftop Solar Helper (Professional Version)</h1>
                        </td>
                    </tr>
                </table>
                <div class="page-break"></div>
                <h1>Customer Details</h1>
                <hr/>
                <table  border="0">
                    <tr>
                        <td class="td_survey">Organization Name</td>
                        <td>Organization Name</td>
                    </tr>
                    <tr>
                        <td class="td_survey">Contact Person</td>
                        <td><?php echo $result_data['contact_name'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Designation</td>
                        <td><?php echo $result_data['designation'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Mobile No.</td>
                        <td><?php echo $result_data['mobile'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Landline</td>
                        <td><?php echo $result_data['landline'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Email</td>
                        <td><?php echo $result_data['email_id'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Address Line 1</td>
                        <td><?php echo $result_data['address1'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Address Line 2</td>
                        <td><?php echo $result_data['address2'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Address Line 3</td>
                        <td><?php echo $result_data['address3'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Latitude</td>
                        <td><?php echo $result_data['site_lat'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey">Longitude</td>
                        <td><?php echo $result_data['site_log'];?></td>
                    </tr>
                    <?php
                    if($mapImage!='')
                    {
                        ?>
                        <tr>
                            <td colspan="2"><img src="<?php echo $mapImage;?>" ></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <div class="page-break"></div>
                <h1>Roof Details</h1>
                <hr/>
                <?php
                $disp_inverter_ph     = 0;
                $disp_battery         = 0;
                $disp_ac_distribution = 0;
                $disp_meter_point     = 0;
                ?>
                <table  border="0">
                    <tr>
                        <td class="td_survey_1">Building Name</td>
                        <td><?php echo $result_data['building_name'];?></td>
                    </tr>
                     <tr>
                        <td class="td_survey_1">Type of Roof</td>
                        <td><?php echo $all_roof[$result_data['roof_type']]; 
                            if($result_data['roof_type']==4) { echo ' - '.$result_data['other_roof_type']; } ?></td>
                    </tr>
                     <tr>
                        <td class="td_survey_1">Roof Strength</td>
                        <td><?php echo $all_roof_st[$result_data['roof_strenght']];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Roof Area</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Overall</td>
                        <td><?php echo $result_data['overall']." ".$all_area_types[$result_data['is_overall']];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Shadow-free</td>
                        <td><?php echo $result_data['shadow_free']." ".$all_area_types[$result_data['is_shadow_free']];?></td>
                    </tr>
                   <tr>
                        <td class="td_survey_1">Age of Building</td>
                        <td><?php echo $result_data['age_of_building'];?> Years</td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Azimuth</td>
                        <td><?php if($result_data['azimuth']!='') { echo $result_data['azimuth'].'degrees'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Inclination</td>
                        <td><?php if($result_data['inclination_of_roof']!='') { echo $result_data['inclination_of_roof'].'degrees'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Is there any major showdown-casting object on the roof?</td>
                        <td valign="top"><?php echo $result_data['object_on_roof'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Height of Parapet?</td>
                        <td><?php if($result_data['height_of_parapet']!='') { echo $result_data['height_of_parapet']." ".$all_area_type_smp[$result_data['is_height_of_parapet']]; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">How many floors below the Terrace?</td>
                        <td valign="top"><?php echo $result_data['floor_below_tarrace'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Distance of DC cable?</td>
                        <td><?php if($result_data['is_dc_cable_distance']!='') { echo $result_data['dc_cabel_distance']." ".$all_area_type_smp[$result_data['is_dc_cable_distance']]; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Place for Inverter?</td>
                        <td><?php if(!empty($result_photo_data) && array_key_exists('place_inverter',$result_photo_data)) 
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
                        <td class="td_survey_1">Place for Battery?</td>
                        <td><?php if(!empty($result_photo_data) && array_key_exists('place_battery',$result_photo_data)) 
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
                        <td class="td_survey_1">Place for AC Distribution Box?</td>
                        <td><?php if(!empty($result_photo_data) && array_key_exists('place_for_ac_distribution_box',$result_photo_data)) 
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
                        <td class="td_survey_1">Place for Metering Point?</td>
                        <td><?php if(!empty($result_photo_data) && array_key_exists('metering_box',$result_photo_data)) 
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
                    <?php
                    if($disp_inverter_ph == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1">Photos for Inverter</td>
                        <td></td>
                        </tr>
                        <?php
                        foreach($all_photo_data as $ph_data)
                        {
                            if($ph_data['type'] == 'place_inverter')
                            {
                                $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                if (file_exists($path))
                                {
                                    $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <img src="<?php echo $image_url;?>" width="450px">
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    if($disp_battery == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1">Photos for Battery</td>
                        <td></td>
                        </tr>
                        <?php
                        foreach($all_photo_data as $ph_data)
                        {
                            if($ph_data['type'] == 'place_battery')
                            {
                                $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                if (file_exists($path))
                                {
                                    $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <img src="<?php echo $image_url;?>" width="450px">
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    if($disp_ac_distribution == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1">Photos for AC Distribution Box</td>
                        <td></td>
                        </tr>
                        <?php
                        foreach($all_photo_data as $ph_data)
                        {
                            if($ph_data['type'] == 'place_for_ac_distribution_box')
                            {
                                $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                if (file_exists($path))
                                {
                                    $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <img src="<?php echo $image_url;?>" width="450px">
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    if($disp_meter_point == 1)
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1">Photos for Metering Point</td>
                        <td></td>
                        </tr>
                        <?php
                        foreach($all_photo_data as $ph_data)
                        {
                            if($ph_data['type'] == 'metering_box')
                            {
                                $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                if (file_exists($path))
                                {
                                    $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <img src="<?php echo $image_url;?>" width="450px">
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    if(!empty($result_photo_data) && (array_key_exists('take_photographs', $result_photo_data) || array_key_exists('site_photos', $result_photo_data)))
                    {
                        ?>
                        <tr>
                        <td class="td_survey_1">Others Photos</td>
                        <td></td>
                        </tr>
                        <?php
                        foreach($all_photo_data as $ph_data)
                        {
                            if($ph_data['type'] == 'site_photos' || $ph_data['type'] == 'take_photographs')
                            {
                                $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                if (file_exists($path))
                                {
                                    $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <img src="<?php echo $image_url;?>" width="450px">
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </table>
                 <div class="page-break"></div>
                <h1>Electrical Parameters</h1>
                <hr/>
                <table  border="0">
                    <tr>
                        <td class="td_survey_1">Voltage Level</td>
                        <td><?php echo $result_data['voltage_pahse_level'];?></td>
                    </tr>
                    <?php
                    if(!empty($result_data['reading_details']))
                    {
                        ?>
                        <tr>
                            <td class="td_survey_1">Electrical Parameters</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
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
                        <td class="td_survey_1">Measured Frequency:</td>
                        <td><?php if($result_data['measured_frequency']!='') { echo $result_data['measured_frequency'].' Hz'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Critical Load:</td>
                        <td><?php if($result_data['critical_load']!='') { echo $result_data['critical_load'].' kW'; } else { echo 'N/A'; } ?></td>
                    </tr>
                    <?php
                    if(!empty($result_data['genset_details']) || !empty($result_data['inverter_details']))
                    {
                        ?>
                        <tr>
                            <td class="td_survey_1">Diesel Genset</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
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
                        <td class="td_survey_1">Photos</td>
                        <td></td>
                        </tr>
                        <?php
                        foreach($all_photo_data as $ph_data)
                        {
                            if($ph_data['type'] == 'electricity_bill')
                            {
                                $path = SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                if (file_exists($path))
                                {
                                    $image_url=WWW_ROOT.SITE_SURVEY_PATH.$ph_data['type'].'/'.$ph_data['photo'];
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <img src="<?php echo $image_url;?>" width="450px">
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </table>
                <div class="page-break"></div>
                <h1>Electricity Bill and Tariff Details</h1>
                <hr />
                <table>
                    <tr>
                        <td class="td_survey_1" style="font-weight: bold !important;">Distribution Company:</td>
                        <td><?php echo $result_data['distribution_company'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1" style="font-weight: bold !important;">Service/ Customer No.:</td>
                        <td><?php echo $result_data['customer_no'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1" style="font-weight: bold !important;">Meter Type:</td>
                        <td><?php echo $all_meter[$result_data['meter_type']];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Meter Accuracy Class:</td>
                        <td><?php echo $all_meter_accuracy[$result_data['meter_accuracy']];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1"><b>Type of Customer:</b></td>
                        <td><?php echo $result_data['customer_type'];?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Sanctioned Load:</td>
                        <td><?php if($result_data['sanctioned_load']!='') { echo $result_data['sanctioned_load'];?> - <?php echo $all_load[$result_data['is_snaction']]; }?> </td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Contract Demand:</td>
                        <td><?php if($result_data['contract_demand']!='') { echo $result_data['contract_demand'];?> - <?php echo $all_load[$result_data['is_contract']]; } ?></td>
                    </tr>
                    <tr>
                        <td class="td_survey_1">Billing Cycle:</td>
                        <td><?php echo $all_billing[$result_data['billing_cycle']];?></td>
                    </tr>
                
                <?php
                if(!empty($result_data['month_details']))
                {
                ?>
                <tr><td colspan="2" style="font-weight: bold;font-size:16px;">Electricity Bill Details:</td></tr>
                <tr><td colspan="2">
                    <table  border="1" style="border:1px solid #000000;" width="400px">
                        <tr>
                            <td style="background-color: #F2F2F2;font-weight: bold;">Month</td>
                            <td style="background-color: #F2F2F2;font-weight: bold;">Year</td>
                            <td style="background-color: #F2F2F2;font-weight: bold;">Unit</td>
                            <td style="background-color: #F2F2F2;font-weight: bold;">Amount</td>
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
                            <td style="background-color: #F2F2F2;"><?php echo $arr_all_month[$i]['month'];?></td>
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
                            <td style="background-color: #F2F2F2;"><?php echo $str_year;?></td>
                            <td style="background-color: #F2F2F2;"><?php echo $str_power_consume;?></td>
                            <td style="background-color: #F2F2F2;"><?php echo $str_bill_amount;?></td>
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
                            <td style="background-color: #F2F2F2;font-weight: bold;">Average</td>
                            <td style="background-color: #F2F2F2;font-weight: bold;"></td>
                            <td style="background-color: #F2F2F2;font-weight: bold;"><?php echo $avg_pow_con;?></td>
                            <td style="background-color: #F2F2F2;font-weight: bold;"><?php echo $avg_bill_amt;?></td>
                        </tr>
                    </table>
                </td></tr>
                <?php
                }
                ?>
                </table>        
            </div>
        </div>
    </body>
</html>