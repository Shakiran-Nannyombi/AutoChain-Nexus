<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturerOrderController extends Controller
{
    public function show($orderId)
    {
        $order = DB::table('vendor_orders')->where('id', $orderId)->first();
        if (!$order) {
            abort(404, 'Order not found');
        }
        return view('dashboards.manufacturer.order-show', compact('order'));
    }
} 