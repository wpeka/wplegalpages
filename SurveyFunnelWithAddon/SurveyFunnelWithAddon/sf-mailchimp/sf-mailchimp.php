<?php
/*
Plugin Name: Mailchimp Add-on for Survey Funnel
Plugin URI: http://www.surveyfunnel.com
Description: Integrates mailchimp with survey funnel.
Version: 1.0
Author: Survey Funnel
Author URI: http://www.surveyfunnel.com
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SFMailChimp' ) ){
	
	class SFMailChimp{
		
		public function __construct(){
			
			$this->define_constant();
			$this->includes();
		}
		
		function define_constant(){
			define( 'SF_MAILCHIMP_URL', plugin_dir_url( __FILE__ ) );
			define( 'SF_MAILCHIMP_PATH', plugin_dir_path(__FILE__ ) );
		}
		
		function includes(){
			include_once( SF_MAILCHIMP_PATH."admin/class-admin-sf-mailchimp.php");
			include_once( SF_MAILCHIMP_PATH."functions.php");
		}
	}
	
	new SFMailChimp();
}
