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
            	<th colspan="4">New Installer Registration</th>
            </tr>
            <tr>
                <td width="25%">Installer Name</td>
                <td width="25%"><?php echo (isset($project_detail['Installers']['installer_name'])?$project_detail['Installers']['installer_name']:''); ?></td>
                <td width="25%">Contact Person</td>
                <td width="25%"><?php echo (isset($project_detail['Installers']['contact_person'])?$project_detail['Installers']['contact_person']:''); ?></td>
            </tr>
            <tr>
                <td>Designation</td>
                <td><?php echo (isset($project_detail['Installers']['designation'])?$project_detail['Installers']['designation']:''); ?></td>
                <td>Mobile</td>
                <td><?php echo (isset($project_detail['Installers']['mobile'])?$project_detail['Installers']['mobile']:''); ?></td>
            </tr>
             <tr>
                <td>Email</td>
                <td><?php echo (isset($project_detail['Installers']['email'])?$project_detail['Installers']['email']:''); ?></td>
                <td>Address line 1</td>
                <td><?php echo (isset($project_detail['Installers']['branch_address1'])?$project_detail['Installers']['branch_address1']:''); ?></td>
            </tr>
            <tr>
                <td>City</td>
                <td><?php echo (isset($project_detail['Installers']['city'])?$project_detail['Installers']['city']:''); ?></td>
                <td>State</td>
                <td><?php echo (isset($project_detail['Installers']['state'])?$project_detail['Installers']['state']:''); ?></td>
            </tr>
            <tr>
                <td>Area Of Work</td>
                <td colspan="3"><?php 
                    foreach ($stateArr as $key => $value) {
                        echo (isset( $value['statename'])?$value['statename'].',':''); 
                    }
                ?></td>
            </tr>
            <tr>
                <td>PAN</td>
                <td><?php echo (isset($project_detail['Installers']['pan'])?$project_detail['Installers']['pan']:''); ?></td>
                <td>TIN</td>
                <td><?php echo (isset($project_detail['Installers']['tin'])?$project_detail['Installers']['tin']:''); ?></td>
            </tr>
            <tr>
                <td colspan='4' style="background-color: #71bf57;">Estimated Cost of 10 kW SPV in Rs.</td>
                
            </tr>
            <tr>
               <td> Non-DCR: </td> 
                <td><?php echo (isset($project_detail['Installers']['kw_non_dcr_10'])?$project_detail['Installers']['kw_non_dcr_10']:''); ?></td>
                <td>DCR Category ( Foreign Cells, Indian Modules): </td>
                <td><?php echo (isset($project_detail['Installers']['kw_dcr_10'])?$project_detail['Installers']['kw_dcr_10']:''); ?></td>
            </tr>
             <tr>
                <td colspan='4' style="background-color: #71bf57;">Estimated Cost of 100 kW SPV in Rs.</td>
                
            </tr>
             <tr>
                <td> Non-DCR: </td> 
                <td><?php echo (isset($project_detail['Installers']['kw_non_dcr_100'])?$project_detail['Installers']['kw_non_dcr_100']:''); ?></td>
                <td>DCR Category ( Foreign Cells, Indian Modules): </td>
                <td><?php echo (isset($project_detail['Installers']['kw_dcr_100'])?$project_detail['Installers']['kw_dcr_100']:''); ?></td>
            </tr>
            
	    </table>
        <br/>
        <table border="1" width="100%" class="table-layout-style1">
            <tr>
                <th width="30%">Rating Type</th>
                <th width="20%">Valid Upto</th>
                <th width="20%">Application No.</th>
                <th width="30%">Name of Rating Agency</th>
                <th width="30%">Agency Rating</th>
                <th width="30%">MNRE Rating</th>
            </tr>
            <?php 
            foreach ($project_detail['type'] as $id=>$key):
                echo '<tr>';
                    echo '<td>'.$project_detail['type'][$id].'</td>';
                    echo '<td>'.$project_detail['validupto'][$id].'</td>';
                    echo '<td>'.$project_detail['appno'][$id].'</td>';
                    echo '<td>'.$project_detail['rate_agency'][$id].'</td>';
                    echo '<td>'.$project_detail['agency_rate'][$id].'</td>';
                    echo '<td>'.$project_detail['mnre_rate'][$id].'</td>';
                echo '</tr>';
            endforeach;
            ?>
        </table>
    </body>
</html>