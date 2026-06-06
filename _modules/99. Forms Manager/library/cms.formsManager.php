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
require_once("class.encryptDecrypt.php");
require_once("class.browserDetails.php");

class formsManager extends systemConfig{
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
			case 1: $displayMessage = 'A new Topic has successfully been added.'; break;
			case 2: $displayMessage = 'The selected Topic has successfully been updated.'; break;
            case 3: $displayMessage = 'The selected Topic has successfully been removed.'; break;
            case 4: $displayMessage = 'The selected Form has successfully been updated.'; break;
            case 5: $displayMessage = 'A new Main Recipient has successfully been added.'; break;
            case 6: $displayMessage = 'The selected Main Recipient has successfully been updated.'; break;
            case 7: $displayMessage = 'The selected Main Recipient has successfully been removed.'; break;
            case 8: $displayMessage = 'A new Topic Recipient has successfully been added.'; break;
            case 9: $displayMessage = 'The selected Topic Recipient has successfully been updated.'; break;
            case 10: $displayMessage = 'The selected Topic Recipient has successfully been removed.'; break;
        }

        $fullMessage = '<div class="rightContentBoxContainerApprove">'.$displayMessage.'</div>';

        if($message != ""){
            return $fullMessage;
        }
    }

    //ESCAPE CERTAIN CHARACTERS FOR SAFER QUERIES
	function escape($str)
    {
        $search=array("\\","\0","\n","\r","\x1a","'",'"');
        $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
        return str_replace($search,$replace,$str);
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
    // GET TOTAL FORMS
    //#################################################################
	function getTotalForms(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM forms WHERE 	deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET TOTAL TOPICS
    //#################################################################
	function getTotalTopics(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM form_topics WHERE deletedBy = ?", array('0'));
		$total	= $connector->numResults($result);

		//RETURN VAlUE
		return $total;

	}

    //#################################################################
    // GET EMPTY FORMS
    //#################################################################
	function getTotalEmptyForms(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM forms WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$formID	= $row['formID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM forms_recipients WHERE formID = ? AND topicID = ? AND deletedBy = ?", array($formID, '0', '0'));
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
    // GET EMPTY TOPICS
    //#################################################################
	function getTotalEmptyTopic(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//DEFAULT VARIABLE
		$count = 0;

		//GET USER INFO
		$result = $connector->query("SELECT * FROM form_topics WHERE deletedBy = ?", array('0'));
		while($row	= $connector->fetchArray($result)){

			//SET VAIABLES
			$topicID	= $row['topicID'];

			//GET ALL CONTENT FOR BLOG POST
			$result2	= $connector->query("SELECT * FROM forms_recipients WHERE topicID = ? AND deletedBy = ?", array($topicID, '0'));
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
    // GET TOPIC INFORMATION
    //#################################################################
	function getTopicInfo($topicID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM form_topics WHERE topicID = ?", array($topicID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET RECIPIENT INFORMATION
    //#################################################################
	function getRecipientInfo($recipientID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

		//GET USER INFO
		$result = $connector->query("SELECT * FROM forms_recipients WHERE recipientID = ?", array($recipientID));
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

	}

	//#################################################################
    // GET PAGE INFORMATION
    //#################################################################
	function getFormInfo($formID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET USER INFO
		$result = $connector->query("SELECT * FROM forms WHERE formID = ?", array($formID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // CHECK IF FORM NAME IS ALREADY IN USE
    //#################################################################
	function editFormCheck($title){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM forms WHERE formName = ?", array($title));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

    //#################################################################
    // CHECK IF TOPIC NAME IS ALREADY IN USE
    //#################################################################
	function addTopicCheck($title, $form){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM form_topics WHERE formID = ? AND topicName = ?", array($form, $title));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

    //#################################################################
    // CHECK IF EMAIL ADDRESS IS ALREADY IN USE BY FORM
    //#################################################################
	function addMainRecipientCheck($email, $formID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM forms_recipients WHERE formID = ? AND topicID = ? AND email = ?", array($formID, '0', $encrypted_email));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

    //#################################################################
    // CHECK IF EMAIL ADDRESS IS ALREADY IN USE BY FORM
    //#################################################################
	function addTopicRecipientCheck($email, $formID, $topicID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//CHECK CATEGORY NAME
		$result = $connector->query("SELECT * FROM forms_recipients WHERE formID = ? AND topicID = ? AND email = ?", array($formID, $topicID, $encrypted_email));
		$total	= $connector->numResults($result);

		//IF CATEGORY NAME HASN'T BEEN USED
		if($total == 0){
			return 'unused';
		}
	}

    //#################################################################
    // CHECK IF MAIN RECIPIENT INFO HAS BEEN CHANGED
    //#################################################################
	function checkMainRecipientChanges($name, $email, $recipientID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM forms_recipients WHERE fullname = ? AND email = ? AND recipientID = ?", array($name, $encrypted_email, $recipientID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // CHECK IF TOPIC RECIPIENT INFO HAS BEEN CHANGED
    //#################################################################
	function checkTopicRecipientChanges($name, $email, $recipientID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//COMPARE CATEGORY INFO
		$result = $connector->query("SELECT * FROM forms_recipients WHERE fullname = ? AND email = ? AND recipientID = ?", array($name, $encrypted_email, $recipientID));
		$total	= $connector->numResults($result);

		//CHECK IF INFORMATION HAS BEEN CHANGED
		if($total == 0){
			return 'changed';
		}

	}

    //#################################################################
    // GET ALL MAIN FORMS
    //#################################################################
	function getMainForms($postedValue){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt    = '';

		//GET ALL MAIN FORMS
		$result = $connector->query("SELECT * FROM forms ORDER BY formName ASC", array());
		while($row	= $connector->fetchArray($result)){

            //SET VARIABLES
            $formID     = $row['formID'];
            $formName   = $row['formName'];

            if($formID == $postedValue){
                $txt.= '<option value="'.$formID.'" selected="selected">'.$formName.'</option>';
            }else{
                $txt.= '<option value="'.$formID.'">'.$formName.'</option>';
            }

        }

        //RETURN RESULT
        return $txt;
	}

    //#################################################################
    // CHECK IF MAIN RECIPIENT IS IN DATABASE
    //#################################################################
    function checkMainRecipientDatabase($recipientID, $formID){
        //CONNECT TO DATABASE
        $connector = new dbConnector();

        //GET QUOTE TOTAL
        $result = $connector->query("SELECT * FROM forms_recipients WHERE recipientID = ? AND formID = ? AND topicID = ?", array($recipientID, $formID, '0'));
        $total	= $connector->NumResults($result);

        //IF NO RESULT FOUND
        if($total == 0){
            return 'not found';
        }
    }

    //#################################################################
    // CHECK IF TOPIC RECIPIENT IS IN DATABASE
    //#################################################################
    function checkTopicRecipientDatabase($recipientID, $formID, $topicID){
        //CONNECT TO DATABASE
        $connector = new dbConnector();

        //GET QUOTE TOTAL
        $result = $connector->query("SELECT * FROM forms_recipients WHERE recipientID = ? AND formID = ? AND topicID = ?", array($recipientID, $formID, $topicID));
        $total	= $connector->NumResults($result);

        //IF NO RESULT FOUND
        if($total == 0){
            return 'not found';
        }
    }

    //#################################################################
    // CHECK IF FORM IS IN DATABASE
    //#################################################################
	function checkFormDatabase($formID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM forms WHERE 	formID = ?", array($formID));
		$total	= $connector->NumResults($result);

		//IF NO RESULT FOUND
		if($total == 0){
			return 'not found';
		}
	}

    //#################################################################
    // CHECK IF TOPIC IS IN DATABASE
    //#################################################################
	function checkTopicDatabase($formID, $topicID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET QUOTE TOTAL
		$result = $connector->query("SELECT * FROM form_topics WHERE formID = ? AND topicID = ?", array($formID, $topicID));
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
    // FORMS ARCHITECTURE
    //#################################################################
	function formsArchitecture($cms_root, $topic_add){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL PAGES
		$result = $connector->query("SELECT * FROM forms WHERE deletedBy = ? ORDER BY formName ASC", array('0'));
		while($row = $connector->fetchArray($result)){

			//SET VARIABLES
			$empty			= '';
			$empty_bg		= '';
            $formID         = $row['formID'];
			$formName		= $row['formName'];

			//GET RECIPIENTS FOR FORM
			$result2	= $connector->query("SELECT * FROM forms_recipients WHERE formID = ? AND topicID = ? AND deletedBy = ?", array($formID, '0', '0'));
			$recipientTotal	= $connector->numResults($result2);

			//IF FORM HS NO RECIPIENTS
			if($recipientTotal == 0){
				$empty		= '<span class="empty-category-text">(No Recipients)</span>';
				$empty_bg	='empty-category';
			}

			//GENERATE OUPUT
			$txt.= '<tr>
				<td colspan="2" class="'.$empty_bg.'">'.$formName.' '.$empty.'</td>
                <td class="'.$empty_bg.'">'.$recipientTotal.' Main Recipient(s)</td>
				<td class="'.$empty_bg.'" align="center">
					<a href="'.$cms_root.'forms-manager/manage-main-recipients.php?formID='.$formID.'" title="Manage Recipient(s)">Manage Recipient(s)</a>
				</td>
                <td class="'.$empty_bg.'" align="center">
					<a href="'.$cms_root.'forms-manager/edit-form.php?formID='.$formID.'" title="Modify">Modify</a>
				</td>
                <td class="'.$empty_bg.'" align="center"> - </td>
			  </tr>';

            //GET FORM TOPICS
            $result3 = $connector->query("SELECT * FROM form_topics WHERE formID = ? AND deletedBy = ? ORDER BY topicName ASC", array($formID, '0'));
            while($row3    = $connector->fetchArray($result3)){

                //SET VARIABLES
    			$empty			= '';
    			$empty_bg		= '';
                $topicID        = $row3['topicID'];
                $formID         = $row3['formID'];
    			$topicName		= $row3['topicName'];

    			//GET CONTENT FOR PAGE
    			$result4	= $connector->query("SELECT * FROM forms_recipients WHERE formID = ? AND topicID = ? AND deletedBy = ?", array($formID, $topicID, '0'));
    			$topicRecipientTotal	= $connector->numResults($result4);

    			//IF NO TOPIC RECIPIENTS HAVE BEEN ADDED
    			if($topicRecipientTotal == 0){
    				$empty		= '<span class="empty-category-text">(No Recipients)</span>';
    				$empty_bg	='empty-category';
    			}

    			//GENERATE OUPUT
                if($topic_add == 0){
                    $txt.= '<tr>
                          <td width="2%" class="no-border-right '.$empty_bg.'"></td>
                          <td class="no-border-left '.$empty_bg.'">'.$topicName.' '.$empty.'</td>
                          <td class="'.$empty_bg.'">'.$topicRecipientTotal.' Total Recipient(s)</td>
                          <td class="'.$empty_bg.'" align="center">
                              <a href="'.$cms_root.'forms-manager/manage-topic-recipient.php?topicID='.$topicID.'&formID='.$formID.'" title="Manage Recipient(s)">Manage Recipient(s)</a>
                          </td>
                          <td class="'.$empty_bg.'" align="center">
          				      <a href="'.$cms_root.'forms-manager/edit-topic.php?topicID='.$topicID.'&formID='.$formID.'" title="Modify">Modify</a>
          			      </td>
                          <td class="'.$empty_bg.'" align="center"> - </td>
                        </tr>';

                }else{
        			$txt.= '<tr>
                          <td width="2%" class="no-border-right '.$empty_bg.'"></td>
                          <td class="no-border-left '.$empty_bg.'">'.$topicName.' '.$empty.'</td>
                          <td class="'.$empty_bg.'">'.$topicRecipientTotal.' Total Recipient(s)</td>
                          <td class="'.$empty_bg.'" align="center">
                              <a href="'.$cms_root.'forms-manager/manage-topic-recipient.php?topicID='.$topicID.'&formID='.$formID.'" title="Manage Recipient(s)">Manage Recipient(s)</a>
                          </td>
                          <td class="'.$empty_bg.'" align="center">
          				      <a href="'.$cms_root.'forms-manager/edit-topic.php?topicID='.$topicID.'&formID='.$formID.'" title="Modify">Modify</a>
          			      </td>
                          <td class="'.$empty_bg.'" align="center">';

                          if($topicRecipientTotal != 0){
                                $txt.= '<a href="javascript:noDeleteTopic()" title="Remove">Remove</a>';
                          }else{
                              $txt.= '<form name="delete_topic'.$topicID.'">
                                <input type="hidden" name="delete_topic" value="1">
                                <input type="hidden" name="topicID" value="'.$topicID.'">
                                <a href="javascript:deleteTopic('.$topicID.')" title="Remove">Remove</a>
                              </form>';
                          }

                          $txt.= '</td>
                        </tr>';
                    }

            }

		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // DELETE TOPIC
    //#################################################################
	function deleteTopic($topicID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//REMOVE USER
		$remove = $connector->query("DELETE FROM form_topics WHERE topicID = ?", array($topicID));

	}

    //#################################################################
    // DELETE MAIN RECIPIENT
    //#################################################################
	function deleteMainRecipient($recipientID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //REMOVE MAIN RECIPIENT
    	$remove = $connector->query("DELETE FROM forms_recipients WHERE recipientID = ?",array($recipientID));

	}

    //#################################################################
    // DELETE TOPIC RECIPIENT
    //#################################################################
	function deleteTopicRecipient($recipientID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

        //REMOVE MAIN RECIPIENT
    	$remove = $connector->query("DELETE FROM forms_recipients WHERE recipientID = ?",array($recipientID));

	}

    //#################################################################
    // MAIN RECIPIENTS ARCHITECTURE
    //#################################################################
	function mainRecipientsArchitecture($cms_root, $formID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL BLOG POSTS
		$result = $connector->query("SELECT * FROM forms_recipients WHERE deletedBy = ? AND formID = ? AND topicID = ? ORDER BY fullname ASC", array('0', $formID, '0'));
		$mainRecipientTotal = $connector->numResults($result);

		//IF MAIN RECIPIENTS ARE AVAILABLE
		if($mainRecipientTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$recipientID    = $row['recipientID'];
                $fullname       = $row['fullname'];

				//GENERATE OUPUT
				$txt.= '<tr>
					<td>'.$fullname.'</td>
					<td align="center">
						<a href="'.$cms_root.'forms-manager/edit-main-recipients.php?formID='.$formID.'&recipientID='.$recipientID.'" title="Modify">Modify</a>
					</td>
					<td align="center">
                        <form name="delete_main_recipient'.$recipientID.'">
							<input type="hidden" name="delete_main_recipient" value="1">
							<input type="hidden" name="recipientID" value="'.$recipientID.'">
							<input type="hidden" name="formID" value="'.$formID.'">
							<a href="javascript:deleteMainRecipient('.$recipientID.')" title="Remove">Remove</a>
						</form>
					</td>
				  </tr>';
			}
		}
		//IF NO MAIN RECIPIENTS ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Main Recipients available. <a href="'.$cms_root.'forms-manager/add-main-recipients.php?formID='.$formID.'" title="Add Main Recipient">Please add a main recipient here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // TOPIC RECIPIENTS ARCHITECTURE
    //#################################################################
	function topicRecipientsArchitecture($cms_root, $topicID, $formID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';
		$status = '';
		$currentUser = $_SESSION['cmsUser'];

		//GET ALL BLOG POSTS
		$result = $connector->query("SELECT * FROM forms_recipients WHERE deletedBy = ? AND formID = ? AND topicID = ? ORDER BY fullname ASC", array('0', $formID, $topicID));
		$topicRecipientTotal = $connector->numResults($result);

		//IF MAIN RECIPIENTS ARE AVAILABLE
		if($topicRecipientTotal != 0){
			while($row = $connector->fetchArray($result)){

				//SET VARIABLES
				$recipientID    = $row['recipientID'];
                $fullname       = $row['fullname'];

				//GENERATE OUPUT
				$txt.= '<tr>
					<td>'.$fullname.'</td>
					<td align="center">
						<a href="'.$cms_root.'forms-manager/edit-topic-recipients.php?formID='.$formID.'&recipientID='.$recipientID.'&topicID='.$topicID.'" title="Modify">Modify</a>
					</td>
					<td align="center">
                        <form name="delete_topic_recipient'.$recipientID.'">
							<input type="hidden" name="delete_topic_recipient" value="1">
							<input type="hidden" name="recipientID" value="'.$recipientID.'">
							<input type="hidden" name="formID" value="'.$formID.'">
                            <input type="hidden" name="topicID" value="'.$topicID.'">
							<a href="javascript:deleteTopicRecipient('.$recipientID.')" title="Remove">Remove</a>
						</form>
					</td>
				  </tr>';
			}
		}
		//IF NO MAIN RECIPIENTS ARE AVAILABLE
		else{
			$txt.= '<tr>
				<td colspan="6">There are currently no Topic Recipients available. <a href="'.$cms_root.'forms-manager/add-topic-recipients.php?formID='.$formID.'&topicID='.$topicID.'" title="Add Topic Recipient">Please add a topic recipient here!</a></td>
			</tr>';
		}

		//OUTPUT
		return $txt;

	}

    //#################################################################
    // UPDATE FORM
    //#################################################################
	function updateForm($title, $modifiedDate, $modifiedBy, $modifiedNumber, $formID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP INFO
		$title			= strip_tags($title);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//UPDATE FORM
		$update			= $connector->query("UPDATE forms SET
                                            formName        = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE formID = ?",
                                            array($title, $modifiedBy, $modifiedNumber, $modifiedDate, $formID));

	}

	//#################################################################
    // ADD TOPIC
    //#################################################################
	function addTopic($title, $form){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$title			= strip_tags($title);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//ADD TOPIC
		$insert = $connector->query("INSERT INTO form_topics (formID, topicName, createdBy, createdDate)
									VALUES (?, ?, ?, ?)",
									array($form, $title, $currentUser, $currentDate));

	}

    //#################################################################
    // ADD MAIN RECIPIENT
    //#################################################################
	function addMainRecipient($name, $email, $formID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name			= strip_tags($name);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//ADD TOPIC
		$insert = $connector->query("INSERT INTO forms_recipients (formID, fullname, email, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?)",
									array($formID, $name, $encrypted_email, $currentUser, $currentDate));

	}

    //#################################################################
    // ADD TOPIC RECIPIENT
    //#################################################################
	function addTopicRecipient($name, $email, $formID, $topicID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

		//CURRENT LOGGED IN USER
		$currentUser = $_SESSION['cmsUser'];
		$currentDate = date('Y-m-d H:i:s');

		//STRIP INFO
		$name			= strip_tags($name);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//ADD TOPIC
		$insert = $connector->query("INSERT INTO forms_recipients (formID, topicID, fullname, email, createdBy, createdDate)
									VALUES (?, ?, ?, ?, ?, ?)",
									array($formID, $topicID, $name, $encrypted_email, $currentUser, $currentDate));

	}

    //#################################################################
    // UPDATE TOPIC
    //#################################################################
	function updateTopic($title, $form, $modifiedDate, $modifiedBy, $modifiedNumber, $topicID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//STRIP INFO
		$title			= strip_tags($title);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//UPDATE TOPIC
		$update			= $connector->query("UPDATE form_topics SET
                                            topicName       = ?,
                                            formID          = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE topicID = ?",
                                            array($title, $form, $modifiedBy, $modifiedNumber, $modifiedDate, $topicID));

	}

    //#################################################################
    // UPDATE MAIN RECIPIENT
    //#################################################################
	function updateMainRecipient($name, $email, $modifiedDate, $modifiedBy, $modifiedNumber, $recipientID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

		//STRIP INFO
		$name			= strip_tags($name);

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//UPDATE TOPIC
		$update			= $connector->query("UPDATE forms_recipients SET
                                            fullname        = ?,
                                            email           = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE recipientID = ?",
                                            array($name, $encrypted_email, $modifiedBy, $modifiedNumber, $modifiedDate, $recipientID));

	}

    //#################################################################
    // UPDATE TOPIC RECIPIENT
    //#################################################################
	function updateTopicRecipient($name, $email, $modifiedDate, $modifiedBy, $modifiedNumber, $recipientID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

		//STRIP INFO
		$name			= strip_tags($name);

        //ENCRYPT EMAIL
        $encrypted_email = $encryptDecrypt->encrypt($email, ENCRYPTION_KEY);

        //SET TO ALLOW ZERO VALUES
        $connector->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"', array());

		//UPDATE TOPIC
		$update			= $connector->query("UPDATE forms_recipients SET
                                            fullname        = ?,
                                            email           = ?,
                                            modifiedBy      = ?,
                                            modifiedNumber  = ?,
                                            modifiedDate    = ?
                                            WHERE recipientID = ?",
                                            array($name, $encrypted_email, $modifiedBy, $modifiedNumber, $modifiedDate, $recipientID));

	}

    //#################################################################
    // GET ALL FORMS
    //#################################################################
    function getFormsForSelectStatement(){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT VARIABLES
        $txt    = '';

        //GET ALL FORMS
        $result = $connector->query("SELECT * FROM forms WHERE deletedBy = ? ORDER BY formName ASC", array('0'));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLES
            $formID     = $row['formID'];
            $formName   = $row['formName'];

            //GENERATE OUTPUT
            $txt.= '<option value="'.$formID.'">'.$formName.'</option>';
        }

        //RETURN OUTPUT
        return $txt;

    }

    //#################################################################
    // GET ALL FORMS FOR FILTER
    //#################################################################
    function getFormsForFilter($filter_form_select){
        //CONNECT TO DATABASE
		$connector = new dbConnector();

        //SET DEFAULT VARIABLES
        $txt    = '';

        //GET ALL FORMS
        $result = $connector->query("SELECT * FROM forms WHERE deletedBy = ? ORDER BY formName ASC", array('0'));
        while($row    = $connector->fetchArray($result)){
            //SET VARIABLES
            $formID     = $row['formID'];
            $formName   = $row['formName'];

            //GENERATE OUTPUT
            if($filter_form_select == $formID){
                $txt.= '<option value="'.$formID.'" selected="selected">'.$formName.'</option>';
            }else{
                $txt.= '<option value="'.$formID.'">'.$formName.'</option>';
            }
        }

        //RETURN OUTPUT
        return $txt;

    }

    //#################################################################
	// GEO GRAPH INFO AVAILABLE
    //#################################################################
	function geoGraphInfoAvailable(){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

        $startDate = date('Y-m', strtotime("-365 days")).'-01 00:00:00';
		$endDate = date("Y-m").'-31 23:59:59';
		$default = $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) ORDER BY date_time ASC LIMIT 0,1", array($startDate, $endDate));
        $total  = $connector->numResults($default);

        //RETURN TOTAL
		return $total;
	}

    //#################################################################
	// GET DEFAULT GRAPH INFO
    //#################################################################
	function getDefaultGraphInfo(){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

        $startDate = date('Y-m', strtotime("-365 days")).'-01 00:00:00';
		$endDate = date("Y-m").'-31 23:59:59';
		$default = $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) AND deletedBy = ? ORDER BY date_time ASC", array($startDate, $endDate, 0));

		//CREATE START ARRAY WITH CORRECT VALUES
		$array = array();
		while($defaultRow= $connector->fetchArray($default)){
			$date = explode(' ',$defaultRow['date_time']);
			$array[].= date("F Y",strtotime(substr($date[0],0,7)));
		}
		$dates_array = array_count_values($array);

		//GET MONTHS BETWEEN 2 DATES
		$count = 0;

		$label = '';
		$values = '';
		$comp_values = '';
		$current = strtotime($startDate);
		$last = strtotime($endDate);
		while( $current <= $last ) {

			if($count == $months){
				$label.= '"'.date("F Y", $current).'",';

				//CHECK IF KEY EXISTS FOR START VALUES
				if(array_key_exists(date("F Y", $current), $dates_array)){
					$values.= $dates_array[date("F Y", $current)].',';
				}else{
					$values.= '0,';
				}

				$count++;
			}else{
				$label.= '"'.date("F Y", $current).'",';

				//CHECK IF KEY EXISTS FOR START VALUES
				if(array_key_exists(date("F Y", $current), $dates_array)){
					$values.= $dates_array[date("F Y", $current)].',';
				}else{
					$values.= '0,';
				}

				$count++;
			}

			$current = strtotime('+1 month', $current);
		}

		//FINALISE THE VALUES
		if(substr($label, -1) == ','){
			$label = substr($label,0,-1);
		}
		$values = substr($values,0,-1);

		return $label.'::'.$values;
	}

    //#################################################################
	// GET GEO CHART INFO
    //#################################################################
	function getGeoChartInfo(){
        //CONNECT TO DATABASE
        $connector 	= new DbConnector();

        //SET VARIABLE TO HOLD INFORMATION
    	$country_values = '';
        $count          = 1;
        $startDate      = date('Y-m', strtotime("-365 days")).'-01 00:00:00';
        $endDate        = date("Y-m").'-31 23:59:59';

    	//GET DATES FROM DATABASE
    	$default 	= $connector->query("SELECT * FROM forms_info WHERE (date_time BETWEEN ? AND ?) $form_search ORDER BY date_time ASC", array($startDate, $endDate));

        //CREATE ARRAY WITH ALL VALUES COUNTED
    	$countryArray = array();
    	while($defaultRow	= $connector->fetchArray($default)){
    		$country 		= explode(' ',$defaultRow['country']);
    		$countryArray[].= $country[0];
    	}
    	$countries_array = array_count_values($countryArray);

        //SORT ARRAY HIGHEST TO LOWEST VALUE
    	arsort($countries_array);

    	//GET LENGTH OF ARRAY - NEEDED LATER FOR GETTING "OTHER" RESULTS
    	$array_length = count($countries_array);

        //SET VALUES ARRAY
        $country_values = '';

        //LOOP THROUGH THE NEWLY CREATED ARRAY
    	foreach($countries_array as $key => $value){
    		//GET NAME FOR KEY
    		$name_selector = $connector->query("SELECT * FROM all_countries WHERE code = ?", array($key));
    		$name		   = $connector->fetchArray($name_selector);
    		$key		   = $name['name_en'];

            //GENERATE OUTPUT
            if($count == 1){
                $country_values.= "['Country','Forms Submitted'],";
                $count++;
            }

            //ADD COUNTRIES TO MULTIDIMENSIONAL ARRAY
            $country_values.= "['".$key."',".$value."],";

    	}

        //RETURN ARRAY
        $country_values = substr($country_values,0,-1);
        return "[".$country_values."]";
    }

    //#################################################################
    // GET ALL FORM CUSTOMER NAMES
    //#################################################################
	function getAllFormCustomerNames(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL CUSTOMER NAMES
		$result = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? GROUP BY fullname ORDER BY fullname ASC", array(0));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $infoID     = $row['infoID'];
            $fullname   = $this->HTMLEntityToSpecialCharacters($row['fullname']);

            //GENERATE OUTPUT
            $txt.= '"'.$fullname.'",';

		}

        //REUTN OUTPUT
		return substr($txt, 0, -1);
	}

    //#################################################################
    // GET ALL FORM CUSTOMER EMAILS
    //#################################################################
	function getAllFormEmails(){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL CUSTOMER NAMES
		$result = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? GROUP BY email ORDER BY email ASC", array(0));
		while($row	= $connector->fetchArray($result)){
            //SET VARIABLES
            $infoID = $row['infoID'];
            $email  = $row['email'];

            //DECRYPT EMAIL
            $decrypted_email = $encryptDecrypt->decrypt($email, ENCRYPTION_KEY);

            //GENERATE OUTPUT
            $txt.= '"'.$decrypted_email.'",';

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
    // GET ALL EMAIL RESULTS
    //#################################################################
	function getEmailResults($customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order, $limit){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $customerNameString = '';
        $customerEmailString = '';
        $formSQL = '';
        $dateSQL = '';

        //SET PARAMETER
        //CUSTOMER NAMES
        if($customer_names != '' && $customer_names != '' && $customer_names != ','){
            //CLEAN STRING
            $customer_names = substr($customer_names, 1, -1);

            //TURN INTO ARRAY
            $customerNamesArray = explode(',', $customer_names);

            //LOOP THROUGH ARRAY
            foreach($customerNamesArray as $customerName){
                $customerNameString.= "'".$customerName."',";
            }

            //CLEAN UP NEW STRING
            $customerNameString = substr($customerNameString, 0, -1);

            //CREATE SQL STRING
            $customerSQL = 'AND fullname in ('.$customerNameString.')';
        }

        //CUSTOMER EMAILS
        if($customer_emails != '' && $customer_emails != '' && $customer_emails != ','){
            //CLEAN STRING
            $customer_emails = substr($customer_emails, 1, -1);

            //TURN INTO ARRAY
            $customerEmailsArray = explode(',', $customer_emails);

            //LOOP THROUGH ARRAY
            foreach($customerEmailsArray as $customerEmail){
                $encrypted_email    = $encryptDecrypt->encrypt($customerEmail, ENCRYPTION_KEY);

                $customerEmailString.= "'".$encrypted_email."',";
            }

            //CLEAN UP NEW STRING
            $customerEmailString = substr($customerEmailString, 0, -1);

            //CREATE SQL STRING
            $emailSQL = 'AND email in ('.$customerEmailString.')';
        }

        //FORM
        if($filter_form_select != 0 && $filter_form_select != '' && $filter_form_select != ' '){
            //CREATE SQL STRING
            $formSQL = 'AND formID = '.$filter_form_select;
        }

        //DATES
        if(($filter_start_date != '0000-00-00' && $filter_start_date != '' && $filter_start_date != ' ') && ($filter_end_date != '0000-00-00' && $filter_end_date != '' && $filter_end_date != ' ')){

            $dateSQL = 'AND date_time >= '.$filter_start_date.' AND date_time <= '.$filter_end_date;

        }elseif($filter_start_date != '0000-00-00' && $filter_start_date != '' && $filter_start_date != ' '){

            $dateSQL = 'AND date_time >='.$filter_start_date;

        }

        //ORDER
        if($filter_order == '' || $filter_order == ' '){
            $filter_order = 'desc';
        }

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL CUSTOMER NAMES
		$result = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? $customerSQL $emailSQL $formSQL $dateSQL ORDER BY date_time $filter_order LIMIT 0, $limit", array(0));
        $total  = $connector->numResults($result);

        //RESULTS HAVE BEEN FOUND
        if($total != 0){
    		while($row	= $connector->fetchArray($result)){
                //SET VARIABLES
                $infoID     = $row['infoID'];
                $email      = $row['email'];
                $tel        = $row['tel'];
                $fullname   = $row['fullname'];
                $date_time  = $row['date_time'];
                $content    = $row['content'];

                //DECRYPT INFO
                $decrypted_email    = $encryptDecrypt->decrypt($email, ENCRYPTION_KEY);
                $decrypted_content  = $encryptDecrypt->decrypt($content, ENCRYPTION_KEY);

                //CONVERT DATE
                $convertedDate      =  date('j F Y', strtotime($date_time));

                //GENERATE OUTPUT
                $txt.= '<div class="email-result-holder">
                    <div class="email-result-name">
                        <strong>Name:</strong> '.$fullname.'
                    </div>
                    <div class="email-result-date">
                        <strong>Date:</strong> '.$convertedDate.'
                    </div>
                    <div class="clear"></div>

                    <div class="email-result-email">
                        <strong>Email:</strong> '.$decrypted_email.'
                    </div>
                    <div class="email-result-contact">
                        <strong>Contact Number:</strong> '.$tel.'
                    </div>
                    <div class="clear"></div>

                    <div class="email-result-content">
                        <strong>Content</strong>
                    </div>

                    '.$decrypted_content.'
                </div>';

    		}
        }
        //NO RESULTS
        else{
            $txt = '<span class="empty-category-text">No results have been found for your supplied filter settings. Please try to adjust your settings.</span>';
        }

        //REUTN OUTPUT
		return $txt;
	}

    //#################################################################
    // GET TOTAL EMAIL RESULTS
    //#################################################################
	function getTotalNumberEmails($customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order, $limit){
		//CONNECT TO DATABASE
		$connector = new dbConnector();
        $encryptDecrypt = new encryptDecrypt();

        //DEFINE ENCRYPTION_KEY
        define("ENCRYPTION_KEY", "%@#!^&$*");

        //DEFAULT VARIABLES
        $customerNameString = '';
        $customerEmailString = '';
        $formSQL = '';
        $dateSQL = '';

        //SET PARAMETER
        //CUSTOMER NAMES
        if($customer_names != '' && $customer_names != '' && $customer_names != ','){
            //CLEAN STRING
            $customer_names = substr($customer_names, 1, -1);

            //TURN INTO ARRAY
            $customerNamesArray = explode(',', $customer_names);

            //LOOP THROUGH ARRAY
            foreach($customerNamesArray as $customerName){
                $customerNameString.= "'".$customerName."',";
            }

            //CLEAN UP NEW STRING
            $customerNameString = substr($customerNameString, 0, -1);

            //CREATE SQL STRING
            $customerSQL = 'AND fullname in ('.$customerNameString.')';
        }

        //CUSTOMER EMAILS
        if($customer_emails != '' && $customer_emails != '' && $customer_emails != ','){
            //CLEAN STRING
            $customer_emails = substr($customer_emails, 1, -1);

            //TURN INTO ARRAY
            $customerEmailsArray = explode(',', $customer_emails);

            //LOOP THROUGH ARRAY
            foreach($customerEmailsArray as $customerEmail){
                $encrypted_email    = $encryptDecrypt->encrypt($customerEmail, ENCRYPTION_KEY);

                $customerEmailString.= "'".$encrypted_email."',";
            }

            //CLEAN UP NEW STRING
            $customerEmailString = substr($customerEmailString, 0, -1);

            //CREATE SQL STRING
            $emailSQL = 'AND email in ('.$customerEmailString.')';
        }

        //FORM
        if($filter_form_select != 0 && $filter_form_select != '' && $filter_form_select != ' '){
            //CREATE SQL STRING
            $formSQL = 'AND formID = '.$filter_form_select;
        }

        //DATES
        if(($filter_start_date != '0000-00-00' && $filter_start_date != '' && $filter_start_date != ' ') && ($filter_end_date != '0000-00-00' && $filter_end_date != '' && $filter_end_date != ' ')){

            $dateSQL = 'AND date_time >= '.$filter_start_date.' AND date_time <= '.$filter_end_date;

        }elseif($filter_start_date != '0000-00-00' && $filter_start_date != '' && $filter_start_date != ' '){

            $dateSQL = 'AND date_time >='.$filter_start_date;

        }

		//SET DEFAULT VARIABLES
		$txt = '';

		//GET ALL CUSTOMER NAMES
		$result = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? $customerSQL $emailSQL $formSQL $dateSQL ORDER BY date_time $filter_order", array(0));
        $total  = $connector->numResults($result);

        //REUTN TOTAL
		return $total;
	}
}

//DEFINE CLASS
$formsManager = new formsManager();

//#################################################################
// EDIT FORM
//#################################################################
if(isset($_POST['edit_form'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $formID           = $_POST['formID'];
	$title            = $_POST['form-name'];

    $modifiedDate	  = $_POST['modifiedDate'];
	$modifiedBy		  = $_SESSION['cmsUser'];
	$modifiedNumber	  = $_POST['modifiedNumber'];

	//HONEY POTS
	$form_type        = $_POST['form-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title  = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
    $v = new formValidation();
    $v->validateString($title, 'Topic Name', 1, 200);

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($form_type == ''){

            //CHECK IF FORM ALREADY EXISTS IN DATABSE
            $form_used = $formsManager->editFormCheck($title);
            if($form_used == 'unused'){

    			//UPDATE FORM INSIDE DATABASE
    			$formsManager->updateForm($title, $modifiedDate, $modifiedBy, $modifiedNumber, $formID);

    			//REDIRECT USER
    			header("Location: ".$cms_root."forms-manager/index.php?message=4");
        		exit;

            }else{
                //SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Form Name</b> you supplied is already use. Please try another!</li></ul>';
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
// ADD TOPIC
//#################################################################
if(isset($_POST['add_topic'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$title		   = $_POST['topic-name'];
	$form 		   = $_POST['topic-main-form'];

	//HONEY POTS
	$topic_type    = $_POST['topic-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title  = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
	$v = new formValidation();
    $v->validateString($title, 'Topic Name', 1, 200);
	$v->validateDropDown($form, 'Main Form');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($topic_type == ''){

            //CHECK IF TOPIC ALREADY EXISTS FOR FORM
            $topic_used = $formsManager->addTopicCheck($title, $form);
            if($topic_used == 'unused'){

    			//INSERT TOPIC INTO DATABASE
    			$formsManager->addTopic($title, $form);

                //REDIRECT USER
    			header("Location: ".$cms_root."forms-manager/index.php?message=1");
        		exit;

            }else{
                //SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Topic Name</b> you supplied is already used by the selected form. Please try another!</li></ul>';
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
// EDIT TOPIC
//#################################################################
if(isset($_POST['edit_topic'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$topicID	       = $_POST['topicID'];
    $formID            = $_POST['formID'];
	$title             = $_POST['topic-name'];
	$form 		       = $_POST['topic-main-form'];

    $modifiedDate	  = $_POST['modifiedDate'];
	$modifiedBy		  = $_SESSION['cmsUser'];
	$modifiedNumber	  = $_POST['modifiedNumber'];

	//HONEY POTS
	$topic_type       = $_POST['topic-type'];

    //CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
    $title  = $userLogin->specialCharactersToHTMLEntity($title);

	//VALIDATION
    $v = new formValidation();
    $v->validateString($title, 'Topic Name', 1, 200);
	$v->validateDropDown($form, 'Main Form');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($topic_type == ''){

            //CHECK IF TOPIC ALREADY EXISTS FOR FORM
            $topic_used = $formsManager->addTopicCheck($title, $form);
            if($topic_used == 'unused'){

    			//UPDATE TOPIC INSIDE DATABASE
    			$formsManager->updateTopic($title, $form, $modifiedDate, $modifiedBy, $modifiedNumber, $topicID);

    			//REDIRECT USER
    			header("Location: ".$cms_root."forms-manager/index.php?message=2");
        		exit;

            }else{
                //SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Topic Name</b> you supplied is already used by the selected form. Please try another!</li></ul>';
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
// ADD MAIN RECIPIENT
//#################################################################
if(isset($_POST['add_main_recipient'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $formID        = $_POST['formID'];
	$name		   = $_POST['main-recipient-name'];
	$email 		   = $_POST['main-recipient-email'];

	//HONEY POTS
	$main_recipient_type    = $_POST['main-recipient-type'];

	//VALIDATION
	$v = new formValidation();
    $v->validateString($name, 'Main Recipient Name', 1, 200);
	$v->validateEmailAddress($email, 'Main Recipient Email');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($main_recipient_type == ''){

            //CHECK IF EMAIL ALREADY EXISTS FOR FORM
            $email_used = $formsManager->addMainRecipientCheck($email, $formID);
            if($email_used == 'unused'){

    			//INSERT MAIN RECIPIENT INTO DATABASE
    			$formsManager->addMainRecipient($name, $email, $formID);

                //REDIRECT USER
    			header("Location: ".$cms_root."forms-manager/manage-main-recipients.php?formID=".$formID."&message=5");
        		exit;

            }else{
                //SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Email Address</b> you supplied is already used by the selected form. Please try another!</li></ul>';
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
// EDIT MAIN RECIPIENT
//#################################################################
if(isset($_POST['edit_main_recipient'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$recipientID       = $_POST['recipientID'];
    $formID            = $_POST['formID'];
	$name              = $_POST['main-recipient-name'];
	$email 		       = $_POST['main-recipient-email'];

    $modifiedDate	  = $_POST['modifiedDate'];
	$modifiedBy		  = $_SESSION['cmsUser'];
	$modifiedNumber	  = $_POST['modifiedNumber'];

	//HONEY POTS
	$main_recipient_type       = $_POST['main-recipient-type'];

    //VALIDATION
	$v = new formValidation();
    $v->validateString($name, 'Main Recipient Name', 1, 200);
	$v->validateEmailAddress($email, 'Main Recipient Email');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($topic_type == ''){

            //CHECK IF CONTENT HAS BEEN CHANGED
			if($formsManager->checkMainRecipientChanges($name, $email, $recipientID) == 'changed'){

                //CHECK IF EMAIL HAS ALREADY BEEN USED
                $email_used = $formsManager->addMainRecipientCheck($email, $formID);
                if($email_used == 'unused'){

        			//UPDATE MAIN RECIPIENT INSIDE DATABASE
        			$formsManager->updateMainRecipient($name, $email, $modifiedDate, $modifiedBy, $modifiedNumber, $recipientID);

        			//REDIRECT USER
        			header("Location: ".$cms_root."forms-manager/manage-main-recipients.php?formID=".$formID."&message=6");
            		exit;

                }else{
                    //SET ERROR MESSAGE
    				$error_message = 'There was an error!';
    				$errors = '<ul class="errors"><li>The <b>Email Address</b> you supplied is already used by the selected form. Please try another!</li></ul>';
                }
            }
            //NO CONTENT HAS BEEN CHANGED
            else{
                //REDIRECT USER
				header("Location: ".$cms_root."forms-manager/manage-main-recipients.php?formID=".$formID);
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
// ADD TOPIC RECIPIENT
//#################################################################
if(isset($_POST['add_topic_recipient'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $formID        = $_POST['formID'];
    $topicID       = $_POST['topicID'];
	$name		   = $_POST['topic-recipient-name'];
	$email 		   = $_POST['topic-recipient-email'];

	//HONEY POTS
	$topic_recipient_type  = $_POST['topic-recipient-type'];

	//VALIDATION
	$v = new formValidation();
    $v->validateString($name, 'Topic Recipient Name', 1, 200);
	$v->validateEmailAddress($email, 'Topic Recipient Email');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($topic_recipient_type == ''){

            //CHECK IF EMAIL ALREADY EXISTS FOR FORM
            $email_used = $formsManager->addTopicRecipientCheck($email, $formID, $topicID);
            if($email_used == 'unused'){

    			//INSERT TOPIC RECIPIENT INTO DATABASE
    			$formsManager->addTopicRecipient($name, $email, $formID, $topicID);

                //REDIRECT USER
    			header("Location: ".$cms_root."forms-manager/manage-topic-recipient.php?topicID=".$topicID."&formID=".$formID."&message=8");
        		exit;

            }else{
                //SET ERROR MESSAGE
				$error_message = 'There was an error!';
				$errors = '<ul class="errors"><li>The <b>Email Address</b> you supplied is already used by the selected topic. Please try another!</li></ul>';
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
// EDIT TOPIC RECIPIENT
//#################################################################
if(isset($_POST['edit_topic_recipient'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
	$recipientID       = $_POST['recipientID'];
    $formID            = $_POST['formID'];
    $topicID           = $_POST['topicID'];
	$name              = $_POST['topic-recipient-name'];
	$email 		       = $_POST['topic-recipient-email'];

    $modifiedDate	  = $_POST['modifiedDate'];
	$modifiedBy		  = $_SESSION['cmsUser'];
	$modifiedNumber	  = $_POST['modifiedNumber'];

	//HONEY POTS
	$topic_recipient_type      = $_POST['topic-recipient-type'];

    //VALIDATION
	$v = new formValidation();
    $v->validateString($name, 'Topic Recipient Name', 1, 200);
	$v->validateEmailAddress($email, 'Topic Recipient Email');

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

		//CHECK IF ALL CONDITONS HAVE BEEN MET
		if($topic_recipient_type == ''){

            //CHECK IF CONTENT HAS BEEN CHANGED
			if($formsManager->checkTopicRecipientChanges($name, $email, $recipientID) == 'changed'){

                //CHECK IF EMAIL HAS ALREADY BEEN USED
                $email_used = $formsManager->addTopicRecipientCheck($email, $formID);
                if($email_used == 'unused'){

        			//UPDATE TOPIC RECIPIENT INSIDE DATABASE
        			$formsManager->updateTopicRecipient($name, $email, $modifiedDate, $modifiedBy, $modifiedNumber, $recipientID);

        			//REDIRECT USER
        			header("Location: ".$cms_root."forms-manager/manage-topic-recipient.php?topicID=".$topicID."&formID=".$formID."&message=9");
            		exit;

                }else{
                    //SET ERROR MESSAGE
    				$error_message = 'There was an error!';
    				$errors = '<ul class="errors"><li>The <b>Email Address</b> you supplied is already used by the selected topic. Please try another!</li></ul>';
                }
            }
            //NO CONTENT HAS BEEN CHANGED
            else{
                //REDIRECT USER
				header("Location: ".$cms_root."forms-manager/manage-topic-recipient.php?topicID=".$topicID."&formID=".$formID);
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
//DELETE TOPIC
//#################################################################
if(isset($_POST['delete_topic'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
    $topicID	= $_POST['topicID'];

    //SET USER AS REMOVED IN DATABASE
    $formsManager->deleteTopic($topicID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."forms-manager/index.php?message=3");
    exit;
}

//#################################################################
//DELETE MAIN RECIPIENT
//#################################################################
if(isset($_POST['delete_main_recipient'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$recipientID	= $_POST['recipientID'];
    $formID         = $_POST['formID'];

    //SET USER AS REMOVED IN DATABASE
    $formsManager->deleteMainRecipient($recipientID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."forms-manager/manage-main-recipients.php?formID=".$formID."&message=7");
    exit;
}

//#################################################################
//DELETE TOPIC RECIPIENT
//#################################################################
if(isset($_POST['delete_topic_recipient'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

    //GET POSTED VARIABLES POSTS
	$recipientID	= $_POST['recipientID'];
    $formID         = $_POST['formID'];
    $topicID        = $_POST['topicID'];

    //SET USER AS REMOVED IN DATABASE
    $formsManager->deleteTopicRecipient($recipientID);

    //REDIRECT PAGE WITH MESSAGE
    header("Location: ".$cms_root."forms-manager/manage-topic-recipient.php?topicID=".$topicID."&formID=".$formID."&message=10");
    exit;
}

//#################################################################
// FILTER EMAILS
//#################################################################
if(isset($_POST['filter-emails'])){
	//CONNECT TO DATABASE
	$connector = new dbConnector();

	//GET POSTED VALUES
    $form       = $_POST['forms-select'];
    $startDate  = $_POST['filter-start-date'];
	$endDate    = $_POST['filter-end-date'];
	$order      = $_POST['forms-order'];
    $names      = $_POST['customer_names'];
    $emails     = $_POST['customer_emails'];

    //VALIDATION
	$v = new formValidation();
    //IF FORM IS SUPPLIED
    if($form != 0 && $form != '' && $form != ' '){
        $v->validateDropDown($form, 'Form');
    }

    //IF A DATE IS SUPPLIED
    if(($startDate != '0000-00-00' && $startDate != '' && $startDate != ' ') || ($endDate != '0000-00-00' && $endDate != '' && $endDate != ' ')){
        $v->validateStartEndDates($startDate, $endDate, 'Date');
    }

    //IF ORDER IS SUPPLIED
    if($order != 0 && $order != '' && $order != ' '){
        $v->validateDropDown($order, 'Order');
    }

    //IF NAMES ARE SUPPLIED
    if($names != '' && $names != ' ' && $names != ','){
        $v->validateTags($names, 'Customer Name');
    }

    //IF EMAIL ARE SUPPLIED
    if($emails != '' && $emails != ' ' && $emails != ','){
        $v->validateTags($emails, 'Customer Email');
    }

	//CHECK IF NO ERROR HAVE BEEN FOUND
	if(!$v->hasErrors()){

        //REDIRECT USER
		header("Location: ".$cms_root."forms-manager/view-emails.php?customer_names=".$names."&customer_emails=".$emails."&forms-select=".$form."&filter-start-date=".$startDate."&filter-end-date=".$endDate."&forms-order=".$order);
		exit;

	}
	//ERRORS HAVE BEEN FOUND
	else{
		//SET ERROR MESSAGE
		$error_message = $v->errorCMSMessage();
		$errors = $v->showErrors();
	}

}
?>
