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
# SET CRON FOR EVERY WEEK AT 01:00
#######################################################################################################

//CPANEL BACKUP CONFIG ARRAY
$fullbackup = array(
    "cpuser" => "XXXXXXXXXX", // Username used to login to CPanel
    "cppass" => "XXXXXXXXXX", // Password used to login to CPanel
    "domain" => "www.domain.com", // Domain name where CPanel is run (Always add the www)
    "cpskin" => "x3" // Set to cPanel skin you use (script won't work if it doesn't match). Most people run the default x theme
);

// PHP script to allow periodic cPanel backups automatically, optionally to a remote FTP server.
// This script contains passwords.  KEEP ACCESS TO THIS FILE SECURE! (place it in your home dir, not /www/)

// ********* THE FOLLOWING ITEMS NEED TO BE CONFIGURED *********

// Info required for cPanel access
$cpuser = $fullbackup['cpuser']; // Username used to login to CPanel
$cppass = $fullbackup['cppass']; // Password used to login to CPanel
$domain = $fullbackup['domain']; // Domain name where CPanel is run
$skin   = $fullbackup['cpskin']; // Set to cPanel skin you use (script won't work if it doesn't match). Most people run the default x theme

// Info required for FTP host
$ftpuser = ""; // Username for FTP account
$ftppass = ""; // Password for FTP account
$ftphost = ""; // Full hostname or IP address for FTP host
$ftpmode = ""; // FTP mode ("ftp" for active, "passiveftp" for passive)

// Notification information
//$notifyemail = "info@michaeljacobsen.ninja"; // Email address to send results

// Secure or non-secure mode
$secure = 0; // Set to 1 for SSL (requires SSL support), otherwise will use standard HTTP

// Set to 1 to have web page result appear in your cron log
$debug = 0;

///////////////////////////////////////////////////////////////
// *********** NO CONFIGURATION ITEMS BELOW THIS LINE *********
///////////////////////////////////////////////////////////////

if ($secure) {
   $url = "ssl://".$domain;
   $port = 2083;
} else {
   $url = $domain;
   $port = 2082;
}

$socket = fsockopen($url,$port);
if (!$socket) { echo "Failed to open socket connection... Bailing out!\n"; exit; }

// Encode authentication string
$authstr = $cpuser.":".$cppass;
$pass = base64_encode($authstr);

$params = "dest=$ftpmode&email=$notifyemail&server=$ftphost&user=$ftpuser&pass=$ftppass&submit=Generate Backup";

// Make POST to cPanel
fputs($socket,"POST /frontend/".$skin."/backup/dofullbackup.html?".$params." HTTP/1.0\r\n");
fputs($socket,"Host: $domain\r\n");
fputs($socket,"Authorization: Basic $pass\r\n");
fputs($socket,"Connection: Close\r\n");
fputs($socket,"\r\n");

// Grab response even if we don't do anything with it.
while (!feof($socket)) {
  $response = fgets($socket,4096);
  if ($debug) echo $response;
}

fclose($socket);
?>
