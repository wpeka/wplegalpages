<?php
/**
 * Class WP_Legal_Pages_Admin_Test class
 *
 * @package wplegalpages
 * @subpackage wplegalpages/tests
 */

/**
 * Require WP_Legal_Pages class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-legal-pages-admin.php';

/**
 * Class WP_Legal_Pages_Admin class test cases
 */
class WP_Legal_Pages_Admin_Test extends WP_UnitTestCase {

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
		self::$wplegalpages_admin = new WP_Legal_Pages_Admin( 'wp-legal-pages', '2.4.9' );
	}

	/**
	 * Test for enqueue_styles function
	 */
	public function test_enqueue_styles() {
		self::$wplegalpages_admin->enqueue_styles();
		global $wp_styles;
		$all_register_styles = $wp_styles->registered;
		$this->assertArrayHasKey( 'wp-legal-pages-admin', $all_register_styles, 'Failed to enqueue style wp-legal-pages-admin' );
		$this->assertArrayHasKey( 'wp-legal-pages-bootstrap', $all_register_styles, 'Failed to enqueue style wp-legal-pages-bootstrap' );
	}

	/**
	 * Test for enqueue_scripts function
	 */
	public function test_enqueue_scripts() {
		self::$wplegalpages_admin->enqueue_scripts();
		global $wp_scripts;
		$all_register_scripts = $wp_scripts->registered;
		$this->assertArrayHasKey( 'wp-legal-pages-tooltip', $all_register_scripts, 'Failed to enqueue script wp-legal-pages-tooltip' );
		$this->assertArrayHasKey( 'wp-legal-pages-vue', $all_register_scripts, 'Failed to enqueue script wp-legal-pages-vue' );
		$this->assertArrayHasKey( 'wp-legal-pages-vue-js', $all_register_scripts, 'Failed to enqueue script wp-legal-pages-vue-js' );
	}

	/**
	 * Test for admin_menu function
	 */
	public function test_admin_menu() {
		$current_user = wp_get_current_user();
		$current_user->add_cap( 'manage_options' );
		update_option( 'lp_accept_terms', '1' );

		self::$wplegalpages_admin->admin_menu();
		global $submenu, $menu;
		$submenu_array = wp_list_pluck( $submenu['legal-pages'], 2 );
		$this->assertTrue( in_array( 'legal-pages', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'lp-show-pages', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'lp-create-page', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'lp-eu-cookies', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'getting-started', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}
}

