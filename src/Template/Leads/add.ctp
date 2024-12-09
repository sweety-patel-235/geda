<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<style type="text/css">
    .project-leads .p-row {
        background: #f7f7f7;
        padding: 15px;
        margin: 10px 0;
    }

    .project-leads .p-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;

    }

    .project-leads .p-title a {
        color: #71BF57;
    }

    .project-leads .p-date {
        font-size: 14px;
        text-align: right;
        display: inline;
        margin-left: 10px;
    }

    .project-leads .action-btn {
        margin-top: 7px;
    }

    input[type="checkbox"] {
        width: 18px;
        float: left;
        margin-left: 4px !important;
        position: relative !important;
        display: inline-block;
        margin-right: 10px;
        margin-top: -3px;
    }
</style>
<?php
$this->Html->addCrumb($pageTitle);

?>
<div class="container project-leads">

    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li role="presentation" class="active">
                <?= $this->Html->link('New', ['action' => 'add']) ?>
            </li>
            <li role="presentation" class="">
                <?= $this->Html->link('My Leads', ['action' => 'index']) ?>
            </li>

            <li role="presentation" class="">
                <?= $this->Html->link('Archive Leads', ['action' => 'index','archived']) ?>
            </li>
         </ul>
    </div>

    <br>

    <?= $this->Form->create($leads,['class' => 'validate-form','type' => 'file']) ?>
    <div class="form-group">
        <div class="col-md-6 ">
            <label>User Name</label>
            <input type="text" name="username" class="form-control" disabled="disabled"
                   placeholder="Username" value="<?php echo $this->Session->read('Customers.name'); ?>">

        </div>
        <?php $error_class_installer_id = '';
        if(isset($ProjectLeadsErrors['categories']) && isset($ProjectLeadsErrors['categories']['_empty']) && !empty($ProjectLeadsErrors['categories']['_empty'])){ $error_class_installer_id = 'has-error'; }?>
        <div class="col-md-6 <?php echo $error_class_installer_id;?>">
            <label>Categories<span class="mendatory">*</span></label>
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
            <label>Name of Client<span class="mendatory">*</span></label>
            <?= $this->Form->input('Name of Project',['label'=>false,'class'=>'required','name'=>'project_name','value'=>$leads->project_name]) ?>
        </div>
        <div class="col-md-4">
            <input type="hidden" readonly="" class="form-control" name="longitude" id="longitude" value="<?php echo $leads->longitude; ?>" >
            <input type="hidden" readonly="" class="form-control" name="latitude" id="latitude" value="<?php echo $leads->latitude; ?>">
            <input type="hidden" readonly="" class="form-control landmark" name="location" value="<?php echo $leads->location; ?>">

            <?= $this->Form->input('Location',['class'=>'required','name'=>'location1','id'=>'landmark','disabled'=>'disabled','value'=>$leads->location]) ?>
        </div>
    </div>


    <div class="form-group">
        <div class="col-md-6">
            <label>Area Type?<span class="mendatory">*</span></label>
            <?php echo $this->Form->select('area_type',$areaTypeArr, array('label' => false,'class'=>'form-control area_type required')); ?>
        </div>

        <div class="col-md-6">
            <label>Rooftop Area<span class="mendatory">*</span></label>
            <?= $this->Form->input('Rooftop Area',['label' => false,'class'=>'required','name'=>'area','type'=>'number','value'=>$leads->area]) ?>
        </div>



    </div>

    <div class="form-group">
        <div class="col-md-4">
            <label>Average Monthly Bill<span class="mendatory">*</span></label>
            <?= $this->Form->input("Average Monthly Bill",['label' => false,'class'=>'required','name'=>'avg_monthly_bill','placeholder'=>'(Rs./ kWh)','type'=>'number','value'=>$leads->avg_monthly_bill]) ?>
        </div>

        <div class="col-md-4">
            <label>Average Energy Consumption<span class="mendatory">*</span></label>
            <?= $this->Form->input('Average Energy Consumption',['label' => false,'class'=>'required','name'=>'avg_energy_consum','placeholder'=>'In (kWh)','type'=>'number','value'=>$leads->avg_energy_consum]) ?>
        </div>

        <div class="col-md-4">
            <label>Contract Load<span class="mendatory">*</span></label>
            <?= $this->Form->input('Contract Load',['label' => false,'class'=>'required','name'=>'contract_load','placeholder'=>'In (kW)','type'=>'number','value'=>$leads->contract_load]) ?>
        </div>

    </div>
    <div class="form-group">
        <div class="col-md-6">
            <label>Source of Lead?<span class="mendatory">*</span></label>
            <?php 
            $disabled           = '';
            $arr_dis            = array();
            if(isset($leads->id) && $leads->id!='')
            {
                foreach($source_lead as $key=>$val)
                {
                    if($leads->source_lead != $key)
                    {
                        $arr_dis[]  = $key;
                    }
                }
            }
            echo $this->Form->select('source_lead',$source_lead, array('label' => false,'class'=>'form-control source_lead required','empty'=>'-Select Source of Lead-','disabled' => $arr_dis)); 
            ?>
        </div>

        <div class="col-md-6 lead_cold_calling" <?php echo  ($leads->source_lead == '1' ) ? '' : 'style="display: none"';?> >
            <label>Source of Cold-Calling<span class="mendatory">*</span></label>
            <?= $this->Form->input('lead_cold_calling',['class'=>'form-control','type'=>'text','label'=>false,'value'=>$leads->lead_cold_calling]) ?>
        </div>

        <div class="col-md-6 lead_reference" <?php echo  ($leads->source_lead == '2' ) ? '' : 'style="display: none"';?> >
            <label>Source of Reference<span class="mendatory">*</span></label>
            <?= $this->Form->input('lead_reference',['class'=>'form-control','label'=>false,'value'=>$leads->lead_reference]) ?>
        </div>

        <div class="col-md-6 lead_other"<?php echo  ($leads->source_lead == '7' ) ? '' : 'style="display: none"';?> >
            <label>Source of other<span class="mendatory">*</span></label>
            <?= $this->Form->input('lead_other',['class'=>'form-control','label'=>false,'value'=>$leads->lead_other]) ?>
        </div>

    </div>


    <div class="form-group">

        <div class="col-md-6">
            <label>Status<span class="mendatory">*</span></label>
            <?php echo $this->Form->select('status',$status_lead, array('label' => false,'class'=>'form-control status required','empty'=>'- Status of Lead -')); ?>
        </div>

        <div class="col-md-6 reason_lead" <?php echo  ($leads->status == 'archived' ) ? '' : 'style="display: none"';?> >
            <label>Reason<span class="mendatory">*</span></label>
            <?= $this->Form->input('status_archived',['class'=>'required form-control','label'=>false,'value'=>$leads->status_archived]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">

            <?php if(!isset($leads->id) || (isset($leads->leads_doc) && count($leads->leads_doc) != 2)) : ?>
            <label>Lead Documents</label>
            <div class="file-loading" >
                <input id="leads_image" name="leads_image[]" type="file" multiple>
            </div>
            <div id="placeinv-file-errors"></div>
            <?php endif;?>

           <?php if(isset($leads->leads_doc) && !empty($leads->leads_doc)) : ?>
            <br/>
            <label><strong>Uploaded Documents</strong></label>

                 <?php foreach($leads->leads_doc as $key =>$document): 
                    $ext='';
                    $ext = pathinfo($document['filename'], PATHINFO_EXTENSION);
                    
                    $img_url="";
                    switch($ext){
                         case "pdf":
                         $img_url= URL_HTTP.'img/pdflogo.jpg' ;
                         break;

                         case "doc":
                         $img_url= URL_HTTP.'img/wordlogo.png' ;
                         break;

                         case "docx":
                         $img_url= URL_HTTP.'img/wordlogo.png' ;
                         break;

                         case "docs":
                         $img_url= URL_HTTP.'img/wordlogo.png' ;
                         break;

                         default:
                        $img_url=$document['filename'];
                    }
                ?>
                <br/>
                    <div class="remove_leaddoc">
                        <a href="<?php echo $document['filename'];?>" ><img src="<?php echo $img_url ;?>" width="75px"></a> <i data-id="<?php echo encode($document['id']);?>" class="fa fa-trash"></i>
                    </div>
                <?php endforeach;?>

        <?php endif;?>
        </div>
        <div class="col-md-6">
            <?php if(isset($leads->allnotes) && !empty($leads->allnotes)) :
                foreach ($leads->allnotes as $key =>$value) : ?>
                    <div class="form-group">
                        <div class="">
                            <label>Notes (<?php echo $value['created_date'];?>) <?php echo (isset($value['created_name']) && $value['created_name'] !="") ? 'Created By: '.$value['created_name'] : '';?></label>
                            <?php echo $this->Form->input('notes[]',["type" => "textarea","label"=>false,"class" => "form-control","readonly"=>true,"rows"=>2, "value"=> $value['notes']]);?>
                        </div>
                    </div>
                <?php   endforeach;
            endif;
            ?>
            <div class="form-group">
                <div class="">
                    <label>Add Notes</label>
                    <?php echo $this->Form->input('new_notes',["type" => "textarea","label"=>false,"class" => "form-control","rows"=>2]);?>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="col-md-12">
            <?= $this->Form->button(__('Submit',['class'=>'121'])); ?>
        </div>
    </div>

    <?= $this->Form->end() ?>

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
            allowedFileExtensions: ["jpg", "jpeg", "png","pdf","doc","docs","docx"],
            elErrorContainer: '#placeinv-file-errors',
            maxFileSize: 1024,
        });

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

        <?php if(isset($leads->id) && $leads->id !="") : ?>
            $(".category_id, #organization, #name-of-project, .area_type, #rooftop-area, #average-monthly-bill, #average-energy-consumption, #contract-load, .source_lead, #lead-cold-calling, #lead-reference, #lead-other").attr("readonly",true);
            //$(".category_id, .area_type, #organization, .source_lead").attr("disabled","disabled");
        <?php endif;?>


        $(".remove_leaddoc i").click(function () {
            $(this).removeClass('fa-trash');
            $(this).addClass('fa-circle-o-notch fa-spin');
            var leadId = $(this).attr('data-id');
            $.ajax({
                url:'<?php echo $this->Url->build(['controller' => 'Leads','action'=>'removeLeadsImages']); ?>',
                type: 'POST',
                data: jQuery.param({id: leadId}),
                beforeSend: function(xhr)
                {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                },
                success: function(data)
                {
                    var data = JSON.parse(data);
                    if(data.type == 'success') {
                        location.reload();
                    }
                    else
                    {
                        location.reload();
                    }
                }
            });
        });



    });
</script>