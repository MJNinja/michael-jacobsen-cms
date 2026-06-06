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

class employmentHistoryManager extends systemConfig{
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
            case 1: $displayMessage = 'The Personal Details section has successfully been updated.'; break;
			case 2: $displayMessage = 'The Additional Information section has successfully been updated.'; break;
            case 3: $displayMessage = 'A new Institution of Study has successfully been added.'; break;
			case 4: $displayMessage = 'The selected Institution of Study has successfully been updated.'; break;
            case 5: $displayMessage = 'The selected Institution of Study has successfully been removed.'; break;
			case 6: $displayMessage = 'A new Work Place has successfully been added.'; break;
			case 7: $displayMessage = 'The selected Work Place has successfully been updated.'; break;
			case 8: $displayMessage = 'The selected Work Place has successfully been removed.'; break;
            case 9: $displayMessage = 'A new Skill has successfully been added.'; break;
			case 10: $displayMessage = 'The selected Skill has successfully been updated.'; break;
			case 11: $displayMessage = 'The selected Skill has successfully been removed.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET PERSONAL INFO
		$result = $connector->query("SELECT * FROM personal_about WHERE about_id = ? AND deletedBy = ?", array($about_id, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['description']).' '.strip_tags($row['occupation']).' '.strip_tags($row['tag_line']);
		}

        //GET EDUCATION INFO
		$result2 = $connector->query("SELECT * FROM personal_education WHERE about_id = ? AND deletedBy = ?", array($about_id, 0));
		while($row2	= $connector->fetchArray($result2)){
			$txt.=	strip_tags($row2['description']).' '.strip_tags($row2['education_place']).' '.strip_tags($row2['location']);
		}

        //GET SKILL INFO
		$result3 = $connector->query("SELECT * FROM personal_skills WHERE about_id = ? AND deletedBy = ?", array($about_id, 0));
		while($row3	= $connector->fetchArray($result3)){
			$txt.=	strip_tags($row3['description']).' '.strip_tags($row3['skill_name']);
		}

        //GET WORK INFO
		$result4 = $connector->query("SELECT * FROM personal_work WHERE about_id = ? AND deletedBy = ?", array($about_id, 0));
		while($row4	= $connector->fetchArray($result4)){
			$txt.=	strip_tags($row4['description']).' '.strip_tags($row4['work_place']);
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
	function getMetaDescription($about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM personal_about WHERE about_id = ? AND deletedBy = ?", array($about_id, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['description']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE about_id = ?", array($about_id));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (about_id, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($about_id, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE about_id = ?",
												array($keywords, $description, $about_id));
		}
	}

