<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$colorbox = 1;
$ckeditor = 1;
$pageTitle = 'Edit Work Place';

//GET URL VARIABLE
if(isset($_POST['work_id'])){$work_id = $_POST['work_id'];}else{$work_id = $_GET['work_id'];}
if(isset($_POST['about_id'])){$about_id = $_POST['about_id'];}else{$about_id = $_GET['about_id'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($work_id != '' && $about_id != ''){
	//CHECK $work_id AND $about_id INSIDE DATABASE
	if($employmentHistoryManager->checkWorkPlaceContentDatabase($work_id, $about_id) == 'not found'){
		header("Location:".$cms_root."employment-history-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."employment-history-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <a href="'.$cms_root.'employment-history-manager/manage-work-history.php" title="Manage Work History">Manage Work History</a> | <span class="current">Edit Work Place</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Work Place - <?php echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'work_place');?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Work Place</b> page. This page will allow you to edit the current Work Place (<?php echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'work_place');?>).</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Work Place</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to edit the current Work Place. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Work Place</b> to edit the current Work Place.
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
                    	<input type="hidden" name="work_id" value="<?php echo $work_id; ?>"/>
                        <input type="hidden" name="about_id" value="<?php echo $about_id; ?>"/>

						<div class="module-form-titles"><span class="required">*</span> Place of Work:</div>
						<input type="text" name="work-place-title" placeholder="Name of the place you worked at" value="<?php if($_POST['work-place-title'] != ''){echo $_POST['work-place-title'];}else{echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'work_place');}?>" maxlength="250" />
                        <i>The Place of Work has a maximum of 250 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Work Type:</div>
						<input type="text" name="work-type" placeholder="Work Type" value="<?php if($_POST['work-type'] != ''){echo $_POST['work-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'description');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-form-titles"><span class="required">*</span> Location of Work Place:</div>
						<input type="text" name="work-place-location" placeholder="Location of Work Place (e.g. Windhoek, Namibia)" value="<?php if($_POST['work-place-location'] != ''){echo $_POST['work-place-location'];}else{echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'work_place');}?>" maxlength="150" />
                        <i>The Location of Work Place has a maximum of 150 characters.</i>

						<span class="hidden"><div class="module-form-titles">Work Place Country:</div>
						<input type="text" name="work-place-country" placeholder="Work Place Country" value="<?php if($_POST['work-place-country'] != ''){echo $_POST['work-place-country'];}?>" />
                        <i>The country has a maximum of 150 characters.</i></span>

						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="work-start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['work-start-date'] != ''){echo $_POST['work-start-date'];}else{echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'start_date');}?>" />
                            <i>Please supply the date you started at the institution.</i>
                        </div>

                        <div class="module-time-input">
							<?php
							//CHECK IF WORK END DATE IS SUPPLIED
							if($employmentHistoryManager->getWorkPlaceInfo($work_id, 'end_date') != '0000-00-00'){
								$work_end_date = $employmentHistoryManager->getWorkPlaceInfo($work_id, 'end_date');
							}
							?>
                            <div class="module-form-titles">End Date:</div>
                            <input type="text" name="work-end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['work-end-date'] != ''){echo $_POST['work-end-date'];}else{echo $work_end_date;}?>" />
                            <i>If you still working at this work place,please leave this field blank, otherwise supply the date you finished working there.</i>
                        </div>

						<div class="clear"></div>

						<div class="module-form-titles">Work Place Website Link:</div>
						<input type="text" name="work-website" placeholder="Work Place Website Link" value="<?php if($_POST['work-website'] != ''){echo $_POST['work-website'];}else{echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'website');}?>" maxlength="250"/>
                        <i>Please supply a valid/workable link.</i>

                    </div>
					<input type="submit" class="module-form-submit" name="edit_work_place" title="Edit Work Place" value="Edit Work Place" onclick="pleasewait()"/>
                </form>

            </div>
            <!-- END PARAGRAPH HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Work Place Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Work Place</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $employmentHistoryManager->getUsersName($employmentHistoryManager->getWorkPlaceInfo($work_id, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($employmentHistoryManager->getWorkPlaceInfo($work_id, 'modifiedBy') != 0){
									echo $employmentHistoryManager->getUsersName($employmentHistoryManager->getWorkPlaceInfo($work_id, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($employmentHistoryManager->getWorkPlaceInfo($work_id, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($employmentHistoryManager->getWorkPlaceInfo($work_id, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($employmentHistoryManager->getWorkPlaceInfo($work_id, 'modifiedDate')));
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
                        	<?php echo $employmentHistoryManager->getWorkPlaceInfo($work_id, 'modifiedNumber');?>
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
                	<?php include_once("../inc/employment-history-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
