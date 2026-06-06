<?php
require_once("../../library/ajax.eventsLibrary.php");
$connector = new dbConnector();

$rowsperpage_events = 40; //MAXIMUM RESULTS PER PAGE
$preload_content_events = 40;

$total_nums_events  = $ajaxEventLibrary->getTotalNumberEvents(); //TOTAL NUMBER OF RESULTS

$total_pages_events = ceil($total_nums_events/$rowsperpage_events); //NUMBER OF PAGE FOR RESULTS
?>
