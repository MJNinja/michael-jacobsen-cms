<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$sequence = 1;
$sequenceTable = 'portfolio_content';
$sequenceMainID = 'portfolioContentID';
$pageTitle = 'Manage Website Content';

//GET URL VARIABLE
if(isset($_POST['portfolioID'])){$portfolioID = $_POST['portfolioID'];}else{$portfolioID = $_GET['portfolioID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//REDIRECT PAGE
if($portfolioID != ''){
	//CHECK $portfolioID INSIDE DATABASE
	if($portfolioManager->checkWebsiteDatabase($portfolioID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."portfolio-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'portfolio-manager/" title="Portfolio Manager">Portfolio Manager</a> | <span class="current">Manage Website Content</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Website Content - <?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Website Content</b> page. This page will allow you to add content to the current website (<?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?>).</p>
            <p>To add a new gallery simply click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Website Content Architecture</div>
                <div class="module-links">

				<!--<?php if($portfolioManager->getGalleryTotal($portfolioID) == 0){?>
				<a href="<?php echo $cms_root; ?>portfolio-manager/add-gallery.php?portfolioID=<?php echo $portfolioID; ?>" title="Add Gallery">Add Gallery</a>
				<?php }?>-->

				<a href="<?php echo $cms_root; ?>portfolio-manager/add-paragraph.php?portfolioID=<?php echo $portfolioID; ?>" title="Add Paragraph">Add Paragraph</a>

				</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current website.
                </div>

                <?php echo $portfolioManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">

                    <?php echo $portfolioManager->websiteContentArchitecture($cms_root, $web_root, $portfolioID);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/portfolio-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
