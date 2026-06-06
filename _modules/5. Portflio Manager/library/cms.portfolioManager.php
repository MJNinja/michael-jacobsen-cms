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

class portfolioManager extends systemConfig{
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
            case 1: $displayMessage = 'A new Website has successfully been added.'; break;
            case 2: $displayMessage = 'The selected Website has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected Website has successfully been removed.'; break;
			case 4: $displayMessage = 'The selected Website has successfully been recovered.'; break;
            case 5: $displayMessage = 'The selected Website has successfully been re-activated.'; break;
            case 6: $displayMessage = 'A new Gallery has successfully been added.'; break;
            case 7: $displayMessage = 'The selected Gallery has successfully been updated.'; break;
            case 8: $displayMessage = 'The selected Gallery has successfully been removed.'; break;
            case 9: $displayMessage = 'A new Paragraph has successfully been added.'; break;
            case 10: $displayMessage = 'The selected Paragraph has successfully been updated.'; break;
            case 11: $displayMessage = 'The selected Paragraph has successfully been removed.'; break;
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
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM portfolio_content WHERE deletedBy = ? AND portfolioID = ?", array(0, $portfolioID));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['paragraphTitle']).' '.strip_tags($row['paragraph']).' '.strip_tags($row['imageTitle']);
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
	function getMetaDescription($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM portfolio_content WHERE deletedBy = ? AND portfolioID = ?", array(0, $portfolioID));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['paragraph']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

    //#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE portfolioID = ?", array($portfolioID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (portfolioID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($portfolioID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE portfolioID = ?",
												array($keywords, $description, $portfolioID));
		}
	}

    //#################################################################
    // CHECK IF PORTFOLIOID IS IN DATABASE
    //#################################################################
	function checkPortfolioIDDatabase($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM portfolio WHERE portfolioID = ?", array($portfolioID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF PORTFOLIO GALLERY IS IN DATABASE
    //#################################################################
	function checkPortfolioGalleryDatabase($portfolioGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM portfolio_gallery WHERE portfolioGalleryID = ? ", array($portfolioGalleryID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF PORTFOLIO CONTENT IS IN DATABASE
    //#################################################################
	function checkPortfolioContentDatabase($portfolioContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioContentID = ? ", array($portfolioContentID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // GET PARAGRAPH CONTENT IMAGE
    //#################################################################
	function getParagraphContentImage($portfolioContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioContentID = ?", array($portfolioContentID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['imageFile'];
		$imageTitle	= $row['imageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div><br /><input type="checkbox" value="1" name="removeImage" />Remove Image from paragraph</div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

    //#################################################################
    // GET PORTFOLIO GALLERY IMAGES
    //#################################################################
	function getPortfolioGalleryImages($portfolioGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? ORDER BY portfolioGalleryContentID ASC", array($portfolioGalleryID));
		while($row	= $connector->fetchArray($result)){
            $portfolioGalleryContentID  = $row['portfolioGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade" id="img'.$portfolioGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="remove_gallery_image">
                    <input type="checkbox" name="remove_gallery_image_'.$portfolioGalleryContentID.'" value="1" />
                    <div class="remove_gallery_image_text">Remove Image</div>
                </div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$portfolioGalleryContentID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET PORTFOLIO GALLERY IMAGES FOR SEQUENCING
    //#################################################################
	function getPortfolioGalleryImagesSequencing($portfolioGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? ORDER BY sequence ASC", array($portfolioGalleryID));
		while($row	= $connector->fetchArray($result)){
            $portfolioGalleryContentID = $row['portfolioGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade sortable-content" id="'.$portfolioGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title: <span class="normal-text">'.$galleryImageTitle.'</span></div></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($portfolioGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? ORDER BY portfolioGalleryContentID ASC", array($portfolioGalleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $portfolioGalleryContentID   = $row['portfolioGalleryContentID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$portfolioGalleryContentID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$portfolioGalleryContentID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                $this->deleteGalleryImage($portfolioGalleryContentID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryContentID = ? AND galleryImageTitle = ?", array($portfolioGalleryContentID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE portfolio_gallery_content SET
                                                galleryImageTitle = ?
                                                WHERE portfolioGalleryContentID = ?",
                                                array($updateImageTitle, $portfolioGalleryContentID));

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
	function deleteGalleryImage($portfolioGalleryContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryContentID = ?", array($portfolioGalleryContentID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM portfolio_gallery_content WHERE portfolioGalleryContentID = ?", array($portfolioGalleryContentID));

	}

    //#################################################################
    // UPDATE PORTFOLIO GALLERY INFO
    //#################################################################
	function updatePortfolioGalleryInfo($portfolioGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM portfolio_gallery WHERE portfolioGalleryID = ?", array($portfolioGalleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD PAGES CONTENT
		$update			= $connector->query("UPDATE portfolio_gallery SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE portfolioGalleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $portfolioGalleryID));

	}

    //#################################################################
    // GET GALLERY INFORMATION
    //#################################################################
	function getGalleryInfo($portfolioGalleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio_gallery WHERE portfolioGalleryID = ?", array($portfolioGalleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET WEBSITE INFORMATION
    //#################################################################
	function getWebsiteInfo($portfolioID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio WHERE portfolioID = ?", array($portfolioID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET WEBSITE CONTENT INFORMATION
    //#################################################################
	function getWebsiteContentInfo($portfolioContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioContentID = ?", array($portfolioContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // CHECK IF WEBSITE IS IN DATABASE
    //#################################################################
	function checkWebsiteDatabase($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET WEBSITE TOTAL
		$result = $connector->query("SELECT * FROM portfolio WHERE portfolioID = ?", array($portfolioID));
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
    // GET TOTAL WEBSITES
    //#################################################################
	function getTotalWebsites(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED WEBSITES
    //#################################################################
	function getTotalRemovedWebsites(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // WEBSITE ARCHITECTURE
    //#################################################################
	function websiteArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL PORTFOLIO
		$result = $connector->query("SELECT * FROM portfolio WHERE deletedBy = ? ORDER BY websiteName ASC", array('0'));
		$websiteTotal = $connector->numResults($result);

		//IF WEBSITES ARE AVAILABLE
		if($websiteTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
                $status			= '';
				$status_bg		= '';
				$portfolioID	= $row['portfolioID'];
				$websiteName	= $row['websiteName'];

                //GET ALL PORTFOLIO GALLERY CONTENT FOR A WEBSITE
				$result2	= $connector->query("SELECT * FROM portfolio_content WHERE portfolioID = ? AND deletedBy = ?", array($portfolioID, '0'));
				$portfolioContentTotal	= $connector->numResults($result2);

                //IF WEBSITE IS EMPTY
				if($portfolioContentTotal == 0){
					$status		= '<span class="empty-category-text">(Empty)</span>';
					$status_bg	='class="empty-category"';
				}

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$status_bg.'>'.$websiteName.' '.$status.'</td>
                    <td '.$status_bg.' align="center">
						<a href="'.$cms_root.'portfolio-manager/manage-website-content.php?portfolioID='.$portfolioID.'" title="Manage">Manage</a>
					</td>
                    <td '.$status_bg.' align="center">
						<a href="'.$cms_root.'portfolio-manager/edit-website.php?portfolioID='.$portfolioID.'" title="Modify">Modify</a>
					</td>
					<td '.$status_bg.' align="center">
					<form name="delete_website'.$portfolioID.'">
						<input type="hidden" name="delete_website" value="1">
						<input type="hidden" name="portfolioID" value="'.$portfolioID.'">
						<a href="javascript:deleteWebsite('.$portfolioID.')" title="Remove">Remove</a>
					</form>
					</td>
				  </tr>';

			}
		}
		//IF NO WEBSITES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="5">There are currently no Websites available. <a href="'.$cms_root.'portfolio-manager/add-website.php" title="Add Website">Please add a website here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // WEBSITE ARCHITECTURE (REMOVED)
    //#################################################################
	function websiteArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM portfolio WHERE deletedBy != ? ORDER BY websiteName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$portfolioID	= $row['portfolioID'];
			$websiteName	= $row['websiteName'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$websiteName.'</td>
				<td align="center">
				<form name="recover_website'.$portfolioID.'">
					<input type="hidden" name="recover_website" value="1">
					<input type="hidden" name="portfolioID" value="'.$portfolioID.'">
					<a href="javascript:recoverWebsite('.$portfolioID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // REMOVE EMPTY GALLERY
    //#################################################################
    function removeEmptyGallery($portfolioGalleryID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //DELETE FROM portfolio_gallery
        $deleteGallery = $connector->query("DELETE FROM portfolio_gallery WHERE portfolioGalleryID = ?", array($portfolioGalleryID));

        //DELETE FROM portfolio_content
        $deleteGalleryContent = $connector->query("DELETE FROM portfolio_content WHERE portfolioGalleryID = ?", array($portfolioGalleryID));
    }

    //#################################################################
    // WEBSITE CONTENT ARCHITECTURE
    //#################################################################
	function websiteContentArchitecture($cms_root, $web_root, $portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM portfolio_content WHERE deletedBy = ?  AND portfolioID = ? ORDER BY sequence ASC", array('0', $portfolioID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$portfolioContentID     = $row['portfolioContentID'];
				$paragraphTitle		    = $row['paragraphTitle'];
				$paragraph			    = $row['paragraph'];
				$imageFile			    = $row['imageFile'];
				$imageTitle			    = $row['imageTitle'];
				$documentFile		    = $row['documentFile'];
				$documentTitle		    = $row['documentTitle'];
				$videoUrl			    = $row['videoUrl'];
				$portfolioGalleryID     = $row['portfolioGalleryID'];
                $sequence               = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				if($portfolioGalleryID != 0){

                    //CHECK IF IMAGES IN GALLERY
                    $result4        = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ?", array($portfolioGalleryID));
                    $totalImages    = $connector->numResults($result4);

                    //REMOVE GALLERY
                    if($totalImages == 0){
                        $this->removeEmptyGallery($portfolioGalleryID);
                        $removedGallery = 1;

                    }else{
    					$txt.= '<div class="module-manage-content-holder" id="'.$portfolioContentID.'">';

                            //GET TOTAL GALLERY IMAGES
                            $result2    = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($portfolioGalleryID, 0));
                            $totalGalleryImage  = $connector->numResults($result2);

                            //IF MORE THAN 6 GALLERY IMAGES
                            if($totalGalleryImage > 6){
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC LIMIT 0,5", array($portfolioGalleryID, 0));
                            }else{
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($portfolioGalleryID, 0));
                            }

                            //GENERATE GALLERY IMAGES OUTPUT
                            while($row3 = $connector->fetchArray($result3)){
                                //SET VARIABLES
                                $galleryImageFile   = $row3['galleryImageFile'];
                                $galleryImageTitle  = $row3['galleryImageTitle'];

                            	$txt.= '<div class="paragraph-image" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>';
                            }

                            //SHOW HOW MANY EXTRA IMAGES
                            if($totalGalleryImage > 6){
                                //CALCULATE EXTRA IMAGES
                                $extraImages    = $totalGalleryImage - 5;

                                $txt.= '<a href="'.$cms_root.'portfolio-manager/edit-gallery.php?portfolioID='.$portfolioID.'&portfolioGalleryID='.$portfolioGalleryID.'" title="View all Gallery Images">
                                    <div class="paragraph-image-indicator">
                                        <div class="paragraph-image-more-indicator">+'.$extraImages.'</div>
                                    </div>
                                </a>';
                            }

                            $txt.= '<div class="clear"></div>
                            <div class="module-manage-content-links">
    							<form name="delete_gallery'.$portfolioContentID.'">
    								<input type="hidden" name="delete_gallery" value="1">
    								<input type="hidden" name="portfolioContentID" value="'.$portfolioContentID.'">
    								<input type="hidden" name="portfolioGalleryID" value="'.$portfolioGalleryID.'">
                                    <input type="hidden" name="portfolioID" value="'.$portfolioID.'">
    								<a href="javascript:deleteGallery('.$portfolioContentID.')" title="Remove Gallery">Remove Gallery</a>
    							</form>
    							<a href="'.$cms_root.'portfolio-manager/edit-gallery.php?portfolioID='.$portfolioID.'&portfolioGalleryID='.$portfolioGalleryID.'" title="Edit Gallery">Edit Gallery</a>
                                <a href="'.$cms_root.'portfolio-manager/sequence-gallery.php?portfolioID='.$portfolioID.'&portfolioGalleryID='.$portfolioGalleryID.'" title="Sequence Gallery">Sequence Gallery</a>
    							<div class="clear"></div>
    							</div>
                        </div>';
                    }
				}else{
					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$portfolioContentID.'">';

						//IF AN IMAGE IS AVAILABLE
						if($imageFile != ''){
							$txt.= '<div class="paragraph-image">
								<img src="'.$web_root.'cms-images/medium/'.$imageFile.'" alt="'.$imageTitle.'" title="'.$imageTitle.'" border="0"/>
							</div>';
						}

						//IF A TITLE IS AVAILABLE
						if($paragraphTitle != ''){
                    		$txt.= '<div class="paragraph-title"><b>'.$paragraphTitle.'</b></div>';
						}

						$txt.= '<div class="paragraph-text">'.$paragraph.'</div>
                        		<div class="clear"></div>';

						//IF A VIDEO IS AVAILABLE
						if($videoUrl != ''){
                        	$txt.= '<div class="paragraph-links">Video: <a href="'.$videoUrl.'" target="_blank">'.$videoUrl.'</a></div>';
						}

						//IF A DOCUMENT IS AVAILABLE
						if($documentFile != ''){
							$txt.= '<div class="paragraph-links">Document: <a href="'.$web_root.'cms-documents/'.$documentFile.'" title="'.$documentTitle.'" target="_blank">'.$documentTitle.'</a></div>';
						}

						$txt.= '<div class="module-manage-content-links">
							<form name="delete_paragraph'.$portfolioContentID.'">
								<input type="hidden" name="delete_paragraph" value="1">
								<input type="hidden" name="portfolioContentID" value="'.$portfolioContentID.'">
								<input type="hidden" name="portfolioID" value="'.$portfolioID.'">
								<a href="javascript:deleteParagraph('.$portfolioContentID.')" title="Remove Paragraph">Remove Paragraph</a>
							</form>
							<a href="'.$cms_root.'portfolio-manager/edit-paragraph.php?portfolioContentID='.$portfolioContentID.'&portfolioID='.$portfolioID.'" title="Edit Paragraph">Edit Paragraph</a>
							<div class="clear"></div>
							</div>
                    </div>';
				}
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'portfolio-manager/add-paragraph.php?portfolioID='.$portfolioID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';

            /*$txt.= '<div class="module-manage-content-holder-nothing">There is currently no Gallery available. <a href="'.$cms_root.'portfolio-manager/add-gallery.php?portfolioID='.$portfolioID.'" title="Add Gallery">Please add a gallery here!</a></div>';*/
		}

        //IF GALLERY(S) REMOVED RELOAD PAGE
        if($removedGallery == 1){
            header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=8");
    		exit;
        }

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // SET basicPagesGalleryID AND RETURN IT
    //#################################################################
	function setPortfolioGalleryID($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//ADD portfolioID INTO portfolio_gallery
		$insert = $connector->query("INSERT INTO portfolio_gallery (portfolioID, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($portfolioID, $currentUser, $currentDate));

        //GET portfolioGalleryID
        $result = $connector->query("SELECT * FROM portfolio_gallery WHERE portfolioID = ? AND createdBy = ? AND createdDate = ? AND deletedBy =?", array($portfolioID, $currentUser, $currentDate, 0));
        $row    = $connector->fetchArray($result);

        //RETURN basicPagesGalleryID
        return $row['portfolioGalleryID'];
	}

    //#################################################################
    // ADD $portfolioGalleryID INTO portfolio_content
    //#################################################################
	function addPortfolioGalleryIDIntoPageContent($portfolioID, $portfolioGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET SEQUENCE
        $result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioID = ? AND deletedBy = ? ORDER BY sequence DESC LIMIT 0,1", array($portfolioID, 0));
        $row    = $connector->fetchArray($result);
        $sequence   = $row['sequence']+1;

        //ADD $portfolioGalleryID INTO portfolio_content
        $insert = $connector->query("INSERT INTO portfolio_content (portfolioID, portfolioGalleryID, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?)",
									array($portfolioID, $portfolioGalleryID, $currentUser, $currentDate, $sequence));
	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($portfolioGalleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ? ORDER BY sequence DESC", array($basicPagesGalleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD IMAGES INTO DATABASE
		$insert = $connector->query("INSERT INTO portfolio_gallery_content (portfolioGalleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($portfolioGalleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($portfolioContentID, $portfolioGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM portfolio_gallery_content WHERE portfolioGalleryID = ?", array($portfolioGalleryID));
		while($row	= $connector->fetchArray($result)){
            $galleryImageFile           = $row['galleryImageFile'];
            $portfolioGalleryContentID   = $row['portfolioGalleryContentID'];

    		//DELETE IMAGES
    		unlink($largeDirectory.$galleryImageFile);
    		unlink($mediumDirectory.$galleryImageFile);
    		unlink($smallDirectory.$galleryImageFile);

    		//REMOVE USER
    		$remove = $connector->query("DELETE FROM portfolio_gallery_content WHERE portfolioGalleryContentID = ?",array($portfolioGalleryContentID));
        }

        //REMOVE GALLERY ENTRIES
        $removeGallery = $connector->query("DELETE FROM portfolio_gallery WHERE portfolioGalleryID = ?",array($portfolioGalleryID));
        $removeEntry = $connector->query("DELETE FROM portfolio_content WHERE portfolioContentID = ?",array($portfolioContentID));

	}

    //#################################################################
	//GET GALLERY TOTAL
	//#################################################################
	function getGalleryTotal($portfolioID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //GET GALLERY INFO
        $result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioID = ? AND portfolioGalleryID != ? AND deletedBy = ?" ,array($portfolioID, 0, 0));
        $total  = $connector->numResults($result);

        //RETURN TOTAL
        return $total;
    }

    //#################################################################
	//OVERWRITE WEBSITE
	//#################################################################
	function overwriteWebsite($name, $link){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$name	    = strip_tags($name);
        $link	    = strip_tags($link);

		//RE-ACTIVATE SOFTWARE
		$update = $connector->query("UPDATE portfolio SET
                                    websiteLink = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE websiteName = ?",
									array($link, '0', '0000-00-00 00:00:00', $name));

	}

	//#################################################################
    // CHECK IF ANY WEBSITES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedWebsites(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM portfolio WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

    //#################################################################
    // CHECK IF WEBSITE INFO HAS BEEN CHANGED
    //#################################################################
	function checkWebsiteChanges($name, $link, $portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM portfolio WHERE websiteName = ? AND websiteLink = ? AND portfolioID = ?", array($name, $link, $portfolioID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD WEBSITE
    //#################################################################
	function addWebsite($name, $link, $imageFile, $project_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name		    = strip_tags($name);

		//ADD WEBSITE
		$insert = $connector->query("INSERT INTO portfolio (websiteName, websiteLink, url, coverImage, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($name, $link, $project_url, $imageFile, $currentUser, $currentDate));

        //GET LAST INSERTED ID
        $result = $connector->query("SELECT * FROM portfolio ORDER BY portfolioID DESC", array());
        $row    = $connector->fetchArray($result);

        //RETURN LAST ID
        return $row['portfolioID'];

	}

	//#################################################################
    // UPDATE WEBSITE
    //#################################################################
	function updateWebsite($name, $link, $imageFile, $project_url, $modifiedBy, $modifiedDate, $modifiedNumber, $portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$name         	= strip_tags($name);
        $link           = strip_tags($link);

		//UPDATE USER
		$update = $connector->query("UPDATE portfolio SET
									websiteName = ?,
                                    websiteLink = ?,
                                    url = ?,
                                    coverImage = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE portfolioID = ?",
									array($name, $link, $project_url, $imageFile, $modifiedBy, $modifiedDate, $modifiedNumber, $portfolioID));

	}

	//#################################################################
    // DELETE WEBSITE
    //#################################################################
	function deleteWebsite($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE portfolio SET
									deletedBy = ?,
									deletedDate = ?
									WHERE portfolioID = ?",
									array($currentUser, $currentDate, $portfolioID));

	}

	//#################################################################
    // RECOVER WEBSITE
    //#################################################################
	function recoverWebsite($portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE portfolio SET
									deletedBy = ?,
									deletedDate = ?
									WHERE portfolioID = ?",
									array('0', '0000-00-00 00:00:00', $portfolioID));

	}

	//#################################################################
    // CHECK IF WEBSITE NAME IS ALREADY IN USE
    //#################################################################
	function addWebsiteCheck($websiteName, $portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM portfolio WHERE websiteName = ? AND portfolioID != ?", array($websiteName, $portfolioID));
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
				return 'removed_website';
			}
		}

	}

    //#################################################################
    // GET WEBSITE COVER IMAGE
    //#################################################################
	function getWebsiteCoverImage($portfolioID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM portfolio WHERE portfolioID = ?", array($portfolioID));
		$row	= $connector->fetchArray($result);
		$coverImage   = $row['coverImage'];
		$websiteName  = $row['websiteName'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($coverImage != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$coverImage.'" title="'.$websiteName.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$coverImage.'" title="'.$websiteName.'" alt="'.$websiteName.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // ADD WEBSITE PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $image_title, $imageFile, $portfolioID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$image_title	= strip_tags($image_title);

		//GET SEQUENCE
		$result	= $connector->query("SELECT * FROM portfolio_content WHERE portfolioID = ? AND deletedBy = ? ORDER BY sequence DESC", array($portfolioID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO portfolio_content (portfolioID, paragraphTitle, paragraph, imageFile, imageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
									array($portfolioID, $title, $paragraph, $imageFile, $image_title, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE WEBSITE PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $image_title, $imageFile, $portfolioContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$image_title	= strip_tags($image_title);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioContentID = ?", array($portfolioContentID));
        $row    = $connector->fetchArray($result);
        $image  = $row['imageFile'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM portfolio_content WHERE portfolioContentID = ?", array($portfolioContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE portfolio_content SET
                                            paragraphTitle  = ?,
                                            paragraph       = ?,
                                            imageFile       = ?,
                                            imageTitle      = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE portfolioContentID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $currentUser, $modifiedNumber, $currentDate, $portfolioContentID));

	}

    //#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($portfolioContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM portfolio_content WHERE portfolioContentID = ?", array($portfolioContentID));
		$row	= $connector->fetchArray($result);
		$imageFile		= $row['imageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$imageFile);
		unlink($mediumDirectory.$imageFile);
		unlink($smallDirectory.$imageFile);

		//REMOVE USER
		$remove = $connector->query("DELETE FROM portfolio_content WHERE portfolioContentID = ?",array($portfolioContentID));

	}

}

//DEFINE CLASS
$portfolioManager = new portfolioManager();


//#################################################################
//DELETE WEBSITE
//#################################################################
if(isset($_POST['delete_website'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $portfolioID	= $_POST['portfolioID'];

    //SET WEBSITE AS REMOVED IN DATABASE
    $portfolioManager->deleteWebsite($portfolioID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."portfolio-manager/index.php?message=3");
    exit;
}

//#################################################################
//RECOVER WEBSITE
//#################################################################
if(isset($_POST['recover_website'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $portfolioID	= $_POST['portfolioID'];

    //SET WEBSITE AS ACTIVE IN DATABASE
    $portfolioManager->recoverWebsite($portfolioID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."portfolio-manager/index.php?message=4");
    exit;
}

//#################################################################
// ADD WEBSITE
//#################################################################
if(isset($_POST['add_website'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name		        = $_POST['website-name'];
    $link               = $_POST['website-link'];

	//HONEY POTS
	$website_type	= $_POST['website-type'];
    $image_type     = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name       = $userLogin->specialCharactersToHTMLEntity($name);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Website  Name', 1, 200);
    $v->validateLink($link, 'Website Link');
    $v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($website_type == '' && $image_type == ''){

            //CHECK IF WEBSITE NAME IS ALREADY IN USE
			$website_used = $portfolioManager->addWebsiteCheck($name, $portfolioID);
            if($website_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $name);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //CREATE PROJECT URL
                $project_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($name));
                $project_url = str_replace(' ', '-', $project_url);

    			//INSERT WEBSITE INTO DATABASE
    			$portfolioID = $portfolioManager->addWebsite($name, $link, $imageFile, $project_url);

                //REDIRECT USER
    			header("Location: ".$cms_root."portfolio-manager/crop-image-website.php?portfolioID=".$portfolioID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;

			}
			//IF WEBSITE HAS BEEN REMOVED
			elseif($website_used == 'removed_website'){
				//SET USER AS REMOVED
				$removed_website = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Website Name</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT WEBSITE
//#################################################################
if(isset($_POST['edit_website'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name              = $_POST['website-name'];
    $link              = $_POST['website-link'];
	$portfolioID   	   = $_POST['portfolioID'];
    $oldImage          = $_POST['oldImage'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$website_type	= $_POST['website-type'];
    $image_type     = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name       = $userLogin->specialCharactersToHTMLEntity($name);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($name, 'Website Name', 1, 200);
    $v->validateLink($link, 'Website Link');

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($website_type == '' && $image_type == ''){

            //CHECK IF WEBSITE HAS BEEN UPDATED
            if($_FILES[$inputField]["tmp_name"] != ''){
                $changed = 'changed';
            }else{
                $changed = $portfolioManager->checkWebsiteChanges($name, $link, $portfolioID);
            }

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($changed == 'changed'){

                //CHECK IF WEBSITE NAME IS ALREADY IN USE
    			$website_used = $portfolioManager->addWebsiteCheck($name, $portfolioID);
                if($website_used == 'unused'){

                    //IF AN IMAGE HAS BEEN ADDED
        			if($_FILES[$inputField]["tmp_name"] != ""){
        				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $name);

        				//GET THE IMAGE SIZE
        				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);

						//REMOVE OLD IMAGE
                        unlink($largeDirectory.$oldImage);
                        unlink($mediumDirectory.$oldImage);
                        unlink($smallDirectory.$oldImage);
        			}
                    //IF NO NEW IMAGE HAS BEEN UPLOADED
                    else{
                        $imageFile      = $oldImage;
                    }

                    //CREATE PROJECT URL
                    $project_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($name));
                    $project_url = str_replace(' ', '-', $project_url);

                    //UPDATE WEBSITE IN DATABASE
    				$portfolioManager->updateWebsite($name, $link, $imageFile, $project_url, $modifiedBy, $modifiedDate, $modifiedNumber, $portfolioID);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."portfolio-manager/crop-image-website.php?portfolioID=".$portfolioID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
                		exit;
                    }else{
                        header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=2");
                		exit;
                    }

    			}
    			//IF WEBSITE HAS BEEN REMOVED
    			elseif($website_used == 'removed_website'){
    				//SET USER AS REMOVED
    				$removed_website = '1';
    			}
    			else{
    				//SET ERROR MESSAGE
    				$error_message = 'There was an error!';
    				$errors = '<ul class="errors"><li>The <b>Website Name</b> you supplied is already in use. Please try another!</li></ul>';
    			}

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
                //REDIRECT USER
				header("Location: ".$cms_root."portfolio-manager/");
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
//ADD WEBSITE GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $portfolioID     = $_POST['portfolioID'];
    $value          = json_decode(stripslashes($_POST['value']));

    //HONEY POTS
    $galleryName    = $_POST['galleryName'];

    //IMAGE PROPERTIES
    $inputField				= 'image';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 500;

    $count = 0;

    //CHECK THAT VALUE CANT BE EMPTY
    if(!empty($value)){

        //CHECK IF ALL CONDITONS HAVE BEEN MET
        if($galleryName == ''){

            //GET portfolioGalleryID
            $portfolioGalleryID  = $portfolioManager->setPortfolioGalleryID($portfolioID);

            //ADD portfolioGalleryID INTO basic_pages_content
            $portfolioManager->addPortfolioGalleryIDIntoPageContent($portfolioID, $portfolioGalleryID);

            //LOOP THROUGH ALL POSTED IMAGES
            foreach ($value as $file => $key) {

                //CHECK IF KEY IS EMPTY (WHICH MEANS THAT IMAGE SHOULD NOT BE UPLOADED)
                if($key != ''){

                    //CHECK IF KEY AND IMAGE RESOURCE MATCH
                    if($_FILES['image']['name'][$file] == $key){

                        //GET IMAGE TITLE
                        $imageTitle = $_POST['imageTitle_'.$count];

                        //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
                        $imageTitle       = $userLogin->specialCharactersToHTMLEntity($imageTitle);

                        //UPLOAD IMAGES
                        $file_name  = $fileUploader->uploadGalleryImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $imageTitle, $file);

                        //INSERT INTO DATABASE
                        $portfolioManager->addGalleryImages($portfolioGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $hasImages = 1;

                    }

                    $count++;
                }
            }

            //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
            if($hasImages == 1){
                header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=4");
        		exit;
            }else{
                header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID);
        		exit;
            }
        }
    }else{
        $error_message  = 'There was an error creating your galley!';
        $errors         = 'You have to choose at least one image in order to create the gallery!';
    }
}

//#################################################################
//EDIT WEBSITE GALLERY
//#################################################################
if(isset($_POST['edit_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $portfolioID            = $_POST['portfolioID'];
    $portfolioGalleryID     = $_POST['portfolioGalleryID'];
    $value                  = json_decode(stripslashes($_POST['value']));

    //HONEY POTS
    $galleryName    = $_POST['galleryName'];

    //IMAGE PROPERTIES
    $inputField				= 'image';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 500;

    $count = 0;

    //CHECK IF ALL CONDITONS HAVE BEEN MET
    if($galleryName == ''){

        //CHECK IF GALLERY IMAGE NEEDS TO BE REMOVED OR UPDATED
        $updatedGalleryImages = $portfolioManager->updateRemoveGalleryImages($portfolioGalleryID);

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
                        $imageTitle       = $userLogin->specialCharactersToHTMLEntity($imageTitle);

                        //UPLOAD IMAGES
                        $file_name  = $fileUploader->uploadGalleryImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $imageTitle, $file);

                        //INSERT INTO DATABASE
                        $portfolioManager->addGalleryImages($portfolioGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $portfolioManager->updatePortfolioGalleryInfo($portfolioGalleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=7");
    		exit;
        }else{
            header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID);
    		exit;
        }
    }
}

//#################################################################
//DELETE WEBSITE GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$portfolioGalleryID   = $_POST['portfolioGalleryID'];
	$portfolioContentID   = $_POST['portfolioContentID'];
    $portfolioID          = $_POST['portfolioID'];

    //REMOVE GALLERY FROM DATABASE
    $portfolioManager->deleteGallery($portfolioContentID, $portfolioGalleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=8");
    exit;
}

//#################################################################
// REACTIVATE WEBSITE
//#################################################################
if(isset($_POST['reactivate-website-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name          		= $_POST['website-name'];
    $link   		    = $_POST['website-link'];

	//HONEY POTS
	$website_type  = $_POST['website-type'];

	if($website_type == ''){

		//OVERWRITE WEBSITE
		$portfolioManager->overwriteWebsite($name, $link);

		//REDIRECT PAGE
		header("Location: ".$cms_root."portfolio-manager/index.php?message=5");
		exit;
	}
}

//#################################################################
// ADD WEBSITE PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$portfolioID	= $_POST['portfolioID'];
	$title			= $_POST['paragraph-title'];
	$paragraph 		= $_POST['paragraph'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$paragraph_type	= $_POST['paragraph-type'];
	$image_type		= $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateText($paragraph, 'Paragraph', 10);

	//IF TITLE HAS BEEN ADDED
	if($title != ''){
		$v->validateString($title, 'Paragraph Title', 1, 200);
	}

	//IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateString($image_title, 'Image Title',3, 150);
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($paragraph_type == '' && $image_type == ''){

			//IF AN IMAGE HAS BEEN ADDED
			if($_FILES[$inputField]["tmp_name"] != ""){
				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

				//GET THE IMAGE SIZE
				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
			}

			//REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT BLOG POST INTO DATABASE
			$portfolioManager->addParagraph($title, $paragraph, $image_title, $imageFile, $portfolioID);

			//GET META DETAILS
			$keywords		= $portfolioManager->getMetaKeyword($portfolioID);
			$description	= $portfolioManager->getMetaDescription($portfolioID);

			//UPDATE META DETAILS
			$portfolioManager->updateMetaDetails($keywords, $description, $portfolioID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."portfolio-manager/crop-image.php?portfolioID=".$portfolioID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=9");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=9");
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
// EDIT WEBSITE PARAGRAPH
//#################################################################
if(isset($_POST['edit_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$portfolioID		   = $_POST['portfolioID'];
	$portfolioContentID    = $_POST['portfolioContentID'];
	$title                 = $_POST['paragraph-title'];
	$paragraph             = $_POST['paragraph'];
	$image_title           = $_POST['image-title'];
    $removeImage           = $_POST['removeImage'];
    $oldImage              = $_POST['oldImage'];

	//HONEY POTS
	$paragraph_type    = $_POST['paragraph-type'];
	$image_type        = $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateText($paragraph, 'Paragraph', 10);

	//IF TITLE HAS BEEN ADDED
	if($title != ''){
		$v->validateString($title, 'Paragraph Title', 1, 200);
	}

	//IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateString($image_title, 'Image Title',3, 150);
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($paragraph_type == '' && $image_type == ''){

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
                    $image_title    = $portfolioManager->getWebsiteContentInfo($portfolioContentID, 'imageTitle');
                }
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT BLOG POST INTO DATABASE
			$portfolioManager->updateParagraph($title, $paragraph, $image_title, $imageFile, $portfolioContentID);

			//GET META DETAILS
			$keywords		= $portfolioManager->getMetaKeyword($portfolioID);
			$description	= $portfolioManager->getMetaDescription($portfolioID);

			//UPDATE META DETAILS
			$portfolioManager->updateMetaDetails($keywords, $description, $portfolioID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."portfolio-manager/crop-image.php?portfolioID=".$portfolioID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=10");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=10");
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
//DELETE PARAGRAPH
//#################################################################
if(isset($_POST['delete_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$portfolioContentID	= $_POST['portfolioContentID'];
	$portfolioID		= $_POST['portfolioID'];

    //SET USER AS REMOVED IN DATABASE
    $portfolioManager->deleteParagraph($portfolioContentID);

    //GET META DETAILS
    $keywords		= $portfolioManager->getMetaKeyword($portfolioID);
    $description	= $portfolioManager->getMetaDescription($portfolioID);

    //UPDATE META DETAILS
    $portfolioManager->updateMetaDetails($keywords, $description, $portfolioID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=11");
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
if($websiteRatio == 1){
    $newWidth		= 1920;
    $newHeight		= 600;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($normalRatio == 1){
    $newWidth		= 1920;
    $newHeight		= 700;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$portfolioID		= $_POST['portfolioID'];
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
	header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=".$message);
    exit;
}

//CROP WEBSITE COVER IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-website'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$portfolioID		= $_POST['portfolioID'];
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
	header("Location: ".$cms_root."portfolio-manager/manage-website-content.php?portfolioID=".$portfolioID."&message=".$message);
    exit;
}
###################################################################
?>
