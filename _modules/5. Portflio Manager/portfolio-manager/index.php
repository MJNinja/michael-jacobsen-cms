<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 5;
$pageTitle = 'Portfolio Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.portfolioManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Portfolio Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Portfolio Manager</h1>
        <div class="intro">
        	<p>This is the <b>Portfolio Manager</b>. This module will allow you to add new website(s) that you worked on to your website.</p>
            <p>In order to add a new website click on <b>Add Website</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Portfolio Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>portfolio-manager/add-website.php" title="Add Website">Add Website</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your wesbites.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active website.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed website.</div>
                    </div>
                </div>

                <?php echo $portfolioManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Website</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="48%">Website Name</td>
                        <td width="17%">Manage Website</td>
                        <td width="17%" align="center">Modify Website</td>
                        <td width="17%" align="center">Remove</td>
                      </tr>

                      <?php echo $portfolioManager->websiteArchitecture($cms_root);?>

                    </table>
                </div>

                <?php if($portfolioManager->checkRemovedWebsites() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Website</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Website Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $portfolioManager->websiteArchitectureRemoved($cms_root);?>

                    </table>
                </div>
                <?php }?>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/portfolio-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
