<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wplegalpages.com/
 * @since 1.0.0
 *
 * @package    Wplegalpages
 */

?>
<div id="testing" style="z-index:999; background-color:white; width:100vw; height:100vh; display:block;position:fixed;top:0px;bottom:0px;"></div>
<div style="clear:both;"></div>
<div class="wrap">
	<div class="wplegalpages_settings_left">
		<div class="wplegalpages-tab-container">
			<form method="post" id="lp_ajax_settings_form"action="
			<?php
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				echo esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ); }
			?>
			" id="wplegalpages_settings_form">	
				<?php
				$wplegalpages_pro_version = get_option( 'wplegalpages_pro_version' ) ? get_option( 'wplegalpages_pro_version' ) : '';
				$localized_data           = array(
					'wplegalpages_pro_version' => $wplegalpages_pro_version,
				);
				wp_localize_script( $this->plugin_name . '-main', 'localized_data', $localized_data );
				wp_enqueue_script( $this->plugin_name . '-main' );
				require_once plugin_dir_path( __FILE__ ) . '/admin-settings.php';
				?>
				<input type="hidden" name="settings_form_nonce" value="<?php echo wp_create_nonce( 'settings-form-nonce' ); ?>"/>
			</form>
            <input type="hidden" id="lp_admin_ajax_url" value="<?php echo admin_url( 'admin-ajax.php' );//phpcs:ignore ?>">	
			<button type="submit" form="lp_ajax_settings_form" id="setting_submit"name="lp-gsubmit" class="btn btn-primary" value="<?php esc_attr_e( 'Save', 'wplegalpages' ); ?>" >Save Changes</button>         
		</div>
	</div>
</div>


