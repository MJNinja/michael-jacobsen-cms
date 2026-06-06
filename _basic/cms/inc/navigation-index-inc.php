<?php
require_once("../library/cms.Information.php");

//GET USER INFO
$userType           = $userLogin->getUserInfo($_SESSION['cmsUser'], 'userType');
$userModuleRights   = $userLogin->getUserInfo($_SESSION['cmsUser'], 'userModuleRights');
?>
<!-- BEGIN SIDE NAVIGATION -->
<div class="navigation-holder">
    <a href="<?php echo $cms_root; ?>" title="Dashboard">
        <div class="navigation-module-holder <?php if($moduleID == 0){ echo 'active-module'; }?>">
            <div class="navigation-module-image"><img src="<?php echo $cms_root; ?>images/icons/dashboard-icon.png" border="0" /></div>
            <div class="navigation-module-title">Dashboard</div>
            <div class="clear"></div>
        </div>
    </a>

    <?php if($userType == 1){ ?>
    <a href="<?php echo $cms_root; ?>cms-users-manager/" title="CMS User Manager">
    	<div class="navigation-module-holder <?php if($moduleID == 1){ echo 'active-module'; }?>">
            <div class="navigation-module-image"><img src="<?php echo $cms_root; ?>images/icons/user-icon.png" border="0" /></div>
            <div class="navigation-module-title">CMS User Manager</div>
            <div class="clear"></div>
        </div>
	</a>
    <?php } ?>

    <?php echo $cmsInformation->getCMSNavigation($moduleID, $userType, $userModuleRights, $cms_root);?>

</div>
<!-- END SIDE NAVIGATION -->
