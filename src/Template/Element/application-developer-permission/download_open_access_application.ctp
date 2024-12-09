<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Annexure - 2</title>
    <!-- Style CSS -->
    <link type="text/css" rel="stylesheet" media="all" href="css/style.css" />
    <style>
        @page {

            margin-bottom: 10px;
            /* create space for footer */
        }
        .page-break {
            page-break-before: always;
        }

        footer {
            position: fixed;
            left: 40%;
            transform: translate(-40%);
            height: 50px;
            bottom: -10px;
        }

        @font-face {
            font-family: 'arial_italic';
            src: url('<?php echo ROOT . DS; ?>vendor/dompdf/lib/fonts/ARIALI.TTF');
        }

        @font-face {
            font-family: 'arial_bold';
            src: url('<?php echo ROOT . DS; ?>vendor/dompdf/lib/fonts/ARIALBD.TTF');
        }

        @font-face {
            font-family: 'arial_simple';
            src: url('<?php echo ROOT . DS; ?>vendor/dompdf/lib/fonts/arial.ttf');
        }

        .td_bold {
            font-family: 'arial_bold';
        }

        .td_italic {
            font-family: 'arial_italic';
        }

        .td_simple {
            font-family: 'arial_simple';
        }

        body {
            padding: 0;
            margin: 0;
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
        }

        table tr td {
            border: 1px solid black;

        }

        .table_1 tbody tr td {
            border: none;
        }
    </style>
</head>

