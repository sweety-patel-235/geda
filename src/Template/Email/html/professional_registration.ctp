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
    </head>
    <body>
    	Hello <?php echo $insData['installer_name']; ?>,<br/><br/>
    	
    	thank you for stay with <?php echo PRODUCT_NAME; ?>
    	
    	Below are your co-worker team activation codes.
    	<?php
		if(!empty($insCodeArr)) { 
    		$worker_no = 1;
    		foreach ($insCodeArr as $key=>$value) { 
				echo "<br/><br/>";
				echo "Co-wroker ".$worker_no." Activation code- ".$value;
				$worker_no++;
    		}
    	}
    	?>
    </body>
</html>