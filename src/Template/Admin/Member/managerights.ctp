<div class="grid_12">
	<div class="box">

<?php 
	$colcounter		= 0;
	$lasttrngroup	= "";
	$COLS_PER_ROW	= 3;
?>
<div class="content">
	<?php echo $this->Form->create('Users',array('url'=>'admin/users/managerights','name'=>'formmain','id'=>'formmain','class'=>'form-horizontal','onsubmit'=>'return validation();'));
		echo $this->Form->hidden('User.action',array('id' =>'UserAction'));?>
		<div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> Add/Remove User Rights
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
	                    <div class="col-md-6">
	                        <div class="form-group">
	                            <label class="col-md-3 control-label">User List</label>
	                            <div class="col-md-9">
									<?php echo $this->Form->select('User.id',$arrAdminuser,array('lable'=>false,'empty'=>'Select User','id'=>'UserId','onchange'=>"changeuser();","class"=>"form-control"));?>
	                            </div>
	                        </div>
	                    </div>
					</div>
					<table class="table table-striped table-bordered table-hover" id="table-example">
					<!-- Source: http://www.bundesliga.de/de/liga/tabelle/ -->
						<colgroup>
							<col class="wwe-table-col-width">
							<col class="wwe-table-col-width">
							<col class="wwe-table-col-width">
							<col class="wwe-table-col-width">
						</colgroup>
						<thead>
							<tr>
								<th scope="col" class="wwe-lang-wins-short">Module Name</th>
								<th scope="col" class="wwe-lang-draws-short" style="text-align: center;">Add</th>
								<th scope="col" class="wwe-lang-losses-short" style="text-align: center;">Edit</th>
								<th scope="col" class="wwe-lang-goals-short" style="text-align: center;">Delete</th>
								<th scope="col" class="wwe-lang-goal-difference" style="text-align: center;">View</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							if(is_array($arradmintrnmodule) && count($arradmintrnmodule) > 0){
								
							foreach($arradmintrnmodule as $kye=>$arrmodule){
								$row=$arrmodule;
								?>
									<tr>
									<td class="wwe-align-left"><?php echo $row->trnmoduletitle ?></td>
									<?php
									if(is_array($arradmintrntype) && count($arradmintrntype) > 0){
										foreach($arradmintrntype as $kye=>$arrtype){
												$type=$arrtype;
									?>
										<td style="text-align: center;"><?php echo $this->Form->checkbox('User.userrights.'.$row->id.'_'.$type->id,array('id'=>$row->id.'_'.$type->id,'value'=>$row->id.'_'.$type->id,'class'=>$row->id.'')); ?>
										</td>
										<?php }
										}?>
									</tr>
									<?php }
								}
								?>
						</tbody>
					</table>
					<div class="form-actions">
	                    <button type="submit" class="btn blue">Submit</button>
	                    <button type="button" class="btn default" onclick="goback()">Cancel</button>
	                </div>
                </div>
			</div> <!-- End of .box -->
		</div> <!-- End of .grid_12 -->
	</div>
</div>
</div>
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
function changeuser()
{
	document.getElementById('UserAction').value = '';
	document.formmain.submit();
}

function validation()
{
	var err="";
	var adminuserid = document.getElementById('UserId').value;
	if(adminuserid=="") err += "You must select a user";
	if(err!="") {
		alert(err);
		document.getElementById('UserId').focus();
		return false;
	} else {
		document.getElementById('UserAction').value = 'Save';
		document.formmain.submit();
	}
}
</script>