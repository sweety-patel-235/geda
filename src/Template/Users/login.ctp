<?php
    $this->Html->addCrumb($pageTitle); 
?>
<!-- File: src/Template/Users/login.ctp -->
<div class="container">
<div class="users form">
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create('User',['class' => 'validate-form']) ?>
    <fieldset>
        <?= $this->Form->input('email',['class'=>'required']) ?>
        <?= $this->Form->input('password',['class'=>'required']) ?>
    </fieldset>
<?= $this->Form->button(__('Login')); ?>
<?= $this->Form->end() ?>
</div>
</div>