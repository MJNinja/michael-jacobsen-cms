<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 16;
$ckeditor = 1;
$pageTitle = 'Edit Gallery';

//GET URL VARIABLE
if(isset($_POST['galleryID'])){$galleryID = $_POST['galleryID'];}else{$galleryID = $_GET['galleryID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.galleryManager.php");

//REDIRECT PAGE
if($galleryID != ''){
	//CHECK $galleryID INSIDE DATABASE
	if($galleryManager->checkGalleryDatabase($galleryID) == 'not found'){
		header("Location:".$cms_root."gallery-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."gallery-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'software-manager/" title="Gallery Manager">Gallery Manager</a> | <span class="current">Edit Gallery</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");

?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Gallery - (<?php echo $galleryManager->getGalleryInfo($galleryID, 'galleryName'); ?>)</h1>
        <div class="intro">
        	<p>This is the <b>Edit Gallery</b> page. This page will allow you to edit the current Gallery.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Gallery</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the Software. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Gallery</b> to edit the gallery.
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
                    	<input type="hidden" name="galleryID" value="<?php echo $galleryID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $galleryManager->getGalleryInfo($galleryID, 'modifiedNumber')+1;?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Gallery Name: </div>
						<input type="text" name="gallery-name" placeholder="Gallery Name" value="<?php if($_POST['gallery-name'] != ''){echo $_POST['gallery-name'];}else{echo $galleryManager->getGalleryInfo($galleryID, 'galleryName');}?>" maxlength="150" />
                        <i>The gallery name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Gallery Type:</div>
						<input type="text" name="gallery-type" placeholder="Gallery Type" value="<?php if($_POST['gallery-type'] != ''){echo $_POST['gallery-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles">Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $galleryManager->getGalleryInfo($galleryID, 'galleryDescription');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>
					<input type="submit" class="module-form-submit" name="edit_gallery" title="Edit Gallery" value="Edit Gallery" onclick="pleasewait()"/>
	            </form>
            </div>

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Gallery Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Gallery</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $galleryManager->getUsersName($galleryManager->getGalleryInfo($galleryID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($galleryManager->getGalleryInfo($galleryID, 'modifiedBy') != 0){
									echo $galleryManager->getUsersName($galleryManager->getGalleryInfo($galleryID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($galleryManager->getGalleryInfo($galleryID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($galleryManager->getGalleryInfo($galleryID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($galleryManager->getGalleryInfo($galleryID, 'modifiedDate')));
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
                        	<?php echo $galleryManager->getGalleryInfo($galleryID, 'modifiedNumber');?>
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
