<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 10;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$pageTitle = 'Add Institution';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.employmentHistoryManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'employment-history-manager/" title="Employment History Manager">Employment History Manager</a> | <a href="'.$cms_root.'employment-history-manager/manage-education-history.php" title="Manage Education History">Manage Education History</a> | <span class="current">Add Institution</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Institution</h1>
        <div class="intro">
        	<p>This is the <b>Add Institution</b> page. This page will allow you to add a new institution where you studied.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Institution</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new Institution of Study. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Institution</b> to add the new institution.
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

                    	<div class="module-form-titles"><span class="required">*</span> Institution of Study:</div>
						<input type="text" name="institution-title" placeholder="Institution of Study (School, University, College, etc)" value="<?php if($_POST['institution-title'] != ''){echo $_POST['institution-title'];}?>" maxlength="250"/>
                        <i>The Institution of Study has a maximum of 250 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Institution Type:</div>
						<input type="text" name="institution-type" placeholder="Institution Type" value="<?php if($_POST['institution-type'] != ''){echo $_POST['institution-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

                        <div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>

						<div class="module-form-titles"><span class="required">*</span> Location of Institution:</div>
						<input type="text" name="institution-location" placeholder="Location of Institution (e.g. Windhoek, Namibia)" value="<?php if($_POST['institution-location'] != ''){echo $_POST['institution-location'];}?>" maxlength="150" />
                        <i>The Location of Institution has a maximum of 150 characters.</i>

						<span class="hidden"><div class="module-form-titles">Institution Country:</div>
						<input type="text" name="institution-country" placeholder="Institution Country" value="<?php if($_POST['institution-country'] != ''){echo $_POST['institution-country'];}?>" />
                        <i>The country has a maximum of 150 characters.</i></span>

						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="institution-start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['institution-start-date'] != ''){echo $_POST['institution-start-date'];}?>" />
                            <i>Please supply the date you started at the institution.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles">End Date:</div>
                            <input type="text" name="institution-end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['institution-end-date'] != ''){echo $_POST['institution-end-date'];}?>" />
                            <i>If you still studying at this institution, please leave this field blank, otherwise supply the date you finished studying there.</i>
                        </div>

						<div class="clear"></div>

						<div class="module-form-titles">Institution Website Link:</div>
						<input type="text" name="institution-website" placeholder="Institution Website Link" value="<?php if($_POST['institution-website'] != ''){echo $_POST['institution-website'];}?>" maxlength="250"/>
                        <i>Please supply a valid/workable link.</i>

                    </div>
					<input type="submit" class="module-form-submit" name="add_institution" title="Add Institution" value="Add Institution" onclick="pleasewait()" />
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
