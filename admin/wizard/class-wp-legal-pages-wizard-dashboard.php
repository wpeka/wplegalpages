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
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->legal_pages = array(
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
					'desc'    => __( 'To limit your liability to copyright infringement claims', 'wplegalpages' ),
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
			);
			$this->legal_pages = apply_filters( 'wplegalpages_wizard_templates', $this->legal_pages );
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

	}
}
