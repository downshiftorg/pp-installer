<?php

/**
 * Render the test-driving admin notice
 *
 * @return void
 */
function p8i_notice_test_driving() {
    if (p8i_not_registered()) {
        $registerUrl = admin_url('themes.php?activated=true');
        include P8I_DIR . '/views/notice-test-driving-unregistered.php';
        return;
    }

    // don't show on manage designs screen, it's super clear there
    if (isset($_GET['page']) && $_GET['page'] === 'pp-designs') {
        return;
    }

    // don't show on the installer/test-drive plugin page
    if (isset($_GET['page']) && $_GET['page'] === 'p8-installer') {
        return;
    }

    // don't show if the theme has just gone live
    if (isset($_GET['ppi_go_live']) && $_GET['ppi_go_live']) {
        return;
    }

    include P8I_DIR . '/views/notice-test-driving.php';
}

/**
 * Render an admin notice signifying that test-drive mode was disabled
 *
 * @return void
 */
function p8i_notice_test_drive_disabled() {
    ob_start();
    include P8I_DIR . '/views/notice-test-drive-disabled.php';
    return ob_get_clean();
}

/**
 * Admin notice to disable p6 installer plugin
 */
function p8i_notice_disable_p6_installer() {
    include P8I_DIR . '/views/notice-disable-p6-installer.php';
}
