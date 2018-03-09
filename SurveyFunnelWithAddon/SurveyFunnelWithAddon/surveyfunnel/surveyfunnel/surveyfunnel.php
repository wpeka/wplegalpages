<?php

/**
 * @link              http://www.surveyfunnel.com
 * @since             1.0.0
 * @package           surveyfunnel
 *
 * @wordpress-plugin
 * Plugin Name:       Survey Funnel
 * Plugin URI:        http://www.surveyfunnel.com
 * Description:       Add dynamic survey funnels to your Wordpress site
 * Version:           6.4.8
 * Author:            WPeka
 * Author URI:        https://club.wpeka.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_survey_funnel() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-survey-funnel-activator.php';
	Survey_Funnel_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_survey_funnel() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-survey-funnel-deactivator.php';
	Survey_Funnel_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_survey_funnel' );
register_deactivation_hook( __FILE__, 'deactivate_survey_funnel' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-survey-funnel.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_survey_funnel() {

	$plugin = new surveyFunnel();
	$plugin->instance();
	//surveyFunnel::instance();

}

run_survey_funnel();
