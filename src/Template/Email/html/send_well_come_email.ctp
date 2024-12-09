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
		Hi  <?php echo (!empty($customer_name)?ucwords($customer_name):'') ?>,<br><br>
		<p>
		Welcome and first of all I would like to congratulate you on choosing AHA! Solar App.
		</p>
		<p>
		Trust me, we are also excited to show you the software that will rock the solar industry!
		</p><p>
		We are happy to answer any of your questions or assist you if you have any problem with logging in, registering or creating a project- just let me know!
		</p>
		<p>Sunny Regards,
		</p>	
		<p>AHA! Rooftop Solar Helper
		<br/>
			E mail: info@ahasolar.in
		<br/>
			Website: www.ahasolar.in
		</p>	
    </body>
</html>



