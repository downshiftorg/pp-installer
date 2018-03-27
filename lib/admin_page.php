<?php

namespace ppi_7;

/**
 * Init the installer plugin admin page
 *
 * @return void
 */
function admin_page_init() {
    wp_enqueue_style('ppi_css', PPI_URL . 'css/admin.css', array(), time());
    wp_enqueue_script('ppi_js', PPI_URL . 'js/admin.js', array(), time());
    add_action('admin_head', '\ppi_7\bootstrap_js');
}


/**
 * Add menu item for installer plugin
 *
 * @return void
 */
function add_menu_item() {
    add_menu_page(
        'ProPhoto Installer',
        is_installed() ? 'P7 Test Drive' : 'P7 Installer',
        'edit_theme_options',
        'prophoto-installer',
        '\ppi_7\render_admin_page',
        '',
        '50'
    );
}

/**
 * Render installer admin page
 *
 * @return void
 */
function render_admin_page() {
    $phpIsCompatible = php_compatible();
    $wpIsCompatible = wp_compatible();
    $gdIsCompatible = gd_compatible();
    $jsonIsComptible = json_compatible();
    $mysqlCompatible = mysql_permission_compatible();
    $hostingCompatible = hosting_compatible();

    $isCompatible = $phpIsCompatible
        && $wpIsCompatible
        && $gdIsCompatible
        && $jsonIsComptible
        && $mysqlCompatible
        && $hostingCompatible;

    if (! $isCompatible || ! is_installed()) {
        include(PPI_DIR . '/views/pre-install.php');
        return;
    }

    if (test_driving()) {
        render_test_driving_page();
        return;
    }

    render_installed_page();

}

/**
 * Render the admin page when the user is test-driving prophoto
 *
 * @return void
 */
function render_test_driving_page() {
    $disableTestDriveUrl = admin_url('?ppi_disable_test_drive=1');
    $goLiveUrl = admin_url('themes.php?activated=true&ppi_go_live=1');
    $nonTestDriveTheme = get_non_test_drive_theme_name();
    include(PPI_DIR . '/views/test-driving.php');
}

/**
 * Render admin page when prophoto is installed but not active or being test-driven
 *
 * @return void
 */
function render_installed_page() {
    $testDriveUrl = admin_url('?ppi_enable_test_drive=1');
    $activateUrl = activate_link();
    include(PPI_DIR . '/views/installed.php');
}

/**
 * Render recommendations for prophoto
 *
 * @return void
 */
function render_recommendations() {
    $phpOutdated = version_compare('7.2', PHP_VERSION) === 1;
    $memoryLimit = (int) ini_get('memory_limit');
    $memoryLimitLow = $memoryLimit < 256;
    $missingImagick = !class_exists('Imagick');

    if ($phpOutdated || $memoryLimitLow || $missingImagick) {
        include(PPI_DIR . '/views/recommendations.php');
    }
}

/**
 * Render test-drive or installation info
 *
 * @return void
 */
function render_install_or_test_drive() {
    if (is_installed()) {
        render_test_drive();
        return;
    }

    render_install_from_registration();
    if (get_registration()) {
        render_install_from_registration();
        return;
    }
}

/**
 * Render view for installing theme from registration data
 *
 * @return void
 */
function render_install_from_registration() {
    include(PPI_DIR . '/views/install-from-registration.php');
}

/**
 * Bootstrap assets to the page for javascript
 *
 * @return void
 */
function bootstrap_js() {
    list($lineItemId, $userToken) = get_registration();
    $ajaxUrl = admin_url('admin-ajax.php');

    include(PPI_DIR . '/views/bootstrap-js.php');
}

/**
 * Get the url of the ProPhoto installer plugin admin page
 *
 * @return string
 */
function get_admin_page_url() {
    return admin_url('admin.php?page=prophoto-installer');
}
