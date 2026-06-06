<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 21;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$time_picker = 1;
$pageTitle = 'Add Event';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.eventsManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'events-manager/" title="Events Manager">Events Manager</a> | <span class="current">Add Event</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Event</h1>
        <div class="intro">
        	<p>This is the <b>Add Event</b> page. This page will allow you to add a new event to your website.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Event</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new event. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Event</b> to add the new event.
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
                    	<div class="module-form-titles"><span class="required">*</span> Event Title:</div>
						<input type="text" name="event-title" placeholder="Event Title" value="<?php if($_POST['event-title'] != ''){echo $_POST['event-title'];}?>" maxlength="150"/>
                        <i>The event title has a maximum of 150 characters.</i>

                        <div class="module-form-titles"><span class="required">*</span> Event Venue</div>
						<input type="text" name="event-venue" placeholder="Event Venue" value="<?php if($_POST['event-venue'] != ''){echo $_POST['event-venue'];}?>" maxlength="200"/>
                        <i>The event venue has a maximum of 200 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="event-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['event-paragraph'] != ''){echo $_POST['event-paragraph'];}?></textarea>
                        <i>Please supply an intro for the event.</i></span>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['start-date'] != ''){echo $_POST['start-date'];}?>" />
                            <i>Please supply the start date of the event.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Time:</div>
                            <input type="text" name="start-time" id="timepicker" placeholder="Start Time" value="<?php if($_POST['start-time'] != ''){echo $_POST['start-time'];}?>" />
                            <i>Please supply the start time of the event.</i>
                        </div>
                        <div class="clear"></div>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> End Date:</div>
                            <input type="text" name="end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['end-date'] != ''){echo $_POST['end-date'];}?>" />
                            <i>Please supply the end date of the event.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> End Time:</div>
                            <input type="text" name="end-time" id="timepicker2" placeholder="End Time" value="<?php if($_POST['end-time'] != ''){echo $_POST['end-time'];}?>" />
                            <i>Please supply the end time of the event.</i>
                        </div>
                        <div class="clear"></div>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Event Image</div>
                	<p>
						An image can be linked to the Event by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Event has been uploaded.
                    </p>

                    <div class="module-form-titles">Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles">Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>

				<input type="submit" class="module-form-submit" name="add_event" title="Add Event" value="Add Event" onclick="pleasewait()" />
			</form>
            </div>
            <!-- END IMAGE HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/event-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
