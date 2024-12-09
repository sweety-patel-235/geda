<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<?php
	$this->Html->addCrumb($pageTitle); 
?>
<br/>

<div class="container project-leads">
	<?php echo $this->Form->create("form-main",array('type'=>'post','id'=>'form-main', 'url' => '/installer-list'));?>
		<?php echo $this->Flash->render('cutom_admin'); ?>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('category_name', $Installer_category,array('label' => false,'class'=>'form-control','empty'=>'-Select Category-','placeholder'=>'From Date')); ?>
					<?php echo $this->Form->hidden('download',array("value"=>0,"id"=>"download")); ?>
				</div>
				<div class="col-md-3">
					<?php 
					echo $this->Form->select('installer_name',$installers_list,array('label' => false,'class'=>'form-control chosen-select','id'=>'installer_name','style'=>'margin-left:-15px;','data-placeholder'=>'-Select Installers-',"multiple"=>"multiple")); 
					//echo $this->Form->input('installer_name', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Name','autocomplete'=>'off')); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->Form->input('Search', array('label' => false,'type'=>'submit','name'=>'Search','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Search')); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->Form->input('Reset', array('label' => false,'type'=>'submit','name'=>'Reset','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Reset','div'=>false)); ?>
				</div>
				<div class="col-md-1">
					<button type="button" class="btn green btn-download"><i class="fa fa-file-excel-o"></i></button>
				</div>
			</div>
		</div>
	<?php echo $this->Form->end();?>
	<?php echo $this->Form->create("form-main",array('type'=>'post', 'url' => '/installer-list'));?>
		<div class="row">
			<div class="col-md-12">
				<h2 class="col-md-9 mb-sm mt-sm"><strong>Installers</strong></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h5 style="margin: 10px;">
				<?=$this->Paginator->counter(['format' => 'Total Installers found: {{count}}']) ?></h5>
			</div>
			<div class="col-md-6">
				<div class="text-right">        
					<ul class="pagination text-right" style="margin: 0px;">
			  <?php  $this->Paginator->options(array(
						'url'=> array(
							'controller' => 'Installers',
							'action' => 'index'
						)));

					echo $this->Paginator->numbers([
								'before' => $this->Paginator->prev('Prev'),
								'after' => $this->Paginator->next('Next')]); ?>

					</ul>
				</div>
			</div>

			<div class="col-md-12">
				<?php 
				foreach($Installers as $Installer): ?>
				<div class="row p-row">
						<div class="p-title">
							<div class="col-md-12">
								<a href="javascript:;"><?php echo $Installer->installer_name; ?></a>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-xs-6 col-sm-6">
								<div class="col-xs-6 col-sm-4 col-lg-4">Address</div>
								<div class="col-xs-6 col-sm-8 col-lg-8">
								   <?= (isset($Installer->address) ? $Installer->address:''); ?>
								</div>
							</div>
							<div class="col-lg-6 col-xs-6 col-sm-6">
								<div class="col-xs-6 col-sm-4 col-lg-4">Email</div>
								<div class="col-xs-6 col-sm-8 col-lg-8">
									<?= (!empty($Installer->email)?$Installer->email:'') ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-xs-6 col-sm-6">
								<div class="col-xs-6 col-sm-4 col-lg-4">City</div>
								<div class="col-xs-6 col-sm-8 col-lg-8">
									<?= (!empty($Installer->city)?$Installer->city:'') ?>
								</div>
							</div>
							<div class="col-lg-6 col-xs-6 col-sm-6">
								<div class="col-xs-6 col-sm-4 col-lg-4">Category</div>
								<div class="col-xs-6 col-sm-8 col-lg-8">
								   <?= (!empty($Installer['installer_category']['category_name'])? $Installer['installer_category']['category_name'] : '') ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-xs-6 col-sm-6">
								<div class="col-xs-6 col-sm-4 col-lg-4">State</div>
								<div class="col-xs-6 col-sm-8 col-lg-8">
									<?= (isset($Installer->state)?$Installer->state:'');  ?>
								</div>
								<?php 
								if($memberViewmobile == 1)
								{
									?>
									<br>
									<div class="col-xs-6 col-sm-4 col-lg-4">Mobile</div>
									<div class="col-xs-6 col-sm-8 col-lg-8">
										<?= (isset($Installer->mobile)?$Installer->mobile:'');  ?>
									</div>
									<?php
								}
								?>
							</div>
							<div class="col-lg-6 col-xs-6 col-sm-6">
								<?php $assign_slots  = $ApplyOnlines->assign_slot_array($Installer['installer_category_mapping']['allowed_bands']); ?>
								<div class="col-xs-6 col-sm-4 col-lg-4">Available Slots</div>
								<div class="col-xs-6 col-sm-8 col-lg-8">
									<?php  echo (isset($assign_slots)) ? implode(",<br>",$assign_slots) : '-';?>
								</div>
							</div>
						</div>
						<?php if(!empty($Installer->installer_old_name)) { ?>
							<div class="row">
								<div class="col-lg-6 col-xs-6 col-sm-6">
									<div class="col-xs-6 col-sm-4 col-lg-4">Old Name</div>
									<div class="col-xs-6 col-sm-8 col-lg-8">
										<?= (isset($Installer->installer_old_name)?$Installer->installer_old_name:'');  ?>
									</div>
								</div>
								<div class="col-lg-6 col-xs-6 col-sm-6">
									<div class="col-xs-6 col-sm-4 col-lg-4">Name change on</div>
									<div class="col-xs-6 col-sm-8 col-lg-8">
										<?php  echo (isset($Installer->name_change_date) && !empty($Installer->name_change_date)) ? date(LIST_DATE_FORMAT,strtotime($Installer->name_change_date)) : '-';?>
									</div>
								</div>
							</div>
						<?php } ?>
				</div>
				<?php endforeach; ?>
			</div>
				

				<!-- Paging Starts Here -->
				<div class="text-right">        
					<ul class="pagination text-right">
					<?php 
					echo $this->Paginator->numbers([
								'before' => $this->Paginator->prev('Prev'),
								'after' => $this->Paginator->next('Next')]); ?>
					</ul>
				</div>
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.btn-download').click(function(){
			$("#download").val(1);
			$("#form-main").submit();
			return false;
		});
		$('.chosen-select').chosen();
		$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	});
$('a[rel="viewView"]').click(function(){
	$.fancybox({
		'autoDimensions' : true,
		'href'    : this.href,
		'width'   : 700, 
		'type'    : 'iframe',
		'arrows'  : false,
		'scrolling':false,
		'autoSize':true,
		'mouseWheel':false
	});
	return false;
});
</script>