<?php
	$this->Html->addCrumb('Projects', ['controller' => 'projects']); 
    $this->Html->addCrumb($pageTitle); 
    //prd($project);
    /*Energy Month Chart Data Create*/
    $monthChart = '';
    if(isset($resultArr['monthChart']) && !empty($resultArr['monthChart'])) {
        $monthChartData =  json_decode($resultArr['monthChart']);
        foreach($monthChartData as $data) { 
            $dateObj   = DateTime::createFromFormat('!m', $data->x);
            $monthName = $dateObj->format('M'); 
            $monthChart .= "['".$monthName."',".$data->y."],";
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
<div class="container">
	 <div class="clearfix"></div>
   <div class="row">
      <div class="col-sm-6">
         	<div class="client-info-div box-border-style">
            <div class="col-sm-4" style="padding: 15px;">
               <?php
                    
                      $latLng = $project['project']->latitude.",".$project['project']->longitude;
                       $mapUrl = base64_encode(file_get_contents('https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=12&size=150x225&markers=color:red%7C'.$latLng.'&sensor=false'));
                       ?>
                       <img alt="Opps! Map not show proper, please refress page Or check Lat Long" src="data:image/png;base64,<?php echo $mapUrl; ?>" />
             </div>
             <div class="col-sm-8">
                  <div class="col-sm-12">
                  <h4>Project Information</h4>
               </div>
               <div class="col-sm-12">
                  <table class="table projectInfo">
                     <tbody>
                        <tr>
                           <th>Name <span class="pull-right">:</span></th>
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
                           <th>Postcode <span class="pull-right">:</span></th>
                           <td><?php echo $project['project']->pincode?></td>
                        </tr>
                         <tr>
                           <th>Rooftop Area <span class="pull-right">:</span></th>
                           <td><?php echo $project['project']->area?> <?php echo $project['project']->area_type?></td>
                        </tr>
                         <tr>
                           <th>Monthly Bill <span class="pull-right">:</span></th>
                           <td><?php echo $project['project']->avg_monthly_bill?></td>
                        </tr>
                         <tr>
                           <th>Back Up Type <span class="pull-right">:</span></th>
                           <td><?php echo $project['project']->backup_type?> <?php echo $project['project']->diesel_genset_kva?></td>
                        </tr>
                        <tr>
                           <th>Back Up Usage <span class="pull-right">:</span></th>
                           <td><?php echo $project['project']->usage_hours?> </td>
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
                           <td><?php echo $project['project']->customer_type?></td>
                        </tr>
                        <tr>
                           <th>Name <span class="pull-right">:</span></th>
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
                           <th>Postcode <span class="pull-right">:</span></th>
                           <td><?php echo $project['project']->pincode?></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
              </div>
      </div>
   </div>
   <div class="row m-t-20">
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
                         <td><?php echo $project['project']->recommended_capacity?> </td>
                      </tr>
                      <tr>
                         <th>Maximum Capacity <span class="pull-right">:</span></th>
                         <td><?php echo $project['project']->maximum_capacity?></td>
                      </tr>
                      <tr>
                         <th>Avergage Energy Genratin  <span class="pull-right">:</span></th>
                         <td><?php echo $project['project']->avg_generate?></td>
                      </tr>
                      <tr>
                         <th>Cost of Solar <span class="pull-right">:</span></th>
                         <td><?php echo $project['project']->cost_solar?></td>
                      </tr>
                      <tr>
                         <th>Payback <span class="pull-right">:</span></th>
                         <td><?php echo $project['project']->payback?></td>
                      </tr>
                      <tr>
                         <th>Estimated Saving <span class="pull-right">:</span></th>
                         <td><?php echo $project['project']->estimated_saving_month?> / Mo</td>
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
   <div class="clearfix"></div>
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
      url: '<?php echo URL_HTTP."projects/saveProjectNote"; ?>',
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
    function showDialog(url){
        //load content and open dialog
            document_window = $(window).width() - $(window).width()*0.05;
            document_height = $(window).height() - $(window).height() * 0.20;
            modal_body = '<div class="modal-header" style="min-height: 45px;">'+
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
                    '</div>'+
                    '<div class="modal-body">'+
                        '<iframe id="TaskIFrame" width="100%;" src="'+url+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
                    '</div>';
            $('#myModal').find(".modal-content").html(modal_body);
            $('#myModal').modal('show');
            $('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
            $('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
            return false;
        }
        window.closeModal = function(){ $('#myModal').modal('hide'); };
</script>