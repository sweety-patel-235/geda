<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pageTitle; ?></title>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pageTitle?></title>
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
            .td_bold{ font-family: 'arial_bold'; font-size: 14px;}
            .td_italic{font-family: 'arial_italic'; font-size: 14px;}
            .td_simple{font-family: 'arial_simple'; font-size: 14px;}
            @page {
                margin: 10px;
            }
            body {
                margin: 20px;
            }
            .text_justify li {
                text-align:justify;
            }
            .border_class {
                border-style: solid; 
                border-width: 1px;
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
            <div id="content" class="mainbox">
                <table width="90%" align="center">
                    <tr>
                        <td colspan="2" align="center">
                            <div style="text-align: center;margin-top: 200px;">
                                <span class="td_bold" style="font-size: 24px;">MEMORANDUM OF UNDERSTANDING</span>
                            </div> 
                        </td>   
                    </tr>   
                    <tr>
                        <td colspan="2" class="td_simple">
                            <span>
                                This memorandum of understanding is made today on between M/s. <?php echo $INSTALLER_NAME ?> (address) <?php echo $INSTALLER_ADDRESS?> (the “Supplier”) and <?php echo $CUSTOMER_NAME?> (hereafter referred as "User Agency") for installation of grid connected Residential Rooftop Solar PV System (RRSS) under RRSS subsidy scheme 2018-2019 of MNRE & GEDA.
                            </span>
                        </td>   
                    </tr>
                    <tr>
                        <td colspan="2" class="td_simple">
                            <span>It has been agreed upon by and between the said parties as under:</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td_simple">
                            <ol class="text_justify">
                                <li>
                                    User Agency has registered the project under Gujarat Solar Power Policy – 2015 with registration number <?php echo $GEDA_REGISTRATION_NO;?> for installation of grid connected Residential Rooftop Solar PV System (RRSS) under MNRE/ GEDA's subsidy scheme 2018-19 through the above authorized vendor of GEDA.
                                </li>
                                <li>
                                    The Government of Gujarat Subsidy of Rs. 10,000/- per kW would be disbursed by GEDA to the Registered Supplier after successful installation and commissioning of the Rooftop Solar PV System by private Residential Consumers with maximum limit of Subsidy of Rs. 20,000/- per consumer.
                                </li>
                                <li>
                                    MNRE Subsidy of 30% of discovered cost of system per kW would be disbursed by GEDA to the Supplier after successful installation and commissioning of grid connected RRSS by private Residential Consumers as per detail below:<br />
                                    <table style="border:1px solid #000000;border-collapse: collapse;" cellpadding="2">
                                        <tr>
                                            <td align="center" class="td_bold border_class" width="10%" >Sr. No.</td>
                                            <td align="center" class="td_bold border_class" width="30%" >System Capacity Range</td>
                                            <td align="center" class="td_bold border_class" width="30%" >Cost in Rs. Per kW</td>
                                            <td align="center" class="td_bold border_class" width="30%" >Subsidy in Rs. Per kW</td>
                                        </tr>
                                        <tr>
                                            <td align="center" width="10%" class="border_class">1</td>
                                            <td align="center" width="30%" class="border_class">1 to 6 kW</td>
                                            <td align="center" width="30%" class="border_class">48,300/-</td>
                                            <td align="center" width="30%" class="border_class">14,490/-</td>
                                        </tr>
                                        <tr>
                                            <td align="center" width="10%" class="border_class">2</td>
                                            <td align="center" width="30%" class="border_class">Above 6 to10 kW</td>
                                            <td align="center" width="30%" class="border_class">48,000/-</td>
                                            <td align="center" width="30%" class="border_class">14,400/-</td>
                                        </tr>
                                        <tr>
                                            <td align="center" width="10%" class="border_class">3</td>
                                            <td align="center" width="30%" class="border_class">Above 10 to 50 kW</td>
                                            <td align="center" width="30%" class="border_class">44,000/-</td>
                                            <td align="center" width="30%" class="border_class">13,200/-</td>
                                        </tr>
                                        <tr>
                                            <td align="center" width="10%" class="border_class">4</td>
                                            <td align="center" width="30%" class="border_class">Above 50 kW</td>
                                            <td align="center" width="30%" class="border_class">41,000/-</td>
                                            <td align="center" width="30%" class="border_class">12,300/-</td>
                                        </tr>
                                    </table>
                                    <br />
                                </li>
                                <li>
                                    Grid connected RRSS installed under GEDA and MNRE subsidy scheme will not be transferred/ sell/ disposed without prior approval of GEDA for a period of five years.
                                </li>
                                <li>
                                    GEDA and MNRE will not be responsible or party for any dispute with the Supplier selected by the User Agency.
                                </li>
                            </ol>
                            <div style="page-break-before:always"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td_simple">
                            <div style="margin-top: 70px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td_simple">
                            <ol start="6">
                                <li>
                                    The User Agency shall follow the instructions given by the Supplier for proper function of the system and its upkeep; and shall inform the Supplier immediately for its non – functioning. The Supplier shall provide necessary service for the breakdown maintenance of the system for the <span class="td_bold">period of 5 years.</span>
                                </li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td_simple">
                            <span class="td_bold">This memorandum of understanding commencing from the date of signing of this Memorandum of Understanding will be valid for the period of five years.</span>
                            <br /><br />
                            In witness whereof these parties have set their hands in token of affirmation and acceptance of the terms and conditions herein above written and mentioned.
                            <br /><br />
                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="td_simple" valign="top">
                            Signature on behalf of User Agency:
                            <div style="margin-top:5px;margin-top:20px;">
                                Stamp
                            </div>
                            <br />
                            <div style="margin-top:5px;">
                                Date:
                            </div>
                        </td>
                        <td align="right" class="td_simple" valign="top">
                            On behalf of Supplier with rubber
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>