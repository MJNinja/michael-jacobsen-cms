<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 17;
$sequence = 1;
$sequenceTable = 'tour_content';
$sequenceMainID = 'tourContentID';
$pageTitle = 'Manage Tour';

//GET URL VARIABLE
if(isset($_POST['tourID'])){$tourID = $_POST['tourID'];}else{$tourID = $_GET['tourID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.toursManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($tourID != ''){
	//CHECK tourID INSIDE DATABASE
	if($toursManager->checkTourDatabase($tourID) == 'not found'){
		header("Location:".$cms_root."tours-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."tours-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'tours-manager/" title="Tours Manager">Tours Manager</a> | <span class="current">Manage Tour</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Tour - <?php echo $toursManager->getTourInfo($tourID, 'tourTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Tour</b> page. This page will allow you to add content to the current Tour (<?php echo $toursManager->getTourInfo($tourID, 'tourTitle'); ?>).</p>
            <p>To add a new paragraph simply click on <b>Add Paragraph</b> and to add a new gallery simply click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Tour Content Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>tours-manager/add-gallery.php?tourID=<?php echo $tourID; ?>" title="Add Gallery">Add Gallery</a><a href="<?php echo $cms_root; ?>tours-manager/add-paragraph.php?tourID=<?php echo $tourID; ?>" title="Add Paragraph">Add Paragraph</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current Tour.
                </div>

                <?php echo $toursManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $toursManager->tourContentArchitecture($cms_root, $web_root, $tourID);?>

                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/tours-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
