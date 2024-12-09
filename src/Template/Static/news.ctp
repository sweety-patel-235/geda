<?php
$this->Html->addCrumb($pageTitle);
?>
<div class="container">
    <div class="rss-feed">
        <?php
        if (isset($news->channel->item) && !empty($news->channel->item)) {
            foreach($news->channel->item as $item) {
                if ($item->title == "This RSS feed URL is deprecated") continue;
                preg_match('@src="([^"]+)"@', $item->description, $match);
                $parts = explode('<font size="-1">', $item->description);
                $description = isset($parts[2])?strip_tags($parts[2]):"";
            ?>
            <div class="rss-row">
                <div class="news-title"><?php echo $item->title; ?></div>
                <p class="news-date"><?php echo $item->pubDate; ?></p>
                <div class="row">
                    <?php if(isset($match[1])){ ?>
                    <div class="col-md-1">
                        <div class="news-image"><img src="<?php echo @$match[1]; ?>" /></div>
                    </div>
                    <?php } ?>
                    <div class="col-md-10">        
                        <div class="news-desc"><?php echo $description; ?></div>
                        <div><a class="news-link" href="<?php echo $item->link; ?>" target="_blank">View More <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php } ?>
        <?php } ?>
        <div class="rss-row">
            <div class="news-title">Top India Solar and Power Related Apps for Download</div>
            <p class="news-date">NOV 22, 2017</p>
            <div class="row">
                <div class="col-md-1">
                    <div class="news-image"><img width="63px" src="http://www.ahasolar.in/img/frontend/logo.png" /></div>
                </div>
                <div class="col-md-10">        
                    <div class="news-desc">
                        <strong>AHA Solar Rooftop Helper:</strong> This app is a platform that estimates the cost of installing a solar PV rooftop system using factors such as the estimated system size to help determine the approximate cost, applicable government incentives, and financing options. It also connects users with information about nearby rooftop solar PV installers and provides them with information on the prerequisites needed to construct a solar rooftop system. The platform is designed to connect end consumers with solar PV installers while offering information about solar power and customized solutions for those interested in adopting solar technology.<br />
                        This app is available on <a href="https://play.google.com/store/apps/details?id=com.energy.ahasolar&amp;hl=en" target="_blank" rel="noopener">Android</a> and on the <a href="http://www.ahasolar.in/solar-pv-installer" target="_blank" rel="noopener">Web</a>
                    </div>
                    <div><a class="news-link" href="https://mercomindia.com/india-solar-power-apps/" target="_blank">View More <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>