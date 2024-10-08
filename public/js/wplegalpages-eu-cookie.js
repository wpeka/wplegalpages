/**
 * Cookie JavaScript.
 *
 * @package    Wplegalpages
 * @subpackage Wplegalpages/public
 * @author     wpeka <https://club.wpeka.com>
 */

 /**
 * Tooltip JavaScript.
 *
 * @package    Wplegalpages
 * @subpackage Wplegalpages/admin
 * @author     wpeka <https://club.wpeka.com>
 */


jQuery(document).ready(function(){
    if (jQuery.cookie('lp_eu_agree') == null) {
        jQuery.cookie('lp_eu_agree', 'NO', { expires: 7, path: '/' });
        lp_eu_show_cookie_bar();
    } else if (jQuery.cookie('lp_eu_agree') == 'NO') {
        lp_eu_show_cookie_bar();
    }

    // Event listener for the agree button
    jQuery('#lp_eu_btn_agree').click(function(){
		location.reload();
        jQuery.cookie('lp_eu_agree', 'YES', { expires: 7, path: '/' });
        jQuery('#lp_eu_container').hide(500);
    });

    // Event listener for the close button
    jQuery('#lp_eu_close_button').click(function(){
        jQuery('#lp_eu_container').css('display', 'none');
        jQuery.cookie('lp_eu_agree', 'close', { expires: 7, path: '/' });
    });
});

function lp_eu_show_cookie_bar() {
    jQuery('#lp_eu_container').css('display', 'block');
    if ('0' === obj.lp_eu_theme_css) {
        // Container design
        jQuery('#lp_eu_container').css({
            'background-color': obj.lp_eu_box_color,
            'border-color': obj.lp_eu_text_color,
            'color': obj.lp_eu_text_color
        });

        // Text font
        jQuery('p#lp_eu_body').css('font-size', obj.lp_eu_text_size);

        // Title design
        jQuery('#lp_eu_title').css('font-size', obj.lp_eu_head_text_size);

        // Agree button design
        jQuery('#lp_eu_btn_agree').css({
            'background-color': obj.lp_eu_button_color,
            'color': obj.lp_eu_button_text_color,
            'border-style': 'none',
            'border': '1px solid #bbb',
            'border-radius': '5px',
            'box-shadow': 'inset 0 0 1px 1px #f6f6f6',
            'line-height': 1,
            'padding': '7px',
            'padding-bottom': '9px',
            'text-align': 'center',
            'text-shadow': '0 1px 0 #fff',
            'cursor': 'pointer',
            'font-size': obj.lp_eu_text_size,
        });

        // Link color
        jQuery('#lp_eu_link').css({ 'color': obj.lp_eu_link_color });
    } else {
        // Container design
        jQuery('#lp_eu_container').css({ 'background-color': 'inherit', 'color': 'inherit' });
    }
    jQuery('#lp_eu_container').show(500);
}
