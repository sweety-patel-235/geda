<?php
/**
 * Admin User List.  Displays List of Admin Users
 * @package      app.View.Elements
 * @author       Pravin Sanghani
 * @since        08/09/2015
 */
?>
<!--script>
function confirmBox(obj,action)
{
	var r =  confirm('Are you sure you want to '+action+' User?');
	if(r == true)
	{
		obj.rel = 'actionRecord';
		return true;
	}
	else
	{
		return false;
	}
}
</script-->
<?php
	$blnEditAdminuserRights		= false;
	$blnEditAdminuserRights		= $Userright->checkadminrights($Userright->ANALYSTS_EDIT);
	$blnEnableAdminuserRights	= false;
	$blnEnableAdminuserRights	= $Userright->checkadminrights($Userright->ANALYSTS_ENABLE);	
	$blnDisableAdminuserRights	= false;
	$blnDisableAdminuserRights	= $Userright->checkadminrights($Userright->ANALYSTS_DISABLE);
?>
<div class="portlet-body">

	<table class="table table-striped table-bordered table-hover" id="table-example">
		<thead>
			<tr>
				<th class="sorting">Project ID</th>
				<th class="sorting">Name</th>
				<th class="sorting">Customer Name</th>
				<th class="sorting">Customer Email</th>
				<th class="sorting">City</th>
				<th class="sorting">State</th>
				<th class="sorting">Recommended Capacity</th>
				<th class="sorting">Installer Count</th>
				<th class="sorting">Status</th>
				<th class="sorting">Created Date</th>
				<th>Action</th>
			</tr>
		</thead>	
	</table>
</div> <!-- End of .content -->