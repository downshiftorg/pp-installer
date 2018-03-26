<?php

/**
 * Attempt to install P7 from registration data
 *
 * @return array
 */
function ppi_install_p7() {
    ppi_prep_install_p7();
    list($lineItemId, $userToken) = ppi_get_registration();
    $endpoint = PROPHOTO_API_URL . "/line-items/{$lineItemId}/download";
    $url = $endpoint .= "?user_token={$userToken}&installer=0";
    $file = download_url($url);

    if (is_wp_error($file)) {
        return ppi_install_wp_error($file);
    }

    if (! ppi_is_zip($file)) {
        return ppi_install_pp_error($file);
    }

    $result = unzip_file($file, get_theme_root() . '/');

    if (is_wp_error($result)) {
        return ppi_install_wp_error($result);
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
function ppi_set_ssl_version($handle) {
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
function ppi_is_zip($file) {
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
function ppi_install_wp_error($error) {
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
function ppi_install_pp_error($file) {
    $data = json_decode(@file_get_contents($file), true);

    if (is_array($data) && isset($data['message'])) {
        $message = $data['message'];
    } else {
        $message = 'Error downloading P7 from ProPhoto site.';
    }

    return array(
        'success' => false,
        'message' => $message,
    );
}

/**
 * Do some housekeeping before attempting to download P7 theme
 *
 * @return void
 */
function ppi_prep_install_p7() {
    // ensure required file functions are loaded
    require_once ABSPATH . 'wp-admin/includes/file.php';

    // try to bump memory limit for download/unzip to 512M if possible
    @ini_set('memory_limit', WP_MAX_MEMORY_LIMIT);
    add_filter('admin_memory_limit', create_function('', 'return "512M";'));

    // initialize the $wp_filesystem global object
    WP_Filesystem();

    add_action('http_api_curl', 'ppi_set_ssl_version');

    @ini_set('max_execution_time', 300);
}
