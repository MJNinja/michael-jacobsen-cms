<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$colorbox = 1;
$paragraph_image_enlarge = 1;
$ckeditor = 1;
$pageTitle = 'Manage Personal Details';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <span class="current">Manage Personal Details</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Personal Details</h1>
        <div class="intro">
        	<p>This is the <b>Manage Personal Details</b> page. This page will allow you to manage all the content about yourself.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Manage Personal Details</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out the information about yourself as far as possible.<br />
                	Once you are done click on <b>Update Personal Details</b> to edit the information about yourself.
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
						<input type="hidden" name="oldImage" value="<?php echo $employmentHistoryManager->getPersonalInfo('personal_image');?>"/>
						<input type="hidden" name="about_id" value="<?php echo $employmentHistoryManager->getPersonalInfo('about_id');?>"/>

                    	<div class="module-form-titles">First Name:</div>
						<input type="text" name="first-name" placeholder="Your First Name" value="<?php if($_POST['first-name'] != ''){echo $_POST['first-name'];}else{echo $employmentHistoryManager->getPersonalInfo('name');}?>" maxlength="150" />
                        <i>The First Name has a maximum of 150 characters.</i>

						<div class="module-form-titles">Surname:</div>
						<input type="text" name="surname" placeholder="Your Surname" value="<?php if($_POST['surname'] != ''){echo $_POST['surname'];}else{echo $employmentHistoryManager->getPersonalInfo('surname');}?>" maxlength="150" />
                        <i>The Surname has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Nickname:</div>
						<input type="text" name="nickname" placeholder="Your Nickname" value="<?php if($_POST['nickname'] != ''){echo $_POST['nickname'];}?>" />
                        <i>The Nickname has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles">Personal Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $employmentHistoryManager->getPersonalInfo('description');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-form-titles">Occupation:</div>
						<input type="text" name="occupation" placeholder="Your Occupation" value="<?php if($_POST['occupation'] != ''){echo $_POST['occupation'];}else{echo $employmentHistoryManager->getPersonalInfo('occupation');}?>" maxlength="150" />
						<i>The Occupation has a maximum of 150 characters.</i>

						<div class="module-form-titles">Tagline:</div>
						<input type="text" name="tagline" placeholder="Your Tagline" value="<?php if($_POST['tagline'] != ''){echo $_POST['tagline'];}else{echo $employmentHistoryManager->getPersonalInfo('tag_line');}?>" maxlength="100" />
						<i>The Tagline has a maximum of 100 characters.</i>

                    </div>

            </div>
            <!-- END PARAGRAPH HOLDER-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Personal Image</div>
                	<p>
                        An image can be linked to the your information by completing the field below, please note that when an image is uploaded
                        you will be required to crop the image after the information has been uploaded.
                    </p>

                    <?php echo $employmentHistoryManager->getPersonalImage($web_root); ?>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles">Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="update_personal_details" title="Update Personal Details" value="Update Personal Details" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/employment-history-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
