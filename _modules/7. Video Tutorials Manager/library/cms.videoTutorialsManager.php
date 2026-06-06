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

class videoTutorialManager extends systemConfig{
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
			case 2: $displayMessage = 'A new Playlist has successfully been added.'; break;
            case 3: $displayMessage = 'The selected Category has successfully been updated.'; break;
			case 4: $displayMessage = 'The selected Playlist has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Category has successfully been removed.'; break;
			case 6: $displayMessage = 'The selected Playlist has successfully been removed.'; break;
			case 7: $displayMessage = 'The selected Category has successfully been recovered.'; break;
			case 8: $displayMessage = 'The selected Category has successfully been re-activated.'; break;
			case 9: $displayMessage = 'The selected Playlist has successfully been recovered.'; break;
			case 10: $displayMessage = 'The selected Playlist has successfully been re-activated.'; break;
			case 11: $displayMessage = 'A new Video(s) has successfully been added.'; break;
			case 12: $displayMessage = 'The selected Video has successfully been updated.'; break;
			case 13: $displayMessage = 'The selected Video has successfully been removed.'; break;
            case 14: $displayMessage = 'The selected Playlist has successfully been published.'; break;
            case 15: $displayMessage = 'The selected Playlist has successfully been unpublished.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

    //#################################################################
	//CHECK VIDEO URL EXISTS
	//#################################################################
	function checkVideoURLExists($url, $videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VATRIABLES
        $count = 1;
        $proceed = 1;
        $newURL = '';

        //GET CURRENT URL USED
        $currentURL = $this->getPlaylistInfo($videoTutPlaylistID, 'url');

        //CHECK IF URL EXISTS
        $result = $connector->query("SELECT url FROM video_tutorials_playlist WHERE url = ? LIMIT 0,1", array($url));
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
                    $result2    = $connector->query("SELECT url FROM video_tutorials_playlist WHERE url = ? LIMIT 0,1", array($newURL));
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
    // GET ALL VIDEO TUTORIAL CATEGORIES
    //#################################################################
	function getAllVideoTutorialCategories(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM video_tutorials_category ORDER BY videoTutCatName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $videoTutCatName    = $row['videoTutCatName'];

			$txt.= '"'.$videoTutCatName.'",';
		}

		return substr($txt, 0, -1);
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
    // CHECK IF A CATEGORY HAS ALREADY BEEN ADDED
    //#################################################################
	function checkCategoryAdded(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORIES
		$result = $connector->query("SELECT * FROM video_tutorials_category", array());
		$total	= $connector->numResults($result);

        //RETURN TOTAL
		return $total;
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
	function getTags($field, $videoTutPlaylistID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
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
                $result2    = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ? AND deletedBy = ?", array($tags, '0'));
                $row2       = $connector->fetchArray($result2);
                $categoryName   = $row2['videoTutCatName'];

                //GENERATE OUTPUT
                $txt.= '<li>'.$categoryName.'</li>';
            }
        }

        //RETURN OUTPUT
        return $txt;
	}

    //#################################################################
    // GENERATE SOFTWARE TAGS FROM DATABASE
    //#################################################################
	function getSoftwareTags($field, $videoTutPlaylistID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
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
                $result2    = $connector->query("SELECT * FROM softwares WHERE softwareID = ? AND deletedBy = ?", array($tags, '0'));
                $row2       = $connector->fetchArray($result2);
                $categoryName   = $row2['softwareName'];

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
	function getAffiliateTags($field, $videoTutPlaylistID){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT ARRAY
        $txt = '';

        //GET TAGS FROM DATABASE
        $result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
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
                $result2    = $connector->query("SELECT * FROM affiliate WHERE affiliateID = ? AND deletedBy = ?", array($tags, '0'));
                $row2       = $connector->fetchArray($result2);
                $categoryName   = $row2['affTitle'];

                //GENERATE OUTPUT
                $txt.= '<li>'.$categoryName.'</li>';
            }
        }

