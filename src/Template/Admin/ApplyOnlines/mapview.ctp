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
<div class="grid_12">
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list-ul"></i>Filter Applications
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body form">
					<!-- BEGIN FORM-->
					<?php echo $this->Form->create('ApplyOnlines',array("id"=>"formmain","url"=>ADMIN_PATH."/ApplyOnlines/mapview","name"=>"clusterview",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-2">Discom</label>
							<div class="col-md-4">
								<?php echo $this->Form->select('ApplyOnlines.discom', $Discoms,array('empty'=>'--SELECT--','label' => false ,'div'=>false,'class'=>'form-control form-control-inline'));?>
							</div>
							<label class="control-label col-md-2">Installer</label>
							<div class="col-md-4">
								<?php echo $this->Form->select('ApplyOnlines.installer_id',$Installers, array('empty'=>'--SELECT--','label' => false,'div'=>false,'class'=>'form-control form-control-inline'));?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">Category</label>
							<div class="col-md-2">
								<?php echo $this->Form->select('ApplyOnlines.category',$Categories, array('empty'=>'--SELECT--','label' => false ,'div'=>false,'class'=>'form-control form-control-inline'));?>
							</div>
							<label class="control-label col-md-2">City</label>
							<div class="col-md-2">
								<?php echo $this->Form->input('ApplyOnlines.city', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline','id'=>'customers-city'));?>
							</div>
							<label class="control-label col-md-2">Meter Installed</label>
							<div class="col-md-2">
								<?php echo $this->Form->select('ApplyOnlines.meter_installed',array(''=>'--SELECT--','1'=>'YES','0'=>'NO'), array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline'));?>
							</div>
						</div>
						<div class="form-actions">
							<div class="row">
								<div class="col-md-offset-5 col-md-6">
									<button type="submit" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Search</button>
									<button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
								</div>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>
					<!-- END FORM-->
				</div>
			</div>
		</div>
		<div class="col-md-12 content">
			<div class="portlet box blue-madison applyonline-viewmain">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-map"></i> Applyonline Map View
					</div>
					<div class="caption col-md-3">&nbsp;</div>
					<div class="caption">Total Application Count: <?php echo $ApplicationCnt;?></div>
					<div class="tools">
						<span for="onOff">Clustering</span><input type="checkbox" name="onOff" id="onOff" checked />
					</div>
				</div>
				<div class="portlet-body form">
					<div id="dvMap" class="gmap3" style="width:100%;height:600px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == "203.88.138.46") { ?>
		<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBEXOn-bf0iWn8WruvOIea8rjtppwDtOr8&sensor=false" type="text/javascript"></script>
<?php } else { ?>
		<script src="//maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY;?>&sensor=false" type="text/javascript"></script>
<?php } ?>
<script type="text/javascript" src="/js/gmap/gmap3.js"></script>
<script type="text/javascript">
		function resetsearch() {
			document.location.reload();
			return false;
		}
		var CustomerList = <?php echo json_encode($getProjectClusterData['data']);?>;
		function onChangeOnOff() {
			if ($(this).is(":checked")){
				$('#dvMap').gmap3({get:"clusterer"}).enable();
			} else {
				$('#dvMap').gmap3({get:"clusterer"}).disable();
			}
		}

		$(document).ready(function() {
				$("#onOff").change(onChangeOnOff);
				var colors = <?php echo json_encode($getProjectClusterData['map_icons']);?>;
				$(function(){
						// create gmap3 and call the marker generation function
						$('#dvMap').gmap3({
							map:{
								options: {
									zoom: 8,
									center:[23.0221,72.5721],
									mapTypeId: google.maps.MapTypeId.TERRAIN,
									panControl: false,
											panControlOptions: {
											position: google.maps.ControlPosition.RIGHT_TOP
										},
										zoomControl: false,
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
						});
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
												},
											events:{
					                              mouseover: function(marker, event, context) {
					                              	console.log(marker.getPosition());
					                                $(this).gmap3(
					                                  {clear:"overlay"},
					                                  {
					                                  overlay:{
					                                    latLng: marker.getPosition(),
					                                    options:{
					                                      content:"<div id='application-details' style='background:white;font-size:16px;'>"+
						                                                "<div onclick='javascript:$(\"#dvMap\").gmap3({clear:\"overlay\"});' style='position:absolute;right:-16px;float:right;top:-16px !important;'><i class='fa fa-close'></i></div>"+
						                                                "<table cellpadding='5'>"+
						                                                "<tr>"+
						                                                   "<td width='300' align='center' valign='middle'>"+
						                                                   		"<table cellpadding='5'>"+
						                                                   		"<tr>"+
							                                                   		"<td width='20%' align='right' valign='middle'><b>Category</b>"+
							                                                   		"<td width='80%' align='left' valign='middle'>"+context.data.Category + " (" +context.data.Capacity+ " kW)" + "</td>"+
						                                                        "</tr>"+
						                                                        "<tr>"+
							                                                   		"<td width='20%' align='right' valign='middle'><b>Application#</b>"+
							                                                   		"<td width='80%' align='left' valign='middle'>"+context.data.Application_No+"</td>"+
						                                                        "</tr>"+
						                                                        "<tr>"+
							                                                   		"<td width='20%' align='right' valign='middle'><b>Installer</b>"+
							                                                   		"<td width='80%' align='left' valign='middle'>"+context.data.Installer+"</td>"+
						                                                        "</tr>"+
						                                                        "</table>"+
						                                                    "</td>"+
						                                                "</tr>"+
						                                                "</table>"+
					                                                "</div>",
					                                        offset: { x:10, y:10 }
					                                    }
					                                  }
					                                });
					                              },
					                              mouseout: function() {
					                                //$(this).gmap3({clear:"overlay"});
					                              }
					                            }
											},
			                    });
				}
		});
</script>