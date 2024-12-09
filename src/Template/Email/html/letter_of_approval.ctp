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
                        <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                          Letter Reference No: <?php echo $LETTER_APPLICATION_NO;?>
                        </td>
                      </tr>
                      <tr>
                        <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
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
                        Consumer No: <?php echo $CONSUMER_NO;?>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                          Reference No: <?php echo $FESIBILITY_REF_NO;?>
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
                        <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                          &nbsp;
                        </td>
                      </tr><tr>
                        <td align="center" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                          Ref: Your request of Application No. <?php echo $APPLICATION_NO;?>
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
        Your request for installing Rooftop PV system for <?php echo $APPROVED_CAPACITY;?> kWp capacity is considered and approval is accorded with the following conditions: 
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
        <table width="570" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad" bgcolor="#ffffff" style="border-collapse: separate;border-spacing: 18px;padding-left: 0;padding-right: 0;width: 100%;max-width: 570px;background-color: #fff;"><tbody>
        <tr><td><table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr>
            </tr><tr><td height="9" style="font-size: 1px; line-height: 1px;"> </td>
            </tr><tr><td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                <p style="margin: 1em 0px;margin-bottom: 10px;margin-top: 10px;">
        <span style="margin-right: 10px">1.</span>You shall set up the rooftop solar PV plant (the “Plant”) and submit the work completion report along with Single Line Diagram of the synchronizing and protection arrangement issued by the approved plant supplier/EPC contractor that the Plant has been installed as per approved standards and specifications within 180 days. In case of delay, you shall have to get further extension from JBVNL. Such extension will be granted for a maximum period of 2-months only and the approval granted will lapse automatically if the Plant is not set-up even in the extended 2-months period. However, you will be eligible to apply in the next financial year but your application will be kept at the bottom of the list of applicants and you will be permitted to set-up the plant only if all the applicants above you are selected and there is still capacity available for allotment.
        </p>
        <p style="margin: 1em 0px;margin-bottom: 10px;margin-top: 10px;">
        <span style="margin-right: 10px">2.</span>You will abide by the guidelines for Grid Interactive Rooftop Solar Photo Voltaic Power Plants issued by Govt. of Jharkhand/JSERC/ JBVNL/JREDA.
        </p>
        <p style="margin: 1em 0px;margin-bottom: 10px;margin-top: 10px;">
                <span style="margin-right: 10px">3.</span>The solar plant shall comply with the relevant standards specified by the MNRE / BIS and CEA. The responsibility of operation and maintenance of the solar photo voltaic (SPV) generator including all accessories and apparatus lies with the consumer. The design and installation of the rooftop SPV should be equipped with appropriately rated protective devices to sense any abnormality in the system and carry out automatic isolation of the SPV from the grid. The inverters used should meet the necessary quality requirements and should be certified for their quality by appropriate authority; the protection logic should be tested before commissioning of the plant. 
                </p>
        <p style="margin: 1em 0px;margin-bottom: 10px;margin-top: 10px;">
        <span style="margin-right: 10px">4.</span>The automatic isolation or islanding protection of SPV should be ensured for, no grid supply and low or over voltage conditions and within the required response time. Adequate rated fuses and fast acting circuit breakers on input and output side of the inverters and disconnect/isolating switches to isolate DC and AC system for maintenance shall be provided. The consumer should provide for all internal safety and protective mechanism for earthing, surge, DC ground fault, transients etc.
        </p>
                <p style="margin: 1em 0px;margin-bottom: 0px;margin-top: 10px;">
                <span style="margin-right: 10px">&nbsp;</span>To prevent back feeding and possible accidents when maintenance works are carried out by JBVNL personnel, Double pole / Triple pole with neutral isolation disconnection switches which ever applicable can be locked by JBVNL personnel should be provided. This is in addition to automatic sensing and isolating on grid supply failure etc. and in addition to internal disconnection switches. In the event of JBVNL LT/HT supply failure, the consumer has to ensure that there will not be any solar power being fed to the LT/HT grid of JBVNL. You will be solely responsible for any accident to human beings/animals whatsoever (fatal/non) fatal/departmental/non departmental) that may occur due to back feeding from SPV plant when the grid supply is off. JBVNL have the right to disconnect the rooftop solar system at any time in the event of possible threat/damage, from such rooftop solar system to its distribution system, to prevent any accident or damage, without any notice.<br /><br />
        <span style="margin-right: 10px">&nbsp;</span>You shall abide by all the codes and regulations issued by the Commission to the extent applicable and in force from time to time and shall comply with JSERC/JBVNL/CEA requirements with respect safe, secure and reliable functioning of the SPV plant and the grid. The power injected into the grid shall be of the required quality in respect of wave shape, frequency, absence of DC components etc.<br /><br />
        <span style="margin-right: 10px">&nbsp;</span>The inverter standard shall be such that it should not allow solar power / battery power to extend to JBVNL’s Grid on failure of JBVNL’s Grid supply irrespective of connectivity options.<br /><br />
        <span style="margin-right: 10px">&nbsp;</span>You shall restrict the harmonic generation within the limit specified in IEEE 519 or as may be specified by the Central Electricity Authority.<br />
                </p><br /><br />
      </td>
      </tr>
      <tr>
          <td>
            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody>
                <tr>
                  <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    AEE/Supply,
                  </td>
                </tr>
                <tr>
                  <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    Sub Division <?php echo $SUBDIVISION;?>
                  </td>
                </tr>
                <!--<tr>
                  <td align="right" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    Division <?php echo $DIVISION;?>
                  </td>
                </tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                <tr>
                  <td align="left" class="long-text links-color" style="text-align: right; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    Powered By AHASolar
                  </td>
                </tr>-->
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
                <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td></tr>
              </tbody>
            </table>
          </td>
      </tr>
      <tr><td height="13" style="font-size: 1px; line-height: 1px;"> </td>
            </tr></tbody></table></td></tr></tbody></table></div>
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