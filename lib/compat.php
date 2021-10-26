<?php

/**
 * Is the current PHP version high enough to run prophoto?
 *
 * @return boolean
 */
function p8i_php_compatible() {
    if (version_compare('7.3.0', PHP_VERSION) === 1) {
        return false;
    }

    return true;
}

/**
 * Is the current WordPress version high enough?
 *
 * @return boolean
 */
function p8i_wp_compatible() {
    return function_exists('rest_url');
}

/**
 * Is the server compiled with the required GD library support?
 *
 * @return boolean
 */
function p8i_gd_compatible() {
    return function_exists('imagecreatetruecolor');
}

/**
 * Does that server have the json-extension loaded?
 *
 * @return boolean
 */
function p8i_json_compatible() {
    return extension_loaded('json');
}

/**
 * Does that server have the dom-extension loaded?
 *
 * @return boolean
 */
function p8i_dom_compatible() {
    return extension_loaded('dom');
}

/**
 * Check if an individual grant string contains necessary permissions
 *
 * @param string $grant
 * @param string $db
 * @return boolean
 */
function p8i_mysql_grant_compatible($grant, $db) {
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
function p8i_mysql_permission_compatible() {
    global $wpdb;
    $db = DB_NAME;
    $grants = $wpdb->get_results('SHOW GRANTS FOR CURRENT_USER', ARRAY_A);

    foreach ($grants as $row) {
        $grant = current($row);
        if (p8i_mysql_grant_compatible($grant, $db)) {
            return true;
        }
    }

    return p8i_can_create_alter_drop_table();
}

/**
 * Check if mysql user can CREATE, ALTER, DROP by actually trying
 *
 * @return boolean
 */
function p8i_can_create_alter_drop_table() {
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
function p8i_hosting_compatible() {
    $domain = p8i_extract_domain(home_url());
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

function p8i_is_running_nextgen_plugin() {
    $activePlugins = get_option('active_plugins');
    return in_array('nextgen-gallery/nggallery.php', $activePlugins);
}
