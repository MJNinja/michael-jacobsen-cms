<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 16;
$pageTitle = 'Gallery Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.galleryManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Gallery Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Gallery Manager</h1>
        <div class="intro">
        	<p>This is the <b>Gallery Manager</b>. This module will allow you to add new galleries to your website.</p>
            <p>In order to add a new gallery click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Gallery Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>gallery-manager/add-gallery.php" title="Add Gallery">Add Gallery</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your galleries.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active gallery.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed gallery.</div>
                    </div>
                </div>

                <?php echo $galleryManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Gallery</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="59%">Gallery Name</td>
                        <td width="10%" align="center">Modify</td>
                        <td width="10%" align="center">Manage</td>
                        <td width="10%" align="center">Sequence</td>
                        <td width="10%" align="center">Remove</td>
                      </tr>

                      <?php echo $galleryManager->galleryArchitecture($cms_root);?>

                    </table>
                </div>

                <?php if($galleryManager->checkRemovedGallery() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Gallery</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Gallery Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $galleryManager->galleryArchitectureRemoved($cms_root);?>

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
                	<?php include_once("../inc/gallery-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
