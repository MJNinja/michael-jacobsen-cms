<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 4;
$pageTitle = 'Affiliates Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.affiliatesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Affiliates Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Affiliates Manager</h1>
        <div class="intro">
        	<p>This is the <b>Affiliates Manager</b>. This module will allow you to manage all your Affiliate Links for your website.</p>
            <p>You firstly have to create a category for your Affiliate Links by clicking on <b>Add Affiliate Category</b>. In the created category you will then be able to add, edit &amp; delete your Affiliate Links.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Affiliate Categories Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>affiliates-manager/add-affiliate-category.php" title="Add Affiliate Category">Add Affiliate Category</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Affiliate Categories.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active categories.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed categories.</div>
                    </div>
                </div>

                <?php echo $affiliatesManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Categories</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="55%">Category Name</td>
                        <td width="16%" align="center">Manage Category</td>
                        <td width="16%" align="center">Modify Category</td>
                        <td width="12%" align="center">Remove</td>
                      </tr>

                      <?php echo $affiliatesManager->categoryArchitecture($cms_root);?>

                    </table>
                </div>

                <?php if($affiliatesManager->checkRemovedCategories() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Categories</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Category Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $affiliatesManager->categoryArchitectureRemoved($cms_root);?>

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
                	<?php include_once("../inc/affiliates-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
