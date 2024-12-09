<h3 class="form-title">Sign In</h3>
<?php /*print_r($this->validationErrors);exit;*/
echo $this->Flash->render('cutom_admin');
echo $this->Form->create($Login, array("action"=>'','url'=>array('controller'=>'users','action'=>'login'),
									"id"=>"loginForm",
									"inputDefaults" => array("label"=>false,"div"=>false),
									"class"=>"login-form")); ?>
<div class="form-group">
	<label class="control-label visible-ie8 visible-ie9">Username</label>
	
	<?php echo $this->Form->input("Login.LoginUsername",array("maxlength"=>50,"class"=>"form-control form-control-solid placeholder-no-fix","autocomplete"=>"off","placeholder"=>"Username",'label'=>false,"tabindex"=>1)); ?>
	<?php echo $this->Form->error('LoginUsername'); ?>
</div>
<div class="form-group">
	<label class="control-label visible-ie8 visible-ie9">Password</label>
	<?php echo $this->Form->password("Login.LoginPassword",array("maxlength"=>64,"class"=>"form-control form-control-solid placeholder-no-fix","autocomplete"=>"off","placeholder"=>"Password","autocomplete"=>"off","tabindex"=>2)); ?>
	<?php echo $this->Form->error('LoginPassword'); ?>
</div>
<div class="form-actions">
	<?php echo $this->Form->button("Login",array("type"=>"submit","class"=>"btn btn-success btn-block uppercase","value"=>"Login","tabindex"=>3)); ?>
</div>
<?php echo $this->Form->end();?>