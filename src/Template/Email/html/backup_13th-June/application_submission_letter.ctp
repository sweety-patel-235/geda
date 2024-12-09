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
#ko_footerBlock_2 .links-color a:visited, #ko_footerBlock_2 .links-color a:hover {color: #ccc;color: #ccc;text-decoration: underline;}</style></head><body bgcolor="#bfbfbf" text="#919191" alink="#cccccc" vlink="#cccccc" style="margin: 0;padding: 0;background-color: #bfbfbf;color: #919191;">

  <center>

  <!-- preheaderBlock -->
  <table width="100%" cellpadding="0" border="0" cellspacing="0" bgcolor="#bfbfbf" style="background-color: #bfbfbf;" id="ko_footerBlock_2"><tbody><tr><td align="center" valign="top" bgcolor="#bfbfbf" style="background-color: #bfbfbf;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;height:30px;">
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr></tbody></table>
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
                  <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                    <tr><td height="9" style="font-size: 1px; line-height: 1px;"> </td></tr>
                      <tr>
                        <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                        Dear <?php echo $CUSTOMER_NAME;?>,
                        </td>
                      </tr>
                       <tr><td height="9" style="font-size: 1px; line-height: 1px;"> </td></tr>
                      <tr>
                        <td align="center" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                          
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
        Your application for Rooftop Solar PV System has been submitted and the Application no. is <?php echo $LETTER_APPLICATION_NO;?>. Kindly keep this Application no. for your future communications with State Nodal Agency and DisCom. Thank you.
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
                
      </td>
      </tr>
      <tr>
          <td>
            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody>
                <tr>
                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    Sunny Regards,
                  </td>
                </tr>
                <tr>
                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    Administrator
                  </td>
                </tr>
                <tr>
                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    AHA! Solar <?php echo $STATENAME;?>
                  </td>
                </tr>
                <tr>
                  <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
                    <img src="<?php echo URL_HTTP;?>pdf/images/logo_pdf.png" width="65" />
                  </td>
                </tr>
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
    </tr></tbody></table><!-- footerBlock --><table width="100%" cellpadding="0" border="0" cellspacing="0" bgcolor="#bfbfbf" style="background-color: #bfbfbf;" id="ko_footerBlock_2"><tbody><tr><td align="center" valign="top" bgcolor="#bfbfbf" style="background-color: #bfbfbf;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;height:30px;">
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr></tbody></table><!-- /footerBlock --></center>

</body></html>