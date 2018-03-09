<?php
class SurveyManage {

	var $survey_id;
	var $survey_name;
	var $survey_key;
	var $show_survey;
	var $tab_image;
	var $background_color;
	var $survey_position;
	var $background_image;
	var $border_color;
	var $border_size;

	var $all_pages;
	//var $post_ids;
	var $lightbox_image;
	var $use_cookie;
	var $cookie_days;
	var $enable_progress_bar;
	var $enable_facebook;
	var $enable_twitter;
	var $enable_linkedin;
	var $icon_size;
	var $survey_orientation;
	var $progress_bar_color;
	var $progress_bar_background_color;
	var $use_widget;

	var $width;
	var $height;
	var $padding;
	var $question_background_color;

	var $questions = array();
	var $question_ids = array();
	var $answer_rules = array();
	var $funnel_size;
	var $action;
	var $updateMsg;
	var $error;
	var $dataErrors = array();

	var $script_reDirect;
	var $script_updateBtn;
	var $use_shortcode;
	var $default_question_header;
	var $survey_theme;
	var $trigger_answers=array();
	var $post_ids;
	var $reset;
/**
	 * Survey Manage Constructor Function
	 *
	 * @return SurveyManage
	 */
	function __construct() {
		$this->action = "";
		$this->updateMsg = "";
		$this->error = false;

		$this->background_color = "#FFFFFF";
		$this->border_color = "#000000";
		$this->survey_id = 0;
		$this->show_survey = "0";

		$this->use_cookie = 0;
		$this->cookie_days = 30;

		$this->enable_progress_bar = 0;
		$this->progress_bar_color = "#000000";
		$this->progress_bar_background_color = "#000000";

		$this->enable_twitter = 0;
		$this->enable_facebook = 0;
		$this->enable_linkedin = 0;
		$this->icon_size = 0;
		$this->survey_orientation = 0;

		$this->use_widget = 0;
		$this->use_shortcode =0;

		$this->width = 320;
		$this->height = 250;
		$this->padding = 15;
		$this->question_background_color = "#CCCCCC";

		$this->survey_key = uniqid("survey_funnel_");

		$this->start_flows[0] = 1;
	}


