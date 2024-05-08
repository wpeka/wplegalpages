<?php
/**
 * Class for handling the WP Cookie Consent App authentication.
 *
 * @category   X
 * @package    Wplegalpages
 * @subpackage Wplegalpages/includes/settings
 * @author     Display Name <username@example.com>
 * @copyright  2019    CyberChimps, Inc.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * @link       https://club.wpeka.com
 * @since      3.0.0
 */

/**
 * Class WP_Legal_Pages_App_Auth.
 * 
 * @category   X
 * @package    Wplegalpages
 * @subpackage Wplegalpages/includes/settings
 * @author     Display Name <username@example.com>
 * @copyright  2019    CyberChimps, Inc.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * @link       https://club.wpeka.com
 * @since      3.0.0
 */
class WP_Legal_Pages_App_Auth {

    /**
     * Base URL of WP Legal Pages App API
     */
    const API_BASE_PATH = WPLEGAL_APP_URL . '/wp-json/api/v1/';

    /**
     * Is the current plugin authenticated with the WPLegalPages App
     *
     * @var bool
     */
    private $has_auth;

    /**
     * The api key used for authenticated requests to the WPLegalPages App.
     *
     * @var string
     */
    private $auth_key;

    /**
     * The auth data from the db.
     *
     * @var array
     */
    private $auth_data;

    /**
     * Header arguments
     *
     * @var array
     */
    private $headers = array();

    /**
     * Request max timeout
     *
     * @var int
     */
    private $timeout = 180;

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct() 
    {
        // Add AJAX actions for authentication.
        if (is_admin()) {
            add_action('wp_ajax_wp_legal_pages_app_start_auth', array($this, 'ajax_auth_url'));
            add_action('wp_ajax_wp_legal_pages_app_store_auth', array($this, 'store_auth_key'));
            add_action('wp_ajax_wp_legal_pages_app_delete_auth', array($this, 'delete_app_auth'));
        }
    }

    /**
     * Ajax handler that returns the auth url used to start the Connect process.
     *
     * @return void
     */
    public function ajax_auth_url() 
    {

        // Verify AJAX nonce.
        check_ajax_referer('wp-legal-pages', '_ajax_nonce');

        // Check user capabilities.
        if (! current_user_can('manage_options')) {
            wp_send_json_error(esc_html__('You do not have permissions to connect WP Cookie Consent.', 'wplegalpages'));
        }
        $is_new_user = filter_input(INPUT_POST, 'is_new_user', FILTER_VALIDATE_BOOLEAN);
        $site_address = rawurlencode(get_site_url());
        $api_auth_url = $is_new_user ? $this->get_api_url('signup') : $this->get_api_url('login');

        // Build auth URL with site name.
        $auth_url = add_query_arg(
            array(
                'platform' => 'wordpress',
                'site' => $site_address,
                'rest_url' => rawurlencode(get_rest_url()),
                'src_plugin' => 'wplegalpages',),
            $api_auth_url
        );

        // Send JSON response with auth URL.
        wp_send_json_success(
            array(
                'url' => $auth_url,)
        );
    }

    /**
     * Get the full URL to an API endpoint by passing the path.
     *
     * @param string $path The path for the API endpoint.
     *
     * @return string
     */
    public function get_api_url($path) 
    {

        return trailingslashit(WPLEGAL_APP_URL) . $path;

    }

    /**
     * Get the full path to an API endpoint by passing the path.
     *
     * @param string $path The path for the API endpoint.
     *
     * @return string
     */
    public function get_api_path($path) 
    {
        return trailingslashit(self::API_BASE_PATH) . $path;
    }

    /**
     * Ajax handler to save the auth API key.
     *
     * @return void
     */
    public function store_auth_key() 
    {

        // Verify AJAX nonce.
        check_ajax_referer('wp-legal-pages', '_ajax_nonce');

        // Check user capabilities.
        if (! current_user_can('manage_options')) {
            wp_send_json_error(esc_html__('You do not have permissions to connect WP Cookie Consent.', 'wplegalpages'));
        }

        // Get data from POST request.
        $data   = $_POST['response'];
        $origin = ! empty($_POST['origin']) ? esc_url_raw(wp_unslash($_POST['origin'])) : false;

        // Verify data and origin.
        if (empty($data) || WPLEGAL_APP_URL !== $origin) {
            wp_send_json_error();
        }

        // Update option with auth data.
        update_option('wplegal_api_framework_app_settings', $data);

        $this->auth_data = $data;

        // Send success response.
        wp_send_json_success(
            array(
                'title' => __('Authentication successfully completed', 'wplegalpages'),
                'text'  => __('Reloading page, please wait.', 'wplegalpages'),)
        );
    }

