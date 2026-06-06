<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Edit Work Place';

//GET URL VARIABLE
if(isset($_POST['skill_id'])){$skill_id = $_POST['skill_id'];}else{$skill_id = $_GET['skill_id'];}
if(isset($_POST['about_id'])){$about_id = $_POST['about_id'];}else{$about_id = $_GET['about_id'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($skill_id != '' && $about_id != ''){
	//CHECK $skill_id AND $about_id INSIDE DATABASE
	if($employmentHistoryManager->checkSkillContentDatabase($skill_id, $about_id) == 'not found'){
		header("Location:".$cms_root."employment-history-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."employment-history-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <a href="'.$cms_root.'employment-history-manager/manage-skills.php" title="Manage Skills">Manage Skills</a> | <span class="current">Edit Skill</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Skill - <?php echo $employmentHistoryManager->getSkillInfo($skill_id, 'skill_name');?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Skill</b> page. This page will allow you to edit the current Skill (<?php echo $employmentHistoryManager->getSkillInfo($skill_id, 'skill_name');?>.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Skill</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current skill. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Skill</b> to edit the current skill.
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
                    	<input type="hidden" name="skill_id" value="<?php echo $skill_id; ?>"/>
                        <input type="hidden" name="about_id" value="<?php echo $about_id; ?>"/>

						<div class="module-form-titles"><span class="required">*</span> Name of Skill:</div>
						<input type="text" name="skill-title" placeholder="Name of the skill you acquired" value="<?php if($_POST['skill-title'] != ''){echo $_POST['skill-title'];}else{echo $employmentHistoryManager->getSkillInfo($skill_id, 'skill_name');}?>" maxlength="250" />
                        <i>The Place of Work has a maximum of 250 characters.</i>

						<span class="hidden"><div class="module-form-titles">Skill Type:</div>
						<input type="text" name="skill-type" placeholder="Skill Type" value="<?php if($_POST['skill-type'] != ''){echo $_POST['skill-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Skill Level:</div>
						<input type="text" name="skill-level" placeholder="How good are you at this skill?" value="<?php if($_POST['skill-level'] != ''){echo $_POST['skill-level'];}else{echo $employmentHistoryManager->getSkillInfo($skill_id, 'percentage');}?>" />
                        <i>The Skill Level has to consist only out of numbers between 0 to 100.</i>

                        <div class="module-form-titles">Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $employmentHistoryManager->getSkillInfo($skill_id, 'description');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<span class="hidden"><div class="module-form-titles">Where did you acquired this skill:</div>
						<input type="text" name="skill-location" placeholder="Where did you acquired this skill" value="<?php if($_POST['skill-location'] != ''){echo $_POST['skill-location'];}?>" />
                        <i>The Where did you acquired this skill has a maximum of 150 characters.</i></span>

                    </div>
					<input type="submit" class="module-form-submit" name="edit_skill" title="Edit Skill" value="Edit Skill" onclick="pleasewait()"/>
                </form>

            </div>
            <!-- END PARAGRAPH HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Skill Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Skill</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $employmentHistoryManager->getUsersName($employmentHistoryManager->getSkillInfo($skill_id, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($employmentHistoryManager->getSkillInfo($skill_id, 'modifiedBy') != 0){
									echo $employmentHistoryManager->getUsersName($employmentHistoryManager->getSkillInfo($skill_id, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($employmentHistoryManager->getSkillInfo($skill_id, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($employmentHistoryManager->getSkillInfo($skill_id, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($employmentHistoryManager->getSkillInfo($skill_id, 'modifiedDate')));
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
                        	<?php echo $employmentHistoryManager->getSkillInfo($skill_id, 'modifiedNumber');?>
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
