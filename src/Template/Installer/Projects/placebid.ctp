<?php
    $this->Html->addCrumb('Events', ['controller' => 'events']); 
    $this->Html->addCrumb('Update Bid'); 
?>
<h1>Place Bid</h1>
<?php
echo $this->Form->create($eventBid,['class' => 'validate-form']);
// just added the categories input
echo $this->Form->input('bid_amount',['class' => 'required number','type' => 'text']);
echo $this->Form->input('bid_desc', ['rows' => '3','class' => 'required']);
echo $this->Form->button(__('Place Bid'));
echo $this->Form->end();