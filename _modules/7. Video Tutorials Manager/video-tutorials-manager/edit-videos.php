<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$colorbox = 1;
$pageTitle = 'Edit Video';

//GET URL VARIABLE
if(isset($_POST['videoTutContentID'])){$videoTutContentID = $_POST['videoTutContentID'];}else{$videoTutContentID = $_GET['videoTutContentID'];}
if(isset($_POST['videoTutPlaylistID'])){$videoTutPlaylistID = $_POST['videoTutPlaylistID'];}else{$videoTutPlaylistID = $_GET['videoTutPlaylistID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.videoTutorialsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($videoTutContentID != '' && $videoTutPlaylistID != ''){
	//CHECK quoteID AND quoteCatID INSIDE DATABASE
	if($videoTutorialManager->checkVideoContentDatabase($videoTutContentID, $videoTutPlaylistID) == 'not found'){
		header("Location:".$cms_root."video-tutorials-manage/");
		exit;
	}
}else{
	header("Location:".$cms_root."video-tutorials-manage/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'video-tutorials-manager/" title="Video Tutorials Manager">Video Tutorials Manager</a> | <a href="'.$cms_root.'video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID='.$videoTutPlaylistID.'" title="Manage Video Tutorial Playlist">Manage Video Tutorial Playlist</a> | <span class="current">Edit Video</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Video - <?php echo $videoTutorialManager->getVideoInfo($videoTutContentID, 'videoTitle');?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Video</b> page. This page will allow you to edit the current video of the current tutorial playlist (<?php echo $videoTutorialManager->getVideoInfo($videoTutContentID, 'videoTitle');?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Video</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current video. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Video</b> to edit the current video.
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
                    	<input type="hidden" name="videoTutContentID" value="<?php echo $videoTutContentID; ?>"/>
                        <input type="hidden" name="videoTutPlaylistID" value="<?php echo $videoTutPlaylistID; ?>"/>

                        <div class="module-form-titles"> YouTube/Vimeo Video:</div>
                        <div>
                        	<p>
                            	To add a YouTube/Vimeo video to the paragraph kindly follow the following instructions:
							</p>
                            <ol>
                                <li>Find and open video on YouTube/Vimeo.</li>
                                <li>Copy the link directly from the URL bar into the input field below.<br /><b>DO NOT CHANGE THE LINK AND MAKE SURE THAT THE LINK IS COPIED DIRECTLY FROM THE URL BAR</b></li>
                            </ol>

                           <?php echo $videoTutorialManager->getVideoContentVideo($videoTutContentID); ?>

						</div>

                        <div class="module-form-titles"><span class="required">*</span> Video Title:</div>
                        <input type="text" name="video-title" placeholder="Video Title" value="<?php if($_POST['video-title'] != ''){echo $_POST['video-title'];}else{echo $videoTutorialManager->getVideoInfo($videoTutContentID, 'videoTitle');}?>" maxlength="150" />
                        <i>The title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Video Type:</div>
                        <input type="text" name="video-type" placeholder="Paragraph Type" value="<?php if($_POST['video-type'] != ''){echo $_POST['paragraph-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Video Link:</div>
						<input type="text" name="youtube-vimeo-video" cols="20" rows="5" placeholder="YouTube/Vimeo Video" value="<?php if($_POST['youtube-vimeo-video'] != ''){echo $_POST['youtube-vimeo-video'];}else{ echo $videoTutorialManager->getVideoInfo($videoTutContentID, 'videoLink');}?>" />
                        <i>Copy your YouTube/Vimeo link from the URL bar into the input field below.</i>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_video" title="Edit Video" value="Edit Video" onclick="pleasewait()"/>
                </form>
            </div>
            <!-- END PARAGRAPH HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Video Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Video</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $videoTutorialManager->getUsersName($videoTutorialManager->getVideoInfo($videoTutContentID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($videoTutorialManager->getVideoInfo($videoTutContentID, 'modifiedBy') != 0){
									echo $videoTutorialManager->getUsersName($videoTutorialManager->getVideoInfo($videoTutContentID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($videoTutorialManager->getVideoInfo($videoTutContentID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($videoTutorialManager->getVideoInfo($videoTutContentID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($videoTutorialManager->getVideoInfo($videoTutContentID, 'modifiedDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>
                        	<div class="edit-information-table-label"><b>No. of Times Modified:</b></div>
                        	<?php echo $videoTutorialManager->getVideoInfo($videoTutContentID, 'modifiedNumber');?>
                        </td>
                      </tr>
                    </table>

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
