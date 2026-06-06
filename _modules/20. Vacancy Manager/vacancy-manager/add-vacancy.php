<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 20;
$ckeditor = 1;
$date_picker = 1;
$pageTitle = 'Add Vacancy';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.vacancyManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'vacancy-manager/" title="Staff Manager">Staff Manager</a> | <span class="current">Add Vacancy</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Vacancy</h1>
        <div class="intro">
        	<p>This is the <b>Add Vacancy</b> page. This page will allow you to add a new Vacancy.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Vacancy</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new vacancy. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Vacancy</b> to add the new vacancy.
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
                    	<div class="module-form-titles"><span class="required">*</span> Vacancy Title:</div>
						<input type="text" name="vacancy-title" placeholder="Vacancy Title" value="<?php if($_POST['vacancy-title'] != ''){echo $_POST['vacancy-title'];}?>" maxlength="150" />
                        <i>The vacancy title has a maximum of 150 characters.</i>

                        <div class="module-form-titles"><span class="required">*</span> Owner of the Vacancy:</div>
						<select name="owner">
							<option value="0">-- Select the Owner --</option>
							<?php echo $vacancyManager->getOwners($_POST['owner']); ?>
						</select>
						<i>Please select the owner of the vacancy.</i>

                        <div class="module-form-titles"><span class="required">*</span> Type of Vacancy:</div>
						<select name="type">
							<option value="0">-- Select the Vacancy Type --</option>
							<?php echo $vacancyManager->getType($_POST['type']); ?>
						</select>
						<i>Please select the vacancy type.</i>

                        <div class="module-form-titles"><span class="required">*</span> City:</div>
						<select name="city">
							<option value="0">-- Select the City --</option>
							<?php echo $vacancyManager->getCity($_POST['city']); ?>
						</select>
						<i>Please select the city.</i>

                        <div class="module-form-titles"><span class="required">*</span> Vacancy Category:</div>
						<select name="category">
							<option value="0">-- Select the Vacancy Category --</option>
							<?php echo $vacancyManager->getCategory($_POST['category']); ?>
						</select>
						<i>Please select the vacancy category.</i>

                        <div class="module-form-titles"><span class="required">*</span> Salary:</div>
						<select name="salary">
							<option value="0">-- Select the Salary --</option>
							<?php echo $vacancyManager->getSalary($_POST['salary']); ?>
						</select>
						<i>Please select the salary.</i>

						<div class="module-form-titles">Vacancy Email:</div>
						<input type="email" name="vacancy-email" placeholder="Vacancy Email" value="<?php if($_POST['vacancy-email'] != ''){echo $_POST['vacancy-email'];}?>" />
                        <i>Please supply a valid email address. This email will not be displayed on the website but used in conjuction with the application form to know where the applicants information should be send to.</i></span>

                        <span class="hidden"><div class="module-form-titles">Staff Email 2:</div>
						<input type="text" name="vacancy-email-2" placeholder="Vacancy Email 2" value="<?php if($_POST['vacancy-email-2'] != ''){echo $_POST['vacancy-email-2'];}?>" />
                        <i>Please supply a valid email address.</i></span>

						<span class="hidden"><div class="module-form-titles">Staff Contact:</div>
						<input type="text" name="vacancy-contact" placeholder="Vacancy Contact" value="<?php if($_POST['vacancy-contact'] != ''){echo $_POST['vacancy-contact'];}?>" />
                        <i>Please supply a valid contact number.</i></span>

						<div class="module-form-titles">Show Application Form:</div>
						<div class="user-module-holder">
                            <label>
                                <input type="checkbox" name="application-form" <?php if($_POST['application-form'] == 1){echo 'checked="checked"';}?> value="1">
                                <span>Show Application Form</span>
                            </label>
                        </div>
                        <i>Please check the box above if you want visitors to be able to apply for the vacancy using the application form.</i>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['start-date'] != ''){echo $_POST['start-date'];}?>">
                            <i>Please supply the start date of the vacancy.</i>
                        </div>

						<div class="module-time-input">
							<div class="module-form-titles">End Date:</div>
                            <input type="text" name="end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['end-date'] != ''){echo $_POST['end-date'];}?>">
                            <i>If no end date is supplied the vacancy will be shown for 2 weeks from the start date.</i>
                        </div>
                        <div class="clear"></div>

                        <div class="module-form-titles"><span class="required">*</span> Vacancy Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>
                    <input type="submit" class="module-form-submit" name="add_vacancy" title="Add Vacancy" value="Add Vacancy" onclick="pleasewait()" />
    			</form>

            </div>
            <!-- END PARAGRAPH HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/vacancy-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
