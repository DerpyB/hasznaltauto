<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartController extends Controller
{
    /**
     * Validation rules for part data
     */
    private function validationRules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'condition' => 'required|in:new,used,refurbished',
            'compatible_make' => 'nullable|string|max:255',
            'compatible_model' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Part::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $part = Part::create($request->all());
        return response()->json($part, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $part = Part::find($id);

        if (!$part) {
            return response()->json([
                'message' => 'Part not found'
            ], 404);
        }

        return response()->json($part, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $part = Part::find($id);

        if (!$part) {
            return response()->json([
                'message' => 'Part not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->validationRules($id));

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $part->update($request->all());
        return response()->json($part, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $part = Part::find($id);

        if (!$part) {
            return response()->json([
                'message' => 'Part not found'
            ], 404);
        }

        $part->delete();
        return response()->json(null, 204);
    }
}