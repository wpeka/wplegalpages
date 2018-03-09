<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SF_Frontend' ) ) :
class SF_Frontend {

	public function __construct(){

		add_filter('the_content', array($this,'survey_funnel_filter_content'));
	}

	function survey_funnel_filter_content($iContent) {

		global $post, $is_survey_page;
		global $wp_query;

		if(!$is_survey_page){
			//this page does not use Survey Funnel

			return $iContent;
		}

		//include_once(dirname(__FILE__)."/classes/all_classes.php");
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");



		$Survey = new Survey();
		if (is_category())
		{
			//$cat_ID = get_query_var('cat');
			$category = get_the_category($post->ID);
			$Survey->getSurveysforCategories($category[0]->term_id);
			remove_filter('the_content', 'survey_funnel_filter_content'); // Added in 3.0 to avoid multiple calls to triggerlightbox
		}
		else
		{
			$Survey->getSurveys($post->ID);
			remove_filter('the_content', 'survey_funnel_filter_content'); // Added in 3.0 to avoid multiple calls to triggerlightbox
		}

		return $iContent;
	}

}

endif;
if(!isset($_COOKIE['survey_completed'])){
new SF_Frontend();
}
