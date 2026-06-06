<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 6;
$pageTitle = 'Resource Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.resourceManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Resource Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Resource Manager</h1>
        <div class="intro">
        	<p>This is the <b>Resource Manager</b>. This module will allow you to manage all your Resources that you published on your website.</p>
            <p>In order to create a Resource you will firstly have to add a Category. To add Categories click on the <b>Manage Resource Categories</b> button. Once a category has been added the <b>Add Resource</b> button will appear, allowing you to add a Resources.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Resource Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>resource-manager/manage-resource-category.php" title="Manage Resource Categories">Manage Resource Categories</a>

                <?php if($resourceManager->checkCategoryAdded() != 0){?>
                <a href="<?php echo $cms_root; ?>resource-manager/add-resource.php" title="Add Resource">Add Resource</a>
                <?php }?>

                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Resources.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active resource.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed resource.</div>
                    </div>
                </div>

                <?php echo $resourceManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Resources</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="47%">Resource Name</td>
                        <td width="14%" align="center">Publish Date</td>
                        <td width="15%" align="center">Manage Resource</td>
                        <td width="14%" align="center">Modify Resource</td>
                        <td width="10%" align="center">Remove</td>
                      </tr>

                      <?php echo $resourceManager->resourceArchitecture($cms_root);?>

                    </table>
                </div>

                <?php if($resourceManager->checkRemovedResources() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Resources</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Resource Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $resourceManager->resourceArchitectureRemoved($cms_root);?>

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
                	<?php include_once("../inc/resource-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
