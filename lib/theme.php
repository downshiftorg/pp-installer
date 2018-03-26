<?php

/**
 * Get the ProPhoto 7 Theme object
 *
 * The documentation for wp_get_themes() says it is expensive, so
 * we cache the result with a local static variable for performance
 *
 * @return WP_Theme|null
 */
function ppi_get_p7_theme() {
    static $p7 = false;

    if (false !== $p7) {
        return $p7;
    }

    foreach (wp_get_themes() as $theme) {
        if ((string) $theme === 'ProPhoto 7') {
            $p7 = $theme;
            return $p7;
        }
    }

    $p7 = null;
    return $p7;
}


/**
 * Is ProPhoto 7 the current active (not test-driven) theme?
 *
 * @return boolean
 */
function ppi_p7_is_active_theme() {
    $p7 = ppi_get_p7_theme();
    if (! $p7) {
        return false;
    }

    return get_option('template') === $p7->get_template();
}

/**
 * Is ProPhoto 7 installed?
 *
 * @return boolean
 */
function ppi_p7_is_installed() {
    return !!ppi_get_p7_theme();
}

/**
 * Get theme name
 *
 * Will determine name of active theme if no template slug passed.
 * ProPhoto 2-5 all use "ProPhoto" as the theme name, so append the
 * major version for disambiguation
 *
 * @param string|null $template
 * @return string
 */
function ppi_get_theme_name($template = null) {
    $theme = wp_get_theme($template);
    if ((string) $theme === 'ProPhoto') {
        return 'ProPhoto ' . intval($theme->get('Version'));
    }

    return (string) $theme;
}

/**
 * Get the theme name NOT being test-driven
 *
 * @return string
 */
function ppi_get_non_test_drive_theme_name() {
    return ppi_get_theme_name(get_option('template'));
}

/**
 * Get a nonced link for activating P7
 *
 * @return string
 */
function ppi_activate_p7_link() {
    $slug = ppi_get_p7_theme_slug();
    $url = 'themes.php?action=activate&amp;stylesheet=' . urlencode($slug);
    $activateLink = wp_nonce_url($url, 'switch-theme_' . $slug);
    return $activateLink;
}

/**
 * Get the P7 theme slug (equivalent to dir name of theme)
 *
 * @return string
 */
function ppi_get_p7_theme_slug() {
    $theme = ppi_get_p7_theme();
    if (! $theme) {
        return null;
    }

    return $theme->get_template();
}
