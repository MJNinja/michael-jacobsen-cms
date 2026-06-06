<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$pageTitle = 'Video Tutorials Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.videoTutorialsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Video Tutorials Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Video Tutorials Manager</h1>
        <div class="intro">
        	<p>This is the <b>Video Tutorials Manager</b>. This module will allow you to manage all your Video Tutorials that you published on your website.</p>
            <p>In order to create a Video Tutorial Playlist you will firstly have to add a Category. To add Categories click on the <b>Manage Tutorial Categories</b> button. Once a category has been added the <b>Add Tutorial Playlist</b> button will appear, allowing you to add Playlists.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Tutorial Category Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>video-tutorials-manager/manage-tutorial-category.php" title="Manage Tutorial Categories">Manage Tutorial Categories</a>

                <?php if($videoTutorialManager->checkCategoryAdded() != 0){?>
                <a href="<?php echo $cms_root; ?>video-tutorials-manager/add-tutorial-playlist.php" title="Add Tutorial Playlist">Add Tutorial Playlist</a>
                <?php }?>

                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Tutorial Playlists.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active playlist.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed playlist.</div>
                    </div>
                </div>

                <?php echo $videoTutorialManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Playlists</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="50%">Playlist Name</td>
                        <td width="12%" align="center">Publish</td>
                        <td width="14%" align="center">Manage Playlist</td>
                        <td width="14%" align="center">Modify Playlist</td>
                        <td width="10%" align="center">Remove</td>
                      </tr>

                      <?php echo $videoTutorialManager->playlistArchitecture($cms_root);?>

                    </table>
                </div>

                <?php if($videoTutorialManager->checkRemovedPlaylists() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Playlist</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Playlist Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>

                      <?php echo $videoTutorialManager->playlistArchitectureRemoved($cms_root);?>

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
                	<?php include_once("../inc/video-tutorials-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
