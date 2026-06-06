<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 21;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$time_picker = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Event';

//GET URL VARIABLE
if(isset($_POST['eventID'])){$eventID = $_POST['eventID'];}else{$eventID = $_GET['eventID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.eventsManager.php");

//REDIRECT PAGE
if($eventID != ''){
	//CHECK eventID INSIDE DATABASE
	if($eventManager->checkEventDatabase($eventID) == 'not found'){
		header("Location:".$cms_root."events-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."events-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'events-manager/" title="Events Manager">Events Manager</a> | <span class="current">Edit Event</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Event - <?php echo $eventManager->getEventInfo($eventID, 'eventTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Event</b> page. This page will allow you to edit this eventy (<?php echo $eventManager->getEventInfo($eventID, 'eventTitle'); ?>).</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Event</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the event. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Event</b> to edit the event.
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
                        <input type="hidden" name="eventID" value="<?php echo $eventID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $eventManager->getEventInfo($eventID, 'modifiedNumber')+1; ?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $eventManager->getEventInfo($eventID, 'eventImageFile'); ?>"/>

                    	<div class="module-form-titles"><span class="required">*</span> Event Title:</div>
						<input type="text" name="event-title" placeholder="Event Title" value="<?php if($_POST['event-title'] != ''){echo $_POST['event-title'];}else{ echo $eventManager->getEventInfo($eventID, 'eventTitle'); } ?>" maxlength="150"/>
                        <i>The event title has a maximum of 150 characters.</i>

						<div class="module-form-titles"><span class="required">*</span> Event Venue</div>
						<input type="text" name="event-venue" placeholder="Event Venue" value="<?php if($_POST['event-title'] != ''){echo $_POST['event-title'];}else{ echo $eventManager->getEventInfo($eventID, 'venue'); } ?>" maxlength="200"/>
                        <i>The event venue has a maximum of 200 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="event-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['event-paragraph'] != ''){echo $_POST['event-paragraph'];}?></textarea>
                        <i>Please supply an intro for the event.</i></span>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="start-date" id="datepicker" placeholder="Start Date" value="<?php if($_POST['start-date'] != ''){echo $_POST['start-date'];}else{ echo $eventManager->getEventDateTimeInfo($eventID, 'date', 'startDate'); } ?>" />
                            <i>Please supply the start date of the event.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Time:</div>
                            <input type="text" name="start-time" id="timepicker" placeholder="Start Time" value="<?php if($_POST['start-time'] != ''){echo $_POST['start-time'];}else{ echo $eventManager->getEventDateTimeInfo($eventID, 'time', 'startDate'); } ?>" />
                            <i>Please supply the start time of the event.</i>
                        </div>
						<div class="clear"></div>

						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> End Date:</div>
                            <input type="text" name="end-date" id="datepicker2" placeholder="End Date" value="<?php if($_POST['end-date'] != ''){echo $_POST['end-date'];}else{ echo $eventManager->getEventDateTimeInfo($eventID, 'date', 'endDate'); } ?>" />
                            <i>Please supply the end date of the event.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> End Time:</div>
                            <input type="text" name="end-time" id="timepicker2" placeholder="End Time" value="<?php if($_POST['end-time'] != ''){echo $_POST['end-time'];}else{ echo $eventManager->getEventDateTimeInfo($eventID, 'time', 'endDate'); } ?>" />
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
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Event".
                    </p>

                    <?php echo $eventManager->getEventImage($eventID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $eventManager->getEventInfo($eventID, 'eventImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="edit_event" title="Edit Event" value="Edit Event" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Event Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Event</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $eventManager->getUsersName($eventManager->getEventInfo($eventID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($eventManager->getEventInfo($eventID, 'modifiedBy') != 0){
									echo $eventManager->getUsersName($eventManager->getEventInfo($eventID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($eventManager->getEventInfo($eventID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($eventManager->getEventInfo($eventID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($eventManager->getEventInfo($eventID, 'modifiedDate')));
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
                        	<?php echo $eventManager->getEventInfo($eventID, 'modifiedNumber');?>
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
