<?php if($Userright->checkadminrights($Userright->LIST_CUSTOMER)){?>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 blue" href="<?php echo constant('WEB_URL').constant('ADMIN_PATH').'customers/'; ?>">
        <div class="visual">
            <i class="fa fa-comments"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="<?php echo $customer_count; ?>"><?php echo $customer_count; ?></span>
            </div>
            <div class="desc">Customers</div>
        </div>
    </a>
</div>
<?php }?>
<?php if($Userright->checkadminrights($Userright->LIST_PROJECT)){?>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 red" href="<?php echo constant('WEB_URL').constant('ADMIN_PATH').'projects/'; ?>">
        <div class="visual">
            <i class="fa fa-bar-chart-o"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="<?php echo $project_count; ?>"><?php echo $project_count; ?></span></div>
            <div class="desc"> Projects </div>
        </div>
    </a>
</div>
<?php }?>
<?php if($Userright->checkadminrights($Userright->LIST_INSTALLER)){?>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 green" href="<?php echo constant('WEB_URL').constant('ADMIN_PATH').'InstallerCompanies/'; ?>">
        <div class="visual">
            <i class="fa fa-shopping-cart"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="<?php echo $installers_count; ?>"><?php echo $installers_count; ?></span>
            </div>
            <div class="desc"> Installers </div>
        </div>
    </a>
</div>
<?php } ?>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 green yellow" href="<?php echo constant('WEB_URL').constant('ADMIN_PATH').'applyOnlines/'; ?>">
        <div class="visual">
            <i class="fa fa-file-text"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="<?php echo $TotalAplication; ?>"><?php echo $TotalAplication; ?></span>
            </div>
            <div class="desc"> Application </div>
        </div>
    </a>
</div>
<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption ">
                <span class="caption-subject font-dark bold uppercase">Project Typewise</span>
                <span class="caption-helper">Till date stats...</span>
            </div>
            <div class="actions">&nbsp;</div>
        </div>
        <div class="portlet-body">
            <div id="ctypewise"></div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption ">
                <span class="caption-subject font-dark bold uppercase">Projects Trend</span>
                <span class="caption-helper">Till Month (Current Year) stats...</span>
            </div>
            <div class="actions">&nbsp;</div>
        </div>
        <div class="portlet-body">
            <div id="projectrend"></div>
        </div>
    </div>
