<?php
    $this->Html->addCrumb($pageTitle); 

?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
<div class="users form">
<?= $this->Form->create($user,['class' => 'validate-form','method' => 'post','type' => 'post','onSubmit'=>'javascript:return changePass_enc();']) ?>
    <fieldset>
        <?= $this->Form->input('old_password',['class'=>'required','type'=>'password','id'=>'old_password']) ?>
        <?= $this->Form->input('password1',['class'=>'required','type'=>'password','label'=>'New Password']) ?>
     	<?= $this->Form->input('password2',['class'=>'required','type'=>'password','label'=>'Confirm Password']) ?>
     
   </fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end() ?>
</div>
</div>
<script language="javascript">
	function changePass_enc()
	{
		if($("#password1").val()!='' && $("#password2").val()!='' && $("#old_password").val()!='')
		{
			var pass1 = $.base64.encode($("#password1").val());
			$("#password1").val(makeid()+pass1+makeid());
			var pass2 = $.base64.encode($("#password2").val());
			$("#password2").val(makeid()+pass2+makeid());
			var oldp  = $.base64.encode($("#old_password").val());
			$("#old_password").val(makeid()+oldp+makeid());
		}
		
	}
</script>