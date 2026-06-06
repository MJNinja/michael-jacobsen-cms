<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 3;
$pageTitle = 'Manage Blog Categories';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.blogManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'blog-manager/" title="Blog Manager">Blog Manager</a> | <span class="current">Manage Blog Categories</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Blog Categories</h1>
        <div class="intro">
        	<p>This is the <b>Manage Blog Categories</b> page. This page will allow you to manage all the categories available for your Blog.</p>
            <p>To add a new Blog Category simply click on <b>Add Blog Category</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Blog Category Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>blog-manager/add-blog-category.php" title="Add Blog Category">Add Blog Category</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current Blog Categories.
                </div>

                <?php echo $blogManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">

                    <?php echo $blogManager->categoryArchitecture($cms_root, $web_root);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/blog-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
