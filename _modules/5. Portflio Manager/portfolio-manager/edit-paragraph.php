<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$colorbox = 1;
$paragraph_image_enlarge = 1;
$ckeditor = 1;
$pageTitle = 'Edit Paragraph';

//GET URL VARIABLE
if(isset($_POST['portfolioID'])){$portfolioID = $_POST['portfolioID'];}else{$portfolioID = $_GET['portfolioID'];}
if(isset($_POST['portfolioContentID'])){$portfolioContentID = $_POST['portfolioContentID'];}else{$portfolioContentID = $_GET['portfolioContentID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//REDIRECT PAGE
if($portfolioID != '' && $portfolioContentID != ''){
	//CHECK $pageID INSIDE DATABASE
	if($portfolioManager->checkPortfolioIDDatabase($portfolioID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}

    //CHECK $basicPagesGalleryID INSIDE DATABASE
    if($portfolioManager->checkPortfolioContentDatabase($portfolioContentID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."portfolio-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'portfolio-manager/" title="Portfolio Manager">Portfolio Manager</a> | <a href="'.$cms_root.'portfolio-manager/manage-website-content.php?portfolioID='.$portfolioID.'" title="Manage Website Content">Manage Website Content</a> | <span class="current">Edit Paragraph</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Paragraph - <?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Paragraph</b> page. This page will allow you to edit the current paragraph of the current website (<?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Paragraph</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current paragraph. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Paragraph</b> to edit the current paragraph.
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
                        <input type="hidden" name="portfolioContentID" value="<?php echo $portfolioContentID; ?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $portfolioManager->getWebsiteContentInfo($portfolioContentID, 'imageFile');?>"/>

                    	<div class="module-form-titles">Paragraph Title:</div>
						<input type="text" name="paragraph-title" placeholder="Paragraph Title" value="<?php if($_POST['paragraph-title'] != ''){echo $_POST['paragraph-title'];}else{echo $portfolioManager->getWebsiteContentInfo($portfolioContentID, 'paragraphTitle');}?>" maxlength="150" />
                        <i>The title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Paragraph Type:</div>
						<input type="text" name="paragraph-type" placeholder="Paragraph Type" value="<?php if($_POST['paragraph-type'] != ''){echo $_POST['paragraph-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $portfolioManager->getWebsiteContentInfo($portfolioContentID, 'paragraph');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>

            </div>
            <!-- END PARAGRAPH HOLDER-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Paragraph Image</div>
                	<p>
                        An image can be linked to the paragraph by completing the fields below, please note that when an image is uploaded
                        you will be required to crop the image after the paragraph has been uploaded. Once an image is selected the image
                        title automatically becomes a mandatory field.
                    </p>

                    <?php echo $portfolioManager->getParagraphContentImage($portfolioContentID, $web_root); ?>

                    <div class="module-form-titles">Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $portfolioManager->getWebsiteContentInfo($portfolioContentID, 'imageTitle');}?>" maxlength="150" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles">Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="edit_paragraph" title="Edit Paragraph" value="Edit Paragraph" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Website Paragraph Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Website Paragraph</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $portfolioManager->getUsersName($portfolioManager->getWebsiteContentInfo($portfolioContentID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($portfolioManager->getWebsiteContentInfo($portfolioContentID, 'modifiedBy') != 0){
									echo $portfolioManager->getUsersName($portfolioManager->getWebsiteContentInfo($portfolioContentID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($portfolioManager->getWebsiteContentInfo($portfolioContentID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($portfolioManager->getWebsiteContentInfo($portfolioContentID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($portfolioManager->getWebsiteContentInfo($portfolioContentID, 'modifiedDate')));
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
                        	<?php echo $portfolioManager->getWebsiteContentInfo($portfolioContentID, 'modifiedNumber');?>
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
