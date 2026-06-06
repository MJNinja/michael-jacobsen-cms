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

class galleryManager extends systemConfig{
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
            case 1: $displayMessage = 'A new Gallery has successfully been added.'; break;
            case 2: $displayMessage = 'The selected Gallery has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected Gallery has successfully been removed.'; break;
			case 4: $displayMessage = 'The selected Gallery has successfully been recovered.'; break;
            case 5: $displayMessage = 'The selected Gallery has successfully been re-activated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

    //#################################################################
	// SPECIAL CHARACTERS TO HTML ENTITY
	//#################################################################
	function specialCharactersToHTMLEntity($str){

		$search = array('&', '<', '>', '€', '‘', '’', '“', '”', '–', '—', '¡', '¢','£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '«', '¬', '®', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã','ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ','Œ', 'œ', '‚', '„', '…', '™', '•', '˜');

		$replace  = array('&amp;', '&lt;', '&gt;', '&euro;', '&lsquo;', '&rsquo;', '&ldquo;','&rdquo;', '&ndash;', '&mdash;', '&iexcl;','&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;','&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;','&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&OElig;', '&oelig;', '&sbquo;', '&bdquo;', '&hellip;', '&trade;', '&bull;', '&asymp;');

		//REPLACE VALUES
		$str = str_replace($search, $replace, $str);

		//RETURN FORMATED STRING
		return $str;
	}

