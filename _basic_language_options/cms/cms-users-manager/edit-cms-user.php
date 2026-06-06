<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 1;
$colorbox = 1;
$generatePassword = 1;
$useMainDB = 1;
$pageTitle = 'Edit CMS User';

//GET URL VARIABLE
if(isset($_POST['userID'])){$userID = $_POST['userID'];}else{$userID = $_GET['userID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.UsersManager.php");

//REDIRECT PAGE
if($userID != ''){
	//CHECK userID INSIDE DATABASE
	if($userManager->checkUserDatabase($userID) == 'not found'){
		header("Location:".$cms_root."cms-users-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."cms-users-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'cms-users-manager/" title="CMS User Manager">CMS User Manager</a> | <span class="current">Edit CMS User</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit User Manager</h1>
        <div class="intro">
        	<p>This is the <b>Edit CMS User</b> page. This allows you to edit an exisiting CMS User.</p>
            <p>If you change the information of the user, an email will be send to you containing the changes made.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit CMS User</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out the fields you wish to change. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit CMS User</b> to save the changes.
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
						<div class="password-field-holder">
							<input type="password" name="user-password" placeholder="Password" value="<?php if($_POST['user-password'] != ''){echo $_POST['user-password'];}?>" />
						</div>
						<div class="generate-password-holder">
							<a href="#" title="Generate Password" class="generate-password" id="userPassword">Generate Password</a>
						</div>
						<div class="clear"></div>
                        <i>Please supply a password for the new user or create a password by clicking on the "Generate Password" buttton.</i>

                        <div class="module-form-titles"><span class="required">*</span> Contact Number: </div>
                        <input type="text" name="user-contact-number" placeholder="Contact Number" value="<?php if($_POST['user-contact-number'] != ''){echo $_POST['user-contact-number'];}else{echo $userManager->getUserInfo($userID, 'contactNumber');}?>" />
                        <i>Please supply the contact number of the new user</i>

                        <span class="hidden"><div class="module-form-titles">Contact Number 2: </div>
                        <input type="text" name="user-contact-number-2" placeholder="Contact Number" value="<?php if($_POST['user-contact-number-2'] != ''){echo $_POST['user-contact-number-2'];}?>" />
                        <i>Please supply another contact number of the new user</i></span>

						<div class="module-form-titles"><span class="required">*</span> User Type:</div>
						<select name="user-type" class="checkChange">
							<option value="0">-- Select the User Type --</option>
							<?php
								if($_POST['user-type'] != ''){
									$userType	= $_POST['user-type'];
								}else{
									$userType	= $userManager->getUserInfo($userID, 'userType');
								}
								echo $userManager->getUserRoles($userType);
							?>
						</select>
						<i>Please select the type of user this is going to be.</i>

						<div id="userModulesSelect" <?php if($userType == 1 || $userType == 0){echo 'class="hidden"';}?>>
							<div class="module-form-titles"><span class="required">*</span> Assign Modules:</div>
							<?php
								if($_POST['userSelectedRoles'] != ''){
									$userSelectedRoles	= $_POST['user-type'];
								}else{
									$userModuleRights	= $userManager->getUserInfo($userID, 'userModuleRights');
									$userModuleRights	= substr($userModuleRights, 1, -1);
									$userSelectedRoles	= explode(',', $userModuleRights);
								}
								echo $userManager->getUserRoleModules($userSelectedRoles, $cms_root);
							?>
							<i>Please select the modules you want to assign to this user.</i>
						</div>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_cms_user" title="Edit CMS User" value="Edit CMS User" />
                </form>

            </div>

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;CMS User Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>CMS User</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $userManager->getUsersName($userManager->getUserInfo($userID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($userManager->getUserInfo($userID, 'modifiedBy') != 0){
									echo $userManager->getUsersName($userManager->getUserInfo($userID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
							<?php
                            	if(date("j F Y",strtotime($userManager->getUserInfo($userID, 'createdDate'))) != '1 January 1970'){
									echo date("j F Y",strtotime($userManager->getUserInfo($userID, 'createdDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($userManager->getUserInfo($userID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($userManager->getUserInfo($userID, 'modifiedDate')));
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
                        	<?php echo $userManager->getUserInfo($userID, 'modifiedNumber');?>
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
