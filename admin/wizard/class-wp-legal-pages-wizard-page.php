<?php
/**
 * WP Legal Page.
 *
 * This class defines the page on wizard.
 *
 * @since      1.5.2
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/admin/wizard
 * @author     WPEka <support@wplegalpages.com>
 */

if ( ! class_exists( 'WP_Legal_Pages_Wizard_Page' ) ) {

	/**
	 * WPLegalPages Dashboard.
	 *
	 * This class defines the page on wizard.
	 *
	 * @since      1.5.2
	 * @package    WP_Legal_Pages
	 * @subpackage WP_Legal_Pages/admin/wizard
	 * @author     WPEka <support@wplegalpages.com>
	 */
	class WP_Legal_Pages_Wizard_Page {


		/**
		 *
		 * Legal Pages Settings.
		 *
		 * @var array $settings legalpages settings
		 */
		private $settings;


		/**
		 *
		 * Get Legal Pages Settings Array.
		 *
		 * @return array
		 */
		public function get_settings() {
			return $this->settings;
		}

		/**
		 * Set Legal Pages Settings Array.
		 *
		 * @param array $settings Settings array.
		 */
		public function set_settings( $settings ) {
			$this->settings = $settings;
		}

		/**
		 * Return available languages.
		 *
		 * @return array|mixed
		 */
		public function get_available_languages() {
			$available_languages = get_site_transient( 'wplegal_available_languages' );
			if ( ! $available_languages ) {
				$response = wp_remote_get( WPLEGAL_API_URL . 'get-languages', array( 'timeout' => 10 ) );
				if ( is_wp_error( $response ) ) {
					$available_languages = array( determine_locale() );
				}
				$response_status = wp_remote_retrieve_response_code( $response );
				if ( 200 === $response_status ) {
					$available_languages = json_decode( wp_remote_retrieve_body( $response ) );
				}
				set_site_transient( 'wplegal_available_languages', $available_languages, 24 * 60 * 60 );
			}
			return $available_languages;
		}

		/**
		 * Return post id for policy page.
		 *
		 * @param string $page Page.
		 * @return mixed|void
		 */
		public function get_pid_by_page( $page ) {
			switch ( $page ) {
				case 'terms_of_use':
					$pid = get_option( 'wplegal_terms_of_use_page' );
					break;
				case 'terms_of_use_free':
					$pid = get_option( 'wplegal_terms_of_use_free_page' );
					break;
				case 'standard_privacy_policy':
					$pid = get_option( 'wplegal_standard_privacy_policy_page' );
					break;
				case 'ccpa_free':
					$pid = get_option( 'wplegal_ccpa_free_page' );
					break;
				case 'coppa':
					$pid = get_option( 'wplegal_coppa_policy_page' );
					break;
				case 'terms_forced':
					$pid = get_option( 'wplegal_terms_forced_policy_page' );
					break;
				case 'gdpr_cookie_policy':
					$pid = get_option( 'wplegal_gdpr_cookie_policy_page' );
					break;
				case 'gdpr_privacy_policy':
					$pid = get_option( 'wplegal_gdpr_privacy_policy_page' );
					break;
				case 'dmca':
					$pid = get_option( 'wplegal_dmca_page' );
					break;
				case 'california_privacy_policy':
					$pid = get_option( 'wplegal_california_privacy_policy_page' );
					break;
				case 'privacy_policy':
					$pid = get_option( 'wplegal_privacy_policy_page' );
					break;
				case 'returns_refunds_policy':
					$pid = get_option( 'wplegal_returns_refunds_policy_page' );
					break;
				case 'impressum':
					$pid = get_option( 'wplegal_impressum_page' );
					break;
				case 'custom_legal':
					$pid = get_option( 'wplegal_custom_legal_page' );
					break;
			}
			return $pid;
		}

		/**
		 * Return remote data for the policy page.
		 *
		 * @param string $data Data to be fetched.
		 * @return array|mixed
		 */
		public function get_remote_data( $data = '' ) {
			$content         = array();
			$response        = wp_remote_get( WPLEGAL_API_URL . $data );
			$response_status = wp_remote_retrieve_response_code( $response );
			if ( 200 === $response_status ) {
				$content = json_decode( wp_remote_retrieve_body( $response ) );
			}
			return $content;
		}

		/**
		 * Get preview by wizard page.
		 *
		 * @param string $page Wizard page.
		 * @param string $language Language.
		 * @return string
		 */
		public function get_preview_by_page( $page, $language ) {
			$preview_text = $this->get_page_preview_text( $page, $language );
			$pid          = $this->get_pid_by_page( $page );
			$post_args    = array(
				'post_content' => $preview_text,
				'ID'           => $pid,
			);
			wp_update_post( $post_args );
			$page_preview = '<div class="page_preview">';
			switch ( $page ) {
				case 'terms_of_use':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Terms and Conditions', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'california_privacy_policy':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Privacy Notice For California Residents', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'terms_of_use_free':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Terms of Use', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'standard_privacy_policy':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Privacy Policy', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'ccpa_free':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'CCPA - California Consumer Privacy Act', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'coppa':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'COPPA - Children’s Online Privacy Policy', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'terms_forced':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Terms(forced agreement)', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'gdpr_cookie_policy':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'GDPR Cookie Policy', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'gdpr_privacy_policy':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'GDPR Privacy Policy', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'dmca':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'DMCA', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'privacy_policy':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Privacy Policy', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'returns_refunds_policy':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Returns and Refunds Policy', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'impressum':
					if ( ! empty( $preview_text ) ) {
						$page_preview .= '<h1>';
						$page_preview .= __( 'Impressum', 'wplegalpages' );
						$page_preview .= '</h1>';
					}
					break;
				case 'custom_legal':
					if ( ! empty( $preview_text ) ) {
						$page_title          = __( 'Custom Legal Page', 'wplegalpages' );
						$custom_page_options = get_post_meta( $pid, 'legal_page_custom_legal_options', true );
						if ( isset( $custom_page_options['custom_title_details'] ) ) {
							$page_title = $custom_page_options['custom_title_details'];
						}
						$page_preview .= '<h1>';
						$page_preview .= $page_title;
						$page_preview .= '</h1>';
					}
					$post_args = array(
						'post_content' => $preview_text,
						'post_title'   => $page_title,
						'ID'           => $pid,
					);
					wp_update_post( $post_args );
					break;
			}
			$page_preview .= $preview_text;
			$page_preview .= '</div>';
			return $page_preview;
		}

		/**
		 * Get wizard page settings.
		 *
		 * @param string $page Wizard page.
		 * @return array
		 */
		public function get_setting_fields_by_page( $page ) {
			$fields        = array();
			$lp_general    = get_option( 'lp_general' );
			$domain_name   = ! empty( $lp_general['domain'] ) ? esc_attr( $lp_general['domain'] ) : esc_url_raw(
				get_bloginfo( 'url' )
			);
			$business_name = ! empty( $lp_general['business'] ) ? esc_attr( $lp_general['business'] ) : '';
			$street        = ! empty( $lp_general['street'] ) ? esc_attr( $lp_general['street'] ) : '';
			$city_state    = ! empty( $lp_general['cityState'] ) ? esc_attr( $lp_general['cityState'] ) : '';
			$country       = ! empty( $lp_general['country'] ) ? esc_attr( $lp_general['country'] ) : '';
			$email         = ! empty( $lp_general['email'] ) ? esc_attr( $lp_general['email'] ) : '';
			$phone         = ! empty( $lp_general['phone'] ) ? esc_attr( $lp_general['phone'] ) : '';
			$address       = ! empty( $lp_general['address'] ) ? esc_attr( $lp_general['address'] ) : '';
			switch ( $page ) {
				case 'terms_of_use':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-street'        => array(
							'title'    => __( 'Street', 'wplegalpages' ),
							'value'    => $street,
							'required' => false,
						),
						'lp-city-state'    => array(
							'title'    => __( 'City, State, Zip code', 'wplegalpages' ),
							'value'    => $city_state,
							'required' => false,
						),
						'lp-country'       => array(
							'title'    => __( 'Country', 'wplegalpages' ),
							'value'    => $country,
							'required' => false,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'terms_of_use_free':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'standard_privacy_policy':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'ccpa_free':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'coppa':
					$fields = array(
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
						'lp-street'        => array(
							'title'    => __( 'Street', 'wplegalpages' ),
							'value'    => $street,
							'required' => false,
						),
						'lp-city-state'    => array(
							'title'    => __( 'City, State, Zip code', 'wplegalpages' ),
							'value'    => $city_state,
							'required' => false,
						),
						'lp-country'       => array(
							'title'    => __( 'Country', 'wplegalpages' ),
							'value'    => $country,
							'required' => false,
						),
					);
					break;
				case 'terms_forced':
					$fields = array(
						'lp-domain-name' => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-email'       => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
						'lp-street'      => array(
							'title'    => __( 'Street', 'wplegalpages' ),
							'value'    => $street,
							'required' => false,
						),
						'lp-city-state'  => array(
							'title'    => __( 'City, State, Zip code', 'wplegalpages' ),
							'value'    => $city_state,
							'required' => false,
						),
						'lp-country'     => array(
							'title'    => __( 'Country', 'wplegalpages' ),
							'value'    => $country,
							'required' => false,
						),
					);
					break;
				case 'gdpr_cookie_policy':
					$fields = array();
					break;
				case 'gdpr_privacy_policy':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'dmca':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'california_privacy_policy':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'privacy_policy':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'returns_refunds_policy':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
						'lp-street'        => array(
							'title'    => __( 'Street', 'wplegalpages' ),
							'value'    => $street,
							'required' => false,
						),
						'lp-city-state'    => array(
							'title'    => __( 'City, State, Zip code', 'wplegalpages' ),
							'value'    => $city_state,
							'required' => false,
						),
						'lp-country'       => array(
							'title'    => __( 'Country', 'wplegalpages' ),
							'value'    => $country,
							'required' => false,
						),
						'lp-phone'         => array(
							'title'    => __( 'Phone', 'wplegalpages' ),
							'value'    => $phone,
							'required' => true,
						),
						'lp-email'         => array(
							'title'    => __( 'Email', 'wplegalpages' ),
							'value'    => $email,
							'required' => true,
						),
					);
					break;
				case 'impressum':
					$fields = array(
						'lp-domain-name'   => array(
							'title'    => __( 'Domain Name', 'wplegalpages' ),
							'value'    => $domain_name,
							'required' => true,
						),
						'lp-business-name' => array(
							'title'    => __( 'Business Name', 'wplegalpages' ),
							'value'    => $business_name,
							'required' => true,
						),
					);
					break;
				case 'custom_legal':
						$fields = array(
							'lp-domain-name'   => array(
								'title'     => __( 'Domain Name', 'wplegalpages' ),
								'value'     => $domain_name,
								'required'  => true,
								'shortcode' => '[Domain]',
							),
							'lp-business-name' => array(
								'title'     => __( 'Business Name', 'wplegalpages' ),
								'value'     => $business_name,
								'required'  => true,
								'shortcode' => '[Business Name]',
							),
							'lp-phone'         => array(
								'title'     => __( 'Phone', 'wplegalpages' ),
								'value'     => $phone,
								'required'  => true,
								'shortcode' => '[Phone]',
							),
							'lp-email'         => array(
								'title'     => __( 'Email', 'wplegalpages' ),
								'value'     => $email,
								'required'  => true,
								'shortcode' => '[Email]',
							),
						);
					break;
			}

			return $fields;
		}

		/**
		 * Create policy page and return post id.
		 *
		 * @param string $page Page.
		 * @param string $title Page title.
		 * @return int|void|WP_Error
		 */
		public function get_pid_by_insert_page( $page, $title = '' ) {
			$lp_general = get_option( 'lp_general' );
			$content    = $this->get_preview_from_remote( $page, array(), $lp_general, $lp_general['language'] );
			$post_args  = array(
				'post_title'   => apply_filters( 'the_title', $title ),
				'post_content' => $content,
				'post_type'    => 'page',
				'post_status'  => 'draft',
				'post_author'  => get_current_user_id(),
			);
			$pid        = wp_insert_post( $post_args );
			if ( ! is_wp_error( $pid ) ) {
				return $pid;
			} else {
				return;
			}
		}

		/**
		 * Get wizard page sections.
		 *
		 * @param string $page Wizard page.
		 * @return array|mixed
		 */
		public function get_section_fields_by_page( $page ) {
			$fields = array();
			$pid    = $this->get_pid_by_page( $page );
			switch ( $page ) {
				case 'terms_of_use_free':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Terms of Use' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_remote_data( 'get_terms_of_use' );
						update_post_meta( $pid, 'legal_page_terms_of_use_settings', $fields );
						update_option( 'wplegal_terms_of_use_free_page', $pid );
					} else {
						$terms_of_use_options = get_post_meta( $pid, 'legal_page_terms_of_use_settings', true );
						if ( ! $terms_of_use_options || empty( $terms_of_use_options ) ) {
							$fields = $this->get_remote_data( 'get_terms_of_use' );
							update_post_meta( $pid, 'legal_page_terms_of_use_settings', $fields );
						} else {
							$fields = $terms_of_use_options;
						}
					}
					break;
				case 'standard_privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Privacy Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_remote_data( 'get_standard_privacy_policy' );
						update_post_meta( $pid, 'legal_page_standard_privacy_policy_settings', $fields );
						update_option( 'wplegal_standard_privacy_policy_page', $pid );
					} else {
						$standard_privacy_policy_options = get_post_meta( $pid, 'legal_page_standard_privacy_policy_settings', true );
						if ( ! $standard_privacy_policy_options || empty( $standard_privacy_policy_options ) ) {
							$fields = $this->get_remote_data( 'get_standard_privacy_policy' );
							update_post_meta( $pid, 'legal_page_standard_privacy_policy_settings', $fields );
						} else {
							$fields = $standard_privacy_policy_options;
						}
					}
					break;
				case 'terms_of_use':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Terms and Conditions' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_remote_data( 'get_clauses' );
						update_post_meta( $pid, 'legal_page_clauses', $fields );
						update_option( 'wplegal_terms_of_use_page', $pid );
					} else {
						$terms_options = get_post_meta( $pid, 'legal_page_clauses', true );
						if ( ! $terms_options || empty( $terms_options ) ) {
							$fields = $this->get_remote_data( 'get_clauses' );
							update_post_meta( $pid, 'legal_page_clauses', $fields );
						} else {
							$fields = $terms_options;
						}
					}
					break;
				case 'california_privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Privacy Notice For California Residents' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_remote_data( 'get_ccpa_settings' );
						update_post_meta( $pid, 'legal_page_ccpa_settings', $fields );
						update_option( 'wplegal_california_privacy_policy_page', $pid );
					} else {
						$ccpa_options = get_post_meta( $pid, 'legal_page_ccpa_settings', true );
						if ( ! $ccpa_options || empty( $ccpa_options ) ) {
							$fields = $this->get_remote_data( 'get_ccpa_settings' );
							update_post_meta( $pid, 'legal_page_ccpa_settings', $fields );
						} else {
							$fields = $ccpa_options;
						}
					}
					break;
				case 'privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Privacy Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = (array) $this->get_remote_data( 'get_privacy_settings' );

						$fields = WP_Legal_Pages_Admin::wplegalpages_add_gdpr_options_to_remote_data( $fields );

						update_post_meta( $pid, 'legal_page_privacy_settings', $fields );
						update_option( 'wplegal_privacy_policy_page', $pid );
					} else {
						$privacy_options = get_post_meta( $pid, 'legal_page_privacy_settings', true );

						if ( ! $privacy_options || empty( $privacy_options ) ) {
							$fields = $this->get_remote_data( 'get_privacy_settings' );
							$fields = WP_Legal_Pages_Admin::wplegalpages_add_gdpr_options_to_remote_data( $fields );
							update_post_meta( $pid, 'legal_page_privacy_settings', $fields );
						} else {
							$fields = $privacy_options;
							$fields = WP_Legal_Pages_Admin::wplegalpages_add_gdpr_options_to_remote_data( $fields );
						}
					}
					break;
				case 'returns_refunds_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Returns and Refunds Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_remote_data( 'get_returns_refunds_settings' );
						update_post_meta( $pid, 'legal_page_returns_refunds_settings', $fields );
						update_option( 'wplegal_returns_refunds_policy_page', $pid );
					} else {
						$returns_refunds_options = get_post_meta( $pid, 'legal_page_returns_refunds_settings', true );
						if ( ! $returns_refunds_options || empty( $returns_refunds_options ) ) {
							$fields = $this->get_remote_data( 'get_returns_refunds_settings' );
							update_post_meta( $pid, 'legal_page_returns_refunds_settings', $fields );
						} else {
							$fields = $returns_refunds_options;
						}
					}
					break;
				case 'impressum':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Impressum' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_remote_data( 'get_impressum_settings' );
						update_post_meta( $pid, 'legal_page_impressum_settings', $fields );
						update_option( 'wplegal_impressum_page', $pid );
					} else {
						$impressum_options = get_post_meta( $pid, 'legal_page_impressum_settings', true );
						if ( ! $impressum_options || empty( $impressum_options ) ) {
							$fields = $this->get_remote_data( 'get_impressum_settings' );
							update_post_meta( $pid, 'legal_page_impressum_settings', $fields );
						} else {
							$fields = $impressum_options;
						}
					}
					break;
				case 'custom_legal':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Custom Legal Page' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$fields = $this->get_custom_legal_page_fields();
						update_post_meta( $pid, 'legal_page_custom_legal_settings', $fields );
						update_option( 'wplegal_custom_legal_page', $pid );
					} else {
						$custom_legal_options = get_post_meta( $pid, 'legal_page_custom_legal_settings', true );
						if ( ! $custom_legal_options || empty( $custom_legal_options ) ) {
							$fields = $this->get_custom_legal_page_fields();
							update_post_meta( $pid, 'legal_page_custom_legal_settings', $fields );
						} else {
							$fields = $custom_legal_options;
						}
					}
					break;
			}
			return $fields;
		}

		/**
		 * Get custom legal page fields.
		 *
		 * @return array|mixed
		 */
		public function get_custom_legal_page_fields() {
			$fields = (object) array(
				'general_information' => (object) array(
					'id'          => 'general_information',
					'title'       => '',
					'description' => '',
					'enabled'     => 1,
					'checked'     => 1,
					'fields'      => (object) array(
						'custom_title'   => (object) array(
							'id'          => 'custom_title',
							'title'       => 'Policy Title',
							'description' => 'Enter the title of your policy here',
							'type'        => 'section',
							'position'    => 1,
							'checked'     => 1,
							'parent'      => 'general_information',
							'collapsible' => '',
							'sub_fields'  => array(
								'custom_title_details' => (object) array(
									'id'          => 'custom_title_details',
									'title'       => '',
									'description' => '',
									'type'        => 'input',
									'position'    => 1,
									'parent'      => 'custom_title',
									'name'        => 'custom_title_details',
									'value'       => '',
									'sub_fields'  => array(),
								),
							),
						),
						'custom_content' => (object) array(
							'id'          => 'custom_content',
							'title'       => 'Policy Description',
							'description' => 'Enter the text for your policy here',
							'type'        => 'section',
							'position'    => 1,
							'checked'     => 1,
							'parent'      => 'general_information',
							'collapsible' => '',
							'sub_fields'  => array(
								'custom_content_details' => (object) array(
									'id'          => 'custom_content_details',
									'title'       => '',
									'description' => '',
									'type'        => 'wpeditor',
									'position'    => 1,
									'parent'      => 'custom_content',
									'name'        => 'custom_content_details',
									'value'       => '',
									'sub_fields'  => array(),
								),
							),
						),
					),
				),
			);

			return $fields;
		}

		/**
		 * Get wizard page preview.
		 *
		 * @param string $page Wizard page.
		 * @return string
		 */
		public function get_page_preview_text( $page ) {
			$lp_general                 = get_option( 'lp_general' );
			$lp_general['last_updated'] = gmdate( 'F j, Y' );
			update_option( 'lp_general', $lp_general );
			$preview_text = '';
			$pid          = $this->get_pid_by_page( $page );
			switch ( $page ) {

				case 'terms_of_use':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Terms and Conditions' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$clauses       = $this->get_remote_data( 'get_clauses' );
						$terms_options = array();
						foreach ( $clauses as $key => $clause ) {
							if ( empty( $clause->fields ) ) {
								$clause->fields        = $this->get_remote_data( 'get_clause_settings?clause=' . $key );
								$terms_options[ $key ] = $clause;
							}
						}
						update_post_meta( $pid, 'legal_page_clauses', $terms_options );
						update_option( 'wplegal_terms_of_use_page', $pid );
					} else {
						$terms_clauses = get_post_meta( $pid, 'legal_page_clauses_options', true );
						$terms_options = $terms_clauses;
					}
					$options      = $terms_options;
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					break;

				case 'terms_of_use_free':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Terms of Use' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$terms_of_use_free_settings = $this->get_remote_data( 'get_terms_of_use' );
						$terms_of_use_free_options  = array();
						foreach ( $terms_of_use_free_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$terms_of_use_free_options[ $key ] = true;
								$fields                            = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$terms_of_use_free_options[ $field_key ] = true;
									} else {
										$terms_of_use_free_options[ $field_key ] = false;
									}
								}
							} else {
								$terms_of_use_free_options[ $key ] = false;
							}
						}
						update_post_meta( $pid, 'legal_page_terms_of_use_settings', $terms_of_use_free_settings );
						update_post_meta( $pid, 'legal_page_terms_of_use_options', $terms_of_use_free_options );
						update_option( 'wplegal_terms_of_use_free_page', $pid );
					} else {
						$terms_of_use_free_settings = get_post_meta( $pid, 'legal_page_terms_of_use_options', true );
						$terms_of_use_free_options  = $terms_of_use_free_settings;
					}
					$options      = $terms_of_use_free_options;
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );

					break;
				case 'standard_privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Privacy Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$standard_privacy_policy_settings = $this->get_remote_data( 'get_standard_privacy_policy' );
						$standard_privacy_policy_options  = array();
						foreach ( $standard_privacy_policy_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$standard_privacy_policy_options[ $key ] = true;
								$fields                                  = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$standard_privacy_policy_options[ $field_key ] = true;
									} else {
										$standard_privacy_policy_options[ $field_key ] = false;
									}
								}
							} else {
								$standard_privacy_policy_options[ $key ] = false;
							}
						}
						update_post_meta( $pid, 'legal_page_standard_privacy_policy_settings', $standard_privacy_policy_settings );
						update_post_meta( $pid, 'legal_page_standard_privacy_policy_options', $standard_privacy_policy_options );
						update_option( 'wplegal_standard_privacy_policy_page', $pid );
					} else {
						$standard_privacy_policy_settings = get_post_meta( $pid, 'legal_page_standard_privacy_policy_options', true );
						$standard_privacy_policy_options  = $standard_privacy_policy_settings;
					}
					$options      = $standard_privacy_policy_options;
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );

					break;
				case 'ccpa_free':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'CCPA - California Consumer Privacy Act' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						update_option( 'wplegal_ccpa_free_page', $pid );
					}
					$options      = array();
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					if ( ! shortcode_exists( 'wpl_cookie_details' ) ) {
						$preview_text = str_replace( '[wpl_cookie_details]', '', stripslashes( $preview_text ) );
					} else {
						$preview_text = do_shortcode( $preview_text );
					}
					break;
				case 'coppa':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'COPPA - Children’s Online Privacy Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						update_option( 'wplegal_coppa_policy_page', $pid );
					}
					$options      = array();
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					break;
				case 'terms_forced':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Terms(forced agreement)' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						update_option( 'wplegal_terms_forced_policy_page', $pid );
					}
					$options      = array();
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					break;

				case 'gdpr_cookie_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'GDPR Cookie Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						update_option( 'wplegal_gdpr_cookie_policy_page', $pid );
					}
					$options      = array();
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					if ( ! shortcode_exists( 'wpl_cookie_details' ) ) {
						$preview_text = str_replace( '[wpl_cookie_details]', '', stripslashes( $preview_text ) );
					} else {
						$preview_text = do_shortcode( $preview_text );
					}
					break;

				case 'gdpr_privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'GDPR Privacy Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						update_option( 'wplegal_gdpr_privacy_policy_page', $pid );
					}
					$options      = array();
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					break;

				case 'dmca':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'DMCA' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						update_option( 'wplegal_dmca_page', $pid );
					}
					$options      = array();
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );

					break;

				case 'california_privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Privacy Notice For California Residents' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$ccpa_settings = $this->get_remote_data( 'get_ccpa_settings' );
						$ccpa_options  = array();
						foreach ( $ccpa_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$ccpa_options[ $key ] = true;
								$fields               = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$ccpa_options[ $field_key ] = true;
									} else {
										$ccpa_options[ $field_key ] = false;
									}
								}
							} else {
								$ccpa_options[ $key ] = false;
							}
						}
						update_post_meta( $pid, 'legal_page_ccpa_settings', $ccpa_settings );
						update_post_meta( $pid, 'legal_page_ccpa_options', $ccpa_options );
						update_option( 'wplegal_california_privacy_policy_page', $pid );
					} else {
						$ccpa_settings = get_post_meta( $pid, 'legal_page_ccpa_options', true );
						$ccpa_options  = $ccpa_settings;
					}
					$options      = $ccpa_options;
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );

					if ( ! shortcode_exists( 'wpl_cookie_details' ) ) {
						$preview_text = str_replace( '[wpl_cookie_details]', '', stripslashes( $preview_text ) );
					} else {
						$preview_text = do_shortcode( $preview_text );
					}

					break;

				case 'privacy_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Privacy Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$privacy_settings = $this->get_remote_data( 'get_privacy_settings' );
						$privacy_options  = array();
						foreach ( $privacy_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$privacy_options[ $key ] = true;
								$fields                  = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$privacy_options[ $field_key ] = true;
										if ( isset( $field->sub_fields ) && ! empty( $field->sub_fields ) ) {
											foreach ( $field->sub_fields as $key => $sub_field ) {
												if ( isset( $field->checked ) && true === $field->checked ) {
													$privacy_options[ $key ] = true;
												} else {
													$privacy_options[ $key ] = false;
												}
											}
										}
									} else {
										$privacy_options[ $field_key ] = false;
									}
								}
							} else {
								$privacy_options[ $key ] = false;
							}
						}

						update_post_meta( $pid, 'legal_page_privacy_settings', $privacy_settings );
						update_post_meta( $pid, 'legal_page_privacy_options', $privacy_options );
						update_option( 'wplegal_privacy_policy_page', $pid );
					} else {
						$privacy_settings = get_post_meta( $pid, 'legal_page_privacy_options', true );
						$privacy_options  = $privacy_settings;
					}
					$options      = $privacy_options;
					$options      = WP_Legal_Pages_Admin::wplegalpages_add_gdpr_policy_description( $options );
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );

					break;

				case 'returns_refunds_policy':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Returns and Refunds Policy' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$returns_refunds_settings = $this->get_remote_data( 'get_returns_refunds_settings' );
						$returns_refunds_options  = array();
						foreach ( $returns_refunds_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$returns_refunds_options[ $key ] = true;
								$fields                          = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$returns_refunds_options[ $field_key ] = true;
										if ( isset( $field->sub_fields ) && ! empty( $field->sub_fields ) ) {
											foreach ( $field->sub_fields as $key => $sub_field ) {
												if ( isset( $field->checked ) && true === $field->checked ) {
													$returns_refunds_options[ $key ] = true;
												} else {
													$returns_refunds_options[ $key ] = false;
												}
											}
										}
									} else {
										$returns_refunds_options[ $field_key ] = false;
									}
								}
							} else {
								$returns_refunds_options[ $key ] = false;
							}
						}

						update_post_meta( $pid, 'legal_page_returns_refunds_settings', $returns_refunds_settings );
						update_post_meta( $pid, 'legal_page_returns_refunds_options', $returns_refunds_options );
						update_option( 'wplegal_returns_refunds_policy_page', $pid );
					} else {
						$returns_refunds_settings = get_post_meta( $pid, 'legal_page_returns_refunds_options', true );
						$returns_refunds_options  = $returns_refunds_settings;
					}
					$options      = $returns_refunds_options;
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					break;
				case 'impressum':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Impressum' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$impressum_settings = $this->get_remote_data( 'get_impressum_settings' );
						$impressum_options  = array();
						foreach ( $impressum_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$impressum_options[ $key ] = true;
								$fields                    = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$impressum_options[ $field_key ] = true;
										if ( isset( $field->sub_fields ) && ! empty( $field->sub_fields ) ) {
											foreach ( $field->sub_fields as $key => $sub_field ) {
												if ( isset( $field->checked ) && true === $field->checked ) {
													$impressum_options[ $key ] = true;
												} else {
													$impressum_options[ $key ] = false;
												}
											}
										}
									} else {
										$impressum_options[ $field_key ] = false;
									}
								}
							} else {
								$impressum_options[ $key ] = false;
							}
						}

						update_post_meta( $pid, 'legal_page_impressum_settings', $impressum_settings );
						update_post_meta( $pid, 'legal_page_impressum_options', $impressum_options );
						update_option( 'wplegal_impressum_page', $pid );
					} else {
						$impressum_settings = get_post_meta( $pid, 'legal_page_impressum_options', true );
						$impressum_options  = $impressum_settings;
					}
					$options      = $impressum_options;
					$preview_text = $this->get_preview_from_remote( $page, $options, $lp_general, $lp_general['language'] );
					break;
				case 'custom_legal':
					if ( empty( $pid ) ) {
						$pid = $this->get_pid_by_insert_page( $page, 'Custom Legal Page' );
						update_post_meta( $pid, 'is_legal', 'yes' );
						update_post_meta( $pid, 'legal_page_type', $page );
						$custom_legal_settings = $this->get_custom_legal_page_fields();
						$custom_legal_options  = array();
						foreach ( $custom_legal_settings as $key => $option ) {
							if ( isset( $option->checked ) && true === $option->checked ) {
								$custom_legal_options[ $key ] = true;
								$fields                       = $option->fields;
								foreach ( $fields as $field_key => $field ) {
									if ( isset( $field->checked ) && true === $field->checked ) {
										$custom_legal_options[ $field_key ] = true;
										if ( isset( $field->sub_fields ) && ! empty( $field->sub_fields ) ) {
											foreach ( $field->sub_fields as $key => $sub_field ) {
												if ( isset( $field->checked ) && true === $field->checked ) {
													$custom_legal_options[ $key ] = true;
												} else {
													$custom_legal_options[ $key ] = false;
												}
											}
										}
									} else {
										$custom_legal_options[ $field_key ] = false;
									}
								}
							} else {
								$custom_legal_options[ $key ] = false;
							}
						}

						update_post_meta( $pid, 'legal_page_custom_legal_settings', $custom_legal_settings );
						update_post_meta( $pid, 'legal_page_custom_legal_options', $custom_legal_options );
						update_option( 'wplegal_custom_legal_page', $pid );

					} else {

						$custom_legal_settings = get_post_meta( $pid, 'legal_page_custom_legal_options', true );
						$custom_legal_options  = $custom_legal_settings;
					}
					$options      = $custom_legal_options;
					$preview_text = $options['custom_content_details'];
					$lp_find      = array( '[Domain]', '[Business Name]', '[Phone]', '[Street]', '[City, State, Zip code]', '[Country]', '[Email]', '[Address]', '[Niche]' );
					$lp_general   = get_option( 'lp_general' );
					$preview_text = str_replace( $lp_find, $lp_general, stripslashes( $preview_text ) );
					break;
			}
			return $preview_text;
		}

		/**
		 * Get remote preview for policy page content.
		 *
		 * @param string $page Page.
		 * @param array  $options Options array.
		 * @param array  $lp_general General options.
		 * @param string $lang Language.
		 * @return mixed|string
		 */
		private function get_preview_from_remote( $page, $options, $lp_general, $lang = 'en_US' ) {
			$text = '';

			$response = wp_remote_post(
				WPLEGAL_API_URL . 'get_content',
				array(
					'body' => array(
						'page'       => $page,
						'options'    => $options,
						'lp_general' => $lp_general,
						'lang'       => $lang,
					),
				)
			);

			if ( is_wp_error( $response ) ) {
				$text = '';
			}

			$response_status = wp_remote_retrieve_response_code( $response );
			if ( 200 === $response_status ) {
				$text = json_decode( wp_remote_retrieve_body( $response ) );
			}

			return $text;

		}

	}
}
