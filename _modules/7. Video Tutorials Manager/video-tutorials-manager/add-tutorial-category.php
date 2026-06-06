<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Add Tutorial Category';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.videoTutorialsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'video-tutorials-manager/" title="Video Tutorials Manager">Video Tutorials Manager</a> | <a href="'.$cms_root.'video-tutorials-manager/manage-tutorial-category.php" title="Manage Tutorial Categories">Manage Tutorial Categories</a> | <span class="current">Add Tutorial Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Tutorial Category</h1>
        <div class="intro">
        	<p>This is the <b>Add Tutorial Category</b> page. This page will allow you to add a new Video Tutorial Category.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Tutorial Category</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new tutorial category. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Tutorial Category</b> to add the new tutorial category.
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
                    	<div class="module-form-titles"><span class="required">*</span> Video Tutorial Category Title:</div>
						<input type="text" name="category-title" placeholder="Video Tutorial Category Title" value="<?php if($_POST['category-title'] != ''){echo $_POST['category-title'];}?>" maxlength="150" />
                        <i>The Video Tutorial Category Title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Tutorial Type:</div>
						<input type="text" name="tutorial-type" placeholder="Tutorial Type" value="<?php if($_POST['tutorial-type'] != ''){echo $_POST['tutorial-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>

            </div>
            <!-- END PARAGRAPH HOLDER-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Tutorial Category Image</div>
                	<p>
                        An image has to be linked to the Video Tutorial Category by completing the fields below, please note that when the image is uploaded
                        you will be required to crop the image after the Video Tutorial Category has been uploaded.
                    </p>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}?>" maxlength="150" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="add_tutorial_category" title="Add Tutorial Category" value="Add Tutorial Category" onclick="pleasewait()" />
			</form>

            </div>
            <!-- END IMAGE HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/video-tutorials-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
