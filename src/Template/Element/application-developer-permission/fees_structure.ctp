<style>
	th, td {
  border: 1px solid #ddd;
  padding:5px;
  
}
</style>


<div class="row" style="border-radius:5px; padding-top:10px;">
	<div class="col-md-12">
		<?php echo $this->Form->create($Applications,['type'=>'file','name'=>'applicationform5','id'=>'applicationform5','class'=>'applicationform5']);?>
		
		<?php if(isset($app_dev_id)){ ?>
			<input type="hidden" name="app_dev_id" value="<?php echo $app_dev_id; ?>"/>
			<input type="hidden" name="tab_id" value="5" />
		<?php } else {?>
			<input type="hidden" name="tab_id" value="4" />
		<?php } ?>
		<fieldset>
			<legend>Fee structure for Provisional Registration at GEDA</legend>
				
				
			<?php 
			
			$application_fee 			= isset($feesDetails['application_fees']) ? $feesDetails['application_fees'] : ''; 
			$gst_fees 					= isset($feesDetails['gst_fees']) ? $feesDetails['gst_fees'] : '';
			$application_tax_percentage = isset($feesDetails['application_tax_percentage']) ? $feesDetails['application_tax_percentage'] : '';
			$application_total_fee 		= isset($feesDetails['application_total_fee']) ? $feesDetails['application_total_fee'] : ''; 
			?>
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>GEDA Processing Fee (in Rs.)</label>
						<?php
							echo $this->Form->input('application_fee', array('label' => false,'class'=>'form-control','readonly'=>'true','value'=>'','placeholder'=>'Processing Fee','value'=>$application_fee));
							
						?>
					</div>
					<div class="col-md-4">
						<label>GST at <?php echo $application_tax_percentage;?>% (in Rs.)</label>
						<?php echo $this->Form->input('gst_fees', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'GST at 18%','value'=>$gst_fees)); ?>
					</div>
					<div class="col-md-4">
						<label>Total (in Rs.)</label>
						<?php echo $this->Form->input('application_total_fee', array('label' => false,'readonly'=>'true','class'=>'form-control','value'=>'','placeholder'=>'Total','value'=>$application_total_fee)); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<?php echo $this->Form->input('liable_tds', array('label' => false,'value'=>'1','type'=>'checkbox','class'=>'','placeholder'=>'')); ?>
							<span class="textCheckeboxLeft">Are you liable to deduct TDS as per Income Tax Act?</span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<?php echo $this->Form->input('terms_agree', array('label' => false,'type'=>'checkbox','class'=>'terms_agree','placeholder'=>'','id'=>'terms_agree','readonly'=>'readonly','value'=>'1')); ?>
							<span class="textCheckeboxLeft">Are you Agree to <a href="javascript:;" data-toggle="modal" data-target="#agree_popup" class="agree_popup" ><strong>Terms and Conditions</strong></a></span>?
					</div>
				</div>
			</div>
		
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						Note: The developer permission fee is not refundable.
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
			
			<div class="row col-md-12">
				<div class="col-md-1">
					<?php echo $this->Form->input('Submit', array('label' => false,'class'=>'next btn btn-primary btn-lg mb-xlg cbtnsendmsg','name'=>'save_5','type'=>'submit','placeholder'=>'Disclaimer','id'=>'save_5')); ?>
				</div>
			
			</div>
		</fieldset>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="col-md-12" style="padding-bottom:10px">
		<div class="row">
			<div class="col-md-12">
				<?php if($Applications->application_type == 2) {?>
				<table>
					<caption style="font-size: 16px">NOTE: Registration fee (non-refundable) details</caption>
					<tr>
						<th>Capacity of the Project</th>
						<th>Charges in Rs. Plus GST extra</th>
					</tr>
					<tr>
						<td>Less than 1 MW</td>
						<td>Rs.13,000</td>
					</tr>
					<tr>
						<td>1 MW & Above</td>
						<td>Rs.26,000 per MW</td>
					</tr>
				</table>
				<?php }
				if($Applications->application_type == 3){ ?>
				<table>
					<caption style="font-size: 16px">NOTE: Registration fee (non-refundable) details</caption>
					<tr>
						<th>Upto 25MW</th>
						<th>Above 25MW to 50MW </th>
						<th>Above 50MW to 75MW </th>
						<th>Above 75MW to 100MW </th>
						<th>Above 100MW</th>
					</tr>
					<tr>
						<td>3.00 Lacs</td>
						<td>6.00 Lacs</td>
						<td>9.00 Lacs</td>
						<td>15.00 Lacs</td>
						<td>18.00 Lacs</td>						
					</tr>					
				</table>
				<?php } ?>
			</div>
		</div>
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
