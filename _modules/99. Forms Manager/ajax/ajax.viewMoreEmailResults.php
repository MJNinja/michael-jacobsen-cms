<?php
require_once("../../library/ajax.formsManagerLibrary.php");
$connector = new dbConnector();

$rowsperpage_emails = 25; //MAXIMUM RESULTS PER PAGE
$preload_content_emails = 25;

$total_nums_emails  = $ajaxMerchantLibrary->getTotalNumberEmails($customer_names, $customer_emails, $filter_form_select, $filter_start_date, $filter_end_date, $filter_order); //TOTAL NUMBER OF RESULTS

$total_pages_emails = ceil($total_nums_emails/$rowsperpage_emails); //NUMBER OF PAGE FOR RESULTS
?>
