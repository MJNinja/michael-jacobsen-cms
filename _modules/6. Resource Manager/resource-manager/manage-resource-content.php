<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 6;
$sequence = 1;
$sequenceTable = 'resource_content';
$sequenceMainID = 'resourceContentID';
$pageTitle = 'Manage Resource';

//GET URL VARIABLE
if(isset($_POST['resourceID'])){$resourceID = $_POST['$resourceID'];}else{$resourceID = $_GET['resourceID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.resourceManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($resourceID != ''){
	//CHECK resourceID INSIDE DATABASE
	if($resourceManager->checkResourceDatabase($resourceID) == 'not found'){
		header("Location:".$cms_root."resource-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."resource-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'resource-manager/" title="Resource Manager">Resource Manager</a> | <span class="current">Manage Resource</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Resource - <?php echo $resourceManager->getResourceInfo($resourceID, 'resourceName');?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Resource</b> page. This page will allow you to add paragraphs to the current resource (<?php echo $resourceManager->getResourceInfo($resourceID, 'resourceName');?>).</p>
            <p>To add a new paragraph simply click on <b>Add Paragraph</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Resource Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>resource-manager/add-paragraph.php?resourceID=<?php echo $resourceID; ?>" title="Add Paragraph">Add Paragraph</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the paragraphs added to the current resource.
                </div>

                <?php echo $resourceManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $resourceManager->resourceContentArchitecture($cms_root, $web_root, $resourceID);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/resource-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
