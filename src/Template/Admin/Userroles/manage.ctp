<div class="grid_12">
	<div class="box">
	<!--div class="header">
		<img width="16" height="16" alt="" src="<?php echo IMAGE_URL?>icons/packs/fugue/16x16/ui-text-field-format.png">
		<h3>Add User</h3>
	</div-->
    	<div class="content">
		<?php echo $this->Form->create($Userrole,array('name'=>'formmain','id'=>'formmain','class'=>'form-horizontal')); ?>
		<?php $rolename = (empty($id))?'Add Administrator Role':'Edit Administrator Role';?>
		<?php
			$colcounter		= 0;
			$lasttrngroup	= "";
			$COLS_PER_ROW	= 3;
		?>
		<div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> <?php echo $rolename;?>
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
	                            <label class="col-md-3 control-label">Role Name</label>
	                            <div class="col-md-9">
			            			<?php echo $this->Form->input('Userrole.id',['type'=>'hidden']); ?>
	                                <?php echo $this->Form->input('Userrole.rolename', array('label' => false,'class'=>'form-control','placeholder'=>'Role Name')); ?>
	                            </div>
	                        </div>
	                    </div>
					</div>
					<table class="table table-striped table-bordered table-hover" id="table-example">
						<thead>
							<tr>
								<th scope="col" class="wwe-lang-wins-short">Module Name</th>
								<th scope="col" class="wwe-lang-draws-short" style="text-align: center;">Add</th>
								<th scope="col" class="wwe-lang-losses-short" style="text-align: center;">Edit</th>
								<th scope="col" class="wwe-lang-goals-short" style="text-align: center;">Delete</th>
								<th scope="col" class="wwe-lang-goal-difference" style="text-align: center;">List</th>
								<th scope="col" class="wwe-lang-goal-difference" style="text-align: center;">View</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if(is_array($arradmintrnmodule) && count($arradmintrnmodule) > 0 ){
						foreach($arradmintrnmodule as $kye=>$arrmodule){
						$row=$arrmodule;
						?>
							<tr>
								<td class="wwe-align-left"><?php echo $row->trnmoduletitle;?></td>
							<?php
							if(is_array($arradmintrntype) && count($arradmintrntype) > 0){
								foreach($arradmintrntype as $kye=>$arrtype){
									$type=$arrtype;?>
									<td style="text-align: center;">
										<?php
										//if(in_array($row["id"].'_'.$type['id'],$arrUserrights)) {	
											echo $this->Form->checkbox('Userrole.rights.'.$row["id"].'_'.$type['id'],array('id'=>$row['id'].'_'.$type['id'],'value'=>$row["id"].'_'.$type['id'],'class'=>$row["id"].'')); 
										/*} else {
											?><img src="<?php echo IMAGE_URL?>icons/packs/fugue/16x16/close.png" class="cancel_rights">
										<? }*/ ?>
									</td>
								<?php }
								} ?>
							</tr>
							<?php }
							}
							?>
						</tbody>
					</table>
					<div class="row">
                        <div class="col-md-offset-5 col-md-6">
                            <button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                            <button type="button" onclick="goback()" class="btn"><i class="fa fa-close"></i> Cancel</button>
                        </div>
                    </div>					
			  	</div>
			</div>
		</div> <!-- End of .content -->
		</div><!-- End of .box -->
	</div>
</div>
	<?php
	$strCancelLink = "/userroles/";
	$blnListAdminuserRights = false;
	$blnListAdminuserRights = $Userright->checkAdminrights($Userright->LIST_ADMIN_USER_ROLES);
	if(!$blnListAdminuserRights)
		 $strCancelLink= "/users/index";
	?>
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
function validation()
{
	var err="";
	var adminuserid = document.getElementById('UserId').value;
	if(adminuserid=="")
		err += "You must select a user";
	if(err!="")
	{
		alert(err);
		document.getElementById('UserId').focus();
		return false;
	}
	else
	{
		document.getElementById('UserAction').value = 'Save';
		document.formmain.submit();
	}
}
function chkGroupElememts(controlid,cName)
{
	for(i=0; i<document.formmain.elements.length; i++)
	{
		if (document.formmain.elements[i].type=="checkbox")
		{
			if (document.formmain.elements[i].id==controlid)
			{
				if(document.getElementById("chk"+controlid).checked== true) {
					document.formmain.elements[i].checked = true;
				}
				else {
					document.formmain.elements[i].checked = false;
				}
			}
		}
	}
}
function checkAll()
{
	for(i = 0; i < document.formmain.elements.length; i++)
	{
		if(document.formmain.elements[i].type== "checkbox")
		{
			if(document.formmain.clsuserrights_Allrights.checked == true)
				document.formmain.elements[i].checked=true;
			else
				document.formmain.elements[i].checked=false;
		}
	}
}
function goback()
{
    window.location.href=WEB_ADMIN_URL+'userroles/index';
}

</script>