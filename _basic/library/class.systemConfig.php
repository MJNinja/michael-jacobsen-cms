<?php
##########################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 03.04.2015
##########################################################################
//SET CORRECT TIME ZONE FOR PHP
//putenv("TZ=Africa/Windhoek");
//date_default_timezone_set("Africa/Windhoek");

//TUNR OFF ERROR REPORTING
//error_reporting(0);

//PREVENT ASSESS OF COOKIE VIA JAVASCRIPT
session_set_cookie_params(time()+172800, '/', '', false, true);

//CHANGE NAME OF DEFAULT SESSION COOKIE
session_name("wsvid");

//ENABLE SESSION START
session_start();
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('X-Permitted-Cross-Domain-Policies: master-only');
header('Content-Type: text/html; charset=utf-8');
ob_start("ob_gzhandler");

require_once('class.dbConnector.php');
require_once('class.mobileDetect.php');

//CHECK IF THE DEVICE SESSION EXISTS
if(!isset($_SESSION['device'])){
	//CHECK IF THE DEVICE IS A TABLET
	if($detect->isTablet()){
		$_SESSION['device'] = 'tablet';
	//CHECK IF THE DEVICE IS A MOBILE PHONE
	}elseif($detect->isMobile()){
		$_SESSION['device'] = 'mobile';
	//CHECK IF THE DEVICE IS A DESKTOP PC
	}else{
		$_SESSION['device'] = 'desktop';
	}
}

class systemConfig{

	//SET DATABASE SETTINGS
	public static function dbSettings(){
		//DEFINE DATABASE SETTINGS
		$settings['host']			= 'localhost';
		$settings['dbname']			= 'jacmat';
		$settings['dbpassword']		= '';
		$settings['dbusername']		= 'root';

		return $settings;
	}

	//SET URLS FOR WEBSITE AND CMS
	public static function urlSettings() {
		//DEFINE WEBSITE URLS
		$settings['simple_url']		= 'localhost/jacmat/';
		$settings['site_dir'] 		= 'http://localhost/jacmat/';
		$settings['cms_dir'] 		= 'http://localhost/jacmat/cms/';

		return $settings;
	}
}
//DEFINE CLASS
$systemConfig = new systemConfig();

//GET URL INFORMATION
$urls = $systemConfig->urlSettings();

//SET WEBSITE URL
$web_root = $urls['site_dir'];

//SET CMS URL
$cms_root = $urls['cms_dir'];

//SET SIMPLE URL
$simple_url = $urls['simple_url'];

//SET CSS & JS CACHE CLEAR (YYYYMMMDDDHHMM)
$cssjscacheclear = '201708112045';
?>
