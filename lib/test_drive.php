<?php

/**
 * Is the user currently test driving prophoto?
 *
 * @return boolean
 */
function p7i_test_driving() {
    return get_option('p7i_test_driving') === 'enabled';
}

/**
 * Initialize test-driving
 *
 * @return void
 */
function p7i_test_drive_init() {
    if (p7i_test_driving() && p7i_user_can_see_test_drive()) {
        add_filter('template', 'p7i_filter_theme');
        add_filter('stylesheet', 'p7i_filter_theme');
    }

    $userIsAdmin = current_user_can('edit_theme_options');
    if (! $userIsAdmin && p7i_test_driving()) {
        add_filter('sidebars_widgets', 'p7i_use_non_test_drive_widgets');
    }

    if (! $userIsAdmin) {
        return;
    }

    // this needs to stay ABOVE handle_test_drive_changes()
    if (p7i_test_driving()) {
        add_filter('all_admin_notices', 'p7i_notice_test_driving');
        add_action('admin_head', 'p7i_manage_designs_bootstrap');
        add_action('pp_customizer_head', 'p7i_testdriving_bootstrap');
        add_action('pp_begin_body', 'p7i_testdriving_bootstrap');
        add_action('admin_head', 'p7i_testdriving_bootstrap');
        add_action('pp_working_design_id_set', 'p7i_set_working_design', 10, 3);
        add_filter('pp_notifications_config', 'p7i_notifications');
    }

    p7i_handle_test_drive_changes();

    if (p7i_is_active_theme()) {
        p7i_disable_test_drive();
    }
}

/**
 * Should the current user be shown the test-driven theme?
 *
 * @return boolean
 */
function p7i_user_can_see_test_drive() {
    if (current_user_can('edit_theme_options')) {
        return true;
    }

    if (p7i_is_tech_front_view()) {
        return true;
    }

    return false;
}

/**
 * Is this request a ProPhoto tech viewing a test-driven site front?
 *
 * @return boolean
 */
function p7i_is_tech_front_view() {
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
function p7i_handle_test_drive_changes() {
    if (isset($_GET['ppi_enable_test_drive'])) {
        p7i_enable_test_drive();
    }

    if (isset($_GET['ppi_disable_test_drive'])) {
        p7i_disable_test_drive();
        $url = p7i_get_admin_page_url() . '&ppi_test_drive_disabled=1';
        header("Location: $url");
        exit;
    }

    if (isset($_GET['ppi_test_drive_disabled'])) {
        p7i_notice_test_drive_disabled();
    }

    if (isset($_GET['ppi_go_live']) && p7i_get_theme_slug()) {
        p7i_go_live();
    }
}

/**
 * Switch out of test-drive mode by making prophoto active
 *
 * @return void
 */
function p7i_go_live() {
    p7i_move_theme_widgets_to_theme_mods();
    delete_option('p7i_test_driving');
    update_option('template', p7i_get_theme_slug());
    update_option('stylesheet', p7i_get_theme_slug());
}

/**
 * Filter the active theme for test-driving purposes
 *
 * @param string $activeTheme
 * @return string
 */
function p7i_filter_theme($activeTheme) {
    $prophoto = p7i_get_theme_slug();

    if (! $prophoto) {
        return $activeTheme;
    }

    return $prophoto;
}

/**
 * Disable test-drive mode
 *
 * @return void
 */
function p7i_disable_test_drive() {
    p7i_unfreeze_theme_widgets();
    delete_option('p7i_test_driving');
}

/**
 * Enable test-drive mode
 *
 * @return void
 */
function p7i_enable_test_drive() {
    if (get_option('p7i_test_driving') === 'enabled' || p7i_get_theme_slug() === get_option('template')) {
        return;
    }

    p7i_delete_user_working_designs();
    p7i_freeze_theme_widgets();
    update_option('p7i_test_driving', 'enabled');
    header('Location: ' . admin_url('themes.php?activated=true'));
    exit;
}
