<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 11;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$product_tags = 1;
$special_check = 1;
$pageTitle = 'Add Product';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.productManager.php");

//SET LOGIN SESSION TIME
if($_SESSION['cmsAddProduct'] == ''){
	$_SESSION['cmsAddProduct'] = date('Y-m-d H:i:s');
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'product-manager/" title="Product Manager">Product Manager</a> | <span class="current">Add Product</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Product</h1>
        <div class="intro">
        	<p>This is the <b>Add Product</b> page. This page will allow you to add a new product to a category.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Product</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new product. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Product</b> to add the new product.
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
						<input type="hidden" name="categories" value="" />
						<input type="hidden" name="product_tags" value="<?php echo $product_tags; ?>" />
                    	<div class="module-form-titles"><span class="required">*</span> Product Title:</div>
						<input type="text" name="product-title" placeholder="Product Title" value="<?php if($_POST['product-title'] != ''){echo $_POST['product-title'];}?>" maxlength="150"/>
                        <i>The product title has a maximum of 150 characters.</i>

						<div class="module-form-titles"><span class="required">*</span> Product Code:</div>
						<input type="text" name="product-code" placeholder="Product Code" value="<?php if($_POST['product-code'] != ''){echo $_POST['product-code'];}?>" maxlength="150"/>
                        <i>Please supply the product code.</i>

                        <div class="module-form-titles"><span class="required">*</span> Intro:</div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Intro"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>The product intro requires a minimum of 10 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="product-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['product-paragraph'] != ''){echo $_POST['product-paragraph'];}?></textarea>
                        <i>Please supply an intro for the product.</i></span>

						<?php if($product_tags == 1){ ?>
                        <div class="module-form-titles"><span class="required">*</span> Product Categories:</div>
                        <ul id="category_tags">
                            <?php if($_POST['categories'] != ''){ echo $productManager->generatePostedTags($_POST['categories']);}?>
                        </ul>
                        <i>Please supply all the categories under which this product falls under.</i>
						<?php }else{ ?>
                        <div class="module-form-titles"><span class="required">*</span> Product Category:</div>
                        <select name="product-category">
							<option value="0">-- Select a Category --</option>
							<?php echo $productManager->getProductCategoriesForProduct($_POST['product-category']); ?>
						</select>
                        <i>Please supply the category under which this product falls under.</i>

						<div class="load-more-loader hidden" id="loader-product-cat">
	                        <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
	                    </div>

						<span class="product-sub-cat-holder <?php if($_POST['product-category'] == '' || $_POST['product-category'] == ' ' || $_POST['product-category'] == 0){ echo 'hidden'; }?>">
							<div class="module-form-titles"><span class="required">*</span> Product Sub Category:</div>
	                        <select name="product-sub-category">
								<option value="0">-- Select a Sub Category --</option>
								<?php
									if($_POST['product-category'] != '' && $_POST['product-category'] != ' ' && $_POST['product-category'] != 0){
										echo $productManager->getProductSubCategories($_POST['product-category'], $_POST['product-sub-category']);
									}
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
						}
						?>
						<label><input type="checkbox" name="product-special" value="1" <?php echo $checked; ?>/> On Special</label>
                        <i>Check the checkbox should this product be on special.</i>

						<div class="special-date-holder">
							<div class="module-date-input">
	                            <div class="module-form-titles"><span class="required">*</span> Special End Date:</div>
	                            <input type="text" name="special-end-date" id="datepicker" placeholder="Special End Date" value="<?php if($_POST['special-end-date'] != ''){echo $_POST['special-end-date'];}?>">
	                            <i>Please supply the start date of the banner.</i>
	                        </div>
							<div class="clear"></div>
						</div>

						<div class="module-form-titles">Product Price:</div>
	                    <input type="text" name="product-price" placeholder="Product Price" value="<?php if($_POST['product-price'] != ''){echo $_POST['product-price'];}?>" />
	                    <i>Only supply if you want to add a price to the product. (e.g. 100.00)</i>

						<div class="module-form-titles">Product Brand:</div>
						<input type="text" name="product-brand" placeholder="Product Brand" value="<?php if($_POST['product-brand'] != ''){echo $_POST['product-brand'];}?>" maxlength="150"/>
                        <i>Please supply the product brand.</i>

						<div class="module-form-titles">Manufacturer Link:</div>
	                    <input type="text" name="product-manufacturer" placeholder="Product Manufacturer" value="<?php if($_POST['product-manufacturer'] != ''){echo $_POST['product-manufacturer'];}?>" />
	                    <i>Supply a valid link to the manufacturer of the product.</i>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Product Image</div>
                	<p>
						An image has to be linked to the Product by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Product has been uploaded.
                    </p>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"><span class="required">*</span> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>

				<input type="submit" class="module-form-submit" name="add_product" title="Add Product" value="Add Product" onclick="pleasewait()" />
			</form>
            </div>
            <!-- END IMAGE HOLDER-->
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
