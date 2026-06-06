<?php
//SET DEFAULT VARIABLES
$cms = 1;
$moduleID = 19;
$colorbox = 1;
$ckeditor = 1;
$paragraph_image_enlarge = 1;
$pageTitle = 'Edit FAQ';

//GET URL VARIABLE
if(isset($_POST['faqID'])){$faqID = $_POST['faqID'];}else{$faqID = $_GET['faqID'];}
require_once("../inc/cms-owner-info-inc.php");
require_once("../../library/cms.userLogin.php");
require_once("../../library/class.systemConfig.php");
require_once("../../library/cms.faqManager.php");

//REDIRECT PAGE
if($faqID != ''){
	//CHECK $faqID INSIDE DATABASE
	if($faqManager->checkFAQDatabase($faqID) == 'not found'){
		header("Location:".$cms_root."faq-manager/");
		exit;
	}
}else{
	header("Location:".$cms_root."faq-manager/");
	exit;
}

//SET BREADCRUMBS
$breadcrumbs = '<a href="'.$cms_root.'" title="Dashboard">Dashboard</a> | <a href="'.$cms_root.'faq-manager/" title="FAQ Manager">FAQ Manager</a> | <span class="current">Edit FAQ</span>';

require_once("../inc/header-inc.php");
require_once("../inc/navigation-inc.php");

?>

    <!-- BEGIN CONTENT HOLDER -->
    <div class="content-holder">

    	<h1 align="center">Edit FAQ</h1>
        <div class="intro">
        	<p>This is the <b>Edit FAQ</b> page. This page will allow you to edit the current FAQ.</p>
        </div>

        <div class="left-column">
            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Edit FAQ</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Please change all the required field below to edit the FAQ. <span class="required">(*) Required</span><br />
                	Once you are done click on <b>Edit FAQ</b> to edit the FAQ.
                </div>

                <?php
				if(!empty($error_message)){
					echo '<div class="rightContentBoxContainerError">';
					echo '<div class="message">'.$error_message.'</div>';
					if(!empty($errors)){
						echo '<div class="errorMessage">'.$errors.'</div>';
					}
					echo '</div>';
				}
				?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="module-form-holder">
                    	<input type="hidden" name="faqID" value="<?php echo $faqID; ?>"/>
                        <input type="hidden" name="modifiedDate" value="<?php echo date('Y-m-d H:i:s');?>"/>
                        <input type="hidden" name="modifiedNumber" value="<?php echo $faqManager->getFAQInfo($faqID, 'modifiedNumber')+1;?>"/>

                        <div class="module-form-titles"><span class="required">*</span> FAQ Question: </div>
						<input type="text" name="faq-question" placeholder="FAQ Question" value="<?php if($_POST['faq-question'] != ''){echo $_POST['faq-question'];}else{echo $faqManager->getFAQInfo($faqID, 'faqQuestions');}?>" maxlength="200" />
                        <i>The FAQ question has a maximum of 200 characters.</i>

                        <span class="hidden"><div class="module-form-titles">FAQ Type:</div>
						<input type="text" name="faq-type" placeholder="FAQ Type" value="<?php if($_POST['faq-type'] != ''){echo $_POST['faq-type'];}?>" />
                        <i>The type has a maximum of 150 characters.</i></span>

						<div class="module-form-titles"><span class="required">*</span> FAQ Answer:</div>
						<textarea name="paragraph" cols="20" rows="5"><?php if($_POST['paragraph'] != ''){echo $_POST['paragraph'];}else{echo $faqManager->getFAQInfo($faqID, 'faqAnswer');}?></textarea>
                        <i>When copying text from a word document, kindly click on the <img src="../images/basic/paste-plain-text.png" width="21" height="20" alt="Paste as Plain Text" title="Paste as Plain Text"> Paste as plain text icon, and paste the text into the pop-up field. This will remove all formatting that is linked to a word document.</i>
                    </div>
					<input type="submit" class="module-form-submit" name="edit_faq" title="Edit FAQ" value="Edit FAQ" onclick="pleasewait()"/>
	            </form>
            </div>

            <div class="module-holder">
                <div class="module-holder-name">&nbsp;&nbsp;FAQ Info</div>
                <div class="clear"></div>
                <div class="module-holder-intro">
                	Below you can see some information about the current <b>FAQ</b>.
                </div>

                <div class="module-architecture-table-holder">
                	<table width="100%" class="edit-information-table">
                      <tr>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Created By:</b></div>
                            <?php echo $faqManager->getUsersName($faqManager->getFAQInfo($faqID, 'createdBy'));?>
                       </td>
                        <td width="50%">
                        	<div class="edit-information-table-label"><b>Modified By:</b></div>
                            <?php
                            	if($faqManager->getFAQInfo($faqID, 'modifiedBy') != 0){
									echo $faqManager->getUsersName($faqManager->getFAQInfo($faqID, 'modifiedBy'));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                        	<div class="edit-information-table-label"><b>Created Date:</b></div>
                        	<?php echo date("j F Y",strtotime($faqManager->getFAQInfo($faqID, 'createdDate')));?>
                        </td>
                        <td>
                        	<div class="edit-information-table-label"><b>Last Modified Date:</b></div>
                        	<?php
                            	if($faqManager->getFAQInfo($faqID, 'modifiedDate') != 0){
									echo date("j F Y", strtotime($faqManager->getFAQInfo($faqID, 'modifiedDate')));
								}else{
									echo '-';
								}
							?>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>
                        	<div class="edit-information-table-label"><b>No. of Times Modified:</b></div>
                        	<?php echo $faqManager->getFAQInfo($faqID, 'modifiedNumber');?>
                        </td>
                      </tr>
                    </table>

                </div>
            </div>

        </div>

        <div class="right-column module-stats-spacing">
            <div class="module-stats-holder">
                <div class="module-holder-name">&nbsp;&nbsp;Module Stats</div>
                <div class="clear"></div>

                <div class="module-stats-container">
                	<?php include_once("../inc/faq-stats-inc.php"); ?>
            	</div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <!-- END CONTENT HOLDER -->

<?php
require_once("../inc/footer-inc.php");
?>
