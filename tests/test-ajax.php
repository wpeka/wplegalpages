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
	 * Set up function.
	 *
	 * @param WP_UnitTest_Factory $factory helper for unit test functionality.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$wplegalpages_admin = new WP_Legal_Pages_Admin( 'wp-legal-pages', '2.6.0' );
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
		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
	}

	/**
	 * Test for wplegal_accept_terms
	 */
	public function test_wplegal_accept_terms() {
		$_POST['_wpnonce'] = wp_create_nonce( 'lp-accept-terms' );
		try {
			$this->_handleAjax( 'lp_accept_terms' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
	}

	/**
	 * Test for get_accept_terms
	 */
	public function test_get_accept_terms() {
		$_GET['nonce']  = wp_create_nonce( 'admin-ajax-nonce' );
		$_GET['action'] = 'get_accept_terms';
		try {
			$this->_handleAjax( 'get_accept_terms' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
	}

	/**
	 * Test for save_accept_terms
	 */
	public function test_save_accept_terms() {
		$_POST['nonce']                   = wp_create_nonce( 'admin-ajax-nonce' );
		$_POST['action']                  = 'save_accept_terms';
		$_POST['data']['lp_accept_terms'] = '1';
		try {
			$this->_handleAjax( 'save_accept_terms' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( $response['success'] );
	}
}
