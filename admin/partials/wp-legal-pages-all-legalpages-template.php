<?php
/**
 * Provide a All Legalpages view for the WP Legal Pages plugin
 *
 * This file is used to markup the All Legalpages facing aspects of the WP Legal Pages plugin.
 * php version 7.0
 * 
 * @category X
 * @package  Wplegalpages
 * @author   Display Name <username@example.com>
 * @license  username@example.com X
 * @link     https://wplegalpages.com/
 * @since    2.10.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$baseurl = '';
if (isset($_SERVER['PHP_SELF'])) {
    $baseurl = esc_url_raw(wp_unslash($_SERVER['PHP_SELF']));
}

if (class_exists('WP_Legal_Pages_Admin')) {
    WP_Legal_Pages_Admin::show_pages();
}
