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

class bannerManager extends systemConfig{
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
			case 1: $displayMessage = 'A new Banner has successfully been added.'; break;
			case 2: $displayMessage = 'The selected Banner has successfully been updated.'; break;
			case 3: $displayMessage = 'The selected Banner has successfully been removed.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

    //#################################################################
    // GET BANNER INFORMATION
    //#################################################################
	function getBannerInfo($bannerID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM banner_images WHERE bannerID = ?", array($bannerID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // CHECK IF DEFAULT BANNER HAS BEEN ADDED
    //#################################################################
	function checkDefaultBanner($bannerAreaID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET DEFAULT BANNER
		$result = $connector->query("SELECT defaultBanner FROM banner_images WHERE deletedBy = ? AND defaultBanner = ? AND bannerAreaID = ?", array(0, 1, $bannerAreaID));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL BANNER AREAS
    //#################################################################
	function getTotalBannerArea(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM banner_area WHERE deletedBy = ?", array(0));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET EMPTY BANNER AREA
    //#################################################################
	function getEmptyBannerAreas(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT bannerAreaID FROM banner_area WHERE deletedBy = ?", array(0));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$bannerAreaID	= $row['bannerAreaID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM banner_images WHERE bannerAreaID = ? AND deletedBy = ?", array($bannerAreaID, '0'));
			$total		= $connector->numResults($result2);

			//CHECK IF CONTENT HAS BEEN FOUND
			if($total == 0){
				$count++;
			}

		}

		//RETURN VAlUE
		return $count;

	}

	//#################################################################
    // GET BANNER AREA INFORMATION
    //#################################################################
	function getBannerAreaInfo($bannerAreaID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET BANNER AREA INFO
		$result = $connector->query("SELECT * FROM banner_area WHERE bannerAreaID = ?", array($bannerAreaID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET BANNER IMAGE
    //#################################################################
	function getBannerImage($bannerID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM banner_images WHERE bannerID = ?", array($bannerID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['imageFile'];
		$imageTitle	= $row['imageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing edit-banner-image-size" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

	//#################################################################
    // CHECK IF BANNERAREAIS IS IN DATABASE
    //#################################################################
	function checkBannerAreaIDDatabase($bannerAreaID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM banner_area WHERE bannerAreaID = ? AND deletedBy = ?", array($bannerAreaID, '0'));
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
    // CHECK IF BANNER IS IN DATABASE
    //#################################################################
	function checkBannerDatabase($bannerAreaID, $bannerID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM banner_images WHERE bannerAreaID = ? AND bannerID = ?", array($bannerAreaID, $bannerID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // BANNER AREA ARCHITECTURE
    //#################################################################
	function bannerAreaArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL BANNER AREAS
		$result = $connector->query("SELECT bannerAreaID, bannerAreaName FROM banner_area WHERE deletedBy = ? ORDER BY bannerAreaID ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$empty			= '';
			$empty_bg		= '';
            $bannerAreaID   = $row['bannerAreaID'];
			$bannerAreaName	= $row['bannerAreaName'];

			//GET IMAGES FOR BANNERS
			$result2	= $connector->query("SELECT * FROM banner_images WHERE bannerAreaID = ? AND deletedBy = ?", array($bannerAreaID, '0'));
			$bannerImagesTotal	= $connector->numResults($result2);

			//IF BANNER ARE AVAILABLE
			if($bannerImagesTotal == 0){
				$empty		= '<span class="empty-category-text">(Empty)</span>';
				$empty_bg	='class="empty-category"';
			}

			//GENERATE OUPUT
			$txt.= '<tr>
				<td colspan="2" '.$empty_bg.'>'.$bannerAreaName.' '.$empty.'</td>
				<td '.$empty_bg.' align="center">
					<a href="'.$cms_root.'banner-manager/manage-banner.php?bannerAreaID='.$bannerAreaID.'" title="Manage">Manage</a>
				</td>
			  </tr>';

		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // DEFAULT BANNER CONTENT ARCHITECTURE
    //#################################################################
	function defaultBannerContentArchitecture($cms_root, $web_root, $bannerAreaID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

        //ADD START CONTENT
        $txt.= '<h2>Default Banner</h2>';

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM banner_images WHERE deletedBy = ? AND bannerAreaID = ? AND defaultBanner = ?", array('0', $bannerAreaID, '1'));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$bannerID               = $row['bannerID'];
				$imageTitle             = $row['imageTitle'];
				$imageFile			    = $row['imageFile'];
				$bannerLink			    = $row['bannerLink'];
                $bannerDescription      = strip_tags($row['bannerDescription']);

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder">
                    <div class="banner-image">
                        <img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0">
                        <div class="module-manage-content-links">
                            <a href="'.$cms_root.'banner-manager/edit-banner.php?bannerID='.$bannerID.'&bannerAreaID='.$bannerAreaID.'" title="Edit Default Banner">Edit Default Banner</a>
                        </div>
                    </div>
                    <div class="banner-image-info">';
				
				//CHECK IF A BANNER IMAGE TITLE IS AVAILABLE
                if($imageTitle != ''){
                    $txt.= '<b>Image Title:</b> '.$imageTitle.'<br /><br />';
                }

                //CHECK IF A BANNER LINK IS AVAILABLE
                if($bannerLink != ''){
                    $txt.= '<b>Banner Link:</b> <a href="'.$bannerLink.'" target="_blank">'.$bannerLink.'</a><br /><br />';
                }

                //CHECK IF BANNER DESCRIPTION IS AVAILABLE
                if($bannerDescription != ''){
                    $txt.= '<b>Banner Description:</b> '.$bannerDescription;
                }

                    $txt.= '</div>
                </div>';
			}
		}
		//IF NO DEFAULT BANNER IS AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There is currently no Default Banners available. <a href="'.$cms_root.'banner-manager/add-banner.php?bannerAreaID='.$bannerAreaID.'" title="Add Default Banner">Please add a Default Banner here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // BANNER CONTENT ARCHITECTURE
    //#################################################################
	function bannerContentArchitecture($cms_root, $web_root, $bannerAreaID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
        $currentDate = date('Y-m-d');
		$currentUser = $_SESSION['cmsUser'];

        //ADD START CONTENT
        $txt.= '<h2>Other Banners</h2>';

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM banner_images WHERE deletedBy = ? AND bannerAreaID = ? AND defaultBanner = ? ORDER BY sequence ASC", array('0', $bannerAreaID, '0'));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$bannerID               = $row['bannerID'];
				$imageTitle             = $row['imageTitle'];
				$imageFile			    = $row['imageFile'];
				$bannerLink			    = $row['bannerLink'];
                $bannerDescription      = strip_tags($row['bannerDescription']);
                $startDate              = $row['startDate'];
                $endDate                = $row['endDate'];

                //FORMAT DATES
                $startDate = date('j F Y', strtotime($startDate));
                if($endDate == '' || $endDate == ' ' || $endDate == '0000-00-00'){
                    $endDate = 'Won\'t expire!';
                }else{
                    $endDate = date('j F Y', strtotime($endDate));
					$endDateFormatted = date('Y-m-d', strtotime($endDate));
                }

                //SET EXPIRED OVERLAY
                if($currentDate > $endDateFormatted && $endDate != 'Won\'t expire!'){
                    $overlay    = '<div class="banner-expired-holder">
                        <div class="banner-expired-parent">
                            <div class="banner-expired-child"><strong>EXPIRED</strong></div>
                        </div>
                    </div>';
                }

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$bannerID.'">
                    <div class="banner-image">
                        '.$overlay.'
                        <img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0">
                        <div class="module-manage-content-links">
                            <form name="delete_banner'.$bannerID.'">
								<input type="hidden" name="delete_banner" value="1">
								<input type="hidden" name="bannerID" value="'.$bannerID.'">
                                <input type="hidden" name="bannerAreaID" value="'.$bannerAreaID.'">
								<a href="javascript:deleteBanner('.$bannerID.')" title="Remove Banner">Remove Banner</a>
							</form>
                            <a href="'.$cms_root.'banner-manager/edit-banner.php?bannerID='.$bannerID.'&bannerAreaID='.$bannerAreaID.'" title="Edit Banner">Edit Banner</a>
                        </div>
                    </div>
                    <div class="banner-image-info">';
					
				//CHECK IF A BANNER IMAGE TITLE IS AVAILABLE
                if($imageTitle != ''){
                    $txt.= '<b>Image Title:</b> '.$imageTitle.'<br /><br />';
                }

                //CHECK IF A BANNER LINK IS AVAILABLE
                if($bannerLink != ''){
                    $txt.= '<b>Banner Link:</b> <a href="'.$bannerLink.'" target="_blank">'.$bannerLink.'</a><br /><br />';
                }

                //CHECK IF BANNER DESCRIPTION IS AVAILABLE
                if($bannerDescription != ''){
                    $txt.= '<b>Banner Description:</b> '.$bannerDescription.'<br /><br />';
                }

                $txt.= '<b>Start Date:</b> '.$startDate.'<br /><br />
                        <b>End Date:</b> '.$endDate.'';

                    $txt.= '</div>
                </div>';
			}
		}
		//IF NO BANNERS ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Banners available. <a href="'.$cms_root.'banner-manager/add-banner.php?bannerAreaID='.$bannerAreaID.'" title="Add Banner">Please add a Banner here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // DELETE BANNER
    //#################################################################
	function deleteBanner($bannerID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//GET BANNER IMAGE INFO
		$result	= $connector->query("SELECT * FROM banner_images WHERE bannerID = ?", array($bannerID));
		$row	= $connector->fetchArray($result);

        //SET VARIABLES
        $imageFile           = $row['imageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$imageFile);
		unlink($mediumDirectory.$imageFile);
		unlink($smallDirectory.$imageFile);

		//REMOVE BANNER ENTRY
		$remove = $connector->query("DELETE FROM banner_images WHERE bannerID = ?",array($bannerID));

	}

	//#################################################################
    // ADD BANNER
    //#################################################################
	function addBanner($title, $link, $imageFile, $paragraph, $start, $end, $defaultBanner, $bannerAreaID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$link	        = strip_tags($link);

        //SET START & END DATE
        if($start == '' || $start == ' '){
            $start = '0000-00-00';
        }

        if($end == '' || $end == ' '){
            $end = '0000-00-00';
        }

		//GET SEQUENCE
        if($defaultBanner == 1){
            $sequence = 0;
        }else{
            $result	= $connector->query("SELECT * FROM banner_images WHERE bannerAreaID = ? AND deletedBy = ? ORDER BY sequence DESC", array($bannerAreaID, 0));
            $row	= $connector->fetchArray($result);
            $sequence = $row['sequence']+1;
        }

		//ADD USER
		$insert = $connector->query("INSERT INTO banner_images (bannerAreaID, imageTitle, bannerDescription, imageFile, bannerLink, startDate, endDate, defaultBanner, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($bannerAreaID, $title, $paragraph, $imageFile, $link, $start, $end, $defaultBanner, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE BANNER
    //#################################################################
	function updateBanner($title, $imageFile, $link, $paragraph, $start, $end, $defaultBanner, $bannerID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$link           = strip_tags($link);

        //SET START AND END DATE
        if($start == '' || $start == ' '){
            $start = '0000-00-00';
        }

        if($end == '' || $end == ' '){
            $end = '0000-00-00';
        }

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM banner_images WHERE bannerID = ?", array($bannerID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD PAGES CONTENT
		$update			= $connector->query("UPDATE banner_images SET
                                            imageTitle          = ?,
                                            bannerDescription   = ?,
                                            imageFile           = ?,
                                            bannerLink          = ?,
                                            startDate           = ?,
                                            endDate             = ?,
                                            modifiedBy          = ?,
                                            modifiedNumber      = ?,
                                            modifiedDate        = ?
                                            WHERE bannerID = ?",
                                            array($title, $paragraph, $imageFile, $link, $start, $end, $currentUser, $modifiedNumber, $currentDate, $bannerID));

	}
}

//DEFINE CLASS
$bannerManager = new bannerManager();

//#################################################################
// ADD BANNER
//#################################################################
if(isset($_POST['add_banner'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $defaultBanner  = $_POST['defaultBanner'];
	$bannerAreaID	= $_POST['bannerAreaID'];
	$title			= $_POST['banner-title'];
    $paragraph      = $_POST['paragraph'];
	$link           = $_POST['banner-link'];
    $start          = $_POST['banner-start-date'];
    $end            = $_POST['banner-end-date'];

	//HONEY POTS
	$image_type		= $_POST['banner-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
	$v = new formValidation();
    //IF A TITLE IS SUPPLIED
    if($title != '' && $title != ' '){
        $v->validateString($title, 'Banner Title', 1, 200);
    }

    //IF A DESCRIPTION IS SUPPLIED
    if($paragraph != '' && $paragraph != ' '){
        $v->validateText($paragraph, 'Banner Description', 10);
    }

	$v->validateImage($inputField, 'Image File');

	//IF A LINK HAS BEEN SUPPLIED
	if($link != '' && $link != ' '){
		$v->validateLink($link, 'Banner Link');
	}

    //IF IT IS NOT A DEFAULT BANNER
    if($defaultBanner == 0){
        $v->validateStartEndDates($start, $end, 'Banner Start & End Date');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($image_type == ''){

			//IF AN IMAGE HAS BEEN ADDED
			if($_FILES[$inputField]["tmp_name"] != ""){
				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $title);

				//GET THE IMAGE SIZE
				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
			}

			//INSERT BANNER INTO DATABASE
			$bannerManager->addBanner($title, $link, $imageFile, $paragraph, $start, $end, $defaultBanner, $bannerAreaID);

            //CROP BANNER IMAGES
			header("Location: ".$cms_root."banner-manager/crop-image.php?bannerAreaID=".$bannerAreaID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
    		exit;
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
// EDIT BANNER
//#################################################################
if(isset($_POST['edit_banner'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $defaultBanner  = $_POST['defaultBanner'];
	$bannerAreaID   = $_POST['bannerAreaID'];
    $bannerID       = $_POST['bannerID'];
	$title          = $_POST['image-title'];
    $paragraph      = $_POST['paragraph'];
	$link           = $_POST['banner-link'];
    $start          = $_POST['banner-start-date'];
    $end            = $_POST['banner-end-date'];
    $oldImage       = $_POST['oldImage'];

	//HONEY POTS
	$image_type        = $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
	$v = new formValidation();
    //IF A TITLE IS SUPPLIED
    if($title != '' && $title != ' '){
        $v->validateString($title, 'Image Title',3, 150);
    }

    //IF A DESCRIPTION IS SUPPLIED
    if($paragraph != '' && $paragraph != ' '){
        $v->validateText($paragraph, 'Banner Description', 10);
    }

    //IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateImage($inputField, 'Image File');
	}

	//IF BANNER LINK HAS BEEN ADDED
	if($link != '' && $link != ' '){
		$v->validateLink($link, 'Banner Link');
	}

    //IF IT IS NOT A DEFAULT BANNER
    if($defaultBanner == 0){
        $v->validateStartEndDates($start, $end, 'Banner Start & End Date');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($image_type == ''){

			//IF AN IMAGE HAS BEEN ADDED
			if($_FILES[$inputField]["tmp_name"] != ""){
				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $title);

				//GET THE IMAGE SIZE
				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);

                //DELETE OLD IMAGES
        		unlink($largeDirectory.$oldImage);
        		unlink($mediumDirectory.$oldImage);
        		unlink($smallDirectory.$oldImage);

			}
            //IF NO NEW IMAGE HAS BEEN UPLOADED
            else{
                $imageFile      = $oldImage;

                //CHECK IF AN IMAGE TITLE IS NOT SET
                if($title == ''){
                    $title    = $bannerManager->getBannerInfo($pageContentID, 'imageTitle');
                }
            }

			//INSERT PAGE CONTENT INTO DATABASE
			$bannerManager->updateBanner($title, $imageFile, $link, $paragraph, $start, $end, $defaultBanner, $bannerID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."banner-manager/crop-image.php?bannerAreaID=".$bannerAreaID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;
			}
			//REDIRECT TO BANNER MANAGER
			else{
				header("Location: ".$cms_root."banner-manager/manage-banner.php?bannerAreaID=".$bannerAreaID."&message=2");
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
//DELETE BANNER
//#################################################################
if(isset($_POST['delete_banner'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$bannerID       = $_POST['bannerID'];
    $bannerAreaID   = $_POST['bannerAreaID'];

    //REMOVE GALLERY FROM DATABASE
    $bannerManager->deleteBanner($bannerID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."banner-manager/manage-banner.php?bannerAreaID=".$bannerAreaID."&message=3");
    exit;
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
$newWidth		= 1920;
$newHeight		= 1080;

//CALCULATE NEW RATIO
$ratio			= $newWidth / $newHeight;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$bannerAreaID		= $_POST['bannerAreaID'];
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
	header("Location: ".$cms_root."banner-manager/manage-banner.php?bannerAreaID=".$bannerAreaID."&message=".$message);
    exit;
}
###################################################################
?>
