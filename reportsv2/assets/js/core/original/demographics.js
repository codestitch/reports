$(function() {
    console.log("ready");  
});

(function() {
    console.log("ready"); 

    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController);

    myApp.filter('capitalize', function() {
        return function(input) {
          return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
        }
    });
}());

var ExportFunction = "";
var SelectedMemberID = "";


function MyController ($scope, NgTableParams) {   

    $scope.GetCustomerSummary = function(){  
        StartPreloader();
        console.log("GetRedemptionSummary");
        $scope.data = null;

        var startDate = $("#startDate").val(),
            endDate = $("#endDate").val();
 
        if (startDate == "" || endDate == "") {
           StopPreloader();
            toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
            return;
        }
  
        var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=get_customerSummary&startDate="+startDate+"&endDate="+endDate;
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
                var count = (result[0].data.length > 25) ? [25, 40, 60, 80] : [];
                $scope.data = result[0].data;  
                $("#totalDisplay1").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 25
                }, { 
                    paginationMaxBlocks: 100,
                    paginationMinBlocks: 2, 
                    counts: count,
                    dataset: $scope.data
                });

                $("#emptyField").html("");
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
                $("#emptyField").html(str);
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


    $scope.GetMemberTransactionHistory = function(memberID){  
        console.log("GetMemberTransactionHistory"); 
        StartPreloader();

        $("#tableTitle").text("Member's Transaction History"); 
 
        if (memberID == "" ) {
            StopPreloader();
            toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
            return;
        }
        SelectedMemberID = memberID;
  
        var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=get_customerTransactionHistory&memberID="+memberID;
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
                var count = (result[0].data.length > 10) ? [10, 15, 20, 25] : [];
                $scope.data = result[0].data;  
                $("#totalDisplay3").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                $scope.tableParams = new NgTableParams({
                    page: 1, 
                    count: 10
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


    $scope.ViewItem = function (item) {
       ViewDetails(item);
    }

}


function ViewDetails(item) {
    angular.element($("#MyController3")).scope().GetMemberTransactionHistory(item);
}


/* REPORT GENERATION */
$("#exportBtn").on("click", function(){
     var startDate = $("#startDate").val(),
        endDate = $("#endDate").val();

    if (startDate == "" || endDate == "") {
       StopPreloader();
        toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
        return;
    }

    ExportFunction = "GetCustomerSummary";
  
    $("#confirmationModal").click();
});

$("#exportBtn2").on("click", function(){
      if (SelectedMemberID == "" ) { 
            toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
            return;
        }
  
    ExportFunction = "GetMemberTransactionHistory";
    $("#confirmationModal").click();
});

$("#generateExportBtn").on("click", function(){
    $("#displayLoading").click();
    console.log("Exporting...");  
    
    var startDate = $("#startDate").val(),
        endDate = $("#endDate").val(); 

    var _post = (ExportFunction == "GetCustomerSummary") ? 
                "function=export_customerSummary&startDate="+startDate+"&endDate="+endDate+"" :
                "function=export_customerTransactionHistory&memberID="+SelectedMemberID;
 
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