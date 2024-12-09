<?php
    $this->Html->addCrumb($pageTitle); 
?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
<div class="users form">
<?= $this->Form->create('verifyotp',['class' => 'validate-form','autocomplete'=>'off']) ?>
    	<div class="form-group">
    		<div class="col-md-6"> 
    			<label>Enter OTP</label>
        		<?php echo $this->Form->input('cus_otp', array('label' => false,'class'=>'form-control','placeholder'=>'OTP')); ?>
    		</div>
    	</div>
    	<div class="form-group">
    		<div class="col-md-2">
    			 <?php echo $this->Form->button(__('Verify OTP'),['type'=>'submit','class'=>'btn btn-primary','onclick'=>'verifyotp()']); ?>
    		</div>
            <div class="col-md-2">
                 <?php echo $this->Form->button(__('Resend OTP'),['type'=>'button','class'=>'btn btn-primary','name'=>'resend','onclick'=>'resendotp()']); ?>
            </div>
            <div class="col-md-2">
                 <i class="fa fa-circle-o-notch fa-spin" id="spinnerloder" style="font-size:24px;margin-left: 14px;color:#71bf57;display: none;"></i>
             </div>
            <?php echo $this->Form->input('cus_id', array('label' => false,'class'=>'form-control','placeholder'=>'OTP','type'=>'hidden','value'=>$cus_id,'id'=>'cus_id')); ?>
    	</div>
<?= $this->Form->end() ?>
</div>
</div>
<script type="text/javascript">
    function verifyotp(){
        $('#spinnerloder').show();
    }
    function resendotp(){
         $('#spinnerloder').show();
        var co_id= $("#cus_id").val();
            $.ajax({
                url:'<?php echo $this->Url->build(['controller' => 'Users','action'=>'forgot_password']); ?>',
                type: 'POST',
                data: jQuery.param({co_id: co_id,action:'resend_otp'}),

                success: function(data)
                {   
                    var data = JSON.parse(data);
                    if(data.success == 1){
                        $('#spinnerloder').hide();
                        window.location.reload();
                    }
                }
            });
    }
</script>