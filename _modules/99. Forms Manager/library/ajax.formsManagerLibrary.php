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
require_once("class.encryptDecrypt.php");

class ajaxMerchantLibrary extends DbConnector{
	//#################################################################
    // DO NOT CHANGE CODE BELOW
    //#################################################################
    function __construct(){}
    function __destruct(){unset($connector);}

    //#################################################################
    //ESCAPE CERTAIN CHARACTERS FOR SAFER QUERIES
    //#################################################################
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
    // GET CATEGORY INFORMATION
    //#################################################################
	function getCategoryInfo($productCatID, $field){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//GET CATEGORY INFO
		$result = $connector->query("SELECT * FROM product_category WHERE productCatID = ?", array($productCatID));
		$row	= $connector->fetchArray($result);

		//RETURN VAlUE
		return $row[$field];

	}

    //#################################################################
    // GET TOTAL EMAIL RESULTS
    //#################################################################
	function getTotalNumberEmails($customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order){
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
		$result = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? $customerSQL $emailSQL $formSQL $dateSQL ORDER BY date_time $filter_order", array(0));
        $total  = $connector->numResults($result);

        //REUTN TOTAL
		return $total;
	}

	//#################################################
	//FETCH EMAILS
	//#################################################
	function fetchEmails($pagenum, $customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order, $cms_root){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();
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

		//ONLY SHOW LOAD BUTTON AT THE BEGINNING
		if($pagenum != 1){

		    $rowsperpage = 25; //MAXIMUM RESULTS PER PAGE
		    $offset = ($pagenum-1) * $rowsperpage; //WHERE THE RESULTS START FROM

		    //FOR RESULTS OF THE PAGE
		    $q = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? $customerSQL $emailSQL $formSQL $dateSQL ORDER BY date_time $filter_order LIMIT $offset, $rowsperpage", array('0'));

		    $total_q = $connector->query("SELECT * FROM forms_info WHERE deletedBy = ? $customerSQL $emailSQL $formSQL $dateSQL ORDER BY date_time $filter_order", array('0'));//FOR ALL RESULTS
		    $total_nums = $connector->numResults($total_q); //TOTAL NUMBER OF RESULTS
		    $total_pages = ceil($total_nums/$rowsperpage); //NUMBER OF PAGES

		    //IF PAGE NUMBER IS WITHIN THE FIRST AND LAST PAGES...
		    if($pagenum >= 1 && $pagenum <= $total_pages)
		    {

		    	while($row	= $connector->fetchArray($q)){
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
                    echo '<div class="email-result-holder">
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
		}
	}
}

$ajaxMerchantLibrary = new ajaxMerchantLibrary();
?>
