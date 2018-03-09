<?php

ob_start();
include(dirname(__FILE__) . "/includes/classes/all_classes.php");

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

function magicQuotes_awStripslashes(&$value, $key) {$value = stripslashes($value);}
$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
array_walk_recursive($gpc, 'magicQuotes_awStripslashes');

$SurveyActivity = new SurveyActivity();
$SurveyActivity->initSurveyActivity();

if (current_user_can('manage_options')) {
	$SurveyQuestionManage = new SurveyQuestionManage();
	$SurveyQuestionManage->initSurveyQuestionManage();

	$SurveyManage = new SurveyManage();
	$SurveyManage->initSurveyManage();

	$SurveyExport = new SurveyExport();
	$SurveyExport->initSurveyExport();
}
?>
