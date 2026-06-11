<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DynamicFieldController extends Controller
{
    public function index()
    {
        $dynamicFields = DynamicField::all();
        $allFields = collect();
        foreach(['family', 'group', 'vip'] as $type) {
            $statics = DynamicField::getStaticFields($type);
            $dynamics = $dynamicFields->where('form_type', $type);
            $combined = $statics->concat($dynamics)->sortBy('order')->values();
            $allFields = $allFields->concat($combined);
        }
        $fields = $allFields;
        return view('admin.dynamic_fields.index', compact('fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_type' => 'required|in:vip,family,group',
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,select,radio,checkbox,date,number,email',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'position' => 'nullable|string', // 'end', 'before_ID', 'after_ID'
        ]);

        $name = Str::slug($request->label, '_');

        if (DynamicField::where('form_type', $request->form_type)->where('name', $name)->exists()) {
            $name = $name . '_' . time();
        }

        $options = null;
        if (in_array($request->type, ['select', 'radio']) && $request->filled('options')) {
            $optionsArray = array_map('trim', explode(',', $request->options));
            $options = json_encode(array_filter($optionsArray));
        }

        // Determine the order
        $order = 0;
        $position = $request->input('position', 'end');
        
        if ($position == 'end' || empty($position)) {
            $maxOrder = DynamicField::where('form_type', $request->form_type)->max('order');
            // Check max order of static fields too
            $staticMax = DynamicField::getStaticFields($request->form_type)->max('order');
            $maxOrder = max((int)$maxOrder, (int)$staticMax);
            $order = $maxOrder + 10;
        } else {
            $parts = explode('_', $position);
            if (count($parts) >= 2) {
                $posType = array_shift($parts); // before or after
                $targetId = implode('_', $parts); // static_name or 5
                
                $targetOrder = null;
                if (str_starts_with($targetId, 'static_')) {
                    $statics = DynamicField::getStaticFields($request->form_type);
                    $targetStatic = $statics->firstWhere('id', $targetId);
                    if ($targetStatic) $targetOrder = $targetStatic->order;
                } else {
                    $targetField = DynamicField::find($targetId);
                    if ($targetField && $targetField->form_type == $request->form_type) {
                        $targetOrder = $targetField->order;
                    }
                }
                
                if ($targetOrder !== null) {
                    if ($posType == 'before') {
                        $order = $targetOrder - 1; // Since dynamic fields are spaced by 10s or placed tightly
                        DynamicField::where('form_type', $request->form_type)
                                    ->where('order', '>=', $order)
                                    ->increment('order');
                    } else if ($posType == 'after') {
                        $order = $targetOrder + 1;
                        DynamicField::where('form_type', $request->form_type)
                                    ->where('order', '>=', $order)
                                    ->increment('order');
                    }
                } else {
                    $maxOrder = DynamicField::where('form_type', $request->form_type)->max('order');
                    $staticMax = DynamicField::getStaticFields($request->form_type)->max('order');
                    $order = max((int)$maxOrder, (int)$staticMax) + 10;
                }
            }
        }

        DynamicField::create([
            'form_type' => $request->form_type,
            'label' => $request->label,
            'name' => $name,
            'type' => $request->type,
            'options' => $options ? json_decode($options, true) : null,
            'is_required' => $request->has('is_required'),
            'status' => true,
            'order' => $order,
        ]);

        return redirect()->back()->with('success', 'Form field added successfully!');
    }

    public function toggle(Request $request, $id)
    {
        $field = DynamicField::findOrFail($id);
        $field->status = !$field->status;
        $field->save();

        return redirect()->back()->with('success', 'Field status updated!');
    }

    public function destroy($id)
    {
        $field = DynamicField::findOrFail($id);
        $field->delete();

        return redirect()->back()->with('success', 'Field deleted successfully!');
    }
}
