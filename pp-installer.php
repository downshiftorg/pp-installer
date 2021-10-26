<?php
/*
Plugin Name: ProPhoto 8 Installer
Plugin URI: https://github.com/downshiftorg/pp-installer
Description: Theme installer plugin for ProPhoto version 8. Checks server compatibility, auto-registers, and allows test-driving Prophoto while safely keeping another theme active.
Author: ProPhoto
Version: 8.0.0
Author URI: https://pro.photo
License: MIT
 */
define('P8I_DIR', dirname(__FILE__));
define('P8I_URL', plugin_dir_url(__FILE__));
defined('PROPHOTO_API_URL') || define('PROPHOTO_API_URL', 'https://api.pro.photo');

foreach ((array) glob(P8I_DIR . '/lib/*.php') as $file) {
    require_once($file);
}

add_action('admin_head-widgets.php', 'p8i_prevent_delete_inactive_widgets');

if (p8i_is_active_theme()) {
    return;
}

if (p8i_legacy_installer_active()) {
    add_action('admin_notices', 'p8i_notice_disable_p6_installer');
    return;
}

add_action('plugins_loaded', 'p8i_test_drive_init');
add_action('wp_ajax_ppi_api', 'p8i_api_route_request');
add_action('admin_menu', 'p8i_add_menu_item');
add_action('load-toplevel_page_p8-installer', 'p8i_admin_page_init');
add_action('admin_enqueue_scripts', 'p8i_pointer_init');
add_action('pp_container_binding', 'p8i_container_bindings');
register_deactivation_hook(__FILE__, 'p8i_deactivation');
