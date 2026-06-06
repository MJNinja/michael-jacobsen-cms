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

class feedbackManager extends systemConfig{
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
            case 1: $displayMessage = 'The selected Feedback Period has successfully been updated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET FEEDBACK INFORMATION
    //#################################################################
	function getFeedbackInfo($field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM feedback", array());
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET TOTAL FEEDBACK RECEIVED
    //#################################################################
	function getTotalFeedback(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET TOTAL STAFF MEMBERS
		$result = $connector->query("SELECT * FROM form_feedback WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // CHECK IF FEEDBACK INFO HAS BEEN CHANGED
    //#################################################################
	function checkFeedbackPeriodChanges($startDate, $endDate){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM feedback WHERE startDate = ? AND endDate = ?", array($startDate, $endDate));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // UPDATE FEEDBACK PERIOD
    //#################################################################
	function updateFeedbackPeriod($startDate, $endDate, $modifiedDate, $modifiedBy, $modifiedNumber){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP TAGS
		$startDate      = strip_tags($startDate);
        $endDate        = strip_tags($endDate);

		//UPDATE STAFF MEMBER
		$update = $connector->query("UPDATE feedback SET
									startDate = ?,
                                    endDate = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?",
									array($startDate, $endDate, $modifiedBy, $modifiedDate, $modifiedNumber));

	}

    //#################################################################
	// GET DEFAULT GRAPH INFO
    //#################################################################
	function getDefaultGraphInfo(){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

        //DEFAULT VARIABLES
        $visit1Total    = 0;
        $visit2Total    = 0;
        $findWhatNeeded1Total   = 0;
        $findWhatNeeded2Total   = 0;
        $findWhatNeeded3Total   = 0;
        $easyFindInfo1Total = 0;
        $easyFindInfo2Total = 0;
        $easyFindInfo3Total = 0;
        $easyFindInfo4Total = 0;
        $easyFindInfo5Total = 0;
        $professional1Total = 0;
        $professional2Total = 0;
        $professional3Total = 0;
        $informative1Total  = 0;
        $informative2Total  = 0;
        $informative3Total  = 0;
        $visuallyPleasing1Total = 0;
        $visuallyPleasing2Total = 0;
        $visuallyPleasing3Total = 0;
        $visitAgain1Total   = 0;
        $visitAgain2Total   = 0;
        $visitAgain3Total   = 0;
        $visitAgain4Total   = 0;
        $visitAgain5Total   = 0;

        //GET ALL FEEDBACK INFO
        $result = $connector->query("SELECT visit, findWhatNeeded, easyFindInfo, professional, informative, visuallyPleasing, visitAgain FROM form_feedback WHERE deletedBy = ? ORDER BY createdDate DESC", array(0));
        $total  = $connector->numResults($result);

        //IF TOTAL ISN'T ZERO (0)
        if($total != 0){
            while($row  = $connector->fetchArray($result)){
                //SET VARIABLES
                $visit              = $row['visit'];
                $findWhatNeeded     = $row['findWhatNeeded'];
                $easyFindInfo       = $row['easyFindInfo'];
                $professional       = $row['professional'];
                $informative        = $row['informative'];
                $visuallyPleasing   = $row['visuallyPleasing'];
                $visitAgain         = $row['visitAgain'];

                //SET VISIT VALUES
                if($visit == 1){
                    $visit1Total++;
                }elseif($visit == 2){
                    $visit2Total++;
                }

                //SET FIND WHAT NEED VALUES
                if($findWhatNeeded == 1){
                    $findWhatNeeded1Total++;
                }elseif($findWhatNeeded == 2){
                    $findWhatNeeded2Total++;
                }elseif($findWhatNeeded == 3){
                    $findWhatNeeded3Total++;
                }

                //SET EASY FIND VALUES
                if($easyFindInfo == 1){
                    $easyFindInfo1Total++;
                }elseif($easyFindInfo == 2){
                    $easyFindInfo2Total++;
                }elseif($easyFindInfo == 3){
                    $easyFindInfo3Total++;
                }elseif($easyFindInfo == 4){
                    $easyFindInfo4Total++;
                }elseif($easyFindInfo == 5){
                    $easyFindInfo5Total++;
                }

                //SET PROFESSIONAL VALUES
                if($professional == 1){
                    $professional1Total++;
                }elseif($professional == 2){
                    $professional2Total++;
                }elseif($professional == 3){
                    $professional3Total++;
                }

                //SET INFORMATIVE VALUES
                if($informative == 1){
                    $informative1Total++;
                }elseif($informative == 2){
                    $informative2Total++;
                }elseif($informative == 3){
                    $informative3Total++;
                }

                //SET VISUALLY PLEASING VALUES
                if($visuallyPleasing == 1){
                    $visuallyPleasing1Total++;
                }elseif($visuallyPleasing == 2){
                    $visuallyPleasing2Total++;
                }elseif($visuallyPleasing == 3){
                    $visuallyPleasing3Total++;
                }

                //SET VISIT AGAIN VALUES
                if($visitAgain == 1){
                    $visitAgain1Total++;
                }elseif($visitAgain == 2){
                    $visitAgain2Total++;
                }elseif($visitAgain == 3){
                    $visitAgain3Total++;
                }elseif($visitAgain == 4){
                    $visitAgain4Total++;
                }elseif($visitAgain == 5){
                    $visitAgain5Total++;
                }

            }
        }

        //RETURN INFO
        return $visit1Total.','.$visit2Total.'::'.$findWhatNeeded1Total.','.$findWhatNeeded2Total.','.$findWhatNeeded3Total.'::'.$easyFindInfo5Total.','.$easyFindInfo4Total.','.$easyFindInfo3Total.','.$easyFindInfo2Total.','.$easyFindInfo1Total.'::'.$professional3Total.','.$professional2Total.','.$professional1Total.'::'.$informative3Total.','.$informative2Total.','.$informative1Total.'::'.$visuallyPleasing3Total.','.$visuallyPleasing2Total.','.$visuallyPleasing1Total.'::'.$visitAgain5Total.','.$visitAgain4Total.','.$visitAgain3Total.','.$visitAgain2Total.','.$visitAgain1Total;
	}

    //#################################################################
    // GET FEEDBACK MESSAGES LIMITED
    //#################################################################
	function getFeedbackMessagesLimited($field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $search = array('<p>', '</p>', '\\');
        $replace = array('', '<br />', '');

        //CONVERT DATES
        $startDate  = date('Y-m-d H:i:s', strtotime($startDate));
        $endDate    = date('Y-m-d H:i:s', strtotime($endDate));

		//GET FEEDBACK MESSAGES
        $result = $connector->query("SELECT * FROM form_feedback WHERE deletedBy = ? ORDER BY createdDate DESC LIMIT 0,10", array(0));
        $total  = $connector->numResults($result);

        //CHECK IF RESULTS ARE AVAILABLE
        if($total != 0){
            while($row    = $connector->fetchArray($result)){
                //SET VARIABLES
                $message    = str_replace($search, $replace, $row[$field]);

                if($message != '' && $message !=' ' && $message != '<br />'){
                    //GENERATE OUTPUT
                    $txt.= '<li><span>'.$message.'</span></li>';
                }
            }


        }else{
            $txt = 'No message have been supplied for the time period.';
        }

        //RETURN OUTPUT
        return $txt;

	}

    //#################################################################
    // GET FEEDBACK MESSAGES ALL
    //#################################################################
	function getFeedbackMessagesAll($field, $limit){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $search = array('<p>', '</p>', '\\');
        $replace = array('', '<br />', '');

		//GET FEEDBACK MESSAGES
        $result = $connector->query("SELECT * FROM form_feedback WHERE deletedBy = ? AND $field != '' ORDER BY createdDate DESC LIMIT 0, $limit", array(0));
        $total  = $connector->numResults($result);

        //CHECK IF RESULTS ARE AVAILABLE
        if($total != 0){
            while($row    = $connector->fetchArray($result)){

                //SET VARIABLES
                $message    = str_replace($search, $replace, $row[$field]);

                if($message != '' && $message != ' ' && $message != '<br />'){
                    //GENERATE OUTPUT
                    $txt.= '<li><span>'.$message.'</span></li>';
                }

            }

        }else{
            $txt = 'No message have been supplied for the time period.';
        }

        //RETURN OUTPUT
        return $txt;

	}

}

//DEFINE CLASS
$feedbackManager = new feedbackManager();


//#################################################################
// UPDATE FEEDBACK PERIOD
//#################################################################
if(isset($_POST['update_feedback_period'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$startDate  = $_POST['feed-start-date'];
    $endDate    = $_POST['feedback-end-date'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $startDate      = $userLogin->specialCharactersToHTMLEntity($startDate);
    $endDate        = $userLogin->specialCharactersToHTMLEntity($endDate);

	//VALIDATION
    $v = new formValidation();
	$v->validateStartEndDates($startDate, $endDate, 'Feedback Form Period');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF CONTENT HAS BEEN CHANGED
		if($feedbackManager->checkFeedbackPeriodChanges($startDate, $endDate) == 'changed'){

			//UPDATE FEEDBACK PERIOD IN DATABASE
			$feedbackManager->updateFeedbackPeriod($startDate, $endDate, $modifiedDate, $modifiedBy, $modifiedNumber);

            //REDIRECT USER
            header("Location: ".$cms_root."feedback-manager/index.php?message=1");
    		exit;

		}
		//NO CONTENT HAS BEEN CHANGED
		else{

			//REDIRECT USER
			header("Location: ".$cms_root."feedback-manager/");
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
?>
