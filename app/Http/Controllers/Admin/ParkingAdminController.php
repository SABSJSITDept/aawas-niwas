<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use Illuminate\Http\Request;

class ParkingAdminController extends Controller
{
    public function index()
    {
        $locations = ParkingLocation::all();
        return view('admin.parking.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.parking.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'map_url' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'color_light' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');
        $validated['lat'] = null;
        $validated['lng'] = null;
        $validated['icon'] = 'bi-p-circle-fill';
        $validated['color'] = '#4338ca';
        $validated['color_light'] = '#818cf8';

        ParkingLocation::create($validated);

        return redirect()->route('admin.parking.index')->with('success', 'Parking Location added successfully.');
    }

    public function edit(ParkingLocation $parking)
    {
        return view('admin.parking.create', compact('parking'));
    }

    public function update(Request $request, ParkingLocation $parking)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'map_url' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'color_light' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');
        $validated['icon'] = $parking->icon ?? 'bi-p-circle-fill';
        $validated['color'] = $parking->color ?? '#4338ca';
        $validated['color_light'] = $parking->color_light ?? '#818cf8';

        $parking->update($validated);

        return redirect()->route('admin.parking.index')->with('success', 'Parking Location updated successfully.');
    }

    public function destroy(ParkingLocation $parking)
    {
        $parking->delete();
        return redirect()->route('admin.parking.index')->with('success', 'Parking Location deleted successfully.');
    }

    public function toggle(ParkingLocation $parking)
    {
        $parking->update(['status' => !$parking->status]);
        return redirect()->back()->with('success', 'Status updated.');
    }
}
