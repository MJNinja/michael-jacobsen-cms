<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 100;
$gallery_upload = 1;
$sequence = 1;
$sequenceTable = 'staff_members';
$sequenceMainID = 'staffID';
$pageTitle = 'Sequence Staff Members';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.languageManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'language-manager/" title="Staff Manager">Staff Manager</a> | <span class="current">Sequence Staff Members</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Sequence Staff Members</h1>
        <div class="intro">
        	<p>This is the <b>Sequence Staff Members</b> page. This page will allow you to sequnce all the Staff Members in the order you want.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN GALLERY IMAGE PREVIEW -->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Sequence Staff Members</div>
                <div class="clear"></div>
                <div class="module-holder-intro"></div>

                <div class="module-form-holder">
                    <div class="module-form-titles">Staff Members</div>
                    <p>
                        Below are all the staff members currently available on your website. To change the sequence of the staff members simply click and hold your left mouse button, and drag the images accordingly.
                    </p>

					<div id="sortable">
                    	<?php echo $languageManager->getStaffMembersSequencing($web_root);?>
					</div>

                </div>
            </div>
            <!-- END GALLERY IMAGE HOLDER-->

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
