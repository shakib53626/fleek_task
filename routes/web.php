<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clear', function () {
    Artisan::call('optimize:clear');

    return 'Success! Your are very lucky!';
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
