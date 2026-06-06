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

class ajaxProductLibrary extends DbConnector{
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

	//#################################################
	//GET TOTAL NUMBER OF PRODUCTS
	//#################################################
	function getTotalNumberProducts(){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

		$total_q_products = $connector->query("SELECT * FROM product WHERE deletedBy = ? ORDER BY productTitle ASC", array('0'));

		$total_nums_products = $connector->numResults($total_q_products); //TOTAL NUMBER OF RESULTS

		//RETURN TOTAL
		return $total_nums_products;
	}

	//#################################################
	//FETCH PRODUCTS
	//#################################################
	function fetchProducts($pagenum, $cms_root){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

		//DEFAULT VARIABLES
		$currentDate = date('Y-m-d');

		//ONLY SHOW LOAD BUTTON AT THE BEGINNING
		if($pagenum != 1){

		    $rowsperpage = 40; //MAXIMUM RESULTS PER PAGE
		    $offset = ($pagenum-1) * $rowsperpage; //WHERE THE RESULTS START FROM

		    //FOR RESULTS OF THE PAGE
		    $q = $connector->query("SELECT * FROM product WHERE deletedBy = ? ORDER BY productTitle ASC LIMIT $offset, $rowsperpage", array('0'));

		    $total_q = $connector->query("SELECT * FROM product WHERE deletedBy = ? ORDER BY productTitle ASC", array('0'));//FOR ALL RESULTS
		    $total_nums = $connector->numResults($total_q); //TOTAL NUMBER OF RESULTS
		    $total_pages = ceil($total_nums/$rowsperpage); //NUMBER OF PAGES

		    //IF PAGE NUMBER IS WITHIN THE FIRST AND LAST PAGES...
		    if($pagenum >= 1 && $pagenum <= $total_pages)
		    {
				//BEGIN TABLE
				echo '<table width="100%" class="module-architecture-table">';

		    	while($row	= $connector->fetchArray($q)){
                    //SET VARIABLES
                    $specialText    = '';
                    $categoryString = '';
    				$status			= '';
    				$status_bg		= '';
    				$date			= '';
    				$currentDate	= date('Y-m-d H:i:s');
    				$productID		= $row['productID'];
    				$productTitle	= $this->HTMLEntityToSpecialCharacters($row['productTitle']);
    				$productCatID	= $row['productCatID'];
                    $productSpecial = $row['productSpecial'];

                    //CHECK IF PRODUCT IS ON SPECIAL
                    if($productSpecial == 1){
                        $specialText = '(Special)';
                    }

                    //TURN INTO ARRAY
                    $productCatIDString = substr($productCatID, 1, -1);
                    $productCatIDArray  = explode(',', $productCatIDString);

                    //GET ALL PRODUCT CATEGORY NAMES
                    foreach($productCatIDArray AS $productCatIDs){
                        //GET CATEGORY NAME
                        $categoryString.= $this->getCategoryInfo($productCatIDs, 'categoryName').', ';
                    }

                    //CLEAN UP CATEGORY STRING
                    $categoryString = substr($categoryString, 0, -2);

    				//GET ALL PRODUCT CONTENT FOR A PRODUCT
    				$result2	= $connector->query("SELECT * FROM product_content WHERE productID = ? AND deletedBy = ?", array($productID, '0'));
    				$productContentTotal	= $connector->numResults($result2);

    				//IF PRODUCT IS EMPTY
    				if($productContentTotal == 0){
    					$status		= '<span class="empty-category-text">(Empty)</span>';
    					$status_bg	='class="empty-category"';
    				}

    				//GENERATE OUPUT
    				echo '<tr>
    					<td width="40%" '.$status_bg.'>'.$productTitle.' '.$status.' '.$specialText.'</td>
    					<td width="21%" '.$status_bg.'>'.$categoryString.'</td>
    					<td width="13%" '.$status_bg.' align="center">
    						<a href="'.$cms_root.'product-manager/manage-product.php?productID='.$productID.'" title="Manage">Manage</a>
    					</td>
    					<td width="13%" '.$status_bg.' align="center">
    						<a href="'.$cms_root.'product-manager/edit-product.php?productID='.$productID.'" title="Modify">Modify</a>
    					</td>
    					<td width="13%" '.$status_bg.' align="center">';

    					echo '<form name="delete_product'.$productID.'">
    							<input type="hidden" name="delete_product" value="1">
    							<input type="hidden" name="productID" value="'.$productID.'">
    							<a href="javascript:deleteProduct('.$productID.')" title="Remove">Remove</a>
    						</form>';

    					echo '</td>
    				  </tr>';
		        }

				//END TABLE
				echo '</table>';
		    }
		}
	}

    //#################################################################
    // GET PRODUCT SUB CATEGORIES
    //#################################################################
	function getProductSubCategories($productCatID){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $success = '';

        //ESCAPE VARIABLE
        $productCatID   = $connector->escape($productCatID);

		//GET PRODUCT SUB CATEGORIES
		$result = $connector->query("SELECT * FROM product_category WHERE productMainCatID = ? AND deletedBy = ? ORDER BY categoryName ASC", array($productCatID, 0));
        $total  = $connector->numResults($result);

        //SUB CATEGORIES AVAILABLE
        if($total != ''){
            while($row	= $connector->fetchArray($result)){
                //SET VARIABLES
                $productCatID   = $row['productCatID'];
                $categoryName   = $row['categoryName'];

                //GENERATE OUTPUT
                $txt.= '<option value="'.$productCatID.'">'.$categoryName.'</option>';
            }

            //SET SUCCESS MESSAGE
            $success = 'success';
        }else{
            //SET SUCCESS MESSAGE
            $success = 'failed';
        }

		//RETURN OUTPUT
		return $success.'#@#'.$txt;

	}
}

$ajaxProductLibrary = new ajaxProductLibrary();
?>
