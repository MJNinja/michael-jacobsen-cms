<?php
$cms = 1;
$auth_page = 1;
$pageTitle = 'CMS Login';

require_once("inc/cms-owner-info-inc.php");
require_once("../library/cms.userLogin.php");
require_once("../library/class.systemConfig.php");

//CHANGE VALUE OF SESSION COOKIE
session_regenerate_id(true);

//SET LOGIN SESSION TIME
if($_SESSION['cmsLogin'] == ''){
	$_SESSION['cmsLogin'] = date('Y-m-d H:i:s');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset='UTF-8'>
<meta http-equiv="content-language" content="en"/>
<meta name="language" content="English"/>
<meta name="robots" content="noindex, nofollow"/>

<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $cms_root; ?>favicon/apple-touch-icon-180x180.png">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="<?php echo $cms_root; ?>favicon/manifest.json">
<link rel="mask-icon" href="<?php echo $cms_root; ?>favicon/safari-pinned-tab.svg" color="#04a6df">
<link rel="shortcut icon" href="<?php echo $cms_root; ?>favicon/favicon.ico">
<meta name="apple-mobile-web-app-title" content="CMS">
<meta name="application-name" content="CMS">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo $cms_root; ?>favicon/mstile-144x144.png">
<meta name="msapplication-config" content="<?php echo $cms_root; ?>favicon/browserconfig.xml">
<meta name="theme-color" content="#04a6df">

<title><?php echo $pageTitle; ?> - <?php echo $cms_name; ?> <?php echo $cms_version; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo $cms_root; ?>css/auth-styles.css"/>
</head>

<body>

<div class="login-holder" align="center">
	<img src="<?php echo $cms_root; ?>images/logo/cms-logo.png" alt="CMS Logo" title="CMS Logo" border="0"/>

    <?php if($userLogin->cmsUserBlocked($_SESSION['user_temp']) == 'blocked'){?>
    <h1>Welcome to the CMS</h1>

    <div class="login-intro">Please Sign In to get access</div>

	<div class="errorContainerWrong">
    	<div class="error_message">Your Account has been blocked!</div>
        <div class="errorMessage">You can attempted to log in again in about 30 Minutes.</div>
	</div>

    <div class="login-form-holder">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <!--[if lt IE 10]>Email: <br /><![endif]-->
            <input type="email" name="email" value="" placeholder="Email:" autofocus="autofocus" /><br />
            <span class="hide"><!--[if lt IE 10]>Re-Type Email: <br /><![endif]-->
            <input type="email" name="email2" value="" placeholder="Re-Type Email:" /><br /></span>
            <!--[if lt IE 10]>Password: <br /><![endif]-->
            <input type="password" name="password" value="" placeholder="Password:" /><br />
            <span class="hide"><!--[if lt IE 10]>Re-Type Password: <br /><![endif]-->
            <input type="password" name="password2" value="" placeholder="Re-Type Password:" /><br /></span>
            <input type="submit" name="submit-login" title="Sign In" value="Sign In" class="submit-button-style" />
        </form>
    </div>

    <p>
    	<?php echo $cms_name; ?> Copyright © <?php if(date('Y') == $cms_year){echo date('Y');}else{echo $cms_year.' - '.date('Y');}?><br /><br />
        Extensions and source code are copyright of <?php echo $cms_owner; ?>.<br />
        The <?php echo $cms_name; ?> is NOT FREE software,<br />
        and may not be redistributed under any conditions.<br />
        Obstructing the appearance of this notice is prohibited by law.<br />
    </p>

    <?php }elseif($auth == 1){?>
    <h1>Verify your Account</h1>

    <div class="login-intro">Please supply your contact number assigned<br /> to this account for validation.</div>

    <?php
	if(!empty($error_message)){
		echo '<div class="errorContainerWrong">';
		echo '<div class="error_message">'.$error_message.'</div>';
		if(!empty($errors)){
			echo '<div class="errorMessage">'.$errors.'</div>';
		}
		echo '</div>';
	}
    ?>

    <div class="login-form-holder">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

            <!--[if lt IE 10]>Contact Number: <br /><![endif]-->
            <input type="text" name="number" value="" placeholder="Contact Number:" autofocus="autofocus" /><br />
            <span class="hide"><!--[if lt IE 10]>Re-Type Contact Number: <br /><![endif]-->
            <input type="text" name="number2" value="" placeholder="Re-Type Contact Number:" /><br /></span>
            <input type="submit" name="submit-verify-account" title="Verify Account" value="Verify Account" class="submit-button-style" />
        </form>
    </div>

    <p>
    	<?php echo $cms_name; ?> Copyright © <?php if(date('Y') == $cms_year){echo date('Y');}else{echo $cms_year.' - '.date('Y');}?><br /><br />
        Extensions and source code are copyright of <?php echo $cms_owner; ?>.<br />
        The <?php echo $cms_name; ?> is NOT FREE software,<br />
        and may not be redistributed under any conditions.<br />
        Obstructing the appearance of this notice is prohibited by law.<br />
    </p>
    <?php }else{?>
    <h1>Welcome to the CMS</h1>

    <div class="login-intro">Please Sign In to get access</div>

    <?php
	if(!empty($error_message)){
		echo '<div class="errorContainerWrong">';
		echo '<div class="error_message">'.$error_message.'</div>';
		if(!empty($errors)){
			echo '<div class="errorMessage">'.$errors.'</div>';
		}
		echo '</div>';
	}
    ?>

    <div class="login-form-holder">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <!--[if lt IE 10]>Email: <br /><![endif]-->
            <input type="email" name="email" value="" placeholder="Email:" autofocus="autofocus" /><br />
            <span class="hide"><!--[if lt IE 10]>Re-Type Email: <br /><![endif]-->
            <input type="email" name="email2" value="" placeholder="Re-Type Email:" /><br /></span>
            <!--[if lt IE 10]>Password: <br /><![endif]-->
            <input type="password" name="password" value="" placeholder="Password:" /><br />
            <span class="hide"><!--[if lt IE 10]>Re-Type Password: <br /><![endif]-->
            <input type="password" name="password2" value="" placeholder="Re-Type Password:" /><br /></span>
            <input type="submit" name="submit-login" title="Sign In" value="Sign In" class="submit-button-style" />
        </form>
    </div>

    <p>
    	<?php echo $cms_name; ?> Copyright © <?php if(date('Y') == $cms_year){echo date('Y');}else{echo $cms_year.' - '.date('Y');}?><br /><br />
        Extensions and source code are copyright of <?php echo $cms_owner; ?>.<br />
        The <?php echo $cms_name; ?> is NOT FREE software,<br />
        and may not be redistributed under any conditions.<br />
        Obstructing the appearance of this notice is prohibited by law.<br />
    </p>
    <?php }?>

</div>

</body>
</html>
