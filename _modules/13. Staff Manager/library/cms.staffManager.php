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

class staffManager extends systemConfig{
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
            case 1: $displayMessage = 'A new Staff Member has successfully been added.'; break;
            case 2: $displayMessage = 'The selected Staff Member has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected Staff Member has successfully been removed.'; break;
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
    // GET STAFF INFORMATION
    //#################################################################
	function getStaffInfo($staffID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM staff_members WHERE staffID = ?", array($staffID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET STAFF IMAGE
    //#################################################################
	function getStaffImage($staffID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM staff_members WHERE staffID = ?", array($staffID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['staffImage'];
		$imageTitle	= $row['staffImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
            //GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div><br /><input type="checkbox" value="1" name="removeImage" />Remove Image from paragraph</div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

	//#################################################################
    // CHECK IF STAFF MEMBER IS IN DATABASE
    //#################################################################
	function checkStaffMemberDatabase($staffID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM staff_members WHERE staffID = ?", array($staffID));
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
    // GET TOTAL STAFF
    //#################################################################
	function getTotalStaff(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET TOTAL STAFF MEMBERS
		$result = $connector->query("SELECT * FROM staff_members WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // STAFF ARCHITECTURE
    //#################################################################
	function staffArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL SOFTWARES
		$result = $connector->query("SELECT * FROM staff_members WHERE deletedBy = ? ORDER BY sequence ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$staffID		= $row['staffID'];
                $staffName	    = $row['staffName'];
				$staffSurname	= $row['staffSurname'];
                $staffPosition  = $row['staffPosition'];

				//GENERATE OUPUT
				$txt.= '<tr>
					<td>'.$staffName.' '.$staffSurname.'</td>
                    <td>'.$staffPosition.'</td>
					<td align="center">
						<a href="'.$cms_root.'staff-manager/edit-staff.php?staffID='.$staffID.'" title="Modify">Modify</a>
					</td>
					<td align="center">
					<form name="delete_staff'.$staffID.'">
						<input type="hidden" name="delete_staff" value="1">
						<input type="hidden" name="staffID" value="'.$staffID.'">
						<a href="javascript:deleteStaff('.$staffID.')" title="Remove">Remove</a>
					</form>
					</td>
				  </tr>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="4">There are currently no Staff Members available. <a href="'.$cms_root.'staff-manager/add-staff.php" title="Add Software">Please add a staff member here!</a></td>
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
    // CHECK IF STAFF MEMBER INFO HAS BEEN CHANGED
    //#################################################################
	function checkStaffChanges($name, $surname, $position, $email, $contact, $paragraph, $image_title, $staffID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM staff_members WHERE staffName = ? AND staffSurname = ? AND staffPosition = ? AND staffImageTitle = ? AND staffEmail = ? AND staffContact = ? AND staffDescription = ? AND staffID = ?", array($name, $surname, $position, $image_title, $email, $contact, $paragraph, $staffID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD STAFF
    //#################################################################
	function addStaff($name, $surname, $position, $email, $contact, $paragraph, $image_title, $imageFile){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //SET VALUES
        if($email == '' || $email == ' '){
            $email = '';
        }

        if($contact == '' || $contact == ' '){
            $contact = '';
        }

        if($paragraph == '' || $paragraph == ' '){
            $paragraph = '';
        }

        if($image_title == '' || $image_title == ' '){
            $image_title = '';
        }

        if($imageFile == '' || $imageFile == ' '){
            $imageFile = '';
        }

		//STRIP INFO
		$name		    = strip_tags($name);
        $surname	    = strip_tags($surname);
        $position	    = strip_tags($position);
        $email  	    = strip_tags($email);
        $contact  	    = strip_tags($contact);
        $image_title    = strip_tags($image_title);

        //GET SEQUENCE
		$result	= $connector->query("SELECT * FROM staff_members WHERE deletedBy = ? ORDER BY sequence DESC", array(0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD STAFF MEMEBR
		$insert = $connector->query("INSERT INTO staff_members(staffName, staffSurname, staffPosition, staffImageTitle, staffImage, staffEmail, staffContact, staffDescription, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($name, $surname, $position, $image_title, $imageFile, $email, $contact, $paragraph, $currentUser, $currentDate, $sequence));

	}

	//#################################################################
    // UPDATE STAFF MEMBER
    //#################################################################
	function updateStaff($name, $surname, $position, $email, $contact, $paragraph, $image_title, $imageFile, $modifiedBy, $modifiedDate, $modifiedNumber, $staffID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VALUES
        if($email == '' || $email == ' '){
            $email = '';
        }

        if($contact == '' || $contact == ' '){
            $contact = '';
        }

        if($paragraph == '' || $paragraph == ' '){
            $paragraph = '';
        }

        if($image_title == '' || $image_title == ' '){
            $image_title = '';
        }

        if($imageFile == '' || $imageFile == ' '){
            $imageFile = '';
        }

		//STRIP TAGS
		$name         	= strip_tags($name);
        $surname        = strip_tags($surname);
        $position       = strip_tags($position);
        $email          = strip_tags($email);
        $contact        = strip_tags($contact);
        $image_title    = strip_tags($image_title);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM staff_members WHERE staffID = ?", array($staffID));
        $row    = $connector->fetchArray($result);
        $image  = $row['staffImage'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE STAFF MEMBER
		$update = $connector->query("UPDATE staff_members SET
									staffName = ?,
                                    staffSurname = ?,
                                    staffPosition = ?,
                                    staffImageTitle = ?,
                                    staffImage = ?,
                                    staffEmail = ?,
                                    staffContact = ?,
                                    staffDescription = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE staffID = ?",
									array($name, $surname, $position, $image_title, $imageFile, $email, $contact, $paragraph, $modifiedBy, $modifiedDate, $modifiedNumber, $staffID));

	}

	//#################################################################
    // DELETE STAFF MEMBER
    //#################################################################
	function deleteStaff($staffID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE STAFF MEMBER
		$remove = $connector->query("DELETE FROM staff_members WHERE staffID = ?", array($staffID));

	}

    //#################################################################
    // GET STAFF MEMBERS FOR SEQUENCING
    //#################################################################
	function getStaffMembersSequencing($web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET STAFF MEMBER INFO
		$result = $connector->query("SELECT * FROM staff_members WHERE deletedBy = ? ORDER BY sequence ASC", array(0));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $staffID        = $row['staffID'];
            $staffImage     = $row['staffImage'];
            $staffName      = $row['staffName'];
            $staffSurname   = $row['staffSurname'];
            $staffPosition  = $row['staffPosition'];

            //CHECK IF A STAFF IMAGE IS AVAILABLE
            if($staffImage == '' || $staffImage == ' '){
                $staffImage = 'default-staff.jpg';
            }

            $txt.= '<div class="uploader_image_shade sortable-content" id="'.$staffID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$staffImage.');"></div>
                <div class="uploader_image_properties"><div class="module-form-titles">Staff Name: <span class="normal-text">'.$staffName.'</span></div><div class="module-form-titles">Staff Name: <span class="normal-text">'.$staffSurname.'</span></div><div class="module-form-titles">Position: <span class="normal-text">'.$staffPosition.'</span></div></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

}

//DEFINE CLASS
$staffManager = new staffManager();


//#################################################################
//DELETE STAFF MEMBER
//#################################################################
if(isset($_POST['delete_staff'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $staffID	= $_POST['staffID'];

    //SET STAFF MEMBER AS REMOVED IN DATABASE
    $staffManager->deleteStaff($staffID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."staff-manager/index.php?message=3");
    exit;
}

//#################################################################
// ADD STAFF MEMBER
//#################################################################
if(isset($_POST['add_staff'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name		    = $_POST['staff-name'];
    $surname        = $_POST['staff-surname'];
    $position       = $_POST['staff-position'];
    $email          = $_POST['staff-email'];
    $contact        = $_POST['staff-contact'];
    $paragraph      = $_POST['paragraph'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$staff_email_2	= $_POST['staff-email-2'];
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
    $surname        = $userLogin->specialCharactersToHTMLEntity($surname);
    $position       = $userLogin->specialCharactersToHTMLEntity($position);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Staff  Name', 1, 200);
    $v->validateString($surname, 'Staff  Surame', 1, 200);
    $v->validateString($position, 'Position', 1, 200);

    //IF EMAIL IS SUPPLIED
    if($email != '' && $email != ' '){
        $v->validateEmailAddress($email, 'Staff Email');
    }

    //IF CONTACT NUMBER IS SUPPLIED
    if($contact != '' && $contact != ' ' && $contact != 0){
        $v->validateContactNumbers($contact, 'Staff Contact');
    }

    //IF A DESCRIPTION IS SUPPLIED
    if($paragraph != '' && $paragraph != ' '){
        $v->validateText($paragraph, 'Description', 10);
    }

    //IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateString($image_title, 'Image Title',3, 150);
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($staff_email_2 == '' && $image_type == ''){

            //IF AN IMAGE HAS BEEN ADDED
			if($_FILES[$inputField]["tmp_name"] != ""){
				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

				//GET THE IMAGE SIZE
				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
			}

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT STAFF MEMBER INTO DATABASE
			$staffManager->addStaff($name, $surname, $position, $email, $contact, $paragraph, $image_title, $imageFile);

            //IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
                //REDIRECT USER
    			header("Location: ".$cms_root."staff-manager/crop-image.php?imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;
			}
			//REDIRECT TO STAFF MANAGER
			else{
				header("Location: ".$cms_root."staff-manager/index.php?message=1");
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
// EDIT STAFF MEMBER
//#################################################################
if(isset($_POST['edit_staff'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name           = $_POST['staff-name'];
    $surname        = $_POST['staff-surname'];
    $position       = $_POST['staff-position'];
    $email          = $_POST['staff-email'];
    $contact        = $_POST['staff-contact'];
    $paragraph      = $_POST['paragraph'];
	$staffID    	= $_POST['staffID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$staff_email_2	= $_POST['staff-email-2'];
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
    $surname        = $userLogin->specialCharactersToHTMLEntity($surname);
    $position       = $userLogin->specialCharactersToHTMLEntity($position);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
    $v = new formValidation();
    $v = new formValidation();
	$v->validateString($name, 'Staff  Name', 1, 200);
    $v->validateString($surname, 'Staff  Surame', 1, 200);
    $v->validateString($position, 'Position', 1, 200);

    //IF EMAIL IS SUPPLIED
    if($email != '' && $email != ' '){
        $v->validateEmailAddress($email, 'Staff Email');
    }

    //IF CONTACT NUMBER IS SUPPLIED
    if($contact != '' && $contact != ' ' && $contact != 0){
        $v->validateContactNumbers($contact, 'Staff Contact');
    }

    //IF A DESCRIPTION IS SUPPLIED
    if($paragraph != '' && $paragraph != ' '){
        $v->validateText($paragraph, 'Description', 10);
    }

    //IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateString($image_title, 'Image Title',3, 150);
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($staff_email_2 == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($staffManager->checkStaffChanges($name, $surname, $position, $email, $contact, $paragraph, $image_title, $staffID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

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

                    //CHECK IF IMAGE TITLE IS NOT SET
                    if($imageTitle == ''){
                        $image_title    = $staffManager->getStaffInfo($staffID, 'staffImageTitle');
                    }
                }

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

				//UPDATE USER IN DATABASE
				$staffManager->updateStaff($name, $surname, $position, $email, $contact, $paragraph, $image_title, $imageFile, $modifiedBy, $modifiedDate, $modifiedNumber, $staffID);

                //IF A NEW IMAGE HAS BEEN UPLOADED
                if($_FILES[$inputField]["tmp_name"] != ""){
                //REDIRECT USER
    			    header("Location: ".$cms_root."staff-manager/crop-image.php?imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
            		exit;
                }else{
                    header("Location: ".$cms_root."staff-manager/index.php?message=2");
            		exit;
                }

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."staff-manager/");
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
$newWidth		= 300;
$newHeight		= 300;

//CALCULATE NEW RATIO
$ratio			= $newWidth / $newHeight;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
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
	header("Location: ".$cms_root."staff-manager/index.php?message=".$message);
    exit;
}
###################################################################
?>
