<?php

/**
 * Is the user currently test driving P6?
 *
 * @return boolean
 */
function ppi_test_driving() {
    return get_option('ppi_test_driving') === 'enabled';
}

/**
 * Initialize test-driving
 *
 * @return void
 */
function ppi_test_drive_init() {
    if (ppi_test_driving() && ppi_user_can_see_test_drive()) {
        add_filter('template', 'ppi_filter_theme');
        add_filter('stylesheet', 'ppi_filter_theme');
    }

    if (! current_user_can('edit_theme_options')) {
        return;
    }

    // this needs to stay ABOVE ppi_handle_test_drive_changes()
    if (ppi_test_driving()) {
        add_filter('all_admin_notices', 'ppi_notice_test_driving');
    }

    ppi_handle_test_drive_changes();

    if (ppi_p6_is_active_theme()) {
        ppi_disable_test_drive();
    }
}

/**
 * Should the current user be shown the test-driven theme?
 *
 * @return boolean
 */
function ppi_user_can_see_test_drive() {
    if (current_user_can('edit_theme_options')) {
        return true;
    }

    if (ppi_is_tech_front_view()) {
        return true;
    }

    return false;
}

/**
 * Is this request a ProPhoto tech viewing a test-driven site front?
 *
 * @return boolean
 */
function ppi_is_tech_front_view() {
    $hash = 'dbd1a12e723ea95c39035e87045ab1be';

    if (isset($_GET['test_drive_auth']) && md5($_GET['test_drive_auth']) === $hash) {
        setcookie('test_drive_auth', $_GET['test_drive_auth'], time()+(60*5), '/');
        return true;
    }

    if (isset($_COOKIE['test_drive_auth']) && md5($_COOKIE['test_drive_auth']) === $hash) {
        return true;
    }

    return false;
}

/**
 * Handle test-drive state changes
 *
 * @return void
 */
function ppi_handle_test_drive_changes() {
    if (isset($_GET['ppi_enable_test_drive'])) {
        ppi_enable_test_drive();
    }

    if (isset($_GET['ppi_disable_test_drive'])) {
        ppi_disable_test_drive();
        $url = ppi_get_admin_page_url() . '&ppi_test_drive_disabled=1';
        header("Location: $url");
        exit;
    }

    if (isset($_GET['ppi_test_drive_disabled'])) {
        ppi_notice_test_drive_disabled();
    }

    if (isset($_GET['ppi_go_live']) && ppi_get_p6_theme_slug()) {
        ppi_disable_test_drive();
        update_option('template', ppi_get_p6_theme_slug());
        update_option('stylesheet', ppi_get_p6_theme_slug());
    }
}

/**
 * Filter the active theme for test-driving purposes
 *
 * @param string $activeTheme
 * @return string
 */
function ppi_filter_theme($activeTheme) {
    $p6 = ppi_get_p6_theme_slug();

    if (! $p6) {
        return $activeTheme;
    }

    return $p6;
}

/**
 * Disable test-drive mode
 *
 * @return void
 */
function ppi_disable_test_drive() {
    delete_option('ppi_test_driving');
}

/**
 * Enable test-drive mode
 *
 * @return void
 */
function ppi_enable_test_drive() {
    update_option('ppi_test_driving', 'enabled');
    ppi_backup_widget_state();
    header('Location: ' . admin_url('themes.php?activated=true'));
    exit;
}