        //RETURN OUTPUT
        return $txt;
	}

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ? AND deletedBy = ?", array($videoTutPlaylistID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['videoTutPlaylistIntro']).' '.strip_tags($row['videoTutPlaylistTitle']);
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
	function getMetaDescription($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ? AND deletedBy = ?", array($videoTutPlaylistID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['videoTutPlaylistIntro']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (videoTutPlaylistID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($videoTutPlaylistID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE videoTutPlaylistID = ?",
												array($keywords, $description, $videoTutPlaylistID));
		}
	}

    //#################################################################
    // GET META KEYWORDS FOR CATEGORY
    //#################################################################
	function getMetaKeywordCategory($videoTutCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ? AND deletedBy = ?", array($videoTutCatID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['videoTutCatDescription']).' '.strip_tags($row['videoTutCatName']);
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
	function getMetaDescriptionCategory($videoTutCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ? AND deletedBy = ?", array($videoTutCatID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['videoTutCatDescription']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS FOR CATEGORY
	//#################################################################
	function updateMetaDetailsCategory($keywords, $description, $videoTutCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE videoTutCatID = ?", array($videoTutCatID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (videoTutCatID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($videoTutCatID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE videoTutCatID = ?",
												array($keywords, $description, $videoTutCatID));
		}
	}

    //#################################################################
	// ADD VIDEO INTO SEARCH INDEX
	//#################################################################
	function addVideoSearchIndex($videoTutContentID, $videoTitle){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//DEFAULT VARIABLES
		$currentDate	= date('Y-m-d H:i:s');

		//GET INDEX INFO
		$result	= $connector->query("SELECT * FROM search_index WHERE videoTutContentID = ?", array($videoTutContentID));
		$row	= $connector->fetchArray($result);
		$total	= $connector->numResults($result);

		//CHECK IF VIDEO IS ALREADY INDEX
		if($total == 0){
			//INSERT VIDEO SEARCH INDEX
			$insert	= $connector->query("INSERT INTO search_index (title, videoTutContentID)
										VALUES(?, ?)"
										, array($videoTitle, $videoTutContentID));
		}else{
			//UPDATE VIDEO SEARCH INDEX
			$update	= $connector->query("UPDATE search_index SET
										title			= ?
										WHERE resourceID = ?"
										, array($videoTitle, $videoTutContentID));
		}

	}

    //#################################################################
	// REMOVE VIDEO FROM SEARCH INDEX
	//#################################################################
	function removeVideoSearchIndex($videoTutContentID){
		//CONNECT TO DATABASE
		$connector 		= new DbConnector();

		//DELETE INDEX INFO
		$result	= $connector->query("DELETE FROM search_index WHERE videoTutContentID = ?", array($videoTutContentID));
	}

	//#################################################################
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($videoTutCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ?", array($videoTutCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET PLAYLIST INFORMATION
    //#################################################################
	function getPlaylistInfo($videoTutPlaylistID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET VIDEO INFORMATION
    //#################################################################
	function getVideoInfo($videoTutContentID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutContentID = ?", array($videoTutContentID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET VIDEO CONTENT VIDEO
    //#################################################################
	function getVideoContentVideo($videoTutContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutContentID = ?", array($videoTutContentID));
		$row	= $connector->fetchArray($result);
		$videoUrl	= $row['videoLink'];

		//IF URL IS YOUTUBE
		if(strpos($videoUrl,'youtube') !== false){

			//GENERATE OUTPUT
			$embedYouTube	= str_replace("watch?v=", "embed/", $videoUrl);

			$txt.= '<div class="video-spacing" align="center"><div class="video-header"><b>Current Video:</b></div><br /><iframe width="560" height="315" src="'.$embedYouTube.'" frameborder="0" allowfullscreen></iframe></div>';
		}
		//IT URL IS VIMEO
		elseif(strpos($videoUrl,'vimeo') !== false){

			//GENERATE OUTPUT
			$embedVimeo = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $videoUrl);

			$txt.= '<div class="video-spacing" align="center"><div class="video-header"><b>Current Video:</b></div><br /><iframe src="'.$embedVimeo.'" width="560" height="315" frameborder="0" allowfullscreen></iframe></div>';
		}

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET CATEGORY IMAGE
    //#################################################################
	function getCategoryImage($videoTutCatID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ?", array($videoTutCatID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['videoTutCatImage'];
		$imageTitle	= $row['videoTutCatImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

    //#################################################################
    // GET PLAYLIST IMAGE
    //#################################################################
	function getPlaylistImage($videoTutPlaylistID, $web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
		$row	= $connector->fetchArray($result);
		$imageFile	= $row['videoTutPlaylistImage'];
		$imageTitle	= $row['videoTutPlaylistImageTitle'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($imageFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$imageFile.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$imageFile.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div></div>';
        }

		//RETURN OUTPUT
		return $txt;


	}

	//#################################################################
    // CHECK IF VIDEO TUTORIAL CATEGORY IS IN DATABASE
    //#################################################################
	function checkCategoryDatabase($videoTutCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY TOTAL
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ?", array($videoTutCatID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}

	}

	//#################################################################
    // CHECK IF PLAYLIST IS IN DATABASE
    //#################################################################
	function checkPlaylistDatabase($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF VIDEO CONTENT IS IN DATABASE
    //#################################################################
    function checkVideoContentDatabase($videoTutContentID, $videoTutPlaylistID){
        //CONNECT TO DATABASE
        $connector = new dbConnector();

        //GET QUOTE TOTAL
        $result = $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutContentID = ? AND videoTutPlaylistID = ?", array($videoTutContentID, $videoTutPlaylistID));
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
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET EMPTY PLAYLISTS
    //#################################################################
	function getEmptyPlaylists(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$videoTutPlaylistID	= $row['videoTutPlaylistID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutPlaylistID = ? AND deletedBy = ?", array($videoTutPlaylistID, '0'));
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
    // GET TOTAL PLAYLISTS
    //#################################################################
	function getTotalPlaylists(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE deletedBy = ?", array('0'));
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
    // GET TOTAL PUBLISHED PLAYLISTS
    //#################################################################
	function getPublishedPlaylists(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPublished = ?", array('1'));
		$totalPublished = $connector->numResults($result);

		//RETURN VAlUE
		return $totalPublished;

	}

	//#################################################################
    // GET TOTAL UNPUBLISHED PLAYLISTS
    //#################################################################
	function getTotalUnpublishedPlaylists(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPublished = ?", array('0'));
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
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE deletedBy = ? ORDER BY videoTutCatName ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$videoTutCatID          = $row['videoTutCatID'];
				$videoTutCatName		= $row['videoTutCatName'];
				$videoTutCatImageTitle	= $row['videoTutCatImageTitle'];
				$videoTutCatImage		= $row['videoTutCatImage'];
                $paragraph              = $row['videoTutCatDescription'];

                //GET PLAYLIST INFO
                $result2        = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutCatID LIKE ?",array("%,$videoTutCatName,%"));
                $totalResults   = $connector->numResults($result2);

                //CHECK PARAGRAPH LENGTH
				$paragraph	= strip_tags($paragraph);
				if(strlen($paragraph) > 450){
					$paragraph	= substr($paragraph, 0, 450).'...';
				}

				//GENERATE OUPUT
				$txt.= '<div class="module-manage-content-holder">';

					//IF AN IMAGE IS AVAILABLE
					if($videoTutCatImage != ''){
						$txt.= '<div class="paragraph-image-category">
							<img src="'.$web_root.'cms-images/large/'.$videoTutCatImage.'" alt="'.$videoTutCatImageTitle.'" title="'.$videoTutCatImageTitle.'" border="0"/>
						</div>';
					}

					//IF A TITLE IS AVAILABLE
					if($videoTutCatName != ''){
                		$txt.= '<div class="paragraph-title"><b>'.$videoTutCatName.'</b></div>';
					}

                    $txt.= '<div class="paragraph-text">'.$paragraph.'</div>
                            <div class="clear"></div>';

					$txt.= '<div class="module-manage-content-links">';

                    //TELL IF CATEGORY CAN BE DELETED
                    if($totalResults == 0){
						$txt.='<form name="delete_tutorial_category'.$videoTutCatID.'">
                        <input type="hidden" name="delete_tutorial_category" value="1">
                        <input type="hidden" name="videoTutCatID" value="'.$videoTutCatID.'">
							<a href="javascript:deleteTutorialCategory('.$videoTutCatID.')" title="Remove Category">Remove Category</a>
						</form>';
                    }else{
                        $txt.='<a href="javascript:noDeleteCategory()" title="Remove Category">Remove Category</a>';
                    }

                    $txt.= '<a href="'.$cms_root.'video-tutorials-manager/edit-tutorial-category.php?videoTutCatID='.$videoTutCatID.'" title="Edit Category">Edit Category</a>
						<div class="clear"></div>
						</div>
                </div>';

			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Categories available. <a href="'.$cms_root.'video-tutorials-manager/add-tutorial-category.php" title="Add Tutorial Category">Please add a category here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // TUTORIAL PLAYLIST ARCHITECTURE
    //#################################################################
	function playlistArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE deletedBy = ? ORDER BY videoTutPlaylistTitle ASC", array('0'));
		$categoryTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($categoryTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$empty			        = '';
				$empty_bg		        = '';
				$videoTutPlaylistID		= $row['videoTutPlaylistID'];
				$videoTutPlaylistTitle	= $row['videoTutPlaylistTitle'];
                $videoTutPublished      = $row['videoTutPublished'];

				//GET ALL VIDEOS FOR A PLAYLIST
				$result2	= $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutPlaylistID = ? AND deletedBy = ?", array($videoTutPlaylistID, '0'));
				$videoTotal	= $connector->numResults($result2);

				//IF PLAYLIST IS EMPTY
				if($videoTotal == 0){
					$empty		= '<span class="empty-category-text">(Empty)</span>';
					$empty_bg	='class="empty-category"';
				}

                //CHECK IF PLAYLIST HAS ALREADY BEEN PUBLISHED
                if($videoTutPublished == 0){
                    $published		= '<span class="unpublished-post-text">(Not yet Published)</span>';
                }else{
                    $published		= '<span class="published-post-text">(Published)</span>';
                }

				//GENERATE OUPUT
				$txt.= '<tr>
					<td class="active-account"></td>
					<td '.$empty_bg.'>'.$videoTutPlaylistTitle.' '.$empty.' '.$published.'</td>
                    <td '.$empty_bg.' align="center">';

                    //IF PLAYLIST IS NOT YET PUBLISHED
					if($videoTutPublished == 0){

						$txt.='<form name="publish_playlist'.$videoTutPlaylistID.'">
							<input type="hidden" name="publish_playlist" value="1">
							<input type="hidden" name="videoTutPlaylistID" value="'.$videoTutPlaylistID.'">
							<a href="javascript:publishPlaylist('.$videoTutPlaylistID.')" title="Publish">Publish</a>
						</form>';
					}
					//IF PLAYLIST HAS BEEN PUBLISHED
					else{
                        $txt.='<form name="unpublish_playlist'.$videoTutPlaylistID.'">
							<input type="hidden" name="unpublish_playlist" value="1">
							<input type="hidden" name="videoTutPlaylistID" value="'.$videoTutPlaylistID.'">
							<a href="javascript:unpublishPlaylist('.$videoTutPlaylistID.')" title="Unpublish">Unpublish</a>
						</form>';
					}

					$txt.='</td>
                    <td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID='.$videoTutPlaylistID.'" title="Manage">Manage</a>
					</td>
					<td '.$empty_bg.' align="center">
						<a href="'.$cms_root.'video-tutorials-manager/edit-tutorial-playlist.php?videoTutPlaylistID='.$videoTutPlaylistID.'" title="Modify">Modify</a>
					</td>
					<td '.$empty_bg.' align="center">';

					//IF NO VIDEOS ARE IN THE PLAYLIST
					if($videoTotal == 0){

						$txt.='<form name="delete_tutorial_playlist'.$videoTutPlaylistID.'">
							<input type="hidden" name="delete_tutorial_playlist" value="1">
							<input type="hidden" name="videoTutPlaylistID" value="'.$videoTutPlaylistID.'">
							<a href="javascript:deleteTutorialPlaylist('.$videoTutPlaylistID.')" title="Remove">Remove</a>
						</form>';
					}
					//IF VIDEOS ARE IN THE PLAYLIST
					else{
						$txt.='<a href="javascript:noDeletePlaylist()" title="Remove">Remove</a>';
					}

					$txt.= '</td>
				  </tr>';

			}
		}
		//IF NO PLAYLISTS ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Playlists available. <a href="'.$cms_root.'video-tutorials-manager/add-tutorial-playlist.php" title="Add Playlist">Please add a playlist here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // TUTORIAL PLAYLIST ARCHITECTURE (REMOVED)
    //#################################################################
	function playlistArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE deletedBy != ? ORDER BY videoTutPlaylistTitle ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$videoTutPlaylistID		= $row['videoTutPlaylistID'];
			$videoTutPlaylistTitle	= $row['videoTutPlaylistTitle'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$videoTutPlaylistTitle.'</td>
				<td align="center">
				<form name="recover_tutorial_playlist'.$videoTutPlaylistID.'">
					<input type="hidden" name="recover_tutorial_playlist" value="1">
					<input type="hidden" name="videoTutPlaylistID" value="'.$videoTutPlaylistID.'">
					<a href="javascript:recoverTutorialPlaylist('.$videoTutPlaylistID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // VIDEO CONTENT ARCHITECTURE
    //#################################################################
	function videoContentArchitecture($cms_root, $web_root, $videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM video_tutorials_content WHERE deletedBy = ?  AND videoTutPlaylistID = ? ORDER BY sequence ASC", array('0', $videoTutPlaylistID));
		$videoTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($videoTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$videoTutContentID	= $row['videoTutContentID'];
                $videoTutPlaylistID = $row['videoTutPlaylistID'];
				$videoTitle		    = $row['videoTitle'];
				$videoLink			= $row['videoLink'];
                $sequence           = $row['sequence'];

    				$txt.= '<div class="module-manage-content-holder sortable-content" id="'.$videoTutContentID.'">';

                    $txt.= '<div class="paragraph-title"><b>'.$videoTitle.'</b></div>';

                    $txt.= '<div class="paragraph-links">Video: <a href="'.$videoLink.'" target="_blank">'.$videoLink.'</a></div>';

					$txt.= '<div class="module-manage-content-links">
						<form name="delete_video'.$videoTutContentID.'">
							<input type="hidden" name="delete_video" value="1">
							<input type="hidden" name="videoTutContentID" value="'.$videoTutContentID.'">
							<input type="hidden" name="videoTutPlaylistID" value="'.$videoTutPlaylistID.'">
							<a href="javascript:deleteVideo('.$videoTutContentID.')" title="Remove Paragraph">Remove Video</a>
						</form>
						<a href="'.$cms_root.'video-tutorials-manager/edit-videos.php?videoTutContentID='.$videoTutContentID.'&videoTutPlaylistID='.$videoTutPlaylistID.'" title="Edit Video">Edit Video</a>
						<div class="clear"></div>
						</div>
                </div>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Videos available. <a href="'.$cms_root.'video-tutorials-manager/add-vidoes.php?videoTutPlaylistID='.$videoTutPlaylistID.'" title="Add Video(s)">Please add a video(s) here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CHECK IF ANY TUTORIAL PLAYLISTS HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedPlaylists(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF TUTORIAL CATEGORY INFO HAS BEEN CHANGED
    //#################################################################
	function checkCategoryChanges($title, $paragraph, $image_title, $videoTutCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatName = ? AND videoTutCatImageTitle = ? AND videoTutCatID = ? AND videoTutCatDescription = ?", array($title, $image_title, $videoTutCatID, $paragraph));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // CHECK IF TUTORIAL PLAYLIST INFO HAS BEEN CHANGED
    //#################################################################
	function checkPlaylistChanges($title, $paragraph, $categories, $affiliates, $softwares, $website_name, $website_link, $image_title, $videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistTitle = ? AND videoTutCatID = ? AND videoTutPlaylistIntro = ? AND videoTutPlaylistImageTitle = ? AND requiredSoftware = ? AND ownerName = ? AND ownerSource = ? AND videoTutPlaylistID = ? AND affiliateIDs = ?", array($title, $categories, $paragraph, $image_title, $softwares, $website_name, $website_link, $videoTutPlaylistID, $affiliates));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // CHECK IF VIDEO INFO HAS BEEN CHANGED
    //#################################################################
	function checkVideoChanges($video_title, $video, $videoTutContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM video_tutorials_content WHERE videoTitle = ? AND videoLink = ? AND videoTutContentID = ?", array($video_title, $video, $videoTutContentID));
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
		$insert = $connector->query("INSERT INTO video_tutorials_category (videoTutCatName, videoTutCatDescription, videoTutCatImageTitle, videoTutCatImage, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($title, $paragraph, $image_title, $imageFile, $currentUser, $currentDate));

        //RETURN CATEGORY ID
        $result = $connector->query("SELECT * FROM video_tutorials_category ORDER BY videoTutCatID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['videoTutCatID'];

	}

	//#################################################################
	//OVERWRITE CATEGORY
	//#################################################################
	function overwriteCategory($category_title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$category_title	= strip_tags($category_title);

		//UPDATE USER
		$update = $connector->query("UPDATE video_tutorials_category SET
									videoTutCatName = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutCatName = ?",
									array($category_title, '0', '0000-00-00 00:00:00', $category_title));

	}

    //#################################################################
	//OVERWRITE PLAYLIST
	//#################################################################
	function overwritePlaylist($playlist_name, $paragraph, $categories, $softwares, $affiliates){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$playlist_name	= strip_tags($playlist_name);
        $categories	    = strip_tags($categories);
        $softwares	    = strip_tags($softwares);
        $affiliates	    = strip_tags($affiliates);

		//UPDATE USER
		$update = $connector->query("UPDATE video_tutorials_playlist SET
                                    videoTutPlaylistIntro = ?,
                                    requiredSoftware = ?,
                                    affiliateIDs = ?,
                                    videoTutCatID = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutPlaylistTitle = ?",
									array($paragraph, $softwares, $affiliates, $categories, '0', '0000-00-00 00:00:00', $playlist_name));

        //GET PLAYLIST ID
        $result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistTitle = ?", array($playlist_name));
        $row    = $connector->fetchArray($result);

        //RETURN ID
        return $row['videoTutPlaylistID'];

	}

	//#################################################################
    // UPDATE TUTORIAL CATEGORY
    //#################################################################
	function updateCategory($title, $paragraph, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $videoTutCatID){
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
        $result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ?", array($videoTutCatID));
        $row    = $connector->fetchArray($result);
        $image  = $row['videoTutCatImage'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE video_tutorials_category SET
									videoTutCatName = ?,
                                    videoTutCatDescription = ?,
                                    videoTutCatImageTitle = ?,
                                    videoTutCatImage = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE videoTutCatID = ?",
									array($title, $paragraph, $image_title, $imageFile, $modifiedBy, $modifiedDate, $modifiedNumber, $videoTutCatID));

	}

    //#################################################################
    // UPDATE TUTORIAL PLAYLIST
    //#################################################################
	function updatePlaylist($title, $paragraph, $categories, $softwares, $affiliates, $website_name, $website_link, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $videoTutPlaylistID, $tutorial_playlist_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //CHECK IF AFFILIATE IDS IS EMPTY
        if($affiliates == ',,'){
            $affiliates = '';
        }

		//STRIP TAGS
		$title                  = strip_tags($title);
        $image_title	        = strip_tags($image_title);
        $categories             = strip_tags($categories);
        $softwares              = strip_tags($softwares);
        $affiliates             = strip_tags($affiliates);
        $website_name           = strip_tags($website_name);
        $website_link           = strip_tags($website_link);
        $tutorial_playlist_url  = strip_tags($tutorial_playlist_url);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistID = ?", array($videoTutPlaylistID));
        $row    = $connector->fetchArray($result);
        $image  = $row['videoTutPlaylistImage'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE video_tutorials_playlist SET
									videoTutPlaylistTitle = ?,
                                    videoTutPlaylistIntro = ?,
                                    videoTutCatID = ?,
                                    videoTutPlaylistImageTitle = ?,
                                    videoTutPlaylistImage = ?,
                                    requiredSoftware = ?,
                                    ownerName = ?,
                                    ownerSource = ?,
                                    affiliateIDs = ?,
                                    url = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE videoTutPlaylistID = ?",
									array($title, $paragraph, $categories, $image_title, $imageFile, $softwares, $website_name, $website_link, $affiliates, $tutorial_playlist_url, $modifiedBy, $modifiedDate, $modifiedNumber, $videoTutPlaylistID));

	}

	//#################################################################
    // DELETE CATEGORY
    //#################################################################
	function deleteCategory($videoTutCatID){
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
        $result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatID = ?",array($videoTutCatID));
        $row    = $connector->fetchArray($result);
        $videoTutCatImage = $row['videoTutCatImage'];

        //REMOVE IMAGES
        unlink($largeDirectory.$videoTutCatImage);
        unlink($mediumDirectory.$videoTutCatImage);
        unlink($smallDirectory.$videoTutCatImage);

        //REMOVE USER
		$remove = $connector->query("DELETE FROM video_tutorials_category WHERE videoTutCatID = ?", array($videoTutCatID));

        //REMOVE META DETAILS
        $remove = $connector->query("DELETE FROM meta_details WHERE videoTutCatID = ?", array($videoTutCatID));

		//REMOVE USER
		/*$remove = $connector->query("UPDATE video_tutorials_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutCatID = ?",
									array($currentUser, $currentDate, $videoTutCatID));*/

	}

    //#################################################################
    // DELETE PLAYLIST
    //#################################################################
	function deletePlaylist($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE video_tutorials_playlist SET
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutPlaylistID = ?",
									array($currentUser, $currentDate, $videoTutPlaylistID));

	}

    //#################################################################
    // PUBLISH PLAYLIST
    //#################################################################
	function publishPlaylist($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE video_tutorials_playlist SET
									videoTutPublished = ?
									WHERE videoTutPlaylistID = ?",
									array(1, $videoTutPlaylistID));

	}

    //#################################################################
    // UNPUBLISH PLAYLIST
    //#################################################################
	function unpublishPlaylist($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE video_tutorials_playlist SET
									videoTutPublished = ?
									WHERE videoTutPlaylistID = ?",
									array(0, $videoTutPlaylistID));

	}

    //#################################################################
    // ADD PLAYLIST
    //#################################################################
	function addPlaylist($title, $paragraph, $categories, $softwares, $affiliates, $image_title, $imageFile, $website_name, $website_link, $tutorial_playlist_url){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title                    = strip_tags($title);
        $categories               = strip_tags($categories);
        $softwares                = strip_tags($softwares);
        $affiliates               = strip_tags($affiliates);
        $image_title	          = strip_tags($image_title);
        $website_name             = strip_tags($website_name);
        $website_link	          = strip_tags($website_link);
        $tutorial_playlist_url    = strip_tags($tutorial_playlist_url);

        //SET PUBLISH DATE
        $publishDate = date("Y-m-d H:i:s", strtotime($date.' '.str_replace(' ', '',$time)));

		//ADD USER
		$insert = $connector->query("INSERT INTO video_tutorials_playlist (videoTutCatID, videoTutPlaylistTitle, videoTutPlaylistIntro, videoTutPlaylistImageTitle, videoTutPlaylistImage, requiredSoftware, affiliateIDs, url, ownerName, ownerSource, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($categories, $title, $paragraph, $image_title, $imageFile, $softwares, $affiliates, $tutorial_playlist_url, $website_name, $website_link, $currentUser, $currentDate));

        //RETURN PLAYLIST ID
        $result = $connector->query("SELECT * FROM video_tutorials_playlist ORDER BY videoTutPlaylistID DESC",array());
        $lastID = $connector->fetchArray($result);

        return  $lastID['videoTutPlaylistID'];

	}

    //#################################################################
    // DELETE VIDEO
    //#################################################################
	function deleteVideo($videoTutContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE VIDEO
		$remove = $connector->query("DELETE FROM video_tutorials_content WHERE videoTutContentID = ?",array($videoTutContentID));

	}

	//#################################################################
    // RECOVER CATEGORY
    //#################################################################
	function recoverCategory($videoTutCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE video_tutorials_category SET
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutCatID = ?",
									array('0', '0000-00-00 00:00:00', $videoTutCatID));

	}

    //#################################################################
    // RECOVER PLAYLIST
    //#################################################################
	function recoverPlaylist($videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE video_tutorials_playlist SET
									deletedBy = ?,
									deletedDate = ?
									WHERE videoTutPlaylistID = ?",
									array('0', '0000-00-00 00:00:00', $videoTutPlaylistID));

	}

	//#################################################################
    // CHECK IF CATEGORY NAME IS ALREADY IN USE
    //#################################################################
	function addCategoryCheck($title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatName = ?", array($title));
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
				return 'removed_tutorial_category';
			}
		}

	}

    //#################################################################
    // CHECK IF PLAYLIST NAME IS ALREADY IN USE
    //#################################################################
	function addPlaylistCheck($title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM video_tutorials_playlist WHERE videoTutPlaylistTitle = ?", array($title));
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
				return 'removed_tutorial_playlist';
			}
		}

	}

    //#################################################################
    // CHECK IF VIDEO TITLE IS ALREADY IN USE
    //#################################################################
	function editVideoCheck($video_title, $videoTutContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK VIDEO
		$result = $connector->query("SELECT * FROM video_tutorials_content WHERE videoTitle = ? AND videoTutContentID = ?", array($video_title, $videoTutContentID));
		$total	= $connector->numResults($result);

		//IF BLOG POST HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

    //#################################################################
    // ADD VIDEOS INTO DATABSE
    //#################################################################
	function addVideos($title, $link, $videoTutPlaylistID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$link			= strip_tags($link);

		//GET SEQUENCE
		$result	= $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutPlaylistID = ? AND deletedBy = ? ORDER BY sequence DESC", array($videoTutPlaylistID, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO video_tutorials_content (videoTutPlaylistID, videoTitle, videoLink, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($videoTutPlaylistID, $title, $link, $currentUser, $currentDate, $sequence));

        //GET LAST INSERTED ID
        $result2    = $connector->query("SELECT * FROM video_tutorials_content ORDER BY videoTutContentID DESC LIMIT 0,1", array());
        $row2       = $connector->fetchArray($result2);
        $videoTutContentID = $row2['videoTutContentID'];

        //RETURN LAST INSERTED ID
        return $videoTutContentID;

	}

    //#################################################################
    // UPDATE VIDEO
    //#################################################################
	function updateVideo($video_title, $video, $videoTutContentID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$video_title	= strip_tags($video_title);
		$video			= strip_tags($video);

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM video_tutorials_content WHERE videoTutContentID = ?", array($videoTutContentID));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE video_tutorials_content SET
                                            videoTitle      = ?,
                                            videoLink       = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE videoTutContentID = ?",
                                            array($video_title, $video, $currentUser, $modifiedNumber, $currentDate, $videoTutContentID));

	}

}

//DEFINE CLASS
$videoTutorialManager = new videoTutorialManager();

//#################################################################
// ADD VIDEO TUTORIAL CATEGORY
//#################################################################
if(isset($_POST['add_tutorial_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['category-title'];
    $paragraph      = $_POST['paragraph'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$tutorial_type	= $_POST['tutorial-type'];
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
	$v->validateString($title, 'Video Tutorial Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($tutorial_type == '' && $image_type == ''){

            //CHECK IF CATEGORY NAME IS ALREADY IN USE
			$category_used = $videoTutorialManager->addCategoryCheck($title);
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
    			$videoTutCatID = $videoTutorialManager->addCategory($title, $paragraph, $image_title, $imageFile);

                //GET META DETAILS
    			$keywords		= $videoTutorialManager->getMetaKeywordCategory($videoTutCatID);
                $description	= $videoTutorialManager->getMetaDescriptionCategory($videoTutCatID);

    			//UPDATE META DETAILS
    			$videoTutorialManager->updateMetaDetailsCategory($keywords, $description, $videoTutCatID);

                //REDIRECT USER
    			header("Location: ".$cms_root."video-tutorials-manager/crop-image.php?videoTutCatID=".$videoTutCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
                exit;

			}
			//IF USER HAS BEEN REMOVED
			/*elseif($category_used == 'removed_tutorial_category'){
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
// REACTIVATE CATEGORY
//#################################################################
if(isset($_POST['reactivate-category-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$category_title		= $_POST['category-title'];

	//HONEY POTS
	$tutorial_type		= $_POST['tutorial-type'];

	if($tutorial_type == ''){

		//OVERWRITE USER
		$videoTutorialManager->overwriteCategory($category_title);

		//REDIRECT PAGE
		header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-category.php?message=8");
        exit;
	}
}

//#################################################################
// EDIT CATEGORY
//#################################################################
if(isset($_POST['edit_tutorial_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title          = $_POST['category-title'];
    $paragraph      = $_POST['paragraph'];
	$videoTutCatID	= $_POST['videoTutCatID'];
    $oldImage       = $_POST['oldImage'];
    $image_title    = $_POST['image-title'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$tutorial_type	= $_POST['tutorial-type'];
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
	$v->validateString($title, 'Video Tutorial Category Title', 1, 200);
    $v->validateText($paragraph, 'Description', 10);
	$v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($tutorial_type == '' && $image_type == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($videoTutorialManager->checkCategoryChanges($title, $paragraph, $image_title, $videoTutCatID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

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
				$videoTutorialManager->updateCategory($title, $paragraph, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $videoTutCatID);

                //GET META DETAILS
    			$keywords		= $videoTutorialManager->getMetaKeywordCategory($videoTutCatID);
    			$description	= $videoTutorialManager->getMetaDescriptionCategory($videoTutCatID);

    			//UPDATE META DETAILS
    			$videoTutorialManager->updateMetaDetailsCategory($keywords, $description, $videoTutCatID);

                //IF A NEW IMAGE HAS BEEN UPLOADED
                if($_FILES[$inputField]["tmp_name"] != ""){
                //REDIRECT USER
    			    header("Location: ".$cms_root."video-tutorials-manager/crop-image.php?videoTutCatID=".$videoTutCatID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=3");
                    exit;
                }else{
                    header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-category.php?message=3");
                    exit;
                }

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-category.php");
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
if(isset($_POST['delete_tutorial_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $videoTutCatID	= $_POST['videoTutCatID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->deleteCategory($videoTutCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-category.php?message=5");
    exit;
}

//#################################################################
//RECOVER CATEGORY
//#################################################################
if(isset($_POST['recover_tutorial_category'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $videoTutCatID	= $_POST['videoTutCatID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->recoverCategory($videoTutCatID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-category.php?message=7");
    exit;
}

//#################################################################
// ADD VIDEO TUTORIAL PLAYLIST
//#################################################################
if(isset($_POST['add_tutorial_playlist'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['playlist-name'];
    $paragraph      = $_POST['paragraph'];
    $categories     = $_POST['categories'];
    $softwares      = $_POST['required-softwares'];
    $affiliates     = $_POST['affiliates'];
    $website_name   = $_POST['owner-website-name'];
    $website_link   = $_POST['owner-website-link'];
	$image_title	= $_POST['image-title'];

	//HONEY POTS
	$date           = $_POST['tutorial-playlist-date'];
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
    $website_name   = $userLogin->specialCharactersToHTMLEntity($website_name);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Video Tutorial Playlist Name', 1, 200);
    //$v->validateText($paragraph, 'Intro', 10);
    $v->validateTags($categories, 'Tutorial Categories');

    //IF A SOFTWARE HAS BEEN ADDED
    if($softwares != ''){
        $v->validateTags($softwares, 'Required Softwares');
    }

    //IF AN AFFILIATE HAS BEEN ADDED
    if($affiliates != ''){
        $v->validateTags($affiliates, 'Tutorial Affiliate Links:');
    }

    //IF WEBSITE OWNER DETAILS HAVE BEEN added
    if($website_name != '' || $website_link != ''){
        $v->validateString($website_name, 'Website Name', 1, 200);
        $v->validateLink($website_link, 'Website Link');
    }

    $v->validateString($image_title, 'Image Title',3, 150);
	$v->validateImage($inputField, 'Image File');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($date == '' && $image_type == ''){

            //GET ALL VIDEO TUTORIAL CATEGORY ID'S
            $videoTutCatIDs         = ',';
            $categories             = substr($categories, 1, -1);
            $videoTutCatNameArray   = explode(',', $categories);
            foreach($videoTutCatNameArray as $videoTutCatName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatName = ? AND deletedBy = ?", array($videoTutCatName, '0'));
                $row    = $connector->fetchArray($result);
                $videoTutCatID  = $row['videoTutCatID'];

                //INSERT INTO STRING
                $videoTutCatIDs.= $videoTutCatID.',';
            }

            //GET ALL VIDEO TUTORIAL SOFTWARE ID'S
            $requiredSoftwares      = ',';
            $softwares              = substr($softwares, 1, -1);
            $softwareNameArray      = explode(',', $softwares);
            foreach($softwareNameArray as $softwareName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM softwares WHERE softwareName = ? AND deletedBy = ?", array($softwareName, '0'));
                $row    = $connector->fetchArray($result);
                $softwareID  = $row['softwareID'];

                //INSERT INTO STRING
                $requiredSoftwares.= $softwareID.',';
            }

            //GET ALL VIDEO TUTORIAL AFFILIATE ID'S
            $affiliatesIDs          = ',';
            $affiliates             = substr($affiliates, 1, -1);
            $affiliateNameArray     = explode(',', $affiliates);
            foreach($affiliateNameArray as $affiliateName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM affiliate WHERE affTitle = ? AND deletedBy = ?", array($affiliateName, '0'));
                $row    = $connector->fetchArray($result);
                $affiliateID  = $row['affiliateID'];

                //INSERT INTO STRING
                $affiliatesIDs.= $affiliateID.',';
            }

            //CHECK IF PLAYLIST NAME IS ALREADY IN USE
			$playlist_used = $videoTutorialManager->addPlaylistCheck($title);
            if($playlist_used == 'unused'){

                //IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				$imageFile	= $fileUploader->uploadImages($inputField, $originalDirectory, $largeDirectory, $mediumDirectory, $smallDirectory, $previewSize, $image_title);

    				//GET THE IMAGE SIZE
    				list($width, $height, $type, $attr) = getimagesize($largeDirectory . $imageFile);
    			}

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

                //CREATE VIDEO TUTORIAL PLAYLIST URL
                $tutorial_playlist_url = str_replace("'", "", $title);
                $tutorial_playlist_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($tutorial_playlist_url));
                $tutorial_playlist_url = str_replace(' ', '-', $tutorial_playlist_url);

                //CHECK IF VIDEO URL EXISTS
                $tutorial_playlist_url = $videoTutorialManager->checkVideoURLExists($tutorial_playlist_url, '');

    			//INSERT BLOG POST INTO DATABASE
    			$videoTutPlaylistID = $videoTutorialManager->addPlaylist($title, $paragraph, $videoTutCatIDs, $requiredSoftwares, $affiliatesIDs, $image_title, $imageFile, $website_name, $website_link, $tutorial_playlist_url);

                //GET META DETAILS
    			$keywords		= $videoTutorialManager->getMetaKeyword($videoTutPlaylistID);
                $description	= $videoTutorialManager->getMetaDescription($videoTutPlaylistID);

    			//UPDATE META DETAILS
    			$videoTutorialManager->updateMetaDetails($keywords, $description, $videoTutPlaylistID);

                //REDIRECT USER
    			header("Location: ".$cms_root."video-tutorials-manager/crop-image-playlist.php?videoTutPlaylistID=".$videoTutPlaylistID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=2");
                exit;

			}
            //IF PLAYLIST HAS BEEN REMOVED
            elseif($playlist_used == 'removed_tutorial_playlist'){
				//SET USER AS REMOVED
				$removed_tutorial_playlist = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Playlist Name</b> you supplied is already in use. Please try another!</li></ul>';
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
// EDIT VIDEO TUTORIAL PLAYLIST
//#################################################################
if(isset($_POST['edit_tutorial_playlist'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $videoTutPlaylistID = $_POST['videoTutPlaylistID'];
	$title              = $_POST['playlist-name'];
    $paragraph          = $_POST['paragraph'];
    $categories         = $_POST['categories'];
    $affiliates         = $_POST['affiliates'];
    $softwares          = $_POST['required-softwares'];
    $website_name       = $_POST['owner-website-name'];
    $website_link       = $_POST['owner-website-link'];
    $oldImage           = $_POST['oldImage'];
    $image_title        = $_POST['image-title'];

	$modifiedDate	    = $_POST['modifiedDate'];
	$modifiedBy		    = $_SESSION['cmsUser'];
	$modifiedNumber	    = $_POST['modifiedNumber'];

	//HONEY POTS
	$tutorial_playlist_date	= $_POST['tutorial-playlist-date'];
    $image_type             = $_POST['image-type'];

    //IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title          = $userLogin->specialCharactersToHTMLEntity($title);
    $website_name   = $userLogin->specialCharactersToHTMLEntity($website_name);
    $image_title    = $userLogin->specialCharactersToHTMLEntity($image_title);

	//VALIDATION
    $v = new formValidation();
	$v->validateString($title, 'Video Tutorial Playlist Name', 1, 200);
    //$v->validateText($paragraph, 'Intro', 10);
    $v->validateTags($categories, 'Tutorial Categories');

    //IF A SOFTWARE HAS BEEN ADDED
    if($softwares != ''){
        $v->validateTags($softwares, 'Required Softwares');
    }

    //IF AN AFFILIATE HAS BEEN ADDED
    if($affiliates != ''){
        $v->validateTags($affiliates, 'Tutorial Affiliate Links:');
    }

    //IF WEBSITE OWNER DETAILS HAVE BEEN added
    if($website_name != '' || $website_link != ''){
        $v->validateString($website_name, 'Website Name', 1, 200);
        $v->validateLink($website_link, 'Website Link');
    }

    $v->validateString($image_title, 'Image Title',3, 150);

    if($_FILES[$inputField]["tmp_name"] != ''){
        $v->validateImage($inputField, 'Image File');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($tutorial_playlist_date == '' && $image_type == ''){

            //GET ALL VIDEO TUTORIAL CATEGORY ID'S
            $videoTutCatIDs         = ',';
            $categories             = substr($categories, 1, -1);
            $videoTutCatNameArray   = explode(',', $categories);
            foreach($videoTutCatNameArray as $videoTutCatName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM video_tutorials_category WHERE videoTutCatName = ? AND deletedBy = ?", array($videoTutCatName, '0'));
                $row    = $connector->fetchArray($result);
                $videoTutCatID  = $row['videoTutCatID'];

                //INSERT INTO STRING
                $videoTutCatIDs.= $videoTutCatID.',';
            }

            //GET ALL VIDEO TUTORIAL SOFTWARE ID'S
            $requiredSoftwares      = ',';
            $softwares              = substr($softwares, 1, -1);
            $softwareNameArray      = explode(',', $softwares);
            foreach($softwareNameArray as $softwareName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM softwares WHERE softwareName = ? AND deletedBy = ?", array($softwareName, '0'));
                $row    = $connector->fetchArray($result);
                $softwareID  = $row['softwareID'];

                //INSERT INTO STRING
                $requiredSoftwares.= $softwareID.',';
            }

            //GET ALL VIDEO TUTORIAL AFFILIATE ID'S
            $affiliatesIDs          = ',';
            $affiliates             = substr($affiliates, 1, -1);
            $affiliateNameArray     = explode(',', $affiliates);
            foreach($affiliateNameArray as $affiliateName){
                //GET ID FOR CATEGORY
                $result = $connector->query("SELECT * FROM affiliate WHERE affTitle = ? AND deletedBy = ?", array($affiliateName, '0'));
                $row    = $connector->fetchArray($result);
                $affiliateID  = $row['affiliateID'];

                //INSERT INTO STRING
                $affiliatesIDs.= $affiliateID.',';
            }

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($videoTutorialManager->checkPlaylistChanges($title, $paragraph, $videoTutCatIDs, $affiliatesIDs, $requiredSoftwares, $website_name, $website_link, $image_title, $videoTutPlaylistID) == 'changed' || $_FILES[$inputField]["tmp_name"] != ''){

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

                //CREATE VIDEO TUTORIAL PLAYLIST URL
                $tutorial_playlist_url = str_replace("'", "", $title);
                $tutorial_playlist_url = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($tutorial_playlist_url));
                $tutorial_playlist_url = str_replace(' ', '-', $tutorial_playlist_url);

                //CHECK IF VIDEO URL EXISTS
                $tutorial_playlist_url = $videoTutorialManager->checkVideoURLExists($tutorial_playlist_url, $videoTutPlaylistID);

				//UPDATE USER IN DATABASE
				$videoTutorialManager->updatePlaylist($title, $paragraph, $videoTutCatIDs, $requiredSoftwares, $affiliatesIDs, $website_name, $website_link, $imageFile, $image_title, $modifiedBy, $modifiedDate, $modifiedNumber, $videoTutPlaylistID, $tutorial_playlist_url);

                //GET META DETAILS
    			$keywords		= $videoTutorialManager->getMetaKeyword($videoTutPlaylistID);
                $description	= $videoTutorialManager->getMetaDescription($videoTutPlaylistID);

    			//UPDATE META DETAILS
    			$videoTutorialManager->updateMetaDetails($keywords, $description, $videoTutPlaylistID);

                //IF A NEW IMAGE HAS BEEN UPLOADED
                if($_FILES[$inputField]["tmp_name"] != ""){
                    //REDIRECT USER
                    header("Location: ".$cms_root."video-tutorials-manager/crop-image-playlist.php?videoTutPlaylistID=".$videoTutPlaylistID."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=4");
                    exit;

                }else{
                    header("Location: ".$cms_root."video-tutorials-manager/index.php?message=4");
                    exit;
                }

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."video-tutorials-manager/");
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
//DELETE PLAYLIST
//#################################################################
if(isset($_POST['delete_tutorial_playlist'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $videoTutPlaylistID	= $_POST['videoTutPlaylistID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->deletePlaylist($videoTutPlaylistID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/index.php?message=6");
    exit;
}

//#################################################################
//RECOVER PLAYLIST
//#################################################################
if(isset($_POST['recover_tutorial_playlist'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $videoTutPlaylistID	= $_POST['videoTutPlaylistID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->recoverPlaylist($videoTutPlaylistID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/index.php?message=9");
    exit;
}

//#################################################################
// REACTIVATE PLAYLIST
//#################################################################
if(isset($_POST['reactivate-playlist-status'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$playlist_name		= $_POST['playlist-name'];
    $paragraph		    = $_POST['paragraph'];
    $categories		    = $_POST['categories'];
    $softwares		    = $_POST['required-softwares'];
    $affiliates         = $_POST['affiliates'];

	//HONEY POTS
	$date		    = $_POST['date'];
    $image_type		= $_POST['image-type'];

	if($image_type == '' && $date == ''){

		//OVERWRITE USER
		$videoTutPlaylistID = $videoTutorialManager->overwritePlaylist($playlist_name, $paragraph, $categories, $softwares, $affiliates);

        //GET META DETAILS
        $keywords		= $videoTutorialManager->getMetaKeyword($videoTutPlaylistID);
        $description	= $videoTutorialManager->getMetaDescription($videoTutPlaylistID);

        //UPDATE META DETAILS
        $videoTutorialManager->updateMetaDetails($keywords, $description, $videoTutPlaylistID);

		//REDIRECT PAGE
		header("Location: ".$cms_root."video-tutorials-manager/index.php?message=10");
        exit;
	}
}

//#################################################################
//PUBLISH PLAYLIST
//#################################################################
if(isset($_POST['publish_playlist'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $videoTutPlaylistID	= $_POST['videoTutPlaylistID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->publishPlaylist($videoTutPlaylistID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/index.php?message=14");
    exit;
}

//#################################################################
//UNPUBLISH PLAYLIST
//#################################################################
if(isset($_POST['unpublish_playlist'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $videoTutPlaylistID	= $_POST['videoTutPlaylistID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->unpublishPlaylist($videoTutPlaylistID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/index.php?message=15");
    exit;
}

//#################################################################
// ADD VIDEOS
//#################################################################
if(isset($_POST['add_videos'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$videoTutPlaylistID		= $_POST['videoTutPlaylistID'];
	$videos		            = $_POST['videos'];

	//HONEY POTS
	$video_type	= $_POST['video-type'];

	//VALIDATION
	$v = new formValidation();
    $v->validateText($videos, 'Video(s)', 10);

    //CHECK IF ERROR HAVE BEEM FOUND
    if(!$v->hasErrors()){
        //TURN STRING INTO ARRAY
        $videosArray = explode("\r\n", str_replace(" ", "", $videos));

        //VALIDATE ALL VIDEOS
        foreach($videosArray as $video){
            $v->validateMultipleVideos($video, 'Videos');

            //TURN STRING INTO ARRAY
            $videoInfo = explode(",", $video);

            $v->validateString($videoInfo[0], 'Video Title', 5, 200);

            $v->validateVideoLink($videoInfo[1], 'Video Link');
        }
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($video_type == ''){

            //TURN STRING INTO ARRAY
            $videosArray = explode("\r\n", $videos);

            //INSERT VIDEOS
            foreach($videosArray as $video){

                //TURN STRING INTO ARRAY
                $videoInfo = explode(",", $video);

                //CONVERT SPECIAL CHARACTERS TO HMTL ENTITIES
                $videoTitle = $userLogin->specialCharactersToHTMLEntity($videoInfo[0]);

                //INSERT VIDEO INFO INTO DATABASE
                $videoTutContentID  = $videoTutorialManager->addVideos($videoTitle, $videoInfo[1], $videoTutPlaylistID);

                //ADD INFORMATION INTO SEARCH INDEX
                $videoTutorialManager->addVideoSearchIndex($videoTutContentID, $videoTitle);
            }

            //REDIRECT USER
			header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID=".$videoTutPlaylistID."&message=11");
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
// EDIT VIDEO
//#################################################################
if(isset($_POST['edit_video'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$videoTutContentID     = $_POST['videoTutContentID'];
	$videoTutPlaylistID    = $_POST['videoTutPlaylistID'];
	$video_title           = $_POST['video-title'];
	$video                 = $_POST['youtube-vimeo-video'];

    //CONVERT SPECIAL CHARACTERS TO HMTL ENTITIES
    $video_title = $userLogin->specialCharactersToHTMLEntity($video_title);

	//HONEY POTS
	$video_type    = $_POST['video-type'];

	//VALIDATION
	$v = new formValidation();
	$v->validateString($video_title, 'Video Title', 1, 200);
	$v->validateVideoLink($video, 'YouTube/Vimeo Video');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($video_type == ''){

            //CHECK IF VIDEO CONTENT HAS BEEN CHANGED
            if($videoTutorialManager->checkVideoChanges($video_title, $video, $videoTutContentID) == 'changed'){

                //CHECK IF TITLE HAS ALREADY BEEN USED
                $video_title_used = $videoTutorialManager->editVideoCheck($video_title, $videoTutContentID);
                if($video_title_used == 'unused'){
        			//UPDATE VIDEO IN DATABASE
        			$videoTutorialManager->updateVideo($video_title, $video, $videoTutContentID);

                    //ADD INFORMATION INTO SEARCH INDEX
                    $videoTutorialManager->addVideoSearchIndex($videoTutContentID, $video_title);

        			header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID=".$videoTutPlaylistID."&message=12");
                    exit;
                }else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Video Title</b> you supplied is already in use. Please try another!</li></ul>';
				}
            }
            else{
    			header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID=".$videoTutPlaylistID);
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
//DELETE VIDEO
//#################################################################
if(isset($_POST['delete_video'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$videoTutContentID	= $_POST['videoTutContentID'];
	$videoTutPlaylistID	= $_POST['videoTutPlaylistID'];

    //SET USER AS REMOVED IN DATABASE
    $videoTutorialManager->deleteVideo($videoTutContentID);

    //REMOVE VIDEO SEARCH INDEX
    $videoTutorialManager->removeVideoSearchIndex($videoTutContentID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-playlist-content.php?videoTutPlaylistID=".$videoTutPlaylistID."&message=13");
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
}elseif($playlistRatio == 1){
    $newWidth		= 350;
    $newHeight		= 190;

    //CALCULATE NEW RATIO
    $ratio			= $newWidth / $newHeight;
}

//CROP IMAGE FOR CATEGORY WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$videoTutCatID		= $_POST['videoTutCatID'];
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
	header("Location: ".$cms_root."video-tutorials-manager/manage-tutorial-category.php?message=".$message);
    exit;
}

//CROP IMAGE FOR PLAYLIST WHEN FINISHED SELECTING AREA
if(isset($_POST['crop_playlist'])){

	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
	$videoTutPlaylistID	= $_POST['videoTutPlaylistID'];
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
	header("Location: ".$cms_root."video-tutorials-manager/index.php?message=".$message);
    exit;
}
###################################################################
?>
