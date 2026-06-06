<?php
//SET INCLUDES
include_once('../../library/class.systemConfig.php');
include_once('../../library/cms.userLogin.php');
?>
<div class="generate-password-modal-holder">
    <div class="generate-password-modal-name">Change Language</div>

    <p>
        In order to change the current language you are working on, select the new language in the list below by clicking on <strong>Change</strong>.
    </p>

    <p>
        Once you have selected your language the page will reload with the new language selected.
    </p>

    <br />

    <table width="100%" class="module-architecture-table">
        <tr class="module-architecture-header">
            <td width="5%" align="center">Flag</td>
            <td width="82%">Language</td>
            <td width="12%" align="center">Change</td>
        </tr>
        <?php echo $userLogin->getLanguageOptions($web_root, $_SESSION['ccl'])?>
    </table>

</div>

<script>
$('.change-lang').click(function(e){
    e.preventDefault();

    //GET VALUE
    var newLang = $(this).attr('href');

    //AJAX REQUERST
    $.ajax({
		type: "POST",
		url: "<?php echo $cms_root; ?>ajax/ajax.change-language.php",
		data: "newLang=" + newLang,
		success: function(response) {
            //SHOW LOADING OVERLAY
            $('.processing-overlay').css("display","table");

            //RELOAD PAGE
            location.reload();
		}
	});
});
</script>
