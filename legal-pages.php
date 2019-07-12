<?php
/**
*Plugin Name: WP Legal Pages
*Plugin URI: http://wplegalpages.com
*Description: WP Legal Pages is a simple 1 click legal page management plugin. You can quickly add in legal pages to your wordpress sites.
*Author: WPEka Club
*Version: 2.2.4
*Author URI: http://wplegalpages.com/
*License: GPL2
*Text Domain: WP Legal Pages
*Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'WPL_LITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during WP Legal Pages activation.
 * This action is documented in includes/class-wp-legal-pages-activator.php
 */
if (!function_exists('activate_wp_legal_pages')) {
	function activate_wp_legal_pages() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-legal-pages-activator.php';
		WP_Legal_Pages_Activator::activate();
	}
}

/**
 * The code that runs during WP Legal Pages deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
if(!function_exists('deactivate_wp_legal_pages')){
function deactivate_wp_legal_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-legal-pages-deactivator.php';
	WP_Legal_Pages_Deactivator::deactivate();
}
}
if(!function_exists('delete_wp_legal_pages')){
function delete_wp_legal_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-legal-pages-delete.php';
	WP_Legal_Pages_Delete::delete();
}
}
register_activation_hook( __FILE__, 'activate_wp_legal_pages' );
register_deactivation_hook( __FILE__, 'deactivate_wp_legal_pages' );
register_uninstall_hook(__FILE__, 'delete_wp_legal_pages');




/**
 * The core WP Legal Pages class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-legal-pages.php';
require plugin_dir_path( __FILE__ ) . 'includes/pointer.php';
/**
 * Begins execution of the WP Legal Pages.
 *
 * Since everything within the WP Legal Pages is registered via hooks,
 * then kicking off the WP Legal Pages from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

if(!function_exists('run_wp_legal_pages')){
function run_wp_legal_pages() {
	$legal_pages = new WP_Legal_Pages();
	$legal_pages->run();

}
}
run_wp_legal_pages();
