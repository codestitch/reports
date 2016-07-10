(function() {

    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController);
 
    var ExportFunction = "";

    function MyController ($scope, NgTableParams) {

        $scope.GetRedemption = function(){

            var _post = "function=get_voucherBranchRedemption";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(result){  

                    var data = result[0].data;
                    if(result[0].response == "Success"){ 
                        if (result[0].data[0].result == "Expired"){
                            toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                            $(".preloader-wrapper").css({'display' : 'block'});  
                            setTimeout(function(){ 
                                window.location.href = "logout.php"; 
                            }, 3000); 
                        }
                       
                        var count = (result[0].data.length > 10) ? [10, 15, 20, 25] : [];
                        $scope.data = result[0].data;  
                        $scope.tableParams = new NgTableParams({
                            page: 1, 
                            count: 10
                        }, { 
                            paginationMaxBlocks: 8,
                            paginationMinBlocks: 2, 
                            counts: count,
                            dataset: $scope.data
                        });
                    }
                    else if (result[0].response == "Empty"){  
                        var str = "<div>No data</div>";
                        $("#MyController1").append(str);
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
        
        $scope.GetDailyCustomers = function(){

            var _post = "function=get_voucherDailyCustomers";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(result){ 
                    console.log(result);
         
                    var data = result[0].data;
                    if(result[0].response == "Success"){    
                        var count = (result[0].data.length > 10) ? [10, 15, 20, 25] : [];
                        $scope.data = result[0].data;  
                        $scope.tableParams = new NgTableParams({
                            page: 1, 
                            count: 10
                        }, { 
                            paginationMaxBlocks: 8,
                            paginationMinBlocks: 2, 
                            counts: count,
                            dataset: $scope.data
                        });
                    }
                    else if (result[0].response == "Empty"){  
                        var str = "<div>No data</div>";
                        $("#MyController2").append(str);
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

        $scope.GetDailyRedemption = function(){

            var _post = "function=get_voucherBranchDailyRedemption";
            $.ajax({
                type: 'POST',
                url: 'php/gateway.php',
                data: _post,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(result){ 
                    console.log(result);
         
                    var data = result[0].data;
                    if(result[0].response == "Success"){  
                        if (result[0].data[0].result == "Expired"){
                            toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                            $(".preloader-wrapper").css({'display' : 'block'});  
                            setTimeout(function(){ 
                                window.location.href = "logout.php"; 
                            }, 3000); 
                        }
                        $("#chartLabel").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                        var count = (result[0].data.length > 10) ? [10, 15, 20, 25] : [];
                        $scope.data = result[0].data;  
                        $scope.tableParams = new NgTableParams({
                            page: 1, 
                            count: 10
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

        
    }

}());


$("#exporter").on("click", function(){
    Clear(); 
}); 


$("#modalRow").on("change", "#exportTypeField", function(){
    var _type = $("#exportTypeField").val();

    ExportFunction = _type;

    if ( _type == "exportTypeField" ){ 
        $("#dateRangeField").show();
    }  
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

    show_loading("#contentBody");
    $("#closeBtn").click();
    $("#exportBtn").off('click', InitExport);
    clickcount++;
    
    if (clickcount> 1) return;
    console.log(clickcount);

    if (ExportFunction != "") { 
         
        var startDate = $("#startDateField").val(),
            endDate = $("#endDateField").val();

        if (ExportFunction == "export_voucherTransHistory"  )
        {
            if (startDate == "" || endDate == "") {
                toastr['warning']("Kindly select date range for reports", "Invalid Entry"); 
                $("#closeBtn").click();
                hide_loading("#contentBody");
            }
            else{ 
                Export(startDate, endDate );
            } 

        }  

    }else{ 
        toastr['warning']("Oops! No export type selected", "Empty Data"); 
        hide_loading("#contentBody");  
        $("#closeBtn").click();
    }
    
    setTimeout(unlock, 3000);
}  


function Export (startDate, endDate) {  
    $("#displayLoading").click();
    
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
                hide_loading("#contentBody");
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
            hide_loading("#contentBody");   
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

 