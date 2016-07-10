$(function() {
	// init background slide images
	$.backstretch([
		// "assets/img/bg/1.jpg",
		"assets/img/bg/home.jpg"
		], {
			fade: 1000,
			duration: 8000
    	}
    );

    $('.backstretch').addClass("dim");

    $("#username").change(function(){
	    var username = $('#username').val();

	    if (!username) {
			$('#username-form').addClass('has-error');
			toastr['warning']("Username field is empty.", "Login Error");
			return;
		}

		// if (validate_email_address(username) == 'Invalid') {
		// 	$('#username-form').addClass('has-error');
		// 	toastr['warning']("Invalid Username.", "Login Error");
		// 	return;
		// }

		$('#username-form').removeClass('has-error');
	});

    $("#password").change(function(){
	    var password = $('#password').val();

	    if (!password) {
			$('#password-form').addClass('has-error');
			toastr['warning']("Password field is empty.", "Login Error");
			return;
		}

		$('#password-form').removeClass('has-error');
	});

});

$('#btn-login-submit').click(function() {
	login();
});

function login() {
	$('#btn-login-submit').unbind();
	show_loading('.content');
	var username = $('#username').val();
	var password = $('#password').val();

	if (!username) {
		$('#username-form').addClass('has-error');

		if (!password) {
			$('#password-form').addClass('has-error');
			toastr['warning']("Password field is empty.", "Login Error");
		}

		toastr['warning']("Username field is empty.", "Login Error");
		hide_loading('.content');
		
		$('#btn-login-submit').click(function() {
			login();
		});
		
		return;
	} 
	
	if (!password) {
		$('#username-form').removeClass('has-error');
		$('#password-form').addClass('has-error');
		toastr['warning']("Password field is empty.", "Login Error");
		hide_loading('.content');
		
		$('#btn-login-submit').click(function() {
			login();
		});
		
		return;
	}

	$('#username-form').removeClass('has-error');
	$('#password-form').removeClass('has-error');

		var _post = "function=login&username="+encodeURIComponent(username)+"&password="+encodeURIComponent(password); 
	$.ajax({
		type: 'POST',
		url: 'php/gateway.php',
		data: _post,
		cache: false,
		async: true,
		dataType: 'TEXT',
		success: function(result){
			console.log(result);
			if (result == 'Success') {
				window.location.href = "customer.php";
			} else if (result == 'Inactive') {
				$('#password').val('');
				toastr['warning']("Oops! Your account has not yet been activated.", "Activation Required");
			} else {
				$('#password').val('');
				toastr['error']("Invalid Username/Password.", "Login Error");
			}
			hide_loading('.content');
		}
	});
 



	$('#btn-login-submit').click(function() {
		login();
	});
}

$('#forget-password').click(function () {
    $('.login-form').hide();
    $('.forget-form').show();
});

$('#back-btn').click(function () {
    $('.login-form').show();
    $('.forget-form').hide();
});

/********** Login **********/
$('#username').on('keyup', function(e) {
    if (e.keyCode === 13) {
        $('#btn-login-submit').click();
    }
});

$('#password').on('keyup', function(e) {
    if (e.keyCode === 13) {
        $('#btn-login-submit').click();
    }
});
