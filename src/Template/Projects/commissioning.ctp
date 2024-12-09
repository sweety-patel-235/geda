<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
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

<div class="container-fluid">
    <div class="">
        <div class="col-md-12">         
            <div class="modal-body">
                
                <?= $this->Form->create($imagePatchEntity,['name'=>'commissioning','id'=>'commissioning','enctype'=>"multipart/form-data"]);
                
                echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>  
                
                <div class="form-group">
                    <label class="control-label col-sm-2">Project Id</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('project_id',["type" => "text",'label' => false,"class" => "form-control",'value'=>$project_id,'id'=>"com_id","readonly"]);?>
                    </div>
                    <label for="pv_wp" class="control-label col-sm-2">Project Name</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('project_name',["type" => "text","label"=>false,"class" => "form-control",'value'=>(isset($data['project_name'])?$data['project_name']:''),"readonly"]);?>
                    </div>
                    
                </div>

                <div class="form-group">
                    <label for="pv_wp" class="control-label col-sm-2">Location</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('location',["type" => "text","label"=>false,"class" => "form-control",'value'=>(isset($data['location'])?$data['location']:''),"readonly"]);?>
                    </div>
                    <label for="pv_wp" class="control-label col-sm-2">Capacity</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('capacity',["type" => "text","label"=>false,"class" => "form-control",'value'=>$capacity,"readonly"]);?>
                    </div>
                 </div>

                <div class="form-group">
                    <label for="pv_wp" class="control-label col-sm-2">Contact No</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('contact_no',["type" => "text","label"=>false,"class" => "form-control",'value'=>$mobile_num,"readonly"]);?>
                    </div>
                    <label for="pv_wp" class="control-label col-sm-2">Image</label>
                    <div class="col-sm-4 ">
                        <div class="file-loading">
                          <input id="upload_image" name="document[image_type][]" type="file" multiple>
                       </div>
                       <div id="commissionig-img-errors"></div>
                        <div>
                       <table width="100%">
                            <tr>
                             <?php
                                 $counter=1;
                                 foreach($all_photo_data as $key => $ph_data)
                                 {

                                   if($ph_data->c['type'] == 'commissioning_img')
                                   {
                                        $path = COMMISSIONING_DATA_PATH.$ph_data->c['type'].'/'.$project_id.'/'.$ph_data->c['photo'];
                                        

                                         if (file_exists($path))
                                         {
                                              $image_url=URL_HTTP.COMMISSIONING_DATA_PATH.$ph_data->c['type'].'/'.$project_id.'/'.$ph_data->c['photo'];
                                     ?>

                                    <td width="25%" id="photo_<?php echo encode($ph_data->c['id']);?>" style="padding-top:10px;">
                                    <table>
                                     <tr>
                                        <td>
                                            <img src="<?php echo $image_url;?>" width="75px">
                                        </td>
                                     </tr>
                                    <tr>
                                        <td align="center"><i class="fa fa-remove"  onclick="javascript:delete_photo('<?php echo encode($ph_data->c['id']);?>');"> </i></td>
                                     </tr>
                                   </table>
                                  </td>
                                  <?php

                                   if($counter%3 == 0)
                                   {
                                       echo '</tr><tr>';
                                   }
                                      $counter++;
                                             }
                                      }
                                 }
                                                ?>
                             </tr>
                        </table>
                       </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pv_wp" class="control-label col-sm-2">Cerrtificate No.</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('certificate_no',["type" => "text","label"=>false,"class" => "form-control"]);?>
                    </div>
                     <label for="pv_wp" class="control-label col-sm-2">Certificate Image</label>
                     <div class="col-sm-4 ">
                        <div class="file-loading">
                          <input id="upload_cer_image" name="document[certificate][]" type="file" 
                          multiple >
                       </div>
                       <div id="commissionig-certificate-errors"></div>
                       <div>
                       <table width="100%">
                            <tr>
                             <?php
                                $img =$imagePatchEntity->certificate_photo;
          
                                  $path = COMMISSIONING_DATA_PATH ."certifictae_img".'/'.$project_id.'/'.$img;
                                  if (file_exists($path))
                                  {
                                     $image_url=URL_HTTP.COMMISSIONING_DATA_PATH."certifictae_img".'/'.$project_id.'/'.$img;
                              ?>
                                   <table>
                                     <tr>
                                       <td style="padding-top:10px;">
                                          <img src="<?php echo $image_url;?>" width="75px">
                                       </td>
                                     </tr>
                                  </table>
                                  <?php 
                                    }
                                  ?>
                             </tr>
                        </table>
                     </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-3">
                      <input type="button" value="Distribution Company" class="btn-primary btn pull-left save_data" data-loading-text="Loading..." name="distribution_company" id="distribution_company">
                     <?php echo $this->Form->input('proj_id',["type" => "hidden","value"=>$project_id,"id"=>"com_id"]); ?>  
                    </div>
                    <div class="col-sm-3">
                      <input type="button" value="Chief Electrical Inspector" class="btn-primary btn pull-left save_data" data-loading-text="Loading..." name="chief_electrical_inspector" id="chief_electrical">
                    </div>
                    <div class="col-sm-3">
                      <input type="button" value="State Energy Nodal Agency" class="btn-primary btn pull-left save_data" data-loading-text="Loading..." name="state_energy_nodal_agency" id="state_energy">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left']); ?>
                <?= $this->Form->end(); ?>
                  </div>
                </div>
            </div>
        </div>
    </div>        
