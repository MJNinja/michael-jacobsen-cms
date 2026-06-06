<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 9;
$colorbox = 1;
$pageTitle = 'Manage Quote Category';

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'quote-manager/" title="Quote Manager">Quote Manager</a> | <span class="current">Manage Quote Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>
    
    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">
    
    	<h1 align="center">Manage Quote Category - <?php echo $quoteManager->getCategoryInfo($quoteCatID, 'categoryName');?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Quote Category</b> page. This page will allow you to add new quotes to the current category (<?php echo $quoteManager->getCategoryInfo($quoteCatID, 'categoryName');?>).</p>
            <p>To add a new Quote simply click on <b>Add Quote</b>.</p>
        </div>
        
        <div class="left-column">
            
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Quote Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>quote-manager/add-quote.php?quoteCatID=<?php echo $quoteCatID; ?>" title="Add Quote">Add Quote</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Quotes added to the current category.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active quotes.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed quotes.</div>
                    </div>
                </div>
                
                <?php echo $quoteManager->defineErrorMessages($_GET['message']); ?>
                
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Quotes</div>
                    
                    <?php echo $quoteManager->quoteArchitecture($cms_root, $quoteCatID);?>
                    
                </div>
                
                <?php if($quoteManager->checkRemovedQuotes($quoteCatID) != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Quotes</div>
                    
                      <?php echo $quoteManager->quoteArchitectureRemoved($cms_root, $quoteCatID);?>
                      
                </div>
                <?php }?>
                
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