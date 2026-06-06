<?php
//SET INCLUDES
include_once('../../library/class.systemConfig.php');
include_once('../../library/cms.userLogin.php');

//CONNECT TO DATABASE
$connector = new dbConnector();

//GET POSTED DATA
$newLang    = $_POST['newLang'];

//DESTROY LANGUAGE COOKIE & SESSION
$userLogin->destroyLanguageCookie();
$userLogin->destroyLanguageSession();

//CREATE LANGUAGE COOKIE & SESSION
$userLogin->createLanguageCookie($newLang);
$userLogin->createLanguageSession($newLang);
?>
