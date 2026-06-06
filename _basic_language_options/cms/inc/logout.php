<?php
include_once('../../library/class.systemConfig.php');

//DESTROY EXISTING SESSION
unset($_SESSION['cmsUser']);
session_unset();
session_destroy();

//DESTROY COOKIE
setcookie("wsvid", "", 1, "/", '', false, true);

header("Location: ".$cms_root);
exit;
?>
