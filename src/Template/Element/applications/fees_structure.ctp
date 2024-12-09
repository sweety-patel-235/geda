<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php echo $this->Form->create($Applications,['type'=>'file','name'=>'applicationform3','id'=>'applicationform3','class'=>'applicationform3']);?>
		<input type="hidden" name="tab_id" value="3" />
		
		<fieldset>
			<legend>Fee structure for Provisional Registration at GEDA</legend>
				
				<div class="form-group hide">
					<div class="col-md-6">
						<label>Bank Name</label>
						<?php echo $this->Form->input('Applications.bank_name', array('label' => false,'class'=>'form-control','placeholder'=>'Bank Name.')); ?>
					</div>
				</div>
				<div class="form-group hide">
					<div class="col-md-6">
						<label>Bank Account No.</label>
						<?php echo $this->Form->input('Applications.bank_ac_no', array('label' => false,'class'=>'form-control','placeholder'=>'Bank AC no.')); ?>
					</div>
				</div>
				<div class="form-group hide">
					<div class="col-md-6">
						<label>IFSC Code</label>
						<?php echo $this->Form->input('Applications.ifsc_code', array('label' => false,'class'=>'form-control','placeholder'=>'IFSC Code.')); ?>
					</div>
				</div>
				<div class="form-group hide">
					<div class="col-md-6">
						<label>GST No. of Consumer</label>
						<?php echo $this->Form->input('Applications.gstno', array('type' => 'text','label' => false,'class'=>'form-control','placeholder'=>'GST Number.')); ?>
					</div>
				</div>
			<?php 
			$application_fee 			= isset($applicationCategory->application_charges) ? $applicationCategory->application_charges : ''; 
			$application_tax_percentage = isset($applicationCategory->application_tax_percentage) ? $applicationCategory->application_tax_percentage : 0; 
			$gst_fees 					= ($application_fee*$application_tax_percentage)/100;
			$application_total_fee 		= $application_fee + $gst_fees; 
			?>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>GEDA Processing Fee (in Rs.)</label>
						<?php
							echo $this->Form->input('Applications.application_fee', array('label' => false,'class'=>'form-control','readonly'=>'true','value'=>'','placeholder'=>'Processing Fee','value'=>$application_fee));
							
						?>
					</div>
					<div class="col-md-4">
						<label>GST at <?php echo $application_tax_percentage;?>% (in Rs.)</label>
						<?php echo $this->Form->input('Applications.gst_fees', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'GST at 18%','value'=>$gst_fees)); ?>
					</div>
					<div class="col-md-4">
						<label>Total (in Rs.)</label>
						<?php echo $this->Form->input('Applications.application_total_fee', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'Total','value'=>$application_total_fee)); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<?php echo $this->Form->input('Applications.liable_tds', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'','placeholder'=>'')); ?>
							<span class="textCheckeboxLeft">Are you liable to deduct TDS as per Income Tax Act?</span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<?php echo $this->Form->input('Applications.terms_agree', array('label' => false,'type'=>'checkbox','class'=>'terms_agree','placeholder'=>'','id'=>'terms_agree','disabled'=>'disabled')); ?>
							<span class="textCheckeboxLeft">Are you Agree to <a href="javascript:;" data-toggle="modal" data-target="#agree_popup" class="agree_popup" ><strong>Terms and Conditions</strong></a></span>?
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<strong>(At present GST of 18% is applicable, GEDA GST No. 24AAATG1858Q1ZA)</strong>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						Note: The Provisional Registration fee submitted will be adjusted against Development Application processing fee. If applicant fails to get the Development Permission with in the stipulated time line, the Provisional Registration fee submitted by the applicant would be non-refundable.
					</div>
				</div>
			</div>
			<div class="row col-md-12">
				<div class="col-md-1" style="float: right;">
					<?php echo $this->Form->input('Submit', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_3','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_3')); ?>
				</div>
			
			</div>
		</fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<div id="agree_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Income Tax TDS Terms</h4>
			</div>
			<div class="modal-body">
				Amount Deducted as TDS should be deposited with government and e-tds return should be filed in prescribed time limit.
				<div id="message_error"></div>
				<br>
				If failed to do so, penalty of equal amount of TDS will be charged.
				<br><br>
				<div class="row">
					<div class="col-md-2">
					<?php echo $this->Form->input('I Agree',['type'=>'button','label'=>false,'class'=>'btn btn-primary ','onclick'=>'agreeClick();']); ?>
					</div>
				</div>
				<?php
				echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
