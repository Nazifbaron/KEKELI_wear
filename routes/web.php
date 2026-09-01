<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogue', function () {
    return view('catalogue');
});

Route::get('/client', function () {
    return view('client');
});

Route::get('/navigation', function () {
    return view('navigation');
});

Route::get('/cat', function () {
    return view('cat');
});
