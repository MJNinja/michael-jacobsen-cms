<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 2;
$pageTitle = 'Basic Content Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.basicContentManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Basic Content Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Basic Content Manager</h1>
        <div class="intro">
        	<p>This is the <b>Basic Content Manager</b>. This module will allow you to add content to pages on your website.</p>
            <p>Click on <b>Manage</b> next to the name of the page you wish to add or edit the content.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Pages Architecture</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Pages available on your website.
                </div>

                <div class="module-architecture-table-holder">
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="80%" colspan="2">Page Name</td>
                        <td width="20%" align="center">Manage Page</td>
                      </tr>

                      <?php echo $basicContentManager->pagesArchitecture($cms_root);?>

                    </table>
                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/basic-content-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
