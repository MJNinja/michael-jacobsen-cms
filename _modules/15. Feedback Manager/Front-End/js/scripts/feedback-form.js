$(document).ready(function() {
    //SUBMIT FORM
    $('#feedback-submit').click(function() {

        //HIDE FORM
        $('#feedback-form').hide();

        //SHOW LOADER
        $('#loader-feedback').show();

        //GET VALUES
        var form                = $('input[name=form]').val();
        var visit               = $('input[name=visit]:checked').val();
        var reasonVisit         = $('textarea[name=reasonVisit]').val();
        var findWhatNeeded      = $('input[name=findWhatNeeded]:checked').val();
        var whatLookingFor      = $('textarea[name=whatLookingFor]').val();
        var easyFindInfo        = $('input[name=easyFindInfo]:checked').val();
        var professional        = $('input[name=professional]:checked').val();
        var informative         = $('input[name=informative]:checked').val();
        var visuallyPleasing    = $('input[name=visuallyPleasing]:checked').val();
        var visitAgain          = $('input[name=visitAgain]:checked').val();
        var comments            = $('textarea[name=comments]').val();

        //HONEY POTS
        var fullName    = $('input[name=feedback-name]').val();
        var email       = $('input[name=feedback-email]').val();

        //DO AJAX
        $.ajax({
    		type: "POST",
    		url: url+"ajax/ajax.fbcds.php",
    		data: 'form=' + form + '&fullName=' + fullName + '&email=' + email + '&visit=' + visit + '&reasonVisit=' + reasonVisit + '&findWhatNeeded=' + findWhatNeeded + '&whatLookingFor=' + whatLookingFor + '&easyFindInfo=' + easyFindInfo + '&professional=' + professional + '&informative=' + informative + '&visuallyPleasing=' + visuallyPleasing + '&visitAgain=' + visitAgain + '&comments=' + comments + "&feedback_submit=1",
    		success: function(response) {

                var responseArray = response.split('#@#');

                //SUCCESS
                if(responseArray[0] == 'success'){
                    //SHOW SUCCESS MESSAGE
                    $('#feedback-success-holder').show();
                }
                //FAIL
                else if(responseArray[0] == 'error'){
                    //EMPTY, APPEND & SHOW ERROR
                    $('#feedback-error-holder').empty().append(responseArray[1]).show();

                    //SHOW FORM
                    $('#feedback-form').show();
                }
                else{
                    //HIDE & EMPTY ERROR
                    $('#feedback-error-holder').hide().empty();

                    //SHOW FORM
                    $('#feedback-form').show();
                }

                //HIDE LOADER
                $('#loader-feedback').hide();
            }
    	});

    });

    //CLOSE COLORBOX
    $('#feedback-close').click(function() {
        $.colorbox.close();
    });
});
