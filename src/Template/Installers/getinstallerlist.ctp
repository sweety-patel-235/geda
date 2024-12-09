<?php
$this->Html->addCrumb($pageTitle);
/* echo "<pre>";
	print_r($arrReturn);
	echo "</pre>";
	die();  */
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p>It is in the interest of the Solar PV Installer to provide as much details as possiblie. This will help in the overall rating and visibility of your organization.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12"><h4><strong><?php echo $pageTitle;?></strong></h4></div>
	</div>
	<div class="clearfix"></div>
	<?php echo $this->Form->create('',['name'=>'installer_form','id'=>'installer_form']);?>
	<div class="row">
        <div class="col-md-12">
           <div class="col-md-6">
                <input type="text" name="installer_name" id="installer_name" value="<?php echo (!empty($this->request->data['installer_name'])?$this->request->data['installer_name']:''); ?>" class="form-control" placeholder="Enter Installer Name">
            </div>
            <div class="col-md-6">
                <input type="submit" name="search_project" id="search" value="Search" class="btn btn-primary" >
            </div>
        </div>   
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                
            </div>
        </div>   
    </div>
	
	<div class="row" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
		<div class="col-md-12">
			<?php foreach ($arrReturn as $key=>$data){ ?>
			<div class="clearfix"></div>
			<div class="row">
				<div class="cgetinstaller">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php
						if($key != 0)
						{
							?>
							<hr/>
							<?php
						}
						?>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php echo $this->Form->input('installer_id[]', array('label'=>false,'div'=>false,'type'=>'checkbox','class'=>'form-control form-control-inline','id'=>'checkedrow[]','value'=>$data->id));?>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<label for="checkedrow[]"><?php echo $data->installer_name; ?></label>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<label for="checkedrow[]"><?php echo $data->address; ?></label>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<label for="checkedrow[]"><?php echo $data->city; ?></label>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<label for="checkedrow[]"><?php echo $data->state; ?></label>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
			<div class="clearfix"></div>
			<div class="row" style="margin-top: 20px;">
				<div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<?php echo $this->Form->button(__('Submit'),['type'=>'submit','id'=>'submit','class'=>'btn-primary btn pull-left']); ?>
				</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<script>
$('.panel-heading u').click(function() {
$('.panel-heading').removeClass('actives');
$(this).parents('.panel-heading').addClass('actives');
$('.panel-title').removeClass('actives'); //just to make a visual sense
$(this).parent().addClass('actives'); //just to make a visual sense
});
</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/auto_suggestion/installercompany.js"></script>