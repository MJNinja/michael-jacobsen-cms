<?php
require_once("../../library/ajax.eventsLibrary.php");

$connector = new dbConnector();

//PAGE NUMBER, RESULTS PER PAGE, AND OFFSET OF THE RESULTS
if($_GET["page"]){
    $pagenum = $_GET["page"];
} else {
    $pagenum = 1;
}

//FETCH EVENTS
$ajaxEventLibrary->fetchEvents($pagenum, $cms_root);
?>
