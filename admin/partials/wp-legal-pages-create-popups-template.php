<?php
/**
 * Provide a admin area view for create popups.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Wplegalpages_Pro
 * @subpackage Wplegalpages_Pro/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	?>

	<head>
		<meta name="viewport" content="width=device-width,initial-scale=0.75,maximum-scale=1.0,user-scalable=0">
		<meta name="viewport" content="width=device-width" /> </head>
	<?php
	global $wpdb;
	$lp_obj = new WP_Legal_Pages();

	// fetch the popup details
	$serialized_object = get_option( 'wplegalpalges_create_popup' );
	$unserialized_object = unserialize( $serialized_object );

	wp_enqueue_style( 'bootstrap-min' );
	wp_enqueue_style( 'style' );
	$lp_obj->wplegalpages_pro_enqueue_editor();
	$baseurl = esc_url( get_bloginfo( 'wpurl' ) );
	?>
		
		<div style="clear:both;"></div>
		<div class="wplegalpages-popup-app">
			
		<div class="wplegal-create-legal-all-pages">

			<div class="wplegal-feature-icon" id="wplegal-settings-create-legal">
				<img src="<?php echo esc_url( WPL_LITE_PLUGIN_URL . 'admin/js/vue/images/carbon_popup.svg' ); ?>" alt="create legal" class="wplegal-create-legal-icon"> <?php //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				<div class="wplegal-create-legal-subtext">
					<p class="wplegal-create-legal-page-subheading"><?php esc_attr_e( 'Create a new popup', 'wplegalpages' ); ?></p>
					<p class="wplegal-create-legal-page-content"><?php esc_attr_e( 'Add another popup to your library.', 'wplegalpages' ); ?></p>
				</div>
			</div>
			<div class="wplegal-create-legal-link">
				<button @click="popupVisible = true" class="wplegal-create-legal-page-button"><?php esc_attr_e( 'Create New Popup', 'wplegalpages' ); ?></button> 
			</div>
		</div>
		<div class="wrap wplegalpages-popup-section">
			<?php
			if ( isset( $_REQUEST['mode'] ) && 'delete' === $_REQUEST['mode'] && current_user_can( 'manage_options' ) ) {
				if ( isset( $_REQUEST['nonce'] ) ) {
					wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'lp-submit-create-popups' );
				}
				$lpid = isset( $_REQUEST['lpid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lpid'] ) ) : '';
				$wpdb->delete( $lp_obj->popuptable, array( 'id' => $lpid ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				
			}
			
			$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';

			$checked      = 'checked="checked"';
			$selected     = 'selected="selected"';
			?>
						<style type="text/css">
							.clear {
								clear: both;
							}

							#lp_generalid {
								width: 800px;
								float: left;
							}

							#lp_generalid_right {
								float: left;
								width: 280px;
								margin-left: 20px;
								margin-top: 20px;
								border-radius: 5px;
								-moz-border-radius: 5px;
								-webkit-border-radius: 5px;
								padding-bottom: 10px;
							}

							#lp_generalid p {
								font-size: 16px;
							}

							#lp_generalid input[type=text] {
								width: 800px;
								height: 30px;
								color: #666;
								font-size: 20px;
								padding: 3px 4px;
							}

							#lp_generalid input[type=submit] {
								width: 100px;
								height: 30px;
								color: #000;
								cursor: pointer;
								font-size: 20px;
								padding: 3px 4px;
							}

							#lp_generalid_right li {
								list-style: square;
								line-height: 20px;
								list-style-position: inside;
								padding-left: 10px;
							}

							#lp_generalid_right li span {
								font-size: 16px;
								cursor: pointer;
							}

							#lp_generalid p a {
								text-decoration: none;
							}

							#content p {
								width: 800px;
							}
						</style>
						<?php
								$result = $wpdb->get_results( 'select * from ' . $lp_obj->popuptable ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery

								if ( $result ) {
									?>
									
						<h2 class="popup-heading hndle create-popup myLabel-head wplegalpages-popup-section-title"> <?php esc_attr_e( 'Available Popups', 'wplegalpages' ); ?></h2>
						<div v-if="successMessage" id="wplegalpages-save-popup-settings-alert">
  		<img src="<?php echo esc_url( WPL_LITE_PLUGIN_URL . 'admin/js/vue/images/settings_saved.svg' ); ?>" alt="create legal" class="wplegal-save-settings-icon">{{ successMessage }}
</div>

						<table class="widefat table table-bordered table-striped create-popup wplegalpages-popup-list-table">
							<thead>
								<tr>
									<th width="5%"><?php esc_attr_e( 'S.No.', 'wplegalpages' ); ?></th>
									<th width="30%"><?php esc_attr_e( 'Template Title', 'wplegalpages' ); ?></th>
									<th width="20%"><?php esc_attr_e( 'Shortcode', 'wplegalpages' ); ?></th>
									<th width="15%"><?php esc_attr_e( 'Action', 'wplegalpages' ); ?></th>
								</tr>
							</thead>
							<tbody>
								
									<?php
										$count = 1;
										$class = '';
									foreach ( $result as $res ) {
										?>
										<tr<?php echo esc_attr( $class ); ?>>
											<td>
										<?php echo esc_attr( $count ); ?>
											</td>
											<td>
										<?php echo esc_attr( $res->popup_name ); ?>
											</td>
											<td>
										<?php echo '[wp-legalpopup pid=' . esc_attr( $res->id ) . ']'; ?>
											</td>
											</td>
											<td><div class="wplegalpages-popup-list-table-actions"><a href="#" @click.prevent="editPopup(<?php echo esc_attr($res->id); ?>)" class="wplegalpages-popup-list-table-action-link" href="<?php echo esc_url( $baseurl ); ?>/wp-admin/admin.php?page=legal-pages&lpid=<?php echo esc_attr( $res->id ); ?>&mode=edit"><div class="wplegalpages-popup-list-table-action wplegalpages-popup-list-table-action-edit"><img src="<?php echo esc_url( WPL_LITE_PLUGIN_URL . 'admin/js/vue/images/edit_popup.svg'); ?>" alt="edit popup"><span class="wplegalpages-popup-list-table-action-title"><?php esc_attr_e( 'Edit', 'wplegalpages' ); ?></span></div></a><a class="wplegalpages-popup-list-table-action-link" href="<?php echo esc_url( $baseurl ); ?>/wp-admin/admin.php?page=legal-pages&nonce=<?php echo esc_attr( wp_create_nonce( 'lp-submit-create-popups' ) ); ?>&lpid=<?php echo esc_attr( $res->id ); ?>&mode=deletepopup" onclick="return confirm('Popup will be permanently deleted. Are you sure you want to delete?')"><div class="wplegalpages-popup-list-table-action wplegalpages-popup-list-table-action-delete" ><img src="<?php echo esc_url( WPL_LITE_PLUGIN_URL . 'admin/js/vue/images/delete_popup.svg' ); ?>" alt="delete popup"/><span class="wplegalpages-popup-list-table-action-title"><?php esc_attr_e( 'Delete', 'wplegalpages' ); ?></span></div></a></div></td>
											</tr>
										<?php
										$count++;
									}
									?>
												
							</tbody>
						</table>
						<?php } else { ?>
									<div class="wplegalpages-list-tabs-container">
										<img src="<?php echo esc_url( WPL_LITE_PLUGIN_URL . 'admin/js/vue/images/carbon_popup.svg' ); ?>" alt="WPLegalPages Setting preview logo" class="wplegalpages-list-tabs-logo"> 
										<p class="wplegalpages-list-tabs-heading"><?php esc_attr_e( 'Create Popup', 'wplegalpages' ); ?></p> 
										<p class="wplegalpages-list-tabs-sub-heading"><?php esc_attr_e( 'Create and personalize a unique popup to suit your preferences.', 'wplegalpages' ); ?></p> 
										<!-- <input type="button" value="Create Popup" class="wplegalpages-list-tabs-popup-btn"> -->
										<button @click="popupVisible = true" class="wplegalpages-list-tabs-popup-btn"><?php esc_attr_e( 'Create New Popup', 'wplegalpages' ); ?></button> 

									</div>
							<?php } ?>
						<!-- <hr class="create-popup wplegalpages-popup-section-separator"> -->
						<v-modal v-model="popupVisible" title="Create New Popup">

						<div class="wplegalpages-popup-shortcode-section create-popup">
							<div class="wplegalpages-popup-shortcode-section-container"><p class="wplegalpages-popup-shortcode-section-desc"><?php esc_attr_e( 'Select Template', 'wplegalpages' ); ?></p>
								<?php
								$all_page_options = ['wplegal_terms_of_use_page', 
													'wplegal_terms_of_use_free_page', 
													'wplegal_fb_policy_page', 
													'wplegal_affiliate_agreement_page',
													'wplegal_affiliate_disclosure_page',
													'wplegal_amazon_affiliate_disclosure_page',
													'wplegal_testimonials_disclosure_page',
													'wplegal_advertising_disclosure_page',
													'wplegal_confidentiality_disclosure_page',
													'wplegal_earnings_disclaimer_page',
													'wplegal_medical_disclaimer_page',
													'wplegal_antispam_page',
													'wplegal_ftc_statement_page',
													'wplegal_double_dart_page',
													'wplegal_about_us_page',
													'wplegal_cpra_page',
													'wplegal_end_user_license_page',
													'wplegal_digital_goods_refund_policy_page',
													'wplegal_newsletters_page',
													'wplegal_general_disclaimer_page',
													'wplegal_standard_privacy_policy_page',
													'wplegal_ccpa_free_page',
													'wplegal_coppa_policy_page',
													'wplegal_terms_forced_policy_page',
													'wplegal_gdpr_cookie_policy_page',
													'wplegal_gdpr_privacy_policy_page',
													'wplegal_cookies_policy_page',
													'wplegal_blog_comments_policy_page',
													'wplegal_linking_policy_page',
													'wplegal_external_link_policy_page',
													'wplegal_dmca_page',
													'wplegal_california_privacy_policy_page',
													'wplegal_privacy_policy_page',
													'wplegal_returns_refunds_policy_page',
													'wplegal_impressum_page',
													'wplegal_custom_legal_page'];

								$post_id_arr = [];
								
								foreach ($all_page_options as $page_option) {
									$page_id = get_option( $page_option );
									if ( $page_id ) {
										$post_id_arr[] = $page_id;
									}
								}

								$id_list = implode( ',', $post_id_arr );

								$res = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title, post_content FROM {$wpdb->posts} WHERE ID IN ({$id_list}) AND post_type = %s AND post_status != %s", 'page', 'trash')); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared	
								
								?>
									<script type="text/javascript">
										function wplpfunc(selectObj) {
											var selectedTemplateId = selectObj.value;
											var textarea = document.getElementById('wplpcode');
											
											if (selectedTemplateId) {
												textarea.style.display = 'block';
												textarea.value = "[wp-legalpage tid=" + selectedTemplateId + "]";
											} else {
												textarea.style.display = 'none';
											}
										}
									</script>
									<form name="me" id="wplp-shortcode-select">
										<select v-model="formData.legalpage_id" class="selectpicker form-control" name="wplp" id="wplp" onChange="wplpfunc(this);" style="width:250px;">
											<option value=""><?php esc_attr_e( 'Select', 'wplegalpages' ); ?></option>
											<?php

											foreach ( $res as $ras ) {
												?>
												<option value="<?php echo esc_attr( $ras->ID ); ?>">
													<?php echo esc_attr( $ras->post_title ); ?>
												</option>
												<?php
											}
											?>
										</select>
									</form>
									<label for="wplpcode" class="screen-reader-text"><?php esc_attr_e( 'Generated Legal Page Code','wplegalpages'); ?></label>
									<textarea id="wplpcode" onclick="document.getElementById('wplpcode').focus();document.getElementById('wplpcode').select();" readonly="readonly" style="width:250px;display:none;"></textarea>
									<div style="clear:both;"></div>
							</div>
							
						</div>
						<div class="wplegalpages-create-popup-section create-popup">
							<?php $row = ''; ?>
								<?php
								if ( get_option( 'wplegalpalges_flag_key' ) ) {
									$lpid = isset( $_REQUEST['lpid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lpid'] ) ) : 0;
									$row  = $unserialized_object; // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
								}
								?>
									<p class="wplegalpages-create-popup-section-title"><?php esc_attr_e( 'Name your Popup', 'wplegalpages' ); ?></p>
									<p class="wplegalpages-create-popup-section-desc"><b><?php esc_attr_e( 'Note:', 'wplegalpages' ); ?> </b><?php esc_attr_e( 'You can use Available Template shortcodes inside the popup to display Legal Pages on Popup. Checkbox to agree for the legal pages is added at the end of every popup forcing the user to agree the legal contents to view the page.', 'wplegalpages' ); ?></p>
									<div id="lp_generalid">
										<form @submit.prevent="savePopupData" id="popup_form" name="popup" method="post" enctype="multipart/form-data">
											<p>
												<label for="lp-name" class="screen-reader-text"><?php esc_attr_e( 'Name','wplegalpages'); ?></label>
												<input v-model="formData.title" type="text" class="form-control myText" name="lp-name" id="lp-name"
														<?php if ( ! empty( $row ) ) { ?>
															value="<?php echo esc_attr( $row->popup_name ); ?>"
														<?php } else { ?>
															value=""
														<?php } ?>
														/> 
											</p>
											<p>
												<div id="poststuff">
													<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>">
														<?php wp_editor( $row ? $row->content : '', 'content' ); ?>
													</div>
													<label for="lp-content" class="screen-reader-text"><?php esc_attr_e( 'Generated Legal Page Content','wplegalpages'); ?></label>
													<textarea id="lp-content" name="lp-content" value="5" style="display:none" rows="10"></textarea>
												</div>
											</p>
											<p>
												<?php
												if ( function_exists( 'wp_nonce_field' ) ) {
													wp_nonce_field( 'lp-submit-create-popups' );
												}
												?>
												<!-- <input type="submit" class="btn btn-primary mybtn" onclick="sp_content_save();" name="lp-submit" value="<?php esc_attr_e( 'Save', 'wplegalpages' );?>"/>  -->
											<button type="button" class="btn btn-primary mybtn"  @click="savePopupData">
  												<?php esc_attr_e( 'Save', 'wplegalpages' ); ?>
											</button>
											</p>
										</form>
									</div>
									<div class="clear"></div>
						</div>
						</v-modal>
</div>
						<?php 
