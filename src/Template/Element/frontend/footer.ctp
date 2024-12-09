<div class="container">
    <div class="row">
        <div class="footer-ribbon">
            <span>Get in Touch</span>
        </div>
        <div class="col-md-3">
            <div class="newsletter">
                <h4 class="heading-primary">Newsletter</h4>
                <p>
                    Keep up on our always evolving product features and technology. Enter your e-mail and subscribe to our newsletter.
                </p>
                <div class="alert alert-success hidden" id="newsletterSuccess">
                    <strong>Success!</strong> You've been added to our email list.
                </div>
                <div class="alert alert-danger hidden" id="newsletterError"></div>
                <?php echo $this->Form->create('newsletterForm',['name'=>'newsletterForm','id'=>'newsletterForm','type'=>'post']);?>
                    <div class="input-group">
                        <input class="form-control" placeholder="Email Address" name="email" id="newsletterEmail" type="email">
                        <span class="input-group-btn">
                            <input type="submit" value="Go!" id="submitEmail" class="btn btn-default" onclick="return saveSubscriber();">
                        </span>
                    </div>
                    <div id="sub-message"></div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
        <div class="col-md-3">
            <h4>Quick Links</h4>
            <ul class="list list-icons list-icons-sm">
                <?php /*<li><i class="fa fa-caret-right"></i> <?php echo $this->Html->link('About Us',['controller'=>'Static','action' => 'about_us']); ?></li>*/?>
                <li><i class="fa fa-caret-right"></i> <?php echo $this->Html->link('News',['controller'=>'Static','action' => 'news']); ?></li>
                <li><i class="fa fa-caret-right"></i> <?php echo $this->Html->link('Terms and Conditions',['controller'=>'Static','action' => 'terms']); ?></li>
                <li><i class="fa fa-caret-right"></i> <?php echo $this->Html->link('Privacy Policy',['controller'=>'Static','action' => 'privacy_policy']); ?></li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="contact-details">
                <h4 class="heading-primary">Contact Us</h4>
                <ul class="contact">
                    <li>
                        <p><i class="fa fa-map-marker mb-50"></i><?php echo COMPANY_ADDRESS?></p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-envelope"></i>
                            <strong>Email:</strong>
                            <a href="mailto:<?php echo COMPANY_INFO_EMAIL?>"><?php echo COMPANY_INFO_EMAIL;?></a>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <h4 class="heading-primary">Supported By</h4>
            <a style="display:table-cell;vertical-align:middle;" href="<?php echo URL_HTTP; ?>">
                <img alt="Ahasolar_GIZ" data-sticky-height="auto" style="max-width: 270px;height:90px;margin-top: 9px;" src="<?php echo URL_HTTP;?>img/frontend/giz-logo1.png" class="img-responsive tran">
            </a>
        </div>
    </div>
    <div class="row" style="text-align:right;margin-right: -69px;">
         <div class="col-md-12">
            <li class="list-inline-item py-md-0 py-2">
                <div class="hit-counter">
                    <?php
                        if(isset($visitor)) {
                            foreach ($visitor as $value) {
                                echo "<span>".$value."</span>";
                            }
                        }
                    ?>
                </div>
            </li>
        </div>
    </div>
</div>
<div class="footer-copyright" id="ud">
    <div class="container">
        <div class="row udfoot">
            <div class="col-md-8">
                 <p style="color:#FFF"><?php echo date('Y'); ?> &copy; <?php echo COMPANY_NAME; ?> | Powered By AHA Solar</p>
            </div>
            <div class="col-md-4">
                <nav id="sub-menu">
                    <ul>
                        <?php /*<li><?php echo $this->Html->link('FAQ',['controller'=>'Static','action' => 'faq']); ?></li> */?>
                        <li><?php echo $this->Html->link('Contact Us',['controller'=>'Static','action' => 'contact_us']); ?></li> 
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo URL_HTTP;?>js/jquery.base64.min.js"></script>
<script type="text/javascript">
      $(document).ready(function(){
        $('#submitEmail').click(function(){
            setTimeout(function(){
                $('#newsletterEmail').val('');
                $('#sub-message').html(''); 
            },2500);
        });
        $(window).scroll(function() {
          if ($(this).scrollTop() > 4){ 
            $('.brand').addClass("nk_sticky");
            $('.nav-top.nk_menu').addClass("hidepill");
            $('.stickyhide').addClass("hidepill");
            $('.brandsticky').removeClass("hideblock");
            $("#header").css({"min-height":"48px", "height":"80px"});  
            // $(".navbar-collapse.nav-main-collapse.collapse").css({"padding":"15px 0"});  
            $(".btn.btn-responsive-nav.btn-inverse").css({"top":"5px"});  
            $("#header").css({"height":"auto"}); 
            $(".hidemobnav").css({"display":"block"});
            $(".mobnavtop").css({"display":"block"}); 
            $('.hidemobnav').addClass("mobmenu");
            $('#header').addClass("smheader mobheaderheight stickyheightauto");
            $('.smheader').addClass("heighttab");
            $('.header-container').addClass("smcontainer deskhead stickyheadercontainer");
            $('.hidemobnav.mobmenu').addClass("mobmenusm");
            $('.logo a').addClass("logosmallsticky");
            $('.logo a img').addClass("logosmallimg");
            $("#header .logo").css({"padding":"0"});
        } else {
            $('.brand').removeClass("nk_sticky");
            $('.nav-top.nk_menu').removeClass("hidepill");
            $('.nav-top.nk_menu').addClass("showpill");
            $('.stickyhide').removeClass("hidepill");
            $('.brandsticky').addClass("hideblock");  
            $("#header").css({"min-height":"140px", "height":"auto !important"}); 
            $('.logo a').removeClass("logosmallsticky");
            $('.logo a img').removeClass("logosmallimg");
            $(".btn.btn-responsive-nav.btn-inverse").css({"top":"15px"});
        }
      });
    });
    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 10; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
</script>
<?php if(CAPTCHA_DISPLAY == 1) { ?>
<script src='https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit' async defer></script>
<script type="text/javascript">
var CaptchaCallback = function(){
    $('.recaptcha').each(function(){
        grecaptcha.render(this,{'sitekey' : '<?php echo CAPTCHA_KEY;?>'});
    })
};
</script>
<?php } ?>