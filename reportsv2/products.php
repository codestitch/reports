<?php
	include_once('header.php');
?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">   
			<!-- BEGIN PAGE HEAD -->  

			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">


					<div class="col-md-12">
						<!-- BEGIN CHART PORTLET-->
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-green-haze"></i>
									<span class="caption-subject bold uppercase font-green-haze"> Daily Product Statistics</span>
								</div> 
							</div>
						<div id="MyController" class="portlet-body" ng-controller="MyController" ng-init="GetDailyProduct()" >

							<div> 
								<i class="fa fa-bar-chart-o"></i>
								<span id="chartLabel">Total: 0</span>
								<br><br>
							</div>
							<table ng-table="tableParams" class="table" show-filter="false"> 
							    <tr ng-repeat="user in $data" >
							        <td title="'Product'" filter="{ name: 'text'}" sortable="'name'" header-class="'bg-grey-left'">
							        	{{user.name}}
							        </td>
							        <td title="'Total'" filter="{ Total: 'text'}" sortable="'Total'" header-class="'bg-grey'" style="text-align:center;">
							        	{{user.Total}}
							        </td> 
							        <td title="'Sales'" filter="{ Sales: 'text'}" sortable="'Sales'" header-class="'bg-grey'" style="text-align:center;">
							        	{{user.Sales}}
							        </td> 
							    </tr>
							</table> 

						</div>
						</div>
						<!-- END CHART PORTLET-->
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
											<option value="export_dailyproductStatistics">Daily Product Statistics</option> 
										</select>
										<label for="exportTypeField">Export Module</label>
									</div>
								</div> 

								<div class="col-md-2" style="text-align: center;">
								</div> 

								<div class="col-md-2" style="text-align: center;">
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