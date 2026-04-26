<?php

/**
 * Health Check Endpoint untuk SatuSehat Laravel Application
 * 
 * Endpoint ini digunakan untuk monitoring kesehatan aplikasi
 * dan dapat diakses di: http://your-domain/health
 */

// Basic health check response
$response = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0',
    'service' => 'SatuSehat Laravel Integration',
    'checks' => []
];

// Check if Laravel is accessible
try {
    // Try to access Laravel application
    if (file_exists(__DIR__ . '/bootstrap/app.php')) {
        $response['checks']['laravel'] = 'accessible';
    } else {
        $response['checks']['laravel'] = 'not_found';
        $response['status'] = 'unhealthy';
    }
} catch (Exception $e) {
    $response['checks']['laravel'] = 'error: ' . $e->getMessage();
    $response['status'] = 'unhealthy';
}

// Check storage directory permissions
try {
    $storagePath = __DIR__ . '/storage';
    if (is_dir($storagePath) && is_writable($storagePath)) {
        $response['checks']['storage'] = 'writable';
    } else {
        $response['checks']['storage'] = 'not_writable';
        $response['status'] = 'unhealthy';
    }
} catch (Exception $e) {
    $response['checks']['storage'] = 'error: ' . $e->getMessage();
    $response['status'] = 'unhealthy';
}

// Check bootstrap/cache directory permissions
try {
    $cachePath = __DIR__ . '/bootstrap/cache';
    if (is_dir($cachePath) && is_writable($cachePath)) {
        $response['checks']['cache'] = 'writable';
    } else {
        $response['checks']['cache'] = 'not_writable';
        $response['status'] = 'unhealthy';
    }
} catch (Exception $e) {
    $response['checks']['cache'] = 'error: ' . $e->getMessage();
    $response['status'] = 'unhealthy';
}

// Set HTTP status code
$httpStatus = ($response['status'] === 'healthy') ? 200 : 503;

// Set response headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
http_response_code($httpStatus);

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
