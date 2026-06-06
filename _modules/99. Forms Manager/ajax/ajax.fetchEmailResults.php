<?php
require_once("../../library/ajax.formsManagerLibrary.php");

$connector = new dbConnector();

//PAGE NUMBER, RESULTS PER PAGE, AND OFFSET OF THE RESULTS
if($_GET["page"]){
    $pagenum = $_GET["page"];
} else {
    $pagenum = 1;
}

if($_GET["customer_names"]){
    $customer_names = $_GET["customer_names"];
}

if($_GET["customer_emails"]){
    $customer_emails = $_GET["customer_emails"];
}

if($_GET["forms-select"]){
    $filter_form_select = $_GET["forms-select"];
}

if($_GET["filter-start-date"]){
    $filter_start_date = $_GET["filter-start-date"];
}

if($_GET["filter-end-date"]){
    $filter_end_date = $_GET["filter-end-date"];
}

if($_GET["forms-order"]){
    $filter_order = $_GET["forms-order"];
}

//FETCH EMAILS
$ajaxMerchantLibrary->fetchEmails($pagenum, $customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order, $cms_root);
?>
