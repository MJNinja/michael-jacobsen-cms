<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 0;

require_once("inc/cms-owner-info-inc.php");
require_once("../library/cms.userLogin.php");
require_once("../library/class.systemConfig.php");

$pageTitle = 'Welcome to the '.$cms_name;

//SET BREADCRUMBS
$breadcrumbs = '<span class="current">Dashboard</span>';

require_once("inc/header-inc.php");
require_once("inc/navigation-index-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Dashboard</h1>
        <div class="intro">
        	<p>Welcome to the <b><?php echo $cms_name; ?></b>. This CMS will allow you to manage the content on your website.</p>
            <p>Below you have quick links to all the available CMS modules that power you website. By clicking on the <strong>Go to Module</strong> link it will take you to the corresponding module.</p>
        </div>

        <div class="stats-container">

            <?php echo $cmsInformation->getDashboardModules($userType, $userModuleRights, $cms_root); ?>

            <div class="clear"></div>

        </div>

    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("inc/footer-inc.php");
?>
