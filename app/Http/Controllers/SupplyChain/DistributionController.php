<?php

namespace App\Http\Controllers\SupplyChain;

use Illuminate\Http\Request;
use App\Models\SupplyChain\Shipment;
use App\Models\SupplyChain\Retailer;
use App\Models\Warehouse\Car;
use App\Models\User;
use App\Http\Controllers\Controller;

class DistributionController extends Controller
{
    public function assignTransport(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'retailer_id' => 'required|exists:retailers,id',
            'distributor_id' => 'required|exists:users,id',
            'transport_details' => 'required|string',
            'expected_delivery_date' => 'required|date',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        if ($car->shipment && $car->shipment->status !== 'Delivered') {
            return response()->json(['error' => 'Car is already assigned to a shipment and not delivered yet.'], 400);
        }

        $shipment = Shipment::create([
            'car_id' => $validated['car_id'],
            'retailer_id' => $validated['retailer_id'],
            'distributor_id' => $validated['distributor_id'],
            'transport_details' => $validated['transport_details'],
            'status' => 'In Transit',
            'expected_delivery_date' => $validated['expected_delivery_date'],
        ]);

        return response()->json(['message' => 'Transport assigned successfully.', 'shipment' => $shipment]);
    }

    /**
     * Track shipment status by shipment ID
     */
    public function trackShipment($id)
    {
        $shipment = Shipment::with(['car', 'retailer', 'distributor'])->findOrFail($id);

        return response()->json([
            'shipment' => $shipment,
            'car' => $shipment->car,
            'retailer' => $shipment->retailer,
            'distributor' => $shipment->distributor,
        ]);
    }

    /**
     * Update shipment status
     */
    public function updateShipmentStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:In Transit,Delivered,Delayed,Canceled',
            'actual_delivery_date' => 'nullable|date',
        ]);

        $shipment = Shipment::findOrFail($id);

        $shipment->status = $validated['status'];
        if (!empty($validated['actual_delivery_date'])) {
            $shipment->actual_delivery_date = $validated['actual_delivery_date'];
        }
        $shipment->save();

        return response()->json(['message' => 'Shipment status updated.', 'shipment' => $shipment]);
    }
}
