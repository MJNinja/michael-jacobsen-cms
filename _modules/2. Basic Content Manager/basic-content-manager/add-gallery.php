<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 2;
$gallery_upload = 1;
$pageTitle = 'Add Gallery';

//GET URL VARIABLE
if(isset($_POST['pageID'])){$pageID = $_POST['pageID'];}else{$pageID = $_GET['pageID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.basicContentManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($pageID != ''){
	//CHECK quoteID AND quoteCatID INSIDE DATABASE
	if($basicContentManager->checkPageIDDatabase($pageID) == 'not found'){
		header("Location:".$cms_root."basic-content-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."basic-content-manager/");
	exit;
}
//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'basic-content-manager/" title="Basic Content Manager">Basic Content Manager</a> | <a href="'.$cms_root.'basic-content-manager/manage-pages.php?pageID='.$pageID.'" title="Manage Page Content">Manage Page Content</a> | <span class="current">Add Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Gallery - <?php echo $basicContentManager->getPageInfo($pageID, 'pageName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Add Gallery</b> page. This page will allow you to add a new gallery to the current blog post (<?php echo $basicContentManager->getPageInfo($pageID, 'pageName'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Gallery</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Click on <b>Choose Images</b> to selected the images that you want to upload to the new gallery. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Gallery</b> to add the new gallery with the selected images.

					<div class="module-important-notice">
						<div class="module-important-notice-title"><b>Important Notice</b></div>
						Each image must be <b>smaller than 3.5 MB</b>, otherwise the image fill not be uploaded.<br />
						A <b>maximum of 15 images</b> are allowed per upload.
					</div>
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

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="multi-image-upload">
                    <div class="module-form-holder">
                    	<input type="hidden" name="pageID" value="<?php echo $pageID ?>"/>

						<span class="hidden"><div class="module-form-titles">Gallery Name:</div>
	                    <input type="text" name="galleryName" placeholder="Gallery Name" value="<?php if($_POST['galleryName'] != ''){echo $_POST['galleryName'];}?>" maxlength="150" />
	                    <i>The gallery name has a maximum of 150 characters.</i></span>

						<div class="module-form-titles">Choose your images:</div>
						<input type="button" name="get_images" value="Choose Images" />
				    	<input type="file" name="image[]" multiple="true" id="files" class="hidden"/>
				        <input type="hidden" name="value" id="values"/>
						<i>A maximum of 15 images are allowed per upload.</i>

						<div id="gallery-errors"></div>

						<div id="preview-area" align="left"></div>

                    </div>
                    <input type="submit" class="module-form-submit" name="add_gallery" title="Add Gallery" value="Add Gallery" id="upload_images" onclick="pleasewait()"/>
                </form>
            </div>
            <!-- END PARAGRAPH HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/basic-content-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
