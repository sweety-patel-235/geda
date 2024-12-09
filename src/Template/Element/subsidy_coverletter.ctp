<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ApplyOnline Subsidy Claim Coverletter</title>
        <!-- Style CSS -->
        <link type="text/css" rel="stylesheet" media="all" href="css/style.css"/>
        <style>
            @font-face {
            font-family: 'arial_bold';
            src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALBD.TTF');
            }
             @font-face {
            font-family:'arial_italic';
            src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/ARIALI.TTF');
            }
            @font-face {
            font-family: 'arial_simple';
            src: url('<?php echo ROOT . DS ;?>vendor/dompdf/lib/fonts/arial.ttf');
            }
            .td_bold{ font-family: 'arial_bold';}
            .td_italic{font-family: 'arial_italic';}
            .td_simple{font-family: 'arial_simple';}
            @page {
                margin: 10px;
            }
            body {
                margin: 20px;
            }
            .text_justify li{
                text-align:justify;
            } 
            </style>
    </head>
    <body id="pdf-header">
        <script type="text/php">
            if (isset($pdf)) {
                $x          = 35;
                $y          = 810;
                $text       = "";
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
                <table width="95%">
                    <tr><td height="55">&nbsp;</td></tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="td_simple" align="right" style="margin-right:10px;">
                                        Date: <?php echo $REQUEST_GENERATION_DATE;?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td align="left">
                            <table><tr><td class="td_simple"><?php echo $COVER_LETTER_ADDRESS_DETAILS;?></td></tr></table>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td align="left">
                            <table>
                            <tr>
                                <td class="td_bold">
                                    SUB: Subsidy Claim [Request No. <?php echo $REQUEST_NO;?>] for the Rooftop Solar PV System from <?php echo $INSTALLER_NAME;?>
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td align="left">
                            <table class="td_simple">
                            <tr>
                                <td class="td_simple text_justify">
                                    Dear Sir/ Madam,<br /><br />
                                    We, <?php echo $INSTALLER_NAME;?> would like to inform you that we have installed the Rooftop Solar PV systems as per the terms and conditions of the Gujarat Energy Development Agency (GEDA) and Gujarat Rooftop Solar Policy – 2015 and we are pleased to submit the subsidy claim for the installed solar PV systems.
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td class="td_simple text_justify">
                                    The cumulative details of the projects which has been installed by the <?php echo $INSTALLER_NAME;?> and submitted for the subsidy claim is given below:
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td>
                            <table style="border:1px solid #000000;" cellpadding="5">
                            <tr>
                                <td style="width:50%; vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">No. of Projects
                                </td>
                                <td style="width:50%; vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $No_of_Projects;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">Total Capacity (kWp)
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $Total_Capacity;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">No. of Residential Projects
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $No_of_Residential;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">Total Capacity of Residential (kWp)
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $Total_Capacity_Residential;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">No. of Social Sector Projects
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $No_of_Social_Sector;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">Total Capacity of Social Sector (kWp)
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $Total_Capacity_Social_Sector;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">Total GEDA Subsidy Amount
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    Rs. <?php echo $Total_State_Amount;?> /-
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">Total MNRE Subsidy Amount
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    Rs. <?php echo $Total_MNRE_Amount;?> /-
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">Total of Subsidy Amount
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    Rs. <?php echo $Total_Subsidy_Amount;?> /-
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">No of GEDA Inspection Report attached
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $Total_Geda_Inspection_Report;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: right;" class="td_simple">GEDA Registration No.
                                </td>
                                <td style="vertical-align: middle;border-bottom: 1px solid #000000;text-align: center;" class="td_simple">
                                    <?php echo $GEDA_APPLICATION_NOS;?>
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td align="left">
                            <table>
                            <tr>
                                <td class="td_simple text_justify">
                                    The detailed documents for the above mentioned projects are enclosed herein. Thank you.
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td align="right" class="td_simple">Sincerely Yours</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td align="right" class="td_simple">(Authorized Signatory)</td></tr>
                            </table>
                        </td>
                    </tr>         
                </table>
            </div>
        </div>
    </body>
</html>