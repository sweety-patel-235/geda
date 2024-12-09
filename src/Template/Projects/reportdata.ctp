<?php
$this->Html->addCrumb('Projects', ['controller' => 'projects']);
$this->Html->addCrumb($pagetitle);

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
.fileinput-button {
  position: relative;
  overflow: hidden;
  display: inline-block;
}
.fileinput-button input {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  opacity: 0;
  -ms-filter: 'alpha(opacity=0)';
  font-size: 200px;
  direction: ltr;
  cursor: pointer;
}

/* Fixes for IE < 8 */
@media screen\9 {
  .fileinput-button input {
    filter: alpha(opacity=0);
    font-size: 100%;
    height: 100%;
}
}
</style>  
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/js/jquery.ui.widget.js"></script>    
<script src="/js/jquery.iframe-transport.js"></script>    
<script src="/js/jquery.fileupload.js"></script>    
 
<div class="container">
    <div class="row border-style">
        <div class="col-md-12">         
                <!-- Modal content-->
                   <div class="modal-header">
                      <h4 class="modal-title">Add Report Data</h4>
                   </div>
                   <div class="modal-body">
                      <?= $this->Form->create($ProjectNotes,['name'=>'add_project_note_form','id'=>'add_project_note_form','enctype'=>"multipart/form-data"],array('action' => 'projects/reportdata'));
                         echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>  
                      <div class="form-group">
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('office_name',["type" => "text","class" => "form-control",'label' => ['text' => 'Name of the Office/ Institution']]);?>
                         </div>
                         <div class="col-sm-1">
                         </div>
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('office_address',["type" => "text","class" => "form-control",'label' => ['text' => 'Office/ Institution Address']]);?>
                         </div>
                       </div>
                        <div class="form-group">
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('rooftop_capacity',["type" => "text","class" => "form-control",'label' => ['text' => 'Recommended Solar Rooftop PV Capacity(kWp)']]);?>
                         </div>
                         <div class="col-sm-1">
                         </div>
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('about_site',["type" => "text","class" => "form-control",'label' => ['text' => 'About the Site']]);?>
                         </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-2">
                              <?php echo $this->Form->input('road_connectivity',["type" => "text","class" => "form-control",'placeholder' => 'NH']);?>
                           </div>
                           <div class="col-md-2">
                                <?php echo $this->Form->input('road_connectivity_sh',["type" => "text","class" => "form-control",'placeholder' => 'SH']);?>
                           </div>
                           <div class="col-md-2">
                           </div>
                           <div class="col-md-2">
                                <?php echo $this->Form->input('airport_connectivity',["type" => "text","class" => "form-control",'placeholder' => 'Airport Name']);?>
                           </div>
                           <div class="col-md-2">
                                 <?php echo $this->Form->input('distance_from_airport',["type" => "text",'label'=>'',"class" => "form-control",'placeholder' => 'Distance','label' => ['text' => 'Distance from Airport']]);?>
                            </div>
                        </div>
                       <div class="form-group">
                          <div class="col-md-2">
                             <?php echo $this->Form->input('port_connectivity',["type" => "text","class" => "form-control",'placeholder' => 'Port Name']);?>
                           </div>
                           <div class="col-md-2">
                               <?php echo $this->Form->input('distance_from_port',["type" => "text",'label'=>'',"class" => "form-control",'placeholder' => 'Distance','label' => ['text' => 'Distance from Port']]);?>
                           </div>
                           <div class="col-md-2">
                           </div>
                           <div class="col-md-2">
                                <?php echo $this->Form->input('rail_connectivity',["type" => "text","class" => "form-control",'placeholder' => 'Railway Station Name']);?>
                           </div>
                           <div class="col-md-2">
                                <?php echo $this->Form->input('distance_from_rail',["type" => "text",'label'=>'',"class" => "form-control",'placeholder' => 'Distance','label' => ['text' => 'Distance from Rail']]);?>
                            </div>
                        </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('estimate_capacity',["type" => "text","class" => "form-control",'label' => ['text' => 'Estimated Capacity (Editable)']]);?>
                         </div>
                         <div class="col-sm-1">
                         </div>
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('modules_per_mounting',["type" => "text","class" => "form-control",'label' => ['text' => 'Modules per Mounting Structures']]);?>
                         </div>
                       </div>

                       <div class="form-group">
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('rating_solar_pv',["type" => "text","class" => "form-control",'label' => ['text' => 'Rating of Solar PV Module']]);?>
                         </div>
                         <div class="col-sm-1">
                         </div>
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('efficiency_solar_pv',["type" => "text","class" => "form-control",'label' => ['text' => 'Efficiency of Solar PV Module']]);?>
                         </div>
                       </div>

                      <div class="form-group">
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('rating_inverter',["type" => "text","class" => "form-control",'label' => ['text' => 'Rating of Inverter']]);?>
                         </div>
                         <div class="col-sm-1">
                         </div>
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('efficiency_inverter',["type" => "text","class" => "form-control",'label' => ['text' => 'Efficiency of Inverter']]);?>
                         </div>
                       </div>

                       <div class="form-group">
                         <div class="col-sm-5">
                            <?php echo $this->Form->input('name_of_discom',["type" => "text","class" => "form-control",'label' => ['text' => 'Name of the DisCom']]);?>
                         </div>
                        </div>
                        
                      <table id="customFields2" width="100%">
                         <tbody>
                           <?php
                           $style = "";
                           $disabled = array();
                           $remove = 0;
                                foreach($dropdown as $key=>$dp)
                                {
                                   if($ProjectNotes->$key !="") {
                                      $remove++;
                                      $style="display:none;";
                                      $disabled = array("disabled"=>"disabled");

                                      $file_url=URL_HTTP.REPORT_DATA_PATH."report_file/".$project_id.'/'.$ProjectNotes->$key;
                                      
                                       ?>
                                      <tr id="rowId2" class="remove_<?php echo $remove;?>">
                                      <td class="wwe-lang-matches" scope="col">
                                          <div class="form-group table-fr-td">
                                               <div class="col-sm-5">
                                                   <?php echo $this->Form->select("reportdata[][name]",array('electricity_bill'=>'electricity_bill','single_line_document'=>'single_line_document','cable_distribution_document'=>'cable_distribution_document','earthing_document'=>'earthing_document','pv_reports_document'=>'pv_reports_document','energy_generation_document'=>'energy_generation_document','technical_details_document'=>'technical_details_document','prepred_for_logo'=>'prepred_for_logo','prepred_by_logo1'=>'prepred_by_logo1','prepred_by_logo2'=>'prepred_by_logo2','prepred_by_logo3'=>'prepred_by_logo3'),["id"=>"document_type","class" => "form-control reportdata",'label' => ['text' => 'Select Document Type'],"value" => $key]);?>
                                                </div>
                                                <div class="col-sm-1">
                                                </div>
                                                <div class="col-sm-5">
                                                  <span class="btn btn-success fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                       <span>Select files...</span>
                                              <!-- The file input field used as target for the file upload widget -->
                                                          <input  id="document_file" type="file" name="reportdata[][files]" multiple>
                                                        
                                                  </span>
                                                   <a id="fileurl" href="<?php echo $file_url;?>"><?php echo $ProjectNotes->$key ;?></a>
                                                </div>
                                                

                                                <div class="col-sm-1">
                                                      <p width="5%"  class="remove_button"><i data-projnoteid="<?php echo $ProjectNotes->id ;?>" data-key="<?php echo $key?>" class="fa fa-remove editremove" data-id="<?php echo $remove;?>" style="color:#ff0000;"></i>
                                                      </p>
                                                </div>
                                          </div>
                                       </td>
                                    </tr>
                                  <?php
                                }
                              }
                            ?>

                            <tr id="rowId3" style="<?php echo $style;?>">
                                <td class="wwe-lang-matches" scope="col">
                                    <div class="form-group table-fr-td">
                                         <div class="col-sm-5">
                                             <?php echo $this->Form->select("reportdata[][name]",array('electricity_bill'=>'electricity_bill','single_line_document'=>'single_line_document','cable_distribution_document'=>'cable_distribution_document','earthing_document'=>'earthing_document','pv_reports_document'=>'pv_reports_document','energy_generation_document'=>'energy_generation_document','technical_details_document'=>'technical_details_document','prepred_for_logo'=>'prepred_for_logo','prepred_by_logo1'=>'prepred_by_logo1','prepred_by_logo2'=>'prepred_by_logo2','prepred_by_logo3'=>'prepred_by_logo3'),["id"=>"document_type","class" => "form-control reportdata",$disabled,'label' => ['text' => 'Select Document Type']]);?>
                                          </div>
                                          <div class="col-sm-1">
                                          </div>
                                          <div class="col-sm-5">
                                            <span class="btn btn-success fileinput-button">
                                              <i class="glyphicon glyphicon-plus"></i>
                                                 <span>Select files...</span>
                                        <!-- The file input field used as target for the file upload widget -->
                                                    <input <?php echo (isset($disabled) && !empty($disabled) )? 'disabled="disabled"' : '';?> id="document_file" type="file" name="reportdata[][files]" multiple>
                                            </span>
                                          </div>
                                          <div class="col-sm-1">
                                                <p width="5%" class="remove_button"><i class="fa fa-remove" style="color:#ff0000;display:none;"></i>
                                                </p>
                                          </div>
                                    </div>
                                 </td>
                              </tr>

                          </tbody>
                      </table>
                      <div class="row">
                                <div class="_100 h_63">
                                    <p>
                                        <label class="spanright">
                                            <span ><a href="javascript:;" class="addmore2">+Add More</a></span>
                                        </label>
                                    </p>
                                </div>
                      </div>
                      <div class="clearfix"></div>
                         <?= $this->Form->button(__('Save Project Note'),['type'=>'submit','class'=>'btn-primary btn text-center center-block']); ?>
                      <?= $this->Form->end(); ?>
                   
						           <br>
						           <br>
						<!-- The global progress bar -->
						
						<!-- The container for the uploaded files -->
						<div id="files" class="files"></div>
                   </div>
        </div>
    </div>        
