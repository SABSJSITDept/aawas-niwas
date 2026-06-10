<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Helpline;
use Illuminate\Http\Request;

class HelplineAdminController extends Controller
{
    public function index()
    {
        $helplines = Helpline::all();
        return view('admin.helplines.index', compact('helplines'));
    }

    public function create()
    {
        return view('admin.helplines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'is_home_medical' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['is_home_medical'] = $request->has('is_home_medical');
        $validated['status'] = $request->has('status');

        Helpline::create($validated);

        return redirect()->route('admin.helplines.index')->with('success', 'Helpline added successfully.');
    }

    public function edit(Helpline $helpline)
    {
        return view('admin.helplines.create', compact('helpline'));
    }

    public function update(Request $request, Helpline $helpline)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'is_home_medical' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['is_home_medical'] = $request->has('is_home_medical');
        $validated['status'] = $request->has('status');

        $helpline->update($validated);

        return redirect()->route('admin.helplines.index')->with('success', 'Helpline updated successfully.');
    }

    public function destroy(Helpline $helpline)
    {
        $helpline->delete();
        return redirect()->route('admin.helplines.index')->with('success', 'Helpline deleted successfully.');
    }

    public function toggle(Helpline $helpline)
    {
        $helpline->update(['status' => !$helpline->status]);
        return redirect()->back()->with('success', 'Status updated.');
    }
}
