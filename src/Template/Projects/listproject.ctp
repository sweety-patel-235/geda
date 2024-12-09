<style type="text/css">
.project-leads .p-row{
    background: #f7f7f7;
    padding: 15px;
    margin: 10px 0;
}
.project-leads .p-title{
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
    
}
.project-leads .p-title a{
    color: #71BF57;
}
.project-leads .p-date{
    font-size: 14px;
    text-align: right;
    display: inline;
    margin-left: 10px;
}
.project-leads .action-btn{
    margin-top: 7px;
}
</style>
<?php
    $this->Html->addCrumb($pageTitle); 
?>
<div class="container project-leads">
    
<form name="form-main" method="post">
    
    <div class="row">
        <div class="col-md-8">
           <div class="col-md-4">
                <select name="customer_type" id="customer_type" class="form-control">
                    <option value="">Select Customer Type</option>
                    <?php
                    if(!empty($projectTypeList)) {
                        foreach ($projectTypeList as $key => $value) { 
                            $selected = ((!empty($this->request->data['customer_type']) && $this->request->data['customer_type'] == $key)? "selected":"");
                            echo "<option value='".$key."' $selected>".$value."</option>";
                        }
                    } ?>
                </select>
            </div>

            <div class="col-md-4">
                <input type="text" name="project_name" id="project_name" value="<?php echo (!empty($this->request->data['project_name'])?$this->request->data['project_name']:''); ?>" class="form-control" placeholder="Enter Project Name">
            </div>

            <div class="col-md-4">
                <input type="text" name="location" id="location" value="<?php echo (!empty($this->request->data['location'])?$this->request->data['location']:''); ?>" class="form-control" placeholder="Enter City or State Name">
            </div>
        </div>   
    </div>
    <br/>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-4">
                <input type="submit" name="search_project" id="search" value="Search" class="btn btn-primary" >
            </div>
        </div>   
    </div>

    <br/>
    <div class="row">
        <div class="col-md-8">
            <h5><?=$this->Paginator->counter(['format' => 'Total Projects found: {{count}}']) ?></h5>
            <!-- <div class="text-right">        
                <ul class="pagination text-right">
                <?php echo $this->Paginator->numbers([
                            'before' => $this->Paginator->prev('Prev'),
                            'after' => $this->Paginator->next('Next')]); ?>
                </ul>
            </div>
       -->

            <?php foreach($projectLeads as $projectLead): ?>
            <div class="row p-row">
                <div class="p-title">
                    <?= (!empty($projectLead->project->name) ? $this->Html->link($projectLead->project->name, ['action' => 'dashboard', encode($projectLead->project->id)]) : ''); ?>
                <span class="p-date pull-right">Date: <?= (!empty($projectLead->project->created) ? date(LIST_DATE_FORMAT,strtotime($projectLead->project->created)) : ''); ?></span>
                </div>
                <div>Capacity: <?= (isset($projectLead->project->capacity_kw)?$projectLead->project->capacity_kw:0)." kW"; ?></div>
                <div>Location: <?= (isset($projectLead->project->address)?$projectLead->project->address:'');  ?></div>
                <div>City: <?= (!empty($projectLead->project->city)?$projectLead->project->city:'') ?></div>
                
                <div class="action-btn">
                    <?php if($projectLead->status == "pending"){ ?>
                    <?= $this->Form->postLink(
                        'Accept',
                        ['action' => 'acceptlead', $projectLead->id],
                        ['confirm' => 'Are you sure?','escape' => false,'class' => "btn btn-success btn-sm"])
                    ?>

                    <?= $this->Form->postLink(
                        'Reject',
                        ['action' => 'rejectlead', $projectLead->id],
                        ['confirm' => 'Are you sure?','escape' => false,'class' => "btn btn-danger btn-sm"])
                    ?>

                    <?= $this->Form->postLink(
                        'Forword',
                        ['action' => 'forwordlead', $projectLead->id],
                        ['confirm' => 'Are you sure?','escape' => false,'class' => "btn btn-warning btn-sm"])
                    ?>
                    <?php } ?>
                </div> 
            </div>
            <?php endforeach; ?>

            <!-- Paging Starts Here -->
            <div class="text-right">        
                <ul class="pagination text-right">
                <?php echo $this->Paginator->numbers([
                            'before' => $this->Paginator->prev('Prev'),
                            'after' => $this->Paginator->next('Next')]); ?>
                </ul>
            </div>
        </div>
    </div>
</form>

</div>

<script type="text/javascript">
$('a[rel="viewView"]').click(function(){
    $.fancybox({
        'autoDimensions' : true,
        'href'    : this.href,
        'width'   : 700, 
        'type'    : 'iframe',
        'arrows'  : false,
        'scrolling':false,
        'autoSize':true,
        'mouseWheel':false
    });
    return false;
});
</script>