<?php
#######################################################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 18.04.2015
#######################################################################################################
require_once("class.systemConfig.php");
require_once("class.formValidation.php");
require_once("class.encryptDecrypt.php");

//CHECK IF USER IS LOGGED IN
if($_SESSION['cmsUser'] == ''){
	if($auth_page != 1){

		//UNSET ALL SESSIONS
		unset($_SESSION['cmsLogin']);
		unset($_SESSION['user_temp']);

		//REDIRECT TO SIGN IN FORM
		header("Location: ".$cms_root."auth.php");
		exit;
	}
}

//DESTROY DEFAULT SESSION
if(isset($_COOKIE['PHPSESSID'])){
	//DESTROY COOKIE
	setcookie("PHPSESSID", "", 1, "/", '', false, true);
}

//DESTROY $_SESSION['cmsLogin']
if($auth_page != 1){
	if($_SESSION['cmsLogin'] != ''){
		unset($_SESSION['cmsLogin']);
	}

	//MAX LIFE OF SESSION
	ini_set('session.gc_maxlifetime', 86400);
}

class userLogin extends systemConfig{
	//#################################################################
    // DO NOT CHANGE CODE BELOW
    //#################################################################
    function __construct(){}
    function __destruct(){unset($connector);}

	//#################################################################
    // GET USER INFORMATION
    //#################################################################
	function getUserInfo($userID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO FROM DATABASE
		$result = $connector->query("SELECT * FROM cms_users WHERE userID = ?",array($userID));
		$row	= $connector->fetchArray($result);

		//RETURN RESULT
		return $row[$field];
	}

	//#################################################################
    // CHECK IF USER HAS BEEN BLOCK
    //#################################################################
	function cmsUserBlocked($userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK IF USER HAS BEEN BLOCK
		$result = $connector->query("SELECT * FROM cms_users WHERE userID = ? AND blocked = '1'", array($userID));
		$total	= $connector->numResults($result);

		//IF USER HAS BEEN BLOCKED
		if($total != 0){
			//CHECK IF BLOCKED IS OLDER THAN 30 MINUTES
			$row = $connector->fetchArray($result);

			$blocked_date = $row['blocked_date'];
			$currenttime = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . "-30 minutes"));

			//STILL BLOCKED
			if($currenttime <= $blocked_date){
				return 'blocked';
			}
			//REMOVE BLOCKED INFORMATION
			else{
				//UPDATE USERS NOT BLOCKED
				$update = $connector->query("UPDATE cms_users SET
											login_attempts	= 0,
											blocked			= 0,
											blocked_date	= '0000-00-00 00:00:00'
											WHERE userID	= ?"
											, array($userID));
			}
		}
	}

	//#################################################################
	// SPECIAL CHARACTERS TO HTML ENTITY
	//#################################################################
	function specialCharactersToHTMLEntity($str){

		$search = array('<', '>', '€', '‘', '’', '“', '”', '–', '—', '¡', '¢','£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '«', '¬', '®', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã','ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ','Œ', 'œ', '‚', '„', '…', '™', '•', '˜', "'", '"', '&');

		$replace  = array('&lt;', '&gt;', '&euro;', '&lsquo;', '&rsquo;', '&ldquo;','&rdquo;', '&ndash;', '&mdash;', '&iexcl;','&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;','&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;','&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&OElig;', '&oelig;', '&sbquo;', '&bdquo;', '&hellip;', '&trade;', '&bull;', '&asymp;', "&#39;", '&quot;', '&amp;');

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
	// REMOVE HTML ENTITIES
	//#################################################################
	function removeHTMLEntity($str){

		$search  = array('&amp;', '&lt;', '&gt;', '&euro;', '&lsquo;', '&rsquo;', '&ldquo;','&rdquo;', '&ndash;', '&mdash;', '&iexcl;','&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;','&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;','&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&OElig;', '&oelig;', '&sbquo;', '&bdquo;', '&hellip;', '&trade;', '&bull;', '&asymp;', "&#39;", '&quot;');

		$replace = array('');

		//REPLACE VALUES
		$str = str_replace($search, $replace, $str);

		//RETURN FORMATED STRING
		return $str;
	}

	//#################################################################
    // CHECK IF USER HAS THE RIGHTS TO VIEW THIS MODULE
    //#################################################################
	function checkUserModuleRights($userType, $moduleID, $userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER TYPE
		$userType	= $this->getUserInfo($userID, 'userType');

		//CHECK USER TYPE
		if($userType != 0 && $userType != 1){
			//CHECK IF USER CAN VIEW THE SELECTED MODULE
			$result = $connector->query("SELECT userID FROM cms_users WHERE userID = ? AND userModuleRights LIKE ? AND userType != ? AND deletedBy = ? LIMIT 0,1", array($userID, '%,'.$moduleID.',%', '0', '0'));
			$total	= $connector->numResults($result);

			//CHECK IF USER HAS THE RIGHT
			if($total != 0){
				return 'yes';
			}
			//DOES NOT HAVE THE RIGHT
			else{
				return 'no';
			}
		}else{
			//RETURN THAT USER CAN VIEW THE MODULE
			return 'yes';
		}
	}
}

