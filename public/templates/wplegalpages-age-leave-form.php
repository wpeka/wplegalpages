<?php
/**
 * Provide a admin area view for age verify.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Wplegalpages_Pro
 * @subpackage Wplegalpages_Pro/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$age_type = get_option('_lp_display_option'); 
$redirect_url = get_option('_lp_redirect_url'); 
?> 

    <div class="verify"> 
        <div class="buttons-set"> 
            <span class="ageleavebutton"> 
                <input type="submit" name="lp_verify" id="lp_verify_yes" value="<?php echo esc_html__('Continue with the website.', 'wplegalpages')?>" /> 
            </span> 
            <span class="ageleavebutton"> 
                <input type="submit" name="lp_verify" id="lp_verify_leave" value="<?php echo esc_html__('Leave', 'wplegalpages')?>" onclick="window.location.href='<?php echo esc_url($redirect_url); ?>'" /> 
            </span> 
        </div> 
    </div>