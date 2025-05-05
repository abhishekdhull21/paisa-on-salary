<?php
// Add CORS headers
file_put_contents(APPPATH . 'logs/cors_debug.log', "CORS Hook triggered\n", FILE_APPEND);

function add_cors_headers() {
    header("Access-Control-Allow-Origin: *"); // Allow all origins, or restrict to specific domain
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); // Allowed HTTP methods
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Auth, X-Requested-With"); // Allow custom headers, including 'auth'
    file_put_contents(APPPATH . 'logs/cors_debug.log', "CORS Hook triggered 1\n", FILE_APPEND);

    // Handle OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // header('HTTP/1.1 200 OK');
        http_response_code(200);
        exit();
    }
}
