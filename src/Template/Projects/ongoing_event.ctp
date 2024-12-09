<?php
$this->Html->addCrumb('Events', ['controller' => 'events']);
$this->Html->addCrumb($eventData->event_title);
?>
<?php echo  $this->Html->script('/plugins/jquery.countdown/jquery.countdown.min.js', ['block' => 'scriptBottom']);?>
<div class="grid_12">
    <div class="box">
        <div class="content event-details">
            <div class="portlet box">
                <div class="portlet-body ">
                    <div class="event-type"><?php echo $eventType;?> Event</div>
                    <section>
                        <div class="event-head-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="event-title"><?php echo $eventData->event_title; ?></div>
                                    <div class="company">Company: <?php echo $eventData->event_title; ?></div>
                                </div>
                                <div class="col-md-2 col-md-offset-4">
                                    <div class="time-heading">Remaining Time</div>
                                    <div class="time red" data-countdown="<?php echo $eventData->event_end_date->format("Y/m/d H:i:s");?>">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="time-heading">Bids</div>
                                    <div class="time yellow"><?php echo $totalBids; ?></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="time-heading">Your Bid</div>
                                    <div class="time blue">
                                        <?php echo (isset($bidDetails->bid_amount)) ? "$".$bidDetails->bid_amount : "N/A"; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div ><?
                                        if(!empty($bidDetails)){
                                        echo "Bid Placed: ".$bidDetails->created->format(LIST_DATETIME_FORMAT)."<br>";    
                                        echo "Last Modified: ".$bidDetails->created->format(LIST_DATETIME_FORMAT)."<br>";
                                        echo $this->Html->link('Update Bid',
                                        ['action' => 'updatebid', encode($bidDetails->id)],
                                        ['class' => 'btn btn-xs btn-success']);

                                        echo " ".$this->Form->postLink(
                                        'Delete Bid',
                                        ['action' => 'deletebid', encode($bidDetails->id)],
                                        ['confirm' => 'Are you sure?','class' => 'btn btn-xs btn-danger']);
                                        
                                        }else{
                                        echo $this->Html->link('Place Bid', ['action' => 'placebid', encode($eventData->id)],
                                        ['class' => 'btn btn-xs btn-success']);
                                        }
                                    ?></div>
                                </div>
                                <div class="col-md-2 col-md-offset-1">
                                    
                                    <div class="time-heading">Start Bid</div>
                                    <div class="time green"><?php echo "$".$eventData->project_amount; ?></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="time-heading">Current Bid</div>
                                    <div class="time green"><?php echo "$".$lastBid; ?></div>
                                    
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <section>
                        <div class="text-left event-desc-head"  role="button" data-toggle="collapse" href="#event-desc" aria-expanded="true" aria-controls="event-desc">
                            Event Details <i class="fa fa-plus pull-right"></i>
                        </div>
                        <div id="event-desc" class="form-group">
                            <?php echo nl2br($eventData->event_details); ?>
                        </div>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
$(function(){
    $('[data-countdown]').each(function() {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            $this.html(event.strftime('%H:%M:%S'));
        });
    });
});
</script>