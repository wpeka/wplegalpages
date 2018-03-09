<?php
class SurveyExport {

	var $survey_id;

	var $action;
	var $updateMsg;
	var $error;
	var $dataErrors = array();


	/**
	 * Survey Export Constructor Function
	 *
	 * @return ReportRun
	 */
  	function __construct() {
  		$this->client_id = isset($_SESSION['client_id'])?$_SESSION['client_id']:'';
	}


	/**
	 * Enter description here...
	 *
	 */
	function initSurveyExport() {
		$this->action = $_REQUEST['action'];

		switch ($this->action) {
			case 'EXPORT_TO_XLS':
					$this->survey_id = $_REQUEST['survey_id'];

					$this->showHeader();

					$this->showQuestionResults();

					exit;
					break;
	// Begin Added by Arvind on 14-AUG-2013
			case 'EXPORTXML' :
				$this->survey_id = $_REQUEST['survey_id'];

				$this->showHeader();

				$this->showQuestionResults1();

				exit;
				break;
	// End by Arvind
			default:
					break;
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	private function showHeader() {
		global $wpdb;
		$r = $wpdb->get_row("SELECT survey_id, survey_name, date_created FROM {$wpdb->prefix}sf_surveys WHERE survey_id = '$this->survey_id'");

		header('Content-Type: application/vnd.ms-excel;');
		header('Content-type: application/x-msexcel');
		header('Content-Disposition: attachment; filename=export_' . trim($r->survey_name) . '.xls');

		if ($r->survey_id) {
			//echo $r->survey_name . " (" . date("m/d/Y", strtotime($r->date_created)) . " - " . date("m/d/Y") . ")\n\n\n";
			echo "Survey Name: ".$r->survey_name . "\n\n";

		} else {
			exit;
		}
	}


	/**
	 * Enter description here...
	 *
	 */
	private function showQuestionResults() {
		global $wpdb;

		$select = "SELECT {$wpdb->prefix}sf_survey_questions.survey_question_id, {$wpdb->prefix}sf_survey_questions.question, {$wpdb->prefix}sf_survey_questions.answers, {$wpdb->prefix}sf_survey_questions.other_answer FROM {$wpdb->prefix}sf_survey_questions
						WHERE {$wpdb->prefix}sf_survey_questions.survey_id = '$this->survey_id'
						AND {$wpdb->prefix}sf_survey_questions.active_status_id = 1
						AND {$wpdb->prefix}sf_survey_questions.question_type = 1
						ORDER BY {$wpdb->prefix}sf_survey_questions.priority";

		//echo "$select\n<br>";
		$tr = $wpdb->get_results($select);

		$questions = array();
		$answers = array();

		foreach ($tr as $r) {

			$r->answers .= "|||".$r->other_answer;

			foreach (explode("|||", $r->answers) as $answer_name) {
				//$answer_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = '$r->survey_question_id' AND answer = '$answer_name'",''));
				$answer_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = %s AND answer = %s",$r->survey_question_id,$answer_name));
				$questions[$r->survey_question_id] = $r->question;
				$answers[$r->survey_question_id][$answer_name] = $answer_count;
			}
			$other_answer_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = %s AND extra_ans = 'true'",$r->survey_question_id));
			$questions[$r->survey_question_id] = $r->question;
			$answers[$r->survey_question_id][$answer_name] = $other_answer_count;
		}

			echo "Questions \tAnswers \tResponses \n\n";

		foreach ($questions as $question_id => $question_name) {
			$question_name = trim(strip_tags(html_entity_decode($question_name)));
			echo "$question_name\n";

			$row_count = 0;

			foreach ($answers[$question_id] as $answer_name => $answer_count) {
				echo "\t$answer_name\t$answer_count\n";
			}
		}
	}

	// Begin Added by Arvind on 14-AUG-2013 for Export UserInfo

	private function showQuestionResults1() {

		global $wpdb;
		$uid = "SELECT DISTINCT {$wpdb->prefix}sf_survey_results.user_id FROM {$wpdb->prefix}sf_survey_results
		WHERE {$wpdb->prefix}sf_survey_results.survey_id = '$this->survey_id' ";

		$tr = $wpdb->get_results($uid);
		echo "User_id \tUser_Name \tEmail_id \tQuestion \tAnswer \tDescriptive_Answer \tDate \tTime \n\n";
		$count=0;
		foreach ($tr as $userid) {

		//echo "\t$userid->user_id\t\t";
			$count=0;
		$uinfo = "SELECT {$wpdb->prefix}sf_survey_user_information.user_name,{$wpdb->prefix}sf_survey_user_information.email_id FROM {$wpdb->prefix}sf_survey_user_information
		WHERE {$wpdb->prefix}sf_survey_user_information.user_id = '$userid->user_id' ";
		$uinfo1 = $wpdb->get_results($uinfo);

		foreach($uinfo1 as $userinfo){
		echo $userid->user_id." \t".$userinfo->user_name." \t".$userinfo->email_id." \t";

		$select1 = "SELECT {$wpdb->prefix}sf_survey_results.answer,{$wpdb->prefix}sf_survey_results.extra_ans,{$wpdb->prefix}sf_survey_results.survey_question_id,{$wpdb->prefix}sf_survey_results.date_created FROM {$wpdb->prefix}sf_survey_results WHERE {$wpdb->prefix}sf_survey_results.user_id = '$userid->user_id'";

		$QA = $wpdb->get_results($select1);

		foreach($QA as $qa){
		$count=$count+1;
		$q = $wpdb->get_row("SELECT question,date_created FROM {$wpdb->prefix}sf_survey_questions WHERE survey_question_id = '$qa->survey_question_id'");
		if($qa->extra_ans=='true'){$qa->extra_ans='';}
		if($count==1){
		$datetime=array();
		$datetime=explode(" ",$qa->date_created);

		/*
		*	Replace newline charachter with Space for descriptive answers
		*/
		$qa->extra_ans =  preg_replace("/\n/"," ", $qa->extra_ans);
		$que_content = trim(strip_tags(html_entity_decode($q->question)));
		echo "\t \t \t".$datetime[0]. "\t".$datetime[1]."\n";
		}
		else
			$que_content = trim(strip_tags(html_entity_decode($q->question)));
			$multi_ans = '';
			if (strpos($qa->answer, '|||') !== false) {
					 $multiple_answers = explode("|||", $qa->answer);
					 for($i=0; $i<count($multiple_answers)-1; $i++)
					 {
					 $j = $i + 1;
					 $multi_ans .= $j.". ".$multiple_answers[$i]."\n";
					 }
			 }
			 else
				 $multi_ans .= $qa->answer;
			echo "\t \t \t \"$que_content\" \t\"$multi_ans\" \t\"$qa->extra_ans\" \n";
		}
		echo "\n";

		}

		}




	}
	//End by Arvind

} // End Survey Export Class
?>
