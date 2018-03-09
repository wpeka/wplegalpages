<div class="panel panel-primary" style="width: 99%;margin-top: 21px;">
    <div class="panel-heading">
      <h3 class="panel-title">Design Survey:
        <a href="admin.php?page=survey_funnel_add&survey_id=<?php echo $SurveyManage->survey_id;?>" id="surveyTitle" style="font-style: italic;font-size: 14px;cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Change Survey Details">
          <?php echo $SurveyManage->survey_name;?>
          <img alt="edit" style="width: 25px;height: 25px;" src="<?php echo WP_PLUGIN_URL_SLLSAFE;?>/admin/images/survey.png">
        </a>
        <button style="float: right;margin-top: -5px;" class="btn btn-success cls-theme-save" onclick="doFormSubmit('Saving Survey Funnel...', jQuery('#survey_frm')); submitAJAX('<?php echo plugins_url( 'json.php?action=UPDATE_FUNNEL', dirname(__FILE__) );?>', jQuery('#survey_frm').serialize());">Save Funnel >></button>
      </h3>
    </div>
    <div class="panel-body">

      <textarea id="tempDefaultHeaderHTML" style="display: none;"><?php echo htmlspecialchars_decode($SurveyManage->default_question_header, ENT_QUOTES); ?></textarea>
      <form id="survey_frm" method="post" onsubmit="return false;">
      <?php $SurveyManage->loadSurveyQuestions(); ?>
      <input type="hidden" name="survey_id" id="survey_id" value="<?php echo $SurveyManage->survey_id; ?>">
      <input type="hidden" name="survey_name" id="survey_name" value="<?php echo $SurveyManage->survey_name; ?>">


      <!-- Nav tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#sfDesignTab" data-toggle="tab">Design</a></li>
    <li><a href="#sfDisplayTab" data-toggle="tab">Trigger Settings</a></li>
  </ul>



  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="sfDesignTab">
      <br/>
      <span class="label label-info" style="font-size:13px;">Display Settings</span><br/><br/>
        <input type="radio" name="show_survey" <?php echo ($SurveyManage->show_survey=='0')? 'checked="checked"':'' ?> value="0" style="margin: 0px 10px 0px 0px;"/>Show this survey to all users<br/><br/>
      <input type="radio" name="show_survey" <?php echo ($SurveyManage->show_survey=='1')? 'checked="checked"':'' ?> value="1" style="margin: 0px 10px 0px 0px;"/>Show this survey to logged in users only<br/><br/>
      <input type="radio" name="show_survey" <?php echo ($SurveyManage->show_survey=='2')? 'checked="checked"':'' ?> value="2" style="margin: 0px 10px 0px 0px;"/>Show this survey to users who are not logged in only
      <hr/>

      <?php
        $bg_color = "#373536";
        $bg_color = $SurveyManage->background_color;
      ?>
        <input type="hidden" name="border_color" value="<?php echo (!empty($SurveyManage->border_color) ? $SurveyManage->border_color : '#FFF' );?>">
        <input type="hidden" name="submitBtnBG">
        <h4>Choose Color </h4>
      <div class="radio-inline">
        <label class="control-label" for="color_black">
          <span id="color-defaults" class="round-button-defaults"> </span>
          <input id="color_black" class="" type="radio" onclick="updateTheme(this);" checked="" value="#373536" name="color" style="cursor: pointer;" <?php echo ($bg_color == '#373536' ? 'checked="true"' : '' );?>>
        </label>
      </div>

      <div class="radio-inline">
        <label for="color_lightgray" class="control-label">
          <span id="color-grays" class="round-button"  style="background: #D2CECE none repeat scroll 0 0;"> </span>
          <input type="radio" onclick="updateTheme(this);" class="" value="#D2CECE" name="color" id="color_lightgray" style="cursor: pointer;" <?php echo ($bg_color == '#D2CECE' ? 'checked="true"' : '' );?>>
        </label>
      </div>

      <div class="radio-inline">
      <label for="color_pink" class="control-label">
      <span id="color-pink" class="round-button" style="background: #EAA8CB none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#EAA8CB" name="color" id="color_pink" style="cursor: pointer;" <?php echo ($bg_color == '#EAA8CB' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_blue" class="control-label">
      <span id="color-blue" class="round-button" style="background: #749FB4 none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#749FB4" name="color" id="color_blue" style="cursor: pointer;" <?php echo ($bg_color == '#749FB4' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_green" class="control-label">
      <span id="color-green" class="round-button" style="background: #7EB26E none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#7EB26E" name="color" id="color_green" style="cursor: pointer;" <?php echo ($bg_color == '#7EB26E' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_maroon" class="control-label">
      <span id="color-maroon" class="round-button" style="background: #B26F70 none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#B26F70" name="color" id="color_maroon" style="cursor: pointer;" <?php echo ($bg_color == '#B26F70' ? 'checked="true"' : '' );?>>
      </label>
      </div>
       <br><br>
      <div class="radio-inline">
      <label for="color_purple" class="control-label">
      <span id="color-purple" class="round-button" style="background: #B885D4 none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#B885D4" name="color" id="color_purple" style="cursor: pointer;" <?php echo ($bg_color == '#B885D4' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_brown" class="control-label">
      <span id="color-brown" class="round-button" style="background: #65573E none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#65573E" name="color" id="color_brown" style="cursor: pointer;" <?php echo ($bg_color == '#65573E' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_lightblue" class="control-label">
      <span id="color-lightblue" class="round-button" style="background: #8CCBEE none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#8CCBEE" name="color" id="color_lightblue" style="cursor: pointer;" <?php echo ($bg_color == '#8CCBEE' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_lightgreen" class="control-label">
      <span id="color-lightgreen" class="round-button" style="background: #C6E98D none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#C6E98D" name="color" id="color_lightgreen" style="cursor: pointer;" <?php echo ($bg_color == '#C6E98D' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_yellow" class="control-label">
      <span id="color-yellow" class="round-button" style="background: #E8E28D none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" value="#E8E28D" name="color" id="color_yellow" style="cursor: pointer;" <?php echo ($bg_color == '#E8E28D' ? 'checked="true"' : '' );?>>
      </label>
      </div>

      <div class="radio-inline">
      <label for="color_ash" class="control-label">
      <span id="color-grays" class="round-button" style="background: #D6DFE5 none repeat scroll 0 0;"> </span>
      <input type="radio" onclick="updateTheme(this);" class="" value="#D6DFE5" name="color" id="color_ash" style="cursor: pointer;" <?php echo ($bg_color == '#D6DFE5' ? 'checked="true"' : '' );?>>
      </label>
      </div>
      <br><br>
      <!--<button class="btn btn-sm btn-info" name="btnChangeDualColor" id="btnChangeDualColor" type="submit">
      Save
      </button>-->
        <hr/>

      <span class="label label-info" style="font-size:13px;">Enable Progress Bar</span><br/><br/>
      <span><input type="checkbox" id="enable_progress_bar" style="margin-bottom:5px;" name="enable_progress_bar" value="1"<?php if ($SurveyManage->enable_progress_bar) { ?>checked<?php } ?>>&nbsp; Enable Progress Bar</span><br>
      <br/>

    <hr/>
      <span class="label label-info" style="font-size:13px;">Funnel Size </span><br/><br/>

      <div id="funnelSizeDiv" >
      <span>Width X Height: </span>
      <span><input type="text" name="width" id="sfFunnelWidth" value="<?php echo $SurveyManage->width; ?>" size="3"/></span><span> X </span><span><input type="text" name="height" id="sfFunnelHeght" value="<?php echo $SurveyManage->height; ?>" size="3"/></span>
      <span><button class="btn btn-default" style="padding: 2px 10px !important;" onclick="applySFTheme();">Apply</button></span>
      </div>

      <hr/>
      <span class="label label-info" style="font-size:13px;">Cookie</span><br/><br/>
    <span><input type="checkbox" style="margin-bottom:5px;" id="use_cookie" name="use_cookie" value="1" onchange="if (jQuery(this).attr('checked')) { jQuery('#cookie_days').attr('disabled', false); } else { jQuery('#cookie_days').attr('disabled', true); }" <?php if ($SurveyManage->use_cookie) { ?>checked<?php } ?>>&nbsp; <a href="javascript:void(0);" onclick="jQuery('#use_cookie').trigger('click');">Use Cookie</a></span><br>
    <span>Expire cookie after <input type="text" name="cookie_days" id="cookie_days" value="<?php echo $SurveyManage->cookie_days; ?>" size="5" maxlength="5" <?php if (!$SurveyManage->use_cookie) { ?>disabled<?php } ?>> days</span>
    <hr/>
    <?php do_action( 'sf_before_add_question', $SurveyManage->survey_id );?>
      <button class="btn btn-primary" onclick="addSFQuestion('<?php echo SF_PLUGIN_URL; ?>');return false;">Add a New Question</button>
      <button class="btn btn-primary" onclick="addDefaultQueHeader('<?php echo SF_PLUGIN_URL; ?>');return false;">Question Header</button>
      <button class="btn btn-primary" onclick="addSFContent('<?php echo SF_PLUGIN_URL; ?>');return false;">Add Content</button>
      <button class="btn btn-primary" onclick="addSFDefaultContent('<?php  echo SF_PLUGIN_URL; ?>');return false;">Add Email Subscription</button><br/>
      <table width="99%" cellpadding="3" cellspacing="1">
      <tr>
        <td id="survey_funnel_question_flow">
          <ul class="sfFlowList" id="sfFlowList">
            <?php $SurveyManage->loadSurveyQuestionDisplay(); ?>
          </ul>
        </td>
      </tr>
    </table>
    </div>
    <div class="tab-pane" id="sfDisplayTab">
                          <?php $FormDisplay = new FormDisplay(); ?>


            <div style="padding:10px;">
                <?php
                $survey_type = 1;
                if ($SurveyManage->lightbox_image != '')
                {
                  $survey_type = 2;
                }
                 if ($SurveyManage->use_shortcode)
                 {
                    $survey_type = 3;
                 }
                 ?>

            <br/>
            <span class="label label-info" style="font-size:13px;">Survey Types : </span>
            <input type="radio" name="survey_type" id="survey_type_slider" value="slider" <?php if($survey_type == 1) {?> checked <?php }?> style="margin: 0px 10px 0px 50px;"/>Slider
            <input type="radio" name="survey_type" id="survey_type_popup" value="popup"  <?php if($survey_type == 2) {?> checked <?php }?> style="margin: 0px 10px 0px 50px;"/>Popup
            <input type="radio" name="survey_type" id="survey_type_shortcode" value="shortcode" <?php if($survey_type == 3) {?> checked <?php }?> style="margin: 0px 10px 0px 50px;"/>Shortcode (Embed in Pages.)

          </div>

          <hr>

            <div style="padding:10px; <?php if($survey_type != 1) {?> display:none; <?php }?>" id="SliderDiv">

              <table style="margin-bottom:10px;">
                  <tr>
                    <?php
                      $SurveyManage->survey_position = trim(strtolower($SurveyManage->survey_position));
                      $survey_position = "left";
                      if($SurveyManage->survey_position=="left-bottom"){
                        $survey_position = "left-bottom";
                      }
                      elseif($SurveyManage->survey_position=="right-bottom"){
                        $survey_position = "right-bottom";
                      }
                    ?>
                    <td valign="top" width="25%" align="left" id="positions"> Survey_Position:
                    </td>
                    <td valign="top" width="25%">
                    <input type="radio" name="survey_position" id="survey_slider_left" value="Left" <?php if($survey_position == "left") {?> checked <?php }?> style="margin: 0px 10px 0px 107px;">Left
                    </td>
                    <td valign="top" width="25%">
                    <input type="radio" name="survey_position" id="survey_slider_left_bottom" value="Left-Bottom" <?php if($survey_position == "left-bottom") {?> checked <?php }?>  style="margin: 0px 10px 0px 50px;">Left-Bottom
                    </td>
                    <td valign="top" width="25%">
                    <input type="radio" name="survey_position" id="survey_slider_right_bottom" value="Right-Bottom" <?php if($survey_position == "right-bottom") {?> checked <?php }?> style="margin: 0px 10px 0px 40px;">Right-Bottom
                    </td>
                  </tr>
              </table>
              <table width="100%" cellpadding="5px" cellspacing="5px">
                  <?php
                    if($survey_position!="left"){
                      echo '<tr class="slider_img" style="display:none">';
                    }
                    else{
                      echo '<tr class="slider_img">';
                    }
                  ?>
                  <td valign="top" width="25%" align="left" id="tab_image_label" >SlideOut Image: &nbsp;</td>
                    <td valign="top" width="25%">
                      <label for="tab_image">
                        <input id="tab_image" type="hidden" size="35" name="tab_image" value="<?php if ($SurveyManage->tab_image != '') { echo $SurveyManage->tab_image; } else { echo SF_PLUGIN_URL .'/admin/images/tabs/click_here.png'; }?>" onfocus="cleanFormError(this);">
                        <input id="tab_image_button" class="WPMediaBtn" type="button" value="Browse...">
                      </label>
                    </td>
                    <td valign="top" width="50%"><span class="description">Select the slide out image.</span></td>
                  </tr>


                  <tr>
                  <td align="left" valign="top" width="25%">	Start Question : &nbsp;</td>
                    <td valign="top" width="25%">
                      <label for="trigger_question_1">
                        <input id="trigger_question_1" type="hidden" size="35" name="trigger_answers[]" value="<?php echo (isset($SurveyManage->trigger_answers[0]))?$SurveyManage->trigger_answers[0]:''; ?>" onfocus="cleanFormError(this);">
                          <!--	<input id="trigger_question_1_button" class="WPMediaBtn" type="button" value="Browse..."> --> <!-- Commented by nishtha on 8th Jan/2015 for CSS look -->
                          &nbsp; <select name="start_flows[]" onmousedown="updateSFFlows(this);" class="sfRuleDropDown">
                          <?php for ($i = 1; $i <= $SurveyManage->start_flows[0]; $i ++) { ?>
                            <option value="<?php echo $i; ?>" <?php if ($SurveyManage->start_flows[0] == $i) { ?>selected<?php } ?>><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                        <!--  	&nbsp;[ <a href="javascript:void(0);" onclick="addSFAnswerQuestion();">Add</a> ] --> <!--  Commented by nishtha on 8th Jan/2015 for CSS look -->
                      </label>
                    </td>
                    <td valign="top" width="50%"><span class="description">Select the number of question to start survey.</span></td>
                  </tr>


                  <?php if (count($SurveyManage->trigger_answers)) { ?>
                    <?php foreach ($SurveyManage->trigger_answers as $key => $answer) { ?>
                        <?php if ($key > 0) { ?>
                        <tr id="answer_trigger_<?php echo $key; ?>">
                          <td align="right" nowrap>&nbsp;</td><td width="">
                            <label>
                              <input id="trigger_question__<?php echo ($key + 1); ?>" type="hidden" size="35" name="trigger_answers[]" value="<?php echo $SurveyManage->trigger_answers[$key]; ?>" onfocus="cleanFormError(this);">
                              <input id="trigger_question__<?php echo ($key + 1); ?>_button" class="WPMediaBtn" type="button" value="Browse...">
                              &nbsp;Start Question: <select name="start_flows[]" onmousedown="updateSFFlows(this);" class="sfRuleDropDown">
                                <?php for ($i = 1; $i <= $SurveyManage->start_flows[$key]; $i ++) { ?>
                                  <option value="<?php echo $i; ?>" <?php if ($SurveyManage->start_flows[$key] == $i) { ?>selected<?php } ?>><?php echo $i; ?></option>
                                <?php } ?>
                              </select>
                              &nbsp;[ <a href="javascript:void(0);" onclick="removeSFTriggerAnswer('trigger_<?php echo $key; ?>');">Remove</a> ]
                            </label>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>

                  <tr id="trigger_question_row"><td></td></tr>
                  <tr><td height="10"></td></tr>

                  <tr>

                    <td align="left" id="page_id_label" valign="top" width="25%" nowrap>Page(s) / Post(s): &nbsp;</td>
                    <td width="25%" align="left" valign="top">
                      <input type="checkbox" name="all_pages" id="all_pages" value="1" onchange="if (jQuery(this).attr('checked')) { jQuery('#post_ids').attr('disabled', true); } else { jQuery('#post_ids').attr('disabled', false); }" <?php if ($SurveyManage->all_pages) { ?>checked<?php } ?>>&nbsp; <a href="javascript:void(0);" onclick="jQuery('#all_pages').trigger('click');">All Pages / Posts</a>
                      <br>
                      <?php $FormDisplay->getPost('post_ids[]', $SurveyManage->post_ids, $SurveyManage->all_pages); ?>
                    </td>
                    <td width="50%" align="left" valign="top"><span class="description">Select the pages where you want to display survey.</span></td>
                  </tr>
                </table>
            </div>

            <div style="padding:10px;<?php if($survey_type != 2) {?> display:none; <?php }?>" id="PopupDiv">
              <table width="100%" cellpadding="5px" cellspacing="5px">
                <tr>
                  <td width="25%" align="left" valign="top" id="lightbox_image_label" nowrap>Trigger Image: &nbsp;</td>
                  <td width="25%" align="left" valign="top">
                    <label for="lightbox_image">
                      <input id="lightbox_image" type="hidden" size="35" name="lightbox_image" value="<?php echo $SurveyManage->lightbox_image; ?>" onfocus="cleanFormError(this);">
                      <input id="lightbox_image_button" class="WPMediaBtn" type="button" value="Browse...">
                    </label>
                  </td>
                  <td width="50%" align="left" valign="top"><span class="description">Select the image by clicking it survey will be open as popup.</span></td>
                </tr>

                <tr>
                <td colspan="3">
                <input type="checkbox" id="use_widget" name="use_widget" value="1" <?php if ($SurveyManage->use_widget) { ?>checked<?php } ?>>&nbsp; Use In A Sidebar Widget
                            <br /><br />
                <a href="" id="resetTriggerImage">Reset</a>
                </td>
                </tr>
              </table>
            </div>

            <div style="padding:10px; <?php if($survey_type != 3) {?> display:none; <?php }?>" id="ShortcodeDiv">
              <table width="100%" cellpadding="5px" cellspacing="5px">
              <!--  	<tr>
                  <td align="right" id="shortcode_image_label" nowrap>ShortCode Image: &nbsp;</td>
                  <td width="100%">
                    <label for="shortcode_image">
                      <input id="shortcode_image" type="hidden" size="35" name="shortcode_image" value="<?php echo $SurveyManage->lightbox_image; ?>" onfocus="cleanFormError(this);">
                      <input id="shortcode_image_button" class="WPMediaBtn" type="button" value="Browse...">
                    </label>
                  </td>
                </tr>-->

                <tr>
                <td width="100%" align="left" valign="top">
                <input type="checkbox" id="use_shortcode" name="use_shortcode" value="1" <?php if ($SurveyManage->use_shortcode) { ?>checked<?php } ?>>&nbsp; Embed in Page
                            <br /><br />
                <!-- <a href="" id="resetTriggerImage">Reset</a> -->
                </td>
                </tr>
              </table>
            </div>

            <hr>


  </div>
  </div>
  <table width="" cellpadding="3" cellspacing="1">
    <tr>
      <td colspan="2">
        <br>
        <div id="updateMsg" class="updateMsg"></div>
        <input type="submit" class="btn btn-success cls-theme-save" name="store_key" value="<?php _e('Save Funnel') ?>" onclick="doFormSubmit('Saving Survey Funnel...', jQuery('#survey_frm')); submitAJAX('<?php echo plugins_url( 'json.php?action=UPDATE_FUNNEL', dirname(__FILE__) );?>', jQuery('#survey_frm').serialize());">
        <?php /*
        <input type="button" value="<?php _e('Disable Funnel') ?>" onclick="">
        */ ?>
        <input type="button" class="btn btn-success" value="<?php _e('Cancel') ?>" onclick="window.location.href='admin.php?page=survey_funnel_welcome';">
<input type=button class="btn btn-success reset-button" name="store_key" value="<?php _e('Reset') ?>" onclick="doFormSubmit('Reset Survey Funnel...', jQuery('#survey_frm')); submitAJAX('<?php echo plugins_url( 'json.php?action=RESET_FUNNEL', dirname(__FILE__) );?>', jQuery('#survey_frm').serialize());">
      </td>
    </tr>
  </table>
  </form>
    </div>
  </div>
