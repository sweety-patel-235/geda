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
    <!-- Here is where we iterate through our $articles query object, printing out article info -->
    <div class="row">
        <div class="col-md-8">
        <!-- Paging Starts Here -->
        <div class="row p-row">
        <div class="p-title"><?= $this->Html->link($projectLead->project->name, ['action' => 'view', encode($projectLead->project->id)]) ?>
        <span class="p-date">on <?= $projectLead->created->format(LIST_DATE_FORMAT) ?></span>
        </div>
        <div>Capacity: <?= $projectLead->project->capacity_kw ?></div>
        <div>Location: <?= $projectLead->project->capacity_kw ?></div>
        <div>City: <?= $projectLead->project->capacity_kw ?></div>
        </div>
        
        <h3><?= $this->Paginator->counter(['format' => 'Total Leads found: {{count}}']) ?></h3>
        
        <?php foreach ($installers as $installer): ?>

    
    <div class="row p-row">
        <div class="p-title"><?= $installer->company_title ?></div>
        <div>Address: <?= $installer->address ?></div>
        <div>Rating: 10.5</div>
        <div class="action-btn">
            <?php if($projectLead->status == "pending"){ ?>
            <?= $this->Form->postLink(
                'Forword',
                ['action' => 'forwordlead', encode($projectLead->id), encode($installer->id)],
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
    </div>