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

Route::get('/tours/{id}', function ($id) {
    return 'Tour ID: ' . $id;
})->name('tours.show');

Route::get('/tours-demo', [TourController::class, 'index']);
