<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 17;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Add Paragraph';

//GET URL VARIABLE
if(isset($_POST['tourID'])){$tourID = $_POST['tourID'];}else{$tourID = $_GET['tourID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.toursManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($tourID != ''){
	//CHECK tourID INSIDE DATABASE
	if($toursManager->checkTourDatabase($tourID) == 'not found'){
		header("Location:".$cms_root."tours-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."tours-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'tours-manager/" title="Tours Manager">Tours Manager</a> | <a href="'.$cms_root.'tours-manager/manage-Tour.php?tourID='.$tourID.'" title="Manage Tour">Manage Tour</a> | <span class="current">Add Paragraph</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Paragraph - <?php echo $toursManager->getTourInfo($tourID, 'tourTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Add Paragraph</b> page. This page will allow you to add a new paragraph to the current tour (<?php echo $toursManager->getTourInfo($tourID, 'tourTitle'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Paragraph</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new paragraph. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Paragraph</b> to add the new paragraph.
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
                        <input type="hidden" name="tourID" value="<?php echo $tourID; ?>"/>
                    	<div class="module-form-titles">Paragraph Title:</div>
						<input type="text" name="paragraph-title" placeholder="Paragraph Title" value="<?php if($_POST['paragraph-title'] != ''){echo $_POST['paragraph-title'];}?>" maxlength="150" />
                        <i>The title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Paragraph Type:</div>
						<input type="text" name="paragraph-type" placeholder="Paragraph Type" value="<?php if($_POST['paragraph-type'] != ''){echo $_POST['paragraph-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
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

                    <div class="module-form-titles">Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}?>" maxlength="150" />
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

                    <div class="module-form-titles">Document Title:</div>
                    <input type="text" name="doc-title" placeholder="Document Title" value="<?php if($_POST['doc-title'] != ''){echo $_POST['doc-title'];}?>" maxlength="150" />
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
						</div>
						<input type="text" name="youtube-vimeo-video" cols="20" rows="5" placeholder="YouTube/Vimeo Video" value="<?php if($_POST['youtube-vimeo-video'] != ''){echo $_POST['youtube-vimeo-video'];}?>" />
                        <i>Copy your YouTube/Vimeo link from the URL bar into the input field below.</i>
                    </div>
                    <input type="submit" class="module-form-submit" name="add_paragraph" title="Add Paragraph" value="Add Paragraph" onclick="pleasewait()" />
                </form>

            </div>
            <!-- END YOUTUBE/VIMEO HOLDER-->
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
