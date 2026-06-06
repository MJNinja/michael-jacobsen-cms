<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$colorbox = 1;
$pageTitle = 'Add Topic Recipient';

//GET URL VARIABLE
if(isset($_POST['formID'])){$formID = $_POST['formID'];}else{$formID = $_GET['formID'];}
if(isset($_POST['topicID'])){$topicID = $_POST['topicID'];}else{$topicID = $_GET['topicID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($formID != '' && $topicID != ''){
	//CHECK formID INSIDE DATABASE
	if($formsManager->checkTopicDatabase($formID, $topicID) == 'not found'){
		header("Location:".$cms_root."forms-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."forms-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <a href="'.$cms_root.'forms-manager/manage-topic-recipient.php?topicID='.$topicID.'&formID='.$formID.'" title="Manage Topic Recipients">Manage Topic Recipients</a> > <span class="current">Add Topic Recipient</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Topic Recipient</h1>
        <div class="intro">
        	<p>This is the <b>Add Topic Recipient</b> page. This page will allow you to add a new topic recipient to the form.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN TOPIC HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Topic Recipient</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new topic recipient. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Topic Recipient</b> to add the new topic recipient.
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
						<input type="hidden" name="formID" value="<?php echo $formID; ?>">
						<input type="hidden" name="topicID" value="<?php echo $topicID; ?>">
                    	<div class="module-form-titles"><span class="required">*</span> Topic Recipient Name:</div>
						<input type="text" name="topic-recipient-name" placeholder="Topic Recipient Name" value="<?php if($_POST['topic-recipient-name'] != ''){echo $_POST['topic-recipient-name'];}?>" maxlength="150" />
                        <i>The topic recipient name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Topic Recipient Type:</div>
						<input type="text" name="topic-recipient-type" placeholder="Topic Recipient Type" value="<?php if($_POST['topic-recipient-type'] != ''){echo $_POST['topic-recipient-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Topic Recipient Email:</div>
						<input type="text" name="topic-recipient-email" placeholder="Topic Recipient Email" value="<?php if($_POST['topic-recipient-email'] != ''){echo $_POST['topic-recipient-email'];}?>" />
                        <i>Please supply the email address of the new topic recipient</i>
                    </div>
					<input type="submit" class="module-form-submit" name="add_topic_recipient" title="Save Changes" value="Save Changes" onclick="pleasewait()" />
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
