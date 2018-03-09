<?php
class SurveyQuestionManage {

	var $question;
	var $question_id;
	var $question_type;
	var $WP_PLUGIN_URL;

	var $action;
	var $updateMsg;
	var $error;
	var $dataErrors = array();

	var $script_reDirect;
	var $script_updateBtn;
	var $script_questionAdd;
	var $default_question_header;
	var $question_header;
	var $updateMsg2='';
	var $text_answers='';
	/**
	 * Survey Question Manage Constructor Function
	 *
	 * @return SurveyQuestionManage
	 */
	function __construct() {
		$this->action = "";
		$this->updateMsg = "";
		$this->error = false;
	}


	/**
	 * Enter description here...
	 *
	 */
	function initSurveyQuestionManage() {
		$this->action = $_REQUEST['action'];

		switch ($this->action) {
			case 'ADD_QUESTION':
					$this->setLocalVars();
					$this->validateData();

					if (!$this->error) {
						$this->addQuestion();
					}

					// Set the update message error response
					if ($this->error) {
						if (isset($this->updateMsg2)) {
							if ($this->updateMsg2 == '') {

								$this->script_reDirect = "\$dialogContent.find('#updateMsg2').html('<span class=\"submissionErrors\">You have errors with your submission</span><br><br>');";
							}
						}

						$this->script_updateBtn = "jQuery('#updateBtn').attr('disabled', false);";
					}

					$this->displayJSON();
					break;

			case 'ADD_DEFAULT_QUESTION_HEADER':
					$this->setLocalVars();
					$this->validateDataForDefaultHeader();

					if (!$this->error) {
						$this->addDefaultQuestionHeader();
					}

					// Set the update message error response
					if ($this->error) {
					if ($this->updateMsg2 == '') {
							//$this->updateMsg2 = "<span class=\"submissionErrors\">You have errors with your submission</span><br><br>";
							$this->script_reDirect = "\$dialogContent.find('#updateMsg2').html('<span class=\"submissionErrors\">You have errors with your submission</span><br><br>');";
						}

						$this->script_updateBtn = "jQuery('#updateBtn').attr('disabled', false);";
					}

					$this->displayJSON();
					break;

			case 'UPDATE_QUESTION':
					$this->setLocalVars();
					$this->validateData();

					if (!$this->error) {
						$this->updateQuestion();
					}

					// Set the update message error response
					if ($this->error) {
						if ($this->updateMsg2 == '') {
							//$this->updateMsg2 = "<span class=\"submissionErrors\">You have errors with your submission</span><br><br>";
							$this->script_reDirect = "\$dialogContent.find('#updateMsg2').html('<span class=\"submissionErrors\">You have errors with your submission</span><br><br>');";
						}

						$this->script_updateBtn = "jQuery('#updateBtn').attr('disabled', false);";
					}

					$this->displayJSON();
					break;

			default:
					break;
		}
	}


	/**
	 * Set local vars based on a form submission
	 *
	 */
	private function setLocalVars() {
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

		$DataCleaner->validData($this->question, 'question_error');

		$this->error = $DataCleaner->error;
		$this->dataErrors = $DataCleaner->dataErrors;
	}

	/**
 	 * Error check submitted info
 	 *
 	 */
	private function validateDataForDefaultHeader() {
		$DataCleaner = new DataCleaner($this);

		$DataCleaner->validateDataForDefaultHeader($this->default_question_header, 'question_error');

		$this->error = $DataCleaner->error;
		$this->dataErrors = $DataCleaner->dataErrors;
	}

