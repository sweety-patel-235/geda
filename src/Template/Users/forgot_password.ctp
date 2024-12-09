<?php
    $this->Html->addCrumb($pageTitle); 
?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
<div class="users form">
<?= $this->Form->create('forgot',['class' => 'validate-form','autocomplete'=>'off']) ?>
    	<div class="form-group">
    		<div class="col-md-6"> 
    			<label>Email</label>
        		<?php echo $this->Form->input('cus_email', array('label' => false,'class'=>'form-control','placeholder'=>'Email')); ?>
    		</div>
    	</div>
    	<div class="form-group">
    		<div class="col-md-8">
    			 <?php echo $this->Form->button(__('Submit'),['type'=>'submit','class'=>'btn btn-primary']); ?>
    		</div>
    	</div>
<?= $this->Form->end() ?>
</div>
</div>