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

class faqManager extends systemConfig{
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
            case 1: $displayMessage = 'A new FAQ has successfully been added.'; break;
            case 2: $displayMessage = 'The selected FAQ has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected FAQ has successfully been removed.'; break;
			case 4: $displayMessage = 'The selected FAQ has successfully been recovered.'; break;
            case 5: $displayMessage = 'The selected FAQ has successfully been re-activated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

    //#################################################################
	// HTML ENTITY TO SPECIAL CHARACTERS
	//#################################################################
	function HTMLEntityToSpecialCharacters($str){

		$search  = array('&lt;', '&gt;', '&euro;', '&lsquo;', '&rsquo;', '&ldquo;','&rdquo;', '&ndash;', '&mdash;', '&iexcl;','&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;','&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;','&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&OElig;', '&oelig;', '&sbquo;', '&bdquo;', '&hellip;', '&trade;', '&bull;', '&asymp;', "&#39;", '&quot;', '&amp;');

		$replace = array('<', '>', '€', '‘', '’', '“', '”', '–', '—', '¡', '¢','£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '«', '¬', '®', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã','ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ','Œ', 'œ', '‚', '„', '…', '™', '•', '˜', "'", '"', '&');

		//REPLACE VALUES
		$str = str_replace($search, $replace, $str);

		//RETURN FORMATED STRING
		return $str;
	}

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM faq WHERE faqID = ? AND deletedBy = ? ORDER BY sequence ASC", array($faqID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['faqAnswer']).' '.strip_tags($row['faqQuestions']);
		}

		//ONCE ALL TEXT IS SELECTED CALCULATE THE META KEYWORDS THAT WILL BE USED
		$params['content'] 					= $txt;				//page content
		$params['encoding'] 				= 'utf-8';			// case insensitive

		// 1-word keywords
		$params['min_word_length'] 			= 3;		// min length of single words
		$params['min_word_occur']  			= 3;		// min occur of single words

		// 2-word keyphrases
		$params['min_2words_length']        = 3;		// min length of words for 2 word phrases; value 0 will DISABLE !!!
		$params['min_2words_phrase_length'] = 3;		// min length of 2 word phrases
		$params['min_2words_phrase_occur']  = 3;		// min occur of 2 words phrase

		// 3-word keyphrases
		$params['min_3words_length']        = 3;		// min length of words for 3 word phrases; value 0 will DISABLE !!!
		$params['min_3words_phrase_length'] = 3;		// min length of 3 word phrases
		$params['min_3words_phrase_occur']  = 3;		// min occur of 3 words phrase

		$keyword = new autokeywords($params);

		//RETURN KEYWORDS
		return $keyword->get_keywords();

	}

	//#################################################################
	//GET THE META DESCRIPTION
	//#################################################################
	function getMetaDescription($faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM faq WHERE faqID = ? AND deletedBy = ? ORDER BY sequence ASC", array($faqID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['faqAnswer']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE faqID = ?", array($faqID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (faqID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($faqID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE faqID = ?",
												array($keywords, $description, $faqID));
		}
	}

	//#################################################################
    // GET FAQ INFORMATION
    //#################################################################
	function getFAQInfo($faqID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM faq WHERE faqID = ?", array($faqID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $this->HTMLEntityToSpecialCharacters($row[$field]);

	}

	//#################################################################
    // CHECK IF FAQ IS IN DATABASE
    //#################################################################
	function checkFAQDatabase($faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM faq WHERE faqID = ?", array($faqID));
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
    // GET TOTAL FAQS
    //#################################################################
	function getTotalFAQS(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM faq WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED FAQS
    //#################################################################
	function getTotalRemovedFAQS(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM faq WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // FAQ ARCHITECTURE
    //#################################################################
	function faqArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL FAQS
		$result = $connector->query("SELECT * FROM faq WHERE deletedBy = ? ORDER BY faqQuestions ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$faqID		    = $row['faqID'];
				$faqQuestions	= $this->HTMLEntityToSpecialCharacters($row['faqQuestions']);

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td>'.$faqQuestions.'</td>
					<td align="center">
						<a href="'.$cms_root.'faq-manager/edit-faq.php?faqID='.$faqID.'" title="Modify">Modify</a>
					</td>
					<td align="center">
					<form name="delete_faq'.$faqID.'">
						<input type="hidden" name="delete_faq" value="1">
						<input type="hidden" name="faqID" value="'.$faqID.'">
						<a href="javascript:deleteFAQ('.$faqID.')" title="Remove">Remove</a>
					</form>
					</td>
				  </tr>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="5">There are currently no FAQs available. <a href="'.$cms_root.'faq-manager/add-faq.php" title="Add FAQ">Please add a FAQ here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // FAQ ARCHITECTURE (REMOVED)
    //#################################################################
	function faqArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM faq WHERE deletedBy != ? ORDER BY faqQuestions ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$faqID		    = $row['faqID'];
			$faqQuestions	= $this->HTMLEntityToSpecialCharacters($row['faqQuestions']);

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$faqQuestions.'</td>
				<td align="center">
				<form name="recover_faq'.$faqID.'">
					<input type="hidden" name="recover_faq" value="1">
					<input type="hidden" name="faqID" value="'.$faqID.'">
					<a href="javascript:recoverFAQ('.$faqID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
	//OVERWRITE FAQ
	//#################################################################
	function overwriteFAQ($question, $answer){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$name	    = strip_tags($name);
        $link	    = strip_tags($link);

		//RE-ACTIVATE FAQ
		$update = $connector->query("UPDATE faq SET
                                    faqAnswer = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE faqQuestions = ?",
									array($answer, '0', '0000-00-00 00:00:00', $question));

	}

	//#################################################################
    // CHECK IF ANY FAQ HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedFAQ(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM faq WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

    //#################################################################
    // CHECK IF FAQ INFO HAS BEEN CHANGED
    //#################################################################
	function checkFAQChanges($question, $answer, $faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM faq WHERE faqQuestions = ? AND faqAnswer = ? AND faqID = ?", array($question, $answer, $faqID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD FAQ
    //#################################################################
	function addFAQ($question, $answer){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name		    = strip_tags($question);

		//ADD USER
		$insert = $connector->query("INSERT INTO faq(faqQuestions, faqAnswer, createdBy, createdDate)
									VALUES (?, ?, ?, ?)",
									array($question, $answer, $currentUser, $currentDate));

	}

	//#################################################################
    // UPDATE FAQ
    //#################################################################
	function updateFAQ($question, $answer, $modifiedBy, $modifiedDate, $modifiedNumber, $faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$name         	= strip_tags($question);

		//UPDATE USER
		$update = $connector->query("UPDATE faq SET
									faqQuestions = ?,
                                    faqAnswer = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE faqID = ?",
									array($question, $answer, $modifiedBy, $modifiedDate, $modifiedNumber, $faqID));

	}

	//#################################################################
    // DELETE FAQ
    //#################################################################
	function deleteFAQ($faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE FAQ
		$remove = $connector->query("UPDATE faq SET
									deletedBy = ?,
									deletedDate = ?
									WHERE faqID = ?",
									array($currentUser, $currentDate, $faqID));

	}

	//#################################################################
    // RECOVER FAQ
    //#################################################################
	function recoverFAQ($faqID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE faq SET
									deletedBy = ?,
									deletedDate = ?
									WHERE faqID = ?",
									array('0', '0000-00-00 00:00:00', $faqID));

	}

	//#################################################################
    // CHECK IF FAQ NAME IS ALREADY IN USE
    //#################################################################
	function addFAQCheck($question){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM faq WHERE faqQuestions = ?", array($question));
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
				return 'removed_faq';
			}
		}

	}

}

//DEFINE CLASS
$faqManager = new faqManager();


//#################################################################
//DELETE FAQ
//#################################################################
if(isset($_POST['delete_faq'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $faqID	= $_POST['faqID'];

    //SET FAQ AS REMOVED IN DATABASE
    $faqManager->deleteFAQ($faqID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."faq-manager/index.php?message=3");
    exit;
}

//#################################################################
//RECOVER FAQ
//#################################################################
if(isset($_POST['recover_faq'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $faqID	= $_POST['faqID'];

    //SET FAQ AS ACTIVE IN DATABASE
    $faqManager->recoverFAQ($faqID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."faq-manager/index.php?message=4");
    exit;
}

//#################################################################
// ADD FAQ
//#################################################################
if(isset($_POST['add_faq'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$question    = $_POST['faq-question'];
    $answer      = $_POST['paragraph'];

	//HONEY POTS
	$faq_type	= $_POST['faq-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $question           = $userLogin->specialCharactersToHTMLEntity($question);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($question, 'FAQ Question', 1, 200);
    $v->validateText($answer, 'FAQ Answer', 10);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($faq_type == ''){

            //CHECK IF FAQ NAME IS ALREADY IN USE
			$faq_used = $faqManager->addFAQCheck($question);
            if($faq_used == 'unused'){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$answer = str_replace('\r\n', '', $answer);

    			//INSERT FAQ INTO DATABASE
    			$videoTutCatID = $faqManager->addFAQ($question, $answer);

                //REDIRECT USER
    			header("Location: ".$cms_root."faq-manager/index.php?message=1");
        		exit;

			}
			//IF FAQ HAS BEEN REMOVED
			elseif($faq_used == 'removed_faq'){
				//SET USER AS REMOVED
				$removed_faq = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>FAQ Question</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT FAQ
//#################################################################
if(isset($_POST['edit_faq'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$question   = $_POST['faq-question'];
    $answer     = $_POST['paragraph'];
    $faqID      = $_POST['faqID'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$faq_type	= $_POST['faq-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $question           = $userLogin->specialCharactersToHTMLEntity($question);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($question, 'FAQ Question', 1, 200);
    $v->validateText($answer, 'FAQ Answer', 10);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($faq_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($faqManager->checkFAQChanges($question, $answer, $faqID) == 'changed'){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$answer = str_replace('\r\n', '', $answer);

				//UPDATE FAQ IN DATABASE
				$faqManager->updateFAQ($question, $answer, $modifiedBy, $modifiedDate, $modifiedNumber, $faqID);

                //REDIRECT USER
			    header("Location: ".$cms_root."faq-manager/index.php?message=2");
        		exit;

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."faq-manager/");
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
// REACTIVATE FAQ
//#################################################################
if(isset($_POST['reactivate-faq-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$question      	= $_POST['faq-question'];
    $answer		    = $_POST['paragraph'];

	//HONEY POTS
	$faq_type  = $_POST['faq-type'];

	if($faq_type == ''){

		//OVERWRITE FAQ
		$faqManager->overwriteFAQ($question, $answer);

		//REDIRECT PAGE
		header("Location: ".$cms_root."faq-manager/index.php?message=5");
		exit;
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
	$blogPostID			= $_POST['blogPostID'];
	$blogCatID			= $_POST['blogCatID'];
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
	header("Location: ".$cms_root."faq-manager/index.php?message=".$message);
    exit;
}
###################################################################
?>
