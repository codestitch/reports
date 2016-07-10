<?php if ($basename == 'login') { ?>
<!-- BEGIN COPYRIGHT -->
<div class="copyright text-black">2015 &copy; Appsolutely Inc.</div>
<!-- END COPYRIGHT -->
<?php } elseif ($basename == '404') { ?>
<?php } elseif ($basename == '500') { ?>
<?php } else { ?>
</div>
<!-- END CONTAINER --> 
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
		 2016 &copy; Content Management System and Reports | <a href="http://http://appsolutely.ph/" title="Get your own CMS for Loyalty Program Now!" target="_blank">Appsolutely Inc</a>

		<div style="float: right; color: rgb(105, 111, 117);; font-style: italic;">
			Codestitch Rocks!
		</div>
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div> 
<!-- END FOOTER -->
<?php } ?>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/js/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/js/plugins/bootstrap-toastr/toastr.min.js"></script>
<script src="assets/js/jquery.idletimeout.js" type="text/javascript"></script>
<script src="assets/js/jquery.idletimer.js" type="text/javascript"></script>

<!-- BEGIN Date picker scripts -->
<script src="assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js" type="text/javascript"></script> 
<script src="assets/js/plugins/bootstrap-datepicker/daterangepicker.js" type="text/javascript"></script> 
<script src="assets/js/plugins/bootstrap-datepicker/moment.min.js" type="text/javascript"></script>
<script src="assets/js/components-pickers.js" type="text/javascript"></script>

<!-- <script type="text/javascript" src="assets/js/plugins/bootstrap-modal/bootstrap-modalmanager.js"></script> 
<script type="text/javascript" src="assets/js/plugins/bootstrap-modal/bootstrap-modal.js"></script>  -->
<!-- <script type="text/javascript" src="assets/js/plugins/bootstrap-modal/ui-extended-modals.js"></script>  -->

<script src="assets/js/core/original/core.js" type="text/javascript"></script>

<script>
    jQuery(document).ready(function() {       
       ComponentsPickers.init(); 
		// UIExtendedModals.init();
    });   
</script>

<?php if ($basename == 'login') { ?>
<script src="assets/js/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script src="assets/js/core/original/login.js" type="text/javascript"></script>

<?php
	} elseif ($basename == 'demographics') {
?> 
<script type="text/javascript" src="assets/js/core/original/demographics.js"></script>
<script type="text/javascript" src="assets/js/amcharts.js"></script>
<script type="text/javascript" src="assets/js/serial.js"></script>
<script type="text/javascript" src="assets/js/pie.js"></script>
<script type="text/javascript" src="assets/js/radar.js"></script>
<script type="text/javascript" src="assets/js/light.js"></script>
<script type="text/javascript" src="assets/js/patterns.js"></script>
<script type="text/javascript" src="assets/js/chalk.js"></script>
<script type="text/javascript" src="assets/js/ammap.js"></script> 
<script type="text/javascript" src="assets/js/amstock.js"></script> 

<script type="text/javascript" src="assets/js/core/original/charts.js"></script>  
 
<?php
	} elseif ($basename == 'downloads') {
?> 


<!-- <script src="assets/js/core/original/dirPagination.js"></script>  -->
<script type="text/javascript" src="assets/js/core/original/downloads.js"></script>
<script type="text/javascript" src="assets/js/amcharts.js"></script>
<script type="text/javascript" src="assets/js/serial.js"></script> 
<script type="text/javascript" src="assets/js/pie.js"></script>
<script type="text/javascript" src="assets/js/light.js"></script> 
 
 

<?php
	} elseif ($basename == 'branch') {
?> 

<script type="text/javascript" src="assets/js/core/original/branch.js"></script> 

<?php
	} elseif ($basename == 'sales') {
?> 

<script type="text/javascript" src="assets/js/core/original/sales.js"></script>  

<?php
	} elseif ($basename == 'customer') {
?> 
 
<script type="text/javascript" src="assets/js/core/original/customer.js"></script>  
 


<?php
	} elseif ($basename == 'spend') {
?> 

<script type="text/javascript" src="assets/js/core/original/spend.js"></script>  
 

<?php
	} elseif ($basename == 'voucher') {
?> 

<script type="text/javascript" src="assets/js/core/original/voucher.js"></script>  
 


<?php
	} elseif ($basename == 'rewards') {
?> 
 
<script type="text/javascript" src="assets/js/core/original/rewards.js"></script>  


<?php
	} elseif ($basename == 'products') {
?> 

<script type="text/javascript" src="assets/js/core/original/products.js"></script>  
 

<?php } ?>

<!-- END PAGE LEVEL SCRIPTS -->
<!-- <script src="assets/admin/pages/scripts/ui-toastr.js"></script> -->
 
<script src="assets/js/metronic.js" type="text/javascript"></script>
<script src="assets/js/layout.js" type="text/javascript"></script>
<!--script src="assets/js/plugins/admin/demo.js" type="text/javascript"></script-->
<script src="assets/js/plugins/admin/ui-idletimeout.js" type="text/javascript"></script>
<script type="text/javascript">
	var basename = "<?php echo $basename ?>";
</script>
<?php if (($basename == '404') || ($basename == '500')) { ?>
<script src="assets/js/core/error.js"></script>
<?php } ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>