    /**
     * Ajax handler to delete the auth data and disconnect the site from the WPCode Library.
     *
     * @return void
     */
    public function delete_app_auth() 
    {

        // Verify AJAX nonce.
        check_ajax_referer('wp-legal-pages', '_ajax_nonce');

        // Require necessary file and get settings.
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/settings/class-wp-legal-pages-settings.php';
        $settings = new WP_Legal_Pages_Settings();
        $options  = $settings->get();

        // Make auth request.
        $this->make_auth_request();

        // Make POST request to disconnect plugin.
        $response = $this->post(
            'plugin/disconnect',
            wp_json_encode(
                array(
                    'id'       => $settings->get_user_id(),
                    'site_key' => $settings->get_website_key(),
                    'platform' => 'wordpress',)
            )
        );

        $response_code = wp_remote_retrieve_response_code($response);

        // Check response code and update settings.
        if (200 !== $response_code) {
            wp_send_json_error();
        }
        $options['api']['token'] = '';
        $settings->update($options);
        $options['account']['connected'] = false;
        $settings->update($options);

        // Send success response.
        wp_send_json_success(
            array(
                'title' => __('Plugin disconnected', 'wplegalpages'),
                'text'  => __('Reloading page, please wait.', 'wplegalpages'),)
        );
    }

    /**
     * Check if the site is authenticated.
     *
     * @return bool Whether the site is authenticated.
     */
    public function has_auth() 
    {
        if (! isset($this->has_auth)) {
            $auth_key = $this->get_auth_key();

            $this->has_auth = ! empty($auth_key);
        }
        return $this->has_auth;
    }

    /**
     * Get the auth key.
     *
     * @return bool|string he auth key if available, otherwise false.
     */
    public function get_auth_key() 
    {
        if (! isset($this->auth_key)) {
            $data           = $this->get_auth_data();
            $this->auth_key = isset($data['api']['token']) ? $data['api']['token'] : false;
        }
        return $this->auth_key;
    }

    /**
     * Get the auth data from the db.
     *
     * @return array|bool The auth data if available, otherwise false.
     */
    public function get_auth_data() 
    {
        if (! isset($this->auth_data)) {
            $this->auth_data = get_option('wplegal_api_framework_app_settings', false);
        }
        return $this->auth_data;
    }

    /**
     * Make a POST API Call
     *
     * @param string $path Endpoint route.
     * @param array  $data Data.
     *
     * @return mixed
     */
    public function post($path, $data = array()) 
    {
        try {
            return $this->request($path, $data);
        } catch (Exception $e) {
            return new WP_Error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Add a new request argument for GET requests
     *
     * @param string $name  Argument name.
     * @param string $value Argument value.
     * 
     * @return nothing
     */
    public function add_header_argument($name, $value) 
    {
        $this->headers[ $name ] = $value;
    }

    /**
     * Make a authenticated request by adding
     *
     * @return void
     */
    protected function make_auth_request() 
    {

        $api_key = $this->get_auth_key();
        if (! empty($api_key)) {
            $this->add_header_argument('Authorization', 'Bearer ' . $api_key);
            $this->add_header_argument('Content-Type', 'application/json');
        }
    }

    /**
     * Make an API Request
     *
     * @param string $path   Path.
     * @param array  $data   Arguments array.
     * @param string $method Method.
     *
     * @return array|mixed|object
     */
    public function request($path, $data = array(), $method = 'post') 
    {
        $url = $this->get_api_path($path);

        $this->make_auth_request();

        $args = array(
            'headers' => $this->headers,
            'method'  => strtoupper($method),
            'timeout' => $this->timeout,
            'body'    => $data,
        );

        $response = wp_remote_post($url, $args);

        return $response;
    }


}
