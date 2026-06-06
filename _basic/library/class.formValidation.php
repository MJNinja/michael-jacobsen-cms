<?php
#######################################################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 03.04.2015
#######################################################################################################
# Validate String								validateString($value, $postName, $min, $max);
# Validate Text									validateText($value, $postName, $min);
# Validate Email Address						validateEmailAddress($value, $postName);
# Validate Drop Down							validateDropDown($value, $postName);
# Validate Image								validateImage($inputField, $postName);
# Validate Document								validateDocument($docInputField, $postName);
# Validate CV									validateCV($docInputField, $postName);
# Validate Money								validateMoney($value, $postName);
# Validate Start and end Dates					validateStartEndDates($startDate, $endDate, $postName);
# Validate Date									validateDate($value, $postName);
# Validate Time									validateTime($value, $postName);
# Validate Video Link (YouTube & Vimeo)			validateVideoLink($value, $postName);
# Validate Password								validatePassword($value, $postName);
# Validate Contact Numbers						validateContactNumbers($value, $postName);
# Validate Number								validateNumbers($value, $postName);
# Validate Number Limit							validateNumbersLimit($value, $postName, $min, $max);
# Validate Gender								validateGender($value, $postName);
# Validate Tags									validateTags($value, $postName);
# Validate Link									validateLink($value, $postname);
#######################################################################################################

class formValidation{

