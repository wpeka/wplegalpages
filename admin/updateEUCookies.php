<?php

if ( ! defined( 'ABSPATH' ) ) exit;
require_once( 'class-wp-legal-pages-admin.php' );

global $wpdb;
$lpObj = new WP_Legal_Pages();
if(!empty($_POST) && isset($_POST['lp-submit'])){
	if(isset($_POST['lp_eu_status']))
	update_option('lp_eu_cookie_enable',sanitize_text_field($_POST['lp_eu_status']));
	else
	update_option('lp_eu_cookie_enable','off');

	if(isset($_POST['lp_eu_theme_css']))
		update_option('lp_eu_theme_css',sanitize_text_field($_POST['lp_eu_theme_css']));
	else
		update_option('lp_eu_theme_css','0');

	update_option('lp_eu_cookie_title',addslashes(sanitize_text_field($_POST['lp_eu_title'])));
	update_option('lp_eu_cookie_message',htmlentities(sanitize_text_field($_POST['lp_eu_message'])));
	update_option('lp_eu_box_color',sanitize_text_field($_POST['lp_eu_box_color']));
	update_option('lp_eu_button_color',sanitize_text_field($_POST['lp_eu_button_color']));
	update_option('lp_eu_button_text_color',sanitize_text_field($_POST['lp_eu_button_text_color']));
	update_option('lp_eu_text_color',sanitize_text_field($_POST['lp_eu_text_color']));

	update_option('lp_eu_button_text',sanitize_text_field($_POST['lp_eu_button_text']));
	update_option('lp_eu_link_text',sanitize_text_field($_POST['lp_eu_link_text']));
	update_option('lp_eu_link_url',sanitize_text_field($_POST['lp_eu_link_url']));
	update_option('lp_eu_text_size',sanitize_text_field($_POST['lp_eu_text_size']));
	update_option('lp_eu_link_color',sanitize_text_field($_POST['lp_eu_link_color']));
}

$lp_eu_theme_css 		= get_option('lp_eu_theme_css');
$lp_eu_get_visibility   = get_option('lp_eu_cookie_enable');
$lp_eu_title			= get_option('lp_eu_cookie_title');
$lp_eu_message			= get_option('lp_eu_cookie_message');
$lp_eu_box_color		= get_option('lp_eu_box_color');
$lp_eu_button_color		= get_option('lp_eu_button_color');
$lp_eu_button_text_color= get_option('lp_eu_button_text_color');
$lp_eu_text_color		= get_option('lp_eu_text_color');


$lp_eu_button_text=get_option('lp_eu_button_text');
$lp_eu_link_text=get_option('lp_eu_link_text');
$lp_eu_link_url=get_option('lp_eu_link_url');
$lp_eu_text_size=get_option('lp_eu_text_size');
$lp_eu_link_color=get_option('lp_eu_link_color');

wp_enqueue_script( 'jquery-minicolor',  WPL_LITE_PLUGIN_URL. 'admin/js/jquery.miniColors.min.js', array('jquery') );
wp_enqueue_style('jquery-miniColor',WPL_LITE_PLUGIN_URL. 'admin/css/minicolor/jquery.miniColors.css');

//Form validation library file
wp_enqueue_script('jquery-validation-plugin', WPL_LITE_PLUGIN_URL.'admin/js/jquery.validate.min.js', array('jquery'));

