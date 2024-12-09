<?php
    echo $this->Html->addCrumb('Events', ['controller' => 'events']); 
    $this->Html->addCrumb('Update Bid', ['controller' => 'updatebid']); 
?>
<h1>Edit Event</h1>
<?php
echo $this->Form->create($event,['class' => 'validate-form']);
// just added the categories input
echo $this->Form->input('event_title');
echo $this->Form->input('event_default_currency');

echo $this->Form->input('event_amount');

echo $this->Form->input('event_date');

echo $this->Form->input('event_duration');
echo $this->Form->input('event_details', ['rows' => '3']);
echo $this->Form->button(__('Update Event'));
echo $this->Form->end();