<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalDataController extends Controller
{
    public function getVendorOrderData(Request $request)
    {
        // Internal Secret Check (Basic security for internal Render communication)
        // If not set in env, defaults to 'changeme' (should be set in production!)
        $secret = env('INTERNAL_API_SECRET', 'changeme');
        
        // Check header (allow 'X-Internal-Secret' or 'Authorization: Bearer <secret>')
        $authHeader = $request->header('X-Internal-Secret');
        
        if (!$authHeader || $authHeader !== $secret) {
             return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Fetch fulfilled orders
        $data = DB::table('vendor_orders')
            ->select('product as car_model', 'quantity as quantity_sold', 'ordered_at as created_at', 'manufacturer_id as region')
            ->where('status', 'fulfilled')
            ->get();

        return response()->json($data);
    }
}
