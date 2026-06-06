<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 99;
$date_picker = 1;
$email_filter_tags = 1;
$email_filter_load_more = 1;
$pageTitle = 'View Emails';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.formsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//GET VARIABLES
//CUSTOMER NAMES
if($_GET['customer_names'] != '' && $_GET['customer_names'] != ' '){
    $customer_names     = $formsManager->escape($_GET['customer_names']);
}else{
    $customer_names     = $formsManager->escape($_POST['customer_names']);
}

//CUSTOMER EMAILS
if($_GET['customer_emails'] != '' && $_GET['customer_emails'] != ' '){
    $customer_emails    = $formsManager->escape($_GET['customer_emails']);
}else{
    $customer_emails    = $formsManager->escape($_POST['customer_emails']);
}

//FORM SELECTED
if($_GET['forms-select'] != '' && $_GET['forms-select'] != ''){
    $filter_form_select = $formsManager->escape($_GET['forms-select']);
}else{
    $filter_form_select = $formsManager->escape($_POST['forms-select']);
}

//START DATE
if($_GET['filter-start-date'] != '' && $_GET['filter-start-date'] != ' '){
    $filter_start_date  = $formsManager->escape($_GET['filter-start-date']);
}else{
    $filter_start_date  = $formsManager->escape($_POST['filter-start-date']);
}

//END DATE
if($_GET['filter-end-date'] != '' && $_GET['filter-end-date'] != ' '){
    $filter_end_date    = $formsManager->escape($_GET['filter-end-date']);
}else{
    $filter_end_date    = $formsManager->escape($_POST['filter-end-date']);
}

//ORDER
if($_GET['forms-order'] != '' && $_GET['forms-order'] != ' '){
    $filter_order       = $formsManager->escape($_GET['forms-order']);
}else{
    $filter_order       = $formsManager->escape($_POST['forms-order']);
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> > <a href="'.$cms_root.'forms-manager/" title="Forms Manager">Forms Manager</a> > <span class="current">View Emails</span>';

//AJAX FOR EMAILS
require_once("../ajax/ajax.viewMoreEmailResults.php");

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">View Emails</h1>
        <div class="intro">
        	<p>This is the <b>View Emails</b> page. This page will allow you to view all emails that have been send through the website.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;View Emails</div>


                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Emails that have been submitted through you website, depending on how you have set up the filter.
                </div>

                <?php echo $formsManager->defineErrorMessages($_GET['message']); ?>

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

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">View Emails Filter</div>
                    <p>
                        The below filter will allow you to search for emails depending on certain parameters.
                    </p>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="customer_names" value="" />
                        <input type="hidden" name="customer_emails" value="" />

                        <div class="filter-holder">
                            <select name="forms-select" class="forms-select">
                                <option value="0">All Forms</option>
                                <?php echo $formsManager->getFormsForFilter($filter_form_select); ?>
                            </select>

                            <input class="input-field" type="text" name="filter-start-date" id="datepicker" placeholder="Start Date" value="<?php if($filter_start_date != '' && $filter_start_date != ' '){ echo $filter_start_date; } ?>">

                            <input class="input-field" type="text" name="filter-end-date" id="datepicker2" placeholder="End Date" value="<?php if($filter_end_date != '' && $filter_end_date != ' '){ echo $filter_end_date; } ?>">

                            <select name="forms-order" class="forms-select">
                                <option value="desc" <?php if($filter_order == 'desc'){ echo 'selected="selected"'; }?>>Newest to Oldest</option>
                                <option value="asc" <?php if($filter_order == 'asc'){ echo 'selected="selected"'; }?>>Oldest to Newest</option>
                            </select>
                        </div>

                        <div class="filter-holder">
                            <ul id="customer_name_tags">
                                <?php if($_GET['customer_names'] != ''){ echo $formsManager->generatePostedTags($customer_names);}?>
                            </ul>

                            <ul id="email_tags">
                                <?php if($_GET['customer_emails'] != ''){ echo $formsManager->generatePostedTags($customer_emails);}?>
                            </ul>
                        </div>

                        <input type="submit" class="module-form-submit" name="filter-emails" title="Filter Emails" value="Filter Emails" onclick="pleasewait()" />
                    </form>

                </div>

            </div>

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Email Results</div>
                	<p>
                        These are the email result that have been found according to your filter settings.
                    </p>
                    <br />

                    <?php echo $formsManager->getEmailResults($customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order, $preload_content_emails); ?>

                    <!-- BEGIN LOAD MORE EMAILS -->
                    <div id="recent-emails"></div>

                    <div class="load-more-loader" id="loader-emails">
                        <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
                    </div>

                    <?php
                    if($total_nums_emails > $preload_content_emails){
                        echo '<input id="loadmore-emails" type="button" class="loadmore-style" title="Load More" value="Load More"><input id="pages-emails" type="hidden" value="'.$total_pages_emails.'">';
                    }
                    ?>
                    <div class="clear"></div>
                    <!-- END LOAD MORE EMAILS -->
                </div>
            </div>
            <!-- END IMAGE HOLDER-->
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