<body>
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
        <footer>
            <p style="font-size:14px;font-weight:bold">Gujarat Energy Developement Agency, Gandhinagar</p>
        </footer>
        <div id="content" class="mainbox" style="margin-top: 20px;">
           
            <table width="100%" class="td_bold table_1" style=" margin: 0; padding: 0; text-align: center;">
                <tr>
                    <td width="85%" style="padding-left: 100px;">
                        <span style="color:black; padding: 0; margin:0; font-size: 20px;">Gujarat Energy Development Agency</span><br>
                        <span style="font-size: 11px;">Climate Change Department, Government of Gujarat<br>4th Floor, Block No, 11/12, Udhyog Bhavan, Sector – 11, Gandhinagar – 382 017<br>Phone: (079) 23257251-53 Fax: 23247097, 57255</span>
                    </td>
                    <td valign="top" width="15%">

                        <?php
                        $logo_image     = '';
                        $path           = 'img/2_4.png';
                        if (!empty($path) && file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            $logo_image = "<img src=\"" . $base64 . "\" height=\"100px\" />";
                        }
                        ?>
                        <?php echo $logo_image; ?>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 16px; text-align:center;">Registration Form of Open Access Solar Power Projects</td>
                </tr>
                <tr> 
                    <td colspan="2" style="font-size: 17px; padding: 0; margin: 0;">Capacity of the Project :
                    <?php echo isset($appData->pv_capacity_ac) && !empty($appData->pv_capacity_ac)?$appData->pv_capacity_ac:0; ?> MW (AC)/ Maximum DC</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; padding: 0; margin: 0;">Capacity <?php echo isset($appData->pv_capacity_dc) && !empty($appData->pv_capacity_dc)?$appData->pv_capacity_dc:0; ?> MW (DC)</td>

                </tr>
                <tr>
                    <td style="text-align: left; padding-bottom: 10px; padding-left: 10pxl;">A. General Profile of Applicant:</td>
                </tr>
            </table>
            <table class="td_simple" width="100%" style="text-align: left;">
                <tr>
                    <td width="35%" style="border-right: none;">Provisional Registration no.</td>
                    <td width="5%" style="border-right: none; border-left: none;">:</td>
                    <td width="60%" style="border-left: none;"><?php echo isset($ApplicationData['applications']['registration_no']) ? $ApplicationData['applications']['registration_no'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border-right: none;">Name of the Applicant/ Owner of project</td>
                    <td width="5%" style="border-right: none; border-left: none;">:</td>
                    <td style="border-left: none;"><?php echo isset($ApplicationData['name_of_applicant']) ? $ApplicationData['name_of_applicant'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Type of the Applicant </td>
                    <td width="5%" style="border: none;">:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['type_of_applicant']) ? $ApplicationData['type_of_applicant'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-top: none; padding: 0; margin: 0; padding-left: 5px;">(Individual, Proprietary firm, Partnership, Public Ltd., Pvt. Ltd., Group of Company etc.)<br>Enclose self-certified copy of relevant certificate / MOA</td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Address:&nbsp;&nbsp;&nbsp;Street/House no </td>
                    <td width="5%" style="border: none;">:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">
                        <?php
                        if (isset($ApplicationData['address']) && isset($ApplicationData['address1'])) {
                            $street = $ApplicationData['address'] . ',' . $ApplicationData['address1'];
                        } else {
                            $street = "";
                        }
                        echo $street;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">City / Village </td>
                    <td width="5%" style="border: none;">:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 80px;"><?php echo isset($ApplicationData['city']) ? $ApplicationData['city'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">Taluka </td>
                    <td width="5%" style="border: none;">:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 80px;"><?php echo isset($ApplicationData['taluka']) ? $ApplicationData['taluka'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">District </td>
                    <td width="5%" style="border: none;">:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 80px;"><?php echo isset($ApplicationData['district']) ? $ApplicationData['district_master']['name'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">Pin code </td>
                    <td width="5%" style="border: none;">:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 80px;"> <?php echo isset($ApplicationData['pincode']) ? $ApplicationData['pincode'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; border-left: 1px solid black; border-top: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Telephone Nos.: + 91<?php echo isset($ApplicationData['contact']) ? $ApplicationData['contact'] : ""; ?></td>
                    <td style="border: none; border-right: 1px solid black; border-top: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">Fax Nos.: + 91<?php echo isset($ApplicationData['mobile']) ? $ApplicationData['mobile'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 20px;">Web site : <?php echo ""; ?></td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 80px;">E-mail : <?php echo isset($ApplicationData['email']) ? $ApplicationData['email'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; border-top: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Name of the Chairman/Managing </td>
                    <td width="5%" style="border: none; border-top: 1px solid black;">:</td>
                    <td style="border: none; border-right: 1px solid black; border-top: 1px solid black; padding: 0; margin: 0;">Mr.<?php echo isset($ApplicationData['name_director']) ? $ApplicationData['name_director'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none; border-left: 1px solid black; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 15px;">Director/CEO of company </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: none; padding: 0; margin: 0; padding-left: 5px;">Name and Designation of the authorized signatory of the applicant:-</td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Mr.<?php echo isset($ApplicationData['name_authority']) ? $ApplicationData['name_authority'] : ""; ?></td>
                    <td width="5%" style="border: none;"></td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Designation : <?php echo isset($ApplicationData['type_authority']) ? $designation[$ApplicationData['type_authority']] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black;  padding: 0; margin: 0; padding-left: 5px;">Mobile no.<?php echo isset($ApplicationData['authority_mobile']) ? $ApplicationData['authority_mobile'] : ""; ?></td>
                    <td width="5%" style="border: none;"></td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">E-mail address : <?php echo isset($ApplicationData['authority_mobile']) ? $ApplicationData['authority_mobile'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none; border-left: 1px solid black; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px; font-size: 10px;">(enclose self-certified copy of boards resolution authorizing person for signing all the documents related to proposed project)</td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Business Profile of the applicant :</td>
                    <td style="border: none; border-right: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 30px;">(i) Nature & activities of the Business :</td>
                    <td style="border: none; border-right: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; border-left: 1px solid black; border-bottom: 1px solid black; padding-bottom: 10px; padding-left: 30px;">(ii) Applicant GST No. (if applicable) :</td>
                    <td style="border: none; border-right: 1px solid black; border-bottom: 1px solid black;"><?php echo isset($ApplicationData['GST']) ? $ApplicationData['GST'] : ""; ?></td>
                </tr>
            </table>
            <table width="100%" class="td_bold table_1" style="border: none; padding: 8px 0px; text-align: left;">
                <tr>
                    <td>B. Technical and Financial details of the Proposed Project:</td>
                </tr>
            </table>
            <table class="td_simple" width="100%" style="text-align: left;">
                <tr>
                    <td style="border-right: none; padding-left: 5px;">Type of Power Project</td>
                    <td width="5%" style="border-right: none; border-left: none;">:</td>
                    <td style="border-left: none; padding: 0; margin: 0; padding-left: 5px;">Solar Photovoltaic</td>
                </tr>
                <tr>
                    <td style="border-right: none; padding-left: 5px;">Type of SPP</td>
                    <td width="5%" style="border-right: none; border-left: none;">:</td>
                    <td style="border-left: none; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['type_of_spp']) ? $typeOfSPP[$ApplicationData['type_of_spp']] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border-right: none; padding-left: 5px;">Type of mounting System used</td>
                    <td width="5%" style="border-right: none; border-left: none;">:</td>
                    <td style="border-left: none; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['type_of_mounting_system']) ? $typeOfMountingSystem[$ApplicationData['type_of_mounting_system']] : ""; ?></td>
                </tr>

                <tr>
                    <td style="border-right: none; padding-left: 5px;">Details of Solar System</td>
                    <td width="5%" style="border-right: none; border-left: none;">:</td>
                    <td style="border-left: none; padding: 0; margin: 0; padding-left: 5px;"></td>
                </tr>



            </table>

            <div class="page-break"></div>
            <table class="td_simple" width="100%" style="text-align: left; margin-top: 20px; font-size: 13.5px">
                <tr>
                    <td colspan="6" style="text-align: center;font-weight:bold">Modules Details</td>
                </tr>
                <?php
                if (isset($moduleAdditionalData)) {
                    echo '<tr style="text-align: center;font-weight:bold">';
                    echo '<td>Make of SPV Modules</td>';
                    echo '<td>Nos. of Modules</td>';
                    echo '<td>Capacity of each SPV Module(in Wp)</td>';
                    echo '<td>Total SPV Modules Capacity (in MW)</td>';
                    echo '<td>Type of SPV Technologies</td>';
                    echo '<td>Type of Solar Panel</td>';
                    echo '</tr>';
                    foreach ($moduleAdditionalData as $k => $v) {
                        echo '<tr style="text-align: center;">';
                        echo '<td>' . $v['mod_inv_make'] . '</td>';
                        echo '<td>' . $v['nos_mod_inv'] . '</td>';
                        echo '<td>' . $v['mod_inv_capacity'] . '</td>';
                        echo '<td>' . $v['mod_inv_total_capacity'] . '</td>';
                        echo '<td>' . $typeOfspv[$v['type_of_spv_technologies']] . '</td>';
                        echo '<td>' . $typeOfSolarPanel[$v['type_of_solar_panel']] . '</td>';
                        echo '</tr>';
                    }

                    echo '<tr style="text-align: center;font-weight:bold">';
                    $noOfModules = isset($totalModulenos) ? $totalModulenos["nos_mod_inv"] : "";
                    $capOfModules = isset($totalModulenos) ? $totalModulenos['mod_inv_total_capacity'] : "";
                    echo '<td colspan="3">Total SPV Module (Nos.) : ' . $noOfModules . '</td>';
                    echo '<td colspan="3">Total SPV Module capacity (MW) : ' . $capOfModules . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
           
            <table class="td_simple" width="100%" style="text-align: left; margin-top: 20px; font-size: 13.5px">
                <tr>
                    <td colspan="5" style="text-align: center;font-weight:bold">Inverter Details</td>
                </tr>
                <?php
                if (isset($inverteAdditionalData)) {
                    echo '<tr style="text-align: center;font-weight:bold">';
                    echo '<td>Make of Inverter</td>';
                    echo '<td>Nos. of Inverter</td>';
                    echo '<td>Capacity of each Inverter (in kW)</td>';
                    echo '<td>Total Inverter Capacity (in MW)</td>';
                    echo '<td>Type of Inverter Used</td>';
                    echo '</tr>';

                    foreach ($inverteAdditionalData as $ik => $iv) {
                        echo '<tr style="text-align: center;">';
                        echo '<td>' . $iv['mod_inv_make'] . '</td>';
                        echo '<td>' . $iv['nos_mod_inv'] . '</td>';
                        echo '<td>' . $iv['mod_inv_capacity'] . '</td>';
                        echo '<td>' . $iv['mod_inv_total_capacity'] . '</td>';
                        echo '<td>' . $typeOfInverterUsed[$iv['type_of_inverter_used']] . '</td>';
                        echo '</tr>';
                    }

                    echo '<tr style="text-align: center;font-weight:bold">';
                    $noOfInv = isset($totalInverternos) ? $totalInverternos["nos_mod_inv"] : "";
                    $capOfInv = isset($totalInverternos) ? $totalInverternos['mod_inv_total_capacity'] : "";
                    echo '<td colspan="3">Total SPV Module (Nos.) : ' . $noOfInv . '</td>';
                    echo '<td colspan="2">Total SPV Module capacity (MW) : ' . $capOfInv . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <!-- Page-2 -->
            <table class="td_simple" width="100%" style="text-align: left; margin-top: 40px; font-size: 13.5px">
                <tr>
                    <td style="border-right: none; padding-left: 5px;">Type of Consumer:</td>
                    <td style="border-left: none;"><?php echo isset($ApplicationData['type_of_consumer']) ? $typeOfConsumer[$ApplicationData['type_of_consumer']] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border-right: none; padding-left: 5px;">Type of MSME Manufacturing Enterprise:</td>
                    <td style="border-left: none;"><?php echo isset($ApplicationData['type_of_msme']) ? $typeOfMsme[$ApplicationData['type_of_msme']] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; border-top: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">End use of electricity:</td>
                    <td style="border: none; border-right: 1px solid black; border-top: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['end_use_of_electricity']) ? $endUseOfElectricity[$ApplicationData['end_use_of_electricity']] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">Party with REC Mechanism</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; border-left: 1px solid black; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px; font-size: 10px;">Note:
                        <span>If end use of power is for “Sale to DISCOM”, please attach the LOI/ PPA with GUVNL/ DISCOM to purchase power from proposed project</span><br>
                        <span style="padding-left: 26px;">Please enclose the “No Due Certificate” for the project site from concern DISCOM in whose jurisdiction the Solar plant is to be installed</span><br>
                        <span style="padding-left: 26px;">Please enclose the “No Due Certificate” for the project site and developer from concern DISCOM in whose jurisdiction the Solar plant is</span><br>
                        <span style="padding-left: 43px;">to be installed</span><br>
                        <span class="td_bold" style="padding-left: 26px;">Proof regarding ownership of the project</span><br>
                        <span style="padding-left: 26px;">A. CA certificate regarding the ownership of the proposed Solar Power Project.</span><br>
                        <span style="padding-left: 26px;">B. Share Subscription & Share Holding Agreement between RE Generator and Consumer Holder along with the CA certificate certifying</span><br>
                        <span style="padding-left: 52px;">the equity holding of the proposed Solar Power Project for captive use as per the definition no. 6 of clause no. 5 of Gujarat Renewable</span><br>
                        <span style="padding-left: 52px;">Energy Policy -2023</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: none; padding: 0; margin: 0; padding-left: 5px;" VALIGN=TOP>Sanctioned load/ Contract Demand:<?php echo isset($ApplicationData['sanctioned_load']) ? $ApplicationData['sanctioned_load'] : ""; ?><span style="padding-left: 15px;">Consumer No.:<?php echo isset($ApplicationData['consumer_no']) ? $ApplicationData['consumer_no'] : ""; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none; padding: 0; margin: 0; padding-left: 5px; font-size: 10px;">(enclose copy of latest electricity bill)</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: none; padding: 0; margin: 0; padding-left: 5px;" VALIGN=TOP>Capacity of Existing Solar Power Plant (MW) if installed before this application:<?php echo isset($ApplicationData['existing_solar_plan']) ? $ApplicationData['existing_solar_plan'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: none; padding: 0; margin: 0; padding-left: 5px; font-size: 10px;">(please enclose the commissioning certificate copy of the existing plant)</td>
                </tr>
                <tr>
                    
                    <td colspan="2" style="padding-left: 5px;">Name of concerned DISCOM, within whose jurisdiction the Solar plant is to be installed:<?php echo isset($ApplicationData['name_of_discome_plant_installed']) ? $discom[$ApplicationData['name_of_discome_plant_installed']] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 5px;">Name of concerned DISCOM, where power to be wheeled:<?php echo isset($ApplicationData['name_of_discome_power_wheeled']) ? $discom[$ApplicationData['name_of_discome_power_wheeled']] : ""; ?> </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 5px;">Proposed GETCO Substation name & Voltage level:<?php echo isset($ApplicationData['getco_substation_name']) ? $ApplicationData['getco_substation_name'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 5px;">Expected Annual output of energy from the proposed project in kWh:<?php echo isset($ApplicationData['expected_annual_output']) ? $ApplicationData['expected_annual_output'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 5px;">Nearest DISCOM/ GETCO’s substation for integration of the generation of the proposed project:</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 5px;">Proposed date of commissioning:<?php echo isset($ApplicationData['proposed_date_of_commm']) ? date("d-m-Y", strtotime($ApplicationData['proposed_date_of_commm'])) : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 5px;">Approximate Project Cost:<?php echo isset($ApplicationData['app_project_cost']) ? $ApplicationData['app_project_cost'] : ""; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: none; padding: 0; margin: 0; padding-left: 5px;">Name of EPC contactor/ Developer:<?php echo isset($ApplicationData['epc_constractor_nm']) ? $ApplicationData['epc_constractor_nm'] : ""; ?> </td>
                </tr>
                <tr>
                    <td width="45%" style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 22px;">Address:</td>
                    <td width="55%" style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['epc_constractor_add']) ? $ApplicationData['epc_constractor_add'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 22px;">Contact person name:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['epc_constractor_con_per']) ? $ApplicationData['epc_constractor_con_per'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 22px;">E-mail address:</td>
                    <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['epc_constractor_email']) ? $ApplicationData['epc_constractor_email'] : ""; ?></td>
                </tr>
                <tr>
                    <td style="border: none; border-left: 1px solid black; border-bottom: 1px solid black; padding: 0; margin: 0; padding-left: 22px;">Mobile no:</td>
                    <td style="border: none; border-right: 1px solid black; border-bottom: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['epc_constractor_mobile']) ? $ApplicationData['epc_constractor_mobile'] : ""; ?></td>
                </tr>
            </table>
            <div class="page-break"></div>
            <?php

            if ((isset($ApplicationData['end_use_of_electricity']) &&  ($ApplicationData['end_use_of_electricity'] == 2 || $ApplicationData['end_use_of_electricity'] == 3)) && ($ApplicationData['captive'] == 1 || $ApplicationData['third_party'] == 1)) { ?>

                <table width="100%" class="td_bold table_1" style="border: none; padding: 7px 0px; text-align: left;">
                    <tr>
                        <td>C. Project for RPO Compliance: <?php echo $ApplicationData['captive'] == 1 ? "Captive" : "Third Party Sale" ?> </td>
                    </tr>
                </table>
                <table class="td_simple" width="100%" style="text-align: left; font-size: 13px">
                    <tr>
                        <td colspan="2" style=" padding-left: 5px;">Whether beneficiary is an Obligated Entity covered under RPO obligation:
                            <?php echo isset($ApplicationData['beneficiary_obligated_entity']) && $ApplicationData['beneficiary_obligated_entity'] == 1 ? 'YES' : 'NO'; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style=" padding-left: 5px;">Documents of beneficiary in support of applicant being obligated entity for RPO compliance:<?php echo isset($ApplicationData['doc_of_beneficiary']) && $ApplicationData['doc_of_beneficiary'] != "" ? 'YES' : 'NO'; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style=" padding-left: 5px;">Copy of GERC Distribution Licensee certificate: <?php echo isset($ApplicationData['copy_of_gerc']) && $ApplicationData['copy_of_gerc'] == 1 ? 'YES' : 'NO'; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: none; padding: 0; margin: 0; padding-left: 5px;">Whether applicant has Captive Conventional Power Plant (CPP): <?php echo isset($ApplicationData['captive_conv_power_plant']) && $ApplicationData['captive_conv_power_plant'] == 1 ? 'YES' : 'NO'; ?></td>
                    </tr>
                    <tr>
                        <td width="70%" style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">(a). If yes, then Capacity of CPP:<?php echo isset($ApplicationData['capacity_of_cpp']) ? $ApplicationData['capacity_of_cpp'].'MW' : ''; ?> </td>
                        <td width="30%" style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"></td>
                    </tr>
                    <tr>
                        <td style="border: none; border-left: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">(b). Enclose the copy of paid conventional electricity duty of last 3 months : <?php echo isset($ApplicationData['copy_of_conventional_electricity']) ? 'YES' : 'NO'; ?></td>
                        <td style="border: none; border-right: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"></td>
                    </tr>
                    <tr>
                        <td style="border: none; border-left: 1px solid black; border-bottom: 1px solid black; padding: 0; margin: 0; padding-left: 5px;">(c). Any previous Solar Project put up for captive RPO: <?php echo isset($ApplicationData['prev_solar_project']) ? 'YES' : 'NO'; ?></td>
                        <td style="border: none; border-right: 1px solid black; border-bottom: 1px solid black; padding: 0; margin: 0; padding-left: 5px;"></td>
                    </tr>
                    <tr>
                        <td style="border-bottom: none; border-right: none; padding: 0; margin: 0; padding-left: 5px;">Certificate of STOA/ MTOA/ LTOA by SLDC/GETCO: </td>
                        <td style="border-bottom: none; border-left: none; padding: 0; margin: 0; padding-left: 5px;"><?php echo isset($ApplicationData['certi_of_stoa']) && $ApplicationData['certi_of_stoa'] == 1 ? 'YES' : 'NO'; ?></td>
                    </tr>
                    <?php 
                        if(isset($ApplicationData['certi_of_stoa']) && $ApplicationData['certi_of_stoa'] == 1 ){
                    ?>
                    <tr>
                        <td colspan="2" style="border-top: none;  padding: 0; margin: 0; padding-left: 5px;">If yes, then Capacity for which Certificate of STOA/ MTOA/ LTOA issued by SLDC/GETCO:<?php echo isset($ApplicationData['certi_of_stoa_capacity']) ? $ApplicationData['certi_of_stoa_capacity'] : ''; ?> MW</td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2" style="border-top: none;  padding: 0; margin: 0; padding-left: 5px; font-size: 12.5px;">If STOA /MTOA / LTOA then Notarized Undertaking on Rs.300 Stamp,confirming the compliance for maintaining the eligibility as obligated entity by retaining STOA /MTOA / LTOA throughout the life span of RE generating plant:<?php echo isset($ApplicationData['RE_generating_plant']) && $ApplicationData['RE_generating_plant'] == 1 ? 'YES' : 'NO'; ?> </td>
                    </tr>
                </table>

                <div class="page-break"></div>
            <?php } ?>
            <!-- Page-3 -->

            <?php
            if (isset($ApplicationData['end_use_of_electricity']) &&  $ApplicationData['end_use_of_electricity'] == 3 && ($ApplicationData['third_party'] == 1 || $ApplicationData['third_party'] == 2)) {
            ?>
                <table width="100%" class="td_bold table_1" style="border: none; padding: 8px 0px; text-align: left; margin-top: 30px;">
                    <tr>
                        <td style="font-size: 14.5px;">D. Project for Third Party Sale OR Third Party Sale & RPO compliance to Third Party OR Third <span style="padding-left: 18px;">Party Sale with REC Mechanism (Details of Third Party beneficiary company):</span></td>
                    </tr>
                </table>
                <table class="td_simple" width="100%" style="text-align: left;">
                    <?php $checkThirdParty = isset($ApplicationData['details_of_third_party']) && $ApplicationData['details_of_third_party'] == 1 ? 'YES' : 'NO' ?>
                    <tr>
                        <td width="30%" style="border-right: none; padding-left: 5px;">Details of Third Party</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td colspan="4" width="65%" style="border-left: none;"><?php echo $checkThirdParty ?></td>
                    </tr>
                    <tr>
                        <td width="30%" style="border-right: none; padding-left: 5px;">Name</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td colspan="4" width="65%" style="border-left: none;"><?php echo $checkThirdParty == 'YES' && isset($ApplicationData['third_party_name']) ? $ApplicationData['third_party_name'] : '' ?></td>
                    </tr>
                    <tr>
                        <td width="30%" style="border-right: none; padding-left: 5px;">Address</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td colspan="4" width="65%" style="border-left: none;"><?php echo $checkThirdParty == 'YES' && isset($ApplicationData['third_party_address']) ? $ApplicationData['third_party_address'] : '' ?></td>
                    </tr>
                    <tr>
                        <td width="20%" style="border-right: none; padding-left: 5px;">Consumer no.</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td width="25%" style="border-left: none; border-right: none; "><?php echo $checkThirdParty == 'YES' && isset($ApplicationData['third_party_consumer_no']) ? $ApplicationData['third_party_consumer_no'] : '' ?></td>
                        <td width="20%" style="border-left: none; border-right: none;">Contract Demand</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td width="25%" style="border-left: none;"><?php echo $checkThirdParty == 'YES' && isset($ApplicationData['third_party_contract_demand']) ? $ApplicationData['third_party_contract_demand'] : '' ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" width="15%" style="border-right: none; padding-left: 5px;">Capacity of Existing Solar Power Plant (MW) if installed before this application :</td>
                        <td width="30%" style="border-left: none;"><?php echo $checkThirdParty == 'YES' && isset($ApplicationData['third_party_capacity_existing_plant']) ? $ApplicationData['third_party_capacity_existing_plant'] : '' ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" width="15%" style="border-right: none; padding-left: 5px;">Enclose the electricity bill of Third Party Consumer :</td>
                        <td width="30%" style="border-left: none;"><?php echo $checkThirdParty == 'YES' && isset($ApplicationData['electricit_bill_of_third_party']) ? 'YES' : 'NO' ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" width="15%" style="padding-left: 5px;">In case of multiple third party consumer, enclosed the detailed annexure copy of the same :</td>
                    </tr>
                </table>

            <?php } ?>

            <?php
            if (isset($ApplicationData['end_use_of_electricity']) && ($ApplicationData['end_use_of_electricity'] == 2 || $ApplicationData['end_use_of_electricity'] == 3) && ($ApplicationData['captive'] == 2 || $ApplicationData['third_party'] == 2)) {
            ?>
                <table width="100%" class="td_bold table_1" style="border: none; padding: 10px 0px; text-align: left;">
                    <tr>
                        <td style="font-size: 14.5px;">E. Project with REC Mechanism: Captive □ / Third Party Sale □</span></td>
                    </tr>
                </table>
                <table class="td_simple" width="100%" style="text-align: left; font-size: 14px;">
                    <tr>
                        <td width="70%" style="border-right: none; padding-left: 5px;">Documents Enclosed:</td>
                        <td width="5%" style="border-left: none; border-right: none;"></td>
                        <td width="25%" style="border-left: none;"></td>
                    </tr>
                    <tr>
                        <td width="70%" style="border-right: none; padding-left: 5px;">Physical copy of application done on online REC registration website</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td width="25%" style="border-left: none;">
                            <?php echo isset($ApplicationData['phy_copy_of_rec_reg_web']) && $ApplicationData['phy_copy_of_rec_reg_web'] == 1 ? 'YES' : 'NO' ?></td>
                    </tr>
                    <tr>
                        <td width="70%" style="border-right: none; padding-left: 5px;">Copy of receipt for application done on online REC registration website</td>
                        <td width="5%" style="border-left: none; border-right: none;">:</td>
                        <td width="25%" style="border-left: none;">
                            <?php echo isset($ApplicationData['receipt_copy_of_rec_reg_web']) && $ApplicationData['receipt_copy_of_rec_reg_web'] == 1 ? 'YES' : 'NO' ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: none; padding-left: 5px;">Power Evacuation Arrangement permission letter from the host State Transmission Utility or the</td>
                    </tr>
                    <tr>
                        <td width="70%" style="border-right: none; border-top: none; padding-left: 5px;">concerned Distribution Licensee, as the case may be</td>
                        <td width="5%" style="border-left: none; border-top: none; border-right: none;">:</td>
                        <td width="25%" style="border-left: none; border-top: none;"><?php echo isset($ApplicationData['power_eva_arra_per']) && $ApplicationData['power_eva_arra_per'] == 1 ? 'YES' : 'NO' ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: none; padding-left: 5px;">(a). Installation of Solar Project shall be allowed up to Sanctioned load/ Contract demand.</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-top: none; padding-left: 18px;">(b). Minimum Capacity of Solar Project shall be 250 kW.</td>
                    </tr>
                </table>

            <?php } ?>

           
            <!-- F -->
            <table width="100%" class="td_bold table_1" style="border: none; padding: 7px 0px; padding-top: 17px; text-align: left;">
                <tr>
                    <td>F. Land details of the proposed project:</td>
                </tr>
            </table>
            <?php if (isset($lanDetails)) { ?>
                <?php foreach ($lanDetails as $lk => $lv) { ?>
                    <table class="td_simple" width="100%" style="text-align: left; font-size: 13.5px;">

                        <tr>
                            <td width="4%" style="border-right: none; text-align: center;"><?php echo $lk + 1 ?></td>
                            <td width="18%" style="border-right: none; padding-left: 5px;">Land Category :</td>
                            <td width="30%" style="border-left: none; border-right: none;"><?php echo isset($lv['land_category']) ? $landCategory[$lv['land_category']] : '' ?> </td>
                            <td width="18%" style="border-right: none; padding-left: 5px;">Plot/Survey No. :</td>
                            <td width="30%" style="border-left: none;"> <?php echo isset($lv['land_plot_servey_no']) ? $lv['land_plot_servey_no'] : '' ?> </td>

                        </tr>
                        <tr>
                            <td colspan=5>
                                <table class="td_simple table_1" width="100%">
                                    <tr>
                                        <td width="15%" style="border-bottom: 1px solid black; padding-left: 5px;">Taluka/Village :</td>
                                        <td width="19%" style="border-bottom: 1px solid black; border-right: 1px solid black;"><?php echo isset($lv['land_taluka']) ? $lv['land_taluka'] : '' ?><?php echo isset($lv['land_city']) ? $lv['land_city'] : '' ?> </td>
                                        <td width="13%" style="border-bottom: 1px solid black; padding-left: 5px;">District :</td>
                                        <td width="20%" style="border-bottom: 1px solid black; border-right: 1px solid black;"><?php echo isset($lv['land_district']) ? '' : '' ?></td>
                                        <td width="13%" style="border-bottom: 1px solid black; padding-left: 5px;">State :</td>
                                        <td width="20%" style="border-bottom: 1px solid black;">Gujrat</td>//$lv['district_master']['name']
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 5px;">Latitude :</td>
                                        <td style="border-right: 1px solid black"><?php echo isset($lv['land_latitude']) ? $lv['land_latitude'] : '' ?></td>
                                        <td style="padding-left: 5px;">Longitude :</td>
                                        <td style="border-right: 1px solid black"><?php echo isset($lv['land_longitude']) ? $lv['land_longitude'] : '' ?></td>
                                        <td style="padding-left: 5px;">Area of land :</td>
                                        <td><?php echo isset($lv['area_of_land']) ? $lv['area_of_land'] : '' ?></td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=5>
                                <table class="td_simple" width="100%">
                                    <tr>
                                        <td width="20%" style="border:none;">Deed of land :</td>
                                        <td width="32%" style="border:none;"><?php echo isset($lv['deed_of_land']) ? $deedOfLand[$lv['deed_of_land']] : '' ?></td>
                                        <td width="30%" style="border:none; border-left: 1px solid black;">Upload Land Document :</td>
                                        <td width="18%" style="border:none;" colspan="3">Yes / No</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table><br>
                <?php }  ?>
            <?php } ?>

            <!-- G -->
            <table width="100%" class="td_bold table_1" style="border: none; padding: 10px 0px;  text-align: left;">
                <tr>
                    <td>G. Registration fee (non-refundable) details:</td>
                </tr>
            </table>
            <table class="td_simple" width="100%" style="text-align: left;">
                <tr>
                    <td class="td_bold">Capacity of the Project</td>
                    <td class="td_bold">Charges in Rs. Plus GST extra</td>
                </tr>
                <tr>
                    <td>Less than 1 MW</td>
                    <td>13,000/-</td>
                </tr>
                <tr>
                    <td>1 MW & Above</td>
                    <td>26,000/- per MW</td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none;">(At present GST of 18% is applicable, GEDA GST No. 24AAATG1858Q1ZA)</td>
                </tr>
            </table>



            <!-- Page-4 -->

            <table width="100%" class="td_bold table_1" style="border: none; padding: 20px 10px; margin-top: 100px; text-align: left;">
                <tr>
                    <td>H. Demand Draft details:</td>
                </tr>
            </table>
            <table class="td_simple" width="100%" style="text-align: left;">
                <tr>
                    <td style="padding: 10px;">Registration fees of Rs._____________/- submitted in the form of Demand Draft drawn on______________<br>Bank, ___________________ Branch, bearing DD/BC No. __________________ dated________________.</td>
                </tr>
            </table>
            <table width="100%" class="td_bold table_1" style="border: none; padding-left: 20px; margin-top: 30px; text-align: left;">
                <tr>
                    <td>I. Declaration to be signed by the authorized signatory of the applicant:</td>
                </tr>
            </table>
            <table class="td_simple" width="100%" style="text-align: left;">
                <tr>
                    <td style="padding: 10px; border:none; text-align:justify; font-size: 14px;">I (Name & designation) ________________________________________(authorized representative of M/s._____________________________________) declare that the information filled in the application form is true and correct to my knowledge and GEDA is not responsible for providing land, the power evacuation and water supply facility, for the operation/ maintenance of the power evacuation facilities and its uninterrupted functioning. Further, that GEDA is absolved from any loss that may occur on account of failure of the substation/transmission line and / or non-performance of any system of the project etc. I have read and understood and shall abide by the provision of the “Gujarat Renewable Energy Policy – 2023 vide G.R. No. REN/e-file/20/2023/0476/B1 dated 04/10/2023”. It is also to certify that I have followed all rules and regulations, including those required for purchase/ lease for the private land and that I indemnify GEDA from all such responsibilities arising at a later date due to noncompliance of any law/regulation of the land. </td>
                </tr>
                <tr style="margin-top: 20px;">
                    <td style="padding: 20px 10px; border:none; text-align:justify; font-size: 14px;">Signature of Applicant and seal of the company:</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; border:none; text-align:justify; font-size: 14px;">Place :</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; border:none; text-align:justify; font-size: 14px;">Date:</td>
                </tr>
            </table>
        </div>

    </div>
    <footer>
        <p style="font-size:14px;font-weight:bold">Gujarat Energy Developement Agency, Gandhinagar</p>
    </footer>
</body>

</html>