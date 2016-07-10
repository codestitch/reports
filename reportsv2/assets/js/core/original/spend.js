    (function() {

    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController); 


    function MyController ($scope, NgTableParams) {


        $scope.GetSpentYearly = function(){ 
            var _post = "function=get_spentYearlySales";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(result){ 
                    console.log(result);
          
                    if(result[0].response == "Success"){  
                        var data = result[0].data[0]; 
                        $scope.YearlySales = (data.Sales  == "") ? 0: data.Sales;  
                        $scope.YearlyTransaction = (data.Transaction  == "") ? 0: data.Transaction;  
                        $scope.YearlyAverage = (data.Average  == "") ? 0: data.Average;  
                    }
                    else if (result[0].response == "Empty"){  
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

                }
            }); 
        } 

        $scope.GetSpentDaily = function(){

            var _post = "function=get_spentdailySales";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(result){ 
                    console.log(result);
         
                    if(result[0].response == "Success"){  
                        var data = result[0].data[0];
                        $scope.DailySales = (data.Sales == "") ? 0: data.Sales;  
                        $scope.DailyTransaction = (data.Transaction == "") ? 0: data.Transaction;  
                        $scope.DailyAverage = (data.Average == "") ? 0 : data.Average;  
                    }
                    else if (result[0].response == "Empty"){  
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

                }
            });

        }


        
        $scope.GetSpentAverageCustomer = function(){ 
            var _post = "function=get_spentaverageCustomer";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(result){ 
                    console.log(result);
          
                    if(result[0].response == "Success"){  

                        var count = (result[0].data.length < 22) ? [] : [10, 15, 20, 25];
                        $scope.data = result[0].data;  
                        $scope.tableParams = new NgTableParams({
                            page: 1, 
                            count: 20
                        }, { 
                            paginationMaxBlocks: 8,
                            paginationMinBlocks: 2, 
                            counts: count,
                            dataset: $scope.data
                        });
                    }
                    else if (result[0].response == "Empty"){  
                        var str = "<div>No data</div>";
                        $("#MyController3").append(str);
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

                }
            }); 
        }



        $scope.GetSpentDailyCustomer = function(){ 
            var _post = "function=get_spentdailyCustomer";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
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
                        var count = (result[0].data.length > 20) ? [10, 15, 20, 25] : [];
                        $scope.data = result[0].data;  
                        $scope.tableParams = new NgTableParams({
                            page: 1, 
                            count: 20
                        }, { 
                            paginationMaxBlocks: 8,
                            paginationMinBlocks: 2, 
                            counts: count,
                            dataset: $scope.data
                        });
                    }
                    else if (result[0].response == "Empty"){  
                        var str = "<div>No data</div>";
                        $("#MyController4").append(str);
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

                }
            }); 
        }



    }
    // end controller

}());

var ExportFunction = "";

$("#exporter").on("click", function(){
    Clear(); 
}); 


$("#modalRow").on("change", "#exportTypeField", function(){
    ExportFunction = $("#exportTypeField").val(); 
});


$("#exportBtn").on('click', InitExport);
var clickcount = 0;

function unlock(){
    console.log("unlocked");
    clickcount = 0;
    $("#exportBtn").on('click', InitExport); 
}

function InitExport (e) {
    e.preventDefault(); 

    $("#closeBtn").click();
    $("#exportBtn").off('click', InitExport);
    clickcount++;
    
    if (clickcount> 1) return;
    console.log(clickcount);

    if (ExportFunction != "") { 
        
        var startDate = $("#startDateField").val(),
                endDate = $("#endDateField").val();

        if (startDate == "" || endDate == "") {
            toastr['warning']("Kindly select date range for reports", "Invalid Entry"); 
            $("#closeBtn").click();
        }
        else{ 
            Export(startDate, endDate);
        } 

    }else{ 
        toastr['warning']("Oops! No export type selected", "Empty Data");
        $("#closeBtn").click();   
    }
    
    setTimeout(unlock, 3000);
}  


function Export (startDate, endDate) {   
    $("#displayLoading").click();

    show_loading("#contentBody");
    var _post = "function="+ExportFunction+"&startDate="+startDate+"&endDate="+endDate;   

    $.ajax({
        type: 'POST',
        url: 'php/gateway.php',
        data: _post,
        cache: false,
        async: false,
        dataType: 'JSON',
        success: function(result){ 
            console.log(result);

            hide_loading("#contentBody");
            if(result[0].response == "Success"){  
                toastr['success']("Congratulations! You've successfully exported specific report", "Sucess"); 
                window.location = "excel/"+result[0].filename;
            } 
            else if (result[0].response == "Empty"){
                toastr['warning']("Oops! No data found", "Empty Data");   
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
            toastr['error']("An error occured while connecting to server.", "Error");    
        }
    });
    hide_loading("#contentBody");
    $("#closeLoading").click();

}

function Clear () { 
    $("#startDateField").val(GetToday());
    $("#endDateField").val(GetToday());
    $("#exportTypeField").val(''); 
    ExportFunction = "";
}