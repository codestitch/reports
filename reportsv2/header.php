<?php
	$baseURL = '../php/api/';
	include_once($baseURL.'sessions/dashboard.session.php'); 
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"  ng-app="myApp">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo MERCHANT; ?> | <?php echo ucwords(str_ireplace("-"," ",$basename)); ?> Page</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="Customer Portal"/>
<meta content="" name="Appsolutely Inc. by fLuGr1M & CodeStitch"/>

<!-- BEGIN PACE PLUGIN FILES -->
<script src="assets/js/plugins/pace/pace.min.js" type="text/javascript"></script>
<link href="assets/js/plugins/pace/themes/pace-theme-big-counter.css" rel="stylesheet" type="text/css"/>
<!-- END PACE PLUGIN FILES -->

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/js/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/js/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/js/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="assets/js/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="assets/js/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/animate.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/js/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
<script src='https://www.google.com/recaptcha/api.js'></script>

<link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>  

<!-- CSS for Date Picker Export Function -->
<link href="assets/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>

<?php if ($basename == 'login') { ?>
<link href="assets/css/login-soft.css" rel="stylesheet" type="text/css"/>
<?php } else if (($basename == '404') || ($basename == '500')) { ?>
<link href="assets/css/error.css" rel="stylesheet" type="text/css"/>
<?php
	} else if ($basename == 'demographics') {
?>
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 

<?php
	} else if ($basename == 'branch') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 

<?php
	} else if ($basename == 'sales') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 


<?php
	} else if ($basename == 'customer') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script>  

<?php
	} else if ($basename == 'spend') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 

<?php
	} else if ($basename == 'voucher') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 

<?php
	} else if ($basename == 'rewards') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 
  
<?php
	} else if ($basename == 'products') {
?> 
<script src="assets/js/angular.min.js"></script> 
<link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script> 

<?php
	} else if ($basename == 'downloads') {
?> 

<!-- <script src="assets/js/angular.min.js"></script>  -->
<!-- <link href="assets/css/ng-table.min.css" rel="stylesheet" type="text/css"/>
<script src="assets/js/ng-table.min.js"></script>  -->
  
<?php } ?>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/default.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/light.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link href="assets/img/favicon.jpg" rel="shortcut icon"/>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<?php if ($basename == 'login') { ?>
<body class="login">
<?php } elseif ($basename == '404') { ?>
<body class="page-500-full-page">
<!-- <body class="page-404-full-page"> -->
<?php } elseif ($basename == '500') { ?>
<body class="page-500-full-page">
<?php } else { ?>
<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo"> 
				<img src="assets/img/headlogo.jpg" alt="logo" class="logo-default"/>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right"> 
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown dropdown-user dropdown-dark">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> 
							<img alt="" class="img-circle" src="assets/img/favicon.jpg"/>
							<span class="username username-hide-mobile">My Account</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="logout.php">
								<i class="icon-key"></i> Logout </a>
							</li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
			</div>
			<!-- END TOP NAVIGATION MENU -->
		</div>
	</div>
	<!-- END HEADER TOP -->


<?php include_once('sidebar.php'); } ?>

<div class="preloader-wrapper">
    <div class="preloader-conainer">
       <div class="spinner">
		  <div class="rect1"></div>
		  <div class="rect2"></div>
		  <div class="rect3"></div>
		  <div class="rect4"></div>
		  <div class="rect5"></div>
		</div>
    </div>
</div>


<div id="preloader" style="display: none;"  class="minipreloader-wrapper ">
<div id="innerpreloader" class="windows8">
	<div class="wBall" id="wBall_1">
		<div class="wInnerBall"></div>
	</div>
	<div class="wBall" id="wBall_2">
		<div class="wInnerBall"></div>
	</div>
	<div class="wBall" id="wBall_3">
		<div class="wInnerBall"></div>
	</div>
	<div class="wBall" id="wBall_4">
		<div class="wInnerBall"></div>
	</div>
	<div class="wBall" id="wBall_5">
		<div class="wInnerBall"></div>
	</div>
</div> 
</div>
