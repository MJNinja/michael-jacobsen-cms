<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 15;
$feedback_messages_load_more = 1;
$pageTitle = 'Feedback Manager';

//DISPLAY DATES
$infoStartDate	= date('d-M-Y', strtotime('-1 month'));
$infoEndDate	= date('d-M-Y');

//GET URL VARIABLE
if(isset($_POST['field'])){$field = $_POST['field'];}else{$field = $_GET['field'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.feedbackManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'feedback-manager/" title="Feedback Manager">Feedback Manager</a> | <span class="current">Feedback Messages</span>';

//SET TOPIC NAME
if($field == 'reasonVisit'){
    $topicName = 'What is the PRIMARY reason you came to the site?';
}elseif($field == 'whatLookingFor'){
    $topicName = 'If you did not find any or all of what you needed, please tell us what information you were looking for.';
}elseif($field == 'comments'){
    $topicName = 'Please add any comments you have for improving the website. We welcome suggestions on specific areas for improvements, features you would like to see added to the site, and examples of what you consider good websites.';
}

//AJAX FOR MESSAGES
require_once("../ajax/ajax.viewMoreFeedbackMessages.php");

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Feedback Message</h1>
        <div class="intro">
        	<p>This is the <b>Feeback Message</b> page. Here you can view all the feedback messages supplied for the selected topic <b>(<?php echo $topicName; ?>)</b> and time period.</p>
        </div>

        <div class="left-column">

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Feedback Form Message Information</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below is a list of messages received from the Feedback Forms.
                </div>

				<div class="module-form-holder">
					<strong>What is the PRIMARY reason you came to the site?</strong><br /><br />
					<div class="feedback-message-list">
						<ol>
                            <?php echo $feedbackManager->getFeedbackMessagesAll($field, $preload_content_messages); ?>
                            <div id="recent-feedback-messages"></div>
                        </ol>

                        <!-- BEGIN LOAD MORE FEEDBACK MESSAGES -->
                        <div id="recent-feedback-messages"></div>

                        <div class="load-more-loader" id="loader-feedback-messages">
                            <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
                        </div>

                        <?php
                        if($total_nums_messages > $preload_content_messages){
                            echo '<input id="loadmore-feedback-messages" type="button" class="loadmore-style" title="Load More" value="Load More"><input id="pages-feedback-messages" type="hidden" value="'.$total_pages_messages.'">';
                        }
                        ?>
                        <!-- END LOAD MORE FEEDBACK MESSAGES -->
					</div>
				</div>
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
