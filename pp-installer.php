<?php
/*
Plugin Name: ProPhoto 7 Installer
Plugin URI: https://github.com/downshiftorg/pp-installer
Description: Theme installer plugin for ProPhoto version 7. Checks server compatibility, auto-registers, and allows test-driving Prophoto while safely keeping another theme active.
Author: ProPhoto
Version: 7.0.5
Author URI: https://pro.photo
License: MIT
 */
define('P7I_DIR', dirname(__FILE__));
define('P7I_URL', plugin_dir_url(__FILE__));
defined('PROPHOTO_API_URL') || define('PROPHOTO_API_URL', 'https://api.pro.photo');

foreach ((array) glob(P7I_DIR . '/lib/*.php') as $file) {
    require_once($file);
}

add_action('admin_head-widgets.php', 'p7i_prevent_delete_inactive_widgets');

if (p7i_is_active_theme()) {
    return;
}

if (p7i_p6_installer_active()) {
    add_action('admin_notices', 'p7i_notice_disable_p6_installer');
    return;
}

add_action('plugins_loaded', 'p7i_test_drive_init');
add_action('wp_ajax_ppi_api', 'p7i_api_route_request');
add_action('admin_menu', 'p7i_add_menu_item');
add_action('load-toplevel_page_p7-installer', 'p7i_admin_page_init');
add_action('admin_enqueue_scripts', 'p7i_pointer_init');
add_action('pp_container_binding', 'p7i_container_bindings');
register_deactivation_hook(__FILE__, 'p7i_deactivation');
