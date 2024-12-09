<?php $this->Html->addCrumb($pageTitle); ?>
<style>
    .cluster-1 {
        background-image: url(/img/mapIcons/m1.png);
        line-height: 53px;
        width: 53px;
        height: 52px;
    }

    .cluster-2 {
        background-image: url(/img/mapIcons/m2.png);
        line-height: 53px;
        width: 56px;
        height: 55px;
    }

    .cluster-3 {
        background-image: url(/img/mapIcons/m3.png);
        line-height: 66px;
        width: 66px;
        height: 65px;
    }

    .cluster-4 {
        background-image: url(/img/mapIcons/m4.png);
        line-height: 66px;
        width: 66px;
        height: 65px;
    }

    .cluster-5 {
        background-image: url(/img/mapIcons/m5.png);
        line-height: 66px;
        width: 66px;
        height: 65px;
    }

    .cluster {
        color: #FFFFFF;
        text-align: center;
        font-size: 11px;
        font-weight: bold;
    }

    .details-padding {
        padding-right: 2px !important;
    }

    .details-padding-capacity {
        padding-right: 11px !important;
    }
</style>
<div class="container dashboard-theme">
    <div class="row_back">
        <?php 
        echo $this->Form->create("form-main", array('type' => 'post', 'id' => 'form-main', 'url' => '/applications-list')); ?>
        <?php echo $this->Form->input('application_status', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '','id' => 'application_status')); ?>
        <?php echo $this->Form->input('application_type', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '', 'id' => 'application_type')); ?>
        <?php echo $this->Form->input('download', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '', 'id' => 'download')); ?>
        <?php echo $this->Form->input('application_search_no', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '', 'id' => 'application_search_no')); ?>
        <?php echo $this->Form->input('name_of_applicant', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '', 'id' => 'name_of_applicant')); ?>
        <?php echo $this->Form->input('payment_status', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '', 'id' => 'payment_status')); ?>
        <?php echo $this->Form->input('order_by_form', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => 'Applications.modified|DESC', 'id' => 'approval_status')); ?>
        <?php echo $this->Form->input('receipt_no', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => '', 'id' => 'receipt_no')); ?>
        <?php echo $this->Form->input('Search', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'value' => 'Search')); ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <fieldset>  
                    <legend>Total RE Application</legend>
                    <?php $c = 0; $status=0; 
                    foreach ($dashboardTotal as $dk => $dv) {                           
                    ?>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                            <a class="dashboard-stat dashboard-stat-v2 green <?php echo $bgColorClass[$c++] ?>" href="javascript:;"  data-id="<?php echo $applicationStatus[$status++]; ?>">
                                
                                <div class="visual">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <div class="details">
                                    
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $dv['total'] ?>">
                                            <?php echo $dv['total'] ?>
                                        </span>
                                    </div>
                                    <div class="desc"><?php echo $dv['title'] ?> <br><?php echo $dv['capacity'] ?>(MW)</div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </fieldset>
            </div>
            <?php            
            $cc = 0;
            foreach ($dashboardData as $dk => $dv) {$count = $ct = 0;               
            ?>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <fieldset>
                        <legend><?php echo $dk ?></legend>
                        
                        <?php 
                        
                        foreach ($dv[0] as $k => $v) {
                            foreach($dv[1] as $dvk => $dvv){
                                if ($k=="Application Submmited" && $k!="Pending Payment" && $k!="Provisional Letter" && $k!="Doc. Verification Pending" && $k!="Query Raised" && $k!="Query Reply" && $dvk=="Tot Cap Application Submmited") {
                                    $capacity= isset($dvv) && !empty($dvv)?$dvv:0;
                                }elseif($k!="Application Submmited" && $k=="Pending Payment" && $k!="Provisional Letter" && $k!="Doc. Verification Pending" && $k!="Query Raised" && $k!="Query Reply" && $dvk=="Tot Cap Pending Payment") {
                                    $capacity= isset($dvv) && !empty($dvv)?$dvv:0;
                                    
                                }elseif($k!="Application Submmited" && $k!="Pending Payment" && $k=="Provisional Letter" && $k!="Doc. Verification Pending" && $k!="Query Raised" && $k!="Query Reply" && $dvk=="Tot Cap Provisional Letter") {
                                    $capacity= isset($dvv) && !empty($dvv)?$dvv:0;
                                    
                                }elseif($k!="Application Submmited" && $k!="Pending Payment" && $k!="Provisional Letter" && $k=="Doc. Verification Pending" && $k!="Query Raised" && $k!="Query Reply" && $dvk=="Tot Cap Doc. Verification Pending") {
                                    $capacity= isset($dvv) && !empty($dvv)?$dvv:0;
                                }else if($k!="Application Submmited" && $k!="Pending Payment" && $k!="Provisional Letter" && $k!="Doc. Verification Pending" && $k=="Query Raised" && $k!="Query Reply" && $dvk=="Tot Cap Query Raised"){
                                    $capacity= isset($dvv) && !empty($dvv)?$dvv:0;
                                }elseif($k!="Application Submmited" && $k!="Pending Payment" && $k!="Provisional Letter" && $k!="Doc. Verification Pending" && $k!="Query Raised" && $k=="Query Reply" && $dvk=="Tot Cap Query Reply"){
                                    $capacity= isset($dvv) && !empty($dvv)?$dvv:0;
                                }
                            }
                            ?>                              
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                <a class="dashboard-stat dashboard-stat-v2 green <?php echo $bgColorClass[$count++] ?>" href="javascript:;" data-id="<?php echo $applicationStatus[$ct++]; ?>" data-cat-id="<?php echo $categoryData[$cc]; ?>">
                                    <div class="visual">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" >
                                                <?php echo $v ?>
                                            </span>
                                        </div>
                                        <div class="desc">
                                            <?php echo $k ?><br><?php echo $capacity." (MW)";
                                            ?>
                                            
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php }
                        
                        $cc++; ?>
                    </fieldset>
                </div>
                
                <?php  }?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script>
    $(".dashboard-stat-v2").click(function() {
        var Application_Status = $(this).data("id");       
        var Category_Id = $(this).data('cat-id');       
        $("#form-main").find("#application_status").val(Application_Status);
        $("#form-main").find("#application_type").val(Category_Id);
        $("#form-main").trigger("submit");
        return false;
    });
</script>