<?php

namespace App\Http\Controllers;

class ParkingController extends Controller
{
    public function index()
    {
        $parkingLocations = \App\Models\ParkingLocation::where('status', true)->get();

        return view('parking', ['parkingLocations' => $parkingLocations]);
    }
}
