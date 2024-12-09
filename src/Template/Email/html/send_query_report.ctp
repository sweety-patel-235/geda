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
<html>
	<head>
    	<title><?php echo PRODUCT_NAME; ?></title>
    	<style type="text/css">
	    	.table-layout-style1{
			    border-collapse: collapse;
			    border: 1px solid black;
			}
			.table-layout-style1 th {
			    background-color: #71bf57;
			    font-weight: bold;
			    padding: 5px;
			}
			.table-layout-style1 td{
			    border: 1px solid black;
			    padding:3px;
			}
		</style>
    </head>
    <body>
    	Hello <?php echo (isset($project_detail['customer']['name'])?$project_detail['customer']['name']:''); ?>,<br/><br/>
    	<table border="1" width="100%" class="table-layout-style1">
        	<tr>
            	<th colspan="4">Inquiry Details</th>
            </tr>
            <tr>
                <td width="25%">Your Name</td>
                <td width="25%"><?php echo (isset($project_detail['customer']['name'])?$project_detail['customer']['name']:''); ?></td>
                <td width="25%">Customer Type</td>
                <td width="25%"><?php echo (isset($project_detail['custtype']['para_value'])?$project_detail['custtype']['para_value']:''); ?></td>
            </tr>
            <tr>
                <td>Latitude</td>
                <td><?php echo (isset($project_detail['latitude'])?$project_detail['latitude']:0); ?></td>
                <td>Longitude </td>
                <td><?php echo (isset($project_detail['longitude'])?$project_detail['longitude']:0); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo (isset($project_detail['customer']['email'])?$project_detail['customer']['email']:''); ?></td>
                <td>Mobile</td>
                <td><?php echo (isset($project_detail['customer']['mobile'])?$project_detail['customer']['mobile']:''); ?></td>
            </tr>
            <tr>
                <td>City</td>
                <td><?php echo (isset($project_detail['city'])?$project_detail['city']:''); ?></td>
                <td>State</td>
                <td><?php echo (isset($project_detail['state'])?$project_detail['state']:''); ?></td>
            </tr>
			<tr>
                <td>Landmark</td>
                <td colspan="3"><?php echo (isset($project_detail['landmark'])?$project_detail['landmark']:''); ?></td>
            </tr>
            <tr>
                <td>Project Name</td>
                <td><?php echo (isset($project_detail['name'])?$project_detail['name']:''); ?></td>
                <td>Rooftop Area</td>
                <td><?php echo (isset($project_detail['area'])?$project_detail['area']:0); ?></td>
            </tr>
            <tr>
                <td>Monthly Average Bill</td>
                <td><?php echo (isset($project_detail['avg_monthly_bill'])?$project_detail['avg_monthly_bill']:0); ?></td>
                <td>Average Monthly Energy Consumption</td>
                <td><?php echo (isset($project_detail['estimated_kwh_year'])?$project_detail['estimated_kwh_year']:0); ?></td>
            </tr>
            <tr>
                <td>Type of Backup</td>
                <td><?php echo (isset($project_detail['backup_type_name'])?$project_detail['backup_type_name']:''); ?></td>
                <td>Usage</td>
                <td><?php echo (isset($project_detail['usage_hours'])?$project_detail['usage_hours']:''); ?></td>
            </tr>
        </table>
        <br/>
        <table border="1" width="100%" class="table-layout-style1">
            <tr>
                <th colspan="4">Customize Report</th>
            </tr>
            <tr>
                <td width="25%">Recommended Capacity</td>
                <td width="25%"><?php echo (isset($project_detail['recommended_capacity'])?$project_detail['recommended_capacity']:0); ?></td>
                <td width="25%">Estimated Cost</td>
                <td width="25%"><?php echo (isset($project_detail['estimated_cost'])?$project_detail['estimated_cost']:0); ?></td>
            </tr>
            <tr>
                <td width="25%">Estimated Cost with Subsidy</td>
                <td width="25%"><?php echo (isset($project_detail['estimated_cost_subsidy'])?$project_detail['estimated_cost_subsidy']:0); ?></td>
                <td width="25%">Payback</td>
                <td width="25%"><?php echo (isset($project_detail['payback'])?$project_detail['payback']:0); ?></td>
            </tr>
            <tr>
                <td width="25%">Average Energy Generation</td>
                <td width="25%"><?php echo ((isset($project_detail['avg_generate']) && ($project_detail['avg_generate'] > 0))?number_format($project_detail['avg_generate']/12,2):0); ?></td>
                <td width="25%">Maximum Solar PV Capacity</td>
                <td width="25%"><?php echo (isset($project_detail['maximum_capacity'])?$project_detail['maximum_capacity']:0); ?></td>
            </tr>
        </table>
        <br/>
        <?php if(!empty($installer_list)) { ?>    
        <table border="1" width="100%" class="table-layout-style1">
            <tr>
                <th width="30%">Installer Name</th>
                <th width="30%">Address</th>
                <th width="25%">City</th>
                <th width="25%">State</th>
            </tr>
            <?php 
            foreach ($installer_list as $installer):
                echo '<tr>';
                    echo '<td>'.$installer['installers']['installer_name'].'</td>';
                    echo '<td>'.$installer['installers']['address'].'</td>';
                    echo '<td>'.$installer['installers']['city'].'</td>';
                    echo '<td>'.$installer['installers']['state'].'</td>';
                echo '</tr>';
            endforeach;
            ?>
        </table>
        <?php } ?>
    </body>
</html>