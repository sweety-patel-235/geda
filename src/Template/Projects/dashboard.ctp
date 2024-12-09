<?php
    $this->Html->addCrumb('Projects',"/project");
    $this->Html->addCrumb($pageTitle);

    /*Energy Month Chart Data Create*/
    $monthChart = '';
    $MonthArr = array(1=>'Jan',2=>'Fab',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
    if(isset($resultArr['monthArr_FE']) && !empty($resultArr['monthArr_FE'])) {
        $monthChartData =  ($resultArr['monthArr_FE']);
        foreach($monthChartData as $data) {
            //$dateObj   = DateTime::createFromFormat('!m', $data->x);
            $monthName = (isset($MonthArr[$data['x']])?$MonthArr[$data['x']]:'');
            $monthChart .= "['".$monthName."',".$data['y']."],";
        }
        $monthChart = rtrim($monthChart,",");
    }

    /*Energy Year Chart Data Create*/
    $yearChart = '';
    if(isset($payBackGraphData) && !empty($payBackGraphData)) {
        foreach($payBackGraphData as $year => $data) {
            $yearChart .= "['".$year."',".$data."],";
        }
        $yearChart = rtrim($yearChart,",");
    }
?>
<style>
.rowcat .col-md-6 {
    border: 1px solid #c1c1c1;
}
.rowcat .control-label {
    text-align: right;
}
.rowcat1 .row {
    border: 1px solid #c1c1c1;
    padding: 7px;
}
</style>

<div class="clearfix"></div>
<?php if( $this->Session->read('Customers.customer_type') == 'installer') { ?>
    <div class="bottom-status-btns">
        <div class="col-sm-12">
            <div class="claim-report-status greenbg">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#" class="showModel" data-title="Site Survey" data-url="<?php echo URL_HTTP; ?>project/sitesurvey/<?php echo encode($project->project_id)?>">
                        <span>
                            <p>Site Survey</p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="claim-report-status lightblue">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#" class="addStructure showModel" data-title="Commercial" data-url="<?php echo URL_HTTP; ?>project/commercial/<?php echo encode($project->project_id)?>">
                        <span>
                            <p> Commercial </p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="claim-report-status light-red">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#" class="addRecovery showModel" data-title="Terms and Condition" data-url="<?php echo URL_HTTP; ?>project/termscondition/<?php echo encode($project->project_id)?>">
                        <span>
                            <p>Terms and Condition</p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="claim-report-status light-blue1">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#"  class="lastbtn addPfi showModel" data-title="Proposal" data-url="<?php echo URL_HTTP; ?>project/proposal/<?php echo encode($project->project_id)?>">
                        <span>
                            <p>Proposal</p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="claim-report-status inactivebg drying darkblue">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#" class="addDrying showModel" data-title="Work Order" data-url="<?php echo URL_HTTP; ?>project/workorder/<?php echo encode($project->project_id)?>">
                        <span class="">
                            <p>Work Order</p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="claim-report-status inactivebg healthsafety darkgreen">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#" class="showModel" data-title="Execution" data-url="<?php echo URL_HTTP; ?>project/execution/<?php echo encode($project->project_id)?>">
                        <span class="">
                            <p>Execution</p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="claim-report-status inactivebg darkcoffee">
                <div class="main-full-claim-report">
                    <div class="title-full-claim-report">
                        <a href="#" class="showModel" data-title="Commissioning" data-url="<?php echo URL_HTTP; ?>project/commissioning/<?php echo encode($project->project_id)?>">
                        <span class="">
                            <p>Commissioning</p>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="container">
    <div class="row border-style">
        <?php if( $this->Session->read('Customers.customer_type') == 'installer') { ?>
        <div class="col-md-12">
            <div class="pull-right">
                <div class="actions-list">
                    <ul>
                        <li>
                            <a href="/projects/sitesurveylist/<?php echo $project_en_id?>">
                                <button type="button" class="btn-style">
                                    <span>Site Survey List</span>
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="/project/downloadreport">
                                <button type="button" class="btn-style">
                                <span>Generate Draft Report</span>
                                </button>
                            </a>
                        </li>
                        <li>
                            <button type="button" class="btn-style">
                            <span>Genrate Final Report</span>
                            </button>
                        </li>
                        <li>
                            <a href="/project/reportdata/<?php echo $project_en_id?>">
                                <button type="button" class="btn-style">
                                <span>Add Additional Data</span>
                                </button>
                            </a>
                        </li>
						<li>
                            <a href="#">
                                <button type="button" class="btn-style">
                                <span>Location</span>
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <!--- <div class="clearfix"></div>-->
	<div class="row">
	<!---
	 <div class="col-sm-12" style="padding: 15px;">
                    <?php
                    $latLng = $project['project']->latitude.",".$project['project']->longitude;
                    $mapUrl = base64_encode(file_get_contents('https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=12&size=150x225&markers=color:red%7C'.$latLng.'&sensor=false'));
                    ?>
                    <img alt="Opps! Map not show proper, please refress page Or check Lat Long" src="data:image/png;base64,<?php echo $mapUrl; ?>" />
                </div>
				-->
	</div>

	<div class="clearfix"></div>
    <div class="row row-eq-height">
        <div class="col-sm-6">
            <div class="client-info-div box-border-style">
               
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <h4>Project Information</h4>
                    </div>
                    <div class="col-sm-12">
                        <table class="table projectInfo">
                            <tbody>
                                <tr>
                                    <th>Project Name <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->name?> </td>
                                </tr>
                                <tr>
                                    <th>Address <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->address?></td>
                                </tr>
                                <tr>
                                    <th>City <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->city?></td>
                                </tr>
                                <tr>
                                    <th>State <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->state?></td>
                                </tr>
                                <tr>
                                    <th>Country <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->country?></td>
                                </tr>
                                <tr>
                                    <th>PinCode <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->pincode?></td>
                                </tr>
                                <tr>
                                    <th>Rooftop Area <span class="pull-right">:</span></th>
                                    <td><?php echo $project['project']->area; ?>  
                                        <?php 
                                        $arearType = (isset($project['project']->area_type)?$project['project']->area_type:'');
                                        echo (!empty($arearType) && isset($areaTypeArr[$arearType])) ? $areaTypeArr[$arearType] : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Average Monthly Electricity Consumption<span class="pull-right">:</span></th>
                                    <td><?php echo (isset($project['project']->estimated_kwh_year)?$project['project']->estimated_kwh_year:'0')." kWh"; ?></td>
                                </tr>
                                <tr>
                                    <th>Average Monthly Electricity Bill<span class="pull-right">:</span></th>
                                    <td><?php echo (isset($project['project']->avg_monthly_bill)?$project['project']->avg_monthly_bill:'0')." Rs"; ?></td>
                                </tr>
                                <tr>
                                    <th>Backup Type<span class="pull-right">:</span></th>
                                    <td><?php 
                                        $backupType = (isset($project['project']->backup_type)?$project['project']->backup_type:'');
                                        echo (!empty($backupType) && isset($backupTypeArr[$backupType])) ? $backupTypeArr[$backupType] : ' No '; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hours of Usage<span class="pull-right">:</span></th>
                                    <td><?php echo (!empty($project['project']->usage_hours)?$project['project']->usage_hours:'0'). " Hours/day"; ?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="ahasolar-info-div box-border-style">
                <div class="col-sm-12">
                    <h4>Customer Information</h4>
                </div>
                <div class="col-sm-12">
                    <table class="table projectInfo">
                        <tbody>
                            <tr>
                                <th>Customer Type <span class="pull-right">:</span></th>
                                <td><?php 
                                    $customerType = (isset($project['project']->customer_type)?$project['project']->customer_type:'');
                                    echo (!empty($customerType) && isset($custTypeArr[$customerType])) ? $custTypeArr[$customerType] : ' No '; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Name <span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['name'])?$customerData['name']:'-'); ?></td>
                            </tr>
                            <tr>
                                <th>Designation <span class="pull-right">:</span></th>
                                <td><?php echo (!empty($customerData['designation'])?$customerData['designation']:'-'); ?></td>
                            </tr>
                            <tr>
                                <th>Mobile No<span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['mobile'])?$customerData['mobile']:'-'); ?></td>
                            </tr>
                            <tr>
                                <th>Landline No<span class="pull-right">:</span></th>
                                <td><?php echo (!empty($customerData['landline'])?$customerData['landline']:'-'); ?></td>
                            </tr>
                            <tr>
                                <th>Email<span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['email'])?$customerData['email']:''); ?></td>
                            </tr>
                            <tr>
                                <th>Address <span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['address1']) ? $customerData['address1']." ".$customerData['address2']." ".$customerData['address3']:''); ?></td>
                            </tr>
                            <tr>
                                <th>PinCode <span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['zip'])?$customerData['zip']:''); ?></td>
                            </tr>
                            <tr>
                                <th>City <span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['city'])?$customerData['city']:''); ?></td>
                            </tr>
                            <tr>
                                <th>State <span class="pull-right">:</span></th>
                                <td><?php echo (isset($customerData['state'])?$customerData['state']:'');; ?></td>
                            </tr>                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-eq-height m-t-20">
        <div class="col-sm-6">
            <div class="ahasolar-info-div box-border-style">
                <div class="col-sm-12">
                    <h4>Technical Information</h4>
                </div>
                <div class="col-sm-12">
                    <table class="table projectInfo">
                        <tbody>
                            <tr>
                                <th>Recomanded Capacity <span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->recommended_capacity)?$project['project']->recommended_capacity:0)." kW"; ?> </td>
                            </tr>
                            <tr>
                                <th>Maximum Capacity <span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->maximum_capacity)?$project['project']->maximum_capacity:0)." kW"; ?> </td>
                            </tr>
                            <tr>
                                <th>Cost of System (In Lacs)<span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->estimated_cost)?$project['project']->estimated_cost:0)." Rs"; ?></td>
                            </tr>
                            <tr>
                                <th>Subsidy Amount (In Lacs)<span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->estimated_cost_subsidy)?$project['project']->estimated_cost_subsidy:0)." Rs"; ?></td>
                            </tr>
                            <tr>
                                <th>Average Energy Generation<span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->avg_generate)?$project['project']->avg_generate:0)." kWh/Month"; ?></td>
                            </tr>
                            <tr>
                                <th>Cost of Solar Energy<span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->cost_solar)?$project['project']->cost_solar:0)." Rs/Month"; ?></td>
                            </tr>
                            <tr>
                                <th>Payback<span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->payback)?$project['project']->payback:0)." Years"; ?></td>
                            </tr>
                            <tr>
                                <th>Approx. Saving<span class="pull-right">:</span></th>
                                <td><?php echo (isset($project['project']->estimated_saving_month)?$project['project']->estimated_saving_month:0)." Rs/Month"; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="client-info-div box-border-style">
                <div class="col-sm-12">
                    <h4>Project Accessibility  Information</h4>
                </div>
                <div class="col-sm-12">
                    <?php if(!empty($noteDataArr)){
                    ?>
                    <table class="table projectInfo">
                        <tbody>
                            <tr>
                                <th>Office Name <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['office_name']?> </td>
                            </tr>
                            <tr>
                                <th>Address <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['office_address']?> </td>
                            </tr>
                            <tr>
                                <th>rooftop_capacity <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['rooftop_capacity']?> </td>
                            </tr>
                            <tr>
                                <th>about_site <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['about_site']?> </td>
                            </tr>
                            <tr>
                                <th>road_connectivity <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['road_connectivity']?> </td>
                            </tr>
                            <tr>
                                <th>airport_connectivity <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['airport_connectivity']?> </td>
                            </tr>
                            <tr>
                                <th>port_connectivity <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['port_connectivity']?> </td>
                            </tr>
                            <tr>
                                <th>rail_connectivity <span class="pull-right">:</span></th>
                                <td><?php echo $noteDataArr['rail_connectivity']?> </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php } else { ?>
                    No Data found!
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <div id="month_data_chart" class="month_chart"></div>
            </div>
            <div class="col-md-6">
                <div id="year_data_chart" class="year_chart"></div>
            </div>
        </div>
    </div>
</div>

</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog2">
    <div class="modal-content">
        
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
$(document).on('click', '#addNote', function() {
    $('#add_projects_note_model').modal('show');
});
$("#add_project_note_form").submit(function(e) {
        var form_data = new FormData(this);
        jQuery.ajax({
            url: '<?php echo URL_HTTP."project/saveProjectNote"; ?>',
            type: 'POST',
            data:  form_data,
            dataType:  'json',
            mimeType:"multipart/form-data",
            processData: false,
            contentType: false,
            success: function(results)
            {
                if(results.status=='1') {
                location.reload();
            } else {

            }
        }
    });
    e.preventDefault();
});
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

google.charts.load("current", {packages:['corechart']});
google.charts.setOnLoadCallback(monthDrawChart);

function monthDrawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Energy', 'Month'],
    <?php echo $monthChart; ?>
    ]);
    var view = new google.visualization.DataView(data);
    var options = {
    title: "Month Energy Chart",
    width: '100%',
    height: 400,
    bar: {groupWidth: "60%"},
    colors: ['#FFCB29'],
    legend: { position: "none" },
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("month_data_chart"));
    chart.draw(view, options);
}

google.charts.setOnLoadCallback(yearDrawChart);

function yearDrawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Energy', 'Year'],
    <?php echo $yearChart; ?>
    ]);
    var view = new google.visualization.DataView(data);
    var options = {
    title: "Payback Chart",
    width: '100%',
    height: 400,
    bar: {groupWidth: "60%"},
    colors: ['#FFCB29'],
    legend: { position: "none" },
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("year_data_chart"));
    chart.draw(view, options);
}


$(".showModel").click(function(){
    
    var modelheader = $(this).data("title");
    var modelUrl = $(this).data("url");

    document_window = $(window).width() - $(window).width()*0.05;
    document_height = $(window).height() - $(window).height() * 0.20;
    
    modal_body = '<div class="modal-header" style="min-height: 45px;">'+
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
    '</div>'+
    '<div class="modal-body">'+
    '<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
    '</div>';
    
    $('#myModal').find(".modal-content").html(modal_body);
    $('#myModal').modal('show');
    $('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
    $('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
    return false;

});

window.closeModal = function(){ $('#myModal').modal('hide'); };
</script>
