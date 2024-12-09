<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php
$content = explode("\n", $content);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="initial-scale=1.0"><meta name="format-detection" content="telephone=no"><title><?php echo PRODUCT_NAME; ?></title><style type="text/css">.socialLinks {font-size: 6px;}
.socialLinks a {display: inline-block;}
.socialIcon {display: inline-block;vertical-align: top;padding-bottom: 0px;border-radius: 100%;}
table.vb-row, table.vb-content {border-collapse: separate;}
table.vb-row {border-spacing: 9px;}
table.vb-row.halfpad {border-spacing: 0;padding-left: 9px;padding-right: 9px;}
table.vb-row.fullwidth {border-spacing: 0;padding: 0;}
table.vb-container.fullwidth {padding-left: 0;padding-right: 0;}</style><style type="text/css">
    /* yahoo, hotmail */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{ line-height: 100%; }
    .yshortcuts a{ border-bottom: none !important; }
    .vb-outer{ min-width: 0 !important; }
    .RMsgBdy, .ExternalClass{
      width: 100%;
      background-color: #3f3f3f;
      background-color: #3f3f3f}

    /* outlook */
    table{ mso-table-rspace: 0pt; mso-table-lspace: 0pt; }
    #outlook a{ padding: 0; }
    img{ outline: none; text-decoration: none; border: none; -ms-interpolation-mode: bicubic; }
    a img{ border: none; }

    @media screen and (max-device-width: 600px), screen and (max-width: 600px) {
      table.vb-container, table.vb-row{
        width: 95% !important;
      }

      .mobile-hide{ display: none !important; }
      .mobile-textcenter{ text-align: center !important; }

      .mobile-full{
        float: none !important;
        width: 100% !important;
        max-width: none !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
      }
      img.mobile-full{
        width: 100% !important;
        max-width: none !important;
        height: auto !important;
      }   
    }
  </style><style type="text/css">#ko_singleArticleBlock_3 .links-color a:visited, #ko_singleArticleBlock_3 .links-color a:hover {color: #3f3f3f;color: #3f3f3f;text-decoration: underline;}
#ko_singleArticleBlock_5 .links-color a:visited, #ko_singleArticleBlock_5 .links-color a:hover {color: #3f3f3f;color: #3f3f3f;text-decoration: underline;}
#ko_footerBlock_2 .links-color a:visited, #ko_footerBlock_2 .links-color a:hover {color: #ccc;color: #ccc;text-decoration: underline;}</style></head><body bgcolor="#3f3f3f" text="#919191" alink="#cccccc" vlink="#cccccc" style="margin: 0;padding: 0;background-color: #3f3f3f;color: #919191;">

  <center>

  <!-- preheaderBlock -->
  
  <!-- /preheaderBlock -->

  <table class="vb-outer" width="100%" cellpadding="0" border="0" cellspacing="0" bgcolor="#bfbfbf" style="background-color: #bfbfbf;" id="ko_singleArticleBlock_3">
    <tbody>
      <tr>
        <td class="vb-outer" align="center" valign="top" bgcolor="#bfbfbf" style="padding-left: 9px;padding-right: 9px;background-color: #bfbfbf;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;">
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
                    <tbody>
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
                                <li>Your application for availing <?php echo $STATE_SUBSIDY?> State CFA for Rooftop Solar PV system</li>
                                <li>Your application for availing <?php echo $CENTRAL_SUBSIDY?> MNRE CFA and <?php echo $STATE_SUBSIDY?> State Subsidy for Rooftop Solar PV system.</li>
                                <li>Net metering application dated <?php echo $APPLICATION_DATE?></li>
                                <li>Consumer No. <?php echo $CONSUMER_NO?></li>
                                <li>Agreement dated <?php echo $AGREEMENT_DATE?></li>
                                <li>Letter dated <?php echo $AGREEMENT_DATE?></li>
                            </ol>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
            </tr>
          <tr>
            <td>
              <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                        <p style="margin: 1em 0px;margin-bottom: 10px;margin-top: 10px;">
                        Sir,<br /><br />
                        With reference to above, Director, JREDA is pleased to sanction subsidy for the above-mentioned system as per the details given below:
                        </p>
              </td>
            </tr></tbody></table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr></tbody></table><table class="vb-outer" width="100%" cellpadding="0" border="0" cellspacing="0" bgcolor="#bfbfbf" style="background-color: #bfbfbf;" id="ko_singleArticleBlock_5"><tbody><tr><td class="vb-outer" align="center" valign="top" bgcolor="#bfbfbf" style="padding-left: 9px;padding-right: 9px;background-color: #bfbfbf;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
    <div class="oldwebkit" style="max-width: 570px;">
        <table width="570" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad" bgcolor="#ffffff" style="border-collapse: separate;border-spacing: 18px;padding-left: 0;padding-right: 0;width: 100%;max-width: 570px;background-color: #fff;">
        <tbody>
        <tr>
            <td>
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td align="left" class="long-text links-color">
                        <table align="left" border="1" cellpadding="5" cellspacing="0" width="100%" style="text-align: left; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td width="2%" valign="top">1.</td>
                                <td width="40%" valign="top">JREDA Work Order No.</td>
                                <td width="58%" valign="top"><?php echo $JREDA_WORK_ORDER_NO;?></td>
                            </tr>
                            <tr>
                                <td valign="top">2.</td>
                                <td valign="top">SPV panel specification/capacity</td>
                                <td valign="top">As per latest edition of IEC 61215/ <?php echo $APPROVED_CAPACITY;?> kW</td>
                            </tr>
                            <tr>
                                <td valign="top">3.</td>
                                <td valign="top">Grid tied inverter specification/ capacity</td>
                                <td valign="top">As per IEC 61683/IS 61683 & IEC 60068-2/ <?php echo $APPROVED_CAPACITY;?> kW</td>
                            </tr>
                            <tr>
                                <td valign="top">4.</td>
                                <td valign="top">Type of beneficiary</td>
                                <td valign="top"><?php echo $CUSTOMER_TYPE;?></td>
                            </tr>
                            <tr>
                                <td valign="top">5.</td>
                                <td valign="top">Total System Cost</td>
                                <td valign="top"><?php echo $ESTIMATED_COST;?></td>
                            </tr>
                            <tr>
                                <td valign="top">6.</td>
                                <td valign="top">MNRE <?php echo $CENTRAL_SUBSIDY?> CFA of MNRE benchmark cost</td>
                                <td valign="top"><?php echo $CENTRAL_SUBSIDY_AMOUNT;?></td>
                            </tr>
                            <tr>
                                <td valign="top">7.</td>
                                <td valign="top">State <?php echo $STATE_SUBSIDY;?> Subsidy of MNRE benchmark cost</td>
                                <td valign="top"><?php echo $STATE_SUBSIDY_AMOUNT;?></td>
                            </tr>
                            <tr>
                                <td valign="top">8.</td>
                                <td valign="top">Total Subsidy</td>
                                <td valign="top"><?php echo $TOTAL_SUBSIDY_AMOUNT;?></td>
                            </tr>
                            <tr>
                                <td valign="top">9.</td>
                                <td valign="top">Address where GCRT plant will be installed</td>
                                <td valign="top"><?php echo $CUSTOMER_ADDRESS;?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                <tr>
                    <td>
                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                        <tbody>
                            <tr>
                                <td width="5%" valign="top">1.</td>
                                <td width="95%" align="left" valign="top" class="long-text links-color">
                               1.   You have to get the system installed before <?php echo $INSTALLATION_DATE?> from the date of issue of this letter through M/s <?php echo $INSTALLER_NAME;?> as per your request.
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
                            <tr><td colspan="2" height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                            <tr>
                                <td valign="top">5.</td>
                                <td align="left" valign="top" class="long-text links-color">
                                    Subsidy sanctioned to you will be treated as cancelled on failing to comply with above terms and conditions. 
                                </td>
                            </tr>
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
                        </tbody>
                        </table>
                    </td>
                </tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                <tr>
                    <td>
                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
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
                        </tbody>
                        </table>
                    </td>
                </tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td>
                </tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr></tbody></table><!-- footerBlock --><table width="100%" cellpadding="0" border="0" cellspacing="0" bgcolor="#3f3f3f" style="background-color: #3f3f3f;" id="ko_footerBlock_2"><tbody><tr><td align="center" valign="top" bgcolor="#3f3f3f" style="background-color: #3f3f3f;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;">
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr></tbody></table><!-- /footerBlock --></center>

</body></html>