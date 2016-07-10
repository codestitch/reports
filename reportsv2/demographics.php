<?php
	include_once('header.php');
?>
<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid">

		<!-- DEMOGRAPHICS --> 
		<div class="row">
			<div class="col-md-12">  

				<div class="col-md-12"> 
					<div class="portlet light"  id="MyController1" ng-controller="MyController" >
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject bold uppercase font-green-haze"> Demographics </span>
								<span class="caption-helper"></span>
							</div>  
							<div class="tools">
								<button type="button" class="btn btn-sm default blue" id="exportBtn">
									 <i class="fa fa-download"></i></button>

								<div style="margin-top: -5px; margin-right: 5px; float:left;">
									<button type="button" class="btn green-sharp btn-sm" id="viewBtn" style="margin-top: 5px;" 
										ng-click="GetCustomerSummary()">
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

							<table ng-table="tableParams" class="table table-striped" show-filter="false">
							    <tr ng-repeat="item in $data" class="even">
							        <td title="'Type'" filter="{ TYPE: 'text'}" sortable="'TYPE'" header-class="'bg-grey-left'">
							        	{{item.TYPE}}
							        </td>
							        <td title="'Acct. No.'" filter="{ memberID: 'text'}" sortable="'memberID'" header-class="'bg-grey'" style="text-align:center;">
								        	{{item.memberID }}
							        </td> 
							        <td title="'Email'" filter="{ email: 'text'}" sortable="'email'" header-class="'bg-grey'" style="text-align:center;">
								        <a data-toggle="modal" data-ng-click="ViewItem(item.memberID);">
								        	{{item.email}}  </a> 
							        </td> 
							        <td title="'Name'" filter="{ name: 'text'}" sortable="'name'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.name}}
							        </td> 
							        <td title="'Member Since'" filter="{ dateReg: 'text'}" sortable="'dateReg'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.dateReg}}
							        </td>  
							        <td title="'Birthdate'" filter="{ birthdate: 'text'}" sortable="'birthdate'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.birthdate }}
							        </td>  
							        <td title="'Gender'" filter="{ gender: 'text'}" sortable="'gender'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.gender }}
							        </td>   
							        <td title="'Mobile'" filter="{ mobile: 'text'}" sortable="'mobile'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.mobile }}
							        </td>    
							        <td title="'Civil Status'" sortable="" header-class="'bg-grey'" style="text-align:center;">
							        	 
							        </td>   
							        <td title="'Occupation'" sortable="" header-class="'bg-grey'" style="text-align:center;">
							         
							        </td>   
							        <td title="'Total Sales'" filter="{ loyaltySales: 'text'}" sortable="'loyaltySales'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.loyaltySales }}
							        </td>  
							        <td title="'Total Trans'" filter="{ loyaltyTrans: 'text'}" sortable="'loyaltyTrans'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.loyaltyTrans }}
							        </td>  
							        <td title="'DLP'" filter="{ lastPurchase: 'text'}" sortable="'lastPurchase'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.lastPurchase }}
							        </td>  
							    </tr>
							</table>  
							<div id="emptyField"></div>    

						</div>
					</div> 
				</div>    


			</div>
		</div>
		<!-- END DEMOGRAPHICS -->


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
											        <td title="'Type'" filter="{ type: 'text'}" sortable="'type'" header-class="'bg-grey-left'">
											        	{{branch.type | capitalize}}
											        </td>
											        <td title="'Member\'s Account No.'" filter="{ acctNo: 'text'}" sortable="'acctNo'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.acctNo }}
											        </td> 
											        <td title="'Trans ID'" filter="{ transID: 'text'}" sortable="'transID'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.transID }}
											        </td>  
											        <td title="'Member\'s Name'" filter="{ memName: 'text'}" sortable="'memName'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.memName }}
											        </td>  
											        <td title="'Branch of Purchase'" filter="{ branch: 'text'}" sortable="'branch'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.branch }}
											        </td>  
											        <td title="'Date of Purchase'" filter="{ transDate: 'text'}" sortable="'transDate'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.transDate }}
											        </td>  
											        <td title="'Sales Amount'" filter="{ amount: 'text'}" sortable="'amount'" header-class="'bg-grey'" style="text-align:center;">
											        	{{branch.amount | number}}
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