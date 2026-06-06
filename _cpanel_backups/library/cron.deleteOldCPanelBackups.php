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
# SET CRON FOR EVERY WEEK AT 03:00
#######################################################################################################

//DEFAULT VARIABLES
$directory = '/home/__username___/'; //CPanel Root Path
$backupsDirectory = $directory.'_backups/'; // Backup Folder
$dateMinusMonth = date("Y-m-d", strtotime("-1 months"));

//GET FILES INSIDE OF BACKUP DIRECTORY
$backupFiles = scandir($backupsDirectory);

//LOOP THROUGH ALL FILES IN BACKUP DIRECTORY
foreach($backupFiles AS $backupFile){
    //CHECK IF FILE IS A BACK UP
    if(strpos($backupFile, '.tar.gz') !== false){
        //GET DATE OF FILE
        $fileDate   = date("Y-m-d",filemtime($backupsDirectory.$backupFile));

        //CHECK IF FILE SHOULD BE REMOVED
        if($fileDate <= $dateMinusMonth){
            unlink($backupsDirectory.$backupFile);
        }
    }
}
?>
