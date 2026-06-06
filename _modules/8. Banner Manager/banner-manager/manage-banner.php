<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 8;
$sequence = 1;
$sequenceTable = 'banner_images';
$sequenceMainID = 'bannerID';
$pageTitle = 'Manage Banner';

//GET URL VARIABLE
if(isset($_POST['bannerAreaID'])){$bannerAreaID = $_POST['bannerAreaID'];}else{$bannerAreaID = $_GET['bannerAreaID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.bannerManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($bannerAreaID != ''){
	//CHECK bannerAreaID INSIDE DATABASE
	if($bannerManager->checkBannerAreaIDDatabase($bannerAreaID) == 'not found'){
		header("Location:".$cms_root."banner-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."banner-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'banner-manager/" title="Banner Manager">Banner Manager</a> | <span class="current">Manage Banner</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Banner - <?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Banner</b> page. This page will allow you to add or edit a banner to the current banner area (<?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?>).</p>
            <p>To add a new banner simply click on <b>Add Banner</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Banner Area Architecture</div>

				<?php if($bannerManager->checkDefaultBanner($bannerAreaID) != 0){ ?>
                <div class="module-links"><a href="<?php echo $cms_root; ?>banner-manager/add-banner.php?bannerAreaID=<?php echo $bannerAreaID; ?>" title="Add Banner">Add Banner</a></div>
				<?php }else{ ?>
				<div class="module-links"><a href="<?php echo $cms_root; ?>banner-manager/add-banner.php?bannerAreaID=<?php echo $bannerAreaID; ?>" title="Add Banner">Add Default Banner</a></div>
				<?php }?>

				<div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the banners added to the current banner area.
                </div>

                <?php echo $bannerManager->defineErrorMessages($_GET['message']); ?>

				<div class="module-architecture-table-holder">
					<?php echo $bannerManager->defaultBannerContentArchitecture($cms_root, $web_root, $bannerAreaID);?>
				</div>

				<?php if($bannerManager->checkDefaultBanner($bannerAreaID) != 0){ ?>
                <div class="module-architecture-table-holder" id="sortable">
                    <?php echo $bannerManager->bannerContentArchitecture($cms_root, $web_root, $bannerAreaID);?>
                </div>
				<?php } ?>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/banner-content-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
