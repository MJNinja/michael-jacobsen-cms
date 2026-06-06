<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 9;
$pageTitle = 'Quote Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.quotesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Quote Manager</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>
    
    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">
    
    	<h1 align="center">Quote Manager</h1>
        <div class="intro">
        	<p>This is the <b>Quote Manager</b>. This module will allow you to manage all Quotes you want to publish on your website.</p>
            <p>You firstly have to create a category for your Quotes by clicking on <b>Add Quote Category</b>. In the created category you will then be able to add, edit &amp; delete your quotes.</p>
        </div>
        
        <div class="left-column">
            
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Quote Categories Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>quote-manager/add-quote-category.php" title="Add Quote Category">Add Quote Category</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Quote Categories.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active categories.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed categories.</div>
                    </div>
                </div>
                
                <?php echo $quoteManager->defineErrorMessages($_GET['message']); ?>
                
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Active Categories</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                      	<td width="1%"></td>
                        <td width="59%">Category Name</td>
                        <td width="16%" align="center">Manage Content</td>
                        <td width="12%" align="center">Modify</td>
                        <td width="12%" align="center">Remove</td>
                      </tr>
                      
                      <?php echo $quoteManager->categoryArchitecture($cms_root);?>
                      
                    </table>
                </div>
                
                <?php if($quoteManager->checkRemovedCategories() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Categories</div>
                    <table width="100%" class="module-architecture-table">
                      <tr class="module-architecture-header">
                        <td width="1%"></td>
                        <td width="75%">Category Name</td>
                        <td width="24%" align="center">Recover</td>
                      </tr>
                      
                      <?php echo $quoteManager->categoryArchitectureRemoved($cms_root);?>
                      
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