    //#################################################################
	//CHECK GALLERY URL EXISTS
	//#################################################################
	function checkGalleryURLExists($url, $galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED
        $currentURL = $this->getGalleryInfo($galleryID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM galleries WHERE url = ? LIMIT 0,1", array($url));
        $total  = $connector->numResults($result);

        //IF RESULT FOUND
        if($total != 0){
            //CHECK IF URL IS THE SAME
            if($currentURL == $url){
                //RETURN URL
                return $url;
            }
            //URL NO THE SAME
            else{
                //CREATE URL
                while($proceed == 1){
                    //CREATE NEW URL
                    $newURL = str_replace('/', '', $url).'-'.$count.'/';

                    //CHECK IF NEW URL IS FINE
                    $result2    = $connector->query("SELECT url FROM galleries WHERE url = ? LIMIT 0,1", array($newURL));
                    $total2     = $connector->numResults($result2);

                    //NO RESULT FOUND
                    if($total2 == 0){
                        //SET PROCEED TO 0
                        $proceed = 0;
                    }
                    //RESULT FOUND
                    else{
                        //INCREMENT COUNT
                        $count++;
                    }

                }

                //RETURN URL
                return $newURL;

            }
        }
        //NO RESULT FOUND
        else{
            return $url;
        }
	}

	//#################################################################
    // GET GALLERY INFORMATION
    //#################################################################
	function getGalleryInfo($galleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM galleries WHERE galleryID = ?", array($galleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // CHECK IF GALLERY IS IN DATABASE
    //#################################################################
	function checkGalleryDatabase($galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM galleries WHERE galleryID = ?", array($galleryID));
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
    // GET TOTAL GALLERIES
    //#################################################################
	function getTotalGalleries(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM galleries WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED GALLERIES
    //#################################################################
	function getTotalRemovedGalleries(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM galleries WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL GALLERY IMAGES
    //#################################################################
	function getTotalGalleriesImages(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM galleries_images WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL REMOVED GALLERY IMAGES
    //#################################################################
	function getTotalRemovedGalleriesImages(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM galleries_images WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET GALLERY IMAGES
    //#################################################################
	function getGalleryImages($galleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET BLOG GALLERY INFO
		$result = $connector->query("SELECT * FROM galleries_images WHERE galleryID = ? ORDER BY galleryImageID ASC", array($galleryID));
        $total  = $connector->numResults($result);

        //CHECK IF IMAGES ARE AVAILABLE
        if($total != 0){
    		while($row	= $connector->fetchArray($result)){
                $galleryImageID     = $row['galleryImageID'];
                $galleryImageFile   = $row['galleryImageFile'];
                $galleryImageTitle  = $row['galleryImageTitle'];

                $txt.= '<div class="uploader_image_shade" id="img'.$galleryImageID.'">
                    <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                    <div class="remove_gallery_image">
                        <input type="checkbox" name="remove_gallery_image_'.$galleryImageID.'" value="1" />
                        <div class="remove_gallery_image_text">Remove Image</div>
                    </div>
                    <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$galleryImageID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
                </div>';

                $count++;
            }
        }else{
            $txt.= '<div class="required">There are currently no Gallery Images available. Please add image(s) with the <strong>Choose Images</strong> button above.</div>';
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET GALLERY IMAGES FOR SEQUENCING
    //#################################################################
	function getGalleryImagesSequencing($galleryID, $web_root, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM galleries_images WHERE galleryID = ? ORDER BY sequence ASC", array($galleryID));
        $total  = $connector->numResults($result);

        //CHECK IF IMAGES ARE AVAILABLE
        if($total != 0){
    		while($row	= $connector->fetchArray($result)){
                $galleryImageID             = $row['galleryImageID'];
                $galleryImageFile           = $row['galleryImageFile'];
                $galleryImageTitle          = $row['galleryImageTitle'];

                $txt.= '<div class="uploader_image_shade sortable-content" id="'.$galleryImageID.'">
                    <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                    <div class="uploader_image_properties"><div class="module-form-titles">Image Title: <span class="normal-text">'.$galleryImageTitle.'</span></div></div><div class="clear"></div>
                </div>';

                $count++;
            }
        }else{
            $txt.= '<div class="required">There are currently no Gallery Images available. Please add image(s) by <strong><a href="'.$cms_root.'gallery-manager/manage-gallery.php?galleryID='.$galleryID.'" title="Add Gallery Images">clicking here</a></strong>.</div>';
        }

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // GALLERY ARCHITECTURE
    //#################################################################
	function galleryArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL GALLERIES
		$result = $connector->query("SELECT * FROM galleries WHERE deletedBy = ? ORDER BY galleryName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
                $status         = '';
                $status_bg      = '';
				$galleryID		= $row['galleryID'];
				$galleryName	= $row['galleryName'];

                //CHECK IF GALLERY IS EMPTY
                $result1    = $connector->query("SELECT * FROM galleries_images WHERE galleryID = ? AND deletedBy = ?", array($galleryID, 0));
                $total      = $connector->numResults($result1);
                if($total == 0){
                    $status		= '<span class="empty-category-text">(Empty)</span>';
                    $status_bg	='class="empty-category"';
                }

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$status_bg.'>'.$galleryName.' '.$status.'</td>
                    <td '.$status_bg.' align="center">
                    <a href="'.$cms_root.'gallery-manager/edit-gallery.php?galleryID='.$galleryID.'" title="Modify">Modify</a>
                    </td>
                    <td '.$status_bg.' align="center">
						<a href="'.$cms_root.'gallery-manager/manage-gallery.php?galleryID='.$galleryID.'" title="Manage">Manage</a>
					</td>
                    <td '.$status_bg.' align="center">
						<a href="'.$cms_root.'gallery-manager/sequence-gallery.php?galleryID='.$galleryID.'" title="Sequence">Sequence</a>
					</td>
					<td '.$status_bg.' align="center">
					<form name="delete_gallery'.$galleryID.'">
						<input type="hidden" name="delete_gallery" value="1">
						<input type="hidden" name="galleryID" value="'.$galleryID.'">
						<a href="javascript:deleteGallery('.$galleryID.')" title="Remove">Remove</a>
					</form>
					</td>
				  </tr>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Galleries available. <a href="'.$cms_root.'gallery-manager/add-gallery.php" title="Add Gallery">Please add a gallery here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // GALLERY ARCHITECTURE (REMOVED)
    //#################################################################
	function galleryArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED GALLERIES
		$result = $connector->query("SELECT * FROM galleries WHERE deletedBy != ? ORDER BY galleryName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$galleryID		= $row['galleryID'];
			$galleryName	= $row['galleryName'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$galleryName.'</td>
				<td align="center">
				<form name="recover_gallery'.$galleryID.'">
					<input type="hidden" name="recover_gallery" value="1">
					<input type="hidden" name="galleryID" value="'.$galleryID.'">
					<a href="javascript:recoverGallery('.$galleryID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
	//OVERWRITE GALLERY
	//#################################################################
	function overwriteGallery($name, $paragraph){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$name	    = strip_tags($name);

		//RE-ACTIVATE GALLERY
		$update = $connector->query("UPDATE galleries SET
                                    galleryDescription = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE galleryName = ?",
									array($paragraph, '0', '0000-00-00 00:00:00', $name));

	}

	//#################################################################
    // CHECK IF ANY GALLERIES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedGallery(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM galleries WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

    //#################################################################
    // CHECK IF GALLERY INFO HAS BEEN CHANGED
    //#################################################################
	function checkGalleryChanges($name, $paragraph, $galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM galleries WHERE galleryName = ? AND galleryDescription = ? AND galleryID = ?", array($name, $paragraph, $galleryID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD GALLERY
    //#################################################################
	function addGallery($name, $paragraph, $url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name		    = strip_tags($name);

		//ADD GALLERY
		$insert = $connector->query("INSERT INTO galleries(galleryName, galleryDescription, url, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?)",
									array($name, $paragraph, $url, $currentUser, $currentDate));

        //GET GALLERY ID
        $result = $connector->query("SELECT galleryID FROM galleries ORDER BY galleryID DESC LIMIT 0,1", array());
        $row    = $connector->fetchArray($result);

        //RETURN GALLERY ID
        return $row['galleryID'];

	}

	//#################################################################
    // UPDATE GALLERY
    //#################################################################
	function updateGallery($name, $paragraph, $modifiedBy, $modifiedDate, $modifiedNumber, $galleryID, $url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$name         	= strip_tags($name);

		//UPDATE USER
		$update = $connector->query("UPDATE galleries SET
									galleryName = ?,
                                    galleryDescription = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE galleryID = ?",
									array($name, $paragraph, $url, $modifiedBy, $modifiedDate, $modifiedNumber, $galleryID));

	}

	//#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE  galleries SET
									deletedBy = ?,
									deletedDate = ?
									WHERE galleryID = ?",
									array($currentUser, $currentDate, $galleryID));

	}

	//#################################################################
    // RECOVER GALLERY
    //#################################################################
	function recoverGallery($galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE galleries SET
									deletedBy = ?,
									deletedDate = ?
									WHERE galleryID = ?",
									array('0', '0000-00-00 00:00:00', $galleryID));

	}

	//#################################################################
    // CHECK IF GALLERY NAME IS ALREADY IN USE
    //#################################################################
	function addGalleryCheck($galleryName){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM galleries WHERE galleryName = ?", array($galleryName));
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
				return 'removed_gallery';
			}
		}

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM galleries_images WHERE galleryID = ? ORDER BY galleryImageID ASC", array($galleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $galleryImageID             = $row['galleryImageID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$galleryImageID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$galleryImageID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                echo

                $this->deleteGalleryImage($galleryImageID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM galleries_images WHERE galleryImageID = ? AND galleryImageTitle = ?", array($galleryImageID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE galleries_images SET
                                                galleryImageTitle = ?
                                                WHERE galleryImageID = ?",
                                                array($updateImageTitle, $galleryImageID));

                    //SET THAT CONTENT HAS BEEN UPDATED
                    $updatedGalleryImages = 1;
                }

            }

        }

        //RETURN RESULT
        return $updatedGalleryImages;
    }

    //#################################################################
    // DELETE GALLERY IMAGE
    //#################################################################
	function deleteGalleryImage($galleryImageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM galleries_images WHERE galleryImageID = ?", array($galleryImageID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM galleries_images WHERE galleryImageID = ?",array($galleryImageID));

	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($galleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM galleries_images WHERE galleryID = ? ORDER BY sequence DESC", array($galleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD galleryID INTO galleries_images
		$insert = $connector->query("INSERT INTO galleries_images (galleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($galleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // UPDATE GALLERY INFO
    //#################################################################
	function updateGalleryInfo($galleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM galleries WHERE galleryID = ?", array($galleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//UPDATE GALLERY INFO
		$update			= $connector->query("UPDATE galleries SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE galleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $galleryID));

	}

}

//DEFINE CLASS
$galleryManager = new galleryManager();


//#################################################################
//DELETE GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $galleryID	= $_POST['galleryID'];

    //SET GALLERY AS REMOVED IN DATABASE
    $galleryManager->deleteGallery($galleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."gallery-manager/index.php?message=3");
    exit;
}

//#################################################################
//RECOVER GALLERY
//#################################################################
if(isset($_POST['recover_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $galleryID	= $_POST['galleryID'];

    //SET GALLERY AS ACTIVE IN DATABASE
    $galleryManager->recoverGallery($galleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."gallery-manager/index.php?message=4");
    exit;
}

//#################################################################
// ADD GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name		    = $_POST['gallery-name'];
    $paragraph      = $_POST['paragraph'];

	//HONEY POTS
	$gallery_type	= $_POST['gallery-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name           = $galleryManager->specialCharactersToHTMLEntity($name);
    $image_title    = $galleryManager->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Gallery Name', 1, 200);

    if($paragraph != '' && $paragraph != ' '){
        $v->validateText($paragraph, 'Description', 10);
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($gallery_type == ''){

            //CHECK IF GALLERY NAME IS ALREADY IN USE
			$gallery_used = $galleryManager->addGalleryCheck($name);
            if($gallery_used == 'unused'){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE GALLERY URL
                $gallery_url = str_replace("'", "", $name);
                $gallery_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($gallery_url));
                $gallery_url = str_replace(' ', '-', $gallery_url).'/';

                //CHECK IF CATEGORY URL EXISTS
                $gallery_url = $galleryManager->checkGalleryURLExists($gallery_url, '');

    			//INSERT GALLERY INTO DATABASE
    			$galleryID = $galleryManager->addGallery($name, $paragraph, $gallery_url);

                //REDIRECT USER
    			header("Location: ".$cms_root."gallery-manager/manage-gallery.php?galleryID=".$galleryID."&message=1");
        		exit;

			}
			//IF GALLERY HAS BEEN REMOVED
			elseif($gallery_used == 'removed_gallery'){
				//SET USER AS REMOVED
				$removed_gallery = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Gallery Name</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT GALLERY
//#################################################################
if(isset($_POST['edit_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name           = $_POST['gallery-name'];
    $paragraph      = $_POST['paragraph'];
	$galleryID    	= $_POST['galleryID'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$gallery_type	= $_POST['gallery-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name           = $galleryManager->specialCharactersToHTMLEntity($name);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($name, 'Gallery Name', 1, 200);

    if($paragraph != '' && $paragraph != ' '){
        $v->validateText($paragraph, 'Description', 10);
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($gallery_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($galleryManager->checkGalleryChanges($name, $paragraph, $galleryID) == 'changed'){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE GALLERY URL
                $gallery_url = str_replace("'", "", $name);
                $gallery_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($gallery_url));
                $gallery_url = str_replace(' ', '-', $gallery_url).'/';

                //CHECK IF CATEGORY URL EXISTS
                $gallery_url = $galleryManager->checkGalleryURLExists($gallery_url, $galleryID);

				//UPDATE GALLERY IN DATABASE
				$galleryManager->updateGallery($name, $paragraph, $modifiedBy, $modifiedDate, $modifiedNumber, $galleryID, $gallery_url);

                //REDIRECT USER
                header("Location: ".$cms_root."gallery-manager/manage-gallery.php?galleryID=".$galleryID."&message=2");
            	exit;

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."gallery-manager/");
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
// REACTIVATE GALLERY
//#################################################################
if(isset($_POST['reactivate-gallery-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name          		= $_POST['gallery-name'];
    $paragraph		    = $_POST['paragraph'];

	//HONEY POTS
	$gallery_type  = $_POST['gallery-type'];

	if($gallery_type == ''){

		//OVERWRITE GALLERY
		$galleryManager->overwriteGallery($name, $paragraph);

		//REDIRECT PAGE
		header("Location: ".$cms_root."gallery-manager/index.php?message=5");
		exit;
	}
}

//#################################################################
//EDIT GALLERY IMAGES
//#################################################################
if(isset($_POST['edit_gallery_images'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $galleryID          = $_POST['galleryID'];
    $value              = json_decode(stripslashes($_POST['value']));

    //HONEY POTS
    $galleryName    = $_POST['galleryName'];

    //IMAGE PROPERTIES
    $inputField				= 'image';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    $count = 0;

    //CHECK IF ALL CONDITONS HAVE BEEN MET
    if($galleryName == ''){

        //CHECK IF GALLERY IMAGE NEEDS TO BE REMOVED OR UPDATED
        $updatedGalleryImages = $galleryManager->updateRemoveGalleryImages($galleryID);

        //CHECK THAT VALUE ISN'T EMPTY
        if(!empty($value)){

            //LOOP THROUGH ALL POSTED IMAGES
            foreach ($value as $file => $key) {

                //CHECK IF KEY IS EMPTY (WHICH MEANS THAT IMAGE SHOULD NOT BE UPLOADED)
                if($key != ''){

                    //CHECK IF KEY AND IMAGE RESOURCE MATCH
                    if($_FILES['image']['name'][$file] == $key){

                        //GET IMAGE TITLE
                        $imageTitle = $_POST['imageTitle_'.$count];

                        //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
                        $imageTitle       = $galleryManager->specialCharactersToHTMLEntity($imageTitle);

                        //UPLOAD IMAGES
                        $file_name  = $fileUploader->uploadGalleryImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $imageTitle, $file);

                        //INSERT INTO DATABASE
                        $galleryManager->addGalleryImages($galleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $galleryManager->updateGalleryInfo($galleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."gallery-manager/manage-gallery.php?galleryID=".$galleryID."&message=2");
    		exit;
        }else{
            header("Location: ".$cms_root."gallery-manager/");
    		exit;
        }
    }
}
?>
