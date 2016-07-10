$(function() {
    console.log("ready");

    var d = new Date();
    var month = d.getMonth();
    var year = d.getFullYear();
    var montharray = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $("#yearHeader").text("Total Downloads for "+year);
    $("#monthHeader").text("Total Downloads for "+montharray[month]);

    GetDownload();
    GetUserRegistrationPlatform();
    GetUserAge();
    GetUserGender(); 

    GetYearlyDownload();
    GetMonthlyDownload();
});


// TOOL BAR AREA
function GetDownload () {

    $.ajax({
      type: 'GET',
      url: 'http://familymartsnap.appsolutely.ph/reportdata.php?function=get_userdownloads', 
      cache: false,
      async: false,
      dataType: 'json',
      success: function(result){ 
        console.log(result); 
        $("#toolandroid").text(numberFormat(result[0].data[0].total));
        $("#toolapple").text(numberFormat(result[0].data[1].total));
        var total = parseInt(result[0].data[0].total) + parseInt(result[0].data[1].total);
        $("#tooltotal").text(numberFormat(total)); 
 
      },
      error: function(result){
        toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
      }
    });
    
} 


function GetUserRegistrationPlatform() {
    
    $.ajax({
      type: 'GET',
      url: 'http://familymartsnap.appsolutely.ph/reportdata.php?function=get_userplatformRegistration', 
      cache: false,
      async: false,
      dataType: 'json',
      success: function(result){ 
        console.log(result);  

        $("#androidLabel").text(numberFormat(result[0].data[0].total));
        $("#cardLabel").text(numberFormat(result[0].data[1].total));
        $("#iosLabel").text(numberFormat(result[0].data[2].total)); 
 
      },
      error: function(result){
        toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
      }
    });

}



