<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::all();

        return view('tours-demo', ['tours' => $tours]);
    }

    public function show(Tour $tour)
    {
        return 'Tour ID: ' . $tour->id . ' | Tour name: ' . $tour->title;
    }
}
