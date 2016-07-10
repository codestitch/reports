$(function() {
	console.log("ready");  
});


var initUserRegChart = function(data) {

    var _total = 0; 
    for (var i = 0; i < data.length; i++) {
        _total += parseInt(data[i].count); 
    };

    $("#chart1Label").html("Total: <b>"+numberFormat(_total)+"</b>");

    var chart = AmCharts.makeChart("user_registrationChart", {
        "type": "pie",
        "fontFamily": 'Open Sans', 
        "color":    '#888', 
        "autoResize" : true,
        "dataProvider": data,
        "valueField": "count",
        "titleField": "v_platform",
        "theme": "light",
        "radius": "29%", 
        "legend": {  
            "enabled": true,
            "align": "center",
            "markerType": "circle",
            "switchType": "v",
            "textClickEnabled": true,
            "valueAlign": "left"
        },
        "exportConfig": {
            menuItems: [{
                icon: Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                format: 'png'
            }]
        }
    });

    $('#user_registrationChart').closest('.portlet').find('.fullscreen').click(function() {
        chart.invalidateSize();
    }); 

}



var initUserDownloadChart = function(data) {
    var _total = 0; 
    for (var i = 0; i < data.length; i++) {
        _total += parseInt(data[i].total); 
    };

    $("#chart2Label").html("Total: <b>"+numberFormat(_total)+"</b>");
    
    var chart = AmCharts.makeChart("user_downloadChart", {
        "type": "pie",
        "theme": "light", 
        "fontFamily": 'Open Sans', 
        "color":    '#888', 
        "dataProvider":  data,
        "valueField": "total",
        "titleField": "platform",
        "labelRadius": 5, 
        "radius": "35%",
        "legend": {  
            "enabled": true,
            "align": "center",
            "markerType": "circle",
            "switchType": "v",
            "textClickEnabled": true,
            "valueAlign": "left"
        },
        "innerRadius": "60%",
        "labelText": "[[title]]",
        "exportConfig": {
            menuItems: [{
                icon: Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                format: 'png'
            }]
        }
    });

    $('#user_downloadChart').closest('.portlet').find('.fullscreen').click(function() {
        chart.invalidateSize();
    });
}

var initAgeChart = function(data) {

    var chart = AmCharts.makeChart("ageChart", {
                "theme": "light",
                "type": "serial",
                "dataProvider": data, 
                "graphs": [{
                    "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                    "fillAlphas": 1,
                    "lineAlpha": 0.2,
                    "title": "Customers",
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

    // var chart = AmCharts.makeChart("ageChart", {
    //     "type": "pie",
    //     "theme": "light",
    //     "fontFamily": 'Open Sans', 
    //     "color":    '#888', 
    //     "autoResize" : true,
    //     "dataProvider":  data,
    //     "valueField": "count",
    //     "titleField": "label",
    //     "radius": "35%",
    //     "legend": {  
    //         "enabled": true,
    //         "align": "center",
    //         "markerType": "circle",
    //         "switchType": "v",
    //         "textClickEnabled": true,
    //         "valueAlign": "left"
    //     },
    //     "export": {
    //         "enabled": true
    //     },
    //     "outlineAlpha": 0.4,
    //     "depth3D": 15,
    //     "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
    //     "angle": 30,
    // });

    $('#ageChart').closest('.portlet').find('.fullscreen').click(function() {
        chart.invalidateSize();
    });
}

var initGenderChart = function(data) {
    var chart = AmCharts.makeChart("genderChart", {
        "type": "pie",
        "theme": "light",
        "fontFamily": 'Open Sans', 
        "color":    '#888', 
        "autoResize" : true,
        "dataProvider":  data,
        "valueField": "count",
        "titleField": "gender",
        "radius": "35%",
        "innerRadius": "50%",
        "legend": {  
            "enabled": true,
            "align": "center",
            "markerType": "circle",
            "switchType": "v",
            "textClickEnabled": true,
            "valueAlign": "left"
        },
        "export": {
            "enabled": true
        },
        "outlineAlpha": 0.4,
        "depth3D": 15,
        "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
        "angle": 30,
    });

    $('#genderChart').closest('.portlet').find('.fullscreen').click(function() {
        chart.invalidateSize();
    });
}




var initProductStatChart = function(data) {
    var chart = AmCharts.makeChart("productStatChart", {
        "type": "serial",
        "theme": "light",
        "dataProvider":  data,
        "valueAxes": [ {
            "gridColor": "#FFFFFF",
            "gridAlpha": 0.2,
            "dashLength": 0,
            "integersOnly": true
        } ],
        "gridAboveGraphs": true,
        "startDuration": 1,
        "graphs": [ {
            "balloonText": "[[category]]: <b>[[value]]</b>",
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "count"
        } ],
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },
        "categoryField": "label",
        "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0,
            "tickPosition": "start",
            "tickLength": 20
        },
        "export": {
            "enabled": true
        }
    });

    $('#productStatChart').closest('.portlet').find('.fullscreen').click(function() {
        chart.invalidateSize();
    });
}