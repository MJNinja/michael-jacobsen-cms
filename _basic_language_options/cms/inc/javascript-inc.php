<?php if($crop != 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/jquery.min.js"></script>
<?php }?>

<?php if($sequence == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/jquery-ui.js"></script>
<script type="text/javascript" language="javascript">
  $(function() {
    $("#sortable").sortable({
        axis: 'y',
		update: function( event, ui ) {
			var sortData = $(this).sortable('toArray');

			$.ajax({
				method: "POST",
				url: "<?php echo $cms_root; ?>ajax/sort-content.php",
				data: { list: sortData, table: '<?php echo $sequenceTable; ?>', mainID: '<?php echo $sequenceMainID; ?>' },
				success: function(data) {},
			})
		}
	});
  });
  </script>
<?php }?>

<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('.loading-overlay').css("display","none");
})

function pleasewait(){
	$('.processing-overlay').css("display","table");
}
</script>

<?php if($ckeditor == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript">
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace( 'paragraph' );
</script>
<?php }?>

<?php if($ckeditor2 == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript">
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace( 'paragraph2' );
</script>
<?php }?>

<?php if($colorbox == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/colorbox/jquery.colorbox-min.js"></script>
<?php }?>

<?php if($paragraph_image_enlarge == 1){?>
<script type="text/javascript" language="javascript">
$(".group1").colorbox({rel:'group1', maxWidth: '75%'});
</script>
<?php }?>

<?php if($date_picker == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/pikaday/moment.js"></script>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/pikaday/pikaday.js"></script>
<script type="text/javascript" language="javascript">
var picker = new Pikaday({
	field: $('#datepicker')[0],
	format : "YYYY-MM-DD"
	});

var picker2 = new Pikaday({
	field: $('#datepicker2')[0],
	format : "YYYY-MM-DD"
	});
</script>
<?php }?>

<?php if($time_picker == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/timepicki/timepicki.js"></script>
<script type='text/javascript'>
	$('#timepicker').timepicki({
		show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		increase_direction:'up'
	});
</script>
<?php }?>

<script type="text/javascript" language="javascript">
$(function() {
	// Clickable Dropdown
	$('.click-nav > ul').toggleClass('no-js js');
	$('.click-nav .js ul').hide();
	$('.click-nav .js').click(function(e) {
		$('.click-nav .js ul').slideToggle(200);
		$('.clicker').toggleClass('active');
		e.stopPropagation();
	});
	$(document).click(function() {
		if ($('.click-nav .js ul').is(':visible')) {
			$('.click-nav .js ul', this).slideUp();
			$('.clicker').removeClass('active');
		}
	});
});
</script>

<?php if($gallery_upload == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/multi-image-preview-uploader/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/multi-image-preview-uploader/preview.js"></script>
<script type="text/javascript" language="javascript">
$("input[name=get_images]").click(function(){
    $("#files").trigger("click");
});
</script>
<?php }?>

<?php if($removed_user == 1){?>
<script type="text/javascript" language="javascript">
var name 		= $('input[name=user-name]').val();
var surname 	= $('input[name=user-surname]').val();
var email 		= $('input[name=user-email]').val();
var password 	= $('input[name=user-password]').val();
var number 		= $('input[name=user-contact-number]').val();
var retype 		= $('input[name=user-email-re-type]').val();
var number2 	= $('input[name=user-contact-number-2]').val();
var userType 	= $('select[name=user-type]').val();
var roleArray   = new Array();
$("input:checkbox:checked").each(function(){
    roleArray.push($(this).val());
});

//OPEN COLORBOX MODAL
$.colorbox({html:'<div class="modal-windows-holder"><div class="modal-name">Overwrite User Information</div><div class="modal-message"><p>The <b>Email</b> you supplied is currently linked to another account which is inactive.</p><p>If you wish you can reactive the account and overwrite it with the supplied information.</p><br /><p class="modal-question">Overwrite old account?</p><br /></div><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data"><input type="hidden" name="name" value="'+ name +'"/><input type="hidden" name="surname" value="'+ surname +'"/><input type="hidden" name="email" value="'+ email +'"/><input type="hidden" name="password" value="'+ password +'"/><input type="hidden" name="number" value="'+ number +'"/><input type="hidden" name="retype" value="'+ retype +'"/><input type="hidden" name="number2" value="'+ number2 +'"/><input type="hidden" name="user-type" value="'+ userType +'"/><input type="hidden" name="userSelectedRoles" value="'+ roleArray +'"/><input type="submit" class="modal-left-link" name="overright-user-info" title="Yes, overwrite old account" value="Yes, overwrite old account"><input type="button" class="modal-right-link" name="cancel-overright-user-info" title="No, leave account as is" value="No, leave account as is"></form></div>', top: '100px'});


//DON'T OVERWRITE USER INFO
$('input[name=cancel-overright-user-info]').click(function(){
	$.colorbox.close();
})

</script>
<?php }?>


<?php if($tabs == 1){ ?>
<script type="text/javascript" language="javascript">
$(".tabs").click(function(){
    var value = $(this).attr('id');

    if(value == 'para'){
        $('.paragraph-holder').show();
        $('.code-holder').hide();
        $('input[name=textHolder]').val('para');
    }else if(value == 'code'){
        $('.code-holder').show();
        $('.paragraph-holder').hide();
        $('input[name=textHolder]').val('code');
    }
});
</script>
<?php } ?>

<?php if($assignRole == 1){ ?>
<script type="text/javascript">
$('.checkChange').on('change', function() {
    //GET VALUE
    var value = $(this).val();

    //TOGGLE SHOWING OF MODULE SELECT
    if(value == 2){
        $('#userModulesSelect').show();
    }else{
        $('#userModulesSelect').hide();
    }
});
</script>
<?php } ?>

<?php if($generatePassword == 1){ ?>
<script type="text/javascript">
    var passwordData;

    $('.generate-password').colorbox({
        href:"<?php echo $cms_root; ?>ajax/ajax.generatePassword.php",
        top: 150,
        onClosed: function(){
            //CHECK IF PASSWORD DATA HAS BEEN SET
            if(passwordData != 'undefined' && passwordData != ' ' && passwordData != ''){
                //SET VALUE OF INPUT FIELD
                $('input[name=user-password]').val(passwordData);
            }
        }
    });

</script>
<?php } ?>

<?php if($lang_select == 1){ ?>
<script type="text/javascript">
$('#changeLanguage').colorbox({
    href:"<?php echo $cms_root; ?>ajax/select-language.php",
    top: 150
});
</script>
<?php } ?>
