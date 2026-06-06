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

class productManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Product has successfully been added.'; break;
            case 3: $displayMessage = 'The selected Category has successfully been updated.'; break;
			case 4: $displayMessage = 'The selected Product has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Category has successfully been removed.'; break;
			case 6: $displayMessage = 'The selected Product has successfully been removed.'; break;
			case 7: $displayMessage = 'The selected Category has successfully been recovered.'; break;
			case 8: $displayMessage = 'The selected Category has successfully been re-activated.'; break;
			case 9: $displayMessage = 'The selected Product has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Product has successfully been re-activated.'; break;
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
	function getMetaKeyword($productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ? ORDER BY sequence ASC", array($productID, 0));
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
	function getMetaDescription($productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ? ORDER BY sequence ASC", array($productID, 0));
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
	function updateMetaDetails($keywords, $description, $productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE productID = ?", array($productID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (productID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($productID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE productID = ?",
												array($keywords, $description, $productID));
		}
	}

    //#################################################################
    // GET META KEYWORDS FOR CATEGORY
    //#################################################################
	function getMetaKeywordCategory($productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM product_category WHERE productCatID = ? AND deletedBy = ?", array($productCatID, 0));
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
	function getMetaDescriptionCategory($productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM product_category WHERE productCatID = ? AND deletedBy = ?", array($productCatID, 0));
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
	function updateMetaDetailsCategory($keywords, $description, $productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE productCatID = ?", array($productCatID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (productCatID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($productCatID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE productCatID = ?",
												array($keywords, $description, $productCatID));
		}
	}

    //#################################################################
	//CHECK CATEGORY URL EXISTS
	//#################################################################
	function checkCategoryURLExists($url, $productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED BY TOUR
        $currentURL = $this->getCategoryInfo($productCatID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM product_category WHERE url = ? LIMIT 0,1", array($url));
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
                    $result2    = $connector->query("SELECT url FROM product_category WHERE url = ? LIMIT 0,1", array($newURL));
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
	// ADD PRODUCT INTO SEARCH INDEX
	//#################################################################
	function addProductSearchIndex($productID, $title, $keywords, $product_intro, $product_code, $categories){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//GET INDEX INFO
		$result	= $connector->query("SELECT * FROM search_index WHERE productID = ?", array($productID));
		$row	= $connector->fetchArray($result);
		$total	= $connector->numResults($result);

		//CHECK IF PRODUCT IS ALREADY INDEX
		if($total == 0){
            if($categories != '' && $categories != '' && $product_code != '' && $product_code != ' '){
                //INSERT PRODUCT SEARCH INDEX
                $insert	= $connector->query("INSERT INTO search_index (title, keywords, content, productID, productCode, categories)
                                            VALUES(?, ?, ?, ?, ?, ?)"
                                            , array($title, $keywords, $product_intro, $productID, $product_code, $categories));
            }elseif($categories != '' && $categories != ' '){
                //INSERT PRODUCT SEARCH INDEX
                $insert	= $connector->query("INSERT INTO search_index (title, keywords, content, productID, categories)
                                            VALUES(?, ?, ?, ?, ?)"
                                            , array($title, $keywords, $product_intro, $productID, $categories));
            }elseif($product_code != '' && $product_code != ' '){
                //INSERT PRODUCT SEARCH INDEX
                $insert	= $connector->query("INSERT INTO search_index (title, keywords, content, productID, productCode)
                                            VALUES(?, ?, ?, ?, ?)"
                                            , array($title, $keywords, $product_intro, $productID, $product_code));
            }else{
                //INSERT PRODUCT SEARCH INDEX
                $insert	= $connector->query("INSERT INTO search_index (title, keywords, content, productID)
                                            VALUES(?, ?, ?, ?)"
                                            , array($title, $keywords, $product_intro, $productID));
            }
		}else{
            if($categories != '' && $categories != '' && $product_code != '' && $product_code != ' '){
                //UPDATE PRODUCT SEARCH INDEX
    			$update	= $connector->query("UPDATE search_index SET
    										title			= ?,
    										keywords		= ?,
    										content			= ?,
                                            categories      = ?,
                                            productCode     = ?
    										WHERE productID = ?"
    										, array($title, $keywords, $product_intro, $categories, $product_code, $productID));
            }
            elseif($categories != '' && $categories != ' '){
                //UPDATE PRODUCT SEARCH INDEX
    			$update	= $connector->query("UPDATE search_index SET
    										title			= ?,
    										keywords		= ?,
    										content			= ?,
                                            categories      = ?
    										WHERE productID = ?"
    										, array($title, $keywords, $product_intro, $categories, $productID));
            }elseif($product_code != '' && $product_code != ' '){
                //UPDATE PRODUCT SEARCH INDEX
    			$update	= $connector->query("UPDATE search_index SET
    										title			= ?,
    										keywords		= ?,
    										content			= ?,
                                            productCode     = ?
    										WHERE productID = ?"
    										, array($title, $keywords, $product_intro, $product_code, $productID));
            }else{
    			//UPDATE PRODUCT SEARCH INDEX
    			$update	= $connector->query("UPDATE search_index SET
    										title			= ?,
    										keywords		= ?,
    										content			= ?
    										WHERE productID = ?"
    										, array($title, $keywords, $product_intro, $productID));
            }
		}

	}

    //#################################################################
	// REMOVE PRODUCT FROM SEARCH INDEX
	//#################################################################
	function removeProductSearchIndex($productID){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//DELETE PRODUCT
        $connector->query('DELETE FROM search_index WHERE productID = ?', array($productID));

	}

    //#################################################################
    // CHECK IF A MAIN CATEGORY HAS ALREADY BEEN ADDED
    //#################################################################
	function checkMainCategoryAdded(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORIES
		$result = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID = ?", array(0, 0));
		$total	= $connector->numResults($result);

        //RETURN TOTAL
		return $total;
	}

    //#################################################################
    // CHECK IF A CATEGORY HAS ALREADY BEEN ADDED
    //#################################################################
	function checkCategoryAdded(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORIES
		$result = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID != ?", array(0, 0));
		$total	= $connector->numResults($result);

        //RETURN TOTAL
		return $total;
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
		return $this->HTMLEntityToSpecialCharacters($row[$field]);

	}

    //#################################################################
    // GET PARAGRAPH INFORMATION
    //#################################################################
	function getParagraphInfo($productContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET PARAGRAPH INFO
		$result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET PRODUCT IMAGE
    //#################################################################
	function getProductImage($productID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product WHERE productID = ?", array($productID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['productImageFile'];
		$imageTitle	= $row['productImageTitle'];

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
	function getGalleryInfo($productGalleryID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_gallery WHERE productGalleryID = ?", array($productGalleryID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET PRODUCT INFORMATION
    //#################################################################
	function getProductInfo($productID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product WHERE productID = ?", array($productID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $this->HTMLEntityToSpecialCharacters($row[$field]);

	}

    //#################################################################
    // GET PRODUCT CATEGORIES
    //#################################################################
	function getProductCategories($currentProductCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET ALL PRODUCT CATEGORIES
		$result = $connector->query("SELECT productCatID, categoryName FROM product_category WHERE deletedBy = ? AND productMainCatID = ? ORDER BY categoryName ASC", array(0, 0));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $productCatID   = $row['productCatID'];
            $categoryName   = $this->HTMLEntityToSpecialCharacters($row['categoryName']);

            //GENERATE OUTPUT
            if($productCatID == $currentProductCatID){
                $txt.= '<option value="'.$productCatID.'" selected="selected">'.$categoryName.'</option>';
            }else{
                $txt.= '<option value="'.$productCatID.'">'.$categoryName.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET PRODUCT CATEGORIES FOR PRODUCT
    //#################################################################
	function getProductCategoriesForProduct($currentProductCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET ALL PRODUCT CATEGORIES
		$result = $connector->query("SELECT productCatID, categoryName FROM product_category WHERE deletedBy = ? AND productMainCatID = ? ORDER BY categoryName ASC", array(0, 0));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $productCatID   = $row['productCatID'];
            $categoryName   = $row['categoryName'];

            //CHECK IF SUB CATEGORIES ARE AVAILABLE
            $result2    = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID = ? LIMIT 0,1", array(0, $productCatID));
            $totalSub   = $connector->numResults($result2);

            //SUB CATEGORIES AVAILABLE
            if($totalSub != 0){
                //GENERATE OUTPUT
                if($productCatID == $currentProductCatID){
                    $txt.= '<option value="'.$productCatID.'" selected="selected">'.$categoryName.'</option>';
                }else{
                    $txt.= '<option value="'.$productCatID.'">'.$categoryName.'</option>';
                }
            }
        }

		//RETURN OUTPUT
		return $txt;

	}

	//#################################################################
    // GET PRODUCT CONTENT INFORMATION
    //#################################################################
	function getProductContentInfo($productContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET PRODUCT CONTENT INFO
		$result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // GET PRODUCT CONTENT VIDEO
    //#################################################################
	function getProductContentVideo($productContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
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
    // GET PRODUCT CONTENT DOCUMENT
    //#################################################################
	function getProductContentDocument($productContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
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
    // GET PRODUCT CONTENT IMAGE
    //#################################################################
	function getProductContentImage($productContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
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
		$result = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID = ? ORDER BY categoryName ASC", array(0, 0));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $productCatID   = $row['productCatID'];
            $categoryName   = $row['categoryName'];

            //GET ALL SUB CATEGORIES
            $result2    = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID = ? ORDER BY categoryName ASC", array(0, $productCatID));
            $totalSub   = $connector->numResults($result2);

            //IF SUB CATEGORIES ARE AVAILABLE
            if($totalSub != 0){
                while($row2 = $connector->fetchArray($result2)){
                    //SET VARIABLES
                    $subCategoryName    = $row2['categoryName'];

                    //GENERATE OUTPUT
                    $txt.= '"'.$categoryName.' - '.$subCategoryName.'",';
                }
            }

		}

        //REUTN OUTPUT
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
	function getProductTags($productID, $field){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM product WHERE productID = ?", array($productID));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLE
            $tagHolder = $row[$field];

            //REMOVE FIRST AND LAST CHARACTER
            $tagString = substr($tagHolder, 1,-1);

            //TURN INTO ARRAY
            $tagArray = explode(",", $tagString);

            //LOOP THROUGH ARRAY
            foreach($tagArray as $tags){
                //GET NAME OF CATEGORY ID
                $result2    = $connector->query("SELECT * FROM product_category WHERE productCatID = ? AND deletedBy = ?", array($tags, '0'));
                $row2       = $connector->fetchArray($result2);

                //SET VARIABLES
                $subMainCatID       = $row2['productMainCatID'];
                $subcategoryName    = $this->specialCharactersToHTMLEntity($row2['categoryName']);

                //GET MAIN CATEGORY NAME
                $result3    = $connector->query("SELECT * FROM product_category WHERE productCatID = ? AND deletedBy = ?", array($subMainCatID, '0'));
                $row3       = $connector->fetchArray($result3);

                //SET VARIABLES
                $categoryName   = $this->specialCharactersToHTMLEntity($row3['categoryName']);

                //GENERATE OUTPUT
                $txt.= '<li>'.$categoryName.' - '.$subcategoryName.'</li>';
            }
        }

        //RETURN OUTPUT
        return $txt;
	}

    //#################################################################
    // GET PRODUCT GALLERY IMAGES
    //#################################################################
	function getProductGalleryImages($productGalleryID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $count = 1;

		//GET PRODUCT GALLERY INFO
		$result = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? ORDER BY productGalleryContentID ASC", array($productGalleryID));
		while($row	= $connector->fetchArray($result)){
            $productGalleryContentID    = $row['productGalleryContentID'];
            $galleryImageFile           = $row['galleryImageFile'];
            $galleryImageTitle          = $row['galleryImageTitle'];

            $txt.= '<div class="uploader_image_shade" id="img'.$productGalleryContentID.'">
                <div class="preview-images" style="background-image: url('.$web_root.'cms-images/medium/'.$galleryImageFile.');"></div>
                <div class="remove_gallery_image">
                    <input type="checkbox" name="remove_gallery_image_'.$productGalleryContentID.'" value="1" />
                    <div class="remove_gallery_image_text">Remove Image</div>
                </div>
                <div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageGalleryTitle_'.$productGalleryContentID.'" value="'.$galleryImageTitle.'" maxlength="150"><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div>
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
    // CHECK IF PRODUCT IS IN DATABASE
    //#################################################################
	function checkProductDatabase($productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM product WHERE productID = ?", array($productID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF PRODUCT GALLERY IS IN DATABASE
    //#################################################################
	function checkProductGalleryDatabase($productGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM product_gallery WHERE productGalleryID = ? ", array($productGalleryID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF PRODUCT CONTENT IS IN DATABASE
    //#################################################################
	function checkProductContentDatabase($productID, $productContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM product_content WHERE productID = ? AND productContentID = ?", array($productID, $productContentID));
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
    // GET TOTAL PRODUCT CATEGORIES
    //#################################################################
	function getTotalCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID = ?", array('0', '0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL PRODUCT SUB CATEGORIES
    //#################################################################
	function getTotalSubCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID != ?", array('0', '0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL PRODUCTS
    //#################################################################
	function getTotalProducts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET EMPTY PRODUCTS
    //#################################################################
	function getEmptyProducts(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM product WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$productID	= $row['productID'];

			//GET ALL CONTENT FOR PRODUCTS
			$result2	= $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ?", array($productID, '0'));
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
    // PRODUCT CONTENT ARCHITECTURE
    //#################################################################
	function categoryArchitecture($cms_root, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM product_category WHERE deletedBy = ? AND productMainCatID = ? ORDER BY categoryName ASC", array('0', '0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$productCatID       = $row['productCatID'];
				$categoryName		= $this->HTMLEntityToSpecialCharacters($row['categoryName']);
				$catImageTitle  	= $row['catImageTitle'];
				$catImage		    = $row['catImage'];
                $paragraph          = strip_tags($row['categoryDescription']);

                //GET PLAYLIST INFO
                $result2        = $connector->query("SELECT * FROM product WHERE productMainCatID LIKE ?",array("%,$productCatID,%"));
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
						$txt.='<form name="delete_product_category'.$productCatID.'">
                        <input type="hidden" name="delete_product_category" value="1">
                        <input type="hidden" name="productCatID" value="'.$productCatID.'">
							<a href="javascript:deleteProductCategory('.$productCatID.')" title="Remove Category">Remove Category</a>
						</form>';
                    }else{
                        $txt.='<a href="javascript:noDeleteCategory()" title="Remove Category">Remove Category</a>';
                    }

                    $txt.= '<a href="'.$cms_root.'product-manager/edit-product-category.php?productCatID='.$productCatID.'" title="Edit Category">Edit Category</a>
						<div class="clear"></div>
						</div>
                </div>';

                //GET ALL SUB CATEGORIES
                $result2    = $connector->query("SELECT * FROM product_category WHERE productMainCatID = ? AND deletedBy = ? ORDER BY categoryName ASC", array($productCatID, '0'));
                while($row2 = $connector->fetchArray($result2)){
                    //SET VARIABLES
                    $subProductCatID        = $row2['productCatID'];
                    $subCategoryName        = $row2['categoryName'];
                    $subcategoryDescription = strip_tags($row2['categoryDescription']);
                    $subCatImageTitle       = $row2['catImageTitle'];
                    $subCatImage            = $row2['catImage'];

                    //GET PLAYLIST INFO
                    $result3        = $connector->query("SELECT * FROM product WHERE productCatID LIKE ?",array("%,$subProductCatID,%"));
                    $totalResults   = $connector->numResults($result3);

                    //GENERATE OUPUT
    				$txt.= '<div class="module-manage-content-holder sub-module-manage-content-holder">';

    					//IF AN IMAGE IS AVAILABLE
    					if($subCatImage != ''){
    						$txt.= '<div class="paragraph-image-category">
    							<img src="'.$web_root.'cms-images/large/'.$subCatImage.'" alt="'.$subCatImageTitle.'" title="'.$subCatImageTitle.'" border="0"/>
    						</div>';
    					}

    					//IF A TITLE IS AVAILABLE
    					if($subCategoryName != ''){
                    		$txt.= '<div class="paragraph-title"><b>'.$subCategoryName.'</b></div>';
    					}

                        $txt.= '<div class="paragraph-text">'.$subcategoryDescription.'</div>
                                <div class="clear"></div>';

    					$txt.= '<div class="module-manage-content-links">';

                        //TELL IF CATEGORY CAN BE DELETED
                        if($totalResults == 0){
    						$txt.='<form name="delete_product_category'.$subProductCatID.'">
                            <input type="hidden" name="delete_product_category" value="1">
                            <input type="hidden" name="productCatID" value="'.$subProductCatID.'">
    							<a href="javascript:deleteProductCategory('.$subProductCatID.')" title="Remove Sub Category">Remove Sub Category</a>
    						</form>';
                        }else{
                            $txt.='<a href="javascript:noDeleteSubCategory()" title="Remove Sub Category">Remove Sub Category</a>';
                        }

                        $txt.= '<a href="'.$cms_root.'product-manager/edit-sub-product-category.php?productCatID='.$subProductCatID.'" title="Edit Sub Category">Edit Sub Category</a>
    						<div class="clear"></div>
    						</div>
                    </div>';
                }

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Categories available. <a href="'.$cms_root.'product-manager/add-product-category.php" title="Add Product Category">Please add a category here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // PRODUCT ARCHITECTURE
    //#################################################################
	function productArchitecture($limit, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL PRODUCTS
		$result = $connector->query("SELECT * FROM product WHERE deletedBy = ? ORDER BY productTitle ASC LIMIT 0, $limit", array('0',));
		$productTotal = $connector->numResults($result);

		//IF PRODUCTS ARE AVAILABLE
		if($productTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
                $specialText    = '';
                $categoryString = '';
				$status			= '';
				$status_bg		= '';
				$date			= '';
				$currentDate	= date('Y-m-d H:i:s');
				$productID		= $row['productID'];
				$productTitle	= $this->HTMLEntityToSpecialCharacters($row['productTitle']);
				$productCatID	= $row['productCatID'];
                $productSpecial = $row['productSpecial'];

                //CHECK IF PRODUCT IS ON SPECIAL
                if($productSpecial == 1){
                    $specialText = '(Special)';
                }

                //TURN INTO ARRAY
                $productCatIDString = substr($productCatID, 1, -1);
                $productCatIDArray  = explode(',', $productCatIDString);

                //GET ALL PRODUCT CATEGORY NAMES
                foreach($productCatIDArray AS $productCatIDs){
                    //GET CATEGORY NAME
                    $categoryString.= $this->getCategoryInfo($productCatIDs, 'categoryName').', ';
                }

                //CLEAN UP CATEGORY STRING
                $categoryString = substr($categoryString, 0, -2);

				//GET ALL PRODUCT CONTENT FOR A PRODUCT
				$result2	= $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ?", array($productID, '0'));
				$productContentTotal	= $connector->numResults($result2);

				//IF PRODUCT IS EMPTY
				if($productContentTotal == 0){
					$status		= '<span class="empty-category-text">(Empty)</span>';
					$status_bg	='class="empty-category"';
				}

				//GENERATE OUPUT
				$txt.= '<tr>
					<td '.$status_bg.'>'.$productTitle.' '.$status.' '.$specialText.'</td>
					<td '.$status_bg.'>'.$categoryString.'</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'product-manager/manage-product.php?productID='.$productID.'" title="Manage">Manage</a>
					</td>
					<td '.$status_bg.' align="center">
						<a href="'.$cms_root.'product-manager/edit-product.php?productID='.$productID.'" title="Modify">Modify</a>
					</td>
					<td '.$status_bg.' align="center">';

					$txt.='<form name="delete_product'.$productID.'">
							<input type="hidden" name="delete_product" value="1">
							<input type="hidden" name="productID" value="'.$productID.'">
							<a href="javascript:deleteProduct('.$productID.')" title="Remove">Remove</a>
						</form>';

					$txt.= '</td>
				  </tr>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Products available. <a href="'.$cms_root.'product-manager/add-product.php" title="Add Product">Please add a Product here!</a></td>
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
    // PRODUCT CONTENT ARCHITECTURE
    //#################################################################
	function productContentArchitecture($cms_root, $web_root, $productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM product_content WHERE deletedBy = ?  AND productID = ? ORDER BY sequence ASC", array('0', $productID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$productContentID	= $row['productContentID'];
				$paragraphTitle		= $row['paragraphTitle'];
				$paragraph			= $row['paragraph'];
				$imageFile			= $row['imageFile'];
				$imageTitle			= $row['imageTitle'];
				$documentFile		= $row['documentFile'];
				$documentTitle		= $row['documentTitle'];
				$videoUrl			= $row['videoUrl'];
				$productGalleryID	= $row['productGalleryID'];
                $sequence           = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				if($productGalleryID != 0){

                    //CHECK IF IMAGES IN GALLERY
                    $result4        = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ?", array($productGalleryID));
                    $totalImages    = $connector->numResults($result4);

                    //REMOVE GALLERY
                    if($totalImages == 0){
                        $this->removeEmptyGallery($productGalleryID);
                        $removedGallery = 1;

                    }else{
    					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$productContentID.'">';

                            //GET TOTAL GALLERY IMAGES
                            $result2    = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($productGalleryID, 0));
                            $totalGalleryImage  = $connector->numResults($result2);

                            //IF MORE THAN 6 GALLERY IMAGES
                            if($totalGalleryImage > 6){
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC LIMIT 0,5", array($productGalleryID, 0));
                            }else{
                                //GET GALLEY IMAGE
                                $result3    = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? AND deletedBy = ? ORDER BY sequence ASC", array($productGalleryID, 0));
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

                                $txt.= '<a href="'.$cms_root.'product-manager/edit-gallery.php?productID='.$productID.'&productGalleryID='.$productGalleryID.'" title="View all Gallery Images">
                                    <div class="paragraph-image-indicator">
                                        <div class="paragraph-image-more-indicator">+'.$extraImages.'</div>
                                    </div>
                                </a>';
                            }

                            $txt.= '<div class="clear"></div>
                            <div class="module-manage-content-links">
    							<form name="delete_gallery'.$productContentID.'">
    								<input type="hidden" name="delete_gallery" value="1">
    								<input type="hidden" name="productContentID" value="'.$productContentID.'">
    								<input type="hidden" name="productGalleryID" value="'.$productGalleryID.'">
                                    <input type="hidden" name="productID" value="'.$productID.'">
    								<a href="javascript:deleteGallery('.$productContentID.')" title="Remove Gallery">Remove Gallery</a>
    							</form>
    							<a href="'.$cms_root.'product-manager/edit-gallery.php?productID='.$productID.'&productGalleryID='.$productGalleryID.'" title="Edit Gallery">Edit Gallery</a>
                                <a href="'.$cms_root.'product-manager/sequence-gallery.php?productID='.$productID.'&productGalleryID='.$productGalleryID.'" title="Sequence Gallery">Sequence Gallery</a>
    							<div class="clear"></div>
    							</div>
                        </div>';
                    }
				}else{
					$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$productContentID.'">';

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
							<form name="delete_paragraph'.$productContentID.'">
								<input type="hidden" name="delete_paragraph" value="1">
								<input type="hidden" name="productContentID" value="'.$productContentID.'">
								<input type="hidden" name="productID" value="'.$productID.'">
								<a href="javascript:deleteParagraph('.$productContentID.')" title="Remove Paragraph">Remove Paragraph</a>
							</form>
							<a href="'.$cms_root.'product-manager/edit-paragraph.php?productContentID='.$productContentID.'&productID='.$productID.'" title="Edit Paragraph">Edit Paragraph</a>
							<div class="clear"></div>
							</div>
                    </div>';
				}
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'product-manager/add-paragraph.php?productID='.$productID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';
		}

        //IF GALLERY(S) REMOVED RELOAD PAGE
        if($removedGallery == 1){
            header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=17");
    		exit;
        }

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // GET PRODUCT SUB CATEGORIES
    //#################################################################
	function getProductSubCategories($productCatID, $currentSubProductCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET PRODUCT SUB CATEGORIES
		$result = $connector->query("SELECT * FROM product_category WHERE productMainCatID = ? AND deletedBy = ? ORDER BY categoryName ASC", array($productCatID, 0));
        while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $subProductCatID   = $row['productCatID'];
            $subCategoryName   = $row['categoryName'];

            //GENERATE OUTPUT
            if($subProductCatID == $currentSubProductCatID){
                $txt.= '<option value="'.$subProductCatID.'" selected="selected">'.$subCategoryName.'</option>';
            }else{
                $txt.= '<option value="'.$subProductCatID.'">'.$subCategoryName.'</option>';
            }
        }

		//RETURN OUTPUT
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
    // CHECK IF PRODUCT SUB CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkSubCategoryChanges($title, $paragraph, $mainCategory, $image_title, $productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND catImageTitle = ? AND productCatID = ? AND categoryDescription = ? AND productMainCatID = ?", array($title, $image_title, $productCatID, $paragraph, $mainCategory));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // CHECK IF PRODUCT INFO HAS BEEN CHANGED
    //#################################################################
	function checkProductChanges($product_title, $product_intro, $productID, $productCatIDs, $productMainCatIDs, $image_title, $product_special, $product_special_date, $product_price, $manufacturer, $product_code, $product_brand){

		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM product WHERE productTitle = ? AND productIntro = ? AND productID = ? AND productCatID = ? AND productImageTitle = ? AND productSpecial = ? AND productPrice = ? AND manufacturerLink = ? AND productMainCatID = ? AND productCode = ? AND productBrand = ? AND productSpecialDate = ?", array($product_title, $product_intro, $productID, $productCatIDs, $image_title, $product_special, $product_price, $manufacturer, $productMainCatIDs, $product_code, $product_brand, $product_special_date));
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
    // UPDATE PRODUCT SUB CATEGORY
    //#################################################################
	function updateSubCategory($title, $paragraph, $mainCategory, $imageFile, $image_title, $category_url, $modifiedBy, $modifiedDate, $modifiedNumber, $productCatID){
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
                                    productMainCatID = ?,
									categoryName = ?,
                                    categoryDescription = ?,
                                    catImageTitle = ?,
                                    catImage = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE productCatID = ?",
									array($mainCategory, $title, $paragraph, $image_title, $imageFile, $category_url, $modifiedBy, $modifiedDate, $modifiedNumber, $productCatID));

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
    // ADD SUB CATEGORY
    //#################################################################
	function addSubCategory($title, $paragraph, $mainCategory, $image_title, $imageFile, $category_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$category_name		= strip_tags($title);
        $image_title		= strip_tags($image_title);

		//ADD PRODUCT CATEGORY
		$insert = $connector->query("INSERT INTO product_category (productMainCatID, categoryName, categoryDescription, catImageTitle, catImage, url, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
									array($mainCategory, $title, $paragraph, $image_title, $imageFile, $category_url, $currentUser, $currentDate));

        //RETURN CATEGORY ID
        $result = $connector->query("SELECT * FROM product_category ORDER BY productCatID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['productCatID'];

	}

	//#################################################################
    // ADD PRODUCT
    //#################################################################
	function addProduct($product_title, $product_intro, $productCatIDs, $productMainCatIDs, $image_title, $imageFile, $product_url, $product_special, $product_special_date, $product_price, $manufacturer, $product_code, $product_brand){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //CHECK PRODUCT SPECIAL
        if($product_special == '' || $product_special == ' '){
            $product_special = 0;
        }

        //SET DEFAULT SPECIAL DATE
        if($product_special_date == '' || $product_special_date == ' '){
            $product_special_date = '0000-00-00';
        }

		//STRIP INFO
		$product_title	        = strip_tags($product_title);
        $categories             = strip_tags($productCatIDs);
        $mainCategories         = strip_tags($productMainCatIDs);
        $image_title            = strip_tags($image_title);
        $product_url            = strip_tags($product_url);
        $product_special        = strip_tags($product_special);
        $product_special_date   = strip_tags($product_special_date);
        $manufacturer           = strip_tags($manufacturer);
        $product_code           = strip_tags($product_code);
        $product_brand          = strip_tags($product_brand);

		//ADD PRODUCT
		$insert = $connector->query("INSERT INTO product (productCatID, productMainCatID, productTitle, productIntro, productImageFile, productImageTitle, url, manufacturerLink, productSpecial, productSpecialDate, productPrice, productCode, productBrand, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($categories, $mainCategories, $product_title, $product_intro, $imageFile, $image_title, $product_url, $manufacturer, $product_special, $product_special_date, $product_price, $product_code, $product_brand, $currentUser, $currentDate));

        //GET LAST INSERTED ID
        $result = $connector->query("SELECT * FROM product ORDER BY productID DESC", array());
        $row    = $connector->fetchArray($result);

        //RETURN ID
        return $row['productID'];

	}

	//#################################################################
    // UPDATE PRODUCT
    //#################################################################
	function updateProduct($product_title, $product_intro, $modifiedBy, $modifiedDate, $modifiedNumber, $productID, $productCatIDs, $productMainCatIDs, $imageFile, $image_title, $product_url, $product_special, $product_special_date, $product_price, $manufacturer, $product_code, $product_brand){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //CHECK PRODUCT SPECIAL
        if($product_special == '' || $product_special == ' '){
            $product_special = 0;
        }

        //SET DEFAULT SPECIAL DATE
        if($product_special_date == '' || $product_special_date == ' '){
            $product_special_date = '0000-00-00';
        }

		//STRIP TAGS
		$product_title	       = strip_tags($product_title);
        $categories		       = strip_tags($productCatIDs);
        $mainCategories	       = strip_tags($productMainCatIDs);
        $image_title           = strip_tags($image_title);
        $product_url           = strip_tags($product_url);
        $product_special       = strip_tags($product_special);
        $product_special_date  = strip_tags($product_special_date);
        $manufacturer          = strip_tags($manufacturer);
        $product_code          = strip_tags($product_code);
        $product_brand         = strip_tags($product_brand);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM product WHERE productID = ?", array($productID));
        $row    = $connector->fetchArray($result);
        $image  = $row['productImageFile'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE product SET
									productTitle = ?,
									productIntro = ?,
                                    productImageFile = ?,
                                    productImageTitle = ?,
                                    productCatID = ?,
                                    productMainCatID = ?,
                                    url = ?,
                                    productSpecial = ?,
                                    productSpecialDate = ?,
                                    productPrice = ?,
                                    productBrand = ?,
                                    manufacturerLink = ?,
                                    productCode = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE productID = ?",
									array($product_title, $product_intro, $imageFile, $image_title, $categories, $mainCategories, $product_url, $product_special, $product_special_date, $product_price, $product_brand, $manufacturer, $product_code, $modifiedBy, $modifiedDate, $modifiedNumber, $productID));

	}

	//#################################################################
    // DELETE PRODUCT
    //#################################################################
	function deleteProduct($productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //GET GALLERY ID
        $result = $connector->query("SELECT productGalleryID FROM product_gallery WHERE productID = ? ", array($productID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLE
            $productGalleryID   = $row['productGalleryID'];

            //GET GALLERY IMAGE FILE
            $result2    = $connector->query("SELECT galleryImageFile FROM product_gallery_content WHERE productGalleryID = ? ORDER BY sequence ASC", array($productGalleryID));

            //SET GALLERY IMAGE FILE VARIABLE
            $galleryImageFile   = $row['galleryImageFile'];

            //DELETE IMAGES
            unlink($largeDirectory.$galleryImageFile);
            unlink($mediumDirectory.$galleryImageFile);
            unlink($smallDirectory.$galleryImageFile);

            //REMOVE PRODUCT GALLERY CONTENT
            $remove = $connector->query("DELETE FROM product_gallery_content WHERE productGalleryID = ?", array($productGalleryID));
        }

		//REMOVE PRODUCT
		$remove = $connector->query("DELETE FROM product WHERE productID = ?", array($productID));

        //REMOVE PRODUCT CONTENT
        $remove = $connector->query("DELETE FROM product_content WHERE productID = ?", array($productID));

        //REMOVE PRODUCT GALLERY
        $remove = $connector->query("DELETE FROM product_gallery WHERE productID = ?", array($productID));

        //REMOVE META DETAILS
        $remove = $connector->query("DELETE FROM meta_details WHERE productID = ?", array($productID));

	}

	//#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($productContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
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
		$remove = $connector->query("DELETE FROM product_content WHERE productContentID = ?",array($productContentID));

	}

    //#################################################################
    // DELETE GALLERY
    //#################################################################
	function deleteGallery($productContentID, $productGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ?", array($productGalleryID));
		while($row	= $connector->fetchArray($result)){
            $galleryImageFile           = $row['galleryImageFile'];
            $productGalleryContentID    = $row['productGalleryContentID'];

    		//DELETE IMAGES
    		unlink($largeDirectory.$galleryImageFile);
    		unlink($mediumDirectory.$galleryImageFile);
    		unlink($smallDirectory.$galleryImageFile);

    		//REMOVE GALLERY IMAGE
    		$remove = $connector->query("DELETE FROM product_gallery_content WHERE productGalleryContentID = ?",array($productGalleryContentID));
        }

        //REMOVE GALLERY ENTRIES
        $removeGallery = $connector->query("DELETE FROM product_gallery WHERE productGalleryID = ?",array($productGalleryID));
        $removeEntry = $connector->query("DELETE FROM product_content WHERE productContentID = ?",array($productContentID));

	}

    //#################################################################
    // DELETE GALLERY IMAGE
    //#################################################################
	function deleteGalleryImage($productGalleryContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

        //GET NAME OF IMAGE
        $result = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryContentID = ?", array($productGalleryContentID));
        $row    = $connector->fetchArray($result);
        $galleryImageFile   = $row['galleryImageFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$galleryImageFile);
		unlink($mediumDirectory.$galleryImageFile);
		unlink($smallDirectory.$galleryImageFile);

		//REMOVE IMAGE
		$remove = $connector->query("DELETE FROM product_gallery_content WHERE productGalleryContentID = ?",array($productGalleryContentID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($category_name){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND productMainCatID = ?", array($category_name, 0));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}

	}

    //#################################################################
    // CHECK IF SUB CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addSubCategoryCheck($category_name, $mainCategory){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND productMainCatID = ?", array($category_name, $mainCategory));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF PRODUCT IS ALREADY IN USE
    //#################################################################
	function addProductCheck($product_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK PRODUCT
		$result = $connector->query("SELECT * FROM product WHERE productTitle = ?", array($product_title));
		$total	= $connector->numResults($result);

		//IF PRODUCT HASN'T BEEN USED
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
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND productMainCatID = ?", array($category_name, 0));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

    //#################################################################
    // CHECK IF PRODUCT CATEGORY IS ALREADY IN USE
    //#################################################################
	function editSubCategoryCheck($category_name, $mainCategory){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY
		$result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND productMainCatID = ?", array($category_name, $mainCategory));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}

	}

	//#################################################################
    // CHECK IF PRODUCT TITLE IS ALREADY IN USE
    //#################################################################
	function editProductCheck($productID, $product_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK PRODUCT
		$result = $connector->query("SELECT * FROM product WHERE productTitle = ? AND productID != ?", array($product_title, $productID));
		$total	= $connector->numResults($result);

		//IF PRODUCT HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

	//#################################################################
    // ADD PRODUCT PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $productID){
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
		$result	= $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ? ORDER BY sequence DESC", array($productID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO product_content (productID, paragraphTitle, paragraph, imageFile, imageTitle, documentFile, documentTitle, videoUrl, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($productID, $title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $productContentID){
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
        $result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
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
        $result = $connector->query("SELECT * FROM product_content WHERE productContentID = ?", array($productContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD PRODUCT CONTENT
		$update			= $connector->query("UPDATE product_content SET
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
                                            WHERE productContentID = ?",
                                            array($title, $paragraph, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $modifiedNumber, $currentDate, $productContentID));

	}

    //#################################################################
    // UPDATE PRODUCT GALLERY INFO
    //#################################################################
	function updateProductGalleryInfo($productGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM product_gallery WHERE productGalleryID = ?", array($productGalleryID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//UPDATE PRODUCT CONTENT
		$update			= $connector->query("UPDATE product_gallery SET
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE productGalleryID = ?",
                                            array($currentUser, $modifiedNumber, $currentDate, $productGalleryID));

	}

    //#################################################################
    // SET productGalleryID AND RETURN IT
    //#################################################################
	function setProductGalleryID($productID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//ADD ProductID INTO product_gallery
		$insert = $connector->query("INSERT INTO product_gallery (productID, createdBy, createdDate)
									VALUES (?, ?, ?)",
									array($productID, $currentUser, $currentDate));

        //GET productGalleryID
        $result = $connector->query("SELECT productGalleryID FROM product_gallery WHERE productID = ? AND createdBy = ? AND createdDate = ? AND deletedBy = ?", array($productID, $currentUser, $currentDate, 0));
        $row    = $connector->fetchArray($result);

        //RETURN productGalleryID
        return $row['productGalleryID'];;
	}

    //#################################################################
    // ADD productGalleryID INTO product_content
    //#################################################################
	function addProductGalleryIDIntoProductContent($productID, $productGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //GET SEQUENCE
        $result = $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ? ORDER BY sequence DESC LIMIT 0,1", array($productID, 0));
        $row    = $connector->fetchArray($result);
        $sequence   = $row['sequence']+1;

        //ADD productGalleryID INTO product_content
        $insert = $connector->query("INSERT INTO product_content (productID, productGalleryID, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?)",
									array($productID, $productGalleryID, $currentUser, $currentDate, $sequence));
	}

    //#################################################################
    // ADD GALLERY IMAGES INTO DATABASE
    //#################################################################
	function addGalleryImages($productGalleryID, $galleryImageFile, $galleryImageTitle){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //STRIP INFO
		$galleryImageTitle    = strip_tags($galleryImageTitle);

        //GET LAST INSERTED SEQUENCE
        $last           = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? ORDER BY sequence DESC", array($productGalleryID));
        $lastResult     = $connector->fetchArray($last);
        $newSequence    = $lastResult['sequence']+1;

		//ADD productGalleryID INTO product_gallery_content
		$insert = $connector->query("INSERT INTO product_gallery_content (productGalleryID, galleryImageFile, galleryImageTitle, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($productGalleryID, $galleryImageFile, $galleryImageTitle, $currentUser, $currentDate, $newSequence));

	}

    //#################################################################
    // UPDATE OR REMOVE GALLERY IMAGES
    //#################################################################
	function updateRemoveGalleryImages($productGalleryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLE
        $updatedGalleryImages = 0;

        //GET CURRENT GALLERY IMAGES THAT MIGHT HAVE TO BE UPDATED
        $result = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryID = ? ORDER BY productGalleryContentID ASC", array($productGalleryID));
        while($row  = $connector->fetchArray($result)){
            //SET VARIABLES
            $productGalleryContentID    = $row['productGalleryContentID'];
            $updateImageTitle           = $_POST['imageGalleryTitle_'.$productGalleryContentID];
            $removeGalleryImage         = $_POST['remove_gallery_image_'.$productGalleryContentID];

            //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $updateImageTitle       = $this->specialCharactersToHTMLEntity($updateImageTitle);

            //CHECK IF GALLERY IMAGE HAS TO BE REMOVED
            if($removeGalleryImage == 1){
                $this->deleteGalleryImage($productGalleryContentID);
                $updatedGalleryImages = 1;
            }
            //CHECK IF GALLERY IMAGE HAS BEEN UPDATED
            else{
                $result1    = $connector->query("SELECT * FROM product_gallery_content WHERE productGalleryContentID = ? AND galleryImageTitle = ?", array($productGalleryContentID, $updateImageTitle));
                $total      = $connector->numResults($result1);

                //UPDATE GALLERY IMAGE TITLE
                if($total == 0){

                    $update = $connector->query("UPDATE product_gallery_content SET
                                                galleryImageTitle = ?
                                                WHERE productGalleryContentID = ?",
                                                array($updateImageTitle, $productGalleryContentID));

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
$productManager = new productManager();

//#################################################################
// ADD PRODUCT CATEGORY
//#################################################################
if(isset($_POST['add_product_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VALUES
	$title		    = $_POST['product-category-title'];
    $paragraph      = $_POST['paragraph'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$product_type	= $_POST['product-type'];
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
	$v->validateString($title, 'Product Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($product_type == '' && $image_type == ''){

			//CHECK IF CATEGORY NAME IS ALREADY IN USE
			$category_used = $productManager->addCategoryCheck($title);
			if($category_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE PRODUCT URL
        		$category_url = str_replace("'", "", $title);
        		$category_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($category_url));
        		$category_url = str_replace(' ', '-', $category_url).'/';

                //CHECK IF CATEGORY URL EXISTS
                $category_url = $productManager->checkCategoryURLExists($category_url, '');

				//INSERT USER INTO DATABASE
				$productCatID = $productManager->addCategory($title, $paragraph, $image_title, $imageFile, $category_url);

                //GET META DETAILS
    			$keywords		= $productManager->getMetaKeywordCategory($productCatID);
                $description	= $productManager->getMetaDescriptionCategory($productCatID);

    			//UPDATE META DETAILS
    			$productManager->updateMetaDetailsCategory($keywords, $description, $productCatID);

                //REDIRECT USER
    			header("Location: ".$cms_root."product-manager/crop-image-category.php?productCatID=".$productCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;

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
// EDIT CATEGORY
//#################################################################
if(isset($_POST['edit_product_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VALUES
	$title          = $_POST['product-category-title'];
    $paragraph      = $_POST['paragraph'];
	$productCatID   = $_POST['productCatID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$product_type   = $_POST['product-type'];
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
	$v->validateString($title, 'Product Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){
		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($product_type == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($productManager->checkCategoryChanges($title, $paragraph, $image_title, $productCatID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

				//CHECK CATEGORY IS USED
				$category_used = $productManager->editCategoryCheck($category_name);
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

                    //GENERATE PRODUCT URL
                    $category_url = str_replace("'", "", $title);
                    $category_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($category_url));
                    $category_url = str_replace(' ', '-', $category_url).'/';

                    //CHECK IF CATEGORY URL EXISTS
                    $category_url = $productManager->checkCategoryURLExists($category_url, $productCatID);

                    //UPDATE USER IN DATABASE
    				$productManager->updateCategory($title, $paragraph, $imageFile, $image_title, $category_url, $modifiedBy, $modifiedDate, $modifiedNumber, $productCatID);

                    //GET META DETAILS
        			$keywords		= $productManager->getMetaKeywordCategory($productCatID);
                    $description	= $productManager->getMetaDescriptionCategory($productCatID);

        			//UPDATE META DETAILS
        			$productManager->updateMetaDetailsCategory($keywords, $description, $productCatID);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."product-manager/crop-image-category.php?productCatID=".$productCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=3");
                		exit;
                    }else{
                        header("Location: ".$cms_root."product-manager/manage-product-category.php?message=3");
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
				header("Location: ".$cms_root."product-manager/manage-product-category.php");
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
// ADD PRODUCT SUB CATEGORY
//#################################################################
if(isset($_POST['add_product_sub_category'])){
    //CONNECT TO DATABASE
    $connector = new dbConnector();

    //GET POSTED VALUES
    $title		    = $_POST['product-category-title'];
    $paragraph      = $_POST['paragraph'];
    $mainCategory   = $_POST['product-main-category'];
    $image_title	= $_POST['image-title'];

    //HONEY POTS
    $product_type	= $_POST['product-type'];
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
    $v->validateString($title, 'Product Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
    $v->validateDropDown($mainCategory, 'Product Main Category');
    $v->validateString($image_title, 'Image Title',2, 150);
    $v->validateImage($inputField, 'Image File');

    //CHECK IF NO ERROR HAVE BEEN FOUND
    if(!$v->hasErrors()){

        //CHECK IF ALL CONDITONS HAVE BEEN MET
        if($product_type == '' && $image_type == ''){

            //CHECK IF CATEGORY NAME IS ALREADY IN USE
            $category_used = $productManager->addSubCategoryCheck($title, $mainCategory);
            if($category_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
                if($_FILES[$inputField]["tmp_name"] != ""){
                    $imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

                    //GET THE IMAGE SIZE
                    list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
                }

                //REMOVE LINE BREAKS FROM PARAGRAPH
                $paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE PRODUCT URL
                $category_url = str_replace("'", "", $title);
                $category_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($category_url));
                $category_url = str_replace(' ', '-', $category_url).'/';

                //CHECK IF CATEGORY URL EXISTS
                $category_url = $productManager->checkCategoryURLExists($category_url, '');

                //INSERT USER INTO DATABASE
                $productCatID = $productManager->addSubCategory($title, $paragraph, $mainCategory, $image_title, $imageFile, $category_url);

                //GET META DETAILS
                $keywords		= $productManager->getMetaKeywordCategory($productCatID);
                $description	= $productManager->getMetaDescriptionCategory($productCatID);

                //UPDATE META DETAILS
                $productManager->updateMetaDetailsCategory($keywords, $description, $productCatID);

                //REDIRECT USER
                header("Location: ".$cms_root."product-manager/crop-image-category.php?productCatID=".$productCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
                exit;

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
// EDIT SUB CATEGORY
//#################################################################
if(isset($_POST['edit_product_sub_category'])){
    //CONNECT TO DATABASE
    $connector = new dbConnector();

    //GET POSTED VALUES
    $title          = $_POST['product-category-title'];
    $paragraph      = $_POST['paragraph'];
    $mainCategory   = $_POST['product-main-category'];
    $productCatID   = $_POST['productCatID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

    $modifiedDate	= $_POST['modifiedDate'];
    $modifiedBy		= $_SESSION['cmsUser'];
    $modifiedNumber	= $_POST['modifiedNumber'];

    //HONEY POTS
    $product_type   = $_POST['product-type'];
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
    $v->validateString($title, 'Product Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
    $v->validateDropDown($mainCategory, 'Product Main Category');
    $v->validateString($image_title, 'Image Title',2, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

    //CHECK IF NO ERROR HAVE BEEN FOUND
    if(!$v->hasErrors()){

        //CHECK IF ALL CONDITONS HAVE BEEN MET
        if($product_type == '' && $image_type == ''){

            //CHECK IF CONTENT HAS BEEN CHANGED
            if($productManager->checkSubCategoryChanges($title, $paragraph, $mainCategory, $image_title, $productCatID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

                //CHECK CATEGORY IS USED
                $category_used = $productManager->editSubCategoryCheck($category_name, $mainCategory);
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

                    //GENERATE PRODUCT URL
                    $category_url = str_replace("'", "", $title);
                    $category_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($category_url));
                    $category_url = str_replace(' ', '-', $category_url).'/';

                    //CHECK IF CATEGORY URL EXISTS
                    $category_url = $productManager->checkCategoryURLExists($category_url, $productCatID);

                    //UPDATE USER IN DATABASE
                    $productManager->updateSubCategory($title, $paragraph, $mainCategory, $imageFile, $image_title, $category_url, $modifiedBy, $modifiedDate, $modifiedNumber, $productCatID);

                    //GET META DETAILS
                    $keywords		= $productManager->getMetaKeywordCategory($productCatID);
                    $description	= $productManager->getMetaDescriptionCategory($productCatID);

                    //UPDATE META DETAILS
                    $productManager->updateMetaDetailsCategory($keywords, $description, $productCatID);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                        //REDIRECT USER
                        header("Location: ".$cms_root."product-manager/crop-image-category.php?productCatID=".$productCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=3");
                        exit;
                    }else{
                        header("Location: ".$cms_root."product-manager/manage-product-category.php?message=3");
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
                header("Location: ".$cms_root."product-manager/manage-product-category.php");
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
if(isset($_POST['delete_product_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $productCatID	= $_POST['productCatID'];

    //SET USER AS REMOVED IN DATABASE
    $productManager->deleteCategory($productCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."product-manager/manage-product-category.php?message=5");
    exit;
}

//#################################################################
// ADD PRODUCT
//#################################################################
if(isset($_POST['add_product'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $product_tags           = $_POST['product_tags'];
	$product_title          = $_POST['product-title'];
    $product_code           = $_POST['product-code'];
	$product_intro	        = $_POST['paragraph'];
    $categories             = $_POST['categories'];
    $product_category       = $_POST['product-category'];
    $product_sub_category   = $_POST['product-sub-category'];
    $product_special        = $_POST['product-special'];
    $product_special_date   = $_POST['special-end-date'];
    $product_price          = $_POST['product-price'];
    $manufacturer           = $_POST['product-manufacturer'];
    $product_brand          = $_POST['product-brand'];
    $image_title	        = $_POST['image-title'];

	//HONEY POTS
	$product_paragraph	= $_POST['product-paragraph'];
    $image_type		    = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $product_title      = $userLogin->specialCharactersToHTMLEntity($product_title);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($product_title, 'Product Title', 2, 100);
    $v->validateString($product_code, 'Product Code', 5, 100);
	$v->validateText($product_intro, 'Intro', 10);

    //MULTIPLE PRODUCT CATEGORY SELECTION
    if($product_tags == 1){
        $v->validateTags($categories, 'Product Categories');
    }
    //SINGLE PRODUCT CATEGORY SELECTION
    else{
        $v->validateDropDown($product_category, 'Product Category');
        $v->validateDropDown($product_sub_category, 'Product  SubCategory');
    }

    //IF SPECIAL HAS BEEN SELECTED
    if($product_special != '' && $product_special != ' ' && $product_special != 0){
        $v->validateDropDown($product_special, 'On Special');
        $v->validateDate($product_special_date, 'Special End Date');
    }

    //IF PRICE HAS BEEN SUPLLIED
    if($product_price != '' && $product_price != ' ' && $product_price != 0){
        $v->validateMoney($product_price, 'Product Price');
    }

    //IF BRAND IS UPPLIED
    if($product_brand != '' && $product_brand != ' '){
        $v->validateString($product_brand, 'Product Brand', 2, 100);
    }

    //IF MANUFACTURER HAS BEEN SUPPLIED
    if($manufacturer != '' && $manufacturer != ' '){
        $v->validateLink($manufacturer, 'Manufacturer Link');
    }

    $v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($product_paragraph == '' && $image_type == ''){

            //MULTIPLE PRODUCT CATEGORY
            if($product_tags == 1){
                //GET ALL PRODUCT CATEGORY ID'S
                $productCatIDs          = ',';
                $productMainCatIDs      = ',';
                $productMainCatIDArray  = array();
                $categories             = substr($categories, 1, -1);
                $productCatNameArray    = explode(',', $categories);
                foreach($productCatNameArray as $productCatName){

                    //GET SUB CATEGORY NAME
                    $productSubCatNameArray = explode(' - ', $productCatName);
                    $productMainCatName     = $userLogin->HTMLEntityToSpecialCharacters($productSubCatNameArray[0]);
                    $productSubCatName      = $userLogin->HTMLEntityToSpecialCharacters($productSubCatNameArray[1]);

                    /*//GET ID FOR CATEGORY
                    $result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND deletedBy = ?", array($productCatName, '0'));
                    $row    = $connector->fetchArray($result);
                    $productCatID  = $row['productCatID'];*/

                    //GET ID FOR MIAN CATEGORY
                    $result = $connector->query("SELECT * FROM product_category WHERE categoryName = ?", array($productMainCatName));
                    $row    = $connector->fetchArray($result);
                    $productMainCatID  = $row['productCatID'];

                    //INSERT INTO STRING
                    $productMainCatIDArray[]= $productMainCatID;

                    //GET ID FOR CATEGORY
                    $result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND productMainCatID = ?", array($productSubCatName, $productMainCatID));
                    $row    = $connector->fetchArray($result);
                    $productCatID  = $row['productCatID'];

                    //INSERT INTO STRING
                    $productCatIDs.= $productCatID.',';
                }

                //CLEAN UP MAINCATID ARRAY
                $uniqueMainCatIDs  = array_unique($productMainCatIDArray);
                foreach($uniqueMainCatIDs AS $mainCatIDs){
                    $productMainCatIDs.= $mainCatIDs.',';
                }
            }
            //SINGLE PRODUCT CATEGORY
            else{
                $productCatIDs      = ','.$product_sub_category.',';
                $productMainCatIDs  = ','.$product_category.',';
            }

			//CHECK IF PRODUCT IS ALREADY IN USE
			$product_used = $productManager->addProductCheck($product_title);
			if($product_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //CREATE PRODUCT URL
        		$product_url = str_replace("'", "", $product_title);
        		$product_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($product_url));
        		$product_url = str_replace(' ', '-', $product_url).'/';

				//INSERT PRODUCT INTO DATABASE
				$productID = $productManager->addProduct($product_title, $product_intro, $productCatIDs, $productMainCatIDs, $image_title, $imageFile, $product_url, $product_special, $product_special_date, $product_price, $manufacturer, $product_code, $product_brand);

                //GET META DETAILS
                $keywords		= $productManager->getMetaKeyword($productID);
                $description	= $productManager->getMetaDescription($productID);

                //UPDATE META DETAILS
                $productManager->updateMetaDetails($keywords, $description, $productID);

                //ADD INFORMATION INTO SEARCH INDEX
                $productManager->addProductSearchIndex($productID, $product_title, $keywords, $product_intro, $product_code, $categories);

                //REDIRECT USER
    			header("Location: ".$cms_root."product-manager/crop-image-post.php?productID=".$productID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;

			}
			else{

				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Product Title</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT PRODUCT
//#################################################################
if(isset($_POST['edit_product'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $product_tags           = $_POST['product_tags'];
	$productID				= $_POST['productID'];
	$product_title 		    = $_POST['product-title'];
    $product_code           = $_POST['product-code'];
	$product_intro		    = $_POST['paragraph'];
    $categories             = $_POST['categories'];
    $product_category       = $_POST['product-category'];
    $product_sub_category   = $_POST['product-sub-category'];
    $product_special        = $_POST['product-special'];
    $product_special_date   = $_POST['special-end-date'];
    $product_price          = $_POST['product-price'];
    $manufacturer           = $_POST['product-manufacturer'];
    $product_brand          = $_POST['product-brand'];
    $oldImage               = $_POST['oldImage'];
    $image_title            = $_POST['image-title'];

	$modifiedDate			= $_POST['modifiedDate'];
	$modifiedBy				= $_SESSION['cmsUser'];
	$modifiedNumber			= $_POST['modifiedNumber'];

	//HONEY POTS
	$product_paragraph     = $_POST['product-paragraph'];
    $image_type             = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $product_title    = $userLogin->specialCharactersToHTMLEntity($product_title);
    $image_title        = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($product_title, 'Product Title', 2, 100);
    $v->validateString($product_code, 'Product Code', 5, 100);
	$v->validateText($product_intro, 'Intro', 10);

    //MULTIPLE PRODUCT CATEGORY SELECTION
    if($product_tags == 1){
        $v->validateTags($categories, 'Product Categories');
    }
    //SINGLE PRODUCT CATEGORY SELECTION
    else{
        $v->validateDropDown($product_category, 'Product Category');
        $v->validateDropDown($product_sub_category, 'Product Sub Category');
    }

    //IF SPECIAL HAS BEEN SELECTED
    if($product_special != '' && $product_special != ' ' && $product_special != 0){
        $v->validateDropDown($product_special, 'On Special');
        $v->validateDate($product_special_date, 'Special End Date');
    }

    //IF PRICE HAS BEEN SUPPLIED
    if($product_price != '' && $product_price != ' ' && $product_price != 0){
        $v->validateMoney($product_price, 'Product Price');
    }

    //IF BRAND IS SUPPLIED
    if($product_brand != '' && $product_brand != ' '){
        $v->validateString($product_brand, 'Product Brand', 2, 100);
    }

    //IF MANUFACTURER IS SUPPLIED
    if($manufacturer != '' && $manufacturer != ' '){
        $v->validateLink($manufacturer, 'Manufacturer Link');
    }

    $v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($product_paragraph == '' && $image_type == ''){

            //MULTIPLE PRODUCT CATEGORY
            if($product_tags == 1){
                //GET ALL PRODUCT CATEGORY ID'S
                $productCatIDs          = ',';
                $productMainCatIDs      = ',';
                $productMainCatIDArray  = array();
                $categories             = substr($categories, 1, -1);
                $productCatNameArray    = explode(',', $categories);
                foreach($productCatNameArray as $productCatName){

                    //GET SUB CATEGORY NAME
                    $productSubCatNameArray = explode(' - ', $productCatName);
                    $productMainCatName     = $userLogin->HTMLEntityToSpecialCharacters($productSubCatNameArray[0]);
                    $productSubCatName      = $userLogin->HTMLEntityToSpecialCharacters($productSubCatNameArray[1]);

                    /*//GET ID FOR CATEGORY
                    $result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND deletedBy = ?", array($productCatName, '0'));
                    $row    = $connector->fetchArray($result);
                    $productCatID  = $row['productCatID'];*/

                    //GET ID FOR MIAN CATEGORY
                    $result = $connector->query("SELECT * FROM product_category WHERE categoryName = ?", array($productMainCatName));
                    $row    = $connector->fetchArray($result);
                    $productMainCatID  = $row['productCatID'];

                    //INSERT INTO STRING
                    $productMainCatIDArray[]= $productMainCatID;

                    //GET ID FOR CATEGORY
                    $result = $connector->query("SELECT * FROM product_category WHERE categoryName = ? AND productMainCatID = ?", array($productSubCatName, $productMainCatID));
                    $row    = $connector->fetchArray($result);
                    $productCatID  = $row['productCatID'];

                    //INSERT INTO STRING
                    $productCatIDs.= $productCatID.',';
                }

                //CLEAN UP MAINCATID ARRAY
                $uniqueMainCatIDs  = array_unique($productMainCatIDArray);
                foreach($uniqueMainCatIDs AS $mainCatIDs){
                    $productMainCatIDs.= $mainCatIDs.',';
                }

            }
            //SINGLE PRODUCT CATEGORY
            else{
                $productCatIDs      = ','.$product_sub_category.',';
                $productMainCatIDs  = ','.$product_category.',';
            }


			//CHECK IF CONTENT HAS BEEN CHANGED
			if($productManager->checkProductChanges($product_title, $product_intro, $productID, $productCatIDs, $productMainCatIDs, $image_title, $product_special, $product_special_date, $product_price, $manufacturer, $product_code, $product_brand) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

				//CHECK TITLE IS USED
				$product_used = $productManager->editProductCheck($productID, $product_title);
				if($product_used == 'unused'){

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

                    //GENERATE PRODUCT URL
                    $product_url = str_replace("'", "", $product_title);
                    $product_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($product_url));
            		$product_url = str_replace(' ', '-', $product_url).'/';

					//UPDATE PRODUCT IN DATABASE
					$productManager->updateProduct($product_title, $product_intro, $modifiedBy, $modifiedDate, $modifiedNumber, $productID, $productCatIDs, $productMainCatIDs, $imageFile, $image_title, $product_url, $product_special, $product_special_date, $product_price, $manufacturer, $product_code, $product_brand);

                    //GET META DETAILS
        			$keywords		= $productManager->getMetaKeyword($productID);
        			$description	= $productManager->getMetaDescription($productID);

        			//UPDATE META DETAILS
        			$productManager->updateMetaDetails($keywords, $description, $productID);

                    //ADD INFORMATION INTO SEARCH INDEX
                    $productManager->addProductSearchIndex($productID, $product_title, $keywords, $product_intro, $product_code, $categories);

                    //IF A NEW IMAGE HAS BEEN UPLOADED
                    if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
        			    header("Location: ".$cms_root."product-manager/crop-image-post.php?productID=".$productID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
                		exit;
                    }else{
                        header("Location: ".$cms_root."product-manager/index.php?message=4");
                		exit;
                    }

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Product Title</b> you supplied is already in use. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."product-manager/index.php");
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
//DELETE PRODUCT
//#################################################################
if(isset($_POST['delete_product'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$productID	= $_POST['productID'];

    //DELETE PRODUCT
    $productManager->deleteProduct($productID);

    //REMOVE PRODUCT FROM SEARCH INDEX
    $productManager->removeProductSearchIndex($productID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."product-manager/index.php?message=6");
    exit;
}

//#################################################################
// ADD PRODUCT PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$productID		= $_POST['productID'];
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
			$productManager->addParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $productID);

			//GET META DETAILS
			$keywords		= $productManager->getMetaKeyword($productID);
			$description	= $productManager->getMetaDescription($productID);

			//UPDATE META DETAILS
			$productManager->updateMetaDetails($keywords, $description, $productID);

            //GET PRODUCT INFO
            $product_title  = $productManager->getProductInfo($productID, 'productTitle');
            $product_intro  = $productManager->getProductInfo($productID, 'productIntro');

            //ADD INFORMATION INTO SEARCH INDEX
            $productManager->addProductSearchIndex($productID, $product_title, $keywords, $product_intro, '', '');

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."product-manager/crop-image.php?productID=".$productID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=11");
        		exit;
			}
			//REDIRECT TO PRODUCT
			else{
				header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=11");
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
	$productID         = $_POST['productID'];
    $productContentID  = $_POST['productContentID'];
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
                    $image_title    = $productManager->getProductContentInfo($productContentID, 'imageTitle');
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
                $doc_title      = $productManager->getProductContentInfo($productContentID, 'documentTitle');
            }

            //CHECK IF VIDEO NEEDS TO BE REMOVED
            if($removeVideo == 1){
                $video = '';
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//UPDATE PRODUCT IN DATABASE
			$productManager->updateParagraph($title, $paragraph, $image_title, $imageFile, $docFile, $doc_title, $video, $productContentID);

			//GET META DETAILS
			$keywords		= $productManager->getMetaKeyword($productID);
			$description	= $productManager->getMetaDescription($productID);

			//UPDATE META DETAILS
			$productManager->updateMetaDetails($keywords, $description, $productID);

            //GET PRODUCT INFO
            $product_title  = $productManager->getProductInfo($productID, 'productTitle');
            $product_intro  = $productManager->getProductInfo($productID, 'productIntro');

            //ADD INFORMATION INTO SEARCH INDEX
            $productManager->addProductSearchIndex($productID, $product_title, $keywords, $product_intro, '', '');

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."product-manager/crop-image.php?productID=".$productID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=12");
        		exit;
			}
			//REDIRECT TO PRODUCT
			else{
				header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=12");
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
	$productContentID	= $_POST['productContentID'];
	$productID			= $_POST['productID'];

    //SET PARAGRPAH AS REMOVED IN DATABASE
    $productManager->deleteParagraph($productContentID);

    //GET META DETAILS
    $keywords		= $productManager->getMetaKeyword($productID);

    //GET PRODUCT INFO
    $product_title  = $productManager->getProductInfo($productID, 'productTitle');
    $product_intro  = $productManager->getProductInfo($productID, 'productIntro');

    //ADD INFORMATION INTO SEARCH INDEX
    $productManager->addProductSearchIndex($productID, $product_title, $keywords, $product_intro, '', '');

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=13");
    exit;
}

//#################################################################
//DELETE GALLERY
//#################################################################
if(isset($_POST['delete_gallery'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$productContentID   = $_POST['productContentID'];
	$productGalleryID   = $_POST['productGalleryID'];
    $productID          = $_POST['productID'];

    //REMOVE GALLERY FROM DATABASE
    $productManager->deleteGallery($productContentID, $productGalleryID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=17");
    exit;
}

//#################################################################
//ADD GALLERY
//#################################################################
if(isset($_POST['add_gallery'])){
    //CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES
    $productID     = $_POST['productID'];
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

            //GET productGalleryID
            $productGalleryID  = $productManager->setProductGalleryID($productID);

            //ADD productGalleryID INTO product_content
            $productManager->addProductGalleryIDIntoProductContent($productID, $productGalleryID);

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
                        $productManager->addGalleryImages($productGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $hasImages = 1;

                    }

                    $count++;
                }
            }

            //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
            if($hasImages == 1){
                header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=15");
        		exit;
            }else{
                header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID);
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
    $productID          = $_POST['productID'];
    $productGalleryID   = $_POST['productGalleryID'];
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
        $updatedGalleryImages = $productManager->updateRemoveGalleryImages($productGalleryID);

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
                        $productManager->addGalleryImages($productGalleryID, $file_name, $imageTitle);

                        //SET THAT AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
                        $updatedGalleryImages = 1;

                    }

                    $count++;
                }
            }
        }

        //CHECK IF GALLERY HAS BEEN MODIFIED
        if($updatedGalleryImages == 1){
            $productManager->updateProductGalleryInfo($productGalleryID);
        }

        //CHECK IF AN IMAGE HAS BEEN UPLOADED TO THE GALLERY
        if($updatedGalleryImages == 1){
            header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=16");
    		exit;
        }else{
            header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID);
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
    $newWidth		= 583;
    $newHeight		= 250;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($productRatio == 1){
    $newWidth		= 400;
    $newHeight		= 313;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($normalRatio == 1){
    $newWidth		= 350;
    $newHeight		= 232;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//SET VARIABLES FOR STATS INCLUDE
$categoryWidth      = 583;
$categoryHeight     = 250;
$productWidth       = 400;
$productHeight      = 313;
$paragraphWidth     = 350;
$paragraphHeight    = 232;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$productID			= $_POST['productID'];
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
	header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=".$message);
    exit;
}

//CROP PRODUCT IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-post'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$productID			= $_POST['productID'];
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
	header("Location: ".$cms_root."product-manager/manage-product.php?productID=".$productID."&message=".$message);
    exit;
}

//CROP IMAGE FOR CATEGORY WHEN FINISHED SELECTING AREA
if(isset($_POST['crop-category'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$productCatID	    = $_POST['productCatID'];
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
	header("Location: ".$cms_root."product-manager/manage-product-category.php?message=".$message);
    exit;
}
###################################################################
?>