//DEFINE CLASS
$userLogin = new userLogin();

if(isset($_POST['submit-login'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();
	$encryptDecrypt = new encryptDecrypt();

	//DEFINE ENCRYPTION_KEY
	define("ENCRYPTION_KEY", "%@#!^&$*");

	$email 		= $_POST['email'];
	$password	= $_POST['password'];

	//HONEY POTS
	$email2		= $_POST['email2'];
	$password2	= $_POST['password2'];

	//GET IP ADDRESS
	if ($_SERVER['HTTP_CLIENT_IP']){
	  $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
	  $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif($_SERVER['HTTP_X_FORWARDED']){
	  $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}else if($_SERVER['HTTP_FORWARDED_FOR']){
	  $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}else if($_SERVER['HTTP_FORWARDED']){
	  $ipaddress = $_SERVER['HTTP_FORWARDED'];
	}else if($_SERVER['REMOTE_ADDR']){
	  $ipaddress = $_SERVER['REMOTE_ADDR'];
	}else{
	  $ipaddress = '';
	}

	//VALIDATION
	$v = new formValidation();
	$v->validateEmailAddress($email, 'Email');
	$v->validatePassword($password, 'Password', 8);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK THAT INFORMATION HAS BEEN SUBMITTED IN OVER 5 SECONDS
		$sessiontime = $_SESSION['cmsLogin'];
		$currenttime = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . "-5 seconds"));
		if($currenttime > $sessiontime){
			$timestamp = 1;
		}

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($email2 == '' && $password2 == '' && $timestamp == 1){

			//MD5 PASSWORD
			$password = md5($password);

			//DECRYPT EMAIL ADDRESS
            $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

			//CHECK IF USER IS INSIDE THE DATABASE
			$result = $connector->query("SELECT * FROM cms_users WHERE email = ? AND password = ? AND deletedBy = ?", array($encrypted_email, $password, '0'));
			$total	= $connector->numResults($result);

			//USER FOUND IN DATABASE
			if($total == 1){
				$row	= $connector->fetchArray($result);
				$userID	= $row['userID'];

				//CHECK IF USER IS BLOCKED
				if($userLogin->cmsUserBlocked($userID) != 'blocked'){

					//CHECK IF USER ALREADY SIGNED IN UNDER CURRENT IP ADDRESS
					$result2 = $connector->query("SELECT * FROM cms_user_ip_address WHERE userID = ? AND ipAddress = ?", array($userID, $ipaddress));
					$total2	 = $connector->numResults($result2);

					//IF USER DIDN'T SIGN IN UNDER CURRENT IP ADDRESS
					if($total2 != 1){

						//SET VARIABLES
						$auth = 1;
						$_SESSION['user_temp'] = $userID;

					}
					//ELSE REDIRECT TO DASHBOARD
					else{
						//SET USER SESSION
						$_SESSION['cmsUser'] = $userID;

						//UNSET SESSIONS
						unset($_SESSION['cmsLogin']);
						unset($_SESSION['user_temp']);

						//LOG USER IN
						header("Location:".$cms_root);
						exit;
					}

				}else{

					//SET VARIABLE
					$_SESSION['user_temp'] = $userID;

					//REDIRECT USER TO LOGIN
					header("Location:".$cms_root."auth.php");
					exit;
				}

			}else{

				//SET SIGN IN ERROR
				$error_message = 'There was an error signing you in!';
				$errors = 'Wrong Email/Password Combination.';
			}

		}

	}
	//ERRORS HAVE BEEN FOUND
	else{

		//SET ERROR MESSAGE
		$error_message = $v->errorSigninMessage();
		$errors = $v->showErrors();
	}

}

