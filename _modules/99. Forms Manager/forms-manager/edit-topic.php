<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$colorbox = 1;
$pageTitle = 'Edit Topic';

//GET URL VARIABLE
if(isset($_POST['topicID'])){$topicID = $_POST['topicID'];}else{$topicID = $_GET['topicID'];}
if(isset($_POST['formID'])){$formID = $_POST['formID'];}else{$formID = $_GET['formID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($topicID != '' && $formID != ''){
	//CHECK topicID AND formID INSIDE DATABASE
	if($formsManager->checkTopicDatabase($formID, $topicID) == 'not found'){
		header("Location:".$cms_root."forms-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."forms-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <span class="current">Edit Topic</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Topic - <?php echo $formsManager->getTopicInfo($topicID, 'topicName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Topic</b> page. This page will allow you to edit the current topic (<?php echo $formsManager->getTopicInfo($topicID, 'topicName'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Topic</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current topic. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Topic</b> to edit the current topic.
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
                    	<input type="hidden" name="topicID" value="<?php echo $topicID; ?>"/>
                        <input type="hidden" name="formID" value="<?php echo $formID;?>"/>
						<input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $formsManager->getTopicInfo($topicID, 'modifiedNumber')+1;?>"/>

                    	<div class="module-form-titles"><span class="required">*</span> Topic Name:</div>
						<input type="text" name="topic-name" placeholder="Topic Name" value="<?php if($_POST['topic-name'] != ''){echo $_POST['topic-name'];}else{echo $formsManager->getTopicInfo($topicID, 'topicName');}?>" maxlength="150" />
                        <i>The topic name has a maximum of 150 characters.</i>

						<span class="hidden"><div class="module-form-titles">Topic Type:</div>
						<input type="text" name="topic-type" placeholder="Topic Type" value="<?php if($_POST['topic-type'] != ''){echo $_POST['topic-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Main Form:</div>
						<select name="topic-main-form">
							<option value="0">-- Select Main Form --</option>
							<?php
							if($_POST['topic-main-form'] != ''){
								$mainForm = $_POST['topic-main-form'];
							}else{
								$mainForm = $formsManager->getTopicInfo($topicID, 'formID');
							}

							echo $formsManager->getMainForms($mainForm);
							?>
						</select>
                        <i>Please select the main form the topic should appear under.</i>

                    </div>
					<input type="submit" class="module-form-submit" name="edit_topic" title="Save Changes" value="Save Changes" onclick="pleasewait()"/>
                </form>
            </div>
            <!-- END PARAGRAPH HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Topic Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Topic</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $formsManager->getUsersName($formsManager->getTopicInfo($topicID, 'createdBy'));?>
                       </td>
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
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($formsManager->getTopicInfo($topicID, 'createdDate')));?>
                        </td>
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
                      </tr>
                      <tr>
                        <td></td>
                        <td>
                        	<div class="edit-information-table-label"><b>No. of Times Modified:</b></div>
                        	<?php echo $formsManager->getTopicInfo($topicID, 'modifiedNumber');?>
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
