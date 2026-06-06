<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$gallery_upload = 1;
$sequence = 1;
$sequenceTable = 'product_gallery_content';
$sequenceMainID = 'productGalleryContentID';
$pageTitle = 'Sequence Gallery';

//GET URL VARIABLE
if(isset($_POST['productID'])){$productID = $_POST['productID'];}else{$productID = $_GET['productID'];}
if(isset($_POST['productGalleryID'])){$productGalleryID = $_POST['productGalleryID'];}else{$productGalleryID = $_GET['productGalleryID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($productID != '' && $productGalleryID != ''){
	//CHECK productID INSIDE DATABASE
	if($productManager->checkProductDatabase($productID) == 'not found'){
		header("Location:".$cms_root."product-manager/");
		exit;
	}

    //CHECK productGalleryID INSIDE DATABASE
    if($productManager->checkProductGalleryDatabase($productGalleryID) == 'not found'){
		header("Location:".$cms_root."product-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."product-manager/");
	exit;
}

//SET LOGIN SESSION TIME
if($_SESSION['cmsEditPageGallery'] == ''){
	$_SESSION['cmsEditPageGallery'] = date('Y-m-d H:i:s');
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'product-manager/" title="Product Manager">Product Manager</a> | <a href="'.$cms_root.'product-manager/manage-product.php?productID='.$productID.'" title="Manage Product">Manage Product</a> | <span class="current">Sequence Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Sequence Gallery - <?php echo $productManager->getProductInfo($productID, 'productTitle');?></h1>
        <div class="intro">
        	<p>This is the <b>Sequence Gallery</b> page. This page will allow you to sequnce the current gallery (<?php echo $productManager->getProductInfo($productID, 'productTitle');?>).</p>
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
                    	<?php echo $productManager->getProductGalleryImagesSequencing($productGalleryID, $web_root);?>
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
                	<?php include_once("../inc/product-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
