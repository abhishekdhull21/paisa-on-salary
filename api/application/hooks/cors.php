<?php
// Log that hook file was loaded
file_put_contents(APPPATH . 'logs/cors_debug.log', "CORS Hook file included\n", FILE_APPEND);

function add_cors_headers() {
    file_put_contents(APPPATH . 'logs/cors_debug.log', "CORS Hook triggered: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Auth, X-Requested-With");

    // If it's a preflight request, end early
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}
