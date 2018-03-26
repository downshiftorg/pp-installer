<?php

/**
 * Filter the ProPhoto bar notifications config array
 *
 * @param array $config
 * @return array
 */
function ppi_notifications($config) {
    foreach ($config as $index => $notification) {
        if ($notification['id'] === 'previewing_non_live_design') {
            unset($config[$index]);
        }

        if ($notification['id'] === 'coming_soon_design') {
            $config[$index]['message'] = "When you first activate P7, we start you out with a design called <b>Coming Soon/Maintenance Mode</b>. Since you're <em>test-driving,</em> you probably don't need this design, because it's meant to be a temporary placeholder while you get a different P7 design ready. You're already showing your live theme to site visitors, so you should go to your <a href='%2\$s' target='_blank'>Manage Designs</a> screen and pick a different design to start with.";
        }
    }

    $liveTheme = ppi_get_non_test_drive_theme_name();

    array_splice($config, 1, 0, array(
        array(
            'id' => 'test_driving',
            'title' => 'Test-Drive Mode',
            'message' => "You are currently <em>test-driving</em> ProPhoto 7. That means only logged-in admin users can see what you're seeing now. All other site visitors are seeing your live theme, which is <b>$liveTheme</b>. You can verify this by viewing this page in a different browser where you are not logged in. &nbsp;<a href='https://help.prophoto.com/install/prophoto/#test-drive-prophoto' target='_blank'>More info &raquo;</a>",
        )
    ));

    return array_values($config);
}
