<?php

/**
 * Route an internal API request
 *
 * @return void
 */
function p8i_api_route_request() {
    status_header(500);
    $affordance = $_GET['affordance'];
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    $handler = "p8i_api_{$method}_{$affordance}";

    if (! function_exists($handler)) {
        status_header(404);
        p8i_api_send_error("unknown affordance $affordance");
        exit;
    }

    call_user_func($handler);
    exit;
}

/**
 * Handle GET requests to install prophoto
 *
 * @return void
 */
function p8i_api_get_install() {
    $result = p8i_install();

    if ($result['success'] === true) {
        status_header(204);
        return;
    }

    status_header(500);
    p8i_api_send_error($result['message']);
}

/**
 * Handle POST requests for saving WordPress options
 *
 * @return void
 */
function p8i_api_post_save_option() {
    $payload = p8i_api_get_json_body();

    if (! isset($payload['key']) || ! isset($payload['value'])) {
        status_header(400);
        p8i_api_send_error('missing required data');
        return;
    }

    if ($payload['value'] === get_option($payload['key'])) {
        status_header(204);
        return;
    }

    if (! update_option($payload['key'], $payload['value'], false)) {
        status_header(500);
        p8i_api_send_error('error saving option');
        return;
    }

    status_header(204);
}

/**
 * Get request body as decoded json
 *
 * @return array
 */
function p8i_api_get_json_body() {
    return json_decode(file_get_contents('php://input'), true);
}

/**
 * Send json content to the browser
 *
 * Uses JSON_PRETTY_PRINT, if available
 *
 * @param array $response
 * @return void
 */
function p8i_api_send_json($response) {
    @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
    $options = defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0;
    echo json_encode($response, $options);
    exit;
}

/**
 * Send an api json error message
 *
 * @param  string $msg
 * @return void
 */
function p8i_api_send_error($msg) {
    p8i_api_send_json(array('errors' => array(array('title' => $msg))));
}
