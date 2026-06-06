<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 13;
$colorbox = 1;
$ckeditor = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Staff Member';

//GET URL VARIABLE
if(isset($_POST['staffID'])){$staffID = $_POST['staffID'];}else{$staffID = $_GET['staffID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.staffManager.php");

//REDIRECT PAGE
if($staffID != ''){
	//CHECK $staffID INSIDE DATABASE
	if($staffManager->checkStaffMemberDatabase($staffID) == 'not found'){
		header("Location:".$cms_root."staff-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."staff-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'staff-manager/" title="Staff Manager">Staff Manager</a> | <span class="current">Edit Staff Member</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");

?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Staff Member</h1>
        <div class="intro">
        	<p>This is the <b>Edit Staff Member</b> page. This page will allow you to edit the current Staff Member.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Staff Member</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the Staff Member. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Staff Member</b> to edit the Staff Member.
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
                    	<input type="hidden" name="staffID" value="<?php echo $staffID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $staffManager->getStaffInfo($staffID, 'modifiedNumber')+1;?>"/>
                        <input type="hidden" name="oldImage" value="<?php echo $staffManager->getStaffInfo($staffID, 'staffImage');?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Staff Name:</div>
						<input type="text" name="staff-name" placeholder="Staff Name" value="<?php if($_POST['staff-name'] != ''){echo $_POST['staff-name'];}else{echo $staffManager->getStaffInfo($staffID, 'staffName');}?>" maxlength="150" />
                        <i>The staff name has a maximum of 150 characters.</i>

						<div class="module-form-titles"><span class="required">*</span> Staff Surname:</div>
						<input type="text" name="staff-surname" placeholder="Staff Surname" value="<?php if($_POST['staff-surname'] != ''){echo $_POST['staff-surname'];}else{echo $staffManager->getStaffInfo($staffID, 'staffSurname');}?>" maxlength="150" />
                        <i>The staff surname has a maximum of 150 characters.</i>

						<div class="module-form-titles"><span class="required">*</span> Position:</div>
						<input type="text" name="staff-position" placeholder="Position" value="<?php if($_POST['staff-position'] != ''){echo $_POST['staff-position'];}else{echo $staffManager->getStaffInfo($staffID, 'staffPosition');}?>" maxlength="150" />
                        <i>The position has a maximum of 150 characters.</i>

						<div class="module-form-titles">Staff Email:</div>
						<input type="email" name="staff-email" placeholder="Staff Email" value="<?php if($_POST['staff-email'] != ''){echo $_POST['staff-email'];}else{echo $staffManager->getStaffInfo($staffID, 'staffEmail');}?>" />
                        <i>Please supply a valid email address.</i></span>

                        <span class="hidden"><div class="module-form-titles">Staff Email 2:</div>
						<input type="text" name="staff-email-2" placeholder="Staff Email 2" value="<?php if($_POST['staff-email-2'] != ''){echo $_POST['staff-email-2'];}?>" />
                        <i>Please supply a valid email address.</i></span>

						<div class="module-form-titles">Staff Contact:</div>
						<input type="text" name="staff-contact" placeholder="Staff Contact" value="<?php if($_POST['staff-contact'] != ''){echo $_POST['staff-contact'];}else{echo $staffManager->getStaffInfo($staffID, 'staffContact');}?>" />
                        <i>Please supply a valid contact number.</i></span>

						<div class="module-form-titles">Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $staffManager->getStaffInfo($staffID, 'staffDescription');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>
            </div>

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Staff Image</div>
                	<p>
                        An image can be linked to the Staff Member by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Staff Member has been uploaded.
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Staff Member".
                    </p>

                    <?php echo $staffManager->getStaffImage($staffID, $web_root); ?>

                    <div class="module-form-titles">Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $staffManager->getStaffInfo($staffID, 'staffImageTitle');}?>" maxlength="150" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
                <input type="submit" class="module-form-submit" name="edit_staff" title="Edit Staff Member" value="Edit Staff Member" onclick="pleasewait()"/>
            </form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Software Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Staff Member</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $staffManager->getUsersName($staffManager->getStaffInfo($staffID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($staffManager->getStaffInfo($staffID, 'modifiedBy') != 0){
									echo $staffManager->getUsersName($staffManager->getStaffInfo($staffID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($staffManager->getStaffInfo($staffID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($staffManager->getStaffInfo($staffID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($staffManager->getStaffInfo($staffID, 'modifiedDate')));
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
                        	<?php echo $staffManager->getStaffInfo($staffID, 'modifiedNumber');?>
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
                	<?php include_once("../inc/staff-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
