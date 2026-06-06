<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 9;
$colorbox = 1;
$pageTitle = 'Edit Quote Category';

//GET URL VARIABLE
if(isset($_POST['quoteCatID'])){$quoteCatID = $_POST['quoteCatID'];}else{$quoteCatID = $_GET['quoteCatID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.quotesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($quoteCatID != ''){
	//CHECK quoteCatID INSIDE DATABASE
	if($quoteManager->checkCategoryDatabase($quoteCatID) == 'not found'){
		header("Location:".$cms_root."quote-manager/");
	}
}else{
	header("Location:".$cms_root."quote-manager/");
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'quote-manager/" title="Quote Manager">Quote Manager</a> | <span class="current">Edit Quote Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Quote Category</h1>
        <div class="intro">
        	<p>This is the <b>Edit Quote Category</b> page. This page will allow you to edit an existing quote category.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Quote Category</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the quote category. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Quote Category</b> to edit the category.
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
                    	<input type="hidden" name="quoteCatID" value="<?php echo $quoteCatID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $quoteManager->getCategoryInfo($quoteCatID, 'modifiedNumber')+1;?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Category Name: </div>
						<input type="text" name="category-name" placeholder="Category Name" value="<?php if($_POST['category-name'] != ''){echo $_POST['category-name'];}else{echo $quoteManager->getCategoryInfo($quoteCatID, 'categoryName');}?>" />
                        <i>Please supply the name of the new category</i>

                        <span class="hidden"><div class="module-form-titles">Category Type: </div>
                        <input type="text" name="category-type" placeholder="Category Type" value="<?php if($_POST['category-type'] != ''){echo $_POST['category-type'];}?>" />
                        <i>Please supply the type of the category</i></span></span>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_quote_category" title="Edit Quote Category" value="Edit Quote Category" />
                </form>

            </div>
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/quote_stats_inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
require_once("../inc/javascript-inc.php")
?>
