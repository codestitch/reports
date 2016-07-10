<?php
	include_once('header.php');
?>
<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid">
 

		<!-- SALES --> 
		<div class="row">
			<div class="col-md-12">  

				<div class="col-md-12"> 
					<div class="portlet light" id="MyController1"  ng-controller="MyController" >
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject bold uppercase font-green-haze"> Sales Summary </span>
								<span class="caption-helper"></span>
							</div>   
							<div class="tools">
								<button type="button" class="btn btn-sm default blue" id="exportBtn">
									 <i class="fa fa-download"></i></button>

								<div style="margin-top: -5px; margin-right: 5px; float:left;">
									<button type="button" class="btn green-sharp btn-sm" id="viewBtn" style="margin-top: 5px;" 
										ng-click="GetBranchSummary()">
									<i class="fa fa-search-plus"></i></button> 
								</div>

							</div>
						</div>
						<div class="portlet-body" >

							<div class="row" style="margin-bottom: 12px;">

								<div class="col-md-12" > 
									<span class="help-block" style="float:left;">
									Set Date Range: </span>

									<div class="input-group input-xlarge date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy/mm/dd" style=" float: left; padding-right: 10px;">
										<input id="branchStartDate" type="text" class="form-control" name="from" placeholder="Start date">
										<span class="input-group-addon">
										to </span>
										<input id="branchEndDate" type="text" class="form-control" name="to" placeholder="End date">
									</div>  
								</div>  

							</div>

							<div> 
								<i class="fa fa-bar-chart-o"></i>
								<span id="totalDisplay1">Total: 0</span>
								<br><br>
							</div>

							<table ng-table="tableParams" class="table" show-filter="false">
							    <tr ng-repeat="branch in $data" class="even">
							        <td title="'Branch'" filter="{ locname: 'text'}" sortable="'locname'" header-class="'bg-grey-left'">
							        	{{branch.locname}}
							        </td>
							        <td title="'Member Count'" filter="{ totalMember: 'text'}" sortable="'totalMember'" header-class="'bg-grey'" style="text-align:center;">
							        	{{branch.totalMember | number}}
							        </td> 
							        <td title="'Total Loyalty Sales'" filter="{ totalAmount: 'text'}" sortable="'totalAmount'" header-class="'bg-grey'" style="text-align:center;">
							        	{{branch.totalAmount | number}}
							        </td> 
							        <td title="'No. of Transactions'" filter="{ totalTransaction: 'text'}" sortable="'totalTransaction'" header-class="'bg-grey'" style="text-align:center;">
							        	{{branch.totalTransaction | number}}
							        </td>  
							    </tr>
							</table>  
							<div id="emptyField1"></div>    

						</div>
					</div> 
				</div>    


			</div>
		</div>


		<!-- START SALES HOUR -->
		<div class="row">
			<div class="col-md-12">  

				<div class="col-md-12"> 
					<div class="portlet light" id="MyController2" ng-controller="MyController">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject bold uppercase font-green-haze"> Sales Hour </span>
								<span class="caption-helper"></span>
							</div> 
							<div class="tools">
								<button type="button" class="btn btn-sm default blue" id="exportBtn2">
									 <i class="fa fa-download"></i></button>

								<div style="margin-top: -5px; margin-right: 5px; float:left;">
									<button type="button" class="btn green-sharp btn-sm" id="viewBtn" style="margin-top: 5px;" 
										ng-click="GetSalesReportHourly()">
									<i class="fa fa-search-plus"></i></button> 
								</div>

							</div>
						</div>
						<div class="portlet-body">

							<div class="row" style="margin-bottom: 12px;"> 

								<div class="col-md-12" > 
									<span class="help-block" style="float:left;">
									Set Date Range: </span>

									<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy/mm/dd" style="float: left;">
										<input id="hourStartDate" type="text" class="form-control" name="from" placeholder="Start date">
										<span class="input-group-addon">
										to </span>
										<input id="hourEndDate" type="text" class="form-control" name="to" placeholder="End date">
									</div>  

									<div style="width: 150px; float: left;  padding-right: 10px;">
										<div class="form-group form-md-line-input has-info">
											<select class="form-control" id="locationField">
												<option value="">Select Branch</option>
											</select> 
										</div>
									</div>  

								</div>   

							</div>   
							<div> 
								<i class="fa fa-bar-chart-o"></i>
								<span id="totalDisplay2">Total: 0</span>
								<br><br>
							</div>
							<table ng-table="tableParams" class="table" show-filter="false" id="table2">
							    <tr ng-repeat="branch in $data" class="even">
							        <td title="'Date'" filter="{ timeStamp: 'text'}" sortable="'timeStamp'" header-class="'bg-grey-left'">
							        	{{branch.timeStamp}}
							        </td>
							        <td title="'Time'" filter="{ Hour: 'text'}" sortable="'Hour'" header-class="'bg-grey'" style="text-align:center;">
							        	{{branch.Hour }}
							        </td> 
							        <td title="'Members Count'" filter="{ MembersCount: 'text'}" sortable="'MembersCount'" header-class="'bg-grey'" style="text-align:center;">
							        		<a data-toggle="modal" data-ng-click="ViewItem(branch.timeStamp, branch.Hour);">
												 {{branch.MembersCount | number}}  </a> 
							        </td> 
							        <td title="'Total Loyalty Sales'" filter="{ TotalLoyaltySales: 'text'}" sortable="'TotalLoyaltySales'" header-class="'bg-grey'" style="text-align:center;">
							        	{{branch.TotalLoyaltySales | number}}
							        </td>  
							        <td title="'No. of Transactions'" filter="{ TotalTransactions: 'text'}" sortable="'TotalTransactions'" header-class="'bg-grey'" style="text-align:center;">
							        	{{branch.TotalTransactions | number}}
							        </td>  
							    </tr>
							</table>
							<div id="emptyField2"></div>   

						</div>
					</div> 
				</div>    


			</div>
		</div>
		<!-- END SALES HOUR -->  


		<!-- MODALS --> 
		<a id="viewDetailModal" class="btn default" data-toggle="modal" href="#large" style="display: none;">open large modal</a> 
		<div class="modal fade bs-modal-lg" id="large" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-xlg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 id="tableTitle" class="modal-title">Modal Title</h4>
					</div>
					<div class="modal-body">
						
						<div class="row">
							<div class="col-md-12">  

								<div class="col-md-12"> 
									<div class="portlet light"> 
										<div id="MyController3" class="portlet-body" ng-controller="MyController" > 

											<div> 
												<i class="fa fa-bar-chart-o"></i>
												<span id="totalDisplay3">Total: 0</span>
												<div class="modalexport">
													<button type="button" class="btn btn-sm default blue" id="exportBtn3">
														 <i class="fa fa-download"></i></button> 
												</div>
											</div>

											<table ng-table="tableParams" class="table" show-filter="false">
											    <tr ng-repeat="branch in $data" class="even">
											        <td title="'Member\s Account No.'" filter="{ memberid: 'text'}" sortable="'memberid'" header-class="'bg-grey-left'">
											        	{{branch.memberid}}
											        </td>
											        <td title="'Email'" filter="{ email: 'text'}" sortable="'email'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.email }}
											        </td> 
											        <td title="'Total Loyalty Sales'" filter="{ amount: 'text'}" sortable="'amount'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.amount | number}}
											        </td>  
											    </tr>
											</table>   
											<div id="emptyField3"></div>    

										</div>
									</div> 
								</div>    

							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">Close</button> 
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>



		<a id="confirmationModal" class="btn default" data-target="#confirmDialouge" data-toggle="modal" style="display:none;"> View Demo </a> 
		<div id="confirmDialouge" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"> 
						<h4 class="modal-title">Generating Report</h4>
					</div>
					<div class="modal-body">
						 Are you sure you want to export generated report? 	
					</div> 
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">No</button>
						<button type="button" class="btn blue" data-dismiss="modal" id="generateExportBtn">Yes</button>
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