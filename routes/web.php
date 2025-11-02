<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function () {
    phpinfo();
});

// fallback json response
Route::fallback(function () {
    return response()->json([
        'status' => 'ERR',
        'message' => 'Access Denied',
    ], 403, [], JSON_PRETTY_PRINT);
});
