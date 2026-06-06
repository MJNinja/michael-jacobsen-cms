<?php
require_once("../../library/ajax.productLibrary.php");

$connector = new dbConnector();

//PAGE NUMBER, RESULTS PER PAGE, AND OFFSET OF THE RESULTS
if($_GET["page"]){
    $pagenum = $_GET["page"];
} else {
    $pagenum = 1;
}

//FETCH PRODUCTS
$ajaxProductLibrary->fetchProducts($pagenum, $cms_root);
?>
