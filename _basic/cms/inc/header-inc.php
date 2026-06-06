<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset='UTF-8'>
<meta http-equiv="content-language" content="en"/>
<meta name="language" content="English"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" >
<meta name="robots" content="noindex, nofollow"/>

<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $cms_root; ?>favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="<?php echo $cms_root; ?>favicon/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="<?php echo $cms_root; ?>favicon/manifest.json">
<link rel="mask-icon" href="<?php echo $cms_root; ?>favicon/safari-pinned-tab.svg" color="#04a6df">
<link rel="shortcut icon" href="<?php echo $cms_root; ?>favicon/favicon.ico">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo $cms_root; ?>favicon/mstile-144x144.png">
<meta name="msapplication-config" content="<?php echo $cms_root; ?>favicon/browserconfig.xml">
<meta name="theme-color" content="#ffffff">

<title><?php echo $pageTitle; ?> - <?php echo $cms_name.' '.$cms_version; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo $cms_root; ?>css/cms-styles.css"/>
<?php if($colorbox == 1){?>
<link rel="stylesheet" type="text/css" href="<?php echo $cms_root; ?>js/colorbox/colorbox.css"/>
<?php }?>

<?php if($date_picker == 1){?>
<link rel="stylesheet" type="text/css" href="<?php echo $cms_root; ?>js/pikaday/css/pikaday.css"/>
<?php }?>

<?php if($time_picker == 1){?>
<link rel="stylesheet" type="text/css" href="<?php echo $cms_root; ?>js/timepicki/css/timepicki.css"/>
<?php }?>

<?php if($crop == 1){?>
<script type="text/javascript" src="<?php echo $cms_root; ?>js/crop/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo $cms_root; ?>js/crop/jquery.Jcrop.css" type="text/css" />
<script type="text/javascript" src="<?php echo $cms_root; ?>js/crop/jquery.Jcrop.js"></script>
<?php }?>

<?php if($gallery_upload == 1){?>
<link rel="stylesheet" href="<?php echo $cms_root; ?>js/multi-image-preview-uploader/multi-image-preview-styles.css" type="text/css" />
<?php }?>

<?php if($moduleID == 1){ ?>
	<script type="text/javascript" language="javascript">
        /*REMOVE USER*/
        function deleteUser(bos) {
            var msg = "Are you sure you want to remove the selected user? You can always recover the user at a later stage.";
            if (confirm(msg)) {
                eval('document.delete_user'+bos+'.method = "post"');
                eval('document.delete_user'+bos+'.action = "<?php echo $_SERVER['PHP_SELF'] ?>"');
                eval('document.delete_user'+bos+'.submit()');
            } else {
                return;
            }
        }
    </script>

    <script type="text/javascript" language="javascript">
        /*RECOVER USER*/
        function recoverUser(bos) {
            var msg = "Are you sure you want to recover the selected user? Once this user has been recovered he/she will have access to the CMS again.";
            if (confirm(msg)) {
                eval('document.recover_user'+bos+'.method = "post"');
                eval('document.recover_user'+bos+'.action = "<?php echo $_SERVER['PHP_SELF'] ?>"');
                eval('document.recover_user'+bos+'.submit()');
            } else {
                return;
            }
        }
    </script>
<?php } ?>

</head>

<body>

<div class="loading-overlay">
	<div class="overlay-content-vertical-align">
		<h1>Loading...</h1>
		<p>
			Do not close this Window! Content is being loaded!
		</p>
	</div>
</div>


<div class="processing-overlay">
	<div class="overlay-content-vertical-align">
		<h1>Please Wait...</h1>
		<p>
			Do not close this Window! Content is being processed!
		</p>
	</div>
</div>

<div class="wrapper">

    <!-- BEGIN TOP NAVIGATION-->
    <div class="top-navigation">
    	<div class="logo">
        	<a href="<?php echo $cms_root; ?>" title="Dashboard"><img src="<?php echo $cms_root; ?>images/logo/cms-logo-small.png" alt="CMS Logo" title="Dashboard" border="0"/>
            <div class="logo-title">CMS</div></a>
        </div>

        <div class="breadcrumbs">
        	You are here: <?php echo $breadcrumbs; ?>
        </div>

        <div class="settings">
        	<div class="cms-users-name"><a href="<?php echo $cms_root; ?>profile/" title="Profile"><?php echo $userLogin->getUserInfo($_SESSION['cmsUser'], 'name').' '.$userLogin->getUserInfo($_SESSION['cmsUser'], 'surname'); ?></a></div>
            <!-- Clickable Nav -->
            <div class="click-nav settings-icon">
                <ul class="no-js">
                    <li>
                        <a class="clicker"><img src="<?php echo $cms_root; ?>images/icons/gear-icon.png" alt="Settings Icon" title="Settings" border="0" /></a>
                        <ul>
                            <li><a href="<?php echo $cms_root; ?>profile/" <?php if($profile == 1){echo 'class="active"';} ?> title="Profile">Profile</a></li>
                            <li><a href="<?php echo $cms_root; ?>inc/logout.php" title="Log Out" >Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /Clickable Nav -->
        	<div class="clear"></div>
        </div>
        <div class="clear"></div>

    </div>
    <!-- END TOP NAVIGATION-->

    <!-- BEGIN MAIN CONTENT-->
    <div class="main-content">
