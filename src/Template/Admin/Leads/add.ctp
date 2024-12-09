<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style>
    #myMap {
        width: 100%;
        height: 200px;
    }
</style>

    <?php echo $this->Form->create($leads,array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off",'type' => 'file')); ?>
    <div class="row">
        <div class="col-md-12">
            <?php  echo $this->Flash->render('cutom_admin'); ?>
            <div class="portlet box blue-madison">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list-ul"></i>Create Lead
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                        <div class="form-body">
                            <div class="form-group"> 
                                <?php echo $this->Form->input('customer_id', array('label' => false,'class'=>'form-control','type'=>'hidden','placeholder'=>'Customer ID')); ?>
                                <?php $error_class_userid = '';
                                if(isset($ProjectLeadsErrors['userid']) && isset($ProjectLeadsErrors['userid']['_empty']) && !empty($ProjectLeadsErrors['userid']['_empty'])){ $error_class_userid = 'has-error'; }?>
                                <div class="col-md-6">
                                    <div class="row align-middle">
                                        <div class="col-md-4">
                                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" type="button" id="thebutton">
                                                <?php if($leads->customer_id == ''): ?>
                                                <span>Select Customer</span> 
                                                <?php else: ?>  
                                                <span>Change Customer</span>
                                                <?php endif; ?>
                                            </button>
                                        </div>
                                        <div class="col-md-8">
                                            <h4 class="display-4">
                                                <?php echo $leads->customer_name;?> <?php if(isset($leads->customer_name)) { echo  "/"; } ?> 
                                                <?php echo $leads->customer_email;?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <?php $error_class_installer_id = '';
                                if(isset($ProjectLeadsErrors['categories']) && isset($ProjectLeadsErrors['categories']['_empty']) && !empty($ProjectLeadsErrors['categories']['_empty'])){ $error_class_installer_id = 'has-error'; }?>
                                <div class="col-md-6 <?php echo $error_class_installer_id;?>">
                                    <label>Categories</label>
                                    <?php echo $this->Form->select('category_id',$category, array('label' => false,'class'=>'form-control category_id','placeholder'=>'Select Category','empty'=>'-Select Category-')); ?>

                                    <?php if(isset($ProjectLeadsErrors['categories']) && isset($ProjectLeadsErrors['categories']['_empty']) && !empty($ProjectLeadsErrors['categories']['_empty'])){  ?>
                                        <div class="help-block"><?php echo $ProjectLeadsErrors['categories']['_empty']; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input id="pac-input" class="form-control" type="text" placeholder="Search Box">
                                    <div id="myMap"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label><?php echo $this->Form->input('organization', array('label' => false,'class'=>'form-control','type'=>'checkbox','placeholder'=>'Disclaimer')); ?>Is the organization a social sector/ non-profit organization?</label>
                                </div>
                                <div class="col-md-4">
                                    <?= $this->Form->input('project_name',['class'=>'required form-control','type' =>'text', 'label'=>'Name of Project','value'=>$leads->project_name,'id'=>'name-of-project']) ?>
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" readonly="" class="form-control" name="longitude" id="longitude" value="<?php echo $leads->longitude; ?>" >
                                    <input type="hidden" readonly="" class="form-control" name="latitude" id="latitude" value="<?php echo $leads->latitude; ?>">
                                    <input type="hidden" readonly="" class="form-control landmark" name="location" value="<?php echo $leads->location; ?>">

                                    <?= $this->Form->input('Location',['class'=>'required form-control','name'=>'location1','id'=>'landmark','disabled'=>'disabled','value'=>$leads->location]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6"> 
                                    <label>Area Type?</label>
                                    <?php echo $this->Form->select('area_type',$areaTypeArr, array('label' => false,'class'=>'form-control source_lead required')); ?>
                                </div> 
                                <div class="col-md-6">
                                     <?= $this->Form->input("area",['class'=>'required form-control','type' =>'number', 'label'=>'Rooftop Area','value'=>$leads->area,'id'=>'rooftop-area']) ?>
                                </div> 
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <?= $this->Form->input("avg_monthly_bill",['class'=>'required form-control','type' =>'number', 'label'=>'Average Monthly Bill','placeholder'=>'(Rs./ kWh)','value'=>$leads->avg_monthly_bill,'id'=>'average-monthly-bill']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $this->Form->input('avg_energy_consum',['class'=>'required form-control', 'type' =>'number', 'label'=>'Average Energy Consumption','placeholder'=>'In (kWh)','value'=>$leads->avg_energy_consum,'id'=>'average-energy-consumption']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $this->Form->input('contract_load',['class'=>'required form-control','type' =>'number','label'=>' Contract Load','placeholder'=>'In (kW)','value'=>$leads->contract_load]) ?>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-6">
                                        <div class="col-md-12 no_padding">
                                            <label><strong>Source of Lead?</strong></label>
                                            <?php echo $this->Form->select('source_lead',$source_lead, array('label' => false,'class'=>'form-control source_lead required','empty'=>'-Select Source of Lead-')); ?>
                                        </div>
                                        <div class="col-md-12 lead_cold_calling" <?php echo  ($leads->source_lead == '1' ) ? '' : 'style="display: none"';?> >
                                            <?= $this->Form->input('lead_cold_calling',['class'=>'form-control','type'=>'text','label'=>'Source of Cold-Calling','value'=>$leads->lead_cold_calling]) ?>
                                        </div>
                                        <div class="col-md-12 lead_reference" <?php echo  ($leads->source_lead == '2' ) ? '' : 'style="display: none"';?> >
                                            <?= $this->Form->input('lead_reference',['class'=>'form-control','label'=>'Source of Reference','value'=>$leads->lead_reference]) ?>
                                        </div>
                                        <div class="col-md-12 lead_other"<?php echo  ($leads->source_lead == '7' ) ? '' : 'style="display: none"';?> >
                                            <?= $this->Form->input('lead_other',['class'=>'form-control','label'=>'Source of other','value'=>$leads->lead_other]) ?>
                                        </div>


                                        <div class="col-md-12 no_padding">
                                            <label><strong>Lead Status</strong></label>
                                            <?php echo $this->Form->select('status',$status_lead, array('label' => false,'class'=>'form-control status required','empty'=>'- Status of Lead -')); ?>
                                        </div>
                                        <div class="col-md-12 reason_lead" <?php echo  ($leads->status == 'archived' ) ? '' : 'style="display: none"';?> >
                                            <?= $this->Form->input('status_archived',['class'=>'required form-control','label'=>' Reason','value'=>$leads->status_archived]) ?>
                                        </div>

                                        <div class="col-sm-12 no_padding">
                                                <label><strong>Add Notes</strong></label>
                                                <?php echo $this->Form->input('new_notes',["type" => "textarea","label"=>false,"class" => "form-control","rows"=>2]);?>

                                                <?php if(isset($leads->allnotes) && !empty($leads->allnotes)) : ?>
                                                    <div class="scrollnotes">
                                                        <?php foreach ($leads->allnotes as $key =>$value) : ?>
                                                                <label><strong>Notes  (<?php echo $value['created_date'];?>) <?php echo (isset($value['created_name']) && $value['created_name'] !="") ? 'Created By: '.$value['created_name'] : '';?></strong></label>
                                                                <?php echo $this->Form->input('notes[]',["type" => "textarea","label"=>false,"class" => "form-control","readonly"=>true,"rows"=>2, "value"=> $value['notes']]);?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif;?>
                                        </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if(isset($leads->leads_doc) && count($leads->leads_doc) != 2) : ?>
                                        <label><strong>Lead Documents</strong></label>
                                        <div class="file-loading" >
                                            <input id="leads_image" name="leads_image[]" type="file" multiple>
                                        </div>
                                        <div id="placeinv-file-errors"></div>
                                    <?php endif;?>

                                    <?php if(isset($leads->leads_doc) && !empty($leads->leads_doc)) : ?>
                                        <br/>
                                        <label><strong>Leads Uploaded Documents</strong></label>
                                        <?php foreach($leads->leads_doc as $key =>$document): ?>
                                            <br/><div class="remove_leaddoc"><a target="_blank" href="<?php echo $document['filename'];?>"><?php echo $document['filename_only'];?></a> <i data-id="<?php echo encode($document['id']);?>" class="fa fa-trash"></i></div>
                                        <?php endforeach;?>

                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-5 col-md-6">
                                        <button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                                        <button type="button" onclick="goback()" class="btn"><i class="fa fa-close"></i> Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php echo $this->Form->end(); ?>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Select User</h4>
            </div>
            <div class="modal-body">
                <?php
               /* $disabled = "";

                if(isset($leads->customer_id) && $cust_id != $leads->created_by){
                    $disabled = 'disabled=>"$disabled"';
                }?>

                <?php echo $this->Form->select('customer_id',$customerArray, array('label' => false,'class'=>'form-control customerid','empty'=>'-select User-','value'=>$leads->customer_id,$disabled)); ?>

                <?php if(isset($leads->customer_id) && $cust_id != $leads->created_by) : ?>
                    <input type="hidden" name="customer_id" value="<?php echo $leads->customer_id;?>">
                <?php endif; ?>

                <?php if(isset($ProjectLeadsErrors['userid']) && isset($ProjectLeadsErrors['userid']['_empty']) && !empty($ProjectLeadsErrors['userid']['_empty'])){  ?>
                    <div class="help-block"><?php echo $ProjectLeadsErrors['userid']['_empty']; ?></div>
                <?php } */

               ?>

                <div class="row">
                    <div class="col-md-4" >
                        <?= $this->Form->input('customer_name',['class'=>'form-control','type'=>'text','label'=>'Customer Name','value'=>'' , 'id'=>"customer_name"]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->input('email',['class'=>'form-control','type'=>'email','label'=>'Customer Email','value'=>'','id'=>"email"]) ?>
                    </div>
                    <div class="col-md-4">
                        <div class="input text">
                            <label></label>
                            <br/>
                            <button class="btn btn-primary btn-md" type="button"  onclick="loadDoc()">
                                Select User
                            </button> <i class="fa fa-circle-o-notch fa-spin" id="loader" style=" font-size:24px; margin-left:10px; display:none;"></i>
                            
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="demo1">

                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" id="p1" class="btn btn-primary btn-md"  type="button">OK</button>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDqfWF0vAnh-vajop-4cCralyhh51uT2Mk&libraries=places"></script>
    <?php echo $this->Html->script('googleMap.js') ?>

    <script>

        $(document).ready(function () {

            var maxFIleUpload = 2;
            <?php if(isset($leads->leads_doc) && !empty($leads->leads_doc) && count($leads->leads_doc) !=2) : ?>
            maxFIleUpload = maxFIleUpload - <?php echo count($leads->leads_doc);?>
                <?php endif; ?>

                $("#leads_image").fileinput({
                    showUpload: false,
                    showPreview: false,
                    dropZoneEnabled: false,
                    maxFileCount: maxFIleUpload,
                    mainClass: "input-group-lg",
                    allowedFileExtensions: ["jpg", "jpeg", "png","pdf","doc","docs"],
                    elErrorContainer: '#placeinv-file-errors',
                    maxFileSize: 1024,
                });


            $(".remove_leaddoc i").click(function () {
                $(this).removeClass('fa-trash');
                $(this).addClass('fa-circle-o-notch fa-spin');
                var leadId = $(this).attr('data-id');
                $.ajax({
                    url:'<?php echo $this->Url->build(['controller' => 'Leads','action'=>'removeLeadsImages']); ?>',
                    type: 'POST',
                    data: jQuery.param({lead_doc_id: leadId}),
                    beforeSend: function(xhr)
                    {
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    },
                    success: function(data)
                    {
                        var data = JSON.parse(data);
                        if(data.type == 'ok') {
                            location.reload();
                        }
                        else
                        {
                            location.reload();
                        }
                    }
                });
            });



            $(".customerid").select2();

            $('select.project_id').on('change', function () {
                if (this.value == '0') {
                    $(".otherclient").show();
                } else {
                    $(".otherclient").hide();
                }
            });


            $('select.status').on('change', function () {
                if (this.value == 'archived') {
                    $(".reason_lead").show();
                } else {
                    $(".reason_lead").hide();
                }
            });

            $('select.source_lead').on('change', function () {

                $(".lead_cold_calling").hide();
                $(".lead_reference").hide();
                $(".lead_other").hide();

                if (this.value == '1') {
                    $(".lead_cold_calling").show();
                }
                if (this.value == '2') {
                    $(".lead_reference").show();
                }
                if (this.value == '7') {
                    $(".lead_other").show();
                }
            });

        });
    </script>


<script>
    function loadDoc() {
        $('#loader').show();
        var customer_name = $("#customer_name").val();
        var email = $("#email").val();
        $.ajax({

            url: '<?php echo $this->Url->build(['controller' => 'Leads','action'=>'get_customer_list']); ?>',
            type: 'POST',
            data: jQuery.param({name: customer_name, email: email}),
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(data) {
                $("#demo1").html("");
                $("#demo1").append(data);
                $('#loader').hide();
            },
            error: function () {
                alert("error");
            }
        });
    }
</script>
<script>
    function goback(){
        window.location.href=WEB_ADMIN_URL+'leads';
    }
    $(document).ready(function(){
        $("#p1").click(function(){
            var radioValue = $("input[name='customer_id']:checked").val();
            var radioName = $("input[name='customer_id']:checked").attr('data-name');
            var radioEmail = $("input[name='customer_id']:checked").attr('data-email');
            //var name= $("#cusname"+radioValue).html();
            if(radioName == undefined  && radioEmail == undefined){
                alert('Please Enter Customer Name or Customer Email');
            }
            $('#myModal').modal('hide');
            $("#customer-id").val(radioValue);
            $(".display-4").html(radioName +" / "+ radioEmail);  
            $("#thebutton span").html("Change Customer");
        });

        <?php if(isset($leads->id) && $leads->id !="") : ?>
        $(".category_id, #organization, #name-of-project, .area_type, #rooftop-area, #average-monthly-bill, #average-energy-consumption, #contract-load, .source_lead, #lead-cold-calling, #lead-reference, #lead-other").attr("readonly",true);
        <?php endif;?>

    });
</script>