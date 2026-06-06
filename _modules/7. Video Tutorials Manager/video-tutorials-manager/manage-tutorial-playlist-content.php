<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$sequence = 1;
$sequenceTable = 'video_tutorials_content';
$sequenceMainID = 'videoTutContentID';
$pageTitle = 'Manage Video Tutorial Playlist';

//GET URL VARIABLE
if(isset($_POST['videoTutPlaylistID'])){$videoTutPlaylistID = $_POST['videoTutPlaylistID'];}else{$videoTutPlaylistID = $_GET['videoTutPlaylistID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.videoTutorialsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($videoTutPlaylistID != ''){
	//CHECK quoteCatID INSIDE DATABASE
	if($videoTutorialManager->checkPlaylistDatabase($videoTutPlaylistID) == 'not found'){
		header("Location:".$cms_root."video-tutorials-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."video-tutorials-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'video-tutorials-manager/" title="Video Tutorials Manager">Video Tutorials Manager</a> | <span class="current">Manage Video Tutorial Playlist</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Video Tutorial Playlist - <?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistTitle');?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Video Tutorial Playlist</b> page. This page will allow you to add videos to the current tutorial playlist (<?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistTitle');?>).</p>
            <p>To add a new video(s) simply click on <b>Add Video(s)</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Tutorial Playlist Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>video-tutorials-manager/add-videos.php?videoTutPlaylistID=<?php echo $videoTutPlaylistID; ?>" title="Add Video(s)">Add Video(s)</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the video(s) added to the current tutorial playlist.
                </div>

                <?php echo $videoTutorialManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $videoTutorialManager->videoContentArchitecture($cms_root, $web_root, $videoTutPlaylistID);?>

                </div>

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
