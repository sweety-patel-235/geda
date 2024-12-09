<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Geo Location List</title>
        <!-- Style CSS -->
        <link type="text/css" rel="stylesheet" media="all" href="css/style.css"/>
        <style>
            @font-face {
            font-family:'arial_italic';
            src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALI.TTF');
            }
            @font-face {
            font-family: 'arial_bold';
            src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALBD.TTF');
            }
            @font-face {
            font-family: 'arial_simple';
            src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/arial.ttf');
            }
            .td_bold{ font-family: 'arial_bold';font-size: 14px;}
            .td_italic{font-family: 'arial_italic';font-size: 14px;}
            .td_simple{font-family: 'arial_simple';font-size: 14px;}
            @page {
                margin: 10px;
            }
            body {
                margin: 20px;
            }
            .text_justify li{
                text-align:justify;
            } 
            .page_break { page-break-before: always; }

            </style>
    </head>
    <body id="pdf-header">
        <script type="text/php">
            if (isset($pdf)) {
                $curdate    = date('d-M-Y');
                $x          = 35;
                $y          = 810;
                $text       = "Date: $curdate";
                $font       = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold");
                $size       = 10;
                $color      = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle      = 0.0;  //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

                $x          = 500;
                $y          = 810;
                $text       = "Page {PAGE_NUM} of {PAGE_COUNT}";
                $font       = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold");
                $size       = 10;
                $color      = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle      = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
        <div class="container">
            <!-- HEADER MARGIN FIRST PAGE -->
            <!--div id="headerA"><h1></h1></div-->
            <!-- HEADER MARGIN -->
            <!-- HEADER MARGIN ALL PAGES-->
            <!--div id="headerB"><h1></h1></div-->
            <!-- HEADER MARGIN ALL PAGES -->
            <div id="content" class="mainbox">
                <table width="100%">
                    <tr>
                        <td align="right" style="margin-right:10px;">
                            <?php
                            $image_path = ROOT . DS ."webroot/pdf/images/geda.jpg";
                            $type = pathinfo($image_path, PATHINFO_EXTENSION);
                            $data = file_get_contents($image_path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                        <img src="<?php echo $base64;?>"  width="225px" height="80px">
                        <div align="center">
                            <p style="color:black;margin-bottom:0;font-size: 22px;" class="td_bold">GUJARAT ENERGY DEVELOPMENT AGENCY</p>
                            <p class="td_simple">4th Floor, Block No. 11-12, Udyog Bhawan, Gandhinagar,<br>
                            Ph: 079-23257251-54, GST. No. 24AAATG1858Q1ZA
                            </p>
                        </div> 
                        </td>   
                    </tr>   
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td align="left" class="td_italic">
                                       <p><span>Reference: <?php echo $applicationDetails->registration_no; ?></span></p> 
                                    </td>
                                    <td align="right" class="td_simple"  >
                                        <p><span style="margin-right:15px"> Date: <?php 
                                        if(!empty($applicationDetails) && !empty($applicationDetails->created))
                                        {
                                            echo date('d-M-Y',strtotime($applicationDetails->created));
                                        }
                                        else
                                        {
                                            echo date('d-M-Y',strtotime($applicationDetails->created));
                                        }
                                        ?>
                                    </span></p>
                                    </td>
                                </tr>
                            </table>
                        </td>            
                    </tr>
                    <tr>
                        <td align="left" class="td_simple">
                            To:
                        </td>
                    </tr>
                     <tr>
                        <td class="td_simple">
                            <?php 
                            //print_R($applicationDetails);
                            echo $applicationDetails->name_of_applicant;?> <?php //echo $ApplyOnlines->name_of_consumer_applicant;?><br>
                            <?php echo $applicationDetails->address1.', '.$applicationDetails->taluka.', '.$applicationDetails->district_master['name'].', '.$applicationDetails->state.' - '.$applicationDetails->pincode;?> 
                            <br>
                            <?php //echo $ApplyOnlines->address2;?>
                            <br>
                            <?php //echo $ApplyOnlines->city;?>
                        </td>         
                    </tr>
                    <tr>
                        <td class="td_simple"> 
                            <p class="td_bold">Sub: Coordinates verification of Wind Turbine Generator’s (WTG) locations.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_simple"> 
                            <p class="td_bold">Ref: Your request for verification of WTG coordinates vide Letter dated <?php if(!empty($geo_application_data) && !empty($geo_application_data->payment_date))
                                        {
                                            echo date('d-M-Y',strtotime($geo_application_data->payment_date));
                                        }
                                        else
                                        {
                                            echo date('d-M-Y',strtotime($geo_application_data->payment_date));
                                        }?></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_simple">
                            <div style="margin-top:5px">
                                 Dear Sir,
                            </div>
                            <div style="margin-top:5px">
                                 With reference to your request dated <?php if(!empty($geo_application_data) && !empty($geo_application_data->payment_date))
                                        {
                                            echo date('d-M-Y',strtotime($geo_application_data->payment_date));
                                        }
                                        else
                                        {
                                            echo date('d-M-Y',strtotime($geo_application_data->payment_date));
                                        }?>, GEDA has verified the coordinates of 03 WTG locations as per the MNRE Guideline vide letter no. F. No. 66/183/2016-WE dated 22 October, 2016. 
                            </div>
                            
                                 
                        </td>
                       
                    </tr>
                    <tr>
                        <td class="td_simple">
                            <div style="margin-top:5px">
                                     Please find here with the coordinate verification report as requested by you and as per the details mentioned below:
                            </div>
                        </td>
                    </tr>
                 </table>
                 <div class="page_break"></div>
                 <table class="td_simple" width="100%" style="text-align: center;border:1px solid #000000;border-collapse: collapse;margin-top:20px;">
                    
                    <tr>
                        <td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" colspan="11">Coordinate Verification for 9 locations of M/s Suzlon Global Services Limited, JAMNAGAR <br>as per the details available at GEDA Porbandar Office</td>
                    </tr>
                    <tr>
                        <td style="width:2%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">Sr.</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">Location</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">Village</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">Taluka</td>
                        <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">District</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" colspan="2">Applied Coordinates</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" colspan="2">Verified Applied Coordinates</td>
                        <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">Make of WTGICapacity(Kw)I Rotor Dia (M) HH 140 </td>
                        <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2">Location Status</td>
                        
                    </tr>
                    <tr>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">X - Coordinate</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">Y - Coordinate</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">X - Coordinate</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">Y - Coordinate</td>
                    </tr>
                    <?php 
                    $counter = 1;
                    foreach ($geo_application_data_array as $key => $value) { 
                            // Key to find
                            $key = $value['geo_district'];

                            // Check if the key is present in the array
                            if (array_key_exists($key, $district)) {
                                // If the key exists, show its value
                                $district_name = $district[$key];
                                
                            } ?>
                        <tr>
                            <td style="width:2%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $counter ;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['wtg_location'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['geo_village'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['geo_taluka'];?></td>
                            <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $district_name;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['x_cordinate'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['y_cordinate'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['x_cordinate'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['y_cordinate'];?></td>
                            <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['wtg_model'];?></td>
                            <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php if($value['approved'] == 1){echo 'Approved';}
                            elseif ($value['approved'] == 2){echo 'Reject';} elseif ($value['approved'] == 5){ echo 'Internal Clashed';} elseif ($value['approved'] == 3){ echo 'Clashed';} else{
                                echo ' ';
                            }
                             ?></td>
                            }
                        </tr>
                   <?php $counter++; }  ?>
                </table>
                <table class="td_simple" style="text-align: center;">
                    <tr>
                        <td colspan="10">Note: ABOVE LOCATIONS ARE MORE THAN 500 METER DISTANCE FROM DWELUNG UNIT TO MITIGATE THE NOISE AS PER MNRE GUIDELINES OF OCTOBER,2016.</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td class="td_simple">
                            
                            <div style="margin-top:5px">
                                 The verification of the coordinates of your proposed WTG locations are subject to the following terms and conditions:
                            </div>
                            <div style="margin-top:5px;text-align: justify;">
                                <p><span style="margin-left:15px">1.  The validity of the coordinate verification report is for the period of three months from the date of verification.</span>
                                 </p>
                                <p><span style="margin-left:15px">2.   During the period of three months mentioned above, you shall submit the application for Development Permission (DP) to GEDA having legal possession of the land for the proposed locations. Failing with, the coordinates verification report of the locations for which DP is not applied will be stands cancelled.</span></p>
                                <p><span style="margin-left:15px">3.   Though GEDA verify the coordinates of proposed locations according to MNRE guidelines, it shall be your responsibility to ensure to follow and consider the related MNRE Guidelines and its amendments from time to time till the completion of above project. If any dispute arises due to non- compliance of aforesaid guidelines, Government of Gujarat and / or GEDA shall be absolved from all the responsibilities / liabilities on any account, whatsoever.</span></p>
                            </div>         
                        </td>
                       
                    </tr>
                    <tr>
                        <td class="td_simple">
                                <div style="margin-top:5px">
                                    Thanking you,
                                </div>
                                <div style="margin-top:5px">
                                    Yours faithfully,
                                </div>
                        </td>       
                    </tr>
                    <tr>
                        <td class="td_simple">
                            <div style="margin-top:5px">
                                <br> <br>
                            </div>
                           <div style="margin-top:5px">
                                Asst. Project Executive
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>