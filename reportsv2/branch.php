<?php
	include_once('header.php');
?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content"  id="contentBody">     

			<!-- DAILY BRANCH STATISTICS -->

			<div class="row">
				<div class="col-md-12">  

					<div class="col-md-12"> 
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-green-haze"></i>
									<span class="caption-subject bold uppercase font-green-haze"> Branch Summary </span>
									<span class="caption-helper"></span>
								</div>  
							</div>
							<div id="MyController1" class="portlet-body" ng-controller="MyController" >
 
								<div class="row" style="margin-bottom: 12px;">

									<div class="col-md-6" > 
										<span class="help-block">
										Set Date Range: </span>

										<div class="input-group input-xlarge date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy/mm/dd">
											<input id="branchStartDate" type="text" class="form-control" name="from" placeholder="Start date">
											<span class="input-group-addon">
											to </span>
											<input id="branchEndDate" type="text" class="form-control" name="to" placeholder="End date">
										</div>  
									</div> 
 
									<br/><br/>
								<!-- 	<div id="locationFlat" class="col-md-4">
										<div class="form-group form-md-line-input has-info">
											<select class="form-control" id="locationField">
												<option value="">Select Location</option> 
											</select>
											<label class="help-block" for="locationField">Select Branch:</label>
										</div>
									</div>  -->
 
									<div >
										<button type="button" class="btn green btn-sm" id="viewBtn" ng-click="GetBranchSummary()" style="margin-top: -7px;">
										View Report</button>
									</div>

								</div>

								<table ng-table="tableParams" class="table" show-filter="false">
								    <tr ng-repeat="branch in $data" >
								        <td title="'Branch'" filter="{ locname: 'text'}" sortable="'locname'" header-class="'bg-grey-left'">
								        	{{branch.locname}}
								        </td>
								        <td title="'Total MemberID'" filter="{ totalMember: 'text'}" sortable="'totalMember'" header-class="'bg-grey'" style="text-align:center;">
								        	{{branch.totalMember | number}}
								        </td> 
								        <td title="'Total Amount'" filter="{ totalAmount: 'text'}" sortable="'totalAmount'" header-class="'bg-grey'" style="text-align:center;">
								        	{{branch.totalAmount | number}}
								        </td> 
								        <td title="'Total Transactions'" filter="{ totalTransaction: 'text'}" sortable="'totalTransaction'" header-class="'bg-grey'" style="text-align:center;">
								        	{{branch.totalTransaction | number}}
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