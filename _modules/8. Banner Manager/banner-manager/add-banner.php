<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 8;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$pageTitle = 'Add Banner';

//GET URL VARIABLE
if(isset($_POST['bannerAreaID'])){$bannerAreaID = $_POST['bannerAreaID'];}else{$bannerAreaID = $_GET['bannerAreaID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.bannerManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($bannerAreaID != ''){
	//CHECK bannerAreaID INSIDE DATABASE
	if($bannerManager->checkBannerAreaIDDatabase($bannerAreaID) == 'not found'){
		header("Location:".$cms_root."banner-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."banner-manager/");
	exit;
}

//CHECK IF DEFAULT BANNER HAS TO BE ADDED
if($bannerManager->checkDefaultBanner($bannerAreaID) == 0){
	$defaultBanner = 1;
}else{
	$defaultBanner = 0;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'banner-manager/" title="Banner Manager">Banner Manager</a> | <a href="'.$cms_root.'banner-manager/manage-banner.php?bannerAreaID='.$bannerAreaID.'" title="Manage Banner">Manage Banner</a> | <span class="current">Add Banner</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

		<?php if($defaultBanner == 0){ ?>
    	<h1 align="center">Add Banner - <?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?></h1>
		<?php }else{ ?>
		<h1 align="center">Add Default Banner - <?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?></h1>
		<?php } ?>

		<div class="intro">
			<?php if($defaultBanner == 0){?>
        	<p>This is the <b>Add Banner</b> page. This page will allow you to add a new Banner to the current banner area (<?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?>).</p>
			<?php }else{ ?>
			<p>This is the <b>Add Default Banner</b> page. This page will allow you to add a the default Banner to the current banner area (<?php echo $bannerManager->getBannerAreaInfo($bannerAreaID, 'bannerAreaName'); ?>).</p>
			<?php } ?>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
				<?php if($defaultBanner == 0){?>
                <div class="module-holder-name">&nbsp;&nbsp;Add Banner</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new banner. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Banner</b> to add the new banner.
                </div>
				<?php }else{ ?>
				<div class="module-holder-name">&nbsp;&nbsp;Add Default Banner</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add the default banner. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Default Banner</b> to add the default banner.
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
					<input type="hidden" name="bannerAreaID" value="<?php echo $bannerAreaID; ?>"/>
					<input type="hidden" name="defaultBanner" value="<?php echo $defaultBanner; ?>"/>
	                <div class="module-form-holder">

	                    <div class="module-form-titles">Banner Title:</div>
	                    <input type="text" name="banner-title" placeholder="Banner Title" value="<?php if($_POST['banner-title'] != ''){echo $_POST['banner-title'];}?>" maxlength="150"/>
	                    <i>The banner title has a maximum of 150 characters.</i>

						<div class="module-form-titles">Banner Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

	                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
	                    <input type="text" name="banner-type" placeholder="Banner Type" value="<?php if($_POST['banner-type'] != ''){echo $_POST['banner-type'];}?>" />
	                    <i>The type has a maximum of 150 characters.</i></span>

	                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
	                    <input type="file" name="image-file" />
	                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>

						<div class="module-form-titles">Banner Link:</div>
	                    <input type="text" name="banner-link" placeholder="Banner Link" value="<?php if($_POST['banner-link'] != ''){echo $_POST['banner-link'];}?>" />
	                    <i>Please supply a valid/workable link.</i>

						<?php if($defaultBanner == 0){ ?>
						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="banner-start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['banner-start-date'] != ''){echo $_POST['banner-start-date'];}?>">
                            <i>Please supply the start date of the banner.</i>
                        </div>

						<div class="module-time-input">
							<div class="module-form-titles">End Date:</div>
                            <input type="text" name="banner-end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['banner-end-date'] != ''){echo $_POST['banner-end-date'];}?>">
                            <i>If no end date is supplied the banner will be shown until either removed or an end date is set.</i>
                        </div>
						<?php } ?>

	                </div>
					<?php if($defaultBanner == 0){?>
					<input type="submit" class="module-form-submit" name="add_banner" title="Add Banner" value="Add Banner" onclick="pleasewait()" />
					<?php }else{ ?>
					<input type="submit" class="module-form-submit" name="add_banner" title="Add Default Banner" value="Add Default Banner" onclick="pleasewait()" />
					<?php }?>
				</form>
            </div>
            <!-- END IMAGE HOLDER-->
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
