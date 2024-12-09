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

$cakeDescription = 'Aha Solar';
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?> - 202.66.172.116 - 1</title>

    <meta name="keywords" content="" />
    <meta name="description" content="">
    <meta name="author" content="">
	
	
	<link rel="shortlink" href="<?php echo URL_HTTP?>">
	<link rel="search" type="application/opensearchdescription+xml" href="<?php echo URL_HTTP?>" title="<?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?>">
	<link rel="icon" href="<?php echo URL_HTTP?>img/frontend/favicon.png" sizes="50x50">
	<meta name="theme-color" content="#e62117">       
	<meta name="google-site-verification" content="iLiJYOEm21CSl_yFSRoPx_E_T3dsfFuAw9L1Ko1xTp8" />
	<meta property="og:site_name" content="<?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?>">
	<meta property="og:url" content="<?php echo URL_HTTP?>">
	<meta property="og:title" content="<?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?>">
	<meta property="og:image" itemprop="image" content="<?php echo URL_HTTP?>img/frontend/logo-sticky.png">

	<meta property="og:description" content="<?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?>">
	  <meta property="al:android:app_name" content="AHA Solar">
    <meta property="al:android:package" content="com.energy.ahasolar">
    <meta property="al:web:url" content="https://play.google.com/store/apps/details?id=com.energy.ahasolar&amp;feature=applinks">

	<meta name="twitter:site" content="<?php echo URL_HTTP?>">
	<meta name="twitter:url" content="<?php echo URL_HTTP?>">
	<meta name="twitter:title" content="<?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?>">
	<meta name="twitter:description" content=" ">
	<meta name="twitter:image" itemprop="image" content="<?php echo URL_HTTP?>img/frontend/logo-sticky.png">
	<meta name="twitter:app:name:googleplay" content="AHA Solar">
	<meta name="twitter:app:id:googleplay" content="com.energy.ahasolar">
	<meta name="twitter:app:url:googleplay" content="https://play.google.com/store/apps/details?id=com.energy.ahasolar&amp;feature=applinks">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/img/frontend/favicon.png" type="image" />
    <link rel="apple-touch-icon" href="/img/frontend/favicon.png">
    
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="/plugins/plugins/bootstrap/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="/vendor/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" href="/vendor/owlcarousel/owl.carousel.min.css" media="screen">
    <link rel="stylesheet" href="/vendor/owlcarousel/owl.theme.default.min.css" media="screen">
    <link rel="stylesheet" href="/vendor/magnific-popup/magnific-popup.css" media="screen">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/css/assets/global/components.css">
    <link rel="stylesheet" href="/plugins/simple-line-icons/simple-line-icons.min.css">
    <link rel="stylesheet" href="/css/frontend/theme.css">
    <link rel="stylesheet" href="/css/frontend/theme-elements.css">
    <link rel="stylesheet" href="/css/frontend/theme-blog.css">
    <link rel="stylesheet" href="/css/frontend/theme-shop.css">
    <link rel="stylesheet" href="/css/frontend/theme-animate.css">
    <link rel="stylesheet" href="/css/admin_custom.css">

    <!-- Current Page CSS -->
    <link rel="stylesheet" href="/vendor/rs-plugin/css/settings.css" media="screen">
    <link rel="stylesheet" href="/vendor/circle-flip-slideshow/css/component.css" media="screen">

    <!-- Skin CSS -->
    <link rel="stylesheet" href="/css/frontend/skins/default.css">
    
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="/css/frontend/custom.css">

    <!-- Sweetalert CSS-->
    <link rel="stylesheet" href="/css/sweetalert.min.css">

    <!-- Head Libs -->
    <script src="/vendor/jquery/jquery.js"></script>
    <?php echo $this->Html->script('jquery.dataTables.js') ?>
    <script src="/vendor/modernizr/modernizr.js"></script>

    <!-- Sweetalert js-->
    <script src="/js/sweetalert.min.js"></script>
    <!--[if IE]>
			<link rel="stylesheet" href="css/ie.css">
		<![endif]-->

    <!--[if lte IE 8]>
			<script src="/vendor/respond/respond.js"></script>
			<script src="/vendor/excanvas/excanvas.js"></script>
		<![endif]-->
        <style>
            .applay-online-from .tabs.tabs-simple .nav-tabs > li a {
                background: <?php echo COLOR_BLUE;?> !important;
                color: #fff !important;
                border-radius: 7px 7px 0 0;
            }
            .applay-online-from .tabs.tabs-simple .nav-tabs > li.active a{
                background: <?php echo COLOR_ORANGE;?> !important;
            }  
            .btn-primary {
                border-color: <?php echo COLOR_GREEN;?> !important;
                background-color: <?php echo COLOR_GREEN;?> !important;
                color: #fff !important;
            }     


            #header nav ul.nav-main ul.dropdown-menu {
                border: 0;
                border-color:  <?php echo COLOR_GREEN;?> !important;
                border-top: 5px solid <?php echo COLOR_GREEN;?> !important;
              
            }
            #header {
                border-color: <?php echo COLOR_ORANGE;?> !important;
                border-bottom: 0px !important;
            }

            #footer .footer-ribbon {
                background: <?php echo COLOR_GREEN;?> !important;
            }
            .ApplyOnline-leads .p-title a {
                color: <?php echo COLOR_GREEN;?> !important;
            }
            .ApplyOnline-leads .p-row {
                border: 1px solid <?php echo COLOR_GREEN;?> !important;
               
            }
            .tabs.tabs-bottom .nav-tabs li.active a, .tabs.tabs-bottom .nav-tabs li.active a:hover, .tabs.tabs-bottom .nav-tabs li.active a:focus {
                border-bottom: 3px solid <?php echo COLOR_ORANGE;?> !important;
                border-top-color: transparent !important;
            }
            .tabs.tabs-simple .nav-tabs > li a, .tabs.tabs-simple .nav-tabs > li a:hover, .tabs.tabs-simple .nav-tabs > li a:focus {
                border-bottom: 3px solid <?php echo COLOR_GREEN;?> !important;
                border-radius: 0;
            }
            .action-row .dropdown .dropdown-menu .dropdown-item {
                color: #212529 !important;
            }
            .applyonline-viewmain .greenbox {
                border-bottom: 1px solid <?php echo COLOR_ORANGE;?> !important;
            }
            .applyonline-viewmain .portlet-body h4 {
                background: <?php echo COLOR_GREEN;?> !important;
            }
            .progressbar_guj li.active:before {
                background-color: <?php echo COLOR_ORANGE;?> !important;
            }
            .progressbar_guj li.active + li:after {
                background-color: <?php echo COLOR_ORANGE;?> !important;
            }
            #header nav ul.nav-main li a:hover {
                background-color: <?php echo COLOR_ORANGE;?>;
                color: #ffffff;
            }
            #header nav ul.nav-main li a:focus {
                background: <?php echo COLOR_ORANGE;?>;
                color: #ffffff;
            }

            .nav-top.nk_menu {
                margin-bottom: -10px !important;
            }
            .action-row .dropdown .btn {
                background: <?php echo COLOR_BLUE;?> !important;
  
            }


            .ApplyOnline-leads .p-row {
                border-radius: 10px !important;
                background: #E6F1FE !important;;
                border:none !important;
            }
            .page-header {
                background-color: #ffffff !important; 
                border-bottom: none !important; 
                border-top: none !important;    
                min-height: 0px !important;
            }
            .page-header ul li a {
                color: <?php echo COLOR_ORANGE;?> !important;
            }
            .page-header ul li.active {
                color: <?php echo COLOR_BLUE;?> !important;
            }
            body.sticky-menu-active #header {
                box-shadow: none !important;
            }
            .green.btn {
                color: #FFFFFF;
                background-color: <?php echo COLOR_GREEN;?> !important;
                border-color: "";
            }
            .custom-greenhead thead tr:first-child {
                background-color: <?php echo COLOR_GREEN;?> !important;
                color: #fff;
            }


            .project-leads .p-row {
                border-radius: 10px !important;
                background: #E6F1FE !important;;
                border:none !important;
            }
            .project-leads .p-title a {
                 color: <?php echo COLOR_GREEN;?> !important;
            }
            .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
                background-color: <?php echo COLOR_GREEN;?>  !important;
            }
            .pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus {
                background-color: <?php echo COLOR_ORANGE;?> !important;
                color: #fff !important;
            }
            .btn-primary, .btn-success, .btn-default, .btn {
                border-radius: 6px !important;
            }
            .btn-file {
                overflow: hidden;
                border-radius: 0px 5px 5px 0px !important;
            }
        </style>
