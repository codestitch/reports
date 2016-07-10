(function() {
    console.log("ready");   
    $("select").css({ color: "#b3b3b3"}); 
    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController);

    myApp.filter('capitalize', function() {
        return function(input) {
          return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
        }
    });

}());

var MEMBERID = 0;

$("#drinkField").on("change", function(){ 
    var value = $("#drinkField").val();

    if (value == "") { 
         $("#drinkField").css({ color: "#b3b3b3"});
    }
    else{ 
         $("#drinkField").css({ color: "#000"});
    }
}); 
 

$("#genderField").on("change", function(){ 
    var value = $("#genderField").val();

    if (value == "") { 
         $("#genderField").css({ color: "#b3b3b3"});
    }
    else{ 
         $("#genderField").css({ color: "#000"});
    }
}); 


$("#bdayField").on("change", function(){ 
    var value = $("#bdayField").val();

    if (value == "") { 
         $("#bdayField").css({ color: "#b3b3b3"});
    }
    else{ 
         $("#bdayField").css({ color: "#000"});
    }
}); 


$("#ageField").on("change", function(){ 
    var value = $("#ageField").val();

    if (value == "") { 
         $("#ageField").css({ color: "#b3b3b3"});
    }
    else{ 
         $("#ageField").css({ color: "#000"});
    }
}); 

