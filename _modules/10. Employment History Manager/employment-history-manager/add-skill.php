<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$pageTitle = 'Add Skill';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <a href="'.$cms_root.'employment-history-manager/manage-skills.php" title="Manage Skills">Manage Skills</a> | <span class="current">Add Skill</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Skill</h1>
        <div class="intro">
        	<p>This is the <b>Add Skill</b> page. This page will allow you to add a new skill you acquired.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Skill</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new Skill. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Skill</b> to add the new skill.
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

                    	<div class="module-form-titles"><span class="required">*</span> Name of Skill:</div>
						<input type="text" name="skill-title" placeholder="Name of the skill you acquired" value="<?php if($_POST['skill-title'] != ''){echo $_POST['skill-title'];}?>" maxlength="250" />
                        <i>The Name of Skill has a maximum of 250 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Skill Type:</div>
						<input type="text" name="skill-type" placeholder="Skill Type" value="<?php if($_POST['skill-type'] != ''){echo $_POST['skill-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Skill Level:</div>
						<input type="text" name="skill-level" placeholder="How good are you at this skill?" value="<?php if($_POST['skill-level'] != ''){echo $_POST['skill-level'];}?>" />
                        <i>The Skill Level has to consist only out of numbers between 0 to 100.</i>

                        <div class="module-form-titles">Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<span class="hidden"><div class="module-form-titles">Where did you acquired this skill:</div>
						<input type="text" name="skill-location" placeholder="Where did you acquired this skill" value="<?php if($_POST['skill-location'] != ''){echo $_POST['skill-location'];}?>" />
                        <i>The Where did you acquired this skill has a maximum of 150 characters.</i></span>

                    </div>
					<input type="submit" class="module-form-submit" name="add_skill" title="Add Skill" value="Add Skill" onclick="pleasewait()" />
                </form>

            </div>
            <!-- END PARAGRAPH HOLDER-->

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