if(isset($_POST['submit-verify-account'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	$auth 		= 1;
	$number 	= $_POST['number'];

	//HONEY POTS
	$number2	= $_POST['number2'];

	//GET IP ADDRESS
	if ($_SERVER['HTTP_CLIENT_IP']){
	  $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
	  $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif($_SERVER['HTTP_X_FORWARDED']){
	  $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}else if($_SERVER['HTTP_FORWARDED_FOR']){
	  $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}else if($_SERVER['HTTP_FORWARDED']){
	  $ipaddress = $_SERVER['HTTP_FORWARDED'];
	}else if($_SERVER['REMOTE_ADDR']){
	  $ipaddress = $_SERVER['REMOTE_ADDR'];
	}else{
	  $ipaddress = '';
	}

	//VALIDATION
	$v = new formValidation();
	$v->validateContactNumbers($number, 'Contact Number');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($number2 == ''){

			//MD5 PASSWORD
			$password = md5($password);

			//CHECK IF USERS NUMBER IS INSIDE THE DATABASE
			$result = $connector->query("SELECT * FROM cms_users WHERE userID = ?", array($_SESSION['user_temp']));
			$row	= $connector->fetchArray($result);
			$contactNumber = $row['contactNumber'];
			$login_attempts = $row['login_attempts'];

			//IF NUMBER IS WRONG INCREMENT ATTEMPTS
			if($contactNumber != $number){

				//INCREMENT LOGIN ATTEMPTS
				$login_attempts = $login_attempts + 1;
				$attempts_left = 4 - $login_attempts;

				//IF NO MORE ATTEMPTA ARE LEFT
				if($attempts_left == 0){
					//UPDATE USER ATTEMPTS
					$update = $connector->query("UPDATE cms_users SET
												login_attempts	= ?,
												blocked			= ?,
												blocked_date	= ?
												WHERE userID	= ?"
												, array(0, 1, date('Y-m-d H:i:s'), $_SESSION['user_temp']));

					//REDIRECT TO LOGIN PAGE
					header("Location:".$cms_root."auth.php");
					exit;

				}else{

					//UPDATE USER ATTEMPTS
					$update = $connector->query("UPDATE cms_users SET
												login_attempts	= ?
												WHERE userID	= ?"
												, array($login_attempts, $_SESSION['user_temp']));

					//SET ERROR MESSAGE
					$error_message = 'There was an error verifying your account!';
					$errors = 'You have '.$attempts_left.' attempts left to verfiy your account';
				}

			}else{
				//INSERT INFO INTO DATABASE
				$insert	= $connector->query("INSERT INTO cms_user_ip_address (ipAddress, userID)
										 	VALUES (?, ?)"
											,array($ipaddress, $_SESSION['user_temp']));

				//SET USER SESSION
				$_SESSION['cmsUser'] = $_SESSION['user_temp'];

				//UNSET SESSIONS
				unset($_SESSION['cmsLogin']);
				unset($_SESSION['user_temp']);

				//REDIRECT USER TO DASHBOARD
				header("Location:".$cms_root);
				exit;
			}

		}

	}
	//ERRORS HAVE BEEN FOUND
	else{

		//SET ERROR MESSAGE
		$error_message = $v->errorVerifyAccountMessage();
		$errors = $v->showErrors();
	}

}
