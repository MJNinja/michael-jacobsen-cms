<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$gallery_upload = 1;
$pageTitle = 'Edit Gallery';

//GET URL VARIABLE
if(isset($_POST['portfolioID'])){$portfolioID = $_POST['portfolioID'];}else{$portfolioID = $_GET['portfolioID'];}
if(isset($_POST['portfolioGalleryID'])){$portfolioGalleryID = $_POST['portfolioGalleryID'];}else{$portfolioGalleryID = $_GET['portfolioGalleryID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//REDIRECT PAGE
if($portfolioID != '' && $portfolioGalleryID != ''){
	//CHECK $pageID INSIDE DATABASE
	if($portfolioManager->checkPortfolioIDDatabase($portfolioID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}

    //CHECK $basicPagesGalleryID INSIDE DATABASE
    if($portfolioManager->checkPortfolioGalleryDatabase($portfolioGalleryID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."portfolio-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'portfolio-manager/" title="Portfolio Manager">Portfolio Manager</a> | <a href="'.$cms_root.'portfolio-manager/manage-website-content.php?portfolioID='.$portfolioID.'" title="Manage Website Content">Manage Website Content</a> | <span class="current">Edit Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Gallery - <?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Gallery</b> page. This page will allow you to edit the current gallery (<?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN GALLERY IMAGE PREVIEW -->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Gallery</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Click on <b>Choose Images</b> to selected the images that you want to upload to the current gallery. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Gallery</b> to edit the current gallery with the selected images.

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
                    	<input type="hidden" name="portfolioID" value="<?php echo $portfolioID; ?>"/>
                        <input type="hidden" name="portfolioGalleryID" value="<?php echo $portfolioGalleryID; ?>"/>

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
            </div>
            <!-- END GALLERY IMAGE PREVIEW -->

            <!-- BEGIN GALLERY IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                    <div class="module-form-titles">Current Gallery Image(s)</div>
                    <p>
                        Below are all the images currently assigned to this gallery. If you wish to change the title of an image simply edit the field next to it, or if you wish to remove an image check the box next to the image.
                    </p>

                    <?php echo $portfolioManager->getPortfolioGalleryImages($portfolioGalleryID, $web_root);?>

                </div>
				<input type="submit" class="module-form-submit" name="edit_gallery" title="Edit Gallery" value="Edit Gallery" id="upload_images" onclick="pleasewait()"/>
	        </form>
            </div>
            <!-- END GALLERY IMAGE HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Website Gallery Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Blog Post Paragraph</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $portfolioManager->getUsersName($portfolioManager->getGalleryInfo($portfolioGalleryID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($portfolioManager->getGalleryInfo($portfolioGalleryID, 'modifiedBy') != 0){
									echo $portfolioManager->getUsersName($portfolioManager->getGalleryInfo($portfolioGalleryID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($portfolioManager->getGalleryInfo($portfolioGalleryID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($portfolioManager->getGalleryInfo($portfolioGalleryID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($portfolioManager->getGalleryInfo($portfolioGalleryID, 'modifiedDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>
                        	<div class="edit-information-table-label"><b>No. of Times Modified:</b></div>
                        	<?php echo $portfolioManager->getGalleryInfo($portfolioGalleryID, 'modifiedNumber');?>
                        </td>
                      </tr>
                    </table>

                </div>
            </div>
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
