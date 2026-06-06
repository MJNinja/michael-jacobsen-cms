<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 4;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Add Affiliate Link';

//GET URL VARIABLE
if(isset($_POST['affCatID'])){$affCatID = $_POST['affCatID'];}else{$affCatID = $_GET['affCatID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.affiliatesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($affCatID != ''){
	//CHECK affCatID INSIDE DATABASE
	if($affiliatesManager->checkCategoryDatabase($affCatID) == 'not found'){
		header("Location:".$cms_root."affiliates-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."affiliates-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'affiliates-manager/" title="Affiliates Manager">Affiliates Manager</a> | <a href="'.$cms_root.'affiliates-manager/manage-affiliate-category-content.php?affCatID='.$affCatID.'" title="Manage Affiliate Category">Manage Affiliate Category</a> | <span class="current">Add Affiliate Link</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Affiliate Link</h1>
        <div class="intro">
        	<p>This is the <b>Add Affiliate Link</b> page. This page will allow you to add a new Affiliate Link to the current category (<?php echo $affiliatesManager->getCategoryInfo($affCatID, 'affCatName');?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Affiliate Link</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new Affiliate Link. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Affiliate Link</b> to add the new Affiliate Link.
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
                    	<input type="hidden" name="affCatID" value="<?php echo $affCatID; ?>"/>
                    	<div class="module-form-titles"><span class="required">*</span> Affiliate Title:</div>
						<input type="text" name="affiliate-title" placeholder="Affiliate Title" value="<?php if($_POST['affiliate-title'] != ''){echo $_POST['affiliate-title'];}?>" maxlength="150" />
                        <i>The title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Affiliate Type:</div>
						<input type="text" name="affiliate-type" placeholder="Affiliate Type" value="<?php if($_POST['affiliate-type'] != ''){echo $_POST['affiliate-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles">Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-form-titles"><span class="required">*</span> Affiliate Link:</div>
						<input type="text" name="affiliate-link" placeholder="Affiliate Link" value="<?php if($_POST['affiliate-link'] != ''){echo $_POST['affiliate-link'];}?>" />
                        <i>The affiliate link has to be a valid URL.</i>
                    </div>

            </div>
            <!-- END PARAGRAPH HOLDER-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Affiliate Image</div>
                	<p>
                        An image can be linked to the affiliate link by completing the fields below, please note that when an image is uploaded
                        you will be required to crop the image after the affiliate link has been uploaded. Once an image is selected the image
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
				<input type="submit" class="module-form-submit" name="add_affiliate_link" title="Add Affiliate Link" value="Add Affiliate Link" onclick="pleasewait()" />
			</form>
            </div>
            <!-- END IMAGE HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/affiliates-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
