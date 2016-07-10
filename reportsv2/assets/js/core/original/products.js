(function() {

    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController);
 

    function MyController ($scope, NgTableParams) {

        $scope.GetDailyProduct = function(){ 
            var _post = "function=get_dailyproductStatistics";
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

                        $("#chartLabel").html("Total: <b>"+numberFormat(result[0].data.length)+"</b>");
                        var count = (result[0].data.length > 10) ? [10, 15, 20, 25] : [];
                        $scope.data = result[0].data;  
                        $scope.tableParams = new NgTableParams({
                            page: 1, 
                            count: result[0].data.length
                        }, { 
                            paginationMaxBlocks: 8,
                            paginationMinBlocks: 2, 
                            counts: 0,
                            dataset: $scope.data
                        });

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
    }   

})();


var ExportFunction = "";

$("#exporter").on("click", function(){
     ExportFunction = "export_dailyproductStatistics";   
    Clear(); 
}); 


// $("#modalRow").on("change", "#exportTypeField", function(){
//     var _type = $("#exportTypeField").val();
 
// });


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
        else  
        { 
            Export(startDate, endDate);
        } 

    }else{ 
        toastr['warning']("Oops! No export type selected", "Empty Data"); 
        $("#closeBtn").click();  
    }
    
    setTimeout(unlock, 3000);
}  


function Export (startDate, endDate ) { 
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
    Clear();
    hide_loading("#contentBody");
    $("#closeLoading").click();

}


function GetData () {
    
    show_loading("#modalBody");
    var _post = "function=json&table=loctable";
    $.ajax({
        type: 'POST',
        url: 'php/gateway.php',
        data: _post,
        cache: false,
        async: true,
        dataType: 'JSON',
        success: function(result){ 
            console.log(result);
            var data = result[0].data;
            $('#locationField').val('');

            var myOptions = data;
            var mySelect = $('#locationField');
            $.each(myOptions, function(val, text) {
                mySelect.append(
                    $("<option></option>").val(text.locID).html(text.locName)
                );
            });

        }
    });

    hide_loading("#modalBody");

}

function Clear () {  
    $("#startDateField").val(GetToday());
    $("#endDateField").val(GetToday()); 
}