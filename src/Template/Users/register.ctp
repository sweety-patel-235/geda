<!-- src/Template/Users/add.ctp -->

<div class="users form">
<?= $this->Form->create($user,['class' => 'validate-form']) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?= $this->Form->input('name',['class'=>'required']) ?>
        <?= $this->Form->input('email',['class'=>'required email']) ?>
        <?= $this->Form->input('password',['class'=>'required']) ?>


       
   </fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end() ?>
</div>