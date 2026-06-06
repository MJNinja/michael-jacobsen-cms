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
require_once("../../library/class.systemConfig.php");
require_once("../../library/ajax.productLibrary.php");

//GET VARIABLE
if(isset($_GET['ddValue'])){$productCatID = $_GET['ddValue'];}else{$productCatID = $_POST['ddValue'];}

//GET SUB CATEGORIES
echo $ajaxProductLibrary->getProductSubCategories($productCatID);
?>
