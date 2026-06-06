<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$colorbox = 1;
$ckeditor = 1;
$paragraph_image_enlarge = 1;
$categoryRatio = 1;
$pageTitle = 'Edit Product Category';

//GET URL VARIABLE
if(isset($_POST['productCatID'])){$productCatID = $_POST['productCatID'];}else{$productCatID = $_GET['productCatID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($productCatID != ''){
	//CHECK productCatID INSIDE DATABASE
	if($productManager->checkCategoryDatabase($productCatID) == 'not found'){
		header("Location:".$cms_root."product-manager/manage-product-category.php");
		exit;
	}
}else{
	header("Location:".$cms_root."product-manager/manage-product-category.php");
	exit;
}

//SET LOGIN SESSION TIME
if($_SESSION['cmsEditProductCategory'] == ''){
	$_SESSION['cmsEditProductCategory'] = date('Y-m-d H:i:s');
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'product-manager/" title="Product Manager">Product Manager</a> | <a href="'.$cms_root.'product-manager/manage-product-category.php" title="Manage Product Categories">Manage Product Categories</a> | <span class="current">Edit Product Category</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");

?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Product Category</h1>
        <div class="intro">
        	<p>This is the <b>Edit Product Category</b> page. This page will allow you to edit an existing Product Category.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Product Category</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the Product Category. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Product Category</b> to edit the category.
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
                    	<input type="hidden" name="productCatID" value="<?php echo $productCatID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $productManager->getCategoryInfo($productCatID, 'modifiedNumber')+1;?>"/>
                        <input type="hidden" name="oldImage" value="<?php echo $productManager->getCategoryInfo($productCatID, 'catImage');?>"/>

                        <div class="module-form-titles"><span class="required">*</span> Product Category Title: </div>
						<input type="text" name="product-category-title" placeholder="Product Category Title" value="<?php if($_POST['product-category-title'] != ''){echo $_POST['product-category-title'];}else{echo $productManager->getCategoryInfo($productCatID, 'categoryName');}?>" maxlength="150" />
                        <i>The Blog Category Title has a maximum of 150 characters.</i>

                        <span class="hidden"><div class="module-form-titles">Product Type:</div>
						<input type="text" name="product-type" placeholder="Product Type" value="<?php if($_POST['product-type'] != ''){echo $_POST['product-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> Description:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $productManager->getCategoryInfo($productCatID, 'categoryDescription');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>
            </div>

            <!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Product Category Image</div>
                	<p>
                        An image has to be linked to the Product Category by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the  Product Category has been uploaded.
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Product Category".
                    </p>

                    <?php echo $productManager->getCategoryImage($productCatID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $productManager->getCategoryInfo($productCatID, 'catImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
                <input type="submit" class="module-form-submit" name="edit_product_category" title="Edit Product Category" value="Edit Product Category" onclick="pleasewait()"/>
            </form>
            </div>
            <!-- END IMAGE HOLDER-->

			<div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Product Category Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Product Category</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $productManager->getUsersName($productManager->getCategoryInfo($productCatID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($productManager->getCategoryInfo($productCatID, 'modifiedBy') != 0){
									echo $productManager->getUsersName($productManager->getCategoryInfo($productCatID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($productManager->getCategoryInfo($productCatID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($productManager->getCategoryInfo($productCatID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($productManager->getCategoryInfo($productCatID, 'modifiedDate')));
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
                        	<?php echo $productManager->getCategoryInfo($productCatID, 'modifiedNumber');?>
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
                	<?php include_once("../inc/product-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
