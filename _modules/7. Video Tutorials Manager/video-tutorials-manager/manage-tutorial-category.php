<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$pageTitle = 'Manage Tutorial Categories';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.videoTutorialsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'video-tutorials-manager/" title="Video Tutorials Manager">Video Tutorials Manager</a> | <span class="current">Manage Tutorial Categories</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Tutorial Categories</h1>
        <div class="intro">
        	<p>This is the <b>Manage Tutorial Categories</b> page. This page will allow you to manage all the categories available for your Video Tutorial Playlists.</p>
            <p>To add a new Tutorial Category simply click on <b>Add Tutorial Category</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Tutorial Category Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>video-tutorials-manager/add-tutorial-category.php" title="Add Tutorial Category">Add Tutorial Category</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current Tutorial Categories.
                </div>

                <?php echo $videoTutorialManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">

                    <?php echo $videoTutorialManager->categoryArchitecture($cms_root, $web_root);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/video-tutorials-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
