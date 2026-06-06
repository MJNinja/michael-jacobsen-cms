<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$pageTitle = 'Manage Product Categories';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'product-manager/" title="Product Manager">Product Manager</a> | <span class="current">Manage Product Categories</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Product Categories</h1>
        <div class="intro">
        	<p>This is the <b>Manage Product Categories</b> page. This page will allow you to manage all the categories available for your Products.</p>
            <p>To add a new Product Category simply click on <b>Add Product Category</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Product Category Architecture</div>
                <div class="module-links">
                    <a href="<?php echo $cms_root; ?>product-manager/add-product-category.php" title="Add Product Category">Add Product Category</a>
                    <?php if($productManager->checkMainCategoryAdded() != 0){ ?>
                    <a href="<?php echo $cms_root; ?>product-manager/add-sub-product-category.php" title="Add Sub Category">Add Sub Category</a>
                    <?php } ?>
                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current Product Categories.
                </div>

                <?php echo $productManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">

                    <?php echo $productManager->categoryArchitecture($cms_root, $web_root);?>

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
