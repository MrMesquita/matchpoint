<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    abort(403, 'Unauthorized access');
});

Route::get('/slack-test', function () {
    return view('welcome');
});
