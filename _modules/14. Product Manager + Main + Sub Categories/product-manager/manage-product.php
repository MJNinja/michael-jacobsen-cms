<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$sequence = 1;
$sequenceTable = 'product_content';
$sequenceMainID = 'productContentID';
$pageTitle = 'Manage Product';

//GET URL VARIABLE
if(isset($_POST['productID'])){$productID = $_POST['productID'];}else{$productID = $_GET['productID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($productID != ''){
	//CHECK productID INSIDE DATABASE
	if($productManager->checkProductDatabase($productID) == 'not found'){
		header("Location:".$cms_root."product-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."product-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'product-manager/" title="Product Manager">Product Manager</a> | <span class="current">Manage Product</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Product - <?php echo $productManager->getProductInfo($productID, 'productTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Product</b> page. This page will allow you to add content to the current Product (<?php echo $productManager->getProductInfo($productID, 'productTitle'); ?>).</p>
            <p>To add a new paragraph simply click on <b>Add Paragraph</b> and to add a new gallery simply click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Product Content Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>product-manager/add-gallery.php?productID=<?php echo $productID; ?>" title="Add Gallery">Add Gallery</a><a href="<?php echo $cms_root; ?>product-manager/add-paragraph.php?productID=<?php echo $productID; ?>" title="Add Paragraph">Add Paragraph</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current Product.
                </div>

                <?php echo $productManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $productManager->productContentArchitecture($cms_root, $web_root, $productID);?>

                </div>

            </div>
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
