<div class="container-fluid">
    <div class="col-md-12">
        <?= $this->Flash->render() ?>
        <div class="modal-body">
            <?php if(isset($bdlists) && !empty($bdlists)) : ?>
                <?php   echo  $this->Form->create('projectAssign',array('url'=>array('action' => 'projectAssignBd')));
                        echo $this->Form->input('projects_id', ["type" => "hidden", "value" => (!empty($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : '')]); ?>
                        <?php foreach ($bdlists as $key =>$bd) : ?>
                            <div class="form-group font_22">
                                <input type="checkbox" name="assign_customer_ids[]" value="<?php echo $bd->id; ?>" <?php echo  (isset($bd->selected) && $bd->selected =="1") ? "checked='checked'":'';?>"> <?php echo $bd->name;?>
                            </div>
                        <?php endforeach; ?>
                <?php echo  $this->Form->button(__('Submit'), ['type' => 'submit', 'id' => 'save_note', 'class' => 'btn-primary btn pull-left']); ?>
                <?= $this->Form->end(); ?>
            <?php else:?>
                <h2> No Business Developer Found</h2>
            <?php endif; ?>

        </div>
    </div>
</div>