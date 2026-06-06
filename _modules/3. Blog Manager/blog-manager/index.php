<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 3;
$blog_post_load_more = 1;
$pageTitle = 'Blog Manager';

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.blogManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <span class="current">Blog Manager</span>';

//AJAX FOR BLOG POSTS
require_once("../ajax/ajax.viewMoreBlogPosts.php");

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Blog Manager</h1>
        <div class="intro">
        	<p>This is the <b>Blog Manager</b>. This module will allow you to manage all your Blog Posts that you published on your website.</p>
            <p>In order to create a Blog Post you will firstly have to add a Category. To add Categories click on the <b>Manage Blog Categories</b> button. Once a category has been added the <b>Add Blog Post</b> button will appear, allowing you to add Blog Posts.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Blog Post Architecture</div>
                <div class="module-links">
                    <a href="<?php echo $cms_root; ?>blog-manager/manage-blog-category.php" title="Manage Blog Categories">Manage Blog Categories</a>

                    <?php if($blogManager->checkCategoryAdded() != 0){?>
                    <a href="<?php echo $cms_root; ?>blog-manager/add-blog-post.php" title="Add Blog Post">Add Blog Post</a>
                    <?php }?>
                </div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the Blog Posts added to the current category.
                    <div class="module-keys-holder">
                        <div class="active-user-key"></div><div class="active-user-key-description">Indicates active blog posts.</div>
                        <div class="clear"></div>
                        <div class="removed-user-key"></div><div class="removed-user-key-description">Indicates removed blog posts.</div>
                    </div>
                </div>

                <?php echo $blogManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder">
                    <div class="module-architecture-table-heading">Active Blog Posts</div>
                    <table width="100%" class="module-architecture-table">
    	                <tr class="module-architecture-header">
                            <td width="1%"></td>
                            <td width="40%">Blog Post Name</td>
                            <td width="20%" align="center">Publish Date</td>
                            <td width="13%" align="center">Manage</td>
                            <td width="13%" align="center">Modify</td>
                            <td width="13%" align="center">Remove</td>
                        </tr>

                        <?php echo $blogManager->blogPostArchitecture($preload_content_blog_posts, $cms_root);?>

                    </table>

                    <!-- BEGIN LOAD MORE BLOG POSTS -->
                    <div id="recent-blog-posts"></div>

                    <div class="load-more-loader" id="loader-blog-posts">
                        <img src="<?php echo $cms_root;?>images/basic/loader.gif" title="Loading content..." alt="Loading content..."/>
                    </div>

                    <?php
                    if($total_nums_blog_posts > $preload_content_blog_posts){
                        echo '<input id="loadmore-blog-posts" type="button" class="loadmore-style" title="Load More" value="Load More"><input id="pages-blog-posts" type="hidden" value="'.$total_pages_blog_posts.'">';
                    }
                    ?>
                    <div class="clear"></div>
                    <!-- END LOAD MORE BLOG POSTS -->
                </div>

                <?php if($blogManager->checkRemovedBlogPosts() != 0){?>
                <div class="module-architecture-table-holder">
                	<div class="module-architecture-table-heading">Removed Blog Posts</div>
                    <table width="100%" class="module-architecture-table">
                        <tr class="module-architecture-header">
                            <td width="1%"></td>
                            <td width="50%">Blog Post Name</td>
                            <td width="18%" align="center">Publish Date</td>
							<td width="18%" align="center">Delete Permanently</td>
                            <td width="13%" align="center">Recover</td>
                        </tr>

                      	<?php echo $blogManager->blogPostArchitectureRemoved($cms_root);?>

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
