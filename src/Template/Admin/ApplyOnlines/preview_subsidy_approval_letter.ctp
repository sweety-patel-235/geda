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
        <div class="container"> 
            <div class="mainbox">
                <table width="570" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad" bgcolor="#ffffff" style="border-collapse: separate;border-spacing: 18px;padding-left: 0;padding-right: 0;width: 100%;max-width: 570px;background-color: #fff;">
                    <tbody>
                      <tr>
                          <td>
                            <table align="right" border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tbody>
                                <tr>
                                  <td width="50%" align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    Letter Reference No: <?php echo $LETTER_APPLICATION_NO;?>
                                  </td>
                                  <td width="50%" align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    Dated: <?php echo $APPLICATION_DATE;?>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                      </tr>
                      <tr>
                          <td>
                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    To,
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    &nbsp;
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  <?php echo $CUSTOMER_NAME;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  <?php echo $CUSTOMER_ADDRESS;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    &nbsp;
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    &nbsp;
                                  </td>
                                </tr>
                                <tr>
                                  <td align="center" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    Sub: Installation of Rooftop Solar PV System under the subsidy scheme
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    &nbsp;
                                  </td>
                                </tr>
                                <tr>
                                  <td align="center" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                    Ref:
                                      <ol>
                                          <!--<li>JREDA Work Order empanelment letter to the <?php //echo $INSTALLER_NAME?></li>-->
                                          <li>JREDA Work Order No. <?php echo $JREDA_WORK_ORDER_NO;?></li>
                                          <li>Your application for availing <?php echo $STATE_SUBSIDY?>% State CFA for Rooftop Solar PV system</li>
                                          <li>Your application for availing <?php echo $CENTRAL_SUBSIDY?>% MNRE CFA and <?php echo $STATE_SUBSIDY?>% State Subsidy for Rooftop Solar PV system.</li>
                                          <li>Net metering application dated <?php echo $APPLICATION_DATE?></li>
                                          <li>Consumer No. <?php echo $CONSUMER_NO?></li>
                                          <li>Agreement dated <?php echo $AGREEMENT_DATE?></li>
                                          <li>Letter dated <?php echo $AGREEMENT_DATE?></li>
                                      </ol>
                                  </td>
                                </tr>
                                <tr>
                                <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                        <p style="margin: 1em 0px;margin-bottom: 10px;margin-top: 10px;">
                                        Sir,<br /><br />
                                        With reference to above, Director, JREDA is pleased to sanction subsidy for the above-mentioned system as per the details given below:
                                        </p>
                                </td>
                                </tr>
                                <tr>
                                <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                 <table cellpadding="5" border="1" cellspacing="2" width="100%" style="text-align: left; font-size: 12px; color: #3f3f3f;border-collapse: collapse;border: 1px solid #ff0000;"> <tr>
                                        <td width="2%" valign="top" >1.</td>
                                        <td width="40%" valign="top" >JREDA Work Order No.</td>
                                        <td width="58%" valign="top" ><?php echo $JREDA_WORK_ORDER_NO;?></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" >2.</td>
                                        <td valign="top" >SPV panel specification/capacity</td>
                                        <td valign="top" >As per latest edition of IEC 61215/ <?php echo $APPROVED_CAPACITY;?> kW</td>
                                    </tr>
                                    <tr>
                                        <td valign="top" >3.</td>
                                        <td valign="top" >Grid tied inverter specification/ capacity</td>
                                        <td valign="top" >As per IEC 61683/IS 61683 & IEC 60068-2/ <?php echo $APPROVED_CAPACITY;?> kW</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">4.</td>
                                        <td valign="top">Type of beneficiary</td>
                                        <td valign="top">Rs <?php echo $CUSTOMER_TYPE;?> /-</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">5.</td>
                                        <td valign="top">Total System Cost</td>
                                        <td valign="top">Rs <?php echo $ESTIMATED_COST;?> /-</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">6.</td>
                                        <td valign="top">MNRE <?php echo $CENTRAL_SUBSIDY?>% CFA of MNRE benchmark cost</td>
                                        <td valign="top">Rs. <?php echo $CENTRAL_SUBSIDY_AMOUNT;?> /-</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">7.</td>
                                        <td valign="top">State <?php echo $STATE_SUBSIDY;?>% Subsidy of MNRE benchmark cost</td>
                                        <td valign="top">Rs. <?php echo $STATE_SUBSIDY_AMOUNT;?> /-</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">8.</td>
                                        <td valign="top">Total Subsidy</td>
                                        <td valign="top">Rs. <?php echo $TOTAL_SUBSIDY_AMOUNT;?> /-</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">9.</td>
                                        <td valign="top">Address where GCRT plant will be installed</td>
                                        <td valign="top">Rs. <?php echo $CUSTOMER_ADDRESS;?> /-</td>
                                    </tr>
                                  </table>
                                </td>
                                </tr>
                                <tr>
                                  <td align="left" class="">
                                      <table align="left" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;" >
                                        <tr>
                                          <td width="5%" valign="top">1.</td>
                                          <td width="95%" align="left" valign="top" class="long-text links-color">
                                            You have to get the system installed before <?php echo $INSTALLATION_DATE?> from the date of issue of this letter through M/s <?php echo $INSTALLER_NAME;?> as per your request.
                                          </td>
                                        </tr>
                                        <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                                        <tr>
                                            <td valign="top">2.</td>
                                            <td align="left" valign="top" class="long-text links-color">
                                                The agency shall install the system as per terms & conditions of JREDA NIB no. <?php echo $JERDA_WORK_NIB; ?> and Specification (inclusive of indigenous SPV modules). 
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                                        <tr>
                                            <td valign="top">3.</td>
                                            <td align="left" valign="top" class="long-text links-color">
                                                You shall issue us a letter of installation of the system along with copy of Connectivity agreement with Discom, Bi-directional and Solar Meter installation & its details to enable us to undertake its inspection.
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                                        <tr>
                                            <td valign="top">4.</td>
                                            <td align="left" valign="top" class="long-text links-color">
                                                The subsidy amount for a Solar PV system at the rate mentioned above (without battery system) shall be released to Beneficiary or to the Contractor on recommendation of beneficiary.
                                            </td>
                                        </tr>
                                        
                                      </table>
                                  </td>
                              </tr>
                            </table>
                          </td>
                      </tr>
                  </tbody>
                </table>
                <div class="page-break"></div>
                <table align="left" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;margin-left:20px;" >
                <tr>
                                            <td valign="top">5.</td>
                                            <td align="left" valign="top" class="long-text links-color">
                                                Subsidy sanctioned to you will be treated as cancelled on failing to comply with above terms and conditions. 
                                            </td>
                                        </tr>
                  </table>
                  <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">    
                      <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                      <tr>
                      <td colspan="2" align="left" class="long-text links-color">
                          Above mentioned subsidy amount can be reduced or cancelled without assigning any reasons.
                      </td>
                      </tr>
                      <tr>
                      <td colspan="2" align="right" class="long-text links-color">
                          Yours faithfully,
                      </td>
                      </tr>
                      <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                      <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                      <tr>
                      <td colspan="2" align="right" class="long-text links-color">
                          (Niranjan Kumar)<br />
                          Director-JREDA
                      </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <table>
                            <tr>
                              <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  Memo No ______________
                              </td>
                              <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  Ranchi, Date ______________
                              </td>
                              </tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr>
                              <td colspan="2" align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  Copy forwarded to M/s SG Enterprises, SG House, Shradhanand Road, Mahabir Chowk, Ranchi-834001, Jharkhand for information & necessary action.
                              </td>
                              </tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr>
                              <td colspan="2" align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  (Niranjan Kumar)<br />
                                  Director-JREDA
                              </td>
                              </tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr>
                              <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  Memo No ______________
                              </td>
                              <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  Ranchi, Date ______________
                              </td>
                              </tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr>
                              <td colspan="2" align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  Copy forwarded to Project Director, JREDA/ Engineer-In-Charge of the work, JREDA and Accounts Officer, JREDA for information & necessary action.
                              </td>
                              </tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                              <tr>
                              <td colspan="2" align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                                  (Niranjan Kumar)<br />
                                  Director-JREDA
                              </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                  </table>
            </div>
        </div>
    </body>
</html>