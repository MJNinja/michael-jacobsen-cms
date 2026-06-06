<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 4;
$colorbox = 1;
$pageTitle = 'Edit Affiliate Category';

//GET URL VARIABLE
if(isset($_POST['affCatID'])){$affCatID = $_POST['affCatID'];}else{$affCatID = $_GET['affCatID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.affiliatesManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($affCatID != ''){
	//CHECK affCatID INSIDE DATABASE
	if($affiliatesManager->checkCategoryDatabase($affCatID) == 'not found'){
		header("Location:".$cms_root."affiliates-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."affiliates-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'affiliates-manager/" title="Affiliates Manager">Affiliates Manager</a> | <span class="current">Edit Affiliate Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Affiliate Category</h1>
        <div class="intro">
        	<p>This is the <b>Edit Affiliate Category</b> page. This page will allow you to edit an existing affiliate category.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Affiliate Category</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the affiliate category. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Affiliate Category</b> to edit the category.
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
                    	<input type="hidden" name="affCatID" value="<?php echo $affCatID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $affiliatesManager->getCategoryInfo($affCatID, 'modifiedNumber')+1;?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Category Name: </div>
						<input type="text" name="category-name" placeholder="Category Name" value="<?php if($_POST['category-name'] != ''){echo $_POST['category-name'];}else{echo $affiliatesManager->getCategoryInfo($affCatID, 'affCatName');}?>" maxlength="150" />
                        <i>The category name has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Category Type: </div>
                        <input type="text" name="category-type" placeholder="Category Type" value="<?php if($_POST['category-type'] != ''){echo $_POST['category-type'];}?>" />
                        <i>Please supply the type of the category</i></span></span>
                    </div>
                    <input type="submit" class="module-form-submit" name="edit_affiliate_category" title="Edit Affiliate Category" value="Edit Affiliate Category" onclick="pleasewait()"/>
                </form>

            </div>

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Affiliate Category Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Affiliate Category</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $affiliatesManager->getUsersName($affiliatesManager->getCategoryInfo($affCatID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($affiliatesManager->getCategoryInfo($affCatID, 'modifiedBy') != 0){
									echo $affiliatesManager->getUsersName($affiliatesManager->getCategoryInfo($affCatID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($affiliatesManager->getCategoryInfo($affCatID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($affiliatesManager->getCategoryInfo($affCatID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($affiliatesManager->getCategoryInfo($affCatID, 'modifiedDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>
                        	<div class="edit-information-table-label"><b>No. of Times Modified:</b></div>
                        	<?php echo $affiliatesManager->getCategoryInfo($affCatID, 'modifiedNumber');?>
                        </td>
                      </tr>
                    </table>

                </div>
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
