<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = -1;
$profile = 1;
$colorbox = 1;
$pageTitle = 'Edit CMS User';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.UsersManager.php");

//SET USER ID
$userID = $_SESSION['cmsUser'];

//SET PAGE TITLE
$pageTitle = $userManager->getUserInfo($userID, 'name').' '.$userManager->getUserInfo($userID, 'surname')."'s Profile";

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Profile</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Profile Page</h1>
        <div class="intro">
        	<p>This is the <b>Profile</b> page. This allows you to edit your CMS User Details. </p>
            <p>If you change the information of the user, an email will be send to you containing the changes made.</p>
        </div>

        <div class="center-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;<?php echo $userManager->getUserInfo($userID, 'name').' '.$userManager->getUserInfo($userID, 'surname'); ?>'s Profile Details</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out the fields you wish to change. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Profile</b> to save the changes.
                </div>

				<?php echo $userManager->defineErrorMessages($_GET['message']); ?>

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
                    	<input type="hidden" name="userID" value="<?php echo $userID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $userManager->getUserInfo($userID, 'modifiedNumber')+1;?>"/>
                    	<div class="module-form-titles"><span class="required">*</span> Name: </div>
						<input type="text" name="user-name" placeholder="Name" value="<?php if($_POST['user-name'] != ''){echo $_POST['user-name'];}else{echo $userManager->getUserInfo($userID, 'name');}?>" />
                        <i>Please supply the first name of the new user</i>

                        <div class="module-form-titles"><span class="required">*</span> Surname: </div>
                        <input type="text" name="user-surname" placeholder="Surname" value="<?php if($_POST['user-surname'] != ''){echo $_POST['user-surname'];}else{echo $userManager->getUserInfo($userID, 'surname');}?>" />
                        <i>Please supply the last name of the new user</i>

                        <div class="module-form-titles"><span class="required">*</span> Email: </div>
                        <input type="text" name="user-email" placeholder="Email" value="<?php if($_POST['user-email'] != ''){echo $_POST['user-email'];}else{echo $userManager->getUserInfo($userID, 'email');}?>" />
                        <i>Please supply the email address of the new user</i>

                        <span class="hidden"><div class="module-form-titles">Email Re-Type: </div>
                        <input type="text" name="user-email-re-type" placeholder="Email Re-Type" value="<?php if($_POST['user-email-re-type'] != ''){echo $_POST['user-email-re-type'];}?>" />
                        <i>Please re-type the email address of the new user</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Password: </div>
                        <input type="password" name="user-password" placeholder="Only fill this out if you wish to change your password" value="<?php if($_POST['user-password'] != ''){echo $_POST['user-password'];}?>" />
                        <i>If you wish to change the password, fill out this field otherwise the password will stay the same.</i>

                        <div class="module-form-titles"><span class="required">*</span> Contact Number: </div>
                        <input type="text" name="user-contact-number" placeholder="Contact Number" value="<?php if($_POST['user-contact-number'] != ''){echo $_POST['user-contact-number'];}else{echo $userManager->getUserInfo($userID, 'contactNumber');}?>" />
                        <i>Please supply the contact number of the new user</i>

                        <span class="hidden"><div class="module-form-titles">Contact Number 2: </div>
                        <input type="text" name="user-contact-number-2" placeholder="Contact Number" value="<?php if($_POST['user-contact-number-2'] != ''){echo $_POST['user-contact-number-2'];}?>" />
                        <i>Please supply another contact number of the new user</i></span>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_profile" title="Edit Profile" value="Edit Profile" />
                </form>

            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
