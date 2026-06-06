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

class ajaxEventLibrary extends DbConnector{
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

	//#################################################
	//GET TOTAL NUMBER OF EVENTS
	//#################################################
	function getTotalNumberEvents(){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

		$total_q_events = $connector->query("SELECT * FROM events WHERE deletedBy = ? ORDER BY startDate DESC", array('0'));

		$total_nums_events = $connector->numResults($total_q_events); //TOTAL NUMBER OF RESULTS

		//RETURN TOTAL
		return $total_nums_events;
	}

	//#################################################
	//FETCH EVENTS
	//#################################################
	function fetchEvents($pagenum, $cms_root){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

		//DEFAULT VARIABLES
		$currentDate = date('Y-m-d');

		//ONLY SHOW LOAD BUTTON AT THE BEGINNING
		if($pagenum != 1){

		    $rowsperpage = 40; //MAXIMUM RESULTS PER PAGE
		    $offset = ($pagenum-1) * $rowsperpage; //WHERE THE RESULTS START FROM

		    //FOR RESULTS OF THE PAGE
		    $q = $connector->query("SELECT * FROM events WHERE deletedBy = ? ORDER BY startDate DESC LIMIT $offset, $rowsperpage", array('0'));

		    $total_q = $connector->query("SELECT * FROM events WHERE deletedBy = ? ORDER BY startDate DESC", array('0'));//FOR ALL RESULTS
		    $total_nums = $connector->numResults($total_q); //TOTAL NUMBER OF RESULTS
		    $total_pages = ceil($total_nums/$rowsperpage); //NUMBER OF PAGES

		    //IF PAGE NUMBER IS WITHIN THE FIRST AND LAST PAGES...
		    if($pagenum >= 1 && $pagenum <= $total_pages)
		    {
				//BEGIN TABLE
				echo '<table width="100%" class="module-architecture-table">';

		    	while($row	= $connector->fetchArray($q)){
					//SET VARIABLES
					$status			= '';
					$status_bg		= '';
					$date			= '';
					$currentDate	= date('Y-m-d H:i:s');
    				$eventID		= $row['eventID'];
    				$eventTitle	    = $this->HTMLEntityToSpecialCharacters($row['eventTitle']);
    				$startDate	    = $row['startDate'];
                    $endDate        = $row['endDate'];

					//FORMAT PUBLISH DATE
                    $sDate = date("j F Y - H:i", strtotime($startDate));
                    $eDate = date("j F Y - H:i", strtotime($endDate));

                    //PENDING EVENT
                    if($startDate > $currentDate){
                        $status		= '<span class="unpublished-post-text">(Pending)</span>';
                        $status_bg	= 'class="unpublished-post"';
                        $status_color = 'class="partial-account"';
                    }
                    //ACTIVE EVENT
                    elseif($endDate > $currentDate && $startDate < $currentDate){
                        $status	= '(Published)';
                        $status_color = 'class="active-account"';
                    }
                    //EXPIRED EVENT
                    else{
                        $status		= '<span class="empty-category-text">(Expired)</span>';
    					$status_bg	='class="empty-category"';
                        $status_color = 'class="removed-account"';
                    }

					//GENERATE OUPUT
                    echo '<tr>
    					<td width="1%" '.$status_color.'></td>
    					<td width="30%" '.$status_bg.'>'.$eventTitle.' '.$status.'</td>
    					<td width="15%" '.$status_bg.' align="center">'.$sDate.'</td>
                        <td width="15%" '.$status_bg.' align="center">'.$eDate.'</td>
    					<td width="13%" '.$status_bg.' align="center">
    						<a href="'.$cms_root.'events-manager/manage-event.php?eventID='.$eventID.'" title="Manage">Manage</a>
    					</td>
    					<td width="13%" '.$status_bg.' align="center">
    						<a href="'.$cms_root.'events-manager/edit-event.php?eventID='.$eventID.'" title="Modify">Modify</a>
    					</td>
    					<td width="13%" '.$status_bg.' align="center">';

    					echo '<form name="delete_event'.$eventID.'">
    							<input type="hidden" name="delete_event" value="1">
    							<input type="hidden" name="eventID" value="'.$eventID.'">
    							<a href="javascript:deleteEvent('.$eventID.')" title="Remove">Remove</a>
    						</form>';

    					echo '</td>
    				  </tr>';
		        }

				//END TABLE
				echo '</table>';
		    }
		}
	}
}

$ajaxEventLibrary = new ajaxEventLibrary();
?>
