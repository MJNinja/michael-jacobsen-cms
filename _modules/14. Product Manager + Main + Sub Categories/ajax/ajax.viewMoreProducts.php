<?php
require_once("../../library/ajax.productLibrary.php");
$connector = new dbConnector();

$rowsperpage_products = 40; //MAXIMUM RESULTS PER PAGE
$preload_content_products = 40;

$total_nums_products  = $ajaxProductLibrary->getTotalNumberProducts(); //TOTAL NUMBER OF RESULTS

$total_pages_products = ceil($total_nums_products/$rowsperpage_products); //NUMBER OF PAGE FOR RESULTS
?>
