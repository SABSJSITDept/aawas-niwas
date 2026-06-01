<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SadhuSadvi;
use App\Models\ThanaSant;
use Illuminate\Http\Request;

class SadhuSadviController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sadhuSadvis = SadhuSadvi::with('thanaSants')->withCount('thanaSants')->get();
            return response()->json([
                'success' => true,
                'data' => $sadhuSadvis,
                'message' => 'Sadhu/Sadvi list fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'link' => 'nullable|url',
                'thana' => 'required|integer|min:1',
                'thana_names' => 'nullable|array',
                'thana_names.*' => 'nullable|string|max:255',
            ]);

            $sadhuSadvi = SadhuSadvi::create([
                'name' => $validated['name'],
                'address' => $validated['address'] ?? null,
                'link' => $validated['link'] ?? null,
                'thana' => $validated['thana'],
            ]);

            // Save thana member names if thana > 1
            if (!empty($validated['thana']) && $validated['thana'] > 1 && !empty($validated['thana_names'])) {
                foreach ($validated['thana_names'] as $santName) {
                    if (!empty(trim($santName))) {
                        ThanaSant::create([
                            'thana_id' => $sadhuSadvi->id,
                            'sant_name' => trim($santName),
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $sadhuSadvi,
                'message' => 'Sadhu/Sadvi added successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $sadhuSadvi = SadhuSadvi::with('thanaSants')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $sadhuSadvi,
                'message' => 'Sadhu/Sadvi fetched successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sadhu/Sadvi not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $sadhuSadvi = SadhuSadvi::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'address' => 'nullable|string',
                'link' => 'nullable|url'
            ]);

            $sadhuSadvi->update($validated);

            return response()->json([
                'success' => true,
                'data' => $sadhuSadvi,
                'message' => 'Sadhu/Sadvi updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sadhu/Sadvi not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $sadhuSadvi = SadhuSadvi::findOrFail($id);
            $sadhuSadvi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sadhu/Sadvi deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sadhu/Sadvi not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting data: ' . $e->getMessage()
            ], 500);
        }
    }
}
