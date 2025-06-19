<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Models\Warehouse\Warehouse;
use App\Models\Warehouse\Car;
use App\Http\Controllers\Controller;


class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('cars')->get();

        return response()->json($warehouses);
    }

    /**
     * Show warehouse details and cars inside
     */
    public function show($id)
    {
        $warehouse = Warehouse::with('cars')->findOrFail($id);

        return response()->json([
            'warehouse' => $warehouse,
            'capacity_used' => $warehouse->cars->count(),
            'capacity_total' => $warehouse->capacity,
            'is_full' => $warehouse->cars->count() >= $warehouse->capacity
        ]);
    }

    /**
     * Store a car in a warehouse (typically from ManufacturerController)
     */
    public function storeCar(Request $request)
    {
        $validated = $request->validate([
            'car_name' => 'required|string',
            'model' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'manufacturer_id' => 'required|exists:users,id'
        ]);

        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);

        if ($warehouse->cars()->count() >= $warehouse->capacity) {
            return response()->json(['error' => 'Warehouse is full.'], 400);
        }

        Car::create([
            'name' => $validated['car_name'],
            'model' => $validated['model'],
            'warehouse_id' => $warehouse->id,
            'manufacturer_id' => $validated['manufacturer_id']
        ]);

        return response()->json(['message' => 'Car stored in warehouse.']);
    }

    /**
     * Create a new warehouse (Admin use)
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:warehouses,name',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $warehouse = Warehouse::create($validated);

        return response()->json([
            'message' => 'Warehouse created successfully.',
            'warehouse' => $warehouse
        ]);
    }

}
