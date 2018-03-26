<?php

namespace ppi_7;

/**
 * Render the test-driving admin notice
 *
 * @return void
 */
function notice_test_driving() {
    if (p7_not_registered()) {
        $registerUrl = admin_url('themes.php?activated=true');
        include PPI_DIR . '/views/notice-test-driving-unregistered.php';
        return;
    }

    // don't show on manage designs screen, it's super clear there
    if (isset($_GET['page']) && $_GET['page'] === 'pp-designs') {
        return;
    }

    include PPI_DIR . '/views/notice-test-driving.php';
}

/**
 * Render an admin notice signifying that test-drive mode was disabled
 *
 * @return void
 */
function notice_test_drive_disabled() {
    include PPI_DIR . '/views/notice-test-drive-disabled.php';
}
