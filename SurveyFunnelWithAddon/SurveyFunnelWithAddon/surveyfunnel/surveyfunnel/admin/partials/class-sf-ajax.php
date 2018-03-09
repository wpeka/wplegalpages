<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SF_Ajax {


	public function __construct() {
		$ajaxEvents = array('get_individual_results' => true);

		foreach ($ajaxEvents as $event => $nopriv){
			add_action('wp_ajax_surveyfunnel_'.$event , array($this,$event));
			if($nopriv) {
				add_action('wp_ajax_nopriv_surveyfunnel_'.$event , array($this,$event));
			}
		}
	}


	public function get_individual_results(){
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/includes/classes/sf_individual_result_class.php');
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		//include 'all_classes.php';
		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/includes/classes/all_classes.php');

		$SF_indv = new sf_indv_result();
		$SF_indv->sf_set_page_no($_POST['page_no']);
		$SF_indv->sf_set_survey_id($_POST['survey_id']);
		$SF_indv->sf_get_indv_results();
		$indv_results = $SF_indv->indv_results;
		//die();
		$userCounts = $SF_indv->sf_get_usercounts();

		include WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/results_individual.php';
		die();
	}
}

new SF_Ajax();
