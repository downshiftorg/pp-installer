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

    return true;
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

/**
 * Check if an individual grant string contains necessary permissions
 *
 * @param string $grant
 * @param string $db
 * @return boolean
 */
function ppi_mysql_grant_compatible($grant, $db) {
    $escaped = str_replace('_', '\_', $db);

    if (strpos($grant, $db) === false && strpos($grant, $escaped) === false && strpos($grant, '*.*') === false) {
        return false;
    }

    if (preg_match('/\bALL\b/', $grant)) {
        return true;
    }

    if (preg_match('/\bALTER\b/', $grant) && preg_match('/\bDROP\b/', $grant)) {
        return true;
    }

    return false;
}

/**
 * Does the mysql user have permissions
 *
 * @return boolean
 */
function ppi_mysql_permission_compatible() {
    global $wpdb;
    $db = DB_NAME;
    $grants = $wpdb->get_results('SHOW GRANTS FOR CURRENT_USER', ARRAY_A);
    foreach ($grants as $row) {
        $grant = current($row);
        if (ppi_mysql_grant_compatible($grant, $db)) {
            return true;
        }
    }
    return false;
}
