<?php $this->Html->addCrumb($pageTitle); ?>
<style>
.cluster-1{
    background-image:url(/img/mapIcons/m1.png);
    line-height:53px;
    width: 53px;
    height: 52px;
  }
  .cluster-2{
    background-image:url(/img/mapIcons/m2.png);
    line-height:53px;
    width: 56px;
    height: 55px;
  }
  .cluster-3{
    background-image:url(/img/mapIcons/m3.png);
    line-height:66px;
    width: 66px;
    height: 65px;
  }
  .cluster-4{
    background-image:url(/img/mapIcons/m4.png);
    line-height:66px;
    width: 66px;
    height: 65px;
  }
  .cluster-5{
    background-image:url(/img/mapIcons/m5.png);
    line-height:66px;
    width: 66px;
    height: 65px;
  }
.cluster {
    color: #FFFFFF;
    text-align:center;
    font-size:11px;
    font-weight:bold;
  }
  .details-padding
  {
    padding-right:2px !important;
  }
  .details-padding-capacity
  {
    padding-right:11px !important;
  }
</style>
<div class="container dashboard-theme">
    <div class="row_back">
        <?php
        if (BLOCK_APPLICATION == 1)
        {
        ?>
            <div class="alert alert-warning">
                <strong>Notice!</strong>
                <ul>
                    <li style="font-size: 1.0em;"><?php echo BLOCK_APPLICATION_MESSAGE;?></li>
                </ul>
            </div>
        <?php
        }
        ?>
        <!-- Show Counters Row Start-->
        <div class="row">
            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                <form id="form-main" name="form-main" action="/apply-online-list" method="post">
                    <?php echo $this->Form->input('status', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'')); ?>
                    <?php echo $this->Form->input('Search', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'Search')); ?>
                    <fieldset>
                        <legend>Total Application Submitted (GEDA)</legend>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green submitted" href="javascript:;">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details details-padding">
                                    <div class="center desc">Total Application Submitted</div>
                                    <div class="center number">
                                        <span data-counter="counterup" data-value="<?php echo $GrandTotalApplicationSubmitted; ?>">
                                            <?php echo $GrandTotalApplicationSubmitted; ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green submitted" href="javascript:;">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details details-padding-capacity">
                                    <div class="center desc">Total Capacity Submitted</div>
                                    <div class="center number">
                                        <span data-counter="counterup" data-value="<?php echo $GrandTotalSubmittedPVCapacity; ?>">
                                            <?php echo $GrandTotalSubmittedPVCapacity; ?>
                                        </span>
                                    </div>
                                    <div class="center desc">kWp</div>
                                </div>
                            </a>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Installer Statistics</legend>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green submitted" href="javascript:;" data-id="<?php echo $arrStatusCode['APPLICATION_SUBMITTED'];?>">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $TotalApplicationSubmitted; ?>">
                                            <?php echo $TotalApplicationSubmitted; ?>
                                        </span>
                                    </div>
                                    <div class="desc">
                                        Application Submitted<br />
                                        <?php echo $TotalSubmittedPVCapacity;?> kWp
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green registration" href="javascript:;" data-id="<?php echo $arrStatusCode['APPROVED_FROM_GEDA'];?>">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $TotalApplicationGEDALetter; ?>">
                                            <?php echo $TotalApplicationGEDALetter; ?>
                                        </span>
                                    </div>
                                    <div class="desc">
                                        Registration<br />
                                        <?php echo $TotalGEDALetterPVCapacity;?> kWp
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green verified" href="javascript:;" data-id="<?php echo $arrStatusCode['DOCUMENT_VERIFIED'];?>">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $TotalApplicationVerified; ?>">
                                            <?php echo $TotalApplicationVerified; ?>
                                        </span>
                                    </div>
                                    <div class="desc">
                                        Documents Verified<br />
                                        <?php echo $TotalVerifiedPVCapacity;?> kWp
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green verification-pending" href="javascript:;" data-id="<?php echo $arrStatusCode['DOCUMENT_NOT_VERIFIED'];?>">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $TotalApplicationNotVerified; ?>">
                                            <?php echo $TotalApplicationNotVerified; ?>
                                        </span>
                                    </div>
                                    <div class="desc">
                                        Docs. Verification Pending<br />
                                        <?php echo $TotalNotVerifiedPVCapacity;?> kWp
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green cancelled" href="javascript:;" data-id="<?php echo $arrStatusCode['APPLICATION_CANCELLED'];?>">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $TotalApplicationRejected; ?>">
                                            <?php echo $TotalApplicationRejected; ?>
                                        </span>
                                    </div>
                                    <div class="desc">
                                        Application Cancelled<br />
                                        <?php echo $TotalRejectedPVCapacity;?> kWp
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green meter-installed" href="javascript:;" data-id="<?php echo $arrStatusCode['METER_INSTALLATION'];?>">
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $TotalApplicationMeterInstalled; ?>">
                                            <?php echo $TotalApplicationMeterInstalled; ?>
                                        </span>
                                    </div>
                                    <div class="desc">
                                        Meter Installed<br />
                                        <?php echo $TotalMeterInstalledPVCapacity;?> kWp
                                    </div>
                                </div>
                            </a>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Apply Online</span>
                            <span class="caption-helper">Till date stats...</span>
                        </div>
                        <div class="actions">&nbsp;</div>
                    </div>
                    <div class="portlet-body">
                        <div id="DisplayMonthwiseStats"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Show Counters Row End-->
        <!-- Show Project Cluster Start-->
        <?php /*
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Apply Online Across Gujarat State</span>
                            <span class="caption-helper">Till Date ...</span>
                        </div>
                        <div class="actions">&nbsp;</div>
                    </div>
                    <div class="portlet-body">
                        <div id="dvMap" class="gmap3" style="width:100%;height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        */?>
        <!-- Show Project Cluster ends-->

    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(DisplayMonthwiseStats);
    function DisplayMonthwiseStats()
    {
        var data = google.visualization.arrayToDataTable([
            [<?php echo $CHART_HEADER;?>, { role: 'annotation' } ],
            <?php foreach($MonthWiseStatistics as $MonthRow) { ?>
                <?php echo "[".implode(",",$MonthRow).",''],";?>
            <?php } ?>
          ]);
        var view = new google.visualization.DataView(data);
        var options = {
            title: "",
            width: '100%',
            height:'100%',
            // hAxis: {
            //   title: 'Month'
            // },
            vAxis: {
                title: 'Total Application By Month', 
                viewWindow: {
                    min:0
                }
            },
            bar: {groupWidth: "60%"},
            colors: ['#58a8c4','#ed7d31','#7f7f7f','#ffc000','#2e75b6'],
            legend: { position: "bottom", maxLines: 3 },
            //isStacked: true,
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("DisplayMonthwiseStats"));
        chart.draw(view, options);
    }
