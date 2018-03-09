<?php

/**
 * Fired during plugin activation
 *
 * @link              http://www.surveyfunnel.com
 * @since      1.0.0
 *
 * @package           surveyfunnel
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package           surveyfunnel
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Survey_Funnel_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		include(dirname(__FILE__)."/classes/all_classes.php");

		$DBTables = new DBTables();
		$DBTables->initDBTables();

	}

}
