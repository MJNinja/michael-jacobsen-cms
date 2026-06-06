<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 17;
$pageTitle = 'Tours Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.toursManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Tours Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Tours Manager</h1>
        <div class="intro">
        	<p>This is the <b>Tours Manager</b>. This module will allow you to manage all your Tours that are on your website.</p>
            <p>In order to create a Tour click on the <b>Add Tour</b> button and fill out the required fields.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Tour Architecture</div>
                <div class="module-links">
                    <a href="<?php echo $cms_root; ?>tours-manager/add-tour.php" title="Add Tour">Add Tour</a>
                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your Tours.
                </div>

                <?php echo $toursManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">Tours</div>
                    <table width="100%" class="module-architecture-table">
    	                <tr class="module-architecture-header">
                            <td width="61%">Tour Name</td>
                            <td width="13%" align="center">Manage</td>
                            <td width="13%" align="center">Modify</td>
                            <td width="13%" align="center">Remove</td>
                        </tr>

                        <?php echo $toursManager->tourArchitecture($cms_root);?>

                    </table>
                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/tours-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
