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
			wp_register_style( $this->plugin_name . '-vue-style', plugin_dir_url( __FILE__ ) . 'css/vue/vue-getting-started' . WPLPP_SUFFIX . '.css', array(), $this->version, 'all' );
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
			wp_register_script( $this->plugin_name . '-vue', plugin_dir_url( __FILE__ ) . 'js/vue/vue.js', array(), $this->version, true );
			wp_register_script( $this->plugin_name . '-vue-js', plugin_dir_url( __FILE__ ) . 'js/vue/vue-getting-started.js', array( 'jquery' ), $this->version, true );
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
			$terms = get_option( 'lp_accept_terms' );
			if ( '1' === $terms ) {
				add_menu_page( __( 'Legal Pages', 'wplegalpages' ), 'Legal Pages', 'manage_options', 'legal-pages', array( $this, 'admin_setting' ), 'dashicons-media-default', 66 );
			} else {
				add_menu_page( __( 'Legal Pages', 'wplegalpages' ), 'Legal Pages', 'manage_options', 'getting-started', array( $this, 'vue_getting_started' ), 'dashicons-media-default', 66 );
			}
			if ( '1' === $terms ) {
				add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Settings', 'Settings', 'manage_options', 'legal-pages', array( $this, 'admin_setting' ) );
				add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Legal Pages', 'Legal Pages', 'manage_options', 'lp-show-pages', array( $this, 'show_pages' ) );
				add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Create Page', 'Create Page', 'manage_options', 'lp-create-page', array( $this, 'create_page' ) );
				add_submenu_page( __( 'legal-pages', 'wplegalpages' ), 'Cookie Bar', 'Cookie Bar', 'manage_options', 'lp-eu-cookies', array( $this, 'update_eu_cookies' ) );
				do_action( 'wplegalpages_admin_menu' );
				add_submenu_page( 'legal-pages', 'Getting Started', __( 'Getting Started', 'wplegalpages' ), 'manage_options', 'getting-started', array( $this, 'vue_getting_started' ) );
			}

		}

		/**
		 * Admin init for database update.
		 *
		 * @since 2.3.5
		 */
		public function wplegal_admin_init() {
			$lp_templates_updated = get_option( '_lp_templates_updated' );
			if ( '1' !== $lp_templates_updated ) {
				global $wpdb;
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				if ( is_multisite() ) {
					// Get all blogs in the network and activate plugin on each one.
					$blog_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs ); // db call ok; no-cache ok.
					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						$this->wplegal_admin_init_install_db();
						restore_current_blog();
					}
				} else {
					$this->wplegal_admin_init_install_db();
				}
			}
		}

		/**
		 * Returns plugin action links.
		 *
		 * @param array $links Plugin action links.
		 * @return array
		 */
		public function wplegal_plugin_action_links( $links ) {
			$lp_pro_installed = get_option( '_lp_pro_installed' );
			if ( '1' !== $lp_pro_installed ) {
				$links = array_merge(
					array(
						'<a href="' . esc_url( 'https://club.wpeka.com/product/wplegalpages/?utm_source=plugins&utm_campaign=wplegalpages&utm_content=upgrade-to-pro' ) . '" target="_blank" rel="noopener noreferrer"><strong style="color: #11967A; display: inline;">' . __( 'Upgrade to Pro', 'wplegalpages' ) . '</strong></a>',
					),
					$links
				);
			}
			return $links;
		}

		/**
		 * Update templates on admin init.
		 */
		public function wplegal_admin_init_install_db() {
			delete_option( '_lp_db_updated' );
			delete_option( '_lp_terms_updated' );
			delete_option( '_lp_terms_fr_de_updated' );
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
			$activated = apply_filters( 'wplegal_check_license_status', true );
			if ( $activated ) {
				$this->enqueue_common_style_scripts();
				include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/create-page.php';
			}
		}

		/**
		 * This Callback function for Show Page menu for WP Legal pages.
		 */
		public function show_pages() {
			$activated = apply_filters( 'wplegal_check_license_status', true );
			if ( $activated ) {
				$this->enqueue_common_style_scripts();
				include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/show-pages.php';
			}
		}

		/**
		 * This Callback function for Getting Started menu for WP Legal pages.
		 */
		public function getting_started() {
			wp_enqueue_style( $this->plugin_name . '-admin' );
			wp_enqueue_script( $this->plugin_name . '-tooltip' );
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/getting-started.php';
		}

		/**
		 * This Callback function for Getting Started menu for WP Legal pages.
		 */
		public function vue_getting_started() {
			$is_pro = get_option( '_lp_pro_active' );
			if ( $is_pro ) {
				$support_url = 'https://club.wpeka.com/my-account/orders/?utm_source=wplegalpages&utm_medium=help-mascot&utm_campaign=link&utm_content=support';
			} else {
				$support_url = 'https://wordpress.org/support/plugin/wplegalpages/?utm_source=wplegalpages&utm_medium=help-mascot&utm_campaign=link&utm_content=forums';
			}
			wp_enqueue_style( $this->plugin_name . '-vue-style' );
			wp_enqueue_script( $this->plugin_name . '-vue' );
			wp_enqueue_script( $this->plugin_name . '-vue-js' );
			wp_localize_script(
				$this->plugin_name . '-vue-js',
				'obj',
				array(
					'ajax_url'          => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'        => wp_create_nonce( 'admin-ajax-nonce' ),
					'is_pro'            => $is_pro,
					'wizard_url'        => menu_page_url( 'wplegal-wizard', false ),
					'settings_url'      => menu_page_url( 'legal-pages', false ),
					'pages_url'         => menu_page_url( 'lp-create-page', false ),
					'video_url'         => 'https://www.youtube-nocookie.com/embed/iqdLl9qsBHc',
					'image_url'         => WPL_LITE_PLUGIN_URL . 'admin/js/vue/images/',
					'documentation_url' => 'https://docs.wpeka.com/wp-legal-pages/?utm_source=wplegalpages&utm_medium=help-mascot&utm_campaign=link&utm_content=documentation',
					'faq_url'           => 'https://docs.wpeka.com/wp-legal-pages/faq/?utm_source=wplegalpages&utm_medium=help-mascot&utm_campaign=link&utm_content=faq',
					'support_url'       => $support_url,
					'upgrade_url'       => 'https://club.wpeka.com/product/wplegalpages/?utm_source=wplegalpages&utm_medium=help-mascot&utm_campaign=link&utm_content=upgrade-to-pro',
				)
			);
			?>
			<div id="gettingstartedapp"></div>
			<div id="wplegal-mascot-app"></div>
			<?php
		}

		/**
		 * This Callback function for EU_Cookies Page menu for WP Legal pages.
		 */
		public function update_eu_cookies() {
			$activated = apply_filters( 'wplegal_check_license_status', true );
			if ( $activated ) {
				$this->enqueue_common_style_scripts();
				include_once 'update-eu-cookies.php';
			}
		}

		/**
		 * Accpet terms.
		 */
		public function wplegal_accept_terms() {
			// Check nonce.
			check_admin_referer( 'lp-accept-terms' );
			update_option( 'lp_accept_terms', '1' );
			add_submenu_page( 'legal-pages', 'Getting Started', __( 'Getting Started', 'wplegalpages' ), 'manage_options', 'getting-started', array( $this, 'vue_getting_started' ) );
			wp_send_json_success( array( 'terms_accepted' => true ) );
		}

		/**
		 * Returns whether terms accepts or not.
		 */
		public function wplegal_get_accept_terms() {
			$result = array(
				'success' => false,
				'data'    => false,
			);
			if ( isset( $_GET['action'] ) ) {
				$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
				if ( wp_verify_nonce( $nonce, 'admin-ajax-nonce' ) ) {
					$result['success'] = true;
					$result['data']    = get_option( 'lp_accept_terms' );
				}
			}
			return wp_send_json( $result );
		}

		/**
		 * Accept terms.
		 */
		public function wplegal_save_accept_terms() {
			$result = array(
				'success' => false,
				'data'    => false,
			);
			if ( isset( $_POST['action'] ) ) {
				$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
				if ( wp_verify_nonce( $nonce, 'admin-ajax-nonce' ) ) {
					if ( isset( $_POST['data']['lp_accept_terms'] ) ) {
						$lp_accept_terms = sanitize_text_field( wp_unslash( $_POST['data']['lp_accept_terms'] ) );
						update_option( 'lp_accept_terms', $lp_accept_terms );
						add_submenu_page( 'legal-pages', 'Getting Started', __( 'Getting Started', 'wplegalpages' ), 'manage_options', 'getting-started', array( $this, 'vue_getting_started' ) );
						$result['success'] = true;
						$result['data']    = get_option( 'lp_accept_terms' );
					}
				}
			}
			return wp_send_json( $result );
		}

	}
}
