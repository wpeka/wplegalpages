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
if(!class_exists('WP_Legal_Pages_Delete')){
class WP_Legal_Pages_Delete {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.5.2
	 */
	public static function delete() {
		global $wpdb;
		$legal_pages = new WP_Legal_Pages();
            delete_option('lp_accept_terms');
            delete_option('lp_excludePage');
            delete_option('lp_general');
            delete_option('lp_accept_terms');
            delete_option('lp_eu_cookie_title');
            delete_option('lp_eu_cookie_message');
            delete_option('lp_eu_cookie_enable');
            delete_option('lp_eu_box_color');
            delete_option('lp_eu_button_color');
            delete_option('lp_eu_button_text_color');
            delete_option('lp_eu_text_color');
	$sql = "DROP TABLE $legal_pages->tablename";
	$sql_popup = "DROP TABLE $legal_pages->popuptable";
	$wpdb->query($sql);
	$wpdb->query($sql_popup);

	}

}
}
