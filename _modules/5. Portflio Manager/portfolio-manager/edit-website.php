<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$colorbox = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Website';

//GET URL VARIABLE
if(isset($_POST['portfolioID'])){$portfolioID = $_POST['portfolioID'];}else{$portfolioID = $_GET['portfolioID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//REDIRECT PAGE
if($portfolioID != ''){
	//CHECK $portfolioID INSIDE DATABASE
	if($portfolioManager->checkWebsiteDatabase($portfolioID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."portfolio-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'portfolio-manager/" title="Portfolio Manager">Portfolio Manager</a> | <span class="current">Edit Website</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");

?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Website</h1>
        <div class="intro">
        	<p>This is the <b>Edit Webiste</b> page. This page will allow you to edit the current Website.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Website</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the Website. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Website</b> to edit the website.
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
                    	<input type="hidden" name="portfolioID" value="<?php echo $portfolioID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'modifiedNumber')+1;?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $portfolioManager->getWebsiteInfo($blogPostID, 'coverImage'); ?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Website Name: </div>
						<input type="text" name="website-name" placeholder="Website Name" value="<?php if($_POST['website-name'] != ''){echo $_POST['website-name'];}else{echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName');}?>" maxlength="150" />
                        <i>The website name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Website Type:</div>
						<input type="text" name="website-type" placeholder="Website Type" value="<?php if($_POST['website-type'] != ''){echo $_POST['website-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Website Link:</div>
						<input type="text" name="website-link" placeholder="Website Link" value="<?php if($_POST['website-link'] != ''){echo $_POST['website-link'];}else{echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteLink');}?>" />
                        <i>The website link has to be a valid URL.</i>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Website Cover Image</div>
                	<p>
                        A cover image has to be linked to the Website by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Website has been uploaded.
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Website".
                    </p>

                    <?php echo $portfolioManager->getWebsiteCoverImage($portfolioID, $web_root); ?>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="edit_website" title="Edit Website" value="Edit Website" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Website Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Website</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $portfolioManager->getUsersName($portfolioManager->getWebsiteInfo($portfolioID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($portfolioManager->getWebsiteInfo($portfolioID, 'modifiedBy') != 0){
									echo $portfolioManager->getUsersName($portfolioManager->getWebsiteInfo($portfolioID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($portfolioManager->getWebsiteInfo($portfolioID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($portfolioManager->getWebsiteInfo($portfolioID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($portfolioManager->getWebsiteInfo($portfolioID, 'modifiedDate')));
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
                        	<?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'modifiedNumber');?>
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
