<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 6;
$colorbox = 1;
$paragraph_image_enlarge = 1;
$ckeditor = 1;
$tabs = 1;
$pageTitle = 'Edit Paragraph';

//GET URL VARIABLE
if(isset($_POST['resourceID'])){$resourceID = $_POST['resourceID'];}else{$resourceID = $_GET['resourceID'];}
if(isset($_POST['resourceContentID'])){$resourceContentID = $_POST['resourceContentID'];}else{$resourceContentID = $_GET['resourceContentID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.resourceManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($resourceID != '' && $resourceContentID != ''){
	//CHECK resourceID AND resourceContentID INSIDE DATABASE
	if($resourceManager->checkParagraphContentDatabase($resourceID, $resourceContentID) == 'not found'){
		header("Location:".$cms_root."resource-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."resource-manager/");
	exit;
}

//CHECK WHICH CONTENT HAS TEXT
$hasContent = $resourceManager->checkContentHasText($resourceContentID);

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'resource-manager/" title="Resource Manager">Resource Manager</a> | <a href="'.$cms_root.'resource-manager/manage-resource-content.php?resourceID='.$resourceID.'" title="Manage Resource">Manage Resource</a> | <span class="current">Edit Paragraph</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Paragraph - <?php echo $resourceManager->getResourceInfo($resourceID, 'resourceName');?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Paragraph</b> page. This page will allow you to edit the current paragraph of the current resource (<?php echo $resourceManager->getResourceInfo($resourceID, 'resourceName');?>).</p>
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
						<input type="hidden" name="textHolder" value="<?php if($hasContent == 'para'){echo 'para';}elseif($hasContent == 'code'){echo 'code';}?>" />
                    	<input type="hidden" name="resourceID" value="<?php echo $resourceID; ?>"/>
                        <input type="hidden" name="resourceContentID" value="<?php echo $resourceContentID;?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $resourceManager->getParagraphContentInfo($resourceContentID, 'imageFile');?>"/>
						<input type="hidden" name="oldDocument" value="<?php echo $resourceManager->getParagraphContentInfo($resourceContentID, 'documentFile');?>"/>
						<input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $resourceManager->getParagraphContentInfo($resourceContentID, 'modifiedNumber')+1;?>"/>

                    	<div class="module-form-titles">Paragraph Title:</div>
						<input type="text" name="paragraph-title" placeholder="Paragraph Title" value="<?php if($_POST['paragraph-title'] != ''){echo $_POST['paragraph-title'];}else{echo $resourceManager->getParagraphContentInfo($resourceContentID, 'paragraphTitle');}?>" maxlength="150" />
                        <i>The title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Paragraph Type:</div>
						<input type="text" name="paragraph-type" placeholder="Paragraph Type" value="<?php if($_POST['paragraph-type'] != ''){echo $_POST['paragraph-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="tabs-holder">
							<div class="tabs" id="para">
								Paragraph
							</div>
							<div class="tabs" id="code">
								Code
							</div>
							<div class="clear"></div>
						</div>

						<div class="paragraph-holder" <?php if($hasContent != 'para'){echo 'style="display: none;"';}?>>
							<div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
							<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $resourceManager->getParagraphContentInfo($resourceContentID, 'paragraph');}?></textarea>
							<i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
						</div>

						<div class="code-holder" <?php if($hasContent != 'code'){echo 'style="display: none;"';}?>>
							<div class="module-form-titles"><span class="required">*</span> Code:</div>
							<textarea name="code" cols="20" rows="8"><?php if($_POST['code'] != ''){echo $_POST['code'];}else{echo $resourceManager->getParagraphContentInfo($resourceContentID, 'code');}?></textarea>
							<i>Please supply the entire code you want to display for this resource.</i>
						</div>
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

                    <?php echo $resourceManager->getParagraphContentImage($resourceContentID, $web_root); ?>

                    <div class="module-form-titles">Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $resourceManager->getParagraphContentInfo($resourceContentID, 'imageTitle');}?>" maxlength="150" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles">Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
            </div>
            <!-- END IMAGE HOLDER-->

            <!-- BEGIN DOCUMENT HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Paragraph Document</div>
					<p>
                        A document can be linked to the paragraph by completing the fields below. Once a document is selected the document
                        title automatically becomes a mandatory field.
                    </p>

                	<?php echo $resourceManager->getParagraphContentDocument($resourceContentID, $web_root); ?>

                    <div class="module-form-titles">Document Title:</div>
                    <input type="text" name="doc-title" placeholder="Document Title" value="<?php if($_POST['doc-title'] != ''){echo $_POST['doc-title'];}else{echo $resourceManager->getParagraphContentInfo($resourceContentID, 'documentTitle');}?>" maxlength="150" />
                    <i>The document title has a maximum of 150 characters.</i>


                    <div class="module-form-titles">Document File:</div>
                    <input type="file" name="doc-file" />
                    <i>The document file has to be in pdf format.</i>
                </div>
            </div>
            <!-- END DOCUMENT HOLDER-->

            <!-- BEGIN YOUTUBE/VIMEO HOLDER-->
            <div class="module-holder">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="module-form-holder">

                        <div class="module-form-titles"> YouTube/Vimeo Video:</div>
                        <div>
                        	<p>
                            	To add a YouTube/Vimeo video to the paragraph kindly follow the following instructions:
							</p>
                            <ol>
                                <li>Find and open video on YouTube/Vimeo.</li>
                                <li>Copy the link directly from the URL bar into the input field below.<br /><b>DO NOT CHANGE THE LINK AND MAKE SURE THAT THE LINK IS COPIED DIRECTLY FROM THE URL BAR</b></li>
                            </ol>

                           <?php echo $resourceManager->getParagraphContentVideo($resourceContentID); ?>

						</div>
						<input type="text" name="youtube-vimeo-video" cols="20" rows="5" placeholder="YouTube/Vimeo Video" value="<?php if($_POST['youtube-vimeo-video'] != ''){echo $_POST['youtube-vimeo-video'];}else{echo $resourceManager->getParagraphContentInfo($resourceContentID, 'videoUrl');}?>" />
                        <i>Copy your YouTube/Vimeo link from the URL bar into the input field below.</i>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_paragraph" title="Edit Paragraph" value="Edit Paragraph" onclick="pleasewait()"/>
                </form>

            </div>
            <!-- END YOUTUBE/VIMEO HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Blog Post Paragraph Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Blog Post Paragraph</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $resourceManager->getUsersName($resourceManager->getParagraphInfo($resourceContentID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($resourceManager->getParagraphInfo($resourceContentID, 'modifiedBy') != 0){
									echo $resourceManager->getUsersName($resourceManager->getParagraphInfo($resourceContentID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($resourceManager->getParagraphInfo($resourceContentID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($resourceManager->getParagraphInfo($resourceContentID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($resourceManager->getParagraphInfo($resourceContentID, 'modifiedDate')));
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
                        	<?php echo $resourceManager->getParagraphInfo($resourceContentID, 'modifiedNumber');?>
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
