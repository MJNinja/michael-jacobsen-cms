<?php
require_once("../../library/ajax.feedbackLibrary.php");
$connector = new dbConnector();

$rowsperpage_messages = 25; //MAXIMUM RESULTS PER PAGE
$preload_content_messages = 25;

$total_nums_messages  = $ajaxFeedbackLibrary->getTotalNumberFeedbackMessages($field); //TOTAL NUMBER OF RESULTS

$total_pages_messages = ceil($total_nums_messages/$rowsperpage_messages); //NUMBER OF PAGE FOR RESULTS
?>