function GetUserAge () { 
    $.ajax({ 
          type: 'GET',
          url: 'http://familymartsnap.appsolutely.ph/reportdata.php?function=get_userage', 
          cache: false,
          async: false,
          dataType: 'json',
        success: function(result){ 
            console.log(result);

            // hide_loading("#contentBody");
            if(result[0].response == "Success"){ 
                if (result[0].data[0].result == "Expired"){
                    toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                    $(".preloader-wrapper").css({'display' : 'block'});  
                    setTimeout(function(){ 
                        window.location.href = "logout.php"; 
                    }, 3000); 
                }
                initAgeChart(result[0].data); 
            }
            else if (result[0].response == "Empty"){
                $("#ageTable").hide();
            }
            else if (result[0].response == "Expired"){
                toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                $(".preloader-wrapper").css({'display' : 'block'});
                setTimeout(3000, function(){ 
                    window.location.href = "logout.php"; 
                });
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

function GetUserGender () { 
    $.ajax({
          type: 'GET',
          url: 'http://familymartsnap.appsolutely.ph/reportdata.php?function=get_usergender', 
          cache: false,
          async: false,
          dataType: 'json',
        success: function(result){ 
            console.log(result);

            if(result[0].response == "Success"){ 
                // initGenderChart(result[0].data);
                $("#maleLabel").text(numberFormat(result[0].data[1].count));
                $("#femaleLabel").text(numberFormat(result[0].data[0].count));
                var total = parseInt(result[0].data[1].count) + parseInt(result[0].data[0].count);
                $("#totalgenderLabel").text(numberFormat(total));
                
            }
            else if (result[0].response == "Empty"){ 

            }
            else if (result[0].response == "Expired"){
                toastr['warning']("Oops! Your account has expired. Kindly login again.", "Session Expired");
                $(".preloader-wrapper").css({'display' : 'block'});
                setTimeout(3000, function(){ 
                    window.location.href = "logout.php"; 
                });
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


function GetYearlyDownload () {

    $.ajax({
      type: 'GET',
      url: 'http://familymartsnap.appsolutely.ph/reportdata.php?function=get_downloads_yearly', 
      cache: false,
      async: false,
      dataType: 'json',
      success: function(result){ 
        console.log(result); 
 
        var androidtotal = 0, iostotal = 0, total = 0;
        for (var i = 0; i < result[0].data.length; i++) {
            androidtotal += parseInt(result[0].data[i].Android);
            iostotal += parseInt(result[0].data[i].iOS);
        }
        total = androidtotal + iostotal; 
        $("#totalDisplay").html("Total: <b>"+numberFormat(total) + "</b>");
        ProduceYearlyChart(result[0].data); 
      },
      error: function(result){
        toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
      }
    });
    
} 

function GetMonthlyDownload () {

    $.ajax({
      type: 'GET',
      url: 'http://familymartsnap.appsolutely.ph/reportdata.php?function=get_downloads_monthly', 
      cache: false,
      async: false,
      dataType: 'json',
      success: function(result){ 
        console.log(result); 

        var androidtotal = 0, iostotal = 0, total = 0;
        for (var i = 0; i < result[0].data.length; i++) {
            androidtotal += parseInt(result[0].data[i].Android);
            iostotal += parseInt(result[0].data[i].iOS);
        }
        total = androidtotal + iostotal; 
        $("#totalDisplay1").html("Total: <b>"+numberFormat(total) + "</b>");

        ProductMonthlyChart(result[0].data); 
      },
      error: function(result){
        toastr['error']("Oops! An error occured while performing the operation. " + result, "Operation Error");
      }
    });
    
} 




function ProduceYearlyChart(data) {

    var chart = AmCharts.makeChart("appdownloadsyearly", {
        "type": "serial",
        "theme": "light",
        "fontFamily": "HelveticaNeue",
        "color":"#b6b6b6", 
        "legend": {
            "align": "left",
            "equalWidths": false,
            "periodValueText": "Total: [[value.sum]]",
            "valueAlign": "left",
            "valueText": "[[value]] ([[percents]]%)",
            "valueWidth": 100
        },
        "dataProvider": data,
        "graphs": [{
            "balloonText": "<i class='fa fa-apple'></i> <span style='font-size:14px; color:#000000;'><b>[[value]]</b></span>",
            "fillAlphas": 0.5,
            "lineAlpha": 0.5,
            "title": "iOS",
            "valueField": "iOS"
        }, {
            "balloonText": "<i class='fa fa-android'></i> <span style='font-size:14px; color:#000000;'><b>[[value]]</b></span>",
            "fillAlphas": 0.5,
            "lineAlpha": 0.5,
            "title": "Android",
            "valueField": "Android"
        }],
        "plotAreaBorderAlpha": 0,
        "marginLeft": 0,
        "marginBottom": 0,
        "chartCursor": {
            "cursorAlpha": 0,
            "zoomable": false
        },
        "categoryField": "Month",
        "categoryAxis": {
            "startOnAxis": true,
            "axisColor": "#DADADA",
            "gridAlpha": 0.07
        },
        "export": {
            "enabled": true
         } 
    });
}


function ProductMonthlyChart(data) {
     var chart = AmCharts.makeChart("appdownloadsmonthly", {
        "type": "serial",
        "theme": "light",
        "fontFamily": "HelveticaNeue",
        "color":"#b6b6b6", 
        "legend": {
            "align": "left",
            "equalWidths": false,
            "periodValueText": "Total: [[value.sum]]",
            "valueAlign": "left",
            "valueText": "[[value]] ([[percents]]%)",
            "valueWidth": 100
        },
        "dataProvider": data,
        "graphs": [{
            "balloonText": "<i class='fa fa-apple'></i> <span style='font-size:14px; color:#000000;'><b>[[value]]</b></span>",
            "fillAlphas": 0.5,
            "lineAlpha": 0.5,
            "title": "iOS",
            "valueField": "iOS"
        }, {
            "balloonText": "<i class='fa fa-android'></i> <span style='font-size:14px; color:#000000;'><b>[[value]]</b></span>",
            "fillAlphas": 0.5,
            "lineAlpha": 0.5,
            "title": "Android",
            "valueField": "Android"
        }],
        "plotAreaBorderAlpha": 0,
        "marginLeft": 0,
        "marginBottom": 0,
        "chartCursor": {
            "cursorAlpha": 0,
            "zoomable": false
        },
        "categoryField": "Week",
        "categoryAxis": {
            "startOnAxis": true,
            "axisColor": "#DADADA",
            "gridAlpha": 0.07
        },
        "export": {
            "enabled": true
         }
     });
}



$("#regExport").on("click", function(){
    Clear();
    $("#selectionField").show();
});

$("#dlExport").on("click", function(){
    Clear();
    ExportFunction = "export_userDownload";
});

$("#ageExport").on("click", function(){
    Clear();
    ExportFunction = "export_userage";
});

$("#genderExport").on("click", function(){
    Clear();
    ExportFunction = "export_usergender";
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

    var extraexport = $("#exportTypeField").val();  
    if (extraexport != "" ){  
        ExportFunction = $("#exportTypeField").val(); 
        console.log(ExportFunction);
    }

    if (ExportFunction != "") { 
        
        var startDate = $("#startDateField").val(),
            endDate = $("#endDateField").val();

        if (startDate == "" || endDate == "") {
            toastr['warning']("Kindly select date range for reports", "Invalid Entry"); 
            hide_loading("#contentBody");
            $("#closeBtn").click();
        }
        else{ 
            Export(startDate, endDate);
        }
    }else{ 
        $("#closeBtn").click();
        toastr['warning']("Oops! No export type selected", "Empty Data");  
        hide_loading("#contentBody"); 
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
            setTimeout(unlock, 3000);

            hide_loading("#contentBody");
            if(result[0].response == "Success"){  
                toastr['success']("Congratulations! You've successfully exported specific report", "Sucess"); 
                window.location = "excel/"+result[0].filename;
            } 
            else if (result[0].response == "Empty"){ 
                toastr['warning']("Oops! No data on selected dates for report", "Empty Data");   
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
    $("#selectionField").hide(); 
    $("#exportTypeField").val(''); 
    ExportFunction = "";
}


 

var initAgeChart = function(data) {

    var chart = AmCharts.makeChart("ageChart", {
                "theme": "light",
                "type": "serial",
                "dataProvider": data, 
                "fontFamily": "HelveticaNeue",
                "color":"#3e3e3e",
                "graphs": [{
                    "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                    "fillAlphas": 1,
                    "lineAlpha": 0.2,
                    "title": "Customers ",
                    "type": "column",
                    "valueField": "count"
                }],
                "depth3D": 1,
                "angle": 1,
                "rotate": true,
                "categoryField": "label",
                "categoryAxis": {
                    "gridPosition": "start",
                    "fillAlpha": 0.05,
                    "position": "left"
                },
                "export": {
                    "enabled": true
                 }
            });   

    // document.getElementById('ageChart').style.width = w + 'px';
    document.getElementById('ageChart').style.height = '160px';
    chart.invalidateSize();
}