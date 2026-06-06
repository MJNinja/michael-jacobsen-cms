<?php
//CHECK IF USER IS ALLOWED TO ACCESS MODULE
if($userLogin->checkUserModuleRights($userType, $moduleID, $_SESSION['cmsUser']) == 'no'){
    //REDIRECT TO INDEX PAGE
    header("Location: ".$cms_root);
}
?>
