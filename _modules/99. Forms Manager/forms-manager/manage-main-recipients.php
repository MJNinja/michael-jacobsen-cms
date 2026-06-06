<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$pageTitle = 'Manage Main Recipients';

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <span class="current">Manage Main Recipients</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Main Recipients - <?php echo $formsManager->getFormInfo($formID, 'formName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Main Recipients</b> page. This page will allow you to add new main recipients to the current form (<?php echo $formsManager->getFormInfo($formID, 'formName'); ?>).</p>
            <p>To add a new Main Recipient simply click on <b>Add Main Recipient</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Main Recipients Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>forms-manager/add-main-recipients.php?formID=<?php echo $formID; ?>" title="Add Main Recipient">Add Main Recipient</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Main Recipient added to the current form.
                </div>

                <?php echo $formsManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                <table width="100%" class="module-architecture-table">
	                <tr class="module-architecture-header">
                        <td width="70%">Main Recipient Name</td>
                        <td width="15%" align="center">Modify</td>
                        <td width="15%" align="center">Remove</td>
                    </tr>

                    <?php echo $formsManager->mainRecipientsArchitecture($cms_root, $formID);?>

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
