<?php
	$this->Html->addCrumb($pageTitle);
?>
<style type="text/css">
	fieldset
	{
		border: 1px solid #ddd !important;
		margin: 0;
		xmin-width: 0;
		padding: 10px;
		position: relative;
		border-radius:4px;
		background-color:#f5f5f5;
		padding-left:10px!important;
	}
	legend
	{
		font-size:14px;
		font-weight:bold;
		margin-bottom: 0px;
		width: 35%;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 5px 5px 5px 10px;
		background-color: #ffffff;
	}
	#DisplayMonthwiseStats {
		display: block;
		margin: 0 auto;
	}
	#DisplayDaywiseCapacityStats {
		display: block;
		margin: 0 auto;
	}
	#DisplayDaywiseApplicationStats {
		display: block;
		margin: 0 auto;
	}
</style>
<div class="container">
	<div>
		<fieldset>
    	<legend>Total of PV Capacity & No of Application</legend>
			<div class="row">
				<?php echo $this->Form->create("form-main",array('type'=>'post','id'=>'form-main')); ?>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-3 form-group text">&nbsp;</div>
						<div class="col-md-2 form-group text">
							<?php echo $this->Form->select('Year',$arrYears,array('id'=>'year','label' => false,'class'=>'form-control','empty'=>'-Select Year-','value'=>$selectedyear)); ?>
						</div>
						<div class="col-md-3 form-group text">
							<?php echo $this->Form->select('discom',$arrDiscoms,array('id'=>'discom','label' => false,'class'=>'form-control','empty'=>'-All Discom-','value'=>$selecteddiscom)); ?>
						</div>
						<div class="col-md-2 form-group text">
							<?php echo $this->Form->input('Search', array('label' => false,'type'=>'submit','name'=>'Search','class'=>'next btn btn-primary','value'=>'Search','div'=>false)); ?>
						</div>
						<div class="col-md-2 form-group text">&nbsp;</div>
					</div>
				</div>
				<?php echo $this->Form->end();?>
			</div>
			<div class="row"><div class="col-md-12 text-center"><b>Overlap Graph of Total of PV Capacity (in kW) & No of Application</b></div></div>
			<div class="col-md-12 text-center">
				<div id="DisplayMonthwiseStats"></div>
			</div>
		</fieldset>
	</div>
	<div class="row"><hr /></div>
	<div>
		<fieldset>
    		<legend>Daywise PV Capacity & No of Application</legend>
			<div class="row">
				<?php echo $this->Form->create("form-main-1",array('type'=>'post','id'=>'form-main-1')); ?>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-1 form-group text">&nbsp;</div>
						<div class="col-md-2 form-group text">
							<?php echo $this->Form->select('Month_1',$arrMonths,array('id'=>'month_1','label' => false,'class'=>'form-control','empty'=>'-Select Year-','value'=>intval($selectedyear_1))); ?>
						</div>
						<div class="col-md-2 form-group text">
							<?php echo $this->Form->select('Year_1',$arrYears,array('id'=>'year_1','label' => false,'class'=>'form-control','empty'=>'-Select Year-','value'=>$selectedyear_1)); ?>
						</div>
						<div class="col-md-3 form-group text">
							<?php echo $this->Form->select('discom_1',$arrDiscoms,array('id'=>'discom_1','label' => false,'class'=>'form-control','empty'=>'-All Discom-','value'=>$selecteddiscom_1)); ?>
						</div>
						<div class="col-md-2 form-group text">
							<?php echo $this->Form->input('Search', array('label' => false,'type'=>'submit','name'=>'Search','class'=>'next btn btn-primary','value'=>'Search','div'=>false)); ?>
						</div>
						<div class="col-md-1 form-group text">&nbsp;</div>
					</div>
				</div>
				<?php echo $this->Form->end();?>
			</div>
			<div class="row"><div class="col-md-12 text-center"><b>Daywise PV Capacity (in kW)</b></div></div>
			<div class="row">&nbsp;</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<div id="DisplayDaywiseCapacityStats"></div>
				</div>
			</div>
			<div class="row">&nbsp;</div>
			<div class="row"><div class="col-md-12 text-center"><b>Daywise No of Application</b></div></div>
			<div class="row">&nbsp;</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<div id="DisplayDaywiseApplicationStats"></div>
				</div>
			</div>
		</fieldset>
	</div>
</div>
<script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#year").val("<?php echo $selectedyear;?>");
		$("#discom").val("<?php echo $selecteddiscom;?>");
		$("#year_1").val("<?php echo $selectedyear_1;?>")
		$("#discom_1").val("<?php echo $selecteddiscom_1;?>")
		$("#month_1").val("<?php echo intval($selectedmonth_1);?>")
	});
	google.charts.load("current", {packages:['corechart']});
	google.charts.setOnLoadCallback(DisplayMonthwiseStats);
	function DisplayMonthwiseStats()
	{
		var data = google.visualization.arrayToDataTable([
			['Month','Application','Capacity (in kW)'],
			<?php foreach($MonthWiseStatistics as $MonthRow) { ?>
				<?php echo "['".$MonthRow['Month']."',".$MonthRow['App_Count'].",".$MonthRow['App_Capacity']."],";?>
			<?php } ?>
		  ]);
		var options = {
			title: 'Overlap Graph of Total of PV Capacity (in kW) & No of Application',
			/*hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},*/
			vAxis: {title: 'PV Capacity (in kW) & Applications',viewWindow: { min:0 },gridlines: { count: 5 }},
			colors: ['#58a8c4','#ed7d31'],
			pointSize: 5,
			legend: { position: "bottom", maxLines: 3 },
			titlePosition: 'none',
		};
		var view = new google.visualization.DataView(data);
		var chart = new google.visualization.AreaChart(document.getElementById("DisplayMonthwiseStats"));
		chart.draw(view, options);
	}
	google.charts.setOnLoadCallback(DisplayDaywiseCapacityStats);
	function DisplayDaywiseCapacityStats()
	{
		var data = google.visualization.arrayToDataTable([
			['Day','Capacity (in kW)'],
			<?php foreach($arrDaywiseCapacity as $Day=>$Capacity) { ?>
				<?php echo "[".$Day.",".$Capacity."],";?>
			<?php } ?>
		  ]);
		var options = {
			title: 'Daywise PV Capacity (in kW)',
			vAxis: {title: 'PV Capacity (in kW)',viewWindow: { min:0 },gridlines: { count: 5 } },
			pointSize: 5,
			legend: { position: "bottom", maxLines: 3 },
			titlePosition: 'none',
		};
		var view = new google.visualization.DataView(data);
		var chart = new google.visualization.AreaChart(document.getElementById("DisplayDaywiseCapacityStats"));
		chart.draw(view, options);
	}
	google.charts.setOnLoadCallback(DisplayDaywiseApplicationStats);
	function DisplayDaywiseApplicationStats()
	{
		var data = google.visualization.arrayToDataTable([
			['Day','Applications'],
			<?php foreach($arrDaywiseApplication as $Day=>$App_Count) { ?>
				<?php echo "[".$Day.",".$App_Count."],";?>
			<?php } ?>
		  ]);
		var options = {
			title: 'Daywise Application',
			vAxis: {title: 'Total Application',viewWindow: { min:0 },gridlines: { count: 5 }},
			pointSize: 5,
			legend: { position: "bottom", maxLines: 3 },
			titlePosition: 'none',
		};
		var view = new google.visualization.DataView(data);
		var chart = new google.visualization.ColumnChart(document.getElementById("DisplayDaywiseApplicationStats"));
		chart.draw(view, options);
	}
</script>