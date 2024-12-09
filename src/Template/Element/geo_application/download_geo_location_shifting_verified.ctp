<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Coordinate verification report</title>
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
            .td_bold{ font-family: 'arial_bold';font-size: 13px;}
            .td_italic{font-family: 'arial_italic';font-size: 12px;}
            .td_simple{font-family: 'arial_simple';font-size: 12px;}
            @page {
                size: A4 landscape;
            }
            body {
                /*margin: 20px;*/
            }
            table {
                /*width: 100%;*/
                border-collapse: collapse;
            }
            table {
                width: 100%;
            }

            td {
                border: 0.1pt solid black;
                padding-left: 2px;
            }
            /*th, td {
                padding: 2px;
            }*/
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
                        <td align="right" style="margin-right:10px;border:none !important; ">
                            <table width="100%">
                                <tr>
                                    <td align="left" valign="top" width="70%" style="border:none !important;">
                                                    
                                                        <span style="color:black;margin-bottom:0;font-size: 20px !important;" class="td_bold">GUJARAT ENERGY DEVELOPMENT AGENCY</span>
                                                        <p class="td_simple">4th Floor, Block No. 11-12, Udyog Bhawan, Gandhinagar,<br>
                                                        Ph: 079-23257251-54, GST. No. 24AAATG1858Q1ZA
                                                        </p>
                                    </td>   
                                    <td align="right" valign="top" width="30%"  style="float: right;border:none !important;">
                                                    <?php
                                                    $image_path = ROOT . DS ."webroot/pdf/images/geda.jpg";
                                                    $type = pathinfo($image_path, PATHINFO_EXTENSION);
                                                    $data = file_get_contents($image_path);
                                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                                    ?>
                                     <img src="<?php echo $base64;?>"   width="225px" height="80px">
                                    </td>
                                </tr>
                            </table>
                        </td>   
                    </tr>
                    <tr>
                        <td  style="border:none !important;">
                            <table width="100%">
                                <tr>
                                    <td align="left" class="td_italic"  style="border:none !important;">
                                       <p><span>Provisional Registration No.: <?php echo $applicationDetails->registration_no; ?></span></p> 
                                    </td>
                                    
                                </tr>
                            </table>
                        </td>            
                    </tr>
                    
                </table>
                <table class="td_simple" width="100%" style="border:1px solid #000000;border-collapse: collapse;margin-top:20px;">
                    
                    <tr>
                        <td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" colspan="13">WTG COORDINATE SHIFTING VERIFICATION REPORT</td>
                    </tr>
                    <tr>
                        <td style="width:10%;vertical-align: top;border-bottom: 1px solid #000000;text-align: left;padding-left:5px;" class="td_bold" colspan="8">Developer Name & Address :  <?php echo $developer_name ?><br><?php echo $developer_address ?></td>

                        <td style="vertical-align: top; border-bottom: 1px solid #000000;text-align: right;padding-right:5px;" class="td_bold" colspan="5"> Date : <?php echo date('d-M-Y H:i:s',strtotime($wtg_verified_date)); ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding-left:5px;" class="td_bold" colspan="13">Nos. of WTG :   <?php echo count($geo_application_data_array) ?></td>
                    </tr>
                    <tr>
                        <td style="width:2%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">Sr.</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">Location</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">Village</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">Taluka</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">District</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" colspan="3">UTM Coordinates</td>
                         <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" colspan="3">Shifted UTM Coordinates</td>
                        <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">Make of WTG, Capacity(Kw), Rotor Dia (M), HH </td>
                        <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold" rowspan="2">Remark</td>
                        
                    </tr>
                    <tr>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Zone</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Easting</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Northing</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Shifted Zone</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Shifted Easting</td>
                        <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_bold">Shifted Northing</td>
                    </tr>
                    <?php 
                    $counter = 1;
                    foreach ($geo_application_data_array as $key => $value) { 
                            // Key to find
                            $key = $value['application_geo_location']['geo_district'];

                            // Check if the key is present in the array
                            if (array_key_exists($key, $district)) {
                                // If the key exists, show its value
                                $district_name = $district[$key];
                                
                            }
                            $zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
                                // Key to check
                                $keyToCheck = $value['application_geo_location']['zone'];
                                if (array_key_exists($keyToCheck, $zonearray)) {
                                    // Display the value corresponding to the key
                                     $zone = $zonearray[$keyToCheck]; 
                                }

                                $keyToCheckSZ = $value['modified_zone'];
                                if (array_key_exists($keyToCheckSZ, $zonearray)) {
                                    // Display the value corresponding to the key
                                     $zoneSZ = $zonearray[$keyToCheckSZ]; 
                                }

                            $key = $value['application_geo_location']['geo_taluka'];

                                // Check if the key is present in the array
                                if (array_key_exists($key, $taluka)) {
                                    // If the key exists, show its value
                                    $taluka_name = $taluka[$key];
                                    
                                }
                            // Key to find
                            $key = $value['application_geo_location']['wtg_make'];
                           // echo"<pre>"; print_r($value['wtg_make']); die();
                                // Check if the key is present in the array
                                if (array_key_exists($key, $wtg_make)) {
                                    // If the key exists, show its value
                                    $wtg_make_name = $wtg_make[$key];
                                    
                                }
                            ?>
                        <tr>
                            <td style="width:2%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple" rowspan="2"><?php echo $counter ;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple"><?php echo $value['application_geo_location']['wtg_location'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple"><?php echo $value['application_geo_location']['geo_village'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple"><?php echo $taluka_name;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple"><?php echo $district_name;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $zone;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['application_geo_location']['x_cordinate'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['application_geo_location']['y_cordinate'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $zoneSZ;?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['modified_x_cordinate'];?></td>
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple"><?php echo $value['modified_y_cordinate'];?></td>
                            <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple"><?php echo $wtg_make_name;?>/ <?php echo $value['application_geo_location']['wtg_capacity'];?>/ <?php echo $value['application_geo_location']['wtg_rotor_dimension'];?>/ <?php echo $value['application_geo_location']['wtg_hub_height'];?></td>
                            <td style="width:10%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                <?php 
                                if(!empty($value['geo_application_clashed_data']['clashed_for'])){
                                    if($value['geo_application_clashed_data']['clashed_for'] == 1){
                                        echo 'Clashing';
                                    }else{
                                        echo 'Internal Clashing';
                                    }
                                }else{
                                    if($value['approved'] == 1){
                                        echo 'No Clashing';
                                        $value['comment'] ='NA';
                                    }else if($value['approved'] == 2){
                                        echo 'Rejected';
                                        $value['comment'] =$value['geo_shifting_application_reject_log']['reject_reason'];
                                    }
                                }


                                ?>
                                 
                             </td>
                            
                        </tr>
                        <tr>
                           <!--  <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple" >Comment :</td> -->
                            <td style="width:5%; vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: left;" class="td_simple" colspan="12">Comment :- <?php echo !empty($value['comment'])?$value['comment']:'NA';?></td>
                        </tr>
                   <?php $counter++; }  ?>
                </table>
                <table class="td_simple" style="text-align: left;">
                    <tr>
                        <td colspan="10" style="border:none !important;">NOTE : WTG LOCATIONS  HAVING REMARKS OF "NO CLASHING" IS/ARE MORE THAN 500 METER DISTANCE FROM DWELLING TO MITIGATE THE NOISE AS PER MNRE GUIDE LINES OF OCTOBER,2016.</td>
                    </tr>
                </table>
                <div class="page_break"></div>
                <table width="100%">
                    <tr>
                        <td class="td_simple" style="border:none !important;">
                            <div style="margin-top:5px">
                                The verification of the Co-ordinates of your proposed WTG locations are subject to the following terms and conditions:
                            </div>
                            <div style="margin-top:5px;text-align: justify;">
                                <p>1. The validity of the coordinate verification report is for the period of three months from the date of verification.
                                </p>
                                <p>2. During the period of three months mentioned above, you shall submit the application for Development Permission (DP) to GEDA having legal possession of the land for the proposed locations. Failing which, the Co-ordinates verification report of the locations for which DP is not applied will be stands cancelled.</p>
                                <p>3. Though GEDA verify the Co-ordinates of proposed locations according to MNRE guidelines, it shall be your
                                responsibility to ensure to follow and consider the related MNRE Guidelines and its amendments from time to time till the completion of above project. If any dispute arises due to non- compliance of aforesaid guidelines, Government of Gujarat and / or GEDA shall be absolved from all the responsibilities / liabilities on any account, whatsoever.</p>
                            </div>         
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="td_simple" style="border:none !important;">
                            <div style="margin-top:5px">
                                <br> <br>
                            </div>
                           <div style="margin-top:5px">
                                Asst. Project Executive<br>
                                <?php //echo $members->address1 ?>
                                <!-- (Porbandar/Gandhidham) -->
                            </div>
                        </td>
                        
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr> 
                    <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr> 
                    <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                     <tr>
                        <td align="left" class="td_simple" style="border:none !important;">&nbsp;</td>
                    </tr>
                    <tr >
                        <td align="center" class="td_bold" style="border:none !important;">
                            This is a computer generated letter and doesn't require signature.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>