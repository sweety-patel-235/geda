<div class="page-header-inner">
	<!-- BEGIN LOGO -->
	<div class="page-logo">
		<a href="<?php echo URL_HTTP.ADMIN_PATH."dashboard"?>">
			<img src="<?php echo IMAGE_URL."logo-sm.png" ?>" alt="logo" class="logo-default"/>
		</a>
		<div class="menu-toggler sidebar-toggler">
			<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
		</div>
	</div>
	<!-- END LOGO -->
	<!-- BEGIN RESPONSIVE MENU TOGGLER -->
	<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
	</a>
	<!-- END RESPONSIVE MENU TOGGLER -->
	
	<!-- END PAGE ACTIONS -->
	<!-- BEGIN PAGE TOP -->
	<div class="page-top">
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<!-- BEGIN NOTIFICATION DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<?php if (isset($Session)) { ?>
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<?php 
							if(!empty($Session->read('User.id')) && file_exists(PROFILE_PATH.$Session->read('User.id').'/r_'.$Session->read('User.profile_pic'))) {
								echo '<img alt="" class="img-circle" src="/'.PROFILE_PATH.$Session->read('User.id').'/r_'.$Session->read('User.profile_pic').'"/>';
							} else {
								echo '<img alt="" class="img-circle" src="/img/default-img.png"/>';
							} 
						?>
						<span class="username username-hide-on-mobile"> <?php echo $Session->read('User.displayname') ?> </span>
						<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="<?php echo WEB_ADMIN_URL."users/edit/".encode($Session->read('User.id')); ?>">
							<i class="icon-user"></i> My Profile </a>
						</li>
						<li>
							<a href="<?php echo WEB_ADMIN_URL."users/change_password/".encode($Session->read('User.id')); ?>">
							<i class="icon-user"></i> Change Password </a>
						</li>
						<li>
							<a href="<?php echo WEB_ADMIN_URL."users/logout"; ?>">
								<i class="icon-key"></i> Log Out 
							</a>
						</li>
					</ul>
				</li>
				<?php } ?>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END PAGE TOP -->
</div>