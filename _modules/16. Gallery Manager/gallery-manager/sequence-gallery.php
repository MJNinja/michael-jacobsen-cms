<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 16;
$sequence = 1;
$gallery_upload = 1;
$sequenceTable = 'galleries_images';
$sequenceMainID = 'galleryImageID';
$pageTitle = 'Sequence Gallery';

//GET URL VARIABLE
if(isset($_POST['galleryID'])){$galleryID = $_POST['galleryID'];}else{$galleryID = $_GET['galleryID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.galleryManager.php");

//REDIRECT PAGE
if($galleryID != ''){
	//CHECK $galleryID INSIDE DATABASE
	if($galleryManager->checkGalleryDatabase($galleryID) == 'not found'){
		header("Location:".$cms_root."gallery-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."gallery-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'gallery-manager/" title="Gallery Manager">Gallery Manager</a> | <span class="current">Sequence Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Sequence Gallery - <?php echo $galleryManager->getGalleryInfo($galleryID, 'galleryName');?></h1>
        <div class="intro">
        	<p>This is the <b>Sequence Gallery</b> page. This page will allow you to seqeunce the current gallery (<?php echo $galleryManager->getGalleryInfo($galleryID, 'galleryName');?>).</p>
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
                    	<?php echo $galleryManager->getGalleryImagesSequencing($galleryID, $web_root, $cms_root);?>
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
                	<?php include_once("../inc/gallery-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
