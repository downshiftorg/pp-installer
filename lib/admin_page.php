<?php

/**
 * Init the installer plugin admin page
 *
 * @return void
 */
function p8i_admin_page_init() {
    wp_enqueue_style('p8i_css', P8I_URL . 'css/admin.css', array(), time());
    wp_enqueue_script('p8i_js', P8I_URL . 'js/admin.js', array(), time());
    add_action('admin_head', 'p8i_bootstrap_js');
}


/**
 * Add menu item for installer plugin
 *
 * @return void
 */
function p8i_add_menu_item() {
    if (p8i_is_installed()) {
        // dont show if the theme just went live
        if (isset($_GET['ppi_go_live']) && $_GET['ppi_go_live']) {
            return;
        }
        add_menu_page(
            'ProPhoto Installer',
            'P8 Test Drive',
            'edit_theme_options',
            'p8-installer',
            'p8i_render_admin_page',
            '',
            '50'
        );
        return;
    }
    add_menu_page(
        'ProPhoto Installer',
        'P8 Installer',
        'edit_theme_options',
        'p8-installer',
        'p8i_render_admin_page',
        '',
        '50'
    );
}

/**
 * Render installer admin page
 *
 * @return void
 */
function p8i_render_admin_page() {
    $phpIsCompatible = p8i_php_compatible();
    $wpIsCompatible = p8i_wp_compatible();
    $gdIsCompatible = p8i_gd_compatible();
    $jsonIsCompatible = p8i_json_compatible();
    $domIsCompatible = p8i_dom_compatible();
    $mysqlCompatible = p8i_mysql_permission_compatible();
    $hostingCompatible = p8i_hosting_compatible();
    $isRunningNextgenPlugin = p8i_is_running_nextgen_plugin();

    $isCompatible = $phpIsCompatible
        && $wpIsCompatible
        && $gdIsCompatible
        && $jsonIsCompatible
        && $domIsCompatible
        && $mysqlCompatible
        && $hostingCompatible
        && ! $isRunningNextgenPlugin;

    include(P8I_DIR . '/views/google-tag-manager.php');

    if (! $isCompatible || ! p8i_is_installed()) {
        include(P8I_DIR . '/views/pre-install.php');
        return;
    }

    if (p8i_test_driving()) {
        p8i_render_test_driving_page();
        return;
    }

    p8i_render_installed_page();

}

/**
 * Render the admin page when the user is test-driving prophoto
 *
 * @return void
 */
function p8i_render_test_driving_page() {
    $disableTestDriveUrl = admin_url('?ppi_disable_test_drive=1');
    $goLiveUrl = admin_url('themes.php?activated=true&ppi_go_live=1');
    $nonTestDriveTheme = p8i_get_non_test_drive_theme_name();
    include(P8I_DIR . '/views/test-driving.php');
}

/**
 * Render admin page when prophoto is installed but not active or being test-driven
 *
 * @return void
 */
function p8i_render_installed_page() {
    $testDriveUrl = admin_url('?ppi_enable_test_drive=1');
    $activateUrl = p8i_activate_link();
    include(P8I_DIR . '/views/installed.php');
}

/**
 * Render recommendations for prophoto
 *
 * @return void
 */
function p8i_render_recommendations() {
    $phpOutdated = version_compare('7.3', PHP_VERSION) === 1;
    $memoryLimit = (int) ini_get('memory_limit');
    $memoryLimitLow = $memoryLimit < 256;
    $missingImagick = !class_exists('Imagick');

    if ($phpOutdated || $memoryLimitLow || $missingImagick) {
        include(P8I_DIR . '/views/recommendations.php');
    }
}

/**
 * Render test-drive or installation info
 *
 * @return void
 */
function p8i_render_install_or_test_drive() {
    if (p8i_is_installed()) {
        p8i_render_test_drive();
        return;
    }

    if (p8i_get_registration()) {
        p8i_render_install_from_registration();
        return;
    }
}

/**
 * Render view for installing theme from registration data
 *
 * @return void
 */
function p8i_render_install_from_registration() {
    include(P8I_DIR . '/views/install-from-registration.php');
}

/**
 * Bootstrap assets to the page for javascript
 *
 * @return void
 */
function p8i_bootstrap_js() {
    list($lineItemId, $userToken) = p8i_get_registration();
    $ajaxUrl = admin_url('admin-ajax.php');

    include(P8I_DIR . '/views/bootstrap-js.php');
}

/**
 * Get the url of the ProPhoto installer plugin admin page
 *
 * @return string
 */
function p8i_get_admin_page_url() {
    return admin_url('admin.php?page=p8-installer');
}
