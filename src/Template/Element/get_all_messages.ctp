<!DOCTYPE html>
<html lang="">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ApplyOnline Application</title>
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
            /*
            @page :first {
                margin-top: 100px;
            }
            */
            body {
                /* margin: 100px 20px 50px 20px; */
                margin: 20px;
            }
            .text_justify li{
                text-align:justify;
            }
            #headerA {
                position: fixed;
                left: 0px; right: 0px; top: 0px;
                text-align: center;
                background-color: white;
                height: 90px;
            }
            #headerB {
                position: absolute;
                left: -20px; right: -20px; top: -200px;
                text-align: center;
                background-color: white;
                height: 190px;
            }
            #footer {
                position: fixed;
                left: 0px; right: 0px; bottom: 0px;
                text-align: center;
                background-color: white;
                height: 40px;
                color:black;
                font-weight:bold;
            }
            .active_status {
                background-color: orange;
                color:white;
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
                <table width="100%" align="center">
                    <tr>
                        <td>
                            <table cellspacing="5" cellpadding="3">
                                <tr>
                                    <td id="header" class="td_bold">
                                         <center>Message History</center>
                                     </td>   
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                </table>
                 <?php if (!empty($ApplyonlineMessage)) { ?>
                    <?php foreach ($ApplyonlineMessage as $Message) { ?>
                        <div >
                            <table class="" border="1" style="border: 1px solid #000;">
                            <tr>
                                <td width="20%" class="td_simple" >Message</td>
                                <td class="td_simple"><?php echo $Message['message']?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="td_simple" >Message From</td>
                                <td class="td_simple"><?php echo $Message['comment_by']?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="td_simple" >Message Date</td>
                                <td class="td_simple"><?php echo $Message['created']?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="td_simple" >IP Address</td>
                                <td class="td_simple"><?php echo $Message['ip_address']?></td>
                            </tr>
                            </table>
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="td_simple">&nbsp;</td>   
                                </tr>
                                
                            </table>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div >
                        <table >
                        <tr>
                            <td class="td_bold">No Message !!!</td>
                        </tr>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>