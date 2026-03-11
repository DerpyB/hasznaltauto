<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * Validation rules for car data
     */
    private function validationRules($id = null)
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|integer|min:0',
            'mileage' => 'required|integer|min:0',
            'engine_size' => 'nullable|integer|min:0',
            'horsepower' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'weight' => 'nullable|integer|min:0',
            'registration_valid_until' => 'nullable|date',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Car::all(), 200);
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

        $car = Car::create($request->all());
        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'message' => 'Car not found'
            ], 404);
        }

        return response()->json($car, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'message' => 'Car not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->validationRules($id));

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $car->update($request->all());
        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'message' => 'Car not found'
            ], 404);
        }

        $car->delete();
        return response()->json(null, 204);
    }
}