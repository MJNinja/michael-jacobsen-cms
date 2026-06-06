<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 19;
$pageTitle = 'FAQ Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.faqManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">FAQ Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">FAQ Manager</h1>
        <div class="intro">
        	<p>This is the <b>FAQ Manager</b>. This module will allow you to add new FAQ(s) that you use on your website.</p>
            <p>In order to add a new FAQ click on <b>Add FAQ</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;FAQ Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>faq-manager/add-faq.php" title="Add FAQ">Add FAQ</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your FAQs.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active FAQ.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed FAQ.</div>
                    </div>
                </div>

                <?php echo $faqManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active FAQ</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="65%">FAQ Question</td>
                        <td width="17%" align="center">Modify FAQ</td>
                        <td width="17%" align="center">Remove</td>
                      </tr>

                      <?php echo $faqManager->faqArchitecture($cms_root);?>

                    </table>
                </div>

                <?php if($faqManager->checkRemovedFAQ() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed FAQ</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">FAQ Question</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $faqManager->faqArchitectureRemoved($cms_root);?>

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
                	<?php include_once("../inc/faq-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
