<?php
$this->Html->addCrumb('Events', ['controller' => 'events','upcoming']);
$this->Html->addCrumb($eventData->event_title);
?>
<?php echo  $this->Html->script('/plugins/jquery.countdown/jquery.countdown.min.js', ['block' => 'scriptBottom']);?>
<div class="grid_12">
    <div class="box">
        <div class="content event-details">
            <div class="portlet box  ">
                
                <div class="portlet-body ">
                    <div class="event-type"><?php echo $eventType;?> Event</div>
                    <section>
                        <div class="event-head-block">
                            
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="event-title"><?php echo $eventData->event_title; ?></div>
                                    <div class="company">Company: <?php echo $eventData->event_title; ?></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="time-heading">Event Date</div>
                                    <div class="time"><?php echo $eventData->event_date->format(LIST_DATE_FORMAT); ?></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="time-heading">Event Start Time</div>
                                    <div class="time"><?php echo $eventData->event_date->format("H:i:s"); ?></div>
                                </div>
                                
                            </div>
                            <hr>
                            <div class="row">
                              
                                <div class="col-md-10">
                                    
                                    <div>  
                                        <?php if($invitationDetails->status == "pending"){ ?>
            <?= $this->Form->postLink(
                'Accept Invitation',
                ['action' => 'acceptinvitation', $invitationDetails->id],
                ['confirm' => 'Are you sure?','escape' => false,'class' => "btn btn-success"])
            ?>

            <?= $this->Form->postLink(
                'Reject Invitation',
                ['action' => 'rejectinvitation', $invitationDetails->id],
                ['confirm' => 'Are you sure?','escape' => false,'class' => "btn btn-danger"])
            ?>
            <?php }elseif($invitationDetails->status == "accepted"){
                echo '<h4>You have <span class="label label-success" style="font-size:18px"> '.$invitationDetails->status.'</span> this event invitation.</h4>';
            }else{
                echo '<h4>You have <span class="label label-danger" style="font-size:18px"> '.$invitationDetails->status.'</span> this event invitation.</h4>';
            } ?></div>
                                    
                                </div>
                                <div class="col-md-2">
                                    
                                    <div class="time-heading">Start Bid</div>
                                    <div class="time green"><?php echo "$".$eventData->project_amount; ?></div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </section>
                    <section>
                        <div class="text-left event-desc-head"  role="button" data-toggle="collapse" href="#event-desc" aria-expanded="true" aria-controls="event-desc">
                            Event Details <i class="fa fa-plus pull-right"></i>
                        </div>
                        <div id="event-desc" class="form-group ">
                            <?php echo nl2br($eventData->event_details); ?>
                        </div>
                    </section>
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
