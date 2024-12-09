<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$defaultDescription = PAGE_TITLE;
$defaultAuthor 		= COMPANY_NAME;
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?= PAGE_TITLE ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="<?php echo $defaultDescription;?>" name="description"/>
<meta content="<?php echo $defaultAuthor;?>" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<?= $this->Html->css('../plugins/font-awesome/css/font-awesome.min.css') ?>
<?= $this->Html->css('../plugins/simple-line-icons/simple-line-icons.min.css') ?>
<?= $this->Html->css('../plugins/bootstrap/css/bootstrap.min.css') ?>
<?= $this->Html->css('../plugins/uniform/css/uniform.default.css') ?>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<?= $this->Html->css('assets/admin/pages/css/login.css') ?>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<?= $this->Html->css('assets/global/components.css') ?>
<?= $this->Html->css('assets/global/plugins.css') ?>
<?= $this->Html->css('layout/css/layout.css') ?>
<?= $this->Html->css('layout/css/themes/default.css') ?>
<?= $this->Html->css('layout/css/custom.css') ?>
<?= $this->Html->css('admin_custom.css'); ?>
<?= $this->Html->script('../plugins/jquery.min.js') ?>
<?= $this->fetch('meta') ?>
<?= $this->fetch('css') ?>
<?= $this->fetch('script') ?>
<link rel="shortcut icon" href="/img/frontend/favicon.png"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
	<a href="<?php echo URL_HTTP.ADMIN_PATH?>">
		<?php echo $this->Html->image('logo.png', ['alt' => ADMIN_COMPANY_NAME ,'class'=>'logo-image']);  ?>
	</a>
	<!-- BEGIN LOGIN FORM -->
	<?php echo $this->fetch('content') ?>
	<!-- END LOGIN FORM -->
	
</div>
<div class="copyright">
	 <?php echo date('Y'); ?> &copy; <?php echo COMPANY_NAME; ?>
</div>
<!-- END LOGIN -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<![endif]-->
<?= $this->Html->script('../plugins/jquery-migrate.min.js') ?>

<?= $this->Html->script('../plugins/bootstrap/js/bootstrap.min.js') ?>
<?= $this->Html->script('../plugins/jquery.blockui.min.js') ?>
<?= $this->Html->script('../plugins/uniform/jquery.uniform.min.js') ?>
<?= $this->Html->script('../plugins/jquery.cokie.min.js') ?>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->

<?= $this->Html->script('../plugins/jquery-validation/js/jquery.validate.min.js') ?>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<?= $this->Html->script('../css/assets/global/scripts/metronic.js') ?>
<?= $this->Html->script('../css/layout/scripts/layout.js') ?>
<?= $this->Html->script('../css/layout/scripts/demo.js') ?>
<?php /*= $this->Html->script('../css/assets/admin/pages/scripts/login.js')*/ ?>
<?= $this->fetch('script') ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
	Metronic.init(); // init metronic core components
	//Layout.init(); // init current layout
	Login.init();
	Demo.init();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
