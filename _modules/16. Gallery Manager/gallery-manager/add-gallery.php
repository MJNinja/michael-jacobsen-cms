<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 16;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Add Gallery';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.galleryManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'gallery-manager/" title="Gallery Manager">Gallery Manager</a> | <span class="current">Add Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Gallery</h1>
        <div class="intro">
        	<p>This is the <b>Add Gallery</b> page. This page will allow you to add a new Gallery.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Gallery</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new software. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Gallery</b> to add the new gallery.
                </div>

                 <?php
				if(!empty($error_message)){
					echo '<div class="rightContentBoxContainerError">';
					echo '<div class="message">'.$error_message.'</div>';
					if(!empty($errors)){
						echo '<div class="errorMessage">'.$errors.'</div>';
					}
					echo '</div>';
				}
				?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="module-form-holder">
                    	<div class="module-form-titles"><span class="required">*</span> Gallery Name:</div>
						<input type="text" name="gallery-name" placeholder="Gallery Name" value="<?php if($_POST['gallery-name'] != ''){echo $_POST['gallery-name'];}?>" maxlength="150" />
                        <i>The gallery name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Software Type:</div>
						<input type="text" name="gallery-type" placeholder="Gallery Type" value="<?php if($_POST['gallery-type'] != ''){echo $_POST['gallery-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles">Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>
					<input type="submit" class="module-form-submit" name="add_gallery" title="Add Gallery" value="Add Gallery" onclick="pleasewait()" />
				</form>

            </div>
            <!-- END PARAGRAPH HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/gallery-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
