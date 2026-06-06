<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 21;
$sequence = 1;
$sequenceTable = 'event_content';
$sequenceMainID = 'eventContentID';
$pageTitle = 'Manage Event';

//GET URL VARIABLE
if(isset($_POST['eventID'])){$eventID = $_POST['eventID'];}else{$eventID = $_GET['eventID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.eventsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($eventID != ''){
	//CHECK eventID INSIDE DATABASE
	if($eventManager->checkEventDatabase($eventID) == 'not found'){
		header("Location:".$cms_root."events-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."events-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'events-manager/" title="Events Manager">Events Manager</a> | <span class="current">Manage Event</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Event - <?php echo $eventManager->getEventInfo($eventID, 'eventTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Event</b> page. This page will allow you to add content to the current event (<?php echo $eventManager->getEventInfo($eventID, 'eventTitle'); ?>).</p>
            <p>To add a new paragraph simply click on <b>Add Paragraph</b> and to add a new gallery simply click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Event Content Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>events-manager/add-gallery.php?eventID=<?php echo $eventID; ?>" title="Add Gallery">Add Gallery</a><a href="<?php echo $cms_root; ?>events-manager/add-paragraph.php?eventID=<?php echo $eventID; ?>" title="Add Paragraph">Add Paragraph</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current event.
                </div>

                <?php echo $eventManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $eventManager->eventContentArchitecture($cms_root, $web_root, $eventID);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/event-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
