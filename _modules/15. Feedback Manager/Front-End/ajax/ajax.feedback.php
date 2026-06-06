<?php
//LIBRARY INCLUDERS
require_once("../library/class.systemConfig.php");
require_once("../library/ajax.library.php");

//DEFAULT VARIABLE
$fbpusc = 'fbpusc';

//CREATE POP-UP SHOWN COOKIE
$ajaxLibrary->createFeedbackPopUpShownCookie($fbpusc);
?>
<div class="feedback-holder">
    <div class="feedback-header">
        <h3>Website Feedback</h3>

        <p>
            We would love to hear what you think of our new website, good or bad, by filling out the form below. This will help us to improve our website and give our customers the best experience possible.
        </p>
    </div>

    <!-- BEGIN FEEDBACK -->
    <div class="load-more-loader" id="loader-feedback">
        <img src="<?php echo $web_root; ?>images/basic/loader.gif" title="Loading..." alt="Loading...">
    </div>
    <!-- END FEEDBACK -->

    <!-- BEGIN SUCCESS HOLDER -->
    <div id="feedback-success-holder">
        <div class="formMessageApprove">Thank you for your feedback!</div>
        <div class="form-reload">
            <input type="button" value="Close" class="submit-button" id="feedback-close">
        </div>
    </div>
    <!-- END SUCCESS HOLDER -->


    <div id="feedback-form">
        <input type="hidden" name="form" value="3">

        <!-- BEGIN ERROR HOLDER -->
        <div id="feedback-error-holder"></div>
        <!-- END ERROR HOLDER -->

        <div class="feedback-question-holder hidden">
            <strong>Your Full Name:</strong>
            <div class="feedback-question-fields">
                <input type="text" name="feedback-name" value="" />
            </div>
        </div>

        <div class="feedback-question-holder hidden">
            <strong>Your Email Address:</strong>
            <div class="feedback-question-fields">
                <input type="text" name="feedback-email" value="" />
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>Is this the first time you have visited the website?</strong>
            <div class="feedback-question-fields">
                <label><input type="radio" name="visit" value="1" /> Yes</label><br />
                <label><input type="radio" name="visit" value="2" /> No</label>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>What is the PRIMARY reason you came to the site?</strong>
            <div class="feedback-question-fields">
                <textarea name="reasonVisit" rows="6" cols="6"></textarea>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>Did you find what you needed?</strong>
            <div class="feedback-question-fields">
                <label><input type="radio" name="findWhatNeeded" value="1" /> Yes, all of it</label><br />
                <label><input type="radio" name="findWhatNeeded" value="2" /> Yes, some of it</label><br />
                <label><input type="radio" name="findWhatNeeded" value="3" /> No, none of it</label>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>If you did not find any or all of what you needed, please tell us what information you were looking for.</strong>
            <div class="feedback-question-fields">
                <textarea name="whatLookingFor" rows="6" cols="6"></textarea>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>Please tell us how easy it is to find information on the site.</strong>
            <div class="feedback-question-fields">
                <label><input type="radio" name="easyFindInfo" value="5" /> Very Easy</label><br />
                <label><input type="radio" name="easyFindInfo" value="4" /> Easy</label><br />
                <label><input type="radio" name="easyFindInfo" value="3" /> Average</label><br />
                <label><input type="radio" name="easyFindInfo" value="2" /> Difficult</label><br />
                <label><input type="radio" name="easyFindInfo" value="1" /> Very Difficult</label>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>What is your overall impression of the site?</strong>
            <div class="feedback-question-fields">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th width="46%">&nbsp;</th>
                        <th width="18%" align="center">Below Expectations</th>
                        <th width="18%" align="center">Meets Expectations</th>
                        <th width="18%" align="center">Exceeds Expectations</th>
                    </tr>
                    <tr>
                        <td>Professional</td>
                        <td align="center"><label><input type="radio" name="professional" value="1" /><br />1</label></td>
                        <td align="center"><label><input type="radio" name="professional" value="2" /><br />1</label></td>
                        <td align="center"><label><input type="radio" name="professional" value="3" /><br />3</label></td>
                    </tr>
                    <tr>
                        <td>Informative</td>
                        <td align="center"><label><input type="radio" name="informative" value="1" /><br />1</label></td>
                        <td align="center"><label><input type="radio" name="informative" value="2" /><br />1</label></td>
                        <td align="center"><label><input type="radio" name="informative" value="3" /><br />3</label></td>
                    </tr>
                    <tr>
                        <td>Visually Pleasing</td>
                        <td align="center"><label><input type="radio" name="visuallyPleasing" value="1" /><br />1</label></td>
                        <td align="center"><label><input type="radio" name="visuallyPleasing" value="2" /><br />1</label></td>
                        <td align="center"><label><input type="radio" name="visuallyPleasing" value="3" /><br />3</label></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>What is the likelihood that you will visit the website again?</strong>
            <div class="feedback-question-fields">
                <label><input type="radio" name="visitAgain" value="5" /> Extremely likely</label><br />
                <label><input type="radio" name="visitAgain" value="4" /> Very likely </label><br />
                <label><input type="radio" name="visitAgain" value="3" /> Moderately likely </label><br />
                <label><input type="radio" name="visitAgain" value="2" /> Slightly likely </label><br />
                <label><input type="radio" name="visitAgain" value="1" /> Not at all likely </label>
            </div>
        </div>

        <div class="feedback-question-holder">
            <strong>Please add any comments you have for improving the website. We welcome suggestions on specific areas for improvements, features you would like to see added to the site, and examples of what you consider good websites.</strong>
            <div class="feedback-question-fields">
                <textarea name="comments" rows="6" cols="6"></textarea>
            </div>
        </div>

        <input type="button" value="Submit Feedback" class="submit-button" id="feedback-submit">
        <!--<input type="button" value="Close" class="submit-button" id="feedback-close">-->
    </div>

</div>

<script type="text/javascript" src="<?php echo $web_root; ?>js/feedback-form.min.js?u=<?php echo $cssjscacheclear;?>"></script>
