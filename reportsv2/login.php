<?php
	include_once('header.php');
?>
	
<!-- BEGIN LOGO -->
<!-- <div class="logo">
	<a href="index.php">
	<img src="assets/img/logo.png" id="logo-img" alt=""/>
	</a>
</div> -->
<div style="margin-top: 20%;"></div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<div class="login-form">
		<h3 class="form-title text-black">Login to your account</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>Enter any username and password.</span>
		</div>
		<div class="form-group" id="username-form">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" id="username"/>
			</div>
		</div>
		<div class="form-group" id="password-form">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="password"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn blue pull-right" id="btn-login-submit">Login <i class="icon-action-redo"></i></button>
		</div> 
		<br><br>
	</div>
	<!-- END LOGIN FORM --> 
</div>
<!-- END LOGIN -->

<?php
	include_once('footer.php');
?>