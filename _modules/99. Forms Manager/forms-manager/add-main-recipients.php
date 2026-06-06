<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$colorbox = 1;
$pageTitle = 'Add Main Recipient';

//GET URL VARIABLE
if(isset($_POST['formID'])){$formID = $_POST['formID'];}else{$formID = $_GET['formID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($formID != ''){
	//CHECK formID INSIDE DATABASE
	if($formsManager->checkFormDatabase($formID) == 'not found'){
		header("Location:".$cms_root."forms-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."forms-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <a href="'.$cms_root.'forms-manager/manage-main-recipients.php?formID='.$formID.'" title="Manage Main Recipients">Manage Main Recipients</a> > <span class="current">Add Main Recipient</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Main Recipient</h1>
        <div class="intro">
        	<p>This is the <b>Add Main Recipient</b> page. This page will allow you to add a new main recipient to the form.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN TOPIC HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Main Recipient</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new main recipient. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Main Recipient</b> to add the new main recipient.
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
                    	<div class="module-form-titles"><span class="required">*</span> Main Recipient Name:</div>
						<input type="text" name="main-recipient-name" placeholder="Main Recipient Name" value="<?php if($_POST['main-recipient-name'] != ''){echo $_POST['main-recipient-name'];}?>" maxlength="150" />
                        <i>The main recipient name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Main Recipient Type:</div>
						<input type="text" name="main-recipient-type" placeholder="Main Recipient Type" value="<?php if($_POST['main-recipient-type'] != ''){echo $_POST['main-recipient-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Main Recipient Email:</div>
						<input type="text" name="main-recipient-email" placeholder="Main Recipient Email" value="<?php if($_POST['main-recipient-email'] != ''){echo $_POST['main-recipient-email'];}?>" />
                        <i>Please supply the email address of the new main recipient</i>
                    </div>
					<input type="submit" class="module-form-submit" name="add_main_recipient" title="Save Changes" value="Save Changes" onclick="pleasewait()" />
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