	/**
	 * Enter description here...
	 *
	 */
	function initSurveyManage() {
		$this->action = $_REQUEST['action'];

		if ((isset($_POST['form_action'])) && ($this->action == '')) {
			$this->action = $_POST['form_action'];
		}

		 global $wpdb;

		switch ($this->action) {
            case 'RESET_FUNNEL':
                $this->survey_id = $_POST['survey_id'];
                $query = "UPDATE {$wpdb->prefix}sf_surveys SET tab_image = NULL ,
                show_survey = NULL ,
                survey_position = 'left',
                background_color = NULL ,
                background_image = NULL ,
                border_color = NULL ,
                border_size = NULL ,
                all_pages = NULL ,
                post_ids = NULL ,
                category_ids = NULL ,
                lightbox_image = NULL ,
                use_widget = NULL ,
                use_cookie = NULL ,
                cookie_days = NULL ,
                enable_progress_bar=NULL,
								enable_twitter=NULL,
								enable_facebook=NULL,
								enable_linkedin=NULL,
								icon_size=NULL,
								survey_orientation=NULL,
                progress_bar_color = NULL,
                progress_bar_background_color = NULL,
                padding = NULL ,
                question_background_color = NULL ,
                answer_images = NULL ,
                answer_flows = NULL ,
                use_shortcode = NULL WHERE {$wpdb->prefix}sf_surveys.survey_id =$this->survey_id;
            ";
            $wpdb->query($query);
		echo 1;
		die();
            break;
    			case 'UPDATE_FUNNEL':
					$this->use_cookie = '';

					$this->setLocalVars();
					$this->validateData();

					if (!$this->error) {
						// Update the data in the database
						$this->updateSurveys();

						// Update the survey questions in the database
						$this->updateSurveyQuestions();

						// Update the survey rules in the database
						$this->updateSurveyRules();

						$this->script_reDirect = "redirectWindowTimeout('admin.php?page=survey_funnel_welcome');";
						//$this->script_reDirect = "jQuery('#survey_frm').submit();";
					}

					// Set the update message error response
					if ($this->error) {
						if ($this->updateMsg == '') {
							$this->updateMsg = "<span class=\"submissionErrors\">You have errors with your submission</span><br><br>";
							//for testing
							print_r($this->dataErrors);exit;
						}

						$this->script_updateBtn = "jQuery('#updateBtn').attr('disabled', false);";
					}

					$this->displayJSON();
					break;

			case 'COPY_FUNNEL':
					$this->survey_id = $_POST['survey_id'];
					$this->survey_name = $_POST['survey_name'];

					$this->copySurvey();

					$this->script_reDirect = "reloadWindowTimeout();";

					$this->displayJSON();
					break;


			case 'DELETE_FUNNEL':
					$this->survey_id = $_POST['survey_id'];
					$this->survey_name = isset($_POST['survey_name'])?$_POST['survey_name']:'';

					$this->deleteSurvey();

					$this->script_reDirect = "reloadWindowTimeout();";

					$this->displayJSON();
					break;

			case 'ACTIVATE_FUNNEL':
					$this->survey_id = $_POST['survey_id'];
					$this->survey_name = isset($_POST['survey_name'])?$_POST['survey_name']:'';

					$this->activateSurvey();

					$this->script_reDirect = "reloadWindowTimeout();";

					$this->displayJSON();
					break;

			default:
					break;
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	function loadSurvey() {
		if (func_num_args() > 0) {
			$this->survey_id = func_get_arg(0);
		}

		global $wpdb;
		$r = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sf_surveys WHERE survey_id = $this->survey_id");
		$this->survey_name = $r->survey_name;
		$this->tab_image = $r->tab_image;
		$this->show_survey = $r->show_survey;
		$this->background_color = $r->background_color;
		$this->survey_position = $r->survey_position;
		$this->background_image = $r->background_image;
		$this->border_color = $r->border_color;
		$this->border_size = $r->border_size;

		$this->all_pages = $r->all_pages;
		$this->post_ids = explode(",", $r->post_ids);
		$this->lightbox_image = $r->lightbox_image;
		$this->use_cookie = $r->use_cookie;
		$this->cookie_days = $r->cookie_days;

		$this->enable_progress_bar = $r->enable_progress_bar;
		$this->progress_bar_color = $r->progress_bar_color;
		$this->progress_bar_background_color = $r->progress_bar_background_color;
		// Get Values of Social Sharing Sites
		$this->enable_facebook = $r->enable_facebook;
		$this->enable_twitter = $r->enable_twitter;
		$this->enable_linkedin = $r->enable_linkedin;
		$this->icon_size = $r->icon_size;

		// Get value of survey Orientation
		$this->survey_orientation = $r->survey_orientation;

		$this->use_widget = $r->use_widget;
		$this->use_shortcode = $r->use_shortcode;
		$this->default_question_header = $r->default_question_header;
		$this->survey_theme = $r->survey_theme;

		$this->funnel_size = $r->funnel_size;
		$this->width = $r->width;
		$this->height = $r->height;
		$this->padding = $r->padding;
		$this->question_background_color = $r->question_background_color;

		$this->trigger_answers = explode("|", $r->answer_images);
		$this->start_flows = explode("|", $r->answer_flows);
		if( empty($r->text_answer_allowed) ){
				$r->text_answer_allowed = 'no';
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	function loadSurveyQuestions() {
		echo "<input type=\"hidden\" name=\"default_question_header\" id=\"sf_defaultQuestionHeader\" value=\"".htmlspecialchars($this->default_question_header, ENT_QUOTES)."\">";
		if (func_num_args() > 0) {
			$this->survey_id = func_get_arg(0);
		}

		global $wpdb;
		//$tr = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sf_survey_questions WHERE survey_id = $this->survey_id AND active_status_id = 1");
		//
		$tr = $wpdb->get_results("SELECT {$wpdb->prefix}sf_survey_questions.*  FROM {$wpdb->prefix}sf_survey_questions
									WHERE {$wpdb->prefix}sf_survey_questions.survey_id = $this->survey_id
									AND {$wpdb->prefix}sf_survey_questions.active_status_id = 1");

		foreach ($tr as $r) {
			if ($r->question_type != 1) {
				$r->question = $r->answers;
				$r->answers=htmlspecialchars($r->answers, ENT_QUOTES);
			}
			$tmpID = $r->survey_question_id;
			/*
			*	Display other_answer field on backend when when Funnel questions load
			*	Added By Kaustubh
			*/
			echo "<input type=\"hidden\" name=\"questions[]\" id=\"key_$tmpID\" value=\"$tmpID\">";
			echo "<textarea style=\"display:none;\" name=\"question_$tmpID\" id=\"question_$tmpID\">".htmlspecialchars_decode($r->question, ENT_QUOTES)."</textarea>";
			echo "<input type=\"hidden\" name=\"font_$tmpID\" id=\"font_$tmpID\" value=\"$r->font\">";
			echo "<input type=\"hidden\" name=\"font_size_$tmpID\" id=\"font_size_$tmpID\" value=\"$r->font_size\">";
			echo "<input type=\"hidden\" name=\"font_color_$tmpID\" id=\"font_color_$tmpID\" value=\"$r->font_color\">";
			echo "<input type=\"hidden\" name=\"answers_$tmpID\" id=\"answers_$tmpID\" value=\"$r->answers\">";
			echo "<input type=\"hidden\" name=\"text_answers_$tmpID\" id=\"text_answers_$tmpID\" value=\"$r->text_answer_allowed\">";
			echo "<input type=\"hidden\" name=\"other_answer_$tmpID\" id=\"other_answer_$tmpID\" value=\"$r->other_answer\">";
			echo "<input type=\"hidden\" name=\"priority_$tmpID\" id=\"priority_$tmpID\" value=\"$r->priority\">";
			echo "<input type=\"hidden\" name=\"question_type_$tmpID\" id=\"question_type_$tmpID\" value=\"$r->question_type\">";
			echo "<input type=\"hidden\" name=\"question_header_$tmpID\" id=\"question_header_$tmpID\" value=\"".htmlspecialchars($r->question_header)."\">";
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	function loadSurveyQuestionDisplay() {
		if (func_num_args() > 0) {
			$this->survey_id = func_get_arg(0);
		}

		// Load the survey rules for display
		$this->loadSurveyRules();

		global $wpdb;

		/*	Fetch new entry (other_answers) from database
		*	Added by Kaustubh
		*/
		$tr = $wpdb->get_results("SELECT {$wpdb->prefix}sf_survey_questions.survey_question_id, {$wpdb->prefix}sf_survey_questions.question, {$wpdb->prefix}sf_survey_questions.answers,{$wpdb->prefix}sf_survey_questions.other_answer, {$wpdb->prefix}sf_survey_questions.question_type FROM {$wpdb->prefix}sf_survey_questions
									WHERE {$wpdb->prefix}sf_survey_questions.survey_id = $this->survey_id
									AND {$wpdb->prefix}sf_survey_questions.active_status_id = 1
									ORDER BY {$wpdb->prefix}sf_survey_questions.priority");

				/*
				End - Content added by Ruqeeba 11 July 2016
				*/

		foreach ($tr as $r) {
			$tmpID = $r->survey_question_id;

			$display = htmlspecialchars_decode($r->question, ENT_QUOTES);
			$jsLink = "editSFQuestion";

			if ($r->question_type == 2) {
				$display = htmlspecialchars_decode($r->answers,ENT_QUOTES);
				$jsLink = "editSFContent";
			}

			//<a href=\"javascript:void(0);\" onclick=\"addSFRule(this, '$tmpID');\"><img src=\"" . plugins_url() . "/survey_funnel/images/btn/rule_btn.png\" border=\"0\"></a>
			// <img src=\"" . plugins_url() . "/survey_funnel/images/btn/sort_btn.png\" class=\"sortHandle\" style=\"cursor: move;\" border=\"0\">

	// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

			if($r-> question_type ==3){
				$display = $r->answers;
				echo "<li id=\"li_$tmpID\" class=\"sf_que_li\">
				<div class=\"sfQuestionDiv\">
				<div class=\"toolbar\">
				<a href=\"javascript:void(0);\" onclick=\"removeSFQuestion('$tmpID');\"><img src=\"" . plugins_url() . "/".SF_PLUGIN_DIR."/admin/images/btn/delete_btn.png\" border=\"0\"></a>
				</div>
				<div class=\"answerToolbar\">
				<div class=\"display\">".htmlspecialchars_decode($display, ENT_QUOTES)."</div>";

			}
	//END By Arvind
			else
		  {
			$header=htmlspecialchars_decode($this->default_question_header, ENT_QUOTES);
                        $isContent="sf_preview_que";
                        if ($r->question_type == 2) $isContent="que_content";

												?>



					<?php

		  	echo "<li id=\"li_$tmpID\" class=\"sf_que_li\">
					<div class=\"sfQuestionDiv\">
						<div class=\"toolbar\">
							<a href=\"javascript:void(0);\" onclick=\"$jsLink('$tmpID', '" . SF_PLUGIN_URL . "');\"><img src=\"" . SF_PLUGIN_URL."/admin/images/btn/edit_btn.png\" border=\"0\"></a>
							<a href=\"javascript:void(0);\" onclick=\"removeSFQuestion('$tmpID');\"><img src=\"" . SF_PLUGIN_URL ."/admin/images/btn/delete_btn.png\" border=\"0\"></a>
						</div>
						<div class=\"display\"></div>
						<div class=\"sf_preview_container\" style=\"\">

						<div class=\"sf_preview_header\" style=\"width=100%;color: white;font-weight: bold;padding: 20px 10px 0;   text-align: center;text-shadow: 0 2px 2px rgba(0, 0, 0, 0.24);\">$header</div>
						<hr style=\"width=100%;border: 0 none;box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 1) inset;height: 8px;\">
						<div class=\"$isContent\" id=\"$isContent\" style=\"width=100%;font-family: helvetica,sans-serif;font-size: 12px;font-style: normal;font-weight: normal;padding: 8px;\" >$display</div>";

						/*
						End - Content added by Ruqeeba 11 July 2016
						*/

		  }

			if ($r->question_type == 1 || $r->question_type==3) // Change the condition by Arvind for User Information
			{
				echo "<div class=\"answers\">";

				$tmpAnswers = explode("|||", $r->answers);

				foreach ($tmpAnswers as $answer_index => $answer_display) {
					if (trim($answer_display)) {
						echo "<div class=\"answerDisplay\" style=\"\">$answer_display</div>";
						echo "<div class=\"answerRule\"><select name=\"answer_rules[$tmpID][]\" onmousedown=\"updateSFFlows(this, 1);\" class=\"sfRuleDropDown\"><option value=\"\">Next Question</option>";

						if (isset($this->answer_rules[$tmpID][$answer_index])) {
							if ($this->answer_rules[$tmpID][$answer_index] != '') {
								for ($i = 1; $i <= $this->answer_rules[$tmpID][$answer_index]; $i ++) {
									echo "<option value=\"$i\""; if ($this->answer_rules[$tmpID][$answer_index] == $i) { echo " selected"; } echo ">$i</option>";
								}
							}
						}

						echo "</select></div>";
						echo "<br clear=\"all\">";
					}
				}
				/*
				*	Div structure of new answer
				*/
				echo "<div class=\"other_answer\" style=\"\">$r->other_answer</div>";
				echo "</div></div>";
			}

			echo "</div>
				  </li>";
		}
	}


	/**
	 * Load the survey rules for display
	 *
	 */
	private function loadSurveyRules() {
		global $wpdb;

		$tr = $wpdb->get_results("SELECT {$wpdb->prefix}sf_survey_questions.survey_question_id, {$wpdb->prefix}sf_survey_rules.answer_index, {$wpdb->prefix}sf_survey_rules.result_answer FROM {$wpdb->prefix}sf_survey_questions, {$wpdb->prefix}sf_survey_rules
									WHERE {$wpdb->prefix}sf_survey_rules.survey_question_id = {$wpdb->prefix}sf_survey_questions.survey_question_id
									AND {$wpdb->prefix}sf_survey_questions.survey_id = $this->survey_id
									AND {$wpdb->prefix}sf_survey_questions.active_status_id = 1
									AND {$wpdb->prefix}sf_survey_rules.active_status_id = 1");

		foreach ($tr as $r) {
			$this->answer_rules[$r->survey_question_id][$r->answer_index] = $r->result_answer;
		}
	}


	/**
	 * Set local vars based on a form submission
	 *
	 */
	private function setLocalVars() {

	/* For magic quotes */
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}



		foreach ($_POST as $key => $value) {
			$this->{$key} = $value;
		}
	}


	/**
	 * Error check submitted info
	 *
	 */
	private function validateData() {
		$DataCleaner = new DataCleaner($this);

		$DataCleaner->validData($this->survey_name, 'survey_name_error');
		$DataCleaner->validData($this->tab_image, 'tab_image_error');

		$DataCleaner->validNum($this->width, 'width_error');
		$DataCleaner->validNum($this->width, 'size_error');
		$DataCleaner->validNum($this->height, 'height_error');
		$DataCleaner->validNum($this->height, 'size_error');

		$DataCleaner->validNum($this->padding, 'padding_error');

		$this->error = $DataCleaner->error;
		$this->dataErrors = $DataCleaner->dataErrors;
	}


	/**
	 * Update the data in the database
	 *
	 */
	private function updateSurveys() {
		global $wpdb;
		$ActiveStatus = new ActiveStatus();

		$date = date("Y-m-d H:i:s");

		if ($this->all_pages == '') { $this->all_pages = 0; }
		if ($this->use_cookie == '') { $this->use_cookie = 0; }
		if ($this->use_shortcode == '') { $this->use_shortcode = 0;	}

		/* Start posts,category insertion */
		$seloption = isset($_POST["post_ids"])?$_POST["post_ids"]:'';

		$cnt = count($seloption);
		$post_ids = "";
		$cat_ids = "";

		for($i=0;$i<$cnt;$i++)
		{
			$selexplode = explode("_",isset($seloption[$i])?$seloption[$i]:'');

			$arr = array(isset($selexplode[1])?$selexplode[1]:'');

			if($selexplode[0] != 'catid')
			{
				//then add post_ids

				$post_ids .=  $selexplode[0] . ",";
			}
			else{
				// Add Categories
				if($selexplode[0] == 'catid')
				{

					$cat_ids .= $selexplode[1] . ",";
		}
			}
		}



			/* end of posts,category insertion */

		if (count($this->trigger_answers)) {
			$answers = stripslashes(implode("|", $this->trigger_answers));
		}

		if (count($this->start_flows)) {
			$flows = implode("|", $this->start_flows);
		}

		//Fix for color picker conflicts with other existing plugins
		//Added March 3rd
		if($_POST['color']){
			$this->background_color = $_POST['color'];
		};

		if(substr($this->background_color, 0, 1) != '#'){
			$this->background_color = '#'.$this->background_color;
		};

		if(substr($this->question_background_color, 0, 1) != '#'){
		$this->question_background_color = '#'.$this->question_background_color;
		};

		if($_POST['border_color']){
			$this->border_color = $_POST['border_color'];
			$this->question_background_color = $_POST['border_color'];
			$this->progress_bar_background_color = $_POST['border_color'];
			$this->progress_bar_color = $_POST['border_color'];
		};

		if(substr($this->border_color, 0, 1) != '#'){
			$this->border_color = '#'.$this->border_color;
		};

		//
		if ($this->survey_id > 0) {
			$wpdb->update($wpdb->prefix . 'sf_surveys',
							array(
									'survey_name' => $this->survey_name,
									'tab_image' => $this->tab_image,
									'survey_position' => $this->survey_position,
									'show_survey' => $this->show_survey,
									'background_color' => $this->background_color,
									'background_image' => $this->background_image,
									'border_color' => $this->border_color,
									'border_size' => $this->border_size,
									'all_pages' => $this->all_pages,
									'post_ids' => $post_ids,
									'category_ids' => $cat_ids,
									'lightbox_image' => $this->lightbox_image,
									'use_cookie' => $this->use_cookie,
									'cookie_days' => $this->cookie_days,
									'enable_progress_bar' => $this->enable_progress_bar,
									'progress_bar_color' => $this->progress_bar_color,
									'enable_facebook' => $this->enable_facebook,
									'enable_twitter' => $this->enable_twitter,
									'enable_linkedin' => $this->enable_linkedin,
									'icon_size' => $this->icon_size,
									'survey_orientation' => $this->survey_orientation,
									'progress_bar_background_color' => $this->progress_bar_background_color,
									'use_widget' => $this->use_widget,
									'use_shortcode' => $this->use_shortcode,
									'default_question_header' => $this->default_question_header,
									'survey_theme' => $this->survey_theme,
									'funnel_size' => $this->funnel_size,
									'width' => $this->width,
									'height' => $this->height,
									'padding' => $this->padding,
									'question_background_color' => $this->question_background_color,

									'answer_images' => $answers,
									'answer_flows' => $flows,

									'active_status_id' => $ActiveStatus->active_records,
									'date_modified' => $date),
							array('survey_id' => $this->survey_id));


			$this->updateMsg = "<span class=\"btext12\"><b>Survey Funnel Updated.</b><br>Reloading Dashboard...</span><br><br>";


		} else {
			$wpdb->insert($wpdb->prefix . 'sf_surveys',
							array(
									'survey_name' => stripslashes($this->survey_name),
									'survey_position' => $this->survey_position,
									'survey_key' => $this->survey_key,
									'tab_image' => $this->tab_image,
									'background_color' => $this->background_color,
									'background_image' => $this->background_image,
									'border_color' => $this->border_color,
									'border_size' => $this->border_size,

									'all_pages' => $this->all_pages,
									'post_ids' => $post_ids,
									'category_ids' => $cat_ids,
									'lightbox_image' => $this->lightbox_image,
									'use_cookie' => $this->use_cookie,
									'cookie_days' => $this->cookie_days,
									'enable_progress_bar' => $this->enable_progress_bar,
									'progress_bar_color' => $this->progress_bar_color,
									'enable_facebook' => $this->enable_facebook,
									'enable_twitter' => $this->enable_twitter,
									'enable_linkedin' => $this->enable_linkedin,
									'progress_bar_background_color' => $this->progress_bar_background_color,
									'use_widget' => $this->use_widget,
									'use_shortcode' => $this->use_shortcode,
									'default_question_header' => $this->default_question_header,
									'survey_theme' => $this->survey_theme,

									'width' => $this->width,
									'height' => $this->height,
									'padding' => $this->padding,
									'question_background_color' => $this->question_background_color,

									'answer_images' => $answers,
									'answer_flows' => $flows,

									'active_status_id' => $ActiveStatus->active_records,
									'date_created' => $date));

			$this->survey_id = $wpdb->insert_id;
			$this->updateMsg = "<span class=\"btext12\"><b>Survey Funnel Created.</b><br>Reloading Dashboard...</span><br><br>";
		}

		do_action( 'sf_after_form_submit',  $_POST );
	}


	/**
	 * Update the survey questions in the database
	 *
	 */
	private function updateSurveyQuestions() {
		global $wpdb;
		$ActiveStatus = new ActiveStatus();
		$date = date("Y-m-d H:i:s");

		$wpdb->update($wpdb->prefix . 'sf_survey_questions', array('active_status_id' => $ActiveStatus->inactive_records, 'date_modified' => $date), array('survey_id' => $this->survey_id), array('%s', '%s'));

		/*
		*	Update the Funnel Question with new answer option
		*/

		foreach ($this->questions as $question_id) {
			if ($this->{"question_type_$question_id"} != 1) {
				$this->{"answers_$question_id"} = $this->{"question_$question_id"};
				$this->{"other_answer_$question_id"} = '';
				$this->{"question_$question_id"} = '';
				$this->{"font_$question_id"} = '';
				$this->{"font_size_$question_id"} = '';
				$this->{"font_color_$question_id"} = '';
				$this->{"text_answers_$question_id"} = '';
			}
			if (is_numeric($question_id)) {
				$wpdb->update($wpdb->prefix . 'sf_survey_questions',
							array(
								  'question' => htmlspecialchars($this->{"question_$question_id"}, ENT_QUOTES),
								  'answers' => stripslashes($this->{"answers_$question_id"}),
								  'other_answer' => stripslashes($this->{"other_answer_$question_id"}),
								  'text_answer_allowed' => strip_tags(stripslashes($this->{"text_answers_$question_id"})),
								  'priority' => $this->{"priority_$question_id"},
								  'question_type' => $this->{"question_type_$question_id"},
								  'active_status_id' => $ActiveStatus->active_records,
								  'date_modified' => $date,
								  'question_header' => isset($this->{"question_header_$question_id"})?$this->{"question_header_$question_id"}:''
								 ),
							array('survey_question_id' => $question_id));

				$this->question_ids[$question_id] = $question_id;

			} else {
				/*
				*	Insert new answer option Label into table
				*/
				$wpdb->insert($wpdb->prefix . 'sf_survey_questions',
							array(
								  'survey_id' => $this->survey_id,
								  'question' => stripslashes($this->{"question_$question_id"}),
								  'answers' => stripslashes($this->{"answers_$question_id"}),
								  'other_answer' => stripslashes($this->{"other_answer_$question_id"}),
								  'text_answer_allowed' => strip_tags(stripslashes($this->{"text_answers_$question_id"})),
								  'priority' => $this->{"priority_$question_id"},
								  'question_type' => $this->{"question_type_$question_id"},
								  'active_status_id' => $ActiveStatus->active_records,
								  'date_created' => $date,
								  'question_header' => isset($this->{"question_header_$question_id"})?$this->{"question_header_$question_id"}:''
								 ));

				$this->question_ids[$question_id] = $wpdb->insert_id;
			}
		}
	}




	/**
	 * Update the survey rules in the database
	 *
	 */
	private function updateSurveyRules() {
		global $wpdb;
		$ActiveStatus = new ActiveStatus();

		$date = date("Y-m-d H:i:s");

		foreach ($this->question_ids as $question_id => $db_id) {
			$wpdb->update($wpdb->prefix . 'sf_survey_rules', array('active_status_id' => $ActiveStatus->inactive_records, 'date_modified' => $date), array('survey_question_id' => $db_id));

			// answer_rules[$tmpID][]
			if (count(isset($this->answer_rules[$question_id])?$this->answer_rules[$question_id]:array()) > 0) {
				foreach ($this->answer_rules[$question_id] as $answer_index => $result_answer) {
					if ($result_answer) {
						$result = $wpdb->update($wpdb->prefix . 'sf_survey_rules',
									array(
								  		'result_answer' => $result_answer,
								  		'active_status_id' => $ActiveStatus->active_records,
								  		'date_modified' => $date
								 		),
									array('survey_question_id' => $db_id, 'answer_index' => $answer_index));

						if (!$result) {
							$wpdb->insert($wpdb->prefix . 'sf_survey_rules',
								array(
									'survey_question_id' => $db_id,
									'answer_index' => $answer_index,
									'result_answer' => $result_answer,
									'active_status_id' => $ActiveStatus->active_records,
								 		'date_created' => $date
							 	));
						}
					}
				}
			}
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	private function copySurvey() {
		global $wpdb;

		$r = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sf_surveys WHERE survey_id = $this->survey_id", ARRAY_A);
		$r['survey_name'] = $this->survey_name;
		unset($r['survey_id']);
		//Need unique survey_key
		$r['survey_key'] = uniqid("survey_funnel_");

		$wpdb->insert($wpdb->prefix . 'sf_surveys', $r);



							$new_survey_id = $wpdb->insert_id;
							/*print_r($r);
							echo 'Working:'.$survey_id;*/
//now questions
$tr = $wpdb->get_results("SELECT {$wpdb->prefix}sf_survey_questions.*  FROM {$wpdb->prefix}sf_survey_questions
									WHERE {$wpdb->prefix}sf_survey_questions.survey_id = $this->survey_id
									", ARRAY_A);



	$newQ = array();

		foreach ($tr as $trr) {
		$origQ = $trr['survey_question_id'];

		unset($trr['survey_question_id']);
		$trr['survey_id'] = $new_survey_id;
		$wpdb->insert($wpdb->prefix . 'sf_survey_questions', $trr);
		//print_r($trr);
		//$newQ = $wpdb->insert_id;


		$newQ[$origQ] = $wpdb->insert_id;
		}

//now rules
$ru = $wpdb->get_results("SELECT wssr.survey_question_id, wssr.answer_index, wssr.result_answer, wssr.active_status_id, wssr.date_modified, wssr.date_created FROM ".$wpdb->prefix . "sf_survey_rules AS wssr LEFT JOIN ".$wpdb->prefix . "sf_survey_questions AS wssq ON wssq.survey_question_id = wssr.survey_question_id WHERE wssq.survey_id = ".$this->survey_id, ARRAY_A);



foreach($ru as $ruu){
$ruu['survey_question_id'] = $newQ[$ruu['survey_question_id']];
$wpdb->insert($wpdb->prefix . 'sf_survey_rules', $ruu);
}

	}

		/**
	 * Delete a survey
	 *
	 */
	private function deleteSurvey() {
		global $wpdb;

		$date = date("Y-m-d H:i:s");

		$wpdb->update($wpdb->prefix . 'sf_surveys',
							array(
									'active_status_id' => 0,
									'date_modified' => $date),
							array('survey_id' => $this->survey_id));

		}

		private function activateSurvey() {
			global $wpdb;

			$date = date("Y-m-d H:i:s");

			$wpdb->update($wpdb->prefix . 'sf_surveys',
								array(
										'active_status_id' => 1,
										'date_modified' => $date),
								array('survey_id' => $this->survey_id));

			}


	/**
	 * Display the JSON info
	 *
	 */
	private function displayJSON() {
		unset($this->post_ids);

		$JSON = new SF_JSON($this);
		$JSON->displayJSON();

		exit;
	}


} // End Survey Manage Class
?>
