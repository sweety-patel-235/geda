<?php
/**
 * Geda Approval Form
 * @package      app.View.Elements
 * @author       Kalpak Prajapati
 * @since        24/07/2018
 */

$arrayOfQue 		= array("Solar PV Modules Made In India?",
							"Solar PV module has minimum capacity of 200Wp?",
							"Rated Output Power tolerance is between +/- 3%?",
							"Is an I-V curve been provided to the consumer by the developer under STC?",
							"Inverter Capacity is as per power plant capacity?",
							"Is PCU/Inverter capable of complete automatic operation including wake-up, syncronization & shutdown?",
							"Structures are made up of Hot dip galvanized MS mounting structures?",
							"Mounting structure steel material thickness minimum of 2.5 mm?",
							"Structure Material other than G.I.(Galvanized Iron)?",
							"Is the fasteners made up of stainless steel?",
							"Is the SPV structure well-grounded/fastened?",
							"Is minimum clearance of the structure from the roof/ground level minimum 300 mm?",
							"Cables connected through Cable glands?",
							"Is the Junction Box followed IP65 Standard and IEC 62208?",
							"MCBs/MCCB is installed?",
							"Nos. of Lighting Arrestors (if System Capacity more than 10 kW)",
							"Nos. of Earthing Protection",
							"PVC/XLPE Cables used in system?",
							"All wiring are concealed/ in rigid PVC pipe/ PVC Patti?",
							"IS an electrical drawings and Installation and O&M manuals been provided to the consumer by the developer?",
							"Additional structure/ items provided, if any, and details thereof.",
							"Remarks, if any"
						);
$non_selectbox 			= array(16,17,18,22);
$array_yes_no 			= array("1"=>"Yes","2"=>"No");
?>
<?php echo $this->Form->create('FJREDA_Status',['name'=>'JREDA_Status','id'=>'FJREDA_Status','label' => false]); ?>
<div id="messageBox"></div>
<div class="form-group text">
<?php echo $this->Form->input('approval_type',['id'=>'JREDA_approval_type','label' => true,'type'=>'hidden','value'=>'3']); ?>
<?php echo $this->Form->input('appid',['id'=>'JREDA_application_id','label' => true,'type'=>'hidden']); ?>
<?php foreach($arrayOfQue as $key=>$que) { ?>
	<div class="row pad-bottom-10">
	<?php if (!in_array($key,$non_selectbox)) { ?>
		<div class="col-md-8">
			<label><?php echo $que;?></label>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->select('que['.$key.']',$array_yes_no,["class" =>"form-control que_".$key,'id'=>'que['.$key.']']); ?>
		</div>
	<?php } else { ?>
		<div class="col-md-8">
			<label><?php echo $que;?></label>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->textarea('que['.$key.']',[ "class" =>"form-control que_".$key,
					                                            'id'=>'que['.$key.']',
					                                            'cols'=>'25',
					                                            'rows'=>'5',
					                                            'placeholder' => 'Comments, if any']);
			?>
		</div>
	<?php } ?>
	</div>
<?php } ?>
<div class="row pad-bottom-10">
	<div class="col-md-12">
		<?php echo $this->Form->select('application_status',array("1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'JREDA_application_status']); ?>
	</div>
</div>
<div class="row pad-bottom-10">
	<div class="col-md-12">
		<?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",
		                                            'id'=>'JREDA_reason',
		                                            'cols'=>'50','rows'=>'5',
		                                            'placeholder' => 'Comments, if any']);
		?>
	</div>
</div>
<div class="row">
    <div class="col-md-2">
    <?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_6','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FJREDA_Status']); ?>
    </div>
</div>
<?php
echo $this->Form->end(); ?>
<!-- End of .content -->