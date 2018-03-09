<?php
$survey_id = $_GET['survey_id'];
$icon_size = 0;
//$enable_facebook_temp = isset($_POST["enable_facebook"])?$_POST["enable_facebook"]:0;
//$enable_twitter_temp = isset($_POST["enable_twitter"])?$_POST["enable_twitter"]:0;
//$enable_linkedin_temp = isset($_POST["enable_linkedin"])?$_POST["enable_linkedin"]:0;

if(isset($_POST['save-funnel']))
{
    $show_survey = $_POST['show_survey'];
    $themeColor = isset($_POST['theme-color'])?$_POST['theme-color']:"#373536";

    $sizeWidth = $_POST['size_width'];
    $sizeHeight = $_POST['size_height'];

    $enable_progress_bar = isset($_POST["enable_progress_bar"])?$_POST["enable_progress_bar"]:0;
    $use_cookie = isset($_POST["use-cookie"])?$_POST["use-cookie"]:0;

    $enable_facebook = isset($_POST["enable_facebook"])?esc_attr($_POST["enable_facebook"]):0;
    $enable_twitter = isset($_POST["enable_twitter"])?esc_attr($_POST["enable_twitter"]):0;
    $enable_linkedin = isset($_POST["enable_linkedin"])?esc_attr($_POST["enable_linkedin"]):0;
    if(isset($_POST["social_sharing"])){
      $icon_size = $_POST["social_sharing"];
    }
    $survey_orientation = isset($_POST["survey_orientation"])?$_POST["survey_orientation"]:0;

    $expire_days = $_POST['expire_days'];
    $question_header = $_POST['question_header'];

    $survey_name = $_POST['survey_name'];
    $survey_position = $_POST['survey_position'];

    $tab_image_url = $_POST['tab_image_url'];

    $all_pages = isset($_POST["all_pages"])?$_POST["all_pages"]:0;

    $seloption = isset($_POST["post_ids"])?$_POST["post_ids"]:'';

		$cnt = count($seloption);
		$post_ids = "";
		$cat_ids = "";
    // Declare page id and url of survey page
    $survey_page_id = -1;
    $social_sharing_survey_url = "";

		for($i=0;$i<$cnt;$i++)
		{
			$selexplode = explode("_",isset($seloption[$i])?$seloption[$i]:'');
			$arr = array(isset($selexplode[1])?$selexplode[1]:'');

    			if($selexplode[0] != 'catid')
    			{
    				//then add post_ids
    				$post_ids .=  $selexplode[0] . ",";
            // Copy page or post id in surevy_page_id
            $survey_page_id = $selexplode[0];
    			}
    			else{
    				    // Add Categories
    				    if($selexplode[0] == 'catid')
    				    {
    					         $cat_ids .= $selexplode[1] . ",";
    		        }
    			}

        }
    $lightbox_image_url = $_POST['lightbox_image_url'];
    $use_widget = (isset($_POST['use_widget']))? $_POST['use_widget'] : 0;

    $use_shortcode = (isset($_POST['use_shortcode']))? $_POST['use_shortcode'] : 0;
    $date = date("Y-m-d H:i:s");

    if($themeColor == "#D2CECE") $borderColor = "#ECECEC";
    if($themeColor == "#373536") $borderColor = "#474646";
    if($themeColor == "#EAA8CB") $borderColor = "#d396b7";
    if($themeColor == "#749FB4") $borderColor = "#96B9C8";
    if($themeColor == "#7EB26E") $borderColor = "#99C889";
    if($themeColor == "#B26F70") $borderColor = "#C28B8C";

    if($themeColor == "#B885D4") $borderColor = "#C39FD8";
    if($themeColor == "#8CCBEE") $borderColor = "#A5DDFA";
    if($themeColor == "#C6E98D") $borderColor = "#b5ce8d";
    if($themeColor == "#E8E28D") $borderColor = "#d5d1a6";
    if($themeColor == "#65573E") $borderColor = "#8d7e69";
    if($themeColor == "#D6DFE5") $borderColor = "#ECECEC";

    $wpdb->update($wpdb->prefix . 'sf_surveys',
            array(
                'survey_name' => $survey_name,
                'tab_image' => $tab_image_url,
                'show_survey' => $show_survey,
                'background_color' => $themeColor,
                'border_color' => $borderColor,
                'all_pages' => $all_pages ,
                'post_ids' => $post_ids,
                'category_ids' => $cat_ids,
                'survey_position' => $survey_position,
                'lightbox_image' => $lightbox_image_url,
                'use_widget' => $use_widget,
                'use_cookie' => $use_cookie,
                'cookie_days' => $expire_days,
                'enable_progress_bar' => $enable_progress_bar,
                'enable_facebook' => $enable_facebook,
                'enable_twitter' => $enable_twitter,
                'enable_linkedin' => $enable_linkedin,
                'icon_size' => $icon_size,
                'survey_orientation' => $survey_orientation,
                'progress_bar_color' => $borderColor,
                'progress_bar_background_color' => $borderColor,
                'width' => $sizeWidth,
                'height' => $sizeHeight,
                'padding' => 15,
                'answer_flows' => 1,
                'question_background_color' => $borderColor,
                'use_shortcode' => $use_shortcode,
                'default_question_header' => $question_header,
                'active_status_id' => 1,
                'date_modified' => $date
              ),
            array('survey_id' => $survey_id));

            // Get URL of survey_page_id from wp_posts table
            $social_sharing_survey_url_row = $wpdb->get_row($wpdb->prepare("SELECT post_title,guid FROM {$wpdb->prefix}posts WHERE ID = %s",$survey_page_id));

            if(!empty($social_sharing_survey_url_row)) {
              // User has selected page or post in target
              $social_sharing_survey_url = $social_sharing_survey_url_row->guid;
              $social_sharing_survey_title = $social_sharing_survey_url_row->post_title;
            }
            else {
              // User has selected all pages in target of survey
              $social_sharing_survey_url_row_all = $wpdb->get_row("SELECT post_title,guid FROM {$wpdb->prefix}posts LIMIT 1");
              $social_sharing_survey_url = $social_sharing_survey_url_row_all->guid;
              $social_sharing_survey_title = $social_sharing_survey_url_row_all->post_title;
            }
            // Modify the Message(answers) before saving, to add URL of selected Page in that
            $questions_ids = array();
            if (isset($_POST['questions_ids'])) {
              $questions_ids = $_POST['questions_ids'];
            }

            if(isset($_POST['questions_content'])){
              $questions_content = $_POST['questions_content'];
            }

            if (isset($_POST['questions_answer'])) {
              $questions_answer = $_POST['questions_answer'];
            }

            if (isset($_POST['next_questions'])) {
              $next_questions = $_POST['next_questions'];
            }

            if (isset($_POST['other_options'])) {
              $other_options = $_POST['other_options'];
            }

            if (isset($_POST['option_texts'])) {
              $option_texts = $_POST['option_texts'];
            }

            if (isset($_POST['desc_answers'])) {
              $desc_answers = $_POST['desc_answers'];
            }

            if (isset($_POST['questions_type'])) {
              $questions_type = $_POST['questions_type'];
            }

            // Getting hidden array of answer content of all questions
            $answer_content = "";
            if (isset($_POST['answer_content'])) {
              $answer_content = $_POST['answer_content'];
            }

            $ques_length = sizeof($questions_ids);

            $wpdb->update($wpdb->prefix . 'sf_survey_questions',
                  array('active_status_id' => 0),
                  array('survey_id' => $survey_id));

            for($i=0;$i<$ques_length;$i++)
            {
                $priority = $i + 1;
                $questionId = $questions_ids[$i];

                $updated_question_answer = $questions_answer[$i];
                // Modify our message before saving into DB table (wp_sf_survey_questions)
                if($questions_type[$i]==2){
                  $facebook_url = "href='http://www.facebook.com/sharer.php?u=".$social_sharing_survey_url."&amp;t=".$social_sharing_survey_title."'";
                  //$updated_question_answer=" href=facebook_url ";
                  $updated_question_answer = str_ireplace("href=facebook_url",$facebook_url,$updated_question_answer);
                  $twitter_url = "href='http://twitter.com/home/?status=".$social_sharing_survey_title."-".$social_sharing_survey_url."'";
                  $updated_question_answer = str_ireplace("href=twitter_url",$twitter_url,$updated_question_answer);
                  $linkedin_url = "href='http://www.linkedin.com/shareArticle?url=".$social_sharing_survey_url."&title=".$social_sharing_survey_title."'";
                  $updated_question_answer = str_ireplace("href=linkedin_url",$linkedin_url,$updated_question_answer);
                  //$updated_question_answer="testing";
                  //$twitter_url = "".$social_sharing_survey_title;
                }

                if($questions_ids[$i] != '')
                {
                   $wpdb->update($wpdb->prefix . 'sf_survey_questions',
                        array(
                            'question' => strip_tags(htmlspecialchars($questions_content[$i], ENT_QUOTES)),
                            'answers' => $updated_question_answer,
                            'other_answer' => strip_tags(stripslashes($option_texts[$i])),
                            'answer_content' => $answer_content[$i],
                            'text_answer_allowed' => $desc_answers[$i],
                            'priority' => $priority,
                            'question_type' => $questions_type[$i],
                            'active_status_id' => 1,
                            'date_modified' => $date
                           ),
                        array('survey_question_id' => $questions_ids[$i]));

                }
                else
                {
                  $wpdb->insert($wpdb->prefix . 'sf_survey_questions',
          							array(
          								  'survey_id' => $survey_id,
                            'question' => strip_tags(htmlspecialchars($questions_content[$i], ENT_QUOTES)),
                            'answers' => $updated_question_answer,
                            'answer_content' => $answer_content[$i],
                            'other_answer' => strip_tags(stripslashes($option_texts[$i])),
                            'text_answer_allowed' => strip_tags(stripslashes($desc_answers[$i])),
                            'priority' => $priority,
                            'question_type' => $questions_type[$i],
                            'active_status_id' => 1,
          								  'date_created' => $date
                          	 ));
                    $questionId = $wpdb->insert_id;
                }

                $wpdb->update($wpdb->prefix . 'sf_survey_rules',
                      array('active_status_id' => 0),
                      array('survey_question_id' => $questionId));

                if($questions_type[$i] == 1)
                {
                  $arr_answer = explode("|||",$questions_answer[$i]);
                  $arr_next_que = explode("|||",$next_questions[$i]);
                  $arr_answer_length = sizeof($arr_answer);
                  for($j=0; $j<$arr_answer_length; $j++)
                  {
                    if($arr_next_que[$j] > 0)
                    {
                          $nq = $wpdb->get_row("SELECT {$wpdb->prefix}sf_survey_rules.result_answer FROM {$wpdb->prefix}sf_survey_rules
                                    WHERE {$wpdb->prefix}sf_survey_rules.survey_question_id =  $questionId
                                    AND {$wpdb->prefix}sf_survey_rules.answer_index = $j");

                          if(isset($nq->result_answer))
                          {
                            $wpdb->update($wpdb->prefix . 'sf_survey_rules',
                                  array(
                                      'result_answer' => $arr_next_que[$j],
                                      'active_status_id' => 1,
                                      'date_modified' => $date
                                     ),
                                  array('survey_question_id' => $questionId,
                                  'answer_index' => $j));
                          } else {

                            $wpdb->insert($wpdb->prefix . 'sf_survey_rules',
                    							array(
                    								  'survey_question_id' => $questionId,
                                      'answer_index' => $j,
                                      'result_answer' => $arr_next_que[$j],
                                      'active_status_id' => 1,
                                      'date_modified' => $date,
                    								  'date_created' => $date
                                    	 ));
                          }
                    }
                  }
                }
            }

  header('location:admin.php?page=survey_funnel_welcome');

}

 ?>

 <form id="survey_edit" method="post">
 <!-- BEGIN CONTAINER -->
 <div class="page-container page-content-inner page-container-bg-solid">
    <!-- BEGIN CONTENT -->
    <div class="container-fluid container-lf-space margin-top-30">
       <div class="row">

          <div class="col-md-12">

             <div class="portlet light bordered">

                <div class="portlet-body">
                   <!-- Tabs start here -->

                         <div class="portlet-title tabbable-line">
                            <ul class="nav nav-tabs">
                               <li class="active">
                                  <a href="#tab_1_1" data-toggle="tab"> Design </a>
                               </li>
                               <li>
                                  <a href="#tab_1_2" id="tab_create" data-toggle="tab"> Create/Edit </a>
                               </li>
                               <li>
                                  <a href="#tab_1_3" data-toggle="tab"> Target </a>
                               </li>
                               <li style="float: right;" class="sf_save_funnel_li" >
                                  <input type="submit" class="btn btn-circle red" name="save-funnel" id="save-funnel" value="Save Funnel">
                               </li>
                               <!-- <li>
                                  <a href="#tab_1_4" id="tab_preview" data-toggle="tab"> Preview </a>
                               </li> -->
                            </ul>
                            <div class="tab-content">

                               <!-- Design Tab start here -->
                               <div class="tab-pane active" id="tab_1_1">
                                  <div class="row">
                                     <div class="col-md-8">

                                       <div class="form-group">
                                           <label>Display Settings</label>
                                           <div class="mt-radio-list">
                                               <label class="mt-radio mt-radio-outline sub-option"> Show this survey to all users
                                                   <input type="radio" value="0" name="show_survey" <?php echo ($SurveyManage->show_survey=='0')? 'checked="checked"':'' ?>>
                                                   <span></span>
                                               </label>
                                               <label class="mt-radio mt-radio-outline sub-option"> Show this survey to logged in users only
                                                   <input type="radio" value="1" name="show_survey" <?php echo ($SurveyManage->show_survey=='1')? 'checked="checked"':'' ?>>
                                                   <span></span>
                                               </label>
                                               <label class="mt-radio mt-radio-outline sub-option"> Show this survey to users who are not logged in only
                                                   <input type="radio" value="2" name="show_survey" <?php echo ($SurveyManage->show_survey=='2')? 'checked="checked"':'' ?>>
                                                   <span></span>
                                               </label>
                                           </div>
                                       </div>

                                       <div class="form-group">
                                          <label><strong>Choose Theme Color</strong></label>
                                          <div class="theme-colors">
                                            <?php
                                            $bg_color = "#373536";
                                			  		 $bg_color = $SurveyManage->background_color;
                                            ?>
                                             <input type="radio"  name="theme-color" id="#373536" value="#373536" <?php echo ($bg_color == '#373536' ? 'checked="true"' : '' );?>>
                                             <label for="#373536" id="black"></label>
                                             <input type="radio" name="theme-color" id="#D2CECE" value="#D2CECE" <?php echo ($bg_color == '#D2CECE' ? 'checked="true"' : '' );?>>
                                             <label for="#D2CECE" id="grey"></label>
                                             <input type="radio" name="theme-color" id="#EAA8CB" value="#EAA8CB" <?php echo ($bg_color == '#EAA8CB' ? 'checked="true"' : '' );?>>
                                             <label for="#EAA8CB" id="pink"></label>
                                             <input type="radio" name="theme-color" id="#749FB4" value="#749FB4" <?php echo ($bg_color == '#749FB4' ? 'checked="true"' : '' );?>>
                                             <label for="#749FB4" id="blue"></label>
                                             <input type="radio" name="theme-color" id="#7EB26E" value="#7EB26E" <?php echo ($bg_color == '#7EB26E' ? 'checked="true"' : '' );?>>
                                             <label for="#7EB26E" id="green"></label>
                                             <input type="radio" name="theme-color" id="#B26F70" value="#B26F70" <?php echo ($bg_color == '#B26F70' ? 'checked="true"' : '' );?>>
                                             <label for="#B26F70" id="maroon"></label>
                                             <br/>
                                             <input type="radio" name="theme-color" id="#B885D4" value="#B885D4" <?php echo ($bg_color == '#B885D4' ? 'checked="true"' : '' );?>>
                                             <label for="#B885D4" id="purple"></label>
                                             <input type="radio" name="theme-color" id="#65573E" value="#65573E" <?php echo ($bg_color == '#65573E' ? 'checked="true"' : '' );?>>
                                             <label for="#65573E" id="brown"></label>
                                             <input type="radio" name="theme-color" id="#8CCBEE" value="#8CCBEE" <?php echo ($bg_color == '#8CCBEE' ? 'checked="true"' : '' );?>>
                                             <label for="#8CCBEE" id="lightblue"></label>
                                             <input type="radio" name="theme-color" id="#C6E98D" value="#C6E98D" <?php echo ($bg_color == '#C6E98D' ? 'checked="true"' : '' );?>>
                                             <label for="#C6E98D" id="lightgreen"></label>
                                             <input type="radio" name="theme-color" id="#E8E28D" value="#E8E28D" <?php echo ($bg_color == '#E8E28D' ? 'checked="true"' : '' );?>>
                                             <label for="#E8E28D" id="yellow"></label>
                                             <input type="radio" name="theme-color" id="#D6DFE5" value="#D6DFE5" <?php echo ($bg_color == '#D6DFE5' ? 'checked="true"' : '' );?>>
                                             <label for="#D6DFE5" id="grays"></label>
                                         </div>
                                       </div>

                                       <div class="form-group">
                                           <label><strong>Funnel Size</strong></labe>
                                              <div class="row">

                                                 <div class="col-md-6">
                                                   <div class="input-group input-small margin-top-10">
                                                      <span class="input-group-addon"> Width </span>
                                                      <input type="text" class="form-control" name="size_width" id="size_width" value="<?php echo $SurveyManage->width; ?>" placeholder="">
                                                    </div>
                                                  </div>

                                                 <div class="col-md-6">
                                                   <div class="input-group input-small margin-top-10">
                                                       <span class="input-group-addon"> Height </span>
                                                       <input type="text" class="form-control" name="size_height" id="size_height" value="<?php echo $SurveyManage->height; ?>" placeholder="">
                                                     </div>
                                                  </div>

                                           </div>
                                         </div>


                                         <div class="form-group">
                                            <label><strong>Progress Bar</strong></label>
                                            <div class="mt-checkbox-list">
                                               <label class="mt-checkbox mt-checkbox-outline sub-option"> Enable
                                               <input type="checkbox"  name="enable_progress_bar" id="enable_progress_bar" value="1"<?php if ($SurveyManage->enable_progress_bar) echo "checked"; ?>/>
                                               <span></span>
                                               </label>
                                            </div>
                                         </div>




                                        <div class="form-group">
                                           <label><strong>Use Cookie</strong></label>
                                           <div class="mt-checkbox-list">
                                              <label class="mt-checkbox mt-checkbox-outline sub-option"> Enable
                                              <input type="checkbox" value="1" <?php if ($SurveyManage->use_cookie) { ?>checked<?php } ?> name="use-cookie"  id="use-cookie"/>
                                              <span></span>
                                              </label>
                                           </div>
                                        </div>

                                        <div class="form-group  form-md-floating-label">
                                           <div class="input-group left-addon right-addon">
                                              <span class="input-group-addon">Expire cookie after </span>
                                              <input type="text" class="form-control" value="<?php echo $SurveyManage->cookie_days; ?>" name="expire_days" id="expire_days">
                                              <span class="input-group-addon"> Days</span>
                                           </div>
                                           <span></span>
                                        </div>

                                        <br/>
                                        <div class="form-group">
                                          <label><strong>Social Sharing</strong></label>
                                            <div class="mt-checkbox-list">
                                              <label class="mt-checkbox mt-checkbox-outline sub-option"> Facebook
                                                <input type="checkbox" name="enable_facebook" id="enable_facebook" value="1"<?php if($SurveyManage->enable_facebook) echo "checked"; ?> />
                                                <span></span>
                                              </label>

                                              <label class="mt-checkbox mt-checkbox-outline sub-option"> Twitter
                                                <input type="checkbox" name="enable_twitter" id="enable_twitter" value="1"<?php if ($SurveyManage->enable_twitter) echo "checked"; ?>/>
                                                <span></span>
                                              </label>

                                              <label class="mt-checkbox mt-checkbox-outline sub-option"> Linkedin
                                                <input type="checkbox" name="enable_linkedin" id="enable_linkedin" value="1"<?php if($SurveyManage->enable_linkedin) echo "checked"; ?>/>
                                                <span></span>
                                              </label>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                          <label><strong>Social Sharing Icon Size</strong></label>
                                          <div class="image-radio">
                                            <br>
                                            <input type="radio" name="social_sharing" id="facebook24" value="24"<?php if($SurveyManage->icon_size==24) echo "checked"; ?>/>
                                            <label for="facebook24"><img src="<?php echo WP_PLUGIN_URL ?>/surveyfunnel/admin/images/fb24.png" alt="Facebook 24*24"/></label>
                                            <input type="radio" name="social_sharing" id="facebook32" value="32"<?php if($SurveyManage->icon_size==32) echo "checked"; ?>/>
                                            <label for="facebook32"><img src="<?php echo WP_PLUGIN_URL ?>/surveyfunnel/admin/images/fb32.png" alt="Facebook 32*32"/ ></label>
                                            <input type="radio" name="social_sharing" id="facebook48" value="48"<?php if($SurveyManage->icon_size==48) echo "checked"; ?>/>
                                            <label for="facebook48"><img src="<?php echo WP_PLUGIN_URL ?>/surveyfunnel/admin/images/fb48.png" alt="Facebook 48*48"/></label>

                                          </div>
                                        </div>

                                     </div>
                                  </div>
                               </div>
                               <!-- Design Tab end here -->

                               <!-- Question Tab start here -->
                               <div class="tab-pane" id="tab_1_2">
                                  <div class="clearfix">
                                     <a id="add_question" class="btn btn-circle btn-sm blue">
                                     <span class="glyphicon glyphicon-th-list"> </span>  Add Question
                                     </a>
                                     <a id="add_header" class="btn btn-circle btn-sm blue">
                                     <span class="glyphicon glyphicon-header"> </span> Survey Header
                                     </a>
                                     <a id="add_message" class="btn btn-circle btn-sm blue">
                                     <span class="glyphicon glyphicon-envelope"> </span> Message Screen
                                     </a>
                                     <a id="add_lead_generation_form" class="btn btn-circle btn-sm blue">
                                     <span class="glyphicon glyphicon-user"> </span> Lead Generation Form
                                     </a>
                                  </div>
                                  <div class="question_scrollable" id="style-3">
                                    <?php
                                    global $wpdb;
                                    // Retrieve data from database if exists for editing (Not for newly saving survey questions)
                                    $tr = $wpdb->get_results("SELECT {$wpdb->prefix}sf_survey_questions.survey_question_id, {$wpdb->prefix}sf_survey_questions.question, {$wpdb->prefix}sf_survey_questions.text_answer_allowed,  {$wpdb->prefix}sf_survey_questions.priority, {$wpdb->prefix}sf_survey_questions.answers, {$wpdb->prefix}sf_survey_questions.other_answer, {$wpdb->prefix}sf_survey_questions.answer_content, {$wpdb->prefix}sf_survey_questions.question_type FROM {$wpdb->prefix}sf_survey_questions
                                                  WHERE {$wpdb->prefix}sf_survey_questions.survey_id = $survey_id
                                                  AND {$wpdb->prefix}sf_survey_questions.active_status_id = 1
                                                  ORDER BY {$wpdb->prefix}sf_survey_questions.priority");
                                     ?>

                                     <div id="screens_list">
                                       <?php foreach ($tr as $r) { ?>

                                         <div id="screen_<?php echo $r->priority ?>" class="screen">

                                               <?php if ($r->question_type == 1)
                                               { ?>
                                                     <span id="question_header_<?php echo $r->priority ?>"><a id="edit_<?php echo $r->priority ?>" class="edit_question">Question <b id="queNumber_<?php echo $r->priority ?>"><?php echo $r->priority ?></b></a></span>
                                                     <span style="float:right"><a id="delete_<?php echo $r->priority ?>" class="question_delete"><i class="fa fa-close"></i> </a></span><br>


                                                     <strong id="question_<?php echo $r->priority ?>"><?php echo strip_tags(html_entity_decode($r->question)); ?></strong>

                                                     <?php
                                                     $arr_next_que = array();
                                                     $arr_answer = explode("|||", $r->answers);
                                                     $arr_answer_length = sizeof($arr_answer);
                                                     for($i=0; $i<$arr_answer_length; $i++)
                                                       $arr_next_que[$i] = 0;

                                                     for($i=0; $i<$arr_answer_length; $i++)
                                                     {
                                                         $nq = $wpdb->get_row("SELECT {$wpdb->prefix}sf_survey_rules.result_answer FROM {$wpdb->prefix}sf_survey_rules
                                                                       WHERE {$wpdb->prefix}sf_survey_rules.survey_question_id =  $r->survey_question_id
                                                                       AND {$wpdb->prefix}sf_survey_rules.answer_index = $i
                                                                       AND {$wpdb->prefix}sf_survey_rules.active_status_id = 1");
                                                         if(isset($nq->result_answer))
                                                             $arr_next_que[$i] = $nq->result_answer;
                                                     }

                                                     $next_que = implode("|||",$arr_next_que);
                                                     ?>
                                                     <!-- Hidden field to get answers of Questions -->
                                                     <input type="hidden" name="questions_answer[]" id="questions_answer_<?php echo $r->priority ?>" value="<?php echo $r->answers ?>">
                                                     <input type="hidden" name="next_questions[]" id="next_questions_<?php echo $r->priority ?>" value="<?php echo $next_que ?>">
                                               <?php } ?>

                                               <?php if ($r->question_type == 2)
                                               { ?>
                                                   <span id="question_header_<?php echo $r->priority ?>"><a id="edit_<?php echo $r->priority ?>" class="edit_message">Message <b id="queNumber_<?php echo $r->priority ?>"><?php echo $r->priority ?></b></a></span>
                                                   <span style="float:right"><a id="delete_<?php echo $r->priority ?>" class="question_delete"><i class="fa fa-close"></i> </a></span><br>
                                                   <strong id="question_<?php echo $r->priority ?>"><?php echo htmlspecialchars_decode(stripslashes($r->answers)) ?></strong>
                                                   <input type="hidden" name="questions_answer[]" id="questions_answer_<?php echo $r->priority ?>" value="<?php /* Modified By: Swapnil Shinde(Avoid repeatition in Message Screen)echo stripslashes($r->answers)*/ echo stripslashes($r->answers) ?>">
                                                   <input type="hidden" name="next_questions[]" id="next_questions_<?php echo $r->priority ?>" value="">
                                                   <!-- Hidden fields used to send data of the Social Sharing enable or disable status -->
                                                   <input type="hidden" name="social_sharing_facebook[]" id="social_sharing_facebook" value="<?php //echo $enable_facebook_temp ?>">
                                               <?php } ?>

                                               <?php if ($r->question_type == 3)
                                               { ?>
                                                     <span id="question_header_<?php echo $r->priority ?>"><a id="edit_<?php echo $r->priority ?>" class="edit_form">Lead Generation Form <b id="queNumber_<?php echo $r->priority ?>"><?php echo $r->priority ?></b></a></span>
                                                     <span style="float:right"><a id="delete_<?php echo $r->priority ?>" class="question_delete"><i class="fa fa-close"></i> </a></span><br>
                                                     <strong id="question_<?php echo $r->priority ?>"><?php echo $r->answers ?></strong>
                                                     <input type="hidden" name="questions_answer[]" id="questions_answer_<?php echo $r->priority ?>" value="<?php echo $r->answers ?>">
                                                     <input type="hidden" name="next_questions[]" id="next_questions_<?php echo $r->priority ?>" value="">
                                               <?php } ?>

                                               <input type="hidden" name="questions_ids[]" id="questions_id_<?php echo $r->priority ?>" value="<?php echo $r->survey_question_id ?>">
                                               <input type="hidden" name="questions_content[]" id="questions_content_<?php echo $r->priority ?>" value="<?php echo (stripslashes($r->question)); ?>">

                                               <input type="hidden" name="other_options[]" id="other_options_<?php echo $r->priority ?>" value="">
                                               <input type="hidden" name="option_texts[]" id="option_texts_<?php echo $r->priority ?>" value="<?php echo $r->other_answer ?>">

                                               <input type="hidden" name="desc_answers[]" id="desc_answers_<?php echo $r->priority ?>" value="<?php echo $r->text_answer_allowed ?>">
                                               <input type="hidden" name="questions_type[]" id="questions_type_<?php echo $r->priority ?>" value="<?php echo $r->question_type ?>">
                                               <!-- Hidden field for the getting answer content text or image -->
                                               <input type="hidden" name="answer_content[]" id="answer_content_<?php echo $r->priority ?>" value="<?php echo $r->answer_content ?>">

                                            </div>

                                        <?php } ?>
                                     </div>
                                  </div>

                                        <!-- BEGIN ADD Question FORM PORTLET-->
                                        <div id="section_question" class="portlet light bordered hidden_section">
                                           <div class="portlet-title">
                                              <div class="caption">
                                                 <i class="fa fa-list-ul font-blue-sharp"></i>
                                                 <span class="caption-subject font-blue-sharp bold uppercase">Update Question</span>
                                              </div>
                                              <div class="actions">
                                                    <a id="add_question_btn" name="add_question_btn" class="btn btn-sm btn-circle green">
                                                      <i class="fa fa-save"></i> Update Question </a>
                                              </div>
                                           </div>
                                           <div class="portlet-body form">

                                             <div class="row">
                                               <div class="col-md-7">
                                                 <div class="form-body">
                                                   <div class="form-group">
                                                     <label>Question <b id="question_number"></b></label>
                                                   </div>
                                                   <div class="form-group">
                                                     <label>Question</label>
                                                     <inpu type="hidden" id="queston_id" name="queston_id" value="">
                                                     <textarea class="form-control" name="question_content" id="question_content" rows="3"></textarea>
                                                   </div>

                                                   <div class="form-group">
                                                     <label>Answer Type </label>
                                                     <select name="desc_answer" id="desc_answer" style="width:100%">
                                                       <option value=""> -Select- </option>
                                                       <option value="no">Single Answer (Radio)</option>
                                                       <option value="multi">Multiple Answer (Checkbox)</option>
                                                       <option value="yes">Descriptive Answer</option>
                                                     </select>
                                                   </div>

                                                   <!-- Code added for Answer content as text or image -->
                                                   <div class="form-group answer_content_div">
                                                     <label>Answer Content </label>
                                                     <select name="answer_contents" id="answer_contents" style="width:100%">
                                                       <option value=""> -Select- </option>
                                                       <option value="text">Text</option>
                                                       <option value="image">Image</option>
                                                     </select>
                                                   </div>

                                                   <div class="form-group mt-repeater display-none">
                                                     <div data-repeater-list="group-b" id="answer_list"></div>
                                                     <a id="add_answer" class="btn btn-success mt-repeater-add">
                                                     <i class="fa fa-plus"></i> Add new Answer</a>
                                                   </div>

                                                   <div class="form-group">
                                                     <label>Display Other Option</label>
                                                     <div class="mt-checkbox-list">
                                                        <label class="mt-checkbox mt-checkbox-outline"> Enable
                                                        <input type="checkbox" value="1" name="other_option"  id="other_option"/>
                                                        <span></span>
                                                        </label>
                                                     </div>
                                                     <label>Option Text</label>
                                                     <input type="text" class="form-control" name="option_text" id="option_text" value="" placeholder="">
                                                   </div>

                                                 </div>
                                               </div>
                                               <div class="col-md-5 sf-preview">
                                                 <div id="survey" style="position: relative;">
                                                    <style type="text/css">
                                                        #survey_funnel_front img{
                                                          margin-top:-7px;
                                                        }
                                                        .sfQuestion{
                                                          border-radius: 20px !important;
                                                          font-family: Lato,sans-serif;
                                                          font-size: 12px;
                                                          font-style: normal;
                                                          font-weight: normal;
                                                          margin-bottom: 5px;
                                                          margin-top: 5px;opacity: 1;
                                                          padding: 10px;
                                                          cursor:pointer;
                                                        }
                                                        #question_survey_funnel_preview{
                                                          position: relative;
                                                          width:100%;
                                                          overflow: auto;
                                                          box-sizing: border-box;
                                                          z-index:2000;
                                                          padding:15px;
                                                          /*border-top-left-radius: 10px !important;*/
                                                          border: 0.182em solid;
                                                          }
                                                          #sfp_minimize{
                                                            position: relative;
                                                            border-top-left-radius: 15px !important;
                                                            border-top-right-radius: 15px !important;
                                                            height: 28px;
                                                            cursor: pointer;
                                                            width: 30px;
                                                            text-align: center;
                                                            z-index: 999;
                                                            border: 0.182em solid;
                                                            border-bottom: none !important;
                                                          }
                                                          @media only screen and (min-width:220px) and (max-width:660px){
                                                            #question_survey_funnel_front{
                                                              width:90%
                                                              }
                                                           }
                                                     </style>


                                                    <div id="question_survey_funnel_preview" class="surveyFunnelDiv aa survey_container_body">
                                                       <div id="survey_question_content_preview">

                                                       </div>
                                                    </div>
                                                  </div>

                                               </div>
                                             </div>

                                           </div>
                                        </div>
                                        <!-- END ADD Question FORM PORTLET-->

                                        <!-- BEGIN Header FORM PORTLET-->
                                        <div  id="section_header" class="portlet light bordered hidden_section">
                                           <div class="portlet-title">
                                              <div class="caption">
                                                 <i class="fa fa-header font-blue-sharp"></i>
                                                 <span class="caption-subject font-blue-sharp bold uppercase">Survey Header</span>
                                              </div>
                                           </div>
                                           <div class="portlet-body form">

                                                 <div class="form-body">
                                                    <div class="form-group">
                                                       <label>Survey Header</label>
                                                       <input type="text" class="form-control" value="<?php echo $SurveyManage->default_question_header;?>" name="question_header" id="question_header" placeholder="">
                                                    </div>
                                                 </div>

                                           </div>
                                        </div>
                                        <!-- END Header FORM PORTLET-->

                                        <!-- BEGIN Message FORM PORTLET-->
                                        <div  id="section_message" class="portlet light bordered hidden_section">
                                           <div class="portlet-title">
                                              <div class="caption">
                                                 <i class="fa fa-envelope-o font-blue-sharp"></i>
                                                 <span class="caption-subject font-blue-sharp bold uppercase">Message Content</span>
                                              </div>
                                              <div class="actions">
                                                    <a id="add_message_btn" name="add_message_btn" class="btn btn-sm btn-circle green">
                                                      <i class="fa fa-save"></i> Update Message </a>
                                              </div>
                                           </div>
                                           <div class="portlet-body form">

                                                 <div class="form-body">
                                                   <div class="form-group">
                                                    <label>Message <b id="message_number"></b></label>
                                                   </div>
                                                    <div class="form-group">
                                                       <label>Message Content</label>
                                                       <textarea class="form-control" name="msg_content" id="msg_content" rows="8"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                       <label>Redirect URL </label>
                                                         <input type="text" class="form-control" name="redirect_url" id="redirect_url" placeholder="">
                                                    </div>
                                                 </div>

                                           </div>
                                        </div>
                                        <!-- END Message FORM PORTLET-->


                               </div>
                               <!-- Question Tab end here -->

                               <!-- Target Tab start here -->
                               <div class="tab-pane" id="tab_1_3">

                                 <div class="panel-group accordion" id="accordion3">
                                               <div class="panel panel-info">
                                                   <div class="panel-heading">
                                                       <h4 class="panel-title">
                                                           <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1"> Survey Name </a>
                                                       </h4>
                                                   </div>
                                                   <div id="collapse_3_1" class="panel-collapse in">
                                                       <div class="panel-body">
                                                         <div class="form-group">
                                                            <label>Survey Name</label>
                                                            <input type="text" class="form-control" name="survey_name" id="survey_name" value="<?php echo $SurveyManage->survey_name;?> " placeholder="">
                                                         </div>
                                                       </div>
                                                   </div>
                                               </div>

                                               <div class="panel panel-info">
                                                   <div class="panel-heading">
                                                       <h4 class="panel-title">
                                                           <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2"> Trigger Settings</a>
                                                       </h4>
                                                   </div>
                                                   <div id="collapse_3_2" class="panel-collapse collapse">
                                                       <div class="panel-body">
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

                                                             <div class="row">
                                                                 <div class="col-md-2 col-sm-2 col-xs-2">
                                                                     <ul class="nav nav-tabs tabs-left">
                                                                         <li class="<?php if($survey_type == 1) echo "active";?>">
                                                                             <a href="#tab_6_1" data-toggle="tab"> Slider </a>
                                                                         </li>
                                                                         <li class="<?php if($survey_type == 2) echo "active";?>">
                                                                             <a href="#tab_6_2" data-toggle="tab"> Popup </a>
                                                                         </li>
                                                                         <li class="<?php if($survey_type == 3) echo "active";?>">
                                                                             <a href="#tab_6_3" data-toggle="tab"> Shortcode </a>
                                                                         </li>
                                                                     </ul>
                                                                 </div>
                                                                 <div class="col-md-10 col-sm-10 col-xs-10">
                                                                     <div class="tab-content">
                                                                         <div class="tab-pane <?php if($survey_type == 1) echo "active";?>" id="tab_6_1">
                                                                                 <?php $FormDisplay = new FormDisplay(); ?>
                                                                                 <?php
                                                                                     $survey_position = "Left";
                                                                                     if($SurveyManage->survey_position=="Left-Bottom"){
                                                                                       $survey_position = "Left-Bottom";
                                                                                     }
                                                                                     elseif($SurveyManage->survey_position=="Right-Bottom"){
                                                                                       $survey_position = "Right-Bottom";
                                                                                     }
                                                                                     elseif($SurveyManage->survey_position=="Embedded") {
                                                                                       $survey_position = "Embedded";
                                                                                     }
                                                                                   ?>
                                                                                     <div class="form-group">
                                                                                           <label><strong>Survey Position</strong></label>
                                                                                           <div class="mt-radio-inline">
                                                                                               <label class="mt-radio mt-radio-outline sub-option"> Left
                                                                                                   <input type="radio"  name="survey_position" id="survey_slider_left" value="Left" <?php if($survey_position == "Left") {?> checked <?php }?> />
                                                                                                   <span></span>
                                                                                               </label>
                                                                                               <label class="mt-radio mt-radio-outline sub-option"> Left-Bottom
                                                                                                   <input type="radio" name="survey_position" id="survey_slider_left_bottom" value="Left-Bottom" <?php if($survey_position == "Left-Bottom") {?> checked <?php }?>  />
                                                                                                   <span></span>
                                                                                               </label>
                                                                                               <label class="mt-radio mt-radio-outline sub-option"> Right-Bottom
                                                                                                   <input type="radio" name="survey_position" id="survey_slider_right_bottom" value="Right-Bottom" <?php if($survey_position == "Right-Bottom") {?> checked <?php }?> />
                                                                                                   <span></span>
                                                                                               </label>

                                                                                           </div>
                                                                                       </div>

                                                                                       <div class="form-group">
                                                                                             <label><strong>Slider Image</strong> </label>
                                                                                             <div class="mt-radio-inline">
                                                                                               <input id="tab_image_url" type="hidden" size="35" name="tab_image_url" value="<?php if ($SurveyManage->tab_image != '') { echo $SurveyManage->tab_image; } else { echo SF_PLUGIN_URL .'/admin/images/tabs/click_here.png'; }?>" onfocus="cleanFormError(this);">
                                                                                               <img width="180px" height="100px" id="tab_image_src" src="<?php if ($SurveyManage->tab_image != '') { echo $SurveyManage->tab_image; } else { echo SF_PLUGIN_URL .'/admin/images/tabs/click_here.png'; } ?>">
                                                                                               <br/>
                                                                                               <button type="button" id="tab_image" class="btn blue start sf_upload_media">
                                                                                                     <i class="fa fa-upload"></i>
                                                                                                     <span> Start upload </span>
                                                                                                 </button>
                                                                                                 <button type="button" id="tab_image" class="btn red start sf_remove_media">
                                                                                                       <i class="fa fa-close"></i>
                                                                                                       <span> Remove </span>
                                                                                                   </button>
                                                                                                 <span class="description">Select the slide out image(Recommended size: 100x300px).</span>
                                                                                             </div>
                                                                                         </div>


                                                                                         <div class="form-group">
                                                                                            <label>Select Page/Post</label>
                                                                                            <div class="mt-checkbox-list">
                                                                                               <label class="mt-checkbox mt-checkbox-outline"> <a href="javascript:void(0);" onclick="jQuery('#all_pages').trigger('click');">All Pages / Posts</a>

                                                                                               <input type="checkbox" name="all_pages" id="all_pages" value="1" onchange="if (jQuery(this).attr('checked')) { jQuery('#post_ids').attr('disabled', true); } else { jQuery('#post_ids').attr('disabled', false); }" <?php if ($SurveyManage->all_pages) { ?>checked<?php } ?> />
                                                                                               <span></span>
                                                                                               </label> <span class="description">Select the pages where you want to display survey.</span>
                                                                                            </div>
                     				                                                                   <?php $FormDisplay->getPost('post_ids[]', $SurveyManage->post_ids, $SurveyManage->all_pages); ?>
                                                                                         </div>

                                                                                 </div>
                                                                         <div class="tab-pane fade <?php if($survey_type == 2) echo "active";?>" id="tab_6_2">
                                                                             <div class="form-group">
                                                                                 <label><strong>Trigger Image</strong> </label>
                                                                                 <div class="mt-radio-inline">
                                                                                   <input id="lightbox_image_url" type="hidden" size="35" name="lightbox_image_url" value="<?php echo $SurveyManage->lightbox_image; ?>" onfocus="cleanFormError(this);">
                                                                                   <img width="180px" height="100px" id="lightbox_image_src" src="<?php echo $SurveyManage->lightbox_image; ?>"> </br/>
                                                                                   <button type="button" id="lightbox_image" class="btn blue start sf_upload_media">
                                                                                         <i class="fa fa-upload"></i>
                                                                                         <span> upload </span>
                                                                                     </button>
                                                                                     <button type="button" id="lightbox_image" class="btn red start sf_remove_media">
                                                                                           <i class="fa fa-close"></i>
                                                                                           <span> Remove </span>
                                                                                       </button>
                                                                                     <span class="description">Select the image by clicking it survey will be open as popup.</span>
                                                                                 </div>
                                                                             </div>

                                                                             <div class="form-group">
                                                                                <label>Use In A Sidebar Widget </label>
                                                                                <div class="mt-checkbox-list">
                                                                                   <label class="mt-checkbox mt-checkbox-outline"> Enable
                                                                                   <input type="checkbox" id="use_widget" name="use_widget" value="1" <?php if ($SurveyManage->use_widget) echo "checked"; ?> />
                                                                                   <span></span>
                                                                                   </label>
                                                                                </div>
                                                                             </div>
                                                                           </div>
                                                                         <div class="tab-pane fade <?php if($survey_type == 3) echo "active";?>" id="tab_6_3">
                                                                           <div class="form-group">
                                                                              <label>Embed in Page  </label>
                                                                              <div class="mt-checkbox-list">
                                                                                 <label class="mt-checkbox mt-checkbox-outline"> Enable
                                                                                 <input type="checkbox" id="use_shortcode" name="use_shortcode" value="1" <?php if ($SurveyManage->use_shortcode) echo "checked"; ?> />
                                                                                 <span></span>
                                                                                 </label>
                                                                              </div>
                                                                           </div>

                                                                           <div class="form-group">
                                                                             <label>Survey Orientation</label>
                                                                             <div class="mt-radio-list">
                                                                               <label class="mt-radio mt-radio-outline sub-option"> Portrait
                                                                                 <input type="radio" name="survey_orientation" id="portrait" value="0" <?php if($SurveyManage->survey_orientation==0) echo "checked";?>>
                                                                                 <span></span>
                                                                               </label>
                                                                               <label class="mt-radio mt-radio-outline sub-option"> Landscape
                                                                                 <input type="radio" name="survey_orientation" id="landscape" value="1" <?php if($SurveyManage->survey_orientation==1) echo "checked";?>>
                                                                                 <span></span>
                                                                               </label>
                                                                             </div>
                                                                           </div>
                                                                        </div>

                                                                     </div>
                                                                 </div>
                                                               </div>

                                                       </div>
                                                   </div>
                                               </div>

                                 </div>

                               </div>
                               <!-- Target Tab end here -->

                               <!-- Preview Tab start here -->
                               <div class="tab-pane" id="tab_1_4">
                                 <div class="sf-preview">

                                   <div id="survey" style="position: relative;">
                                      <style type="text/css">
                                      #survey_funnel_front img{
                                        margin-top:-7px;
                                      }
                                      .sfQuestion{
                                        border-radius: 20px !important;
                                        font-family: Lato,sans-serif;
                                        font-size: 12px;
                                        font-style: normal;
                                        font-weight: normal;
                                        margin-bottom: 5px;
                                        margin-top: 5px;opacity: 1;
                                        padding: 10px;
                                        cursor:pointer;
                                      }
                                      #question_survey_funnel_front{
                                        position: relative;
                                        width:100%;
                                        overflow: auto;
                                        box-sizing: border-box;
                                        z-index:2000;
                                        padding:15px;
                                        border-top-left-radius: 10px !important;
                                        border: 0.182em solid;
                                        }
                                        #sfp_minimize{
                                          position: relative;
                                          border-top-left-radius: 15px !important;
                                          border-top-right-radius: 15px !important;
                                          height: 28px;
                                          cursor: pointer;
                                          width: 30px;
                                          text-align: center;
                                          z-index: 999;
                                          border: 0.182em solid;
                                          border-bottom: none !important;
                                        }
                                        @media only screen and (min-width:220px) and (max-width:660px){
                                          #question_survey_funnel_front{
                                            width:90%
                                            }
                                         }
                                       </style>

                                        <div id="sfp_minimize" class="surveyFunnelDiv survey_container_body">-</div>
                                        <div id="question_survey_funnel_front" class="surveyFunnelDiv aa survey_container_body">
                                           <div id="survey_content">

                                           </div>
                                        </div>
                                      </div>

                                 </div>
                               </div>
                               <!-- Preview Tab end here -->

                            </div>
                         </div>

                   <!-- Tabs end here -->
                </div>
             </div>
          </div>
       </div>
    </div>
    <!-- ROW CONTENT -->
 </div>
 <!-- END CONTAINER -->
 </form>