	//VALIDATE STRING LENGTH
	function validateString($value, $postName, $min, $max){

		//CHECK IF A STRING HAS BEEN SUPPLIED
		if(strlen($value) == 0){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//CHECK IF STRING IS SMALLER THAN THE MINIMUM SIZE
		elseif(strlen($value) < $min){
			$this->setError($postName, '<b>'.$postName.'</b> should be atleast <b>'.$min.' characters</b> long.');
		}
		//CHECK IF STRING IS LARGER THAN THE MAXIMUM SIZE
		elseif(strlen($value) > $max){
			$this->setError($postName, '<b>'.$postName.'</b> shouldn\'t be larger than <b>'.$max.' characters</b>.');
		}

	}

	//VALIDATE TEXT LENGTH
	function validateText($value, $postName, $min){

		//CHECK IF A TEXT HAS BEEN SUPPLIED
		if(strlen($value) == 0){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//CHECK IF TEXT IS LARGER THAN THE MAXIMUM SIZE
		elseif(strlen($value) < $min){
			$this->setError($postName, '<b>'.$postName.'</b> should be atleast <b>'.$min.' characters</b> long.');
		}

	}

	//VALIDE MULTIPLE VIDEOS
	function validateMultipleVideos($value, $postName){
		//CHECK IF SYNTAX OF VALUE IS CORRENT
		/*if(!preg_match('/[a-zA-Z0-9]+[,]+[a-zA-Z0-9]/', $value)){
			$this->setError($postName, "The supplied video information has a mistake. Please make sure it is has no special characters <b>(!@#$%^&*)</b> and is in the following format: <b>Video Tile, Video Link</b>");
		}*/

		if(!preg_match('/[,]+[a-zA-Z0-9]/', $value)){
			$this->setError($postName, "The supplied video information has a mistake. Please make sure it is has no special characters <b>(!@#$%^&*)</b> and is in the following format: <b>Video Tile, Video Link</b>");
		}
	}

	//VALIDATE EMAIL ADDRESS
	function validateEmailAddress($value, $postName){
		//TURN VALUE INTO ARRAY
		$emailArray = explode("@", $value);

		//CHECK IF AN EMAIL ADDRESS HAS BEEN SUPPLIED
		if(strlen($value) == 0) {
			$this->setError($postName, "Please enter an <b>".$postName."</b>");
		}
		//CHECK IF EMAIL IS IN THE CORRECT FORMAT
		elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			$this->setError($postName, "Please enter a <b>Valid Email Address</b>");
		}
		/*//CHECK IF EMAIL IS IN THE CORRECT FORMAT
		elseif(!preg_match('/^[^0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,6}$/', $value)) {
			$this->setError($postName, "Please enter a <b>Valid Email Address</b>");
		}*/
		/*//CHECK IF EMAIL DOMAIN IS VAILD
		elseif(!checkdnsrr($emailArray[1], 'MX')){
			$this->setError($postName, "Please enter a <b>Valid Email Address</b>");
		}*/

	}

	//VALIDATE DROP DOWN
	function validateDropDown($value, $postName){
		if($value == 0){
			$this->setError($postName, "A <b>".$postName."</b> has to be selected.");
		}
	}

	//VALIDATE TAGS
	function validateTags($value, $postName){
		if($value == ''){
			$this->setError($postName, "A <b>".$postName."</b> has to be selected.");
		}
	}

	//VALIDATE LINKS
	function validateLink($value, $postname){
		//THE REGULAR EXPRESION FILTER
		$reg_exUrl = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/";

		// CHECK IF URL IS IN THE CORRECT FORMAT
		if(!preg_match($reg_exUrl, $value)) {
			$this->setError($postname, "The <b>".$postname."</b> is not a valid url. Please make sure to add the www");
		}
	}

	//VALIDATE MONEY
	function validateMoney($value, $postName){

		//CHECK IF FIELD IS NOT EMPTY OR 0
		if($value == "" || $value == 0){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}

		//CHECK IF STRING HAS DECIMAL
		if(strpos($value,".") == false){
			$this->setError($postName, '<b>'.$postName.'</b> must consist of a decimal.');
		}

		//REMOVE DECIMAL AND SPACES
		$newPostVal			= str_replace(".","",$value);
		$newPostVal2		= str_replace(" ","",$value);

		//CHECK IF THE REMAINDER OF THE STRING HAS ONLY NUMBERS
		if(!is_numeric($newPostVal2)){
			$this->setError($postName, '<b>'.$postName.'</b> must consist only of numbers.');
		}

	}

	//VALIDATE DATE
	function validateDate($value, $postName){

		//COVERT DATE
		$convertedDate = date("Y-m-d", strtotime($value));

		//CHECK IF A DATE HAS BEEN SUPPLIED
		if($value == '' || $value == ' '){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//CHECK IF DATE FORMAT IS IN THE CORRECT
		elseif($convertedDate == '1970-01-01'){
			$this->setError($postName, "There is a problem with the supplied date.");
		}

	}

	//VALIDATE TIME
	function validateTime($value, $postName){
		//EXPOLDE TIME VALUE
		$timeArray = explode(':', $value);

		//REMOVE : FROM STRING
		$search		= array(':', ' ');
		$replace	= array('', '');
		$time = str_replace($search, $replace, $value);

		//CHECK IF A TIME HAS BEEN SUPPLIED
		if($value == '' || $value == ' '){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//CHECK THAT TIME IS ONLY MADE OF NUMBER
		elseif(!is_numeric($time)){
			$this->setError($postName, "<b>".$postName."</b> may only consit out of numbers in the follow format HH:mm. Example:".date('H:i'));
		}
		//CHECK IF TIME IS IN THE CORRECT FORMAT
		elseif($timeArray[0] == '' || $timeArray[1] == ''){
			$this->setError($postName, "<b>".$postName."</b> has to be in time format as follows: HH:mm. Example:".date('H:i'));
		}
		//CHECK THAT TIME HAS THE CORRECT NUMBERS
		else{
			//CHECK THAT HOURS ARE BETWEEN 0 AND 23
			if ($timeArray[0] < 0 || $timeArray[0] > 23) {
				$this->setError($postName, "Hours have to be between 0 and 23.");
			}
			//CHECK THAT MINUTES ARE BETWEEN 0 AND 59
			if ($timeArray[1] < 0 || $timeArray[1] > 59) {
				$this->setError($postName, "Minutes have to be between 0 and 59.");
			}
		}
	}

	//VALIDATE START AND END DATES
	function validateStartEndDates($startDate, $endDate, $postName){

		//CONVERT TO CORRECT DATE FORMAT
		$convertedStartDate = date("Y-m-d", strtotime($startDate));
		$convertedEndDate = date("Y-m-d", strtotime($endDate));

		//CHECK IF END DATE HAS BEEN SUPPLIED BUT NO START DATE
		if($endDate != '' && $startDate == ''){
			$this->setError($postName, "A start date is required.");
		}
		//CHECK IF START DATE HAS BEEN SUPPLIED
		elseif($startDate == '' || $value == ' '){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//WHEN START DATE HAS BEEN SUPPLIED
		elseif($startDate != ''){
			//CHECK IF START DATE FORMAT IS CORRECT
			if($convertedStartDate == '1970-01-01')
			{
				$this->setError($postName, "There is a problem with the supplied start date.");
			}
			//CHECK IF END DATE HAS BEEN SUPPLIED
			elseif($endDate != ''){
				//CHECK IF END DATE FORMAT IS CORRECT
				if($convertedEndDate == '1970-01-01')
				{
					$this->setError($postName, "There is a problem with the supplied end date.");
				}
				//IF START DATE IS SMALLER THAN END DATE
				elseif($startDate > $endDate){
					$this->setError($postName, "End date is smaller than the start date.");
				}

			}
		}

	}

	//VALIDATE VIDEO LINK
	function validateVideoLink($value, $postName){

		//CHECK IF VIDEO HAS BEEN SUPPLIED
		if($value == '' || $value == ' '){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//CHECK IF THE VIDEO LINK IS FROM YOUTUBE
		elseif(strpos($value,'youtube') !== false){

			//CHECK IF YOUTUBE VIDEO IS IN THE CORRECT FORMAT
			if (strpos($value,'https://www.youtube.com/watch?v=') === false) {
				$this->setError($postName, "There is a problem with your YouTube link.");
			}

		}
		//CHECK IF THE VIDEO LINK IS FROM VIMEO
		elseif(strpos($value,'vimeo') !== false){

			//CHECK IF VIMEO VIDEO IS IN THE CORRECT FORMAT
			if (strpos($value,'https://vimeo.com/') === false) {
				$this->setError($postName, "There is a problem with your Vimeo link.");
			}

		}
		//IF VIDEO LINK IS NOT YOUTUBE OR VIMEO
		else{
			$this->setError($postName, "The video has to be from either YouTube or Vimeo.");
		}

	}

	//VALIDATE PASSWORD
	function validatePassword($value, $postName, $min){

		//CHECK IF PASSWORD HAS BEEN SUPPLIED
		if($value == '' || $value == ' '){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//CHECK IF PASSWORD IS SMALLER THAN MIN REQUIRED
		elseif(strlen($value) < $min){
			$this->setError($postName, '<b>'.$postName.'</b> should be atleast <b>'.$min.' characters</b> long.');
		}
		//CHECK IF PASSWORD HAS ALL REQUIRE CHARACTERS
		elseif (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $value))
		{
			$this->setError($postName, '<b>'.$postName.'</b> has to consits atleast of <b>One Number</b>, <b>One Lowercase Character</b> and <b>One Uppercase Character</b>.');
		}

	}

	//VALIDATE CELL NUMBER
	function validateContactNumbers($value, $postName){

		//STR_REPLACE ARRAYS
		$search = array('+', '(', ')' , ' ');
		$replace = array('');

		//REPLACE VALUES INSIDE OF STRING
		$newValue = str_replace($search, $replace, $value);

		 //CHECK IF CELL HAS BEEN SUPPLIED
		 if($value == ''){
			 $this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		 }
		 //CHECK LENGTH OF CELL NUMBER
		 elseif(strlen($value) < $length){
			 $this->setError($postName, '<b>'.$postName.'</b> has to be at least '.$length.' numbers long.');
		 }
		 //CHECK IF VALUE ONLY CONSISTS OF NUMBERS
		 elseif(!ctype_digit($newValue)){
			 $this->setError($postName, '<b>'.$postName.'</b> has to consist only of numbers.');
		 }
	}

	//VALIDATE IMAGE
	function validateImage($inputField, $postName){
		//GET FILE INFORMATION
		$fileName		= $_FILES[$inputField]["name"];			// FILE NAME
		$fileType		= $_FILES[$inputField]["type"];			// FILE TYPE
		$fileType		= explode("/",$fileType);				// FILE TYPE
		$fileType		= $fileType[1];							// FILE TYPE
		$fileSize		= $_FILES[$inputField]["size"] / 1024;	// FILE SIZE IN KB
		$fileStored		= $_FILES[$inputField]["tmp_name"];		// FILE TEMP STORAGE SPACE

		//CHECK IF A FILE HAS BEEN FOUND
		if($fileStored != ''){
			list($width,$height)= getimagesize($fileStored);
		}
		//CHECK IF FILE HAS BEEN UPLOADED
		if($fileName == ""){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//IF A FILE HAS BEEN UPLOADED
		else{

			//CHECK IF FILE IS BIGGER THAN ALLOWED WIDTH
			if($width > 5000){
				$this->setError($postName, "Uploaded image width is: <b>".$width."</b> pixels. All images should be smaller than <b>4800 pixels wide.</b>");
			}
			//CHECK IF FILE IS IN THE CORRECT FORMAT
			if($fileType != "jpeg" && $fileType != "jpg" && $fileType != "JPEG" && $fileType != "JPG" && $fileType != "png" && $fileType != "PNG" ){
				$this->setError($postName, "Only <b>.jpg</b> images are allowed no <b>.'".$fileType."'</b> files are allowed.");
			}
			//CHECK IF FILE IS BIGGER THAN ALLOWED SIZE
			if($fileSize > 5120){
				$this->setError($postName, "The image uploaded was: <b>".$fileSize." kb</b>, the image needs to be smaller than <b>4096 kb</b>.");
			}
		}
	}

	//VALIDATE DOCUMENT
	function validateDocument($docInputField, $postName){
		//GET FILE INFORMATION
		$fileName		= $_FILES[$docInputField]["name"];			// FILE NAME
		$fileType		= $_FILES[$docInputField]["type"];			// FILE TYPE
		$fileType		= explode("/",$fileType);					// FILE TYPE
		$fileType		= $fileType[1];								// FILE TYPE
		$fileSize		= $_FILES[$docInputField]["size"] / 1024;	// FILE SIZE IN KB
		$fileStored		= $_FILES[$docInputField]["tmp_name"];		// FILE TEMP STORAGE SPACE

		//CHECK IF FILE HAS BEEN UPLOADED
		if($fileName == ""){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//IF A FILE HAS BEEN UPLOADED
		else{
			//CHECK IF FILE IS IN THE CORRECT FORMAT
			if($fileType != 'pdf'){
				$this->setError($postName, "The document you uploaded was: <b>".$fileType."</b>, only <b>.pdf</b> files allowed.");
			}
			//CHECK IF FILE IS IN BIGGER THAN ALLOWED SIZE
			if($fileSize > 5120 ){
				$this->setError($postName, "The document uploaded was: <b>".$fileSize." kb</b>, the document has to be smaller than <b>5120 kb</b>.");
			}
		}

	}

	//VALIDATE CV
	function validateCV($docInputField, $postName){
		//GET FILE INFORMATION
		$fileName		= $_FILES[$docInputField]["name"];			// FILE NAME
		$fileType		= $_FILES[$docInputField]["type"];			// FILE TYPE
		$fileType		= explode("/",$fileType);					// FILE TYPE
		$fileType		= $fileType[1];								// FILE TYPE
		$fileSize		= $_FILES[$docInputField]["size"] / 1024;	// FILE SIZE IN KB
		$fileStored		= $_FILES[$docInputField]["tmp_name"];		// FILE TEMP STORAGE SPACE

		//CHECK IF FILE HAS BEEN UPLOADED
		if($fileName == ""){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//IF A FILE HAS BEEN UPLOADED
		else{
			//CHECK IF FILE IS IN THE CORRECT FORMAT
			if($fileType != 'pdf' && $fileType != 'doc' && $fileType != 'docx'){
				$this->setError($postName, "The document you uploaded was: <b>".$fileType."</b>, only <b>.pdf</b>, <b>.doc</b> and <b>.docx</b> files allowed.");
			}
			//CHECK IF FILE IS IN BIGGER THAN ALLOWED SIZE
			if($fileSize > 5120 ){
				$this->setError($postName, "The document uploaded was: <b>".$fileSize." kb</b>, the document has to be smaller than <b>5120kb</b>.");
			}
		}


	}

	//VALIDATE ZIP FILE
	function validateZipFile($zipInputField, $postName){
		//GET FILE INFORMATION
		$fileName		= $_FILES[$zipInputField]["name"];				// FILE NAME
		$fileType 		= substr($fileName, strrpos($fileName, ".")+1); 	// FILE TYPE
		$fileSize		= $_FILES[$zipInputField]["size"] / 1024;		// FILE SIZE IN KB
		$fileStored		= $_FILES[$zipInputField]["tmp_name"];			// FILE TEMP STORAGE SPACE

		//CHECK IF FILE HAS BEEN UPLOADED
		if($fileName == ""){
			$this->setError($postName, '<b>'.$postName.'</b> is a required field.');
		}
		//IF A FILE HAS BEEN UPLOADED
		else{
			//CHECK IF FILE IS IN THE CORRECT FORMAT
			if($fileType != 'zip'){
				$this->setError($postName, "The document you uploaded was: <b>".$fileType."</b>, only <b>.zip</b> files allowed.");
			}
			//CHECK IF FILE IS IN BIGGER THAN ALLOWED SIZE
			if($fileSize > 10240 ){
				$this->setError($postName, "The document uploaded was: <b>".$fileSize." kb</b>, the document has to be smaller than <b>10240 kb</b>.");
			}
		}

	}

	//VALIDATE NUMBER
	function validateNumbers($value, $postName){

		//CHECK THAT VALUE ONLY HAS NUMBERS
		if(!ctype_digit($value)){
			$this->setError($postName, "<b>".$postName."</b> must consist of numbers with no spaces.");
		}
	}

	//VALIDATE NUMBER LIMIT
	function validateNumbersLimit($value, $postName, $min, $max){

		//CHECK THAT VALUE ONLY HAS NUMBERS
		if(!ctype_digit($value)){
			$this->setError($postName, "<b>".$postName."</b> must consist of numbers with no spaces.");
		}
		// CHECK IF NUMBER IS BETWEEN VALUES
		elseif($value < $min || $value > $max){
			$this->setError($postName, "<b>".$postName."</b> must be between ".$min." and ".$max.".");
		}
	}

	//VALIDATE GENDER
	function validateGender($value, $postName){
		if($value == '' || $value == ' ') {
			//CHECK THAT A GENDER HAS BEEN SELECTED
			$this->setError($postName, "Please select a <b>".$postName."</b>");
		}
	}

	###################################################################
	## ERROR HANDLING
	###################################################################
	//SET AN ERROR MESSAGES
	function setError($element, $message) {
		$this->errors[$element] = $message;
	}

	//RETURN WHETHER THE FORM HAS ERRORS
	function hasErrors() {
		if(count((array)$this->errors) > 0) {
			return true;
		} else {
			return false;
		}
	}

	//DISPLAY THE ERRORS AS AN HTML UN-ORDERED LIST
	function showErrors() {
		$errorList = '<ul class="errors">';
		foreach((array)$this->errors as $value) {
			$errorList.= '<li>'.$value.'</li>';
		}
		$errorList.= '</ul>';
		return $errorList;
	}

	//TELL HOW MANY ERROR HAVE BEEN FOUND
	function errorMessage() {
		if(count((array)$this->errors) > 1) {
			$message = "There were ".count((array)$this->errors)." errors sending your message!\n";
		} else {
			$message = "There was an error sending your message!";
		}
		return $message;
	}

	//TELL HOW MANY ERROR HAVE BEEN FOUND
	function errorSigninMessage() {
		if(count((array)$this->errors) > 1) {
			$message = "There were ".count((array)$this->errors)." errors signing you in!\n";
		} else {
			$message = "There was an error signing you in!";
		}
		return $message;
	}

	//TELL HOW MANY ERROR HAVE BEEN FOUND
	function errorVerifyAccountMessage() {
		if(count((array)$this->errors) > 1) {
			$message = "There were ".count((array)$this->errors)." errors verifying your account!\n";
		} else {
			$message = "There was an error verifying your account!";
		}
		return $message;
	}

	//TELL HOW MANY ERROR HAVE BEEN FOUND
	function errorCMSMessage() {
		if(count((array)$this->errors) > 1) {
			$message = "There were ".count((array)$this->errors)." errors!\n";
		} else {
			$message = "There was an error!";
		}
		return $message;
	}

	//TELL HOW MANY ERROR HAVE BEEN FOUND
	function errorCommentMessage() {
		if(count((array)$this->errors) > 1) {
			$message = "There were ".count((array)$this->errors)." errors submitting your comment!\n";
		} else {
			$message = "There was an error submitting your comment!";
		}
		return $message;
	}
}


?>
