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

class softwareManager extends systemConfig{
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
            case 1: $displayMessage = 'A new Software has successfully been added.'; break;
            case 2: $displayMessage = 'The selected Software has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected Software has successfully been removed.'; break;
			case 4: $displayMessage = 'The selected Software has successfully been recovered.'; break;
            case 5: $displayMessage = 'The selected Software has successfully been re-activated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ? AND deletedBy = ? ORDER BY sequence ASC", array($softwareID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['softwareDescription']).' '.strip_tags($row['softwareName']);
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
	function getMetaDescription($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ? AND deletedBy = ? ORDER BY sequence ASC", array($softwareID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['softwareDescription']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE softwareID = ?", array($softwareID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (softwareID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($softwareID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE softwareID = ?",
												array($keywords, $description, $softwareID));
		}
	}

	//#################################################################
    // GET SOFTWARE INFORMATION
    //#################################################################
	function getSoftwareInfo($softwareID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ?", array($softwareID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET SOFTWARE IMAGE
    //#################################################################
	function getSoftwareImage($softwareID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ?", array($softwareID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['softwareImage'];
		$imageTitle	= $row['softwareImageName'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

	//#################################################################
    // CHECK IF SOFTWARE IS IN DATABASE
    //#################################################################
	function checkSoftwareDatabase($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ?", array($softwareID));
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
    // GET TOTAL SOFTWARES
    //#################################################################
	function getTotalSoftwares(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM softwares WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED SOFTWARES
    //#################################################################
	function getTotalRemovedSoftwares(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM softwares WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // SOFTWARE ARCHITECTURE
    //#################################################################
	function softwareArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL SOFTWARES
		$result = $connector->query("SELECT * FROM softwares WHERE deletedBy = ? ORDER BY softwareName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$softwareID		= $row['softwareID'];
				$softwareName	= $row['softwareName'];

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td>'.$softwareName.'</td>
					<td align="center">
						<a href="'.$cms_root.'software-manager/edit-software.php?softwareID='.$softwareID.'" title="Modify">Modify</a>
					</td>
					<td align="center">
					<form name="delete_software'.$softwareID.'">
						<input type="hidden" name="delete_software" value="1">
						<input type="hidden" name="softwareID" value="'.$softwareID.'">
						<a href="javascript:deleteSoftware('.$softwareID.')" title="Remove">Remove</a>
					</form>
					</td>
				  </tr>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="5">There are currently no Softwares available. <a href="'.$cms_root.'software-manager/add-software.php" title="Add Software">Please add a software here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // SOFTWARE ARCHITECTURE (REMOVED)
    //#################################################################
	function softwareArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM softwares WHERE deletedBy != ? ORDER BY softwareName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$softwareID		= $row['softwareID'];
			$softwareName	= $row['softwareName'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$softwareName.'</td>
				<td align="center">
				<form name="recover_software'.$softwareID.'">
					<input type="hidden" name="recover_software" value="1">
					<input type="hidden" name="softwareID" value="'.$softwareID.'">
					<a href="javascript:recoverSoftware('.$softwareID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
	//OVERWRITE SOFTWARE
	//#################################################################
	function overwriteSoftware($name, $paragraph, $link){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$name	    = strip_tags($name);
        $link	    = strip_tags($link);

		//RE-ACTIVATE SOFTWARE
		$update = $connector->query("UPDATE softwares SET
                                    softwareLink = ?,
                                    softwareDescription = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE softwareName = ?",
									array($link, $paragraph, '0', '0000-00-00 00:00:00', $name));

	}

	//#################################################################
    // CHECK IF ANY SOFTWARES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedSoftware(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM softwares WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

    //#################################################################
    // CHECK IF SOFTWARE INFO HAS BEEN CHANGED
    //#################################################################
	function checkSoftwareChanges($name, $paragraph, $link, $image_title, $softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM softwares WHERE softwareName = ? AND softwareImageName = ? AND softwareLink = ? AND softwareDescription = ? AND softwareID = ?", array($name, $image_title, $link, $paragraph, $softwareID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD SOFTWARE
    //#################################################################
	function addSoftware($name, $paragraph, $link, $image_title, $imageFile){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name		    = strip_tags($name);
        $image_title    = strip_tags($image_title);

		//ADD USER
		$insert = $connector->query("INSERT INTO softwares(softwareName, softwareDescription, softwareLink, softwareImage, softwareImageName, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?)",
									array($name, $paragraph, $link, $imageFile, $image_title, $currentUser, $currentDate));

	}

	//#################################################################
    // UPDATE SOFTWARE
    //#################################################################
	function updateSoftware($name, $paragraph, $link, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$name         	= strip_tags($name);
        $link           = strip_tags($link);
        $image_title    = strip_tags($image_title);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM softwares WHERE softwareID = ?", array($softwareID));
        $row    = $connector->fetchArray($result);
        $image  = $row['softwareImage'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE softwares SET
									softwareName = ?,
                                    softwareImage = ?,
                                    softwareImageName = ?,
                                    softwareLink = ?,
                                    softwareDescription = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE softwareID = ?",
									array($name, $imageFile, $image_title, $link, $paragraph, $modifiedBy, $modifiedDate, $modifiedNumber, $softwareID));

	}

	//#################################################################
    // DELETE SOFTWARE
    //#################################################################
	function deleteSoftware($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE softwares SET
									deletedBy = ?,
									deletedDate = ?
									WHERE softwareID = ?",
									array($currentUser, $currentDate, $softwareID));

	}

	//#################################################################
    // RECOVER SOFTWARE
    //#################################################################
	function recoverSoftware($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE softwares SET
									deletedBy = ?,
									deletedDate = ?
									WHERE softwareID = ?",
									array('0', '0000-00-00 00:00:00', $softwareID));

	}

	//#################################################################
    // CHECK IF SOFTWARE NAME IS ALREADY IN USE
    //#################################################################
	function addSoftwareCheck($softwareName){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM softwares WHERE softwareName = ?", array($softwareName));
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
				return 'removed_software';
			}
		}

	}

}

//DEFINE CLASS
$softwareManager = new softwareManager();


//#################################################################
//DELETE SOFTWARE
//#################################################################
if(isset($_POST['delete_software'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $softwareID	= $_POST['softwareID'];

    //SET SOFTWARE AS REMOVED IN DATABASE
    $softwareManager->deleteSoftware($softwareID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."software-manager/index.php?message=3");
    exit;
}

//#################################################################
//RECOVER SOFTWARE
//#################################################################
if(isset($_POST['recover_software'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $softwareID	= $_POST['softwareID'];

    //SET SOFTWARE AS ACTIVE IN DATABASE
    $softwareManager->recoverSoftware($softwareID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."software-manager/index.php?message=4");
    exit;
}

//#################################################################
// ADD SOFTWARE
//#################################################################
if(isset($_POST['add_software'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name		    = $_POST['software-name'];
    $paragraph      = $_POST['paragraph'];
    $link           = $_POST['software-link'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$software_type	= $_POST['software-type'];
	$image_type		= $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name           = $userLogin->specialCharactersToHTMLEntity($name);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Software Name', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
    $v->validateLink($link, 'Software Link');
	$v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($software_type == '' && $image_type == ''){

            //CHECK IF SOFTWARE NAME IS ALREADY IN USE
			$software_used = $softwareManager->addSoftwareCheck($name);
            if($software_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

    			//INSERT SOFTWARE INTO DATABASE
    			$videoTutCatID = $softwareManager->addSoftware($name, $paragraph, $link, $image_title, $imageFile);

                //REDIRECT USER
    			header("Location: ".$cms_root."software-manager/crop-image.php?imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;

			}
			//IF SOFTWARE HAS BEEN REMOVED
			elseif($software_used == 'removed_software'){
				//SET USER AS REMOVED
				$removed_software = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Software Name</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT SOFTWARE
//#################################################################
if(isset($_POST['edit_software'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name           = $_POST['software-name'];
    $paragraph      = $_POST['paragraph'];
    $link           = $_POST['software-link'];
	$softwareID    	= $_POST['softwareID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$software_type	= $_POST['software-type'];
    $image_type     = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name           = $userLogin->specialCharactersToHTMLEntity($name);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($name, 'Software Name', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
    $v->validateLink($link, 'Software Link');
	$v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($software_type == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($softwareManager->checkSoftwareChanges($name, $paragraph, $link, $image_title, $softwareID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}
                //IF NO NEW IMAGE HAS BEEN UPLOADED
                else{
                    $imageFile      = $oldImage;
                }

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

				//UPDATE USER IN DATABASE
				$softwareManager->updateSoftware($name, $paragraph, $link, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $softwareID);

                //IF A NEW IMAGE HAS BEEN UPLOADED
                if($_FILES[$inputField]["tmp_name"] != ""){
                //REDIRECT USER
    			    header("Location: ".$cms_root."software-manager/crop-image.php?imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
            		exit;
                }else{
                    header("Location: ".$cms_root."software-manager/index.php?message=2");
            		exit;
                }

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."software-manager/");
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
// REACTIVATE SOFTWARE
//#################################################################
if(isset($_POST['reactivate-software-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name          		= $_POST['software-name'];
    $paragraph		    = $_POST['paragraph'];
    $link   		    = $_POST['software-link'];

	//HONEY POTS
	$software_type  = $_POST['software-type'];
    $image_type		= $_POST['image-type'];

	if($image_type == '' && $software_type == ''){

		//OVERWRITE SOFTWARE
		$softwareManager->overwriteSoftware($name, $paragraph, $link);

		//REDIRECT PAGE
		header("Location: ".$cms_root."software-manager/index.php?message=5");
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
	header("Location: ".$cms_root."software-manager/index.php?message=".$message);
    exit;
}
###################################################################
?>
