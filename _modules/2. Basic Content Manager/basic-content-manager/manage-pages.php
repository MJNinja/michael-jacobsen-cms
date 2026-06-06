<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 2;
$sequence = 1;
$sequenceTable = 'basic_pages_content';
$sequenceMainID = 'pageContentID';
$pageTitle = 'Manage Page Content';

//GET URL VARIABLE
if(isset($_POST['pageID'])){$pageID = $_POST['pageID'];}else{$pageID = $_GET['pageID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.basicContentManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($pageID != ''){
	//CHECK quoteID AND quoteCatID INSIDE DATABASE
	if($basicContentManager->checkPageIDDatabase($pageID) == 'not found'){
		header("Location:".$cms_root."basic-content-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."basic-content-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'basic-content-manager/" title="Basic Content Manager">Basic Content Manager</a> | <span class="current">Manage Page Content</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Page Content - <?php echo $basicContentManager->getPageInfo($pageID, 'pageName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Page Content</b> page. This page will allow you to add content to the current page (<?php echo $basicContentManager->getPageInfo($pageID, 'pageName'); ?>).</p>
            <p>To add a new paragraph simply click on <b>Add Paragraph</b> and to add a new gallery simply click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Page Content Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>basic-content-manager/add-gallery.php?pageID=<?php echo $pageID; ?>" title="Add Gallery">Add Gallery</a><a href="<?php echo $cms_root; ?>basic-content-manager/add-paragraph.php?pageID=<?php echo $pageID; ?>" title="Add Paragraph">Add Paragraph</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current page.
                </div>

                <?php echo $basicContentManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $basicContentManager->pageContentArchitecture($cms_root, $web_root, $pageID);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/basic-content-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
