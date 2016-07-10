(function() {

    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController);
    GetBranchLocation(); 

}());

var SelectedHourlyLocation = "";
var SelectedDate = "", SelectedTime = "";

function MyController ($scope, NgTableParams) {   

    $scope.GetBranchSummary = function(){ 
        StartPreloader();
        console.log("GetBranchSummary");
        $scope.data = null;

        var startDate = $("#branchStartDate").val(),
            endDate = $("#branchEndDate").val();
 
        if (startDate == "" || endDate == "") {
            StopPreloader();
            toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
            return;
        }
  
        var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=get_salesreportsummary&startDate="+startDate+"&endDate="+endDate;
        $.ajax({
          type: 'GET',
          url: path, 
          cache: false,
          async: false,
          dataType: 'json',
          success: function(result){ 
            console.log(result); 

            if(result[0].response == "Success"){  
                if (result[0].data[0].result == "Expired"){
                    toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                    $(".preloader-wrapper").css({'display' : 'block'});  
                    setTimeout(function(){ 
                        window.location.href = "logout.php"; 
                    }, 3000); 
                }

                $("#exportBtn").prop('disabled', false);
                var count = (result[0].data.length > 10) ? [40, 80, 100, 150] : [];
                $scope.data = result[0].data;  
                
                $("#totalDisplay1").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 40
                }, { 
                    paginationMaxBlocks: 100,
                    paginationMinBlocks: 2, 
                    counts: count,
                    dataset: $scope.data
                });
                $("#emptyField1").html("");
            }
            else if (result[0].response == "Empty"){  
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 30
                }, { 
                    paginationMaxBlocks: 100,
                    paginationMinBlocks: 2, 
                    counts: count,
                    dataset: $scope.data
                });

                var str = "<div>No data</div>";
                $("#emptyField1").html(str);
                $("#totalDisplay1").html("Total: <b>0</b>"); 
            }
            else if (result[0].response == "Expired"){
                toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                $(".preloader-wrapper").css({'display' : 'block'});
                window.location.href = "logout.php";
            }
            else if (result[0].response == "Failed"){
                toastr['warning']("Oops! Current operation fails. Kindly try again.", "Operation Failed");
            }
            else if (result[0].response == "Exceeds"){
                toastr['warning']("Oops! You've exceed file size upload. Kindly check your file.", "Operation Failed");
            }
            else if (result[0].response == "Invalid"){
                toastr['warning']("Oops! Your file is invalid. Kindly check it again.", "Operation Failed");
            }  

          },
          error: function(result){
            toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
          }
        });

        StopPreloader();

    }

    $scope.GetSalesReportHourly = function(){ 
        StartPreloader();
        console.log("GetBranchSummary");

        $scope.data = null;

        var startDate = $("#hourStartDate").val(),
            endDate = $("#hourEndDate").val()
            branch = $("#locationField :selected").text(); 
            SelectedHourlyLocation = $("#locationField :selected").text(); 
 
        if (startDate == "" || endDate == "" || branch == "Select Branch") {
            StopPreloader();
            toastr['warning']("Oops! Kindly fill Start Date, End Date and Branch.", "Insufficient Data");
            return;
        } 
  
        var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=get_salesreporthourly&startDate="+startDate+"&endDate="+endDate+"&locName="+branch;
        $.ajax({
          type: 'GET',
          url: path, 
          cache: false,
          async: false,
          dataType: 'json',
          success: function(result){ 
            console.log(result);  

            if(result[0].response == "Success"){  
                if (result[0].data[0].result == "Expired"){
                    toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                    $(".preloader-wrapper").css({'display' : 'block'});  
                    setTimeout(function(){ 
                        window.location.href = "logout.php"; 
                    }, 3000); 
                }

                $("#exportBtn2").prop('disabled', false);
                var count = (result[0].data.length > 10) ? [40, 80, 100, 150] : [];
                $scope.data = result[0].data;  
                $("#totalDisplay2").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 40
                }, { 
                    paginationMaxBlocks: 100,
                    paginationMinBlocks: 2, 
                    counts: count,
                    dataset: $scope.data
                });

                $("#emptyField2").html("");
            }
            else if (result[0].response == "Empty"){  
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 30
                }, { 
                    paginationMaxBlocks: 100,
                    paginationMinBlocks: 2, 
                    counts: count,
                    dataset: $scope.data
                });

                var str = "<div>No data</div>";
                $("#emptyField2").html(str);
                $("#totalDisplay2").html("Total: <b>0</b>"); 

            }
            else if (result[0].response == "Expired"){
                toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                $(".preloader-wrapper").css({'display' : 'block'});
                window.location.href = "logout.php";
            }
            else if (result[0].response == "Failed"){
                toastr['warning']("Oops! Current operation fails. Kindly try again.", "Operation Failed");
            }
            else if (result[0].response == "Exceeds"){
                toastr['warning']("Oops! You've exceed file size upload. Kindly check your file.", "Operation Failed");
            }
            else if (result[0].response == "Invalid"){
                toastr['warning']("Oops! Your file is invalid. Kindly check it again.", "Operation Failed");
            }  

          },
          error: function(result){
            toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
          }
        });

        StopPreloader();

    } 


    $scope.GetMembersCount = function(date, time){  
        console.log("GetMembersCount"); 

        StartPreloader();
        $("#tableTitle").text("Members List");

 
        if (date == "" || time == "" || SelectedHourlyLocation == "") {
            StopPreloader();
            toastr['warning']("Oops! No date, time and location found.", "Insufficient Data");
            return;
        }

        SelectedDate = date;
        SelectedTime = time;
  
        var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=get_salesreportmembers&startDate="+date+"&startTime="+time+"&locName="+SelectedHourlyLocation;
        $.ajax({
          type: 'GET',
          url: path, 
          cache: false,
          async: false,
          dataType: 'json',
          success: function(result){ 
            console.log(result);  

            if(result[0].response == "Success"){  
                if (result[0].data[0].result == "Expired"){
                    toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                    $(".preloader-wrapper").css({'display' : 'block'});  
                    setTimeout(function(){ 
                        window.location.href = "logout.php"; 
                    }, 3000); 
                }

                $("#viewDetailModal").click(); 
                var count = (result[0].data.length > 10) ? [40, 80, 100, 150] : [];
                $scope.data = result[0].data;  
                $("#totalDisplay3").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 40
                }, { 
                    paginationMaxBlocks: 100,
                    paginationMinBlocks: 2, 
                    counts: count,
                    dataset: $scope.data
                });
            }
            else if (result[0].response == "Empty"){  
                toastr['warning']("Oops! There seems to be no data", "No data found");
            }
            else if (result[0].response == "Expired"){
                toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                $(".preloader-wrapper").css({'display' : 'block'});
                window.location.href = "logout.php";
            }
            else if (result[0].response == "Failed"){
                toastr['warning']("Oops! Current operation fails. Kindly try again.", "Operation Failed");
            }
            else if (result[0].response == "Exceeds"){
                toastr['warning']("Oops! You've exceed file size upload. Kindly check your file.", "Operation Failed");
            }
            else if (result[0].response == "Invalid"){
                toastr['warning']("Oops! Your file is invalid. Kindly check it again.", "Operation Failed");
            }  

          },
          error: function(result){
            toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
          }
        });

        StopPreloader();

    }

    $scope.ViewItem = function (date, time) {
       ViewDetails(date, time);
    }
}

