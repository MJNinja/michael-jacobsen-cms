<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 3;
$colorbox = 1;
$ckeditor = 1;
$date_picker = 1;
$time_picker = 1;
$blog_post_tags = 1;
$blog_affiliates_tags = 1;
$pageTitle = 'Add Blog Post';

//GET URL VARIABLE
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.blogManager.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'blog-manager/" title="Blog Manager">Blog Manager</a> | <span class="current">Add Blog Post</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Add Blog Post</h1>
        <div class="intro">
        	<p>This is the <b>Add Blog Post</b> page. This page will allow you to add a new blog post to multiple categories.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Add Blog Post</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please fill out all the required field below to add a new blog post. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Add Blog Post</b> to add the new blog post.
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
						<input type="hidden" name="categories" value="">
						<?php if($blog_affiliates_tags == 1){?>
						<input type="hidden" name="affiliates" value="">
						<?php }?>
                    	<div class="module-form-titles"><span class="required">*</span> Blog Post Title:</div>
						<input type="text" name="blog-post-title" placeholder="Blog Post Title" value="<?php if($_POST['blog-post-title'] != ''){echo $_POST['blog-post-title'];}?>" maxlength="150"/>
                        <i>The blog post title has a maximum of 150 characters.</i>

                        <div class="module-form-titles"><span class="required">*</span> Intro:</div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Intro"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}?></textarea>
                        <i>The blog post intro requires a minimum of 10 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="blog-post-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['blog-post-paragraph'] != ''){echo $_POST['blog-post-paragraph'];}?></textarea>
                        <i>Please supply an intro for the blog post.</i></span>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Date:</div>
                            <input type="text" name="blog-post-date" id="datepicker" placeholder="Publish Date" value="<?php if($_POST['blog-post-date'] != ''){echo $_POST['blog-post-date'];}?>" />
                            <i>Please supply the publish date of the blog post.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Time:</div>
                            <input type="text" name="blog-post-time" id="timepicker" placeholder="Publish Time" value="<?php if($_POST['blog-post-time'] != ''){echo $_POST['blog-post-time'];}?>" />
                            <i>Please supply the publish time of the blog post.</i>
                        </div>

                        <span class="hidden"><div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> End Publish Date:</div>
                            <input type="text" name="blog-post-date2" id="datepicker" placeholder="End Publish Date" value="<?php if($_POST['blog-post-date2'] != ''){echo $_POST['blog-post-date2'];}?>" />
                            <i>Please supply the publish date of the blog post.</i>
                        </div></span>
                        <div class="clear"></div>

						<div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Blog Categories:</div>
                            <ul id="category_tags">
                                <?php if($_POST['categories'] != ''){ echo $blogManager->generatePostedTags($_POST['categories']);}?>
                            </ul>
                            <i>Please supply all the categories under which this Blog Post falls under.</i>
                        </div>

						<?php if($blog_affiliates_tags == 1){?>
						<div class="module-time-input">
                            <div class="module-form-titles">Affiliate Links:</div>
                            <ul id="affiliate_tags">
                                <?php if($_POST['affiliates'] != ''){ echo $blogManager->generatePostedTags($_POST['affiliates']);}?>
                            </ul>
                            <i>Please supply all the affiliate links which should be added to this blog post.</i>
                        </div>
						<?php }?>

						<div class="clear"></div>

						<div class="module-form-titles"><span class="required">*</span> Author:</div>
						<select name="blog-post-author">
							<option value="0">-- Select the Author --</option>
							<?php echo $blogManager->getAllAuthors($_POST['blog-post-author']); ?>
						</select>
                        <i>Please select the author of this blog post.</i>
                    </div>
            </div>

			<!-- BEGIN IMAGE HOLDER-->
            <div class="module-holder">
                <div class="module-form-holder">
                	<div class="module-form-titles">Blog Post Image</div>
                	<p>
						An image has to be linked to the Blog Post by completing the fields below, please note that when the image is uploaded you will be required to crop the image after the Blog Post has been uploaded.
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

				<input type="submit" class="module-form-submit" name="add_blog_post" title="Add Blog Post" value="Add Blog Post" onclick="pleasewait()" />
			</form>
            </div>
            <!-- END IMAGE HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/blog-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
