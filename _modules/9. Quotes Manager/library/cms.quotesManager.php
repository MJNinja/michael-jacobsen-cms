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
require_once("class.formValidation.php");

class quoteManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Quote has successfully been added.'; break;
            case 3: $displayMessage = 'The selected Category has successfully been updated.'; break;
			case 4: $displayMessage = 'The selected Quote has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Category has successfully been removed.'; break;
			case 6: $displayMessage = 'The selected Quote has successfully been removed.'; break;
			case 7: $displayMessage = 'The selected Category has successfully been recovered.'; break;
			case 8: $displayMessage = 'The selected Category has successfully been re-activated.'; break;
			case 9: $displayMessage = 'The selected Quote has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Quote has successfully been re-activated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($quoteCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//ESCAPE STRING
		$userID = $connector->escape($userID);

		//GET USER INFO
		$result = $connector->query("SELECT * FROM quotes_category WHERE quoteCatID = ?", array($quoteCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET QUOTE INFORMATION
    //#################################################################
	function getQuoteInfo($quoteID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//ESCAPE STRING
		$userID = $connector->escape($userID);

		//GET USER INFO
		$result = $connector->query("SELECT * FROM quotes WHERE quoteID = ?", array($quoteID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // CHECK IF CATEGORY IS IN DATABASE
    //#################################################################
	function checkCategoryDatabase($quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//ESCAPE STRING
		$quoteCatID = $connector->escape($quoteCatID);

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM quotes_category WHERE quoteCatID = ?", array($quoteCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}

	}

	//#################################################################
    // CHECK IF QUOTE IS IN DATABASE
    //#################################################################
	function checkQuoteDatabase($quoteID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//ESCAPE STRING
		$quoteID = $connector->escape($quoteID);

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM quotes WHERE quoteID = ?", array($quoteID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF CATEGORY AND QUOTE ARE IN DATABASE
    //#################################################################
	function checkCategoryQuoteDatabase($quoteID, $quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//ESCAPE STRING
		$quoteID = $connector->escape($quoteID);
		$quoteID = $connector->escape($quoteCatID);

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM quotes WHERE quoteID = ? AND quoteCatID = ?", array($quoteID, $quoteCatID));
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

		//ESCAPE STRING
		$userID = $connector->escape($userID);

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE userID = ?", array($userID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row['name'].' '.$row['surname'];

	}

	//#################################################################
    // GET TOTAL CATEGORIES
    //#################################################################
	function getTotalCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM quotes_category WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVES CATEGORIES
    //#################################################################
	function getTotalRemovedCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM quotes_category WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL QUOTES
    //#################################################################
	function getTotalQuotes(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM quotes WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED QUOTES
    //#################################################################
	function getTotalRemovedQuotes(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM quotes WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // QUOTE CATEGORY ARCHITECTURE
    //#################################################################
	function categoryArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['user'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM quotes_category WHERE deletedBy = ? ORDER BY categoryName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$empty			= '';
				$empty_bg		= '';
				$quoteCatID		= $row['quoteCatID'];
				$categoryName	= $row['categoryName'];

				//GET ALL QUOTES FOR A CATEGORY
				$result2	= $connector->query("SELECT * FROM quotes WHERE quoteCatID = ? AND deletedBy = ?", array($quoteCatID, '0'));
				$quoteTotal	= $connector->numResults($result2);

				//IF CATEGORY IS EMPTY
				if($quoteTotal == 0){
					$empty		= '<span class="empty-category-text">(Empty)</span>';
					$empty_bg	='class="empty-category"';
				}

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$empty_bg.'>'.$categoryName.' '.$empty.'</td>
					<td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'quote-manager/manage-quote-category-content.php?quoteCatID='.$quoteCatID.'" title="Manage">Manage</a>
					</td>
					<td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'quote-manager/edit-quote-category.php?quoteCatID='.$quoteCatID.'" title="Modify">Modify</a>
					</td>
					<td '.$empty_bg.' align="center">';

					//IF NO QUOTES ARE IN THE CATEGORY
					if($quoteTotal == 0){

						$txt.='<form name="delete_category'.$quoteCatID.'">
							<input type="hidden" name="delete_category" value="1">
							<input type="hidden" name="quoteCatID" value="'.$quoteCatID.'">
							<a href="javascript:deleteCategory('.$quoteCatID.')" title="Remove">Remove</a>
						</form>';
					}
					//IF QUOTES ARE IN THE CATEGORY
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
				<td colspan="5">There are currently no Categories available. <a href="'.$cms_root.'quote-manager/add-quote-category.php" title="Add Quote Category">Please add a category here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // QUOTE CATEGORY ARCHITECTURE (REMOVED)
    //#################################################################
	function categoryArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM quotes_category WHERE deletedBy != ? ORDER BY categoryName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$quoteCatID		= $row['quoteCatID'];
			$categoryName	= $row['categoryName'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$categoryName.'</td>
				<td align="center">
				<form name="recover_category'.$quoteCatID.'">
					<input type="hidden" name="recover_category" value="1">
					<input type="hidden" name="quoteCatID" value="'.$quoteCatID.'">
					<a href="javascript:recoverCategory('.$quoteCatID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';

		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // QUOTE ARCHITECTURE
    //#################################################################
	function quoteArchitecture($cms_root, $quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['user'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM quotes WHERE deletedBy = ?  AND quoteCatID = ? ORDER BY quoteText, quoteBy ASC", array('0', $quoteCatID));
		$quotesTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($quotesTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$quoteID		= $row['quoteID'];
				$quoteText		= $row['quoteText'];
				$quoteBy		= $row['quoteBy'];

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder active-content">
                    	<div class="quote"><b>'.$quoteText.'</b></div>
                        <div class="quote-author">- '.$quoteBy.'</div>
                        <div class="module-manage-content-links">
							<form name="delete_quote'.$quoteID.'">
								<input type="hidden" name="delete_quote" value="1">
								<input type="hidden" name="quoteID" value="'.$quoteID.'">
								<input type="hidden" name="quoteCatID" value="'.$quoteCatID.'">
								<a href="javascript:deleteQuote('.$quoteID.')" title="Remove Quote">Remove Quote</a>
							</form>
							<a href="'.$cms_root.'quote-manager/edit-quote.php?quoteID='.$quoteID.'&quoteCatID='.$quoteCatID.'" title="Edit Quote">Edit Quote</a>
							<div class="clear"></div>
							</div>
                    </div>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Quotes available. <a href="'.$cms_root.'quote-manager/add-quote.php?quoteCatID='.$quoteCatID.'" title="Add Quote">Please add a quote here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // QUOTE ARCHITECTURE (REMOVED)
    //#################################################################
	function quoteArchitectureRemoved($cms_root, $quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM quotes WHERE deletedBy != ? AND quoteCatID = ? ORDER BY quoteText, quoteBy ASC", array('0', $quoteCatID));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$quoteID		= $row['quoteID'];
			$quoteText		= $row['quoteText'];
			$quoteBy		= $row['quoteBy'];

			//GENERATE OUPUT
			$txt.= '<div class="module-manage-content-holder removed-content">
					<div class="quote"><b>'.$quoteText.'</b></div>
					<div class="quote-author">- '.$quoteBy.'</div>
					<div class="module-manage-content-links">
						<form name="recover_quote'.$quoteID.'">
							<input type="hidden" name="recover_quote" value="1">
							<input type="hidden" name="quoteID" value="'.$quoteID.'">
							<input type="hidden" name="quoteCatID" value="'.$quoteCatID.'">
							<a href="javascript:recoverQuote('.$quoteID.')" title="Recover">Recover</a>
						</form>
						<div class="clear"></div>
						</div>
				</div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF ANY CATEGORIES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM quotes_category WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF ANY QUOTES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedQuotes($quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM quotes WHERE deletedBy != ? AND quoteCatID = ?", array('0', $quoteCatID));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkCategoryChanges($category_name, $quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM quotes_category WHERE categoryName = ? AND quoteCatID = ?", array($category_name, $quoteCatID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // CHECK IF QUOTE INFO HAS BEEN CHANGED
    //#################################################################
	function checkQuoteChanges($author_name, $quote, $quoteID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM quotes WHERE quoteText = ? AND quoteBy = ? AND quoteID = ?", array($quote, $author_name, $quoteID));
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
		$currentUser = $_SESSION['user'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$category_name		= strip_tags($category_name);

		//ADD USER
		$insert = $connector->query("INSERT INTO quotes_category (categoryName, createdBy, createdDate)
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
		$currentUser = $_SESSION['user'];
		$currentDate = date('Y-m-d H:i:s');

		//UPDATE USER
		$update = $connector->query("UPDATE quotes_category SET
									categoryName = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE categoryName = ?",
									array($category_name, '0', '0000-00-00 00:00:00', $category_name));

	}

	//#################################################################
    // UPDATE CATEGORY
    //#################################################################
	function updateCategory($category_name, $modifiedBy, $modifiedDate, $modifiedNumber, $quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//UPDATE USER
		$update = $connector->query("UPDATE quotes_category SET
									categoryName = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE quoteCatID = ?",
									array($category_name, $modifiedBy, $modifiedDate, $modifiedNumber, $quoteCatID));

	}

	//#################################################################
    // DELETE CATEGORY
    //#################################################################
	function deleteCategory($quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['user'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE quotes_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE quoteCatID = ?",
									array($currentUser, $currentDate, $quoteCatID));

	}

	//#################################################################
    // ADD QUOTE
    //#################################################################
	function addQuote($author_name, $quote, $quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['user'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$author_name		= strip_tags($author_name);
		$quote				= strip_tags($quote);

		//ADD USER
		$insert = $connector->query("INSERT INTO quotes (quoteCatID, quoteText, quoteBy, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?)",
									array($quoteCatID, $quote, $author_name, $currentUser, $currentDate));

	}

	//#################################################################
	//OVERWRITE QUOTE
	//#################################################################
	function overwriteQuote($quoteCatID, $author_name, $quote){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['user'];
		$currentDate = date('Y-m-d H:i:s');

		//UPDATE USER
		$update = $connector->query("UPDATE quotes SET
									quoteBy = ?,
									quoteCatID = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE quoteText = ?",
									array($author_name, $quoteCatID, '0', '0000-00-00 00:00:00', $quote));

	}

	//#################################################################
    // UPDATE QUOTE
    //#################################################################
	function updateQuote($author_name, $quote, $modifiedBy, $modifiedDate, $modifiedNumber, $quoteID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//UPDATE USER
		$update = $connector->query("UPDATE quotes SET
									quoteText = ?,
									quoteBy =?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE quoteID = ?",
									array($quote, $author_name, $modifiedBy, $modifiedDate, $modifiedNumber, $quoteID));

	}

	//#################################################################
    // DELETE QUOTE
    //#################################################################
	function deleteQuote($quoteID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['user'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE quotes SET
									deletedBy = ?,
									deletedDate = ?
									WHERE quoteID = ?",
									array($currentUser, $currentDate, $quoteID));

	}

	//#################################################################
    // RECOVER CATEGORY
    //#################################################################
	function recoverCategory($quoteCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE quotes_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE quoteCatID = ?",
									array('0', '0000-00-00 00:00:00', $quoteCatID));

	}

	//#################################################################
    // RECOVER QUOTE
    //#################################################################
	function recoverQuote($quoteID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE quotes SET
									deletedBy = ?,
									deletedDate = ?
									WHERE quoteID = ?",
									array('0', '0000-00-00 00:00:00', $quoteID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM quotes_category WHERE categoryName = ?", array($category_name));
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
				return 'removed_category';
			}
		}

	}

	//#################################################################
    // CHECK IF QUOTE IS ALREADY IN USE
    //#################################################################
	function addQuoteCheck($quote){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK QUOTE
		$result = $connector->query("SELECT * FROM quotes WHERE quoteText = ?", array($quote));
		$total	= $connector->numResults($result);

		//IF QUOTE HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
		//IF QUOTE HAS BEEN USED
		elseif($total == 1){
			//GET USER INFO
			$row 		= $connector->fetchArray($result);

			//SET VARIABLES
			$deletedBy	= $row['deletedBy'];

			//IF CATEGORY HAS BEEN REMOVED
			if($deletedBy != 0){
				return 'removed_quote';
			}
		}

	}

	//#################################################################
    // CHECK IF CATEGORY IS ALREADY IN USE
    //#################################################################
	function editCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM quotes_category WHERE categoryName = ?", array($category_name));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF QUOTE IS ALREADY IN USE
    //#################################################################
	function editQuoteCheck($quoteID, $quote){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM quotes WHERE quoteText = ? AND quoteID = ?", array($quote, $quoteID));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

}

//DEFINE CLASS
$quoteManager = new quoteManager();

//#################################################################
// ADD QUOTE CATEGORY
//#################################################################
if(isset($_POST['add_quote_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$category_name 		= $connector->escape($_POST['category-name']);

	//HONEY POTS
	$category_type		= $connector->escape($_POST['category-type']);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($category_name, 'Category Name', 2, 150);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($category_type == ''){

			//CHECK IF EMAIL IS ALREADY IN USE
			$category_used = $quoteManager->addCategoryCheck($category_name);
			if($category_used == 'unused'){

				//INSERT USER INTO DATABASE
				$quoteManager->addCategory($category_name);

				//REDIRECT USER
				header("Location: ".$cms_root."quote-manager/index.php?message=1");

			}
			//IF USER HAS BEEN REMOVED
			elseif($category_used == 'removed_category'){
				//SET USER AS REMOVED
				$removed_category = '1';
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
	$category_name		= $connector->escape($_POST['category-name']);;

	//HONEY POTS
	$category_type		= $connector->escape($_POST['category-type']);

	if($category_type == ''){

		//OVERWRITE USER
		$quoteManager->overwriteCategory($category_name);

		//REDIRECT PAGE
		header("Location: ".$cms_root."quote-manager/index.php?message=8");
	}
}

//#################################################################
// EDIT CATEGORY
//#################################################################
if(isset($_POST['edit_quote_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$category_name 	= $connector->escape($_POST['category-name']);
	$quoteCatID		= $connector->escape($_POST['quoteCatID']);

	$modifiedDate	= $connector->escape($_POST['modifiedDate']);
	$modifiedBy		= $_SESSION['user'];
	$modifiedNumber	= $connector->escape($_POST['modifiedNumber']);

	//HONEY POTS
	$category_type	= $connector->escape($_POST['category-type']);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($category_name, 'Category Name', 2, 150);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($category_type == ''){


			//CHECK IF CONTENT HAS BEEN CHANGED
			if($quoteManager->checkCategoryChanges($category_name, $quoteCatID) == 'changed'){

				//CHECK CATEGORY IS USED
				$category_used = $quoteManager->editCategoryCheck($category_name);
				if($category_used == 'unused'){

					//UPDATE USER IN DATABASE
					$quoteManager->updateCategory($category_name, $modifiedBy, $modifiedDate, $modifiedNumber, $quoteCatID);

					//REDIRECT USER
					header("Location: ".$cms_root."quote-manager/index.php?message=3");

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
				header("Location: ".$cms_root."quote-manager/");
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
if(isset($_POST['delete_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $quoteCatID	= $connector->escape($_POST['quoteCatID']);

    //SET USER AS REMOVED IN DATABASE
    $quoteManager->deleteCategory($quoteCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."quote-manager/index.php?message=5");
}

//#################################################################
//RECOVER CATEGORY
//#################################################################
if(isset($_POST['recover_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $quoteCatID	= $connector->escape($_POST['quoteCatID']);

    //SET USER AS REMOVED IN DATABASE
    $quoteManager->recoverCategory($quoteCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."quote-manager/index.php?message=7");
}

//#################################################################
// ADD QUOTE
//#################################################################
if(isset($_POST['add_quote'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$quoteCatID			= $connector->escape($_POST['quoteCatID']);
	$author_name 		= $connector->escape($_POST['author-name']);
	$quote				= $connector->escape($_POST['paragraph']);

	//HONEY POTS
	$quote_type		= $connector->escape($_POST['quote-type']);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($author_name, 'Author Name', 2, 150);
	$v->validateText($quote, 'Quote', 10);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($quote_type == ''){

			//CHECK IF EMAIL IS ALREADY IN USE
			$quote_used = $quoteManager->addQuoteCheck($quote);
			if($quote_used == 'unused'){

				//INSERT USER INTO DATABASE
				$quoteManager->addQuote($author_name, $quote, $quoteCatID);

				//REDIRECT USER
				header("Location: ".$cms_root."quote-manager/manage-quote-category-content.php?quoteCatID=".$quoteCatID."&message=2");

			}
			//IF USER HAS BEEN REMOVED
			elseif($quote_used == 'removed_quote'){
				//SET USER AS REMOVED
				$removed_quote = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Quote</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT QUOTE
//#################################################################
if(isset($_POST['edit_quote'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$quoteCatID 	= $connector->escape($_POST['quoteCatID']);
	$quoteID		= $connector->escape($_POST['quoteID']);
	$author_name	= $connector->escape($_POST['author-name']);
	$quote			= $connector->escape($_POST['paragraph']);

	$modifiedDate	= $connector->escape($_POST['modifiedDate']);
	$modifiedBy		= $_SESSION['user'];
	$modifiedNumber	= $connector->escape($_POST['modifiedNumber']);

	//HONEY POTS
	$quote_type		= $connector->escape($_POST['quote-type']);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($author_name, 'Author Name', 2, 150);
	$v->validateText($quote, 'Quote', 10);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($quote_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($quoteManager->checkQuoteChanges($author_name, $quote, $quoteID) == 'changed'){

				//CHECK QUOTE IS USED
				$quote_used = $quoteManager->editQuoteCheck($quoteID, $quote);
				if($quote_used == 'unused'){

					//UPDATE USER IN DATABASE
					$quoteManager->updateQuote($author_name, $quote, $modifiedBy, $modifiedDate, $modifiedNumber, $quoteID);

					//REDIRECT USER
					header("Location: ".$cms_root."quote-manager/manage-quote-category-content.php?quoteCatID=".$quoteCatID."&message=4");

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Quote</b> you supplied is already in use. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."quote-manager/manage-quote-category-content.php?quoteCatID=".$quoteCatID);
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
// REACTIVATE QUOTE
//#################################################################
if(isset($_POST['reactivate-quote-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$quoteCatID			= $connector->escape($_POST['quoteCatID']);
	$author_name		= $connector->escape($_POST['author_name']);
	$quote				= $connector->escape($_POST['quote']);

	//HONEY POTS
	$quote_type			= $connector->escape($_POST['quote_type']);

	if($quote_type == ''){

		//OVERWRITE USER
		$quoteManager->overwriteQuote($quoteCatID, $author_name, $quote);

		//REDIRECT PAGE
		header("Location: ".$cms_root."quote-manager/manage-quote-category-content.php?quoteCatID=".$quoteCatID."&message=10");
	}
}

//#################################################################
//DELETE QUOTE
//#################################################################
if(isset($_POST['delete_quote'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$quoteID	= $connector->escape($_POST['quoteID']);
    $quoteCatID	= $connector->escape($_POST['quoteCatID']);

    //SET USER AS REMOVED IN DATABASE
    $quoteManager->deleteQuote($quoteID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."quote-manager/manage-quote-category-content.php?quoteCatID=".$quoteCatID."&message=6");
}

//#################################################################
//RECOVER QUOTE
//#################################################################
if(isset($_POST['recover_quote'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $quoteID	= $connector->escape($_POST['quoteID']);
	$quoteCatID	= $connector->escape($_POST['quoteCatID']);

    //SET USER AS REMOVED IN DATABASE
    $quoteManager->recoverQuote($quoteID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."quote-manager/manage-quote-category-content.php?quoteCatID=".$quoteCatID."&message=9");
}
?>
