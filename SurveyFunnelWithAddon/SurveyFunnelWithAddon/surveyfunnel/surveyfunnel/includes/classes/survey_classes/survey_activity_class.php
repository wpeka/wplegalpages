<?php

class SurveyActivity {

	var $survey_id;
	var $survey_theme;
	var $survey_key;
	var $survey_question_id;
	var $survey_priority;
	var $question_background_color;
	var $answer;
	var $WP_PLUGIN_URL;
	var $light_box;
	var $trigger_answer;
	var $answer_index;
	var $result_answer;
	var $question_type;
	var $extra_ans;

	var $question_display;

	var $action;
	var $script_reDirect;
	var $script_updateBtn;

	var $start;
	var $end;
    var $totalquestion;
    var $current_survey_array;
	var $percentage_calculator;
	/**
	 * Survey Activity Constructor Function
	 *
	 * @return SurveyActivity
	 */
	function __construct() {
		$this->action = "";
		$this->survey_priority = 0;
		$this->survey_question_id = 0;
	}


	/**
	 * Enter description here...
	 *
	 */
	function initSurveyActivity() {

	global $wpdb;
		if( isset( $_POST['survey_key'] ) ){
			$this->survey_key = sanitize_text_field ( $_POST['survey_key'] );
		}
		if( isset( $_REQUEST['survey_id'] ) ){
 			$this->survey_id = sanitize_text_field ( $_REQUEST['survey_id'] );
		}
		$r = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sf_surveys WHERE survey_key = '$this->survey_key'");

		/* Code added by Nishtha for progress bar */
		if(isset($r->survey_id)){
		$result= $wpdb->get_row("SELECT count(question) as total_questions , survey_question_id FROM {$wpdb->prefix}sf_survey_questions WHERE survey_id = '$r->survey_id' and active_status_id = '1'");
		$this->totalquestion=$result->total_questions;

		$result_survey_array = $wpdb->get_results("SELECT survey_question_id FROM {$wpdb->prefix}sf_survey_questions WHERE survey_id = '$r->survey_id' and active_status_id = '1'");

		$a = array();
		$b = array();
		foreach ($result_survey_array as $a){
			array_push($b,$a->survey_question_id);

		}
		$this->current_survey_array = $b ;

		}
		/* End Code added by Nishtha for progress bar */


	//added by Jack
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );




		$this->action = $_REQUEST['action'];

