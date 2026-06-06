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
require_once("../library/class.systemConfig.php");
require_once("../library/ajax.library.php");
require_once("../library/class.formValidation.php");

//SET VARIABLE
$fbcn   = 'fbcd';

//GET VARIABLE
if(isset($_GET['gfbcd'])){$gfbcd = $_GET['gfbcd'];}else{$gfbcd = $_POST['gfbcd'];}
if(isset($_GET['cfbcd'])){$cfbcd = $_GET['cfbcd'];}else{$cfbcd = $_POST['cfbcd'];}
if(isset($_GET['ufbcd'])){$ufbcd = $_GET['ufbcd'];}else{$ufbcd = $_POST['ufbcd'];}
if(isset($_GET['feedback_submit'])){$feedback_submit = $_GET['feedback_submit'];}else{$feedback_submit = $_POST['feedback_submit'];}

//GET FEEDBACK COOKIE INFO
if($gfbcd == 1){
    echo $ajaxLibrary->getFeedbackCookieValue($fbcn);
}

//CREATE FEEDBACK COOKIE
if($cfbcd == 1){
    echo $ajaxLibrary->createFeedbackCookie($fbcn);
}

//UPDATE FEEDBACK COOKIE
if($ufbcd == 1){
    echo $ajaxLibrary->updateFeedbackCookie($fbcn);
}

//SUBMIT FORM
if($feedback_submit){
    //CONNECT TO DATABASE
	$connector 	= new DbConnector();

    //GET VARIABLES
    $visit              = $connector->escape($_POST['visit']);
    $reasonVisit        = $connector->escape($_POST['reasonVisit']);
    $findWhatNeeded     = $connector->escape($_POST['findWhatNeeded']);
    $whatLookingFor     = $connector->escape($_POST['whatLookingFor']);
    $easyFindInfo       = $connector->escape($_POST['easyFindInfo']);
    $professional       = $connector->escape($_POST['professional']);
    $informative        = $connector->escape($_POST['informative']);
    $visuallyPleasing   = $connector->escape($_POST['visuallyPleasing']);
    $visitAgain         = $connector->escape($_POST['visitAgain']);
    $comments           = $connector->escape($_POST['comments']);

    $form_id           = $connector->escape($_POST['form']);
    $topic_id          = 0;

    //HONEY POTS
    $fullName           = $_POST['fullName'];
    $email              = $_POST['email'];

    //FORM VALIDATION
    $v = new formValidation();
	$v->validateDropDown($visit, 'First Time Visit');
    $v->validateText($reasonVisit, 'Reason for Visit', 10);
    $v->validateDropDown($findWhatNeeded, 'Did you find what you needed?');

    if($whatLookingFor != '' && $whatLookingFor != ' '){
        $v->validateText($whatLookingFor, 'Please tell us what information you were looking for', 10);
    }

    $v->validateDropDown($easyFindInfo, 'How easy it is to find information on the site');
    $v->validateDropDown($professional, 'Overall professional impression of the site');
    $v->validateDropDown($informative, 'Overall information impression of the site');
    $v->validateDropDown($visuallyPleasing, 'Overall visual pleasing impression of the site');
    $v->validateDropDown($visitAgain, 'Likelihood that you will visit the website again');

    if($comments != '' && $comments != ' '){
        $v->validateText($comments, 'Comment', 10);
    }

    if(!$v->hasErrors()) {

        if($fullName == '' && $email == ''){
            //SCAN CONTENT
            $reasonVisit = $ajaxLibrary->contentScanAlteration($reasonVisit);
            $whatLookingFor = $ajaxLibrary->contentScanAlteration($whatLookingFor);
            $comments = $ajaxLibrary->contentScanAlteration($comments);

            //INSERT INTO DATABASE
            $ajaxLibrary->saveFeedback($visit, $reasonVisit, $findWhatNeeded, $whatLookingFor, $easyFindInfo, $professional, $informative, $visuallyPleasing, $visitAgain, $comments);

            //VISIT VALUE
            if($visit == 1){
                $visitValue = 'Yes';
            }elseif($visit == 2){
                $visitValue = 'No';
            }

            //FIND WHAT NEEDED VALUE
            if($findWhatNeeded == 1){
                $findWhatNeededValue = 'Yes, all of it';
            }elseif($findWhatNeeded == 2){
                $findWhatNeededValue = 'Yes, some of it';
            }elseif($findWhatNeeded == 3){
                $findWhatNeededValue = 'No, none of it ';
            }

            //EASY FIND VALUE
            if($easyFindInfo == 5){
                $easyFindInfoValue = 'Very Easy';
            }elseif($easyFindInfo == 4){
                $easyFindInfoValue = 'Easy';
            }elseif($easyFindInfo == 3){
                $easyFindInfoValue = 'Average';
            }elseif($easyFindInfo == 2){
                $easyFindInfoValue = 'Difficult';
            }elseif($easyFindInfo == 1){
                $easyFindInfoValue = 'Very Difficult';
            }

            //PROFESSIONAL VALUE
            if($professional == 1){
                $professionalValue = 'Below Expectations';
            }elseif($professional == 2){
                $professionalValue = 'Meets Expectations';
            }elseif($professional == 3){
                $professionalValue = 'Exceeds Expectations';
            }

            //INFORMATIVE VALUE
            if($informative == 1){
                $informativeValue = 'Below Expectations';
            }elseif($informative == 2){
                $informativeValue = 'Meets Expectations';
            }elseif($informative == 3){
                $informativeValue = 'Exceeds Expectations';
            }

            //VISUALLY PLEASING VALUE
            if($visuallyPleasing == 1){
                $visuallyPleasingValue = 'Below Expectations';
            }elseif($visuallyPleasing == 2){
                $visuallyPleasingValue = 'Meets Expectations';
            }elseif($visuallyPleasing == 3){
                $visuallyPleasingValue = 'Exceeds Expectations';
            }

            //VISIT AGAIN VALUE
            if($visitAgain == 5){
                $visitAgainValue = 'Extremely likely';
            }elseif($visitAgain == 4){
                $visitAgainValue = 'Very likely';
            }elseif($visitAgain == 3){
                $visitAgainValue = 'Moderately likely';
            }elseif($visitAgain == 2){
                $visitAgainValue = 'Slightly likely';
            }elseif($visitAgain == 1){
                $visitAgainValue = 'Not at all likely';
            }

            //SEND FEEDBACK
			$ajaxLibrary->sendFeedbackEmail($visitValue, $reasonVisit, $findWhatNeededValue, $whatLookingFor, $easyFindInfoValue, $professionalValue, $informativeValue, $visuallyPleasingValue, $visitAgainValue, $comments, $form_id, $topic_id, $web_root);

            //RETURN SUCCESS
            echo 'success';
        }

    }else{
        //SET THE NUMBER OF ERROR MESSAGES
		$message_text = $v->errorFeedbackMessage();

		//STORE THE ERROR LIST IN A VARIABLE
		$errors = $v->showErrors();

        //RETURN ERROR
        echo 'error#@#<div class="formMessageError">
            <div class="message">'.$message_text.'</div>
            <div class="errorMessage">'.$errors.'</div>
        </div>';
    }
}
?>