    //#################################################################
    // GET COMPLETED SECTIONS
    //#################################################################
	function getCompletedSections(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET PERSONAL INFO
		$result = $connector->query("SELECT * FROM personal_about WHERE name != '' AND surname != '' AND tag_line != '' AND occupation != '' AND description != '' AND personal_image != '' LIMIT 0,1", array());
		$totalPersonal	= $connector->numResults($result);

        //GET EDUCATION INFO
        $result2 = $connector->query("SELECT * FROM personal_education WHERE start_date != '' AND end_date != '' AND education_place != '' AND location != '' AND description != '' LIMIT 0,1", array());
		$totalEducation	= $connector->numResults($result2);

        //GET WORK INFO
        $result3 = $connector->query("SELECT * FROM personal_work WHERE start_date != '' AND end_date != '' AND work_place != '' AND location != '' AND description != '' LIMIT 0,1", array());
		$totalWork	= $connector->numResults($result3);

        //GET SKILL INFO
        $result4 = $connector->query("SELECT * FROM personal_skills WHERE skill_name != '' AND percentage != '' AND description != '' LIMIT 0,1", array());
		$totalSkill	= $connector->numResults($result4);

        //ADD TOTALS TOGETHER
        $total = $totalPersonal + $totalAdditional + $totalEducation + $totalWork + $totalSkill;

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET COMPLETED SECTIONS
    //#################################################################
	function getEmptySections(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET PERSONAL INFO
		$result = $connector->query("SELECT * FROM personal_about WHERE name = '' AND surname = '' AND tag_line = '' AND occupation = '' AND description = '' AND personal_image = '' LIMIT 0,1", array());
		$totalPersonal	= $connector->numResults($result);

        //GET EDUCATION INFO
        $result2 = $connector->query("SELECT * FROM personal_education WHERE start_date = '' AND end_date = '' AND education_place = '' AND location = '' AND description = '' LIMIT 0,1", array());
		$totalEducation	= $connector->numResults($result2);

        //GET WORK INFO
        $result3 = $connector->query("SELECT * FROM personal_work WHERE start_date = '' AND end_date = '' AND work_place = '' AND location = '' AND description = '' LIMIT 0,1", array());
		$totalWork	= $connector->numResults($result3);

        //GET SKILL INFO
        $result4 = $connector->query("SELECT * FROM personal_skills WHERE skill_name = '' AND percentage = '' AND description = '' LIMIT 0,1", array());
		$totalSkill	= $connector->numResults($result4);

        //ADD TOTALS TOGETHER
        $total = $totalPersonal + $totalAdditional + $totalEducation + $totalWork + $totalSkill;

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET INSTITUTION INFORMATION
    //#################################################################
	function getInstitutionInfo($education_id, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_education WHERE education_id = ?", array($education_id));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET WORK PLACE INFORMATION
    //#################################################################
	function getWorkPlaceInfo($work_id, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_work WHERE work_id = ?", array($work_id));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET SKILL INFORMATION
    //#################################################################
	function getSkillInfo($skill_id, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_skills WHERE skill_id = ?", array($skill_id));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET ABOUT ID
    //#################################################################
	function getAboutID(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_about", array());
        $totalResults = $connector->NumResults($result);

        //CHECK IF ABOUT ID IS AVAILABLE
        if($totalResults != 0){
	          $row	= $connector->fetchArray($result);

              //RETURN VAlUE
      	      return $row['about_id'];
        }else{
            //INSERT ABOUT ID
            $insert = $connector->query("INSERT INTO personal_about (createdBy, createdDate)
    									VALUES (?, ?)",
    									array($currentUser, $currentDate));

            //GET NEWLY ADDED ABOUT ID
            $result2 = $connector->query("SELECT * FROM personal_about", array());
            $row2	= $connector->fetchArray($result2);

            //RETURN VAlUE
            return $row2['about_id'];
        }

	}

    //#################################################################
    // CHECK IF PERSONAL INFO IS IN DATABASE
    //#################################################################
	function checkPersonalInfoDatabase($about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM personal_about WHERE about_id = ?", array($about_id));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // GET PERSONAL INFORMATION
    //#################################################################
	function getPersonalInfo($field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_about", array());
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // CHECK IF INSTITUTION HAS BEEN UPDATED
    //#################################################################
	function checkInstitutionUpdated($education_id, $title, $description, $location, $startDate, $endDate, $website){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_education WHERE education_id = ? AND start_date = ? AND end_date = ? AND education_place = ? AND location = ? AND description = ? AND website = ?", array($education_id, $startDate, $endDate, $title, $location, $description, $website));
		$totalResults	= $connector->numResults($result);

        //IF INSTITUTION HAS BEEN UPDATED
		if($totalResults == 0){
            return 'updated';
        }

	}

    //#################################################################
    // CHECK IF WORK PLACE HAS BEEN UPDATED
    //#################################################################
	function checkWorkPlaceUpdated($work_id, $title, $description, $location, $startDate, $endDate, $website){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_work WHERE work_id = ? AND start_date = ? AND end_date = ? AND work_place = ? AND location = ? AND description = ? AND website = ?", array($work_id, $startDate, $endDate, $title, $location, $description, $website));
		$totalResults	= $connector->numResults($result);

        //IF INSTITUTION HAS BEEN UPDATED
		if($totalResults == 0){
            return 'updated';
        }

	}

    //#################################################################
    // CHECK IF SKILL HAS BEEN UPDATED
    //#################################################################
	function checkSkillUpdated($skill_id, $title, $description, $percentage){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_skills WHERE skill_id = ? AND skill_name = ? AND percentage = ? AND description = ?", array($skill_id, $title, $percentage, $description));
		$totalResults	= $connector->numResults($result);

        //IF INSTITUTION HAS BEEN UPDATED
		if($totalResults == 0){
            return 'updated';
        }

	}

    //#################################################################
    // CHECK IF PERSONAL DETAILS HAVE BEEN UPDATED
    //#################################################################
	function checkPersonalDetailsUpdated($about_id, $first_name, $surname, $paragraph, $occupation, $tagline){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_about WHERE about_id = ? AND name = ? AND surname = ? AND tag_line = ? AND occupation = ? AND description = ?", array($about_id, $first_name, $surname, $tagline, $occupation, $paragraph));
		$totalResults	= $connector->numResults($result);

        //IF INSTITUTION HAS BEEN UPDATED
		if($totalResults == 0){
            return 'updated';
        }

	}

    //#################################################################
    // CHECK IF ADDITIONAL INFO HAS BEEN UPDATED
    //#################################################################
	function checkAdditionalInfoUpdated($education_id, $title, $description, $location, $startDate, $endDate){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_education WHERE education_id = ? AND start_date = ? AND end_date = ? AND education_place = ? AND location = ? AND description = ?", array($education_id, $startDate, $endDate, $title, $location, $description));
		$totalResults	= $connector->numResults($result);

        //IF INSTITUTION HAS BEEN UPDATED
		if($totalResults == 0){
            return 'updated';
        }

	}

    //#################################################################
    // GET CV DOCUMENT
    //#################################################################
	function getCVDocument($web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_about", array());
		$row	= $connector->fetchArray($result);
		$documentFile	= $row['cv'];

        //CHECK IF A DOCUMENT IS A AVAILABLE
        if($documentFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="document-spacing" align="center"><div class="document-header"><b>Current CV Document: </b><span><a href="'.$web_root.'cms-documents/'.$documentFile.'" title="View CV" target="_blank">CV</a></span></div><br /><input type="checkbox" name="removeDocumentCV" value="1" />Remove Document from paragraph</div>';
        }
		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET RESUME DOCUMENT
    //#################################################################
	function getResumeDocument($web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_about", array());
		$row	= $connector->fetchArray($result);
		$documentFile	= $row['resume'];

        //CHECK IF A DOCUMENT IS A AVAILABLE
        if($documentFile != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="document-spacing" align="center"><div class="document-header"><b>Current R&#233;sum&#233; Document: </b><span><a href="'.$web_root.'cms-documents/'.$documentFile.'" title="View R&#233;sum&#233;" target="_blank">R&#233;sum&#233;</a></span></div><br /><input type="checkbox" name="removeDocumentResume" value="1" />Remove Document from paragraph</div>';
        }
		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET PERSONAL IMAGE
    //#################################################################
	function getPersonalImage($web_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt.= '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM personal_about", array());
		$row	= $connector->fetchArray($result);
		$personal_image	= $row['personal_image'];
        $imageTitle     = $row['personal_image_title'];

        //CHECK IF IMAGE FILE IS AVAILABLE
        if($personal_image != ''){
    		//GENERATE OUTPUT
    		$txt.= '<div class="image-spacing" align="center"><div class="image-header"><b>Current Image:</b></div><br /><a href="'.$web_root.'cms-images/large/'.$personal_image.'" title="'.$imageTitle.'" class="group1"><img src="'.$web_root.'cms-images/medium/'.$personal_image.'" title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0"></a><div class="enlarge-image-text"><i>(Click on image to enlarge)</i></div><br /><input type="checkbox" value="1" name="removeImage" />Remove Image from Personal Details</div>';
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // CHECK IF INSTITUTION CONTENT IS IN DATABASE
    //#################################################################
	function checkInstitutionContentDatabase($education_id, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM personal_education WHERE education_id = ? AND about_id = ?", array($education_id, $about_id));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF WORK PLACE CONTENT IS IN DATABASE
    //#################################################################
	function checkWorkPlaceContentDatabase($work_id, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM personal_work WHERE work_id = ? AND about_id = ?", array($work_id, $about_id));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF SKILL CONTENT IS IN DATABASE
    //#################################################################
	function checkSkillContentDatabase($skill_id, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM personal_skills WHERE skill_id = ? AND about_id = ?", array($skill_id, $about_id));
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
    // ABOUT ME SECTION ARCHITECTURE
    //#################################################################
	function aboutMeSectionArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

        //GET INFO FROM DATABASE
        $result = $connector->query("SELECT * FROM personal_about", array());
        $row    = $connector->fetchArray($result);

        //SET VARIABLES
        $status_who_i_am    = '';
        $name               = $row['name'];
        $surname            = $row['surname'];
        $tag_line           = $row['tag_line'];
        $occupation         = $row['occupation'];
        $description        = $row['description'];
        $personal_image     = $row['personal_image'];

        $status_personal    = '';
        $birthplace         = $row['birthplace'];
        $residence          = $row['residence'];
        $hobbies            = $row['hobbies'];
        $date_birth         = $row['date_birth'];
        $resume             = $row['resume'];
        $cv                 = $row['cv'];

        //CHECK IF WHO I AM CONTENT IS EMPTY
        if($name == '' && $surname == '' && $tag_line == '' && $occupation == '' && $description == '' && $personal_image == ''){
            $status_who_i_am = 'removed-account';
        }
        //CHECK IF WHO I AM CONTENT IS NOT EMPTY
        elseif($name != '' && $surname != '' && $tag_line != '' && $occupation != '' && $description != '' && $personal_image != '') {
            $status_who_i_am = 'active-account';
        }
        //CHECK IF WHO I AM CONTENT IS PARTIALLY EMPTY
        else{
            $status_who_i_am = 'partial-account';
        }

        //GENERATE OUTPUT
        $txt.= '<div class="module-architecture-table-holder">
            <div class="module-architecture-table-heading">About Me</div>
            <table width="100%" class="module-architecture-table">

              <tr>
                <td width="1%" class="'.$status_who_i_am.'"></td>
                <td width="84%">Personal Details</td>
                <td width="16%" align="center">
                    <a href="'.$cms_root.'employment-history-manager/personal-details.php" title="Manage">Manage</a>
                </td>
              </tr>

            </table>

        </div>';

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // CARRIER SECTION ARCHITECTURE
    //#################################################################
	function carrierSectionArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

        //GET EDUCATION INFO FROM DATABASE
        $result = $connector->query("SELECT * FROM personal_education WHERE deletedBy = ? ORDER BY sequence ASC", array(0));
        $row    = $connector->fetchArray($result);

        //SET VARIABLES
        $status_education    = '';
        $education_id        = $row['education_id'];

        //CHECK IF EDUCATION CONTENT IS EMPTY
        if($education_id == ''){
            $status_education = 'removed-account';
        }
        //CHECK IF EDUCATION IS NOT EMPTY
        elseif($education_id != '') {
            $status_education = 'active-account';
        }

        //GET WORK INFO FROM DATABASE
        $result2 = $connector->query("SELECT * FROM personal_work WHERE deletedBy = ? ORDER BY sequence ASC", array(0));
        $row2    = $connector->fetchArray($result2);

        $status_work    = '';
        $work_id        = $row2['work_id'];

        //CHECK IF WORK DETAILS CONTENT IS EMPTY
        if($work_id == ''){
            $status_work = 'removed-account';
        }
        //CHECK IF WORK DETAILS CONTENT IS NOT EMPTY
        elseif($work_id != '') {
            $status_work = 'active-account';
        }

        //GENERATE OUTPUT
        $txt.= '<div class="module-architecture-table-holder">
            <div class="module-architecture-table-heading">Carrier</div>
            <table width="100%" class="module-architecture-table">

              <tr>
                <td width="1%" class="'.$status_education.'"></td>
                <td width="84%">Education</td>
                <td width="16%" align="center">
                    <a href="'.$cms_root.'employment-history-manager/manage-education-history.php" title="Manage">Manage</a>
                </td>
              </tr>
              <tr>
                <td class="'.$status_work.'"></td>
                <td>Work</td>
                <td align="center">
                    <a href="'.$cms_root.'employment-history-manager/manage-work-history.php" title="Manage">Manage</a>
                </td>
              </tr>

            </table>

        </div>';

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // SKILLS SECTION ARCHITECTURE
    //#################################################################
	function skillsSectionArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

        //GET EDUCATION INFO FROM DATABASE
        $result = $connector->query("SELECT * FROM personal_skills WHERE deletedBy = ? ORDER BY sequence ASC", array(0));
        $row    = $connector->fetchArray($result);

        //SET VARIABLES
        $status_skills       = '';
        $skill_id            = $row['skill_id'];

        //CHECK IF EDUCATION CONTENT IS EMPTY
        if($skill_id == ''){
            $status_skills = 'removed-account';
        }
        //CHECK IF EDUCATION IS NOT EMPTY
        elseif($skill_id != '') {
            $status_skills = 'active-account';
        }

        //GENERATE OUTPUT
        $txt.= '<div class="module-architecture-table-holder">
            <div class="module-architecture-table-heading">Skills</div>
            <table width="100%" class="module-architecture-table">

              <tr>
                <td width="1%" class="'.$status_skills.'"></td>
                <td width="84%">My Skills</td>
                <td width="16%" align="center">
                    <a href="'.$cms_root.'employment-history-manager/manage-skills.php" title="Manage">Manage</a>
                </td>
              </tr>

            </table>

        </div>';

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // EDUCATION HISTORY CONTENT ARCHITECTURE
    //#################################################################
	function educationHistoryContentArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM personal_education WHERE deletedBy = ? ORDER BY sequence ASC", array('0'));
		$institutionTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($institutionTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$education_id	    = $row['education_id'];
				$about_id		    = $row['about_id'];
				$start_date			= $row['start_date'];
				$end_date			= $row['end_date'];
				$education_place	= $row['education_place'];
				$location		    = $row['location'];
				$description		= $row['description'];
                $website            = $row['website'];

				//CHECK DESCRIPTION LENGTH
				$description	= strip_tags($description);
				if(strlen($description) > 450){
					$description	= substr($description, 1, 450).'...';
				}

                //SET DATES
                $start_date = date("F Y", strtotime($start_date));

                if($end_date != '0000-00-00'){
                    $end_date = date("F Y", strtotime($end_date));
                }else{
                    $end_date = 'Present';
                }

				            //GENERATE OUPUT
		            $txt.= '<div class="module-manage-content-holder sortable-content" id="'.$education_id.'">';

                    $txt.= '<div class="paragraph-title"><b>'.$education_place.'</b></div>';

					$txt.= '<div class="paragraph-text"><b>Description:</b> '.$description.'</div>';

                    $txt.= '<div class="paragraph-text"><b>Location:</b> '.$location.'</div>';

                    $txt.= '<div class="paragraph-text"><b>Time Period:</b> '.$start_date.' till '.$end_date.'</div>';

                    $txt.= '<div class="paragraph-text"><b>Website Link:</b> <a href="'.$website.'" target="_blank">'.$website.'</a></div>';

					$txt.= '<div class="module-manage-content-links">
						<form name="delete_institution'.$education_id.'">
							<input type="hidden" name="delete_institution" value="1">
							<input type="hidden" name="education_id" value="'.$education_id.'">
							<a href="javascript:deleteInstitution('.$education_id.')" title="Remove Institution">Remove Institution</a>
						</form>
						<a href="'.$cms_root.'employment-history-manager/edit-institution.php?education_id='.$education_id.'&about_id='.$about_id.'" title="Edit Institution">Edit Institution</a>
						<div class="clear"></div>
						</div>
                </div>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Institutions added. <a href="'.$cms_root.'employment-history-manager/add-institution.php" title="Add Institution">Please add an institution here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // WORK HISTORY CONTENT ARCHITECTURE
    //#################################################################
	function workHistoryContentArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM personal_work WHERE deletedBy = ? ORDER BY sequence ASC", array('0'));
		$institutionTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($institutionTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$work_id	        = $row['work_id'];
				$about_id		    = $row['about_id'];
				$start_date			= $row['start_date'];
				$end_date			= $row['end_date'];
				$work_place	        = $row['work_place'];
				$location		    = $row['location'];
				$description		= $row['description'];
                $website            = $row['website'];

				//CHECK DESCRIPTION LENGTH
				$description	= strip_tags($description);
				if(strlen($description) > 450){
					$description	= substr($description, 1, 450).'...';
				}

                //SET DATES
                $start_date = date("F Y", strtotime($start_date));

                if($end_date != '0000-00-00'){
                    $end_date = date("F Y", strtotime($end_date));
                }else{
                    $end_date = 'Present';
                }

				    //GENERATE OUPUT
		            $txt.= '<div class="module-manage-content-holder sortable-content" id="'.$work_id.'">';

                    $txt.= '<div class="paragraph-title"><b>'.$work_place.'</b></div>';

					$txt.= '<div class="paragraph-text"><b>Description:</b> '.$description.'</div>';

                    $txt.= '<div class="paragraph-text"><b>Location:</b> '.$location.'</div>';

                    $txt.= '<div class="paragraph-text"><b>Time Period:</b> '.$start_date.' till '.$end_date.'</div>';

                    $txt.= '<div class="paragraph-text"><b>Website Link:</b> <a href="'.$website.'" target="_blank">'.$website.'</a></div>';

					$txt.= '<div class="module-manage-content-links">
						<form name="delete_work_place'.$work_id.'">
							<input type="hidden" name="delete_work_place" value="1">
							<input type="hidden" name="work_id" value="'.$work_id.'">
							<a href="javascript:deleteWorkPlace('.$work_id.')" title="Remove Work Place">Remove Work Place</a>
						</form>
						<a href="'.$cms_root.'employment-history-manager/edit-work-place.php?work_id='.$work_id.'&about_id='.$about_id.'" title="Edit Work Place">Edit Work Place</a>
						<div class="clear"></div>
						</div>
                </div>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Work Places added. <a href="'.$cms_root.'employment-history-manager/add-work-place.php" title="Add Work Place">Please add a Work Place here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // SKILLS CONTENT ARCHITECTURE
    //#################################################################
	function skillsContentArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM personal_skills WHERE deletedBy = ? ORDER BY sequence ASC", array('0'));
		$institutionTotal = $connector->numResults($result);

		//IF CATEGORIES ARE AVAILABLE
		if($institutionTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$skill_id	        = $row['skill_id'];
				$about_id		    = $row['about_id'];
				$skill_name			= $row['skill_name'];
				$percentage			= $row['percentage'];
				$description		= $row['description'];

				//CHECK DESCRIPTION LENGTH
				$description	= strip_tags($description);
				if(strlen($description) > 450){
					$description	= substr($description, 1, 450).'...';
				}

				    //GENERATE OUPUT
		            $txt.= '<div class="module-manage-content-holder sortable-content" id="'.$skill_id.'">';

                    $txt.= '<div class="paragraph-title"><b>'.$skill_name.'</b></div>';

                    $txt.= '<div class="paragraph-text"><b>Percentage:</b> '.$percentage.'%</div>';

                    if($description != ''){
					    $txt.= '<div class="paragraph-text"><b>Description:</b> '.$description.'</div>';
                    }

					$txt.= '<div class="module-manage-content-links">
						<form name="delete_skill'.$skill_id.'">
							<input type="hidden" name="delete_skill" value="1">
							<input type="hidden" name="skill_id" value="'.$skill_id.'">
							<a href="javascript:deleteSkill('.$skill_id.')" title="Remove Skill">Remove Skill</a>
						</form>
						<a href="'.$cms_root.'employment-history-manager/edit-skill.php?skill_id='.$skill_id.'&about_id='.$about_id.'" title="Edit Skill">Edit Skill</a>
						<div class="clear"></div>
						</div>
                </div>';
			}
		}
		//IF NO CATEGORIES ARE AVAILABLE
		else{
			$txt.= '<div class="module-manage-content-holder-nothing">There are currently no Skills added. <a href="'.$cms_root.'employment-history-manager/add-skill.php" title="Add Skill">Please add a Skill here!</a></div>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // DELETE INSTITUTION
    //#################################################################
	function deleteInstitution($education_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE INSTITUTION
		$remove = $connector->query("DELETE FROM personal_education WHERE education_id = ?",array($education_id));

	}

    //#################################################################
    // DELETE WORK PLACE
    //#################################################################
	function deleteWorkPlace($work_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE INSTITUTION
		$remove = $connector->query("DELETE FROM personal_work WHERE work_id = ?",array($work_id));

	}

    //#################################################################
    // DELETE SKILL
    //#################################################################
	function deleteSkill($skill_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE INSTITUTION
		$remove = $connector->query("DELETE FROM personal_skills WHERE skill_id = ?",array($skill_id));

	}

    //#################################################################
    // ADD INSTITUTION
    //#################################################################
	function addInstitution($title, $description, $location, $startDate, $endDate, $website, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$location	    = strip_tags($location);

		//GET SEQUENCE
		$result	= $connector->query("SELECT * FROM personal_education WHERE about_id = ? AND deletedBy = ? ORDER BY sequence DESC", array($about_id, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO personal_education (about_id, start_date, end_date, education_place, website, location, description, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($about_id, $startDate, $endDate, $title, $website, $location, $description, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // ADD WORK PLACE
    //#################################################################
	function addWorkPlace($title, $description, $location, $startDate, $endDate, $website, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$location	    = strip_tags($location);

		//GET SEQUENCE
		$result	= $connector->query("SELECT * FROM personal_work WHERE about_id = ? AND deletedBy = ? ORDER BY sequence DESC", array($about_id, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO personal_work (about_id, start_date, end_date, work_place, website, location, description, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($about_id, $startDate, $endDate, $title, $website, $location, $description, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // ADD SKILL
    //#################################################################
	function addSkill($title, $description, $percentage, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);

		//GET SEQUENCE
		$result	= $connector->query("SELECT * FROM personal_skills WHERE about_id = ? AND deletedBy = ? ORDER BY sequence DESC", array($about_id, 0));
		$row	= $connector->fetchArray($result);
		$sequence = $row['sequence']+1;

		//ADD USER
		$insert = $connector->query("INSERT INTO personal_skills (about_id, skill_name, percentage, description, createdBy, createdDate, sequence)
									VALUES (?, ?, ?, ?, ?, ?, ?)",
									array($about_id, $title, $percentage, $description, $currentUser, $currentDate, $sequence));

	}

    //#################################################################
    // UPDATE PERSONAL INFORMATION
    //#################################################################
	function updatePersonalInfo($first_name, $surname, $paragraph, $occupation, $tagline, $image_title, $imageFile, $about_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$first_name       = strip_tags($first_name);
		$surname          = strip_tags($surname);
		$occupation       = strip_tags($occupation);
		$tagline          = strip_tags($tagline);
        $image_title      = strip_tags($image_title);
        $imageFile        = strip_tags($imageFile);

        //IMAGE DIRECTORIES
        $largeDirectory			= '../../cms-images/large/';
    	$mediumDirectory		= '../../cms-images/medium/';
    	$smallDirectory			= '../../cms-images/small/';

        //GET OLD IMAGE NAME
        $result = $connector->query("SELECT * FROM personal_about WHERE about_id = ?", array($about_id));
        $row    = $connector->fetchArray($result);
        $image  = $row['personal_image'];

        //CHECK IF A NEW IMAGE HAS BEEN ADDED
        if($imageFile != $image){
            //REMOVE IMAGES
            unlink($largeDirectory.$image);
            unlink($mediumDirectory.$image);
            unlink($smallDirectory.$image);
        }

        //CHECK IF ABOUT ID IS AVAILABLE
        if($about_id != ''){
            //GET NUMBER OF MODIFICATION
            $result = $connector->query("SELECT * FROM personal_about WHERE about_id = ?", array($about_id));
            $row    = $connector->fetchArray($result);
            $modifiedNumber = $row['modifiedNumber']+1;

    		//UPDATE PERSONAL INFO
    		$update			= $connector->query("UPDATE personal_about SET
                                                name                    = ?,
                                                surname                 = ?,
                                                tag_line                = ?,
                                                occupation              = ?,
                                                description             = ?,
                                                personal_image_title    = ?,
                                                personal_image          = ?,
                                                modifiedBy              = ?,
                                                modifiedNumber          = ?,
                                                modifiedDate            = ?
                                                WHERE about_id = ?",
                                                array($first_name, $surname, $tagline, $occupation, $paragraph, $image_title, $imageFile, $currentUser, $modifiedNumber, $currentDate, $about_id));
            //RETURN ID
            return $about_id;
        }else{
            //ADD PERSONAL INFO
    		$insert = $connector->query("INSERT INTO personal_about (name, surname, tag_line, occupation, description, personal_image_title, personal_image, createdBy, createdDate)
    									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
    									array($first_name, $surname, $tagline, $occupation, $paragraph, $image_title, $imageFile, $currentUser, $currentDate));

            //RETURN ID
            $result2 = $connector->query("SELECT * FROM personal_about ORDER BY about_id DESC",array());
            $row2    = $connector->fetchArray($result2);
            return $row2['about_id'];

        }

	}

    //#################################################################
    // UPDATE INSTITUTION
    //#################################################################
	function updateInstitution($title, $description, $location, $startDate, $endDate, $education_id, $website){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$location	    = strip_tags($location);

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM personal_education WHERE education_id = ?", array($education_id));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE personal_education SET
                                            start_date      = ?,
                                            end_date        = ?,
                                            education_place = ?,
                                            website         = ?,
                                            location        = ?,
                                            description	    = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE education_id = ?",
                                            array($startDate, $endDate, $title, $website, $location, $description, $currentUser, $modifiedNumber, $currentDate, $education_id));

	}

    //#################################################################
    // UPDATE WORK PLACE
    //#################################################################
	function updateWorkPlace($title, $description, $location, $startDate, $endDate, $work_id, $website){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);
		$location	    = strip_tags($location);

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM personal_work WHERE work_id = ?", array($work_id));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE personal_work SET
                                            start_date      = ?,
                                            end_date        = ?,
                                            work_place      = ?,
                                            website         = ?,
                                            location        = ?,
                                            description	    = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE work_id = ?",
                                            array($startDate, $endDate, $title, $website, $location, $description, $currentUser, $modifiedNumber, $currentDate, $work_id));

	}

    //#################################################################
    // UPDATE SKILL
    //#################################################################
	function updateSkill($title, $description, $percentage, $skill_id){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);

        //GET NUMBER OF MODIFICATION
        $result = $connector->query("SELECT * FROM personal_skills WHERE skill_id = ?", array($skill_id));
        $row    = $connector->fetchArray($result);
        $modifiedNumber = $row['modifiedNumber']+1;

		//ADD BLOG POST CONTENT
		$update			= $connector->query("UPDATE personal_skills SET
                                            skill_name      = ?,
                                            percentage      = ?,
                                            description	    = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE skill_id = ?",
                                            array($title, $percentage, $description, $currentUser, $modifiedNumber, $currentDate, $skill_id));

	}

}

//DEFINE CLASS
$employmentHistoryManager = new employmentHistoryManager();

//#################################################################
// UPDATE PERSONAL DETAILS
//#################################################################
if(isset($_POST['update_personal_details'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $about_id          = $_POST['about_id'];
	$first_name        = $_POST['first-name'];
    $surname           = $_POST['surname'];
	$paragraph         = $_POST['paragraph'];
    $occupation        = $_POST['occupation'];
    $tagline           = $_POST['tagline'];
    $removeImage       = $_POST['removeImage'];
    $oldImage          = $_POST['oldImage'];

	//HONEY POTS
	$nickname          = $_POST['nickname'];
	$image_type        = $_POST['image-type'];

	//IMAGE PROPERTIES
	$inputField				= 'image-file';
	$originalDirectory		= '../../cms-images/original/';
	$largeDirectory			= '../../cms-images/large/';
	$mediumDirectory		= '../../cms-images/medium/';
	$smallDirectory			= '../../cms-images/small/';
	$previewSize			= 800;

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $first_name = $userLogin->specialCharactersToHTMLEntity($first_name);
    $surname    = $userLogin->specialCharactersToHTMLEntity($surname);
    $occupation = $userLogin->specialCharactersToHTMLEntity($occupation);
    $tagline    = $userLogin->specialCharactersToHTMLEntity($tagline);

	//VALIDATION
	$v = new formValidation();

    //IF A FIRST NAME HAS BEEN ADDED
	if($first_name != ''){
		$v->validateString($first_name, 'First Name', 2, 200);
	}

    //IF A SURNAME HAS BEEN ADDED
	if($surname != ''){
		$v->validateString($surname, 'Surname', 3, 200);
	}

    //IF A PERSONAL DESCRIPTION HAS BEEN ADDED
    if($paragraph){
       $v->validateText($paragraph, 'Personal Description', 10);
    }

    //IF AN OCCUPATION HAS BEEN ADDED
	if($occupation != ''){
		$v->validateString($occupation, 'Occupation', 2, 200);
	}

    //IF A TAGLINE HAS BEEN ADDED
	if($tagline != ''){
		$v->validateString($tagline, 'Tagline', 3, 150);
	}

	//IF A IMAGE HAS BEEN ADDED
	if($_FILES[$inputField]["tmp_name"] != ""){
		$v->validateImage($inputField, 'Image File');
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

        //CHECK IF PERSONAL DETAILS HAVE BENN UPDATED
        if($_FILES[$inputField]["tmp_name"] != "" || $removeImage == 1 || $employmentHistoryManager->checkPersonalDetailsUpdated($about_id, $first_name, $surname, $paragraph, $occupation, $tagline) == 'updated'){

    		//CHECK IF ALL CONDITONS HAVE BEEN MET
    		if($nickname == '' && $image_type == ''){

    			//IF AN IMAGE HAS BEEN ADDED
    			if($_FILES[$inputField]["tmp_name"] != ""){
                    //GENERATE PERSONAL IMAGE NAME
                    if($first_name != '' && $surname != ''){
                        $image_title = $first_name.' '.$surname;
                    }else{
                        $image_title = '';
                    }

                    //UPLOAD IMAGE
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
                        $image_title    = $employmentHistoryManager->getPersonalInfo('personal_image_title');
                    }
                }

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

    			//INSERT PERSOANL INFO INTO DATABASE
    			$about_id = $employmentHistoryManager->updatePersonalInfo($first_name, $surname, $paragraph, $occupation, $tagline, $image_title, $imageFile, $about_id);

    			//GET META DETAILS
    			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
    			$description	= $employmentHistoryManager->getMetaDescription($about_id);

    			//UPDATE META DETAILS
    			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

    			//IF IMAGE HAS BEEN UPLOADED SEND TO CROP
    			if($_FILES[$inputField]["tmp_name"] != ""){
    				header("Location: ".$cms_root."employment-history-manager/crop-image.php?about_id=".$about_id."&imageFileName=".$imageFile."&width=".$width."&height=".$height."&message=1");
            		exit;
    			}
    			//REDIRECT TO BLOG POST
    			else{
    				header("Location: ".$cms_root."employment-history-manager/index.php?message=1");
            		exit;
    			}
    		}
        }else{
            header("Location: ".$cms_root."employment-history-manager/");
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
// ADD INSTITUTION
//#################################################################
if(isset($_POST['add_institution'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['institution-title'];
	$description 	= $_POST['paragraph'];
	$location		= $_POST['institution-location'];
	$startDate	    = $_POST['institution-start-date'];
	$endDate		= $_POST['institution-end-date'];
    $website        = $_POST['institution-website'];

	//HONEY POTS
	$institution_type	     = $_POST['institution-type'];
	$institution_country	 = $_POST['institution-country'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $location    = $userLogin->specialCharactersToHTMLEntity($location);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Institution of Study', 1, 250);
	$v->validateText($description, 'Description', 10);
    $v->validateString($location, 'Location of Institution', 1, 200);
    $v->validateDate($startDate, 'Start Date');

    //CHECK IF END DATE HAS BEEN SUPLLIED
    if($endDate != ''){
        $v->validateDate($endDate, 'End Date');
    }

    $v->validateLink($website, 'Institution Website Link');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($institution_type == '' && $institution_country == ''){

            //GET ABOUT ID
            $about_id = $employmentHistoryManager->getAboutID();

			//REMOVE LINE BREAKS FROM PARAGRAPH
			$description = str_replace('\r\n', '', $description);

			//INSERT INSTITUTION INTO DATABASE
			$employmentHistoryManager->addInstitution($title, $description, $location, $startDate, $endDate, $website, $about_id);

			//GET META DETAILS
			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
			$description	= $employmentHistoryManager->getMetaDescription($about_id);

			//UPDATE META DETAILS
			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

			header("Location: ".$cms_root."employment-history-manager/manage-education-history.php?message=3");
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
// ADD WORK PLACE
//#################################################################
if(isset($_POST['add_work_place'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['work-place-title'];
	$description 	= $_POST['paragraph'];
	$location		= $_POST['work-place-location'];
	$startDate	    = $_POST['work-start-date'];
	$endDate		= $_POST['work-end-date'];
    $website        = $_POST['work-website'];

	//HONEY POTS
	$work_type           = $_POST['work-type'];
	$work_place_country	 = $_POST['work-place-country'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $location    = $userLogin->specialCharactersToHTMLEntity($location);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Place of Work', 1, 250);
	$v->validateText($description, 'Description', 10);
    $v->validateString($location, 'Location of Work Place', 1, 200);
    $v->validateDate($startDate, 'Start Date');

    //IF AN END DATE HAS BEEN SUPPLIED
    if($endDate != ''){
	      $v->validateDate($endDate, 'End Date');
    }

    $v->validateLink($website, 'Work Place Website Link');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($work_type == '' && $work_place_country == ''){

            //GET ABOUT ID
            $about_id = $employmentHistoryManager->getAboutID();

			//REMOVE LINE BREAKS FROM PARAGRAPH
			$description = str_replace('\r\n', '', $description);

			//INSERT WORK PLACE INTO DATABASE
			$employmentHistoryManager->addWorkPlace($title, $description, $location, $startDate, $endDate, $website, $about_id);

			//GET META DETAILS
			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
			$description	= $employmentHistoryManager->getMetaDescription($about_id);

			//UPDATE META DETAILS
			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

			header("Location: ".$cms_root."employment-history-manager/manage-work-history.php?message=6");
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
// ADD SKILL
//#################################################################
if(isset($_POST['add_skill'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		    = $_POST['skill-title'];
	$description 	= $_POST['paragraph'];
	$percentage		= $_POST['skill-level'];

	//HONEY POTS
	$skill_type          = $_POST['skill-type'];
	$skill_location	     = $_POST['skill-location'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Name of Skill', 1, 250);
    $v->validateNumbersLimit($percentage, 'Skill Level', '0', '100');

    if($description != ''){
	    $v->validateText($description, 'Description', 10);
    }
	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($skill_type == '' && $skill_location == ''){

            //GET ABOUT ID
            $about_id = $employmentHistoryManager->getAboutID();

			//REMOVE LINE BREAKS FROM PARAGRAPH
			$description = str_replace('\r\n', '', $description);

			//INSERT WORK PLACE INTO DATABASE
			$employmentHistoryManager->addSkill($title, $description, $percentage, $about_id);

			//GET META DETAILS
			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
			$description	= $employmentHistoryManager->getMetaDescription($about_id);

			//UPDATE META DETAILS
			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

			header("Location: ".$cms_root."employment-history-manager/manage-skills.php?message=9");
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
// EDIT INSTITUTION
//#################################################################
if(isset($_POST['edit_institution'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$education_id      = $_POST['education_id'];
	$about_id          = $_POST['about_id'];
	$title             = $_POST['institution-title'];
	$description       = $_POST['paragraph'];
	$location          = $_POST['institution-location'];
	$startDate         = $_POST['institution-start-date'];
	$endDate		   = $_POST['institution-end-date'];
    $website           = $_POST['institution-website'];

	//HONEY POTS
	$institution_type      = $_POST['institution-type'];
	$institution_country   = $_POST['institution-country'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $location    = $userLogin->specialCharactersToHTMLEntity($location);

	//VALIDATION
	$v = new formValidation();
    $v->validateString($title, 'Institution of Study', 1, 250);
	$v->validateText($description, 'Description', 10);
    $v->validateString($location, 'Location of Institution', 1, 200);
    $v->validateDate($startDate, 'Start Date');

    //CHECK IF END DATE HAS BEEN SUPPLIED
    if($endDate != ''){
        $v->validateDate($endDate, 'End Date');
    }else{
        $endDate = '0000-00-00';
    }

    $v->validateLink($website, 'Institution Website Link');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

        //CHECK IF CONTENT HAS BEEN MODIFIED
        if($employmentHistoryManager->checkInstitutionUpdated($education_id, $title, $description, $location, $startDate, $endDate, $website) == 'updated'){

    		//CHECK IF ALL CONDITONS HAVE BEEN MET
    		if($institution_type == '' && $institution_country == ''){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$description = str_replace('\r\n', '', $description);

    			//UPDATE INSTITUTION
    			$employmentHistoryManager->updateInstitution($title, $description, $location, $startDate, $endDate, $education_id, $website);

    			//GET META DETAILS
    			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
    			$description	= $employmentHistoryManager->getMetaDescription($about_id);

    			//UPDATE META DETAILS
    			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

    			header("Location: ".$cms_root."employment-history-manager/manage-education-history.php?message=4");
        		exit;
    		}
        }else{
            header("Location: ".$cms_root."employment-history-manager/manage-education-history.php");
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
// EDIT WORK PLACE
//#################################################################
if(isset($_POST['edit_work_place'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$work_id           = $_POST['work_id'];
	$about_id          = $_POST['about_id'];
	$title             = $_POST['work-place-title'];
	$description       = $_POST['paragraph'];
	$location          = $_POST['work-place-location'];
	$startDate         = $_POST['work-start-date'];
	$endDate		   = $_POST['work-end-date'];
    $website           = $_POST['work-website'];

	//HONEY POTS
	$work_type            = $_POST['work-type'];
	$work_place_country   = $_POST['work-place-country'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);
    $location    = $userLogin->specialCharactersToHTMLEntity($location);

	//VALIDATION
	$v = new formValidation();
    $v->validateString($title, 'Place of Work', 1, 250);
	$v->validateText($description, 'Description', 10);
    $v->validateString($location, 'Location of Work Place', 1, 200);
    $v->validateDate($startDate, 'Start Date');

    //CHECK IF AN END DATE HAS BEEN SUPPLIED
    if($endDate != ''){
        $v->validateDate($endDate, 'End Date');
    }else{
        $endDate = '0000-00-00';
    }

    $v->validateLink($website, 'Work Place Website Link');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

        //CHECK IF CONTENT HAS BEEN MODIFIED
        if($employmentHistoryManager->checkWorkPlaceUpdated($work_id, $title, $description, $location, $startDate, $endDate, $website) == 'updated'){

    		//CHECK IF ALL CONDITONS HAVE BEEN MET
    		if($work_type == '' && $work_place_country == ''){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$description = str_replace('\r\n', '', $description);

    			//UPDATE WORK PLACE
    			$employmentHistoryManager->updateWorkPlace($title, $description, $location, $startDate, $endDate, $work_id, $website);

    			//GET META DETAILS
    			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
    			$description	= $employmentHistoryManager->getMetaDescription($about_id);

    			//UPDATE META DETAILS
    			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

    			header("Location: ".$cms_root."employment-history-manager/manage-work-history.php?message=7");
        		exit;
    		}
        }else{
            header("Location: ".$cms_root."employment-history-manager/manage-work-history.php");
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
// EDIT SKILL
//#################################################################
if(isset($_POST['edit_skill'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$skill_id          = $_POST['skill_id'];
	$about_id          = $_POST['about_id'];
	$title             = $_POST['skill-title'];
	$description       = $_POST['paragraph'];
	$percentage        = $_POST['skill-level'];

	//HONEY POTS
	$skill_type           = $_POST['skill-type'];
	$skill_location       = $_POST['skill-location'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title       = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
	$v = new formValidation();
    $v->validateString($title, 'Name of Skill', 1, 250);
    $v->validateNumbersLimit($percentage, 'Skill Level', 0, 100);

    if($description != ''){
	    $v->validateText($description, 'Description', 10);
    }
	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

        //CHECK IF CONTENT HAS BEEN MODIFIED
        if($employmentHistoryManager->checkSkillUpdated($skill_id, $title, $description, $percentage) == 'updated'){

    		//CHECK IF ALL CONDITONS HAVE BEEN MET
    		if($skill_type == '' && $skill_location == ''){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$description = str_replace('\r\n', '', $description);

    			//UPDATE WORK PLACE
    			$employmentHistoryManager->updateSkill($title, $description, $percentage, $skill_id);

    			//GET META DETAILS
    			$keywords		= $employmentHistoryManager->getMetaKeyword($about_id);
    			$description	= $employmentHistoryManager->getMetaDescription($about_id);

    			//UPDATE META DETAILS
    			$employmentHistoryManager->updateMetaDetails($keywords, $description, $about_id);

    			header("Location: ".$cms_root."employment-history-manager/manage-skills.php?message=10");
        		exit;
    		}
        }else{
            header("Location: ".$cms_root."employment-history-manager/manage-skills.php");
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
//DELETE INSTITUTION
//#################################################################
if(isset($_POST['delete_institution'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$education_id	= $_POST['education_id'];

    //SET USER AS REMOVED IN DATABASE
    $employmentHistoryManager->deleteInstitution($education_id);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."employment-history-manager/manage-education-history.php?message=5");
    exit;
}

//#################################################################
//DELETE WORK PLACE
//#################################################################
if(isset($_POST['delete_work_place'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$work_id	= $_POST['work_id'];

    //SET USER AS REMOVED IN DATABASE
    $employmentHistoryManager->deleteWorkPlace($work_id);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."employment-history-manager/manage-education-history.php?message=8");
    exit;
}

//#################################################################
//DELETE SKILL
//#################################################################
if(isset($_POST['delete_skill'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$skill_id	= $_POST['skill_id'];

    //SET USER AS REMOVED IN DATABASE
    $employmentHistoryManager->deleteSkill($skill_id);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."employment-history-manager/manage-skills.php?message=11");
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
$newWidth		= 400;
$newHeight		= 400;

//CALCULATE NEW RATIO
$ratio			= $newWidth / $newHeight;

//CROP IMAGE WHEN FINISHED SELECTING AREA
if(isset($_POST['crop'])){
	//CONVERT POSTS
	$imageFileName		= $_POST['imageFileName'];
	$imageWidth			= $_POST['width'];
	$imageHeight		= $_POST['height'];
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

	//REDIRECT TO INDEX PAGE
	header("Location: ".$cms_root."employment-history-manager/index.php?message=".$message);
    exit;
}
###################################################################
?>
