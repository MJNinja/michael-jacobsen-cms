<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$pageTitle = 'Employment History Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Employment History Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Employment History Manager</h1>
        <div class="intro">
        	<p>This is the <b>Employment History Manager</b>. This module will allow you to manage your About page on your website.</p>
            <p>Choose the section you wish to edit or add new information to and then click on the <b>Manage</b> link.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;About Me Architecture</div>
                <div class="module-links"></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the About Me information that can be edited, which are ordered into sections.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates section is 100% complete.</div>
                        <div class="clear"></div>
                        <div class="partially-user-key"></div><div class="partially-user-key-description">Indicates section is partially complete.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates section is 0% complete.</div>
                    </div>
                </div>

                <?php echo $employmentHistoryManager->defineErrorMessages($_GET['message']); ?>

                <?php echo $employmentHistoryManager->aboutMeSectionArchitecture($cms_root); ?>

                <?php echo $employmentHistoryManager->carrierSectionArchitecture($cms_root); ?>

                <?php echo $employmentHistoryManager->skillsSectionArchitecture($cms_root); ?>

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
