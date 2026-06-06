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
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit Blog Post';

//GET URL VARIABLE
if(isset($_POST['blogPostID'])){$blogPostID = $_POST['blogPostID'];}else{$blogPostID = $_GET['blogPostID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.blogManager.php");

//REDIRECT PAGE
if($blogPostID != ''){
	//CHECK quoteID AND quoteCatID INSIDE DATABASE
	if($blogManager->checkBlogPostDatabase($blogPostID) == 'not found'){
		header("Location:".$cms_root."blog-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."blog-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'blog-manager/" title="Blog Manager">Blog Manager</a> | <span class="current">Edit Blog Post</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit Blog Post - <?php echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Edit Blog Post</b> page. This page will allow you to edit this blog post in the current category (<?php echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle'); ?>).</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit Blog Post</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the blog post. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit Blog Post</b> to edit the blog post.
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
                        <input type="hidden" name="blogPostID" value="<?php echo $blogPostID; ?>"/>
						<?php if($blog_affiliates_tags == 1){?>
						<input type="hidden" name="affiliates" value="">
						<?php }?>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $blogManager->getBlogPostInfo($blogPostID, 'modifiedNumber')+1; ?>"/>
						<input type="hidden" name="oldImage" value="<?php echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostImageFile'); ?>"/>

                    	<div class="module-form-titles"><span class="required">*</span> Blog Post Title:</div>
						<input type="text" name="blog-post-title" placeholder="Blog Post Title" value="<?php if($_POST['blog-post-title'] != ''){echo $_POST['blog-post-title'];}else{ echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle'); } ?>" maxlength="150"/>
                        <i>The blog post title has a maximum of 150 characters.</i>

                        <div class="module-form-titles"><span class="required">*</span> Intro:</div>
						<textarea name="paragraph" cols="20" rows="5" placeholder="Intro"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{ echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostIntro'); } ?></textarea>
                        <i>The blog post intro requires a minimum of 10 characters.</i>

                        <span class="hidden"><div class="module-form-titles"><span class="required">*</span> Paragraph:</div>
						<textarea name="blog-post-paragraph" cols="20" rows="5" placeholder="Paragraph"><?php if($_POST['blog-post-paragraph'] != ''){echo $_POST['blog-post-paragraph'];}?></textarea>
                        <i>Please supply an intro for the blog post.</i></span>

                        <div class="module-date-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Date:</div>
                            <input type="text" name="blog-post-date" id="datepicker" placeholder="Publish Date" value="<?php if($_POST['blog-post-date'] != ''){echo $_POST['blog-post-date'];}else{ echo $blogManager->getBlogPostDateTimeInfo($blogPostID, 'date'); } ?>" />
                            <i>Please supply the publish date of the blog post.</i>
                        </div>

                        <div class="module-time-input">
                            <div class="module-form-titles"><span class="required">*</span> Publish Time:</div>
                            <input type="text" name="blog-post-time" id="timepicker" placeholder="Publish Time" value="<?php if($_POST['blog-post-time'] != ''){echo $_POST['blog-post-time'];}else{ echo $blogManager->getBlogPostDateTimeInfo($blogPostID, 'time'); } ?>" />
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
                                <?php if($_POST['categories'] != ''){ echo $blogManager->generatePostedTags($_POST['categories']);}else{ echo $blogManager->getBlogPostTags($blogPostID, 'blogCatID');}?>
                            </ul>
                            <i>Please supply all the categories under which this Blog Post falls under.</i>
                        </div>

						<?php if($blog_affiliates_tags == 1){?>
						<div class="module-time-input">
                            <div class="module-form-titles">Affiliate Links:</div>
                            <ul id="affiliate_tags">
                                <?php if($_POST['affiliates'] != ''){ echo $blogManager->generatePostedTags($_POST['affiliates']);}else{ echo $blogManager->getTags($blogPostID, 'affiliateIDs');}?>
                            </ul>
                            <i>Please supply all the affiliate links which should be added to this blog post.</i>
                        </div>
						<?php }?>

						<div class="clear"></div>

						<div class="module-form-titles"><span class="required">*</span> Author:</div>
						<select name="blog-post-author">
							<?php
							if($_POST['blog-post-author'] != ''){
								$author = $_POST['blog-post-author'];
							}else{
								$author = $blogManager->getBlogPostInfo($blogPostID, 'authorID');
							}
							?>
							<option value="0">-- Select the Author --</option>
							<?php echo $blogManager->getAllAuthors($author); ?>
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
                        <br/><br/>
                        In order to change the image, simply choose a new image under "Image File" and click "Edit Blog Post".
                    </p>

                    <?php echo $blogManager->getBlogPostImage($blogPostID, $web_root); ?>

                    <div class="module-form-titles"><span class="required">*</span> Image Title:</div>
                    <input type="text" name="image-title" placeholder="Image Title" value="<?php if($_POST['image-title'] != ''){echo $_POST['image-title'];}else{echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostImageTitle');}?>" />
                    <i>The image title has a maximum of 150 characters.</i>

                    <span class="hidden"><div class="module-form-titles">Image Type:</div>
                    <input type="text" name="image-type" placeholder="Image Type" value="<?php if($_POST['image-type'] != ''){echo $_POST['image-type'];}?>" />
                    <i>The type has a maximum of 150 characters.</i></span>

                    <div class="module-form-titles"> Image File:</div>
                    <input type="file" name="image-file" />
                    <i>The image file has to be in jpeg/jpg/JPEG/JPG/png/PNG format.</i>
                </div>
				<input type="submit" class="module-form-submit" name="edit_blog_post" title="Edit Blog Post" value="Edit Blog Post" onclick="pleasewait()"/>
			</form>
            </div>
            <!-- END IMAGE HOLDER-->

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Blog Post Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>Blog Post</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $blogManager->getUsersName($blogManager->getBlogPostInfo($blogPostID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($blogManager->getBlogPostInfo($blogPostID, 'modifiedBy') != 0){
									echo $blogManager->getUsersName($blogManager->getBlogPostInfo($blogPostID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($blogManager->getBlogPostInfo($blogPostID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($blogManager->getBlogPostInfo($blogPostID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($blogManager->getBlogPostInfo($blogPostID, 'modifiedDate')));
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
                        	<?php echo $blogManager->getBlogPostInfo($blogPostID, 'modifiedNumber');?>
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