</div>
<?php if($Userright->checkadminrights($Userright->LIST_PROJECT)){?>
<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">Projects Activity</span>
                <span class="caption-helper">Today stats...</span>
            </div>
            <div class="actions">&nbsp;</div>
        </div>
        <div class="portlet-body">
            <div class="row number-stats margin-bottom-30">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="stat-left">
                        <div class="stat-chart">&nbsp;</div>
                        <div class="stat-number">
                            <div class="title"> Total </div>
                            <div class="number"> <?php echo $project_count?> </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="stat-right">
                        <div class="stat-chart">&nbsp;</div>
                        <div class="stat-number">
                            <div class="title"> New </div>
                            <div class="number"> <?php echo (!empty($RegisteredProjectsCount) ? $RegisteredProjectsCount : 0);?> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-scrollable table-scrollable-borderless widget-h200">
                <table class="table table-hover table-light">
                    <thead>
                        <tr class="uppercase">
                            <th> NAME </th>
                            <th> MOBILE </th>
                            <th> CITY </th>
                            <th> CUSTOMER TYPE </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!empty($TodaysProjects)) {
                                foreach ($TodaysProjects as $TodaysProject) {
                                    echo "<tr>";
                                    echo "<td>".$TodaysProject['name']."</td>";
                                    echo "<td>".$TodaysProject['mobile']."</td>";
                                    echo "<td>".$TodaysProject['city']."</td>";
                                    echo "<td>".$TodaysProject['customer_type']."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<td colspan=\"4\">No Project submitted.</td>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php if($Userright->checkadminrights($Userright->LIST_CUSTOMER)){?>
<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">Member Activity</span>
                <span class="caption-helper">Today stats...</span>
            </div>
            <div class="actions">&nbsp;</div>
        </div>
        <div class="portlet-body">
            <div class="row number-stats margin-bottom-30">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="stat-left">
                        <div class="stat-chart">&nbsp;</div>
                        <div class="stat-number">
                            <div class="title"> Total </div>
                            <div class="number"> <?php echo $customer_count?> </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="stat-right">
                        <div class="stat-chart">&nbsp;</div>
                        <div class="stat-number">
                            <div class="title"> New </div>
                            <div class="number"> <?php echo (!empty($TodaysCustomerCount)?$TodaysCustomerCount:0);?> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-scrollable table-scrollable-borderless widget-h200">
                <table class="table table-hover table-light">
                    <thead>
                        <tr class="uppercase">
                            <th> NAME </th>
                            <th> EMAIL </th>
                            <th> MOBILE </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!empty($TodaysCustomer)) {
                                foreach ($TodaysCustomer as $Customer) {
                                    echo "<tr>";
                                    echo "<td>".$Customer['name']."</td>";
                                    echo "<td>".$Customer['email']."</td>";
                                    echo "<td>".$Customer['mobile']."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<td colspan=\"3\">No Customer Registered for Today.</td>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="clearfix"></div>
<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption ">
                <span class="caption-subject font-dark bold uppercase">Apply Online</span>
                <span class="caption-helper">Till date stats...</span>
            </div>
            <div class="actions">&nbsp;</div>
        </div>
        <div class="portlet-body">
            <div id="applhistory"></div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-xs-12 col-sm-12">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption ">
                <span class="caption-subject font-dark bold uppercase">Apply Online</span>
                <span class="caption-helper">Till date stats...</span>
            </div>
            <div class="actions">&nbsp;</div>
        </div>
        <div class="portlet-body">
            <div class="portlet-body">
            <div class="row number-stats margin-bottom-30">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="stat-left">
                        <div class="stat-chart">&nbsp;</div>
                        <div class="stat-number">
                            <div class="title"> Total </div>
                            <div class="number"> <?php echo $TotalAplication; ?> </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="stat-right">
                        <div class="stat-chart">&nbsp;</div>
                        <div class="stat-number">
                            <div class="title"> New </div>
                            <div class="number"> <?php echo (!empty($TodaysAplicationCount)? $TodaysAplicationCount :0);?> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-scrollable table-scrollable-borderless widget-h200">
                <table class="table table-hover table-light">
                    <thead>
                        <tr class="uppercase">
                            <th> CUSTOMER NAME</th>
                            <th> EMAIL </th>
                            <th> MOBILE </th>
                            <th> PV CAPACITY </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!empty($TodaysAplication)) {
                                foreach ($TodaysAplication as $Customer) {
                                    echo "<tr>";
                                        echo "<td>".$Customer['name_of_consumer_applicant']."</td>";
                                    echo "<td>".$Customer['email']."</td>";
                                    echo "<td>".$Customer['mobile']."</td>";
                                        echo "<td>".$Customer['pv_capacity']."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan=\"4\">No Aplication for Today.</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(DisplayTypeWiseProject);
   
    function DisplayTypeWiseProject() {
        var data = google.visualization.arrayToDataTable([
            ['Projects Type', 'No. Of Projects'],
            <?php
                if (!empty($getTypewiseProjects)) {
                    foreach ($getTypewiseProjects as $type => $count) {
                        echo "['".$type."',".$count."],";
                    }
                }
            ?>
        ]);

        var view = new google.visualization.DataView(data);
        var options = {
            title: "",
            width: '100%',
            height:'100%',
            bar: {groupWidth: "70%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("ctypewise"));
        chart.draw(view, options);
    }

    google.charts.setOnLoadCallback(DisplayProjectTrend);
    function DisplayProjectTrend() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'No. Of Projects'],
            <?php
                if (!empty($ProjectHistoryCurrentYear)) {
                    foreach ($ProjectHistoryCurrentYear as $Month => $count) {
                        echo "['".$Month."',".$count."],";
                    }
                }
            ?>
        ]);

        var view = new google.visualization.DataView(data);
        var options = {
            title: "",
            width: '100%',
            height:'100%',
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("projectrend"));
        chart.draw(view, options);
    }

    google.charts.setOnLoadCallback(DisplayTypeWiseAplication);
    function DisplayTypeWiseAplication() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'No. Of Aplication'],
            <?php
                if (!empty($getApplHistoryCurrentYear)) {
                    foreach ($getApplHistoryCurrentYear as $type => $count) {
                        echo "['".$type."',".$count."],";
                    }
                }
            ?>
        ]);

        var view = new google.visualization.DataView(data);
        var options = {
            title: "",
            width: '100%',
            height:'100%',
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("applhistory"));
        chart.draw(view, options);
    }
</script>