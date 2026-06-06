<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$showCharts = 1;
$pageTitle = 'Forms Manager';

//SET TO 1 IF TOPIC CAN BE ADDED AND 0 IF NOT
$topic_add = 0;

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <span class="current">Forms Manager</span>';

//CHECK IF GEO GRPAH INFOR IS AVAILABLE
$showGeoGraph = $formsManager->geoGraphInfoAvailable();

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Forms Manager</h1>
        <div class="intro">
        	<p>This is the <b>Forms Manager</b>. This module will allow you to manage various topics of forms available on your website. You are also able to add various recipients to each form and topic.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Forms Manager</div>

                <div class="module-links"><a href="<?php echo $cms_root; ?>forms-manager/view-emails.php" title="View Emails">View Emails</a></div>

                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Forms available on your website and graphical representations of information received by the form(s) of your website.
                </div>

                <?php echo $formsManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">Forms Insight Information</div>
                    <p>
                        Below are graphical representations of information received by the contact form(s) of the website.
                    </p>

                    <input type="hidden" name="tabName" value="formsSubmitted">

                    <select name="forms-select" class="forms-select">
                        <option value="0">All Forms</option>
                        <?php echo $formsManager->getFormsForSelectStatement(); ?>
                    </select>

                    <div class="form-tab-links" title="Forms Submitted" onclick="formsSubmitted()">Forms Submitted</div>
                    <div class="form-tab-links" title="Geo Locations (Countries)" onclick="countries()">Geo Locations (Countries)</div>
                    <div class="form-tab-links" title="More Details" onclick="moreDetails()">More Details</div>

                    <div id="lineChart-holder">
                        <div class="insight-information-heading">Forms Submitted</div>
                        <canvas id="lineChart"></canvas>
                    </div>

                    <div id="country_div_holder">
                        <div class="insight-information-heading">Geo Locations (Countries)</div>

                        <?php if($showGeoGraph != 0){ ?>
                            <div id="country_div"></div>
                        <?php }else{ ?>
                            <div>There is currently no graph data to show.</div>
                        <?php } ?>
                    </div>

                    <div id="piecharts-holder"></div>


                    <div class="form-loader"><img src="<?php echo $cms_root; ?>images/basic/loader.GIF" alt="Loader"></div>

                </div>

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">Available Website Forms</div>
                    <p>
                        Below you can find a list of all the available forms on your website. You are able to add or remove recipients of a selected form by clicking on <b>Manage Recipient(s)</b> and you are also able to add new sub topics to a form by clicking on <b>Add Topic</b>.
                    </p>

                    <?php if($topic_add == 1){?>
                    <div class="module-links reduce-margin"><a href="<?php echo $cms_root; ?>forms-manager/add-topic.php" title="Add Topic">Add Topic</a></div>
                    <?php }?>

                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="45%" colspan="2">Form Names</td>
                        <td width="15%" align="center">Total Recipients</td>
                        <td width="20%" align="center">Manage Recipients</td>
                        <td width="10%" align="center">Modify</td>
                        <td width="10%" align="center">Remove</td>
                      </tr>

                      <?php echo $formsManager->formsArchitecture($cms_root, $topic_add);?>

                    </table>
                </div>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/form-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
