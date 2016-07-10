<?php
	include_once('header.php');
?>

<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid"> 

		<!-- REWARDS --> 
		<div class="row">
			<div class="col-md-12">  

				<div class="col-md-12"> 
					<div class="portlet light"  id="MyController1" ng-controller="MyController" >
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject bold uppercase font-green-haze"> Redemption </span>
								<span class="caption-helper"></span>
							</div> 
							<div class="tools">
								<button type="button" class="btn btn-sm default blue" id="exportBtn">
									 <i class="fa fa-download"></i></button>

								<div style="margin-top: -5px; margin-right: 5px; float:left;">
									<button type="button" class="btn green-sharp btn-sm" id="viewBtn" style="margin-top: 5px;" 
										ng-click="GetRedemptionSummary()">
									<i class="fa fa-search-plus"></i></button> 
								</div>

							</div> 
						</div>
						<div class="portlet-body">

							<div class="row" style="margin-bottom: 12px;">

								<div class="col-md-12" > 
									<span class="help-block" style="float:left;">
									Set Date Range: </span>

									<div class="input-group input-xlarge date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy/mm/dd" style=" float: left; padding-right: 10px;">
										<input id="startDate" type="text" class="form-control" name="from" placeholder="Start date">
										<span class="input-group-addon">
										to </span>
										<input id="endDate" type="text" class="form-control" name="to" placeholder="End date">
									</div>  
								</div>  

							</div>

							<div> 
								<i class="fa fa-bar-chart-o"></i>
								<span id="totalDisplay1">Total: 0</span>
								<br><br>
							</div>

							<table ng-table="tableParams" class="table" show-filter="false">
							    <tr ng-repeat="item in $data" class="even">
							        <td title="'Branch'" filter="{ branch: 'text'}" sortable="'branch'" header-class="'bg-grey-left'">
							        		<a data-toggle="modal" data-ng-click="ViewItem(item.branch);">
												 {{item.branch}} </a>  
							        </td>
							        <td title="'No. of Transactions'" filter="{ totalMember: 'text'}" sortable="'totalMember'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.totalMember | number}}
							        </td> 
							        <td title="'Total Snaps Earned'" filter="{ earnedSnaps: 'text'}" sortable="'earnedSnaps'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.earnedSnaps | number}}
							        </td> 
							        <td title="'Total Snaps Redeemed'" filter="{ redeemedSnaps: 'text'}" sortable="'redeemedSnaps'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.redeemedSnaps | number}}
							        </td>  
							        <td title="'Twirl'" filter="{ twirl: 'text'}" sortable="'twirl'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.twirl | number}}
							        </td>  
							        <td title="'Baked Treats'" filter="{ baked: 'text'}" sortable="'baked'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.baked | number}}
							        </td>  
							        <td title="'Rice Meals'" filter="{ rice: 'text'}" sortable="'rice'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.rice | number}}
							        </td>  
							    </tr>
							</table>  
							<div id="emptyField"></div>     

						</div>
					</div> 
				</div>    


			</div>
		</div>



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
													<button type="button" class="btn btn-sm default blue" id="exportBtn2">
														 <i class="fa fa-download"></i></button> 
												</div>
											</div>

											<table ng-table="tableParams" class="table" show-filter="false">
											    <tr ng-repeat="branch in $data" class="even">
											        <td title="'Email'" filter="{ email: 'text'}" sortable="'email'" header-class="'bg-grey'" style="text-align:center;">
											        	<p style="text-align: left;">{{branch.email }}</p>
											        </td> 
											        <td title="'Member Account No.'" filter="{ memberid: 'text'}" sortable="'memberid'" header-class="'bg-grey-left'">
											        	{{branch.memberid}}
											        </td>
											        <td title="'Total Snaps Earned'" filter="{ points: 'text'}" sortable="'points'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.points | number}}
											        </td>  
											        <td title="'Total Snaps Redeemed'" filter="{ redeemedSnaps: 'text'}" sortable="'redeemedSnaps'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.redeemedSnaps | number}}
											        </td>    
											        <td title="'Twirl'" filter="{ twirl: 'text'}" sortable="'twirl'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.twirl | number}}
											        </td>  
											        <td title="'Baked Treats'" filter="{ baked: 'text'}" sortable="'baked'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.baked | number}}
											        </td>  
											        <td title="'Rice Meals'" filter="{ rice: 'text'}" sortable="'rice'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.rice | number}}
											        </td>  
											    </tr>
											</table> 
											<div id="emptyField1"></div>      

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