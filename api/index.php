<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if the requested uri contains .php 
if (preg_match('#[a-z0-9_-]+\.php(.*)#i', $_SERVER['REQUEST_URI'])) {
	http_response_code(403);
	header('Content-Type: application/json');
	die(json_encode([
        'status' => 'ERR',
        'message' => 'Access Denied'
    ], JSON_PRETTY_PRINT));
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
