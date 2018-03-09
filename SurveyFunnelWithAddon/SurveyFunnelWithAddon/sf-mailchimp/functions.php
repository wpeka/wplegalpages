<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SFMailChimpFunctions' ) ){
	
	class SFMailChimpFunctions{
		
		public function __construct(){
			$sf_mailchimp_enable_subscription = get_option( 'sf_mailchimp_enable_subscription' );
			if( $sf_mailchimp_enable_subscription ){
				add_action( 'sf_after_user_info_fields', array( $this, 'sf_mailchimp_add_subscription_checkbox' ) );
				add_action( 'sf_after_user_info_submission', array( $this, 'sf_add_mailchimp_user'), 12, 1 );
			}
		}
		
		/*
		 * Subscribe email address to MailChimp
		 */
		function sf_add_mailchimp_user( $request ){
			$username = $request['user_name'];
			$email = $request['email_id'];
			$api_key = get_option( 'sf_mailchimp_api_key' );
			$list_id = get_option( 'sf_mailchimp_list_id_'.$request['survey_id'] );
			$mailchimp_subscription = $request['form'][2]['value'];
			error_log(print_r($form[2]['value'],true));
			if( !empty( $api_key ) && !empty( $list_id ) && $mailchimp_subscription == 'on' ){
				include_once( SF_MAILCHIMP_PATH."lib/mailchimp/MailChimp.php");
				$sfMailChimpObj = new SFMailChimpIN( $api_key );
				$sfMailChimpObj->call( array( 'list_id'=>$list_id, 'email'=>$email, 'uname'=>$username ) );
			}
		}
		
		function sf_mailchimp_add_subscription_checkbox(){
			?>
			<script>
			   jQuery(document).ready(function(){
					jQuery('#usercontent form').append("<span><input type=checkbox checked name='mailchimp_subscription'>Subscribe to newsletter</span>");
				})
			</script>
			<?php 
			
		}
	}
	
	new SFMailChimpFunctions();
}
