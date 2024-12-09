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
    <title><?php echo (isset($page_title)?$page_title:PRODUCT_NAME); ?></title>

    <meta name="keywords" content="" />
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/img/frontend/favicon.png" type="image" />
    <link rel="apple-touch-icon" href="/img/frontend/favicon.png">
    
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Web Fonts  -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="/vendor/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="/vendor/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" href="/vendor/owlcarousel/owl.carousel.min.css" media="screen">
    <link rel="stylesheet" href="/vendor/owlcarousel/owl.theme.default.min.css" media="screen">
    <link rel="stylesheet" href="/vendor/magnific-popup/magnific-popup.css" media="screen">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/css/frontend/theme.css">
    <link rel="stylesheet" href="/css/frontend/theme-elements.css">
    <link rel="stylesheet" href="/css/frontend/theme-blog.css">
    <link rel="stylesheet" href="/css/frontend/theme-shop.css">
    <link rel="stylesheet" href="/css/frontend/theme-animate.css">

    <!-- Current Page CSS -->
    <link rel="stylesheet" href="/vendor/rs-plugin/css/settings.css" media="screen">
    <link rel="stylesheet" href="/vendor/circle-flip-slideshow/css/component.css" media="screen">

    <!-- Skin CSS -->
    <link rel="stylesheet" href="/css/frontend/skins/default.css">
    
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="/css/frontend/custom.css">

    <!-- Head Libs -->
    <script src="/vendor/modernizr/modernizr.js"></script>
    <!--[if IE]>
			<link rel="stylesheet" href="css/ie.css">
		<![endif]-->

    <!--[if lte IE 8]>
			<script src="/vendor/respond/respond.js"></script>
			<script src="/vendor/excanvas/excanvas.js"></script>
		<![endif]-->
</head>
<body>
    <div class="body">
        <?php 
        if(strtolower($this->request->controller) == "users" && strtolower($this->request->action) == "index"){
            echo $this->fetch('content');
        }else{
        ?>
        <div role="main" class="main">
        <div class="container">
		<?php echo $this->Flash->render(); ?>
        </div>
        <?php echo $this->fetch('content') ?>
        
        <?php } ?>
        </div>
	</div>

<!-- Vendor -->
<!--[if lt IE 9]>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<![endif]-->
<!--[if gte IE 9]><!-->
<script src="/vendor/jquery/jquery.js"></script>
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
</script>
</body>
</html>