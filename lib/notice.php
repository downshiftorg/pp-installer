<?php

/**
 * Render the test-driving admin notice
 *
 * @return void
 */
function ppi_notice_test_driving() {
    if (ppi_p6_not_registered()) {
        $registerUrl = admin_url('themes.php?activated=true');
        include PPI_DIR . '/views/notice-test-driving-unregistered.php';
        return;
    }

    include PPI_DIR . '/views/notice-test-driving.php';
}

/**
 * Render an admin notice signifying that test-drive mode was disabled
 *
 * @return void
 */
function ppi_notice_test_drive_disabled() {
    include PPI_DIR . '/views/notice-test-drive-disabled.php';
}