	/**
	 * Enter description here...
	 *
	 */
	private function addQuestion() {
		$tmpID = uniqid();
		$answers="";
		if ($this->question_type == 1) {
			$Tempanswers = preg_split("/(\r\n|\n|\r)/", $this->answers);
			$answers = implode("|||", $Tempanswers);
			/*	Added by Kaustubh	*/
			$other_answer = $this->other_answer_text;

			if($this->text_answers){
				$text_answers = "yes";
			}
			else{
				$text_answers = "no";
			}
		}


		$answers = htmlspecialchars($answers, ENT_QUOTES);


		$this->script_questionAdd = "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"questions[]\" id=\"key_$tmpID\" value=\"$tmpID\">');";
		$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"priority_$tmpID\" id=\"priority_$tmpID\" value=\"1\">\');";
		$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"question_type_$tmpID\" id=\"question_type_$tmpID\" value=\"$this->question_type\">\');";


		if ($this->question_type == 1) {
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"answers_$tmpID\" id=\"answers_$tmpID\" value=\"".addslashes($answers)."\" />');";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"other_answer_$tmpID\" id=\"other_answer_$tmpID\" value=\"".addslashes($other_answer)."\" />');";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"text_answers_$tmpID\" id=\"text_answers_$tmpID\" value=\"".addslashes($text_answers)."\" />');";
			$this->script_questionAdd .= "jQuery('#survey_frm').append('<textarea style=\"display: none;\" name=\"question_$tmpID\" id=\"question_$tmpID\"></textarea>');";
			$this->script_questionAdd .= "performSFQuestionAdd(\"$tmpID\", \"" . SF_PLUGIN_URL . "\");";

		} elseif ($this->question_type == 2) {
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"answers_$tmpID\" id=\"answers_$tmpID\" value=\"\">');";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"question_$tmpID\" id=\"question_$tmpID\" value=\"\">');";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").find(\"#question_$tmpID\").val(\$dialogContent.find(\"#question\").val());";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").find(\"#answers_$tmpID\").val(\$dialogContent.find(\"#question\").val());";
			$this->script_questionAdd .= "performSFContentAdd(\"$tmpID\", \"" . SF_PLUGIN_URL . "\");";
		}

// BEGIN Added By Arvind On 7-AUG-2013  For Add U useful, for instance, if you generate identifierserInformation

		elseif($this->question_type ==3){

			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"answers_$tmpID\" id=\"answers_$tmpID\" value=\"\">');";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").append('<input type=\"hidden\" name=\"question_$tmpID\" id=\"question_$tmpID\" value=\"\">');";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").find(\"#question_$tmpID\").val(\$dialogContent.find(\"#add\").val());";
			$this->script_questionAdd .= "performSFUserContentAdd(\"$tmpID\", \"" . SF_PLUGIN_URL . "\");";
		}
//END
		$this->script_questionAdd .= "\$dialogContent.dialog('destroy'); \$dialogContent.hide(); jQuery('#survey_funnel_question_flow').show(); updateSFColumns();";

	}

	/**
 	 * Enter description here...
 	 *
 	 */
	private function addDefaultQuestionHeader() {
		$defaultHeader = htmlspecialchars($this->default_question_header, ENT_QUOTES);
		$defaultHeader = str_replace("\n", "", $defaultHeader);
		$defaultHeader = str_replace("\r", "", $defaultHeader);

		$this->script_questionAdd .= "updateDefaultHeader('".$defaultHeader."')";

	}

	/**
	 * Enter description here...
	 *
	 */
	private function updateQuestion() {
		$tmpID = $this->question_id;
		$answers="";
		if ($this->question_type == 1) {
			$Tempanswers = preg_split("/(\r\n|\n|\r)/", $this->answers);
			$answers = implode("|||", $Tempanswers);
			$other_answer = $this->other_answer_text;
			if($this->text_answers){
				$text_answers = "yes";
			}
			else{
				$text_answers = "no";
			}
		}

		$answers = htmlspecialchars($answers, ENT_QUOTES);

		if ($this->question_type == 1) {
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").find(\"#answers_$tmpID\").val(\"$answers\");";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").find(\"#other_answer_$tmpID\").val(\"$other_answer\");";
			$this->script_questionAdd .= "jQuery(\"#survey_frm\").find(\"#text_answers_$tmpID\").val(\"$text_answers\");";
			$this->script_questionAdd .= "performSFQuestionUpdate(\"$tmpID\", \"" . $this->WP_PLUGIN_URL . "\");";

		} elseif ($this->question_type == 2) {
			$this->script_questionAdd .= 'jQuery(\'#li_' . $tmpID . ' .que_content\').html($dialogContent.find(\'#question\').val());';
			//$this->script_questionAdd .= 'jQuery(\'#li_' . $tmpID . ' .display\').html(jQuery(\'<div/>\').text($dialogContent.find(\'#question\').val()).html());';
			$this->script_questionAdd .= 'jQuery(\'#survey_frm\').find(\'#question_' . $tmpID . '\').val($dialogContent.find(\'#question\').val());';
			$this->script_questionAdd .= 'jQuery(\'#survey_frm\').find(\'#answers_' . $tmpID . '\').val($dialogContent.find(\'#question\').val());';
		}

// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

		elseif ($this->question_type==3){
			$this->script_questionAdd .= 'jQuery(\'#li_' . $tmpID . ' .display\').html($dialogContent.find(\'#question\').val());';
			$this->script_questionAdd .= 'jQuery(\'#survey_frm\').find(\'#question_' . $tmpID . '\').val($dialogContent.find(\'#add\').val());';

		}

//END

		$this->script_questionAdd .= '$dialogContent.dialog("destroy"); \$dialogContent.hide(); updateSFColumns();';
	}


	/**
	 * Display the JSON info
	 *
	 */
	private function displayJSON() {
		$JSON = new SF_JSON($this);
		$JSON->displayJSON();

		exit;
	}


} // End Survey Question Manage Class
?>
