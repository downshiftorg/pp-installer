<?php

namespace ppi_7;

/**
 * Get the ProPhoto 7 Theme object
 *
 * The documentation for wp_get_themes() says it is expensive, so
 * we cache the result with a local static variable for performance
 *
 * @return WP_Theme|null
 */
function get_theme() {
    static $prophoto = false;

    if (false !== $prophoto) {
        return $prophoto;
    }

    foreach (wp_get_themes() as $theme) {
        if ((string) $theme === 'ProPhoto7') {
            $prophoto = $theme;
            return $prophoto;
        }
    }

    $prophoto = null;
    return $prophoto;
}


/**
 * Is ProPhoto 7 the current active (not test-driven) theme?
 *
 * @return boolean
 */
function is_active_theme() {
    $prophoto = get_theme();
    if (! $prophoto) {
        return false;
    }

    return get_option('template') === $prophoto->get_template();
}

/**
 * Is ProPhoto 7 installed?
 *
 * @return boolean
 */
function is_installed() {
    return !!get_theme();
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
function get_theme_name($template = null) {
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
function get_non_test_drive_theme_name() {
    return get_theme_name(get_option('template'));
}

/**
 * Get a nonced link for activating prophoto
 *
 * @return string
 */
function activate_link() {
    $slug = get_theme_slug();
    $url = 'themes.php?action=activate&amp;stylesheet=' . urlencode($slug);
    $activateLink = wp_nonce_url($url, 'switch-theme_' . $slug);
    return $activateLink;
}

/**
 * Get the prophoto theme slug (equivalent to dir name of theme)
 *
 * @return string
 */
function get_theme_slug() {
    $theme = get_theme();
    if (! $theme) {
        return null;
    }

    return $theme->get_template();
}
