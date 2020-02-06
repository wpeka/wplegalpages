<?php
/**
 * The Admin-specific functionality of the WP Legal Pages.
 *
 * @link       http://wplegalpages.com/
 * @since      1.5.2
 *
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/admin
 */

/**
 * The admin-specific functionality of the WP Legal Pages.
 *
 * Defines the WP Legal Pages name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/includes
 * @author     WPEka <support@wplegalpages.com>
 */
if ( ! class_exists( 'WP_Legal_Pages_Admin' ) ) {
	/**
	 * The admin-specific functionality of the WP Legal Pages.
	 *
	 * Defines the WP Legal Pages name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    WP_Legal_Pages
	 * @subpackage WP_Legal_Pages/includes
	 * @author     WPEka <support@wplegalpages.com>
	 */
	class WP_Legal_Pages_Admin {
		/**
		 * The ID of this WP Legal Pages.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $WP Legal Pages_name    The ID of this WP Legal Pages.
		 */
		private $plugin_name;

		/**
		 * The version of this WP Legal Pages.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this WP Legal Pages.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name The name of this WP Legal Pages.
		 * @param      string $version    The version of this WP Legal Pages.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			wp_register_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/wp-legal-pages-admin' . WPLPP_SUFFIX . '.css', array(), $this->version, 'all' );
			wp_register_style( $this->plugin_name . '-bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Plugin_Name_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Plugin_Name_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			wp_register_script( $this->plugin_name . '-tooltip', plugin_dir_url( __FILE__ ) . 'js/tooltip' . WPLPP_SUFFIX . '.js', array(), $this->version, true );
		}

		/**
		 * This function is provided for WordPress dashbord menus.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Legal_Pages_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Legal_Pages_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		public function admin_menu() {
			add_menu_page( __( 'Legal Pages', 'wplegalpages' ), 'Legal Pages', 'manage_options', 'legal-pages', array( $this, 'admin_setting' ), 'dashicons-media-default', 66 );
			$terms = get_option( 'lp_accept_terms' );
			if ( '1' === $terms ) {
					add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Settings', 'Settings', 'manage_options', 'legal-pages', array( $this, 'admin_setting' ) );
					add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Legal Pages', 'Legal Pages', 'manage_options', 'lp-show-pages', array( $this, 'show_pages' ) );
					add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Create Page', 'Create Page', 'manage_options', 'lp-create-page', array( $this, 'create_page' ) );
					add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Cookie Bar', 'Cookie Bar', 'manage_options', 'lp-eu-cookies', array( $this, 'update_eu_cookies' ) );
					do_action( 'wplegalpages_admin_menu' );
			}

		}

		/**
		 * Admin init for database update.
		 *
		 * @since 2.3.5
		 */
		public function wplegal_admin_init() {
			delete_option( '_lp_db_updated' );
			delete_option( '_lp_terms_updated' );
			delete_option( '_lp_terms_fr_de_updated' );
			$lp_templates_updated = get_option( '_lp_templates_updated' );
			if ( '1' !== $lp_templates_updated ) {
				global $wpdb;
				$legal_pages = new WP_Legal_Pages();
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';

				$privacy      = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/privacy.html' );
				$dmca         = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/dmca.html' );
				$terms_latest = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/Terms-of-use.html' );
				$ccpa         = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/CCPA.html' );
				$terms_fr     = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/Terms-of-use-fr.html' );
				$terms_de     = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/Terms-of-use-de.html' );

				$privacy_policy_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $legal_pages->tablename . ' WHERE title=%s', array( 'Privacy Policy' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( '0' === $privacy_policy_count ) {
					$wpdb->insert(
						$legal_pages->tablename,
						array(
							'title'      => 'Privacy Policy',
							'content'    => $privacy,
							'contentfor' => 'kCjTeYOZxB',
							'is_active'  => '1',
						),
						array( '%s', '%s', '%s', '%d' )
					); // db call ok; no-cache ok.
				} else {
					$wpdb->update(
						$legal_pages->tablename,
						array(
							'is_active'  => '1',
							'content'    => $privacy,
							'contentfor' => 'kCjTeYOZxB',
						),
						array( 'title' => 'Privacy Policy' ),
						array( '%d', '%s', '%s' ),
						array( '%s' )
					); // db call ok; no-cache ok.
				}
				$dmca_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $legal_pages->tablename . ' WHERE title=%s', array( 'DMCA' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( '0' === $dmca_count ) {
					$wpdb->insert(
						$legal_pages->tablename,
						array(
							'title'      => 'DMCA',
							'content'    => $dmca,
							'contentfor' => '1r4X6y8tssz0j',
							'is_active'  => '1',
						),
						array( '%s', '%s', '%s', '%d' )
					); // db call ok; no-cache ok.
				} else {
					$wpdb->update(
						$legal_pages->tablename,
						array(
							'is_active'  => '1',
							'content'    => $dmca,
							'contentfor' => 'r4X6y8tssz',
						),
						array( 'title' => 'DMCA' ),
						array( '%d', '%s', '%s' ),
						array( '%s' )
					); // db call ok; no-cache ok.
				}
				$terms_of_use_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $legal_pages->tablename . ' WHERE title=%s', array( 'Terms of Use' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( '0' === $terms_of_use_count ) {
					$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$legal_pages->tablename,
						array(
							'title'      => 'Terms of Use',
							'content'    => $terms_latest,
							'contentfor' => 'n1bmPjZ6Xj',
							'is_active'  => '1',
						),
						array( '%s', '%s', '%s', '%d' )
					);
				} else {
					$wpdb->update(
						$legal_pages->tablename,
						array(
							'is_active'  => '1',
							'content'    => $terms_latest,
							'contentfor' => 'n1bmPjZ6Xj',
						),
						array( 'title' => 'Terms of Use' ),
						array( '%d', '%s', '%s' ),
						array( '%s' )
					); // db call ok; no-cache ok.
				}
				$terms_of_use_fr_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $legal_pages->tablename . ' WHERE title=%s', array( 'Terms of Use - FR' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( '0' === $terms_of_use_fr_count ) {
					$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$legal_pages->tablename,
						array(
							'title'      => 'Terms of Use - FR',
							'content'    => $terms_fr,
							'contentfor' => 'MMFqUJfC3m',
							'is_active'  => '1',
						),
						array( '%s', '%s', '%s', '%d' )
					);
				} else {
					$wpdb->update(
						$legal_pages->tablename,
						array(
							'is_active'  => '1',
							'content'    => $terms_fr,
							'contentfor' => 'MMFqUJfC3m',
						),
						array( 'title' => 'Terms of Use - FR' ),
						array( '%d', '%s', '%s' ),
						array( '%s' )
					); // db call ok; no-cache ok.
				}
				$terms_of_use_de_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $legal_pages->tablename . ' WHERE title=%s', array( 'Terms of Use - DE' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( '0' === $terms_of_use_de_count ) {
					$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$legal_pages->tablename,
						array(
							'title'      => 'Terms of Use - DE',
							'content'    => $terms_de,
							'contentfor' => 'fbBlC5Y4yZ',
							'is_active'  => '1',
						),
						array( '%s', '%s', '%s', '%d' )
					);
				} else {
					$wpdb->update(
						$legal_pages->tablename,
						array(
							'is_active'  => '1',
							'content'    => $terms_de,
							'contentfor' => 'fbBlC5Y4yZ',
						),
						array( 'title' => 'Terms of Use - DE' ),
						array( '%d', '%s' ),
						array( '%s' )
					); // db call ok; no-cache ok.
				}
				$ccpa_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $legal_pages->tablename . ' WHERE title=%s', array( 'CCPA - California Consumer Privacy Act' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				if ( '0' === $ccpa_count ) {
					$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$legal_pages->tablename,
						array(
							'title'      => 'CCPA - California Consumer Privacy Act',
							'content'    => $ccpa,
							'contentfor' => 'JRevVk8nkP',
							'is_active'  => '1',
						),
						array( '%s', '%s', '%s', '%d' )
					);
				} else {
					$wpdb->update(
						$legal_pages->tablename,
						array(
							'is_active'  => '1',
							'content'    => $ccpa,
							'contentfor' => 'JRevVk8nkP',
						),
						array( 'title' => 'CCPA - California Consumer Privacy Act' ),
						array( '%d', '%s', '%s' ),
						array( '%s' )
					); // db call ok; no-cache ok.
				}
				update_option( '_lp_templates_updated', true );
			}
		}

		/**
		 * Enqueue admin common style and scripts.
		 */
		public function enqueue_common_style_scripts() {
			wp_enqueue_style( $this->plugin_name . '-admin' );
			wp_enqueue_style( $this->plugin_name . '-bootstrap' );
			wp_enqueue_script( $this->plugin_name . '-tooltip' );
		}

		/**
		 * This Callback function for Admin Setting menu for WP Legal pages.
		 */
		public function admin_setting() {
			$this->enqueue_common_style_scripts();
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin-settings.php';
		}


		/**
		 * This Callback function for Create Page menu for WP Legal pages.
		 */
		public function create_page() {
			$this->enqueue_common_style_scripts();
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/create-page.php';
		}

		/**
		 * This Callback function for Show Page menu for WP Legal pages.
		 */
		public function show_pages() {
			$this->enqueue_common_style_scripts();
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/show-pages.php';
		}

		/**
		 * This Callback function for EU_Cookies Page menu for WP Legal pages.
		 */
		public function update_eu_cookies() {
			$this->enqueue_common_style_scripts();
			include_once 'update-eu-cookies.php';
		}

	}
}
