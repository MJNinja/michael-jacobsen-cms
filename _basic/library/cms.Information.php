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
require_once("class.systemConfig.php");
require_once("class.formValidation.php");
require_once("class.formValidation.php");

class cmsInformation extends systemConfig{
	//#################################################################
    // DO NOT CHANGE CODE BELOW
    //#################################################################
    function __construct(){}
    function __destruct(){unset($connector);}

	//#################################################################
    // GET CMS NAVIGATION
    //#################################################################
	function getCMSNavigation($moduleID, $userType, $userModuleRights, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLE
		$txt = '';

		//GET USER INFO
		$result = $connector->query("SELECT * FROM cms_modules ORDER BY cmsModuleID ASC", array());
		while($row	= $connector->fetchArray($result)){
			//GET VARIABLES
			$active			= '';
            $showModule     = 1;
			$cmsModuleID	= $row['cmsModuleID'];
			$cmsModuleIcon	= $row['cmsModuleIcon'];
			$cmsModuleName	= $row['cmsModuleName'];
			$cmsModulePath	= $row['cmsModulePath'];

            //SHOW MODULE IF ADMINISTRATOR
            if($userType == 1){
                $showModule = 1;
            }
            //CHECK IF MODULE SHOULD BE SHOWN
            elseif(strpos($userModuleRights, ','.$cmsModuleID.',') !== false){
                $showModule = 1;
            }
            //DON'T SHOW MODULE
            else{
                $showModule = 0;
            }

            //SHOW MODULE
            if($showModule == 1){
    			//CHECK IF MODULE IS CURRENT
    			if($cmsModuleID == $moduleID){
    				$active = 'active-module';
    			}

    			//GENERATE OUTPUT
    			$txt.='<a href="'.$cms_root.$cmsModulePath.'" title="'.$cmsModuleName.'">
    					<div class="navigation-module-holder '.$active.'">
    						<div class="navigation-module-image"><img src="'.$cms_root.'images/icons/'.$cmsModuleIcon.'" border="0" /></div>
    						<div class="navigation-module-title">'.$cmsModuleName.'</div>
    						<div class="clear"></div>
    					</div>
    				</a>';
            }
		}

		//RETURN OUTPUT
		return $txt;

	}

    //#################################################################
    // GET DASHBOARD MODULES
    //#################################################################
	function getDashboardModules($userType, $userModuleRights, $cms_root){
		//CONNECT TO DATABASE
		$connector = new dbConnector();

		//SET DEFAULT VARIABLE
		$txt = '';
        $count = 1;

		//GET USER INFO
		$result = $connector->query("SELECT cmsModuleID, cmsModuleName, cmsModuleDescription, cmsModulePath FROM cms_modules ORDER BY cmsModuleID ASC", array());
		while($row	= $connector->fetchArray($result)){
			//GET VARIABLES
            $spacing                 = '';
            $showModule              = 1;
            $cmsModuleID             = $row['cmsModuleID'];
			$cmsModuleName           = $row['cmsModuleName'];
			$cmsModuleDescription    = strip_tags($row['cmsModuleDescription']);
            $cmsModulePath           = $row['cmsModulePath'];

            //SHOW MODULE IF ADMINISTRATOR
            if($userType == 1){
                $showModule = 1;
            }
            //CHECK IF MODULE SHOULD BE SHOWN
            elseif(strpos($userModuleRights, ','.$cmsModuleID.',') !== false){
                $showModule = 1;
            }
            //DON'T SHOW MODULE
            else{
                $showModule = 0;
            }

            //SHOW MODULE
            if($showModule == 1){
                //SET SPACING VARIABLE
                if($count != 1){
                    $spacing = 'stats-spacing';
                }

    			//GENERATE OUTPUT
    			$txt.='<div class="stats-holder '.$spacing.'">
                	<div class="stats-name">&nbsp;&nbsp;'.$cmsModuleName.'</div>
                    <div class="stats-intro">'.$cmsModuleDescription.'</div>
                    <a href="'.$cms_root.$cmsModulePath.'" title="Go to Module">Go to Module</a>
                </div>';

                //INSERT CLEAR DIV
                if($count == 3){
                    //INSERT CLEAR DIV
                    $txt.= '<div class="clear"></div>';

                    //RESET COUNT
                    $count = 0;
                }

                //INCREMENT COUNT
                $count++;
            }
		}

		//RETURN OUTPUT
		return $txt;

	}

}

//DEFINE CLASS
$cmsInformation = new cmsInformation();
?>
