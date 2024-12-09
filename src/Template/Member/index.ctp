<?php $this->Html->addCrumb($pageTitle);
$ALLOWED_APPROVE_GEDAIDS    = ALLOW_ALL_ACCESS;
?>
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
</style>
<div class="container dashboard-theme">
	<div class="row_back">
		<?php 
		if(in_array($member_id,ALLOW_DEVELOPERS_ALL_ACCESS)){ ?>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 pull-right" style="text-align:right;padding:0px 35px">
				<a href="/developer-dashboard" class="next btn btn-primary btn-sm">RE Dashboard</a>
			</div>
		</div>
		<?php } ?>
		<!-- Show Counters Row Start-->
		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
				<a href="/Test/GenerateMISReport" style="padding-right:5px;"><i class="fa fa-download"></i> MIS Report ('.$dateTime.')</a>
				<?php
				if(($DonwloadDiscomMIS || $isDonwloadMIS) && $member_id!=1435)
				{
					$dateTime 		= '';
					if($authority_account == 1) {
						$ZipFileName= MISREPORT_PATH.date("Ymd")."MISExtendedReport.zip";
						if(file_exists($ZipFileName)) {
							$dateTime   	= '<small>Generated On '.date ("d-m-Y H:i", filemtime($ZipFileName))."</small>";
						} else {
							$ZipFileName 	= MISREPORT_PATH.date("Ymd",strtotime("-1 day"))."MISExtendedReport.zip";
							if (file_exists($ZipFileName)) {
								$dateTime   = '<small>Generated On '.date ("d-m-Y H:i", filemtime($ZipFileName))."</small>";
							}
						}
					}
					if (!empty($dateTime)) {
					echo '<a href="/Reports/DownloadExtendedMISReport" style="padding-right:5px;"><i class="fa fa-download"></i> Extended MIS Report ('.$dateTime.')</a>';
					}
					$ZipFileName    = MISREPORT_PATH.date("Ymd")."MISReport".$loginArea.".zip";

					if(file_exists($ZipFileName)) {
						$dateTime   = '<small>Generated On '.date ("d-m-Y H:i", filemtime($ZipFileName))."</small>";
					} else {
						$ZipFileName    = MISREPORT_PATH.date("Ymd",strtotime("-1 day"))."MISReport".$loginArea.".zip";
						if (file_exists($ZipFileName)) {
							$dateTime   = '<small>Generated On '.date ("d-m-Y H:i", filemtime($ZipFileName))."</small>";
						}
					}
					if (!empty($dateTime)) {
						echo '<a href="/Reports/DownloadMISReport" style="padding-right:5px;"><i class="fa fa-download"></i> MIS Report ('.$dateTime.')</a>';
					}
				}
				?>
				<?php echo $this->Form->create("form-main",array('type'=>'post','id'=>'form-main', 'url' => '/apply-online-list'));?>
					<?php echo $this->Form->input('status', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'')); ?>
					<?php echo $this->Form->input('disclaimer_subsidy', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'disclaimer_subsidy')); ?>
					<?php echo $this->Form->input('pcr_code', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'pcr_code')); ?>
					<?php echo $this->Form->input('social_consumer', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'social_consumer')); ?>
					<?php echo $this->Form->input('inspection_status', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'inspection_status')); ?>
					<?php echo $this->Form->input('geda_approved_status', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'geda_approved_status')); ?>
					<?php echo $this->Form->input('approval_status', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'approval_status')); ?>
					<?php echo $this->Form->input('category[]', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'','id'=>'category',"multiple"=>'multiple')); ?>
					<?php echo $this->Form->input('Search', array('label' => false,'class'=>'form-control','type'=>'hidden','value'=>'Search')); ?>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green non_subsidy" href="javascript:;" data-id="<?php echo $arrStatusCode['NON_SUBSIDY'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationNonSubsidy; ?>">
										<?php echo $TotalApplicationNonSubsidy; ?>
									</span>
								</div>
								<div class="desc">
									Non Subsidy<br />
									<?php echo $TotalNonSubsidyInstalledPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green pcr_generated" href="javascript:;" data-id="<?php echo $arrStatusCode['PCR_GENERATED'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationPCR; ?>">
										<?php echo $TotalApplicationPCR; ?>
									</span>
								</div>
								<div class="desc">
									PCR Generated<br />
									<?php echo $TotalPCRInstalledPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green pcr_submitted" href="javascript:;" data-id="<?php echo $arrStatusCode['PCR_SUBMITTED'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationPCRSubmitted; ?>">
										<?php echo $TotalApplicationPCRSubmitted; ?>
									</span>
								</div>
								<div class="desc">
									PCR Submitted<br />
									<?php echo $TotalPCRInstalledPVCapacitySubmitted;?> kWp
								</div>
							</div>
						</a>
					</div>

					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 hide">
						<a class="dashboard-stat dashboard-stat-v2 green" href="javascript:;" data-id="<?php echo $arrStatusCode['SOCIAL_CONSUMER'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationSocial; ?>">
										<?php echo $TotalApplicationSocial; ?>
									</span>
								</div>
								<div class="desc">
									Social Sector<br />
									<?php echo $TotalSocialInstalledPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green residential_box" href="javascript:;" data-id="<?php echo $arrStatusCode['RESIDENTIAL'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationResidential; ?>">
										<?php echo $TotalApplicationResidential; ?>
									</span>
								</div>
								<div class="desc">
									Residential<br />
									<?php echo $TotalResidentialPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green industrial_commer_box" href="javascript:;" data-id="<?php echo $arrStatusCode['INS_COM'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationInsCom; ?>">
										<?php echo $TotalApplicationInsCom; ?>
									</span>
								</div>
								<div class="desc">
									Industrial + Commercial<br />
									<?php echo $TotalInsComPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green ht_industries_box" href="javascript:;" data-id="<?php echo $arrStatusCode['HT_INDUSTRIES'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationHT; ?>">
										<?php echo $TotalApplicationHT; ?>
									</span>
								</div>
								<div class="desc">
									HT Industries<br />
									<?php echo $TotalHTPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green others_box" href="javascript:;" data-id="<?php echo $arrStatusCode['OTHERS'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationOthers; ?>">
										<?php echo $TotalApplicationOthers; ?>
									</span>
								</div>
								<div class="desc">
									Others<br />
									<?php echo $TotalOthersPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<?php if(in_array($member_id,$ALLOWED_APPROVE_GEDAIDS)) { ?>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green inspection_box" href="javascript:;" data-id="<?php echo $arrStatusCode['INSPECTION'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalApplicationInspection; ?>">
										<?php echo $TotalApplicationInspection; ?>
									</span>
								</div>
								<div class="desc">
									Inspection<br />
									<?php echo $TotalInspectionPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green msme_box" href="javascript:;" data-id="<?php echo $arrStatusCode['MSME'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalMSMEPending; ?>">
										<?php echo $TotalMSMEPending; ?>
									</span>
								</div>
								<div class="desc">
									MSME Approved<br />
									<?php echo $TotalMSMEPendingPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat dashboard-stat-v2 green adr_box" href="javascript:;" data-id="<?php echo $arrStatusCode['DELETE_APP_REQUEST'];?>">
							<div class="visual">
								<i class="fa fa-file-text"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $TotalADRPending; ?>">
										<?php echo $TotalADRPending; ?>
									</span>
								</div>
								<div class="desc">
									Delete Request Pending<br />
									<?php echo $TotalADRPendingPVCapacity;?> kWp
								</div>
							</div>
						</a>
					</div>
					<?php } ?>
					<?php if (isset($UnReadMessages) && $UnReadMessages > 0) { ?>
					<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
						<a class="dashboard-stat green inspection_box showunreadmessages" href="javascript:;">
							<div class="visual">
								<i class="fa fa-comment"></i>
							</div>
							<div class="details">
								<div class="number">
									<span data-counter="counterup" data-value="<?php echo $UnReadMessages; ?>">
										<?php echo $UnReadMessages; ?>
									</span>
								</div>
								<div class="desc">Unread Messages</div>
							</div>
						</a>
					</div>
					<?php } ?>
				<?php echo $this->Form->end();?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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

<div id="unread_message" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Unread Messages</h4>
			</div>
			<?php echo $this->Form->create('UnreadMessageForm',['name'=>'UnreadMessageForm','id'=>'UnreadMessageForm']); ?>
			<div class="modal-body">
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
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
		$(".showunreadmessages").click(function() {
			$.ajax({
					type: "POST",
					url: "/apply-onlines/unreadmessages",
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.html != '') {
							$("#unread_message").find(".modal-body").html(result.html);
						}
						$("#unread_message").modal("show");
					}
				});
		});
		$(".dashboard-stat-v2").click(function(){
			var Application_Status = $(this).data("id");
			if(Application_Status=='1_N')
			{
				Application_Status  = '1';
				$("#form-main").find("#disclaimer_subsidy").val(1);
			}
			else if(Application_Status=='PCR')
			{
				Application_Status  = '1';
				$("#form-main").find("#pcr_code").val(1);
			}
			else if(Application_Status=='PCR_S')
			{
				Application_Status  = '1';
				$("#form-main").find("#pcr_code").val(3);
			}
			else if(Application_Status=='SC')
			{
				Application_Status  = '1';
				$("#form-main").find("#social_consumer").val(1);
			}
			else if(Application_Status=='3001' || Application_Status=='3002,3003' || Application_Status=='3006' || Application_Status=='3005')
			{

				$("#form-main").find("#category").val(Application_Status);
				Application_Status  = '1';

			}
			else if(Application_Status=='INSPECTION')
			{
				$("#form-main").find("#inspection_status").val(1);
				Application_Status  = '1';
			}
			else if(Application_Status=='MSME')
			{
				$("#form-main").find("#geda_approved_status").val(2);
				Application_Status  = '1';
			}
			else if(Application_Status=='DAR')
			{
				$("#form-main").find("#approval_status").val(0);
				$("#form-main").attr('action','/apply_onlines/DeleteApplicationRequestList')
				
			}
			
			$("#form-main").find("#status").val(Application_Status);
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