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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo PRODUCT_NAME; ?></title>
    	<style type="text/css">
	    	.table-layout-style1 {
			    border-collapse: collapse;
			    border: 0px;
			}
			.table-layout-style1 th {
			    background-color: #ffcb29;
			    font-weight: bold;
			    padding: 5px;
			}
			.lable{
				color:#ffcb29;
				font-weight: bold;
			}
			.table-layout-style1 td{
				border: none;
			    padding:3px;
			}
			.bordercls{
				border: 1px solid #ffcb29 !important;
				padding-top:5px !important;
				padding-bottom:5px !important;
				padding-left:5px !important;
			}
		</style>
    </head>
    <body>
		<table width="100%" class="table-layout-style1" style="border-collapse: collapse;border: 0px;">
        	<tr>
                <td style="border: none;padding: 3px;"><span class="lable" style="color: #ffcb29;font-weight: bold;">Query No.</span> <?php echo (isset($project_detail['id'])?$project_detail['id']:''); ?></td>
                <td style="border: none;padding: 3px;">&nbsp;</td>
				<td colspan="2" style="border: none;padding: 3px;"></td>
                <td rowspan="2" align="right" style="border: none;padding: 3px;"><img src="<?php echo URL_HTTP; ?>img/ahasolarLogo.png"></td>
			</tr>
			<tr>
                <td style="border: none;padding: 3px;"><span class="lable" style="color: #ffcb29;font-weight: bold;">Date</span> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <?php echo date('d-M-Y'); ?></td>
                <td style="border: none;padding: 3px;">&nbsp;</td>
				<td style="border: none;padding: 3px;"></td>
                <td style="border: none;padding: 3px;"></td>
			</tr>
        </table>
		<br>
    	Hello ,<br><br>
		<p>
		AHA! has received an inquiry for installation of a rooftop solar photovoltaic (RTPV) system from one of our users.  The user has selected your company to send its query.  The details input by the user are given below:
		</p>
		<div class="bordercls" style="border: 1px solid #ffcb29 !important;padding-top: 5px !important;padding-bottom: 5px !important;padding-left: 5px !important;">
    	<table width="100%" class="table-layout-style1" style="border-collapse: collapse;border: 0px;">
        	<tr>
            	<th colspan="4" style="background-color: #ffcb29;font-weight: bold;padding: 5px;">GENERAL INFORMATION</th>
            </tr>
            <tr>
                <td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">User Name</td>
                <td width="25%" style="border: none;padding: 3px;"><?php echo (isset($project_detail['customer']['name'])?$project_detail['customer']['name']:''); ?></td>
				<td width="25%" style="border: none;padding: 3px;">&nbsp;</td>
				<td width="25%" style="border: none;padding: 3px;">&nbsp;</td>
                
            </tr>
            <tr>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Email</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['customer']['email'])?$project_detail['customer']['email']:''); ?></td>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Mobile</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['customer']['mobile'])?$project_detail['customer']['mobile']:''); ?></td>
            </tr>
            <tr>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">City</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['city'])?$project_detail['city']:''); ?></td>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">State</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['state'])?$project_detail['state']:''); ?></td>
            </tr>
			 <tr>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Latitude</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['latitude'])?$project_detail['latitude']:0); ?></td>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Longitude </td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['longitude'])?$project_detail['longitude']:0); ?></td>
            </tr>
			</table>
			</div>
			<br>
			<div class="bordercls" style="border: 1px solid #ffcb29 !important;padding-top: 5px !important;padding-bottom: 5px !important;padding-left: 5px !important;">
			<table width="100%" class="table-layout-style1" style="border-collapse: collapse;border: 0px;">
        	<tr>
            	<th colspan="4" style="background-color: #ffcb29;font-weight: bold;padding: 5px;">PROJECT INPUTS BY USER</th>
            </tr>
            <tr>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Project Name</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['name'])?$project_detail['name']:0); ?></td>
				<td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Customer Type</td>
                <td width="25%" style="border: none;padding: 3px;"><?php echo (isset($project_detail['custtype']['para_value'])?$project_detail['custtype']['para_value']:''); ?></td>
            </tr>
            <tr>
				<td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Rooftop Area</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['area'])?$project_detail['area']:0); ?> <?php  echo (($project_detail['area_type']=='2001')?'sq ft':'sq mt'); ?></td>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Avg. Monthly Bill</td>
                <td style="border: none;padding: 3px;">Rs. <?php echo (isset($project_detail['avg_monthly_bill'])?$project_detail['avg_monthly_bill']:0); ?>/-</td>
                
            </tr>
            <tr>
				<td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Avg. Monthly Consumption</td>
                <td style="border: none;padding: 3px;">Rs. <?php echo (isset($project_detail['avg_monthly_bill'])?$project_detail['avg_monthly_bill']:0); ?>/-</td>
                <td class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Type of Backup</td>
                <td style="border: none;padding: 3px;"><?php echo (isset($project_detail['backup_type_name'])?$project_detail['backup_type_name']:''); ?></td>
            </tr>
        </table>
		</div>
        <br>
		<div class="bordercls" style="border: 1px solid #ffcb29 !important;padding-top: 5px !important;padding-bottom: 5px !important;padding-left: 5px !important;">
        <table width="100%" class="table-layout-style1" style="border-collapse: collapse;border: 0px;">
            <tr>
                <th colspan="4" style="background-color: #ffcb29;font-weight: bold;padding: 5px;">AHA! RECOMMENDATION (based on user Inputs)</th>
            </tr>
            <tr>
                <td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Recommended RTPV Capacity</td>
                <td width="25%" style="border: none;padding: 3px;"><?php echo (isset($project_detail['recommended_capacity'])?$project_detail['recommended_capacity']:0); ?> kW</td>
				<td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">(Max. Capacity)</td>
                <td width="25%" style="border: none;padding: 3px;">(<?php echo (isset($project_detail['maximum_capacity'])?$project_detail['maximum_capacity']:0); ?> kW)</td>
                
            </tr>
            <tr>
                <td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Estimated Subsidized Cost</td>
                <td width="25%" style="border: none;padding: 3px;">Rs. <?php echo (isset($project_detail['estimated_cost_subsidy'])?$project_detail['estimated_cost_subsidy']:0); ?> Lakhs</td>
				<td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Estimated Cost</td>
                <td width="25%" style="border: none;padding: 3px;">Rs. <?php echo (isset($project_detail['estimated_cost'])?$project_detail['estimated_cost']:0); ?> Lakhs</td>
			</tr>
            <tr>
                <td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Average Energy Generation</td>
                <td width="25%" style="border: none;padding: 3px;"><?php echo (isset($project_detail['avg_generate'])?$project_detail['avg_generate']:0); ?> /Month</td>
                <td width="25%" class="lable" style="color: #ffcb29;font-weight: bold;border: none;padding: 3px;">Expected Payback</td>
                <td width="25%" style="border: none;padding: 3px;"><?php echo (isset($project_detail['payback'])?$project_detail['payback']:0); ?> years</td>
            </tr>
        </table>
		</div>
        <br> 
		<p>
		We believe the above information will be useful to you. We kindly request you to contact the user and assist them in establishing their rooftop solar photovoltaic system.
		</p><p>
We also request you to visit us at http://www.ahasolar.in and get yourself registered with us to increase the visibility on AHA!'s marketplace. Thank you.
		</p>
		<p>Sincerely Yours,
		</p>	
		<p>AHA! Rooftop Solar Helper Team</p>	
    </body>
</html>



