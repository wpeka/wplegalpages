<?php
/**
 * Provide a admin area view for the settings.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Wplegalpages
 * @subpackage Wplegalpages/admin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$baseurl = '';
if ( isset( $_SERVER['PHP_SELF'] ) ) {
	$baseurl = esc_url_raw( wp_unslash( $_SERVER['PHP_SELF'] ) );
}

if ( isset( $_POST['lp_submit'] ) && 'Accept' === $_POST['lp_submit'] ) {
	check_admin_referer( 'lp-submit-accept-terms' );
	update_option( 'lp_accept_terms', isset( $_POST['lp_accept_terms'] ) ? sanitize_text_field( wp_unslash( $_POST['lp_accept_terms'] ) ) : '' );
}

wp_enqueue_script( 'jquery' );
$lp_pro_active     = get_option( '_lp_pro_active' );
$lpterms           = get_option( 'lp_accept_terms' );
$lp_pro_installed  = get_option( '_lp_pro_installed' );
$lp_general        = get_option( 'lp_general' );
$lp_footer_options = get_option( 'lp_footer_options' );

if ( false === $lp_footer_options || empty( $lp_footer_options ) ) {
	$lp_footer_options = array(
		'footer_legal_pages' => '',
		'show_footer'        => '0',
		'footer_bg_color'    => '#ffffff',
		'footer_text_align'  => 'center',
		'footer_separator'   => '',
		'footer_new_tab'     => '0',
		'footer_text_color'  => '#333333',
		'footer_link_color'  => '#333333',
		'footer_font'        => 'Open Sans',
		'footer_font_id'     => 'Open+Sans',
		'footer_font_size'   => '16',
		'footer_custom_css'  => '',
	);
	update_option( 'lp_footer_options', $lp_footer_options );
}
?>
<div class="wplegalpages-app-container" id="wplegalpages-settings-app">
	<c-container class="wplegalpages-settings-container">
		<div class="wplegalpages-marketing-banner">
		<?php
		if ( '1' !== $lp_pro_active ) :
			?>
			<div style="">
				<div style="line-height: 2.4em;" class='wplegalpages-pro-promotion'>
					<a href="https://club.wpeka.com/product/wplegalpages/?utm_source=plugin-banner&utm_campaign=wplegalpages&utm_content=upgrade-to-pro" target="_blank">
						<img alt="Upgrade to Pro" src="<?php echo esc_attr( WPL_LITE_PLUGIN_URL ) . 'admin/images/upgrade-to-pro.jpg'; ?>">
					</a>
				</div>
			</div>
			<div style="clear:both;"></div>
			<?php
		endif;
		?>
		</div>
		<c-form id="lp-save-settings-form" spellcheck="false" class="wplegalpages-settings-form">
			<input type="hidden" name="settings_form_nonce" value="<?php echo wp_create_nonce( 'settings-form-nonce' ); ?>"/>
			<div class="wplegalpages-settings-top">
				<div class="wplegalpages-save-button">
					<c-button color="info"><span>Save Changes</span></c-button>
				</div>
			</div>
			<div class="wplegalpages-settings-content">
				<div id="wplegalpages-save-settings-alert">Settings saved</div>	
				<c-tabs variant="pills" ref="active_tab" class="wplegalpages-settings-nav">
					<c-tab title="<?php esc_attr_e( 'General', 'wplegalpages' ); ?>" href="#general">
					<?php do_action( 'wp_legalpages_notice' ); ?>
						<c-card>
							<c-card-body>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Domain Name', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Enter your website URL', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Domain] as shortcode" type="text" name="lp-domain-name" value="<?php echo ! empty( $lp_general['domain'] ) ? esc_attr( $lp_general['domain'] ) : esc_url_raw( get_bloginfo( 'url' ) ); ?>"></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Business Name', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Enter your Legal business name', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Business Name] as shortcode" type="text" name="lp-business-name" value="<?php echo ! empty( $lp_general['business'] ) ? esc_attr( $lp_general['business'] ) : ''; ?>"></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Phone', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Certain policies like CCPA require your contact details', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Phone] as shortcode" type="text"  name="lp-phone" value="<?php echo ! empty( $lp_general['phone'] ) ? esc_attr( $lp_general['phone'] ) : ''; ?>"></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Street', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Certain policies like CCPA require your contact details', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Street] as shortcode" type="text" name="lp-street" value="<?php echo ! empty( $lp_general['street'] ) ? esc_attr( $lp_general['street'] ) : ''; ?>"></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'City, State, Zip code', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Certain policies like CCPA require your contact details', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [City, State, Zip code] as shortcode" type="text"  name="lp-city-state" value="<?php echo ! empty( $lp_general['cityState'] ) ? esc_attr( $lp_general['cityState'] ) : ''; ?>"></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Country', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Certain policies like CCPA require your contact details', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Country] as shortcode" type="text" name="lp-country" value="<?php echo ! empty( $lp_general['country'] ) ? esc_attr( $lp_general['country'] ) : ''; ?>"></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Email', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Certain policies like CCPA require your contact details', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Email] as shortcode" type="text"  name="lp-email" value="<?php echo ! empty( $lp_general['email'] ) ? esc_attr( $lp_general['email'] ) : esc_attr( get_option( 'admin_email' ) ); ?>" ></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Address', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Certain policies like CCPA require your contact details', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Address] as shortcode" type="text" name="lp-address" value="<?php echo ! empty( $lp_general['address'] ) ? esc_attr( $lp_general['address'] ) : ''; ?>"></c-input></c-col>
								</c-row>
								<c-row>
									<c-col class="col-sm-4"><label><?php esc_attr_e( 'Niche', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( ' Fill the general niche of your business', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
									<c-col class="col-sm-8"><c-input description="use [Niche] as shortcode" type="text" name="lp-niche" value="<?php echo ! empty( $lp_general['niche'] ) ? esc_attr( $lp_general['niche'] ) : ''; ?>"></c-input></c-col>
								</c-row>
								<?php do_action( 'wplegalpages_admin_settings', $lp_general ); ?>
							</c-card-body>
						</c-card>
					</c-tab>
					<?php do_action( 'wp_legalpages_after_general_tab' ); ?>
					<?php do_action( 'wp_legalpages_after_data_tab' ); ?>
					<c-tab title="<?php esc_attr_e( 'Compliances', 'wplegalpages' ); ?>" href="#compliances">
						<div class="wplegalpages-additonal-features-tab">
							<c-card>
								<c-card-header><?php esc_html_e( 'Add Legal Pages Link to the Footer', 'wplegalpages' ); ?></c-card-header>
								<c-card-body>
									<div class="wplegalpages-additional-features-descripiton">
										<p class="wplegalpages-additonal-features-card-description"><?php esc_html_e( 'Display links to your legal pages in the footer section of your website.', 'wplegalpages' ); ?></p>
									</div>
									<div class="wplegalpages-additional-features-buttons">
										<c-button color="info" @click="onClickFooter">
											<span v-show="is_footer"><?php esc_attr_e( 'Disable' ); ?></span>
											<span v-show="!is_footer"><?php esc_attr_e( 'Enable' ); ?></span>
										</c-button>
										<c-button color="secondary" @click="showFooterForm">
											<span><?php esc_attr_e( 'Configure' ); ?></span>
										</c-button>
									</div>
									<input type="hidden" name="lp-footer" ref="footer" v-model="is_footer">
								</c-card-body>
							</c-card>
							<c-card>
								<c-card-header><?php esc_html_e( 'Announcement Banner for Legal Pages', 'wplegalpages' ); ?></c-card-header>
								<c-card-body>
									<div class="wplegalpages-additional-features-descripiton">
										<p class="wplegalpages-additonal-features-card-description"><?php esc_html_e( 'Display announcement banners on your website whenever any legal pages have been updated.', 'wplegalpages' ); ?></p>
									</div>
									<div class="wplegalpages-additional-features-buttons">
										<c-button color="info" @click="onClickBanner">
											<span v-show="is_banner"><?php esc_attr_e( 'Disable' ); ?></span>
											<span v-show="!is_banner"><?php esc_attr_e( 'Enable' ); ?></span>
										</c-button>
										<c-button color="secondary">
											<span><?php esc_attr_e( 'Configure' ); ?></span>
										</c-button>
									</div>
									<input type="hidden" name="lp-banner" ref="banner" v-model="is_banner">
								</c-card-body>
							</c-card>
						</div>
						<div id="wplegalpages-form-modal-footer-form">
							<c-card class="wplegalpages-form-modal-dialog">
								<c-card-header class="wplegalpages-form-header">
									<span><?php esc_html_e( 'Add Legal Pages Link to the Footer', 'wplegalpages' ); ?></span>
									<span @click="showFooterForm">X</span>
								</c-card-header>
								<c-card-body>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Enabled', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Add Privacy Policy links to the footer.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<c-switch v-bind="labelIcon" ref="switch_footer" v-model="is_footer" id="wplegalpages-show-footer" variant="3d" color="success" :checked="is_footer" v-on:update:checked="onSwitchFooter"></c-switch>
											<input type="hidden" name="lp-is-footer" v-model="is_footer">
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Legal Pages', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the Legal Pages you want to add to the footer.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<input type="hidden" ref="footer_legal_pages" v-model="footer_legal_pages" name="footer_legal_pages">
											<input type="hidden" ref="footer_legal_pages_mount" value="<?php echo esc_html( stripslashes( $lp_footer_options['footer_legal_pages'] ) ); ?>">
											<v-select id="wplegalpages-footer-pages" class="form-group" :options="page_options" multiple v-model="footer_legal_pages"  >
											</v-select>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Background Color', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the background color for the footer section', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<!-- <colorpicker id="wplegalpages-footer-bgcolor" :color="link_bg_color" v-model="link_bg_color" /> -->
											<c-input id="wplegalpages-lp-form-bg-color" type="color" name="lp-footer-bg-color" value="<?php echo esc_html( $lp_footer_options['footer_bg_color'] ); ?>"></c-input>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Font', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the font.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<!-- <font-picker id="wplegalpages-footer-font" :api-key="apiKey"  :active-font="footer_font" @change="onFooterFont"></font-picker> -->
											<input type="hidden" ref="footer_font_family" v-model="footer_font" name="lp-footer-font-family">
											<input type="hidden" ref="footer_font_family_mount" value="<?php echo esc_html( stripslashes( $lp_footer_options['footer_font'] ) ); ?>">
											<v-select class="form-group" id="wplegalpages-footer-font" :options="font_options" v-model="footer_font">
											</v-select>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Font Size', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the Font size for the footer section.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<input type="hidden" ref="footer_font_size" v-model="footer_font_size" name="lp-footer-font-size">
											<input type="hidden" ref="footer_font_size_mount" value="<?php echo esc_html( stripslashes( $lp_footer_options['footer_font_size'] ) ); ?>">
											<v-select class="form-group" id="wplegalpages-footer-font-size" :options="font_size_options" v-model="footer_font_size">
											</v-select>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Text Color', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the color for the text.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<c-input id="wplegalpages-lp-form-text-color" type="color" name="lp-footer-text-color" value="<?php echo esc_html( $lp_footer_options['footer_text_color'] ); ?>"></c-input>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Text Alignment', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the text alignment.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<input type="hidden" ref="footer_text_align" v-model="footer_text_align" name="lp-footer-text-align">
											<input type="hidden" ref="footer_text_align_mount" value="<?php echo esc_html( stripslashes( $lp_footer_options['footer_text_align'] ) ); ?>">
											<v-select class="form-group" id="wplegalpages-footer-align" :options="footer_align_options" v-model="footer_text_align">
											</v-select>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Link Color', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select the color for links in the footer.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<!-- <colorpicker class="wplegalpages-input-color-picker" id="wplegalpages-footer-link-color" :color="footer_link_color" v-model="footer_link_color"></colorpicker> -->
											<c-input id="wplegalpages-lp-form-link-color" type="color" name="lp-footer-link-color" value="<?php echo esc_html( $lp_footer_options['footer_link_color'] ); ?>"></c-input>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Links Separator', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Select link separator element.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<c-input id="wplegalpages-lp-form-separator" type="text" name="lp-footer-separator" value="<?php echo esc_html( $lp_footer_options['footer_separator'] ); ?>"></c-input>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Open Link in New Tab', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Enable if you want to open links in the New Tab.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<c-switch v-bind="labelIcon" ref="footer_new_tab" v-model="footer_new_tab" id="wplegalpages-footer-new-tab" variant="3d" color="success" :checked="footer_new_tab" v-on:update:checked="onClickNewTab"></c-switch>
											<input type="hidden" name="lp-footer-new-tab" v-model="footer_new_tab">
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Additional CSS', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'You can add CSS to change the style of the footer.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<vue-editor id="wplegalpages-lp-footer-custom-css" :editor-toolbar="customToolbarForm" v-model="footer_custom_css"></vue-editor>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"></c-col>
										<c-col class="col-sm-8">
											<p class="wplegalpages-custom-css-heading">Available CSS Selectors</p>
											<p class="wplegalpages-custom-css-selector">Container ID's: <span class="wplegalpages-custom-css-links" @click="addContainerID">#wplegalpages_footer_links_container</span></p>
											<p class="wplegalpages-custom-css-selector">Links class: <span class="wplegalpages-custom-css-links" @click="addLinksClass">.wplegalpages_footer_link</span></p>
											<p class="wplegalpages-custom-css-selector">Text class: <span class="wplegalpages-custom-css-links" @click="addTextClass">.wplegalpages_footer_separator_text</span></p>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><label><?php esc_attr_e( 'Links Order', 'wplegalpages' ); ?><c-icon name="cib-google-keep" color="primary" v-c-tooltip="'<?php esc_html_e( 'Drag to reorder the links.', 'wplegalpages' ); ?>'"></c-icon></label></c-col>
										<c-col class="col-sm-8">
											<draggable id="wplegalpages-footer-order-links" v-model="footer_legal_pages">
												<div class="wplegalpages-draggable-item" v-for="footer_page in footer_legal_pages" :key="footer_page">{{footer_page}}</div>
											</draggable>
										</c-col>
									</c-row>
									<c-row>
										<c-col class="col-sm-4"><input type="hidden" id="wplegalpages-footer-form-nonce" name="lp-footer-form-nonce" value="<?php echo wp_create_nonce( 'settings_footer_form_nonce' ); ?>"/></c-col>
										<c-col class="col-sm-8">
											<c-button id="wplegalpages-footer-form-submit" color="info"><span>Save</span></c-button>
											<c-button color="secondary" @click="showFooterForm"><span>Cancel</span></c-button>
										</c-col>
									</c-row>
								</c-card-body>
							</c-card>
						</div>
					</c-tab>
				</c-tabs>
			</div>
			<div class="wplegalpages-settings-bottom">
				<div class="wplegalpages-save-button">
					<c-button color="info"><span>Save Changes</span></c-button>
				</div>
			</div>
		</c-form>
	</c-container>
</div>
