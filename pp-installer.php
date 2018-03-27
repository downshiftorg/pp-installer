<?php
/*
Plugin Name: ProPhoto 7 Installer
Plugin URI: https://github.com/downshiftorg/pp-installer
Description: Theme installer plugin for ProPhoto version 7. Checks server compatibility, auto-registers, and allows test-driving Prophoto while safely keeping another theme active.
Author: ProPhoto
Version: 7.0.0
Author URI: https://pro.photo
License: MIT
 */

define('PPI_DIR', dirname(__FILE__));
define('PPI_URL', plugin_dir_url(__FILE__));
defined('PROPHOTO_API_URL') || define('PROPHOTO_API_URL', 'https://api.pro.photo');

foreach ((array) glob(PPI_DIR . '/lib/*.php') as $file) {
    require_once($file);
}

add_action('admin_head-widgets.php', 'ppi_7\prevent_delete_inactive_widgets');

if (ppi_7\is_active_theme()) {
    return;
}

add_action('plugins_loaded', 'ppi_7\test_drive_init');
add_action('wp_ajax_ppi_api', 'ppi_7\api_route_request');
add_action('admin_menu', 'ppi_7\add_menu_item');
add_action('load-toplevel_page_prophoto-installer', 'ppi_7\admin_page_init');
add_action('admin_enqueue_scripts', 'ppi_7\pointer_init');
add_action('pp_container_binding', 'ppi_7\container_bindings');
register_deactivation_hook(__FILE__, 'ppi_7\deactivation');
