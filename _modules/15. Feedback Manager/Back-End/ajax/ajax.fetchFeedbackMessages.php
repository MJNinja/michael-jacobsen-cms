<?php
require_once("../../library/ajax.feedbackLibrary.php");

$connector = new dbConnector();

//PAGE NUMBER, RESULTS PER PAGE, AND OFFSET OF THE RESULTS
if($_GET["page"]){
    $pagenum = $_GET["page"];
} else {
    $pagenum = 1;
}

//GET FIELD
if($_GET["f"]){
    $field = $_GET["f"];
} else {
    $field = $_POST["f"];
}


//FETCH FEEDBACK MESSAGES
$ajaxFeedbackLibrary->fetchFeedbackMessages($pagenum, $cms_root, $field);
?>
