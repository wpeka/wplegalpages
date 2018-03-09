<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SF_Admin' ) ) :
class SF_Admin {
	var $survey_id;
	var $start;
	var $end;
	public function __construct() {

		add_action('admin_menu', array($this,'survey_funnel_admin_menu'));
		add_action( 'admin_init', array($this,'survey_funnel_admin_init'));
		add_action( 'admin_enqueue_scripts', array($this,'my_enqueue') );
	}

	public function survey_funnel_admin_menu() {
		// Admin Navigation

		add_menu_page('Survey Funnel Dashboard', 'Survey Funnel', 'manage_options', 'survey_funnel_welcome', array($this,'survey_funnel_dashboard') , SF_PLUGIN_URL.'/admin/images/survey.png');
		add_submenu_page('survey_funnel_welcome', 'Survey Funnel', 'Dashboard', 'manage_options', 'survey_funnel_welcome', array($this,'survey_funnel_dashboard'));

		$addPage = add_submenu_page('survey_funnel_welcome', 'Survey Funnel: Add New', 'Add New Funnel', 'manage_options', 'survey_funnel_add', array($this,'survey_funnel_add'));

		add_submenu_page($addPage, 'Survey Funnel: Edit', '', 'manage_options', 'survey_funnel_edit', array($this,'survey_funnel_edit'));
		add_submenu_page($addPage, 'Survey Funnel: Results', '', 'manage_options', 'survey_funnel_results', array($this,'survey_funnel_results'));

		add_submenu_page('survey_funnel_welcome', 'Survey Funnel', 'Settings', 'manage_options', 'survey_funnel_setting', array($this,'survey_funnel_setting'));

		// Adding submenu Social Sharing
		//add_submenu_page('survey_funnel_welcome','Social Sharing','Social Sharing','manage_options','survey_funnel_sharing',array($this,'survey_funnel_sharing'));
	}

// Callback function called when submenu "Social Sharing" clicked
/*
	public function survey_funnel_sharing(){
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
			# code...
		}
			wp_enqueue_style('survey_style',SF_PLUGIN_URL.'/admin/css/style.css');
			wp_enqueue_style('survey_style_admin',SF_PLUGIN_URL.'/admin/css/survey-funnel-admin.css');
			wp_enqueue_style('bootstrap_sharing_css', SF_PLUGIN_URL . "/admin/assets/global/plugins/bootstrap/css/bootstrap.min.css");
			include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/admin/templates/sharing.php");
	}
	*/

