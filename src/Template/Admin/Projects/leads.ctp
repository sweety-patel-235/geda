
<style>
    #myMap {
        width: 100%;
        height: 200px;
    }
</style>

    <?php echo $this->Form->create($leads,array("id"=>"formmain","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
    <div class="row">
        <div class="col-md-12">
            <?php  echo $this->Flash->render('cutom_admin'); ?>
            <div class="portlet box blue-madison">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list-ul"></i>Create List
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
                                    <?php echo $this->Form->select('category_id',$category, array('label' => false,'class'=>'form-control','placeholder'=>'Select Category','empty'=>'-Select Category-')); ?>

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
                                    <?= $this->Form->input('project_name',['class'=>'required form-control','type' =>'text', 'label'=>'Name of Project','value'=>$leads->project_name]) ?>
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
                                     <?= $this->Form->input("area",['class'=>'required form-control','type' =>'number', 'label'=>'Rooftop Area','value'=>$leads->area]) ?>  
                                </div> 
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <?= $this->Form->input("avg_monthly_bill",['class'=>'required form-control','type' =>'number', 'label'=>'Average Monthly Bill','placeholder'=>'(Rs./ kWh)','value'=>$leads->avg_monthly_bill]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $this->Form->input('avg_energy_consum',['class'=>'required form-control', 'type' =>'number', 'label'=>'Average Energy Consumption','placeholder'=>'In (kWh)','value'=>$leads->avg_energy_consum]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $this->Form->input('contract_load',['class'=>'required form-control','type' =>'number','label'=>' Contract Load','placeholder'=>'In (kW)','value'=>$leads->contract_load]) ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <label><strong>Source of Lead?</strong></label>
                                    <?php echo $this->Form->select('source_lead',$source_lead, array('label' => false,'class'=>'form-control source_lead required','empty'=>'-Select Source of Lead-')); ?>
                                </div>
                                <div class="col-md-6 lead_cold_calling" <?php echo  ($leads->source_lead == '1' ) ? '' : 'style="display: none"';?> >
                                    <?= $this->Form->input('lead_cold_calling',['class'=>'form-control','type'=>'text','label'=>'Source of Cold-Calling','value'=>$leads->lead_cold_calling]) ?>
                                </div>
                                <div class="col-md-6 lead_reference" <?php echo  ($leads->source_lead == '2' ) ? '' : 'style="display: none"';?> >
                                    <?= $this->Form->input('lead_reference',['class'=>'form-control','label'=>'Source of Reference','value'=>$leads->lead_reference]) ?>
                                </div>
                                <div class="col-md-6 lead_other"<?php echo  ($leads->source_lead == '7' ) ? '' : 'style="display: none"';?> >
                                    <?= $this->Form->input('lead_other',['class'=>'form-control','label'=>'Source of other','value'=>$leads->lead_other]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label><strong>Lead Status</strong></label>
                                    <?php echo $this->Form->select('status',$status_lead, array('label' => false,'class'=>'form-control status required','empty'=>'- Status of Lead -')); ?>
                                </div>
                                <div class="col-md-6 reason_lead" <?php echo  ($leads->status == 'archived' ) ? '' : 'style="display: none"';?> >
                                    <?= $this->Form->input('status_archived',['class'=>'required form-control','label'=>' Reason','value'=>$leads->status_archived]) ?>
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

            url: '<?php echo $this->Url->build(['controller' => 'Projects','action'=>'get_customer_list']); ?>',
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
    });
</script>