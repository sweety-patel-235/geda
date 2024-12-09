<?php
    $this->Html->addCrumb($pageTitle); 
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                /*Energy Month Chart Data Create*/
                $monthChart = '';
                if(isset($result['monthArr_FE']) && !empty($result['monthArr_FE'])) {
                    $monthChartData =  $result['monthArr_FE'];
                    foreach($monthChartData as $data) { 
                        $dateObj   = DateTime::createFromFormat('!m', $data['x']);
                        $monthName = $dateObj->format('M'); 
                        $monthChart .= "['".$monthName."',".$data['y']."],";
                    }
                    $monthChart = rtrim($monthChart,",");   
                } 

                /*Energy Year Chart Data Create*/
                $yearChart = '';
                if(isset($result['yearArr_FE']) && !empty($result['yearArr_FE'])) {
                    $yearChartData =  $result['yearArr_FE'];
                    foreach($yearChartData as $data) { 
                        $yearChart .= "['".$data['x']."',".$data['y']."],";
                    }
                    $yearChart = rtrim($yearChart,",");   
                }
                ?>
                <?php echo $this->Form->create('',['name'=>'project_form','id'=>'project_form']);?>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $this->Html->link('Back to Calculator',['controller'=>'SolarCalculators','action' => 'solar_calculator'],['class' => 'btn btn-primary btn-md pull-right mright50']); ?>
                        <?php if(!empty($customerId)) {
                        $class='col-md-8';
                        if(empty($projectData->name))
                        {
                        ?>
                            <?php $error_class_for_name_prefix = '';
                                if($ProjectsErrors!=''){ $error_class_for_name_prefix ='has-error'; } ?>
                            <div class="col-md-3 form-group text <?php echo $error_class_for_name_prefix;?>">
                                <input type="text" name="project_name" id="project_name" class="form-control " placeholder="Enter Project Name">
                                <?php echo $ProjectsErrors;?>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id;?>">
                                <input type="submit" name="submit" id="submit" value="Save Project" class="btn btn-primary" >
                            </div>
                        <?php
                         $class='col-md-3';
                        }
                        ?>
                        <div class="<?php echo $class;?>">
                           <?php echo $this->Html->link('Find Installers',['controller'=>'Installers','action' => 'getinstallerlist/'.encode($result['proj_id'])],['class' => 'btn btn-primary btn-md pull-right mright50']); ?>
                        </div>
                        <?php } ?>
                    </div>            
                </div>
                <?php echo $this->Form->end();?>
                <div class="row">
                    <div class="col-md-6 mtop32">
                        <h4><strong>Solar Calculator Result</strong></h4>
                        <table class="table table-borderd pricing-table">
                            <tr>
                                <th>Recommended Capacity</th>
                                <td align="left"><?php echo (isset($result['capacity'])?$result['capacity']." kW":0); ?></td>
                            </tr>
                            <tr>
                                <th>Estimated Cost</th>
                                <td align="left"><?php echo (isset($result['est_cost'])?$result['est_cost']." Lacs":0); ?></td>
                            </tr>
                            <tr>
                                <th>Estimated Cost with Subsidy</th>
                                <td align="left"><?php echo (isset($result['est_cost_subsidy'])?$result['est_cost_subsidy']." Lacs":0); ?></td>
                            </tr>
                            <tr>
                                <th>Average Generation</th>
                                <td align="left"><?php echo (isset($result['avg_gen'])?$result['avg_gen']." kWh/Month":0); ?></td>
                            </tr>
                             <tr>
                                <th>Savings</th>
                                <td align="left"><?php echo (isset($result['saving_month'])?$result['saving_month']." Rs/Month":0); ?></td>
                            </tr>
                             <tr>
                                <th>Payback</th>
                                <td align="left"><?php echo (isset($result['payback'])?$result['payback']." in Year":0); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div id="month_data_chart" class="month_chart"></div> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                
            </div>
            <div class="col-md-6">
                <div id="year_data_chart" class="year_chart"></div>
            </div>           
        </div>
        <div class="row">
            
        </div>
        <br/><br/>
    </div>
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
            width: 600,
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
            title: "Year Energy Chart",
            width: 600,
            height: 400,
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("year_data_chart"));
        chart.draw(view, options);
    }
</script>

