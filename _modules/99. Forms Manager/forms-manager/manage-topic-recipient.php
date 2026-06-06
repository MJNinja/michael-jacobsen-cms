<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$pageTitle = 'Manage Topic Recipients';

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <span class="current">Manage Topic Recipients</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Topic Recipients - <?php echo $formsManager->getTopicInfo($topicID, 'topicName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Topic Recipients</b> page. This page will allow you to add new topic recipients to the current form (<?php echo $formsManager->getTopicInfo($topicID, 'topicName'); ?>).</p>
            <p>To add a new Topic Recipient simply click on <b>Add Topic Recipient</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Topic Recipients Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>forms-manager/add-topic-recipients.php?formID=<?php echo $formID; ?>&topicID=<?php echo $topicID; ?>" title="Add Topic Recipient">Add Topic Recipient</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Topic Recipient added to the current topic.
                </div>

                <?php echo $formsManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                <table width="100%" class="module-architecture-table">
	                <tr class="module-architecture-header">
                        <td width="70%">Topic Recipient Name</td>
                        <td width="15%" align="center">Modify</td>
                        <td width="15%" align="center">Remove</td>
                    </tr>

                    <?php echo $formsManager->topicRecipientsArchitecture($cms_root, $topicID, $formID);?>

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
