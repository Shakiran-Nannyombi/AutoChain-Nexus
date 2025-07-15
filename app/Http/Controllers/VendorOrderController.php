<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|in:manufacturer,vendor',
            'partner_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
            'special_instructions' => 'nullable|string',
        ]);
        // TODO: Save order to database
        return response()->json(['success' => true, 'message' => 'Order created successfully!']);
    }
}