</script>
<?php /*script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY;?>" type="text/javascript"></script>
<script type="text/javascript" src="/js/gmap/gmap3.js"></script>*/?>
<script type="text/javascript">
    var CustomerList = <?php echo json_encode($getProjectClusterData['data']);?>;
    $(document).ready(function() {
        $(".dashboard-stat").click(function(){
            var $Application_Status = $(this).data("id");
            $("#form-main").find("#status").val($Application_Status);
            $("#form-main").trigger("submit");
            return false;
        });
        var colors = <?php echo json_encode($getProjectClusterData['map_icons']);?>;
        $(function(){
    
            // create colors checkbox and associate onChange function 
            /*
            $.each(colors, function(i, color) {
                $("#colors").append("<input lbl-name='"+color.lbl+"' type='checkbox' name='"+color.group+"' checked><label for='"+color.lbl+"'>"+color.group+"</label>");
            });
            */
            $("#colors input[type=checkbox]").change(onChangeChk);
            $("#onOff").change(onChangeOnOff); 
            
            
            // create gmap3 and call the marker generation function  
            /*$('#dvMap').gmap3({
              map:{
                options: {
                  zoom: 12,
                  center:[23.0221,72.5721],
                  mapTypeId: google.maps.MapTypeId.TERRAIN,
                  panControl: true,
                      panControlOptions: {
                      position: google.maps.ControlPosition.RIGHT_TOP
                    },
                    zoomControl: true,
                    zoomControlOptions: {
                      style: google.maps.ZoomControlStyle.LARGE,
                      position: google.maps.ControlPosition.RIGHT_TOP
                    }
                },
                onces: {
                  bounds_changed: function(){
                    ShowMarkers();
                  }
                }
              }
            });*/
        });
      
      function ShowMarkers() 
      {
        // call the clustering function
        $('#dvMap').gmap3({
            marker :{   values : CustomerList,
                        cluster: {
                                    radius: 200, 
                                    // This style will be used for clusters with more than 0 markers
                                    0: {
                                        content: '<div class="cluster cluster-1">CLUSTER_COUNT</div>',
                                        width: 53,
                                        height: 52
                                    },
                                    // This style will be used for clusters with more than 20 markers
                                    20: {
                                        content: '<div class="cluster cluster-2">CLUSTER_COUNT</div>',
                                        width: 56,
                                        height: 55
                                    },
                                    // This style will be used for clusters with more than 50 markers
                                    50: {
                                        content: '<div class="cluster cluster-3">CLUSTER_COUNT</div>',
                                        width: 66,
                                        height: 65
                                    },
                                    // This style will be used for clusters with more than 100 markers
                                    100: {
                                        content: '<div class="cluster cluster-4">CLUSTER_COUNT</div>',
                                        width: 66,
                                        height: 65
                                    },
                                    // This style will be used for clusters with more than 150 markers
                                    150: {
                                        content: '<div class="cluster cluster-5">CLUSTER_COUNT</div>',
                                        width: 66,
                                        height: 65
                                    },
                                    events: {
                                            click: function(cluster) {
                                                var map = $(this).gmap3("get");
                                                map.setCenter(cluster.main.getPosition());
                                                map.setZoom(map.getZoom() + 1);
                                            }
                                    }
                                }
                    }
            });
        }
      
        function onChangeOnOff() {
            if ($(this).is(":checked")){
                $('#dvMap').gmap3({get:"clusterer"}).enable();
            } else {
                $('#dvMap').gmap3({get:"clusterer"}).disable();
            }
        }
        function onChangeChk(){
            // first : create an object where keys are colors and values is true (only for checked objects)
            var checkedColors = {};
            $("#colors input[type=checkbox]:checked").each(function(i, chk) {
                checkedColors[$(chk).attr("lbl-name")] = true;
            });

            // set a filter function using the closure data "checkedColors"
            $('#dvMap').gmap3({get:"clusterer"}).filter(function(data){
                return data.tag in checkedColors;
            });
        }
    });
</script>