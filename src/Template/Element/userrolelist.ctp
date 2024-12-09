<?php
/**
 * Admin User Role List.  Displays List of Admin User Roles
 * @package      app.View.Elements
 * @author       Jitendra Rathod
 * @since        02/12/2013
 */

$blnEditAdminuserroleRights	= false;
$blnEditAdminuserroleRights	= $Userright->checkadminrights($Userright->EDIT_ADMIN_USER_ROLE);
$blnDeleteAdminuserroleRights	= false;
//$blnDeleteAdminuserroleRights	= $this->Userright->checkadminrights($this->Userright->DELETE_ADMIN_USER_ROLE);
?>
<div class="portlet-body">
	<table class="table table-striped table-bordered table-hover" id="grid_table">
		<thead>
			<th class="grid_caption sortable">Role ID</th>
			<th  class="grid_caption sortable">Role</th>
			<th class="grid_caption sortable">Created</th>
			<th class="grid_caption sortable">Modified</th>
			<th>Action</th>
		</thead>
	</table>
</div>