<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 4;
$pageTitle = 'Manage Affiliate Category';

//GET URL VARIABLE
if(isset($_POST['affCatID'])){$affCatID = $_POST['affCatID'];}else{$affCatID = $_GET['affCatID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.affiliatesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($affCatID != ''){
	//CHECK affCatID INSIDE DATABASE
	if($affiliatesManager->checkCategoryDatabase($affCatID) == 'not found'){
		header("Location:".$cms_root."affiliates-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."affiliates-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'affiliates-manager/" title="Blog Manager">Affiliates Manager</a> | <span class="current">Manage Affiliate Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Affiliate Category - <?php echo $affiliatesManager->getCategoryInfo($affCatID, 'affCatName');?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Affiliate Category</b> page. This page will allow you to add new Affiliate Links to the current category (<?php echo $affiliatesManager->getCategoryInfo($affCatID, 'affCatName');?>).</p>
            <p>To add a new Affiliate Link simply click on <b>Add Affiliate Link</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Affiliate Link Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>affiliates-manager/add-affiliate.php?affCatID=<?php echo $affCatID; ?>" title="Add Blog Post">Add Affiliate Link</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Affiliate Link added to the current category.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active Affiliate Links.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed Affiliate Links.</div>
                    </div>
                </div>

                <?php echo $affiliatesManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                <div class="module-architecture-table-heading">Active Affiliate Links</div>
                <table width="100%" class="module-architecture-table">
	                <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="40%">Affiliate Link Name</td>
                        <td width="14%" align="center">Modify</td>
                        <td width="13%" align="center">Remove</td>
                    </tr>

                    <?php echo $affiliatesManager->affiliateLinkArchitecture($cms_root, $affCatID);?>

                </table>
                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/affiliates-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
