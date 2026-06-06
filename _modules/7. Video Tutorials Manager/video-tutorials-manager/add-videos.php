<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$pageTitle = 'Add Video(s)';

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'video-tutorials-manager/" title="Video Tutorials Manager">Video Tutorials Manager</a> | <a href="'.$cms_root.'video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID='.$videoTutPlaylistID.'" title="Manage Video Tutorial Playlist">Manage Video Tutorial Playlist</a> | <span class="current">Add Video(s)</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Video(s) - <?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistTitle');?></h1>
        <div class="intro">
        	<p>This is the <b>Add Video(s)</b> page. This page will allow you to add a new video(s) to the current tutorial playlist (<?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistTitle');?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Video(s)</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out the required field below to add new video(s). <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Video(s)</b> to add the new video(s).
                </div>

                 <?php
				if(!empty($error_message)){
					echo '<div class="rightContentBoxContainerError">';
					echo '<div class="message">'.$error_message.'</div>';
					if(!empty($errors)){
						echo '<div class="errorMessage">'.$errors.'</div>';
					}
					echo '</div>';
				}
				?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="module-form-holder">
                    	<input type="hidden" name="videoTutPlaylistID" value="<?php echo $videoTutPlaylistID; ?>"/>

                        <span class="hidden"><div class="module-form-titles">Video Type:</div>
						<input type="text" name="video-type" placeholder="Video Type" value="<?php if($_POST['video-type'] != ''){echo $_POST['video-type'];}?>" maxlength="150" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Video(s):</div>
                        <p>
                            The Video(s) field allows you to add multiple videos at the same time. Simply use the following syntax: <b>Video Title, Video Link (YouTube or Vimeo)</b>, then press the <b>Enter Button</b> to begin a new line and use the same syntax again. Each new line will represent a new video.
                        </p>

						<textarea name="videos" cols="20" rows="8" placeholder="Use the following syntax to add video(s) to the current playlist:                                                                                                                         Video Title, Video Link (YouTube or Vimeo)"><?php if($_POST['videos'] != ''){echo $_POST['videos'];}?></textarea>
                        <i>In order to add videos to the current playlist use the following syntax: <b>Video Title, Video Link (YouTube or Vimeo)</b>.</i>
                    </div>
                    <input type="submit" class="module-form-submit" name="add_videos" title="Add Video(s)" value="Add Video(s)" onclick="pleasewait()" />
                </form>

            </div>
            <!-- END PARAGRAPH HOLDER-->
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
