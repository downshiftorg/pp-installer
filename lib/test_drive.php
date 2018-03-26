<?php

namespace ppi_7;

/**
 * Is the user currently test driving P7?
 *
 * @return boolean
 */
function test_driving() {
    return get_option('test_driving') === 'enabled';
}

/**
 * Initialize test-driving
 *
 * @return void
 */
function test_drive_init() {
    if (test_driving() && user_can_see_test_drive()) {
        add_filter('template', 'filter_theme');
        add_filter('stylesheet', 'filter_theme');
    }

    $userIsAdmin = current_user_can('edit_theme_options');
    if (! $userIsAdmin && test_driving()) {
        add_filter('sidebars_widgets', 'use_non_test_drive_widgets');
    }

    if (! $userIsAdmin) {
        return;
    }

    // this needs to stay ABOVE handle_test_drive_changes()
    if (test_driving()) {
        add_filter('all_admin_notices', 'ppi_7\notice_test_driving');
        add_action('admin_head', 'ppi_7\manage_designs_bootstrap');
        add_action('pp_customizer_head', 'ppi_7\testdriving_bootstrap');
        add_action('pp_begin_body', 'ppi_7\testdriving_bootstrap');
        add_action('admin_head', 'ppi_7\testdriving_bootstrap');
        add_action('pp_working_design_id_set', 'ppi_7\set_working_design', 10, 3);
        add_filter('pp_notifications_config', 'ppi_7\notifications');
    }

    handle_test_drive_changes();

    if (p7_is_active_theme()) {
        disable_test_drive();
    }
}

/**
 * Should the current user be shown the test-driven theme?
 *
 * @return boolean
 */
function user_can_see_test_drive() {
    if (current_user_can('edit_theme_options')) {
        return true;
    }

    if (is_tech_front_view()) {
        return true;
    }

    return false;
}

/**
 * Is this request a ProPhoto tech viewing a test-driven site front?
 *
 * @return boolean
 */
function is_tech_front_view() {
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
function handle_test_drive_changes() {
    if (isset($_GET['ppi_enable_test_drive'])) {
        enable_test_drive();
    }

    if (isset($_GET['ppi_disable_test_drive'])) {
        disable_test_drive();
        $url = get_admin_page_url() . '&ppi_test_drive_disabled=1';
        header("Location: $url");
        exit;
    }

    if (isset($_GET['ppi_test_drive_disabled'])) {
        notice_test_drive_disabled();
    }

    if (isset($_GET['ppi_go_live']) && get_p7_theme_slug()) {
        go_live();
    }
}

/**
 * Switch out of test-drive mode by making P7 active
 *
 * @return void
 */
function go_live() {
    move_theme_widgets_to_theme_mods();
    delete_option('ppi_test_driving');
    update_option('template', get_p7_theme_slug());
    update_option('stylesheet', get_p7_theme_slug());
}

/**
 * Filter the active theme for test-driving purposes
 *
 * @param string $activeTheme
 * @return string
 */
function filter_theme($activeTheme) {
    $p7 = get_p7_theme_slug();

    if (! $p7) {
        return $activeTheme;
    }

    return $p7;
}

/**
 * Disable test-drive mode
 *
 * @return void
 */
function disable_test_drive() {
    unfreeze_theme_widgets();
    delete_option('ppi_test_driving');
}

/**
 * Enable test-drive mode
 *
 * @return void
 */
function enable_test_drive() {
    if (get_option('ppi_test_driving') === 'enabled' || get_p7_theme_slug() === get_option('template')) {
        return;
    }

    delete_user_working_designs();
    freeze_theme_widgets();
    update_option('ppi_test_driving', 'enabled');
    header('Location: ' . admin_url('themes.php?activated=true'));
    exit;
}
