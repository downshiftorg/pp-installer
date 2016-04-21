<?php

/**
 * Is the current PHP version high enough to run P6?
 *
 * @return boolean
 */
function ppi_php_compatible() {
    if (version_compare('5.3.6', PHP_VERSION) === 1) {
        return false;
    }

    return PHP_MAJOR_VERSION < 7;
}

/**
 * Is the current WordPress version high enough?
 *
 * @return boolean
 */
function ppi_wp_compatible() {
    return function_exists('do_shortcodes_in_html_tags');
}

/**
 * Is the server compiled with the required GD library support?
 *
 * @return boolean
 */
function ppi_gd_compatible() {
    return function_exists('imagecreatetruecolor');
}

/**
 * Does that server have the json-extension loaded?
 *
 * @return boolean
 */
function ppi_json_compatible() {
    return extension_loaded('json');
}
