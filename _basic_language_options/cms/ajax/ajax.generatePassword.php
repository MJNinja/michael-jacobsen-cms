<div class="generate-password-modal-holder">
    <div class="generate-password-modal-name">Password Generator</div>

    <p>
        In order to generate a password, click on the <strong>Generate Password</strong> button.
    </p>

    <p>
        Once you have generated your password check the checkbox below to confirm that you have copied or written down the password. Once you click on <strong>Accept</strong> we won't be able to recover the password again.
    </p>

    <!-- DISPLAYS GENERATED PASSWORD HERE -->
    <div class="generate-password-result-holder">
        <div class="generate-password-result-title">Password:</div>
        <div class="generate-password-result" id="generatedPassword">Password will appear here</div>
        <div class="clear"></div>
    </div>

    <!-- CLICK ON BUTTON CALLS generatePassword() FUNCTION -->
    <input type="button" value="Generate Password" onClick="generatePassword()" class="generate-password-modal-button"><br /><br />

    <!-- AGREED THAT THE PASSWORD HAS BEEN COPIED -->
    <label><input type="checkbox" name="copiedPassword" id="copiedPassword" value="1" /><em>I have copied the password.</em></label><br /><br />

    <!-- CLICK ON BUTTON CALLS acceptPassword() FUNCTION -->
    <input type="button" value="Accept" onClick="acceptPassword()" class="acceptPassword"> <input type="button" value="Close" onClick="closeColorbox()" class="closePasswordModal">
</div>

<!-- BEGIN GENERATE PASSWORD -->
<script language="javascript">
function generatePassword() {
	//LENGTH OF PASSWORD
    var length = 10;

	//CHARACTERS TO BE INSIDE PASSWORD
    var charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789[]@!$#?.";

	//STORES GENERATED PASSWORD
    var retVal = "";

	//GENERATES THE PASSWORD
    for (var i = 0, numCharacters = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * numCharacters));
    }

	//WRITES PASSWORD INTO DIV
	document.getElementById('generatedPassword').innerHTML = retVal;
}

function acceptPassword() {
    //CHECK IF CHECKBOX HAS BEEN CHECKED
    if(document.getElementById('copiedPassword').checked) {
        //GET GENERATED PASSWORD
        var generatedPassword = document.getElementById('generatedPassword').innerHTML;

        //SET GENERATED PASSWORD AS A PARENT VALUE SO THAT IT CAN BE PASSED BACK TO THE MAIN PAGE
        parent.passwordData = generatedPassword;

        //CLOSE COLOR BOX
        $.colorbox.close();
    }
}

function closeColorbox() {
    //CLOSE COLOR BOX
    $.colorbox.close();
}
</script>
<!-- END GENERATE PASSWORD -->
