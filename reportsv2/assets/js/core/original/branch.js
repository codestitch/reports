(function() {

    var myApp = angular.module('myApp', ['ngTable']);
    myApp.controller('MyController', MyController);
 
    var ExportFunction = ""; 

}());

function MyController ($scope, NgTableParams) {   

    $scope.GetBranchSummary = function(){ 
        console.log("GetBranchSummary");

        var startDate = $("#branchStartDate").val(),
            endDate = $("#branchEndDate").val();
        show_loading("#MyController1");
 
        if (startDate == "" || endDate == "") {
            hide_loading("#MyController1");
            toastr['warning']("Oops! Kindly fill Start Date and End Date.", "Insufficient Data");
            return;
        }
  
        var path = "http://familymartsnap.appsolutely.ph/reportdata.php?function=get_branchsummary&startDate="+startDate+"&endDate="+endDate;
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

                var count = (result[0].data.length > 10) ? [10, 15, 20, 25] : [];
                $scope.data = result[0].data;  
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

          },
          error: function(result){
            toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
          }
        });

        hide_loading("#MyController1");

    }
        
}



$("#exporter").on("click", function(){
    Clear(); 
}); 


$("#modalRow").on("change", "#exportTypeField", function(){
    var _type = $("#exportTypeField").val();

    ExportFunction = _type;

    if (_type == "export_branchtranssummary_points" || _type == "export_branchtranssummary_redeem" 
        || _type == "export_branchtranssummary_sales" || _type == "" ) {
        $("#locationFlat").hide();
        $("#dateRangeField").show();
    }
    else if ( _type == "export_dailybranchStatistics" ){
        $("#locationFlat").hide();
        $("#dateRangeField").hide();
    } 
    else {
        $("#locationFlat").show();
        $("#dateRangeField").show();
        GetData();
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
        
        if (ExportFunction == "export_dailybranchStatistics"){
            Export("",""); 
        }
        else
        {
            var startDate = $("#startDateField").val(),
                endDate = $("#endDateField").val(),
                locid = $('#locationField').val();

            if (ExportFunction == "export_branchtranssummary_points" || 
                ExportFunction == "export_branchtranssummary_redeem" ||
                ExportFunction == "export_branchtranssummary_sales" )
            {
                if (startDate == "" || endDate == "") {
                    toastr['warning']("Kindly select date range for reports", "Invalid Entry"); 
                    hide_loading("#contentBody");
                    $("#closeBtn").click();
                }
                else{ 
                    Export(startDate, endDate, "");
                } 

            }
            else {
                if (locid == "" || startDate == "" || endDate == "") {
                    toastr['warning']("Kindly select location and date range for reports", "Invalid Entry"); 
                    hide_loading("#contentBody");
                    $("#closeBtn").click();
                }
                else{
                    Export(startDate, endDate, locid); 
                }
            } 
            
        }

    }else{ 
        toastr['warning']("Oops! No export type selected", "Empty Data");   
        $("#closeBtn").click();
    }
    
    setTimeout(unlock, 3000);
}  


function Export (startDate, endDate, locid) {  
    $("#displayLoading").click();
    
    var _post = "function="+ExportFunction+"&startDate="+startDate+"&endDate="+endDate; 
    var _type = $("#exportTypeField").val();

    if (_type == "export_branchtranssummary_points" || _type == "export_branchtranssummary_redeem" 
        || _type == "export_branchtranssummary_sales" || _type == "export_dailybranchStatistics" ) {
        
        _post = "function="+ExportFunction+"&startDate="+startDate+"&endDate="+endDate;
    }
    else if ( _type == "export_dailybranchStatistics" ){
         
        _post = "function="+ExportFunction;

    } 
    else { 
        _post = "function="+ExportFunction+"&startDate="+startDate+"&endDate="+endDate+"&locID="+locid;
    }

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


function GetData () {

    $('#locationField')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select Export Option</option>')
    .val('');

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