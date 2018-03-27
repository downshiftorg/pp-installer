<?php

namespace ppi_7;

/**
 * Restore frozen theme widgets to `sidebars_widgets` option
 *
 * @return void
 */
function unfreeze_theme_widgets() {
    $prophotoTheme = get_theme_slug();
    $sidebarsWidgets = wp_get_sidebars_widgets();
    update_option("ppi_theme_widgets_{$prophotoTheme}", $sidebarsWidgets);

    $theme = get_option('template');
    $themeWidgets = get_option("ppi_theme_widgets_{$theme}", array());
    update_option('sidebars_widgets', $themeWidgets);
}

/**
 * Freeze theme widgets during test drive
 *
 * @return void
 */
function freeze_theme_widgets() {
    $theme = get_option('template');
    $sidebarsWidgets = wp_get_sidebars_widgets();
    update_option("ppi_theme_widgets_{$theme}", $sidebarsWidgets);

    $prophotoTheme = get_theme_slug();
    $prophotoWidgets = get_option("ppi_theme_widgets_{$prophotoTheme}", array());
    update_option('sidebars_widgets', $prophotoWidgets);
}

/**
 * Use frozen active theme widgets for non-admins while test-driving
 *
 * @return array
 */
function use_non_test_drive_widgets() {
    $theme = get_option('template');
    return get_option("ppi_theme_widgets_{$theme}");
}

/**
 * Move frozen theme widgets to theme mods for graceful restore on future theme switch
 *
 * @return void
 */
function move_theme_widgets_to_theme_mods() {
    $theme = get_option('template');
    $themeWidgets = get_option("ppi_theme_widgets_{$theme}", array());
    $themeMods = get_option("theme_mods_{$theme}", array());
    $themeMods['sidebars_widgets'] = array('time' => time(), 'data' => $themeWidgets);
    update_option("theme_mods_{$theme}", $themeMods);
}

/**
 * Hide delete inactive widgets button and append delete warning
 *
 * @return void
 */
function prevent_delete_inactive_widgets() {
    $warning = 'Be careful deleting! These widgets may be used by ProPhoto 7.';
    $css  = "#widgets-left .sidebar-description p:after {content: ' $warning'}";
    $css .= '.inactive-sidebar > .description, .remove-inactive-widgets {display: none}';
    echo "<style>$css</style>";
}
