<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 1;
$colorbox = 1;
$assignRole = 1;
$generatePassword = 1;
$useMainDB = 1;
$pageTitle = 'Add CMS User';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.UsersManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'cms-users-manager/" title="CMS User Manager">CMS User Manager</a> | <span class="current">Add CMS User</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add User Manager</h1>
        <div class="intro">
        	<p>This is the <b>Add CMS User</b> page. This page will allow you to add a new User to the CMS.</p>
			<p>Once the new user is added, an email will be send to him/her containing his/her CMS User Account details.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add CMS User</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new CMS User. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add CMS User</b> to add the new User.
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
                    	<div class="module-form-titles"><span class="required">*</span> Name: </div>
						<input type="text" name="user-name" placeholder="Name" value="<?php if($_POST['user-name'] != ''){echo $_POST['user-name'];}?>" />
                        <i>Please supply the first name of the new user</i>

                        <div class="module-form-titles"><span class="required">*</span> Surname: </div>
                        <input type="text" name="user-surname" placeholder="Surname" value="<?php if($_POST['user-surname'] != ''){echo $_POST['user-surname'];}?>" />
                        <i>Please supply the last name of the new user</i>

                        <div class="module-form-titles"><span class="required">*</span> Email: </div>
                        <input type="text" name="user-email" placeholder="Email" value="<?php if($_POST['user-email'] != ''){echo $_POST['user-email'];}?>" />
                        <i>Please supply the email address of the new user</i>

                        <span class="hidden"><div class="module-form-titles">Email Re-Type: </div>
                        <input type="text" name="user-email-re-type" placeholder="Email Re-Type" value="<?php if($_POST['user-email-re-type'] != ''){echo $_POST['user-email-re-type'];}?>" />
                        <i>Please re-type the email address of the new user</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Password: </div>
						<div class="password-field-holder">
							<input type="password" name="user-password" placeholder="Password" value="<?php if($_POST['user-password'] != ''){echo $_POST['user-password'];}?>" />
						</div>
						<div class="generate-password-holder">
							<a href="#" title="Generate Password" class="generate-password" id="userPassword">Generate Password</a>
						</div>
						<div class="clear"></div>
                        <i>Please supply a password for the new user or create a password by clicking on the "Generate Password" buttton.</i>


                        <div class="module-form-titles"><span class="required">*</span> Contact Number: </div>
                        <input type="text" name="user-contact-number" placeholder="Contact Number" value="<?php if($_POST['user-contact-number'] != ''){echo $_POST['user-contact-number'];}?>" />
                        <i>Please supply the contact number of the new user</i>

                        <span class="hidden"><div class="module-form-titles">Contact Number 2: </div>
                        <input type="text" name="user-contact-number-2" placeholder="Contact Number" value="<?php if($_POST['user-contact-number-2'] != ''){echo $_POST['user-contact-number-2'];}?>" />
                        <i>Please supply another contact number of the new user</i></span>

						<div class="module-form-titles"><span class="required">*</span> User Type:</div>
						<select name="user-type" class="checkChange">
							<option value="0">-- Select the User Type --</option>
							<?php echo $userManager->getUserRoles($_POST['user-type']); ?>
						</select>
						<i>Please select the type of user this is going to be.</i>

						<div id="userModulesSelect" <?php if($_POST['user-type'] == 1 || $_POST['user-type'] == 0){echo 'class="hidden"';}?>>
							<div class="module-form-titles"><span class="required">*</span> Assign Modules:</div>
							<?php echo $userManager->getUserRoleModules($_POST['userSelectedRoles'], $cms_root);?>
							<i>Please select the modules you want to assign to this user.</i>
						</div>

                    </div>
                    <input type="submit" class="module-form-submit" name="add_cms_user" title="Add CMS User" value="Add CMS User" />
                </form>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<div class="module-stats-label">
                    	Active Users:
                        <b><?php echo $userManager->getTotalActives();?></b>
                    </div>
                    <div class="module-stats-label">
                    	Removed Users:
                        <b><?php echo $userManager->getTotalDeletes();?></b>
                    </div>
                    <div class="module-stats-label">
                    	Total Users:
                        <b><?php echo $userManager->getTotalUsers();?></b>
                    </div>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->



<?php
require_once("../inc/footer-inc.php");
?>