</head>
<body>
    <div class="body">
        	<?php echo $this->element('frontend/header'); ?>
		<?php 
        if(strtolower($this->request->controller) == "users" && strtolower($this->request->action) == "index"){
            echo $this->fetch('content');
        }else{
        ?>
        <div role="main" class="main">
        <section class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <?php 
                        echo $this->Html->getCrumbList(
                            [
                                'firstClass' => false,
                                'lastClass' => 'active',
                                'class' => 'breadcrumb',
                                'seperator' => ">"
                            ],
                            'Home'
                        );?>
                    </div>
                    <div class="col-md-4 text-right">
                        <?php echo $this->element('frontend/userinfo'); ?>
                    </div>
                </div>
            </div>
        </section>
        <?php /*<div class="container"> */?>
		<?php echo $this->Flash->render(); ?>
         <?php /*</div> */?>
        <?php echo $this->fetch('content') ?>
        
        <?php } ?>
        </div>
		<footer id="footer">
			<?php echo $this->element('frontend/footer'); ?>
		</footer>
	</div>

<!-- Vendor -->
<!--[if lt IE 9]>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<![endif]-->
<!--[if gte IE 9]><!-->
<!--<![endif]-->
<script src="/vendor/jquery.appear/jquery.appear.js"></script>
<script src="/vendor/jquery.easing/jquery.easing.js"></script>
<script src="/vendor/jquery-cookie/jquery-cookie.js"></script>
<script src="/vendor/bootstrap/bootstrap.js"></script>
<script src="/vendor/common/common.js"></script>
<script src="/vendor/jquery.validation/jquery.validation.js"></script>
<script src="/vendor/jquery.stellar/jquery.stellar.js"></script>
<script src="/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="/vendor/jquery.gmap/jquery.gmap.js"></script>
<script src="/vendor/isotope/jquery.isotope.js"></script>
<script src="/vendor/owlcarousel/owl.carousel.js"></script>
<script src="/vendor/jflickrfeed/jflickrfeed.js"></script>
<script src="/vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="/vendor/vide/vide.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="/js/frontend/theme.js"></script>

<!-- Specific Page Vendor and Views -->
<script src="/vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="/vendor/circle-flip-slideshow/js/jquery.flipshow.js"></script>
<script src="/js/frontend/views/view.home.js"></script>

<!-- Theme Custom -->
<script src="/js/frontend/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="/js/frontend/theme.init.js"></script>
<script type="text/javascript"> 
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-42715764-5']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

    function saveSubscriber() {
        var emailId    = $("#newsletterEmail").val();
        $.get('/Static/saveSubscriber?email='+emailId, function(result) {
            $("#sub-message").html(result);
        });
    }
</script>
</body>
</html>
