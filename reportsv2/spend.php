<?php
	include_once('header.php');
?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">    
			<!-- END PAGE HEADER-->
 
			<div class="row">
				<div class="col-md-12"> 

					<div class="col-md-6"> 
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-green-haze"></i>
									<span class="caption-subject bold uppercase font-green-haze"> Year To Date Sales </span>
									<span class="caption-helper"> </span>
								</div> 
							</div>
							<div id="MyController1" class="portlet-body" ng-controller="MyController" ng-init="GetSpentYearly()">
								<div>
									<div> 
										<div class="icon customersales">
											<i class="icon-pie-chart thestyle"></i>
										</div>
									 	<h1 class="head">₱{{ YearlySales }}</h1> 
									 	<label class="innerstyle">Sales</label> 
									</div>  
								 </div>

								<div>
									<div> 
										<div class="icon customersales">
											<i class="icon-pie-chart thestyle"></i>
										</div>
									 	<h1 class="head">{{ YearlyTransaction }}</h1> 
									 	<label class="innerstyle">Transaction</label> 
									</div> 
								 </div>

								<div>
									<div> 
										<div class="icon customersales">
											<i class="icon-pie-chart thestyle"></i>
										</div>
									 	<h1 class="head">₱{{ YearlyAverage  }}</h1> 
									 	<label class="innerstyle">Average</label> 
									</div> 
								 </div>

							</div>
						</div> 
					</div> 
					<div class="col-md-6"> 
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-green-haze"></i>
									<span class="caption-subject bold uppercase font-green-haze"> Daily Sales </span>
									<span class="caption-helper"> </span>
								</div> 
							</div>
							<div id="MyController2" class="portlet-body" ng-controller="MyController" ng-init="GetSpentDaily()">
								
								<div>
										<div> 
											<div class="icon customersales">
												<i class="icon-pie-chart thestyle"></i>
											</div>
										 	<h1 class="head">₱{{ DailySales }}</h1> 
										 	<label class="innerstyle">Sales</label> 
										</div>  
									 </div>

									<div>
										<div> 
											<div class="icon customersales">
												<i class="icon-pie-chart thestyle"></i>
											</div>
										 	<h1 class="head">{{ DailyTransaction }}</h1> 
										 	<label class="innerstyle">Transaction</label> 
										</div> 
									 </div>

									<div>
										<div> 
											<div class="icon customersales">
												<i class="icon-pie-chart thestyle"></i>
											</div>
										 	<h1 class="head">₱{{ DailyAverage  }}</h1> 
										 	<label class="innerstyle">Average</label> 
										</div> 
								 	</div> 

							</div>
						</div> 
					</div>  

				</div>
			</div> 

			<div class="row">
				<div class="col-md-12"> 

					<div class="col-md-12"> 
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-green-haze"></i>
									<span class="caption-subject bold uppercase font-green-haze"> Top 20 Customers </span>
									<span class="caption-helper"> </span>
								</div> 
							</div>
							<div id="MyController3" class="portlet-body" ng-controller="MyController" ng-init="GetSpentAverageCustomer()" >
 
 								<table ng-table="tableParams" class="table" show-filter="false">  
								    <tr ng-repeat="user in $data"  >
								        <td title="'Name'" sortable="'NAME'" header-class="'bg-grey-left'">
								        	{{user.NAME}}
								        </td>
								        <td title="'Total Spend'" sortable="'TotalSpend'" header-class="'bg-grey-left'" style="text-align:center;">
								        	{{user.TotalSpend | number}}
								        </td>
								        <td title="'Average Per Transaction'" sortable="'AveragePerTransaction'"  header-class="'bg-grey'" style="text-align:center;">
								        	{{user.AveragePerTransaction | number}}
								        </td>
								        <td title="'Average Daily Spend'" sortable="'AverageDailySpend'" header-class="'bg-grey'" style="text-align:center;">
								        	{{user.AverageDailySpend | number}}
								        </td>
								    </tr>
								</table> 
 
 
							</div>
						</div> 
					</div> 

				</div>
			</div>

			<div class="row">
				<div class="col-md-12"> 

					<div class="col-md-12"> 
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-green-haze"></i>
									<span class="caption-subject bold uppercase font-green-haze"> Average Daily Spend </span>
									<span class="caption-helper"> </span>
								</div> 
							</div>
							<div id="MyController4" class="portlet-body" ng-controller="MyController" ng-init="GetSpentDailyCustomer()" > 

	 							<table ng-table="tableParams" class="table" show-filter="false"> 
								    <tr ng-repeat="user in $data">
								        <td title="'Name'" filter="{ NAME: 'text'}" sortable="'NAME'" header-class="'bg-grey-left'">
								        	{{user.NAME}}
								        </td>
								        <td title="'Total Spend'" filter="{ TotalSpend: 'number'}" sortable="'TotalSpend'" header-class="'bg-grey'" style="text-align:center;">
								        	{{user.TotalSpend | number}}
								        </td>  
								    </tr>
								</table> 

							</div>
						</div> 
					</div> 

				</div>
			</div>
			
			<div class="row"> 
				<div class="col-md-12">  
					<div class="col-md-12"> 
						<div class="portlet light">
							<div class="portlet-title" style="margin-bottom: -11px; border-bottom: 1px solid #FFF;">
								<div class="caption" style="font-size: 27px;">
									<!-- <i class="icon-bar-chart font-green-haze"></i> -->
									<span class="caption-subject bold uppercase font-green-haze">  </span>
									<span class="caption-helper"></span>
								</div> 
								<div class="tools">
									<button type="button" class="btn green right btn-sm" id="exporter" 
									data-toggle="modal" href="#dateRangeModal"> &nbsp; Export &nbsp; </button> 
								</div>
							</div> 
						</div> 
					</div>    
				</div>  
			</div>




			<!-- EXPORT MODAL --> 
			<div class="modal fade" id="dateRangeModal" tabindex="-1"  aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Export Options</h4>
						</div>
						<div class="modal-body" id="modalBody">
							 <div class="row" id="modalRow">  
								<div class="col-md-12" style="text-align: center;">
									<div class="form-group form-md-line-input has-info">
										<select class="form-control" id="exportTypeField">
											<option value="">Select Export Module</option>
											<option value="export_totalCustomerSpent">Total Customer Spent</option>
											<option value="export_averageCustomerSpent">Average Customer Spent</option> 
										</select>
										<label for="exportTypeField">Export Module</label>
									</div>
								</div> 
									
								<div class="form-group" id="dateRangeField"> 
									<div class="col-md-12" style="text-align: center;"> 
										<span class="help-block">
										Select date range </span>
										<div class="input-group input-xlarge date-picker input-daterange" style="margin: auto;" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
											<input id="startDateField" type="text" class="form-control" name="from" placeholder="Start date">
											<span class="input-group-addon">
											to </span>
											<input id="endDateField" type="text" class="form-control" name="to" placeholder="End date">
										</div>
										<!-- /input-group -->
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