function ViewDetails(date, time) {
    angular.element($("#MyController3")).scope().GetMembersCount(date, time);
}


function GetBranchLocation () {
     
    var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=getLocation";
    $.ajax({
      type: 'GET',
        url: path, 
        cache: false,
        async: false,
        dataType: 'JSON',
        success: function(result){  
            var data = result[0].data; 

            if (data == null || data.length == 0) {
                toastr['warning']("Oops! No Location found. Kindly add first a category.", "No Data Found");
                return;
            } 

            var myOptions = data; 
            var mySelect = $('#locationField');
            $.each(myOptions, function(val, text) {
                mySelect.append(
                    $("<option></option>").val(text.locID).html(text.locName)
                );
            }); 
        }
    });
 
}
 
var SelectedExport = "";

/* REPORT GENERATION */
$("#exportBtn").on("click", function(){
    var startDate = $("#branchStartDate").val(),
        endDate = $("#branchEndDate").val();    

    if (startDate == "" || endDate == "") { 
        toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
        return;
    }

    $("#confirmationModal").click();
    SelectedExport = "GetBranchSummary";
});

$("#exportBtn2").on("click", function(){
    var startDate = $("#hourStartDate").val(),
            endDate = $("#hourEndDate").val(),
        branch = $("#locationField :selected").text();    
    console.log(branch);
    if (startDate == "" || endDate == "" || branch == "Select Branch") { 
        toastr['warning']("Oops! Kindly fill Start Date, End Date and Branch", "Insufficient Data");
        return;
    }

    $("#confirmationModal").click();
    SelectedExport = "GetSalesReportHourly";
});

$("#exportBtn3").on("click", function(){
    var startDate = $("#hourStartDate").val(),
            endDate = $("#hourEndDate").val(),
        branch = $("#locationField :selected").text();    
    console.log(branch);
   
    if (SelectedDate == "" || SelectedTime == "" || SelectedHourlyLocation == "") { 
        toastr['warning']("Oops! No date, time and location found.", "Insufficient Data");
        return;
    }

    $("#confirmationModal").click();
    SelectedExport = "GetMembersCount";
});



$("#generateExportBtn").on("click", function(){
    console.log("Exporting...");  

    var _post = ""; 

    if (SelectedExport == "GetBranchSummary") {

        var startDate = $("#branchStartDate").val(),
            endDate = $("#branchEndDate").val();
        _post = "function=export_salesreportsummary&startDate="+startDate+"&endDate="+endDate;
    }
    else if (SelectedExport == "GetSalesReportHourly"){
        
        var startDate = $("#hourStartDate").val(),
            endDate = $("#hourEndDate").val(),
            branch = $("#locationField :selected").text(); 
        _post = "function=export_salesreporthourly&startDate="+startDate+"&endDate="+endDate+"&locName="+branch; 
    } 
    else if (SelectedExport == "GetMembersCount"){

        _post = "function=export_salesreportmembers&startDate="+SelectedDate+"&startTime="+SelectedTime+"&locName="+SelectedHourlyLocation;
    }

    if (_post == "") { 
        toastr['error']("Oops! Internal error. Did not get selected export. Kindly contact administrator." + result, "Operation Error");
        return; 
    }
    
    console.log(_post);
    $("#displayLoading").click();
    $.ajax({
        type: 'POST',
        url: 'http://familymartsnap.appsolutely.ph/exportreportdata.php', 
        data: _post,
        cache: false,
        async: false,
        dataType: 'json',
        success: function(result){ 
            console.log(result);  
            toastr['success']("Congratulations! You've successfully exported specific report", "Sucess"); 
            window.location = "http://familymartsnap.appsolutely.ph/reports/excel/"+result[0].filename;
        },
        error: function(result){
            toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
        }
    });
    $("#closeLoading").click();
 
});