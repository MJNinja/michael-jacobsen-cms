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

class vacancyManager extends systemConfig{
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
            case 1: $displayMessage = 'A new Vacancy has successfully been added.'; break;
            case 2: $displayMessage = 'The selected Vacancy has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected Vacancy has successfully been removed.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET META KEYWORDS
    //#################################################################
	function getMetaKeyword($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL PARAGRAPHS
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ? AND deletedBy = ? ORDER BY sequence ASC", array($softwareID, 0));
		while($row	= $connector->fetchArray($result)){
			$txt.=	strip_tags($row['softwareDescription']).' '.strip_tags($row['softwareName']);
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
	function getMetaDescription($softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$txt		= '';

		//SELECT ALL PARAGRAPHS FOR SELECTED PAGE
		$result = $connector->query("SELECT * FROM softwares WHERE softwareID = ? AND deletedBy = ? ORDER BY sequence ASC", array($softwareID, 0));
		while($row 	= $connector->fetchArray($result)){
			$txt.= strip_tags($row['softwareDescription']);
		}

		//SHORTEN TEXT
		$metaDescription	= substr(strip_tags($txt),0,500);

		//RETURN OUTPUT
		return $metaDescription;
	}

	//#################################################################
	//UPDATE META DETAILS
	//#################################################################
	function updateMetaDetails($keywords, $description, $softwareID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF DETAILS ALREADY EXISTS
		$result	= $connector->query("SELECT * FROM meta_details WHERE softwareID = ?", array($softwareID));
		$total	= $connector->numResults($result);

		//INSERT META DETAILS
		if($total == 0){
			$insert		= $connector->query("INSERT INTO meta_details (softwareID, metaKeywords, metaDescription)
											VALUES (?, ?, ?)",
											array($softwareID, $keywords, $description));
		}
		//UPDATE META DETAILS
		else{
			$update			= $connector->query("UPDATE meta_details SET
												metaKeywords	= ?,
												metaDescription	= ?
												WHERE softwareID = ?",
												array($keywords, $description, $softwareID));
		}
	}

	//#################################################################
    // GET VACANCY INFORMATION
    //#################################################################
	function getVacancyInfo($vacID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET VACANCY INFO
		$result = $connector->query("SELECT * FROM vac_listings WHERE vacID = ?", array($vacID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

	//#################################################################
    // CHECK IF VACANCY IS IN DATABASE
    //#################################################################
	function checkVacancyDatabase($vacID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET VACANCY TOTAL
		$result = $connector->query("SELECT * FROM vac_listings WHERE vacID = ?", array($vacID));
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
    // GET TOTAL VACANCIES
    //#################################################################
	function getTotalVacancies(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET TOTAL VACANCIES
		$result = $connector->query("SELECT * FROM vac_listings WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL ACTIVE VACANCIES
    //#################################################################
	function getTotalActiveVacancies(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $currentDate = date('Y-m-d H:i:s');

		//GET TOTAL VACANCIES
		$result = $connector->query("SELECT * FROM vac_listings WHERE deletedBy = ? AND startDate <= ? AND endDate >= ?", array('0', $currentDate, $currentDate));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL PENDING VACANCIES
    //#################################################################
	function getTotalPendingVacancies(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $currentDate = date('Y-m-d H:i:s');

		//GET TOTAL VACANCIES
		$result = $connector->query("SELECT * FROM vac_listings WHERE deletedBy = ? AND startDate > ?", array('0', $currentDate));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL EXPIRED VACANCIES
    //#################################################################
	function getTotalExpiredVacancies(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $currentDate = date('Y-m-d H:i:s');

		//GET TOTAL VACANCIES
		$result = $connector->query("SELECT * FROM vac_listings WHERE deletedBy = ? AND endDate < ?", array('0', $currentDate));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // VACANCY ARCHITECTURE
    //#################################################################
	function vacancyArchitecture($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];
        $currentDate = date('Y-m-d H:i:s');

		//GET ALL VACANCIES
		$result = $connector->query("SELECT * FROM vac_listings WHERE deletedBy = ? ORDER BY jobTitle ASC", array('0'));
		$vacancyTotal = $connector->numResults($result);

		//IF VACANCIES ARE AVAILABLE
		if($vacancyTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
                $status         = '';
                $status_bg      = '';
				$vacID		    = $row['vacID'];
                $jobTitle	    = $row['jobTitle'];
                $startDate      = $row['startDate'];
                $endDate        = $row['endDate'];

                //CONVERTED DATES
                $convertedStartDate      = date('j F Y', strtotime($row['startDate']));
                $convertedEndDate        = date('j F Y', strtotime($row['endDate']));

                //CHECK IF VACANCY STILL HAS TO BE SHOWN
                if($currentDate < $startDate){
                    $status         = '<span class="unpublished-post-text">(Pending)</span>';
                    $status_bg      = 'class="unpublished-post"';
                }
                //CHECK IF VACANCY HAS EXPIRED
                elseif($currentDate > $endDate){
                    $status         = '<span class="empty-category-text">(Expired)</span>';
                    $status_bg      = 'class="empty-category"';
                }

				//GENERATE OUPUT
				$txt.= '<tr>
					<td '.$status_bg.'>'.$jobTitle.' '.$status.'</td>
                    <td '.$status_bg.'>'.$convertedStartDate.'</td>
                    <td '.$status_bg.'>'.$convertedEndDate.'</td>
					<td align="center" '.$status_bg.'>
						<a href="'.$cms_root.'vacancy-manager/edit-vacancy.php?vacID='.$vacID.'" title="Modify">Modify</a>
					</td>
					<td align="center" '.$status_bg.'>
					<form name="delete_vacancy'.$vacID.'">
						<input type="hidden" name="delete_vacancy" value="1">
						<input type="hidden" name="vacID" value="'.$vacID.'">
						<a href="javascript:deleteVacancy('.$vacID.')" title="Remove">Remove</a>
					</form>
					</td>
				  </tr>';

			}
		}
		//IF NO VACANCIES ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="5">There are currently no Vacancies available. <a href="'.$cms_root.'vacancy-manager/add-vacancy.php" title="Add Vacancy">Please add a vacancy here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // GET OWNERS
    //#################################################################
	function getOwners($currentOwnerID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET OWNER INFO
		$result = $connector->query("SELECT * FROM vac_owner ORDER BY vacOwnerName ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $vacOwnerID     = $row['vacOwnerID'];
            $vacOwnerName   = $row['vacOwnerName'];

            //GENERATE OUTPUT
            if($currentOwnerID == $vacOwnerID){
                $txt.= '<option value="'.$vacOwnerID.'" selected="selected">'.$vacOwnerName.'</option>';
            }else{
                $txt.= '<option value="'.$vacOwnerID.'">'.$vacOwnerName.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET TYPE
    //#################################################################
	function getType($currentTypeID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET TYPE INFO
		$result = $connector->query("SELECT * FROM vac_job ORDER BY type ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $value     = $row['value'];
            $type      = $row['type'];

            //GENERATE OUTPUT
            if($currentTypeID == $value){
                $txt.= '<option value="'.$value.'" selected="selected">'.$type.'</option>';
            }else{
                $txt.= '<option value="'.$value.'">'.$type.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET CITY
    //#################################################################
	function getCity($currentCityID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET CTY INFO
		$result = $connector->query("SELECT * FROM vac_city ORDER BY city ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $value     = $row['value'];
            $city      = $row['city'];

            //GENERATE OUTPUT
            if($currentCityID == $value){
                $txt.= '<option value="'.$value.'" selected="selected">'.$city.'</option>';
            }else{
                $txt.= '<option value="'.$value.'">'.$city.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET CATEGORY
    //#################################################################
	function getCategory($currentCategoryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET CTY INFO
		$result = $connector->query("SELECT * FROM vac_categories ORDER BY name ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $value     = $row['value'];
            $name      = $row['name'];

            //GENERATE OUTPUT
            if($currentCategoryID == $value){
                $txt.= '<option value="'.$value.'" selected="selected">'.$name.'</option>';
            }else{
                $txt.= '<option value="'.$value.'">'.$name.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET SALARY
    //#################################################################
	function getSalary($currentSalaryID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET CTY INFO
		$result = $connector->query("SELECT * FROM vac_salary ORDER BY description ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $value            = $row['value'];
            $description      = $row['description'];

            //GENERATE OUTPUT
            if($currentSalaryID == $value){
                $txt.= '<option value="'.$value.'" selected="selected">'.$description.'</option>';
            }else{
                $txt.= '<option value="'.$value.'">'.$description.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
	//OVERWRITE SOFTWARE
	//#################################################################
	function overwriteSoftware($name, $paragraph, $link){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP TAGS
		$name	    = strip_tags($name);
        $link	    = strip_tags($link);

		//RE-ACTIVATE SOFTWARE
		$update = $connector->query("UPDATE softwares SET
                                    softwareLink = ?,
                                    softwareDescription = ?,
									deletedBy = ?,
									deletedDate = ?
									WHERE softwareName = ?",
									array($link, $paragraph, '0', '0000-00-00 00:00:00', $name));

	}

    //#################################################################
    // CHECK IF VACANCY INFO HAS BEEN CHANGED
    //#################################################################
	function checkVacancyChanges($title, $owner, $type, $city, $category, $salary, $email, $application, $start, $end, $paragraph, $vacID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VALUES
        if($title == '' || $title == ' '){
            $title = '';
        }

        if($owner == '' || $owner == ' '){
            $owner = 0;
        }

        if($type == '' || $type == ' '){
            $type = 0;
        }

        if($city == '' || $city == ' '){
            $city = 0;
        }

        if($category == '' || $category == ' '){
            $category = 0;
        }

        if($salary == '' || $salary == ' '){
            $salary = 0;
        }

        if($email == '' || $email == ' '){
            $email = '';
        }

        if($application == '' || $application == ' '){
            $application = 0;
        }

        if($paragraph == '' || $paragraph == ' '){
            $paragraph = '';
        }

		//STRIP INFO
		$title		    = strip_tags($title);
        $email  	    = strip_tags($email);

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM vac_listings WHERE type = ? AND city = ? AND category = ? AND salary = ? AND vacOwnerID = ? AND startDate = ? AND endDate = ? AND email = ? AND jobTitle = ? AND description = ? AND showForm = ? AND vacID = ?", array($type, $city, $category, $salary, $owner, $start, $end, $email, $title, $paragraph, $application, $vacID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD VACANCY
    //#################################################################
	function addVacancy($title, $owner, $type, $city, $category, $salary, $email, $application, $start, $end, $paragraph){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //SET VALUES
        if($title == '' || $title == ' '){
            $title = '';
        }

        if($owner == '' || $owner == ' '){
            $owner = 0;
        }

        if($type == '' || $type == ' '){
            $type = 0;
        }

        if($city == '' || $city == ' '){
            $city = 0;
        }

        if($category == '' || $category == ' '){
            $category = 0;
        }

        if($salary == '' || $salary == ' '){
            $salary = 0;
        }

        if($email == '' || $email == ' '){
            $email = '';
        }

        if($application == '' || $application == ' '){
            $application = 0;
        }

        if($end == '' || $end == ' '){
            $end = date('Y-m-d', strtotime($start. ' + 14 days'));
        }

        if($paragraph == '' || $paragraph == ' '){
            $paragraph = '';
        }

		//STRIP INFO
		$title		    = strip_tags($title);
        $email  	    = strip_tags($email);

		//ADD VACANCY
		$insert = $connector->query("INSERT INTO vac_listings(type, city, category, salary, vacOwnerID, startDate, endDate, email, jobTitle, description, showForm, creadtedBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($type, $city, $category, $salary, $owner, $start, $end, $email, $title, $paragraph, $application, $currentUser, $currentDate));

	}

	//#################################################################
    // UPDATE VACANCY
    //#################################################################
	function updateVacancy($title, $owner, $type, $city, $category, $salary, $email, $application, $start, $end, $paragraph, $modifiedBy, $modifiedDate, $modifiedNumber, $vacID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VALUES
        if($title == '' || $title == ' '){
            $title = '';
        }

        if($owner == '' || $owner == ' '){
            $owner = 0;
        }

        if($type == '' || $type == ' '){
            $type = 0;
        }

        if($city == '' || $city == ' '){
            $city = 0;
        }

        if($category == '' || $category == ' '){
            $category = 0;
        }

        if($salary == '' || $salary == ' '){
            $salary = 0;
        }

        if($email == '' || $email == ' '){
            $email = '';
        }

        if($application == '' || $application == ' '){
            $application = 0;
        }

        if($end == '' || $end == ' '){
            $end = date('Y-m-d', strtotime($start. ' + 14 days'));
        }

        if($paragraph == '' || $paragraph == ' '){
            $paragraph = '';
        }

		//STRIP INFO
		$title		    = strip_tags($title);
        $email  	    = strip_tags($email);

		//UPDATE VACANCY
		$update = $connector->query("UPDATE vac_listings SET
									type = ?,
                                    city = ?,
                                    category = ?,
                                    salary = ?,
                                    vacOwnerID = ?,
                                    startDate = ?,
                                    endDate = ?,
                                    email = ?,
                                    jobTitle = ?,
                                    description = ?,
                                    showForm = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE vacID = ?",
									array($type, $city, $category, $salary, $owner, $start, $end, $email, $title, $paragraph, $application, $modifiedBy, $modifiedDate, $modifiedNumber, $vacID));

	}

	//#################################################################
    // DELETE VACANCY
    //#################################################################
	function deleteVacancy($vacID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//REMOVE VACANCY
		$remove = $connector->query("DELETE FROM vac_listings WHERE vacID = ?", array($vacID));

	}

}

//DEFINE CLASS
$vacancyManager = new vacancyManager();


//#################################################################
//DELETE VACANCY
//#################################################################
if(isset($_POST['delete_vacancy'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $vacID	= $_POST['vacID'];

    //REMOVE VACANCY
    $vacancyManager->deleteVacancy($vacID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."vacancy-manager/index.php?message=3");
    exit;
}

//#################################################################
// ADD VACANCY
//#################################################################
if(isset($_POST['add_vacancy'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		   = $_POST['vacancy-title'];
    $owner          = $_POST['owner'];
    $type           = $_POST['type'];
    $city           = $_POST['city'];
    $category       = $_POST['category'];
    $salary         = $_POST['salary'];
	$email          = $_POST['vacancy-email'];
    $application    = $_POST['application-form'];
	$start	        = $_POST['start-date'];
    $end            = $_POST['end-date'];
	$paragraph	    = $_POST['paragraph'];

	//HONEY POTS
	$email_2	= $_POST['vacancy-email-2'];
	$contact	= $_POST['vacancy-contact'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title           = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Vacancy Title', 3, 200);
    $v->validateDropDown($owner, 'Owner of the Vacancy');
    $v->validateDropDown($type, 'Type of Vacancy');
    $v->validateDropDown($city, 'City');
    $v->validateDropDown($category, 'Vacancy Category');
    $v->validateDropDown($salary, 'Salary');

    //IF EMAIL IS SUPPLIED
    if($email != '' && $email != ' '){
        $v->validateEmailAddress($email, 'Vacancy Email');
    }

    // IF APPLICATION FORM HAS BEEN CHECKED
    if($application != '' && $application != ' ' && $application != 0){
        $v->validateTags($application, 'Show Application Form');
    }

    $v->validateStartEndDates($start, $end, 'Vacancy Date');

    $v->validateText($paragraph, 'Description', 10);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($email_2 == '' && $contact == ''){

            //REMOVE LINE BREAKS FROM PARAGRAPH
			$paragraph = str_replace('\r\n', '', $paragraph);

			//INSERT VACANCY INTO DATABASE
			$vacancyManager->addVacancy($title, $owner, $type, $city, $category, $salary, $email, $application, $start, $end, $paragraph);

            //REDIRECT USER
			header("Location: ".$cms_root."vacancy-manager/index.php?message=1");
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
// EDIT VACANCY
//#################################################################
if(isset($_POST['edit_vacancy'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VALUES
    $vacID          = $_POST['vacID'];
	$title		    = $_POST['vacancy-title'];
    $owner          = $_POST['owner'];
    $type           = $_POST['type'];
    $city           = $_POST['city'];
    $category       = $_POST['category'];
    $salary         = $_POST['salary'];
	$email          = $_POST['vacancy-email'];
    $application    = $_POST['application-form'];
	$start	        = $_POST['start-date'];
    $end            = $_POST['end-date'];
	$paragraph	    = $_POST['paragraph'];

    $modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$email_2	= $_POST['vacancy-email-2'];
	$contact	= $_POST['vacancy-contact'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title           = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
    //VALIDATION
	$v = new formValidation();
	$v->validateString($title, 'Vacancy Title', 3, 200);
    $v->validateDropDown($owner, 'Owner of the Vacancy');
    $v->validateDropDown($type, 'Type of Vacancy');
    $v->validateDropDown($city, 'City');
    $v->validateDropDown($category, 'Vacancy Category');
    $v->validateDropDown($salary, 'Salary');

    //IF EMAIL IS SUPPLIED
    if($email != '' && $email != ' '){
        $v->validateEmailAddress($email, 'Vacancy Email');
    }

    // IF APPLICATION FORM HAS BEEN CHECKED
    if($application != '' && $application != ' ' && $application != 0){
        $v->validateTags($application, 'Show Application Form');
    }

    $v->validateStartEndDates($start, $end, 'Vacancy Date');

    $v->validateText($paragraph, 'Description', 10);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($email_2 == '' && $contact == ''){

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($vacancyManager->checkVacancyChanges($title, $owner, $type, $city, $category, $salary, $email, $application, $start, $end, $paragraph, $vacID) == 'changed'){

                //REMOVE LINE BREAKS FROM PARAGRAPH
    			$paragraph = str_replace('\r\n', '', $paragraph);

				//UPDATE USER IN DATABASE
				$vacancyManager->updateVacancy($title, $owner, $type, $city, $category, $salary, $email, $application, $start, $end, $paragraph, $modifiedBy, $modifiedDate, $modifiedNumber, $vacID);

                //REDIRECT USER
                header("Location: ".$cms_root."vacancy-manager/index.php?message=2");
            	exit;

			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."vacancy-manager/");
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
$newWidth		= 300;
$newHeight		= 300;

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

	//REDIRECT TO CONTENT MANAGER PAGE
	header("Location: ".$cms_root."vacancy-manager/index.php?message=".$message);
    exit;
}
###################################################################
?>
