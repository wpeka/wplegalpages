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
 * Require WP_Legal_Pages_Activator class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-legal-pages-activator.php';

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
	 * Created legal pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $lp_ids legalpage ids.
	 */
	public static $lp_ids;

	/**
	 * Created legal page.
	 *
	 * @access public
	 * @var    string $lp_id legalpage ids.
	 */
	public static $lp_id;

	/**
	 * Set up function.
	 *
	 * @param WP_UnitTest_Factory $factory helper for unit test functionality.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$lp_ids             = $factory->post->create_many( 2, array( 'post_type' => 'page' ) );
		self::$lp_id              = $factory->post->create( array( 'post_type' => 'page' ) );
		self::$wplegalpages_admin = new WP_Legal_Pages_Admin( 'wp-legal-pages', '2.7.0' );
	}

	/**
	 * Test for construction function
	 */
	public function test_construct() {
		$obj = new WP_Legal_Pages_Admin( 'wp-legal-pages', '2.7.0' );
		$this->assertTrue( $obj instanceof WP_Legal_Pages_Admin );
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

		update_option( 'lp_accept_terms', '0' );
		self::$wplegalpages_admin->admin_menu();
		$this->assertNotEmpty( menu_page_url( 'getting-started' ) );

		update_option( 'lp_accept_terms', '1' );

		self::$wplegalpages_admin->admin_menu();
		global $submenu, $menu;
		$submenu_array = wp_list_pluck( $submenu['legal-pages'], 2 );
		$this->assertTrue( in_array( 'legal-pages', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'lp-show-pages', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'lp-create-page', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'getting-started', $submenu_array ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}

	/**
	 * Test for wplegal_plugin_action_links function
	 */
	public function test_wplegal_plugin_action_links() {
		$links = self::$wplegalpages_admin->wplegal_plugin_action_links( array() );
		$this->assertRegexp( '/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/', $links[0] );
		$this->assertContains( 'https://club.wpeka.com/product/wplegalpages/?utm_source=plugins&#038;utm_campaign=wplegalpages&#038;utm_content=upgrade-to-pro', $links[0] );
	}

	/**
	 * Test for wplegalpages_add_menu_meta_box function
	 */
	public function test_wplegalpages_add_menu_meta_box() {
		global $wp_meta_boxes;
		$this->assertTrue( empty( $wp_meta_boxes ) || ! array_key_exists( 'nav-menus' ) );
		$obj = self::$wplegalpages_admin->wplegalpages_add_menu_meta_box( 'page' );
		$this->assertTrue( ! empty( $wp_meta_boxes ) || array_key_exists( 'nav-menus' ) );
	}

	/**
	 * Test for wplegal_admin_init function
	 */
	public function test_wplegal_admin_init() {
		WP_Legal_Pages_Activator::activate();
		update_option( '_lp_templates_updated', false );
		update_option( '_lp_effective_date_templates_updated', false );
		$this->assertFalse( get_option( '_lp_templates_updated' ) );
		$this->assertFalse( get_option( '_lp_effective_date_templates_updated' ) );
		self::$wplegalpages_admin->wplegal_admin_init();
		$this->assertFalse( get_option( '_lp_db_updated' ) );
		$this->assertFalse( get_option( '_lp_terms_updated' ) );
		$this->assertFalse( get_option( '_lp_terms_fr_de_updated' ) );
		$this->assertTrue( get_option( '_lp_templates_updated' ) );
		$this->assertTrue( get_option( '_lp_effective_date_templates_updated' ) );
	}

	/**
	 * Test for enqueue_common_style_scripts function
	 */
	public function test_enqueue_common_style_scripts() {
		global $wp_styles, $wp_scripts;
		$all_enqueue_style  = $wp_styles->queue;
		$all_enqueue_script = $wp_scripts->queue;

		// Before enqueue style-script.
		$this->assertFalse( in_array( 'wp-legal-pages-admin', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertFalse( in_array( 'wp-legal-pages-bootstrap', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertFalse( in_array( 'wp-legal-pages-bootstrap', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

		self::$wplegalpages_admin->enqueue_common_style_scripts();
		$all_enqueue_style  = $wp_styles->queue;
		$all_enqueue_script = $wp_scripts->queue;

		// After enqueue style-script.
		$this->assertTrue( in_array( 'wp-legal-pages-admin', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-bootstrap', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-tooltip', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}

	/**
	 * Test for admin_setting function
	 */
	public function test_admin_setting() {
		update_option( '_lp_pro_installed', '1' );
		$_POST['lp-greset']  = true;
		$_POST['lp-gsubmit'] = true;
		ob_start();
		self::$wplegalpages_admin->admin_setting();
		$actual_html = ob_get_clean();
		$this->assertTrue( is_string( $actual_html ) && ( wp_strip_all_tags( $actual_html ) !== $actual_html ) );
		global $wp_styles, $wp_scripts;
		$all_enqueue_style  = $wp_styles->queue;
		$all_enqueue_script = $wp_scripts->queue;

		// After enqueue style-script.
		$this->assertTrue( in_array( 'wp-legal-pages-admin', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-bootstrap', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-tooltip', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}

	/**
	 * Test for show_pages function
	 */
	public function test_show_pages() {
		ob_start();
		self::$wplegalpages_admin->show_pages();
		$expected_html = ob_get_clean();

		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/show-pages.php';
		$actual_html = ob_get_clean();
		$this->assertEquals( $actual_html, $expected_html );

		global $wp_styles, $wp_scripts;
		$all_enqueue_style  = $wp_styles->queue;
		$all_enqueue_script = $wp_scripts->queue;

		$this->assertTrue( in_array( 'wp-legal-pages-admin', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-bootstrap', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-tooltip', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}

	/**
	 * Test for getting_started function
	 */
	public function test_getting_started() {
		update_option( 'lp_accept_terms', '1' );
		update_option( '_lp_pro_installed', '1' );

		ob_start();
		self::$wplegalpages_admin->getting_started();
		$expected_html = ob_get_clean();

		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/getting-started.php';
		$actual_html = ob_get_clean();
		$this->assertEquals( $actual_html, $expected_html );

		global $wp_styles, $wp_scripts;
		$all_enqueue_style  = $wp_styles->queue;
		$all_enqueue_script = $wp_scripts->queue;

		$this->assertTrue( in_array( 'wp-legal-pages-admin', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-tooltip', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}

	/**
	 * Test for vue_getting_started function
	 */
	public function test_vue_getting_started() {
		ob_start();
		self::$wplegalpages_admin->vue_getting_started();
		$actual_html = ob_get_clean();

		$this->assertContains( '<div id="gettingstartedapp"></div>', $actual_html );
		$this->assertContains( '<div id="wplegal-mascot-app"></div>', $actual_html );

		global $wp_styles, $wp_scripts;
		$all_enqueue_style  = $wp_styles->queue;
		$all_enqueue_script = $wp_scripts->queue;

		$this->assertTrue( in_array( 'wp-legal-pages-vue-style', $all_enqueue_style ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-vue', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$this->assertTrue( in_array( 'wp-legal-pages-vue-js', $all_enqueue_script ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		$data = $wp_scripts->get_data( 'wp-legal-pages-vue-js', 'data' );

		// Select only string which is actually an object.
		$start = strpos( $data, '{' );
		$end   = strrpos( $data, '}' );
		$data  = substr( $data, $start, ( $end - $start ) + 1 );
		$data  = json_decode( $data, true );
		$this->assertArrayHasKey( 'ajax_url', $data, 'Failed to localize array index ajax_url.' );
		$this->assertArrayHasKey( 'ajax_nonce', $data, 'Failed to localize array index ajax_nonce.' );
		$this->assertArrayHasKey( 'is_pro', $data, 'Failed to localize array index is_pro.' );
		$this->assertArrayHasKey( 'video_url', $data, 'Failed to localize array index video_url.' );
		$this->assertArrayHasKey( 'image_url', $data, 'Failed to localize array index image_url.' );
		$this->assertArrayHasKey( 'welcome_text', $data, 'Failed to localize array index welcome_text.' );
		$this->assertArrayHasKey( 'welcome_subtext', $data, 'Failed to localize array index welcome_subtext.' );
		$this->assertArrayHasKey( 'welcome_description', $data, 'Failed to localize array index welcome_description.' );
		$this->assertArrayHasKey( 'quick_links_text', $data, 'Failed to localize array index quick_links_text.' );
		$this->assertArrayHasKey( 'features', $data, 'Failed to localize array index features.' );
		$this->assertArrayHasKey( 'configure', $data, 'Failed to localize array index configure.' );
		$this->assertArrayHasKey( 'create', $data, 'Failed to localize array index create.' );
		$this->assertArrayHasKey( 'wizard', $data, 'Failed to localize array index wizard.' );
		$this->assertArrayHasKey( 'terms', $data, 'Failed to localize array index terms.' );
	}

	/**
	 * Test for update_eu_cookies function
	 */
	public function test_update_eu_cookies() {
		ob_start();
		self::$wplegalpages_admin->update_eu_cookies();
		$html = ob_get_clean();
		$this->assertTrue( is_string( $html ) && wp_strip_all_tags( $html ) );
	}

	/**
	 * Test for wplegalpages_disable_settings_warning
	 */
	public function test_wplegalpages_disable_settings_warning() {
		$result = self::$wplegalpages_admin->wplegalpages_disable_settings_warning();
		$this->assertTrue( $result );
		$option_value = get_option( 'wplegalpages_disable_settings_warning' );
		$this->assertEquals( $option_value, '1' );
	}

	/**
	 * Test for wplegalpages_trash_page function
	 */
	public function test_wplegalpages_trash_page() {
		update_post_meta( self::$lp_id, 'is_legal', 'yes' );
		$pages                                = array( self::$lp_id );
		$footer_options                       = get_option( 'lp_footer_options' );
		$footer_options['footer_legal_pages'] = $pages;
		update_option( 'lp_footer_options', $footer_options );
		self::$wplegalpages_admin->wplegalpages_trash_page( self::$lp_id );
		$options = get_option( 'lp_footer_options' );
		$this->assertEmpty( $options['footer_legal_pages'] );
	}
}

