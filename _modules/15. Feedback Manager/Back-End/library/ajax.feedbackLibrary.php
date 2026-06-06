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

class ajaxFeedbackLibrary extends DbConnector{
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

	//#################################################
	//GET TOTAL NUMBER OF FEEDBACK MESSAGES
	//#################################################
	function getTotalNumberFeedbackMessages($field){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

        //GET TOTAL
		$total_q_products = $connector->query("SELECT * FROM form_feedback WHERE deletedBy = ? AND $field != '' ORDER BY createdDate DESC", array(0));

		$total_nums_products = $connector->numResults($total_q_products); //TOTAL NUMBER OF RESULTS

		//RETURN TOTAL
		return $total_nums_products;
	}

	//#################################################
	//FETCH FEEDBACK MESSAGES
	//#################################################
	function fetchFeedbackMessages($pagenum, $cms_root, $field){
		//CONNECT TO DATABASE
		$connector 	= new DbConnector();

        //DEFAULT VARIABLES
        $txt = '';
        $search = array('<p>', '</p>'. '\\');
        $replace = array('', '<br />', '');

		//ONLY SHOW LOAD BUTTON AT THE BEGINNING
		if($pagenum != 1){

		    $rowsperpage = 25; //MAXIMUM RESULTS PER PAGE
		    $offset = ($pagenum-1) * $rowsperpage; //WHERE THE RESULTS START FROM

		    //FOR RESULTS OF THE PAGE
		    $q = $connector->query("SELECT * FROM form_feedback WHERE deletedBy = ? AND $field != '' ORDER BY createdDate DESC LIMIT $offset, $rowsperpage", array(0));

		    $total_q = $connector->query("SELECT * FROM form_feedback WHERE deletedBy = ? AND $field != '' ORDER BY createdDate DESC", array(0));//FOR ALL RESULTS
		    $total_nums = $connector->numResults($total_q); //TOTAL NUMBER OF RESULTS
		    $total_pages = ceil($total_nums/$rowsperpage); //NUMBER OF PAGES

		    //IF PAGE NUMBER IS WITHIN THE FIRST AND LAST PAGES...
		    if($pagenum >= 1 && $pagenum <= $total_pages)
		    {
		    	while($row	= $connector->fetchArray($q)){
                    //SET VARIABLES
                    $message    = str_replace($search, $replace, $row[$field]);

                    if($message != '' && $message != ' ' && $message != '<br />'){
                        //GENERATE OUTPUT
                        echo  '<li><span>'.$message.'</span></li>';
                    }

		        }
		    }
		}
	}
}

$ajaxFeedbackLibrary = new ajaxFeedbackLibrary();
?>
