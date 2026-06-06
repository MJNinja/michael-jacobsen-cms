<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 1;
$pageTitle = 'CMS User Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.UsersManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">CMS User Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">CMS User Manager</h1>
        <div class="intro">
        	<p>This is the <b>CMS User Manager</b>. This module will allow you to add, edit &amp; delete CMS Users.</p>
            <p>By clicking on the <b>Add CMS User</b> you are able to grand more people access to the CMS.</p>
            <p>If you <b>remove a user</b>, an email will be send to him/her telling them their account has been deleted.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;CMS User Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>cms-users-manager/add-cms-user.php" title="Add CMS User">Add CMS User</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the CMS Users.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active users.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed users.</div>
                    </div>
                </div>

                <?php echo $userManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Users</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Full Name</td>
                        <td width="12%" align="center">Modify</td>
                        <td width="12%" align="center">Remove</td>
                      </tr>

                      <?php echo $userManager->cmsUserArchitectureActive($cms_root);?>

                    </table>
                </div>

                <?php if($userManager->checkRemovedCMSUsers() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Users</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Full Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $userManager->cmsUserArchitectureRemoved($cms_root);?>

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
                	<div class="module-stats-label">
                    	Active Users:
                        <b><?php echo $userManager->getTotalActives();?></b>
                    </div>
                    <div class="module-stats-label">
                    	Removed Users:
                        <b><?php echo $userManager->getTotalDeletes();?></b>
                    </div>
                    <div class="module-stats-label">
                    	Total Users:
                        <b><?php echo $userManager->getTotalUsers();?></b>
                    </div>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
