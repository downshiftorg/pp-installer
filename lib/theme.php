<?php

/**
 * Get the ProPhoto 7 Theme object
 *
 * The documentation for wp_get_themes() says it is expensive, so
 * we cache the result with a local static variable for performance
 *
 * @return WP_Theme|null
 */
function p7i_get_theme() {
    static $prophoto = false;

    if (false !== $prophoto) {
        return $prophoto;
    }

    foreach (wp_get_themes() as $theme) {
        if ((string) $theme === 'ProPhoto 7') {
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
function p7i_is_active_theme() {
    $prophoto = p7i_get_theme();
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
function p7i_is_installed() {
    return !!p7i_get_theme();
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
function p7i_get_theme_name($template = null) {
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
function p7i_get_non_test_drive_theme_name() {
    return p7i_get_theme_name(get_option('template'));
}

/**
 * Get a nonced link for activating prophoto
 *
 * @return string
 */
function p7i_activate_link() {
    $slug = p7i_get_theme_slug();
    $url = 'themes.php?action=activate&amp;stylesheet=' . urlencode($slug);
    $activateLink = wp_nonce_url($url, 'switch-theme_' . $slug);
    return $activateLink;
}

/**
 * Get the prophoto theme slug (equivalent to dir name of theme)
 *
 * @return string
 */
function p7i_get_theme_slug() {
    $theme = p7i_get_theme();
    if (! $theme) {
        return null;
    }

    return $theme->get_template();
}
