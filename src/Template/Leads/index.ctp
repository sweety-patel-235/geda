<?php
$this->Html->addCrumb($pageTitle);
?>
<style>
    .progressbar li:after {
        background-color: transparent !important;
    }
</style>
<div class="container ApplyOnline-leads">

    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li role="presentation">
                <?= $this->Html->link('New', ['action' => 'add']) ?>
            </li>
            <li role="presentation" class="<?php echo (isset($leadstatus))? '':'active';?>">
                <?= $this->Html->link('My Leads', ['action' => 'index']) ?>
            </li>
            <li role="presentation" class="<?php echo (isset($leadstatus))? 'active':'';?>">
                <?= $this->Html->link('Archive Leads', ['action' => 'index','archived']) ?>
            </li>
        </ul>
    </div>

    <div class="row">
        <?php if (!empty($leads)) : ?>
            <div class="col-md-6">
                <h5 style="margin: 10px;"><?= $this->Paginator->counter(['format' => 'Total Application found: {{count}}']) ?></h5>
            </div>
            <div class="col-md-6">
                <div class="text-right">
                    <ul class="pagination text-right" style="margin: 0px;">
                        <?php
                        echo $this->Paginator->numbers(['before' => $this->Paginator->prev('Prev'),
                            'after' => $this->Paginator->next('Next')]); ?>

                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <?php // pr($leads); ?>
    <?php foreach ($leads as $lead): ?>

        <div class="row p-row">
            <div class="row p-title">
                <div class="col-md-6" style="padding-left: 47px;">
                    <a href="<?php echo $this->Url->build(['action' => 'add', encode($lead->id)]); ?>">
                        <?php echo $lead->project_name; ?> <i class="fa fa-pencil-square-o"></i> </a>
                    <span class="application-status">
                                <small style="font-size:12px">
                                <br>
                             (<?= $lead->category_name; ?>)
                                </small>
                            </span>
                </div>

                <div class="col-md-6">
                            <span class="p-date pull-right">
                                <?php echo date("d-m-Y h:i:a", strtotime($lead->created)); ?>
                            </span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php if ($lead->organization != 0): ?>
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="col-xs-6 col-sm-3 col-lg-3">Organization</div>
                            <div class="col-xs-6 col-sm-6 col-lg-6">
                                It is a social sector/ non-profit organization
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class="col-xs-6 col-sm-3 col-lg-3">Location</div>
                        <div class="col-xs-6 col-sm-6 col-lg-6">
                            <?php echo $lead->location; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class="col-xs-6 col-sm-6 col-lg-6">Average Monthly Bill (Rs./ kWh)</div>
                        <div class="col-xs-6 col-sm-6 col-lg-6">
                            <?= $lead->avg_monthly_bill ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class="col-xs-6 col-sm-6 col-lg-6">Average Energy Consumption (kWh)</div>
                        <div class="col-xs-6 col-sm-6 col-lg-6">
                            <?= $lead->avg_energy_consum ?>
                        </div>
                    </div>


                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class="col-xs-6 col-sm-6 col-lg-6">Source of the lead?</div>
                        <div class="col-xs-6 col-sm-6 col-lg-6">
                            <?php foreach ($source_lead as $source_lead_single) {
                                if ($source_lead_single == $source_lead[$lead->source_lead]) {
                                    echo $source_lead_single;
                                }
                            } ?>
                            <?php echo ($lead->source_lead == "1") ? "(" . $lead->lead_cold_calling . ")" : "" ?>
                            <?php echo ($lead->source_lead == "2") ? "(" . $lead->lead_reference . ")" : "" ?>
                            <?php echo ($lead->source_lead == "7") ? "(" . $lead->lead_other . ")" : "" ?>
                        </div>
                    </div>
                </div>


            </div>
            <br/>
            <div class="col-md-12" style="margin-left: 18px;">
                <span> Contract Load (kW) </span>: <?= $lead->contract_load ?>
                <span>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span style="color:#71BF57;"> Status </span>: <?php echo $status_lead[$lead->status]; ?>
            </div>


            <!--<div class="row progressbar-container">
                <ul class="progressbar">
                    <?php foreach ($status_lead as $status) : ?>
                        <li class="<?php echo (isset($lead->status) && $status == $status_lead[$lead->status]) ? 'active' : '' ?>"><?php echo $status; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>-->
        </div>
        </a>
    <?php endforeach; ?>

    <div class="row">
        <?php if (!empty($leads)) : ?>
            <div class="col-md-6">
                <h5 style="margin: 10px;"><?= $this->Paginator->counter(['format' => 'Total Application found: {{count}}']) ?></h5>
            </div>
            <div class="col-md-6">
                <div class="text-right">
                    <ul class="pagination text-right" style="margin: 0px;">
                        <?php
                        echo $this->Paginator->numbers(['before' => $this->Paginator->prev('Prev'),
                            'after' => $this->Paginator->next('Next')]); ?>

                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
