<?php
##########################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 04.04.2015
##########################################################################
require_once ('class.systemConfig.php');

class dbConnector extends systemConfig{

	//CONNECT TO DATABASE
	function __construct(){

		//GET DATABASE SETTINGS
		$settings = systemConfig::dbSettings();

		//SET THE SETTINGS
		$host 	= $settings['host'];
		$db 	= $settings['dbname'];
		$user 	= $settings['dbusername'];
		$pass 	= $settings['dbpassword'];

		// Connect to the database
		$DSN = "mysql:host=$host;dbname=$db";

        try
        {
            $this->dbh= new PDO($DSN,$user,$pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            //error has been caught change to something else (redirect to a page)
			//header("location:".$web_root);
			//exit;
			die("Sorry, Database Problem.");
        }
	}

	//CLOSE DATABASE CONNECTION WHEN OBJECT DESTRUCTS
    function __destruct(){
        $this->dbh=null;
    }

	//EXECUTE A DATABASE QUERY
	function query($query, $conditions_array){
		$result = $this->dbh->prepare($query);
		$result->execute($conditions_array);
		return $result;
	}


	//GET ARRAY OF QUERY RESULTS
    function fetchArray($result) {
        $array = $result->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    //GET NUMBER OF RESULTS OF QUERY
    function numResults($result) {
        $count = $result->rowCount();

        return $count;
    }

	//ESCAPE CERTAIN CHARACTERS FOR SAFER QUERIES
	function escape($str)
    {
        $search=array("\\","\0","\n","\r","\x1a","'",'"');
        $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
        return str_replace($search,$replace,$str);
    }

}
?>
