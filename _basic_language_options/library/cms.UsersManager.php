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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("class.systemConfig.php");
require_once("class.formValidation.php");
require_once("class.encryptDecrypt.php");
require_once("phpmailer/src/Exception.php");
require_once("phpmailer/src/PHPMailer.php");
require_once("phpmailer/src/SMTP.php");
include("../inc/cms-owner-info-inc.php");

class userManager extends systemConfig{
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
            case 1: $displayMessage = 'A new CMS User has successfully been added.'; break;
            case 2: $displayMessage = 'The selected CMS User has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected CMS User has successfully been removed.'; break;
			case 4: $displayMessage = 'The selected CMS User has successfully been recovered.'; break;
			case 5: $displayMessage = 'The selected CMS User has successfully been reactivated and overwritten.'; break;
            case 6: $displayMessage = 'Your Profile has been updated.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

	//#################################################################
    // GET USER INFORMATION
    //#################################################################
	function getUserInfo($userID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE userID = ?", array($userID));
		$row	= $connector->fetchArray($result);

        //CHECK IF EMAIL IS REQUESTED
        if($field == 'email'){
            //SET VARIABLE
            $email = $row[$field];

            //DECRYPT EMAIL ADDRESS
            $decrypted_email = $encryptDecrypt->decrypt($email, ENCRYPTION_KEY);

            //RETURN VALUE
            return $decrypted_email;
        }else{
            //RETURN VAlUE
            return $row[$field];
        }

		//RETURN VAlUE
		return $row[$field];

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
    // GET TOTAL ACTIVES
    //#################################################################
	function getTotalActives(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL DELETES
    //#################################################################
	function getTotalDeletes(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE deletedBy != ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

	//#################################################################
    // GET TOTAL USERS
    //#################################################################
	function getTotalUsers(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_users", array());
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET USER ROLES
    //#################################################################
	function getUserRoles($currentRoleID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_roles ORDER BY roleType ASC", array());
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $roleID     = $row['roleID'];
            $roleType   = $row['roleType'];

            //GENERATE OUTPUT
            if($currentRoleID == $roleID){
                $txt.= '<option value="'.$roleID.'" selected="selected">'.$roleType.'</option>';
            }else{
                $txt.= '<option value="'.$roleID.'">'.$roleType.'</option>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET USER ROLE MODULES
    //#################################################################
	function getUserRoleModules($moduleArray, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';

        if(empty($moduleArray)){
            $moduleArray = array();
        }

		//GET USER INFO
		$result = $connector->query("SELECT cmsModuleID, cmsModuleIcon, cmsModuleName FROM cms_modules WHERE moduleRights = ? ORDER BY cmsModuleName ASC", array('1'));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $cmsModuleID    = $row['cmsModuleID'];
            $cmsModuleIcon  = $row['cmsModuleIcon'];
            $cmsModuleName  = $row['cmsModuleName'];

            //GENERATE OUTPUT
            if(in_array($cmsModuleID, $moduleArray)){
                $txt.= '<div class="user-module-holder">
                    <label>
                        <input type="checkbox" name="userSelectedRoles[]" value="'.$cmsModuleID.'" checked="checked"/>
                        <img src="'.$cms_root.'images/icons/'.$cmsModuleIcon.'" />
                        <span>'.$cmsModuleName.'</span>
                    </label>
                </div>';
            }else{
                $txt.= '<div class="user-module-holder">
                    <label>
                        <input type="checkbox" name="userSelectedRoles[]" value="'.$cmsModuleID.'" />
                        <img src="'.$cms_root.'images/icons/'.$cmsModuleIcon.'" />
                        <span>'.$cmsModuleName.'</span>
                    </label>
                </div>';
            }
        }

		//RETURN OUTPUT
		return $txt;
	}

	//#################################################################
    // CMS USER ARCHITECTURE
    //#################################################################
	function cmsUserArchitectureActive($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL NON-REMOVED USERS
		$result = $connector->query("SELECT * FROM cms_users WHERE deletedBy = ? ORDER BY name ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$userID		= $row['userID'];
			$name		= $row['name'];
			$surname	= $row['surname'];
			$deletedBy	= $row['deletedBy'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="active-account"></td>
				<td>'.$name.' '.$surname.'</td>
				<td align="center">';

				if($userID == 1){
					if($currentUser == 1){
						$txt.= '<a href="'.$cms_root.'cms-users-manager/edit-cms-user.php?userID='.$userID.'" title="Modify">Modify</a>';
					}else{
						$txt.= '-';
					}
				}else{
					$txt.= '<a href="'.$cms_root.'cms-users-manager/edit-cms-user.php?userID='.$userID.'" title="Modify">Modify</a>';
				}

				$txt.= '</td>
				<td align="center">';

				if($userID == 1){
					$txt.= '-';
				}elseif($userID != $currentUser){
					$txt.= '<form name="delete_user'.$userID.'">
								<input type="hidden" name="delete_user" value="1">
								<input type="hidden" name="userID" value="'.$userID.'">
								<a href="javascript:deleteUser('.$userID.')" title="Remove">Remove</a>
							</form>';
				}else{
					$txt.= '-';
				}

				$txt.= '</td>
			  </tr>';

		}

		//OUTPUT
		return $txt;

	}

	//#################################################################
    // CMS USER ARCHITECTURE
    //#################################################################
	function cmsUserArchitectureRemoved($cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL REMOVED USERS
		$result = $connector->query("SELECT * FROM cms_users WHERE deletedBy != ? ORDER BY name ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$userID		= $row['userID'];
			$name		= $row['name'];
			$surname	= $row['surname'];
			$deletedBy	= $row['deletedBy'];

			//GENERATE OUPUT
			$txt.= '<tr>
				<td class="removed-account"></td>
				<td>'.$name.' '.$surname.'</td>
				<td align="center">
				<form name="recover_user'.$userID.'">
					<input type="hidden" name="recover_user" value="1">
					<input type="hidden" name="userID" value="'.$userID.'">
					<a href="javascript:recoverUser('.$userID.')" title="Recover">Recover</a>
				</form>
				</td>
			  </tr>';

		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // CHECK IF USER IS IN DATABASE
    //#################################################################
	function checkUserDatabase($userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM cms_users WHERE userID = ?", array($userID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

	//#################################################################
    // CHECK IF ANY USERS HAVE BEEN REMOVED
    //#################################################################
	function checkRemovedCMSUsers(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET REMOVED USERS
		$result = $connector->query("SELECT * FROM cms_users WHERE deletedBy != ?", array('0'));
		$total = $connector->numResults($result);

		//RETURN TOTAL
		return $total;

	}

	//#################################################################
    // CHECK IF USER INFO HAS BEEN CHANGED
    //#################################################################
	function checkCmsUserChanges($name, $surname, $email, $password, $cell, $userID, $type, $modules){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $userModules = '';

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //CHECK IF MODULE HAD TO BE SUPPLIED
        if($type != 1 && $type != 0){
            //CONVERT ARRAY TO STRING
            foreach($modules AS $module){
                $userModules.= ','.$module.',';
            }

            //CLEAN UP STRING
            $userModules    = str_replace(',,', ',', $userModules);
        }

		//COMPARE USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE name = ? AND surname = ? AND email = ? AND password = ? AND contactNumber = ? AND userID = ? AND userType = ? AND userModuleRights = ?", array($name, $surname, $encrypted_email, $password, $cell, $userID, $type, $userModules));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION AHS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // CHECK IF USER INFO HAS BEEN CHANGED (PROFILE)
    //#################################################################
	function checkCmsUserChangesProfile($name, $surname, $email, $password, $cell, $userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $userModules = '';

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //CHECK IF MODULE HAD TO BE SUPPLIED
        if($type != 1 && $type != 0){
            //CONVERT ARRAY TO STRING
            foreach($modules AS $module){
                $userModules.= ','.$module.',';
            }

            //CLEAN UP STRING
            $userModules    = str_replace(',,', ',', $userModules);
        }

		//COMPARE USER INFO
		$result = $connector->query("SELECT * FROM cms_users WHERE name = ? AND surname = ? AND email = ? AND password = ? AND contactNumber = ? AND userID = ?", array($name, $surname, $encrypted_email, $password, $cell, $userID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION AHS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

	//#################################################################
    // ADD USER
    //#################################################################
	function addCmsUser($name, $surname, $email, $password, $cell, $type, $modules){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $userModules    = '';

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name		= strip_tags($name);
		$surname	= strip_tags($surname);
		$email		= strip_tags($email);
		$password	= strip_tags($password);
		$cell		= strip_tags($cell);

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //CHECK IF MODULE HAD TO BE SUPPLIED
        if($type != 1 && $type != 0){
            //CONVERT ARRAY TO STRING
            foreach($modules AS $module){
                $userModules.= ','.$module.',';
            }

            //CLEAN UP STRING
            $userModules    = str_replace(',,', ',', $userModules);
        }

		//ADD USER
		$insert = $connector->query("INSERT INTO cms_users (name, surname, email, password, contactNumber, userType, userModuleRights, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($name, $surname, $encrypted_email, $password, $cell, $type, $userModules, $currentUser, $currentDate));

        //GET LAST INSERTED USER ID
        $lastInsert = $connector->query("SELECT * FROM cms_users ORDER BY userID DESC", array());
        $last       = $connector->fetchArray($lastInsert);

        //RETURN LAST USER ID
        return $last['userID'];

	}

	//#################################################################
	//OVERWRITE USER
	//#################################################################
	function overwriteCMSUser($name, $surname, $email, $password, $cell, $type, $modules){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $userModules = '';

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name		= strip_tags($name);
		$surname	= strip_tags($surname);
		$email		= strip_tags($email);
		$password	= strip_tags($password);
		$cell		= strip_tags($cell);

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //CHECK IF MODULE HAD TO BE SUPPLIED
        if($type != 1 && $type != 0){
            //CLEAN UP STRING
            $userModules    = str_replace(',,', ',', $modules);
        }

		//REMOVE USER
		$delete = $connector->query("DELETE FROM cms_users WHERE email = ?", array($encrypted_email));

		//ADD USER
		$insert = $connector->query("INSERT INTO cms_users (name, surname, email, password, contactNumber, userType, userModuleRights, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
									array($name, $surname, $encrypted_email, $password, $cell, $type, $userModules, $currentUser, $currentDate));

	}

	//#################################################################
    // UPDATE USER
    //#################################################################
	function updateCmsUser($name, $surname, $email, $password, $cell, $userID, $type, $modules, $modifiedBy, $modifiedDate, $modifiedNumber){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $userModules = '';

		//STRIP INFO
		$name			= strip_tags($name);
		$surname		= strip_tags($surname);
		$email			= strip_tags($email);
		$password		= strip_tags($password);
		$cell			= strip_tags($cell);
		$modifiedBy		= strip_tags($modifiedBy);
		$modifiedDate	= strip_tags($modifiedDate);
		$modifiedNumber	= strip_tags($modifiedNumber);

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //CHECK IF MODULE HAD TO BE SUPPLIED
        if($type != 1 && $type != 0){
            //CONVERT ARRAY TO STRING
            foreach($modules AS $module){
                $userModules.= ','.$module.',';
            }

            //CLEAN UP STRING
            $userModules    = str_replace(',,', ',', $userModules);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE cms_users SET
									name = ?,
									surname = ?,
									email = ?,
									password = ?,
									contactNumber = ?,
                                    userType = ?,
                                    userModuleRights = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE userID = ?",
									array($name, $surname, $encrypted_email, $password, $cell, $type, $userModules, $modifiedBy, $modifiedDate, $modifiedNumber, $userID));

	}

    //#################################################################
    // UPDATE USER (PROFILE)
    //#################################################################
	function updateCmsUserProfile($name, $surname, $email, $password, $cell, $userID, $modifiedBy, $modifiedDate, $modifiedNumber){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $userModules = '';

		//STRIP INFO
		$name			= strip_tags($name);
		$surname		= strip_tags($surname);
		$email			= strip_tags($email);
		$password		= strip_tags($password);
		$cell			= strip_tags($cell);
		$modifiedBy		= strip_tags($modifiedBy);
		$modifiedDate	= strip_tags($modifiedDate);
		$modifiedNumber	= strip_tags($modifiedNumber);

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //CHECK IF MODULE HAD TO BE SUPPLIED
        if($type != 1 && $type != 0){
            //CONVERT ARRAY TO STRING
            foreach($modules AS $module){
                $userModules.= ','.$module.',';
            }

            //CLEAN UP STRING
            $userModules    = str_replace(',,', ',', $userModules);
        }

		//UPDATE USER
		$update = $connector->query("UPDATE cms_users SET
									name = ?,
									surname = ?,
									email = ?,
									password = ?,
									contactNumber = ?,
									modifiedBy = ?,
									modifiedDate = ?,
									modifiedNumber = ?
									WHERE userID = ?",
									array($name, $surname, $encrypted_email, $password, $cell, $modifiedBy, $modifiedDate, $modifiedNumber, $userID));

	}

    //#################################################################
    // ADDED USER INFO EMAIL
    //#################################################################
	function addedCmsUserInfoEmail($userID, $cms_name, $cms_fake_email, $cms_root, $non_md5_password){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLES
        $fullname   = $this->getUserInfo($userID, 'name').' '.$this->getUserInfo($userID, 'surname');
        $name       = $this->getUserInfo($userID, 'name');
        $surname    = $this->getUserInfo($userID, 'surname');
        $email      = $this->getUserInfo($userID, 'email');
        $number     = $this->getUserInfo($userID, 'contactNumber');
        $password   = $non_md5_password;
        $date       = date("j F Y");

        //SET EMAIL VARIABLES
        /*$to			= $email;
		$subject 	= 'Account Added - '.$cms_name;
		$from 		= $cms_name.'<'.$cms_fake_email.'>';

		// To send HTML mail, the Content-type header must be set
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers.= "From: ".$from;*/

		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><head style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><meta name="viewport" content="width=device-width" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">

        <style type="text/css" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">
        * {margin: 0; padding: 0; font-size: 100%; font-family: "Open Sans", "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; line-height: 1.65;}
        img {max-width: 100%; margin: 0 auto; display: block;}
        body, .body-wrap {width: 100% !important; height: 100%; background: #efefef; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;}
        a {color: #04a6df; text-decoration: none;}
        .text-center {text-align: center;}
        .text-right {text-align: right;}
        .text-left {text-align: left;}
        .button {display: inline-block; color: white; background: #04a6df; border: solid #04a6df; border-width: 10px 20px 8px; font-weight: bold; border-radius: 2px;}
        h1, h2, h3, h4, h5, h6 {margin-bottom: 20px; line-height: 1.25;}
        h1 {font-size: 32px;}
        h2 {font-size: 28px;}
        h3 {font-size: 24px;}
        h4 {font-size: 20px;}
        h5 {font-size: 16px;}
        p, ul, ol {font-size: 16px; font-weight: normal; margin-bottom: 20px;}
        .container {display: block !important; clear: both !important; margin: 0 auto !important; max-width: 580px !important;}
        .container table {width: 100% !important; border-collapse: collapse;}
        .container .masthead {padding: 40px 0; background: #1c2126; color: white;}
        .container .masthead h1 {margin: 30px auto 0 auto !important; max-width: 90%; text-transform: uppercase;}
        .container .content {background: white; padding: 30px 35px;}
        .container .content.footer {background: none;}
        .container .content.footer p {margin-bottom: 0; color: #888; text-align: center; font-size: 14px;}
        .container .content.footer a {color: #888; text-decoration: none; font-weight: bold;}
        </style>

        </head><body style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;"><table class="body-wrap" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="container" style="margin: 0 auto !important;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td align="center" class="masthead" style="margin: 0;padding: 40px 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: #1c2126;color: white;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td align="center" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><img src="'.$cms_root.'images/logo/cms-logo.png" alt="Michael Jacobsen CMS Logo" title="Michael Jacobsen CMS Logo" style="margin: 0 auto;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;max-width: 100%;display: block;"></td></tr></table><h1 style="margin: 30px auto 0 auto !important;padding: 0;font-size: 32px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px;max-width: 90%;text-transform: uppercase;">CMS Account Added</h1></td></tr><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="content" style="margin: 0;padding: 30px 35px;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: white;"><h2 style="margin: 0;padding: 0;font-size: 28px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px;">Hi '.$fullname.',</h2><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">This email is a notification to inform you that your CMS account has been added on <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$date.'</b></strong> and below you can find your account details.</p><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">Name: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$name.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Surname: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$surname.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Email: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$email.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Password: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$password.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Contact Number: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$number.'</b></strong></p><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">Please note down these details, store them in a safe place and then delete this email. This is for security reasons so that no one who has access to your email account can steal these details and log into the CMS without you permission.</p></td></tr></table></td></tr><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="container" style="margin: 0 auto !important;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="content footer" align="center" style="margin: 0;padding: 30px 35px;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: none;"><p style="margin: 0;padding: 0;font-size: 14px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 0;color: #888;text-align: center;">Sent by <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$cms_name.'</b></strong></p></td></tr></table></td></tr></table></body></html>';

		//mail($to,$subject,$body,$headers);

        //SEND USING PHPMAILER
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        /*$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'user@example.com';                 // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to*/

        $mail->CharSet = 'UTF-8';

        $mail->setFrom($cms_fake_email, $cms_name);
        $mail->addAddress($email);
        $mail->addReplyTo($cms_fake_email, $cms_name);
        /*$mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');*/

        /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Account Added - '.$cms_name;
        $mail->Body    = $body;

        if(!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message has been sent';
        }

	}

    //#################################################################
    // CHANGE USER INFO EMAIL
    //#################################################################
	function changeCmsUserInfoEmail($userID, $cms_name, $cms_fake_email, $cms_root, $non_md5_password){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLES
        $fullname   = $this->getUserInfo($userID, 'name').' '.$this->getUserInfo($userID, 'surname');
        $name       = $this->getUserInfo($userID, 'name');
        $surname    = $this->getUserInfo($userID, 'surname');
        $email      = $this->getUserInfo($userID, 'email');
        $number     = $this->getUserInfo($userID, 'contactNumber');
        $password   = $non_md5_password;
        $date       = date("j F Y");

        //SET EMAIL VARIANLES
        /*$to			= $email;
		$subject 	= 'Account Updated - '.$cms_name;
		$from 		= $cms_name.'<'.$cms_fake_email.'>';

		// To send HTML mail, the Content-type header must be set
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers.= "From: ".$from;*/

		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><head style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><meta name="viewport" content="width=device-width" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">

        <style type="text/css" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">
        * {margin: 0; padding: 0; font-size: 100%; font-family: "Open Sans", "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; line-height: 1.65;}
        img {max-width: 100%; margin: 0 auto; display: block;}
        body, .body-wrap {width: 100% !important; height: 100%; background: #efefef; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;}
        a {color: #04a6df; text-decoration: none;}
        .text-center {text-align: center;}
        .text-right {text-align: right;}
        .text-left {text-align: left;}
        .button {display: inline-block; color: white; background: #04a6df; border: solid #04a6df; border-width: 10px 20px 8px; font-weight: bold; border-radius: 2px;}
        h1, h2, h3, h4, h5, h6 {margin-bottom: 20px; line-height: 1.25;}
        h1 {font-size: 32px;}
        h2 {font-size: 28px;}
        h3 {font-size: 24px;}
        h4 {font-size: 20px;}
        h5 {font-size: 16px;}
        p, ul, ol {font-size: 16px; font-weight: normal; margin-bottom: 20px;}
        .container {display: block !important; clear: both !important; margin: 0 auto !important; max-width: 580px !important;}
        .container table {width: 100% !important; border-collapse: collapse;}
        .container .masthead {padding: 40px 0; background: #1c2126; color: white;}
        .container .masthead h1 {margin: 30px auto 0 auto !important; max-width: 90%; text-transform: uppercase;}
        .container .content {background: white; padding: 30px 35px;}
        .container .content.footer {background: none;}
        .container .content.footer p {margin-bottom: 0; color: #888; text-align: center; font-size: 14px;}
        .container .content.footer a {color: #888; text-decoration: none; font-weight: bold;}
        </style>

        </head><body style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;"><table class="body-wrap" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="container" style="margin: 0 auto !important;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td align="center" class="masthead" style="margin: 0;padding: 40px 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: #1c2126;color: white;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td align="center" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><img src="'.$cms_root.'images/logo/cms-logo.png" alt="Michael Jacobsen CMS Logo" title="Michael Jacobsen CMS Logo" style="margin: 0 auto;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;max-width: 100%;display: block;"></td></tr></table><h1 style="margin: 30px auto 0 auto !important;padding: 0;font-size: 32px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px;max-width: 90%;text-transform: uppercase;">CMS Account Information Updated</h1></td></tr><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="content" style="margin: 0;padding: 30px 35px;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: white;"><h2 style="margin: 0;padding: 0;font-size: 28px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px;">Hi '.$fullname.',</h2><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">This email is a notification to inform you that your CMS account information has been updated on <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$date.'</b></strong> and below you can find the new updated account details.</p><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">Name: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$name.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Surname: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$surname.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Email: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$email.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">';

        if($password != ''){
            $body.= 'Password: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$password.'</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">';
        }else{
            $body.= 'Password: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">Your Password is still the same</b></strong><br style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">';
        }

        $body.= 'Contact Number: <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$number.'</b></strong></p><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">Please note down these details, store them in a safe place and then delete this email. This is for security reasons so that no one who has access to your email account can steal these details and log into the CMS without you permission.</p></td></tr></table></td></tr><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="container" style="margin: 0 auto !important;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="content footer" align="center" style="margin: 0;padding: 30px 35px;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: none;"><p style="margin: 0;padding: 0;font-size: 14px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 0;color: #888;text-align: center;">Sent by <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$cms_name.'</b></strong></p></td></tr></table></td></tr></table></body></html>';

		//mail($to,$subject,$body,$headers);

        //SEND USING PHPMAILER
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        /*$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'user@example.com';                 // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to*/

        $mail->CharSet = 'UTF-8';

        $mail->setFrom($cms_fake_email, $cms_name);
        $mail->addAddress($email);
        $mail->addReplyTo($cms_fake_email, $cms_name);
        /*$mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');*/

        /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Account Updated - '.$cms_name;
        $mail->Body    = $body;

        if(!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message has been sent';
        }

	}

    //#################################################################
    // DELETE USER EMAIL
    //#################################################################
	function deleteCmsUserEmail($userID, $cms_name, $cms_fake_email, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET VARIABLES
        $name   = $this->getUserInfo($userID, 'name').' '.$this->getUserInfo($userID, 'surname');
        $date   = date("j F Y");
        $email  = $this->getUserInfo($userID, 'email');

        //SET EMAIL VARIANLES
        /*$to			= $email;
		$subject 	= 'Account Deleted - '.$cms_name;
		$from 		= $cms_name.'<'.$cms_fake_email.'>';*/

		// To send HTML mail, the Content-type header must be set
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers.= "From: ".$from;

		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><head style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><meta name="viewport" content="width=device-width" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">

            <style type="text/css" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">
            * {margin: 0; padding: 0; font-size: 100%; font-family: "Open Sans", "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; line-height: 1.65;}
            img {max-width: 100%; margin: 0 auto; display: block;}
            body, .body-wrap {width: 100% !important; height: 100%; background: #efefef; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;}
            a {color: #04a6df; text-decoration: none;}
            .text-center {text-align: center;}
            .text-right {text-align: right;}
            .text-left {text-align: left;}
            .button {display: inline-block; color: white; background: #04a6df; border: solid #04a6df; border-width: 10px 20px 8px; font-weight: bold; border-radius: 2px;}
            h1, h2, h3, h4, h5, h6 {margin-bottom: 20px; line-height: 1.25;}
            h1 {font-size: 32px;}
            h2 {font-size: 28px;}
            h3 {font-size: 24px;}
            h4 {font-size: 20px;}
            h5 {font-size: 16px;}
            p, ul, ol {font-size: 16px; font-weight: normal; margin-bottom: 20px;}
            .container {display: block !important; clear: both !important; margin: 0 auto !important; max-width: 580px !important;}
            .container table {width: 100% !important; border-collapse: collapse;}
            .container .masthead {padding: 40px 0; background: #1c2126; color: white;}
            .container .masthead h1 {margin: 30px auto 0 auto !important; max-width: 90%; text-transform: uppercase;}
            .container .content {background: white; padding: 30px 35px;}
            .container .content.footer {background: none;}
            .container .content.footer p {margin-bottom: 0; color: #888; text-align: center; font-size: 14px;}
            .container .content.footer a {color: #888; text-decoration: none; font-weight: bold;}
            </style>

        </head><body style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;"><table class="body-wrap" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="container" style="margin: 0 auto !important;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td align="center" class="masthead" style="margin: 0;padding: 40px 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: #1c2126;color: white;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td align="center" style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><img src="'.$cms_root.'images/logo/cms-logo.png" alt="Michael Jacobsen CMS Logo" title="Michael Jacobsen CMS Logo" style="margin: 0 auto;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;max-width: 100%;display: block;"></td></tr></table><h1 style="margin: 30px auto 0 auto !important;padding: 0;font-size: 32px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px;max-width: 90%;text-transform: uppercase;">CMS Account Deleted</h1></td></tr><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="content" style="margin: 0;padding: 30px 35px;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: white;"><h2 style="margin: 0;padding: 0;font-size: 28px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px;">Hi '.$name.',</h2><p style="margin: 0;padding: 0;font-size: 16px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 20px;">This email is a notification to inform you that your CMS account has been deleted on the <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$date.'</b></strong> and you will no longer be able access to it.</p></td></tr></table></td></tr><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="container" style="margin: 0 auto !important;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;"><table style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;"><tr style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><td class="content footer" align="center" style="margin: 0;padding: 30px 35px;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;background: none;"><p style="margin: 0;padding: 0;font-size: 14px;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;font-weight: normal;margin-bottom: 0;color: #888;text-align: center;">Sent by <strong style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;"><b style="margin: 0;padding: 0;font-size: 100%;font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;line-height: 1.65;">'.$cms_name.'</b></strong></p></td></tr></table></td></tr></table></body></html>';

		//mail($to,$subject,$body,$headers);

        //SEND USING PHPMAILER
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        /*$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'user@example.com';                 // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to*/

        $mail->CharSet = 'UTF-8';

        $mail->setFrom($cms_fake_email, $cms_name);
        $mail->addAddress($email);
        $mail->addReplyTo($cms_fake_email, $cms_name);
        /*$mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');*/

        /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Account Deleted - '.$cms_name;
        $mail->Body    = $body;

        if(!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message has been sent';
        }

	}



	//#################################################################
    // DELETE USER
    //#################################################################
	function deleteCmsUser($userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("UPDATE cms_users SET
									deletedBy = ?,
									deletedDate = ?
									WHERE userID = ?",
									array($currentUser, $currentDate, $userID));

	}

	//#################################################################
    // RECOVER USER
    //#################################################################
	function recoverCmsUser($userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//RECOVER USER
		$recover = $connector->query("UPDATE cms_users SET
									deletedBy = ?,
									deletedDate = ?
									WHERE userID = ?",
									array('0', '0000-00-00 00:00:00', $userID));

	}

	//#################################################################
    // CHECK IF EMAIL IS ALREADY IN USE
    //#################################################################
	function addEmailCheck($email){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//CHECK EMAIL
		$result = $connector->query("SELECT * FROM cms_users WHERE email = ?", array($encrypted_email));
		$total	= $connector->numResults($result);

		//IF EMAIL HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
		//IF EMAIL HAS BEEN USED
		elseif($total == 1){
			//GET USER INFO
			$row 		= $connector->fetchArray($result);

			//SET VARIABLES
			$deletedBy	= $row['deletedBy'];

			//IF USER HAS BEEN REMOVED
			if($deletedBy != 0){
				return 'removed_user';
			}
		}

	}

	//#################################################################
    // CHECK IF EMAIL IS ALREADY IN USE
    //#################################################################
	function editEmailCheck($email, $userID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//CHECK EMAIL
		$result = $connector->query("SELECT * FROM cms_users WHERE email = ? AND userID != ?", array($encrypted_email, $userID));
		$total	= $connector->numResults($result);

		//NOT IS USE
		if($total == 0){
			return 'unused';
		}
        //IF EMAIL HAS BEEN USED
		elseif($total == 1){
			//GET USER INFO
			$row 		= $connector->fetchArray($result);

			//SET VARIABLES
			$deletedBy	= $row['deletedBy'];

			//IF USER HAS BEEN REMOVED
			if($deletedBy != 0){
				return 'removed_user';
			}
		}

	}

}

//DEFINE CLASS
$userManager = new userManager();

//#################################################################
// ADD USER
//#################################################################
if(isset($_POST['add_cms_user'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name 		= $_POST['user-name'];
	$surname	= $_POST['user-surname'];
	$email		= $_POST['user-email'];
	$password	= $_POST['user-password'];
	$cell		= $_POST['user-contact-number'];
    $type       = $_POST['user-type'];
    $modules    = $_POST['userSelectedRoles'];

	//HONEY POTS
	$email2		= $_POST['user-email-re-type'];
	$cell2		= $_POST['user-contact-number-2'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name       = $userLogin->specialCharactersToHTMLEntity($name);
    $surname    = $userLogin->specialCharactersToHTMLEntity($surname);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Name', 2, 150);
	$v->validateString($surname, 'Surname', 2, 150);
	$v->validateEmailAddress($email, 'Email');
	$v->validateCMSUserPassword($password, 'Password', 8);
	$v->validateContactNumbers($cell, 'Contact Number');
    $v->validateDropDown($type, 'User Type');

    //CHECK IF USER IS NOT AN ADMINISTRATOR
    if($type != 1 && $type != 0){
        //VALIDATE MODULE ARRAY
        $v->validateTags($modules, 'Assign Modules');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($email2 == '' && $cell2 == ''){

			//CHECK IF EMAIL IS ALREADY IN USE
			$email_used = $userManager->addEmailCheck($email);
			if($email_used == 'unused'){

                //NON ENCRYPTED PASSWORD
                $non_md5_password = $password;

				//SET PASSWORD
				$password = md5($password);

				//INSERT USER INTO DATABASE
				$userID = $userManager->addCmsUser($name, $surname, $email, $password, $cell, $type, $modules);

                //SEND ADDED EMAIL
                $userManager->addedCmsUserInfoEmail($userID, $cms_name, $cms_fake_email, $cms_root, $non_md5_password);

				//REDIRECT USER
				header("Location: ".$cms_root."cms-users-manager/index.php?message=1");
                exit;

			}
			//IF USER HAS BEEN REMOVED
			elseif($email_used == 'removed_user'){
				//SET USER AS REMOVED
				$removed_user = '1';
			}
			else{
				//SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Email</b> you supplied is already used by another active user. Please try another!</li></ul>';
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
// OVERWRITE USER
//#################################################################
if(isset($_POST['overright-user-info'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();
    $encryptDecrypt = new encryptDecrypt();

    //DEFINE ENCRYPTION_KEY
    define("ENCRYPTION_KEY", "%@#!^&$*");

	//GET POSTED VALUES
	$name		= $_POST['name'];
	$surname	= $_POST['surname'];
	$email		= $_POST['email'];
	$password	= $_POST['password'];
	$cell		= $_POST['number'];
    $type       = $_POST['user-type'];
    $modules    = ','.$_POST['userSelectedRoles'].',';

	//HONEY POTS
	$retype		= $_POST['retype'];
	$number2	= $_POST['number2'];

	if($retype == '' && $number2 == ''){

        //MD5 PASSWORD
        $password   = md5($password);

		//OVERWRITE USER
		$userManager->overwriteCMSUser($name, $surname, $email, $password, $cell, $type, $modules);

		//REDIRECT PAGE
		header("Location: ".$cms_root."cms-users-manager/index.php?message=5");
        exit;
	}
}

//#################################################################
// EDIT USER
//#################################################################
if(isset($_POST['edit_cms_user'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name 			= $_POST['user-name'];
	$surname		= $_POST['user-surname'];
	$email			= $_POST['user-email'];
	$password		= $_POST['user-password'];
	$cell			= $_POST['user-contact-number'];
	$userID			= $_POST['userID'];
    $type           = $_POST['user-type'];
    $modules        = $_POST['userSelectedRoles'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$email2		= $_POST['user-email-re-type'];
	$cell2		= $_POST['user-contact-number-2'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $name       = $userLogin->specialCharactersToHTMLEntity($name);
    $surname    = $userLogin->specialCharactersToHTMLEntity($surname);

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Name', 2, 150);
	$v->validateString($surname, 'Surname', 2, 150);
	$v->validateEmailAddress($email, 'Email');
	$v->validateContactNumbers($cell, 'Contact Number');

	//ONLY IF PASSWORD WANTS TO BE CHANGED
	if($password != ''){
		$v->validateCMSUserPassword($password, 'Password', 8);
	}

    $v->validateDropDown($type, 'User Type');

    //CHECK IF USER IS NOT AN ADMINISTRATOR
    if($type != 1 && $type != 0){
        //VALIDATE MODULE ARRAY
        $v->validateTags($modules, 'Assign Modules');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($email2 == '' && $cell2 == ''){

			//CHECK IF PASSWORD HAS BEEN FILLED OUT
            $non_md5_password = $password;
			if($password == ''){
				$password = $userManager->getUserInfo($userID, 'password');
			}else{
				$password = md5($password);
			}

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($userManager->checkCmsUserChanges($name, $surname, $email, $password, $cell, $userID, $type, $modules) == 'changed'){

				//CHECK EMAIL USED
				$email_used = $userManager->editEmailCheck($email, $userID);
				if($email_used == 'unused'){

					//UPDATE USER IN DATABASE
					$userManager->updateCmsUser($name, $surname, $email, $password, $cell, $userID, $type, $modules, $modifiedBy, $modifiedDate, $modifiedNumber);

                    //SEND CHANGE EMAIL
                    $userManager->changeCmsUserInfoEmail($userID, $cms_name, $cms_fake_email, $cms_root, $non_md5_password);

					//REDIRECT USER
					header("Location: ".$cms_root."cms-users-manager/index.php?message=2");
                    exit;

				}
                //IF USER HAS BEEN REMOVED
    			elseif($email_used == 'removed_user'){
    				//SET USER AS REMOVED
    				$removed_user = '1';
    			}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Email</b> you supplied is already used by another user. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."cms-users-manager/");
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
// EDIT PROFILE
//#################################################################
if(isset($_POST['edit_profile'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$name 			= $_POST['user-name'];
	$surname		= $_POST['user-surname'];
	$email			= $_POST['user-email'];
	$password		= $_POST['user-password'];
	$cell			= $_POST['user-contact-number'];
	$userID			= $_POST['userID'];

	$modifiedDate	= $_POST['modifiedDate'];
	$modifiedBy		= $_SESSION['cmsUser'];
	$modifiedNumber	= $_POST['modifiedNumber'];

	//HONEY POTS
	$email2		= $_POST['user-email-re-type'];
	$cell2		= $_POST['user-contact-number-2'];

	//VALIDATION
	$v = new formValidation();
	$v->validateString($name, 'Name', 2, 150);
	$v->validateString($surname, 'Surname', 2, 150);
	$v->validateEmailAddress($email, 'Email');
	$v->validateContactNumbers($cell, 'Contact Number');

	//ONLY IF PASSWORD WANTS TO BE CHANGED
	if($password != ''){
		$v->validatePassword($password, 'Password', 8);
	}

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($email2 == '' && $cell2 == ''){

			//CHECK IF PASSWORD HAS BEEN FILLED OUT
            $non_md5_password = $password;
			if($password == ''){
				$password = $userManager->getUserInfo($userID, 'password');
			}else{
				$password = md5($password);
			}

			//CHECK IF CONTENT HAS BEEN CHANGED
			if($userManager->checkCmsUserChangesProfile($name, $surname, $email, $password, $cell, $userID) == 'changed'){

				//CHECK EMAIL USED
				$email_used = $userManager->editEmailCheck($email, $userID);
				if($email_used == 'unused'){

					//UPDATE USER IN DATABASE
					$userManager->updateCmsUserProfile($name, $surname, $email, $password, $cell, $userID, $modifiedBy, $modifiedDate, $modifiedNumber);

                    //SEND CHANGE EMAIL
                    $userManager->changeCmsUserInfoEmail($userID, $cms_name, $cms_fake_email, $cms_root, $non_md5_password);

					//REDIRECT USER
					header("Location: ".$cms_root."profile/index.php?message=6");
                    exit;

				}
				else{
					//SET ERROR MESSAGE
					$error_message = 'There was an error!';
					$errors = '<ul class="errors"><li>The <b>Email</b> you supplied is already used by another user. Please try another!</li></ul>';
				}
			}
			//NO CONTENT HAS BEEN CHANGED
			else{
				//REDIRECT USER
				header("Location: ".$cms_root."profile/");
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
//DELETE USER
//#################################################################
if(isset($_POST['delete_user'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $userID	= $_POST['userID'];

    //SENT DELETE USER EMAIL
    $userManager->deleteCmsUserEmail($userID, $cms_name, $cms_fake_email, $cms_root);

    //SET USER AS REMOVED IN DATABASE
    $userManager->deleteCmsUser($userID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."cms-users-manager/index.php?message=3");
    exit;
}

//#################################################################
//RECOVER USER
//#################################################################
if(isset($_POST['recover_user'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $userID	= $_POST['userID'];

    //SET USER AS REMOVED IN DATABASE
    $userManager->recoverCmsUser($userID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."cms-users-manager/index.php?message=4");
    exit;
}
?>
