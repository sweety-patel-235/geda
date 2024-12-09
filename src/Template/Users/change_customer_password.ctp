<?php
    $this->Html->addCrumb($pageTitle); 
?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
<div class="users form">
<?= $this->Form->create('cahnge_password',['class' => 'validate-form','autocomplete'=>'off']) ?>
        <div class="form-group">
            <div class="col-md-6"> 
                <label>Enter Password</label>
                <?php echo $this->Form->input('cus_pass', array('label' => false,'class'=>'form-control','placeholder'=>'Enter Password','type'=>'Password')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6"> 
                <label>Confirm Password</label>
                <?php echo $this->Form->input('confirm_pass', array('label' => false,'class'=>'form-control','placeholder'=>'Confirm Password','type'=>'Password')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-8">
                 <?php echo $this->Form->button(__('Change Password'),['type'=>'submit','class'=>'btn btn-primary']); ?>
            </div>
        </div>
<?= $this->Form->end() ?>
</div>
</div>