<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 4;
$colorbox = 1;
$pageTitle = 'Add Affiliate Category';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.affiliatesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'affiliates-manager/" title="Affiliates Manager">Affiliates Manager</a> | <span class="current">Add Affiliate Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Affiliate Category</h1>
        <div class="intro">
        	<p>This is the <b>Add Affiliate Category</b> page. This page will allow you to add a new affiliate category.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Affiliate Category</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new affiliate category. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Affiliate Category</b> to add the new category.
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
                    	<div class="module-form-titles"><span class="required">*</span> Category Name: </div>
						<input type="text" name="category-name" placeholder="Category Name" value="<?php if($_POST['category-name'] != ''){echo $_POST['category-name'];}?>" maxlength="150" />
                        <i>The category name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Category Type: </div>
                        <input type="text" name="category-type" placeholder="Category Type" value="<?php if($_POST['category-type'] != ''){echo $_POST['category-type'];}?>" />
                        <i>Please supply the type of the category</i></span>
                    </div>
                    <input type="submit" class="module-form-submit" name="add_affiliate_category" title="Add Affiliate Category" value="Add Affiliate Category" onclick="pleasewait()" />
                </form>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/affiliates-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
