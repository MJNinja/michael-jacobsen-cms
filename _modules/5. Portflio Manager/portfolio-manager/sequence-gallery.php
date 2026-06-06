<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$gallery_upload = 1;
$sequence = 1;
$sequenceTable = 'portfolio_gallery_content';
$sequenceMainID = 'portfolioGalleryContentID';
$pageTitle = 'Sequence Gallery';

//GET URL VARIABLE
if(isset($_POST['portfolioID'])){$portfolioID = $_POST['portfolioID'];}else{$portfolioID = $_GET['portfolioID'];}
if(isset($_POST['portfolioGalleryID'])){$portfolioGalleryID = $_POST['portfolioGalleryID'];}else{$portfolioGalleryID = $_GET['portfolioGalleryID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//REDIRECT PAGE
if($portfolioID != '' && $portfolioGalleryID != ''){
	//CHECK $pageID INSIDE DATABASE
	if($portfolioManager->checkPortfolioIDDatabase($portfolioID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}

    //CHECK $basicPagesGalleryID INSIDE DATABASE
    if($portfolioManager->checkPortfolioGalleryDatabase($portfolioGalleryID) == 'not found'){
		header("Location:".$cms_root."portfolio-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."portfolio-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'portfolio-manager/" title="Portfolio Manager">Portfolio Manager</a> | <a href="'.$cms_root.'portfolio-manager/manage-website-content.php?portfolioID='.$portfolioID.'" title="Manage Website Content">Manage Website Content</a> | <span class="current">Sequence Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Sequence Gallery - <?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Sequence Gallery</b> page. This page will allow you to sequnce the current gallery (<?php echo $portfolioManager->getWebsiteInfo($portfolioID, 'websiteName'); ?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN GALLERY IMAGE PREVIEW -->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Sequence Gallery</div>
                <div class="clear"></div>
                <div class="module-holder-intro"></div>

                <div class="module-form-holder">
                    <div class="module-form-titles">Current Gallery Image(s)</div>
                    <p>
                        Below are all the images currently assigned to this gallery. To change the sequence of the gallery simply click and hold your left mouse button, and drag the images accordingly.
                    </p>

					<div id="sortable">
                    	<?php echo $portfolioManager->getPortfolioGalleryImagesSequencing($portfolioGalleryID, $web_root);?>
					</div>

                </div>
            </div>
            <!-- END GALLERY IMAGE HOLDER-->

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
