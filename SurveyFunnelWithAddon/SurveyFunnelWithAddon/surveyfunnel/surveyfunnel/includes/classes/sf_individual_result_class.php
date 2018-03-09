<?php
if(!defined('ABSPATH')) {exit;}
if(!class_exists('sf_indv_result')) :

class sf_indv_result {
	public $page_no = 1;
	public $survey_id = null;
	public $wpdb;
	public $indv_results;
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->SurveyManage = new SurveyManage();
		$this->SurveyManage->loadSurvey($_REQUEST['survey_id']);
		$this->survey_id = $this->SurveyManage->survey_id;
	}

	public function sf_set_page_no($page_no){
		$this->page_no = $page_no;
	}
	public function sf_set_survey_id($survey_id){
		$this->survey_id = $survey_id;
	}

	public function sf_get_survey_users() {
		$lowidx = ($this->page_no -1)*10;
		$select = "SELECT {$this->wpdb->prefix}sf_survey_results.user_id
		FROM {$this->wpdb->prefix}sf_survey_results
		INNER JOIN {$this->wpdb->prefix}sf_survey_questions
		ON  {$this->wpdb->prefix}sf_survey_results.survey_question_id = {$this->wpdb->prefix}sf_survey_questions.survey_question_id
		WHERE {$this->wpdb->prefix}sf_survey_results.survey_id='$this->survey_id'
		AND {$this->wpdb->prefix}sf_survey_questions.active_status_id = '1'
		GROUP BY {$this->wpdb->prefix}sf_survey_results.user_id LIMIT $lowidx,10";


		return $this->wpdb->get_results($select);
	}

	public	function sf_get_usercounts() {
		$select = "SELECT {$this->wpdb->prefix}sf_survey_results.user_id FROM {$this->wpdb->prefix}sf_survey_results , {$this->wpdb->prefix}sf_survey_questions
			WHERE {$this->wpdb->prefix}sf_survey_results.survey_id = '$this->survey_id'
			AND {$this->wpdb->prefix}sf_survey_questions.survey_question_id =  {$this->wpdb->prefix}sf_survey_results.survey_question_id
			AND {$this->wpdb->prefix}sf_survey_questions.active_status_id = '1'";

			$user_ids = $this->wpdb->get_results($select);
			if($user_ids){
				foreach( $user_ids as $user_id )
					$users[] = $user_id->user_id;

				$counts = count((array_count_values($users)));
				return $counts;
			}else{
				return 0;
			}
	}

	public function sf_get_indv_results() {
		$users = $this->sf_get_survey_users();

		foreach ($users as $user) {
			$select = "SELECT {$this->wpdb->prefix}sf_survey_results.answer ,
			{$this->wpdb->prefix}sf_survey_results.extra_ans,
			{$this->wpdb->prefix}sf_survey_user_information.email_id,
		  {$this->wpdb->prefix}sf_survey_user_information.user_name,
			{$this->wpdb->prefix}sf_survey_results.user_id,
			{$this->wpdb->prefix}sf_survey_results.date_created,
			{$this->wpdb->prefix}sf_survey_results.date_modified,
			{$this->wpdb->prefix}sf_survey_results.survey_question_id,
			{$this->wpdb->prefix}sf_survey_questions.question
			FROM {$this->wpdb->prefix}sf_survey_results
			INNER JOIN {$this->wpdb->prefix}sf_survey_questions
			ON  {$this->wpdb->prefix}sf_survey_results.survey_question_id = {$this->wpdb->prefix}sf_survey_questions.survey_question_id
			INNER JOIN {$this->wpdb->prefix}sf_survey_user_information
			ON  {$this->wpdb->prefix}sf_survey_results.user_id = {$this->wpdb->prefix}sf_survey_user_information.user_id
			WHERE {$this->wpdb->prefix}sf_survey_results.survey_id='$this->survey_id'
			AND {$this->wpdb->prefix}sf_survey_questions.active_status_id = '1'
			AND {$this->wpdb->prefix}sf_survey_results.user_id = '$user->user_id'
			GROUP BY {$this->wpdb->prefix}sf_survey_results.survey_question_id
			";

			$results = $this->wpdb->get_results($select);
			$i = 0;

			foreach ($results as $result){
				$this->indv_results[$user->user_id][0]['email_id'] = $result->email_id;
				$this->indv_results[$user->user_id][0]['user_name'] = $result->user_name;
			$this->indv_results[$user->user_id][$i]['question'] = $result->question;
			$this->indv_results[$user->user_id][$i]['answer'] = $result->answer;
			$this->indv_results[$user->user_id][$i]['extra_ans'] = $result->extra_ans;
			$this->indv_results[$user->user_id][$i]['date_created'] = $result->date_created;
			$this->indv_results[$user->user_id][$i]['date_modified'] = $result->date_modified;
						$i++;
			}


		}
	}

}

endif;

new sf_indv_result();
?>
