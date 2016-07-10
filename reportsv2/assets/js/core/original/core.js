$(function() {

    /********** Preloader Screen **********/
	$(window).load(function() {
        window.setTimeout(function() {
        	$('body').css({'overflow': 'auto'});

        	if ((basename == '404') || (basename == '500')) {
        		$('body').css({'overflow-x': 'hidden'});
        	}

            $('.preloader-container').fadeOut();
			$('.preloader-wrapper').delay(50).fadeOut('fast');

			if ((basename == '404') || (basename == '500')) {
        		$('.number').addClass('rubberBand animated');
        	} else if (basename == 'login') {
        		$('#logo-img').addClass('rubberBand animated');
    		}
        }, 2000);
	});

	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "positionClass": "toast-bottom-left",
	  "onclick": null,
	  "showDuration": "1000",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	}

	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	// Demo.init(); // init demo features
	// UIToastr.init();
	if (basename != 'login') {
		// UIIdleTimeout.init(); // initialize session timeout settings
	}

});

function show_loading(destination) {
	Metronic.blockUI({
        target: destination
    });
    return;
}

function hide_loading(destination) {
    window.setTimeout(function() {
        Metronic.unblockUI(destination);
    }, 2000);
}

function validate_email_address(email){
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (reg.test(email) == false) {
    	return "Invalid";
    } else {
    	return "Valid";
    }
}

function numberFormat(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}



function GetToday () {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 

    if(mm<10) {
        mm='0'+mm
    } 

    return mm+'/'+dd+'/'+yyyy;
}

function StartPreloader() { 
    // var $this = object;
    // console.log($this);
    // var offset = $this.offset();
    // var width = $this.width();
    // var height = $this.height();

    // var centerX = offset.left + width / 2;
    // var centerY = offset.top + height / 2;
    // console.log("centerX: "+centerX);
    // console.log("centerY: "+centerY);
    // console.log("top: "+$this.position().top);
    // console.log("left: "+$this.position().left);
    // var newy = parseInt(centerY) + 120;

    $("#preloader").show(); 
}

function StopPreloader() {
    $("#preloader").hide(); 
}