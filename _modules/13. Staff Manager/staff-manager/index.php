<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 13;
$pageTitle = 'Staff Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.staffManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Staff Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Staff Manager</h1>
        <div class="intro">
        	<p>This is the <b>Staff Manager</b>. This module will allow you to add new staff members to your website.</p>
            <p>In order to add a new staff memeber click on <b>Add Staff Member</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Staff Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>staff-manager/add-staff.php" title="Add Staff Member">Add Staff Member</a></div>

                <?php if($staffManager->getTotalStaff() >= 2){ ?>
                <div class="module-links"><a href="<?php echo $cms_root; ?>staff-manager/sequence.php" title="Sequence Staff Members">Sequence Staff Members</a></div>
                <?php } ?>
                
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all your staff members.
                </div>

                <?php echo $staffManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Staff Members</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="50%">Staff Name</td>
                        <td width="30%">Position</td>
                        <td width="10%" align="center">Modify</td>
                        <td width="10%" align="center">Remove</td>
                      </tr>

                      <?php echo $staffManager->staffArchitecture($cms_root);?>

                    </table>
                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/staff-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
