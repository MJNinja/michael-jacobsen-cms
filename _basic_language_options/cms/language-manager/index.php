<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 100;
$pageTitle = 'Language Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.languageManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Language Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Language Manager</h1>
        <div class="intro">
        	<p>This is the <b>Language Manager</b>. This module will allow you to manage all language options available on your website.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Language Architecture</div>

                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your available Languages options.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active language.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates de-activated language.</div>
                    </div>
                </div>

                <?php echo $languageManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Languages</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="5%" align="center">Flag</td>
                        <td width="82%">Language</td>
                        <td width="12%" align="center">De-activate</td>
                      </tr>

                      <?php echo $languageManager->languageArchitecture($cms_root, $web_root, $_SESSION['ccl']);?>

                    </table>
                </div>

                <?php if($languageManager->checkDeActivatedLanguage() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">De-Activated Languages</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="5%" align="center">Flag</td>
                        <td width="82%">Language</td>
                        <td width="12%" align="center">Activate</td>
                      </tr>

                      <?php echo $languageManager->languageArchitectureDeActivated($cms_root, $web_root);?>

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
                	<?php include_once("../inc/language-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
