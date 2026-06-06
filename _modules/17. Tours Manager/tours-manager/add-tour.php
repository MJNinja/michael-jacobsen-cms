<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 17;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Add Tours';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.toursManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'tours-manager/" title="Tours Manager">Tours Manager</a> | <span class="current">Add Tour</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Tour</h1>
        <div class="intro">
        	<p>This is the <b>Add Tour</b> page. This page will allow you to add a new tour.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Tour</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new product. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Tour</b> to add the new tour.
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
                    	<div class="module-form-titles"><span class="required">*</span> Tour Title:</div>
						<input type="text" name="tour-title" placeholder="Tour Title" value="<?php if($_POST['tour-title'] != ''){echo $_POST['tour-title'];}?>" maxlength="150"/>
                        <i>The tour title has a maximum of 150 characters.</i>

                        <!--<div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Description"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>The tour intro requires a minimum of 10 characters.</i>-->

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="tour-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['tour-paragraph'] != ''){echo $_POST['tour-paragraph'];}?></textarea>
                        <i>Please supply an intro for the tour.</i></span>

						<div class="clear"></div>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Tour Image</div>
                	<p>
						An image can be linked to the Tour by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Tour has been uploaded.
                    </p>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>

				<input type="submit" class="module-form-submit" name="add_tour" title="Add Tour" value="Add Tour" onclick="pleasewait()" />
			</form>
            </div>
            <!-- END IMAGE HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/tours-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
