<?php

/**
 * Fired during WP Legal Pages deactivation
 *
 * @link       http://wplegalpages.com/
 * @since      1.5.2
 *
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/includes
 */

/**
 * Fired during WP Legal Pages deactivation.
 *
 * This class defines all code necessary to run during the WP Legal Pages's deactivation.
 *
 * @since      1.5.2
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/includes
 * @author     WPEka <support@wplegalpages.com>
 */
if(!class_exists('WP_Legal_Pages_Deactivator')){
class WP_Legal_Pages_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.5.2
	 */
	public static function deactivate() {
            delete_option('lp_accept_terms');
	}

}
}