<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 21;
$event_load_more = 1;
$pageTitle = 'Events Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.eventsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Events Manager</span>';

//AJAX FOR BLOG POSTS
require_once("../ajax/ajax.viewMoreEvents.php");

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Events Manager</h1>
        <div class="intro">
        	<p>This is the <b>Events Manager</b>. This module will allow you to manage all your Events that you have on your website.</p>
            <p>In order to create an Event click the <b>Add Event</b> button.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Events Architecture</div>
                <div class="module-links">
                    <a href="<?php echo $cms_root; ?>events-manager/add-event.php" title="Add Event">Add Event</a>
                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all Events.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active event.</div>
                        <div class="clear"></div>
                        <div class="partially-user-key"></div><div class="partially-user-key-description">Indicates scheduled events.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates expired events.</div>
                    </div>
                </div>

                <?php echo $eventManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">Active Events</div>
                    <table width="100%" class="module-architecture-table">
    	                <tr class="module-architecture-header">
                            <td width="1%"></td>
                            <td width="30%">Event Title</td>
                            <td width="15%" align="center">Start Date</td>
                            <td width="15%" align="center">End Date</td>
                            <td width="13%" align="center">Manage</td>
                            <td width="13%" align="center">Modify</td>
                            <td width="13%" align="center">Remove</td>
                        </tr>

                        <?php echo $eventManager->eventArchitecture($preload_content_events, $cms_root);?>

                    </table>

                    <!-- BEGIN LOAD MORE EVENTS -->
                    <div id="recent-events"></div>

                    <div class="load-more-loader" id="loader-events">
                        <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
                    </div>

                    <?php
                    if($total_nums_events > $preload_content_events){
                        echo '<input id="loadmore-events" type="button" class="loadmore-style" title="Load More" value="Load More"><input id="pages-events" type="hidden" value="'.$total_pages_events.'">';
                    }
                    ?>
                    <div class="clear"></div>
                    <!-- END LOAD MORE EVENTS -->
                </div>

                <?php if($eventManager->checkRemovedEvents() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Events</div>
                    <table width="100%" class="module-architecture-table">
                        <tr class="module-architecture-header">
                            <td width="1%"></td>
                            <td width="40%">Event Title</td>
                            <td width="15%" align="center">Start Date</td>
                            <td width="15%" align="center">End Date</td>
							<td width="18%" align="center">Delete Permanently</td>
                            <td width="13%" align="center">Recover</td>
                        </tr>

                      	<?php echo $eventManager->eventArchitectureRemoved($cms_root);?>

                    </table>
                </div>
                <?php }?>

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