	public function survey_funnel_welcome() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/admin-header.php');
		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/admin/welcome.php");
	}

	function survey_funnel_setting() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		wp_enqueue_style('survey_style',SF_PLUGIN_URL.'/includes/assets/css/styles.css');

		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/admin-header.php');

		if ( isset($_POST['saveEmail']) ){

			$email_display = isset($_POST['sf-email']) ? $_POST['sf-email'] : '';
			update_option('sf_email_id', $email_display);
		}
		else{

			$email_display = get_option('sf_email_id');

			if( empty($email_display) ){

				$email_display = '';
			}
		}

		wp_enqueue_script('sf_bootstrap_min_scripts', SF_PLUGIN_URL .'/admin/assets/global/plugins/bootstrap/js/bootstrap.min.js');

		//Metronic style
		include('survey-funnel-header.php');

		?>
		<div class="container-fluid form-start">
		  <div class="row">
		    <div class="col-md-8">
		      <div class="portlet box blue-hoki">
		        <div class="portlet-title">
		          <div class="caption">
		            Survey Funnel Settings
		          </div>
		        </div>
		        <div class="portlet-body form">
		          <!-- BEGIN FORM-->
		          	<form action='' method='POST'>
		            <div class="form-body">
		              <div class="form-group">
										<div class="row">
		                <label class="col-md-3 control-label">Send Mail From</label>
		                <div class="col-md-4">
		                  <input id='sf-email' name='sf-email' class="form-control input-circle"  type='email' value='<?php echo $email_display; ?>' maxlength='255' />
										</div>
										<div class="col-md-4">
											<span class="description">After successful subscription users will get mail from this email id.</span>
		                </div>
									</div>
		              </div>
		            </div>
		            <div class="form-actions">
		              <div class="row">
		                <div class="col-md-offset-3 col-md-9">
											<input class='btn btn-circle green' id='saveEmail' name='saveEmail' type='submit' value='Submit'/>
		                </div>
		              </div>
		            </div>
		          </form>
		          <!-- END FORM-->
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
<?php
	}

	function survey_funnel_dashboard() {
		global $update_needed;
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		wp_enqueue_style('survey_style',SF_PLUGIN_URL.'/includes/assets/css/styles.css');

		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/admin-header.php');
		//include(dirname(__FILE__)."/form_templates/dashboard.php");
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/dashboard.php');

	}

	/**
	 * Enter description here...
	 *
	 */
	function survey_funnel_add() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		wp_register_script('date.js', SF_PLUGIN_URL . '/includes/assets/js/date.js','','',true);
		wp_enqueue_script('date.js');
		wp_enqueue_style('survey',SF_PLUGIN_URL.'/includes/assets/css/datepicker.css');
		wp_register_script('jquery.ui.datepicker.js', SF_PLUGIN_URL . '/includes/assets/js/jquery.ui.datepicker.js');
		wp_enqueue_script('jquery.ui.datepicker.js');
		wp_enqueue_style('bootstrap_admin_css', SF_PLUGIN_URL . "/admin/assets/global/plugins/bootstrap/css/bootstrap.min.css");
		wp_enqueue_script('bootstrap_admin_js', SF_PLUGIN_URL . '/admin/assets/global/plugins/bootstrap/js/bootstrap.min.js');
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/admin-header.php');
		//include(dirname(__FILE__)."/form_templates/add_new.php");
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/add_new.php');

	}

	/**
	 * Enter description here...
	 *
	 */
	function survey_funnel_edit() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		wp_register_script('date.js', SF_PLUGIN_URL . '/includes/assets/js/date.js','','',true);
		wp_enqueue_script('date.js');
		wp_enqueue_style('survey',SF_PLUGIN_URL.'/includes/assets/css/datepicker.css');
		wp_register_script('jquery.ui.datepicker.js', SF_PLUGIN_URL . '/includes/assets/js/jquery.ui.datepicker.js');
		wp_enqueue_script('jquery.ui.datepicker.js');
		wp_enqueue_style('bootstrap_admin_css', SF_PLUGIN_URL . "/admin/assets/global/plugins/bootstrap/css/bootstrap.min.css");
		wp_enqueue_script('bootstrap_admin_js', SF_PLUGIN_URL . '/admin/assets/global/plugins/bootstrap/js/bootstrap.min.js');

		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/all_classes.php");

		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/edit.php');
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/question.php');

		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/content.php');
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/templates/userInfo.php');

	}


	/**
	 * Enter description here...
	 *
	 */
	function survey_funnel_results() {

		//include_once 'classes/sf_individual_result_class.php';
		include(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR."/includes/classes/sf_individual_result_class.php");

		//include_once 'class-admin-result-analyze-sf.php';
		include_once(WP_PLUGIN_DIR.'/'.SF_PLUGIN_DIR.'/admin/class-survey-funnel-admin-result-analyze.php');
	}

	function survey_funnel_admin_init() {
		/* Register our script. */
		wp_register_script('survey_funnel_minicolor_js', SF_PLUGIN_URL.'/includes/assets/jquery/js/jquery.miniColors.min.js' );
		wp_register_style('survey_funnel_jquery_ui_css', SF_PLUGIN_URL.'/includes/assets/jquery/css/redmond/jquery-ui-1.7.3.custom.css' );
		wp_register_style('survey_funnel_minicolor_css', SF_PLUGIN_URL.'/includes/assets/jquery/css/minicolor/jquery.miniColors.css' );

		//Figures updates

	}

	function my_enqueue($hook) {

		global $post;
		if(is_object($post)){
		    wp_enqueue_media( array(
		        'post' => $post->ID
		    ) );
		}

		wp_enqueue_media();

		//echo $hook;exit;
		$dash_ar = array(
				'survey-funnel_page_survey_funnel_dashboard',
				'admin_page_survey_funnel_edit',
				'survey-funnel_page_survey_funnel_add',
				'toplevel_page_survey_funnel_welcome',
				''

		);
		if(in_array($hook, $dash_ar)) {
			/* Added in 2.1.2 to include wordpress jquery */
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script( 'jquery-ui-dialog' );

			wp_enqueue_script( 'survey_funnel_minicolor_js');
			wp_enqueue_style( 'survey_funnel_jquery_ui_css');
			wp_enqueue_style( 'survey_funnel_minicolor_css');
			wp_enqueue_script('survey_funnel_ajax', SF_PLUGIN_URL.'/includes/assets/js/ajax.js', array('jquery') ,'1.0', false);

			// Media Uploader
			wp_enqueue_script(array('media-upload', 'thickbox'));
			wp_enqueue_style('thickbox');

			wp_enqueue_style('survey_funnel_result_css',SF_PLUGIN_URL.'/includes/assets/css/sf_result.css');
			// Main styles
			wp_enqueue_style('survey_funnel_styles', SF_PLUGIN_URL.'/includes/assets/css/styles.css' );
			wp_enqueue_script('survey_funnel_scripts', SF_PLUGIN_URL .'/includes/assets/js/scripts.js');

			//Metronic style
			include('survey-funnel-header.php');

			//Metronic script
			include('survey-funnel-footer.php');

		}


		return;
	}

}
endif;

new SF_Admin();
