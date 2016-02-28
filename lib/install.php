<?php

/**
 * Attempt to install P6 by authenticated token download from ProPhoto API
 *
 * @return array
 */
function ppi_install_p6() {
    ppi_prep_install_p6();
    $token = ppi_get_token();
    $url = 'https://api.prophoto.com/download/prophoto/token/' . $token;
    $file = download_url($url);

    if (is_wp_error($file)) {
        return ppi_install_wp_error($file);
    }

    if (mime_content_type($file) !== 'application/zip') {
        return ppi_install_pp_error($file);
    }

    $result = unzip_file($file, get_theme_root() . '/');

    if (is_wp_error($result)) {
        return ppi_install_wp_error($result);
    }

    return array('success' => true);
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
        $message = 'Error downloading P6 from ProPhoto site.';
    }

    return array(
        'success' => false,
        'message' => $message,
    );
}

/**
 * Do some housekeeping before attempting to download P6 theme
 *
 * @return void
 */
function ppi_prep_install_p6() {
    // ensure required file functions are loaded
    require_once ABSPATH . 'wp-admin/includes/file.php';

    // try to bump memory limit for download/unzip to 512M if possible
    @ini_set('memory_limit', WP_MAX_MEMORY_LIMIT);
    add_filter('admin_memory_limit', create_function('', 'return "512M";'));

    // initialize the $wp_filesystem global object
    WP_Filesystem();

    @ini_set('max_execution_time', 300);
}
