<?php
require_once("../../library/ajax.blogLibrary.php");

$connector = new dbConnector();

//PAGE NUMBER, RESULTS PER PAGE, AND OFFSET OF THE RESULTS
if($_GET["page"]){
    $pagenum = $_GET["page"];
} else {
    $pagenum = 1;
}

//FETCH BLOG POSTS
$ajaxBlogLibrary->fetchBlogPosts($pagenum, $cms_root);
?>
