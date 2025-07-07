<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include bootstrap
require_once __DIR__ . '/bootstrap.php';

// Initialize session
Session::start();

// Create router
$router = new Router();

// Define routes
require_once __DIR__ . '/app/Routes.php';
defineRoutes($router);

// Dispatch request
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    // Handle server errors
    http_response_code(500);
    include __DIR__ . '/app/Views/errors/500.view.php';
}
