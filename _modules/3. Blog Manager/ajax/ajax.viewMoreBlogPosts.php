<?php
require_once("../../library/ajax.blogLibrary.php");
$connector = new dbConnector();

$rowsperpage_blog_posts = 40; //MAXIMUM RESULTS PER PAGE
$preload_content_blog_posts = 40;

$total_nums_blog_posts  = $ajaxBlogLibrary->getTotalNumberBlogPosts(); //TOTAL NUMBER OF RESULTS

$total_pages_blog_posts = ceil($total_nums_blog_posts/$rowsperpage_blog_posts); //NUMBER OF PAGE FOR RESULTS
?>
