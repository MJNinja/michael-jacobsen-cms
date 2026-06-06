<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 7;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$time_picker = 1;
$tutorial_tags = 1;
$tutorial_affiliates_tags = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Tutorial Playlist';

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'video-tutorials-manager/" title="Video Tutorials Manager">Video Tutorials Manager</a> | <span class="current">Edit Tutorial Playlist</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Tutorial Playlist</h1>
        <div class="intro">
        	<p>This is the <b>Edit Tutorial Playlist</b> page. This page will allow you to edit an existing Video Tutorial Playlist.</p>
        </div>

        <div class="left-column">
            <!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Tutorial Playlist</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the video tutorial playlist. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Tutorial Playlist</b> to edit the playlist.
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
				echo $removed_user;
				?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="module-form-holder">
                    	<input type="hidden" name="videoTutPlaylistID" value="<?php echo $videoTutPlaylistID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'modifiedNumber')+1;?>"/>
                        <input type="hidden" name="oldImage" value="<?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistImage');?>"/>
                        <input type="hidden" name="categories" value="">
                        <input type="hidden" name="required-softwares" value="">
						<input type="hidden" name="playlistRatio" value="1">

						<?php if($tutorial_affiliates_tags == 1){?>
						<input type="hidden" name="affiliates" value="">
						<?php }?>

                        <div class="module-form-titles"><span class="required">*</span> Video Tutorial Playlist Name:</div>
                        <input type="text" name="playlist-name" placeholder="Video Tutorial Playlist Name" value="<?php if($_POST['playlist-name'] != ''){echo $_POST['playlist-name'];}else{echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistTitle');}?>" maxlength="150" />
                        <i>The Video Tutorial Playlist Name has a maximum of 150 characters.</i>

                        <!--<div class="module-form-titles"><span class="required">*</span> Intro:</div>
                        <textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistIntro');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>-->

                        <span class="hidden"><div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Date:</div>
                            <input type="text" name="tutorial-playlist-date" id="datepicker" placeholder="Publish Date" value="<?php if($_POST['tutorial-playlist-date'] != ''){echo $_POST['tutorial-playlist-date'];}?>" />
                            <i>Please supply the publish date of the Video Tutorial Playlist.</i>
                        </div></span>
                        <div class="clear"></div>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Tutorial Categories:</div>
                            <ul id="category_tags">
                                <?php if($_POST['categories'] != ''){ echo $videoTutorialManager->generatePostedTags($_POST['categories']);}else{ echo $videoTutorialManager->getTags('videoTutCatID', $videoTutPlaylistID);}?>
                            </ul>
                            <i>Please supply all the categories under which this Video Tutorial Playlist falls under.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"> Required Softwares:</div>
                            <ul id="software_tags">
                                <?php if($_POST['required-softwares'] != ''){ echo $videoTutorialManager->generatePostedTags($_POST['required-softwares']);}else{ echo $videoTutorialManager->getSoftwareTags('requiredSoftware', $videoTutPlaylistID);}?>
                            </ul>
                            <i>Please supply all the required softwares needed for doing the tutorials in this playlist.</i>
                        </div>
                        <div class="clear"></div>

						<?php if($tutorial_affiliates_tags == 1){?>
						<div class="module-date-input">
                            <div class="module-form-titles">Tutorial Affiliate Links:</div>
                            <ul id="affiliate_tags">
                                <?php if($_POST['affiliates'] != ''){ echo $videoTutorialManager->generatePostedTags($_POST['affiliates']);}else{ echo $videoTutorialManager->getAffiliateTags('affiliateIDs', $videoTutPlaylistID);}?>
                            </ul>
                            <i>Please supply all the affiliate links which should be added to this Video Tutorial Playlist.</i>
                        </div>
						<div class="clear"></div>
						<?php }?>

                    </div>
            </div>
            <!-- END PARAGRAPH HOLDER-->

            <!-- BEGIN OWNER DETAILS-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Tutorial Owner</div>
                	<p>
                        If this Tutorial Playlist wasn't done by you, it is required that you give credit to its owner. Please fill out the fields below in order to do so. Once either a website owner or website link has been supplied the corresponding field will become mandatory.
                    </p>

                    <div class="module-form-titles">Website Name:</div>
                    <input type="text" name="owner-website-name" placeholder="Website Name" value="<?php if($_POST['owner-website-name'] != ''){echo $_POST['owner-website-name'];}else{echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'ownerName');}?>" />
                    <i>This is the name of the website of the original owner.</i>

                    <div class="module-form-titles">Website Link:</div>
                    <input type="text" name="owner-website-link" placeholder="Website Link" value="<?php if($_POST['owner-website-link'] != ''){echo $_POST['owner-website-link'];}else{echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'ownerSource');}?>" />
                    <i>This link should direct the user to the original tutorial.</i>
                </div>
            </div>
            <!-- END OWNER DETAILS-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Tutorial Playlist Image</div>
                	<p>
                        An image has to be linked to the Video Tutorial Playlist by completing the fields below, please note that when the image is uploaded
                        you will be required to crop the image after the Video Tutorial Playlist has been uploaded.
                    </p>

                    <?php echo $videoTutorialManager->getPlaylistImage($videoTutPlaylistID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'videoTutPlaylistImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
                <input type="submit" class="module-form-submit" name="edit_tutorial_playlist" title="Edit Tutorial Playlist" value="Edit Tutorial Playlist" onclick="pleasewait()"/>
            </form>

            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Video Tutorial Playlist Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Video Tutorial Playlist</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $videoTutorialManager->getUsersName($videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'modifiedBy') != 0){
									echo $videoTutorialManager->getUsersName($videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'modifiedDate')));
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
                        	<?php echo $videoTutorialManager->getPlaylistInfo($videoTutPlaylistID, 'modifiedNumber');?>
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
