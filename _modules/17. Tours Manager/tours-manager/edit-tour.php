<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 17;
$colorbox = 1;
$ckeditor = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Tour';

//GET URL VARIABLE
if(isset($_POST['tourID'])){$tourID = $_POST['tourID'];}else{$tourID = $_GET['tourID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.toursManager.php");

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'tours-manager/" title="Tours Manager">Tours Manager</a> | <span class="current">Edit Tour</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Tour - <?php echo $toursManager->getTourInfo($tourID, 'tourTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Tour</b> page. This page will allow you to edit the current tour (<?php echo $toursManager->getTourInfo($tourID, 'tourTitle'); ?>).</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Tour</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the tour. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Tour</b> to edit the tour.
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
                        <input type="hidden" name="tourID" value="<?php echo $tourID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $toursManager->getTourInfo($tourID, 'modifiedNumber')+1; ?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $toursManager->getTourInfo($tourID, 'tourImageFile'); ?>"/>

                    	<div class="module-form-titles"><span class="required">*</span> Tour Title:</div>
						<input type="text" name="tour-title" placeholder="Tour Title" value="<?php if($_POST['tour-title'] != ''){echo $_POST['tour-title'];}else{ echo $toursManager->getTourInfo($tourID, 'tourTitle'); } ?>" maxlength="150"/>
                        <i>The tour title has a maximum of 150 characters.</i>

                        <!--<div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Description"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{ echo $toursManager->getTourInfo($tourID, 'tourIntro'); } ?></textarea>-->
                        <i>The tour description requires a minimum of 10 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="tour-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['tour-paragraph'] != ''){echo $_POST['tour-paragraph'];}?></textarea>
                        <i>Please supply an description for the tour.</i></span>

						<div class="clear"></div>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Tour Image</div>
                	<p>
                        An image can be linked to the Tour by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Tour has been uploaded.
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Tour".
                    </p>

                    <?php echo $toursManager->getProductImage($tourID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $toursManager->getTourInfo($tourID, 'tourImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="edit_tour" title="Edit Tour" value="Edit Tour" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Tour Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Tour</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $toursManager->getUsersName($toursManager->getTourInfo($tourID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($toursManager->getTourInfo($tourID, 'modifiedBy') != 0){
									echo $toursManager->getUsersName($toursManager->getTourInfo($tourID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($toursManager->getTourInfo($tourID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($toursManager->getTourInfo($tourID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($toursManager->getTourInfo($tourID, 'modifiedDate')));
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
                        	<?php echo $toursManager->getTourInfo($tourID, 'modifiedNumber');?>
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
