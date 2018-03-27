<?php

namespace ppi_7;

/**
 * Is the current PHP version high enough to run prophoto?
 *
 * @return boolean
 */
function php_compatible() {
    if (version_compare('5.4.0', PHP_VERSION) === 1) {
        return false;
    }

    return true;
}

/**
 * Is the current WordPress version high enough?
 *
 * @return boolean
 */
function wp_compatible() {
    return function_exists('rest_url');
}

/**
 * Is the server compiled with the required GD library support?
 *
 * @return boolean
 */
function gd_compatible() {
    return function_exists('imagecreatetruecolor');
}

/**
 * Does that server have the json-extension loaded?
 *
 * @return boolean
 */
function json_compatible() {
    return extension_loaded('json');
}

/**
 * Check if an individual grant string contains necessary permissions
 *
 * @param string $grant
 * @param string $db
 * @return boolean
 */
function mysql_grant_compatible($grant, $db) {
    $escaped = str_replace('_', '\_', $db);
    $wildcard = preg_replace('/_([\w-]+)/', '__', $escaped);

    // ensure the grant is for a particular db
    if (stripos($grant, $db) === false
        && stripos($grant, $escaped) === false
        && strpos($grant, '*.*') === false
        && stripos($grant, $wildcard) === false) {
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
function mysql_permission_compatible() {
    global $wpdb;
    $db = DB_NAME;
    $grants = $wpdb->get_results('SHOW GRANTS FOR CURRENT_USER', ARRAY_A);

    foreach ($grants as $row) {
        $grant = current($row);
        if (mysql_grant_compatible($grant, $db)) {
            return true;
        }
    }

    return can_create_alter_drop_table();
}

/**
 * Check if mysql user can CREATE, ALTER, DROP by actually trying
 *
 * @return boolean
 */
function can_create_alter_drop_table() {
    global $wpdb;
    $wpdb->suppress_errors();
    $testTable = "{$wpdb->prefix}ppi_priv_test";

    // CREATE
    $wpdb->query("CREATE TABLE IF NOT EXISTS $testTable (id INT)");
    if (! $wpdb->get_var("SHOW TABLES LIKE '$testTable'")) {
        return false;
    }

    // ALTER
    $wpdb->query("ALTER TABLE $testTable ADD COLUMN `add` INT");
    $wpdb->insert($testTable, array('id' => 1, 'add' => 2));
    $results = $wpdb->get_results("SELECT * FROM $testTable WHERE `add` = 2");
    if (empty($results)) {
        $wpdb->query("DROP TABLE {$wpdb->prefix}ppi_priv_test");
        return false;
    }

    // DROP
    $wpdb->query("DROP TABLE {$wpdb->prefix}ppi_priv_test");
    if (! $wpdb->get_var("SHOW TABLES LIKE '$testTable'")) {
        return true;
    }

    return false;
}

/**
 * Is the hosting compatible? (i.e. -- NOT wordpress.com)
 *
 * @return boolean
 */
function hosting_compatible() {
    $domain = extract_domain(home_url());
    if (! $domain) {
        return true;
    }

    $records = dns_get_record($domain, DNS_NS);
    $wpPattern = '/^ns[0-9]\.wordpress\.com$/i';
    foreach ($records as $entry) {
        if (isset($entry['target']) && preg_match($wpPattern, $entry['target'])) {
            return false;
        }
    }

    return true;
}
