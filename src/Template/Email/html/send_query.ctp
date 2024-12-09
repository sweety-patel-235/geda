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
    	Hello Admin,<br/><br/>
    	<table border="1" width="100%" class="table-layout-style1">
        	<tr>
            	<th colspan="4">New Inquiry</th>
            </tr>
            <tr>
                <td width="25%">Customer Name</td>
                <td width="25%"><?php echo (isset($project_detail['Customer']['name'])?$project_detail['Customer']['name']:''); ?></td>
                <td width="25%">Customer Type</td>
                <td width="25%"><?php echo (isset($project_detail['Parameter']['para_value'])?$project_detail['Parameter']['para_value']:''); ?></td>
            </tr>
            <tr>
                <td>Latitude</td>
                <td><?php echo (isset($project_detail['Project']['latitude'])?$project_detail['Project']['latitude']:0); ?></td>
                <td>Longitude </td>
                <td><?php echo (isset($project_detail['Project']['longitude'])?$project_detail['Project']['longitude']:0); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo (isset($project_detail['Customer']['email'])?$project_detail['Customer']['email']:''); ?></td>
                <td>Mobile</td>
                <td><?php echo (isset($project_detail['Customer']['mobile'])?$project_detail['Customer']['mobile']:''); ?></td>
            </tr>
            <tr>
                <td>City</td>
                <td><?php echo (isset($project_detail['Project']['city'])?$project_detail['Project']['city']:''); ?></td>
                <td>State</td>
                <td><?php echo (isset($project_detail['Project']['state'])?$project_detail['Project']['state']:''); ?></td>
            </tr>
			<tr>
                <td>Landmark</td>
                <td colspan="3"><?php echo (isset($project_detail['Project']['landmark'])?$project_detail['Project']['landmark']:''); ?></td>
            </tr>
            <tr>
                <td>Project Name</td>
                <td><?php echo (isset($project_detail['Project']['name'])?$project_detail['Project']['name']:''); ?></td>
                <td>Rooftop Area</td>
                <td><?php echo (isset($project_detail['Project']['area'])?$project_detail['Project']['area']:0); ?></td>
            </tr>
            <tr>
                <td>Monthly Average Bill</td>
                <td><?php echo (isset($project_detail['Project']['avg_monthly_bill'])?$project_detail['Project']['avg_monthly_bill']:0); ?></td>
                <td>Average Monthly Energy Consumption</td>
                <td><?php echo (isset($project_detail['Project']['estimated_kwh_year'])?$project_detail['Project']['estimated_kwh_year']:0); ?></td>
            </tr>
            <tr>
                <td>Type of Backup</td>
                <td><?php echo (isset($project_detail['Project']['backup_type_name'])?$project_detail['Project']['backup_type_name']:''); ?></td>
                <td>Usage</td>
                <td><?php echo (isset($project_detail['Project']['usage_hours'])?$project_detail['Project']['usage_hours']:''); ?></td>
            </tr>
        </table>
        <br/>
        <table border="1" width="100%" class="table-layout-style1">
            <tr>
                <th colspan="4">Customize Report</th>
            </tr>
            <tr>
                <td width="25%">Recommended Capacity</td>
                <td width="25%"><?php echo (isset($project_detail['Project']['recommended_capacity'])?$project_detail['Project']['recommended_capacity']:0); ?></td>
                <td width="25%">Estimated Cost</td>
                <td width="25%"><?php echo (isset($project_detail['Project']['estimated_cost'])?$project_detail['Project']['estimated_cost']:0); ?></td>
            </tr>
            <tr>
                <td width="25%">Estimated Cost with Subsidy</td>
                <td width="25%"><?php echo (isset($project_detail['Project']['estimated_cost_subsidy'])?$project_detail['Project']['estimated_cost_subsidy']:0); ?></td>
                <td width="25%">Payback</td>
                <td width="25%"><?php echo (isset($project_detail['Project']['payback'])?$project_detail['Project']['payback']:0); ?></td>
            </tr>
            <tr>
                <td width="25%">Average Energy Generation</td>
                <td width="25%"><?php echo (isset($project_detail['Project']['avg_generate'])?$project_detail['Project']['avg_generate']:0); ?></td>
                <td width="25%">Maximum Solar PV Capacity</td>
                <td width="25%"><?php echo (isset($project_detail['Project']['maximum_capacity'])?$project_detail['Project']['maximum_capacity']:0); ?></td>
            </tr>
        </table>
        <br/>    
        <table border="1" width="100%" class="table-layout-style1">
            <tr>
                <th width="25%">Installer Name</th>
                <th width="20%">Email</th>
                <th width="15%">Mobile</th>
                <th width="25%">Address</th>
                <th width="15%">City</th>
            </tr>
            <?php 
            foreach ($installer_list as $installer):
                echo '<tr>';
                    echo '<td>'.$installer['installer_name'].'</td>';
                    echo '<td>'.$installer['email'].'</td>';
                    echo '<td>'.$installer['mobile'].'</td>';
                    echo '<td>'.$installer['address'].'</td>';
                    echo '<td>'.$installer['city'].'</td>';
                echo '</tr>';
            endforeach;
            ?>
        </table>
    </body>
</html>