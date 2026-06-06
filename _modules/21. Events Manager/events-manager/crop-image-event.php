<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 21;
$crop = 1;
$eventRatio = 1;
$pageTitle = 'Crop Image';

//GET URL VARIABLE
if(isset($_POST['eventID'])){$eventID = $_POST['eventID'];}else{$eventID = $_GET['eventID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.eventsManager.php");

//CHECK IF USER IS ALLOWED TO ACCESS MODULE
require_once("../inc/cms-user-module-rights-inc.php");

//REDIRECT PAGE
if($eventID != ''){
	//CHECK eventID INSIDE DATABASE
	if($eventManager->checkEventDatabase($eventID) == 'not found'){
		header("Location:".$cms_root."events-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."events-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = 'Dashboard | Events Manager | Manage Event | Add Paragraph | Crop Image';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");
?>

<script type="text/javascript">

  $(function(){

    $('#cropbox').Jcrop({
     	boxWidth: 800,
		aspectRatio: <?php echo $ratio?>,
		setSelect: [ 0, 0, 300, 300 ],
		onSelect: updateCoords,
		onChange: updateCoords
	});

  });

  function updateCoords(c)
  {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
  };

  function checkCoords()
  {
    if (parseInt($('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
  };

</script>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Crop Image - <?php echo $eventManager->getEventInfo($eventID, 'eventTitle');?></h1>
        <div class="intro">
        	<p>This is the <b>Crop Image</b> page. On this page you will have to crop the image for the current paragraph so that it fits perfectly on the website.</p>
        </div>

        <div class="left-column">
        	<!-- BEGIN PARAGRAPH HOLDER-->
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Crop Image</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Select the area of the image that you want to apppear on your website, by either clicking on one of the white square block and then dragging it to resize the selected area or by clicking in the middle to reposition the selected area.

                    <div class="warning">
                        PLEASE NOTE: THIS PAGE SHOULD NOT BE CLOSED WHILE THE IMAGE IS BEING CROPPED.
                    </div>
                </div>

                    <div class="module-form-holder">

                        <div class="crop-image" align="center">
                        	<img src="<?php echo $originalFolder.$imageFileName; ?>" id="cropbox" />
                        </div>

                    	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return checkCoords();">
                        	<input type="hidden" name="imageFileName" value="<?php echo $imageFileName; ?>"/>
                            <input type="hidden" name="eventID" value="<?php echo $eventID; ?>"/>
                            <input type="hidden" name="message" value="<?php echo $message; ?>"/>
                            <input type="hidden" id="x" name="x" />
                            <input type="hidden" id="y" name="y" />
                            <input type="hidden" id="w" name="w" />
                            <input type="hidden" id="h" name="h" />

                    </div>
                	<input type="submit" class="module-form-submit" name="crop-event" title="Crop Image" value="Crop Image" onclick="pleasewait()"/>
            	</form>
            </div>
            <!-- END PARAGRAPH HOLDER-->
        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/event-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
