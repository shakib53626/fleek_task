<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('welcome'); // Vue.js এর মূল Blade ফাইল লোড করবে
})->where('any', '.*'); // যেকোনো রুট Vue Router এ পাঠাবে
