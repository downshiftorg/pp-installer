<?php

/**
 * Init the installer plugin admin page
 *
 * @return void
 */
function p7i_admin_page_init() {
    wp_enqueue_style('p7i_css', P7I_URL . 'css/admin.css', array(), time());
    wp_enqueue_script('p7i_js', P7I_URL . 'js/admin.js', array(), time());
    add_action('admin_head', 'p7i_bootstrap_js');
}


/**
 * Add menu item for installer plugin
 *
 * @return void
 */
function p7i_add_menu_item() {
    add_menu_page(
        'ProPhoto Installer',
        p7i_is_installed() ? 'P7 Test Drive' : 'P7 Installer',
        'edit_theme_options',
        'p7-installer',
        'p7i_render_admin_page',
        '',
        '50'
    );
}

/**
 * Render installer admin page
 *
 * @return void
 */
function p7i_render_admin_page() {
    $phpIsCompatible = p7i_php_compatible();
    $wpIsCompatible = p7i_wp_compatible();
    $gdIsCompatible = p7i_gd_compatible();
    $jsonIsCompatible = p7i_json_compatible();
    $domIsCompatible = p7i_dom_compatible();
    $mysqlCompatible = p7i_mysql_permission_compatible();
    $hostingCompatible = p7i_hosting_compatible();

    $isCompatible = $phpIsCompatible
        && $wpIsCompatible
        && $gdIsCompatible
        && $jsonIsCompatible
        && $domIsCompatible
        && $mysqlCompatible
        && $hostingCompatible;

    include(P7I_DIR . '/views/google-tag-manager.php');

    if (! $isCompatible || ! p7i_is_installed()) {
        include(P7I_DIR . '/views/pre-install.php');
        return;
    }

    if (p7i_test_driving()) {
        p7i_render_test_driving_page();
        return;
    }

    p7i_render_installed_page();

}

/**
 * Render the admin page when the user is test-driving prophoto
 *
 * @return void
 */
function p7i_render_test_driving_page() {
    $disableTestDriveUrl = admin_url('?ppi_disable_test_drive=1');
    $goLiveUrl = admin_url('themes.php?activated=true&ppi_go_live=1');
    $nonTestDriveTheme = p7i_get_non_test_drive_theme_name();
    include(P7I_DIR . '/views/test-driving.php');
}

/**
 * Render admin page when prophoto is installed but not active or being test-driven
 *
 * @return void
 */
function p7i_render_installed_page() {
    $testDriveUrl = admin_url('?ppi_enable_test_drive=1');
    $activateUrl = p7i_activate_link();
    include(P7I_DIR . '/views/installed.php');
}

/**
 * Render recommendations for prophoto
 *
 * @return void
 */
function p7i_render_recommendations() {
    $phpOutdated = version_compare('7.0', PHP_VERSION) === 1;
    $memoryLimit = (int) ini_get('memory_limit');
    $memoryLimitLow = $memoryLimit < 256;
    $missingImagick = !class_exists('Imagick');

    if ($phpOutdated || $memoryLimitLow || $missingImagick) {
        include(P7I_DIR . '/views/recommendations.php');
    }
}

/**
 * Render test-drive or installation info
 *
 * @return void
 */
function p7i_render_install_or_test_drive() {
    if (p7i_is_installed()) {
        p7i_render_test_drive();
        return;
    }

    if (p7i_get_registration()) {
        p7i_render_install_from_registration();
        return;
    }
}

/**
 * Render view for installing theme from registration data
 *
 * @return void
 */
function p7i_render_install_from_registration() {
    include(P7I_DIR . '/views/install-from-registration.php');
}

/**
 * Bootstrap assets to the page for javascript
 *
 * @return void
 */
function p7i_bootstrap_js() {
    list($lineItemId, $userToken) = p7i_get_registration();
    $ajaxUrl = admin_url('admin-ajax.php');

    include(P7I_DIR . '/views/bootstrap-js.php');
}

/**
 * Get the url of the ProPhoto installer plugin admin page
 *
 * @return string
 */
function p7i_get_admin_page_url() {
    return admin_url('admin.php?page=p7-installer');
}
