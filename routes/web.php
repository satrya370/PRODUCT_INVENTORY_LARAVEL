<?php

use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/day-two', function () {
    return view('day-two');
});

Route::get('/hello', function () {
    return 'Hello TourFlow';
});

Route::get('/tours/{tour}', [TourController::class, 'show'])->name('tours.show');

Route::get('/tours-demo', [TourController::class, 'index']);
