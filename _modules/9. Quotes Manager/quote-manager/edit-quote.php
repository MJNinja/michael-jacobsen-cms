<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 9;
$colorbox = 1;
$pageTitle = 'Edit Quote';

//GET URL VARIABLE
if(isset($_POST['quoteID'])){$quoteID = $_POST['quoteID'];}else{$quoteID = $_GET['quoteID'];}
if(isset($_POST['quoteCatID'])){$quoteCatID = $_POST['quoteCatID'];}else{$quoteCatID = $_GET['quoteCatID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.quotesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($quoteCatID != '' || $quoteID != ''){
	//CHECK quoteID AND quoteCatID INSIDE DATABASE
	if($quoteManager->checkCategoryQuoteDatabase($quoteID, $quoteCatID) == 'not found'){
		header("Location:".$cms_root."quote-manager/");
	}
}else{
	header("Location:".$cms_root."quote-manager/");
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'quote-manager/" title="Quote Manager">Quote Manager</a> | <a href="'.$cms_root.'quote-manager/manage-quote-category-content.php?quoteCatID='.$quoteCatID.'" title="Manage Quote Category">Manage Quote Category</a> | <span class="current">Edit Quote</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Quote - <?php echo $quoteManager->getCategoryInfo($quoteCatID, 'categoryName');?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Quote </b> page. This page will allow you to edit this quote in the current category (<?php echo $quoteManager->getCategoryInfo($quoteCatID, 'categoryName');?>).</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Quote</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the quote category. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Quote</b> to edit the quote.
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
                        <input type="hidden" name="quoteID" value="<?php echo $quoteID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $quoteManager->getQuoteInfo($quoteID, 'modifiedNumber')+1;?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Author Name: </div>
						<input type="text" name="author-name" placeholder="Author Name" value="<?php if($_POST['author-name'] != ''){echo $_POST['author-name'];}else{ echo $quoteManager->getQuoteInfo($quoteID, 'quoteBy');}?>" />
                        <i>Please supply the name of the person who said the quote</i>

                        <div class="module-form-titles"><span class="required">*</span> Quote: </div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Quote"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{ echo $quoteManager->getQuoteInfo($quoteID, 'quoteText');}?></textarea>
                        <i>Please supply the quote from the author</i>

                        <span class="hidden"><div class="module-form-titles">Quote Type: </div>
                        <input type="text" name="quote-type" placeholder="Quote Type" value="<?php if($_POST['quote-type'] != ''){echo $_POST['quote-type'];}?>" />
                        <i>Please supply the type of the quote</i></span>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_quote" title="Edit Quote" value="Edit Quote" />
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
