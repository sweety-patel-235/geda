<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<style>
.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th
{
    text-align: center;
}
</style>
<?php
//$this->Html->addCrumb('Projects', ['controller' => 'projects']);
$this->Html->addCrumb($pagetitle);

?>
<?php echo $this->Form->create('Reports',array("id"=>"formmainsummary","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
<div class="container project-leads">
    <div class="col-md-12">
        <?php //echo $this->Form->hidden('draw',array("value"=>$page_count,"id"=>"draw")); ?>
        <div class="form-body">
            <div class="row col-md-12">
                <div class="col-md-3 ">
                    <?php echo $this->Form->input('DateFrom', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'DateFrom','placeholder'=>'From Date')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $this->Form->input('DateTo', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'DateTo','placeholder'=>'To Date')); ?>
                </div>
                <div class="col-md-3">
                    <?php //echo $this->Form->input('installer_name', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'installer_name','placeholder'=>'Installer Name')); ?>
                    <?php echo $this->Form->select('installer_name',$installers_list,array('label' => false,'div'=>false,'class'=>'form-control chosen-select','id'=>'installer_name','style'=>'margin-left:-15px;','multiple'=>'true','data-placeholder'=>'-Select Installers-')); ?>
                </div>
                <?php
                if($DisplayFilter == 1)
                {
                    ?>
                    <div class="col-md-3">
                        <?php echo $this->Form->select('category',array('3001'=>'Residential','3000'=>'Non Residential'),array('label' => false,'div'=>false,'class'=>'form-control','id'=>'installer_name','style'=>'margin-left:-15px;','data-placeholder'=>'-Select Category-','empty'=>'-Select Category-')); ?>
                    </div>
                    <?php
                }
                ?>
                
            </div>
            <div class="row col-md-12">
                <div class="col-md-3 ">
                    <?php echo $this->Form->input('application_no', array('label' => false ,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium','id'=>'application_no','placeholder'=>'Application No.')); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $this->Form->input('geda_application_no', array('label' => false ,'div'=>false,'type'=>'text' , 'class'=>'form-control form-control-inline input-medium','id'=>'geda_application_no','placeholder'=>'GEDA Registration No.')); ?>
                </div>

                <div class="col-md-2 hide">
                    <?php echo $this->Form->input('page_no', array('label' => false ,'div'=>false,'type'=>'number' , 'class'=>'form-control form-control-inline input-medium','id'=>'page_no','placeholder'=>'Go To','style'=>'width:100px !important;','min'=>'1')); ?>
                    <input type="hidden" id="total_records_data" value="" name="total_records_data" />
                    
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
                    <button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
                    
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-5 col-md-6">
                        <div class="col-md-12 form-group text">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <?php  echo $this->Flash->render('cutom_admin'); ?>
        <!-- BEGIN EXAMPLE TABLE PORTLET-->

        <div class="portlet box blue-madison noborder">
            <table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
                <thead>
                    <tr>
                        <th class="">Sr.</th>
                        <th class="" style="text-align: left;">Name of Installer</th>
                        <th class="">Nos of Projects</th>
                        <th class="">Capacity</th>
                        <th class="">Category of Bidder</th>
                        <th class="">Type of Scheme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_application  = 0;
                    $total_capacity      = 0;
                    foreach($final_data as $key=>$val)
                    {
                        ?>
                        <tr>
                            <td><?php echo $key+1;?></td>
                            <td style="text-align: left;"><?php echo $final_data[$key]['installer'];?></td>
                            <td><?php echo $final_data[$key]['TOTAL_METER_INSTALLED'];?></td>
                            <td><?php echo !empty($final_data[$key]['TOTAL_CAPACITY']) ? number_format($final_data[$key]['TOTAL_CAPACITY'],3,'.','') : '0.000';?></td>
                            <td><?php echo $final_data[$key]['category_name'];?></td>
                            <td><?php echo $final_data[$key]['scheme_type'];?></td>
                        </tr>
                        <?php
                        $total_application  += $final_data[$key]['TOTAL_METER_INSTALLED'];
                        $total_capacity     += $final_data[$key]['TOTAL_CAPACITY'];
                    }
                    ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td><strong>Grand Total</strong></td>
                        <td><strong><?php echo $total_application;?></strong></td>
                        <td><strong><?php echo number_format($total_capacity,3,'.','');?></strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>


<script type="text/javascript">
    <?php
        //echo $JqdTablescr;
    ?>
    $(document).ready(function() {
    resetcustomdates(true);
    resetdates();
    $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
});
function resetcustomdates(onload)
{
    var period      = $('#SearchPeriod').val();
    var Today       = '<?php echo date("d-m-Y");?>';
    var Yesterday   = '<?php echo date("d-m-Y",strtotime("yesterday"));?>';
    $("#DateFrom").removeAttr("disabled");
    $("#DateTo").removeAttr("disabled");
    if(!onload) {
        $("#DateFrom").val(Yesterday);
        $("#DateTo").val(Today);
    }
    $("#DateFrom").datepicker({format:'dd-mm-yyyy',autoclose: true});
    $("#DateTo").datepicker({format:'dd-mm-yyyy',autoclose: true});
}
function resetdates()
{
    
}
function resetsearch()
{
    $("#DateFrom").val('');
    $("#DateTo").val('');
    $("#application_status").val('');
    $("#installer_name").val('');
    $("#application_no").val('');
    $("#geda_application_no").val('');
    $('#formmainsummary').submit();
}
function validatesearchform()
{
    return true;
}

function download_xls()
{
    $('#formmain').attr('action','<?php echo "/Reports/getreportfromexel"; ?>');
    $('#formmain').submit();

//window.location.href = WEB_ADMIN_URL+'projects/create_xls/'+project_id;
}

function click_view_projectsurveys()
{
    var project_id = $("#project_id").val();
    window.location.href="<?php echo constant('WEB_URL').'projects/viewprojectsurveyreport/';?>"+project_id;

}
</script>