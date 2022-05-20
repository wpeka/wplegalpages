<?php
/**
 * WPLegalPages Dashboard.
 *
 * This class defines all the pages on wizard dashboard.
 *
 * @since      1.5.2
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/admin/wizard
 * @author     WPEka <support@wplegalpages.com>
 */

if ( ! class_exists( 'WP_Legal_Pages_Wizard_Dashboard' ) ) {

	/**
	 * WPLegalPages Dashboard.
	 *
	 * This class defines all the pages on wizard dashboard.
	 *
	 * @since      1.5.2
	 * @package    WP_Legal_Pages
	 * @subpackage WP_Legal_Pages/admin/wizard
	 * @author     WPEka <support@wplegalpages.com>
	 */
	class WP_Legal_Pages_Wizard_Dashboard {

		/**
		 *
		 * Legal Pages Array.
		 *
		 * @var array $legal_pages legalpages
		 */
		public $legal_pages = array();

		/**
		 *
		 * Pro Legal Pages Array.
		 *
		 * @var array $pro_legalpages legalpages
		 */
		public $pro_legalpages = array();

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->legal_pages    = array(
				'Available Templates' => array(
					'standard_privacy_policy' => array(
						'title'   => __( 'Standard Privacy Policy', 'wplegalpages' ),
						'desc'    => __( 'If you collect any personal data from user.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'lang'    => array( 'English' ),
						'enabled' => true,
					),
					'terms_of_use_free'       => array(
						'title'   => __( 'Terms of Use', 'wplegalpages' ),
						'desc'    => __( 'If you want to protect your business.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'lang'    => array( 'English', 'French', 'German' ),
						'enabled' => true,
					),
					'dmca'                    => array(
						'title'   => __( 'DMCA', 'wplegalpages' ),
						'desc'    => __( 'To limit your liability to copyright infringement claims.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'lang'    => array( 'English' ),
						'enabled' => true,
					),
					'ccpa_free'               => array(
						'title'   => __( 'CCPA - California Consumer Privacy Act', 'wplegalpages' ),
						'desc'    => __( 'If you are collecting personal data and have California based users.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'lang'    => array( 'English' ),
						'enabled' => true,
					),
				),
			);
			$this->legal_pages    = apply_filters( 'wplegalpages_wizard_templates', $this->legal_pages );
			$this->pro_legalpages = array(
				'Popular'  => array(
					'privacy_policy_grey'            => array(
						'title'   => __( 'Professional Privacy Policy', 'wplegalpages' ),
						'desc'    => __( 'If you collect any personal data from users (GDPR and CCPA compliant).', 'wplegalpages' ),
						'btn_txt' => __( 'Go Pro', 'wplegalpages' ),
						'enabled' => false,
					),
					'terms_of_use_grey'              => array(
						'title'   => __( 'Terms and Conditions', 'wplegalpages' ),
						'desc'    => __( 'If you want to protect your business.', 'wplegalpages' ),
						'btn_txt' => __( 'Go Pro', 'wplegalpages' ),
						'enabled' => false,
					),
					'returns_refunds_policy_grey'    => array(
						'title'   => __( 'Returns & Refunds Policy', 'wplegalpages' ),
						'desc'    => __( 'If you want to protect your e-Commerce business.', 'wplegalpages' ),
						'btn_txt' => __( 'Go Pro', 'wplegalpages' ),
						'enabled' => false,
					),
					'impressum_grey'                 => array(
						'title'   => __( 'Impressum', 'wplegalpages' ),
						'desc'    => __( 'If you want to issue a statement of ownership and authorship of your content.', 'wplegalpages' ),
						'btn_txt' => __( 'Go Pro', 'wplegalpages' ),
						'enabled' => false,
					),
					'california_privacy_policy_grey' => array(
						'title'   => __( 'CCPA', 'wplegalpages' ),
						'desc'    => __( 'If you have California based users and want to give them clarity on disclosure of personal information.', 'wplegalpages' ),
						'btn_txt' => __( 'Go Pro', 'wplegalpages' ),
						'enabled' => false,
					),
					'coppa_grey'                     => array(
						'title'   => __( 'COPPA', 'wplegalpages' ),
						'desc'    => __( 'If you are collecting personal information on your website from children below 13 years in age.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'terms_forced_grey'              => array(
						'title'   => __( 'Terms(forced agreement)', 'wplegalpages' ),
						'desc'    => __( 'Use when you don\'t want your users to proceed without agreeing to website terms', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'custom_legal_grey'              => array(
						'title'   => __( 'Create Custom Legal Page', 'wplegalpages' ),
						'desc'    => __( 'Add your own text to create a custom legal policy.', 'wplegalpages' ),
						'btn_txt' => __( 'Go Pro', 'wplegalpages' ),
						'enabled' => false,
					),
				),
				'Policies' => array(
					'gdpr_cookie_policy_grey'   => array(
						'title'   => __( 'GDPR Cookie Policy', 'wplegalpages' ),
						'desc'    => __( 'Use when you have visitors from the EU & are using cookies on your website.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'gdpr_privacy_policy_grey'  => array(
						'title'   => __( 'GDPR Privacy Policy', 'wplegalpages' ),
						'desc'    => __( 'Use when your website collects personal information and has visitors from the EU.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'cookies_policy_grey'       => array(
						'title'   => __( 'Cookies Policy', 'wplegalpages' ),
						'desc'    => __( 'To inform users about the cookies active on your website that track user data.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'blog_comments_policy_grey' => array(
						'title'   => __( 'Blog Comments Policy', 'wplegalpages' ),
						'desc'    => __( 'Use when you have comments enabled on your blog.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'linking_policy_grey'       => array(
						'title'   => __( 'Linking Policy', 'wplegalpages' ),
						'desc'    => __( 'Use this policy to inform the users about the terms and conditions for linking to your website and disclaimers for external linking.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
					'external_link_policy_grey' => array(
						'title'   => __( 'External Links Policy', 'wplegalpages' ),
						'desc'    => __( 'If your website links to other external websites, you can use this to ensure that the external links are in compliance with the applicable laws.', 'wplegalpages' ),
						'btn_txt' => __( 'Create', 'wplegalpages' ),
						'enabled' => false,
					),
				),
			);
		}

		/**
		 *
		 * Get Legal Pages.
		 *
		 * @return array $legal_pages legal pages.
		 */
		public function get_legal_pages() {
			return $this->legal_pages;
		}

		/**
		 *
		 * Get Pro Legal Pages
		 *
		 * @return array $pro_legalpages legal pages.
		 */
		public function get_pro_legal_pages() {
			return $this->pro_legalpages;
		}

	}
}
