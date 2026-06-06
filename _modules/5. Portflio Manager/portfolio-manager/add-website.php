<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$colorbox = 1;
$pageTitle = 'Add Webste';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'portfolio-manager/" title="Portfolio Manager">Portfolio Manager</a> | <span class="current">Add Website</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Website</h1>
        <div class="intro">
        	<p>This is the <b>Add Website</b> page. This page will allow you to add a new Website.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Website</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new website. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Website</b> to add the new website.
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
                    	<div class="module-form-titles"><span class="required">*</span> Website Name:</div>
						<input type="text" name="website-name" placeholder="Website Name" value="<?php if($_POST['website-name'] != ''){echo $_POST['website-name'];}?>" maxlength="150" />
                        <i>The website name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Website Type:</div>
						<input type="text" name="website-type" placeholder="Website Type" value="<?php if($_POST['website-type'] != ''){echo $_POST['website-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Website Link:</div>
						<input type="text" name="website-link" placeholder="Website Link" value="<?php if($_POST['website-link'] != ''){echo $_POST['website-link'];}?>" />
                        <i>The website link has to be a valid URL.</i>
                    </div>

            </div>
            <!-- END PARAGRAPH HOLDER-->

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Website Cover Image</div>
                	<p>
						A cover image has to be linked to the Website by completing the fields below, please note that when the image is uploaded you will be required to crop the cover image after the Website has been uploaded.
                    </p>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>

				<input type="submit" class="module-form-submit" name="add_website" title="Add Website" value="Add Website" onclick="pleasewait()" />
			</form>
            </div>
            <!-- END IMAGE HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/portfolio-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
