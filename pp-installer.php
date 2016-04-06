<?php
/*
Plugin Name: ProPhoto 6 Installer
Plugin URI: https://github.com/netrivet/prophoto-installer-plugin
Description: Theme installer plugin for ProPhoto version 6. Checks server compatibility, auto-registers, and allows test-driving P6 while safely keeping another theme active.
Author: ProPhoto
Version: 6.0.0
Author URI: https://www.prophoto.com/
License: MIT
 */

define('PPI_DIR', dirname(__FILE__));
define('PPI_URL', plugin_dir_url(__FILE__));

foreach ((array) glob(PPI_DIR . '/lib/*.php') as $file) {
    require_once($file);
}

add_action('admin_head-widgets.php', 'ppi_prevent_delete_inactive_widgets');

if (ppi_p6_is_active_theme()) {
    return;
}

add_action('plugins_loaded', 'ppi_test_drive_init');
add_action('wp_ajax_ppi_api', 'ppi_api_route_request');
add_action('admin_menu', 'ppi_add_menu_item');
add_action('load-toplevel_page_prophoto-installer', 'ppi_admin_page_init');
add_action('admin_enqueue_scripts', 'ppi_pointer_init');
register_deactivation_hook(__FILE__, 'ppi_deactivation');
