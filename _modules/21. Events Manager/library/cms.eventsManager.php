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

class eventManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Event has successfully been added.'; break;
			case 4: $displayMessage = 'The selected Event has successfully been updated.'; break;
			case 6: $displayMessage = 'The selected Event has successfully been removed.'; break;
			case 9: $displayMessage = 'The selected Event has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Event has successfully been re-activated.'; break;
			case 11: $displayMessage = 'A new Paragraph has successfully been added.'; break;
			case 12: $displayMessage = 'The selected Paragraph has successfully been updated.'; break;
			case 13: $displayMessage = 'The selected Paragraph has successfully been removed.'; break;
            case 14: $displayMessage = 'The selected Event has successfully been permanently deleted.'; break;
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
	//CHECK EVENT URL EXISTS
	//#################################################################
	function checkEventURLExists($url, $eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED
        $currentURL = $this->getEventInfo($eventID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM events WHERE url = ? LIMIT 0,1", array($url));
        $total  = $connector->numResults($result);

        //IF RESULT FOUND
        if($total != 0){
            //CHECK IF URL IS THE SAME
            if($currentURL == $url){
                //RETURN URL
                return $url.'/';
            }
            //URL NO THE SAME
            else{

                //CREATE URL
                while($proceed == 1){
                    //CREATE NEW URL
                    $newURL = str_replace('/', '', $url).'-'.$count.'/';

                    //CHECK IF NEW URL IS FINE
                    $result2    = $connector->query("SELECT url FROM events WHERE url = ? LIMIT 0,1", array($newURL));
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
            return $url.'/';
        }
	}

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM event_content WHERE eventID = ? AND deletedBy = ? ORDER BY sequence ASC", array($eventID, 0));
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
	function getMetaDescription($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM event_content WHERE eventID = ? AND deletedBy = ? ORDER BY sequence ASC", array($eventID, 0));
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
	function updateMetaDetails($keywords, $description, $eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE eventID = ?", array($eventID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (eventID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($eventID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE eventID = ?",
												array($keywords, $description, $eventID));
		}
	}

    //#################################################################
	// ADD EVENT INTO SEARCH INDEX
	//#################################################################
	function addEventSearchIndex($eventID, $event_title, $keywords){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//GET INDEX INFO
		$result	= $connector->query("SELECT * FROM search_index WHERE eventID = ?", array($eventID));
		$row	= $connector->fetchArray($result);
		$total	= $connector->numResults($result);

		//CHECK IF EVENT IS ALREADY INDEX
		if($total == 0){
			//INSERT EVENT SEARCH INDEX
			$insert	= $connector->query("INSERT INTO search_index (title, keywords, eventID)
										VALUES(?, ?, ?)"
										, array($event_title, $keywords, $eventID));
		}else{
			//UPDATE EVENT SEARCH INDEX
			$update	= $connector->query("UPDATE search_index SET
										title			= ?,
										keywords		= ?
										WHERE eventID = ?"
										, array($event_title, $keywords, $eventID));
		}

	}

    //#################################################################
    // GET PARAGRAPH INFORMATION
    //#################################################################
	function getParagraphInfo($eventContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET EVENT IMAGE
    //#################################################################
	function getEventImage($eventID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE eventID = ?", array($eventID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['eventImageFile'];
		$imageTitle	= $row['eventImageTitle'];

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
	function getGalleryInfo($eventGalleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM event_gallery WHERE eventGalleryID = ?", array($eventGalleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET EVENT INFORMATION
    //#################################################################
	function getEventInfo($eventID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE eventID = ?", array($eventID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET EVENT CONTENT INFORMATION
    //#################################################################
	function getEventContentInfo($eventContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET EVENT CONTENT INFO
		$result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET EVENT CONTENT VIDEO
    //#################################################################
	function getEventContentVideo($eventContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET EVENT CONTENT INFO
		$result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
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
    // GET EVENT CONTENT DOCUMENT
    //#################################################################
	function getEventContentDocument($eventContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET EVENT CONTENT INFO
		$result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
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
    // GET EVENT CONTENT IMAGE
    //#################################################################
	function getEventContentImage($eventContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET EVENT CONTENT INFO
		$result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
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
    // GET EVENT GALLERY IMAGES
    //#################################################################
	function getEventGalleryImages($eventGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET EVENT GALLERY INFO
		$result = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ? ORDER BY eventGalleryContentID ASC", array($eventGalleryID));
		while($row	= $connector->fetchArray($result)){
            $eventGalleryContentID      = $row['eventGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade" id="img'.$eventGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="remove_gallery_image">
                    <input type="checkbox" name="remove_gallery_image_'.$eventGalleryContentID.'" value="1" />
                    <div class="remove_gallery_image_text">Remove Image</div>
                </div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$eventGalleryContentID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET EVENT GALLERY IMAGES FOR SEQUENCING
    //#################################################################
	function getEventGalleryImagesSequencing($eventGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ? ORDER BY sequence ASC", array($eventGalleryID));
		while($row	= $connector->fetchArray($result)){
            $eventGalleryContentID      = $row['eventGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade sortable-content" id="'.$eventGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title: <span class="normal-text">'.$galleryImageTitle.'</span></div></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // GET EVENT DATE/TIME INFORMATION
    //#################################################################
	function getEventDateTimeInfo($eventID, $field, $dbField){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE eventID = ?", array($eventID));
		$row	= $connector->fetchArray($result);
		$publishDate	= $row[$dbField];

		//GET DATE
		if($field == 'date'){
			return date("Y-m-d" ,strtotime($publishDate));
		}
		//GET TIME
		elseif($field == 'time'){
			return date("H:i" ,strtotime($publishDate));
		}
	}

	//#################################################################
    // CHECK IF EVENT IS IN DATABASE
    //#################################################################
	function checkEventDatabase($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET EVENT TOTAL
		$result = $connector->query("SELECT * FROM events WHERE eventID = ?", array($eventID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF EVENT GALLERY IS IN DATABASE
    //#################################################################
	function checkEventGalleryDatabase($eventGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM event_gallery WHERE eventGalleryID = ? ", array($eventGalleryID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF EVENT CONTENT IS IN DATABASE
    //#################################################################
	function checkEventContentDatabase($eventID, $eventContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET EVENT CONTENT TOTAL
		$result = $connector->query("SELECT * FROM event_content WHERE eventID = ? AND eventContentID = ?", array($eventID, $eventContentID));
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
    // GET TOTAL EVENTS
    //#################################################################
	function getTotalEvents(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET PENDING EVENTS
    //#################################################################
	function getPendingEvents(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
        $currentDate = date('Y-m-d H:i:s');

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE startDate > ? AND deletedBy = ?", array($currentDate, '0'));
        $total  = $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL ACTIVE EVENTS
    //#################################################################
	function getActiveEvents(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLES
		$currentDate = date("Y-m-d H:i:s");

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE startDate <= ? AND endDate >= ? AND deletedBy = ?", array($currentDate, $currentDate, '0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL EXPIRED EVENTS
    //#################################################################
	function getTotalExpiredEvents(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $currentDate = date("Y-m-d H:i:s");

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE endDate < ? AND deletedBy != ?", array($currentDate, '0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL REMOVED EVENTS
    //#################################################################
	function getTotalRemovedEvents(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM events WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // EVENT ARCHITECTURE
    //#################################################################
	function eventArchitecture($limit, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL EVENTS
		$result = $connector->query("SELECT * FROM events WHERE deletedBy = ? ORDER BY startDate DESC LIMIT 0, $limit", array('0',));
		$eventTotal = $connector->numResults($result);

		//IF EVENTS ARE AVAILABLE
		if($eventTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$status			= '';
				$status_bg		= '';
                $status_color   = '';
				$date			= '';
				$currentDate	= date('Y-m-d H:i:s');
				$eventID		= $row['eventID'];
				$eventTitle	    = $this->HTMLEntityToSpecialCharacters($row['eventTitle']);
				$startDate	    = $row['startDate'];
                $endDate        = $row['endDate'];

				//FORMAT PUBLISH DATE
				$sDate = date("j F Y - H:i", strtotime($startDate));
                $eDate = date("j F Y - H:i", strtotime($endDate));

                //PENDING EVENT
                if($startDate > $currentDate){
                    $status		= '<span class="unpublished-post-text">(Pending)</span>';
                    $status_bg	= 'class="unpublished-post"';
                    $status_color = 'class="partial-account"';
                }
                //ACTIVE EVENT
                elseif($endDate > $currentDate && $startDate < $currentDate){
                    $status	= '(Published)';
                    $status_color = 'class="active-account"';
                }
                //EXPIRED EVENT
                else{
                    $status		= '<span class="empty-category-text">(Expired)</span>';
					$status_bg	='class="empty-category"';
                    $status_color = 'class="removed-account"';
                }

				//GENERATE OUPUT
				$txt.= '<tr>
					<td '.$status_color.'></td>
					<td '.$status_bg.'>'.$eventTitle.' '.$status.'</td>
					<td '.$status_bg.' align="center">'.$sDate.'</td>
                    <td '.$status_bg.' align="center">'.$eDate.'</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'events-manager/manage-event.php?eventID='.$eventID.'" title="Manage">Manage</a>
					</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'events-manager/edit-event.php?eventID='.$eventID.'" title="Modify">Modify</a>
					</td>
					<td '.$status_bg.' align="center">';

					$txt.='<form name="delete_event'.$eventID.'">
							<input type="hidden" name="delete_event" value="1">
							<input type="hidden" name="eventID" value="'.$eventID.'">
							<a href="javascript:deleteEvent('.$eventID.')" title="Remove">Remove</a>
						</form>';

					$txt.= '</td>
				  </tr>';
			}
		}
		//IF NO EVENTS ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="7">There are currently no Events available. <a href="'.$cms_root.'events-manager/add-event.php" title="Add Event">Please add an event here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // EVENT ARCHITECTURE (REMOVED)
    //#################################################################
	function eventArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM events WHERE deletedBy != ? ORDER BY eventTitle ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$eventID		= $row['eventID'];
			$eventTitle	    = $row['eventTitle'];
			$startDate      = $row['startDate'];
            $endDate        = $row['endDate'];

			//FORMAT PUBLISH DATE
			$startDate	= date("j F Y - H:i", strtotime($startDate));
            $endDate	= date("j F Y - H:i", strtotime($endDate));

			//GENERATE OUPUT
			$txt.= '<tr>
						<td class="removed-account"></td>
						<td>'.$eventTitle.'</td>
						<td align="center">'.$startDate.'</td>
                        <td align="center">'.$endDate.'</td>
                        <td align="center">
                            <form name="delete_permanently_event'.$eventID.'">
								<input type="hidden" name="delete_permanently_event" value="1">
								<input type="hidden" name="eventID" value="'.$eventID.'">
								<a href="javascript:deletePermanentlyEvent('.$eventID.')" title="Delete">Delete</a>
							</form>
                        </td>
						<td align="center">
							<form name="recover_event'.$eventID.'">
								<input type="hidden" name="recover_event" value="1">
								<input type="hidden" name="eventID" value="'.$eventID.'">
								<a href="javascript:recoverEvent('.$eventID.')" title="Recover">Recover</a>
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
    function removeEmptyGallery($eventGalleryID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //DELETE FROM event_gallery
        $deleteGallery = $connector->query("DELETE FROM event_gallery WHERE eventGalleryID = ?", array($eventGalleryID));

        //DELETE FROM event_content
        $deleteGalleryContent = $connector->query("DELETE FROM event_content WHERE eventGalleryID = ?", array($eventGalleryID));
    }

	//#################################################################
    // EVENT CONTENT ARCHITECTURE
    //#################################################################
	function eventContentArchitecture($cms_root, $web_root, $eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL EVENT CONTENT
		$result = $connector->query("SELECT * FROM event_content WHERE deletedBy = ?  AND eventID = ? ORDER BY sequence ASC", array('0', $eventID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$eventContentID	    = $row['eventContentID'];
				$paragraphTitle		= $row['paragraphTitle'];
				$paragraph			= $row['paragraph'];
				$imageFile			= $row['imageFile'];
				$imageTitle			= $row['imageTitle'];
				$documentFile		= $row['documentFile'];
				$documentTitle		= $row['documentTitle'];
				$videoUrl			= $row['videoUrl'];
				$eventGalleryID     = $row['eventGalleryID'];
                $sequence           = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				if($eventGalleryID != 0){

                    //CHECK IF IMAGES IN GALLERY
                    $result4        = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ?", array($eventGalleryID));
                    $totalImages    = $connector->numResults($result4);

                    //REMOVE GALLERY
                    if($totalImages == 0){
                        $this->removeEmptyGallery($eventGalleryID);
                        $removedGallery = 1;

                    }else{
    					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$eventContentID.'">';

                            //GET TOTAL GALLERY IMAGES
                            $result2    = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($eventGalleryID, 0));
                            $totalGalleryImage  = $connector->numResults($result2);

                            //IF MORE THAN 6 GALLERY IMAGES
                            if($totalGalleryImage > 6){
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC LIMIT 0,5", array($eventGalleryID, 0));
                            }else{
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($eventGalleryID, 0));
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

                                $txt.= '<a href="'.$cms_root.'events-manager/edit-gallery.php?eventID='.$eventID.'&eventGalleryID='.$eventGalleryID.'" title="View all Gallery Images">
                                    <div class="paragraph-image-indicator">
                                        <div class="paragraph-image-more-indicator">+'.$extraImages.'</div>
                                    </div>
                                </a>';
                            }

                            $txt.= '<div class="clear"></div>
                            <div class="module-manage-content-links">
    							<form name="delete_gallery'.$eventContentID.'">
    								<input type="hidden" name="delete_gallery" value="1">
    								<input type="hidden" name="eventContentID" value="'.$eventContentID.'">
    								<input type="hidden" name="eventGalleryID" value="'.$eventGalleryID.'">
                                    <input type="hidden" name="eventID" value="'.$eventID.'">
    								<a href="javascript:deleteGallery('.$eventContentID.')" title="Remove Gallery">Remove Gallery</a>
    							</form>
    							<a href="'.$cms_root.'events-manager/edit-gallery.php?eventID='.$eventID.'&eventGalleryID='.$eventGalleryID.'" title="Edit Gallery">Edit Gallery</a>
                                <a href="'.$cms_root.'events-manager/sequence-gallery.php?eventID='.$eventID.'&eventGalleryID='.$eventGalleryID.'" title="Sequence Gallery">Sequence Gallery</a>
    							<div class="clear"></div>
    							</div>
                        </div>';
                    }
				}else{
					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$eventContentID.'">';

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
							<form name="delete_paragraph'.$eventContentID.'">
								<input type="hidden" name="delete_paragraph" value="1">
								<input type="hidden" name="eventContentID" value="'.$eventContentID.'">
								<input type="hidden" name="eventID" value="'.$eventID.'">
								<a href="javascript:deleteParagraph('.$eventContentID.')" title="Remove Paragraph">Remove Paragraph</a>
							</form>
							<a href="'.$cms_root.'events-manager/edit-paragraph.php?eventContentID='.$eventContentID.'&eventID='.$eventID.'" title="Edit Paragraph">Edit Paragraph</a>
							<div class="clear"></div>
							</div>
                    </div>';
				}
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'events-manager/add-paragraph.php?eventID='.$eventID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';
		}

        //IF GALLERY(S) REMOVED RELOAD PAGE
        if($removedGallery == 1){
            header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=17");
    		exit;
        }

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF ANY EVENT HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedEvents(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED EVENTS
		$result = $connector->query("SELECT * FROM events WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF EVENT INFO HAS BEEN CHANGED
    //#################################################################
	function checkEventChanges($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time, $image_title, $eventID){

		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE SPACES IN TIMES
		$startTime  = str_replace(' ', '', $start_time).':00';
        $endTime    = str_replace(' ', '', $end_time).':00';

		//CREATE DATES
		$startDate  = date("Y-m-d H:i:s", strtotime($start_date.' '.$startTime));
        $endDate    = date("Y-m-d H:i:s", strtotime($end_date.' '.$endTime));

		//COMPARE EVENT INFO
		$result = $connector->query("SELECT * FROM events WHERE eventTitle = ? AND eventImageTitle = ? AND startDate = ? AND endDate = ? AND venue = ? AND eventID = ? ", array($event_title, $image_title, $startDate, $endDate, $event_venue, $eventID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD EVENT
    //#################################################################
	function addEvent($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time, $image_title, $imageFile, $event_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$event_title	= strip_tags($event_title);
		$event_venue	= strip_tags($event_venue);
		$start_date		= strip_tags($start_date);
		$start_time		= strip_tags($start_time);
        $end_date       = strip_tags($end_date);
        $end_time       = strip_tags($end_time);
        $image_title    = strip_tags($image_title);
        $event_url      = strip_tags($event_url);

		//REMOVE SPACES IN TIMES
		$start_time = str_replace(' ', '', $start_time).':00';
        $end_time   = str_replace(' ', '', $end_time).':00';

		//CREATE DATES
		$startDate  = date("Y-m-d H:i:s", strtotime($start_date.' '.$start_time));
        $endDate    = date("Y-m-d H:i:s", strtotime($end_date.' '.$end_time));

		//ADD EVENT
		$insert = $connector->query("INSERT INTO events (eventTitle, eventImageFile, eventImageTitle, startDate, endDate, venue, url, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($event_title, $imageFile, $image_title, $startDate, $endDate, $event_venue, $event_url, $currentUser, $currentDate));

        //GET LAST INSERTED ID
        $result = $connector->query("SELECT * FROM events ORDER BY eventID DESC", array());
        $row    = $connector->fetchArray($result);

        //RETURN ID
        return $row['eventID'];

	}

	//#################################################################
	//OVERWRITE EVENT
	//#################################################################
	function overwriteEvent($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$event_title	= strip_tags($event_title);
		$event_venue	= strip_tags($event_venue);
		$start_date		= strip_tags($start_date);
		$start_time		= strip_tags($start_time);
        $end_date		= strip_tags($end_date);
		$end_time		= strip_tags($end_time);

		//REMOVE SPACES IN TIMEs
		$startTime  = str_replace(' ', '', $start_time).':00';
        $endTime    = str_replace(' ', '', $end_time).':00';

		//CREATE DATES
		$startDate = date("Y-m-d H:i:s", strtotime($start_date.' '.$startTime));
        $endDate   = date("Y-m-d H:i:s", strtotime($end_date.' '.$endTime));

		//UPDATE USER
		$update = $connector->query("UPDATE events SET
									startDate = ?,
									endDate = ?,
                                    venue = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE eventTitle = ?",
									array($startDate, $endDate, $event_venue, '0', '0000-00-00 00:00:00', $event_title));

        //GET EVENT ID
        $result = $connector->query('SELECT eventID FROM events WHERE eventTitle = ? LIMIT 0,1', array($event_title));
        $row    = $connector->fetchArray($result);

        //RETURN EVENT ID
        return $row['eventID'];

	}

	//#################################################################
    // UPDATE EVENT
    //#################################################################
	function updateEvent($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time, $imageFile, $image_title, $event_url, $eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$event_title	= strip_tags($event_title);
		$event_venue	= strip_tags($event_venue);
		$start_date		= strip_tags($start_date);
		$start_time		= strip_tags($start_time);
        $end_date		= strip_tags($end_date);
        $end_time       = strip_tags($end_time);
        $image_title    = strip_tags($image_title);
        $event_url      = strip_tags($event_url);

		//REMOVE SPACES IN TIMES
		$startTime  = str_replace(' ', '', $start_time).':00';
        $endTime    = str_replace(' ', '', $end_time).':00';

		//CREATE DATE
		$startDate  = date("Y-m-d H:i:s", strtotime($start_date.' '.$startTime));
        $endDate    = date("Y-m-d H:i:s", strtotime($end_date.' '.$endTime));

		//UPDATE EVENT
		$update = $connector->query("UPDATE events SET
									eventTitle = ?,
									eventImageFile = ?,
                                    eventImageTitle = ?,
                                    startDate = ?,
									endDate = ?,
                                    venue = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE eventID = ?",
									array($event_title, $imageFile, $image_title, $startDate, $endDate, $event_venue, $event_url, $modifiedBy, $modifiedDate, $modifiedNumber, $eventID));

	}

	//#################################################################
    // DELETE EVENT
    //#################################################################
	function deleteEvent($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE events SET
									deletedBy = ?,
									deletedDate = ?
									WHERE eventID = ?",
									array($currentUser, $currentDate, $eventID));

	}

	//#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($eventContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
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
		$remove = $connector->query("DELETE FROM event_content WHERE eventContentID = ?",array($eventContentID));

	}

    //#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($eventContentID, $eventGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE GALLERY IMAGES
		$result	= $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ?", array($eventGalleryID));
		while($row	= $connector->fetchArray($result)){
            $galleryImageFile           = $row['galleryImageFile'];
            $eventGalleryContentID   = $row['eventGalleryContentID'];

    		//DELETE IMAGES
    		unlink($largeDirectory.$galleryImageFile);
    		unlink($mediumDirectory.$galleryImageFile);
    		unlink($smallDirectory.$galleryImageFile);

    		//REMOVE GALLERY IMAGE
    		$remove = $connector->query("DELETE FROM event_gallery_content WHERE eventGalleryContentID = ?",array($eventGalleryContentID));
        }

        //REMOVE GALLERY ENTRIES
        $removeGallery = $connector->query("DELETE FROM event_gallery WHERE eventGalleryID = ?",array($eventGalleryID));
        $removeEntry = $connector->query("DELETE FROM event_content WHERE eventContentID = ?",array($eventContentID));

	}

    //#################################################################
    // DELETE GALLERY IMAGE
    //#################################################################
	function deleteGalleryImage($eventGalleryContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryContentID = ?", array($eventGalleryContentID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM event_gallery_content WHERE eventGalleryContentID = ?",array($eventGalleryContentID));

	}

    //#################################################################
    // DELETE PERMANENTLY EVENT
    //#################################################################
	function deletePermanentlyEvent($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

        //GET EVENT CONTENT
        $result = $connector->query("SELECT * FROM event_content WHERE eventID = ? ORDER BY sequence ASC", array($eventID));
        while ($row = $connector->fetchArray($result)) {
            $imageFile          = $row['imageFile'];
            $documentFile       = $row['documentFile'];
            $eventGalleryID     = $row['eventGalleryID'];

            //CHECK IF GALLERY IS AVAILABLE
            if($eventGalleryID != 0){

                //GET ALL GALLERY IMAGES
                $result2    = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ?", array($eventGalleryID));
                while($row2       = $connector->fetchArray($result2)){
                    $galleryImageFile   = $row2['galleryImageFile'];

                    unlink($largeDirectory.$galleryImageFile);
                }

                //DELETE EVENT GALLERY
                $deleteGallery   = $connector->query("DELETE FROM event_gallery WHERE eventGalleryID = ?", array($eventGalleryID));

                //DELETE EVENT GALLERY CONTENT
                $deleteGalleryContent   = $connector->query("DELETE FROM event_gallery_content WHERE eventGalleryID = ?", array($eventGalleryID));

            }else{
                //DELETE IMAGES AND DOCUMENT
                unlink($largeDirectory.$imageFile);
                unlink($mediumDirectory.$imageFile);
                unlink($smallDirectory.$imageFile);

                unlink($docDirectory.$documentFile);
            }
        }

        //DELETE EVENT PERMANENTLY
		$deletePost   = $connector->query("DELETE FROM events WHERE eventID = ?", array($eventID));

        //REMOVE META DETAILS
        $deletePost   = $connector->query("DELETE FROM meta_details WHERE eventID = ?", array($eventID));

	}

	//#################################################################
    // RECOVER EVENT
    //#################################################################
	function recoverEvent($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE  events SET
									deletedBy = ?,
									deletedDate = ?
									WHERE eventID = ?",
									array('0', '0000-00-00 00:00:00', $eventID));

	}

	//#################################################################
    // CHECK IF EVENT IS ALREADY IN USE
    //#################################################################
	function addEventCheck($event_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK EVENT
		$result = $connector->query("SELECT * FROM events WHERE eventTitle = ?", array($event_title));
		$total	= $connector->numResults($result);

		//IF EVENT HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
		//IF EVENT HAS BEEN USED
		elseif($total == 1){
			//GET USER INFO
			$row 		= $connector->fetchArray($result);

			//SET VARIABLES
			$deletedBy	= $row['deletedBy'];

			//IF EVENT HAS BEEN REMOVED
			if($deletedBy != 0){
				return 'removed_event';
			}
		}

	}

	//#################################################################
    // CHECK IF EVENT TITLE IS ALREADY IN USE
    //#################################################################
	function editEventCheck($eventID, $event_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK EVENT
		$result = $connector->query("SELECT * FROM events WHERE eventID != ? AND eventTitle = ?", array($eventID, $event_title));
		$total	= $connector->numResults($result);

		//IF EVENT HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

	//#################################################################
    // ADD EVENT PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $eventID){
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
		$result	= $connector->query("SELECT * FROM event_content WHERE eventID = ? AND deletedBy = ? ORDER BY sequence DESC", array($eventID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO event_content (eventID, paragraphTitle, paragraph, imageFile, imageTitle, documentFile, documentTitle, videoUrl, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($eventID, $title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE EVENT PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $eventContentID){
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
        $result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
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
        $result = $connector->query("SELECT * FROM event_content WHERE eventContentID = ?", array($eventContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//UPDATE EVENT CONTENT
		$update			= $connector->query("UPDATE event_content SET
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
                                            WHERE eventContentID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $modifiedNumber, $currentDate, $eventContentID));

	}

    //#################################################################
    // UPDATE EVENT GALLERY INFO
    //#################################################################
	function updateEventGalleryInfo($eventGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM event_gallery WHERE eventGalleryID = ?", array($eventGalleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//UPDATE EVENT GALLERY CONTENT
		$update			= $connector->query("UPDATE event_gallery SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE eventGalleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $eventGalleryID));

	}

    //#################################################################
    // SET eventGalleryID AND RETURN IT
    //#################################################################
	function setEventGalleryID($eventID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//ADD EventID INTO event_gallery
		$insert = $connector->query("INSERT INTO event_gallery (eventID, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($eventID, $currentUser, $currentDate));

        //GET eventGalleryID
        $result = $connector->query("SELECT * FROM event_gallery WHERE eventID = ? AND createdBy = ? AND createdDate = ? AND deletedBy =?", array($eventID, $currentUser, $currentDate, 0));
        $row    = $connector->fetchArray($result);

        //RETURN eventGalleryID
        return $row['eventGalleryID'];;
	}

    //#################################################################
    // ADD eventGalleryID INTO event_content
    //#################################################################
	function addEventGalleryIDIntoEventContent($eventID, $eventGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET SEQUENCE
        $result = $connector->query("SELECT * FROM event_content WHERE eventID = ? AND deletedBy = ? ORDER BY sequence DESC LIMIT 0,1", array($eventID, 0));
        $row    = $connector->fetchArray($result);
        $sequence   = $row['sequence']+1;

        //ADD eventGalleryID INTO event_content
        $insert = $connector->query("INSERT INTO event_content (eventID, eventGalleryID, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?)",
									array($eventID, $eventGalleryID, $currentUser, $currentDate, $sequence));
	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($eventGalleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryID = ? ORDER BY sequence DESC", array($eventGalleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD eventGalleryID INTO eventGallery
		$insert = $connector->query("INSERT INTO event_gallery_content (eventGalleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($eventGalleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($eventGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM event_gallery_content WHERE $eventGalleryID = ? ORDER BY eventGalleryContentID ASC", array($eventGalleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $eventGalleryContentID      = $row['eventGalleryContentID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$eventGalleryContentID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$eventGalleryContentID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                $this->deleteGalleryImage($eventGalleryContentID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM event_gallery_content WHERE eventGalleryContentID = ? AND galleryImageTitle = ?", array($eventGalleryContentID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE event_gallery_content SET
                                                galleryImageTitle = ?
                                                WHERE eventGalleryContentID = ?",
                                                array($updateImageTitle, $eventGalleryContentID));

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
$eventManager = new eventManager();

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
		$eventManager->overwriteCategory($category_name);

		//REDIRECT PAGE
		header("Location: ".$cms_root."events-manager/index.php?message=8");
		exit;
	}
}

//#################################################################
// ADD EVENT
//#################################################################
if(isset($_POST['add_event'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$event_title 		    = $_POST['event-title'];
	$event_venue		    = $_POST['event-venue'];
	$start_date			    = $_POST['start-date'];
	$start_time			    = $_POST['start-time'];
    $end_date               = $_POST['end-date'];
    $end_time               = $_POST['end-time'];
    $image_title	        = $_POST['image-title'];

	//HONEY POTS
	$event_paragraph	    = $_POST['event-paragraph'];
    $image_type		        = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1200;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $event_title        = $userLogin->specialCharactersToHTMLEntity($event_title);
    $event_venue        = $userLogin->specialCharactersToHTMLEntity($event_venue);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($event_title, 'Event Title', 2, 150);
    $v->validateString($event_venue, 'Event Venue', 2, 200);
	$v->validateDate($start_date, 'Start Date');
	$v->validateTime($start_time, 'Start Time');
    $v->validateDate($end_date, 'End Date');
	$v->validateTime($end_time, 'End Time');

    if($_FILES[$inputField]["tmp_name"] != '' && $_FILES[$inputField]["tmp_name"] != ' '){
        $v->validateString($image_title, 'Image Title',3, 150);
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($event_paragraph == '' && $image_type == ''){

			//CHECK IF EVENT IS ALREADY IN USE
			$event_used = $eventManager->addEventCheck($event_title);
			if($event_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //CREATE EVENT URL
        		$event_url = str_replace("'", "", $event_title);
        		$event_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($event_url));
        		$event_url = str_replace(' ', '-', $event_url);

                //CHECK IF EVENT URL EXISTS
                $event_url = $eventManager->checkEventURLExists($event_url, '');

				//INSERT EVENT INTO DATABASE
				$eventID = $eventManager->addEvent($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time, $image_title, $imageFile, $event_url);

                //GET META DETAILS
                $keywords		= $eventManager->getMetaKeyword($eventID);
                $description	= $eventManager->getMetaDescription($eventID);

                //UPDATE META DETAILS
                $eventManager->updateMetaDetails($keywords, $description, $eventID);

                //ADD INFORMATION INTO SEARCH INDEX
                $eventManager->addEventSearchIndex($eventID, $event_title, $keywords);

                //REDIRECT USER
    			header("Location: ".$cms_root."events-manager/crop-image-event.php?eventID=".$eventID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;

			}
			//IF EVENT HAS BEEN REMOVED
			elseif($event_used == 'removed_event'){

				//SET EVENT AS REMOVED
				$removed_event = '1';
			}
			else{

				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Event Title</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT EVENT
//#################################################################
if(isset($_POST['edit_event'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VALUES
    $eventID                = $_POST['eventID'];
	$event_title 		    = $_POST['event-title'];
	$event_venue		    = $_POST['event-venue'];
	$start_date			    = $_POST['start-date'];
	$start_time			    = $_POST['start-time'];
    $end_date               = $_POST['end-date'];
    $end_time               = $_POST['end-time'];
    $image_title	        = $_POST['image-title'];

	$modifiedDate			= $_POST['modifiedDate'];
	$modifiedBy				= $_SESSION['cmsUser'];
	$modifiedNumber			= $_POST['modifiedNumber'];

    //HONEY POTS
	$event_paragraph	    = $_POST['event-paragraph'];
    $image_type		        = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 1200;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $event_title        = $userLogin->specialCharactersToHTMLEntity($event_title);
    $event_venue        = $userLogin->specialCharactersToHTMLEntity($event_venue);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

    //VALIDATION
	$v = new formValidation();
	$v->validateString($event_title, 'Event Title', 2, 150);
    $v->validateString($event_venue, 'Event Venue', 2, 200);
	$v->validateDate($start_date, 'Start Date');
	$v->validateTime($start_time, 'Start Time');
    $v->validateDate($end_date, 'End Date');
	$v->validateTime($end_time, 'End Time');

    if($_FILES[$inputField]["tmp_name"] != '' && $_FILES[$inputField]["tmp_name"] != ' '){
        $v->validateString($image_title, 'Image Title',3, 150);
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($event_paragraph == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($eventManager->checkEventChanges($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time, $image_title, $eventID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

				//CHECK TITLE IS USED
				$event_used = $eventManager->editEventCheck($eventID, $event_title);
				if($event_used == 'unused'){

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

                    //GENERATE EVENT URL
                    $event_url = str_replace("'", "", $event_title);
                    $event_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($event_url));
            		$event_url = str_replace(' ', '-', $event_url);

                    //CHECK IF EVENT URL EXISTS
                    $event_url = $eventManager->checkEventURLExists($event_url, $eventID);

					//UPDATE USER IN DATABASE
					$eventManager->updateEvent($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time, $imageFile, $image_title, $event_url, $eventID);

                    //GET META DETAILS
        			$keywords		= $eventManager->getMetaKeyword($eventID);
        			$description	= $eventManager->getMetaDescription($eventID);

        			//UPDATE META DETAILS
        			$eventManager->updateMetaDetails($keywords, $description, $eventID);

                    //ADD INFORMATION INTO SEARCH INDEX
                    $eventManager->addEventSearchIndex($eventID, $event_title, $keywords);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."events-manager/crop-image-event.php?eventID=".$eventID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
                		exit;
                    }else{
                        header("Location: ".$cms_root."events-manager/index.php?message=4");
                		exit;
                    }

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Event Title</b> you supplied is already in use. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."events-manager/index.php");
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
// REACTIVATE EVENT
//#################################################################
if(isset($_POST['reactivate-event-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$event_title	= $_POST['event-title'];
    $event_venue    = $_POST['event-venue'];
	$start_date		= $_POST['start-date'];
	$start_time		= $_POST['start-time'];
	$end_date		= $_POST['end-date'];
	$end_time		= $_POST['end-time'];

	//HONEY POTS
	$event_paragraph   = $_POST['event-paragraph'];
	$image_type        = $_POST['image-type'];

	if($event_paragraph == '' && $image_type == ''){

		//OVERWRITE EVENT
		$eventID = $eventManager->overwriteEvent($event_title, $event_venue, $start_date, $start_time, $end_date, $end_time);

		//REDIRECT PAGE
		header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=10");
		exit;
	}
}

//#################################################################
//DELETE EVENT
//#################################################################
if(isset($_POST['delete_event'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$eventID	= $_POST['eventID'];

    //SET EVENT AS REMOVED IN DATABASE
    $eventManager->deleteEvent($eventID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."events-manager/index.php?message=6");
    exit;
}

//#################################################################
//DELETE PERMANENTLY EVENT
//#################################################################
if(isset($_POST['delete_permanently_event'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $eventID	= $_POST['eventID'];

    //SET USER AS REMOVED IN DATABASE
    $eventManager->deletePermanentlyEvent($eventID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."events-manager/index.php?message=14");
    exit;
}

//#################################################################
//RECOVER EVENT
//#################################################################
if(isset($_POST['recover_event'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $eventID	= $_POST['eventID'];

    //SET EVENT AS RECOVERED IN DATABASE
    $eventManager->recoverEvent($eventID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."events-manager/index.php?message=9");
    exit;
}

//#################################################################
// ADD EVENT PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$eventID	    = $_POST['eventID'];
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
	$previewSize			= 1200;

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

			//INSERT EVENT PARAGRAPH INTO DATABASE
			$eventManager->addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $eventID);

			//GET META DETAILS
			$keywords		= $eventManager->getMetaKeyword($eventID);
			$description	= $eventManager->getMetaDescription($eventID);

			//UPDATE META DETAILS
			$eventManager->updateMetaDetails($keywords, $description, $eventID);

            //GET EVENT INFO
            $eventTitle  = $eventManager->getEventInfo($eventID, 'eventTitle');

            //ADD INFORMATION INTO SEARCH INDEX
            $eventManager->addEventSearchIndex($eventID, $eventTitle, $keywords);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."events-manager/crop-image.php?eventID=".$eventID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=11");
        		exit;
			}
			//REDIRECT TO EVENT
			else{
				header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=11");
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
// EDIT EVENT PARAGRAPH
//#################################################################
if(isset($_POST['edit_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$eventID		   = $_POST['eventID'];
    $eventContentID    = $_POST['eventContentID'];
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
                    $image_title    = $eventManager->getEventContentInfo($eventContentID, 'imageTitle');
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
                $doc_title      = $eventManager->getEventContentInfo($eventContentID, 'documentTitle');
            }

            //CHECK IF VIDEO NEEDS TO BE REMOVED
            if($removeVideo == 1){
                $video = '';
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT EVENT PARAGRAPH INTO DATABASE
			$eventManager->updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $eventContentID);

			//GET META DETAILS
			$keywords		= $eventManager->getMetaKeyword($eventID);
			$description	= $eventManager->getMetaDescription($eventID);

			//UPDATE META DETAILS
			$eventManager->updateMetaDetails($keywords, $description, $eventID);

            //GET EVENT INFO
            $eventTitle  = $eventManager->getEventInfo($eventID, 'eventTitle');

            //ADD INFORMATION INTO SEARCH INDEX
            $eventManager->addEventSearchIndex($eventID, $eventTitle, $keywords);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."events-manager/crop-image.php?eventID=".$eventID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=12");
        		exit;
			}
			//REDIRECT TO EVENT
			else{
				header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=12");
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
	$eventContentID	    = $_POST['eventContentID'];
	$eventID			= $_POST['eventID'];

    //SET USER AS REMOVED IN DATABASE
    $eventManager->deleteParagraph($eventContentID);

    //GET META DETAILS
    $keywords		= $eventManager->getMetaKeyword($eventID);

    //GET EVENT INFO
    $eventTitle  = $eventManager->getEventInfo($eventID, 'eventTitle');

    //ADD INFORMATION INTO SEARCH INDEX
    $eventManager->addEventSearchIndex($eventID, $eventTitle, $keywords);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=13");
    exit;
}

//#################################################################
//DELETE GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$eventContentID  = $_POST['eventContentID'];
	$eventGalleryID  = $_POST['eventGalleryID'];
    $eventID         = $_POST['eventID'];

    //REMOVE GALLERY FROM DATABASE
    $eventManager->deleteGallery($eventContentID, $eventGalleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=17");
    exit;
}

//#################################################################
//ADD GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $eventID        = $_POST['eventID'];
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

            //GET eventGalleryID
            $eventGalleryID  = $eventManager->setEventGalleryID($eventID);

            //ADD eventGalleryID INTO event_content
            $eventManager->addEventGalleryIDIntoEventContent($eventID, $eventGalleryID);

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
                        $eventManager->addGalleryImages($eventGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $hasImages = 1;

                    }

                    $count++;
                }
            }

            //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
            if($hasImages == 1){
                header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=15");
        		exit;
            }else{
                header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID);
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
    $eventID            = $_POST['eventID'];
    $eventGalleryID     = $_POST['eventGalleryID'];
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
        $updatedGalleryImages = $eventManager->updateRemoveGalleryImages($eventGalleryID);

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
                        $eventManager->addGalleryImages($eventGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $eventManager->updateEventGalleryInfo($eventGalleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=16");
    		exit;
        }else{
            header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID);
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
if($eventRatio == 1){
    $newWidth		= 770;
    $newHeight		= 328;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($normalRatio == 1){
    $newWidth		= 770;
    $newHeight		= 481;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//IMAGE SIZES
$eventWidth		   = 770;
$eventHeight	   = 328;
$paragraphWidth		= 770;
$paragraphHeight	= 481;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$eventID			= $_POST['eventID'];
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
	header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=".$message);
    exit;
}

//CROP EVENT IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-event'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$eventID			= $_POST['eventID'];
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
	header("Location: ".$cms_root."events-manager/manage-event.php?eventID=".$eventID."&message=".$message);
    exit;
}
###################################################################
?>
