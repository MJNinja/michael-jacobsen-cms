<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$product_load_more = 1;
$pageTitle = 'Product Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Product Manager</span>';

//AJAX FOR BLOG POSTS
require_once("../ajax/ajax.viewMoreProducts.php");

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Product Manager</h1>
        <div class="intro">
        	<p>This is the <b>Product Manager</b>. This module will allow you to manage all your Products that are on your website.</p>
            <p>In order to create a Product you will firstly have to add a Category. To add Categories click on the <b>Manage Product Categories</b> button. Once a category has been added the <b>Add Product</b> button will appear, allowing you to add Product.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Product Architecture</div>
                <div class="module-links">
                    <a href="<?php echo $cms_root; ?>product-manager/manage-product-category.php" title="Manage Product Categories">Manage Product Categories</a>

                    <?php if($productManager->checkCategoryAdded() != 0){?>
                    <a href="<?php echo $cms_root; ?>product-manager/add-product.php" title="Add Product">Add Product</a>
                    <?php }?>
                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Product added to the current category.
                </div>

                <?php echo $productManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">Products</div>
                    <table width="100%" class="module-architecture-table">
    	                <tr class="module-architecture-header">
                            <td width="40%">Product Name</td>
                            <td width="21%" align="center">Category</td>
                            <td width="13%" align="center">Manage</td>
                            <td width="13%" align="center">Modify</td>
                            <td width="13%" align="center">Remove</td>
                        </tr>

                        <?php echo $productManager->productArchitecture($preload_content_products, $cms_root);?>

                    </table>

                    <!-- BEGIN LOAD MORE PRODUCTS -->
                    <div id="recent-products"></div>

                    <div class="load-more-loader" id="loader-products">
                        <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
                    </div>

                    <?php
                    if($total_nums_products > $preload_content_products){
                        echo '<input id="loadmore-products" type="button" class="loadmore-style" title="Load More" value="Load More"><input id="pages-products" type="hidden" value="'.$total_pages_products.'">';
                    }
                    ?>
                    <div class="clear"></div>
                    <!-- END LOAD MORE PRODUCTS -->
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
