<?php
 
	$demographics = "";
	$onlinestore = "";
	$customer = "";
	$branch = ""; 
	$products = "";
	$spend = "";
	$rewards = "";
	$dashboard = "";
	$voucher = "";
	$downloads = "";
	$sales = "";

	if ($basename == 'demographics') {
        $demographics = "active";
    }
	else if ($basename == 'onlinestore') {
        $onlinestore = "active";
    } 
	else if ($basename == 'customer') {
        $customer = "active";
    } 
	else if ($basename == 'branch') {
        $branch = "active";
    } 
	else if ($basename == 'sales') {
        $sales = "active";
    } 
	else if ($basename == 'products') {
        $products = "active";
    } 
	else if ($basename == 'spend') {
        $spend = "active";
    } 
	else if ($basename == 'rewards') {
        $rewards = "active";
    } 
	else if ($basename == 'dashboard') {
        $dashboard = "active";
    } 
	else if ($basename == 'voucher') {
        $voucher = "active";
    } 
	else if ($basename == 'downloads') {
        $downloads = "active";
    } 

?>
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu">
		<div class="container"> 
			<!-- BEGIN MEGA MENU -->
			<!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
			<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
			<div class="hor-menu ">
				<ul class="nav navbar-nav">
					<li class=" <?php echo $downloads; ?>">
						<a href="downloads.php">Downloads</a>
					</li>
					<li class=" <?php echo $demographics; ?>">
						<a href="demographics.php">Demographics</a>
					</li>
					<li class=" <?php echo $sales; ?>">
						<a href="sales.php">Loyalty Sales Report</a>
					</li>
					<li class=" <?php echo $rewards; ?>">
						<a href="rewards.php">Redemption Report</a>
					</li>
					<!-- <li class=" <?php echo $promo; ?>">
						<a href="promo.php">Promo Report</a>
					</li> -->
					<li class=" <?php echo $special; ?>">
						<a href="customer.php">Special Report</a>
					</li>
				</ul>
			</div>
			<!-- END MEGA MENU -->
		</div>
	</div>
	<!-- END HEADER MENU -->


</div>
<!-- END HEADER -->

<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">