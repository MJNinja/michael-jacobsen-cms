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
require_once("class.formValidation.php");
require_once("class.fileUploader.php");
require_once("cms.autoKeywords.php");

class affiliatesManager extends systemConfig{
	//#################################################################
    // DO NOT CHANGE CODE BELOW
    //#################################################################
    function __construct(){}
    function __destruct(){unset($connector);}

	//#################################################################
    // DEFINE ERROR MESSAGES
    //#################################################################
    function defineErrorMessages($message){
        switch($message){
            case 1: $displayMessage = 'A new Category has successfully been added.'; break;
			case 2: $displayMessage = 'A new Affiliate Link has successfully been added.'; break;
            case 3: $displayMessage = 'The selected Category has successfully been updated.'; break;
			case 4: $displayMessage = 'The selected Affiliate Link has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Category has successfully been removed.'; break;
			case 6: $displayMessage = 'The selected Affiliate Link has successfully been removed.'; break;
			case 7: $displayMessage = 'The selected Category has successfully been recovered.'; break;
			case 8: $displayMessage = 'The selected Category has successfully been re-activated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($affCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM affiliate_category WHERE affCatID = ?", array($affCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET AFFILIATE INFORMATION
    //#################################################################
	function getAffiliateLinkInfo($affiliateID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM affiliate WHERE affiliateID = ?", array($affiliateID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET AFFILIATE LINK IMAGE
    //#################################################################
	function getAffiliateLinkImage($affiliateID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM affiliate WHERE affiliateID = ?", array($affiliateID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['imageFile'];
		$imageTitle	= $row['imageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div><br /><input type="checkbox" value="1" name="removeImage" />Remove Image from Affiliate Link</div>';
        }

		//RETURN OUTPUT
		return $txt;
	}

	//#################################################################
    // CHECK IF BLOG CATEGORY IS IN DATABASE
    //#################################################################
	function checkCategoryDatabase($affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM affiliate_category WHERE affCatID = ?", array($affCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}

	}

	//#################################################################
    // CHECK IF AFFILIATE IS IN DATABASE
    //#################################################################
	function checkAffiliateLinkDatabase($affiliateID, $affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM affiliate WHERE affiliateID = ? AND affCatID = ?", array($affiliateID, $affCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // GET USER NAME
    //#################################################################
	function getUsersName($userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE userID = ?", array($userID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row['name'].' '.$row['surname'];

	}

	//#################################################################
    // GET TOTAL AFFILIATE CATEGORIES
    //#################################################################
	function getTotalAffiliateCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM affiliate_category WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED AFFILIATE CATEGORIES
    //#################################################################
	function getTotalRemovedAffiliateCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM affiliate_category WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL AFFILIATE LINKS
    //#################################################################
	function getTotalAffiliateLinks(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM affiliate WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // BLOG CATEGORY ARCHITECTURE
    //#################################################################
	function categoryArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM affiliate_category WHERE deletedBy = ? ORDER BY affCatName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$empty			= '';
				$empty_bg		= '';
				$affCatID		= $row['affCatID'];
				$affCatName	    = $row['affCatName'];

				//GET ALL BLOG POSTS FOR A CATEGORY
				$result2	= $connector->query("SELECT * FROM affiliate WHERE affCatID = ? AND deletedBy = ?", array($affCatID, '0'));
				$affiliateTotal	= $connector->numResults($result2);

				//IF CATEGORY IS EMPTY
				if($affiliateTotal == 0){
					$empty		= '<span class="empty-category-text">(Empty)</span>';
					$empty_bg	='class="empty-category"';
				}

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$empty_bg.'>'.$affCatName.' '.$empty.'</td>
					<td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'affiliates-manager/manage-affiliate-category-content.php?affCatID='.$affCatID.'" title="Manage">Manage</a>
					</td>
					<td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'affiliates-manager/edit-affiliate-category.php?affCatID='.$affCatID.'" title="Modify">Modify</a>
					</td>
					<td '.$empty_bg.' align="center">';

					//IF NO AFFILIATE LINK ARE IN THE CATEGORY
					if($affiliateTotal == 0){

						$txt.='<form name="delete_affiliate_category'.$affCatID.'">
							<input type="hidden" name="delete_affiliate_category" value="1">
							<input type="hidden" name="affCatID" value="'.$affCatID.'">
							<a href="javascript:deleteAffiliateCategory('.$affCatID.')" title="Remove">Remove</a>
						</form>';
					}
					//IF AFFILIATE LINK ARE IN THE CATEGORY
					else{
						$txt.='<a href="javascript:noDeleteCategory()" title="Remove">Remove</a>';
					}

					$txt.= '</td>
				  </tr>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="5">There are currently no Categories available. <a href="'.$cms_root.'affiliates-manager/add-affiliate-category.php" title="Add Affiliate Category">Please add a category here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // AFFILIATE CATEGORY ARCHITECTURE (REMOVED)
    //#################################################################
	function categoryArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM affiliate_category WHERE deletedBy != ? ORDER BY affCatName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$affCatID		= $row['affCatID'];
			$affCatName	    = $row['affCatName'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$affCatName.'</td>
				<td align="center">
				<form name="recover_affiliate_category'.$affCatID.'">
					<input type="hidden" name="recover_affiliate_category" value="1">
					<input type="hidden" name="affCatID" value="'.$affCatID.'">
					<a href="javascript:recoverAffiliateCategory('.$affCatID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // AFFILIATE LINK ARCHITECTURE
    //#################################################################
	function affiliateLinkArchitecture($cms_root, $affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL BLOG POSTS
		$result = $connector->query("SELECT * FROM affiliate WHERE deletedBy = ?  AND affCatID = ? ORDER BY affTitle DESC", array('0', $affCatID));
		$affiliateLinkTotal = $connector->numResults($result);

		//IF BLOG POSTS ARE AVAILABLE
		if($affiliateLinkTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$affiliateID    = $row['affiliateID'];
				$affTitle   	= $row['affTitle'];

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td>'.$affTitle.'</td>
					<td align="center">
						<a href="'.$cms_root.'affiliates-manager/edit-affiliate.php?affiliateID='.$affiliateID.'&affCatID='.$affCatID.'" title="Modify">Modify</a>
					</td>
					<td align="center">';

					$txt.='<form name="delete_affiliate_link'.$affiliateID.'">
							<input type="hidden" name="delete_affiliate_link" value="1">
							<input type="hidden" name="affiliateID" value="'.$affiliateID.'">
							<input type="hidden" name="affCatID" value="'.$affCatID.'">
							<a href="javascript:deleteAffiliateLink('.$affiliateID.')" title="Remove">Remove</a>
						</form>';

					$txt.= '</td>
				  </tr>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="4">There are currently no Blog Posts available. <a href="'.$cms_root.'affiliates-manager/add-affiliate.php?affCatID='.$affCatID.'" title="Add Affiliate Link">Please add an Affiliate Link here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF ANY AFFILIATE CATEGORIES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM affiliate_category WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF AFFILIATE CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkCategoryChanges($category_name, $affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM affiliate_category WHERE affCatName = ? AND affCatID = ?", array($category_name, $affCatID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD CATEGORY
    //#################################################################
	function addCategory($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$category_name		= strip_tags($category_name);

		//ADD USER
		$insert = $connector->query("INSERT INTO affiliate_category (affCatName, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($category_name, $currentUser, $currentDate));

	}

	//#################################################################
	//OVERWRITE CATEGORY
	//#################################################################
	function overwriteCategory($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$category_name	= strip_tags($category_name);

		//UPDATE USER
		$update = $connector->query("UPDATE affiliate_category SET
									affCatName = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE affCatName = ?",
									array($category_name, '0', '0000-00-00 00:00:00', $category_name));

	}

	//#################################################################
    // UPDATE BLOG CATEGORY
    //#################################################################
	function updateCategory($category_name, $modifiedBy, $modifiedDate, $modifiedNumber, $affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$category_name	= strip_tags($category_name);

		//UPDATE USER
		$update = $connector->query("UPDATE affiliate_category SET
									affCatName = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE affCatID = ?",
									array($category_name, $modifiedBy, $modifiedDate, $modifiedNumber, $affCatID));

	}

	//#################################################################
    // DELETE CATEGORY
    //#################################################################
	function deleteCategory($affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE affiliate_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE affCatID = ?",
									array($currentUser, $currentDate, $affCatID));

	}

	//#################################################################
    // DELETE AFFILIATE LINK
    //#################################################################
	function deleteAffiliateLink($affiliateID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM affiliate WHERE affiliateID = ?", array($affiliateID));
		$row	= $connector->fetchArray($result);
		$imageFile		= $row['imageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$imageFile);
		unlink($mediumDirectory.$imageFile);
		unlink($smallDirectory.$imageFile);

		//REMOVE AFFILIATE LINK
		$remove = $connector->query("DELETE FROM affiliate WHERE affiliateID = ?",array($affiliateID));

	}

	//#################################################################
    // RECOVER CATEGORY
    //#################################################################
	function recoverCategory($affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE affiliate_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE affCatID = ?",
									array('0', '0000-00-00 00:00:00', $affCatID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM affiliate_category WHERE affCatName = ?", array($category_name));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
		//IF CATEGORY NAME HAS BEEN USED
		elseif($total == 1){
			//GET USER INFO
			$row 		= $connector->fetchArray($result);

			//SET VARIABLES
			$deletedBy	= $row['deletedBy'];

			//IF CATEGORY HAS BEEN REMOVED
			if($deletedBy != 0){
				return 'removed_affiliate_category';
			}
		}

	}

	//#################################################################
    // CHECK IF AFFILIATE CATEGORY IS ALREADY IN USE
    //#################################################################
	function editCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM affiliate_category WHERE affCatName = ?", array($category_name));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // ADD AFFILIATE LINK
    //#################################################################
	function addAffiliate($title, $paragraph, $link, $image_title, $imageFile, $affCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$image_title	= strip_tags($image_title);
		$link		    = strip_tags($link);

		//ADD USER
		$insert = $connector->query("INSERT INTO affiliate (affCatID, affTitle, affDescription, imageFile, imageTitle, affLink, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
									array($affCatID, $title, $paragraph, $imageFile, $image_title, $link, $currentUser, $currentDate));

	}

    //#################################################################
    // UPDATE AFFILIATE
    //#################################################################
	function updateAffiliate($title, $paragraph, $link, $image_title, $imageFile, $modifiedDate, $modifiedNumber, $affiliateID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$image_title	= strip_tags($image_title);
		$link		    = strip_tags($link);
        $paragraph      = strip_tags($paragraph);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM affiliate WHERE affiliateID = ?", array($affiliateID));
        $row    = $connector->fetchArray($result);
        $image  = $row['imageFile'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//ADD AFFILIATE LINK
		$update			= $connector->query("UPDATE affiliate SET
                                            affTitle        = ?,
                                            affDescription  = ?,
                                            imageFile       = ?,
                                            imageTitle      = ?,
                                            affLink         = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE affiliateID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $link, $currentUser, $modifiedNumber, $modifiedDate, $affiliateID));

	}

}

//DEFINE CLASS
$affiliatesManager = new affiliatesManager();

//#################################################################
// ADD AFFILIATE CATEGORY
//#################################################################
if(isset($_POST['add_affiliate_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$category_name 		= $_POST['category-name'];

	//HONEY POTS
	$category_type		= $_POST['category-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $category_name       = $userLogin->specialCharactersToHTMLEntity($category_name);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($category_name, 'Category Name', 2, 150);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($category_type == ''){

			//CHECK IF CATEGORY NAME IS ALREADY IN USE
			$category_used = $affiliatesManager->addCategoryCheck($category_name);
			if($category_used == 'unused'){

				//INSERT USER INTO DATABASE
				$affiliatesManager->addCategory($category_name);

				//REDIRECT USER
				header("Location: ".$cms_root."affiliates-manager/index.php?message=1");
        		exit;

			}
			//IF USER HAS BEEN REMOVED
			elseif($category_used == 'removed_affiliate_category'){
				//SET USER AS REMOVED
				$removed_affiliate_category = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Category Name</b> you supplied is already in use. Please try another!</li></ul>';
			}

		}

	}
	//ERRORS HAVE BEEN FOUND
	else{

		//SET ERROR MESSAGE
		$error_message = $v->errorCMSMessage();
		$errors = $v->showErrors();
	}

}

//#################################################################
// REACTIVATE CATEGORY
//#################################################################
if(isset($_POST['reactivate-category-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$category_name		= $_POST['category-name'];

	//HONEY POTS
	$category_type		= $_POST['category-type'];

	if($category_type == ''){

		//OVERWRITE USER
		$affiliatesManager->overwriteCategory($category_name);

		//REDIRECT PAGE
		header("Location: ".$cms_root."affiliates-manager/index.php?message=8");
		exit;
	}
}

//#################################################################
// EDIT CATEGORY
//#################################################################
if(isset($_POST['edit_affiliate_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$category_name 	= $_POST['category-name'];
	$affCatID		= $_POST['affCatID'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$category_type	= $_POST['category-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $category_name       = $userLogin->specialCharactersToHTMLEntity($category_name);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($category_name, 'Category Name', 2, 150);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($category_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($affiliatesManager->checkCategoryChanges($category_name, $affCatID) == 'changed'){

				//CHECK CATEGORY IS USED
				$category_used = $affiliatesManager->editCategoryCheck($category_name);
				if($category_used == 'unused'){

					//UPDATE USER IN DATABASE
					$affiliatesManager->updateCategory($category_name, $modifiedBy, $modifiedDate, $modifiedNumber, $affCatID);


					//REDIRECT USER
					header("Location: ".$cms_root."affiliates-manager/index.php?message=3");
            		exit;

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Category Name</b> you supplied is already in use. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."affiliates-manager/");
        		exit;
			}

		}

	}
	//ERRORS HAVE BEEN FOUND
	else{

		//SET ERROR MESSAGE
		$error_message = $v->errorCMSMessage();
		$errors = $v->showErrors();
	}

}

//#################################################################
//DELETE CATEGORY
//#################################################################
if(isset($_POST['delete_affiliate_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $affCatID	= $_POST['affCatID'];

    //SET USER AS REMOVED IN DATABASE
    $affiliatesManager->deleteCategory($affCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."affiliates-manager/index.php?message=5");
    exit;
}

//#################################################################
//RECOVER CATEGORY
//#################################################################
if(isset($_POST['recover_affiliate_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $affCatID	= $_POST['affCatID'];

    //SET USER AS REMOVED IN DATABASE
    $affiliatesManager->recoverCategory($affCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."affiliates-manager/index.php?message=7");
    exit;
}

//#################################################################
//DELETE AFFILIATE LINK
//#################################################################
if(isset($_POST['delete_affiliate_link'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$affiliateID  = $_POST['affiliateID'];
    $affCatID	  = $_POST['affCatID'];

    //REMOVE AFFILIATE LINK IN DATABASE
    $affiliatesManager->deleteAffiliateLink($affiliateID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."affiliates-manager/manage-affiliate-category-content.php?affCatID=".$affCatID."&message=6");
    exit;
}

//#################################################################
// ADD AFFILIATE LINK
//#################################################################
if(isset($_POST['add_affiliate_link'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$affCatID		= $_POST['affCatID'];
	$title			= $_POST['affiliate-title'];
    $paragraph      = $_POST['paragraph'];
    $link           = $_POST['affiliate-link'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$affiliate_type	= $_POST['affiliate-type'];
	$image_type		= $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Affiliate Title', 1, 200);

    //CHECK IF DESCRIPTION HAS BEEN SUPPLIED
    if($paragraph != ''){
        $v->validateText($paragraph, 'Description', 10);
    }

    $v->validateLink($link, 'Affiliate Link');

	//IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateString($image_title, 'Image Title',3, 150);
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($affiliate_type == '' && $image_type == ''){

			//IF AN IMAGE HAS BEEN ADDED
			if($_FILES[$inputField]["tmp_name"] != ""){
				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

				//GET THE IMAGE SIZE
				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
			}

			//INSERT AFFILIATE LINK INTO DATABASE
			$affiliatesManager->addAffiliate($title, $paragraph, $link, $image_title, $imageFile, $affCatID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."affiliates-manager/crop-image.php?affCatID=".$affCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."affiliates-manager/manage-affiliate-category-content.php?affCatID=".$affCatID."&message=2");
        		exit;
			}
		}

	}
	//ERRORS HAVE BEEN FOUND
	else{
		//SET ERROR MESSAGE
		$error_message = $v->errorCMSMessage();
		$errors = $v->showErrors();
	}

}

//#################################################################
// EDIT BLOG POST PARAGRAPH
//#################################################################
if(isset($_POST['edit_affiliate_link'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$affiliateID	   = $_POST['affiliateID'];
	$affCatID          = $_POST['affCatID'];
	$title             = $_POST['affiliate-title'];
    $paragraph         = $_POST['paragraph'];
    $link              = $_POST['affiliate-link'];
	$image_title       = $_POST['image-title'];
    $removeImage       = $_POST['removeImage'];
    $oldImage          = $_POST['oldImage'];
    $modifiedDate      = $_POST['modifiedDate'];
    $modifiedNumber    = $_POST['modifiedNumber'];

	//HONEY POTS
	$affiliate_type    = $_POST['affiliate-type'];
	$image_type        = $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Paragraph Title', 1, 200);

    //CHECK IF DESCRIPTION HAS BEEN SUPPLIED
    if($paragraph != ''){
        $v->validateText($paragraph, 'Description', 10);
    }

    $v->validateLink($link, 'Affiliate Link');

	//IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateString($image_title, 'Image Title',3, 150);
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($affiliate_type == '' && $image_type == ''){

			//IF AN IMAGE HAS BEEN ADDED
			if($_FILES[$inputField]["tmp_name"] != ""){
				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

				//GET THE IMAGE SIZE
				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
			}
            //CHECK IF IMAGE NEEDS TO BE REMOVED
            elseif($removeImage == 1){
                $imageFile      = '';
                $image_title    = '';

                //REMOVE IMAGES
                unlink($largeDirectory.$oldImage);
                unlink($mediumDirectory.$oldImage);
                unlink($smallDirectory.$oldImage);
            }
            //IF NO NEW IMAGE HAS BEEN UPLOADED
            else{
                $imageFile      = $oldImage;

                //CHECK IF AN IMAGE TITLE IS NOT SET
                if($image_title == ''){
                    $image_title    = $affiliatesManager->getAffiliateLinkInfo($affiliateID, 'imageTitle');
                }
            }

			//INSERT AFFILIATE LINK INTO DATABASE
			$affiliatesManager->updateAffiliate($title, $paragraph, $link, $image_title, $imageFile, $modifiedDate, $modifiedNumber, $affiliateID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."affiliates-manager/crop-image.php?affCatID=".$affCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."affiliates-manager/manage-affiliate-category-content.php?affCatID=".$affCatID."&message=4");
        		exit;
			}
		}

	}
	//ERRORS HAVE BEEN FOUND
	else{
		//SET ERROR MESSAGE
		$error_message = $v->errorCMSMessage();
		$errors = $v->showErrors();
	}

}

###################################################################
//IMAGE CROP PROPERTIES
###################################################################
//GET VARIABLES FROM URL
$imageFileName	= $_GET['imageFileName'];
$width			= $_GET['width'];
$height			= $_GET['height'];
$message		= $_GET['message'];

//DEFINE FOLDER PATHS
$originalFolder	= '../../cms-images/large/';		//(folder)
$newFolder		= '../../cms-images/medium/';		//(gfolder)

//DEFINE THE NEW WIDTH AND HEIGHT OF IMAGE
$newWidth		= 250;
$newHeight		= 250;

//CALCULATE NEW RATIO
$ratio			= $newWidth / $newHeight;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$affCatID			= $_POST['affCatID'];
	$message			= $_POST['message'];

	//GET FILE EXTENSION
	$file_extension		= substr($imageFileName, strripos($imageFileName, '.'));

	//SET IMAGE SETTINGS
	if($file_extension=='.jpg' || $file_extension=='.JPG' || $file_extension=='.jpeg' || $file_extension=='.JPEG'){
		//SET IMAGE CREATE PROPERTIES
		$imageType			= 'imagejpeg';
		$imageQuality		= 100;
		$imageCreate 		= "imagecreatefromjpeg";

	}elseif($file_extension=='.png' || $file_extension=='.PNG'){
		//SET IMAGE CREATE PROPERTIES
		$imageType			= 'imagepng';
		$imageQuality		= 9;
		$imageCreate		= "imagecreatefrompng";

	}

	//CREATE A TEMP IMAGE
	$src				= $imageCreate($originalFolder.$imageFileName);

	//CREATE NEW IMAGE
	$tmp				= imagecreatetruecolor($newWidth, $newHeight);

	//SAVE TRANSPARENCY
	if($file_extension=='.png' || $file_extension=='.PNG'){
		imagealphablending( $tmp, false );
		imagesavealpha( $tmp, true );
	}

	imagecopyresampled($tmp, $src, 0,0,$_POST['x'],$_POST['y'],$newWidth,$newHeight,$_POST['w'],$_POST['h']);
	$imageType($tmp,$newFolder.$imageFileName,$imageQuality);

	//DESTROY TEMP IMAGES
	imagedestroy($tmp);
	imagedestroy($src);

	//REDIRECT TO CONTENT MANAGER PAGE
	header("Location: ".$cms_root."affiliates-manager/manage-affiliate-category-content.php?affCatID=".$affCatID."&message=".$message);
    exit;
}
###################################################################
?>
