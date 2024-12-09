<?php
	$this->Html->addCrumb($pageTitle); 
?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
<div class="users form">
<?= $this->Form->create($user,['class' => 'validate-form','method'=>'post','type'=>'post']) ?>
    <fieldset>
    	<label>Email : <?php echo $user->email ;?></label>
        <?= $this->Form->input('name',['class'=>'required']) ?>
       	<?= $this->Form->input('mobile') ?>
     
   </fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end() ?>
</div>
</div>