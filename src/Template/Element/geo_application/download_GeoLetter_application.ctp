<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RE Application</title>
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
                                        if(!empty($geo_application_data) && !empty($geo_application_data->approved_date))
                                        {
                                            echo date('d-M-Y',strtotime($geo_application_data->approved_date));
                                        }
                                        else
                                        {
                                            echo date('d-M-Y',strtotime($geo_application_data->approved_date));
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
                                        }?>, GEDA has verified the coordinates of 03 WTG locations as per the MNRE Guideline vide letter no. F. No. 66/183/2016-WE dated 22 October, 2016. Please find enclosed herewith the coordinate verification report as requested by you.
                            </div>
                            <div style="margin-top:5px">
                                 The verification of the coordinates of your proposed WTG locations are subject to the following terms and conditions:
                            </div>
                            <div style="margin-top:5px;text-align: justify;">
                                <p>1.  The validity of the coordinate verification report is for the period of three months from the date of verification.
                                 </p>
                                <p>2.   During the period of three months mentioned above, you shall submit the application for Development Permission (DP) to GEDA having legal possession of the land for the proposed locations. Failing with, the coordinates verification report of the locations for which DP is not applied will be stands cancelled.</p>
                                <p>3.   Though GEDA verify the coordinates of proposed locations according to MNRE guidelines, it shall be your responsibility to ensure to follow and consider the related MNRE Guidelines and its amendments from time to time till the completion of above project. If any dispute arises due to non- compliance of aforesaid guidelines, Government of Gujarat and / or GEDA shall be absolved from all the responsibilities / liabilities on any account, whatsoever.</p>
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
                                Asst. Project Executive
                            </div>
                           <div style="margin-top:5px">
                                Encl.: - as above
                            </div>
                        </td>
                    </tr>
                </table>
                
            </div>
        </div>
    </body>
</html>