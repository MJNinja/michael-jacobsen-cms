<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 15;
$date_picker = 1;
$showFeedbackCharts = 1;
$pageTitle = 'Feedback Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.feedbackManager.php");

//SET LOGIN SESSION TIME
if($_SESSION['cmsUpdateFeedbackPeriod'] == ''){
	$_SESSION['cmsUpdateFeedbackPeriod'] = date('Y-m-d H:i:s');
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Feedback Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Feedback Manager</h1>
        <div class="intro">
        	<p>This is the <b>Feeback Manager</b>. This module will allow you to you to manage the feedback that was given about your website.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Feedback Form Period</div>
				<div class="module-links">
					<a href="<?php echo $cms_root; ?>feedback-manager/" title="Refresh Information" onclick="pleasewait()">Refresh Information</a>
				</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can set the period of when the Feedback Form should be available.
                </div>

                <?php echo $feedbackManager->defineErrorMessages($_GET['message']); ?>

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
                    <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                    <input type="hidden" name="modifiedNumber" value="<?php echo $feedbackManager->getFeedbackInfo('modifiedNumber')+1;?>"/>
                    <div class="module-form-holder">
						<?php
						//SET FEEDBACK START DATE
						if($_POST['feed-start-date'] != '' && $_POST['feed-start-date'] != ' '){
							$feedbackStartDate	= $_POST['feed-start-date'];
						}elseif($feedbackManager->getFeedbackInfo('startDate') != '0000-00-00'){
							$feedbackStartDate	= $feedbackManager->getFeedbackInfo('startDate');
						}

						//SET FEEDBACK END DATE
						if($_POST['feedback-end-date'] != '' && $_POST['feedback-end-date'] != ' '){
							$feedbackEndDate	= $_POST['feedback-end-date'];
						}elseif($feedbackManager->getFeedbackInfo('endDate') != '0000-00-00'){
							$feedbackEndDate	= $feedbackManager->getFeedbackInfo('endDate');
						}
						?>
                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Start Date:</div>
                            <input type="text" name="feed-start-date" id="datepicker" placeholder="Start Date" value="<?php echo $feedbackStartDate; ?>">
                            <i>Please supply the start date of the feedback form.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles">End Date:</div>
                            <input type="text" name="feedback-end-date" id="datepicker2" placeholder="End Date" value="<?php echo $feedbackEndDate; ?>">
                            <i>Please supply the end date of the feedback form.</i>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <input type="submit" class="module-form-submit" name="update_feedback_period" title="Update Feedback Period" value="Update Feedback Period" onclick="pleasewait()" />
                </form>

            </div>

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Feedback Form Chart Information</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below is a graphical representation of the information received from the Feedback Forms.
                </div>

                <div class="module-form-holder">
                    <div class="module-date-input">
						<strong>Is this the first time you have visited the website?</strong><br /><br />
						<canvas id="visitBarChart" class="feedback-canvas-size"></canvas>
                    </div>

                    <div class="module-time-input">
						<strong>Did you find what you needed?</strong><br /><br />
						<canvas id="findBarChart" class="feedback-canvas-size"></canvas>
                    </div>
                    <div class="clear"></div>
                </div>

				<div class="module-form-holder">
                    <div class="module-date-input">
						<strong>Please tell us how easy it is to find information on the site.</strong><br /><br />
						<canvas id="easyFindBarChart" class="feedback-canvas-size"></canvas>
                    </div>

                    <div class="module-time-input">
						<strong>What is your overall professional impression of the site?</strong><br /><br />
						<canvas id="professionalBarChart" class="feedback-canvas-size"></canvas>
                    </div>
                    <div class="clear"></div>
                </div>

				<div class="module-form-holder">
                    <div class="module-date-input">
						<strong>What is your overall informative impression of the site?</strong><br /><br />
						<canvas id="informativeBarChart" class="feedback-canvas-size"></canvas>
                    </div>

                    <div class="module-time-input">
						<strong>What is your overall visually pleasing impression of the site?</strong><br /><br />
						<canvas id="visuallyPleasingBarChart" class="feedback-canvas-size"></canvas>
                    </div>
                    <div class="clear"></div>
                </div>

				<div class="module-form-holder">
                    <div class="module-date-input">
						<strong>What is the likelihood that you will visit the website again?</strong><br /><br />
						<canvas id="visitAgainBarChart" class="feedback-canvas-size"></canvas>
                    </div>
                    <div class="clear"></div>
                </div>

            </div>

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Feedback Form Message Information</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below is a list of messages received from the Feedback Forms.
                </div>

				<div class="module-form-holder">
					<strong>What is the PRIMARY reason you came to the site?</strong><br /><br />
					<div class="feedback-message-list">
						<ol><?php echo $feedbackManager->getFeedbackMessagesLimited('reasonVisit'); ?></ol>
					</div>
				</div>

				<a href="<?php echo $cms_root; ?>feedback-manager/messages.php?field=reasonVisit" class="module-form-submit" name="add_topic" title="View All Messages" onclick="pleasewait()" />View All Messages</a>

				<div class="module-form-holder">
					<strong>If you did not find any or all of what you needed, please tell us what information you were looking for.</strong><br /><br />
					<div class="feedback-message-list">
						<ol><?php echo $feedbackManager->getFeedbackMessagesLimited('whatLookingFor'); ?></ol>
					</div>
				</div>

				<a href="<?php echo $cms_root; ?>feedback-manager/messages.php?field=whatLookingFor" class="module-form-submit" name="add_topic" title="View All Messages" onclick="pleasewait()" />View All Messages</a>

				<div class="module-form-holder">
					<strong>Please add any comments you have for improving the website. We welcome suggestions on specific areas for improvements, features you would like to see added to the site, and examples of what you consider good websites.</strong><br /><br />
					<div class="feedback-message-list">
						<ol><?php echo $feedbackManager->getFeedbackMessagesLimited('comments'); ?></ol>
					</div>
				</div>

				<a href="<?php echo $cms_root; ?>feedback-manager/messages.php?field=comments" class="module-form-submit" name="add_topic" title="View All Messages" onclick="pleasewait()" />View All Messages</a>
            </div>

        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/feedback-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