		switch ($this->action) {
			case 'LOAD_FUNNEL':
					$this->survey_key = sanitize_text_field ( $_POST['survey_key'] );
					//$this->WP_PLUGIN_URL = $_POST['url'];
					$this->WP_PLUGIN_URL = WP_PLUGIN_URL_SLLSAFE;
					$this->light_box = sanitize_text_field ( $_POST['light_box'] );
					$this->trigger_answer = sanitize_text_field ( $_POST['trigger_answer'] );

					if ($this->loadFirstQuestion()) {
						// Add an impression for a lightbox start
						if ($this->light_box == 'true') {
							$Survey = new Survey();
							$Survey->addImprint($this->survey_id);
						}
					  if ($this->light_box == 'slide') {
							$Survey = new Survey();
							$Survey->addImprint($this->survey_id);
						}

						if (!$this->loadNextQuestion()) {
							$this->question_display .= "|||eosf";

							// Set the completion cookie (if necessary)
							$this->setSFCookie();

							// Add a completion to the stats table
							$this->addCompletion();
						}
						echo $this->question_display;
					}

					exit;
					break;

			case 'SUBMIT_ANSWER':
					$this->survey_id = sanitize_text_field ($_POST['survey_id'] );
					$this->survey_question_id = sanitize_text_field ($_POST['survey_question_id'] );
					$this->survey_priority = sanitize_text_field ( $_POST['survey_priority'] );
					$this->survey_key = sanitize_text_field ( $_POST['survey_key'] );
					$this->question_background_color = sanitize_text_field ($_POST['color'] );
					$this->answer_index = sanitize_text_field ( $_POST['answer_index'] );
					$this->answer = isset( $_POST['answer'] ) ? sanitize_text_field ( $_POST['answer'] ) : '';
					$this->extra_ans = isset($_POST['extra_ans']) ? sanitize_text_field ($_POST['extra_ans'] ) : '';
					//$this->WP_PLUGIN_URL = $_POST['url'];
					$this->WP_PLUGIN_URL = WP_PLUGIN_DIR;
					// Add the answer to the results table
					$this->addAnswer();

					// Check to see if the specified answer has a rule
					$this->checkForAnswerRule();

					// Load the next question in the current column
					if (!$this->loadNextQuestion()) {
						$this->question_display .= "|||eosf";

						// Set the completion cookie (if necessary)
						$this->setSFCookie();

						// Add a completion to the stats table
						$this->addCompletion();

						do_action( 'sf_after_survey_completion', $_POST );
					}

					echo $this->question_display;

					exit;
					break;

	// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

		case 'SUBMIT_USERINFO':

						$this->survey_id = sanitize_text_field ( $_POST['survey_id'] );
						$this->survey_question_id = sanitize_text_field ($_POST['survey_question_id'] );

						$this->survey_key = sanitize_text_field ( $_POST['survey_key'] );
						$this->survey_priority = sanitize_text_field ($_POST['survey_priority'] );
						$this->question_background_color = isset($_POST['color'])? sanitize_text_field ($_POST['color']):'';


						$this->user_name = sanitize_text_field ($_POST['user_name'] );
						$this->email_id = sanitize_text_field ($_POST['email_id'] );
						$this->WP_PLUGIN_URL = WP_PLUGIN_DIR;

						// Add the answer to the results table
						$this->addAnswer1();

						// Check to see if the specified answer has a rule
						$this->checkForAnswerRule();

						// Load the next question in the current column
						if (!$this->loadNextQuestion()) {
							$this->question_display .= "|||eosf";

							// Set the completion cookie (if necessary)
							$this->setSFCookie();

							// Add a completion to the stats table
							$this->addCompletion();
							do_action( 'sf_after_user_info_submission', $_POST );
						}

						echo $this->question_display;

						exit;
						break;

		case 'CANCELUSERINFO':
$this->survey_id = $_POST['survey_id'];
						$this->survey_question_id = sanitize_text_field ( $_POST['survey_question_id'] );

						$this->survey_key = sanitize_text_field ( $_POST['survey_key'] );
						$this->survey_priority = sanitize_text_field ( $_POST['survey_priority'] );
						$this->question_background_color = isset($_POST['color'])?sanitize_text_field ($_POST['color'] ):'';
				   		$this->WP_PLUGIN_URL = WP_PLUGIN_DIR;
						// Check to see if the specified answer has a rule
						$this->checkForAnswerRule();

						// Load the next question in the current column
						if (!$this->loadNextQuestion()) {
							$this->question_display .= "|||eosf";

							// Set the completion cookie (if necessary)
							$this->setSFCookie();

							// Add a completion to the stats table
							$this->addCompletion();
						}

						echo $this->question_display;

			           exit;
					   break;
	// End By Arvind


			default:
					break;
		}
	}


	/**
	 * Add the answer to the results table
	 *
	 */
	private function addAnswer() {
		global $wpdb;
		$ActiveStatus = new ActiveStatus();
		//---------------------Begin -Added by Dinesh on 3rd September 2013----------
		// Insert the new users id while taking survey....
		$insanswer = $wpdb->get_row("SELECT priority FROM {$wpdb->prefix}sf_survey_questions WHERE survey_id = '$this->survey_id' and survey_question_id = '$this->survey_question_id'");
		if($insanswer->priority =='1')
		{
			$wpdb->insert($wpdb->prefix . 'sf_survey_user_information',
					array(

							'user_name' =>'',
							'email_id'=>''
					));

		}

		// ----------End by Dinesh on 3rd September, 2013----------------
		$date = date("Y-m-d H:i:s");
		// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation
		$r1 = $wpdb->get_row("SELECT user_id FROM {$wpdb->prefix}sf_survey_user_information WHERE 1 ORDER BY user_id DESC LIMIT 1");

		$wpdb->insert($wpdb->prefix . 'sf_survey_results',
						array(
								'survey_id' => $this->survey_id,
								'survey_question_id' => $this->survey_question_id,
								'answer' => $this->answer,
								'extra_ans' => $this->extra_ans,
								'active_status_id' => $ActiveStatus->active_records,
								'date_created' => $date,
								'user_id'=> $r1->user_id));
		// End
	}


// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

	private function addAnswer1() {

		global $wpdb;
		$ActiveStatus = new ActiveStatus();

		$r1 = $wpdb->get_row("SELECT user_id FROM {$wpdb->prefix}sf_survey_user_information WHERE 1 ORDER BY user_id DESC LIMIT 1");


		$wpdb->update(
				($wpdb->prefix).'sf_survey_user_information',
				array(
						'user_name' =>$this->user_name,
						'email_id' => $this->email_id	),
				array( 'user_id' => $r1->user_id ));

	}
	// END

	/**
	 * Check to see if the specified answer/question has a column change
	 *
	 */
	private function checkForAnswerRule() {
		global $wpdb;
		$ActiveStatus = new ActiveStatus();

		$r = $wpdb->get_row("SELECT result_answer FROM {$wpdb->prefix}sf_survey_rules WHERE survey_question_id = '$this->survey_question_id' AND answer_index = '$this->answer_index' AND active_status_id = 1");

		if (isset($r->result_answer)) {
			$this->result_answer = $r->result_answer;

		} else {
			$this->result_answer = 0;
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	private function loadFirstQuestion() {
		/* Code added by Nishtha for progress bar */

		delete_option('countofans'.get_current_user_id());

		/* Code added by Nishtha for progress bar */

		if (func_num_args() > 0) {
			$this->survey_key = func_get_arg(0);
		}

		global $wpdb;
		$ActiveStatus = new ActiveStatus();

		$r = $wpdb->get_row("SELECT survey_id, question_background_color, answer_flows,survey_theme FROM {$wpdb->prefix}sf_surveys WHERE survey_key = '$this->survey_key' AND active_status_id = '$ActiveStatus->active_records'");

		if (!isset($r->survey_id)) {
			return false;
		}

		if (!isset($r->answer_flows)) {
			return false;
		}

		$this->survey_id = $r->survey_id;
		$this->survey_theme = $r->survey_theme;
		$this->question_background_color = $r->question_background_color;

		$tmpColumns = explode("|", $r->answer_flows);

		$this->result_answer = isset($tmpColumns[$this->trigger_answer])?$tmpColumns[$this->trigger_answer]:'';

		return true;
	}


	/**
	 * Load the next question in the current column
	 *
	 */
  private function loadNextQuestion() {
  	 global $wpdb;
		 // Live Answers counter to arrange answers dynamically in landscape Orientation
		 $live_answer_counter = 0;
		 // Get the survey Orientation value to change further styles
    	$ActiveStatus = new ActiveStatus();

		if ($this->result_answer > 0) {
			$r = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sf_survey_questions WHERE survey_id = '$this->survey_id' AND active_status_id = '$ActiveStatus->active_records' ORDER BY priority LIMIT " . ($this->result_answer - 1) . ",1");

		} else {
			$r = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sf_survey_questions WHERE survey_id = '$this->survey_id' AND survey_question_id <> '$this->survey_question_id' AND priority > $this->survey_priority AND active_status_id = '$ActiveStatus->active_records' ORDER BY priority LIMIT 1");
		}

		/*Code added by nishtha for progress bar */
		/*code change by ishani to fix progress bar bug*/


		$total_questions=$this->totalquestion;

		$replied=get_option('countofans'.get_current_user_id());

		if($replied=='') {
			$replied=0;
		}
		else{
			$replied=$replied+1;
		}



		update_option('countofans'.get_current_user_id(),$replied);
		if(isset($r->survey_question_id)){
		if(in_array($r->survey_question_id,$this->current_survey_array)){
			$this->percentage_calculator = array_search($r->survey_question_id, $this->current_survey_array);
		}
		}
		$percentage=((($this->percentage_calculator)/$total_questions)*100);
		$q=((($this->percentage_calculator)/$total_questions)*100)."%";


		/*code change by ishani to fix progress bar bug*/
		/*End Code added by nishtha for progress bar */


		if (!isset($r->survey_question_id)) {
			return false;
		}

      $header = $wpdb->get_row("SELECT default_question_header from {$wpdb->prefix}sf_surveys where survey_id=".$this->survey_id);
      $sf_question_header=htmlspecialchars_decode($header->default_question_header, ENT_QUOTES);

		if ($r->question_type == 1) {

			$sf_question=str_ireplace("\'", "'",$r->question);
			$this->question_display='';
			$bg_color = $wpdb->get_results('SELECT background_color FROM '.$wpdb->prefix.'sf_surveys WHERE survey_id = '.$this->survey_id );
			$hover_color = '#373536';
			$text_color = $bg_color[0]->background_color;
			$text_color_mouseout = '#373536';
			$color_arr = array('#65573E','#B885D4','#B26F70','#EAA8CB','#373536','#749FB4','#7EB26E');
			if(in_array($bg_color[0]->background_color, $color_arr) ){
				$hover_color = '#FFF';
				$text_color = $bg_color[0]->background_color;
				$text_color_mouseout = '#FFF';
			}
			if($bg_color[0]->background_color=='#373536'){
				$text_color = '#797979';
				$text_color_mouseout = '#FFF';
				$this->question_background_color = '#474646';
			}

			if ($sf_question_header!='') {
				/*
				Start - Content added by Ruqeeba 11 July 2016
				*/
				$this->question_display.="<div style=\" color:".$text_color_mouseout.";width=100%;border-radius: 5px;font-family: helvetica,sans-serif;  font-size: 12px;font-style: normal;font-weight: normal;padding: 8px;text-align: center; color:".$text_color_mouseout."\">".$sf_question_header."</div>";
				$this->question_display.="<hr style=\"width=100%;border: 0 none;box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 1) inset;height: 8px;\">";
				/*
				End - Content added by Ruqeeba 11 July 2016
				*/
			}

			/*
+			*	Get Funnel height
+			*/
			$sfp_height = $wpdb->get_results('SELECT height FROM '.$wpdb->prefix.'sf_surveys WHERE survey_id = '.$this->survey_id );


			if( !empty($sfp_height)){
				$funnel_height = ($sfp_height[0]->height) + 50;
			}

			// Get the Orientation for the Survey answers
			$survey_orientation_row = $wpdb->get_row('SELECT survey_orientation,width FROM '.$wpdb->prefix.'sf_surveys WHERE survey_id = '.$this->survey_id);
			if (!empty($survey_orientation_row)) {
					$survey_orientation = $survey_orientation_row->survey_orientation;
					$survey_max_width = $survey_orientation_row->width;

			}

			$this->question_display .=  "<div style=\"text-align:center; font-family: $r->font; font-size: $r->font_size; color: $r->font_color; padding-bottom:20px;color:".$text_color_mouseout.";margin-top:3%;margin-bottom:2%;echo ($survey_orientation==1)? margin-top:2%;margin-bottom:0%;margin-top:2%; : \">".stripslashes(htmlspecialchars_decode($sf_question, ENT_QUOTES))."</div>";

			if ( $r->answers && $r->text_answer_allowed != 'yes') {
				// Counting the number of answers for the question
				$answers_count_arr = explode("|||", $r->answers);
				$answers_count = 0;
				foreach ($answers_count_arr as $key => $value) {
						$answers_count++;
				}

				// Check whether Other Answer available or not
				if($r->other_answer!=""){
					$answers_count++;
				}

				$answers = explode("|||", $r->answers);
				foreach ($answers as $answer_index => $newAnswer) {
					$jsAnswer = str_ireplace("'", "\'", $newAnswer);
					// Added by Swapnil Shinde
					$extra_answer = '';
					// Check for the landscape mode on or off
					$changed_landscape_answer_styles = "";
					$answer_width=0;
					if($survey_orientation==1 && $survey_max_width>=900){
						if ($answers_count<=4) {
								$answer_width = ($survey_max_width/$answers_count)-(200/$answers_count);
						}
						else if ($answers_count>4) {
								$answer_width = ($survey_max_width/4)-(200/4);
						}

						//$answer_width=20;
						$changed_landscape_answer_styles = ";float:left;width:". $answer_width."px; padding:1.5%; margin:2%;font-size:13px;padding-top:1.5%;margin-top:1%";
					}

					$image_width = 120;
					$input_style = "";
					$background_div_style = "";
					if ($r->answer_content=="image") {

						$input_style = "display:none;";
						$send_button_style = "";
						// Arrange send button for odd answers_count$send_button_style = "margin-top:20%;";
						if($answers_count%2 != 0 && ($survey_orientation==0) || ($survey_max_width>=500 && $survey_orientation==0)){
							$send_button_style = "margin-top:5%;";
						}
						if($survey_orientation==0 && $survey_max_width<=500)
						{
							$image_width = ($survey_max_width/2)-(50);
						}
						else if($survey_orientation==1 && $survey_max_width>=900){
							if ($answers_count<=4) {
									$image_width = ($survey_max_width/$answers_count)-(400/$answers_count);
									$send_button_style = "margin-top:5%;";
							}
							else if ($answers_count>4) {
									$image_width = ($survey_max_width/4)-(200/4);
							}
						}
						$background_div_style = "background-color:transparent;width:".$image_width."px;float:left;margin-left:10%;border-radius:2px;padding-left:0px;";
						$tmpImageAnswer = "<img width='".$image_width."px' style=\'height:80px !important;\' src='".$newAnswer."'>";
						$background_color_of_question = $bg_color[0]->background_color;
					}
					else {
						$tmpImageAnswer = $newAnswer;
						$background_color_of_question = $this->question_background_color;
					}

					if($r->text_answer_allowed == 'no') {
						// If the answer is not descriptive
					$this->question_display .= "<div class=\"sfQuestion\" style=\"background-color:".$background_color_of_question.";
					border-radius: 20px;width:100%;font-family: Lato,sans-serif;font-size: 13px;font-style: normal;font-weight: normal;opacity: 1;padding: 2% 2% 2% 2%;margin: 2%;/*margin-top:4%;*/cursor:pointer; color:".$text_color_mouseout.$changed_landscape_answer_styles.$background_div_style.";\" onclick=\"checkSFCheckmark(jQuery(this));submitSFAnswer('$this->survey_key', '" . SF_PLUGIN_URL . "', $r->survey_id, $r->survey_question_id, $r->priority, '$jsAnswer', '$extra_answer', '$this->question_background_color', $answer_index);\" onmouseout=\"jQuery(this).css({'background':'".$background_color_of_question."','color':'".$text_color_mouseout."'});\" onmouseover=\"jQuery(this).css({'background':'".$hover_color."','color':'".$text_color."'});\">
													<span ><input type=\"radio\" name=\"survey\" class=\"sf_answer_radio\"  style=\"margin-top:-1.5%;margin-left:5px;vertical-align: middle;".$input_style."\"><span style=\"cursor:pointer; padding-left: 5px;\">$tmpImageAnswer</span></span>
												</div>";
					} else {
						$this->question_display .= "<div class=\"sfQuestion\" style=\"background-color:".$background_color_of_question.";
						border-radius: 20px;font-family: Lato,sans-serif;font-size: 13px;font-style: normal;font-weight: normal;opacity: 1;padding: 2% 2% 2% 2%;margin: 2%;cursor:pointer;/*margin-top:4%*/; color:".$text_color_mouseout.$changed_landscape_answer_styles.$background_div_style.";\" onmouseout=\"jQuery(this).css({'background':'".$background_color_of_question."','color':'".$text_color_mouseout."'});\" onmouseover=\"jQuery(this).css({'background':'".$hover_color."','color':'".$text_color."'});\">
														<span ><label><input type=\"checkbox\" name=\"survey\" class=\"sf_answer_radio\" value=\"".$newAnswer."\" style=\"margin-top:-1.5%;margin-left:5px;vertical-align: middle;".$input_style."\"><span style=\"cursor:pointer; padding-left: 5px;\">$tmpImageAnswer</span></label></span>
													</div>";
					}
				}

				if($r->text_answer_allowed == 'multi')
				{
					$this->question_display .= "<div class=\"sfp_response_send_btn\" style=\"text-align: right;\" ><input type=\"button\" class=\"checkbox_answer_submit\" style=\"color:".$text_color_mouseout.";cursor: pointer; margin: 5px;background-image: none; background-color:transparent;".$send_button_style."\" value=\"Send\" onmouseout=\"jQuery(this).css('background', 'transparent');\" onmouseover=\"jQuery(this).css('background','#EFEFEF');\"/></div>";
					$this->question_display .="<script>
					jQuery('.checkbox_answer_submit').click(function(){
						var val = '';

						jQuery.each(jQuery('input:checkbox[name=survey]:checked'), function(){
							val += jQuery(this).val()+'|||';

						});


					if(val != '')
					{
						submitSFAnswer('$this->survey_key', '" . WP_PLUGIN_URL_SLLSAFE . "', $r->survey_id, $r->survey_question_id, $r->priority, val, '', '$this->question_background_color', 0, '$this->survey_theme');
					}
				});
											   	var sfUseCheckmark=true;
											   	if(sfUseCheckmark) jQuery('.sfCheckmark').css('background','url(\"".WP_PLUGIN_URL_SLLSAFE."/public/images/checkmark.png\") repeat scroll 0px 0 rgba(0, 0, 0, 0)');
											    function checkSFCheckmark(obj){
											   			if(sfUseCheckmark)
											   				{
											   					jQuery(obj).find('img').css('background','url(\"".WP_PLUGIN_URL_SLLSAFE."/public/images/checkmark.png\") repeat scroll -20px 0 rgba(0, 0, 0, 0)');
											   				}
												}
											   </script>";
				}


			/*	On FrontEnd Survey form added New Field
				*	to Extra option Answer. Get its value and pass directly
				*	to javascript variable 'new_answer'
				*	Added By Kaustubh - START
				*/
				if ($r->other_answer) {
					//$changed_landscape_answer_styles = "margin:2%;padding:1.5%;float:left;width:20%;";
					$this->question_display .= "<div class=\"other_answer\" style=\"background-color:$this->question_background_color; cursor:pointer;border-radius: 20px;font-family: Lato,sans-serif;font-size: 13px;font-style: normal;font-weight: normal;margin:4% 2% 2%;width:100%; opacity: 1;padding: 3% 3% 2% 5%;$changed_landscape_answer_styles.$background_div_style\">
													<label style=\"margin-bottom: 0px;\"><input type=\"radio\" name=\"survey\" class=\"sf_answer_radio\" value =\"".$r->other_answer."\" style=\"margin-top:-1.5%;margin-left:5px;vertical-align: middle;$input_style\"><span style=\"padding-left: 5px;cursor:pointer;\">$r->other_answer</span></label>
												</div>";
					$this->question_display .= "<script>
												jQuery('.other_answer').find('label').one(\"click\",function(){
													jQuery('#question_".$this->survey_key."').css({
														'height' : '".$funnel_height."',
														'transition' : 'opacity 1s ease-in-out'
													});
													jQuery('#sfp_minimize').css({
														'bottom' : '".$funnel_height."px'
													});

													jQuery('.other_answer').append('<textarea id=\"new_answer\" style=\"margin-top: 10px; width: 100%;\" rows=\"2\" cols=\"50\"></textarea>');
													checkSFCheckmark(jQuery(this));
													jQuery('.other_answer').append('<input type=\"button\" id=\"other_option_submit\" style=\"background-image: none;margin:5px;\" value=\"Send\" />');
													jQuery('#other_option_submit').click(function(){
														if( jQuery('#new_answer').val().length > 0){
															var new_answer = jQuery('#new_answer').val();
															var extra_answer = 'true';
															submitSFAnswer('$this->survey_key', '" . SF_PLUGIN_URL . "', $r->survey_id, $r->survey_question_id, $r->priority, new_answer, extra_answer, '$this->question_background_color', $answer_index, '$this->survey_theme');
														}
													});
												});</script>";
				}
				/*
				*	Added By Kaustubh - END
				*/
				$this->question_display .="<script>
										   	var sfUseCheckmark=true;
										   	if(sfUseCheckmark) jQuery('.sfCheckmark').css('background','url(\"".WP_PLUGIN_URL_SLLSAFE."/public/images/checkmark.png\") repeat scroll 0px 0 rgba(0, 0, 0, 0)');
										    function checkSFCheckmark(obj){
										   			if(sfUseCheckmark) jQuery(obj).find('img').css('background','url(\"".WP_PLUGIN_URL_SLLSAFE."/public/images/checkmark.png\") repeat scroll -20px 0 rgba(0, 0, 0, 0)');
											}
										   </script>";
			}

			/*
			*	New condition to allow text answers for each question
			*/

			if($r->text_answer_allowed == 'yes'){
				$bg_color = $wpdb->get_results('SELECT background_color FROM '.$wpdb->prefix.'sf_surveys WHERE survey_id = '.$this->survey_id );
				$answers = explode("|||", $r->answers);
				$tmpAnswer = "";
				foreach ($answers as $answer_index => $tmpAnswer) {
					$jsAnswer = str_ireplace("'", "\'", $tmpAnswer);
					$extra_answer = '';

					$hover_color = '#373536';
					$text_color = $bg_color[0]->background_color;
					$text_color_mouseout = '#373536';
					$color_arr = array('#65573E','#B885D4','#B26F70','#EAA8CB','#373536','#749FB4','#7EB26E');
					if(in_array($bg_color[0]->background_color, $color_arr) ){
						$hover_color = '#FFF';
						$text_color = $bg_color[0]->background_color;
						$text_color_mouseout = '#FFF';
					}
					if($bg_color[0]->background_color=='#373536'){
						$text_color = '#797979';
						$text_color_mouseout = '#FFF';
						$this->question_background_color = '#474646';
					}
					$this->question_display .= "<div class=\"sfQuestion\" id=\"sfp_text_answer\" style=\"color:".$text_color_mouseout.";background-color:$this->question_background_color;border-radius: 20px;font-family: Lato,sans-serif;font-size: 12px;font-style: normal;font-weight: normal;opacity: 1;padding: 12px;margin: 2%;cursor:pointer;\" onclick=\"text_answer_input(jQuery(this),'$answer_index'); checkSFCheckmark(jQuery(this));\" onmouseout=\"jQuery(this).css({'background':'".$this->question_background_color."','color':'".$text_color_mouseout."'});\" onmouseover=\"jQuery(this).css({'background':'".$hover_color."','color':'".$text_color."'});\">
												<textarea  id=\"text_answer\" style=\"width:100% !important;\" ></textarea>
												</div>";
				  }


					/*onclick=\"checkSFCheckmark(jQuery(this));submitSFAnswer('$this->survey_key', '" . SF_PLUGIN_URL . "', $r->survey_id, $r->survey_question_id, $r->priority, '$jsAnswer', '$extra_answer', '$this->question_background_color', $answer_index, '$this->survey_theme');\"*/
					$this->question_display .= "<script>
												function text_answer_input(obj,answer_index){

													jQuery('#question_".$this->survey_key."').css({
														'height' : '".$funnel_height."'
													});

													jQuery('#sfp_minimize').css({
														'bottom' : '".$funnel_height."px'
													});

													jQuery('.sfQuestion').removeClass('active');
													jQuery('.sfQuestion').find('#text_answer').hide();
													jQuery('.other_answer').find('#other_option_submit').hide();
													jQuery('.other_answer').find('#new_answer').hide();
													jQuery('.sfCheckmark').css('background-position-x','0px');
													jQuery(obj).addClass('active');
													jQuery('.active').find('#text_answer').show();
													jQuery('.text_answer_submit').show();
													jQuery('.text_answer_submit').css('cursor','pointer');

												jQuery('.text_answer_submit').click(function(){

													if( jQuery('.active').find('#text_answer').val() ){
														var text_answer = jQuery('.active').find('#text_answer').val();
														var jsAnswer = jQuery('.active').find('span').html();
														submitSFAnswer('$this->survey_key', '" . WP_PLUGIN_URL_SLLSAFE . "', $r->survey_id, $r->survey_question_id, $r->priority, jsAnswer, text_answer, '$this->question_background_color', answer_index, '$this->survey_theme');
													}
												});


									}

											</script>";
				/*	On FrontEnd Survey form added New Field
				*	to Extra option Answer. Get its value and pass directly
				*	to javascript variable 'new_answer'
				*	Added By Kaustubh - START
				*/
				if ($r->other_answer) {
					$this->question_display .= "<div class=\"other_answer\" style=\ cursor:pointer;border-radius: 20px;font-family: Lato,sans-serif;font-size: 12px;font-style: normal;font-weight: normal;margin-bottom: 5px;margin-top: 5px;    opacity: 1;padding: 10px;\" onclick=\"other_answer_input(jQuery(this)); checkSFCheckmark(jQuery(this));\">
													<label style=\"display: block;\"><input type=\"radio\" name=\"survey\" value = \"".$r->other_answer."\" class=\"sf_answer_radio\" style=\"margin-top:-1.5%;margin-left:5px;vertical-align: middle;\"><span style=\"cursor:pointer; padding-left: 5px; word-break: break-all;\">$r->other_answer</span></label>
													<textarea  id=\"new_answer\" style=\"display: none; margin-top: 10px; width: 100%;\" rows=\"2\" cols=\"50\" ></textarea>
													<div class=\"sfp_response_send_btn\" style=\"text-align: right;\"><input type=\"button\" id=\"other_option_submit\" style=\"color:".$text_color_mouseout.";cursor: pointer; display: none;  background-image: none; background-color:".$this->question_background_color."\" value=\"Send\" /></div>
												</div>";

					$this->question_display .= "<script>


												function other_answer_input(obj){

													jQuery('#sfp_minimize').css({
														'bottom' : '".$funnel_height."px'
													});

													jQuery('#question_".$this->survey_key."').css({
														'height' : '".$funnel_height."',
														'transition' : 'opacity 1s ease-in-out'
													});


													jQuery('.sfQuestion').find('#text_answer').hide();
													jQuery('.text_answer_submit').hide();
													jQuery('.sfQuestion').find('.sfCheckmark').css('background-position-x','0px');
													jQuery('.other_answer').removeClass('active');

													jQuery(obj).addClass('active');
													jQuery('.active').find('#new_answer').show();
													jQuery('.other_answer').find('#other_option_submit').show();

												}
												jQuery('#other_option_submit').click(function(){
														if( jQuery('#new_answer').val().length > 0){
																var new_answer = jQuery('#new_answer').val();
																var extra_answer = 'true';
																submitSFAnswer('$this->survey_key', '" . WP_PLUGIN_URL_SLLSAFE . "', $r->survey_id, $r->survey_question_id, $r->priority, new_answer, extra_answer, '$this->question_background_color', $answer_index, '$this->survey_theme');
															}
												});
												</script>";
				}
				/*
				*	Added By Kaustubh - END
				*/
				$this->question_display .= "<div class=\"sfp_response_send_btn\" style=\"text-align: right;\" ><input type=\"button\" class=\"text_answer_submit\" style=\"color:".$text_color_mouseout.";cursor: pointer; margin: 5px;background-image: none; background-color:transparent\" value=\"Send\" onmouseout=\"jQuery(this).css('background', 'transparent');\" onmouseover=\"jQuery(this).css('background','#EFEFEF');\"/></div>";
				$this->question_display .="<script>
										   	var sfUseCheckmark=true;
										   	if(sfUseCheckmark) jQuery('.sfCheckmark').css('background','url(\"".WP_PLUGIN_URL_SLLSAFE."/public/images/checkmark.png\") repeat scroll 0px 0 rgba(0, 0, 0, 0)');
										    function checkSFCheckmark(obj){
										   			if(sfUseCheckmark)
										   				{
										   					jQuery(obj).find('img').css('background','url(\"".WP_PLUGIN_URL_SLLSAFE."/public/images/checkmark.png\") repeat scroll -20px 0 rgba(0, 0, 0, 0)');
										   				}
											}
										   </script>";
			}


			/* Start Code added by nishtha for progress bar */


			$enable_progress_bar=$wpdb->get_row("SELECT enable_progress_bar from {$wpdb->prefix}sf_surveys where survey_id=".$this->survey_id);
			$enable_progress= $enable_progress_bar->enable_progress_bar;

			$progress_colour=$wpdb->get_row("SELECT progress_bar_color from {$wpdb->prefix}sf_surveys where survey_id=".$this->survey_id);
			$progress_color= $progress_colour->progress_bar_color;

			$progress_bg_colour=$wpdb->get_row("SELECT progress_bar_background_color from {$wpdb->prefix}sf_surveys where survey_id=".$this->survey_id);
			$progress_bg_color= $progress_bg_colour->progress_bar_background_color;

			if ($enable_progress=='1'){
				$height = "20px";
				$a_width = "100%";
				$percentage= $percentage| 0;

				/*
				Start - Content added by Ruqeeba 11 July 2016
				*/

			//	$this->question_display .="<div class=\"meter\"  style='width:$a_width; height:$height; background: $progress_bg_color; display:inline-block;'><div class=\"nometer\"  style='width:$q; height:$height;  background-color: $progress_color;  margin-bottom: 5px;'>$percentage%</div></div></br>";

			$this->question_display .= "<div align='left' style=\"background-color:".$progress_bg_color." ;border: 1px solid ".$progress_bg_color.";border-radius: 25px;box-shadow: 0 -1px 1px rgba(255, 255, 255, 0.3) inset; height: 10px; margin: 15px 12px;    position: relative;\">
            <span style=\"width: $percentage%; font-size:14px; text-align:center; color: black; display: block; height: 100%; border-top-right-radius: 20px; border-bottom-right-radius: 20px; border-top-left-radius: 20px; border-bottom-left-radius: 20px; background-color: white; box-shadow: inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4); position: relative; overflow: hidden;\"></span>
          </div>";

					/*
					End - Content added by Ruqeeba 11 July 2016
					*/

			}

			/* End Code added by nishtha for progress bar */

			/*
			*	End of text_answer_allowed condition
			*/

			$this->question_display .= "</div>";

		} elseif ($r->question_type == 2) {

                    $this->question_display='';
										if ($sf_question_header!='') {
	                            $this->question_display.="<div style=\"width=100%;border-radius: 5px;font-family: helvetica,sans-serif;font-size: 12px;font-style: normal;font-weight: normal;padding: 8px;    text-align: center;\">".$sf_question_header."</div>";
															$this->question_display.="<hr style=\"width=100%;border: 0 none;box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 1) inset;height: 8px;\">";
	                    }
											$message_modified = str_replace("style","style='color:white'",$r->answers);
											$content_div ="<div style=\"width=100%;font-family: helvetica,sans-serif;font-size: 12px;font-style: normal;font-weight: normal;padding: 8px;text-align: center; color:white;\">".stripslashes(htmlspecialchars_decode($message_modified, ENT_QUOTES))."</div>";

	                    $this->question_display .= htmlspecialchars_decode(do_shortcode($content_div), ENT_QUOTES);
		}

	// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

		elseif($r->question_type == 3){
			$bg_color = $wpdb->get_results('SELECT background_color FROM '.$wpdb->prefix.'sf_surveys WHERE survey_id = '.$this->survey_id );
			$hover_color = '#373536';
			$text_color = $bg_color[0]->background_color;
			$text_color_mouseout = '#373536';
			$color_arr = array('#65573E','#B885D4','#B26F70','#EAA8CB','#373536','#749FB4','#7EB26E');
			if(in_array($bg_color[0]->background_color, $color_arr) ){
				$hover_color = '#FFF';
				$text_color = $bg_color[0]->background_color;
				$text_color_mouseout = '#FFF';
			}
			if($bg_color[0]->background_color=='#373536'){
				$text_color = '#797979';
				$text_color_mouseout = '#FFF';
				$this->question_background_color = '#474646';
			}
			$this->question_display .= "<div class=\"sfQuestion\" style=\"color:".$text_color_mouseout.";padding: 5px;\"   id=\"usercontent\">
			<form action='' method='post' id=\"frm1\">
			<h2 style=\"font-size: inherit;font-weight: inherit; text-align:center;font-family:inherit;margin: 3px;\"> User Information </h2>

			<table  id=\"defaultcontent\" style=\"border: none;\">
			<tr>
			<td style=\"vertical-align: middle;width:30%;font-size:14px;border:none;font-family:Lato, sans-serif;color:".$text_color_mouseout.";\"><span>Name</span>
			</td><td style=\"vertical-align: middle;width:70%;border:none;\"><input style=\"padding: 5px;font-size:13px;font-family:Lato, sans-serif;\" type=\"text\" name=\"uname\" id=\"uname\"></td>
			</tr>
			<tr>
			<td style=\"vertical-align: middle;font-size:14px;font-family:Lato,sans-serif;border:none;width:30%;color:".$text_color_mouseout.";\"><span>Email Id</span></td>
			<td style=\"vertical-align: middle;border:none;width:70%;\"><input style=\"padding: 5px;font-size:13px;font-family:Lato, sans-serif;\" type=\"email\" name=\"email\" id=\"email\"></td>
			</tr>

			</table></form>";
			$this->question_display .= "<div id=\"sfform_buttons;\" style=\"text-align:center;\"><button style=\"padding:4px 10px;border:1px solid #000;border-radius:12px;font-size:14px;background-color:inherit;color:$text_color_mouseout;\" onclick=\"mysfUserinfo('$this->survey_key', '" . SF_PLUGIN_URL . "', $r->survey_id, $r->survey_question_id, $r->priority,'$this->question_background_color')\">Submit</button>  <button style=\"padding:4px 10px;border: 1px solid #000;border-radius:12px;font-size:14px;background-color:inherit;color:$text_color_mouseout;\" onclick=\"cancelUserInfo('$this->survey_key', '" . SF_PLUGIN_URL . "', $r->survey_id, $r->survey_question_id, $r->priority,'$this->question_background_color')\">Cancel</button></div> </div>";

			do_action( 'sf_after_user_info_fields' );
		}

    //END
		if ($r->question_type != 1) {
			return false;

		} else {
			return true;
		}
	}


	/**
	 * Set the completion cookie (if necessary)
	 *
	 */
	private function setSFCookie() {
		global $wpdb;
		$r = $wpdb->get_row("SELECT survey_key, use_cookie, cookie_days FROM {$wpdb->prefix}sf_surveys WHERE survey_id = '$this->survey_id'");

		if ($r->use_cookie) {

		}

		setcookie($r->survey_key, 1, time() + 60 * 60 * 24 * $r->cookie_days, COOKIEPATH, COOKIE_DOMAIN, false);
	}


	/**
	 * Add a completion to the stats table
	 *
	 */
	private function addCompletion() {
                /* Begin - Arun 29-April-2013 */
		if($email = get_option('sf_email_id')){
		mail($email, 'Survey Answered','Your survey has been answered by a user at '.date('Y-m-d H:i:s').' on '.get_site_url(),'Notification@surveyfunnel.com');
	    }
/* Ended - Arun */
		global $wpdb;
		$ActiveStatus = new ActiveStatus();

		$date = date("Y-m-d H:i:s");

		$r = $wpdb->get_row("SELECT imprints, completions FROM {$wpdb->prefix}sf_survey_stats WHERE survey_id = '$this->survey_id'");

		if (!$r->imprints) {
			$wpdb->insert($wpdb->prefix . 'sf_survey_stats',
							array(
									'survey_id' => $this->survey_id,
									'completions' => '1',
									'active_status_id' => $ActiveStatus->active_records,
									'date_created' => $date));
		} else {
			$tmpNum = $r->completions + 1;
			$wpdb->update($wpdb->prefix . 'sf_survey_stats',
							array(
									'completions' => $tmpNum,
									'date_modified' => $date
							),
							array('survey_id' => $this->survey_id));
		}
	}


} // End Survey Activity Class
?>
