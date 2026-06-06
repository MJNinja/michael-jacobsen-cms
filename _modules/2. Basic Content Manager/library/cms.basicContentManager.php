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

class basicContentManager extends systemConfig{
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
			case 1: $displayMessage = 'A new Paragraph has successfully been added.'; break;
			case 2: $displayMessage = 'The selected Paragraph has successfully been updated.'; break;
			case 3: $displayMessage = 'The selected Paragraph has successfully been removed.'; break;
            case 4: $displayMessage = 'A new Gallery has successfully been added.'; break;
            case 5: $displayMessage = 'The selected Gallery has successfully been updated.'; break;
            case 6: $displayMessage = 'The selected Gallery has successfully been removed.'; break;
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
	function getMetaKeyword($pageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ? ORDER BY sequence ASC", array($pageID, 0));
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
	function getMetaDescription($pageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ? ORDER BY sequence ASC", array($pageID, 0));
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
	function updateMetaDetails($keywords, $description, $pageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE pageID = ?", array($pageID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (pageID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($pageID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE pageID = ?",
												array($keywords, $description, $pageID));
		}
	}

    //#################################################################
    // GET PARAGRAPH INFORMATION
    //#################################################################
	function getParagraphInfo($pageContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET TOTAL PAGES
    //#################################################################
	function getTotalPages(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages WHERE moduleID = ?", array('2'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET EMPTY PAGES
    //#################################################################
	function getEmptyPages(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages WHERE moduleID = ?", array('2'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$pageID	= $row['pageID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ?", array($pageID, '0'));
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
    // GET GALLERY INFORMATION
    //#################################################################
	function getGalleryInfo($basicPagesGalleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages_gallery WHERE basicPagesGalleryID = ?", array($basicPagesGalleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET PAGE INFORMATION
    //#################################################################
	function getPageInfo($pageID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages WHERE pageID = ?", array($pageID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET PAGE CONTENT INFORMATION
    //#################################################################
	function getPageContentInfo($pageContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $this->HTMLEntityToSpecialCharacters($row[$field]);

	}

	//#################################################################
    // GET PARAGRAPH CONTENT VIDEO
    //#################################################################
	function getPageContentVideo($pageContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
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
    // GET PAGE CONTENT DOCUMENT
    //#################################################################
	function getPageContentDocument($pageContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
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
    // GET PAGE CONTENT IMAGE
    //#################################################################
	function getPageContentImage($pageContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
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
    // GET PAGE GALLERY IMAGES
    //#################################################################
	function getPageGalleryImages($basicPagesGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? ORDER BY sequence ASC", array($basicPagesGalleryID));
		while($row	= $connector->fetchArray($result)){
            $basicPagesGalleryContentID = $row['basicPagesGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade" id="img_'.$basicPagesGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="remove_gallery_image">
                    <input type="checkbox" name="remove_gallery_image_'.$basicPagesGalleryContentID.'" value="1" />
                    <div class="remove_gallery_image_text">Remove Image</div>
                </div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$basicPagesGalleryContentID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET PAGE GALLERY IMAGES FOR SEQUENCING
    //#################################################################
	function getPageGalleryImagesSequencing($basicPagesGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? ORDER BY sequence ASC", array($basicPagesGalleryID));
		while($row	= $connector->fetchArray($result)){
            $basicPagesGalleryContentID = $row['basicPagesGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade sortable-content" id="'.$basicPagesGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title: <span class="normal-text">'.$galleryImageTitle.'</span></div></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF PAGEID IS IN DATABASE
    //#################################################################
	function checkPageIDDatabase($pageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM basic_pages WHERE pageID = ?", array($pageID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF PAGE GALLERY IS IN DATABASE
    //#################################################################
	function checkPageGalleryDatabase($basicPagesGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM basic_pages_gallery WHERE basicPagesGalleryID = ? ", array($basicPagesGalleryID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF PAGE CONTENT IS IN DATABASE
    //#################################################################
	function checkPageContentDatabase($pageID, $pageContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND pageContentID = ?", array($pageID, $pageContentID));
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
    // PAGES ARCHITECTURE
    //#################################################################
	function pagesArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL PAGES
		$result = $connector->query("SELECT * FROM basic_pages WHERE mainPageID = ? AND navLevel = ? AND moduleID = ? ORDER BY pageID ASC", array('0', '1', '2'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$empty			= '';
			$empty_bg		= '';
            $pageID         = $row['pageID'];
			$pageName		= $row['pageName'];

			//GET CONTENT FOR PAGE
			$result2	= $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ?", array($pageID, '0'));
			$pageContentTotal	= $connector->numResults($result2);

			//IF PAGE IS EMPTY
			if($pageContentTotal == 0){
				$empty		= '<span class="empty-category-text">(Empty)</span>';
				$empty_bg	='class="empty-category"';
			}

			//GENERATE OUPUT
			$txt.= '<tr>
				<td colspan="2" '.$empty_bg.'>'.$pageName.' '.$empty.'</td>
				<td '.$empty_bg.' align="center">
					<a href="'.$cms_root.'basic-content-manager/manage-pages.php?pageID='.$pageID.'" title="Manage">Manage</a>
				</td>
			  </tr>';

            //GET SUB PAGES
            $result3 = $connector->query("SELECT * FROM basic_pages WHERE mainPageID = ? AND moduleID = ? AND navLevel = ? ORDER BY pageName ASC", array($pageID, '2' ,'2'));
            while($row3    = $connector->fetchArray($result3)){

                //SET VARIABLES
    			$empty			= '';
    			$empty_bg		= '';
                $pageID         = $row3['pageID'];
    			$pageName		= $row3['pageName'];

    			//GET CONTENT FOR PAGE
    			$result4	= $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ?", array($pageID, '0'));
    			$pageContentTotal	= $connector->numResults($result4);

    			//IF PAGE IS EMPTY
    			if($pageContentTotal == 0){
    				$empty		= '<span class="empty-category-text">(Empty)</span>';
    				$empty_bg	='empty-category';
    			}

    			//GENERATE OUPUT
    			$txt.= '<tr>
                      <td width="2%" class="no-border-right '.$empty_bg.'"></td>
                      <td class="no-border-left '.$empty_bg.'">'.$pageName.' '.$empty.'</td>
                      <td class="'.$empty_bg.'" align="center">
                          <a href="'.$cms_root.'basic-content-manager/manage-pages.php?pageID='.$pageID.'" title="Manage">Manage</a>
                      </td>
                    </tr>';

            }

		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // REMOVE EMPTY GALLERY
    //#################################################################
    function removeEmptyGallery($basicPagesGalleryID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //DELETE FROM basic_pages_gallery
        $deleteGallery = $connector->query("DELETE FROM basic_pages_gallery WHERE basicPagesGalleryID = ?", array($basicPagesGalleryID));

        //DELETE FROM basic_pages_content
        $deleteGalleryContent = $connector->query("DELETE FROM basic_pages_content WHERE basicPagesGalleryID = ?", array($basicPagesGalleryID));
    }

	//#################################################################
    // PAGE CONTENT ARCHITECTURE
    //#################################################################
	function pageContentArchitecture($cms_root, $web_root, $pageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM basic_pages_content WHERE deletedBy = ?  AND pageID = ? ORDER BY sequence ASC", array('0', $pageID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$pageContentID          = $row['pageContentID'];
				$paragraphTitle		    = $row['paragraphTitle'];
				$paragraph			    = $row['paragraph'];
				$imageFile			    = $row['imageFile'];
				$imageTitle			    = $row['imageTitle'];
				$documentFile		    = $row['documentFile'];
				$documentTitle		    = $row['documentTitle'];
				$videoUrl			    = $row['videoUrl'];
				$basicPagesGalleryID    = $row['basicPagesGalleryID'];
                $sequence               = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				if($basicPagesGalleryID != 0){

                    //CHECK IF IMAGES IN GALLERY
                    $result4        = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ?", array($basicPagesGalleryID));
                    $totalImages    = $connector->numResults($result4);

                    //REMOVE GALLERY
                    if($totalImages == 0){
                        $this->removeEmptyGallery($basicPagesGalleryID);
                        $removedGallery = 1;

                    }else{
    					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$pageContentID.'">';

                            //GET TOTAL GALLERY IMAGES
                            $result2    = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($basicPagesGalleryID, 0));
                            $totalGalleryImage  = $connector->numResults($result2);

                            //IF MORE THAN 6 GALLERY IMAGES
                            if($totalGalleryImage > 6){
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC LIMIT 0,5", array($basicPagesGalleryID, 0));
                            }else{
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($basicPagesGalleryID, 0));
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

                                $txt.= '<a href="'.$cms_root.'basic-content-manager/edit-gallery.php?pageID='.$pageID.'&basicPagesGalleryID='.$basicPagesGalleryID.'" title="View all Gallery Images">
                                    <div class="paragraph-image-indicator">
                                        <div class="paragraph-image-more-indicator">+'.$extraImages.'</div>
                                    </div>
                                </a>';
                            }

                            $txt.= '<div class="clear"></div>
                            <div class="module-manage-content-links">
    							<form name="delete_gallery'.$pageContentID.'">
    								<input type="hidden" name="delete_gallery" value="1">
    								<input type="hidden" name="pageContentID" value="'.$pageContentID.'">
    								<input type="hidden" name="basicPagesGalleryID" value="'.$basicPagesGalleryID.'">
                                    <input type="hidden" name="pageID" value="'.$pageID.'">
    								<a href="javascript:deleteGallery('.$pageContentID.')" title="Remove Gallery">Remove Gallery</a>
    							</form>
    							<a href="'.$cms_root.'basic-content-manager/edit-gallery.php?pageID='.$pageID.'&basicPagesGalleryID='.$basicPagesGalleryID.'" title="Edit Gallery">Edit Gallery</a>
                                <a href="'.$cms_root.'basic-content-manager/sequence-gallery.php?pageID='.$pageID.'&basicPagesGalleryID='.$basicPagesGalleryID.'" title="Sequence Gallery">Sequence Gallery</a>
    							<div class="clear"></div>
    							</div>
                        </div>';
                    }
				}else{
					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$pageContentID.'">';

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
							<form name="delete_paragraph'.$pageContentID.'">
								<input type="hidden" name="delete_paragraph" value="1">
								<input type="hidden" name="pageContentID" value="'.$pageContentID.'">
								<input type="hidden" name="pageID" value="'.$pageID.'">
								<a href="javascript:deleteParagraph('.$pageContentID.')" title="Remove Paragraph">Remove Paragraph</a>
							</form>
							<a href="'.$cms_root.'basic-content-manager/edit-paragraph.php?pageContentID='.$pageContentID.'&pageID='.$pageID.'" title="Edit Paragraph">Edit Paragraph</a>
							<div class="clear"></div>
							</div>
                    </div>';
				}
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'basic-content-manager/add-paragraph.php?pageID='.$pageID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';
		}

        //IF GALLERY(S) REMOVED RELOAD PAGE
        if($removedGallery == 1){
            header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=6");
    		exit;
        }

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($pageContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
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
		$remove = $connector->query("DELETE FROM basic_pages_content WHERE pageContentID = ?",array($pageContentID));

	}

    //#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($pageContentID, $basicPagesGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ?", array($basicPagesGalleryID));
		while($row	= $connector->fetchArray($result)){
            $galleryImageFile           = $row['galleryImageFile'];
            $basicPagesGalleryContentID   = $row['basicPagesGalleryContentID'];

    		//DELETE IMAGES
    		unlink($largeDirectory.$galleryImageFile);
    		unlink($mediumDirectory.$galleryImageFile);
    		unlink($smallDirectory.$galleryImageFile);

    		//REMOVE USER
    		$remove = $connector->query("DELETE FROM basic_pages_gallery_content WHERE basicPagesGalleryContentID = ?",array($basicPagesGalleryContentID));
        }

        //REMOVE GALLERY ENTRIES
        $removeGallery = $connector->query("DELETE FROM basic_pages_gallery WHERE basicPagesGalleryID = ?",array($basicPagesGalleryID));
        $removeEntry = $connector->query("DELETE FROM basic_pages_content WHERE pageContentID = ?",array($pageContentID));

	}

    //#################################################################
    // DELETE GALLERY IMAGE
    //#################################################################
	function deleteGalleryImage($basicPagesGalleryContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryContentID = ?", array($basicPagesGalleryContentID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM basic_pages_gallery_content WHERE basicPagesGalleryContentID = ?", array($basicPagesGalleryContentID));

	}

	//#################################################################
    // ADD PAGE PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $pageID){
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
		$result	= $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ? ORDER BY sequence DESC", array($pageID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO basic_pages_content (pageID, paragraphTitle, paragraph, imageFile, imageTitle, documentFile, documentTitle, videoUrl, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($pageID, $title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE PAGE PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $pageContentID){
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
        $result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
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
        $result = $connector->query("SELECT * FROM basic_pages_content WHERE pageContentID = ?", array($pageContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD PAGES CONTENT
		$update			= $connector->query("UPDATE basic_pages_content SET
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
                                            WHERE pageContentID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $modifiedNumber, $currentDate, $pageContentID));

	}

    //#################################################################
    // UPDATE PAGES GALLERY INFO
    //#################################################################
	function updatePagesGalleryInfo($basicPagesGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM basic_pages_gallery WHERE basicPagesGalleryID = ?", array($basicPagesGalleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD PAGES CONTENT
		$update			= $connector->query("UPDATE basic_pages_gallery SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE basicPagesGalleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $basicPagesGalleryID));

	}

    //#################################################################
    // SET basicPagesGalleryID AND RETURN IT
    //#################################################################
	function setPagesGalleryID($pageID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//ADD pagesID INTO basic_pages_gallery
		$insert = $connector->query("INSERT INTO basic_pages_gallery (pageID, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($pageID, $currentUser, $currentDate));

        //GET basicPagesGalleryID
        $result = $connector->query("SELECT * FROM basic_pages_gallery WHERE pageID = ? AND createdBy = ? AND createdDate = ? AND deletedBy =?", array($pageID, $currentUser, $currentDate, 0));
        $row    = $connector->fetchArray($result);

        //RETURN basicPagesGalleryID
        return $row['basicPagesGalleryID'];;
	}

    //#################################################################
    // ADD basicPagesGalleryID INTO basic_pages_content
    //#################################################################
	function addPageGalleryIDIntoPageContent($pageID, $basicPagesGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET SEQUENCE
        $result = $connector->query("SELECT * FROM basic_pages_content WHERE pageID = ? AND deletedBy = ? ORDER BY sequence DESC LIMIT 0,1", array($pageID, 0));
        $row    = $connector->fetchArray($result);
        $sequence   = $row['sequence']+1;

        //ADD $basicPagesGalleryID INTO basic_pages_content
        $insert = $connector->query("INSERT INTO basic_pages_content (pageID, basicPagesGalleryID, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?)",
									array($pageID, $basicPagesGalleryID, $currentUser, $currentDate, $sequence));
	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($basicPagesGalleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? ORDER BY sequence DESC", array($basicPagesGalleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD IMAGES INTO DATABASE
		$insert = $connector->query("INSERT INTO basic_pages_gallery_content (basicPagesGalleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($basicPagesGalleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($basicPagesGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryID = ? ORDER BY basicPagesGalleryContentID ASC", array($basicPagesGalleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $basicPagesGalleryContentID = $row['basicPagesGalleryContentID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$basicPagesGalleryContentID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$basicPagesGalleryContentID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                $this->deleteGalleryImage($basicPagesGalleryContentID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM basic_pages_gallery_content WHERE basicPagesGalleryContentID = ? AND galleryImageTitle = ?", array($basicPagesGalleryContentID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE basic_pages_gallery_content SET
                                                galleryImageTitle = ?
                                                WHERE basicPagesGalleryContentID = ?",
                                                array($updateImageTitle, $basicPagesGalleryContentID));

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
$basicContentManager = new basicContentManager();

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
		$basicContentManager->overwriteCategory($category_name);

		//REDIRECT PAGE
		header("Location: ".$cms_root."blog-manager/index.php?message=8");
		exit;
	}
}

//#################################################################
// ADD PAGES CONTENT PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$pageID		    = $_POST['pageID'];
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

			//INSERT BLOG POST INTO DATABASE
			$basicContentManager->addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $pageID);

			//GET META DETAILS
			$keywords		= $basicContentManager->getMetaKeyword($pageID);
			$description	= $basicContentManager->getMetaDescription($pageID);

			//UPDATE META DETAILS
			$basicContentManager->updateMetaDetails($keywords, $description, $pageID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."basic-content-manager/crop-image.php?pageID=".$pageID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=1");
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
// EDIT PAGES CONTENT PARAGRAPH
//#################################################################
if(isset($_POST['edit_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$pageID		       = $_POST['pageID'];
    $pageContentID     = $_POST['pageContentID'];
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

                //CHECK IF IMAGE TITLE IS NOT SET
                if($imageTitle == ''){
                    $image_title    = $basicContentManager->getPageContentInfo($pageContentID, 'imageTitle');
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
                $doc_title      = $basicContentManager->getPageContentInfo($pageContentID, 'documentTitle');
            }

            //CHECK IF VIDEO NEEDS TO BE REMOVED
            if($removeVideo == 1){
                $video = '';
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT PAGE CONTENT INTO DATABASE
			$basicContentManager->updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $pageContentID);

			//GET META DETAILS
			$keywords		= $basicContentManager->getMetaKeyword($pageID);
			$description	= $basicContentManager->getMetaDescription($pageID);

			//UPDATE META DETAILS
			$basicContentManager->updateMetaDetails($keywords, $description, $pageID);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."basic-content-manager/crop-image.php?pageID=".$pageID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=2");
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
//DELETE PAGES CONTENT PARAGRAPH
//#################################################################
if(isset($_POST['delete_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$pageContentID	= $_POST['pageContentID'];
	$pageID			= $_POST['pageID'];

    //SET USER AS REMOVED IN DATABASE
    $basicContentManager->deleteParagraph($pageContentID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=3");
    exit;
}

//#################################################################
//DELETE PAGES GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$basicPagesGalleryID  = $_POST['basicPagesGalleryID'];
	$pageContentID        = $_POST['pageContentID'];
    $pageID               = $_POST['pageID'];

    //REMOVE GALLERY FROM DATABASE
    $basicContentManager->deleteGallery($pageContentID, $basicPagesGalleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=6");
    exit;
}

//#################################################################
//ADD PAGES GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $pageID     = $_POST['pageID'];
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

            //GET basicPagesGalleryID
            $basicPagesGalleryID  = $basicContentManager->setPagesGalleryID($pageID);

            //ADD basicPagesGalleryID INTO basic_pages_content
            $basicContentManager->addPageGalleryIDIntoPageContent($pageID, $basicPagesGalleryID);

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
                        $basicContentManager->addGalleryImages($basicPagesGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $hasImages = 1;

                    }

                    $count++;
                }
            }

            //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
            if($hasImages == 1){
                header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=4");
        		exit;
            }else{
                header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID);
        		exit;
            }
        }
    }else{
        $error_message  = 'There was an error creating your galley!';
        $errors         = 'You have to choose at least one image in order to create the gallery!';
    }
}

//#################################################################
//EDIT PAGES GALLERY
//#################################################################
if(isset($_POST['edit_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $pageID                 = $_POST['pageID'];
    $basicPagesGalleryID    = $_POST['basicPagesGalleryID'];
    $value                  = json_decode(stripslashes($_POST['value']));

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
        $updatedGalleryImages = $basicContentManager->updateRemoveGalleryImages($basicPagesGalleryID);

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
                        $basicContentManager->addGalleryImages($basicPagesGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $basicContentManager->updatePagesGalleryInfo($basicPagesGalleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=5");
    		exit;
        }else{
            header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID);
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
$newWidth		= 350;
$newHeight		= 263;

//CALCULATE NEW RATIO
$ratio			= $newWidth / $newHeight;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$pageID			    = $_POST['pageID'];
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
	header("Location: ".$cms_root."basic-content-manager/manage-pages.php?pageID=".$pageID."&message=".$message);
    exit;
}
###################################################################
?>
