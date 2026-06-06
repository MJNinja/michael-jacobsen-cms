<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 3;
$sequence = 1;
$sequenceTable = 'blog_post_content';
$sequenceMainID = 'blogPostContentID';
$pageTitle = 'Manage Blog Post';

//GET URL VARIABLE
if(isset($_POST['blogPostID'])){$blogPostID = $_POST['blogPostID'];}else{$blogPostID = $_GET['blogPostID'];}

require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.blogManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

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
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'blog-manager/" title="Blog Manager">Blog Manager</a> | <span class="current">Manage Blog Post</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Manage Blog Post - <?php echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle'); ?></h1>
        <div class="intro">
        	<p>This is the <b>Manage Blog Post</b> page. This page will allow you to add content to the current blog post (<?php echo $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle'); ?>).</p>
            <p>To add a new paragraph simply click on <b>Add Paragraph</b> and to add a new gallery simply click on <b>Add Gallery</b>.</p>
        </div>

        <div class="left-column">

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Blog Post Content Architecture</div>
                <div class="module-links"><a href="<?php echo $cms_root; ?>blog-manager/add-gallery.php?blogPostID=<?php echo $blogPostID; ?>" title="Add Gallery">Add Gallery</a><a href="<?php echo $cms_root; ?>blog-manager/add-paragraph.php?blogPostID=<?php echo $blogPostID; ?>" title="Add Paragraph">Add Paragraph</a></div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see all the content added to the current blog post.
                </div>

                <?php echo $blogManager->defineErrorMessages($_GET['message']); ?>

                <div class="module-architecture-table-holder" id="sortable">

                    <?php echo $blogManager->blogPostContentArchitecture($cms_root, $web_root, $blogPostID);?>

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
