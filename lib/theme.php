<?php

/**
 * Get the ProPhoto 6 Theme object
 *
 * The documentation for wp_get_themes() says it is expensive, so
 * we cache the result with a local static variable for performance
 *
 * @return WP_Theme|null
 */
function ppi_get_p6_theme() {
    static $p6 = false;

    if (false !== $p6) {
        return $p6;
    }

    foreach (wp_get_themes() as $theme) {
        if ((string) $theme === 'ProPhoto 6') {
            $p6 = $theme;
            return $p6;
        }
    }

    $p6 = null;
    return $p6;
}


/**
 * Is ProPhoto 6 the current active (not test-driven) theme?
 *
 * @return boolean
 */
function ppi_p6_is_active_theme() {
    $p6 = ppi_get_p6_theme();
    if (! $p6) {
        return false;
    }

    return get_option('template') === $p6->get_template();
}

/**
 * Is ProPhoto 6 installed?
 *
 * @return boolean
 */
function ppi_p6_is_installed() {
    return !!ppi_get_p6_theme();
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
 * Get a nonced link for activating P6
 *
 * @return string
 */
function ppi_activate_p6_link() {
    $slug = ppi_get_p6_theme_slug();
    $url = 'themes.php?action=activate&amp;stylesheet=' . urlencode($slug);
    $activateLink = wp_nonce_url($url, 'switch-theme_' . $slug);
    return $activateLink;
}

/**
 * Get the P6 theme slug (equivalent to dir name of theme)
 *
 * @return string
 */
function ppi_get_p6_theme_slug() {
    $theme = ppi_get_p6_theme();
    if (! $theme) {
        return null;
    }

    return $theme->get_template();
}