function MyController ($scope, NgTableParams) {   

    $scope.AllowVip= false;

    $scope.GetCustomerQuery = function(){  
        StartPreloader();
        console.log("GetCustomerQuery");

        $scope.data = null;

        var email = $("#emailField").val(),
            favoriteDrink = $("#drinkField").val(),
            gender = $("#genderField").val(),
            birthday = $("#bdayField").val(),
            age = $("#ageField").val().split("-"),
            startDate = $("#startDate").val(),
            endDate = $("#endDate").val();

        if (email == "" && favoriteDrink == "" && gender == "" && birthday == "" && age == "" && startDate == "" && endDate == "") {
            toastr['warning']("Oops! Kindly fill at least one filter", "Insufficient Filter");
            StopPreloader();
            return; 
        }

        var startAge = age[0],
            endAge = age[1];  

        // LOCAL Path
        // var path = "php/dummytest.php?function=get_userinformation&email="+email+"&favoriteDrink="+favoriteDrink+
        //             "&gender="+gender+"&birthday="+birthday+"&startAge="+startAge+"&endAge="+endAge+"&startDate="+startDate+"&endDate="+endDate;

        // LIVE PATH
        var path = "http://boscoffee.appsolutely.ph/reportsdata.php?function=get_userinformation&email="+email+"&favoriteDrink="+favoriteDrink+
                "&gender="+gender+"&birthday="+birthday+"&startAge="+startAge+"&endAge="+endAge+"&startDate="+startDate+"&endDate="+endDate;
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


    $scope.GetCustomerTransactionHistory = function(memberID){  
        console.log("GetCustomerTransactionHistory"); 

        
        StartPreloader();
        $("#tableTitle").text("Customer Transaction History"); 

        // LOCAL PATH
        // var path = "php/dummytest.php?function=get_customerHistoryTransactions&memberID="+memberID;

        // LIVE PATH
        var path = "http://boscoffee.appsolutely.ph/reportsdata.php?function=get_customerHistoryTransactions&memberID="+memberID;

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
 


    $scope.GetCustomerProfileDetail = function(email){  
        console.log("GetCustomerProfileDetail"); 
 
        StartPreloader();
        $("#addmdTitle").text("Customer Details"); 

        // LOCAL PATH
        // var path = "php/dummytest.php?function=get_customerProfileDetail&email="+email;

        // LIVE PATH
        var path = "http://boscoffee.appsolutely.ph/reportsdata.php?function=get_customerProfileDetail&email="+email;
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

                $scope.profdata =  result[0].data[0]; 

                if (result[0].data[0].profileStatus == "complete") {
                    $scope.AllowVip = true;
                }

                $("#viedAddModal").click();  
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

    $scope.ViewItem = function (_item) {
       ViewDetails(_item);
    }

    $scope.AddPoints = function (_item) { 
        AddGift(_item);
    }

    $scope.ApplyPoints = function(memberID) {

        MEMBERID = memberID;
        var points = $("#pointsField").val();
        console.log("$scope.AllowVip: "+$scope.AllowVip); 

        if (!$scope.AllowVip) {
            toastr['warning']("Oops! Profile Incomplete. Profile should be complete first before VIP can be applied", "Incomplete Profile");
            $("#pointsField").val("");  
            return; 
        }

        if (points == "") {
            toastr['warning']("Oops! No points found. Kindly add points.", "Invalid Input");
            return;
        }
            
        $("#confirmptBtn").click(); 
        
        // var _post1 = "function=add_points&memberID="+memberID+"&points="+points+"&transactionType="+transactionType; 

    }

}

$("#pushPtBtn").on("click", function(){
    SendPoints(MEMBERID);
});


function SendPoints(memberID) {

    var points = $("#pointsField").val();
    var transactionType = "vip"; 
   
    // LOCAL PATH
    // var path = "php/dummytest.php?function=add_points&memberID="+memberID+"&points="+points+"&transactionType="+transactionType;

    // LIVE SCRIPT
    var path = "http://boscoffee.appsolutely.ph/reportsdata.php?function=add_points&memberID="+memberID+"&points="+points+"&transactionType="+transactionType;
    $.ajax({
      type: 'GET',
      url: path, 
      cache: false,
      async: false,
      dataType: 'json',
      success: function(result){ 
        console.log(result);   

            console.log(result);
            if(result[0].response == "Success"){
                toastr['success']("Great! You successfully added "+points+" Point(s)! Kindly query again to view the added points.", "Success");  
                $("#pointsField").val("");  
            }
            else if (result[0].response == "Expired"){
                toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                $(".preloader-wrapper").css({'display' : 'block'});
                window.location.href = "logout.php";
            }
            else if (result[0].response == "Failed"){
                toastr['warning']("Oops! Current operation fails. Kindly try again.", "Operation Failed");
                return;
            }
            else if (result[0].response == "Exceeds"){
                toastr['warning']("Oops! You've exceed file size upload. Kindly check your file.", "Operation Failed");
                return;
            }
            else if (result[0].response == "Invalid"){
                toastr['warning']("Oops! Your file is invalid. Kindly check it again.", "Operation Failed");
                return;
            } 
        },
        error:  function(result) {  
            toastr['error']("Oops! An error occured. "+result, "Error Encounter"); 
        }
    });  

    // LOCAL SCRIPT

    $("#pointsField").val("");  
}


$("#sampleBtn").on("click", function(){

    var path = "php/dummytest.php?function=add_points&memberID=MEM6R23154NgLSVR&points=5&transactionType=vip";

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
});


function ViewDetails(_item) {
    angular.element($("#MyController3")).scope().GetCustomerTransactionHistory(_item);
}


function AddGift(_item) { 
    angular.element($("#MyController4")).scope().GetCustomerProfileDetail(_item);
}


$("#addPointBtn").on("click", function(){

    

});


/* REPORT GENERATION */
$("#exportBtn").on("click", function(){
    $("#confirmationModal").click();
});

$("#generateExportBtn").on("click", function(){
    $("#displayLoading").click();
    console.log("Exporting..."); 

    var email = $("#emailField").val(),
        favoriteDrink = $("#drinkField").val(),
        gender = $("#genderField").val(),
        birthday = $("#bdayField").val(),
        age = $("#ageField").val().split("-"),
        startDate = $("#startDate").val(),
        endDate = $("#endDate").val(); 

    var startAge = age[0],
        endAge = age[1];  

    var _post = "function=export_customerdetails&email="+email+"&favoriteDrink="+favoriteDrink+
                "&gender="+gender+"&birthday="+birthday+"&startAge="+startAge+"&endAge="+endAge+"&startDate="+startDate+"&endDate="+endDate;
    $.ajax({
        type: 'POST',
        url: 'http://boscoffee.appsolutely.ph/exportreportsdata.php', 
        data: _post,
        cache: false,
        async: false,
        dataType: 'json',
        success: function(result){ 
            console.log(result);  
            toastr['success']("Congratulations! You've successfully exported specific report", "Sucess"); 
            window.location = "http://boscoffee.appsolutely.ph/reports/excel/"+result[0].filename;
        },
        error: function(result){
            toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
        }
    });
    $("#closeLoading").click();
 
});



$("#pointsField").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
         // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