?>

    <div class="wrap">
        <div class='wplegalpages-addon-promotion'>
            <a href="https://club.wpeka.com/product/wp-gdpr-cookie-consent/" target="_blank">
                <img alt="WPLegalPages Cookie Consent Addon" src="<?php echo WPL_LITE_PLUGIN_URL.'admin/images/wplegalpages-addon-promotion.jpg'; ?>">
            </a>
        </div>
        <h2 class="title-head">  <?php _e('Cookie Bar','wplegalpages');?> : </h2>
        <div style="clear:both;"></div>
        <div class="postbox all_pad">
            <form id="lp_eu_cookies_form" enctype="multipart/form-data" method="post" action="" name="terms">
                <div class="row">
                    <div class="col-md-9">
                        <label class="field_title"> <?php _e('Cookie Bar','wplegalpages');?> : </label>
                        <label class="switch">
                            <input type="checkbox" <?php if($lp_eu_get_visibility=='ON' ) echo 'checked'; ?> name="lp_eu_status" value="ON" >
                            <div class="slider round"></div>
                        </label>
                        <p class="top_pad">
                            <label class="field_title"> <?php _e('Cookie Title','wplegalpages');?> : </label>
                            <input type="text" value="<?php echo stripslashes($lp_eu_title); ?>" style="width:50%;" id="lp-title" name="lp_eu_title"> </p>
                        <div class="row">
                            <label class="field_title left_pad"> <?php _e('Cookie Message Body','wplegalpages');?> : </label>
                            <div id="poststuff">
                                <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>">
                                    <?php wp_editor(stripslashes(html_entity_decode($lp_eu_message)),'content'); ?>
                                </div>
                                <script type="text/javascript">
                                    jQuery(document).ready(function () {
                                        jQuery(".lp_eu_colors").miniColors({
                                            change: function (hex, rgb) {}
                                        });

	                                    // Form validation
	                                    jQuery("#lp_eu_cookies_form").validate();

	                                  	//Toggle for use theme css or custom css
	                                    jQuery("#lp_eu_theme_css").click(function(){
											jQuery("#lp_eu_custom_css").toggle();
	                                    });

                                    });

									function sp_content_save() {
                                            var obj = document.getElementById('lp-content');
	                                        var content = document.getElementById('content');
	                                        tinyMCE.triggerSave(0, 1);
	                                        obj.value = content.value;
                                    }
                                </script>
                                <textarea id="lp-content" name="lp_eu_message" value="5" style="display:none" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        	<label class="field_title top_pad"> <?php _e('Button Text','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" id='lp_eu_button_text' name="lp_eu_button_text" value="<?php echo (isset($lp_eu_button_text) && !empty($lp_eu_button_text) ) ? $lp_eu_button_text : _e('I agree','wplegalpages') ; ?>" required />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Link Text','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" name="lp_eu_link_text" value="<?php echo $lp_eu_link_text; ?>" />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Link URL','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" name="lp_eu_link_url" value="<?php echo $lp_eu_link_url; ?>" />
	                        <br>
	                        <br>

                    		<label class="field_title"> <?php _e('Use Theme CSS','wplegalpages');?> : </label>
                            <input type="checkbox" <?php if(isset($lp_eu_theme_css) && $lp_eu_theme_css == 1 ) { echo 'checked';  } ?> value="1" id="lp_eu_theme_css" name="lp_eu_theme_css">

                        <div <?php if( isset($lp_eu_theme_css) && $lp_eu_theme_css == 1 ) { echo " style= 'display : none;' ";  } ?>  class="lp_eu_custom_css" id = "lp_eu_custom_css" >
	                        <label class="field_title"> <?php _e('Box Background Color','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" class="lp_eu_colors" name="lp_eu_box_color" value="<?php echo $lp_eu_box_color; ?>" />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Box Text Color','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" class="lp_eu_colors" name="lp_eu_text_color" value="<?php echo $lp_eu_text_color; ?>" />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Button Background Color','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" class="lp_eu_colors" name="lp_eu_button_color" value="<?php echo $lp_eu_button_color; ?>" />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Button Text Color','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" class="lp_eu_colors" name="lp_eu_button_text_color" value="<?php echo $lp_eu_button_text_color; ?>" />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Link Color','wplegalpages');?> : </label>
	                        <br>
	                        <input type="text" class="lp_eu_colors" name="lp_eu_link_color" value="<?php echo $lp_eu_link_color; ?>" />
	                        <br>
	                        <label class="field_title top_pad"> <?php _e('Text Size','wplegalpages');?> : </label>
	                        <br>
	                        <select name="lp_eu_text_size" style="width:100px;">
	                            <?php
								// This loop is for font-size
								for($i=10; $i<32; $i++)
								{ ?>
	                                <option value="<?php echo $i; ?>" <?php if($lp_eu_text_size== $i) echo "selected"; ?>>
	                                    <?php echo $i; ?>
	                                </option>
	                                <?php $i++; }
								?>
	                        </select>
                        <br>
                        </div> <!-- custom css div end -->
                        <p class="top_pad">
                            <input type="submit" class="btn btn-primary" onclick="sp_content_save();" name="lp-submit" value="<?php _e('Update','wplegalpages') ?>" /> </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
