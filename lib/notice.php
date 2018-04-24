<?php

/**
 * Render the test-driving admin notice
 *
 * @return void
 */
function p7i_notice_test_driving() {
    if (p7i_not_registered()) {
        $registerUrl = admin_url('themes.php?activated=true');
        include P7I_DIR . '/views/notice-test-driving-unregistered.php';
        return;
    }

    // don't show on manage designs screen, it's super clear there
    if (isset($_GET['page']) && $_GET['page'] === 'pp-designs') {
        return;
    }

    include P7I_DIR . '/views/notice-test-driving.php';
}

/**
 * Render an admin notice signifying that test-drive mode was disabled
 *
 * @return void
 */
function p7i_notice_test_drive_disabled() {
    include P7I_DIR . '/views/notice-test-drive-disabled.php';
}
