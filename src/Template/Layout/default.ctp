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
<title> <?= PAGE_TITLE ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">

<?= $this->Html->css('../plugins/font-awesome/css/font-awesome.min.css') ?>
<?= $this->Html->css('../plugins/simple-line-icons/simple-line-icons.min.css') ?>
<?= $this->Html->css('../plugins/bootstrap/css/bootstrap.min.css') ?>
<?= $this->Html->css('../plugins/uniform/css/uniform.default.css') ?>
<?= $this->Html->css('../plugins/bootstrap-switch/css/bootstrap-switch.min.css') ?>
<?= $this->Html->css('../plugins/plugins/bootstrap/dataTables.bootstrap.css') ?>
<?= $this->Html->css('../plugins/bootstrap-datepicker/css/datepicker3.css') ?>
<?= $this->Html->css('../plugins/bootstrap-select/bootstrap-select.min.css'); ?>
<!-- END GLOBAL MANDATORY STYLES -->
<?= $this->Html->css('../plugins/select2/select2.css') ?>

<!-- BEGIN THEME STYLES -->

<?= $this->Html->css('assets/global/components.css') ?>
<?= $this->Html->css('assets/global/plugins.css') ?>
<?= $this->Html->css('layout/css/layout.css') ?>
<?= $this->Html->css('layout/css/themes/grey.css') ?>
<?= $this->Html->css('layout/css/jquery.fancybox.css') ?>
<?= $this->Html->css('layout/css/custom.css') ?>
<?= $this->Html->css('admin_custom.css') ?>
<!--END THEME STYLES -->
 <?= $this->Html->script('../plugins/jquery.min.js') ?>
<?= $this->Html->script('jquery.dataTables.js') ?>
<?= $this->fetch('meta') ?>
<?= $this->fetch('css') ?>
<?= $this->fetch('script') ?>
<script type="text/javascript">
	WEB_ADMIN_URL = "<?php echo URL_HTTP.ADMIN_PATH ?>";
</script>
<link rel="shortcut icon" href="/img/frontend/favicon.png"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-static-top">
	<!-- BEGIN HEADER INNER -->
	<?php echo $this->element('header'); ?>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!--<div class="container">-->
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
		<!-- BEGIN SIDEBAR -->
		<?php echo $this->element('side_menu'); ?>
		<!-- END SIDEBAR -->
		<!-- BEGIN CONTENT -->
		<div class="page-content-wrapper">
			<div class="page-content">
				<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
				<?php echo $this->fetch('content') ?>
				<!-- END PAGE CONTENT-->
			</div>
		</div>
		<!-- END CONTENT -->
		<!-- BEGIN QUICK SIDEBAR -->
		<!--Cooming Soon...-->
		<!-- END QUICK SIDEBAR -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="page-footer">
		<?php echo $this->element('footer'); ?>
	</div>
	<!-- END FOOTER -->
<!--</div>-->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>

<![endif]-->
<?= $this->Html->script('../plugins/jquery-migrate.min.js') ?>
<!--<script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<?php //= $this->Html->script('admin_search.js'); ?>
<?= $this->Html->script('../plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') ?>
<?= $this->Html->script('../plugins/bootstrap/js/bootstrap.min.js') ?>
<?= $this->Html->script('../plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') ?>
<?= $this->Html->script('../plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>
<?= $this->Html->script('../plugins/jquery.blockui.min.js') ?>
<?= $this->Html->script('../plugins/jquery.cokie.min.js') ?>
<?= $this->Html->script('../plugins/uniform/jquery.uniform.min.js') ?>
<?= $this->Html->script('../plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>
<?= $this->Html->script('../plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>
<?= $this->Html->script('../css/assets/global/scripts/metronic.js') ?>
<?= $this->Html->script('../css/layout/scripts/layout.js') ?>
<?= $this->Html->script('../css/layout/scripts/demo.js') ?>
<?= $this->Html->script('../plugins/bootstrap-select/bootstrap-select.min.js'); ?>
<?= $this->Html->script('../plugins/select2/select2.min.js'); ?>
<?= $this->Html->script('../plugins/jquery-multi-select/js/jquery.multi-select.js'); ?>
<?= $this->Html->script('../css/assets/admin/pages/scripts/components-dropdowns.js'); ?>
<?= $this->Html->script('admin_custom.js'); ?>
<?= $this->fetch('script') ?>
<script>
	jQuery(document).ready(function() {    
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		Demo.init(); // init demo features
		ComponentsDropdowns.init();
		$('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
     	/*$("#select2_sample5").select2({
            tags: ["red", "green", "blue", "yellow", "pink"]
        });*/
	});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
