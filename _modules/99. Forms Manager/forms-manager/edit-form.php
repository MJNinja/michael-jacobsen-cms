<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$colorbox = 1;
$pageTitle = 'Edit From';

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <span class="current">Edit Form</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Form - <?php echo $formsManager->getFormInfo($formID, 'formName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Form</b> page. This page will allow you to edit the current form (<?php echo $formsManager->getFormInfo($formID, 'formName'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Form</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current form. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Form</b> to edit the current form.
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
                        <input type="hidden" name="formID" value="<?php echo $formID;?>"/>
						<input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $formsManager->getTopicInfo($topicID, 'modifiedNumber')+1;?>"/>

                    	<div class="module-form-titles"><span class="required">*</span> Form Name:</div>
						<input type="text" name="form-name" placeholder="Form Name" value="<?php if($_POST['form-name'] != ''){echo $_POST['form-name'];}else{echo $formsManager->getFormInfo($formID, 'formName');}?>" maxlength="150" />
                        <i>The form name has a maximum of 150 characters.</i>

						<span class="hidden"><div class="module-form-titles">Form Type:</div>
						<input type="text" name="form-type" placeholder="Form Type" value="<?php if($_POST['form-type'] != ''){echo $_POST['form-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                    </div>
					<input type="submit" class="module-form-submit" name="edit_form" title="Save Changes" value="Save Changes" onclick="pleasewait()"/>
                </form>
            </div>
            <!-- END PARAGRAPH HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Form Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Form</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
							<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($formsManager->getTopicInfo($topicID, 'modifiedBy') != 0){
									echo $formsManager->getUsersName($formsManager->getTopicInfo($topicID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                       </td>
                        <td width="50%"></td>
                      </tr>
                      <tr>
                        <td>
							<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($formsManager->getTopicInfo($topicID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($formsManager->getTopicInfo($topicID, 'modifiedDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>
							<div class="edit-information-table-label"><b>No. of Times Modified:</b></div>
                        	<?php echo $formsManager->getTopicInfo($topicID, 'modifiedNumber');?>
						</td>
                        <td></td>
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
