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

class toursManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Tour has successfully been added.'; break;
			case 4: $displayMessage = 'The selected Tour has successfully been updated.'; break;
			case 6: $displayMessage = 'The selected Tour has successfully been removed.'; break;
			case 9: $displayMessage = 'The selected Tour has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Tour has successfully been re-activated.'; break;
			case 11: $displayMessage = 'A new Paragraph has successfully been added.'; break;
			case 12: $displayMessage = 'The selected Paragraph has successfully been updated.'; break;
			case 13: $displayMessage = 'The selected Paragraph has successfully been removed.'; break;
            case 14: $displayMessage = 'The selected Product has successfully been permanently deleted.'; break;
            case 15: $displayMessage = 'A new Gallery has successfully been added.'; break;
            case 16: $displayMessage = 'The selected Gallery has successfully been updated.'; break;
            case 17: $displayMessage = 'The selected Gallery has successfully been removed.'; break;
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
	function getMetaKeyword($tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND deletedBy = ? ORDER BY sequence ASC", array($tourID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['paragraph']).' '.strip_tags($row['paragraphTitle']);
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
	function getMetaDescription($tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND deletedBy = ? ORDER BY sequence ASC", array($tourID, 0));
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
	function updateMetaDetails($keywords, $description, $tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE tourID = ?", array($tourID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (tourID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($tourID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE tourID = ?",
												array($keywords, $description, $tourID));
		}
	}

    //#################################################################
	//CHECK TOUR URL EXISTS
	//#################################################################
	function checkTourURLExists($url, $tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED
        $currentURL = $this->getTourInfo($tourID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM tours WHERE url = ? LIMIT 0,1", array($url));
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
                    $result2    = $connector->query("SELECT url FROM tours WHERE url = ? LIMIT 0,1", array($newURL));
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
	// ADD TOUR INTO SEARCH INDEX
	//#################################################################
	function addTourSearchIndex($tourID, $title, $keywords, $tour_intro){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//GET INDEX INFO
		$result	= $connector->query("SELECT * FROM search_index WHERE tourID = ?", array($tourID));
		$row	= $connector->fetchArray($result);
		$total	= $connector->numResults($result);

		//CHECK IF PRODUCT IS ALREADY INDEX
		if($total == 0){
			//INSERT PRODUCT SEARCH INDEX
			$insert	= $connector->query("INSERT INTO search_index (title, keywords, content, tourID)
										VALUES(?, ?, ?, ?)"
										, array($title, $keywords, $tour_intro, $tourID));
		}else{
			//UPDATE PRODUCT SEARCH INDEX
			$update	= $connector->query("UPDATE search_index SET
										title			= ?,
										keywords		= ?,
										content			= ?
										WHERE tourID = ?"
										, array($title, $keywords, $tour_intro, $tourID));
		}

	}

    //#################################################################
	// REMOVE TOUR FROM SEARCH INDEX
	//#################################################################
	function removeTourSearchIndex($tourID){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//DELETE PRODUCT
        $connector->query('DELETE FROM search_index WHERE tourID = ?', array($tourID));

	}

	//#################################################################
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($productCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY INFO
		$result = $connector->query("SELECT * FROM product_category WHERE productCatID = ?", array($productCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET PARAGRAPH INFORMATION
    //#################################################################
	function getParagraphInfo($tourContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET PARAGRAPH INFO
		$result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET PRODUCT IMAGE
    //#################################################################
	function getProductImage($tourID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tours WHERE tourID = ?", array($tourID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['tourImageFile'];
		$imageTitle	= $row['tourImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET CATEGORY IMAGE
    //#################################################################
	function getCategoryImage($productCatID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_category WHERE productCatID = ?", array($productCatID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['catImage'];
		$imageTitle	= $row['catImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

    //#################################################################
    // GET GALLERY INFORMATION
    //#################################################################
	function getGalleryInfo($tourGalleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tour_gallery WHERE tourGalleryID = ?", array($tourGalleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET TOUR INFORMATION
    //#################################################################
	function getTourInfo($tourID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tours WHERE tourID = ?", array($tourID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET TOUR CONTENT INFORMATION
    //#################################################################
	function getTourContentInfo($tourContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET PRODUCT CONTENT INFO
		$result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET TOUR CONTENT VIDEO
    //#################################################################
	function getTourContentVideo($tourContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
		$row	= $connector->fetchArray($result);
		$videoUrl	= $row['videoUrl'];

		//IF URL IS YOUTUBE
		if(strpos($videoUrl,'youtube') !== false){

			//GENERATE OUTPUT
			$embedYouTube	= str_replace("watch?v=", "embed/", $videoUrl);

			$txt.= '<div class="video-spacing" align="center"><div class="video-header"><b>Current Video:</b></div><br /><iframe width="560" height="315" src="'.$embedYouTube.'" frameborder="0" allowfullscreen></iframe><br /><br /><input type="checkbox" name="removeVideo" value="1" />Remove the YouTube video from paragraph</div>';
		}
		//IT URL IS VIMEO
		elseif(strpos($videoUrl,'vimeo') !== false){

			//GENERATE OUTPUT
			$embedVimeo = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $videoUrl);

			$txt.= '<div class="video-spacing" align="center"><div class="video-header"><b>Current Video:</b></div><br /><iframe src="'.$embedVimeo.'" width="560" height="315" frameborder="0" allowfullscreen></iframe><br /><br /><input type="checkbox" name="removeVideo" value="1" />Remove the Vimeo video from paragraph</div>';
		}

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // GET TOUR CONTENT DOCUMENT
    //#################################################################
	function getTourContentDocument($tourContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
		$row	= $connector->fetchArray($result);
		$documentFile	= $row['documentFile'];
		$documentTitle	= $row['documentTitle'];

        //CHECK IF A DOCUMENT IS A AVAILABLE
        if($documentFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="document-spacing" align="center"><div class="document-header"><b>Current Document: </b><span><a href="'.$web_root.'cms-documents/'.$documentFile.'" title="View '.$documentTitle.'" target="_blank">'.$documentTitle.'</a></span></div><br /><input type="checkbox" name="removeDocument" value="1" />Remove Document from paragraph</div>';
        }
		//RETURN OUTPUT
		return $txt;


	}

	//#################################################################
    // GET TOUR CONTENT IMAGE
    //#################################################################
	function getTourContentImage($tourContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
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
    // GET ALL PRODUCT CATEGORIES
    //#################################################################
	function getAllProductCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL CATEGORIES
		$result = $connector->query("SELECT * FROM product_category ORDER BY categoryName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $categoryName    = $row['categoryName'];

			$txt.= '"'.$categoryName.'",';
		}

		return substr($txt, 0, -1);
	}

    //#################################################################
    // GET TOUR GALLERY IMAGES
    //#################################################################
	function getTourGalleryImages($tourGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PRODUCT GALLERY INFO
		$result = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ? ORDER BY tourGalleryContentID ASC", array($tourGalleryID));
		while($row	= $connector->fetchArray($result)){
            $tourGalleryContentID       = $row['tourGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade" id="img'.$tourGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="remove_gallery_image">
                    <input type="checkbox" name="remove_gallery_image_'.$tourGalleryContentID.'" value="1" />
                    <div class="remove_gallery_image_text">Remove Image</div>
                </div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$tourGalleryContentID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET PRODUCT GALLERY IMAGES FOR SEQUENCING
    //#################################################################
	function getProductGalleryImagesSequencing($productGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? ORDER BY sequence ASC", array($productGalleryID));
		while($row	= $connector->fetchArray($result)){
            $productGalleryContentID    = $row['productGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade sortable-content" id="'.$productGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title: <span class="normal-text">'.$galleryImageTitle.'</span></div></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF PRODUCT CATEGORY IS IN DATABASE
    //#################################################################
	function checkCategoryDatabase($productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM product_category WHERE productCatID = ?", array($productCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}

	}

	//#################################################################
    // CHECK IF TOUR IS IN DATABASE
    //#################################################################
	function checkTourDatabase($tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET TOUR TOTAL
		$result = $connector->query("SELECT * FROM tours WHERE tourID = ?", array($tourID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF TOUR GALLERY IS IN DATABASE
    //#################################################################
	function checkTourGalleryDatabase($tourGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM tour_gallery WHERE tourGalleryID = ? ", array($tourGalleryID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF TOUR CONTENT IS IN DATABASE
    //#################################################################
	function checkTourContentDatabase($tourID, $tourContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET TOUR TOTAL
		$result = $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND tourContentID = ?", array($tourID, $tourContentID));
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
    // GET TOTAL TOURS
    //#################################################################
	function getTotalTours(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM  tours WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET EMPTY TOURS
    //#################################################################
	function getEmptyTours(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM tours WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VARIABLES
			$tourID	= $row['tourID'];

			//GET ALL CONTENT FOR PRODUCTS
			$result2	= $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND deletedBy = ?", array($tourID, '0'));
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
    // TOUR ARCHITECTURE
    //#################################################################
	function tourArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL TOURS
		$result = $connector->query("SELECT * FROM tours WHERE deletedBy = ? ORDER BY tourTitle ASC", array('0',));
		$tourTotal = $connector->numResults($result);

		//IF TOURS ARE AVAILABLE
		if($tourTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$status			= '';
				$status_bg		= '';
				$date			= '';
				$currentDate	= date('Y-m-d H:i:s');
				$tourID		    = $row['tourID'];
				$tourTitle      = $row['tourTitle'];

				//GET ALL TOUR CONTENT FOR A TOUR
				$result2	= $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND deletedBy = ?", array($tourID, '0'));
				$tourContentTotal	= $connector->numResults($result2);

				//IF TOUR IS EMPTY
				if($tourContentTotal == 0){
					$status		= '<span class="empty-category-text">(Empty)</span>';
					$status_bg	='class="empty-category"';
				}

				//GENERATE OUPUT
				$txt.= '<tr>
					<td '.$status_bg.'>'.$tourTitle.' '.$status.'</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'tours-manager/manage-tour.php?tourID='.$tourID.'" title="Manage">Manage</a>
					</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'tours-manager/edit-tour.php?tourID='.$tourID.'" title="Modify">Modify</a>
					</td>
					<td '.$status_bg.' align="center">';

					$txt.='<form name="delete_tour'.$tourID.'">
							<input type="hidden" name="delete_tour" value="1">
							<input type="hidden" name="tourID" value="'.$tourID.'">
							<a href="javascript:deleteTour('.$tourID.')" title="Remove">Remove</a>
						</form>';

					$txt.= '</td>
				  </tr>';
			}
		}
		//IF NO TOURS ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="5">There are currently no Tours available. <a href="'.$cms_root.'tours-manager/add-tour.php" title="Add Tour">Please add a Tour here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // REMOVE EMPTY GALLERY
    //#################################################################
    function removeEmptyGallery($productGalleryID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //DELETE FROM product_gallery
        $deleteGallery = $connector->query("DELETE FROM product_gallery WHERE productGalleryID = ?", array($productGalleryID));

        //DELETE FROM product_content
        $deleteGalleryContent = $connector->query("DELETE FROM product_content WHERE productGalleryID = ?", array($productGalleryID));
    }

	//#################################################################
    // TOUR CONTENT ARCHITECTURE
    //#################################################################
	function tourContentArchitecture($cms_root, $web_root, $tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM tour_content WHERE deletedBy = ?  AND tourID = ? ORDER BY sequence ASC", array('0', $tourID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$tourContentID	    = $row['tourContentID'];
				$paragraphTitle		= $row['paragraphTitle'];
				$paragraph			= $row['paragraph'];
				$imageFile			= $row['imageFile'];
				$imageTitle			= $row['imageTitle'];
				$documentFile		= $row['documentFile'];
				$documentTitle		= $row['documentTitle'];
				$videoUrl			= $row['videoUrl'];
				$tourGalleryID      = $row['tourGalleryID'];
                $sequence           = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				if($tourGalleryID != 0){

                    //CHECK IF IMAGES IN GALLERY
                    $result4        = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ?", array($tourGalleryID));
                    $totalImages    = $connector->numResults($result4);

                    //REMOVE GALLERY
                    if($totalImages == 0){
                        $this->removeEmptyGallery($tourGalleryID);
                        $removedGallery = 1;

                    }else{
    					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$tourContentID.'">';

                            //GET TOTAL GALLERY IMAGES
                            $result2    = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($tourGalleryID, 0));
                            $totalGalleryImage  = $connector->numResults($result2);

                            //IF MORE THAN 6 GALLERY IMAGES
                            if($totalGalleryImage > 6){
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC LIMIT 0,5", array($tourGalleryID, 0));
                            }else{
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($tourGalleryID, 0));
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

                                $txt.= '<a href="'.$cms_root.'tours-manager/edit-gallery.php?tourID='.$tourID.'&tourGalleryID='.$tourGalleryID.'" title="View all Gallery Images">
                                    <div class="paragraph-image-indicator">
                                        <div class="paragraph-image-more-indicator">+'.$extraImages.'</div>
                                    </div>
                                </a>';
                            }

                            $txt.= '<div class="clear"></div>
                            <div class="module-manage-content-links">
    							<form name="delete_gallery'.$tourContentID.'">
    								<input type="hidden" name="delete_gallery" value="1">
    								<input type="hidden" name="tourContentID" value="'.$tourContentID.'">
    								<input type="hidden" name="tourGalleryID" value="'.$tourGalleryID.'">
                                    <input type="hidden" name="tourID" value="'.$tourID.'">
    								<a href="javascript:deleteGallery('.$tourContentID.')" title="Remove Gallery">Remove Gallery</a>
    							</form>
    							<a href="'.$cms_root.'tours-manager/edit-gallery.php?tourID='.$tourID.'&tourGalleryID='.$tourGalleryID.'" title="Edit Gallery">Edit Gallery</a>
                                <a href="'.$cms_root.'tours-manager/sequence-gallery.php?tourID='.$tourID.'&tourGalleryID='.$tourGalleryID.'" title="Sequence Gallery">Sequence Gallery</a>
    							<div class="clear"></div>
    							</div>
                        </div>';
                    }
				}else{
					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$tourContentID.'">';

						//IF AN IMAGE IS AVAILABLE
						if($imageFile != ''){
							$txt.= '<div class="paragraph-image">
								<img src="'.$web_root.'cms-images/medium/'.$imageFile.'" alt="'.$imageTitle.'" title="'.$imageTitle.'" border="0"/>
							</div>';
						}

						//IF A TITLE IS AVAILABLE
						if($paragraphTitle != ''){
                    		$txt.= '<div class="paragraph-title"><b>'.$this->HTMLEntityToSpecialCharacters($paragraphTitle).'</b></div>';
						}

						$txt.= '<div class="paragraph-text">'.$paragraph.'</div>
                        		<div class="clear"></div>';

						//IF A VIDEO IS AVAILABLE
						if($video4 != ''){
                        	$txt.= '<div class="paragraph-links">Video: <a href="'.$videoUrl.'" target="_blank">'.$videoUrl.'</a></div>';
						}

						//IF A DOCUMENT IS AVAILABLE
						if($documentFile != ''){
							$txt.= '<div class="paragraph-links">Document: <a href="'.$web_root.'cms-documents/'.$documentFile.'" title="'.$documentTitle.'" target="_blank">'.$documentTitle.'</a></div>';
						}

						$txt.= '<div class="module-manage-content-links">
							<form name="delete_paragraph'.$tourContentID.'">
								<input type="hidden" name="delete_paragraph" value="1">
								<input type="hidden" name="tourContentID" value="'.$tourContentID.'">
								<input type="hidden" name="tourID" value="'.$tourID.'">
								<a href="javascript:deleteParagraph('.$tourContentID.')" title="Remove Paragraph">Remove Paragraph</a>
							</form>
							<a href="'.$cms_root.'tours-manager/edit-paragraph.php?tourContentID='.$tourContentID.'&tourID='.$tourID.'" title="Edit Paragraph">Edit Paragraph</a>
							<div class="clear"></div>
							</div>
                    </div>';
				}
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'tours-manager/add-paragraph.php?tourID='.$tourID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';
		}

        //IF GALLERY(S) REMOVED RELOAD PAGE
        if($removedGallery == 1){
            header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=17");
    		exit;
        }

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // CHECK IF PRODUCT CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkCategoryChanges($title, $paragraph, $image_title, $productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND catImageTitle = ? AND productCatID = ? AND categoryDescription = ?", array($title, $image_title, $productCatID, $paragraph));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // CHECK IF TOUR INFO HAS BEEN CHANGED
    //#################################################################
	function checkTourChanges($tour_title, $tour_intro, $tourID, $image_title){

		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE TOUR INFO
		$result = $connector->query("SELECT * FROM tours WHERE tourTitle = ? AND tourIntro = ? AND tourID = ? AND tourImageTitle = ?", array($tour_title, $tour_intro, $tourID, $image_title));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // ADD CATEGORY
    //#################################################################
	function addCategory($title, $paragraph, $image_title, $imageFile, $category_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$category_name		= strip_tags($title);
        $image_title		= strip_tags($image_title);

		//ADD PRODUCT CATEGORY
		$insert = $connector->query("INSERT INTO product_category (categoryName, categoryDescription, catImageTitle, catImage, url, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?)",
									array($title, $paragraph, $image_title, $imageFile, $category_url, $currentUser, $currentDate));

        //RETURN CATEGORY ID
        $result = $connector->query("SELECT * FROM product_category ORDER BY productCatID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['productCatID'];

	}

    //#################################################################
    // UPDATE PRODUCT CATEGORY
    //#################################################################
	function updateCategory($title, $paragraph, $imageFile, $image_title, $category_url, $modifiedBy, $modifiedDate, $modifiedNumber, $productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$title          = strip_tags($title);
        $image_title	= strip_tags($image_title);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM product_category WHERE productCatID = ?", array($productCatID));
        $row    = $connector->fetchArray($result);
        $image  = $row['catImage'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE product_category SET
									categoryName = ?,
                                    categoryDescription = ?,
                                    catImageTitle = ?,
                                    catImage = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE productCatID = ?",
									array($title, $paragraph, $image_title, $imageFile, $category_url, $modifiedBy, $modifiedDate, $modifiedNumber, $productCatID));

	}

	//#################################################################
    // DELETE CATEGORY
    //#################################################################
	function deleteCategory($productCatID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET IMAGES
        $result = $connector->query("SELECT * FROM product_category WHERE productCatID = ?",array($productCatID));
        $row    = $connector->fetchArray($result);
        $catImage = $row['catImage'];

        //REMOVE IMAGES
        unlink($largeDirectory.$catImage);
        unlink($mediumDirectory.$catImage);
        unlink($smallDirectory.$catImage);

        //REMOVE USER
		$remove = $connector->query("DELETE FROM product_category WHERE productCatID = ?", array($productCatID));

        //REMOVE META DETAILS
        $remove = $connector->query("DELETE FROM meta_details WHERE productCatID = ?", array($productCatID));

	}

	//#################################################################
    // ADD TOUR
    //#################################################################
	function addTour($tour_title, $tour_intro, $image_title, $imageFile, $tour_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$tour_title	    = strip_tags($tour_title);
        $image_title    = strip_tags($image_title);
        $tour_url       = strip_tags($tour_url);

		//ADD PRODUCT
		$insert = $connector->query("INSERT INTO tours (tourTitle, tourIntro, tourImageFile, tourImageTitle, url, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?)",
									array($tour_title, $tour_intro, $imageFile, $image_title, $tour_url, $currentUser, $currentDate));

        //GET LAST INSERTED ID
        $result = $connector->query("SELECT * FROM tours ORDER BY tourID DESC", array());
        $row    = $connector->fetchArray($result);

        //RETURN ID
        return $row['tourID'];

	}

	//#################################################################
    // UPDATE TOUR
    //#################################################################
	function updateTour($tour_title, $tour_intro, $modifiedBy, $modifiedDate, $modifiedNumber, $tourID, $imageFile, $image_title, $tour_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$tour_title	    = strip_tags($tour_title);
        $image_title    = strip_tags($image_title);
        $tour_url       = strip_tags($tour_url);

		//UPDATE USER
		$update = $connector->query("UPDATE tours SET
									tourTitle = ?,
									tourIntro = ?,
                                    tourImageFile = ?,
                                    tourImageTitle = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE tourID = ?",
									array($tour_title, $tour_intro, $imageFile, $image_title, $tour_url, $modifiedBy, $modifiedDate, $modifiedNumber, $tourID));

	}

	//#################################################################
    // DELETE TOUR
    //#################################################################
	function deleteTour($tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //REMOVE TOUR GALLERY IMAGES
        $result = $connector->query("SELECT tourGalleryID FROM tour_gallery WHERE tourID = ? ", array($tourID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLE
            $tourGalleryID      = $row['tourGalleryID'];

            //GET GALLERY IMAGE FILE
            $result2    = $connector->query("SELECT galleryImageFile FROM tour_gallery_content WHERE tourGalleryID = ? ORDER BY sequence ASC", array($tourGalleryID));

            //SET GALLERY IMAGE FILE VARIABLE
            $galleryImageFile   = $row['galleryImageFile'];

            //DELETE IMAGES
            unlink($largeDirectory.$galleryImageFile);
            unlink($mediumDirectory.$galleryImageFile);
            unlink($smallDirectory.$galleryImageFile);

            //REMOVE TOUR GALLERY CONTENT
            $remove = $connector->query("DELETE FROM tour_gallery_content WHERE tourGalleryID = ?", array($tourGalleryID));
        }

        //REMOVE TOUR CONTENT IMAGES
        $result = $connector->query("SELECT imageFile FROM tour_content WHERE tourID = ? ", array($tourID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLE
            $imageFile      = $row['imageFile'];

            //DELETE IMAGES
            unlink($largeDirectory.$imageFile);
            unlink($mediumDirectory.$imageFile);
            unlink($smallDirectory.$imageFile);
        }

		//REMOVE TOUR
		$remove = $connector->query("DELETE FROM tours WHERE tourID = ?", array($tourID));

        //REMOVE TOUR CONTENT
        $remove = $connector->query("DELETE FROM tour_content WHERE tourID = ?", array($tourID));

        //REMOVE TOUR GALLERY
        $remove = $connector->query("DELETE FROM tour_gallery WHERE tourID = ?", array($tourID));

        //REMOVE META DETAILS
        $remove = $connector->query("DELETE FROM meta_details WHERE tourID = ?", array($tourID));

	}

	//#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($tourContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
		$row	= $connector->fetchArray($result);
		$imageFile		= $row['imageFile'];
		$documentFile	= $row['documentFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$imageFile);
		unlink($mediumDirectory.$imageFile);
		unlink($smallDirectory.$imageFile);

		//DELETE DOCUMENT
		unlink($docDirectory.$documentFile);

		//REMOVE USER
		$remove = $connector->query("DELETE FROM tour_content WHERE tourContentID = ?",array($tourContentID));

	}

    //#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($tourContentID, $tourGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ?", array($tourGalleryID));
		while($row	= $connector->fetchArray($result)){
            $galleryImageFile           = $row['galleryImageFile'];
            $tourGalleryContentID       = $row['tourGalleryContentID'];

    		//DELETE IMAGES
    		unlink($largeDirectory.$galleryImageFile);
    		unlink($mediumDirectory.$galleryImageFile);
    		unlink($smallDirectory.$galleryImageFile);

    		//REMOVE GALLERY IMAGE
    		$remove = $connector->query("DELETE FROM tour_gallery_content WHERE tourGalleryContentID = ?",array($tourGalleryContentID));
        }

        //REMOVE GALLERY ENTRIES
        $removeGallery = $connector->query("DELETE FROM tour_gallery WHERE tourGalleryID = ?",array($tourGalleryID));
        $removeEntry = $connector->query("DELETE FROM tour_content WHERE tourGalleryID = ?",array($tourGalleryID));

	}

    //#################################################################
    // DELETE GALLERY IMAGE
    //#################################################################
	function deleteGalleryImage($tourGalleryContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryContentID = ?", array($tourGalleryContentID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM tour_gallery_content WHERE tourGalleryContentID = ?",array($tourGalleryContentID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ?", array($category_name));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF TOUR IS ALREADY IN USE
    //#################################################################
	function addTourCheck($tour_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK TOUR
		$result = $connector->query("SELECT * FROM tours WHERE tourTitle = ?", array($tour_title));
		$total	= $connector->numResults($result);

		//IF TOUR HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF PRODUCT CATEGORY IS ALREADY IN USE
    //#################################################################
	function editCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ?", array($category_name));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF TOUR TITLE IS ALREADY IN USE
    //#################################################################
	function editTourCheck($tourID, $tour_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK TOUR
		$result = $connector->query("SELECT * FROM tours WHERE tourTitle = ? AND tourID != ?", array($tour_title, $tourID));
		$total	= $connector->numResults($result);

		//IF TOUR HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

	//#################################################################
    // ADD PRODUCT PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$image_title	= strip_tags($image_title);
		$doc_title		= strip_tags($doc_title);
		$video			= strip_tags($video);

		//GET SEQUENCE
		$result	= $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND deletedBy = ? ORDER BY sequence DESC", array($tourID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO tour_content (tourID, paragraphTitle, paragraph, imageFile, imageTitle, documentFile, documentTitle, videoUrl, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($tourID, $title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $tourContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$image_title	= strip_tags($image_title);
		$doc_title		= strip_tags($doc_title);
		$video			= strip_tags($video);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
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
        $result = $connector->query("SELECT * FROM tour_content WHERE tourContentID = ?", array($tourContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD PRODUCT CONTENT
		$update			= $connector->query("UPDATE tour_content SET
                                            paragraphTitle  = ?,
                                            paragraph       = ?,
                                            imageFile       = ?,
                                            imageTitle      = ?,
                                            documentFile	= ?,
                                            documentTitle	= ?,
                                            videoUrl        = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE tourContentID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $modifiedNumber, $currentDate, $tourContentID));

	}

    //#################################################################
    // UPDATE TOUR GALLERY INFO
    //#################################################################
	function updateTourGalleryInfo($tourGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM tour_gallery WHERE tourGalleryID = ?", array($tourGalleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//UPDATE PRODUCT CONTENT
		$update			= $connector->query("UPDATE tour_gallery SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE tourGalleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $tourGalleryID));

	}

    //#################################################################
    // SET tourGalleryID AND RETURN IT
    //#################################################################
	function setTourGalleryID($tourID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//ADD TourID INTO tour_gallery
		$insert = $connector->query("INSERT INTO  tour_gallery (tourID, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($tourID, $currentUser, $currentDate));

        //GET tourGalleryID
        $result = $connector->query("SELECT tourGalleryID FROM tour_gallery WHERE tourID = ? AND createdBy = ? AND createdDate = ? AND deletedBy = ?", array($tourID, $currentUser, $currentDate, 0));
        $row    = $connector->fetchArray($result);

        //RETURN tourGalleryID
        return $row['tourGalleryID'];;
	}

    //#################################################################
    // ADD tourGalleryID INTO tour_content
    //#################################################################
	function addTourGalleryIDIntoTourContent($tourID, $tourGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET SEQUENCE
        $result = $connector->query("SELECT * FROM tour_content WHERE tourID = ? AND deletedBy = ? ORDER BY sequence DESC LIMIT 0,1", array($tourID, 0));
        $row    = $connector->fetchArray($result);
        $sequence   = $row['sequence']+1;

        //ADD tourGalleryID INTO tour_content
        $insert = $connector->query("INSERT INTO tour_content (tourID, tourGalleryID, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?)",
									array($tourID, $tourGalleryID, $currentUser, $currentDate, $sequence));
	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($tourGalleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ? ORDER BY sequence DESC", array($tourGalleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD tourGalleryID INTO tour_gallery_content
		$insert = $connector->query("INSERT INTO tour_gallery_content (tourGalleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($tourGalleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($tourGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryID = ? ORDER BY tourGalleryContentID ASC", array($tourGalleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $tourGalleryContentID       = $row['tourGalleryContentID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$tourGalleryContentID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$tourGalleryContentID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                $this->deleteGalleryImage($tourGalleryContentID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM tour_gallery_content WHERE tourGalleryContentID = ? AND galleryImageTitle = ?", array($tourGalleryContentID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE tour_gallery_content SET
                                                galleryImageTitle = ?
                                                WHERE tourGalleryContentID = ?",
                                                array($updateImageTitle, $tourGalleryContentID));

                    //SET THAT CONTENT HAS BEEN UPDATED
                    $updatedGalleryImages = 1;
                }

            }

        }

        //RETURN RESULT
        return $updatedGalleryImages;
    }

}

//DEFINE CLASS
$toursManager = new toursManager();

//#################################################################
// ADD TOUR
//#################################################################
if(isset($_POST['add_tour'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$tour_title 		= $_POST['tour-title'];
	$tour_intro	        = $_POST['paragraph'];
    $image_title	    = $_POST['image-title'];

	//HONEY POTS
	$tour_paragraph	    = $_POST['tour-paragraph'];
    $image_type		    = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $tour_title         = $userLogin->specialCharactersToHTMLEntity($tour_title);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($tour_title, 'Tour Title', 2, 100);
	//$v->validateText($tour_intro, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($tour_paragraph == '' && $image_type == ''){

			//CHECK IF PRODUCT IS ALREADY IN USE
			$tour_used = $toursManager->addTourCheck($tour_title);
			if($tour_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //CREATE TOUR URL
        		$tour_url = str_replace("'", "", $tour_title);
        		$tour_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($tour_url));
        		$tour_url = str_replace(' ', '-', $tour_url).'/';

                //CHECK IF TOUR URL EXISTS
                $tour_url = $toursManager->checkTourURLExists($tour_url, '');

				//INSERT PRODUCT INTO DATABASE
				$tourID = $toursManager->addTour($tour_title, $tour_intro, $image_title, $imageFile, $tour_url);

                //GET META DETAILS
                $keywords		= $toursManager->getMetaKeyword($tourID);
                $description	= $toursManager->getMetaDescription($tourID);

                //UPDATE META DETAILS
                $toursManager->updateMetaDetails($keywords, $description, $tourID);

                //ADD INFORMATION INTO SEARCH INDEX
                $toursManager->addTourSearchIndex($tourID, $tour_title, $keywords, $tour_intro);

                //REDIRECT USER
            	if($_FILES[$inputField]["tmp_name"] != ""){
                    header("Location: ".$cms_root."tours-manager/crop-image-tour.php?tourID=".$tourID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
                    exit;
                }else{
                    header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=2");
                    exit;
                }

			}
			else{

				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Tour Title</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT TOUR
//#################################################################
if(isset($_POST['edit_tour'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$tourID				    = $_POST['tourID'];
	$tour_title 		    = $_POST['tour-title'];
	$tour_intro		        = $_POST['paragraph'];
    $oldImage               = $_POST['oldImage'];
    $image_title            = $_POST['image-title'];

	$modifiedDate			= $_POST['modifiedDate'];
	$modifiedBy				= $_SESSION['cmsUser'];
	$modifiedNumber			= $_POST['modifiedNumber'];

	//HONEY POTS
	$tour_paragraph         = $_POST['tour-paragraph'];
    $image_type             = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1920;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $tour_title         = $userLogin->specialCharactersToHTMLEntity($tour_title);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($tour_title, 'Tour Title', 2, 100);
	//$v->validateText($tour_intro, 'Description', 10);
    $v->validateString($image_title, 'Image Title',3, 150);

    //IF AN IMAGE HAS BEEN SUPPLIED
    if($_FILES[$inputField]["tmp_name"] != ""){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($tour_paragraph == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($toursManager->checkTourChanges($tour_title, $tour_intro, $tourID, $image_title) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

				//CHECK TITLE IS USED
				$tour_used = $toursManager->editTourCheck($tourID, $tour_title);
				if($tour_used == 'unused'){

                    //IF AN IMAGE HAS BEEN ADDED
        			if($_FILES[$inputField]["tmp_name"] != ""){
        				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

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

                    //GENERATE TOUR URL
                    $tour_url = str_replace("'", "", $tour_title);
                    $tour_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($tour_url));
            		$tour_url = str_replace(' ', '-', $tour_url).'/';

                    //CHECK IF TOUR URL EXISTS
                    $tour_url = $toursManager->checkTourURLExists($tour_url, $tourID);

					//UPDATE TOUR IN DATABASE
					$toursManager->updateTour($tour_title, $tour_intro, $modifiedBy, $modifiedDate, $modifiedNumber, $tourID, $imageFile, $image_title, $tour_url);

                    //GET META DETAILS
        			$keywords		= $toursManager->getMetaKeyword($tourID);
        			$description	= $toursManager->getMetaDescription($tourID);

        			//UPDATE META DETAILS
        			$toursManager->updateMetaDetails($keywords, $description, $tourID);

                    //ADD INFORMATION INTO SEARCH INDEX
                    $toursManager->addTourSearchIndex($tourID, $tour_title, $keywords, $tour_intro);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."tours-manager/crop-image-tour.php?tourID=".$tourID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
                		exit;
                    }else{
                        header("Location: ".$cms_root."tours-manager/index.php?message=4");
                		exit;
                    }

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Tour Title</b> you supplied is already in use. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."tours-manager/");
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
//DELETE TOUR
//#################################################################
if(isset($_POST['delete_tour'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$tourID	= $_POST['tourID'];

    //DELETE TOUR
    $toursManager->deleteTour($tourID);

    //REMOVE TOUR FROM SEARCH INDEX
    $toursManager->removeTourSearchIndex($tourID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."tours-manager/index.php?message=6");
    exit;
}

//#################################################################
// ADD PRODUCT PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$tourID         = $_POST['tourID'];
	$title			= $_POST['paragraph-title'];
	$paragraph 		= $_POST['paragraph'];
	$video			= $_POST['youtube-vimeo-video'];
	$image_title	= $_POST['image-title'];
	$doc_title		= $_POST['doc-title'];

	//HONEY POTS
	$paragraph_type	= $_POST['paragraph-type'];
	$image_type		= $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

	//DOCUMENT PROPERTIES
	$docField				= 'doc-file';
	$docfileDirectory		= '../../cms-documents/';

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);
    $doc_title      = $userLogin->specialCharactersToHTMLEntity($doc_title);

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

	//IF A DOCUMENT HAS BEEN ADDED
	if($_FILES[$docField]["tmp_name"] != ""){
		$v->validateString($doc_title, 'Document Title',3, 150);
		$v->validateDocument($docField, 'Document File');
	}

	//IF VIDEO HAS BEEN SUPPLIED
	if($video != ''){
		$v->validateVideoLink($video, 'YouTube/Vimeo Video');
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

			//IF A DOCUMENT HAS BEEN ADDED
			if($_FILES[$docField]["tmp_name"] != ""){
				$docFile		= $fileUploader->uploadDocuments($docField, $docfileDirectory, $doc_title);
			}

			//REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT PARAGRAPH INTO DATABASE
			$toursManager->addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $tourID);

			//GET META DETAILS
			$keywords		= $toursManager->getMetaKeyword($tourID);
			$description	= $toursManager->getMetaDescription($tourID);

			//UPDATE META DETAILS
			$toursManager->updateMetaDetails($keywords, $description, $tourID);

            //GET TOUR INFO
            $tourTitle  = $toursManager->getTourInfo($tourID, 'tourTitle');
            $tourIntro  = $toursManager->getTourInfo($tourID, 'tourIntro');

            //ADD INFORMATION INTO SEARCH INDEX
            $toursManager->addTourSearchIndex($tourID, $tourTitle, $keywords, $tourIntro);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."tours-manager/crop-image.php?tourID=".$tourID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=11");
        		exit;
			}
			//REDIRECT TO PRODUCT
			else{
				header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=11");
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
// EDIT PARAGRAPH
//#################################################################
if(isset($_POST['edit_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$tourID            = $_POST['tourID'];
    $tourContentID     = $_POST['tourContentID'];
	$title             = $_POST['paragraph-title'];
	$paragraph         = $_POST['paragraph'];
	$video             = $_POST['youtube-vimeo-video'];
	$image_title       = $_POST['image-title'];
	$doc_title		   = $_POST['doc-title'];
    $removeImage       = $_POST['removeImage'];
    $removeDocument    = $_POST['removeDocument'];
    $removeVideo       = $_POST['removeVideo'];
    $oldImage          = $_POST['oldImage'];
    $oldDocument       = $_POST['oldDocument'];

	//HONEY POTS
	$paragraph_type    = $_POST['paragraph-type'];
	$image_type        = $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

	//DOCUMENT PROPERTIES
	$docField				= 'doc-file';
	$docfileDirectory		= '../../cms-documents/';

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);
    $doc_title      = $userLogin->specialCharactersToHTMLEntity($doc_title);

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

	//IF A DOCUMENT HAS BEEN ADDED
	if($_FILES[$docField]["tmp_name"] != ""){
		$v->validateString($doc_title, 'Document Title',3, 150);
		$v->validateDocument($docField, 'Document File');
	}

	//IF VIDEO HAS BEEN SUPPLIED
	if($video != ''){
		$v->validateVideoLink($video, 'YouTube/Vimeo Video');
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
                    $image_title    = $toursManager->getTourContentInfo($tourContentID, 'imageTitle');
                }
            }

			//IF A DOCUMENT HAS BEEN ADDED
			if($_FILES[$docField]["tmp_name"] != ""){
				$docFile		= $fileUploader->uploadDocuments($docField, $docfileDirectory, $doc_title);
			}
            //CHECK IF DOCUMENT NEEDS TO BE REMOVED
            elseif($removeDocument == 1){
                $docFile        = '';
                $doc_title      = '';

                //REMOVE DOCUMENT
                unlink($docfileDirectory.$oldDocument);
            }
            //IF NO NEW DOCUMENT HAS BEEN UPLOADED
            else{
                $docFile        = $oldDocument;
                $doc_title      = $toursManager->getTourContentInfo($tourContentID, 'documentTitle');
            }

            //CHECK IF VIDEO NEEDS TO BE REMOVED
            if($removeVideo == 1){
                $video = '';
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//UPDATE TOUR IN DATABASE
			$toursManager->updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $tourContentID);

			//GET META DETAILS
			$keywords		= $toursManager->getMetaKeyword($tourID);
			$description	= $toursManager->getMetaDescription($tourID);

			//UPDATE META DETAILS
			$toursManager->updateMetaDetails($keywords, $description, $tourID);

            //GET PRODUCT INFO
            $tourTitle  = $toursManager->getTourInfo($tourID, 'tourTitle');
            $tourIntro  = $toursManager->getTourInfo($tourID, 'tourIntro');

            //ADD INFORMATION INTO SEARCH INDEX
            $toursManager->addTourSearchIndex($tourID, $tourTitle, $keywords, $tourIntro);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."tours-manager/crop-image.php?tourID=".$tourID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=12");
        		exit;
			}
			//REDIRECT TO PRODUCT
			else{
				header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=12");
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
	$tourContentID	= $_POST['tourContentID'];
	$tourID			= $_POST['tourID'];

    //SET PARAGRPAH AS REMOVED IN DATABASE
    $toursManager->deleteParagraph($tourContentID);

    //GET META DETAILS
    $keywords		= $toursManager->getMetaKeyword($tourID);

    //GET PRODUCT INFO
    $tourTitle  = $toursManager->getTourInfo($tourID, 'tourTitle');
    $tourIntro  = $toursManager->getTourInfo($tourID, 'tourIntro');

    //ADD INFORMATION INTO SEARCH INDEX
    $toursManager->addTourSearchIndex($tourID, $tourTitle, $keywords, $tourIntro);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=13");
    exit;
}

//#################################################################
//DELETE GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$tourContentID   = $_POST['tourContentID'];
	$tourGalleryID   = $_POST['tourGalleryID'];
    $tourID          = $_POST['tourID'];

    //REMOVE GALLERY FROM DATABASE
    $toursManager->deleteGallery($tourContentID, $tourGalleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=17");
    exit;
}

//#################################################################
//ADD GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $tourID         = $_POST['tourID'];
    $value          = json_decode(stripslashes($_POST['value']));

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

    //CHECK THAT VALUE CANT BE EMPTY
    if(!empty($value)){

        //CHECK IF ALL CONDITONS HAVE BEEN MET
        if($galleryName == ''){

            //GET tourGalleryID
            $tourGalleryID  = $toursManager->setTourGalleryID($tourID);

            //ADD tourGalleryID INTO tour_content
            $toursManager->addTourGalleryIDIntoTourContent($tourID, $tourGalleryID);

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
                        $toursManager->addGalleryImages($tourGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $hasImages = 1;

                    }

                    $count++;
                }
            }

            //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
            if($hasImages == 1){
                header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=15");
        		exit;
            }else{
                header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID);
        		exit;
            }
        }
    }else{
        $error_message  = 'There was an error creating your galley!';
        $errors         = 'You have to choose at least one image in order to create the gallery!';
    }
}

//#################################################################
//EDIT GALLERY
//#################################################################
if(isset($_POST['edit_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $tourID             = $_POST['tourID'];
    $tourGalleryID      = $_POST['tourGalleryID'];
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
        $updatedGalleryImages = $toursManager->updateRemoveGalleryImages($tourGalleryID);

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
                        $toursManager->addGalleryImages($tourGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $toursManager->updateTourGalleryInfo($tourGalleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=16");
    		exit;
        }else{
            header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID);
    		exit;
        }
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
if($tourRatio == 1){
    $newWidth		= 1200;
    $newHeight		= 647;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($normalRatio == 1){
    $newWidth		= 400;
    $newHeight		= 265;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//SET VARIABLES FOR STATS INCLUDE
$toursWidth         = 2000;
$toursHeight        = 1078;
$paragraphWidth     = 400;
$paragraphHeight    = 265;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$tourID             = $_POST['tourID'];
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
	header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=".$message);
    exit;
}

//CROP TOUR IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-tour'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$tourID			    = $_POST['tourID'];
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
	header("Location: ".$cms_root."tours-manager/manage-tour.php?tourID=".$tourID."&message=".$message);
    exit;
}
###################################################################
?>
