<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return view('admin');
});

// Payment routes
Route::get('/payment/success', function () {
    return view('welcome');
});

Route::get('/payment/failed', function () {
    return view('welcome');
});

// Catch-all route for other frontend routes, excluding /api routes
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');

 