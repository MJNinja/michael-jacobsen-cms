/*DEFAULT*/
var responseArray   = '';
var result          = '';
var cdtm            = '';
var fbcs            = '';
var popuptime       = 300000;

/*GET COOKIE*/
getCookie();

/*CHECK IF COOKIE IS EMPTY*/
function checkCookie(result, fbcs){
    if(result == '' || result == ' ' || result === undefined){
        setCookie();
    }
    //CLEAR INTERVAL
    else if(fbcs == 'yes'){
        clearInterval(timer);
    }
}

/*CHECK IF COOKIE IS EMPTY*/
function reloadPage(){
    location.reload();
}

/*GET COOKIE*/
function getCookie() {
    /*DO AJAX*/
    $.ajax({
		type: "POST",
		url: url+"ajax/ajax.fbcds.php",
		data: "gfbcd=1",
		success: function(response) {
            responseArray   = response.split('#@#');
            result  = responseArray[0];
            cdtm    = responseArray[1];
            fbcs    = responseArray[2];

            //CHECK COOKIE
            checkCookie(result, fbcs);
        }
	});
}

/*SET COOKIE*/
function setCookie() {
    /*DO AJAX*/
    $.ajax({
		type: "POST",
		url: url+"ajax/ajax.fbcds.php",
		data: "cfbcd=1",
		success: function() {
            reloadPage();
        }
	});
}

/*CHECK IF COLORBOX SHOULD BE OPENED*/
function checkOpenColorbox(){
    var cDate = new Date();
    var milli = cDate.getTime();
    var difference = milli - cdtm;

    //OPEN COLORBOX & CLEAR INTERVAL
    if(difference >= popuptime){
        //CLEAR INTERVAL
        clearInterval(timer);

        //OPEN COLORBOX
        $.colorbox({
            href: url+"ajax/ajax.feedback.php",
            top: 100,
            fixed: true
        });

        //DO AJAX
        $.ajax({
    		type: "POST",
    		url: url+"ajax/ajax.fbcds.php",
    		data: "ufbcd=1",
    		success: function() {}
    	});
    }
}

/*CHECK EVERY MINUTE*/var timer = setInterval(function() {
    checkOpenColorbox();
}, 60 * 1000);
