<?php
	include_once('header.php');
?>

<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid"> 

		<!-- REWARDS --> 
		<div class="row">
			<div class="col-md-12">  

				<div class="col-md-12" ng-controller="MyController" id="MyController1"> 
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-bar-chart font-green-haze"></i>
								<span class="caption-subject bold uppercase font-green-haze"> Customer Query </span>
								<span class="caption-helper"></span>
							</div>  
							<div class="tools">
								<button type="button" class="btn btn-sm default blue" id="exportBtn" disabled>
								 <i class="fa fa-download"></i></button>
								
								<div style="margin-top: -5px; margin-right: 5px; float:left;">
									<button type="button" class="btn green-sharp btn-sm" id="viewBtn" style="margin-top: 5px;" 
										ng-click="GetCustomerQuery()">
									<i class="fa fa-search-plus"></i></button> 
								</div>

							</div>
						</div>
						<div class="portlet-body">

							<div class="row" style="padding: 12px 0px 20px 15px;"> 
					         <label style="width: 60px; float: left;margin-top: 8px; ">Filters:</label>

								<div class="input-group input-sm date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy/mm/dd" style=" float: left; padding-right: 10px; margin-top: -5px;">
									<input id="startDate" type="text" class="form-control" name="from" placeholder="Reg Start Date">
									<span class="input-group-addon" style="font-size: 12px;">
									to </span>
									<input id="endDate" type="text" class="form-control" name="to" placeholder="Reg End Date">
								</div> 
					         
					         <div style="width: 200px; float: left;  padding-right: 10px;">
					         	<div class="form-group form-md-line-input has-info">
										<input type="text" class="form-control" id="emailField" placeholder="Email">  
									</div>
					         </div> 

								<div style="width: 200px; float: left;  padding-right: 10px;">
									<div class="form-group form-md-line-input has-info">
										<select class="form-control" id="drinkField" >
											<option value="" class="firstoption">Favorite Drink</option>
											<option value="Espresso">Espresso</option> 
											<option value="Espresso Macchiatto">Espresso Macchiatto</option>
											<option value="Hot Caffe Americano">Hot Caffe Americano</option>
											<option value="Hot Caffe Latte">Hot Caffe Latte</option>
											<option value="Hot Caffe Mocha">Hot Caffe Mocha</option>
											<option value="Cappuccino">Cappuccino</option>
											<option value="Hot Caramel Latte">Hot Caramel Latte</option>
											<option value="Hot Wht Choco Mocha">Hot Wht Choco Mocha</option>
											<option value="Iced Caffe Americano">Iced Caffe Americano</option>
											<option value="Iced Caffe Latte">Iced Caffe Latte</option>
											<option value="Iced Caffe Mocha">Iced Caffe Mocha</option>
											<option value="Iced Caramel Latte">Iced Caramel Latte</option>
											<option value="Iced Wht Choco Mocha">Iced Wht Choco Mocha</option>
											<option value="Brewed Coffee">Brewed Coffee</option>
											<option value="Pour Over Benguet">Pour Over Benguet</option>
											<option value="Pour Over Kitanglad">Pour Over Kitanglad</option>
											<option value="Pour Over Matutum">Pour Over Matutum</option>
											<option value="Pour Over Mt. Apo">Pour Over Mt. Apo</option>
											<option value="Pour Over Sagada">Pour Over Sagada</option>
											<option value="French Pr Benguet">French Pr Benguet</option>
											<option value="French Pr Kitanglad">French Pr Kitanglad</option>
											<option value="French Pr Matutum">French Pr Matutum</option>
											<option value="French Pr Mt. Apo">French Pr Mt. Apo</option>
											<option value="French Pr Sagada">French Pr Sagada</option>
											<option value="Froccino Caramelo">Froccino Caramelo</option>
											<option value="Froccino Choco Chip">Froccino Choco Chip</option>
											<option value="Froccino Coffee">Froccino Coffee</option>
											<option value="Froccino Coffee Jlly">Froccino Coffee Jlly</option>
											<option value="Froccino Esp Crumble">Froccino Esp Crumble</option>
											<option value="Froccino Mocha">Froccino Mocha</option>
											<option value="Froccino Oreo">Froccino Oreo</option>
											<option value="Froc Wht Choco Mocha">Froc Wht Choco Mocha</option>
											<option value="Frz Artisanal Choco">Frz Artisanal Choco</option>
											<option value="Frz Cookies n' Cream">Frz Cookies n' Cream</option>
											<option value="Frz Matcha Green Tea">Frz Matcha Green Tea</option>
											<option value="Freeze Mixed Berry">Freeze Mixed Berry</option>
											<option value="Freeze Strawberry">Freeze Strawberry</option>
											<option value="Freeze Vanilla">Freeze Vanilla</option>
											<option value="Antarteaca Mango">Antarteaca Mango</option>
											<option value="Antarteaca Mix Berry">Antarteaca Mix Berry</option>
											<option value="Traditnal Chamomint">Traditnal Chamomint</option>
											<option value="Traditional Coconut">Traditional Coconut</option>
											<option value="Traditional Dallah">Traditional Dallah</option>
											<option value="Traditional Mango">Traditional Mango</option>
											<option value="Loose Tea Chamomint">Loose Tea Chamomint</option>
											<option value="Loose Tea Coconut">Loose Tea Coconut</option>
											<option value="Loose Tea Dallah">Loose Tea Dallah</option>
											<option value="Loose Tea Mango">Loose Tea Mango</option>
											<option value="Matcha Grn Tea Latte">Matcha Grn Tea Latte</option>
											<option value="Iced Tea Apple">Iced Tea Apple</option>
											<option value="Icd Tea Hny Dalandan">Icd Tea Hny Dalandan</option>
											<option value="Icd Tea Passion Frt">Icd Tea Passion Frt</option>
											<option value="Iced Tea Raspberry">Iced Tea Raspberry</option>
											<option value="Icd Tea Apple Chia">Icd Tea Apple Chia</option>
											<option value="Icd Tea Pch Mang Bry">Icd Tea Pch Mang Bry</option>
											<option value="Icd Tea Tropc Breeze">Icd Tea Tropc Breeze</option>
											<option value="Artisanal Choco Hot">Artisanal Choco Hot</option>
											<option value="Artisanal Choco Iced">Artisanal Choco Iced</option>
										</select> 
									</div>
								</div> 

								<div style="width: 150px; float: left;  padding-right: 10px;">
									<div class="form-group form-md-line-input has-info">
										<select class="form-control" id="genderField"> 
											<option value="" class="firstoption">Gender</option>
											<option value="male">Male</option>
											<option value="female">Female</option>
										</select> 
									</div>
								</div> 
	 
								<div style="width: 150px; float: left;  padding-right: 10px;">
									<div class="form-group form-md-line-input has-info">
										<select class="form-control" id="bdayField">
											<option value="" class="firstoption">Birth Month</option>
											<option value="1" >January</option>
											<option value="2">February</option>
											<option value="3">March</option>
											<option value="4">April</option>
											<option value="5">May</option>
											<option value="6">June</option>
											<option value="7">July</option>
											<option value="8">August</option>
											<option value="9">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
											<option value="0">No Birthday</option>
										</select> 
									</div>
								</div> 
	 

								<div style="width: 150px; float: left;  padding-right: 10px;">
									<div class="form-group form-md-line-input has-info">
										<select class="form-control" id="ageField">
											<option value="" class="firstoption">Age Range</option>
											<option value="18-25">18 to 25</option>
											<option value="26-35">26 to 35</option>
											<option value="36-45">36 to 45</option>
											<option value="46-59">46 to 59</option>
										</select> 
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
						         <td title="'Email'" filter="{ email: 'text'}" sortable="'email'" header-class="'bg-grey-left'">
 
											<img ng-if="item.image != ''" src="{{ item.image }}" width="20%" class="imgcircle">
											<img ng-if="item.image == ''" src="assets/img/boslogo.jpg" width="20%" class="imgcircle">

							        	{{item.email}}
							        </td>
							        <td title="'Name'" filter="{ lname: 'text'}" sortable="'lname'" header-class="'bg-grey-left'">
							        		<div ng-if="item.lname == '' &&  item.fname == ''  &&  item.mname == '' "></div>
							        		<div ng-if="item.lname != '' ||  item.fname != ''  ||  item.mname != '' ">
							        			{{item.lname | capitalize}}, {{item.fname | capitalize}} {{item.mname | capitalize}}
							        		</div>
							        		
							        </td>
							        <td title="'Birthday'" filter="{ dateOfBirth: 'text'}" sortable="'dateOfBirth'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.dateofbirth }}
							        </td> 
							        <td title="'Gender'" filter="{ gender: 'text'}" sortable="'gender'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.gender | capitalize}}
							        </td>  
							        <td title="'Mobile No.'" filter="{ mobileNum: 'text'}" sortable="'mobileNum'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.mobileNum }}
							        </td>   
							        <td title="'Drinks'" filter="{ drinks: 'text'}" sortable="'drinks'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.drinks }}
							        </td>  
							        <td title="'Points'" filter="{ accumulatedPoints: 'text'}" sortable="'accumulatedPoints'" header-class="'bg-grey'" style="text-align:center;">
							        	{{item.accumulatedPoints  | number}}
							        </td>    
							        <td title="'Action'"  header-class="'bg-grey'" style="text-align:center;">
							        		<a href="javascript:;" class="btn blue btn-xs black icopad" data-ng-click="ViewItem(item.memberID);">
											<i class="fa fa-bars"></i></a> 
											<a href="javascript:;" class="btn green-sharp btn-xs black icopad" data-ng-click="AddPoints(item.email);">
											<i class="fa fa-gift"></i></a> 
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
												<br><br>
											</div>

											<table ng-table="tableParams" class="table" show-filter="false">
											    <tr ng-repeat="item in $data" class="even"> 
											      <td title="'Type'" filter="{ type: 'text'}" sortable="'type'" header-class="'bg-grey-left'">
										        		{{item.type | capitalize}}
										        </td>
										        <td title="'Member ID'" filter="{ memberID: 'text'}" sortable="'memberID'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.memberID }}
										        </td> 
										        <td title="'Transaction ID'" filter="{ transactionID: 'text'}" sortable="'transactionID'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.transactionID }}
										        </td>  
										        <td title="'Name'" filter="{ name: 'text'}" sortable="'name'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.name | capitalize}}
										        </td>  
										        <td title="'Branch'" filter="{ branch: 'text'}" sortable="'branch'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.branch | capitalize}}
										        </td>  
										        <td title="'Date'" filter="{ date: 'text'}" sortable="'date'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.date }}
										        </td>   
										        <td title="'Points'" filter="{ points: 'text'}" sortable="'points'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.points | number }}
										        </td> 
										        <td title="'Amount'" filter="{ amount: 'text'}" sortable="'amount'" header-class="'bg-grey'" style="text-align:center;">
										        	{{item.amount | number }}
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


		<!-- MODALS --> 
		<a id="viedAddModal" class="btn default" data-toggle="modal" href="#addmodal" style="display: none;">open addmodal modal</a> 
		<div class="modal fade bs-modal-lg" id="addmodal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg" ng-controller="MyController" id="MyController4" >
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4  class="modal-title">Customer Details</h4>
					</div>
					<div class="modal-body" class="portlet-body" >
						
						<div class="row rowpad">
							<div class="col-md-5">      
								<img ng-if="profdata.image != ''" src="{{ profdata.image }}" width="20%" class="mainimgcircle">
								<img ng-if="profdata.image == ''" src="assets/img/boslogo.jpg" width="20%" class="mainimgcircle">
							</div>
							<div class="col-md-7">      
								<div class="customerdetails"> 
									<label class="justlabel">Name:</label>
									<span class="bold">{{ profdata.name | capitalize}}</span>
					         </div>       
								<div class="customerdetails">  
									<label class="justlabel">Email:</label>
									<span class="bold">{{ profdata.email }}</span>
					         </div>       
								<div class="customerdetails"> 
									<label class="justlabel">Address:</label>
									<span class="bold">{{ profdata.address | capitalize}}</span>
					         </div>  
								<div class="customerdetails"> 
									<label class="justlabel">Birthday:</label>
									<span class="bold">{{ profdata.dateofbirth }}</span>
					         </div> 
								<div class="customerdetails"> 
									<label class="justlabel">Gender:</label>
									<span class="bold">{{ profdata.gender | capitalize }}</span>
					         </div> 
								<div class="customerdetails"> 
									<label class="justlabel">Mobile:</label>
									<span class="bold">{{ profdata.mobile }}</span>
					         </div> 
								<div class="customerdetails"> 
									<label class="justlabel">Favorite Drink:</label>
									<span class="bold">{{ profdata.drink }}</span>
					         </div> 
								<div class="customerdetails"> 
									<label class="justlabel">Current Points:</label>
									<span class="bold">{{ profdata.points }}</span>
					         </div> 
								<div class="customerdetails"> 
									<label class="justlabel">Total Points:</label>
									<span class="bold">{{ profdata.totalpoints }}</span>
					         </div>  

								<div class="addptlabel"> 
									<label class="justlabel floatleft">Add VIP Points:</label>

						         <div class="inputptlabel">
										<input type="text" class="form-control" id="pointsField" placeholder="Points Here">  
									</div>

									<button type="button" class="btn btn-sm green-sharp floatleft ptbtn" data-dismiss="modal" ng-click="ApplyPoints(profdata.memberID)"><i class="fa fa-check-square"></i> </button> 
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

		<!--  EXPORT CONFIRMATION  -->
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


		<!-- ADD POINTS VALIDATION --> 
		<a id="confirmptBtn" class="btn default" data-target="#confirmptDialouge" data-toggle="modal" style="display:none;"> View Demo </a> 
		<div id="confirmptDialouge" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"> 
						<h4 class="modal-title">Points Validation</h4>
					</div>
					<div class="modal-body" >
						 Are you sure you want to add points?
					</div> 
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">No</button>
						<button type="button" class="btn blue" data-dismiss="modal" id="pushPtBtn">Yes</button>
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