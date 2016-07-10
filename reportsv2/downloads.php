<?php
	include_once('header.php');
?>
	<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid"> 

		<!-- BEGIN REGISTRATION / DOWNLOAD-->
		<div class="row">
			<div class="col-md-12"> 

				<div class="col-md-12">
					<!-- BEGIN CHART PORTLET-->
					<div class="portlet light" style="margin-bottom: 0 !important;"> 
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject font-green-haze"> Quick Registration Dashboard</span>
							</div>  
							<div class="tools">
								<label class="toollabel">Downloads:</label>
								<div class="toolcircle">
									<i class="fa fa-android toolicon"></i> 
								</div>
								<span id="toolandroid" class="toolvalue">300,000</span> 

								<div class="toolcircle redico">
									<i class="fa fa-apple toolicon"></i> 
								</div>
								<span id="toolapple" class="toolvalue">450,120</span> 

								<div class="toolcircle greenico">
									<i class="fa fa-asterisk tooliconall"></i> 
								</div>
								<span id="tooltotal" class="toolvalue">750,120</span> 
							</div>
						</div>
						<div class="portlet-body">    

							<div class="row">

								<div class="col-md-4 "> 
									<div class="portlet light bordercontent">
										<div class="portlet-title">
											<div class="caption">
												<i class="icon-bar-chart font-green-haze"></i>
												<span class="caption-subject bold uppercase font-green-haze"> Registration </span>
												<span class="caption-helper"> </span>
											</div>  
										</div>
										<div class="portlet-body">
											<div style="height: 160px;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="statsquare">
														<div class="malecircle">
															<i class="fa fa-android iconstat"></i> 
														</div>
														<span id="androidLabel" class="statnumber">300,000</span> 
														<label class="statcaption">Total Android</label> 
													</div>
												</div> 
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="statsquare">
														<div class="femalecircle">
															<i class="fa fa-apple iconstat"></i> 
														</div>
														<span id="iosLabel" class="statnumber">450,000</span>
														<label class="statcaption">Total iOS</label> 
													</div>
												</div> 
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="statsquare last">
														<div class="lgbtcircle">
															<i class="fa fa-credit-card iconstat iconlast"></i> 
														</div>
														<span id="cardLabel" class="statnumber">750,000</span>
														<label class="statcaption">Total Card</label> 
													</div>
												</div>
											</div> 

										</div>
									</div>  
									
								</div>

								<div class="col-md-4"> 
									<div class="portlet light borderchart">
										<div class="portlet-title">
											<div class="caption">
												<i class="icon-bar-chart font-green-haze"></i>
												<span class="caption-subject bold uppercase font-green-haze"> Age Group </span>
												<span class="caption-helper"> </span>
											</div>  
										</div>
										<div class="portlet-body" >
											<div id="ageChart" >
											</div>
										</div>
									</div> 
								</div> 

								<div class="col-md-4 "> 
									<div class="portlet light bordercontent">
										<div class="portlet-title">
											<div class="caption">
												<i class="icon-bar-chart font-green-haze"></i>
												<span class="caption-subject bold uppercase font-green-haze"> Gender Group </span>
												<span class="caption-helper"> </span>
											</div>  
										</div>
										<div class="portlet-body">
											<div style="height: 160px;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="statsquare">
														<div class="malecircle">
															<i class="fa fa-male iconstat"></i> 
														</div>
														<div  id="maleLabel" class="statnumber">300,000</div> 
														<label class="statcaption">Male </label> 
													</div>
												</div> 
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="statsquare">
														<div class="femalecircle">
															<i class="fa fa-female iconstat"></i> 
														</div>
														<div id="femaleLabel" class="statnumber">450,000</div>
														<label class="statcaption">Female </label> 
													</div>
												</div>  
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="statsquare">
														<div class="lgbtcircle">
															<img src="assets/img/lgbt.png" style="width: 41px;">
														</div>
														<div id="totalgenderLabel" class="statnumber">450,000</div>
														<label class="statcaption">Total </label> 
													</div>
												</div>  
											</div> 
 

										</div>
									</div>  
									
								</div>

							</div>



						</div>
					</div>
					<!-- END CHART PORTLET-->
				</div>   

			</div>
		</div>
		<!-- END REGISTRATION / DOWNLOAD --> 


		<!-- BEGIN REGISTRATION / DOWNLOAD-->
		<div class="row">
			<div class="col-md-12">


				<div class="col-md-6">
					<!-- BEGIN CHART PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject font-green-haze" id="yearHeader"> Total Downloads for 2016</span>
							</div>  
						</div>
						<div class="portlet-body"> 
										
							<div> 
								<i class="fa fa-bar-chart-o"></i>
								<span id="totalDisplay">Total: 0</span>
								<br><br>
							</div> 
 
							<div id="appdownloadsyearly" class="chart" >
							</div>
						</div>
					</div>
					<!-- END CHART PORTLET-->
				</div>  


				<div class="col-md-6">
					<!-- BEGIN CHART PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject font-green-haze" id="monthHeader"> Total Downloads for April</span>
							</div>  
						</div>
						<div class="portlet-body"> 
										
							<div> 
								<i class="fa fa-bar-chart-o"></i>
								<span id="totalDisplay1">Total: 0</span>
								<br><br>
							</div> 

							

							<div id="appdownloadsmonthly" class="chart" >
							</div>
						</div>
					</div>
					<!-- END CHART PORTLET-->
				</div>  
				

			</div>
		</div>
		<!-- END REGISTRATION / DOWNLOAD --> 



		<!-- BEGIN REGISTRATION / DOWNLOAD-->
		<div class="row">
			<div class="col-md-12">


				

			</div>
		</div>
		<!-- END REGISTRATION / DOWNLOAD --> 
		 


		<!-- EXPORT MODAL --> 
		<div class="modal fade" id="dateRangeModal" tabindex="-1"  aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Select Date</h4>
					</div>
					<div class="modal-body">
						 <div class="row"> 
								
							<div class="col-md-12" style="text-align: center;" id="selectionField">
								<div class="form-group form-md-line-input has-info">
									<select class="form-control" id="exportTypeField">
										<option value="">Select Export Module</option>
										<option value="export_registeredcustomerapp">Registered Customer - APP</option>
										<!-- <option value="export_registeredcustomercard">Registered Customer - CARD</option>  -->
										<option value="export_userPlatform">User Platform</option> 
									</select>
									<label for="exportTypeField">Export Module</label>
								</div>
							</div>
							<div class="form-group"> 
								<div class="col-md-12" style="text-align: center;">
									<div class="input-group input-xlarge date-picker input-daterange" style="margin: auto;" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
										<input id="startDateField" type="text" class="form-control" name="from">
										<span class="input-group-addon">
										to </span>
										<input id="endDateField" type="text" class="form-control" name="to">
									</div>
									<!-- /input-group -->
									<span class="help-block">
									Select date range </span>
								</div> 
							</div> 

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal" id="closeBtn">Close</button>
						<button type="button" class="btn green" id="exportBtn">Export</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- END EXPORT MODAL --> 

		<a id="displayLoading" class="btn default" data-target="#loadingModal" data-toggle="modal" style="display:none;"> View Demo </a> 
		<div id="loadingModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"> 
						<h4 class="modal-title">Generating Report</h4>
					</div>
					<div class="modal-body">
						<br><br><br>
						<div align="center">
							<img src="assets/img/loading-spinner-grey.gif" alt="" class="loading"> 
						</div>
						<br><br>
						<p align="center">
							Thank you for patiently waiting while we are generating your report.
						</p><br><br><br> 
					</div>
					<button id="closeLoading" style="display:none;"
						 type="button" data-dismiss="modal" class="btn default">Cancel</button> 
				</div>
			</div>
		</div>

	</div>
</div>
<!-- END CONTENT -->

<?php
	include_once('footer.php');
?>