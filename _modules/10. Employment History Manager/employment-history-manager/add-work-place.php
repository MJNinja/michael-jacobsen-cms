<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$pageTitle = 'Add Work Place';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <a href="'.$cms_root.'employment-history-manager/manage-work-history.php" title="Manage Work History">Manage Work History</a> | <span class="current">Add Work Place</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Work Place</h1>
        <div class="intro">
        	<p>This is the <b>Add Work Place</b> page. This page will allow you to add a new place where you worked.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Work Place</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new Work Place. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Work Place</b> to add the new work place.
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

                    	<div class="module-form-titles"><span class="required">*</span> Place of Work:</div>
						<input type="text" name="work-place-title" placeholder="Name of the place you worked at" value="<?php if($_POST['work-place-title'] != ''){echo $_POST['work-place-title'];}?>" maxlength="250" />
                        <i>The Place of Work has a maximum of 250 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Work Type:</div>
						<input type="text" name="work-type" placeholder="Work Type" value="<?php if($_POST['work-type'] != ''){echo $_POST['work-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-form-titles"><span class="required">*</span> Location of Work Place:</div>
						<input type="text" name="work-place-location" placeholder="Location of Work Place (e.g. Windhoek, Namibia)" value="<?php if($_POST['work-place-location'] != ''){echo $_POST['work-place-location'];}?>" maxlength="150" />
                        <i>The Location of Work Place has a maximum of 150 characters.</i>

						<span class="hidden"><div class="module-form-titles">Work Place Country:</div>
						<input type="text" name="work-place-country" placeholder="Work Place Country" value="<?php if($_POST['work-place-country'] != ''){echo $_POST['work-place-country'];}?>" />
                        <i>The country has a maximum of 150 characters.</i></span>

						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="work-start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['work-start-date'] != ''){echo $_POST['work-start-date'];}?>" />
                            <i>Please supply the date you started at the work place.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles">End Date:</div>
                            <input type="text" name="work-end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['work-end-date'] != ''){echo $_POST['work-end-date'];}?>" />
                            <i>If you still working at this work place,please leave this field blank, otherwise supply the date you finished working there.</i>
                        </div>

						<div class="clear"></div>

						<div class="module-form-titles">Work Place Website Link:</div>
						<input type="text" name="work-website" placeholder="Work Place Website Link" value="<?php if($_POST['work-website'] != ''){echo $_POST['work-website'];}?>" maxlength="250"/>
                        <i>Please supply a valid/workable link.</i>

                    </div>
					<input type="submit" class="module-form-submit" name="add_work_place" title="Add Work Place" value="Add Work Place" onclick="pleasewait()" />
                </form>

            </div>
            <!-- END PARAGRAPH HOLDER-->

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
