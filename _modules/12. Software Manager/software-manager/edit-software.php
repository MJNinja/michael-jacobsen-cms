<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 12;
$colorbox = 1;
$ckeditor = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Software';

//GET URL VARIABLE
if(isset($_POST['softwareID'])){$softwareID = $_POST['softwareID'];}else{$softwareID = $_GET['softwareID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.softwareManager.php");

//REDIRECT PAGE
if($softwareID != ''){
	//CHECK $softwareID INSIDE DATABASE
	if($softwareManager->checkSoftwareDatabase($softwareID) == 'not found'){
		header("Location:".$cms_root."software-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."software-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'software-manager/" title="Software Manager">Software Manager</a> | <span class="current">Edit Software</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");

?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Software</h1>
        <div class="intro">
        	<p>This is the <b>Edit Software</b> page. This page will allow you to edit the current Software.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Software</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the Software. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Software</b> to edit the software.
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
                    	<input type="hidden" name="softwareID" value="<?php echo $softwareID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $softwareManager->getSoftwareInfo($softwareID, 'modifiedNumber')+1;?>"/>
                        <input type="hidden" name="oldImage" value="<?php echo $softwareManager->getSoftwareInfo($softwareID, 'softwareImage');?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Software Name: </div>
						<input type="text" name="software-name" placeholder="Software Name" value="<?php if($_POST['software-name'] != ''){echo $_POST['software-name'];}else{echo $softwareManager->getSoftwareInfo($softwareID, 'softwareName');}?>" maxlength="150" />
                        <i>The software name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Software Type:</div>
						<input type="text" name="software-type" placeholder="Software Type" value="<?php if($_POST['software-type'] != ''){echo $_POST['software-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $softwareManager->getSoftwareInfo($softwareID, 'softwareDescription');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-form-titles"><span class="required">*</span> Software Link:</div>
						<input type="text" name="software-link" placeholder="Software Link" value="<?php if($_POST['software-link'] != ''){echo $_POST['software-link'];}else{echo $softwareManager->getSoftwareInfo($softwareID, 'softwareLink');}?>" />
                        <i>The software link has to be a valid URL.</i>
                    </div>
            </div>

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Tutorial Category Image</div>
                	<p>
                        An image has to be linked to the Software by completing the fields below, please note that when the image is uploaded
                        you will be required to crop the image after the Software has been uploaded.
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Software".
                    </p>

                    <?php echo $softwareManager->getSoftwareImage($softwareID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $softwareManager->getSoftwareInfo($softwareID, 'softwareImageName');}?>" maxlength="150" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
                <input type="submit" class="module-form-submit" name="edit_software" title="Edit Software" value="Edit Software" onclick="pleasewait()"/>
            </form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Software Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Tutorial Category</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $softwareManager->getUsersName($softwareManager->getSoftwareInfo($softwareID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($softwareManager->getSoftwareInfo($softwareID, 'modifiedBy') != 0){
									echo $softwareManager->getUsersName($softwareManager->getSoftwareInfo($softwareID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($softwareManager->getSoftwareInfo($softwareID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($softwareManager->getSoftwareInfo($softwareID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($softwareManager->getSoftwareInfo($softwareID, 'modifiedDate')));
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
                        	<?php echo $softwareManager->getSoftwareInfo($softwareID, 'modifiedNumber');?>
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
                	<?php include_once("../inc/software-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
