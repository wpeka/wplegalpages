<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link              http://www.surveyfunnel.com
 * @since      1.0.0
 *
 * @package           surveyfunnel
 * @subpackage Plugin_Name/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package           surveyfunnel
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */

 if ( ! class_exists( 'surveyFunnel' ) ) :

 final class surveyFunnel {

 protected static $_instance = null;

 	/**
 	 * Main surveyFunnel Instance
 	 *
 	 * Ensures only one instance of surveyFunnel is loaded or can be loaded.
 	 *
 	 * @since 2.1
 	 * @static
 	 * @see WC()
 	 * @return surveyFunnel - Main instance
 	 */
 	public static function instance() {
 		if ( is_null( self::$_instance ) ) {
 			self::$_instance = new self();
 		}
 		return self::$_instance;
 	}


 	public function __construct() {

 		$this->define_constants();
 		$this->includes();
 		add_action('init', array($this,'survey_funnel_header'));
 		add_action('shutdown', array($this,'survey_funnel_shutdown'));
 		add_action('wp_enqueue_scripts', array($this,'my_sf_scripts_method'));
 		add_action('wp_ajax_sf_update', array($this,'sf_update_callback'));
 		add_action('wp_ajax_sf_delete', array($this,'sf_delete_callback'));//2.0.9
    add_action('wp_ajax_sf_activate', array($this,'sf_activate_callback'));//2.0.9
 		add_action('wp_ajax_sf_clone', array($this,'sf_clone_callback'));//2.0.9
 		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/class-survey-funnel-short.php');

 	}

	private function define_constants() {

 		$plugindir=explode('/', dirname(__FILE__));
 		$plugindir=$plugindir[count($plugindir)-2];
 		if(!defined('SF_PLUGIN_URL')) define('SF_PLUGIN_URL', WP_PLUGIN_URL.'/'.$plugindir);
 		if(!defined('SF_PLUGIN_DIR')) define('SF_PLUGIN_DIR', $plugindir);

 		if ( ! defined( 'WP_CONTENT_URL' ) )
 			define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
 		if ( ! defined( 'WP_CONTENT_DIR' ) )
 			define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
 		if ( ! defined( 'WP_PLUGIN_URL' ) )
 			define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
 		if ( ! defined( 'WP_PLUGIN_DIR' ) )
 			define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
    if ( ! defined( 'WP_PLUGIN_URL_SLLSAFE' ) )
 		   define( 'WP_PLUGIN_URL_SLLSAFE', str_replace(array('http://', 'https://'),'//',SF_PLUGIN_URL) );

 	}

 	public function includes() {

 		if(is_admin()){
 		  //include_once('class-admin-sf.php');
      include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/class-survey-funnel-admin.php');
 			include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/partials/class-sf-ajax.php');
 		}
      //include_once('class-frontend-sf.php');
 			include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/public/class-survey-funnel-public.php');
 	}


 	public function survey_funnel_header(){
 		//include(dirname(__FILE__)."/classes/all_classes.php");
    include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");
 		$DBTables = new DBTables();
 		$DBTables->initDBTables();

 		//Jack added
 		ob_start();
 	}

 	function survey_funnel_shutdown() {
 		$_SESSION['sf_current_page'] = '';
 	}


 	function my_sf_scripts_method(){
 		//See if survey funnel should be shown on public facing pages
 		//Purpose: reduce loading of js and css

 		global $wpdb, $post, $is_survey_page;

  	//include_once(dirname(__FILE__) . "/classes/all_classes.php");
    include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");

 		$Survey = new Survey();
 		if (!isset($post->ID))
    $post->ID=0; //added for fix to survey showing on 404 page

 		if (is_category())
 		{
 			$category = get_the_category($post->ID);
 			if($Survey->isSurveyCategory($category[0]->term_id)){
 		$is_survey_page = true;
 		//enque the scripts and styles

 	wp_enqueue_script('survey_funnel_ajax', SF_PLUGIN_URL.'/includes/assets/js/ajax.js', array('jquery') ,'1.0', false);
 	wp_enqueue_script('survey_funnel', SF_PLUGIN_URL.'/includes/assets/js/survey_funnel.js', array('jquery'),'1.0', false);
 	wp_enqueue_script('survey_funnel_fancybox', SF_PLUGIN_URL.'/includes/assets/jquery/fancyBox-2.1.5/source/jquery.fancybox.pack.js', array('jquery'),'1.0', false);


 	wp_enqueue_style('survey_funnel_styles', SF_PLUGIN_URL.'/includes/assets/css/styles.css');
 	wp_enqueue_style('survey_funnel_client_styles', SF_PLUGIN_URL.'/includes/assets/css/survey_funnel.css');
 	wp_enqueue_style('survey_funnel_client_styles_fancybox', SF_PLUGIN_URL.'/includes/assets/jquery/fancyBox-2.1.5/source/jquery.fancybox.css' );
 			}
 			else
 				$is_survey_page = false;
 		}
 		else if ($Survey->isSurveyPage($post->ID)){

 			$is_survey_page = true;

 			//enque the scripts and styles
 			wp_enqueue_script('survey_funnel_ajax', SF_PLUGIN_URL.'/includes/assets/js/ajax.js', array('jquery') ,'1.0', false);
 			wp_enqueue_script('survey_funnel', SF_PLUGIN_URL.'/includes/assets/js/survey_funnel.js', array('jquery'),'1.0', false);
 			wp_enqueue_script('survey_funnel_fancybox', SF_PLUGIN_URL.'/includes/assets/jquery/fancyBox-2.1.5/source/jquery.fancybox.pack.js', array('jquery'),'1.0', false);

 			wp_enqueue_style('survey_funnel_styles', SF_PLUGIN_URL.'/includes/assets/css/styles.css' );
 			wp_enqueue_style('survey_funnel_client_styles', SF_PLUGIN_URL.'/includes/assets/css/survey_funnel.css' );
 			wp_enqueue_style('survey_funnel_client_styles_fancybox', SF_PLUGIN_URL.'/includes/assets/jquery/fancyBox-2.1.5/source/jquery.fancybox.css' );

 		}
 		else {
 			$found = $Survey->isShortcode($post->ID);
 //			exit();
 			if($found)
 			{

 				//echo "<style type=\"text/css\">#content #staticsurvey { display: none; }</style>";
 				wp_enqueue_script('survey_funnel_ajax', SF_PLUGIN_URL.'/includes/assets/js/ajax.js', array('jquery') ,'1.0', false);
 				wp_enqueue_script('survey_funnel', SF_PLUGIN_URL.'/includes/assets/js/survey_funnel.js', array('jquery'),'1.0', false);
 				wp_enqueue_script('survey_funnel_fancybox', SF_PLUGIN_URL.'/includes/assets/jquery/fancyBox-2.1.5/source/jquery.fancybox.pack.js', array('jquery'),'1.0', false);

 				wp_enqueue_style('survey_funnel_styles', SF_PLUGIN_URL.'/includes/assets/css/styles.css' );
 				wp_enqueue_style('survey_funnel_client_styles', SF_PLUGIN_URL.'/includes/assets/css/survey_funnel.css' );
 				wp_enqueue_style('survey_funnel_client_styles_fancybox', SF_PLUGIN_URL.'/includes/assets/jquery/fancyBox-2.1.5/source/jquery.fancybox.css' );

 			}else{
 		$is_survey_page = false;
 		}
 		}


     }

 	function sf_update_callback(){


 	$parts = explode('action=', $_REQUEST['iPage']);

 	$action = $parts[1];

 	//$_POST['data'] = urldecode(($_POST['data']));//commented this out in 2.0.7
 	parse_str($_POST['data'], $_POST);
 	$_POST['action'] = $action;
 	$_REQUEST['action'] = $action;

 	//$data = urldecode(($_REQUEST['data']));

 	//for testing
 	//echo json_encode($_POST);die();



 		$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
 		//array_walk_recursive($gpc, 'magicQuotes_newawStripslashes');


 		ob_start();
 		//include(dirname(__FILE__) . "/classes/all_classes.php");
      include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");

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


 	die(); // this is required to return a proper result
 	}



 	function sf_delete_callback(){

 	$_POST['action'] = 'DELETE_FUNNEL';
 	$_REQUEST['action'] = 'DELETE_FUNNEL';
 		$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
 		//array_walk_recursive($gpc, 'magicQuotes_newawStripslashes');

 		ob_start();
 		//include(dirname(__FILE__) . "/classes/all_classes.php");
      include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");
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


 	die(); // this is required to return a proper result
 	}

  function sf_activate_callback(){

  $_POST['action'] = 'ACTIVATE_FUNNEL';
  $_REQUEST['action'] = 'ACTIVATE_FUNNEL';
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    //array_walk_recursive($gpc, 'magicQuotes_newawStripslashes');

    ob_start();
    //include(dirname(__FILE__) . "/classes/all_classes.php");
     include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");
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


  die(); // this is required to return a proper result
  }

 	function sf_clone_callback(){

 	$_POST['action'] = 'COPY_FUNNEL';
 	$_REQUEST['action'] = 'COPY_FUNNEL';
 		$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
 		//array_walk_recursive($gpc, 'magicQuotes_newawStripslashes');

 		ob_start();
 	//	include(dirname(__FILE__)."/classes/all_classes.php");
      include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");

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
 		die(); // this is required to return a proper result
 	}
 }

 endif;