</div>

<script type="text/javascript">
$(document).on('click', '#addNote', function() {  
   $('#add_projects_note_model').modal('show');
});

$("#upload_image").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 6,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#commissionig-img-errors',
        maxFileSize: 100,
    });

$("#upload_cer_image").fileinput({
        showUpload: false,
        showPreview: false,
        dropZoneEnabled: false,
        maxFileCount: 1,
        mainClass: "input-group-lg",
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
        elErrorContainer: '#commissionig-certificate-errors',
        maxFileSize: 100,
    });
function delete_photo(photo_id)
    {
      var proj_id= $("#com_id").val();

        if(window.confirm("Are you sure want to delete photo?"))
        {
            jQuery.ajax({
                url: '<?php echo URL_HTTP."projects/delete_commissioning_image"; ?>',
                type: 'POST',
                data:  {photo_id:photo_id,proj_id:proj_id},
                success: function(results)
                {
                    if(results=='1') {
                        $("#photo_"+photo_id).remove();
                        // location.reload();
                    } else {

                    }
                }
            });
        }
    }
$("body").on("click",".save_data",function(){
    var co_name = $(this).attr("name");
    var co_value = $(this).attr("value");
    var proj_id= $("#com_id").val();
            $.ajax({
                url:'<?php echo $this->Url->build(['controller' => 'Projects','action'=>'commissionig_check_data']); ?>',
                type: 'POST',
                data: jQuery.param({c_name: co_name, pr_id: proj_id ,c_value: co_value}),

           success: function(data)
            {
                var data = JSON.parse(data);
                if(data.success == 1) {
                   if (confirm("Request sent on  "+data.date+". Do you want to resend it ?")) {
                     save_data_commisssioning(data.pr_id,data.c_name,data.c_value);
                  }

                }
                else{
                  save_data_commisssioning(data.pr_id,data.c_name,data.c_value);
                }
                
            }
            });
   }); 
function save_data_commisssioning(pr_id,c_name,c_value)
{
            $.ajax({
                url:'<?php echo $this->Url->build(['controller' => 'Projects','action'=>'commissionig_data_save']); ?>',
                type: 'POST',
                data: jQuery.param({co_name: c_name, proj_id: pr_id ,co_value: c_value}),

           success: function(data)
            {
                var data = JSON.parse(data);
                if(data.success == 1) {
                  alert("data saved successfully.");
                }

            }
                
            });
}
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
            title: "Year Energy Chart",
            width: '100%',
            height: 400,
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: { position: "none" },
          };
        var chart = new google.visualization.ColumnChart(document.getElementById("year_data_chart"));
        chart.draw(view, options);
    }
    
</script>