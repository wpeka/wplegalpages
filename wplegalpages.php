<?php
/**
 * Plugin Name: WPLegalPages
 * Plugin URI: https://club.wpeka.com/
 * Description: WPLegalPages is a simple 1 click legal page management plugin. You can quickly add in legal pages to your WordPress sites.
 * Author: WPEka Club
 * Version: 3.0.0
 * Author URI: https://club.wpeka.com
 * License: GPL2
 * Text Domain: wplegalpages
 * Domain Path: /languages
 *
 * @category  X
 * @package   WP_Legal_Pages
 * @author    Display Name <username@example.com>
 * @copyright 2019    CyberChimps, Inc.
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * @link      https://club.wpeka.com/
 * @since     1.0.0
 */

// If this file is called directly, abort.
if (! defined('ABSPATH')) {
    die;
}

if (! defined('WPL_LITE_PLUGIN_URL')) {
    define('WPL_LITE_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (! defined('WPL_LITE_PLUGIN_BASENAME')) {
    define('WPL_LITE_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (! defined('WPLEGAL_API_URL')) {
    define('WPLEGAL_API_URL', 'https://api.wpeka.com/wp-json/wplegal/v2/');
}
/**
 * Check if the constant GDPR_APP_URL is not already defined.
*/
if (! defined('WPLEGAL_APP_URL')) {
    define('WPLEGAL_APP_URL', 'https://app.wplegalpages.com');
}

if (! function_exists('wplp_fs')) {
    /**
     * Helper function to access SDK.
     *
     * @return Analytics
     */
    function wplp_fs() 
    {
        global $wplp_fs;

        if (! isset($wplp_fs)) {
            // Include Analytics SDK.
            include_once dirname(__FILE__) . '/analytics/start.php';

            $wplp_fs = ras_dynamic_init(
                array(
                    'id'              => '4',
                    'slug'            => 'wplegalpages',
                    'product_name'    => 'WPLegalPages',
                    'module_type'     => 'plugin',
                    'version'         => '2.9.5',
                    'plugin_basename' => 'wplegalpages/wplegalpages.php',
                    'plugin_url'      => WPL_LITE_PLUGIN_URL,)
            );
        }

        return $wplp_fs;
    }

    // Init Analytics.
    // wplp_fs();
    // SDK initiated.
    // do_action('wplp_fs_loaded');
}

if (! defined('WPLPP_SUFFIX')) {
    define('WPLPP_SUFFIX', (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min');
}

if (! function_exists('activate_wp_legal_pages')) {
    /**
     * The code that runs during WPLegalPages activation.
     * This action is documented in includes/class-wp-legal-pages-activator.php
     * 
     * @return nothing
     */
    function activate_wp_legal_pages() 
    {
        include_once plugin_dir_path(__FILE__) . 'includes/class-wp-legal-pages-activator.php';
        WP_Legal_Pages_Activator::activate();
        add_option('analytics_activation_redirect_wplegalpages', true);
    }
}

/**
 * The code that runs during WPLegalPages deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
if (! function_exists('deactivate_wp_legal_pages')) {
    /**
     * The code that runs during WPLegalPages deactivation.
     * This action is documented in includes/class-plugin-name-deactivator.php
     * 
     * @return nothing
     */
    function deactivate_wp_legal_pages() 
    {
        include_once plugin_dir_path(__FILE__) . 'includes/class-wp-legal-pages-deactivator.php';
        WP_Legal_Pages_Deactivator::deactivate();
    }
}
if (! function_exists('delete_wp_legal_pages')) {
    /**
     * The code that runs during WPLegalPages delete.
     * This action is documented in includes/class-plugin-name-delete.php
     * 
     * @return nothing
     */
    function delete_wp_legal_pages() 
    {
        include_once plugin_dir_path(__FILE__) . 'includes/class-wp-legal-pages-delete.php';
        WP_Legal_Pages_Delete::delete();
    }
}
register_activation_hook(__FILE__, 'activate_wp_legal_pages');
register_deactivation_hook(__FILE__, 'deactivate_wp_legal_pages');
register_uninstall_hook(__FILE__, 'delete_wp_legal_pages');




/**
 * The core WPLegalPages class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-legal-pages.php';

/**
 * Begins execution of the WPLegalPages.
 *
 * Since everything within the WPLegalPages is registered via hooks,
 * then kicking off the WPLegalPages from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 * 
 * @return nothing
 */
function run_wp_legal_pages() 
{
    $legal_pages = new WP_Legal_Pages();
    $legal_pages->run();
}
run_wp_legal_pages();
