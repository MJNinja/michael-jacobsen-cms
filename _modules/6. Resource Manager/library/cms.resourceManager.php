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

class resourceManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Resource has successfully been added.'; break;
            case 3: $displayMessage = 'The selected Category has successfully been updated.'; break;
			case 4: $displayMessage = 'The selected Resource has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Category has successfully been removed.'; break;
			case 6: $displayMessage = 'The selected Resource has successfully been removed.'; break;
			case 7: $displayMessage = 'The selected Category has successfully been recovered.'; break;
			case 8: $displayMessage = 'The selected Category has successfully been re-activated.'; break;
			case 9: $displayMessage = 'The selected Resource has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Resource has successfully been re-activated.'; break;
			case 11: $displayMessage = 'A new Paragraph has successfully been added.'; break;
			case 12: $displayMessage = 'The selected Paragraph has successfully been updated.'; break;
			case 13: $displayMessage = 'The selected Paragraph has successfully been removed.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

    //#################################################################
	//CHECK RESOURCE URL EXISTS
	//#################################################################
	function checkResourceURLExists($url, $resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED
        $currentURL = $this->getResourceInfo($resourceID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM resource WHERE url = ? LIMIT 0,1", array($url));
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
                    $result2    = $connector->query("SELECT url FROM resource WHERE url = ? LIMIT 0,1", array($newURL));
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
    // GET ALL RESOURCE CATEGORIES
    //#################################################################
	function getAllResourceCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM resource_category ORDER BY categoryName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $categoryName    = $row['categoryName'];

			$txt.= '"'.$categoryName.'",';
		}

		return substr($txt, 0, -1);
	}

    //#################################################################
    // CHECK IF A CATEGORY HAS ALREADY BEEN ADDED
    //#################################################################
	function checkCategoryAdded(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORIES
		$result = $connector->query("SELECT * FROM resource_category", array());
		$total	= $connector->numResults($result);

        //RETURN TOTAL
		return $total;
	}

    //#################################################################
    // CHECK WHICH CONTENT HAS TEXT
    //#################################################################
	function checkContentHasText($resourceContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
		$row	= $connector->fetchArray($result);

        //SET VARIABLES
        $paragraph  = $row['paragraph'];
        $code       = $row['code'];

        //CHECK WHICH ONE HAS CONTENT
        if($paragraph != ''){
            return 'para';
        }elseif($code !=''){
            return 'code';
        }
	}

    //#################################################################
    // GET ALL SOFTWARES
    //#################################################################
	function getAllSoftwares(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM softwares ORDER BY softwareName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $softwareName    = $row['softwareName'];

			$txt.= '"'.$softwareName.'",';
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
	function getTags($field, $resourceID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLE
            $tagHolder = $row[$field];

            //REMOVE FIRST AND LAST CHARACTER
            $tagString = substr($tagHolder, 1,-1);

            //TURN INTO ARRAY
            $tagArray = explode(",", $tagString);

            //LOOP THROUGH ARRAY
            foreach($tagArray as $tags){
                //GET NAME OF RESOURCE CATEGORY ID
                $result2    = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ? AND deletedBy = ?", array($tags, '0'));
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
    // GENERATE AFFILIATE TAGS FROM DATABASE
    //#################################################################
	function getAffiliateTags($resourceID, $field){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
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
    // GET DATE OR TIME FROM DATABASE
    //#################################################################
	function getDateTime($field, $resourceID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLE
            $publishDate = $row['publishDate'];

            //CONVERT DATETIME TO UNIX
            $info = strtotime($publishDate);

            //CHECK WHICH INFORMATION IS Required
            if($field == 'time'){
                $txt = date("H : i" ,$info);
            }elseif($field == 'date'){
                $txt = date("Y-m-d" ,$info);
            }

        }

        //RETURN OUTPUT
        return $txt;
	}

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET RESOURCE
		$result = $connector->query("SELECT * FROM resource WHERE resourceID = ? AND deletedBy = ?", array($resourceID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['resourceDescription']).' '.strip_tags($row['resourceName']);
		}

        //GET ALL RESOURCE CONTENT PARAGRAPHS
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceID = ? AND deletedBy = ?", array($resourceID, 0));
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
	function getMetaDescription($resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM resource WHERE resourceID = ? AND deletedBy = ?", array($resourceID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['resourceDescription']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE resourceID = ?", array($resourceID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (resourceID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($resourceID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE resourceID = ?",
												array($keywords, $description, $resourceID));
		}
	}

    //#################################################################
    // GET META KEYWORDS FOR CATEGORY
    //#################################################################
	function getMetaKeywordCategory($resourceCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ? AND deletedBy = ?", array($resourceCatID, 0));
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
	function getMetaDescriptionCategory($resourceCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ? AND deletedBy = ?", array($resourceCatID, 0));
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
	function updateMetaDetailsCategory($keywords, $description, $resourceCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE resourceCatID = ?", array($resourceCatID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (resourceCatID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($resourceCatID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE resourceCatID = ?",
												array($keywords, $description, $resourceCatID));
		}
	}

    //#################################################################
	// ADD RESOURCE INTO SEARCH INDEX
	//#################################################################
	function addResourceSearchIndex($resourceID, $title, $keywords, $paragraph){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//GET INDEX INFO
		$result	= $connector->query("SELECT * FROM search_index WHERE resourceID = ?", array($resourceID));
		$row	= $connector->fetchArray($result);
		$total	= $connector->numResults($result);

		//CHECK IF RESOURCE IS ALREADY INDEX
		if($total == 0){
			//INSERT RESOURCE SEARCH INDEX
			$insert	= $connector->query("INSERT INTO search_index (title, keywords, content, resourceID)
										VALUES(?, ?, ?, ?)"
										, array($title, $keywords, $paragraph, $resourceID));
		}else{
			//UPDATE RESOURCE SEARCH INDEX
			$update	= $connector->query("UPDATE search_index SET
										title			= ?,
										keywords		= ?,
										content			= ?
										WHERE resourceID = ?"
										, array($title, $keywords, $paragraph, $resourceID));
		}

	}

	//#################################################################
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($resourceCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ?", array($resourceCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET RESOURCE INFORMATION
    //#################################################################
	function getResourceInfo($resourceID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET CATEGORY IMAGE
    //#################################################################
	function getCategoryImage($resourceCatID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ?", array($resourceCatID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['categoryImage'];
		$imageTitle	= $row['categoryImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

    //#################################################################
    // GET RESOURCE IMAGE
    //#################################################################
	function getResourceImage($resourceID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['resourceImageFile'];
		$imageTitle	= $row['resourceImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET RESOURCE ZIP FILE
    //#################################################################
	function getResourceZipFile($resourceID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
		$row	= $connector->fetchArray($result);
		$zipFile	= $row['zipFile'];
		$zipFileTitle	= $row['zipFileTitle'];

        //CHECK IF ZIP FILE IS AVAILABLE
        if($zipFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="file-spacing" align="center"><div class="image-header"><b>Current File:</b></div><br /><a href="'.$web_root.'cms-zip/'.$zipFile.'" title="'.$zipFileTitle.'">'.$zipFileTitle.'</a><div class="download-text"><i>(Click on link above to download the file)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;
	}

	//#################################################################
    // CHECK IF RESOURCE CATEGORY IS IN DATABASE
    //#################################################################
	function checkCategoryDatabase($resourceCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ?", array($resourceCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}

	}

	//#################################################################
    // CHECK IF RESOURCE IS IN DATABASE
    //#################################################################
	function checkResourceDatabase($resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
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
    // GET TOTAL RESOURCE CATEGORIES
    //#################################################################
	function getTotalCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_category WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET EMPTY RESOURCES
    //#################################################################
	function getEmptyResources(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$resourceID	= $row['resourceID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM resource_content WHERE resourceID = ? AND deletedBy = ?", array($resourceID, '0'));
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
    // GET TOTAL RESOURCES
    //#################################################################
	function getTotalResources(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL VIDEOS
    //#################################################################
	function getTotalVideos(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_content WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL PUBLISHED RESOURCES
    //#################################################################
	function getPublishedResources(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

        $currentDate = date("Y-m-d H:i:s");

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource WHERE publishDate <= ?", array($currentDate));
		$totalPublished = $connector->numResults($result);

		//RETURN VAlUE
		return $totalPublished;

	}

    //#################################################################
    // RESOURCE CATEGORY ARCHITECTURE
    //#################################################################
	function categoryArchitecture($cms_root, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM resource_category WHERE deletedBy = ? ORDER BY categoryName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$resourceCatID      = $row['resourceCatID'];
				$categoryName       = $row['categoryName'];
				$categoryImageTitle	= $row['categoryImageTitle'];
				$categoryImage		= $row['categoryImage'];
                $paragraph          = $row['categoryDescription'];

                //GET RESOURCE INFO
                $result2        = $connector->query("SELECT * FROM resource WHERE resourceCatIDs LIKE ?",array("%,$resourceCatID,%"));
                $totalResults   = $connector->numResults($result2);

                //CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder">';

					//IF AN IMAGE IS AVAILABLE
					if($categoryImage != ''){
						$txt.= '<div class="paragraph-image-category">
							<img src="'.$web_root.'cms-images/medium/'.$categoryImage.'" alt="'.$categoryImageTitle.'" title="'.$categoryImageTitle.'" border="0"/>
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
						$txt.='<form name="delete_resource_category'.$resourceCatID.'">
                        <input type="hidden" name="delete_resource_category" value="1">
                        <input type="hidden" name="resourceCatID" value="'.$resourceCatID.'">
							<a href="javascript:deleteResourceCategory('.$resourceCatID.')" title="Remove Category">Remove Category</a>
						</form>';
                    }else{
                        $txt.='<a href="javascript:noDeleteCategory()" title="Remove Category">Remove Category</a>';
                    }

                    $txt.= '<a href="'.$cms_root.'resource-manager/edit-resource-category.php?resourceCatID='.$resourceCatID.'" title="Edit Category">Edit Category</a>
						<div class="clear"></div>
						</div>
                </div>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Categories available. <a href="'.$cms_root.'resource-manager/add-resource-category.php" title="Add Tutorial Category">Please add a category here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // RESOURCE ARCHITECTURE
    //#################################################################
	function resourceArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];
        $currentDate = date("Y-m-d H:i:s");

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM resource WHERE deletedBy = ? ORDER BY publishDate DESC", array('0'));
		$resourceTotal = $connector->numResults($result);

		//IF RESOURCES ARE AVAILABLE
		if($resourceTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$empty			        = '';
				$empty_bg		        = '';
				$resourceID		        = $row['resourceID'];
				$resourceName	        = $row['resourceName'];
                $publishDate            = $row['publishDate'];

				//GET ALL CONTENT FOR A RESOURCE
				$result2	= $connector->query("SELECT * FROM resource_content WHERE resourceID = ? AND deletedBy = ?", array($resourceID, '0'));
				$resourceTotal	= $connector->numResults($result2);

				//IF RESOURCE IS EMPTY
				if($resourceTotal == 0){
					$empty		= '<span class="empty-category-text">(Empty)</span>';
					$empty_bg	='class="empty-category"';
				}

                //CHECK IF RESOURCE HAS ALREADY BEEN PUBLISHED
                if($publishDate > $currentDate){
                    $published		= '<span class="unpublished-post-text">(Not yet Published)</span>';
                }else{
                    $published		= '<span class="published-post-text">(Published)</span>';
                }

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$empty_bg.'>'.$resourceName.' '.$empty.' '.$published.'</td>
                    <td '.$empty_bg.' align="center">'.date("j F Y H:i", strtotime($publishDate)).'</td>
                    <td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'resource-manager/manage-resource-content.php?resourceID='.$resourceID.'" title="Manage">Manage</a>
					</td>
					<td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'resource-manager/edit-resource.php?resourceID='.$resourceID.'" title="Modify">Modify</a>
					</td>
					<td '.$empty_bg.' align="center">';

					$txt.='<form name="delete_resource'.$resourceID.'">
						<input type="hidden" name="delete_resource" value="1">
						<input type="hidden" name="resourceID" value="'.$resourceID.'">
						<a href="javascript:deleteResource('.$resourceID.')" title="Remove">Remove</a>
					</form>';

					$txt.= '</td>
				  </tr>';

			}
		}
		//IF NO PLAYLISTS ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Resources available. <a href="'.$cms_root.'resource-manager/add-resource.php" title="Add Resource">Please add a resource here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // RESOURCE ARCHITECTURE (REMOVED)
    //#################################################################
	function resourceArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM resource WHERE deletedBy != ? ORDER BY resourceName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$resourceID		= $row['resourceID'];
			$resourceName	= $row['resourceName'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$resourceName.'</td>
				<td align="center">
				<form name="recover_resource'.$resourceID.'">
					<input type="hidden" name="recover_resource" value="1">
					<input type="hidden" name="resourceID" value="'.$resourceID.'">
					<a href="javascript:recoverResource('.$resourceID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // RESOURCE CONTENT ARCHITECTURE
    //#################################################################
	function resourceContentArchitecture($cms_root, $web_root, $resourceID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET RESOURCE CONTENT
		$result = $connector->query("SELECT * FROM resource_content WHERE deletedBy = ?  AND resourceID = ? ORDER BY sequence ASC", array('0', $resourceID));
		$paragraphsTotal = $connector->numResults($result);

		//IF CONTENT ARE AVAILABLE
		if($paragraphsTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$resourceContentID      = $row['resourceContentID'];
				$paragraphTitle		    = $row['paragraphTitle'];
				$paragraph			    = $row['paragraph'];
                $code                   = $row['code'];
				$imageFile			    = $row['imageFile'];
				$imageTitle			    = $row['imageTitle'];
				$documentFile		    = $row['documentFile'];
				$documentTitle		    = $row['documentTitle'];
				$videoUrl			    = $row['videoUrl'];
                $sequence               = $row['sequence'];

				//CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

                //CHECK CODE LENGTH
                $code	= strip_tags($code);
				if(strlen($code) > 450){
					$code	= substr($code, 0, 450).'...';
				}

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$resourceContentID.'">';

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

                    //CHECK WHICH PARAGRAPH HAS CONTENT
                    if($paragraph != ''){
                        $txt.= '<div class="paragraph-text">'.$paragraph.'</div>
                        <div class="clear"></div>';
                    }elseif($code != ''){
                        $txt.= '<div class="paragraph-text">'.$code.'</div>
                        <div class="clear"></div>';
                    }

					//IF A VIDEO IS AVAILABLE
					if($videoUrl != ''){
                    	$txt.= '<div class="paragraph-links">Video: <a href="'.$videoUrl.'" target="_blank">'.$videoUrl.'</a></div>';
					}

					//IF A DOCUMENT IS AVAILABLE
					if($documentFile != ''){
						$txt.= '<div class="paragraph-links">Document: <a href="'.$web_root.'cms-documents/'.$documentFile.'" title="'.$documentTitle.'" target="_blank">'.$documentTitle.'</a></div>';
					}

					$txt.= '<div class="module-manage-content-links">
						<form name="delete_paragraph'.$resourceContentID.'">
							<input type="hidden" name="delete_paragraph" value="1">
							<input type="hidden" name="resourceContentID" value="'.$resourceContentID.'">
							<input type="hidden" name="resourceID" value="'.$resourceID.'">
							<a href="javascript:deleteParagraph('.$resourceContentID.')" title="Remove Paragraph">Remove Paragraph</a>
						</form>
						<a href="'.$cms_root.'resource-manager/edit-paragraph.php?resourceContentID='.$resourceContentID.'&resourceID='.$resourceID.'" title="Edit Paragraph">Edit Paragraph</a>
						<div class="clear"></div>
						</div>
                </div>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Paragraphs available. <a href="'.$cms_root.'resource-manager/add-paragraph.php?resourceID='.$resourceID.'" title="Add Paragraph">Please add a paragraph here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF ANY RESOURCES HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedResources(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM resource WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF RESOURCE CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkCategoryChanges($title, $paragraph, $image_title, $resourceCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM resource_category WHERE categoryName = ? AND categoryImageTitle = ? AND resourceCatID = ? AND categoryDescription = ?", array($title, $image_title, $resourceCatID, $paragraph));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // CHECK IF RESOURCE INFO HAS BEEN CHANGED
    //#################################################################
	function checkResourceChanges($title, $paragraph, $categories, $date, $time, $image_title, $zip_title, $resourceID, $affiliateIDs){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET PUBLISH DATE
        $publishDate = date("Y-m-d H:i:s", strtotime($date.' '.str_replace(' ', '',$time)));

        //CHECK AFFILIATE ID
        if($affiliateIDs == ',,'){
            $affiliateIDs = '';
        }

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM resource WHERE resourceName = ? AND resourceCatIDs = ? AND resourceDescription = ? AND resourceImageTitle = ? AND zipFileTitle = ? AND publishDate = ? AND resourceID = ? AND affiliateIDs = ?", array($title, $categories, $paragraph, $image_title, $zip_title, $publishDate, $resourceID, $affiliateIDs));
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
		$insert = $connector->query("INSERT INTO resource_category (categoryName, categoryDescription, categoryImageTitle, categoryImage, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($title, $paragraph, $image_title, $imageFile, $currentUser, $currentDate));

        //RETURN CATEGORY ID
        $result = $connector->query("SELECT * FROM resource_category ORDER BY resourceCatID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['resourceCatID'];

	}

    //#################################################################
	//OVERWRITE RESOURCE
	//#################################################################
	function overwriteResource($resource_title, $paragraph, $categories, $date, $time){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$playlist_name	= strip_tags($resource_title);
        $categories	    = strip_tags($categories);
        $date	        = strip_tags($date);
        $time	        = strip_tags($time);

        //SRET PUBLISH DATE
        $publishDate = date("Y-m-d H:i:s", strtotime($date.' '.str_replace(' ', '',$time)));

		//UPDATE USER
		$update = $connector->query("UPDATE resource SET
                                    resourceDescription = ?,
                                    publishDate = ?,
                                    resourceCatIDs = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE resourceName = ?",
									array($paragraph, $publishDate, $categories, '0', '0000-00-00 00:00:00', $resource_title));

        //GET PLAYLIST ID
        $result = $connector->query("SELECT * FROM resource WHERE resourceName = ?", array($resource_title));
        $row    = $connector->fetchArray($result);

        //RETURN ID
        return $row['resourceID'];

	}

	//#################################################################
    // UPDATE RESOURCE CATEGORY
    //#################################################################
	function updateCategory($title, $paragraph, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $resourceCatID){
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
        $result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ?", array($resourceCatID));
        $row    = $connector->fetchArray($result);
        $image  = $row['categoryImage'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE resource_category SET
									categoryName = ?,
                                    categoryDescription = ?,
                                    categoryImageTitle = ?,
                                    categoryImage = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE resourceCatID = ?",
									array($title, $paragraph, $image_title, $imageFile, $modifiedBy, $modifiedDate, $modifiedNumber, $resourceCatID));

	}

    //#################################################################
    // UPDATE RESOURCE
    //#################################################################
	function updateResource($title, $paragraph, $categories, $date, $time, $imageFile, $image_title, $zipFile, $zip_title, $modifiedBy, $modifiedDate, $modifiedNumber, $resourceID, $uid, $resource_url, $affiliateIDs){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //CHECK IF AFFILIATE IDS IS EMPTY
        if($affiliateIDs == ',,'){
            $affiliateIDs = '';
        }

		//STRIP TAGS
		$title          = strip_tags($title);
        $image_title	= strip_tags($image_title);
        $zip_title	    = strip_tags($zip_title);
        $categories     = strip_tags($categories);
        $date           = strip_tags($date);
        $time           = strip_tags($time);
        $resource_url   = strip_tags($resource_url);

        //SET PUBLISH DATE
        $publishDate = date("Y-m-d H:i:s", strtotime($date.' '.str_replace(' ', '',$time)));

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //ZIP DIRECTORY
        $zipfileDirectory		= '../../cms-zip/';

        //GET OLD IMAGE AND ZIP FILE NAME
        $result = $connector->query("SELECT * FROM resource WHERE resourceID = ?", array($resourceID));
        $row    = $connector->fetchArray($result);
        $image  = $row['resourceImageFile'];
        $zip    = $row['zipFile'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

        //CHECK IF A NEW ZIP FILE HAS BEEN ADDED
        if($zipFile != $zip){
            //REMOVE ZIP FILE
            unlink($zipfileDirectory.$zip);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE resource SET
									resourceName = ?,
                                    resourceDescription = ?,
                                    resourceCatIDs = ?,
                                    resourceImageTitle = ?,
                                    resourceImageFile = ?,
                                    zipFile = ?,
                                    zipFileTitle = ?,
                                    publishDate = ?,
                                    zipFileIdentifier = ?,
                                    url = ?,
                                    affiliateIDs = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE resourceID = ?",
									array($title, $paragraph, $categories, $image_title, $imageFile, $zipFile, $zip_title, $publishDate, $uid, $resource_url, $affiliateIDs, $modifiedBy, $modifiedDate, $modifiedNumber, $resourceID));

	}

	//#################################################################
    // DELETE CATEGORY
    //#################################################################
	function deleteCategory($resourceCatID){
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
        $result = $connector->query("SELECT * FROM resource_category WHERE resourceCatID = ?",array($resourceCatID));
        $row    = $connector->fetchArray($result);
        $categoryImage = $row['categoryImage'];

        //REMOVE IMAGES
        unlink($largeDirectory.$categoryImage);
        unlink($mediumDirectory.$categoryImage);
        unlink($smallDirectory.$categoryImage);

        //REMOVE RESOURCE
		$remove = $connector->query("DELETE FROM resource_category WHERE resourceCatID = ?", array($resourceCatID));

        //REMOVE META DETAILS
        $remove = $connector->query("DELETE FROM meta_details WHERE resourceCatID = ?", array($resourceCatID));

		//REMOVE USER
		/*$remove = $connector->query("UPDATE video_tutorials_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutCatID = ?",
									array($currentUser, $currentDate, $videoTutCatID));*/

	}

    //#################################################################
    // DELETE RESOURCE
    //#################################################################
	function deleteResource($resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE resource SET
									deletedBy = ?,
									deletedDate = ?
									WHERE resourceID = ?",
									array($currentUser, $currentDate, $resourceID));

	}

    //#################################################################
    // ADD RESOURCE
    //#################################################################
	function addResource($title, $paragraph, $categories, $image_title, $imageFile, $zip_title, $zipFile, $date, $time, $uid, $resource_url, $affiliateIDs){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //CHECK IF AFFILIATE IDS IS EMPTY
        if($affiliateIDs == ',,'){
            $affiliateIDs = '';
        }

		//STRIP INFO
		$title          = strip_tags($title);
        $categories     = strip_tags($categories);
        //$softwares      = strip_tags($softwares);
        $image_title	= strip_tags($image_title);
        $zip_title      = strip_tags($zip_title);
        $resource_url   = strip_tags($resource_url);

        //SET PUBLISH DATE
        $publishDate = date("Y-m-d H:i:s", strtotime($date.' '.str_replace(' ', '',$time)));

		//ADD USER
		$insert = $connector->query("INSERT INTO resource (resourceCatIDs, resourceName, resourceDescription, resourceImageTitle, resourceImageFile, zipFile, zipFileTitle, publishDate, url, affiliateIDs, zipFileIdentifier, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($categories, $title, $paragraph, $image_title, $imageFile, $zipFile, $zip_title, $publishDate, $resource_url, $affiliateIDs, $uid, $currentUser, $currentDate));

        //RETURN RESOURCE ID
        $result = $connector->query("SELECT * FROM resource ORDER BY resourceID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['resourceID'];

	}

    //#################################################################
    // CHECK IF PARAGRPAH CONTNET IS IN DATABASE
    //#################################################################
	function checkParagraphContentDatabase($resourceID, $resourceContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceID = ? AND resourceContentID = ?", array($resourceID, $resourceContentID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // GET PARAGRAPH CONTENT INFORMATION
    //#################################################################
	function getParagraphContentInfo($resourceContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET PARAGRAPH CONTENT IMAGE
    //#################################################################
	function getParagraphContentImage($resourceContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
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
    // GET PARAGRAPH CONTENT DOCUMENT
    //#################################################################
	function getParagraphContentDocument($resourceContentID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
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
    // GET PARAGRAPH CONTENT VIDEO
    //#################################################################
	function getParagraphContentVideo($resourceContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
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
    // GET PARAGRAPH INFORMATION
    //#################################################################
	function getParagraphInfo($resourceContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // ADD PARAGRAPH
    //#################################################################
	function addParagraph($title, $paragraph, $code, $image_title, $imageFile, $docFile, $doc_title, $video, $resourceID){
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
		$result	= $connector->query("SELECT * FROM resource_content WHERE resourceID = ? AND deletedBy = ? ORDER BY sequence DESC", array($resourceID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO resource_content (resourceID, paragraphTitle, paragraph, code, imageFile, imageTitle, documentFile, documentTitle, videoUrl, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($resourceID, $title, $paragraph, $code, $imageFile, $image_title, $docFile, $doc_title, $video, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE RESOURCE PARAGRAPH
    //#################################################################
	function updateParagraph($title, $paragraph, $code, $image_title, $imageFile, $docFile, $doc_title, $video, $modifiedDate, $modifiedBy, $modifiedNumber, $resourceContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

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
        $result = $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
        $row    = $connector->fetchArray($result);
        $image  = $row['imageFile'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//ADD PAGES CONTENT
		$update			= $connector->query("UPDATE resource_content SET
                                            paragraphTitle  = ?,
                                            paragraph       = ?,
                                            code            = ?,
                                            imageFile       = ?,
                                            imageTitle      = ?,
                                            documentFile	= ?,
                                            documentTitle	= ?,
                                            videoUrl        = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE resourceContentID = ?",
                                            array($title, $paragraph, $code, $imageFile, $image_title, $docFile, $doc_title, $video, $modifiedBy, $modifiedNumber, $modifiedDate, $resourceContentID));

	}

    //#################################################################
    // DELETE PARAGRAPH
    //#################################################################
	function deleteParagraph($resourceContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//IMAGE PATHS
		$largeDirectory			= '../../cms-images/large/';
		$mediumDirectory		= '../../cms-images/medium/';
		$smallDirectory			= '../../cms-images/small/';

		//DOCUMENT PATH
		$docDirectory			= '../../cms-documents/';

		//REMOVE IMAGES
		$result	= $connector->query("SELECT * FROM resource_content WHERE resourceContentID = ?", array($resourceContentID));
		$row	= $connector->fetchArray($result);
		$imageFile		= $row['imageFile'];
		$documentFile	= $row['documentFile'];

		//DELETE IMAGES
		unlink($largeDirectory.$imageFile);
		unlink($mediumDirectory.$imageFile);
		unlink($smallDirectory.$imageFile);

		//DELETE DOCUMENT
		unlink($docDirectory.$documentFile);

		//REMOVE PARAGRPAH
		$remove = $connector->query("DELETE FROM resource_content WHERE resourceContentID = ?",array($resourceContentID));

	}

    //#################################################################
    // RECOVER RESOURCE
    //#################################################################
	function recoverResource($resourceID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE resource SET
									deletedBy = ?,
									deletedDate = ?
									WHERE resourceID = ?",
									array('0', '0000-00-00 00:00:00', $resourceID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM resource_category WHERE categoryName = ?", array($title));
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
				return 'removed_resource_category';
			}
		}

	}

    //#################################################################
    // CHECK IF RESOURCE NAME IS ALREADY IN USE
    //#################################################################
	function addResourceCheck($title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM resource WHERE resourceName = ?", array($title));
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
				return 'removed_resource';
			}
		}

	}

}

//DEFINE CLASS
$resourceManager = new resourceManager();

//#################################################################
// ADD RESOURCE CATEGORY
//#################################################################
if(isset($_POST['add_resource_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['category-title'];
    $paragraph      = $_POST['paragraph'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$resource_type	= $_POST['resource-type'];
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
	$v->validateString($title, 'Resource Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($resource_type == '' && $image_type == ''){

            //CHECK IF CATEGORY NAME IS ALREADY IN USE
			$category_used = $resourceManager->addCategoryCheck($title);
            if($category_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

    			//INSERT BLOG POST INTO DATABASE
    			$resourceCatID = $resourceManager->addCategory($title, $paragraph, $image_title, $imageFile);

                //GET META DETAILS
    			$keywords		= $resourceManager->getMetaKeywordCategory($resourceCatID);
                $description	= $resourceManager->getMetaDescriptionCategory($resourceCatID);

    			//UPDATE META DETAILS
    			$resourceManager->updateMetaDetailsCategory($keywords, $description, $resourceCatID);

                //REDIRECT USER
    			header("Location: ".$cms_root."resource-manager/crop-image.php?resourceCatID=".$resourceCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
        		exit;

			}
			//IF USER HAS BEEN REMOVED
			/*elseif($category_used == 'removed_resource_category'){
				//SET USER AS REMOVED
				$removed_tutorial_category = '1';
			}*/
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
if(isset($_POST['edit_resource_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title          = $_POST['category-title'];
    $paragraph      = $_POST['paragraph'];
	$resourceCatID	= $_POST['resourceCatID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$resource_type	= $_POST['resource-type'];
    $image_type     = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($title, 'Resource Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($resource_type == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($resourceManager->checkCategoryChanges($title, $paragraph, $image_title, $resourceCatID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

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
				$resourceManager->updateCategory($title, $paragraph, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $resourceCatID);

                //GET META DETAILS
    			$keywords		= $resourceManager->getMetaKeywordCategory($resourceCatID);
    			$description	= $resourceManager->getMetaDescriptionCategory($resourceCatID);

    			//UPDATE META DETAILS
    			$resourceManager->updateMetaDetailsCategory($keywords, $description, $resourceCatID);

                //IF A NEW IMAGE HAS BEEN UPLOADED
                if($_FILES[$inputField]["tmp_name"] != ""){
                //REDIRECT USER
    			    header("Location: ".$cms_root."resource-manager/crop-image.php?resourceCatID=".$resourceCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=3");
            		exit;
                }else{
                    header("Location: ".$cms_root."resource-manager/manage-resource-category.php?message=3");
            		exit;
                }

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."resource-manager/manage-resource-category.php");
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
if(isset($_POST['delete_resource_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $resourceCatID	= $_POST['resourceCatID'];

    //SET USER AS REMOVED IN DATABASE
    $resourceManager->deleteCategory($resourceCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."resource-manager/manage-resource-category.php?message=5");
    exit;
}

//#################################################################
// ADD RESOURCE
//#################################################################
if(isset($_POST['add_resource'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['resource-title'];
    $paragraph      = $_POST['paragraph'];
    $categories     = $_POST['categories'];
    //$softwares      = $_POST['required-softwares'];
    $affiliates     = $_POST['affiliates'];
	$image_title	= $_POST['image-title'];
    $zip_title		= $_POST['zip-title'];
    $date           = $_POST['resource-date'];
    $time           = $_POST['resource-time'];

	//HONEY POTS
    $zip_type		= $_POST['zip-type'];
	$image_type		= $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //ZIP FILE PROPERTIES
	$zipField				= 'zip-file';
	$zipfileDirectory		= '../../cms-zip/';

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);
    $zip_title      = $userLogin->specialCharactersToHTMLEntity($zip_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Resource Title', 1, 200);
    $v->validateText($paragraph, 'Intro', 10);
    $v->validateDate($date, 'Publish Date');
	$v->validateTime($time, 'Publish Time');
    $v->validateTags($categories, 'Resource Categories');

    //IF A SOFTWARE HAS BEEN ADDED
    /*if($softwares != ''){
        $v->validateTags($softwares, 'Required Softwares');
    }*/

    //IF AN AFFILIATE HAS BEEN ADDED
    if($affiliates != ''){
        $v->validateTags($affiliates, 'Affiliate Links:');
    }

    //IF A DOCUMENT HAS BEEN ADDED
	$v->validateString($zip_title, 'Zip File Title',3, 150);
	$v->validateZipFile($zipField, 'Zip File');

    $v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($zip_type == '' && $image_type == ''){

            //GET ALL RESOURCE CATEGORY ID'S
            $resourceCatIDs         = ',';
            $categories             = substr($categories, 1, -1);
            $resourceCatNameArray   = explode(',', $categories);
            foreach($resourceCatNameArray as $resourceCatName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM resource_category WHERE categoryName = ? AND deletedBy = ?", array($resourceCatName, '0'));
                $row    = $connector->fetchArray($result);
                $resourceCatID  = $row['resourceCatID'];

                //INSERT INTO STRING
                $resourceCatIDs.= $resourceCatID.',';
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

            //CHECK IF RESOURCE NAME IS ALREADY IN USE
			$resource_used = $resourceManager->addResourceCheck($title);
            if($resource_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //IF A ZIP FILE HAS BEEN ADDED
    			if($_FILES[$zipField]["tmp_name"] != ""){
    				$zipFile		= $fileUploader->uploadDocuments($zipField, $zipfileDirectory, $zip_title);
    			}

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE UNIQUE IDENTIFIER
                $uid    = md5($title);

                //CREATE RESOURCE URL
                $resource_url = str_replace("'", "", $title);
                $resource_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($title));
                $resource_url = str_replace(' ', '-', $resource_url);

                //CHECK IF RESOURCE URL EXISTS
                $resource_url = $resourceManager->checkResourceURLExists($resource_url, '');

    			//INSERT BLOG POST INTO DATABASE
    			$resourceID = $resourceManager->addResource($title, $paragraph, $resourceCatIDs, $image_title, $imageFile, $zip_title, $zipFile, $date, $time, $uid, $resource_url, $affiliateIDs);

                //GET META DETAILS
    			$keywords		= $resourceManager->getMetaKeyword($resourceID);
                $description	= $resourceManager->getMetaDescription($resourceID);

    			//UPDATE META DETAILS
    			$resourceManager->updateMetaDetails($keywords, $description, $resourceID);

                //ADD INFORMATION INTO SEARCH INDEX
                $resourceManager->addResourceSearchIndex($resourceID, $title, $keywords, $paragraph);

                //REDIRECT USER
    			header("Location: ".$cms_root."resource-manager/crop-image-resource.php?resourceID=".$resourceID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
        		exit;

			}
            //IF PLAYLIST HAS BEEN REMOVED
            elseif($resource_used == 'removed_resource'){
				//SET USER AS REMOVED
				$removed_resource = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Resource Name</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT RESOURCE
//#################################################################
if(isset($_POST['edit_resource'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $resourceID         = $_POST['resourceID'];
	$title              = $_POST['resource-title'];
    $paragraph          = $_POST['paragraph'];
    $categories         = $_POST['categories'];
    //$softwares          = $_POST['required-softwares'];
    $affiliates         = $_POST['affiliates'];
    $date               = $_POST['resource-date'];
    $time               = $_POST['resource-time'];
    $oldImage           = $_POST['oldImage'];
    $image_title        = $_POST['image-title'];
    $oldFile            = $_POST['oldFile'];
    $zip_title          = $_POST['zip-title'];

	$modifiedDate	    = $_POST['modifiedDate'];
	$modifiedBy		    = $_SESSION['cmsUser'];
	$modifiedNumber	    = $_POST['modifiedNumber'];

	//HONEY POTS
	$zip_type          = $_POST['zip-type'];
    $image_type        = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //ZIP FILE PROPERTIES
	$zipField				= 'zip-file';
	$zipfileDirectory		= '../../cms-zip/';

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);
    $zip_title      = $userLogin->specialCharactersToHTMLEntity($zip_title);

	//VALIDATION
    $v = new formValidation();
    $v->validateString($title, 'Resource Title', 1, 200);
    $v->validateText($paragraph, 'Intro', 10);
    $v->validateDate($date, 'Publish Date');
	$v->validateTime($time, 'Publish Time');
    $v->validateTags($categories, 'Resource Categories');

    //IF A SOFTWARE HAS BEEN ADDED
    /*if($softwares != ''){
        $v->validateTags($softwares, 'Required Softwares');
    }*/

    //IF AN AFFILIATE HAS BEEN ADDED
    if($affiliates != ''){
        $v->validateTags($affiliates, 'Affiliate Links:');
    }

	$v->validateString($zip_title, 'Zip File Title',3, 150);

    //IF A ZIP FILE HAS BEEN ADDED
    if($_FILES[$zipField]["tmp_name"] != ''){
        $v->validateZipFile($zipField, 'Zip File');
    }

    $v->validateString($image_title, 'Image Title',3, 150);

    //IF IMAGE HAS BEEN ADDED
    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($zip_type == '' && $image_type == ''){

            //GET ALL RESOURCE CATEGORY ID'S
            $resourceCatIDs         = ',';
            $categories             = substr($categories, 1, -1);
            $resourceCatNameArray   = explode(',', $categories);
            foreach($resourceCatNameArray as $resourceCatName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM resource_category WHERE categoryName = ? AND deletedBy = ?", array($resourceCatName, '0'));
                $row    = $connector->fetchArray($result);
                $resourceCatID  = $row['resourceCatID'];

                //INSERT INTO STRING
                $resourceCatIDs.= $resourceCatID.',';
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
			if($resourceManager->checkResourceChanges($title, $paragraph, $resourceCatIDs, $date, $time, $image_title, $zip_title, $resourceID, $affiliateIDs) == 'changed' || $_FILES[$inputField]["tmp_name"] != '' || $_FILES[$zipField]["tmp_name"] != ''){

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

                //IF A ZIP FILE HAS BEEN ADDED
    			if($_FILES[$zipField]["tmp_name"] != ""){
    				$zipFile		= $fileUploader->uploadDocuments($zipField, $zipfileDirectory, $zip_title);
    			}
                //IF NO NEW ZIP FILE HAS BEEN UPLOADED
                else{
                    $zipFile      = $oldFile;
                }

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE UNIQUE IDENTIFIER
                $uid    = md5($title);

                //CREATE RESOURCE URL
                $resource_url = str_replace("'", "", $title);
                $resource_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($title));
                $resource_url = str_replace(' ', '-', $resource_url);

                //CHECK IF RESOURCE URL EXISTS
                $resource_url = $resourceManager->checkResourceURLExists($resource_url, $resourceID);

				//UPDATE USER IN DATABASE
				$resourceManager->updateResource($title, $paragraph, $resourceCatIDs, $date, $time, $imageFile, $image_title, $zipFile, $zip_title, $modifiedBy, $modifiedDate, $modifiedNumber, $resourceID, $uid, $resource_url, $affiliateIDs);

                //GET META DETAILS
    			$keywords		= $resourceManager->getMetaKeyword($resourceID);
                $description	= $resourceManager->getMetaDescription($resourceID);

    			//UPDATE META DETAILS
    			$resourceManager->updateMetaDetails($keywords, $description, $resourceID);

                //ADD INFORMATION INTO SEARCH INDEX
                $resourceManager->addResourceSearchIndex($resourceID, $title, $keywords, $paragraph);

                //IF A NEW IMAGE HAS BEEN UPLOADED
                if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
                    header("Location: ".$cms_root."resource-manager/crop-image-resource.php?resourceID=".$resourceID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
            		exit;

                }else{
                    header("Location: ".$cms_root."resource-manager/index.php?message=4");
            		exit;
                }

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."resource-manager/");
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
//DELETE RESOURCE
//#################################################################
if(isset($_POST['delete_resource'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $resourceID	= $_POST['resourceID'];

    //SET USER AS REMOVED IN DATABASE
    $resourceManager->deleteResource($resourceID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."resource-manager/index.php?message=6");
    exit;
}

//#################################################################
//RECOVER RESOURCE
//#################################################################
if(isset($_POST['recover_resource'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $resourceID	= $_POST['resourceID'];

    //SET USER AS REMOVED IN DATABASE
    $resourceManager->recoverResource($resourceID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."resource-manager/index.php?message=9");
    exit;
}

//#################################################################
// REACTIVATE RESOURCE
//#################################################################
if(isset($_POST['reactivate-resource-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$resource_title		= $_POST['resource-title'];
    $paragraph		    = $_POST['paragraph'];
    $categories		    = $_POST['categories'];
    $date		        = $_POST['date'];
    $time               = $_POST['time'];

	//HONEY POTS
	$zip_type		= $_POST['zip-type'];
    $image_type		= $_POST['image-type'];

	if($zip_type == '' && $image_type == ''){

		//OVERWRITE USER
		$resourceID = $resourceManager->overwriteResource($resource_title, $paragraph, $categories, $date, $time);

        //GET META DETAILS
        $keywords		= $resourceManager->getMetaKeyword($resourceID);
        $description	= $resourceManager->getMetaDescription($resourceID);

        //UPDATE META DETAILS
        $resourceManager->updateMetaDetails($keywords, $description, $resourceID);

		//REDIRECT PAGE
		header("Location: ".$cms_root."resource-manager/index.php?message=10");
		exit;
	}else{
        //REDIRECT PAGE
        header("Location: ".$cms_root."resource-manager/");
		exit;
    }
}

//#################################################################
// ADD PARAGRAPH
//#################################################################
if(isset($_POST['add_paragraph'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $textHolder     = $_POST['textHolder'];
	$resourceID	    = $_POST['resourceID'];
	$title			= $_POST['paragraph-title'];
	$paragraph 		= $_POST['paragraph'];
    $code           = $_POST['code'];
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
    $code           = htmlentities($code);

	//VALIDATION
	$v = new formValidation();
    if($textHolder == 'code'){
        $v->validateText($code, 'Code', 10);
    }elseif($textHolder == 'para'){
        $v->validateText($paragraph, 'Paragraph', 10);
    }

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
			$resourceManager->addParagraph($title, $paragraph, $code, $image_title, $imageFile, $docFile, $doc_title, $video, $resourceID);

			//GET META DETAILS
			$keywords		= $resourceManager->getMetaKeyword($resourceID);
			$description	= $resourceManager->getMetaDescription($resourceID);

			//UPDATE META DETAILS
			$resourceManager->updateMetaDetails($keywords, $description, $resourceID);

            //GET META DETAILS
            $keywords		= $resourceManager->getMetaKeyword($resourceID);
            $description	= $resourceManager->getMetaDescription($resourceID);

            //UPDATE META DETAILS
            $resourceManager->updateMetaDetails($keywords, $description, $resourceID);

            //GET RESOURCE INFO
            $resourceName           = $resourceManager->getResourceInfo($resourceID, 'resourceName');
            $resourceDescription    = $resourceManager->getResourceInfo($resourceID, 'resourceDescription');

            //ADD INFORMATION INTO SEARCH INDEX
            $resourceManager->addResourceSearchIndex($resourceID, $resourceName, $keywords, $resourceDescription);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."resource-manager/crop-image-paragraph.php?resourceID=".$resourceID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=11");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."resource-manager/manage-resource-content.php?resourceID=".$resourceID."&message=11");
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
    $textHolder        = $_POST['textHolder'];
	$resourceID	       = $_POST['resourceID'];
    $resourceContentID = $_POST['resourceContentID'];
	$title             = $_POST['paragraph-title'];
	$paragraph         = $_POST['paragraph'];
    $code              = $_POST['code'];
	$video             = $_POST['youtube-vimeo-video'];
	$image_title       = $_POST['image-title'];
	$doc_title		   = $_POST['doc-title'];
    $removeImage       = $_POST['removeImage'];
    $removeDocument    = $_POST['removeDocument'];
    $removeVideo       = $_POST['removeVideo'];
    $oldImage          = $_POST['oldImage'];
    $oldDocument       = $_POST['oldDocument'];

    $modifiedDate      = $_POST['modifiedDate'];
    $modifiedBy		   = $_SESSION['cmsUser'];
    $modifiedNumber    = $_POST['modifiedNumber'];

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
    $code           = htmlentities($code);

	//VALIDATION
	$v = new formValidation();
    if($textHolder == 'code'){
        $v->validateText($code, 'Code', 10);
    }elseif($textHolder == 'para'){
        $v->validateText($paragraph, 'Paragraph', 10);
    }

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
                    $image_title    = $resourceManager->getParagraphContentInfo($resourceContentID, 'imageTitle');
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
                $doc_title      = $resourceManager->getParagraphContentInfo($resourceContentID, 'documentTitle');
            }

            //CHECK IF VIDEO NEEDS TO BE REMOVED
            if($removeVideo == 1){
                $video = '';
            }

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT PAGE CONTENT INTO DATABASE
			$resourceManager->updateParagraph($title, $paragraph, $code, $image_title, $imageFile, $docFile, $doc_title, $video, $modifiedDate, $modifiedBy, $modifiedNumber, $resourceContentID);

			//GET META DETAILS
			$keywords		= $resourceManager->getMetaKeyword($resourceID);
			$description	= $resourceManager->getMetaDescription($resourceID);

			//UPDATE META DETAILS
			$resourceManager->updateMetaDetails($keywords, $description, $resourceID);

            //GET RESOURCE INFO
            $resourceName           = $resourceManager->getResourceInfo($resourceID, 'resourceName');
            $resourceDescription    = $resourceManager->getResourceInfo($resourceID, 'resourceDescription');

            //ADD INFORMATION INTO SEARCH INDEX
            $resourceManager->addResourceSearchIndex($resourceID, $resourceName, $keywords, $resourceDescription);

			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
			if($_FILES[$inputField]["tmp_name"] != ""){
				header("Location: ".$cms_root."resource-manager/crop-image-paragraph.php?resourceID=".$resourceID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=12");
        		exit;
			}
			//REDIRECT TO BLOG POST
			else{
				header("Location: ".$cms_root."resource-manager/manage-resource-content.php?resourceID=".$resourceID."&message=12");
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
	$resourceContentID	= $_POST['resourceContentID'];
	$resourceID    	    = $_POST['resourceID'];

    //REMOVE PARAGRPH FROM DATABASE
    $resourceManager->deleteParagraph($resourceContentID);

    //GET META DETAILS
    $keywords = $resourceManager->getMetaKeyword($resourceID);

    //GET RESOURCE INFO
    $resourceName           = $resourceManager->getResourceInfo($resourceID, 'resourceName');
    $resourceDescription    = $resourceManager->getResourceInfo($resourceID, 'resourceDescription');

    //ADD INFORMATION INTO SEARCH INDEX
    $resourceManager->addResourceSearchIndex($resourceID, $resourceName, $keywords, $resourceDescription);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."resource-manager/manage-resource-content.php?resourceID=".$resourceID."&message=13");
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
if($categoryRatio == 1){
    $newWidth		= 350;
    $newHeight		= 190;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}elseif($paragraphRatio == 1){
    $newWidth		= 770;
    $newHeight		= 433;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}
elseif($resourceRatio == 1){
    $newWidth		= 770;
    $newHeight		= 328;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//CROP IMAGE FOR CATEGORY WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$resourceCatID		= $_POST['resourceCatID'];
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
	header("Location: ".$cms_root."resource-manager/manage-resource-category.php?message=".$message);
    exit;
}

//CROP IMAGE FOR RESOURCE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop_resource'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$resourceID        	= $_POST['resourceID'];
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
	header("Location: ".$cms_root."resource-manager/index.php?message=".$message);
    exit;
}

//CROP IMAGE FOR RESOURCE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop_paragraph'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$resourceID        	= $_POST['resourceID'];
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
	header("Location: ".$cms_root."resource-manager/manage-resource-content.php?resourceID=".$resourceID."&message=".$message);
    exit;
}
###################################################################
?>
