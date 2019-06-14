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
if(!class_exists('WP_Legal_Pages_Admin')){
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
	 * @param      string    $WP Legal Pages_name       The name of this WP Legal Pages.
	 * @param      string    $version    The version of this WP Legal Pages.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_styles() {
		if( (isset( $_GET['page'] )) && (($_GET['page'] == 'legal-pages')||($_GET['page'] == 'lp-show-pages')||($_GET['page'] == 'lp-create-page')||($_GET['page'] == 'lp-eu-cookies'))){
		wp_enqueue_style( $this->plugin_name."-admin", plugin_dir_url( __FILE__ ) . 'css/wp-legal-pages-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name."-bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}
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
		 	if( (isset( $_GET['page'] )) && (($_GET['page'] == 'legal-pages')||($_GET['page'] == 'lp-show-pages')||($_GET['page'] == 'lp-create-page')||($_GET['page'] == 'lp-eu-cookies'))){
		wp_enqueue_script('tooltip-js', WPL_LITE_PLUGIN_URL. 'admin/js/tooltip.js');
}
	}

       /**
        * This function is provided for wordpress dashbord menus.
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

		add_menu_page(__('Legal Pages','legal-pages'), 'Legal Pages', 'manage_options', 'legal-pages', array($this, 'admin_setting'), 'dashicons-media-default', 66);
                $terms = get_option('lp_accept_terms');
                if($terms == 1){
                        add_submenu_page(__('legal-pages','legal-pages'), 'Settings', 'Settings', 'manage_options', 'legal-pages', array($this, 'admin_setting'));
                        add_submenu_page(__('legal-pages','legal-pages'), 'Legal Pages', 'Legal Pages', 'manage_options', 'lp-show-pages', array($this, 'show_pages'));
                        add_submenu_page(__('legal-pages','legal-pages'), 'Create Page', 'Create Page', 'manage_options', 'lp-create-page', array($this, 'create_page'));
                        add_submenu_page(__('legal-pages','legal-pages'), 'Cookie Bar', 'Cookie Bar', 'manage_options', 'lp-eu-cookies', array($this, 'update_eu_cookies'));
                }

	}

        /**
        * This Callback function for Admin Setting menu for WP Legal pages.
        *
        */
        function admin_setting()
        {
            include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin-settings.php';
        }


        /**
        * This Callback function for Create Page menu for WP Legal pages.
        *
        */

        function create_page()
        {
             include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/create-page.php';
        }

        /**
         * This Callback function for Show Page menu for WP Legal pages.
         *
         */

        function show_pages()
        {
            include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/show-pages.php';
        }
        /**
         * This Callback function for EU_Cookies Page menu for WP Legal pages.
         *
         */
        function update_eu_cookies()
            {
        	      include_once "updateEUCookies.php";
        	 }

}
}
