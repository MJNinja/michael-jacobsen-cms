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

class blogManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Post has successfully been added.'; break;
            case 3: $displayMessage = 'The selected Category has successfully been updated.'; break;
			case 4: $displayMessage = 'The selected Post has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Category has successfully been removed.'; break;
			case 6: $displayMessage = 'The selected Post has successfully been removed.'; break;
			case 7: $displayMessage = 'The selected Category has successfully been recovered.'; break;
			case 8: $displayMessage = 'The selected Category has successfully been re-activated.'; break;
			case 9: $displayMessage = 'The selected Post has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Post has successfully been re-activated.'; break;
			case 11: $displayMessage = 'A new Paragraph has successfully been added.'; break;
			case 12: $displayMessage = 'The selected Paragraph has successfully been updated.'; break;
			case 13: $displayMessage = 'The selected Paragraph has successfully been removed.'; break;
            case 14: $displayMessage = 'The selected Post has successfully been permanently deleted.'; break;
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
	//CHECK BLOG URL EXISTS
	//#################################################################
	function checkBlogURLExists($url, $blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED
        $currentURL = $this->getBlogPostInfo($blogPostID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM blog_posts WHERE url = ? LIMIT 0,1", array($url));
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
                    $result2    = $connector->query("SELECT url FROM blog_posts WHERE url = ? LIMIT 0,1", array($newURL));
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
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ? ORDER BY sequence ASC", array($blogPostID, 0));
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
	function getMetaDescription($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ? ORDER BY sequence ASC", array($blogPostID, 0));
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
	function updateMetaDetails($keywords, $description, $blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE blogPostID = ?", array($blogPostID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (blogPostID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($blogPostID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE blogPostID = ?",
												array($keywords, $description, $blogPostID));
		}
	}

    //#################################################################
    // GET META KEYWORDS FOR CATEGORY
    //#################################################################
	function getMetaKeywordCategory($blogCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ? AND deletedBy = ?", array($blogCatID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['categoryDescription']).' '.strip_tags($row['categoryName']);
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
	//GET THE META DESCRIPTION FOR CATEGORY
	//#################################################################
	function getMetaDescriptionCategory($blogCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ? AND deletedBy = ?", array($blogCatID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['categoryDescription']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS FOR CATEGORY
	//#################################################################
	function updateMetaDetailsCategory($keywords, $description, $blogCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE blogCatID = ?", array($blogCatID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (blogCatID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($blogCatID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE blogCatID = ?",
												array($keywords, $description, $blogCatID));
		}
	}

    //#################################################################
	// ADD BLOG POST INTO SEARCH INDEX
	//#################################################################
	function addBlogPostSearchIndex($blogPostID, $title, $keywords, $blogPostIntro){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//GET INDEX INFO
		$result	= $connector->query("SELECT * FROM search_index WHERE blogPostID = ?", array($blogPostID));
		$row	= $connector->fetchArray($result);
		$total	= $connector->numResults($result);

		//CHECK IF BLOG POST IS ALREADY INDEX
		if($total == 0){
			//INSERT BLOG POST SEARCH INDEX
			$insert	= $connector->query("INSERT INTO search_index (title, keywords, content, blogPostID)
										VALUES(?, ?, ?, ?)"
										, array($title, $keywords, $blogPostIntro, $blogPostID));
		}else{
			//UPDATE BLOG POST SEARCH INDEX
			$update	= $connector->query("UPDATE search_index SET
										title			= ?,
										keywords		= ?,
										content			= ?
										WHERE blogPostID = ?"
										, array($title, $keywords, $blogPostIntro, $blogPostID));
		}

	}

    //#################################################################
    // CHECK IF A CATEGORY HAS ALREADY BEEN ADDED
    //#################################################################
	function checkCategoryAdded(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORIES
		$result = $connector->query("SELECT * FROM blog_category", array());
		$total	= $connector->numResults($result);

        //RETURN TOTAL
		return $total;
	}

	//#################################################################
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($blogCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ?", array($blogCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET PARAGRAPH INFORMATION
    //#################################################################
	function getParagraphInfo($blogPostContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET BLOG POST IMAGE
    //#################################################################
	function getBlogPostImage($blogPostID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostID = ?", array($blogPostID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['blogPostImageFile'];
		$imageTitle	= $row['blogPostImageTitle'];

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
	function getCategoryImage($blogCatID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ?", array($blogCatID));
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
	function getGalleryInfo($blogPostGalleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_post_gallery WHERE blogPostGalleryID = ?", array($blogPostGalleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET BLOG POST INFORMATION
    //#################################################################
	function getBlogPostInfo($blogPostID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostID = ?", array($blogPostID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET BLOG POST CONTENT INFORMATION
    //#################################################################
	function getBlogPostContentInfo($blogPostContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET BLOG POST CONTENT VIDEO
    //#################################################################
	function getBlogPostContentVideo($blogPostContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
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
    // GET BLOG POST CONTENT DOCUMENT
    //#################################################################
	function getBlogPostContentDocument($blogPostContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
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
    // GET BLOG POST CONTENT IMAGE
    //#################################################################
	function getBlogPostContentImage($blogPostContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
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
    // GET ALL AUTHORS
    //#################################################################
	function getAllAuthors($postedValue){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM blog_authors ORDER BY authorName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $authorID   = $row['authorID'];
            $authorName = $row['authorName'];

            //GENERATE OUTPUT
            if($authorID == $postedValue){
                $txt.= '<option value="'.$authorID.'" selected="selected">'.$authorName.'</option>';
            }else{
                $txt.= '<option value="'.$authorID.'">'.$authorName.'</option>';
            }
		}

		return $txt;
	}

    //#################################################################
    // GET ALL AFFILIATE LINKS
    //#################################################################
	function getAllAffiliateLinks(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM affiliate ORDER BY affTitle ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $affTitle    = $row['affTitle'];

			$txt.= '"'.$affTitle.'",';
		}

		return substr($txt, 0, -1);
	}

    //#################################################################
    // GENERATE POSTED TAGS
    //#################################################################
	function generatePostedTags($value){
        //SET DEFAULT ARRAY
        $txt = '';

        //REMOVE FIRST AND LAST CHARACTER
        $tagString = substr($value, 1,-1);

        //TURN INTO ARRAY
        $tagArray = explode(",", $tagString);

        //LOOP THROUGH ARRAY
        foreach($tagArray as $tags){
            //GENERATE OUTPUT
            $txt.= '<li>'.$tags.'</li>';
        }

        //RETURN OUTPUT
        return $txt;
	}

    //#################################################################
    // GENERATE TAGS FROM DATABASE
    //#################################################################
	function getTags($blogPostID, $field){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM blog_posts WHERE blogPostID = ?", array($blogPostID));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLE
            $tagHolder = $row[$field];

            //REMOVE FIRST AND LAST CHARACTER
            $tagString = substr($tagHolder, 1,-1);

            //TURN INTO ARRAY
            $tagArray = explode(",", $tagString);

            //LOOP THROUGH ARRAY
            foreach($tagArray as $tags){
                //GET NAME OF BLOG CATEGORY ID
                $result2    = $connector->query("SELECT * FROM affiliate WHERE affiliateID = ? AND deletedBy = ?", array($tags, '0'));
                $row2       = $connector->fetchArray($result2);
                $affTitle   = $row2['affTitle'];

                //GENERATE OUTPUT
                $txt.= '<li>'.$affTitle.'</li>';
            }
        }

        //RETURN OUTPUT
        return $txt;
	}

    //#################################################################
    // GENERATE TAGS FROM DATABASE
    //#################################################################
	function getBlogPostTags($blogPostID, $field){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM blog_posts WHERE blogPostID = ?", array($blogPostID));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLE
            $tagHolder = $row[$field];

            //REMOVE FIRST AND LAST CHARACTER
            $tagString = substr($tagHolder, 1,-1);

            //TURN INTO ARRAY
            $tagArray = explode(",", $tagString);

            //LOOP THROUGH ARRAY
            foreach($tagArray as $tags){
                //GET NAME OF BLOG CATEGORY ID
                $result2    = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ? AND deletedBy = ?", array($tags, '0'));
                $row2       = $connector->fetchArray($result2);
                $categoryName   = $row2['categoryName'];

                //GENERATE OUTPUT
                $txt.= '<li>'.$categoryName.'</li>';
            }
        }

        //RETURN OUTPUT
        return $txt;
	}

    //#################################################################
    // GET BLOG GALLERY IMAGES
    //#################################################################
	function getBlogGalleryImages($blogPostGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET BLOG GALLERY INFO
		$result = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? ORDER BY blogPostGalleryContentID ASC", array($blogPostGalleryID));
		while($row	= $connector->fetchArray($result)){
            $blogPostGalleryContentID   = $row['blogPostGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade" id="img'.$blogPostGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="remove_gallery_image">
                    <input type="checkbox" name="remove_gallery_image_'.$blogPostGalleryContentID.'" value="1" />
                    <div class="remove_gallery_image_text">Remove Image</div>
                </div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$blogPostGalleryContentID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET BLOG GALLERY IMAGES FOR SEQUENCING
    //#################################################################
	function getBlogGalleryImagesSequencing($blogPostGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PAGES GALLERY INFO
		$result = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? ORDER BY sequence ASC", array($blogPostGalleryID));
		while($row	= $connector->fetchArray($result)){
            $blogPostGalleryContentID   = $row['blogPostGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade sortable-content" id="'.$blogPostGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title: <span class="normal-text">'.$galleryImageTitle.'</span></div></div><div class="clear"></div>
            </div>';

            $count++;
        }

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // GET BLOG POST DATE/TIME INFORMATION
    //#################################################################
	function getBlogPostDateTimeInfo($blogPostID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostID = ?", array($blogPostID));
		$row	= $connector->fetchArray($result);
		$publishDate	= $row['publishDate'];

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
    // CHECK IF BLOG CATEGORY IS IN DATABASE
    //#################################################################
	function checkCategoryDatabase($blogCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ?", array($blogCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}

	}

	//#################################################################
    // CHECK IF BLOG POST IS IN DATABASE
    //#################################################################
	function checkBlogPostDatabase($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostID = ?", array($blogPostID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // GET ALL BLOG CATEGORIES
    //#################################################################
	function getAllBlogCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM blog_category ORDER BY categoryName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $categoryName    = $row['categoryName'];

			$txt.= '"'.$categoryName.'",';
		}

		return substr($txt, 0, -1);
	}

    //#################################################################
    // CHECK IF BLOG GALLERY IS IN DATABASE
    //#################################################################
	function checkBlogGalleryDatabase($blogPostGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM blog_post_gallery WHERE blogPostGalleryID = ? ", array($blogPostGalleryID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF BLOG POST CONTENT IS IN DATABASE
    //#################################################################
	function checkBlogPostContentDatabase($blogPostID, $blogPostContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND blogPostContentID = ?", array($blogPostID, $blogPostContentID));
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
    // GET TOTAL BLOG CATEGORIES
    //#################################################################
	function getTotalCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_category WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL BLOG POSTS
    //#################################################################
	function getTotalBlogPosts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET EMPTY BLOG POSTS
    //#################################################################
	function getEmptyBlogPosts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$blogPostID	= $row['blogPostID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ?", array($blogPostID, '0'));
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
    // GET TOTAL PUBLISHED BLOG POSTS
    //#################################################################
	function getPublishedBlogPosts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET VARIABLES
		$currentDate = date("Y-m-d H:i:s");

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE publishDate <= ? AND deletedBy = ?", array($currentDate, '0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL REMOVED BLOG POSTS
    //#################################################################
	function getTotalRemovedBlogPosts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // BLOG POST CONTENT ARCHITECTURE
    //#################################################################
	function categoryArchitecture($cms_root, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM blog_category WHERE deletedBy = ? ORDER BY categoryName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$blogCatID          = $row['blogCatID'];
				$categoryName		= $row['categoryName'];
				$catImageTitle  	= $row['catImageTitle'];
				$catImage		    = $row['catImage'];
                $paragraph          = $row['categoryDescription'];

                //GET PLAYLIST INFO
                $result2        = $connector->query("SELECT * FROM blog_posts WHERE blogCatID LIKE ?",array("%,$categoryName,%"));
                $totalResults   = $connector->numResults($result2);

                //CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder">';

					//IF AN IMAGE IS AVAILABLE
					if($catImage != ''){
						$txt.= '<div class="paragraph-image-category">
							<img src="'.$web_root.'cms-images/large/'.$catImage.'" alt="'.$catImageTitle.'" title="'.$catImageTitle.'" border="0"/>
						</div>';
					}

					//IF A TITLE IS AVAILABLE
					if($categoryName != ''){
                		$txt.= '<div class="paragraph-title"><b>'.$categoryName.'</b></div>';
					}

                    $txt.= '<div class="paragraph-text">'.$paragraph.'</div>
                            <div class="clear"></div>';

					$txt.= '<div class="module-manage-content-links">';

                    //TELL IF CATEGORY CAN BE DELETED
                    if($totalResults == 0){
						$txt.='<form name="delete_blog_category'.$blogCatID.'">
                        <input type="hidden" name="delete_blog_category" value="1">
                        <input type="hidden" name="blogCatID" value="'.$blogCatID.'">
							<a href="javascript:deleteBlogCategory('.$blogCatID.')" title="Remove Category">Remove Category</a>
						</form>';
                    }else{
                        $txt.='<a href="javascript:noDeleteCategory()" title="Remove Category">Remove Category</a>';
                    }

                    $txt.= '<a href="'.$cms_root.'blog-manager/edit-blog-category.php?blogCatID='.$blogCatID.'" title="Edit Category">Edit Category</a>
						<div class="clear"></div>
						</div>
                </div>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Categories available. <a href="'.$cms_root.'blog-manager/add-blog-category.php" title="Add Blog Category">Please add a category here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // BLOG POST ARCHITECTURE
    //#################################################################
	function blogPostArchitecture($limit, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL BLOG POSTS
		$result = $connector->query("SELECT * FROM blog_posts WHERE deletedBy = ? ORDER BY publishDate DESC LIMIT 0, $limit", array('0',));
		$blogPostTotal = $connector->numResults($result);

		//IF BLOG POSTS ARE AVAILABLE
		if($blogPostTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$status			= '';
				$status_bg		= '';
				$date			= '';
				$currentDate	= date('Y-m-d H:i:s');
				$blogPostID		= $row['blogPostID'];
				$blogPostTitle	= $row['blogPostTitle'];
				$publishDate	= $row['publishDate'];

				//FORMAT PUBLISH DATE
				$date = date("j F Y - H:i", strtotime($publishDate));

				//GET ALL BLOG POST CONTENT FOR A BLOG POST
				$result2	= $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ?", array($blogPostID, '0'));
				$blogPostContentTotal	= $connector->numResults($result2);

				//IF BLOG POST IS EMPTY
				if($blogPostContentTotal == 0){
					$status		= '<span class="empty-category-text">(Empty)</span>';
					$status_bg	='class="empty-category"';
				}
				//CHECK IF POST HAS ALREADY BEEN PUBLISHED
				else{
					if($publishDate > $currentDate){
						//NOT YET PUBLISHED
						$status		= '<span class="unpublished-post-text">(Not yet published)</span>';
						$status_bg	= 'class="unpublished-post"';
					}elseif($publishDate < $currentDate){
						//PUBLISHED
						$status	= '(Published)';
					}
				}

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$status_bg.'>'.$blogPostTitle.' '.$status.'</td>
					<td '.$status_bg.' align="center">'.$date.'</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'blog-manager/manage-blog-post.php?blogPostID='.$blogPostID.'" title="Manage">Manage</a>
					</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'blog-manager/edit-blog-post.php?blogPostID='.$blogPostID.'" title="Modify">Modify</a>
					</td>
					<td '.$status_bg.' align="center">';

					$txt.='<form name="delete_blog_post'.$blogPostID.'">
							<input type="hidden" name="delete_blog_post" value="1">
							<input type="hidden" name="blogPostID" value="'.$blogPostID.'">
							<a href="javascript:deleteBlogPost('.$blogPostID.')" title="Remove">Remove</a>
						</form>';

					$txt.= '</td>
				  </tr>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Blog Posts available. <a href="'.$cms_root.'blog-manager/add-blog-post.php" title="Add Blog Post">Please add a blog post here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // BLOG POST ARCHITECTURE (REMOVED)
    //#################################################################
	function blogPostArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM blog_posts WHERE deletedBy != ? ORDER BY blogPostTitle ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$blogPostID		= $row['blogPostID'];
			$blogPostTitle	= $row['blogPostTitle'];
			$publishDate	= $row['publishDate'];

			//FORMAT PUBLISH DATE
			$publishDate	= date("j F Y - H:i", strtotime($publishDate));

			//GENERATE OUPUT
			$txt.= '<tr>
						<td width="1%" class="removed-account"></td>
						<td width="50%">'.$blogPostTitle.'</td>
						<td width="18%" align="center">'.$publishDate.'</td>
                        <td width="18%" align="center">
                            <form name="delete_permanently_blog_post'.$blogPostID.'">
								<input type="hidden" name="delete_permanently_blog_post" value="1">
								<input type="hidden" name="blogPostID" value="'.$blogPostID.'">
								<a href="javascript:deletePermanentlyBlogPost('.$blogPostID.')" title="Delete">Delete</a>
							</form>
                        </td>
						<td width="13%" align="center">
							<form name="recover_blog_post'.$blogPostID.'">
								<input type="hidden" name="recover_blog_post" value="1">
								<input type="hidden" name="blogPostID" value="'.$blogPostID.'">
								<a href="javascript:recoverBlogPost('.$blogPostID.')" title="Recover">Recover</a>
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
    function removeEmptyGallery($blogPostGalleryID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //DELETE FROM blog_post_gallery
        $deleteGallery = $connector->query("DELETE FROM blog_post_gallery WHERE blogPostGalleryID = ?", array($blogPostGalleryID));

        //DELETE FROM blog_post_content
        $deleteGalleryContent = $connector->query("DELETE FROM blog_post_content WHERE blogPostGalleryID = ?", array($blogPostGalleryID));
    }

	//#################################################################
    // BLOG POST CONTENT ARCHITECTURE
    //#################################################################
	function blogPostContentArchitecture($cms_root, $web_root, $blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM blog_post_content WHERE deletedBy = ?  AND blogPostID = ? ORDER BY sequence ASC", array('0', $blogPostID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$blogPostContentID	= $row['blogPostContentID'];
				$paragraphTitle		= $row['paragraphTitle'];
				$paragraph			= $row['paragraph'];
				$imageFile			= $row['imageFile'];
				$imageTitle			= $row['imageTitle'];
				$documentFile		= $row['documentFile'];
				$documentTitle		= $row['documentTitle'];
				$videoUrl			= $row['videoUrl'];
				$blogPostGalleryID	= $row['blogPostGalleryID'];
                $sequence           = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				if($blogPostGalleryID != 0){

                    //CHECK IF IMAGES IN GALLERY
                    $result4        = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ?", array($blogPostGalleryID));
                    $totalImages    = $connector->numResults($result4);

                    //REMOVE GALLERY
                    if($totalImages == 0){
                        $this->removeEmptyGallery($blogPostGalleryID);
                        $removedGallery = 1;

                    }else{
    					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$blogPostContentID.'">';

                            //GET TOTAL GALLERY IMAGES
                            $result2    = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($blogPostGalleryID, 0));
                            $totalGalleryImage  = $connector->numResults($result2);

                            //IF MORE THAN 6 GALLERY IMAGES
                            if($totalGalleryImage > 6){
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC LIMIT 0,5", array($blogPostGalleryID, 0));
                            }else{
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($blogPostGalleryID, 0));
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

                                $txt.= '<a href="'.$cms_root.'blog-manager/edit-gallery.php?blogPostID='.$blogPostID.'&blogPostGalleryID='.$blogPostGalleryID.'" title="View all Gallery Images">
                                    <div class="paragraph-image-indicator">
                                        <div class="paragraph-image-more-indicator">+'.$extraImages.'</div>
                                    </div>
                                </a>';
                            }

                            $txt.= '<div class="clear"></div>
                            <div class="module-manage-content-links">
    							<form name="delete_gallery'.$blogPostContentID.'">
    								<input type="hidden" name="delete_gallery" value="1">
    								<input type="hidden" name="blogPostContentID" value="'.$blogPostContentID.'">
    								<input type="hidden" name="blogPostGalleryID" value="'.$blogPostGalleryID.'">
                                    <input type="hidden" name="blogPostID" value="'.$blogPostID.'">
    								<a href="javascript:deleteGallery('.$blogPostContentID.')" title="Remove Gallery">Remove Gallery</a>
    							</form>
    							<a href="'.$cms_root.'blog-manager/edit-gallery.php?blogPostID='.$blogPostID.'&blogPostGalleryID='.$blogPostGalleryID.'" title="Edit Gallery">Edit Gallery</a>
                                <a href="'.$cms_root.'blog-manager/sequence-gallery.php?blogPostID='.$blogPostID.'&blogPostGalleryID='.$blogPostGalleryID.'" title="Sequence Gallery">Sequence Gallery</a>
    							<div class="clear"></div>
    							</div>
                        </div>';
                    }
				}else{
					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$blogPostContentID.'">';

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
							<form name="delete_paragraph'.$blogPostContentID.'">
								<input type="hidden" name="delete_paragraph" value="1">
								<input type="hidden" name="blogPostContentID" value="'.$blogPostContentID.'">
								<input type="hidden" name="blogPostID" value="'.$blogPostID.'">
								<a href="javascript:deleteParagraph('.$blogPostContentID.')" title="Remove Paragraph">Remove Paragraph</a>
							</form>
							<a href="'.$cms_root.'blog-manager/edit-paragraph.php?blogPostContentID='.$blogPostContentID.'&blogPostID='.$blogPostID.'" title="Edit Paragraph">Edit Paragraph</a>
							<div class="clear"></div>
							</div>
                    </div>';
				}
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'blog-manager/add-paragraph.php?blogPostID='.$blogPostID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';
		}

        //IF GALLERY(S) REMOVED RELOAD PAGE
        if($removedGallery == 1){
            header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&message=17");
    		exit;
        }

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF ANY BLOG POSTS HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedBlogPosts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM blog_posts WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

    //#################################################################
    // CHECK IF BLOG CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkCategoryChanges($title, $paragraph, $image_title, $blogCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM blog_category WHERE categoryName = ? AND catImageTitle = ? AND blogCatID = ? AND categoryDescription = ?", array($title, $image_title, $blogCatID, $paragraph));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // CHECK IF BLOG POST INFO HAS BEEN CHANGED
    //#################################################################
	function checkBlogPostChanges($blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $blogPostID, $blog_post_author, $categories, $affiliateIDs, $image_title){

		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE SPACES IN PUBLISH TIME
		$blog_post_time = str_replace(' ', '', $blog_post_time).':00';

		//CREATE PUBLISH DATE
		$publishDate = date("Y-m-d H:i:s", strtotime($blog_post_date.' '.$blog_post_time));

        //CHECK AFFILIATE ID
        if($affiliateIDs == ',,'){
            $affiliateIDs = '';
        }

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostTitle = ? AND blogPostIntro = ? AND publishDate = ? AND blogPostID = ? AND authorID = ? AND blogCatID = ? AND affiliateIDs = ? AND blogPostImageTitle = ?", array($blog_post_title, $blog_post_intro, $publishDate, $blogPostID, $blog_post_author, $categories, $affiliateIDs, $image_title));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // ADD CATEGORY
    //#################################################################
	function addCategory($title, $paragraph, $image_title, $imageFile){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$category_name		= strip_tags($title);
        $image_title		= strip_tags($image_title);

		//ADD USER
		$insert = $connector->query("INSERT INTO blog_category (categoryName, categoryDescription, catImageTitle, catImage, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($title, $paragraph, $image_title, $imageFile, $currentUser, $currentDate));

        //RETURN CATEGORY ID
        $result = $connector->query("SELECT * FROM blog_category ORDER BY blogCatID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['blogCatID'];

	}

	//#################################################################
	//OVERWRITE CATEGORY
	//#################################################################
	function overwriteCategory($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$category_name	= strip_tags($category_name);

		//UPDATE USER
		$update = $connector->query("UPDATE blog_category SET
									categoryName = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE categoryName = ?",
									array($category_name, '0', '0000-00-00 00:00:00', $category_name));

	}

    //#################################################################
    // UPDATE BLOG CATEGORY
    //#################################################################
	function updateCategory($title, $paragraph, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $blogCatID){
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
        $result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ?", array($blogCatID));
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
		$update = $connector->query("UPDATE blog_category SET
									categoryName = ?,
                                    categoryDescription = ?,
                                    catImageTitle = ?,
                                    catImage = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE blogCatID = ?",
									array($title, $paragraph, $image_title, $imageFile, $modifiedBy, $modifiedDate, $modifiedNumber, $blogCatID));

	}

	//#################################################################
    // DELETE CATEGORY
    //#################################################################
	function deleteCategory($blogCatID){
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
        $result = $connector->query("SELECT * FROM blog_category WHERE blogCatID = ?",array($blogCatID));
        $row    = $connector->fetchArray($result);
        $catImage = $row['catImage'];

        //REMOVE IMAGES
        unlink($largeDirectory.$catImage);
        unlink($mediumDirectory.$catImage);
        unlink($smallDirectory.$catImage);

        //REMOVE USER
		$remove = $connector->query("DELETE FROM blog_category WHERE blogCatID = ?", array($blogCatID));

        //REMOVE META DETAILS
        $remove = $connector->query("DELETE FROM meta_details WHERE blogCatID = ?", array($blogCatID));

	}

	//#################################################################
    // ADD BLOG POST
    //#################################################################
	function addBlogPost($blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $blog_post_author, $categories, $affiliates, $image_title, $imageFile, $blog_post_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //CHECK IF AFFILIATE IDS IS EMPTY
        if($affiliates == ',,'){
            $affiliates = '';
        }

		//STRIP INFO
		$blog_post_title	= strip_tags($blog_post_title);
		$blog_post_intro	= strip_tags($blog_post_intro);
		$blog_post_date		= strip_tags($blog_post_date);
		$blog_post_time		= strip_tags($blog_post_time);
        $categories         = strip_tags($categories);
        $affiliates         = strip_tags($affiliates);
        $image_title        = strip_tags($image_title);
        $blog_post_url      = strip_tags($blog_post_url);

		//REMOVE SPACES IN PUBLISH TIME
		$blog_post_time = str_replace(' ', '', $blog_post_time).':00';

		//CREATE PUBLISH DATE
		$publishDate = date("Y-m-d H:i:s", strtotime($blog_post_date.' '.$blog_post_time));

		//ADD USER
		$insert = $connector->query("INSERT INTO blog_posts (blogCatID, blogPostTitle, blogPostIntro, blogPostImageFile, blogPostImageTitle, publishDate, affiliateIDs, authorID, url, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($categories, $blog_post_title, $blog_post_intro, $imageFile, $image_title, $publishDate, $affiliates, $blog_post_author, $blog_post_url, $currentUser, $currentDate));

        //GET LAST INSERTED ID
        $result = $connector->query("SELECT * FROM blog_posts ORDER BY blogPostID DESC", array());
        $row    = $connector->fetchArray($result);

        //RETURN ID
        return $row['blogPostID'];

	}

	//#################################################################
	//OVERWRITE BLOG POST
	//#################################################################
	function overwriteBlogPost($blogCatID, $blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $blog_post_author){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$blog_post_title	= strip_tags($blog_post_title);
		$blog_post_intro	= strip_tags($blog_post_intro);
		$blog_post_date		= strip_tags($blog_post_date);
		$blog_post_time		= strip_tags($blog_post_time);

		//REMOVE SPACES IN PUBLISH TIME
		$blog_post_time = str_replace(' ', '', $blog_post_time).':00';

		//CREATE PUBLISH DATE
		$publishDate = date("Y-m-d H:i:s", strtotime($blog_post_date.' '.$blog_post_time));

		//UPDATE USER
		$update = $connector->query("UPDATE blog_posts SET
									blogCatID = ?,
									blogPostIntro = ?,
									publishDate = ?,
                                    authorID = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE blogPostTitle = ?",
									array($blogCatID, $blog_post_intro, $publishDate, $blog_post_author, '0', '0000-00-00 00:00:00', $blog_post_title));

	}

	//#################################################################
    // UPDATE BLOG POST
    //#################################################################
	function updateBlogPost($blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $modifiedBy, $modifiedDate, $modifiedNumber, $blogPostID, $blog_post_author, $categories, $affiliateIDs, $imageFile, $image_title, $blog_post_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //CHECK IF AFFILIATE IDS IS EMPTY
        if($affiliateIDs == ',,'){
            $affiliateIDs = '';
        }

		//STRIP TAGS
		$blog_post_title	= strip_tags($blog_post_title);
		$blog_post_intro	= strip_tags($blog_post_intro);
		$blog_post_date		= strip_tags($blog_post_date);
		$blog_post_time		= strip_tags($blog_post_time);
        $categories		    = strip_tags($categories);
        $affiliateIDs       = strip_tags($affiliateIDs);
        $image_title        = strip_tags($image_title);
        $blog_post_url      = strip_tags($blog_post_url);

		//REMOVE SPACES IN PUBLISH TIME
		$blog_post_time = str_replace(' ', '', $blog_post_time).':00';

		//CREATE PUBLISH DATE
		$publishDate = date("Y-m-d H:i:s", strtotime($blog_post_date.' '.$blog_post_time));

		//UPDATE USER
		$update = $connector->query("UPDATE blog_posts SET
									blogPostTitle = ?,
									blogPostIntro = ?,
                                    blogPostImageFile = ?,
                                    blogPostImageTitle = ?,
									publishDate = ?,
                                    affiliateIDs = ?,
                                    blogCatID = ?,
                                    authorID = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE blogPostID = ?",
									array($blog_post_title, $blog_post_intro, $imageFile, $image_title, $publishDate, $affiliateIDs, $categories, $blog_post_author, $blog_post_url, $modifiedBy, $modifiedDate, $modifiedNumber, $blogPostID));

	}

	//#################################################################
    // DELETE BLOG POST
    //#################################################################
	function deleteBlogPost($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE blog_posts SET
									deletedBy = ?,
									deletedDate = ?
									WHERE blogPostID = ?",
									array($currentUser, $currentDate, $blogPostID));

	}

	//#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($blogPostContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
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
		$remove = $connector->query("DELETE FROM blog_post_content WHERE blogPostContentID = ?",array($blogPostContentID));

	}

    //#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($blogPostContentID, $blogPostGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ?", array($blogPostGalleryID));
		while($row	= $connector->fetchArray($result)){
            $galleryImageFile           = $row['galleryImageFile'];
            $blogPostGalleryContentID   = $row['blogPostGalleryContentID'];

    		//DELETE IMAGES
    		unlink($largeDirectory.$galleryImageFile);
    		unlink($mediumDirectory.$galleryImageFile);
    		unlink($smallDirectory.$galleryImageFile);

    		//REMOVE USER
    		$remove = $connector->query("DELETE FROM blog_post_gallery_content WHERE blogPostGalleryContentID = ?",array($blogPostGalleryContentID));
        }

        //REMOVE GALLERY ENTRIES
        $removeGallery = $connector->query("DELETE FROM blog_post_gallery WHERE blogPostGalleryID = ?",array($blogPostGalleryID));
        $removeEntry = $connector->query("DELETE FROM blog_post_content WHERE blogPostContentID = ?",array($blogPostContentID));

	}

    //#################################################################
    // DELETE GALLERY IMAGE
    //#################################################################
	function deleteGalleryImage($blogPostGalleryContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryContentID = ?", array($blogPostGalleryContentID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM blog_post_gallery_content WHERE blogPostGalleryContentID = ?",array($blogPostGalleryContentID));

	}

	//#################################################################
    // RECOVER CATEGORY
    //#################################################################
	function recoverCategory($blogCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE blog_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE blogCatID = ?",
									array('0', '0000-00-00 00:00:00', $blogCatID));

	}

    //#################################################################
    // DELETE PERMANENTLY BLOG POST
    //#################################################################
	function deletePermanentlyBlogPost($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

        //GET BLOG POST CONTENT
        $result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? ORDER BY sequence ASC", array($blogPostID));
        while ($row = $connector->fetchArray($result)) {
            $imageFile          = $row['imageFile'];
            $documentFile       = $row['documentFile'];
            $blogPostGalleryID  = $row['blogPostGalleryID'];

            //CHECK IF GALLERY IS AVAILABLE
            if($blogPostGalleryID != 0){

                //GET ALL GALLERY IMAGES
                $result2    = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ?", array($blogPostGalleryID));
                while($row2       = $connector->fetchArray($result2)){
                    $galleryImageFile   = $row2['galleryImageFile'];

                    unlink($largeDirectory.$galleryImageFile);
                }

                //DELETE BLOG POST GALLERY
                $deleteGallery   = $connector->query("DELETE FROM blog_post_gallery WHERE blogPostGalleryID = ?", array($blogPostGalleryID));

                //DELETE BLOG POST GALLERY CONTENT
                $deleteGalleryContent   = $connector->query("DELETE FROM blog_post_gallery_content WHERE blogPostGalleryID = ?", array($blogPostGalleryID));

            }else{
                //DELETE IMAGES AND DOCUMENT
                unlink($largeDirectory.$imageFile);
                unlink($mediumDirectory.$imageFile);
                unlink($smallDirectory.$imageFile);

                unlink($docDirectory.$documentFile);
            }
        }

        //DELETE BLOG POST PERMANENTLY
		$deletePost   = $connector->query("DELETE FROM blog_posts WHERE blogPostID = ?", array($blogPostID));

        //REMOVE META DETAILS
        $deletePost   = $connector->query("DELETE FROM meta_details WHERE blogPostID = ?", array($blogPostID));

	}

	//#################################################################
    // RECOVER BLOG POST
    //#################################################################
	function recoverBlogPost($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE blog_posts SET
									deletedBy = ?,
									deletedDate = ?
									WHERE blogPostID = ?",
									array('0', '0000-00-00 00:00:00', $blogPostID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM blog_category WHERE categoryName = ?", array($category_name));
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
				return 'removed_blog_category';
			}
		}

	}

	//#################################################################
    // CHECK IF BLOG POST IS ALREADY IN USE
    //#################################################################
	function addBlogPostCheck($blog_post_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK QUOTE
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostTitle = ?", array($blog_post_title));
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
				return 'removed_blog_post';
			}
		}

	}

	//#################################################################
    // CHECK IF BLOG CATEGORY IS ALREADY IN USE
    //#################################################################
	function editCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM blog_category WHERE categoryName = ?", array($category_name));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF BLOG TITLE IS ALREADY IN USE
    //#################################################################
	function editBlogPostCheck($blogPostID, $blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE SPACES IN PUBLISH TIME
		$blog_post_time = str_replace(' ', '', $blog_post_time).':00';

		//CREATE PUBLISH DATE
		$publishDate = date("Y-m-d H:i:s", strtotime($blog_post_date.' '.$blog_post_time));

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM blog_posts WHERE blogPostTitle = ? AND blogPostIntro =? AND publishDate = ? AND blogPostID != ?", array($blog_post_title, $blog_post_intro, $publishDate, $blogPostID));
		$total	= $connector->numResults($result);

		//IF BLOG POST HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

	//#################################################################
    // ADD BLOG POST PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $blogPostID){
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
		$result	= $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ? ORDER BY sequence DESC", array($blogPostID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO blog_post_content (blogPostID, paragraphTitle, paragraph, imageFile, imageTitle, documentFile, documentTitle, videoUrl, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($blogPostID, $title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE BLOG POST PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $blogPostContentID){
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
        $result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
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
        $result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostContentID = ?", array($blogPostContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE blog_post_content SET
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
                                            WHERE blogPostContentID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $modifiedNumber, $currentDate, $blogPostContentID));

	}

    //#################################################################
    // UPDATE BLOG POST GALLERY INFO
    //#################################################################
	function updateBlogPostGalleryInfo($blogPostGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM blog_post_gallery WHERE blogPostGalleryID = ?", array($blogPostGalleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE blog_post_gallery SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE blogPostGalleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $blogPostGalleryID));

	}

    //#################################################################
    // SET blogPostGalleryID AND RETURN IT
    //#################################################################
	function setBlogPostGalleryID($blogPostID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//ADD BlogPostID INTO blog_post_gallery
		$insert = $connector->query("INSERT INTO blog_post_gallery (blogPostID, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($blogPostID, $currentUser, $currentDate));

        //GET blogPostGalleryID
        $result = $connector->query("SELECT * FROM blog_post_gallery WHERE blogPostID = ? AND createdBy = ? AND createdDate = ? AND deletedBy =?", array($blogPostID, $currentUser, $currentDate, 0));
        $row    = $connector->fetchArray($result);

        //RETURN blogPostGalleryID
        return $row['blogPostGalleryID'];;
	}

    //#################################################################
    // ADD blogPostGalleryID INTO blog_post_content
    //#################################################################
	function addBlogPostGalleryIDIntoBlogPostContent($blogPostID, $blogPostGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET SEQUENCE
        $result = $connector->query("SELECT * FROM blog_post_content WHERE blogPostID = ? AND deletedBy = ? ORDER BY sequence DESC LIMIT 0,1", array($blogPostID, 0));
        $row    = $connector->fetchArray($result);
        $sequence   = $row['sequence']+1;

        //ADD blogPostGalleryID INTO blog_post_content
        $insert = $connector->query("INSERT INTO blog_post_content (blogPostID, blogPostGalleryID, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?)",
									array($blogPostID, $blogPostGalleryID, $currentUser, $currentDate, $sequence));
	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($blogPostGalleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? ORDER BY sequence DESC", array($blogPostGalleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD BlogPostID INTO BlogPostGallery
		$insert = $connector->query("INSERT INTO blog_post_gallery_content (blogPostGalleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($blogPostGalleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($blogPostGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryID = ? ORDER BY blogPostGalleryContentID ASC", array($blogPostGalleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $blogPostGalleryContentID   = $row['blogPostGalleryContentID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$blogPostGalleryContentID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$blogPostGalleryContentID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                $this->deleteGalleryImage($blogPostGalleryContentID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM blog_post_gallery_content WHERE blogPostGalleryContentID = ? AND galleryImageTitle = ?", array($blogPostGalleryContentID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE blog_post_gallery_content SET
                                                galleryImageTitle = ?
                                                WHERE blogPostGalleryContentID = ?",
                                                array($updateImageTitle, $blogPostGalleryContentID));

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
$blogManager = new blogManager();

//#################################################################
// ADD BLOG CATEGORY
//#################################################################
if(isset($_POST['add_blog_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VALUES
	$title		    = $_POST['blog-category-title'];
    $paragraph      = $_POST['paragraph'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$blog_type	    = $_POST['blog-type'];
	$image_type		= $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Blog Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($blog_type == '' && $image_type == ''){

			//CHECK IF CATEGORY NAME IS ALREADY IN USE
			$category_used = $blogManager->addCategoryCheck($title);
			if($category_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

				//INSERT USER INTO DATABASE
				$blogCatID = $blogManager->addCategory($title, $paragraph, $image_title, $imageFile);

                //GET META DETAILS
    			$keywords		= $blogManager->getMetaKeywordCategory($blogCatID);
                $description	= $blogManager->getMetaDescriptionCategory($blogCatID);

    			//UPDATE META DETAILS
    			$blogManager->updateMetaDetailsCategory($keywords, $description, $blogCatID);

                //REDIRECT USER
    			header("Location: ".$cms_root."blog-manager/crop-image-category.php?blogCatID=".$blogCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;

			}
			//IF USER HAS BEEN REMOVED
			elseif($category_used == 'removed_blog_category'){
				//SET USER AS REMOVED
				$removed_blog_category = '1';
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
	$category_name		= $_POST['category-name'];

	//HONEY POTS
	$category_type		= $_POST['category-type'];

	if($category_type == ''){

		//OVERWRITE USER
		$blogManager->overwriteCategory($category_name);

		//REDIRECT PAGE
		header("Location: ".$cms_root."blog-manager/index.php?message=8");
		exit;
	}
}

//#################################################################
// EDIT CATEGORY
//#################################################################
if(isset($_POST['edit_blog_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VALUES
	$title          = $_POST['blog-category-title'];
    $paragraph      = $_POST['paragraph'];
	$blogCatID     	= $_POST['blogCatID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$blog_type      = $_POST['blog-type'];
    $image_type     = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($title, 'Blog Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($blog_type == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($blogManager->checkCategoryChanges($title, $paragraph, $image_title, $blogCatID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

				//CHECK CATEGORY IS USED
				$category_used = $blogManager->editCategoryCheck($category_name);
				if($category_used == 'unused'){

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
    				$blogManager->updateCategory($title, $paragraph, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $blogCatID);

                    //GET META DETAILS
        			$keywords		= $blogManager->getMetaKeywordCategory($blogCatID);
                    $description	= $blogManager->getMetaDescriptionCategory($blogCatID);

        			//UPDATE META DETAILS
        			$blogManager->updateMetaDetailsCategory($keywords, $description, $blogCatID);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."blog-manager/crop-image-category.php?blogCatID=".$blogCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=3");
                		exit;
                    }else{
                        header("Location: ".$cms_root."blog-manager/manage-blog-category.php?message=3");
                		exit;
                    }

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
				header("Location: ".$cms_root."blog-manager/manage-blog-category.php");
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
//DELETE CATEGORY
//#################################################################
if(isset($_POST['delete_blog_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $blogCatID	= $_POST['blogCatID'];

    //SET USER AS REMOVED IN DATABASE
    $blogManager->deleteCategory($blogCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/manage-blog-category.php?message=5");
    exit;
}

//#################################################################
//RECOVER CATEGORY
//#################################################################
if(isset($_POST['recover_blog_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $blogCatID	= $_POST['blogCatID'];

    //SET USER AS REMOVED IN DATABASE
    $blogManager->recoverCategory($blogCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/index.php?message=7");
    exit;
}

//#################################################################
// ADD BLOG POST
//#################################################################
if(isset($_POST['add_blog_post'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$blog_post_title 		= $_POST['blog-post-title'];
	$blog_post_intro		= $_POST['paragraph'];
	$blog_post_date			= $_POST['blog-post-date'];
	$blog_post_time			= $_POST['blog-post-time'];
    $affiliates             = $_POST['affiliates'];
    $blog_post_author       = $_POST['blog-post-author'];
    $categories             = $_POST['categories'];
    $image_title	        = $_POST['image-title'];

	//HONEY POTS
	$blog_post_paragraph	= $_POST['blog-post-paragraph'];
	$blog_post_date2		= $_POST['blog-post-date2'];
    $image_type		        = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $blog_post_title    = $userLogin->specialCharactersToHTMLEntity($blog_post_title);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($blog_post_title, 'Blog Post Title', 2, 100);
	$v->validateText($blog_post_intro, 'Intro', 10);
	$v->validateDate($blog_post_date, 'Publish Date');
	$v->validateTime($blog_post_time, 'Publish Time');

    //IF AN AFFILIATE HAS BEEN ADDED
    if($affiliates != ''){
        $v->validateTags($affiliates, 'Affiliate Links:');
    }

    $v->validateTags($categories, 'Blog Categories');
    $v->validateDropDown($blog_post_author, 'Author');
    $v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){


		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($blog_post_paragraph == '' && $blog_post_date2 == '' && $image_type == ''){

            //GET ALL BLOG CATEGORY ID'S
            $blogCatIDs         = ',';
            $categories         = substr($categories, 1, -1);
            $blogCatNameArray   = explode(',', $categories);
            foreach($blogCatNameArray as $blogCatName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM blog_category WHERE categoryName = ? AND deletedBy = ?", array($blogCatName, '0'));
                $row    = $connector->fetchArray($result);
                $blogCatID  = $row['blogCatID'];

                //INSERT INTO STRING
                $blogCatIDs.= $blogCatID.',';
            }

            //GET ALL AFFILIATE ID'S
            $affiliateIDs           = ',';
            $affiliates             = substr($affiliates, 1, -1);
            $affiliateNamesArray    = explode(',', $affiliates);
            foreach($affiliateNamesArray as $affiliateName){
                //GET ID FOR AFFILIATE
                $result = $connector->query("SELECT * FROM affiliate WHERE affTitle = ? AND deletedBy = ?", array($affiliateName, '0'));
                $row    = $connector->fetchArray($result);
                $affiliateID  = $row['affiliateID'];

                //INSERT INTO STRING
                $affiliateIDs.= $affiliateID.',';
            }

			//CHECK IF BLOG POST IS ALREADY IN USE
			$blog_post_used = $blogManager->addBlogPostCheck($blog_post_title);
			if($blog_post_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //CREATE BLOG POST URL
        		$blog_post_url = str_replace("'", "", $blog_post_title);
        		$blog_post_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($blog_post_url));
        		$blog_post_url = str_replace(' ', '-', $blog_post_url);

                //CHECK IF BLOG POST URL EXISTS
                $blog_post_url = $blogManager->checkBlogURLExists($blog_post_url, '');

				//INSERT BLOG POST INTO DATABASE
				$blogPostID = $blogManager->addBlogPost($blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $blog_post_author, $blogCatIDs, $affiliateIDs, $image_title, $imageFile, $blog_post_url);

                //GET META DETAILS
                $keywords		= $blogManager->getMetaKeyword($blogPostID);
                $description	= $blogManager->getMetaDescription($blogPostID);

                //UPDATE META DETAILS
                $blogManager->updateMetaDetails($keywords, $description, $blogPostID);

                //ADD INFORMATION INTO SEARCH INDEX
                $blogManager->addBlogPostSearchIndex($blogPostID, $blog_post_title, $keywords, $blog_post_intro);

                //REDIRECT USER
    			header("Location: ".$cms_root."blog-manager/crop-image-post.php?blogPostID=".$blogPostID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;

			}
			//IF USER HAS BEEN REMOVED
			elseif($blog_post_used == 'removed_blog_post'){

				//SET BLOG POST AS REMOVED
				$removed_blog_post = '1';
			}
			else{

				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Blog Post Title</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT BLOG POST
//#################################################################
if(isset($_POST['edit_blog_post'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$blogPostID				= $_POST['blogPostID'];
	$blog_post_title 		= $_POST['blog-post-title'];
	$blog_post_intro		= $_POST['paragraph'];
	$blog_post_date			= $_POST['blog-post-date'];
	$blog_post_time			= $_POST['blog-post-time'];
    $blog_post_author       = $_POST['blog-post-author'];
    $categories             = $_POST['categories'];
    $affiliates             = $_POST['affiliates'];
    $oldImage               = $_POST['oldImage'];
    $image_title            = $_POST['image-title'];

	$modifiedDate			= $_POST['modifiedDate'];
	$modifiedBy				= $_SESSION['cmsUser'];
	$modifiedNumber			= $_POST['modifiedNumber'];

	//HONEY POTS
	$blog_post_paragraph	= $_POST['blog-post-paragraph'];
	$blog_post_date2		= $_POST['blog-post-date2'];
    $image_type             = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $blog_post_title    = $userLogin->specialCharactersToHTMLEntity($blog_post_title);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($blog_post_title, 'Blog Post Title', 2, 100);
	$v->validateText($blog_post_intro, 'Intro', 10);
	$v->validateDate($blog_post_date, 'Publish Date');
	$v->validateTime($blog_post_time, 'Publish Time');
    $v->validateTags($categories, 'Blog Categories');

    //IF AN AFFILIATE HAS BEEN ADDED
    if($affiliates != ''){
        $v->validateTags($affiliates, 'Affiliate Links:');
    }

    $v->validateDropDown($blog_post_author, 'Author');
    $v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($blog_post_paragraph == '' && $blog_post_date2 == '' && $image_type == ''){

            //GET ALL BLOG CATEGORY ID'S
            $blogCatIDs         = ',';
            $categories         = substr($categories, 1, -1);
            $blogCatNameArray   = explode(',', $categories);
            foreach($blogCatNameArray as $blogCatName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM blog_category WHERE categoryName = ? AND deletedBy = ?", array($blogCatName, '0'));
                $row    = $connector->fetchArray($result);
                $blogCatID  = $row['blogCatID'];

                //INSERT INTO STRING
                $blogCatIDs.= $blogCatID.',';
            }

            //GET ALL AFFILIATE ID'S
            $affiliateIDs           = ',';
            $affiliates             = substr($affiliates, 1, -1);
            $affiliateNamesArray    = explode(',', $affiliates);
            foreach($affiliateNamesArray as $affiliateName){
                //GET ID FOR AFFILIATE
                $result = $connector->query("SELECT * FROM affiliate WHERE affTitle = ? AND deletedBy = ?", array($affiliateName, '0'));
                $row    = $connector->fetchArray($result);
                $affiliateID  = $row['affiliateID'];

                //INSERT INTO STRING
                $affiliateIDs.= $affiliateID.',';
            }

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($blogManager->checkBlogPostChanges($blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $blogPostID, $blog_post_author, $blogCatIDs, $affiliateIDs, $image_title) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

				//CHECK TITLE IS USED
				$blog_post_used = $blogManager->editBlogPostCheck($blogPostID, $blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time);
				if($blog_post_used == 'unused'){

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

                    //GENERATE BLOG POST URL
                    $blog_post_url = str_replace("'", "", $blog_post_title);
                    $blog_post_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($blog_post_url));
            		$blog_post_url = str_replace(' ', '-', $blog_post_url);

                    //CHECK IF BLOG POST URL EXISTS
                    $blog_post_url = $blogManager->checkBlogURLExists($blog_post_url, $blogPostID);

					//UPDATE USER IN DATABASE
					$blogManager->updateBlogPost($blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $modifiedBy, $modifiedDate, $modifiedNumber, $blogPostID, $blog_post_author, $blogCatIDs, $affiliateIDs, $imageFile, $image_title, $blog_post_url);

                    //GET META DETAILS
        			$keywords		= $blogManager->getMetaKeyword($blogPostID);
        			$description	= $blogManager->getMetaDescription($blogPostID);

        			//UPDATE META DETAILS
        			$blogManager->updateMetaDetails($keywords, $description, $blogPostID);

                    //ADD INFORMATION INTO SEARCH INDEX
                    $blogManager->addBlogPostSearchIndex($blogPostID, $blog_post_title, $keywords, $blog_post_intro);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."blog-manager/crop-image-post.php?blogPostID=".$blogPostID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
                		exit;
                    }else{
                        header("Location: ".$cms_root."blog-manager/index.php?message=4");
                		exit;
                    }

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Blog Post Title</b> you supplied is already in use. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."blog-manager/index.php");
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
// REACTIVATE BLOG POST
//#################################################################
if(isset($_POST['reactivate-blog-post-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$blogCatID				= $_POST['blogCatID'];
	$blog_post_title		= $_POST['blog-post-title'];
	$blog_post_intro		= $_POST['blog-post-intro'];
	$blog_post_date			= $_POST['blog-post-date'];
	$blog_post_time			= $_POST['blog-post-time'];
    $blog_post_author       = $_POST['blog-post-author'];

	//HONEY POTS
	$blog_post_paragraph	= $_POST['blog-post-paragraph'];
	$blog_post_date2		= $_POST['blog-post-date2'];

	if($blog_post_paragraph == '' && $blog_post_date2 == ''){

		//OVERWRITE USER
		$blogManager->overwriteBlogPost($blogCatID, $blog_post_title, $blog_post_intro, $blog_post_date, $blog_post_time, $blog_post_author);

		//REDIRECT PAGE
		header("Location: ".$cms_root."blog-manager/manage-blog-category-content.php?blogCatID=".$blogCatID."&message=10");
		exit;
	}
}

//#################################################################
//DELETE BLOG POST
//#################################################################
if(isset($_POST['delete_blog_post'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$blogPostID	= $_POST['blogPostID'];

    //SET USER AS REMOVED IN DATABASE
    $blogManager->deleteBlogPost($blogPostID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/index.php?message=6");
    exit;
}

//#################################################################
//DELETE PERMANENTLY BLOG POST
//#################################################################
if(isset($_POST['delete_permanently_blog_post'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $blogPostID	= $_POST['blogPostID'];

    //SET USER AS REMOVED IN DATABASE
    $blogManager->deletePermanentlyBlogPost($blogPostID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/index.php?message=14");
    exit;
}

//#################################################################
//RECOVER BLOG POST
//#################################################################
if(isset($_POST['recover_blog_post'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $blogPostID	= $_POST['blogPostID'];

    //SET USER AS REMOVED IN DATABASE
    $blogManager->recoverBlogPost($blogPostID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/index.php?message=9");
    exit;
}

//#################################################################
// ADD BLOG POST PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$blogCatID		= $_POST['blogCatID'];
	$blogPostID		= $_POST['blogPostID'];
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
			$blogManager->addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $blogPostID);

			//GET META DETAILS
			$keywords		= $blogManager->getMetaKeyword($blogPostID);
			$description	= $blogManager->getMetaDescription($blogPostID);

			//UPDATE META DETAILS
			$blogManager->updateMetaDetails($keywords, $description, $blogPostID);

            //GET BLOG POST INFO
            $blogPostTitle  = $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle');
            $blogPostIntro  = $blogManager->getBlogPostInfo($blogPostID, 'blogPostIntro');

            //ADD INFORMATION INTO SEARCH INDEX
            $blogManager->addBlogPostSearchIndex($blogPostID, $blogPostTitle, $keywords, $blogPostIntro);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."blog-manager/crop-image.php?blogPostID=".$blogPostID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=11");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&message=11");
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
// EDIT BLOG POST PARAGRAPH
//#################################################################
if(isset($_POST['edit_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$blogCatID		   = $_POST['blogCatID'];
	$blogPostID        = $_POST['blogPostID'];
    $blogPostContentID = $_POST['blogPostContentID'];
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
                    $image_title    = $blogManager->getBlogPostContentInfo($blogPostContentID, 'imageTitle');
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
                $doc_title      = $blogManager->getBlogPostContentInfo($blogPostContentID, 'documentTitle');
            }

            //CHECK IF VIDEO NEEDS TO BE REMOVED
            if($removeVideo == 1){
                $video = '';
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT BLOG POST INTO DATABASE
			$blogManager->updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $blogPostContentID);

			//GET META DETAILS
			$keywords		= $blogManager->getMetaKeyword($blogPostID);
			$description	= $blogManager->getMetaDescription($blogPostID);

			//UPDATE META DETAILS
			$blogManager->updateMetaDetails($keywords, $description, $blogPostID);

            //GET BLOG POST INFO
            $blogPostTitle  = $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle');
            $blogPostIntro  = $blogManager->getBlogPostInfo($blogPostID, 'blogPostIntro');

            //ADD INFORMATION INTO SEARCH INDEX
            $blogManager->addBlogPostSearchIndex($blogPostID, $blogPostTitle, $keywords, $blogPostIntro);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."blog-manager/crop-image.php?blogPostID=".$blogPostID."&blogCatID=".$blogCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=12");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&blogCatID=".$blogCatID."&message=12");
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
	$blogPostContentID	= $_POST['blogPostContentID'];
	$blogPostID			= $_POST['blogPostID'];

    //SET USER AS REMOVED IN DATABASE
    $blogManager->deleteParagraph($blogPostContentID);

    //GET META DETAILS
    $keywords		= $blogManager->getMetaKeyword($blogPostID);

    //GET RESOURCE INFO
    $blogPostTitle  = $blogManager->getBlogPostInfo($blogPostID, 'blogPostTitle');
    $blogPostIntro  = $blogManager->getBlogPostInfo($blogPostID, 'blogPostIntro');

    //ADD INFORMATION INTO SEARCH INDEX
    $blogManager->addBlogPostSearchIndex($blogPostID, $blogPostTitle, $keywords, $blogPostIntro);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&message=13");
    exit;
}

//#################################################################
//DELETE GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$blogPostContentID  = $_POST['blogPostContentID'];
	$blogPostGalleryID  = $_POST['blogPostGalleryID'];
    $blogPostID         = $_POST['blogPostID'];

    //REMOVE GALLERY FROM DATABASE
    $blogManager->deleteGallery($blogPostContentID, $blogPostGalleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&message=17");
    exit;
}

//#################################################################
//ADD GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $blogCatID      = $_POST['blogCatID'];
    $blogPostID     = $_POST['blogPostID'];
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

            //GET BlogPostGalleryID
            $blogPostGalleryID  = $blogManager->setBlogPostGalleryID($blogPostID);

            //ADD blogPostGalleryID INTO blog_post_content
            $blogManager->addBlogPostGalleryIDIntoBlogPostContent($blogPostID, $blogPostGalleryID);

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
                        $blogManager->addGalleryImages($blogPostGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $hasImages = 1;

                    }

                    $count++;
                }
            }

            //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
            if($hasImages == 1){
                header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&blogCatID=".$blogCatID."&message=15");
        		exit;
            }else{
                header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&blogCatID=".$blogCatID);
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
    $blogCatID          = $_POST['blogCatID'];
    $blogPostID         = $_POST['blogPostID'];
    $blogPostGalleryID  = $_POST['blogPostGalleryID'];
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
        $updatedGalleryImages = $blogManager->updateRemoveGalleryImages($blogPostGalleryID);

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
                        $blogManager->addGalleryImages($blogPostGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $blogManager->updateBlogPostGalleryInfo($blogPostGalleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&blogCatID=".$blogCatID."&message=16");
    		exit;
        }else{
            header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&blogCatID=".$blogCatID);
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
if($categoryRatio == 1){
    $newWidth		= 350;
    $newHeight		= 190;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($postRatio == 1){
    $newWidth		= 770;
    $newHeight		= 328;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($normalRatio == 1){
    $newWidth		= 400;
    $newHeight		= 250;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$blogPostID			= $_POST['blogPostID'];
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
	header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&message=".$message);
    exit;
}

//CROP BLOG POST IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-post'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$blogPostID			= $_POST['blogPostID'];
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
	header("Location: ".$cms_root."blog-manager/manage-blog-post.php?blogPostID=".$blogPostID."&message=".$message);
    exit;
}

//CROP IMAGE FOR CATEGORY WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-category'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$blogCatID		    = $_POST['blogCatID'];
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
	header("Location: ".$cms_root."blog-manager/manage-blog-category.php?message=".$message);
    exit;
}
###################################################################
?>
