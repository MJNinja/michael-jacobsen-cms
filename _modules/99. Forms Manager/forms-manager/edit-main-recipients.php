<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$colorbox = 1;
$pageTitle = 'Edit Main Recipient';

//GET URL VARIABLE
if(isset($_POST['recipientID'])){$recipientID = $_POST['recipientID'];}else{$recipientID = $_GET['recipientID'];}
if(isset($_POST['formID'])){$formID = $_POST['formID'];}else{$formID = $_GET['formID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($recipientID != '' && $formID != ''){
	//CHECK recipientID AND formID INSIDE DATABASE
	if($formsManager->checkMainRecipientDatabase($recipientID, $formID) == 'not found'){
		header("Location:".$cms_root."forms-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."forms-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <a href="'.$cms_root.'forms-manager/manage-main-recipients.php?formID='.$formID.'" title="Manage Main Recipients">Manage Main Recipients</a> > <span class="current">Edit Main Recipients</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Main Recipient - <?php echo $formsManager->getRecipientInfo($recipientID, 'fullname'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Main Recipient</b> page. This page will allow you to edit the current main recipient (<?php echo $formsManager->getRecipientInfo($recipientID, 'fullname'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Main Recipient</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current main recipient. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Main Recipient</b> to edit the current main recipient.
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
                    	<input type="hidden" name="recipientID" value="<?php echo $recipientID; ?>"/>
                        <input type="hidden" name="formID" value="<?php echo $formID;?>"/>
						<input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $formsManager->getRecipientInfo($recipientID, 'modifiedNumber')+1;?>"/>

						<div class="module-form-titles"><span class="required">*</span> Main Recipient Name:</div>
						<input type="text" name="main-recipient-name" placeholder="Main Recipient Name" value="<?php if($_POST['main-recipient-name'] != ''){echo $_POST['main-recipient-name'];}else{echo $formsManager->getRecipientInfo($recipientID, 'fullname');}?>" maxlength="150" />
                        <i>The main recipient name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Main Recipient Type:</div>
						<input type="text" name="main-recipient-type" placeholder="Main Recipient Type" value="<?php if($_POST['main-recipient-type'] != ''){echo $_POST['main-recipient-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Main Recipient Email:</div>
						<input type="text" name="main-recipient-email" placeholder="Main Recipient Email" value="<?php if($_POST['main-recipient-email'] != ''){echo $_POST['main-recipient-email'];}else{echo $formsManager->getRecipientInfo($recipientID, 'email');}?>" />
                        <i>Please supply the email address of the new main recipient</i>

                    </div>
					<input type="submit" class="module-form-submit" name="edit_main_recipient" title="Save Changes" value="Save Changes" onclick="pleasewait()"/>
                </form>
            </div>
            <!-- END PARAGRAPH HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Main Recipient Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Main Recipient</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $formsManager->getUsersName($formsManager->getRecipientInfo($recipientID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($formsManager->getRecipientInfo($recipientID, 'modifiedBy') != 0){
									echo $formsManager->getUsersName($formsManager->getRecipientInfo($recipientID, 'modifiedBy'));
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
                            	if(date("j F Y",strtotime($formsManager->getRecipientInfo($recipientID, 'createdDate'))) != '1 January 1970'){
									echo date("j F Y",strtotime($formsManager->getRecipientInfo($recipientID, 'createdDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($formsManager->getRecipientInfo($recipientID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($formsManager->getRecipientInfo($recipientID, 'modifiedDate')));
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
                        	<?php echo $formsManager->getRecipientInfo($recipientID, 'modifiedNumber');?>
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
