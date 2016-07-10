function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=1) {
        clearInterval(error_interval);
        location.href = 'index.php';
    }
    i.innerHTML = parseInt(i.innerHTML)-1;
}
var error_interval = setInterval(function(){ countdown(); },1000);