<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$product_tags = 1;
$special_check = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Product';

//GET URL VARIABLE
if(isset($_POST['productID'])){$productID = $_POST['productID'];}else{$productID = $_GET['productID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//REDIRECT PAGE
if($productID != ''){
	//CHECK productID INSIDE DATABASE
	if($productManager->checkProductDatabase($productID) == 'not found'){
		header("Location:".$cms_root."product-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."product-manager/");
	exit;
}

//SET LOGIN SESSION TIME
if($_SESSION['cmsEditProduct'] == ''){
	$_SESSION['cmsEditProduct'] = date('Y-m-d H:i:s');
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'product-manager/" title="Product Manager">Product Manager</a> | <span class="current">Edit Product</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Product - <?php echo $productManager->getProductInfo($productID, 'productTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Product</b> page. This page will allow you to edit this product in the current category (<?php echo $productManager->getProductInfo($productID, 'productTitle'); ?>).</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Product</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the roduct. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Product</b> to edit the product.
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
						<input type="hidden" name="categories" value="">
                        <input type="hidden" name="productID" value="<?php echo $productID; ?>"/>
						<input type="hidden" name="product_tags" value="<?php echo $product_tags; ?>" />
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $productManager->getProductInfo($productID, 'modifiedNumber')+1; ?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $productManager->getProductInfo($productID, 'productImageFile'); ?>"/>

                    	<div class="module-form-titles"><span class="required">*</span> Product Title:</div>
						<input type="text" name="product-title" placeholder="Product Title" value="<?php if($_POST['product-title'] != ''){echo $_POST['product-title'];}else{ echo $productManager->getProductInfo($productID, 'productTitle'); } ?>" maxlength="150"/>
                        <i>The product title has a maximum of 150 characters.</i>

						<div class="module-form-titles"><span class="required">*</span> Product Code:</div>
						<input type="text" name="product-code" placeholder="Product Code" value="<?php if($_POST['product-code'] != ''){echo $_POST['product-code'];}else{ echo $productManager->getProductInfo($productID, 'productCode'); } ?>" maxlength="150"/>
                        <i>Please supply the product code.</i>

                        <div class="module-form-titles"><span class="required">*</span> Intro:</div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Intro"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{ echo $productManager->getProductInfo($productID, 'productIntro'); } ?></textarea>
                        <i>The product intro requires a minimum of 10 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="product-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['product-paragraph'] != ''){echo $_POST['product-paragraph'];}?></textarea>
                        <i>Please supply an intro for the product.</i></span>

						<?php if($product_tags == 1){ ?>
                        <div class="module-form-titles"><span class="required">*</span> Product Categories:</div>
                        <ul id="category_tags">
                            <?php if($_POST['categories'] != ''){ echo $productManager->generatePostedTags($_POST['categories']);}else{ echo $productManager->getProductTags($productID, 'productCatID');}?>
                        </ul>
                        <i>Please supply all the categories under which this product falls under.</i>
						<?php }else{ ?>
						<div class="module-form-titles"><span class="required">*</span> Product Category:</div>
						<select name="product-category">
							<option value="0">-- Select a Category --</option>
							<?php
								if($_POST['product-category'] != ''){
									$product_category	= $_POST['product-category'];
								}else{
									$productCatID		= $productManager->getProductInfo($productID, 'productMainCatID');
									$product_category	= substr($productCatID, 1, -1);
								}
								echo $productManager->getProductCategoriesForProduct($product_category);
							?>
						</select>
						<i>Please supply the category under which this product falls under.</i>

						<div class="load-more-loader hidden" id="loader-product-cat">
	                        <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
	                    </div>

						<span class="product-sub-cat-holder">
							<div class="module-form-titles"><span class="required">*</span> Product Sub Category:</div>
	                        <select name="product-sub-category">
								<option value="0">-- Select a Sub Category --</option>
								<?php
									if($_POST['product-category'] != '' && $_POST['product-category'] != ' ' && $_POST['product-category'] != 0){
										$mainCatID	= $_POST['product-category'];
										$subCatID	= $_POST['product-sub-category'];
									}else{
										$mainCatID	= $productManager->getProductInfo($productID, 'productMainCatID');
										$mainCatID	= substr($productCatID, 1, -1);
										$subCatID	= $productManager->getProductInfo($productID, 'productCatID');
										$subCatID	= substr($productCatID, 1, -1);
									}
									echo $productManager->getProductSubCategories($mainCatID, $subCatID);
								?>
							</select>
	                        <i>Please supply the sub category under which this product falls under.</i>
						</span>

						<?php }?>

						<div class="clear"></div>

						<div class="module-form-titles">On Special:</div>
						<?php
						if($_POST['product-special'] == 1){
							$checked = 'checked="checked"';
						}elseif($productSpecial	= $productManager->getProductInfo($productID, 'productSpecial') == 1){
							$checked = 'checked="checked"';
						}
						?>
						<label><input type="checkbox" name="product-special" value="1" <?php echo $checked; ?>/> On Special</label>
                        <i>Check the checkbox should this product be on special.</i>

						<div class="special-date-holder <?php if($checked != 'checked="checked"'){ echo 'hidden'; }?>">
							<?php
							if($_POST['special-end-date'] != ''){
								$specialDate = $_POST['special-end-date'];
							}elseif($productManager->getProductInfo($productID, 'productSpecialDate') == '0000-00-00'){
								$specialDate = '';
							}else{
								$specialDate = $productManager->getProductInfo($productID, 'productSpecialDate');
							}
							?>
							<div class="module-date-input">
	                            <div class="module-form-titles"><span class="required">*</span> Special End Date:</div>
	                            <input type="text" name="special-end-date" id="datepicker" placeholder="Special End Date" value="<?php echo $specialDate; ?>">
	                            <i>Please supply the start date of the banner.</i>
	                        </div>
							<div class="clear"></div>
						</div>

						<div class="module-form-titles">Product Price:</div>
	                    <input type="text" name="product-price" placeholder="Product Price" value="<?php if($_POST['product-price'] != ''){echo $_POST['product-price'];}else{ echo $productManager->getProductInfo($productID, 'productPrice'); } ?>" />
	                    <i>Only supply if you want to add a price to the product. (e.g. 100.00)</i>

						<div class="module-form-titles">Product Brand:</div>
						<input type="text" name="product-brand" placeholder="Product Brand" value="<?php if($_POST['product-brand'] != ''){echo $_POST['product-brand'];}else{ echo $productManager->getProductInfo($productID, 'productBrand'); } ?>" />
						<i>Please supply the product brand.</i>

						<div class="module-form-titles">Manufacturer Link:</div>
	                    <input type="text" name="product-manufacturer" placeholder="Product Manufacturer" value="<?php if($_POST['product-manufacturer'] != ''){echo $_POST['product-manufacturer'];}else{ echo $productManager->getProductInfo($productID, 'manufacturerLink'); } ?>" />
	                    <i>Supply a valid link to the manufacturer of the product.</i>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Product Image</div>
                	<p>
                        An image has to be linked to the Product by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Product has been uploaded.
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Product".
                    </p>

                    <?php echo $productManager->getProductImage($productID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $productManager->getProductInfo($productID, 'productImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="edit_product" title="Edit Product" value="Edit Product" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Product Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Product</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $productManager->getUsersName($productManager->getProductInfo($productID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($productManager->getProductInfo($productID, 'modifiedBy') != 0){
									echo $productManager->getUsersName($productManager->getProductInfo($productID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($productManager->getProductInfo($productID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($productManager->getProductInfo($productID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($productManager->getProductInfo($productID, 'modifiedDate')));
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
                        	<?php echo $productManager->getProductInfo($productID, 'modifiedNumber');?>
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
