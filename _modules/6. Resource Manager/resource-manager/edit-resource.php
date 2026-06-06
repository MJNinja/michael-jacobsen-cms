<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 6;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$time_picker = 1;
$resource_tags = 1;
$paragraph_image_enlarge = 1;
$resource_affiliates_tags = 1;
$pageTitle = 'Edit Resource';

//GET URL VARIABLE
if(isset($_POST['resourceID'])){$resourceID = $_POST['resourceID'];}else{$resourceID = $_GET['resourceID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.resourceManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($resourceID != ''){
	//CHECK resourceID INSIDE DATABASE
	if($resourceManager->checkResourceDatabase($resourceID) == 'not found'){
		header("Location:".$cms_root."resource-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."resource-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'resource-manager/" title="Resource Manager">Resource Manager</a> | <span class="current">Edit Resource</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Resource</h1>
        <div class="intro">
        	<p>This is the <b>Edit Resource</b> page. This page will allow you to edit an existing Resource.</p>
        </div>

        <div class="left-column">
            <!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Resource</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the resource. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Resource</b> to edit the resource.
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
				echo $removed_user;
				?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="module-form-holder">
                    	<input type="hidden" name="resourceID" value="<?php echo $resourceID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $resourceManager->getResourceInfo($resourceID, 'modifiedNumber')+1;?>"/>
                        <input type="hidden" name="oldImage" value="<?php echo $resourceManager->getResourceInfo($resourceID, 'resourceImageFile');?>"/>
						<input type="hidden" name="oldFile" value="<?php echo $resourceManager->getResourceInfo($resourceID, 'zipFile');?>"/>
						<input type="hidden" name="categories" value="">
	                    <input type="hidden" name="required-softwares" value="">
						<?php if($resource_affiliates_tags == 1){?>
						<input type="hidden" name="affiliates" value="">
						<?php }?>

						<div class="module-form-titles"><span class="required">*</span> Resource Title:</div>
						<input type="text" name="resource-title" placeholder="Resource Title" value="<?php if($_POST['resource-title'] != ''){echo $_POST['resource-title'];}else{echo $resourceManager->getResourceInfo($resourceID, 'resourceName');}?>" maxlength="150" />
                        <i>The Resource Title has a maximum of 150 characters.</i>

						<div class="module-form-titles"><span class="required">*</span> Intro:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $resourceManager->getResourceInfo($resourceID, 'resourceDescription');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Date:</div>
                            <input type="text" name="resource-date" id="datepicker" placeholder="Publish Date" value="<?php if($_POST['resource-date'] != ''){echo $_POST['resource-date'];}else{ echo $resourceManager->getDateTime('date', $resourceID);}?>" />
                            <i>Please supply the publish date of the Resource.</i>
                        </div>

						<div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Time:</div>
                            <input type="text" name="resource-time" id="timepicker" placeholder="Publish Time" value="<?php if($_POST['resource-time'] != ''){echo $_POST['resource-time'];}else{ echo $resourceManager->getDateTime('time', $resourceID);}?>" />
                            <i>Please supply the publish time of the Resource.</i>
                        </div>
                        <div class="clear"></div>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Resource Categories:</div>
                            <ul id="category_tags">
                                <?php if($_POST['categories'] != ''){ echo $resourceManager->generatePostedTags($_POST['categories']);}else{ echo $resourceManager->getTags('resourceCatIDs', $resourceID);}?>
                            </ul>
                            <i>Please supply all the categories under which this Resource falls under.</i>
                        </div>

						<?php if($resource_affiliates_tags == 1){?>
						<div class="module-time-input">
                            <div class="module-form-titles">Affiliate Links:</div>
                            <ul id="affiliate_tags">
                                <?php if($_POST['affiliates'] != ''){ echo $resourceManager->generatePostedTags($_POST['affiliates']);}else{ echo $resourceManager->getAffiliateTags($resourceID, 'affiliateIDs');}?>
                            </ul>
                            <i>Please supply all the affiliate links which should be added to this blog post.</i>
                        </div>
						<?php }?>

                        <span class="hidden"><div class="module-time-input">
                            <div class="module-form-titles"> Required Softwares:</div>
                            <ul id="software_tags">
                                <?php if($_POST['required-softwares'] != ''){ echo $resourceManager->generatePostedTags($_POST['required-softwares']);}?>
                            </ul>
                            <i>Please supply all the required softwares needed for doing the tutorials in this playlist.</i>
                        </div></span>
                        <div class="clear"></div>

                    </div>
            </div>
            <!-- END PARAGRAPH HOLDER-->

			<!-- BEGIN DOCUMENT HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Resource Zip File</div>
					<p>
                        An Zip File has to be linked to the Resource by completing the fields below.
                    </p>

					<?php echo $resourceManager->getResourceZipFile($resourceID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Zip File Title:</div>
                    <input type="text" name="zip-title" placeholder="Zip File Title" value="<?php if($_POST['zip-title'] != ''){echo $_POST['zip-title'];}else{echo $resourceManager->getResourceInfo($resourceID, 'zipFileTitle');}?>" />
                    <i>The zip file title has a maximum of 150 characters.</i>

					<span class="hidden"><div class="module-form-titles">Zip Type:</div>
                    <input type="text" name="zip-type" placeholder="Zip Type" value="<?php if($_POST['zip-type'] != ''){echo $_POST['zip-type'];}?>" maxlength="150" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Zip File:</div>
                    <input type="file" name="zip-file" />
                    <i>The zip file has to be in zip format.</i>
                </div>
            </div>
            <!-- END DOCUMENT HOLDER-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
					<div class="module-form-titles">Resource Image</div>
                	<p>
                        An image has to be linked to the Resource by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Resource has been uploaded.
                    </p>

					<?php echo $resourceManager->getResourceImage($resourceID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $resourceManager->getResourceInfo($resourceID, 'resourceImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
                <input type="submit" class="module-form-submit" name="edit_resource" title="Edit Resource" value="Edit Resource" onclick="pleasewait()"/>
            </form>

            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Resource Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Resource</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $resourceManager->getUsersName($resourceManager->getResourceInfo($resourceID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($resourceManager->getResourceInfo($resourceID, 'modifiedBy') != 0){
									echo $resourceManager->getUsersName($resourceManager->getResourceInfo($resourceID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($resourceManager->getResourceInfo($resourceID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($resourceManager->getResourceInfo($resourceID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($resourceManager->getResourceInfo($resourceID, 'modifiedDate')));
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
                        	<?php echo $resourceManager->getResourceInfo($resourceID, 'modifiedNumber');?>
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
                	<?php include_once("../inc/resource-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
