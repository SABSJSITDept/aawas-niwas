<?php

namespace App\Http\Controllers;

class ParkingController extends Controller
{
    public function index()
    {
        $parkingLocations = [
            [
                'name' => 'पार्किंग स्थान 1 (Dummy)',
                'coordinates' => ['lat' => 0.0000000, 'lng' => 0.0000000],
                'mapUrl' => 'https://www.google.com/maps?q=0.0000000,0.0000000&z=17&hl=en',
                'icon' => 'bi-p-circle-fill',
                'color' => '#4338ca',
                'colorLight' => '#4338cadd'
            ],
            [
                'name' => 'पार्किंग स्थान 2 (Dummy)',
                'coordinates' => ['lat' => 0.0000000, 'lng' => 0.0000000],
                'mapUrl' => 'https://www.google.com/maps?q=0.0000000,0.0000000&z=17&hl=en',
                'icon' => 'bi-circle-fill',
                'color' => '#6366f1',
                'colorLight' => '#6366f1dd'
            ],
            [
                'name' => 'पार्किंग स्थान 3 (Dummy)',
                'coordinates' => ['lat' => 0.0000000, 'lng' => 0.0000000],
                'mapUrl' => 'https://www.google.com/maps?q=0.0000000,0.0000000&z=17&hl=en',
                'icon' => 'bi-m-circle-fill',
                'color' => '#818cf8',
                'colorLight' => '#818cf8dd'
            ]
        ];

        return view('parking', ['parkingLocations' => $parkingLocations]);
    }
}
