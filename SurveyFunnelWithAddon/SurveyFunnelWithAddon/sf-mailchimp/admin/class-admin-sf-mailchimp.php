<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SFMailChimp_Admin' ) ){
	
	class SFMailChimp_admin{
		
		public function __construct(){
			add_action( 'admin_menu', array( $this, 'sf_mailchimp_admin_menu' ), 12 );
			$this->sf_mailchimp_add_list_id_setting();
			add_action( 'sf_after_form_submit', array( $this, 'sf_mailchimp_save_list_id' ), 12, 1 );
		}
		
		/*
		 * Function to add MailChimp menu in Survey Funnel Tab
		 */
		function sf_mailchimp_admin_menu(){
			add_submenu_page('survey_funnel_welcome', 'Survey Funnel', 'MailChimp Setting', 'manage_options', 'sf_mailchimp_setting', array($this,'sf_mailchimp_setting'));
		}
		
		/*
		 * Add field in backend to save mailchimp api key
		 * Save mailchimp api key in options table
		 */
		function sf_mailchimp_setting(){
			wp_enqueue_style('survey_style',SF_PLUGIN_URL.'/css/styles.css');
			wp_enqueue_style('bootstrap_admin_css', SF_PLUGIN_URL . "/js/bootstrap/css/bootstrap.min.css");
			wp_enqueue_script('bootstrap_admin_js', SF_PLUGIN_URL . '/js/bootstrap/js/bootstrap.min.js');
			$sf_mailchimp_api_key = get_option( 'sf_mailchimp_api_key' );
			$sf_mailchimp_enable_subscription = get_option('sf_mailchimp_enable_subscription');
			if( current_user_can( 'administrator' ) ){
				if( isset( $_POST['savemailchimpkey'] ) )
				{
					if( isset( $_POST['sf_mailchimp_api_key'] ) )
					{ 
						$sf_mailchimp_api_key = sanitize_text_field( $_POST['sf_mailchimp_api_key'] );
						$sf_mailchimp_enable_subscription = $_POST['sf_mailchimp_enable_subscription'];
						update_option( 'sf_mailchimp_api_key', $sf_mailchimp_api_key );
						update_option( 'sf_mailchimp_enable_subscription', $sf_mailchimp_enable_subscription);
					}
				}
			}
			
			/* Include view for mailchimp api key setting */
			include_once( SF_MAILCHIMP_PATH."admin/sf_mailchimp_setting_view.php" );
						
		}
		
		/*
		 * Add setting for getting mailchimp list ID
		 * Each survey funnel will have Different/Similar list ID's
		 */
		function sf_mailchimp_add_list_id_setting(){
			add_action( 'sf_before_add_question', array( $this, 'sf_add_mailchimp_list_id' ), 12, 1 );
		}
		
		function sf_add_mailchimp_list_id( $survey_id ){
			$sf_mailchimp_list_id = get_option( 'sf_mailchimp_list_id_'.$survey_id );
		?>
			  	<span class="label label-info" style="font-size:13px;"><?php _e('MailChimp List ID'); ?></span><br/><br/>
				<span><?php _e('Enter MailChimp List ID:'); ?> <input type="text" name="sf_mailchimp_list_id_<?php echo $survey_id;?>" id="sf_mailchimp_list_id_<?php echo $survey_id;?>" value="<?php echo $sf_mailchimp_list_id; ?>" size="20"> </span>
				<hr/>
		<?php 	
		}
		
		/*
		 * Save MailChimp List ID for each survey funnel
		 */
		function sf_mailchimp_save_list_id( $request_array ){
			$sf_mailchimp_list_id = $request_array['sf_mailchimp_list_id_'.$request_array['survey_id']];
			  if( current_user_can( 'administrator' ) ){	
				    update_option( 'sf_mailchimp_list_id_'.$request_array['survey_id'], sanitize_text_field( $sf_mailchimp_list_id ) );
			}
		}

	}
	
	new SFMailChimp_admin();
}
