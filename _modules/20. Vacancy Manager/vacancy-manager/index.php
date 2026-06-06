<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 20;
$pageTitle = 'Vacancy Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.vacancyManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Vacancy Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Vacancy Manager</h1>
        <div class="intro">
        	<p>This is the <b>Vacancy Manager</b>. This module will allow you to add new vacancies to your website.</p>
            <p>In order to add a new vacancy click on <b>Add Vacancy</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Vacancy Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>vacancy-manager/add-vacancy.php" title="Add Vacancy Member">Add Vacancy</a></div>

                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your vacancies.
                </div>

                <?php echo $vacancyManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Vacancies</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="50%">Vacancy Title</td>
                        <td width="15%">Start Date</td>
                        <td width="15%">End Date</td>
                        <td width="10%" align="center">Modify</td>
                        <td width="10%" align="center">Remove</td>
                      </tr>

                      <?php echo $vacancyManager->vacancyArchitecture($cms_root);?>

                    </table>
                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/vacancy-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
