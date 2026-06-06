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
# SET CRON FOR EVERY WEEK AT 02:00
#######################################################################################################
//DEFAULT VARIABLES
$directory = '.';
$backupsDirectory = '_backups/';

//SCAN DIRECTORY
$files = scandir($directory);

//LOOP THROUGH FILES IN DIRECTORY
foreach($files AS $file){
    //CHECK IF FILE IS A BACK UP
    if(strpos($file, '.tar.gz') !== false){
        //MOVE TO BACK UP FOLDER
        rename($directory.$file, $backupsDirectory.$file);
    }
}
?>
