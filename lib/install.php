<?php

/**
 * Attempt to install prophoto from registration data
 *
 * @return array
 */
function p8i_install() {
    p8i_prep_install();
    list($lineItemId, $userToken) = p8i_get_registration();
    $endpoint = PROPHOTO_API_URL . "/line-items/{$lineItemId}/download";
    $url = $endpoint .= "?user_token={$userToken}&installer=0";
    $file = download_url($url);

    if (is_wp_error($file)) {
        return p8i_install_wp_error($file);
    }

    if (! p8i_is_zip($file)) {
        return p8i_install_pp_error($file);
    }

    $result = unzip_file($file, get_theme_root() . '/');

    if (is_wp_error($result)) {
        return p8i_install_wp_error($result);
    }

    return array('success' => true);
}

/**
 * Set curl ssl version to 1, to prevent SSL SNI issues
 *
 * @see https://github.com/netrivet/prophoto-issues/issues/445
 * @param  resource $handle
 * @return void
 */
function p8i_set_ssl_version($handle) {
    curl_setopt($handle, CURLOPT_SSLVERSION, 1);
}

/**
 * Is the file a zip?
 *
 * Not all PHP 5.2+ servers have mime_content_type() or finfo.
 * I borrowed the code from Drupal, so it should be pretty solid.
 *
 * @see http://api.drush.org/api/drush/includes!drush.inc/function/drush_mime_content_type/6.x
 * @param string $file
 * @return boolean
 */
function p8i_is_zip($file) {
    $handle = fopen($file, 'rb');
    $first = fread($handle, 2);
    fclose($handle);

    if ($first === false) {
        return false;
    }

    $data = unpack('v', $first);
    return $data[1] === 0x4b50;
}

/**
 * Return an install error array from a WP_Error object
 *
 * @param WP_Error $error
 * @return array
 */
function p8i_install_wp_error($error) {
    return array(
        'success' => false,
        'message' => $error->get_error_message(),
    );
}

/**
 * Return an install error array from non-zip filepath
 *
 * @param string $file
 * @return array
 */
function p8i_install_pp_error($file) {
    $data = json_decode(@file_get_contents($file), true);

    if (is_array($data) && isset($data['message'])) {
        $message = $data['message'];
    } else {
        $message = 'Error downloading P8 from ProPhoto site.';
    }

    return array(
        'success' => false,
        'message' => $message,
    );
}

/**
 * Do some housekeeping before attempting to download prophoto theme
 *
 * @return void
 */
function p8i_prep_install() {
    // ensure required file functions are loaded
    require_once ABSPATH . 'wp-admin/includes/file.php';

    // try to bump memory limit for download/unzip to 512M if possible
    @ini_set('memory_limit', WP_MAX_MEMORY_LIMIT);
    add_filter('admin_memory_limit', 'p8i_bump_mem_limit');

    // initialize the $wp_filesystem global object
    WP_Filesystem();

    add_action('http_api_curl', 'p8i_set_ssl_version');

    @ini_set('max_execution_time', 300);
}

/**
 * Get the new allowed memory limit
 * 
 * @return string
 */
function p8i_bump_mem_limit() {
    return '512M';
}
