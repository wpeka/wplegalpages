<?php

/**
 * Fired during WP Legal Pages activation
 *
 * @link       http://wplegalpages.com/
 * @since      1.5.2
 *
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/includes
 */

/**
 * Fired during WP Legal Pages activation.
 *
 * This class defines all code necessary to run during the WP Legal Pages's activation.
 *
 * @since      1.5.2
 * @package    WP_Legal_Pages
 * @subpackage WP_Legal_Pages/includes
 * @author     WPEka <support@wplegalpages.com>
 */
if (!class_exists('WP_Legal_Pages_Activator')){
class WP_Legal_Pages_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.5.2
	 */
	public static function activate() {
            global $wpdb;
            $legal_pages = new WP_Legal_Pages();
            $privacy = file_get_contents(plugin_dir_path( dirname( __FILE__ ) ) . 'templates/privacy.html');
            $dmca = file_get_contents(plugin_dir_path( dirname( __FILE__ ) ). 'templates/dmca.html');


            add_option('lp_excludePage','true');
            add_option('lp_general', '');
            add_option('lp_accept_terms','0');
            add_option('lp_eu_cookie_title','A note to our visitors');
						$message_body="This website has updated its privacy policy in compliance with changes to European Union data protection law, for all members globally. We’ve also updated our Privacy Policy to give you more information about your rights and responsibilities with respect to your privacy and personal information. Please read this to review the updates about which cookies we use and what information we collect on our site. By continuing to use this site, you are agreeing to our updated privacy policy.";
             add_option('lp_eu_cookie_message', htmlentities($message_body));
             add_option('lp_eu_cookie_enable', 'OFF');
             add_option('lp_eu_box_color', '#000000');

            add_option('lp_eu_cookie_message',htmlentities($message_body));
            add_option('lp_eu_cookie_enable','OFF');
            add_option('lp_eu_box_color', '#000000');
 	        add_option('lp_eu_button_color', '#e3e3e3');
            add_option('lp_eu_button_text_color','#333333');
            add_option('lp_eu_text_color', '#FFFFFF');
            add_option('lp_eu_link_color', '#8f0410');
 	      add_option('lp_eu_text_size', '12');

            $sql = "CREATE TABLE IF NOT EXISTS `$legal_pages->tablename` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `title` text NOT NULL,
                              `content` longtext NOT NULL,
                              `notes` text NOT NULL,
                              `contentfor` varchar(200) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM;";
            $sqlpopup = "CREATE TABLE IF NOT EXISTS `$legal_pages->popuptable` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `popupName` text NOT NULL,
                              `content` longtext NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM;";

            $wpdb->query($sql);
            $wpdb->query($sqlpopup);
            $privacy_policy_count = $wpdb->get_var( "SELECT COUNT(*) FROM `$legal_pages->tablename` WHERE title='Privacy Policy'" );
            if($privacy_policy_count==0){
                    $wpdb->insert($legal_pages->tablename,array('title'=>'Privacy Policy','content'=>$privacy,'contentfor'=>'1a2b3c4d5e6f7g8h9i'),array('%s','%s','%s'));
            }
            $dmca_count = $wpdb->get_var( "SELECT COUNT(*) FROM `$legal_pages->tablename` WHERE title='DMCA'" );
            if($dmca_count==0){
                    $wpdb->insert($legal_pages->tablename,array('title'=>'DMCA','content'=>$dmca,'contentfor'=>'10j'),array('%s','%s','%s'));
            }

	}
	}


}
