<?php

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

Route::get('/tours-demo', function () {
    $tours = [
        // ['name' => '<h2>Ubud Cultural Tour</h2>', 'duration' => '6 hours', 'price' => 450000],
        ['name' => 'Nusa Penida Day Trip', 'duration' => '10 hours', 'price' => 850000],
        ['name' => 'Mount Batur Sunrise Trek', 'duration' => '7 hours', 'price' => 600000],
        ['name' => "<script>alert('x')</script>", 'duration' => 'EXPERIMENT', 'price' => 0],
    ];

    return view('tours-demo', ['tours' => $tours]);
});
