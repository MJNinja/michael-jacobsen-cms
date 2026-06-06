<?php
#######################################################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 14.05.2015
#######################################################################################################
require_once("class.systemConfig.php");

class ajaxBlogLibrary extends DbConnector{
	//#################################################################
    // DO NOT CHANGE CODE BELOW
    //#################################################################
    function __construct(){}
    function __destruct(){unset($connector);}

    //#################################################################
    //ESCAPE CERTAIN CHARACTERS FOR SAFER QUERIES
    //#################################################################
	function escape($str)
    {
        $search=array("\\","\0","\n","\r","\x1a","'",'"');
        $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
        return str_replace($search,$replace,$str);
    }

	//#################################################
	//GET TOTAL NUMBER OF BLOG POSTS
	//#################################################
	function getTotalNumberBlogPosts(){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

		$total_q_blog_posts = $connector->query("SELECT * FROM blog_posts WHERE deletedBy = ? ORDER BY publishDate DESC", array('0'));

		$total_nums_blog_posts = $connector->numResults($total_q_blog_posts); //TOTAL NUMBER OF RESULTS

		//RETURN TOTAL
		return $total_nums_blog_posts;
	}

	//#################################################
	//FETCH BLOG POSTS
	//#################################################
	function fetchBlogPosts($pagenum, $cms_root){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

		//DEFAULT VARIABLES
		$currentDate = date('Y-m-d');

		//ONLY SHOW LOAD BUTTON AT THE BEGINNING
		if($pagenum != 1){

		    $rowsperpage = 40; //MAXIMUM RESULTS PER PAGE
		    $offset = ($pagenum-1) * $rowsperpage; //WHERE THE RESULTS START FROM

		    //FOR RESULTS OF THE PAGE
		    $q = $connector->query("SELECT * FROM blog_posts WHERE deletedBy = ? ORDER BY publishDate DESC LIMIT $offset, $rowsperpage", array('0'));

		    $total_q = $connector->query("SELECT * FROM blog_posts WHERE deletedBy = ? ORDER BY publishDate DESC", array('0'));//FOR ALL RESULTS
		    $total_nums = $connector->numResults($total_q); //TOTAL NUMBER OF RESULTS
		    $total_pages = ceil($total_nums/$rowsperpage); //NUMBER OF PAGES

		    //IF PAGE NUMBER IS WITHIN THE FIRST AND LAST PAGES...
		    if($pagenum >= 1 && $pagenum <= $total_pages)
		    {
				//BEGIN TABLE
				echo '<table width="100%" class="module-architecture-table">';

		    	while($row	= $connector->fetchArray($q)){
					//SET VARIABLES
					$status			= '';
					$status_bg		= '';
					$date			= '';
					$currentDate	= date('Y-m-d H:i:s');
					$blogPostID		= $row['blogPostID'];
					$blogPostTitle	= $row['blogPostTitle'];
					$publishDate	= $row['publishDate'];

					//FORMAT PUBLISH DATE
					$date = date("j F Y - H:i", strtotime($publishDate));

					//GET ALL BLOG POST CONTENT FOR A BLOG POST
					$result2	= $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ?", array($blogPostID, '0'));
					$blogPostContentTotal	= $connector->numResults($result2);

					//IF BLOG POST IS EMPTY
					if($blogPostContentTotal == 0){
						$status		= '<span class="empty-category-text">(Empty)</span>';
						$status_bg	='class="empty-category"';
					}
					//CHECK IF POST HAS ALREADY BEEN PUBLISHED
					else{
						if($publishDate > $currentDate){
							//NOT YET PUBLISHED
							$status		= '<span class="unpublished-post-text">(Not yet published)</span>';
							$status_bg	= 'class="unpublished-post"';
						}elseif($publishDate < $currentDate){
							//PUBLISHED
							$status	= '(Published)';
						}
					}
					//GENERATE OUPUT
					echo '<tr>
						<td width="1%" class="active-account"></td>
						<td width="40%" '.$status_bg.'>'.$blogPostTitle.' '.$status.'</td>
						<td width="20%" '.$status_bg.' align="center">'.$date.'</td>
						<td width="13%" '.$status_bg.' align="center">
							<a href="'.$cms_root.'blog-manager/manage-blog-post.php?blogPostID='.$blogPostID.'" title="Manage">Manage</a>
						</td>
						<td width="13%" '.$status_bg.' align="center">
							<a href="'.$cms_root.'blog-manager/edit-blog-post.php?blogPostID='.$blogPostID.'" title="Modify">Modify</a>
						</td>
						<td width="13%" '.$status_bg.' align="center">';

						echo '<form name="delete_blog_post'.$blogPostID.'">
								<input type="hidden" name="delete_blog_post" value="1">
								<input type="hidden" name="blogPostID" value="'.$blogPostID.'">
								<a href="javascript:deleteBlogPost('.$blogPostID.')" title="Remove">Remove</a>
							</form>';

						echo '</td>
					  </tr>';
		        }

				//END TABLE
				echo '</table>';
		    }
		}
	}
}

$ajaxBlogLibrary = new ajaxBlogLibrary();
?>
