<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 8;
$colorbox = 1;
$paragraph_image_enlarge = 1;
$ckeditor = 1;
$date_picker = 1;
$pageTitle = 'Edit Banner';

//GET URL VARIABLE
if(isset($_POST['bannerAreaID'])){$bannerAreaID = $_POST['bannerAreaID'];}else{$bannerAreaID = $_GET['bannerAreaID'];}
if(isset($_POST['bannerID'])){$bannerID = $_POST['bannerID'];}else{$bannerID = $_GET['bannerID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.bannerManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($bannerAreaID != '' && $bannerID != ''){
	//CHECK bannerAreaID AND bannerID INSIDE DATABASE
	if($bannerManager->checkBannerDatabase($bannerAreaID, $bannerID) == 'not found'){
		header("Location:".$cms_root."banner-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."banner-manager/");
	exit;
}

//CHECK IF IT IS A DEFAULT BANNER
$defaultBanner = $bannerManager->getBannerInfo($bannerID, 'defaultBanner');

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'banner-manager/" title="Banner Manager">Banner Manager</a> | <a href="'.$cms_root.'banner-manager/manage-banner.php?bannerAreaID='.$bannerAreaID.'" title="Manage Banner">Manage Banner</a> | <span class="current">Edit Banner</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

		<?php if($defaultBanner == 0){ ?>
    	<h1 align="center">Edit Banner - <?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?></h1>
		<?php }else{ ?>
		<h1 align="center">Edit Default Banner - <?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?></h1>
		<?php } ?>

        <div class="intro">
			<?php if($defaultBanner == 0){?>
        	<p>This is the <b>Edit Banner</b> page. This page will allow you to edit the current banner of the banner area (<?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?>).</p>
			<?php }else{ ?>
			<p>This is the <b>Edit Default Banner</b> page. This page will allow you to edit the default banner of the banner area (<?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?>).</p>
			<?php } ?>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
				<?php if($defaultBanner == 0){?>
				<div class="module-holder-name">&nbsp;&nbsp;Edit Banner</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current banner. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Banner</b> to edit the current banner.
                </div>
				<?php }else{ ?>
				<div class="module-holder-name">&nbsp;&nbsp;Edit Default Banner</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the default banner. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Default Banner</b> to edit the default banner.
                </div>
				<?php } ?>
            </div>
            <!-- END PARAGRAPH HOLDER-->

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">

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
					<input type="hidden" name="bannerAreaID" value="<?php echo $bannerAreaID; ?>"/>
					<input type="hidden" name="bannerID" value="<?php echo $bannerID;?>"/>
					<input type="hidden" name="defaultBanner" value="<?php echo $defaultBanner; ?>"/>
					<input type="hidden" name="oldImage" value="<?php echo $bannerManager->getBannerInfo($bannerID, 'imageFile');?>"/>
                	<div class="module-form-titles">Banner Image</div>

                    <?php echo $bannerManager->getBannerImage($bannerID, $web_root); ?>

                    <div class="module-form-titles">Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $bannerManager->getBannerInfo($bannerID, 'imageTitle');}?>" maxlength="150" />
                    <i>The image title has a maximum of 150 characters.</i>

					<div class="module-form-titles">Banner Description:</div>
					<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $bannerManager->getBannerInfo($bannerID, 'bannerDescription');}?></textarea>
					<i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles">Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>

					<div class="module-form-titles">Banner Link:</div>
					<input type="text" name="banner-link" placeholder="Banner Link" value="<?php if($_POST['banner-link'] != ''){echo $_POST['banner-link'];}else{echo $bannerManager->getBannerInfo($bannerID, 'bannerLink');}?>" />
					<i>Please supply a valid/workable link.</i>

					<?php if($defaultBanner == 0){ ?>
					<div class="module-date-input">
						<div class="module-form-titles"><span class="required">*</span> Start Date:</div>
						<?php
							if($_POST['banner-start-date'] != ''){
								$startDate = $_POST['banner-start-date'];
							}else{
								$startDate = $bannerManager->getBannerInfo($bannerID, 'startDate');
								if($startDate == '' || $startDate == ' ' || $startDate == '0000-00-00'){
									$startDate = '';
								}
							}
						?>
						<input type="text" name="banner-start-date" id="datepicker" placeholder="Start Date" value="<?php echo $startDate; ?>">
						<i>Please supply the start date of the banner.</i>
					</div>

					<div class="module-time-input">
						<div class="module-form-titles">End Date:</div>
						<?php
							if($_POST['banner-end-date'] != ''){
								$endDate = $_POST['banner-end-date'];
							}else{
								$endDate = $bannerManager->getBannerInfo($bannerID, 'endDate');
								if($endDate == '' || $endDate == ' ' || $endDate == '0000-00-00'){
									$endDate = '';
								}
							}
						?>
						<input type="text" name="banner-end-date" id="datepicker2" placeholder="End Date" value="<?php echo $endDate; ?>">
						<i>If no end date is supplied the banner will be shown until either removed or an end date is set.</i>
					</div>
					<?php } ?>

                </div>
				<?php if($defaultBanner == 0){?>
				<input type="submit" class="module-form-submit" name="edit_banner" title="Edit Banner" value="Edit Banner" onclick="pleasewait()"/>
				<?php }else{ ?>
				<input type="submit" class="module-form-submit" name="edit_banner" title="Edit Default Banner" value="Edit Default Banner" onclick="pleasewait()"/>
				<?php }?>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Banner Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Banner</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $bannerManager->getUsersName($bannerManager->getBannerInfo($bannerID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($bannerManager->getBannerInfo($bannerID, 'modifiedBy') != 0){
									echo $bannerManager->getUsersName($bannerManager->getBannerInfo($bannerID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($bannerManager->getBannerInfo($bannerID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($bannerManager->getBannerInfo($bannerID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($bannerManager->getBannerInfo($bannerID, 'modifiedDate')));
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
                        	<?php echo $bannerManager->getBannerInfo($bannerID, 'modifiedNumber');?>
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
                	<?php include_once("../inc/banner-content-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
