<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$sequence = 1;
$sequenceTable = 'personal_education';
$sequenceMainID = 'education_id';
$pageTitle = 'Manage Education History';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <span class="current">Manage Education History</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Education History</h1>
        <div class="intro">
        	<p>This is the <b>Manage Education History</b> page. This page will allow you to add institutions where you studied.</p>
            <p>To add a new institution simply click on <b>Add Institution</b></p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Education History Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>employment-history-manager/add-institution.php" title="Add Institution">Add Institution</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the your Education History.
                </div>

                <?php echo $employmentHistoryManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $employmentHistoryManager->educationHistoryContentArchitecture($cms_root);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/employment-history-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
