<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    /**
     * Validation rules for listing data
     */
    private function validationRules($id = null)
    {
        return [
            'seller_id' => 'required|exists:users,id',
            'listable_type' => 'required|string|max:255|in:car,part',
            'listable_id' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:active,sold,hidden',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Listing::all(), 200);
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

        $listing = Listing::create($request->all());
        return response()->json($listing, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        return response()->json($listing, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->validationRules($id));

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $listing->update($request->all());
        return response()->json($listing, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        $listing->delete();
        return response()->json(null, 204);
    }
}