</div>


<script type="text/javascript">

$(document).ready(function() {
    var selectid1 = 1;
    var selectid2 = 1;
    var clonerow = '';

if($("#rowId3").attr('id') == 'rowId3'){
    clonerow2 = $("#rowId3").clone();
}
 var selected = [];
    $('.addmore2').click(function(){
        $(clonerow2).css("display","table-row");
        clonerow2 = $(clonerow2).removeAttr("id");
        $(clonerow2).find("select,input").removeAttr("disabled");
        $(clonerow2).find("fileurl").remove();
        $(clonerow2).find("a").removeClass('hide');
        $(clonerow2).find("input").val('');
        $(clonerow2).find("input").removeAttr("id");
        $(clonerow2).find("select").attr("id",$(clonerow2).find("select").attr("id")+selectid2);
        $(clonerow2).find("#"+$(clonerow2).find("select").attr("id")+"_chzn").remove();
        $(clonerow2).find("select").removeClass('chzn-done');
        $(clonerow2).find("#"+$(clonerow2).find("select").attr("id")+selectid2).removeAttr('style');
        $(clonerow2).find(".fa-remove").removeAttr("style");
        $(clonerow2).find(".fa-remove").attr("style",'color:#ff0000;cursor:pointer;');
        $(clonerow2).find(".fa-remove").attr("onclick",'javascript:$(this).parent().parent().parent().parent().remove();');
            $('#customFields2 tr').last().after($(clonerow2).clone());
            $('#customFields2 tbody tr').last().find('span.checkbox').remove();
            $('#customFields2 tbody tr').last().find("input[type='checkbox']").attr('checked',false);
            $('#customFields2 tbody tr').last().find('input[type=checkbox]').each(function(){$(this).checkbox({
                cls : 'checkbox',

                empty : WEB_ADMIN_URL+'img/sprites/forms/checkboxes/empty.png'
            });
        });
        
        selectid2++;
    });

});
$(document).on('click', '#addNote', function() {  
   $('#add_projects_note_model').modal('show');
});
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : 'server/php/';
    $('#document_file').fileupload({
        url: '/projects/documentupload',
		dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#files');
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

$("body").on("click",".editremove",function(){
        remove = $(this).attr("data-id");
          var doc_val= $(this).attr('data-key');
          var id= $(this).attr('data-projnoteid');
            $.ajax({
                url:'<?php echo $this->Url->build(['controller' => 'Projects','action'=>'remove_data']); ?>',
                type: 'POST',
                data: jQuery.param({docval: doc_val, id: id}),

            success: function(data)
            {
                if(data == 1){
                  $(".remove_"+remove).remove();
                }
            }
            });
   }); 
    

// $(".reportdata").on('change',function(){
//     $(".reportdata option:selected").each(function()
// {
//         alert(this.value);
// });
// })


// $(".reportdata").on('focus', function ()
//     {
//         previous = this.value;
//       }).change(function() {
//     var previoues_val=previous;

//     var selected=$(this).val();
//     var opts = $(this)[0].options;    

//     var array = $.map(opts, function(elem) {
//     return (elem.value || elem.text);
//     });




//     $('.reportdata').each(function() {

//       var v=$(this).val();
//       if(previoues_val != '' )
//       {
//         $('option[value="' + previoues_val + '"]').removeAttr('disabled'); 
//       }
//     $('option[value="' + selected + '"]').attr('disabled','disabled');
//     $('option[value=""]').removeAttr('disabled'); 
//     });
//     previous = this.value;
//     });
</script>

