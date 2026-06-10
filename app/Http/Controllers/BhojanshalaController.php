<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BhojanshalaController extends Controller
{
    public function index()
    {
        $settings = \App\Models\SiteSetting::all()->pluck('value', 'key');
        return view('bhojanshala', compact('settings'));
    }
}
