<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 8;
$pageTitle = 'Banner Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.bannerManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Banner Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Banner Manager</h1>
        <div class="intro">
        	<p>This is the <b>Banner Manager</b>. This module will allow you to manage all the banners availabe on your website.</p>
            <p>Click on <b>Manage</b> next to the banner name you wish to add or edit the banner.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Banner Architecture</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the banner areas on your website.
                </div>

                <div class="module-architecture-table-holder">
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="80%" colspan="2">Banner Name</td>
                        <td width="20%" align="center">Manage Banner</td>
                      </tr>

                      <?php echo $bannerManager->bannerAreaArchitecture($cms_root);?>

                    </table>
                </div>

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
