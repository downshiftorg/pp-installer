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
@ini_set('display_errors', 1);
error_reporting(E_ALL);
define('P7I_DIR', dirname(__FILE__));
define('P7I_URL', plugin_dir_url(__FILE__));
defined('PROPHOTO_API_URL') || define('PROPHOTO_API_URL', 'https://api.pro.photo');

foreach ((array) glob(P7I_DIR . '/lib/*.php') as $file) {
    require_once($file);
}

add_action('admin_head-widgets.php', 'p7i_prevent_delete_inactive_widgets');
add_action('wp_ajax_ppi_api', 'p7i_api_route_request');
add_action('plugins_loaded', 'p7i_init');
add_action('pp_container_binding', 'p7i_container_bindings');
register_deactivation_hook(__FILE__, 'p7i_deactivation');
