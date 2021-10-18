<?php
/**
 * Class Wplegalpages_admin_test
 *
 * @package wplegalpages
 * @subpackage wplegalpages/tests
 */

/**
 * Required file.
 */
require_once ABSPATH . 'wp-admin/includes/ajax-actions.php';

/**
 * Require WP_Legal_Pages class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-legal-pages-admin.php';

/**
 * Require WP_Legal_Pages class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-legal-pages.php';

/**
 * Unit test cases for ajax request in reports page.
 *
 * @package    wplegalpages
 * @subpackage wplegalpages/tests
 * @author     WPEka <hello@wpeka.com>
 */
class AjaxTestCLASS extends WP_Ajax_UnitTestCase {

	/**
	 * The WP_Legal_Pages_Admin class instance.
	 *
	 * @access public
	 * @var    string    $wplegalpages_admin class instance.
	 */
	public static $wplegalpages_admin;

	/**
	 * The WP_Legal_Pages class instance.
	 *
	 * @access public
	 * @var    string    $wplegalpages class instance.
	 */
	public static $wplegalpages;

	/**
	 * Set up function.
	 *
	 * @param WP_UnitTest_Factory $factory helper for unit test functionality.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$wplegalpages_admin = new WP_Legal_Pages_Admin( 'wp-legal-pages', '2.6.0' );
		self::$wplegalpages = new WP_Legal_Pages();
		// self::$wplegalpages->define_admin_hooks();
	}

	/**
	 * Sample test
	 */
	public function test_sample() {
		$this->assertTrue( true );
	}

	/**
	 * Test for wplegalpages_save_footer_form
	 */
	public function test_wplegalpages_save_footer_form() {
		error_log( print_r( 'Test AJAX', true ) );
		$this->_setRole( 'administrator' );
		$_POST['lp_footer_nonce_data']     = wp_create_nonce( 'settings_footer_form_nonce' );
		$_POST['action']                   = 'lp_save_footer_form';
		$_POST['lp-footer-pages']          = '';
		$_POST['lp-is-footer']             = '';
		$_POST['lp-footer-link-bg-color']  = '';
		$_POST['lp-footer-align']          = '';
		$_POST['lp-footer-separator']      = '';
		$_POST['lp-footer-new-tab']        = '';
		$_POST['lp-footer-text-color']     = '';
		$_POST['lp-footer-link-color']     = '';
		$_POST['lp-footer-font']           = '';
		$_POST['lp-footer-font-family-id'] = '';
		$_POST['lp-footer-font-size']      = '';
		$_POST['lp-footer-css']            = '';

		try {
			$this->_handleAjax( 'lp_save_footer_form' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		error_log( print_r( $this->_last_response, true ) );
		$this->assertTrue( true );
	}

	/**
	 * Test for wplegalpages_save_footer_form
	 */
	// public function test_wplegalpages_save_footer_form() {
	// 	$this->_setRole( 'administrator' );
	// 	$_POST['lp_footer_nonce_data']     = wp_create_nonce( 'settings_footer_form_nonce' );
	// 	$_POST['action']                   = 'lp_save_footer_form';
	// 	$_POST['lp-footer-pages']          = '';
	// 	$_POST['lp-is-footer']             = true;
	// 	$_POST['lp-footer-link-bg-color']  = '';
	// 	$_POST['lp-footer-align']          = '';
	// 	$_POST['lp-footer-separator']      = '';
	// 	$_POST['lp-footer-new-tab']        = '';
	// 	$_POST['lp-footer-text-color']     = '';
	// 	$_POST['lp-footer-link-color']     = '';
	// 	$_POST['lp-footer-font']           = '';
	// 	$_POST['lp-footer-font-family-id'] = '';
	// 	$_POST['lp-footer-font-size']      = '';
	// 	$_POST['lp-footer-css']            = '';

	// 	try {
	// 		self::$wplegalpages_admin->wplegalpages_save_footer_form();
	// 	} catch ( WPDieException $ex ) {
	// 		unset( $ex );
	// 	}
	// 	print_r( get_option( 'lp_footer_options' ) );
	// 	$this->assertTrue( true );
	// }
}
