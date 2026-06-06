<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$colorbox = 1;
$pageTitle = 'Add Topic';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <span class="current">Add Topic</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Topic</h1>
        <div class="intro">
        	<p>This is the <b>Add Topic</b> page. This page will allow you to add a new topic to a form.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN TOPIC HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Topic</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new topic. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Topic</b> to add the new topic.
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
                    	<div class="module-form-titles"><span class="required">*</span> Topic Name:</div>
						<input type="text" name="topic-name" placeholder="Topic Name" value="<?php if($_POST['topic-name'] != ''){echo $_POST['topic-name'];}?>" maxlength="150" />
                        <i>The topic name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Topic Type:</div>
						<input type="text" name="topic-type" placeholder="Topic Type" value="<?php if($_POST['topic-type'] != ''){echo $_POST['topic-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Main Form:</div>
						<select name="topic-main-form">
							<option value="0">-- Select Main Form --</option>
							<?php echo $formsManager->getMainForms($_POST['topic-main-form']); ?>
						</select>
                        <i>Please select the main form the topic should appear under.</i>
                    </div>
					<input type="submit" class="module-form-submit" name="add_topic" title="Save Changes" value="Save Changes" onclick="pleasewait()" />
                </form>
            </div>
            <!-- END TOPIC HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/form-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
